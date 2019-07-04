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
    <title>编辑订单</title>
	<link href="/src/css/layui.css" rel="stylesheet" />
    <script src="/src/layui.js"></script>

</head>
<body>
    <div class="main-wrap" style='padding:10px;'>
        <blockquote class="layui-elem-quote fhui-admin-main_hd">
            <h3>编辑订单</h3>
        </blockquote>
        <form action="/cate/oedit" class="layui-form layui-form-pane" id="formrec" method="post" role="form">
			<div class="layui-form-item">
				<input name='id' value='<?php echo $id?>' type='hidden' />
                <label class="layui-form-label">快递名称</label>
                <div class="layui-input-block">
                    <input name='ordername' autocomplete="off" lay-verify="required" value="" placeholder="必填,快递名称" class="layui-input" type="text">
                </div>
            </div>
			<div class="layui-form-item">
                <label class="layui-form-label">快递单号</label>
                <div class="layui-input-block">
                    <input name='ordernum' autocomplete="off" lay-verify="required" value="" placeholder="必填,快递单号" class="layui-input" type="text">
                </div>
            </div>
			<div class="layui-form-item">
                <label class="layui-form-label">地址</label>
                <div class="layui-input-block">
                    <input name='address' autocomplete="off" lay-verify="required" placeholder="必填,地址" class="layui-input" type="text" value="<?php echo $address;?>">
                </div>
            </div>
			<div class="layui-form-item">
                <label class="layui-form-label">状态</label>
                <div class="layui-input-block">
                  <input name="status" value="1" title="发货" type="radio" />
                </div>
            </div>
            <!--底部工具栏-->
            <div class="page-footer">
                <div class="btn-list">
                    <div class="btnlist">
                        <a class="layui-btn layui-btn-sm" lay-submit="" lay-filter="doPostParent" data-url="/cate/oedit"><i class="layui-icon">&#x1005;</i>提交</a>
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