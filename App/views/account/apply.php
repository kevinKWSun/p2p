<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>借款申请-伽满优</title>
    <meta http-equiv="Content-Language" content="zh-cn" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <meta name="viewport" content="width=device-width, maximum-scale=1.0, initial-scale=1.0,initial-scale=1.0,user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="Keywords" content="借款申请-伽满优,车贷理财,车辆抵押,P2P投资理财,投资理财公司,短期理财,P2P投资理财平台" />
	<meta name="Description" content="借款申请-伽满优,通过公开透明的规范操作,平台为投资理财人士提供收益合理、安全可靠、高效灵活的车贷理财产品。" />
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
		<i class="icon"></i><a href="/">首页</a> > <a href="/account.html"> 我的账户 </a> > 借款申请
	</div>
	<div class="zhzx">
		<div class="zhzx_l">
			<?php include("left.php") ?>
		</div>
		<div class="zhzx_r">
			<h2>借款申请</h2>
			<form class="layui-form" id="formrec" method="post" role="form" style="margin-top:25px;">
				<div class="layui-form-item">
					<div class="layui-inline">
						<label class="layui-form-label">借款金额</label>
						<div class="layui-input-inline">
							<input type="tel" name="money" lay-verify="required" autocomplete="off" class="layui-input" onkeyup="this.value=this.value.replace(/\D/g,''); " maxlength="9" placeholder="最小借款金额10000元">
						</div>
						<div class="layui-form-mid layui-word-aux">元</div>
					</div>
				</div>
				<div class="layui-form-item layui-form-text">
					<label class="layui-form-label">借款原因</label>
					<div class="layui-input-block">
						<textarea name="remark" lay-verify="required" placeholder="请输入内容" class="layui-textarea"></textarea>
					</div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block">
						<button class="layui-btn" lay-submit="" lay-filter="doPost" data-url="/apply.html" style="background-color:#fc501b"><i class="layui-icon">&#x1005;</i>提交</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<?php include("foot.php") ?>
<script src="/src/global.js"></script>
</body>
</html>