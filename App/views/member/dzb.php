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
    <title>账户对款</title>
    <link href="/src/css/layui.css" rel="stylesheet" />
    <script src="/src/layui.js"></script>
</head>
<body>
    <div class="main-wrap" style='padding:10px;'>
        <blockquote class="layui-elem-quote fhui-admin-main_hd">
            <h2>账户对款</h2>
        </blockquote>
        <div class="y-role">
            <!--工具栏-->
            <div id="floatHead" class="toolbar-wrap">
                <div class="toolbar">
                    <div class="box-wrap">
                        <div class="l-list clearfix">
                            <form id="tt" class="layui-form layui-form-pane">
                                <div class="layui-form-item">
                                    <!--div class="layui-inline">
                                        <div class="layui-input-block" style="margin-left: 0px">
                                            <input name="skey" value="" autocomplete="off" placeholder="ID/真实姓名/手机号" class="layui-input" type="text" />
                                        </div>
                                    </div>
                                    <div class="layui-inline">
                                        <a class="layui-btn layui-btn-small" lay-submit="" lay-filter="search" data-url="/member.html">
                                            <i class="fa fa-search"></i>查询
                                        </a>
										<a class="layui-btn layui-btn-small do-action" data-type="doShow" data-url="/member/upcode.html">
                                            <i class="layui-icon">&#xe642;</i>修改推荐人
                                        </a>
										<a class="layui-btn layui-btn-small do-action" data-type="doEdit" data-url="/member/dzb.html">
                                            <i class="layui-icon">&#xe642;</i>账户对款
                                        </a>
										<a class="layui-btn layui-btn-small do-action" data-type="doEdit" data-url="/member/water.html">
                                            <i class="layui-icon">&#xe642;</i>流水错误表
                                        </a>
                                        <a class="layui-btn layui-btn-small do-action" data-type="doRefresh" data-url="/member.html">
                                            <i class="layui-icon">&#xe669;</i>重新载入
                                        </a>
                                    </div-->
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
                                <th>姓名</th>
                                <th>手机</th>
								<th>客户号码</th>
								<th>账户号码</th>
								<th>账户余额</th>
								<th>电子余额</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(! $member){ ?>
                            <tr>
                                <td class="nodata" colspan="7" align='center'>暂无数据！</td>
                            </tr>
                            <?php }else{foreach ($member as $k=>$v): ?>
                            <tr>
								<td><?php echo $k+1; ?></td>
                                <td><?php echo get_member_info($v['uid'])['real_name']; ?></td>
                                <td><?php echo get_member_info($v['uid'])['phone']; ?></td>
								<td><?php echo get_member_info($v['uid'])['custNo']; ?></td>
								<td><?php echo get_member_info($v['uid'])['acctNo']; ?></td>
								<td><?php echo $v['account_money']; ?></td>
                                <td><?php echo $v['actualAmt']; ?></td>
                            </tr>
                            <?php endforeach;} ?>
                        </tbody>
                    </table>
                </form>
            </div>
            <!--/文字列表-->
            <div class="layui-box layui-laypage layui-laypage-default">
				
			</div>
        </div>
    </div>
	<script src="/src/global.js"></script>
</body>
</html>
