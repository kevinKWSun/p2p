<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>提现-伽满优</title>
    <meta http-equiv="Content-Language" content="zh-cn" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <meta name="viewport" content="width=device-width, maximum-scale=1.0, initial-scale=1.0,initial-scale=1.0,user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="Keywords" content="提现-伽满优,车贷理财,车辆抵押,P2P投资理财,投资理财公司,短期理财,P2P投资理财平台" />
	<meta name="Description" content="提现-伽满优,通过公开透明的规范操作,平台为投资理财人士提供收益合理、安全可靠、高效灵活的车贷理财产品。" />
	<link href="/images/default.css" rel="stylesheet" type="text/css" />
	<link href="/images/index.css" rel="stylesheet" type="text/css" />
	<link href="/images/new/user-info.css" rel="stylesheet" type="text/css" />
	<link href="/images/new/date.css" rel="stylesheet" type="text/css" />
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
			<h2>账号提现 </h2>
			<?php $bank = array(
				"中国工商银行"=>"bank-1.png",
				"中国光大银行"=>"bank-2.png",
				"广发银行"=>"bank-3.png",
				"交通银行"=>"bank-4.png",
				"平安银行"=>"bank-5.png",
				"浦发银行"=>"bank-6.png",
				"兴业银行"=>"bank-7.png",
				"中国邮政储蓄银行"=>"bank-8.png",
				"中国建设银行"=>"bank-9.png",
				"中国银行"=>"bank-10.png",
				"中信银行"=>"bank-11.png",
				"上海银行"=>"bank-12.png"
			);?>
			
			<div class="fhui-admin-table-container">
				<div class="layui-form-item">
					<label class="layui-form-label" style='font-size:14px'>可用余额:</label>
					<div class="layui-input-inline" style='font-size:14px;line-height:35px;'>
						￥ <?php echo $my['account_money']+0;?>元
					</div>
				</div>
				<div class="layui-form-item">
					<label class="layui-form-label" style='font-size:14px'>可提金额:</label>
					<div class="layui-input-inline" style='font-size:14px;line-height:35px;'>
						￥ <?php echo (($my['account_money']-$today['money']) > 0) ? ($my['account_money']-$today['money']) : 0; ?>元
					</div>
				</div>
				<div class="layui-form-item">
					<label class="layui-form-label" style='font-size:14px'>真实姓名:</label>
					<div class="layui-input-block" style='font-size:14px;line-height:35px;'>
						<?php echo mb_substr($my_user['real_name'],0,1,'utf-8'); ?>* <i style="color: #999999;font-size: 13px;">（必须和您的银行账号姓名一致）</i>
					</div>
				</div>
				<div class="layui-form-item">
					<label class="layui-form-label" style='font-size:14px'>银卡卡号:</label>		
					<div class="online_bank_card">
						<img src="/images/new/image/<?php echo $bank[$my_user['bank_address']];?> ">
						<strong><var>尾号<?php echo mb_substr($my_user['paycard'],-4,4,'utf-8'); ?></var></strong>
					</div>				
				</div>

				<div class="layui-form-item" style="margin-top: 25px;">
					<label class="layui-form-label" style='font-size:14px'>提现金额:</label>
					<div class="layui-input-inline">
						<input autocomplete="off" lay-verify="required" value="" placeholder="必填,提现金额" class="layui-input" type="text" onkeyup="value=value.replace(/[^\d.]/g,'');" onblur="value=value.replace(/[^\d.]/g,'');" maxlength="11"/>
					</div>
				</div>
				<div class="layui-form-item">
					<label class="layui-form-label" style='font-size:14px'>手续费:</label>
					<div class="layui-input-block" style='font-size:14px;line-height:35px;'>
						0.00 元
					</div>
				</div>
				<div class="layui-form-item">
					<label class="layui-form-label" style='font-size:14px'>预计到账:</label>
					<div class="layui-input-block" style='font-size:14px;line-height:35px;'>
						<?php echo date('Y-m-d', time()+86400*2)?> &nbsp;&nbsp; 1-2个工作日(双休和法定节假日除外)之内到账
					</div>
				</div>
				<div class="layui-form-item">
					<label class="layui-form-label" style='font-size:14px'>提现方式:</label>
					<div class="layui-input-block" style='font-size:14px;line-height:35px;'>
						<a class="layui-btn layui-btn-danger js-withdraw" data-url="/account/withdraw_tx/1">T+0提现</a>
						<a class="layui-btn layui-btn-danger js-withdraw" data-url="/account/withdraw_tx/2" style="background: #ffb8a2;">T+1提现</a>
					</div>
				</div>
				<div class="layui-form-item" style='font-size:14px;line-height:35px;'>
					<label class="layui-form-label" style="color: #fc501b;">温馨提示:</label><br/>
					<div class="layui-input-block">
						1、 申请提现前，如果账户未绑定银行卡，请先在页面点击”添加银行卡“进行绑定。<br>
						2、 提现T+1模式，由平台承担2元/笔的手续费，提现金额需≥100元。<br>
						3、 提现T+0模式，由用户承担2元/笔以及提现金额0.05%的手续费，提现金额需≥100元。<br>
						4、 为保证提现成功，请确保您的银行卡账号及开户行信息准确无误，相关信息可拨打银行客服电话查询。<br>
						5、 提现申请提交成功后，我们将尽快为您处理，T+0模式的资金将于两个小时内到账；T+1模式的资金预计在1个工作日（双休日或法定节假日顺延）到账，请您注意查收。<br>
						6、 根据《反洗钱法》规定，禁止洗钱、信用卡套现、虚假交易等行为。平台对有上述可疑行为的用户发起的提现将采取延迟付款或终止该账户的使用。
					</div>
				</div>
			</div>
			
	</div>
	</div>
</div>
<?php include("foot.php") ?>	
	
<script type="text/javascript">
	var withdraw = withdraw || {};
	
	layui.use(['layer', 'form'], function() {
		var $ = layui.$
		,layer = layui.layer
		,form = layui.form;
		
		withdraw.money_kt = "<?php echo $my['account_money']-$today['money'];?>";
		$('.js-withdraw').click(function(){
			var url = $(this).attr('data-url');
			if(url) {
				var money = parseFloat($('input').val());
				if(money) {
					if(money > parseFloat(withdraw.money_kt)) {
						layer.msg('提现金额超过可提金额', {icon:5,time:1500});
					} else if(money < 100) {
						layer.msg('最小提现金额为100元，请修改金额后再操作', {icon:5,time:1500});
					} else {
						parent.location.href = url + '/' + money;
					}
				} else {
					layer.msg('提现金额不能为空', {icon:5,time:1500});
				}
			} else {
				return false;
			}
			
		});
	});
</script>
</body>
</html>