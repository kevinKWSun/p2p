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
    <title>投资红包</title>
    <link href="/src/css/layui.css" rel="stylesheet" />
    <script src="/src/layui.js"></script>
</head>
<body>
    <div class="main-wrap" style='padding:10px;'>
        <blockquote class="layui-elem-quote fhui-admin-main_hd">
            <h2>投资红包</h2>
        </blockquote>
        <form action="/member/hb" class="layui-form layui-form-pane" id="formrec" method="post" role="form">
			<input type="hidden" name="uid" value="<?php echo $meminfo['uid']; ?>" />
            <div class="layui-form-item">
                <label class="layui-form-label">会员姓名</label>
				<div class="layui-input-inline">
                    <input value="<?php echo $meminfo['real_name'];?>" class="layui-input layui-btn-disabled" type="text" disabled />
                </div>
				
            </div>
			<div class="layui-form-item">
                <label class="layui-form-label">红包金额</label>
                <div class="layui-input-inline">
                    <input name="money" autocomplete="off" lay-verify="required|number" placeholder="必填,红包金额(有效期一个月)" class="layui-input" type="text" maxlength="4" />
                </div>
            </div>
			<div class="layui-form-item">
                <label class="layui-form-label">投资期限</label>
                <div class="layui-input-inline">
                    <input name="times" autocomplete="off" lay-verify="required|number" placeholder="必填,大与多少天可用" class="layui-input" type="text" maxlength="2" />
                </div>
            </div>
			<div class="layui-form-item">
                <label class="layui-form-label">投资金额</label>
                <div class="layui-input-inline">
                    <input name="moneys" autocomplete="off" lay-verify="required|number" placeholder="必填,最小投资金额" class="layui-input" type="text" maxlength="8" />
                </div>
            </div>
            <!--底部工具栏-->
            <div class="page-footer">
                <div class="btn-list">
                    <div class="btnlist">
                        <a class="layui-btn layui-btn-sm" lay-submit="" lay-filter="doPostParent" data-url="/member/hb.html"><i class="layui-icon">&#x1005;</i>提交</a>
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
