<?php get_header(); ?>
<?php if (current_user_can( 'administrator' )) : ?>
	<?php get_sidebar('admin') ?>
	<a class="admin-block top-gap-1 bot-gap-1" href="/admin-post-index">内容分发模块</a>
	<!--
	<a class="admin-block"></a>
	<a class="admin-block"></a>
	-->
<?php else : ?>
	<script>
		window.location = '/'
	</script>
<?php endif ?>
<?php get_footer(); ?>