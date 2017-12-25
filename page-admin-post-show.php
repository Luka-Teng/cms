<?php get_header(); ?>
<?php if (current_user_can( 'administrator' )) : ?>
	<?php 
		$category = $_GET['cat_id'];
		$ourCurrentPage = get_query_var('paged')
	?>
	<?php $catPosts = new WP_Query(array(
		'cat' => $category,
		'posts_per_page' => 10,
		'paged' => $ourCurrentPage
	)); ?>
	<?php $current_cat = get_category($category) ?>	
	<p class="admin-title"><?php echo $current_cat->name ?></p>
	<a class="admin-newpost" href="/admin-post-new?cat_id=<?php echo $category ?>">new</a>
	<?php if ($catPosts->have_posts()) : ?>
		<?php while ($catPosts->have_posts()) : $catPosts->the_post(); ?>
			<div class="admin-block top-gap-1 bot-gap-1">				
				<span><?php echo get_the_title(); ?></span>
				<a class="admin-del-btn" id="delpost-btn" href="javascript:void(0)" data-value="<?php echo get_the_ID() ?>">delete</a>
				<a class="admin-edit-btn right-gap-1" href="/admin-post-edit?postid=<?php echo get_the_ID() ?>">edit</a>
			</div>
		<?php endwhile; ?>
	<?php endif ?>
<?php else : ?>
	<script>
		window.location = '/'
	</script>
<?php endif ?>
<?php get_footer(); ?>
