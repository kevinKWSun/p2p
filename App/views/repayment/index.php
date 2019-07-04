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
    <title>还款统计</title>
    <link href="/src/css/layui.css" rel="stylesheet" />
    <script src="/src/layui.js"></script>
</head>
<body>
    <div class="main-wrap" style='padding:10px;'>
        <blockquote class="layui-elem-quote fhui-admin-main_hd">
            <h2>还款统计</h2>
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
                                        <div class="layui-input-inline">
                                            <input name="name" value="<?php if(isset($name)) { echo $name; } ?>" autocomplete="off" placeholder="标的名称/编号" class="layui-input" type="text" />
                                        </div>
										<div class="layui-input-inline">
											<div class="layui-input-inline">
												<input name="time" type="text" class="layui-input" value="<?php if(isset($time)) { echo $time; } ?>" id="test6" placeholder="发生 - 时间">
											</div>
                                        </div>
										<div class="layui-input-inline">
											<select name="status" lay-verify="required" lay-search="">
												<option value="0">--请选择--</option>
												<option value="1" <?php if(isset($status) && $status == 1) { echo 'selected'; } ?>>已还</option>
												<option value="2" <?php if(isset($status) && $status == 2) { echo 'selected'; } ?>>未还</option>
											</select>
										</div>
                                    </div>
                                    <div class="layui-inline">
                                        <a class="layui-btn layui-btn-small" lay-submit="" lay-filter="search" data-url="/repayment/index.html">
                                            <i class="fa fa-search"></i>查询
                                        </a>
										<a class="layui-btn layui-btn-small" lay-submit="" lay-filter="js-export" data-url="/repayment/export.html">
                                            <i class="fa fa-search"></i>导出
                                        </a>
                                        <a class="layui-btn layui-btn-small do-action" data-type="doRefresh" data-url="/repayment.html">
                                            <i class="layui-icon">&#xe669;</i>重新载入
                                        </a>
										<!--<a class="layui-btn layui-btn-small" lay-submit="" lay-filter="excel" data-url="/excel.html">
                                            <i class="fa fa-search"></i>导出
                                        </a>-->
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
                                <th>还款ID</th>
                                <th>标的ID</th>
                                <th>标题</th>
                                <th>还款日期</th>
                                <th>期数</th>
                                <th>还款方式</th>
								<th>待还总额</th>
                                <th>待还本金</th>
                                <th>待还利息</th>
                                <th>状态</th>
								<th>还款操作人</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                        	<?php if(! $detail){ ?>
                            <tr>
                                <td class="nodata" colspan="13" align='center'>暂无数据！</td>
                            </tr>
                            <?php }else{foreach ($detail as $v): ?>
                            <tr>
                                <td>
									<div class="layui-input-inline">
										<input ids="<?php echo $v['borrow']['id']; ?>" lay-skin="primary" type="checkbox" />
									</div>
                                </td>
								<td><?php echo $v['borrow']['borrow_no']; ?></td>
                                <td><?php echo $v['borrow']['id']; ?></td>
                                <td><?php echo $v['borrow']['borrow_type'] == 2 ? $v['borrow']['borrow_name'].'<font color="red">[新]</font>' : $v['borrow']['borrow_name']; ?></td>
                                <td><?php echo date('Y-m-d', $v['deadline']); ?></td>
                                <td><?php echo $v['sort_order'],'/',$v['total']; ?></td>
                                <td><?php echo $this->config->item('repayment_type')[$v['borrow']['repayment_type']]; ?></td>
								<td><?php echo ($v['detail']['capital'] + $v['detail']['interest']); ?>元</td>
                                <td><?php echo $v['detail']['capital']; ?></td>
                                <td><?php echo $v['detail']['interest']; ?></td>
                                <td>
									<?php 
										if($v['status']['status'] > 4 || $v['detail']['repayment_time'] > 0) {
											if($v['status']['total'] > 1) {
												echo '第' . $v['status']['sort_order'] . '期已还';
											} else {
												echo '已还';
											}
										} else {
											echo '未还';
										}
										//echo ($v['status'] > 4 || $v['detail']['repayment_time'] > 0) ? '已还' : '未还'; 
									?>
								</td>
								<td><?php echo $v['detail']['adminname']; ?></td>
								<td><a class="layui-btn layui-btn-sm do-action" data-type="doEdit" data-url="/repayment/detail/<?php echo $v['deadline'] ?>.html" target="_blank">还款详情</a></td>
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
			form.on('submit(search)', function(data) {
				var url = $(this).data('url');
				var str = JSON.stringify(data.field);
				str = str.replace(/\t/g,"");
				str = str.replace(/\"/g,"").replace("{","").replace("}","").replace(",","&").replace(":","=");
				str = str.replace(/\"/g,"").replace(/{/g,"").replace(/}/g,"").replace(/,/g,"&").replace(/:/g,"=");
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