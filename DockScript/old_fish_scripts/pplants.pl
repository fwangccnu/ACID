#! /usr/bin/perl -w
#==job name============
#PBS -N pplants
#===resources==============
#PBS -l nodes=cnode16:ppn=32,walltime=144:00:00              
#PBS -q batch
#PBS -V
use strict;
use POSIX ":sys_wait_h";
my  $RADIUS = 15;
#===1. check variable==============================
my $USG="usage:\n\tqsub -v lig=ARIP.mol2 param=dock.param";
die "ERR:No liagnd file given! $USG\n" if(!$ENV{lig});
die "ERR:No params file given! $USG\n" if(!$ENV{param});
my $lig_name;
if($ENV{lig} =~ /(\w+?)\.mol2$/o){	 #检查配体
	$lig_name = $1;
}else{
	die "ERR: illeagal input: $ENV{lig}!Please give the full path of lig file\n";	
}
chdir $ENV{PBS_O_WORKDIR};				#切换到工作路径
if(!stat($ENV{lig})){		
	die "No such file or cannot access lig: $ENV{lig}!\n";
}
my @lig_info=stat($ENV{lig});
if($lig_info[7] < 588){
	die "HeavAtom num of $ENV{lig} is too small,keep it greater than 6!\n";
}
#==================================================
print "Starting Job at ".`date`."\n";	#打印任务开始时间
print "curr work dir: ".`pwd`."\n";
#****begin of task*****************
#print "ligand file name: $ENV{lig}\n";
if(!open(PARAM,$ENV{param})){
	die "No such file or cannot access param file: $ENV{param}\n";
}
my @param = <PARAM>;
close PARAM;
die "File $ENV{param} is empty\n" if(!$param[0]);

my $task_num = $#param + 1;	#待处理任务数
my $core_num = 32;			#空闲cpu核数

$SIG{CHLD} = sub {	#信号处理
		my $pid =0;
		while (($pid = waitpid(-1, WNOHANG)) > 0 ) {#收到信号后，要一直等到回收完资源为止
			$core_num ++;
		}	
};

while($task_num > 0){
		$task_num --;
		$core_num --;
		my $pid=fork();
		if(0==$pid){					#fork()返回给子进程的值为0
			do_task($lig_name,$param[$task_num]);
			exit(0);		#必须加上exit让子进程及时退出
		}elsif(!defined $pid){
			$task_num ++;
			$core_num ++;
			print STDERR "Fail to get resource for child process!\n";
		}elsif($pid >0){					#fork()返回给父进程的值为生成的子进程的ID号
			
		}
		
		while($core_num <1){
			sleep(1);
		}
	}
while($core_num <32){
	sleep(1);
}
#****end of task******************************
print  "Ending Job at ".`date`."\n";
exit(0);

#==sub rout====================================##
sub do_task{
	my($lig_name,$param) = @_;
	return undef if!(my $argv=parseParam($lig_name,$param) );
	#print "$argv->[0]\n";
	if(0== chdir $argv->[6] ){	#切换路径
		mkdir $argv->[6],0755;		#若失败则创建之，然后切换路径
		chdir $argv->[6];
	}
	#print "sub curr work dir: ".`pwd`;
	return undef if!(my $stat=checkProt($argv->[0]) );
	prepareConfig($argv);
	return undef if!((my $run_time=runPlants($argv->[5])) >0);
	clearFiles($argv->[5]);
	
	print "Plants Dock $lig into $argv->[0] COMPLETE in:$run_time"."s [by LCZ]\n";
	return 0;
}

sub parseParam{
	my ($lig,$param) = @_;
	chomp $param;
	#my @arr = split(/\t/,$param);
	my @arr = split /\s+/,$param;
	#print "$#arr: $param\n";
	if(7 != $#arr){
		print STDERR "ERR: param wrong with: @arr\n";
		return undef ;
	}
	my @argv;
	push(@argv,"../$arr[0].mol2");
	push(@argv,"$ENV{PBS_O_WORKDIR}/$ENV{lig}");				#配体文件使用全路径【绝对路径】
	push(@argv,0.5*(0.0+$arr[2]+$arr[3]) );
	push(@argv,0.5*(0.0+$arr[4]+$arr[5]) );
	push(@argv,0.5*(0.0+$arr[6]+$arr[7]) );
	push(@argv,"$lig");		#task_name
	push(@argv,"$arr[0]/$arr[1]/");	#plants的工作路径 PDBID/LIGID/
	return \@argv;
}

sub checkProt{
	my $prot=shift;
	#===检查受体蛋白===================#
	#print "$prot\n";
	my $stat=stat($prot);
	#print "stat: $stat\n";
	if(!$stat){		
		print STDERR "No such file or cannot access prot: $prot!\n";
		return undef;
	}
	my @prot_info=stat($prot);
	#print "size: $prot_info[7]\n";
	#经研究，大于200个残基加氢的蛋白mol2文件至少大于31万个字节
	if($prot_info[7] < 80000){	#取大于60个残基
		print STDERR "Residue num of $prot is too small,keep it greater than 100!\n";
		return undef;
	}
	return $prot_info[7];
}

sub prepareConfig{
	my $argv =shift;
	my ($prot,$lig,$x,$y,$z,$tsk_name) = @{$argv};
	my $content="bindingsite_center $x $y $z\n";
	$content .= "bindingsite_radius $RADIUS\n";
	$content .= "scoring_function chemplp\n";
	#$content .= "cluster_rmsd 2.0\n";
	$content .= "cluster_structures 15\n";
	$content .= "protein_file $prot\n";
	$content .= "ligand_file $lig\n";
	open(CONF,">$tsk_name\_plnt.conf");
	print CONF $content;
	close CONF;
	return 0;
}

sub runPlants{
	my $tsk_name =shift;
	my $err_buf;
	my @bindEnergy;
	###start to run##########
	#print "enter run\n";
	my $ini_t=time();
	my $cmd="/yp_home/licz/bin/Plants/plants --mode screen $tsk_name\_plnt.conf";
	if(!open PLANT,'-|',"$cmd 2>&1"){
		print STDERR "Fail to launch plants for $tsk_name! $!\n";
		return 0;
	}
	while(<PLANT>){
		$err_buf .=$_ if /^PLANTS error/o;		#缓存plants输出的错误信息
		#print $_;								#在测试阶段，暂时把plants输出的都打印到终端，以便及时发现错误
	}
	close PLANT;
	my $end_t=time();
	###end##################
	$end_t -= $ini_t;
	if($err_buf){
		open(ERR,">$tsk_name\_plnt.err");
		print ERR $err_buf;
		close ERR;
		return 0-$end_t;		#如果成功启动了plants但是在中途出现问题导致失败
	}
	
	return $end_t;
}

#清除垃圾文件，并将有用文件改为更容易识别的名称
sub clearFiles{
	my ($tsk_name)=@_;
	unlink glob "*.log";
	unlink glob "PLANTS*.pid";
	unlink glob "descent*";
	rename 'ranking.csv',"$tsk_name\_plnt.score";			#plants基于CHEMPLP打分函数，保留这个打分文件
	#rename 'skippedligands.csv',"$tsk_name\_plant.fail";	#将失败的配体记录保留下来
	unlink glob "*.csv";
	unlink "$tsk_name\_plnt.conf";
	unlink "protein_bindingsite_fixed.mol2","plantsconfig","docked_proteins.mol2";
	`/yp_home/licz/bin/cluster docked_ligands.mol2 2.0 >$tsk_name\_plnt.mol2`; 
	unlink 'docked_ligands.mol2';	#保留生成的配体构象文件
	return 1;
}
