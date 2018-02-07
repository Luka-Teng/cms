<?php get_header(); ?>
<link rel="stylesheet" href="<?php echo get_static_url('/css/content.css') ?>">
<section class="article-wrapper clearfix">
	<?php get_sidebar(); ?>
	<?php if (have_posts()) : ?>
		<?php while (have_posts()) : the_post(); ?>
		<div class="article-zone">
			<div class="article-zone-bread">
				<a href="#"><?php echo get_the_category()[0] -> name; ?></a> >
				<a href="#" style="color: #0076ff;"><?php echo the_title(); ?></a>
			</div>
			<div class="article-zone-text">
				<div class="article-info">
					<span>发布时间：<?php echo the_time('Y-m-d H:m:s'); ?></span>&nbsp;&nbsp;&nbsp;&nbsp;
				</div>
				<h3><?php echo the_title(); ?></h3>
				<?php echo the_content(); ?>
			</div>
		</div>
		<?php endwhile ?>
	<?php else : ?>
	<?php endif ?>
</section>
<?php get_footer(); ?>