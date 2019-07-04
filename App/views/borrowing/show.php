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
    <title>标的信息</title>
    <link href="/src/css/layui.css" rel="stylesheet" />
    <script src="/src/layui.js"></script>
	
	<style>
		.ds,span.s{display:none;}
		.layui-input {
			padding : 8px 0px 8px 15px;
		}
	</style>
</head>
<body>
    <div class="main-wrap" style='padding:10px;'>
        <blockquote class="layui-elem-quote fhui-admin-main_hd">
            <h2>标的信息</h2>
        </blockquote>
        <form action="/borrowing/add" class="layui-form layui-form-pane" id="formrec" method="post" role="form">
			<input type="hidden" name="id" value="<?php echo $borrowing['id']; ?>" />
			<input type="hidden" name="current_page" value="<?php echo $current_page; ?>" />
            <div class="layui-form-item">
                <label class="layui-form-label">收款人</label>
				<div class="layui-input-inline layui-input">
					<?php echo get_member_info($borrowing['borrow_uid'])['real_name']; ?>
				</div>
				<label class="layui-form-label">收款类型</label>
				<div class="layui-input-inline layui-input">
					<?php foreach($this->config->item('borrows_type') as $k => $v):?>
						<?php if($borrowing['type'] == $k) { echo $v; } ?>
					<?php endforeach;?>
				</div>
				<label class="layui-form-label">标名称</label>
				<div class="layui-input-inline layui-input">
					<?php echo $borrowing['borrow_name']; ?>
				</div>
            </div>
            <div class="layui-form-item">
				<label class="layui-form-label">金额(元)</label>
				<div class="layui-input-inline layui-input">
					<?php echo intval($borrowing['borrow_money']); ?>
				</div>
				<label class="layui-form-label">类型</label>
				<div class="layui-input-inline layui-input">
					<?php foreach($this->config->item('borrow_type') as $k => $v):?>
						<?php if($borrowing['borrow_type'] == $k) { echo $v; } ?>
					<?php endforeach;?>
				</div>
				<label class="layui-form-label">期限(天)</label>
				<div class="layui-input-inline layui-input">
					<?php foreach($this->config->item('borrow_duration') as $k => $v):?>
						<?php if($borrowing['borrow_duration'] == $k) { echo $v; } ?>
					<?php endforeach;?>
				</div>
            </div>
            <div class="layui-form-item">
				<label class="layui-form-label">起投(元)</label>
				<div class="layui-input-inline layui-input">
					<?php echo intval($borrowing['borrow_min']); ?>
				</div>
				<label class="layui-form-label">终投(元)</label>
				<div class="layui-input-inline layui-input">
					<?php echo intval($borrowing['borrow_max']); ?>
				</div>
				<label class="layui-form-label">还款方式</label>
				<div class="layui-input-inline layui-input">
					<?php foreach($this->config->item('repayment_type') as $k => $v):?>
						<?php if($borrowing['repayment_type'] == $k) { echo $v; } ?>
					<?php endforeach;?>
				</div>
            </div>
            <div class="layui-form-item">
				
				<label class="layui-form-label">利率(%)</label>
				<div class="layui-input-inline layui-input">
					<?php echo $borrowing['borrow_interest_rate']; ?>
				</div>
				<label class="layui-form-label">服务费(%)</label>
				<div class="layui-input-inline layui-input">
					<?php echo $borrowing['service_money']; ?>
				</div>
				<label class="layui-form-label">图片</label>
				<div class="layui-input-inline layui-input">
					
				</div>
            </div>
			<div class="layui-form-item">
				<label class="layui-form-label">加息(%)</label>
				<div class="layui-input-inline layui-input">
					<?php echo $borrowing['add_rate']; ?>
				</div>
				<label class="layui-form-label">推荐</label>
				<div class="layui-input-inline layui-input">
					<?php 
						if($borrowing['is_tuijian'] == 1) { 
							echo '推荐'; 
						} else if($borrowing['is_tuijian'] == 2) {
							echo '热门'; 
						} else {
							echo '正常';
						}
					?>
				</div>
				<label class="layui-form-label">密码</label>
				<div class="layui-input-inline layui-input">
					<?php echo $borrowing['password']; ?>
				</div>
            </div>
			<div class="layui-form-item">
				<label class="layui-form-label">募集时间</label>
				<div class="layui-input-inline layui-input">
					<?php echo $borrowing['number_time']; ?>
				</div>
				<label class="layui-form-label">借款用途</label>
				<div class="layui-input-inline layui-input">
					<?php echo $borrowing['borrow_use']; ?>
				</div>
				<label class="layui-form-label">选择担保人</label>
				<div class="layui-input-inline layui-input">
					
				</div>
            </div>
			<div class="layui-form-item">
				<label class="layui-form-label">标的编号</label>
				<div class="layui-input-inline layui-input">
					<?php echo $borrowing['borrow_no']; ?>
				</div>
            </div>
			<fieldset class="layui-elem-field layui-field-title" style="width: 919px;">
                <legend>担保人</legend>
            </fieldset>
			<table class="layui-table layui-tables" lay-skin="line" style="width: 919px;">
				 <thead>
					<tr>
						<th>ID</th>
						<th>姓名</th>
						<th>手机号</th>
						<th>身份证号</th>
						<th>操作</th>
					</tr>
				</thead>
				<tbody>
					<?php if(!empty($borrowing['guarantor'])) { ?>
						<?php foreach($borrowing['guarantor'] as $k=>$v) { ?>
							<tr>
								<td><?php echo $v['id']; ?></td>
								<td><?php echo $v['name']; ?></td>
								<td><?php echo $v['phone']; ?></td>
								<td><?php echo $v['idcard']; ?></td>
								<td><a class="js-show" style="cursor:pointer;" data-id="<?php echo $v['id']; ?>">查看图片</a></td>
							</tr>
							
						<?php } ?>
					<?php } ?>
				</tbody>
			</table>
			<fieldset class="layui-elem-field layui-field-title" style="width: 919px;">
                <legend>车辆图片</legend>
            </fieldset>
			<div class="layui-form-item layer-photos-demo">
				<?php if($borrowing['pic']) { foreach(explode(',', $borrowing['pic']) as $v) { ?>
				<div class="layui-inline slts"><img height='100' src="<?php echo $v ?>" /></div>
				<?php } } ?>
			</div>
            <fieldset class="layui-elem-field layui-field-title" style="width: 919px;">
                <legend>借款信息介绍(必填)</legend>
            </fieldset>
            <div class="layui-form-item" style="width: 919px;">
                <textarea class="layui-textarea" disabled><?php echo strip_tags($borrowing['borrow_info']); ?></textarea>
                <input  name='borrow_info' type='hidden' value='info' />
            </div>
			<fieldset class="layui-elem-field layui-field-title" style="width: 919px;">
                <legend>还款来源(必填)</legend>
            </fieldset>
            <div class="layui-form-item" style="width: 919px;">
                <textarea class="layui-textarea" maxlength="500" disabled><?php echo strip_tags($borrowing['payment']); ?></textarea>
                <input name='payment' type='hidden' value='payment' />
            </div>
			<fieldset class="layui-elem-field layui-field-title" style="width: 919px;">
                <legend>还款保障(必填)</legend>
            </fieldset>
            <div class="layui-form-item" style="width: 919px;">
                <textarea class="layui-textarea" maxlength="500" disabled><?php echo strip_tags($borrowing['guarantee']); ?></textarea>
                <input name='guarantee' type='hidden' value='guarantee' />
            </div>
            <!--底部工具栏-->
            <div class="page-footer">
                <div class="btn-list">
                    <div class="btnlist">
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
    <script type="text/javascript">
		layui.config({
		  base: '/src/lay/modules/'
		}).extend({
		  lazyload: 'lazyload'
		});
        layui.use(['layer', 'lazyload'], function(){
			var $ = layui.$
			, layer = layui.layer;
			//, lazyload = layui.lazyload;
			
			//$("img").lazyload();
			//点击查看的时候，加载图片
			// $('.js-show-img').click(function() {
				// $(this).parents('tr').next().show();
				// $(this).parents('tr').next().find('img').lazyload({
					// failure_limit : 20
				// });
			// });
			
			$('.js-show').click(function() {
				var id = $(this).attr('data-id');
				var href = '/borrowing/showimg/'+id+'.html';
				layer.open({
					type: 2,
					title: '担保人详细信息',
					shade: 0.1,
					maxmin: true,
					area: ['45%', '90%'],
					fixed: true,
					content: href
				});
			});
			
			layer.photos({
				photos: '.layer-photos-demo'
				,anim: 5
			}); 
        });
	</script>
</body>
</html>