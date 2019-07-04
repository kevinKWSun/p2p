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
    <title>编辑标的信息</title>
    <link href="/src/css/layui.css" rel="stylesheet" />
    <script src="/src/layui.js"></script>
	<style>
		.ds,span.s{display:none;}
	</style>
</head>
<body>
    <div class="main-wrap" style='padding:10px;'>
        <blockquote class="layui-elem-quote fhui-admin-main_hd">
            <h2>编辑标的信息</h2>
        </blockquote>
		<?php if(!empty($borrowing['remark'])) { ?>
			<div class="layui-form layui-form-pane layui-form-item">
				<label class="layui-form-label"><font color="red">退回原因</font></label>
				<div class="layui-input-block layui-input" style="padding : 8px 0px 8px 15px;width:80%;">
					<font color="red"><?php echo $borrowing['remark']; ?></font>
				</div>
			</div>
		<?php } ?>
		
        <form action="/borrowing/add" class="layui-form layui-form-pane" id="formrec" method="post" role="form">
			<input type="hidden" name="id" value="<?php echo $borrowing['id']; ?>" />
			<input type="hidden" name="current_page" value="<?php echo $current_page; ?>" />
            <div class="layui-form-item">
                <label class="layui-form-label">收款人</label>
				<div class="layui-input-inline">
					<input autocomplete="off" lay-verify="required" value="<?php echo get_member_info($borrowing['borrow_uid'])['real_name']; ?>" placeholder="必填,点击选择借款人姓名" class="layui-input" type="text" readonly id='borrow_name' />
					<input name='borrow_uid' id='borrow_uid' type='hidden' value="<?php echo $borrowing['borrow_uid']; ?>" />
				</div>
				<label class="layui-form-label">收款类型</label>
                <div class="layui-input-inline">
                    <select data-val="true" data-val-number="字段 Int32 必须是一个数字" data-val-required="Int32 字段是必需的" name="type">
                        <?php foreach($this->config->item('borrows_type') as $k => $v):?>
							<option value="<?php echo $k?>" <?php if($borrowing['type'] == $k) { echo 'selected'; } ?>><?php echo $v?></option>
						<?php endforeach;?>
                    </select>
                </div>
                <label class="layui-form-label">标名称</label>
                <div class="layui-input-inline">
                    <input name="borrow_name" autocomplete="off" lay-verify="required" value="<?php echo $borrowing['borrow_name']; ?>" placeholder="必填,借款标题名" class="layui-input" type="text" >
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">金额(元)</label>
                <div class="layui-input-inline">
                    <input name="borrow_money" autocomplete="off" lay-verify="required|number" value="<?php echo intval($borrowing['borrow_money']); ?>"  onkeyup="value=value.replace(/[^\d]/g,'');" placeholder="必填,金额" class="layui-input" type="text">
                </div>
                <label class="layui-form-label">类型</label>
                <div class="layui-input-inline">
                    <select lay-filter="l" data-val="true" data-val-number="字段 Int32 必须是一个数字" data-val-required="Int32 字段是必需的" name="borrow_type">
                        <?php foreach($this->config->item('borrow_type') as $k => $v):?>
                        <option value="<?php echo $k?>" <?php if($borrowing['borrow_type'] == $k) { echo 'selected'; } ?>><?php echo $v?></option>
						<?php endforeach;?>
                    </select>
                </div>
                <label class="layui-form-label">期限(天)</label>
                <div class="layui-input-inline">
					<select data-val="true" data-val-number="字段 Int32 必须是一个数字" data-val-required="Int32 字段是必需的" name="borrow_duration">
                        <?php foreach($this->config->item('borrow_duration') as $k => $v):?>
                        <option value="<?php echo $k?>" <?php if($borrowing['borrow_duration'] == $k) { echo 'selected'; } ?>><?php echo $v?></option>
						<?php endforeach;?>
                    </select>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">起投(元)</label>
                <div class="layui-input-inline">
                    <input name="borrow_min" autocomplete="off" lay-verify="number" value="<?php echo intval($borrowing['borrow_min']); ?>" placeholder="必填,单笔最小投资额" class="layui-input" type="text">
                </div>
                <label class="layui-form-label">终投(元)</label>
                <div class="layui-input-inline">
                    <input name="borrow_max" autocomplete="off" lay-verify="" value="<?php echo intval($borrowing['borrow_max']); ?>" placeholder="选填,单笔最大投资额" class="layui-input" type="text">
                </div>
                <label class="layui-form-label">还款方式</label>
                <div class="layui-input-inline"><select data-val="true" data-val-number="字段 Int32 必须是一个数字" data-val-required="Int32 字段是必需的" name="repayment_type">
						<?php foreach($this->config->item('repayment_type') as $k => $v):?>
                        <option value="<?php echo $k?>" <?php if($borrowing['repayment_type'] == $k) { echo 'selected'; } ?>><?php echo $v?></option>
						<?php endforeach;?>
                    </select>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">利率(%)</label>
                <div class="layui-input-inline">
                    <input name="borrow_interest_rate" autocomplete="off" lay-verify="required" value="<?php echo $borrowing['borrow_interest_rate']; ?>" placeholder="必填,年化利率" class="layui-input" type="text">
                </div>
                <label class="layui-form-label">服务费(%)</label>
                <div class="layui-input-inline">
                    <input name="service_money" autocomplete="off" value="<?php echo $borrowing['service_money']; ?>" placeholder="必填,服务费比率" class="layui-input" type="text">
                </div>
                <label class="layui-form-label">图片 <span class="layui-badge" lay-tips="一次性全选上传,否则将被覆盖!">?</span></label>
                <div class="layui-input-inline">
					<button type="button" class="layui-btn" id="uppic"><i class="layui-icon">　</i>上传文件/全选上传</button>
					<input name="pic" autocomplete="off" lay-verify="" placeholder="必填" class="layui-input" type="hidden" >
                </div>
            </div>
			<div class="layui-form-item">
                <label class="layui-form-label">加息(%)</label>
                <div class="layui-input-inline">
                    <input name="add_rate" autocomplete="off" lay-verify="" value="<?php echo $borrowing['add_rate']; ?>" placeholder="选填,年化利率" class="layui-input" type="text">
                </div>
                <label class="layui-form-label">推荐</label>
                <div class="layui-input-inline">
                    <select data-val="true" data-val-number="字段 Int32 必须是一个数字" data-val-required="Int32 字段是必需的" name="is_tuijian">
                        <option value="0" <?php if($borrowing['is_tuijian'] == 0) { echo 'selected'; } ?>>正常</option>
						<option value="1" <?php if($borrowing['is_tuijian'] == 1) { echo 'selected'; } ?>>推荐</option>
						<option value="2" <?php if($borrowing['is_tuijian'] == 2) { echo 'selected'; } ?>>热门</option>
                    </select>
                </div>
                <label class="layui-form-label">密码</label>
                <div class="layui-input-inline">
					<input name="password" autocomplete="off" lay-verify="" value="<?php echo $borrowing['password']; ?>" placeholder="选填,密码" class="layui-input" type="text">
                </div>
            </div>
			<div class="layui-form-item">
                <label class="layui-form-label">募集时间</label>
                <div class="layui-input-inline">
                    <input name="number_time" autocomplete="off" lay-verify="" value="<?php echo $borrowing['number_time']; ?>" placeholder="必填,募集时间/天" class="layui-input" type="text">
                </div>
				<label class="layui-form-label">借款用途</label>
                <div class="layui-input-inline">
                    <input name="borrow_use" autocomplete="off" lay-verify="required" value="<?php echo $borrowing['borrow_use']; ?>" placeholder="必填,借款用途" class="layui-input" type="text" maxlength="20">
                </div>
				<label class="layui-form-label">选择担保人</label>
                <div class="layui-input-inline">
                    <input id="js-guarantor" placeholder="必填,点击选择担保人姓名" class="layui-input" type="text" readonly />
                </div>
            </div>
			<div class="layui-form-item">
                <label class="layui-form-label">标的编号</label>
                <div class="layui-input-inline">
                    <input name="borrow_no" autocomplete="off" lay-verify="required" value="<?php echo $borrowing['borrow_no']; ?>" placeholder="必填,标的编号" class="layui-input" type="text" maxlength="8">
                </div>
				<label class="layui-form-label">项目风险等级</label>
                <div class="layui-input-inline">
					<select name="grade">
						<?php foreach($this->config->item('grade') as $v) { ?>
							<option value="<?php echo $v; ?>" <?php if($borrowing['grade'] == $v) { echo 'selected'; } ?>><?php echo $v; ?></option>
						<?php } ?>
					</select>
                </div>
            </div>
			<fieldset class="layui-elem-field layui-field-title" style="width: 919px;">
                <legend>担保人</legend>
            </fieldset>
			<div class="layui-form-item" style="width: 919px;" id="js-guarantore">
				<?php if(!empty($borrowing['guarantor'])) { ?>
					<?php foreach($borrowing['guarantor'] as $k=>$v) { ?>
						<span style="margin-right:10px;"><a class="layui-btn layui-btn-primary layui-btn-sm" style="margin-bottom:10px;cursor:default;"><?php echo $v['name']; ?><i class="layui-icon layui-icon-close" style="vertical-align:middle;margin-left:5px;cursor:pointer;"></i></a><input type="hidden" name="guarantor[<?php echo $k; ?>]" value="<?php echo $v['id'] ?>"/></span>
					<?php } ?>
				<?php } ?>
			</div>
			<fieldset class="layui-elem-field layui-field-title" style="width: 919px;">
                <legend>车辆图片</legend>
            </fieldset>
			<div class="layui-form-item">
				<?php if($borrowing['pic']) { foreach(explode(',', $borrowing['pic']) as $v) { ?>
				<div class="layui-inline slts"><img height='200' src="<?php echo $v ?>" /></div>
				<?php } } ?>
				<div class="layui-inline pic"></div>
			</div>
            <fieldset class="layui-elem-field layui-field-title" style="width: 919px;">
                <legend>借款信息介绍(必填)</legend>
            </fieldset>
            <div class="layui-form-item" style="width: 919px;">
                <textarea class="layui-textarea" id="LAY_demo2" placeholder="必填,借款信息介绍"><?php echo $borrowing['borrow_info']; ?></textarea>
                <input  name='borrow_info' type='hidden' value='info' />
            </div>
			<fieldset class="layui-elem-field layui-field-title" style="width: 919px;">
                <legend>还款来源(必填)</legend>
            </fieldset>
            <div class="layui-form-item" style="width: 919px;">
                <textarea class="layui-textarea" id="LAY_demo3" placeholder="必填,借款信息介绍" maxlength="500" ><?php echo $borrowing['payment']; ?></textarea>
                <input name='payment' type='hidden' value='payment' />
            </div>
			<fieldset class="layui-elem-field layui-field-title" style="width: 919px;">
                <legend>还款保障(必填)</legend>
            </fieldset>
            <div class="layui-form-item" style="width: 919px;">
                <textarea class="layui-textarea" id="LAY_demo4" placeholder="必填,借款信息介绍" maxlength="500" ><?php echo $borrowing['guarantee']; ?></textarea>
                <input name='guarantee' type='hidden' value='guarantee' />
            </div>
            <!--底部工具栏-->
            <div class="page-footer">
                <div class="btn-list">
                    <div class="btnlist">
						<?php if(is_rule('/borrowing/modify')) { ?>
							<a class="layui-btn layui-btn-small" lay-submit="" lay-filter="btnsubmita" data-url="/borrowing/modify/1.html"><i class="layui-icon">&#x1005;</i>保存</a>
							<a class="layui-btn layui-btn-small" lay-submit="" lay-filter="btnsubmitb" data-url="/borrowing/modify/2.html"><i class="layui-icon">&#x1005;</i>提交</a>
						<?php } ?>
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
		var thui = thui || {};
		
        layui.use(['layedit','layer', 'form', 'upload'], function(){
            var layedit = layui.layedit
                ,$ = layui.$
                , layer = layui.layer
                , form = layui.form
				, upload = layui.upload;
			var index = layedit.build('LAY_demo2', {
				tool: [
				  'strong' //加粗
				  ,'italic' //斜体
				  ,'underline' //下划线
				  ,'del' //删除线
				  ,'|' //分割线
				  ,'left' //左对齐
				  ,'center' //居中对齐
				  ,'right' //右对齐
				  ,'link' //超链接
				  ,'unlink' //清除链接
				],
                height: 100
            });
			var index1 = layedit.build('LAY_demo3', {
				tool: [
				  'strong' //加粗
				  ,'italic' //斜体
				  ,'underline' //下划线
				  ,'del' //删除线
				  ,'|' //分割线
				  ,'left' //左对齐
				  ,'center' //居中对齐
				  ,'right' //右对齐
				  ,'link' //超链接
				  ,'unlink' //清除链接
				],
                height: 100
            });
			var index2 = layedit.build('LAY_demo4', {
				tool: [
				  'strong' //加粗
				  ,'italic' //斜体
				  ,'underline' //下划线
				  ,'del' //删除线
				  ,'|' //分割线
				  ,'left' //左对齐
				  ,'center' //居中对齐
				  ,'right' //右对齐
				  ,'link' //超链接
				  ,'unlink' //清除链接
				],
                height: 100
            });
			upload.render({
				elem: '#uppic'
				,url: '/login/do_uploads'
				,accept: 'images' //普通文件
				,multiple: true
				,field : 'userfile[]'
				,before: function(input){
				    console.log('文件上传中');
			    }
				,done: function(res){
				    layer.msg('上传完毕');
					var img = $('input[name=pic]').val();
					$.each(res, function(key, val){
						if(res[key].code == 200){
							if(img){
								$('input[name=pic]').val(img + ',' + res[key].savepath);
							}else{
								$('input[name=pic]').val(res[key].savepath);
							}
							var obj = "<div class='layui-inline'><img width='200' src='"+res[key].savepath+"' /></div>";
							$('.pic').before(obj);
							console.log(img);
						}
					});
				}
				,error : function(e){
					console.log(e);
				}
			});
			thui.entry = thui.entry || {};
			thui.entry.submit = function(url, formdata) {
                if(formdata.field.borrow_info == 'info'){
                    formdata.field.borrow_info = layedit.getContent(index);
                }
				if(formdata.field.payment == 'payment'){
                    formdata.field.payment = layedit.getContent(index1);
                }
				if(formdata.field.guarantee == 'guarantee'){
                    formdata.field.guarantee = layedit.getContent(index2);
                }
                $.post(url,formdata.field,function(s){
					layer.msg(s.message, {time:1500}, function() {
						if(s.state == 1){
							self.location.href = s.url;
						}
					});
                });
                return false;
			}
            form.on('submit(btnsubmita)', function (formdata) {
				var url = $(this).data('url');
				console.log(url);
                thui.entry.submit(url, formdata);
            });
			form.on('submit(btnsubmitb)', function (formdata) {
				var url = $(this).data('url');
				console.log(url);
                thui.entry.submit(url, formdata);
            });
            $('#borrow_name').on('click',function(){
                layer.open({
                    type: 2,
                    title: '获取收款人信息',
                    //shadeClose: true,
                    shade: 0.5,
                    maxmin: false,
                    area: ['700px', '520px'],
                    fixed: false,
                    content: '/borrowing/getusers.html'
                });
            });
			$('#js-guarantor').on('click', function() {
				layer.open({
                    type: 2,
                    title: '担保人信息',
                    shade: 0.5,
                    maxmin: false,
                    area: ['700px', '520px'],
                    fixed: false,
                    content: '/borrowing/get_guarantor.html'
                });
			}); 
			$('#js-guarantore').on('click', 'i', function(){
				$(this).parent().parent().remove();
			});
			form.on("select(l)", function(data){
				var sid = data.value;
				if(sid == 1){
					$('select[name=borrow_duration]').parent().find('input').val('3');
					$('select[name=borrow_duration]').parent().find('dd').attr('class','');
					$('select[name=borrow_duration]').parent().find('dd:eq(0)').attr('class','layui-this');
					$('.ds').hide();
				}else{
					$('.ds').show();
				}
			});
			$('body').on('mouseenter', '*[lay-tips]', function(){
				var othis = $(this)
				,tips = othis.attr('lay-tips')
				,offset = othis.attr('lay-offset') 
				,index = layer.tips(tips, this, {
					tips: 2
					,time: -1
					,success: function(layero, index){
						if(offset){
							layero.css('margin-left', offset + 'px');
						}
					}
				});
				othis.data('index', index);
			}).on('mouseleave', '*[lay-tips]', function(){
				layer.close($(this).data('index'));
			});
        });
</script>
</body>
</html>