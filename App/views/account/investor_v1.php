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
	<link href="/images/new/user-info.css" rel="stylesheet" type="text/css" />
	<link href="/images/index.css" rel="stylesheet" type="text/css" />
	<link href="/src/css/layui.css" rel="stylesheet" /> 
	<script src="/images/jquery-1.7.2.min.js"></script>
	<script src="/images/clipboard.min.js"></script>
	<script src="/images/jquery.qrcode.min.js"></script>


</head>
<body>
<?php include("top.php") ?>
 <div class="cent_v2">

	<div class="zhzx_v2">
		<div class="zhzx_l_v2">
			<?php include("left_v1.php") ?>
		</div>
		<div class="zhzx_r_v2">
			<h2>推荐出借人</h2>
			<div class="jyjl_v2">
				<div class="yqlj_v2">
					<h3>我的邀请链接：<font color="red"></font>
					</h3>
					<div class="yqlj_nr_v2"  style="height: 28px;">
						<div >
							<div class="yaoqtext_v2">电脑用户</div>
							<input id="txt_links" readonly="readonly" value="https://www.jiamanu.com/suny/reg.html?invite=<?php printf("%06s", QUID);?>" type="text" style="margin-bottom:15px;">
							<a href="javascript:" class="colink_v2" data-clipboard-action="copy" data-clipboard-target="#txt_links">点击复制</a>
						</div>
						<div style="clear:both;"></div>
						<div class="">
							<div class="yaoqtext_v2">手机用户</div>
							<input id="txt_links1" readonly="readonly" value="https://m.jiamanu.com/reg.html?invite=<?php printf("%06s", QUID);?>" type="text">
							<a href="javascript:" class="colink1_v2" data-clipboard-action="copy" data-clipboard-target="#txt_links1">点击复制</a>
						</div>
					</div>
				</div>		
						
				<div class="yqlj_v2">
					<h3>我的邀请码：</h3>
					
					<div class="clear"></div>	
					<div class="yqm_l_v2">
						<div class="yqm_tu1_v2">
							<p class="img_v2"></p>
							<p class="text_v2"><?php printf("%06s", QUID);?></p>
						</div>
					</div>
					<div class="yqm_l_v2">
						<div class="yqm_tu_v2">
							<p class="img1_v2"></p>
							<p class="text1_v2"><?php printf("%06s", QUID);?></p>
						</div>
					</div>
					
					<div class="yqm_r_v2">
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

		<script>
			$(function() {
				var clipboard = new Clipboard('a.colink_v2');
				var clipboard = new Clipboard('a.colink1_v2');
				clipboard.on('success', function(e) {
					$('H3 font').text("您好，链接复制成功!");
					setTimeout(function() {
						$('H3 font').text('');
					}, 2000);
				});
				clipboard.on('error', function(e) {
					$('H3 font').text("链接复制失败，请手动复制!");
					setTimeout(function() {
						$('H3 font').text('');
					}, 2000);
				});
				$('p.img_v2').qrcode($('#txt_links').val());
				$('p.img1_v2').qrcode($('#txt_links1').val());
				
				
			});
		</script>
<?php include("foot.php") ?>
</body>
</html>