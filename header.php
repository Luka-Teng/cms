<!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo('charset'); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1" >
		<title><?php bloginfo('name'); ?></title>
		<link rel="stylesheet" href="<?php echo get_template_directory_uri() . '/css/frontend.css' ?>" type="text/css" media="all">
		<?php wp_head(); ?>
	</head>	
<body <?php body_class(); ?>>	
	<div class="body-wrapper">
		<!-- site-header -->
		<header class="site-header">
			<a href="<?php echo home_url(); ?>"><?php bloginfo('name'); ?></a>
			<span>
				<?php echo 'hello, '.wp_get_current_user()->nickname ?>
			</span>
			<?php $categories = get_all_categories(); ?>
			<div class="nav">
				<?php foreach($categories as $cat) { ?>
					<div class="nav-item">
					 	<a href="<?php echo get_category_link($cat->term_id) ?>"><?php echo $cat->name ?></a>
					 	<div class="nav-item-child">
							<?php $all_posts = get_posts(array('category' => $cat->term_id)); ?>
							
							<?php foreach($all_posts as $single_post) { ?>
								<a href='<?php echo get_the_permalink($single_post->ID) ?>'>
									<?php echo $single_post->post_title; ?>
								</a>
							<?php } ?>
					 	</div>
				 	</div>
				<?php } ?>
			</div>
			<?php if (!is_user_logged_in()) : ?>
				<a href="/login" class="log" >登录</a>					
			<?php else : ?>
				<a href="<?php echo wp_logout_url("/") ?>" class='log' >注销</a>
			<?php endif ?>		
		</header><!-- /site-header -->
		<?php get_template_part('common/loading', '') ?>
		<?php get_template_part('common/error', '') ?>