1. 邮箱服务要在php.ini种开启openssl
2. 文件读写要在linux设置权限: sudo chmod -R 777 myResources
3. 设置linux定时任务: crontab -e
	--a 定时删除验证码表 ：0 0 * * * -hlocalhost -p3306 -uroot -ptengweimjj wordpress -e "SET SQL_SAFE_UPDATES = 0; delete from wp_email_verification;"
	--b 定时删除tmp内文件： 0 */3 * * * rm -f ..../tmp/*
	
4.wp_db开启事务：
	$wpdb->query('START TRANSACTION');
	$ts1 = $wpdb -> insert(...);
	$ts2 = $wpdb -> insert(...);
	if ($ts1 && $ts2) {
		$wpdb->query('COMMIT');
	} else {
		$wpdb->query('ROLLBACK');
	}
	
5. 生产环境开启错误日志

6. 安装category_order插件