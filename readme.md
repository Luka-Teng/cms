1. 邮箱服务要在php.ini种开启openssl
2. 文件读写要在linux设置权限: sudo chmod -R 777 myResources
3. 设置linux定时任务: crontab -e
	--a 定时删除无效的验证码表 ：0 0 * * * -hlocalhost -p3306 -uroot -ptengweimjj wordpress -e "SET SQL_SAFE_UPDATES = 0; delete from wp_email_verification where is_applicant = '0';"
	--b 定时删除tmp内文件： 0 */3 * * * rm -f ..../tmp/*