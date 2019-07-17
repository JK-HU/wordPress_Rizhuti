<?php

define('SHOP_VERSION', '1.0.0');
define('SHOP_URL', get_stylesheet_directory_uri().'/shop');
define('SHOP_PATH', dirname( __FILE__ ));
define('SHOP_ADMIN_URL', admin_url());

/**
 * 定义数据库wp_wppay_order 此处切勿修改
 */
global $wpdb, $wppay_table_name;
$wppay_table_name = isset($table_prefix) ? ($table_prefix . 'wppay_order') : ($wpdb->prefix . 'wppay_order');

/**
 * 加载类
 */
require SHOP_PATH . '/include/shop.class.php';
require SHOP_PATH . '/include/shop.functions.php';
require SHOP_PATH . '/include/qr.class.php';
