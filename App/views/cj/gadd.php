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
    <title>增加商品</title>
	<link href="/src/css/layui.css" rel="stylesheet" />
    <script src="/src/layui.js"></script>

</head>
<body>
    <div class="main-wrap" style='padding:10px;'>
        <blockquote class="layui-elem-quote fhui-admin-main_hd">
            <h3>增加商品　<button type="button" class="layui-btn" id="test10"><i class="layui-icon"></i>上传图片</button></h3>
        </blockquote>
        <form action="/cj/gadd" class="layui-form layui-form-pane" id="formrec" method="post" role="form">
            <div class="layui-form-item">
                <div class="layui-input-inline">
					<img id='img' src='/src/images/none.png' width='100' style='display:none;' />
                    <input name="img" value="" type="hidden">
                </div>
            </div>
			<div class="layui-form-item">
                <label class="layui-form-label">名称</label>
                <div class="layui-input-block">
                    <input name="name" autocomplete="off" lay-verify="required" value="" placeholder="必填,名称" class="layui-input" type="text">
                </div>
            </div>
			<div class="layui-form-item">
                <label class="layui-form-label">概率</label>
                <div class="layui-input-block">
                    <input name="probability" autocomplete="off" lay-verify="required" value="" placeholder="必填,概率/%" class="layui-input" type="text">
                </div>
            </div>
			<div class="layui-form-item">
                <label class="layui-form-label">数量</label>
                <div class="layui-input-block">
                    <input name="num" autocomplete="off" lay-verify="required" value="" placeholder="必填,数量" class="layui-input" type="text">
                </div>
            </div>
			<div class="layui-form-item">
                <label class="layui-form-label">类别</label>
                <div class="layui-input-block">
                    <input name="types" value="1" title="抽奖" type="radio" />
					<input name="types" value="2" title="宝箱" type="radio" />
                </div>
            </div>
            <!--底部工具栏-->
            <div class="page-footer">
                <div class="btn-list">
                    <div class="btnlist">
                        <a class="layui-btn layui-btn-sm" lay-submit="" lay-filter="doPost" data-url="/cj/gadd"><i class="layui-icon">&#x1005;</i>提交</a>
                        <a class="layui-btn layui-btn-sm do-action" data-type="doRefresh" data-url=""><i class="layui-icon">&#xe669;</i>刷新</a>
                        <a class="layui-btn layui-btn-sm do-action" data-type="doGoBack"><i class="layui-icon">&#xe65c;</i>返回上一页</a>
                        <a class="layui-btn layui-btn-sm do-action" data-type="doGoTop" data-url=""><i class="layui-icon">&#xe604;</i>返回顶部</a>
                    </div>
                </div>
            </div>
            <!--/底部工具栏-->
        </form>
    </div>
    <script src="/src/global.js"></script>
	<script>
		layui.use(['layedit','layer', 'form', 'upload'], function(){
            var layedit = layui.layedit
                ,$ = layui.$
                , layer = layui.layer
                , form = layui.form
				, upload = layui.upload;
			upload.render({
				elem: '#test10'
				,url: '/login/ydo_upload'
				,type: 'post'
				,done: function(res){
					//console.log(res)
					if(res.code == 0){
						$('input[name=img]').val(res.data.src);
						$('#img').attr('src', res.data.src).show();
					}else{
						layer.msg(res.errorMsg);
					}
				}
			});
        });
	</script>
</body>
</html>