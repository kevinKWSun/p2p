<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>修改手机号-伽满优</title>
    <meta http-equiv="Content-Language" content="zh-cn" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <meta name="viewport" content="width=device-width, maximum-scale=1.0, initial-scale=1.0,initial-scale=1.0,user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="Keywords" content="修改手机号-伽满优,车贷理财,车辆抵押,P2P投资理财,投资理财公司,短期理财,P2P投资理财平台" />
	<meta name="Description" content="修改手机号-伽满优,通过公开透明的规范操作,平台为投资理财人士提供收益合理、安全可靠、高效灵活的车贷理财产品。" />
    <link href="/src/css/layui.css" rel="stylesheet" />
    <script src="/src/layui.js"></script>
</head>
<body>
<div class="main-wrap" style='padding:10px;'>
	<blockquote class="layui-elem-quote" style='border-left:5px solid #FF5722'>
		<h3>修改手机号</h3>
	</blockquote>
	<div class="y-role">
		<div class="fhui-admin-table-container">
			<form id="formrec" method="post" role="form">
				<div class="layui-form-item">
					<label class="layui-form-label" style='font-size:14px'>原手机号</label>
					<div class="layui-input-inline">
						<input  class="layui-input" type="text" value="<?php echo $info['phone']?>" disabled name='oldtel' />
					</div>
				</div>
				<div class="layui-form-item">
					<label class="layui-form-label" style='font-size:14px'>新手机号</label>
					<div class="layui-input-inline">
						<input autocomplete="off" lay-verify="required" value="" placeholder="必填,新手机号" class="layui-input" type="text" onkeyup="value=value.replace(/[^\d.]/g,'');" onblur="value=value.replace(/[^\d.]/g,'');;" maxlength="11" name='tel'/>
					</div>
				</div>
				<div class="layui-form-item">
					<label class="layui-form-label" style='font-size:14px'></label>
					<div class="layui-input-block" style='font-size:14px;line-height:35px;'>
						<a class="layui-btn layui-btn-danger" id="js-recharge_wg" data-url="/safe/domodify">下一步</a>
					</div>
				</div>
				<div class="layui-form-item" style='font-size:14px;line-height:35px;'>
					<label class="layui-form-label">温馨提示</label>
					<div class="layui-input-block">
						<em style="color:Red;">1、新号码不能与原号码一致</em><br>2、如有问题请联系平台客服：021-62127903
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<script type="text/javascript">
	layui.use(['layer', 'form'], function() {
		var $ = layui.$
		,layer = layui.layer
		,form = layui.form;
		$('#js-recharge_wg').on('click',function(){
			var url = $(this).attr('data-url');
			if(url) {
				var tel = $('input[name=tel]').val();
				var oldtel = $('input[name=oldtel]').val();
				if(tel) {
					if(tel == oldtel){
						layer.msg('手机号不能相同', {time:1500});
					}else{
						parent.location.href = url+'/'+tel
					}
				} else {
					layer.msg('手机号不能为空', {time:1500});
				}
			} else {
				return false;
			}
			
		});
	});
</script>
</body>
</html>