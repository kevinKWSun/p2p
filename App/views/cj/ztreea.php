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
    <title>发财树记录</title>
    <link href="/src/css/layui.css" rel="stylesheet" />
    <script src="/src/layui.js"></script>
</head>
<body>
    <div class="main-wrap" style='padding:10px;'>
        <blockquote class="layui-elem-quote fhui-admin-main_hd">
            <h2>发财树记录</h2>
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
                                    <div class="layui-inline">
                                        <a class="layui-btn layui-btn-small" lay-submit="" lay-filter="search" data-url="/cj/ztree.html">
                                            <i class="fa fa-search"></i>查询
                                        </a>
                                        <a class="layui-btn layui-btn-small do-action" data-type="doRefresh" data-url="/cj/ztree.html">
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
								<th>总投资金额（33-97-168）</th>
								<th>累计剩余金额（33-97-168）</th>
								<th>已获得苹果</th>
								<th>已收割</th>
								<th>未收割</th>
								<th>未兑换</th>
								<th>添加时间</th>
								<th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(! $ztree){ ?>
                            <tr>
                                <td class="nodata" colspan="12" align='center'>暂无数据！</td>
                            </tr>
                            <?php }else{foreach ($ztree as $v): ?>
                            <tr>
								<td><?php echo $v['id']; ?></td>
                                <td><?php echo $v['uid']; ?></td>
                                <td><?php echo $v['real_name']; ?></td>
								<td><?php echo $v['idcard']; ?></td>
								<td><?php echo $v['money33'], '-', $v['money97'], '-', $v['money168']; ?></td>
								<td><?php echo $v['t33'], '-', $v['t97'], '-', $v['t168']; ?></td>
								<td><?php echo '红(',$v['num'],') 金(',$v['gold'], ')'; ?></td>
								<td><?php echo '红(',($v['nnum'] + $v['dh_red']),') 金(',($v['ngold'] + $v['dh_gold']), ')'; ?></td>
								<td><?php echo '红(',($v['num'] - $v['nnum'] - $v['dh_red']),') 金(',($v['gold'] - $v['ngold'] - $v['dh_gold']), ')'; ?></td>
								<td><?php echo '红(',$v['nnum'],') 金(',$v['ngold'], ')'; ?></td>
								<td><?php echo date('Y-m-d H:i:s', $v['addtime']); ?></td>
								<td>
									<?php if(!is_rule('/cj/ztree_provide')) { ?>
										--
									<?php } else { ?>
										<!--<a class="layui-btn layui-btn-sm" lay-submit="" lay-filter="js-provide" data-href="/cj/ztree_provide/<?php echo $v['uid']; ?>.html"><i class="icon-edit  fa fa-search"></i>发红苹果
                                        </a>--> --
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
			, layer = layui.layer;
			
			form.on('submit(search)', function(data) {
				var url = $(this).data('url');
				var str = $('#tt').serialize();
				location.href = url + "?" + str;
				return false; 
			});
			form.on('submit(js-provide)', function(data) {
				//var parent_obj = $(this).parent();
				var url = $(this).data('href');
				layer.prompt({
				  formType: 0,
				  value: 0,
				  maxlength: 3,
				  title: '发红苹果'
				  //area: ['300px', '100px'] //自定义文本域宽高
				}, function(value, index, elem){
					layer.load(1);
					$.ajax({
						url 		: url,
						type		: 'post',
						data 		: {value : value},
						dataType	: 'json',
						beforeSend	: function () {
							$(data.elem).attr('disabled', true).html('变更中...');
						},
						success 	: function(data) {
							layer.msg(data.message, {time: 1500}, function() {
								if(data.state == 1) {
									location.reload();
								}
							});
						},
						complete	: function() {
							$(data.elem).attr('disabled', false).html('<i class="icon-edit  fa fa-search"></i>发红苹果');
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
