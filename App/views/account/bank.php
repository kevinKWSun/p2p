<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>银行卡管理</title>
    <script type='text/javascript' src='/Content/js/jquery-1.7.2.min.js'></script>
    <link href="Css/bootstrap.min.css" rel="stylesheet" /> 
	<link href="Css/components.css?ver=142682356" rel="stylesheet" /> 
	<link href="Css/main.css?ver=142682356" rel="stylesheet" /> 
	<link href="Css/new-home.css?ver=142682356" rel="stylesheet" />
	<!--[if lt IE 9]>
	<link href="Css/ie8.css?ver=142682356" rel="stylesheet">
	<![endif]--> 
	<!--[if IE 9]>
	<link href="Css/ie9.css?ver=142682356" rel="stylesheet">
	<![endif]-->
</head>
<body>
<?php include("top.php") ?>
<div class="wrapper ">  
    <div id="my-account" class="container my-account"> 
        <div class="row"> 
			<?php include("left.php") ?>
			<div class="col-xs-9 ng-scope" autoscroll="false" ui-view="" style="">
				<div class="bank-cards content-wrapper ng-scope">
					<header class="section-header">
						<h6 class="section-header-title">银行卡管理</h6>
						<span class="section-header-action ng-binding">当前绑定1张银行卡</span>
					</header>
					<section class="summary-section">
						<section class="cards-content">
							<div class="row">
							<?php if($b){?>
								<div class="col-xs-6 card-col ng-scope">
									<div class="bank-card">
										<header class="clearfix">
											<div class="pull-left">
												<a class="icon-bank">
													<!--span class="form-control bank-logo bank-bc"></span-->
													<?php echo $this->config->item('bank')[$b['bid']]?>
												</a>
												<!--span class="card-type ng-binding">储蓄卡</span-->
											</div>
											<h6 class="pull-right ng-binding">储蓄卡</h6>
										</header>
										<div class="text-center">
											<h5 class="card-number ng-binding">
												<?php echo substr_replace($b['card'],' **** **** ',4,8);?>
											</h5>
										</div>
									</div>
								</div>
							<?php }else{?>
								<div class="col-xs-6 card-col ng-show">
									<div class="bank-card add-card text-center" id="checkLoggedIn">
										<h6 class="add-card-now">添加银行卡(转向后点击提现或充值)</h6>
									</div>
								</div>
							<?php }?>
								</div>
							</div>
						</section>
					</section>
				</div>
			</div>
		</div>
	</div>
    <?php include("foot.php") ?>
</div>
<script>
	$(function(){
		$('#checkLoggedIn').click(function(){
			location.href = '/pay.html';
		});
	});
</script>
</body>
</html>