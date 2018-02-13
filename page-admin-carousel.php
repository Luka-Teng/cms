<?php get_header('admin'); ?>
<?php if (current_user_can( 'administrator' )) : ?>
	<?php $result = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'carousel', OBJECT ) ?>
	<?php echo json_encode(get_cities()) ?>
	<?php for ($i = 1; $i <= count($result); $i++) { ?>
		<div class="clearfix">
			<div class="title top-gap-1 bot-gap-1" style="margin-left:4%">轮播图<?php echo $i ?></div>
			<div class="admin-block col-5 pull-left" style="margin-left:4.165%">
				<a class="file btn btn-default width-100">
					<input type='file' accept="image/*" name="carousel_<?php echo $i ?>_1" id='carousel_<?php echo $i ?>_1'>
					上传
				</a>
				<a class="btn btn-info width-100 left-gap-1 carousel-upload-btn" data-carousel="carousel_<?php echo $i ?>_1">提交</a>
				<a class="btn btn-danger width-100 left-gap-1 carousel-delete-btn" data-carousel="carousel_<?php echo $i ?>_1">删除</a>
				<div class="top-gap-1 image-container">
					<img id='carousel-target-<?php echo $i ?>-1' 
						src="<?php echo equal_and_set_values($result[$i-1]->url_1, '/', get_template_directory_uri()."/img/alt.jpg",  'http://' . $_SERVER['HTTP_HOST'] . '/' . $result[$i-1]->url_1) ?>">
				</div>
			</div>
			<div class="admin-block col-5 pull-left" style="margin-left:8.33%">
				<a class="file btn btn-default width-100">
					<input type='file' accept="image/*" name="carousel_<?php echo $i ?>_2" id='carousel_<?php echo $i ?>_2'>
					上传
				</a>
				<a class="btn btn-info width-100 left-gap-1 carousel-upload-btn" data-carousel="carousel_<?php echo $i ?>_2">提交</a>
				<a class="btn btn-danger width-100 left-gap-1 carousel-delete-btn" data-carousel="carousel_<?php echo $i ?>_2">删除</a>
				<div class="top-gap-1 image-container">
					<img id='carousel-target-<?php echo $i ?>-2' 
						src="<?php echo equal_and_set_values($result[$i-1]->url_2, '/', get_template_directory_uri()."/img/alt.jpg",  'http://' . $_SERVER['HTTP_HOST'] . '/' . $result[$i-1]->url_2) ?>">
				</div>
			</div>
		</div>
		<?php 
			if($i != (count($result))) {
				echo '<div class="normal-divider" style="margin-left:4.165%;margin-right:4.165%;margin-top:20px"></div>';
			}
		?>	
	<?php } ?>
	
<?php else : ?>
	<script>
		window.location = '/login'
	</script>
<?php endif ?>
<?php get_footer('admin'); ?>