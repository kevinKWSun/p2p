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
    <title>商品管理</title>
	<link href="/src/css/layui.css" rel="stylesheet" />
    <script src="/src/layui.js"></script>
</head>
<body>
    <div class="main-wrap" style='padding:10px;'>
        <blockquote class="layui-elem-quote fhui-admin-main_hd">
            <h3>商品管理</h3>
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
                                        <a class="layui-btn layui-btn-sm do-action" data-type="doAdd" data-url="/cate/gadd.html">
                                            <i class="layui-icon">&#xe61f;</i>新增
                                        </a>
                                        <a class="layui-btn layui-btn-sm do-action" data-type="doRefresh" data-url="/cate/goods.html">
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
                                <th>分类名称</th>
								<th>商品名称</th>
								<th>商品价格</th>
								<th>所需积分</th>
								<th>剩余存量</th>
                                <th>添加日期</th>
								<th>状态</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                        	<?php if(! $cate){ ?>
                            <tr>
                                <td class="nodata" colspan="7" align='center'>暂无数据！</td>
                            </tr>
                            <?php }else{foreach ($cate as $v): ?>
                            <tr>
                                <td><?php echo $v['name']; ?></td>
								<td><?php echo $v['gname']; ?></td>
								<td><?php echo $v['price']; ?></td>
								<td><?php echo $v['score']; ?></td>
								<td><?php echo $v['num']; ?></td>
                                <td><?php echo date('Y-m-d',$v['addtime']); ?></td>
								<td><?php echo $v['gstatus'] ? '正常' : '关闭'; ?></td>
                                <td>
                                    <a class="layui-btn layui-btn-sm do-action" data-type="doEdit" data-url="/cate/gedit/<?php echo $v['id']; ?>.html"><i class="layui-icon">&#xe642;</i>编辑</a>
                                </td>
                            </tr>
                            <?php endforeach;} ?>
                        </tbody>
                    </table>
                </form>
            </div>
            <div class="layui-box layui-laypage layui-laypage-default">
				<?php echo $page; ?>
				<a href="javascript:;" class="layui-laypage-next" data-page="2">共 <?php echo $totals; ?> 条</a>
			</div>
        </div>
    </div>
    <script src="/src/global.js"></script>
</body>
</html>