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
    <title>大乐透列表</title>
    <link href="/src/css/layui.css" rel="stylesheet" />
    <script src="/src/layui.js"></script>
</head>
<body>
    <div class="main-wrap" style='padding:10px;'>
        <blockquote class="layui-elem-quote fhui-admin-main_hd">
            <h2>大乐透列表</h2>
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
                                            <input name="phone" value="<?php if(isset($phone)) { echo $phone; } ?>" autocomplete="off" placeholder="手机号" class="layui-input" type="text" />
                                        </div>
                                    </div>
									<div class="layui-inline">
                                        <div class="layui-input-block" style="margin-left: 0px">
                                            <input name="rname" value="<?php if(isset($rname)) { echo $rname; } ?>" autocomplete="off" placeholder="真实姓名" class="layui-input" type="text" />
                                        </div>
                                    </div>
									<div class="layui-input-inline">
										<div class="layui-input-inline">
											<input name="time" type="text" class="layui-input" value="<?php if(isset($time)) { echo $time; } ?>" id="test6" placeholder="投注 - 时间">
										</div>
									</div>
                                    <div class="layui-inline">
                                        <a class="layui-btn layui-btn-small" lay-submit="" lay-filter="search" data-url="/cj/dlt_list.html">
                                            <i class="fa fa-search"></i>查询
                                        </a>
										<?php if(is_rule('/cj/dlt_export')) { ?>
											<a class="layui-btn layui-btn-small" lay-submit="" lay-filter="js-export" data-url="/cj/dlt_export.html">
												<i class="fa fa-search"></i>导出
											</a>
										<?php } ?>
                                        <a class="layui-btn layui-btn-small do-action" data-type="doRefresh" data-url="/cj/dlt_list.html">
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
								<th>真实姓名</th>
								<th>手机号</th>
								<th>身份证号</th>
                                <th>选中号码</th>
								<th>倍数</th>
								<th>状态</th>
								<th>添加时间</th>
								<th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(! $dlt){ ?>
                            <tr>
                                <td class="nodata" colspan="8" align='center'>暂无数据！</td>
                            </tr>
                            <?php }else{foreach ($dlt as $v): ?>
                            <tr>
								<td><?php echo $v['id']; ?></td>
								<td><?php echo $v['real_name']; ?></td>
                                <td><?php echo $v['phone']; ?></td>
								<td><?php echo $v['idcard']; ?></td>
                                <td><?php echo $v['num']; ?></td>
								<td><?php echo $v['multiple']; ?></td>
								<td><?php echo $v['status'] ? '已处理' : '未处理'; ?></td>
								<td><?php echo date('Y-m-d H:i', $v['addtime']); ?></td>
								<td>--</td>
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
			
			form.on('submit(search)', function(data) {
				var url = $(this).data('url');
				var str = $('#tt').serialize();
				location.href = url + "?" + str;
				return false; 
			});
			//导出
			form.on('submit(js-export)', function(data) {
				var url = $(this).data('url');
				var str = JSON.stringify(data.field);
				str = str.replace(/\t/g,"");
				str = str.replace(/\"/g,"").replace("{","").replace("}","").replace(",","&").replace(":","=");
				str = str.replace(/\"/g,"").replace(/{/g,"").replace(/}/g,"").replace(/,/g,"&").replace(/:/g,"=");
				location.href = url + "?" + str;
				return false; 
			});
			laydate.render({
				elem: '#test6'
				,range: true
			});
		});
	</script>
</body>
</html>
