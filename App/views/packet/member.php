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
    <title>会员信息</title>
    <link href="/src/css/layui.css" rel="stylesheet" />
    <script src="/src/layui.js"></script>
</head>
<body>
    <div class="main-wrap" style='padding:10px;'>
        <blockquote class="layui-elem-quote fhui-admin-main_hd">
            <h2>会员信息</h2>
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
                                            <input name="skey" value="<?php if(isset($skey)) { echo $skey; } ?>" autocomplete="off" placeholder="ID/真实姓名/手机号" class="layui-input" type="text" />
                                        </div>
                                    </div>
                                    <div class="layui-inline">
										
										<?php if(isset($type)) { ?>
											<a class="layui-btn layui-btn-small" lay-submit="" lay-filter="search" data-url="/packet/member/1.html">
												<i class="fa fa-search"></i>查询
											</a>
											<a class="layui-btn layui-btn-small" lay-submit="" lay-filter="send" data-url="/packet/sendt.html">
												<i class="fa fa-search"></i>发放
											</a>
											<a class="layui-btn layui-btn-small do-action" data-type="doAdd" data-url="/packet/lists.html">
												<i class="fa fa-search"></i>返回上一页
											</a>
											<a class="layui-btn layui-btn-small do-action" data-type="doRefresh" data-url="/packet/member/1.html">
												<i class="layui-icon">&#xe669;</i>重新载入
											</a>
										<?php } else { ?>
											<a class="layui-btn layui-btn-small" lay-submit="" lay-filter="search" data-url="/packet/member.html">
												<i class="fa fa-search"></i>查询
											</a>
											<a class="layui-btn layui-btn-small" lay-submit="" lay-filter="send" data-url="/packet/send.html">
												<i class="fa fa-search"></i>发放
											</a>
											<a class="layui-btn layui-btn-small do-action" data-type="doAdd" data-url="/packet.html">
												<i class="fa fa-search"></i>返回上一页
											</a>
											<a class="layui-btn layui-btn-small do-action" data-type="doRefresh" data-url="/packet/member.html">
												<i class="layui-icon">&#xe669;</i>重新载入
											</a>
										<?php } ?>
										
                                        
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!--/工具栏-->
            <div class="fhui-admin-table-container">
                <form class="layui-form form-horizontal" id="formrec" method="post" role="form">
                    <table class="layui-table layui-tables" lay-skin="line" boder='1'>
                        <thead>
                            <tr>
								<th>
									<div class="layui-input-inline">
										<input type="checkbox" lay-skin="primary" lay-filter="selected-all" />
									</div>
								</th>
								<th>ID</th>
								<th>真实姓名</th>
								<th>手机号</th>
								<th>认证状态</th>
								<th>注册日期</th>
								<th>用户状态</th>
								<th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(! $member){ ?>
								<tr>
									<td class="nodata" colspan="8" align='center'>暂无数据！</td>
								</tr>
                            <?php }else{foreach ($member as $v): ?>
								<tr>
									<td>
										<div class="layui-input-inline">
											<input ids="<?php echo $v['id']; ?>" lay-skin="primary" type="checkbox" />
										</div>
									</td>
									<td><?php echo $v['id']; ?></td>
									<td><?php echo $v['real_name']; ?></td>
									<td><?php echo $v['user_name']; ?></td>
									<td><?php echo $v['id_status'] ? '已认证' : '未认证'; ?></td>
									<td><?php echo date('Y-m-d', $v['reg_time']); ?></td>
									<td><?php echo $v['is_ban'] ? '锁定' : '正常'; ?></td>
									<td>
										<a class="layui-btn layui-btn-sm" data-type="doShow" data-url="#">
											<i class="layui-icon">&#xe621;</i>查看
										</a>
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
				var str = JSON.stringify(data.field);
				str = str.replace(/\t/g,"");
				str = str.replace(/\"/g,"").replace("{","").replace("}","").replace(",","&").replace(":","=");
				str = str.replace(/\"/g,"").replace(/{/g,"").replace(/}/g,"").replace(/,/g,"&").replace(/:/g,"=");
				location.href = url + "?" + str;
				return false; 
			});
			form.on('submit(send)', function(data) {
				var url = $(this).data('url');
				if ($(".layui-table tbody input:checked").size() < 1) {
					layer.closeAll();
					layer.msg('对不起,请选中您要操作的记录！');
					return false;
				}
				var ids = "";
				var checkObj = $(".layui-table tbody input:checked");
				if(checkObj.length > 1){
					for (var i = 0; i < checkObj.length; i++) {
						if (checkObj[i].checked && $(checkObj[i]).attr("disabled") != "disabled"){
							ids += $(checkObj[i]).attr("ids") + ','; //如果选中，将value添加到变量idlist中   
						} 
					}
				}else{
					if (checkObj[0].checked && $(checkObj[0]).attr("disabled") != "disabled"){
						ids += $(checkObj[0]).attr("ids") + ',';
					}
				}
				//var data = { "ids": ids };
				layer.open({
					type: 2,
                    title: '详情',
                    shade: 0.1,
                    maxmin: true,
                    area: ['40%', '90%'],
                    fixed: true,
                    content: url + '?ids=' + ids
				});
			});
		});
		//查出选择的记录
			
	</script>
</body>
</html>
