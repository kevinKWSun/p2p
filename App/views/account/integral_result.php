<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>出借者风险承受能力调查评估表-伽满优</title>
    <meta http-equiv="Content-Language" content="zh-cn" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <meta name="viewport" content="width=device-width, maximum-scale=1.0, initial-scale=1.0,initial-scale=1.0,user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="Keywords" content="充值-伽满优,车贷理财,车辆抵押,P2P出借理财,出借理财公司,短期理财,P2P出借理财平台" />
	<meta name="Description" content="充值-伽满优,通过公开透明的规范操作,平台为出借理财人士提供收益合理、安全可靠、高效灵活的车贷理财产品。" />
    <link href="/src/css/layui.css" rel="stylesheet" />
    <script src="/src/layui.js"></script>
	<style>
		.layui-input-block {
			margin-left: 35px;
			line-height: 25px;
			padding-right: 35px;
		}
		#formrec .content {
			padding-left: 30px;
		}
		#formrec .content label{
			width: 100%;
		}
		#formrec .content span{
			display: inline-block;
			float: right;
			color: #666;
			
		}
	</style>
</head>

<body>
<div class="main-wrap" style='padding:10px;'>
	<blockquote class="layui-elem-quote" style='border-left:5px solid #FF5722'>
		<h3>出借者风险承受能力调查评估表</h3>
	</blockquote>
	<div class="y-role">
		<div class="fhui-admin-table-container">
			<form id="formrec" method="post" role="form" class="layui-form" onsubmit="return false;">
				<div class="layui-form-item">
					<div class="layui-input-block">
						<h2><strong>客户姓名：<?php echo $meminfo['real_name']; ?></strong></h2>
					</div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block">
						<h3><strong>二、调查评估结果：</strong></h3>
					</div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block content">您的得分总计为：<?php echo $meminfo['integral']; ?>分
					</div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block content">评估结果，您的风险承受能力等级为：
					</div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block content">
						
						<?php if($type == 1) { ?>
							<input type="checkbox" lay-skin="primary"  title="积极型" disabled  checked />
						<?php } ?>
						<?php if($type == 2) { ?>
							<input type="checkbox" lay-skin="primary"  title="稳健型" disabled  checked />
						<?php } ?>
						<?php if($type == 3) { ?>
							<input type="checkbox" lay-skin="primary"  title="保守型" disabled  checked />
						<?php } ?>
					</div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block content">风险出借金额为：<?php echo $fanwei; ?>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<script src="/src/global.js"></script>

</body>
</html>