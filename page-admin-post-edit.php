<?php get_header(); ?>
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
				<input class="form-item-input block" name="title" id="title" value="<?php echo $current_post->post_title  ?>">
			</div>
			<div class="form-item">
				<p class="form-item-title">Category</p>
				<select class="form-item-input block" name="category" id="category">
					<?php 
						$categories = get_all_categories();
						foreach ($categories as $cat) {
							echo '<option value=' . $cat->term_id . '>' . $cat->name . '</option>';
						}
					?>
				</select>
			</div>
			<div class="form-item top-gap-1">
				<p class="form-item-title">Content</p>
				<?php wp_editor($current_post->post_content, 'newpost', array(
					media_buttons => true
				)) ?>
			</div>
		</div>
		
		<div class="clearfix" style="margin-right:10%">
			<a href="javascript:void(0)" id="editpost-btn" class="btn btn-default bot-gap-1 pull-right">UPDATE</a>
		</div>
	<?php else : ?>
		<div>no post exsiting</div>
	<?php endif ?>
<?php else : ?>
	<script>
		window.location = '/'
	</script>
<?php endif ?>
<?php get_footer(); ?>