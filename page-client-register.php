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
				<h2 style="text-align: center; margin: 2.5rem 0;">上海城市经济协调会</h2>
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
					<label for="register-cellphone" class="col-sm-2 control-label">手机号&nbsp;</label>
					<div class="col-sm-6">
						<input name="phone" type="text" class="form-control input-erik" id="phone" placeholder="请输入手机号">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">参展日期&nbsp;<span style="color: #0076ff; line-height: 1.2rem; font-weight: bold;">*</span></label>
					<div class="col-sm-10">
						<div class="pc-show">
							<button type="button" class="btn btn-choose" data-date="2017/08/17">2017/08/17 周三</button>
							<button type="button" class="btn btn-choose" data-date="2017/08/18">2017/08/18 周四</button>
							<button type="button" class="btn btn-choose" data-date="2017/08/19">2017/08/19 周五</button>
						</div>
						<div class="pc-show">
							<button type="button" class="btn btn-choose" data-date="2017/08/20">2017/08/20 周六</button>
							<button type="button" class="btn btn-choose" data-date="2017/08/21">2017/08/21 周日</button>
							<button type="button" class="btn btn-choose" data-date="2017/08/22">2017/08/22 周一</button>
						</div>
						<div class="mobile-show">
							<button type="button" class="btn btn-choose" data-date="2017/08/17">2017/08/17 周三</button>
							<button type="button" class="btn btn-choose" data-date="2017/08/18">2017/08/18 周四</button>
						</div>
						<div class="mobile-show">
							<button type="button" class="btn btn-choose" data-date="2017/08/19">2017/08/19 周五</button>
							<button type="button" class="btn btn-choose" data-date="2017/08/20">2017/08/20 周六</button>
						</div>
						<div class="mobile-show">
							<button type="button" class="btn btn-choose" data-date="2017/08/21">2017/08/21 周日</button>
							<button type="button" class="btn btn-choose" data-date="2017/08/22">2017/08/22 周一</button>
						</div>
						<div>
							<span style="color: #0076ff;">注：2017/08/19为媒体日不对公众开放敬请谅解，电子票将通过邮箱发送，请注意查收。</span>
						</div>
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
<?php get_footer(); ?>