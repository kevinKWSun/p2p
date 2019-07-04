<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>新增地址-伽满优</title>
    <meta http-equiv="Content-Language" content="zh-cn" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <meta name="viewport" content="width=device-width, maximum-scale=1.0, initial-scale=1.0,initial-scale=1.0,user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="Keywords" content="新增地址-伽满优,车贷理财,车辆抵押,P2P投资理财,投资理财公司,短期理财,P2P投资理财平台" />
	<meta name="Description" content="新增地址-伽满优,通过公开透明的规范操作,平台为投资理财人士提供收益合理、安全可靠、高效灵活的车贷理财产品。" />
    <link href="/src/css/layui.css" rel="stylesheet" />
    <script src="/src/layui.js"></script>
</head>
<body>
	<div class="main-wrap" style='padding:10px;'>
		<form class="layui-form layui-form-pane" id="formrec" method="post" role="form">
			<div class="layui-form-item">
				<label class="layui-form-label">姓名</label>
				<div class="layui-input-block">
					<input type="text" name="realname" lay-verify="required" autocomplete="off" class="layui-input" placeholder="收货姓名">
				</div>
			</div>
			<div class="layui-form-item">
				<label class="layui-form-label">手机</label>
				<div class="layui-input-block">
					<input type="text" name="tel" lay-verify="number" autocomplete="off" class="layui-input" onkeyup="this.value=this.value.replace(/\D/g,''); " maxlength="11" placeholder="收货联系方式">
				</div>
			</div>
			<div class="layui-form-item">
				<label class="layui-form-label">地址</label>
				<div class="layui-input-block">
					<input type="text" name="address" lay-verify="required" autocomplete="off" class="layui-input" placeholder="收货详情地址">
				</div>
			</div>
			<div class="page-footer">
                <div class="btn-list">
                    <div class="btnlist">
                        <center><a class="layui-btn  layui-btn-danger" lay-submit="" lay-filter="doPostParent" data-url="/mall/add.html"><i class="layui-icon">&#x1005;</i>提交</a></center>
                    </div>
                </div>
            </div>
		</form>
	</div>
<script src="/src/global.js"></script>
</body>
</html>