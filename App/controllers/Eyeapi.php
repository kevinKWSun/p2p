<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Eyeapi extends CI_Controller {
	public function __construct() {
		parent::__construct();
		//关闭网贷天眼和网贷之家的接口，2019-1-11
		die;
		//$this->load->library('pagination');
		$this->load->helper(array('common'));
		$this->load->model('eyeapi/eyeapi_model');
		//验证token// && $this->router->fetch_method() !== 'account'
		if($this->router->fetch_method() !== 'token' && $this->router->fetch_method() !== 'test') {
			$token = $this->input->get_post('token', true);
			if(!$token || !$this->auth($token)) {
				$data['result'] = -1;
				$this->error($data);
			}
		}
	}
	
	
	/** 网贷之家数据 */
	public function wdzj_data() {
		$date = $this->input->get('date', true);//当日日期
		$page = $this->input->get('page', true);//当前页
		$pageSize = $this->input->get('pageSize', true);//每页的借款标数
		if($pageSize < 1 || $page < 1) {
			$result["borrowList"] = array();
			$result["currentPage"] = '1';
			$result["totalAmount"] = '0';
			$result["totalCount"] = '0';
			$result["totalPage"] = '1';
			$this->output
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($result))
			->_display();
			exit;
		}
		
		//满标数据
		$where['fulltime'] = array(strtotime($date . ' 00:00:00'), strtotime($date . ' 23:59:59'));
		//放款时间
		$where['endtime'] = array(strtotime($date . ' 00:00:00'), strtotime($date . ' 23:59:59'));
		$where['borrow_status >='] = 3;
		$where['del'] = 0;
		$total_rows = $this->eyeapi_model->get_borrow_related_nums($where);
		$borrow = $this->eyeapi_model->get_borrow_related($pageSize, ($page-1) * $pageSize, $where);
		//p($borrow);
		if($borrow) {
			$result = array();
			$result['totalPage'] = ceil($total_rows/$pageSize);
			$result['currentPage'] = $page;
			$result['totalCount'] = $total_rows;
			$result['totalAmount'] = 0.00;
			$result['borrowList'] = array();
			$i = 0;
			foreach($borrow as $k=>$v) {
				//借款表信息
				$result['borrowList'][$i] = [
					'projectId' 		=> $v['id'],
					'title'				=> $v['borrow_name'],
					'amount'			=> sprintf("%.2f", $v['borrow_money']),
					'schedule'			=> 100,
					'interestRate'		=> sprintf("%.2f", $v['borrow_interest_rate'] + $v['add_rate']) . '%',
					'deadline'			=> $this->config->item('borrow_duration')[$v['borrow_duration']],
					'deadlineUnit'		=> '天',
					'reward'			=> 0,
					'type'				=> $this->config->item('borrow_type')[$v['borrow_type']],
					'repaymentType'		=> ($v['repayment_type'] == 1) ? 1 : 5,
					'plateType'			=> '',
					'guarantorsType'	=> '',
					'province'			=> '',
					'city'				=> '',
					'userName'			=> substr($v['idcard'], -10) . substr($v['phone'], -5),
					'userAvatarUrl'		=> '',
					'amountUsedDesc'	=> '',
					'revenue'			=> 0,
					'loanUrl'			=> 'https://www.jiamanu.com/invest/show/' . $v['id'] . '.html',
					'successTime'		=> date('Y-m-d H:i:s', $v['endtime']),
					'publishTime'		=> date('Y-m-d H:i:s', $v['send_time']),
					'isAgency'			=> 0,
					'subscribes'		=> array()
				];
				$result['totalAmount'] += $v['borrow_money'];
				//调取投资信息
				$borrow_investor = $this->eyeapi_model->get_borrow_investor_one($v['id']);
				//p($borrow_investor);
				foreach($borrow_investor as $key=>$value) {
					$result['borrowList'][$i]['subscribes'][] = [
						'subscribeUserName'		=> $value['investor_uid'],
						'amount'				=> sprintf('%.2f', $value['investor_capital']),
						'validAmount'			=> sprintf('%.2f', $value['investor_capital']),
						'addDate'				=> date('Y-m-d H:i:s', $value['add_time']),
						'status'				=> 1,
						'type'					=> 0,
						'sourceType'			=> 1	
					];
				}
				$i++;
			}
			$result['totalAmount'] = sprintf("%.2f", $result['totalAmount']);
		} else {
			$result["borrowList"] = array();
			$result["currentPage"] = '1';
			$result["totalAmount"] = '0';
			$result["totalCount"] = '0';
			$result["totalPage"] = '1';
		}
		
		$this->output
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($result))
			->_display();
			exit;
	}
	
	/** 网贷之家 提前还款接口 */
	public function wdzj_pre_repay() {
		$date = $this->input->get('date', true);//当日日期
		$page = $this->input->get('page', true);//当前页
		$pageSize = $this->input->get('pageSize', true);//每页的借款标数
		if($pageSize < 1 || $page < 1) {
			$result["totalCount"] = '0';
			$result["totalPage"] = '1';
			$result["currentPage"] = '1';
			$result["preapys"] = array();
			$this->output
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($result))
			->_display();
			exit;
		}
		$where['repayment_time'] = array(strtotime($date . ' 00:00:00'), strtotime($date . ' 23:59:59'));
		$total_rows = $this->eyeapi_model->get_borrow_repay_nums($where);
		$borrow = $this->eyeapi_model->get_borrow_repay($pageSize, ($page-1) * $pageSize, $where);
		
		$result = array();
		if($borrow) {
			$result['totalPage'] = ceil($total_rows/$pageSize);
			$result['currentPage'] = $page;
			foreach($borrow as $k=>$v) {
				$result['preapys'][] = [
					'projectId' 		=> $v['id'],
					'deadlineUnit'		=> '天',
					'deadline'			=> intval((strtotime(date('Y-m-d', $v['repayment_time'])) - strtotime(date('Y-m-d', $v['endtime'])))/86400)
				];
			}
		} else {
			$result["totalCount"] = '0';
			$result["totalPage"] = '1';
			$result["currentPage"] = '1';
			$result["preapys"] = array();
		}
		$this->output
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($result))
			->_display();
			exit;
	}
	
	
	
	
	
	
	/** 测试页面 */
	public function test() {
		$this->load->view('testadmin/eyeapi');
	}
	/** 借款数据 */
	public function loans() {
		//接收参数
		$status = $this->input->get_post('status', true);//标的状态0.正在投标中的借款标;1.已完成
		$time_from = $this->input->get_post('time_from', true);//起始时间
		$time_to = $this->input->get_post('time_to', true);//截止时间
		$page_size = intval($this->input->get_post('page_size', true));//每页记录条数
		$page_index = intval($this->input->get_post('page_index', true));//请求的页码
		$post = $this->input->get_post(null, true);
		if($page_size < 1 || $page_index < 1) {
			$result["result_code"] = "-1";
			$result["result_msg"] = '未授权的访问';
			$result["page_count"] = "0";
			$result["page_index"] = "0";
			$result["loans"] = null;
			$this->output
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($result))
			->_display();
			exit;
		}
		
		//已完成的标的
		if($status == 1) {
			//满标时间
			$where['fulltime'] = array(strtotime($time_from), strtotime($time_to));
			//放款时间
			$where['endtime'] = array(strtotime($time_from), strtotime($time_to));
			$where['borrow_status >='] = 3;
			$where['del'] = 0;
			$total_rows = $this->eyeapi_model->get_borrow_related_nums($where);
			$borrow = $this->eyeapi_model->get_borrow_related($page_size, ($page_index-1) * $page_size, $where);
			
			$result = array();
			if($borrow) {
				$result["result_code"] = 1;
				$result["result_msg"] = '获取数据成功';
				$result["page_count"] = ceil($total_rows/$page_size);
				$result["page_index"] = $page_index;
				foreach($borrow as $k=>$v) {
					$result["loans"][] = [
						"id"			=> $v['id'],
						"url"			=> "https://www.jiamanu.com/invest/show/" . $v['id'] . ".html",
						"platform_name"	=> "伽满优",
						"title"			=> $v['borrow_name'],
						"username"		=> str_replace(mb_substr($v['real_name'], 0, 6), '**', $v['real_name']),
						"status"		=> "1",
						"userid"		=> $v['borrow_uid'],
						"c_type"		=> "2.1",
						"amount"		=> $v['borrow_money'],
						"rate"			=> round(($v['borrow_interest_rate'] + $v['add_rate'])/100, 4),
						"period"		=> $this->config->item('borrow_duration')[$v['borrow_duration']],
						"p_type"		=> "0",
						"pay_way"		=> ($v['repayment_type'] == 1) ? 3 : 2,
						"process"		=> sprintf("%.1f", $v['has_borrow']/$v['borrow_money']),
						"reward"		=> "0.00",
						"guarantee"		=> "0.00",
						"start_time"	=> date('Y-m-d H:i:s', $v['send_time']),
						"end_time"		=> date('Y-m-d H:i:s', $v['endtime']),
						"invest_num"	=> $v['borrow_times'],
						"c_reward"		=> "0.00"
					];
				}
			} else {
				$result["result_code"] = "-1";
				$result["result_msg"] = '未授权的访问';
				$result["page_count"] = "0";
				$result["page_index"] = "0";
				$result["loans"] = null;
			}
			
		} else {//借款中的
			//上标时间
			$where['send_time'] = array(strtotime($time_from), strtotime($time_to));
			$where['borrow_status'] = 2;
			$where['del'] = 0;
			$total_rows = $this->eyeapi_model->get_borrow_related_nums($where);
			$borrow = $this->eyeapi_model->get_borrow_related($page_size, ($page_index-1) * $page_size, $where);
			
			if($borrow) {
				$result["result_code"] = 1;
				$result["result_msg"] = '获取数据成功';
				$result["page_count"] = ceil($total_rows/$page_size);
				$result["page_index"] = $page_index;
				foreach($borrow as $k=>$v) {
					$result["loans"][] = [
						"id"			=> $v['id'],
						"url"			=> "https://www.jiamanu.com/invest/show/" . $v['id'] . ".html",
						"platform_name"	=> "伽满优",
						"title"			=> $v['borrow_name'],
						"username"		=> str_replace(mb_substr($v['real_name'], 0, 6), '**', $v['real_name']),
						"status"		=> "0",
						"userid"		=> $v['borrow_uid'],
						"c_type"		=> "2.1",
						"amount"		=> $v['borrow_money'],
						"rate"			=> round(($v['borrow_interest_rate'] + $v['add_rate'])/100, 4),
						"period"		=> $this->config->item('borrow_duration')[$v['borrow_duration']],
						"p_type"		=> "0",
						"pay_way"		=> ($v['repayment_type'] == 1) ? 3 : 2,
						"process"		=> sprintf("%.1f", $v['has_borrow']/$v['borrow_money']),
						"reward"		=> "0.00",
						"guarantee"		=> "0.00",
						"start_time"	=> date('Y-m-d H:i:s', $v['send_time']),
						//"end_time"		=> date('Y-m-d H:i:s', $v['endtime']),
						"invest_num"	=> $v['borrow_times'],
						"c_reward"		=> "0.00"
					];
				}
			} else {
				$result["result_code"] = "-1";
				$result["result_msg"] = '未授权的访问';
				$result["page_count"] = "0";
				$result["page_index"] = "0";
				$result["loans"] = null;
			}
		}
		$this->output
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($result))
			->_display();
			exit;
	}
	
	/** 投资记录 */
	public function data() {
		$status = $this->input->get_post('status', true);//标的状态0.正在投标中的借款标;1.已完成
		$time_from = $this->input->get_post('time_from', true);//起始时间
		$time_to = $this->input->get_post('time_to', true);//截止时间
		$page_size = intval($this->input->get_post('page_size', true));//每页记录条数
		$page_index = intval($this->input->get_post('page_index', true));//请求的页码
		if($page_size < 1 || $page_index < 1) {
			$result["result_code"] = "-1";
			$result["result_msg"] = '未授权的访问';
			$result["page_count"] = "0";
			$result["page_index"] = "0";
			$result["loans"] = null;
			$this->output
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($result))
			->_display();
			exit;
		}
		//已完成的标的
		if($status == 1) {
			//满标时间
			$where['fulltime'] = array(strtotime($time_from), strtotime($time_to));
			//放款时间
			$where['endtime'] = array(strtotime($time_from), strtotime($time_to));
			$where['borrow_status >='] = 3;
			$where['del'] = 0;
			$total_rows = $this->eyeapi_model->get_borrow_investor_num($where);
			$borrow = $this->eyeapi_model->get_borrow_investor_lists($page_size, ($page_index-1) * $page_size, $where);
			//p($borrow);
			$result = array();
			if($borrow) {
				$result["result_code"] = 1;
				$result["result_msg"] = '获取数据成功';
				$result["page_count"] = ceil($total_rows/$page_size);
				$result["page_index"] = $page_index;
				foreach($borrow as $k=>$v) {
					$result["loans"][] = [
						"id"			=> $v['borrow_id'],
						"link"			=> "https://www.jiamanu.com/invest/show/" . $v['borrow_id'] . ".html",
						"useraddress"	=> "",
						"username"		=> substr(md5($v['phone']), 0, 12),
						"userid"		=> $v['investor_uid'],
						"type"			=> "手动",
						"money"			=> $v['investor_capital'],
						"account"		=> $v['investor_capital'],
						"status"		=> "成功",
						"add_time"		=> date('Y-m-d H:i:s', $v['add_time'])
					];
				}
			} else {
				$result["result_code"] = "-1";
				$result["result_msg"] = '未授权的访问';
				$result["page_count"] = "0";
				$result["page_index"] = "0";
				$result["loans"] = null;
			}
		} else {//借款中的
			$where['send_time'] = array(strtotime($time_from), strtotime($time_to));
			$where['borrow_status'] = 2;
			$where['del'] = 0;
			$total_rows = $this->eyeapi_model->get_borrow_related_nums($where);
			$borrow = $this->eyeapi_model->get_borrow_related($page_size, ($page_index-1) * $page_size, $where);
			
			$result = array();
			if($borrow) {
				$result["result_code"] = 1;
				$result["result_msg"] = '获取数据成功';
				$result["page_count"] = ceil($total_rows/$page_size);
				$result["page_index"] = $page_index;
				foreach($borrow as $k=>$v) {
					$result["loans"][] = [
						"id"			=> $v['borrow_id'],
						"link"			=> "https://www.jiamanu.com/invest/show/" . $v['borrow_id'] . ".html",
						"useraddress"	=> "",
						"username"		=> substr(md5($v['phone']), 0, 12),
						"userid"		=> $v['investor_uid'],
						"type"			=> "手动",
						"money"			=> $v['investor_capital'],
						"account"		=> $v['investor_capital'],
						"status"		=> "成功",
						"add_time"		=> date('Y-m-d H:i:s', $v['add_time'])
					];
				}
			} else {
				$result["result_code"] = "-1";
				$result["result_msg"] = '未授权的访问';
				$result["page_count"] = "0";
				$result["page_index"] = "0";
				$result["loans"] = null;
			}
		}
		$this->output
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($result))
			->_display();
			exit;
	}
	
	/** 提前还款 */
	public function repay() {
		$status = $this->input->get_post('status', true);//标的状态0.正在投标中的借款标;1.已完成
		$time_from = $this->input->get_post('time_from', true);//起始时间
		$time_to = $this->input->get_post('time_to', true);//截止时间
		$page_size = intval($this->input->get_post('page_size', true));//每页记录条数
		$page_index = intval($this->input->get_post('page_index', true));//请求的页码
		if($page_size < 1 || $page_index < 1) {
			$result["result_code"] = "-1";
			$result["result_msg"] = '未授权的访问';
			$result["page_count"] = "0";
			$result["page_index"] = "0";
			$result["loans"] = null;
			$this->output
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($result))
			->_display();
			exit;
		}
		
		if($status == 1) {
			//到期时间
			$where['deadline'] = array(strtotime($time_from), strtotime($time_to));
			$where['borrow_status'] = 6;
			$where['del'] = 0;
			$total_rows = $this->eyeapi_model->get_borrow_related_numss($where);
			$borrow = $this->eyeapi_model->get_borrow_relateds($page_size, ($page_index-1) * $page_size, $where);
			
			$result = array();
			if($borrow) {
				$result["result_code"] = 1;
				$result["result_msg"] = '获取数据成功';
				$result["page_count"] = ceil($total_rows/$page_size);
				$result["page_index"] = $page_index;
				$repay_money = array();
				foreach($borrow as $k=>$v) {
					$repay_money = $this->eyeapi_model->get_repay_money($v['id']);
					$result["loans"][] = [
						"id"			=> $v['id'],
						"url"			=> "https://www.jiamanu.com/invest/show/" . $v['id'] . ".html",
						"platform_name"	=> "伽满优",
						"title"			=> $v['borrow_name'],
						"username"		=> str_replace(mb_substr($v['real_name'], 0, 6), '**', $v['real_name']),
						"status"		=> "1",
						"userid"		=> $v['borrow_uid'],
						"c_type"		=> "2.1",
						"amount"		=> $v['borrow_money'],
						"rate"			=> round(($v['borrow_interest_rate'] + $v['add_rate'])/100, 4),
						"period"		=> $this->config->item('borrow_duration')[$v['borrow_duration']],
						"p_type"		=> "0",
						"pay_way"		=> ($v['repayment_type'] == 1) ? 3 : 2,
						"process"		=> sprintf("%.1f", $v['has_borrow']/$v['borrow_money']),
						"end_time"		=> date('Y-m-d H:i:s', $v['endtime']),
						"prepayment_time"=> "2014-03-13 16:44:26",
						"accrual_end_time"=> date('Y-m-d H:i:s', $v['deadline']),
						"prepayment_type"=> "0",
						"prepayment_amount"=> $repay_money['receive_capital'] + $repay_money['receive_interest'],
						"prepayment_accrual"=> $repay_money['receive_interest']
						
					];
				}
			} else {
				$result["result_code"] = "-1";
				$result["result_msg"] = '未授权的访问';
				$result["page_count"] = "0";
				$result["page_index"] = "0";
				$result["loans"] = null;
			}
		} else {
			$result["result_code"] = "-1";
			$result["result_msg"] = '未授权的访问';
			$result["page_count"] = "0";
			$result["page_index"] = "0";
			$result["loans"] = null;
		}
		$this->output
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($result))
			->_display();
			exit;
	}
	
	/** 添加账号 */
	public function account() {die;
		$timstamp = time();
		$salt = getTxNo16();
		$data = [
			'username' => 'wdzj',//'eyeapi',
			'password' => md5($timstamp . $salt),
			'addtime'  => $timstamp
		]; 
		$this->eyeapi_model->add_account($data);
	}
	
	/** 验证用户并生成token */
	public function token() {
		$get = $this->input->get(NULL, TRUE);
		$username = $get['username'];
		$password = $get['password'];
		$account = $this->eyeapi_model->get_account_byusername($username);
		//如果密码不匹配，获取token失败
		if(!$account || $account['password'] !== $password) {
			$data = [
				'result' 	=> -1,
				'data'		=> [
					'token' => null
				]
			];
			$this->error($data);
		}
		$account['token'] = md5(time().getTxNo16());
		$account['uptime'] = time();
		$account['expire'] = time() + 86400;
		if($this->eyeapi_model->modify_account($account)) {
			$data = [
				'result' 	=> 1,
				'data'		=> [
					'token' => $account['token']
				]
			];
			$this->success($data);
		} else {
			$data = [
				'result' 	=> -1,
				'data'		=> [
					'token' => null
				]
			];
			$this->error($data);
		}
	}
	
	/** 判断token */
	private function auth($token) {
		$account = $this->eyeapi_model->get_account_bytoken($token);
		//有数据token没有过期
		if($account && $account['expire'] > time()) {
			return TRUE;
		}
		return FALSE;
	}
	
	
	/**
     * 报错信息
     * @param string  $message    错误信息
     */
    private function error($data) {
		$this->output
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data))
			->_display();
			exit;
    }
	/**
     * 成功信息
     * @param string  $message    	成功信息
     */
    private function success($data) {
		$this->output
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data))
			->_display();
			exit;
    }
}