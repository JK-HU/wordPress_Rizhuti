<?php 
global $current_user;
?>
<div class="info-wrap">
	<div class="user-usermeta-vip">
		<div class="vip-row">
				<div class="vip-item">
					<h2><i class="iconfont">&#xe66b;</i> <span><?php echo $current_user->user_login;?></span><i class="iconfont">&#xe63f;</i> <span><?php echo vip_type_name();?></span> <i class="iconfont">&#xe61c;</i> <span><span><?php echo vip_time();?> 到期</span></h2>
				</div>
				<div class="vip-item form">

					<label for="type1" class="radio-box v1">
						<p><img src="<?php echo get_stylesheet_directory_uri() . '/img/vp1.png' ?>"></p>
						<div class="tips-box"><span>包月VIP</span></div>
						<div class="dec"><?php echo _hui('vip_options')['vip_price_31_desc']; ?></div>
					    <input type="radio" checked="checked" name="order_type" value="2" id="type1" />
					    <span class="radio-style">包月￥<?php echo _hui('vip_options')['vip_price_31']; ?></span>
					</label>
					<label for="type2" class="radio-box v2">
						<p><img src="<?php echo get_stylesheet_directory_uri() . '/img/vp2.png' ?>"></p>
						<div class="tips-box"><span>包年VIP</span></div>
						<div class="dec"><?php echo _hui('vip_options')['vip_price_365_desc']; ?></div>
					    <input type="radio" name="order_type" value="3" id="type2" />
					    <span class="radio-style">包年￥<?php echo _hui('vip_options')['vip_price_365']; ?></span>
					</label>
					<label for="type3" class="radio-box v3">
						<p><img src="<?php echo get_stylesheet_directory_uri() . '/img/vp3.png' ?>"></p>
						<div class="tips-box"><span>终身VIP</span></div>
						<div class="dec"><?php echo _hui('vip_options')['vip_price_3600_desc']; ?></div>
					    <input type="radio" name="order_type" value="4" id="type3" />
					    <span class="radio-style">终身￥<?php echo _hui('vip_options')['vip_price_3600']; ?></span>
					</label>

				</div>
              
              	<div class="vip-item">
					<button class="btn btn-primary" href="javascript:;" id="pay-vip">立即开通</button>
					<p style="margin-top: 1.8rem;color: #c5c5c5;">开通的等级大于当前等级，到期日期会自动延长</p>
				</div>
				<div class="vip-item">
					<div class="sc sc-faq">
					    <h3 class="sc-hd">
					        <strong>常见问题</strong>
					        <span>FAQ</span>
					    </h3>

					    <div class="sc-bd">
					        <ul class="faq-list" id="R_faqList">
					        	<li class="item">
					                <div class="hd">
					                    <strong>开通VIP的十万个好处？</strong>
					                </div>
					                <div class="bd">开通VIP第99999个好处就是有排面！</div>
					            </li>
					            <li class="item">
					                <div class="hd">
					                    <strong>VIP资源需要单独购买吗？</strong>
					                </div>
					                <div class="bd">本站所有资源，针对不同等级VIP免，可直接下载。</div>
					            </li>
					            <li class="item">
					                <div class="hd">
					                    <strong>VIP会员是否无限次下载资源？</strong>
					                </div>
					                <div class="bd">在遵守VIP会员协议前提下，VIP会员在会员有效期内可以任意下载所有免费和VIP资源。</div>
					            </li>
					            <li class="item">
					                <div class="hd">
					                    <strong>是否可以与他人分享VIP会员账号？</strong>
					                </div>
					                <div class="bd">一个VIP账号仅限一个人使用，禁止与他人分享账号。</div>
					            </li>
					            <li class="item">
					                <div class="hd">
					                    <strong>是否可以申请退款？</strong>
					                </div>
					                <div class="bd">VIP会员属于虚拟服务，付款后不能够申请退款。如付钱前有任何疑问，联系站长处理</a></div>
					            </li>
					        </ul>
					    </div>
					</div>
				</div>
			<script>
			<?php
				if (vip_type() == 0) {$this_types = 0;
				} elseif(vip_type() == 31) {$this_types = 2;
				}elseif(vip_type() == 365) {$this_types = 3;
				}elseif(vip_type() == 3600) {$this_types = 4;}
			?>
			var this_types = "<?php echo $this_types ?>"; 
			$("label.radio-box").on("click", function() {

			$("label.radio-box").css("background-color","rgb(255, 255, 255)");
				var type = $("input[name='order_type']:checked").val();
				var cbtn = $("button.btn-primary");
				var type = $("input[name='order_type']:checked").val();
				cbtn.removeAttr("disabled");
				if (this_types==0) {
					cbtn.text('立即开通');
				}else if(this_types==4){
					cbtn.text('等级已达到最高');
					cbtn.attr("disabled","true");
				}else if(type == this_types){
					cbtn.text('立即续费');
				}else if(type > this_types){
					cbtn.text('立即升级');
				}else if(type < this_types){
					cbtn.text('续费1个月年费会员');
				}else{
					cbtn.text('立即开通');
					
				}

				if($(this).css("background-color")=="rgb(255, 255, 255)"){
		            $(this).css("background-color","rgb(226, 241, 255)")
		        }else if($(this).css("background-color")=="rgb(226, 241, 255)"){
		       		$(this).css("background-color","#FFFFFF");
		       	}

			});
			$("#R_faqList .item").on("click", function() {
			     $(this).toggleClass("active").siblings().removeClass("active")
			});

			</script>
          
		</div>
	</div>

</div>