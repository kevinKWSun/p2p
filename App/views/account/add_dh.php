<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>兑换商品-伽满优</title>
    <meta http-equiv="Content-Language" content="zh-cn" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <meta name="viewport" content="width=device-width, maximum-scale=1.0, initial-scale=1.0,initial-scale=1.0,user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="Keywords" content="兑换商品-伽满优,车贷理财,车辆抵押,P2P投资理财,投资理财公司,短期理财,P2P投资理财平台" />
	<meta name="Description" content="兑换商品-伽满优,通过公开透明的规范操作,平台为投资理财人士提供收益合理、安全可靠、高效灵活的车贷理财产品。" />
    <link href="/src/css/layui.css" rel="stylesheet" />
    <script src="/src/layui.js"></script>
</head>
<body>
	<div class="main-wrap" style='padding:10px;'>
		<form class="layui-form layui-form-pane" id="formrec" method="post" role="form">
			<input name='id' value='<?php echo $goods['id']?>' type='hidden' />
			<div class="layui-form-item">
				<label class="layui-form-label">商品名称</label>
				<div class="layui-input-block">
					<input type="text" autocomplete="off" class="layui-input" placeholder="<?php echo $goods['gname']?>" disabled>
				</div>
			</div>
			<div class="layui-form-item">
				<label class="layui-form-label">应付积分</label>
				<div class="layui-input-block">
					<input type="text" autocomplete="off" class="layui-input" placeholder="<?php echo $goods['score']?>" disabled>
				</div>
			</div>
			<div class="layui-form-item">
				<label class="layui-form-label">可用积分</label>
				<div class="layui-input-block">
					<input type="text" autocomplete="off" class="layui-input" placeholder="<?php echo $info['totalscore']?>" disabled>
				</div>
			</div>
			<div class="layui-form-item">
				<label class="layui-form-label">选择数量</label>
				<div class="layui-input-inline">
					<input type="text" name='num' class="layui-input" value="1" />
				</div>
				<a href='javascript:;' class="layui-btn layui-btn-danger jia">+</a>
				<a href='javascript:;' class="layui-btn layui-btn-danger jian">-</a>
				<a href='javascript:;' class="layui-btn layui-btn-danger add">增加地址</a>
			</div>
			<div class="layui-form-item">
				<label class="layui-form-label">选择地址</label>
				<div class="layui-input-block">
					<select name="aid">
						<option value="">请选择地址</option>
						<?php foreach($address as $v):?>
						<option value="<?php echo $v['id']?>"><?php echo $v['address']?></option>
						<?php endforeach;?>
					</select>
				</div>
			</div>	
			<div class="page-footer">
                <div class="btn-list">
                    <div class="btnlist">
                        <center><a class="layui-btn  layui-btn-danger" lay-submit="" lay-filter="doPostParent" data-url="/mall/addlist.html"><i class="layui-icon">&#x1005;</i>提交</a></center>
                    </div>
                </div>
            </div>
		</form>
	</div>
<script src="/src/global.js"></script>
<script type="text/javascript">
	layui.use(['layer', 'form', 'laydate'], function () {
		var $ = layui.$
		, layer = layui.layer
		, form = layui.form;
		$('a.jia').on('click', function(){
			var num = $('input[name=num]').val();
			if(num > 0){
				$('input[name=num]').val(num*1+1);
			}else{
				$('input[name=num]').val(1);
			}
		});
		$('a.jian').on('click', function(){
			var num = $('input[name=num]').val();
			if(num > 1){
				$('input[name=num]').val(num*1-1);
			}else{
				$('input[name=num]').val(1);
			}
		});
		$('a.add').on('click', function(){
			parent.location.href='/mall/address.html';
		});
	});		
</script>
</body>
</html>