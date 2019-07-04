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
    <title>发红包</title>
    <link href="/src/css/layui.css" rel="stylesheet" />
    <script src="/src/layui.js"></script>
</head>
<body>
    <div class="main-wrap" style='padding:10px;'>
		<blockquote class="layui-elem-quote fhui-admin-main_hd">
            <h2>发红包</h2>
        </blockquote>
		<form class="layui-form layui-form-pane form-horizontal" id="formrec" method="post" role="form">
			<div class="layui-form-item">
				<label class="layui-form-label">投资人</label>
				<div class="layui-input-block">
					<?php foreach($investor as $k=>$v) { ?>
						<input type="checkbox" name="investor_uid[<?php echo $k; ?>]" lay-skin="primary" title="<?php echo get_member_info($k)['real_name'] . '['.$v['investor_capital'].'元]'; ?>" value="<?php echo $v['investor_capital']; ?>" checked="">
					<?php } ?>
				</div>
			</div>
			<div class="layui-form-item">
				<label class="layui-form-label">红包类型</label>
				<div class="layui-input-block">
					<input type="radio" name="status" value="cash" title="现金红包" checked="" lay-filter="status"/>
					<input type="radio" name="status" value="invest" title="投资红包" lay-filter="status"/>
					<input type="radio" name="status" value="all" title="两者都有" lay-filter="status"/>
				</div>
			</div>
			<div id="cash" class="all">
				
			</div>
			<div id="invest" class="all">
				
			</div>
			<div class="page-footer">
                <div class="btn-list">
                    <div class="btnlist">
						<?php if(is_rule('/borrow/send')) { ?>
							<a class="layui-btn layui-btn-sm" lay-submit="" lay-filter="doPost" data-url="/borrow/send/<?php echo $id; ?>.html"><i class="layui-icon">&#x1005;</i>提交</a>
						<?php } ?>
                        <a class="layui-btn layui-btn-sm do-action" data-type="doRefresh" data-url=""><i class="layui-icon">&#xe669;</i>刷新</a>
                        <a class="layui-btn layui-btn-sm do-action" data-type="doGoBack"><i class="layui-icon">&#xe65c;</i>返回上一页</a>
                        <a class="layui-btn layui-btn-sm do-action" data-type="doGoTop" data-url=""><i class="layui-icon">&#xe604;</i>返回顶部</a>
                    </div>
                </div>
            </div>
		</form>
    </div>
    <script src="/src/global.js"></script>
	<script type="text/javascript">
		layui.use(['layer', 'form'], function(){
			var $ = layui.$
			, form = layui.form
			, layer = layui.layer;
			
			var cash = '<fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;width:28%;"><legend>现金红包</legend></fieldset><div class="layui-form-item"><label class="layui-form-label">红包占比</label><div class="layui-input-inline"><input type="text" name="cash[]" lay-verify="required" placeholder="必填" autocomplete="off" class="layui-input"></div><div class="layui-form-mid layui-word-aux">%<button class="layui-btn layui-btn-sm" id="cash-add">增加</button><button class="layui-btn layui-btn-sm" id="cash-del">删除</button></div></div><div class="layui-form-item layui-form-text" style="width:27%;"><label class="layui-form-label">备注</label><div class="layui-input-block"><textarea lay-verify="required" placeholder="必填" name="remark[]" class="layui-textarea" maxlength="300"></textarea></div></div>';
			var invest = '<fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;width:28%;"><legend>投资红包</legend></fieldset><div class="layui-form-item"><label class="layui-form-label">红包占比</label><div class="layui-input-inline"><input type="text" name="invest[]" lay-verify="required" placeholder="必填" autocomplete="off" class="layui-input"></div><div class="layui-form-mid layui-word-aux">%<button class="layui-btn layui-btn-sm" id="invest-add">增加</button><button class="layui-btn layui-btn-sm" id="invest-del">删除</button></div></div><div class="layui-form-item"><label class="layui-form-label">投资下限</label><div class="layui-input-inline"><input type="text" name="min_money[]" lay-verify="required" placeholder="必填" autocomplete="off" class="layui-input"></div><div class="layui-form-mid layui-word-aux">元[100或100的整数倍]</div></div><div class="layui-form-item"><label class="layui-form-label">期限下限</label><div class="layui-input-inline"><input type="text" name="times[]" lay-verify="required" placeholder="必填" autocomplete="off" class="layui-input"></div><div class="layui-form-mid layui-word-aux">天[输入0无下限]</div></div><div class="layui-form-item"><label class="layui-form-label">有效期</label><div class="layui-input-inline"><input type="text" name="etime[]" lay-verify="required" placeholder="必填" autocomplete="off" class="layui-input"></div><div class="layui-form-mid layui-word-aux">天</div></div>';
			$(cash).appendTo($('#cash'));
			form.on('radio(status)', function(data){
				if(data.value == 'all') {
					$('.all').children().remove();
					$(invest).appendTo($('#invest'));
					$(cash).appendTo($('#cash'));
				} else if(data.value == 'invest') {
					$('.all').children().remove();
					$(invest).appendTo($('#invest'));
				} else if(data.value == 'cash') {
					$('.all').children().remove();
					$(cash).appendTo($('#cash'));
				}
				return false;
			});  
			
			$('#cash').on('click', '#cash-add', function() {
				$('<div class="layui-form-item js-cash-add1"><label class="layui-form-label">红包占比</label><div class="layui-input-inline"><input type="text" name="cash[]" lay-verify="required" placeholder="必填" autocomplete="off" class="layui-input"></div><div class="layui-form-mid layui-word-aux">%</div></div>').appendTo($('#cash'));
				$('<div class="layui-form-item layui-form-text js-cash-add2" style="width:27%;"><label class="layui-form-label">备注</label><div class="layui-input-block"><textarea lay-verify="required" placeholder="必填" name="remark[]" class="layui-textarea" maxlength="300"></textarea></div></div>').appendTo($('#cash'));
				return false;
			});
			$('#cash').on('click', '#cash-del', function() {
				if($('#cash>.layui-form-text').length > 1) {
					$('.js-cash-add1:last').remove();
					$('.js-cash-add2:last').remove();
				}
				return false;
			});
			$('#invest').on('click', '#invest-add', function() {
				$('<div class="layui-form-item js-invest-add1"><label class="layui-form-label">红包占比</label><div class="layui-input-inline"><input type="text" name="invest[]" lay-verify="required" placeholder="必填" autocomplete="off" class="layui-input"></div><div class="layui-form-mid layui-word-aux">%</div></div>').appendTo($('#invest'));
				$('<div class="layui-form-item js-invest-add2"><label class="layui-form-label">投资下限</label><div class="layui-input-inline"><input type="text" name="min_money[]" lay-verify="required" placeholder="必填" autocomplete="off" class="layui-input"></div><div class="layui-form-mid layui-word-aux">元[100或100的整数倍]</div></div>').appendTo($('#invest'));
				$('<div class="layui-form-item js-invest-add3"><label class="layui-form-label">期限下限</label><div class="layui-input-inline"><input type="text" name="times[]" lay-verify="required" placeholder="必填" autocomplete="off" class="layui-input"></div><div class="layui-form-mid layui-word-aux">天[输入0无下限]</div></div>').appendTo($('#invest'));
				$('<div class="layui-form-item js-invest-add4"><label class="layui-form-label">有效期</label><div class="layui-input-inline"><input type="text" name="etime[]" lay-verify="required" placeholder="必填" autocomplete="off" class="layui-input"></div><div class="layui-form-mid layui-word-aux">天</div></div>').appendTo($('#invest'));
				return false;
			});
			$('#invest').on('click', '#invest-del', function() {
				$('.js-invest-add1:last').remove();
				$('.js-invest-add2:last').remove();
				$('.js-invest-add3:last').remove();
				$('.js-invest-add4:last').remove();
				return false;
			});
		});
		
	</script>
</body>
</html>