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
<body>
<?php include("top.php") ?>

<div class="tz_banner"></div>
<div class="cent">
	<div class="tzxm">
		<div class="txxm_xq">
			<h3>
				<span>最低加入金额<?php echo $borrow_min; ?>元，单笔最高不限额</span><em><?php echo $borrow_name; ?></em> |
				<?php echo $this->config->item('repayment_type')[$repayment_type]; ?>
			</h3>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="23%" align="left"><span><em><?php echo sprintf("%.2f", $borrow_interest_rate + $add_rate); ?>%</em></span></td>
					<td width="14%" align="center"><span><em><?php echo $this->config->item('borrow_duration')[$borrow_duration]; ?></em>天</span></td>
					<td width="32%" align="center"><span><em><?php echo sprintf("%.2f", $borrow_money/10000); ?>万</em>元</span></td>
					<td width="31%" align="left"><span>
						<?php if($borrow_money - $has_borrow > 0) {
							echo '<em>' . round($borrow_money - $has_borrow) . '</em>元';
						} else { 
							echo '<em>此标已满</em>';
						} ?>
					</span></td>
				</tr>
				<tr>
					<td align="left">历史年化利率</td>
					<td align="center">投资期限</td>
					<td align="center">项目总额</td>
					<td align="left">剩余可购金额</td>
				</tr>
			</table>
			<div class="jzrq">
				<p>
					距离截至：<span style="color:#fc501b;" starttime="<?php echo date('Y/m/d H:i:s', $send_time + $number_time*24*3600); ?>">-</span>
				</p>
				<div class="kyje">
					<ul>
						<?php if(! QUID){ ?>
							<li>您尚未登录，请先登录    &nbsp;&nbsp;&nbsp;&nbsp;</li>
							<li>可用红包：<select name="ctl00$MainPlaceHolder$ctl00$ddlRed" id="ctl00_MainPlaceHolder_ctl00_ddlRed">
												<option>无可用红包</option>
											</select>
							</li>
							<li></li> 
						<?php }else{?>
							<?php if($borrow_money - $has_borrow > 0) { ?>
								<form id="invest-form" onsubmit="return false;">
								<li>账户余额：<?php echo !empty($money) ? $money['account_money'] : '0.00'; ?>元 &nbsp;&nbsp;&nbsp;&nbsp;</li>
								<li>可用红包：
									<select name="ddlRed" id="ctl00_MainPlaceHolder_ctl00_ddlRed">
										<?php if(!empty($packet)) { ?>
											<option value="">请选择</option>
											<?php foreach($packet as $v) { ?>
												<option value="<?php echo $v['id'] ?>"><?php echo round($v['money']); ?>元红包，投资下限<?php echo round($v['min_money']); ?>元</option>
											<?php } ?>
										<?php } else { ?>
											<option>无可用红包</option>
										<?php } ?>
									</select>
								</li>
								<li><input name="TextBox1"
									type="text" maxlength="8"
									id="ctl00_MainPlaceHolder_ctl00_TextBox1" placeholder="请输入投资金额"
									onafterpaste="this.value=this.value.replace(/\D/g,'')"
									onkeyup="this.value=this.value.replace(/\D/g,''); "
									class="layui-input" /></li>
								<li id="ctl00_MainPlaceHolder_ctl00_PA"><input
									id="ctl00_MainPlaceHolder_ctl00_CheckBox1" type="checkbox"
									name="CheckBox1" />我已阅读并同意<a
									href="javascript:;" id="contract"
									style="color: #b39559" target="_blank">《伽满优投资协议》</a></li>
									<input type="hidden" name="bid" value="<?php echo $id; ?>" />
								</form>
							<?php } else { ?>
								<li>账户余额：0.00元    &nbsp;&nbsp;&nbsp;&nbsp;</li>
								<li>可用红包：<select name="ctl00$MainPlaceHolder$ctl00$ddlRed" id="ctl00_MainPlaceHolder_ctl00_ddlRed">
								<option value="">无可用红包</option>

								</select></li>
								<li><input name="ctl00$MainPlaceHolder$ctl00$TextBox1" type="text" maxlength="8" id="ctl00_MainPlaceHolder_ctl00_TextBox1" disabled="disabled" placeholder="100元起投" onafterpaste="this.value=this.value.replace(/\D/g,'')" onkeyup="this.value=this.value.replace(/\D/g,'');" class="layui-input" ></li>
								<li id="ctl00_MainPlaceHolder_ctl00_PA"><input id="ctl00_MainPlaceHolder_ctl00_CheckBox1" type="checkbox" name="ctl00$MainPlaceHolder$ctl00$CheckBox1" checked="checked">我已阅读并同意<a  href="javascript:;" id="contract" style="color: #b39559" target="_blank">《伽满优借款合同》</a></li>
							<?php } ?>
							
						<?php }?>
						
							
					</ul>
				</div>
				<div class="clear"></div>
			</div>
			<div class="xmjd xmjd1">
				<span>项目进度</span>
				<div class="jdt">
					<div class="jdt_bi" style=" width:<?php echo round($has_borrow*100/$borrow_money); ?>%"></div>
				</div>
				<span><?php echo round($has_borrow*100/$borrow_money); ?>%</span> 
				
				<?php if(! QUID){ ?>
					<a class="ljdl_biao" href="/suny/login.html">立即登录</a>
				<?php }else{?>
					<?php if($borrow_money - $has_borrow > 0) { ?>
						<input type="submit" name="ctl00$MainPlaceHolder$ctl00$btnQrfk" value="立即投标" id="invest-submit" class="ljdl_biao" value="立即投资" style="cursor:pointer;"/>
					<?php } else { ?>
						<?php if($borrow_status > 4) { ?>
							<input type="submit" name="ctl00$MainPlaceHolder$ctl00$btnQrfk" value="已完成" id="ctl00_MainPlaceHolder_ctl00_btnQrfk" disabled="disabled" class="jiaru yjs">
						<?php } elseif($borrow_status == '3') { ?>
							<input type="submit" name="ctl00$MainPlaceHolder$ctl00$btnQrfk" value="已满标" id="ctl00_MainPlaceHolder_ctl00_btnQrfk" disabled="disabled" class="jiaru yjs">
						<?php } elseif($borrow_status == '4') { ?>
							<input type="submit" name="ctl00$MainPlaceHolder$ctl00$btnQrfk" value="还款中" id="ctl00_MainPlaceHolder_ctl00_btnQrfk" disabled="disabled" class="jiaru yjs">
						<?php } ?>
					<?php } ?>
				<?php }?>

			</div>
		</div>
		<div class="lvts">*历史年化利率不代表实际收益，投资需谨慎</div>
		<div class="tzbz">
			<ul>
				<li><i class="icon tzbz_tu1"></i>
					<h3>加入车e贷</h3>
					<h4>注册平台会员，参与投资</h4></li>
				<li class="li1"><i class="icon tzbz_biao"></i></li>
				<li><i class="icon tzbz_tu2"></i>
					<h3>进行投标</h3>
					<h4>根据个人情况，选择产品</h4></li>
				<li class="li1"><i class="icon tzbz_biao"></i></li>
				<li><i class="icon tzbz_tu3"></i>
					<h3>投标完成</h3>
					<h4>项目满标结束，等待收益</h4></li>
				<li class="li1"><i class="icon tzbz_biao"></i></li>
				<li><i class="icon tzbz_tu4"></i>
					<h3>获得收益</h3>
					<h4>本息回款完成，随时提现</h4></li>
			</ul>
		</div>
		<div class="tzxm_di">
			<div class="xxk">
				<ul>
					<li class="on">项目详情</li>
					<li>相关文件</li>
					<li>投资记录</li>

				</ul>
			</div>
			<div class="det_content2">
				<div class="xmxq">
					<h3>
						<i class="icon"></i><strong>基本情况</strong>
					</h3>
					<div class="xmxq_nr">
						<p><?php echo $borrow_info; ?></p>
					</div>
					<h3>
						<i class="icon"></i><strong>借款人基本信息</strong>
					</h3>
					<div id="ctl00_MainPlaceHolder_ctl00_Panel1" class="xmxq_nr">

						<ul>
							<li>企业地址： <?php echo $company['saddress']; ?></li>
							<li>注册资金： <?php echo $company['age']; ?></li>
							<li>成立时间：<?php echo $company['htime']; ?></li>
							
						</ul>
						<div class="clear"></div>
					</div>
					<h3>
						<i class="icon"></i><strong>风控审核</strong>
					</h3>
					<div class="xmxq_nr">
						<p>资料信息提供齐全，登记手续正规有效，经综合评定予以批准.</p>
					</div>
				</div>
				<div class="xmxq  hide">
					<h3>
						<i class="icon"></i><strong>图片资料</strong>
					</h3>
					<div class="xmxq_nr">
						<p>
							借款合同和借款人身份信息等原件信息。<br /> （为保障相关人员隐私，以下图片已做特殊处理）
						</p>
					</div>
					<div class="xgwj_tu">
						<div class="imgBox">
							<ul id="imgList">
								<?php if(is_array($pic)) { ?>
									<?php foreach($pic as $v) { ?>
										<li><a data-url="<?php echo $v; ?>"><img src="<?php echo $v; ?>" alt="" /></a></li>
									<?php } ?>
								<?php } ?>
							</ul>
						</div>
						<div id="dia"></div>
						<div id="dialog">

							<div class="bigImg">
								<a href="javascript:void(0)" id="prevImg" class="icon">上一张</a> <img
									src="" alt="" /> <a href="javascript:void(0);"
									id="nextImg" class="icon">下一张</a>
							</div>
							<div class="zt_js">
								<h3>
									<i class="icon"></i><strong>图片资料</strong>
								</h3>
								<div class="xmxq_nr">
									<p>（为保障相关人员隐私，以下图片已做特殊处理）</p>
								</div>
							</div>
							<div class="imgtour">
								<ul></ul>
							</div>
						</div>
					</div>
				</div>
				<div class="tzjl hide">
					<h2>全部购买记录</h2>
					<h3>(按投资时间倒序排列)</h3>
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<thead>
							<tr>
								<th style="text-align:center;">序号</th>
								<th style="text-align:center;">投资人</th>
								<th style="text-align:center;">投资金额（元）</th>
								<th style="text-align:center;">投资时间</th>
								<th style="text-align:center;">投资状态</th>
							</tr>
						</thead>
						<tbody>
							<?php if(!empty($d_i)) { ?>
								<?php foreach($d_i as $k=>$v) { ?>
									<tr>
										<td><?php echo $k+1; ?></td>
										<td><?php echo $v['investor_uid']; ?></td>
										<td>￥<?php echo sprintf('%.2f', $v['investor_capital']); ?></td>
										<td><?php echo date('Y-m-d H:i:s', $v['add_time']); ?></td>
										<td>成功</td>
									</tr>
								<?php } ?>
							<?php } ?>
						</tbody>
					</table>

				</div>
				
			</div>
		</div>
	</div>
