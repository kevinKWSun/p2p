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
    <title>编辑规则</title>
	<link href="/src/css/layui.css" rel="stylesheet" />
    <script src="/src/layui.js"></script>

</head>
<body>
    <div class="main-wrap" style='padding:10px;'>
        <blockquote class="layui-elem-quote fhui-admin-main_hd">
            <h3>编辑规则</h3>
        </blockquote>
        <form action="/rule/edit" class="layui-form layui-form-pane" id="formrec" method="post" role="form">
            <input type='hidden' name='d' value="<?php echo $id; ?>" />
            <div class="layui-form-item">
                <label class="layui-form-label">所属权限</label>
                <div class="layui-input-block">
                    <select data-val="true" data-val-number="字段 Int32 必须是一个数字" data-val-required="Int32 字段是必需的" name="gids">
                        <option selected="selected" value="0">选择所属权限</option>
                        <?php foreach ($rule as $v): ?>
                        <option value="<?php echo $v['id']; ?>" <?php if($v['id'] == $pid){?>selected<?php }?>><?php echo $v['level'],$v['title']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">规则名称</label>
                <div class="layui-input-block">
                    <input name="title" autocomplete="off" lay-verify="required" value="<?php echo $title; ?>" placeholder="必填,规则名称" class="layui-input" type="text">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">URL</label>
                <div class="layui-input-block">
                    <input name="name" autocomplete="off" lay-verify="" value="<?php echo $name; ?>" placeholder="无子类必填,URL(由字母和'/'组成)" class="layui-input" type="text">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">显示状态</label>
                <div class="layui-input-block">
                  <input name="type" value="1" <?php if($type == 1){ ?> checked=""<?php } ?> title="显示" type="radio" />
                  <input name="type" value="2" <?php if($type == 2){ ?> checked=""<?php } ?> title="隐藏" type="radio" />
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">删除状态</label>
                <div class="layui-input-block">
                  <input name="status" value="1" title="正常" <?php if($status == 1){ ?> checked=""<?php } ?> type="radio" />
                  <input name="status" value="2" title="删除" <?php if($status == 2){ ?> checked=""<?php } ?> type="radio" />
                </div>
            </div>
            <!--底部工具栏-->
            <div class="page-footer">
                <div class="btn-list">
                    <div class="btnlist">
                        <a class="layui-btn layui-btn-sm" lay-submit="" lay-filter="doPost" data-url="/rule/edit"><i class="layui-icon">&#x1005;</i>提交</a>
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