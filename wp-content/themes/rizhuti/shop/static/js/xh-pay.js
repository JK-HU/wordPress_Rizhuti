
jQuery(function($){
    var payment_alipay_html= '';
    var payment_weixin_html= '<div class="pay-item" data-id="3" style="border-bottom:0;"><i class="weixinpay"></i><span>微信支付</span></div>';

    //免签专用js，为了懒惰处理js 直接换文件，不判断*_*
    // CONET PAY This POST Alipay FOR POST
    $("#pay-loader").on("click",function(){
        var post_id = $(this).data("post");
        var order_type = 1;
        // 获取支付方式
        popup.showCustomModal({
            template: "PayMethod",  // AlipayQrcode; WeixinpayQrcode ;Popup；PayMethod
            layerClose: 1,
            data: {html: payment_alipay_html+payment_weixin_html}
        });

        $("#customModal .modal .pay-button-box .pay-item").on("click",function(){
            var pay_type = $(this).attr('data-id');
            popup.hideModal('customModal');
            popup.showToast({
                type: "it",
                text: "订单创建中...",
                time: 4000
            });
            senData(pay_type,post_id,order_type);
        });
        return false;
    });

    // CONET PAY This VIP Alipay for USER PAGE
    $("#pay-vip").on("click",function(){
      var post_id = 0; 
      var order_type = $("input[name='order_type']:checked").val();
      // 获取支付方式
      popup.showCustomModal({
          template: "PayMethod",  // AlipayQrcode; WeixinpayQrcode ;Popup；PayMethod
          layerClose: 1,
          data: {html: payment_alipay_html+payment_weixin_html}
      });
      $("#customModal .modal .pay-button-box .pay-item").on("click",function(){
          var pay_type = $(this).attr('data-id');
          // $(this).html('获取支付信息...');
          popup.hideModal('customModal');
          popup.showToast({
              type: "it",
              text: "订单创建中...",
              time: 4000
          });
          senData(pay_type,post_id,order_type);
      });
      return false;
    });
    
    // 请求订单 paytype ; postid
    function senData(pay_type,post_id,order_type){
      
      action = "xhpay";
      $.post(wppay_ajax_url, {"action": action,"post_id": post_id,"order_type": order_type,"pay_type": pay_type},function (result) {
        // if start
        if( result.status == 200 ){
            popup.hideToast();
            window.location.href=result.msg
        }else{
            popup.showToast({
                type: "text",
                text: result.msg
            });
        }
        // end
      },'json');
    }

});