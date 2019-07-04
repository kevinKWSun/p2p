<!DOCTYPE html>
<html>
<head>
<title><?php echo $proname; ?>-伽满优</title>
<meta name="Keywords" content="伽满优，车贷理财，车辆抵押,P2P投资理财,投资理财公司,短期理财,P2P投资理财平台">
<meta name="Description" content="伽满优，专注质押车优质投资服务，多种出借策略，期限自由搭配，操作灵活，产品丰富，综合年化7%-12%。">
<link href="/favicon.ico" rel="SHORTCUT ICON">
<link href="/favicon.ico" rel="SHORTCUT ICON" />
<link href="/images/default.css" rel="stylesheet" type="text/css" />
<link href="/images/new/index.css" rel="stylesheet" type="text/css" />
<link href="swiper.min.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/images/jquery-1.7.2.min.js"></script>
<link href="/src/css/layui.css" rel="stylesheet" />
<script src="/src/layui.js"></script>

<style>
	.xmxq_v1 h3 i {
		width: 24px; 
	}
	.xmxq_nr ul li {
		float: left;
		width: 352px;
		height: 36px;
		font-size: 14px;
		color: #444444;
		text-indent: 20px;
		margin: 1px 5px;
		line-height: 36px;
	}
</style>
    
</head>
<body>
<?php include("topa.php") ?>
<div class="tz_banner"></div>
<div class="cent_v1">
  <div class="tzxm">
    <div class="txxm_xq_v1">
      <h3><span>最低加入金额<?php echo $mininvestbalance; ?>元，单笔最高不限额</span><em><?php echo $proname; ?></em></h3>
      <table width="100%" border="0" cellspacing="0" cellpadding="0" style="table-layout:fixed;">
		<tbody>
			<tr>
				<td width="25%" align="left"><span><em class="lsnhl_v1"><?php echo sprintf("%.2f", $yearyield); ?>%</em></span></td>
				<td width="15%" align="center"><span><em><?php echo 97; ?></em>天</span></td>
				<td width="21.4%" align="center"><span><em><?php echo sprintf("%.2f", $borrowbalance/10000); ?>万</em>元</span></td>
				<td width="30%" align="left" class="tb_bg"><span>
					<?php 
						echo '<em>此标已满</em>';
					?>
				</span></td>    
			</tr>
			<tr height="40">
				<td  align="left">历史年化利率</td>
				<td align="center">出借期限</td>
				<td align="center">项目总额</td>
				<td align="left" class="tb_bg">剩余出借金额</td>
			</tr>
		</tbody>
	   </table>
       <div class="jzrq_v1" style="height:140px;">
       	<p>距离截至：<span style=" color:#fc501b;" starttime="">-</span></p>
        <div class="kyje_v1" style="height: 140px;">
        	<ul>
			
				<?php if(! QUID){ ?>
				
					<li>您尚未登录，请先登录    &nbsp;&nbsp;&nbsp;&nbsp;</li>
					<li>可用红包：
						<select name="ddlRed" id="ctl00_MainPlaceHolder_ctl00_ddlRed">
							<option value="">无可用红包</option>
						</select>
					</li>
					<li>伽满优提醒您：市场有风险，出借需谨慎。</li>
				<?php } else { ?>
					<form id="invest-form" onsubmit="return false;">
						<li>账户余额：0.00元    &nbsp;&nbsp;&nbsp;&nbsp;</li>
						<li>可用红包：
							<select name="ctl00$MainPlaceHolder$ctl00$ddlRed" id="ctl00_MainPlaceHolder_ctl00_ddlRed">
								<option value="">无可用红包</option>

							</select>
						</li>
						<li>
							<input name="TextBox1" type="text" maxlength="8" id="ctl00_MainPlaceHolder_ctl00_TextBox1" disabled="disabled" placeholder="请输入出借金额" onafterpaste="this.value=this.value.replace(/\D/g,'')" onkeyup="this.value=this.value.replace(/\D/g,'');" class="layui-input"  style="width:71%;">
						</li>
						<li id="ctl00_MainPlaceHolder_ctl00_PA">
							<input id="ctl00_MainPlaceHolder_ctl00_CheckBox1" type="checkbox" name="CheckBox1">我已阅读并同意<a  href="javascript:;" id="contract" style="color: #b39559">《伽满优借款合同》</a></li>
							<input type="hidden" name="bid" value="" />
					</form>
				<?php }?>                                                      
        	</ul>
        </div>
        <div class="clear"></div>
       </div>
       <div class="xmjd_v1 xmjd1_v1">
		   <span>项目进度</span>
		   <div class="jdt_v1">
			<div class="jdt_bi_v1" style=" width:<?php echo 100; ?>%"></div>
		   </div>
		   <span><?php echo 100; ?>%</span>
		   <div class="ljdl_biao_right_v1"></div>
	   </div>
	   <div class="xmtg_v1">伽满优提醒您：市场有风险，出借需谨慎。</div>
	   <div class="ljdl_biao_v1" style="">	
			<?php if(! QUID){ ?>
				<a href="/suny/login.html">立即登录</a>
			<?php }else{?>
				<a id="ctl00_MainPlaceHolder_ctl00_btnQrfk" disabled style="background-color:#ddd;">已完成</a>
			<?php }?>
	   </div>
    </div>
	
    <div class="lvts"></div>
    <div class="tzbz_v1">
	<h2>计划进度</h2>
      <ul>
        <li>
          <i class="icon tzbz_tu1"></i>
          <h3>今日出借</h3>
          <h4>注册平台会员，参与出借</h4>
        </li>
        <li class="li1"><i class="icon tzbz_biao"></i></li>
        <li>
          <i class="icon tzbz_tu2"></i>
          <h3>满标起息</h3>
          <h4>根据个人情况，慎重选择产品</h4>
        </li>
        <li class="li1"><i class="icon tzbz_biao"></i></li>
        <li>
          <i class="icon tzbz_tu3"></i>
          <h3>收益中</h3>
          <h4>项目满标结束，等待收益</h4>
        </li>
        <li class="li1"><i class="icon tzbz_biao"></i></li>
        <li>
          <i class="icon tzbz_tu4"></i>
          <h3>到期退出</h3>
          <h4>本息回款完成，随时提现</h4>
        </li>
        
      </ul>
    </div>
    <div class="tzxm_di_v1">
     <div class="xxk_v1">
       <ul>
         <li class="on">项目详情</li>
         <li>相关文件</li>
         <li>投资记录</li>
       </ul>
     </div>
     <div class="det_content2">
       
       <div class="xmxq">
		 <h3><i class="icon"></i><strong>基本情况</strong></h3>
		 <div class="xmxq_nr">
			<?php echo $personal_info['JiBenQingKuang']; ?>
		 </div>
         <h3><i class="icon"></i><strong>项目详情</strong></h3>
			<div id="ctl00_MainPlaceHolder_ctl00_Panel1" class="xmxq_nr">	
				<ul>
					 <li>借款人： 马***</li>
					 <li>婚姻状况： 已婚</li>
					 <li>年收入：￥250,000.00</li>
					 <li>性别： 男</li>
					 <li>工作年限： 20</li>
					 <li>年龄： 57</li>
					 <li>工作行业： 信息产业</li>
					 <li>岗位职位： 经理</li>
					 <li>工作城市： 山东东营</li>
					 <li>行业性质： 私营企业</li>
					 <li>文化程度： 本科</li>
					 <li>户口所在地： 山东东营</li>
					 <li>有无房贷： 无</li>
					 <li>有无车贷： 无</li>
					 <li>工作认证： 有</li>
					 <li>有无信用报告： 有</li>
					 <li>有无购房： 有</li>
					 <li>收入认证： 有</li>
					 <li>婚姻证明： 有</li>
					 <li>学历认证： 有</li>
					 <li>居住地证明： 有</li>
			   </ul>
			   <div class="clear"></div>        
			</div>
			<h3><i class="icon"></i><strong>风控审核</strong></h3>
			<div class="xmxq_nr">
				<?php echo $personal_info['FengXianShenHe']; ?>
			</div>
       </div>
	   <div class="xmxq_v1 hide">
			<h3><i class="icon"></i><strong>图片资料</strong></h3>
			<div class="xmxq_nr">
				<p>借款合同和借款人身份信息等原件信息。<br>（为保障相关人员隐私，以下图片已做特殊处理）</p>
			</div>
			<div class="xgwj_tu">
				<div class="imgBox">
					<ul id="imgList">
						<?php if(!empty($car_images)) { foreach($car_images['list'] as $v) { ?>
							<li><a data-url="<?php echo $v['icourlb']; ?>"><img src="<?php echo $v['icourls']; ?>" alt="<?php echo $v['PicID']; ?>"></a></li>
						<?php } } ?>
					</ul>
				</div>
				<div id="dia"></div>
				<div id="dialog" style="left: 351.5px;">

					<div class="bigImg">
						<a href="javascript:void(0)" id="prevImg" class="icon">上一张</a>
						<img src="images/1.jpg" alt="">
						<a href="javascript:void(0)" id="nextImg" class="icon">下一张</a>
					</div>
					<div class="zt_js">
						<h3><i class="icon"></i><strong>图片资料</strong></h3>
						<div class="xmxq_nr">
							<p>（为保障相关人员隐私，以下图片已做特殊处理）</p>
						</div>
					</div>
					<div class="imgtour"><ul></ul></div>
				</div>
			</div>
       </div>
	   <div class="xmxq hide">
          <h3><i class="icon"></i><strong>全部购买记录</strong><span style="font-size:12px;">(按投资时间倒序排列)</span></h3>
		  <table width="100%" border="0" cellspacing="0" cellpadding="0" style="table-layout:fixed;" class="jkpt_tb">
			<tbody>
				<tr align="center" >
					<th style="text-align:center;" width="5%" align="center">序号</th>
					<th style="text-align:center;" width="5%" align="center">投资人</th>
					<th style="text-align:center;" width="15%" align="center">投资金额（元）</th> 
					<th style="text-align:center;" width="15%" align="center">投资时间</th>
					<th style="text-align:center;" width="15%" align="center">投资状态</th>
				</tr>
				<?php if(!empty($d_i['list'])) { ?>
					<?php foreach($d_i['list'] as $key => $v) { ?>
						<tr>
							<td align="center"><?php echo $key + 1; ?></td>
							<td align="center"><?php echo $v['investman']; ?></td>
							<td align="center"><?php echo $v['investmoney']; ?></td>
							<td align="center">￥<?php echo $v['investtime']; ?></td>
							<td align="center"><?php echo $v['investstatuse']; ?></td>
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



<script type="text/javascript">
    $(function () {
        var width = $(document).width();
        $('#dialog').css('left', (width - 1200) / 2);
    });
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
    })
    $('#dia').click(function () {
        $(this).hide();
        $('#dialog').hide();
    })
    $('.imgtour ul').on("click", "li", function () {
        $(this).addClass('active').siblings().removeClass('active');

        var imgsrc2 = $(this).find('img').attr('src');
        $(this).parents('.imgtour').siblings('.bigImg').find('img').attr('src', imgsrc2)
    })
    $('#prevImg').click(function () {
        var index2 = $('.imgtour').find('li.active').index();
        if (index2 == 0) {
            index2 = $('.imgtour li').length - 1;
        } else {
            index2--;
        }
        $('.imgtour li:eq(' + index2 + ')').click();
    })
    $('#nextImg').click(function () {
        var index2 = $('.imgtour').find('li.active').index();
        if (index2 == $('.imgtour li').length - 1) {
            index2 = 0;
        } else {
            index2++;
        }
        $('.imgtour li:eq(' + index2 + ')').click();
    })
		</script>








