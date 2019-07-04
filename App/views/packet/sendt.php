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
    <title>发投资红包</title>
    <link href="/src/css/layui.css" rel="stylesheet" />
    <script src="/src/layui.js"></script>
</head>
<body>
    <div class="main-wrap" style='padding:10px;'>
		<blockquote class="layui-elem-quote fhui-admin-main_hd">
            <h2>发投资红包</h2>
        </blockquote>
		<form class="layui-form layui-form-pane form-horizontal" id="formrec" method="post" role="form">
			<input type="hidden" name="ids" value="<?php echo $ids; ?>" />
			<div class="layui-form-item">
				<label class="layui-form-label">红包类型</label>
				<div class="layui-input-inline">
					<input type="text" value="投资红包" class="layui-input" disabled/>
				</div>
			</div>
			<div class="layui-form-item">
				<label class="layui-form-label">发放人数</label>
				<div class="layui-input-inline">
					<input type="text" value="<?php echo $count; ?>人" class="layui-input" disabled/>
				</div>
			</div>
			<div id="invest" class="all">
				
			</div>
			<div class="page-footer">
                <div class="btn-list">
                    <div class="btnlist">
                        <a class="layui-btn layui-btn-sm" lay-submit="" lay-filter="send_packet" data-url="/packet/sendt.html"><i class="layui-icon">&#x1005;</i>提交</a>
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
			
			var invest = '<div class="layui-form-item"><label class="layui-form-label">红包金额</label><div class="layui-input-inline"><input type="text" name="invest[]" lay-verify="required" placeholder="必填" autocomplete="off" class="layui-input"></div><div class="layui-form-mid layui-word-aux">元<button class="layui-btn layui-btn-sm" id="invest-add">增加</button><button class="layui-btn layui-btn-sm" id="invest-del">删除</button></div></div><div class="layui-form-item"><label class="layui-form-label">投资下限</label><div class="layui-input-inline"><input type="text" name="min_money[]" lay-verify="required" placeholder="必填" autocomplete="off" class="layui-input"></div><div class="layui-form-mid layui-word-aux">元[100或100的整数倍]</div></div><div class="layui-form-item"><label class="layui-form-label">期限下限</label><div class="layui-input-inline"><input type="text" name="times[]" lay-verify="required" placeholder="必填" autocomplete="off" class="layui-input"></div><div class="layui-form-mid layui-word-aux">天[输入0无下限]</div></div><div class="layui-form-item"><label class="layui-form-label">有效期</label><div class="layui-input-inline"><input type="text" name="etime[]" lay-verify="required" placeholder="必填" autocomplete="off" class="layui-input"></div><div class="layui-form-mid layui-word-aux">天</div></div>';
			$(invest).appendTo($('#invest'));
			$('#invest').on('click', '#invest-add', function() {
				$('<div class="layui-form-item js-invest-add1"><label class="layui-form-label">红包金额</label><div class="layui-input-inline"><input type="text" name="invest[]" lay-verify="required" placeholder="必填" autocomplete="off" class="layui-input"></div><div class="layui-form-mid layui-word-aux">元</div></div>').appendTo($('#invest'));
				$('<div class="layui-form-item js-invest-add2"><label class="layui-form-label">投资下限</label><div class="layui-input-inline"><input type="text" name="min_money[]" lay-verify="required" placeholder="必填" autocomplete="off" class="layui-input"></div><div class="layui-form-mid layui-word-aux">元[100或100的整数倍]</div></div>').appendTo($('#invest'));
				$('<div class="layui-form-item js-invest-add3"><label class="layui-form-label">期限下限</label><div class="layui-input-inline"><input type="text" name="times[]" lay-verify="required" placeholder="必填" autocomplete="off" class="layui-input"></div><div class="layui-form-mid layui-word-aux">天[输入0无下限]</div></div>').appendTo($('#invest'));
				$('<div class="layui-form-item js-invest-add4"><label class="layui-form-label">有效期</label><div class="layui-input-inline"><input type="text" name="etime[]" lay-verify="required" placeholder="必填" autocomplete="off" class="layui-input"></div><div class="layui-form-mid layui-word-aux">天</div></div>').appendTo($('#invest'));
				return false;
			});
			$('#invest').on('click', '#invest-del', function() {
				$('.js-invest-add1:last').remove();
				$('.js-invest-add2:last').remove();
				$('.js-invest-add3:last').remove();
				$('.js-invest-add4:last').remove();
				return false;
			});
			form.on('submit(send_packet)', function(data) {
				var url = $(this).data('url');
				//console.log(url);
				// $.post(url, $('#formrec').serialize(), function(r){
					// var icon = (r.state == 1) ? 6 : 5;
					// layer.msg(r.message, {icon:icon, time:1500}, function() {
						// if(r.state == 1) {
							// parent.window.location.href = parent.window.location.href;
						// }
					// });
				// }, 'json');
				
				if (url) {
					layer.load(1);
					$.ajax({
						url: url,
						type: 'post',
						dataType: 'json',
						data: $('#formrec').serialize(),
						success: function (r, startic) {
							var icon = (r.state == 1) ? 6 : 5;
							layer.msg(r.message, {icon:icon, time:1500}, function() {
								if(r.state == 1){
									 parent.window.location.href = parent.window.location.href;
								}
							});
						},
						beforeSend: function () {
						   $(data.elem).attr("disabled", "true").text("提交中...");
						},
						complete: function () {
						   $(data.elem).removeAttr("disabled").html('<i class="layui-icon">&#x1005;</i>提交');
						},
						error: function (XMLHttpRequest, textStatus, errorThrown) {
							layer.msg(textStatus);
						}
					});
					//layer.closeAll();
				} else {
					layer.msg('链接错误！');
				}
			});
		});
		
	</script>
</body>
</html>