<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Company extends MY_Controller {
	public function __construct() {
        parent::__construct();
        $this->load->library('pagination');
		$this->load->model('member/member_model');
    }
	public function index(){
		$data = array();
		$where = array();
		$search = $this->input->get(null, true);
		if(!empty($search['skey'])) {
			$data['skey'] = trim(trim($search['skey']), '\t');
			$where['skey'] = $data['skey'];
		}
		$where['members.attribute'] = 2;
		$current_page = $this->uri->segment(3) ? $this->uri->segment(3) : 1;
		$current_page = $current_page > 0 ? $current_page - 1 : 0;
		$per_page = 10;
        $offset = $current_page;
        $config['base_url'] = base_url('company/index');
        $config['total_rows'] = $this->member_model->get_member_related_num($where);
        $config['per_page'] = $per_page;
		$config['page_query_string'] = FALSE;
		$config['first_link'] = '首页'; // 第一页显示   
		$config['last_link'] = '末页'; // 最后一页显示   
		$config['next_link'] = '下一页'; // 下一页显示   
		$config['prev_link'] = '上一页'; // 上一页显示   
		$config['cur_tag_open'] = ' <span class="current">'; // 当前页开始样式   
		$config['cur_tag_close'] = '</span>';   
		$config['num_links'] = 10;
		$config['uri_segment'] = 3;
		$config['use_page_numbers'] = TRUE;
		$config['reuse_query_string'] = TRUE;
        $this->pagination->initialize($config); 
        $data['totals'] = $config['total_rows'];
        $data['page'] = $this->pagination->create_links();
        $data['p'] = $current_page;
        $member = $this->member_model->get_member_related($per_page, $offset * $per_page, $where);
        $data['member'] = $member;
		//$this->load->view('member/member', $data);
		$this->load->view('company/index', $data);
	}
	public function export() {
		$this->load->model('company/company_model');
		$data = array();
		$where = array();
		$search = $this->input->get(null, true);
		if(!empty($search['skey'])) {
			$data['skey'] = trim(trim($search['skey']), '\t');
			$where['skey'] = $data['skey'];
		}
		$where['members.attribute'] = 2;
		$config['total_rows'] = $this->company_model->get_company_related_num($where);
		$member = $this->company_model->get_company_related($config['total_rows'], 0, $where);
		$all = $member;
		$this->load->helper('common');
		$this->load->library('PHPExcel');
		$resultPHPExcel = new PHPExcel();
		$resultPHPExcel->getActiveSheet()->setCellValue('A1', '企业名称');
		$resultPHPExcel->getActiveSheet()->setCellValue('B1', '联系手机');
		$resultPHPExcel->getActiveSheet()->setCellValue('C1', '企业号码');
		$resultPHPExcel->getActiveSheet()->setCellValue('D1', '注册时间');
		$resultPHPExcel->getActiveSheet()->setCellValue('E1', '所属行业');
		$resultPHPExcel->getActiveSheet()->setCellValue('F1', '统一社会信用代码/注册号');
		$resultPHPExcel->getActiveSheet()->setCellValue('G1', '成立时间');
		$resultPHPExcel->getActiveSheet()->setCellValue('H1', '注册地址');
		$resultPHPExcel->getActiveSheet()->setCellValue('I1', '登记状态');
		$resultPHPExcel->getActiveSheet()->setCellValue('J1', '征信状况');
		$resultPHPExcel->getActiveSheet()->setCellValue('K1', '涉诉情况');
		$resultPHPExcel->getActiveSheet()->setCellValue('L1', '行政处罚状况');
		$resultPHPExcel->getActiveSheet()->setCellValue('M1', '已知其他网贷平台负债');
		$resultPHPExcel->getActiveSheet()->setCellValue('N1', '平台历史借款记录');
		$resultPHPExcel->getActiveSheet()->setCellValue('O1', '企业相关资料');
		$resultPHPExcel->getActiveSheet()->setCellValue('P1', '经营范围');
		$i = 1;
		foreach($all as $k => $v){
			$i++;
			$resultPHPExcel->getActiveSheet()->setCellValue('A'.$i, $v['real_name']);
			$resultPHPExcel->getActiveSheet()->setCellValue('B'.$i, $v['phone']." ");
			$resultPHPExcel->getActiveSheet()->setCellValue('C'.$i, $v['idcard']." ");
			$resultPHPExcel->getActiveSheet()->setCellValue('D'.$i, date('Y-m-d', $v['reg_time']));
			$resultPHPExcel->getActiveSheet()->setCellValue('E'.$i, $v['industry']);
			$resultPHPExcel->getActiveSheet()->setCellValue('F'.$i, $v['credit']);
			$resultPHPExcel->getActiveSheet()->setCellValue('G'.$i, $v['founding_time']);
			$resultPHPExcel->getActiveSheet()->setCellValue('H'.$i, $v['reg_address']);
			$resultPHPExcel->getActiveSheet()->setCellValue('I'.$i, $v['reg_status']);
			$resultPHPExcel->getActiveSheet()->setCellValue('J'.$i, $v['credit_status']);
			$resultPHPExcel->getActiveSheet()->setCellValue('K'.$i, $v['situation']);
			$resultPHPExcel->getActiveSheet()->setCellValue('L'.$i, $v['sanction']);
			$resultPHPExcel->getActiveSheet()->setCellValue('M'.$i, $v['liabilities']);
			$resultPHPExcel->getActiveSheet()->setCellValue('N'.$i, $v['records']);
			$resultPHPExcel->getActiveSheet()->setCellValue('O'.$i, $v['info']);
			$resultPHPExcel->getActiveSheet()->setCellValue('P'.$i, $v['scope']);
			
		}
		$outputFileName = '企业信息.xls'; 
		$xlsWriter = new PHPExcel_Writer_Excel5($resultPHPExcel); 
		header("Content-Type: application/force-download"); 
		header("Content-Type: application/octet-stream"); 
		header("Content-Type: application/download"); 
		header('Content-Disposition:inline;filename="'.$outputFileName.'"'); 
		header("Content-Transfer-Encoding: binary"); 
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
		header("Pragma: no-cache");
		$xlsWriter->save( "php://output" );
	}
	public function add(){
		if($post = $this->input->post(NULL, TRUE)){
			$data['user_name'] = $post['phone'];
			$data['reg_time'] = time();
			$data['reg_ip'] = $this->input->ip_address();
			$data['salt'] = salt();
			$data['type'] = 2;
			$data['attribute'] = 2;
			
			//客户号和账户号不能重复
			$count_custNo = $this->member_model->get_member_info_byacc($post['custNo']);
			$count_acctNo = $this->member_model->get_member_info_byacc($post['acctNo']);
			if($count_custNo > 0 || $count_acctNo > 0) {
				$this->error('客户号码或账户号码不能重复');
			}
			$post['custNo'] = trim($post['custNo']);
			$post['acctNo'] = trim($post['acctNo']);
			if(strlen($post['acctNo']) != 13) {
				$this->error('账户号码位数不正确');
			}
			if(strlen($post['custNo']) != 18) {
				$this->error('客户号码位数不正确');
			}
			if(! $post['user_name']){
				$info['state'] = 0;
				$info['message'] = '请填写企业名称!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			if (! is_phone($data['user_name'])){
				$info['state'] = 0;
				$info['message'] = '请填写真实的手机号码!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			
			if(! $post['card']){
				$info['state'] = 0;
				$info['message'] = '请填写企业号码!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			} else {
				$post['card'] = trim($post['card']);
			}
			if($this->member_model->get_member_info_byrc($post['card'], $post['user_name'])){
				$info['state'] = 0;
				$info['message'] = '企业名称或号码被注册过!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			if($this->member_model->get_member_byusername($data['user_name'])){
				$info['state'] = 0;
				$info['message'] = '手机号码已存被注册过!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			$data['user_pass'] = MD5(suny_encrypt(trim($post['pwd']), $data['salt']));
			$this->db->trans_begin();
			$m = $this->member_model->add_member($data);
			$datam = array(
				'uid' => $m,
				'money_freeze' => '0.00',
				'money_collect' => '0.00',
				'account_money' => '0.00'
			);
			$datas = array(
				'uid' => $m,
				'phone_status' => 1,
				'id_status' => 1
			);
			$datai = array(
				'uid' => $m,
				'phone' => $data['user_name'],
				'idcard' => $post['card'],
				'real_name' => $post['user_name'],
				'custNo'	=> $post['custNo'],
				'acctNo'	=> $post['acctNo'],
				'ppwd'		=> $post['ppwd']
			);
			$this->member_model->add_members_money($datam);
			$this->member_model->add_member_status($datas);
			$this->member_model->add_member_info($datai);
			if($this->db->trans_status() === TRUE){
				$this->db->trans_commit();
				$info['state'] = 1;
				$info['message'] = '注册成功!';
				$info['url'] = '/company.html';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}else{
				$this->db->trans_rollback();
				$info['state'] = 0;
				$info['message'] = '注册失败!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
		}
		$this->load->view('company/add');
	}
	/** 充值密码 */
	public function reset_pwd() {
		if(IS_POST) {
			$id = $this->uri->segment(3);
			$member = $this->member_model->get_member_byuserid($id);
			if($member['type'] == 2 && $member['attribute'] == 2) {
				//$this->success($member['user_name']);
				//$data['salt'] = salt();
				//$data['user_pass'] = MD5(suny_encrypt('', $data['salt']));
				//更新密码就可以了
			} else {
				$this->error('非企业用户请求');
			}
			
		} else {
			$this->error('非法请求');
		}
		
	}
	/** 绑卡 */
	public function addcard(){
		/* $this->load->library('CG');
		$cg = new CG();
		$regBody = array(
			'callbackUrl' => 'https://test.jiamanu.com/paytest',
			'responsePath' => 'https://test.jiamanu.com/',
			'registerPhone' => '15021180031',
			'custType' => '00',//03贷款用户
		);
		$cg->getInstance('123456','1044',$reqBody); */
		//$this->load->view('company/addcard');
		
		//调取当前用户信息
		//接收ID值
		$id = $this->uri->segment(3);
		if(empty($id)) {
			$this->error('请求有误');
		}
		$mem = $this->member_model->get_member_byuserid(UID);
		$meminfo = get_member_info($id);
		
		//判断是否是手机号
		if(!is_phone($meminfo['phone'])) {
			$this->error('该手机号不能用于绑卡');
		}
		
		
		$params['callbackUrl'] 		= 'https://www.jiamanu.com/paytest';
		$params['responsePath'] 	= 'https://www.jiamanu.com/company/addcardui';
		$params['registerPhone'] 	= $meminfo['phone'];
		$params['custType'] 		= $mem['type'] === '2' ? '03' : '00';
		//$data = $this->head($params, 'CG1044');
		$data = head($params, 'CG1044');
		
		$data['url'] = $this->config->item('Interfaces').'1044';
		
		$this->load->view('company/jump', $data);
		
		
		
		
		
		
		//解密
		/*$req = array();
		$req = array(
			//'merchantNo' => $this->config->item('mchnt_cd'),
			'key' => $b['keyEnc'],
			'bodys' => $b['jsonEnc'],
			'sign' => $b['sign']
		);
		$res = post_curl($payapi['desdo'], $req);
		var_dump($res);*/
		
		
	}
	
	/** 绑卡成功后页面 */
	public function addcardui() {
		echo 11111;
	}
	
	//-----------------------------------------
	
	
	/** 解绑银行卡 */
	public function unbunding() {
		$params['custNo'] = '110181808080010725';
		$params['callbackUrl'] = $this->config->item('payapi')['callbackUrl'];
		$params['responsePath'] = 'https://www.jiamanu.com/company/addcardui';
		$data = head($params, 'CG1057');
		
		$data['url'] = $this->config->item('Interfaces').'1057';
		
		$this->load->view('company/jump', $data);
	}
	
	/** 更换银行卡 */
	public function changecard() {
		$uid = $this->uri->segment(3);
		$meminfo = $this->member_model->get_member_info_byuid($uid);
		$params['custNo'] = $meminfo['custNo'];
		$params['callbackUrl'] = $this->config->item('payapi')['callbackUrl'];
		$params['responsePath'] = 'https://www.jiamanu.com/company/addcardui';
		$data = head($params, 'CG1056');
		
		$data['url'] = $this->config->item('Interfaces').'1056';
		$this->load->view('company/jump', $data);
	}
	
	/** 充值 */
	public function recharge() {
		$params['acctNo'] = '1018001073101';//'1018001073101';
		$params['amount'] = 0.01;
		$params['incomeAmt'] = 0.00;
		$params['callbackUrl'] = $this->config->item('payapi')['callbackUrl'];
		$params['responsePath'] = 'https://www.jiamanu.com/company/addcardui';
		$data = head($params, 'CG1045');
		
		$data['url'] = $this->config->item('Interfaces').'1045';
		$this->load->view('company/recharge', $data);
	}
	/** 网关充值  待处理*/
	public function gat_recharge() {
		$params['uaType'] = '00';//'1018001073101';
		$params['acctNo'] = '1018001073101';//'1018001073101';
		$params['acctType'] = 'C001';
		$params['incomeAmt'] = 0.00;
		$params['callbackUrl'] = $this->config->item('payapi')['callbackUrl'];
		$params['responsePath'] = 'https://www.jiamanu.com/company/addcardui';
		$data = head($params, 'CG1045');
		
		$data['url'] = $this->config->item('Interfaces').'1045';
		$this->load->view('company/recharge', $data);
	}
	/** 提现 */
	//public function 
	
	/* 交易查询(实时接口) */
	public function inquiry() {
		$params['acctNo'] = '1018001073101';
		//$params['acctType'] = '01';
		//$params['amount'] = '0.01';
		
		
		
		$data = head($params, 'CG2001');
		
		$url = $this->config->item('Interface').'2001';
		//var_dump($url);
		//var_dump($data);
		//$res = post_curl($url, $params);
		//$params['responsePath'] = 'https://www.jiamanu.com/company/addcardui';
		//$data = head($params, 'CG1044');
		
		
		
		
		$res = post_curl($url, $data);
		var_dump($res);
		//解密
		/*$aaa['key'] = $res['keyEnc'];
		$aaa['bodys'] = $res['jsonEnc'];
		$aaa['sign'] = $res['sign'];
		$res = decrypt($aaa);
		var_dump($res);*/
		//$this->load->view('company/inquiry', $data);
	}
	
	/** 重置交易密码 */
	public function reset_trade_password() {
		$uid = $this->uri->segment(3);
		$meminfo = $this->member_model->get_member_info_byuid($uid);
		$params['custNo'] = $meminfo['custNo'];
		$params['callbackUrl'] = $this->config->item('payapi')['callbackUrl'];
		$params['responsePath'] = 'https://www.jiamanu.com/company/addcardui';
		$data = head($params, 'CG1055');
		
		$data['url'] = $this->config->item('Interfaces').'1055';
		$this->load->view('company/jump', $data);
	}
	/** 修改支付密码 */
	public function set_password() {
		$params['custNo'] = '110181808080010731';
		$params['pswordCode'] = '01';
		$params['callbackUrl'] = $this->config->item('payapi')['callbackUrl'];
		$params['responsePath'] = 'https://www.jiamanu.com/company/addcardui';
		$data = head($params, 'CG1048');
		
		$data['url'] = $this->config->item('Interfaces').'1048';
		$this->load->view('company/jump', $data);
	}
	
	/** 余额查询 */
	public function balance() {
		$uid = $this->uri->segment(3);
		$meminfo = $this->member_model->get_member_info_byuid($uid);
		$params['acctNo'] = $meminfo['acctNo'];
		//echo $meminfo['custNo'];
		$head = head($params, 'CG2001', 'over');
		unset($head['callbackUrl'], $head['registerPhone'], $head['responsePath'], $head['url']);

		$data = $head;
		$data = json_encode($data);
		//var_dump($data);die;
		$url = $this->config->item('Interface').'2001';
		//var_dump($url);die;
		//请求接口
		$str = post_curl_test($url, $data);
		$this->load->model(array('paytest/paytest_model'));
		$tmp_body = $this->paytest_model->excute($str);
		$this->load->view('company/balance', $tmp_body);
	}
	/** 上标 */
	public function subject() {
		//$params['merchantNo'] = $this->config->item('mchnt_cd');
		//$params['merOrderNo'] = date('YmdHis').genRandomString(10);
		//$params['tradeCode'] = 'CG1012';
		$params['merSubjectNo'] = date('YmdHis').genRandomString(10);
		$params['subjectName'] = '车辆抵押20180829';
		$params['subjectAmt'] = '1000';
		$params['subjectRate'] = '0.12';
		$params['subjectPurpose'] = '个人消费';
		$params['payeeAcctNo'] = '1018001021501';//'110181805300010215';//
		$params['subjectType'] = '00';
		$params['identificationNo'] = '450981197504136773';
		$params['serviceRate'] = '0.005';
		//$params['guarantor'] = '';
		//$params['guarantorAcct'] = '1018001021201';
		$params['subjectStartDate'] = '20180829';
		$params['SubjectEndDate'] = '20180910';
		//$params['callbackUrl'] = $this->config->item('payapi')['callbackUrl'];
		//$params['responsePath'] = 'https://www.jiamanu.com/company/addcardui';
		//$params['url']	= 'https://cg.eqqnsyh.com/services/cgbiz/cg1012';
		$head = head($params, 'CG1012', 'createdo');
		//var_dump($head);die;
		unset($head['callbackUrl']);
		unset($head['registerPhone']);
		unset($head['responsePath']);
		unset($head['url']);
		//p($head);die;
		$data = $head;
		//$data['body'] = $params;
		// foreach($params as $k=>$v) {
			// $data[$k] = $v;
		// }
		//$data['body'] = $params;
		$data = json_encode($data);
		//p($data);
		//echo gettype($data);die;
		//curl请求方式
		$url = $this->config->item('Interface').'1012';
		$res = post_curl_test($url, $data);
		var_dump($res);
	}
	
	/** 标的购买 */
	public function buy() {
		$params['acctNo'] = '1018001073101';//'1018001073101';
		$params['amount'] = 0.01;
		$params['subjectNo'] = 'CN0002';
		$params['callbackUrl'] = $this->config->item('payapi')['callbackUrl'];
		$params['responsePath'] = 'https://www.jiamanu.com/company/addcardui';
		$data = head($params, 'CG1052');
		
		$data['url'] = $this->config->item('Interfaces').'1052';
		$this->load->view('company/jump', $data);
	}
	
	/** 企业信息完善 */
	public function perfect() {
		if(IS_POST) {
			$post = $this->input->post(NULL, TRUE);
			$uid = intval($post['uid']);
			if(empty($uid)) {
				$this->error('数据错误');
			}
			$data = $this->member_model->get_company_info_byuid($uid);
			$data['uid']			= $uid;
			$data['industry'] 		= $post['industry'];
			$data['credit'] 		= $post['credit'];
			$data['founding_time'] 	= $post['founding_time'];
			$data['reg_address'] 	= $post['reg_address'];
			$data['reg_status'] 	= $post['reg_status'];
			$data['credit_status'] 	= $post['credit_status'];
			$data['situation'] 		= $post['situation'];
			$data['sanction'] 		= $post['sanction'];
			$data['liabilities'] 	= $post['liabilities'];
			$data['records'] 		= $post['records'];
			$data['info'] 			= $post['info'];
			$data['scope'] 			= $post['scope'];
			$data['uptime'] 		= time();
			$data['adminid'] 		= UID;
			if($this->member_model->up_company_info($data)) {
				$this->success('操作成功');
			} else {
				$this->error('操作失败');
			}
		}
		$data = array();
		$uid  = $this->uri->segment(3);
		if(empty($uid)) {
			exit('数据错误');
		}
		$data['uid'] = $uid;
		
		$data['meminfo'] = $this->member_model->get_member_info_byuid($uid);
		$data['company'] = $this->member_model->get_company_info_byuid($uid);
		$this->load->view('company/perfect', $data);
	}
	
	/** 生成签章 */
	public function esign() {
		$uid = $this->uri->segment(3);
		
		if(empty($uid)) {
			$this->error('信息错误');
		}
		$accountId = '';//账户标识
		//调取个人信息
		$meminfo = $this->member_model->get_member_info_byuid($uid);
		if(!empty($meminfo['sealPath'])) {
			$this->error('印章已创建,路径为：' . $meminfo['sealPath']);
		}
		//如果没有创建账户，需要重新创建账户
		$this->load->library('EsignAPI/EsignAPI'); 
		$EsignAPI = new EsignAPI();
		if(empty($meminfo['accountId'])) {
			//创建企业账户
			$res = $EsignAPI->addOrgAccount($meminfo);
			//print_r($res);die;
			if(empty($res['errCode'])) {//返回账户标识
				$meminfo['accountId'] = $res['accountId'];
				$this->member_model->up_members_info($meminfo, $meminfo['uid']);
			} else {
				$this->error($res['msg']);
			}
		}
		
		//生成签章
		$res = $EsignAPI->addOrgTemplateSeal($meminfo);
		if(empty($res['errCode'])) {//返回印章base64图片
			$image = set_base64_image($res['imageBase64'], $meminfo['uid']);
			$meminfo['sealPath'] = $image;
			$this->member_model->up_members_info($meminfo, $meminfo['uid']);
			$this->error('印章已创建,路径为：' . $meminfo['sealPath']);
		} else {
			$this->error($res['msg']);
		}
	}
	/** 解密返回字符串 */
	// public function entrytype() {
		// $bbbbb = '{"merchantNo":"131010000011018","merOrderNo":"201808301112581869683470","jsonEnc":"c53727b54058fe60dca6ce89ca7ac196fda5e8fde52ac87da2e7ffe58df108bd39d2af45ef03210d5d3c04f4b88d793455bc890ebea5ac8eadad75a2b7faf00c55a6c5346344ae589e097921ad11b9eaad0bd7da3406548cf4a23ee1e8ef9755e20f6ac1653529754f2194404f633c09fd3599c6a461244361a49309481d8a99ed0dbebcdadfe678251720261c5db764b83228ad8c8ef9748bc5153c529d7ccab71cf7f47cc23dd4087ac7cfc98baefc44c2c3adf8504773af20e5af2a1f3649e091f8ede2a75ee5c2a7ccb649ef44afdc5af17711a39826072d87299c78b610c4d170fa2c5e0925b26150d4eb0b33fc4a61914d5a0ccc5b97d005864472546d0573fb200b008448ad1aabfef7fe32894225de95cf527463f7f37a983acf8d310730b9263d9dde38a09574ce8923c0cf","keyEnc":"dbc52727986ed522e8dbebf3de4a19e553d401df25c2a5b575cbeb39ad592d60a58b026434120196b26704d6e15bde6ee8b36e189598ac1a4ccbeb111f3feef2a1a68f900d7776dd67a6f8302df5f9195e2399ca8bcd1e05641d81d6e30ac1dc0b497535bae2c0e356e2c6b9d65375da63f6f253a6c441df71b6f2927ee1bb46f30646e5d28a1bd902b44260ffbfe0e2ba9c68205acfc60f738cf001d2f40887da0eea24fa8db3c1c3deb69ab4d6b11820793bb787122075f7fcaeb5087f891a7c18fbdaddbd519d53c27b8fe60cd91bc00c9201f631946c6ae6952f6866a86ce23e208dad8fee3857c18156cf9947076f3bbb663e4795514bfa0d37d5b068be","sign":"3bfcff70a481161267708caffa4d131a4c589c09ab11dc711514a1ef1d9794e2f120a08e022e655cc13bb185b26471ddea2c0ab1b79c01a1c7ebdc7ec1c6f37844c2d37d23f8a3ac77171bbe49be62d52c5fd49ea569157c44e3b3d4a0ab4949bef3fff7bed50a30bac88fb3d6f264dbd1e043f569bddd44084bb56842e4d67550378be9522294af3703b181bbf4fbe54b5be6705c1e9558a196a7643aeba1b0897f6ceca87f620b31c37652587d1264d072f2df8329253724c04610dbd674de5b452f66ad470d4c0012b5ec11f7db31cfa7d4106b8dc01631e9b89c36be4884dc50f83b0a0e9a04b099f42ed0a2fecbf5abe8a8cbf1261d486df0bd9efc5759"}';
		////parse_str($bbbbb, $arr);
		// $arr = json_decode($bbbbb, true);
		// $params['key'] = $arr['keyEnc'];
		// $params['bodys'] = $arr['jsonEnc'];
		// $params['sign'] = $arr['sign'];
		////p($params);
		// $data = decrypt($params);
		////echo $data;
		// $data = json_decode($data, true);
		// $data['value']['body'] = json_decode($data['value']['body'], true);
		// p($data);
	// }

}