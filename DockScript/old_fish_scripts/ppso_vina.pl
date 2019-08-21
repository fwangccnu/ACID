#! /usr/bin/perl -w
#==job name============
#PBS -N ppso_vina
#===resources==============
#PBS -l nodes=cnode15:ppn=32,walltime=144:00:00              
#PBS -q batch
#PBS -V
use strict;
use POSIX ":sys_wait_h";
#===1. check variable==============================
my $USG="usage:\n\tqsub -v lig=ARIP.pdbqt param=dock.param";
die "ERR:No liagnd file given! $USG\n" if(!$ENV{lig});
die "ERR:No params file given! $USG\n" if(!$ENV{param});
my $lig_name;
if($ENV{lig} =~ /(\w+?)\.pdbqt$/o){	 #检查配体
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
			$core_num +=2;
		}	
};

while($task_num > 0){
		$task_num --;
		$core_num -= 2;
		my $pid=fork();
		if(0==$pid){					#fork()返回给子进程的值为0
			do_task($lig_name,$param[$task_num]);
			exit(0);		#必须加上exit让子进程及时退出
		}elsif(!defined $pid){
			$task_num ++;
			$core_num += 2;
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
	if(0== chdir $argv->[9] ){	#切换路径
		mkdir $argv->[9],0755;		#若失败则创建之，然后切换路径
		chdir $argv->[9];
	}
	#print "sub curr work dir: ".`pwd`;
	return undef if!(my $stat=checkProt($argv->[0]) );
	return undef if!((my $run_time=run_psoVina($argv)) >0);
	#getLowConform($argv->[8]);
	print "Pso_vina Dock $lig_name into $argv->[0] COMPLETE in:$run_time"."s [by LCZ]\n";
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
	push(@argv,"../$arr[0].pdbqt");
	push(@argv,"$ENV{PBS_O_WORKDIR}/$ENV{lig}");				#配体文件使用全路径【绝对路径】
	push(@argv,0.5*(0.0+$arr[2]+$arr[3]) );
	push(@argv,0.5*(0.0+$arr[4]+$arr[5]) );
	push(@argv,0.5*(0.0+$arr[6]+$arr[7]) );
	push(@argv,0.0+$arr[3]-$arr[2] );
	push(@argv,0.0+$arr[5]-$arr[4] );
	push(@argv,0.0+$arr[7]-$arr[6] );
	push(@argv,"$lig");		#task_name
	push(@argv,"$arr[0]/$arr[1]/");	#psovina的工作路径 PDBID/LIGID/
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
	#if($prot_info[7] < 127000){#经研究，大于200个残基prepare_receptor4.py生成的蛋白pdbqt文件至少大于12.7万个字节
	if($prot_info[7] < 35000){	#取>60个残基
		print STDERR "Residue num of $prot is too small,keep it greater than 100!\n";
		return undef;
	}
	return $prot_info[7];
}

sub run_psoVina{
	my $argv =shift;
	my $err_buf;
	my @bindEnergy;
	###start to run##########
	#print "enter run\n";
	my $ini_t=time();
	my $cmd="/yp_home/licz/bin/Vina/pso_vina  --receptor $argv->[0] --ligand $argv->[1] ";
	$cmd.=	"--center_x $argv->[2] --center_y $argv->[3] --center_z $argv->[4] ";
	$cmd.=	"--size_x $argv->[5] --size_y $argv->[6] --size_z $argv->[7] ";
	$cmd.=	"--out $argv->[8]\_pso.pdbqt";
	if(!open PSO,'-|',"$cmd 2>&1"){
		print STDERR "Fail to launch pso_vina for $argv->[8]!\n";
		return 0;
	}
	while(<PSO>){
		if (/^ERROR/){
			$err_buf .= $_;
		}elsif(/^   \d/o){
			my @split = split;
			push(@bindEnergy, $split[1]);
		}
		#print $_;
	}
	close PSO;
	my $end_t=time();
	###end##################
	$end_t -= $ini_t;
	if($err_buf){
		open(ERR,">$argv->[8]\_pso.err");
		print ERR $err_buf;
		close ERR;
		print STDERR "Fail to dock $argv->[8] into $argv->[0] by pso_vina\n";
		return 0-$end_t;		#如果成功启动了pso_vina但是在中途出现问题导致失败
	}
	if($bindEnergy[0]){
		open(SCORE,">$argv->[8]\_pso.score");
		foreach(@bindEnergy){
			print SCORE $_."\n";
		}
		close SCORE;
	}
	return $end_t;
}

sub getLowConform{
	my $tsk_name=shift;
	my $i=0;
	my @mol_conf;
	open(PDBQT,"<$tsk_name\_pso.pdbqt");
	while(<PDBQT>){
		if(/^(.{12}([A-Z])([A-Z]) {3}LIG.{57})/o){
			my $tmp = lc($3);
			$mol_conf[$i] .= "$1$2$tmp \n";		#
		}elsif(/^(.{12} ([A-Z]) {3}LIG.{57})/o){
			$mol_conf[$i] .= "$1$2 \n";
		}elsif(/^REMARK VINA RESULT:/o){	#记录结合能
			$mol_conf[$i] .= $_;
		}elsif(/^ENDMDL/o){
			$mol_conf[$i] .= "END\n";
			$i ++;
		}
	}
	close PDBQT;
	open(OUT,">$tsk_name\_pso.pdb");
	print OUT @mol_conf;
	close OUT;
}
