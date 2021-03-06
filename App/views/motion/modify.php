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
    <title>编辑</title>
	<link href="/src/css/layui.css" rel="stylesheet" />
    <script src="/src/layui.js"></script>
	<style type="text/css">
		.layui-form-pane .layui-form-label { width: 210px; }
		.layui-form-item .layui-input-inline { width: 300px; }
	</style>
</head>
<body>
    <div class="main-wrap" style='padding:10px;'>
        <blockquote class="layui-elem-quote fhui-admin-main_hd">
            <h3>编辑（<?php echo date('Y-m', $motion['date']); ?>运营报告）</h3>
        </blockquote>
        <form action="/motion/add" class="layui-form layui-form-pane" id="formrec" method="post" role="form">
			<input type="hidden" name="id" value="<?php echo $motion['id']; ?>" />
			<input type="hidden" name="current_page" value="<?php echo $current_page; ?>" />
            <div class="layui-form-item">
                <label class="layui-form-label">历史累计交易金额</label>
                <div class="layui-input-inline">
                    <input name="bamount" autocomplete="off" class="layui-input" type="text" maxlength="15" value="<?php echo $motion['bamount']; ?>">
                </div>
				<label class="layui-form-label">累计回款金额</label>
                <div class="layui-input-inline">
                    <input name="back" autocomplete="off" class="layui-input" type="text" maxlength="15" value="<?php echo $motion['back']; ?>">
                </div>
				<label class="layui-form-label">年化投资总金额</label>
                <div class="layui-input-inline">
                    <input name="total" autocomplete="off" class="layui-input" type="text" maxlength="15" value="<?php echo $motion['total']; ?>">
                </div>
            </div>
			<div class="layui-form-item">
                <label class="layui-form-label">用户累计获得收益</label>
                <div class="layui-input-inline">
                    <input name="income" autocomplete="off" class="layui-input" type="text" maxlength="15" value="<?php echo $motion['income']; ?>">
                </div>
				<label class="layui-form-label">平台累计注册人数</label>
                <div class="layui-input-inline">
                    <input name="reg" autocomplete="off" class="layui-input" type="text" maxlength="10" value="<?php echo $motion['reg']; ?>">
                </div>
				<label class="layui-form-label">历史累计交易次数</label>
                <div class="layui-input-inline">
                    <input name="trade_times" autocomplete="off" class="layui-input" type="text" maxlength="10" value="<?php echo $motion['trade_times']; ?>">
                </div>
            </div>
			<div class="layui-form-item">
                <label class="layui-form-label">借款余额</label>
                <div class="layui-input-inline">
                    <input name="balance" autocomplete="off" class="layui-input" type="text" maxlength="15" value="<?php echo $motion['balance']; ?>">
                </div>
				<label class="layui-form-label">累计出借人数量</label>
                <div class="layui-input-inline">
                    <input name="lenders" autocomplete="off" class="layui-input" type="text" maxlength="10" value="<?php echo $motion['lenders']; ?>">
                </div>
				<label class="layui-form-label">前十大借款人待还占比</label>
                <div class="layui-input-inline">
                    <input name="ratio" autocomplete="off" class="layui-input" type="text" maxlength="6" value="<?php echo $motion['ratio']; ?>">
                </div>
            </div>
			<div class="layui-form-item">
                <label class="layui-form-label">历史累计交易笔数</label>
                <div class="layui-input-inline">
                    <input name="trade_total" autocomplete="off" class="layui-input" type="text" maxlength="10" value="<?php echo $motion['trade_total']; ?>">
                </div>
				<label class="layui-form-label">借贷笔数</label>
                <div class="layui-input-inline">
                    <input name="lead_nums" autocomplete="off" class="layui-input" type="text" maxlength="10" value="<?php echo $motion['lead_nums']; ?>">
                </div>
				<label class="layui-form-label">累计借款人数量</label>
                <div class="layui-input-inline">
                    <input name="borrowers_toal" autocomplete="off" class="layui-input" type="text" maxlength="10" value="<?php echo $motion['borrowers_toal']; ?>">
                </div>
            </div>
			<div class="layui-form-item">
                <label class="layui-form-label">当期出借人数量</label>
                <div class="layui-input-inline">
                    <input name="current_leader" autocomplete="off" class="layui-input" type="text" maxlength="8" value="<?php echo $motion['current_leader']; ?>">
                </div>
				<label class="layui-form-label">当期借款人数量</label>
                <div class="layui-input-inline">
                    <input name="current_borrow" autocomplete="off" class="layui-input" type="text" maxlength="8" value="<?php echo $motion['current_borrow']; ?>">
                </div>
				<label class="layui-form-label">最高借款人待还金额占比</label>
                <div class="layui-input-inline">
                    <input name="persent_borrow" autocomplete="off" class="layui-input" type="text" maxlength="6" value="<?php echo $motion['persent_borrow']; ?>">
                </div>
            </div>
			<div class="layui-form-item">
                <label class="layui-form-label">关联关系借款余额/笔数</label>
                <div class="layui-input-inline">
                    <input name="loan_balance" autocomplete="off" class="layui-input" type="text" maxlength="15" value="<?php echo $motion['loan_balance']; ?>">
                </div>
				<label class="layui-form-label">逾期金额/笔数</label>
                <div class="layui-input-inline">
                    <input name="overdue" autocomplete="off" class="layui-input" type="text" maxlength="15" value="<?php echo $motion['overdue']; ?>">
                </div>
				<label class="layui-form-label">累计代偿金额/笔数</label>
                <div class="layui-input-inline">
                    <input name="compensation" autocomplete="off" class="layui-input" type="text" maxlength="15" value="<?php echo $motion['compensation']; ?>">
                </div>
            </div>

           
            <!--底部工具栏-->
            <div class="page-footer">
                <div class="btn-list">
                    <div class="btnlist">
						<?php if(is_rule('/motion/modify')) { ?>
							<a class="layui-btn layui-btn-sm" lay-submit="" lay-filter="add_motion" data-href="/motion/modify"><i class="layui-icon">&#x1005;</i>提交</a>
						<?php } ?>
                        <a class="layui-btn layui-btn-sm do-action" data-type="doRefresh" data-url=""><i class="layui-icon">&#xe669;</i>刷新</a>
                        <a class="layui-btn layui-btn-sm do-action" data-type="doGoBack"><i class="layui-icon">&#xe65c;</i>返回上一页</a>
                    </div>
                </div>
            </div>
            <!--/底部工具栏-->
        </form>
    </div>
    <script src="/src/global.js"></script>
	<script type="text/javascript">
		layui.use(['layer','laydate', 'form'], function(){
            var $ = layui.$
                , layer = layui.layer
				, form = layui.form
				, laydate = layui.laydate;
			
			form.on('submit(add_motion)', function(formdata) {
				var url = $(this).data('href');
				$.post(url, formdata.field, function(r) {
					var icon = r.state ? 6 : 5;
					layer.msg(r.message, {icon: icon, time: 1500}, function() {
						if(r.state) {
							self.location.href = r.url;
						}
					});
				}, 'json');
			});
        });
	</script>
</body>
</html>