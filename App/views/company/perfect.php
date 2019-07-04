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
    <title>完善企业信息</title>
	<link href="/src/css/layui.css" rel="stylesheet" />
    <script src="/src/layui.js"></script>

</head>
<body>
    <div class="main-wrap" style='padding:10px;'>
        <blockquote class="layui-elem-quote fhui-admin-main_hd">
            <h3>完善企业信息</h3>
        </blockquote>
        <form action="/company/add" class="layui-form layui-form-pane" id="formrec" method="post" role="form">
			<input type="hidden" name="uid" value="<?php echo $uid; ?>" />
            <div class="layui-form-item">
                <label class="layui-form-label">企业名称</label>
                <div class="layui-input-inline">
                    <input autocomplete="off" lay-verify="required" value="<?php echo $meminfo['real_name']; ?>" placeholder="必填,企业名称" class="layui-input" type="text" disabled />
                </div>
				<label class="layui-form-label">所属行业</label>
                <div class="layui-input-inline">
                    <input name="industry" autocomplete="off" lay-verify="required" value="<?php if(isset($company['industry'])) { echo $company['industry']; } ?>" placeholder="必填,所属行业" class="layui-input" type="text" maxlength="45"/>
                </div>
				<label class="layui-form-label">统一社会信用代码/注册号</label>
                <div class="layui-input-inline">
                    <input name="credit" autocomplete="off" lay-verify="required" value="<?php if(isset($company['credit'])) { echo $company['credit']; } ?>" placeholder="必填,统一社会信用代码/注册号" class="layui-input" type="text" maxlength="45"/>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">成立时间</label>
                <div class="layui-input-inline">
                   <input name="founding_time" autocomplete="off" lay-verify="required" value="<?php if(isset($company['founding_time'])) { echo $company['founding_time']; } ?>" placeholder="必填,成立时间" class="layui-input" type="text" maxlength="20"/>
                </div>
				<label class="layui-form-label">注册地址</label>
                <div class="layui-input-inline">
                    <input name="reg_address" autocomplete="off" lay-verify="required" value="<?php if(isset($company['reg_address'])) { echo $company['reg_address']; } ?>" placeholder="必填,注册地址" class="layui-input" type="text" maxlength="45"/>
                </div>
				<label class="layui-form-label">登记状态</label>
                <div class="layui-input-inline">
                    <input name="reg_status" autocomplete="off" lay-verify="required" value="<?php if(isset($company['reg_status'])) { echo $company['reg_status']; } ?>" placeholder="必填,登记状态" class="layui-input" type="text" maxlength="20"/>
                </div>
            </div>
			<div class="layui-form-item">
                <label class="layui-form-label">征信状况</label>
                <div class="layui-input-inline">
                   <input name="credit_status" autocomplete="off" lay-verify="required" value="<?php if(isset($company['credit_status'])) { echo $company['credit_status']; } ?>" placeholder="必填,征信状况" class="layui-input" type="text" maxlength="45"/>
                </div>
				<label class="layui-form-label">涉诉情况</label>
                <div class="layui-input-inline">
                    <input name="situation" autocomplete="off" lay-verify="required" value="<?php if(isset($company['situation'])) { echo $company['situation']; } ?>" placeholder="必填,涉诉情况" class="layui-input" type="text" maxlength="45"/>
                </div>
				<label class="layui-form-label">行政处罚状况</label>
                <div class="layui-input-inline">
                    <input name="sanction" autocomplete="off" lay-verify="required" value="<?php if(isset($company['sanction'])) { echo $company['sanction']; } ?>" placeholder="必填,行政处罚状况" class="layui-input" type="text" maxlength="45"/>
                </div>
            </div>
			<div class="layui-form-item">
                <label class="layui-form-label">已知其他网贷平台负债</label>
                <div class="layui-input-inline">
                   <input name="liabilities" autocomplete="off" lay-verify="required" value="<?php if(isset($company['liabilities'])) { echo $company['liabilities']; } ?>" placeholder="必填,已知其他网贷平台负债" class="layui-input" type="text" maxlength="45"/>
                </div>
				<label class="layui-form-label">平台历史借款记录</label>
                <div class="layui-input-inline">
                    <input name="records" autocomplete="off" lay-verify="required" value="<?php if(isset($company['records'])) { echo $company['records']; } ?>" placeholder="必填,平台历史借款记录" class="layui-input" type="text" maxlength="45"/>
                </div>
				<label class="layui-form-label">企业相关资料</label>
                <div class="layui-input-inline">
                    <input name="info" autocomplete="off" lay-verify="required" value="<?php if(isset($company['info'])) { echo $company['info']; } ?>" placeholder="必填,企业相关资料" class="layui-input" type="text" maxlength="45"/>
                </div>
			</div>
			<div class="layui-form-item">
				<label class="layui-form-label">经营范围</label>
				<div class="layui-input-inline">
                    <input name="scope" autocomplete="off" lay-verify="required" value="<?php if(isset($company['scope'])) { echo $company['scope']; } ?>" placeholder="必填,经营范围" class="layui-input" type="text" maxlength="200"/>
                </div>
            </div>
            <!--底部工具栏-->
            <div class="page-footer">
                <div class="btn-list">
                    <div class="btnlist">
                        <a class="layui-btn layui-btn-sm" lay-submit="" lay-filter="js-perfect" data-url="/company/perfect"><i class="layui-icon">&#x1005;</i>提交</a>
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
		layui.use(['layer', 'form'], function() {
			var $ = layui.$
			, layer = layui.layer
			, form = layui.form;
			form.on('submit(js-perfect)', function(data) {
				var url = $(this).data('url');
				$.post(url, data.field, function(r) {
					layer.msg(r.message, {time:1500}, function() {
						if(r.state == 1) {
							parent.location.reload();
						}
					});
				}, 'json');
			});
		});
	</script>
</body>
</html>