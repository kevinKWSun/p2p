<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<title>伽满优-资金银行存管，安全透明高效的车贷出借平台</title>
		<meta name="Keywords" content="伽满优，车贷理财，车辆抵押,P2P出借理财,出借理财公司,短期理财,P2P出借理财平台" />
		<meta name="Description" content="伽满优（www.jiamanu.com）成立于2017年9月，是专注于质押车借贷的互联网金融信息服务平台。平台荣获AAA级信用认证，通过三级等保，推出多种出借策略，让出借更省心。" />
		<link href="/favicon.ico" rel="SHORTCUT ICON" />
		<link href="/images/default.css" rel="stylesheet" type="text/css" />
		<link href="/images/new/index.css" rel="stylesheet" type="text/css" />
		<link href="/images/new/swiper.min.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript" src="/images/jquery-1.7.2.min.js"></script>
		<script type="text/javascript" src="/images/globle.js"></script>
		<script type="text/javascript" src="/images/index.js"></script>
		<script src="/images/new/js/swiper.min.js"></script>
		<script src="/images/new/js/jquery.SuperSlide.2.1.js"></script>
		<style>
		.btnlogin{ width:298px; height:48px; color:#fff; border:none; font-size:18px; background:#fc501b; border-radius:3px;}
		.companyinfo li div { margin-bottom: -25px; }
		</style>
</head>
<body>
<?php include("topa.php") ?>
<div class="scroll">
    <div class="slide-main" id="touchMain">
		<div class="slide-box" id="slideContent">
            <div class="slide">
                <a href="/message/detail/12/70.html" stat="sslink-1" class='img0'></a>
            </div>
			<div class="slide">
                <a href="javascript:;" stat="sslink-5" class='img4'></a>
            </div>
			<div class="slide">
                <a href="/huodong/tow_19.html" stat="sslink-6" class='img5'></a>
            </div>
			<!--<div class="slide">
                <a href="/prize/prize.html" stat="sslink-7" class='img6'></a>
            </div-->
			<!--<div class="slide">
                <a href="/lucky.html" stat="sslink-2" class='img2'></a>
            </div>-->
			<div class="slide">
                <a href="/ztrees.html" stat="sslink-7" class='img6'></a>
            </div>
        </div>
        <div class="item">
            <a class="cur" stat="item1000" href="javascript:;"></a>
			<a href="javascript:;" stat="item1004"></a>
			<a href="javascript:;" stat="item1005"></a>
			<!--a href="javascript:;" stat="item1006"></a-->
			<!--<a href="javascript:;" stat="item1002"></a>-->
			<a href="javascript:;" stat="item1006"></a>
        </div>
     </div>
</div>
<div class="cent">
	<div class="loginguang" style="z-index:99999;">
		<div class="denglukuang">
			<h3>
				<span><b>6</b>.0% ~ <b>8</b>.0%</span> 
			</h3>
			<h4 style="margin: 19px auto;font-size:16px;">
				历史年化收益率
			</h4>
			<h4 style="margin: 19px auto;">
				用户资金由鄂托克前旗农商银行存管
			</h4>
			<?php if(! QUID){ ?>
				<a id="ctl00_MainPlaceHolder_ctl00_HyL_reg" class="btnreg"
				href="/suny/reg.html">注册领188元红包</a>
				<ul class="langul"><li>已有账号?</li><li><a href="/account.html">立即登录</a></li></ul>
			<?php } else { ?>
				<a id="ctl00_MainPlaceHolder_ctl00_HyL_reg" class="btnreg" href="/account.html">个人中心</a>
			<?php } ?>
		</div>
		<div class="clear"></div>
	</div>
</div>
<div class="cent">
	<div class="sy_gg">
		<i class="icon gg_biao"></i>
		<b style='float:left;color:red; margin-top:17px;'>最新公告：</b>
		<div class="sy_gg_nr" id="pg-header-post-cnt">
			<ul>
				<?php if(!empty($newest)) { ?>
					<?php foreach($newest as $k=>$v) { ?>
						<li><a target="_parent" href="/message/detail/<?php echo $newest_cate; ?>/<?php echo $v['id']; ?>.html"><?php echo $v['title']; ?></a><span><?php  echo date('Y-m-d', $v['addtime']); ?></span></li>
					<?php } ?>
				<?php } ?>
			</ul>
		</div>
		<div style='float: right;width:250px;line-height:25px;'>
		<a href="/message/newest_notice.html" style='float:left;width:50px; margin-top:12px;'>更多 > </a>
		<span style='float: right;color:#ff6230;width:150px;border:1px solid #ff6230;border-radius:25px;text-align:center; margin-top:12px;'>市场有风险，出借需谨慎</span>
		</div>
	</div>
    <div class="floor1">
         <ul class="floor1-ul1">
            <li><span><p><?php echo round(60199.80+$totalmoney/10000,2);?>万元</p><i>累计交易金额</i></span></li>
            <li><span><p><?php echo 42831+$totalmember;?>笔</p><i>累计交易笔数</i></span></li> 
			<li><span><p><?php echo round($totalsmoney,2);?>元</p><i>累计赚取收益</i></span></li>
         </ul>
          <ul class="floor1-ul2">
            <li><a href="javascript:;"><span><p>银行存管</p><i>鄂托克前旗农商银行存管</i></span></a></li>
            <li><a href="javascript:;"><span><p>利润稳健</p><i>历史年化利率6%-8%</i></span></a></li> 
			<li><a href="javascript:;"><span><p>专业风控体系</p><i>资金存管,车辆质押在库</i></span></a></li>
			<li><a href="javascript:;"><span><p>快速了解伽满优</p></span></a></li>
         </ul>
        <div class="clear"></div>
    </div>
    <!-- 新手标开始 -->
	<?php if(!empty($borrow_new)) { ?>
	<div id="new-people-title">
		<img src="images/new/img/inew-new-pepple-title.png" >
	</div>
	<div class="new_people">
		<div class="new_people_left">
			<img src="images/new/img/exclusive_new.jpg" />
		</div>
		<div class="new_people_centre">
			<dl>
				<dt><strong>【新手标-<?php echo $borrow_new['borrow_no']; ?>】</strong><span class="poeple_sp_1"><?php echo $borrow_new['borrow_name']; ?></span><span class="poeple_sp_2"><i><?php echo $this->config->item('repayment_type')[$borrow_new['repayment_type']]; ?></i><i>新手标</i></span></dt>
				<dd class="new_people_dd_one">
					<ul>
						<li>
							<h3><strong><?php echo round($borrow_new['borrow_interest_rate'], 2); ?>%<i>+<?php echo round($borrow_new['add_rate'], 2); ?>%</i></strong></h3>
							<p><img src="images/new/img/index_lilv.jpg" > 历史年化利率</p>
						</li>
						<li>
							<h3><?php echo $this->config->item('borrow_duration')[$borrow_new['borrow_duration']]; ?>天</h3>
							<p><img src="images/new/img/index_qixian.jpg" > 借款期限</p>
						</li>
						<li id="li-last-child">
							<h3><?php echo round($borrow_new['borrow_money']); ?>元</h3>
							<p><img src="images/new/img/index_zonge.jpg" > 标的总额</p>
						</li>
					</ul>
				</dd>
				<dd class="new_people_dd_two">
					<ul>
						<li>出借进度</li>
						<li class="new_people-percent-li">
							<span class="">
								<i style="width: <?php echo intval($borrow_new['has_borrow'] / $borrow_new['borrow_money'] *100); ?>%;"></i>
							</span>
						</li>
						<li><?php echo intval($borrow_new['has_borrow'] / $borrow_new['borrow_money'] *100); ?>%</li>
					</ul>
				</dd>
			</dl>					
		</div>
		<div class="new_people_right">
			<?php if($borrow_new['borrow_money'] - $borrow_new['has_borrow'] > 0) { ?>
				<a id="ctl00_MainPlaceHolder_ctl00_RptList_ctl00_HlStatus"  href="/invest/show/<?php echo $borrow_new['id']; ?>.html">立即出借</a>									
			<?php } else { ?>
				<?php if($borrow_new['borrow_status'] > 4) { ?>
					<a id="ctl00_MainPlaceHolder_ctl00_RptList_ctl00_HlStatus" class="hot-product-refund" href="/invest/show/<?php echo $borrow_new['id']; ?>.html">已完成</a>
				<?php } elseif($borrow_new['borrow_status'] == '3') { ?>
					<a id="ctl00_MainPlaceHolder_ctl00_RptList_ctl00_HlStatus" class="hot-product-refund" href="/invest/show/<?php echo $borrow_new['id']; ?>.html" >已满标</a>
				<?php } elseif($borrow_new['borrow_status'] == '4') { ?>
					<a id="ctl00_MainPlaceHolder_ctl00_RptList_ctl00_HlStatus" class="hot-product-refund" href="/invest/show/<?php echo $borrow_new['id']; ?>.html" >还款中</a>
				<?php } ?>									
			<?php } ?>
		</div>
	</div>
	<?php } ?>
	<!-- 新手标结束 -->
	
</div>
<!-- 热门产品开始 -->
<div class="hot-product" >
			<div class="hot-product-content">
				<h3><img src="/images/new/img/hot.png" alt="热门产品"/></h3>
				<?php if(!empty($nborrow)) { ?>
				<?php foreach($nborrow as $k=>$v) { ?>
				<dl>
					<dt><h4><?php echo mb_substr($v['borrow_name'], 0, 8); ?>..</h4><var><?php echo $this->config->item('borrow_duration')[$v['borrow_duration']]; ?>天</var></dt>
					<dd>借款金额：<?php echo round($v['borrow_money']/10000); ?>万</dd>

					<dd><?php echo $this->config->item('repayment_type')[$v['repayment_type']]; ?></dd>
					<dd><var>	
								<?php if(! $v['add_rate']) { ?>
									<?php echo $v['borrow_interest_rate']; ?>+<?php echo $v['add_rate']; ?>
								<?php } else { ?>
									<?php echo $v['borrow_interest_rate']; ?>
								<?php } ?>
							</var>%</dd>
					<dd>历史年化收益率</dd>
					<dd><?php if($v['borrow_money'] - $v['has_borrow'] > 0) { ?>		
						<span><i style="width: <?php echo intval($v['has_borrow'] / $v['borrow_money'] *100); ?>%;"></i></span><var><?php echo intval($v['has_borrow'] / $v['borrow_money'] *100); ?>%</var>
						<?php } else { ?>						
						<span><i style="width: 100%;"></i></span><var>100%</var>
						<?php } ?>
						</dd>
					<dd><?php if($v['borrow_money'] - $v['has_borrow'] > 0) { ?>
									<a id="ctl00_MainPlaceHolder_ctl00_RptList_ctl00_HlStatus"  href="/invest/show/<?php echo $v['id']; ?>.html">立即出借</a>									
								<?php } else { ?>
									<?php if($v['borrow_status'] > 4) { ?>
										<a id="ctl00_MainPlaceHolder_ctl00_RptList_ctl00_HlStatus" class="hot-product-refund" href="/invest/show/<?php echo $v['id']; ?>.html">已完成</a>
									<?php } elseif($v['borrow_status'] == '3') { ?>
										<a id="ctl00_MainPlaceHolder_ctl00_RptList_ctl00_HlStatus" class="hot-product-refund" href="/invest/show/<?php echo $v['id']; ?>.html" >已满标</a>
									<?php } elseif($v['borrow_status'] == '4') { ?>
										<a id="ctl00_MainPlaceHolder_ctl00_RptList_ctl00_HlStatus" class="hot-product-refund" href="/invest/show/<?php echo $v['id']; ?>.html" >还款中</a>
									<?php } ?>									
								<?php } ?></dd>
				</dl>
				<?php } ?>
				<?php } ?>				
			</div>
		</div>		
<!-- 	热门产品结束	 -->

<div class="cent">
<div class="floor4">
    <div class="floor4-left">
        <div class="floor4-title">  
			<p><span>公司动态</span></p>
		</div>
		<dl class="tabRank" id="tabRank">
			<dd class="bd">
				<ul class="ulList">
					<li class="t on"><img src="/images/new/img/huikuan.png"></li>
					<?php if(!empty($payments)) { ?>
						<?php foreach($payments as $k=>$v) { ?>
							
							<li class="t">
									<span class="num">.</span>
									<div class="c ">
										<div class="pubtitle"><a href="/message/detail/<?php echo $payments_cate; ?>/<?php echo $v['id']; ?>.html"><?php echo mb_substr($v['title'], 0, 16) ?>..</a></div>
									</div>
							</li>
						<?php } ?>
						
					<?php } ?>
				</ul>
            </dd>
		</dl>       
    </div>
    <div class="floor4-center">
    <div class="floor4-title">  
                      <p><span></span></p>
                 </div>
           <dl class="tabRank" id="tabRank">
			<dd class="bd">
				<ul class="ulList">
					<li class="t on"><img src="/images/new/img/gonggao.png"></li>
					<?php if(!empty($ptwest)) { ?>
						<?php foreach($ptwest as $k=>$v) { ?>
							<li class="t">
								<div class="c ">
									<div class="pubtitle"><a href="/message/detail/<?php echo $pt_cate; ?>/<?php echo $v['id']; ?>.html"><?php echo mb_substr($v['title'], 0, 16) ?>..</a><p><?php echo date('Y/m/d', $v['addtime']); ?></p></div>
								</div>
							</li>
						<?php } ?>
					<?php } ?>
				</ul>
            </dd>
			
	</dl>       

    </div>
     <div class="floor4-right">
            <div class="floor4-title">  
                      <p><span>&nbsp;&nbsp;媒体报道</span><a href="/message/media.html" class="gd">更多&nbsp;<i>&gt;</i></a></p>
                 </div>
	<div class="allFocus">
		<div class="t2_n">
			<ul class="m2list">
				<?php if(!empty($media)) { ?>
					<?php foreach($media as $k=>$v) { ?>
						<li>
							<div class="pic"> <a href="/message/detail/<?php echo $media_cate; ?>/<?php echo $v['id']; ?>.html"> <img src="<?php echo $v['img']; ?>" width="120";> </a> </div>
							<p class="tint"> <a href="/message/detail/<?php echo $media_cate; ?>/<?php echo $v['id']; ?>.html"> <?php echo mb_substr($v['title'], 0, 16) ?>..</a> </p>
							<p><?php echo $v['content']; ?></p>
						</li>
					<?php } ?>
				<?php } ?>
				
			</ul>
		</div>
	</div>
     </div>
   <div class="clear"></div>
</div>
	<div class="hzhb">
		<h2>
			<a href="javascript:;" class="gd"><!--更多 &nbsp;<i>&gt;</i>--></a> 合作伙伴 <span>感谢一路相伴</span>
		</h2>
		<div class="scrollBox" style="margin:0 auto">
			<div class="ohbox">
					<ul class="piclist">
						<li><a href="javascript:;"><img src="/new_img/8.png" rel="1"/></a></li>
						<li><a href="javascript:;"><img src="/new_img/1.png" rel="2"/></a></li>
						<li><a href="javascript:;"><img src="/new_img/6.png" rel="3"/></a></li>
						<li><a href="javascript:;"><img src="/new_img/3.png" rel="4"/></a></li>
						<li><a href="javascript:;"><img src="/new_img/4.png" rel="5"/></a></li>
						<li><a href="javascript:;"><img src="/new_img/5.png" rel="6"/></a></li>
						<li><a href="javascript:;"><img src="/new_img/2.png" rel="7"/></a></li>
					</ul>
			</div>
			<div class="pageBtn">
				<div class="prev"></div>
				<div class="next"></div>
				<ul class="list">
					<li>1</li>
					<li>2</li>

				</ul>
			</div>
			<div class="clear"></div>
	</div>
	<script type="text/javascript">
		jQuery(".scrollBox").slide({ titCell:".list li", mainCell:".piclist", effect:"leftLoop",vis:5,scroll:5,delayTime:800,trigger:"click",easing:"easeOutCirc",autoPlay:true});
	</script>
</div>
</div>

<!--</div>
	<div class="xiaoman" style="bottom: 0px;z-index:99999999">
		<div class="xiaomanimg">
			<a href="http://www.jiamanu.cn" target="_blank"><img src="/images/new/img/xiaoman.png" id='mao'></a>
			<img class="closes" src="/images/new/img/close.png">
		</div>
	</div>
</div>
<!--<div id='popupContact'>
	<div style='position:fixed;background:url(/new_img/tand.png) no-repeat;width:535px; height:76px;top:10%;left:35%;z-index:999999999; padding-top:500px;'>
		<img id='gb1' src='/new_img/bank_b.png' style='margin-left:65px;' />
		<a href='http://www.jiamanu.cn' target='_blank'><img src='/new_img/ffbg.png' style='margin-left:50px;' /></a>
		<br>
		<img id='gb' src='/new_img/dxd.png' style='margin-left:235px;margin-top:25px;' />
	</div>
</div>-->
<?php include("foota.php") ?>
<script>
	$(function(){
		$('#mao').click(function(){
			$('#popupContact').show();
		});
		$('#gb').click(function(){
			$('#popupContact').hide();
		});
		$('.closes').click(function(){
			$('.xiaoman').hide();
		});
		$('#gb1').click(function(){
			$('#popupContact').hide();
		});
	});
</script>
</body>
</html>