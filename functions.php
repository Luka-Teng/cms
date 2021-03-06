<?php 
	//remove dafault dashboard
	add_filter('show_admin_bar', '__return_false');
	add_theme_support('post-thumbnails');
?>

<?php 
	//load all js or css resources
	function loading_resources() {	
		wp_enqueue_style('style', get_stylesheet_uri());
		wp_enqueue_script('main_js', get_template_directory_uri() . '/js/main.js', array(
			'jquery' => 'jquery'
		), 1.0, true);
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
	//show all categories, 8个主要标题
	function get_all_categories () {
		return get_categories(array(
			'taxonomy' => 'category',
			'hide_empty' => false,
			'slug' => ['home', 'about-show', 'show-center', 'audience-center', 'activity', 'media-center', 'service', 'contact-us'],
			'orderby' => 'term_id',
			'order' => 'ASC',
		));
	}
	
	//show categories in cities, 城市之窗
	function get_cities ($paged = 1, $data_per_page = 100) {
		$passed_data = ($paged - 1) * $data_per_page;
		return get_categories(array(
			'taxonomy' => 'category',
			'hide_empty' => false,
			'child_of' => get_categories(array(
				'taxonomy' => 'category',
				'hide_empty' => false,
				'slug' => ['cities']
			))[0]->cat_ID,
			'number' => $data_per_page,
			'offset' => $passed_data
		));
	}
	
	//show categories, 展商动态
	function get_show_news () {
		return get_categories(array(
			'taxonomy' => 'category',
			'hide_empty' => false,
			'slug' => ['show-news']
		));
	}
	
	//show categories, 	会展亮点
	function get_show_highlights () {
		return get_categories(array(
			'taxonomy' => 'category',
			'hide_empty' => false,
			'slug' => ['show-highlights']
		));
	}
	
	//get static resources url
	function get_static_url($string) {
		return get_template_directory_uri() . $string;
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
	function sendEmail($to_email, $subject, $content) {
		// 引入PHPMailer的核心文件
		require_once("utils/phpMailer/PHPMailer.php");
		require_once("utils/phpMailer/SMTP.php");
		require_once('utils/phpMailer/Exception.php');
		require_once('utils/phpMailer/config.php');
		// 实例化PHPMailer核心类
		$mail = new PHPMailer();
		// 是否启用smtp的debug进行调试 开发环境建议开启 生产环境注释掉即可 默认关闭debug调试模式
		$mail->SMTPDebug = 0;
		// 使用smtp鉴权方式发送邮件
		$mail->isSMTP();
		// smtp需要鉴权 这个必须是true
		$mail->SMTPAuth = true;
		// 链接qq域名邮箱的服务器地址
		$mail->Host = $emailConfig['host'];
		// 设置使用ssl加密方式登录鉴权
		$mail->SMTPSecure = 'ssl';
		// 设置ssl连接smtp服务器的远程服务器端口号
		$mail->Port = 465;
		// 设置发送的邮件的编码
		$mail->CharSet = 'UTF-8';
		// 设置发件人昵称 显示在收件人邮件的发件人邮箱地址前的发件人姓名
		$mail->FromName = $emailConfig['from_name'];
		// smtp登录的账号 QQ邮箱即可
		$mail->Username = $emailConfig['from_email'];
		// smtp登录的密码 使用生成的授权码
		$mail->Password = $emailConfig['from_password'];
		// 设置发件人邮箱地址 同登录账号
		$mail->From = $emailConfig['from_email'];
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
	define('APPLICANT_TABLE', $wpdb->prefix . 'applicant');
	define('BANNER_TABLE', $wpdb->prefix . 'banner');
	define('TICKET_TABLE', $wpdb->prefix . 'ticket');
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
				UNIQUE KEY id (id)
		    ) $charset_collate;";
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		    dbDelta( $sql1 );
	    }
	    //创建申请人登记数据库
	    if ($wpdb->get_var('show tables like "' . APPLICANT_TABLE . '"') !== APPLICANT_TABLE) {
	    	$sql2 = "CREATE TABLE " . APPLICANT_TABLE . " (
		        id mediumint(9) NOT NULL AUTO_INCREMENT,
				uid varchar(50) NOT NULL UNIQUE,
				trade_no varchar(50) DEFAULT '' UNIQUE,
				email varchar(30) NOT NULL DEFAULT '', 
		        time timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
		        company varchar(55) DEFAULT '' NOT NULL,
		        name varchar(20) NOT NULL,
				job varchar(20) NOT NULL,
				phone varchar(20) NOT NULL,
				type varchar(20) NOT NULL,
				payment_type varchar(20) NOT NULL DEFAULT 'free',
				total_amount varchar(20) NOT NULL DEFAULT '0.00',
				tickets varchar(1000) DEFAULT '[]',
				checked varchar(1000) DEFAULT '[]' NOT NULL,
		        UNIQUE KEY id (id)
		    ) $charset_collate;";
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		    dbDelta( $sql2 );
	    }
		//创建票务数据库
	    if ($wpdb->get_var('show tables like "' . TICKET_TABLE . '"') !== TICKET_TABLE) {
	    	$sql5 = "CREATE TABLE " . TICKET_TABLE . " (
		        id mediumint(9) NOT NULL AUTO_INCREMENT,
				uid varchar(50) NOT NULL UNIQUE,
				date varchar(20) NOT NULL,
				type varchar(20) NOT NULL,
				price varchar(20) NOT NULL DEFAULT '0.00',
		        UNIQUE KEY id (id)
		    ) $charset_collate;";
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		    dbDelta( $sql5 );
	    }
	    //创建轮播图数据库
	    if ($wpdb->get_var('show tables like "' . CAROUSEL_TABLE . '"') !== CAROUSEL_TABLE) {
	    	$sql3_1 = "CREATE TABLE " . CAROUSEL_TABLE . " (
		        id mediumint(9) NOT NULL AUTO_INCREMENT,
		        time timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
		        url_1 varchar(100) DEFAULT '' NOT NULL,
				url_2 varchar(100) DEFAULT '' NOT NULL,
		        name varchar(20) NOT NULL,
		        UNIQUE KEY id (id)
		    ) $charset_collate;";
			$sql3_2 = "INSERT INTO " . CAROUSEL_TABLE . "(`id`, `time`, `url_1`, `url_2`, `name`) VALUES (NULL, CURRENT_TIMESTAMP, '/', '/', 'carousel_1')";
			$sql3_3 = "INSERT INTO " . CAROUSEL_TABLE . "(`id`, `time`, `url_1`, `url_2`, `name`) VALUES (NULL, CURRENT_TIMESTAMP, '/', '/', 'carousel_2')";
			$sql3_4 = "INSERT INTO " . CAROUSEL_TABLE . "(`id`, `time`, `url_1`, `url_2`, `name`) VALUES (NULL, CURRENT_TIMESTAMP, '/', '/', 'carousel_3')";
			$sql3_5 = "INSERT INTO " . CAROUSEL_TABLE . "(`id`, `time`, `url_1`, `url_2`, `name`) VALUES (NULL, CURRENT_TIMESTAMP, '/', '/', 'carousel_4')";
			$sql3_6 = "INSERT INTO " . CAROUSEL_TABLE . "(`id`, `time`, `url_1`, `url_2`, `name`) VALUES (NULL, CURRENT_TIMESTAMP, '/', '/', 'carousel_5')";
		    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		    dbDelta( $sql3_1 );
			dbDelta( $sql3_2 );
			dbDelta( $sql3_3 );
			dbDelta( $sql3_4 );
			dbDelta( $sql3_5 );
			dbDelta( $sql3_6 );
	    }
		//创建BANNER数据库
	    if ($wpdb->get_var('show tables like "' . BANNER_TABLE . '"') !== BANNER_TABLE) {
	    	$sql4_1 = "CREATE TABLE " . BANNER_TABLE . " (
		        id mediumint(9) NOT NULL AUTO_INCREMENT,
		        time timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				uid varchar(50) NOT NULL UNIQUE,
		        image_url varchar(100) DEFAULT '' NOT NULL,
		        title varchar(20) NOT NULL,
				link varchar(100) DEFAULT '' NOT NULL,
		        UNIQUE KEY id (id)
		    ) $charset_collate;";
			$sql4_2 = "INSERT INTO " . BANNER_TABLE . "(`id`, `time`, `uid`, `image_url`, `title`, `link`) VALUES (NULL, CURRENT_TIMESTAMP, '1', '/', 'null', '/')";
			$sql4_3 = "INSERT INTO " . BANNER_TABLE . "(`id`, `time`, `uid`, `image_url`, `title`, `link`) VALUES (NULL, CURRENT_TIMESTAMP, '2', '/', 'null', '/')";
			$sql4_4 = "INSERT INTO " . BANNER_TABLE . "(`id`, `time`, `uid`, `image_url`, `title`, `link`) VALUES (NULL, CURRENT_TIMESTAMP, '3', '/', 'null', '/')";
			$sql4_5 = "INSERT INTO " . BANNER_TABLE . "(`id`, `time`, `uid`, `image_url`, `title`, `link`) VALUES (NULL, CURRENT_TIMESTAMP, '4', '/', 'null', '/')";
			$sql4_6 = "INSERT INTO " . BANNER_TABLE . "(`id`, `time`, `uid`, `image_url`, `title`, `link`) VALUES (NULL, CURRENT_TIMESTAMP, '5', '/', 'null', '/')";
			$sql4_7 = "INSERT INTO " . BANNER_TABLE . "(`id`, `time`, `uid`, `image_url`, `title`, `link`) VALUES (NULL, CURRENT_TIMESTAMP, '6', '/', 'null', '/')";
			$sql4_8 = "INSERT INTO " . BANNER_TABLE . "(`id`, `time`, `uid`, `image_url`, `title`, `link`) VALUES (NULL, CURRENT_TIMESTAMP, '7', '/', 'null', '/')";
			$sql4_9 = "INSERT INTO " . BANNER_TABLE . "(`id`, `time`, `uid`, `image_url`, `title`, `link`) VALUES (NULL, CURRENT_TIMESTAMP, '8', '/', 'null', '/')";
		    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		    dbDelta( $sql4_1 );
			dbDelta( $sql4_2 );
			dbDelta( $sql4_3 );
			dbDelta( $sql4_4 );
			dbDelta( $sql4_5 );
			dbDelta( $sql4_6 );
			dbDelta( $sql4_7 );
			dbDelta( $sql4_8 );
			dbDelta( $sql4_9 );
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
	
	//if it is editor，检票员角色?
	function isEditor() {
		if (current_user_can( 'editor' )) {
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
	
	//定义ticket模块
	
	//创建票的路由
	add_action( 'rest_api_init', 'new_ticket_hook' );
	function new_ticket_hook() {
		register_rest_route(
			'apis', 'new_ticket',
			array(
				'methods'  => 'POST',
				'callback' => 'new_ticket',
				'permission_callback' => function () {
					return isAdministrator();
				},
				'args' => array(
				  'type' => array(
					'validate_callback' => function ($param) {
						//类型可以是媒体，观众
						return isInArray($param, ['media', 'audience']);
					}
				  ),
				  'price' => array(
					'validate_callback' => function ($param) {
						return isNumber($param);
					}
				  ),
				  'date' => array(
					'validate_callback' => function ($param) {
						return isNotNull($param);
					}
				  )
				)
			)
		);
	}
	function new_ticket($request) {
		global $wpdb;
		$columns['date'] = $request['date'];
		$columns['type'] = $request['type'];
		$columns['price'] = $request['price'];
		$columns['uid'] = $request['type'] . '-' . $request['date'];
		$sql = $wpdb->insert( 
			TICKET_TABLE, 
			$columns
		);
		if ($sql) {
			return array(
				'status' => '200',
				'message' => 'success'
			);
		} else {
			return new WP_Error( 'database error', 'database error', array('status' => '505') );
		}
	}
	
	//删除票的路由
	add_action( 'rest_api_init', 'delete_ticket_hook' );
	function delete_ticket_hook() {
		register_rest_route(
			'apis', 'delete_ticket',
			array(
				'methods'  => 'DELETE',
				'callback' => 'delete_ticket',
				'permission_callback' => function () {
					return isAdministrator();
				}
			)
		);
	}
	function delete_ticket($request) {
		global $wpdb;
		$columns['uid'] = $request['uid'];
		$sql = $wpdb->delete(TICKET_TABLE, $columns);
		if ($sql) {
			return array(
				'status' => '200',
				'message' => 'success'
			);
		} else {
			return new WP_Error( 'database error', 'database error', array('status' => '505') );
		}
	}
	
	/******************************************************/
	
	//定义首页的banner模块
	add_action( 'rest_api_init', 'update_banner_hook' );
	function update_banner_hook() {
		register_rest_route(
			'apis', 'update_banner',
			array(
				'methods'  => 'POST',
				'callback' => 'update_banner',
				'permission_callback' => function () {
					return isAdministrator();
				}
			)
		);
	}
	function update_banner($request){
		global $wpdb;
		$uid = $request['uid'];
		$banner_name = 'banner_' . $uid;	
		if (isset($_FILES[$banner_name])) {
			if ((($_FILES[$banner_name]["type"] === "image/gif") || ($_FILES[$banner_name]["type"] === "image/jpeg")
				|| ($_FILES[$banner_name]["type"] === "image/jpg") || ($_FILES[$banner_name]["type"] === "image/pjpeg") || ($_FILES[$banner_name]["type"] === "image/png"))
				&& ($_FILES[$banner_name]["size"] < 2000000)) {
				if ($_FILES[$banner_name]["error"] > 0) {
					return new WP_Error( 'file error', $_FILES[$banner_name]["error"], array(status => '505') );
				} else {
					global $wpdb;
					$dir_path = 'wp-content/themes/cms/upload';
					$file_path = 'wp-content/themes/cms/upload/'.$banner_name.$_FILES[$banner_name]["name"];
					if (!file_exists($dir_path)) {
						mkdir($dir_path, 0700);
					}
					//先将名字转化为utf-8在保存，防止中文乱码
					move_uploaded_file($_FILES[$banner_name]["tmp_name"], iconv('utf-8','gb2312',$file_path));
					$sql = $wpdb->update(BANNER_TABLE, array('image_url' => $file_path, 'title' => $request['title'], 'link' => $request['link']), array('uid' => $uid));
					if ($sql) {
						return array(
							'status' => '200',
							'message' => 'success'
						);
						
					} else {
						return new WP_Error( 'database error', 'database error', array('status' => '505') );
					}
					
				}
			} else {
				return new WP_Error( 'invalid file', '必须为图片格式，且不能超过2MB', array('status' => '505') );
			}
		} else {
			$sql = $wpdb->update(BANNER_TABLE, array('title' => $request['title'], 'link' => $request['link']), array('uid' => $uid));
			if ($sql) {
				return array(
					'status' => '200',
					'message' => 'success'
				);
				
			} else {
				return new WP_Error( 'database error', 'database error', array('status' => '505') );
			}
		}
		
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
			&& ($_FILES[$name]["size"] < 2000000)) {
			if ($_FILES[$name]["error"] > 0) {
				return new WP_Error( 'file error', $_FILES[$name]["error"], array(status => '505') );
			} else {
				global $wpdb;
				$dir_path = 'wp-content/themes/cms/upload';
				$file_path = 'wp-content/themes/cms/upload/'.$name.$_FILES[$name]["name"];
				if (!file_exists($dir_path)) {
					mkdir($dir_path, 0700);
				}
				//先将名字转化为utf-8在保存，防止中文乱码
				move_uploaded_file($_FILES[$name]["tmp_name"], iconv('utf-8','gb2312',$file_path));
				$wpdb->update(CAROUSEL_TABLE, array('url_' . substr($name,-1) => $file_path), array('name' => substr($name, 0, 10)));
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
		$white_names = ['carousel_1_1', 'carousel_1_2', 'carousel_2_1', 'carousel_2_2', 'carousel_3_1', 'carousel_3_2', 'carousel_4_1', 'carousel_4_2', 'carousel_5_1', 'carousel_5_2'];
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
		$white_names = ['carousel_1_1', 'carousel_1_2', 'carousel_2_1', 'carousel_2_2', 'carousel_3_1', 'carousel_3_2', 'carousel_4_1', 'carousel_4_2', 'carousel_5_1', 'carousel_5_2'];
		if (in_array($carousel_name, $white_names)) {
			global $wpdb;
			$wpdb->update($wpdb->prefix . 'carousel', array('url_' . substr($carousel_name,-1) => '/'), array('name' => substr($carousel_name	, 0, 10)));
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
	//是否是数字
	function isNumber($value) {
		$pattern = "/^(\d+)|(\d+.\d+)$/i";
		if ( preg_match( $pattern, $value ) ) {
			return true;
		} else {
			return false;
		}
	}
	//是否是日期
	function isDate($value) {
		//目前支持yyyy-mm-dd, yyyy/mm/dd
		$pattern = "/^\d{4}(-|\/)\d{2}(-|\/)\d{2}$/i";
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
	//是否不为空
	function isNotNull($value) {
		if ( empty($value) ) {
			return false;
		} else {
			return true;
		}
	}
	//值是否在数组之中
	function isInArray($value, $arr) {
		if (in_array($value, $arr)) {
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
		if (!$result || $result->code !== $email_code) {
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
		$result = sendEmail (
			$request["email"],
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
	
	//验证电话和验证码的匹配
	function checkout_phone_code($phone, $code) {
		require_once("utils/msg/msg.php");
		$AppKey = 'a40ff03c4f7f3cc3243ea235a8b0332e';
		$AppSecret = '1a384cdd9931';
		$p = new ServerAPI($AppKey,$AppSecret,'curl');
		$result = $p->verifySmsCode($phone, $code);
		if ($result['code'] == '200') {
			return true;
		}
		return false;
	}
	
	//创建电话验证码的接口
	add_action( 'rest_api_init', 'phone_code_hook' );
	function phone_code_hook() {
		register_rest_route(
			'apis', 'phone_code',
			array(
				'methods'  => 'POST',
				'callback' => 'phone_code',
				'args' => array(
				  'phone' => array(
					'validate_callback' => function ($param) {
						return isPhone($param);
					}
				  )
				)
			)
		);
	}
	function phone_code($request) {
		require_once("utils/msg/msg.php");
		$AppKey = 'a40ff03c4f7f3cc3243ea235a8b0332e';
		$AppSecret = '1a384cdd9931';
		$p = new ServerAPI($AppKey,$AppSecret,'curl');
		$result = $p->sendSmsCode('3872662',$request['phone'],'','6');
		if ($result['code'] == '200') {
			return array(
				'status' => '200',
				'message' => 'success'
			); 
		} else {
			return new WP_Error( 'phone sent error', '验证码无法发送', array('status' => '505') );
		}
	}
	//todo发送短信模板
	
	//create a media applicant
	add_action( 'rest_api_init', 'create_applicant_hook' );
	function create_applicant_hook() {
		register_rest_route(
			'apis', 'create_applicant',
			array(
				'methods'  => 'POST',
				'callback' => 'create_applicant',
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
				  ),
				  'name' => array(
					'validate_callback' => function ($param) {
						return isNotNull($param);
					}
				  ),
				  'company' => array(
					'validate_callback' => function ($param) {
						return isNotNull($param);
					}
				  ),
				  'job' => array(
					'validate_callback' => function ($param) {
						return isNotNull($param);
					}
				  ),
				  'tickets' => array(
					'validate_callback' => function ($param) {
						return count(json_decode($param)) !== 0;
					}
				  ),
				  'type' => array(
					'validate_callback' => function ($param) {
						return isInArray($param, ['audience', 'media']);
					}
				  ),
				  'payment_type' => array(
					'validate_callback' => function ($param) {
						//付款类型可以是免费，支付宝，微信
						return isInArray($param, ['alipay', 'wechat', 'free']);
					}
				  )
				)
			)
		);
	}
	
	//付款后的业务逻辑代码
	function after_paid($data) {
		#免费请求， 直接修改paid数据
		global $wpdb;
		//如果用户未创建，创建用户，并发起请求
		$columns["uid"] = $data["uid"];
		$columns["email"] = $data["email"];
		$columns["name"] = $data["name"];
		$columns["company"] = $data["company"];
		$columns["job"] = $data["job"];
		$columns["phone"] = $data["phone"];
		$columns["type"] = $data["type"];
		$columns["tickets"] = $data["tickets"];
		$columns["total_amount"] = $data["total_amount"];
		$columns["payment_type"] = $data["payment_type"];
		$columns["trade_no"] = $data["trade_no"];
		//插入新用户			
		$query = $wpdb->insert( 
			APPLICANT_TABLE, 
			$columns
		);
		//如果数据库请求成功
		if ($query) {
			$code_html = generateBarcode($columns["uid"]);
			$email_result = sendEmail(
				$columns["email"],
				"csjbrandexpo", 
				"
				<h1>这是您入场用的条形码。<h1>
				<h5>请妥善保管。</h5>
				<div>{$code_html}</div>
				");
		}
		//返回成功和错误数据
		if (!$query) {
			return new WP_Error( 'database error', '数据库出错', array('status' => '505') );
		} else if (!$email_result) {
			return new WP_Error( 'email sent error', '邮件无法发送', array('status' => '505') );
		} else {
			return array(
        		'status' => 'success',
        		'message' => '购买成功'
			);
		}
	}

	//支付宝付款方法
	/*
	function alipay_pay($applicant_email, $applicant_uid, $total_amount, $subject, $body) {
		#支付宝请求
		require_once("utils/payment/payment.php");						
		#创建alipay实例
		$alipay = new Alipayment();					
		#返回支付订单页
		$result = $alipay->payRequest(Array(
			'returnUrl' => 'http://' . $_SERVER['HTTP_HOST'] . '/return-url',
			'notifyUrl' => 'http://' . $_SERVER['HTTP_HOST'] . '/wp-json/apis/alipay_notifyUrl/?to_email=' . $applicant_email,
			'out_trade_no' => $applicant_uid,
			'subject' => $subject,
			'total_amount' => $total_amount,
			'body' => $body
		));						
		return $result;
	}
	*/

	function create_applicant($request){
		//检查验证码是否正确
		$result1 = checkout_email_code($request["email"], $request["email_code"]);
		$result2 = checkout_phone_code($request["phone"], $request["phone_code"]);
		if ($result1 && $result2) {
			#判断该邮箱是否注册，或者该注册邮箱是否unpaid
			global $wpdb;
			//查询票价
			$tickets = $wpdb->get_results( 'SELECT * FROM ' . TICKET_TABLE, OBJECT );
			$columns["total_amount"] = 0.00;
			$columns["uid"] = getOrderNo("ma");
			$columns["email"] = $request["email"];
			$columns["name"] = $request["name"];
			$columns["company"] = $request["company"];
			$columns["job"] = $request["job"];
			$columns["phone"] = $request["phone"];
			$columns["type"] = $request["type"];
			$columns["tickets"] = $request["tickets"];
			$columns["payment_type"] = $request["payment_type"];
			if ($tickets) {
				foreach ($tickets as $ticket) {
					if (in_array($ticket->uid, json_decode($request["tickets"]))) {
						$columns["total_amount"] +=  number_format($ticket->price, 2, '.', '');
					}
				}
			} else {
				return new WP_Error( 'database error', '数据库出错', array('status' => '505') );
			}
			if ($request["payment_type"] === 'alipay') {
				//发起支付宝请求
				// return alipay_pay($request["email"], getOrderNo("ma"), $columns["total_amount"], 'alipay_subject', 'alipay_body');
			}	else if ($request["payment_type"] === 'free') {	
				//直接开始付款后的业务逻辑
				$columns["trade_no"] = $columns["uid"];
				return after_paid($columns);
			}
		} else {
			//验证失败
			return new WP_Error( 'error', '验证失败', array('status' => '505') );
		}
		
	}
	
	/******************************************************/
	//自助快捷购票，只需要提供名字， 电话, 票
	add_action( 'rest_api_init', 'quick_applicant_hook' );
	function quick_applicant_hook() {
		register_rest_route(
			'apis', 'quick_applicant',
			array(
				'methods'  => 'POST',
				'callback' => 'quick_applicant',
				'permission_callback' => function () {
					return isEditor();
				},
				'args' => array(
				  'phone' => array(
					'validate_callback' => function ($param) {
						return isPhone($param);
					}
				  ),
				  'name' => array(
					'validate_callback' => function ($param) {
						return isNotNull($param);
					}
				  ),
				  'type' => array(
					'validate_callback' => function ($param) {
						return isInArray($param, ['audience', 'media']);
					}
				  ),
				  'tickets' => array(
					'validate_callback' => function ($param) {
						return count(json_decode($param)) == 1;
					}
				  )
				)	
			)
		);
	}
	function quick_applicant($request) {
		global $wpdb;
		$tickets = $wpdb->get_results( 'SELECT * FROM ' . TICKET_TABLE, OBJECT );
		$columns["uid"] = getOrderNo("ma");
		$columns["total_amount"] = 0.00;
		$columns["email"] = 'user@user.com';
		$columns["name"] = $request["name"];
		$columns["company"] = 'user';
		$columns["job"] = 'user';
		$columns["phone"] = $request["phone"];
		$columns["type"] = $request["type"];
		$columns["tickets"] = $request["tickets"];
		$columns["payment_type"] = 'offline';
		$columns["trade_no"] = $columns["uid"];
		$columns["checked"] = $request["tickets"];
		if ($tickets) {
			foreach ($tickets as $ticket) {
				if (in_array($ticket->uid, json_decode($request["tickets"]))) {
					$columns["total_amount"] +=  number_format($ticket->price, 2, '.', '');
				}
			}
		} else {
			return new WP_Error( 'database error', '数据库出错', array('status' => '505') );
		}
		$query = $wpdb->insert( 
			APPLICANT_TABLE, 
			$columns
		);
		if (!$query) {
			return new WP_Error( 'database error', '数据库出错', array('status' => '505') );
		} else {
			return array(
        		'status' => 'success',
        		'message' => '购买成功'
			);
		}
	}
	/******************************************************/
	//确认入场
	add_action( 'rest_api_init', 'check_in_hook' );
	function check_in_hook() {
		register_rest_route(
			'apis', 'check_in',
			array(
				'methods'  => 'POST',
				'callback' => 'check_in',
				'args' => array(
				  'uids' => array(
					'validate_callback' => function ($param) {
						return count(json_decode($param)) !== 0;
					}
				  ),
				  'date' => array(
					'validate_callback' => function ($param) {
						return isNotNull($param);
					}
				  )
				),
				'permission_callback' => function () {
					return isEditor();
				}
			)
		);
	}
	function check_in($request) {
		global $wpdb;
		$uids = json_decode($request["uids"]);
		$date = $request['date'];
		$media_ticket = 'media-' . $date;
		$audience_ticket = 'audience-' . $date;
		$wpdb->query('START TRANSACTION');
		$ts = [];
		$ok = true;
		foreach ($uids as $uid) {
			$ts[$uid . '1'] = $wpdb -> get_results('SELECT * FROM ' . APPLICANT_TABLE . " WHERE uid='{$uid}'", OBJECT);
			if ($ts[$uid . '1']) {
				// 判断是什么类型
				$ts[$uid . '1'][0]->type == 'media' ? $ticket = $media_ticket : $ticket = $audience_ticket;
				$checked_tickets = json_decode($ts[$uid . '1'][0]->checked);
				$tickets_bought = json_decode($ts[$uid . '1'][0]->tickets);
				// 判断票是否已经checkin并且是否存在
				if (!in_array($ticket, $checked_tickets) && in_array($ticket, $tickets_bought)) {
					array_push($checked_tickets, $ticket);
					$ts[$uid . '2'] = $wpdb -> update(APPLICANT_TABLE, array('checked' => json_encode($checked_tickets)), array('uid' => $uid));
					continue;
				} else {
					$ok = false;
					break;
				}
			} else {
				$ok = false;
				break;
			}
		}
		if ($ok) {
			$wpdb->query('COMMIT');
			return array(
        		'status' => 'success',
        		'message' => '入场成功'
			);
		} else {
			$wpdb->query('ROLLBACK');
			return new WP_Error( 'database error', '入场失败， 不存在票或重复刷票可能', array('status' => '505') );
		}
	}
	/******************************************************/
	
	//定义alipay的notifyUrl接口
	add_action( 'rest_api_init', 'alipay_notifyUrl_hook' );
	function alipay_notifyUrl_hook() {
		register_rest_route(
			'apis', 'alipay_notifyUrl',
			array(
				'methods'  => 'POST',
				'callback' => 'alipay_notifyUrl'
			)
		);
	}
	//支付宝回调方法
	/*
	function alipay_notifyUrl($request){
		#支付宝请求
		require_once("utils/payment/payment.php");		
		#创建alipay实例
		$alipay = new Alipayment();
		#用于输出日志
		$str = '';
		#用于校验
		$arr = [];
		#用于返回校验结果
		foreach ($_POST as $key => $value) {
			$str .= $key . '=' . stripslashes($value) . '&';
			#自传参数不参加校验，自传参数目前只有to_email
			if ($key === 'to_email') {
				continue;
			}
			$arr[$key] = stripslashes($value);		
		}
		$str = substr($str,0,strlen($str)-1);
		$flag = $alipay->check($arr);
		write_log_file("支付宝验签开始-----------------");
		if ($flag) {
			write_log_file("验签成功");
			write_log_file($str);
			write_log_file("支付宝验签结束-----------------");
			#验签成功，开始业务验证
			if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
				#判断是否存在该uid用户, 总金额是否正确，appid是否一致
				global $wpdb;
				require("utils/payment/config.php");
				$applicant = $wpdb->get_row( "SELECT * FROM " . APPLICANT_TABLE . " WHERE uid = '{$_POST['out_trade_no']}' ", OBJECT );
				if ($applicant) {
					write_log_file("查到这个用户");
				} else {
					write_log_file("查不到这个用户");
				}
				if ($applicant->total_amount === $_POST['total_amount']) {
					write_log_file("金额对");
				} else {
					write_log_file("金额不对");
				}
				if ($alipayConfig['appId'] === $_POST['app_id']) {
					write_log_file("id in config : " . $alipayConfig['appId']);
					write_log_file("id in params : " . $_POST['app_id']);
				} else {
					write_log_file("id in config : " . $alipayConfig['appId']);
					write_log_file("id in params : " . $_POST['app_id']);
				}
				if ($applicant && $applicant->total_amount === $_POST['total_amount'] && $alipayConfig['appId'] === $_POST['app_id']) {
					#实现业务逻辑
					after_paid($_POST['to_email'], $_POST['out_trade_no'], $_POST['trade_no']);
				}
			}
			header( 'Content-Type: text/plain; charset=' . get_option( 'blog_charset' ) );
			return 'success';
		} else {
			write_log_file("验签失败");
			write_log_file($str);
			write_log_file("支付宝验签结束-----------------");
			header( 'Content-Type: text/plain; charset=' . get_option( 'blog_charset' ) );
			return 'fail';
		}		
	}
	*/
	/******************************************************/
	
	//define excel api
	
	//define exporting method
	function export_excel($table_name) {   
	    global $wpdb;
		$applicants = $wpdb->get_results( "SELECT * FROM " . $table_name, OBJECT );
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
				$index = 0;
				foreach($applicant as $key => $value) {
					$index++;
					if ($key === 'id') {
						$result .= "<td>{$index}</td>";
					} else {
						$result .= "<td>{$value}</td>";
					}
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
	add_action( 'rest_api_init', 'applicant_excel_hook' );
	function applicant_excel_hook() {
		register_rest_route(
			'apis', 'applicant_excel',
			array(
				'methods'  => 'GET',
				'callback' => 'applicant_excel',
				'permission_callback' => function () {
					return isAdministrator();
				}
			)
		);
	}
	
	function applicant_excel($request){
		return export_excel(APPLICANT_TABLE);
	}
?>

<?php
	//记录HTTP log
	make_httpLog_file();
	function make_httpLog_file(){
		//log文件名
		$filename = 'httpLogs.txt';
		$word = '';
		//去除rc-ajax评论以及cron机制访问记录
		if(strstr($_SERVER["REQUEST_URI"],"rc-ajax")== false
		&& strstr($_SERVER["REQUEST_URI"],"wp-cron.php")== false ) {
			$word .= date('Y-m-d H:i:s',$_SERVER['REQUEST_TIME'] + 3600*8) . " ";
			//访问页面
			$word .= $_SERVER["REQUEST_URI"] ." ";
			//协议
			$word .= $_SERVER['SERVER_PROTOCOL'] ." ";
			//方法,POST OR GET
			$word .= $_SERVER['REQUEST_METHOD'] . "\n";
			//传递参数
			$word .= json_encode($_POST) . "\n";
			//跳转地址
			$word .= "FROM " . $_SERVER['HTTP_REFERER'] . "\n";
			$word .= "\n";
			$fh = fopen('wp-content/themes/cms/'.$filename, "a");
			fwrite($fh, $word);
			fclose($fh);
		}
	}
	
	//自定义log
	function write_log_file($msg){
		//log文件名
		$filename = 'myLogs.txt';
		$word = $msg;
		$word .= "\n";
		$fh = fopen('wp-content/themes/cms/'.$filename, "a");
		fwrite($fh, $word);
		fclose($fh);
	}
?>