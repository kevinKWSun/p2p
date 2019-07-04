<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>圣诞节抽奖活动-伽满优</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<meta name="Keywords" content="圣诞节抽奖活动" />
		<meta name="Description" content="圣诞节抽奖活动" />
		<link href="/fanpai/css/index.css" rel="stylesheet" type="text/css" />
	</head>
	<body>
		<div id="activity">
			<div class="activity-rules">
				<div class="activity-rules-content" style="font-family: PingFangSC-Regular, sans-serif;">
					<p>活动日期：2018年12月1日至2019年1月10日</p>
					<p>活动人群：平台所有参与出借用户</p>
					<dl style="margin-bottom:10px;">
						<dt>活动规则：</dt>
						<dd>1、活动期间内，用户在伽满优平台中所获得的积分，可在积分商城抽奖使用；</dd>
						<dd>2、每次抽奖需扣除用户50个积分；</dd>
						<dd>3、抽奖订单一旦生成，不可取消，积分不作退还；</dd>
						<dd>4、每天参与抽奖次数不限；</dd>
						<dd>5、本次抽奖活动所产生的礼品，将在活动结束后T+5个工作日发放。</dd>
					</dl>
					<dl>
						<dt>投资红包使用规则：</dt>
						<dd>1、投资红包有效期为15天。</dd>
						<dd>2、投资红包激活要求：<br>　 ①　投资97天以上产品满30,000元即可使用200元红包；<br>　 ②　投资97天以上产品满50,000元即可使用300元红包。</dd>
						<dd>3、一个产品可激活多个投资红包。</dd>
						<dd>4、每天参与抽奖次数不限。</dd>
						<dd>5、本次抽奖活动所产生的礼品，将在活动结束后T+5个工作日发放。</dd>
					</dl>
					<span>
						<p>以上活动用户可重复参加，且与平台其他活动同享</p>
						<p>本次活动最终解释权归伽满优平台所有</p>
					</span>
				</div>
			</div>
			<div class="clear" style="height: 65px"></div>
			<div class="trophy-area">
				<div class="trophy-area-centent">
					<ul id="prize">
						<?php foreach($prizes as $k => $v){ if($k > 6){?>
						<li><img src='<?php echo $v['img']?>' /></li>
						<?php }}?>
					</ul>
					<div id='data'></div>
					<div class="clear"></div>
					<div class="start-lottery">
						<p class="p1"><?php if(! QUID){?>
						您尚未登录，<a href="/suny/login.html?ret_url=/prize/prize.html">去登录</a>
						<?php }?></p>
						<p class="p2"><a href="javascript:;" id="repeat">开始翻盘</a></p>
						<p class="p3"><label><input name='t' type='radio' value='1' checked />您剩余的单倍抽奖次数（<span><?php echo $totalscore?></span>）</label>| <a href="javascript:;">刷新试试</a></p>
						<p style='font-size: 14px;padding-left:7px;' class='p4'><label><input type='radio' value='2' name='t' />您剩余的双倍抽奖次数（<span><?php echo $totalscores?></span>）</label></p>
					</div>
				</div>
			</div>
			<div class="clear" style="height: 65px"></div>
			<div class="activity-detail">
				<div class="activity-detail-content">
					<ul class="activity-detail-content-one">
						<li>1、抽奖次数达到25次，可获得施华洛世奇圣诞钟挂饰；</li>
						<li>2、抽奖次数达到50次，可获得GUCCI BAMBOO 50ML香水；</li>
						<li>3、抽奖次数达到75次，可获得施华洛世奇 LOUISON PERAL手链；</li>
						<li>4、抽奖次数达到100次，可获得GIVENCHY 双G皮腰带；</li>
						<li>5、抽奖次数达到120次，可获得LV  BEYOND MONOGRAM 披肩；</li>
						<li>6、抽奖次数达到200次，可获得价值14000元神秘大奖；</li>
						<li>7、抽奖次数达到300次，可获得价值34600元神秘大奖。</li>
					</ul>
					<center><font color='red'><h2></h2></font></center>
					<ul class="activity-detail-content-two">
						<li <?php if($times>=0&&$times<25){echo 'id="activ"';}?>>
							<p></p>
							<var><?php echo $times?></var>
						</li>
						<li <?php if($times>=25&&$times<50){echo 'id="activ"';}?>>
							<p><?php if($img_1){?><img src="/fanpai/img/K1.png"/><?php }else{?><img src="/fanpai/img/b1.png" res="1" /><?php }?></p>
							<var><?php echo $times?></var>
						</li>
						<li <?php if($times>=50&&$times<75){echo 'id="activ"';}?>>
							<p><?php if($img_2){?><img src="/fanpai/img/K2.png"/><?php }else{?><img src="/fanpai/img/b2.png" res="2" /><?php }?></p>
							<var><?php echo $times?></var>
						</li>
						<li <?php if($times>=75&&$times<100){echo 'id="activ"';}?>>
							<p><?php if($img_3){?><img src="/fanpai/img/K3.png"/><?php }else{?><img src="/fanpai/img/b3.png" res="3" /><?php }?></p>
							<var><?php echo $times?></var>							
						</li>
						<li <?php if($times>=100&&$times<120){echo 'id="activ"';}?>>
							<p><?php if($img_4){?><img src="/fanpai/img/K4.png"/><?php }else{?><img src="/fanpai/img/b4.png" res="4" /><?php }?></p>
							<var><?php echo $times?></var>						
						</li>
						<li <?php if($times>=120&&$times<200){echo 'id="activ"';}?>>
							<p><?php if($img_5){?><img src="/fanpai/img/K5.png"/><?php }else{?><img src="/fanpai/img/b5.png" res="5" /><?php }?></p>
							<var><?php echo $times?></var>							
						</li>
						<li <?php if($times>=200&&$times<300){echo 'id="activ"';}?>>
							<p><?php if($img_6){?><img src="/fanpai/img/K6.png"/><?php }else{?><img src="/fanpai/img/b6.png" res="6" /><?php }?></p>
							<var><?php echo $times?></var>
						</li>
						<li <?php if($times>=300){echo 'id="activ"';}?>>
							<p><?php if($img_7){?><img src="/fanpai/img/K7.png"/><?php }else{?><img src="/fanpai/img/b7.png" res="7" /><?php }?></p>
							<var><?php echo $times?></var>						
						</li>
					</ul>
				</div>
			
			</div>
			<div class="clear"></div>
			<div class="my-trophy">
				<div class="my-trophy-centent">
					<ul>
						<?php foreach($prizes as $k => $v){?>
						<li><span class="active"><img src="<?php echo $v['img']?>" /></span><p>数量 <font color='red' id='num_<?php echo $v['id']?>'><?php echo $v['unum']?></font> 个</p></li>
						<?php }?>				
					</ul>
				</div>
			</div>			
		</div>
	</body>
