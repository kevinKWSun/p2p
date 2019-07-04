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
                                        <a class="layui-btn layui-btn-small" lay-submit="" lay-filter="search" data-url="/deal/times_lists.html">
                                            <i class="fa fa-search"></i>查询
                                        </a>
                                        <a class="layui-btn layui-btn-small do-action" data-type="doRefresh" data-url="/deal/times_lists.html">
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
								<th>用户ID</th>
                                <th>姓名</th>
                                <th>手机</th>
								<th>累计金额</th>
								<th>抽奖次数</th>
								<th>剩余抽奖次数</th>
								<th>剩余双倍抽奖次数</th>
								<th>期限</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(! $times){ ?>
                            <tr>
                                <td class="nodata" colspan="11" align='center'>暂无数据！</td>
                            </tr>
                            <?php }else{foreach ($times as $v): ?>
                            <tr>
								<td><?php echo $v['id']; ?></td>
								<td><?php echo $v['uid']; ?></td>
                                <td><?php echo $v['real_name']; ?></td>
                                <td><?php echo $v['phone']; ?></td>
								<td><?php echo $v['money']; ?></td>
								<td><?php echo $v['times']; ?></td>
								<td><?php echo $v['single']; ?></td>
								<td><?php echo $v['doubles']; ?></td>
								<td>
									<?php 
										if($v['type'] == 4) {
											echo '97天';
										} else if($v['type'] == 3) {
											echo '65天';
										} else if($v['type'] == 2) {
											echo '33天';
										}
									?>
								</td>
								<!--<td>
									<a style="cursor:pointer;color:#009688;" class="do-action" data-type="doShow" data-url="">
										余额查询
									</a>
									
								</td>-->
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
