<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>充值-伽满优</title>
    <meta http-equiv="Content-Language" content="zh-cn" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <meta name="viewport" content="width=device-width, maximum-scale=1.0, initial-scale=1.0,initial-scale=1.0,user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="Keywords" content="充值-伽满优,车贷理财,车辆抵押,P2P投资理财,投资理财公司,短期理财,P2P投资理财平台" />
	<meta name="Description" content="充值-伽满优,通过公开透明的规范操作,平台为投资理财人士提供收益合理、安全可靠、高效灵活的车贷理财产品。" />
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
			<h2>账号充值 </h2>
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
			<form id="formrec" method="post" role="form">
				<div class="layui-form-item">
					<label class="layui-form-label" style='font-size:14px'>账户余额</label>
					<div class="layui-input-inline" style='font-size:14px;line-height:35px;'>
						￥ <?php echo $my['account_money']+0;?>
					</div>
				</div>
				<div class="layui-form-item">
					<label class="layui-form-label" style='font-size:14px'>银卡卡号:</label>		
					<div class="online_bank_card">
						<img src="/images/new/image/<?php echo $bank[$my_user['bank_address']];?> ">
						<strong><var>尾号<?php echo mb_substr($my_user['paycard'],-4,4,'utf-8'); ?></var></strong>
					</div>				
				</div>
				<div class="layui-form-item">
					<label class="layui-form-label" style='font-size:14px'>充值金额</label>
					<div class="layui-input-inline">
						<input autocomplete="off" lay-verify="required" value="" placeholder="必填,充值金额" class="layui-input" type="text" onkeyup="value=value.replace(/[^\d.]/g,'');recharge.changefee(this.value);" onblur="value=value.replace(/[^\d.]/g,'');recharge.changefee(this.value);" maxlength="11"/>
					</div>
				</div>
				<div class="layui-form-item">
					<label class="layui-form-label" style='font-size:14px'>充值费用</label>
					<div class="layui-input-inline" style='font-size:14px;line-height:35px;' id="recharge_fee">
						￥ 0.00
					</div>
				</div>
				<div class="layui-form-item">
					<label class="layui-form-label" style='font-size:14px'>充值方式</label>
					<div class="layui-input-block" style='font-size:14px;line-height:35px;'>
						<a class="layui-btn layui-btn-danger" id="js-recharge_wg" data-url="/account/recharge_wg">网银充值</a>
						<?php if(!IS_COMPANY) { ?>
							<a class="layui-btn layui-btn-danger" id="js-recharge_q" data-url="/account/recharge_q" style="background: #ffb8a2;">快捷充值</a>
						<?php } ?>
					</div>
				</div>
				<div class="layui-form-item" style='font-size:14px;line-height:35px;'>
					<label class="layui-form-label">温馨提示</label>
					<div class="layui-input-block">
						<em style="color:Red;">1、因快捷支付支持银行及充值额度有限，建议优先使用网银充值。</em>。<br>2、鄂托克托管银行充值收取0.15%手续费，现暂由平台支付。<br>3、为了您的账户安全，请在充值前进行实名认证、银行卡绑定以及交易密码设置。<br>4、禁止洗钱、信用卡套现、虚假交易等行为，一经发现并确认，将终止该账户的使用。<br>5、充值前请确认您的银行卡限额。如充值成功后未能及时到账，请联系客服：021-62127903。<br>注：快捷支付可支持银行：工商银行、中国银行、建设银行、交通银行、光大银行、广发银行、兴业银行、平安银行、浦发银行、上海银行、邮储银行、中信银行。
					</div>
				</div>
			</form>
		</div>
			
	</div>
	</div>
</div>
<?php include("foot.php") ?>		
	
<script type="text/javascript">
	var recharge = recharge || {};
	layui.use(['layer', 'form'], function() {
		var $ = layui.$
		,layer = layui.layer
		,form = layui.form;
		
		$('#js-recharge_q').click(function(){
			var url = $(this).attr('data-url');
			if(url) {
				var money = $('input').val();
				if(money) {
					parent.location.href = url + '/' + money;
				} else {
					layer.msg('充值金额不能为空', {time:1500});
				}
			} else {
				return false;
			}
			
		});
		$('#js-recharge_wg').click(function(){
			var url = $(this).attr('data-url');
			if(url) {
				var money = $('input').val();
				if(money) {
					parent.layer.closeAll();
					open(url + '/' + money);
				} else {
					layer.msg('充值金额不能为空', {time:1500});
				}
			} else {
				return false;
			}
			
		});
		recharge.changefee = function(money) {
			var format_money = recharge.number_format(money, 2, '.', ',');
			$('#recharge_fee').html('￥ ' + money*0.0015 + ' (现暂由平台支付)');
		}
		recharge.number_format = function(number, decimals, dec_point, thousands_sep) {
			/*
			* 参数说明：
			* number：要格式化的数字
			* decimals：保留几位小数
			* dec_point：小数点符号
			* thousands_sep：千分位符号
			* */
			number = (number + '').replace(/[^0-9+-Ee.]/g, '');
			var n = !isFinite(+number) ? 0 : +number,
				prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
				sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
				dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
				s = '',
				toFixedFix = function (n, prec) {
					var k = Math.pow(10, prec);
					return '' + Math.ceil(n * k) / k;
				};
		 
			s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
			var re = /(-?\d+)(\d{3})/;
			while (re.test(s[0])) {
				s[0] = s[0].replace(re, "$1" + sep + "$2");
			}
		 
			if ((s[1] || '').length < prec) {
				s[1] = s[1] || '';
				s[1] += new Array(prec - s[1].length + 1).join('0');
			}
			return s.join(dec);
		}
		
	});
</script>
</body>
</html>