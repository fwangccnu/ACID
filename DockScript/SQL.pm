package SQL;
use strict;
use threads; 
use threads::shared;
use Thread::Queue;

my $h = '192.168.3.20';
my $u = 'user';
my $p = 'user@724';
my $db= 'reverse_dock';
my @tskSet;					#存放数组形式的任务队列

sub new{
	my ($class,$job_id) = @_;
	defined $job_id or return undef;
	my $tar_set = sqlQuery("SELECT target_sets FROM $db.jobs_reversedock WHERE job_id='$job_id'");
	(defined $tar_set->[0] and $tar_set->[0]!~/^\s*$/o) ? chomp $tar_set->[0] : return undef;
	my $upload_file = sqlQuery("SELECT file_path FROM $db.jobs_reversedock WHERE job_id='$job_id'");
	(defined $upload_file->[0] and $upload_file->[0]!~/^\s*$/o) ? chomp $upload_file->[0] : return undef;
	
	my ($THR_NUM,$rFunc,%prot,%vote,%mc,%le,%plt,%pso,%pbsa);
	my $this={
		'THR_NUM' 		=> $THR_NUM,	#线程数目
		'FUNC'			=> $rFunc,		#任务单元函数
		'job_id'		=> $job_id,		#任务编号
		'file'			=> $upload_file->[0],#服务器上传的文件
		'tar_set'		=> $tar_set->[0],	#蛋白集合
		'ligand'		=> 'LIG',		#计算时，配体名统一用'LIG'
		'number'		=> 500,
		'prot'			=> \%prot,		#pdb与protID之间的对应
		'vote'			=> \%vote,		#pdb,site与xscore分数间的关系
		'mc'			=> \%mc,		#pdb,site与vina score分数间的关系
		'le'			=> \%le,		#pdb,site与ledock score分数间的关系
		'plt'			=> \%plt,		#pdb,site与plt score分数间的关系
		'pso'			=> \%pso,		#pdb,site与PSO score分数间的关系
		'pbsa'			=> \%pbsa		#pdb,site与PSO score分数间的关系
	};
	
	bless $this,$class;
	return $this;
}

sub setThrNum{ my ($this,$n)= @_; $this->{THR_NUM} = $n; }
sub	setFUNC	 { my ($this,$rFunc)= @_; $this->{FUNC} = $rFunc;}

