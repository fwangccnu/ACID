#! /usr/bin/perl -w
use strict;
die "usage:\n\t$0 <file_name>\n" if !defined($ARGV[0]);
my $file = $ARGV[0];
my $time1=undef;
my $time2=undef;
open(FILE,$file);
while(<FILE>){
	if(/^(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})$/){
		$time1 = $3*24*60 + $4*60 + $5 unless defined $time1;
		$time2 = $3*24*60 + $4*60 + $5;
	}
}
my $time = $time2 -$time1;
print $file."\t$time\n";
close FILE;
