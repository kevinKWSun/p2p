<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//公共方法
/**
 * 检测用户是否登录
 * @return integer 0-未登录，大于0-当前登录用户ID
 */
function is_login(){
	$SC =& get_instance();
	$SC->load->library('session');
    $uid = $SC->session->userdata('uid');
    if (empty($uid)) {
        return 0;
    } else {
        return $uid ? $uid : 0;
    }
}
function is_qlogin(){
    $SC =& get_instance();
    $SC->load->library('session');
    $uid = $SC->session->userdata('quid');
    if (empty($uid)) {
        return 0;
    } else {
        return $uid ? $uid : 0;
    }
}
/**检查用户是否有操作权限 */
function is_rule($rule) {
	$SC =& get_instance();
	$SC->load->library('auth');
	if(UID != 1 && ! $SC->auth->check($rule, UID, 1, 'url')){
		return false;
	}
	return true;
}
/**
 * 检测当前用户是否为管理员
 * @return boolean true-管理员，false-非管理员
 */
function is_administrator($uid = null){
    $uid = is_null($uid) ? is_login() : $uid;
    return $uid && (intval($uid) === 1);
}
/**
 * 数据签名认证
 * @param  array  $data 被认证的数据
 * @return string       签名
 */
function data_auth_sign($data) {
    //数据类型检测
    if(!is_array($data)){
        $data = (array)$data;
    }
    ksort($data); //排序
    $code = http_build_query($data); //url编码并生成query字符串
    $sign = sha1($code); //生成签名
    return $sign;
}
/**
 * 字符串截取，支持中文和其他编码
 * @static
 * @access public
 * @param string $str 需要转换的字符串
 * @param string $start 开始位置
 * @param string $length 截取长度
 * @param string $charset 编码格式
 * @param string $suffix 截断显示字符
 * @return string
 */
function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true) {
    if(function_exists("mb_substr"))
        $slice = mb_substr($str, $start, $length, $charset);
    elseif(function_exists('iconv_substr')) {
        $slice = iconv_substr($str,$start,$length,$charset);
        if(false === $slice) {
            $slice = '';
        }
    }else{
        $re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
        $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
        $re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
        $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
        preg_match_all($re[$charset], $str, $match);
        $slice = join("",array_slice($match[0], $start, $length));
    }
    return $suffix ? $slice.'...' : $slice;
}
/**
 * 系统加密方法
 * @param string $data 要加密的字符串
 * @param string $key  加密密钥
 * @param int $expire  过期时间 单位 秒
 * @return string
 */
function suny_encrypt($data, $key = '', $expire = 0) {
    $key  = md5(empty($key) ? 'Suny' : $key);
    $data = base64_encode($data);
    $x    = 0;
    $len  = strlen($data);
    $l    = strlen($key);
    $char = '';

    for ($i = 0; $i < $len; $i++) {
        if ($x == $l) $x = 0;
        $char .= substr($key, $x, 1);
        $x++;
    }

    $str = sprintf('%010d', $expire ? $expire + time():0);

    for ($i = 0; $i < $len; $i++) {
        $str .= chr(ord(substr($data, $i, 1)) + (ord(substr($char, $i, 1)))%256);
    }
    return str_replace(array('+','/','='),array('-','_',''),base64_encode($str));
}

/**
 * 系统解密方法
 * @param  string $data 要解密的字符串 （必须是suny_encrypt方法加密的字符串）
 * @param  string $key  加密密钥
 * @return string
 */
