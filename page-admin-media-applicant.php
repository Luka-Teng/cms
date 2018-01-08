<?php get_header('admin'); ?>
<?php if (current_user_can( 'administrator' )) : ?>
	<?php $paged = get_query_var('paged') ? get_query_var('paged') : 1; ?>
	<?php $data_per_page = 10 ?>
	<?php $result = get_paginated_data('media_applicant', $paged, $data_per_page); ?>
	<div class="clearfix">
		<a href="javascript:void(0)" class="btn btn-info pull-right" id="export-media-excel">导出数据</a>
	</div>
	<div class="table-responsive">
		<table class="table table-striped">
			<thead>
				<tr>
					<th>名字</th>
					<th>公司</th>
					<th>职位</th>
					<th>电话</th>
					<th>创建日期</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($result as $applicant) { ?>
					<tr>
						<td><?php echo $applicant->name ?></td>
						<td><?php echo $applicant->company ?></td>
						<td><?php echo $applicant->job ?></td>
						<td><?php echo $applicant->phone ?></td>
						<td><?php echo $applicant->time ?></td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
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