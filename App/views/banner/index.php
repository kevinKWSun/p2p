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
    <title>导航图列表</title>
    <link href="/src/css/layui.css" rel="stylesheet" />
    <script src="/src/layui.js"></script>
</head>
<body>
    <div class="main-wrap" style='padding:10px;'>
        <blockquote class="layui-elem-quote fhui-admin-main_hd">
            <h2>导航图列表</h2>
        </blockquote>
        <div class="y-role">
            <!--工具栏-->
            <div id="floatHead" class="toolbar-wrap">
                <div class="toolbar">
                    <div class="box-wrap">
                        <div class="l-list clearfix">
                            <form id="tt" class="layui-form layui-form-pane">
                                <div class="layui-form-item">
                                    <!--<div class="layui-inline">
                                        <div class="layui-input-inline">
                                            <input name="name" value="" autocomplete="off" placeholder="名称" class="layui-input" type="text" />
                                        </div>
                                    </div>-->
                                    <div class="layui-inline">
                                        <!--<a class="layui-btn layui-btn-small" lay-submit="" lay-filter="search" data-url="/banner/index.html">
                                            <i class="fa fa-search"></i>查询
                                        </a>-->
										<?php if(is_rule('/banner/add')) { ?>
											<a class="layui-btn layui-btn-small do-action" data-type="doAdd" data-url="/banner/add.html">
												<i class="layui-icon">&#xe61f;</i>新增
											</a>
										<?php } ?>
                                        <a class="layui-btn layui-btn-small do-action" data-type="doRefresh" data-url="/banner.html">
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
                <form class="form-horizontal layui-form" id="formrec" method="post" role="form">
                    <table class="layui-table layui-tables" lay-skin="line">
                        <thead>
                            <tr>
								<th>ID</th>
								<th>名称</th>
								<th>链接</th>
								<th>描述</th>
								<th>类型</th>
								<th>删除</th>
								<th title="数值越大越靠前">排序</th>
								<th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                        	<?php if(! $banner){ ?>
                            <tr>
                                <td class="nodata" colspan="8" align='center'>暂无数据！</td>
                            </tr>
                            <?php }else{foreach ($banner as $v): ?>
                            <tr>
                                <td><?php echo $v['id']; ?></td>
								<td><?php echo $v['name']; ?></td>
								<td><?php echo $v['img']; ?></td>
								<td><?php echo $v['info']; ?></td>
								<td><?php echo $cate[$v['type']]; ?></td>
								<td><?php echo $v['del'] === '0' ? '正常' : '已删除'; ?></td>
								<td><?php echo $v['sort']; ?></td>
								<td>
									<?php if(is_rule('/banner/modify') && $v['del'] === '0') { ?>
										<a class="layui-btn layui-btn-sm do-action" data-type="doEdit" data-url="/banner/modify/<?php echo $v['id'], '/', $p; ?>.html"><i class="icon-edit fa fa-search"></i>编 辑</a>
									<?php } ?>
									<?php if(is_rule('/banner/del')  && $v['del'] === '0') { ?>
										<a class="layui-btn layui-btn-sm" lay-submit="" lay-filter="js-del" data-href='/banner/del/<?php echo $v['id']; ?>.html' target='_blank'>
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
		layui.use(['layer', 'form', 'laydate'], function(){
			var $ = layui.$
			, form = layui.form
			, layer = layui.layer
			, laydate = layui.laydate;
			
			//搜索查询
			/* form.on('submit(search)', function(data) {
				var url = $(this).data('url');
				var str = JSON.stringify(data.field);
				str = str.replace(/\t/g,"");
				str = str.replace(/\"/g,"").replace("{","").replace("}","").replace(",","&").replace(":","=");
				str = str.replace(/\"/g,"").replace(/{/g,"").replace(/}/g,"").replace(/,/g,"&").replace(/:/g,"=");
				location.href = url + "?" + str;
				return false; 
			}); */
			
			
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