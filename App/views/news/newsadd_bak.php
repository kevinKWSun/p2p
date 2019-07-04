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
    <title>增加新闻</title>
	<link href="/src/css/layui.css" rel="stylesheet" />
    <script src="/src/layui.js"></script>

</head>
<body>
    <div class="main-wrap" style='padding:10px;'>
        <blockquote class="layui-elem-quote fhui-admin-main_hd">
            <h3>增加新闻　<button type="button" class="layui-btn" id="test10"><i class="layui-icon"></i>上传图片</button></h3>
        </blockquote>
        <form action="/news/add" class="layui-form layui-form-pane" id="formrec" method="post" role="form">
            <div class="layui-form-item">
                <div class="layui-input-inline">
					<img id='img' src='/src/images/none.png' width='100' style='display:none;' />
                    <input name="img" value="" type="hidden">
                </div>
            </div>
			<div class="layui-form-item">
                <label class="layui-form-label">分类名称</label>
                <div class="layui-input-block">
                    <select data-val="true" data-val-number="字段 Int32 必须是一个数字" data-val-required="Int32 字段是必需的" name="cid">
                        <option selected="selected" value="0">选择所属权限</option>
                        <?php foreach ($cate as $v): ?>
                        <option value="<?php echo $v['id']; ?>"><?php echo $v['type_name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
			<div class="layui-form-item">
                <label class="layui-form-label">标题名称</label>
                <div class="layui-input-block">
                    <input name="title" autocomplete="off" lay-verify="required" value="" placeholder="必填,标题名称" class="layui-input" type="text">
                </div>
            </div>
			<div class="layui-form-item">
				<fieldset class="layui-elem-field layui-field-title">
					<legend>内容</legend>
				</fieldset>
				<div id="editor">
					<p>欢迎使用 <b>we</b> 富文本编辑器</p>
				</div>
				<textarea class="layui-textarea" id="LAY_demo2" placeholder="必填,内容信息"></textarea>
				<input name='content' type='hidden' value='info' />
            </div>
            <!--底部工具栏-->
            <div class="page-footer">
                <div class="btn-list">
                    <div class="btnlist">
                        <a class="layui-btn layui-btn-sm" lay-submit="" lay-filter="btnsubmit" data-url="/news/add"><i class="layui-icon">&#xe616;</i>提交</a>
                        <a class="layui-btn layui-btn-sm do-action" data-type="doRefresh" data-url=""><i class="layui-icon">&#x1002;</i>刷新</a>
                        <a class="layui-btn layui-btn-sm do-action" data-type="doGoBack"><i class="layui-icon">&#xe65c;</i>返回上一页</a>
                        <a class="layui-btn layui-btn-sm do-action" data-type="doGoTop" data-url=""><i class="layui-icon">&#xe604;</i>返回顶部</a>
                    </div>
                </div>
            </div>
            <!--/底部工具栏-->
        </form>
    </div>
    <script src="/src/global.js"></script>
	<script src="/We/release/wangEditor.min.js"></script>
	<script>
		layui.use(['layedit','layer', 'form', 'upload'], function(){
            var layedit = layui.layedit
                ,$ = layui.$
                , layer = layui.layer
                , form = layui.form
				, upload = layui.upload;
			/* upload.render({
				elem: '#test10'
				,url: '/login/do_upload'
				,type: 'post'
				,done: function(res){
					//console.log(res)
					if(res.code == 0){
						$('input[name=img]').val(res.data.src);
						$('#img').attr('src', res.data.src).show();
					}else{
						layer.msg(res.errorMsg);
					}
				}
			});
			layedit.set({
				uploadImage: {
					url: '/login/do_upload'
				  , type: 'post'
				  , success: function (res) {
					if(res.code == 200){
						res.data = res.data;
					}else{
						layer.msg(res.errorMsg);
					}
				  }
				}
			});
            var index = layedit.build('LAY_demo2', {
                height: 450
            }); */
			var E = window.wangEditor;
			var editor = new E('#editor');
			editor.create();
			alert(editor.txt.html());
			form.on('submit(btnsubmit)', function (data) {
				var url = $(this).data('url');
				if(data.field.content == 'info'){
					data.field.content = layedit.getContent(index);
				}
				layer.load(1);
				$.post(url,data.field,function(s){
					layer.msg(s.message);
					if(s.state == 1){
						self.location.href = s.url;
					}else{
						layer.closeAll();
					}
				});
				return false;
			});
        });
	</script>
</body>
</html>