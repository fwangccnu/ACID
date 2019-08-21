#! /usr/bin/perl -w
use strict;
BEGIN{push(@INC,"$ENV{HOME}/bin/")};	#设置perl环境@INC
use PDBParser;

my $USG="usage:\n\tpdbTreat.pl PDBfile.pdb -[s|i] --[glu]\n";
$USG .= "for detailed usage:\n\tpdbTreat.pl --help\n";
my $HELP =$USG;
$HELP .= "\twith -s will give a new file containing solvent molecules\n";
$HELP .= "\twith -i will give a new file containing ions\n";

my $stat=parseCMD();
my $pdb=PDBParser->new();
die "Fail to parse $ARGV[0]\n" if(!$pdb->parse($ARGV[0]));
$pdb->printBy($stat);

print "DONE by lcz!\n";

#==sub routine========================
#分析命令行参数
sub parseCMD{
	if(!$ARGV[0]){
		die "ERROR: NO input PDB file given!\n$USG";
	}elsif($ARGV[0] !~ /(\w{4})\.pdb$/o){
		die "ERROR: A PDB format file is needed as input!\n$USG";
	}
	
	my $status=0;	#s为2，i为1
	if(!$ARGV[1]){}
	elsif($ARGV[1] =~/^-s(i)?$/){
		$status +=2;
		$status ++ if($1);
	}elsif($ARGV[1] =~/^-i(s)?$/){
		$status ++;
		$status +=2 if($1);
	}
	
	return $status;
}