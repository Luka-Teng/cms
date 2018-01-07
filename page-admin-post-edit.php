<?php get_header('admin'); ?>
<?php if (current_user_can( 'administrator' )) : ?>
	<?php 
		$post_id = $_GET['postid'];
		$current_post = get_post($post_id);
	?>
	<?php if ($current_post->post_type == 'post') : ?>
		<div class="form">
			<input id='editpost-id' hidden value="<?php echo $post_id ?>">
			<div class="form-item">
				<p class="form-item-title">Title</p>
				<input class="form-control required" name="title" id="title" value="<?php echo $current_post->post_title  ?>">
			</div>
			<div class="form-divider"></div>
			<div class="form-item">
				<p class="form-item-title">Category</p>
				<select class="form-control" name="category" id="category">
					<?php $categories = get_all_categories(); ?>
					<?php foreach ($categories as $cat) { ?>
						<option value="<?php echo $cat->term_id ?>"
							<?php 
								if ($cat->term_id == $current_post->post_category[0]) {
									echo "selected";
								} 
							?>
						><?php echo $cat->name ?></option>
					<?php } ?>				
				</select>
			</div>
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