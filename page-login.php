<?php get_header(); ?>
<?php if (!is_user_logged_in()) : ?>
  <div class="form">
    <div class="form-item">
      <p class="form-item-title">Username</p>
      <input class="form-item-input block" name="username" id="login-username">
    </div>
    <div class="form-item">
      <p class="form-item-title">Password</p>
      <input class="form-item-input block" name="password" id="login-password">
    </div>
  </div>
  <div class="clearfix" style="margin-right:10%">
    <a href="javascript:void(0)" id="login-btn" class="btn btn-default bot-gap-1 pull-right">LOGIN</a>
  </div>
<?php else : ?>
  <script>
    window.location = '/'
  </script>
<?php endif ?>
<?php get_footer(); ?>