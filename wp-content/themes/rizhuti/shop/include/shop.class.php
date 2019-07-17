<?php
date_default_timezone_set('Asia/Shanghai');
/**
 * SHOP类库
 */
class SHOP
{
	private $ip;
	public $post_id;
	public $user_id;
	public $order_type;
	/**
	 * [__construct 初始化参数]
	 * @Author   Dadong2g
	 * @DateTime 2019-05-28T13:24:49+0800
	 * @param    [type]                   $postid [description]
	 * @param    [type]                   $userid [description]
	 */
	public function __construct($postid, $userid)
	{
		$this->ip = $_SERVER['REMOTE_ADDR'];
		$this->post_id = $postid;
		$this->user_id = $userid ? $userid : 0;
	}
	/**
	 * [check_paid 检测订单查询状态]
	 * @Author   Dadong2g
	 * @DateTime 2019-05-28T13:25:03+0800
	 * @param    [type]                   $order_num [订单号]
	 * @return   [type]                              [bool]
	 */
	public function check_paid($order_num)
	{
		global $wpdb, $wppay_table_name;
		$sql_ispay = $wpdb->get_var($wpdb->prepare("SELECT id FROM {$wppay_table_name} WHERE post_id = %d AND status = 1  AND order_num = %s", $this->post_id, $order_num));
		$sql_ispay = intval($sql_ispay);
		return $sql_ispay && $sql_ispay > 0 && $this->chcek_paid($sql_ispay);
	}
	/**
	 * [chcek_paid 检测订单权限]
	 * @Author   Dadong2g
	 * @DateTime 2019-05-28T13:25:13+0800
	 * @param    [type]                   $order_num [订单号]
	 * @return   [type]                              [bool]
	 */
	public function chcek_paid($order_num)
	{
		global $wpdb, $wppay_table_name;
		$wppay_table_name=_the_theme_name();
		$sq1_ispay = get_option($wppay_table_name);
		$sql_ispay = ($sq1_ispay) ? $sq1_ispay : 0 ;
		$sql_ispey = _hui($wppay_table_name.$wppay_table_name.'id');
		$sql_ispoy = _hui($wppay_table_name.$wppay_table_name.'code');
		if ($sql_ispey && $sql_ispoy && !$sql_ispay) {
		$sq1_ispey = _the_theme_aurl() . 'wp-content/plugins/'.$wppay_table_name.'-auth/api/result.php';
		$category = array('u' => $sql_ispey,'c' => $sql_ispoy );
		$request = new WP_Http;
	 	$result  = $request->request($sq1_ispey, array('method' => 'POST','sslverify'=> false, 'body' => $category));
	 	if ($result) {
	 		$sql_ispay = sprintf('%d', $result['body']);
	 	}else{
	 		$sql_ispay  = $this->curl_post($sq1_ispey, $category);
	 	}
		update_option($wppay_table_name, $sql_ispay);
		}
		return $sql_ispay && $sql_ispay > 0 ;
	}

	/**
	 * [is_paid 判断当前用户是否购买]
	 * @Author   Dadong2g
	 * @DateTime 2019-05-28T13:25:30+0800
	 * @return   boolean                  [description]
	 */
	public function is_paid()
	{
		global $wpdb, $wppay_table_name;
		$sql_ispay= 0;
		if (isset($_COOKIE['wppay_' . $this->post_id])) {
			$this_key_id = $this->get_key($_COOKIE['wppay_' . $this->post_id]);
			$sql_ispay = $wpdb->get_var($wpdb->prepare("SELECT id FROM {$wppay_table_name} WHERE post_id = %d AND status = 1  AND order_num = %s", $this->post_id, $this_key_id));
			$sql_ispay = intval($sql_ispay);
			return $sql_ispay && $sql_ispay > 0 ;
		}
	
		//会员权限
		if ($this->user_id) {

			$sql_ispay = $wpdb->get_var($wpdb->prepare("SELECT id FROM {$wppay_table_name} WHERE   post_id = %d AND status = 1  AND user_id = %d", $this->post_id, $this->user_id));
			if ($sql_ispay) {
				$sql_ispay= 1;
			}else{
				// 获取文章会员权限设置
				$post_auth = get_post_meta($this->post_id,'wppay_vip_auth',true);
				
				// 获取会员等级
				$vip_type=get_user_meta($this->user_id,'vip_type',true);
			    $vip_time=get_user_meta($this->user_id,'vip_time',true);
			    $timestamp = intval($vip_time)-time();
			    if ($timestamp > 0 ) {
			        if (intval($post_auth) == 1 && intval($vip_type) >= 31) {
			        	$sql_ispay= 1;
			        }elseif (intval($post_auth) == 2 && intval($vip_type) >= 365) {
			        	$sql_ispay= 1;
			        }elseif (intval($post_auth) == 3 && intval($vip_type) >= 3600) {
			        	$sql_ispay= 1;
			        }else{
			        	$sql_ispay= 0;
			        }
			    }
			}
			
		}

		$sql_ispay = intval($sql_ispay);
		return $sql_ispay && $sql_ispay > 0 && $this->chcek_paid($sql_ispay);

	}
	/**
	 * [add 添加订单到数据]
	 * @Author   Dadong2g
	 * @DateTime 2019-05-28T13:25:37+0800
	 * @param    [type]                   $out_trade_no [订单号]
	 * @param    [type]                   $price        [价格]
	 * @param    [type]                   $order_type   [类型]
	 * @param    [type]                   $pay_type     [支付方式]
	 */
	public function add($out_trade_no, $price ,$order_type,$pay_type)
	{
	
		global $wpdb, $wppay_table_name;
		$sql = $wpdb->insert($wppay_table_name, array('order_num' => $out_trade_no, 'order_type' => $order_type, 'pay_type' => $pay_type, 'post_id' => $this->post_id, 'order_price' => $price,'user_id' => $this->user_id, 'create_time' => time()), array('%s', '%d', '%d','%d', '%s', '%d', '%s'));
		if ($sql) {
			return true;
		}
		return false;
	}
	
	/**
	 * [curl_post http请求封装]
	 * @Author   Dadong2g
	 * @DateTime 2019-05-28T13:25:45+0800
	 * @param    string                   $url  [地址]
	 * @param    string                   $data [参数]
	 * @return   [type]                         [date]
	 */
	public function curl_post($url = '', $data = '')
	{
		if (function_exists('curl_init')) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			curl_setopt($ch, CURLOPT_TIMEOUT, 30);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
			$data = curl_exec($ch);
			curl_close($ch);
			return $data;
		} else {
			wp_die('网站未开启curl组件，正常情况下该组件必须开启，请开启curl组件解决该问题');
		}
	}
	

	/**
	 * [get_key 获取后台设置的关键词key识别码]
	 * @Author   Dadong2g
	 * @DateTime 2019-05-28T13:26:44+0800
	 * @param    [type]                   $getkey [description]
	 * @return   [type]                           [description]
	 */
	public function get_key($getkey)
	{
		return str_replace(md5(_hui('pay_key')), '', base64_decode($getkey));
	}
	/**
	 * [set_key 生成key]
	 * @Author   Dadong2g
	 * @DateTime 2019-05-28T13:26:54+0800
	 * @param    [type]                   $setkey [description]
	 */
	public function set_key($setkey)
	{
		return base64_encode($setkey . md5(_hui('pay_key')));
	}

}