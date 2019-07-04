<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>账户总览</title>
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
			<div class="col-xs-9 ng-scope">
				<div class="account-summary content-wrapper ng-scope">
					<section class="row simple-summary">
						<div class="col-xs-7 simple-summary-section total-net-income">
							<div id="column-chart" class="total-interest">
								<div class="text-center ng-hide" ng-show="!gotSummary">
									<h4 class="loading-animation summary-loading">
										<i class="spinner sl-icon-loading"></i>
									</h4>
								</div>
								<div class="monthly-income">
									<ul class="list-inline monthly-income-bars">
										<?php foreach($ms as $k => $v):?>
										<li class="every-month ng-scope" style="left: <?php echo $k * 38.9167;?>px;">
											<a href="javascript:;" style="width: 33.9167px; height: 8px;">
												<div class="income-info">
													<span class="ng-binding"><?php echo $v['m']?>净收入</span>
													<br>
													<span class="ng-binding"><?php echo $v['tm'] ? $v['tm'] : 0;?>元</span>
												</div>
											</a>
										</li>
										<?php endforeach;?>
										<div class="sum-number inside-column">
											<h3 class="highlighted-sum">
												<abbr class="ng-binding ng-scope" title="">
													<?php echo $tm;?>
													<small>元</small>
												</abbr>
											</h3>
											<p class="highlighted-sum-caption">累计净收益</p>
										</div>
									</ul>
								</div>
							</div>
						</div>
						<div class="col-xs-5 simple-summary-section balance-sheet">
							<div class="text-center ng-hide">
								<h4 class="loading-animation summary-loading">
									<i class="spinner sl-icon-loading"></i>
								</h4>
							</div>
							<div class="sum-number">
								<h3 class="highlighted-sum ng-binding">
									<?php echo $ky['account_money'] ? $ky['account_money'] : 0;?>
									<small>元</small>
								</h3>
								<p class="highlighted-sum-caption">可用余额</p>
								<a class="btn btn-secondary btn-embossed" href="/pay.html">
									<span class="sl-icon-piggy-bank"></span>
									充值
								</a>
							</div>
						</div>
					</section>
					<section class="summary-section my-asset">
						<div class="asset-content">
							<div class="ng-scope" ng-if="gotSummary">
								<div class="asset-chart">
									<div class="con-top con-top-a loaded">
										<div class="top">
											<span class="text">可用余额</span>
											<span class="value ng-binding" title="0.00元">
												<?php echo $ky['account_money'] ? $ky['account_money'] : 0;?>
												<small>元</small>
											</span>
										</div>
										<!--div>
											<a class="link" href="market.html">查看热投项目</a>
										</div-->
									</div>
									<div class="con-top con-top-b loaded" ng-class="{loaded:loaded}">
										<div class="top">
											<span class="text">冻结金额</span>
											<span class="value ng-binding" ng-bind-html="summary.inFundingAmount | slMoney" title="0.00元">
												<?php echo $ky['money_freeze'] ? $ky['money_freeze'] : 0;?>
												<small>元</small>
											</span>
										</div>
									</div>
									<div class="con-top con-top-c loaded" ng-class="{loaded:loaded}">
										<div class="top">
											<span class="text">待收本金</span>
											<span class="value ng-binding" ng-bind-html="chartSummary.OutstandingCash.value | slMoney" title="0">
												<?php echo $ky['money_collect'] ? $ky['money_collect'] : 0;?>
												<small>元</small>
											</span>
										</div>
									</div>
								</div>
								<div id="total-asset" class="total-asset text-center loaded" ng-class="{loaded:loaded}">
									<h3 class="ng-binding" ng-bind-html="chartSummary.totalAssets | slMoney">
										<?php echo $ky['money_collect']+$ky['money_freeze']+$ky['account_money'] ;?>
										<small>元</small>
									</h3>
									<h6 class="text">总资产 </h6>
								</div>
							</div>
						</div>
					</section>
				</div>
			</div>
		</div>
	</div>
    <?php include("foot.php") ?>
</div>
</body>
</html>