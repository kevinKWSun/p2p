<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<title>伽满优-资金第三方存管，安全透明高效的车贷理财平台</title>
		<meta name="Keywords" content="伽满优，车贷理财，车辆抵押,P2P投资理财,投资理财公司,短期理财,P2P投资理财平台" />
		<meta name="Description" content="伽满优，通过公开透明的规范操作，平台为投资理财人士提供收益合理、安全可靠、高效灵活的车贷理财产品。" />
		<link href="/favicon.ico" rel="SHORTCUT ICON" />
		<link href="/images/default.css" rel="stylesheet" type="text/css" /><link href="/images/index.css" rel="stylesheet" type="text/css" />
		<link href="/src/css/layui.css" rel="stylesheet" />
		<style>
			.layui-laypage-default a {
				display: inline-block;
				vertical-align: middle;
				padding: 0 15px;
				height: 28px;
				line-height: 28px;
				margin: 0 -1px 5px 0;
				background-color: #fff;
				color: #333;
				font-size: 12px;
			}
		</style>
</head>
<body>
<?php include("top.php") ?>

<div class="tz_banner"></div>
<div class="cent">
	<div class="dqwz">
		<i class="icon"></i><a href="/">首页</a> > 我要投资
	</div>
	<div class="tzlb">
		<div class="tz_sx">
			<h3>筛选出借项目</h3>
			<ul>
				<li><span>借款期限：</span> <a class="on"
					href="/List-1-0---1--0--0-1.aspx">不限</a> <a class=""
					href="/List-1-0-0to30--1--0--0-1.aspx">30天以下</a> <a class=""
					href="/List-1-0-30to90--1--0--0-1.aspx">30~90天</a> <a class=""
					href="/List-1-0-90to180--1--0--0-1.aspx">90~180天</a> <a class=""
					href="/List-1-0-180to--1--0--0-1.aspx">180天以上</a></li>
				<li><span>融资金额：</span> <a class="on"
					href="/List-1-0---1--0--0-1.aspx">不限</a> <a class=""
					href="/List-1-0---1--0-1to5-0-1.aspx">1~5万</a> <a class=""
					href="/List-1-0---1--0-5to10-0-1.aspx">5~10万</a> <a class=""
					href="/List-1-0---1--0-10to20-0-1.aspx">10~20万</a> <a class=""
					href="/List-1-0---1--0-20to50-0-1.aspx">20~50万</a> <a class=""
					href="/List-1-0---1--0-50to-0-1.aspx">50万以上</a></li>
				<li><span>历史年化利率：</span> <a class="on"
					href="/List-1-0---1--0--0-1.aspx">不限</a> <a class=""
					href="/List-1-0--7to10-1--0--0-1.aspx">7%～10%</a> <a class=""
					href="/List-1-0--10to12-1--0--0-1.aspx">10%～12%</a> <a class=""
					href="/List-1-0--12to15-1--0--0-1.aspx">12%～15%</a> <a class=""
					href="/List-1-0--15to-1--0--0-1.aspx">15%以上</a></li>
			</ul>
		</div>
		<div class="tz_lb">
			
			<?php if(!empty($borrow)) { ?>
				<?php foreach($borrow as $k=>$v) { ?>
					<div class="tzlb_nr">
						<h3>
							<?php if($v['borrow_type'] === '1') { ?>
								<a id="ctl00_MainPlaceHolder_ctl00_RptList_ctl00_HlName" class="proname" href="/invest/show/<?php echo $v['id']; ?>.html">体验宝（<?php echo $v['borrow_name']; ?>）</a>
							<?php } elseif($v['borrow_type'] === '2') { ?>
								<a id="ctl00_MainPlaceHolder_ctl00_RptList_ctl00_HlName" class="proname" href="/invest/show/<?php echo $v['id']; ?>.html">[新手标]<?php echo $v['borrow_name']; ?></a>
								<span class="zyjx_biao">质押精选</span>
							<?php } else { ?>
								<a id="ctl00_MainPlaceHolder_ctl00_RptList_ctl00_HlName" class="proname" href="/invest/show/<?php echo $v['id']; ?>.html"><?php echo $v['borrow_name']; ?></a>
								<span class="zyjx_biao"><?php echo $this->config->item('borrow_type')[$v['borrow_type']]; ?></span>
							<?php } ?>
							
						</h3>
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<?php if(in_array($v['borrow_type'], array(1, 2))) { ?>
									<td width="230px" class="td1"><span class="span1"><?php echo sprintf("%.2f", $v['borrow_interest_rate']); ?>%<?php if(!empty($v['add_rate'])) { echo '+',sprintf("%.2f", $v['add_rate']),'%'; } ?></span></td>
									<td width="100px"><span><?php echo $this->config->item('borrow_duration')[$v['borrow_duration']]; ?>天</span></td>
									<td width="160px"><span><?php echo round($v['borrow_money']); ?>元</span></td>
									<td width="260px"><span><?php echo $this->config->item('repayment_type')[$v['repayment_type']]; ?></span></td>
									<td width="150px"><span><?php echo round($v['borrow_money'] - $v['has_borrow']); ?>元</span></td>
									<?php if($v['has_borrow'] >= $v['borrow_money']) { ?>
										<?php if($v['borrow_status'] > 4) { ?>
											<td rowspan="2"><a id="ctl00_MainPlaceHolder_ctl00_RptList_ctl07_HlStatus" class="zzhk_biao" href="/invest/show/<?php echo $v['id']; ?>.html">已完成</a></td>
										<?php } elseif($v['borrow_status'] == '3') { ?>
											<td rowspan="2"><a id="ctl00_MainPlaceHolder_ctl00_RptList_ctl07_HlStatus" class="zzhk_biao" href="/invest/show/<?php echo $v['id']; ?>.html">已满标</a></td>
										<?php } elseif($v['borrow_status'] == '4') { ?>
											<td rowspan="2"><a id="ctl00_MainPlaceHolder_ctl00_RptList_ctl07_HlStatus" class="zzhk_biao" href="/invest/show/<?php echo $v['id']; ?>.html">还款中</a></td>
										<?php } ?>
										
									<?php } else { ?>
										<td rowspan="2"><a id="ctl00_MainPlaceHolder_ctl00_RptList_ctl00_HlStatus" class="ljtz_biao" href="/invest/show/<?php echo $v['id']; ?>.html">立即投标</a></td>
									<?php } ?>
									
								<?php } else { ?>
									<td width="230px" class="td1"><span class="span1"><?php echo sprintf("%.2f", $v['borrow_interest_rate'] + $v['add_rate']); ?>%</span></td>
									<td width="100px"><span><?php echo $this->config->item('borrow_duration')[$v['borrow_duration']]; ?>天</span></td>
									<td width="160px"><span><?php echo round($v['borrow_money']); ?>元</span></td>
									<td width="260px"><span><?php echo $this->config->item('repayment_type')[$v['repayment_type']]; ?></span></td>
									<td width="150px"><span><?php echo round($v['borrow_money'] - $v['has_borrow']); ?>元</span></td>
									<?php if($v['has_borrow'] >= $v['borrow_money']) { ?>
										
										<?php if($v['borrow_status'] > 4) { ?>
											<td rowspan="2"><a id="ctl00_MainPlaceHolder_ctl00_RptList_ctl07_HlStatus" class="zzhk_biao" href="/invest/show/<?php echo $v['id']; ?>.html">已完成</a></td>
										<?php } elseif($v['borrow_status'] == '3') { ?>
											<td rowspan="2"><a id="ctl00_MainPlaceHolder_ctl00_RptList_ctl07_HlStatus" class="zzhk_biao" href="/invest/show/<?php echo $v['id']; ?>.html">已满标</a></td>
										<?php } elseif($v['borrow_status'] == '4') { ?>
											<td rowspan="2"><a id="ctl00_MainPlaceHolder_ctl00_RptList_ctl07_HlStatus" class="zzhk_biao" href="/invest/show/<?php echo $v['id']; ?>.html">还款中</a></td>
										<?php } ?>
									<?php } else { ?>
										<td rowspan="2"><a id="ctl00_MainPlaceHolder_ctl00_RptList_ctl00_HlStatus" class="ljtz_biao" href="/invest/show/<?php echo $v['id']; ?>.html">立即投标</a></td>
									<?php } ?>
								<?php } ?>
								
							</tr>
							<tr>
								<td class="td1">历史年化利率</td>
								<td>投资期限</td>
								<td>项目总额</td>
								<td>收益方式</td>
								<td>剩余可购金额</td>
							</tr>
						</table>
					</div>
				<?php } ?>
			<?php } ?>
		</div>
	</div>
	<div class="page">
		<div class="layui-box layui-laypage layui-laypage-default">
			<?php echo $page; ?>
			<a href="javascript:;" class="layui-laypage-next" data-page="2">共 <?php echo $totals; ?> 条</a> 
		</div>
	</div>

