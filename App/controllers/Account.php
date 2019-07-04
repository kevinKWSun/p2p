<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Account extends Baseaccount {
	public function __construct() {
		parent::__construct();
		$this->load->library('pagination');
		$this->load->model(array('account/info_model', 'member/member_model', 'zcard/zcard_model'));
		//$this->load->helper('url');
	}
	//首页
	public function index(){
		$dssy = $this->info_model->get_sy_m(QUID);
		$dsbj = $this->info_model->get_ds_m(QUID);
		$my = $this->info_model->get_money(QUID);
		$packets = $this->info_model->get_packets_money(QUID);
		$data['tm'] = $dssy['tml'] ? $dssy['tml']-$dssy['tm'] : '0';
		$data['ky'] = $my;
		$data['ds'] = $dsbj['c'] - $dsbj['rc'];
		$data['packets'] = $packets['packets'] ? $packets['packets'] : 0;
		$data['b'] = $this->member_model->get_bank_byuid('', QUID, 1);
		
		$data['month'] = date('m',time());
		$data['investor'] = $this->info_model->get_investor_month_info(QUID,date('Y-m',time()));
		$data['current'] = $this->info_model->get_investor_current_info(QUID,strtotime(date("Y-m-01")),time());
		$card = $this->zcard_model->get_by_uid(QUID);
		$data['totals'] = $card['total'];
		$data['cards'] = $card['card1']+$card['card2']+$card['card3']+$card['card4']+$card['card5']+$card['card6']+$card['card7']+$card['card8']+$card['card9']+$card['card10'];
		$this->load->view('account/accounts_v1', $data);
	}
	
	//获取回款日期
	public function get_investor_detail(){
		$data = $this->info_model->get_investor_detail_date(QUID);
		$info = [];
		foreach($data as $val){
			$info[$val['times']] = sprintf("%.0f", date('d',strtotime($val['times'])));			
		}		
		$this->output
		->set_content_type('application/json', 'utf-8')
		->set_output(json_encode($info))
		->_display();
		exit;
	}
	
	//根据时间获取当日数据
	public function get_investor_detail_day(){
		$days = $this->input->get('times', TRUE);
		$data = $this->info_model->get_investor_detail_days(QUID,$days);
		$result = strtotime($days)-strtotime(date("Y-m-d"));
		$before_total = $before_capital = $after_total = $after_capital = 0;
		foreach($data as $val){
			$after_total += $val['capital']+$val['interest']; //总金额 利息+本金 当天回款累计
			$after_capital += $val['capital'];                //金额 本金 当天回款累计
			$before_total += $val['receive_capital']+$val['receive_interest'];
			$before_capital += $val['receive_capital'];
		}
		if($result < 0){
			$info['total'] = $before_total;
			$info['capital'] = $before_capital;
		}else{
			$info['total'] = $after_total;
			$info['capital'] = $after_capital;
		}
		$info['sql'] = $this->db->last_query();
		$this->output
		->set_content_type('application/json', 'utf-8')
		->set_output(json_encode($info))
		->_display();
		exit;
	}
	
	//上传银行卡图片
	//上传银行卡图片
	public function upload_card_img(){
		$dir = iconv('UTF-8', 'GBK', './code/' . date('Ymd'));
		if(! file_exists($dir)){
			mkdir ($dir,0777,true);
		}
		$config['allowed_types']    = 'gif|jpg|png';
		$config['max_size']         = 5 * 1024;
        $config['allowed_types']    = 'gif|jpg|png';
		$config['upload_path']      = './code/' . date('Ymd');
	    $this->load->library('upload', $config);
        if($this->upload->do_upload('file')){
			$data = array('upload_data' => $this->upload->data());
			$config['image_library'] = 'gd2';
            $config['source_image'] = './code/' . date('Ymd') . '/' . $data['upload_data']['file_name'];
			$config['create_thumb'] = FALSE;
			$config['maintain_ratio'] = TRUE;
			$config['width']     = 800;
			//$config['height']   = 150;
			
			if($this->uri->segment(3) != 1) {
				$this->load->library('image_lib', $config);
				$ret_resize = $this->image_lib->resize();
			} else {
				$ret_resize = true;
			}
			$new_name = date('Ymdhis').rand(111,999) . $data['upload_data']['file_ext'];
			$new_path = $data['upload_data']['file_path'] . $new_name;
			if(!rename($data['upload_data']['file_path'] . $data['upload_data']['file_name'], $new_path)) {
				$info = array('code'=>0,'msg'=>'重命名失败.');
				$this->output
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($info))
					->_display();
					exit;
			}
			$data['upload_data']['file_name'] = $new_name;
			if ( ! $ret_resize){
				$info = array('code'=>0,'msg'=>'压缩失败.');
				//echo $this->image_lib->display_errors();
			}else{
				$info = array('code'=>200,'data'=>'./code/' . date('Ymd') . '/' . $data['upload_data']['file_name'], 'msg'=>'上传成功');
			}
        }else{
            $info  =array('code' => 0,'msg' => '上传失败！','data' => $path);
        }
		$this->output
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($info))
			->_display();
			exit;		
	}
	// public function upload_card_img(){
		// $dir = iconv('UTF-8', 'GBK', './code/' . date('Ymd'));
		// if(! file_exists($dir)){
			// mkdir ($dir,0777,true);
		// }
		// $config['allowed_types']    = 'gif|jpg|png';
		// $config['max_size']         = 5 * 1024;
        // $config['allowed_types']    = 'gif|jpg|png';
		// $config['upload_path']      = './code/' . date('Ymd');
		// $config['file_name']        = "/".date('Ymdhis').rand(111,999).".png";        
		// $path = $config['upload_path'].$config["file_name"];
	    // $this->load->library('upload', $config);
        // if($this->upload->do_upload('file')){
            // $info = array('code' => 200,'msg' => '上传成功！','data' => $path);
        // }else{
            // $info  =array('code' => 0,'msg' => '上传失败！','data' => $path);
        // }
		// $this->output
			// ->set_content_type('application/json', 'utf-8')
			// ->set_output(json_encode($info))
			// ->_display();
			// exit;		
	// }
	
	public function get_upbind_img(){
		$data = $this->member_model->get_upbind_status_img(QUID);
		if($data == null){
			$info  =array('status' => 'null','msg' => '没有数据！');
		}else{
			$info  =array('status' => $data['status'],'msg' => '获取数据成功！');	
		}
		$this->output
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($info))
			->_display();
			exit;	
	}

	
	/** 新增 修改银行卡*/
	public function upbinds() {
		if(IS_POST) {
			$post = $this->input->post(NULL, TRUE);
			if($post['hand_identity_card'] =='' || $post['hand_bank_card'] ==''){
				$info  =array('code' => 0,'msg' => '未按要求上传图片！');
				$this->output
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($info))
					->_display();
					exit;	
			}			

			$post['uid'] = QUID;
			$post['addtime'] = time();
		
			$result = $this->member_model->add_upbind_img($post);		
			if($result){     
				$info = array('code' => 200,'msg' => '添加成功！');
			}else{
				$info  =array('code' => 0,'msg' => '添加失败！');
			}
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($info))
				->_display();
				exit;		
		}else{			
			$this->load->view('account/upbinds_v1');
		}
	}
	
	/** 积分 */
	public function score() {
		$this->load->model(array('score/score_model', 'borrow/borrow_model'));
		$data = array();
		$current_page = intval($this->uri->segment(3)) ? intval($this->uri->segment(3)) - 1 : 0;
		//$status = intval($this->input->get('status', TRUE));
		//$data['status'] = $status ? '已使用' : '已发放';
		
		$per_page = 8;
        $offset = $current_page;
        $config['base_url'] = base_url('account/score');
        $config['total_rows'] = $this->score_model->get_score_num(QUID);
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
        $data['score'] = $this->score_model->get_score($per_page, $offset * $per_page, QUID);
		foreach($data['score'] as $k=>$v) {
			if($v['invest_id'] > 0) {
				$data['score'][$k]['investor'] = $this->borrow_model->get_borrow_investor_byid('', $v['invest_id']);
				$data['score'][$k]['borrow'] = $this->borrow_model->get_borrow_byid($v['bid']);
			}
		}
		//p($data['score']);
		$this->load->view('account/score', $data);
	}
	
	//红包
	public function packet(){
		$current_page = $this->uri->segment(3) ? $this->uri->segment(3) : 1;
        if($current_page > 0){
            $current_page = $current_page - 1;
        }else if($current_page < 0){
            $current_page = 0;
        }
		$status = $this->input->get('status', TRUE);
		if(! $status){
			$status = 0;
		}
		if($status == 0){
			$data['status'] = '未使用';
		}elseif($status == 1){
			$data['status'] = '已使用';
		}elseif($status == 2){
			$data['status'] = '已过期';
		}
		$per_page = 8;
        $offset = $current_page;
        $config['base_url'] = base_url('account/packet/');
        $config['total_rows'] = $this->member_model->get_packets_nums(QUID, $status);
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
        $data['packets'] = $this->member_model->get_packets($per_page, $offset * $per_page, QUID, $status);
		$this->load->view('account/packet_v1', $data);
	}
	//红包使用说明
	public function instructions() {
		$data['status'] = '使用说明';
		$this->load->view('account/instructions', $data);
	}
	//充值
	public function recharge(){
		die;
		//查询是否有调查表表积分
		$meminfo = $this->member_model->get_member_info_byuid(QUID);
		$data['my_user'] = $this->info_model->get_members_quickbank(QUID);
		if(empty($meminfo['integral'])) {
			$data['meminfo'] = $meminfo;
			$this->load->view('account/integral', $data);
		} else {
			$data['my'] = $this->info_model->get_money(QUID);
			$this->load->view('account/recharge_v1', $data);
		}
		
	}
	public function integral() {
		die;
		if(IS_POST) {
			$post = $this->input->post(NULL, TRUE);
			for($i = 1; $i <= 15; $i++) {
				if(!isset($post['score'][$i])) {
					$this->error('第' . $i . '题必须要选择');
				}
			}
			
			//总分数
			$score = 0;
			//循环添加分数
			foreach($post['score'] as $k=>$v) {
				$score += $v;
			}
			//插入数据表
			$data = $this->member_model->get_member_info_byuid(QUID);
			$data['integral'] = $score;
			$this->member_model->up_members_info($data, QUID);
			$data = array();
			$data['uid'] = QUID;
			$data['score'] = $score;
			$data['result'] = serialize($post);
			$data['addtime'] = time();
			$this->load->model('integral/integral_model');
			$this->integral_model->add_integral($data);
			$this->success('操作成功');
		}
		$data['meminfo'] = $this->member_model->get_member_info_byuid(QUID);
		if($data['meminfo']['integral'] >= 45 && $data['meminfo']['integral'] <= 60) {
			$data['type'] = 1;
			$data['fanwei'] = '200-1000万';
		} else if($data['meminfo']['integral'] >= 30 && $data['meminfo']['integral'] <= 44) {
			$data['type'] = 2;
			$data['fanwei'] = '50-200万';
		} else if($data['meminfo']['integral'] >= 15 && $data['meminfo']['integral'] <= 29) {
			$data['type'] = 3;
			$data['fanwei'] = '0-50万';
		} else {
			$data['fanwei'] = '调查错误，请联系客服';
		}
		
		$this->load->view('account/integral_result', $data);
	}
	//提现
	public function withdraw(){
		$data['my'] = $this->info_model->get_money(QUID);
		$data['my_user'] = $this->info_model->get_members_quickbank(QUID);
		$data['today'] = 0;//$this->info_model->get_today_money(QUID);
		$this->load->view('account/withdraw_v1', $data);
	}
	public function codeid(){
		if($m = $this->input->post(NULL, TRUE)){
			$data['real_name'] = trim($m['realname']);
			$data['idcard'] = trim($m['idcode']);
			if(strlen($data['real_name']) < 3){
				$info['state'] = 0;
				$info['message'] = '真实姓名不正确!';
				$this->output
				    ->set_content_type('application/json', 'utf-8')
				    ->set_output(json_encode($info))
					->_display();
				    exit;
			}
			if(! idcard_checksum18($data['idcard'])){
				$info['state'] = 0;
				$info['message'] = '身份证号不正确!';
				$this->output
				    ->set_content_type('application/json', 'utf-8')
				    ->set_output(json_encode($info))
					->_display();
				    exit;
			}
			$this->db->trans_begin();
			$this->member_model->up_members_info($data, QUID);
			$this->member_model->up_members_status(array('id_status' => 1), QUID);
			if($this->db->trans_status() === TRUE){
				$this->db->trans_commit();
				$info['state'] = 1;
				$info['message'] = '操作成功!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}else{
				$this->db->trans_rollback();
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
	public function tel(){
		if($m = $this->input->post(NULL, TRUE)){
			$data['phone'] = trim($m['phone']);
			$code = $m['phoneVerifyCode'];
			if(! is_phone($data['phone'])){
				$info['state'] = 0;
				$info['message'] = '手机格式不正确!';
				$this->output
				    ->set_content_type('application/json', 'utf-8')
				    ->set_output(json_encode($info))
					->_display();
				    exit;
			}
			$info = $this->member_model->get_member_info_byuid(QUID);
			if($data['phone'] == $info['phone']){
				$info['state'] = 0;
				$info['message'] = '手机号没有改变!';
				$this->output
				    ->set_content_type('application/json', 'utf-8')
				    ->set_output(json_encode($info))
					->_display();
				    exit;
			}
			if($code != '000'){
				$info['state'] = 0;
				$info['message'] = '验证码不正确!';
				$this->output
				    ->set_content_type('application/json', 'utf-8')
				    ->set_output(json_encode($info))
					->_display();
				    exit;
			}
			$this->db->trans_begin();
			$this->member_model->up_members_info($data, QUID);
			$this->member_model->modify_member(array('user_name' => $data['phone']), QUID);
			if($this->db->trans_status() === TRUE){
				$this->db->trans_commit();
				$info['state'] = 1;
				$info['message'] = '操作成功!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}else{
				$this->db->trans_rollback();
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
	
	/** 认证页面 绑卡 */
	public function authentication() {
		if(empty(QUID)) {
			$this->error('请登录后操作');
		}
		$mem = $this->member_model->get_member_byuserid(QUID);
		$meminfo = get_member_info(QUID);
		
		//判断是否是手机号
		if(!is_phone($meminfo['phone'])) {
			$this->error('该手机号不能用于绑卡');
		}
		
		$params['callbackUrl'] 		= $this->config->item('payapi')['callbackUrl'];
		$params['responsePath'] 	= $this->config->item('payapi')['response'];
		$params['registerPhone'] 	= $meminfo['phone'];
		$params['custType'] 		= $mem['attribute'] === '2' ? '03' : '00';
		$data = head($params, 'CG1044');
		water(QUID, $data['merOrderNo'], 'CG1044');
		
		$data['url'] = $this->config->item('Interfaces').'1044';
		$this->load->view('account/jump', $data);
	}
	
	/** 重置交易密码 */
	public function reset_trade_password() {
		if(empty(QUID)) {
			$this->error('请登录后操作');
		}
		$meminfo = $this->member_model->get_member_info_byuid(QUID);
		
		$params['custNo'] = $meminfo['custNo'];
		$params['callbackUrl'] = $this->config->item('payapi')['callbackUrl'];
		$params['responsePath'] = 'https://www.jiamanu.com/safe.html';
		$data = head($params, 'CG1055');
		water(QUID, $data['merOrderNo'], 'CG1055');
		
		$data['url'] = $this->config->item('Interfaces').'1055';
		$this->load->view('account/jump', $data);
	}
	/** 修改交易密码 */
	public function repass_trade_password() {
		if(empty(QUID)) {
			$this->error('请登录后操作');
		}
		$meminfo = $this->member_model->get_member_info_byuid(QUID);
		
		$params['custNo'] = $meminfo['custNo'];
		$params['pswordCode'] = '01';
		$params['callbackUrl'] = $this->config->item('payapi')['callbackUrl'];
		$params['responsePath'] = 'https://www.jiamanu.com/safe.html';
		$data = head($params, 'CG1048');
		water(QUID, $data['merOrderNo'], 'CG1048');
		
		$data['url'] = $this->config->item('Interfaces').'1048';
		$this->load->view('account/jump', $data);
	}
	
	/** 回调页面 */
	public function response() {
		redirect('https://www.jiamanu.com/account.html');
		//$this->load->view('account/success');
	}
	
	/** 快捷充值 */
	public function recharge_q() {
		die;
		//调用客户信息
		$meminfo = $this->member_model->get_member_info_byuid(QUID);
		$money = $this->uri->segment(3);
		$money = round(floatval($money), 2) > 0 ? round(floatval($money), 2) : '0.00';
		$params['acctNo'] = $meminfo['acctNo'];
		$params['amount'] = $money;
		$params['incomeAmt'] = 0.00;
		$params['callbackUrl'] = $this->config->item('payapi')['callbackUrl'];
		$params['responsePath'] = $this->config->item('payapi')['response'];
		$data = head($params, 'CG1045');
		water(QUID, $data['merOrderNo'], 'CG1045', 0, $money);
		
		$data['url'] = $this->config->item('Interfaces').'1045';
		$this->load->view('account/jump', $data);
	}
	
	/** 网关充值 */
	public function recharge_wg() {
		die;
		//调用客户信息
		$meminfo = $this->member_model->get_member_info_byuid(QUID);
		$money = $this->uri->segment(3);
		$money = round(floatval($money), 2) > 0 ? round(floatval($money), 2) : '0.00';
		$params['uaType'] = '00';//客户端类型
		$params['acctNo'] = $meminfo['acctNo'];//账户号码
		$params['acctType'] = IS_COMPANY ? 'C002' : 'C001';//账户类型
		$params['prodInfo'] = '账户充值';//商品信息
		$params['orderDesc'] = '账户充值';//订单描述
		//p($params);die;
		$params['amount'] = $money;
		$params['currency'] = 'CNY';//币种
		$params['merNotifyUrl'] = $this->config->item('payapi')['notify']; //通知地址
		$params['merBackUrl'] = $this->config->item('payapi')['response'];
		$data = head($params, 'CG1019', 'payments');
		water(QUID, $data['merOrderNo'], 'CG1019', 0, $money);
		
		$data['url'] = $this->config->item('recharge');
		$this->load->view('account/jump', $data);
	}
	
	/** 修改绑定的银行卡 */
	public function upbind() {
		//判断客服是否同意修改银行卡
		$this->load->model('bind/bind_model');
		$bind = $this->bind_model->get_bind_byuid(QUID);
		if($bind['status'] != 1) {
			exit();
		}
		//调用客户信息
		$meminfo = $this->member_model->get_member_info_byuid(QUID);
		$params['custNo'] = $meminfo['custNo'];
		$params['callbackUrl'] = $this->config->item('payapi')['callbackUrl'];
		$params['responsePath'] = $this->config->item('payapi')['response'];
		$data = head($params, 'CG1056');
		water(QUID, $data['merOrderNo'], 'CG1056');
		
		$data['url'] = $this->config->item('Interfaces').'1056';
		$this->load->view('account/jump', $data);
	}
	/** 提现 (T+0) 和 (T+1)*/
	public function withdraw_tx() {
		//提现类型
		$type = $this->uri->segment(3);
		if(!in_array($type, array(1, 2))) {
			exit('数据错误，请重新操作');
		}
		
		//调用客户信息
		$meminfo = $this->member_model->get_member_info_byuid(QUID);
		$money = $this->uri->segment(4);
		$money = round(floatval($money), 2) > 0 ? round(floatval($money), 2) : '0.00';
		if($money < 100) {
			exit('提现金额太不能小于100元，不能操作');
		}
		$my = $this->info_model->get_money(QUID);
		$today = $this->info_model->get_today_money(QUID);
		/* if($money > ($my['account_money']-$today['money'])) {
			exit('提现金额超出可提金额，不能操作');
		} */
		//当天充值也可提现
		if($money > $my['account_money']) {
			exit('提现金额超出可提金额，不能操作');
		}
		//T+0
		if($type == 1) {
			$params['acctNo'] = $meminfo['acctNo'];//账户号码
			$params['amount'] = $money;//提现金额
			//$params['incomeAmt'] = IS_COMPANY ? 0.00 : round(2.00 + $money*0.05/100, 2);//$money < 100 ? 2.00 : 0.00;//收益金额
			$params['incomeAmt'] = round(2.00 + $money*0.05/100, 2);
			$params['acctType'] = 'C001';//账户类型
		
			$params['callbackUrl'] = $this->config->item('payapi')['callbackUrl'];
			$params['responsePath'] = $this->config->item('payapi')['response'];
			$data = head($params, 'CG1046');
			water(QUID, $data['merOrderNo'], 'CG1046', 0, $money);
			
			$data['url'] = $this->config->item('Interfaces').'1046';
		} else if($type == 2) {//T+1
			$params['acctNo'] = $meminfo['acctNo'];//账户号码
			$params['amount'] = $money;//提现金额
			$params['incomeAmt'] = 0.00;//T+1手续费为0
			$params['acctType'] = 'C001';//账户类型
		
			$params['callbackUrl'] = $this->config->item('payapi')['callbackUrl'];
			$params['responsePath'] = $this->config->item('payapi')['response'];
			$data = head($params, 'CG1047');
			water(QUID, $data['merOrderNo'], 'CG1047', 0, $money);
			
			$data['url'] = $this->config->item('Interfaces').'1047';
		}
		$this->load->view('account/jump', $data);
	}
	/** 提现 (T+0)*/
	// public function withdraw_tx() {
		////调用客户信息
		// $meminfo = $this->member_model->get_member_info_byuid(QUID);
		// $money = $this->uri->segment(3);
		// $money = round(floatval($money), 2) > 0 ? round(floatval($money), 2) : '0.00';
		
		// if($money <= 2) {
			// exit('提现金额太小，不能操作');
		// }
		// $my = $this->info_model->get_money(QUID);
		// $today = $this->info_model->get_today_money(QUID);
		// if($money > ($my['account_money']-$today['money'])) {
			// exit('提现金额超出可提金额，不能操作');
		// }
		
		// $params['acctNo'] = $meminfo['acctNo'];//账户号码
		// $params['amount'] = $money;//提现金额
		// $params['incomeAmt'] = $money < 100 ? 2.00 : 0.00;//收益金额
		// $params['acctType'] = 'C001';//账户类型
	
		// $params['callbackUrl'] = $this->config->item('payapi')['callbackUrl'];
		// $params['responsePath'] = $this->config->item('payapi')['response'];
		// $data = head($params, 'CG1046');
		// water(QUID, $data['merOrderNo'], 'CG1046', 0, $money);
		
		// $data['url'] = $this->config->item('Interfaces').'1046';
		// $this->load->view('account/jump', $data);
	// }
	/** 注销 */
	public function cancel() {
		$meminfo = $this->member_model->get_member_info_byuid(QUID);
		$my = $this->info_model->get_money(QUID);
		if($my['account_money'] > 0.001) {
			exit('请确保账户金额为0，才能进行注销');
		}
		$params['custNo'] = $meminfo['custNo'];
		$params['callbackUrl'] = $this->config->item('payapi')['callbackUrl'];
		$params['responsePath'] = $this->config->item('payapi')['response'];
		$data = head($params, 'CG1051');
		water(QUID, $data['merOrderNo'], 'CG1051', 0, $money);
		
		$data['url'] = $this->config->item('Interfaces').'1051';
		$this->load->view('account/jump', $data);
	}
	/** 测试返回数据 */
	// public function test() {
		// $aaa = 'a:1:{s:5:"value";a:2:{s:5:"signs";s:4:"true";s:4:"body";a:2:{s:4:"body";a:5:{s:6:"acctNo";s:13:"1018001073101";s:9:"actualAmt";s:5:"13.00";s:10:"pledgedAmt";s:4:"0.00";s:9:"preLicAmt";s:4:"0.00";s:8:"totalAmt";s:5:"13.00";}s:4:"head";a:10:{s:7:"bizFlow";s:21:"008201809131751310110";s:10:"merOrderNo";s:20:"18091317514151696169";s:10:"merchantNo";s:15:"131010000011018";s:8:"respCode";s:6:"000000";s:8:"respDesc";s:6:"成功";s:9:"tradeCode";s:6:"CG1045";s:9:"tradeDate";s:8:"20180913";s:9:"tradeTime";s:6:"175131";s:9:"tradeType";s:2:"01";s:7:"version";s:5:"1.0.0";}}}}';
		// p(unserialize($aaa));
	// }
	
}
