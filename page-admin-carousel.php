<?php get_header('admin'); ?>
<?php if (current_user_can( 'administrator' )) : ?>
	<input type='file' name='carousel_1' id='carousel_1'>
	<button id="carousel-upload-btn">submit</button>
	<button id="carousel-delete-btn" data-carousel="carousel_1">delete</button>
<?php else : ?>
	<script>
		window.location = '/login'
	</script>
<?php endif ?>
<?php get_footer('admin'); ?>