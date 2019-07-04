<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>倒计时</title>
		<link href="https://www.jiamanu.com/src/css/layui.css" rel="stylesheet" />
		<script src="https://www.jiamanu.com/src/layui.js"></script>
	</head>
	<body>
		<input name="pcode" type="text" class="in1 in2" placeholder="请输入手机验证码" style="float:none;" />
					&nbsp;&nbsp;<input id="js-yqbcode" type="button" class="hqyzm_biao" value="点击获取验证码" style="float:none;" />
	</body>
	<script type="text/javascript">
		layui.use(['layedit', 'form', 'util', 'laydate'], function() {
			var $ = layui.$
			, util = layui.util
	  		, laydate = layui.laydate
			, layer = layui.layer;
		
			$('#js-yqbcode').click(function() {
				console.log(22);
				builddjs.settime(this);
				
				$.post('test.html', {}, function(r){
					var icon = r.message == '成功' ? 6 : 5;
					layer.msg(r.message, {icon : icon, time: 1500});
					return;
				}, 'json');
			});
			
			
		});
		var builddjs = builddjs || {};
		
		builddjs.countdown = 60;
		builddjs.settime = function(obj) {
		    if (builddjs.countdown == 0) {
		        obj.removeAttribute("disabled");   
		        obj.value="免费获取验证码";
				builddjs.countdown = 60;
		        return;
		    } else {
		        obj.setAttribute("disabled", true);
		        obj.value="重新发送(" + builddjs.countdown + ")";
				builddjs.countdown--;
		    }
			setTimeout(function() {
				builddjs.settime(obj) 
			},1000);
		}

		
	</script>
</html>
