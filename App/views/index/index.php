<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<title>伽满优-资金第三方存管，安全透明高效的车贷理财平台</title>
		<meta name="Keywords" content="伽满优，车贷理财，车辆抵押,P2P投资理财,投资理财公司,短期理财,P2P投资理财平台" />
		<meta name="Description" content="伽满优（满优（www.jiamanu.com）成立于）成立于2017年9月，是专注于质押车借贷的互联网金融信息服务平台。平台荣获AAA级信用认证，通过三级等保，推出多种出借策略，让出借更省心。" />
		<link href="/favicon.ico" rel="SHORTCUT ICON" />
		<link href="./images/default.css" rel="stylesheet" type="text/css" />
		<link href="./images/index.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript" src="./images/jquery-1.7.2.min.js"></script>
		<script type="text/javascript" src="./images/globle.js"></script>
		<script type="text/javascript" src="./images/index.js"></script>
		<style>
.btnlogin{ width:298px; height:48px; color:#fff; border:none; font-size:18px; background:#fc501b; border-radius:3px;}
</style>
</head>
<body>
<?php include("top.php") ?>

<div class="scroll">
    <div class="slide-main" id="touchMain">
        <!-- <a class="prev" href="javascript:;" stat="prev1001"><img src="/Content/images/l.png" /></a>-->
        <div class="slide-box" id="slideContent">
            <div class="slide">
                <a href="javascript:;" stat="sslink-1" class='img0'></a>
            </div>
            <div class="slide">
                <a href="javascript:;" stat="sslink-2" class='img1'></a>
            </div>
			 <div class="slide">
                <a href="javascript:;" stat="sslink-3" class='img2'></a>
            </div>
            <div class="slide">
                <a href="javascript:;" stat="sslink-4" class='img3'></a>
            </div>
			 <div class="slide">
                <a href="javascript:;" stat="sslink-5" class='img4'></a>
            </div>
            <div class="slide">
                <a href="javascript:;" stat="sslink-6" class='img5'></a>
            </div>
			 <div class="slide">
                <a href="javascript:;" stat="sslink-7" class='img6'></a>
            </div>
        </div>
        <!-- <a class="next" href="javascript:;" stat="next1002"><img src="/Content/images/r.png" /></a>-->
        <div class="item">
            <a class="cur" stat="item1000" href="javascript:;"></a>
            <a href="javascript:;" stat="item1001"></a>
			<a href="javascript:;" stat="item1002"></a>
			<a href="javascript:;" stat="item1003"></a>
			<a href="javascript:;" stat="item1004"></a>
			<a href="javascript:;" stat="item1005"></a>
			<a href="javascript:;" stat="item1006"></a>
        </div>
    </div>
</div>

<div class="loginguang" style="z-index:99999;">
	<div class="denglukuang">
		<h4 style="margin: 19px auto;">当前累计成交额超过</h4>
		<h3>
			<span>37,101.34</span> 万元
		</h3>
		<h4 style="margin: 19px auto;">
			已为 30,872 位用户<br />赚取收益 2,938,438.37 元
		</h4>
		<?php if(! QUID){ ?>
			<a id="ctl00_MainPlaceHolder_ctl00_HyL_reg" class="btnreg"
			href="/suny/reg.html">注册领188元红包</a>
		<?php } else { ?>
			<a id="ctl00_MainPlaceHolder_ctl00_HyL_reg" class="btnreg" href="/account.html">个人中心</a>
		<?php } ?>
		
		

		<h5>以上数据取自伽满优运营统计</h5>
		<audio id="player" style="width: 276px;" src="/1.mp3" controls loop
			preload="auto"></audio>
	</div>
</div>
<div class="cent">
	<div class="sy_gg">
		<i class="icon gg_biao"></i>
		<div class="sy_gg_nr" id="pg-header-post-cnt">
			<ul>
				<?php if(!empty($newest)) { ?>
					<?php foreach($newest as $k=>$v) { ?>
						<li><a target="_parent" href="/message/detail/<?php echo $newest_cate; ?>/<?php echo $v['id']; ?>.html"><?php echo $v['title']; ?></a><span>
						<?php  echo date('Y-m-d', $v['addtime']); ?></span></li>
					<?php } ?>
					
				<?php } ?>
			</ul>
		</div>
		<a href="/message/newest_notice.html" class="gd">更多 <i class="icon"></i></a>
	</div>
	<div class="sy_mk">
		<ul>
			<li><i class="icon mk_biao1"></i>
				<h3>精选优质项目</h3>
				<h4>灵活机动100元起投</h4></li>
			<li><i class="icon mk_biao2"></i>
				<h3>利润稳健</h3>
				<h4>协议借款年利率14%</h4></li>
			<li><i class="icon mk_biao3"></i>
				<h3>专业风控体系</h3>
				<h4>资金托管，车辆质押在库</h4></li>
		</ul>
		<img src="./images/sy_ggt1.jpg" />
	</div>
	<div class="cptj">
		<h2>
			产品推荐 <span>安心之选，极致体验</span>
		</h2>
		<div class="cptj_di">
			<?php if(!empty($borrow)) { ?>
				<?php foreach($borrow as $k=>$v) { ?>
					<div class="cptj_nr">
						<h3>
							<a id="ctl00_MainPlaceHolder_ctl00_RptTuiJian_ctl00_HlName"
								href="/invest/show/<?php echo $v['id']; ?>.html"><?php echo $v['borrow_name']; ?></a>
						</h3>
						<span class="fx_biao"><?php echo $this->config->item('repayment_type')[$v['repayment_type']]; ?></span>
						<h4>
							<span><?php echo $v['borrow_interest_rate']; ?>%</span>
						</h4>
						<h5>历史年化利率</h5>
						<?php if($v['borrow_money'] - $v['has_borrow'] > 0) { ?>
							<a id="ctl00_MainPlaceHolder_ctl00_RptTuiJian_ctl00_HlStatus"
							class="tx_biao" href="/invest/show/<?php echo $v['id']; ?>.html">投资<?php echo $this->config->item('borrow_duration')[$v['borrow_duration']]; ?>天产品</a>
						<?php } else { ?>
							<p>项目总额：<?php echo number_format($v['borrow_money'], 2); ?> 元</p>
						<?php } ?>
					</div>
				<?php } ?>
			<?php } ?>
			
		</div>
	</div>
	<!--<div class="tyzq">
		<h2>
			体验专区 <span>惠享礼遇</span>
		</h2>
		<div class="tyzq_di">
			<img src="./images/sy_ty_tu.png" />
			<div class="tyzq_nr">
				<h3>
					<a id="ctl00_MainPlaceHolder_ctl00_RptXinShou_ctl00_HlName"
						href="/Product-136016181.aspx" style="color:#333;">体验宝（2018244期）</a>
					>
				</h3>
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td><span><em>8.00%</em>+0.88%</span></td>
						<td><span><em>3</em> 天</span></td>
						<td><span><em>67,000.00</em> 元</span></td>
						<td><a
							id="ctl00_MainPlaceHolder_ctl00_RptXinShou_ctl00_HlStatus"
							class="ljty_biao" href="/Product-136016181.aspx">立即投资</a></td>
					</tr>
					<tr>
						<td>历史年化利率</td>
						<td>产品期限</td>
						<td>项目总额</td>
						<td>&nbsp;</td>
					</tr>
				</table>
				<div class="xmjd">
					<span>项目进度</span>
					<div class="jdt">
						<div class="jdt_bi" style=" width:0%"></div>
					</div>
					0%
				</div>
			</div>
		</div>
	</div>-->
	<div class="clear"></div>

	<div class="tyzq">
		<h2>
			新手专区 <span>惠享礼遇</span>
		</h2>
		<div class="tyzq_di">
			<img src="./images/sy_xs_tu.png" />

			
			<div class="tyzq_nr">
				<?php if(isset($xinshou) && $xinshou) { ?>
					<h3>
						<a id="ctl00_MainPlaceHolder_ctl00_RptList_ctl00_HlName"
							href="/invest/show/<?php echo $xinshou['id']; ?>.html" style="color:#333;">[新手标]<?php echo mb_substr($xinshou['borrow_name'], 0, 5); ?>...</a>
						> <span>资金灵活 收益较高 安全保障</span>
					</h3>
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td><span><em><?php echo sprintf("%.2f", $xinshou['borrow_interest_rate']); ?>%</em>+<?php echo sprintf("%.2f", $xinshou['add_rate']); ?>%</span></td>
							<td><span><em><?php echo $this->config->item('borrow_duration')[$xinshou['borrow_duration']]; ?></em> 天</span></td>
							<td><span><em><?php echo number_format($xinshou['borrow_money']); ?></em> 元</span></td>
							<?php if($xinshou['borrow_money'] - $xinshou['has_borrow'] > 0) { ?>
								<td><a
								id="ctl00_MainPlaceHolder_ctl00_RptList_ctl00_HlStatus"
								class="ljty_biao" href="/invest/show/<?php echo $xinshou['id']; ?>.html">立即投资</a></td>
							<?php } else { ?>
								<td>
									<?php if($xinshou['borrow_status'] > 4) { ?>
										<a id="ctl00_MainPlaceHolder_ctl00_RptList_ctl00_HlStatus" class="ysq_biao" href="/invest/show/<?php echo $xinshou['id']; ?>.html">已完成</a>
									<?php } elseif($xinshou['borrow_status'] == '3') { ?>
										<a id="ctl00_MainPlaceHolder_ctl00_RptList_ctl00_HlStatus" class="ysq_biao" href="/invest/show/<?php echo $xinshou['id']; ?>.html">已满标</a>
									<?php } elseif($xinshou['borrow_status'] == '4') { ?>
										<a id="ctl00_MainPlaceHolder_ctl00_RptList_ctl00_HlStatus" class="ysq_biao" href="/invest/show/<?php echo $xinshou['id']; ?>.html">还款中</a>
									<?php } ?>
									
								</td>
							<?php } ?>
							
						</tr>
						<tr>
							<td>历史年化利率</td>
							<td>产品期限</td>
							<td>项目总额</td>
							<td>&nbsp;</td>
						</tr>
					</table>
				
					<div class="xmjd">
						<span>项目进度</span>
						<div class="jdt">
							<div class="jdt_bi" style=" width:<?php echo round($xinshou['has_borrow']*100/$xinshou['borrow_money']); ?>%"></div>
						</div>
						<?php echo round($xinshou['has_borrow']*100/$xinshou['borrow_money']); ?>%
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
	<div class="clear"></div>

	<div class="rmcp">
		<h2>
			<a href="/invest.html" class="gd">更多 <i class="icon"></i></a> 热门产品
			<span>投资送豪礼，短期推荐</span>
		</h2>
		<div class="rmcp_di">
			<img src="./images/sy_rm_tu.png"
				width="290" height="490" />
			<div class="rmcp_r">
				<?php if(!empty($borrow)) { ?>
					<?php foreach($borrow as $k=>$v) { ?>
						<div class="rmcp_nr">
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td width="26%"><span><a
											id="ctl00_MainPlaceHolder_ctl00_RpthotList_ctl00_HlName"
											href="/invest/show/<?php echo $v['id']; ?>.html"><?php echo mb_substr($v['borrow_name'], 0, 10); ?>...</a></span></td>
									<td width="23%"><span class="sp1"><?php echo sprintf("%.2f", $v['borrow_interest_rate'] + $v['add_rate']); ?>%</span></td>
									<td width="15%"><span><?php echo $this->config->item('borrow_duration')[$v['borrow_duration']]; ?>天</span></td>
									<td width="16%"><span><?php echo number_format($v['borrow_money']); ?>元</span></td>
									<?php if($v['borrow_money'] - $v['has_borrow'] > 0) { ?>
										<td width="20%" rowspan="2"><a
										id="ctl00_MainPlaceHolder_ctl00_RpthotList_ctl00_HlStatus"
										class="ljtz_biao" href="/invest/show/<?php echo $v['id']; ?>.html">立即投资</a></td>
									<?php } else { ?>
										<?php if($v['borrow_status'] > 4) { ?>
											<td width="20%" rowspan="2"><a id="ctl00_MainPlaceHolder_ctl00_RpthotList_ctl02_HlStatus" class="ysq_biao" href="/invest/show/<?php echo $v['id']; ?>.html">已完成</a></td>
										<?php } elseif($v['borrow_status'] == '3') { ?>
											<td width="20%" rowspan="2"><a id="ctl00_MainPlaceHolder_ctl00_RpthotList_ctl02_HlStatus" class="ysq_biao" href="/invest/show/<?php echo $v['id']; ?>.html">已满标</a></td>
										<?php } elseif($v['borrow_status'] == '4') { ?>
											<td width="20%" rowspan="2"><a id="ctl00_MainPlaceHolder_ctl00_RpthotList_ctl02_HlStatus" class="ysq_biao" href="/invest/show/<?php echo $v['id']; ?>.html">还款中</a></td>
										<?php } ?>
									<?php } ?>
									
								</tr>
								<tr>
									<td>到期还本息</td>
									<td>历史年化利率</td>
									<td>投资期限</td>
									<td>项目总额</td>
								</tr>
							</table>
						</div>
					<?php } ?>
				<?php } ?>
			</div>
		</div>
	</div>
	<div class="clear"></div>
	<div class="ggxw">
		<div class="hkgg">
			<h2>
				<a href="/message/payments.html" class="gd">更多 <i class="icon"></i></a>
				回款公告
			</h2>
			<div class="hkgg_di">
				<ul>
					<?php if(!empty($payments)) { ?>
						<?php foreach($payments as $k=>$v) { ?>
							<li><a href="/message/detail/<?php echo $payments_cate; ?>/<?php echo $v['id']; ?>.html"><?php echo mb_substr($v['title'], 0, 19) ?>..</a></li>
						<?php } ?>
						
					<?php } ?>
				</ul>
			</div>
		</div>
		<div class="mtbd">
			<h2>
				<a href="/message/media.html" class="gd">更多 <i class="icon"></i></a>
				媒体报道
			</h2>
			<div class="mtbd_di">
				<?php if(!empty($media)) { ?>
					<?php foreach($media as $k=>$v) { ?>
						<div class="mtbd_nr" style="width:250px;">
							<a href="/message/detail/<?php echo $media_cate; ?>/<?php echo $v['id']; ?>.html"><img
								src="<?php echo $v['img']; ?>"
								border="0" style="width:250px;" /></a>
							<h3><?php echo $v['title']; ?></h3>
							<p><?php echo $v['content']; ?></p>
						</div>
					<?php } ?>
				<?php } ?>
			</div>
		</div>
	</div>
	<div class="hzhb">
		<h2>
			<a href="#" class="gd">更多 <i class="icon"></i></a> 合作伙伴 <span>感谢一路相伴</span>
		</h2>
		<div class="hzhb_di">
			<ul>

				<li><a target="_blank" href="http://www.wdzj.com" title="网贷之家"><img
						src="./images/4c6db03c97bf4bfd845941d2e43ea025.png"
						width="290" height="100" /></a></li>

				<li><a target="_blank" href="https://www.sstl.org.cn/"
					title="上海市计算机软件评测重点实验室"><img
						src="./images/187154b65a604806a07cdc25318afb5d.png"
						width="290" height="100" /></a></li>

				<li><a target="_blank" href="https://www.aliyun.com/"
					title="阿里云"><img
						src="./images/7bf29ffd6582404caa0c192aac083ff9.png"
						width="290" height="100" /></a></li>

				<li><a target="_blank" href="https://www.tsign.cn/" title="e签宝"><img
						src="./images/1eaf57cd58ce4e65a7f4f62792e69ac4.png"
						width="290" height="100" /></a></li>

				<li><a target="_blank" href="http://www.hanshenglaw.cn/"
					title="汉盛律师事务所"><img
						src="./images/e0d1c6c6c298429e8ddc44fd72baa2e2.png"
						width="290" height="100" /></a></li>

				<!--<li><a target="_blank" href="http://www.eqqnsyh.com/"
					title="鄂托克前旗银行"><img
						src="./images/be5aeb3d36164971be42819cc7a63e4c.png"
						width="290" height="100" /></a></li>-->

				<li><a target="_blank" href="http://www.p2peye.com/"
					title="网贷天眼"><img
						src="./images/b4d1476ec5ea4aaa8cfe1ba241a5eb60.png"
						width="290" height="100" /></a></li>

				<li><a target="_blank" href="http://www.boc.cn/" title="中国银行"><img
						src="./images/5c7d6c4299e84bcbb9e1de30eee29d41.png"
						width="290" height="100" /></a></li>

				<li><a target="_blank" href="http://pay.fuiou.com/"
					title="富友支付"><img
						src="./images/522dd3ceb4394918879afc382dc236ea.png"
						width="290" height="100" /></a></li>

				<!--<li><a target="_blank" href="http://www.pxkeji.com"
					title="鹏翔科技"><img
						src="./images/2942fea898954ee5a856a83691ce6f01.png"
						width="290" height="100" /></a></li>-->

			</ul>
		</div>
		<div class="clear"></div>
	</div>
</div>


<div class="banner" style="display: none;">
	<div class="loginbox">
		<div class="loginareas"></div>
		<div class="loginarea">
			<div>
				<span>历史年化利率</span>
				<h1>7-15%</h1>

			</div>
		</div>
	</div>
	<div class="fullSlide">
		<div class="bd">
			<ul>

			</ul>
		</div>
		<div class="hd">
			<ul></ul>
		</div>
		<span class="prev"></span> <span class="next"></span>
	</div>
</div>
<?php include("foot.php") ?>
</body>
</html>