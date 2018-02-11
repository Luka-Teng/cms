<?php get_header('admin'); ?>
<?php if (current_user_can( 'administrator' )) : ?>
	<?php $paged = get_query_var('paged') ? get_query_var('paged') : 1; ?>
	<?php $data_per_page = 10 ?>
	<?php $result = get_paginated_data('ticket', $paged, $data_per_page); ?>
	<div class="clearfix">
		<div class="title top-gap-1 bot-gap-1" style="margin-left:15px">新增票务</div>
		<div class="form-group col-4 pull-left general-padding">
			<label>日期 : </label>
			<input id="date" type="date" name="date" class="form-control">
		</div>
		<div class="form-group col-4 pull-left general-padding">
			<label>类型 : </label>
			<select name="type" id="type" class="form-control">
				<option value="media">媒体</option>
				<option value="audience">观众</option>
			</select>
		</div>
		<div class="form-group col-4 pull-left general-padding">
			<label>价格 : </label>
			<input type="number" name="price" id="price" class="form-control" value="0">
		</div>
		<div class="col-12 general-padding">
			<a id="new-ticket" class="btn btn-info" href="javascript:void(0)">提交</a>
		</div>
	</div>
	<div class="form-divider"></div>
	<div class="clearfix">
		<div class="title top-gap-1 bot-gap-1" style="margin-left:15px">票务列表</div>
		<div class="table-responsive general-padding">
			<table class="table table-striped">
				<thead>
					<tr>
						<th>类型</th>
						<th>日期</th>
						<th>价格</th>
						<th>操作</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($result as $ticket) { ?>
						<tr>
							<td><?php echo $ticket->type ?></td>
							<td><?php echo $ticket->date?></td>
							<td><?php echo $ticket->price ?></td>
							<td><a href="javascript:void(0)" data-ticket="<?php echo $ticket->type ?>_<?php echo $ticket->date ?>" class="del-ticket">删除</a></td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
		<div class="posts-nav left-gap-1">
			<?php 
				echo paginate_links(array(
				  'total' => get_paginated_length('ticket', $data_per_page),
				  'prev_next' => 0,
				  'mid_size' => 2
				));
			?>
		</div>
	</div>
<?php else : ?>
	<script>
		window.location = '/login'
	</script>
<?php endif ?>
<?php get_footer('admin'); ?>