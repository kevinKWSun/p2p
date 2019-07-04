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
			<form action="" class="layui-form" method="post" lay-filter="LAY_table_user" id="LAY_table_user">
				<table class="layui-table">
					<thead>
						<tr>
							<th>金额(元)</th>
							<th>状态</th>
							<th>红包来源</th>
							<th>有效期截至</th>
							<th>使用范围</th>
							<th>使用说明</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($packets as $v):?>
						<tr>
							<td><?php echo $v['money']?></td>
							<td><?php echo $status?></td>
							<td>出借红包</td>
							<td><?php echo date('Y-m-d', $v['etime'])?></td>
							<td>全部产品</td>
							<td><?php echo '标的期限>=',$v['times'],'天,单笔投资额>=',$v['min_money'],'元,可使用';?></td>
						</tr>
						<?php endforeach;?>
					</tbody>
				</table>
			</form>
		</div>
		<div class="layui-box layui-laypage layui-laypage-default">
			<?php echo $page; ?>
			<a href="javascript:;" class="layui-laypage-next">共 <?php echo $totals; ?> 条</a>
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