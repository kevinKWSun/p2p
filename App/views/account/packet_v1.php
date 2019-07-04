<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>我的红包-伽满优</title>
    <meta http-equiv="Content-Language" content="zh-cn" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <meta name="viewport" content="width=device-width, maximum-scale=1.0, initial-scale=1.0,initial-scale=1.0,user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="Keywords" content="我的红包-伽满优,车贷理财,车辆抵押,P2P投资理财,投资理财公司,短期理财,P2P投资理财平台" />
		<meta name="Description" content="我的红包-伽满优,通过公开透明的规范操作,平台为投资理财人士提供收益合理、安全可靠、高效灵活的车贷理财产品。" />
		<link href="/images/default.css" rel="stylesheet" type="text/css" />
		<link href="/images/index.css" rel="stylesheet" type="text/css" />
		<link href="/images/new/user-info.css" rel="stylesheet" type="text/css" />
		<link href="/images/new/date.css" rel="stylesheet" type="text/css" />
		<link href="/src/css/layui.css" rel="stylesheet" />
		<script src="/images/jquery-1.7.2.min.js"></script>
		<script src="/src/layui.js"></script>
		<style>
			.layui-tab-title .layui-this:after{ border:none;}
		</style>
</head>
<body>
<?php include("top.php") ?>
 <div class="cent_v2">

	<div class="zhzx_v2">
		<div class="zhzx_l_v2">
			<?php include("left_v1.php") ?>
		</div>
		<?php $red_status = isset($_GET['status'])?$_GET['status']:0; ?>
		<div class="zhzx_r_v2">
			<h2>我的红包</h2>
			<div class="red_packet_v2">

				<div class="layui-tab-red-border">
				  <ul class="layui-tab-title" id="floatHead">
					<li class="layui-li-tab" data-url="/account/packet.html?status=0">未使用</li>
					<li class="layui-li-tab" data-url="/account/packet.html?status=1">已使用</li>
					<li class="layui-li-tab" data-url="/account/packet.html?status=2">已过期</li>
					<li class="layui-li-tab" data-url="/account/packet.html?status=3">使用说明</li>
				  </ul>
				  <div class="layui-tab-content">
					<?php  if($red_status == '0' || $red_status == '1' || $red_status == '2'): ?>
					<div class="red-packet-public">
						<ul>
							<?php foreach($packets as $v):?>
							<li>
								<?php if($red_status == '0') { ?>
									<a href="/invest.html">
								<?php } else if($red_status == '1' && $v['bid'] > 0) { ?>
									<a href="/invest/show/<?php echo $v['bid']; ?>.html">
								<?php } else { ?>
									<a href="javascript:;">
								<?php }?>
									<dl>
										<dt>
											<strong><var><?php echo floatval($v['money']);?></var>元红包</strong>
											<span>满<?php echo $v['min_money'];?>元可用</span>
											<span><?php echo $v['times'];?>天以上期间可用</span>
										</dt>
										<dd>有效期至</br><?php echo date('Y-m-d', $v['etime'])?></dd>
									</dl>
								</a>
							</li>						

							<?php endforeach;?>
						</ul>
					</div>
					<div style="clear:both;"></div>
					<div class="layui-box layui-laypage layui-laypage-default">
						<?php echo $page; ?>
						<a href="javascript:;" class="layui-laypage-next">共 <?php echo $totals; ?> 条</a>
					</div>	
					<?php else:?>
					<div class="Using-document">
							<ul>
								<li>1、投资红包适用所有投资项目，需用户选择操作，当项目放款后，系统自动激活所选红包。</li>
								<li>2、投资红包激活后，将自动变成可用余额，每笔投资可选择激活一个条件适合的投资红包。</li>
								<li>3、投资红包有效期：每个投资红包都有一个有效期，用户需在有效期内激活，过期无效。</li>
								<li>4、本次红包活动规则最终解释权归“伽满优”平台所有。</li>									
							</ul>
					</div>
					<?php endif;?>
				  </div>
				</div>				 

			</div>
		</div>
	</div>
</div>
<?php include("foot.php") ?>

<script type="text/javascript">
	layui.use(['layer', 'form'], function () {
		var $ = layui.$
		, layer = layui.layer
		, form = layui.form;
		
		var status = <?php echo $red_status;?>;
		switch(status){
			case 0:
			  $(".red-packet-public").addClass("no-used");
			  $("#floatHead li:first").addClass("layui-this");
			  break;
			case 1:
			  $(".red-packet-public").addClass("have-used");
			  $("#floatHead li").eq(1).addClass("layui-this");
			  break;
		    case 2:
			  $(".red-packet-public").addClass("stale-dated");
			  $("#floatHead li").eq(2).addClass("layui-this");
			  break;
			default:
			 $("#floatHead li").eq(3).addClass("layui-this");

		}

		$('#floatHead li').on('click',function(data){			
			location.href = $(this).attr('data-url');			
		});
	});
</script>
</body>
</html>