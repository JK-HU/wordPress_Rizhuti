<?php

if ( !defined('ABSPATH') ) {exit;}
if(!is_user_logged_in())
{
	wp_die('请先登录系统');
}
date_default_timezone_set('Asia/Shanghai');
global $wpdb, $wppay_table_name;

$action=isset($_GET['action']) ?$_GET['action'] :false;
$id=isset($_GET['id']) && is_numeric($_GET['id']) ?intval($_GET['id']) :0;
// 清理无效订单
if ($action=="remosql" && current_user_can('administrator')) {
	$del_order = $wpdb->query("DELETE FROM $wppay_table_name WHERE status = 0 ");
	echo'<div class="updated settings-error"><p>成功清理'.$del_order.'条无效订单！</p></div>';
}





// 保存
if($action=="save" && current_user_can('administrator')){
	$result = isset($_POST['result']) && is_numeric($_POST['result']) ?intval($_POST['result']) :0;
	$update_order = $wpdb->query("UPDATE $wppay_table_name SET pay_num = '88888888', pay_time = '".time()."' ,status='".$result."' WHERE id = '".$id."'");
	if(!$update_order){
		echo '<div id="message" class="updated notice is-dismissible"><p>系统更错处理失败</p></div>';
	}
	else {
		echo '<div id="message" class="updated notice is-dismissible"><p>更新成功</p></div>';
	}
	unset($id);
}

// 内页
if($id && current_user_can('administrator'))
{
	$info=$wpdb->get_row("SELECT * FROM $wppay_table_name where id=".$id);
	if(!$info->id)
	{
		echo '<div id="message" class="updated notice is-dismissible"><p>订单ID无效</p></div>';
		exit;
	}
	?>
<div class="wrap">
<h1 class="wp-heading-inline">查看订单详情</h1>
    <hr class="wp-header-end">
<form method="post" action="<?php echo admin_url('admin.php?page=shop_orders_page&action=save&id='.$id); ?>" >
    <table class="form-table">
        <tr>
            <td valign="top" width="30%"><strong>订单号</strong><br />
            </td>
            <td><?php echo $info->order_num?></td>
        </tr>
        <tr>
            <td valign="top" width="30%"><strong>用户ID</strong><br />
            </td>
            <td><?php echo $userName = ($info->user_id != 0 ) ? get_user_by('id',$info->user_id)->user_login : '游客' ; ?></td>
        </tr>
        <tr>
            <td valign="top" width="30%"><strong>商品名称</strong><br />
            </td>
            <td><?php echo get_the_title($info->post_id)?>
            </td>
        </tr>
         <tr>
            <td valign="top" width="30%"><strong>价格</strong><br />
            </td>
            <td><?php echo $info->order_price ?>
            </td>
        </tr>
        <tr>
            <td valign="top" width="30%"><strong>支付状态</strong><br />
            </td>
            <td><input type="radio" name="result" id="res1" value="1" <?php if($info->status==1) echo "checked";?>/>已支付 
            <input type="radio" name="result" id="res1" value="0" <?php if($info->status==0) echo "checked";?>/>未支付
            </td>
        </tr>
        <tr>
            <td valign="top" width="30%"><strong>支付方式</strong><br />
            </td>
            <td><?php echo $pay_type = ($info->pay_type==1) ? '支付宝' : '微信' ;?></td>
        </tr>
        <tr>
            <td valign="top" width="30%"><strong>下单时间</strong><br />
            </td>
            <td><?php echo date('Y-m-d h:i:s',$info->create_time) ?>
            </td>
        </tr>
        <tr>
            <td valign="top" width="30%"><strong>支付时间</strong><br />
            </td>
            <td><?php echo $times = ($info->pay_time) ? date('Y-m-d h:i:s',$info->pay_time) : '' ; ?>
            </td>
        </tr>
        <tr>
            <td valign="top" width="30%"><strong>支付商户订单号</strong><br />
            </td>
            <td><?php echo $info->pay_num ?>
            </td>
        </tr>
	</table>
    <table> 
    	<tr>
        <td><p class="submit">
            <input type="submit" name="Submit" value="保存设置" class="button-primary"/>
            </p>
        </td>
        </tr> 
    </table>

</form>
</div>
<?php
exit;
}

