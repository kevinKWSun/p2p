<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Paytest_model extends CI_Model{
    public function __construct() {
        parent::__construct();
        //$this->load->database();
		$this->load->model(array('water/water_model'));
    }
	//回写通用处理方法
	/** 
	* 回调数据处理 
	* $str 解析字符串
	* $async 是否是异步
	*/
	public function excute($str, $async = false) {
		if(substr($str, 0, 1) == '{') {//页面返回数据
			$arr = json_decode($str, true);
		} else {//实时返回数据
			parse_str($str, $arr);
		}
		//解密数据
		if(!isset($arr['keyEnc'])) {
			log_record('请求异步接口出错 /paytest_model[keyEnc]');
			if(substr($str, 0, 1) != '{') {
				exit('请求异步接口出错keyEnc');
			}
		}
		$params['key'] = $arr['keyEnc'];
		
		$params['bodys'] = $arr['jsonEnc'];
		$params['sign'] = $arr['sign'];

		$data = decrypt($params);
		//转成数组
		$data = json_decode($data, true);
		$data['value']['body'] = json_decode($data['value']['body'], true);
		//返回信息入库
		if(!empty($data['value']['body']['head']['merOrderNo'])) {
			$water = $this->water_model->get_water_byorder($data['value']['body']['head']['merOrderNo']);
		}
		if(!isset($water['status']) || $water['status'] === '0') {
			$this->update($data);
		}
		
		//业务处理
		$signs = isset($data['value']['signs']) ? $data['value']['signs'] : '';
		$body = isset($data['value']['body']['body']) ? $data['value']['body']['body'] : '';
		$head = $data['value']['body']['head'];
		
		switch($head['tradeCode']) {
			case 'CG1044': //开户绑卡
				//银行注册手机号匹配用户ID，
				if($head['respCode'] === '000000') {
					$this->bindcard($data['value']['body']);
				}
				break;
			case 'CG1056'://更新绑定信息
				if($head['respCode'] === '000000') {
					$this->upbind($data['value']['body']);
				}
				break;
				
			case 'CG1045'://快捷充值
				if($async) {
					return $this->recharge_q($data['value']['body'], $async);
				} else {
					$this->recharge_q($data['value']['body'], $async);
				}
				break;
			case 'CG1046'://T+0提现
				if($async) {
					return $this->withdraw_tx($data['value']['body'], $async);
				} else {
					$this->withdraw_tx($data['value']['body'], $async);
				}
				break;
			case 'CG1047'://T+0提现
				$this->withdraw_tx1($data['value']['body']);
				break;
			case 'CG1019'://网关充值
				if(isset($body['resultStatus'])) {
					$this->recharge_wg($data['value']['body']);
					if($body['resultStatus'] === '000' || $body['resultStatus'] === '001') {
						return true;
					} else {
						return false;
					}
				}
				return false;
				break;
			case 'CG1052': //标的购买
				$this->invest($data['value']['body']);
				
				break;
			case 'CG1021': //放款
				return $data['value']['body'];
				break;
			case 'CG2001': //余额查询
				return $data['value']['body'];
				break;
			case 'CG1050': //还款转账获取授权
				$water = $this->water_model->get_water_byorder($data['value']['body']['head']['merOrderNo']);
				if($body['head']['respCode'] == '000000') {
					$data_authorize = array(
						'uid'	=> $water['uid'],
						'custNo' => $body['custNo'],
						'authType' =>  $body['authType'],
						'expiryTime' =>  $body['expiryTime'],
						'amount' =>  $body['amount'],
						'addtime' => time()
					);
					$this->load->model(array('borrow/borrow_model'));
					$this->borrow_model->add_authorize($data_authorize);
				}
				break;
			case 'CG1010': //还款转账
				return $data['value']['body'];
				break;
			case 'CG1053': //还款转账
				$this->repayment($data['value']['body']);
				break;
			case 'CG1032': //标的结束
				return $data['value']['body'];
				break;
			case 'CG1008': //红包
				return $data['value']['body'];
				break;
			case 'CG1049': //修改注册手机号
				$mem_body = $data['value']['body'];
				$phone = $mem_body['body']['newMobile'];
				$custNo = $mem_body['body']['custNo'];
				$this->load->model(array('member/member_model'));
				$count = $this->member_model->get_member_phone_num($phone);
				if($count < 1) {
					$meminfo = $this->member_model->get_member_info_bycustno($custNo);
					$data_mem['member'] = array(
						'id'	=> $meminfo['uid'],
						'user_name' => $phone
					);
					$data_mem['meminfo'] = array(
						'uid' => $meminfo['uid'],
						'phone' => $phone
					);
					$this->member_model->change_phone($data_mem);
				}
				break;
			case 'CG1051': //支付密码重置
				if($head['respCode'] === '000000') {
					$water = $this->water_model->get_water_byorder($data['value']['body']['head']['merOrderNo']);
					$meminfo = $this->member_model->get_member_info_byuid($water['uid']);
					$meminfo['is_cancel'] = 1;
					$this->member_model->up_members_info($meminfo, $meminfo['uid']);
				}
				break;
			case 'CG1055': //支付密码重置
				break;
			case 'CG1048': //修改交易密码
				break;
			
			//同步
			case 'CG1012': //上标
				return $data['value']['body'];
				break;
			default:
				
		}
	}
	//标的购买
	public function invest($body) {
		$respCode = $body['head']['respCode'];
		if($respCode === '000000') {
			$merOrderNo = $body['head']['merOrderNo'];
			$water = $this->water_model->get_water_byorder($merOrderNo);
			//投资人
			$investor_uid = $water['uid'];
			//标的ID
			$bid = $water['bid'];
			$this->load->model(array('borrow/borrow_model'));
			$info = $this->borrow_model->get_borrow_byid($bid);
			//组织数据
			//第二张表
			//应收利息
			$interest = round($water['money'] * (($info['borrow_interest_rate'] + $info['add_rate']) / 100 / 360) * $this->config->item('borrow_duration')[$info['borrow_duration']],2);
			$data = array();
			$data['investor'] = array(
				'status' 		=> 2,
				'borrow_id'		=> $bid,
				'investor_uid'	=> $investor_uid,
				'borrow_uid'	=> $info['borrow_uid'],
				'investor_capital'=> $water['money'],
				'investor_interest'=> $interest,
				'add_time'		=> time(),
				'acctNo'		=> $body['body']['acctNo'],
				'subjectAuthCode'=> $body['body']['subjectAuthCode'],
				'redid'			=> $water['redid']
			);
			//账户处理
			$members_money = $this->member_model->get_members_money_byuid($water['uid']);
			$data['money'] = array(
				'account_money' => $members_money['account_money'] - $water['money'],
				'money_freeze'	=> $members_money['money_freeze'] + $water['money']
			);
			$in_cust = $this->member_model->get_member_info_byuid($investor_uid);
			//$out_cust = $this->member_model->get_member_info_byuid($info['borrow_uid']);
			$data['log'] = array(
				'uid'			 => $water['uid'],
				'type' 			 => 5,
				'affect_money'	 => $water['money'],
				'account_money'	 => $members_money['account_money'] - $water['money'],
				'collect_money'	 => $members_money['money_collect'],
				'freeze_money'	 => $members_money['money_freeze'] + $water['money'],
				'info'			 => '冻结完成【' . $info['borrow_name'] . '】',
				'add_time'		 => time(),
				'bid'			 => $bid,
				'nid'			 => $merOrderNo
			);
			//借款标的处理
			$data['borrow'] = array(
				'has_borrow' 	=> $info['has_borrow'] + $water['money'],
				'borrow_times'  => $info['borrow_times'] + 1
			);
			//修改流水表状态为1
			$data['water']['status'] = 1;
			//红包处理
			if($water['redid'] > 0) {
				$data['red']['id'] = $water['redid'];
				$data['red']['bid'] = $bid;
				$data['red']['status'] = 1;
			}
			$borrow_investor_id = $this->member_model->invest($data, $investor_uid, $merOrderNo, $bid);
			if($borrow_investor_id > 0) {
				//最后一笔放款，要修改状态borrow和borrow_investor
				$info = $this->borrow_model->get_borrow_byid($bid);
				if($info['borrow_money'] <= $info['has_borrow']) {
					$datas['borrow']['borrow_status'] = 3;
					$datas['investor']['status'] = 3;
					$this->member_model->full($datas, $bid);
					
				}
				
				//合同保全服务
				$this->load->library('EsignAPI/EsignAPI'); 
				$this->load->model(array('contract/contract_model'));
				$EsignAPI = new EsignAPI();
				//根据订单号查询签署记录ID
				$contract = $this->contract_model->get_water_bynid($merOrderNo);
				$res_esign = $EsignAPI->saveWitnessGuide($contract['signServiceId']);
				if(empty($res_esign['errCode'])) {
					$contract_pdf = $this->contract_model->get_contract_pdf_bynid($merOrderNo);
					$contract_pdf['status'] = 1;
					$contract_pdf['invest_id'] = $borrow_investor_id;
					$this->contract_model->modify_contract_pdf($contract_pdf);
				}
			}
		}
	}
	/** 回调数据写入数据库 */
	public function update($data) {
		$signs = isset($data['value']['signs']) ? $data['value']['signs'] : '';
		$body = isset($data['value']['body']['body']) ? $data['value']['body']['body'] : '';
		$head = isset($data['value']['body']['head']) ? $data['value']['body']['head'] : '';
		$insert_data = array(
			'signs' 	=> $signs,
			'bizFlow'	=> isset($head['bizFlow']) ? $head['bizFlow'] : '',
			//'merOrderNo'=> isset($head['merOrderNo']) ? $head['merOrderNo'] : '',
			'respCode'	=> isset($head['respCode']) ? $head['respCode'] : '',
			'respDesc'	=> isset($head['respDesc']) ? $head['respDesc'] : '',
			'tradeCode' => isset($head['tradeCode']) ? $head['tradeCode'] : '',
			'serialize' => serialize($data)
			//'addtime'	=> time()
		);
		
		//其他情况处理(网关充值)
		if($insert_data['tradeCode'] === "CG1019") {
			$insert_data['bizFlow'] = isset($body['tradeFlow']) ? $body['tradeFlow'] : '';
			$insert_data['respCode'] = isset($body['resultStatus']) ? $body['resultStatus'] : '';
			$insert_data['respDesc'] = isset($body['resultDesc']) ? $body['resultDesc'] : '';
		}
		//快捷充值(异步返回数据),T+0提现（异步返回数据）
		if($insert_data['tradeCode'] === "CG1045" || $insert_data['tradeCode'] === "CG1046") {
			$insert_data['bizFlow'] = isset($body['bizFlow']) ? $body['bizFlow'] : '';
			$insert_data['respCode'] = isset($body['respCode']) ? $body['respCode'] : '';
			$insert_data['respDesc'] = isset($body['respDesc']) ? $body['respDesc'] : '';
		}
		
		$merOrderNo = isset($head['merOrderNo']) ? $head['merOrderNo'] : '';
		if(!empty($merOrderNo)) {
			//入库操作
			$this->load->model(array('water/water_model'));
			$this->water_model->edit_water($insert_data, $head['merOrderNo']);
		}
		
	}
	
	/** 回写绑卡信息 */
	public function bindcard($body = array()) {
		// $a = "{\"body\":{\"acctNo\":\"1018001072901\",\"bankMobile\":\"15021180031\",\"bankName\":\"中信银行\",\"cardNo\":\"621768****8526\",\"certNo\":\"411024198410223253\",\"custName\":\"孙开卫\",\"custNo\":\"110181808080010729\",\"registerPhone\":\"15221341326\"},\"head\":{\"bizFlow\":\"008201808081353091126\",\"merOrderNo\":\"214120491029430198\",\"merchantNo\":\"131010000011018\",\"respCode\":\"000000\",\"respDesc\":\"成功\",\"tradeCode\":\"CG1044\",\"tradeDate\":\"20180808\",\"tradeTime\":\"135603\",\"tradeType\":\"01\",\"version\":\"1.0.0\"}}";
		// $a = json_decode($a, true);
		// $body = $a;
		// p($body);
		//写入members_quickbank表，要记录body里边的客户号码（custNo），
		//账户号码（acctNo），bankMobile（bankMobile），注册手机号（registerPhone），证件号码（certNo）必填字段，
		$data = array();
		if(empty($body['body'])) {
			return false;
		}
		$this->load->model(array('member/member_model'));
		$meminfo = $this->member_model->get_member_info_byphone($body['body']['registerPhone']);
		var_dump($meminfo);
		if(empty($meminfo['uid'])) {
			return false;
		}
		$data['quick'] = array(
			'uid' 		=> $meminfo['uid'],
			'tel' 		=> $body['body']['bankMobile'],
			'paycard'	=> $body['body']['cardNo'],
			'number'	=> $body['head']['bizFlow'],
			'real_name'	=> $body['body']['custName'],
			'card'		=> $body['body']['certNo'],
			'paystatus'	=> 1,
			'bank_address'=> $body['body']['bankName'],
			'addtime'	=> time()
		);
		$data['meminfo'] = array(
			'idcard' 	=> $body['body']['certNo'],
			'real_name'	=> $body['body']['custName'],
			'custNo'	=> $body['body']['custNo'],
			'acctNo'	=> $body['body']['acctNo']
		);
		$data['memstatus'] = array(
			'id_status' 	=> 1,
			'phone_status'	=> 1
		);
		$this->member_model->update_members_bindcard($data);
	}
	
	/** 回写重新绑定信息 */ 
	public function upbind($body = array()) {
		$merOrderNo = $body['head']['merOrderNo'];
		$water = $this->water_model->get_water_byorder($merOrderNo);
		//$meminfo = $this->member_model->get_member_info_bycust('110181808080010731');
		$data['quick'] = $this->member_model->get_quick_bank($water['uid']);
		if(empty($data['quick'])) {
			return false;
		}
		
		$data['bak'] = array(
			'uid' 		=> $data['quick']['uid'],
			'tel' 		=> $data['quick']['tel'],
			'paycard'	=> $data['quick']['paycard'],
			'number'	=> $data['quick']['number'],
			'bank_address'=> $data['quick']['bank_address'],
			//'addtime'	=> $data['quick']['addtime'],
			'uptime'	=> time()
		);
		$data['quick']['tel'] = $body['body']['bankMobile'];
		$data['quick']['paycard'] = $body['body']['cardNo'];
		$data['quick']['bank_address'] = $body['body']['bankName'];
		$data['quick']['number'] = $body['head']['bizFlow'];
		$this->member_model->update_upbind($data);
	}
	
	/** 回写页面充值记录 */
	public function recharge_q($body = array(), $async) {
		//先调取water表记录信息，查看状态，如果状态是000000，修改充值状态, 其他不做处理
		$merOrderNo = $body['head']['merOrderNo'];
		$water = $this->water_model->get_water_byorder($merOrderNo);
		if($water['status'] > 0) {
			return true;
		}
		if(empty($water['uid'])) {
			log_record('充值记录表(members_moneylog)出错,请手动处理.订单号为:'.$merOrderNo, '', $water['uid'].'-40');
		}
 		//$money_cz = $water['money'];
		$this->load->model(array('member/member_model'));
		$data = array();
		if($async) {//异步并且未处理过, 需要用事务来锁定数据
			//异步因为有并发，所有需要由事务来锁定数据
			//$this->load->database();
			//$this->db->trans_begin();
			//充值成功
			if($body['body']['respCode'] === '000000') {
				//回写表members_money
				$members_money = $this->member_model->get_members_money_byuid($water['uid']);
				$money = round(($body['body']['actualAmt'] - $members_money['account_money']), 2);//round(floatval($water['money']), 2);
				$data['money']['account_money'] = $members_money['account_money'] + $money;
				//回写表members_money_log
				$money_log = $this->member_model->get_moneylog_bynid($merOrderNo);
				if(empty($money_log)) {//等待同步数据先处理，再来处理异步数据
					log_record('充值记录表(members_moneylog)出错,请手动处理.订单号为:'.$merOrderNo, '', $water['uid'].'-20');
					return false;
				}
				$data['log'] = $money_log;
				//$data['log']['uid'] = $water['uid'];
				//$data['log']['type'] = 1;
				//$data['log']['affect_money'] = $money;
				$data['log']['account_money'] = $data['money']['account_money'];
				//$data['log']['collect_money'] = $members_money['money_collect'];
				//$data['log']['freeze_money'] = $members_money['money_freeze'];
				//$in_cust = $this->member_model->get_member_info_byuid($water['uid']);
				$data['log']['info'] = "充值" . $data['log']['affect_money'] . "元，充值成功";//$in_cust['real_name'] . "向自己账户中充值" . $data['log']['affect_money'] . "元，充值成功";//.",".$body['body']['actualAmt'].",".$members_money['account_money'].",".$data['money']['account_money'];
				//$data['log']['add_time'] = time();
				$data['log']['actualAmt'] = $body['body']['actualAmt'];
				$data['log']['pledgedAmt'] = $body['body']['pledgedAmt'];
				$data['log']['preLicAmt'] = $body['body']['preLicAmt'];
				$data['log']['totalAmt'] = $body['body']['totalAmt'];
				$data['log']['acctNo'] = $body['body']['acctNo'];
				//$data['log']['nid'] = $merOrderNo;
				//回写表members_payonline
				$payonline = $this->member_model->get_quick_bank($water['uid']);
				$data['payonline']['uid'] = $water['uid'];
				$data['payonline']['nid'] = $merOrderNo;
				$data['payonline']['money'] = $data['log']['affect_money'];
				$data['payonline']['way'] = '';
				
				$data['payonline']['bank'] = $payonline['paycard'];
				$data['payonline']['status'] = 1;
				$data['payonline']['add_time'] = time();
				$data['payonline']['remark'] = $payonline['bank_address'];
				$data['payonline']['type'] = 1;//充值
				//修改流水表状态为1
				$data['water']['status'] = 1;
				if(!$this->member_model->recharge($data, $water['uid'], $merOrderNo)) {
					log_record(serialize($data), '', $water['uid'].'-10');
				}
			} else {
				//充值失败
				$members_money = $this->member_model->get_members_money_byuid($water['uid']);
				$money = round(floatval($water['money']), 2);
				//回写表members_money_log
				$money_log = $this->member_model->get_moneylog_bynid($merOrderNo);
				if(empty($money_log)) {
					if($body['body']['respCode'] !== 'EC0035') {//其他错误，记录下来
						log_record('充值记录表(members_moneylog)出错,请手动处理.订单号为:'.$merOrderNo, '', $water['uid'].'-21');
						return false;
					} else {//EC0035收款行处理失败,不需要处理
						return true;
					}
				}
				$data['log'] = $money_log;
				// $data['log']['uid'] = $water['uid'];
				// $data['log']['type'] = 1;
				// $data['log']['affect_money'] = $money;
				// $data['log']['account_money'] = $members_money['account_money'];
				// $data['log']['collect_money'] = $members_money['money_collect'];
				// $data['log']['freeze_money'] = $members_money['money_freeze'];
				//$in_cust = $this->member_model->get_member_info_byuid($water['uid']);
				$data['log']['info'] = "充值" . $money . "元，操作失败";//$in_cust['real_name'] . "向自己账户中充值" . $money . "元，操作失败";
				// $data['log']['add_time'] = time();
				// $data['log']['actualAmt'] = $body['body']['actualAmt'];
				// $data['log']['pledgedAmt'] = $body['body']['pledgedAmt'];
				// $data['log']['preLicAmt'] = $body['body']['preLicAmt'];
				// $data['log']['totalAmt'] = $body['body']['totalAmt'];
				// $data['log']['acctNo'] = $body['body']['acctNo'];
				// $data['log']['nid'] = $merOrderNo;
				//回写表members_payonline
				$payonline = $this->member_model->get_quick_bank($water['uid']);
				$data['payonline']['uid'] = $water['uid'];
				$data['payonline']['nid'] = $merOrderNo;
				$data['payonline']['money'] = $money;
				$data['payonline']['way'] = '';
				$data['payonline']['bank'] = $payonline['paycard'];
				$data['payonline']['status'] = 0;
				$data['payonline']['add_time'] = time();
				$data['payonline']['remark'] = $payonline['bankaddress'];
				$data['payonline']['type'] = 1;//充值
				//修改流水表状态为1
				$data['water']['status'] = 1;
				if(!$this->member_model->recharge($data, $water['uid'], $merOrderNo)) {
					log_record(serialize($data), '', $water['uid'].'-1');
				}
			}
		} else if($water['status'] === '0') {//同步处理,如果成功直接处理,如果异步已处理过，不需要重复操作，
			if($body['head']['respCode'] == '000000') {
				$members_money = $this->member_model->get_members_money_byuid($water['uid']);
				$money = round(floatval($water['money']), 2);//round(($body['body']['actualAmt'] - $members_money['account_money']), 2);
				if(abs($body['body']['actualAmt'] - $members_money['account_money'] - $money) < 0.001 || abs($body['body']['actualAmt'] - $members_money['account_money']) < 0.001) {//说明充值成功，不需要等待异步处理
					$data['money']['account_money'] = $members_money['account_money'] + $money;
					//回写表members_money_log
					//$money_log = $this->member_model->get_moneylog_bynid($merOrderNo);
					$data['log']['uid'] = $water['uid'];
					$data['log']['type'] = 1;
					$data['log']['affect_money'] = $money;
					$data['log']['account_money'] = $data['money']['account_money'];
					$data['log']['collect_money'] = $members_money['money_collect'];
					$data['log']['freeze_money'] = $members_money['money_freeze'];
					//$in_cust = $this->member_model->get_member_info_byuid($water['uid']);
					$data['log']['info'] = "充值" . $money . "元，充值成功";//$in_cust['real_name'] . "向自己账户中充值" . $money . "元，充值成功";//.",".$body['body']['actualAmt'].",".$members_money['account_money'].",".$data['money']['account_money'];
					$data['log']['add_time'] = time();
					$data['log']['actualAmt'] = $body['body']['actualAmt'];
					$data['log']['pledgedAmt'] = $body['body']['pledgedAmt'];
					$data['log']['preLicAmt'] = $body['body']['preLicAmt'];
					$data['log']['totalAmt'] = $body['body']['totalAmt'];
					$data['log']['acctNo'] = $body['body']['acctNo'];
					$data['log']['nid'] = $merOrderNo;
					//回写表members_payonline
					$payonline = $this->member_model->get_quick_bank($water['uid']);
					$data['payonline']['uid'] = $water['uid'];
					$data['payonline']['nid'] = $merOrderNo;
					$data['payonline']['money'] = $money;
					$data['payonline']['way'] = '';
					
					$data['payonline']['bank'] = $payonline['paycard'];
					$data['payonline']['status'] = 1;
					$data['payonline']['add_time'] = time();
					$data['payonline']['remark'] = $payonline['bank_address'];
					$data['payonline']['type'] = 1;//充值
					//修改流水表状态为1
					$data['water']['status'] = 1;
					if(!$this->member_model->recharge($data, $water['uid'], $merOrderNo)) {
						log_record(serialize($data), '', $water['uid'].'-30');
					}
				} else {//等待异步回写数据,接口返回成功，默认交易成功
					log_record('请检查账户表(members_money),如有问题，请手动处理.订单号为:'.$merOrderNo, '', $water['uid'].'-80');
					/*
					//回写表members_money_log
					$data['log']['uid'] = $water['uid'];
					$data['log']['type'] = 1;
					$data['log']['affect_money'] = $money;//一直是处理中的，就等价于操作失败
					$data['log']['account_money'] = $members_money['account_money'];
					$data['log']['collect_money'] = $members_money['money_collect'];
					$data['log']['freeze_money'] = $members_money['money_freeze'];
					//$in_cust = $this->member_model->get_member_info_byuid($water['uid']);
					$data['log']['info'] = "充值" . $money . "元，充值处理中";//$in_cust['real_name'] . "向自己账户中充值" . $money . "元，充值处理中";
					$data['log']['add_time'] = time();
					$data['log']['actualAmt'] = $body['body']['actualAmt'];
					$data['log']['pledgedAmt'] = $body['body']['pledgedAmt'];
					$data['log']['preLicAmt'] = $body['body']['preLicAmt'];
					$data['log']['totalAmt'] = $body['body']['totalAmt'];
					$data['log']['acctNo'] = $body['body']['acctNo'];
					$data['log']['nid'] = $merOrderNo;
					
					if(!$this->member_model->recharge($data, $water['uid'], $merOrderNo)) {
						log_record(serialize($data), '', $water['uid'].'-2');
					}*/
				}
			} else {
				log_record('充值失败.原因'.$body['head']['respDesc'].'订单号为:'.$merOrderNo, '', $water['uid'].'-81');
			}
		}
	}
	/** 回写网关充值记录 */
	public function recharge_wg($body = array()) {
		// 测试数据
		// $test = 'a:1:{s:5:"value";a:2:{s:5:"signs";s:4:"true";s:4:"body";a:2:{s:4:"body";a:9:{s:6:"acctNo";s:13:"1018001073101";s:8:"acctType";s:4:"C001";s:6:"amount";s:4:"1.00";s:8:"bankCode";s:8:"01040000";s:10:"finishTime";s:14:"20180914140254";s:10:"resultCode";s:6:"000000";s:10:"resultDesc";s:6:"成功";s:12:"resultStatus";s:3:"000";s:9:"tradeFlow";s:21:"002201809141402490360";}s:4:"head";a:7:{s:10:"merOrderNo";s:20:"p-mer-20180914140249";s:10:"merchantNo";s:15:"131010000011018";s:9:"tradeCode";s:6:"CG1019";s:9:"tradeDate";s:8:"20180914";s:9:"tradeTime";s:6:"140255";s:9:"tradeType";s:2:"00";s:7:"version";s:5:"1.0.0";}}}}';
		// $aaa = unserialize($test);
		// $body = $aaa['value']['body'];
		//先调取water表记录信息，查看状态，如果状态是000或者001，不做处理，如果是003，修改充值状态
		$merOrderNo = $body['head']['merOrderNo'];
		
		$water = $this->water_model->get_water_byorder($merOrderNo);
		if(!empty($water['respCode'])) {
			$this->load->model(array('member/member_model'));
			$data = array();
			//充值成功
			if($water['respCode'] === '000' && $water['status'] === '0') {
				//回写表members_money
				$members_money = $this->member_model->get_members_money_byuid($water['uid']);
				$money = round(floatval($body['body']['amount']), 2);
				$data['money']['account_money'] = $members_money['account_money'] + $money;
				//回写表members_money_log
				// $money_log = $this->member_model->get_moneylog_bynid($merOrderNo);
				// if(empty($money_log)) {
					// log_record('网关充值记录表(members_moneylog)出错,请手动处理.订单号为:'.$merOrderNo, '', $water['uid'].'-25');
					// return false;
				// }
				// $data['log'] = $money_log;
				$data['log']['uid'] = $water['uid'];
				$data['log']['type'] = 1;
				$data['log']['affect_money'] = $money;
				$data['log']['account_money'] = $data['log']['account_money'] + $money;
				$data['log']['collect_money'] = $members_money['money_collect'];
				$data['log']['freeze_money'] = $members_money['money_freeze'];
				//$in_cust = $this->member_model->get_member_info_byuid($water['uid']);
				$data['log']['info'] = "充值" . $money . "元，操作成功";//$in_cust['real_name'] . "向自己账户中充值" . $money . "元，操作成功";
				$data['log']['add_time'] = time();
				$data['log']['nid'] = $merOrderNo;
				//回写表members_payonline
				$data['payonline']['uid'] = $water['uid'];
				$data['payonline']['nid'] = $merOrderNo;
				$data['payonline']['money'] = $money;
				$data['payonline']['way'] = '';
				$data['payonline']['bank'] = '';
				$data['payonline']['status'] = 1;
				$data['payonline']['add_time'] = time();
				$data['payonline']['remark'] = $this->config->item('bank')[$body['body']['bankCode']];
				$data['payonline']['type'] = 1;//充值
				//修改流水表状态为1
				$data['water']['status'] = 1;
				if(!$this->member_model->recharge($data, $water['uid'], $merOrderNo)) {
					log_record(serialize($data), '', $water['uid'].'-3');
				}
			}
			//充值失败
			if($water['respCode'] === '001' && $water['status'] === '0') {
				$members_money = $this->member_model->get_members_money_byuid($water['uid']);
				$money = round(floatval($body['body']['amount']), 2);
				//回写表members_money_log
				// $money_log = $this->member_model->get_moneylog_bynid($merOrderNo);
				// if(empty($money_log)) {
					// log_record('网关充值记录表(members_moneylog)出错,请手动处理.订单号为:'.$merOrderNo, '', $water['uid'].'-26');
					// return false;
				// }
				// $data['log'] = $money_log;
				$data['log']['uid'] = $water['uid'];
				$data['log']['type'] = 1;
				$data['log']['affect_money'] = 0;
				$data['log']['account_money'] = $data['log']['account_money'];
				$data['log']['collect_money'] = $members_money['money_collect'];
				$data['log']['freeze_money'] = $members_money['money_freeze'];
				//$in_cust = $this->member_model->get_member_info_byuid($water['uid']);
				$data['log']['info'] = "充值" . $money . "元，操作失败";//$in_cust['real_name'] . "向自己账户中充值" . $money . "元，操作失败";
				$data['log']['add_time'] = time();
				$data['log']['nid'] = $merOrderNo;
				//回写表members_payonline
				$data['payonline']['uid'] = $water['uid'];
				$data['payonline']['nid'] = $merOrderNo;
				$data['payonline']['money'] = $money;
				$data['payonline']['way'] = '';
				$data['payonline']['bank'] = '';
				$data['payonline']['status'] = 0;
				$data['payonline']['add_time'] = time();
				$data['payonline']['remark'] = $this->config->item('bank')[$body['body']['bankCode']];
				$data['payonline']['type'] = 1;//充值
				//修改流水表状态为1
				$data['water']['status'] = 1;
				if(!$this->member_model->recharge($data, $water['uid'], $merOrderNo)) {
					log_record(serialize($data), '', $water['uid'].'-4');
				}
			}
			//处理中
			if($water['respCode'] === '002' && $water['status'] === '0') {
				$members_money = $this->member_model->get_members_money_byuid($water['uid']);
				$money = round(floatval($body['body']['amount']), 2);
				//回写表members_money_log
				$data['log']['uid'] = $water['uid'];
				$data['log']['type'] = 1;
				$data['log']['affect_money'] = 0;
				$data['log']['account_money'] = $members_money['account_money'];
				$data['log']['collect_money'] = $members_money['money_collect'];
				$data['log']['freeze_money'] = $members_money['money_freeze'];
				//$in_cust = $this->member_model->get_member_info_byuid($water['uid']);
				$data['log']['info'] = "充值" . $money . "元，操作处理中";//$in_cust['real_name'] . "向自己账户中充值" . $money . "元，操作处理中";
				$data['log']['add_time'] = time();
				$data['log']['nid'] = $merOrderNo;
				if(!$this->member_model->recharge($data, $water['uid'], $merOrderNo)) {
					log_record(serialize($data), '', $water['uid'].'-5');
				}
			}
		}
	}
	/** 回写提现数据(T+0) 
	public function withdraw_tx($body = array(), $async) {
		$merOrderNo = $body['head']['merOrderNo'];
		$water = $this->water_model->get_water_byorder($merOrderNo);
		$this->load->model(array('member/member_model'));
		$data = array();
		if($async) {//异步并且未处理过
			if($water['status']) {
				return true;
			}
			//提现成功
			if($body['body']['respCode'] === '000000') {
				//回写表members_money
				$members_money = $this->member_model->get_members_money_byuid($water['uid']);
				$money = round($members_money['account_money'] - $body['body']['actualAmt'], 2);
				if(($members_money['account_money'] - $body['body']['actualAmt']) < 0.001) {
					return false;
				}
				$data['money']['account_money'] = $members_money['account_money'] - $money;
				//回写表members_money_log
				$data['log']['uid'] = $water['uid'];
				$data['log']['type'] = 10;
				$data['log']['affect_money'] = $money;
				$data['log']['account_money'] = $body['body']['actualAmt'];
				$data['log']['collect_money'] = $members_money['money_collect'];
				$data['log']['freeze_money'] = $members_money['money_freeze'];
				$in_cust = $this->member_model->get_member_info_byuid($water['uid']);
				$data['log']['info'] = $in_cust['real_name'] . "从账户中提现" . $money . "元，操作成功";
				$data['log']['add_time'] = time();
				$data['log']['actualAmt'] = $body['body']['actualAmt'];
				$data['log']['pledgedAmt'] = $body['body']['pledgedAmt'];
				$data['log']['preLicAmt'] = $body['body']['preLicAmt'];
				$data['log']['totalAmt'] = $body['body']['totalAmt'];
				$data['log']['acctNo'] = $body['body']['acctNo'];
				$data['log']['nid'] = $merOrderNo;
				//回写表members_payonline
				$payonline = $this->member_model->get_quick_bank($water['uid']);
				$data['payonline']['uid'] = $water['uid'];
				$data['payonline']['nid'] = $merOrderNo;
				$data['payonline']['money'] = $money;
				$data['payonline']['way'] = '';
				
				$data['payonline']['bank'] = $payonline['paycard'];
				$data['payonline']['status'] = 1;
				$data['payonline']['add_time'] = time();
				$data['payonline']['remark'] = $payonline['bank_address'];
				$data['payonline']['type'] = 2;//提现
				//修改流水表状态为1
				$data['water']['status'] = 1;
				$this->member_model->recharge($data, $water['uid'], $merOrderNo);
			} else {
				//提现失败
				$members_money = $this->member_model->get_members_money_byuid($water['uid']);
				$money = round(floatval($water['money']), 2);
				
				//回写表members_money_log
				$data['log']['uid'] = $water['uid'];
				$data['log']['type'] = 1;
				$data['log']['affect_money'] = $money;
				$data['log']['account_money'] = $members_money['account_money'] + $money;
				$data['log']['collect_money'] = $members_money['money_collect'];
				$data['log']['freeze_money'] = $members_money['money_freeze'] - $money;
				$in_cust = $this->member_model->get_member_info_byuid($water['uid']);
				$data['log']['info'] = $in_cust['real_name'] . "从账户中提现" . $money . "元，操作失败";
				$data['log']['add_time'] = time();
				$data['log']['actualAmt'] = $body['body']['actualAmt'];
				$data['log']['pledgedAmt'] = $body['body']['pledgedAmt'];
				$data['log']['preLicAmt'] = $body['body']['preLicAmt'];
				$data['log']['totalAmt'] = $body['body']['totalAmt'];
				$data['log']['acctNo'] = $body['body']['acctNo'];
				$data['log']['nid'] = $merOrderNo;
				//回写表members_payonline
				$payonline = $this->member_model->get_quick_bank($water['uid']);
				$data['payonline']['uid'] = $water['uid'];
				$data['payonline']['nid'] = $merOrderNo;
				$data['payonline']['money'] = $money;
				$data['payonline']['way'] = '';
				$data['payonline']['bank'] = $payonline['paycard'];
				$data['payonline']['status'] = 0;
				$data['payonline']['add_time'] = time();
				$data['payonline']['remark'] = $payonline['bankaddress'];
				$data['payonline']['type'] = 2;//提现
				//修改流水表状态为1
				$data['water']['status'] = 1;
				$this->member_model->recharge($data, $water['uid'], $merOrderNo);
				
			}
			return true;
		} else if($water['status'] === '0') {//同步只生成moneylog表,如果异步已处理过，不需要重复操作
			$members_money = $this->member_model->get_members_money_byuid($water['uid']);
			$money = round(floatval($water['money']), 2);
			//回写表members_money_log
			$data['log']['uid'] = $water['uid'];
			$data['log']['type'] = 10;
			$data['log']['affect_money'] = $money;
			$data['log']['account_money'] = $members_money['account_money'] - $money;
			$data['log']['collect_money'] = $members_money['money_collect'];
			$data['log']['freeze_money'] = $members_money['money_freeze'] + $money;
			$in_cust = $this->member_model->get_member_info_byuid($water['uid']);
			$data['log']['info'] = $in_cust['real_name'] . "从账户中提现" . $money . "元，操作处理中";
			$data['log']['add_time'] = time();
			$data['log']['actualAmt'] = $body['body']['actualAmt'];
			$data['log']['pledgedAmt'] = $body['body']['pledgedAmt'];
			$data['log']['preLicAmt'] = $body['body']['preLicAmt'];
			$data['log']['totalAmt'] = $body['body']['totalAmt'];
			$data['log']['acctNo'] = $body['body']['acctNo'];
			$data['log']['nid'] = $merOrderNo;
			
			$this->member_model->recharge($data, $water['uid'], $merOrderNo);
		}
	}*/
	/** 有冻结功能的提现 （T+0）*/
	public function withdraw_tx($body = array(), $async) {
		$merOrderNo = $body['head']['merOrderNo'];
		$water = $this->water_model->get_water_byorder($merOrderNo);
		if($water['status'] > 0) {
			return true;
		}
		if(empty($water['uid'])) {
			log_record('提现记录表(members_moneylog)出错,请手动处理.订单号为:'.$merOrderNo, '', $water['uid'].'-41');
		}
		$this->load->model(array('member/member_model'));
		$data = array();
		if($async) {//异步并且未处理过
			//log_record('22222');
			//查询账户余额
			$meminfo = $this->member_model->get_member_info_byuid($water['uid']);
			$params['acctNo'] = $meminfo['acctNo'];
			$head = head($params, 'CG2001', 'over');
			unset($head['callbackUrl'], $head['registerPhone'], $head['responsePath'], $head['url']);

			$data = $head;
			$data = json_encode($data);
			$url = $this->config->item('Interface').'2001';
			//请求接口
			$str = post_curl_test($url, $data);
			//$this->load->model(array('paytest/paytest_model'));
			$tmp_body = $this->excute($str);
			//log_record(serialize($tmp_body));
			//提现成功
			if($body['body']['respCode'] === '000000') {
				$members_money = $this->member_model->get_members_money_byuid($water['uid']);
				$money = round(floatval($water['money']), 2);;//round($members_money['account_money'] - $body['body']['actualAmt'], 2);
				// if(($members_money['account_money'] - $body['body']['actualAmt']) < 0.001) {
					// return false;
				// }
				$data['money']['account_money'] = $members_money['account_money'] - $money;
				//回写表members_money_log
				$data['log']['uid'] = $water['uid'];
				$data['log']['type'] = 10;
				$data['log']['affect_money'] = $money;
				$data['log']['account_money'] = $data['money']['account_money'];
				$data['log']['collect_money'] = $members_money['money_collect'];
				$data['log']['freeze_money'] = $members_money['money_freeze'];
				//$in_cust = $this->member_model->get_member_info_byuid($water['uid']);
				$data['log']['info'] = "提现" . $money . "元，操作成功";
				$data['log']['add_time'] = time();
				$data['log']['actualAmt'] = $body['body']['actualAmt'];
				$data['log']['pledgedAmt'] = $body['body']['pledgedAmt'];
				$data['log']['preLicAmt'] = $body['body']['preLicAmt'];
				$data['log']['totalAmt'] = $body['body']['totalAmt'];
				$data['log']['acctNo'] = $body['body']['acctNo'];
				$data['log']['nid'] = $merOrderNo;
				//回写表members_payonline
				$payonline = $this->member_model->get_quick_bank($water['uid']);
				$data['payonline']['uid'] = $water['uid'];
				$data['payonline']['nid'] = $merOrderNo;
				$data['payonline']['money'] = $money;
				$data['payonline']['way'] = '';
				
				$data['payonline']['bank'] = $payonline['paycard'];
				$data['payonline']['status'] = 1;
				$data['payonline']['add_time'] = time();
				$data['payonline']['remark'] = $payonline['bank_address'];
				$data['payonline']['type'] = 2;//提现
				//修改流水表状态为1
				$data['water']['status'] = 1;
				//$this->member_model->recharge($data, $water['uid'], $merOrderNo);
				if(!$this->member_model->recharge($data, $water['uid'], $merOrderNo)) {
					log_record(serialize($data), '', $water['uid'].'-6');
				}
			} else { 
				//提现失败
				// $members_money = $this->member_model->get_members_money_byuid($water['uid']);
				// $money = round(floatval($water['money']), 2);
				$data = array();
				$data['money']['account_money'] = $tmp_body['body']['actualAmt'];
				//$data['money']['money_freeze'] = $members_money['money_freeze'] - $money;
				//回写表members_money_log
				$money_log = $this->member_model->get_moneylog_bynid($merOrderNo);
				if(empty($money_log)) {
					log_record('提现记录表(members_moneylog)出错,请手动处理.订单号为:'.$merOrderNo, '', $water['uid'].'-23');
					return false;
				}
				$data['log'] = $money_log;
				//$data['log']['uid'] = $water['uid'];
				//$data['log']['type'] = 10;
				//$data['log']['affect_money'] = $money;
				$data['log']['account_money'] = $data['money']['account_money'];
				//$data['log']['collect_money'] = $members_money['money_collect'];
				//$data['log']['freeze_money'] = data['log']['freeze_money'] - $money;
				//$in_cust = $this->member_model->get_member_info_byuid($water['uid']);
				$data['log']['info'] = "提现" . $data['log']['affect_money'] . "元，提现失败";//$in_cust['real_name'] . "从账户中提现" . $data['log']['affect_money'] . "元，提现失败";
				//$data['log']['add_time'] = time();
				//$data['log']['actualAmt'] = $body['body']['actualAmt'];
				//$data['log']['pledgedAmt'] = $body['body']['pledgedAmt'];
				//$data['log']['preLicAmt'] = $body['body']['preLicAmt'];
				//$data['log']['totalAmt'] = $body['body']['totalAmt'];
				//$data['log']['acctNo'] = $body['body']['acctNo'];
				//$data['log']['nid'] = $merOrderNo;
				//回写表members_payonline
				$payonline = $this->member_model->get_quick_bank($water['uid']);
				$data['payonline']['uid'] = $water['uid'];
				$data['payonline']['nid'] = $merOrderNo;
				$data['payonline']['money'] = $data['log']['affect_money'];
				$data['payonline']['way'] = '';
				$data['payonline']['bank'] = $payonline['paycard'];
				$data['payonline']['status'] = 0;
				$data['payonline']['add_time'] = time();
				$data['payonline']['remark'] = $payonline['bankaddress'];
				$data['payonline']['type'] = 2;//提现
				//修改流水表状态为1
				$data['water']['status'] = 1;
				if(!$this->member_model->recharge($data, $water['uid'], $merOrderNo)) {
					log_record(serialize($data), '', $water['uid'].'-7');
				}
			}
			return true;
		} else if($water['status'] === '0') {//同步只生成moneylog表,如果异步已处理过，不需要重复操作
			if($body['head']['respCode'] == '000000') {
				$members_money = $this->member_model->get_members_money_byuid($water['uid']);
				$money = round(floatval($water['money']), 2);
				$data['money']['account_money'] = $members_money['account_money'] - $money;
				//回写表members_money_log
				//$money_log = $this->member_model->get_moneylog_bynid($merOrderNo);
				$data['log']['uid'] = $water['uid'];
				$data['log']['type'] = 10;
				$data['log']['affect_money'] = $money;
				$data['log']['account_money'] = $data['money']['account_money'];
				$data['log']['collect_money'] = $members_money['money_collect'];
				$data['log']['freeze_money'] = $members_money['money_freeze'];
				//$in_cust = $this->member_model->get_member_info_byuid($water['uid']);
				$data['log']['info'] = "提现" . $money . "元，提现成功";//$in_cust['real_name'] . "向自己账户中充值" . $money . "元，充值成功";//.",".$body['body']['actualAmt'].",".$members_money['account_money'].",".$data['money']['account_money'];
				$data['log']['add_time'] = time();
				$data['log']['actualAmt'] = $body['body']['actualAmt'];
				$data['log']['pledgedAmt'] = $body['body']['pledgedAmt'];
				$data['log']['preLicAmt'] = $body['body']['preLicAmt'];
				$data['log']['totalAmt'] = $body['body']['totalAmt'];
				$data['log']['acctNo'] = $body['body']['acctNo'];
				$data['log']['nid'] = $merOrderNo;
				//回写表members_payonline
				$payonline = $this->member_model->get_quick_bank($water['uid']);
				$data['payonline']['uid'] = $water['uid'];
				$data['payonline']['nid'] = $merOrderNo;
				$data['payonline']['money'] = $money;
				$data['payonline']['way'] = '';
				
				$data['payonline']['bank'] = $payonline['paycard'];
				$data['payonline']['status'] = 1;
				$data['payonline']['add_time'] = time();
				$data['payonline']['remark'] = $payonline['bank_address'];
				$data['payonline']['type'] = 2;//充值
				//修改流水表状态为1
				$data['water']['status'] = 1;
				if(!$this->member_model->recharge($data, $water['uid'], $merOrderNo)) {
					log_record(serialize($data), '', $water['uid'].'-30');
				}
			} else {
				log_record('提现失败.原因'.$body['head']['respDesc'].'订单号为:'.$merOrderNo, '', $water['uid'].'-82');
			}
			/*$members_money = $this->member_model->get_members_money_byuid($water['uid']);
			$money = round(floatval($water['money']), 2);
			//账户冻结一定金额
			$data['money']['account_money'] = $members_money['account_money'] - $money;
			//$data['money']['money_freeze'] = $members_money['money_freeze'];
			//回写表members_money_log
			$data['log']['uid'] = $water['uid'];
			$data['log']['type'] = 10;
			$data['log']['affect_money'] = $money;
			$data['log']['account_money'] = $members_money['account_money'] - $money;
			$data['log']['collect_money'] = $members_money['money_collect'];
			$data['log']['freeze_money'] = $members_money['money_freeze'];
			$in_cust = $this->member_model->get_member_info_byuid($water['uid']);
			$data['log']['info'] = "提现" . $money . "元，提现处理中";//$in_cust['real_name'] . "从账户中提现" . $money . "元，提现处理中";
			$data['log']['add_time'] = time();
			$data['log']['actualAmt'] = $body['body']['actualAmt'];
			$data['log']['pledgedAmt'] = $body['body']['pledgedAmt'];
			$data['log']['preLicAmt'] = $body['body']['preLicAmt'];
			$data['log']['totalAmt'] = $body['body']['totalAmt'];
			$data['log']['acctNo'] = $body['body']['acctNo'];
			$data['log']['nid'] = $merOrderNo;
			
			if(!$this->member_model->recharge($data, $water['uid'], $merOrderNo)) {
				log_record(serialize($data), '', $water['uid'].'-8');
			}*/
		}
	}
	/** T+1 提现 */
	public function withdraw_tx1($body = array()) {
		$merOrderNo = $body['head']['merOrderNo'];
		$water = $this->water_model->get_water_byorder($merOrderNo);
		$this->load->model(array('member/member_model'));
		$data = array();
		if($water['status'] === '0') {
			//回写表members_money
			$members_money = $this->member_model->get_members_money_byuid($water['uid']);
			$money = round(floatval($water['money']), 2);
			$data['money']['account_money'] = $members_money['account_money'] - $money;
			//回写表members_money_log
			$data['log']['uid'] = $water['uid'];
			$data['log']['type'] = 10;
			$data['log']['affect_money'] = $money;
			$data['log']['account_money'] = $data['money']['account_money'];
			$data['log']['collect_money'] = $members_money['money_collect'];
			$data['log']['freeze_money'] = $members_money['money_freeze'] - $money;
			//$in_cust = $this->member_model->get_member_info_byuid($water['uid']);
			$data['log']['info'] = "提现" . $money . "元，提现成功";//$in_cust['real_name'] . "从账户中提现" . $money . "元，提现成功";
			$data['log']['add_time'] = time();
			$data['log']['actualAmt'] = $body['body']['actualAmt'];
			$data['log']['pledgedAmt'] = $body['body']['pledgedAmt'];
			$data['log']['preLicAmt'] = $body['body']['preLicAmt'];
			$data['log']['totalAmt'] = $body['body']['totalAmt'];
			$data['log']['acctNo'] = $body['body']['acctNo'];
			$data['log']['nid'] = $merOrderNo;
			//回写表members_payonline
			$payonline = $this->member_model->get_quick_bank($water['uid']);
			$data['payonline']['uid'] = $water['uid'];
			$data['payonline']['nid'] = $merOrderNo;
			$data['payonline']['money'] = $money;
			$data['payonline']['way'] = '';
			
			$data['payonline']['bank'] = $payonline['paycard'];
			$data['payonline']['status'] = 1;
			$data['payonline']['add_time'] = time();
			$data['payonline']['remark'] = $payonline['bank_address'];
			$data['payonline']['type'] = 2;//提现
			//修改流水表状态为1
			$data['water'] = $water;
			$data['water']['status'] = 1;
			if(!$this->member_model->recharge($data, $water['uid'], $merOrderNo)) {
				log_record(serialize($data), '', $water['uid'].'-9');
			}
		}
	}
	//还款(一笔一笔的还款)
	public function repayment($body = array()) {
		$respCode = $body['head']['respCode'];
		if($respCode === '000000') {
			$merOrderNo = $body['head']['merOrderNo'];
			$water = $this->water_model->get_water_byorder($merOrderNo);
			$id_detail = $water['bid'];
			$this->load->model(array('borrow/borrow_model', 'member/member_model'));
			$detail = $this->borrow_model->get_detail_one($id_detail);
			//判断是不是最后一期的还款
			$is_total = $detail['sort_order'] === $detail['total'] ? true : false;
			$id = $detail['borrow_id'];
			//调取标信息
			$borrow = $this->borrow_model->get_borrow_byid($id);
			//调取借款人信息
			$meminfo = $this->member_model->get_member_info_byuid($borrow['borrow_uid']);
			//调取投资人的账户信息
			$meminfos = $this->member_model->get_member_info_byuid($detail['investor_uid']);
			//判断是不是最后一笔还款
			//>0,不是最后一笔还款，否则这是最后一笔还款
			$res_detail = $this->borrow_model->is_last_detail($id_detail, $id);
			//$res_detail = 0;
			$is_last = $res_detail > 0 ? false : true;
			//操作金额
			$money = $is_total ? ($detail['capital'] + $detail['interest']) : $detail['interest'];
			//时间戳
			$timestamp = time();
			
			//修改主表状态
			$data = array();
			$data['borrow'] = array(
				'id'	=> $id,
				'borrow_status' => $is_last ? 5 : 4,
				'has_pay'		=> $borrow['has_pay'] + $money,
			);
			//第二张表
			//调取所有投资人信息
			$investor = $this->borrow_model->get_investor_one($detail['invest_id']);
			$data['investor'] = array(
				'id' => $investor['id'],
				'receive_capital' => $is_total ? ($investor['receive_capital'] + $detail['capital']) : $investor['receive_capital'],
				'receive_interest' => $investor['receive_interest'] + $detail['interest']
			);
			//第三张表数据 【一次性到期还本息， 按月付息到期还本】
			$data['detail'] = array(
				'id'				=> $id_detail,
				'repayment_time' 	=> $timestamp,
				'receive_capital' 	=> $is_total ? $detail['capital'] : 0,
				'receive_interest' 	=> $detail['interest']
			);
			//如果是最后一笔
			if($is_last) {
				$data['investor_status'] = array(
					'borrow_id' => $id,
					'status'	=> 5
				);
				$data['detail_status'] = array(
					'borrow_id' => $id,
					'status' => 5
				);
			}
			//处理金额
			//投资人
			$memoney = $this->info_model->get_money($investor['investor_uid']);
			$data['money'][] = array(
				'uid'			=> $investor['investor_uid'],
				'account_money'	=> $memoney['account_money'] + $money,
				'money_freeze' => $memoney['money_freeze'],
				'money_collect' => $is_total ? ($memoney['money_collect'] - $detail['capital']) : $memoney['money_collect']
			);
			$data['log'][] = array(
				'uid' => $investor['investor_uid'],
				'type' => 9,//收款
				'affect_money' => $water['money'],
				'account_money' => $memoney['account_money'] + $money,//可用
				'collect_money' => $is_total ? ($memoney['money_collect'] - $detail['capital']) : $memoney['money_collect'],//待收
				'freeze_money' => $memoney['money_freeze'],//解冻
				'info' => $is_total ? '收取本金' . $detail['capital'] . '元，利息' . $detail['interest'] . '元' : '收取利息'. $detail['interest'] . '元',
				'add_time' => $timestamp,
				//'add_ip' => $this->input->ip_address(),
				'bid' 	=> $id,
				'nid'	=> $merOrderNo
			);
			//借款人
			$memoney_borrow = $this->info_model->get_money($borrow['borrow_uid']);
			$data['money'][] = array(
				'uid'			=> $borrow['borrow_uid'],
				'account_money' => $memoney_borrow['account_money'] - $water['money'],
				'money_freeze' => $memoney_borrow['money_freeze'],
				'money_collect' => $memoney_borrow['money_collect']
			);
			
			if($water['money'] - $money > 0.001) {
				$info = ',平台服务费' . ($water['money'] - $money) . '元';
			} else {
				$info = '';
			}
			$data['log'][] = array(
				'uid' => $borrow['borrow_uid'],
				'type' => 8,//还款
				'affect_money' => $water['money'],
				'account_money' => $memoney_borrow['account_money'] - $water['money'],//可用
				'collect_money' => $memoney_borrow['money_collect'],//待收
				'freeze_money' => $memoney_borrow['money_freeze'],//冻结
				'info' => $is_total ? '还款本金' . $detail['capital'] . '元，利息' . $detail['interest'] . '元'.$info : '还款利息'. $detail['interest'] . '元'.$info,
				'add_time' => $timestamp,
				//'add_ip' => $this->input->ip_address(),
				'bid' => $id,
				'nid' => $merOrderNo
			);
			$data['water']['status'] = 1;
			$data['water']['merOrderNo'] = $merOrderNo;
			$this->borrow_model->repayment($data);
			
			//p($data);
			//标的结束
			if($is_last) {
				$params['subjectNo'] = $borrow['subjectNo'];
				$head = head($params, 'CG1032', 'over');
				water($borrow['borrow_uid'], $head['merOrderNo'], 'CG1032', $borrow['id']);
				unset($head['callbackUrl'], $head['registerPhone'], $head['responsePath'], $head['url']);
				$data = $head;
				$data = json_encode($data);
				$url = $this->config->item('Interface').'1032';
				$str = post_curl_test($url, $data);
				$this->load->model(array('paytest/paytest_model'));
				$res_water = $this->paytest_model->excute($str);
				if($res_water['head']['respCode'] === '000000') {
					$data['water']['status'] = 1;
					//$data['water']['merOrderNo'] = $res_water['head']['merOrderNo'];
					$this->load->model(array('water/water_model'));
					$this->water_model->edit_water($data['water'], $res_water['head']['merOrderNo']);
				}
				
			}
		}
	}
}

