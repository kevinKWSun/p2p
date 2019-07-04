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
    <title>修改推荐人</title>
    <link href="/src/css/layui.css" rel="stylesheet" />
    <script src="/src/layui.js"></script>
</head>
<body>
    <div class="main-wrap" style='padding:10px;'>
        <blockquote class="layui-elem-quote fhui-admin-main_hd">
            <h2>修改推荐人</h2>
        </blockquote>
        <form action="/member/upcode" class="layui-form layui-form-pane" id="formrec" method="post" role="form">
			
			<div class="layui-form-item">
                <label class="layui-form-label">注册手机号</label>
				<div class="layui-input-block">
                    <input value="" class="layui-input" lay-verify="required|number" type="text" name='phones' placeholder="必填,要修改人员的注册手机号,多个用英文','号隔开" />
                </div>
            </div>
			<div class="layui-form-item">
                <label class="layui-form-label">推荐邀请码</label>
                <div class="layui-input-inline">
                    <input name="codeuid" autocomplete="off" lay-verify="required|number" placeholder="必填,推荐邀请码(000001)" class="layui-input" type="text" maxlength="6" />
                </div>
            </div>
            <!--底部工具栏-->
            <div class="page-footer">
                <div class="btn-list">
                    <div class="btnlist">
                        <a class="layui-btn layui-btn-sm" lay-submit="" lay-filter="doPostParent" data-url="/member/upcode.html"><i class="layui-icon">&#x1005;</i>提交</a>
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
