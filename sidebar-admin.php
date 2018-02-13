<div class="sidebar">
	<div class="side-block">
		<a class="sidebar-block-title" data-toggle="collapse" href="#side-bar-0">数据分析模块</a>
		<div class="collapse in sidebar-block-children" id="side-bar-0">
			<a href="/admin-analysis-applicant">申请人数据分析</a>
		</div>
	</div>
	<div class="side-block">
		<a class="sidebar-block-title" data-toggle="collapse" href="#side-bar-1">内容分发模块</a>
		<div class="collapse in sidebar-block-children" id="side-bar-1">
			<?php $categories = get_all_categories(); ?>
			<?php foreach($categories as $cat) { ?>
				<a href="/admin-post-show?cat_id=<?php echo $cat->term_id ?>"><?php echo $cat->name ?></a>
			<?php } ?>
			<?php $categories = get_show_news(); ?>
			<?php foreach($categories as $cat) { ?>
				<a href="/admin-post-show?cat_id=<?php echo $cat->term_id ?>"><?php echo $cat->name ?></a>
			<?php } ?>
			<?php $categories = get_show_highlights(); ?>
			<?php foreach($categories as $cat) { ?>
				<a href="/admin-post-show?cat_id=<?php echo $cat->term_id ?>"><?php echo $cat->name ?></a>
			<?php } ?>
			<a href="/admin-post-index">城市之窗</a>
		</div>
	</div>
	<div class="side-block">
		<a class="sidebar-block-title" data-toggle="collapse" href="#side-bar-2">首页模块</a>
		<div class="collapse in sidebar-block-children" id="side-bar-2">
			<a href="/admin-carousel">轮播图模块</a>
		</div>
		<div class="collapse in sidebar-block-children" id="side-bar-2">
			<a href="/admin-home">首页其他</a>
		</div>
	</div>
	<div class="side-block">
		<a class="sidebar-block-title" data-toggle="collapse" href="#side-bar-3">其他信息模块</a>
		<div class="collapse in sidebar-block-children" id="side-bar-3">
			<a href="/admin-applicant">申请人信息</a>
		</div>
		<div class="collapse in sidebar-block-children" id="side-bar-3">
			<a href="/admin-ticket">票务信息</a>
		</div>
	</div>				
</div>