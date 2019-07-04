<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>数据整合</title>
    <meta http-equiv="Content-Language" content="zh-cn" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <meta name="viewport" content="width=device-width, maximum-scale=1.0, initial-scale=1.0,initial-scale=1.0,user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <link href="/src/css/layui.css" rel="stylesheet" />
    <script src="/src/layui.js"></script>
</head>
<body>
    <div class="main-wrap" style='padding:10px;'>
        <blockquote class="layui-elem-quote fhui-admin-main_hd">
            <button type="button" class="layui-btn" id="test10"><i class="layui-icon">
			</i>上传文件</button></h3>
        </blockquote>
        <form action="" class="layui-form layui-form-pane" id="formrec" method="post" role="form">
            <input name="xls" value="" type="hidden">
			<div class="fhui-admin-table-container">
                <form class="layui-form" method="post">
                    <table class="layui-table">
                        <thead>
                            <tr>
                                <th>渠道</th>
                                <th>用户</th>
                                <th>33</th>
                                <th>65</th>
                                <th>97</th>
                            </tr>
                        </thead>
                        <tbody>
                        	<?php if(! $l){ ?>
                            <tr>
                                <td class="nodata" colspan="5" align='center'>暂无数据！</td>
                            </tr>
                            <?php }else{foreach ($l as $k => $v): if($k=='闫帅'){ foreach($v as $vs): ?>
                            <tr>
                                <td><?php echo $k; ?></td>
								<td><?php echo $vs['name']; ?></td>
                                <td rel=<?php echo isset($vs[33])?$vs[33]:0; ?>>
								<?php echo isset($vs[33])?$vs[33]:0; ?>
								<input type='text' size='2' />% <input type='text' name='s' size='7' disabled />
								</td>
                                <td rel=<?php echo isset($vs[65])?$vs[65]:0; ?>>
                                	<?php echo isset($vs[65])?$vs[65]:0; ?>
									<input type='text' size='2' />% <input type='text' name='s' size='7' disabled />
                                </td>
                                <td rel=<?php echo isset($vs[97])?$vs[97]:0; ?>><?php echo isset($vs[97])?$vs[97]:0; ?>
									<input type='text' size='2' />% <input type='text' name='s' size='7' disabled />
								</td>
                            </tr>
							<?php endforeach;}else{ ?>
							<tr>
                                <td><?php echo $k; ?></td>
								<td><?php echo $k; ?></td>
                                <td rel=<?php echo isset($v[33])?$v[33]:0; ?>><?php echo isset($v[33])?$v[33]:0; ?>
								<input type='text' size='2' />% <input type='text' name='s' size='7' disabled /></td>
                                <td rel=<?php echo isset($v[65])?$v[65]:0; ?>>
                                	<?php echo isset($v[65])?$v[65]:0; ?>
									<input type='text' size='2' />% <input type='text' name='s' size='7' disabled />
                                </td>
                                <td rel=<?php echo isset($vs[97])?$vs[97]:0; ?>><?php echo isset($v[97])?$v[97]:0; ?>
								<input type='text' size='2' />% <input type='text' name='s' size='7' disabled /></td>
                            </tr>
                            <?php }endforeach;} ?>
                        </tbody>
                    </table>
                </form>
            </div>
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
				,url: '/login/do_file'
				,type: 'post'
				,accept: 'file'
				,exts: 'xlsx'
				,size: 20000
				,done: function(res){
					//console.log(res)
					if(res.code == 0){
						$('input[name=xls]').val(res.data.src);
						self.location.href = '/datasystem.html?url='+res.data.src;
					}else{
						layer.msg(res.errorMsg);
					}
				}
			});
			$('input[type=text]').bind("keyup", function() {
				var rel = $(this).parent();
				var total = rel.attr('rel') * 1;
				rel.find('input[name=s]').val(total * $(this).val() * 0.01);
			});
        });
	</script>
</body>
</html>