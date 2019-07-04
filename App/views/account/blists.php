<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>借款列表-伽满优</title>
    <meta http-equiv="Content-Language" content="zh-cn" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <meta name="viewport" content="width=device-width, maximum-scale=1.0, initial-scale=1.0,initial-scale=1.0,user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="Keywords" content="借款列表-伽满优,车贷理财,车辆抵押,P2P投资理财,投资理财公司,短期理财,P2P投资理财平台" />
	<meta name="Description" content="借款列表-伽满优,通过公开透明的规范操作,平台为投资理财人士提供收益合理、安全可靠、高效灵活的车贷理财产品。" />
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
		<i class="icon"></i><a href="/">首页</a> > <a href="/account.html"> 我的账户 </a> > 借款列表
	</div>
	<div class="zhzx">
		<div class="zhzx_l">
			<?php include("left.php") ?>
		</div>
		<div class="zhzx_r">
			<h2>借款列表</h2>
			<div class="jyjl">
				<div class="jyjl">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<th>标名</th>
							<th>金额/万</th>
							<th>期限</th>
							<th>到期日期</th>
							<th>还款方式</th>
							<th>状态</th>
							<th>操作</th>
						</tr>
						<?php if(! $borrow){ ?>
							<tr>
								<td class="nodata" colspan="9" align='center'>暂无数据！</td>
							</tr>
						<?php } else { foreach ($borrow as $v) { ?>
							<tr>
								<td><?php echo $v['borrow_name']; ?></td>
								<td><?php echo $v['borrow_money'] / 10000; ?>万</td>
								<td><?php echo $this->config->item('borrow_duration')[$v['borrow_duration']]; ?>天</td>
								<td><?php echo $v['endtime']?date('Y-m-d',$v['endtime']+$v['total']*86400-86400):'无'; ?></td>
								<td><?php echo $this->config->item('repayment_type')[$v['repayment_type']]; ?></td>
								<td><?php echo $this->config->item('borrow_status')[$v['borrow_status']]; ?></td>
								<td>
									<?php if($v['borrow_status'] == 4){ ?>
										<a class="do-action" data-type="doAdd" data-url="/apply/repaylist/<?php echo $v['id']; ?>.html"><i class="icon-edit fa fa-dollar"></i>还　款</a>
									<?php } else { ?>
										<a href='/invest/show/<?php echo $v['id']; ?>.html' target='_blank'>
											<i class="icon-edit  fa fa-search"></i>
											查　看
										</a>
									<?php } ?>
								</td>
							</tr>
						<?php } ?>
						<?php } ?>
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
<script src="/src/global.js"></script>
</body>
</html>