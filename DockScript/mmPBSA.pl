#! /usr/bin/perl -w
use strict;
my $lig =$ARGV[0];
my $prot=$ARGV[1];
my $site=$ARGV[2];
my $idex=$ARGV[3];
($lig && $prot && $site) or die "usage:\n\t$0 lig prot site\n";
my $PATH = "$ENV{ACID}/DockScript";
my $POOL_PATH="$ENV{ACID}/FishPool";

my $ORIG_DIR = $ENV{PBS_O_WORKDIR} ? $ENV{PBS_O_WORKDIR} : $ENV{PWD};

my $TMP_DIR="$ORIG_DIR/$prot\_$site\_$lig\_pbsa";

my $out_log;
sub Main{
	Init();
	return -1 if !defined PrepComplex($lig,$prot,$site);
	#print STDERR getDateTime()."\n";
	mmPBSA();
	my @E=getDetail();
	Destroy();
	printRes(\@E);
	#print STDERR getDateTime()."\n";
	return 0;
}
exit(Main);
#==================================================================
sub Init{
	mkdir $TMP_DIR;# or print STDERR "DIRECTORY $TMP_DIR ALREADY EXISTS\n";
	chdir $TMP_DIR;
}

sub PrepComplex{
	my($lig,$prot,$site)=@_;
	#将受体中的重原子写入到complex.pdb中
	`awk '\$0 !~ /.{77}H/' $POOL_PATH/$prot/$prot\_r.pdb > COMPLEX.pdb`;
	
	return addLig2Complex("$ORIG_DIR/$prot\_$site\_$lig\_vote.mol2");
}

sub mmPBSA{
	$out_log= `$ENV{ACID}/DockScript/PBSA/auto4pbsa.py $ENV{ACID}/DockScript/PBSA/parameter.txt`;
}

sub Destroy{
	chdir "..";
	rename "$TMP_DIR/complex_mini.pdb","$prot\_$site\_$lig\_pbsa.pdb";
	#`rm -r $TMP_DIR`;
}

#转化mol2配体格式为pdb格式，并滤掉氢原子，将重原子追加到complex.pdb中
sub addLig2Complex{
	my $lig=shift;
	my $err = `babel $lig -opdb -d 2>&1 |grep Error`;
	chomp $err;
	if(defined $err and $err ne ''){
		print STDERR $err." #$lig#\n";
		return undef ;
	}
	
	my @lines=`babel $lig -opdb -d 2>/dev/null|awk '\$1~/^[AH][TE]/{if(\$0!~/.{77}H/){print \$0}}'`;
	
	for(my $i=0; $i<=$#lines; ++$i){
		if($lines[$i] =~ /^(.{17})(.{3})(.+)/){
			$lines[$i]=$1.'LIG'.$3."\n";
		}
	}
	
	open(COM,">>COMPLEX.pdb");
	print COM @lines;
	print COM "END\n";		#写上末尾符号
	close COM;
	return 1;
}

sub getDetail{
	my %E;
	my $tmp;
	# open(DG,"snapshot/delta_g.out") or print STDERR "FILE $TMP_DIR/snapshot/delta_g.out NOT FOUND\n" and goto NO_G ;
	# while(<DG>){
		# my @vec=split;
		# $E{$vec[0]}=$vec[1];
	# }
	# close DG;
# NO_G:
	
	open(STAT,"snapshot/delta_E_statistics.out") or print STDERR "$out_log#FILE $TMP_DIR/snapshot/delta_E_statistics.out NOT FOUND\n" and goto NO_H ;
	my $cnt=0;
	while(<STAT>){
		next if(++$cnt<24);
		my @vec=split;
		$E{$vec[0]}=$vec[1];
	}
	close STAT;
NO_H:

	return (
	# $E{'GTOT'}	?	$E{'GTOT'}	:	'null',
	# $E{'-TS'}	?	$E{'-TS'}	:	'null',
	$E{'GAS'}	?	$E{'GAS'}	:	'null',
	$E{'PBSOL'}	?	$E{'PBSOL'}	:	'null',
	$E{'PBTOT'}	?	$E{'PBTOT'}	:	'null'
	#$E{'ELE'}	?	$E{'ELE'}	:	'null',
	#$E{'VDW'}	?	$E{'VDW'}	:	'null',
	#$E{'PBSUR'}	?	$E{'PBSUR'}	:	'null',
	#$E{'PBCAL'}	?	$E{'PBCAL'}	:	'null'
	);
}

sub printRes{
	my $rE = shift;
	if( defined($idex) ){
		open(RES,">>$lig\_$idex.pbsa");
		print RES "$prot $site @{$rE}\n";
		close RES;
	}else{
		print "$prot $site @{$rE}\n";
	}
}
sub getDateTime{
        my ($sec,$min,$hour,$mday,$mon,$year,$wday,$yday,$isdst) = localtime(time());
        $year += 1900; # $yearˇ??900???????ear?????900
        $mon ++; # $monˇ?????????on?????
        return sprintf("%d-%02d-%02d %02d:%02d:%02d",$year,$mon,$mday,$hour,$min,$sec) ;
}
