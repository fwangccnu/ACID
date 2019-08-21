#! /usr/bin/perl -w
use strict;
opendir(DIR,".");
while(my $file=readdir DIR){
	delZero($file);
}
sub delZero{
	my $file=shift;
	return undef unless stat($file);
	my @info=stat($file);
	unlink $file if(0 == $info[7]);
}