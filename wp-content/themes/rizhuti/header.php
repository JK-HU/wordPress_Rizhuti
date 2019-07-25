<!DOCTYPE HTML>
<html>
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta http-equiv="cache-control" content="no-siteapp">
<link rel="shortcut icon" href="<?php echo _hui_img( 'web_favicon' )?>">
<title><?php echo _title() ?></title>

<?php wp_head(); ?>
<!--[if lt IE 9]><script src="<?php echo get_stylesheet_directory_uri() ?>/js/html5.js"></script><![endif]-->
</head>
<body <?php body_class(_bodyclass())  ?>>

<script>
	(function() {
		//控制侧边栏的二维码
		$('body').on('mouseover','#csbwfs-li-a',function(event) {
			$('.pos_img').attr('style','display:block !important');
			return false;
			
		});
		$('body').on('mouseout','#csbwfs-li-a',function(event) {
			$('.pos_img').attr('style','display:none !important');
			event.stopPropagation();
			return false;
		});

	})();

</script>

<header class="header">
	<div class="container_header">
		<h1 class="logo">
			<a href="<?php echo esc_url(home_url('/')); ?>" rel="home" style="position: relative;">
				<img src="<?php echo _hui_img('header_logo'); ?>">
				<span style="display: block;font-size: 14px;color: #fff;position: absolute;bottom: 0">视频制作大叔资源网</span>
			</a>
		</h1>
		
		<?php if( is_user_logged_in() ){
				global $current_user;
			?>	
				<div class="wel">
					<?php if (vip_type() == 0) { ?>
						<div class="wel-item"><a href="<?php echo home_url('/user?action=vip') ?>"><i class="iconfont">&#xe63f;</i> 开通VIP</a></div>
					<?php }else{ ?>
						<div class="wel-item" ><a href="<?php echo home_url('/user?action=vip') ?>" style=" color: #fadb30; "><i class="iconfont">&#xe63f;</i> <?php echo vip_type_name() ?></a></div>
					<?php } ?> 
					<div class="wel-item" style="width: 200px;height:36px;border:1px solid #999;position:relative;top:-24px;border-radius:4px">
						<a href="javascript:;" id="search" style="height:43px">
							
							<form class="search-form search-form--horizontal" method="get" action="<?php echo esc_url( home_url( '/' ) ) ?>">
								<div class="search-form__input-wrap">
									<input style="height:30px;position:absolute;top:0;left:0;background:none;border:none;" type="text" name="s" class="search-form__input" placeholder="输入关键词进行搜索..." value="">
								</div>
								<div class="search-form__submit-wrap" style="display:block;position:absolute;top:0;right:0">
									<button type="submit" style="padding:0;position:absolute;top:8px;right:8px;" class="search-form__submit btn btn-primary"><i class="iconfont" style="position:absolute;top:0px;right:11px">&#xe67a;</i></button>
								</div>
							</form>
						</a>
					</div>
					<div class="wel-item has-sub-menu">
						<a href="<?php echo home_url('/user') ?>">
							<?php echo _get_user_avatar( $current_user->user_email, true, 50); ?><span class="wel-name"><?php echo $current_user->display_name ?></span>
						</a>
						<div class="sub-menu">
							<ul>
								
								<?php if( $current_user->roles[0] == 'administrator'|| $current_user->roles[0] == 'editor') { ?>
								<li><a target="_blank" href="<?php echo home_url('/wp-admin/index.php') ?>">后台管理</a></li>
					          	<?php } ?>
								<li><a href="<?php echo home_url('/user?action=order') ?>">我的订单</a></li>
								<li><a href="<?php echo home_url('/user?action=vip') ?>">会员特权</a></li>
								<li><a href="<?php echo home_url('/user?action=info') ?>">修改资料</a></li>
								<li><a href="<?php echo wp_logout_url(home_url()); ?>">退出</a></li>
							</ul>
						</div>
					</div>
				</div>
				<div class="m-wel">
					<header>
						<?php echo _get_user_avatar( $current_user->user_email, true, 50); ?><h4><?php echo $current_user->display_name ?></h4>
						<h5><?php echo $current_user->user_email ?></h5>
					</header>
					<div class="m-wel-content">
						<ul>
							<li><a href="<?php echo home_url('/user?action=order') ?>">我的订单</a></li>
							<li><a href="<?php echo home_url('/user?action=vip') ?>">会员特权</a></li>
							<li><a href="<?php echo home_url('/user?action=info') ?>">修改资料</a></li>
						</ul>
					</div>
					<footer>
						<a href="<?php echo wp_logout_url(home_url()); ?>">退出当前账户</a>
					</footer>
				</div>
				<!-- <div class="signuser-welcome">
					<a class="signuser-info" href="<?php echo home_url('/user') ?>"><?php echo _get_user_avatar( $current_user->user_email, true, 50); ?><strong><?php echo $current_user->display_name ?></strong></a>
					<a class="signuser-logout" href="<?php echo wp_logout_url(home_url()); ?>">退出</a>
				</div> -->
		<?php }else{ ?>
			<div class="wel">
					<div class="wel-item">
						<a href="<?php echo home_url('/user?action=vip') ?>"><i class="iconfont">&#xe63f;</i> 开通VIP</a>
					</div>
					
				<div class="wel-item"><a href="<?php echo home_url('login') ?>" etap="login_btn">登录</a></div>
				<div class="wel-item wel-item-btn"><a href="<?php echo home_url('/login?action=register') ?>" etap="register_btn">注册新用户</a></div>
				<div class="wel-item"><a href="javascript:;" id="search"><i class="iconfont">&#xe67a;</i></a></div>
			</div>

			<div class="m-wel">
				<div class="m-wel-login">
					<img class="avatar" src="<?php echo get_stylesheet_directory_uri() . '/img/avatar.png';?>">
					<a class="m-wel-login" href="<?php echo home_url('login') ?>">登录</a>
					<a class="m-wel-register" href="<?php echo home_url('/login?action=register') ?>">新用户注册</a>
				</div>
			</div>
		<?php } ?>

		<div class="site-navbar">
			<?php _the_menu('nav'); ?>
		</div>

		<div class="m-navbar-start"><i class="iconfont" style="margin-right:6px;font-size:18px">&#xe648;</i>更多分类</div>
		<div class="m-wel-start"><i class="iconfont">&#xe66b;</i></div>
		<div class="m-mask"></div>
	</div>
	<div id="header-search-dropdown" style="display:none" class="header-search-dropdown ajax-search is-in-navbar js-ajax-search">
		<div class="container container--narrow">
			<form class="search-form search-form--horizontal" method="get" action="<?php echo esc_url( home_url( '/' ) ) ?>">
				<div class="search-form__input-wrap">
					<input type="text" name="s" class="search-form__input" placeholder="输入关键词进行搜索..." value="">
				</div>
				<div class="search-form__submit-wrap">
					<button type="submit" class="search-form__submit btn btn-primary">搜索一下</button>
				</div>
			</form>
			<div class="search-results">
				<div class="typing-loader"></div>
				<div class="search-results__inner"></div>
			</div>
		</div>
	</div>
</header>

<!-- 加载页面动画效果 -->
<script type="text/javascript">
	$(document).ready(function() { NProgress.start(); $(window).load(function() { NProgress.done(); }); });
	(function() {
		$(document).ready(function() {
			$(document).on('click','.vipImg',function() {
				window.location.href='https://weidian.com/item.html?itemID=2582634581&spider_token=10e9';
			})
		})
	})();
</script>

