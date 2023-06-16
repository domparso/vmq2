<?php
require '../../application/common.php';
$http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
$url = $http_type . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$ua = $_SERVER['HTTP_USER_AGENT'];

$order_id = isset($_GET['orderId']) && is_numeric($_GET['orderId']) ? $_GET['orderId'] : '';
if($order_id){
    $data = get_curl($http_type.$_SERVER['HTTP_HOST'].'/getOrder',['orderId' => $order_id]);
    $get_order = json_decode($data,true);
    //print_r($get_order);exit();
    if($get_order['data']['payType'] == 1){
        $pay_type_wx = true;
        $pay_type1 = '微信';
    }
    if($get_order['data']['payType'] == 2){
        $pay_type_ali = true;
        $pay_type1 = '支付宝';
    }
    if($get_order['data']['payType'] == 3){
        $pay_type_qq = true;
        $pay_type1 = 'QQ';
    }
    
    $data = get_curl($http_type.$_SERVER['HTTP_HOST'].'/alipayInfo',['orderId' => $order_id]);
    $alipay_info = json_decode($data,true);
    // print_r($alipay_info);
    if($alipay_info['passageway'] == 5 && (stripos($ua,'iphone') !== false || stripos($ua,'ipod') !== false)){
        $alipay_info['transfer'] = 2;
    }
}else{
    exit(json_encode(['code' => 0,'msg' => '云端订单编号为空']));
}
//exit();
?>
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="Content-Language" content="zh-cn">
    <meta name="apple-mobile-web-app-capable" content="no" />
    <meta name="apple-touch-fullscreen" content="yes" />
    <meta name="format-detection" content="telephone=no,email=no" />
    <meta name="apple-mobile-web-app-status-bar-style" content="white">
    <meta name="renderer" content="webkit" />
    <meta name="force-rendering" content="webkit" />
    <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1" />
    <meta http-equiv="Expires" content="0">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Cache-control" content="no-cache">
    <meta http-equiv="Cache" content="no-cache">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>扫码支付</title>
    <link href="pay.css" rel="stylesheet" media="screen">
    <style>
    <?php if($pay_type_ali){?>
    #orderbody .time-item strong{
        background: #00A0E8;
    }
    .mod-ct .tip .ico-scan{
        background-image: url(ali-pay.png);
    }
    .mod-ct .detail .arrow .ico-arrow{
        background-image: url(ali-pay.png);
    }
    <?php }?>
    </style>
</head>

