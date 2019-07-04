<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>伽满优-福袋</title>
		<link rel="stylesheet" href="/lucky/css/lucky.css" type="text/css" />
		 <link rel="stylesheet" href="/lucky/css/simpleAlert.css">		 
		 <link href="/images/new/index.css" rel="stylesheet" type="text/css" />
		<script src="/images/jquery-1.7.2.min.js"></script>		
		<script src="/lucky/js/simpleAlert.js"></script>
		
		<script src="/src/layui.js"></script>
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
							<?php if(! QUID){ ?>
								<a class="menu-hd" href="/suny/login.html?ret_url=/lucky.html"><i class="icon"></i>登录<b class="icon"></b></a>
							<?php }else{?>
								<a class="menu-hd" href="/account.html"><i class="icon"></i>我的账户<b class="icon"></b></a>
								<div class="menu-bd">
									<div class="menu-bd-panel"> 
										<a href="/suny/out.html?ret_url=/lucky.html">退出</a>
									</div>
								</div>
							<?php }?>
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
		
		<div id="lucky-body">
			<div id="lucky-banner">
				<img src="/lucky/img/banner1.jpg" >
			</div>
			<div id="lucky-main">
				<div class="lucky-main-packet">
					<ul>
						<li class="js-58">
							<img src="/lucky/img/packet-58.png" >
							<span>X<var><?php echo $m58; ?></var></span>
						</li>
						<li class="js-88">
							<img src="/lucky/img/packet-88.png" >
							<span>X<var><?php echo $m88; ?></var></span>
						</li>
						<li class="js-188">
							<img src="/lucky/img/packet-188.png" >
							<span>X<var><?php echo $m188; ?></var></span>
						</li>
						<li class="js-288">
							<img src="/lucky/img/packet-288.png" >
							<span>X<var><?php echo $m288; ?></var></span>
						</li>
						<li class="js-588">
							<img src="/lucky/img/packet-588.png" >
							<span>X<var><?php echo $m588; ?></var></span>
						</li>
						<li class="js-888">
							<img src="/lucky/img/packet-888.png" >
							<span>X<var><?php echo $m888; ?></var></span>
						</li>
					</ul>
				</div>
				<div class="lucky-main-home" id="lucky">
					<ul>
						<!--<li><img src="/lucky/img/font-jia.png" ></li>-->
						<li><img src="/lucky/img/fu-bag-open.png" ></li>
						<li><img src="/lucky/img/fu-bag-open.png" ></li>
						<li><img src="/lucky/img/fu-bag-open.png" ></li>
					</ul>
				</div>
				<div class="lucky-main-operate">
					<?php if(!QUID) { ?>
						<p class="">
							您尚未登录，<a href="/suny/login.html?ret_url=/lucky.html">去登录</a>
						</p>
					<?php } else { ?>
						<p class="">
							<a href='/invest.html'>出借赚福袋</a>
						</p>
					<?php  } ?>
					<p class="lucky-operate-take">
						<span class="lucky-operate-take-p" id="begin" style="cursor:pointer;">
							<a>开始抽选</a>
						</span>
					</p>
					<p class="lucky-count">
						<label><input type="radio" name="fudai" value="1" checked/>您当前剩余单倍福袋（<var><?php echo isset($zcash) ? $zcash['total'] : 0; ?></var>）</label> <label><input type="radio" name="fudai" value="2" />您当前剩余双倍福袋（<var><?php echo isset($zcash) ? $zcash['doub'] : 0; ?></var>）</label>| <strong><a href="/lucky.html?rel=<?php echo time(); ?>#lucky">刷新试试</a></strong>
					</p>
				</div>
				<div class="lucky-main-rule">
					<img src="/lucky/img/rule.png" >
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
						<li><a target="_blank"  href="http://www.miibeian.gov.cn">
						&nbsp;沪ICP备17038560号-1 　</a> 版权所有：上海童汇信息科技有限公司</li>
						<li>温馨提示：市场有风险，出借需谨慎。网络出借不等于存款，请合理选择出借项目，最终收益以实际为准</li>
					</ul>
					<ul class="companylogos">
						<li><a href="http://shuidi.cn/company_extreme_72484988042801684312807540045555.html" target='_blank'>
						<img src="/images/aaa.png"/>
						</a></li>
						<li><a href="http://shuidi.cn/company_extreme_72484988042801684312807540045555.html" target='_blank'>
						<img src="/images/lixin.png"/>
						</a></li>
						<li><a id="_pingansec_bottomimagesmall_p2p" href="http://si.trustutn.org/info?sn=842170927000609685434&certType=4">
						<img src="http://v.trustutn.org/images/cert/p2p_official_small.jpg" style="height: 47px;"/>
						</a></li>
						<li><a  key ="59e59a8f0c90967a9453b596"  logo_size="124x47"  logo_type="business"  href="https://v.pinpaibao.com.cn/cert/site/?site=www.jiamanu.com&at=business" ><img src="/images/hy_124x47.png"/></a></li>
						<li><a id='___szfw_logo___' href='https://credit.szfw.org/CX20171106036688891688.html' target='_blank'><img src='http://icon.szfw.org/cert.png' border='0'  style="height: 47px;" /></a></li>
						<script type='text/javascript'>(function(){document.getElementById('___szfw_logo___').oncontextmenu = function(){return false;}})();</script>
						<li><a id="kx_verify"></a></li><script type="text/javascript">(function (){var _kxs = document.createElement('script');_kxs.id = 'kx_script';_kxs.async = true;_kxs.setAttribute('cid', 'kx_verify');_kxs.src = ('https:' == document.location.protocol ? 'https://ss.knet.cn' : 'http://rr.knet.cn')+'/static/js/icon3.js?sn=e17110631010669455yk5z000000&tp=icon3';_kxs.setAttribute('size', 0);var _kx = document.getElementById('kx_verify');_kx.parentNode.insertBefore(_kxs, _kx);})();</script>
		
					</ul>
				</div>
			</div>
		</div>
			
	</body>
	<script>
		layui.use(['layer'], function() {
			var $ = layui.$
			,layer = layui.layer;
			//单次单选弹框
			$(".lucky-main-home li img").each(function(i, n) {
				$(this).on('click', function() {
					// 判断是否登陆
					if(parseInt('<?php echo QUID; ?>') < 1 ) {
						layer.msg('请先登陆', {icon: 5, time: 1500});
						return;
					}
					// 判断抽奖次数是否大于零
					//console.log($('input[name=fudai]:checked').val());
					var obj_fudai = $('input[name=fudai]:checked');
					if(obj_fudai.val() == 1 && parseInt(obj_fudai.parent('label').find('var').html()) < 1) {
						layer.msg('剩余单倍福袋次数不足', {icon: 5, time: 1500});
						return;
					}
					if(obj_fudai.val() == 2 && parseInt(obj_fudai.parent('label').find('var').html()) < 1) {
						layer.msg('剩余双倍福袋次数不足', {icon: 5, time: 1500});
						return;
					}
					/* if($('input[name=fudai]:checked').val() == 1)
					if(parseInt($('.lucky-count var').html()) < 1) {
						
					} */
					var flag = false;
					switch(i) {
						case 0:
							var img_src = $(this).attr('src');
							if(img_src == '/lucky/img/font-jia.png') {
								flag = true;
							} else {
								$(this).attr('src', '/lucky/img/font-jia.png');
							}
						break;
						case 1:
							var img_src = $(this).attr('src');
							if(img_src == '/lucky/img/font-man.png') {
								flag = true;
							} else {
								$(this).attr('src', '/lucky/img/font-man.png');
							}
						break;
						case 2:
							var img_src = $(this).attr('src');
							if(img_src == '/lucky/img/font-you.png') {
								flag = true;
							} else {
								$(this).attr('src', '/lucky/img/font-you.png');
							}
						break;
					}
					if(!flag) {
						$.post('/lucky/get_money.html', {doub:obj_fudai.val()}, function(r) {
							if(r.state == 1) {
								obj_fudai.parent('label').find('var').html(parseInt(obj_fudai.parent('label').find('var').html()) - 1);
								$('.js-'+ r.message + ' var').html(parseInt($('.js-'+ r.message + ' var').html()) + 1);
								var onlyChoseAlert = simpleAlert({
									"content":r.message,
									"buttons":{
										"确定":function () {
											onlyChoseAlert.close();
										}
									}
								});
							} else {
								layer.msg(r.message, {icon: 5, time: 1500});
							}
						}, 'json')
						
					}
					
				});
			}); 
			/* $(".lucky-main-home li img").click(function () {
				$(this).attr('src', '/lucky/img/font-jia.png');
				console.log($(this));
				var onlyChoseAlert = simpleAlert({
					"content":"58",
					"buttons":{
						"确定":function () {
							onlyChoseAlert.close();
						}
					}
				});
			}); */
			$('#begin').click(function() {
				$('#lucky li img').attr('src', '/lucky/img/fu-bag-open.png');
			});
		});
		/* $(function () {
			

		}) */
	</script>
</html>
