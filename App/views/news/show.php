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
    <title>新闻详情</title>
	<link href="/src/css/layui.css" rel="stylesheet" />
</head>
<body>
    <div class="main-wrap" style='padding:10px;'>
        <form action="/news/edit" class="layui-form layui-form-pane" id="formrec" method="post" role="form">
			<div class="layui-form-item">
                <center><h2><?php echo $title?><h2></center>
            </div>
			<div class="layui-form-item">
				<center><?php echo $content?></center>
            </div>
        </form>
    </div>
</body>
</html>