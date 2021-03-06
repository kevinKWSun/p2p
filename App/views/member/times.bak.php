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
    <title>冲抽奖次数</title>
    <link href="/src/css/layui.css" rel="stylesheet" />
    <script src="/src/layui.js"></script>
</head>
<body>
    <div class="main-wrap" style='padding:10px;'>
        <blockquote class="layui-elem-quote fhui-admin-main_hd">
            <h2>冲抽奖次数</h2>
        </blockquote>
        <form action="/member/times" class="layui-form layui-form-pane" id="formrec" method="post" role="form">
			<input type="hidden" name="uid" value="<?php echo $meminfo['uid']; ?>" />
            <div class="layui-form-item">
                <label class="layui-form-label">会员姓名</label>
				<div class="layui-input-inline">
                    <input value="<?php echo $meminfo['real_name'];?>" class="layui-input layui-btn-disabled" type="text" disabled />
                </div>
				
            </div>
			<div class="layui-form-item">
                <label class="layui-form-label">已有抽奖次数</label>
				<div class="layui-input-inline">
                    <input value="<?php echo $meminfo['times'];?>" class="layui-input layui-btn-disabled" type="text" disabled />
                </div>
            </div>
			<div class="layui-form-item">
                <label class="layui-form-label">增加抽奖次数</label>
                <div class="layui-input-inline">
                    <input name="times" autocomplete="off" lay-verify="required|number" placeholder="必填,次数" class="layui-input" type="text" maxlength="8" />
                </div>
            </div>
			<div class="layui-form-item">
                <label class="layui-form-label">倍数</label>
                <div class="layui-input-block">
                    <input name="types" value="1" title="单倍" type="radio" checked />
					<input name="types" value="2" title="双倍" type="radio" />
                </div>
            </div>
			<div class="layui-form-item">
                <label class="layui-form-label">备注</label>
                <div class="layui-input-inline">
                    <input name="remark" autocomplete="off" placeholder="备注" class="layui-input" type="text" maxlength="255" />
                </div>
            </div>
            <!--底部工具栏-->
            <div class="page-footer">
                <div class="btn-list">
                    <div class="btnlist">
                        <a class="layui-btn layui-btn-sm" lay-submit="" lay-filter="js-totalscore" data-url="/member/times.html"><i class="layui-icon">&#x1005;</i>提交</a>
                        <a class="layui-btn layui-btn-sm do-action" data-type="doRefresh" data-url=""><i class="layui-icon">&#xe669;</i>刷新</a>
                    </div>
                </div>
            </div>
            <!--/底部工具栏-->
        </form>
        
    </div>
	<script src="/src/global.js"></script>
	<script type="text/javascript">
		layui.use(['layer', 'form'], function() {
			var $ = layui.$
			, layer = layui.layer
			, form = layui.form;
			form.on('submit(js-totalscore)', function(data) {
				var url = $(this).data('url');
				$.post(url, data.field, function(r) {
					layer.msg(r.message, {time:1500}, function() {
						if(r.state == 1) {
							parent.location.reload();
						}
					});
				}, 'json');
			});
		});
	</script>
</body>
</html>
