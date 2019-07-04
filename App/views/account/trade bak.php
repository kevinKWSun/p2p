<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>交易记录</title>
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
				<div class="trade-history content-wrapper ng-scope">
					<header class="section-header">
						<h6 class="section-header-title">交易记录</h6>
					</header>
					<section class="summary-section">
						<div class="trade-header">
							
						</div>
						<h2 class="text-center ng-hide">
							<i class="spinner sl-icon-loading"></i>
						</h2>
						<div id="table-tradeHistoryItems" class="">
							<?php if($moneylog){?>
							<div class="notes-table">
								<div class="data-table-wrapper ng-isolate-scope">
									<table class="table data-table table-hover table-striped ">
										<thead>
											<tr>
												<th class="ng-scope">
													<span>
														<span class="ng-binding">交易日期</span>
													</span>
												</th>
												<th class="ng-scope">
														<span class="ng-binding">交易类型</span>
													</span>
												</th>
												<th class="ng-scope">
														<span class="ng-binding">金额</span>
													</span>
												</th>
												<th class="ng-scope">
													<span>
														<span class="ng-binding">
															<span title="余额=可用余额+冻结金额">余额</span>
														</span>
													</span>
												</th>
												<th class="ng-scope">
													<span>
														<span class="ng-binding">交易描述</span>
													</span>
												</th>
											</tr>
										</thead>
										<tbody>
											<?php foreach($moneylog as $v):?>
											<tr>
												<td><?php echo date('Y-m-d', $v['add_time'])?></td>
												<td><?php echo $this->config->item('record')[$v['type']]?></td>
												<td><?php echo $v['affect_money']?></td>
												<td><?php echo $v['account_money'] + $v['freeze_money']?></td>
												<td><?php echo $v['info']?></td>
											</tr>
											<?php endforeach;?>
										</tbody>
									</table>
								</div>
							</div>
							<?php }else{?>
							<div class="alert alert-warning clearfix">
								无任何记录
								<a class="close sl-icon-cross"></a>
							</div>
							<?php }?>
							<div class="notes-pagination">
								<div class="sl-pagination pagination ng-isolate-scope">
									<div class="page">
										<?php echo $page; ?>
										<a>共 <?php echo $totals; ?> 条</a>
									</div>
								</div>
							</div>
						</div>
						<div class="history-general">
							<div class="row">
								<div class="col-xs-6 text-center right-gray-border">
									<h3 class="col-xs-6 col-xs-offset-3 text-center green-label ng-binding">
										<?php echo $in['affect_money']?>
										<small>元</small>
									</h3>
									<div class="col-xs-6 col-xs-offset-3 text-center paddingUpDown">
										<span>流入</span>
									</div>
								</div>
								<div class="col-xs-6 text-center">
									<h3 class="col-xs-6 col-xs-offset-3 text-center dark-blue-label ng-binding">
										<?php echo $out['affect_money']?>
										<small>元</small>
									</h3>
									<div class="col-xs-6 col-xs-offset-3 text-center paddingUpDown">
										<span>流出</span>
									</div>
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