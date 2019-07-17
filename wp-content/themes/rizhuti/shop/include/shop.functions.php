<?php

/**
 * [shop_menu 添加设置菜单项]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T13:15:38+0800
 * @return   [type]                   [description]
 */
function shop_menu() {
	add_menu_page('SHOP', '商城', 'activate_plugins', 'shop_orders_page', 'shop_orders_page','dashicons-shield');
	add_submenu_page('shop_orders_page', '订单', '订单', 'activate_plugins', 'shop_orders_page','shop_orders_page');
	add_submenu_page('shop_orders_page', '会员', '会员', 'activate_plugins', 'shop_vip_page','shop_vip_page');
}
add_action('admin_menu', 'shop_menu');

/**
 * [shop_orders_page 插件订单页面]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T13:15:11+0800
 * @return   [type]                   [description]
 */
function shop_orders_page(){
    @include SHOP_PATH.'/admin/orders.php';
}

/**
 * [shop_vip_page 插件会员页面]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T13:15:16+0800
 * @return   [type]                   [description]
 */
function shop_vip_page(){
    @include SHOP_PATH.'/admin/vip.php';
}



/**
 * [vip_type 当前会员类型]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T12:23:57+0800
 * @param    string                   $users_id [description]
 * @return   [type]                             [0 31 365 3600]
 */
function vip_type($users_id = '')
{
    global $current_user;
    $uid       = (!$users_id) ? $current_user->ID : $users_id;
    $vip_type  = get_user_meta($uid, 'vip_type', true);
    $vip_time  = get_user_meta($uid, 'vip_time', true);
    $timestamp = intval($vip_time) - time();
    if ($timestamp > 0) {
        return intval($vip_type);
    } else {
        return 0;
    }

}

/**
 * [vip_type_name 当前会员名称]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T12:24:57+0800
 * @param    string                   $users_id [description]
 * @return   [type]                             [description]
 */
function vip_type_name($users_id = '')
{
    global $current_user;
    $uid      = (!$users_id) ? $current_user->ID : $users_id;
    $vip_type = get_user_meta($uid, 'vip_type', true);
    if (!$vip_type) {
        return '普通用户';
    }
    $vip_time  = get_user_meta($uid, 'vip_time', true);
    $timestamp = intval($vip_time) - time();
    if ($timestamp > 0) {
        if (intval($vip_type) == 31) {
            $name = '月费会员';
        } elseif (intval($vip_type) == 365) {
            $name = '年费会员';
        } elseif (intval($vip_type) == 3600) {
            $name = '终身会员';
        }
    } else {
        $name = '普通用户';
    }
    return $name;
}


/**
 * [vip_time 当前会员到期时间]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T12:25:12+0800
 * @param    string                   $users_id [description]
 * @return   [type]                             [时间戳]
 */
function vip_time($users_id = '')
{
    global $current_user;
    $uid      = (!$users_id) ? $current_user->ID : $users_id;
    $vip_time = get_user_meta($uid, 'vip_time', true);
    if ($vip_time > time()) {
        return date('Y-m-d H:i:s', intval($vip_time));
    } else {
        return date('Y-m-d H:i:s', time());
    }

}

/**
 * [getTime 获取今天的开始和结束时间]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T12:25:26+0800
 * @return   [type]                   [description]
 */
function getTime()
{
    $str          = date("Y-m-d", time()) . "0:0:0";
    $data["star"] = strtotime($str);
    $str          = date("Y-m-d", time()) . "24:00:00";
    $data["end"]  = strtotime($str);
    return $data;
}

/**
 * [this_vip_downum 当前会员下载次数限制]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T12:25:34+0800
 * @param    string                   $users_id [description]
 * @return   [type]                             [description]
 */
function this_vip_downum($users_id = '')
{
    global $current_user;
    if (!is_user_logged_in()) {
        return 0;
    }
    $uid = (!$users_id) ? $current_user->ID : $users_id;
    // 会员当前下载结束时间
    $this_vip_downend_time = (get_user_meta($uid, 'this_vip_downend_time', true) > 0) ? get_user_meta($uid, 'this_vip_downend_time', true) : 0;
    // 会员当前下载次数
    $this_vip_downum = (get_user_meta($uid, 'this_vip_downum', true) > 0) ? get_user_meta($uid, 'this_vip_downum', true) : 0;
    // 自动更新下载时间
    $getTime  = getTime();
    $thenTime = time();
    // 获取用户结束时间
    
    // 当用时间为0 时候 初始化时间为今天开始时间 OR 当前时间大于结束时间 刷新新时间
    if ($this_vip_downend_time = 0 || intval($thenTime) > intval($this_vip_downend_time)) {
        update_user_meta($uid, 'this_vip_downend_time', $getTime['end']); //更新用户本次到期时间
        update_user_meta($uid, 'this_vip_downum', 0); //更新用户本次到期时间
    }

    $this_vip_type = vip_type($uid);
    $vip_options = _hui('vip_options');
    if (intval($this_vip_type) == 31) {
        $over_down_num = intval($vip_options['vip_price_31_downum']) - intval($this_vip_downum);
    } elseif (intval($this_vip_type) == 365) {
        $over_down_num = intval($vip_options['vip_price_365_downum']) - intval($this_vip_downum);
    } elseif (intval($this_vip_type) == 3600) {
        $over_down_num = intval($vip_options['vip_price_3600_downum']) - intval($this_vip_downum);
    } else {
        $over_down_num = 0;
    }

    $is_down = ($over_down_num > 0) ? true : false;

    $data = array(
        'is_down'           => $is_down, //是否可以下载
        'today_down_num'    => $this_vip_downum, //当前已下载次数
        'over_down_num'     => $over_down_num, //剩余下载次数
        'over_down_endtime' => $getTime['end'], // 下次下载次数更新时间
    );

    return $data;
    // var_dump(this_vip_downum());
}


/**
 * [up_user_vipinfo 更新会员数据]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T13:15:50+0800
 * @param    [type]                   $user_id    [description]
 * @param    [type]                   $order_type [description]
 * @return   [type]                               [description]
 */
function up_user_vipinfo($user_id,$order_type){
    $this_vip_type=vip_type($user_id); //当前会员类型 0 31 365 3600
    $this_vip_time=get_user_meta($user_id,'vip_time',true); //当前时间
    $time_stampc = intval($this_vip_time)-time();// 到期时间减去当前时间
    
    if ($order_type==2) {
        # 月费...
        $days= 31;
    }else if ($order_type==3) {
        # 年费...
        $days= 365;
    }else if ($order_type==4) {
        # 终身...
        $days= 3600;
    }else{
        $days= 0;
    }
  	if ($time_stampc > 0) {
        $nwetimes= intval($this_vip_time);
    }else{
        $nwetimes= time();
    }
    // 写入usermeta
    $new_vip_type = ($this_vip_type<$days) ? $days : $this_vip_type ;
    update_user_meta( $user_id, 'vip_type', $new_vip_type ); //更新等级 
    update_user_meta( $user_id, 'vip_time', $nwetimes+$days*24*3600 );   //更新到期时间
}



