<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>投标记录</title>
    <script type='text/javascript' src='/Content/js/jquery-1.7.2.min.js'></script>
    <link href="/Css/bootstrap.min.css" rel="stylesheet" /> 
	<link href="/Css/components.css?ver=142682356" rel="stylesheet" /> 
	<link href="/Css/main.css?ver=142682356" rel="stylesheet" />
	<link href="/Css/new-home.css?ver=142682356" rel="stylesheet" />
	<!--[if lt IE 9]>
	<link href="/Css/ie8.css?ver=142682356" rel="stylesheet">
	<![endif]--> 
	<!--[if IE 9]>
	<link href="/Css/ie9.css?ver=142682356" rel="stylesheet">
	<![endif]-->
</head>
<body>
<?php include("top.php") ?>
<div class="wrapper ">  
    <div id="my-account" class="container my-account"> 
        <div class="row"> 
			<?php include("left.php") ?>
			<div class="col-xs-9 ng-scope">
				<div class="invest-history content-wrapper ng-scope">
					<div class="nav-wrapper">
						<ul id="invest-history-tab" class="nav nav-tabs">
							<li <?php if($status == 4){echo ' class="active"';}?>>
								<a href="/record/index/1?query=4">
									还款中的投资
								</a>
							</li>
							<li <?php if($status == 2){echo ' class="active"';}?>>
								<a href="/record/index/1?query=2">
									投标中的投资
								</a>
							</li>
							<li <?php if($status == 5){echo ' class="active"';}?>>
								<a href="/record/index/1?query=5">
									已结清的投资
								</a>
							</li>
						</ul>
					</div>
					<section class="summary-sec">
						<div class="tab-content">
							<div id="ih-myNotes" class="tab-pane active my-notes">
								<div class="my-notes-row">
									<div class="my-notes-summary">
										<div class="row summary-section">
											<div class="col-xs-5 divider monthly">
												<h3 class="highlighted-sum ng-binding">
													<?php echo $month['investor_capital'] + $month['investor_interest'];?>
													<small>元</small>
												</h3>
												<p class="highlighted-sum-caption ng-binding">本月预期回款</p>
											</div>
											<div class="col-xs-5 dayly">
												<h3 class="highlighted-sum ng-binding">
													<?php echo $day['investor_capital'] + $day['investor_interest'];?>
													<small>元</small>
												</h3>
												<p class="highlighted-sum-caption">今日预期回款</p>
											</div>
										</div>
									</div>
								</div>
								<div class="table-wrapper ng-show">
									<h2 class="text-center loading-animation ng-hide">
										<i class="spinner sl-icon-loading"></i>
									</h2>
									<div>
										<?php if($borrow){?>
										<div class="notes-table">
											<div class="data-table-wrapper ng-isolate-scope">
												<table class="table data-table table-hover table-striped ">
													<thead>
														<tr>
															<th class="ng-scope">
																<span class="ng-binding">
																	<span>名称</span>
																</span>
															</th>
															<th class="ng-scope">
																<span>
																	<span class="ng-binding">年化利率(%)</span>
																</span>
															</th>
															<th class="ng-scope">
																<span>
																	<span class="ng-binding">投资金额(元)</span>
																</span>
															</th>
															<th class="ng-scope">
																<span>
																	<span class="ng-binding">剩余本金(元)</span>
																</span>
															</th>
															<th class="ng-scope">
																<span>
																	<span class="ng-binding">剩余利息(元)</span>
																</span>
															</th>
															<th class="ng-scope">
																<span class="ng-binding">
																	<span>还款时间</span>
																</span>
															</th>
														</tr>
													</thead>
													<tbody>
														<?php foreach($borrow as $v):?>
														<tr>
															<td><a href='/invest/show/<?php echo $v['borrow_id']?>.html' target='_blank'><?php echo $v['borrow_name']?></a></td>
															<td><?php echo $v['borrow_interest_rate']?></td>
															<td><?php echo $v['borrow_money']?></td>
															<td><?php echo $v['investor_capital']-$v['receive_capital']?></td>
															<td><?php echo $v['investor_interest']-$v['receive_interest']?></td></td>
															<td><?php echo date('Y-m-d', $v['deadline'])?></td>
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
										<div class="notes-pagination ng-show">
											<div class="sl-pagination pagination ng-isolate-scope">
												<div class="page">
													<?php echo $page; ?>
													<a>共 <?php echo $totals; ?> 条</a>
												</div>
											</div>
										</div>
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