<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>伽满优-大乐透</title>
		<link href="/images/default.css" rel="stylesheet" type="text/css" />
		<link href="/images/new/index.css" rel="stylesheet" type="text/css" />
		<link href="/pintu/css/lotto.css" rel="stylesheet" type="text/css" />
		<link href="/src/css/layui.css" rel="stylesheet" />
		<script src="/src/layui.js"></script>
		<style>
			.lotto-bottom li {
				height:35px;
				line-height:35px;
				margin-bottom:5px;
			}
			.lotto-bottom-phone div {
				display: inline-block;
				width: 400px;
				height: 35px;
				border: 1px solid #b6b6b6;
				border-radius: 5px;
				text-indent: 24px;
				font-size: 18px;
				color: #cccccc;
			}
			.lottom-multiple {
				float:left;
				margin-top: 10px;
				margin-right: 30px;
			}
			.foot_nav h3 .icon1 {
				margin-top:-5px;
			}
		</style>
	</head>

	<body>
		<div class="header">
			<div class="top">
				<div class="sn-menu">
		                <a class="menu-hd" href="/account.html"><i class="icon"></i>我的账户<b class="icon"></b></a>
						<div class="menu-bd">
							<div class="menu-bd-panel"> 
								<a href="/suny/out.html">退出</a>
							</div>
						</div>

		        </div>
				<a href="/"><img src="/images/logo.jpg" class="logo" /></a>
				<ul>
				   <li><a href="/">首页</a></li>
				   <li><a href="/invest.html">我要出借</a></li>
				   <li><a href="/message/novice.html">帮助中心</a></li>
				   <li><a href="/message.html">信息披露</a></li>
				   <li><a href="/suny/borrowbefor.html">我要借款</a></li>
				   <li><a href="/message.html">关于伽满优</a></li>
				</ul>
			</div>
		</div>

		<div id="lotto-main">
			<div id="lotto-title"></div>	
			<div class= "lotto-line"></div>	
			<div class= "lotto-logo">快来参加</div>
			
			<div class="lotto-main-up">
				<ul class="lotto-main-up-ul">
					<li class="lotto-li-active">01</li>
					<li>02</li>
					<li>03</li>
					<li>04</li>
					<li>05</li>
					<li>06</li>
					<li>07</li>
					<li>08</li>
					<li>09</li>
					<li>10</li>
					<li>11</li>
					<li>12</li>
					<li>13</li>
					<li>14</li>
					<li>15</li>
					<li>16</li>
					<li>17</li>
					<li>18</li>
					<li>19</li>
					<li>20</li>
					<li>21</li>
					<li>22</li>
					<li>23</li>
					<li>24</li>
					<li>25</li>
				</ul>
			</div>
			<div class="clear"></div>
			<form id="lotto" action="#" method="post" id="formrec" role="form">
				<input type="hidden" name="lotto" value="<?php echo $lotto; ?>" />
				<div class="lotto-main-bottom" style="height:40px;">
					<button type="button" id="lotto-confirm" class="lotto-button-large">选好了</button>
					<!--<a title="" class="gray m-trl" onclick="clearLockingBall(3)">清空全部</a>-->
					<select name="c-r-z" class="m-t15" id="page_randomLockingAccount">
						<option value="1" selected="selected">1</option>
						<option value="2">2</option>
						<option value="5">5</option>
						<option value="10">10</option>
	                </select>
	                
	                <button type="button" class="lotto-button-middle" id="lotto-random">机选</button>
				</div>
				<div class="c-r-bet m-t20">
					<div class="lotto-result-ul" id="page_ul_lockingCodeList">
					</div>
	            </div>
				<div class="lotto-main-bbtom">
					 您选择了<span id="total">0</span>注，
                	<input name="multiple" class="i-a" value="1" maxlength="2" type="text" maxlength="2">倍
               		<span style="*zoom:1;"></span>
                	<button type="button" id="letto_clear" class="lotto-button-middle" style="vertical-align: middle;margin-left:15px;">清空列表</button>
                	<input type="text" name="phone" placeholder="请输入手机号" maxlength="11" class="lotto-bbtom-phone"/>
				</div>
                <div class="lotto-main-bottom" style="height:80px;">
                	<button type="button" lay-submit="" lay-filter="dlt" data-href="/lotto/dlt_save" class="lotto-button-large">确认</button>
                </div>
			</form>
			<div class="lotto-main-centre" style="border-bottom:0px;">
				<?php foreach($dlt as $v) { ?>
					<div class="lotto-hao" style="font-size:16px;">已选号码：</div>
					<ul class="lotto-main-centre-ul lotto-bottom">
						<?php foreach(explode(',', $v['num']) as $value) { ?>
							<li style="font-size:18px;"><?php echo $value; ?></li>
						<?php } ?>
					</ul>
					<div class="lottom-multiple"><?php echo $v['multiple'] < 10 ? '&nbsp;&nbsp;'.$v['multiple'] : $v['multiple']; ?>倍</div>
					<div class="lotto-phone lotto-bottom-phone" style="line-height:35px;heigth:35px;">
						<div><?php echo str_replace(mb_substr($v['phone'], 3, 5), '***', $v['phone']);?></div>
					</div>
					<div class="clear"></div>
				<?php } ?>
			</div>
		</div>
		<script type="text/javascript">
			var lotto = lotto || {};
			layui.use(['layedit','layer', 'form'], function(){
				var layedit = layui.layedit
					,$ = layui.$
					, layer = layui.layer
					, form = layui.form;
					
				$(".lotto-main-up-ul").on('click', 'li', function(event){
					$(this).off('click');
					var addcar = $(this);
					var leng = $(".lotto-active").length;
					if(leng == 5){
						layer.msg("选择完毕", {icon:6, time:1500});
						return false;
					}
					addcar.addClass("lotto-active");
				});
				form.on('submit(dlt)', function() {
					var url = $(this).data('href');
					if(!$('input[name=phone]').val()) {
						layer.msg("请填写手机号", {icon:6, time:1500});
						return;
					}
					if(!$('input[name="num[]"]').val()) {
						layer.msg("数据为空", {icon:6, time:1500});
						return;
					}
					if($('input[name=multiple]').val() > 10) {
						layer.msg("倍数最高10倍!", {icon:6, time:1500});
						return;
					}
					$.post(url, $('#lotto').serialize(), function(r) {
						var icon = r.state ? 6 : 5;
						layer.msg(r.message, {icon:icon, time:1500}, function() {
							if(r.state) {
								parent.location.reload();
								layer.closeAll();
							}
						});
					}, 'json');
				});
				
				// 选好了
				$('#lotto-confirm').click(function() {
					if($('.lotto-result-li').length*1 > 499) {
						layer.msg('最多选择500注！', {icon:6, time:1500});
						return false;
					}
					var length = $('.lotto-active').length;
					if(length == 5) {
						var html = '<div class="lotto-result-li">';
						var num_value = '';
						$('.lotto-active').each(function(i, n) {
							html += '<span>'+$(n).html()+'</span>';
							num_value += $(n).html() + ',';
							$(n).removeClass('.lotto-active');
						});
						html += '<input type="hidden" name="num[]" value="'+num_value.substring(0, num_value.length*1 - 1)+'" />';
						html += '<input type="button" value="删除" class="lotto-result-button" /></div>';
						
						// 移除已选中的数字
						$('.lotto-main-up-ul li').removeClass('lotto-active');
						// 添加一条数据
						$(html).appendTo($('#page_ul_lockingCodeList'));
						// 对应的注数+1
						$('#total').html(parseInt($('#total').html()) + 1);
					} else {
						layer.msg('必须选择5个数字', {icon:6, time:1500});
					}
				});
				
				// 机选
				$('#lotto-random').click(function() {
					var random_total = $('select[name=c-r-z]').val();
					if($('.lotto-result-li').length*1 + random_total*1 > 500) {
						layer.msg('最多选择500注！', {icon:6, time:1500});
						return false;
					}
					var random_has = $('.lotto-random-num').length;
					if(random_has*1 + random_total*1 > 10) {
						layer.msg('机选最多选择10注，你还能机选'+(10 - parseInt(random_has))+'注！', {icon:6, time:1500});
					} else {
						for(var i = 0; i < random_total; i++) {
							var random_arr = lotto.random(5);
							
							var html = '<div class="lotto-result-li lotto-random-num">';
							var num_value = '';
							for(var j = 0; j < random_arr.length; j++) {
								var tmp_num = random_arr[j];
								if(tmp_num < 10) tmp_num = '0' + tmp_num;
								html += '<span>'+tmp_num+'</span>';
								num_value += tmp_num + ',';
							}
							html += '<input type="hidden" name="num[]" value="'+num_value.substring(0, num_value.length*1 - 1)+'" />';
							html += '<input type="button" value="删除" class="lotto-result-button" /></div>';
							// 添加一条数据
							$(html).appendTo($('#page_ul_lockingCodeList'));
							// 对应的注数+1
							$('#total').html(parseInt($('#total').html()) + 1);
						}
					}
				});
				// 生成随机数
				lotto.random = function(level) {
					if(level > 5) {
						layer.msg('机选出错，请联系客服', {icon:6, time:1500});
					}
					var arr = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25];
					var min_num = 0;
					var max_num = 24;
					var result = [];
					for(var i = 0; i < level; i++) {
						var random_num = parseInt(Math.random()*(max_num-min_num+1)+min_num,10);
						result[i] = arr.splice(random_num, 1)[0];
						max_num = parseInt(max_num) - 1;
					}
					return result.sort(lotto.compare);
				}
				// 排序
				lotto.compare = function(val1, val2) {
					return val1-val2;
				}
				// 删除节点
				$('.lotto-result-ul').on('click', '.lotto-result-button', function() {
					$(this).parent('div.lotto-result-li').remove();
					$('#total').html(parseInt($('#total').html()) - 1);
					return;
				});
				// 清空列表
				$('#letto_clear').click(function() {
					$('div.lotto-result-li').remove();
					$('#total').html(0);
				}); 
			});
		</script>
		<div class="foot">
			<div class="foot1">
				<div class="foot1_c">
					<div class="foot_nav">
						<h3> <img src="/images/new/img/hone.png" class="icon1" style="margin-right:5px;"> 关于伽满优</h3>
						<ul>
							<li><a href="/message.html">关于我们</a></li>
							<li><a href="/message/structure.html">组织信息</a></li>
							<li><a href="/message/newest_notice.html">最新公告</a></li>
						</ul>
					</div>
					<div class="foot_nav">
						<h3><img src="/images/new/img/an.png" class="icon1">平台信息</h3>
						<ul>
							<li><a href="/message/payments.html">回款公告</a></li>
							<li><a href="/message/process.html">公司历程</a></li>
						</ul>
					</div>
					<div class="foot_nav">
						<h3><img src="/images/new/img/wen.png" class="icon1">帮助中心</h3>
						<ul>
							<li><a href="/message/novice.html">常见问题</a></li>
							<li><a href="/message/contact.html">联系我们</a></li>
						</ul>
					</div>
					<div class="lines"></div>
					<div class="foot_nav">
						<img src="/images/new/img/wx2.jpg" width="88" height="88" class='img' />
						<h5 style="margin-top:5px; margin-left:10px;">IOS App下载</h5>
					</div>
					<div class="foot_nav">
						<img src="/images/new/img/wx1.jpg" width="88" height="88" class='img' />
						<h5 style="margin-top:5px; margin-left:10px;">安卓App下载</h5>
					</div>
					<div class="lines"></div>
					<div class="foot_nav">
						<h3>联系方式</h3>
						<P><img src="/images/new/img/email.jpg" class="icon">邮箱：service@jiamanu.com</P>
						<P><img src="/images/new/img/tel.jpg" class="icon">电话：021-62127903（客服工作时间：09:00-18:00）</P>
						<P><img src="/images/new/img/addr.jpg" class="icon">上海市南京西路1468号 中欣大厦2101</P>
					</div>
				</div>
		    </div>
		</div>
		<div class="foot2">
		    <div class="foot_bq">
				<div class="tz_tb">
					<ul class="companyinfo">
						<li><a target="_blank"  href="http://www.miibeian.gov.cn">
						&nbsp;沪ICP备17038560号-1 　</a> 版权所有：上海童汇信息科技有限公司</li>
						<li>温馨提示：市场有风险，出借需谨慎。网络出借不等于存款，请合理选择出借项目，最终收益以实际为准</li>
					</ul>
					<ul class="companylogos">
						<li><a href="http://shuidi.cn/company_extreme_72484988042801684312807540045555.html" target='_blank'>
						<img src="/images/aaa.png"/>
						</a></li>
						<li><a href="http://shuidi.cn/company_extreme_72484988042801684312807540045555.html" target='_blank'>
						<img src="/images/lixin.png"/>
						</a></li>
						<li><a id="_pingansec_bottomimagesmall_p2p" href="http://si.trustutn.org/info?sn=842170927000609685434&certType=4">
						<img src="http://v.trustutn.org/images/cert/p2p_official_small.jpg" style="height: 47px;"/>
						</a></li>
						<li><a  key ="59e59a8f0c90967a9453b596"  logo_size="124x47"  logo_type="business"  href="https://v.pinpaibao.com.cn/cert/site/?site=www.jiamanu.com&at=business" ><img src="/images/hy_124x47.png"/></a></li>
						<li><a id='___szfw_logo___' href='https://credit.szfw.org/CX20171106036688891688.html' target='_blank'><img src='http://icon.szfw.org/cert.png' border='0'  style="height: 47px;" /></a></li>
						<script type='text/javascript'>(function(){document.getElementById('___szfw_logo___').oncontextmenu = function(){return false;}})();</script>
						<li><a id="kx_verify"></a></li><script type="text/javascript">(function (){var _kxs = document.createElement('script');_kxs.id = 'kx_script';_kxs.async = true;_kxs.setAttribute('cid', 'kx_verify');_kxs.src = ('https:' == document.location.protocol ? 'https://ss.knet.cn' : 'http://rr.knet.cn')+'/static/js/icon3.js?sn=e17110631010669455yk5z000000&tp=icon3';_kxs.setAttribute('size', 0);var _kx = document.getElementById('kx_verify');_kx.parentNode.insertBefore(_kxs, _kx);})();</script>
		
					</ul>
				</div>
			</div>
		</div>
	</body>
</html>
