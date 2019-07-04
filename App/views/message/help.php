<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<title><?php echo $name; ?>-伽满优</title>
		<meta name="Keywords" content="<?php echo $name; ?>" />
		<meta name="Description" content="<?php echo $name; ?>" />
		<link href="/images/new_help/images/default.css" rel="stylesheet" type="text/css" />
		<link href="/images/new_help/images/index.css" rel="stylesheet" type="text/css" />
		<link href="/images/new_help/images/info.css" rel="stylesheet" type="text/css" />
		<link href="/src/css/layui.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript" src="/images/jquery-1.7.2.min.js"></script>
		<script type="text/javascript" src="/images/globle.js"></script>
		<script type="text/javascript" src="/images/index.js"></script>
		<script src="/images/new/js/swiper.min.js"></script>
		<style>
			.foot_nav h3 img.icon1{margin-top:0}
		</style>
</head>
<body>
<?php echo $top; ?> 
<div class="center_v1">
	<div class="bzzx_v1" style="margin-bottom:0px;">
		<div class="bzzx_l_v1">
			<img id="ctl00_MainPlaceHolder_ctl00_MainLeft1_Image1" 
			src="/images/new_help/img/<?php echo $title_img; ?>"
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
		<div class="bzzx_r_v1" id="help_list" style="margin-top:20px;">
			<?php echo $con['content']; ?>
			
		</div>
	</div>
	<div class="clear"></div>
</div>



<?php echo $foot; ?>
<script >
   //帮助中心折叠效果
	$(document).ready(function(){
		$("#help_list .title").click(function(){
			if(!$(this).hasClass("open")){
				$("#help_list .title").removeClass("open");
				$("#help_list .title").next().hide();
				$(this).next().show();
				$(this).addClass("open");
				//$('html, body').animate({scrollTop:$(this).offset().top}, '100');
			}else{
				$(this).next().hide();
				$(this).removeClass("open");
			}
		});
	});
</script>
</body>
</html>