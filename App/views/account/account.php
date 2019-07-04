<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>基本信息</title>
    <script type='text/javascript' src='/Content/js/jquery-1.7.2.min.js'></script>
    <link href="Css/bootstrap.min.css" rel="stylesheet" /> 
	<link href="Css/components.css?ver=142682356" rel="stylesheet" /> 
	<link href="Css/main.css?ver=142682356" rel="stylesheet" /> 
	<link href="Css/new-home.css?ver=142682356" rel="stylesheet" />
	<!--[if lt IE 9]>
	<link href="Css/ie8.css?ver=142682356" rel="stylesheet">
	<![endif]--> 
	<!--[if IE 9]>
	<link href="Css/ie9.css?ver=142682356" rel="stylesheet">
	<![endif]-->
</head>
<body>
<?php include("top.php") ?>
<div class="wrapper ">  
    <div id="my-account" class="container my-account">
        <div class="row"> 
            <?php include("left.php") ?>
            <div class="col-xs-9 ng-scope" autoscroll="false" ui-view="" style="">
                <div class="account-profile content-wrapper ng-scope">
                    <header class="section-header">
                        <h6 class="section-header-title">基本信息</h6>
                    </header>
                    <section class="basic summary-section">
                        <div class="basic-profile clearfix">
                            <div class="pull-left">
                                <a class="profile-header">
                                    <img width="100%" src="images/goup_buy_info_people.png">
                                </a>
                            </div>
                            <div class="pull-left">
                                <div class="field">
                                    <h6 class="username ng-binding" ng-bind="basicProfile.name">Member_<?php printf("%06s", QUID);?></h6>
                                </div>
                                <div class="field clearfix">
                                    <span class="pull-left">资料完整度</span>
                                    <div class="pull-left">
                                        <div class="progress">
                                            <div id="security-tooltip" class="progress-bar" ng-style="{width:basicProfile.securityLevel + '%'}" data-original-title="" title="" style="width: <?php if($status['id_status'] && ! $status['phone_status'] || ! $status['id_status'] && $status['phone_status']){echo '50';}else{echo '100';}?>%;"></div>
                                        </div>
                                        <div class="verify-icons">
                                            <ul class="list-unstyled list-inline">
                                                <li class="ng-scope">
                                                    <a class="verify-icon sl-icon-profile <?php echo $status['id_status'] ? 'active':''?>" title="身份认证"></a>
                                                </li>
                                                <li class="ng-scope">
                                                    <a class="verify-icon sl-icon-mobile <?php echo $status['phone_status'] ? 'active':''?>" title="手机号码验证"></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="pull-left">
                                        <span class="security-level ng-binding">
										<?php if($status['id_status'] && ! $status['phone_status'] || ! $status['id_status'] && $status['phone_status']){echo '中';}else{echo '高';}?>
										</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <section class="summary-section">
                        <ul class="nav nav-tabs section-nav-tabs">
                            <li class="active">
                                <a href="javascript:;">个人信息</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div id="personalInfo" class="tab-pane active personal-info">
                                <div class="info-content row">
                                    <div class="col-xs-9">
                                        <div class="info-row" ng-class="{editing:username.editing}">
                                            <div class="row">
                                                <div class="col-xs-3 info-value text-center">用 户 名</div>
                                                <div class="col-xs-5">
                                                    <span class="ng-binding">Member_<?php printf("%06s", QUID);?></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="info-row row-line">
                                            <div class="row">
                                                <div class="col-xs-3 info-value text-center">登录密码</div>
                                                <div class="col-xs-5">
                                                    <span class="">*********</span>
                                                </div>
                                                <div class="col-xs-4">
                                                    <div class="sl-icons">
                                                        <a class="btn btn-secondary bind-blue btn-hollow pass" href="javascript:;">修改</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row editing ng-hide epass">
                                                <form class="ng-pristine ng-invalid ng-invalid-required" id='pass'>
                                                    <div class="row">
                                                        <div class="col-xs-3 info-value text-center">修改登录密码</div>
                                                        <div class="col-xs-8">
                                                            <input class="form-control input-sm ng-pristine ng-invalid ng-invalid-required" type="password" placeholder="输入原有登录密码" name="ypass">
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-xs-3"></div>
                                                        <div class="col-xs-8">
                                                            <input class="form-control input-sm ng-pristine ng-invalid ng-invalid-required" type="password" placeholder="输入新登录密码(6位以上字母和数字组合)" name="newpass">
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-xs-3"></div>
                                                        <div class="col-xs-8">
                                                            <input class="form-control input-sm ng-pristine ng-invalid ng-invalid-required" type="password" placeholder="确认新密码" name="pass">
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-xs-3"></div>
                                                        <div class="col-xs-8">
                                                            <span class="display-block bind-red ng-binding  erpass"></span>
                                                            <span class="btn-group">
                                                                <a class="btn btn-secondary btn-confirm tjpass" href="javascript:;">确定</a>
                                                            </span>
                                                            <span class="btn-group">
                                                                <a class="btn btn-secondary bind-blue btn-hollow cancelChangePwd" href="javascript:;">取消</a>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="info-row row-line">
											<div class="row">
												<div class="col-xs-3 info-value text-center">真实姓名</div>
												<div class="col-xs-5">
													<span class="bind-gray ng-scope"><?php echo $status['id_status'] ? substr_replace($m['real_name'],'*',3,3) : '未验证'?></span>
												</div>
											</div>
											<div class="row">
												<div class="col-xs-3 info-value text-center">身份证号</div>
												<div class="col-xs-5">
													<span class="bind-gray ng-scope"><?php echo $status['id_status'] ? substr_replace($m['idcard'],'***',3,12) : '未验证'?></span>
												</div>
												<?php if(! $status['id_status']){?>
												<div class="col-xs-4 ljsubmit">
													<div class="sl-icons">
														<a class="btn btn-secondary bind-blue btn-hollow btn-bg-blue userIdentity" href="javascript:;">立即验证</a>
													</div>
												</div>
												<?php }?>
											</div>
											<div class="row editing ng-hide userid">
												<form class="ng-pristine ng-invalid ng-invalid-required" id='userIdentity'>
													<div class="row">
														<div class="col-xs-3 info-value text-center">身份验证</div>
														<div class="col-xs-8">
															<input class="form-control input-sm ng-pristine ng-invalid ng-invalid-required" type="text" placeholder="输入您的真实姓名" name='realname'>
														</div>
													</div>
													<div class="row">
														<div class="col-xs-3"></div>
														<div class="col-xs-8">
															<input class="form-control input-sm ng-pristine ng-invalid ng-invalid-required" type="text" placeholder="输入18位身份证号" name='idcode'>
														</div>
													</div>
													<div class="row">
														<div class="col-xs-3"></div>
														<div class="col-xs-8">
															<span class="display-block bind-red ng-binding  codeid"></span>
															<span class="btn-group">
																<a class="btn btn-secondary btn-confirm codesbumit" href="javascript:;">立即验证</a>
															</span>
															<span class="btn-group">
																<a class="btn btn-secondary bind-blue btn-hollow codehide" href="javascript:;">取消</a>
															</span>
														</div>
													</div>
												</form>
											</div>
										</div>
                                        <div class="info-row">
                                            <div class="row">
                                                <div class="col-xs-3 info-value text-center">绑定手机</div>
                                                <div class="col-xs-5">
                                                    <span class="ng-binding ng-scope">
													<?php echo substr_replace($m['phone'],'***',2,7);?>
													</span>
                                                </div>
                                                <div class="col-xs-3">
                                                    <div class="sl-icons bind-green ng-scope">
                                                        <a class="btn btn-secondary bind-blue btn-hollow ctel" href="javascript:;">修改</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row editing ng-hide cttel">
                                                <form class="form-horizontal verifyCellphoneForm ng-pristine ng-invalid ng-invalid-required" id='ctel'>
                                                    <div class="row">
                                                        <div class="col-xs-3 info-value text-center">绑定手机</div>
                                                        <div class="col-xs-8">
                                                            <input class="form-control phone input-sm ng-pristine ng-invalid ng-invalid-required" type="text" placeholder="输入新的手机号码" maxlength="11" name="phone">
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-xs-3 info-value text-center"></div>
                                                        <div class="col-xs-5">
                                                            <input class="form-control phone-verify-input input-sm ng-pristine ng-invalid ng-invalid-required" type="text" placeholder="输入验证码" maxlength="6" name="phoneVerifyCode">
                                                        </div>
                                                        <div class="col-xs-3">
                                                            <a class="btn btn-secondary btn-bg-blue verifyPhoneSubmit ng-binding" title="点击发送验证码" onClick="submitVerificationCode();">
                                                                发 送
                                                                <span class="phoneCountDown ng-binding ng-show">（60）</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-xs-3"></div>
                                                        <div class="col-xs-8">
                                                            <span class="bind-red display-block ng-binding telr"></span>
                                                            <span class="btn-group">
                                                                <a class="btn btn-secondary btn-confirm telsubmit" href="javascript:;">立即绑定</a>
                                                            </span>
                                                            <span class="btn-group">
                                                                <a class="btn btn-secondary bind-blue btn-hollow rctel" href="javascript:;">取消</a>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </section>
                </div>
                <div id="informationModal" class="modal fade information-modal ng-scope">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button class="close sl-icon-cross" ng-click="cancelVerifyPhone()" aria-hidden="true" data-dismiss="modal" type="button"></button>
                                <h4 class="modal-title">完善个人信息</h4>
                            </div>
                            <div class="modal-body">
                                <div class="row ng-binding"> 用户： </div>
                                <div class="row">
                                    您还未验证您的
                                    <span class="ng-show" ng-show="basicProfile.profile.realName">身份证号</span>
                                    <span class="" ng-show="!basicProfile.profile.realName">真实姓名</span>
                                    ，请致电客服电话
                                </div>
                                <div class="row telephone"> 400-1231 </div>
                                <div class="row"> 完善个人信息，继续赚钱投资 </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
    </div> 
    <?php include("foot.php") ?>
