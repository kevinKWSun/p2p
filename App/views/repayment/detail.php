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
    <title>还款详情</title>
    <link href="/src/css/layui.css" rel="stylesheet" />
    <script src="/src/layui.js"></script>
</head>
<body>
    <div class="main-wrap" style='padding:10px;'>
        <blockquote class="layui-elem-quote fhui-admin-main_hd">
            <h2>还款详情</h2>
        </blockquote>
        <div class="y-role">
            <!--文字列表-->
            <div class="fhui-admin-table-container">
                <form class="form-horizontal layui-form" id="formrec" method="post" role="form">
                    <table class="layui-table layui-tables" lay-skin="line">
                        <thead>
                            <tr>
                                <th>序号</th>
                                <th>标题</th>
                                <th>投资用户ID</th>
                                <th>真实姓名</th>
                                <th>联系方式</th>
                                <th>推荐人</th>
                                <th>还款总额</th>
								<th>应还本金</th>
                                <th>应还利息</th>
                                <th>期数</th>
                                <th>状态</th>
                            </tr>
                        </thead>
                        <tbody>
                        	<?php if(! $detail){ ?>
                            <tr>
                                <td class="nodata" colspan="17" align='center'>暂无数据！</td>
                            </tr>
                            <?php }else{foreach ($detail as $k=>$v): ?>
                            <tr>
                                <td><?php echo $k+1; ?></td>
                                <td><?php echo $v['borrow_name']; ?></td>
                                <td><?php echo $v['investor_uid']; ?></td>
                                <td><?php echo $v['real_name']; ?></td>
                                <td><?php echo $v['phone']; ?></td>
                                <td><?php echo $v['codename']; ?></td>
                                <td><?php echo $v['capital'] + $v['interest']; ?></td>
								<td><?php echo $v['capital']; ?></td>
                                <td><?php echo $v['interest']; ?></td>
                                <td><?php echo $v['sort_order'],'/',$v['total']; ?></td>
                                <td><?php echo ($v['status'] > 4 || $v['repayment_time'] > 0) ? '已收' : '待收'; ?></td>
                            </tr>
                            <?php endforeach;} ?>
                        </tbody>
                    </table>
					<div class="page-footer">
						<div class="btn-list">
							<div class="btnlist">
								<a class="layui-btn layui-btn-small" id="export" data-href="/repayment/export_detail/<?php echo $detail[0]['deadline']; ?>.html"><i class="layui-icon">&#xe669;</i>导出</a>
								<a class="layui-btn layui-btn-small do-action" data-type="doRefresh" data-url=""><i class="layui-icon">&#xe669;</i>刷新</a>
								<a class="layui-btn layui-btn-small do-action" data-type="doGoBack" data-url=""><i class="layui-icon">&#xe65c;</i>返回上一页</a>
								<a class="layui-btn layui-btn-small do-action" data-type="doGoTop" data-url=""><i class="layui-icon">&#xe604;</i>返回顶部</a>
							</div>
						</div>
					</div>
                </form>
            </div>
        </div>
    </div>
    <script src="/src/global.js"></script>
	<script type="text/javascript">
		layui.use(['layer', 'form', 'laydate'], function(){
			var $ = layui.$;
			$('#export').click(function() {
				var url = $(this).data('href');
				location.href = url;
			});
		});
	</script>
</body>
</html>