$year = date("Y");$month = date("m");$day = date("d");$dayBegin = mktime(0, 0, 0, $month, $day, $year); 
$dayEnd = mktime(23, 59, 59, $month, $day, $year);
$beginThismonth=mktime(0,0,0,date('m'),1,date('Y'));
$endThismonth=mktime(23,59,59,date('m'),date('t'),date('Y'));

$total   = $wpdb->get_var("SELECT COUNT(id) FROM $wppay_table_name WHERE order_type =1");
$totalfkdd   = $wpdb->get_var("SELECT COUNT(id) FROM $wppay_table_name WHERE create_time > $beginThismonth AND create_time < $endThismonth AND status =1 ");
$totalfkje   = $wpdb->get_var("SELECT SUM(order_price) FROM $wppay_table_name WHERE create_time > $beginThismonth AND create_time < $endThismonth AND status =1");
$perpage = 20;
$pages = ceil($total / $perpage);
$page=isset($_GET['paged']) ?intval($_GET['paged']) :1;
$offset = $perpage*($page-1);
$list = $wpdb->get_results("SELECT * FROM $wppay_table_name WHERE id ORDER BY create_time DESC limit $offset,$perpage");


// 今日总订单
$jrzdd   = $wpdb->get_var("SELECT COUNT(id) FROM $wppay_table_name WHERE create_time > $dayBegin AND create_time < $dayEnd ");
// 今日付款订单
$jrzfkdd   = $wpdb->get_var("SELECT COUNT(id) FROM $wppay_table_name WHERE create_time > $dayBegin AND create_time < $dayEnd AND status =1");
// 今日收入
$jrzfkddje   = $wpdb->get_var("SELECT SUM(order_price) FROM $wppay_table_name WHERE create_time > $dayBegin AND create_time < $dayEnd AND status =1");
?>

