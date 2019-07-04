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
   <!--<div class="tz_sx_1">
     <h3>筛选出借项目</h3>
     <ul>
       <li><span>借款类型：</span>
			<a class="on" href="javascript:;">全部</a>
			<a class="" href="javascript:;">企业贷</a>
			<a class="" href="javascript:;">个人贷</a>
                         
		</li>
       <li><span>项目期限：</span> 
			<a class="on" href="javascript:;">全部</a>
			<a class="" href="javascript:;">1-33天</a>
			<a class="" href="javascript:;">34-65天</a>
			<a class="" href="javascript:;">66-97天</a>
		</li>
       <li><span>项目状态：</span> 
			<a class="on" href="javascript:;">全部</a>
			<a class="" href="javascript:;">募集中</a>
			<a class="" href="javascript:;">还款中</a>
			<a class="" href="javascript:;">还款完成</a>
                            
		</li>
	   <li><span>还款方式：</span> 
			<a class="on" href="javascript:;">全部</a>
			<a class="" href="javascript:;">到期还本息</a>
			<a class="" href="javascript:;">按月付息，到期利随本清</a>
			<a class="" href="javascript:;">等额本息</a>			
		</li>
     </ul>
	 <strong>伽满优提醒您：市场有风险，出借需谨慎。</strong>
   </div>
   <div class="clear"></div>-->
   <div class="tz_lb_v1">
		<!--<div class="tz_lb_top">
			<dl>
				<dt>默认排序</dt>
				<dd><a href="javascript:;" >金额</a></dd>
				<dd><a href="javascript:;" >利率</a></dd>
				<dd><a href="javascript:;" >期限</a></dd>
			</dd>
			</dl>
		</div>-->
		<?php if(!empty($borrow)) { ?>
			<?php foreach($borrow as $k=>$v) { ?>
				<div class="tzlb_nr_v1" style='margin-bottom:20px; background: url(/new_img/tuijian.png) #ffffff no-repeat right top;padding-top:20px;'>
					<h3>
						<span class="qyd_biao" <?php if($v['borrow_type'] == 2) echo 'style="background:url(/new_img/xin-icon.png) no-repeat;"' ?>>【<?php echo $v['borrow_type'] == 2 ? '新手标' : '企业贷'; ?>-<?php echo $v['borrow_no']; ?>】</span><a id="ctl00_MainPlaceHolder_ctl00_RptList_ctl00_HlName" class="proname" href="/invest/show/<?php echo $v['id']; ?>.html"><?php echo $v['borrow_name']; ?></a>  
					</h3>
					<h4 style='float:right;padding-right:200px;'>
						<i><?php echo $this->config->item('repayment_type')[$v['repayment_type']]; ?></i>
						<i><?php echo $v['borrow_type'] == 5 ? '质押精选' : $this->config->item('borrow_type')[$v['borrow_type']]; ?></i>
					</h4>
				   <table width="100%" border="0" cellspacing="0" cellpadding="0">
					  <tbody>
						<tr>
							<td width="20%" class="td1"><span class="span1"><?php echo sprintf("%.2f", $v['borrow_interest_rate']); ?>%</span><span class="span2">+<?php echo sprintf("%.2f", $v['add_rate']); ?>%</span></td>
							<td width="20%"><span><?php echo $this->config->item('borrow_duration')[$v['borrow_duration']]; ?>天</span></td>
							<td width="20%"><span><?php echo round($v['borrow_money']); ?>元</span></td>
							<td width="20%" class="td2"><strong><i style="width:<?php echo floor($v['has_borrow']/$v['borrow_money']*100)?>%"></i></strong><span class="percent"><?php echo floor($v['has_borrow']/$v['borrow_money']*100)?>%</span></td>
							<td width="20%" rowspan="2">
							
								<?php if($v['has_borrow'] >= $v['borrow_money']) { ?>
										
									<?php if($v['borrow_status'] > 4) { ?>
										<a id="ctl00_MainPlaceHolder_ctl00_RptList_ctl00_HlStatus" class="ljtz_biao" href="/invest/show/<?php echo $v['id']; ?>.html" style="background-color:#ddd;">已完成</a>
									<?php } elseif($v['borrow_status'] == '3') { ?>
										<a id="ctl00_MainPlaceHolder_ctl00_RptList_ctl00_HlStatus" class="ljtz_biao" href="/invest/show/<?php echo $v['id']; ?>.html" style="background-color:#ddd;">已满标</a>
									<?php } elseif($v['borrow_status'] == '4') { ?>
										<a id="ctl00_MainPlaceHolder_ctl00_RptList_ctl00_HlStatus" class="ljtz_biao" href="/invest/show/<?php echo $v['id']; ?>.html" style="background-color:#ddd;">还款中</a>
									<?php } else { ?>
										<a id="ctl00_MainPlaceHolder_ctl00_RptList_ctl00_HlStatus" class="ljtz_biao" href="/invest/show/<?php echo $v['id']; ?>.html" style="background-color:#ddd;">已满标</a>
									<?php } ?>
								<?php } else { ?>
									<a id="ctl00_MainPlaceHolder_ctl00_RptList_ctl00_HlStatus" class="ljtz_biao" href="/invest/show/<?php echo $v['id']; ?>.html">立即出借</a>
								<?php } ?>
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