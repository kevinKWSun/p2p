<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>项目出借-伽满优</title>
    <meta http-equiv="Content-Language" content="zh-cn" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <meta name="viewport" content="width=device-width, maximum-scale=1.0, initial-scale=1.0,initial-scale=1.0,user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="Keywords" content="项目投资-伽满优,车贷理财,车辆抵押,P2P投资理财,投资理财公司,短期理财,P2P投资理财平台" />
	<meta name="Description" content="项目投资-伽满优,通过公开透明的规范操作,平台为投资理财人士提供收益合理、安全可靠、高效灵活的车贷理财产品。" />
	<link href="/images/index.css" rel="stylesheet" type="text/css" />
	<link href="./images/CenterIndex.css" rel="stylesheet" type="text/css" />
    <link href="/src/css/layui.css" rel="stylesheet" />
    <link href="/images/default.css" rel="stylesheet" type="text/css" />
    <script src="/src/layui.js"></script>
</head>
<body>
<?php include("top.php") ?>
 <div class="cent">
	<div class="dqwz">
		<i class="icon"></i><a href="/">首页</a> > <a href="/account.html"> 我的账户 </a> > 项目出借
	</div>
	<div class="zhzx">
		<div class="zhzx_l">
			<?php include("left.php") ?>
		</div>
		<div class="zhzx_r">
			<h2>项目出借</h2>
			<div class="jyjl">
				<div class="jyjl_top">
					<div class="jyjl_nr"><span>状态：</span>
						<a href="/record/index/1.html?query=2" class="<?php echo $status==2?'on':'';?>">出借中</a>
						<a href="/record/index/1.html?query=4" class="<?php echo $status==4?'on':'';?>">回款中</a>
						<a href="/record/index/1.html?query=5" class="<?php echo $status==5?'on':'';?>">还款完成</a>
					</div>
				</div>
				<div class="jyjl">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<th>标题</th>
							<th>年化利率(%)</th>
							<th>出借金额(元)</th>
							<th>剩余本金(元)</th>
							<th>剩余利息(元)</th>
							<th>还款时间</th>
							<th>操作</th>
						</tr>
						<?php foreach($borrow as $v):?>
						<tr>
							<td><a href='/invest/show/<?php echo $v['borrow_id']?>.html' target='_blank'><?php echo $v['borrow_name']?></a></td>
							<td><?php echo $v['borrow_interest_rate']; ?></td>
							<td><?php echo $v['investor_capital']; ?></td>
							<td><?php echo sprintf('%.2f', $v['investor_capital']-$v['receive_capital']); ?></td>
							<td><?php echo sprintf('%.2f', $v['investor_interest']-$v['receive_interest']);?></td></td>
							<td><?php echo $v['deadline']?date('Y-m-d', $v['deadline']):'--'; ?></td>
							<td>
							<?php if($status >= 4 && $status < 10){?>
								<a href="#" class="js-path" data-href='/record/agreement/<?php echo $v['invest_id']?>/1.pdf'>借款合同</a>
								<a href="#" class="js-path" data-href='/record/agreement/<?php echo $v['invest_id']?>/2.pdf'>居间合同</a>
							<?php }else{echo '--';}?>
							</td>
						</tr>
						<?php endforeach;?>
					</table>
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
<script type="text/javascript">
	layui.use(['layer'], function() {
		var $ = layui.$
		, layer = layui.layer;
		$('.js-path').click(function() {
			var href = $(this).data('href');
			console.log(href);
			layer.open({
				type: 2,
				title: '借款合同',
				shade: 0.1,
				maxmin: true,
				area: ['45%', '90%'],
				fixed: true,
				content: href
			});
			return;
		});
	});
</script>
</html>