<div class="detailscon" style="display:none;">
	<div class="maincon clearfix">
		<div class="dcon_left fl">
			<h1></h1>
			<h5></h5>
			<div class="bdc clearfix">
				<ul>
					<li><h1>%</h1></li>
					<li><span>历史年化利率</span></li>
				</ul>
				<ul>
					<li><h1>天</h1></li>
					<li><span>投资期限</span></li>
				</ul>
				<ul class="bnone">
					<li><h1></h1></li>
					<li><span>项目总额（元）</span></li>
				</ul>
			</div>
								
			<p class="tbtips">最低投资金额：元</p>
			<div class="tblc">
				<span class="lc lc1">加入车e贷</span>
				<span>-购买产品-</span>
				<span class="lc lc2">完成投标</span>
				<span>-获得收益-</span>
				<span class="lc lc3">投资结束</span>
				<span>-本息回款-</span>
				<span class="lc lc4">完成退出</span>						
			</div>
			<div class="bcon">
				<ul class="btab">
					<li><a href="javascript:;" class="bt">项目详情</a></li>
					<li><a href="javascript:;">相关文件</a></li>
					<li><a href="javascript:;">投资记录</a></li>
				</ul>
				<div class="btabcon">
					<div class="proc">
						<ul>
							<li>项目名称
								<p>
									97质押车-浙D路虎发现神行</p>
							</li>
							<li>收益处理方式<br>
								及目标利率
								<p>
									
									&nbsp;12.00%(年化)</p>
							</li>
							<li>封闭期
								<p>
									97天</p>
							</li>
							<li>加入条件
								<p>
									最低加入金额100元。</p>
							</li>
							
							
							<li>
								基本情况
								<p></p>
							</li>
							<li>
								借款用途
								<p>扩大经营</p>
							</li>
							<li>
								提供资料说明
								<p>见附件</p>
							</li>
							<li>
								实地考察情况
								<p>真实可信</p>
							</li>
							<li>
								风控审核描述
								<p></p>
							</li>
						</ul>
						
					</div>
					
					
				</div>
			</div>
		</div>
		
	</div>			
</div>

<?php include("foota.php") ?>

<script type="text/javascript">
	var invest_show = invest_show || {};
	layui.use(['layedit', 'element', 'layer'], function(){
		var $ = layui.$
		, layer = layui.layer
		, element = layui.element;
		
		//标签切换
		$('.xxk_v1').find('li').click(function () {
            $('.xxk_v1').find('li').removeClass('on');
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
	});
</script>
<style>.foot_nav h3 img.icon1{margin-top:0}</style>