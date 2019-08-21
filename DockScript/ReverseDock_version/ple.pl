#! /usr/bin/perl -w
#==job name============
#PBS -N le
#===resources==============
#PBS -l nodes=cnode16:ppn=32,walltime=144:00:00              
#PBS -q batch
#PBS -V
use strict;
use strict;
use threads; 
use threads::shared;
use Thread::Queue;
use Thread::Semaphore;
sub Main{
	my $MAX_THREADS=32;
	my $semaph= Thread::Semaphore->new($MAX_THREADS);	#创建信号量，以控制线程调度
	my($taskQue,$lig_name,$path) = parseCmd();		#解析命令和参数文件，生成线程安全的任务队列
	controlVersion();
	Log("Starting LE docking for $lig_name");
	my $childs=jump2parallel($MAX_THREADS,$taskQue,$semaph,$lig_name,$path);#进入并行计算状态
	my $score=wait4childs($childs,$MAX_THREADS,$semaph); 

	foreach my$key(sort{$score->{$a}<=>$score->{$b}} keys %{$score}){	# <=>为按值排序		cmp按ASCII码表排序
		print "$key,$score->{$key}\n";
	}
	Log("Ending LE docking for $lig_name");
	return 0;
}
exit(Main);
	
#==sub rout====================================##
sub controlVersion{
	###version control############
	open(VERSION,"</etc/redhat-release");
	my $version=<VERSION>;
	close VERSION;
	chomp $version;
	$ENV{'LD_LIBRARY_PATH'}='/yp_home/licz/bin/Ledock/libc' if($version !~ /Linux release 7\./);	#
}

sub parseCmd{
	my $USG="usage:\n\tqsub -v lig=ARIP.mol2,param=dock.param\nor:\n\t$0 <lig.mol2> <paramfile>";
	#===确保路径安全==============
	my $path=($ENV{PBS_O_WORKDIR} ? $ENV{PBS_O_WORKDIR} : $ENV{PWD} );
	chdir $path;		
	#===检查配体文件==============
	my $lig_f=($ENV{lig} ? $ENV{lig} : $ARGV[0]);
	die "ERR:No liagnd file given! $USG\n" unless $lig_f;
	die "HeavAtom num of $lig_f is too small,keep it greater than 6!\n" unless checkFile($lig_f,1301);
	my $lig_name = $1 if($lig_f =~/(\w+)\.mol2$/o);
	#===检查参数文件===========
	my $param_f=($ENV{param} ? $ENV{param}: $ARGV[1]);
	die "ERR:No params file given! $USG\n" unless $param_f;
	die "No such file or cannot access param file: $param_f\n" unless open(PARAM,$param_f);
	my @param = <PARAM> and close PARAM;
	die "File $param_f is empty\n" if(!$param[0]);
	
	my $task_que=Thread::Queue->new(@param);	#根据已有列表创建共享队列【线程安全】
	return ($task_que,$lig_name,$path);
}

sub jump2parallel{
	my ($child_num,$taskQue,$semaph,$lig_name,$path)=@_;
	my @childs;
	for(my $i=0;$i<$child_num;$i++){
		$semaph->down();	#信号量还存在资源【即还没降低到0】，down()才会返回，否则处于阻塞状态
		my $thread=threads->create(\&do_task,$taskQue,$semaph,$lig_name,$path);
		push @childs,$thread;
	}
	return \@childs;
}

sub do_task{
	my($taskQue,$semaph,$lig_name,$path)=@_;
	my %hash;
	while(my $param=$taskQue->dequeue_nb()){
		my $argv=parseParam($lig_name,$param,$path);
		next if!(defined $argv);
		next if!(checkProt($argv) );
		next if!(prepareConfig($argv) );
		my $score=run($argv);
		next if!(defined $score);
		$hash{$argv->[9]}=$score;	
	}
	$semaph->up();
	return \%hash;
}

sub wait4childs{
	my ($childs,$running_num,$semaph)=@_;
	while($running_num > 0){
		$semaph->down();
		$running_num --;
		#print "running childs: $running_num\n";
	}
	
	my %score;
	foreach my$thread(@{$childs}){	#回收子线程资源和结果
		my $rScore=$thread->join();
		%score=(%score,%{$rScore});
	}
	return \%score;
}

sub parseParam{
	my ($lig_name,$param,$path) = @_;
	chomp $param;
	my @arr = split /\s+/,$param;
	if(7 != $#arr){
		print STDERR "ERR: param wrong with: @arr\n";
		return undef ;
	}
	my @argv;
	push(@argv,"$path/$arr[0]/$arr[0].pdb");
	push(@argv,"$path/$lig_name.mol2");	#配体文件使用全路径
	push(@argv,$arr[2] );
	push(@argv,$arr[3] );
	push(@argv,$arr[4] );
	push(@argv,$arr[5] );
	push(@argv,$arr[6] );
	push(@argv,$arr[7] );
	push(@argv,"$path/$arr[0]/$arr[1]/$lig_name\_le");		
	push(@argv,"$arr[0]/$arr[1]");	
	return \@argv;
}

