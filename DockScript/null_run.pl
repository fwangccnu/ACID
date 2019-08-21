#! /usr/bin/perl -w
#PBS -N null_run
#PBS -l nodes=cnode03:ppn=32,walltime=144:00:00              
#PBS -q batch
#PBS -V
use strict;
while(1){
	sleep(10000);
}
