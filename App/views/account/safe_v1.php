<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>安全中心-伽满优</title>
    <meta http-equiv="Content-Language" content="zh-cn" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <meta name="viewport" content="width=device-width, maximum-scale=1.0, initial-scale=1.0,initial-scale=1.0,user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="Keywords" content="安全中心-伽满优,车贷理财,车辆抵押,P2P投资理财,投资理财公司,短期理财,P2P投资理财平台" />
	<meta name="Description" content="安全中心-伽满优,通过公开透明的规范操作,平台为投资理财人士提供收益合理、安全可靠、高效灵活的车贷理财产品。" />
    <link href="/images/default.css" rel="stylesheet" type="text/css" />
	<link href="/images/index.css" rel="stylesheet" type="text/css" />
	<link href="/images/new/user-info.css" rel="stylesheet" type="text/css" />
    <link href="/src/css/layui.css" rel="stylesheet" />
    <script src="/src/layui.js"></script>
	<script src="/images/jquery-1.7.2.min.js"></script>
</head>
<body>
<?php include("top.php") ?>
 <div class="cent_v2">
	<div class="zhzx_v2">
		<div class="zhzx_l_v2">
			<?php include("left_v1.php") ?>
		</div>
		<div class="zhzx_r_v2">
			<h2>安全中心</h2>
			<div class="aqgl">
				<div class="aqgl_nr_v2">
					<ul>
						<li><i class="zhgl_biao1_v2"></i>
							<h4>登录密码<br /><span>用于保护帐号信息和登录安全</span></h4>
							<a href="javascript:;" id='modify'>修改</a> </li>
							<div class="jyjl_top" style='display:none;background: #fff5f2;'>
								<div class="jyjl_nr" style='padding:0 15px;'>
									<input class="in1" type="password" placeholder="输入原有登录密码" name="ypass" style='width:96%;margin-top:18px;height:35px;line-height:35px;'>
									<input class="in1" type="password" placeholder="输入新登录密码(6位以上字母和数字组合)" name="newpass" style='width:96%;margin-top:18px;height:35px;line-height:35px;'>
									<input class="in1" type="password" placeholder="确认新密码" name="pass" style='width:96%;height:35px;line-height:35px;margin-top:18px;'>
									<li style='padding:15px 0 0 0;'>
										<center>
											<a class="hqyzm_biao hide" href="javascript:;">取消</a>
											<a class="hqyzm_biao do" href="javascript:;">确定</a>
										</center>
									</li>
								</div>
							</div>
						<li><i class="zhgl_biao2_v2"></i>
							<h4>收短信手机号<br /><span>用于支付接口短信验证</span></h4>
							<?php if(IS_BANK){?><a href="javascript:;" id='modifytel'>修改</a><?php }?>
						</li>
						<li><i class="zhgl_biao3_v2"></i>
							<?php if(IS_COMPANY) { ?>
								<h4>实名认证<br /><span>响应国家政策需要，购买理财产品需要实名信息验证</span></h4>
								<?php if(IS_CHECK){?><a href="javascript:;">已认证</a><?php }else{?><a href="javascript:;">等待认证</a><?php }?>
							<?php } else { ?>
								<h4>实名认证<br /><span>响应国家政策需要，购买理财产品需要实名信息验证</span></h4>
								<?php if(IS_CHECK){?><a href="javascript:;">已认证</a><?php }else{?><a href="javascript:;" id="js-authentication">去认证</a><?php }?>
							<?php } ?>
							
						</li>
						<li><i class="zhgl_biao4_v2"></i>
							<?php if(IS_COMPANY) { ?>
								<h4 style="width:360px;">提现（支付）密码<br /><span>用于提现账户内余额，或支付购买理财产品</span></h4>
								<?php if(IS_CHECK){?><a href="javascript:;" id="js-reset">重置</a><a href="javascript:;" id="js-repass" style="margin-left:6px;">修改</a><?php }?>
							<?php } else { ?>
								<h4 style="width:360px;">提现（支付）密码<br /><span>用于提现账户内余额，或支付购买理财产品</span></h4>
								<?php if(IS_BANK){?><a href="javascript:;" id="js-reset">重置</a><a href="javascript:;" id="js-repass" style="margin-left:6px;">修改</a><?php }?>
							<?php } ?>
						</li>
						<?php if(IS_COMPANY) { ?>
							<li><i class="icon zhgl_biao5"></i>
								<h4>注销申请<br /><span>用于注销在商户平台的用户信息</span></h4>
								<?php if(IS_CHECK){?><a href="javascript:;" id="js-cancel">注销</a><?php }?>
							</li>

						<?php } ?>
						
					</ul>
				</div>
				<img src="/images/new/image/img-shiming.png" />
				<div class="clear"></div>
			</div>
			<div class="wxts_v2">
				<h3>
					温馨提示：</h3>
				<p>
					1、为了您的账户和资金安全，变更手机号，需要在伽满优的第三方资金托管平台后台进行修改。<br>2、如需协助，请联系伽满优工作人员。</p>
			</div>
		</div>
	</div>
</div>

<?php include("foot.php") ?>
<script>
	layui.use(['layer', 'form'], function () {
		var $ = layui.$
		, layer = layui.layer
		, form = layui.form;
		$('#modify').on('click',function(s){
			$('.jyjl_top').show();
		});
		$('.hide').on('click',function(s){
			$('.jyjl_top').hide();
		});
		$('.do').on('click',function(s){
			$.post('/safe/mfpassword',{ypass:$('input[name=ypass]').val(),newpass:$('input[name=newpass]').val(),pass:$('input[name=pass]').val()},function(p){
				layer.msg("<font color='#FFF'>"+p.message+"</font>");
				if(p.state == 1){
					$('.jyjl_top').hide();
				}
			}, 'json');
		});
		$('#js-authentication').on('click',function(){
			//location.href = '/account/authentication.html';
			layer.open({
				type: 2,
				title: '认证',
				shade: 0.1,
				maxmin: true,
				area: ['450px', '400px'],
				fixed: true,
				content: '/safe/autherui.html'
			});
		});
		$('#modifytel').on('click',function() {
			layer.open({
				type: 2,
				title: '修改注册手机号',
				shade: 0.1,
				maxmin: true,
				area: ['35%', '45%'],
				fixed: true,
				content: '/safe/modifytel.html'
			});
		});
		$('#js-reset').on('click',function(){
			location.href = '/account/reset_trade_password.html';
			// layer.open({
				// type: 2,
				// title: '重置交易密码',
				// shade: 0.1,
				// maxmin: true,
				// area: ['65%', '80%'],
				// fixed: true,
				// content: '/account/reset_trade_password.html'
			// });
		});
		$('#js-repass').on('click',function(){
			location.href = '/account/repass_trade_password.html';
		});
		$('#js-cancel').on('click', function() {
			layer.open({
				type: 2,
				title: '注销',
				shade: 0.1,
				maxmin: true,
				area: ['65%', '70%'],
				fixed: true,
				content: '/account/cancel.html'
			});
		});
	});
</script>
</body>
</html>