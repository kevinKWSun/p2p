<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>交易记录-伽满优</title>
    <meta http-equiv="Content-Language" content="zh-cn" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <meta name="viewport" content="width=device-width, maximum-scale=1.0, initial-scale=1.0,initial-scale=1.0,user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="Keywords" content="交易记录-伽满优,车贷理财,车辆抵押,P2P投资理财,投资理财公司,短期理财,P2P投资理财平台" />
		<meta name="Description" content="交易记录-伽满优,通过公开透明的规范操作,平台为投资理财人士提供收益合理、安全可靠、高效灵活的车贷理财产品。" />
    <link href="/images/default.css" rel="stylesheet" type="text/css" />
		<link href="/images/index.css" rel="stylesheet" type="text/css" />
		<link href="/images/new/user-info.css" rel="stylesheet" type="text/css" />
    <link href="/src/css/layui.css" rel="stylesheet" />
    <script src="/src/layui.js"></script>
		<script src="/images/jquery-1.7.2.min.js"></script>
</head>
<body>
<?php include("top.php") ?>
 <div class="cent_v2">
	<div class="zhzx_v2">
		<div class="zhzx_l_v2">
			<?php include("left_v1.php") ?>
		</div>
		<div class="zhzx_r_v2">
			<h2>交易记录</h2>
				<div class="jyjl_v2">
					<div class="jyjl_top_v2">
						<div class="jyjl_nr_v2 ">
						<span>时间：</span>						
						<input name="time1" id="time1" type="text" maxlength="10" class="in1" style="width: 104px" readonly="" lay-key="1">
						<em>-</em>
						<input name="time2" id="time2" type="text" maxlength="10" class="in1" style="width: 104px;margin-right: 55px;" readonly="" lay-key="2" >
						 
						<a href="/trade.html?data=1" class="<?php echo $date==1?'on':'';?>">今天</a>
						<a href="/trade.html?data=2" class="<?php echo $date==2?'on':'';?>">1个月</a> 
						<a href="/trade.html?data=3" class="<?php echo $date==3?'on':'';?>">3个月</a>
						<a href="/trade.html?data=4" class="<?php echo $date==4?'on':'';?>">半年</a> 
						<a href="/trade.html?data=5" class="<?php echo $date==5?'on':'';?>">1年</a>
					</div>
					<div class="jyjl_nr_v2">
						<dl class="jyjl_nr_dl_v2">
							<dt>交易分类：</dt>
							<?php $type = isset($_GET['type'])?$_GET['type']:''; ?>
							<dd><a href="javascript:void(0);" rel="0" class="<?php echo $type==0?'on':'';?>">全部</a></dd>
							<dd><a href="javascript:void(0);" rel="1" class="<?php echo $type==1?'on':'';?>">充值</a></dd>
							<dd><a href="javascript:void(0);" rel="2" class="<?php echo $type==2?'on':'';?>">解冻</a></dd>
							<dd><a href="javascript:void(0);" rel="3" class="<?php echo $type==3?'on':'';?>">冻结</a></dd>
							<dd><a href="javascript:void(0);" rel="4" class="<?php echo $type==4?'on':'';?>">借款</a></dd>
							<dd><a href="javascript:void(0);" rel="5" class="<?php echo $type==5?'on':'';?>">出借</a></dd>
							<dd><a href="javascript:void(0);" rel="8" class="<?php echo $type==8?'on':'';?>">还款</a></dd>
							<dd><a href="javascript:void(0);" rel="9" class="<?php echo $type==9?'on':'';?>">收款</a></dd>
							<dd><a href="javascript:void(0);" rel="10" class="<?php echo $type==10?'on':'';?>">提现</a></dd>
							<dd><a href="javascript:void(0);" rel="11" class="<?php echo $type==11?'on':'';?>">佣金</a></dd>
						</dl>
						<dl class="jyjl_nr_dl_v2">
							<dt>其他分类：</dt>
							<dd><a href="javascript:void(0);" rel="0" class="<?php echo $type==0?'on':'';?>">全部</a></dd>
							<dd><a href="javascript:void(0);" rel="6" class="<?php echo $type==6?'on':'';?>">出借红包</a></dd>
							<dd><a href="javascript:void(0);" rel="7" class="<?php echo $type==7?'on':'';?>">现金红包</a></dd>
							<dd><a href="javascript:void(0);" rel="12" class="<?php echo $type==12?'on':'';?>">推荐奖励</a></dd>
							<dd><a href="javascript:void(0);" rel="13" class="<?php echo $type==13?'on':'';?>">其他费用</a></dd>
							<dd><a href="javascript:void(0);" rel="15" class="<?php echo $type==15?'on':'';?>">账号差额</a></dd>
							<dd><a href="javascript:void(0);" rel="14" class="<?php echo $type==14?'on':'';?>">红包发送失败</a></dd>
						</dl>
					</div>

				</div>
				
				<div class="jytime_v2">
					<table width="100%" cellspacing="0" cellpadding="0">
						<tr>
							<td><img src ="/images/new/image/time-icon.png"/><span>交易时间</span></td>
							<td><img src ="/images/new/image/jiaoyi-icon.png"/><span>交易类型</span></td>
							<td><img src ="/images/new/image/shouzhi-icon.png"/><span>收支</span></td>
							<td><img src ="/images/new/image/jine-icon.png"/><span>账户余额</span></td>
							<td><img src ="/images/new/image/beizhu-icon.png"/><span>备注</span></td>
						</tr>
						<?php if(empty($moneylog)):?>
						<tr>
							<td colspan='5'>暂无数据！</td>
						</tr>
						<?php endif;?>
						<?php foreach($moneylog as $v):?>
						<tr>
							<td><?php echo date('Y-m-d H:i:s', $v['add_time'])?></td>
							<td><?php echo $this->config->item('money_logs')[$v['type']]?></td>
							<td>￥<?php echo $v['affect_money']?></td>
							<td>￥<?php echo $v['account_money']?></td>
							<td><?php echo $v['info']?></td>
						</tr>
						<?php endforeach;?>
					</table>
					<div  class="lstPager" >
						<div class="layui-box layui-laypage layui-laypage-default"><!--span class="PageEndStyle">&lt;</span><span class="SelectIndexStyle">1</span><span class="PageEndStyle">&gt;</span-->
						<?php echo $page; ?>
						<a href="javascript:;" class="layui-laypage-next">共 <?php echo $totals; ?> 条</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php include("foot.php") ?>
<script type="text/javascript">
	layui.use(['layer', 'form', 'laydate'], function () {
		var $ = layui.$
		, layer = layui.layer
		, form = layui.form
		,laydate = layui.laydate;
		laydate.render({
			elem: '#time1'
	    });
		  laydate.render({
			elem: '#time2'
		});
		$('.jyjl_nr_dl_v2 dd a').on('click', function(){
			var tel = $(this).attr('rel');
			var time1 = $('input[name=time1]').val();
			var time2 = $('input[name=time2]').val();
			location.href = '/trade.html?type='+tel+'&time1='+time1+'&time2='+time2;
		});
		
	});		
</script>
</body>
</html>