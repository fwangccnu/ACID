#! /usr/bin/perl -w
use strict;
my $PLANTS="$ENV{ACID}/DockScript/Plants";		#PLANTS软件所在位置
my $USG="usage:\n\t$0 <in.smi|sdf>\n";

my $lig_name;
die "ERROR: illeagal cmdLine syntax!\n$USG" if(!$ARGV[0]);
$ARGV[0] =~/^(.+)\.s(mi|df)$/ ? $lig_name=$1 : die "ERROR: Invalid file format!\n$USG";
system "babel $ARGV[0] $lig_name.smi" if($2 eq 'df');
system "babel $ARGV[0] $lig_name.pdb --gen3D -h";
#system "obminimize -sd -ff MMFF94 $lig_name.pdb";
#system "obminimize -cg -ff MMFF94 $lig_name.pdb";
system "$PLANTS/SPORES_64bit --mode completepdb $lig_name.pdb $lig_name.mol2";
system "prepare_ligand4.py -l $lig_name.mol2";
print "Converting $ARGV[0] for concensus reverse docking.[by LCZ]\n";
