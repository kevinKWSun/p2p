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
    <title>收款企业信息</title>
	<link href="/src/css/layui.css" rel="stylesheet" />
    <script src="/src/layui.js"></script>
</head>
<body>
    <div class="main-wrap" style='padding:10px;'>
        <blockquote class="layui-elem-quote fhui-admin-main_hd">
            <h3>收款企业信息</h3>
        </blockquote>
        <div class="y-role">
            <!--工具栏-->
            <div id="floatHead" class="toolbar-wrap">
                <div class="toolbar">
                    <div class="box-wrap">
                        <div class="l-list clearfix">
                            <form id="tt" class="layui-form layui-form-pane">
                                <div class="layui-form-item">
                                    <div class="layui-inline">
                                        <div class="layui-input-block" style="margin-left: 0px">
                                            <input name="skey" value="<?php if(isset($skey)) { echo $skey; } ?>" autocomplete="off" placeholder="ID/企业名称/手机号/企业号码" class="layui-input" type="text" />
                                        </div>
                                    </div>
                                    <div class="layui-inline">
                                        <a class="layui-btn layui-btn-sm" lay-submit="" lay-filter="search" data-url="/company.html">
                                            <i class="fa fa-search"></i>查询
                                        </a>
										<?php if(is_rule("/company/export")) { ?>
											<a class="layui-btn layui-btn-sm" lay-submit="" lay-filter="js-export" data-url="/company/export.html">
                                            <i class="fa fa-search"></i>导出
                                        </a>
										<?php } ?>
										<a class="layui-btn layui-btn-sm do-action" data-type="doAdd" data-url="/company/add.html">
                                            <i class="layui-icon">&#xe61f;</i>新增
                                        </a>
                                        <a class="layui-btn layui-btn-sm do-action" data-type="doRefresh" data-url="/company.html">
                                            <i class="layui-icon">&#xe669;</i>重新载入
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="fhui-admin-table-container">
                <form class="form-horizontal" id="formrec" method="post" role="form">
                    <table class="layui-table">
                        <thead>
                            <tr>
								<th>ID</th>
                                <th>企业名称</th>
                                <th>联系手机</th>
                                <th>企业号码</th>
                                <th>注册时间</th>
								<th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(! $member){ ?>
                            <tr>
                                <td class="nodata" colspan="5" align='center'>暂无数据！</td>
                            </tr>
                            <?php }else{foreach ($member as $v): ?>
                            <tr>
								<td><?php echo $v['id']; ?></td>
                                <td class='sed'><?php echo $v['real_name']; ?></td>
                                <td><?php echo $v['phone']; ?></td>
                                <td><?php echo $v['idcard']; ?></td>
                                <td><?php echo date('Y-m-d', $v['reg_time']); ?></td>
								<td>
									<a class="layui-btn layui-btn-sm do-action" data-type="doShow" data-url="/company/perfect/<?php echo $v['id'] ?>.html">
										<i class="layui-icon">&#xe621;</i>完善信息
									</a>
									<a class="layui-btn layui-btn-sm" lay-submit="" lay-filter="js-esign" data-url="/company/esign/<?php echo $v['id'] ?>.html">
										<i class="layui-icon">&#xe621;</i>生成签章
									</a>
									<a class="layui-btn layui-btn-sm do-action" data-type="doShow" data-url="/company/reset_trade_password/<?php echo $v['id'] ?>.html">
										<i class="layui-icon">&#xe621;</i>重置交易密码
									</a>
									<a class="layui-btn layui-btn-sm do-action" data-type="doShow" data-url="/company/balance/<?php echo $v['id'] ?>.html">
										<i class="layui-icon">&#xe621;</i>余额查询
									</a>
									<a class="layui-btn layui-btn-sm do-action" data-type="doShow" data-url="/member/cz/<?php echo $v['id'] ?>.html">
										<i class="layui-icon">&#xe621;</i>充值
									</a>
									<!--<a class="layui-btn layui-btn-sm" lay-submit="" lay-filter="js-resetpwd" data-href="/company/reset_pwd/<?php echo $v['id'] ?>.html">
										<i class="layui-icon">&#xe621;</i>重置密码
									</a>-->
									<!--<a class="layui-btn layui-btn-sm do-action" data-type="doShow" data-url="/company/recharge.html">
										<i class="layui-icon">&#xe621;</i>充值
									</a>
									<a class="layui-btn layui-btn-sm do-action" data-type="doShow" data-url="/company/inquiry<?php echo $v['id'] ?>.html">
										<i class="layui-icon">&#xe621;</i>交易查询
									</a>
									
									<a class="layui-btn layui-btn-sm do-action" data-type="doShow" data-url="/company/set_password.html">
										<i class="layui-icon">&#xe621;</i>支付密码修改
									</a>-->
								</td>
                            </tr>
                            <?php endforeach;} ?>
                        </tbody>
                    </table>
                </form>
            </div>
            <!--/文字列表-->
            <div class="layui-box layui-laypage layui-laypage-default">
				<?php echo $page; ?>
				<a href="javascript:;" class="layui-laypage-next" data-page="2">共 <?php echo $totals; ?> 条</a>
			</div>
        </div>
    </div>
    <script src="/src/global.js"></script>
	<script type="text/javascript">
		layui.use(['layer', 'form'], function() {
			var $ = layui.$
			, form = layui.form
			, layer = layui.layer;
			
			form.on('submit(search)', function(data) {
				var url = $(this).data('url');
				var str = JSON.stringify(data.field);
				str = str.replace(/\t/g,"");
				str = str.replace(/\"/g,"").replace("{","").replace("}","").replace(",","&").replace(":","=");
				str = str.replace(/\"/g,"").replace(/{/g,"").replace(/}/g,"").replace(/,/g,"&").replace(/:/g,"=");
				location.href = url + "?" + str;
				return false; 
			});
			//导出
			form.on('submit(js-export)', function(data) {
				var url = $(this).data('url');
				var str = JSON.stringify(data.field);
				str = str.replace(/\t/g,"");
				str = str.replace(/\"/g,"").replace("{","").replace("}","").replace(",","&").replace(":","=");
				str = str.replace(/\"/g,"").replace(/{/g,"").replace(/}/g,"").replace(/,/g,"&").replace(/:/g,"=");
				location.href = url + "?" + str;
				return false; 
			});
			form.on('submit(js-esign)', function(data) {
				var url = $(this).data('url');
				layer.load(1);
				$.ajax({
					url: url,
					type: 'post',
					dataType: 'json',
					data: {},
					success: function (r, startic) {
						layer.msg(r.message, {time:1500})
					},
					beforeSend: function () {
					   $(data.elem).attr("disabled", "true").text("生成中...");
					},
					complete: function () {
					   $(data.elem).removeAttr("disabled").html('<i class="layui-icon">&#xe621;</i>生成签章');
					},
					error: function (XMLHttpRequest, textStatus, errorThrown) {
						layer.msg(textStatus);
					}
				});
				layer.closeAll();
			});
			// form.on('submit(js-resetpwd)', function(data) {
				// var url = $(this).data('href');
				// $.post(url, {}, function(r) {
					// layer.msg(r.message, {time : 1500});
				// }, 'json');
			// });
		});
	</script>
</body>
</html>