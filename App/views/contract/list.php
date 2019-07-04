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
    <title>合同列表</title>
    <link href="/src/css/layui.css" rel="stylesheet" />
    <script src="/src/layui.js"></script>
</head>
<body>
    <div class="main-wrap" style='padding:10px;'>
        <blockquote class="layui-elem-quote fhui-admin-main_hd">
            <h2>合同列表</h2>
        </blockquote>
        <div class="y-role">
            <!--工具栏-->
            <div id="floatHead" class="toolbar-wrap">
                <div class="toolbar">
                    <div class="box-wrap">
                        <div class="l-list clearfix">
                            <form id="tt" class="layui-form layui-form-pane">
                                <div class="layui-form-item">
                                    <!-- <div class="layui-inline">
                                        <div class="layui-input-block" style="margin-left: 0px">
                                            <input name="skey" lay-verify="required" value="" autocomplete="off" placeholder="请输入关键字" class="layui-input" type="text" />
                                        </div>
                                    </div> -->
                                    <div class="layui-inline">
										<?php if(is_rule('/contract/add')) { ?>
											<a class="layui-btn layui-btn-small do-action" data-type="doAdd" data-url="/contract/add.html">
												<i class="layui-icon">&#xe61f;</i>新增
											</a>
										<?php } ?>
                                        <a class="layui-btn layui-btn-small do-action" data-type="doRefresh" data-url="/contract.html">
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
                                <th>合同编号</th>
                                <th>合同名称</th>
                                <th>类型</th>
                                <th width="25%">模版</th>
                                <th>启用状态</th>
                                <th>添加时间</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                        	<?php if(! $contract){ ?>
                            <tr>
                                <td class="nodata" colspan="9" align='center'>暂无数据！</td>
                            </tr>
                            <?php }else{foreach ($contract as $v): ?>
                            <tr>
                                <td><?php echo $v['id']; ?></td>
                                <td><?php echo $v['name']; ?></td>
                                <td><?php echo $v['genre']; ?></td>
                                <td><?php echo mb_substr(htmlentities($v['mold']), 0, 80); ?></td>
                                <td>
									<?php if($v['status'] == 1) { ?>
										启用
									<?php } else { ?>
										禁用
									<?php } ?>
								</td>
                                <td><?php echo date('Y-m-d', $v['addtime']); ?></td>
                                <td>
									<?php if(is_rule('/contract/status')) { ?>
										<a class="layui-btn layui-btn-small" lay-submit="" lay-filter="status" data-url='/contract/status/<?php echo $v['id']; ?>.html' target='_blank'>
											<i class="icon-edit  fa fa-search"></i>
											<?php if($v['status'] == 1) { ?>
												禁 用
											<?php } else { ?>
												启 用
											<?php } ?>
										</a>
									<?php } ?>
									<?php if(is_rule('/contract/modify')) { ?>
										<a class="layui-btn layui-btn-small do-action" data-type="doEdit" data-url='/contract/modify/<?php echo $v['id']; ?>.html' target='_blank'>
											<i class="icon-edit  fa fa-search"></i>
											修 改
										</a>
									<?php } ?>
									<?php if(is_rule('/contract/delte')) { ?>
										<a class="layui-btn layui-btn-small" lay-submit="" lay-filter="delte" data-url='/contract/delte/<?php echo $v['id']; ?>.html' target='_blank'>
											<i class="icon-edit  fa fa-search"></i>
											删 除
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
            </div>
        </div>
    </div>
    <script src="/src/global.js"></script>
	<script type="text/javascript">
		layui.use(['layer', 'form'], function(){
			var $ = layui.$
			, form = layui.form
			, layer = layui.layer
			
			form.on('submit(status)', function() {
				var url = $(this).data('url');
				$.post(url, {}, function(r){
					var icon = (r.state == 1) ? 6 : 5;
					layer.msg(r.message, {icon:icon, time:1500}, function() {
						if(r.state == 1) {
							location.reload();
						}
					});
				}, 'json');
			});
			form.on('submit(delte)', function() {
				var url = $(this).data('url');
				$.post(url, {}, function(r){
					var icon = (r.state == 1) ? 6 : 5;
					layer.msg(r.message, {icon:icon, time:1500}, function() {
						if(r.state == 1) {
							location.reload();
						}
					});
				}, 'json');
			});
		});
	</script>
</body>
</html>