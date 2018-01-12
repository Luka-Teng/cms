<?php get_header(); ?>
<?php
	strtotime(date("2018-1-10 16:16:11")) - strtotime(date("2018-1-10 16:15:11"));
	$result1 = $wpdb->get_row( "SELECT * FROM " . EMAIL_VERIFICATION_TABLE . " WHERE email = '359593891@qq.com' ", OBJECT );
	echo json_encode($result1 -> is_applicant);
	echo generateBarcode("ma2018011213124318385314929");
?>
<div style="padding:30px;marign:10px auto">
	<div class="form-group">
		邮箱<input name="email" id="email" class="form-control">
	</div>
	
	<a href="javascript:void(0)" id="getcode-btn">获取验证码</a>
	
	<div class="form-group">
		验证码<input name="email_code" id="email_code" class="form-control">
	</div>
	<div class="form-group">
		名字<input name="name" id="name" class="form-control">
	</div>
	<div class="form-group">
		公司<input name="company" id="company" class="form-control">
	</div>
	<div class="form-group">
		职业<input name="job" id="job" class="form-control">
	</div>
	<div class="form-group">
		电话<input name="phone" id="phone" class="form-control">
	</div>
	<button id="create_media_applicant">submit</button>
</div>
<?php get_footer(); ?>