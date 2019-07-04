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
	<link href="./images/CenterIndex.css" rel="stylesheet" type="text/css" />
    <link href="/src/css/layui.css" rel="stylesheet" />
    <script src="/src/layui.js"></script>
</head>
<body>
<?php include("top.php") ?>
 <div class="cent">
	<div class="dqwz">
		<i class="icon"></i><a href="/">首页</a> > <a href="/account.html"> 我的账户 </a> > 交易记录
	</div>
	<div class="zhzx">
		<div class="zhzx_l">
			<?php include("left.php") ?>
		</div>
		<div class="zhzx_r">
			<h2>交易记录</h2>
			<div class="jyjl">
				<div class="jyjl_top">
					<div class="jyjl_nr jyjl_nr1">
						<span>时间：</span>
						<input name="time1" id='time1' type="text" maxlength="10" class="in1" style="width: 104px" readonly />
						<em>-</em>
						 <input name="time2" id='time2' type="text" maxlength="10" class="in1" style="width: 104px" readonly />
						<a href="/trade.html?data=1" class="<?php echo $date==1?'on':'';?>">今天</a>&nbsp; &nbsp; | &nbsp;&nbsp; 最近：
						<a href="/trade.html?data=2" class="<?php echo $date==2?'on':'';?>">1个月</a> 
						<a href="/trade.html?data=3" class="<?php echo $date==3?'on':'';?>">3个月</a>
						<a href="/trade.html?data=4" class="<?php echo $date==4?'on':'';?>">半年</a> 
						<a href="/trade.html?data=5" class="<?php echo $date==5?'on':'';?>">1年</a>
					</div>
					<div class="jyjl_nr">
						<span>类型：</span>
						<select name="type">
							<option value="0">全部</option>
							<?php foreach($types as $k => $v):?>
							<option value="<?php echo $k?>"><?php echo $v?></option>
							<?php endforeach;?>
						</select>
						<button class="layui-btn layui-btn-xs layui-btn-danger">查询</button>
					</div>
				</div>
				<div class="jyjl">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<th>交易时间</th>
							<th>交易类型</th>
							<th>收支</th>
							<th>账户余额</th>
							<th>备注</th>
						</tr>
						<?php foreach($moneylog as $v):?>
						<tr>
							<td><?php echo date('Y-m-d H:i:s', $v['add_time'])?></td>
							<td><?php echo $this->config->item('money_logs')[$v['type']]?></td>
							<td class="td2">￥<?php echo $v['affect_money']?></td>
							<td class="td3">￥<?php echo $v['account_money']?></td>
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
		$('.layui-btn-danger').on('click', function(){
			var tel = $('select[name=type] option:selected').val();
			var time1 = $('input[name=time1]').val();
			var time2 = $('input[name=time2]').val();
			location.href = '/trade.html?type='+tel+'&time1='+time1+'&time2='+time2;
		});
	});		
</script>
</body>
</html>