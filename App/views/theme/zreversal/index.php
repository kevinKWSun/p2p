<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>伽满优-卡片翻转</title>
		<link rel="stylesheet" href="/src/reversal/css/reversal.css" type="text/css" />
		<link rel="stylesheet" href="/src/css/layui.css" type="text/css">
<style>
	.sponsorFlip,.sponsorData{
		height: 100%;
		cursor: pointer;
	}
	.reversal-table-a {
		cursor: pointer;
	}
	.reversal-table-a-1 {
		cursor: pointer;
	}
	.reversal-table-b {
		color: #b1b1b1;
		cursor: auto;
	}
	a:hover {
		text-decoration: none;
	}
	.out {
		background-color: none;
	}
</style>
	</head>
	<body>
		<div class="reversal-main">
			<div class="reversal-rule">
				<img src="/src/reversal/img/rule.png">
			</div>
			<div class="reversal-card" id="card">
				<ul>
					<?php echo $html; ?>
				</ul>
				<div class="login-reversal">
					<?php if(!QUID) { ?>
						<h2>您尚未登录，<a href="/suny/login.html?ret_url=/zreversal.html">去登录</a></h2>
						<h3> <span style="cursor:pointer;"><a href="/suny/login.html?ret_url=/zreversal.html" style="color:#fff;">开始翻牌</a></span></h3>
					<?php } else { ?>
						<h3> <span style="cursor:pointer;" id="reverse">开始翻牌</span></h3>
					<?php } ?>
					
					
					<h4>您当前剩余翻牌数（<var><?php echo isset($zreversal['num']) ? $zreversal['num'] : 0; ?></var>） | <a href="javascript:;" id="refresh">刷新试试</a></h4>
				</div>
			</div>
			<div id="js-container">
				<?php if (isset($order['html'])) {
					echo $order['html'];
				} ?>
			</div>
			
		</div>
	</body>
	<script type="text/javascript" src="/src/reversal/js/jquery-1.11.0.min.js"></script>
	<script type="text/javascript" src="/src/reversal/js/jquery-ui.min.js"></script>
	<script type="text/javascript" src="/src/reversal/js/jquery.flip.min.js"></script>
	<script type="text/javascript" src="/src/layui.all.js"></script>
	<script type="text/javascript">
		var zreversal = zreversal || {};
		;!function(){
			var layer = layui.layer;
		}();
		$(function() {
			var layer = layui.layer;
			console.log(layer);
			$('#refresh').on('click', function() {
				location.href='/zreversal?rel='+new Date().getTime()+'#card';
			});
			$(document).on('click', '.exchange', function() {
				var elem = $(this);
				var id = $(this).attr('data-id');
				var url = $(this).attr('data-url');
				$.post(url, {id : id}, function(r) {
					if(r.state == 1) {
						elem.removeClass('.exchange');
						elem.addClass('reversal-table-b');
						layer.msg(r.message);
					} else {
						layer.msg(r.message);
					}
				});
			});
			$(document).on('click', '.pagination li a', function() {
				var url = $(this).attr('data-href');
				$.get(url, function(r) {
					if(r.state == 1) {
						$('#js-container').html(r.message);
					}
				}, 'json');
			});
			$(document).on('click', '#js-page', function() {
				var url = $(this).attr('data-href');
				var page = parseInt($('input[name=page]').val());
				if(page > 0) {
					$.get(url + page + '.html', function(r) {
						if(r.state == 1) {
							$('#js-container').html(r.message);
						}
					}, 'json');
				}
			});
			$('.sponsorFlip').click(function() {
				var uid = '<?php echo defined('QUID') ? QUID : 0; ?>';
				var elem =  $(this);
				if(elem.hasClass('out')){
					return true;					
				}
				elem.css('pointer-events',"none");
				elem.unbind('click');
				var index = elem.parent().index();
				if(uid > 0) {
					if (elem.data('flipped')) {
						elem.revertFlip();
						elem.data('flipped', false)
					} else {
						var url = '/zreversal/reversal';
						$.post(url, {'index': index}, function(r) {
							if(r.state == 1) {
								elem.siblings('.sponsorData').find('img').attr('src', r.message);
								elem.flip({
									direction: 'lr',
									speed: 350,
									onBefore: function() {
										elem.html(elem.siblings('.sponsorData').html());
									},
									onEnd: function() {
										var style = "background-color: rgba(0,0,0,0);visibility:visible;cursor: auto;";
										elem.attr("style", style);
									}
								});
								elem.data('flipped', true);
								//elem.unbind('click');
								elem.addClass('out');
								// 翻牌次数对应减少
								var num = parseInt($('.login-reversal h4').find('var').html()) - 1;
								num = num >= 0 ? num : 0;
								$('.login-reversal h4').find('var').html(num);
								
							} else {
								layer.msg(r.message);
								return;
							}
						}, 'json');
						
					}
					
				} else {
					location.href = '/suny/login.html?ret_url=/zreversal.html';
				}
				
			});
			$('#reverse').click(function() {
				$("#reverse").css('pointer-events',"none");
				var url = '/zreversal/reversal';
				$.post(url, {'all': true}, function(r) {
					if(r.state == 1) {
						var nums = 0;
						$(".reversal-card ul li div.sponsorFlip").each(function(index){
							if(!$(this).hasClass('out')) {
								var elem = $(this);
								elem.siblings('.sponsorData').find('img').attr('src', r.message[index]);
								zreversal.card(elem);
								nums++;
							}
						});
						// 翻牌次数对应减少
						var num = parseInt($('.login-reversal h4').find('var').html()) - nums;
						num = num >= 0 ? num : 0;
						$('.login-reversal h4').find('var').html(num);
					} else {
						layer.msg(r.message);
						return;
					}
				}, 'json');
				
			});
			zreversal.card = function(elem) {
				if(elem.hasClass('out')){
					return true;					
				}
				if (elem.data('flipped')) {
					elem.revertFlip();
					elem.data('flipped', false);
				} else {
					elem.flip({
						direction: 'lr',
						speed: 350,
						onBefore: function() {
							elem.html(elem.siblings('.sponsorData').html());
						},
						onEnd: function() {
							var style = "background-color: rgba(0,0,0,0);visibility:visible;cursor: auto;";
							elem.attr("style", style);
						}
					});
					elem.data('flipped', true);
				}
				elem.addClass('out');
			}
		});
	</script>

</html>
