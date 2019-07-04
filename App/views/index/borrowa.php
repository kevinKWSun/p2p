<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>我要借款-伽满优</title>
    <meta http-equiv="Content-Language" content="zh-cn" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <meta name="viewport" content="width=device-width, maximum-scale=1.0, initial-scale=1.0,initial-scale=1.0,user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="Keywords" content="我要借款-伽满优,车贷理财,车辆抵押,P2P投资理财,投资理财公司,短期理财,P2P投资理财平台" />
	<meta name="Description" content="我要借款-伽满优,通过公开透明的规范操作,平台为投资理财人士提供收益合理、安全可靠、高效灵活的车贷理财产品。" />
    <link href="/images/new_help/images/default.css" rel="stylesheet" type="text/css" />
	<link href="/images/new_help/images/index.css" rel="stylesheet" type="text/css" />
	<link href="/images/new_help/images/info.css" rel="stylesheet" type="text/css" />
    <link href="/src/css/layui.css" rel="stylesheet" />
	<script type="text/javascript" src="/images/jquery-1.7.2.min.js"></script>
	<script type="text/javascript" src="/images/globle.js"></script>
	<script type="text/javascript" src="/images/index.js"></script>
	<script src="/images/new/js/swiper.min.js"></script>
    <script src="/src/layui.js"></script>
	<style>
		.foot_nav h3 img.icon1{margin-top:0}
	</style>
