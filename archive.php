<?php get_header(); ?>
<link rel="stylesheet" href="<?php echo get_static_url('/css/content.css') ?>">
<section class="article-wrapper clearfix">
	<?php get_sidebar(); ?>
	<?php if (is_category()) { ?>
		<?php $category = get_queried_object(); ?>
		<?php #echo json_encode($category);?>
		<div class="article-zone">
			<div class="article-zone-bread">
				<a href="#"><?php echo $category -> cat_name ?></a>
			</div>
			<div class="article-zone-text">
				<?php if ($category -> category_nicename === 'cities') {?>
					<?php #针对城市之窗的所有城市列举 ?>
					<?php $paged = $_GET['page'] ? $_GET['page'] : 1; ?>
					<?php $data_per_page = 10; ?>
					<?php $cities = get_cities($paged, $data_per_page); ?>
					<?php foreach($cities as $city) { ?>
						<a class="tmp-a" href="/<?php echo $city->category_nicename?>"><?php echo $city->cat_name ?></a>
					<?php } ?>
					<div class="posts-nav">
						<?php 
							echo paginate_links(array(
							  'format' => '?page=%#%',
							  'total' => ceil(count(get_cities())/$data_per_page),
							  'prev_next' => 0,
							  'mid_size' => 2,
							  'current' => max( 1, $_GET['page'] )
							));
						?>
					</div>
				<?php } else { ?>
					<?php if (have_posts()) { ?>
						<div class="news-zone-content">	
							<ul style="padding:0px">
								<?php while (have_posts()) : the_post(); ?>
									<li><span class="glyphicon glyphicon-play"></span><a href="<?php echo get_the_permalink(); ?>"><?php echo get_the_title(); ?></a></li>
								<?php endwhile; ?>	
							</ul>
						</div>
					<?php } ?>
					<?php echo paginate_links(); ?>
				<?php } ?>
			</div>
		</div>
	<?php } ?>	
</section>
<?php get_footer(); ?>