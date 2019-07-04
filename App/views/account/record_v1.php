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
	<link href="/images/new/user-info.css" rel="stylesheet" type="text/css" />
    <link href="/src/css/layui.css" rel="stylesheet" />
    <link href="/images/default.css" rel="stylesheet" type="text/css" />
    <script src="/src/layui.js"></script>
	<script src="/images/jquery-1.7.2.min.js"></script>
</head>
<body>
<?php include("top.php") ?>
 <div class="center_v1">
	<div class="zhzx_v2">
		<div class="zhzx_l_v2">
			<?php include("left_v1.php") ?>
		</div>
		<div class="zhzx_r_v2">
			<h2>项目出借</h2>
			<div class="jyjl_nr_v2"><span>出借状态：</span>
				<a href="/record/index/1.html?query=0" class="<?php echo $status==0?'on':'';?>">全部</a>
				<a href="/record/index/1.html?query=2" class="<?php echo $status==2?'on':'';?>">投标中</a>
				<a href="/record/index/1.html?query=4" class="<?php echo $status==4?'on':'';?>">回款中</a>
				<a href="/record/index/1.html?query=5" class="<?php echo $status==5?'on':'';?>">还款完成</a>
			</div>
			<div class="lc_tb_di_v2">
				<table class="td2_v2" width="100%">
					<tbody>
						<tr>
							<td><img src="/images/new/image/title-icon.png"/><span>标题</span></td>
							<td><img src="/images/new/image/lilv-icon.png"/><span>年化利率(%)</span></td>
							<td><img src="/images/new/image/time-icon.png"/><span>借款期限(天)</span></td>
							<td><img src="/images/new/image/jine-icon.png"/><span>出借金额(元)</span></td>
							<td><img src="/images/new/image/benjin-icon.png"/><span>剩余本金(元)</span></td>
							<td><img src="/images/new/image/lixi-icon.png"/><span>剩余利息(元)</span></td>
							<td><img src="/images/new/image/time-icon.png"/><span>出借时间</span></td>
							<td><img src="/images/new/image/time-icon.png"/><span>还款时间</span></td>
							<td><img src="/images/new/image/caozuo-icon.png"/><span>操作</span></td>
						</tr>
						<?php if(empty($borrow)):?>
						<tr>
							<td colspan='9'>暂无数据！</td>
						</tr>
						<?php endif;?>
						<?php foreach($borrow as $v):?>
						<tr>
							<td><a href='/invest/show/<?php echo $v['borrow_id']?>.html' target='_blank'><?php echo $v['borrow_name']?></a></td>
							<td><?php echo $v['borrow_interest_rate']+$v['add_rate']; ?></td>
							<td><?php echo $this->config->item('borrow_duration')[$v['borrow_duration']]; ?></td>
							<td><?php echo $v['investor_capital']; ?></td>
							<td><?php echo sprintf('%.2f', $v['investor_capital']-$v['receive_capital']); ?></td>
							<td><?php echo sprintf('%.2f', $v['investor_interest']-$v['receive_interest']);?></td></td>
							<td><?php echo $v['endtime']?date('Y-m-d', $v['endtime']):'--'; ?></td>
							<td><?php echo $v['deadline']?date('Y-m-d', $v['deadline']):'--'; ?></td>
							<td>
							<?php if($v['borrow_status'] >= 4 && $v['borrow_status'] < 10){?>
								<a href="#" class="js-path" data-href='/record/agreement/<?php echo $v['invest_id']?>/1.pdf'>借款合同</a>
								<a href="#" class="js-path" data-href='/record/agreement/<?php echo $v['invest_id']?>/2.pdf'>居间合同</a>
							<?php }else{echo '--';}?>
							</td>
						</tr>
						<?php endforeach;?>
					</tbody>
				</table>
				<div>
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