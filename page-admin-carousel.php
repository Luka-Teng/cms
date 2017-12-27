<?php get_header('admin'); ?>
<?php if (current_user_can( 'administrator' )) : ?>
	<form action='/wp-json/apis/upload' method='POST' enctype="multipart/form-data">
	<input type='file' name='file'>
	<button>submit</button>
	</form>
<?php else : ?>
	<script>
		window.location = '/login'
	</script>
<?php endif ?>
<?php get_footer('admin'); ?>