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
    <title>完成列表</title>
    <link href="/src/css/layui.css" rel="stylesheet" />
    <script src="/src/layui.js"></script>
</head>
<body>
    <div class="main-wrap" style='padding:10px;'>
        <blockquote class="layui-elem-quote fhui-admin-main_hd">
            <h2>完成列表</h2>
        </blockquote>
        <div class="y-role">
            <!--工具栏-->
            <div id="floatHead" class="toolbar-wrap">
                <div class="toolbar">
                    <div class="box-wrap">
                        <div class="l-list clearfix">
                            <form id="tt" class="layui-form layui-form-pane">
                                <div class="layui-form-item">
                                    <div class="layui-inline">
                                        <div class="layui-input-inline" style="margin-left: 0px">
                                            <input name="skey" value="<?php if(isset($skey)) { echo $skey; } ?>" autocomplete="off" placeholder="ID/标名/编号" class="layui-input" type="text" />
                                        </div>
										<div class="layui-input-inline" style="margin-left: 0px">
                                            <input name="name" value="<?php if(isset($name)) { echo $name; } ?>" autocomplete="off" placeholder="借款人ID/借款人名字" class="layui-input" type="text" />
                                        </div>
										<div class="layui-input-inline" style="margin-left: 0px">
                                            <input name="guarantor" value="<?php if(isset($guarantor)) { echo $guarantor; } ?>" autocomplete="off" placeholder="担保人ID" class="layui-input" type="text" />
                                        </div>
										<div class="layui-input-inline" style="margin-left: 0px">
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
                                        <a class="layui-btn layui-btn-small" lay-submit="" lay-filter="js-search" data-url="/borrow/complete.html">
                                            <i class="fa fa-search"></i>查询
                                        </a>
										<?php if(is_rule('/borrow/export')) { ?>
											<a class="layui-btn layui-btn-small" lay-submit="" lay-filter="js-export" data-url="/borrow/export.html">
												<i class="fa fa-search"></i>导出
											</a>
										<?php } ?>
                                        <a class="layui-btn layui-btn-small do-action" data-type="doRefresh" data-url="/borrow/complete.html">
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
            <div class="fhui-admin-table-container">
                <form class="form-horizontal" id="formrec" method="post" role="form">
                    <table class="layui-table layui-tables" lay-skin="line">
                        <thead>
                            <tr>
								<th>ID</th>
                                <th>标名</th>
								<th>编号</th>
                                <th>借款人</th>
                                <th>金额/万</th>
                                <th>期限</th>
                                <th>申请日期</th>
								<th>上标日期</th>
								<th>放款日期</th>
                                <th>到期日期</th>
								<th>实际还款日期</th>
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
                                <td><?php echo $v['borrow_type'] == 2 ? $v['borrow_name'].'<font color="red">[新]</font>' : $v['borrow_name']; ?></td>
								<td><?php echo $v['borrow_no']; ?></td>
                                <td><?php echo get_member_info($v['borrow_uid'])['real_name'];?></td>
                                <td><?php echo $v['borrow_money'] / 10000; ?>万</td>
                                <td><?php echo $this->config->item('borrow_duration')[$v['borrow_duration']]; ?>天</td>
                                <td><?php echo date('Y-m-d',$v['add_time']); ?></td>
								<td><?php if($v['send_time'] > 0) { echo date('Y-m-d',$v['send_time']); } else { echo '-'; } ?></td>
								<td><?php if($v['endtime'] > 0) { echo date('Y-m-d',$v['endtime']); } else { echo '-'; } ?></td>
                                <th><?php echo $v['endtime']?date('Y-m-d',$v['endtime']+$this->config->item('borrow_duration')[$v['borrow_duration']]*86400):'无'; ?></th>
								<th><?php echo $v['real_repayment_time'] > 0 ? date('Y-m-d', $v['real_repayment_time']) : '-'; ?></th>
                                <th><?php echo $this->config->item('repayment_type')[$v['repayment_type']]; ?></th>
                                <th><?php echo $this->config->item('borrow_status')[$v['borrow_status']]; ?></th>
                                <td>
									<?php if($v['borrow_status'] == 10){?>
										<a class="layui-btn layui-btn-sm"><i class="icon-edit fa fa-trash-o"></i>已流标</a>
									<?php } else { ?>
										<a class="layui-btn layui-btn-sm" href='/invest/show/<?php echo $v['id']; ?>.html' target='_blank'>
											<i class="icon-edit  fa fa-search"></i>
											查　看
										</a>
									<?php } ?>
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
				<?php if(isset($skip_page)) { echo $skip_page; } ?>
            </div>
        </div>
    </div>
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
		});
	</script>
</body>
</html>