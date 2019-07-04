<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>新闻公告</title>
    <meta http-equiv="Content-Language" content="zh-cn" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <meta name="viewport" content="width=device-width, maximum-scale=1.0, initial-scale=1.0,initial-scale=1.0,user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <link href="Content/css/news.css" rel="stylesheet" />
    <link href="Content/css/iglobal.css" rel="stylesheet" />
    <script type='text/javascript' src='/Content/js/jquery-1.7.2.min.js'></script>
    <script type='text/javascript' src='/Content/js/global.js'></script>
</head>
<body>
<?php include("head.php") ?>
<div class='contant'>
    <div class="news_title">
        <ul class="news_list">
			<?php foreach($news as $v):?>
            <li>
                <div class="news_con">
                    <span>
						<a href="/newlist/news/<?php echo $v['id']?>.html"><b><?php echo $v['title']?></b></a>
					</span>
                </div>
                <div class="news_right"><?php echo date('m-d',$v['addtime']); ?></div>
            </li>
			<?php endforeach;?>
            <!--li>
                <div class="news_left">
                                    <img src="/Public/Img/deflogo.png">         </div>
                <div class="news_con">
                    <span><a href="/new_detail/1894.html"><b>楼市调控出重拳土地市场仍火热 网友吁建长效机制</b></a></span>
                    <p>楼市调控出重拳土地市场仍火热 网友吁建长效机制</p>
                </div>
                <div class="news_right">2017-04-05</div>
            </li-->
        </ul>
    </div>
    <div class="clr"></div>
    <div class='page'>
		<?php echo $page; ?>
		<span>共 <?php echo $totals; ?> 条</span>
	</div>
</div>
<?php include("foot.php") ?>
</body>
</html>