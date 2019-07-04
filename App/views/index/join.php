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
    <link href="/Content/css/join.css" rel="stylesheet" />
    <link href="/Content/css/iglobal.css" rel="stylesheet" />
    <script type='text/javascript' src='/Content/js/jquery-1.7.2.min.js'></script>
    <script type='text/javascript' src='/Content/js/global.js'></script>
    <script>
		$(function(){
			$('.job').hide();
			$('.job:first').show();
			$('.job:first .partner_right_b').hide();
			$('.partner_right_b:first').show();
			$('.partner_right_top a').click(function(){
				//$('.sj img').attr('src','Content/images/bots.png');
				$('.partner_right_top a').removeClass('current');
				$(this).addClass('current');
				$('.job').hide();
				var obj = $('.job').eq($(this).index());
				obj.show();
				if($(this).index() == 1){
					$('.job_cw').eq(1).find('.partner_right_b').hide();
				}
				//obj.find('.partner_right_b').hide();
			});
			$('.sj img').click(function(e){
				var target = e.target || e.srcElement;
				$(target).parent().siblings('.partner_right_b').slideToggle();
				$(target).parents('.job_cw').siblings().find('.partner_right_b').hide();
				var last_pic = $(target).attr('src').toString();
				$('.sj img').attr('src','/Content/images/bot.png');
				var pic1 = $(target).attr('src').toString();
				if(last_pic == '/Content/images/bot.png'){
					$(target).attr('src','/Content/images/top.png');
				}else{
					$(target).attr('src','/Content/images/bot.png');
				}
			});
		});
	</script>
