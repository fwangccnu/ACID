#! /usr/bin/perl -w
#==job name============
#PBS -N plt
#===resources==============
#PBS -l nodes=cnode17:ppn=32,walltime=144:00:00              
#PBS -q batch
#PBS -V
use strict;
use threads; 
use threads::shared;
use Thread::Queue;
use Thread::Semaphore;
my $RADIUS=20;
sub Main{
	my $MAX_THREADS=32;
	my $semaph= Thread::Semaphore->new($MAX_THREADS);	#创建信号量，以控制线程调度
	my($taskQue,$lig_name,$path) = parseCmd();		#解析命令和参数文件，生成线程安全的任务队列
	Log("Starting PLT docking for $lig_name");
	my $childs=jump2parallel($MAX_THREADS,$taskQue,$semaph,$lig_name,$path);#进入并行计算状态
	my $score=wait4childs($childs,$MAX_THREADS,$semaph); 

	foreach my$key(sort{$score->{$a}<=>$score->{$b}} keys %{$score}){	# <=>为按值排序		cmp按ASCII码表排序
		print "$key,$score->{$key}\n";
	}
	Log("Ending PLT docking for $lig_name");
	return 0;
}
exit(Main);
	
#==sub rout====================================##
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
		next if!(checkProt($argv));
		prepareConfig($argv);
		my $score=run($argv);
		next if!(defined $score);
		$hash{$argv->[6]}=$score;	
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
	push(@argv,"$path/$arr[0]/$arr[0].mol2");
	push(@argv,"$path/$lig_name.mol2");	#配体文件使用全路径
	push(@argv,0.5*(0.0+$arr[2]+$arr[3]) );
	push(@argv,0.5*(0.0+$arr[4]+$arr[5]) );
	push(@argv,0.5*(0.0+$arr[6]+$arr[7]) );
	push(@argv,"$path/$arr[0]/$arr[1]/$lig_name\_plt");		
	push(@argv,"$arr[0]/$arr[1]");	
	push(@argv,$path);
	return \@argv;
}

sub checkProt{
	my $argv=shift;
	
	if(!stat($argv->[0])){		
		print STDERR "No such file or cannot access prot: $argv->[0]!\n";
		return undef;
	}
	my @prot_info=stat($argv->[0]);
	if($prot_info[7] < 80000){
		print STDERR "Residue num of $argv->[0] is too small,keep it greater than 100!\n";
		return undef;
	}
	return $prot_info[7];
}

sub prepareConfig{
	my $argv =shift;
	my ($prot,$lig,$x,$y,$z) = @{$argv};
	my $content="bindingsite_center $x $y $z\n";
	$content .= "bindingsite_radius $RADIUS\n";
	$content .= "scoring_function chemplp\n";
	#$content .= "cluster_rmsd 2.0\n";
	$content .= "cluster_structures 15\n";
	$content .= "protein_file $prot\n";		#prot的位置使用绝对路径指定
	$content .= "ligand_file $lig\n";		#lig也是用绝对路径指定
	$content .= "output_dir $argv->[5]\n";	#同时指定一个工作路径，保护输出结果
	$content .= "write_protein_conformations 0\n";	
	$content .= "write_protein_bindingsite 0\n";	
	
	open(CONF,">$argv->[5].conf");
	print CONF $content;
	close CONF;
	unlink glob "$argv->[5]/*";
	rmdir $argv->[5];
	return 0;
}

sub run{
	my ($argv) = @_;
	return undef unless checkFile("$argv->[5].conf",150);
	#===正常运行的plants，若成功返回0;任何非零值表示失败===============
	my $stat=system "/yp_home/licz/bin/Plants/plants --mode screen $argv->[5].conf >/dev/null 2>$argv->[5].err";
	delZero("$argv->[5].err");					#失败退出前清理干净生成的垃圾文件
	print STDERR "Error occured during the PLT docking of $argv->[5]!\n$!\n" and clear_all($argv->[5]) and
	return undef if $stat;
	#==================================================================
	return do_cluster($argv->[5]);
}

sub do_cluster{
	my $arg=shift;	
	rename "$arg/ranking.csv","$arg.score";	#plants基于CHEMPLP打分函数
	
	return undef unless checkFile("$arg/docked_ligands.mol2",100);	##保护外部程序的输入文件安全
	#=====正常运行的cluster，若成功返回0;任何非零值表示失败===============
	my $stat=system "/yp_home/licz/bin/cluster $arg/docked_ligands.mol2 2.0 >$arg.mol2"; 
	print STDERR "Error occured during cluster $arg.mol2\n$!\n" and 
	rename "$arg/docked_ligands.mol2","$arg\_ligan.mol2" if $stat;
	#==================================================================
	clear_all($arg);
	
	print STDERR "Not score file $arg.score generated!\n" and 
	return undef unless open(SCORE,"<$arg.score");
	<SCORE>;
	my $line=<SCORE>;
	my @arr=split /,/,$line;
	print STDERR "No score generated for $arg\n" and
	return undef unless $arr[1];
	return $arr[1];
}

sub clear_all{
	my $arg=shift;
	unlink "$arg.conf";
	unlink glob "$arg/*";
	rmdir $arg;
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
	
	print STDERR "Size of $file is too small,keep it greater than $min_siz!\n" and
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
