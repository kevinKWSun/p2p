<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>推荐出借人-伽满优</title>
    <meta http-equiv="Content-Language" content="zh-cn" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <meta name="viewport" content="width=device-width, maximum-scale=1.0, initial-scale=1.0,initial-scale=1.0,user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="Keywords" content="推荐出借人-伽满优,车贷理财,车辆抵押,P2P投资理财,投资理财公司,短期理财,P2P投资理财平台" />
	<meta name="Description" content="推荐出借人-伽满优,通过公开透明的规范操作,平台为投资理财人士提供收益合理、安全可靠、高效灵活的车贷理财产品。" />
    <link href="/images/default.css" rel="stylesheet" type="text/css" />
	<link href="/images/index.css" rel="stylesheet" type="text/css" />
	<link href="/images/CenterIndex.css" rel="stylesheet" type="text/css" />
    <link href="/src/css/layui.css" rel="stylesheet" />
    <script src="/images/jquery-1.7.2.min.js"></script>
	<script src="/images/clipboard.min.js"></script>
	<script src="/images/jquery.qrcode.min.js"></script>
</head>
<body>
<?php include("top.php") ?>
 <div class="cent">
	<div class="dqwz">
		<i class="icon"></i><a href="/">首页</a> > <a href="/account.html"> 我的账户 </a> > 推荐出借人
	</div>
	<div class="zhzx">
		<div class="zhzx_l">
			<?php include("left.php") ?>
		</div>
		<div class="zhzx_r">
			<h2>推荐出借人</h2>
			<div class="jyjl">
				<div class="yqlj">
					<h3>我的邀请链接：<font color='red'></font></h3>
					<div class="yqlj_nr">
						<input id="txt_links" readonly="readonly" value="https://www.jiamanu.com/suny/reg.html?invite=<?php printf("%06s", QUID);?>" type="text">
						<a href="javascript:" class="colink"  data-clipboard-action="copy" data-clipboard-target="#txt_links">点击复制</a>
					</div>
				</div>
				<div class="yqlj">
					<h3>我的邀请码：</h3>
					<div class="yqm_di">
						<div class="yqm_l">
							<div class="yqm_tu">
								<p class="img">
									
								</p>
								<p class="text"><?php printf("%06s", QUID);?></p>
							</div>
						</div>
						<div class="yqm_r">
							<div class="clear"></div>
										<div style="width: 400px;">
							 <h3>温馨提示：</h3>
								  <p>1、通过邀请码或者邀请链接的推荐用户为您的邀请客户。<br></p>
						   </div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<style>
p.img canvas{width:130px;he}
</style>
<?php include("foot.php") ?>
<script>
	$(function(){
		var clipboard = new Clipboard('a.colink');
		clipboard.on('success', function(e) {
			$('H3 font').text("您好，链接复制成功!");
			setTimeout(function(){
				$('H3 font').text('');
			},2000);
		});
		clipboard.on('error', function(e) {
			$('H3 font').text("链接复制失败，请手动复制!");
			setTimeout(function(){
				$('H3 font').text('');
			},2000);
		});
		$('p.img').qrcode($('#txt_links').val());
	});
</script>
</body>
</html>