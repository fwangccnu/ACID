#! /usr/bin/perl -w
use strict;

my $fst=$ARGV[0];
my $sec=$ARGV[1];
($fst && $sec) or die "usage:\n\t$0 <FST> <SECl>";

my %hash;

open(FST,"<$fst");
while(<FST>){
	chomp;
	$hash{$_} = 1;
}
close FST;

open(SEC,"<$sec");
while(<SEC>){
	chomp;
	$hash{$_} --;
}
close SEC;

my @fsts;
my @secs;
my @comms;

foreach my$k(keys %hash){
	if(1 == $hash{$k}){
		push(@fsts,$k);
	}elsif(-1 == $hash{$k}){
		push(@secs,$k);
	}elsif(0 == $hash{$k}){
		push(@comms,$k);
	}
}

print "+++just in first file++++++++++++++\n";
foreach my $line(@fsts){
	print $line."\n";
}
print "+++++++++++++++++++++++++++++++++++\n";
print "---just in second file-------------\n";
foreach my $line(@secs){
	print $line."\n";
}
print "-----------------------------------\n";
print "===both in common==================\n";
foreach my $line(@comms){
	print $line."\n";
}
print "===================================\n";