/**
 * [getQrcode 生产二维码]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T13:17:10+0800
 * @param    [type]                   $url [description]
 * @return   [type]                        [description]
 */
function getQrcode($url){
ob_start();
$errorCorrectionLevel = 'L';//容错级别 
$matrixPointSize = 6;//生成图片大小 
QRcode::png($url, false , $errorCorrectionLevel, $matrixPointSize, 2);
$data =ob_get_contents();
ob_end_clean();
$imageString = base64_encode($data);
header("content-type:application/json; charset=utf-8");
return 'data:image/jpeg;base64,'.$imageString;
// return "data:image/jpeg;base64,".base64_encode($data);
}


/**
 * [shop_scripts 商城静态资源初始化]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T13:07:52+0800
 * @return   [type]                   [description]
 */
function shop_scripts(){
	wp_enqueue_style( 'shop', shop_css_url('pay'), array(), SHOP_VERSION );
	wp_enqueue_script('jquery');
	
	if (_hui('weixinpay') || _hui('alpay')) {
		$this_js = 'qy-pay'; 
	}elseif (_hui('is_mianqian_skb') && !_hui('is_mianqian_mzf')) {
		$this_js = 'skb-pay'; 
	}elseif (_hui('is_mianqian_mzf') && !_hui('is_mianqian_skb')) {
		$this_js = 'mzf-pay';
	}elseif(_hui('is_mianqian_xunhupay') && _hui('xunhupay_appid')){
		$this_js = 'xh-pay'; 
	}else{
		$this_js = 'qy-pay'; 
	}
	wp_enqueue_script( 'shop',  shop_js_url($this_js), false, '', true, SHOP_VERSION );
    wp_localize_script( 'shop', 'wppay_ajax_url', SHOP_ADMIN_URL . "admin-ajax.php");
}
add_action('wp_enqueue_scripts', 'shop_scripts', 20, 1);



/**
 * [myAdminScripts 加载后台静态资源 优化界面 自适应]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T12:19:13+0800
 * @return   [type]                   [description]
 */
function myAdminScripts() {   
    if ( isset($_GET['page']) && $_GET['page'] == "shop_orders_page" ) {  
    wp_enqueue_style('layui', get_stylesheet_directory_uri() . '/css/layui.css', array(), _the_theme_version(), 'all');
    wp_enqueue_style('layui-admin', get_stylesheet_directory_uri() . '/css/admin.css', array('layui'), _the_theme_version(), 'all');
    }
}
add_action( 'admin_enqueue_scripts', 'myAdminScripts' );



/**
 * [alipay_callback 获取支付支付宝二维码返回的ajax参数]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T13:08:11+0800
 * @return   [type]                   [description]
 */
function alipay_callback(){
	date_default_timezone_set('Asia/Shanghai');
	header('Content-type:application/json; Charset=utf-8');
	global $wpdb, $wppay_table_name;
	$post_id = $_POST['post_id'];
	$user_id = is_user_logged_in() ? wp_get_current_user()->ID : 0;
	$code='';$link='';$msg='';$num='';$status=400;
	$out_trade_no = date("ymdhis").mt_rand(100,999).mt_rand(100,999).mt_rand(100,999);
	$order_type = $_POST['order_type'];
	$vip_options = _hui('vip_options');
	if ($order_type == 1) {
		// 文章价格
		$price = get_post_meta($post_id,'wppay_price',true);
	}else if ($order_type == 2) {
		// 月会员价格
		$price = $vip_options['vip_price_31'];
	}else if ($order_type == 3) {
		// 年费会员价格
		$price = $vip_options['vip_price_365'];
	}else if ($order_type == 4) {
		// 终身会员价格
		$price = $vip_options['vip_price_3600'];
	}else{
		$price = 0;
	}
	///////////////////////////////////////////
	$appid = _hui('alipay_appid');  
	$notifyUrl = get_stylesheet_directory_uri() . '/shop/payment/alipay/notify.php';     //付款成功后的异步回调地址
	$outTradeNo = $out_trade_no;     //你自己的商品订单号，不能重复
	$payAmount = $price;          //付款金额，单位:元
	$orderName = get_bloginfo('name').'付费资源';    //订单标题
	$signType = 'RSA2';			//签名算法类型，支持RSA2和RSA，推荐使用RSA2
	$rsaPrivateKey= _hui('alipay_privatekey');	
	/*** 配置结束 ***/

	$aliPay = new AlipayService();
	$aliPay->setAppid($appid);
	$aliPay->setNotifyUrl($notifyUrl);
	$aliPay->setRsaPrivateKey($rsaPrivateKey);
	$aliPay->setTotalFee($payAmount);
	$aliPay->setOutTradeNo($outTradeNo);
	$aliPay->setOrderName($orderName);
	/////////////////////////////////////////////////

	if($appid){

		$shop = new SHOP($post_id, $user_id, $order_type);
		// 写入订单到本地数据库
		$pay_type = 1; //定义支付方式为支付宝
		if($shop->add($out_trade_no, $price ,$order_type,$pay_type)){
			
			$result = $aliPay->doPay();

			$result = $result['alipay_trade_precreate_response'];
			if($result['code'] && $result['code']=='10000'){
			    //生成二维码
			    $url = getQrcode($result['qr_code']);
			    $msg =	'二维码内容：'.$result['qr_code'];

			    $result_json = array(
					'status' => '200',
					'price' =>$payAmount,
					'qr' => $url,
					'num' => $outTradeNo,
					'msg' => $msg
				);


			}else{
				$result_json = array(
					'status' => '201',
					'price' =>$payAmount,
					'qr' => '',
					'num' => $outTradeNo,
					'msg' => $result['msg'].' : '.$result['sub_msg']
				);
			}
		}else{
			$result_json = array(
				'status' => '202',
				'price' =>$payAmount,
				'qr' => '',
				'num' => $outTradeNo,
				'msg' => '订单创建失败，请刷新重试！'
			);
		}
	}else{
		$result_json = array(
			'status' => '203',
			'price' =>$payAmount,
			'qr' => '',
			'num' => $outTradeNo,
			'msg' => '支付接口信息未正确配置！'
		);
	}

	echo json_encode($result_json);
	exit;
}
add_action( 'wp_ajax_alipay', 'alipay_callback');
add_action( 'wp_ajax_nopriv_alipay', 'alipay_callback');

/**
 * [weixinpay_callback 获取微信支付二维码返回的ajax参数]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T13:08:22+0800
 * @return   [type]                   [description]
 */
