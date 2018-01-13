<?php 
	//remove dafault dashboard
	add_filter('show_admin_bar', '__return_false');
	add_theme_support('post-thumbnails');
?>

<?php 
	//load all js or css resources
	function loading_resources() {	
		wp_enqueue_style('style', get_stylesheet_uri());
		wp_enqueue_style('bootstrap_css', get_template_directory_uri() . '/css/bootstrap.min.css');
		wp_enqueue_script('main_js', get_template_directory_uri() . '/js/main.js', array(
			'jquery' => 'jquery'
		), 1.0, true);
		wp_enqueue_script('bootstrap_js', get_template_directory_uri() . '/js/bootstrap.min.js');
		wp_enqueue_script('validate_js', get_template_directory_uri() . '/js/validate.js');
		wp_enqueue_script('echarts', 'https://cdnjs.cloudflare.com/ajax/libs/echarts/3.8.5/echarts-en.common.min.js');
		wp_localize_script('main_js', 'magicalData', array(
			'nonce' => wp_create_nonce('wp_rest'),
			'siteURL' => get_site_url()
		));		
	}
	add_action('wp_enqueue_scripts', 'loading_resources');
 ?>

<?php 
	//some helper functions
	function equal_and_set_values ($origin, $target, $true_value, $false_value) {
		if ( $origin === $target) {
			return $true_value;
		}
		return $false_value;
	}
	//show all categories
	function get_all_categories () {
		return get_categories(array(
			'taxonomy' => 'category',
			'hide_empty' => false,
			'exclude' => 1,
			'orderby' => 'term_id',
			'order' => 'ASC'
		));
	}
	//pagination functions
	function get_paginated_data ($table_name, $paged, $data_per_page) {
		global $wpdb;
		$passed_data = ($paged - 1) * $data_per_page;
		return $wpdb->get_results( "SELECT * FROM " . $wpdb->prefix . $table_name . " LIMIT {$passed_data},{$data_per_page}", OBJECT );
	}
	function get_paginated_length ($table_name, $data_per_page) {
		global $wpdb;
		$result = $wpdb->get_results( "SELECT * FROM " . $wpdb->prefix . $table_name, OBJECT );
		return ceil(count($result) / $data_per_page);
	}
	//send email
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;
	function sendEmail($host, $from_name, $from_email, $from_password, $to_email, $subject, $content) {
		// 引入PHPMailer的核心文件
		require_once("utils/phpMailer/PHPMailer.php");
		require_once("utils/phpMailer/SMTP.php");
		require_once('utils/phpMailer/Exception.php');	
		// 实例化PHPMailer核心类
		$mail = new PHPMailer();
		// 是否启用smtp的debug进行调试 开发环境建议开启 生产环境注释掉即可 默认关闭debug调试模式
		$mail->SMTPDebug = 0;
		// 使用smtp鉴权方式发送邮件
		$mail->isSMTP();
		// smtp需要鉴权 这个必须是true
		$mail->SMTPAuth = true;
		// 链接qq域名邮箱的服务器地址
		$mail->Host = $host;
		// 设置使用ssl加密方式登录鉴权
		$mail->SMTPSecure = 'ssl';
		// 设置ssl连接smtp服务器的远程服务器端口号
		$mail->Port = 465;
		// 设置发送的邮件的编码
		$mail->CharSet = 'UTF-8';
		// 设置发件人昵称 显示在收件人邮件的发件人邮箱地址前的发件人姓名
		$mail->FromName = $from_name;
		// smtp登录的账号 QQ邮箱即可
		$mail->Username = $from_email;
		// smtp登录的密码 使用生成的授权码
		$mail->Password = $from_password;
		// 设置发件人邮箱地址 同登录账号
		$mail->From = $from_email;
		// 邮件正文是否为html编码 注意此处是一个方法
		$mail->isHTML(true);
		// 设置收件人邮箱地址
		$mail->addAddress($to_email);
		// 添加多个收件人 则多次调用方法即可
		#$mail->addAddress('87654321@163.com');
		// 添加该邮件的主题
		$mail->Subject = $subject;
		// 添加邮件正文
		$mail->Body = $content;
		// 为该邮件添加附件
		#$mail->addAttachment('');
		// 发送邮件 返回状态
		$status = $mail->send();
		return $status;
	}
	//生成条形码
	function generateBarcode($code) {
		require_once("utils/phpBarcodeGenerator/BarcodeGeneratorJPG.php");
		$generatorJPG = new Picqer\Barcode\BarcodeGeneratorJPG();
		return '<img style="display:inline-block;height:60px" src="data:image/png;base64,' . base64_encode($generatorJPG->getBarcode($code, $generatorJPG::TYPE_CODE_128, 1)) . '">';
	}
