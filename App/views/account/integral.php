<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>出借者风险承受能力调查评估表-伽满优</title>
    <meta http-equiv="Content-Language" content="zh-cn" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <meta name="viewport" content="width=device-width, maximum-scale=1.0, initial-scale=1.0,initial-scale=1.0,user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="Keywords" content="充值-伽满优,车贷理财,车辆抵押,P2P出借理财,出借理财公司,短期理财,P2P出借理财平台" />
	<meta name="Description" content="充值-伽满优,通过公开透明的规范操作,平台为出借理财人士提供收益合理、安全可靠、高效灵活的车贷理财产品。" />
    <link href="/src/css/layui.css" rel="stylesheet" />
    <script src="/src/layui.js"></script>
	<style>
		.layui-input-block {
			margin-left: 35px;
			line-height: 25px;
			padding-right: 35px;
		}
		#formrec .content {
			padding-left: 30px;
		}
		#formrec .content label{
			width: 100%;
		}
		#formrec .content span{
			display: inline-block;
			float: right;
			color: #666;
			
		}
	</style>
</head>

<body>
<div class="main-wrap" style='padding:10px;'>
	<blockquote class="layui-elem-quote" style='border-left:5px solid #FF5722'>
		<h3>出借者风险承受能力调查评估表</h3>
	</blockquote>
	<div class="y-role">
		<div class="fhui-admin-table-container">
			<form id="formrec" method="post" role="form" class="layui-form" onsubmit="return false;">
				<div class="layui-form-item">
					<div class="layui-input-block">
						<h2><strong>客户姓名：<?php echo $meminfo['real_name']; ?></strong></h2>
					</div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block">
						<h3><strong>一、评估问卷</strong></h3>
					</div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;非常感谢您对我司提供的产品的关注，并主动要求了解或要求出借我司提供的相关产品。依据中国银行业监督委员会颁布的《网络借贷信息中介机构业务活动管理暂行办法》等有关要求，本着对出借者负责的态度，专门设计了本调查问卷，下列问题将有助于您清楚了解自己的风险偏好及风险承受能力，在您在我司出借时，它可协助评估您的出借偏好和风险承受能力，有助于您控制出借的风险，同时也便于我司据此为您提供更准确的服务及产品推介。
					</div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;请在下列各题最合适的答案上打勾，我们将根据您的选择来评估您对出借风险的适应度，并提供适合您出借的产品和服务建议。我行承诺对您的个人资料严格保密。
					</div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block content"> <strong>1、您的年龄：</strong></div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block content">
						<label><input class="score[1]" type="radio" name="score[1]" lay-skin="primary" value="1" title="20岁以下或65岁以上" /></label><span style="margin-top:6px;">（1分）</span>
					</div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block content">
						<label><input class="score[1]" type="radio" name="score[1]" lay-skin="primary" value="2" title="51岁至65岁" /></label><span style="margin-top:6px;">（2分）</span>
					</div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block content">
						<label><input class="score[1]" type="radio" name="score[1]" lay-skin="primary" value="3" title="21岁至30岁" /></label><span style="margin-top:6px;">（3分）</span>
					</div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block content">
						<label><input class="score[1]" type="radio" name="score[1]" lay-skin="primary" value="4" title="31岁至50岁" /></label><span style="margin-top:6px;">（4分）</span>
					</div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block content"> <strong>2、您的教育程度：</strong></div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block content">
						<label><input class="score2" type="radio" name="score[2]" lay-skin="primary" value="1" title="高中以下" /></label><span style="margin-top:6px;">（1分）</span>
					</div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block content">
						<label><input class="score2" type="radio" name="score[2]" lay-skin="primary" value="2" title="专科" /></label><span style="margin-top:6px;">（2分）</span>
					</div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block content">
						<label><input class="score2" type="radio" name="score[2]" lay-skin="primary" value="3" title="本科" /></label><span style="margin-top:6px;">（3分）</span>
					</div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block content">
						<label><input class="score2" type="radio" name="score[2]" lay-skin="primary" value="4" title="研究生或研究生以上" /></label><span style="margin-top:6px;">（4分）</span>
					</div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block content"> <strong>3、您的健康状况：</strong></div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block content">
						<label><input type="radio" name="score[3]" lay-skin="primary" value="1" title="较差" /></label><span style="margin-top:6px;">（1分）</span>
					</div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block content">
						<label><input type="radio" name="score[3]" lay-skin="primary" value="2" title="一般" /></label><span style="margin-top:6px;">（2分）</span>
					</div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block content">
						<label><input type="radio" name="score[3]" lay-skin="primary" value="3" title="良好" /></label><span style="margin-top:6px;">（3分）</span>
					</div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block content">
						<label><input type="radio" name="score[3]" lay-skin="primary" value="4" title="很好" /></label><span style="margin-top:6px;">（4分）</span>
					</div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block content"> <strong>4、您目前的职业状况：</strong></div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block content">
						<label><input type="radio" name="score[4]" lay-skin="primary" value="1" title="待业或退休" /></label><span style="margin-top:6px;">（1分）</span>
					</div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block content">
						<label><input type="radio" name="score[4]" lay-skin="primary" value="2" title="无固定工作" /></label><span style="margin-top:6px;">（2分）</span>
					</div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block content">
						<label><input type="radio" name="score[4]" lay-skin="primary" value="3" title="企事业单位固定工作" /></label><span style="margin-top:6px;">（3分）</span>
					</div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block content">
						<label><input type="radio" name="score[4]" lay-skin="primary" value="4" title="私营业主" /></label><span style="margin-top:6px;">（4分）</span>
					</div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block content"> <strong>5、您目前的年收入状况：</strong></div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block content">
						<label><input type="radio" name="score[5]" lay-skin="primary" value="1" title="10万以下" /></label><span style="margin-top:6px;">（1分）</span>
					</div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block content">
						<label><input type="radio" name="score[5]" lay-skin="primary" value="2" title="10-50万" /></label><span style="margin-top:6px;">（2分）</span>
					</div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block content">
						<label><input type="radio" name="score[5]" lay-skin="primary" value="3" title="50-100万" /></label><span style="margin-top:6px;">（3分）</span>
					</div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block content">
						<label><input type="radio" name="score[5]" lay-skin="primary" value="4" title="100万以上" /></label><span style="margin-top:6px;">（4分）</span>
					</div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block content"> <strong>6、您进行出借的主要目的是：</strong></div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block content">
						<label><input type="radio" name="score[6]" lay-skin="primary" value="1" title="确保资产的安全性，同时获得固定收益 " /></label><span style="margin-top:6px;">（1分）</span>
					</div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block content">
						<label><input type="radio" name="score[6]" lay-skin="primary" value="2" title="希望出借能获得一定的增值，同时获得波动适度的年回报" /></label><span style="margin-top:6px;">（2分）</span>
					</div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block content">
						<label><input type="radio" name="score[6]" lay-skin="primary" value="3" title="倾向于长期的成长，较少关心短期的回报和波动" /></label><span style="margin-top:6px;">（3分）</span>
					</div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block content">
						<label><input type="radio" name="score[6]" lay-skin="primary" value="4" title="只关心长期的高回报，能够接受短期的资产价值波动" /></label><span style="margin-top:6px;">（4分）</span>
					</div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block content"> <strong>7．您的出借知识：</strong></div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block content">
						<label><input type="radio" name="score[7]" lay-skin="primary" value="1" title="缺乏出借基本常识 " /></label><span style="margin-top:6px;">（1分）</span>
					</div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block content">
						<label><input type="radio" name="score[7]" lay-skin="primary" value="2" title="略有了解，但不懂出借技巧 " /></label><span style="margin-top:6px;">（2分）</span>
					</div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block content">
						<label><input type="radio" name="score[7]" lay-skin="primary" value="3" title="有一定了解，懂一些的出借技巧" /></label><span style="margin-top:6px;">（3分）</span>
					</div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block content">
						<label><input type="radio" name="score[7]" lay-skin="primary" value="4" title="认识充分，并懂得出借技巧" /></label><span style="margin-top:6px;">（4分）</span>
					</div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block content"> <strong>8、您的出借经验：</strong></div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block content">
						<label><input type="radio" name="score[8]" lay-skin="primary" value="1" title="无出借经验" /></label><span style="margin-top:6px;">（1分）</span>
					</div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block content">
						<label><input type="radio" name="score[8]" lay-skin="primary" value="2" title="少于2年（不含2年）" /></label><span style="margin-top:6px;">（2分）</span>
					</div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block content">
						<label><input type="radio" name="score[8]" lay-skin="primary" value="3" title="2年至5年（不含5年）" /></label><span style="margin-top:6px;">（3分）</span>
					</div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block content">
						<label><input type="radio" name="score[8]" lay-skin="primary" value="4" title="5年以上" /></label><span style="margin-top:6px;">（4分）</span>
					</div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block content"> <strong>9、您的出借品种偏好：</strong></div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block content">
						<label><input type="radio" name="score[9]" lay-skin="primary" value="1" title="债券、债券型基金、货币型基金" /></label><span style="margin-top:6px;">（1分）</span>
					</div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block content">
						<label><input type="radio" name="score[9]" lay-skin="primary" value="2" title="外币、黄金、出借型保单" /></label><span style="margin-top:6px;">（2分）</span>
					</div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block content">
						<label><input type="radio" name="score[9]" lay-skin="primary" value="3" title="股票、基金（不包括债券、货币型基金）" /></label><span style="margin-top:6px;">（3分）</span>
					</div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block content">
						<label><input type="radio" name="score[9]" lay-skin="primary" value="4" title="期货、权证、P2P出借" /></label><span style="margin-top:6px;">（4分）</span>
					</div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block content"> <strong>10、您进行出借的资金占家庭自有资金的比例：</strong></div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block content">
						<label><input type="radio" name="score[10]" lay-skin="primary" value="1" title="15%以下" /></label><span style="margin-top:6px;">（1分）</span>
					</div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block content">
						<label><input type="radio" name="score[10]" lay-skin="primary" value="2" title="15-30%" /></label><span style="margin-top:6px;">（2分）</span>
					</div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block content">
						<label><input type="radio" name="score[10]" lay-skin="primary" value="3" title="30-50%" /></label><span style="margin-top:6px;">（3分）</span>
					</div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block content">
						<label><input type="radio" name="score[10]" lay-skin="primary" value="4" title="50%以上" /></label><span style="margin-top:6px;">（4分）</span>
					</div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block content"> <strong>11、您出借某项非保本项目产品时，能接受的出借期限一般是：</strong></div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block content">
						<label><input type="radio" name="score[11]" lay-skin="primary" value="1" title="1年以下" /></label><span style="margin-top:6px;">（1分）</span>
					</div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block content">
						<label><input type="radio" name="score[11]" lay-skin="primary" value="2" title="1-3年" /></label><span style="margin-top:6px;">（2分）</span>
					</div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block content">
						<label><input type="radio" name="score[11]" lay-skin="primary" value="3" title="3-5年" /></label><span style="margin-top:6px;">（3分）</span>
					</div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block content">
						<label><input type="radio" name="score[11]" lay-skin="primary" value="4" title="5年以上" /></label><span style="margin-top:6px;">（4分）</span>
					</div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block content"> <strong>12、您进行出借时所能承受的最大亏损比例是：</strong></div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block content">
						<label><input type="radio" name="score[12]" lay-skin="primary" value="1" title="10%以内" /></label><span style="margin-top:6px;">（1分）</span>
					</div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block content">
						<label><input type="radio" name="score[12]" lay-skin="primary" value="2" title="10-30%" /></label><span style="margin-top:6px;">（2分）</span>
					</div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block content">
						<label><input type="radio" name="score[12]" lay-skin="primary" value="3" title="30-50%" /></label><span style="margin-top:6px;">（3分）</span>
					</div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block content">
						<label><input type="radio" name="score[12]" lay-skin="primary" value="4" title="50%以上" /></label><span style="margin-top:6px;">（4分）</span>
					</div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block content"> <strong>13、您进行出借的方法：</strong></div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block content">
						<label><input type="radio" name="score[13]" lay-skin="primary" value="1" title="靠直觉和运气，跟着别人操作，没有认真分析 " /></label><span style="margin-top:6px;">（1分）</span>
					</div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block content">
						<label><input type="radio" name="score[13]" lay-skin="primary" value="2" title="看图形操作，自己懂一点技术分析" /></label><span style="margin-top:6px;">（2分）</span>
					</div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block content">
						<label><input type="radio" name="score[13]" lay-skin="primary" value="3" title="技术分析和基本面分析相结合" /></label><span style="margin-top:6px;">（3分）</span>
					</div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block content">
						<label><input type="radio" name="score[13]" lay-skin="primary" value="4" title="在专家指导下操作" /></label><span style="margin-top:6px;">（4分）</span>
					</div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block content"> <strong>14、您期望的项目历史年化收益：</strong></div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block content">
						<label><input type="radio" name="score[14]" lay-skin="primary" value="1" title="高于同期定期存款" /></label><span style="margin-top:6px;">（1分）</span>
					</div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block content">
						<label><input type="radio" name="score[14]" lay-skin="primary" value="2" title="10%左右，要求相对风险较低 " /></label><span style="margin-top:6px;">（2分）</span>
					</div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block content">
						<label><input type="radio" name="score[14]" lay-skin="primary" value="3" title="10-20%，可承受中等风险" /></label><span style="margin-top:6px;">（3分）</span>
					</div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block content">
						<label><input type="radio" name="score[14]" lay-skin="primary" value="4" title="20%以上，可承担较高风险" /></label><span style="margin-top:6px;">（4分）</span>
					</div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block content"> <strong>15、您如何看待出借亏损：</strong></div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block content">
						<label><input type="radio" name="score[15]" lay-skin="primary" value="1" title="很难接受，影响正常的生活" /></label><span style="margin-top:6px;">（1分）</span>
					</div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block content">
						<label><input type="radio" name="score[15]" lay-skin="primary" value="2" title="受到一定的影响，但不影响正常生活" /></label><span style="margin-top:6px;">（2分）</span>
					</div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block content">
						<label><input type="radio" name="score[15]" lay-skin="primary" value="3" title="平常心看待，对情绪没有明显的影响" /></label><span style="margin-top:6px;">（3分）</span>
					</div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block content">
						<label><input type="radio" name="score[15]" lay-skin="primary" value="4" title="很正常，出借有风险，没有人只赚不赔" /></label><span style="margin-top:6px;">（4分）</span>
					</div>
				</div>
				<div class="layui-form-item" style="margin-top: 35px;">
					<div class="layui-input-block content" style="text-align: center;">
						<button class="layui-btn layui-btn-danger" style="width: 160px;" lay-filter="js-submit" lay-submit="" data-url="/account/integral/<? echo $meminfo['uid']; ?>.html" id="js-submit">提&nbsp;&nbsp;交</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<script src="/src/global.js"></script>
<script type="text/javascript">
	layui.use(['layer', 'form'], function(){
		var $ = layui.$
			, layer = layui.layer
			, form = layui.form;
		
		form.on('submit(js-submit)', function() {
			var url = $(this).data('url');
			
			$.post(url, $('#formrec').serialize(), function(r) {
				layer.msg(r.message, {time: 1500}, function() {
					if(r.state == 1) {
						layer.open({
							type: 2,
							title: '评分结果',
							shade: 0.1,
							maxmin: true,
							area: ['65%', '70%'],
							fixed: true,
							content: '/account/integral/<?php echo $meminfo['uid']; ?>.html',
							cancel: function(index, layero){ 
								location.reload();
								return false; 
							}    
						});
					}
				});
			}, 'json');
		});
	});
</script>
</body>
</html>