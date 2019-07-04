<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
	<head>
		<title>银行修改卡</title>
    <meta charset="utf-8">
    <meta http-equiv="Content-Language" content="zh-cn" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <meta name="viewport" content="width=device-width, maximum-scale=1.0, initial-scale=1.0,initial-scale=1.0,user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="Keywords" content="会员中心-伽满优,车贷理财,车辆抵押,P2P投资理财,投资理财公司,短期理财,P2P投资理财平台" />
		<meta name="Description" content="会员中心-伽满优,通过公开透明的规范操作,平台为投资理财人士提供收益合理、安全可靠、高效灵活的车贷理财产品。" />
		<link href="/images/default.css" rel="stylesheet" type="text/css" />
		<link href="/images/index.css" rel="stylesheet" type="text/css" />
		<link href="/images/new/user-info.css" rel="stylesheet" type="text/css" />
		<link href="/src/css/layui.css" rel="stylesheet" />
		<script src="/src/layui.js"></script>
		<script src="/images/jquery-1.7.2.min.js"></script>
	</head>
	<body style="width: 600px; height: 290px; overflow: hidden;">

			<div id="identity_box">
				<form class="layui-form" action="">  
					<div style="width: 560px;margin: 0 auto ;">
						<p>请根据提示分别上传您手持身份证和银行卡的正面照片</p>
						<div id="identity_one">
							<div id="preview_one"></div>
						</div>
						<div id="identity_two">
							<div id="preview_two"></div>
						</div>
					</div>
					<div class="clear"></div>
					<input type="hidden" name="uid" />
					<input id="identity_z" type="hidden" name="identity_z" />
					<input id="identity_f" type="hidden" name="identity_f" />
					<div style="text-align: center; margin-top: 20px;"><button type="button" class="layui-btn layui-btn-normal" id="testList">提交</button></div>
				</form>
			</div>

	</body>
		<script type="text/javascript">
			layui.use(['upload', 'form'], function () {
			  var $ = layui.jquery
				, upload = layui.upload
				, form = layui.form;
			  //支持拖拽上传
			  upload.render({
				elem: '#identity_one'
				,url: '/account/upload_card_img'
				, choose: function (obj) {  //选择图片后事件
					 //预读本地文件示例，不支持ie8
						obj.preview(function (index, file, result) {
						$('#preview_one').css('background-image','url('+result+')');            
					});
				}
				,done: function(res){
						//接口示例 {code: 200, msg: "上传成功！", data: "./uploads/20181121072340159.png"}
						console.log(res);
						$('#identity_z').val(res.data);
				}
			  });    
				
			  upload.render({
				elem: '#identity_two'
				,url: '/account/upload_card_img'
				, choose: function (obj) {  
					obj.preview(function (index, file, result) {
							$('#preview_two').css('background-image','url('+result+')');            
					});
				}
				,done: function(res){  //上传成功后的 回调函数
				  $('#identity_f').val(res.data);
				}
			  });
				
				$("#testList").on('click',function() {
					var hand_identity_card = $('#identity_z').val();
					var hand_bank_card = $('#identity_f').val();
					var url = '/account/upbinds';				
					
					if(hand_identity_card=='' || hand_bank_card == ''){	
						layer.msg( "未按要求上传图片" , {icon: 6,time : 3500});		
						return false;
					}					
					$.post(url, {hand_bank_card:hand_bank_card,hand_identity_card:hand_identity_card}, function(result){
							if(result.code == 200){							
								layer.msg( result.msg , {icon: 6,time : 3500});		
							}
							setTimeout(function(){ 
								var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
								parent.layer.close(index); 
							}, 3000);
					});
				});

	
			});
		</script>
</html>
