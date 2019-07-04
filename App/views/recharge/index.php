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
    <title>充值列表</title>
    <link href="/src/css/layui.css" rel="stylesheet" />
    <script src="/src/layui.js"></script>
</head>
<body>
    <div class="main-wrap" style='padding:10px;'>
        <blockquote class="layui-elem-quote fhui-admin-main_hd">
            <h2>充值列表</h2>
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
                                            <input name="skey" value="<?php if(isset($skey)) { echo $skey; } ?>" autocomplete="off" placeholder="用户ID/真实姓名/手机号" class="layui-input" type="text" />
                                        </div>
                                    </div>
                                    <div class="layui-inline">
                                        <a class="layui-btn layui-btn-small" lay-submit="" lay-filter="search" data-url="/recharge.html">
                                            <i class="fa fa-search"></i>查询
                                        </a>
									</div>
									<div class="layui-inline">
										<input type="text" class="layui-input" id="test1" placeholder="开始时间" />
									</div>
									<div class="layui-inline">
										<input type="text" class="layui-input" id="test2" placeholder="结束时间" />
									</div>
									<div class="layui-inline">
										<a class="layui-btn layui-btn-small" lay-submit="" lay-filter='excel' data-url="/excel/recharge.html">
                                            <i class="layui-icon"></i>导出
                                        </a>
                                        <a class="layui-btn layui-btn-small do-action" data-type="doRefresh" data-url="/recharge.html">
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
                <form class="form-horizontal" id="formrec" method="post" role="form">
                    <table class="layui-table layui-tables" lay-skin="line">
                        <thead>
                            <tr>
								<th>用户ID</th>
                                <th>姓名</th>
                                <th>手机</th>
                                <th>充值方式</th>
                                <th>充值金额</th>
                                <th>充值时间</th>
                                <th>充值状态</th>
                                <th>充值结果</th>
                            </tr>
                        </thead>
                        <tbody>
                        	<?php if(! $recharge){ ?>
                            <tr>
                                <td class="nodata" colspan="7" align='center'>暂无数据！</td>
                            </tr>
                            <?php }else{foreach ($recharge as $v): ?>
                            <tr>
								<td><?php echo $v['uid']; ?></td>
                                <td><?php echo $v['real_name']; ?></td>
                                <td><?php echo $v['phone'];?></td>
                                <td><?php if(strpos($v['nid'], 'p-mer') === FALSE) { echo '快捷'; } else { echo '网银'; } ?></td>
                                <td><?php echo $v['money']; ?></td>
                                <td><?php echo date('Y-m-d H:i',$v['add_time']); ?></td>
                                <td><?php echo $v['status']?'成功':'失败'; ?></td>
                                <td><?php echo $v['remark']?$v['remark']:'无'; ?></td>
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
				var str = JSON.stringify(data.field);
				str = str.replace(/\t/g,"");
				str = str.replace(/\"/g,"").replace("{","").replace("}","").replace(",","&").replace(":","=");
				str = str.replace(/\"/g,"").replace(/{/g,"").replace(/}/g,"").replace(/,/g,"&").replace(/:/g,"=");
				location.href = url + "?" + str;
				return false; 
			});
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