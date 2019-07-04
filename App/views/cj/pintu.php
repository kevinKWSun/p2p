<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>拼图抽奖活动-伽满优</title>
		<link href="/images/default.css" rel="stylesheet" type="text/css" />
		<link href="/images/new/index.css" rel="stylesheet" type="text/css" />
		<link rel="stylesheet" href="/pintu/css/index.css" type="text/css" />
		<style type="text/css">
			.foot_nav h3 .icon1 {
				margin-top:-5px;
			}
		</style>
	</head>
	<body>
		<div class="header">
			<div class="top">
				<div class="sn-menu">
		                <a class="menu-hd" href="/account.html"><i class="icon"></i>我的账户<b class="icon"></b></a>
						<div class="menu-bd">
							<div class="menu-bd-panel"> 
								<a href="/suny/out.html">退出</a>
							</div>
						</div>

		        </div>
				<a href="/"><img src="/images/logo.jpg" class="logo" /></a>
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
		<div id="main">
			<div class="header-pintu"></div>
			<div class="common">
				<h3>活动细则</h3>
				<div class="common-content">
					<div class="common-content-text">
						<dl>
							<dt>活动规则：</dt>
							<dd>1、活动期间内，33天产品投资额度满3万元，可选择一块拼图；</dd>
							<dd>2、活动期间内，65天产品投资额度满2万元，可选择一块拼图；</dd>
							<dd>3、活动期间内，97天产品投资额度满1万元，可选择一块拼图。</dd>
							<dt> 注：</dt>
							<dd>1、完成整幅拼图（10块），可兑换一等奖;</dd>
							<dd>2、完成伽满优（www.jiamanu.com）字样（6块）拼图，</dd>
							<dd class="indent-text">可兑换二等奖;</dd>
							<dd>3、完成小满图像（4块）拼图，可兑换三等奖；</dd>
							<dd>4、任意五块拼图碎片可兑换200元投资红包；</dd>
							<dd>5、任意十块拼图碎片可兑换500元投资红包；</dd>
							<dd>6、剩余碎片，每块碎片可兑换6积分；</dd>
							<dd>7、本次活动所产生的实物礼品将在活动结束后T+3个工作日发</dd>
							<dd class="indent-text">放，积分将在活动结束后T+1个工作日发放。</dd>
						</dl>
						<dl>
							<dt>投资红包使用规则:</dt>
							<dd>1、投资红包有效期为15天。</dd>
							<dd>2、投资红包激活要求：</dd>
							<dd class="indent-text">① 投资97天以上产品满30,000元即可使用200元投资红包；</dd>
							<dd class="indent-text">② 投资97天以上产品满50,000元即可使用500元投资红包。</dd>
						</dl>
						<div style="margin-top: 12px;">
							<p>以上活动用户可重复参加，且与平台其他活动同享</p>
							<p>本次活动最终解释权归伽满优平台所有</p>
						</div>
					</div>
				</div>
			</div>
			<div class="height-30"></div>
			<div class="public-shadow" id='prize'>
				<div class="public-header">
					<h3>拼图翻盘</h3>
				</div>
				<div class="height-60"></div>				
				<div class="public-background">
					<div class="public-content">
						<div class="dumping-middle">
							<ul>
								<li><img src="/pintu/img/cutting-01.png" ></li>
								<li><img src="/pintu/img/cutting-02.png" ></li>
								<li><img src="/pintu/img/cutting-03.png" ></li>
								<li><img src="/pintu/img/cutting-04.png" ></li>
								<li><img src="/pintu/img/cutting-05.png" ></li>
								<li><img src="/pintu/img/cutting-06.png" ></li>
								<li><img src="/pintu/img/cutting-07.png" ></li>
								<li><img src="/pintu/img/cutting-08.png" ></li>
								<li><img src="/pintu/img/cutting-09.png" ></li>
								<li><img src="/pintu/img/cutting-10.png" ></li>
							</ul>
						</div>
						<div id='data'></div>
						<div style="height: 160px;"></div>
						<div class="dumping-common-bottom">
							<p class="p1"><?php if(! QUID){?>您尚未登录，<a href="/suny/login.html?ret_url=/pintu.html">去登录</a><?php }else{?><a href='/invest.html'>出借赚抽奖次数</a><?php }?></p>
							<p class="p2"><a href="javascript:;" id="repeat">开始翻盘</a></p>
							<p class="p3"><label>您剩余的抽奖次数（<span><?php echo isset($zcard['total'])?$zcard['total']:0;?></span>）</label>| <a href="/pintu.html?rel=<?php echo time(); ?>#prize">刷新试试</a></p>
						</div>
					</div>					
				</div>				
			</div>		
			<div class="height-30"></div>
			<div class="public-shadow">
				<div class="public-header">
					<h3>已获得拼图</h3>
				</div>
				<div class="height-60"></div>				
				<div class="public-background">
					<div class="public-content">
						<div class="joint-common">
							<div class="joint-common-top">
								<ul class="joint-img-ul">
									<li class="end1"><var>0</var><img rel="1" src="/pintu/img/joint-masking-1.png" ></li>
									<li class="end2"><var>0</var><img rel="2" src="/pintu/img/joint-masking-2.png" ></li>
									<li class="end3"><var>0</var><img rel="3" src="/pintu/img/joint-masking-3.png" ></li>
									<li class="end4"><var>0</var><img rel="4" src="/pintu/img/joint-masking-4.png" ></li>
									<li class="end5"><var>0</var><img rel="5" src="/pintu/img/joint-masking-5.png" ></li>
									<li class="end6"><var>0</var><img rel="6" src="/pintu/img/joint-masking-6.png" ></li>
									<li class="end7"><var>0</var><img rel="7" src="/pintu/img/joint-masking-7.png" ></li>
									<li class="end8"><var>0</var><img rel="8" src="/pintu/img/joint-masking-8.png" ></li>
									<li class="end9"><var>0</var><img rel="9" src="/pintu/img/joint-masking-9.png" ></li>
									<li class="end10"><var>0</var><img rel="10" src="/pintu/img/joint-masking-10.png" ></li>
								</ul>
							</div>							
						</div>
						<div class="joint-common-middle">
							<ul>
								<li class="start1"><img rel="1" src="/pintu/img/cutting-01.png" ><p><var><?php echo isset($zcard['card1'])?$zcard['card1']:0;?></var></p></li>
								<li class="start2"><img rel="2" src="/pintu/img/cutting-02.png" ><p><var><?php echo isset($zcard['card2'])?$zcard['card2']:0;?></var></p></li>
								<li class="start3"><img rel="3" src="/pintu/img/cutting-03.png" ><p><var><?php echo isset($zcard['card3'])?$zcard['card3']:0;?></var></p></li>
								<li class="start4"><img rel="4" src="/pintu/img/cutting-04.png" ><p><var><?php echo isset($zcard['card4'])?$zcard['card4']:0;?></var></p></li>
								<li class="start5"><img rel="5" src="/pintu/img/cutting-05.png" ><p><var><?php echo isset($zcard['card5'])?$zcard['card5']:0;?></var></p></li>
								<li class="start6"><img rel="6" src="/pintu/img/cutting-06.png" ><p><var><?php echo isset($zcard['card6'])?$zcard['card6']:0;?></var></p></li>
								<li class="start7"><img rel="7" src="/pintu/img/cutting-07.png" ><p><var><?php echo isset($zcard['card7'])?$zcard['card7']:0;?></var></p></li>
								<li class="start8"><img rel="8" src="/pintu/img/cutting-08.png" ><p><var><?php echo isset($zcard['card8'])?$zcard['card8']:0;?></var></p></li>
								<li class="start9"><img rel="9" src="/pintu/img/cutting-09.png" ><p><var><?php echo isset($zcard['card9'])?$zcard['card9']:0;?></var></p></li>
								<li class="start10"><img rel="10" src="/pintu/img/cutting-10.png" ><p><var><?php echo isset($zcard['card10'])?$zcard['card10']:0;?></var></p></li>
							</ul>								
						</div>
						<div class="joint-common-bottom">
							<ul>
								<li id='j_1'><span>一等奖</span><a href="javascript:;" class="addcar01" rel="01">兑换</a></li>
								<li id='j_2'><span>二等奖</span><a href="javascript:;" class="addcar02" rel="02">兑换</a></li>
								<li id='j_3'><span>三等奖</span><a href="javascript:;" class="addcar03" rel="03">兑换</a></li>
								<li id='j_4'><span>6积分</span><a href="javascript:;" class="addcar04" rel="04">兑换</a></li>
								<li id='j_5'><span>200红包</span><a href="javascript:;" class="addcar05" rel="05">兑换</a></li>
								<li id='j_6'><span>500红包</span><a href="javascript:;" class="addcar06" rel="06">兑换</a></li>
							</ul>								
						</div>
						<div id='error' style='width:100%;height:30px;color:#f37263;'>
							<center style='display:none;'>ERROR</center>
						</div>
					</div>					
				</div>				
			</div>
			<div class="height-30"></div>
			<div class="public-shadow">
				<div class="public-header">
					<h3>已获得奖品</h3>
				</div>
				<div class="height-60"></div>				
				<div class="public-background">
					<div class="public-content">
						<div class="acquire-middle">
							<ul>
								<li class="ends01"><img src="/pintu/img/acquire-01.png" ><p>数量<var><?php echo isset($order['num1'])?$order['num1']:0;?></var>个</p></li>
								<li class="ends02"><img src="/pintu/img/acquire-02.png" ><p>数量<var><?php echo isset($order['num2'])?$order['num2']:0;?></var>个</p></li>
								<li class="ends03"><img src="/pintu/img/acquire-03.png" ><p>数量<var><?php echo isset($order['num3'])?$order['num3']:0;?></var>个</p></li>
								<li class="ends04"><img src="/pintu/img/acquire-04.png" ><p>数量<var><?php echo isset($order['num4'])?$order['num4']:0;?></var>个</p></li>
								<li class="ends05"><img src="/pintu/img/acquire-05.png" ><p>数量<var><?php echo isset($order['num5'])?$order['num5']:0;?></var>个</p></li>
								<li class="ends06"><img src="/pintu/img/acquire-06.png" ><p>数量<var><?php echo isset($order['num6'])?$order['num6']:0;?></var>个</p></li>
							</ul>
						</div>		
					
				</div>				
			</div>			
		</div></div>
		<div id="msg">已成功加入！</div>
		<script type="text/javascript" src="/fanpai/js/jquery.js"></script>
		<script src="/pintu/js/jquery.fly.min.js"></script>
		<script src="/pintu/js/requestAnimationFrame.js"></script>
		<script type="text/javascript" src="/fanpai/js/jquery-ui-1.7.2.custom.min.js"></script>
		<script type="text/javascript" src="/fanpai/js/jquery.flip.min.js"></script>
		<script>
			if($.browser.msie){     
				if($.browser.version<9){  
					alert("浏览器版本过低，请先升级浏览器");  
				}  
			}  
			$(function() {
				$(".joint-common-middle img").click(function(event){
					var addcar = $(this);
					var rel = addcar.attr('rel');				
					var img = "/pintu/img/joint-color-"+rel+".png";
					var start_rect = document.querySelector(".start"+rel).getBoundingClientRect();
					var end_rect = document.querySelector(".end"+rel).getBoundingClientRect();
					var flyer = $('<img class="u-flyer" src="'+img+'">');
					var xy = $(".end"+rel+" var").text()*1;
					var xys = $(".start"+rel+" var").text()*1;
					if(xys <= 0){
						return false;
					}
					flyer.fly({
						start: {
							left: start_rect.left,
							top: start_rect.top
						},
						end: {
							left: end_rect.left+10,
							top: end_rect.top+10,
							width: 0,
							height: 0
						},
						onEnd: function(){
							//$("#msg").html("已拼图成功");
							//$("#msg").show().animate({width: '250px'}, 200).fadeOut(1000);
							$(".end"+rel+" img").attr('src',img);
							$(".end"+rel+" var").text(xy+1);
							$(".start"+rel+" var").text(xys-1);
							var end1 = $(".end1 var").text()*1;
							var end2 = $(".end2 var").text()*1;
							var end3 = $(".end3 var").text()*1;
							var end4 = $(".end4 var").text()*1;
							var end5 = $(".end5 var").text()*1;
							var end6 = $(".end6 var").text()*1;
							var end7 = $(".end7 var").text()*1;
							var end8 = $(".end8 var").text()*1;
							var end9 = $(".end9 var").text()*1;
							var end10 = $(".end10 var").text()*1;
							var totale = end1+end2+end3+end4+end5+end6+end7+end8+end9+end10;
							if(totale > 0){
								$('#j_4 a').addClass('c');
							}
							if(totale > 4){
								$('#j_5 a').addClass('c');
							}
							if(totale > 9){
								$('#j_6 a').addClass('c');
							}
							if(end1 > 0 && end2 > 0 && end6 > 0 && end7 > 0){
								$('#j_3 a').addClass('c');
							}
							if(end3 > 0 && end4 > 0 && end5 > 0 && end8 > 0 && end9 > 0 && end10 > 0){
								$('#j_2 a').addClass('c');
							}
							if(end1 > 0 && end2 > 0 && end3 > 0 && end4 > 0 && end5 > 0 && end6 > 0 && end7 > 0 && end8 > 0 && end9 > 0 && end10 > 0){
								$('#j_1 a').addClass('c');
							}
							this.destory();
						}
					});
				});
				
				$(".joint-common-bottom a").click(function(event){
					var addcar = $(this);
					var rel = addcar.attr('rel');				
					var img = "/pintu/img/acquire-"+rel+".png";
					var start_rect = document.querySelector(".addcar"+rel).getBoundingClientRect(); //起始位置 屏幕的相对坐标
					var end_rect = document.querySelector(".ends"+rel).getBoundingClientRect(); 	   // 结束位置 屏幕的相对坐标
					var flyer = $('<img class="u-flyers" src="'+img+'">');
					var end1 = $(".end1 var").text()*1;
					var end2 = $(".end2 var").text()*1;
					var end3 = $(".end3 var").text()*1;
					var end4 = $(".end4 var").text()*1;
					var end5 = $(".end5 var").text()*1;
					var end6 = $(".end6 var").text()*1;
					var end7 = $(".end7 var").text()*1;
					var end8 = $(".end8 var").text()*1;
					var end9 = $(".end9 var").text()*1;
					var end10 = $(".end10 var").text()*1;
					var totale = end1+end2+end3+end4+end5+end6+end7+end8+end9+end10;
					if(rel == '05'){
						if(totale%5 != 0){
							$("#error center").html("数量不是5的倍数...");
							$("#error center").show().fadeOut(2000);
							return false;
						}
					}else if(rel == '06'){
						if(totale%10 != 0){
							$("#error center").html("数量不是10的倍数...");
							$("#error center").show().fadeOut(2000);
							return false;
						}
					}
					var $product = {'01':'一等奖', '02':'二等奖', '03':'三等奖', '04':'6积分', '05':'200红包', '06':'500红包'};
					if(confirm('你确定要兑换'+$product[rel]+'吗？')) {
						$.post('/pintu/duijiang', {'end1':end1, 'end2':end2, 'end3':end3, 'end4':end4, 'end5':end5, 'end6':end6, 'end7':end7, 'end8':end8, 'end9':end9, 'end10':end10, 'rel':rel}, function(r) {
							if(r.state == 1) {
								flyer.fly({
									start: {
										left: start_rect.left,
										top: start_rect.top
									},
									end: {
										left: end_rect.left+10,
										top: end_rect.top+20,
										width: 0,
										height: 0
									},
									onEnd: function(){
										//$("#msg").html("已兑换成功");
										//$("#msg").show().animate({width: '250px'}, 200).fadeOut(1000);
										$(".ends"+rel+" img").attr('src',img);
										if(rel == '01'){//1
											var ends01 = $('.ends01 var').text()*1;
											if(end1 > 0 && end2 > 0 && end3 > 0 && end4 > 0 && end5 > 0 && end6 > 0 && end7 > 0 && end8 > 0 && end9 > 0 && end10 > 0){
												$('.ends01 var').text(ends01+1);
												$(".joint-img-ul li").each(function(e){
													var r = $(this).find('img').attr('rel');
													$(this).find('var').text($(".end"+r+" var").text()*1-1);
													if($(this).find('var').text()*1 == 0){
														$(this).find('img').attr('src', '/pintu/img/joint-masking-'+r+'.png');
													}
												});
												var end1_1 = $(".end1 var").text()*1;
												var end2_1 = $(".end2 var").text()*1;
												var end3_1 = $(".end3 var").text()*1;
												var end4_1 = $(".end4 var").text()*1;
												var end5_1 = $(".end5 var").text()*1;
												var end6_1 = $(".end6 var").text()*1;
												var end7_1 = $(".end7 var").text()*1;
												var end8_1 = $(".end8 var").text()*1;
												var end9_1 = $(".end9 var").text()*1;
												var end10_1 = $(".end10 var").text()*1;
												var totale_1 = end1_1+end2_1+end3_1+end4_1+end5_1+end6_1+end7_1+end8_1+end9_1+end10_1;
												$('.joint-common-bottom a').removeClass('c');
												if(totale_1 > 0){
													$('#j_4 a').addClass('c');
												}
												if(totale_1 > 4){
													$('#j_5 a').addClass('c');
												}
												if(totale_1 > 9){
													$('#j_6 a').addClass('c');
												}
												if(end1_1 > 0 && end2_1 > 0 && end6_1 > 0 && end7_1 > 0){
													$('#j_3 a').addClass('c');
												}
												if(end3_1 > 0 && end4_1 > 0 && end5_1 > 0 && end8_1 > 0 && end9_1 > 0 && end10_1 > 0){
													$('#j_2 a').addClass('c');
												}
												if(end1_1 > 0 && end2_1 > 0 && end3_1 > 0 && end4_1 > 0 && end5_1 > 0 && end6_1 > 0 && end7_1 > 0 && end8_1 > 0 && end9_1 > 0 && end10_1 > 0){
													$('#j_1 a').addClass('c');
												}
											}
											
										}else if(rel == '02'){//2
											var ends02 = $('.ends02 var').text()*1;
											if(end3 > 0 && end4 > 0 && end5 > 0 && end8 > 0 && end9 > 0 && end10 > 0){
												$('.ends02 var').text(ends02+1);
												$(".end3 var").text(end3-1);
												$(".end4 var").text(end4-1);
												$(".end5 var").text(end5-1);
												$(".end8 var").text(end8-1);
												$(".end9 var").text(end9-1);
												$(".end10 var").text(end10-1);
												$(".joint-img-ul li").each(function(e){
													var r = $(this).find('img').attr('rel');
													if(r == 3 && (end3-1) == 0){
														$(this).find('img').attr('src', '/pintu/img/joint-masking-'+r+'.png');
													}
													if(r == 4 && (end4-1) == 0){
														$(this).find('img').attr('src', '/pintu/img/joint-masking-'+r+'.png');
													}
													if(r == 5 && (end5-1) == 0){
														$(this).find('img').attr('src', '/pintu/img/joint-masking-'+r+'.png');
													}
													if(r == 8 && (end8-1) == 0){
														$(this).find('img').attr('src', '/pintu/img/joint-masking-'+r+'.png');
													}
													if(r == 9 && (end9-1) == 0){
														$(this).find('img').attr('src', '/pintu/img/joint-masking-'+r+'.png');
													}
													if(r == 10 && (end10-1) == 0){
														$(this).find('img').attr('src', '/pintu/img/joint-masking-'+r+'.png');
													}
												});
												var end1_2 = $(".end1 var").text()*1;
												var end2_2 = $(".end2 var").text()*1;
												var end3_2 = $(".end3 var").text()*1;
												var end4_2 = $(".end4 var").text()*1;
												var end5_2 = $(".end5 var").text()*1;
												var end6_2 = $(".end6 var").text()*1;
												var end7_2 = $(".end7 var").text()*1;
												var end8_2 = $(".end8 var").text()*1;
												var end9_2 = $(".end9 var").text()*1;
												var end10_2 = $(".end10 var").text()*1;
												var totale_2 = end1_2+end2_2+end3_2+end4_2+end5_2+end6_2+end7_2+end8_2+end9_2+end10_2;
												$('.joint-common-bottom a').removeClass('c');
												if(totale_2 > 0){
													$('#j_4 a').addClass('c');
												}
												if(totale_2 > 4){
													$('#j_5 a').addClass('c');
												}
												if(totale_2 > 9){
													$('#j_6 a').addClass('c');
												}
												if(end1_2 > 0 && end2_2 > 0 && end6_2 > 0 && end7_2 > 0){
													$('#j_3 a').addClass('c');
												}
												if(end3_2 > 0 && end4_2 > 0 && end5_2 > 0 && end8_2 > 0 && end9_2 > 0 && end10_2 > 0){
													$('#j_2 a').addClass('c');
												}
												if(end1_2 > 0 && end2_2 > 0 && end3_2 > 0 && end4_2 > 0 && end5_2 > 0 && end6_2 > 0 && end7_2 > 0 && end8_2 > 0 && end9_2 > 0 && end10_2 > 0){
													$('#j_1 a').addClass('c');
												}
											}
										}else if(rel == '03'){//3
											var ends03 = $('.ends03 var').text()*1;
											if(end1 > 0 && end2 > 0 && end6 > 0 && end7 > 0){
												$('.ends03 var').text(ends03+1);
												$(".end1 var").text(end1-1);
												$(".end2 var").text(end2-1);
												$(".end6 var").text(end6-1);
												$(".end7 var").text(end7-1);
												$(".joint-img-ul li").each(function(e){
													var r = $(this).find('img').attr('rel');
													if(r == 1 && (end1-1) == 0){
														$(this).find('img').attr('src', '/pintu/img/joint-masking-'+r+'.png');
													}
													if(r == 2 && (end2-1) == 0){
														$(this).find('img').attr('src', '/pintu/img/joint-masking-'+r+'.png');
													}
													if(r == 6 && (end6-1) == 0){
														$(this).find('img').attr('src', '/pintu/img/joint-masking-'+r+'.png');
													}
													if(r == 7 && (end7-1) == 0){
														$(this).find('img').attr('src', '/pintu/img/joint-masking-'+r+'.png');
													}
												});
												var end1_3 = $(".end1 var").text()*1;
												var end2_3 = $(".end2 var").text()*1;
												var end3_3 = $(".end3 var").text()*1;
												var end4_3 = $(".end4 var").text()*1;
												var end5_3 = $(".end5 var").text()*1;
												var end6_3 = $(".end6 var").text()*1;
												var end7_3 = $(".end7 var").text()*1;
												var end8_3 = $(".end8 var").text()*1;
												var end9_3 = $(".end9 var").text()*1;
												var end10_3 = $(".end10 var").text()*1;
												var totale_3 = end1_3+end2_3+end3_3+end4_3+end5_3+end6_3+end7_3+end8_3+end9_3+end10_3;
												$('.joint-common-bottom a').removeClass('c');
												if(totale_3 > 0){
													$('#j_4 a').addClass('c');
												}
												if(totale_3 > 4){
													$('#j_5 a').addClass('c');
												}
												if(totale_3 > 9){
													$('#j_6 a').addClass('c');
												}
												if(end1_3 > 0 && end2_3 > 0 && end6_3 > 0 && end7_3 > 0){
													$('#j_3 a').addClass('c');
												}
												if(end3_3 > 0 && end4_3 > 0 && end5_3 > 0 && end8_3 > 0 && end9_3 > 0 && end10_3 > 0){
													$('#j_2 a').addClass('c');
												}
												if(end1_3 > 0 && end2_3 > 0 && end3_3 > 0 && end4_3 > 0 && end5_3 > 0 && end6_3 > 0 && end7_3 > 0 && end8_3 > 0 && end9_3 > 0 && end10_3 > 0){
													$('#j_1 a').addClass('c');
												}
											}
										}else if(rel == '04'){//6
											var ends04 = $('.ends04 var').text()*1;
											$('.ends04 var').text(ends04+totale);
											$(".joint-img-ul var").text(0);
											$(".joint-img-ul li").each(function(e){
												var r = $(this).find('img').attr('rel');
												$(this).find('img').attr('src', '/pintu/img/joint-masking-'+r+'.png');
											});
											$('.joint-common-bottom a').removeClass('c');
										}else if(rel == '05'){//200
											var ends05 = $('.ends05 var').text()*1;
											if(totale >= 5){
												var tsw = parseInt(totale/5);
												$('.ends05 var').text(ends05+tsw);
												$(".joint-img-ul var").text(0);
												$(".joint-img-ul li").each(function(e){
													var r = $(this).find('img').attr('rel');
													$(this).find('img').attr('src', '/pintu/img/joint-masking-'+r+'.png');
												});
											}
											$('.joint-common-bottom a').removeClass('c');
										}else if(rel == '06'){//500
											var ends06 = $('.ends06 var').text()*1;
											if(totale >= 10){
												var tsw = parseInt(totale/10);
												$('.ends06 var').text(ends06+tsw);
												$(".joint-img-ul var").text(0);
												$(".joint-img-ul li").each(function(e){
													var r = $(this).find('img').attr('rel');
													$(this).find('img').attr('src', '/pintu/img/joint-masking-'+r+'.png');
												});
											}
											$('.joint-common-bottom a').removeClass('c');
										}
										this.destory();
									}
								});
							} else {
								$("#error center").html(r.message);
								$("#error center").show().fadeOut(2000);
								return;
							}
						}, 'json');
					}
					
				});
				/////反
				$('#repeat').click(function() {
					var check = '<?php echo QUID ? 1 : 0;?>';
					if(check === 0){
						location.href = '/suny/login.html?ret_url=/pintu.html#prize';
					}else{
						$("#prize li img").attr('src','/pintu/img/cover-img.png');
						$("#prize li img").css("cursor", "pointer");
						$("#prize li").find('.active').removeClass('active');
						$("#prize li").removeAttr('style');
						$("#prize li").removeAttr('id');
					}
					$("#repeat").css("pointer-events","none");
				});
				$("#prize li").each(function() {
					var p = $(this);
					p.click(function() {
						$("#prize li").css("pointer-events","none");
						if(p.find('img').attr('src')!='/pintu/img/cover-img.png'){
							return false;
						}
						$.getJSON("/pintu/c.html", function(json) {
							console.log(json);
							var prize = json.yes;
							if(prize.check === 0){
								location.href = '/suny/login.html?ret_url=/pintu.html#prize';
							}else if(prize.check === 1){
								$('.p1').html('抽奖次数不足');
							}else if(prize.check === 2){
								$('.p1').html('<a href="javascript:;">服务器链接超时... >></a>');
							}else if(prize.check === 3){
								$('.p1').html('<a href="javascript:;">活动将于2019年1月1日开始... </a>');
							}else if(prize.check === 5){
								$('.p1').html('<a href="javascript:;">本期活动已结束... </a>');
							}else{
								p.flip({
									direction: 'rl',
									content: prize.img,
									color: '#FFFFFF',
									onEnd: function() {
										//$("#prize li").unbind('click');
										p.find('span').addClass('active');
										p.attr("id", "r");
										viewother(); 
										$("#prize li img").css("cursor", "default");
										var num = $('.joint-common-middle li.start'+prize.id).find('var').text();
										var totles = $('.p3 span').text();
										$('.joint-common-middle li.start'+prize.id).find('var').text(num*1+1);
										$('.p3 span').text(totles*1-1);
										setTimeout(function(){
											$("#repeat").removeAttr('style');
										},1000);
									}
								});
								$("#data").data("nolist", json.no);
							}
						});
					});
				});
				function viewother() {
					var mydata = $("#data").data("nolist");
					var mydata2 = eval(mydata);
					$("#prize li").not($('#r')[0]).each(function(index) {
						var pr = $(this);
						pr.flip({
							direction: 'bt',
							color: 'lightgrey',
							content: mydata2[index],
							onEnd: function() {
								pr.css({"background-color": "#fff"});
							}
						});
					});
					$("#data").removeData("nolist");
				}
			});
		</script>
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
</html>