function suny_decrypt($data, $key = ''){
    $key    = md5(empty($key) ? 'Suny' : $key);
    $data   = str_replace(array('-','_'),array('+','/'),$data);
    $mod4   = strlen($data) % 4;
    if ($mod4) {
       $data .= substr('====', $mod4);
    }
    $data   = base64_decode($data);
    $expire = substr($data,0,10);
    $data   = substr($data,10);
    if($expire > 0 && $expire < time()) {
        return '';
    }
    $x      = 0;
    $len    = strlen($data);
    $l      = strlen($key);
    $char   = $str = '';
    for ($i = 0; $i < $len; $i++) {
        if ($x == $l) $x = 0;
        $char .= substr($key, $x, 1);
        $x++;
    }

    for ($i = 0; $i < $len; $i++) {
        if (ord(substr($data, $i, 1))<ord(substr($char, $i, 1))) {
            $str .= chr((ord(substr($data, $i, 1)) + 256) - ord(substr($char, $i, 1)));
        }else{
            $str .= chr(ord(substr($data, $i, 1)) - ord(substr($char, $i, 1)));
        }
    }
    return base64_decode($str);
}
//验证手机
function is_phone($phone){
    if(! preg_match("/^1[3456789]{1}[0-9]{9}$/", trim($phone))){
        return FALSE;
    }else{
        return TRUE;
    }
}
//验证密码 type1 字母加数字  2 字母
function is_password($pwd, $type = 1){
    if($type == 1){
        if(! preg_match("/^(?=.*?[a-zA-Z])(?=.*?[0-9])[a-zA-Z0-9]{6,20}$/", trim($pwd))){
            return FALSE;
        }else{
            return TRUE;
        }
    }else{
        if(! preg_match('/^[a-zA-Z]{3,20}$/', trim($pwd))){
            return FALSE;
        }else{
            return TRUE;
        }
    }
}
/* 无限极分类获得一维数组 */
function get_son_arr($arr, $pname = '', $pid = 0, $level = '　├ ') {
    $tree = array();
    if(! is_array($arr)){
        return false;
    }
    if(! is_string($pname)){
        return false;
    }
    foreach($arr as $v){
        $pnames = empty($pname) ? 'pid' : $pname;
        if($v[$pnames] == $pid){
            $v['level'] = $level;
            $tree[] = $v;
            $tree = array_merge($tree,get_son_arr($arr, $pnames, $v['id'], '　' . $level));
        }
    }
    return $tree;
}
/* 无限极分类获得多维数组 */
function get_sons_arr($arr, $pname = '', $pid = 0, $level = '　 ') {
    $tree = array();
    if(! is_array($arr)){
        return false;
    }
    if(! is_string($pname)){
        return false;
    }
    foreach($arr as $k => $v){
        $pnames = empty($pname) ? 'pid' : $pname;
        if($v[$pnames] == $pid){
            $v['level'] = $level;
            $v['child'] = get_sons_arr($arr, $pname, $v['id'], '　' . $level);
            $tree[] = $v;
        }
    }
    return $tree;
}
//通过PID获取所有父级id
function get_parents_byid ($cate, $pid) {
    $arr = array();
    if(! is_array($arr)){
        return false;
    }
    foreach ($cate as $v) {
        if ($v['id'] == $pid) {
            $arr[] = $v;
            $arr = array_merge(get_childs_byid($cate, $v['pid']), $arr);
        }
    }
    return $arr;
}
//php数组格式化
function p($arr){
    return '<pre>' . print_r($arr) . '<pre>';
}
function get_user($id){
    $CI =& get_instance();
    $CI->load->model('user/ausers_model');
    return $CI->ausers_model->get_ausers_byuid($id);
}
function get_member_info($uid){
    $CI =& get_instance();
    $CI->load->model('member/member_model');
    return $CI->member_model->get_member_info_byuid($uid);
}
function get_members($uid){
    $CI =& get_instance();
    $CI->load->model('member/member_model');
    return $CI->member_model->get_m_bycodeuid($uid);
}
/**
 * 生成随机字符串
 * @param int $len 生成位数,默认6个字符
 * @param int $type 1所有,2英文,3数字
 * @return string
 */