function weixinpay_callback(){
	date_default_timezone_set('Asia/Shanghai');
	header('Content-type:application/json; Charset=utf-8');
	global $wpdb, $wppay_table_name;

	$post_id = $_POST['post_id'];
	$user_id = is_user_logged_in() ? wp_get_current_user()->ID : 0;
	$code='';$link='';$msg='';$num='';$status=400;
	$out_trade_no = date("ymdhis").mt_rand(100,999).mt_rand(100,999).mt_rand(100,999);
	$order_type = $_POST['order_type'];
	$vip_options = _hui('vip_options');
	if ($order_type == 1) {
		// 文章价格
		$price = get_post_meta($post_id,'wppay_price',true);
	}else if ($order_type == 2) {
		// 月会员价格
		$price = $vip_options['vip_price_31'];
	}else if ($order_type == 3) {
		// 年费会员价格
		$price = $vip_options['vip_price_365'];
	}else if ($order_type == 4) {
		// 终身会员价格
		$price = $vip_options['vip_price_3600'];
	}else{
		$price = 0;
	}
	///////////////////////////////////////////
	$mchid = _hui('weixinpay_mchid');          //微信支付商户号 PartnerID 通过微信支付商户资料审核后邮件发送
	$appid = _hui('weixinpay_appid');  //公众号APPID 通过微信支付商户资料审核后邮件发送
	$apiKey = _hui('weixinpay_apikey');   //https://pay.weixin.qq.com 帐户设置-安全设置-API安全-API密钥-设置API密钥
	$wxPay = new WxpayService($mchid,$appid,$apiKey);
	$outTradeNo = date("ymdhis").mt_rand(100,999).mt_rand(100,999).mt_rand(100,999);     //你自己的商品订单号
	$payAmount = $price;        //付款金额，单位:元
	$orderName = get_bloginfo('name').'付费资源';    //订单标题 
	$notifyUrl = get_stylesheet_directory_uri() . '/shop/payment/weixin/notify.php';     //付款成功后的回调地址(不要有问号)
	$payTime = time();      //付款时间
	/*** 配置结束 ***/
	/////////////////////////////////////////////////

	if($mchid){

		$shop = new SHOP($post_id, $user_id, $order_type);
		// 写入订单到本地数据库
		$pay_type = 2; //定义支付方式为微信支付
		if($shop->add($out_trade_no, $price ,$order_type,$pay_type)){
			
			$result = $wxPay->createJsBizPackage($payAmount,$out_trade_no,$orderName,$notifyUrl,$payTime);  //发起微信支付

			if($result['code_url']){
			    //生成二维码
			    $url = getQrcode($result['code_url']); 
			    $msg =	'二维码内容：'.$result['qr_code'];

			    $result_json = array(
					'status' => '200',
					'price' =>$payAmount,
					'qr' => $url,
					'num' => $out_trade_no,
					'msg' => $msg
				);


			}else{
				$result_json = array(
					'status' => '201',
					'price' =>$payAmount,
					'qr' => '',
					'num' => $out_trade_no,
					'msg' => '二维码生成失败，请刷新重试！'
				);
			}
		}else{
			$result_json = array(
				'status' => '202',
				'price' =>$payAmount,
				'qr' => '',
				'num' => $out_trade_no,
				'msg' => '订单创建失败，请刷新重试！'
			);
		}
	}else{
		$result_json = array(
			'status' => '203',
			'price' =>$payAmount,
			'qr' => '',
			'num' => $out_trade_no,
			'msg' => '支付接口信息未正确配置！'
		);
	}

	echo json_encode($result_json);
	exit;
}
add_action( 'wp_ajax_weixinpay', 'weixinpay_callback');
add_action( 'wp_ajax_nopriv_weixinpay', 'weixinpay_callback');





// 收款宝免签约函数封装  zlkbcodepay
if (_hui('is_mianqian_skb')) {
	@include SHOP_PATH.'/payment/zlkbcodepay/zlkbcodepay.class.php';
}

/**
 * [skbalipay_callback 收款宝免签约函数封装 支付宝支付]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T13:08:49+0800
 * @return   [type]                   [description]
 */
function skbalipay_callback(){
	date_default_timezone_set('Asia/Shanghai');
	header('Content-type:application/json; Charset=utf-8');
	global $wpdb, $wppay_table_name;
	$pay_type = 3; //收款宝免签支付宝方式
	$post_id = $_POST['post_id'];
	$user_id = is_user_logged_in() ? wp_get_current_user()->ID : 0;
	$code='';$link='';$msg='';$num='';$status=400;
	$out_trade_no = date("ymdhis").mt_rand(100,999).mt_rand(100,999).mt_rand(100,999);
	$order_type = $_POST['order_type'];
	$vip_options = _hui('vip_options');
	if ($order_type == 1) {
		// 文章价格
		$price = get_post_meta($post_id,'wppay_price',true);
	}else if ($order_type == 2) {
		// 月会员价格
		$price = $vip_options['vip_price_31'];
	}else if ($order_type == 3) {
		// 年费会员价格
		$price = $vip_options['vip_price_365'];
	}else if ($order_type == 4) {
		// 终身会员价格
		$price = $vip_options['vip_price_3600'];
	}else{
		$price = 0;
	}
	///////////////////////////////////////////
	$paymethod = 2; //支付宝
	$zlkb_appid  = _hui('zlkb_appid'); //appid
	$zlkb_secret = _hui('zlkb_secret'); //secret
	//构造需要传递的参数
	$params = array(
	     "app_id"         => $zlkb_appid, //app_id
	     "app_secret"         => $zlkb_secret, //secret
	     "overtime"         => 220, //超时时间
	     "orderid"         => $out_trade_no, //订单号
	     "subject"         => get_bloginfo('name').'付费资源',
	     "money"         => $price, //金额,
	     "returnurl"         => get_stylesheet_directory_uri() . '/shop/payment/zlkbcodepay/notify.php',
	     "notifyurl"         => get_stylesheet_directory_uri() . '/shop/payment/zlkbcodepay/notify.php',
	);
	if($zlkb_appid && $zlkb_secret){
		$shop = new SHOP($post_id, $user_id, $order_type);
		if ($shop->add($out_trade_no, $price ,$order_type,$pay_type)) {
			$link = new zlkbcodepay_link();
			$goPay  = $link->get_paylink($params,$paymethod); 
			// var_dump($goPay);die;
			// 创建订单成功
			if ($goPay['code'] == 1) {
				$payDeta = $goPay['data']; 
				//生成二维码
			    $url = getQrcode($payDeta['qr_content']); 
			    $msg =	'二维码内容：'.$payDeta['qr_content'];

			    $result_json = array(
					'status' => '200',
					'price' =>$payDeta['money'],
					'qr' => $url,
					'num' => $payDeta['ordersn'],
					'msg' => $msg
				);
			}
		}else{
			$result_json = array(
				'status' => '202',
				'price' =>$price,
				'qr' => '',
				'num' => $out_trade_no,
				'msg' => '订单创建失败，请刷新重试！'
			);
		}
	}else{
		$result_json = array(
			'status' => '203',
			'price' =>$price,
			'qr' => '',
			'num' => $outTradeNo,
			'msg' => '免签约支付接口信息未正确配置！'
		);
	}
	
	echo json_encode($result_json);
	exit;
	
}
add_action( 'wp_ajax_skbalipay', 'skbalipay_callback');
add_action( 'wp_ajax_nopriv_skbalipay', 'skbalipay_callback');

/**
 * [skbweixinpay_callback 收款宝免签约函数封装 微信支付]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T13:09:13+0800
 * @return   [type]                   [description]
 */
