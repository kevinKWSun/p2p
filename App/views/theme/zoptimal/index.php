<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>伽满优-优果升级</title>
		<link rel="stylesheet" href="/src/optimal/css/optimal.css" type="text/css" />
		 <link rel="stylesheet" href="/src/optimal/css/simpleAlert.css"> 
		<script src="/src/optimal/js/simpleAlert.js"></script>

		 <link href="https://www.jiamanu.com/images/new/index.css" rel="stylesheet" type="text/css" />
		<script src="https://www.jiamanu.com/images/jquery-1.7.2.min.js"></script>		
	</head>
	<style>
		.header  a{
			color: #000000;
		}
	</style>
	<body>
		<div id="optimal-main">
			<div class="activity-rule">
				<img src="/src/optimal/img/rule.png" >
			</div>
			<div class="optimal-progress" data-rounds="<?php echo isset($zoptimal['rounds']) ? $zoptimal['rounds'] : 0; ?>" data-num="<?php echo isset($zoptimal['num']) ? $zoptimal['num'] : 0; ?>" data-round="<?php echo isset($zoptimal['round']) ? $zoptimal['round'] : 0; ?>">
				<ul>
					<li><i class="<?php if(!QUID || !isset($zoptimal) || (isset($zoptimal) && $zoptimal['num'] == 0)) { echo 'active '; } ?>progress-car-1"><var><?php if(!QUID || !isset($zoptimal) || (isset($zoptimal) && $zoptimal['num'] == 0)) { echo '0'; } ?></var></i></li>
					<li><span class="<?php echo (isset($detail_count) && in_array(1, $detail_count)) ? 'open-copper-extra-small-box' : 'copper-extra-small-box'; ?>" id="treasure-1"></span>
						<?php echo (isset($zoptimal) && $zoptimal['num'] == 1) ? '<i class="active progress-car-2"><var>1</var></i>' : '<i class="progress-car-2"><var></var></i>'; ?>
					</li>
					<li><span class="<?php echo (isset($detail_count) && in_array(2, $detail_count)) ? 'open-copper-small-box' : 'copper-small-box'; ?>" id="treasure-2"></span>
						<?php echo (isset($zoptimal) && $zoptimal['num'] == 2) ? '<i class="active progress-car-3"><var>2</var></i>' : '<i class="progress-car-3"><var></var></i>'; ?>
					</li>
					<li><span class="<?php echo (isset($detail_count) && in_array(3, $detail_count)) ? 'open-copper-medium-box' : 'copper-medium-box'; ?>" id="treasure-3"></span>
						<?php echo (isset($zoptimal) && $zoptimal['num'] == 3) ? '<i class="active progress-car-4"><var>3</var></i>' : '<i class="progress-car-4"><var></var></i>'; ?>
					</li>
					<li><span class="<?php echo (isset($detail_count) && in_array(4, $detail_count)) ? 'open-copper-big-box' : 'copper-big-box'; ?>" id="treasure-4"></span>
						<?php echo (isset($zoptimal) && $zoptimal['num'] == 4) ? '<i class="active progress-car-5"><var>5</var></i>' : '<i class="progress-car-5"><var></var></i>'; ?>
					</li>
					<li><span class="<?php echo (isset($detail_count) && in_array(5, $detail_count)) ? 'open-silve-extra-small-box' : 'silve-extra-small-box'; ?>" id="treasure-5"></span>
						<?php echo (isset($zoptimal) && $zoptimal['num'] == 5) ? '<i class="active progress-car-6"><var>7</var></i>' : '<i class="progress-car-6"><var></var></i>'; ?>
					</li>
					<li><span class="<?php echo (isset($detail_count) && in_array(6, $detail_count)) ? 'open-silve-small-box' : 'silve-small-box'; ?>" id="treasure-6"></span>
						<?php echo (isset($zoptimal) && $zoptimal['num'] == 6) ? '<i class="active progress-car-7"><var>10</var></i>' : '<i class="progress-car-7"><var></var></i>'; ?>
					</li>
					<li><span class="<?php echo (isset($detail_count) && in_array(7, $detail_count)) ? 'open-silve-medium-box' : 'silve-medium-box'; ?>" id="treasure-7"></span>
						<?php echo (isset($zoptimal) && $zoptimal['num'] == 7) ? '<i class="active progress-car-8"><var>15</var></i>' : '<i class="progress-car-8"><var></var></i>'; ?>
					</li>
					<li><span class="<?php echo (isset($detail_count) && in_array(8, $detail_count)) ? 'open-silve-big-box' : 'silve-big-box'; ?>" id="treasure-8"></span>
						<?php echo (isset($zoptimal) && $zoptimal['num'] == 8) ? '<i class="active progress-car-9"><var>20</var></i>' : '<i class="progress-car-9"><var></var></i>'; ?>
					</li>
					<li><span class="<?php echo (isset($detail_count) && in_array(9, $detail_count)) ? 'open-gold-extra-small-box' : 'gold-extra-small-box'; ?>" id="treasure-9"></span>
						<?php echo (isset($zoptimal) && $zoptimal['num'] == 9) ? '<i class="active progress-car-10"><var>30</var></i>' : '<i class="progress-car-10"><var></var></i>'; ?>
					</li>
					<li><span class="<?php echo (isset($detail_count) && in_array(10, $detail_count)) ? 'open-gold-small-box' : 'gold-small-box'; ?>" id="treasure-10"></span>
						<?php echo (isset($zoptimal) && $zoptimal['num'] == 10) ? '<i class="active progress-car-11"><var>40</var></i>' : '<i class="progress-car-11"><var></var></i>'; ?>
					</li>
					<li><span class="<?php echo (isset($detail_count) && in_array(11, $detail_count)) ? 'open-gold-medium-box' : 'gold-medium-box'; ?>" id="treasure-11"></span>
						<?php echo (isset($zoptimal) && $zoptimal['num'] == 11) ? '<i class="active progress-car-12"><var>50</var></i>' : '<i class="progress-car-12"><var></var></i>'; ?>
					</li>
					<li><span class="<?php echo (isset($detail_count) && in_array(12, $detail_count)) ? 'open-gold-big-box' : 'gold-big-box'; ?>" id="treasure-12"></span>
						<?php echo (isset($zoptimal) && $zoptimal['num'] == 12) ? '<i class="active progress-car-13"><var>60</var></i>' : '<i class="progress-car-13"><var></var></i>'; ?>
					</li>
					<li><span class="<?php echo (isset($detail_count) && in_array(13, $detail_count)) ? 'open-jewel-small-box' : 'jewel-small-box'; ?>" id="treasure-13"></span>
						<?php echo (isset($zoptimal) && $zoptimal['num'] == 13) ? '<i class="active progress-car-14"><var>80</var></i>' : '<i class="progress-car-14"><var></var></i>'; ?>
					</li>
					<li><span class="<?php echo (isset($detail_count) && in_array(14, $detail_count)) ? 'open-jewel-medium-box' : 'jewel-medium-box'; ?>" id="treasure-14"></span>
						<?php echo (isset($zoptimal) && $zoptimal['num'] == 14) ? '<i class="active progress-car-15"><var>100</var></i>' : '<i class="progress-car-15"><var></var></i>'; ?>
					</li>
					<li><span class="<?php echo (isset($detail_count) && in_array(15, $detail_count)) ? 'open-jewel-big-box' : 'jewel-big-box'; ?>" id="treasure-15"></span>
						<?php echo (isset($zoptimal) && $zoptimal['num'] == 15) ? '<i class="active progress-car-16"><var>150</var></i>' : '<i class="progress-car-16"><var></var></i>'; ?>
					</li>
				</ul>
			</div>
			<div class="card-optimal">
				<i></i>
				<img src="/src/optimal/img/card.png" >
				<span>现有卡片( <var><?php echo isset($card_num) ? $card_num : 0; ?></var> )</span>
			<div>
			<div class="table-data">
				<table border="" cellspacing="" cellpadding="">
					<tr>
						<th>出借金额</th>
						<th>现金红包</th>
						<th>出借红包</th>
						<th>幸运树增益</th>
						<th>实物奖励</th>
					</tr>
					<tr>
						<td>1万</td>
						<td>120元<?php echo isset($number[1][1]) ? '<var>(*'.$number[1][1].')</var>' : ''; ?></td>
						<td>130元*1<?php echo isset($number[1][2]) ? '<var>(*'.$number[1][2].')</var>' : ''; ?></td>
						<td>优果*1<?php echo isset($number[1][3]) ? '<var>(*'.$number[1][3].')</var>' : ''; ?></td>
						<td>150元京东卡<?php echo isset($number[1][4]) ? '<var>(*'.$number[1][4].')</var>' : ''; ?></td>
					</tr>
					<tr>
						<td>2万</td>
						<td>123元<?php echo isset($number[2][1]) ? '<var>(*'.$number[2][1].')</var>' : ''; ?></td>
						<td>135元*1<?php echo isset($number[2][2]) ? '<var>(*'.$number[2][2].')</var>' : ''; ?></td>
						<td>优果*1<?php echo isset($number[2][3]) ? '<var>(*'.$number[2][3].')</var>' : ''; ?></td>
						<td>160元京东卡<?php echo isset($number[2][4]) ? '<var>(*'.$number[2][4].')</var>' : ''; ?></td>
					</tr>
					<tr>
						<td>3万</td>
						<td>126元<?php echo isset($number[3][1]) ? '<var>(*'.$number[3][1].')</var>' : ''; ?></td>
						<td>140元*1<?php echo isset($number[3][2]) ? '<var>(*'.$number[3][2].')</var>' : ''; ?></td>
						<td>优果*1<?php echo isset($number[3][3]) ? '<var>(*'.$number[3][3].')</var>' : ''; ?></td>
						<td>170元京东卡<?php echo isset($number[3][4]) ? '<var>(*'.$number[3][4].')</var>' : ''; ?></td>
					</tr>
					<tr>
						<td>5万</td>
						<td>261元<?php echo isset($number[4][1]) ? '<var>(*'.$number[4][1].')</var>' : ''; ?></td>
						<td>295元*1<?php echo isset($number[4][2]) ? '<var>(*'.$number[4][2].')</var>' : ''; ?></td>
						<td>优果*1<?php echo isset($number[4][3]) ? '<var>(*'.$number[4][3].')</var>' : ''; ?></td>
						<td>370元京东卡<?php echo isset($number[4][4]) ? '<var>(*'.$number[4][4].')</var>' : ''; ?></td>
					</tr>
					<tr>
						<td>7万</td>
						<td> 273元<?php echo isset($number[5][1]) ? '<var>(*'.$number[5][1].')</var>' : ''; ?></td>
						<td>315元*1<?php echo isset($number[5][2]) ? '<var>(*'.$number[5][2].')</var>' : ''; ?></td>
						<td>梨*2<?php echo isset($number[5][3]) ? '<var>(*'.$number[5][3].')</var>' : ''; ?></td>
						<td>410元京东卡<?php echo isset($number[5][4]) ? '<var>(*'.$number[5][4].')</var>' : ''; ?></td>
					</tr>		
					<tr>
						<td>10万</td>
						<td>432元<?php echo isset($number[6][1]) ? '<var>(*'.$number[6][1].')</var>' : ''; ?></td>
						<td>510元*1<?php echo isset($number[6][2]) ? '<var>(*'.$number[6][2].')</var>' : ''; ?></td>
						<td>优果*1、梨*2<?php echo isset($number[6][3]) ? '<var>(*'.$number[6][3].')</var>' : ''; ?></td>
						<td>690元京东卡<?php echo isset($number[6][4]) ? '<var>(*'.$number[6][4].')</var>' : ''; ?></td>
					</tr>
					<tr>
						<td>15万</td>
						<td>780元<?php echo isset($number[7][1]) ? '<var>(*'.$number[7][1].')</var>' : ''; ?></td>
						<td>950元*1<?php echo isset($number[7][2]) ? '<var>(*'.$number[7][2].')</var>' : ''; ?></td>
						<td>优果*3、橘子*1<?php echo isset($number[7][3]) ? '<var>(*'.$number[7][3].')</var>' : ''; ?></td>
						<td>1,350元京东卡<?php echo isset($number[7][4]) ? '<var>(*'.$number[7][4].')</var>' : ''; ?></td>
					</tr>
					<tr>
						<td>20万</td>
						<td>855元<?php echo isset($number[8][1]) ? '<var>(*'.$number[8][1].')</var>' : ''; ?></td>
						<td>1,000元*1<?php echo isset($number[8][2]) ? '<var>(*'.$number[8][2].')</var>' : ''; ?></td>
						<td>优果*5、火龙果*2<?php echo isset($number[8][3]) ? '<var>(*'.$number[8][3].')</var>' : ''; ?></td>
						<td>1,600元惊喜礼遇<?php echo isset($number[8][4]) ? '<var>(*'.$number[8][4].')</var>' : ''; ?></td>
					</tr>
					<tr>
						<td>30万</td>
						<td>1,935元<?php echo isset($number[9][1]) ? '<var>(*'.$number[9][1].')</var>' : ''; ?></td>
						<td>1,000元*2、500元*1<?php echo isset($number[9][2]) ? '<var>(*'.$number[9][2].')</var>' : ''; ?></td>
						<td> 优果*10、葡萄*5<?php echo isset($number[9][3]) ? '<var>(*'.$number[9][3].')</var>' : ''; ?></td>
						<td>3,950元惊喜礼遇<?php echo isset($number[9][4]) ? '<var>(*'.$number[9][4].')</var>' : ''; ?></td>
					</tr>
					<tr>
						<td>40万</td>
						<td>2,235元<?php echo isset($number[10][1]) ? '<var>(*'.$number[10][1].')</var>' : ''; ?></td>
						<td>1,000元*3<?php echo isset($number[10][2]) ? '<var>(*'.$number[10][2].')</var>' : ''; ?></td>
						<td> 优果*15、桃子*3<?php echo isset($number[10][3]) ? '<var>(*'.$number[10][3].')</var>' : ''; ?></td>
						<td>4,950元惊喜礼遇<?php echo isset($number[10][4]) ? '<var>(*'.$number[10][4].')</var>' : ''; ?></td>
					</tr>
					<tr>
						<td>50万</td>
						<td>2,535元<?php echo isset($number[11][1]) ? '<var>(*'.$number[11][1].')</var>' : ''; ?></td>
						<td>1,000元*3、500元*1<?php echo isset($number[11][2]) ? '<var>(*'.$number[11][2].')</var>' : ''; ?></td>
						<td> 优果*25、桃子*3<?php echo isset($number[11][3]) ? '<var>(*'.$number[11][3].')</var>' : ''; ?></td>
						<td>5,950元惊喜礼遇<?php echo isset($number[11][4]) ? '<var>(*'.$number[11][4].')</var>' : ''; ?></td>
					</tr>
					<tr>
						<td>60万</td>
						<td>2,835元<?php echo isset($number[12][1]) ? '<var>(*'.$number[12][1].')</var>' : ''; ?></td>
						<td>1,000元*4<?php echo isset($number[12][2]) ? '<var>(*'.$number[12][2].')</var>' : ''; ?></td>
						<td>  优果*30、桃子*3<?php echo isset($number[12][3]) ? '<var>(*'.$number[12][3].')</var>' : ''; ?></td>
						<td>6,950元惊喜礼遇<?php echo isset($number[12][4]) ? '<var>(*'.$number[12][4].')</var>' : ''; ?></td>
					</tr>
					<tr>
						<td>80万</td>
						<td> 6,570元<?php echo isset($number[13][1]) ? '<var>(*'.$number[13][1].')</var>' : ''; ?></td>
						<td>1,000元*9、500元*1<?php echo isset($number[13][2]) ? '<var>(*'.$number[13][2].')</var>' : ''; ?></td>
						<td> 优果*10、金苹果*1<?php echo isset($number[13][3]) ? '<var>(*'.$number[13][3].')</var>' : ''; ?></td>
						<td>17,640元惊喜礼遇<?php echo isset($number[13][4]) ? '<var>(*'.$number[13][4].')</var>' : ''; ?></td>
					</tr>
					<tr>
						<td>100万</td>
						<td>7,770元<?php echo isset($number[14][1]) ? '<var>(*'.$number[14][1].')</var>' : ''; ?></td>
						<td>1,000元*11、500元*1<?php echo isset($number[14][2]) ? '<var>(*'.$number[14][2].')</var>' : ''; ?></td>
						<td> 优果*15、金苹果*1<?php echo isset($number[14][3]) ? '<var>(*'.$number[14][3].')</var>' : ''; ?></td>
						<td>20,900元惊喜礼遇<?php echo isset($number[14][4]) ? '<var>(*'.$number[14][4].')</var>' : ''; ?></td>
					</tr>
					<tr>
						<td>150万</td>
						<td>24,675元<?php echo isset($number[15][1]) ? '<var>(*'.$number[15][1].')</var>' : ''; ?></td>
						<td>1,000元*37、500元*1<?php echo isset($number[15][2]) ? '<var>(*'.$number[15][2].')</var>' : ''; ?></td>
						<td> 优果*10、金苹果*4<?php echo isset($number[15][3]) ? '<var>(*'.$number[15][3].')</var>' : ''; ?></td>
						<td>69,750元惊喜礼遇<?php echo isset($number[15][4]) ? '<var>(*'.$number[15][4].')</var>' : ''; ?></td>
					</tr>					
				</table>
			</div>
		</div>
	</body>
	<script>
		$(function () {
			var zoptimal = zoptimal || {};
			zoptimal.rounds = $('.optimal-progress').attr('data-rounds');
			zoptimal.round = $('.optimal-progress').attr('data-round');
			zoptimal.num = $('.optimal-progress').attr('data-num');
			$(".optimal-progress ul li i:not(.active) var").remove();
			//单次单选弹框
			$(".optimal-progress li span").die().live('click', function () {
				var quid = "<?php echo QUID ? QUID : 0; ?>";
				if(quid == '' || quid == 0 || quid == 'undefined') {
					zoptimal.alert('<div class="pop-content"><p style="margin-right: 50px;text-align: center;">请登录您的账号</p></div>', 1, "/suny/login.html?ret_url=/zoptimal.html");
					return;
				} else {
					var num = $(this).attr("id").split('-')[1];
					if(num*1 <= zoptimal.num*1) {
						var classname = $(this).prop("className");
						var arr = classname.split('-');
						if(arr[0] == 'open'){
							return;
						}
						var click_obj = $(this);
						$.post('/zoptimal/open_treasure', {num:num}, function(r) {
							if(r.state == 1) {
								zoptimal.alert(r.message.msg, 2, "");
								// 对应的商品数量增加
								var obj = $('.table-data tr:eq('+(num*1)+') td:eq('+(r.message.column*1)+')');
								if(r.message.column == 5) {
									var card_num = $('.card-optimal span var').html();
									card_num = card_num*1 + 1;
									$('.card-optimal span var').html(card_num);
									
								} else {
									if(obj.find('var').length) {
										var td_num = parseInt(obj.find('var').text().replace(/[^0-9]/ig,""));
										td_num += 1;
										obj.find('var').html('(*' + td_num + ')');
									} else {
										var html = obj.html();
										obj.html(html + '<var>(*1)</var>')
									}
								}
								
								click_obj.attr("class","open-"+classname);
								if(r.message.reflush) {
									setTimeout(function () {
										window.location.reload();
									}, 5000);
								}
							} else {
								if(r.message == 'login') {
									zoptimal.alert('<div class="pop-content"><p style="margin-right: 50px;text-align: center;">请登录您的账号</p></div>', 1, "/suny/login.html?ret_url=/zoptimal.html");
								} else {
									zoptimal.alert('<div class="pop-content"><p  style="margin-right: 50px;text-align: center;">'+r.message+'</p></div>', 3, "");
								}
								
								
							}
						}, 'json');
						
					} else {
						//zoptimal.alert('<div class="pop-content"><p style="margin-right: 50px;text-align: center;">网页异常，请联系客服</p></div>', 3);
					}
					
				}
			});
			
			// 弹窗
			zoptimal.alert = function(content, state, url) {
				var onlyChoseAlert = simpleAlert({
					"state": state,
					"content":content,
					"buttons":{
						"确定":function () {
							onlyChoseAlert.close(content);
							if(url) {
								location.href = url;
							}
						}
					}
				});
			}
		});
	</script>
</html>
