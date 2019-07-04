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
    <title>新闻管理</title>
	<link href="/src/css/layui.css" rel="stylesheet" />
    <script src="/src/layui.js"></script>
</head>
<body>
    <div class="main-wrap" style='padding:10px;'>
        <blockquote class="layui-elem-quote fhui-admin-main_hd">
            <h3>新闻管理</h3>
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
										<?php if(is_rule('/news/add')) { ?>
											<a class="layui-btn layui-btn-sm do-action" data-type="doAdd" data-url="/news/add.html">
												<i class="layui-icon">&#xe61f;</i>新增
											</a>
										<?php } ?>
                                        <a class="layui-btn layui-btn-sm do-action" data-type="doRefresh" data-url="/news.html">
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
                <form class="form-horizontal" method="post">
                    <table class="layui-table">
                        <thead>
                            <tr>
                                <th>名称</th>
                                <th>分类</th>
								<th>时间</th>
								<th>管理</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                        	<?php if(! $news){ ?>
                            <tr>
                                <td class="nodata" colspan="5" align='center'>暂无数据！</td>
                            </tr>
                            <?php }else{foreach ($news as $v): ?>
                            <tr>
                                <td><?php echo $v['title']; ?></td>
								<td><?php echo $v['type_name']; ?></td>
                                <td><?php echo date('Y-m-d',$v['addtime']); ?></td>
								<td><?php echo get_user($v['admin_id'])['realname']; ?></td>
                                <td>
									<?php if(is_rule('/news/edit')) { ?>
										<a class="layui-btn layui-btn-sm do-action" data-type="doEdit" data-url="/news/edit/<?php echo $v['id']; ?>.html"><i class="layui-icon">&#xe642;</i>编辑</a>
									<?php } ?>
									<?php if(is_rule('/news/show')) { ?>
										<a class="layui-btn layui-btn-sm do-action" data-type="doShow" data-url="/news/show/<?php echo $v['id']; ?>.html"><i class="layui-icon">&#xe705;</i>查看</a>
									<?php } ?>
									<?php if(is_rule('/news/del')) { ?>
										<a class="layui-btn layui-btn-sm"  lay-submit="" lay-filter="del" data-url="/news/del/<?php echo $v['id']; ?>.html"><i class="layui-icon">&#xe705;</i>删除</a>
									<?php } ?>
                                </td>
                            </tr>
                            <?php endforeach;} ?>
                        </tbody>
                    </table>
                </form>
            </div>
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
			, form = layui.form;
			
			form.on('submit(del)', function(data) {
				var url = $(this).data('url');
				if(url) {
					layer.confirm('你确定要删除吗?', {icon:6, title:'删除'}, function() {
						layer.load(1);
						$.ajax({
							url 		: url, 
							type		: 'post',
							dataType 	: 'json',
							data		: {},
							success		: function(r, startic) {
								var icon = r.state ? 6 : 5;
								layer.msg(r.message, {icon: icon, time: 1500}, function() {
									if(r.state > 0) {
										location.reload();
									}
								});
							},
							beforeSend	: function() {
								$(data.elem).attr('disabled', true).html('删除中...');
							},
							complete : function() {
								$(data.elem).remove('disbaled').html('<i class="layui-icon">&#xe705;</i>删除');
							},
							error: function (XMLHttpRequest, textStatus, errorThrown) {
								layer.msg(textStatus);
							}
						});
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