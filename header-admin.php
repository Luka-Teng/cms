<!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo('charset'); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1" >
		<title><?php bloginfo('name'); ?></title>
		<link rel="stylesheet" href="<?php echo get_template_directory_uri() . '/css/backend.css' ?>" type="text/css" media="all">
		<?php wp_head(); ?>
	</head>	
<body <?php body_class(); ?>>	
	<div class="body-wrapper">
	
		<!--nav-->
		<nav class="navbar navbar-inverse border-radius-0" role="navigation">
			<div class="container-fluid">
				<div class="navbar-header">
					<a class="navbar-brand" href="/admin-index">CMS</a>
					<span class="text-muted nav-sub-brand">后台模块</span>
				</div>
				<div>
					<ul class="nav navbar-nav pull-right">
						<li><a href="#"><?php echo 'hello, '.wp_get_current_user()->nickname ?></a></li>
						<?php if (!is_user_logged_in()) : ?>
							<li><a href="/login">登录</a></li>					
						<?php else : ?>
							<li><a href="<?php echo wp_logout_url("/") ?>">注销</a></li>
						<?php endif ?>	
					</ul>
				</div>
			</div>
		</nav>
		<!--nav-->
		
		<!--content-wrapper-->
		<div class="content-wrapper clearfix">
			<!--sidebar-->
			<div class="sidebar">
				<div class="side-block">
					<a class="sidebar-block-title" data-toggle="collapse" href="#side-bar-1">内容分发模块</a>
					<div class="collapse in sidebar-block-children" id="side-bar-1">
						<?php $categories = get_all_categories(); ?>
						<?php foreach($categories as $cat) { ?>
							<a href="/admin-post-show?cat_id=<?php echo $cat->term_id ?>"><?php echo $cat->name ?></a>
						<?php } ?>
					</div>
				</div>
				<div class="side-block">
					<a class="sidebar-block-title" href="javascript:void(0)">轮播图管理</a>
				</div>
				<div class="side-block">
					<a class="sidebar-block-title" href="javascript:void(0)">用户申请</a>
				</div>				
			</div>
			<!--main content-->
			<div class="main">
				<!--common tools-->
				<?php get_template_part('common/loading', '') ?>
				<?php get_template_part('common/error', '') ?>
				<!--common tools-->