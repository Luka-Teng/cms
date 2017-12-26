<?php get_header('admin'); ?>
<?php if (!is_user_logged_in()) : ?>
  <div class="form">
    <div class="form-item">
      <p class="form-item-title">Username</p>
      <input class="form-control" name="username" id="login-username">
    </div>
	<div class="form-divider"></div>
    <div class="form-item">
      <p class="form-item-title">Password</p>
      <input class="form-control" name="password" id="login-password" type="password">
    </div>
	<div class="form-divider"></div>
  </div>
  <div class="clearfix">
    <a href="javascript:void(0)" id="login-btn" class="btn btn-primary pull-right top-gap-1 right-gap-1">LOGIN</a>
  </div>
<?php else : ?>
  <script>
    window.location = '/'
  </script>
<?php endif ?>
<?php get_footer('admin'); ?>