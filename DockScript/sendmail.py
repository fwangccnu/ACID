#!/yp_home/public/soft/system_soft/PYTHON/2.7.9/bin/python
import pymysql
import os
import sys
from email.mime.text import MIMEText
from email.mime.multipart import MIMEMultipart
import smtplib

class sendmail:
	def __init__(self):
		self.host = 'master'
		self.port = 3306
		self.user = 'user'
		self.passwd = 'user@724'
		self.db = 'reverse_dock'
                self.job_id = sys.argv[1]

		conn = pymysql.connect(host=self.host, port = self.port, user=self.user, passwd=self.passwd, db=self.db)
		cur = conn.cursor()
		cur.execute("select * from jobs_reversedock where job_id='{dirname}'".format(dirname=self.job_id))
		task_data = cur.fetchone()
		self.email = task_data[5] 				#
		self.password = task_data[6]                                    #obtain job information
		self.status = task_data[9]
		self.ligand_name = task_data[4]
		cur.close()



#######################################################################################################################################


	def sendmail2user(self,Mail,information):
                '''
--------------------------------------------------------
'sendmail' is used to send mail to user
        usage: sendmail2user(infomation)
        input : information - the words you want to say
--------------------------------------------------------
                '''
		if self.ligand_name is None:
				self.ligand_name = ''
		if self.password is None:
				self.password = ''
		Content = '<span style="font-size: 16px;">Dear '+Mail.split('@')[0]+':<br>'
		Content+= '&nbsp;&nbsp;' + information + '<br><br>'
		Content+= '&nbsp;&nbsp;&nbsp;&nbsp;<b><span style="font-size: 18px; ">Job Id:&nbsp;&nbsp;<a href="http://chemyang.ccnu.edu.cn/ccb/server/ACID/index.php/reversedock/login/' + self.job_id + '">' + self.job_id + '</a></span></b> <br>'
		Content+= '&nbsp;&nbsp;&nbsp;&nbsp;<b><span style="font-size: 18px; ">Compound Name:&nbsp;&nbsp;' + self.ligand_name + '</span></b> <br>'
		Content+= '&nbsp;&nbsp;&nbsp;&nbsp;<b><span style="font-size: 18px; ">Password:&nbsp;&nbsp;' + self.password + '</span></b> <br><br>'
		Content+= '&nbsp;&nbsp; Please check your job.<br>'
		Content+='&nbsp;&nbsp; Thanks for using ACID and we sincerely welcome your precious suggestions on our webserver! For more details, see:<br>&nbsp;&nbsp; <a style="font-size: 16px;" href="http://chemyang.ccnu.edu.cn/ccb/server/ACID/index.php">ACID Server</a></span><br>&nbsp;&nbsp; Sincerely<br>&nbsp;&nbsp; <a style="font-size: 16px;" href="http://chemyang.ccnu.edu.cn/">The Yang Group</a></span>'

		MAIL_ADD='computchembio_group_noreply@mails.ccnu.edu.cn'
		MAIL_USER='computchembio_group_noreply@mails.ccnu.edu.cn'
		MAIL_PASS='aCaQaWQMUm8C2tUQ'
		MAIL_HOST='smtp.exmail.qq.com'
		try:
       			msg=MIMEMultipart()
       			msg.attach(MIMEText(Content,'html','utf-8'))
       			msg['From']='ACID Server<computchembio_group_noreply@mails.ccnu.edu.cn>'
       			msg['To']=Mail
       			msg['Subject']='Message From ACID web server!'

       			smtp = smtplib.SMTP()
       			smtp.connect(MAIL_HOST)
       			smtp.login(MAIL_USER,MAIL_PASS)
       			smtp.sendmail(MAIL_ADD,Mail,msg.as_string())
       			smtp.quit()
    		except Exception, err:
          		print "Send mail failed to: %s" % err

if __name__ == '__main__':
	send = sendmail()
	if send.status == 'FINISHED':
		if  send.email is not None and send.email != '' :
			send.sendmail2user(send.email,'Your job has been finished!')
		send.sendmail2user('wufengxu@mails.ccnu.edu.cn','A job has been finished!')
	elif send.status == 'QUEUE':
		send.sendmail2user('wufengxu@mails.ccnu.edu.cn','Someone submitted a job!')
	elif send.status == 'RUNNING':		
		send.sendmail2user('wufengxu@mails.ccnu.edu.cn','Error occured!');	#RUNNING to mark 'ERROR' status



