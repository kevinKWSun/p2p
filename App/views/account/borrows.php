<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>我要借款</title>
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
    <div class="wrapper borrower-landing"> 
		<header class="sl-header" ng-controller="HeaderCtrl" id="sl-header"> 
			<div class="site-menu">
				<div class="jumbotron"> 
					<div id="borrower-banner" class="container hero"> 
						<div class="row"> 
							<div class="col-xs-8 invisible"> 
								<h5 class="slogan">一笔资金，一些梦想</h5> 
								<div id="carousel-borrower" class="carousel slide"> 
									<div class="carousel-inner"> 
										<div class="item" style="display: block"> 
											<span class="carousel-caption-icon"></span> 
											<h3>申请，审核，放款，24小时搞定</h3> 
											<h6>开放透明的平台，行业最低借款费用，拒绝利差！</h6> 
											<div class="actions"> 
												<a href="/suny/reg.html?type=borrower" class="btn btn-secondary btn-embossed">立即注册申请贷款</a> 
											</div> 
										</div> 
									</div> 
								</div> 
							</div> 
							<div class="col-xs-4 check-rate-form"> 
								<h5 class="slogan check-rate-form-title">我需要资金</h5> 
								<h5 class="slogan check-rate-form-title" style='padding:60px 0'>
									<div class="actions"> 
										<a href="/suny/reg.html?type=borrower" class="btn btn-secondary btn-embossed" target="_blank">立即注册申请贷款</a> 
									</div> 
								</h5>
								<div class="select-loan"> 
									<div class="tab-content" id="container"> 
										<div class="tab-pane active" id="business-loan"> 
											<!--form name="verifyBusinessLoanForm" class="form-vertical"> 
												<div class="form-group"> 
													<input type="text" name="username" placeholder="真实姓名" class="form-control flat" value="" />
												</div> 
												<div class="form-group"> 
													<input type="text" name="mobile" placeholder="手机号码" class="form-control flat" value="" />
												</div> 
												<div class="form-group"> 
													<button class="btn btn-block btn-action btn-embossed" type="submit">即刻申请</button> 
												</div> 
											</form-->
											<div class="form-group"> 
												<div id="regist-success">
													注册成功，稍后会有工作人员与您联系。谢谢！
												</div> 
											</div> 
										</div> 
									</div> 
								</div> 
							</div> 
						</div> 
					</div> 
				</div> 
			</div> 
		</header> 
		
		<div id="notifications" class="ng-cloak affix-top"> 
			<div class="container"> 
				<div class="msg-fold-up"> 
				</div> 
				<div class="alert msg-content"> 
					<a type="button" class="close sl-icon-cross"></a> 
					<p></p> 
				</div> 
				<div class="msg-fold-down"> 
				</div> 
			</div> 
		</div> 
		<!--content--> 
		<div class="borrower-content">
			<div id="tutorial"> 
				<section id="borrower-what-is-dianrong" class="container landing-about-us"> 
					<div id="borrower-what-is-dianrong-header"> 
						<h5 class="tutorial-section-title">什么是伽满优?</h5> 
						<h6 class="tutorial-section-subtitle">借贷也可以很幸福哦!</h6> 
					</div> 
					<div class="row"> 
						<div class="col-xs-5"> 
							<img src="images/tutorial-boss-heads.png" /> 
						</div> 
						<div class="col-xs-7 tutorial-content"> 
							<h3>最好的<span>管理团队</span></h3> 
							<ul class="list-inline tutorial-content-point"> 
								<li>出色的专业素养</li> 
								<li>毕业于世界著名学府, 8人出自美国常青藤学院</li> 
								<li>各自专业领域中的佼佼者</li> 
							</ul> 
							<ul class="list-inline tutorial-content-point"> 
								<li>丰富的实践经验</li> 
								<li>平均拥有15年以上国际知名企业的工作经验</li> 
								<li>6人曾经自主创业</li> 
								<li>4人曾经从事国内银行相关金融管理</li> 
							</ul> 
						</div> 
					</div> 
					<div class="row"> 
						<div class="col-xs-7 tutorial-content technology"> 
							<h3>强大的<span>技术平台</span></h3> 
							<ul class="list-inline tutorial-content-point"> 
								<li>领先的P2P技术</li> 
								<li>创始人来自全球最大网络借贷平台Lending Club</li> 
								<li>引进Lending Club的先进技术和管理经验</li> 
								<li>P2P技术鼻祖, 只做纯正的P2P网贷</li> 
							</ul> 
							<ul class="list-inline tutorial-content-point"> 
								<li>透明的自主借贷</li> 
								<li>简易高效的操作, 自助式借贷轻松借款投标</li> 
								<li>先进技术实现最低的审核成本</li> 
							</ul> 
						</div> 
						<div class="col-xs-5"> 
							<img src="images/tutorial-radish.png" /> 
						</div> 
					</div> 
					<div class="row tutorial-risk-control-bg"> 
						<div class="col-xs-5"> 
						</div> 
						<div class="col-xs-7 tutorial-content riskmanagement"> 
							<h3>严格的<span>风险控制</span></h3> 
							<ul class="list-inline tutorial-content-point"> 
								<li>完备的校验流程</li> 
								<li>基于多家商业银行7年数据研发的近100个风险模型</li> 
								<li>互联网信息源、多个第三方信息源、50多条信息校验规则及人工深层排查防止欺诈风险</li> 
								<li>历史坏账率1%以下，历史年化收益率达15-18%</li> 
							</ul> 
							<ul class="list-inline tutorial-content-point"> 
								<li>切实的法律保障</li> 
								<li>共同创始人拥有数十年法律金融行业经验</li> 
								<li>律师团队全程控制合约风险及合规监控</li> 
								<li>国资企业东方资产为合作伙伴</li> 
							</ul> 
						</div> 
					</div> 
				</section> 
				<section id="borrower-testimony" class="landing-testimony"> 
					<div class="container"> 
						<h5 class="tutorial-section-title">为什么选择伽满优?</h5> 
						<ul class="list-inline"> 
							<li> 
								<div class="landing-testimony-item1"> 
									<img src="images/tutorial-free-charge.png" /> 
								</div> <h5><span>1</span> 无手续费</h5> 
								<ul class="list-unstyled"> 
									<li>我们最大的特色在于去掉了借</li> 
									<li>款的中间环节，不收取手续费</li> 
								</ul> </li> 
							<li> 
								<div class="landing-testimony-item2"> 
									<img src="images/tutorial-low-interest.png" /> 
								</div> <h5><span>2</span> 超低利息</h5> 
								<ul class="list-unstyled"> 
									<li>绝大数借款利息在12-18%</li> 
									<li>远低于民间利息</li> 
								</ul> </li> 
							<li> 
								<div class="landing-testimony-item3"> 
									<img src="images/tutorial-quick-progress.png" /> 
								</div> <h5><span>3</span> 快速审批</h5> 
								<ul class="list-unstyled"> 
									<li>审批直接在网上完成，无需出</li> 
									<li>门，大大减少时间精力</li> 
								</ul> </li> 
							<li> 
								<div class="landing-testimony-item4"> 
									<img src="images/tutorial-quick-funding.png" /> 
								</div> <h5><span>4</span> 迅速筹标</h5> 
								<ul class="list-unstyled"> 
									<li>历史筹标时间基本在24小时满</li> 
									<li>标，第三方支持系统马上转账</li> 
								</ul> </li> 
						</ul> 
					</div> 
				</section> 
				<section id="four-steps-borrow" class="clearfix"> 
					<div class="col-xs-8 col-xs-offset-2"> 
						<h5 class="tutorial-section-title">轻松四步，借款无烦恼!</h5> 
						<div class="row"> 
							<div class="four-steps-borrow-col1 col-xs-6"> 
								<div class="four-steps-borrow-item1"> 
									<figure> 
										<img src="images/tutorial-apply-loan-progress-01.png" /> 
										<figcaption> 
											<span>1</span> 
											<div> 
												<h4>完成<span>注册</span>成为借款人</h4> 
												<p>超轻松注册</p> 
											</div> 
										</figcaption> 
									</figure> 
								</div> 
								<div class="four-steps-borrow-item3"> 
									<figure> 
										<img src="images/tutorial-apply-loan-progress-04.png" /> 
										<figcaption> 
											<span>3</span> 
											<div> 
												<h4>快速审批</h4> 
												<p>1-3个工作日内完成审批</p> 
											</div> 
										</figcaption> 
									</figure> 
								</div> 
							</div> 
							<div class="four-steps-borrow-col2  col-xs-6"> 
								<div class="four-steps-borrow-item2"> 
									<figure> 
										<img src="images/tutorial-apply-loan-progress-02.png" /> 
										<figcaption> 
											<span>2</span> 
											<div> 
												<h4>提交审批材料</h4> 
												<p>降低借贷成本</p> 
											</div> 
										</figcaption> 
									</figure> 
								</div> 
								<div class="four-steps-borrow-item4"> 
									<figure> 
										<img src="images/tutorial-apply-loan-progress-03.png" /> 
										<figcaption> 
											<span>4</span> 
											<div> 
												<h4>出资人投标</h4> 
												<p>迅速取得融资</p> 
											</div> 
										</figcaption> 
									</figure> 
								</div> 
							</div> 
						</div> 
					</div> 
				</section> 
				<section id="borrower-simple-faq" class="landing-section landing-simple-faq" ng-controller="LandingFaqCtrl"> 
					<div class="container"> 
						<span class="icon-landing-section-dianrong-logo"></span> 
						<header> 
							<span class="faq-icon">FAQ</span> 
							<h4 class="faq-icon-title">常见问题</h4> 
							<h6>你有问题，我们有解答</h6> 
						</header>
						<div class="more"> 
							<span>有更多问题？请咨询客服</span> 
						</div> 
					</div> 
				</section> 
			</div> 
		</div> 
		<div id="call-to-action"> 
			<div class="container footer-decor"> 
				<h4>实现梦想的路上需要各种助力</h4> 
				<p><a class="btn btn-lg btn-action btn-embossed" href="/suny/reg.html?type=borrower">立即申请贷款</a></p> 
			</div> 
		</div> 
	</div>
    <?php include("foot.php") ?>
</div>
</body>
</html>