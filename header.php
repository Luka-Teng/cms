<!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo('charset'); ?>">
		<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
		<title><?php bloginfo('name'); ?></title>
		<link rel="stylesheet" href="<?php echo get_template_directory_uri() . '/lib/bootstrap/css/bootstrap.css' ?>" type="text/css" media="all">
		<link rel="stylesheet" href="<?php echo get_template_directory_uri() . '/lib/slick/slick.css' ?>" type="text/css" media="all">
		<link rel="stylesheet" href="<?php echo get_template_directory_uri() . '/lib/slick/slick-theme.css' ?>" type="text/css" media="all">
		<link rel="stylesheet" href="<?php echo get_template_directory_uri() . '/css/public.css' ?>" type="text/css" media="all">
		<link rel="stylesheet" href="<?php echo get_template_directory_uri() . '/css/header.css' ?>" type="text/css" media="all">
		<link rel="stylesheet" href="<?php echo get_template_directory_uri() . '/css/index.css' ?>" type="text/css" media="all">
		<style>
			html {
				font-size: 12px;
			}
		</style>
		<?php wp_head(); ?>
	</head>	
<body <?php body_class(); ?>>	

	<!-- site-header -->
	<header class="header-wrapper">
		<div class="header-row row">
			<div class="col-sm-5">
				<img src="<?php echo get_template_directory_uri() . '/img/Group 2.png' ?>">
			</div>
			<div class="col-sm-2"></div>
			<div class="col-sm-5" style="text-align: right;">
				<div class="search-container" style="margin-left: auto;">
					<div class="search-item search-icon">
						<span class="glyphicon glyphicon-search"></span>
					</div>
					<div class="search-item search-input-container">
						<input class="search-input" placeholder="站内搜索..." name="searchContent" />
					</div>
					<div class="search-item search-button pull-right">
						搜索
					</div>
				</div>
				<div class="language-container">
					<p class="">当前语言：中文/EN</p>
				</div>
			</div>
		</div>
		<div class="header-row row second-row">
			<div class="nav-container row">
				<ul class="menu-nav-list">
				<?php $categories = get_all_categories(); ?>
				<?php foreach($categories as $cat) { ?>
					<li class="menu-item">
						<span class="item-label-wrapper">
							<a href="/" class="menu-item-label"><?php echo $cat->name ?></a>
						</span>
						<div class="sub-menu">
							<?php $all_posts = get_posts(array('category' => $cat->term_id)); ?>
							<?php $loop_num = 1; ?>
							<?php foreach($all_posts as $single_post) { ?>
								<?php if ($loop_num % 2 === 1) { echo '<div class="row">'; } ?>									
									<div class="col-sm-6 sub-menu-item">
										<a href="<?php echo get_the_permalink($single_post->ID) ?>"><?php echo $single_post->post_title; ?></a>
									</div>
								<?php if ($loop_num % 2 === 0) { echo '</div>'; } ?>	
								<?php $loop_num++ ?>
							<?php } ?>
							<?php #观众中心需要增加一个注册页面 ?>
							<?php if ($cat->category_nicename === 'audience-center') { ?>
								<?php if ($loop_num % 2 === 1) { echo '<div class="row">'; } ?>									
									<div class="col-sm-6 sub-menu-item">
										<a href="/client-register">观众注册</a>
									</div>
								</div>	
							<?php #媒体中心需要增加一个注册页面 ?>
							<?php } elseif ($cat->category_nicename === 'media-center') { ?>
								<?php if ($loop_num % 2 === 1) { echo '<div class="row">'; } ?>									
									<div class="col-sm-6 sub-menu-item">
										<a href="/client-register">媒体注册</a>
									</div>
								</div>	
							<?php } else { ?>
								<?php if (count($all_posts) % 2 === 1) { echo '</div>'; } ?>
							<?php } ?>
						</div>
					</li>
				<?php } ?>
				</ul>
			</div>
		</div>
	</header>
	<header class="mobile-header-wrapper">
		<div class="clearfix">
			<div class="mobile-header-item show-nav">
				<span class="glyphicon glyphicon-list"></span>
			</div>
			<div class="mobile-header-item show-logo">
				<a href="./index.html"><img src="<?php echo get_template_directory_uri() . '/img/Group 2.png' ?>"></a>
			</div>
		</div>
	</header>
	<nav class="mobile-nav-container">
		<ul>
			<?php foreach($categories as $cat) { ?>
				<li>
					<div class="nav-first"><?php echo $cat->name ?></div>
					<ul>
						<?php $all_posts = get_posts(array('category' => $cat->term_id)); ?>
						<?php foreach($all_posts as $single_post) { ?>
							<li>
								<div class="nav-second"><a href="<?php echo get_the_permalink($single_post->ID) ?>"><?php echo $single_post->post_title; ?></a></div>
							</li>
						<?php } ?>
						<?php #观众中心需要增加一个注册页面 ?>
						<?php if ($cat->category_nicename === 'audience-center') { ?>								
							<li>
								<div class="nav-second"><a href="/client-register">观众注册</a></div>
							</li>
						<?php #媒体中心需要增加一个注册页面 ?>
						<?php } elseif ($cat->category_nicename === 'media-center') { ?>
							<li>
								<div class="nav-second"><a href="/client-register">媒体注册</a></div>
							</li>							
						<?php } ?>
					</ul>
				</li>
			<?php } ?>
		</ul>
	</nav><!-- /site-header -->
	
	<div class="content-container">
	<!--common tools-->
	<?php get_template_part('common/loading', '') ?>
	<?php get_template_part('common/error', '') ?>
	<?php get_template_part('common/flash', '') ?>
	<!--common tools-->