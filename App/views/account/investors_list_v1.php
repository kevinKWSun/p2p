<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>出借人列表-伽满优</title>
    <meta http-equiv="Content-Language" content="zh-cn" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <meta name="viewport" content="width=device-width, maximum-scale=1.0, initial-scale=1.0,initial-scale=1.0,user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="Keywords" content="出借人列表-伽满优,车贷理财,车辆抵押,P2P投资理财,投资理财公司,短期理财,P2P投资理财平台" />
	<meta name="Description" content="出借人列表-伽满优,通过公开透明的规范操作,平台为投资理财人士提供收益合理、安全可靠、高效灵活的车贷理财产品。" />
    <link href="/images/default.css" rel="stylesheet" type="text/css" />
	<link href="/images/index.css" rel="stylesheet" type="text/css" />
	<link href="/images/new/user-info.css" rel="stylesheet" type="text/css" />
    <link href="/src/css/layui.css" rel="stylesheet" />
    <script src="/src/layui.js"></script>
	<script src="/images/jquery-1.7.2.min.js"></script>
</head>
<body>
<?php include("top.php") ?>
 <div class="cent_v2">
	<div class="zhzx_v2">
		<div class="zhzx_l_v2">
			<?php include("left_v1.php") ?>
		</div>
		<div class="zhzx_r_v2">
			<h2>出借人列表</h2>
			<div class="lc_tb_di_v2">
				<table class="td2_v2" width="100%" cellspacing="0" cellpadding="0" border="0">
					<tbody>
						<tr>
							<td><img src="/images/new/image/head-icon.png" /><span>真实姓名</span></td>
							<td><img src="/images/new/image/iphone-icon.png" /><span>手机号</span></td>
							<td><img src="/images/new/image/time-icon.png" /><span>注册时间</span></td>
						</tr>
						<?php foreach($investor as $v):?>
						<tr>
							<td><?php echo get_member_info($v['id'])['real_name']?></td>
							<td><?php echo $v['user_name']?></td>
							<td><?php echo date('Y-m-d', $v['reg_time'])?></td>
						</tr>
						<?php endforeach;?>
					</tbody>
				</table>
				<div>
					<div class="layui-box layui-laypage layui-laypage-default"><!--span class="PageEndStyle">&lt;</span><span class="SelectIndexStyle">1</span><span class="PageEndStyle">&gt;</span-->
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