#! /usr/bin/perl -w
use strict;
my $PATH = "$ENV{ACID}/DockScript/";
BEGIN{push(@INC,"$ENV{ACID}/DockScript/")};     #设置perl环境@INC
use SQL;
#***********************************************************
my $UPLOAD = "$ENV{ACID}/uploads";#各个job_id文件夹的父文件夹
#***********************************************************
my $USAGE = "usage:\n\t $0 <job_id> [start_point]\n";
defined $ARGV[0] or die $USAGE;
my $job_id = $ARGV[0];

#===进入工作环境============================
mkdir "$UPLOAD/$job_id/" unless stat "$UPLOAD/$job_id/";
chdir "$UPLOAD/$job_id/";

my $obj = SQL->new($ARGV[0]);
defined $obj or die "ERR:No parameters gotten from web front!\n Fail to create object\n";

my $cnode = getNode();		#获取计算资源
if(!$obj->enQue($cnode) ){
	#$obj->setStatus("ERROR");
	die "Fail to put $ARGV[0] into task queue\n";
}

my $PREFIX = " -v job_id=$job_id";						#设置任务id
$PREFIX .= ",start_point=$ARGV[1]" if(defined $ARGV[1]);#设置任务开始位置
$PREFIX .= " -l nodes=$cnode:ppn=32 -N ACID_$job_id ";	#设置运行节点和任务名字

`qsub $PREFIX  -e $obj->{ligand}.e -o $obj->{ligand}.o $PATH/pInvDock.pl`;	#开始任务
`$PATH/sendmail.py $job_id & `;	#发送邮件通知状态
exit(0);

#===sub routine==============================================================
sub getNode{
	my @nodes;
	my @lines=`pbsnodes -l all |awk '\$1~/cnode/&& \$1 > "cnode12" && \$1 < "cnode21"'`;	#获取13-20范围内所有机器对应的状态
	foreach my$line(@lines){
		chomp $line;
		my($node,$stat)=split /\s+/,$line;		#从每一行中识别节点名和机器状态
		if($stat eq 'free'){					#如果该机器的状态是'free'
			my $job = `pbsnodes -q $node|awk -F ' = ' '\$1~/jobs/{print \$2}'`;	#再一次确认该机器上是否有任务
			chomp $job;
			(defined $job and $job ne '') or push @nodes,$node;		#如果确认没有，标记该节点到可用节点池
		}
	}
	my $cnode;
	if($#nodes >= 0){			#如果可用节点池不为空
		$cnode = $nodes[0];		#取第一个节点，作为将要用于执行任务的机器
	}else{
		my $n = Random(13,20);	#随机产生[13,18]之间的整数
		$cnode = "cnode$n";		#于是随机选取一个已经有任务的节点，新提交的任务将会到该机器上排队
	}
	return $cnode;
}

sub Random{			#随机数函数
	my($low,$high) = @_;
	srand();
	my $len = $high - $low + 1;
	my $n =int(rand($len));
	return ($low + $n);
}
