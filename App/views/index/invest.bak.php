<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>投资列表</title>
    <meta http-equiv="Content-Language" content="zh-cn" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <meta name="viewport" content="width=device-width, maximum-scale=1.0, initial-scale=1.0,initial-scale=1.0,user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <link href="/Content/css/invest.css" rel="stylesheet" />
    <link href="/Content/css/iglobal.css" rel="stylesheet" />
    <script type='text/javascript' src='/Content/js/jquery-1.7.2.min.js'></script>
    <script type='text/javascript' src='/Content/js/global.js'></script>
</head>
<body>
<?php include("head.php") ?>
    <div class='contant'>
        <?php foreach($borrow as $v): ?>
        <div class="rate clearfix">
            <div class="rate_l">
                <img src="/Content/images/m-dollar.jpg" height="130" width="70" alt=""  />
            </div>
            <div class="rate_r">
                <p><a href="/invest/show/<?php echo $v['id']?>.html"><?php echo $v['borrow_name']?></a></p>
                <ul>
                    <li>
                        <h2><?php echo $v['borrow_interest_rate']?> %</h2>
                        <span>年化收益</span>
                    </li>
                    <li>
                        <h2 class="day"><?php echo $v['borrow_duration']?> 天</h2>
                        <span>投资期限</span>
                    </li>
                    <li>
                        <h3><?php echo $v['borrow_money'] / 10000?> 万</h3>
                        <span>项目金额</span>
                    </li>
                    <li>
                        <h3><?php echo $v['borrow_min']?> 元</h3>
                        <span>起投金额</span>
                    </li>
                    <li>
                        <h3>一次性到期还款</h3>
                        <span>还款方式</span>
                    </li>
                </ul>
            </div>
            <div class="rate_last">
                <div class="circle">
                    <div class="b_jingdu b_jd<?php echo intval($v['has_borrow']/$v['borrow_money']*100);?>"><?php echo intval($v['has_borrow']/$v['borrow_money']);?>%</div>
                    <p>进度</p>
                </div>
                <div class="bid">
                    <?php if($v['borrow_money'] == $v['has_borrow'] || $v['borrow_status'] > 2){?>
                    <a href="javascript:;" style="background:#AAA">售罄</a>
                    <?php }else{?>
                    <a href="/invest/show/<?php echo $v['id']?>.html">立即投资</a>
                    <?php }?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        <p class="pages salelistp">
            <div class="page"><?php echo $page ?></div>
        </p>
    </div>

<?php include("foot.php") ?>
</body>
</html>