</div>
<script>
	$(function(){
		$('a.ctel').click(function(){
			$('.cttel').removeClass('ng-hide');
			$('.cttel').addClass('ng-show');
		});
		$('.rctel').click(function(){
			$('.cttel').removeClass('ng-show');
			$('.cttel').addClass('ng-hide');
		});
		$('.telsubmit').click(function(){
			$.post('/account/tel', $('#ctel').serialize(), function(t){
				$('.telr').text(t.message);
				if(t.state == 1){
					setTimeout(function(){
						$('.cttel').removeClass('ng-show');
						$('.cttel').addClass('ng-hide');
						$('input').val('');
						$('.telr').text('');
					},2000);
				}
			}, 'json');
		});
		$('a.pass').click(function(){
			$('.epass').removeClass('ng-hide');
			$('.epass').addClass('ng-show');
		});
		$('.cancelChangePwd').click(function(){
			$('.epass').removeClass('ng-show');
			$('.epass').addClass('ng-hide');
		});
		$('.tjpass').click(function(){
			$.post('/account/mfpassword', $('#pass').serialize(), function(p){
				$('.erpass').text(p.message);
				if(p.state == 1){
					setTimeout(function(){
						$('.epass').removeClass('ng-show');
						$('.epass').addClass('ng-hide');
						$('input').val('');
						$('.erpass').text('');
					},2000);
				}
			}, 'json');
		});
		$('a.userIdentity"').click(function(){
			$('.userid').removeClass('ng-hide');
			$('.userid').addClass('ng-show');
		});
		$('.codehide').click(function(){
			$('.userid').removeClass('ng-show');
			$('.userid').addClass('ng-hide');
		});
		$('.codesbumit').click(function(){
			$.post('/account/codeid', $('#userIdentity').serialize(), function(c){
				$('.codeid').text(c.message);
				if(c.state == 1){
					$('.ljsubmit').remove();
					setTimeout(function(){
						$('.userid').removeClass('ng-show');
						$('.userid').addClass('ng-hide');
						$('input').val('');
						$('.codeid').text('');
					},2000);
				}
			}, 'json');
		});
	});
</script>
</body>
</html>