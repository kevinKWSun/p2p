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
    <title>投标列表</title>
    <link href="/src/css/layui.css" rel="stylesheet" />
    <script src="/src/layui.js"></script>
</head>
<body>
    <div class="main-wrap" style='padding:10px;'>
        <blockquote class="layui-elem-quote fhui-admin-main_hd">
            <h2>借款列表</h2>
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
										<div class="layui-input-inline" style="width:240px;">
                                            <input name="username" value="<?php if(isset($username)) { echo $username; } ?>" autocomplete="off" placeholder="投资用户:ID/真实姓名/手机号" class="layui-input" type="text" />
                                        </div>
										<div class="layui-input-inline">
                                            <input name="codename" value="<?php if(isset($codename)) { echo $codename; } ?>" autocomplete="off" placeholder="推荐用户:ID/真实姓名" class="layui-input" type="text" />
                                        </div>
										<div class="layui-input-inline">
											<div class="layui-input-inline">
												<input name="time" type="text" class="layui-input" value="<?php if(isset($time)) { echo $time; } ?>" id="test6" placeholder="投资 - 时间">
											</div>
                                        </div>
                                    </div>
                                    <div class="layui-inline">
                                        <a class="layui-btn layui-btn-small" lay-submit="" lay-filter="search" data-url="/bid/index.html">
                                            <i class="fa fa-search"></i>查询
                                        </a>
                                        <a class="layui-btn layui-btn-small do-action" data-type="doRefresh" data-url="/bid.html">
                                            <i class="layui-icon">&#xe669;</i>重新载入
                                        </a>
										<a class="layui-btn layui-btn-small" lay-submit="" lay-filter="excel" data-url="/bid/export.html">
                                            <i class="fa fa-search"></i>导出
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
                                <th>投资ID</th>
                                <th>投资用户ID</th>
                                <th>真实姓名</th>
                                <th>手机号</th>
                                <th>身份证号</th>
								<th>注册时间</th>
                                <th>标的ID</th>
                                <th>标题</th>
								<th>还款方式</th>
                                <th>标的期限</th>
                                <th>投资金额</th>
                                <th>投资日期</th>
								<th>到期日</th>
                                <th>状态</th>
                                <th>推荐人ID</th>
                                <th>推荐人</th>
								<th>首投</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                        	<?php if(! $invest){ ?>
                            <tr>
                                <td class="nodata" colspan="19" align='center'>暂无数据！</td>
                            </tr>
                            <?php }else{foreach ($invest as $v): ?>
                            <tr>
                                <td>
									<div class="layui-input-inline">
										<input ids="<?php echo $v['id']; ?>" lay-skin="primary" type="checkbox" />
									</div>
                                </td>
								<td><?php echo $v['id']; ?></td>
								<td><?php echo $v['investor_uid']; ?></td>
								<td><?php echo $v['real_name']; ?></td>
								<td><?php echo $v['phone']; ?></td>
								<td><?php echo $v['idcard']; ?></td>
								<td><?php echo date('Y-m-d', $v['reg_time']); ?></td>
								<td><?php echo $v['bid'] . '/' . $v['borrow_no']; ?></td>
								<td><?php echo $v['borrow_type'] == 2 ? $v['borrow_name'].'<font color="red">[新]</font>' : $v['borrow_name']; ?></td>
								<td><?php echo $this->config->item('repayment_type')[$v['repayment_type']]; ?></td>
								<td><?php echo $this->config->item('borrow_duration')[$v['borrow_duration']]; ?></td>
								<td><?php echo $v['investor_capital']; ?></td>
								<td><?php echo date('Y-m-d H:i:s', $v['add_time']); ?></td>
								<td><?php echo $v['deadline'] > 0 ? date('Y-m-d H:i:s', $v['deadline']) : '-'; ?></td>
								<td><?php echo $this->config->item('borrow_status')[$v['status']]; ?></td>
								<td><?php echo $v['codeuid'] > 0 ? $v['codeuid'] : '无'; ?></td>
								<td><?php echo !empty($v['code_name']) ? $v['code_name'] : '无'; ?></td>
								<td><?php echo $v['first'] ? '是' : '否'?></td>
								<td><a href="/invest/show/<?php echo $v['bid'] ?>.html" target="_blank">查看</a></td>
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
			form.on('submit(excel)', function(data) {
				var url = $(this).data('url');
				var str = JSON.stringify(data.field);
				str = str.replace(/\t/g,"");
				str = str.replace(/\"/g,"").replace("{","").replace("}","").replace(",","&").replace(":","=");
				str = str.replace(/\"/g,"").replace(/{/g,"").replace(/}/g,"").replace(/,/g,"&").replace(/:/g,"=");
				location.href = url + "?" + str;
				return false; 
			});
			// form.on('submit(excel)', function(data) {
				// var url = $(this).data('url');
				// console.log($('#test6').val());
				// location.href = url + "?" + 'query='+$('#test6').val();
			// });
			laydate.render({
				elem: '#test6'
				,range: true
			});
		});
	</script>
</body>
</html>