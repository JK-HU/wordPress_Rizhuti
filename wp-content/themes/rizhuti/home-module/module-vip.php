<?php if (!$paged || $paged===1) { 
$module_home_vip = _hui( 'mo_home_vip' );
?>

<?php if (!$module_home_vip) { ?>
    <h2 style=" text-align: center; margin: 0 auto; padding: 30px; ">请前往后台设置VIP介绍模块！</h2>
<?php }else{ ?>
	<section class="container home3">
		<div class="container">
			<div class="row block-wrapper" style="padding-bottom: 0; padding-top: 30px; margin-bottom: 0; ">
			<?php foreach ($module_home_vip as $key => $value) { ?>
				<!-- <?php if ($value['_title']) { 
					//echo'<div class="block-item"><div class="icon"><img src="' .$value['_img']['url'].' " width="100%"></div><h3 class="content0-title">'.$value['_title'].'</h3><p>'.$value['_desc'].'</p></div>';
				} ?> -->
				<div class="block-item">
				<div class="home-vipbox">
					<div class="icon">
						<img src="<?php echo $value['_img']['url']; ?>">
					</div>
					<h3 class="content0-title"><?php echo $value['_title']; ?></h3>
					<p class="home-price"><i>¥</i><?php echo $value['_price']; ?></p>
					<?php echo $value['_desc']; ?>
					<a href="<?php echo home_url('/user?action=vip') ?>"><p class="vip-bt">开通</p></a>
				</div>
				</div>


			<?php } ?>

			
			</div>
		</div>
	</section>
<?php } ?>

<?php } ?>
