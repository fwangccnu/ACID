#! /usr/bin/perl -w
#PBS -N pledock
#PBS -l nodes=cnode17:ppn=32,walltime=144:00:00              
#PBS -q batch
#PBS -V
use strict;
use POSIX ":sys_wait_h";
#===1. check variable==============================
my $USG="usage:\n\tqsub -v lig=ARIP.mol2,param=dock.param pledock.pl";
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
if($lig_info[7] < 1301){
	die "HeavAtom num of $ENV{lig} is too small,keep it greater than 6!\n";
}
#==================================================
print "Starting Job at ".`date`;	#打印任务开始时间
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
	if(0== chdir $argv->[9] ){	#切换路径
		mkdir $argv->[9],0755;		#若失败则创建之，然后切换路径
		chdir $argv->[9];
	}
	return undef if!(my $stat=checkProt($argv->[0]) );
	prepareConfig($argv);
	controlVersion();
	return undef if!((my $run_time=runLedock($argv->[8])) >0);
	clearFiles($argv->[8]);
	deal_dok($argv->[8]);
	print "Ledock $lig_name into $argv->[0] COMPLETE in:$run_time"."s [by LCZ]\n";
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
	push(@argv,"../$arr[0].pdb");
	push(@argv,"$ENV{PBS_O_WORKDIR}/$ENV{lig}");	#配体文件使用全路径【绝对路径】
	push(@argv,$arr[2] );
	push(@argv,$arr[3] );
	push(@argv,$arr[4] );
	push(@argv,$arr[5] );
	push(@argv,$arr[6] );
	push(@argv,$arr[7] );
	push(@argv,"$lig");		#task_name
	push(@argv,"$arr[0]/$arr[1]/");	#ledock的工作路径 PDBID/LIGID/
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
	#if($prot_info[7] < 168000){#经研究，大于200个残基prepare_receptor4.py生成的蛋白pdbqt文件至少大于16.8万个字节
	if($prot_info[7] < 50000){	#取大于60个残基
		print STDERR "Residue num of $prot is too small,keep it greater than 100!\n";
		return undef;
	}
	return $prot_info[7];
}

sub prepareConfig{
	my $argv=shift;
	my ($prot,$lig,$x1,$x2,$y1,$y2,$z1,$z2,$tsk_name,$path)= @{$argv};
	my $content="Receptor\n$prot\n";
	$content .= "RMSD\n2.0\n";			#suitable for highthroughput virtual screening
	$content .= "Binding pocket\n";
	$content .= "$x1 $x2\n";
	$content .= "$y1 $y2\n";
	$content .= "$z1 $z2\n";
	$content .= "Number of binding poses\n10\n";
	$content .= "Ligands list\n$tsk_name.list\nEND\n";
	open(CONF,">$tsk_name\_le.conf");
	print CONF $content;
	close CONF;
	`cp $lig .`;	#拷贝到当前路径
	open(LIST,">$tsk_name.list");
	print LIST "$tsk_name.mol2";
	close LIST;
	return 1;
}

sub controlVersion{
	###version control############
	open(VERSION,"</etc/redhat-release");
	my $version=<VERSION>;
	close VERSION;
	chomp $version;
	$ENV{'LD_LIBRARY_PATH'}='/yp_home/licz/bin/Ledock/libc' if($version !~ /Linux release 7\./);	#
}

sub runLedock{
	my $tsk_name=shift;
	my $err_buf=undef;
	###start to run##########
	#print "sub curr work dir: ".`pwd`;
	my $ini_t=time();
	if(!open LEDOCK,'-|',"/yp_home/licz/bin/Ledock/ledock $tsk_name\_le.conf 2>&1" ){
print STDERR "Fail to launch ledock for $tsk_name! $!\n";
		return 0;
	}
	while(<LEDOCK>){
		$err_buf .=$_ if /^---Warning/o;		#缓存ledock输出的警告信息
		#print $_;								#在测试阶段，暂时把ledock输出的都打印到终端，以便及时发现错误
	}
	close LEDOCK;
	my $end_t=time();
	###end##################
	$end_t -= $ini_t;
	if($err_buf){
		open(ERR,">$tsk_name\_le.err");
		print ERR $err_buf;
		close ERR;
		return 0-$end_t;		#如果成功启动了ledock但是在中途出现问题导致失败
	}
	 
	return $end_t>0 ? $end_t : 1;	#keep time_val great than zero
}

sub clearFiles{  
	my ($tsk_name)=@_;
	unlink "$tsk_name.list";
	unlink "$tsk_name.mol2";
	unlink "$tsk_name\_le.conf";
}

sub deal_dok{
	my $tsk_name=shift;
	my $conf=[[]];
	my $idex=1;
	my @score;
	open(DOK,"<$tsk_name.dok");
	while(<DOK>){
		chomp;
		if(/^REMARK.{26}Score: (.+)kcal\/mol/o){
			push(@score,$1."\n");
			push @{$conf->[$idex]},"MODEL $idex\nCOMPND    $idex\nAUTHOR    LCZ\n";
		}elsif(/^(ATOM.{8}) ([A-Z])([A-Z])(.+)/o){
			my $lower=lc($3);
			my $line="$1$2$3 $4  1.00  0.00          $2$lower  \n";
			push @{$conf->[$idex]},$line;
		}elsif(/^ATOM.{8} ([A-Z]) /o){
			my $line="$_  1.00  0.00           $1  \n";
			push @{$conf->[$idex]},$line;
		}elsif(/^END/o){
			push @{$conf->[$idex]},$_."\n";
			push @{$conf->[$idex]},"ENDMDL\n";
			$idex ++;
		}
	}
	close DOK;
	
	open(SCORE,">$tsk_name\_le.score");
	print SCORE @score;
	close SCORE;
	open(PDB,">$tsk_name.dok");
	foreach my$pdb(@{$conf}){
		print PDB @{$pdb};
	}
	close PDB;
	my @buf=`babel -ipdb $tsk_name.dok -opdb $tsk_name\_le.pdb -d 2>&1`;
	foreach my $stat(@buf){
		print STDERR "xxx: $stat" if($stat !~/molecules? converted|audit log messages?/o);
	}
	unlink "$tsk_name.dok";
	return 1;
}
