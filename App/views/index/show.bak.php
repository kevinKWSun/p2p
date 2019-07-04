<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>产品投资详情</title>
    <meta http-equiv="Content-Language" content="zh-cn" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <meta name="viewport" content="width=device-width, maximum-scale=1.0, initial-scale=1.0,initial-scale=1.0,user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <link href="/Content/css/show.css" rel="stylesheet" />
    <link href="/Content/css/iglobal.css" rel="stylesheet" />
    <script type='text/javascript' src='/Content/js/jquery-1.7.2.min.js'></script>
    <script type='text/javascript' src='/Content/js/global.js'></script>
</head>
<body>
<?php include("head.php") ?>

<div class="content">
    <div class="content_m">
        <h3><a href='/invest.html'>我要投资</a> > 产品详情</h3>
        <div class="detail  clearfix">
            <div class="detail_l">
                <div class="middle">
                    <div class="top">
                        <i>贷</i>
                        <span class="t_code"><?php echo $borrow_name;if($del==-1||$borrow_status==10){echo '(<font color="red">已流标</font>)';}?></span>
                        <!-- <span>已投资人数<font color='#632B4E'>11</font>人</span> -->
                    </div>
                    <div class="income">
                        <i></i>
                        <h1><?php echo $borrow_interest_rate?> %</h1>
                        <span>年化收益</span>
                    </div>
                    <div class="right">
                        <div class="date">
                            <div class="invest"><span>投资期限</span> <h1><?php echo $borrow_duration?> 天</h1></div>
                            <div class="time"><span>起投金额</span> <h1><?php echo $borrow_min?> 元</h1></div>
                        </div>
                        <div class="date_b">
                            <div class="date_b_l">
                                <p class="project">
                                    项目担保：房产抵押</p>
                                <p>发标时间：<?php echo date('Y-m-d',$send_time);?></p>
                                <p>最小投标金额：<?php echo $borrow_min?> 元</p>
                            </div>
                            <div class="date_b_r">
                                <p class="project">还款方式：按月付息到期还本</p>
                                <p>计息时间：确认放款后 + 1 天</p>
                                <p>最大投标金额：<?php echo $borrow_max ? $borrow_max : '不限';?> 元</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="rate">
                    <p class="process"><span style="width: <?php echo intval($has_borrow/$borrow_money*100)?>%;"></span></p>
                    <p class="sale">已售<?php echo $has_borrow?> %<span>剩余总额 <?php echo ($borrow_money-$has_borrow)/10000?> 万</span></p>
                </div>
            </div>
            <form name="investForm" id="investForm">
            <div class="detail_r" style='height:310px;'>
                <br><br>
                <p class="look">账户余额  &nbsp;
					<?php if(! QUID){?>
                    <a href="javascript:void(0);">请登录后查看</a>
					<?php }else{?>
                    <?php echo $money['account_money']?> 元 
                    <span>
                        <a href='/pay.html' target='_blank'>充值</a> | 
                        <a href='javascript:;' id='qt'>全投</a>
                    <span>
                    <input type="hidden" value='<?php echo $borrow_money-$has_borrow?>' id='all' />
					<?php }?>
                </p>
                <p>起投金额 <span><?php echo $borrow_min ?>元</span></p>
                <p>最高金额 <span><?php echo $borrow_max ? $borrow_max.'元' : '不限';?></span></p>
                <p>投资金额
                <input placeholder="输入投资金额" type="text" class='input' name="money" onkeyup="value=value.replace(/[^0-9-]/g,'')" />
                </p>
                <p><span>投资收益：<b id='s'>0</b>元</span></p><br>
                <?php if($borrow_money == $has_borrow || $del == -1 || $borrow_status == 10){?>
				<br>
                <a class="l_invest" style='background:#AAA' href="javascript:;">已售罄</a>
                <?php }else{?>
				<p style='color:red' id='error'></p>
                <a class="l_invest tz" href="javascript:;">投资</a>
                <input type="hidden" name="borrow_id" value="<?php echo $id ?>" />
                <?php }?>
            </div>
            </form>
        </div>
        <div class="record">
            <ul class="record_top">
                <li rel='1' class="current">项目详情</li>
                <li rel='2'>投资记录</li>
                <li rel='3'>回款记录</li>
            </ul>
            <div class='c_1'>
                <?php echo $borrow_info?>
                <!-- <table style="border-collapse: collapse; font-family: 微软雅黑; color: rgb(0,0,0); font-size: 14px">
                    <tbody>
                        <tr>
                            <td class="target_td1" valign="middle" width="101"><p class="target_p"><span style="font-family: 微软雅黑; font-size: 10pt; font-weight: bold">产品名称</span></p></td>
                            <td style="border-bottom: rgb(249,176,116) 1pt solid; padding-bottom: 0pt; border-left-style: none; padding-left: 5.4pt; width: 347.65pt; padding-right: 5.4pt; font-size: 14px; border-top: rgb(249,176,116) 1pt solid; border-right: rgb(249,176,116) 1pt solid; padding-top: 0pt" valign="top" width="463"><p style="font-family: microsoft yahei; font-size: 13px"><span style="font-size: 13px"><span style="font-family: 微软雅黑; font-size: 13px"><span style="font-size: 10pt">100号</span></span></span></p></td>
                        </tr>
                        <tr>
                            <td class="target_td1" valign="middle" width="101"><p class="target_p"><span style="font-family: 微软雅黑; font-size: 10pt; font-weight: bold">产品类别</span></p></td>
                            <td class="target_td3" valign="top" width="463"><p style="font-family: microsoft yahei; font-size: 13px"><span style="font-size: 13px"><span style="font-family: 微软雅黑; font-size: 13px"><span style="font-size: 11pt">一对多专户（管理型）</span></span></span></p></td>
                        </tr>
                        <tr>
                            <td class="target_td1" valign="middle" width="101"><p class="target_p"><span style="font-family: 微软雅黑; font-size: 10pt; font-weight: bold">融资方介绍</span></p></td>
                            <td class="target_td3" valign="top" width="463"><p style="font-family: microsoft yahei; font-size: 13px"><span style="font-size: 13px"><span style="font-family: 微软雅黑; font-size: 13px"><span style="font-size: 10.5pt">{$vo.borrow_info}</span></span></span></p></td>
                        </tr>
                        <tr>
                            <td class="target_td2" valign="middle" width="101"><p class="target_p"><span style="font-family: 微软雅黑; font-size: 10pt; font-weight: bold">投资范围</span></p></td>
                            <td class="target_td3" valign="top" width="463"><p style="font-family: microsoft yahei; font-size: 13px"><span style="font-size: 13px"><span style="font-family: 微软雅黑; font-size: 13px"><span style="font-size: 10pt">购买优质资产收益权</span></span></span></p></td>
                        </tr>
                        <tr>
                            <td class="target_td2" valign="middle" width="101"><p class="target_p"><span style="font-family: 微软雅黑; font-size: 10pt; font-weight: bold">托管行</span></p></td>
                            <td class="target_td3" valign="top" width="463"><p style="font-family: microsoft yahei; font-size: 13px"><span style="font-size: 13px"><span style="font-family: 微软雅黑; font-size: 13px"><span style="font-size: 10pt">宝付、中金支付</span></span></span></p></td>
                        </tr>
                        <tr>
                            <td class="target_td2" valign="middle" width="101"><p class="target_p"><span style="font-family: 微软雅黑; font-size: 10pt; font-weight: bold">风控策略</span></p>
                            </td>
                            <td class="target_td3" valign="top" width="463"><p style="font-family: microsoft yahei; font-size: 13px" class="p"><span style="font-size: 13px">三重风险保障：</span></p><p style="font-family: microsoft yahei; font-size: 13px" class="p"><span style="font-size: 13px"><span style="font-family: 微软雅黑; font-size: 14px">1，借款人承担第一性还款责任</span></span></p><p style="font-family: microsoft yahei; font-size: 13px" class="p"><span style="font-size: 13px"><span style="font-family: 微软雅黑; font-size: 14px">2，第三方资产管理公司承担回购责任</span></span></p><p style="font-family: microsoft yahei; font-size: 13px" class="p"><span style="font-size: 13px"><span style="font-family: 微软雅黑; font-size: 14px">3，足额上海本地房产抵押作为担保措施</span></span></p>
                            </td>
                        </tr>
                        <tr>
                            <td class="target_td2" valign="middle" width="101"><p class="target_p"><font face="微软雅黑"><b>起息日</b></font></p>
                            </td>
                            <td class="target_td3" valign="top" width="463"><p style="font-family: microsoft yahei; font-size: 13px" class="p"><span style="font-family: 微软雅黑; font-size: 13px"><span style="font-family: microsoft yahei; font-size: 13px"><span style="font-family: 微软雅黑; font-size: 10.5pt">当天计息</span></span></span></p>
                            </td>
                        </tr>
                    </tbody>
                </table> -->
            </div>
            <div class='c_2'>
                <table width="100%" cellspacing="10" cellpadding="1" border="0">
                    <thead>
                        <tr>
                            <th class="first_tr">序号</th>
                            <th>投标人</th>
                            <th>投标金额</th>
                            <th>投标时间</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($d_i){foreach($d_i as $k=>$v):?>
                        <tr bgcolor="#ffffff">
                            <td align="center"><?php echo $k+1;?></td>
                            <td align="center"><?php echo substr_replace(get_member_info($v['investor_uid'])['phone'], '***',2 , 7);?></td>
                            <td align="center"><?php echo $v['investor_capital']?></td>
                            <td align="center"><?php echo date('Y-m-d', $v['add_time']);?></td>
                        </tr>
                        <?php endforeach;}else{?>
                        <tr bgcolor="#ffffff"><td align="center" colspan="4">暂无数据</td></tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <div class='c_3'>
                <table width="100%" border="0" cellspacing="10" cellpadding="1" >
                    <thead>
                        <tr>
                            <th class="first_tr">期数</th>
                            <th>还款时间</th>
                            <th>本金</th>
                            <th>利息</th>
                            <th>总额</th>
                            <th>剩余本金</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr bgcolor="#ffffff">
                            <td align="center">1</td>
                            <td align="center"><?php echo $endtime?date('Y-m-d', $endtime):'融资方确认后放款';?></td>
                            <td align="center"><?php echo $borrow_money?></td>
                            <td align="center"><?php echo round($borrow_money*$borrow_interest_rate/100/360*$borrow_duration, 4);?></td>
                            <td align="center"><?php echo round($borrow_money*$borrow_interest_rate/100/360*$borrow_duration+$borrow_money, 4);?></td>
                            <td align="center"><?php echo $borrow_money-$has_borrow;?></td>
                        </tr>
                    </tbody>
                </table>    
            </div>
        </div>
    </div>
</div>    

<?php include("foot.php") ?>
<script type="text/javascript">
$(function(){
	$('.tz').click(function(){
		var money = $('input[name=money]').val();
		var borrow_id = $('input[name=borrow_id]').val();
		var min = '<?php echo $borrow_min ?>';
		var max = '<?php echo $borrow_max ?>';
		if(! money){
			$('#error').text('投资金额有误...');
			return;
		}
		if(money * 1 > $('#all').val()){
			$('#error').text('投资金额超出范围...');
			return;
		}
		if(money * 1 < min){
			$('#error').text('投资金额小于最低金额....');
			return;
		}
		if(max && money * 1 > max){
			$('#error').text('投资金额大于最高金额');
			return;
		}
		if(! borrow_id){
			$('#error').text('数据有误...');
			return;
		}
		$('#error').text('');
		$.post('/invest/dozt', {m:money, bid:borrow_id},function(s){
			$('#error').text(s.message);
			if(s.state){
				location.reload();
			}else{
				return;
			}
		}, 'json');
	});
    if($('input[name=money]').val() > 0){
        var t = $("#invest_money").val() * 1;
        var m = "<?php echo $borrow_interest_rate/100/360*$borrow_duration;?>";
        $('#s').text((t*m).toFixed(4));
    }
    $('input[name=money]').bind("keyup", function() {
        $this = $(this);
        var t = $this.val() * 1;
        var m = "<?php echo $borrow_interest_rate/100/360*$borrow_duration;?>";
        $('#s').text((t*m).toFixed(4));
    });
    $('#qt').click(function(){
        $('input[name=money]').val($('#all').val());
        var t = $('#all').val() * 1;
        var m = "<?php echo $borrow_interest_rate/100/360*$borrow_duration;?>";
        $('#s').text((t*m).toFixed(4));
    });
    $('.record_top li').click(function(){
        $('.record_top li').removeClass('current');
        $(this).addClass('current');
        var rel = $(this).attr('rel');
        $('.c_1').hide();
        $('.c_2').hide();
        $('.c_3').hide();
        $('.c_'+rel).show();
    });
});
</script>
</body>
</html>