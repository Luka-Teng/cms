<?php get_header(); ?>
<style>
.pc-show{
    display: block;
}
.mobile-show{
    display: none;
}
.slick-slide img {
    width: 100%;
}
.slider {
    height: auto;
}
.slider-wrapper {
    height: auto;
}
@media screen and (max-width: 768px) {
    .pc-show{
        display: none;
    }
    .mobile-show{
        display: block;
    }
}
</style>
<!--轮播图-->
<section class="row img-container pc-show">
	<div class="slider-wrapper">
		<?php $carousels = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'carousel', OBJECT ) ?>
		<?php for ($i = 0; $i < count($carousels); $i++) { ?>
			<?php if ($carousels[$i]->url_1 !== '/') : ?>
			<div class="slider">
				<img class="slider-img" src="<?php echo $carousels[$i]->url_1?>">
			</div>
			<?php endif ?>
		<?php } ?>		
	</div>
</section>
<section class="row img-container mobile-show">
	<div class="slider-wrapper">
		<?php for ($i = 0; $i < count($carousels); $i++) { ?>
			<?php if ($carousels[$i]->url_2 !== '/') : ?>
			<div class="slider">
				<img class="slider-img" src="<?php echo $carousels[$i]->url_2?>">
			</div>
			<?php endif ?>
		<?php } ?>		
	</div>
</section>

<!--banner-->
<?php $banners = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'banner', OBJECT ) ?>
<section class="row recent-container">
	<div class="col-sm-12">
		<div class="title">最新动态</div>
	</div>	
	<div class="col-sm-12">
		<div class="recent-wrapper">
			<?php for ($i = 0; $i < 4; $i++) { ?>
				<div class="recent-slider <?php if ($i === 0) :?>wide-slider<?php endif ?>">
					<a class="recent-box" href="<?php echo $banners[$i]->link?>">
						<div class="recent-img-container">
							<div>
								<img class="recent-img" src="<?php echo $banners[$i]->image_url?>" />
							</div>
						</div>
						<div class="recent-text-container">
							<div class="recent-text">
								<?php echo $banners[$i]->title?>
							</div>
						</div>
					</a>
				</div>
			<?php } ?>
		</div>
	</div>
</section>
<section class="other-container">
	<div class="other-wrapper clearfix">
		<?php for ($i = 4; $i < 7; $i++) { ?>
			<a href="<?php echo $banners[$i]->link?>" class="other-item">
				<div class="other-item-img-container">
					<img src="<?php echo $banners[$i]->image_url?>" class="other-item-img">
				</div>
				<div class="other-item-text-container">
					<div class="other-item-text">
						<?php echo $banners[$i]->title?>
					</div>
				</div>
			</a>
		<?php } ?>
	</div>
</section>

<section class="row news-container">
	<div class="news-wrapper">
		<div class="news-zone wide-news-zone">
			<div class="news-zone-header">
				展商动态
			</div>
			<div class="news-zone-content">
				<ul>
					<?php $left_posts = get_posts(array(
						'category_name' => 'show-news',
						'numberposts' => 8
					)); ?>
					<?php for ($i = 0; $i < count($left_posts); $i++) { ?>
						<li>
							<span class="glyphicon glyphicon-play"></span>
							<a href="<?php echo get_permalink($left_posts[$i]->ID)?>"><?php echo $left_posts[$i]->post_title ?></a>
							<span class="news-date"><?php echo explode(" ", $left_posts[$i]->post_modified)[0] ?></span>
						</li>
					<?php } ?>					
				</ul>
			</div>
		</div>
		<div class="news-zone narrow-news-zone">
			<div class="news-zone-header">
				会展亮点
			</div>
			<div class="news-zone-content">
				<ul>
					<?php $right_posts = get_posts(array(
						'category_name' => 'show-highlights',
						'numberposts' => 8
					)); ?>
					<?php for ($i = 0; $i < count($right_posts); $i++) { ?>
						<li>
							<span class="glyphicon glyphicon-play"></span>
							<a href="<?php echo get_permalink($right_posts[$i]->ID)?>"><?php echo $right_posts[$i]->post_title ?></a>
							<span class="news-date"><?php echo explode(" ", $right_posts[$i]->post_modified)[0] ?></span>
						</li>
					<?php } ?>
				</ul>
			</div>
		</div>
	</div>
</section>
<section class="row support-container">
	<div class="support-jmg-container">
		<img src="<?php echo $banners[7]->image_url?>" />
	</div>
</section>
<?php get_footer(); ?>