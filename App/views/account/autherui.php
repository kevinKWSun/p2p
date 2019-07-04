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
			<ul align="left" >
				<li   style="height:20px; margin-top:25px;">
					<label><input type="checkbox" name="agree_one" value="1" checked> 我已阅读并同意 </label><a href="javascript:;" id="agree_one">《鄂托克前旗农商银行网络交易资金账户服务三方协议》</a>
				</li>
				<li    style="height:20px; margin-top:25px;">
					<label><input type="checkbox" name="agree_two" value="1" checked> 我已阅读并同意 </label><a href="javascript:;" id="agree_two">《免密授权书》</a>
				</li>
				<li style="margin-top:25px;">
					<input type="submit" id="contract_submit" name="" value="完善认证" class="xyb" style="cursor:pointer;width:380px;">
				</li>
			</ul>
		</form>
	</div>
</body>
<script type="text/javascript">
	layui.use(['layedit', 'form'], function(){
		var $ = layui.$
		, layer = layui.layer;
		
		$('#agree_one').click(function() {
			window.open('/safe/build_one.html');
			// layer.open({
				// type: 2,
				// title: '鄂托克前旗农商银行网络交易资金账户服务三方协议',
				// shade: 0.1,
				// maxmin: true,
				// area: ['100%', '100%'],
				// fixed: true,
				// content: '/safe/build_one.html'
			// });
		});
		$('#agree_two').click(function() {
			window.open('/safe/build_two.html');
			// layer.open({
				// type: 2,
				// title: '伽满优居间服务合同',
				// shade: 0.1,
				// maxmin: true,
				// area: ['100%', '100%'],
				// fixed: true,
				// content: '/safe/build_two.html'
			// });
		});
		$('#contract_submit').click(function() {
			var url = '/safe/auther.html';
			$.post(url, $('#contractForm').serialize(), function(r) {
				if(r.state == 1) {
					parent.layer.closeAll();
					//location.href = r.url;
					window.open(r.url);
				} else {
					layer.msg(r.message, {time: 1500});
				}
			}, 'json');
			
		});
	});
</script>
</html>