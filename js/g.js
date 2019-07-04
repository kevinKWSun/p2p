var global_ajaxObject = {
	//index 模块
	banners : "banners", //轮播图
	login   : "login",
	sendcode: "sendcode",
	reg     : "reg",
	novice  : "novice",
	invest  : "invest",
	noun    : "noun",
	question: "question",
	userinfo: "info",
	uppassword: "mfpassword",
};

var ApiUrl = "http://www.jiamanu.cn/appapi/"; //接口地址
var ImgUrl = "http://www.jiamanu.cn"; //图片地址
var localurl = 'http://m.jiamanu.cn/';
var global_localStorage = {
	UserName: "loc_UserName", //用户名
	UserId: "loc_UserId", //用户id
	BidItemId: "loc_BidItemId",
	GidItemId: "loc_GidItemId",
};
var ajax = function ajax(url, data, success, type, dataType) {
	type = arguments[3] ? arguments[3] : 'post';
	dataType = arguments[4] ? arguments[4] : 'json';
	//data = MD5Data(data);
	mui.ajax(url, {
		data: data,
		dataType: dataType,
		type: type,
		timeout: 50000,
		/* headers:{
			'Content-Type':'application/x-www-form-urlencoded'
		}, */
		success: function(response) {
			success(response);
		},
		error: function(xhr, type, errorThrown) {
			if(type == 'timeout') {
				mui.toast("请求超时：请检查网络");
			}else{
				mui.toast('连接异常：' + type + '\n err:' + errorThrown);
			}
		}
	});

};
var MuiUse = {
	openWindow: function(url, id, aniShow, WAutoShow) {
		if(!arguments[0]) {
			mui.toast('未检测到界面');
			return false;
		}
		id = arguments[1] ? arguments[1] : arguments[0];
		//localStorage.setItem(global_localStorage.UrlId, id);
		aniShow = arguments[2] ? arguments[2] : 'slide-in-right';
		WAutoShow = arguments[3] ? arguments[3] : false;
		mui.openWindow({
			url: url,
			id: id,
			createNew: true,
			show: {
				autoShow: true, //页面loaded事件发生后自动显示，默认为true
				aniShow: aniShow //页面显示动画，默认为”slide-in-right“；
			},
			waiting: {
				autoShow: WAutoShow, //自动显示等待框，默认为true
				title: '正在加载...' //等待对话框上显示的提示内容
			}
		})
	}
};
var MuiUses = {
	openWindow: function(url, id, aniShow, WAutoShow) {
		if(!arguments[0]) {
			mui.toast('未检测到界面');
			return false;
		}
		id = arguments[1] ? arguments[1] : arguments[0];
		//localStorage.setItem(global_localStorage.UrlId, id);
		aniShow = arguments[2] ? arguments[2] : 'slide-in-right';
		WAutoShow = arguments[3] ? arguments[3] : false;
		mui.openWindow({
			url: url,
			id: id,
			createNew: true,
			show: {
				autoShow: true, //页面loaded事件发生后自动显示，默认为true
				aniShow: aniShow //页面显示动画，默认为”slide-in-right“；
			},
			waiting: {
				autoShow: WAutoShow, //自动显示等待框，默认为true
				title: '正在加载...' //等待对话框上显示的提示内容
			}
		})
	}
};
function checkPhone(Iphone) {
	var phone = Iphone;
	if(! (/^1[345789]\d{9}$/.test(phone))) {
		//alert("手机号码有误，请重填");  
		return false;
	}else{
		return true;
	}
}
/*
 * 退出账户
 */
