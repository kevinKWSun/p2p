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
    <title>添加合同</title>
	<link href="/src/css/layui.css" rel="stylesheet" />
    <script src="/src/layui.js"></script>
	<script type="text/javascript" charset="utf-8" src="/src/ue/ueditor.config.js"></script>
    <script type="text/javascript" charset="utf-8" src="/src/ue/ueditor.all.min.js"> </script>

</head>
<body>
    <div class="main-wrap" style='padding:10px;'>
        <blockquote class="layui-elem-quote fhui-admin-main_hd">
            <h3>添加合同</h3>
        </blockquote>
        <form action="/contract/add" class="layui-form layui-form-pane" id="formrec" method="post" role="form">
            <div class="layui-form-item">
				<div class="layui-inline">
					<label class="layui-form-label">合同名称</label>
					<div class="layui-input-block">
						<input name="name" autocomplete="off" lay-verify="required" value="" placeholder="必填,合同名称" class="layui-input" type="text">
					</div>
				</div>
				<div class="layui-inline">
					<label class="layui-form-label">类型</label>
					<div class="layui-input-block" style="z-index:1000;">
						<select data-val="true" data-val-number="字段 Int32 必须是一个数字" data-val-required="Int32 字段是必需的" name="genre">
							<option selected="selected" value="0">选择类型</option>
							<option value="服务合同">服务合同</option>
							<option value="投资合同">投资合同</option>
						</select>
					</div>
				</div>
				<div class="layui-inline">
					<label class="layui-form-label">启用状态</label>
					<div class="layui-input-block" style="z-index:1000;">
						<select data-val="true" data-val-number="字段 Int32 必须是一个数字" data-val-required="Int32 字段是必需的" name="status">
							<option selected="selected" value="-1">选择类型</option>
							<option value="0">禁用</option>
							<option value="1" selected>启用</option>
						</select>
					</div>
				</div>
            </div>
			<div class="layui-form-item">
				<fieldset class="layui-elem-field layui-field-title">
					<legend>内容</legend>
				</fieldset>
				<script id="editor" type="text/plain" style="width:100%;height:500px;"></script>
            </div>
            <!--底部工具栏-->
            <div class="page-footer">
                <div class="btn-list">
                    <div class="btnlist">
						<?php if(is_rule('/contract/add')) { ?>
							<a class="layui-btn layui-btn-sm" lay-submit="" lay-filter="doPost" data-url="/contract/add"><i class="layui-icon">&#x1005;</i>提交</a>
						<?php } ?>
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
	<script type="text/javascript">
		var ue = UE.getEditor('editor');
	</script>
</body>
</html>