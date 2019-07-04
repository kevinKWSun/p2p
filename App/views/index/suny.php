<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>首页</title>
    <meta http-equiv="Content-Language" content="zh-cn" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <meta name="viewport" content="width=device-width, maximum-scale=1.0, initial-scale=1.0,initial-scale=1.0,user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <link href="Content/css/iglobal.css" rel="stylesheet" />
    <link href="Content/css/index.css" rel="stylesheet" />
    <script type='text/javascript' src='/Content/js/jquery-1.7.2.min.js'></script>
    <script type='text/javascript' src='/Content/js/global.js'></script>
</head>
<body>

<?php include("head.php") ?>

<div class="measure">
    <div class="measure_m">
        <ul>
            <li>
                <a href="javascript:void(0);"><img src="/Content/images/sy.png"/></a>
                <h3>稳健收益</h3>
                <p>独有的3层风控体系风控体系风控体系风控体系</p>
                
            </li>
            <li>
                <a href="javascript:void(0);"><img src="/Content/images/aq.png"/></a>
                <h3>安全保障</h3>
                <p>独有的3层风控体系独有的3层风控体系</p>
            </li>
            <li>
                <a href="javascript:void(0);"><img src="/Content/images/lh.png"/></a>
                <h3>投取方便</h3>
                <p>投资随心提取也随意取也随意风控体系风控体系</p>
            </li>
        </ul>
    </div>
</div>

<div class="Novice_standard">
    <div class="rate clearfix">
        <div class="rate_l">
            <a href='/invest.html'><img src="/Content/images/cd.png"/></a>
        </div>
        <div class="rate_r">
            <?php foreach($borrows as $v): ?>
            <dl>
                <dt><a href="/invest/show/<?php echo $v['id']?>.html"><?php echo $v['borrow_name']?></a></dt>
                <dd>
                    <img src='/Content/images/li.png' />
                    <h2><?php echo $v['borrow_interest_rate']?> %</h2>
                    <span>年化收益</span>
                </dd>
                <dd>
                    <img src='/Content/images/ri.png' />
                    <h2 class="day"><?php echo $v['borrow_duration']?> 天</h2>
                    <span>投资期限</span>
                </dd>
                <dd>
                    <img src='/Content/images/jin.png' />
                    <h2><?php echo $v['borrow_money'] - $v['has_borrow']?> 元</h2>
                    <span>剩余总额</span>
                </dd>
                <dd>
                    <h2><?php echo $v['borrow_min']?> 元起投</h2>
                    <span>一次性到期还款</span>
                </dd>
                <dd>
                    <?php if($v['borrow_money'] == $v['has_borrow'] || $v['borrow_status'] > 2){?>
                    <a href="javascript:;" style="background:#AAA">售罄</a>
                    <?php }else{?>
                    <a href="/invest/show/<?php echo $v['id']?>.html">立即投资</a>
                    <?php }?>
                </dd>
            </dl>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<div class="Novice_standard">
    <div class="rate clearfix">
        <div class="rate_l">
            <a href='/invest.html'><img src="/Content/images/cd.png"/></a>
        </div>
        <div class="rate_r">
            <?php foreach($borrows as $v): ?>
            <dl>
                <dt><a href="/invest/show/<?php echo $v['id']?>.html"><?php echo $v['borrow_name']?></a></dt>
                <dd>
                    <img src='/Content/images/li.png' />
                    <h2><?php echo $v['borrow_interest_rate']?> %</h2>
                    <span>年化收益</span>
                </dd>
                <dd>
                    <img src='/Content/images/ri.png' />
                    <h2 class="day"><?php echo $v['borrow_duration']?> 天</h2>
                    <span>投资期限</span>
                </dd>
                <dd>
                    <img src='/Content/images/jin.png' />
                    <h2><?php echo $v['borrow_money'] - $v['has_borrow']?> 元</h2>
                    <span>剩余总额</span>
                </dd>
                <dd>
                    <h2><?php echo $v['borrow_min']?> 元起投</h2>
                    <span>一次性到期还款</span>
                </dd>
                <dd>
                    <?php if($v['borrow_money'] == $v['has_borrow'] || $v['borrow_status'] > 2){?>
                    <a href="javascript:;" style="background:#AAA">售罄</a>
                    <?php }else{?>
                    <a href="/invest/show/<?php echo $v['id']?>.html">立即投资</a>
                    <?php }?>
                </dd>
            </dl>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<div class="advert">
	<div class="news clearfix">
		<div class="news_l">
			<h2>新闻公告</h2><br/>
			<p>
			<img src='images/xb.jpg' width='98%' />                					
			</p>
		</div>
		<div class="news_r clearfix">
			<h2>行业新闻</h2><br/>
			<ul>
				<?php foreach($news as $v):?>
				<li>
					<a href='/newlist/news/<?php echo $v['id']?>.html' title="<?php echo $v['title']?>">
						<?php echo $v['title']?>
					</a>
				</li>
				<?php endforeach;?>
			</ul>
			<ul class="date">
				<?php foreach($news as $v):?>
				<li><?php echo date('m-d',$v['addtime']); ?></li>
				<?php endforeach;?>
			</ul>
			<div class="l_more"><a href="/newlist.html" >MORE...</a></div>
		</div>
	</div>
</div>
<?php include("foot.php") ?>
</body>
</html>