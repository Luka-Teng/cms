<?php get_header('admin'); ?>
<?php if (current_user_can( 'administrator' )) : ?>
	<?php $categories = get_all_categories(); ?>
	<?php foreach($categories as $cat) { ?>
		<a class="admin-block top-gap-1 bot-gap-1" href="/admin-post-show?cat_id=<?php echo $cat->term_id ?>">
			<p><?php echo $cat->name ?></p>
		</a>
	<?php } ?>
<?php else : ?>
	<script>
		window.location = '/'
	</script>
<?php endif ?>
<?php get_footer('admin'); ?>
