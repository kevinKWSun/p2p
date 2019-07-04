<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>用户登录-伽满优</title>
    <meta http-equiv="Content-Language" content="zh-cn" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <meta name="viewport" content="width=device-width, maximum-scale=1.0, initial-scale=1.0,initial-scale=1.0,user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="Keywords" content="用户登录-伽满优,车贷理财,车辆抵押,P2P投资理财,投资理财公司,短期理财,P2P投资理财平台" />
	<meta name="Description" content="用户登录-伽满优,通过公开透明的规范操作,平台为投资理财人士提供收益合理、安全可靠、高效灵活的车贷理财产品。" />
    <link href="/images/default.css" rel="stylesheet" type="text/css" />
	<link href="/images/index.css" rel="stylesheet" type="text/css" />
    <link href="/src/css/layui.css" rel="stylesheet" />
    <script src="/src/layui.js"></script>
</head>
<body>
<?php include("top.php") ?>
<div class="cent">
    <div class="dqwz"><i class="icon"></i><a href="/">首页</a> >   登录</div>
    <div class="dl">
		<img src="/images/dl_tu.jpg" />
		<div class="sx"></div>
		<div class="dl_nr"><br><br><br><br>
			<h3><span>没有账户？ <a href="/suny/reg.html">注册</a></span>用户登录</h3><br>
			<ul>
				<form id="loginForm" onsubmit="return false;">
					<li style='display:none;color:red;height:12px;line-height:12px;'><font></font></li>
					<li><input name="user_name" type="text" class="in1 dl_biao1" placeholder="手机号" /></li>
					<li>
						<input name="tcode" type="text" placeholder="图形验证码" maxlength="6" class="in1"  style="float: left;" />
						<b></b>
					</li>
					<li>
						<input name="user_pass" type="password" placeholder="请输入密码或验证码" class="in1 in2" />
						<input id="oncode" type="button" class="hqyzm_biao" value="获取验证码" />
					<li><input type="submit" value="登录" id="loginSum" class="btnlogin" /></li>
				</form>
			    <!--li><a href="javascript:;" id='find' class="a1">找回密码？</a></li-->
			</ul>
		</div> 
	</div>
</div>
<?php include("foot.php") ?>
<script>
	layui.use(['layer', 'form'], function () {
		var $ = layui.$
		, layer = layui.layer
		, form = layui.form;
		$('input[name=user_name]').focus();
		$('#loginSum').on('click',function(){
			$.post("/suny/login.html", $('#loginForm').serialize(), 
				function(r){
					if(r.state == 1){
						top.location.href = r.url;
					} else {
						$('font').html(r.message);
						$('font').parent().show();
						return;
					}
			}, 'json');
		});
		$('#loginSum').submit();
		$('li b').load('/suny/captcha.html');
		$('li b').click(function(){
			$(this).load('/suny/captcha.html?t=<?php echo time();?>');
		});
		$('#oncode').on('click',function(){
			$.post('/suny/sends.html', $('#loginForm').serialize(), function(s){
				$('font').html(s.message);
				$('font').parent().show();
				if(s.state == 1){
					sendMessage();
				}
				return;
			}, 'json');
		});
		var InterValObj; 
		var count = 60; 
		var curCount;
		function sendMessage() {
			curCount = count;
			$("#oncode").prop("disabled",false);
			$("#oncode").val(curCount + "(S)").prop("disabled",true);
			InterValObj = window.setInterval(SetRemainTime, 1000); 
		}
		//timer处理函数
		function SetRemainTime() {
			if (curCount == 0) {                
				window.clearInterval(InterValObj);
				$("#oncode").prop("disabled", false);
				$("#oncode").val("重新发送").prop("disabled", false);
			}
			else {
				curCount--;
				$("#oncode").prop("disabled", false);
				$("#oncode").val(curCount + "(S)").prop("disabled",true);
			}
		}
		$('#find').on('click',function(){
			layer.open({
				type: 2,
				title: '密码找回',
				shadeClose: true,
				shade: 0.5,
				maxmin: true,
				area: ['800px', '500px'],
				fixed: false,
				content: 'https://www.jiamanu.com/SiteAgreement.aspx?configId=11'
			});
		});
	});
</script>
</body>
</html>