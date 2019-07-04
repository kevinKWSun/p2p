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
    <title>编辑商品</title>
	<link href="/src/css/layui.css" rel="stylesheet" />
    <script src="/src/layui.js"></script>

</head>
<body>
    <div class="main-wrap" style='padding:10px;'>
        <blockquote class="layui-elem-quote fhui-admin-main_hd">
            <h3>编辑商品</h3>
        </blockquote>
        <form action="/cate/gedit" class="layui-form layui-form-pane" id="formrec" method="post" role="form">
            <div class="layui-form-item">
                <div class="layui-input-inline">
					<img id='img' src='<?php echo $img; ?>' width='100' />
                </div>
            </div>
			<input name='id' value='<?php echo $id?>' type='hidden' />
			<div class="layui-form-item">
                <label class="layui-form-label">商品名称</label>
                <div class="layui-input-block">
                    <input autocomplete="off" lay-verify="required" value="<?php echo $name?>" placeholder="必填,商品名称" class="layui-input" type="text" disabled>
                </div>
            </div>
			<div class="layui-form-item">
                <label class="layui-form-label">概率</label>
                <div class="layui-input-block">
                    <input autocomplete="off" lay-verify="required" value="<?php echo $probability?>" placeholder="必填,概率/%" class="layui-input" type="text" disabled>
                </div>
            </div>
			<div class="layui-form-item">
                <label class="layui-form-label">数量</label>
                <div class="layui-input-block">
                    <input autocomplete="off" lay-verify="required" value="<?php echo $num?>" placeholder="必填,数量" class="layui-input" type="text" disabled>
                </div>
            </div>
			<div class="layui-form-item">
                <label class="layui-form-label">状态</label>
                <div class="layui-input-block">
                  <input name="gstatus" value="1" <?php if($status == 1){ ?> checked=""<?php } ?> title="正常" type="radio" />
                  <input name="gstatus" value="0" <?php if($status == 0){ ?> checked=""<?php } ?> title="关闭" type="radio" />
                </div>
            </div>
            <!--底部工具栏-->
            <div class="page-footer">
                <div class="btn-list">
                    <div class="btnlist">
                        <a class="layui-btn layui-btn-sm" lay-submit="" lay-filter="doPost" data-url="/cj/gedit"><i class="layui-icon">&#x1005;</i>提交</a>
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