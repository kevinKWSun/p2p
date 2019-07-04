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
    <title>录入列表</title>
    <link href="/src/css/layui.css" rel="stylesheet" />
    <script src="/src/layui.js"></script>
</head>
<body>
    <div class="main-wrap" style='padding:10px;'>
        <blockquote class="layui-elem-quote fhui-admin-main_hd">
            <h2>录入列表</h2>
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
                                            <input name="skey" value="<?php if(isset($skey)) { echo $skey; } ?>" autocomplete="off" placeholder="ID/标名/编号" class="layui-input" type="text" />
                                        </div>
                                    </div>
                                    <div class="layui-inline">
                                        <a class="layui-btn layui-btn-small" lay-submit="" lay-filter="js-search" data-url="/borrowing/entry.html">
                                            <i class="fa fa-search"></i>查询
                                        </a>
										<?php if(is_rule('/borrowing/add')) { ?>
											<a class="layui-btn layui-btn-small do-action" data-type="doAdd" data-url="/borrowing/add.html">
												<i class="layui-icon">&#xe61f;</i>新增
											</a>
										<?php } ?>
                                        <a class="layui-btn layui-btn-small do-action" data-type="doRefresh" data-url="/borrowing/entry.html">
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
								<th>ID</th>
                                <th>标名</th>
								<th>编号</th>
                                <th>借款人</th>
                                <th>金额/万</th>
								<th>已上标金额</th>
                                <th>期限</th>
                                <th>申请日期</th>
                                <th>还款方式</th>
                                <th>状态</th>
								<th>退回原因</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                        	<?php if(! $borrowing){ ?>
                            <tr>
                                <td class="nodata" colspan="12" align='center'>暂无数据！</td>
                            </tr>
                            <?php }else{foreach ($borrowing as $v): ?>
                            <tr>
								<td><?php echo $v['id']; ?></td>
                                <td><?php echo $v['borrow_name']; ?></td>
								<td><?php echo $v['borrow_no']; ?></td>
                                <td><?php echo get_member_info($v['borrow_uid'])['real_name'];?></td>
                                <td><?php echo $v['borrow_money'] / 10000; ?>万</td>
								<td><?php echo $v['has_borrow']; ?></td>
                                <td><?php echo $this->config->item('borrow_duration')[$v['borrow_duration']]; ?>天</td>
                                <td><?php echo date('Y-m-d',$v['add_time']); ?></td>
                                <td><?php echo $this->config->item('repayment_type')[$v['repayment_type']]; ?></td>
                                <td><?php echo $this->config->item('audit_status')[$v['audit']]; ?></td>
								<td title="<?php echo $v['remark']; ?>"><?php echo mb_substr($v['remark'], 0, 15); ?></td>
                                <td>
									<?php if(is_rule('/borrowing/modify')) { ?>
										<a class="layui-btn layui-btn-sm do-action" data-type="doEdit" data-url="/borrowing/modify/<?php echo $v['id'], '/', $p; ?>.html"><i class="icon-edit fa fa-search"></i>编 辑</a>
									<?php } ?>
									<?php if(is_rule('/borrowing/show')) { ?>
										<a class="layui-btn layui-btn-sm" href='/borrowing/show/<?php echo $v['id']; ?>.html'>
											<i class="icon-edit  fa fa-search"></i>
											查　看
										</a>
									<?php } ?>
									<?php if(is_rule('/borrowing/del')) { ?>
										<a class="layui-btn layui-btn-sm" lay-submit="" lay-filter="js-del" data-href='/borrowing/del/<?php echo $v['id']; ?>.html' target='_blank'>
											<i class="icon-edit  fa fa-search"></i>
											删　除
										</a>
									<?php } ?>
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
				<?php if(isset($skip_page)) { echo $skip_page; } ?>
            </div>
        </div>
    </div>
    <script src="/src/global.js"></script>
	<script type="text/javascript">
		layui.use(['layer', 'form'], function(){
			var $ = layui.$
			, form = layui.form
			, layer = layui.layer
			
			form.on('submit(js-search)', function(data) {
				var url = $(this).data('url');
				var str = JSON.stringify(data.field);
				str = str.replace(/\t/g,"");
				str = str.replace(/\"/g,"").replace("{","").replace("}","").replace(",","&").replace(":","=");
				str = str.replace(/\"/g,"").replace(/{/g,"").replace(/}/g,"").replace(/,/g,"&").replace(/:/g,"=");
				location.href = url + "?" + str;
				return false; 
			});
			
			form.on('submit(js-del)', function(data) {
				var url = $(this).data('href');
				layer.confirm('你确定要删除吗?', function() {
					layer.load(1);
					$.ajax({
						url 		: url,
						type		: 'post',
						data 		: {},
						dataType	: 'json',
						beforeSend	: function () {
							$(data.elem).attr('disabled', true).html('删除中...');
						},
						success 	: function(data) {
							layer.msg(data.message, {time: 1500}, function() {
								if(data.state == 1) {
									location.reload();
								}
							});
						},
						complete	: function() {
							$(data.elem).attr('disabled', false).html('<i class="icon-edit fa fa-search"></i>删　除');
						},
						error 		: function(data) {
							 console.info("error: " + data.responseText);
						}
					});
					layer.closeAll();
				});
			});
		});
	</script>
</body>
</html>