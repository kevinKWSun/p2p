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
    <title>撤销红包</title>
	<link href="/src/css/layui.css" rel="stylesheet" />
    <script src="/src/layui.js"></script>

</head>
<body>
    <div class="main-wrap" style='padding:10px;'>
        <blockquote class="layui-elem-quote fhui-admin-main_hd">
            <h3>撤销红包</h3>
        </blockquote>
        <form action="/packet/revoke" class="layui-form layui-form-pane" id="formrec" method="post" role="form">
			<input name='id' value='<?php echo $id; ?>' type='hidden' />
			<div class="layui-form-item">
                <label class="layui-form-label">撤销原因</label>
                <div class="layui-input-block">
                  <input name="remark" autocomplete="off" class="layui-input" type="text" maxlength="200"/>
                </div>
            </div>
            <!--底部工具栏-->
            <div class="page-footer">
                <div class="btn-list">
                    <div class="btnlist">
                        <a class="layui-btn layui-btn-sm" lay-submit="" lay-filter="js-revoke" data-url="/packet/revoke"><i class="layui-icon">&#x1005;</i>撤销</a>
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
			
			form.on('submit(js-revoke)', function(data) {
				var url = $(this).data('url');
				$.post(url, data.field, function(r) {
					var icon = r.state ? 6 : 5;
					layer.msg(r.message, {'icon':icon, 'time': 1500}, function() {
						if(r.state == 1) {
							layer.closeAll();
							parent.location.reload();
						}
					});
					
				}, 'json');
				return false; 
			});
		});
	</script>
</body>
</html>