<?php
header("Content-type:text/html;character=utf-8");
ob_start();
require_once dirname(__FILE__) . "/../../../../wp-load.php";
ob_end_clean();
ini_set('display_errors', 'Off');
ini_set('error_reporting', E_ALL);
global $wpdb, $wppay_table_name;
$url = isset($_GET['url']) ? $_GET['url'] : false;
$postId = isset($_GET['id']) ? $_GET['id'] : 0;
if (!$url) {
    wp_die('下载参数信息错误！','下载参数信息错误！');exit();
}

$unlock_down = rizhuti_unlock_url($url, _hui('rzt_down_downkey'));

if (isset($_COOKIE['unlock_down_time'])) {
    wp_die('您的下载太频繁，请一分钟后再试。切勿重复短时间内下载相同资源，以免被扣除下载次数！');exit();
} else {
    $endtime = 60; // 发送一个 60秒过期的 cookie
    setcookie("unlock_down_time", time(), time() + $endtime);
}

// 用户已经登录 并且是会员 满足下载次数限制要求 下载次数非常严格 切勿重复点击
if (is_user_logged_in()) {
    $user_id  = get_current_user_id();
    $vip_type = vip_type($user_id);
    $sql_ispay = $wpdb->get_var($wpdb->prepare("SELECT id FROM {$wppay_table_name} WHERE   post_id = %d AND status = 1  AND user_id = %d", $postId, $user_id));
    if ($vip_type > 0 && empty($sql_ispay)) {
        // 满足下载限制
        $this_vip_downum = this_vip_downum($user_id);
        if ($this_vip_downum['is_down']) {
            update_user_meta($user_id, 'this_vip_downum', $this_vip_downum['today_down_num'] + 1); //更新+1
            $go = rizhuti_download_file($unlock_down);
            exit();
        } else {
            wp_die('今日下载次数已经用完！');exit();
        }
    }
}

$go = rizhuti_download_file($unlock_down);
exit();