#===ReverseDock与Paralell之间就以$h对象为纽带进行信息传递====
sub setTskQue{
	my ($this)= @_;
	my $rs;
	my $sql = 'SELECT pdbID,siteID,x1,x2,y1,y2,z1,z2,protID FROM reverse_dock.screen_table WHERE class IN';
	if($this->{tar_set} eq 'ALL'){
		$rs= sqlQuery("SELECT pdbID,siteID,x1,x2,y1,y2,z1,z2,protID FROM reverse_dock.screen_table");
	}else{
		my @arr = split / /,$this->{tar_set};
		$sql .= "('" . join("','",@arr) . "')";
		$rs= sqlQuery($sql);
	}
	my $n = scalar(@{$rs});
	$this->{number} = $n/2 ;
	print "$n seperated task in total\n";
	foreach my$param(@{$rs}){
		my @arr = split /\s+/,$param;
		(8 == $#arr) or die "ERR: param wrong with: @arr\n";	
		my %hash;
		$hash{'lig'} = $this->{'ligand'};
		$hash{'pdb'} = $arr[0];
		$hash{'site'}= $arr[1];
		$hash{'x1'}  = $arr[2];
		$hash{'x2'}  = $arr[3];
		$hash{'y1'}  = $arr[4];
		$hash{'y2'}  = $arr[5]; 
		$hash{'z1'}  = $arr[6];
		$hash{'z2'}  = $arr[7];
		$hash{'Cx'}  = 0.5*(0.0+$arr[2]+$arr[3]);
		$hash{'Cy'}  = 0.5*(0.0+$arr[4]+$arr[5]);
		$hash{'Cz'}  = 0.5*(0.0+$arr[6]+$arr[7]);
		$hash{'Lenx'}  = 0.0+$arr[3]-$arr[2];
		$hash{'Leny'}  = 0.0+$arr[5]-$arr[4];
		$hash{'Lenz'}  = 0.0+$arr[7]-$arr[6];
		$hash{'R'}	 = max($hash{'Lenx'},$hash{'Leny'},$hash{'Lenz'});
		
		$this->{'prot'}->{$arr[0]} = $arr[8];
		push(@tskSet,\%hash);
	}
}

sub start{	#start 启动工作线程启动工作线程
	my($this) = @_;
	my $tskQue = Thread::Queue->new(@tskSet);
	for(my $i=0; $i < $this->{THR_NUM}; ++$i){
		threads->create(\&do_task,$this,$i,$tskQue);
	}
	#获取没有被join没有被detach的线程的数目
	while((my $thr_num = threads->list(threads::all) ) > 0){
		sleep(1);
	}
}

sub do_task{	#do_task不断地从人物队列中取出任务，交由相关的功能函数进行处理
	my ($this,$i,$tskQue) = @_;
	while(my $tsk = $tskQue->dequeue_nb() ){ 
		next if!(defined $tsk);
		#next if!(checkProt($argv));
		$this->{FUNC}->($tsk,$i);		#$tsk对象储存参数, $i储存标号
	}
	threads->detach();		#当任务运行结束时，将自己剥离开
}

sub dealOutErr{
	my($this,$suffix) = @_;
	if(!defined($suffix) ){
		print STDERR "No suffix given!Fail to sort results\n";
		return undef;
	}
	$suffix eq 'pbsa' ?
		`sort -n -k 5 $this->{ligand}_*.$suffix >>$this->{ligand}.$suffix` 
	:
		`sort -n -k 3 $this->{ligand}_*.$suffix >>$this->{ligand}.$suffix`;
	unlink glob "$this->{ligand}_*.$suffix";
	my $err_num = `ls |awk 'BEGIN{n=0}/_$suffix.err/{++n}END{print n}'`;
	chomp $err_num;
	print "###$suffix finished! \terr_num: $err_num####\n";
	return 0+$err_num;
}

sub get_res{
	my($this,$suffix) = @_;
	if(!stat "$this->{ligand}.$suffix"){
		print STDERR "Cannot access $this->{ligand}.$suffix !\n" ; 
		return undef;		#若没生成文件则失败
	}
	open(SCORE,"<$this->{ligand}.$suffix");
	while(<SCORE>){
		my @arr = split;
		$this->{$suffix}->{"$arr[0] $arr[1]"} = $arr[2] if($arr[2] ne 'null');
	}
	close SCORE;
}

sub store_res{
	my($this) = @_;
	$this->get_res('mc');
	$this->get_res('le');
	$this->get_res('pso');
	$this->get_res('plt');
	$this->get_res('vote');
		sqlExec("DROP TABLE IF EXISTS $db.reversedock_$this->{job_id};CREATE TABLE $db.reversedock_$this->{job_id} SELECT * FROM $db.reversedock_job_id WHERE 1=2");
		my @data_set;
		my $i;
		open(PBSA,"<$this->{ligand}.pbsa");
		while(<PBSA>){
			my @arr = split;
			if($arr[2] ne 'null'){
				my $a =shift @arr;
				my $b =shift @arr;
				my $data = "('$this->{prot}->{$a}','$a','$b'," . join(',',@arr) ;
				$data .= ','.(defined $this->{vote}->{"$a $b"} ? $this->{vote}->{"$a $b"} : 'NULL');
				$data .= ','.(defined $this->{mc}->{"$a $b"} ? $this->{mc}->{"$a $b"} : 'NULL');
				$data .= ','.(defined $this->{le}->{"$a $b"} ? $this->{le}->{"$a $b"} : 'NULL');
				$data .= ','.(defined $this->{pso}->{"$a $b"} ? $this->{pso}->{"$a $b"} : 'NULL');
				$data .= ','.(defined $this->{plt}->{"$a $b"} ? $this->{plt}->{"$a $b"} : 'NULL');
				$data .= ','."'$a\_$b\_$this->{ligand}_pbsa.pdb')";
				push(@data_set,$data);
			}
			if(++$i >= 100){
				my $sql = "INSERT INTO $db.reversedock_$this->{job_id} VALUES" . join(',',@data_set);
				sqlExec($sql);
				$i = 0;			#计数器归零
				@data_set = ();	#清空数据集
			}
		}
		close PBSA;
		my $sql = "INSERT INTO $db.reversedock_$this->{job_id} VALUES" . join(',',@data_set);
		sqlExec($sql);
}

sub getStage{
	my ($this) = @_ ;
	my $rs = sqlQuery("SELECT cal_stage FROM $db.jobs_reversedock WHERE job_id='$this->{job_id}'");
	chomp $rs->[0] if(defined $rs->[0]);
	return $rs->[0];
}

sub getStatus{
	my ($this) = @_ ;
	my $rs = sqlQuery("SELECT status FROM $db.jobs_reversedock WHERE job_id='$this->{job_id}'");
	chomp $rs->[0] if(defined $rs->[0]);
	return $rs->[0];
}

sub enQue{
	my ($this,$node) = @_ ;
	my $sql = "UPDATE $db.jobs_reversedock SET cal_stage='WAIT',status='QUEUE',node='$node' WHERE job_id='$this->{job_id}'";
	my @errs = `mysql -h$h -u$u -p$p -e \"$sql\" 2>&1 | grep -E '^ERROR'`;
	if(defined($errs[0]) ){
		LogErr("Fial to enQUe #$sql#\n@errs###########\n","Mysql.err") ;
		return undef;
	}else{
		return -1;
	}
}

#stage的几种状态WAIT,MC,PSO,PLT,LE,VOTE,PBSA,DONE，若发生SQL语句执行失败，手动check，手动更改后台状态
sub setStage{
	my ($this,$stage) = @_;
	my $sql = "UPDATE $db.jobs_reversedock SET cal_stage='$stage' WHERE job_id='$this->{job_id}'";
	sqlExec($sql);
}
#status的几种状态QUEUE,RUNNING,ERROR,FINISH，若发生SQL语句执行失败，手动check，手动更改后台状态
sub setStatus{
	my ($this,$status) = @_;
	my $sql = "UPDATE $db.jobs_reversedock SET status='$status'  WHERE job_id='$this->{job_id}'";
	sqlExec($sql);
}

sub Finish{
	my ($this) = @_ ;
	my $stage = $this->getStage();
	if(defined $stage and $stage eq 'DONE'){
		$this->setStatus('FINISHED');
	}else{
		#$this->setStatus('ERROR');
		$this->setStatus('RUNNING');	#试运行期间不能出现ERROR，用RUNNING代替
		return undef;
	}
	$this->store_res();
}

1;

#===以下的函数，与$this没有联系===============================================
sub sqlQuery{
	my ($sql) = @_ ;
	my @rs = `mysql -h$h -u$u -p$p -e \"$sql\"`;
	shift @rs;
	if(!defined($rs[0])){
		my @err = `mysql -h$h -u$u -p$p -e \"$sql\" 2>&1 | grep -E '^ERROR'`;
		LogErr("Fial to exec #$sql#\n@err###########\n","Mysql.err");
		return undef;
	}
	#print "$rs[0]\n";
	return \@rs;
}

sub sqlExec{
	my ($sql) = @_ ;
	my @errs = `mysql -h$h -u$u -p$p -e \"$sql\" 2>&1 | grep -E '^ERROR'`;
	if(defined($errs[0]) ){
		LogErr("Fial to exec #$sql#\n@errs###########\n","Mysql.err");
		return undef;
	}else {
		return -1;
	}
}

sub Random{
	my($low,$high) = @_;
	srand();
	my $len = $high - $low + 1;
	my $n =int(rand($len));
	return ($low + $n);
}

sub max{
	my ($a,$b,$c) = @_ ;
	my $max = 0;
	foreach my $e(@_){
		$max = $e if($e > $max);
	}
	return $max;
}

sub getDateTime{
	my ($sec,$min,$hour,$mday,$mon,$year,$wday,$yday,$isdst) = localtime(time());
	$year += 1900; # $year是从1900开始计数的，所以$year需要加上1900
	$mon ++; # $mon是从0开始计数的，所以$mon需要加上1
	return (sprintf("%d-%02d-%02d",$year,$mon,$mday), sprintf("%02d:%02d:%02d",$hour,$min,$sec) );
}

sub LogErr{
	my ($conten,$file) = @_;
	my ($date,$time) = getDateTime();
	if(defined($file)){
		open(ERR,">>$file");
		print ERR "$date $time# $conten\n";
		close ERR;
	}else{
		print STDERR "$date $time# $conten\n";
	}
}
