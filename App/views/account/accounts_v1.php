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
		<link href="/images/new/user-info.css" rel="stylesheet" type="text/css" />
		<link href="/images/new/date.css" rel="stylesheet" type="text/css" />
		<link href="/src/css/layui.css" rel="stylesheet" />
		<script src="/images/jquery-1.7.2.min.js"></script>
		<script src="/src/layui.js"></script>
</head>
<body>
<?php include("top.php") ?>
<div class="cent_v2">

	<div class="zhzx_v2">
		<div class="zhzx_l_v2">
			<?php include("left_v1.php") ?>
		</div>

		
		<div class="zhzx_r_v2">
			<h2>我的资产
				<?php if(!IS_FINANCE) { ?>
					<span style="font-size: 14px;color: #fc501b;margin-left: 30px;"><a style="color: #fc501b ;" href="/invest.html">去出借 > </a> </span>
				<?php  } ?>
			</h2>
			<div class="wdzc_nr_v2">
				<ul>
					<li style="width: 30%;">
						<h4>总资产(元)</h4>
						<strong><?php echo sprintf("%.2f", $ky['money_collect']+$ky['money_freeze']+$ky['account_money']) ;?></strong>
						<span>
							<a href="/account/recharge.html" class="cz_biao">充值 </a>
							<a href="/account/withdraw.html" class="tx_biao">提现</a>
						</span>

					</li>
					<li style="width: 30%;">
						<h4>可用余额(元)</h4>
						<strong><?php echo sprintf("%.2f", $ky['account_money']+0);?></strong>
						<?php if(!IS_FINANCE) { ?>
							<i>可用红包</i>
							<em id="hb" style="cursor:pointer">
							<em> <?php echo $packets ?> 元</em>&nbsp;&nbsp;&nbsp; 查看</em>
						<?php  } ?>
					</li>
					<li style="width: 20%;">
						<h4>可用积分(分) </h4>
						<strong><?php echo get_member_info(QUID)['totalscore']?></strong>
						<?php if(!IS_FINANCE) { ?>
							<em id="js-score" style="cursor:pointer">查看</em>
						<?php  } ?>
					</li>
					<!--<li style="width: 20%;">
						<h4>可抽奖数/卡片数</h4>
						<strong>totals,'/',cards;</strong>
						<em><a href='/pintu.html' target='_blank'>去抽奖</a><a style="color: #fc501b ;" href="/invest.html">出借赚次数</a></em>
					</li>-->
				</ul>
			</div>
			<div class="ybt_v2">
				<div id="main" style="height: 100%;width: 35%;float: left;">
				</div>
				<div class="echarts_list">
					<ul>
						<li><var style="background: #d48265;"></var><i>待收收益</i><i><?php echo $tm ?>元</i></li>
						<li><var style="background: #c23531;"></var><i>可用余额</i><i><?php echo $ky['account_money'] ? $ky['account_money'] : 0;?>元</i></li>
						<li><var style="background: #61a0a8;"></var><i>待收本金</i><i><?php echo $ky['money_collect'] ? $ky['money_collect'] : 0;?>元</i></li>
						<li><var style="background: #2f4554;"></var><i>冻结金额</i><i> <?php echo $ky['money_freeze'] ? $ky['money_freeze'] : 0;?>元</i></li>
					</ul>
				</div>
			</div>
			
			<div class="clear"></div>
			
			<div class="wdyhk_v2">
				<h3>银行卡</h3>
				<div class="card-item-v2">
					<span class="bank-logob-v2" id="bank-0302b"><?php echo $b['bank_address']?></span> <br>
					<p class="card-num-v2"><?php echo $b['paycard'];?></p>
					<span class="card-edit-v2">修改银行卡</span>
				</div>
				<div class="yhk_ts_v2">
					<h4>温馨提示：</h4>
					<p>1、资金同卡进出；</p>
					<p>2、(快捷)支持银行：</p>
					<p>　　工商银行、中国银行、建设银行、交通银行</p>
					<p>　　光大银行、广发银行、兴业银行、平安银行</p>
					<p>　　浦发银行、上海银行、邮储银行、中信银行</p>
				</div>
			</div>
			<div class="clear"></div>
			<div style="margin-top: 20px;">
				<h3 style="height: 40px; font-size: 16px;">回款日历</h3>
				<div class="layui-inline" id="test-n2"></div>
				<div class="layui-row" style="margin-bottom: 30px;">
					<div class="layui-col-md9">
						<?php echo $month; ?>月：本月已回：<?php echo $current['current_capital'] + $current['current_interest'];?> 元；本月应回<?php echo $investor['month_interest'] + $investor['month_capital'];?> 元
					</div>
					<div class="layui-col-md3" style="text-align: right;">
						<span><var style="background: #ca4117; height: 10px;width: 10px;border-radius: 5px;margin-right: 10px;display: inline-block"></var>待回款日期</span>
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

		$('.card-edit-v2').on('click', function() {
			var get_url = "/account/get_upbind_img.html";
			$.get(get_url,function(result){				
					if(result.status == "0"){
						layer.alert("审核中！");
						return false;
					}else if(result.status == "1"){		
						location.href = "/account/upbind.html";		
					}else{
						var url = "/account/upbinds.html";
						layer.open({
							type: 2,
							title: '修改银行卡',
							shade: 0.1,
							maxmin: true,
							area: ['600px', '330px'],
							fixed: true,
							content: url
						});
					}
			});

		});
					
					
		$('#hb').on('click',function(){
			var index = '/account/packet.html';
			location.href = index;
			return false;
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
// 		$('.cz_biao').on('click',function(){
// 			layer.open({
// 				type: 2,
// 				title: '充值',
// 				//shadeClose: true,
// 				shade: 0.1,
// 				maxmin: true,
// 				area: ['65%', '75%'],
// 				fixed: true,
// 				content: '/account/recharge.html'
// 			});
// 		});
// 		$('.tx_biao').on('click',function(){
// 			layer.open({
// 				type: 2,
// 				title: '提现',
// 				//shadeClose: true,
// 				shade: 0.1,
// 				maxmin: true,
// 				area: ['65%', '75%'],
// 				fixed: true,
// 				content: '/account/withdraw.html'
// 			});
// 		});
		
		$('input[name=upbind]').on('click',function() {
			layer.msg('为了你的账户安全，请联系客服', {icon: 6,time : 1500});
			//location.href = '/account/upbind.html';
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
<script>
		function formatDate(x){
			var date1 = new Date();
			var date2 = new Date(date1);
			date2.setDate(date1.getDate() + x);
			return(date2.getFullYear() + "-" + (date2.getMonth() + 1) + "-" + date2.getDate());
		}
		
		layui.use(['layer', 'form', 'jquery', 'laydate'], function() {
			var layer = layui.layer,
					$ = layui.jquery,
					laydate = layui.laydate,
					form = layui.form;

			var new_date = new Date();
			var maxTime = formatDate(97);
			var minTime = formatDate(-90);
			//获取用户的所有回款数据
			$.get('/account/get_investor_detail',function(info){
				loding_date(new_date, info);				
			});			
			
			//日历插件调用方法  
			function loding_date(date_value, data) {
				var test_ttt = {
					elem: '#test-n2',
					type: 'date',
					theme: 'grid',
					max: maxTime,   
					min: minTime,
					position: 'static',
					range: false,
					value: date_value,
					isInitValue: false,
					calendar: true,
					btns: false,
					ready: function(value) {
						hide_mr(value);
					},
					done: function(value, date, endDate) {
						date_chose(value, data);
					},
					mark: data //重要json！
				}
				laydate.render(test_ttt);
			}

			function hide_mr(value) {
				var mm = value.year + '-' + value.month + '-' + value.date;
				$('.laydate-theme-grid table tbody').find('[lay-ymd="' + mm + '"]').removeClass('layui-this');
			}

			//获取隐藏的弹出层内容
			var date_choebox = $('.date_box').html();

			//定义弹出层方法

			function date_chose(obj_date, data) {
				if (data[obj_date] == undefined) {
					layer.msg('当日没有数据，请换个时间查询', {
					  icon: 6
					});
					//$('.laydate-theme-grid table tbody').find('[lay-ymd="' + obj_date + '"]').removeClass('layui-this');
					return false;
				} else {
					//发生ajax 查询
					//$.post('url', obj, function(str){});  返回 money1  money2 值
					$.get('/account/get_investor_detail_day',{times: obj_date},function(info){
						//$('.laydate-theme-grid table tbody').find('[lay-ymd="' + obj_date + '"]').removeClass('layui-this');
						var index = layer.open({
							type: 1,
							skin: 'layui-layer-rim', //加上边框
							title: '预计回款详情',
							area: ['350px', 'auto'], //宽高
							content: '<div class="text_box">' +
								'<span>当日回款总金额' +
								'<var>' + (info.total).toFixed(2) + '元</var>' +
								'<br/>当日回款本金' +
								'<var>' + info.capital + '元</var>' +
								'</span></div>'
						});							
					});		
				}
			}
		});
	</script>
	<style>	
		.layui-laydate .layui-this {
			background-color: orangered !important;
		}
	</style>

</body>
</html>