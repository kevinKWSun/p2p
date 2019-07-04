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
    <link href="/src/css/layui.css" rel="stylesheet" />
    <script src="/src/layui.js"></script>
</head>
<body>
<div class="main-wrap" style='padding:10px;'>
	<blockquote class="layui-elem-quote" style='border-left:5px solid #FF5722'>
		<h3>提现</h3>
	</blockquote>
	<div class="y-role">
		<div class="fhui-admin-table-container">
			<div class="layui-form-item">
				<label class="layui-form-label" style='font-size:14px'>可用余额</label>
				<div class="layui-input-inline" style='font-size:14px;line-height:35px;'>
					￥ <?php echo $my['account_money']+0;?>元
				</div>
			</div>
			<div class="layui-form-item">
				<label class="layui-form-label" style='font-size:14px'>可提金额</label>
				<div class="layui-input-inline" style='font-size:14px;line-height:35px;'>
					￥ <?php echo (($my['account_money']-$today['money']) > 0) ? ($my['account_money']-$today['money']) : 0; ?>元
				</div>
			</div>
			<div class="layui-form-item">
				<label class="layui-form-label" style='font-size:14px'>提现金额</label>
				<div class="layui-input-inline">
					<input autocomplete="off" lay-verify="required" value="" placeholder="必填,提现金额" class="layui-input" type="text" onkeyup="value=value.replace(/[^\d.]/g,'');" onblur="value=value.replace(/[^\d.]/g,'');" maxlength="11"/>
				</div>
			</div>
			<div class="layui-form-item">
				<label class="layui-form-label" style='font-size:14px'>预计到账</label>
				<div class="layui-input-block" style='font-size:14px;line-height:35px;'>
					<?php echo date('Y-m-d', time()+86400*2)?>  1-2个工作日(双休和法定节假日除外)之内到账
				</div>
			</div>
			<div class="layui-form-item">
				<label class="layui-form-label" style='font-size:14px'></label>
				<div class="layui-input-block" style='font-size:14px;line-height:35px;'>
					<a class="layui-btn layui-btn-danger js-withdraw" data-url="/account/withdraw_tx/1">T+0提现</a>
					<a class="layui-btn layui-btn-danger js-withdraw" data-url="/account/withdraw_tx/2">T+1提现</a>
				</div>
			</div>
			<div class="layui-form-item" style='font-size:14px;line-height:35px;'>
				<label class="layui-form-label">温馨提示</label>
				<div class="layui-input-block">
					1、 申请提现前，如果账户未绑定银行卡，请先在页面点击”添加银行卡“进行绑定。<br>
					2、 提现T+1模式，由平台承担2元/笔的手续费，提现金额需≥100元。<br>
					3、 提现T+0模式，由<em style="color:Red;">用户</em>承担2元/笔以及提现金额0.05%的手续费，提现金额需≥100元。<br>
					4、 为保证提现成功，请确保您的银行卡账号及开户行信息准确无误，相关信息可拨打银行客服电话查询。<br>
					5、 提现申请提交成功后，我们将尽快为您处理，T+0模式的资金将于两个小时内到账；T+1模式的资金预计在1个工作日（双休日或法定节假日顺延）到账，请您注意查收。<br>
					6、 根据《反洗钱法》规定，禁止洗钱、信用卡套现、虚假交易等行为。平台对有上述可疑行为的用户发起的提现将采取延迟付款或终止该账户的使用。
				</div>
			</div>
		</div>
	</div>
</div>
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
						layer.msg('提现金额超过可提金额', {time:1500});
					} else if(money < 100) {
						layer.msg('最小提现金额为100元，请修改金额后再操作', {time:1500});
					} else {
						parent.location.href = url + '/' + money;
					}
				} else {
					layer.msg('提现金额不能为空', {time:1500});
				}
			} else {
				return false;
			}
			
		});
	});
</script>
</body>
</html>