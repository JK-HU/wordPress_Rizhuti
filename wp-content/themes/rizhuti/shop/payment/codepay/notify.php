<?php
header('Content-type:text/html; Charset=utf-8');
date_default_timezone_set('Asia/Shanghai');
ob_start();
require_once('../../../../../../wp-load.php');
ob_end_clean();
global $wpdb, $wppay_table_name;
$mzf_appid  = _hui('mzf_appid'); //appid
$mzf_secret = _hui('mzf_secret'); //secret


ksort($_POST); //排序post参数
reset($_POST); //内部指针指向数组中的第一个元素
$sign = '';//初始化
foreach ($_POST AS $key => $val) { //遍历POST参数
    if ($val == '' || $key == 'sign') continue; //跳过这些不签名
    if ($sign) $sign .= '&'; //第一个字符串签名不加& 其他加&连接起来参数
    $sign .= "$key=$val"; //拼接为url参数形式
}
if (!$_POST['pay_no'] || md5($sign . $mzf_secret) != $_POST['sign']) { //不合法的数据
    exit('fail');  //返回失败 继续补单
} else { //合法的数据
    //业务处理
    $pay_id = $_POST['pay_id']; //需要充值的ID 或订单号 或用户名
    $money = (float)$_POST['money']; //实际付款金额
    $price = (float)$_POST['price']; //订单的原价
    $param = $_POST['param']; //自定义参数
    $pay_no = $_POST['pay_no']; //流水号


    $order=$wpdb->get_row("select * from $wppay_table_name where order_num='".$pay_id."'");
    if($order){
        $user_id = $order->user_id; //该订单用户id
        $order_type = $order->order_type; //订单类型
        if(!$order->status){

            if ($order->order_type!= 1) {
                up_user_vipinfo($user_id,$order_type);// 更新会员信息
            }

            $update_order = $wpdb->query("UPDATE $wppay_table_name SET pay_num = '".$pay_no."', pay_time = '".time()."' ,status=1 WHERE order_num = '".$pay_id."'");
            // 发送邮件
            if ($update_order && _hui('is_sened_paymail') && $user_id != 0) {
                $user_obj = get_user_by('id', $user_id);
                $email = $user_obj->user_email;
                $title = '恭喜您付款成功';
                $headers = array('Content-Type: text/html; charset=UTF-8');
                $pay_type = ($_POST['paymethod'] == 1) ? '微信支付' : '支付宝' ;
                $a_href = '<a href="'.home_url().'">查看或下载</a>';
                // get_permalink($value->post_id)
                if ($order_type == 1) {
                    $order_name = get_the_title($order->post_id);
                    $a_href = '<a href="'.get_permalink($order->post_id).'">查看或下载</a>';
                }elseif ($order_type == 2) {
                    $order_name = '月费会员';
                }elseif ($order_type == 3) {
                    $order_name = '年费会员';
                }elseif ($order_type == 4) {
                    $order_name = '终身会员';
                }
                $message = tpl_emailPay($order->order_num,$order_name,$order->order_price,$pay_type,$a_href);
                _sendMail($email,$title,$message,$headers);
            }
            echo 'success';exit();
        }
    }
    



    exit('success'); //返回成功 不要删除哦
}