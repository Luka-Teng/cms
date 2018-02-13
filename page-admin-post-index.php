<?php get_header('admin'); ?>
<?php if (current_user_can( 'administrator' )) : ?>
	<p class="admin-title">
		城市之窗
	</p>
	<?php $paged = get_query_var('paged') ? get_query_var('paged') : 1; ?>
	<?php $data_per_page = 12; ?>
	<?php $cities = get_cities($paged, $data_per_page); ?>
	<div class="clearfix">
		<?php foreach($cities as $city) { ?>
			<div class="col-4 pull-left top-gap-1 bot-gap-1" style="padding:0px 10px">
				<a class="admin-block" href="/admin-post-show?cat_id=<?php echo $city->term_id ?>">
					<p><?php echo $city->name ?></p>
				</a>
			</div>
		<?php } ?>
	</div>
	<div class="posts-nav">
		<?php 
			echo paginate_links(array(
			  'total' => ceil(count(get_cities())/$data_per_page),
			  'prev_next' => 0,
			  'mid_size' => 2
			));
		?>
	</div>
<?php else : ?>
	<script>
		window.location = '/login'
	</script>
<?php endif ?>
<?php get_footer('admin'); ?>
