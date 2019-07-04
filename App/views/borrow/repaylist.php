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
    <title>还款列表</title>
    <link href="/src/css/layui.css" rel="stylesheet" />
    <script src="/src/layui.js"></script>
</head>
<body>
    <div class="main-wrap" style='padding:10px;'>
        <blockquote class="layui-elem-quote fhui-admin-main_hd">
            <h2>还款列表</h2>
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
										<?php if(is_rule('/borrow/authorize')) { ?>
											<a class="layui-btn layui-btn-small js-authorize" data-href="/borrow/authorize/<?php echo $borrow['id']; ?>.html">
												<i class="layui-icon">&#xe669;</i>申 请 授 权
											</a>
										<?php } ?>
                                        <a class="layui-btn layui-btn-small do-action" data-type="doRefresh" data-url="/borrow/repaylist/<?php echo $borrow['id']; ?>.html">
                                            <i class="layui-icon">&#xe669;</i>重新载入
                                        </a>
										<?php if(is_rule('/borrow/pre_repayment')) { ?>
											<a class="layui-btn layui-btn-small" lay-submit="" lay-filter="js-pre-repay" data-href="/borrow/pre_repayment/<?php echo $borrow['id']; ?>.html"><i class="icon-edit fa fa-dollar"></i>提 前 还 款</a>
										<?php } ?>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!--/工具栏-->
            <!--文字列表-->
            <div class="fhui-admin-table-container">
                <form class="form-horizontal" id="formrec" method="post" role="form">
                    <table class="layui-table layui-tables" lay-skin="line">
                        <thead>
                            <tr>
                                <th>标名</th>
                                <th>借款人</th>
                                <th>金额/万</th>
                                <th>期限</th>
								<th>还款方式</th>
								<th>投资人</th>
                                <th>应还金额（利息+本金）</th>
								<th>已还金额</th>
								<th>期数/总期数</th>
								<th>还款日期</th>
                                <th>状态</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                        	<?php if(! $details){ ?>
                            <tr>
                                <td class="nodata" colspan="9" align='center'>暂无数据！</td>
                            </tr>
                            <?php }else{foreach ($details as $v): ?>
                            <tr>
								<td><?php echo $borrow['borrow_name']; ?></td>
								<td><?php echo $borrow_uid; ?></td>
								<td><?php echo round($borrow['borrow_money']/10000, 2); ?>万</td>
								<td><?php echo $this->config->item('borrow_duration')[$borrow['borrow_duration']]; ?>天</td>
								<td><?php echo $this->config->item('repayment_type')[$borrow['repayment_type']]; ?></td>
								<td>
									<?php 
										foreach($v['investor_name'] as $key=>$investor_name) { 
											echo $investor_name, ',';
										}
									?>
								</td>
								<td>
									<?php if($v['sort_order'] < $v['total']) { ?>
										<?php echo round($v['interest'], 2); ?>元(<?php echo round($v['interest'], 2); ?>元 + <?php echo sprintf("%.2f", $v['capital']); ?>元)
									<?php } else { ?>
										<?php echo round($v['capital'] + $v['interest'], 2); ?>元(<?php echo round($v['interest'], 2); ?>元 + <?php echo round($v['capital'], 2); ?>元)
									<?php } ?>
								</td>
								<td><?php echo round($v['receive_interest'] + $v['receive_capital'], 2); ?>元</td>
								<td><?php echo round($v['sort_order'], 2); ?>/<?php echo round($v['total'], 2); ?></td>
								<td><?php echo date('Y-m-d', $v['deadline']); ?></td>
								<td><?php echo $this->config->item('borrow_status')[$v['status']]; ?></td>
								<td>
									<?php if($v['repayment_time'] > 0) { ?>
										<a class="layui-btn layui-btn-small layui-btn-disabled"><i class="icon-edit fa fa-dollar"></i>还　款</a>
									<?php } else { ?>
										<?php if(is_rule('/borrow/repayment')) { ?>
											<a class="layui-btn layui-btn-small js-prpayment" data-href="/borrow/repayment.html" data-id="<?php echo $v['id']; ?>" lay-submit="" lay-filter="repayment"><i class="icon-edit fa fa-dollar"></i>还　款</a>
										<?php } ?>
									<?php } ?>
									
                                </td>
                            </tr>
                            <?php endforeach;} ?>
                        </tbody>
                    </table>
                </form>
            </div>
            <!--/文字列表-->
            
        </div>
    </div>
    <script src="/src/global.js"></script>
	<script type="text/javascript">
		layui.use(['layer', 'form'], function(){
			var $ = layui.$
			, form = layui.form
			, layer = layui.layer;
			// $('.js-prpayment').on('click', function() {
				// var url = $(this).data('href');
				// var id = $(this).data('id');
				// $.post(url, {id : id}, function(r){
					// if(r.state == 1) {
						// layer.msg(r.message, {icon: 6,time:1500}, function(){
							// location.reload();
						// });
					// } else {
						// layer.msg(r.message, {icon: 5,time:1500});
					// }
				// }, 'json');
			// });
			
			form.on('submit(repayment)', function(data) {
				var url = $(this).data('href');
				var id = $(this).data('id');
				if (url) {
					layer.confirm('你确定要还款吗？', {icon: 6, title:'信息提示'}
					, function(index){
						layer.load(1);
						$.ajax({
							url: url,
							type: 'post',
							dataType: 'json',
							data: {id : id},
							timeout : 0,
							success: function (r, startic) {
								if(r.state == 1) {
									layer.msg(r.message, {icon: 6,time:1500}, function(){
										location.reload();
									});
								} else {
									layer.msg(r.message, {icon: 5,time:1500});
								}
							},
							beforeSend: function () {
							   $(data.elem).attr("disabled", "true").text("还款中...");
							},
							complete: function () {
							   $(data.elem).removeAttr("disabled").html('<i class="icon-edit fa fa-dollar"></i>还　款');
							},
							error: function (XMLHttpRequest, textStatus, errorThrown) {
								layer.msg(textStatus + XMLHttpRequest.readyState);
							}
						});
						layer.closeAll();
					}, function(index) {
						layer.closeAll();
					});
				} else {
					layer.msg('链接错误！');
				}
			});
			
			/** 提前还款 */
			form.on('submit(js-pre-repay)', function(data) {
				var url = $(this).data('href');
				if (url) {
					layer.confirm('你确定要提前还款吗？', {icon: 6, title:'信息提示'}
					, function(index){
						layer.load(1);
						$.ajax({
							url: url,
							type: 'post',
							dataType: 'json',
							data: {},
							success: function (r, startic) {
								layer.msg(r.message, {time:1500}, function() {
									if(r.state == 1){
										 self.location.reload();
									} else {
										layer.closeAll();
									}
								});
							},
							beforeSend: function () {
							   $(data.elem).attr("disabled", "true").text("提前还款中...");
							},
							complete: function () {
							   $(data.elem).removeAttr("disabled").html('<i class="icon-edit fa fa-dollar"></i>提 前 还 款');
							},
							error: function (XMLHttpRequest, textStatus, errorThrown) {
								layer.msg(textStatus);
							}
						});
						layer.closeAll();
					}, function(index) {
						layer.closeAll();
					});
				} else {
					layer.msg('链接错误！');
				}
			});
			
			
			$('.js-authorize').on('click', function() {
				var url = $(this).data('href');
				var id = $(this).data('id');
				layer.open({
					type: 2,
					title: '投资',
					shade: 0.1,
					maxmin: true,
					area: ['50%', '90%'],
					fixed: true,
					content: url + '?id=' + id
				});
			});
		});
		
	</script>
</body>
</html>