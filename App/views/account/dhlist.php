<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>兑换记录-伽满优</title>
    <meta http-equiv="Content-Language" content="zh-cn" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <meta name="viewport" content="width=device-width, maximum-scale=1.0, initial-scale=1.0,initial-scale=1.0,user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="Keywords" content="兑换记录-伽满优,车贷理财,车辆抵押,P2P投资理财,投资理财公司,短期理财,P2P投资理财平台" />
	<meta name="Description" content="兑换记录-伽满优,通过公开透明的规范操作,平台为投资理财人士提供收益合理、安全可靠、高效灵活的车贷理财产品。" />
    <link href="/images/default.css" rel="stylesheet" type="text/css" />
	<link href="/images/index.css" rel="stylesheet" type="text/css" />
	<link href="/images/CenterIndex.css" rel="stylesheet" type="text/css" />
    <link href="/src/css/layui.css" rel="stylesheet" />
    <script src="/src/layui.js"></script>
</head>
<body>
<?php include("top.php") ?>
 <div class="cent">
	<div class="dqwz">
		<i class="icon"></i><a href="/">首页</a> > <a href="/account.html"> 我的账户 </a> > 兑换记录
	</div>
	<div class="zhzx">
		<div class="zhzx_l">
			<?php include("left.php") ?>
		</div>
		<div class="zhzx_r">
			<h2>兑换记录</h2>
			<div class="jyjl">
				<div class="jyjl">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<th>商品名称</th>
							<th>实付积分</th>
							<th>邮寄地址</th>
							<th>快递名称</th>
							<th>快递单号</th>
							<th>出货时间</th>
							<th>状态</th>
							<th>兑换时间</th>
						</tr>
						<?php foreach ($cate as $v): ?>
						<tr>
							<td><?php echo $v['gname']; ?></td>
							<td><?php echo $v['sscore']; ?></td>
							<td><?php echo unserialize($v['amark'])['address']; ?></td>
							<td><?php echo $v['ordername']?$v['ordername']:'--'; ?></td>
							<td><?php echo $v['ordernum']?$v['ordernum']:'--'; ?></td>
							<td><?php if($v['uptime']){echo date('Y-m-d',$v['uptime']);}elseif($v['puptime']){echo date('Y-m-d',$v['puptime']);}else{echo '--';} ?></td>
							<td><?php echo $v['status'] ? '已发货' : '待发货'; ?></td>
							<td><?php echo date('Y-m-d',$v['addtime']); ?></td>
						</tr>
						<?php endforeach; ?>
					</table>
					<div  class="lstPager" >
						<div class="layui-box layui-laypage layui-laypage-default">
						<?php echo $page; ?>
						<a href="javascript:;" class="layui-laypage-next">共 <?php echo $totals; ?> 条</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php include("foot.php") ?>
</body>
</html>