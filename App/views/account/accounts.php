<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>会员中心-伽满优</title>
    <meta http-equiv="Content-Language" content="zh-cn" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <meta name="viewport" content="width=device-width, maximum-scale=1.0, initial-scale=1.0,initial-scale=1.0,user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="Keywords" content="会员中心-伽满优,车贷理财,车辆抵押,P2P投资理财,投资理财公司,短期理财,P2P投资理财平台" />
	<meta name="Description" content="会员中心-伽满优,通过公开透明的规范操作,平台为投资理财人士提供收益合理、安全可靠、高效灵活的车贷理财产品。" />
    <link href="/images/default.css" rel="stylesheet" type="text/css" />
	<link href="/images/index.css" rel="stylesheet" type="text/css" />
	<link href="./images/CenterIndex.css" rel="stylesheet" type="text/css" />
    <link href="/src/css/layui.css" rel="stylesheet" />
    <script src="/src/layui.js"></script>
</head>
<body>
<?php include("top.php") ?>
<div class="cent">
	<div class="dqwz">
		<i class="icon"></i><a href="/">首页</a> > <a href="/account.html"> 我的账户</a> > 我的资产
	</div>
	<div class="zhzx">
		<div class="zhzx_l">
			<?php include("left.php") ?>
		</div>
		<div class="zhzx_r">
			<h2>我的资产</h2>
			<div>
				<div class="wdzc">
					<div class="wdzc_nr">
						<h4>
							总资产(元) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; |  <i class="icon"></i> 可用红包
							<em id='hb' style='cursor:pointer'>
							<em> <?php echo $packets ?> 元</em> 查看</em><!--  |  
							<i class="icon"></i> 可用体验金 
							<em><em><?php if($ky['max_time']>time()){echo $ky['ty_money']?$ky['ty_money']:0;}else{echo 0;} ?></em></em> 元 -->
						</h4>
						<?php if(!IS_COMPANY) { ?>
							<h3>
								<a href="/invest.html" class="rtz">去投资 ></a> 
								<?php echo sprintf("%.2f", $ky['money_collect']+$ky['money_freeze']+$ky['account_money']) ;?>
							</h3>
						<?php } ?>
						
						<h4>可用余额(元)</h4>
						<h3>
							<a href="javascript:;"
								class="cz_biao">充值 </a> <a
								href="javascript:;" class="tx_biao">提现
							</a> <?php echo sprintf("%.2f", $ky['account_money']+0);?>
						</h3>
						<h4>可用积分(分) &nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;<em id="js-score" style="cursor : pointer;">查看</em></h4>
						<h3><?php echo get_member_info(QUID)['totalscore']?></h3>
					</div>
					<div class="ybt">
						<div id="main" style="width: 440px; height: 160px;"></div>
					</div>
					<div class="clear"></div>
				</div>
				<div class="xu_xian"></div>
				<div class="wdyhk">
					<h3>我的银行卡</h3>
					<div class="chongzhi">
						<div class="yhk_nr">
							<div class="card-item"
								style="background-image: url('/images/yhk_bj.jpg');background-size:100% 100%;border:none;">
								<?php if($b){?>
								<span class="bank-logob" id="bank-0302b"><?php echo $b['bank_address']?></span> <br>
								<p class="card-num">卡号：<?php echo $b['paycard'];?></p>
								<br><br><br>
								<p class="al-right">
									<input type="submit" name="upbind"
										value="修改银行卡" class="xiugai" />
								</p>
								<?php }else{?>
								<span>无银行卡信息</span><br><br><br><br><br>
								<p class="card-num"></p>
								<p class="al-right">
									<input type="submit" name="bind"
										value="添加银行卡" class="xiugai" />
								</p>
								<?php }?>
							</div>
							<div class="yhk_ts">
								<h4>温馨提示：</h4>
								<p>1、资金同卡进出；</p>
								<p>2、(快捷)支持银行：</p>
								<!--p>工商银行、建设银行、农业银行、招商银行、交通银行、中国银行</p>
								<p>光大银行、民生银行、兴业银行、中信银行、广发银行、浦发银行</p>
								<p>平安银行、华夏银行、宁波银行、东亚银行、上海银行、中国邮储</p>
								<p>南京银行、上海农商、渤海银行、成都银行、北京银行</p-->
								<p>　　工商银行、中国银行、建设银行、交通银行</p>
								<p>　　光大银行、广发银行、兴业银行、平安银行</p>
								<p>　　浦发银行、上海银行、邮储银行、中信银行</p>
							</div>
						</div>

					</div>

				</div>
			</div>
		</div>

	</div>
