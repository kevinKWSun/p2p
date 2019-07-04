<!DOCTYPE html>
<html>
<head>
<title><?php echo $borrow_name; ?>-伽满优</title>
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
</style>
    
</head>
<body>
<?php include("topa.php") ?>
<div class="tz_banner"></div>
<div class="cent_v1">
  <div class="tzxm">
    <div class="txxm_xq_v1">
      <h3><span>最低加入金额<?php echo ($borrow_type == 2 && $borrow_min < 1000) ? 1000 : $borrow_min; ?>元，单笔最高不限额</span><em>【<?php echo $borrow_type == 2 ? '新手标' : '企业贷'; ?>-<?php echo $borrow_no; ?>】<?php echo $borrow_name; ?></em> | <?php echo $this->config->item('repayment_type')[$repayment_type]; ?></h3>
      <table width="100%" border="0" cellspacing="0" cellpadding="0" style="table-layout:fixed;">
		<tbody>
			<tr>
				<td width="25%" align="left"><span><em class="lsnhl_v1"><?php echo sprintf("%.2f", $borrow_interest_rate + $add_rate); ?>%</em></span></td>
				<td width="15%" align="center"><span><em><?php echo $this->config->item('borrow_duration')[$borrow_duration]; ?></em>天</span></td>
				<td width="21.4%" align="center"><span><em><?php echo sprintf("%.2f", $borrow_money/10000); ?>万</em>元</span></td>
				<td width="30%" align="left" class="tb_bg"><span>
					<?php if($borrow_money - $has_borrow > 0) {
						echo '<em>' . round($borrow_money - $has_borrow) . '</em>元';
					} else { 
						echo '<em>此标已满</em>';
					} ?>
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
       <div class="jzrq_v1" <?php if(!empty($password)) { echo "style='height: 184px;'";} else {echo "style='height: 140px;'";} ?>>
       	<p>距离截至：<span style=" color:#fc501b;" starttime="<?php if($has_borrow <= $borrow_money) { echo date('Y/m/d H:i:s', $send_time + $number_time*24*3600); } ?>">-</span></p>
        <div class="kyje_v1" <?php if(!empty($password)) { echo "style='height: 184px;'";} else {echo "style='height: 140px;'";} ?>>
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
					<?php if($borrow_money - $has_borrow > 0) { ?>
						<form id="invest-form" onsubmit="return false;">
						<li>账户余额：<?php echo !empty($money) ? $money['account_money'] : '0.00'; ?>元 &nbsp;&nbsp;&nbsp;&nbsp;</li>
						<li>可用红包：
							<select name="ddlRed" id="ctl00_MainPlaceHolder_ctl00_ddlRed">
								<?php if(!empty($packet)) { ?>
									<option value="">请选择</option>
									<?php foreach($packet as $v) { ?>
										<option value="<?php echo $v['id'] ?>"><?php echo round($v['money']); ?>元红包，投资下限<?php echo round($v['min_money']); ?>元，<?php echo date('Y-m-d', $v['etime']); ?>过期</option>
									<?php } ?>
								<?php } else { ?>
									<option>无可用红包</option>
								<?php } ?>
							</select>
						</li>
						<?php if(!empty($password)) { ?>
							<li>
								<input name="TextBox1" type="text" maxlength="8" id="ctl00_MainPlaceHolder_ctl00_TextBox1" placeholder="请输入出借金额"
	 onafterpaste="this.value=this.value.replace(/\D/g,'')" onkeyup="this.value=this.value.replace(/\D/g,''); " class="layui-input" style="width:71%;"/>
							</li>
							<li>
								<input name="pwd" type="text" maxlength="6" id="ctl00_MainPlaceHolder_ctl00_TextBox1" placeholder="请输入出借密码" onafterpaste="this.value=this.value.replace(/\D/g,'')" onkeyup="this.value=this.value.replace(/\D/g,'');" class="layui-input"  style="width:71%;">
							</li>
						<?php } else { ?>
							<li>
								<input name="TextBox1" type="text" maxlength="8" id="ctl00_MainPlaceHolder_ctl00_TextBox1" placeholder="请输入出借金额"
	 onafterpaste="this.value=this.value.replace(/\D/g,'')" onkeyup="this.value=this.value.replace(/\D/g,''); " class="layui-input" style="width:71%;"/>
							</li>
						<?php } ?>
						<li id="ctl00_MainPlaceHolder_ctl00_PA">
							<input id="ctl00_MainPlaceHolder_ctl00_CheckBox1" type="checkbox" name="CheckBox1" />我已阅读并同意<a href="javascript:;" id="contract" style="color: #b39559">《伽满优借款协议》</a></li>
							<input type="hidden" name="bid" value="<?php echo $id; ?>" />
						</form>
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
					<?php } ?>
					
				<?php }?>                                                      
        	</ul>
        </div>
        <div class="clear"></div>
       </div>
       <div class="xmjd_v1 xmjd1_v1">
		   <span>项目进度</span>
		   <div class="jdt_v1">
			<div class="jdt_bi_v1" style=" width:<?php echo floor($has_borrow*100/$borrow_money); ?>%"></div>
		   </div>
		   <span><?php echo floor($has_borrow*100/$borrow_money); ?>%</span>
		   <div class="ljdl_biao_right_v1"></div>
	   </div>
	   <div class="xmtg_v1">本项目由鄂托克前旗农商银行进行资金存管  伽满优提醒您：市场有风险，出借需谨慎。</div>
	   <div class="ljdl_biao_v1" style="">	
			<?php if(! QUID){ ?>
				<a href="/suny/login.html">立即登录</a>
			<?php }else{?>
				<?php if($borrow_money - $has_borrow > 0) { ?>
					<a id="invest-submit">立即出借</a>
				<?php } else { ?>
					<?php if($borrow_status > 4) { ?>
						<a disabled style="background-color:#ddd;">已完成</a>
					<?php } elseif($borrow_status == '3') { ?>
						<a id="ctl00_MainPlaceHolder_ctl00_btnQrfk" disabled style="background-color:#ddd;">已满标</a>
					<?php } elseif($borrow_status == '4') { ?>
						<a id="ctl00_MainPlaceHolder_ctl00_btnQrfk" disabled style="background-color:#ddd;">还款中</a>
					<?php } else { ?>
						<a id="ctl00_MainPlaceHolder_ctl00_btnQrfk" disabled style="background-color:#ddd;">已满标</a>
					<?php } ?>
				<?php } ?>
			<?php }?>
	   </div>
    </div>
	
    <div class="lvts"></div>
    <div class="tzbz_v1" <?php if(!empty($password)) { echo "style='margin-top: 48px;'";} ?>>
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
         <li class="on">企业基本信息</li>
         <li>项目详情</li>
         <li>交易流程</li>
		 <li>担保信息</li>
         <li>出借记录</li>
         
       </ul>
     </div>
     <div class="det_content2">
       
       <div class="xmxq">
         <h3><i class="icon"></i><strong>借款人基本信息</strong></h3>
			<div id="ctl00_MainPlaceHolder_ctl00_Panel1">	
				<ul class="xmxq_nr_ul_v1">
					<li>借款企业： <?php echo str_replace(mb_substr($meminfo['real_name'], 0, 6), '**', $meminfo['real_name']); ?></li>
					
					<?php if(!$company_info) { ?>
						 <li>主营行业： </li>
						 <li>统一社会信用代码/注册号：</li>
						 <li>成立时间： </li>
						 <li>注册地址： </li>
						 <li>登记状态： </li>
						 <li>经营范围： </li>
						 <li>征信状况： </li>
						 <li>涉诉情况： </li>
						 <li>行政处罚状况： </li>
						 <li>其他网站平台负债： </li>
						 <li>企业相关资料： </li>
					<?php } else { ?>
						<li>主营行业： <?php echo $company_info['industry']; ?></li>
						 <li>统一社会信用代码/注册号： <?php echo str_replace(mb_substr($company_info['credit'], 2, 8), '**', $company_info['credit']); ?></li>
						 <li>成立时间： <?php echo $company_info['founding_time']; ?></li>
						 <li>注册地址： <?php echo str_replace(mb_substr($company_info['reg_address'], 5, 15), '**********', $company_info['reg_address']); ?></li>
						 <li>登记状态： <?php echo $company_info['reg_status']; ?></li>
						 <li>经营范围： <?php echo empty($company_info['scope']) ? $company_info['industry'] : $company_info['scope']; ?></li>
						 <li>征信状况： <?php echo $company_info['credit_status']; ?></li>
						 <li style="border-bottom:1px dotted  #cccccc;">涉诉情况： <?php echo $company_info['situation']; ?></li>
						 <li style="border-bottom:1px dotted  #cccccc;">行政处罚状况： <?php echo $company_info['sanction']; ?></li>
						 <li>其他网站平台负债： <?php echo $company_info['liabilities']; ?></li>
						 <li>企业相关资料： <?php echo $company_info['info']; ?></li>
						
					<?php } ?>
					 
			   </ul>
			   <div class="clear"></div>        
			</div>
			<h3><i class="icon"></i><strong>借款人平台历史借款记录</strong></h3>
              <table width="100%" border="0" cellspacing="0" cellpadding="0" style="table-layout:fixed;" class="jkpt_tb">
				<tbody>
					<tr>
						<th width="5%" style="text-align:center;">序号</th>
						<th width="15%" style="text-align:center;">借款时间</th>
						<th width="20%" style="text-align:center;">借款期限</th>
						<th width="20%" style="text-align:center;">借款金额</th>    
						<th width="20%" style="text-align:center;">借款标号</th>    
						<th width="20%" style="text-align:center;">还款情况</th>    
					</tr>
					<?php if(!empty($borrow_history)) { ?>
						<?php foreach($borrow_history as $v) { ?>
							<tr>
								<td align="center"><?php echo $v['id']; ?></td>
								<td align="center"><?php echo date('Y-m-d', $v['add_time']); ?></td>
								<td align="center"><?php echo $this->config->item('borrow_duration')[$v['borrow_duration']]; ?>天</td>
								<td align="center"><?php echo round($v['borrow_money']/10000, 2); ?>万</td>
								<td align="center"><?php echo $v['borrow_no']; ?></td>
								<td align="center"><?php echo $this->config->item('borrow_status')[$v['borrow_status']]; ?></td>
							</tr>
						<?php } ?>
					<?php } ?>
				</tbody>
			   </table>
       </div>
	   <div class="xmxq_v1 hide">
			 <h3><i class="icon"></i><strong>基本情况</strong></h3>
			 <div class="xmxq_nr_v1">
			   <p><?php echo strip_tags($borrow_info); ?></p>
			 </div>
			 <h3><i class="icon"></i><strong>项目风险评估结果</strong></h3>
			 <div class="xmxq_nr_v1">
			   <p><?php echo $grade . '级'; ?></p>
			 </div>
			 <h3><i class="icon"></i><strong>借款用途</strong></h3>
			 <div class="xmxq_nr_v1">
			   <p><?php echo strip_tags($borrow_use); ?></p>
			 </div>
			 <h3><i class="icon"></i><strong>还款来源</strong></h3>
			 <div class="xmxq_nr_v1">
			   <p><?php echo strip_tags($payment); ?></p>
			 </div>
			 <h3><i class="icon"></i><strong>借款保障</strong></h3>
			 <div class="xmxq_nr_v1">
			   <p><?php echo strip_tags($guarantee); ?></p>
			 </div>
			 
			  <h3><i class="icon"></i><strong>借款人信息列表</strong></h3>
			  <table width="100%" border="0" cellspacing="0" cellpadding="0" style="table-layout:fixed;" class="jkpt_tb">
				<tbody>
					<tr align="center" >
						<th style="text-align:center;" width="5%" align="center">企业名称</th>
						<th style="text-align:center;" width="15%" align="center">统一社会信用代码/注册号</th> 
						<th style="text-align:center;" width="15%" align="center">操作</th>
					</tr>
					<tr>
						<td align="center"><?php echo str_replace(mb_substr($meminfo['real_name'], 0, 6), '**', $meminfo['real_name']); ?></td>
						<td align="center"><?php echo str_replace(mb_substr($company_info['credit'], 2, 8), '**', $company_info['credit']); ?></td>
						<td align="center"><button class="js-showb" data-id="<?php echo $id; ?>">查看详细资料</button></td>
					</tr>
				</tbody>
			   </table>
		   
		   
	
       </div>
       <div class="xmxq hide">
				<h3><i class="icon"></i><strong>伽满优交易流程</strong></h3>
				<div class="jylc_img"></div>
       </div>
       <div class="xmxq hide">
            <h3><i class="icon"></i><strong>担保人信息列表</strong></h3>
		  <table width="100%" border="0" cellspacing="0" cellpadding="0" style="table-layout:fixed;" class="jkpt_tb">
			<tbody>
				<tr align="center" >
					<th style="text-align:center;" width="5%" align="center">姓名</th>
					<th style="text-align:center;" width="15%" align="center">身份证</th> 
					<th style="text-align:center;" width="15%" align="center">操作</th>
				</tr>
				<?php if(!empty($guarantors)) { ?>
					<?php foreach($guarantors as $v) { ?>
						<tr>
							<td align="center"><?php echo str_replace(mb_substr($v['name'], 1), '**', $v['name']); ?></td>
							<td align="center"><?php echo str_replace(mb_substr($v['idcard'], 3, 11), '**', $v['idcard']);  ?></td>
							<td align="center"><button class="js-show" data-id="<?php echo $v['id']; ?>">查看详细资料</button></td>
						</tr>
					<?php } ?>
				<?php } ?>
			</tbody>
		   </table>
       </div>
	   <div class="xmxq hide">
          <h3><i class="icon"></i><strong>出借记录</strong></h3>
		  <table width="100%" border="0" cellspacing="0" cellpadding="0" style="table-layout:fixed;" class="jkpt_tb">
			<tbody>
				<tr align="center" >
					<th style="text-align:center;" width="5%" align="center">ID</th>
					<th style="text-align:center;" width="5%" align="center">用户</th>
					<th style="text-align:center;" width="15%" align="center">出借金额</th> 
					<th style="text-align:center;" width="15%" align="center">出借时间</th>
				</tr>
				<?php if(!empty($d_i)) { ?>
					<?php foreach($d_i as $key => $v) { ?>
						<tr>
							<td align="center"><?php echo $key+1; ?></td>
							<td align="center"><?php echo $v['investor_uid']; ?></td>
							<td align="center">￥<?php echo $v['investor_capital']; ?></td>
							<td align="center"><?php echo date('Y-m-d H:i', $v['add_time']); ?></td>
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
		
		
		$('.js-show').click(function() {
			var id = $(this).attr('data-id');
			var href = '/invest/showimg/'+id+'.html';
			layer.open({
				type: 2,
				title: '担保人详细信息',
				shade: 0.1,
				maxmin: true,
				area: ['45%', '90%'],
				fixed: true,
				content: href
			});
		});
		$('.js-showb').click(function() {
			var id = $(this).attr('data-id');
			var href = '/invest/showimgb/'+id+'.html';
			layer.open({
				type: 2,
				title: '企业详细信息',
				shade: 0.1,
				maxmin: true,
				area: ['45%', '90%'],
				fixed: true,
				content: href
			});
		});
		// layer.photos({
			// photos: '.layui-show'
			// ,anim: 5 //0-6的选择，指定弹出图片动画类型，默认随机（请注意，3.0之前的版本用shift参数）
		// });
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
							area: ['450px', '400px'],
							fixed: true,
							content: '/invest/build/' + data.message + '.html'
						});
					} else {
						layer.msg(data.message, {icon: 5,time:1500});
					}
				}
			});
		});
		/* $('#invest-submit').click(function(){
			$.ajax({
				'url' : '/invest/dozt/<?php echo $id; ?>.html',
				'type': 'post',
				'data': $('#invest-form').serialize(),
				'success' : function(data) {
					if(data.state) {
						layer.open({
							type: 2,
							title: '投资',
							shade: 0.1,
							maxmin: true,
							area: ['50%', '90%'],
							fixed: true,
							content: '/invest/toub/' + data.message + '.html'
						});
					} else {
						layer.msg(data.message, {icon: 5,time:1500});
					}
				}
			});
		}); */
		$('#contract').on('click',function(){
			layer.open({
				type: 2,
				title: '《伽满优借款合同》',
				shadeClose: true,
				shade: 0.5,
				maxmin: true,
				area: ['1200px', '800px'],
				fixed: false,
				content: 'https://www.jiamanu.com/invest/contract.html'
			});
		});
	});
</script>
<style>.foot_nav h3 img.icon1{margin-top:0}</style>