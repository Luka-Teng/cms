<?php get_header('admin'); ?>
<?php if (current_user_can( 'administrator' )) : ?>
	<?php 
		$cat_id = $_GET['cat_id'];
	?>
	<div class="form">
		<div class="form-item">
			<p class="form-item-title">Title</p>
			<input class="form-control required" name="title" id="title">
		</div>
		<div class="form-divider"></div>
		<div class="form-item">
			<p class="form-item-title">Category</p>
			<select class="form-control" name="category" id="category">
				<?php 
					$categories = get_all_categories();
					foreach ($categories as $cat) {
				?>
					<option value="<?php echo $cat->term_id ?>"
						<?php if ($cat->term_id == $cat_id) {
							echo "selected";
						} ?>
					><?php echo $cat->name ?></option>
				<?php } ?>
			</select>
		</div>
		<div class="form-divider"></div>
		<div class="form-item top-gap-1">
			<p class="form-item-title">Content</p>
			<?php wp_editor('', 'newpost', array(
				media_buttons => true
			)) ?>
		</div>
	</div>
	
	<div class="clearfix">
		<a href="javascript:void(0)" id="post-btn" class="btn btn-primary pull-right top-gap-1 right-gap-1">POST</a>
	</div>
<?php else : ?>
	<script>
		window.location = '/login'
	</script>
<?php endif ?>
<?php get_footer('admin'); ?>