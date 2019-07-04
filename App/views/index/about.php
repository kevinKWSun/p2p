<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>公司简介</title>
    <meta http-equiv="Content-Language" content="zh-cn" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <meta name="viewport" content="width=device-width, maximum-scale=1.0, initial-scale=1.0,initial-scale=1.0,user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <link href="/Content/css/intro.css" rel="stylesheet" />
    <link href="/Content/css/iglobal.css" rel="stylesheet" />
    <script type='text/javascript' src='/Content/js/jquery-1.7.2.min.js'></script>
    <script type='text/javascript' src='/Content/js/global.js'></script>
	<script>
	$(function(){
		$(window).scroll(function(e) {
			if($(window).scrollTop() > 480){
				$('.summary').addClass('myFixed');
			}else {
				$('.summary').removeClass('myFixed');		
			}
			
        });
	})

</script>
</head>
<body>
<?php include("head.php") ?>
<div class="content">
	<div class="content_mid clearfix">
		<div class="partner_left">
			<ul>
				<li><a href="/newlist/about/1.htm" class="current">公司简介</a></li>
				<li><a href="/newlist/about/2.htm">合作伙伴</a></li>
				<li><a href="/newlist/about/3.htm">帮助中心</a></li>
				<li><a href="/newlist/about/4.htm">加入我们</a></li>
				<li><a href="/newlist/about/5.htm">联系我们</a></li>
			</ul>
		</div>
		<div class="pane">
			<div class="summary">
				<ul>
					<li onclick='change(this,0);'>
						<a href="#001">关于我们</a>			
					</li>
					<li onclick='change(this,1);'>
						<a href="#002">历史</a>
					</li>
					<li onclick='change(this,2);'>
						<a href="#003">文化</a>
					</li>
					<li onclick='change(this,3);'>
						<a href="#004">品牌故事</a>
					</li>
					<li onclick='change(this,4);'>
						<a href="#005">优势</a>
					</li>
				</ul>
			</div>
			<a name="001" id="001" ></a>
			<div class="zx_about">
				<h1>关于我们</h1>
				<div class="progress"><span></span></div>
				<div class="img_m clearfix">
					<!--div class="img_left"></div>
					<div class="img_right"></div-->
				</div>
				<div class="con_text">
					<h4>一家综合性金融服务平台。</h4>
					<p>
						公司自2017年1月成立起，一直致力于大众投融资服务平台的构建，专注中小企业
						<br/>及个人投融资咨询与服务，提供大众理财服务方案，去除中间环节，促使投融资平
						<a name="002" id="002" ></a>
						<br/>台的两端更安全、更规范、更高效，为投融资双方提供专业可靠的服务，实现双方
						<br/>利益更大化。
					</p>
				</div>
				<div class="zx_bottom"></div>
			</div>
			<div class="zx_about zx_hist">
				<h1>历史</h1>
				<div class="progress"><span></span></div>
				<div class="course"></div>
				<a name="003" id="003" ></a>
				<div class="zx_bottom"></div>
			</div>
			<div class="zx_culture zx_about">
				<h1>文化</h1>
				<div class="progress"><span></span></div>
				<p>
					仰望星空，脚踏实地，勿忘初心，务实诚信。<br/>
					诚信是人秉持的态度，对事以诚，待人以信，不隐瞒不欺骗。<br/>
					拼搏是人梦想的原动力，积极主动，努力奋斗，不后退不抱怨。<br/>
					务实是人恪守的职业品格，脚踏实地，寸劲寸进，不急躁不推诿。<br/>
					梦想是人一致的追求，和衷共济，互尊互爱，不犹豫不放弃。
				</p>
				<div class="culture_img clearfix">
					<img src="/Content/images/culture_01.png" height="178" width="206" alt="" />
					<img src="/Content/images/culture_02.png" height="178" width="206" alt="" />
					<img src="/Content/images/culture_03.png" height="178" width="206" alt="" />
					<img src="/Content/images/culture_04.png" height="178" width="206" alt="" />
										
				</div>	
				<a name="004" id="004" ></a>
				<div class="zx_bottom"></div>
			</div>
			
			<div class="zx_story zx_about">
				<h1>品牌故事</h1>
				<div class="progress"><span></span></div>
				<p>
					2017年，创始人成立直向营销公司进行数据库营销，开拓市场渠道。<br/>
					在顺应市场发展规律的同时，公司逐渐转型，致力于中小企业投融资服务。<br/>
					本着诚信专业、持续共赢的态度，<br/>
					不断扩大业务范畴，建立专业理财团队，真正立足于投融资市场。<br/>
					希望像Logo“向”字笔画中的一撇，<br/>
					演绎成一个可以传递价值的超级符号，<br/>
					它象征正确、上升、增值、未来……<br/>
					<a name="005" id="005" ></a>寓意直向投资致力于用专业的服务搭建一个安全、高效、信赖的金融服务平台。
				</p>
				<div class="zx_bottom"></div>
			</div>
			<div class="zx_advantage zx_about">
				<h1>优势</h1>
				<div class="progress"><span></span></div>
				<p class="prof">专业高效，实力雄厚；运营稳健，值得信赖。</p>
				<div class="ad_step clearfix">
					<div class="zx_ad_left">
						<span class="safety">安全</span>
						<span>专业</span>
						<span>创新</span>
					</div>
					<div class="zx_ad_right">
						<p>
							信用审核体系、风险评估机制等安全保障措施，<br/>
							保护投融资双方的经济利益。
						</p>
						<p>
							知识专业，服务专业，管理专业，<br/>
							用专业创造财富，诚信理财，专致专注，<br/>
							保证投融资双方的健康运行。
						</p>
						<p>
							财富源自前瞻，用高效的进取精神，<br/>
							不信不立、不诚不行的态度，<br/>
							创造卓越，促使投融资双方的互惠共赢。
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php include("foot.php") ?>
</body>
</html>