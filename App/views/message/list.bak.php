<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<title>伽满优-资金第三方存管，安全透明高效的车贷理财平台</title>
		<meta name="Keywords" content="伽满优，车贷理财，车辆抵押,P2P投资理财,投资理财公司,短期理财,P2P投资理财平台" />
		<meta name="Description" content="伽满优，通过公开透明的规范操作，平台为投资理财人士提供收益合理、安全可靠、高效灵活的车贷理财产品。" />
		<link href="/favicon.ico" rel="SHORTCUT ICON" />
		<link href="/images/default.css" rel="stylesheet" type="text/css" /><link href="/images/index.css" rel="stylesheet" type="text/css" />
		<link href="/src/css/layui.css" rel="stylesheet" />
</head>
<body>

<?php echo $top; ?> 
<div class="cent">
	<div class="dqwz">
		<i class="icon"></i><a href="/">首页</a>><a href='/message.html'>关于我们</a>
		> <a href='<?php echo $url; ?>'><?php echo $name; ?></a>
	</div>
	<div class="bzzx">



		<div class="bzzx_l">
			<img id="ctl00_MainPlaceHolder_ctl00_MainLeft1_Image1"
				src="/images/xxpl_tu.gif"
				style="border-width:0px;" />
			<ul>
				<?php foreach($menu as $k=>$v) { ?>
					<li class="<?php if($v['url'] == $url) { echo 'on'; } ?>">
						<a class="<?php if($v['url'] == $url) { echo 'on'; } ?>" href="<?php echo $v['url']; ?>"> 
							<?php echo $v['name']; ?>
						</a>
					</li>
				<?php } ?>
			</ul>
		</div>
		<div class="bzzx_r">
			<h2><?php echo $name; ?> <span>&nbsp;</span></h2>
			<div class="xxpl">
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