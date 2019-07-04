<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>伽满优-福袋</title>
		<link rel="stylesheet" href="/src/plantrees/css/trees.css" type="text/css" />
		<link rel="stylesheet" href="/src/plantrees/css/simpleAlert.css">

		<link href="https://www.jiamanu.com/images/new/index.css" rel="stylesheet" type="text/css" />
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
						<a class="menu-hd" href="/suny/login.html?ret_url=/ztrees.html"><i class="icon"></i>登陆<b class="icon"></b></a>
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
				<img src="/src/plantrees/img/banner.jpg">
			</div>
			<div class="tree-main">
				<div class="tree-main-rule">
					<img src="/src/plantrees/img/rule.png">
				</div>



				<div class="tree-main-prize" id="ztree_dh">
					<!-- 转移上面开始 -->
					<div class="tree-main-last">
						<img src="/src/plantrees/img/tree-001.png" />
						<p>现金红包8,800元</p>
						<p>数量<var><?php echo isset($ztrees_order_gold) ? $ztrees_order_gold : 0; ?></var>个</p>
						<p class="apple-<?php echo (isset($ztrees['ngold']) && $ztrees['ngold'] >= 1) ? 'yes' : 'no'; ?>-conversion">金苹果1个兑换</p>
					</div>
					<!-- 转移上面结束 -->
					<ul>
						<li>
							<img src="/src/plantrees/img/packet-three.png" />
							<p>现金红包5,800元</p>
							<p>数量<var><?php echo isset($ztrees_order_red_6) ? $ztrees_order_red_6 : 0; ?></var>个</p>
							<p class="apple-<?php echo (isset($ztrees['nnum']) && $ztrees['nnum'] >= 20) ? 'yes' : 'no'; ?>-conversion">苹果20个兑换</p>
						</li>
						<li>
							<img src="/src/plantrees/img/packet-two.png" />
							<p>现金红包3,700元</p>
							<p>数量<var><?php echo isset($ztrees_order_red_5) ? $ztrees_order_red_5 : 0; ?></var>个</p>
							<p class="apple-<?php echo (isset($ztrees['nnum']) && $ztrees['nnum'] >= 15) ? 'yes' : 'no'; ?>-conversion">苹果15个兑换</p>
						</li>
						<li>
							<img src="/src/plantrees/img/packet-one-big.png" />
							<p>现金红包2,100元</p>
							<p>数量<var><?php echo isset($ztrees_order_red_4) ? $ztrees_order_red_4 : 0; ?></var>个</p>
							<p class="apple-<?php echo (isset($ztrees['nnum']) && $ztrees['nnum'] >= 10) ? 'yes' : 'no'; ?>-conversion">苹果10个兑换</p>
						</li>
						<li>
							<img src="/src/plantrees/img/packet-one.png" />
							<p>现金红包900元</p>
							<p>数量<var><?php echo isset($ztrees_order_red_3) ? $ztrees_order_red_3 : 0; ?></var>个</p>
							<p class="apple-<?php echo (isset($ztrees['nnum']) && $ztrees['nnum'] >= 5) ? 'yes' : 'no'; ?>-conversion">苹果5个兑换</p>
						</li>
						<li>
							<img src="/src/plantrees/img/packet-mandarin.png" />
							<p>现金红包300元</p>
							<p>数量<var><?php echo isset($ztrees_order_red_2) ? $ztrees_order_red_2 : 0; ?></var>个</p>
							<p class="apple-<?php echo (isset($ztrees['nnum']) && $ztrees['nnum'] >= 2) ? 'yes' : 'no'; ?>-conversion">苹果2个兑换</p>
						</li>
						<li>
							<img src="/src/plantrees/img/packet-mandarin.png" />
							<p>现金红包128元</p>
							<p>数量<var><?php echo isset($ztrees_order_red_1) ? $ztrees_order_red_1 : 0; ?></var>个</p>
							<p class="apple-<?php echo (isset($ztrees['nnum']) && $ztrees['nnum'] >= 1) ? 'yes' : 'no'; ?>-conversion">苹果1个兑换</p>
						</li>
						<li>
							<img src="/src/plantrees/img/packet-mandarin.png" />
							<p>现金红包200元</p>
							<p>数量<var><?php echo isset($ztrees_order_red_8) ? $ztrees_order_red_8 : 0; ?></var>个</p>
							<p class="apple-<?php echo (isset($pear_num) && isset($ztrees_order_red_8) && ($pear_num - $ztrees_order_red_8) > 0) ? 'yes' : 'no'; ?>-conversion">梨1个兑换</p>
						</li>
						<li>
							<img src="/src/plantrees/img/packet-mandarin.png" />
							<p>现金红包220元</p>
							<p>数量<var><?php echo isset($ztrees_order_red_9) ? $ztrees_order_red_9 : 0; ?></var>个</p>
							<p class="apple-<?php echo (isset($orange_num) && isset($ztrees_order_red_9) && ($orange_num - $ztrees_order_red_9) > 0) ? 'yes' : 'no'; ?>-conversion">橘子1个兑换</p>
						</li>
						<li>
							<img src="/src/plantrees/img/packet-mandarin.png" />
							<p>现金红包240元</p>
							<p>数量<var><?php echo isset($ztrees_order_red_10) ? $ztrees_order_red_10 : 0; ?></var>个</p>
							<p class="apple-<?php echo (isset($pitaya_num) && isset($ztrees_order_red_10) && ($pitaya_num - $ztrees_order_red_10) > 0) ? 'yes' : 'no'; ?>-conversion">火龙果1个兑换</p>
						</li>
						<li>
							<img src="/src/plantrees/img/packet-mandarin.png" />
							<p>现金红包260元</p>
							<p>数量<var><?php echo isset($ztrees_order_red_11) ? $ztrees_order_red_11 : 0; ?></var>个</p>
							<p class="apple-<?php echo (isset($grape_num) && isset($ztrees_order_red_11) && ($grape_num - $ztrees_order_red_11) > 0) ? 'yes' : 'no'; ?>-conversion">葡萄1串兑换</p>
						</li>
						<li>
							<img src="/src/plantrees/img/packet-mandarin.png" />
							<p>现金红包280元</p>
							<p>数量<var><?php echo isset($ztrees_order_red_12) ? $ztrees_order_red_12 : 0; ?></var>个</p>
							<p class="apple-<?php echo (isset($peach_num) && isset($ztrees_order_red_12) && ($peach_num - $ztrees_order_red_12) > 0) ? 'yes' : 'no'; ?>-conversion">桃子1个兑换</p>
						</li>
						<li>
							<img src="/src/plantrees/img/packet-mandarin.png" />
							<p>出借红包388元</p>
							<p>数量<var><?php echo isset($ztrees_order_red_13) ? $ztrees_order_red_13 : 0; ?></var>个</p>
							<p class="apple-<?php echo (isset($packe_num) && isset($ztrees_order_red_13) && ($packe_num - $ztrees_order_red_13) > 0) ? 'yes' : 'no'; ?>-conversion">出借红包1份兑换</p>
						</li>
					</ul>

				</div>
				<div class="tree-main-operate" id="ztrees">
					<?php if(! QUID){ ?>
						<p class="">
							您尚未登录，<a href="/suny/login.html?ret_url=/ztrees.html" style="pointer:cursor;">去登录</a>
						</p>
					<?php } ?>
					<p class="tree-operate-take">
						<span class="tree-operate-take-p">
							<?php if(! QUID){ ?>
								<a href="/suny/login.html?ret_url=/ztrees.html" style="cursor: pointer;">开始浇水</a>
							<?php } else { ?>
								<?php if(isset($ztrees['status']) && $ztrees['status'] == 5) { ?>
									<a id="apple_sg" style="cursor: pointer;">开始收割（<var><?php echo $dsg_total ? $dsg_total : 0; ?></var>）</a>
								<?php } else { ?>
									<a href="/invest.html" style="cursor: pointer;">开始浇水</a>
								<?php } ?>
							<?php } ?>
						</span>
					</p>
					<div class="tree-fruit-list">
						<label>您当前拥有：</label>
						<span id="apple-red">（<var><?php echo (isset($red_num) && isset($red_order_total) && (($red_num - $red_order_total) > 0)) ? ($red_num - $red_order_total) : 0; ?></var>）、</span>
						<span id="pear">（<var><?php echo (isset($pear_num) && isset($ztrees_order_red_8) && ($pear_num - $ztrees_order_red_8) > 0) ? ($pear_num - $ztrees_order_red_8) : 0; ?></var>）、</span>
						<span id="orange">（<var><?php echo (isset($orange_num) && isset($ztrees_order_red_9) && ($orange_num - $ztrees_order_red_9) > 0) ? ($orange_num - $ztrees_order_red_9) : 0; ?></var></var>）、</span>
						<span id="pitaya">（<var><?php echo (isset($pitaya_num) && isset($ztrees_order_red_10) && ($pitaya_num - $ztrees_order_red_10) > 0) ? ($pitaya_num - $ztrees_order_red_10) : 0; ?></var>）、</span>
						<span id="grape">（<var><?php echo (isset($grape_num) && isset($ztrees_order_red_11) && ($grape_num - $ztrees_order_red_11) > 0) ? ($grape_num - $ztrees_order_red_11) : 0; ?></var>）、</span>
						<span id="peach">（<var><?php echo (isset($peach_num) && isset($ztrees_order_red_12) && ($peach_num - $ztrees_order_red_12) > 0) ? ($peach_num - $ztrees_order_red_12) : 0; ?></var>）、</span>
						<span id="red-packet">（<var><?php echo (isset($packe_num) && isset($ztrees_order_red_13) && ($packe_num - $ztrees_order_red_13) > 0) ? ($packe_num - $ztrees_order_red_13) : 0; ?></var>）、</span>
						<span id="apple-golden">（<var><?php echo isset($ztrees['ngold']) ? $ztrees['ngold'] : 0; ?></var>）|</span>
						<span id="tree-refresh"><a href="/ztrees.html?rel=<?php echo time();?>#ztrees">刷新试试</a></span>
					</div>
				</div>

				<div class="trees-fruit">
					<div class="trees-fruit-1" style="display: none;">
					</div>
					<div class="trees-fruit-4" id="unused_apple_html">
						<ul>
							<?php if(isset($ztrees_detail) && !empty($ztrees_detail)) { foreach($ztrees_detail as $k=>$v) { ?>
								<li id="trees-fruit-li-<?php echo ($k+1); ?>" class="trees-fruit-li-<?php echo ($k+1); ?> <?php switch($v['type']) {
										case '1':
											echo 'apple-golden';
										break;
										case '8':
											echo 'pear';
										break;
										case '9':
											echo 'orange';
										break;
										case '10':
											echo 'pitaya';
										break;
										case '11':
											echo 'grape';
										break;
										case '12':
											echo 'peach';
										break;
										case '13':
											echo 'red-packet';
										break;
										case '15':
											echo 'cute-gold';
										break;
										default:
											echo 'apple-red';
									} ?>"></li>
							<?php } }?>
						</ul>
					</div>
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

	<script type="text/javascript" src="/src/plantrees/js/jquery-1.7.2.min.js"></script>
	<script type="text/javascript" src="/src/plantrees/js/jquery.fly.min.js"></script>
	<script type="text/javascript" src="/pintu/js/requestAnimationFrame.js"></script>
	<script>
		var ztrees = ztrees || {};
		ztrees.fly_num = 0;
		$(function() {
			$('.apple-yes-conversion').die().live('click', function() {
				var exchange_obj = $(this);
				var exchange_num = parseInt($(this).parent().find('p:first').text().replace(/[^0-9]/ig,""));
				var exchange_type = 0;
				switch(exchange_num) {
					case 8800:
						exchange_type = 7;break;
					case 5800:
						exchange_type = 6;break;
					case 3700:
						exchange_type = 5;break;
					case 2100:
						exchange_type = 4;break;
					case 900:
						exchange_type = 3;break;
					case 300:
						exchange_type = 2;break;
					case 128:
						exchange_type = 1;break;
					case 200:
						exchange_type = 8;break;
					case 220:
						exchange_type = 9;break;
					case 240:
						exchange_type = 10;break;
					case 260:
						exchange_type = 11;break;
					case 280:
						exchange_type = 12;break;
					case 388:
						exchange_type = 13;break;
					default:
						exchange_type = 0;
				}
				if(confirm('你确定要兑换吗？')) {
					$.post('/ztrees/apple_exchange', {'type':exchange_type}, function(r) {
						if(r.state == 1) {
							// 请求成功
							var prev_obj = exchange_obj.prev().find('var');
							var prev_num = prev_obj.html();
							prev_obj.html(prev_num*1 + 1);
							var dh_num = 0;
							switch(exchange_type) {
								case 1:
									var fruit_obj = $('#apple-red var');dh_num = 1; break;
								case 2:
									var fruit_obj = $('#apple-red var');dh_num = 2; break;
								case 3:
									var fruit_obj = $('#apple-red var');dh_num = 5; break;
								case 4:
									var fruit_obj = $('#apple-red var');dh_num = 10; break;
								case 5:
									var fruit_obj = $('#apple-red var');dh_num = 15; break;
								case 6:
									var fruit_obj = $('#apple-red var');dh_num = 20; break;
								break;
								case 7:
									var fruit_obj = $('#apple-golden var'); dh_num=1; break;
								case 8:
									var fruit_obj = $('#pear var'); dh_num=1; break;
								case 9:
									var fruit_obj = $('#orange var'); dh_num=1; break;
								case 10:
									var fruit_obj = $('#pitaya var'); dh_num=1; break;
								case 11:
									var fruit_obj = $('#grape var'); dh_num=1; break;
								case 12:
									var fruit_obj = $('#peach var'); dh_num=1; break;
								case 13:
									var fruit_obj = $('#red-packet var'); dh_num=1; break;
								default:
									var fruit_obj = 0;
							}
							if(fruit_obj) {
								var fruit_num = fruit_obj.html();
								var fruit_num_total = fruit_num*1 - dh_num*1;
								fruit_obj.html(fruit_num_total);
							}
							
							if(exchange_type == 7 || exchange_type == 8 || exchange_type == 9 || exchange_type == 10 || exchange_type == 11 || exchange_type == 12 || exchange_type == 13) {
								if(fruit_num_total < 1 && exchange_obj.attr('class') == 'apple-yes-conversion') {
									exchange_obj.removeClass('apple-yes-conversion');
									exchange_obj.addClass('apple-no-conversion');
								}
							} else {
								$("#ztree_dh ul > li").each(function(i) {
									var current_obj = $(this).find('p:last');
									var current_num = parseInt(current_obj.text().replace(/[^0-9]/ig,""));
									if(fruit_num_total < current_num && current_obj.attr('class') == 'apple-yes-conversion') {
										current_obj.removeClass('apple-yes-conversion');
										current_obj.addClass('apple-no-conversion');
									}
								});
							}
						} else {
							alert(r.message);
							location.reload();
						}
					}, 'json');
				}
			});
			$("#apple_sg").die().live('click', function(event){
				$("#apple_sg").css('pointer-events',"none");
				var url = '/ztrees/tree_sg';
				$.post(url, {}, function(r) {
					if(r.state == 1) {
						//console.log(r.message);
						// 处理成功
						var fruit_data = r.message;
						var red_num = fruit_data.red_apple;
						var red_num_total = $('#apple-red var').html()*1 + red_num*1;
						$('#apple-red var').html(red_num_total);
						var pear_num = fruit_data.pear;
						var pear_num_total = $('#pear var').html()*1 + pear_num*1;
						$('#pear var').html(pear_num_total);
						var orange_num = fruit_data.orange;
						var orange_num_total = $('#orange var').html()*1 + orange_num*1;
						$('#orange var').html(orange_num_total);
						var pitaya_num = fruit_data.pitaya;
						var pitaya_num_total = $('#pitaya var').html()*1 + pitaya_num*1;
						$('#pitaya var').html(pitaya_num_total);
						var grape_num = fruit_data.grape;
						var grape_num_toal = $('#grape var').html()*1 + grape_num*1;
						$('#grape var').html(grape_num_toal);
						var peach_num = fruit_data.peach;
						var peach_num_total = $('#peach var').html()*1 + peach_num*1;
						$('#peach var').html(peach_num_total);
						var packet_num = fruit_data.packet;
						var packet_num_total = $('#red-packet var').html()*1 + packet_num*1;
						$('#red-packet var').html(packet_num_total);
						var golden_num = fruit_data.gold_apple;
						var golden_num_total = $('#apple-golden var').html()*1 + golden_num*1;
						$('#apple-golden var').html(golden_num_total);
						
						// 待收割数量
						var dsg_num = $('#apple_sg').find('var').html();
						var fruit_total = red_num*1 + pear_num*1 + orange_num*1 + pitaya_num*1 + grape_num*1 + peach_num*1 + packet_num*1 + golden_num*1;
						if(dsg_num >= fruit_total) {
							dsg_num = dsg_num - fruit_total*1;
						} else {
							dsg_num = 0;
						}
						$('#apple_sg').find('var').html(dsg_num);
						// 兑奖部分
						$("#ztree_dh ul > li").each(function(i) {
							var dh_money = parseInt($(this).find('p:first').text().replace(/[^0-9]/ig,""));
							var dh_obj = $(this).find('p:last');
							var dh_num = parseInt(dh_obj.text().replace(/[^0-9]/ig,""));
							//console.log(dh_money);
							//console.log(dh_num);
							switch(dh_money) {
								case 5800:
								case 3700:
								case 2100:
								case 900:
								case 300:
								case 128:
									if(red_num_total >= dh_num && dh_obj.attr('class') == 'apple-no-conversion') {
										dh_obj.removeClass('apple-no-conversion');
										dh_obj.addClass('apple-yes-conversion');
									}
								break;
								case 200:
									if(pear_num_total >= dh_num && dh_obj.attr('class') == 'apple-no-conversion') {
										dh_obj.removeClass('apple-no-conversion');
										dh_obj.addClass('apple-yes-conversion');
									}
								break;
								case 220:
									if(orange_num_total >= dh_num && dh_obj.attr('class') == 'apple-no-conversion') {
										dh_obj.removeClass('apple-no-conversion');
										dh_obj.addClass('apple-yes-conversion');
									}
								break;
								case 240:
									if(pitaya_num_total >= dh_num && dh_obj.attr('class') == 'apple-no-conversion') {
										dh_obj.removeClass('apple-no-conversion');
										dh_obj.addClass('apple-yes-conversion');
									}
								break;
								case 260:
									if(grape_num_toal >= dh_num && dh_obj.attr('class') == 'apple-no-conversion') {
										dh_obj.removeClass('apple-no-conversion');
										dh_obj.addClass('apple-yes-conversion');
									}
								break;
								case 280:
									if(peach_num_total >= dh_num && dh_obj.attr('class') == 'apple-no-conversion') {
										dh_obj.removeClass('apple-no-conversion');
										dh_obj.addClass('apple-yes-conversion');
									}
								break;
								case 388:
									if(packet_num_total >= dh_num && dh_obj.attr('class') == 'apple-no-conversion') {
										dh_obj.removeClass('apple-no-conversion');
										dh_obj.addClass('apple-yes-conversion');
									}
								break;
									
							}
						});
						if(golden_num_total >= 1 && $('.tree-main-last p:last').attr('class') == 'apple-no-conversion') {
							$('.tree-main-last p:last').removeClass('apple-no-conversion');
							$('.tree-main-last p:last').addClass('apple-yes-conversion');
						}
						var substr = 'apple';
						var cars = $(".trees-fruit-4 ul > li").size();
						if (cars == 0) {
							alert("还未结成果实！");
							return false;
						}
						$(".trees-fruit-4 ul > li").each(function() {
							var arr = $(this).attr("class").split(' ');
							if(arr[1] == 'cute-gold'){
								arr[1] = 'apple-golden';
							}
							var start_rect = document.querySelector("." + arr[0]).getBoundingClientRect(); //起始位置 屏幕的相对坐标
							var end_rect = document.querySelector("#" + arr[1]).getBoundingClientRect(); // 结束位置 屏幕的相对坐标

							var img = "/src/plantrees/img/" + arr[1] + ".png";
							var flyer = $('<img class="u-flyers" src="' + img + '">');
							flyer.fly({
								start: {
									left: start_rect.left,
									top: start_rect.top,
								},
								end: {
									left: end_rect.left,
									top: end_rect.top,
									width: 0,
									height: 0
								},
								speed: 1.5, //越大越快，默认1.2 
								onEnd: function() {
									ztrees.fly_num++;
									if(arr[1] == 'apple-golden'){
										arr[1] = 'cute-gold';
									}
									$("." + arr[1]).remove();
									if(ztrees.fly_num != 0 && ztrees.fly_num == fruit_total) {
										$.post('/ztrees/apple_unused', function(r) {
											// 重新获取剩余的苹果
											if(r.state == 1) {
												// 如果有剩余的苹果
												if(r.message != '0') {
													$('#unused_apple_html ul').html(r.message);
													ztrees.fly_num = 0;
												}
											}
										}, 'json');
									}
								}
								
							});

						});
					} else {
						// 处理失败
					}
				}, 'json');
				$("#apple_sg").css('pointer-events',"auto");
			});
		});
	</script>
</html>
