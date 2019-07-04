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
    <link href="/src/css/layui.css" rel="stylesheet" />
	<link href="/images/new/user-info.css" rel="stylesheet" type="text/css" />
	<script src="/images/jquery-1.7.2.min.js"></script>
    <script src="/src/layui.js"></script>
</head>
<body>
<?php include("top.php") ?>
 <div class="cent_v2">
	<div class="zhzx_v2">
		<div class="zhzx_l_v2">
			<?php include("left_v1.php") ?>
		</div>
		<div class="zhzx_r_v2">
			<h2>兑换记录</h2>
			<div class="lc_tb_di_v2">
				<table class="td2_v2" width="100%">
					<tr>
						<td><img src="/images/new/image/commodity-icon.png" /><span>商品名称</span></td>
						<td><img src="/images/new/image/integral-icon.png" /><span>实付积分</span></td>
						<td><img src="/images/new/image/address-icon.png" /><span>邮寄地址</span></td>
						<td><img src="/images/new/image/kuaidi-icon.png" /><span>快递名称</span></td>
						<td><img src="/images/new/image/number-icon.png" /><span>快递单号</span></td>
						<td><img src="/images/new/image/pai-time-icon.png" /><span>出货时间</span></td>
						<td><img src="/images/new/image/state-icon.png" /><span>状态</span></td>
						<td><img src="/images/new/image/ch-time-icon.png" /><span>兑换时间</span></td>
					</tr>
					<?php if (empty($cate)): ?>
					<tr>
						<td colspan="8">暂无数据！</td>
					</tr>
					<?php endif; ?>
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
						</td>
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
<?php include("foot.php") ?>
</body>
</html>