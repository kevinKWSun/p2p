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
    <title>详情</title>
    <link href="/src/css/layui.css" rel="stylesheet" />
    <script src="/src/layui.js"></script>
</head>
<body>
    <div class="main-wrap" style='padding:10px;'>
        <blockquote class="layui-elem-quote fhui-admin-main_hd">
            <h2>详情</h2>
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
										<a class="layui-btn layui-btn-sm do-action" data-type="doGoBack" data-url="">
                                            <i class="layui-icon">&#xe65c;</i>返回上一页
                                        </a>
                                        <a class="layui-btn layui-btn-sm do-action" data-type="doRefresh" data-url="/contract/detail/<?php echo $id; ?>.html">
                                            <i class="layui-icon">&#xe669;</i>重新载入
                                        </a>
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
                                <th>投资ID</th>
                                <th>真实姓名</th>
                                <th>手机号</th>
                                <th>身份证号</th>
                                <th>标的ID</th>
                                <th>标题</th>
								<th>还款方式</th>
                                <th>标的期限</th>
                                <th>投资金额</th>
                                <th>投资日期</th>
								<th>到期日</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                        	<?php if(! $invest){ ?>
                            <tr>
                                <td class="nodata" colspan="17" align='center'>暂无数据！</td>
                            </tr>
                            <?php }else{foreach ($invest as $v): ?>
                            <tr>
								<td><?php echo $v['id']; ?></td>
								<td><?php echo $v['real_name']; ?></td>
								<td><?php echo $v['phone']; ?></td>
								<td><?php echo $v['idcard']; ?></td>
								<td><?php echo $v['bid'] . '/' . $v['borrow_no']; ?></td>
								<td><?php echo mb_substr($v['borrow_name'], 0, 14); ?></td>
								<td><?php echo $this->config->item('repayment_type')[$v['repayment_type']]; ?></td>
								<td><?php echo $this->config->item('borrow_duration')[$v['borrow_duration']]; ?></td>
								<td><?php echo $v['investor_capital']; ?></td>
								<td><?php echo date('Y-m-d H:i:s', $v['add_time']); ?></td>
								<td><?php echo $v['deadline'] > 0 ? date('Y-m-d H:i:s', $v['deadline']) : '-'; ?></td>
								<td>
									<?php if($v['contract_status'] > 0) { ?>
										<a class="layui-btn layui-btn-sm layui-btn-disabled" ><i class="icon-edit fa fa-search"></i>已生成</a>
									<?php } else { ?>
										<?php if(is_rule('/contract/build_one')) { ?>
											<a class="layui-btn layui-btn-sm" lay-submit="" lay-filter="js-bulild-one" data-href="/contract/build_one/<?php echo $v['id']; ?>.html"><i class="icon-edit fa fa-search"></i>生成合同</a>
										<?php } ?>
									<?php } ?>
									<?php if(is_rule('/contract/add')) { ?>
										<a class="layui-btn layui-btn-sm do-action" data-type="doShow" data-url="/contract/show/<?php echo $v['id']; ?>.pdf"><i class="icon-edit fa fa-search"></i>查看合同</a>
									<?php } ?>
								</td>
                            </tr>
                            <?php endforeach;} ?>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
    <script src="/src/global.js"></script>
	<script type="text/javascript">
		layui.use(['layer', 'form'], function(){
			var $ = layui.$
			, form = layui.form
			, layer = layui.layer
			
			form.on('submit(js-bulild-one)', function(data) {
				var url = $(this).data('href');
				if (url) {
					layer.confirm('你确定要生成吗？', {icon: 6, title:'信息提示'}
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
										location.reload();
									}else{
										layer.closeAll();
									}
								});
							},
							beforeSend: function () {
							   $(data.elem).attr("disabled", "true").text("生成中...");
							},
							complete: function () {
							   //$(data.elem).removeAttr("disabled").html('<i class="icon-edit"></i>生成合同');
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
		});
	</script>
</body>
</html>