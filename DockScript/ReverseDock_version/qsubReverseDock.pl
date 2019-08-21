#! /usr/bin/perl -w
use strict;
my $PATH = "$ENV{ACID}/DockScript/";
BEGIN{push(@INC,"$ENV{ACID}/DockScript/")};	#设置perl环境@INC
use Paralell;
#***********************************************************
my $UPLOAD = "$ENV{ACID}/uploads";#各个job_id文件夹的父文件夹
#***********************************************************
my $USAGE = "usage:\n\t $0 <job_id> [start_point]\n";
defined $ARGV[0] or die $USAGE;
my $job_id = $ARGV[0];

#===进入工作环境============================
mkdir "$UPLOAD/$job_id/" unless stat "$UPLOAD/$job_id/";
chdir "$UPLOAD/$job_id/";

my $obj = Paralell->new($ARGV[0]);
defined $obj or die "ERR:No parameters gotten from web front!\n Fail to create object\n";

my $cnode = getNode();		#获取计算资源
#my $cnode='cnode18';
if(!$obj->enQue($cnode) ){
	#$obj->setStatus("ERROR");
	die "Fail to put $ARGV[0] into task queue\n";
}

my $PREFIX = " -v job_id=$job_id";						#设置任务id
$PREFIX .= ",start_point=$ARGV[1]" if(defined $ARGV[1]);#设置任务开始位置
$PREFIX .= " -l nodes=$cnode:ppn=32 -N ACID_$job_id ";	#设置运行节点和任务名字

#`$PATH/sendmail.py $job_id & `;	#发送邮件通知状态
`qsub $PREFIX  -e $obj->{ligand}.e -o $obj->{ligand}.o $PATH/pReverseDock.pl`;	#开始任务
exit(0);

#===sub routine==============================================================
sub getNode{
	my @nodes;
	my @lines=`pbsnodes -l all |awk '\$1~/cnode/&& \$1 > "cnode12"'`;
	foreach my$line(@lines){
		chomp $line;
		my($node,$stat)=split /\s+/,$line;
		if($stat eq 'free'){
			my $job = `pbsnodes -q $node|awk -F ' = ' '\$1~/jobs/{print \$2}'`;
			chomp $job;
			(defined $job and $job ne '')or push @nodes,$node;
		}
	}
	my $cnode;
	if($#nodes >= 0){
		$cnode = $nodes[0];
	}else{
		my $n = Random(13,20);	#随机产生[13,20]之间的整数
		$cnode = "cnode$n";
	}
	return $cnode;
}

sub Random{
	my($low,$high) = @_;
	srand();
	my $len = $high - $low + 1;
	my $n =int(rand($len));
	return ($low + $n);
}
