1. �������Ҫ��php.ini�ֿ���openssl
2. �ļ���дҪ��linux����Ȩ��: sudo chmod -R 777 myResources
3. ����linux��ʱ����: crontab -e
	--a ��ʱɾ����Ч����֤��� ��0 0 * * * -hlocalhost -p3306 -uroot -ptengweimjj wordpress -e "SET SQL_SAFE_UPDATES = 0; delete from wp_email_verification where is_applicant = '0';"
	--b ��ʱɾ��tmp���ļ��� 0 */3 * * * rm -f ..../tmp/*
	
4.wp_db��������
	$wpdb->query('START TRANSACTION');
	$ts1 = $wpdb -> insert(...);
	$ts2 = $wpdb -> insert(...);
	if ($ts1 && $ts2) {
		$wpdb->query('COMMIT');
	} else {
		$wpdb->query('ROLLBACK');
	}
				  