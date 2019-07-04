<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>公司简介</title>
    <meta http-equiv="Content-Language" content="zh-cn" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <meta name="viewport" content="width=device-width, maximum-scale=1.0, initial-scale=1.0,initial-scale=1.0,user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
	<link href="/src/css/layui.css" rel="stylesheet" />
	<script src="/src/layui.js"></script>
</head>
<body>
<div class="content">
	<div class="content_mid clearfix">
		<?php if(!empty($guarantor['pic'])) {?>
			<?php foreach($guarantor['pic'] as $v) {?>
				<div style=" margin-bottom:10px; text-align:center;">
					<img style="width:600px; border:solid 2px #999999;"  src="<?php echo $v; ?>" />
				</div>
			<?php } ?>
		<?php } ?>
	</div>
</div>
</body>
</html>