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
    <title>企业注册</title>
	<link href="/src/css/layui.css" rel="stylesheet" />
    <script src="/src/layui.js"></script>

</head>
<body>
    <div class="main-wrap" style='padding:10px;'>
        <blockquote class="layui-elem-quote fhui-admin-main_hd">
            <h3>企业注册</h3>
        </blockquote>
        <form action="/company/add" class="layui-form layui-form-pane" id="formrec" method="post" role="form">
            <div class="layui-form-item">
                <label class="layui-form-label">企业名称</label>
                <div class="layui-input-block">
                    <input name="user_name" autocomplete="off" lay-verify="required" value="" placeholder="必填,企业名称" class="layui-input" type="text">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">联系手机</label>
                <div class="layui-input-block">
                    <input name="phone" autocomplete="off" lay-verify="required" value="" placeholder="必填,联系手机" class="layui-input" type="text">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">企业号码</label>
                <div class="layui-input-block">
                    <input name="card" autocomplete="off" lay-verify="required" value="" placeholder="必填,企业号码(营业执照)" class="layui-input" type="text">
                </div>
            </div>
			<div class="layui-form-item">
                <label class="layui-form-label">客户号码</label>
                <div class="layui-input-block">
                    <input name="custNo" autocomplete="off" lay-verify="required" value="" placeholder="必填,客户号码" class="layui-input" type="text" maxlength="18">
                </div>
            </div>
			<div class="layui-form-item">
                <label class="layui-form-label">账户号码</label>
                <div class="layui-input-block">
                    <input name="acctNo" autocomplete="off" lay-verify="required" value="" placeholder="必填,账户号码" class="layui-input" type="text" maxlength="13">
                </div>
            </div>
			<div class="layui-form-item">
                <label class="layui-form-label">支付密码</label>
                <div class="layui-input-block">
                    <input name="ppwd" autocomplete="off" lay-verify="required" value="" placeholder="必填,支付密码" class="layui-input" type="text">
                </div>
            </div>
			<div class="layui-form-item">
                <label class="layui-form-label">登录密码</label>
                <div class="layui-input-block">
                    <input name="pwd" autocomplete="off" lay-verify="required" value="" placeholder="必填,会员中心登录密码" class="layui-input" type="text">
                </div>
            </div>
            <!--底部工具栏-->
            <div class="page-footer">
                <div class="btn-list">
                    <div class="btnlist">
                        <a class="layui-btn layui-btn-sm" lay-submit="" lay-filter="doPost" data-url="/company/add"><i class="layui-icon">&#x1005;</i>提交</a>
                        <a class="layui-btn layui-btn-sm do-action" data-type="doRefresh" data-url=""><i class="layui-icon">&#xe669;</i>刷新</a>
                        <a class="layui-btn layui-btn-sm do-action" data-type="doGoBack"><i class="layui-icon">&#xe65c;</i>返回上一页</a>
                        <a class="layui-btn layui-btn-sm do-action" data-type="doGoTop" data-url=""><i class="layui-icon">&#xe604;</i>返回顶部</a>
                    </div>
                </div>
            </div>
            <!--/底部工具栏-->
        </form>
    </div>
    <script src="/src/global.js"></script>
</body>
</html>