function salt($len = 6,$type=1){
    switch($type){
        case 1:
            $chars = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz!@#$%^&*()';
            break;
        case 2:
            $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
            break;
        case 3:
            $chars = '1234567890';
            break;
    }
    $str = '';
    for( $i = 0; $i < $len; $i++ ){
        $str .= $chars[mt_rand( 0, strlen($chars) -1 )];
    }
    return $str;
}
// 计算身份证校验码，根据国家标准GB 11643-1999
function idcard_verify_number($idcard_base){
	if (strlen($idcard_base) != 17){ 
		return false; 
	}
	// 加权因子
	$factor = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
	// 校验码对应值
	$verify_number_list = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
	$checksum = 0;
	for ($i = 0; $i < strlen($idcard_base); $i++){
		$checksum += substr($idcard_base, $i, 1) * $factor[$i];
	}
	$mod = strtoupper($checksum % 11);
	$verify_number = $verify_number_list[$mod];
	return $verify_number;
}
// 将15位身份证升级到18位
function idcard_15to18($idcard){
	if (strlen($idcard) != 15){
		return false;
	}else{
		// 如果身份证顺序码是996 997 998 999，这些是为百岁以上老人的特殊编码
		if (array_search(substr($idcard, 12, 3), array('996', '997', '998', '999')) !== false){
			$idcard = substr($idcard, 0, 6) . '18'. substr($idcard, 6, 9);
		}else{
			$idcard = substr($idcard, 0, 6) . '19'. substr($idcard, 6, 9);
		}
	}
	$idcard = $idcard . idcard_verify_number($idcard);
	return $idcard;
}
//18位身份证校验码有效性检查
function idcard_checksum18($idcard){
	if (strlen($idcard) != 18){
		return false;
	}
	$aCity = array(11 => "北京",12=>"天津",13=>"河北",14=>"山西",15=>"内蒙古",
	21=>"辽宁",22=>"吉林",23=>"黑龙江",
	31=>"上海",32=>"江苏",33=>"浙江",34=>"安徽",35=>"福建",36=>"江西",37=>"山东",
	41=>"河南",42=>"湖北",43=>"湖南",44=>"广东",45=>"广西",46=>"海南",
	50=>"重庆",51=>"四川",52=>"贵州",53=>"云南",54=>"西藏",
	61=>"陕西",62=>"甘肃",63=>"青海",64=>"宁夏",65=>"新疆",
	71=>"台湾",81=>"香港",82=>"澳门",
	91=>"国外");
	//非法地区
	if (!array_key_exists(substr($idcard,0,2),$aCity)) {
		return false;
	}
	//验证生日
	if (!checkdate(substr($idcard,10,2),substr($idcard,12,2),substr($idcard,6,4))) {
		return false;
	}
	$idcard_base = substr($idcard, 0, 17);
	if (idcard_verify_number($idcard_base) != strtoupper(substr($idcard, 17, 1))){
		return false;
	}else{
		return true;
	}
}
//订单号
function genRandomString($lens){  
	$chars = array("0", "1", "2","3", "4", "5", "6", "7", "8", "9","0", "1", "2","3", "4", "5", "6", "7", "8", "9","0", "1", "2","3", "4", "5", "6", "7", "8", "9","0", "1", "2","3", "4", "5", "6", "7", "8", "9","0", "1", "2","3", "4", "5", "6", "7", "8", "9", "0", "1", "2","3", "4", "5", "6", "7", "8", "9");
	$charsLen = count($chars) - 1;
	shuffle($chars);
	$output = "";
	for ($i=0; $i<$lens; $i++){
		$output .= $chars[mt_rand(0, $charsLen)];
	}
	return $output;
} 
function getTxNo16(){
	$timePrefix = date("ymdH"); //yyMMddHH
	$randomString = genRandomString(8);
	return $timePrefix.$randomString;
}
function getTxNo20(){
	$timePrefix = date("ymdHi"); //yyMMddHHmm
	$randomString = genRandomString(10);
	return $timePrefix.$randomString;
}
function get_curl($url){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	$output  = curl_exec($ch);
	curl_close($ch);
	return $output ;
}
function post_curl($url,$post_data = '',$header = 0){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch,CURLOPT_BINARYTRANSFER,1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	/* if($header){
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
	}
	if(! $header){
		$post_data = http_build_query($post_data);
	} */
	$post_data = http_build_query($post_data);
	curl_setopt($ch, CURLOPT_POSTFIELDS,$post_data);
	curl_setopt($ch, CURLOPT_URL, $url);
	$output  = curl_exec($ch);
	curl_close($ch);
	return $output ;
}
/** json数据 post请求*/
function post_curl_test($url,$post_data = '',$header = 0){
	$ch = curl_init();
	//$data_url = http_build_query($post_data);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch,CURLOPT_BINARYTRANSFER,1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($post_data)));
	//$post_data = $data_url;
	curl_setopt($ch, CURLOPT_POSTFIELDS,$post_data);
	curl_setopt($ch, CURLOPT_URL, $url);
	$output  = curl_exec($ch);
	curl_close($ch);
	return $output ;
}

