<?php get_header('admin'); ?>
<?php if (current_user_can( 'editor' )) : ?>
	<div class="clearfix">
		<div class="title top-gap-1 bot-gap-1" style="margin-left:15px">扫码确认</div>
		<div class="form-group col-5 pull-left general-padding">
			<input id="today" type="date" name="today" class="form-control">
		</div>
		<div class="form-group col-12 pull-left general-padding">
			<input id="scanning" type="text" name="scaning" class="form-control">
		</div>
		
		<div class="title top-gap-1 bot-gap-1" style="margin-left:15px">人工确认</div>
		<?php global $wpdb ?>
		<?php $check_phone = $_GET['check_phone'] ? $_GET['check_phone'] : ''?>
		<?php $unchecked_applicant = $wpdb -> get_results( 'SELECT * FROM ' . APPLICANT_TABLE . " WHERE phone='{$check_phone}' AND checked='unchecked'", OBJECT );?>
		<form method="GET" action="">
			<div class="form-group col-4 pull-left general-padding">
				<input id="check_phone" type="text" value="<?php echo $check_phone ?>" name="check_phone" class="form-control">
			</div>
			<div class="form-group col-4 pull-left general-padding">
				<button class="btn btn-info">查询</button>
			</div>
		</form>
		<div class="title top-gap-1 bot-gap-1" style="margin-left:15px">现场购票</div>
		<div class="form-group col-4 pull-left general-padding">
			<label>电话 : </label>
			<input id="phone" type="phone" name="phone" class="form-control">
		</div>
		<div class="form-group col-4 pull-left general-padding">
			<label>姓名 : </label>
			<input id="name" type="name" name="name" class="form-control">
		</div>
		<div class="form-group col-4 pull-left general-padding">
			<label>类型 : </label>
			<select name="type" type="text" class="form-control" id="type">
				<option value="audience">个人观众</option>
				<option value="media">媒体</option>
			</select>
		</div>
		<?php $result_media = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'ticket where type = "media"', OBJECT ) ?>
		<?php $result_audience = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'ticket where type = "audience"', OBJECT ) ?>
		<div class="form-group col-4 pull-left general-padding" style="display: none" id="media_show">
			<label>媒体票 : </label>
			<select name="type" type="text" class="form-control" id="media_type">
				<?php foreach($result_media as $result) { ?>
					<option value="media-<?php echo $result->date ?>"><?php echo $result->date ?></option>
				<?php } ?>
			</select>
		</div>
		<div class="form-group col-4 pull-left general-padding" id="audience_show">
			<label>个人观众票 : </label>
			<select name="type" type="text" class="form-control" id="audience_type">
				<?php foreach($result_audience as $result) { ?>
					<option value="audience-<?php echo $result->date ?>"><?php echo $result->date ?></option>
				<?php } ?>
			</select>
		</div>
		<div class="form-group col-4 pull-left general-padding" style="margin-top: 25px">
			<a id="quick-ticket" class="btn btn-info" href="javascript:void(0)">提交</a>
		</div>
	</div>
	<script>
		jQuery('#type').change(function () {
			var value = jQuery(this).val()
			if (value === 'media') {
				jQuery("#media_show").show()
				jQuery("#audience_show").hide()
			} else if (value === 'audience') {
				jQuery("#media_show").hide()
				jQuery("#audience_show").show()
			}
		})
	</script>
<?php else : ?>
	<script>
		window.location = '/login'
	</script>
<?php endif ?>
<?php get_footer('admin'); ?>