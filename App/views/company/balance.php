<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Language" content="zh-cn" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <meta name="viewport" content="width=device-width, maximum-scale=1.0, initial-scale=1.0,initial-scale=1.0,user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <title>收款企业信息</title>
	<link href="/src/css/layui.css" rel="stylesheet" />
    <script src="/src/layui.js"></script>
</head>
<body>
	<fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
		<legend>账户信息</legend>
	</fieldset>
	<form class="layui-form layui-form-pane"> 
		<div class="layui-form-item">
			<label class="layui-form-label">可用余额</label>
			<div class="layui-input-block">
				<input type="text" value="<?php if(isset($body['actualAmt'])) echo $body['actualAmt']; ?>元"  class="layui-input"/>
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">冻结余额</label>
			<div class="layui-input-block layui-disabled">
				<input type="text" value="<?php if(isset($body['actualAmt'])) echo $body['pledgedAmt']; ?>元"  class="layui-input"/>
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">投标金额</label>
			<div class="layui-input-block layui-disabled">
				<input type="text" value="<?php if(isset($body['actualAmt'])) echo $body['preLicAmt']; ?>元"  class="layui-input"/>
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">总余额</label>
			<div class="layui-input-block layui-disabled">
				<input type="text" value="<?php if(isset($body['actualAmt'])) echo $body['totalAmt']; ?>元"  class="layui-input"/>
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">账户状态</label>
			<div class="layui-input-block layui-disabled">
				<input type="text" value="<?php if(isset($body['actualAmt'])) echo $body['acctStatus'] === '00' ? '启用' : '禁用'; ?>"  class="layui-input"/>
			</div>
		</div>
	</form>
</body>
</html>