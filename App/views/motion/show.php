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
    <title>查看</title>
	<link href="/src/css/layui.css" rel="stylesheet" />
    <script src="/src/layui.js"></script>
	<style type="text/css">
		.layui-form-pane .layui-form-label { width: 210px; }
		.layui-form-item .layui-input-inline { width: 300px; }
		.ds,span.s{display:none;}
		.layui-input {
			padding : 8px 0px 8px 15px;
		}
	</style>
</head>
<body>
    <div class="main-wrap" style='padding:10px;'>
        <blockquote class="layui-elem-quote fhui-admin-main_hd">
            <h3>查看（<?php echo date('Y-m', $motion['date']); ?>运营报告）</h3>
        </blockquote>
        <form action="/motion/add" class="layui-form layui-form-pane" id="formrec" method="post" role="form">
			<input type="hidden" name="id" value="<?php echo $motion['id']; ?>" />
			<input type="hidden" name="current_page" value="<?php echo $current_page; ?>" />
            <div class="layui-form-item">
                <label class="layui-form-label">历史累计交易金额</label>
				<div class="layui-input-inline layui-input">
					<?php echo $motion['bamount']; ?>
				</div>
				<label class="layui-form-label">累计回款金额</label>
                <div class="layui-input-inline layui-input">
                    <?php echo $motion['back']; ?>
                </div>
				<label class="layui-form-label">年化投资总金额</label>
                <div class="layui-input-inline layui-input">
                    <?php echo $motion['total']; ?>
                </div>
            </div>
			<div class="layui-form-item">
                <label class="layui-form-label">用户累计获得收益</label>
                <div class="layui-input-inline layui-input">
                   <?php echo $motion['income']; ?>
                </div>
				<label class="layui-form-label">平台累计注册人数</label>
                <div class="layui-input-inline layui-input">
                    <?php echo $motion['reg']; ?>
                </div>
				<label class="layui-form-label">历史累计交易次数</label>
                <div class="layui-input-inline layui-input">
                    <?php echo $motion['trade_times']; ?>
                </div>
            </div>
			<div class="layui-form-item">
                <label class="layui-form-label">借款余额</label>
                <div class="layui-input-inline layui-input">
                    <?php echo $motion['balance']; ?>
                </div>
				<label class="layui-form-label">累计出借人数量</label>
                <div class="layui-input-inline layui-input">
                  <?php echo $motion['lenders']; ?>
                </div>
				<label class="layui-form-label">前十大借款人待还占比</label>
                <div class="layui-input-inline layui-input">
                    <?php echo $motion['ratio']; ?>
                </div>
            </div>
			<div class="layui-form-item">
                <label class="layui-form-label">历史累计交易笔数</label>
                <div class="layui-input-inline layui-input">
                    <?php echo $motion['trade_total']; ?>
                </div>
				<label class="layui-form-label">借贷笔数</label>
                <div class="layui-input-inline layui-input">
                    <?php echo $motion['lead_nums']; ?>
                </div>
				<label class="layui-form-label">累计借款人数量</label>
                <div class="layui-input-inline layui-input">
                    <?php echo $motion['borrowers_toal']; ?>
                </div>
            </div>
			<div class="layui-form-item">
                <label class="layui-form-label">当期出借人数量</label>
                <div class="layui-input-inline layui-input">
                    <?php echo $motion['current_leader']; ?>
                </div>
				<label class="layui-form-label">当期借款人数量</label>
                <div class="layui-input-inline layui-input">
                    <?php echo $motion['current_borrow']; ?>
                </div>
				<label class="layui-form-label">最高借款人待还金额占比</label>
                <div class="layui-input-inline layui-input">
                    <?php echo $motion['persent_borrow']; ?>
                </div>
            </div>
			<div class="layui-form-item">
                <label class="layui-form-label">关联关系借款余额/笔数</label>
                <div class="layui-input-inline layui-input">
                    <?php echo $motion['loan_balance']; ?>
                </div>
				<label class="layui-form-label">逾期金额/笔数</label>
                <div class="layui-input-inline layui-input">
                    <?php echo $motion['overdue']; ?>
                </div>
				<label class="layui-form-label">累计代偿金额/笔数</label>
                <div class="layui-input-inline layui-input">
                   <?php echo $motion['compensation']; ?>
                </div>
            </div>

           
            <!--底部工具栏-->
            <div class="page-footer">
                <div class="btn-list">
                    <div class="btnlist">
                        <a class="layui-btn layui-btn-sm do-action" data-type="doRefresh" data-url=""><i class="layui-icon">&#xe669;</i>刷新</a>
                        <a class="layui-btn layui-btn-sm do-action" data-type="doGoBack"><i class="layui-icon">&#xe65c;</i>返回上一页</a>
                    </div>
                </div>
            </div>
            <!--/底部工具栏-->
        </form>
    </div>
    <script src="/src/global.js"></script>
</body>
</html>