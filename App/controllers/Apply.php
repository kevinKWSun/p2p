<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Apply extends Baseaccount {
	public function __construct() {
		parent::__construct();
		$this->load->library('pagination');
		$this->load->model(array('member/member_model', 'borrow/borrow_model'));
		//$this->load->helper('url');
	}
	
	public function index() {
		if($post = $this->input->post(NULL, TRUE)){
			$money = intval($post['money']);
			$remark = $post['remark'];
			if(!QUID) {
				$this->error('请登陆后再操作');
			}
			if(mb_strlen($remark) > 500) {
				$this->error('字数超过限制');
			}
			if(mb_strlen($money) > 9 || empty($money) || mb_strlen($money) < 5) {
				$this->error('金额有误');
			}
			$meminfo = $this->member_model->get_member_info_byuid(QUID);
			$data = array(
				'uid' 		=> QUID,
				'name'		=> $meminfo['real_name'],
				'phone'		=> $meminfo['phone'],
				'money'		=> $money,
				'remark'	=> $remark,
				'add_time'	=> time()
			);
			if($this->member_model->add_borrow($data)) {
				$this->success('操作成功', '/apply/lists');
			} else {
				$this->error('操作失败');
			}
		}
		$data = array();
		$this->load->view('account/apply_v1', $data);
	}
	
	//借款列表
	public function lists(){
		$data = array();
		$where = array();
		$search = $this->input->get(null, true);
		if(!empty($search['skey'])) {
			$data['skey'] = trim(trim($search['skey']), '\t');
			$where['skey'] = $data['skey'];
		}
		if(!empty($search['name'])) {
			$data['name'] = trim(trim($search['name']), '\t');
			$where['name'] = $data['name'];
		}
		if(!empty($search['status'])) {
			$data['status'] = trim(trim($search['status']), '\t');
			$where['borrow_status'] = $data['status'];
		}
		$where['del'] = 0;
		$where['borrow_uid'] = QUID;
		//$where['borrow_status >='] = 2;  //状态
		//$where['borrow_status <='] = 3;
		$per_page = 10;
		$current_page = $this->uri->segment(3) ? $this->uri->segment(3) - 1 : 0;
		$offset = $current_page;
		$config['base_url'] = base_url('borrow/index');
		$config['total_rows'] = $this->borrow_model->get_borrow_related_nums($where);
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
		$borrow = $this->borrow_model->get_borrow_related($per_page, $offset * $per_page, $where);
		foreach($borrow as $k=>$v) {
			//获取实际还款时间
			$borrow[$k]['real_repayment_time'] = $this->borrow_model->get_detail_one_bybid($v['id'])['repayment_time'];
		}
		$data['borrow'] = $borrow;
		if(IS_COMPANY) {
			$this->blists();
		} else {
			$this->load->view('account/apply_list_v1', $data);
		}
		
	}
	
	// public function lists() {
		// $data = array();
		// $current_page  = intval($this->uri->segment(3));
		// $current_page = $current_page > 0 ? $current_page - 1 : 0;
		// $per_page = 10;
        // $offset = $current_page;
        // $config['base_url'] = base_url('apply/lists');
        // $config['total_rows'] = $this->member_model->get_borrow_my_nums(QUID);
        // $config['per_page'] = $per_page;
		// $config['page_query_string'] = FALSE;
		// $config['first_link'] = '首页'; // 第一页显示   
		// $config['last_link'] = '末页'; // 最后一页显示   
		// $config['next_link'] = '下一页'; // 下一页显示   
		// $config['prev_link'] = '上一页'; // 上一页显示   
		// $config['cur_tag_open'] = ' <span class="current">'; // 当前页开始样式   
		// $config['cur_tag_close'] = '</span>';   
		// $config['num_links'] = 10;
		// $config['uri_segment'] = 3;
		// $config['use_page_numbers'] = TRUE;
        // $this->pagination->initialize($config); 
        // $data['totals'] = $config['total_rows'];
        // $data['page'] = $this->pagination->create_links();
        // $data['p'] = $current_page;
        // $data['borrow'] = $this->member_model->get_borrow_my($per_page, $offset * $per_page, QUID);
		// $this->load->view('account/apply_list_v1', $data);
	// }
	
	/** 已放款的借款列表 */
	public function blists() {
		$data = array();
		$current_page  = intval($this->uri->segment(3));
		$current_page = $current_page > 0 ? $current_page - 1 : 0;
		$per_page = 5;
        $offset = $current_page;
        $config['base_url'] = base_url('apply/blists');
        $config['total_rows'] = $this->borrow_model->get_borrow_num(QUID);
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
        $this->pagination->initialize($config); 
        $data['totals'] = $config['total_rows'];
        $data['page'] = $this->pagination->create_links();
        $data['p'] = $current_page;
        $data['borrow'] = $this->borrow_model->get_borrow($per_page, $offset * $per_page, QUID);
		//p($data['borrow']);die;
		$this->load->view('account/blists', $data);
	}
	/** 还款列表 */
	public function repaylist() {
		$id = $this->uri->segment(3);
		$data = array();
		$data['borrow'] = $this->borrow_model->get_borrow_byid($id);
		if($data['borrow']['borrow_uid'] != QUID) {
			exit('数据错误，请联系客服');
		}
		$data['borrow_uid'] = $this->member_model->get_member_info_byuid($data['borrow']['borrow_uid'])['real_name'];
		$data['details'] = $this->borrow_model->get_investor_detail_byid($id);
		foreach($data['details'] as $k=>$v) {
			$data['details'][$k]['investor_name'] = $this->member_model->get_member_info_byuid($v['investor_uid'])['real_name'];
		}
		$this->load->view('account/repaylist', $data);
	}
	//还款(一期一期的还())
	public function repayment() {
		$id_detail = $this->uri->segment(3);
		if($id_detail <= 0){
			$this->error('加载数据出错!');
		}
		$detail = $this->borrow_model->get_detail_one($id_detail);
		if($detail['repayment_time'] > 0) {
			$this->error('已操作过该笔还款');
		}
		$id = $detail['borrow_id'];
		//调取标信息
		$borrow = $this->borrow_model->get_borrow_byid($id);
		if($borrow['borrow_uid'] != QUID) {
			$this->error('数据错误，请联系客服');
		}
		//调取借款人信息
		$meminfo = $this->member_model->get_member_info_byuid($borrow['borrow_uid']);
		//调取投资人的账户信息
		$meminfos = $this->member_model->get_member_info_byuid($detail['investor_uid']);
		//时间戳
		$timestamp = time();
		//最后还款日
		$deadline = $timestamp + $this->config->item('borrow_duration')[$borrow['borrow_duration']]*86400;
		//请求放款接口CG1021,
		//组织放款数据，
		$this->load->model(array('paytest/paytest_model'));
		$params['subjectNo'] = $borrow['subjectNo'];
		$params['payerAcctNo'] = $meminfo['acctNo'];
		if($detail['sort_order'] < $detail['total']) {
			$params['amount'] = $detail['interest'];
			$params['capital'] = 0;
			$params['payerAmount'] = $detail['interest'];
		} else {
			$params['amount'] = $detail['capital'] + $detail['interest'];
			$params['capital'] = $detail['capital'];
			$params['payerAmount'] = $detail['capital'] + $detail['interest'];
		}
		//$params['incomeAmt'] = 0;//round(floatval($detail['investor_capital']*$borrow['service_money']/100), 2);
		$params['payeeAcctNo'] = $meminfos['acctNo'];
		
		$params['callbackUrl'] = 'https://www.jiamanu.com/paytest';
		$params['responsePath'] = 'https://www.jiamanu.com/account/response';
		$head = head($params, 'CG1053', 'paymentmoney');
		water($meminfo['uid'], $head['merOrderNo'], 'CG1053', $id_detail, $params['amount']);
		unset($head['callbackUrl'], $head['registerPhone'], $head['responsePath'], $head['url']);
		$data = $head;
		
		$data['url'] = $this->config->item('Interfaces').'1053';
		$this->load->view('account/jump', $data);
		
	}
	/*public function repaylist() {
		$id = $this->uri->segment(3);
		$data = array();
		$data['borrow'] = $this->borrow_model->get_borrow_byid($id);
		$data['borrow_uid'] = $this->member_model->get_member_info_byuid($data['borrow']['borrow_uid'])['real_name'];
		$data['detail'] = $this->borrow_model->get_investor_detail_byid($id);
		foreach($data['detail'] as $k=>$v) {
			$data['detail'][$k]['investor_name'] = $this->member_model->get_member_info_byuid($v['investor_uid'])['real_name'];
		}
		//组织数据(组织成一期一期的数据)
		foreach($data['detail'] as $v) {
			if(isset($data['details'][$v['sort_order']])) {
				$data['details'][$v['sort_order']]['id'][] = $v['id'];
				$data['details'][$v['sort_order']]['invest_id'][] = $v['invest_id'];
				$data['details'][$v['sort_order']]['investor_uid'][] = $v['investor_uid'];
				$data['details'][$v['sort_order']]['investor_name'][] = $v['investor_name'];
				$data['details'][$v['sort_order']]['capital'] += $v['capital'];
				$data['details'][$v['sort_order']]['interest'] += $v['interest'];
				$data['details'][$v['sort_order']]['receive_interest'] += $v['receive_interest'];
				$data['details'][$v['sort_order']]['receive_capital'] += $v['receive_capital'];
				$data['details'][$v['sort_order']]['repayment_time'] = $v['repayment_time'];
			} else {
				$data['details'][$v['sort_order']]['id'][] = $v['id'];
				$data['details'][$v['sort_order']]['repayment_time'] = $v['repayment_time'];
				$data['details'][$v['sort_order']]['borrow_id'] = $v['borrow_id'];
				$data['details'][$v['sort_order']]['invest_id'][] = $v['invest_id'];
				$data['details'][$v['sort_order']]['investor_uid'][] = $v['investor_uid'];
				$data['details'][$v['sort_order']]['investor_name'][] = $v['investor_name'];
				$data['details'][$v['sort_order']]['borrow_uid'] = $v['borrow_uid'];
				$data['details'][$v['sort_order']]['capital'] = $v['capital'];
				$data['details'][$v['sort_order']]['interest'] = $v['interest'];
				$data['details'][$v['sort_order']]['status'] = $v['status'];
				$data['details'][$v['sort_order']]['receive_interest'] = $v['receive_interest'];
				$data['details'][$v['sort_order']]['receive_capital'] = $v['receive_capital'];
				$data['details'][$v['sort_order']]['sort_order'] = $v['sort_order'];
				$data['details'][$v['sort_order']]['total'] = $v['total'];
				$data['details'][$v['sort_order']]['deadline'] = $v['deadline'];
			}
		}
		//去重复
		foreach($data['details'] as $v) {
			$data['details'][$v['sort_order']]['id'] = implode(',', $v['id']);
			$data['details'][$v['sort_order']]['investor_uid'] = array_unique($v['investor_uid']);
			$data['details'][$v['sort_order']]['investor_name'] = array_unique($v['investor_name']);
		}
		//p($data['details']);die;
		$this->load->view('account/repaylist', $data);
	}
	//还款(一期一期的还)
	/*public function repayment() {
		if(IS_POST) {
			$this->load->model(array('account/info_model'));
			$id_details = $this->input->post('id', true);
			$ids_details = explode(',', $id_details);
			foreach($ids_details as $k=>$v) {
				if(empty($v)) {
					$this->error('加载数据出错!');
				}
			}
			//p($ids_details);die;
			foreach($ids_details as $v) {
				$detail = $this->borrow_model->get_detail_one($v);
				if($detail['repayment_time'] > 0) {
					continue;
				}
				$id = $detail['borrow_id'];
				//调取标信息
				$borrow = $this->borrow_model->get_borrow_byid($id);
				//调取借款人信息
				$meminfo = $this->member_model->get_member_info_byuid($borrow['borrow_uid']);
				//调取投资人的账户信息
				$meminfos = $this->member_model->get_member_info_byuid($detail['investor_uid']);
				//时间戳
				$timestamp = time();
				//最后还款日
				$deadline = $timestamp + $this->config->item('borrow_duration')[$borrow['borrow_duration']]*86400;
				//请还款接口CG1010,
				//组织放款数据，
				$this->load->model(array('paytest/paytest_model'));
				$params['subjectNo'] = $borrow['subjectNo'];
				$params['payerAcctNo'] = $meminfo['acctNo'];
				if($detail['sort_order'] < $detail['total']) {
					$params['amount'] = $detail['interest'];
					$params['capital'] = 0;
					$params['payerAmount'] = $detail['interest'];
				} else {
					$params['amount'] = $detail['capital'] + $detail['interest'];
					$params['capital'] = $detail['capital'];
					$params['payerAmount'] = $detail['capital'] + $detail['interest'];
				}
				//$params['incomeAmt'] = 0;//round(floatval($detail['investor_capital']*$borrow['service_money']/100), 2);
				//p($meminfo['ppwd']);die;
				//$params['payPwd'] = intval($meminfo['ppwd']);
				$params['payeeAcctNo'] = $meminfos['acctNo'];
				
				
				
				//p($params);
				$head = head($params, 'CG1010', 'paymentmoney');
				water($meminfo['uid'], $head['merOrderNo'], 'CG1010', $v, $params['amount']);
				unset($head['callbackUrl'], $head['registerPhone'], $head['responsePath'], $head['url']);
				$data = json_encode($head);
				$url = $this->config->item('Interface').'1010';
				$str = post_curl_test($url, $data);
				$tmp_body = $this->paytest_model->excute($str);
				//p($tmp_body);die;
				
				// $aaa = 'a:1:{s:5:"value";a:2:{s:5:"signs";s:4:"true";s:4:"body";a:2:{s:4:"body";a:1:{s:4:"list";a:1:{i:0;a:3:{s:7:"bizFlow";s:21:"003201809261720242018";s:11:"payeeAcctNo";s:13:"1018001073101";s:10:"resultCode";s:6:"000000";}}}s:4:"head";a:10:{s:7:"bizFlow";s:21:"003201809261720242016";s:10:"merOrderNo";s:20:"18092617208757581418";s:10:"merchantNo";s:15:"131010000011018";s:8:"respCode";s:6:"000000";s:8:"respDesc";s:6:"成功";s:9:"tradeCode";s:6:"CG1010";s:9:"tradeDate";s:8:"20180926";s:9:"tradeTime";s:6:"172024";s:9:"tradeType";s:2:"01";s:7:"version";s:5:"1.0.0";}}}}';
				// $tmp_body = unserialize($aaa)['value']['body'];

				$respCode = $tmp_body['head']['respCode'];
				if($respCode === '000000') {
					$merOrderNo = $tmp_body['head']['merOrderNo'];
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
					//p($memoney_borrow);p($water);die;
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
					
				} else {
					if(isset($tmp_body['head']['respDesc'])) {
						$this->error($tmp_body['head']['respDesc']);
					} else {
						$this->error('接口错误');
					}
					
				}
			}
			$this->success('还款成功');
		}
	}*/
}