</head>
<body>
<?php include("head.php") ?>
<div class="content">
	<div class="content_mid clearfix">
		<div class="partner_left">
			<ul>
				<li><a href="/newlist/about/1.htm">公司简介</a></li>
				<li><a href="/newlist/about/2.htm">合作伙伴</a></li>
				<li><a href="/newlist/about/3.htm">帮助中心</a></li>
				<li><a href="/newlist/about/4.htm" class="current">加入我们</a></li>
				<li><a href="/newlist/about/5.htm">联系我们</a></li>
			</ul>
		</div>
		<div class="partner_right">
			<div class="partner_right_top">
             	<a onclick='change(this,0);' href="javascript:void(0);" class="current">IT技术部</a>
                <a onclick='change(this,1);' href="javascript:void(0);">财务部</a>
                <a onclick='change(this,2);' href="javascript:void(0);">法务部</a>
                <a onclick='change(this,3);' href="javascript:void(0);">人事部</a>
                <a onclick='change(this,4);' href="javascript:void(0);">市场部</a>
                <a onclick='change(this,5);' href="javascript:void(0);">销售部</a>
                <a onclick='change(this,6);' href="javascript:void(0);">理财部</a>
             </div>
            <div class="job">
            	<div class="sj">
          			<span>UI设计</span>
          			<img src="/Content/images/bot.png" height="12" width="21" alt="" />
          		</div> 
	          	<div class="partner_right_b">
	             	<h2>UI设计</h2>
	                <hr/>
	                <h3>岗位职责:</h3>
	                <p>
	                	1.公司官方网站、APP、微官网的设计制作;<br/>
						2.负责公司广告、图片、动画、海报、logo及网页的设计制作上传;<br/>
						3.负责公司网站活动专题的版面策划、设计以及制作上传;<br/>
						4.认真做好各类信息和资料收集、整理、汇总、归档等工作，为公司旗下各项目的成功开发提供优质的素材。<br/>
	                </p>
	                <h3>岗位要求:</h3>
	                <p>
	                	1.精通photoshop,flash任意图形处理工具;<br/>
						2.熟练使用Dreamweaver等网站页面开发工具对设计好的页面进行切图与排版;<br/>
						3.能够使用photoshop，AI等绘图，矢量工具设计平面印刷品;<br/>4.具有深厚的美术功底及良好的创意构思能力，很好把握视觉色彩与网站布局，思想敏锐活跃，具有丰富的视觉创作经验和独到的审美修养;<br/>
						5.具备优秀的网站整体策划、设计能力，有丰富的网页设计经验1年以上。<br/>
						联系邮箱:
	                </p>
	            </div>
            </div>
            <div class="job">
            	<div class="job_cw">
	            	<div class="sj">
	          			<span>财务专员</span>
	          			<img src="/Content/images/bot.png" height="12" width="21" alt="" />
	          		</div> 
		          	<div class="partner_right_b">
					 	<h2>财务专员</h2>
					    <hr/>
					    <h3>岗位职责:</h3>
					    <p>
					    	1、日常费用的报销及银行付款、对帐工作;<br/>
							2、收付款确认；按公司规定办理相关单据的银行支付手续;<br/>
							3、开具普通发票及增值税发票;<br/>
							4、及时整理相关收款单据，按日填报资金报表，反映现金的收支状况负责;<br/>
							5、妥善保管空白支票和其他结算单据，保管库存现金和有价证券，确保其安全无损;<br/>
							6、固定资产管理;<br/>
							7、日常行政事务处理协调;<br/>
							8、领导交办的其他事宜。<br/>
					    </p>
					    <h3>岗位要求:</h3>
					    <p>
					    	1、专科学历以上，有出纳工作经验1年以上，持有会计上岗证；<br/>
							2、为人正直、责任心强、作风严谨、工作仔细认真；<br/>
							3、有较强的沟通协调能力；有良好的纪律性、团队合作以及开拓创新精神；能承受一定工作压力；<br/>
							4、熟练应用财务软件和办公软件。<br/>
							联系邮箱:
					    </p>
		 			</div>
	 			</div>
	 			<div class="job_cw">
	 				<div class="sj" style="margin-top:1px">
	          			<span>财务(实习生)</span>
	          			<img src="/Content/images/top.png" height="12" width="21" alt="" />
	          		</div> 
		 			<div class="partner_right_b">
					 	<h2>财务(实习生)</h2>
					    <hr/>
					    <h3>岗位职责:</h3>
					    <p>
					    	1、完成财务报表的统计整理工作；<br/>
							2、按照公司要求能够编制财务报表并能够简单分析财务内容；<br/>
							3、完成各项财务结算、会计核算，上报财务执行情况，为管理层提供分析数据。<br/>
					     </p>               
					    <h3>岗位要求:</h3>
					    <p>
					    	1.大专以上学历，在读大三、大四学生； <br/>
							2.专业不限，财会、金融类相关专业优先；<br/>
							 3.配合任职部门实施和完善工作；<br/>
							 4.能熟练操作Office软件,Excel操作熟练；<br/>
							 5.具有良好的沟通和表达能力，具有较强的责任心。<br/>
							联系邮箱:
					    </p>
					</div>
				</div>
			</div>
			<div class="job">
				<div class="sj">
          			<span>法务助理</span>
          			<img src="/Content/images/bot.png" height="12" width="21" alt="" />
          		</div> 
				<div class="partner_right_b">
				 	<h2>法务助理</h2>
				    <hr/>
				    <h3>岗位职责:</h3>
				    <p>
				    	1、诉讼保全业务工作；<br/>
						2、法律文书的核对检查工作；<br/>
						3、日常法律合规检查工作；<br/>
						4、其它日常协助工作。<br/>
				     </p>               
				    <h3>岗位要求:</h3>
				    <p>
				    	1、全日制本科及以上学历，有律师资格证优先；<br/>
						2、法律专业，民法、经济法优先；<br/>
						3、做事严谨、积极、有责任心。<br/>
						联系邮箱:
				    </p>
				</div>
			</div>
			<div class="job">
				<div class="sj">
          			<span>前台行政</span>
          			<img src="/Content/images/bot.png" height="12" width="21" alt="" />
          		</div> 
				<div class="partner_right_b">
	             	<h2>前台行政</h2>
	                <hr/>
	                <h3>岗位职责:</h3>
	                <p>
	                	1、 前台接待、引导；<br/>
						2、 日常办公用品采购、登陆、发放业务；<br/>
						3、 办公区域环境整理和保洁阿姨工作监督；<br/>
						4、 办公设备保修工作登记及上报处理工作；<br/>
						5、 快递业务；<br/>
						6、 考勤业务；<br/>
						7、 名片登记、印制；<br/>
						8、 办公饮用水预定及管理。<br/>
	                 </p>
	                <h3>岗位要求:</h3>
	                <p>
	                	1、文秘、行政管理及相关专业大专以上学历；<br/>
						2、一年以上相关工作经验；<br/>
						3、熟悉前台工作流程，熟练使用各种办公自动化设备；<br/>
						4、工作热情积极、细致耐心，具有良好的沟通能力、协调能力，性格开朗，相貌端正，待人热诚；<br/>
						5、熟练使用相关办公软件。<br/>
						联系邮箱:
	                </p>
	            </div>
            </div>
			<div class="job">
				<div class="sj">
          			<span>平面设计</span>
          			<img src="/Content/images/bot.png" height="12" width="21" alt="" />
          		</div> 
				<div class="partner_right_b">
	             	<h2>平面设计(备)</h2>
	                <hr/>
	                <h3>岗位职责:</h3>
	                <p>
	                	1.视觉形象把控，确保公司内所有对外宣传及分布视觉设计统一，包括内部使用品logo、格式等；<br/>
						2.主抓宣传品设计，包括公司折页、单页、海报、易拉活动及宣传短片制作；<br/>
						3.定期更新宣传图片和视频素材；<br/>
						4.协助活动负责人完善展示布置，包括场地设计与布置；<br/>
						5.协助网络负责人把控网络视觉设计；<br/>
						6.其他涉及公司形象宣传的设计；<br/>
						7.完成上级领导安排的其他工作任务。
	                 </p>               
	                <h3>岗位要求:</h3>
	                <p>
	                	1.男女不限，25-35岁平面设计学相关专业大学本科以上学历3年以上设计师工作经验，有手绘经验者优先；<br/>
						2.具有平面设计和视觉传达相关专业培训；<br/>
						3.在PC、MAC机器上均熟练操作Photoshop、Illustrator、Indesign等设计类软件；<br/>
						4.身体健康，恪尽职守，具有良好的职业道德素质和团队合作精神；<br/>
						5.良好的沟通及团队配合能力，可以及时的学习新知识接受新的工作项目并高效的完成工作；<br/>
						6.服从领导，完全的执行能力，较高工作效率，协作的团队精神，敬畏认真的工作态度，勤奋的学习劲头。<br/>
						联系邮箱:
	                </p>
	            </div>
            </div>
            <div class="job">
            	<div class="sj">
          			<span>客户经理</span>
          			<img src="/Content/images/bot.png" height="12" width="21" alt="" />
          		</div> 
		  		<div class="partner_right_b">
				 	<h2>客户经理</h2>
				    <hr/>
				    <h3>岗位职责:</h3>
				    <p>
				    	1.协助主管做好市场渠道信息收集、整理和分析工作；<br/>
				        2.完成销售业绩及回款任务；<br/>
				        3.做好客户关系管理工作，定期回访老客户；<br/>
				       	4.负责销售账款回收；<br/>
				        5.完成上级交办其他工作。
				     </p>
				    <h3>岗位要求:</h3>
				    <p>
				    	1.对市场营销工作有深刻认知；<br/>
				        2.有良好的市场判断能力和开拓能力；<br/>
				        3.有良好的风险控制意识； <br/>
				        4.熟练操作办公软件； <br/>
				        5.正直、坦诚、成熟、豁达、自信；<br/>
				        6.高度的工作热情，良好的团队合作精神；<br/>
				        7.较强的观察力和应变能力。<br/>
				        联系邮箱:service@cd.com
				    </p>
				</div>
			</div>
			<div class="job">
				<div class="sj">
          			<span>理财经理</span>
          			<img src="/Content/images/bot.png" height="12" width="21" alt="" />
          		</div> 
				<div class="partner_right_b">
				 	<h2>理财经理</h2>
				    <hr/>
				    <h3>岗位职责:</h3>
				    <p>
				    	1.团队管理：督促理财顾问团队的客户开发，通过多种方式，积极寻找潜在客户，引导客户需求，促成客户成交，
						并根据客户的理财需求，提供产品配置与建议，为客户制定理财方案，确保为客户提供高质量的理财服务；<br/>2.客户管理：制定客户发展计划，提交客户跟进，成交和维护信息，妥善处理客户关系做好客户转介绍工作；<br/>
				        3.团队构成：理财团队由高级理财顾问和理财顾问组成，团队人数8-10人；<br/>
						4.工作目标：P2P单子分配，解决团队成员工作难点，推动团队进步和激励成员达到既定销售目标；<br/>
						5.团队发展：本团队培训任务，持续招聘团队新鲜队员，做团队文化建设。
				     </p>               
				    <h3>岗位要求:</h3>
				    <p>
				    	1.具有两年以上理财经理管理经验或三年以上理财经理从业经验；<br/>
					2.具备较强的沟通能力和组织协调能力，熟悉金融产品，具有较强的专业理财技能，零售金融产品营销推广及组
						织策划能力；<br/>3.对理财产品和客户分层服务有较为深入的了解，在客户营销和后期服务方面经验丰富，有较强的组织推动能力和营销拓展能力；<br/>4.具有一定的业务资源和客户资源，具备较好的专业技能，中高端个人客户开发及维护经验；<br/>5.团队协作能力强，能较好适应工作压力并具备解决问题的能力，性格开朗，乐观向上。<br/>联系邮箱:
				    </p>
				</div>
			</div>
		</div>
	</div>
</div>
<?php include("foot.php") ?>
</body>
</html>