function skbweixinpay_callback(){
	date_default_timezone_set('Asia/Shanghai');
	header('Content-type:application/json; Charset=utf-8');
	global $wpdb, $wppay_table_name;
	$pay_type = 4; //收款宝免签weixin方式
	$post_id = $_POST['post_id'];
	$user_id = is_user_logged_in() ? wp_get_current_user()->ID : 0;
	$code='';$link='';$msg='';$num='';$status=400;
	$out_trade_no = date("ymdhis").mt_rand(100,999).mt_rand(100,999).mt_rand(100,999);
	$order_type = $_POST['order_type'];
	$vip_options = _hui('vip_options');
	if ($order_type == 1) {
		// 文章价格
		$price = get_post_meta($post_id,'wppay_price',true);
	}else if ($order_type == 2) {
		// 月会员价格
		$price = $vip_options['vip_price_31'];
	}else if ($order_type == 3) {
		// 年费会员价格
		$price = $vip_options['vip_price_365'];
	}else if ($order_type == 4) {
		// 终身会员价格
		$price = $vip_options['vip_price_3600'];
	}else{
		$price = 0;
	}
	///////////////////////////////////////////
	$paymethod = 1; //微信
	$zlkb_appid  = _hui('zlkb_appid'); //appid
	$zlkb_secret = _hui('zlkb_secret'); //secret
	//构造需要传递的参数
	$params = array(
	     "app_id"         => $zlkb_appid, //app_id
	     "app_secret"         => $zlkb_secret, //secret
	     "overtime"         => 220, //超时时间
	     "orderid"         => $out_trade_no, //订单号
	     "subject"         => get_bloginfo('name').'付费资源',
	     "money"         => $price, //金额,
	     "returnurl"         => get_stylesheet_directory_uri() . '/shop/payment/zlkbcodepay/notify.php',
	     "notifyurl"         => get_stylesheet_directory_uri() . '/shop/payment/zlkbcodepay/notify.php',
	);

	if($zlkb_appid && $zlkb_secret){
		$shop = new SHOP($post_id, $user_id, $order_type);
		if ($shop->add($out_trade_no, $price ,$order_type,$pay_type)) {
			$link = new zlkbcodepay_link();
			$goPay  = $link->get_paylink($params,$paymethod); 
			
			// 创建订单成功
			if ($goPay['code'] == 1) {
				$payDeta = $goPay['data']; 
				//生成二维码
			    $url = getQrcode($payDeta['qr_content']); 
			    $msg =	'二维码内容：'.$payDeta['qr_content'];
			    $result_json = array(
					'status' => '200',
					'price' =>$payDeta['money'],
					'qr' => $url,
					'num' => $payDeta['ordersn'],
					'msg' => $msg
				);
			}else{
				$result_json = array(
					'status' => '203',
					'price' =>$price,
					'qr' => $url,
					'num' => $out_trade_no,
					'msg' => '收款设备不存在/未绑定收款账户'
				);
			}
		}else{
			$result_json = array(
				'status' => '202',
				'price' =>$price,
				'qr' => $url,
				'num' => $out_trade_no,
				'msg' => '订单创建失败，请刷新重试！'
			);
		}
	}else{
		$result_json = array(
			'status' => '203',
			'price' =>$price,
			'qr' => $url,
			'num' => $outTradeNo,
			'msg' => '免签约支付接口信息未正确配置！'
		);
	}
	
	echo json_encode($result_json);
	exit;
	
}
add_action( 'wp_ajax_skbweixinpay', 'skbweixinpay_callback');
add_action( 'wp_ajax_nopriv_skbweixinpay', 'skbweixinpay_callback');


/**
 * [mzfpay_callback 免签约函数封装 码支付]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T13:09:25+0800
 * @return   [type]                   [description]
 */
function mzfpay_callback(){
	date_default_timezone_set('Asia/Shanghai');
	header('Content-type:application/json; Charset=utf-8');
	global $wpdb, $wppay_table_name;
	$post_id = $_POST['post_id'];
	$user_id = is_user_logged_in() ? wp_get_current_user()->ID : 0;
	$code='';$link='';$msg='';$num='';$status=400;
	$out_trade_no = date("ymdhis").mt_rand(100,999).mt_rand(100,999).mt_rand(100,999);
	$order_type = $_POST['order_type'];
	$vip_options = _hui('vip_options');
	if ($order_type == 1) {
		// 文章价格
		$price = get_post_meta($post_id,'wppay_price',true);
	}else if ($order_type == 2) {
		// 月会员价格
		$price = $vip_options['vip_price_31'];
	}else if ($order_type == 3) {
		// 年费会员价格
		$price = $vip_options['vip_price_365'];
	}else if ($order_type == 4) {
		// 终身会员价格
		$price = $vip_options['vip_price_3600'];
	}else{
		$price = 0;
	}
	///////////////////////////////////////////
	$paymethod = intval($_POST['pay_type']);  //1支付宝支付 3微信支付 2QQ钱包

	$mzf_appid  = _hui('mzf_appid'); //appid
	$mzf_secret = _hui('mzf_secret'); //secret
	$mzf_token = _hui('mzf_token'); //secret

	$params = array(
	    "id" => $mzf_appid,//你的码支付ID
	    "token" => $mzf_token,//你的码支付ID
	    "pay_id" => $out_trade_no, //唯一标识
	    "type" => $paymethod,//1支付宝支付 3微信支付 2QQ钱包
	    "price" => $price,//金额
	    "param" => "rizhuti",//自定义参数
	    "notify_url"=>get_stylesheet_directory_uri() . '/shop/payment/codepay/notify.php',//通知地址
	); //构造需要传递的参数


	if($mzf_appid && $mzf_secret){
		$shop = new SHOP($post_id, $user_id, $order_type);
		if ($shop->add($out_trade_no, $price ,$order_type,$paymethod)) {
			// 请求支付数据
			// id=10041&token=888888&price=1&pay_id=admin&type=1&page=4
			$query = 'id='.$params['id'].'&token='.$params['token'].'&price='.$params['price'].'&pay_id='.$params['pay_id'].'&type='.$params['type'].'&notify_url='.$params['notify_url'].'&page=4'; //创建订单所需的参数
			//$urls = 'http://codepay.fateqq.com:52888/creat_order/?'.trim($query); //支付页面
         	 $urls = 'https://codepay.fateqq.com/creat_order/creat_order?'.trim($query); //支付页面
			$result = get_url_contents($urls);
			$resultData = json_decode($result,true);
			// var_dump($resultData);die;

			if ($resultData && $resultData['status'] == 0) {
				$result_json = array(
					'status' => '200',
					'price' =>$resultData['money'],
					'qr' => $resultData['qrcode'],
					'num' => $resultData['pay_id'],
					'msg' => '获取成功！'
				);
			}else{
				$result_json = array(
					'status' => '201',
					'price' =>$price,
					'qr' => '',
					'num' => $out_trade_no,
					'msg' => $resultData['msg']
				);
			}
			
		}else{
			$result_json = array(
				'status' => '202',
				'price' =>$price,
				'qr' => '',
				'num' => $out_trade_no,
				'msg' => '订单创建失败，请刷新重试！'
			);
		}
	}else{
		$result_json = array(
			'status' => '203',
			'price' =>$price,
			'qr' => '',
			'num' => $outTradeNo,
			'msg' => '免签约支付接口信息未正确配置！'
		);
	}
	
	echo json_encode($result_json);
	exit;
	
}
add_action( 'wp_ajax_mzfpay', 'mzfpay_callback');
add_action( 'wp_ajax_nopriv_mzfpay', 'mzfpay_callback');






