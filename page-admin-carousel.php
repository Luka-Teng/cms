<?php get_header('admin'); ?>
<?php if (current_user_can( 'administrator' )) : ?>
	<?php $result = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'carousel', OBJECT ) ?>
	<?php for ($i = 1; $i <= count($result); $i++) { ?>
		<div class="admin-block">
			<a class="file btn btn-default width-100">
				<input type='file' name="carousel_<?php echo $i ?>" id='carousel_<?php echo $i ?>'>
				上传
			</a>
			<a class="btn btn-info width-100 left-gap-1 carousel-upload-btn" data-carousel="carousel_<?php echo $i ?>">提交</a>
			<a class="btn btn-danger width-100 left-gap-1 carousel-delete-btn" data-carousel="carousel_<?php echo $i ?>">删除</a>
			<div class="top-gap-1 image-container">
				<img id='carousel-target-<?php echo $i ?>' 
					src="<?php echo equal_and_set_values($result[$i-1]->url, '/', get_template_directory_uri()."/img/alt.jpg",  'http://' . $_SERVER['HTTP_HOST'] . '/' . $result[$i-1]->url) ?>">
			</div>
			<div class="admin-block-num">
				<i><?php echo $i ?></i>
			</div>	
		</div>
		<?php 
			if($i != (count($result))) {
				echo '<div class="normal-divider"></div>';
			}
		?>		
	<?php } ?>
<?php else : ?>
	<script>
		window.location = '/login'
	</script>
<?php endif ?>
<?php get_footer('admin'); ?>