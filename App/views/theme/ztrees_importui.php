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
    <title>发财树导入数据</title>
    <link href="/src/css/layui.css" rel="stylesheet" />
    <script src="/src/layui.js"></script>
</head>
<body>
    <div class="main-wrap" style='padding:10px;'>
        <blockquote class="layui-elem-quote fhui-admin-main_hd">
            <h2>发财树导入数据</h2>
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
                                            <input name="name" value="<?php if(isset($name)) { echo $name; } ?>" autocomplete="off" placeholder="真实姓名" class="layui-input" type="text" />
                                        </div>
                                    </div>
                                    <div class="layui-inline">
                                        <a class="layui-btn layui-btn-small" lay-submit="" lay-filter="search" data-url="/ztreesa/import_ztreesui.html">
                                            <i class="fa fa-search"></i>查询
                                        </a>
										<?php if(is_rule('/ztreesa/import_ztrees_data')) { ?>
											<a class="layui-btn layui-btn-small" id="uploadcsv">
												<i class="fa fa-search"></i>导入
											</a>
										<?php } ?>
                                        <a class="layui-btn layui-btn-small do-action" data-type="doRefresh" data-url="/ztreesa/import_ztreesui.html">
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
            <div class="fhui-admin-table-container">
                <form class="form-horizontal" id="formrec" method="post" role="form">
                    <table class="layui-table layui-tables" lay-skin="line" boder='1'>
                        <thead>
                            <tr>
								<th>ID</th>
								<th>用户ID</th>
                                <th>真实姓名</th>
								<th>身份证号</th>
								<th>金额</th>
								<th>期限</th>
								<th>投标日期</th>
								<th>标识</th>
								<th>操作时间</th>
								<th>操作人</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(! $ztrees){ ?>
                            <tr>
                                <td class="nodata" colspan="10" align='center'>暂无数据！</td>
                            </tr>
                            <?php }else{foreach ($ztrees as $v): ?>
                            <tr>
								<td><?php echo $v['id']; ?></td>
                                <td><?php echo $v['uid']; ?></td>
                                <td><?php echo $v['status'] ? $v['name'] : '<font color="red">' . $v['name'] . '</font>'; ?></td>
								<td><?php echo $v['idcard']; ?></td>
								<td><?php echo $v['money']; ?>元</td>
								<td><?php echo $v['duration']; ?>天</td>
								<td><?php echo date('Y-m-d', $v['itime']); ?></td>
								<td><?php echo $v['flag']; ?></td>
								<td><?php echo date('Y-m-d H:i', $v['addtime']); ?></td>
								<td><?php echo get_user($v['adminid'])['realname']; ?></td>
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
		layui.use(['layer', 'form', 'upload'], function(){
			var $ = layui.$
			, form = layui.form
			, upload = layui.upload
			, layer = layui.layer;
			
			form.on('submit(search)', function(data) {
				var url = $(this).data('url');
				var str = $('#tt').serialize();
				location.href = url + "?" + str;
				return false; 
			});
			
			//导入excel，
			var uploadInst = upload.render({
				elem: '#uploadcsv' //绑定元素
				,url: '/ztreesa/import_ztrees_data.html' //上传接口
				,field: 'excel'
				,accept: 'file'
				,exts:	'xlsx|xls'
				,done: function(res){
					var icon = res.state == 1 ? 6 : 5;
					layer.msg(res.message, {icon:icon, time:1500}, function() {
						if(res.state == 1) {
							location.reload();
						}
					});
				}
				,error: function(){
					
				}
			});
		});
	</script>
</body>
</html>
