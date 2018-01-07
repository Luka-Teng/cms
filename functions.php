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
		if ( $origin == $target) {
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
?>

<?php
	//adding new tables
	// 声明全局变量$wpdb 和 数据表名常量
	global $wpdb;
	define('CAROUSEL_TABLE', $wpdb->prefix . 'carousel');
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
	    //创建媒体登记数据库
	    if ($wpdb->get_var('show tables like "' . MEDIA_TABLE . '"') != MEDIA_TABLE) {
	    	$sql1 = "CREATE TABLE " . MEDIA_TABLE . " (
		        id mediumint(9) NOT NULL AUTO_INCREMENT,
		        time timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
		        company varchar(55) DEFAULT '' NOT NULL,
		        name varchar(20) NOT NULL,
				job varchar(20) NOT NULL,
				phone varchar(20) NOT NULL,
		        UNIQUE KEY id (id)
		    ) $charset_collate;";
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		    dbDelta( $sql1 );
	    }
	    //创建轮播图数据库
	    if ($wpdb->get_var('show tables like "' . CAROUSEL_TABLE . '"') != CAROUSEL_TABLE) {
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
		if ((($_FILES[$name]["type"] == "image/gif") || ($_FILES[$name]["type"] == "image/jpeg")
			|| ($_FILES[$name]["type"] == "image/jpg") || ($_FILES[$name]["type"] == "image/pjpeg") || ($_FILES[$name]["type"] == "image/png"))
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
/*
	//define excel api 
	add_action( 'rest_api_init', 'excel_hook' );
	function excel_hook() {
		register_rest_route(
			'apis', 'excel',
			array(
				'methods'  => 'GET',
				'callback' => 'excel'
			)
		);
	}
	//excel method
	function excel($request){
		//输出的文件类型为excel    
	    header("Content-type:application/vnd.ms-excel");    
	    //提示下载    
	    header("Content-Disposition:attachement;filename=Haoyunyun_".date("Ymd").".xls");    
	    //报表数据    
	    $ReportArr = array(array(1,2,3,4,5),    
	                        array('A','B','C','D','E'),    
	                        array('up','down','left','right','center'),    
	                        array('欢','迎','光','临','郝云云','的','CSDN','博客')    
	                    );    
	    $ReportContent = '';    
	    $num1 = count($ReportArr);    
	    for($i=0;$i<$num1;$i++){    
	        $num2 = count($ReportArr[$i]);    
	        for($j=0;$j<$num2;$j++){    
	            //ecxel都是一格一格的，用\t将每一行的数据连接起来    
	            $ReportContent .= $ReportArr[$i][$j]."\t";    
	        }    
	        //最后连接\n 表示换行    
	        $ReportContent .= "\n";    
	    }    
	    //用的utf-8 最后转换一个编码为gb    
	    $ReportContent = mb_convert_encoding($ReportContent,"gb2312","utf-8");    
	    //输出即提示下载    
	    echo $ReportContent;    
		}
		*/
?>