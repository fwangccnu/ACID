#! /bin/bash
pbsnodes |awk -F " = " '{if($1~/^cnode1[345678]|^cnode20/){idx=NR;node=$1};if($1~/ +state/ && $2=="free" && NR==idx+1)print node}'
