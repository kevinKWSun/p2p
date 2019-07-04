<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>还款列表-伽满优</title>
    <meta http-equiv="Content-Language" content="zh-cn" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <meta name="viewport" content="width=device-width, maximum-scale=1.0, initial-scale=1.0,initial-scale=1.0,user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="Keywords" content="还款列表-伽满优,车贷理财,车辆抵押,P2P投资理财,投资理财公司,短期理财,P2P投资理财平台" />
	<meta name="Description" content="还款列表-伽满优,通过公开透明的规范操作,平台为投资理财人士提供收益合理、安全可靠、高效灵活的车贷理财产品。" />
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
		<i class="icon"></i><a href="/">首页</a> > <a href="/account.html"> 我的账户 </a> > 还款列表
	</div>
	<div class="zhzx">
		<div class="zhzx_l">
			<?php include("left.php") ?>
		</div>
		<div class="zhzx_r">
			<h2>还款列表</h2>
			<div class="jyjl">
				<div class="jyjl">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<!--<th>标名</th>
							<th>金额/万</th>
							<th>期限</th>
							<th>还款方式</th>-->
							<th>投资人</th>
							<th>应还金额（利息+本金）</th>
							<th>已还金额</th>
							<th>期数/总期数</th>
							<th>还款日期</th>
							<th>状态</th>
							<th>操作</th>
						</tr>
						<?php if(! $details){ ?>
							<tr>
								<td class="nodata" colspan="9" align='center'>暂无数据！</td>
							</tr>
						<?php }else{foreach ($details as $v): ?>
							<tr>
								<td>
									<?php 
										echo $v['investor_name'];
									?>
								</td>
								<td>
									<?php if($v['sort_order'] < $v['total']) { ?>
										<?php echo round($v['interest'], 2); ?>元(<?php echo round($v['interest'], 2); ?>元 + 0.00元)
									<?php } else { ?>
										<?php echo round($v['capital'] + $v['interest'], 2); ?>元(<?php echo round($v['interest'], 2); ?>元 + <?php echo round($v['capital'], 2); ?>元)
									<?php } ?>
								</td>
								<td><?php echo round($v['receive_interest'] + $v['receive_capital'], 2); ?>元</td>
								<td><?php echo round($v['sort_order'], 2); ?>/<?php echo round($v['total'], 2); ?></td>
								<td><?php echo date('Y-m-d', $v['deadline']); ?></td>
								<td><?php echo $this->config->item('borrow_status')[$v['status']]; ?></td>
								<td>
									<?php if($v['repayment_time'] > 0) { ?>
										已还
									<?php } else { ?>
										<a class="js-prpayment" data-href="/apply/repayment/<?php echo $v['id']; ?>.html" data-id="<?php echo $v['id']; ?>"><i class="icon-edit fa fa-dollar"></i>还　款</a>
									<?php } ?>
									
								</td>
							</tr>
						<?php endforeach;} ?>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<?php include("foot.php") ?>
<script src="/src/global.js"></script>
<script type="text/javascript">
	layui.use(['layer', 'form'], function(){
		var $ = layui.$
		, form = layui.form
		, layer = layui.layer;
		$('.js-prpayment').on('click', function() {
			var url = $(this).data('href');
			layer.open({
				type: 2,
				title: '投资',
				shade: 0.1,
				maxmin: true,
				area: ['50%', '90%'],
				fixed: true,
				content: url
			});
		});
	});
		
</script>
</body>
</html>