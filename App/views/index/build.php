<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<title>伽满优-资金第三方存管，安全透明高效的车贷理财平台</title>
		<meta name="Keywords" content="伽满优，车贷理财，车辆抵押,P2P投资理财,投资理财公司,短期理财,P2P投资理财平台" />
		<meta name="Description" content="伽满优，通过公开透明的规范操作，平台为投资理财人士提供收益合理、安全可靠、高效灵活的车贷理财产品。" />
		<link href="/favicon.ico" rel="SHORTCUT ICON" />
		<link href="/images/default.css" rel="stylesheet" type="text/css" />
		<link href="/images/index.css" rel="stylesheet" type="text/css" />
		<link href="/src/css/layui.css" rel="stylesheet" />
		<script src="/src/layui.js"></script>
</head>
<body style="overflow-x:hidden">
	<div class="dl_nr" style="margin-left:35px;width:400px;">
		<form id="contractForm" onsubmit="return false;">
			<input type="hidden" name="merOrderNo" value="<?php echo $merOrderNo; ?>" />
			<ul align="left" >
				<li   style="height:20px; margin-top:25px;">
					<label><input type="checkbox" name="agree_one" value="1"> 同意并签署伽满优借款合同 </label><a href="javascript:;" id="agree_one">《伽满优借款合同》</a>
				</li>
				<li    style="height:20px; margin-top:25px;">
					<label><input type="checkbox" name="agree_two" value="1"> 同意并签署伽满优居间服务合同 </label><a href="javascript:;" id="agree_two">《伽满优居间服务合同》</a>
				</li>
				<li style="height:20px; margin-top:25px;" > 
					<input name="pcode" type="text" class="in1 in2" placeholder="请输入手机验证码" style="float:none;" />
					&nbsp;&nbsp;<input id="js-yqbcode" type="button" class="hqyzm_biao" value="点击获取验证码" style="float:none;" />
				</li>
				<li style="height:20px; margin-top:25px;">
					<input type="submit" id="contract_submit" name="" value="签署" class="xyb" style="cursor:pointer;">
				</li>
			</ul>
		</form>
	</div>
</body>
<script type="text/javascript">
	layui.use(['layedit', 'form'], function(){
		var $ = layui.$
		, layer = layui.layer;
		
		$('#js-yqbcode').click(function() {
			builddjs.settime(this);
			$.post('/invest/send_code.html', {}, function(r){
				var icon = r.message == '成功' ? 6 : 5;
				layer.msg(r.message, {icon : icon, time: 1500});
				return;
			}, 'json');
		});
		$('#agree_one').click(function() {
			var flag = $('input[name=merOrderNo]').val();
			layer.open({
				type: 2,
				title: '伽满优借款合同',
				shade: 0.1,
				maxmin: true,
				area: ['45%', '90%'],
				fixed: true,
				content: '/invest/build_one/' + flag + '.html'
			});
		});
		$('#agree_two').click(function() {
			var flag = $('input[name=merOrderNo]').val();
			layer.open({
				type: 2,
				title: '伽满优居间服务合同',
				shade: 0.1,
				maxmin: true,
				area: ['45%', '90%'],
				fixed: true,
				content: '/invest/build_two/' + flag + '.html'
			});
		});
		$('#contract_submit').click(function() {
			$('#contract_submit').attr("disabled",true);
			$('#contract_submit').val('签署中');
			$.post('/invest/contract_submit.html', $('#contractForm').serialize(), function(r) {
				var icon = r.state ? 6 : 5;
				layer.msg(r.message, {icon: icon, time : 1500}, function() {
					if(r.state == 1) {
						parent.location.href = r.url;
					} else {
						$('#contract_submit').attr("disabled",false);
						$('#contract_submit').val('签署');
					}
				});
			}, 'json');
		});
		
		var builddjs = builddjs || {};
		
		builddjs.countdown = 60;
		builddjs.settime = function(obj) {
		    if (builddjs.countdown == 0) {
		        obj.removeAttribute("disabled");   
		        obj.value="免费获取验证码";
				builddjs.countdown = 60;
		        return;
		    } else {
		        obj.setAttribute("disabled", true);
		        obj.value="重新发送(" + builddjs.countdown + ")";
				builddjs.countdown--;
		    }
			setTimeout(function() {
				builddjs.settime(obj) 
			},1000);
		}
	});
</script>
</html>