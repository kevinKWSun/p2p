<!DOCTYPE html>
<!-- saved from url=(0038)https://www.jiamanu.com/Default-1.aspx -->
<html>
<title>项目列表-伽满优</title>
<meta name="Keywords" content="伽满优，车贷理财，车辆抵押,P2P投资理财,投资理财公司,短期理财,P2P投资理财平台"><meta name="Description" content="伽满优，专注质押车优质投资服务，多种出借策略，期限自由搭配，操作灵活，产品丰富，综合年化7%-12%。"><link href="/favicon.ico" rel="SHORTCUT ICON">

    <link href="/favicon.ico" rel="SHORTCUT ICON" />
	<link href="/src/css/layui.css" rel="stylesheet" />
	<link href="/images/default.css" rel="stylesheet" type="text/css" />
	<link href="/images/new/index.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="/images/jquery-1.7.2.min.js"></script>
	<style>
		.companyinfo li div {
			margin-bottom: -20px;
		}
	</style>
</head>
<body>
<?php include("topa.php"); ?>
<div class="tz_banner"></div>
<div class="cent">
 <div class="dqwz"><i class="icon"></i><a href="https://www.jiamanu.com/">首页</a> &gt;   我要出借</div>
 <div class="tzlb">
   <div class="tz_lb_v1">
		<?php if(!empty($borrow)) { ?>
			<?php foreach($borrow as $k=>$v) { ?>
				<div class="tzlb_nr_v1" style='margin-bottom:20px; background: url(/new_img/tuijian.png) #ffffff no-repeat right top;padding-top:20px;'>
					<h3>
						<span class="qyd_biao" style="background:url(/new_img/ge-icon.png) no-repeat;">【个人贷-<?php echo $v['proid']; ?>】</span><a id="ctl00_MainPlaceHolder_ctl00_RptList_ctl00_HlName" class="proname" href="/invest/shows/<?php echo $v['proid']; ?>.html"><?php echo $v['proname']; ?></a>  
					</h3>
					<h4 style='float:right;padding-right:200px;'>
						<i><?php echo $v['paymentType']; ?></i>
						<!--<i><?php echo 'borrow_type'; ?></i>-->
					</h4>
				   <table width="100%" border="0" cellspacing="0" cellpadding="0">
					  <tbody>
						<tr>
							<td width="20%" class="td1">
								<span class="span1"><?php echo sprintf("%.2f", $v['InterestRateFixed']); ?>%</span>
								<?php if($v['InterestRateAdd'] > 0) { ?>
									<span class="span2">+<?php echo $v['InterestRateAdd']; ?>%</span>
								<?php } ?>
							</td>
							<td width="20%"><span><?php echo 97; ?>天</span></td>
							<td width="20%"><span><?php echo $v['borrowbalance']; ?>元</span></td>
							<td width="20%" class="td2"><strong><i style="width:<?php echo 100; ?>%"></i></strong><span class="percent"><?php echo 100; ?>%</span></td>
							<td width="20%" rowspan="2">
								<a id="ctl00_MainPlaceHolder_ctl00_RptList_ctl00_HlStatus" class="ljtz_biao" href="/invest/shows/<?php echo $v['proid']; ?>.html" style="background-color:#ddd;"> 
									<?php if($v['Sta'] < 5) {
										echo '已完成';
									} else {
										echo '已完成';
									}
									?>
								</a>
							</td>
						</tr>
						<tr>
							<td><img src='/new_img/s-2.png' style='margin-top:15px;'/> 历史年化利率</td>
							<td><img src='/new_img/s-3.png' style='margin-top:15px;'/> 借款期限</td>
							<td><img src='/new_img/s-1.png' style='margin-top:15px;'/> 标的总额</td>
							<td>项目进度</td>
						</tr>
					   </tbody>
					</table>
									 
				 </div>
			<?php } ?>
		<?php } ?>
		<div class="page" style="text-align:center;">
			<div class="layui-box layui-laypage layui-laypage-default">
				<?php echo $page; ?>
				<a href="javascript:;" class="layui-laypage-next" data-page="2">共 <?php echo $totals; ?> 条</a> 
			</div>
		</div>
   </div>
 </div>

</div>

<?php include("foota.php"); ?>
</body></html>