</div>

<div class="listcon" style="display:none;">

	<div class="banner_list">
		<img src="/Template/Default/Css/img/list_banner.jpg" />
	</div>
	<div class="listcon">
		<div class="list_tab">
			<div class="maincon">
				<span>投资列表</span>
			</div>
		</div>
		<div class="sx_tj">
			<div class="sx_tj_c">
				<ul>
					<li><span>标的类型：</span> <a class="on"
						href="/List-1-0---1--0--0-1.aspx">不限</a> <a class=""
						href="/List-1-0---1-抵押标-0--0-1.aspx">抵押标</a> <a class=""
						href="/List-1-0---1-担保标-0--0-1.aspx">担保标</a> <a class=""
						href="/List-1-0---1-信用标-0--0-1.aspx">信用标</a></li>



					<li><span>还款方式：</span> <a class="on"
						href="/List-1-0---1--0--0-1.aspx">不限</a> <a class=""
						href="/List-1-0---1--0--1-1.aspx">按月付息，到期还本</a> <a class=""
						href="/List-1-0---1--0--2-1.aspx">等额本息</a> <a class=""
						href="/List-1-0---1--0--5-1.aspx">季度付息 </a> <a class=""
						href="/List-1-0---1--0--4-1.aspx">半年付息 </a> <a class=""
						href="/List-1-0---1--0--3-1.aspx">到期还本息</a></li>
					<li style="display:none;"><span>资信评级：</span> <a class="on"
						href="/List-1-0---1--0--0-1.aspx">不限</a> <a class=""
						href="/List-1-0---1--7--0-1.aspx">AAA</a> <a class=""
						href="/List-1-0---1--6--0-1.aspx">AA+</a> <a class=""
						href="/List-1-0---1--5--0-1.aspx">AA</a> <a class=""
						href="/List-1-0---1--4--0-1.aspx">A+</a> <a class=""
						href="/List-1-0---1--3--0-1.aspx">A</a></li>
				</ul>
			</div>
			<div class="clear"></div>
		</div>


		<div class="tabcon maincon">
			<div class="tctit clearfix">
				<span class="w1">项目名称</span> <span class="w2">融资金额</span> <span
					class="w3">年化收益</span> <span class="w4">投资期限</span> <span
					class="w5">投资进度</span> <span class="w6">剩余金额</span> <span
					class="w7">状态</span>
			</div>

		</div>
	</div>


</div>

<?php include("foot.php") ?>
</body>
</html>