#! /usr/bin/perl -w
(defined $ARGV[0] and defined $ARGV[1]) or die "usage:\n\t $0 <raw.pdb> <out.pdb>\n";
`/yp_home/licz/bin/Ledock/lepro $ARGV[0]`;
unlink 'dock.in';
rename 'pro.pdb',"$ARGV[1]";