</html>
<script type="text/javascript" src="/fanpai/js/jquery.js"></script> 
<script type="text/javascript" src="/fanpai/js/jquery-ui-1.7.2.custom.min.js"></script>
<script type="text/javascript" src="/fanpai/js/jquery.flip.min.js"></script>
<script type="text/javascript">
	$(function() {
		$("#prize li").each(function() {
			var p = $(this);
			p.click(function() {
				$("#prize li").css("pointer-events","none");
				if(p.find('img').attr('src')!='/fanpai/img/overspread.png'){
					return false;
				}
				var t = 1;
				t = $("input[type='radio']:checked").val();
				//$("#prize li").unbind('click');
				$.getJSON("/prize.html?rel="+t, function(json) {
					var prize = json.yes;
					if(prize.check === 0){
						location.href = '/suny/login.html?ret_url=/prize/prize.html';
					}else if(prize.check === 1){
						$('.p1').html('抽奖次数不足');
					}else if(prize.check === 2){
						$('.p1').html('<a href="javascript:;">服务器链接超时... >></a>');
					}else if(prize.check === 3){
						$('.p1').html('<a href="javascript:;">活动将于2018年12月1日开始... </a>');
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
								var num = $('font#num_'+prize.id).text();
								var totles = $('.p3 span').text();
								if(t == 1){
									$('font#num_'+prize.id).text(num*1+1);
									$('.p3 span').text(totles*1-1);
								}else if(t == 2){
									$('font#num_'+prize.id).text(num*1+2);
									totles = $('.p4 span').text();
									$('.p4 span').text(totles*1-1);
								}else{
									$('font#num_'+prize.id).text(num*1+1);
									$('.p3 span').text(totles*1-1);
								}
								var vars = $('ul.activity-detail-content-two li').eq(0).find('var').text();
								var tot = vars*1+1;
								$('ul.activity-detail-content-two li var').text(tot);
								if(tot>=25&&tot<50){
									$('ul.activity-detail-content-two li').removeAttr('id');
									$('ul.activity-detail-content-two li').eq(1).attr('id', 'activ');
								}else if(tot>=50&&tot<75){
									$('ul.activity-detail-content-two li').removeAttr('id');
									$('ul.activity-detail-content-two li').eq(2).attr('id', 'activ');
								}else if(tot>=75&&tot<100){
									$('ul.activity-detail-content-two li').removeAttr('id');
									$('ul.activity-detail-content-two li').eq(3).attr('id', 'activ');
								}else if(tot>=100&&tot<120){
									$('ul.activity-detail-content-two li').removeAttr('id');
									$('ul.activity-detail-content-two li').eq(4).attr('id', 'activ');
								}else if(tot>=120&&tot<200){
									$('ul.activity-detail-content-two li').removeAttr('id');
									$('ul.activity-detail-content-two li').eq(5).attr('id', 'activ');
								}else if(tot>=200&&tot<300){
									$('ul.activity-detail-content-two li').removeAttr('id');
									$('ul.activity-detail-content-two li').eq(6).attr('id', 'activ');
								}else if(tot>=300){
									$('ul.activity-detail-content-two li').removeAttr('id');
									$('ul.activity-detail-content-two li').eq(7).attr('id', 'activ');
								}
								setTimeout(function(){
									$("#repeat").removeAttr('style');
								},1000);
							}
						});
						$("#data").data("nolist", json.no);
					}
				});
			});
		});//var vars = $('ul.activity-detail-content-two li').eq(0).find('var').text();console.log(vars);
		$('ul.activity-detail-content-two li img').click(function(){
			var a = $(this);
			a.css("pointer-events","none");
			var res = a.attr('res');
			if(res){
				if(res*1>=1&&res*1<=7){
					$.post('/prize/box.html',{res:res},function(e){
						if(e.status == 1){console.log(res+'//');
							$('center font h2').text('恭喜您获得'+e.info).show();
							a.attr('src', '/fanpai/img/K'+res+'.png');
							a.removeAttr('res');
							var num = $('font#num_'+e.id).text();
							$('font#num_'+e.id).text(num*1+1);
							setTimeout(function(){
								$('center font h2').hide();
							},1500);
							var vars = $('ul.activity-detail-content-two li').eq(0).find('var').text();
							var tot = vars*1;
							if(tot>=25&&tot<50){
								$('ul.activity-detail-content-two li').removeAttr('id');
								$('ul.activity-detail-content-two li').eq(1).attr('id', 'activ');
							}else if(tot>=50&&tot<75){
								$('ul.activity-detail-content-two li').removeAttr('id');
								$('ul.activity-detail-content-two li').eq(2).attr('id', 'activ');
							}else if(tot>=75&&tot<100){
								$('ul.activity-detail-content-two li').removeAttr('id');
								$('ul.activity-detail-content-two li').eq(3).attr('id', 'activ');
							}else if(tot>=100&&tot<120){
								$('ul.activity-detail-content-two li').removeAttr('id');
								$('ul.activity-detail-content-two li').eq(4).attr('id', 'activ');
							}else if(tot>=120&&tot<200){
								$('ul.activity-detail-content-two li').removeAttr('id');
								$('ul.activity-detail-content-two li').eq(5).attr('id', 'activ');
							}else if(tot>=200&&tot<300){
								$('ul.activity-detail-content-two li').removeAttr('id');
								$('ul.activity-detail-content-two li').eq(6).attr('id', 'activ');
							}else if(tot>=300){
								$('ul.activity-detail-content-two li').removeAttr('id');
								$('ul.activity-detail-content-two li').eq(7).attr('id', 'activ');
							}
						}else if(e.status == 2){console.log(res+'==');
							$('center font h2').text(e.info).show();
							setTimeout(function(){
								$('center font h2').hide();
							},1500);
							setTimeout(function(){
								a.removeAttr('style');
							},1500);
						}else{console.log(res+'--');
							setTimeout(function(){
								a.removeAttr('style');
							},1500);
						}
					},'json');
				}
			}else{
				console.log(res+'===');
				setTimeout(function(){
					a.removeAttr('style');
				},1000);
			}
		});
		$('#repeat').click(function() {
			var check = '<?php echo QUID ? 1 : 0;?>';
			if(check === 0){
				location.href = '/suny/login.html?ret_url=/prize/prize.html';
			}else{
				$("#prize li img").css("cursor", "pointer");
				$("#prize li img").attr('src','/fanpai/img/overspread.png');
				$("#prize li").find('.active').removeClass('active');
				$("#prize li").removeAttr('style');
				$("#prize li").removeAttr('id');
			}
			$("#repeat").css("pointer-events","none");
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
		$('.p3 a').click(function() {
			location.href = '/prize/prize.html#prize';
		});
	});
</script>
