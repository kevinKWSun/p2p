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
    <title>福袋发放记录</title>
    <link href="/src/css/layui.css" rel="stylesheet" />
    <script src="/src/layui.js"></script>
</head>
<body>
    <div class="main-wrap" style='padding:10px;'>
        <blockquote class="layui-elem-quote fhui-admin-main_hd">
            <h2>福袋发放记录</h2>
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
                                        <a class="layui-btn layui-btn-small" lay-submit="" lay-filter="search" data-url="/cj/zcash_record.html">
                                            <i class="fa fa-search"></i>查询
                                        </a>
                                        <a class="layui-btn layui-btn-small do-action" data-type="doRefresh" data-url="/cj/zcash_record.html">
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
                                <th>电话</th>
								<th>姓名</th>
								<th>次数</th>
								<th>倍数</th>
								<th>发放时间</th>
								<th>标的编号</th>
								<th>备注</th>
								<th>操作人</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(! $order){ ?>
                            <tr>
                                <td class="nodata" colspan="10" align='center'>暂无数据！</td>
                            </tr>
                            <?php }else{foreach ($order as $v): ?>
                            <tr>
								<td><?php echo $v['id']; ?></td>
                                <td><?php echo $v['phone']; ?></td>
								<td><?php echo $v['real_name']; ?></td>
								<td>
									<?php echo $v['num']; ?>次
								</td>
								<td><?php echo $v['multiple']; ?>倍</td>
								<td>
									<?php echo date('Y-m-d H:i', $v['addtime']); ?>
								</td>
								<td><?php echo isset($v['borrowno']) ? $v['borrowno'] : '' ?></td>
								<td><?php echo !empty($v['adminid']) ? $v['remark'] : '系统发放' ?></td>
								<td>
									<?php echo !empty($v['adminid']) ? get_user($v['adminid'])['realname'] : '系统' ?>
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
			
			$('.js-modify').click(function() {
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
		});
	</script>
</body>
</html>
