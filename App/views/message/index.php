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
			.foot_nav h3 img.icon1{margin-top:0}
		</style>

	</head>
	<body>

		<?php echo $top; ?> 
		<div class="center_v1">
			<div class="bzzx_v1">
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
					<?php if($title_img == 'gyjmy.png') { ?>
						<img id="ctl00_MainPlaceHolder_ctl00_MainLeft1_Image1" src="/images/new_help/img/appxiazai.png" style="border-width:0px;" />
						<ul>
							<li class="">
								<a class="" href="/message/dowload.html">APP下载</a>
							</li>
						</ul>
					<?php } ?>
					

				</div>
				<div class="bzzx_r_v1">
					<!--<h2>
						<?php echo $name; ?> <span>&nbsp;</span>
					</h2>
					<p></p>-->
					<div class="xxpl_v1">
						<p>	<br /></p>
						<?php if(isset($html)) { echo $html; } ?>
						<?php if(isset($images)) { ?>
							<?php foreach($images as $k=>$v) { ?>
								<p>
									<?php 
										if(substr($v['src'], 0, 1) != '/') { 
											echo $v['src'];
										} else {
									?>
										<img src="<?php echo $v['src']; ?>" style="float:none;" title="<?php echo $v['title']; ?>" />
									<?php } ?>
									
								</p>
							<?php } ?>
						<?php } ?>
						
					</div>
				</div>
				<div class="clear"></div>
			
			</div>

		</div>
		<?php echo $foot; ?>

	</body>
</html>
