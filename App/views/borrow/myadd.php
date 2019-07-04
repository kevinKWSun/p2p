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
    <title>增加</title>
	<link href="/src/css/layui.css" rel="stylesheet" />
    <script src="/src/layui.js"></script>

</head>
<body>
    <div class="main-wrap" style='padding:10px;'>
        <blockquote class="layui-elem-quote fhui-admin-main_hd">
            <h3>增加</h3>
        </blockquote>
        <form action="/borrow_my/add" class="layui-form layui-form-pane" id="formrec" method="post" role="form">
            <div class="layui-form-item">
                <label class="layui-form-label">借款类型</label>
                <div class="layui-input-block">
                   <select data-val="true" data-val-number="字段 Int32 必须是一个数字" data-val-required="Int32 字段是必需的" name="type">
                        <?php foreach($this->config->item('borrows_type') as $k => $v):?>
                        <option selected="selected" value="<?php echo $k?>"><?php echo $v?></option>
						<?php endforeach;?>
                    </select>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">企业名称</label>
                <div class="layui-input-block">
                    <input name="name" autocomplete="off" lay-verify="required" value="" placeholder="必填,个人 or 法人" class="layui-input" type="text">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">借款方式</label>
                <div class="layui-input-block">
                    <input name="jktpe" autocomplete="off" lay-verify="required" value="担保质押" placeholder="必填,担保质押" class="layui-input" type="text">
                </div>
            </div>
			<div class="layui-form-item">
                <label class="layui-form-label">借款金额</label>
                <div class="layui-input-block">
                    <input name="money" autocomplete="off" lay-verify="required" value="" placeholder="必填,个人最多20万;企业最多100万" class="layui-input" type="text">
                </div>
            </div>
			<div class="layui-form-item">
                <label class="layui-form-label">借款期限</label>
                <div class="layui-input-block">
                    <select data-val="true" data-val-number="字段 Int32 必须是一个数字" data-val-required="Int32 字段是必需的" name="day">
                        <?php foreach($this->config->item('borrow_duration') as $k => $v):?>
                        <option value="<?php echo $v?>"><?php echo $v?>天</option>
						<?php endforeach;?>
                    </select>
                </div>
            </div>
			<div class="layui-form-item">
                <label class="layui-form-label">综合利率</label>
                <div class="layui-input-block">
                    <select data-val="true" data-val-number="字段 Int32 必须是一个数字" data-val-required="Int32 字段是必需的" name="lx">
						<option selected="selected" value="6%">6%</option>
						<option value="7%">7%</option>
						<option value="8%">8%</option>
					</select>
                </div>
            </div>
			<div class="layui-form-item">
                <label class="layui-form-label">借款用途</label>
                <div class="layui-input-block">
                    <input name="yt" autocomplete="off" lay-verify="required" value="" placeholder="必填,借款用途" class="layui-input" type="text">
                </div>
            </div>
			<div class="layui-form-item">
                <label class="layui-form-label">担保人</label>
                <div class="layui-input-block">
                    <input name="member" autocomplete="off" lay-verify="required" value="" placeholder="必填,担保人,担保质押物 ,质押物价格(每个内容为英文空格隔开;企业担保人可多人,以英文','号隔开)" class="layui-input" type="text">
                </div>
            </div>
			<div class="layui-form-item">
                <label class="layui-form-label">借款人</label>
                <div class="layui-input-block">
                    <input name="car" autocomplete="off" lay-verify="required" value="" placeholder="必填,借款人-借款人质押物" class="layui-input" type="text">
                </div>
            </div>
			<div class="layui-form-item">
                <label class="layui-form-label">日期</label>
                <div class="layui-input-block">
                    <input name="add_time" autocomplete="off" lay-verify="required" value="" placeholder="必填,借款申请日期" class="layui-input" type="text" id='test1' readonly>
                </div>
            </div>
            <!--底部工具栏-->
            <div class="page-footer">
                <div class="btn-list">
                    <div class="btnlist">
						<?php if(is_rule('/borrow_my/add')) { ?>
							<a class="layui-btn layui-btn-sm" lay-submit="" lay-filter="doPost" data-url="/borrow_my/add"><i class="layui-icon">&#x1005;</i>提交</a>
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
	<script>
        layui.use(['layedit','layer', 'form', 'laydate'], function(){
            var layedit = layui.layedit
                ,$ = layui.$
                , layer = layui.layer
                , form = layui.form
				, laydate = layui.laydate;
			laydate.render({
				elem: '#test1'
			});
		});
	</script>
</body>
</html>