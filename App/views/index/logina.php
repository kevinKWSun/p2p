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
		<script type="text/javascript" src="/images/index.js"></script>
		<script src="/images/new/js/swiper.min.js"></script>
		<script src="/images/new/js/jquery.SuperSlide.2.1.js"></script>
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
			<h3><span>没有账户?<a href="/suny/reg.html">立即注册</a></span>登录伽满优</h3>
			<form id="loginForm" onsubmit="return false;">
				<div class="regForm">
					<ul>
						<li>
						  <input  name="phone" type="text" placeholder="请输入手机号" value="<?php if($phone) { echo $phone; }?>"> 
						</li>
						<li>
						  <input  name="user_pass" type="password" placeholder="请输入密码" value="">
						</li>                        
						<li colspan="2" class="td1" align="center" style="height:20px; padding:0">
							<label><input type="checkbox" name="agree" value="1" <?php if($phone) { echo 'checked'; } ?> /> 记住账户 </label><a href="javascript:;" id="agree">忘记密码？</a>
							<input type='hidden' value='1' name='type' />
							<input type='hidden' value='<?php echo $url?>' name='ret_url' />
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
			
			$.post("/suny/login.html", $('#loginForm').serialize(), function(r) { 
				if(r.state == 1) {
					location.href = r.url;
				} else {
					layer.msg(r.message, {icon: 2, time : 1500});
				}
			}, 'json');
		});
		$('#agree').on('click',function(){
			location.href = '/suny/forget.html';
		});
	});
</script>