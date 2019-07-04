<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Paytest extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->helper('common');
		$this->load->model(array('test/Pay_model', 'account/info_model', 'member/member_model', 'recharge/recharge_model'));
	}
	//首页
	public function index(){
		$word = file_get_contents("php://input");
		$dirpath = dirname(BASEPATH);
		$path = $dirpath . '/App/logs/'.date('Y-m-d').'.log';
		$fp = fopen($path,"a");
		flock($fp, LOCK_EX) ;
		fwrite($fp,"回写信息：".strftime("%Y%m%d%H%M%S",time())."\n".$word."\n");
		flock($fp, LOCK_UN);
		fclose($fp);
		if(empty($word)){
			exit('FALSE');
		}
		//处理数据
		$this->load->model(array('paytest/paytest_model'));
		$this->paytest_model->excute($word); 
		die;
		$data = $this->input->post_get();
		$this->pay_model->add(serialize($data));
	}
	// public function index(){
		////$word = $this->input->post_get();
		// $word = file_get_contents("php://input");
		////$word = json_encode($word);
		// $fp = fopen("/data/sftp/jmupc/web/log.txt","a");
		// flock($fp, LOCK_EX) ;
		// fwrite($fp,"回写信息：".strftime("%Y%m%d%H%M%S",time())."\n".$word."\n");
		// flock($fp, LOCK_UN);
		// fclose($fp);
		// if(empty($word)){
			// exit('FALSE');
		// }
		////处理数据
		// $this->load->model(array('paytest/paytest_model'));
		// $this->paytest_model->excute($word); 
		// die;
		// $data = $this->input->post_get();
		// $this->pay_model->add(serialize($data));
	// }
	// 记录同步返回的数据到log中
	public function write_log($word) {
		$dirpath = dirname(BASEPATH);
		$path = $dirpath . '/App/logs/'.date('Y-m-d').'.log';
		$fp = fopen($path,"a");
		flock($fp, LOCK_EX) ;
		fwrite($fp,"回写信息：".strftime("%Y%m%d%H%M%S",time())."\n".$word."\n");
		flock($fp, LOCK_UN);
		fclose($fp);
	}
	//网关操作异步回调
	public function merNotifyUrl() {
		$word = file_get_contents("php://input");
		$dirpath = dirname(BASEPATH);
		$path = $dirpath . '/App/logs/'.date('Y-m-d').'.log';
		$fp = fopen($path,"a");
		flock($fp, LOCK_EX) ;
		fwrite($fp,"回写信息：".strftime("%Y%m%d%H%M%S",time())."\n".$word."\n");
		flock($fp, LOCK_UN);
		fclose($fp);
		if(empty($word)){
			exit('FALSE');
		}
		//处理数据
		$this->load->model(array('paytest/paytest_model'));
		$ret = $this->paytest_model->excute($word);
		if($ret) {
			echo '000000';
		}
	}
	
	//快捷充值异步通知
	public function modify() {
		$word = file_get_contents("php://input");
		$dirpath = dirname(BASEPATH);
		$path = $dirpath . '/App/logs/'.date('Y-m-d').'.log';
		$fp = fopen($path,"a");
		flock($fp, LOCK_EX) ;
		fwrite($fp,"回写信息：".strftime("%Y%m%d%H%M%S",time())."\n".$word."\n");
		flock($fp, LOCK_UN);
		fclose($fp);
		
		if(empty($word)){
			exit('FALSE');
		}
		//处理数据
		$this->load->model(array('paytest/paytest_model'));
		$ret = $this->paytest_model->excute($word, true); 
		
		//$res = response('000000');
		if($ret) {
			echo '000000';
		}
	}
	//网关操作异步提现通知（T+0，T+1）
	public function withdraw() {
		$word = file_get_contents("php://input");
		$dirpath = dirname(BASEPATH);
		$path = $dirpath . '/App/logs/'.date('Y-m-d').'.log';
		$fp = fopen($path,"a");
		flock($fp, LOCK_EX) ;
		fwrite($fp,"回写信息：".strftime("%Y%m%d%H%M%S",time())."\n".$word."\n");
		flock($fp, LOCK_UN);
		fclose($fp);
		if(empty($word)){
			exit('FALSE');
		}
		//处理数据
		$this->load->model(array('paytest/paytest_model'));
		$ret = $this->paytest_model->excute($word, true); 
		
		//$res = response('000000');
		if($ret) {
			echo '000000';
		}
	}
	
	// public function read(){
		// $a = "{\"body\":{\"acctNo\":\"1018001072901\",\"bankMobile\":\"15021180031\",\"bankName\":\"中信银行\",\"cardNo\":\"621768****8526\",\"certNo\":\"411024198410223253\",\"custName\":\"孙开卫\",\"custNo\":\"110181808080010729\",\"registerPhone\":\"13213375861\"},\"head\":{\"bizFlow\":\"008201808081353091126\",\"merOrderNo\":\"214120491029430198\",\"merchantNo\":\"131010000011018\",\"respCode\":\"000000\",\"respDesc\":\"成功\",\"tradeCode\":\"CG1044\",\"tradeDate\":\"20180808\",\"tradeTime\":\"135603\",\"tradeType\":\"01\",\"version\":\"1.0.0\"}}";
		// $b = json_decode($a, true);
		// print_r($b);
		////parse_str($a, $data);
	// }
	
	/** 读取返回数据 */
	public function reader() {
		$str = "merchantNo=131010000011013&merOrderNo=19021010009340459170&jsonEnc=02bf4176db45f1670af0af3e180be98352381ad17bf781f7feeab21b053b74da2f2088b325fbf93b20412559df3d29a72d40bdac98baf27f1954cd43d5d4e2624db3c5ff9620014701caf5dac42caebb85b7483766c82e7a8eb8e2c6fbd6e3c82a4a316f4dcfc1caa566b634fdc394cd1cd1cc43f1e2aad470d717a26232b02bf9f56399c932ce81a521caadad4412eb22ec1848a587c4c5200af0bf09541d143c932d4ebb2f830a68aebd74856a3a29e5afbf43fe93fc5912ce4a7692668c40b1b19559aeb18d13b2cf0ceb24835146fe5213a3cbe966e704ce2198486d0c4adf9b2df3344b3ae5313f92aaf901d17cd272f68f55d974ed1996e60033026bda67863546e3ff3fae5089c7931eedf0424e873ae424cbaabc62019f62b69f00c38572b8dc5ca23e1c1c509abb3585386e7a2d277a4d768f8f90ef1ddcfa3e3c53&keyEnc=7fb090864d1f7495cdecc374fca6e580da58c1f97ac27e83bdc265b8aefecafee5e715bd8680b1eba00e5c3da680b46a4fbc9ab6fec6b72192b86df0e35b9fdde3f54d71c646001d91110f532c73973cb5ef37deb6818eadb456bc7360b41bbd8e379e81d23a6e084e5094861d3c069abed30fdff4a97c0f19dc614dd202bf49b0804ddc9fc91da1016a7044016ed6ee12f3b2d24cce2d63a1c28a4b6434fff4bacc9900b4cfc6e9ed5957eadc86c5f95926335731675e4449008ea1bdf4e79ee7c47ae133e32b6fd1d280b6c41ca4f0d4fd7b6a66091ecc98c7e82129a2eb1e8196c816965e1f9144ae7297b45a52b22336fb06dca52a48dca3e7777e043520&sign=18175f48228f8d3f0e1b4195b2702aea062e2dd4b7335ffdd8e063d33e997933302bb778f19f0567e5d0cf803d7a30747005bd7f9c408bfd6488933f1ea7fe9395ea6f4fc6d5b9e457fdcc1131100bebc206240f4a4507649ed8b0c3a78c1387494c1d8277335c133ec7c66ace4fa813d65a556a43aea6a4d4a3fd001065cd53d286cb0955098cd216c851a20a1b96195699b8e49bfa1f91898e542551c1e6fb477857978510b80d535f3c2cf6f1cdaa346f1eeef82f6dc2f4b5a5374df47cab5877443995b0911acb96ca3c21188f52a8e5cc6edaa29cc16b8bc5f1a8370a96f21f7d4841d168216a4dee5111ab5ba35997ff1f9fcf1a6de7cf87a9e5a4db85";
		
		parse_str($str, $arr);
		$params['key'] = $arr['keyEnc'];
		$params['bodys'] = $arr['jsonEnc'];
		$params['sign'] = $arr['sign'];
		
		p($params);
		$data = decrypt($params);
		var_dump($data);
		$data = json_decode($data, true);
		$data['value']['body'] = json_decode($data['value']['body'], true);
		var_dump($data);
		//p($data['value']['body']);
		
	} 
	
	/** 读取异步返回数据 */
	/*public function asynic() {
		$str = '{"jsonEnc":"39526dbb2d9e062900663b64bb1e2d8c99382c3e3d680a3357baa330470ee0b4d30e9163f7b6c9b7b95840b1df7e4e30d918988237897282ea9ffa268a3f520ac44bcda479bb6824e66e3d1b3c678930796ccfce4cff4a555116fb1aab22337f0ef8da2688240aff26220b2d708c54b5e214d961b9f94ab7f0f077bf3e55b0e91a2f65d85245d6f7d002137036da4f22acd2a5b17ee8a795a5849a64c33ce416894f430bf72539785c539940b905df226ee318ce169f8a887fb9fa07c989364746ad5ad7dd2488ad414ab66b2ffe44d6329b9c811862cf454ad007629f5f81fee7f4cf17542880644de242c04993010c0280a659685dfdef2b90ea2d75fbf376743602563f027ea92f6f5e62821dbee494f99083b9e1fdde8b81d85b1c1aee28dd631557d772b9281eae4684bfc2af627d1a22384ca6b75d324c67155724ba5ca33c7ff8f67973b02c3f057d35e076844885ce967de192e70969f3e66bb20e16faca8eaf26cc8b8804b7ba55ab97f90f2e96f5566d120cd808fc081421bdac690443787640daedf10df0ad4f336b82b4","keyEnc":"5b3d492f5dfd4a11047e5a0584daf612d27f191eeb70cabb27fd83e6407efdd017dafae778d87bdc402a3f39b94ca2b249946d772e95d709cf0130c322d53003527e67c9f30a0ca9251cdbaaf3d4b90cb001282645383bc5a0ea3328979a9a28b253903390e5f135312292448e608b119dc30ef911c8df9c6d511b6f3a7619d70e76af10a97c0e9a1109b7488674068b590f0e9c3e7f795be1a54093f6952670a68bc907185854b91609ea8b34a15fc9c47e3ea7c22b5930a5937b5f0ff2bbcdddabe9541b6ea329b4ded66c5cb1095bd46bc39ec7c47cb2f1b3ca61db430a142f2fa6cf438204684d3bb80d2ddedde0d6fc1d3d853da8e55018f65ca394624c","merOrderNo":"18091814339331588170","merchantNo":"131010000011018","sign":"00904e8904f4c2214afe24ef449db33ae600fb7e9da00c40253488b60c041465a97457a2c642be8f3cbce8b69a1ae71f18112e28422331e43c657318e1b073382021f2da13a9538a36f44c7568eb8abd30723c957ab4974da10de829668302b0a566a0aa31b0907f1df25e34982f8d3440c115eab6a87822e6bcad5b95d5f4e64d74dee75d46a7eee1972239697a5dc494692c6c73cdd7795bd95e9dbce05ff7b67325ca060ebe95a4d90511a336daab6a6f976d3ae842d0e36696aa1993ab700edc488e5b89e6c03e1a29b56f2612b678fb48fcda5defb75d6da577fe5ade1b6260bdb64f1c80e022ed46a63321fcdbbb3b58251eb0e84a0633b13e5b307b6b"}';
		
		$arr = json_decode($str, true);
		//解密数据
		$params['key'] = $arr['keyEnc'];
		$params['bodys'] = $arr['jsonEnc'];
		$params['sign'] = $arr['sign'];
		$data = decrypt($params);
		//转成数组
		$data = json_decode($data, true);
		$data['value']['body'] = json_decode($data['value']['body'], true);
		p($data);
	}*/
	
	/** 测试绑卡 */
	// public function bindcard() {
		// $this->load->model(array('paytest/paytest_model'));
		// $this->paytest_model->bindcard();
	// }
	/** 测试变更绑定信息 */
	// public function upbind() {
		// $this->load->model(array('paytest/paytest_model'));
		// $this->paytest_model->upbind();
	// }
	/** 测试网关充值 */
	// public function recharge_wg() {
		// $this->load->model(array('paytest/paytest_model'));
		// $this->paytest_model->recharge_wg();
	// }
	/** 快捷充值 */
	// public function recharge_q() {
		// $this->load->model(array('paytest/paytest_model'));
		// $this->paytest_model->recharge_q();
	// }
	/** 快捷充值(同步) */
	// public function recharge_q() {
		// $this->load->model(array('paytest/paytest_model'));
		// $this->paytest_model->recharge_q(array(), false);
	// }
	/** 页面提现(同步) */
	// public function withdraw_tx() {
		// $this->load->model(array('paytest/paytest_model'));
		// $this->paytest_model->withdraw_tx(array(), false);
	// }
	
	// public function test() {
		// $res = response('000000');
		// echo $res;
	// }
	
	// public function invest() {
		// $aaaaa = 'a:1:{s:5:"value";a:2:{s:5:"signs";s:4:"true";s:4:"body";a:2:{s:4:"body";a:2:{s:6:"acctNo";s:13:"1018001073101";s:15:"subjectAuthCode";s:21:"008201809201148110388";}s:4:"head";a:10:{s:7:"bizFlow";s:21:"008201809201148000387";s:10:"merOrderNo";s:20:"18092011483185212287";s:10:"merchantNo";s:15:"131010000011018";s:8:"respCode";s:6:"000000";s:8:"respDesc";s:6:"成功";s:9:"tradeCode";s:6:"CG1052";s:9:"tradeDate";s:8:"20180920";s:9:"tradeTime";s:6:"114811";s:9:"tradeType";s:2:"01";s:7:"version";s:5:"1.0.0";}}}}';
		// $data = unserialize($aaaaa);
		// $this->load->model(array('paytest/paytest_model'));
		// $this->paytest_model->invest($data['value']['body'], false);
	// }
	/** 放款后处理数据 */
	// public function putmoney() {
		// if($this->db->trans_status() === TRUE){
			// $this->db->trans_commit();
			// $info['state'] = 1;
			// $info['message'] = '放款成功!';
			// $info['url'] = '/borrow.html';
			// $this->output
				// ->set_content_type('application/json', 'utf-8')
				// ->set_output(json_encode($info))
				// ->_display();
				// exit;
		// }else{
			// $this->db->trans_rollback();
			// $info['state'] = 0;
			// $info['message'] = '放款失败,刷新后重试!';
			// $this->output
				// ->set_content_type('application/json', 'utf-8')
				// ->set_output(json_encode($info))
				// ->_display();
				// exit;
		// }
	// }
	// public function log_record() {
		// log_record('aaaa', '', 'CG1001');
	// }
	
	/** 还款测试 */
	// public function repayment() {
		// $arr = array(
			// 'body' => array(
				// 'list' => array(
					// '0' => array(
						// 'bizFlow' => '003201809211943066845',
						// 'payerAcctNo' => '1018001073101',
						// 'resultCode' => '000000',
						// 'subjectAuthCode' => '008201809211128290573',
					// )
				// )
			// ),
			// 'head' => array(
				// 'bizFlow' => '003201809211943066842',
				// 'merOrderNo' => '18092119435133969177',
				// 'merchantNo' => '131010000011018',
				// 'respCode' => '000000',
				// 'respDesc' => '成功',
				// 'tradeCode' => 'CG1053',
				// 'tradeDate' => '20180921',
				// 'tradeTime' => '194306',
				// 'tradeType' => '01',
				// 'version' => '1.0.0',
			// )
		// );
		// $this->load->model(array('paytest/paytest_model'));
		// $this->paytest_model->repayment($arr);
	// }
	
	/** 查询数据 */
	// public function log_get() {
		// $nid = '18092416310937721000';
		// $res = $this->member_model->get_moneylog_bynid($nid);
		// p($res);
	// }
	// public function test_phone() {
		////$mem_body = $data['value']['body'];
		// $phone = '15221341325';
		// $custNo = '110181808080010731';
		// $this->load->model(array('member/member_model'));
		// $count = $this->member_model->get_member_phone_num($phone);
		// if($count < 1) {
			// $meminfo = $this->member_model->get_member_info_bycustno($custNo);
			// $data_mem['member'] = array(
				// 'id'	=> $meminfo['uid'],
				// 'user_name' => $phone
			// );
			// $data_mem['meminfo'] = array(
				// 'uid' => $meminfo['uid'],
				// 'phone' => $phone
			// );
			// $this->member_model->change_phone($data_mem);
		// }
	// }
	
	/** 测试模版解析方法 */
	// public function test_build() {
		// contract_build();
	// }
	/** 测试保全接口 */
	/*public function test_bq() {
		//合同保全服务
		$merOrderNo = '18102410162889765728';
		$this->load->library('EsignAPI/EsignAPI'); 
		$this->load->model(array('contract/contract_model'));
		$EsignAPI = new EsignAPI();
		//根据订单号查询签署记录ID
		$contract = $this->contract_model->get_water_bynid($merOrderNo);
		$res_esign = $EsignAPI->saveWitnessGuide($contract['signServiceId']);
		if(empty($res_esign['errCode'])) {
			$contract_pdf = $this->contract_model->get_contract_pdf_bynid($merOrderNo);
			$contract_pdf['status'] = 1;
			$this->contract_model->modify_contract_pdf($contract_pdf);
		}
	}*/
	/** 测试member_model的record方法*/
	// public function test_member_model_record() {
		// $water['uid'] = 4;
		// $meminfo = $this->member_model->get_member_info_byuid($water['uid']);
		// $params['acctNo'] = $meminfo['acctNo'];
		// $head = head($params, 'CG2001', 'over');
		// unset($head['callbackUrl'], $head['registerPhone'], $head['responsePath'], $head['url']);

		// $data = $head;
		// $data = json_encode($data);
		// $url = $this->config->item('Interface').'2001';
		/////请求接口
		// $str = post_curl_test($url, $data);
		// $this->load->model(array('paytest/paytest_model'));
		// $tmp_body = $this->paytest_model->excute($str);
		
		
		// $str = 'a:4:{s:5:"money";a:1:{s:13:"account_money";N;}s:3:"log";a:16:{s:3:"uid";s:1:"4";s:4:"type";s:2:"10";s:12:"affect_money";s:6:"100.00";s:13:"account_money";s:8:"27311.00";s:13:"collect_money";s:7:"2720.00";s:12:"freeze_money";s:5:"41.76";s:4:"info";s:51:"陈方杰从账户中提现100.00元，提现成功";s:8:"add_time";s:10:"1540534966";s:6:"add_ip";s:0:"";s:3:"bid";s:1:"0";s:9:"actualAmt";s:5:"27311";s:10:"pledgedAmt";s:1:"0";s:9:"preLicAmt";s:4:"9897";s:8:"totalAmt";s:5:"37208";s:6:"acctNo";s:13:"1018001073101";s:3:"nid";s:20:"18102614221897254764";}s:9:"payonline";a:9:{s:3:"uid";s:1:"4";s:3:"nid";s:20:"18102614221897254764";s:5:"money";s:6:"100.00";s:3:"way";s:0:"";s:4:"bank";s:14:"621226****0764";s:6:"status";i:1;s:8:"add_time";i:1540538581;s:6:"remark";s:18:"中国工商银行";s:4:"type";i:2;}s:5:"water";a:1:{s:6:"status";i:1;}}';
		// $data = unserialize($str);
		// echo $tmp_body['body']['actualAmt'];die;
		// $data['money']['account_money'] = $tmp_body['body']['actualAmt'];
		// log_record(serialize($data));
		// $this->load->model(array('member/member_model', 'water/water_model'));
		// $water = $this->water_model->get_water_byorder($data['log']['nid']);
		
		// $this->member_model->recharge($data, $water['uid'], $water['merOrderNo']);
	// }
	
	// public function test_cg2001() {
		// $uid = 4;
		// $meminfo = $this->member_model->get_member_info_byuid($uid);
		// $params['acctNo'] = $meminfo['acctNo'];
		//echo $meminfo['custNo'];
		// $head = head($params, 'CG2001', 'over');
		// unset($head['callbackUrl'], $head['registerPhone'], $head['responsePath'], $head['url']);

		// $data = $head;
		// $data = json_encode($data);
		//var_dump($data);die;
		// $url = $this->config->item('Interface').'2001';
		////var_dump($url);die;
		////请求接口
		// $str = post_curl_test($url, $data);
		// $this->load->model(array('paytest/paytest_model'));
		// $tmp_body = $this->paytest_model->excute($str);
		// print_r($tmp_body);
	// }
	
	/*
	public function test_tx() {
		
		$water['uid'] = 4;
		$meminfo = $this->member_model->get_member_info_byuid($water['uid']);
		$params['acctNo'] = $meminfo['acctNo'];
		$head = head($params, 'CG2001', 'over');
		unset($head['callbackUrl'], $head['registerPhone'], $head['responsePath'], $head['url']);

		$data = $head;
		$data = json_encode($data);
		$url = $this->config->item('Interface').'2001';
		// 请求接口
		$str = post_curl_test($url, $data);
		$this->load->model(array('paytest/paytest_model'));
		$tmp_body = $this->paytest_model->excute($str);
		// p($tmp_body);
		echo round($tmp_body['body']['actualAmt'], 2);die;
		$data = array();
		$data['money']['account_money'] = round($tmp_body['body']['actualAmt'], 2);
		// $data['money']['money_freeze'] = $members_money['money_freeze'] - $money;
		// 回写表members_money_log
		$money_log = $this->member_model->get_moneylog_bynid($merOrderNo);
		if(empty($money_log)) {
			log_record('提现记录表(members_moneylog)出错,请手动处理.订单号为:'.$merOrderNo, '', $water['uid'].'-22');
			return false;
		}
		$data['log'] = $money_log;
		$data['log']['uid'] = $water['uid'];
		$data['log']['type'] = 10;
		$data['log']['affect_money'] = $money;
		$data['log']['account_money'] = $data['money']['account_money'];
		$data['log']['collect_money'] = $members_money['money_collect'];
		$data['log']['freeze_money'] = $members_money['money_freeze'] - $money;
		$in_cust = $this->member_model->get_member_info_byuid($water['uid']);
		$data['log']['info'] = $in_cust['real_name'] . "从账户中提现" . $data['log']['affect_money'] . "元，提现成功";
		// $data['log']['add_time'] = time();
		// $data['log']['actualAmt'] = $body['body']['actualAmt'];
		// $data['log']['pledgedAmt'] = $body['body']['pledgedAmt'];
		// $data['log']['preLicAmt'] = $body['body']['preLicAmt'];
		// $data['log']['totalAmt'] = $body['body']['totalAmt'];
		// $data['log']['acctNo'] = $body['body']['acctNo'];
		// $data['log']['nid'] = $merOrderNo;
		// 回写表members_payonline
		$payonline = $this->member_model->get_quick_bank($water['uid']);
		$data['payonline']['uid'] = $water['uid'];
		$data['payonline']['nid'] = $merOrderNo;
		$data['payonline']['money'] = $data['log']['affect_money'];
		$data['payonline']['way'] = '';
		
		$data['payonline']['bank'] = $payonline['paycard'];
		$data['payonline']['status'] = 1;
		$data['payonline']['add_time'] = time();
		$data['payonline']['remark'] = $payonline['bank_address'];
		$data['payonline']['type'] = 2;//提现
		// 修改流水表状态为1
		$data['water']['status'] = 1;
		log_record(serialize($data));
		if(!$this->member_model->recharge($data, $water['uid'], $merOrderNo)) {
			log_record(serialize($data), '', $water['uid'].'-6');
		}
		
	}*/
	public function testeee() {
		$this->load->model(array('paytest/paytest_model'));
		$this->paytest_model->testeee();
	}
}