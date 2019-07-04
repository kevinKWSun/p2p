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
    <title>担保人信息</title>
	<link href="/src/css/layui.css" rel="stylesheet" />
    <script src="/src/layui.js"></script>
</head>
<body>
    <div class="main-wrap" style='padding:10px;'>
        <blockquote class="layui-elem-quote fhui-admin-main_hd">
            <h3>担保人信息</h3>
        </blockquote>
        <div class="y-role">
            <!--工具栏-->
            <div id="floatHead" class="toolbar-wrap">
                <div class="toolbar">
                    <div class="box-wrap">
                        <div class="l-list clearfix">
                            <form id="tt" class="layui-form layui-form-pane">
                                <div class="layui-form-item">
									<div  class="layui-inline">
										<div class="layui-input-block" style="margin-left: 0px">
                                            <input name="guarantor_id" value="<?php if(isset($guarantor_id)) { echo $guarantor_id; } ?>" autocomplete="off" placeholder="担保人ID" class="layui-input" type="text" />
                                        </div>
									</div>
                                    <div class="layui-inline">
                                        <div class="layui-input-block" style="margin-left: 0px">
                                            <input name="skey" value="<?php if(isset($skey)) { echo $skey; } ?>" autocomplete="off" placeholder="姓名/手机号/身份证号" class="layui-input" type="text" />
                                        </div>
                                    </div>
                                    <div class="layui-inline">
										<a class="layui-btn layui-btn-sm" lay-submit="" lay-filter="search" data-url="/guarantor.html">
                                            <i class="fa fa-search"></i>查询
                                        </a>
										<?php if(is_rule("/guarantor/export")) { ?>
											<a class="layui-btn layui-btn-sm" lay-submit="" lay-filter="js-export" data-url="/guarantor/export.html">
												<i class="fa fa-search"></i>导出
											</a>
										<?php } ?>
										<a class="layui-btn layui-btn-sm do-action" data-type="doAdd" data-url="/guarantor/add.html">
                                            <i class="layui-icon">&#xe61f;</i>新增
                                        </a>
                                        <a class="layui-btn layui-btn-sm do-action" data-type="doRefresh" data-url="/guarantor.html">
                                            <i class="layui-icon">&#xe669;</i>重新载入
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="fhui-admin-table-container">
                <form class="form-horizontal" id="formrec" method="post" role="form">
                    <table class="layui-table">
                        <thead>
                            <tr>
								<th>ID</th>
                                <th>姓名</th>
                                <th>手机号</th>
                                <th>身份证号</th>
                                <th>添加时间</th>
								<th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(! $guarantor){ ?>
                            <tr>
                                <td class="nodata" colspan="6" align='center'>暂无数据！</td>
                            </tr>
                            <?php }else{foreach ($guarantor as $v): ?>
                            <tr>
								<td><?php echo $v['id']; ?></td>
                                <td class='sed'><?php echo $v['name']; ?></td>
                                <td><?php echo $v['phone']; ?></td>
                                <td><?php echo $v['idcard']; ?></td>
                                <td><?php echo date('Y-m-d', $v['addtime']); ?></td>
								<td>
									<a class="layui-btn layui-btn-sm do-action" data-type="doShow" data-url="/guarantor/modify/<?php echo $v['id']; ?>.html">
										<i class="layui-icon">&#xe621;</i>编辑
									</a>
									<? if(is_rule('/guarantor/esign')) { ?>
										<a class="layui-btn layui-btn-sm" lay-submit="" lay-filter="js-esign" data-url="/guarantor/esign/<?php echo $v['id'] ?>.html">
											<i class="layui-icon">&#xe621;</i>生成签章
										</a>
									<? } ?>
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
			
			form.on('submit(js-esign)', function() {
				var url = $(this).data(url);
				$.post(url, {}, function(r) {
					layer.msg(r.message, {time:1500})
				}, 'json');
			});
		});
		
			
	</script>
</body>
</html>