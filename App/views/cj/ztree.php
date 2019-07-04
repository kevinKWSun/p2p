<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>伽满优-发财树</title>
		<link rel="stylesheet" href="/src/plantree/css/trees.css" type="text/css" />
		<link rel="stylesheet" href="/src/plantree/css/simpleAlert.css">

		<link href="/images/new/index.css" rel="stylesheet" type="text/css" />
		<script src="/src/plantree/js/simpleAlert.js"></script>
	</head>
	<style>
		body{
			background: #f5f5f5;
		}
		.header  a{
			color: #000000;
		}
	</style>
	<body>
		<div class="header">
			<div class="top">
				<div class="sn-menu">
					
					<?php if(!QUID) { ?>
						<a class="menu-hd" href="/suny/login.html?ret_url=/ztree.html"><i class="icon"></i>登陆<b class="icon"></b></a>
					<?php } else { ?>
						<a class="menu-hd" href="/account.html"><i class="icon"></i>我的账户<b class="icon"></b></a>
						<div class="menu-bd">
							<div class="menu-bd-panel">
								<a href="/suny/out.html">退出</a>
							</div>
						</div>
					<?php } ?>
					

				</div>
				<a href="/"><img src="https://www.jiamanu.com/images/logo.jpg" class="logo" /></a>
				<ul>
					<li><a href="/">首页</a></li>
					<li><a href="/invest.html">我要出借</a></li>
					<li><a href="/message/novice.html">帮助中心</a></li>
					<li><a href="/message.html">信息披露</a></li>
					<li><a href="/suny/borrowbefor.html">我要借款</a></li>
					<li><a href="/message.html">关于伽满优</a></li>
				</ul>
			</div>
		</div>

		<div id="tree-body">
			<div id="tree-banner">
				<img src="/src/plantree/img/banner.jpg">
			</div>
			<div class="tree-main">
				<div class="tree-main-rule">
					<img src="/src/plantree/img/rule.png">
				</div>



				<div class="tree-main-prize" id="ztree_dh">
					<ul>
						<li>
							<img src="/src/plantree/img/tree-007.png" />
							<p>现金红包128元</p>
							<p>数量<var><?php echo isset($ztree_order_red_1) ? $ztree_order_red_1 : 0; ?></var>个</p>
							<p class="apple-<?php echo (isset($ztree['nnum']) && $ztree['nnum'] >= 1) ? 'yes' : 'no'; ?>-conversion">苹果1个兑换</p>
						</li>
						<li>
							<img src="/src/plantree/img/tree-006.png" />
							<p>现金红包300元</p>
							<p>数量<var><?php echo isset($ztree_order_red_2) ? $ztree_order_red_2 : 0; ?></var>个</p>
							<p class="apple-<?php echo (isset($ztree['nnum']) && $ztree['nnum'] >= 2) ? 'yes' : 'no'; ?>-conversion">苹果2个兑换</p>
						</li>
						<li>
							<img src="/src/plantree/img/tree-005.png" />
							<p>现金红包900元</p>
							<p>数量<var><?php echo isset($ztree_order_red_3) ? $ztree_order_red_3 : 0; ?></var>个</p>
							<p class="apple-<?php echo (isset($ztree['nnum']) && $ztree['nnum'] >= 5) ? 'yes' : 'no'; ?>-conversion">苹果5个兑换</p>
						</li>
						<li>
							<img src="/src/plantree/img/tree-004.png" />
							<p>现金红包2100元</p>
							<p>数量<var><?php echo isset($ztree_order_red_4) ? $ztree_order_red_4 : 0; ?></var>个</p>
							<p class="apple-<?php echo (isset($ztree['nnum']) && $ztree['nnum'] >= 10) ? 'yes' : 'no'; ?>-conversion">苹果10个兑换</p>
						</li>
						<li>
							<img src="/src/plantree/img/tree-003.png" />
							<p>现金红包3700元</p>
							<p>数量<var><?php echo isset($ztree_order_red_5) ? $ztree_order_red_5 : 0; ?></var>个</p>
							<p class="apple-<?php echo (isset($ztree['nnum']) && $ztree['nnum'] >= 15) ? 'yes' : 'no'; ?>-conversion">苹果15个兑换</p>
						</li>
						<li>
							<img src="/src/plantree/img/tree-002.png" />
							<p>现金红包5800元</p>
							<p>数量<var><?php echo isset($ztree_order_red_6) ? $ztree_order_red_6 : 0; ?></var>个</p>
							<p class="apple-<?php echo (isset($ztree['nnum']) && $ztree['nnum'] >= 20) ? 'yes' : 'no'; ?>-conversion">苹果20个兑换</p>
						</li>
					</ul>
					<div class="tree-main-last" id="ztree_dh_gold">
						<img src="/src/plantree/img/tree-001.png" />
						<p>现金红包8800元</p>
						<p>数量<var><?php echo isset($ztree_order_gold) ? $ztree_order_gold : 0; ?></var>个</p>
						<p class="apple-<?php echo (isset($ztree['ngold']) && $ztree['ngold'] >= 1) ? 'yes' : 'no'; ?>-conversion">金苹果1个兑换</p>
					</div>
				</div>
				<div class="tree-main-operate" id="ztree">
					<?php if(! QUID){ ?>
						<p class="">
							您尚未登录，<a href="/suny/login.html?ret_url=/ztree.html" style="pointer:cursor;">去登录</a>
						</p>
					<?php } ?>
					<p class="tree-operate-take">
						<span class="tree-operate-take-p">
							<?php if(! QUID){ ?>
								<a href="/suny/login.html?ret_url=/ztree.html" style="cursor: pointer;">开始浇水</a>
							<?php } else { ?>
								<?php if(isset($ztree['status']) && $ztree['status'] == 5) { ?>
									<a id="apple_sg" style="cursor: pointer;">开始收割（<var><?php echo $dsg_total ? $dsg_total : 0; ?></var>）</a>
								<?php } else { ?>
									<a href="/invest.html" style="cursor: pointer;">开始浇水</a>
								<?php } ?>
							<?php } ?>
						</span>
					</p>
					<p class="" id="apple_num">
						您当前拥有的红苹果（<var><?php echo isset($ztree['nnum']) ? $ztree['nnum'] : 0; ?></var>）金苹果（<var><?php echo isset($ztree['ngold']) ? $ztree['ngold'] : 0; ?></var>）| <strong><a href="/ztree.html?rel=<?php echo time();?>#ztree">刷新试试</a></strong>
					</p>
				</div>
				<div class="trees-fruit">
					<div class="trees-fruit-1" style="display: none;">
					</div>
					<div class="trees-fruit-<?php echo isset($ztree['status']) ? ($ztree['status'] - 1) : 0; ?>" id="unused_apple_html">
						<ul>
							
							<?php if(isset($ztree_detail)) { foreach($ztree_detail as $k=>$v) { ?>
								<li class="trees-fruit-li-<?php echo ($k+1); ?> <?php echo $v['type'] ? 'apple-golden' : 'apple-red'; ?>"></li>
							<?php } }?>
						</ul>
					</div>
					<div class="basket-0-0" id="tree-basket"></div>
					<div class="dog-banner"></div>
				</div>
			</div>
		</div>




		<div class="foot">
			<div class="foot1">
				<div class="foot1_c">
					<div class="foot_nav">
						<h3> <img src="/images/new/img/hone.png" class="icon1" style="margin-right:5px;"> 关于伽满优</h3>
						<ul>
							<li><a href="/message.html">关于我们</a></li>
							<li><a href="/message/structure.html">组织信息</a></li>
							<li><a href="/message/newest_notice.html">最新公告</a></li>
						</ul>
					</div>
					<div class="foot_nav">
						<h3><img src="/images/new/img/an.png" class="icon1">平台信息</h3>
						<ul>
							<li><a href="/message/payments.html">回款公告</a></li>
							<li><a href="/message/process.html">公司历程</a></li>
						</ul>
					</div>
					<div class="foot_nav">
						<h3><img src="/images/new/img/wen.png" class="icon1">帮助中心</h3>
						<ul>
							<li><a href="/message/novice.html">常见问题</a></li>
							<li><a href="/message/contact.html">联系我们</a></li>
						</ul>
					</div>
					<div class="lines"></div>
					<div class="foot_nav">
						<img src="/images/new/img/wx2.jpg" width="88" height="88" class='img' />
						<h5 style="margin-top:5px; margin-left:10px;">IOS App下载</h5>
					</div>
					<div class="foot_nav">
						<img src="/images/new/img/wx1.jpg" width="88" height="88" class='img' />
						<h5 style="margin-top:5px; margin-left:10px;">安卓App下载</h5>
					</div>
					<div class="lines"></div>
					<div class="foot_nav">
						<h3>联系方式</h3>
						<P><img src="/images/new/img/email.jpg" class="icon">邮箱：service@jiamanu.com</P>
						<P><img src="/images/new/img/tel.jpg" class="icon">电话：021-62127903（客服工作时间：09:00-18:00）</P>
						<P><img src="/images/new/img/addr.jpg" class="icon">上海市南京西路1468号 中欣大厦2101</P>
					</div>
				</div>
			</div>
		</div>
		<div class="foot2">
			<div class="foot_bq">
				<div class="tz_tb">
					<ul class="companyinfo">
						<li><a target="_blank" href="http://www.miibeian.gov.cn">
								&nbsp;沪ICP备17038560号-1 　</a> 版权所有：上海童汇信息科技有限公司</li>
						<li>温馨提示：市场有风险，出借需谨慎。网络出借不等于存款，请合理选择出借项目，最终收益以实际为准</li>
					</ul>
					<ul class="companylogos">
						<li><a href="http://shuidi.cn/company_extreme_72484988042801684312807540045555.html" target='_blank'>
								<img src="/images/aaa.png" />
							</a></li>
						<li><a href="http://shuidi.cn/company_extreme_72484988042801684312807540045555.html" target='_blank'>
								<img src="/images/lixin.png" />
							</a></li>
						<li><a id="_pingansec_bottomimagesmall_p2p" href="http://si.trustutn.org/info?sn=842170927000609685434&certType=4">
								<img src="http://v.trustutn.org/images/cert/p2p_official_small.jpg" style="height: 47px;" />
							</a></li>
						<li><a key="59e59a8f0c90967a9453b596" logo_size="124x47" logo_type="business" href="https://v.pinpaibao.com.cn/cert/site/?site=www.jiamanu.com&at=business"><img
								 src="/images/hy_124x47.png" /></a></li>
						<li><a id='___szfw_logo___' href='https://credit.szfw.org/CX20171106036688891688.html' target='_blank'><img src='http://icon.szfw.org/cert.png'
								 border='0' style="height: 47px;" /></a></li>
						<script type='text/javascript'>
							(function() {
								document.getElementById('___szfw_logo___').oncontextmenu = function() {
									return false;
								}
							})();
						</script>
						<li><a id="kx_verify"></a></li>
						<script type="text/javascript">
							(function() {
								var _kxs = document.createElement('script');
								_kxs.id = 'kx_script';
								_kxs.async = true;
								_kxs.setAttribute('cid', 'kx_verify');
								_kxs.src = ('https:' == document.location.protocol ? 'https://ss.knet.cn' : 'http://rr.knet.cn') +
									'/static/js/icon3.js?sn=e17110631010669455yk5z000000&tp=icon3';
								_kxs.setAttribute('size', 0);
								var _kx = document.getElementById('kx_verify');
								_kx.parentNode.insertBefore(_kxs, _kx);
							})();
						</script>

					</ul>
				</div>
			</div>
		</div>

	</body>
	<script type="text/javascript" src="/src/plantree/js/jquery-1.7.2.min.js"></script>
	<script type="text/javascript" src="/src/plantree/js/jquery.fly.min.js"></script>
	<script type="text/javascript" src="/pintu/js/requestAnimationFrame.js"></script>
	<script>
		$(function() {
			$('.apple-yes-conversion').die().live('click', function() {
				var exchange_obj = $(this);
				var exchange_num = parseInt($(this).text().replace(/[^0-9]/ig,""));
				var exchange_type = $(this).parent('#ztree_dh_gold').length;
				if(confirm('你确定要兑换吗？')) {
					$.post('/ztree/apple_exchange', {num: exchange_num, 'type':exchange_type}, function(r) {
						if(r.state == 1) {
							// 请求成功
							if(exchange_type == 1) {
								var apple_gold_num = $('#apple_num var:last').html();
								var apple_gold_total = apple_gold_num*1 - exchange_num*1;
								$('#apple_num var:last').html(apple_gold_total);
								
								if(apple_gold_total < 1 && $('#ztree_dh_gold p:last').attr('class') == 'apple-yes-conversion') {
									$('#ztree_dh_gold p:last').removeClass('apple-yes-conversion');
									$('#ztree_dh_gold p:last').addClass('apple-no-conversion');
								}
							} else {
								var apple_red_num = $('#apple_num var:first').html();
								var apple_red_total = apple_red_num*1 - exchange_num*1;
								$('#apple_num var:first').html(apple_red_total);
								
								$("#ztree_dh ul > li").each(function(i) {
									var dh_obj = $(this).find('p:last');
									var dh_num = parseInt(dh_obj.text().replace(/[^0-9]/ig,""));
									if(apple_red_total < dh_num && dh_obj.attr('class') == 'apple-yes-conversion') {
										dh_obj.removeClass('apple-yes-conversion');
										dh_obj.addClass('apple-no-conversion');
									}
								});
							}
							
							var prev_obj = exchange_obj.prev().find('var');
							var prev_num = prev_obj.html();
							prev_obj.html(prev_num*1 + 1);
						}
					}, 'json');
				}
			});
			$("#apple_sg").die().live('click', function(event){
				$("#apple_sg").css('pointer-events',"none");
				var url = '/ztree/tree_sg';
				$.post(url, {}, function(r) {
					if(r.state == 1) {
						// 处理成功
						var apple_num = r.message;
						var apple_red_num = $('#apple_num var:first').html();
						var apple_red_total = apple_red_num*1 + apple_num.red_apple*1;
						$('#apple_num var:first').html(apple_red_total);
						var apple_gold_num = $('#apple_num var:last').html();
						var apple_gold_total = apple_gold_num*1 + apple_num.gold_apple*1;
						$('#apple_num var:last').html(apple_gold_total);
						
						// 待收割数量
						var dsg_num = $('#apple_sg').find('var').html();
						if(dsg_num >= (apple_num.red_apple*1 + apple_num.gold_apple*1)) {
							dsg_num = dsg_num - (apple_num.red_apple*1 + apple_num.gold_apple*1);
						} else {
							dsg_num = 0;
						}
						$('#apple_sg').find('var').html(dsg_num);
						// 兑奖部分
						$("#ztree_dh ul > li").each(function(i) {
							var dh_obj = $(this).find('p:last');
							var dh_num = parseInt(dh_obj.text().replace(/[^0-9]/ig,""));
							if(apple_red_total >= dh_num && dh_obj.attr('class') == 'apple-no-conversion') {
								dh_obj.removeClass('apple-no-conversion');
								dh_obj.addClass('apple-yes-conversion');
							}
						});
						if(apple_gold_total >= 1 && $('#ztree_dh_gold p:last').attr('class') == 'apple-no-conversion') {
							$('#ztree_dh_gold p:last').removeClass('apple-no-conversion');
							$('#ztree_dh_gold p:last').addClass('apple-yes-conversion');
						}
						
						
						var end_rect = document.querySelector("#tree-basket").getBoundingClientRect(); 	   // 结束位置 屏幕的相对坐标
				
						var cars = $(".trees-fruit-4 ul > li").size();
						if(cars == 0){
							alert("还未结成果实！");
						} else {
							$(".trees-fruit-4 ul > li").each(function(each_i){					 
								var arr  = $(this).attr("class").split(' ');
								var start_rect = document.querySelector("."+arr[0]).getBoundingClientRect(); //起始位置 屏幕的相对坐标
								var img = "/src/plantree/img/"+arr[1]+".png";				
								var flyer = $('<img class="u-flyers" src="'+img+'">');
								
								flyer.fly({
									start: {
										left: start_rect.left,
										top: start_rect.top,
									},
									end: {
										left: end_rect.left+60,
										top: end_rect.top +80,
										width: 0,
										height: 0
									},
									onEnd: function(){
										$("."+arr[1]).remove();
										if(cars == each_i + 1) {
											$.post('/ztree/apple_unused', {}, function(r) {
												// 重新获取剩余的苹果
												if(r.state == 1) {
													var unused_apple = r.message;
													// 如果有剩余的苹果
													if(unused_apple != '0') {
														$('#unused_apple_html ul').html('');
														var apple_html = '';
														$.each(unused_apple, function(i, n) {
															var apple_color = (n.type == 1) ? 'apple-golden' : 'apple-red';
															apple_html += '<li class="trees-fruit-li-' + (i*1+1) + ' ' + apple_color + '"></li>';
														});
														$('#unused_apple_html ul').html(apple_html);
														//console.log($('#unused_apple_html ul').html());
													}
												}
											}, 'json');
										}
									},
								});
									
							});	
						}
						
						if(cars > 0) {
							// 处理篮子显示
							var basket_class = $('#tree-basket').attr('class');
							$('#tree-basket').removeClass(basket_class);
							if(apple_num.gold_apple > 0) {
								if(apple_num.red_apple <= 5) {
									$('#tree-basket').addClass('basket-0-5-golden');
								} else if(apple_num.red_apple <= 10) {
									$('#tree-basket').addClass('basket-5-10-golden');
								} else if(apple_num.red_apple <= 20) {
									$('#tree-basket').addClass('basket-10-golden');
								}
							} else {
								if(apple_num.red_apple <= 5) {
									$('#tree-basket').addClass('basket-0-5-red');
								} else if(apple_num.red_apple <= 10) {
									$('#tree-basket').addClass('basket-5-10-red');
								} else if(apple_num.red_apple <= 20) {
									$('#tree-basket').addClass('basket-10-red');
								}
							}
						}
						
						
						
					} else {
						// 处理失败
					}
					$("#apple_sg").css('pointer-events',"auto");
				}, 'json');
				
				
																
			});
		});
	</script>
</html>
