#!/bin/bash
src=/data/			   # ��Ҫͬ����Դ·��
des=data			     # Ŀ��������� rsync --daemon ���������ƣ�rsync --daemon����Ͳ��������ˣ�������һ�£��Ƚϼ򵥡�
rsync_passwd_file=/etc/rsyncd.passwd	    # rsync��֤�������ļ�
ip1=192.168.0.18		 # Ŀ�������1
ip2=192.168.0.19		 # Ŀ�������2
user=root			    # rsync --daemon�������֤�û���
cd ${src}			      # �˷����У�����rsyncͬ�������ԣ��������Ҫ��cd��ԴĿ¼��inotify�ټ��� ./ ����rsyncͬ����Ŀ¼�ṹһ�£�����Ȥ��ͬѧ���Խ��и��ֳ��Թۿ���Ч��
/usr/local/bin/inotifywait -mrq --format  '%Xe %w%f' -e modify,create,delete,attrib,close_write,move ./ | while read file	 # �Ѽ�ص��з������ĵ�"�ļ�·���б�"ѭ��
do
	INO_EVENT=$(echo $file | awk '{print $1}')      # ��inotify����и� ���¼����Ͳ��ָ�ֵ��INO_EVENT
	INO_FILE=$(echo $file | awk '{print $2}')       # ��inotify����и� ���ļ�·�����ָ�ֵ��INO_FILE
	echo "-------------------------------$(date)------------------------------------"
	echo $file
	#���ӡ��޸ġ�д����ɡ��ƶ����¼�
	#�����ķ���ͬһ���жϣ���Ϊ���Ƕ��϶�������ļ��Ĳ�������ʹ���½�Ŀ¼��Ҫͬ����Ҳֻ��һ����Ŀ¼������Ӱ���ٶȡ�
	if [[ $INO_EVENT =~ 'CREATE' ]] || [[ $INO_EVENT =~ 'MODIFY' ]] || [[ $INO_EVENT =~ 'CLOSE_WRITE' ]] || [[ $INO_EVENT =~ 'MOVED_TO' ]]	 # �ж��¼�����
	then
		echo 'CREATE or MODIFY or CLOSE_WRITE or MOVED_TO'
		rsync -avzcR --password-file=${rsync_passwd_file} $(dirname ${INO_FILE}) ${user}@${ip1}::${des} &&	 # INO_FILE��������·��Ŷ  -cУ���ļ�����
		rsync -avzcR --password-file=${rsync_passwd_file} $(dirname ${INO_FILE}) ${user}@${ip2}::${des}
		 #��ϸ�� �����rsyncͬ������ Դ������$(dirname ${INO_FILE})���� ��ÿ��ֻ����Ե�ͬ�������ı���ļ���Ŀ¼(ֻͬ��Ŀ���ļ��ķ���������������ĳЩ���˻����»�©�ļ� ���ڿ����ڲ�©�ļ���Ҳ�в�����ٶ� ����ƽ��) Ȼ����-R������Դ��Ŀ¼�ṹ�ݹ鵽Ŀ����� ��֤Ŀ¼�ṹһ����
	fi
	#ɾ�����ƶ����¼�
	if [[ $INO_EVENT =~ 'DELETE' ]] || [[ $INO_EVENT =~ 'MOVED_FROM' ]]
	then
		echo 'DELETE or MOVED_FROM'
		rsync -avzR --delete --password-file=${rsync_passwd_file} $(dirname ${INO_FILE}) ${user}@${ip1}::${des} &&
		rsync -avzR --delete --password-file=${rsync_passwd_file} $(dirname ${INO_FILE}) ${user}@${ip2}::${des}
		#��rsync���� ���ֱ��ͬ����ɾ����·��${INO_FILE}�ᱨno such or directory���� ��������ͬ����Դ�Ǳ�ɾ�ļ���Ŀ¼����һ��·����������--delete��ɾ��Ŀ�����ж�Դ��û�е��ļ������ﲻ������ָ���ļ�ɾ�������ɾ����·��Խ����������ͬ����Ŀ¼�¶࣬ͬ��ɾ���Ĳ�����Խ��ʱ�䡣�����и��÷�����ͬѧ����ӭ������
	fi
	#�޸������¼� ָ touch chgrp chmod chown�Ȳ���
	if [[ $INO_EVENT =~ 'ATTRIB' ]]
	then
		echo 'ATTRIB'
		if [ ! -d "$INO_FILE" ]		 # ����޸����Ե���Ŀ¼ ��ͬ������Ϊͬ��Ŀ¼�ᷢ���ݹ�ɨ�裬�ȴ�Ŀ¼�µ��ļ�����ͬ��ʱ��rsync��˳�����´�Ŀ¼��
		then
			rsync -avzcR --password-file=${rsync_passwd_file} $(dirname ${INO_FILE}) ${user}@${ip1}::${des} &&	    
			rsync -avzcR --password-file=${rsync_passwd_file} $(dirname ${INO_FILE}) ${user}@${ip2}::${des}
		fi
	fi
done