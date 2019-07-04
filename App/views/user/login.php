<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="/src/css/layui.css">
    <title>系统登录</title>
    <style>
        div{text-align: center;}
        div.layui-container {padding-top: 20%;}
        form {padding: 20px;padding-top: 0;border: 1px solid #DDD;border-radius: 10px;}
    </style>
</head>
<body style='background:url(/src/images/bgs.jpg)'>
<!--[if lt IE 9]>
	<script src="/src/jr/html5.min.js"></script>
	<script src="/srcjr/respond.min.js"></script>
<![endif]-->
<div class="layui-container">
    <form class="layui-form layui-form-pane" action="/login.html" method="post" onSubmit="return false;" style='margin:0 auto;width:40%;'>
        <fieldset class="layui-elem-field layui-field-title" style="margin-top: 40px;">
            <legend style="color:#FFF">登录</legend>
        </fieldset>
        <div class="layui-form-item">
			<input name="user_name" lay-verify="required" autocomplete="off" placeholder="请输入账号" class="layui-input" type="text" />
        </div>
        <div class="layui-form-item">
			<input name="password" lay-verify="required"  autocomplete="off" placeholder="请输入密码" class="layui-input" type="password" />
        </div>
        <div class="layui-form-item">
            <a class="layui-btn pop_login_btn" lay-submit="" data-loading-text="登录中..." lay-filter="btnsubmit">登录</a>
        </div>
    </form>
</div>
<script src="/src/layui.js"></script>
<script type="text/javascript">
	layui.use(['layer', 'form'], function () {
		var $ = layui.$
		, layer = layui.layer
		, form = layui.form;
		$(document).keydown(function(event){
			if(event.keyCode == 13){
				$("a.pop_login_btn").click();
			}
		});
		$('input[name=user_name]').focus();
		form.on('submit(btnsubmit)', function (formdata) {
			var index = layer.load(1);
			var url = $(this).attr('action');
			$.post(url,formdata.field,function(s){
				layer.close(index);
				if(s.state == 1){
					top.location.href = s.url;
				}else{
					layer.msg(s.message);
				}
			});
			return false;
		});
	});
</script>
</body>
</html>