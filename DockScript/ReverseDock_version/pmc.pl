#! /usr/bin/perl -w
#==job name============
#PBS -N mc
#===resources==============
#PBS -l nodes=cnode14:ppn=32,walltime=144:00:00              
#PBS -q batch
#PBS -V
use strict;
use threads; 
use threads::shared;
use Thread::Queue;
use Thread::Semaphore;
sub Main{
	my $MAX_THREADS=16;
	my $semaph= Thread::Semaphore->new($MAX_THREADS);	#创建信号量，以控制线程调度
	my($taskQue,$lig_name,$path) = parseCmd();		#解析命令和参数文件，生成线程安全的任务队列
	Log("Starting MC docking for $lig_name");
	my $childs=jump2parallel($MAX_THREADS,$taskQue,$semaph,$lig_name,$path);#进入并行计算状态
	my $score=wait4childs($childs,$MAX_THREADS,$semaph); 

	foreach my$key(sort{$score->{$a}<=>$score->{$b}} keys %{$score}){	# <=>为按值排序		cmp按ASCII码表排序
		print "$key,$score->{$key}\n";
	}
	Log("Ending MC docking for $lig_name");
	return 0;
}
exit(Main);	

#==sub rout====================================##
sub parseCmd{
	my $USG="usage:\n\tqsub -v lig=ARIP.pdbqt,param=dock.param\nor:\n\t$0 <lig.pdbqt> <paramfile>";
	#===确保路径安全==============
	my $path=($ENV{PBS_O_WORKDIR} ? $ENV{PBS_O_WORKDIR} : $ENV{PWD} );
	chdir $path;		
	#===检查配体文件==============
	my $lig_f=($ENV{lig} ? $ENV{lig} : $ARGV[0]);
	die "ERR:No liagnd file given! $USG\n" unless $lig_f;
	die "HeavAtom num of $lig_f is too small,keep it greater than 6!\n" unless checkFile($lig_f,588);
	my $lig_name = $1 if($lig_f =~/(\w+)\.pdbqt$/o);
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
		#print $param;
		my $argv=parseParam($lig_name,$param,$path);
		next if!(defined $argv);
		next if!(checkProt($argv));
		my $score=run($argv);
		next if!(defined $score);
		$hash{$argv->[9]}=$score;	#收录各个位点处的vina最高打分
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
	push(@argv,"$path/$arr[0]/$arr[0].pdbqt");
	push(@argv,"$path/$lig_name.pdbqt");	#配体文件使用全路径
	push(@argv,0.5*(0.0+$arr[2]+$arr[3]) );
	push(@argv,0.5*(0.0+$arr[4]+$arr[5]) );
	push(@argv,0.5*(0.0+$arr[6]+$arr[7]) );
	push(@argv,0.0+$arr[3]-$arr[2] );
	push(@argv,0.0+$arr[5]-$arr[4] );
	push(@argv,0.0+$arr[7]-$arr[6] );
	push(@argv,"$path/$arr[0]/$arr[1]/$lig_name\_mc");		#输出文件名
	push(@argv,"$arr[0]/$arr[1]");
	return \@argv;
}

sub checkProt{
	my $argv=shift;

	if(!stat($argv->[0])){		
		print STDERR "No such file or cannot access prot: $argv->[0]!\n";
		return undef;
	}
	my @prot_info=stat($argv->[0]);
	if($prot_info[7] < 35000){
		print STDERR "Residue num of $argv->[0] is too small,keep it greater than 100!\n";
		return undef;
	}
	return $prot_info[7];
}

sub run{
	my $argv =shift;
	my @bindEnergy;
	
	my $cmd="/yp_home/licz/bin/Vina/mc_vina  --receptor $argv->[0] --ligand $argv->[1] ";
	$cmd.=	"--center_x $argv->[2] --center_y $argv->[3] --center_z $argv->[4] ";
	$cmd.=	"--size_x $argv->[5] --size_y $argv->[6] --size_z $argv->[7] ";
	$cmd.=	"--out $argv->[8].pdbqt";
	#print "$cmd\n";
	if(!open MC,'-|',"$cmd 2>$argv->[8].err"){
		print STDERR "Error occured during the MC docking of $argv->[8]\n";
		return undef;
	}
	while(<MC>){
		if(/^   \d/o){
			my @split = split;
			$split[1] = 0 + $split[1];
			push(@bindEnergy, $split[1]);
		}
	}
	close MC;
	delZero("$argv->[8].err");
	return $bindEnergy[0] if($bindEnergy[0]);
	
	return undef;
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
	$mon ++; # $mon是从0开始计数的，所以$mon需要加上1
	printf("$content at %d-%02d-%02d %02d:%02d:%02d\n",$year,$mon,$mday,$hour,$min,$sec);
}
