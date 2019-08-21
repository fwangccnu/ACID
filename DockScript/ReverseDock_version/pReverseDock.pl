#! /usr/bin/perl -w
#PBS -N RevDock
#PBS -l nodes=cnode14:ppn=30,walltime=144:00:00              
#PBS -q batch
#PBS -V
use strict;
my $PATH = "$ENV{ACID}/DockScript/";
BEGIN{push(@INC,"$ENV{ACID}/DockScript/")};        #设置perl环境@INC
use ReverseDock;
use Paralell;
#************************************************
my $USG="usage:\n\tqsub -v <job_id=job_id>[,start_point=start_point] $0\nor:\n\t$0 <job_id> [start_point]";

chdir $ENV{PBS_O_WORKDIR} if defined $ENV{PBS_O_WORKDIR};

my $job_id=($ENV{job_id} ? $ENV{job_id}: $ARGV[0]);
die "ERR:No job_id given! $USG\n" unless $job_id;

my $start_point = 'start';
if(defined $ENV{start_point}){
	$start_point = $ENV{start_point};
}elsif(defined $ARGV[1]){
	$start_point = $ARGV[1];
} 
#***************************************************
my $start_time = ReverseDock::getDateTime();
print "start time: $start_time\n";
Main_CRD($job_id,$start_point);
my $end_time = ReverseDock::getDateTime();
print "end time: $end_time\n";
#===sub routine==============================================================
sub Main_CRD{
	my ($id,$start) = @_;
	
	my $revdock = Paralell->new($id);
	defined $revdock or die "Fail to Create Paralell object of job: $id\n";
	$revdock->setTskQue();			#设置任务队列
	$revdock->setStatus('RUNNING');	#设置任务状态
	
	goto _LE  if($start =~ /^le$/i);
	goto VOTE if($start =~ /^vote$/i);
	#goto PBSA if($start =~ /^pbsa$/i);
	goto DONE if($start =~ /^done$/i);
	prepare($revdock);	#准备工作，处理小分子

_LE:
	controlVersion();
	Run(32,$revdock,\&ReverseDock::leDock,'le') 	or return undef;
VOTE:
	Run(32,$revdock,\&ReverseDock::vote,'vote') 	or return undef;
#PBSA:
	#Run(16,$revdock,\&ReverseDock::pbsa,'pbsa')	;#	or return undef;
DONE:
	$revdock->setStage('DONE');
	$revdock->Finish();				#结束任务
	#`$PATH/sendmail.py $job_id & `;		#发送邮件通知状态
}

sub Run{
	my ($n,$dock,$rFUNC,$suffix) = @_;
	$dock->setStage($suffix);
	$dock->setThrNum($n);
	$dock->setFUNC($rFUNC);
	$dock->start();
	my $err_num = $dock->sort($suffix); #sort除了对输出结果进行整理排序，还对err文件进行计数，并返回这个数
	print "###$suffix finished! \terr_num: $err_num####\n";
	#return $err_num > 1 ? undef : -1;
	return $err_num > 1 ? $err_num : -1;
}


sub prepare{
	my ($obj) = @_;
	`cp $obj->{file} $obj->{ligand}.sdf`;
	system "LigPrep.pl $obj->{ligand}.sdf 2>&1";	#生成准备文件
	system "unset DISPLAY && /yp_home/user/soft/ChemAxon6/bin/molconvert -Y 'jpeg:w500,Q95,#ffffff' $obj->{ligand}.sdf -o $obj->{ligand}.jpg";
	if(! ReverseDock::checkOrganic("$obj->{ligand}.mol2",680) ){
		#revdock->setStatus("ERROR");
		die "Please input a larger organic molecule\n";
	}
}
sub controlVersion{
open(VERSION,"</etc/redhat-release");
        my $version=<VERSION>;
        close VERSION;
        chomp $version;
        $ENV{'LD_LIBRARY_PATH'}='/yp_home/licz/bin/Ledock/libc:'.$ENV{'LD_LIBRARY_PATH'} if($version !~ /Linux release 7\./);    #
}
