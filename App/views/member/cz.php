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
    <title>充值补差</title>
    <link href="/src/css/layui.css" rel="stylesheet" />
    <script src="/src/layui.js"></script>
</head>
<body>
    <div class="main-wrap" style='padding:10px;'>
        <blockquote class="layui-elem-quote fhui-admin-main_hd">
            <h2>充值补差</h2>
        </blockquote>
        <form action="/member/cz" class="layui-form layui-form-pane" id="formrec" method="post" role="form">
			<input type="hidden" name="uid" value="<?php echo $meminfo['uid']; ?>" />
            <div class="layui-form-item">
                <label class="layui-form-label">会员姓名</label>
				<div class="layui-input-inline">
                    <input value="<?php echo $meminfo['real_name'];?>" class="layui-input layui-btn-disabled" type="text" disabled />
                </div>
				
            </div>
			<div class="layui-form-item">
                <label class="layui-form-label">可用金额</label>
				<div class="layui-input-inline">
                    <input value="<?php echo $meminfo['account_money'];?>" class="layui-input layui-btn-disabled" type="text" disabled />
					<input name='account_money' value="<?php echo $meminfo['account_money'];?>" type='hidden' />
                </div>
            </div>
			<div class="layui-form-item">
                <label class="layui-form-label">电子金额</label>
				<div class="layui-input-inline">
                    <input value="<?php echo $actualAmt;?>" class="layui-input layui-btn-disabled" type="text" disabled />
                </div>
            </div>
			<div class="layui-form-item">
                <label class="layui-form-label">增加差额</label>
                <div class="layui-input-inline">
                    <input name="score" autocomplete="off" lay-verify="required|number" placeholder="必填,增加差额" class="layui-input" type="text" maxlength="8" value="<?php echo ($actualAmt-$meminfo['account_money']);?>" readonly />
                </div>
            </div>
            <!--底部工具栏-->
            <div class="page-footer">
                <div class="btn-list">
                    <div class="btnlist">
                        <a class="layui-btn layui-btn-sm" lay-submit="" lay-filter="doPostParent" data-url="/member/cz.html"><i class="layui-icon">&#x1005;</i>提交</a>
                        <a class="layui-btn layui-btn-sm do-action" data-type="doRefresh" data-url=""><i class="layui-icon">&#xe669;</i>刷新</a>
                    </div>
                </div>
            </div>
            <!--/底部工具栏-->
        </form>
        
    </div>
	<script src="/src/global.js"></script>
</body>
</html>