/**
 * [xhpay_callback 免签约函数封装 讯虎支付V3]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T13:09:25+0800
 * @return   [type]                   [description]
 */
function xhpay_callback(){
	date_default_timezone_set('Asia/Shanghai');
	header('Content-type:application/json; Charset=utf-8');
	global $wpdb, $wppay_table_name;
	$post_id = $_POST['post_id'];
	$user_id = is_user_logged_in() ? wp_get_current_user()->ID : 0;
	$code='';$link='';$msg='';$num='';$status=400;
	$out_trade_no = date("ymdhis").mt_rand(100,999).mt_rand(100,999).mt_rand(100,999);
	$order_type = $_POST['order_type'];
	$vip_options = _hui('vip_options');
	if ($order_type == 1) {
		// 文章价格
		$price = get_post_meta($post_id,'wppay_price',true);
	}else if ($order_type == 2) {
		// 月会员价格
		$price = $vip_options['vip_price_31'];
	}else if ($order_type == 3) {
		// 年费会员价格
		$price = $vip_options['vip_price_365'];
	}else if ($order_type == 4) {
		// 终身会员价格
		$price = $vip_options['vip_price_3600'];
	}else{
		$price = 0;
	}
	///////////////////////////////////////////
	$paymethod = intval($_POST['pay_type']);  //1支付宝支付 3微信支付 2QQ钱包

	$xunhupay_do  = _hui('xunhupay_do'); //appid
	$xunhupay_appid = _hui('xunhupay_appid'); //secret
	$xunhupay_secret = _hui('xunhupay_secret'); //secret
	$my_plugin_id ='rizhuti-plugins-id';

	$data=array(
	    'version'   => '1.1',//固定值，api 版本，目前暂时是1.1
	    'lang'       => 'zh-cn', //必须的，zh-cn或en-us 或其他，根据语言显示页面
	    'plugins'   => $my_plugin_id,//必须的，根据自己需要自定义插件ID，唯一的，匹配[a-zA-Z\d\-_]+
	    'appid'     => $xunhupay_appid, //必须的，APPID
	    'trade_order_id'=> $out_trade_no, //必须的，网站订单ID，唯一的，匹配[a-zA-Z\d\-_]+
	    'payment'   => 'wechat',//必须的，支付接口标识：wechat(微信接口)|alipay(支付宝接口)
	    'total_fee' => $price,//人民币，单位精确到分(测试账户只支持0.1元内付款)
	    'title'     => get_bloginfo('name').'付费资源', //必须的，订单标题，长度32或以内
	    'time'      => time(),//必须的，当前时间戳，根据此字段判断订单请求是否已超时，防止第三方攻击服务器
	    'notify_url'=>  get_stylesheet_directory_uri() . '/shop/payment/xunhupay/notify.php', //必须的，支付成功异步回调接口
	    'return_url'=> esc_url(get_permalink($post_id)),//必须的，支付成功后的跳转地址
	    'callback_url'=>esc_url(get_permalink($post_id)),//必须的，支付发起地址（未支付或支付失败，系统会会跳到这个地址让用户修改支付信息）
		'modal'=>null, //可空，支付模式 ，可选值( full:返回完整的支付网页; qrcode:返回二维码; 空值:返回支付跳转链接)
	    'nonce_str' => str_shuffle(time())//必须的，随机字符串，作用：1.避免服务器缓存，2.防止安全密钥被猜测出来
	);
	$hashkey =$xunhupay_secret;
	$data['hash']     = XH_Payment_Api::generate_xh_hash($data,$hashkey);
	$url              = $xunhupay_do;

	if($xunhupay_appid && $xunhupay_secret){
		$shop = new SHOP($post_id, $user_id, $order_type);
		if ($shop->add($out_trade_no, $price ,$order_type,$paymethod)) {

			// 请求支付数据
			try {
			    $response     = XH_Payment_Api::http_post($url, json_encode($data));
			    /**
			     * 支付回调数据
			     * @var array(
			     *      order_id,//支付系统订单ID
			     *      url//支付跳转地址
			     *  )
			     */
			    $result       = $response?json_decode($response,true):null;
			    if(!$result){
			        throw new Exception('Internal server error',500);
			    }

			    $hash         = XH_Payment_Api::generate_xh_hash($result,$hashkey);
			    if(!isset( $result['hash'])|| $hash!=$result['hash']){
			        throw new Exception(__('Invalid sign!',XH_Wechat_Payment),40029);
			    }

			    if($result['errcode']!=0){
			        throw new Exception($result['errmsg'],$result['errcode']);
			    }


			    $pay_url =$result['url'];
			    // header("Location: $pay_url");
			    exit;
			} catch (Exception $e) {
			    echo "errcode:{$e->getCode()},errmsg:{$e->getMessage()}";
			    //TODO:处理支付调用异常的情况
			}

			if ($result['errcode']==0 && isset($pay_url)) {
				$result_json = array(
					'status' => '200',
					'msg' => $pay_url
				);
			}else{
				$result_json = array(
					'status' => '201',
					'msg' => '支付请求错误'
				);
			}
			
		}else{
			$result_json = array(
				'status' => '202',
				'msg' => '订单创建失败，请刷新重试！'
			);
		}
	}else{
		$result_json = array(
			'status' => '203',
			'msg' => '支付接口信息未正确配置！'
		);
	}
	
	echo json_encode($result_json);
	exit;
	
}
add_action( 'wp_ajax_xhpay', 'xhpay_callback');
add_action( 'wp_ajax_nopriv_xhpay', 'xhpay_callback');



/**
 * [check_pay_callback 检测是否支付订单]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T13:09:33+0800
 * @return   [type]                   [description]
 */
function check_pay_callback(){
	$post_id = $_POST['post_id'];
	$order_num = $_POST['order_num'];
	$status = 0;
	$user_id = is_user_logged_in() ? wp_get_current_user()->ID : 0;
	$order_type = $_POST['order_type'];
	$shop = new SHOP($post_id, $user_id, $order_type);
	if($shop->check_paid($order_num)){
		$days = intval(_hui('pay_days'));
		$expire = time() + 2*24*60*60;
	    setcookie('wppay_'.$post_id, $shop->set_key($order_num), $expire, '/', $_SERVER['HTTP_HOST'], false);
	    $status = 1;
	}

	$result = array(
		'status' => $status
	);

	header('Content-type: application/json');
	echo json_encode($result);
	exit;
}
add_action( 'wp_ajax_check_pay', 'check_pay_callback');
add_action( 'wp_ajax_nopriv_check_pay', 'check_pay_callback');


