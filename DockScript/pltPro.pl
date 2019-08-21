#! /usr/bin/perl -w
use strict;
my $PLANTS='/yp_home/licz/bin/Plants';		#PLANTS软件所在位置
my $USG="usage:\n\t$0 <in.pdb> <out.mol2>\n";

die "ERROR: illeagal syntax!\n$USG" if(1 != $#ARGV);
chomp $ARGV[0];
chomp $ARGV[1];
$ARGV[0] =~/(\w+)\.pdb/  or die "ERROR: Invalid file format!\n$USG";
$ARGV[1] =~/(\w+)\.mol2/ or die "ERROR: Invalid file format!\n$USG";

`$PLANTS/SPORES_64bit --mode splitpdb $ARGV[0]`;
rename 'protein.mol2', "$ARGV[1]";
unlink "water.mol2";
unlink glob "ligand_*.mol2";

print "success to convert $ARGV[0] for PLANTS.[by LCZ]\n";
