<?php 
	//remove dafault dashboard
	add_filter('show_admin_bar', '__return_false');
	add_theme_support('post-thumbnails');
?>

<?php 
	//load all js or css resources
	function learning_resources() {	
		wp_enqueue_style('bootstrap_css', get_stylesheet_uri());
		wp_enqueue_style('style', get_template_directory_uri() . '/css/bootstrap.min.css');		
		wp_enqueue_script('main_js', get_template_directory_uri() . '/js/main.js', array(
			jquery => 'jquery'
		), 1.0, true);
		wp_enqueue_script('bootstrap_js', get_template_directory_uri() . '/js/bootstrap.min.js');
		wp_localize_script('main_js', 'magicalData', array(
			'nonce' => wp_create_nonce('wp_rest'),
			'siteURL' => get_site_url()
		));		
	}
	add_action('wp_enqueue_scripts', 'learning_resources');
 ?>

<?php 
	//show all categories
	function get_all_categories () {
		return get_categories(array(
			taxonomy => 'category',
			hide_empty => false,
			exclude => 1,
			orderby => 'term_id',
			order => 'ASC'
		));
	}
?>

<?php
	//adding new tables
	// 声明全局变量$wpdb 和 数据表名常量
	global $wpdb;
	define('CAROUSEL_TABLE', $wpdb->prefix . 'Carousel');
	define('REGISTRATION_TABLE', $wpdb->prefix . 'Registration');
	// 插件激活时，运行回调方法创建数据表, 在WP原有的options表中插入插件版本号
	add_action('after_setup_theme', 'createTable');
	function createTable() {
	    /*
	     * We'll set the default character set and collation for this table.
	     * If we don't do this, some characters could end up being converted 
	     * to just ?'s when saved in our table.
	     */
	    $charset_collate = '';

	    if (!empty($wpdb->charset)) {
	      $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
	    }

	    if (!empty( $wpdb->collate)) {
	      $charset_collate .= " COLLATE {$wpdb->collate}";
	    }

	    $sql1 = "CREATE TABLE " . CAROUSEL_TABLE . " (
	        id mediumint(9) NOT NULL AUTO_INCREMENT,
	        time timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	        url varchar(55) DEFAULT '' NOT NULL,
	        name varchar(20) NOT NULL,
	        UNIQUE KEY id (id)
	    ) $charset_collate;";

	    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	    dbDelta( $sql1 );
	}
?>

<?php
	//apis
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
				status => 'error',
				message => $user->get_error_message()
			);
		}
        return array(
        	status => 'success',
        	message => $user
        );
	}
	//define upload api 
	add_action( 'rest_api_init', 'upload_hook' );
	function upload_hook() {
		register_rest_route(
			'apis', 'upload',
			array(
				'methods'  => 'POST',
				'callback' => 'custom_upload',
			)
		);
	}
	function custom_upload($request){
		if ((($_FILES["file"]["type"] == "image/gif") || ($_FILES["file"]["type"] == "image/jpeg")
			|| ($_FILES["file"]["type"] == "image/jpg") || ($_FILES["file"]["type"] == "image/pjpeg"))
			&& ($_FILES["file"]["size"] < 20000)) {
			if ($_FILES["file"]["error"] > 0) {
				return new WP_Error( 'file error', $_FILES["file"]["error"], array(status => '505') );
			} else {
				$dir_path = 'wp-content/themes/cms/upload';
				$file_path = 'wp-content/themes/cms/upload/'.$_FILES["file"]["name"];
				if (!file_exists($dir_path)) {
					mkdir($dir_path, 0700);
				}
				if (!file_exists($file_path)) {
					move_uploaded_file($_FILES["file"]["tmp_name"], $file_path);
				}
				global $wpdb;
				$wpdb->insert( 'cms_carousel', array( url => $file_path ) ); 
				return array(
					status => '200',
					message => 'success'
				);
			}
	  } else {
	  	return new WP_Error( 'invalid file', 'invalid file', array(status => '505') );
	  }
	}
?>