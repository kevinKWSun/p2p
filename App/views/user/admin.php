<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <title>后台管理系统</title>
        <link rel="stylesheet" href="./src/css/layui.css">
    </head>
    <body class="layui-layout-body">
		<!--[if lt IE 9]>
			<script src="./src/jr/html5.min.js"></script>
			<script src="./src/jr/respond.min.js"></script>
		<![endif]-->
        <div class="layui-layout layui-layout-admin">
            <div class="layui-header header-admin">
                <div class="layui-logo">
					<a href="javascript:;"><img src='./src/images/OPay.png' alt='后台布局' /></a>
					<i class="layui-icon" style='cursor:pointer;' title="侧边伸缩" id="LAY_app_flexible" rel='1'>　　&#xe668;</i>
				</div>
                <ul class="layui-nav layui-layout-left" id='menu' style='padding:0;'>
					<?php foreach ($rule as $k=>$v): ?>
                    <li class="layui-nav-item" <?php if($k==0){echo "style='background-color: #009688;'";} ?>>
                        <a href="javascript:;" data-fid="<?php echo $v['id']; ?>">
                            <?php echo $v['title']; ?>
                        </a>
                    </li>
                    <?php endforeach; ?>
                    <!-- <li class="layui-nav-item">
                        <a href="javascript:;">其它系统</a>
                        <dl class="layui-nav-child">
                            <dd><a href="">邮件管理</a></dd>
                            <dd><a href="">消息管理</a></dd>
                            <dd><a href="">授权管理</a></dd>
                        </dl>
                    </li> -->
                </ul>
                <ul class="layui-nav layui-layout-right">
                    <li class="layui-nav-item">
                        <a href="javascript:;">
                            <img src="./src/images/0.jpg" class="layui-nav-img">
                            <?php echo $name;?>
                        </a>
                        <dl class="layui-nav-child">
                            <dd><a href="javascript:;"><?php echo $rname;?></a></dd>
                            <dd><a href="javascript:;" class='do-admin' data-url="/adminr/modify.html">修改密码</a></dd>
                        </dl>
                    </li>
                    <li class="layui-nav-item"><a href="/adminr/logout.html">退了</a></li>
                </ul>
            </div>
            <div class="layui-side layui-bg-black">
                <div class="layui-side-scroll">
                    <ul class="layui-nav layui-nav-tree" lay-filter="side"></ul>
                </div>
            </div>
            <div class="layui-body" style="bottom: 0;">
				<div class="layui-tab-content" id="admin-tab-container" style="min-height: 150px;padding:0;">
                    <div class="layui-tab-item layui-show">
                        <iframe name="mainframe" frameborder="0" src="/adminr/center.html" style='width:100%;'></iframe>
                    </div>
                </div>
            </div>
            <div class="layui-footer">
                © 2017 - 通用后台系统
            </div>
        </div>
        <script src="./src/layui.js"></script>
		<script src="./src/_admin.js"></script>
    </body>
</html>