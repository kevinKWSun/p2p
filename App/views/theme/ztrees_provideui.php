<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Language" content="zh-cn" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <meta name="viewport" content="width=device-width, maximum-scale=1.0, initial-scale=1.0,initial-scale=1.0,user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <title>发放多种水果</title>
	<link href="/src/css/layui.css" rel="stylesheet" />
    <script src="/src/layui.js"></script>

</head>
<body>
    <div class="main-wrap" style='padding:25px;'>
        <form action="/ztreesa/provide" class="layui-form layui-form-pane" id="formrec" method="post" role="form">
			<input name='uid' value='<?php echo $uid; ?>' type='hidden' />
			<div class="layui-form-item">
                <label class="layui-form-label">类型</label>
                <div class="layui-input-block">
                    <select name="type">
						<?php foreach($type as $k=>$v) { ?>
							<option value="<?php echo $k; ?>"><?php echo $v; ?></option>
						<?php } ?>
					</select>
                </div>
            </div>
			<div class="layui-form-item">
                <label class="layui-form-label">数量</label>
                <div class="layui-input-block">
                  <input name="num" autocomplete="off" class="layui-input" type="text" maxlength="1000"/>
                </div>
            </div>
			<div class="layui-form-item">
                <label class="layui-form-label">备注</label>
                <div class="layui-input-block">
                  <input name="remark" autocomplete="off" class="layui-input" type="text" maxlength="200"/>
                </div>
            </div>
            <!--底部工具栏-->
            <div class="page-footer">
                <div class="btn-list">
                    <div class="btnlist">
                        <a class="layui-btn layui-btn-sm" lay-submit="" lay-filter="js-order-modify" data-url="/ztreesa/provide"><i class="layui-icon">&#x1005;</i>提交</a>
                        <a class="layui-btn layui-btn-sm do-action" data-type="doRefresh" data-url=""><i class="layui-icon">&#xe669;</i>刷新</a>
                    </div>
                </div>
            </div>
            <!--/底部工具栏-->
        </form>
    </div>
    <script src="/src/global.js"></script>
	<script type="text/javascript">
		
		layui.use(['layer', 'form'], function(){
			var $ = layui.$
			, form = layui.form
			, layer = layui.layer;
			
			form.on('submit(js-order-modify)', function(data) {
				var url = $(this).data('url');
				$.post(url, data.field, function(r) {
					var icon = r.state ? 6 : 5;
					layer.msg(r.message, {'icon':icon, 'time': 1500}, function() {
						if(r.state == 1) {
							parent.location.reload();
							layer.closeAll();
						}
					});
					
				}, 'json');
				return false; 
			});
		});
	</script>
</body>
</html>