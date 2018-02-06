<?php get_header('admin'); ?>
<?php if (current_user_can( 'administrator' )) : ?>
	<?php $result = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'banner', OBJECT ) ?>
	<?php for ($i = 1; $i <= (count($result) - 1); $i++) { ?>
		<div class="clearfix <?php echo $i > 4 ? 'col-4' : 'col-3' ?> pull-left" style="padding:10px">
			<div class="title top-gap-1 bot-gap-1">BANNER<?php echo $i ?></div>
			<div class="admin-block">
				<a class="file btn btn-default <?php echo $i > 4 ? 'width-100' : '' ?>">
					<input type='file' name="banner_<?php echo $i ?>" id='banner_<?php echo $i ?>'>
					上传
				</a>
				<a class="btn btn-info left-gap-1 <?php echo $i > 4 ? 'width-100' : '' ?> banner-upload-btn" data-banner="banner_<?php echo $i ?>">提交</a>
				<div class="top-gap-1 image-container">
					<img id='banner-target-<?php echo $i ?>' 
						src="<?php echo equal_and_set_values($result[$i-1]->image_url, '/', get_template_directory_uri()."/img/alt.jpg",  'http://' . $_SERVER['HTTP_HOST'] . '/' . $result[$i-1]->image_url) ?>">
				</div>
				<input class="form-control top-gap-1" name="banner_<?php echo $i ?>_title" id="banner_<?php echo $i ?>_title" placeholder="标题" value=<?php echo $result[$i - 1] -> title ?>>
				<input class="form-control top-gap-1" name="banner_<?php echo $i ?>_link" id="banner_<?php echo $i ?>_link" placeholder="链接" value=<?php echo $result[$i - 1] -> link ?>>
			</div>
		</div>
	<?php } ?>
	<div class="clearfix"></div>
	<div class="form-divider"></div>
	<div class="clearfix" style="padding:10px">
		<div class="title top-gap-1 bot-gap-1">BRAND品牌</div>
		<div class="admin-block text-center">
			<a class="file btn btn-default width-100">
				<input type='file' name="banner_<?php echo count($result) ?>" id='banner_<?php echo count($result) ?>'>
				上传
			</a>
			<a class="btn btn-info left-gap-1 width-100 banner-upload-btn" data-banner="banner_<?php echo count($result) ?>">提交</a>
			<div class="top-gap-1 image-container">
				<img id='banner-target-<?php echo count($result) ?>' 
					src="<?php echo equal_and_set_values($result[(count($result)-1)]->image_url, '/', get_template_directory_uri()."/img/alt.jpg",  'http://' . $_SERVER['HTTP_HOST'] . '/' . $result[(count($result)-1)]->image_url) ?>">
			</div>
		</div>
	</div>
<?php else : ?>
	<script>
		window.location = '/login'
	</script>
<?php endif ?>
<?php get_footer('admin'); ?>