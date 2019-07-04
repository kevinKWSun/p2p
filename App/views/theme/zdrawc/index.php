<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>伽满优-518理财节</title>
		<link rel="stylesheet" href="/src/zdraw/css/maydraw-new.css" type="text/css" />
		<link rel="stylesheet" href="https://www.jiamanu.com/src/css/layui.css">

		<script type="text/javascript" src="/src/zdraw/js/jquery.min.js"></script>
		<script type="text/javascript" src="/src/zdraw/js/num.js"></script>
		<script type="text/javascript" src="https://www.jiamanu.com/src/layui.js"></script>
		<!--[if lt IE 10]>
			<script type="text/javascript">
				var str = "你的IE浏览器版本太低了,需要更新到IE10以上";
				var str2 = "推荐使用:<a href='https://www.baidu.com/s?ie=UTF-8&wd=%E8%B0%B7%E6%AD%8C%E6%B5%8F%E8%A7%88%E5%99%A8' target='_blank' style='color:blue;'>谷歌</a>,"
						+ "<a href='https://www.baidu.com/s?ie=UTF-8&wd=%E7%81%AB%E7%8B%90%E6%B5%8F%E8%A7%88%E5%99%A8' target='_blank' style='color:blue;'>火狐</a>,"
						+ "其他双核极速模式";
				var contents ="<pre style='text-align:center;height:100%;width:100%;'>" +
						"<br/><br/><strong> "+ str + "</strong><br/><br/>" +str2 + "</pre>";
		 
				layui.use('layer', function(){ //独立版的layer无需执行这一句
					var $ = layui.jquery, layer = layui.layer; //独立版的layer无需执行这一句
					layer.open({
					  type: 1,
					  skin: 'layui-layer-rim', //加上边框
					  area: ['420px', '240px'], //宽高
					  content: contents
					});
				});
			</script>
		<![endif]-->
	</head>
	<body>


		<div class="maydraw-main">
			<div class="drawing">
				<div id="dataNums">
					<ul class="dataNums inrow"><li class="dataOne "><div class="dataBoc"><div class="tt" t="38" style="transition: all 5s ease-in-out 0s; top: -1880px;"><span class="num0">0</span> <span class="num1">1</span> <span class="num2">2</span> <span class="num3">3</span> <span class="num4">4</span><span class="num5">5</span> <span class="num6">6</span> <span class="num7">7</span> <span class="num8">8</span> <span class="num9">9</span><span class="num0">0</span> <span class="num1">1</span> <span class="num2">2</span> <span class="num3">3</span> <span class="num4">4</span><span class="num5">5</span> <span class="num6">6</span> <span class="num7">7</span> <span class="num8">8</span> <span class="num9">9</span></div></div></li><li class="dataOne "><div class="dataBoc"><div class="tt" t="38" style="transition: all 2s ease-in-out 0s; top: -1880px;"><span class="num0">0</span> <span class="num1">1</span> <span class="num2">2</span> <span class="num3">3</span> <span class="num4">4</span><span class="num5">5</span> <span class="num6">6</span> <span class="num7">7</span> <span class="num8">8</span> <span class="num9">9</span><span class="num0">0</span> <span class="num1">1</span> <span class="num2">2</span> <span class="num3">3</span> <span class="num4">4</span><span class="num5">5</span> <span class="num6">6</span> <span class="num7">7</span> <span class="num8">8</span> <span class="num9">9</span></div></div></li><li class="dataOne "><div class="dataBoc"><div class="tt" t="38" style="transition: all 2s ease-in-out 0s; top: -1880px;"><span class="num0">0</span> <span class="num1">1</span> <span class="num2">2</span> <span class="num3">3</span> <span class="num4">4</span><span class="num5">5</span> <span class="num6">6</span> <span class="num7">7</span> <span class="num8">8</span> <span class="num9">9</span><span class="num0">0</span> <span class="num1">1</span> <span class="num2">2</span> <span class="num3">3</span> <span class="num4">4</span><span class="num5">5</span> <span class="num6">6</span> <span class="num7">7</span> <span class="num8">8</span> <span class="num9">9</span></div></div></li></ul>
				</div>
				<div id="dataNums2"> </div>
				<div class="residue-num">
					<?php echo isset($zdrawc) ? $zdrawc['num'] : 0; ?>
				</div>
				<div class="immediately ">
				</div>
			</div>
			<div class="storage">
		
			</div>

		</div>
	</body>
	<script>
	    var zdrawc = zdrawc || {};
		zdrawc.flag = true;
		$(function() {
			$(".immediately").click(function() {
				zdrawc.flag = false;
				$(".immediately").css('pointer-events',"none");
				var login = "<?php echo defined('QUID') ? QUID : 0; ?>";
				if(login == 0) {
					// 跳转登陆
					layer.confirm('请先登陆.', {title:'登陆提示'}, function(index){
						location.href= '/suny/login.html?ret_url=/zdrawc.html';
					});
				} else {
					var num = parseInt($('.residue-num').html());
					if(!num) {
						layer.confirm('剩余抽奖次数不足，立刻抽奖次数.', {title:'出借提示'}, function(index){
							location.href= '/invest.html';
						});
						zdrawc.flag = true;
						$(".immediately").css('pointer-events',"auto");
					} else {
						var url = '/zdrawc/random';
						$.post(url, function(r) {
							if(r.state == 1) {
								if(r.message) {
									$("#dataNums").empty();
									$("#dataNums2").empty();
									$("#dataNums").rollNumDaq({
										deVal: r.message
									});
									$('.residue-num').html(num*1 - 1);
									setTimeout(function() {
										zdrawc.flag = true;
										$(".immediately").css('pointer-events',"auto");
									}, 6 * 1000 + 500);
								}
							} else {
								layer.confirm(r.message, {title:'错误提示'}, function(index){
									location.reload();
								});
							}
						}, 'json');
					}
				}
				// zdrawc.flag = true;
				// $(".immediately").css('pointer-events',"auto");
			});

		});
		
	</script>
	
	<script>
		layui.use('layer', function(){ //独立版的layer无需执行这一句
			var $ = layui.jquery, layer = layui.layer; //独立版的layer无需执行这一句
			
			var login = "<?php echo defined('QUID') ? QUID : 0; ?>";
			$('.storage').on('click', function(){
				if(zdrawc.flag) {
					if(login == 0) {
						// 跳转登陆
						layer.confirm('请先登陆.', {title:'登陆提示'}, function(index){
							location.href= '/suny/login.html?ret_url=/zdrawc.html';
						});
					} else {
						var url = '/zdrawc/getmoney';
						$.post(url, function(r) {
							if(r.state == 1) {
								layer.confirm('存储金额'+r.message+'元', {title:'提示'}, function(index){
									layer.close(index);
								});
							} else {
								layer.confirm(r.message, {title:'错误提示'}, function(index){
									location.reload();
								});
							}
						}, 'json');
						
					}
				}
			});
		});
</script>
</html>
