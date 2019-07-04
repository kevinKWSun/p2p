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
                                            <input name="flow" value="<?php if(isset($flow)) { echo $flow; } ?>" autocomplete="off" placeholder="流水号" class="layui-input" type="text" />
                                        </div>
                                    </div>
                                    <div class="layui-inline">
                                        <a class="layui-btn layui-btn-small" lay-submit="" lay-filter="search" data-url="/deal/bid.html">
                                            <i class="fa fa-search"></i>查询
                                        </a>
                                        <a class="layui-btn layui-btn-small do-action" data-type="doRefresh" data-url="/deal/bid.html">
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
								<th>标的ID</th>
                                <th>标题</th>
                                <th>出借人姓名</th>
                                <th>手机号</th>
                                <th>身份证号</th>
                                <th>投资金额(元)</th>
								<th>流水号</th>
								<th>商户订单号</th>
                                <th>状态</th>
								
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                        	<?php if(! $water){ ?>
                            <tr>
                                <td class="nodata" colspan="17" align='center'>暂无数据！</td>
                            </tr>
                            <?php } else{ ?>
                            <tr>
								<td><?php echo $borrow['id']; ?></td>
                                <td><?php echo $borrow['borrow_name']; ?></td>
                                <td><?php echo $member['real_name']; ?></td>
                                <td><?php echo $member['phone']; ?></td>
                                <td><?php echo $member['idcard']; ?></td>
                                <td><?php echo $water['money']; ?></td>
								<td><?php echo $water['bizFlow']; ?></td>
								<td><?php echo $water['merOrderNo']; ?></td>
                                <td><?php echo $water['respDesc']; ?></td>
                                <td>
									<?php if(true) { ?>
										<a class="layui-btn layui-btn-sm js-show" data-href="/deal/show/<?php echo $water['id']; ?>.html">查看</a>
									<?php } ?>
									
								</td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </form>
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
			$('.js-show').click(function() {
				//var id = $(this).attr('data-id');
				var href = $(this).data('href');
				layer.open({
					type: 2,
					title: '投标信息',
					shade: 0.1,
					maxmin: true,
					area: ['45%', '90%'],
					fixed: true,
					content: href
				});
			});
		});
	</script>
</body>
</html>