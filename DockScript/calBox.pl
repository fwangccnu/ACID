#! /usr/bin/perl -w
use strict;
if(0 != $#ARGV or $ARGV[0]!~/^(\w{4})_(\w{3})\.pdb$/){
        print STDERR "ERROR: illeagal cmdLine syntax!\nusage:\n\tcalBox.pl <XXXX_LIG.pdb>\n#file should be named by PROT_LIG ID in length of[8]#\n";
        exit(1);
}

open(LIG,$ARGV[0]);
my @LIG=<LIG>;
close LIG;
die "ERR:\n##Cannot find $ARGV[0] or $ARGV[0] is empty\n" if(!@LIG);
my $rlig=\@LIG;
my $PDB=$1;
my $LIGID=$2;
my @box=calBOX($rlig,$LIGID);
if(@box){
	print "$PDB\t$LIGID\t$box[0]\t$box[1]\t$box[2]\t$box[3]\t$box[4]\t$box[5]\n";
}else{
	print STDERR"ERR:\n##Fail to get Box of $ARGV[0]"; 
}


sub calBOX{
	my ($rlig,$LIGID) = @_;
	my @x;
	my @y;
	my @z;
	foreach(@$rlig){#内插了有变量的正则表达式不可以进行预编译，否则将永远只匹配第一个变量对应的字符串
		if(/^HETATM.{11}$LIGID.{6,8} +?(-?\d{1,3}\.\d{3}) {0,4}(-?\d{1,3}\.\d{3}) {0,4}(-?\d{1,3}\.\d{3})/){	
			#print;
			my $tmp;
			$tmp = 0.0+$1;
			push(@x,$1);
			$tmp = 0.0+$2;
			push(@y,$2);
			$tmp = 0.0+$3;
			push(@z,$3);
			#print "LCZ match! $1 $2 $3\n";
		}
		#else{
			# print "Fail match: $_";
		#}
	}
	@x=sort{$a<=>$b}@x;
	@y=sort{$a<=>$b}@y;
	@z=sort{$a<=>$b}@z;
	# print "x: @x\n";
	# print "y: @y\n";
	# print "z: @z\n";
	return undef if(!@x or !@y or !@z);
	return ($x[0]-2.5,$x[$#x]+2.5,$y[0]-2.5,$y[$#y]+2.5,$z[0]-2.5,$z[$#z]+2.5);
}