sub checkProt{
	my ($argv)=@_;
	if(!stat($argv->[0])){		
		print STDERR "No such file or cannot access prot: $argv->[0]!\n";
		return undef;
	}
	my @prot_info=stat($argv->[0]);
	if($prot_info[7] < 50000){
		print STDERR "Residue num of $argv->[0] is too small,keep it greater than 100!\n";
		return undef;
	}
	return $prot_info[7];
}

sub prepareConfig{
	my ($argv)=@_;
	my ($prot,$lig,$x1,$x2,$y1,$y2,$z1,$z2,$tsk_name)= @{$argv};
	my $content="Receptor\n$prot\n";
	$content .= "RMSD\n2.0\n";			#suitable for highthroughput virtual screening
	$content .= "Binding pocket\n";
	$content .= "$x1 $x2\n";
	$content .= "$y1 $y2\n";
	$content .= "$z1 $z2\n";
	$content .= "Number of binding poses\n10\n";
	$content .= "Ligands list\n$tsk_name.list\nEND\n";
	open(CONF,">$tsk_name.conf");
	print CONF $content;
	close CONF;
	
	print STDERR "Fail to create symlink $tsk_name.mol2" and 
	return undef unless symlink $lig,"$tsk_name.mol2";		#创建符号链接
	
	open(LIST,">$tsk_name.list");
	print LIST "$tsk_name.mol2";		#Ledock自动使用配体所在的路径作为工作路径
	close LIST;
	return 1;
}

sub run{
	my ($argv) =@_;
	#===鉴于ledock的不稳定表现，增加检查等保护措施=========
	return undef unless checkFile("$argv->[8].list",6);
	return undef unless checkFile("$argv->[8].mol2",1301); #防止符号链接的创建失败造成影响
	#===========================================
	#===正常运行的ledock，并不向终端显示任何输出,若成功返回0;任何非零值表示失败=========
	my $stat=system "/yp_home/licz/bin/Ledock/ledock $argv->[8].conf 2>$argv->[8].err";
	unlink "$argv->[8].list";
	unlink "$argv->[8].mol2";
	unlink "$argv->[8].conf";
	delZero("$argv->[8].err");
	print STDERR "Error occured during the LE docking of $argv->[8]!\n$!\n"	and
	return undef if $stat;							
	#============================================================	
	return deal_dok($argv->[8]);
}

sub deal_dok{
	my ($tsk_name) =@_;
	my $conf=[[]];
	my $idex=1;
	my @score;

	print STDERR "Fail to open original $tsk_name.dok!\n" and 
	return undef unless open(DOK,"<$tsk_name.dok");
	while(<DOK>){
		#chomp;
		if(/^REMARK.{26}Score: (.+)kcal\/mol/o){
			push(@score,$1);
			push @{$conf->[$idex]},"MODEL $idex\nCOMPND    $idex\nAUTHOR    LCZ\n";
			push @{$conf->[$idex]},$_;
		}elsif(/^(ATOM.{8})(.{4})(.+)/o){
			my($ATM,$atm)=fix_atm($2);
			my $line;
			if(defined $ATM and defined $atm){
				$line="$1$ATM$3  1.00  0.00          $atm  \n";
			}else{
				$line=$_;
			}
			push @{$conf->[$idex]},$line;
		}elsif(/^ATOM.{8} ([A-Z]) /o){
			chomp $_;
			my $line="$_  1.00  0.00           $1  \n";
			push @{$conf->[$idex]},$line;
		}elsif(/^END/o){
			push @{$conf->[$idex]},$_;
			push @{$conf->[$idex]},"ENDMDL\n";
			$idex ++;
		}
	}
	close DOK;
	#unlink "$tsk_name.dok";
	
	open(PDB,">$tsk_name.pdb");
		print PDB @{$_} foreach @{$conf};
	close PDB;
	
	print STDERR "No score generated for $tsk_name\n" and
	return undef unless $score[0];
	
	return $score[0];
}

sub fix_atm{
	my $dok =shift;
	my $ATM;
	my $atm;
	if($dok =~/^ ?(CL|BR|SI|AS|SE)/o){
		$ATM="$1  ";
		$atm=substr($1,0,1).lc(substr($1,1,1))."  ";
	}elsif($dok =~/^ ?(C|H|O|N|S|P|F|I|B)/){
		$ATM=" $1  ";
		$atm=" $1  ";
	}
	return ($ATM,$atm);
}

sub delZero{
	my $file=shift;
	return undef unless stat($file);
	my @info=stat($file);
	unlink $file if(0 == $info[7]);
}

sub checkFile{
	my($file,$min_siz)=@_;
	
	print STDERR "No such file or cannot access: $file!\n" and 
	return undef unless stat($file);

	my @info=stat($file);
	
	print STDERR "$file is empty or size too small!\n" and
	return undef if($info[7] < $min_siz);
	
	return $info[7];
}

sub Log{
	my $content=shift;
	my $time= time();
	my ($sec,$min,$hour,$mday,$mon,$year,$wday,$yday,$isdst) = localtime($time);
	$year += 1900; # $year是从1900开始计数的，所以$year需要加上1900
	$mon += 1; # $mon是从0开始计数的，所以$mon需要加上1
	printf("$content at %d-%02d-%02d %02d:%02d:%02d\n",$year,$mon,$mday,$hour,$min,$sec);
}