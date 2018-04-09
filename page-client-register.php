<?php get_header(); ?>
<link rel="stylesheet" href="<?php echo get_static_url('/css/content.css') ?>">
<section class="article-wrapper clearfix">
	<?php get_sidebar(); ?>
	<div class="article-zone">
		<div class="article-zone-bread">
			<a href="#">观众中心</a> >
			<a href="#" style="color: #0076ff;">观众注册</a>
		</div>
		<div class="article-zone-text">
			<div class="article-info">
				<span>发布时间：2018-01-01</span>&nbsp;&nbsp;&nbsp;&nbsp;
			</div>
			<h3>观众注册</h3>
			<form class="form-horizontal my-form">
				<h2 style="text-align: center; margin: 2.5rem 0;">首届中国长三角（上海）<span class="header-divider"></span>品牌博览会</h2>
				<div class="form-group">
					<label for="register-name" class="col-sm-2 control-label">姓名&nbsp;<span style="color: #0076ff; line-height: 1.2rem; font-weight: bold;">*</span></label>
					<div class="col-sm-6">
						<input name="name" type="text" class="form-control input-erik" id="name" placeholder="请输入姓名">
					</div>
				</div>
				<div class="form-group">
					<label for="register-name" class="col-sm-2 control-label">用户类型&nbsp;<span style="color: #0076ff; line-height: 1.2rem; font-weight: bold;">*</span></label>
					<div class="col-sm-6">
						<select name="type" type="text" class="form-control input-erik" id="type">
							<option value="audience">个人观众</option>
							<option value="media">媒体</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label for="register-name" class="col-sm-2 control-label">公司&nbsp;<span style="color: #0076ff; line-height: 1.2rem; font-weight: bold;">*</span></label>
					<div class="col-sm-6">
						<input name="company" type="text" class="form-control input-erik" id="company" placeholder="请输入公司">
					</div>
				</div>
				<div class="form-group">
					<label for="register-job" class="col-sm-2 control-label">职位&nbsp;<span style="color: #0076ff; line-height: 1.2rem; font-weight: bold;">*</span></label>
					<div class="col-sm-6">
						<input name="job" type="text" class="form-control input-erik" id="job" placeholder="请输入职位">
					</div>
				</div>
				<div class="form-group">
					<label for="register-email" class="col-sm-2 control-label">邮箱&nbsp;<span style="color: #0076ff; line-height: 1.2rem; font-weight: bold;">*</span></label>
					<div class="col-sm-3">
						<input name="email" id="email" type="email" class="form-control input-erik" placeholder="请输入邮箱">
					</div>
					<div class="col-sm-3">
						<button type="button" style="width: 100%;" id="getcode-btn" class="btn btn-primary">获取验证码</button>
					</div>
				</div>
				<div class="form-group">
					<label for="register-code" class="col-sm-2 control-label">验证码&nbsp;<span style="color: #0076ff; line-height: 1.2rem; font-weight: bold;">*</span></label>
					<div class="col-sm-6">
						<input name="email_code" id="email_code" type="text" class="form-control input-erik" placeholder="请输入获得的验证码">
					</div>
				</div>
				<div class="form-group">
					<label for="register-cellphone" class="col-sm-2 control-label">手机号&nbsp;<span style="color: #0076ff; line-height: 1.2rem; font-weight: bold;">*</span></label>
					<div class="col-sm-3">
						<input name="phone" type="text" class="form-control input-erik" id="phone" placeholder="请输入手机号">
					</div>
					<div class="col-sm-3">
						<button type="button" style="width: 100%;" id="get-phonecode-btn" class="btn btn-primary">获取验证码</button>
					</div>
				</div>
				<div class="form-group">
					<label for="register-code" class="col-sm-2 control-label">验证码&nbsp;<span style="color: #0076ff; line-height: 1.2rem; font-weight: bold;">*</span></label>
					<div class="col-sm-6">
						<input name="email_code" id="phone_code" type="text" class="form-control input-erik" placeholder="请输入获得的验证码">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">参展日期&nbsp;<span style="color: #0076ff; line-height: 1.2rem; font-weight: bold;">*</span></label>
					<?php $result_media = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'ticket where type = "media"', OBJECT ) ?>
					<?php $result_audience = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'ticket where type = "audience"', OBJECT ) ?>
					<div class="col-sm-10 ticket-media" style="display:none">
					
						<?php $loop_num = 1; ?>
						<?php foreach($result_media as $result) { ?>
							<?php if ($loop_num % 3 == 1) { echo '<div class="pc-show">'; } ?>									
								<button type="button" class="ticket-btn btn btn-choose" data-tickettype="media" data-ticketprice="<?php echo $result->price ?>" data-ticketdate="<?php echo $result->date ?>"><?php echo $result->date ?></button>
							<?php if ($loop_num % 3 === 0) { echo '</div>'; } ?>	
							<?php $loop_num++ ?>
						<?php } ?>
						<?php if (count($result_media) % 3 !== 0) { echo '</div>'; } ?>
						
						<?php $loop_num = 1; ?>
						<?php foreach($result_media as $result) { ?>
							<?php if ($loop_num % 2 == 1) { echo '<div class="mobile-show">'; } ?>									
								<button type="button" class="ticket-btn btn btn-choose" data-tickettype="media" data-ticketprice="<?php echo $result->price ?>" data-ticketdate="<?php echo $result->date ?>"><?php echo $result->date ?></button>
							<?php if ($loop_num % 2 === 0) { echo '</div>'; } ?>	
							<?php $loop_num++ ?>
						<?php } ?>
						<?php if (count($result_media) % 2 !== 0) { echo '</div>'; } ?>
						
					</div>
					<div class="col-sm-10 ticket-audience">
					
						<?php $loop_num = 1; ?>
						<?php foreach($result_audience as $result) { ?>
							<?php if ($loop_num % 3 == 1) { echo '<div class="pc-show">'; } ?>									
								<button type="button" class="ticket-btn btn btn-choose" data-tickettype="audience" data-ticketprice="<?php echo $result->price ?>" data-ticketdate="<?php echo $result->date ?>"><?php echo $result->date ?></button>
							<?php if ($loop_num % 3 === 0) { echo '</div>'; } ?>	
							<?php $loop_num++ ?>
						<?php } ?>
						<?php if (count($result_audience) % 3 !== 0) { echo '</div>'; } ?>
						
						<?php $loop_num = 1; ?>
						<?php foreach($result_audience as $result) { ?>
							<?php if ($loop_num % 2 == 1) { echo '<div class="mobile-show">'; } ?>									
								<button type="button" class="ticket-btn btn btn-choose" data-tickettype="audience" data-ticketprice="<?php echo $result->price ?>" data-ticketdate="<?php echo $result->date ?>"><?php echo $result->date ?></button>
							<?php if ($loop_num % 2 === 0) { echo '</div>'; } ?>	
							<?php $loop_num++ ?>
						<?php } ?>
						<?php if (count($result_audience) % 2 !== 0) { echo '</div>'; } ?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">在线购买&nbsp;<span style="color: #0076ff; line-height: 1.2rem; font-weight: bold;">*</span></label>
					<div class="col-sm-6">
						<div class="radio">
							<label>
								<input name="payment_type" id= "payment_type" type="radio" value="free" checked> 免费
							</label>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-10">
						<button type="button" class="btn btn-default" id="create_media_applicant">确认</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</section>
<script>
	jQuery('#type').change(function () {
		var value = jQuery(this).val()
		$(".ticket-btn").removeClass("btn-active")
		if (value === 'media') {
			jQuery(".ticket-media").show()
			jQuery(".ticket-audience").hide()
		} else if (value === 'audience') {
			jQuery(".ticket-media").hide()
			jQuery(".ticket-audience").show()
		}
	})
</script>
<?php get_footer(); ?>