</div>

<?php include("foot.php") ?>
<script type="text/javascript">
	var invest_show = invest_show || {};
	layui.use(['layedit'], function(){
		var $ = layui.$;
		
		var nowtime = new Date('<?php echo date("Y/m/d H:i:s"); ?>').getTime();//今天的日期(毫秒值);
		invest_show.lxfEndtime1 = function() {
			$("span[starttime]").each(function () {
				var endtime = new Date($(this).attr("starttime")).getTime(); //取结束日期(毫秒值)
				var youtime = endtime - nowtime; //还有多久(毫秒值)
				var seconds = youtime / 1000;
				var minutes = Math.floor(seconds / 60);
				var hours = Math.floor(minutes / 60);
				var days = Math.floor(hours / 24);
				var CDay = days;
				var CHour =  hours % 24; // hours % 24
				var CMinute = minutes % 60;            
				var CSecond = Math.floor(seconds % 60); //"%"是取余运算，可以理解为60进一后取余数，然后只要余数。
				if (CSecond < 10) { 
					CSecond = "0" + CSecond; 
				}
				if (CMinute < 10) { 
					CMinute = "0" + CMinute; 
				}
				if(CHour<10){//如果小时数为单数，则前面补零
					CHour="0"+CHour;
				}
				if (endtime <= nowtime) {
					invest_show.daojishi = false;
					//window.location.reload(); //刷新当前页面
				} else {
					$(this).html(  CDay + "天" +CHour + "时" + CMinute + "分" + CSecond+ "秒" );          //输出没有天数的数据
				}
				nowtime += 1000;
			});
			if(invest_show.daojishi) {
				setTimeout("invest_show.lxfEndtime1()", 1000);
			}
		}
		
		invest_show.isjieshi = "<?php if($borrow_status < 3) { echo 'on'; } ?>";
		invest_show.daojishi = true;
		
		if (invest_show.daojishi && invest_show.isjieshi == "on") {
			invest_show.lxfEndtime1();
		}
		
		//标签切换
		$('.xxk').find('li').click(function () {
            $('.xxk').find('li').removeClass('on');
            $(this).addClass('on');
            $('.det_content2').children('div').hide();
            $('.det_content2').children('div').eq($(this).index()).show();
        });
		
		//图片预览
		$('#imgList li').click(function () {
			var index = $(this).index();
			var lis = $('#imgList li');
			var TemplateHtml = '';
			$('.imgtour ul').empty();
			for (var i = 0; i < lis.length; i++) {
				var imgsrc = $(lis[i]).find('a').attr('data-url');
				if (i == 0) {
					TemplateHtml += '<li class="active">';
					TemplateHtml += '	<img src="' + imgsrc + '" alt="" />';
					TemplateHtml += '</li>';
				} else {
					TemplateHtml += '<li>';
					TemplateHtml += '	<img src="' + imgsrc + '" alt="" />';
					TemplateHtml += '</li>';
				}

			}
			$('.imgtour ul').append(TemplateHtml);
			$('.imgtour ul li:eq(' + index + ')').click();
			$('#dia').show();
			$('#dialog').show();
		});
		$('#dia').click(function () {
			$(this).hide();
			$('#dialog').hide();
		});
		$('.imgtour ul').on("click", "li", function () {
			$(this).addClass('active').siblings().removeClass('active');

			var imgsrc2 = $(this).find('img').attr('src');
			$(this).parents('.imgtour').siblings('.bigImg').find('img').attr('src', imgsrc2)
		});
		$('#prevImg').click(function () {
			var index2 = $('.imgtour').find('li.active').index();
			if (index2 == 0) {
				index2 = $('.imgtour li').length - 1;
			} else {
				index2--;
			}
			$('.imgtour li:eq(' + index2 + ')').click();
		});
		$('#nextImg').click(function () {
			var index2 = $('.imgtour').find('li.active').index();
			if (index2 == $('.imgtour li').length - 1) {
				index2 = 0;
			} else {
				index2++;
			}
			$('.imgtour li:eq(' + index2 + ')').click();
		});
		$('#invest-submit').click(function(){
			$.ajax({
				'url' : '/invest/dozt/<?php echo $id; ?>.html',
				'type': 'post',
				'data': $('#invest-form').serialize(),
				'success' : function(data) {
					if(data.state) {
						layer.open({
							type: 2,
							title: '签署合同',
							shade: 0.1,
							maxmin: true,
							area: ['45%', '90%'],
							fixed: true,
							content: '/invest/build/' + data.message + '.html'
						});
					} else {
						layer.msg(data.message, {icon: 5,time:1500});
					}
				}
			});
		});
		// $('#invest-submit').click(function(){
			// $.ajax({
				// 'url' : '/invest/dozt/<?php echo $id; ?>.html',
				// 'type': 'post',
				// 'data': $('#invest-form').serialize(),
				// 'success' : function(data) {
					// if(data.state) {
						// layer.open({
							// type: 2,
							// title: '投资',
							// shade: 0.1,
							// maxmin: true,
							// area: ['50%', '90%'],
							// fixed: true,
							// content: '/invest/toub/' + data.message + '.html'
						// });
					// } else {
						// layer.msg(data.message, {icon: 5,time:1500});
					// }
				// }
			// });
		// });
		$('#contract').on('click',function(){
			layer.open({
				type: 2,
				title: '《伽满优借款合同》',
				shadeClose: true,
				shade: 0.5,
				maxmin: true,
				area: ['1200px', '800px'],
				fixed: false,
				content: 'http://www.jiamanu.cn/invest/contract.html'
			});
		});
	});
</script>
</body>
</html>