////////////////银行
/**
 * RSA签名
 * @param $data 待签名数据(按照文档说明拼成的字符串)
 * @param $private_key_path 商户私钥文件路径
 * return 签名结果
 */
function rsaSign($data, $private_key_path) {
    $priKey = file_get_contents($private_key_path);
    $res = openssl_get_privatekey($priKey);
    openssl_sign($data, $sign, $res);
    openssl_free_key($res);
	//base64编码
    $sign = base64_encode($sign);
    return $sign;
}

/**
 * RSA验签
 * @param $data 待签名数据(如果是xml返回则数据为<plain>标签的值,包含<plain>标签，如果为form(key-value，一般指异步返回类的)返回,则需要按照文档中进行key的顺序进行，value拼接)
 * @param $ali_public_key_path 富友的公钥文件路径
 * @param $sign 要校对的的签名结果
 * return 验证结果
 */
function rsaVerify($data, $ali_public_key_path, $sign)  {
	$pubKey = file_get_contents($ali_public_key_path);
    $res = openssl_get_publickey($pubKey);
    $result = (bool)openssl_verify($data, base64_decode($sign), $res);
    openssl_free_key($res);    
    return $result;
}
//短信start
function curlPost($url,$postFields){
	$postFields = json_encode($postFields);
	$ch = curl_init ();
	curl_setopt( $ch, CURLOPT_URL, $url ); 
	curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/json; charset=utf-8'
		)
	);
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
	curl_setopt( $ch, CURLOPT_POST, 1 );
	curl_setopt( $ch, CURLOPT_POSTFIELDS, $postFields);
	curl_setopt( $ch, CURLOPT_TIMEOUT,1); 
	curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0);
	$ret = curl_exec ( $ch );
	if (false == $ret) {
		$result = curl_error(  $ch);
	} else {
		$rsp = curl_getinfo( $ch, CURLINFO_HTTP_CODE);
		if (200 != $rsp) {
			$result = "请求状态 ". $rsp . " " . curl_error($ch);
		} else {
			$result = $ret;
		}
	}
	curl_close ( $ch );
	return $result;
}
function sendSMS($mobile, $msg, $needstatus = 'true') {
		$postArr = array (
			'account'  =>  'N0714100',
			'password' => 'pWM8E7r1K3a77e',
			'msg' => urlencode($msg),
			'phone' => $mobile,
			'report' => $needstatus
        );
		$result = curlPost('http://smssh1.253.com/msg/send/json', $postArr);
		return $result;
	}
function send_sms($mobile, $msg, $code = ''){
	$result = sendSMS($mobile, '【伽满优】' . $msg . $code);
	if(! is_null(json_decode($result))){
		$output=json_decode($result,true);
		if(isset($output['code'])  && $output['code']=='0'){
			//echo '短信发送成功！' ;
			return TRUE;
		}else{
			//echo $output['errorMsg'];
			return FALSE;
		}
	}else{
		return FALSE;
	}
}
//短信end

