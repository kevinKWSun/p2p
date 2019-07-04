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
    <title>规则列表</title>
    <link href="/src/css/layui.css" rel="stylesheet" />
    <script src="/src/layui.js"></script>
</head>
<body>
    <div class="main-wrap" style='padding:10px;'>
        <blockquote class="layui-elem-quote fhui-admin-main_hd">
            <h2>规则列表</h2>
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
                                        <a class="layui-btn layui-btn-small" id="js-total" data-url="/zreversala/total_rate.html">
                                            <i class="fa fa-search"></i>总概率
                                        </a>
										<a class="layui-btn layui-btn-small" id="js-test" data-url="/zreversala/test.html">
                                            <i class="fa fa-search"></i>测试
                                        </a>
                                        <a class="layui-btn layui-btn-small do-action" data-type="doRefresh" data-url="/zreversala/prob.html">
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
								<th>
									<div class="layui-input-inline">
										<input type="checkbox" lay-skin="primary" lay-filter="selected-all" />
									</div>
								</th>
								<th>ID</th>
								<th>规则</th>
                                <th>概率</th>
								<th>描述</th>
								<th>状态</th>
								<th>添加时间</th>
								<th>修改时间</th>
								<th>修改人</th>
								<th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(! $rule){ ?>
                            <tr>
                                <td class="nodata" colspan="10" align='center'>暂无数据！</td>
                            </tr>
                            <?php }else{foreach ($rule as $v): ?>
                            <tr>
								<td>
									<div class="layui-input-inline">
										<input name="ids[]" value="<?php echo $v['id']; ?>" lay-skin="primary" type="checkbox" />
									</div>
                                </td>
								<td><?php echo $v['id']; ?></td>
                                <td><?php echo $v['rule']; ?></td>
                                <td><?php echo $v['rat']; ?></td>
								<td><?php echo $v['desc']; ?></td>
								<td><?php echo $v['status'] > 0 ? '禁用' : '启用'; ?></td>
								<td><?php echo $v['addtime'] > 0 ? date('Y-m-d H:i:s',$v['addtime']) : '-'; ?></td>
								<td><?php echo $v['uptime'] > 0 ? date('Y-m-d H:i:s',$v['uptime']) : '-'; ?></td>
								<td><?php echo $v['adminid'] > 0 ? get_user($v['id'])['realname'] : '-'; ?></td>
								<td>
									<?php if(is_rule('/zreversala/change_rat') && $v['status'] == 0) { ?>
										<a class="layui-btn layui-btn-sm" lay-submit="" lay-filter="js-modify" data-href="/zreversala/change_rat/<?php echo $v['id']; ?>.html"><i class="layui-icon">&#xe642;</i>编辑</a>
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
        </div>
    </div>
	<script src="/src/global.js"></script>
	<script type="text/javascript">
		layui.use(['layer', 'form'], function(){
			var $ = layui.$
			, form = layui.form
			, layer = layui.layer;
			
			$('#js-test').click(function() {
				var url = $(this).attr('data-url');
				layer.open({
					type: 2,
					title: '编辑',
					//shadeClose: true,
					shade: 0.1,
					maxmin: true,
					area: ['35%', '45%'],
					fixed: true,
					content: url
				});
			});
			
			$('#js-total').click(function() {
				var url = $(this).attr('data-url');
				$.get(url, function(r) {
					layer.msg(r.message + '%');
				}, 'json');
				
			});
			form.on('submit(js-modify)', function(data) {
				var url = $(this).data('href');
				layer.prompt({
				  formType: 0,
				  value: 0,
				  maxlength: 6,
				  title: '修改概率'
				  //area: ['300px', '100px'] //自定义文本域宽高
				}, function(value, index, elem){
					layer.load(1);
					$.ajax({
						url 		: url,
						type		: 'post',
						data 		: {value : value},
						dataType	: 'json',
						beforeSend	: function () {
							$(data.elem).attr('disabled', true).html('修改中...');
						},
						success 	: function(data) {
							layer.msg(data.message, {time: 1500}, function() {
								if(data.state == 1) {
									location.reload();
								}
							});
						},
						complete	: function() {
							$(data.elem).attr('disabled', false).html('<i class="layui-icon">&#xe642;</i>编辑');
						},
						error 		: function(data) {
							 console.info("error: " + data.responseText);
						}
					});
					layer.closeAll();
				});
				return;
			});
		});
	</script>
</body>
</html>
