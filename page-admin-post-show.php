<?php get_header('admin'); ?>
<?php if (current_user_can( 'administrator' )) : ?>
	<?php 
		$category = $_GET['cat_id'];
		$ourCurrentPage = get_query_var('paged')
	?>
	<?php $catPosts = new WP_Query(array(
		'cat' => $category,
		'posts_per_page' => 5,
		'paged' => $ourCurrentPage
	)); ?>
	<?php $current_cat = get_category($category) ?>	
	<p class="admin-title">
		<?php echo $current_cat->name ?>
		<a class="btn btn-info pull-right" href="/admin-post-new?cat_id=<?php echo $category ?>">new</a>
	</p>
	<?php if ($catPosts->have_posts()) : ?>
		<?php while ($catPosts->have_posts()) : $catPosts->the_post(); ?>
			<div class="admin-block top-gap-1 bot-gap-1 clearfix">				
				<span><?php echo get_the_title(); ?></span>
				<a class="btn btn-danger pull-right btn-sm" id="delpost-btn" href="javascript:void(0)" data-value="<?php echo get_the_ID() ?>">delete</a>
				<a class="btn btn-warning pull-right btn-sm right-gap-1" href="/admin-post-edit?postid=<?php echo get_the_ID() ?>">edit</a>
			</div>
		<?php endwhile; ?>
		<div class="posts-nav">
			<?php 
				echo paginate_links(array(
				  'total' => $catPosts->max_num_pages,
				  'prev_next' => 0,
				  'mid_size' => 2
				));
			?>
		</div>
	<?php endif ?>
<?php else : ?>
	<script>
		window.location = '/'
	</script>
<?php endif ?>
<?php get_footer('admin'); ?>
