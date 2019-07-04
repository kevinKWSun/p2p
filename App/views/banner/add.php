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
    <title>增加导航图</title>
    <link href="/src/css/layui.css" rel="stylesheet" />
    <script src="/src/layui.js"></script>
	<style>
		.ds,span.s{display:none;}
	</style>
</head>
<body>
    <div class="main-wrap" style='padding:10px;'>
        <blockquote class="layui-elem-quote fhui-admin-main_hd">
            <h2>增加导航图</h2>
        </blockquote>
        <form action="/banner/add" class="layui-form layui-form-pane" id="formrec" method="post" role="form">
            <div class="layui-form-item">
                <label class="layui-form-label">名称</label>
				<div class="layui-input-inline">
					<input name='name' type="text" autocomplete="off" lay-verify="required" placeholder="必填,名称" class="layui-input"  maxlength="20"/>
				</div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">描述</label>
				<div class="layui-input-inline">
					<input name='info' type="text" autocomplete="off" lay-verify="required" placeholder="必填,描述" class="layui-input"  maxlength="50"/>
				</div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">类型</label>
                <div class="layui-input-inline">
                    <select data-val-required="Int32 字段是必需的" name="type">
                        <?php foreach($cate as $k => $v):?>
                        <option value="<?php echo $k?>"><?php echo $v?></option>
						<?php endforeach;?>
                    </select>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">排序</label>
				<div class="layui-input-inline">
					<input name='sort' type="text" autocomplete="off" lay-verify="required|number" placeholder="必填,排序" class="layui-input"  maxlength="10"/>
				</div>
            </div>
			<div class="layui-form-item">
				<button type="button" class="layui-btn" id="test10"><i class="layui-icon"></i>上传图片</button>
			</div>
			<div class="layui-form-item">
                <div class="layui-input-inline">
					<img id='img' src='' width='100' style='display:none;' />
                    <input name="img" value="" type="hidden">
                </div>
            </div>
            <!--底部工具栏-->
            <div class="page-footer">
                <div class="btn-list">
                    <div class="btnlist">
						<?php if(is_rule('/banner/add')) { ?>
							<a class="layui-btn layui-btn-small" lay-submit="" lay-filter="btnsubmit" data-url="/banner/add"><i class="layui-icon">&#x1005;</i>提交</a>
						<?php  } ?>
                        <a class="layui-btn layui-btn-small do-action" data-type="doRefresh" data-url=""><i class="layui-icon">&#xe669;</i>刷新</a>
                        <a class="layui-btn layui-btn-small do-action" data-type="doGoBack" data-url=""><i class="layui-icon">&#xe65c;</i>返回上一页</a>
                        <a class="layui-btn layui-btn-small do-action" data-type="doGoTop" data-url=""><i class="layui-icon">&#xe604;</i>返回顶部</a>
                    </div>
                </div>
            </div>
            <!--/底部工具栏-->
        </form>
    </div>
    <script src="/src/global.js"></script>
    <script>
        layui.use(['layedit','layer', 'form', 'upload'], function(){
            var layedit = layui.layedit
                ,$ = layui.$
                , layer = layui.layer
                , form = layui.form
				, upload = layui.upload;
			upload.render({
				elem: '#test10'
				,url: '/login/do_upload/1.html'
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
			form.on('submit(btnsubmit)', function (formdata) {
				 $.post($(this).attr('data-url'),formdata.field,function(s){
					layer.msg(s.message, {time:1500}, function() {
						if(s.state == 1){
							self.location.href = s.url;
						}
					});
                });
			});
           
        });
</script>
</body>
</html>