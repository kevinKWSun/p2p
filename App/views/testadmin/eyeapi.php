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
    <title>天眼接口测试</title>
    <link href="/src/css/layui.css" rel="stylesheet" />
    <script src="/src/layui.js"></script>
</head>
<body>
    <div class="main-wrap" style='padding:10px;'>
		<blockquote class="layui-elem-quote fhui-admin-main_hd">
            <h2>天眼接口测试</h2>
        </blockquote>
		<form class="layui-form layui-form-pane form-horizontal" id="formrec" method="post" role="form">
			
			<div class="layui-form-item">
                <label class="layui-form-label">天眼接口地址</label>
				<div class="layui-input-block">
					<input autocomplete="off" lay-verify="required" value="http://datatest.p2peye.com" class="layui-input" type="text" dispabled />
				</div>
            </div>
			<div class="layui-form-item">
                <label class="layui-form-label">测试地址</label>
				<div class="layui-input-block">
					<input autocomplete="off" name="url" lay-verify="required" value="https://www.jiamanu.com/eyeapi/loans" class="layui-input" type="text" />
				</div>
            </div>
			 <div class="layui-form-item">
                <label class="layui-form-label">标的状态</label>
				<div class="layui-input-block">
					<input name="status" autocomplete="off" lay-verify="required" value="" placeholder="必填,标的状态:0.正在投标中的借款标;1.已完成(包括还款中和已完成的借款标)." class="layui-input" type="text"  />
				</div>
				<label class="layui-form-label">起始时间</label>
                <div class="layui-input-block">
                    <input name="time_from" autocomplete="off" lay-verify="required" value="" placeholder="必填,起始时间如:2014-05-09 06:10:00,
状态为1是对应平台满标字段的值检索,状态为0就以平台发标时间字段检索." class="layui-input" type="text"  />
                </div>
                <label class="layui-form-label">截止时间</label>
                <div class="layui-input-block">
                    <input name="time_to" autocomplete="off" lay-verify="required" value="" placeholder="必填,截止时间如:2014-05-09 06:10:00,
状态为1是对应平台满标字段的值检索,状态为0就以平台发标时间字段检索." class="layui-input" type="text"  />
                </div>
				 <label class="layui-form-label">记录条数</label>
                <div class="layui-input-block">
                    <input name="page_size" autocomplete="off" lay-verify="required" value="" placeholder="每页记录条数" class="layui-input" type="text"  />
                </div>
				 <label class="layui-form-label">页码</label>
                <div class="layui-input-block">
                    <input name="page_index" autocomplete="off" lay-verify="required" value="" placeholder="必填,请求的页码." class="layui-input" type="text"  />
                </div>
				
            </div>
			<div class="page-footer">
                <div class="btn-list">
                    <div class="btnlist">
                        <a class="layui-btn layui-btn-sm" lay-submit="" lay-filter="post" data-url="/eyeapi/test.html"><i class="layui-icon">&#x1005;</i>提交</a>
                        <a class="layui-btn layui-btn-sm do-action" data-type="doRefresh" data-url=""><i class="layui-icon">&#xe669;</i>刷新</a>
                        <a class="layui-btn layui-btn-sm do-action" data-type="doGoBack"><i class="layui-icon">&#xe65c;</i>返回上一页</a>
                        <a class="layui-btn layui-btn-sm do-action" data-type="doGoTop" data-url=""><i class="layui-icon">&#xe604;</i>返回顶部</a>
                    </div>
                </div>
            </div>
		</form>
    </div>
    <script src="/src/global.js"></script>
	<script type="text/javascript">
		layui.use(['layer', 'form'], function(){
			var $ = layui.$
			, form = layui.form
			, layer = layui.layer;
			form.on('submit(post)', function(formdata) {
				var url = $('input[name=url]').val();
				console.log(formdata.field);
				$.post(url, formdata.field, function(r) {
					console.log(r);
				}, 'json');
			});
		});
	</script>
</body>
</html>