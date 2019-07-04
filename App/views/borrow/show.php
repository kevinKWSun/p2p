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
    <title>借款详情</title>
    <link href="/src/css/layui.css" rel="stylesheet" />
    <script src="/src/layui.js"></script>
	<style>
	.layui-tables[lay-skin="line"] td, .layui-tables[lay-skin="line"] th {text-align: center;
    border-bottom: 1px solid #e2e2e2;border-left: 1px solid #e2e2e2;}
	</style>
</head>
<body>
    <div class="main-wrap" style='padding:10px;'>
        <blockquote class="layui-elem-quote fhui-admin-main_hd">
            <h2>借款详情</h2>
        </blockquote>
        <div class="y-role">
            <!--文字列表-->
            <div class="fhui-admin-table-container">
                <form class="form-horizontal" id="formrec" method="post" role="form">
                    <table class="layui-table layui-tables" lay-skin="line">
						<thead>
                            <tr>
                                <th colspan="2">项目情况</th>
                            </tr>
                        </thead>
                        <tbody>
                        	<tr>
								<td width='15%'><?php echo unserialize($one['info'])['type']==1?'个人':'企业'?>名称</td>
								<td><?php echo $one['name']?></td>
							</tr>
							<tr>
								<td>借款人名称</td>
								<td><?php echo unserialize($one['info'])['car']?></td>
							</tr>
							<tr>
								<td>项目类型</td>
								<td><?php echo unserialize($one['info'])['jktpe']?></td>
							</tr>
							<thead>
								<tr>
									<th colspan="2">借款信息</th>
								</tr>
							</thead>
							<tr>
								<td>借款金额</td>
								<td><?php echo $one['money']?>万</td>
							</tr>
							<tr>
								<td>借款期限</td>
								<td><?php echo unserialize($one['info'])['day']?>天</td>
							</tr>
							<tr>
								<td>综合利率</td>
								<td><?php echo unserialize($one['info'])['lx']?></td>
							</tr>
							<thead>
								<tr>
									<th colspan="2">项目要点</th>
								</tr>
							</thead>
							<tr>
								<td>借款用途</td>
								<td><?php echo unserialize($one['info'])['yt']?></td>
							</tr>
							<?php foreach(explode(',',unserialize($one['info'])['member']) as $k=>$v):?>
							<tr>
								<td>担保人(<?php echo $k+1;?>)</td>
								<td><?php echo explode(' ',unserialize($one['info'])['member'])[0]?></td>
							</tr>
							<tr>
								<td>担保质押物</td>
								<td><?php echo explode(' ',unserialize($one['info'])['member'])[1]?></td>
							</tr>
							<tr>
								<td>质押物价格(元)</td>
								<td><?php echo explode(' ',unserialize($one['info'])['member'])[2]?></td>
							</tr>
							<?php endforeach;?>
                        </tbody>
                    </table>
                </form>
            </div>
			<div class="page-footer">
                <div class="btn-list">
                    <div class="btnlist">
                        <a class="layui-btn layui-btn-sm do-action" data-type="doRefresh" data-url=""><i class="layui-icon">&#xe669;</i>刷新</a>
                        <a class="layui-btn layui-btn-sm do-action" data-type="doGoBack"><i class="layui-icon">&#xe65c;</i>返回上一页</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="/src/global.js"></script>
</body>
</html>