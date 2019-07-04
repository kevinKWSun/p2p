<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<title><?php echo $name; ?>-伽满优</title>
		<meta name="Keywords" content="<?php echo $name; ?>" />
		<meta name="Description" content="<?php echo $name; ?>" />
		<link href="/favicon.ico" rel="SHORTCUT ICON" />
		<link href="/images/new_help/images/default.css" rel="stylesheet" type="text/css" />
		<link href="/images/new_help/images/index.css" rel="stylesheet" type="text/css" />
		<link href="/images/new_help/images/info.css" rel="stylesheet" type="text/css" />
		<link href="/src/css/layui.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript" src="/images/jquery-1.7.2.min.js"></script>
		<script type="text/javascript" src="/images/globle.js"></script>
		<script type="text/javascript" src="/images/index.js"></script>
		<script src="/images/new/js/swiper.min.js"></script>
		<script src="/images/new/js/jquery.SuperSlide.2.1.js"></script>
		<style>
			#layui-tab-brief ul {
				width:850px;
			}
			.layui-tab-content ul li {
				font-size:14px;
				float:left;width:33%;
				line-height:45px;
			}
			.layui-tab-content ul li a {
				color:#fc501b;
			}
			.layui-tab-content ul li a:hover {
				color:#fc801b;
			}
			.foot_nav h3 img.icon1{margin-top:0px;}
		</style>
	</head>
	<body>

		<?php echo $top; ?> 
		<div class="center_v1">
			<div class="bzzx_v1">
				<div class="bzzx_l_v1">
					<img id="ctl00_MainPlaceHolder_ctl00_MainLeft1_Image1" 
					src="/images/new_help/img/<?php echo $title_img; ?>"
					style="border-width:0px;" />
					<ul>
						<?php foreach($menu as $k=>$v) { ?>
							<li class="<?php if($v['url'] == $url) { echo 'on'; } ?>">
								<a class="<?php if($v['url'] == $url) { echo 'on'; } ?>" href="<?php echo $v['url']; ?>"> 
									<?php echo $v['name']; ?>
								</a>
							</li>
						<?php } ?>
					</ul>
					<?php if($title_img == 'gyjmy.png') { ?>
						<img id="ctl00_MainPlaceHolder_ctl00_MainLeft1_Image1" src="/images/new_help/img/appxiazai.png" style="border-width:0px;" />
						<ul>
							<li class="">
								<a class="" href="/message/dowload.html">APP下载</a>
							</li>
						</ul>
					<?php } ?>
					

				</div>
				<div class="bzzx_r_v1">
					<h2>
						<?php echo $name; ?> <span>&nbsp;</span>
					</h2>
					<p></p>
					<div class="xxpl_v1">
						<div class="run_reports_v1">
							<i>为响应监管要求，平台每月的信息披露内容均经法人确认，并签署确认函，供广大用户查阅。</i>
							<div id="operation_list" style="margin-top:20px;">
								<div class="acticle" >
									<h1 class="title" style="padding:0px;">
										<?php echo $year . '年' . $month . '月报告'; ?>
										<span style="float: right;padding-right: 50px;font-size: 12px;color: #999;">查看详情</span>
									</h1>
									
									<div class="content" style="display: none;">
										<style>
											/* 运营数据 */
											.yy_data {
												width: 100%;
												margin-bottom: 35px;
												
											}
											.yy_data tr th {
												text-align:center;
											}
											.yy_data tbody tr {
												text-align:center;
											}
											.yy_data > tbody > tr:nth-child(odd) {
												
											}
											.yy_data > tbody > tr:nth-child(odd) > td {
												color: #a3a3a3;
												font-size: 16px;
												
											}
											.yy_data > tbody > tr:nth-child(even){
											}
											.yy_data > tbody > tr:nth-child(even) > td{
												padding-top: 25px;
												padding-bottom: 5px;
												font-size: 18px;
												
											}
										</style>
										<?php if(!empty($motion)) { ?>
											<table class="yy_data">
												<tr>
													<th colspan="3" style="font-size: 16px;">借贷交易数据</th>
												</tr>
												<tr>
													<td style="vertical-align: text-bottom;"><?php echo $motion['bamount']; ?></td>
													<td><?php echo $motion['back']; ?></td>
													<td><?php echo $motion['total']; ?></td>
												</tr>
												<tr>
													<td style="vertical-align: text-top;">历史累计交易金额（元）</td>
													<td>累计回款金额（元）</td>
													<td>年化投资总金额（元）</td>
												</tr>
												<tr>
													<td><?php echo $motion['income']; ?></td>
													<td><?php echo $motion['reg']; ?></td>
													<td><?php echo $motion['trade_times']; ?></td>
												</tr>
												<tr>
													<td>用户累计获得收益（元）</td>
													<td>平台累计注册人数（人）</td>
													<td>历史累计交易次数（次）</td>
												</tr>
												<tr>
													<td><?php echo $motion['balance']; ?></td>
													<td><?php echo $motion['lenders']; ?></td>
													<td><?php echo $motion['ratio']; ?></td>
												</tr>
												<tr>
													<td>借款余额（元）</td>
													<td>累计出借人数量（人）</td>
													<td>前十大借款人待还占比（%）</td>
												</tr>
												<tr>
													<td><?php echo $motion['trade_total']; ?></td>
													<td><?php echo $motion['lead_nums']; ?></td>
													<td><?php echo $motion['borrowers_toal']; ?></td>
												</tr>
												<tr>
													<td>历史累计交易笔数（笔）</td>
													<td>借贷笔数（笔）</td>
													<td>累计借款人数量（人）</td>
												</tr>
												<tr>
													<td><?php echo $motion['current_leader']; ?></td>
													<td><?php echo $motion['current_borrow']; ?></td>
													<td><?php echo $motion['persent_borrow']; ?></td>
												</tr>
												<tr>
													<td>当期出借人数量（人）</td>
													<td>当期借款人数量（人）</td>
													<td>最高借款人待还金额占比（%）</td>
												</tr>
												<tr>
													<td> <?php echo $motion['loan_balance']; ?></td>
													<td><?php echo $motion['overdue']; ?></td>
													<td><?php echo $motion['compensation']; ?></td>
												</tr>
												<tr>
													<td>关联关系借款余额/笔数（元/笔）</td>
													<td>逾期金额/笔数（元/笔）</td>
													<td>累计代偿金额/笔数（元/笔）</td>
												</tr>
											</table>
										<?php } else { echo '整理中...'; } ?>
									</div>
								</div>	
							</div>
							<div id="layui-tab-brief" class="layui-tab">
							  <ul id="layui-tab-title" class="layui-tab-title">
								<?php foreach($news as $k=>$v) { ?>
									<li class="<?php if($year == $k) { echo 'layui-this'; } ?>"><?php echo $k; ?></li>
								<?php } ?>
								<!--<li class="layui-this">2018</li>
								<li>2017</li>-->
							  </ul>
							  <div class="layui-tab-content">
									<?php foreach($news as $k=>$v) { ?>
										<div class="layui-tab-item <?php if($year == $k) { echo 'layui-show'; } ?> yytb_con_v1">
											<ul>
												<?php foreach($v as $key=>$value) { ?>
													<li>
														<a href="/message/operation_detail/<?php echo $value['id']; ?>.html" target="_blank"><?php echo $value['title']; ?></a>
													</li>
												<?php } ?>
											</ul>
											<div class="clear"></div>
											<!--<dl>
												<dt><img src="/images/new_help/img/58pic_5249a3d744631.jpg"/></dt>
												<dd><a href="#" >2018年10月运营报告</a></dd>
											</dl>-->
										</div>
									<?php } ?>
							  </div>
							</div>							
						</div>
						
					</div>
				</div>
				<div class="clear"></div>
			</div>
		</div>


		<?php echo $foot; ?>

	</body>
	<script src="/src/layui.js" charset="utf-8"></script>
	<script>
	//注意：选项卡 依赖 element 模块，否则无法进行功能性操作
	layui.use('element', function(){
		var element = layui.element;
		var $ = layui.$;
		$("#operation_list .title").click(function(){
			if(!$(this).hasClass("open")){
				$("#operation_list .title").removeClass("open");
				$("#operation_list .title").next().hide();
				$(this).next().show();
				$(this).addClass("open");
				//$('html, body').animate({scrollTop:$(this).offset().top}, '100');
			}else{
				$(this).next().hide();
				$(this).removeClass("open");
			}
		});
	});
	</script>
</html>