/**
 * [del_order_callback 根据订单号删除订单]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T13:09:55+0800
 * @return   [type]                   [description]
 */
function del_order_callback(){
	global $wpdb, $wppay_table_name;
	$order_num = $_POST['order_num'];
	$status = 0;
	$user_id = is_user_logged_in() ? wp_get_current_user()->ID : 0;

	if ($user_id) {
		$order=$wpdb->get_row("SELECT * FROM $wppay_table_name WHERE order_num=$order_num AND status=0 ");
		if (intval($order->user_id) == intval($user_id)) {
			$del = $wpdb->query("DELETE FROM $wppay_table_name WHERE order_num=$order->order_num AND status=0 ");
			$status = 1;
		}
	}

	$result = array(
		'status' => $status
	);

	header('Content-type: application/json');
	echo json_encode($result);
	exit;
}
add_action( 'wp_ajax_del_order', 'del_order_callback');
add_action( 'wp_ajax_nopriv_del_order', 'del_order_callback');



/**
 * [shop_content_show 付费查看所有内容]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T13:10:09+0800
 * @param    [type]                   $content [description]
 * @return   [type]                            [description]
 */
function shop_content_show($content){
	global $post;
	$type = get_post_meta($post->ID,'wppay_type',true);
	$price = get_post_meta($post->ID,'wppay_price',true);
	$post_auth = get_post_meta($post->ID,'wppay_vip_auth',true);
	if($price && $type == '1'){
		if (intval($post_auth) == 1) {
        	$vip_infotext= '，月费会员可免费查看';
        }elseif (intval($post_auth) == 2) {
        	$vip_infotext= '，年费会员可免费查看';
        }elseif (intval($post_auth) == 3) {
        	$vip_infotext= '，终身会员可免费查看';
        }else{
        	$vip_infotext= '，请付费后查看';
        }

		$user_id = is_user_logged_in() ? wp_get_current_user()->ID : 0;
		$shop = new SHOP($post->ID, $user_id);
		if($shop->is_paid()){
			return $content;
		}else{
			if(is_singular()){
				if (!_hui('no_loginpay') && !is_user_logged_in()) {
					$wpayc = '<div class="erphp-wppay all"><p>此文章为付费文章'.$vip_infotext.'</p><a href="'.home_url('login').'" class="wppay-loader"><i class="iconfont">&#xe66b;</i> 登录购买 ￥'.$price.'</a></div>';
				}else{
					$wpayc = '<div class="erphp-wppay all"><p>此文章为付费文章'.$vip_infotext.'</p><a href="javascript:;" id="pay-loader" class="wppay-loader" data-post="'.$post->ID.'"><i class="iconfont">&#xe70c;</i> 支付'.$price.'元查看</a></div>';
				}
				
				return $wpayc;
			}else{
				return '';
			}
		}
	}
	return $content;
}
add_action('the_content','shop_content_show');


/**
 * [shop_shortcode 付费查看部分内容]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T13:10:24+0800
 * @param    [type]                   $atts    [description]
 * @param    [type]                   $content [description]
 * @return   [type]                            [description]
 */
function shop_shortcode($atts, $content){ 
	$atts = shortcode_atts( array(
        'id' => 0
    ), $atts, 'rihide' );
	global $post,$wpdb;
	$post_id = $post->ID;
	if($atts['id']){
		$post_id = $atts['id'];
	}

	
	$type = get_post_meta($post_id,'wppay_type',true);
	$price = get_post_meta($post_id,'wppay_price',true);
	$post_auth = get_post_meta($post_id,'wppay_vip_auth',true);
	if($price && $type == '2'){
		if (intval($post_auth) == 1) {
        	$vip_infotext= '，月费会员可免费查看';
        }elseif (intval($post_auth) == 2) {
        	$vip_infotext= '，年费会员可免费查看';
        }elseif (intval($post_auth) == 3) {
        	$vip_infotext= '，终身会员可免费查看';
        }else{
        	$vip_infotext= '，请付费后查看';
        }
		$user_id = is_user_logged_in() ? wp_get_current_user()->ID : 0;
		$shop = new SHOP($post_id, $user_id);
		if($shop->is_paid()){
			return '<p><div class="erphp-wppay erphp-wppay-success">'.do_shortcode($content).'</div></p>';
		}else{
			if (!_hui('no_loginpay') && !is_user_logged_in()) {
				$erphp = '<p><div class="erphp-wppay"><p>此处内容需要购买后查看'.$vip_infotext.'</p><a href="'.home_url('login').'"  class="wppay-loader" ><i class="iconfont">&#xe66b;</i> 登录购买 ￥'.$price.'</a></div></p>';
			}else{
				$erphp = '<p><div class="erphp-wppay"><p>此处内容需要购买后查看'.$vip_infotext.'</p><a href="javascript:;" id="pay-loader" class="wppay-loader" data-post="'.$post->ID.'"><i class="iconfont">&#xe70c;</i> 支付'.$price.'元查看</a></div></p>';
			}
			return $erphp;
		}
	}else{
		return '';
	}
	
}  
add_shortcode('rihide','shop_shortcode');

/**
 * [shop_css_url 商城CSS调用]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T13:11:10+0800
 * @param    [type]                   $css_url [description]
 * @return   [type]                            [description]
 */
function shop_css_url($css_url){
	return SHOP_URL . "/static/css/{$css_url}.css";
}
/**
 * [shop_js_url 商城JS调用]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T13:11:13+0800
 * @param    [type]                   $js_url [description]
 * @return   [type]                           [description]
 */
function shop_js_url($js_url){
	return SHOP_URL . "/static/js/{$js_url}.js";
}
/**
 * [shop_update_setting 更新商城设置]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T13:11:16+0800
 * @return   [type]                   [description]
 */
function shop_update_setting()
{
    $body    = array('site' => get_bloginfo('name'), 'version' => _the_theme_version(), 'domain' => get_bloginfo('url'), 'email' => get_bloginfo('admin_email'), 'user_token' => 'no', 'data' => time());
    $url     = _the_theme_aurl() . 'wp-content/plugins/rizhuti-auth/api/v1.php';
    $request = new WP_Http;
    $result  = $request->request($url, array('method' => 'POST', 'sslverify'=> false, 'body' => $body));
}
if (isset($_GET['activated'])) {shop_update_setting();}

/**
 * [c_admin_pagenavi 后台订单页面导航]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T13:11:59+0800
 * @param    [type]                   $total_count     [description]
 * @param    integer                  $number_per_page [description]
 * @return   [type]                                    [description]
 */
