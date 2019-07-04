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
			<div class="layui-form-item">
				<input name='id' value='<?php echo $id?>' type='hidden' />
                <label class="layui-form-label">分类名称</label>
                <div class="layui-input-block">
                    <select data-val="true" data-val-number="字段 Int32 必须是一个数字" data-val-required="Int32 字段是必需的" disabled>
                        <option selected="selected" value="0">选择所属分类</option>
                        <?php foreach ($cate as $v): ?>
                        <option value="<?php echo $v['id']; ?>"<?php if($v['id']==$cid){echo ' selected';}?>><?php echo $v['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
			<div class="layui-form-item">
                <label class="layui-form-label">商品名称</label>
                <div class="layui-input-block">
                    <input autocomplete="off" lay-verify="required" value="<?php echo $gname?>" placeholder="必填,商品名称" class="layui-input" type="text" disabled>
                </div>
            </div>
			<div class="layui-form-item">
                <label class="layui-form-label">商品价格</label>
                <div class="layui-input-block">
                    <input autocomplete="off" lay-verify="required" value="<?php echo $price?>" placeholder="必填,商品价格/(0.00)" class="layui-input" type="text" disabled>
                </div>
            </div>
			<div class="layui-form-item">
                <label class="layui-form-label">所需积分</label>
                <div class="layui-input-block">
                    <input autocomplete="off" lay-verify="required" value="<?php echo $score?>" placeholder="必填,所需积分" class="layui-input" type="text" disabled>
                </div>
            </div>
			<div class="layui-form-item">
                <label class="layui-form-label">数量</label>
                <div class="layui-input-block">
                    <input name="num" autocomplete="off" lay-verify="number" value="<?php echo $num?>" placeholder="必填,存量数" class="layui-input" type="text" disabled>
                </div>
            </div>
			<div class="layui-form-item">
                <label class="layui-form-label">状态</label>
                <div class="layui-input-block">
                  <input name="gstatus" value="1" <?php if($gstatus == 1){ ?> checked=""<?php } ?> title="正常" type="radio" />
                  <input name="gstatus" value="0" <?php if($gstatus == 0){ ?> checked=""<?php } ?> title="关闭" type="radio" />
                </div>
            </div>
			<div class="layui-form-item">
				<fieldset class="layui-elem-field layui-field-title">
					<legend>详情说明</legend>
				</fieldset>
				<textarea class="layui-textarea" id="LAY_demo2" placeholder="必填,详情说明" disabled><?php echo strip_tags($mark);?></textarea>
            </div>
            <!--底部工具栏-->
            <div class="page-footer">
                <div class="btn-list">
                    <div class="btnlist">
                        <a class="layui-btn layui-btn-sm" lay-submit="" lay-filter="doPost" data-url="/cate/gedit"><i class="layui-icon">&#x1005;</i>提交</a>
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