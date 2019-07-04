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
    <title>权限管理</title>
	<link href="/src/css/layui.css" rel="stylesheet" />
    <script src="/src/layui.js"></script>
</head>
<body>
    <div class="main-wrap" style='padding:10px;'>
        <blockquote class="layui-elem-quote fhui-admin-main_hd">
            <h3>权限管理</h3>
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
                                            <input name="skey" lay-verify="required" value="" autocomplete="off" placeholder="请输入关键字" class="layui-input" type="text" />
                                        </div>
                                    </div>
                                    <div class="layui-inline">
                                        <a class="layui-btn layui-btn-sm" lay-submit="" lay-filter="solor" data-url='/group/solor'>
                                            <i class="layui-icon">&#xe615;</i>查询
                                        </a>
                                        <a class="layui-btn layui-btn-sm do-action" data-type="doAdd" data-url="/group/add.html">
                                            <i class="layui-icon">&#xe61f;</i>新增
                                        </a>
                                        <a class="layui-btn layui-btn-sm do-action" data-type="doAction" data-url="/group/lock.html">
                                            <i class="layui-icon">&#xe6b2;</i>锁定
                                        </a>
                                        <a class="layui-btn layui-btn-sm do-action" data-type="doAction" data-url="/group/unlock.html">
                                            <i class="layui-icon">&#xe6b2;</i>解锁
                                        </a>
                                        <a class="layui-btn layui-btn-sm do-action" data-type="doRefresh" data-url="">
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
                <form action="" class="layui-form" method="post">
                    <table class="layui-table">
                        <thead>
                            <tr>
                                <th>
									<div class="layui-input-inline">
										<input type="checkbox" lay-skin="primary" lay-filter="selected-all" />
									</div>
								</th>
                                <th>组名</th>
                                <th style='text-align:center;'>状态</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                        	<?php if(! $group){ ?>
                            <tr>
                                <td class="nodata" colspan="4" align='center'>暂无数据！</td>
                            </tr>
                            <?php }else{foreach ($group as $v): ?>
                            <tr>
                                <td>
									<div class="layui-input-inline">
										<input ids="<?php echo $v['id']; ?>" lay-skin="primary" type="checkbox" />
									</div>
                                </td>
                                <td><?php echo $v['title']; ?></td>
                                <td align="center">
                                	<?php if($v['status'] == 1){ ?>
                                	正常
                                	<?php }else{ ?>
                                	已锁定
                                	<?php } ?>
                                </td>
                                <td>
                                    <a class="layui-btn layui-btn-sm do-action" data-type="doEdit" data-url="/group/edit/<?php echo $v['id']; ?>.html"><i class="layui-icon">&#xe642;</i>编辑</a>
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