//请求银行接口统一加密方法[请求报文]
function head($data, $tradeCode, $url = '', $merOrderNo = '') {
	$CI =& get_instance();
	$head  = array(
		//报文版本号
		'version' 		=> '1.0.0',
		//请求报文
		'tradeType'		=> '00',
		//存管平台分配的唯一编号
		'merchantNo'	=> $CI->config->item('mchnt_cd'),
		//交易日期
		'tradeDate'		=> date('Ymd'),
		//交易时间
		'tradeTime'		=> date('His'),
		//商户请求的唯一标识(自己生成的流水号)
		'merOrderNo'	=> empty($merOrderNo) ? getTxNo20() : $merOrderNo,
		//请求交易代码
		'tradeCode'		=> $tradeCode,
	);
	foreach($data as $k=>$v) {
		$head[$k] = $v;
	}
	//加密解密请求地址
	$payapi = $CI->config->item('payapi');
	
	//数据加密链接,返回的是字符串
	if(!empty($url)) {
		$res = post_curl($payapi[$url], $head);
	} else {
		$res = post_curl($payapi['condo'], $head);
	}
	
	//转数组数据
	$res = json_decode($res, true);
	
	//返回数据
	return $res;
}
//请求银行接口统一加密方法[响应报文]
// function response($tradeCode, $bizFlow, $merOrderNo, $data = array()) {
	// $CI =& get_instance();
	// $head  = array(
		//报文版本号
		// 'version' 		=> '1.0.0',
		//响应报文
		// 'tradeType'		=> '01',
		//存管平台分配的唯一编号
		// 'merchantNo'	=> $CI->config->item('mchnt_cd'),
		//交易日期
		// 'tradeDate'		=> date('Ymd'),
		//交易时间
		// 'tradeTime'		=> date('His'),
		//商户请求的唯一标识(自己生成的流水号)
		// 'merOrderNo'	=> $merOrderNo,
		//响应交易代码
		// "tradeCode"		=> $tradeCode,
		// "bizFlow" 		=> $bizFlow,
		// "respCode"		=> '000000',
	// );
	// if(!empty($data)) {
		// foreach($data as $k=>$v) {
			// $head[$k] = $v;
		// }
	// }
	//加密解密请求地址
	// $payapi = $CI->config->item('payapi');
	
	//数据加密链接,返回的是字符串
	// $res = post_curl($payapi['condo'], $head);
	
	//返回数据
	// return $res;
// }
//请求银行接口统一加密方法[响应报文]
function response($respCode) {
	$CI =& get_instance();
	$head = array(
		'merchantNo' => $CI->config->item('mchnt_cd'),
		'respCode'	 => $respCode
	);
	$payapi = $CI->config->item('payapi');
	$res = post_curl($payapi['success'], $head);
	return $res;
}
//生成流水信息
function water($uid, $merOrderNo, $tradeCode, $bid = 0, $money = 0, $redid = 0) {
	$CI = & get_instance();
	//记录流水数据
	$CI->load->model('water/water_model');
	$data_water['UID'] = $uid;
	$data_water['merOrderNo'] = $merOrderNo;
	$data_water['tradeCode'] = $tradeCode;
	if(!empty($money)) {
		$data_water['money'] = $money;
	}
	if(!empty($money)) {
		$data_water['redid'] = $redid;
	}
	$data_water['bid'] = $bid;
	$data_water['addtime'] = time();
	$CI->water_model->add_water($data_water);
}
//银行接口返回数据解密方法
function decrypt($params) {
	$CI =& get_instance();
	$payapi = $CI->config->item('payapi');
	return post_curl($payapi['desdo'], $params);
}

