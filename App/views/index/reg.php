<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>用户注册-伽满优</title>
    <meta http-equiv="Content-Language" content="zh-cn" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <meta name="viewport" content="width=device-width, maximum-scale=1.0, initial-scale=1.0,initial-scale=1.0,user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="Keywords" content="用户注册-伽满优,车贷理财,车辆抵押,P2P投资理财,投资理财公司,短期理财,P2P投资理财平台" />
	<meta name="Description" content="用户注册-伽满优,通过公开透明的规范操作,平台为投资理财人士提供收益合理、安全可靠、高效灵活的车贷理财产品。" />
    <link href="/images/default.css" rel="stylesheet" type="text/css" />
	<link href="/images/index.css" rel="stylesheet" type="text/css" />
    <link href="/src/css/layui.css" rel="stylesheet" />
	<style>
		.dl_nr ul li {
			height: 30px;
		}
		.dl_nr ul li .in1 {
			height: 35px;
		}
		.dl_nr ul li .hqyzm_biao:enabled {
			height: 35px;
			line-height: 35px;
		}
		.dl_nr ul li img#Imageid {
			height: 25px;
		}
	</style>
    <script src="/src/layui.js"></script>
</head>
<body>
<?php include("top.php") ?>
<div class="cent">
    <div class="dqwz"><i class="icon"></i><a href="/">首页</a> >   注册</div>
    <div class="dl">
		<img src="/images/zc_tu.jpg" />
		<div class="sx"></div>
		<div class="dl_nr">
			<h3><span>已有账户？ <a href="/suny/login.html">登录</a></span>新用户注册</h3>
			<form id="regForm" onsubmit="return false;">
			<ul>
				<li style='display:none;color:red;height:12px;line-height:12px;'><font></font></li>
				<li>
				   <input name="phone" type="text" class="in1" maxlength="11" placeholder="请输入手机号码" />
					<br />
				</li>
				<li>
				   <input name="user_pass" type="password" class="in1" placeholder="输入登录密码" />
				</li>                        
				<li>
					<input name="tcode" type="text" maxlength="6" class="in1" placeholder="输入图形验证码" />
					<b></b>
				</li>                       
				<li> 
					<input name="pcode" type="text" class="in1 in2" placeholder="请输入手机验证码" />
					&nbsp;&nbsp;<input id="oncode" type="button" class="hqyzm_biao" value="获取验证码" />
				</li>
		   
				<li>
					<input <?php if($a=$this->input->get('invite', TRUE)){?>value="<?php echo intval($a);?>" disabled <?php }?> name="codeuid" type="text" class="in1" maxlength="11" placeholder="推荐人邀请码(选填)" />
					<?php if($a=$this->input->get('invite', TRUE)){?>
					<input type='hidden' value='<?php echo intval($a);?>' name="codeuid" />
					<?php }?>
				</li>
				<li>
					<select name="status" class="in1" style="color:#333;">
						<option value="1">个人用户</option>
						<option value="2">企业用户</option>
					</select>
				</li>
				<li>
					<select name="attribute" class="in1"  style="color:#333;">
						<option value="1">出借人</option>
						<option value="2">融资人</option>
					</select>
				</li>
				<li colspan="2" class="td1" align="center" style="height:20px; padding:0">
					<input type="checkbox" name="agree" checked="checked" disabled /> 我已阅读并同意 <a href="javascript:;" id="agree">《伽满优服务协议》</a>
				</li>
				<input type='hidden' value='<?php echo $type?>' name='type' />
				<li>
					<input type="submit" id='reg' name="" value="立即注册" class="xyb" />
				</li>
			</ul>
			</form>
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
		$('#reg').on('click',function(){
			$.post("/suny/reg.html", $('#regForm').serialize(), 
				function(r){
					layer.msg(r.message, {icon: 2, time : 1500});
					if(r.state == 1){
						location.href = r.url;
					} else {
						return;
					}
			}, 'json');
		});
		$('select[name=status]').change(function() {
			if($(this).val() == 1) {
				$('select[name=attribute]').html('<option value="1">出借人</option><option value="2">融资人</option>');
			} else {
				$('select[name=attribute]').html('<option value="2">融资人</option>');
			}
		});
		$('#reg').submit();
		$('li b').load('/suny/captcha');
		$('li b').click(function(){
			$(this).load('/suny/captcha.html?t=<?php echo time();?>');
		});
		$('#oncode').on('click',function(){
			$.post('/suny/send.html', $('#regForm').serialize(), function(s){
				layer.msg(r.message, {icon: 2, time : 1500});
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
				$("#oncode").val("重发").prop("disabled", false);
			}
			else {
				curCount--;
				$("#oncode").prop("disabled", false);
				$("#oncode").val(curCount + "(S)").prop("disabled",true);
			}
		}
		$('#agree').on('click',function(){
			layer.open({
				type: 2,
				title: '《伽满优服务协议》',
				shadeClose: true,
				shade: 0.5,
				maxmin: true,
				area: ['1200px', '800px'],
				fixed: false,
				//content: 'https://www.jiamanu.com/SiteAgreement.aspx?configId=11'
				content: 'https://www.jiamanu.com/suny/contract.html'
			});
		});
	});
</script>
<style>
#Imageid {
	width: 150px;
	height: 29px;
	display: inline-block;
	position: absolute;
	right: 10px;
	top: 4px;
	cursor: pointer;
}
</style>
</body>
</html>