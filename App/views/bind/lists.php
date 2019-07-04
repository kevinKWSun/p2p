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
										<div class="layui-input-inline" style="width:240px;">
                                            <input name="username" value="<?php if(isset($username)) { echo $username; } ?>" autocomplete="off" placeholder="用户:ID/真实姓名/手机号" class="layui-input" type="text" />
                                        </div>
                                    </div>
                                    <div class="layui-inline">
                                        <a class="layui-btn layui-btn-small" lay-submit="" lay-filter="search" data-url="/bind/lists.html">
                                            <i class="fa fa-search"></i>查询
                                        </a>
                                        <a class="layui-btn layui-btn-small do-action" data-type="doRefresh" data-url="/bind/lists.html">
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
                                <th>用户ID</th>
                                <th>真实姓名</th>
                                <th>手机号</th>
                                <th>身份证号</th>
								<th>手持银行卡</th>
								<th>原银行卡号</th>
                                <th>手持身份证</th>
                                <th>申请时间</th>
								<th>状态</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                        	<?php if(! $bind){ ?>
                            <tr>
                                <td class="nodata" colspan="9" align='center'>暂无数据！</td>
                            </tr>
                            <?php }else{foreach ($bind as $v): ?>
                            <tr>
                                <td>
									<div class="layui-input-inline">
										<input ids="<?php echo $v['id']; ?>" lay-skin="primary" type="checkbox" />
									</div>
                                </td>
								<td><?php echo $v['id']; ?></td>
								<td><?php echo $v['uid']; ?></td>
								<td><?php echo $v['real_name']; ?></td>
								<td><?php echo $v['phone']; ?></td>
								<td><?php echo $v['idcard']; ?></td>
								<td>
									<img src="<?php echo substr($v['hand_bank_card'], 1); ?>" style="height:35px;"/>
								</td>
								<td>
									<?php 
										if($v['status'] == 3) {  
											echo $v['bank_card'];
										} else { 
											echo $v['paycard'];
										} 
									?>
								</td>
								<td>
									<img src="<?php echo substr($v['hand_identity_card'], 1); ?>" style="height:35px;" />
								</td>
								<td><?php echo date('Y-m-d H:i', $v['addtime']); ?></td>
								<td>
									<?php 
										switch($v['status']) { 
											case 0:
												echo '申请中';
											break;
											case 1:
												echo '通过';
											break;
											case 2:
												echo '拒绝';
											break;
											case 3:
												echo '已修改';
											break;
											default:
												echo '有误';
										}
									?>
								</td>
								<td>
									<?php if($v['status'] == 0 && is_rule('/bind/lists')) { ?>
										<a class="layui-btn layui-btn-sm" lay-submit="" lay-filter="js-audit" data-href="/bind/audit/<?php echo $v['id'] ?>/1.html">同意</a>
										<a class="layui-btn layui-btn-sm" lay-submit="" lay-filter="js-audit" data-href="/bind/audit/<?php echo $v['id'] ?>/2.html">拒绝</a>
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
			$('img').click(function() {
				var src = $(this).attr('src');
				layer.open({
					type: 1,
					title: '预览',
					shade: 0.1,
					maxmin: true,
					area: ['45%', '75%'],
					fixed: true,
					content: '<div style="margin-top:15px; margin-bottom:10px; text-align:center;"><img style="width:600px; border:solid 2px #999999;" src="' + src + '" /></div>'
				});
			});
			
			form.on('submit(js-audit)', function(data) {
				var href = $(this).data('href');
				$.post(href, {}, function(r) {
					var icon = r.state ? 6 : 5;
					layer.msg(r.message, {icon : icon, time : 1500}, function() {
						if(r.state) {
							location.reload();
						} else {
							layer.closeAll();
						}
					});
				}, 'json');
			});
		});
	</script>
</body>
</html>