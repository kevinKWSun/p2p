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
    <title>还款列表</title>
    <link href="/src/css/layui.css" rel="stylesheet" />
    <script src="/src/layui.js"></script>
</head>
<body>
	<form >
		<a class="layui-btn layui-btn-small js-prpayment" data-href="/testAdmin/test_ajax.html" lay-submit="" lay-filter="repayment" data-id="2666,2663,2669,2660,2672,2675,2666,2663,2669,2660,2672,2675,2666,2663,2669,2660,2672,2675,2666,2663,2669,2660,2672,2675,2666,2663,2669,2660,2672,2675,2666,2663,2669,2660,2672,2675,2666,2663,2669,2660,2672,2675,2666,2663,2669,2660,2672,2675,2666,2663,2669,2660,2672,2675,2666,2663,2669,2660,2672,2675,2666,2663,2669,2660,2672,2675,2666,2663,2669,2660,2672,2675,2666,2663,2669,2660,2672,2675"><i class="icon-edit fa fa-dollar"></i>还　款</a>
		<a class="layui-btn layui-btn-small js-prpayment" data-href="/testAdmin/test_ajax.html" lay-submit="" lay-filter="repayment" data-id="2666,2663,2669,2660"><i class="icon-edit fa fa-dollar"></i>还　款</a>
		<a class="layui-btn layui-btn-small js-prpayment" data-href="/testAdmin/test_ajax.html" lay-submit="" lay-filter="repayment" data-id="2666,2663,2669,2660"><i class="icon-edit fa fa-dollar"></i>还　款</a>
	</form>
	<script src="/src/global.js"></script>
	<script type="text/javascript">
		layui.use(['layer', 'form'], function() {
			var $ = layui.$
			, form = layui.form
			, layer = layui.layer;
			form.on('submit(repayment)', function(data) {
				var url = $(this).data('href');
				var id = $(this).data('id');
				if (url) {
					layer.confirm('你确定要还款吗？', {icon: 6, title:'信息提示'}
					, function(index){
						layer.load(1);
						$.ajax({
							url: url,
							type: 'post',
							dataType: 'json',
							data: {id : id},
							//timeout : 0,
							success: function (r, startic) {
								if(r.state == 1) {
									layer.msg(r.message, {icon: 6,time:1500}, function(){
										location.reload();
									});
								} else {
									layer.msg(r.message, {icon: 5,time:1500});
								}
							},
							beforeSend: function () {
							   $(data.elem).attr("disabled", "true").text("还款中...");
							},
							complete: function () {
							   $(data.elem).removeAttr("disabled").html('<i class="icon-edit fa fa-dollar"></i>还　款');
							},
							error: function (XMLHttpRequest, textStatus, errorThrown) {
								//layer.msg(textStatus);
								layer.msg(textStatus + XMLHttpRequest.readyState);
							}
						});
						layer.closeAll();
					}, function(index) {
						layer.closeAll();
					});
				} else {
					layer.msg('链接错误！');
				}
			});
		});
		
	</script>
</body>
</html>