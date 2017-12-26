<?php get_header('admin'); ?>
<?php if (current_user_can( 'administrator' )) : ?>
	<?php get_sidebar('admin') ?>
	Welcome to a new CMS template!
<?php else : ?>
	<script>
		window.location = '/'
	</script>
<?php endif ?>
<?php get_footer('admin'); ?>