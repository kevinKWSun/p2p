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
    <title>宝箱抽奖详情</title>
    <link href="/src/css/layui.css" rel="stylesheet" />
    <script src="/src/layui.js"></script>
</head>
<body>
    <div class="main-wrap" style='padding:10px;'>
        <blockquote class="layui-elem-quote fhui-admin-main_hd">
            <h2>宝箱抽奖详情</h2>
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
											<input name="time" type="text" class="layui-input" value="<?php if(isset($time)) { echo $time; } ?>" id="test6" placeholder="抽奖 - 时间">
										</div>
									</div>
                                    <div class="layui-inline">
                                        <a class="layui-btn layui-btn-small" lay-submit="" lay-filter="search" data-url="/zoptimala/detail.html">
                                            <i class="fa fa-search"></i>查询
                                        </a>
										<?php if(is_rule('/zoptimala/detail_export')) { ?>
											<a class="layui-btn layui-btn-small" lay-submit="" lay-filter="excel" data-url="/zoptimala/detail_export.html">
												<i class="fa fa-search"></i>导出
											</a>
										<?php } ?>
                                        <a class="layui-btn layui-btn-small do-action" data-type="doRefresh" data-url="/zoptimala/detail.html">
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
                <form class="form-horizontal layui-form" id="formrec" method="post" role="form">
                    <table class="layui-table layui-tables" lay-skin="line" boder='1'>
                        <thead>
                            <tr>
								<th>ID</th>
								<th>用户ID</th>
                                <th>姓名</th>
								<th>电话</th>
								<th>奖品</th>
								<th>第几档</th>
								<th>奖品类型</th>
								<th>抽奖时间</th>
								<th>是否发放</th>
								<th>发放时间</th>
								<th>备注</th>
								<th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(! $detail){ ?>
                            <tr>
                                <td class="nodata" colspan="12" align='center'>暂无数据！</td>
                            </tr>
                            <?php }else{foreach ($detail as $v): ?>
                            <tr>
								<td><?php echo $v['id']; ?></td>
								<td><?php echo $v['uid']; ?></td>
                                <td><?php echo $v['real_name']; ?></td>
								<td><?php echo $v['phone']; ?></td>
								<td><?php echo $v['name'];?></td>
								<td><?php echo $v['num'];?>档</td>
								<td><?php echo $v['desc']; ?></td>
								<td><?php echo date('Y-m-d H:i', $v['addtime']); ?></td>
								<td><?php echo $v['status'] ? '已发放' : '未发放'; ?></td>
								<td><?php echo $v['uptime'] ? date('Y-m-d H:i', $v['uptime']) : '--'; ?></td>
								<td title="<?php echo $v['remark']; ?>"><?php echo $v['remark'] ? mb_substr($v['remark'], 0, 15) : '--'; ?></td>
								<td>
									<?php if($v['status'] == 0 && in_array($v['column'], $items) && is_rule('/zoptimala/provide')) {?>
										<a class="layui-btn layui-btn-sm " lay-submit="" lay-filter="js-provide" data-url="/zoptimala/provide/<?php echo $v['id']; ?>.html"><i class="layui-icon">&#xe642;</i>发放奖品</a>
									<?php } elseif(is_rule('/zoptimala/ssplit') && $v['column'] == 2) { ?>
										<a class="layui-btn layui-btn-sm" lay-submit="" lay-filter="js-tz" data-url="/zoptimala/ssplit.html?rid=<?php echo $v['id']; ?>">
                                            查看
                                        </a>
									<?php } else { ?>
										--
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
		layui.use(['layer', 'form', 'laydate'], function(){
			var $ = layui.$
			, form = layui.form
			, laydate = layui.laydate
			, layer = layui.layer;
			
			form.on('submit(js-tz)', function(data) {
				var url = $(this).data('url');
				console.log(url);
				var next_tab = $('.layui-this', parent.document).next();
				next_tab.prev().removeClass('layui-this');
				next_tab.addClass('layui-this');
				location.href = url;
				return;
			});
			
			form.on('submit(search)', function(data) {
				var url = $(this).data('url');
				var str = $('#tt').serialize();
				location.href = url + "?" + str;
				return false; 
			});
			form.on('submit(js-provide)', function(data) {
				var parent_obj = $(this).parent();
				var url = $(this).data('url');
				//console.log(url);
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
							$(data.elem).attr('disabled', true).html('发放中...');
						},
						success 	: function(data) {
							layer.msg(data.message, {time: 1500}, function() {
								if(data.state == 1) {
									location.reload();
								} else {
									parent_obj.find('a').attr('disabled', false).html('<i class="layui-icon">&#xe642;</i>发放奖品');
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
			form.on('submit(excel)', function(data) {
				var url = $(this).data('url');
				var str = JSON.stringify(data.field);
				str = str.replace(/\t/g,"");
				str = str.replace(/\"/g,"").replace("{","").replace("}","").replace(",","&").replace(":","=");
				str = str.replace(/\"/g,"").replace(/{/g,"").replace(/}/g,"").replace(/,/g,"&").replace(/:/g,"=");
				location.href = url + "?" + str;
				return false; 
			});
		});
	</script>
</body>
</html>
