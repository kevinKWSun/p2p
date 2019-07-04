<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>充值提现</title>
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
				<div class="account-transfer content-wrapper ng-scope">
					<header class="section-header">
						<h6 class="section-header-title">充值提现</h6>
					</header>
					<section class="summary-section">
						<div class="row">
							<div class="col-xs-8">
								<div class="tip-wrapper">
									<header class="header">
										<h6 class="header-label">温馨提示</h6>
									</header>
									<div>
										<ul class="my-account-tip">
											<li>
												<p>在你申请提现前，如果未绑定银行卡请先点击充值或提现绑定</p>
											</li>
											<li>
												<p>收到你的提现请求后，将在1个工作日（双休日或法定节假日顺延）处理你的提现申请，请你注意查收</p>
											</li>
											<li>
												<p>为保障你的账户资金安全，申请提现时，你选择的银行卡开户名必须与你账户实名认证一致，否则提现申请将不予受理。</p>
											</li>
										</ul>
									</div>
								</div>
							</div>
							<div class="col-xs-4">
								<div class="status-wrapper">
									<div class="textTop">
										<div class="col-xs-6 cashText">可用余额</div>
										<div class="col-xs-6 cashNumber ng-binding">
											<?php echo $money['money_freeze']+$money['money_collect']+$money['account_money'] ?>
											<small>元</small>
										</div>
										<div class="col-xs-6 cashText">可提现金额</div>
										<div class="col-xs-6 cashNumber ng-binding">
											<?php echo $money['account_money'] ?>
											<small>元</small>
										</div>
									</div>
									<div class="buttonBottom">
										<a class="btn btn-action btn-embossed withdraw" rel='<?php echo $bank ? 1 : 0; ?>'>
											<span class="sl-icon-credit-card"></span>
											提现
										</a>
										<a class="btn btn-primary btn-embossed cload" rel='<?php echo $bank ? 1 : 0; ?>'>
											<span class="sl-icon-piggy-bank"></span>
											充值
										</a>
									</div>
								</div>
							</div>
						</div>
					</section>

					<div>
						<div id="addCardModal" class="add-card ng-hide">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<button class="close sl-icon-cross" type="button"></button>
										<h4 id="myModalLabel" class="modal-title">添加银行卡</h4>
									</div>
									<div class="modal-body summary-section">
										<div class="addCard-container">
											<div class="alert alert-info warning-info ng-show">
												<p class="ng-binding" style='corlor:red'></p>
											</div>
											<div class="alert alert-info">
												<p>
													为保证提现成功,请确认该银行卡开户姓名为
													<strong class="ng-binding">
													<?php echo $info['real_name']?$info['real_name']:"(请先 <a href='/account.html' style='color:red'>实名认证</a>)"?>
													</strong>
												</p>
											</div>
											<form action='/pay/add' method='post' target='_blank' class="form-horizontal ng-pristine ng-invalid ng-invalid-required" id="addCardForm">
											<div class="form-group">
												<label class="col-xs-3 col-xs-offset-1 control-label">持卡人</label>
												<div class="col-xs-7">
													<p class="form-control-static ng-binding"><?php echo $info['real_name']?></p>
												</div>
											</div>
											<div class="form-group">
												<label class="col-xs-3 col-xs-offset-1 control-label">银行账号</label>
												<div class="col-xs-7">
													<input class="form-control ng-pristine ng-invalid ng-invalid-required" type="text" name="account" placeholder='银行账号'>
												</div>
											</div>
											<div class="form-group">
												<label class="col-xs-3 col-xs-offset-1 control-label">选择银行</label>
												<div class="col-xs-7">
													<div class="btn-group select select-block mbn ng-isolate-scope">
														<select class="form-control" name="bank">
														<?php foreach($banks as $k => $v):?>
															<option class="ng-binding ng-scope" value="<?php echo $k?>"><?php echo $v?></option>
														<?php endforeach;?>
														</select>
													</div>
												</div>
											</div>
											<div class="form-group">
												<label class="col-xs-3 col-xs-offset-1 control-label">开户省份</label>
												<div class="col-xs-7">
													<div class="btn-group select-block mbn ng-isolate-scope">
														<select class="form-control province" name="province">
														<?php foreach($area as $k => $v):?>
															<option class="ng-binding ng-scope" value="<?php echo $v['name']?>" rel='<? echo $v['id']?>' <?php if($v['id'] == 860){echo 'selected';}?>><?php echo $v['name']?></option>
														<?php endforeach;?>
														</select>
													</div>
												</div>
											</div>
											<div class="form-group areas ng-hide">
												<label class="col-xs-3 col-xs-offset-1 control-label">开户市区</label>
												<div class="col-xs-7">
													<div class="btn-group area select-block mbn ng-isolate-scope">
														<select class="form-control" name="area"></select>
													</div>
												</div>
											</div>
											<div class="form-group citys ng-hide">
												<label class="col-xs-3 col-xs-offset-1 control-label">开户县区</label>
												<div class="col-xs-7">
													<div class="btn-group city select-block mbn ng-isolate-scope">
														<select class="form-control" name="city"></select>
													</div>
												</div>
											</div>
											<div class="form-group">
												<label class="col-xs-3 col-xs-offset-1 control-label">支行名称</label>
												<div class="col-xs-7">
													<input id="bankBranchName" class="form-control ng-scope ng-pristine ng-invalid ng-invalid-required" type="text" placeholder="请输入支行名称" name="bankBranchName">
												</div>
											</div>
											<input type='submit' class='ng-hide' />
											</form>
										</div>
									</div>
									<div class="modal-footer">
										<a class="btn btn-link ">取消</a>
										<button id="bankChoose" class="btn btn-secondary" type="submit">确定</button>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div id="loadBindFinish" class="ng-hide">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<button class="close sl-icon-cross" type="button"></button>
									<h4 id="myModalLabel" class="modal-title">完成绑定</h4>
								</div>
								<div class="modal-body">
									<p>请在绑定页面完成后选择：</p>
									<h6>绑定成功</h6>
									<p class="">
										查看
										<a href="/bank.html">绑定记录</a>
									</p>
									<h6>绑定失败</h6>
									<p class="">
										查看绑定
										<span>
											<a href="javascript:;" id='loadsbumit'>重新提交</a>
										</span>
										或联系客服
									</p>
								</div>
							</div>
						</div>
					</div>
					<div id="loadMoneyWizard" class="ng-isolate-scope ng-hide">
						<div class="modal-dialog modal-lg" style='width:850px;'>
							<div class="modal-content loadMoneyModal">
								<div class="modal-header">
									<button class="close sl-icon-cross rloadMoneyWizard" type="button"></button>
									<span class="modal-title">账户充值</span>
								</div>
								<div class="modal-body summary-section">
									<form class="loadMoneyForm ng-pristine ng-invalid ng-invalid-required" target="_blank" name="loadMoneyForm" action='/pay/adds' method='post'>
										<div class="row addLightBorder ng-isolate-scope">
											<label class="col-xs-1 inputNumberText ng-binding">充值金额</label>
											<div class="col-xs-2 inputNumber">
												<input class="form-control textFrame ng-pristine ng-invalid ng-invalid-required" type="text" placeholder="充值金额" name="transferAmt">
												<div class="ng-scope">
													<span class="show">
														<span class="ng-scope" style='color:red'></span>
													</span>
												</div>
												<input type="hidden" value="NEW_SITE_CLIENT" name="PAYMENT_CLIENT_SOURCE">
											</div>
											<div class="col-xs-2 col-xs-offset-1 alignLeft textAlign ng-scope">
												<label>当前可用余额</label>
											</div>
											<div class="col-xs-2 textAlign availableCash ng-scope">
												<div>
													<span class="ng-binding">
														<?php echo $money['account_money'] ?>
														<small>元</small>
													</span>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="">
												<label class="col-xs-1 inputNumberText">选择银行</label>
												<div class="ng-scope">
													<div class="col-xs-11 inputNumber chooseBank">
														<div id="paybank-list" class="paybank-list">
															<label class="radio paybank-item first paybank-item-style ng-scope">
																<span class="icons">
																	<span class="first-icon sl-icon-radio-unchecked"></span>
																	<span class="second-icon sl-icon-radio-checked"></span>
																</span>
																<input class="ng-pristine ng-valid ng-valid-required" type="radio" value="0801020000" name="bankType">
																<span class="bank-logo bank-icbc"></span>
															</label>
															<label class="radio paybank-item first paybank-item-style ng-scope checked">
																<span class="icons">
																	<span class="first-icon sl-icon-radio-unchecked"></span>
																	<span class="second-icon sl-icon-radio-checked"></span>
																</span>
																<input class="ng-pristine ng-valid ng-valid-required" type="radio" value="0801030000" name="bankType" checked="checked">
																<span class="bank-logo bank-abc"></span>
															</label>
															<label class="radio paybank-item first paybank-item-style ng-scope">
																<span class="icons">
																	<span class="first-icon sl-icon-radio-unchecked"></span>
																	<span class="second-icon sl-icon-radio-checked"></span>
																</span>
																<input class="ng-pristine ng-valid ng-valid-required" type="radio" value="0801040000" name="bankType">
																<span class="bank-logo bank-boc"></span>
															</label>
															<label class="radio paybank-item first paybank-item-style ng-scope">
																<span class="icons">
																	<span class="first-icon sl-icon-radio-unchecked"></span>
																	<span class="second-icon sl-icon-radio-checked"></span>
																</span>
																<input class="ng-pristine ng-valid ng-valid-required" type="radio" value="0801050000" name="bankType">
																<span class="bank-logo bank-ccb"></span>
															</label>
															<label class="radio paybank-item first paybank-item-style ng-scope">
																<span class="icons">
																	<span class="first-icon sl-icon-radio-unchecked"></span>
																	<span class="second-icon sl-icon-radio-checked"></span>
																</span>
																<input class="ng-pristine ng-valid ng-valid-required" type="radio" value="0803080000" name="bankType">
																<span class="bank-logo bank-mb"></span>
															</label>
															<label class="radio paybank-item paybank-item-style ng-scope ng-hide">
																<span class="icons">
																	<span class="first-icon sl-icon-radio-unchecked"></span>
																	<span class="second-icon sl-icon-radio-checked"></span>
																</span>
																<input class="ng-pristine ng-valid ng-valid-required" type="radio" value="0803010000" name="bankType">
																<span class="bank-logo bank-bc"></span>
															</label>
															<label class="radio paybank-item paybank-item-style ng-scope ng-hide">
																<span class="icons">
																	<span class="first-icon sl-icon-radio-unchecked"></span>
																	<span class="second-icon sl-icon-radio-checked"></span>
																</span>
																<input class="ng-pristine ng-valid ng-valid-required" type="radio" value="0803020000" name="bankType">
																<span class="bank-logo bank-cb"></span>
															</label>
															<label class="radio paybank-item paybank-item-style ng-scope ng-hide">
																<span class="icons">
																	<span class="first-icon sl-icon-radio-unchecked"></span>
																	<span class="second-icon sl-icon-radio-checked"></span>
																</span>
																<input class="ng-pristine ng-valid ng-valid-required" type="radio" value="0803030000" name="bankType">
																<span class="bank-logo bank-ceb"></span>
															</label>
															<label class="radio paybank-item paybank-item-style ng-scope ng-hide">
																<span class="icons">
																	<span class="first-icon sl-icon-radio-unchecked"></span>
																	<span class="second-icon sl-icon-radio-checked"></span>
																</span>
																<input class="ng-pristine ng-valid ng-valid-required" type="radio" value="0803040000" name="bankType">
																<span class="bank-logo bank-hb"></span>
															</label>
															<label class="radio paybank-item paybank-item-style ng-scope ng-hide">
																<span class="icons">
																	<span class="first-icon sl-icon-radio-unchecked"></span>
																	<span class="second-icon sl-icon-radio-checked"></span>
																</span>
																<input class="ng-pristine ng-valid ng-valid-required" type="radio" value="0803050000" name="bankType">
																<span class="bank-logo bank-cmb"></span>
															</label>
															<label class="radio paybank-item paybank-item-style ng-scope ng-hide">
																<span class="icons">
																	<span class="first-icon sl-icon-radio-unchecked"></span>
																	<span class="second-icon sl-icon-radio-checked"></span>
																</span>
																<input class="ng-pristine ng-valid ng-valid-required" type="radio" value="0803060000" name="bankType">
																<span class="bank-logo bank-gdb"></span>
															</label>
															<label class="radio paybank-item paybank-item-style ng-scope ng-hide">
																<span class="icons">
																	<span class="first-icon sl-icon-radio-unchecked"></span>
																	<span class="second-icon sl-icon-radio-checked"></span>
																</span>
																<input class="ng-pristine ng-valid ng-valid-required" type="radio" value="0804105840" name="bankType">
																<span class="bank-logo bank-sdb"></span>
															</label>
															<label class="radio paybank-item paybank-item-style ng-scope ng-hide">
																<span class="icons">
																	<span class="first-icon sl-icon-radio-unchecked"></span>
																	<span class="second-icon sl-icon-radio-checked"></span>
																</span>
																<input class="ng-pristine ng-valid ng-valid-required" type="radio" value="0803090000" name="bankType">
																<span class="bank-logo bank-ib"></span>
															</label>
															<label class="radio paybank-item paybank-item-style ng-scope ng-hide">
																<span class="icons">
																	<span class="first-icon sl-icon-radio-unchecked"></span>
																	<span class="second-icon sl-icon-radio-checked"></span>
																</span>
																<input class="ng-pristine ng-valid ng-valid-required" type="radio" value="0803100000" name="bankType">
																<span class="bank-logo bank-spdb"></span>
															</label>
															<label class="radio paybank-item paybank-item-style ng-scope ng-hide">
																<span class="icons">
																	<span class="first-icon sl-icon-radio-unchecked"></span>
																	<span class="second-icon sl-icon-radio-checked"></span>
																</span>
																<input class="ng-pristine ng-valid ng-valid-required" type="radio" value="0804031000" name="bankType">
																<span class="bank-logo bank-bob"></span>
															</label>
															<label class="radio paybank-item paybank-item-style ng-scope ng-hide">
																<span class="icons">
																	<span class="first-icon sl-icon-radio-unchecked"></span>
																	<span class="second-icon sl-icon-radio-checked"></span>
																</span>
																<input class="ng-pristine ng-valid ng-valid-required" type="radio" value="0804105840" name="bankType">
																<span class="bank-logo bank-spb"></span>
															</label>
															<label class="radio paybank-item paybank-item-style ng-scope ng-hide">
																<span class="icons">
																	<span class="first-icon sl-icon-radio-unchecked"></span>
																	<span class="second-icon sl-icon-radio-checked"></span>
																</span>
																<input class="ng-pristine ng-valid ng-valid-required" type="radio" value="0801000000" name="bankType">
																<span class="bank-logo bank-psbc"></span>
															</label>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-xs-10 buttonRecharge">
												<button type='submit' class="btn btn-primary rechargeMoney">充 值</button>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
					<div id="loadMoneyFinish" class="ng-hide">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<button class="close sl-icon-cross" type="button"></button>
									<h4 id="myModalLabel" class="modal-title">完成充值</h4>
								</div>
								<div class="modal-body">
									<p>请在新开网银页面完成充值后选择：</p>
									<h6>充值成功</h6>
									<p class="">
										查看
										<a href="/pay.html">充值记录</a>
									</p>
									<h6>充值失败</h6>
									<p class="">
										查看充值
										<span>
											<a href="javascript:;" id='reloadMoneyFinish'>重新提交</a>
										</span>
										或联系客服
									</p>
								</div>
							</div>
						</div>
					</div>
					<div id="withdrawMoneyWizard" class='ng-hide'>
						<div class="modal-dialog">
							<div class="modal-content">
								<form class="ng-pristine ng-invalid ng-invalid-required" name="withdrawMoneyForm" target="_blank" action='/pay/withdraw' method='post'>
									<div class="ng-isolate-scope">
										<div class="modal-header">
											<button class="close sl-icon-cross" type="button"></button>
											<h4 class="modal-title ng-binding">提现申请</h4>
											<ul class="steps-indicator steps-2">
												<li class="ng-scope current">
													<a class="ng-binding ng-pristine ng-valid">
														<span class="step-num ng-binding">1</span>
														输入提现金额
													</a>
												</li>
												<li class="ng-scope default">
													<a class="ng-binding ng-pristine ng-valid">
														<span class="step-num ng-binding">2</span>
														等待处理
													</a>
												</li>
											</ul>
										</div>
										<div class="steps">
											<section class="step1 step ng-isolate-scope current">
												<div class="modal-body ng-scope">
													<h6 class="available-to-invest">
														可提现金额：
														<span class="ng-binding">
															<?php echo $money['account_money'] ?>
															<small>元</small>
														</span>
													</h6>
													<div class="form-horizontal">
														<div class="form-group">
															<label class="col-xs-3 col-xs-offset-1 control-label">持卡人</label>
															<div class="col-xs-5">
																<p class="form-control-static ng-binding">
																<?php echo $info['real_name']?>
																</p>
															</div>
														</div>
														<div class="form-group">
															<label class="col-xs-3 col-xs-offset-1 control-label">提现银行</label>
															<div class="col-xs-5 ng-scope">
																<div class="form-control">
																	<select class="select-options ng-pristine ng-valid" name='bid'>
																		<option value="<?php echo $bank['bid']?>"><?php echo substr_replace($bank['card'],' **** **** ',4,8);?></option>
																	</select>
																</div>
															</div>
														</div>
														<div class="form-group withdraw-amount-row">
															<label class="col-xs-3 col-xs-offset-1 control-label">提现金额</label>
															<div class="col-xs-5">
																<input id="amount" class="form-control ng-scope ng-pristine ng-invalid ng-invalid-required" placeholder="输入提现金额" name="withdrawAmt">
																<span class="yuan">元</span>
															</div>
														</div>
														<div class="warning-tip">
															<div class="col-xs-3 col-xs-offset-1"></div>
															<div class="col-xs-6">谨慎填写信息，提现才会成功哦！</div>
														</div>
													</div>
												</div>
												<div class="modal-footer ng-scope">
													<a class="btn btn-link" href="javascript:;">取消</a>
													<button type='submit' class="btn btn-secondary" id='secondary'>下一步</button>
												</div>
											</section>
											<section class="step2 step ng-isolate-scope ng-hide">
												<div class="modal-body ng-scope" style="text-align: center">
													<h6>款项成功后将在1-2工作日内转入你的银行账户。</h6>
												</div>
												<div class="modal-footer ng-scope">
													<a class="btn btn-secondary" href="/pay.html">关闭</a>
													<a class="btn btn-secondary" id='withdraw'>重新提交</a>
												</div>
											</section>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
					<div id="notBindBankcardModal" class="not-bind-bank-modal ng-hide" style='padding-top:10px;'>
						<div class="modal-dialog modal-sm">
							<div class="modal-content">
								<div class="modal-header">
									<button class="close sl-icon-cross" aria-hidden="true" data-dismiss="modal" type="button"></button>
									<h4 class="modal-title">提现</h4>
								</div>
								<div class="modal-body">
									<div class="content-tip">你尚未绑定取现银行卡</div>
									<div class="row text-center">
										<a class="btn btn-secondary btn-confirm big-btn">去绑定</a>
									</div>
								</div>
							</div>
						</div>
					</div>
					<section class="summary-section account-transfer-filter ng-hide">
						<div>
							<span class="account-transfer-filter-title">交易时间</span>
							<a class="btn btn-sm btn-filter active">所有 </a>
							<a class="btn btn-sm btn-filter">今天 </a>
							<a class="btn btn-sm btn-filter">最近一周 </a>
							<a class="btn btn-sm btn-filter">一个月 </a>
							<a class="btn btn-sm btn-filter">三个月 </a>
							<a class="btn btn-sm btn-filter">六个月 </a>
						</div>
						<div>
							<span class="account-transfer-filter-title">交易类型</span>
							<a class="btn btn-sm btn-filter active">全部 </a>
							<a class="btn btn-sm btn-filter">充值 </a>
							<a class="btn btn-sm btn-filter">提现 </a>
						</div>
					</section>
					<section class="summary-section">
						<div>
							<div class="data-table-wrapper ng-isolate-scope">
								<table class="table data-table table-hover table-striped ">
									<thead>
										<tr>
											<th class="ng-scope">
												<span class="active sortable">
													<span class="ng-binding">交易流水号</span>
												</span>
											</th>
											<th class="ng-scope">
												<span class="active sortable">
													<span class="ng-binding">交易类型</span>
												</span>
											</th>
											<th class="ng-scope text-right">
												<span class="active sortable">
													<span class="ng-binding">交易金额</span>
												</span>
											</th>
											<th class="ng-scope text-right">
												<span class="active sortable">
													<span class="ng-binding">账户余额</span>
													<!--span class="sl-icon-pointer-down-dark"></span-->
												</span>
											</th>
											<th class="ng-scope">
												<span class="active sortable">
													<span class="ng-binding">交易状态</span>
												</span>
											</th>
											<th class="ng-scope">
												<span class="active sortable">
													<span class="ng-binding">交易时间</span>
												</span>
											</th>
										</tr>
									</thead>
									<tbody>
									<?php foreach($log as $v):?>
										<tr>
											<th class="ng-scope">
												<span class="sortable">
													<span class="ng-binding"><?php echo $v['bid']?></span>
												</span>
											</th>
											<th class="ng-scope">
												<span class="sortable">
													<span class="ng-binding"><?php if($v['type']==1){echo '充值';}else{echo '提现';}?></span>
												</span>
											</th>
											<th class="ng-scope text-right">
												<span class="sortable">
													<span class="ng-binding"><?php echo $v['affect_money']?></span>
												</span>
											</th>
											<th class="ng-scope text-right">
												<span class="sortable">
													<span class="ng-binding"><?php echo $v['account_money']?></span>
													<!--span class="sl-icon-pointer-down-dark"></span-->
												</span>
											</th>
											<th class="ng-scope">
												<span class="sortable">
													<span class="ng-binding">成功</span>
												</span>
											</th>
											<th class="ng-scope">
												<span class="sortable">
													<span class="ng-binding"><?php echo date('Y-m-d H:i',$v['add_time']);?></span>
												</span>
											</th>
										</tr>
									<?php endforeach;?>
									</tbody>
								</table>
							</div>
						</div>
						<div class="notes-pagination">
							<div class="sl-pagination pagination ng-isolate-scope">
								<div class="fhui-admin-pagelist">
									<div class="page">
										<?php echo $page; ?>
										<a>共 <?php echo $totals; ?> 条</a>
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
<script>
	$(function(){
		$('button#secondary').click(function(){
			$('.step2').removeClass('ng-hide');
			$('.step2').addClass('ng-show');
			$('.step1').addClass('ng-hide');
		});
		$('#withdraw').click(function(){
			$('.step1').removeClass('ng-hide');
			$('.step1').addClass('ng-show');
			$('.step2').addClass('ng-hide');
		});
		$('button.rechargeMoney').click(function(){
			$('#loadMoneyFinish').removeClass('ng-hide');
			$('#loadMoneyFinish').addClass('ng-show');
			$('#loadMoneyWizard').addClass('ng-hide');
		});
		$('#reloadMoneyFinish').click(function(){
			$('#loadMoneyWizard').removeClass('ng-hide');
			$('#loadMoneyWizard').addClass('ng-show');
			$('#loadMoneyFinish').addClass('ng-hide');
		});
		$('.chooseBank label').click(function(){
			$('.chooseBank label').removeClass('checked');
			$('.chooseBank input').attr('checked', false);
			$(this).addClass('checked');
			$(this).find('input').attr('checked', true);
		});
		//cq
		$('a.cload').click(function(){
			$('.ng-show').addClass('ng-hide');
			var rel = $(this).attr('rel');
			if(rel == 1){
				$('#loadMoneyWizard').removeClass('ng-hide');
				$('#loadMoneyWizard').addClass('ng-show');
			}else{
				$('#notBindBankcardModal').removeClass('ng-hide');
				$('#notBindBankcardModal').addClass('ng-show');
			}
		});
		//tix
		$('button#bankChoose').click(function(){
			$('.warning-info').removeClass('ng-hide');
			$('.warning-info').addClass('ng-show');
			if($('input[name=account]').val() == '' || isNaN($('input[name=account]').val())){
				$('.warning-info p').text('卡号不正确...');
				return;
			}
			if($('select[name=area]').val() == 0){
				$('.warning-info p').text('请选择开户市区...');
				return;
			}
			if($('select[name=city]').val() == 0){
				$('.warning-info p').text('请选择开户县区...');
				return;
			}
			if(! $('input[name=bankBranchName]').val()){
				$('.warning-info p').text('请输入支行名称...');
				return;
			}
			$('#loadBindFinish').removeClass('ng-hide');
			$('#loadBindFinish').addClass('ng-show');
			$('#addCardModal').addClass('ng-hide');
			$('.warning-info p').text('重新提交中...');
			$('#addCardForm').submit();
		});
		$('#loadsbumit').click(function(){
			$('#loadBindFinish').addClass('ng-hide');
			$('#addCardModal').removeClass('ng-hide');
			$('#addCardModal').addClass('ng-show');
		});
		$('.withdraw').click(function(){
			$('.ng-show').addClass('ng-hide');
			var rel = $(this).attr('rel');
			if(rel == 1){
				$('#withdrawMoneyWizard').removeClass('ng-hide');
				$('#withdrawMoneyWizard').addClass('ng-show');
			}else{
				$('#notBindBankcardModal').removeClass('ng-hide');
				$('#notBindBankcardModal').addClass('ng-show');
			}
		});
		$('a.big-btn').click(function(){
			$('.ng-show').addClass('ng-hide');
			$('#addCardModal').removeClass('ng-hide');
			$('#addCardModal').addClass('ng-show');
			$.get('/pay/getarea/860/1.html', function(c1){
				$('.areas').removeClass('ng-hide');
				$('.areas').addClass('ng-show');
				$('.area select').html(c1);
			});
		});
		$('button.close').click(function(){
			$('.ng-show').addClass('ng-hide');
		});
		$('a.btn-link').click(function(){
			$('.ng-show').addClass('ng-hide');
		});
		//otix
		//
		$('select.province').change(function(){
			var rel = $(this).find('option:selected').attr('rel');
			$('.citys').removeClass('ng-show');
			$('.citys').addClass('ng-hide');
			$.get('/pay/getarea/' + rel + '/1.html', function(s){
				$('.areas').removeClass('ng-hide');
				$('.areas').addClass('ng-show');
				$('.area select').html(s);
			});
		});
		$('.area select').change(function(){
			var rel = $(this).find('option:selected').attr('rel');
			if(rel != 0){
				$.get('/pay/getarea/' + rel + '/2.html', function(cs){
					$('.citys').removeClass('ng-hide');
					$('.citys').addClass('ng-show');
					$('.city select').html(cs);
				});
			}else{
				$('.citys').removeClass('ng-show');
				$('.citys').addClass('ng-hide');
			}
		});
	});
</script>
</body>
</html>