<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>福袋兑换-伽满优</title>
    <meta http-equiv="Content-Language" content="zh-cn" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <meta name="viewport" content="width=device-width, maximum-scale=1.0, initial-scale=1.0,initial-scale=1.0,user-scalable=no" />
    <meta name="apple-mobile-web-app-capabl卡片兑奖抽奖记录-伽满优,车贷理财,车辆抵押,P2P投资理财,投资理财公司,短期理财,P2P出借平台" />
	<meta name="Description" content="卡片兑奖-伽满优,通过公开透明的规范操作,平台为出借人士提供收益合理、安全可靠、高效灵活的车贷理财产品。" />
    <link href="/images/default.css" rel="stylesheet" type="text/css" />
	<link href="/images/index.css" rel="stylesheet" type="text/css" />
	<link href="/images/new/user-info.css" rel="stylesheet" type="text/css" />
	<link href="/images/new/date.css" rel="stylesheet" type="text/css" />
	<link href="/src/css/layui.css" rel="stylesheet" />
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
			<h2>福袋兑换</h2>
			<div class="jyjl">
				<div class="jyjl">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<th>福袋金额</th>
							<th>倍数</th>
							<th>状态</th>
							<th>处理时间</th>
							<th>时间</th>
							<th>备注</th>
						</tr>
						<?php foreach ($zcash as $v): ?>
						<tr>
							
							<td><?php echo $v['num']; ?>元</td>
							<td><?php echo $v['multiple'] == 2 ? '双倍' : '单倍'; ?></td>
							<td><?php echo $v['status'] ? '已处理' : '待处理'; ?></td>
							<td><?php if($v['puptime']){echo date('Y-m-d',$v['puptime']);}else{echo '--';} ?></td>
							<td><?php echo date('Y-m-d',$v['addtime']); ?></td>
							<td title="<?php echo $v['remark']; ?>"><?php echo mb_substr($v['remark'], 0, 35); ?></td>
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