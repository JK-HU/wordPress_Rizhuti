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

					<!-- <label for="type1" class="radio-box v1">
						<p><img src="<?php //echo get_stylesheet_directory_uri() . '/img/vp1.png' ?>"></p>
						<div class="tips-box"><span>包月VIP</span></div>
						<div class="dec"><?php //echo _hui('vip_options')['vip_price_31_desc']; ?></div>
					    <input type="radio" checked="checked" name="order_type" value="2" id="type1" />
					    <span class="radio-style">包月￥<?php //echo _hui('vip_options')['vip_price_31']; ?></span>
					</label> -->
					
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
					                    <strong>Q：视频制作大叔资源库是什么？</strong>
					                </div>
					                <div class="bd">A：视频制作大叔资源库，是一个汇聚全网素材、软件、插件、教程、预设、模板等资源的学习社群，致力于打造超优质的传媒类资源共享平台。 资源库不仅对国内外各大网站内最优质的部分进行整合，同时也会推出大量优质的原创内容。在这里你将不仅可以免费获取各种传媒类优质资源，
还能和志同道合的小伙伴交流讨论，更有行业大神带你飞，让你分分钟晋升行业精英！走上人生巅峰！</div>
					            </li>
					            <li class="item">
					                <div class="hd">
					                    <strong>Q：如何加入视频制作大叔资源库？</strong>
					                </div>
					                <div class="bd">A：可以在网站和视频制作大叔微店购买，也可以直接转给大叔哦；（大叔微信：13761315792）</div>
					            </li>
					            <li class="item">
					                <div class="hd">
					                    <strong>Q：资源怎么获取？</strong>
					                </div>
					                <div class="bd">A: 加入资源库后，即可通过后台验证，注册为网站会员，所有资源都可以查看，并通过百度云盘下载链接下载；</div>
					            </li>
					            <li class="item">
					                <div class="hd">
					                    <strong>Q：资源是永久的么？</strong>
					                </div>
					                <div class="bd">A: 资源是不仅可以永久免费获取，而且资源还是源源不断免费更新的哦。</div>
					            </li>
					            <li class="item">
					                <div class="hd">
					                    <strong>Q：为什么要收费？</strong>
					                </div>
					                <div class="bd">A: 因为建立这样一个庞大资源共享平台，需要不断购买整合大量的资源，而这是需要大量的资金和精力的。资源库不是以营利为目的，收费的费用仅作为赞助我们，不是资源本身的价格！</a></div>
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
