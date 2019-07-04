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
    <title>规则管理</title>
	<link href="/src/css/layui.css" rel="stylesheet" />
    <script src="/src/layui.js"></script>
</head>
<body>
    <div class="main-wrap" style='padding:10px;'>
        <blockquote class="layui-elem-quote fhui-admin-main_hd">
            <h3>规则管理</h3>
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
                                        <a class="layui-btn layui-btn-sm" lay-submit="" lay-filter="solor" data-url="/rule/solor">
                                            <i class="layui-icon">&#xe615;</i>查询
                                        </a>
                                        <a class="layui-btn layui-btn-sm do-action" data-type="doAdd" data-url="/rule/add.html">
                                            <i class="layui-icon">&#xe61f;</i>新增
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
            <!--/工具栏-->
            <!--文字列表-->
            <div class="fhui-admin-table-container">
                <form class="layui-form" method="post">
                    <table class="layui-table">
                        <thead>
                            <tr>
                                <th>名称</th>
                                <th>URL</th>
                                <th style='text-align:center;'>显示状态</th>
                                <th style='text-align:center;'>删除状态</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                        	<?php if(! $rule){ ?>
                            <tr>
                                <td class="nodata" colspan="5" align='center'>暂无数据！</td>
                            </tr>
                            <?php }else{foreach ($rule as $v): ?>
                            <tr>
                                <td>
                                    <?php echo $v['title']; ?>
                                </td>
                                <td><?php echo $v['name']; ?></td>
                                <td align="center">
                                	<?php if($v['type'] == 1){ ?>
                                	显示
                                	<?php }else{ ?>
                                	隐藏
                                	<?php } ?>
                                </td>
                                <td align="center">
                                    <?php if($v['status'] == 1){ ?>
                                    正常
                                    <?php }else{ ?>
                                    已删除
                                    <?php } ?>
                                </td>
                                <td>
                                    <a class="layui-btn layui-btn-sm do-action" data-type="doEdit" data-url="/rule/edit/<?php echo $v['id']; ?>.html"><i class="layui-icon">&#xe642;</i>编辑</a>
                                    <a class="layui-btn layui-btn-sm do-action" data-type="doEdit" data-url="/rule/addall/<?php echo $v['id']; ?>.html"><i class="layui-icon">&#xe61f;</i>增加</a>
                                </td>
                            </tr>
                            <?php if ($v['child']){ foreach ($v['child'] as $vs):  ?>
                            <tr>
                                <td>
                                    <?php echo $vs['level'],$vs['title']; ?>
                                </td>
                                <td><?php echo $vs['name']; ?></td>
                                <td align="center">
                                    <?php if($vs['type'] == 1){ ?>
                                    显示
                                    <?php }else{ ?>
                                    隐藏
                                    <?php } ?>
                                </td>
                                <td align="center">
                                    <?php if($vs['status'] == 1){ ?>
                                    正常
                                    <?php }else{ ?>
                                    已删除
                                    <?php } ?>
                                </td>
                                <td>
                                    <a class="layui-btn layui-btn-sm do-action" data-type="doEdit" data-url="/rule/edit/<?php echo $vs['id']; ?>.html"><i class="layui-icon">&#xe642;</i>编辑</a>
                                    <a class="layui-btn layui-btn-sm do-action" data-type="doEdit" data-url="/rule/addall/<?php echo $vs['id']; ?>.html"><i class="layui-icon">&#xe61f;</i>增加</a>
                                </td>
                            </tr>
                            <?php endforeach;}endforeach;} ?>
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