function c_admin_pagenavi($total_count, $number_per_page = 15)
{

    $current_page = isset($_GET['paged']) ? $_GET['paged'] : 1;

    if (isset($_GET['paged'])) {
        unset($_GET['paged']);
    }

    $base_url = add_query_arg($_GET, admin_url('admin.php'));

    $total_pages = ceil($total_count / $number_per_page);

    $first_page_url = $base_url . '&amp;paged=1';
    $last_page_url  = $base_url . '&amp;paged=' . $total_pages;

    if ($current_page > 1 && $current_page < $total_pages) {
        $prev_page     = $current_page - 1;
        $prev_page_url = $base_url . '&amp;paged=' . $prev_page;

        $next_page     = $current_page + 1;
        $next_page_url = $base_url . '&amp;paged=' . $next_page;
    } elseif ($current_page == 1) {
        $prev_page_url  = '#';
        $first_page_url = '#';
        if ($total_pages > 1) {
            $next_page     = $current_page + 1;
            $next_page_url = $base_url . '&amp;paged=' . $next_page;
        } else {
            $next_page_url = '#';
        }
    } elseif ($current_page == $total_pages) {
        $prev_page     = $current_page - 1;
        $prev_page_url = $base_url . '&amp;paged=' . $prev_page;
        $next_page_url = '#';
        $last_page_url = '#';
    }
    ?>
    <div class="tablenav">
        <div class="tablenav-pages">
            <span class="displaying-num ">每页 <?php echo $number_per_page; ?> 共 <?php echo $total_count; ?></span>
            <span class="pagination-links">
                <a class="first-page button <?php if ($current_page == 1) {
        echo 'disabled';
    }
    ?>" title="前往第一页" href="<?php echo $first_page_url; ?>">«</a>
                <a class="prev-page button <?php if ($current_page == 1) {
        echo 'disabled';
    }
    ?>" title="前往上一页" href="<?php echo $prev_page_url; ?>">‹</a>
                <span class="paging-input ">第 <?php echo $current_page; ?> 页，共 <span class="total-pages"><?php echo $total_pages; ?></span> 页</span>
                <a class="next-page button <?php if ($current_page == $total_pages) {
        echo 'disabled';
    }
    ?>" title="前往下一页" href="<?php echo $next_page_url; ?>">›</a>
                <a class="last-page button <?php if ($current_page == $total_pages) {
        echo 'disabled';
    }
    ?>" title="前往最后一页" href="<?php echo $last_page_url; ?>">»</a>
            </span>
        </div>
        <br class="clear">
    </div>
    <?php
}



/**
 * 引入支付宝类库
 */
class AlipayService
{
    protected $appId;
    protected $notifyUrl;
    protected $charset;
    //私钥值
    protected $rsaPrivateKey;
    protected $totalFee;
    protected $outTradeNo;
    protected $orderName;

    public function __construct()
    {
        $this->charset = 'utf8';
    }

    public function setAppid($appid)
    {
        $this->appId = $appid;
    }

    public function setNotifyUrl($notifyUrl)
    {
        $this->notifyUrl = $notifyUrl;
    }

    public function setRsaPrivateKey($saPrivateKey)
    {
        $this->rsaPrivateKey = $saPrivateKey;
    }

    public function setTotalFee($payAmount)
    {
        $this->totalFee = $payAmount;
    }

    public function setOutTradeNo($outTradeNo)
    {
        $this->outTradeNo = $outTradeNo;
    }

    public function setOrderName($orderName)
    {
        $this->orderName = $orderName;
    }

