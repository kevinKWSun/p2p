<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>借款列表-伽满优</title>
    <meta http-equiv="Content-Language" content="zh-cn" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <meta name="viewport" content="width=device-width, maximum-scale=1.0, initial-scale=1.0,initial-scale=1.0,user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="Keywords" content="借款列表-伽满优,车贷理财,车辆抵押,P2P投资理财,投资理财公司,短期理财,P2P投资理财平台" />
		<meta name="Description" content="借款列表-伽满优,通过公开透明的规范操作,平台为投资理财人士提供收益合理、安全可靠、高效灵活的车贷理财产品。" />
    <link href="/images/default.css" rel="stylesheet" type="text/css" />
		<link href="/images/index.css" rel="stylesheet" type="text/css" />
		<link href="/images/new/user-info.css" rel="stylesheet" type="text/css" />
    <link href="/src/css/layui.css" rel="stylesheet" />
    <script src="/src/layui.js"></script>
		<script src="/images/jquery-1.7.2.min.js"></script>
</head>
<body>
<?php include("top.php") ?>
 <div class="cent_v2">
	<div class="zhzx_v2">
		<div class="zhzx_l_v2">
			<?php include("left_v1.php") ?>
		</div>
		<div class="zhzx_r_v2">
			<h2>借款列表</h2>
			<div class="y-role">
				<!--工具栏-->		
				
				<div id="floatHead" class="toolbar-wrap">
				<div class="toolbar borrow_input">
					<div class="box-wrap">
						<div class="l-list clearfix">
							<form id="tt" class="layui-form layui-form-pane">
								<div class="layui-form-item">
									<div class="layui-inline">						
										<div class="layui-input-block" style="margin-left: 0px">		
											<input name="skey" value="<?php if(isset($skey)) { echo $skey; } ?>" autocomplete="off" placeholder="ID/标名/编号" class="layui-input" type="text" />
										</div>
									</div>

									<div class="layui-inline">	
										<div class="layui-input-block" style="margin-left: 0px">
											<select name="status">
											<option value="0">--选择状态--</option>
												<option value="2" <?php if(isset($status) && $status == 2) { echo 'selected'; } ?>>募集中</option>
												<option value="3"  <?php if(isset($status) && $status == 3) { echo 'selected'; } ?>>满标</option>
												<option value="4"  <?php if(isset($status) && $status == 4) { echo 'selected'; } ?>>放款</option>
												<option value="5"  <?php if(isset($status) && $status == 5) { echo 'selected'; } ?>>正常还款</option>
											</select>
										</div>
									</div>
									<div class="layui-inline">
											<a class="layui-btn  layui-btn-sm" lay-submit="" lay-filter="js-search" data-url="/apply/lists.html">
												<i class="fa fa-search"></i>查询
											</a>
<!-- 											<a class="layui-btn layui-btn-sm" lay-submit="" lay-filter="js-export" data-url="/apply/export.html">
												<i class="fa fa-search"></i>导出
											</a> -->
											<a class="layui-btn layui-btn-sm do-action" data-type="doRefresh" data-url="/apply/lists.html">
												<i class="layui-icon">&#xe669;</i>重新载入
											</a>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
				</div>
				<!--/工具栏-->
				<!--文字列表-->
				<div class="borrow_table">
					<form class="form-horizontal" id="formrec" method="post" role="form">
						<table class="layui-table layui-tables"  lay-size="sm" style="text-align:center;">
							<colgroup>
								<col width="50">
								<col width="100">
								<col width="50">
								<col width="100">
								<col width="45">
								<col width="40">
								<col width="70">
								<col width="80">
								<col width="80">
								<col width="80">
								<col width="100">
								<col width="60">
								<col width="130">
							</colgroup>
							<thead>
								<tr>
									<th>ID</th>
									<th>标名</th>
									<th>编号</th>
									<th>借款人</th>
									<th>金额/元</th>
									<th>期限</th>
									<th>申请日期</th>
									<th>上标日期</th>
									<th>放款日期</th>
									<th>到期日期</th>
									<th>还款方式</th>
									<th>状态</th>
									<th>操作</th>
								</tr>
							</thead>
						<tbody>
							<?php if(! $borrow){ ?>
								<tr>
									<td class="nodata" colspan="13" align='center'>暂无数据！</td>
								</tr>
							<?php }else{foreach ($borrow as $v): ?>
							<tr>
								<td><?php echo $v['id']; ?></td>
								<td><?php echo $v['borrow_name']; ?></td>
								<td><?php echo $v['borrow_no']; ?></td>
								<td><?php echo get_member_info($v['borrow_uid'])['real_name'];?></td>
								<td><?php echo $v['borrow_money'] / 10000; ?>万</td>
								<td><?php echo $this->config->item('borrow_duration')[$v['borrow_duration']]; ?>天</td>
								<td><?php echo date('Y-m-d',$v['add_time']); ?></td>
								<td><?php if($v['send_time'] > 0) { echo date('Y-m-d',$v['send_time']); } else { echo '-'; } ?></td>
								<td><?php if($v['endtime'] > 0) { echo date('Y-m-d',$v['endtime']); } else { echo '-'; } ?></td>
								<td><?php echo $v['endtime']?date('Y-m-d',$v['endtime']+$this->config->item('borrow_duration')[$v['borrow_duration']]*86400):'无'; ?></td>
								<td><?php echo $this->config->item('repayment_type')[$v['repayment_type']]; ?></td>
								<td><?php echo $this->config->item('borrow_status')[$v['borrow_status']]; ?></td>
								<td>
									<?php if($v['borrow_status'] == 3){ ?>
									<a class="layui-btn layui-btn-sm" lay-submit="" lay-filter="putmoney" data-href="/borrow/putmoney/<?php echo $v['id']; ?>.html"><i class="icon-edit fa fa-search"></i>放　款</a>										
									<?php }elseif($v['borrow_status'] == 2){?>
									<a class="layui-btn layui-btn-sm" lay-submit="" lay-filter="putmoney" data-href="/borrow/putmoney/<?php echo $v['id']; ?>.html"><i class="icon-edit fa fa-search"></i>提 前 放 款</a>
									<a class="layui-btn layui-btn-sm" lay-submit="" lay-filter="revoke" data-href="/borrow/revoke/<?php echo $v['id']; ?>.html"><i class="icon-edit fa fa-trash-o"></i>流　标</a>
									<?php } ?>
									<a class="layui-btn layui-btn-sm" href='/invest/show/<?php echo $v['id']; ?>.html' target='_blank'>
									<i class="icon-edit fa fa-search"></i>查　看</a>
								</td>
							</tr>		
								
							<?php endforeach;} ?>
							</tbody>
						</table>
					</form>
				</div>
				<!--/文字列表-->
				<div class="layui-box layui-laypage layui-laypage-default">
				<?php echo $page; ?>
				<a href="javascript:;" class="layui-laypage-next" data-page="2">共 <?php echo $totals; ?> 条</a>
			</div>
		</div>
			
		</div>
	</div>
