<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>出借人明细-伽满优</title>
    <meta http-equiv="Content-Language" content="zh-cn" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <meta name="viewport" content="width=device-width, maximum-scale=1.0, initial-scale=1.0,initial-scale=1.0,user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="Keywords" content="出借人明细-伽满优,车贷理财,车辆抵押,P2P投资理财,投资理财公司,短期理财,P2P投资理财平台" />
		<meta name="Description" content="出借人明细-伽满优,通过公开透明的规范操作,平台为投资理财人士提供收益合理、安全可靠、高效灵活的车贷理财产品。" />
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
			<h2>出借人明细</h2>
			<div class="lc_tb_di_v2">
				<div style="height: 25px; line-height: 25px; padding: 16px 0px; width: 908px; 
					text-align: left; float: left; border-bottom: #eb790d 0px solid; font-size: 14px;">
					截止<span style="color: #fc501b; font-weight: bold; margin:0 20px 0 10px; "><?php echo date('Y-m-d H:i:s')?></span>
					您直接邀请的会员投资情况：
				</div>
				<ul class="lc_tb_li_v2">
					<li>邀请人数：&nbsp;&nbsp;&nbsp;&nbsp; <?php echo $counts?> 人</li>
					<li>好友投资：&nbsp;&nbsp;&nbsp;&nbsp; <?php echo $money['money']?$money['money']:'0.00'?> 元</li>
				</ul>
				<table class="td1_v2" width="100%" cellspacing="0" cellpadding="0" border="0">
					<tbody>
						<tr>
							<td width="15%">
								出借人手机号：
							</td>
							<td width="35%">
								<input name="tel" type="text" value="<?php echo $tel?>">
							</td>
							<td width="10%">
								投资日期：
							</td>
							<td width="14%">
								<input name="time1" maxlength="10" style="width:124px;" type="text" value="<?php echo $time1?>" id="time1" readonly="" lay-key="1">
							</td>
							<td style="color: #909090;" width="3%" align="center">
								至
							</td>
							<td width="18%">
								<input name="time2" maxlength="10" style="width:124px;" type="text" value="<?php echo $time2?>" id="time2" readonly="" lay-key="2">
							</td>
							<td width="11%">
								<input name="tj" class="cx_v2" type="submit" value="查询">
							</td>
						</tr>
					</tbody>
				</table>
				
				<table class="td2_v2" width="100%" cellspacing="0" cellpadding="0" border="0">
					<tbody>
						<tr class="tr">
							<td width="12%"><img src="/images/new/image/head-icon.png" /><span>真实姓名</span></td>
							<td width="14%"><img src="/images/new/image/iphone-icon.png" /><span>手机号</span></td>
							<td><img src="/images/new/image/time-icon.png" /><span>注册时间</span></td>
							<td><img src="/images/new/image/item-icon.png" /><span>出借项目</span></td>
							<td><img src="/images/new/image/chujie-icon.png" /><span>出借时间</span></td>
							<td width="12%"><img src="/images/new/image/jine-icon.png" /><span>出借金额</span></td>
							<td width="10%"><img src="/images/new/image/qixian-icon.png" /><span>期限</span></td>
						</tr>
						<?php foreach($investor as $v):?>
						<tr>
							<td width="8%"><?php echo $v['real_name']?></td>
							<td width="14%"><?php echo $v['phone']?></td>
							<td><?php echo date('Y-m-d', $v['reg_time'])?></td>
							<td><?php echo $v['borrow_type'] == 2 ? $v['borrow_name'].'<font color="red">[新]</font>' : $v['borrow_name']; ?></td>
							<td><?php echo date('Y-m-d', $v['add_time'])?></td>
							<td width="10%"><?php echo $v['investor_capital']?></td>
							<td width="5%"><?php echo $this->config->item('borrow_duration')[$v['borrow_duration']]?></td>
						</tr>
						<?php endforeach;?>
					</tbody>
				</table>
				<div>
					<div class="layui-box layui-laypage layui-laypage-default"><!--span class="PageEndStyle">&lt;</span><span class="SelectIndexStyle">1</span><span class="PageEndStyle">&gt;</span-->
					<?php echo $page; ?>
					<a href="javascript:;" class="layui-laypage-next">共 <?php echo $totals; ?> 条</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php include("foot.php") ?>
<script>
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
		$('.cx_v2').on('click', function(){
			var tel = $('input[name=tel]').val();
			var time1 = $('input[name=time1]').val();
			var time2 = $('input[name=time2]').val();
			location.href = '/investors.html?tel='+tel+'&time1='+time1+'&time2='+time2;
		});
	});
</script>
</body>
</html>