#! /usr/bin/perl -w
#PBS -N PBSA
#PBS -l nodes=cnode17:ppn=32,walltime=144:00:00              
#PBS -q batch
#PBS -V
use strict;
use threads; 
use threads::shared;
use Thread::Queue;
#===确保路径安全==============
my $path=($ENV{PBS_O_WORKDIR} ? $ENV{PBS_O_WORKDIR} : $ENV{PWD} );
chdir $path;			#常量
my $MAX_THREADS=18;		#常量
#解析命令行，获取参数
my($taskQue,$lig_name) = parseCmd();	

sub Main{	
	Log("Starting RankByVote for $lig_name");
	jump2parallel();
	rnkEner();
	Log("Ending Job");
	return 0;
}
exit(Main);

#==sub rout====================================##
sub parseCmd{
	my $USG="usage:\n\tqsub -v lig=ARIP,param=dock.param\nor:\n\t$0 <lig> <paramfile>";
	
	my $lig_name=($ENV{lig} ? $ENV{lig} : $ARGV[0]);
	die "ERR:No liagnd given! $USG\n" unless $lig_name;
	
	#===检查参数文件===========
	my $param_f=($ENV{param} ? $ENV{param}: $ARGV[1]);
	die "ERR:No params file given! $USG\n" unless $param_f;
	die "No such file or cannot access param file: $param_f\n" unless open(PARAM,$param_f);
	my @param = <PARAM> and close PARAM;
	die "File $param_f is empty\n" if(!$param[0]);
	
	my $task_que=Thread::Queue->new(@param);	#根据已有列表创建共享队列【线程安全】
	return ($task_que,$lig_name);
}

sub jump2parallel{
	for(my $i=0; $i < $MAX_THREADS; ++$i){
		threads->create(\&do_task,$i);
	}
	#获取没有被join没有被detach的线程的数目
	while((my $thr_num = threads->list(threads::all) ) > 0){
		sleep(1);
		#print "thr_num: $thr_num============\n";
	}
}

sub do_task{
	my $idx=shift;
	my %hash;
	while(my $task=$taskQue->dequeue_nb()){
		my @arr=split /\s+/,$task;
		my $prot=$arr[0];
		my $site=$arr[1];
		`mmPBSA.pl $lig_name $prot $site $idx`;	#执行子任务，每个任务由一个进程完成
		#print "###thread $prot $site $lig_name $idx\n";
		#for(my $i=0;$i < 10000000;$i++){};
	}
	threads->detach();		#当任务运行结束时，将自己剥离开
}

sub rnkEner{
	my $files='';
	for(my $i=0; $i < $MAX_THREADS; ++$i){
		$files += "$lig_name\_$i.out ";
	}
	my @buff=`sort -n -k 4 $files`;	#-n按数值排序;  -k 4第四列
	
	open(RESULT,">/yp_home/licz/FishPool/PBSA_RESULT/$lig_name.pbsa.csv");
	print RESULT "pdb,site,lig,dG,-TS,dH,ELE,VDW,PBSUR,PBCAL\n";
	print "pdb,site,lig,dG,-TS,dH,ELE,VDW,PBSUR,PBCAL\n";
	foreach my$line(@buff){
		$line =~ s/ /,/g;
		print RESULT $line;
		print $line;
	}
	close RESULT;
	
	`mv $files /yp_home/licz/FishPool/PBSA_RESULT/`;
}

sub Log{
	my $content=shift;
	my $time= time();
	my ($sec,$min,$hour,$mday,$mon,$year,$wday,$yday,$isdst) = localtime($time);
	$year += 1900; # $year是从1900开始计数的，所以$year需要加上1900
	$mon ++; # $mon是从0开始计数的，所以$mon需要加上1
	printf("$content at %d-%02d-%02d %02d:%02d:%02d\n",$year,$mon,$mday,$hour,$min,$sec);
}
