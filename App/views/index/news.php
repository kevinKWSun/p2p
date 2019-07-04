<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>新闻公告-<?php echo $title?></title>
    <meta http-equiv="Content-Language" content="zh-cn" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <meta name="viewport" content="width=device-width, maximum-scale=1.0, initial-scale=1.0,initial-scale=1.0,user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <link href="/Content/css/news.css" rel="stylesheet" />
    <link href="/Content/css/iglobal.css" rel="stylesheet" />
    <script type='text/javascript' src='/Content/js/jquery-1.7.2.min.js'></script>
    <script type='text/javascript' src='/Content/js/global.js'></script>
</head>
<body>
<?php include("head.php") ?>
<div class='contant'>
    <div class="news_title" style='margin:10px 0;'>
        <div style='border:1px solid #CCC;padding:15px; font-size:14px;'>
			<center style='line-height:40px; font-size:16px;'><b><?php echo $title?></b></center>
			<p><?php echo $content?></p>
		</div>
    </div>
    <div class="clr"></div>
</div>
<?php include("foot.php") ?>
</body>
</html>