?>

<?php
	//adding new tables
	// 声明全局变量$wpdb 和 数据表名常量
	global $wpdb;
	define('CAROUSEL_TABLE', $wpdb->prefix . 'carousel');
	define('EMAIL_VERIFICATION_TABLE', $wpdb->prefix . 'email_verification');
	define('MEDIA_TABLE', $wpdb->prefix . 'media_applicant');
	define('SHOW_TABLE', $wpdb->prefix . 'show_applicant');
	define('AUDIENCE_TABLE', $wpdb->prefix . 'audience_applicant');
	// 插件激活时，运行回调方法创建数据表, 在WP原有的options表中插入插件版本号
	add_action('after_switch_theme', 'initdb');
	function initdb() {
	    /*
	     * We'll set the default character set and collation for this table.
	     * If we don't do this, some characters could end up being converted 
	     * to just ?'s when saved in our table.
	     */
	    global $wpdb;
	    $charset_collate = '';
	    if (!empty($wpdb->charset)) {
	      $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
	    }
	    if (!empty( $wpdb->collate)) {
	      $charset_collate .= " COLLATE {$wpdb->collate}";
	    }
		//创建邮箱验证数据库
		if ($wpdb->get_var('show tables like "' . EMAIL_VERIFICATION_TABLE . '"') !== EMAIL_VERIFICATION_TABLE) {
	    	$sql1 = "CREATE TABLE " . EMAIL_VERIFICATION_TABLE . " (
		        id mediumint(9) NOT NULL AUTO_INCREMENT,
				email varchar(30) NOT NULL UNIQUE,
				code varchar(6) NOT NULL,
				time timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				is_applicant char(1) NOT NULL DEFAULT 0, 
				UNIQUE KEY id (id)
		    ) $charset_collate;";
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		    dbDelta( $sql1 );
	    }
	    //创建媒体登记数据库
	    if ($wpdb->get_var('show tables like "' . MEDIA_TABLE . '"') !== MEDIA_TABLE) {
	    	$sql2 = "CREATE TABLE " . MEDIA_TABLE . " (
		        id mediumint(9) NOT NULL AUTO_INCREMENT,
				uid varchar(50) NOT NULL UNIQUE,
				email varchar(30) NOT NULL DEFAULT '' UNIQUE, 
		        time timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
		        company varchar(55) DEFAULT '' NOT NULL,
		        name varchar(20) NOT NULL,
				job varchar(20) NOT NULL,
				phone varchar(20) NOT NULL,
		        UNIQUE KEY id (id)
		    ) $charset_collate;";
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		    dbDelta( $sql2 );
	    }
	    //创建轮播图数据库
	    if ($wpdb->get_var('show tables like "' . CAROUSEL_TABLE . '"') !== CAROUSEL_TABLE) {
	    	$sql3_1 = "CREATE TABLE " . CAROUSEL_TABLE . " (
		        id mediumint(9) NOT NULL AUTO_INCREMENT,
		        time timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
		        url varchar(55) DEFAULT '' NOT NULL,
		        name varchar(20) NOT NULL,
		        UNIQUE KEY id (id)
		    ) $charset_collate;";
			$sql3_2 = "INSERT INTO " . CAROUSEL_TABLE . "(`id`, `time`, `url`, `name`) VALUES (NULL, CURRENT_TIMESTAMP, '/', 'carousel_1')";
			$sql3_3 = "INSERT INTO " . CAROUSEL_TABLE . "(`id`, `time`, `url`, `name`) VALUES (NULL, CURRENT_TIMESTAMP, '/', 'carousel_2')";
			$sql3_4 = "INSERT INTO " . CAROUSEL_TABLE . "(`id`, `time`, `url`, `name`) VALUES (NULL, CURRENT_TIMESTAMP, '/', 'carousel_3')";
			$sql3_5 = "INSERT INTO " . CAROUSEL_TABLE . "(`id`, `time`, `url`, `name`) VALUES (NULL, CURRENT_TIMESTAMP, '/', 'carousel_4')";
			$sql3_6 = "INSERT INTO " . CAROUSEL_TABLE . "(`id`, `time`, `url`, `name`) VALUES (NULL, CURRENT_TIMESTAMP, '/', 'carousel_5')";
		    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		    dbDelta( $sql3_1 );
			dbDelta( $sql3_2 );
			dbDelta( $sql3_3 );
			dbDelta( $sql3_4 );
			dbDelta( $sql3_5 );
			dbDelta( $sql3_6 );
	    }	    
	}
