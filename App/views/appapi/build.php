<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title></title>
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<link href="/images/default.css" rel="stylesheet" type="text/css" />
		<link href="/images/index.css" rel="stylesheet" type="text/css" />
		<link href="/src/css/layui.css" rel="stylesheet" />
		<script src="/src/layui.js"></script>
		<link rel="stylesheet" href="/css/mui.min.css">
    	<link href="/css/iconfont.css" rel="stylesheet"/>
	</head>
	<body>
		<div class="mui-content mui-scroll-wrapper">
			<input type="hidden" name="merOrderNo" value="<?php echo $merOrderNo; ?>" />
			<input type="hidden" name="uid" value="<?php echo $uid; ?>" />
			<div class="main">
				<div class="xgmm" style=" border-top:none;">
					<ul>
						<div class="mui-input-row mui-checkbox mui-left">
							<label>同意并签署借款合同</label>
							<input name="agree_one" value="1" type="checkbox" id='agree_one'>
						</div>
						<div class="mui-input-row mui-checkbox mui-left agree_two">
							<label>同意并签署居间合同</label>
							<input name="agree_two" value="1" type="checkbox" id='agree_two'>
						</div>
						<li>
							<input id='pcode' type="number" class="in1 in2" placeholder="请输入手机验证码">
							<a id="oncode" class="hq_biao tappable" style='margin-top:0.4rem'>获取验证码</a>
						</li>
					</ul>
				</div>
			</div>
			<div class="xqtb">
				<a href="javascript:void(0)" class="ljtb_baio bidState" id='contract_submit'>确认签署</a>
			</div>
		</div>
	</body>
	<script src="/js/mui.min.js"></script>
	<script src="/js/jquery-1.11.0.js"></script>
	<script src="/js/g.js"></script>
	<script type="text/javascript">
		$('#oncode').on('tap', function(){
			var success = function (response){
				mui.toast(response.message);
				sendMessage();
			}
			var data = {
				uid : $('input[name=uid]').val()
			}
			ajax('/appapi/send_code.html', data, success);
		});
		var InterValObj; 
		var count = 60; 
		var curCount;
		function sendMessage() {
			curCount = count;
			$("#oncode").prop("disabled",false);
			$("#oncode").text(curCount + "(S)").prop("disabled",true);
			InterValObj = window.setInterval(SetRemainTime, 1000); 
		}
		//timer处理函数
		function SetRemainTime() {
			if (curCount == 0) {                
				window.clearInterval(InterValObj);
				$("#oncode").prop("disabled", false);
				$("#oncode").text("重发").prop("disabled", false);
			}
			else {
				curCount--;
				$("#oncode").prop("disabled", false);
				$("#oncode").text(curCount + "(S)").prop("disabled",true);
			}
		}
		/* $('#agree_one').on('tap', function(){
			var flag = $('input[name=merOrderNo]').val();
			MuiUse.openWindow('/appapi/build_one/' + flag + '.html');
		});
		$('#agree_two').on('tap', function(){
			var flag = $('input[name=merOrderNo]').val();
			MuiUse.openWindow('/appapi/build_two/' + flag + '.html');
		}); */
		$('#contract_submit').on('tap', function(){
			var agree_one = $('#agree_one');
			for(i = 0; i < agree_one.length; i++){
				if(agree_one[i].checked){
					var agree_one = agree_one[i].value;
				}else{
					var agree_one = 0;
				}
			}
			var agree_two = $('#agree_two');
			for(i = 0; i < agree_two.length; i++){
				if(agree_two[i].checked){
					var agree_two = agree_two[i].value;
				}else{
					var agree_two = 0;
				}
			}
			$('#contract_submit').text('签署中...');
			$('#contract_submit').attr("disabled",true);
			var success = function (response){
				mui.toast(response.message);
				if(response.state == 1) {
					location.href = response.url;
				} else {
					$('#contract_submit').text('确认签署');
					$('#contract_submit').attr("disabled",false);
				}
			}
			var data = {
				agree_one : agree_one,
				agree_two : agree_two,
				pcode     : $('#pcode').val(),
				uid       : $('input[name=uid]').val(),
				merOrderNo :$('input[name=merOrderNo]').val(),
			}
			ajax('/appapi/contract_submit.html', data, success);
		});
	</script>
</html>