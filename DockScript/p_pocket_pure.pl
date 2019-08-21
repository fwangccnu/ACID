#!/usr/bin/perl -w
#use strict;
# by Chengzhang Li 2017.01.06
use POSIX ":sys_wait_h";			#为了使用WNOHANG来非阻塞调用waitpid()
open(PDB,"<pure.list");
my @param=<PDB>;
close PDB;
my $task_num = $#param + 1;	#待处理任务数
my $core_num = 32;			#空闲cpu核数

	$SIG{CHLD} = sub {	#信号处理
		my $pid =0;
		while (($pid = waitpid(-1, WNOHANG)) > 0 ) {#收到信号后，要一直等到回收完资源为止
			$core_num ++;
		}	
	};
	while($task_num > 0){
		$task_num --;
		$core_num --;
		chomp $param[$task_num];
		my $pid=fork();
		if(0==$pid){					#fork()返回给子进程的值为0
			`fpocket -f $param[$task_num]`;
			print "success to find pocket in $param[$task_num] [by LCZ]\n";
			exit;		#必须加上exit让子进程及时退出
		}elsif(!defined $pid){
			$task_num ++;
			$core_num ++;
			print STDERR "Fail to get resource for child process!\n";
		}elsif($pid >0){					#fork()返回给父进程的值为生成的子进程的ID号
			
		}
		
		while($core_num <1){
			sleep(1);
		}
	}
	
while($core_num <32){
	sleep(1);
}
exit(0);
#===============================================================