</head>
<body>
<?php include("topa.php") ?>
<div class="borrows_v2">
	<div class="borrows_con_v2">
		<div class="boorows_con_tab_v2">
			<form class="borrows_form_1" style="display: none;" onsubmit="return false;" id="formrec">
				<div class="bor-form-item">
					<span class="bor_form_jk">借款类型：</span>
					<div class="bor_form_radio">
						<input class="geren_radio" type="radio" name="type" value="1" checked><label for="female">个人</label>
						<input class="qiye_radio" type="radio" name="type" value="2" ><label for="female">企业</label>
					</div>							
				</div>
				<div class="bor-form-item">
					<span>借款人姓名：</span>
					<input class="bor-from-input clear" type="text" name="name" lay-verify="required"  placeholder="必填,个人">
				</div>
				<div class="bor-form-item">
					<span>联系电话：</span>
					<input class="bor-from-input clear" type="text" name="phone" placeholder="必填,联系电话"  maxlength="11">
				</div>
				<div class="bor-form-item">
					<span>借款方式：</span>
					<input class="bor-from-input clear" type="text" name="jktpe"  placeholder="担保质押">
				</div>
				<div class="bor-form-item">
					<span>借款金额：</span>
					<input class="bor-from-input clear" type="text" name="money"  placeholder="必填,个人最多20万" onkeyup="value=value.replace(/[^\d]/g,'')" >
				</div>
				<div class="bor-form-item">
					<span>借款期限：</span>
					<select data-val="true" data-val-number="字段 Int32 必须是一个数字" data-val-required="Int32 字段是必需的" name="day">
						<?php foreach($this->config->item('borrow_duration') as $k => $v):?>
						<option value="<?php echo $v?>"><?php echo $v?>天</option>
						<?php endforeach;?>
					</select>
				</div>
				<div class="bor-form-item">
					<span>借款利率：</span>
					<select data-val="true" data-val-number="字段 Int32 必须是一个数字" data-val-required="Int32 字段是必需的" name="lx">
						<option selected="selected" value="6%">6%</option>
						<option value="7%">7%</option>
						<option value="8%">8%</option>
					</select>
				</div>
				<div class="bor-form-item">
					<span>借款用途：</span>
					<input class="bor-from-input clear" type="text" name="yt"  placeholder="必填,借款用途">
				</div>
				<div class="bor-form-item">
					<span style="margin-top: 9px;">担保人：</span>
					<span class="layui-badge" title="必填,担保人,担保质押物 ,质押物价格(每个内容为英文空格隔开;企业担保人可多人,以英文','号隔开)" style="width: 10px;float: right;z-index: 99;margin-top: 10px;margin-right: 10px;cursor: pointer;">?</span>
					<input class="bor-from-input clear" type="text" name="member"  placeholder="必填,担保人,担保质押物 ,质押物价格..." style="position: absolute;">
				</div>
				<div class="bor-form-item">
					<span>质押物：</span>
					<input class="bor-from-input clear" type="text" name="car"  placeholder="必填,借款人-借款人质押物">
				</div>
				<div class="bor-form-item">
					<span>借款日期：</span>
					<input class="bor-from-input clear" type="text" name="add_time"  placeholder="必填,借款申请日期">
				</div>
				<div class="bor_form-item"><button class="from-btn" lay-submit="" lay-filter="js-go" style="cursor:pointer">提交申请</button></div>
			</form>
			<form class="borrows_form_2" onsubmit="return false;" id="formrec">
				<div class="bor-form-item">
					<span class="bor_form_jk">借款类型：</span>
					<div class="bor_form_radio">
						<input class="geren_radio" type="radio" name="type" value="1"><label for="female">个人</label>
						<input class="qiye_radio" type="radio" name="type" value="2" checked><label for="female">企业</label>
					</div>
					
				</div>
				<div class="bor-form-item">
					<span>借款人姓名：</span>
					<input class="bor-from-input clear" type="text" name="name" lay-verify="required"  placeholder="必填,法人">
				</div>
				<div class="bor-form-item">
					<span>联系电话：</span>
					<input class="bor-from-input clear" type="text" name="phone" placeholder="必填,联系电话" maxlength="11">
				</div>
				<div class="bor-form-item">
					<span>借款方式：</span>
					<input class="bor-from-input clear" type="text" name="jktpe"  placeholder="担保质押">
				</div>
				<div class="bor-form-item">
					<span>借款金额：</span>
					<input class="bor-from-input clear" type="text" name="money"  placeholder="必填,企业最多100万,单位万" onkeyup="value=value.replace(/[^\d]/g,'')" >
				</div>
				<div class="bor-form-item">
					<span>借款期限：</span>
					<select data-val="true" data-val-number="字段 Int32 必须是一个数字" data-val-required="Int32 字段是必需的" name="day">
						<?php foreach($this->config->item('borrow_duration') as $k => $v):?>
						<option value="<?php echo $v?>"><?php echo $v?>天</option>
						<?php endforeach;?>
					</select>
				</div>
				<div class="bor-form-item">
					<span>借款利率：</span>
					<select data-val="true" data-val-number="字段 Int32 必须是一个数字" data-val-required="Int32 字段是必需的" name="lx">
						<option selected="selected" value="6%">6%</option>
						<option value="7%">7%</option>
						<option value="8%">8%</option>
					</select>
				</div>
				<div class="bor-form-item">
					<span>借款用途：</span>
					<input class="bor-from-input clear" type="text" name="yt"  placeholder="必填,借款用途">
				</div>
				<div class="bor-form-item">
					<span style="margin-top: 9px;">担保人：</span>
					<span class="layui-badge" title="必填,担保人,担保质押物 ,质押物价格(每个内容为英文空格隔开;企业担保人可多人,以英文','号隔开)" style="width: 10px;float: right;z-index: 99;margin-top: 10px;margin-right: 10px;cursor: pointer;">?</span>
					<input class="bor-from-input clear" type="text" name="member"  placeholder="必填,担保人,担保质押物 ,质押物价格..." style="position: absolute;">
				</div>
				<div class="bor-form-item">
					<span>质押物：</span>
					<input class="bor-from-input clear" type="text" name="car"  placeholder="必填,借款人-借款人质押物">
				</div>
				<div class="bor-form-item">
					<span>借款日期：</span>
					<input class="bor-from-input clear" type="text" name="add_time"  placeholder="必填,借款申请日期">
				</div>
				 <div class="bor-form-item"><button class="from-btn" lay-submit="" lay-filter="js-go" style="cursor:pointer">提交申请</button></div>
			</form>
			<script type="text/javascript">	
				layui.use(['layer', 'form'], function() {
					var $ = layui.$
					, form = layui.form
					, layer = layui.layer;
					
					form.on('submit(js-go)', function() {
						var obj = $(this);
						$.post('/suny/borrow.html', $(this).parents('form').serialize(), function(r) {
							var icon = r.state ? 6 : 5;
							layer.msg(r.message, {icon: icon, time: 1500}, function() {
								if(!r.state) {
									if(r.message == '手机号不能为空' || r.message == '请填写真实的手机号码') {
										obj.parents('form').find('input[name=phone]').css({'border': '1px solid red', 'width': '258px'});
										// $('input[name=phone]').css({border: '1px solid #000FFFFFF'});
										// console.log($('input[name=phone]').length);
										obj.parents('form').find('input[name=phone]').focus();
									} 
									
								} else {
									location.reload();
								}
							});
						}, 'json');
						return ;
					});
					
					$('input[name=phone]').blur(function() {
						$(this).css({'border': '0', 'width': '260px'});
					});
					
				});
			  	$(function(){
					$("input[name=type]").change(function(){
						var v = $(this).val();
						if (v =="1"){
							$(":radio").removeAttr('checked');
							$(".geren_radio").prop("checked","checked");
							$(".borrows_form_1").show();	
							$(".borrows_form_2").hide(); 
						}else if(v =="2"){
							$(":radio").removeAttr('checked');
							$(".qiye_radio").prop("checked","checked");
							$(".borrows_form_1").hide();
							$(".borrows_form_2").show();

						}
					});
					// $('.from-btn').click(function() {
						// $.post('/suny/borrow.html', $(this).parents('form').serialize(), function(r) {
							
						// }, 'json');
						// return ;
					// });
			    });
			</script>
		</div>
	</div>
</div>	
<?php include("foota.php") ?>
<style>
#Imageid {
	width: 150px;
	height: 29px;
	display: inline-block;
	position: absolute;
	right: 10px;
	top: 4px;
	cursor: pointer;
}
.bor-form-item select {
	padding-left: 8px;
    border-radius: 5px;
    height: 42px;
    border: 0px;
    width: 260px;
    color: #AFAFAF;
}
</style>
</body>
</html>