?>

<?php
	//apis
	
	//if it is administrator?
	function isAdministrator() {
		if (current_user_can( 'administrator' )) {
			return true;
		} else {
			return false;
		}
	}
	
	/******************************************************/
	
	//define the login api 
	add_action( 'rest_api_init', 'login_hook' );
	function login_hook() {
		register_rest_route(
			'apis', 'login',
			array(
				'methods'  => 'POST',
				'callback' => 'custom_login',
			)
		);
	}
	function custom_login($request){
		$creds = array();
		$creds['user_login'] = $request["username"];
		$creds['user_password'] =  $request["password"];
		$creds['remember'] = true;
		$user = wp_signon( $creds, false );
		if (is_wp_error($user)) {
			return array(
				'status' => 'error',
				'message' => $user->get_error_message()
			);
		}
        return array(
        	'status' => 'success',
        	'message' => $user
        );
	}
	
	/******************************************************/
	
	//define carousel_upload api 
	add_action( 'rest_api_init', 'carousel_upload_hook' );
	function carousel_upload_hook() {
		register_rest_route(
			'apis', 'carousel_upload',
			array(
				'methods'  => 'POST',
				'callback' => 'carousel_upload',
				'permission_callback' => function () {
					return isAdministrator();
				}
			)
		);
	}
	//--specific carousel_upload method
	//--have to set the auth in linux: sudo chmod -R 777 myResources
	function carousel_upload_func($name) {
		if ((($_FILES[$name]["type"] === "image/gif") || ($_FILES[$name]["type"] === "image/jpeg")
			|| ($_FILES[$name]["type"] === "image/jpg") || ($_FILES[$name]["type"] === "image/pjpeg") || ($_FILES[$name]["type"] === "image/png"))
			&& ($_FILES[$name]["size"] < 1000000)) {
			if ($_FILES[$name]["error"] > 0) {
				return new WP_Error( 'file error', $_FILES[$name]["error"], array(status => '505') );
			} else {
				global $wpdb;
				$dir_path = 'wp-content/themes/cms/upload';
				$file_path = 'wp-content/themes/cms/upload/'.$_FILES[$name]["name"];
				if (!file_exists($dir_path)) {
					mkdir($dir_path, 0700);
				}
				if (!file_exists($file_path)) {
					move_uploaded_file($_FILES[$name]["tmp_name"], $file_path);
				}
				$wpdb->update($wpdb->prefix . 'carousel', array( 'url' => $file_path), array('name' => $name));
				$wpdb->show_errors();
				if ($wpdb->last_error) {
					return new WP_Error( 'database error', $wpdb->last_error, array('status' => '505') );
				} else {
					return array(
						'status' => '200',
						'message' => 'success'
					);
				}
				
			}
	  } else {
	  	return new WP_Error( 'invalid file', '必须为图片格式，且不能超过2MB', array('status' => '505') );
	  }
	}
	//carousel_upload method
	function carousel_upload($request){
		$white_names = ['carousel_1', 'carousel_2', 'carousel_3', 'carousel_4', 'carousel_5'];
		foreach ($white_names as $name) {
			if (isset($_FILES[$name])) {
				return carousel_upload_func($name);
			}
		}
		return new WP_error('file error', '文件为空，请重新上传文件', array('status' => '505'));
	}
	
	/******************************************************/
	
	//define carousel_delete api 
	add_action( 'rest_api_init', 'carousel_delete_hook' );
	function carousel_delete_hook() {
		register_rest_route(
			'apis', 'carousel_delete',
			array(
				'methods'  => 'DELETE',
				'callback' => 'carousel_delete',
				'permission_callback' => function () {
					return isAdministrator();
				}
			)
		);
	}
	//carousel_delete method
	function carousel_delete($request){
		$carousel_name = $request['carousel_name'];
		$white_names = ['carousel_1', 'carousel_2', 'carousel_3', 'carousel_4', 'carousel_5'];
		if (in_array($carousel_name, $white_names)) {
			global $wpdb;
			$wpdb->update($wpdb->prefix . 'carousel', array( 'url' => '/'), array('name' => $carousel_name));
			$wpdb->show_errors();
			if ($wpdb->last_error) {
				return new WP_Error( 'database error', $wpdb->last_error, array('status' => '505') );
			} else {
				return array(
					'status' => '200',
					'message' => 'success'
				);
			}
		}
		return new WP_error('file error', '请重新操作', array('status' => '505'));
	}
	
	/******************************************************/
	
	//define applicants api
	
	//create a order num
	function getOrderNo($prefix) {
		//订单号码主体（YYYYMMDDHHIISSNNNNNNNNN）
		$order_id_main = date('YmdHis') . rand(100000000,999999999);
		//订单号码主体长度
		$order_id_len = strlen($order_id_main);
		$order_id_sum = 0;
		for($i=0; $i<$order_id_len; $i++){
			$order_id_sum += (int)(substr($order_id_main,$i,1));
		}
		$order_id = $order_id_main . str_pad((100 - $order_id_sum % 100) % 100,2,'0',STR_PAD_LEFT);
		return $prefix ? $prefix . $order_id : $order_id;
	}
	
	//create an email code
	function getSixCode() {
		return rand(100000,999999);
	}
	
	
	//define validations
	//是否是邮箱
	function isEmail($value) {
		$pattern = "/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i";
		if ( preg_match( $pattern, $value ) ) {
			return true;
		} else {
			return false;
		}
	}
	//是否是电话
	function isPhone($value) {
		$pattern = "/^1[0-9]{10}$/i";
		if ( preg_match( $pattern, $value ) ) {
			return true;
		} else {
			return false;
		}
	}
	
	//更新或插入邮箱验证码
	function setEmailCode($email) {
		global $wpdb;
		$code = getSixCode();
		$columns["email"] = $email;
		$columns["code"] = $code;
		$result1 = $wpdb->get_row( "SELECT * FROM " . EMAIL_VERIFICATION_TABLE . " WHERE email = '{$email}' ", OBJECT );
		if ($result1 !== null) {
			//判断此邮箱是否已注册用户
			if ($result1->is_applicant === '1') {
				return new WP_Error( 'email used', '该邮箱已经注册过', array('status' => '505') ); 
			}
			$result2 = $wpdb->update( 
				EMAIL_VERIFICATION_TABLE, 
				$columns,
				array("email" => $email)
			);
		} else {
			$result2 = $wpdb->insert( 
				EMAIL_VERIFICATION_TABLE, 
				$columns
			);
		}
		//如果任意一个数据库出错， 返回数据库错误，否则返回code
		if ($result1 === false || $result2 === false) {
			return new WP_Error( 'database error', $wpdb->last_error, array('status' => '505') );
		} else {
			return $code;
		}
	}
	
	//验证邮箱和验证码的匹配
	function checkout_email_code($email, $email_code) {
		global $wpdb;
		$result = $wpdb->get_row( "SELECT * FROM " . EMAIL_VERIFICATION_TABLE . " WHERE email = '{$email}' ", OBJECT );
		if (!$result || $result->is_applicant === '1' || $result->code !== $email_code) {
			return false;
		}
		return true;
	}
	
	//创建邮箱验证码的接口
	add_action( 'rest_api_init', 'email_code_hook' );
	function email_code_hook() {
		register_rest_route(
			'apis', 'email_code',
			array(
				'methods'  => 'POST',
				'callback' => 'email_code',
				'args' => array(
				  'email' => array(
					'validate_callback' => function ($param) {
						return isEmail($param);
					}
				  )
				)
			)
		);
	}
	function email_code($request) {
		$result =  setEmailCode($request["email"]);
		if (is_wp_error($result)) {
			return $result;
		}
		$result = sendEmail("smtp.qq.com", 
			"no-reply", 
			"359593891@qq.com", 
			"bmmytyvhsqxkbigd", 
			"{$request["email"]}", 
			"no-reply", 
			"
			<h1>请保管好验证码，切勿泄露<h1>
			<h5>验证码: ${result}</h5>
			");
		if ($result) {
			return array(
				'status' => '200',
				'message' => 'success'
			); 
		} else {
			return new WP_Error( 'email sent error', '邮件无法发送', array('status' => '505') );
		}
	}
	
	//create a media applicant
	add_action( 'rest_api_init', 'media_applicant_hook' );
	function media_applicant_hook() {
		register_rest_route(
			'apis', 'media_applicant',
			array(
				'methods'  => 'POST',
				'callback' => 'media_applicant',
				'args' => array(
				  'email' => array(
					'validate_callback' => function ($param) {
						return isEmail($param);
					}
				  ),
				  'phone' => array(
					'validate_callback' => function ($param) {
						return isPhone($param);
					}
				  )
				)
			)
		);
	}
	
	function media_applicant($request){
		//检查验证码是否正确
		$result = checkout_email_code($request["email"], $request["email_code"]);
		if ($result) {
			//检验成功，插入数据，并修改EMAIL_VERIFICATION_TABLE对应的值
			$columns["uid"] = getOrderNo("ma");
			$columns["email"] = $request["email"];
			$columns["name"] = $request["name"];
			$columns["company"] = $request["company"];
			$columns["job"] = $request["job"];
			$columns["phone"] = $request["phone"];	
			global $wpdb;	
			//开启事务,插入新用户，并且修改验证码状态
			$wpdb->query('START TRANSACTION');
			$ts1 = $wpdb->update( 
				EMAIL_VERIFICATION_TABLE, 
				array("is_applicant" => "1"),
				array("email" => $request["email"])
			);
			$ts2 = $wpdb->insert( 
				MEDIA_TABLE, 
				$columns
			);
			if ($ts1 && $ts2) {
				$wpdb->query('COMMIT');
				$code_html = generateBarcode($columns["uid"]);
				$result = sendEmail("smtp.qq.com", 
					"no-reply", 
					"359593891@qq.com", 
					"bmmytyvhsqxkbigd", 
					"{$request["email"]}", 
					"no-reply", 
					"
					<h1>这是您入场用的条形码。<h1>
					<h5>请妥善保管。</h5>
					<div>{$code_html}</div>
					");
				if ($result) {
					return array(
						'status' => '200',
						'message' => 'success'
					); 
				} else {
					return new WP_Error( 'email sent error', '用户已创建，但邮件无法发送，请后台处理', array('status' => '505') );
				}
			} else {
				$wpdb->query('ROLLBACK');
				return new WP_Error( 'database error', '数据库出错', array('status' => '505') );
			}
		} else {
			//验证失败
			return new WP_Error( 'error', '验证失败，或者用户已注册', array('status' => '505') );
		}
		
	}

	/******************************************************/
	
	//define excel api
	
	//define exporting method
	function export_excel($table_name) {   
	    global $wpdb;
		$applicants = $wpdb->get_results( "SELECT * FROM " . $wpdb->prefix . $table_name, OBJECT );
		$wpdb->show_errors();
		if ($wpdb->last_error) {
			return new WP_Error( 'database error', $wpdb->last_error, array('status' => '505') );
		} else {
			
			//穿建缓存目录，和临时文件
			//临时文件需要linux进行定时任务进行删除
			//crontab -e
			//0 0 * * * rm -f path/* 每天0点准时删除
			$rand_num = mt_rand(1000000000000,9999999999999);
			$dir_path = 'wp-content/themes/cms/tmp';
			$file_path = 'wp-content/themes/cms/tmp/'."{$table_name}{$rand_num}.xls";
			if (!file_exists($dir_path)) {
				mkdir($dir_path, 0700);
			}
			
			//写入文件
			$file = fopen($file_path, 'w');
			$result = "<table><thead><tr>";
			foreach ($applicants[0] as $key => $value) {
				$result .= "<td>{$key}</td>";
			}
			$result .= "</tr><thead><tbody>";
			foreach ($applicants as $applicant) {
				$result .= "<tr>";
				foreach($applicant as $key => $value) {
					$result .= "<td>{$value}</td>";
				}
				$result .= "</tr>";
			}
			$result .= "</tbody></table>";
			fwrite($file, $result);
			fclose($file);
			
			
			return array(
				'status' => '200',
				'message' => 'success',
				'url' => $file_path
			);
		}	
	}
	
	//for media applicants
	add_action( 'rest_api_init', 'media_excel_hook' );
	function media_excel_hook() {
		register_rest_route(
			'apis', 'media_excel',
			array(
				'methods'  => 'GET',
				'callback' => 'media_excel',
				'permission_callback' => function () {
					return isAdministrator();
				}
			)
		);
	}
	
	function media_excel($request){
		return export_excel("media_applicant");
	}
?>