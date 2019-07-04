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
    <title>订单管理</title>
	<link href="/src/css/layui.css" rel="stylesheet" />
    <script src="/src/layui.js"></script>
</head>
<body>
    <div class="main-wrap" style='padding:10px;'>
        <blockquote class="layui-elem-quote fhui-admin-main_hd">
            <h3>订单管理</h3>
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
                                            <input name="skey" lay-verify="" value="" autocomplete="off" placeholder="手机号/姓名" class="layui-input" type="text" />
                                        </div>
                                    </div>
									<div class="layui-inline">
										<a class="layui-btn layui-btn-small" lay-submit="" lay-filter="solor" data-url="/cate/order.html">
                                            <i class="layui-icon">&#xe615;</i>查询
                                        </a>
									</div>
									<div class="layui-inline">
										<input type="text" class="layui-input" id="test1" placeholder="开始时间" />
									</div>
									<div class="layui-inline">
										<input type="text" class="layui-input" id="test2" placeholder="结束时间" />
									</div>
									<div class="layui-inline">
										<a class="layui-btn layui-btn-sm layui-btn-small" lay-submit="" lay-filter='excel' data-url="/excel/order.html">
											<i class="layui-icon"></i>导出
										</a>
										<a class="layui-btn layui-btn-sm do-action" data-type="doAction" data-url="/cate/allfh.html">
											<i class="layui-icon">&#xe6b2;</i>批量发货
										</a>
										<a class="layui-btn layui-btn-sm do-action" data-type="doRefresh" data-url="/cate/order.html">
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
								<th>
									<div class="layui-input-inline">
										<input type="checkbox" lay-skin="primary" lay-filter="selected-all" />
									</div>
								</th>
								<th>ID</th>
								<th>客户姓名</th>
								<th>客户手机号</th>
                                <th>商品名称</th>
								<th>商品图片</th>
								<th>应付积分</th>
								<th>实付积分</th>
								<th>地址</th>
                                <th>电话</th>
								<th>姓名</th>
								<th>状态</th>
								<th>发货时间</th>
								<th>时间</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                        	<?php if(! $cate){ ?>
                            <tr>
                                <td class="nodata" colspan="15" align='center'>暂无数据！</td>
                            </tr>
                            <?php }else{foreach ($cate as $v): ?>
                            <tr>
								<td>
									<div class="layui-input-inline">
										<?php if(! $v['status']){?>
										<input ids="<?php echo $v['id']; ?>" lay-skin="primary" type="checkbox" />
										<?php }?>
									</div>
                                </td>
								<td><?php echo $v['id']; ?></td>
								<td><?php echo $v['user_name']; ?></td>
								<td><?php echo $v['user_phone']; ?></td>
                                <td><?php echo $v['gname']; ?></td>
								<td><img src="<?php echo $v['img']; ?>" width='80' /></td>
								<td><?php echo $v['score']*$v['num']; ?></td>
								<td><?php echo $v['sscore']; ?></td>
								<td><?php echo unserialize($v['amark'])['address']; ?></td>
								<td><?php echo unserialize($v['amark'])['tel']; ?></td>
								<td><?php echo unserialize($v['amark'])['realname']; ?></td>
								<td><?php echo $v['status'] ? '已发货' : '待发货'; ?></td>
								<td><?php if($v['uptime']){echo date('Y-m-d',$v['uptime']);}elseif($v['puptime']){echo date('Y-m-d',$v['puptime']);}else{echo '--';} ?></td>
                                <td><?php echo date('Y-m-d',$v['addtime']); ?></td>
                                <td><?php if($v['status'] == 0){?>
                                    <a class="layui-btn layui-btn-sm do-action" data-type="doShow" data-url="/cate/oedit/<?php echo $v['id']; ?>.html"><i class="layui-icon">&#xe642;</i>编辑</a>
								<?php }else{echo '--';}?>
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
		layui.use(['layer', 'form', 'laydate'], function(){
			var $ = layui.$
			, form = layui.form
			, laydate = layui.laydate
			, layer = layui.layer;
			form.on('submit(excel)', function(data) {
				var url = $(this).data('url');
				location.href = url + "?" + 'time1='+$('#test1').val()+'&time2='+$('#test2').val();
			});
			laydate.render({
				elem: '#test1'
			});
			laydate.render({
				elem: '#test2'
			});
		});
	</script>
</body>
</html>