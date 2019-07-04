<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<title>伽满优用户注册-资金第三方存管，安全透明高效的车贷理财平台</title>
		<meta name="Keywords" content="伽满优用户注册,车贷理财,车辆抵押,P2P投资理财,投资理财公司,短期理财,P2P投资理财平台" />
		<meta name="Description" content="伽满优用户注册,通过公开透明的规范操作,平台为投资理财人士提供收益合理、安全可靠、高效灵活的车贷理财产品。" />
		<link href="/favicon.ico" rel="SHORTCUT ICON" />
		<link href="/images/default.css" rel="stylesheet" type="text/css" />
		<link href="/images/new/index.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript" src="/images/jquery-1.7.2.min.js"></script>
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
<div class="dl">
	<div class="cent">
		<div class="dl_nr">
			<form id="regForm" onsubmit="return false;">
				<div class="regForm">
					<ul>
						<li>
							<label><img src="/images/new/img/users.png">用户身份：</label>　
							<label><input name="status" type="radio" value="1" checked>个人　</label>　
							<label><input name="status" type="radio" value="2">企业</label>
							<br/>
							<label>　 　　　　　　　</label>
							<label id='cj'><input name="attribute" type="radio" value="1" checked>我要出借</label>
							<label id='jk'><input name="attribute" type="radio" value="2">我要借款</label>
						</li>
						<li>
						  <input name="phone" type="text" placeholder="请输入手机号" value=""> 
						</li>
						<li>
						  <input name="user_pass" type="password" placeholder="请输入密码" value="">
						</li>                        
						<li>
						   <input name="tcode" type="text" placeholder="请输入图形码" value="">
						   <b></b>
						</li>                       
						<li>
						  <input name="pcode" type="text" placeholder="请输入手机验证码" value="">
						  <input id="oncode" type="button" class="hqyzm_biao" value="获取验证码" />
						</li>
						<li>
							<input <?php if($a=$this->input->get('invite', TRUE)){?>value="<?php echo intval($a);?>" disabled <?php }?> name="codeuid" type="text" maxlength="11" placeholder="推荐人邀请码(选填)" />
							<?php if($a=$this->input->get('invite', TRUE)){?>
							<input type='hidden' value='<?php echo intval($a);?>' name="codeuid" />
							<?php }?>
						</li>
						<li colspan="2" class="td1" align="center" style="height:20px; padding:0">
							<input type="checkbox" name="agree" checked="checked" /> 我已阅读并同意 <a href="javascript:;" id="agree">《伽满优服务协议》</a>
							<input type='hidden' value='<?php echo $type?>' name='type' />
						</li>
						<li>
							<input type="submit" id='reg' name="" value="立即注册" class="xyb" />
						</li>
					</ul>
				</div>
			</form>
		</div> 
	</div>
</div>
<?php include("foota.php") ?>
<script>
	layui.use(['layer', 'form'], function () {
		var $ = layui.$
		, layer = layui.layer
		, form = layui.form;
		$('input[name=phone]').focus();
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
		$('input[name=status]').change(function() {
			if($(this).val() == 1) {
				$('#cj').show().click();
			} else {
				$('#cj').hide();
				$('#jk').click();
			}
		});
		$('#reg').submit();
		$('li b').load('/suny/captcha');
		$('li b').click(function(){
			$(this).load('/suny/captcha.html?t=<?php echo time();?>');
		});
		$('#oncode').on('click',function(){
			$.post('/suny/send.html', $('#regForm').serialize(), function(s){
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
		$('#agree').on('click',function(){
			layer.open({
				type: 2,
				title: '《伽满优服务协议》',
				shadeClose: true,
				shade: 0.5,
				maxmin: true,
				area: ['1200px', '800px'],
				fixed: false,
				content: 'https://www.jiamanu.com/suny/contract.html'
			});
		});
	});
</script>
<style>
#Imageid {
	width: 63px;
	height: 32px;
	display: inline-block;
	position: absolute;
	right: 30px;
	margin-top: 4px;
	cursor: pointer;
}
.dl .regForm ul li:nth-child(4) {
height: 40px;
line-height:40px;
}
</style>
</body>
</html>