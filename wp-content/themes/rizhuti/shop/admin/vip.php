<?php

if ( !defined('ABSPATH') ) {exit;}
if(!is_user_logged_in())
{
	wp_die('请先登录系统');
}
date_default_timezone_set('Asia/Shanghai');
global $wpdb, $wppay_table_name;
?>

<script type="text/javascript">
　　window.location.href="<?php echo admin_url('/users.php'); ?>";
</script>