//写文件测试
function log_record($word, $file = '', $tradNo = '') {
	//$word = serialize($word);
	$dirpath = dirname(BASEPATH);
	$path = empty($file) ? $dirpath . '/App/logs/error.log' : $dirpath . '/App/logs' . $file;
	$fp = fopen($path,"a");
	flock($fp, LOCK_EX) ;
	$title = "message：" . $tradNo . "  " . date('Y-m-d H:i:s');
	fwrite($fp,$title."\n".$word."\n");
	flock($fp, LOCK_UN);
	fclose($fp);
}

/** 获取下个月当前日期 */
function get_next_month($timestamp, $i) {
	$returned = 0;
	$day = date('d', $timestamp);
	//获取下个月的最后一天
	$last_day = strtotime("last day of +".$i." month", $timestamp);
	if($day > date('d', $last_day)) {
		$returned = $last_day;
	} else {
		$returned = strtotime(date('Y-m-'.$day.' H:i:s', $last_day));
	}
	return $returned;
}
/**生成借款合同 */
function contract_build($data, $type) {
	$CI =& get_instance();
	
	//模版
	$CI->load->model('contract/contract_model');
	$res = $CI->contract_model->get_contract_one($type);
	
	$CI->load->library('parser');
	$CI->parser->set_delimiters('{', '}');
	return $CI->parser->parse_string($res['mold'], $data, true);
}
/** 生成pdf */
function html2pdf($html, $output = array(), $path = '') {
	$CI =& get_instance();
	$CI->load->library('Tcpdf/Tcpdf'); 

	$tcpdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT,true, 'UTF-8', false);
	#var_dump($tcpdf);
	
	//设置作者，标题，文件属性
	$tcpdf->SetCreator('');
	$tcpdf->SetAuthor('th');
	$tcpdf->SetTitle('协议');
	$tcpdf->SetSubject('SUBJECT');
	$tcpdf->SetKeywords('PDF, TCPDF');
	// 设置页眉和页脚信息
	$tcpdf->setHeaderData('', 0, '', '上海童汇信息科技有限公司', array(0,0,0), array(0,0,0));
	$tcpdf->setFooterData(array(0,0,0), array(0,0,0));
	
	// 设置页眉和页脚字体
	$tcpdf->setHeaderFont(Array('stsongstdlight', '', '10'));
	$tcpdf->setFooterFont(Array('helvetica', '', '8'));

	//设置文档对齐，间距，字体，图片
	$tcpdf->SetCreator(PDF_CREATOR);
	$tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
	$tcpdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

	//设置页眉页脚 边距
	$tcpdf->setHeaderMargin(PDF_MARGIN_HEADER);
	$tcpdf->setFooterMargin(PDF_MARGIN_FOOTER);
	
	//自动分页
	$tcpdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
	$tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
	$tcpdf->setFontSubsetting(true);
	#$tcpdf->setPageMark();
	//设置正文字体，大小   （stsongstdlight，网上说这个字体支持的文字更全，支持中文不乱码）
	$tcpdf->SetFont('stsongstdlight', '', 10);

	//创建页面，渲染PDF
	$tcpdf->AddPage();
	
	//$html = $thml;
	#$html = strip_tags($html);
	$tcpdf->writeHTML($html, true, false, true, true, ''); 
	$tcpdf->lastPage();

	//PDF输出   I：在浏览器中打开，D：下载，F：在服务器生成pdf ，S：只返回pdf的字符串
	#ob_clean();
	$root_dir = dirname(dirname(dirname(__FILE__))) . '/contracts/';
	
	if(empty($path)) {
		$real_path = $root_dir . date('Y-m-d');
		if(!is_dir($real_path)) mkdir($real_path,0755);
		$real_path .= '/' . md5(time()) . '.pdf';
	} else {
		$path = ltrim($path, '/');
		$real_path = $root_dir.$path;
	}
	
	if(empty($output)) {
		$tcpdf->Output('$real_path','I');
	}
	if(in_array('I', $output)) {
		$tcpdf->Output($real_path, 'I');
	}
	if(in_array('D', $output)) {
		$tcpdf->Output($real_path, 'D');
	}
	if(in_array('F', $output)) {
		//自定义路径生成文件
		if(!empty($path)) {
			if(($end = strripos($path, '/')) > 0) {
				$end_path = $root_dir . substr($path, 0, $end);
				if(!is_dir($end_path)) {
					mkdir($end_path,0755, true);
				}
			}
		}
		if(file_exists($real_path)) {
			$real_path = substr($real_path, 0, -36) . md5(getTxNo16()) . '.pdf';
		}
		$tcpdf->Output($real_path, 'F');
		$save_path = $real_path;//返回文件保存路径
	}
	if(in_array('S', $output)) {
		$tcpdf->Output($real_path, 'S');
	}
	return substr($real_path, 20);
	#$tcpdf->Output('/data/sftp/test/web/tcpdf/download/aaa.pdf','I');
	#生成pdf文件
	#$tcpdf->Output('/data/sftp/test/web/tcpdf/download/aaa.pdf','F');
}
/**
 * [将Base64图片转换为本地图片并保存]
 * @TIME   2017-04-07
 * @param  [Base64] $base64_image_content [要保存的Base64]
 * @param  [目录] $path [要保存的路径]
 */
