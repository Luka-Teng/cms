<?php get_header(); ?>
<div class="content-container">
	<!--轮播图-->
	<section class="row img-container">
		<div class="slider-wrapper">
			<?php $carousels = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'carousel', OBJECT ) ?>
			<?php for ($i = 0; $i < count($carousels); $i++) { ?>
				<?php if ($carousels[$i]->url_1 !== '/') : ?>
				<div class="slider">
					<img class="slider-img" src="<?php echo $carousels[$i]->url_1?>">
					<img class="slider-text" src="<?php echo get_static_url('/img/slider-text.png') ?>">
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
							'category' => 6,
							'numberposts' => 8
						)); ?>
						<?php for ($i = 0; $i < count($left_posts); $i++) { ?>
							<li>
								<span class="glyphicon glyphicon-play"></span>
								<a href="<?php echo get_permalink($left_posts[$i]->ID)?>"><?php echo $left_posts[$i]->post_title ?></a>
								<span class="news-date"><?php echo $left_posts[$i]->post_modified ?></span>
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
							'category' => 4,
							'numberposts' => 8
						)); ?>
						<?php for ($i = 0; $i < count($left_posts); $i++) { ?>
							<li>
								<span class="glyphicon glyphicon-play"></span>
								<a href="<?php echo get_permalink($right_posts[$i]->ID)?>"><?php echo $right_posts[$i]->post_title ?></a>
								<span class="news-date"><?php echo $right_posts[$i]->post_modified ?></span>
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
	<div class="row" style="background-color: #1a2e46; height: 1.2rem;"></div>
</div>
<!--
<div style="padding:30px;marign:10px auto">
	<div class="form-group">
		邮箱<input name="email" id="email" class="form-control">
	</div>
	
	<a href="javascript:void(0)" id="getcode-btn">获取验证码</a>
	
	<div class="form-group">
		验证码<input name="email_code" id="email_code" class="form-control">
	</div>
	<div class="form-group">
		名字<input name="name" id="name" class="form-control">
	</div>
	<div class="form-group">
		公司<input name="company" id="company" class="form-control">
	</div>
	<div class="form-group">
		职业<input name="job" id="job" class="form-control">
	</div>
	<div class="form-group">
		电话<input name="phone" id="phone" class="form-control">
	</div>
	<button id="create_media_applicant">submit</button>
</div>
-->
<?php get_footer(); ?>