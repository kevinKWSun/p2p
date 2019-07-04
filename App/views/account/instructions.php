<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>我的红包-伽满优</title>
    <meta http-equiv="Content-Language" content="zh-cn" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <meta name="viewport" content="width=device-width, maximum-scale=1.0, initial-scale=1.0,initial-scale=1.0,user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="Keywords" content="我的红包-伽满优,车贷理财,车辆抵押,P2P投资理财,投资理财公司,短期理财,P2P投资理财平台" />
	<meta name="Description" content="我的红包-伽满优,通过公开透明的规范操作,平台为投资理财人士提供收益合理、安全可靠、高效灵活的车贷理财产品。" />
    <link href="/src/css/layui.css" rel="stylesheet" />
    <script src="/src/layui.js"></script>
</head>
<body>
<div class="main-wrap" style='padding:10px;'>
	<blockquote class="layui-elem-quote" style='border-left:5px solid #FF5722'>
		<h3><?php echo $status?></h3>
	</blockquote>
	<div class="y-role">
		<!--工具栏-->
		<div id="floatHead" class="toolbar-wrap">
			<div class="toolbar">
				<div class="box-wrap">
					<div class="l-list clearfix">
						<form id="tt" class="layui-form layui-form-pane">
							<div class="layui-form-item">
								<div class="layui-inline">
									<a class="layui-btn layui-btn-danger" data-type="" data-url="/account/packet.html?status=0">
										未使用
									</a>
									<a class="layui-btn layui-btn-danger" data-type="" data-url="/account/packet.html?status=1">
										已使用
									</a>
									<a class="layui-btn layui-btn-danger" data-type="" data-url="/account/packet.html?status=2">
										已过期
									</a>
									<a class="layui-btn layui-btn-danger" data-type="" data-url="/account/instructions.html">
										使用说明
									</a>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<div class="fhui-admin-table-container">
			<div class="layui-form-item" style='font-size:14px;line-height:35px;'>
				<div class="layui-input-block" style="margin-left:35px;">
					1、投资红包适用所有投资项目，需用户选择操作，当项目放款后，系统自动激活所选红包。<br />
					2、投资红包激活后，将自动变成可用余额，每笔投资可选择激活一个条件适合的投资红包。<br />
					3、投资红包有效期：每个投资红包都有一个有效期，用户需在有效期内激活，过期无效。<br />
					4、本次红包活动规则最终解释权归“伽满优”平台所有。<br />
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	layui.use(['layer', 'form'], function () {
		var $ = layui.$
		, layer = layui.layer
		, form = layui.form;
		$('#floatHead a').on('click',function(data){
			location.href = $(this).attr('data-url');
		});
	});
</script>
</body>
</html>