<!-- 默认显示页面 -->
<div class="wrap">
	<h1 class="wp-heading-inline">所有订单</h1>
  	<a href="<?php echo admin_url('admin.php?page=shop_orders_page&action=remosql'); ?>"  onclick="javascript:if(!confirm('确定清理无效订单？')) return false;" class="page-title-action"><span class="layui-badge-dot"></span> 清理无效订单 <span class="layui-badge-dot"></span></a>
    <hr class="wp-header-end">
    <hr>
	<!-- 统计信息 -->
	<div class="layui-fluid">
  		<div class="layui-row layui-col-space15">

	  		<div class="layui-col-sm6 layui-col-md3">
	  			<div class="layui-card">
			        <div class="layui-card-header">总订单<span class="layui-badge layui-bg-blue layuiadmin-badge">全部</span></div>
			        <div class="layui-card-body layuiadmin-card-list">
			        	<p class="layuiadmin-big-font"><?php echo $total ?></p>
			        	<p>总订单已付款 <span class="layuiadmin-span-color"><?php echo $totalfkdd ?></span></p>
			        </div>
			    </div>
			</div>
			<div class="layui-col-sm6 layui-col-md3">
	  			<div class="layui-card">
			        <div class="layui-card-header">总收入<span class="layui-badge layui-bg-blue layuiadmin-badge">全部</span></div>
			        <div class="layui-card-body layuiadmin-card-list">
			        	<p class="layuiadmin-big-font">￥<?php echo $totalfkje ?></p>
			        	<p>总订单已付款 <span class="layuiadmin-span-color"><?php echo $totalfkje ?></span></p>
			        </div>
			    </div>
			</div>
			<div class="layui-col-sm6 layui-col-md3">
	  			<div class="layui-card">
			        <div class="layui-card-header">今日订单<span class="layui-badge layui-bg-cyan layuiadmin-badge">本月</span></div>
			        <div class="layui-card-body layuiadmin-card-list">
			        	<p class="layuiadmin-big-font"><?php echo $jrzdd ?></p>
			        	<p>今日订单已付款 <span class="layuiadmin-span-color"><?php echo $jrzfkdd ?></span></p>
			        </div>
			    </div>
			</div>
			<div class="layui-col-sm6 layui-col-md3">
	  			<div class="layui-card">
			        <div class="layui-card-header">今日收入<span class="layui-badge layui-bg-cyan layuiadmin-badge">本月</span></div>
			        <div class="layui-card-body layuiadmin-card-list">
			        	<p class="layuiadmin-big-font">￥<?php echo $jrzfkddjse = ($jrzfkddje) ? $jrzfkddje : 0 ; ?></p>
			        	<p>今日订单已付款 <span class="layuiadmin-span-color"><?php echo $jrzfkddjse = ($jrzfkddje) ? $jrzfkddje : 0 ; ?></span></p>
			        </div>
			    </div>
			</div>

	  	</div>
	</div><hr>
	<!-- 统计结束 -->


	<table class="wp-list-table widefat fixed striped posts">
		<thead>
			<tr>
				<th class="column-primary">订单号</th>
				<th>用户ID</th>	
				<th>商品名称</th>
				<th>价格</th>
				<th>状态</th>
				<th>支付方式</th>
				<th>下单时间</th>
				<th>操作</th>
			</tr>
		</thead>
		<tbody id="the-list">

	<?php

		if($list) {
			foreach($list as $value){
				echo '<tr id="order-info" data-num="'.$value->order_num.'">';
				echo '<td class="has-row-actions column-primary">'.$value->order_num.'<button type="button" class="toggle-row"><span class="screen-reader-text">显示详情</span></button></td>';
				if($value->user_id){
					echo '<td data-colname="用户ID">'.get_user_by('id',$value->user_id)->user_login.'</td>';
				}else{
					echo '<td data-colname="用户ID">游客</td>';
				}
				if ($value->order_type == 1) {
					echo '<td data-colname="商品名称"><a target="_blank" href='.get_permalink($value->post_id).'>'.get_the_title($value->post_id).'</a></td>';
				}else{
					echo '<td data-colname="商品名称"><span class="layui-badge">开通VIP</span></td>';
				}
				
				echo '<td data-colname="价格">'.$value->order_price.'</td>';
				if ($value->status == 1) {
					echo '<td data-colname="状态"><div class="layui-unselect layui-form-switch layui-form-onswitch" lay-skin="_switch"><em>已付款</em><i></i></div></td>';
				}else{
					echo '<td data-colname="状态"><div class="layui-form-switch layui-checkbox-disbaled layui-disabled" lay-skin="_switch"><em>未付款</em><i></i></div></td>';
				}
				if ($value->pay_type == 1) {
					# 支付宝...
					echo '<td data-colname="支付方式"><span class="layui-badge layui-bg-blue">支付宝</span></td>';
				}else{
					# 微信...
					echo '<td data-colname="支付方式"><span class="layui-badge layui-bg-orange">微信</span></td>';
				}
				
				echo '<td data-colname="下单时间">'.date('Y-m-d h:i:s',$value->create_time).'</td>';
				echo '<td data-colname="操作"><a href="'.admin_url('admin.php?page=shop_orders_page&id='.$value->id).'">操作/详情</a></td>';
				echo "</tr>";
			}
		}
		else{
			echo '<tr><td colspan="6" align="center"><strong>没有订单</strong></td></tr>';
		}
	?>
	</tbody>
	</table>
    <?php echo c_admin_pagenavi($total,$perpage);?>
    <script>
            jQuery(document).ready(function($){

            });
	</script>
</div>
