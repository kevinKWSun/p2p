<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>个人信息-伽满优</title>
    <meta http-equiv="Content-Language" content="zh-cn" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <meta name="viewport" content="width=device-width, maximum-scale=1.0, initial-scale=1.0,initial-scale=1.0,user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="Keywords" content="个人信息-伽满优,车贷理财,车辆抵押,P2P投资理财,投资理财公司,短期理财,P2P投资理财平台" />
	<meta name="Description" content="个人信息-伽满优,通过公开透明的规范操作,平台为投资理财人士提供收益合理、安全可靠、高效灵活的车贷理财产品。" />
    <link href="/images/default.css" rel="stylesheet" type="text/css" />
	<link href="/images/index.css" rel="stylesheet" type="text/css" />
	<link href="./images/CenterIndex.css" rel="stylesheet" type="text/css" />
    <link href="/src/css/layui.css" rel="stylesheet" />
    <script src="/src/layui.js"></script>
</head>
<body>
<?php include("top.php") ?>
 <div class="cent">
	<div class="dqwz">
		<i class="icon"></i><a href="/">首页</a> > <a href="/account.html"> 我的账户 </a> > 个人信息
	</div>
	<div class="zhzx">
		<div class="zhzx_l">
			<?php include("left.php") ?>
		</div>
		<div class="zhzx_r">
			<h2>个人信息</h2>
			<div class="jbzl">
				<div class="jbzl_nr">
					<h3>
						基本资料</h3>
					<ul>
						<li><span>用户昵称：</span><?php echo get_member_info(QUID)['phone']; ?></li>
						<li><span>真实姓名：</span><?php echo substr_replace($info['real_name'],'*',3,3);?>【<?php echo IS_CHECK==TRUE?'已认证':'未认证'?>】</li>
						<li><span>身份证号：</span><?php echo substr_replace($info['idcard'],'*',3,15);?>【<?php echo IS_CHECK==TRUE?'已认证':'未认证'?>】</li>
						<li><span>手机号码：</span><?php echo substr_replace($info['phone'],'******',3,6);?></li>
					</ul>
				</div>
				<div class="xgtx">
					<img src="/images/mrtx_tu.jpg" />
					
				</div>
			</div>
		</div>
	</div>
</div>

<?php include("foot.php") ?>
</body>
</html>