function util_logOut() {
	localStorage.removeItem(global_localStorage.UserId);
	localStorage.removeItem(global_localStorage.UserName);
	MuiUse.openWindow("login.html");
}
function cative_on(rel){
	$('nav.mui-bar-tab a').removeClass('mui-active');
	if(rel == 1){
		$('nav.mui-bar-tab a[rel=1]').addClass('mui-active');
  	}else if(rel == 2){
  		$('nav.mui-bar-tab a[rel=2]').addClass('mui-active');
  	}else if(rel == 3){
  		$('nav.mui-bar-tab a[rel=3]').addClass('mui-active');
  	}else if(rel == 4){
  		$('nav.mui-bar-tab a[rel=4]').addClass('mui-active');
  	}
}
mui("nav").on('tap','a',function(){
  	var rel = this.getAttribute("rel");
  	var href = '';
  	if(rel == 1){
		href = 'index.html';
  	}else if(rel == 2){
  		href = 'list.html';
  	}else if(rel == 3){
  		href = 'user.html';
  	}else if(rel == 4){
  		href = 'help.html';
  	}
  	MuiUse.openWindow(href);
  	return;
});
$('#BidList').on('tap', '.biao_nr', function(){
	var bid = $(this).find('h3').attr('data-id');
	localStorage.setItem(global_localStorage.BidItemId, bid);
	MuiUse.openWindow('detail.html');
  	return;
});
$('#BidList').on('tap', '.wdtz_nr', function(){
	var bid = $(this).find('h3').attr('data-id');
	localStorage.setItem(global_localStorage.BidItemId, bid);
	MuiUse.openWindow('detail.html');
  	return;
});
var uid = localStorage.getItem(global_localStorage.UserId);
var uname = localStorage.getItem(global_localStorage.UserName);
var bid = localStorage.getItem(global_localStorage.BidItemId);
var gid = localStorage.getItem(global_localStorage.GidItemId);
mui('.fw_fl').on('tap', 'a', function(e) {
	fw = this.getAttribute('id');
	click_news(fw);
});
function is_login(uids){
	if(uids || uids != null){
		MuiUse.openWindow("user.html");
	}else{
		if(uid || uid != null){
			MuiUse.openWindow("user.html");
		}
	}
};
function is_logins(uids){
	if(! uids || uids == null){
		MuiUse.openWindow("login.html");
	}else{
		if(! uid || uid == null){
			MuiUse.openWindow("login.html");
		}
	}
};
var InterValObj; 
var count = 60; 
var curCount;
function sendMessage() {
	curCount = count;
	$("#getCode").prop("disabled",false);
	$("#getCode").text(curCount + "(S)").prop("disabled",true);
	InterValObj = window.setInterval(SetRemainTime, 1000); 
};
//timer处理函数
function SetRemainTime() {
	if (curCount == 0) {                
		window.clearInterval(InterValObj);
		$("#getCode").prop("disabled", false);
		$("#getCode").text("重新发送").prop("disabled", false);
	}
	else {
		curCount--;
		$("#getCode").prop("disabled", false);
		$("#getCode").text(curCount + "(S)").prop("disabled",true);
	}
};
function Reg(){
	var telNo = $('#textUserName').val().trim();
	var pNo = $('#passwordPassword').val().trim();
	var cNo = $('#verifyCode').val().trim();
	var pwd = $('#textCode').val().trim();
	if(telNo == ""){
		mui.toast('请输入手机号');
		return false;
	}
	if(! checkPhone(telNo)) {
		mui.toast('手机号码格式不正确');
		return;
	}
	if(cNo == ""){
		mui.toast('输入手机验证码');
		return false;
	}
	if(pNo == ""){
		mui.toast('输入登录密码');
		return false;
	}
	var success = function (response){
		mui.toast(response.message);
		if(response.state == 1){
			$('.in1').val('');
			localStorage.setItem(global_localStorage.UserName, response.user_name);
			localStorage.setItem(global_localStorage.UserId, response.user_id);
			MuiUse.openWindow(response.url);
			return;
		}
	}
	var data = {
		phone     : telNo,
		user_pass : pNo,
		pcode     : cNo,
		codeuid   : pwd
	}
	ajax(ApiUrl + global_ajaxObject.reg, data, success);
};
function Login(){
	var telNo = $('#textUserName').val().trim();
	var pwd = $('#verifyCode').val().trim();
	if(telNo == ""){
		mui.toast('请输入手机号');
		return false;
	}
	if(! checkPhone(telNo)) {
		mui.toast('手机号码格式不正确');
		return false;
	}
	if(pwd == ""){
		mui.toast('输入登录密码或手机验证码');
		return false;
	}
	var success = function (response){
		mui.toast(response.message);
		if(response.state == 1){
			$('.in1').val('');
			localStorage.setItem(global_localStorage.UserName, response.user_name);
			localStorage.setItem(global_localStorage.UserId, response.user_id);
			MuiUse.openWindow("user.html");
			return;
		}
	}
	var data = {
		user_name : telNo,
		user_pass : pwd
	}
	ajax(ApiUrl + global_ajaxObject.login, data, success);
};
function getCode(type){
	var telNo = $('#textUserName').val().trim();
	if(telNo == ""){
		mui.toast('请输入手机号');
		return false;
	}
	if(! isMobilePhone(telNo)) {
		mui.toast('手机号码格式不正确');
		return;
	}
	var success = function (response){
		mui.toast(response.message);
		if(response.state == 1){
			sendMessage();
		}else{
			return;
		}
	}
	var data = {
		user_name : telNo,
		type : type
	}
	ajax(ApiUrl + global_ajaxObject.sendcode, data, success);
};
function click_news(ons){
	$('.fw_fl a').removeClass('on');
	$('#'+ons).addClass('on');
	var success = function (response){
		if(response.state == 1){
			$('#mainContent').html(response.con.content);
			return;
		}
	}
	var data = {
		type : 'info'
	}
	ajax(ApiUrl + ons, data, success, 'get');
};
