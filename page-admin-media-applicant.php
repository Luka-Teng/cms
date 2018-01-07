<?php get_header('admin'); ?>
<?php if (current_user_can( 'administrator' )) : ?>
	<?php $paged = get_query_var('paged') ? get_query_var('paged') : 1; ?>
	<?php $data_per_page = 10 ?>
	<?php $result = get_paginated_data('media_applicant', $paged, $data_per_page); ?>
	<?php echo json_encode($result); ?>
	<div class="posts-nav">
		<?php 
			echo paginate_links(array(
			  'total' => get_paginated_length('media_applicant', $data_per_page),
			  'prev_next' => 0,
			  'mid_size' => 2
			));
		?>
	</div>
<?php else : ?>
	<script>
		window.location = '/login'
	</script>
<?php endif ?>
<?php get_footer('admin'); ?>