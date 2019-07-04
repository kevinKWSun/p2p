<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<title><?php echo $name; ?>-伽满优</title>
		<meta name="Keywords" content="<?php echo $name; ?>" />
		<meta name="Description" content="<?php echo $name; ?>" />
		<link href="/favicon.ico" rel="SHORTCUT ICON" />
		<link href="/images/new_help/images/default.css" rel="stylesheet" type="text/css" />
		<link href="/images/new_help/images/index.css" rel="stylesheet" type="text/css" />
		<link href="/images/new_help/images/info.css" rel="stylesheet" type="text/css" />
		<link href="/src/css/layui.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript" src="/images/jquery-1.7.2.min.js"></script>
		<script type="text/javascript" src="/images/globle.js"></script>
		<script type="text/javascript" src="/images/index.js"></script>
		<script src="/images/new/js/swiper.min.js"></script>
		<style>
			.foot_nav h3 img.icon1{margin-top:0px;}
		</style>
</head>
<body>

<?php echo $top; ?> 

<div class="center_v1">
	<div class="bzzx_v1">
		<div class="bzzx_l_v1">
			<img id="ctl00_MainPlaceHolder_ctl00_MainLeft1_Image1" src="/images/new_help/img/gyjmy.png" style="border-width:0px;" />
			<ul>
				<?php foreach($menu as $k=>$v) { ?>
					<li class="<?php if($v['url'] == $url) { echo 'on'; } ?>">
						<a class="<?php if($v['url'] == $url) { echo 'on'; } ?>" href="<?php echo $v['url']; ?>"> 
							<?php echo $v['name']; ?>
						</a>
					</li>
				<?php } ?>
			</ul>
			<img id="ctl00_MainPlaceHolder_ctl00_MainLeft1_Image1" src="/images/new_help/img/appxiazai.png" style="border-width:0px;" />
			<ul>
				<li class="">
					<a class="" href="/message/dowload.html">APP下载</a>
				</li>
			</ul>

		</div>
		<div class="bzzx_r_v1">
			<!--<h2>
				<?php echo $name; ?> <span>&nbsp;</span>
			</h2>
			<p></p>-->
			<div class="xxpl_v1">
				<p>	<br /></p>
				<div class="ptgg">
					<div class="ptgg_di">
						<ul>
							<?php if(!empty($list)) { ?>
								<?php foreach($list as $k=>$v) { ?>
									<li><span><?php echo date('Y-m-d', $v['addtime']); ?></span> <a href="/message/detail/<?php echo $cate; ?>/<?php echo $v['id']; ?>.html"><?php echo $v['title']; ?></a></li>
								<?php } ?>
								
							<?php } ?>
							
						</ul>
						<div class="page">
							<div class="layui-box layui-laypage layui-laypage-default">
								<?php echo $page; ?>
								<a href="javascript:;" class="layui-laypage-next" data-page="2">共 <?php echo $totals; ?> 条</a> 
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="clear"></div>
	</div>
</div>


<?php echo $foot; ?>

</body>
</html>