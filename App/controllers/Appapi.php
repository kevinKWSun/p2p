<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header( "Access-Control-Allow-Origin:https://m.jiamanu.com");
header('Access-Control-Allow-Methods:POST,GET');
header('Access-Control-Allow-Headers:x-requested-with,content-type');
class Appapi extends Baseaccounts {
	public function __construct() {
		//echo '更新中...';die;
		parent::__construct();
		$this->load->library('pagination');
		$this->load->model(array('borrow/borrow_model', 'account/info_model', 'member/member_model', 'news/news_model','mall/cate_model'));
		$this->load->helper(array('url', 'common'));
	}
	public function banners(){
		$imgs = array(
			array('url' => '/images/0f73c9145b4445c383d1351c088884b3.png'),
			array('url' => '/images/93f652b3839f414d8ebc9c559e15f17b.png'),
		);
		$this->output
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($imgs))
			->_display();
		exit;
	}
	public function login(){
		if($this->input->post()){
			$data['name'] = $this->input->post_get('user_name', TRUE);
			$data['password'] = $this->input->post_get('user_pass', TRUE);
			if(! $data['name']){
				$info['state'] = 0;
				$info['message'] = '请输入手机号!';
				$this->output
			    //->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			if(! $data['password']){
				$info['state'] = 0;
				$info['message'] = '请输入密码!';
				$this->output
			    //->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			$result = $this->member_model->get_member_byusername($data['name']);
			if($result['is_ban'] == 1 || $result['type'] == 2){
	        	$info['state'] = 0;
				$info['message'] = '已被锁定,请联系客服人员!';
				$this->output
			    //->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
	        }
			if($result['user_pass'] != md5(suny_encrypt($data['password'], $result['salt']))){
				$co = $this->member_model->get_code('', $data['name'], 2);
				if(! $co || $co['code'] != $data['password'] || $co['time'] < time()){
					$info['state'] = 0;
					$info['message'] = '手机验证码已过期或密码不正确!';
					$this->output
					//->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($info))
					->_display();
					exit;
				}
			}
			$info = array(
				'state' => 1,
				'message' => '登录成功!',
				'user_name' => substr_replace($data['name'],'******',3,6),
				'user_id' => $result['id'],
			);
			$this->output
			    //->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
		}
	}
	//
	public function sendcode(){
		if($post = $this->input->post(NULL, TRUE)){
			$data['user_name'] = $post['user_name'];
			$type = $post['type'] ? $post['type'] : 2;
			if (! is_phone($data['user_name'])){
				$info['state'] = 0;
				$info['message'] = '请填写真实的手机号码!';
				$this->output
			    //->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			
			$datas['tel'] = $data['user_name'];
			$datas['type'] = $type;
			$datas['code'] = mt_rand(0,9) . mt_rand(0,9) . mt_rand(0,9) . mt_rand(0,9);
			$datas['time'] = time() + 300;
			if($type == 1){
				$datas['content'] = '(5分钟内有效) 您的注册码为：';
				if($this->member_model->get_member_byusername($data['user_name'])){
					$info['state'] = 0;
					$info['message'] = '手机号码已存被注册过!';
					$this->output
					//->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($info))
					->_display();
					exit;
				}
			}else{
				$datas['content'] = '(5分钟内有效) 您的登录码为：';
				if(! $member = $this->member_model->get_member_byusername($data['user_name'])){
					$info['state'] = 0;
					$info['message'] = '链接服务器失败!';
					$this->output
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($info))
					->_display();
					exit;
				}
			}
			if($this->member_model->add_code($datas)){
				if(send_sms($datas['tel'], $datas['content'], $datas['code'])){
					$info['state'] = 1;
					$info['message'] = '发送成功!';
					$this->output
					//->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($info))
					->_display();
					exit;
				}else{
					$info['state'] = 0;
					$info['message'] = '发送失败,请联系客服!';
					$this->output
					//->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($info))
					->_display();
					exit;
				}
			}else{
				$info['state'] = 0;
				$info['message'] = '发送失败,刷新后重试!';
				$this->output
			    //->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
		}
	}
	public function reg(){
		if($post = $this->input->post(NULL, TRUE)){
			$data['user_name'] = $post['phone'];
			$data['user_pass'] = $post['user_pass'];
			$code['pcode'] = $post['pcode'];
			$data['reg_time'] = time();
			$data['reg_ip'] = $this->input->ip_address();
			$data['salt'] = salt();
			$data['type'] = $post['type'] ? $post['type'] : 1;
			
			if (! is_phone($data['user_name'])){
				$info['state'] = 0;
				$info['message'] = '请填写真实的手机号码!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			if($this->member_model->get_member_byusername($data['user_name'])){
				$info['state'] = 0;
				$info['message'] = '手机号码已被注册过!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			if (! is_password($data['user_pass'])){
				$info['state'] = 0;
				$info['message'] = '密码必须由字母和数字组成!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			$co = $this->member_model->get_code('', $data['user_name'], 1);
			if(! $co || $co['code'] != $code['pcode'] || $co['time'] < time()){
				$info['state'] = 0;
				$info['message'] = '手机验证码已过期或不正确!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			if($post['codeuid']){
				$m_u = $this->member_model->get_m_bycodeuid($post['codeuid']);
				if($m_u){
					$data['codeuid'] = intval($post['codeuid']);
				}
			}
			$data['user_pass'] = MD5(suny_encrypt($data['user_pass'], $data['salt']));
			$this->db->trans_begin();
			$m = $this->member_model->add_member($data);
			$datam = array(
				'uid' => $m,
				'money_freeze' => '0.00',
				'money_collect' => '0.00',
				'account_money' => '0.00',
				//'ty_money' => '6600.00',
				'max_time' => time()+86400*30
			);
			$datas = array(
				'uid' => $m,
				'phone_status' => 1
			);
			$datai = array(
				'uid' => $m,
				'phone' => $data['user_name']
			);
			$this->member_model->add_members_money($datam);
			$this->member_model->add_member_status($datas);
			$this->member_model->add_member_info($datai);
			//红包及体验金操作如下, 2019-2-11，红包有效期修改为180天
			$packet = array(
				array(
					'uid' => $m,
					'stime' => time(),
					'etime' => time()+86400*180,
					'money' => '100.00',
					'min_money' => '8000.00',
					'times' => '30',
					'addtime' => time()
				),
				array(
					'uid' => $m,
					'stime' => time(),
					'etime' => time()+86400*180,
					'money' => '50.00',
					'min_money' => '3000.00',
					'times' => '30',
					'addtime' => time()
				),
				array(
					'uid' => $m,
					'stime' => time(),
					'etime' => time()+86400*180,
					'money' => '30.00',
					'min_money' => '1500.00',
					'times' => '30',
					'addtime' => time()
				),
				array(
					'uid' => $m,
					'stime' => time(),
					'etime' => time()+86400*180,
					'money' => '8.00',
					'min_money' => '300.00',
					'times' => '30',
					'addtime' => time()
				),
			);
			$this->member_model->addall_packet($packet);
			if($this->db->trans_status() === TRUE){
				$this->db->trans_commit();
				$info['state'] = 1;
				$info['message'] = '注册成功!';
				$info['url'] = '/setting.html';
				$info['user_name'] = substr_replace($data['user_name'],'******',3,6);
				$info['user_id'] = $m;
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
	}
	//
	public function novice() {
		$data['name'] = '新手指引';
		$data['state'] = 1;
		$data['con'] = $this->news_model->get_one_bycid(3);
		$this->output
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data))
			->_display();
			exit;
	}
	//我要出借
	public function invest() {
		$data['name'] = '我要出借';
		$data['state'] = 1;
		$data['con'] = $this->news_model->get_one_bycid(4);
		$this->output
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data))
			->_display();
			exit;
	}
	//名词解释
	public function noun() {
		$data['name'] = '名词解释';
		$data['state'] = 1;
		$data['con'] = $this->news_model->get_one_bycid(5);
		$this->output
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data))
			->_display();
			exit;
	}
	//常见问题
	public function question() {
		$data['name'] = '常见问题';
		$data['state'] = 1;
		$data['con'] = $this->news_model->get_one_bycid(6);
		$this->output
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data))
			->_display();
			exit;
	}
	public function info(){
		$QUID = $this->input->get('uid');
		if(! $QUID > 0){
			$data['state'] = 0;
			$data['message'] = '数据出错';
			$this->output
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data))
			->_display();
			exit;
		}
		$dssy = $this->info_model->get_sy_m($QUID);
		//$dsbj = $this->info_model->get_ds_m($QUID);
		$my = $this->info_model->get_money($QUID);
		$packets = $this->info_model->get_packets_money($QUID);
		$data['tm'] = $dssy['tml'] ? $dssy['tml']-$dssy['tm'] : '0';
		$data['ky'] = $my;
		//$data['totalAmount'] =  round(($my['money_collect'] +  $my['money_freeze'] + $my['account_money']),2);
		$data['totalAmount'] =  number_format(($my['money_collect'] +  $my['money_freeze'] + $my['account_money']),2,".",""); 
		//$data['ds'] = $dsbj['c'] - $dsbj['rc'];
		$data['packets'] = $packets['packets'] ? $packets['packets'] : 0;
		//$data['b'] = $this->member_model->get_bank_byuid('', $QUID, 1);
		$data['ljsy'] = $this->info_model->ljsy($QUID);
		$data['ljsy'] = $data['ljsy']['ljsy'] ? $data['ljsy']['ljsy'] : 0;
		$data['state'] = 1;
		$data['info'] = get_member_info($QUID);
		$this->output
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data))
			->_display();
			exit;
	}
	public function mfpassword(){
		if($m = $this->input->post(NULL, TRUE)){
			$ypass = $m['ypass'];
			if(! $m['uid'] > 0){
				$data['state'] = 0;
				$data['message'] = '数据出错';
				$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($data))
				->_display();
				exit;
			}
			$member = $this->member_model->get_member_byuserid($m['uid']);
			if($member['user_pass'] != md5(suny_encrypt($ypass, $member['salt']))){
	        	$info['state'] = 0;
				$info['message'] = '原密码不正确!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
	        }
			$newpassword = $m['newpass'];
			$data['user_pass'] = $m['pass'];
			if (! is_password($data['user_pass'])){
				$info['state'] = 0;
				$info['message'] = '密码必须由字母和数字组成!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			if($newpassword != $data['user_pass']){
				$info['state'] = 0;
				$info['message'] = '两次新密码不一致!';
				$this->output
				    ->set_content_type('application/json', 'utf-8')
				    ->set_output(json_encode($info))
					->_display();
				    exit;
			}
			if($ypass == $newpassword){
				$info['state'] = 0;
				$info['message'] = '新密码没有改变!';
				$this->output
				    ->set_content_type('application/json', 'utf-8')
				    ->set_output(json_encode($info))
					->_display();
				    exit;
			}
			$data['user_pass'] = MD5(suny_encrypt($data['user_pass'], $member['salt']));
			if($this->member_model->modify_member($data, $m['uid'])){
				$info['state'] = 1;
				$info['message'] = '操作成功!';
				$this->output
				    ->set_content_type('application/json', 'utf-8')
				    ->set_output(json_encode($info))
					->_display();
				    exit;
			}else{
				$info['state'] = 0;
				$info['message'] = '操作失败!';
				$this->output
				    ->set_content_type('application/json', 'utf-8')
				    ->set_output(json_encode($info))
					->_display();
				    exit;
			}
		}
	}
	//绑卡认证
	public function authentication() {
		$uid = $this->uri->segment(3) ? $this->uri->segment(3) : 0;
		$backurl = 'https://m.jiamanu.com/setting.html';
		$mem = $this->member_model->get_member_byuserid($uid);
		$meminfo = get_member_info($uid);
		//判断是否是手机号
		if(! is_phone($meminfo['phone'])) {
			exit("<center style='font-size:2rem;color:red;'>该手机号不能用于绑卡</center>");
		}
		$params['callbackUrl'] 		= 'https://www.jiamanu.com/paytest';
		$params['responsePath'] 	= $backurl;
		$params['registerPhone'] 	= $meminfo['phone'];
		$params['custType'] 		= $mem['type'] === '2' ? '03' : '00';
		$data = head($params, 'CG1044');
		water($uid, $data['merOrderNo'], 'CG1044');
		$data['url'] = $this->config->item('Interfaces').'1044';
		$this->load->view('account/jump', $data);
	}
	public function ischeck(){
		$QUID = $this->input->get('uid');
		if(! $QUID > 0){
			$data['state'] = 0;
			$data['message'] = '数据出错';
			$this->output
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data))
			->_display();
			exit;
		}
		$mstatus = $this->member_model->get_members_status_byuserid($QUID);
		if($mstatus['id_status'] != 1){
			$info['state'] = 0;
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($info))
				->_display();
				exit;
		}else{
			$info['state'] = 1;
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($info))
				->_display();
				exit;
		}
	}
	//红包
	public function packet(){
		$current_page = $this->input->get('p', TRUE) ? $this->input->get('p', TRUE) : 1;
		$uid = $this->input->get('uid', TRUE) ? $this->input->get('uid', TRUE) : 0;
        if($current_page > 0){
            $current_page = $current_page - 1;
        }else if($current_page < 0){
            $current_page = 0;
        }
		$status = $this->input->get('status', TRUE) ? $this->input->get('status', TRUE) : 0;
		if($status == 0){
			$data['status'] = '未使用';
		}elseif($status == 1){
			$data['status'] = '已使用';
		}elseif($status == 2){
			$data['status'] = '已过期';
		}
		$per_page = 8;
        $offset = $current_page;
        $config['base_url'] = base_url('appapi/packet/');
        $config['total_rows'] = $this->member_model->get_packets_nums($uid, $status);
        $config['per_page'] = $per_page;
		$config['page_query_string'] = FALSE;
		$config['first_link'] = '首页'; // 第一页显示   
		$config['last_link'] = '末页'; // 最后一页显示   
		$config['next_link'] = '下一页'; // 下一页显示   
		$config['prev_link'] = '上一页'; // 上一页显示   
		$config['cur_tag_open'] = ' <span class="current">'; // 当前页开始样式   
		$config['cur_tag_close'] = '</span>';   
		$config['num_links'] = 10;
		$config['uri_segment'] = 4;
		$config['use_page_numbers'] = TRUE;
		$config['reuse_query_string'] = TRUE;
        $this->pagination->initialize($config); 
        $data['totals'] = ceil($config['total_rows'] / $per_page);
        $data['p'] = $this->input->get('p', TRUE);
		// 2019-1-16， 红包修改
        //$packets = $this->member_model->get_packets($per_page, $offset * $per_page, $uid, $status);
		$packets = $this->member_model->get_member_packets($per_page, $offset * $per_page, $uid, $status);
		foreach($packets as &$val){
			if($val['remark'] == 1){
				$val['remark'] = "撤销"; 
			}
			if($val['remark'] == ''){
				$val['packet_state'] = 0;			
			}
			if($val['remark'] != ''){
				$val['packet_state'] = 1;			
			}
			if($val['addtime'] == $val['reg_time']){
				$val['packet_state'] = 2;
			}
		}
		if($packets){
			$data['state'] = 1;
		}else{
			$data['state'] = 0;
		}
		foreach($packets as $k => $v){
			$binfo = $this->borrow_model->get_borrow_info_bybid($v['bid']);
			if($binfo){
				$packets[$k]['name'] = '使用项目：' . $binfo['borrow_name'];
			}else{
				$packets[$k]['name'] = '';
			}
			$packets[$k]['etime'] = date('Y-m-d H:i',$v['etime']);
			$packets[$k]['days'] = intval(($v['etime']-time())/86400);
		}
		$data['packets'] = $packets;
		$this->output
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data))
			->_display();
			exit;
	}
	//充值
	public function recharge(){
		die;
		$QUID = $this->input->get('uid');
		if(! $QUID > 0){
			$data['state'] = 0;
			$data['message'] = '数据出错';
			$this->output
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data))
			->_display();
			exit;
		}
		$meminfo = $this->member_model->get_member_info_byuid($QUID);
		$data['integral'] = intval($meminfo['integral']);
		$data['my'] = $this->info_model->get_money($QUID);
		$data['b'] = $this->member_model->get_bank_byuid('', $QUID, 1);
		$data['state'] = 1;
		$this->output
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data))
			->_display();
			exit;
	}
	public function recharge_q() {
		die;
		//调用客户信息
		$QUID = $this->uri->segment(4);
		$meminfo = $this->member_model->get_member_info_byuid($QUID);
		$money = $this->uri->segment(3);
		$backurl = 'https://m.jiamanu.com/my_zijinmingxi.html';
		$money = round(floatval($money), 2) > 0 ? round(floatval($money), 2) : '0.00';
		$params['acctNo'] = $meminfo['acctNo'];
		$params['amount'] = $money;
		$params['incomeAmt'] = 0.00;
		$params['callbackUrl'] = $this->config->item('payapi')['callbackUrl'];
		$params['responsePath'] = $backurl;
		$data = head($params, 'CG1045');
		water($QUID, $data['merOrderNo'], 'CG1045', 0, $money);
		
		$data['url'] = $this->config->item('Interfaces').'1045';
		$this->load->view('account/jump', $data);
	}
	//提现
	public function withdraw(){
		$QUID = $this->input->get('uid');
		if(! $QUID > 0){
			$data['state'] = 0;
			$data['message'] = '数据出错';
			$this->output
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data))
			->_display();
			exit;
		}
		$data['state'] = 1;
		$data['my'] = $this->info_model->get_money($QUID);
		$data['today'] = 0;//$this->info_model->get_today_money($QUID);
		$data['b'] = $this->member_model->get_bank_byuid('', $QUID, 1);
		$this->output
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data))
			->_display();
			exit;
	}
	public function withdraw_tx() {
		//调用客户信息
		$QUID = $this->uri->segment(4);
		$meminfo = $this->member_model->get_member_info_byuid($QUID);
		$money = $this->uri->segment(3);
		$money = round(floatval($money), 2) > 0 ? round(floatval($money), 2) : '0.00';
		$type = $this->uri->segment(5);
		$backurl = 'https://m.jiamanu.com/my_zijinmingxi.html';
		if($money < 100) {
			exit("<center style='font-size:2rem;color:red;'>提现金额太小，不能操作</center>");
		}
		$my = $this->info_model->get_money($QUID);
		$today = $this->info_model->get_today_money($QUID);
		/* if($money > ($my['account_money']-$today['money'])) {
			exit("<center style='font-size:2rem;color:red;'>提现金额超出可提金额，不能操作</center>");
		} */
		//当天充值也可提现
		if($money > $my['account_money']) {
			exit("<center style='font-size:2rem;color:red;'>提现金额超出可提金额，不能操作</center>");
		}
		if($type == 1) {
			$params['acctNo'] = $meminfo['acctNo'];//账户号码
			$params['amount'] = $money;//提现金额
			$params['incomeAmt'] = round(2.00 + $money*0.05/100, 2);//$money < 100 ? 2.00 : 0.00;//收益金额
			$params['acctType'] = 'C001';//账户类型
		
			$params['callbackUrl'] = $this->config->item('payapi')['callbackUrl'];
			$params['responsePath'] = $backurl;
			$data = head($params, 'CG1046');
			water($QUID, $data['merOrderNo'], 'CG1046', 0, $money);
			
			$data['url'] = $this->config->item('Interfaces').'1046';
		} else if($type == 2) {//T+1
			$params['acctNo'] = $meminfo['acctNo'];//账户号码
			$params['amount'] = $money;//提现金额
			$params['incomeAmt'] = 0.00;//T+1手续费为0
			$params['acctType'] = 'C001';//账户类型
		
			$params['callbackUrl'] = $this->config->item('payapi')['callbackUrl'];
			$params['responsePath'] = $backurl;
			$data = head($params, 'CG1047');
			water($QUID, $data['merOrderNo'], 'CG1047', 0, $money);
			
			$data['url'] = $this->config->item('Interfaces').'1047';
		}
		$this->load->view('account/jump', $data);
	}
	public function score() {
		$this->load->model(array('score/score_model', 'borrow/borrow_model'));
		$data = array();
		$current_page = $this->input->get('p', TRUE) ? $this->input->get('p', TRUE) : 1;
		$uid = $this->input->get('uid', TRUE) ? $this->input->get('uid', TRUE) : 0;
		if($current_page > 0){
            $current_page = $current_page - 1;
        }else if($current_page < 0){
            $current_page = 0;
        }
		$per_page = 8;
        $offset = $current_page;
        $config['base_url'] = base_url('account/score');
        $config['total_rows'] = $this->score_model->get_score_num($uid);
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
        $data['score'] = $this->score_model->get_score($per_page, $offset * $per_page, $uid);
		if($data['score']){
			$data['state'] = 1;
		}else{
			$data['state'] = 0;
		}
		foreach($data['score'] as $k=>$v) {
			if($v['invest_id'] > 0) {
				$investor = $this->borrow_model->get_borrow_investor_byid('', $v['invest_id']);
				$borrow = $this->borrow_model->get_borrow_byid($v['bid']);
				$data['score'][$k]['infos'] = '投标['.$borrow['borrow_name'] . ']['. $investor[0]['investor_capital'] . '元]获得';
			}else{
				$data['score'][$k]['infos'] = '客服发放';
			}
			
			$data['score'][$k]['addtime'] = date('Y-m-d', $v['addtime']);
		}
		$this->output
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data))
			->_display();
			exit;
	}
	public function trade(){
		$current_page = $this->input->get('p', TRUE) ? $this->input->get('p', TRUE) : 1;
		$uid = $this->input->get('uid', TRUE) ? $this->input->get('uid', TRUE) : 0;
        if($current_page > 0){
            $current_page = $current_page - 1;
        }else if($current_page < 0){
            $current_page = 0;
        }
		$per_page = 8;
        $offset = $current_page;
        $config['base_url'] = base_url('trade/index');
        $config['total_rows'] = $this->info_model->get_moneylog_num($uid);
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
        $data['totals'] = ceil($config['total_rows'] / $per_page);
        $data['p'] = $this->input->get('p', TRUE);
        $moneylog = $this->info_model->get_moneylog($per_page, $offset * $per_page, $uid);
		if($moneylog){
			$data['state'] = 1;
		}else{
			$data['state'] = 0;
		}
		foreach($moneylog as $k => $v){
			$moneylog[$k]['type'] = $this->config->item('money_logs')[$v['type']];
			$moneylog[$k]['add_time'] = date('Y-m-d H:i',$v['add_time']);
		}
        $data['moneylog'] = $moneylog;
		$this->output
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data))
			->_display();
			exit;
	}
	#资金明细列表	
	public function trades($current_month = true,$uid = true){
		if(gettype($current_month) === "boolean"){
			$current_month = $this->input->get('p', TRUE) ? $this->input->get('p', TRUE) : 0;
			$uid = $this->input->get('uid', TRUE) ? $this->input->get('uid', TRUE) : 0;			
		}		
		if($current_month == 0){
		   $time['begin'] =  mktime(0, 0 , 0,date("m"),1,date("Y"));
           $time['end'] =  mktime(23,59,59,date("m")+1 ,0,date("Y"));
		}else{
           $m = $current_month -1;
           $time['begin'] = mktime(0, 0 , 0,date("m")-$current_month,1,date("Y"));
           $time['end'] = mktime(23,59,59,date("m")-$m ,0,date("Y"));
		}			
		$moneylog = $this->info_model->get_month_moneylog($time, $uid);		
		$month = date("Y-m",strtotime("-".$current_month ."month"));
		$end_month = date("Y-m",time());
		//时间限制
		$astrict_month = "2018-10";
		$time1 = strtotime($astrict_month);
		$time2 = strtotime($end_month);		
		$totals = 1;	
		while(($time1 = strtotime('+1 month', $time1)) <= $time2){
			$totals ++;
		}		
		$data['totals'] = $totals;
		if(strtotime($month) < strtotime($astrict_month)){
			$data['p'] = $current_month;
			$data['state'] = 1;
			$data['moneylog'] = "";
			$data['month'] = $month;
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($data))
				->_display();
				exit;
		}		
		if(!$moneylog){
			$current_month++;			
			$this->trades($current_month,$uid);//如果没有数据递归调用!
		}		
		//递归返回数据;
		if($moneylog){
			foreach($moneylog as $k => $v){
				$moneylog[$k]['type'] = $this->config->item('money_logs')[$v['type']];
				$moneylog[$k]['add_time'] = date('Y-m-d H:i',$v['add_time']);
				$moneylog[$k]['month'] = date('Y-m',$v['add_time']);				
			}
			$tem = array();	
			$data['p'] = $current_month;
			$data['state'] = 1;
			$data['month'] = $month;
			$data['moneylog'] = $moneylog;
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($data))
				->_display();
				exit;
		}
	}

	public function borrowlist(){
		$current_page = $this->input->get('p', TRUE) ? $this->input->get('p', TRUE) : 1;
		$uid = $this->input->get('uid', TRUE) ? $this->input->get('uid', TRUE) : 0;
        if($current_page > 0){
            $current_page = $current_page - 1;
        }else if($current_page < 0){
            $current_page = 0;
        }
		$status = $this->input->get('status', TRUE) ? $this->input->get('status', TRUE) : 2;
		if($status == 2){
			$data['status'] = '投标中';
			$data['type'] = 'tou';
			$data['name'] = '预计收益';
		}elseif($status == 4){
			$data['status'] = '回款中';
			$data['type'] = 'hui';
			$data['name'] = '剩余利息';
		}elseif($status == 5){
			$data['status'] = '已完成';
			$data['type'] = 'yi';
			$data['name'] = '回款利息';
		}
		$per_page = 8;
        $offset = $current_page;
        $config['base_url'] = base_url('record/index');
        $config['total_rows'] = $this->borrow_model->get_borrow_investor_num($status, $uid);
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
        $data['totals'] = ceil($config['total_rows'] / $per_page);
        $data['p'] = $this->input->get('p', TRUE);
        $borrow = $this->borrow_model->get_borrow_investor($per_page, $offset * $per_page, $status, $uid);
		foreach($borrow as $k => $v){
			if($v['add_time']){
				$borrow[$k]['deadline'] = date('Y-m-d',$v['add_time']);
			}else{
				$borrow[$k]['deadline'] = '';
			}
			$borrow[$k]['borrow_duration'] = $this->config->item('borrow_duration')[$v['borrow_duration']];
			$borrow[$k]['interests'] = round(($v['investor_interest']-$v['receive_interest']),2);
			$three = $this->borrow_model->get_investor_detail_byid_uid($v['invest_id'], $v['investor_uid']);
			if($three){
				$borrow[$k]['hktime'] = date('Y-m-d',$three['deadline']).' ('.$three['sort_order'].'/'.$three['total'].')';
				$borrow[$k]['hkmoneybx'] = intval($three['capital']).' / '.$three['interest'];
			}else{
				$borrow[$k]['hktime'] = '';
				$borrow[$k]['hkmoneybx'] = '放款后计算';
			}
			$borrow[$k]['borrow_interest_rate'] = $v['borrow_interest_rate'] + $v['add_rate'];
		}
        $data['borrow'] = $borrow;
		if($borrow){
			$data['state'] = 1;
		}else{
			$data['state'] = 0;
		}
		$this->output
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data))
			->_display();
			exit;
	}
	//首页
	public function hot(){
		$where['borrow_type >'] = 2;
		$where['borrow_status >='] = 2;
		$borrow = $this->borrow_model->get_borrows_bywhere($where, 5);
		foreach($borrow as $k=>$v){
			if($v['add_rate'] > 0){
				$borrow[$k]['interests'] = '+'.$v['add_rate'].'%';
			}else{
				$borrow[$k]['interests'] = '';
			}
			$borrow[$k]['pr'] = intval($v['has_borrow']/$v['borrow_money']*100);
			$borrow[$k]['borrow_type'] = $this->config->item('borrow_type')[$v['borrow_type']];
			$borrow[$k]['borrow_duration'] = $this->config->item('borrow_duration')[$v['borrow_duration']];
		}
		if($borrow){
			$data['state'] = 1;
		}else{
			$data['state'] = 0;
		}
		
		
		$data['totalmoney'] = $this->borrow_model->get_totalmoney();
		$data['totalmoney'] = $data['totalmoney'] ? $data['totalmoney']['totalmoney'] : 0;
		$data['totalmoney_s'] = round(($data['totalmoney']/10000 + 60199.8), 2);     //ADD			

		$data['totalmember'] = $this->borrow_model->get_totalmember();
		$data['totalmember_s'] = $data['totalmember'] + 42831;                     //ADD
		//$data['totalmember'] = $data['totalmember'] ? count(array_unique($data['totalmember'], SORT_REGULAR)) : 0;
		//$borrow['totalmember'] = $borrow['totalmember'] ? count(array_column($borrow['totalmember'], 'investor_uid')) : 0;
		$data['totalsmoney'] = $this->borrow_model->get_totalsmoney();
		$data['totalsmoney'] = $data['totalsmoney'] ? $data['totalsmoney']['totalsmoney'] : 0;
		$a = get_curl('http://www.jiamanu.cn/api2.aspx?action=getSYTotle');
		$a = str_replace(',','',json_decode($a, TRUE)['totle']);
		$data['totalsmoney'] = $data['totalsmoney'] + $a;
		//$data['totalsmoney'] = round($data['totalsmoney'], 2);                     //ADD
		$data['borrow'] = $borrow;
		$this->output
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data))
			->_display();
			exit;
	}
	//投标列表
	public function invests(){
		$current_page = $this->input->get('p', TRUE) ? $this->input->get('p', TRUE) : 1;
        if($current_page > 0){
            $current_page = $current_page - 1;
        }else if($current_page < 0){
            $current_page = 0;
        }
		$per_page = 8;
        $offset = $current_page;
        $config['base_url'] = base_url('appapi/invests');
        $config['total_rows'] = $this->borrow_model->get_borrow_num_index();
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
        $data['totals'] = ceil($config['total_rows'] / $per_page);
        $data['p'] = $this->input->get('p', TRUE);
        $borrow = $this->borrow_model->get_borrow_index($per_page, $offset * $per_page);
		foreach($borrow as $k => $v){
			$borrow[$k]['pr'] = intval($v['has_borrow']/$v['borrow_money']*100);
			$borrow[$k]['borrow_duration'] = $this->config->item('borrow_duration')[$v['borrow_duration']];
			if($v['has_borrow'] == $v['borrow_money']){
				if($v['borrow_status'] == 3){
					$borrow[$k]['name'] = '已满标';
				}elseif($v['borrow_status'] > 4){
					$borrow[$k]['name'] = '已结束';
				}else{
					$borrow[$k]['name'] = '还款中';
				}
			}else{
				$borrow[$k]['name'] = '立即投标';
			}
			
			$borrow[$k]['borrow_type'] = $this->config->item('borrow_type')[$v['borrow_type']];
			if($borrow[$k]['borrow_type'] == '新手标'){
				$borrow[$k]['borrow_type'] = '<font color=red>' . $borrow[$k]['borrow_type'] . '</font>';
			}
			if($v['add_rate'] > 0){
				$borrow[$k]['interests'] = '+'.$v['add_rate'].'%';
			}else{
				$borrow[$k]['interests'] = '';
			}
		}
        $data['borrow'] = $borrow;
		if($borrow){
			$data['state'] = 1;
		}else{
			$data['state'] = 0;
		}
		$this->output
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data))
			->_display();
			exit;
	}
	public function show(){
		$id = $this->input->get('bid', TRUE);
		$uid = $this->input->get('uid', TRUE);
		if(empty($id) || empty($uid)) {
			$data['state'] = 0;
			$data['message'] = '数据有误';
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($data))
				->_display();
				exit;
		}
		$data = $this->borrow_model->get_borrow_byid($id);
		$data['guarantee'] = str_replace('\\n', "", strip_tags($data['guarantee']));
		$data['payment'] =  str_replace('\\n', "", strip_tags($data['payment']));
		$meminfo = $this->member_model->get_member_info_byuid($data['borrow_uid']);
		$data['meminfo']['real_name'] = str_replace(mb_substr($meminfo['real_name'], 0, 6), '**', $meminfo['real_name']);
		if($data['add_rate'] > 0){
			if($data['borrow_interest_rate'] == intval($data['borrow_interest_rate'])){
				$data['borrow_interest_rate'] = intval($data['borrow_interest_rate']).'+'.$data['add_rate'];
			}
			$data['interests'] = '+'.$data['add_rate'];
		}else{
			if($data['borrow_interest_rate'] == intval($data['borrow_interest_rate'])){
				$data['borrow_interest_rate'] = intval($data['borrow_interest_rate']);
			}
			$data['interests'] = '';
		}
		$data['dtimes'] = date('Y-m-d H:i:s', $data['send_time']+$data['number_time']*86400);
		$data['borrow_duration'] = $this->config->item('borrow_duration')[$data['borrow_duration']];
		$data['pr'] = intval($data['has_borrow']/$data['borrow_money']*100);
		$data['borrow_info'] = strip_tags($data['borrow_info']);
		if($data['has_borrow'] == $data['borrow_money']){
			if($data['borrow_status'] == 3){
				$data['name'] = '已满标';
			}elseif($data['borrow_status'] > 4){
				$data['name'] = '已结束';
			}else{
				$data['name'] = '还款中';
			}
		}else{
			$data['name'] = '立即投标';
		}
		$data['borrow_type'] = $this->config->item('borrow_type')[$data['borrow_type']];
		/* if(!empty($data['pic'])) {
			$data['pic'] = explode(',', $data['pic']);
		} */
		$data['d_i'] = $this->borrow_model->get_borrow_investor_byid($id);
		foreach($data['d_i'] as $k=>$v) {
			$phone = get_member_info($v['investor_uid'])['phone'];
			$world = mb_substr($phone, 3, 5);
			$data['d_i'][$k]['investor_uid'] = str_replace($world, '***', $phone);
			$data['d_i'][$k]['add_time'] = date('Y-m-d H:i',$v['add_time']);
		}
		if($uid > 0) {
			$data['money'] = $this->info_model->get_money($uid);
			$data['packet'] = $this->member_model->get_packet_byuid($uid);
		}else{
			$data['money'] = 0;
			$data['packet'] = '';
		}
		$mstatus = $this->member_model->get_members_status_byuserid($uid);
		$data['id_status'] = $mstatus ? $mstatus['id_status'] : 0;
		$data['state'] = 1;
		//借款人信息
		$data['company_info'] = $this->member_model->get_company_info_byuid($data['borrow_uid']);
		$data['company_info']['credit'] = str_replace(mb_substr($data['company_info']['credit'], 2, 8), '**', $data['company_info']['credit']);
		$data['company_info']['reg_address'] = str_replace(mb_substr($data['company_info']['reg_address'], 5, 15), '**********', $data['company_info']['reg_address']);
		
		//担保人
		$this->load->model(array('guarantor/guarantor_model'));
		$data['guarantors'] = $this->guarantor_model->get_guarantor_more(explode(',', $data['guarantor']));
		foreach($data['guarantors'] as &$v){
			$v['name'] = str_replace(mb_substr($v['name'], 1), '**', $v['name']);
			$v['idcard'] = str_replace(mb_substr($v['idcard'], 3, 11), '**', $v['idcard']); 
		} 
		$this->output
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data))
			->_display();
			exit;
	}
	public function dozt(){
		if($p = $this->input->post(NULL, TRUE)){
			$bid = intval($p['bid']);//标的ID
			$redp  = intval($p['ddlRed']);//红包ID
			$money = round(floatval($p['TextBox1']), 2);//出借金额
			$uid = intval($p['uid']);//ID
			if(! $bid > 0){
				$this->error('服务器链接出错...');
			}
			if(empty($uid)) {
				$this->error('请登录后再操作...');
			}
			if($money < 0.001) {
				$this->error('出借金额不能为空');
			}
			$member = $this->member_model->get_member_byuserid($uid);
			if($member['type'] !== '1') {
				$this->error('借款用户不能投标');
			}
			$mymoney = $this->info_model->get_money($uid);
			$info = $this->borrow_model->get_borrow_byid($bid);
			// 判断是否有密码2019-5-17
			if(!empty($info['password'])) {
				if(!isset($p['pwd']) || ($p['pwd'] != $info['password'])) {
					$this->error('出借密码不正确，请修改后再提交');
				}
			}
			if($redp > 0) {
				$packet = $this->member_model->get_packet_byid($redp, $uid);
				if($packet['status'] > 0) {
					$this->error('红包已使用，不能重复使用');
				}
				if($packet['etime'] < time()) {
					$this->error('红包已过期');
				}
				$borrow_duration = $this->config->item('borrow_duration')[$info['borrow_duration']];
				if($borrow_duration < $packet['times']) {
					$this->error('标的期限不满足红包使用条件');
				}
				if($money < $packet['min_money']) {
					$this->error('出借金额不满足红包使用条件');
				}
				/** 只能使用注册红包，新手标 */
				if($info['borrow_type'] == '2') {
					if(!in_array($packet['money'], array(8, 30, 50, 100))) {
						$this->error('新手标只能使用注册红包');
					}
				}
			}
			//是否出借过新手标
			/* $new_num = $this->borrow_model->get_borrow_investor_type_num(2, $uid);
			if($new_num > 0 && $info['borrow_type'] === '2') {
				$this->error('新手标只能出借一次');
			} */
			if($info['borrow_type'] == '2') {
				$new_num = $this->borrow_model->is_invest_new($uid);
				if($new_num > 0) {
					$this->error('新手标只能出借一次');
				}
				if($money < 1000) {
					$this->error('新手标最小出借金额为1000元');
				}
				if($money > 50000) {
					$this->error('新手标最大出借金额为50000元');
				}
			}
			/** 500元标  需要添加只能投一次的功能*/
			if($info['borrow_type'] == '5') {
				if($money != 500) {
					$this->error('投标金额必须是500元');
				}
				if($redp > 0) {
					$this->error('该标的不能使用红包');
				}
			}
			if($money > ($info['borrow_money'] - $info['has_borrow'])) {
				$this->error('出借金额大于可投金额');
			}
			if(! $money > $info['borrow_min']){
				$this->error('出借金额小于最小出借金额...');
			}
			if(! $money > $info['borrow_max']){
				$this->error('出借金额大于最大出借金额...');
			}
			if($money > $mymoney['account_money']){
				$this->error('出借金额大于可用金额...');
			}
			$has = $info['has_borrow'] + $money;
			
			$last = $info['borrow_money'] - $has;
			if($last > 0 && $last <  $info['borrow_min']){
				$this->error('剩余金额不足最小出借金额...');
			}
			
			$merOrderNo = getTxNo20();
			water($uid, $merOrderNo, 'CG1052', $bid, $money, $redp);
			$this->success($merOrderNo);
		}
	}
	//////
	//出借协议
	public function contract() {
		$data = array();
		$data['borrow_name'] = '';
		$html = contract_build($data, 10);
		$save_path = html2pdf($html, array(), '');
	}
	//生成合同
	public function build() {
		$merOrderNo = $this->uri->segment(3);
		$data['uid'] = $this->uri->segment(4);
		$data['merOrderNo'] = $merOrderNo;
		$this->load->view('appapi/build', $data);
	}
	//借款合同
	public function build_one() {
		$merOrderNo = $this->uri->segment(3) ? $this->uri->segment(3) : 0;
		$data = $this->get_contract_info($merOrderNo);
		$html = contract_build($data, 10);
		//记录生成pdf合同的信息
		$this->load->model('contract/contract_model');
		$contract = $this->contract_model->get_contract_pdf_bynid($merOrderNo);
		
		//如果没有生成过pdf版本合同，要生成pdf版本合同，否则不需要生成
		if(empty($contract)) {
			$save_path = html2pdf($html, array('I', 'F'), '');
			$contract['nid'] = $merOrderNo;
			$contract['contract_num'] = $data['contract_num'];
			$contract['src_path'] = $save_path;
			$contract['addtime'] = time();
			$this->contract_model->modify_contract_pdf($contract);
		} elseif(empty($contract['src_path'])) {
			$save_path = html2pdf($html, array('I', 'F'), '');
			$contract['contract_num'] = $data['contract_num'];
			$contract['src_path'] = $save_path;
			$this->contract_model->modify_contract_pdf($contract);
		} else {
			html2pdf($html, array(), '');
		}
	}
	//居间合同
	public function build_two() {
		$merOrderNo = $this->uri->segment(3);

		$data = $this->get_contract_mediation_info($merOrderNo);
		//p($data);die;
		$html = contract_build($data, 11);
		
		//记录生成pdf合同的信息
		$this->load->model('contract/contract_model');
		$contract = $this->contract_model->get_contract_pdf_bynid($merOrderNo);
		//var_dump($contract);
		//如果没有生成过pdf版本合同，要生成pdf版本合同，否则不需要生成
		if(empty($contract)) {
			$save_path = html2pdf($html, array('I', 'F'), '');
			$contract['nid'] = $merOrderNo;
			//$contract['contract_num'] = $data['contract_num'];
			$contract['src1_path'] = $save_path;
			$contract['addtime'] = time();
			$this->contract_model->modify_contract_pdf($contract);
		} elseif(empty($contract['src1_path'])) {
			$save_path = html2pdf($html, array('I', 'F'), '');
			$contract['src1_path'] = $save_path;
			$this->contract_model->modify_contract_pdf($contract);
		} else {
			html2pdf($html, array(), '');
		}
		
	}
	//发送易签宝验证码
	public function send_code() {
		if(IS_POST) {
			$QUID = $this->input->post('uid', TRUE);
			$accountId = '';//账户标识
			//调取个人信息
			$meminfo = $this->member_model->get_member_info_byuid($QUID);
			$memstatus = $this->member_model->get_members_status_byuserid($QUID);
			if(empty($memstatus['id_status'])) {
				$this->error('还未实名认证，不能签署合同');
			}
			//如果没有创建账户，需要重新创建账户
			$this->load->library('EsignAPI/EsignAPI'); 
			$EsignAPI = new EsignAPI();
			if(empty($meminfo['accountId'])) {
				//创建个人账户
				$res = $EsignAPI->addPersonAccount($meminfo);
				//print_r($res);die;
				if(empty($res['errCode'])) {//返回账户标识
					$meminfo['accountId'] = $res['accountId'];
					$this->member_model->up_members_info($meminfo, $meminfo['uid']);
				} else {
					$this->error($res['msg']);
				}
			}
			
			//生成签章
			if(empty($meminfo['sealPath'])) {
				$res = $EsignAPI->addPersonTemplateSeal($meminfo);
				if(empty($res['errCode'])) {//返回印章base64图片
					$image = set_base64_image($res['imageBase64'], $meminfo['uid']);
					$meminfo['sealPath'] = $image;
					if(!$this->member_model->up_members_info($meminfo, $meminfo['uid'])) {
						$this->error('签章生成失败,请联系客户,客服电话021-62127903');
					}
				} else {
					$this->error($res['msg']);
				}
			}
			
			
			//发送验证码
			$res = $EsignAPI->sendCode($meminfo);
			$this->load->model('contract/contract_model');
			$this->contract_model->add_sendcode(array('uid'=>$QUID, 'code'=>'', 'addtime'=>time(), 'type'=>1));
			$this->error($res['msg']);
		}
	}
	//签署合同
	public function contract_submit() {
		if(IS_POST) {
			$post = $this->input->post(NULL, TRUE);
			if(empty($post['merOrderNo'])) {
				$this->error('信息错误，请重新投标');
			}
			if(! $post['agree_one']) {
				$this->error('必选选择同意并签署伽满借款合同');
			}
			if(! $post['agree_two']) {
				$this->error('必选选择同意并签署伽满借款合同');
			}
			if(empty($post['pcode'])) {
				$this->error('必须要有验证码');
			}
			
			$this->load->model(array('water/water_model', 'contract/contract_model', 'guarantor/guarantor_model'));
			$contract = $this->contract_model->get_contract_pdf_bynid($post['merOrderNo']);
			
			//判断是否生成过借款合同，如果没有，直接生成
			if(empty($contract)) {
				$data = $this->get_contract_info($post['merOrderNo']);
				$html = contract_build($data, 10);
				//如果没有生成过pdf版本合同，要生成pdf版本合同，否则不需要生成
				$save_path = html2pdf($html, array('F'), '');
				$contract['nid'] = $post['merOrderNo'];
				$contract['contract_num'] = $data['contract_num'];
				$contract['src_path'] = $save_path;
				$contract['addtime'] = time();
				
				$data = $this->get_contract_mediation_info($post['merOrderNo']);
				$html = contract_build($data, 11);
				$save_path = html2pdf($html, array('F'), '');
				$contract['src1_path'] = $save_path;
				
				$this->contract_model->modify_contract_pdf($contract);
			} elseif(empty($contract['src_path']) || empty($contract['src1_path'])) {
				if(empty($contract['src_path'])) {
					$data = $this->get_contract_info($post['merOrderNo']);
					$html = contract_build($data, 10);
					$save_path = html2pdf($html, array('F'), '');
					$contract['src_path'] = $save_path;
					$contract['contract_num'] = $data['contract_num'];
				}
				
				if(empty($contract['src1_path'])) {
					$data = $this->get_contract_mediation_info($post['merOrderNo']);
					$html = contract_build($data, 11);
					
					$save_path = html2pdf($html, array('F'), '');
					$contract['src1_path'] = $save_path;
				}
				$this->contract_model->modify_contract_pdf($contract);
			}
			
			
			//签署合同
			
			//调取合同信息
			$contract = $this->contract_model->get_contract_pdf_bynid($post['merOrderNo']);
			//调取流水信息
			$water = $this->water_model->get_water_byorder($post['merOrderNo']);
			if(empty($water)) {
				$this->error('信息有误，请重新投标');
			}
			//调取借款信息
			$borrow = $this->borrow_model->get_borrow_byid($water['bid']);
			//调取借款人签章
			$borrow_info = $this->member_model->get_member_info_byuid($borrow['borrow_uid']);
			//调取担保人签章
			if(!empty($borrow['guarantor'])) {
				$guarantor = $this->guarantor_model->get_guarantor_more(explode(',', $borrow['guarantor']));
			}
			//调取出借人签章
			$investor = $this->member_model->get_member_info_byuid($post['uid']);
			
			$this->load->library('EsignAPI/EsignAPI'); 
			$EsignAPI = new EsignAPI();
			
			//出借人签署
			$path = dirname(BASEPATH);
			if(empty($contract['des_path']) && empty($contract['des1_path'])) {
				$res = $EsignAPI->userMultiSignPDF($investor, $path, $post['pcode'], $contract);
				if(!empty($res['failList'])) {//报错
					$this->error($res['failList'][0]['msg']);
				} else {
					if(!empty($res['errCode'])) {//报错
						$this->error($res['msg']);
					} else {
						foreach($res['successList'] as $k=>$v) {
							if($contract['src_path'] == substr($v['filePath'], -58)) {
								$contract['des_path'] = substr($v['dstFilePath'], -58);
								$contract['signServiceId'] = $v['signServiceId'];
								$contract['signDetailUrl'] = $v['signDetailUrl'];
							} else {
								$contract['des1_path'] = substr($v['dstFilePath'], -58);
								$contract['signServiceId1'] = $v['signServiceId'];
								$contract['signDetailUrl1'] = $v['signDetailUrl'];
							}
						}
						$this->contract_model->modify_contract_pdf($contract);
						$this->contract_model->add_sendcode(array('uid'=>$water['uid'], 'code'=>$post['pcode'], 'addtime'=>time(), 'type'=>2));
					}
				}
			}
			
			//借款人签署
			//签署位置
			$signPos = array(
				'posPage' => count_pdf_pages($path . $contract['src_path']),//6,
				'posX' => 150,
				'posY' => 410,
				'key' => '',
				'width' => 150
			);
			$res = $EsignAPI->userSignPDF($borrow_info, $path, $contract['des_path'], $signPos);
			if(!empty($res['errCode'])) {
				$this->error($res['msg'] . 1);
			} else {
				$data_water = array(
					'uid' => $borrow['borrow_uid'],
					'nid' => $post['merOrderNo'],
					'path' => $res['des_path'],
					'signServiceId' => $res['signServiceId']
				);
				$this->contract_model->add_contract_water($data_water);
			}
			//要签署文档的路径
			$sign_path = $res['des_path'];
			if($guarantor){
				//担保人签署
				$signPos['posY'] = $signPos['posY'] - 62;
				$signPos['width'] = 50;
				$i = 0;
				$signPos['posX'] = 50;
				foreach($guarantor as $k=>$v) {
					$signPos['posX'] += 50;
					if($i == 10) {
						$signPos['posX'] = 50;
						$signPos['posY'] = $signPos['posY'] - 50;
					}
					$res = $EsignAPI->userSignPDF($v, $path, $sign_path, $signPos);
					if(!empty($res['errCode'])) {
						$this->error($res['msg'] . 2);
					} else {
						$data_water = array(
							'uid' => $v['id'],
							'nid' => $post['merOrderNo'],
							'path' => $res['des_path'],
							'signServiceId' => $res['signServiceId'],
							'type'	=> 1
						);
						$this->contract_model->add_contract_water($data_water);
					}
					$sign_path = $res['des_path'];
					$i++;
				}
			}
			//平台签署借款合同
			$signPos['posX'] = 150;
			$signPos['posY'] = $signPos['posY'] - 62;
			$signPos['width'] = 150;
			$res = $EsignAPI->selfSignPDF($path, $sign_path, $signPos);
			if(!empty($res['errCode'])) {
				$this->error($res['msg'] . 3);
			} else {
				$data_water = array(
					'uid' => 0,
					'nid' => $post['merOrderNo'],
					'path' => $res['des_path'],
					'signServiceId' => $res['signServiceId']
				);
				$this->contract_model->add_contract_water($data_water);
			}
			$sign_path = $res['des_path'];
			
			//平台签署居间合同
			$signPos = array(
				'posPage' => count_pdf_pages($path . $contract['src1_path']),//4,//
				'posX' => 260,
				'posY' => 720,
				'key' => '',
				'width' => 150
			);
			//echo $signPos['posPage'];die;
			$res = $EsignAPI->selfSignPDF($path, $contract['des1_path'], $signPos);
			if(!empty($res['errCode'])) {
				$this->error($res['msg'] . 4);
			} else {
				$contract = $this->contract_model->get_contract_pdf_bynid($post['merOrderNo']);
				$contract['pdf_path'] = $res['des_path'];
				$contract['path'] = $sign_path;
				$contract['uptime'] = time();
				$this->contract_model->modify_contract_pdf($contract);
			}
			
			//签署成功，跳转出借页面
			$url = '/appapi/toub/' . $post['merOrderNo'] . '.html';
			$this->success('签署成功', $url);
		}
	}
	//生成居间合同所需要的数据
	private function get_contract_mediation_info($merOrderNo) {
		if(empty($merOrderNo)) {
			exit('数据错误');
		}
		//时间戳
		$timestamp = time();
		//调取流水信息
		$this->load->model('water/water_model');
		$water = $this->water_model->get_water_byorder($merOrderNo);
		//借款信息
		$data = array();
		//$investor = $this->borrow_model->get_borrow_investor_by_id($id);
		$borrow = $this->borrow_model->get_borrow_byid($water['bid']);
		foreach($borrow as $k=>$v) {
			$data[$k] = $v;
		}
		
		//出借人信息
		$meminfos = $this->member_model->get_member_info_byuid($water['uid']);
		$data['investor_name'] = $meminfos['real_name'];
		$data['investor_idcard_type'] = '身份证';
		$data['investor_idcard'] = $meminfos['idcard'];
		//签署日期
		$data['y'] = date('Y', $timestamp);
		$data['m'] = date('m', $timestamp);
		$data['d'] = date('d', $timestamp);
		//签署日期
		$data['endtime'] = date('Y年m月d日', $timestamp);
		//签署地点
		$data['address1'] = '上海';
		$data['address2'] = '上海';
		
		return $data;
	}
	//生成借款合同所需要的数据
	private function get_contract_info($merOrderNo) {
		if(empty($merOrderNo)) {
			exit('数据错误');
		}
		//时间戳
		$timestamp = time();
		//调取流水信息
		$this->load->model('water/water_model');
		$water = $this->water_model->get_water_byorder($merOrderNo);
		//借款信息
		$data = array();
		//$investor = $this->borrow_model->get_borrow_investor_by_id($id);
		$borrow = $this->borrow_model->get_borrow_byid($water['bid']);
		if(empty($borrow)) {
			exit('连接出错，请重新投标');
		}
		foreach($borrow as $k=>$v) {
			$data[$k] = $v;
		}
		//借款人信息
		$meminfo = $this->member_model->get_member_info_byuid($data['borrow_uid']);
		$data['borrow_name'] = $meminfo['real_name'];
		$data['borrow_idcard_type'] = $borrow['type'] > 1 ? '营业执照号' : '身份证';
		$data['borrow_idcard'] = $meminfo['idcard'];
		$company_info = $this->member_model->get_company_info_byuid($data['borrow_uid']);
		$data['borrow_address'] = $borrow['type'] > 1 ? $company_info['reg_address'] : '';
		//出借人信息
		$meminfos = $this->member_model->get_member_info_byuid($water['uid']);
		$data['investor_name'] = $meminfos['real_name'];
		$data['investor_idcard_type'] = '身份证';
		$data['investor_idcard'] = $meminfos['idcard'];
		$data['investor_capital'] = $water['money'] . '元';
		//担保人
		if(!empty($borrow['guarantor'])) {
			$this->load->model('guarantor/guarantor_model');
			$guarantor = $this->guarantor_model->get_guarantor_more(explode(',', $borrow['guarantor']));
			$data['guarantor_html'] = '';
			$data['guarantor_names'] = '';
			$data['guarantor_idcard'] = '';
			foreach($guarantor as $k=>$v) {
				// if($k > 0) {
					// $data['guarantor_html'] .= '<strong>担保方：' . $v['name'] . '</strong></p><p>证件类型： 身份证&nbsp;</p><p>证件号码：' . $v['idcard'] . '&nbsp;</p><p>地址：' . $v['address'] . '&nbsp;</p><p><br />';
				// } else {
					// $data['guarantor_name'] = $v['name'];
					// $data['guarantor_idcard_type'] = '身份证';
					// $data['guarantor_idcard'] = $v['idcard'];
					// $data['guarantor_address'] = $v['address'];
				// }
				$data['guarantor_names'] .= $v['name'] . ', ';
				$data['guarantor_idcard'] .= $v['idcard'] . ', ';
			}
			$data['guarantor_idcard'] = rtrim($data['guarantor_idcard'], ', ');
			
			//测试数据
			// $data['guarantor_name'] = '刘素平, 刘素平, 刘素平, 刘素平, 刘素平, 刘素平, 刘素平, 刘素平, 刘素平, 刘素平, 刘素平, 刘素平, 刘素平, 刘素平, 刘素平, 刘素平, 刘素平, 刘素平, 刘素平, 刘素平';
			// $data['guarantor_idcard'] = '410221197112101342, 410221197112101342, 410221197112101342, 410221197112101342, 410221197112101342, 410221197112101342, 410221197112101342, 410221197112101342, 410221197112101342, 410221197112101342, 410221197112101342, 410221197112101342, 410221197112101342, 410221197112101342, 410221197112101342, 410221197112101342, 410221197112101342, 410221197112101342, 410221197112101342, 410221197112101342';
			
			
			
			$data['guarantor_idcard_type'] = '身份证';
			//$data['guarantor_names'] = $data['guarantor_name'];
			$data['guarantor_names'] = rtrim($data['guarantor_names'], ', ');
			$data['guarantor_name'] = $data['guarantor_names'];
		} else {
			$data['guarantor_html'] = '';
			$data['guarantor_name'] = '';
			$data['guarantor_idcard_type'] = '';
			$data['guarantor_idcard'] = '';
			$data['guarantor_address'] = '';
			$data['guarantor_names'] = '';
		}
		//借款信息
		//期数
		//$borrow['total'] = 
		$data['borrow_title'] = $borrow['borrow_name'];
		//$data['borrow_money'] = $borrow['borrow_money'] . '元';
		$data['borrow_duration'] = $this->config->item('borrow_duration')[$borrow['borrow_duration']] . '天';
		$data['borrow_repayment'] = $this->config->item('repayment_type')[$borrow['repayment_type']];
		$data['borrow_total'] = $borrow['total'] . '期';
		$data['borrow_rate'] = $borrow['borrow_interest_rate'] + $borrow['add_rate'];
		$data['borrow_endtime'] = date('Y年m月d日', $timestamp);
		$data['borrow_deadline'] = date('Y年m月d日', $timestamp + $this->config->item('borrow_duration')[$borrow['borrow_duration']]*86400);
		$data['borrow_use'] = $this->config->item('borrow_useid')[$borrow['borrow_useid']];
		$data['borrow_complex_rate'] = $borrow['borrow_interest_rate'] + $borrow['add_rate'] + 12;
		
		//计算公式
		$data['borrow_formula'] = '日利率=借款年化利率÷360×借款期限（天）÷借款期限内总天数';
		//服务费
		$data['fee_rate'] = $borrow['borrow_duration'] - 1;
		$data['fee_money'] = round($borrow['borrow_money'] * $data['fee_rate']/100, 2);
		//$data['fee_rate'] = '3';
		$data['fee_day'] = '3';
		//逾期还款
		$data['day_rate'] = '0.06';
		$data['day_penalty_rate'] = '0.06';
		
		//判断是否有合同号
		$this->load->model('contract/contract_model');
		$contract = $this->contract_model->get_contract_pdf_bynid($merOrderNo);
		if(!empty($contract)) {
			//判断合同日期必须是当天
			if(!empty($contract['contract_num']) && (substr($contract['contract_num'], 0, 8) != substr(date('YmdHis', $timestamp).$water['bid'].QUID, 0, 8))) {
				exit('合同出错，请重新投标');
			}
			if(!empty($contract['contract_num'])) {
				$data['contract_num'] = $contract['contract_num'];
			} else {
				$data['contract_num'] = date('YmdHis', $timestamp).$water['bid'].QUID;
			}
			
		} else {
			$data['contract_num'] = date('YmdHis', $timestamp).$water['bid'].QUID;
		}
		$data['subject_num'] = $borrow['subjectNo'];
		//签署日期
		$data['y'] = date('Y', $timestamp);
		$data['m'] = date('m', $timestamp);
		$data['d'] = date('d', $timestamp);
		//签署日期
		$data['endtime'] = date('Y年m月d日', $timestamp);
		//计息日
		$data['start_time'] = date('Y年m月d日', $timestamp + 86400);
		
		return $data;
	}
	/////
	public function toub() {
		$merOrderNo = $this->uri->segment(3);
		if(empty($merOrderNo)) {
			exit('<center>服务器链接出错...</center>');
		}
		$backurl = 'https://m.jiamanu.com/my_InvestList.html';
		$this->load->model(array('water/water_model'));
		$water = $this->water_model->get_water_byorder($merOrderNo);
		if(empty($water)) {
			exit('服务器链接出错....');
		}

		$mem = $this->member_model->get_member_byuserid($water['uid']);
		$meminfo = get_member_info($water['uid']);
		$info = $this->borrow_model->get_borrow_byid($water['bid']);
		
		$params['acctNo']			= $meminfo['acctNo'];
		$params['subjectNo']		= $info['subjectNo'];
		$params['amount']			= $water['money'];
		$params['callbackUrl'] 		= 'https://www.jiamanu.com/paytest';
		$params['responsePath'] 	= $backurl;
		$data = head($params, 'CG1052', 'transfer', $merOrderNo);
		$data['url'] = $this->config->item('Interfaces').'1052';
		$this->load->view('account/jump', $data);
	}
	//mall
	public function mall(){
		$current_page = $this->input->get('p', TRUE);
		$status = $this->input->get('query', TRUE) ? $this->input->get('query') : 0;
        if($current_page > 0){
            $current_page = $current_page - 1;
        }else if($current_page < 0){
            $current_page = 0;
        }
		$per_page = 8;
        $offset = $current_page;
        $config['base_url'] = base_url('mall/index');
        $config['total_rows'] = $this->cate_model->get_goods_num($status);
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
        $data['totals'] = ceil($config['total_rows'] / $per_page);
        $data['page'] = $this->pagination->create_links();
        $data['p'] = $current_page;
		$data['status'] = $status;
        $goods = $this->cate_model->get_goods($status, $per_page, $offset * $per_page);
        $data['goods'] = $goods;
		$data['cate'] = $this->cate_model->get_cates();
		$data['state'] = 1;
		$this->output
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data))
			->_display();
			exit;
	}
	public function mallList(){
		$current_page = $this->input->get('p', TRUE);
		$uid = $this->input->get('uid', TRUE);
        if($current_page > 0){
            $current_page = $current_page - 1;
        }else if($current_page < 0){
            $current_page = 0;
        }
		$per_page = 8;
        $offset = $current_page;
        $config['base_url'] = base_url('mall/lists');
        $config['total_rows'] = $this->cate_model->get_order_num($uid);
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
        $data['totals'] = ceil($config['total_rows'] / $per_page);
        $data['page'] = $this->pagination->create_links();
        $data['p'] = $current_page;
        $cate = $this->cate_model->get_order($uid, $per_page, $offset * $per_page);
		foreach($cate as $k=>$v){
			$cate[$k]['address'] = unserialize($v['amark'])['address'];
			$cate[$k]['ordername'] = $v['ordername']?$v['ordername']:'--';
			$cate[$k]['ordernum'] = $v['ordernum']?$v['ordernum']:'--';
			//$cate[$k]['uptime'] = $v['uptime']?date('Y-m-d',$v['uptime']):'--';
			$cate[$k]['status'] = $v['status']?'已发货':'待发货';
			$cate[$k]['type'] = $v['status']?'yi':'tou';
			if($v['uptime']){
				$cate[$k]['uptime'] = date('Y-m-d',$v['uptime']);
			}elseif($v['puptime']){
				$cate[$k]['uptime'] = $v['puptime'] ? date('Y-m-d',$v['puptime']) : '--';
			}else{
				$cate[$k]['uptime'] = '--';
			}
			$cate[$k]['addtime'] = $v['addtime']?date('Y-m-d',$v['addtime']):'--';
		}
        $data['cate'] = $cate;
		$data['state'] = 1;
		$this->output
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data))
			->_display();
			exit;
	}
	public function mall_addlist(){
		if($p = $this->input->post(NULL, TRUE)) {
			$data['gid'] = intval($p['id']);
			$data['num'] = intval($p['num']) ? intval($p['num']) : 1;
			$data['aid'] = intval($p['aid']) ? intval($p['aid']) : '00';
			$uid = intval($p['uid']);
			if(! intval($data['gid'])){
				$info['state'] = 0;
				$info['message'] = '商品数据有误!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			$goods = $this->cate_model->get_goods_one($data['gid']);
			if(! $goods){
				$info['state'] = 0;
				$info['message'] = '商品数据有误或已下架!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			if(! $data['num'] > 0){
				$info['state'] = 0;
				$info['message'] = '数量不能为空!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			if(! $goods['num'] > 0){
				$info['state'] = 0;
				$info['message'] = '库存不足,您可以兑换其他商品!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			if($goods['num'] < $data['num']){
				$info['state'] = 0;
				$info['message'] = '库存数量不足!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			if(! intval($data['aid'])){
				$info['state'] = 0;
				$info['message'] = '请选择地址!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			$address = $this->cate_model->get_address_one($data['aid'], $uid);
			if(! $address){
				$info['state'] = 0;
				$info['message'] = '地址不存在!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			$total = round($goods['score'] * $data['num'], 2);
			if($total > get_member_info($uid)['totalscore']){
				$info['state'] = 0;
				$info['message'] = '可用积分不够,请修改兑换数量!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			//
			$data['sscore'] = $total;
			$data['amark'] = serialize($address);
			$data['addtime'] = time();
			$data['uid'] = $uid;
			$this->db->trans_begin();
			if($data['gid'] == 11){
				$data['status'] = 1;
				$data['puptime'] = time();
				$this->cate_model->up_userinfo_times($uid, $data['num']);
			}
			$this->cate_model->add_order($data);
			$this->cate_model->up_goods_num($data['gid'], $data['num']);
			$this->cate_model->up_userinfo_score($uid, $total);
			if ($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
				$info['state'] = 0;
				$info['message'] = '操作失败!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}else{
				$this->db->trans_commit();
				$info['state'] = 1;
				$info['message'] = '操作成功!';
				$info['url'] = '/mall/lists.html';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
		}
		$uid = $this->input->get('uid', TRUE);
		$gid = $this->input->get('gid', TRUE);
		$d['state'] = 1;
		$d['info'] = get_member_info($uid);
		$d['address'] = $this->cate_model->get_address_more($uid);
		$d['goods'] = $this->cate_model->get_goods_one($gid);
		$d['state'] = 1;
		$this->output
		->set_content_type('application/json', 'utf-8')
		->set_output(json_encode($d))
		->_display();
		exit;
	}
	public function mall_address(){
		$current_page = $this->input->get('p', TRUE);
		$uid = $this->input->get('uid', TRUE);
        if($current_page > 0){
            $current_page = $current_page - 1;
        }else if($current_page < 0){
            $current_page = 0;
        }
		$per_page = 8;
        $offset = $current_page;
        $config['base_url'] = base_url('mall/address');
        $config['total_rows'] = $this->cate_model->get_address_num($uid);
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
        $data['totals'] = ceil($config['total_rows'] / $per_page);
        $data['page'] = $this->pagination->create_links();
        $data['p'] = $current_page;
        $address = $this->cate_model->get_address($uid, $per_page, $offset * $per_page);
        $data['address'] = $address;
		$data['state'] = 1;
		$this->output
		->set_content_type('application/json', 'utf-8')
		->set_output(json_encode($data))
		->_display();
		exit;
	}
	public function mall_add(){
		if($p = $this->input->post(NULL, TRUE)) {
			$data['tel'] = $p['tel'];
			$data['address'] = $p['address'];
			$data['realname'] = $p['realname'];
			$data['uid'] = $p['uid'];
			if(! $data['realname']){
				$info['state'] = 0;
				$info['message'] = '真实姓名不能为空!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			if(! is_phone($data['tel'])){
				$info['state'] = 0;
				$info['message'] = '手机号不正确!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			if(! $data['address']){
				$info['state'] = 0;
				$info['message'] = '地址不能为空!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			if($this->cate_model->get_address_one('', $data['uid'], $data['address'])){
				$info['state'] = 0;
				$info['message'] = '地址已存在!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			$data['addtime'] = time();
			if($this->cate_model->add_address($data)){
				$info['state'] = 1;
				$info['message'] = '操作成功!';
				$info['url'] = '/mall/address.html';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}else{
				$info['state'] = 0;
				$info['message'] = '操作失败!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
		}
	}
	public function mall_edit(){
		if($p = $this->input->post(NULL, TRUE)) {
			$id = $p['id'];
			$data['tel'] = $p['tel'];
			$data['address'] = $p['address'];
			$data['realname'] = $p['realname'];
			$data['uid'] = $p['uid'];
			$a = $this->cate_model->get_address_one($id, $data['uid']);
			if(! $a || ! $id){
				$info['state'] = 0;
				$info['message'] = '数据有误,请刷新后再试!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			if(! $data['realname']){
				$info['state'] = 0;
				$info['message'] = '真实姓名不能为空!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			if(! is_phone($data['tel'])){
				$info['state'] = 0;
				$info['message'] = '手机号不正确!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			if(! $data['address']){
				$info['state'] = 0;
				$info['message'] = '地址不能为空!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			if($a['address'] != $data['address']  && $b = $this->cate_model->get_address_one('', $data['uid'], $data['address'])){
				$info['state'] = 0;
				$info['message'] = '地址已存在!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			$data['addtime'] = time();
			if($this->cate_model->up_address($data, $id, QUID)){
				$info['state'] = 1;
				$info['message'] = '操作成功!';
				$info['url'] = '/mall/address.html';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}else{
				$info['state'] = 0;
				$info['message'] = '操作失败!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
		}
		$id = $this->input->get('id', TRUE);
		$uid = $this->input->get('uid', TRUE);
		$d = $this->cate_model->get_address_one($id, $uid);
		$d['state'] = 1;
		$this->output
		->set_content_type('application/json', 'utf-8')
		->set_output(json_encode($d))
		->_display();
		exit;
	}
}