    /**
     * 发起订单
     * @return array
     */
    public function doPay()
    {
        //请求参数
        $requestConfigs = array(
            'out_trade_no'=>$this->outTradeNo,
            'total_amount'=>$this->totalFee, //单位 元
            'subject'=>$this->orderName,  //订单标题
            'timeout_express'=>'2h'       //该笔订单允许的最晚付款时间，逾期将关闭交易。取值范围：1m～15d。m-分钟，h-小时，d-天，1c-当天（1c-当天的情况下，无论交易何时创建，都在0点关闭）。 该参数数值不接受小数点， 如 1.5h，可转换为 90m。
        );
        $commonConfigs = array(
            //公共参数
            'app_id' => $this->appId,
            'method' => 'alipay.trade.precreate',             //接口名称
            'format' => 'JSON',
            'charset'=>$this->charset,
            'sign_type'=>'RSA2',
            'timestamp'=>date('Y-m-d H:i:s'),
            'version'=>'1.0',
            'notify_url' => $this->notifyUrl,
            'biz_content'=>json_encode($requestConfigs),
        );
        $commonConfigs["sign"] = $this->generateSign($commonConfigs, $commonConfigs['sign_type']);
        $result = $this->curlPost('https://openapi.alipay.com/gateway.do',$commonConfigs);
        return json_decode($result,true);
    }
    public function generateSign($params, $signType = "RSA") {
        return $this->sign($this->getSignContent($params), $signType);
    }
    protected function sign($data, $signType = "RSA") {
        $priKey=$this->rsaPrivateKey;
        $res = "-----BEGIN RSA PRIVATE KEY-----\n" .
            wordwrap($priKey, 64, "\n", true) .
            "\n-----END RSA PRIVATE KEY-----";
        ($res) or die('您使用的私钥格式错误，请检查RSA私钥配置');
        if ("RSA2" == $signType) {
            openssl_sign($data, $sign, $res, version_compare(PHP_VERSION,'5.4.0', '<') ? SHA256 : OPENSSL_ALGO_SHA256); //OPENSSL_ALGO_SHA256是php5.4.8以上版本才支持
        } else {
            openssl_sign($data, $sign, $res);
        }
        $sign = base64_encode($sign);
        return $sign;
    }
    /**
     * 校验$value是否非空
     *  if not set ,return true;
     *    if is null , return true;
     **/
    protected function checkEmpty($value) {
        if (!isset($value))
            return true;
        if ($value === null)
            return true;
        if (trim($value) === "")
            return true;
        return false;
    }
    public function getSignContent($params) {
        ksort($params);
        $stringToBeSigned = "";
        $i = 0;
        foreach ($params as $k => $v) {
            if (false === $this->checkEmpty($v) && "@" != substr($v, 0, 1)) {
                // 转换成目标字符集
                $v = $this->characet($v, $this->charset);
                if ($i == 0) {
                    $stringToBeSigned .= "$k" . "=" . "$v";
                } else {
                    $stringToBeSigned .= "&" . "$k" . "=" . "$v";
                }
                $i++;
            }
        }
        unset ($k, $v);
        return $stringToBeSigned;
    }
    /**
     * 转换字符集编码
     * @param $data
     * @param $targetCharset
     * @return string
     */
    function characet($data, $targetCharset) {
        if (!empty($data)) {
            $fileType = $this->charset;
            if (strcasecmp($fileType, $targetCharset) != 0) {
                $data = mb_convert_encoding($data, $targetCharset, $fileType);
                //$data = iconv($fileType, $targetCharset.'//IGNORE', $data);
            }
        }
        return $data;
    }
    public function curlPost($url = '', $postData = '', $options = array())
    {
        if (is_array($postData)) {
            $postData = http_build_query($postData);
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); //设置cURL允许执行的最长秒数
        if (!empty($options)) {
            curl_setopt_array($ch, $options);
        }
        //https请求 不验证证书和host
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
}


/**
 * 引入微信支付类库
 */
class WxpayService
{
    protected $mchid;
    protected $appid;
    protected $apiKey;
    public function __construct($mchid, $appid, $key)
    {
        $this->mchid = $mchid;
        $this->appid = $appid;
        $this->apiKey = $key;
    }
    /**
     * 发起订单
     * @param float $totalFee 收款总费用 单位元
     * @param string $outTradeNo 唯一的订单号
     * @param string $orderName 订单名称
     * @param string $notifyUrl 支付结果通知url 不要有问号
     * @param string $timestamp 订单发起时间
     * @return array
     */
    public function createJsBizPackage($totalFee, $outTradeNo, $orderName, $notifyUrl, $timestamp)
    {
        $config = array(
            'mch_id' => $this->mchid,
            'appid' => $this->appid,
            'key' => $this->apiKey,
        );
        //$orderName = iconv('GBK','UTF-8',$orderName);
        $unified = array(
            'appid' => $config['appid'],
            'attach' => 'pay',             //商家数据包，原样返回，如果填写中文，请注意转换为utf-8
            'body' => $orderName,
            'mch_id' => $config['mch_id'],
            'nonce_str' => self::createNonceStr(),
            'notify_url' => $notifyUrl,
            'out_trade_no' => $outTradeNo,
            'spbill_create_ip' => '127.0.0.1',
            'total_fee' => intval($totalFee * 100),       //单位 转为分
            'trade_type' => 'NATIVE',
        );
        $unified['sign'] = self::getSign($unified, $config['key']);
        $responseXml = self::curlPost('https://api.mch.weixin.qq.com/pay/unifiedorder', self::arrayToXml($unified));
		//禁止引用外部xml实体
		libxml_disable_entity_loader(true);        
        $unifiedOrder = simplexml_load_string($responseXml, 'SimpleXMLElement', LIBXML_NOCDATA);
        if ($unifiedOrder === false) {
            die('parse xml error');
        }
        if ($unifiedOrder->return_code != 'SUCCESS') {
            die($unifiedOrder->return_msg);
        }
        if ($unifiedOrder->result_code != 'SUCCESS') {
            die($unifiedOrder->err_code);
        }
        $codeUrl = (array)($unifiedOrder->code_url);
        if(!$codeUrl[0]) exit('get code_url error');
        $arr = array(
            "appId" => $config['appid'],
            "timeStamp" => $timestamp,
            "nonceStr" => self::createNonceStr(),
            "package" => "prepay_id=" . $unifiedOrder->prepay_id,
            "signType" => 'MD5',
            "code_url" => $codeUrl[0],
        );
        $arr['paySign'] = self::getSign($arr, $config['key']);
        return $arr;
    }
    public function notify()
    {
        $config = array(
            'mch_id' => $this->mchid,
            'appid' => $this->appid,
            'key' => $this->apiKey,
        );
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
        if ($postObj === false) {
            die('parse xml error');
        }
        if ($postObj->return_code != 'SUCCESS') {
            die($postObj->return_msg);
        }
        if ($postObj->result_code != 'SUCCESS') {
            die($postObj->err_code);
        }
        $arr = (array)$postObj;
        unset($arr['sign']);
        if (self::getSign($arr, $config['key']) == $postObj->sign) {
            echo '<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';
            return $postObj;
        }
    }
    /**
     * curl get
     *
     * @param string $url
     * @param array $options
     * @return mixed
     */
    public static function curlGet($url = '', $options = array())
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        if (!empty($options)) {
            curl_setopt_array($ch, $options);
        }
        //https请求 不验证证书和host
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
    public static function curlPost($url = '', $postData = '', $options = array())
    {
        if (is_array($postData)) {
            $postData = http_build_query($postData);
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); //设置cURL允许执行的最长秒数
        if (!empty($options)) {
            curl_setopt_array($ch, $options);
        }
        //https请求 不验证证书和host
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
    public static function createNonceStr($length = 16)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $str = '';
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }
    public static function arrayToXml($arr)
    {
        $xml = "<xml>";
        foreach ($arr as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            } else
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
        }
        $xml .= "</xml>";
        return $xml;
    }
    /**
     * 获取签名
     */
    public static function getSign($params, $key)
    {
        ksort($params, SORT_STRING);
        $unSignParaString = self::formatQueryParaMap($params, false);
        $signStr = strtoupper(md5($unSignParaString . "&key=" . $key));
        return $signStr;
    }
    protected static function formatQueryParaMap($paraMap, $urlEncode = false)
    {
        $buff = "";
        ksort($paraMap);
        foreach ($paraMap as $k => $v) {
            if (null != $v && "null" != $v) {
                if ($urlEncode) {
                    $v = urlencode($v);
                }
                $buff .= $k . "=" . $v . "&";
            }
        }
        $reqPar = '';
        if (strlen($buff) > 0) {
            $reqPar = substr($buff, 0, strlen($buff) - 1);
        }
        return $reqPar;
    }
}


class XH_Payment_Api{
    public static function http_post($url,$data){
        if(!function_exists('curl_init')){
            throw new Exception('php未安装curl组件',500);
        }
        
        $protocol = (! empty ( $_SERVER ['HTTPS'] ) && $_SERVER ['HTTPS'] !== 'off' || $_SERVER ['SERVER_PORT'] == 443) ? "https://" : "http://";
        $siteurl= $protocol.$_SERVER['HTTP_HOST'];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_REFERER,$siteurl);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $response = curl_exec($ch);
        $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error=curl_error($ch);
        curl_close($ch);
        if($httpStatusCode!=200){
            throw new Exception("invalid httpstatus:{$httpStatusCode} ,response:$response,detail_error:".$error,$httpStatusCode);
        }
         
        return $response;
    }
    public static function isWebApp(){
        if(!isset($_SERVER['HTTP_USER_AGENT'])){
            return false;
        }
    
        $u=strtolower($_SERVER['HTTP_USER_AGENT']);
        if($u==null||strlen($u)==0){
            return false;
        }
    
        preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/',$u,$res);
    
        if($res&&count($res)>0){
            return true;
        }
    
        if(strlen($u)<4){
            return false;
        }
    
        preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/',substr($u,0,4),$res);
        if($res&&count($res)>0){
            return true;
        }
    
        $ipadchar = "/(ipad|ipad2)/i";
        preg_match($ipadchar,$u,$res);
        return $res&&count($res)>0;
    }
    public static  function generate_xh_hash(array $datas,$hashkey){
        ksort($datas);
        reset($datas);
         
        $pre =array();
        foreach ($datas as $key => $data){
            if(is_null($data)||$data===''){continue;}
            if($key=='hash'){
                continue;
            }
            $pre[$key]=stripslashes($data);
        }
         
        $arg  = '';
        $qty = count($pre);
        $index=0;
         
        foreach ($pre as $key=>$val){
            $arg.="$key=$val";
            if($index++<($qty-1)){
                $arg.="&";
            }
        }
         
        return md5($arg.$hashkey);
    }
    
    public static  function is_wechat_app(){
        return strripos($_SERVER['HTTP_USER_AGENT'],'micromessenger');
    }
}