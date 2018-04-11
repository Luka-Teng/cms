<?php get_header('admin'); ?>
<?php if (current_user_can( 'editor' )) : ?>
	<div class="clearfix">
		<div class="title top-gap-1 bot-gap-1" style="margin-left:15px">扫码确认</div>
		<div class="form-group col-5 pull-left general-padding">
			<input id="today" type="date" name="today" class="form-control" value="<?php echo $_GET['check_date'] ? $_GET['check_date'] : date("Y-m-d")?>">
		</div>
		<div class="form-group col-12 pull-left general-padding">
			<input id="scanning" type="text" name="scaning" class="form-control">
		</div>
		<div class="clearfix"></div>
		<div class="normal-divider" style="margin:20px; margin-bottom:40px"></div>
		<div class="title top-gap-1 bot-gap-1" style="margin-left:15px">人工确认(电话号码)</div>
		<?php global $wpdb ?>
		<?php 
			$check_phone = $_GET['check_phone'] ? $_GET['check_phone'] : '';
			$check_date = $_GET['check_date'] ? $_GET['check_date'] : '';
			$today_media_ticket = "media-{$check_date}";
			$today_audience_ticket = "audience-{$check_date}";
			$filtered_applicants = $wpdb -> get_results( 'SELECT * FROM ' . APPLICANT_TABLE . " WHERE phone='{$check_phone}'", OBJECT );
			function getUnchecked ($applicant) {
				global $today_audience_ticket;
				global $today_media_ticket;
				$all_tickets = json_decode($applicant->tickets);
				$checked_tickets = json_decode($applicant->checked);
				if ((in_array($today_media_ticket, $all_tickets) && !in_array($today_media_ticket, $checked_tickets)) || (in_array($today_audience_ticket, $all_tickets) && !in_array($today_audience_ticket, $checked_tickets))) {
					return true;
				}
				return false;
			}
			$unchecked_applicants = array_filter($filtered_applicants, 'getUnchecked');
		?>
		<form method="GET" action="">
			<input id="check_date" type="text" value="<?php echo $check_date ?>" name="check_date" hidden ">
			<div class="form-group col-4 pull-left general-padding">
				<input id="check_phone" type="text" value="<?php echo $check_phone ?>" name="check_phone" class="form-control">
			</div>
			<div class="form-group col-4 pull-left general-padding">
				<button class="btn btn-info">查询</button>
			</div>
		</form>
		<div class="table-responsive col-6" style="margin: 15px;padding: 10px;box-shadow: 0 0 3px 0 rgba(0,0,0,0.5);">
			<table class="table table-striped">
				<thead>
					<tr>
						<th>uid</th>
						<th>类型</th>
						<th>名字</th>
						<th>电话</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($unchecked_applicants as $applicant) { ?>
						<tr>
							<td style="vertical-align: middle"><?php echo $applicant->uid ?></td>
							<td style="vertical-align: middle"><?php echo $applicant->type === 'media' ? '媒体' : '个人观众' ?></td>
							<td style="vertical-align: middle"><?php echo $applicant->name ?></td>
							<td style="vertical-align: middle"><?php echo $applicant->phone ?></td>
							<td style="vertical-align: middle"><a class="btn btn-warning btn-sm" href="javascript:void(0)" onclick="checkinRe(['<?php echo $applicant->uid ?>'], '<?php echo $_GET['check_date'] ?>')">确认入场</a></td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
		<div class="clearfix"></div>
		<div class="normal-divider" style="margin:20px; margin-bottom:40px"></div>
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
			<input name="type" type="text" class="form-control" id="media_type" disabled>
		</div>
		<div class="form-group col-4 pull-left general-padding" id="audience_show">
			<label>个人观众票 : </label>
			<input name="type" type="text" class="form-control" id="audience_type" disabled>
		</div>
		<div class="form-group col-4 pull-left general-padding" style="margin-top: 25px">
			<a id="quick-ticket" class="btn btn-info" href="javascript:void(0)">提交</a>
		</div>
	</div>
	<script>
		var media_tickets = JSON.parse('<?php echo json_encode($result_media) ?>')
		var audience_tickets = JSON.parse('<?php echo json_encode($result_audience) ?>')
		function setDate() {
			jQuery("#check_date").val(jQuery('#today').val())
			jQuery('#media_type').val('')
			jQuery('#audience_type').val('')
			var media_today = 'media-' + jQuery('#today').val()
			var audience_today = 'audience-' + jQuery('#today').val()
			for (var index in media_tickets) {
				if (media_tickets[index].uid === media_today) {
					jQuery('#media_type').val(media_today)
					break
				}
			}
			for (var index in audience_tickets) {
				if (audience_tickets[index].uid === audience_today) {
					jQuery('#audience_type').val(audience_today)
					break
				}
			}
		}
		setDate()
		jQuery("#today").change(function () {
			setDate()
		})
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
		//确认进场
		function checkinRe(uids, date) {
			var url = magicalData.siteURL + '/wp-json/apis/check_in'
			$.ajax({
				type: 'post',
				url: url,
				data: {
					uids: JSON.stringify(uids),
					date: date
				},
				beforeSend: function (xhr) {
					xhr.setRequestHeader('X-WP-Nonce', magicalData.nonce)
				},
				success: function (data) {
					location.reload()		
				},
				error: function (data) {
					console.log(data)
				}
			})
		}
	</script>
<?php else : ?>
	<script>
		window.location = '/login'
	</script>
<?php endif ?>
<?php get_footer('admin'); ?>