#! /usr/bin/perl -w
#PBS -N rankbyvote
#PBS -l nodes=cnode17:ppn=32,walltime=144:00:00              
#PBS -q batch
#PBS -V
use strict;
use threads; 
use threads::shared;
use Thread::Queue;
use Thread::Semaphore;
sub Main{
	my $MAX_THREADS=32;
	my $semaph= Thread::Semaphore->new($MAX_THREADS);	#创建信号量，以控制线程调度
	my($taskQue,$lig_name,$path) = parseCmd();		#解析命令和参数文件，生成线程安全的任务队列
	Log("Starting RankByVote for $lig_name");
	print "pdb  lig,i,v,score\n";
	my $childs=jump2parallel($MAX_THREADS,$taskQue,$semaph,$lig_name,$path);#进入并行计算状态
	my $score=wait4childs($childs,$MAX_THREADS,$semaph); 

	foreach my$key(sort{$score->{$a}<=>$score->{$b}} keys %{$score}){	# <=>为按值排序		cmp按ASCII码表排序
		print "$key,$score->{$key}\n";
	}
	Log("Ending Job");
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
	while(my $task=$taskQue->dequeue_nb()){
		my @arr=split /\s+/,$task;
		my $name="$path/$arr[0]/$arr[1]/$lig_name";
		my $prot="$path/$arr[0]/$arr[0]\_r.pdb";
		my $vt=vote($name);
		next unless $vt;
		my $sc=score($name,$prot);
		next unless $sc;
		#print "$arr[0]/$arr[1],$vt,$sc\n";
		$hash{"$arr[0]/$arr[1],$vt"}=$sc;
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

sub vote{
	my $name=shift;
	return undef unless checkFile("$name\_mc.pdbqt",588);
	return undef unless checkFile("$name\_pso.pdbqt",588);
	return undef unless checkFile("$name\_plt.mol2",588);
	return undef unless checkFile("$name\_le.pdb",100);
	my $cmd="rankbyvote $name\_mc.pdbqt  $name\_pso.pdbqt $name\_le.pdb $name\_plt.mol2";
	my $stat=system "$cmd >$name\_vote.mol2 2>$name\_vote.err";
	delZero("$name\_vote.mol2");
	delZero("$name\_vote.err");
	print STDERR "Error occured during the VOTE of $name!\n$!\n" and
	return undef if $stat;
	
	print STDERR "No vote file generated for $name\n" and
	return undef unless open(VOTE,"<$name\_vote.mol2");
	<VOTE>;
	my $line=<VOTE>;
	close VOTE;
	chomp $line;
	my @arr =split /\s+/,$line;
	print STDERR "No vote found in $name\_vote.mol2\n" and
	return undef unless($arr[1]);
	return "$arr[0],$arr[1]";
}

sub score{
	my($name,$prot)=@_;
	return undef unless checkFile($prot,25000);	#检查蛋白大小
	return undef unless checkFile("$name\_vote.mol2",1301);
	my$cmd="xscore ~/bin/xscore/parameter/ $prot $name\_vote.mol2";
	my$stat=system "$cmd >$name\_vote.score 2>>$name\_vote.err";
	delZero("$name\_vote.score");
	delZero("$name\_vote.err");
	print STDERR "Error occured during the XSCORE of $name!\n$!\n" and
	return undef if $stat;
	
	print STDERR "No score file generated for $name\_vote.mol2\n" and
	return undef unless open(SCORE,"<$name\_vote.score");
	while(<SCORE>){
		close SCORE and return 0+$1 if/^Predicted binding energy =(.+) kcal\/mol/o;	#获取能量值
	}
	
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
	
	print STDERR "$file is empty or size too small!\n" and
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