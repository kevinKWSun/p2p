<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<title><?php echo $news['title']; ?>-伽满优</title>
		<meta name="Keywords" content="<?php echo $news['title']; ?>" />
		<meta name="Description" content="<?php echo $news['title']; ?>" />
		<link href="/favicon.ico" rel="SHORTCUT ICON" />
		<link href="/images/new_help/images/default.css" rel="stylesheet" type="text/css" />
		<link href="/images/new_help/images/index.css" rel="stylesheet" type="text/css" />
		<link href="/images/new_help/images/info.css" rel="stylesheet" type="text/css" />
		<link href="/src/css/layui.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript" src="/images/jquery-1.7.2.min.js"></script>
		<script type="text/javascript" src="/images/globle.js"></script>
		<script type="text/javascript" src="/images/index.js"></script>
		<script src="/images/new/js/swiper.min.js"></script>
		<script src="/images/new/js/jquery.SuperSlide.2.1.js"></script>

	</head>
	<body>

		<div class="clear"></div>
		<div id="oper_relative;">
			<img src="<?php echo $news['img']; ?>" style="width:100%;"/>
		</div>
		<?php if($news['year'] == '2017') { ?>
			<div id="oper_absolute" style="top:840px;">
				<div class="layui-carousel" id="carousel_id">
					<div carousel-item>
						<?php if(!empty($news['content'])) { foreach($news['content'] as $v) { ?>
							<div style="background-color:#fff;"><img src="<?php echo $v; ?>"></div>
						<?php } } ?>
					</div>
				</div>
			</div>
		<?php } else if(empty($news['content'])) { ?>
			<div id="oper_absolute"></div>
		<?php } else { ?>
			<div id="oper_absolute">
				<div class="layui-carousel" id="carousel_id">
					<div carousel-item>
						<?php if(!empty($news['content'])) { foreach($news['content'] as $v) { ?>
							<div style="background-color:#fff;"><img src="<?php echo $v; ?>"></div>
						<?php } } ?>
					</div>
				</div>
			</div>
		<?php } ?>
	</body>
	<script src="/src/layui.js" charset="utf-8"></script>
	<script>
		layui.use('carousel', function() {
			var carousel = layui.carousel;
			//建造实例
			carousel.render({
				elem: '#carousel_id',
				width: '1510' //设置容器宽度
					,
				height: '862',
				arrow: 'always' //始终显示箭头
					,
				indicator: 'none'
			});
		});
	</script>
</html>