<body>
    <div class="body" id="body">
        <h1 class="mod-title">
            <?php if($pay_type_wx){?>
            <span class="ico_log ico-1"></span>
            <?php }?>
            <?php if($pay_type_ali){?>
            <span class="ico_log ico-2"></span>
            <?php }?>
            <?php if($pay_type_qq){?>
            <span class="ico_log ico-3"></span>
            <?php }?>
        </h1>
        <div class="mod-ct">
            <div class="order">
            </div>
            <div class="amount" id="timeOut" style="font-size: 20px;color: red;display: none;">
                <p>订单已过期，请您返回网站重新发起支付</p><br>
            </div>
            <div id="orderbody">
                <div class="amount" id="money">￥<?php echo $get_order['data']['reallyPrice'];?></div>
                <div class="qrcode-img-wrapper" data-role="qrPayImgWrapper">
                    <div data-role="qrPayImg" class="qrcode-img-area">
                        <div class="ui-loading qrcode-loading" data-role="qrPayImgLoading" style="display: none;">加载中</div>
                        <div style="position: relative;display: inline-block;">
                            <?php if($alipay_info['alipayId'] && $pay_type_ali){?>
                            <?php if($alipay_info['confirm'] == 1){?>
                            <img id='show-qrcode' alt="加载中..." src="/enQrcode?url=<?php echo urlencode($http_type.$_SERVER['HTTP_HOST'].'/payPage/go_alipay.php?orderId='.$order_id);?>" width="210" height="210">
                            <?php }else if($alipay_info['transfer'] == 1){?>
                            <img id='show-qrcode' alt="加载中..." src="/enQrcode?url=https://render.alipay.com/p/s/i?scheme=<?php echo urlencode("alipays://platformapi/startapp?appId=09999988&actionType=toAccount&goBack=NO&amount={$get_order['data']['reallyPrice']}&userId={$alipay_info['alipayId']}&memo={$get_order['data']['id']}");?>" width="210" height="210">
                            <?php }else if($alipay_info['transfer'] == 2){?>
                            <img id='show-qrcode' alt="加载中..." src="/enQrcode?url=https://render.alipay.com/p/s/i?scheme=<?php echo urlencode("alipays://platformapi/startapp?appId=20000123&actionType=scan&biz_data={\"s\": \"money\",\"u\": \"{$alipay_info['alipayId']}\",\"a\": \"{$get_order['data']['reallyPrice']}\",\"m\":\"{$get_order['data']['id']}\"}");?>" width="210" height="210">
                            <?php }else{?>
                            <img id='show-qrcode' alt="加载中..." src="/enQrcode?url=<?php echo urlencode($get_order['data']['payUrl']);?>" width="210" height="210" style="display: block;">
                            <?php }?>
                            <?php }else{?>
                            <img id='show-qrcode' alt="加载中..." src="/enQrcode?url=<?php echo urlencode($get_order['data']['payUrl']);?>" width="210" height="210" style="display: block;">
                            <?php }?>
                            <?php if($pay_type_wx){?>
                            <img onclick="$(this).hide()" src="use_1.png" style="position: absolute;top: 50%;left: 50%;width:32px;height:32px;margin-left: -16px;margin-top: -16px">
                             <?php }?>
                             <?php if($pay_type_ali){?>
                            <img onclick="$(this).hide()" src="use_2.png" style="position: absolute;top: 50%;left: 50%;width:32px;height:32px;margin-left: -16px;margin-top: -16px">
                             <?php }?>
                             <?php if($pay_type_qq){?>
                            <img onclick="$(this).hide()" src="use_3.png" style="position: absolute;top: 50%;left: 50%;width:32px;height:32px;margin-left: -16px;margin-top: -16px">
                             <?php }?>
                        </div>
                    </div>
                </div>
                <div class="time-item">
                    <div class="time-item" id="msg">
                        <h1>
                            <span style="color:red"><?php if($get_order['data']['price'] != $get_order['data']['reallyPrice']){echo $get_order['data']['price'].'元已被占用，';}?>请务必付款<?php echo $get_order['data']['reallyPrice'];?>元，否则会支付失败</span><br>
                        </h1>
                    </div>
                    <strong id="hour_show">0时</strong>
                    <strong id="minute_show">0分</strong>
                    <strong id="second_show">0秒</strong>
                </div>
                <?php if($pay_type_wx || $pay_type_qq){?>
                <div class="open_app">
                    <a id="wxpay" class="btn-open-app picture">点击保存二维码</a><br><br>
                </div>
                <?php }?>
                <?php if($pay_type_ali && stripos($ua,'alipay') === false){?>
                <div class="open_app">
                    <?php echo stripos($ua,'baidu') === false ? '<a id="alipay" class="btn-open-app">打开支付宝APP继续付款</a>' : '<a id="alipay" class="btn-open-app picture">点击保存二维码</a>';?><br><br>
                </div>
                <?php }?>
                <div style="font-size: 18px;margin-top: 18px;">
                    <span class="mobileTip"><?php if($pay_type_wx || stripos($ua,'baidu') !== false || $pay_type_qq){echo '请先点击保存二维码，';}if(!$alipay_info['alipayId'] || !$alipay_info['transfer'] || $pay_type_wx || $pay_type_qq){echo '点击之后金额会自动复制，然后在输入金额里粘贴即可，';}?></span><?php if($pay_type_ali && isMobile()){echo '<span style="color: red;">如果提示已停止访问，关闭支付宝再重试或者截图扫码</span>，';}?>支付后页面没跳转点击一下-><a href="javascript:;" id="submitBd">提交补单</a>
                </div>
                <div class="tip">
                    <div class="ico-scan"></div>
                    <div class="tip-text">
                        <p>请使用<?php echo $pay_type1;?>扫一扫</p>
                        <?php if($get_order['data']['isAuto'] == 0 || ($alipay_info['alipayId'] && $alipay_info['transfer'] && $pay_type_ali)){?>
                        <p>扫描二维码完成支付</p>
                        <?php }else{?>
                        <p>扫码后输入金额支付</p>
                        <?php }?>
                    </div>
                </div>
                <div class="detail" id="orderDetail">
                    <dl class="detail-ct" id="desc" style="display: none;">
                        <dt>商品信息：</dt>
                        <dd><?php echo $get_order['data']['param'];?></dd>
                        <dt>金额：</dt>
                        <dd><?php echo $get_order['data']['price'];?></dd>
                        <dt>商户订单：</dt>
                        <dd><?php echo $get_order['data']['payId'];?></dd>
                        <dt>创建时间：</dt>
                        <dd><?php echo date('Y-m-d H:i:s',$get_order['data']['date']);?></dd>
                        <dt>状态：</dt>
                        <dd>等待支付</dd>
                    </dl>
                    <a href="javascript:void(0)" class="arrow" onclick="aaa()"><i class="ico-arrow"></i></a>
                </div>
            </div>
            <div class="tip-text">
            </div>
        </div>
        <div class="foot">
            <div class="inner">
                <p>手机用户可保存上方二维码到手机中</p>
                <p>在<?php echo $pay_type1;?>扫一扫中选择“相册”即可</p>
            </div>
        </div>
    </div>
    <div class="copyRight">
    </div>
    <script src="/js/jquery.min.js"></script>
    <script src="/js/clipboard.min.js"></script>
    <script src="/layer/layer.js"></script>
    <script>
    function aaa() {
        if ($('#orderDetail').hasClass('detail-open')) {
            $('#orderDetail .detail-ct').slideUp(500, function() {
                $('#orderDetail').removeClass('detail-open');
            });
        } else {
            $('#orderDetail .detail-ct').slideDown(500, function() {
                $('#orderDetail').addClass('detail-open');
            });
        }
    }

    var myTimer;

    function timer(intDiff) {
        var i = 0;
        i++;
        var day = 0,
            hour = 0,
            minute = 0,
            second = 0; //时间默认值
        if (intDiff > 0) {
            day = Math.floor(intDiff / (60 * 60 * 24));
            hour = Math.floor(intDiff / (60 * 60)) - (day * 24);
            minute = Math.floor(intDiff / 60) - (day * 24 * 60) - (hour * 60);
            second = Math.floor(intDiff) - (day * 24 * 60 * 60) - (hour * 60 * 60) - (minute * 60);
        }
        if (minute <= 9) minute = '0' + minute;
        if (second <= 9) second = '0' + second;
        $('#hour_show').html('<s id="h"></s>' + hour + '时');
        $('#minute_show').html('<s></s>' + minute + '分');
        $('#second_show').html('<s></s>' + second + '秒');
        if (hour <= 0 && minute <= 0 && second <= 0) {
            qrcode_timeout()
            clearInterval(myTimer);

        }
        intDiff--;

        myTimer = window.setInterval(function() {
            i++;
            var day = 0,
                hour = 0,
                minute = 0,
                second = 0; //时间默认值
            if (intDiff > 0) {
                day = Math.floor(intDiff / (60 * 60 * 24));
                hour = Math.floor(intDiff / (60 * 60)) - (day * 24);
                minute = Math.floor(intDiff / 60) - (day * 24 * 60) - (hour * 60);
                second = Math.floor(intDiff) - (day * 24 * 60 * 60) - (hour * 60 * 60) - (minute * 60);
            }
            if (minute <= 9) minute = '0' + minute;
            if (second <= 9) second = '0' + second;
            $('#hour_show').html('<s id="h"></s>' + hour + '时');
            $('#minute_show').html('<s></s>' + minute + '分');
            $('#second_show').html('<s></s>' + second + '秒');
            if (hour <= 0 && minute <= 0 && second <= 0) {
                qrcode_timeout()
                clearInterval(myTimer);

            }
            intDiff--;
        }, 1000);
    }

    function qrcode_timeout() {
        document.getElementById("orderbody").style.display = "none";
        document.getElementById("timeOut").style.display = "";
    }
    
    <?php if($get_order['code'] == 1){?>
        var time = new Date().getTime() - <?php echo $get_order['data']['date'];?> * 1000;
        time = time / 1000;
        time = <?php echo $get_order['data']['timeOut'];?> * 60 - time;

        if (<?php echo $get_order['data']['state'];?> == -1) {
            time = 0;
        }
        timer(time);
        check();
    <?php }else{?>
        timer(0);
    <?php }?>
    
    function check() {
        $.post("../checkOrder", "orderId=<?php echo $order_id;?>", function(data) {
            // console.log(data);
            if (data.code == 1) {
                userAgent = navigator.userAgent;
                if(userAgent.indexOf('Alipay') != -1){
                    alert(data.msg);
                    window.open("","_self").close()
                }else{
                    location.href = data.data;
                    // if(location.href != data.data){
                    //     setInterval(function() {
                    //         location.href = data.data;
                    //     },1000)
                    // }
                }
            } else {
                if (data.data == "订单已过期") {
                    intDiff = 0;
                } else {
                    setTimeout("check()", 1000);
                }
            }
        })
    }
    
    function copyMoney(cthis){
        money = '<?php echo $get_order['data']['reallyPrice'];?>';
        clipboard = new ClipboardJS(cthis, {
            // 点击btn按钮，直接通过text直接返回复印的内容
           text: function() {
               return money;
           }
       });
       clipboard.on('error', function(e) {
           layer.msg('复制金额失败，请手动复制',{time: 2000,icon: 2});
       });
      cthis.click(); //解决clipboard二次点击生效问题
    }

    <?php if($pay_type_ali && $alipay_info['alipayId'] && $alipay_info['transfer'] && $get_order['data']['state'] == 0 && isMobile()){?>
        $('body').on('click','#alipay',function(){
            <?php if($alipay_info['confirm'] == 1){?>
            location.href = 'alipays://platformapi/startapp?appId=20000067&url=<?php echo urlencode($http_type.$_SERVER['HTTP_HOST'].'/payPage/go_alipay.php?orderId='.$order_id);?>';
            <?php }else{?>
            url = $("#show-qrcode").attr('src').replace(/\/enQrcode\?url=https:\/\/render\.alipay\.com\/p\/s\/i\?scheme=/g,"");
            location.href = "alipays://platformapi/startapp?appId=20000067&url="+url;
            <?php }?>
        });
    <?php }?>
    <?php if($pay_type_ali && $alipay_info['alipayId'] && $alipay_info['transfer'] && stripos($ua,'alipay') !== false && $get_order['data']['state'] == 0){?>
        userAgent = navigator.userAgent;
        if(userAgent.indexOf('Alipay') != -1){
            money = '<?php echo $get_order['data']['reallyPrice'];?>';
            if(money != ''){
                // location.href = 'alipays://platformapi/startapp?appId=20000123&actionType=scan&biz_data={"s": "money","u": "<?php echo $alipay_info['ali_user_id'];?>","a": "'+money+'","m":"<?php echo $get_order['data']['payId'];?>"}';
                // location.href = 'alipays://platformapi/startapp?appId=09999988&actionType=toAccount&goBack=NO&amount='+money+'&userId=<?php echo $alipay_info['ali_user_id'];?>&memo=<?php echo $get_order['data']['payId'];?>';
            }
        }
     <?php }else{?>
        <?php if($pay_type_ali && (!$alipay_info['alipayId'] || !$alipay_info['transfer']) && $get_order['data']['state'] == 0 && isMobile()){?>
        $('body').on('click','#alipay',function() {
            copyMoney(this);
            location.href = 'alipayqr://platformapi/startapp?saId=10000007&qrcode=<?php echo urlencode($get_order['data']['payUrl']);?>';
        })
        <?php }?>
    <?php }?>
    <?php if($get_order['data']['state'] == 0 && isMobile()){?>
    $('body').on('click','.picture',function(){
        copyMoney(this);
        if(window.picture != 1){
            savePicture($('#show-qrcode').attr('src'));
            window.picture = 1;
        }
    })
    <?php }?>
    <?php if($get_order['data']['state'] == 0 && isMobile()){?>
    var triggerEvent = "touchstart";
    function savePicture(Url){
        var blob=new Blob([''], {type:'application/octet-stream'});
        var url = URL.createObjectURL(blob);
        var a = document.createElement('a');
        a.href = Url;
        a.download = Url.replace(/(.*\/)*([^.]+.*)/ig,"$2").split("?")[0];
        var e = document.createEvent('MouseEvents');
        e.initMouseEvent('click', true, false, window, 0, 0, 0, 0, 0, false, false, false, false, 0, null);
        a.dispatchEvent(e);
        URL.revokeObjectURL(url);
    }
    <?php }?>
    <?php if(isMobile()){?>
        $('.open_app').show();
        $('.mobileTip').show();
    <?php }?>
    
    $('#submitBd').on('click',function(){
        layer.prompt({title: '请输入你的邮箱，不填输入0', formType: 3}, function(email, index){
            layer.close(index);
            // console.log(email);
            layer.load(3);
            $.post('/submitBd',{payId: '<?php echo $get_order['data']['payId'];?>',email: email},function(res){
                if(res.code == 1){
                    layer.msg("提交补单成功，等待核实中",{time: 2000,icon: 1});
                }else{
                    layer.msg(res.msg,{time: 2000,icon: 2});
                }
                layer.closeAll('loading');
            })
        });
    })
    <?php if($get_order['data']['state'] == 0 && stripos($ua,'alipay') === false && $alipay_info['voice'] == 1){?>
        text = '你好，你本次订单实际金额为<?php echo $get_order['data']['reallyPrice'];?>元，请按照金额进行付款。';
        $('body').append(`<audio autoplay>
          <source src="https://tts.youdao.com/fanyivoice?word=${text}&le=zh&keyfrom=speaker-target" type="audio/mpeg">
        您的浏览器不支持 audio 元素。
        </audio>`);
    <?php }?>
    </script>
</body>

</html>