</div>

<?php include("foot.php") ?>
<script src="/src/global.js"></script>
	<script type="text/javascript">
		layui.use(['layer', 'form'], function(){
			var $ = layui.$
			, form = layui.form
			, layer = layui.layer
			
			form.on('submit(js-search)', function(data) {
				var url = $(this).data('url');
				var str = JSON.stringify(data.field);
				str = str.replace(/\t/g,"");
				str = str.replace(/\"/g,"").replace("{","").replace("}","").replace(",","&").replace(":","=");
				str = str.replace(/\"/g,"").replace(/{/g,"").replace(/}/g,"").replace(/,/g,"&").replace(/:/g,"=");
				location.href = url + "?" + str;
				return false; 
			});
			//导出
			form.on('submit(js-export)', function(data) {
				var url = $(this).data('url');
				var str = JSON.stringify(data.field);
				str = str.replace(/\t/g,"");
				str = str.replace(/\"/g,"").replace("{","").replace("}","").replace(",","&").replace(":","=");
				str = str.replace(/\"/g,"").replace(/{/g,"").replace(/}/g,"").replace(/,/g,"&").replace(/:/g,"=");
				location.href = url + "?" + str;
				return false; 
			});
			form.on('submit(revoke)', function(data) {
				var url = $(this).data('href');
				if (url) {
					layer.confirm('你确定要流标吗？', {icon: 6, title:'信息提示'}
					, function(index){
						layer.load(1);
						$.ajax({
							url: url,
							type: 'post',
							dataType: 'json',
							data: {},
							success: function (r, startic) {
								layer.msg(r.message, {time:1500}, function() {
									if(r.state == 1){
										 self.location.reload();
									}else{
										layer.closeAll();
									}
								});
							},
							beforeSend: function () {
							   $(data.elem).attr("disabled", "true").text("流标中...");
							},
							complete: function () {
							   $(data.elem).removeAttr("disabled").html('<i class="icon-edit fa fa-trash-o"></i>流　标');
							},
							error: function (XMLHttpRequest, textStatus, errorThrown) {
								layer.msg(textStatus);
							}
						});
						layer.closeAll();
					}, function(index) {
						layer.closeAll();
					});
				} else {
					layer.msg('链接错误！');
				}
			});
			
			form.on('submit(putmoney)', function(data) {
				var url = $(this).data('href');
				if (url) {
					layer.confirm('你确定要放款吗？', {icon: 6, title:'信息提示'}
					, function(index){
						// $.post(url, {}, function(r) {
							// layer.msg(r.message, {time:1500}, function() {
								// if(r.state == 1){
									// location.href = r.url;
								// }else{
									// layer.closeAll();
								// }
							// });
						// }, 'json');
						layer.load(1);
						$.ajax({
							url: url,
							type: 'post',
							dataType: 'json',
							data: {},
							success: function (r, startic) {
								layer.msg(r.message, {time:1500}, function() {
									if(r.state == 1){
										location.href = r.url;
									}else{
										layer.closeAll();
									}
								});
							},
							beforeSend: function () {
							   $(data.elem).attr("disabled", "true").text("放款中...");
							},
							complete: function () {
							   $(data.elem).removeAttr("disabled").html('<i class="icon-edit"></i>放　款');
							},
							error: function (XMLHttpRequest, textStatus, errorThrown) {
								layer.msg(textStatus);
							}
						});
						layer.closeAll();
					}, function(index) {
						layer.closeAll();
					});
				} else {
					layer.msg('链接错误！');
				}
			});
			// form.on('submit(send)', function(data) {
				// var url = $(this).data('href');
				// var title = $(this).data('title');
				// if (url) {
					// layer.open({
						// 'type' : 2,
						// 'title' : '[' + title + ']发红包',
						// 'area' : ['50%', '80%'],
						// 'maxmin' : true,
						// 'shade' : 0.3,
						// 'content' : url
					// });
				// } else {
					// layer.msg('链接错误！');
				// }
			// });
		});
	</script>
</body>
</html>