function set_base64_image($base64_image_content, $uid){
	$new_file = dirname(BASEPATH)."/contracts/seal/";
	if(!file_exists($new_file)){
		mkdir($new_file, 0755);
	}
	$new_file = $new_file. (string)$uid . time(). getTxNo16(). ".jpg";
	//if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))){
	if(file_put_contents($new_file, base64_decode($base64_image_content))){
		return substr($new_file, (0 - 46 - strlen($uid)));
	} else {
		return false;
	}
}

/** 获取PDF文件的页数*/
function count_pdf_pages($pdfname) {
    $pdftext = file_get_contents($pdfname);
    $num = preg_match_all("/\/Page\W/", $pdftext, $dummy);
    return $num;
}

/** 记录操作日志 
 *  使用：record_adminlog($this->router->fetch_class(), $this->router->fetch_method(), 11, '22');
 */
function record_adminlog($controller, $method, $bid, $info) {
	$data['bid']		= $bid;
	$data['controller'] = $controller;
	$data['method'] 	= $method;
	$data['info']		= $info;
	$data['addtime']	= time();
	$data['adminid']	= UID;
	$CI =& get_instance();
	$CI->load->model('adminlog/adminlog_model');
	$CI->adminlog_model->add_adminlog($data);
}

/*API 二维数组过滤*/
function array_filters($selet,$arr){
	if(empty($arr)){
	    return $arr;
		}
    $data = array();
    $tem = array();
    foreach($arr as $val){
        foreach($val as $key=>$vval){
             if(!in_array($key,$selet)){
                continue;
             }else{
                 $tem[$key] =  $vval;
             }
        }
       $data[] = $tem;
    }
    return $data;
}
/*API 数组过滤*/
function array_filter_one($selet,$arr){
    if(empty($arr) || empty($selet)){
        return $arr;
    }
    $tem = [];
    foreach($arr as $key =>$val){
        if(in_array($key,$selet)){
            $tem[$key] =$val;
        }
    }
    return $tem;
}
// 记录同步返回的数据到log中
function write_log($word) {
	$dirpath = dirname(BASEPATH);
	$path = $dirpath . '/App/logs/'.date('Y-m-d').'.log';
	$fp = fopen($path,"a");
	flock($fp, LOCK_EX) ;
	fwrite($fp,"回写信息：".strftime("%Y%m%d%H%M%S",time())."\n".$word."\n");
	flock($fp, LOCK_UN);
	fclose($fp);
}