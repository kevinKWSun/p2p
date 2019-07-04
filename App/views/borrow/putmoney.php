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
    <title>放款信息</title>
    <link href="/src/css/layui.css" rel="stylesheet" />
    <script src="/src/layui.js"></script>

</head>
<body>
    <div class="main-wrap" style='padding:10px;'>
        <blockquote class="layui-elem-quote fhui-admin-main_hd">
            <h2>放款信息信息</h2>
        </blockquote>
        <form action="/borrow/putmoney" class="layui-form layui-form-pane" id="formrec" method="post" role="form">
            <div class="layui-form-item">
                <label class="layui-form-label">借款人</label>
                <input style="width: 499px;" autocomplete="off" value="<?php echo $borrow_uid?get_member_info($borrow_uid)['real_name']:'无';?>" placeholder="必填,点击选择借款人姓名" class="layui-input" type="text">
            </div>
            <input name='id' value="<?php echo $id ?>" type='text'>
            <div class="layui-form-item">
                <label class="layui-form-label">标名称</label>
                <div class="layui-input-inline">
                    <input value='<?php echo $borrow_name?>' autocomplete="off" placeholder="必填,借款标题名" class="layui-input" type="text" readonly />
                </div>
                <label class="layui-form-label">金额(元)</label>
                <div class="layui-input-inline">
                    <input value='<?php echo $borrow_money?>' autocomplete="off" placeholder="必填,真实姓名" class="layui-input" type="text" readonly>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">类型</label>
                <div class="layui-input-inline">
                    <select data-val="true" data-val-number="字段 Int32 必须是一个数字" data-val-required="Int32 字段是必需的" disabled>
                        <option selected="selected" value="1">车贷</option>
                    </select>
                </div>
                <label class="layui-form-label">期限(天)</label>
                <div class="layui-input-inline">
                    <input value='<?php echo $borrow_duration?>' autocomplete="off" placeholder="必填,借款期限" class="layui-input" type="text" readonly>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">起投(元)</label>
                <div class="layui-input-inline">
                    <input value='<?php echo $borrow_min?>' autocomplete="off" placeholder="必填,单笔最小投资额" class="layui-input" type="text" readonly>
                </div>
                <label class="layui-form-label">终投(元)</label>
                <div class="layui-input-inline">
                    <input value='<?php echo $borrow_max?>' autocomplete="off" placeholder="选填,单笔最大投资额" class="layui-input" type="text" readonly>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">还款方式</label>
                <div class="layui-input-inline">
                    <select data-val="true" data-val-number="字段 Int32 必须是一个数字" data-val-required="Int32 字段是必需的" disabled>
                        <option selected="selected" value="1">一次性到期还款</option>
                    </select>
                </div>
                <label class="layui-form-label">利率(%)</label>
                <div class="layui-input-inline">
                    <input value='<?php echo $borrow_interest_rate?>' autocomplete="off" placeholder="必填,年化利率" class="layui-input" type="text" readonly>
                </div>
            </div>
            <fieldset class="layui-elem-field layui-field-title" style="width: 609px;">
                <legend>借款信息介绍(必填)</legend>
            </fieldset>
            <div class="layui-form-item" style="width: 609px;">
                <?php echo $borrow_info;?>
            </div>
            <!--底部工具栏-->
            <div class="page-footer">
                <div class="btn-list">
                    <div class="btnlist">
						<?php if(is_rule('/borrow/putmoney')) { ?>
							<a class="layui-btn layui-btn-small" lay-submit="" lay-filter="doPost" data-url="/borrow/putmoney"><i class="layui-icon">&#x1005;</i>提交</a>
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
</body>
</html>