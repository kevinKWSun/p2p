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
    <title>增加权限</title>
	<link href="/src/css/layui.css" rel="stylesheet" />
    <script src="/src/layui.js"></script>
</head>
<body>
    <div class="main-wrap" style='padding:10px;'>
        <blockquote class="layui-elem-quote fhui-admin-main_hd">
            <h3>增加权限</h3>
        </blockquote>
        <form action="/group/add" class="layui-form layui-form-pane" id="formrec" method="post" role="form">
            <div class="layui-form-item">
                <label class="layui-form-label">权限名称</label>
                <div class="layui-input-block">
                    <input id='name' name="name" autocomplete="off" lay-verify="required" value="" placeholder="必填,权限名称" class="layui-input" type="text">
                </div>
            </div>
            <?php if($rules){foreach ($rules as $k => $v): ?>
            <dl class="checkmod">
                <dt>
                    <input title="<?php echo $v['title']?>" lay-skin="primary" name="rules" ids="<?php echo $v['id']?>" type="checkbox" lay-filter="demofx" />
                </dt>
                <?php foreach ($v['child'] as $ks => $vs): if($vs['child']){?>
                <dd>
                    <div class='towC' style='margin-left:28px;'>
                        <input title="<?php echo $vs['title']?>" lay-skin="primary" name="rules" ids="<?php echo $vs['id']?>" type="checkbox" lay-filter="demofx" />
                    </div>
                    <ul style='padding-left:28px;'>
                    <?php foreach ($vs['child'] as $kb=>$vb): if($vb['child']){?>
                    <li>
                        <div class='towD' style='margin-left:28px;'>
                            <input title="<?php echo $vb['title']?>" lay-skin="primary" name="rules" ids="<?php echo $vb['id']?>" type="checkbox" lay-filter="demofx" />
                        </div>
                        <ul style='padding-left:28px;'>
                        <?php foreach ($vb['child'] as $kc=>$vc): if($vc['child']){?>
                        <li>
                            <div class='towE' style='margin-left:28px;'>
                                <input title="<?php echo $vc['title']?>" lay-skin="primary" name="rules" ids="<?php echo $vc['id']?>" type="checkbox" lay-filter="demofx" />
                            </div>
                            <?php foreach ($vc['child'] as $kd=>$vd): ?>
                            <span class="divsion">&nbsp;</span>
                            <span style='margin-left:28px;'>
                                <input title="<?php echo $vd['title']?>" lay-skin="primary" name="rules" ids="<?php echo $vd['id']?>" type="checkbox" />
                            </span>
                            <?php endforeach; ?>
                        </li>
                        <?php }else{?>
                        <span style='margin-left:28px;'>
                            <input title="<?php echo $vc['title']?>" lay-skin="primary" name="rules" ids="<?php echo $vc['id']?>" type="checkbox" />
                        </span>
                        <?php }endforeach;?>
                        </ul>
                    </li>
                    <?php }else{?>
                    <span style='margin-left:28px;'>
                        <input title="<?php echo $vb['title']?>" lay-skin="primary" name="rules" ids="<?php echo $vb['id']?>" type="checkbox" />
                    </span>
                    <?php }endforeach; ?>
                    </ul>
                </dd>
                <?php }else{?>
                <span>
                    <input title="<?php echo $vs['title']?>" lay-skin="primary" name="rules" ids="<?php echo $vs['id']?>" type="checkbox" />
                </span>
                <?php }endforeach; ?>
            </dl>
            <?php endforeach;} ?>
            <!--底部工具栏-->
            <div class="page-footer" style='margin-top:20px;'>
                <div class="btn-list">
                    <div class="btnlist">
                        <a class="layui-btn layui-btn-sm" lay-submit="" lay-filter="doPostg" data-url="/group/add"><i class="layui-icon">&#x1005;</i>提交</a>
                        <a class="layui-btn layui-btn-sm do-action" data-type="doRefresh" data-url=""><i class="layui-icon">&#xe669;</i>刷新</a>
                        <a class="layui-btn layui-btn-sm do-action" data-type="doGoBack"><i class="layui-icon">&#xe65c;</i>返回上一页</a>
                        <a class="layui-btn layui-btn-sm do-action" data-type="doGoTop" data-url=""><i class="layui-icon">&#xe604;</i>返回顶部</a>
                    </div>
                </div>
            </div>
            <!--/底部工具栏-->
        </form>
    </div>
    <script src="/src/global.js"></script>
</body>
</html>