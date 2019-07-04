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
    <title>运营报告</title>
    <link href="/src/css/layui.css" rel="stylesheet" />
    <script src="/src/layui.js"></script>
</head>
<body>
    <div class="main-wrap" style='padding:10px;'>
        <blockquote class="layui-elem-quote fhui-admin-main_hd">
            <h2>运营报告</h2>
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
                                        <a class="layui-btn layui-btn-small" lay-submit="" lay-filter="search" data-url="/motion/lists.html">
                                            <i class="fa fa-search"></i>查询
                                        </a>
										<?php if(is_rule('/motion/add')) { ?>
											<a class="layui-btn layui-btn-small do-action" data-type="doAdd" data-url="/motion/add.html">
                                            <i class="layui-icon">&#xe61f;</i>新增
                                        </a>
										<?php } ?>
                                        <a class="layui-btn layui-btn-small do-action" data-type="doRefresh" data-url="/motion/lists.html">
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
								<th>运营报告日期</th>
                                <th>操作时间</th>
								<th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(! $motion){ ?>
                            <tr>
                                <td class="nodata" colspan="11" align='center'>暂无数据！</td>
                            </tr>
                            <?php }else{foreach ($motion as $v): ?>
                            <tr>
								<td><?php echo $v['id']; ?></td>
                                <td><?php echo date('Y-m', $v['date']); ?></td>
                                <td><?php echo date('Y-m-d H:i', $v['addtime']); ?></td>
								<td>
									<?php if(is_rule('/motion/modify')) { ?>
										<a class="layui-btn layui-btn-sm do-action" data-type="doEdit" data-url="/motion/modify/<?php echo $v['id'], '/', $p; ?>.html"><i class="icon-edit fa fa-search"></i>编 辑</a>
									<?php } ?>
									<?php if(is_rule('/motion/show')) { ?>
										<a class="layui-btn layui-btn-sm" href='/motion/show/<?php echo $v['id']; ?>.html'>
											<i class="icon-edit  fa fa-search"></i>
											查　看
										</a>
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
		//查出选择的记录
			
	</script>
</body>
</html>
