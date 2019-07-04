<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<title>伽满优-资金第三方存管，安全透明高效的车贷理财平台</title>
		<meta name="Keywords" content="伽满优，车贷理财，车辆抵押,P2P投资理财,投资理财公司,短期理财,P2P投资理财平台" />
		<meta name="Description" content="伽满优，通过公开透明的规范操作，平台为投资理财人士提供收益合理、安全可靠、高效灵活的车贷理财产品。" />
		<link href="/favicon.ico" rel="SHORTCUT ICON" />
		<link href="/images/default.css" rel="stylesheet" type="text/css" />
		<link href="/images/new/index.css" rel="stylesheet" type="text/css" />
		<link href="/images/new/swiper.min.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript" src="/images/jquery-1.7.2.min.js"></script>
		<script type="text/javascript" src="/images/globle.js"></script>
		<link href="/src/css/layui.css" rel="stylesheet" />
		<script src="/src/layui.js"></script>
		<style>
.btnlogin{ width:298px; height:48px; color:#fff; border:none; font-size:18px; background:#fc501b; border-radius:3px;}
.foot_nav h3 img.icon1{margin-top:0}
</style>
</head>
<body>
<?php include("topa.php"); ?>
</div>
<div class="dl2">
	<div class="cent">
		<div class="dl_nr" style="height:400px;">
			<h3>验证码登录</h3>
			<form id="regForm" onsubmit="return false;">
				<div class="regForm">
					<ul>
						<li>
						  <input  name="phone" type="text" placeholder="请输入手机号" value="<?php if($phone) { echo $phone; }?>"> 
						</li>
						<li>
						  <input  name="tcode" type="text" placeholder="请输入图形码" value="">
						  <b></b>
						</li>                        
						<li>
						  <input  name="user_pass" type="password" placeholder="请输入验证码" value="">
						  <input id="oncode" type="button" class="hqyzm_biao" value="获取验证码" />
						</li>
						<li>
							<input type="submit" id='loginSum' name="" value="登录" class="xyb" style="cursor:pointer;" />
						</li>
					</ul>
				</div>
			</form>
		</div> 
	</div>
</div>
<?php include("foota.php"); ?>
</body>
</html>
<script type="text/javascript">
	layui.use(['layer', 'form'], function () {
		var $ = layui.$
		, layer = layui.layer
		, form = layui.form;
		$('input[name=user_name]').focus();
		$('#loginSum').on('click',function(){
			$.post("/suny/login.html", $('#regForm').serialize(), function(r) { 
				if(r.state == 1) {
					location.href = r.url;
				} else {
					layer.msg(r.message, {icon: 2, time : 1500});
				}
			}, 'json');
		});
		$('li b').load('/suny/captcha');
		$('li b').click(function(){
			$(this).load('/suny/captcha.html?t=<?php echo time();?>');
		});
		$('#oncode').on('click',function(){
			$.post('/suny/sends.html', $('#regForm').serialize(), function(s){
				layer.msg(s.message, {icon: 2, time : 1500});
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
	});
</script>
<style>
#Imageid {
	width: 63px;
	height: 32px;
	display: inline-block;
	position: absolute;
	margin-top:4px;
	cursor: pointer;
}
.dl2 .regForm ul li:nth-child(2) input[type="text"] {
    width: 290px;
}
.dl2 .regForm ul li:nth-child(3){
	background-color: #fff;
	margin-left: 20px;
}
.dl2 .regForm ul li:nth-child(3) input[type="password"] {
    width: 200px;
    border-radius: 5px;
    padding-left: 10px;
}
.dl2 .regForm ul li:nth-child(3) input[type="button"] {
    width: 140px;
    border-radius: 5px;
    background: #fc501b;
    border: none;
    height: 40px;
    color: #fff;
    float: right;
    font-size: 16px;
}
.dl_nr h3 {
    line-height: 25px;
}
</style>