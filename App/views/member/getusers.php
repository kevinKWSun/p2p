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
    <title>会员信息</title>
	<link href="/src/css/layui.css" rel="stylesheet" />
    <script src="/src/layui.js"></script>
</head>
<body>
    <div class="main-wrap" style='padding:10px;'>
        <div class="y-role">
			<!--工具栏-->
            <div id="floatHead" class="toolbar-wrap">
                <div class="toolbar">
                    <div class="box-wrap">
                        <div class="l-list clearfix">
                            <form id="tt" class="layui-form layui-form-pane">
                                <div class="layui-form-item">
                                    <div class="layui-inline">
                                        <div class="layui-input-block" style="margin-left: 0px">
                                            <input name="skey" value="<?php if(isset($skey)) { echo $skey; } ?>" autocomplete="off" placeholder="ID/真实姓名/手机号" class="layui-input" type="text" />
                                        </div>
                                    </div>
                                    <div class="layui-inline">
                                        <a class="layui-btn layui-btn-small" lay-submit="" lay-filter="search" data-url="/borrowing/getusers.html">
                                            <i class="fa fa-search"></i>查询
                                        </a>
										<a class="layui-btn layui-btn-small do-action" data-type="doRefresh" data-url="/borrowing/getusers.html">
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
            <div class="fhui-admin-table-container">
				<form class="form-horizontal" id="formrec" method="post">
                    <table class="layui-table layui-tables">
                        <thead>
                            <tr>
                                <th>选择项</th>
                                <th>姓名/公司</th>
                                <th>手机</th>
                                <th>证件/企业代码</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php if(! $member){ ?>
                            <tr>
                                <td class="nodata" colspan="7" align='center'>暂无数据！</td>
                            </tr>
                            <?php }else{foreach ($member as $v): ?>
                            <tr class='ck'>
                                <td>
                                    <input name="ck" value="<?php echo $v['id']; ?>" title="" type="radio" >
                                </td>
                                <td class='sed'><?php echo $v['real_name']; ?></td>
                                <td><?php echo $v['phone']; ?></td>
                                <td><?php echo $v['idcard']; ?></td>
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
        layui.use(['layer', 'form'], function () {
            var $ = layui.jquery, layer = layui.layer, form = layui.form;
            var index = parent.layer.getFrameIndex(window.name);
            $('tr.ck').on('click', function(){
                var sid = $(this).find('input').val();
                $(window.parent.document).find('#borrow_uid').val(sid);
                $(window.parent.document).find('#borrow_name').val($(this).find('.sed').text());
				//$(window.parent.document).find('#saddress').val($(this).parents().find('tr').find('.saddress').text()); 
				//$(window.parent.document).find('#age').val($(this).parents().find('tr').find('.age').text());
				//$(window.parent.document).find('#htime').val($(this).parents().find('tr').find('.htime').text());
                parent.layer.close(index);
            });
			form.on('submit(search)', function(data) {
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
