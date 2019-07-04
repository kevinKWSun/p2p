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
    <title>变更记录</title>
    <link href="/src/css/layui.css" rel="stylesheet" />
    <script src="/src/layui.js"></script>
</head>
<body>
    <div class="main-wrap" style='padding:10px;'>
        <blockquote class="layui-elem-quote fhui-admin-main_hd">
            <h2>变更记录</h2>
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
                                            <input name="name" value="<?php if(isset($name)) { echo $name; } ?>" autocomplete="off" placeholder="ID/真实姓名/手机号" class="layui-input" type="text" />
                                        </div>
                                    </div>
									<div class="layui-input-inline">
										<div class="layui-input-inline">
											<input name="time" type="text" class="layui-input" value="<?php if(isset($time)) { echo $time; } ?>" id="test6" placeholder="变更 - 时间">
										</div>
									</div>
                                    <div class="layui-inline">
                                        <a class="layui-btn layui-btn-small" lay-submit="" lay-filter="search" data-url="/ztreesa/change_record.html">
                                            <i class="fa fa-search"></i>查询
                                        </a>
										<?php if(is_rule('/ztreesa/change_export')) { ?>
											<a class="layui-btn layui-btn-small" lay-submit="" lay-filter="excel" data-url="/ztreesa/detail_export.html">
												<i class="fa fa-search"></i>导出
											</a>
										<?php } ?>
                                        <a class="layui-btn layui-btn-small do-action" data-type="doRefresh" data-url="/ztreesa/change_record.html">
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
                                <th>姓名</th>
								<th>电话</th>
								<th>变更前</th>
								<th>变更后</th>
								<th>操作人</th>
								<th>操作时间</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(! $detail){ ?>
                            <tr>
                                <td class="nodata" colspan="8" align='center'>暂无数据！</td>
                            </tr>
                            <?php }else{foreach ($detail as $v): ?>
                            <tr>
								<td><?php echo $v['id']; ?></td>
								<td><?php echo $v['uid']; ?></td>
                                <td><?php echo $v['real_name']; ?></td>
								<td><?php echo $v['phone']; ?></td>
								<td><?php 
									switch($v['type_before']) {
										case 0:
											echo '红苹果';break;
										case 1:
											echo '<font color="gold">金苹果</font>';break;
										case 8:
											echo '梨';break;
										case 9:
											echo '橘子';break;
										case 10:
											echo '火龙果';break;
										case 11:
											echo '葡萄';break;
										case 12:
											echo '桃子';break;
										case 13:
											echo '投资红包';break;
									}
								?></td>
								<td><?php 
									switch($v['type_after']) {
										case 0:
											echo '红苹果';break;
										case 1:
											echo '<font color="gold">金苹果</font>';break;
										case 8:
											echo '梨';break;
										case 9:
											echo '橘子';break;
										case 10:
											echo '火龙果';break;
										case 11:
											echo '葡萄';break;
										case 12:
											echo '桃子';break;
										case 13:
											echo '投资红包';break;
									}
								?></td>
								<td><?php echo $v['realname']; ?></td>
								<td><?php echo date('Y-m-d H:i', $v['addtime']); ?></td>
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
		layui.use(['layer', 'form', 'laydate'], function(){
			var $ = layui.$
			, form = layui.form
			, laydate = layui.laydate
			, layer = layui.layer;
			
			form.on('submit(excel)', function(data) {
				var url = $(this).data('url');
				var str = JSON.stringify(data.field);
				str = str.replace(/\t/g,"");
				str = str.replace(/\"/g,"").replace("{","").replace("}","").replace(",","&").replace(":","=");
				str = str.replace(/\"/g,"").replace(/{/g,"").replace(/}/g,"").replace(/,/g,"&").replace(/:/g,"=");
				location.href = url + "?" + str;
				return false; 
			});
			
			$('.js-chang-type').click(function() {
				var url = $(this).attr('data-url');
				layer.open({
					type: 2,
					title: '发放各种水果',
					//shadeClose: true,
					shade: 0.1,
					maxmin: true,
					area: ['25%', '54%'],
					fixed: true,
					content: url
				});
			});
			
			form.on('submit(search)', function(data) {
				var url = $(this).data('url');
				var str = $('#tt').serialize();
				location.href = url + "?" + str;
				return false; 
			});
			form.on('submit(search)', function(data) {
				var url = $(this).data('url');
				var str = $('#tt').serialize();
				location.href = url + "?" + str;
				return false; 
			});
			form.on('submit(js-change)', function(data) {
				var parent_obj = $(this).parent();
				var url = $(this).data('href');
				layer.prompt({
				  formType: 2,
				  value: '',
				  title: '备注',
				  area: ['300px', '100px'] //自定义文本域宽高
				}, function(value, index, elem){
					layer.load(1);
					$.ajax({
						url 		: url,
						type		: 'post',
						data 		: {value : value},
						dataType	: 'json',
						beforeSend	: function () {
							$(data.elem).attr('disabled', true).html('变更...');
						},
						success 	: function(data) {
							layer.msg(data.message, {time: 1500}, function() {
								if(data.state == 1) {
									location.reload();
								} else {
									parent_obj.find('a').attr('disabled', false).html('<i class="icon-edit  fa fa-search"></i>变  更');
								}
							});
						},
						complete	: function() {
							//parent_obj.html('--');
						},
						error 		: function(data) {
							 console.info("error: " + data.responseText);
						}
					});
					layer.closeAll();
				});
				return;
			});
			laydate.render({
				elem: '#test6'
				,range: true
			});
		});
	</script>
</body>
</html>