</div>
<?php include("foot.php") ?>
<script src="/src/echarts.js" type="text/javascript"></script>
<script type="text/javascript">
	layui.use(['layer', 'form'], function () {
		var $ = layui.$
		, layer = layui.layer
		, form = layui.form;
		$('#hb').on('click',function(){
			layer.open({
				type: 2,
				title: '我的红包',
				//shadeClose: true,
				shade: 0.1,
				maxmin: true,
				area: ['65%', '70%'],
				fixed: true,
				content: '/account/packet.html'
			});
		});
		$('#js-score').on('click',function(){
			layer.open({
				type: 2,
				title: '我的积分',
				//shadeClose: true,
				shade: 0.1,
				maxmin: true,
				area: ['65%', '70%'],
				fixed: true,
				content: '/account/score.html'
			});
		});
		$('.cz_biao').on('click',function(){
			layer.open({
				type: 2,
				title: '充值',
				//shadeClose: true,
				shade: 0.1,
				maxmin: true,
				area: ['65%', '75%'],
				fixed: true,
				content: '/account/recharge.html'
			});
		});
		$('.tx_biao').on('click',function(){
			layer.open({
				type: 2,
				title: '提现',
				//shadeClose: true,
				shade: 0.1,
				maxmin: true,
				area: ['65%', '75%'],
				fixed: true,
				content: '/account/withdraw.html'
			});
		});
		$('input[name=upbind]').on('click',function() {
			layer.msg('为了你的账户安全，请联系客服', {icon: 6,time : 1500});
			// location.href = '/account/upbind.html';
			// layer.open({
				// type: 2,
				// title: '修改银行卡',
				// shade: 0.1,
				// maxmin: true,
				// area: ['65%', '70%'],
				// fixed: true,
				// content: '/account/upbind.html'
			// });
		});
	});
	var myChart = echarts.init(document.getElementById('main'));
	var option = {
		tooltip : {
			trigger : 'item',
			formatter : "{a} <br/>{b} : {c} ({d}%)"
		},
		legend : {
			orient : 'vertical',
			left : 'left',
			data : [ '可用余额', '冻结金额', '待收本金', '待收收益' ]
		},
		series : [
			{
				name : '我的资产',
				type : 'pie',
				selectedMode : 'single',
				radius : [ 0, '80%' ],
				label : {
					normal : {
						show : false,
						position : 'inner'
					}
				},
				labelLine : {
					normal : {
						show : false
					}
				},
				data : [
					{
						value : <?php echo $ky['account_money'] ? $ky['account_money'] : 0;?>,
						name : '可用余额：<?php echo $ky['account_money'] ? $ky['account_money'] : 0;?>元'
					},
					{
						value : <?php echo $ky['money_freeze'] ? $ky['money_freeze'] : 0;?>,
						name : '冻结金额：<?php echo $ky['money_freeze'] ? $ky['money_freeze'] : 0;?>元'
					},
					{
						value : <?php echo $ky['money_collect'] ? $ky['money_collect'] : 0;?>,
						name : '待收本金：<?php echo $ky['money_collect'] ? $ky['money_collect'] : 0;?>元'
					},
					{
						value : <?php echo $tm ?>,
						name : '待收收益：<?php echo $tm ?>元'
					}

				]
			}
		]
	};
	myChart.setOption(option);

</script>
</body>
</html>