<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>我的地址-伽满优</title>
    <meta http-equiv="Content-Language" content="zh-cn" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <meta name="viewport" content="width=device-width, maximum-scale=1.0, initial-scale=1.0,initial-scale=1.0,user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="Keywords" content="我的地址-伽满优,车贷理财,车辆抵押,P2P投资理财,投资理财公司,短期理财,P2P投资理财平台" />
	<meta name="Description" content="我的地址-伽满优,通过公开透明的规范操作,平台为投资理财人士提供收益合理、安全可靠、高效灵活的车贷理财产品。" />
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
		<i class="icon"></i><a href="/">首页</a> > <a href="/account.html"> 我的账户 </a> > 我的地址
	</div>
	<div class="zhzx">
		<div class="zhzx_l">
			<?php include("left.php") ?>
		</div>
		<div class="zhzx_r">
			<h2>我的地址 <button class="layui-btn layui-btn-sm layui-btn-danger">新增</button></h2>
			<div class="jyjl">
				<div class="jyjl">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<th>姓名</th>
							<th>手机</th>
							<th>地址</th>
							<th>修改</th>
						</tr>
						<?php foreach($borrow as $v):?>
						<tr>
							<td><?php echo $v['realname']; ?></td>
							<td><?php echo $v['tel']; ?></td>
							<td><?php echo $v['address']; ?></td>
							<td><a href='javascript:;' data-id="<?php echo $v['id']; ?>">修改</a></td>
						</tr>
						<?php endforeach;?>
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
<script type="text/javascript">
	layui.use(['layer', 'form', 'laydate'], function () {
		var $ = layui.$
		, layer = layui.layer
		, form = layui.form;
		$('button.layui-btn-danger').on('click', function(){
			layer.open({
				type: 2,
				title: '增加地址',
				//shadeClose: true,
				shade: 0.1,
				maxmin: true,
				area: ['30%', '30%'],
				fixed: true,
				content: '/mall/add.html'
			});
		});
		$('td a').on('click', function(){
			var id = $(this).attr('data-id');
			layer.open({
				type: 2,
				title: '修改地址',
				//shadeClose: true,
				shade: 0.1,
				maxmin: true,
				area: ['30%', '30%'],
				fixed: true,
				content: '/mall/edit/'+id+'.html'
			});
		});
	});		
</script>
</body>
</html>