<?php get_header('admin'); ?>
<?php if (current_user_can( 'administrator' )) : ?>
	<?php 
		$post_id = $_GET['postid'];
		$current_post = get_post($post_id);
	?>
	<?php if ($current_post->post_type == 'post') : ?>	
		<div class="form">
			<div class="title top-gap-1 bot-gap-1 left-gap-1"><?php echo get_category($current_post->post_category[0])->name ?></div>
			<input id='editpost-id' hidden value="<?php echo $post_id ?>">
			<div class="form-item">
				<p class="form-item-title">Title</p>
				<input class="form-control required" name="title" id="title" value="<?php echo $current_post->post_title  ?>">
			</div>
			<input hidden name="category" id="category" value="<?php echo $current_post->post_category[0] ?>">
			<div class="form-divider"></div>
			<div class="form-item top-gap-1">
				<p class="form-item-title">Content</p>
				<?php wp_editor($current_post->post_content, 'newpost', array(
					media_buttons => true
				)) ?>
			</div>
		</div>
		
		<div class="clearfix">
			<a href="javascript:void(0)" id="editpost-btn" class="btn btn-primary pull-right top-gap-1 right-gap-1">UPDATE</a>
		</div>
	<?php else : ?>
		<div>no post exsiting</div>
	<?php endif ?>
<?php else : ?>
	<script>
		window.location = '/login'
	</script>
<?php endif ?>
<?php get_footer('admin'); ?>