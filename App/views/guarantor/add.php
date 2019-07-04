<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Language" content="zh-cn" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <meta name="viewport" content="width=device-width, maximum-scale=1.0, initial-scale=1.0,initial-scale=1.0,user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <title>添加担保人</title>
	<link href="/src/css/layui.css" rel="stylesheet" />
    <script src="/src/layui.js"></script>

</head>
<body>
    <div class="main-wrap" style='padding:10px;'>
        <blockquote class="layui-elem-quote fhui-admin-main_hd">
            <h3>添加担保人</h3>
        </blockquote>
        <form action="/guarantor/add" class="layui-form layui-form-pane" id="formrec" method="post" role="form">
            <div class="layui-form-item">
                <label class="layui-form-label">姓名</label>
                <div class="layui-input-inline">
                    <input name="name" autocomplete="off" lay-verify="required" value="" placeholder="必填,姓名" class="layui-input" type="text" maxlength="20">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">手机号</label>
                <div class="layui-input-inline">
                    <input name="phone" autocomplete="off" lay-verify="required" value="" placeholder="必填,联系手机" class="layui-input" type="text" maxlength="11">
                </div>
				<div class="layui-form-mid layui-word-aux" id="js-phone"></div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">身份证号</label>
                <div class="layui-input-inline">
                    <input name="idcard" autocomplete="off" lay-verify="required" value="" placeholder="必填,身份证号" class="layui-input" type="text" maxlength="18">
                </div>
				<div class="layui-form-mid layui-word-aux" id="js-idcard"></div>
            </div>
			<div class="layui-form-item">
                <label class="layui-form-label">性别</label>
                <div class="layui-input-inline">
					<select name="sex">
						<option>--请选择--</option>
						<option value="1">男</option>
						<option value="1">女</option>
					</select>
                </div>
            </div>
			<div class="layui-form-item">
                <label class="layui-form-label">车辆型号</label>
                <div class="layui-input-inline">
                    <input name="mode" autocomplete="off" lay-verify="required" value="" placeholder="必填,车辆型号" class="layui-input" type="text" maxlength="20">
                </div>
            </div>
			<div class="layui-form-item">
                <label class="layui-form-label">评估价</label>
                <div class="layui-input-inline">
                    <input name="price" autocomplete="off" lay-verify="required" value="" placeholder="必填,评估价,单位元" class="layui-input" type="text" maxlength="20">
                </div>
				<div class="layui-form-mid layui-word-aux">元</div>
            </div>
			<div class="layui-form-item">
                <label class="layui-form-label">图片 <span class="layui-badge" lay-tips="一次性全选上传,否则将被覆盖!">?</span></label>
                <div class="layui-input-inline">
					<button type="button" class="layui-btn" id="uppic"><i class="layui-icon">　</i>上传文件/全选上传</button>
					<input name="pic" autocomplete="off" lay-verify="" placeholder="必填" class="layui-input" type="hidden" >
                </div>
            </div>
			<div class="layui-form-item">
				<div class="pic"></div>
			</div>
            <!--底部工具栏-->
            <div class="page-footer">
                <div class="btn-list">
                    <div class="btnlist">
                        <a class="layui-btn layui-btn-sm" lay-submit="" lay-filter="doPost" data-url="/guarantor/add"><i class="layui-icon">&#x1005;</i>提交</a>
                        <a class="layui-btn layui-btn-sm do-action" data-type="doRefresh" data-url=""><i class="layui-icon">&#xe669;</i>刷新</a>
                        <a class="layui-btn layui-btn-sm do-action" data-type="doGoBack"><i class="layui-icon">&#xe65c;</i>返回上一页</a>
                    </div>
                </div>
            </div>
            <!--/底部工具栏-->
        </form>
    </div>
    <script src="/src/global.js"></script>
	<script type="text/javascript">
		layui.use(['layer', 'upload'], function(){
            var $ = layui.$
                , layer = layui.layer
				, upload = layui.upload;
			
			upload.render({
				elem: '#uppic'
				,url: '/login/do_uploads'
				,accept: 'images' //普通文件
				,multiple: true
				,field : 'userfile[]'
				,before: function(input){
				    console.log('文件上传中');
			    }
				,done: function(res){
				    layer.msg('上传完毕');
					var img = $('input[name=pic]').val();
					$.each(res, function(key, val){
						if(res[key].code == 200){
							if(img){
								$('input[name=pic]').val(img + ',' + res[key].savepath);
							}else{
								$('input[name=pic]').val(res[key].savepath);
							}
							var obj = "<div class='layui-inline'><img width='200' src='"+res[key].savepath+"' /></div>";
							$('.pic').before(obj);
							console.log(img);
						}
					});
				}
				,error : function(e){
					console.log(e);
				}
			});
			$('input[name=phone]').blur(function() {
				var phone = $(this).val();
				$.post('/guarantor/is_repeated/1.html', {phone: phone}, function(r) {
					if(!r.state) {
						$('#js-phone').html(r.message);
					}
				}, 'json');
			}).focus(function() {
				$('#js-phone').html('');
			});
			$('input[name=idcard]').blur(function() {
				var idcard = $(this).val();
				$.post('/guarantor/is_repeated/2.html', {idcard: idcard}, function(r) {
					if(!r.state) {
						$('#js-idcard').html(r.message);
					}
				}, 'json');
			}).focus(function() {
				$('#js-idcard').html('');
			});
        });
	</script>
</body>
</html>