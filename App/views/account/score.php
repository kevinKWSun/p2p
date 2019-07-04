<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>我的积分-伽满优</title>
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
		<h3>发放记录</h3>
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
									<!--<a class="layui-btn layui-btn-danger" data-type="" data-url="/account/score.html?status=0">
										已发放
									</a>-->
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
							<th>积分</th>
							<th>发放时间</th>
							<th>描述</th>
							<th>操作</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($score as $v):?>
						<tr>
							<td><?php echo $v['score']; ?></td>
							<td><?php echo date('Y-m-d H:i', $v['addtime']); ?></td>
							<td>
								<?php if($v['remark']) { echo $v['remark']; } elseif(!$v['invest_id']) { echo '客服发放'; } else { echo '投标['.$v['borrow']['borrow_name'] . ']['. $v['investor'][0]['investor_capital'] . '元]获得';} ?>
							</td>
							<td><?php if($v['invest_id']) { echo '<a href="/invest/show/'.$v['bid'].'.html" target="_blank">查看</a>'; } else { echo '-'; } ?></td>
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