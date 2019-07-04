<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Borrow extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->library('pagination');
		$this->load->model(array('borrow/borrow_model', 'member/member_model', 'account/info_model'));
		$this->load->helper(array('url', 'common'));
	}
	//借款列表
	public function index(){
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
		if(!empty($search['guarantor'])) {
			$data['guarantor'] = trim(trim($search['guarantor']), '\t');
			$where['guarantor'] = $data['guarantor'];
		}
		if(!empty($search['status'])) {
			$data['status'] = trim(trim($search['status']), '\t');
			$where['borrow_status'] = $data['status'];
		}
		$where['del'] = 0;
		$where['borrow_status >='] = 2;
		$where['borrow_status <='] = 3;
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
		$data['skip_page'] = $this->pagination->create_skip_link();
        $data['p'] = $current_page;
        $borrow = $this->borrow_model->get_borrow_related($per_page, $offset * $per_page, $where);
        $data['borrow'] = $borrow;
		$this->load->view('borrow/borrow', $data);
	}
	/** 导出 */
	public function export() {
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
		$borrow = $this->borrow_model->get_borrow_related($per_page, $offset * $per_page, $where);
		$all = $borrow;
		$this->load->helper('common');
		$this->load->library('PHPExcel');
		$resultPHPExcel = new PHPExcel();
		$resultPHPExcel->getActiveSheet()->setCellValue('A1', 'ID');
		$resultPHPExcel->getActiveSheet()->setCellValue('B1', '标名');
		$resultPHPExcel->getActiveSheet()->setCellValue('C1', '编号');
		$resultPHPExcel->getActiveSheet()->setCellValue('D1', '借款人');
		$resultPHPExcel->getActiveSheet()->setCellValue('E1', '金额/万');
		$resultPHPExcel->getActiveSheet()->setCellValue('F1', '期限');
		$resultPHPExcel->getActiveSheet()->setCellValue('G1', '申请日期');
		$resultPHPExcel->getActiveSheet()->setCellValue('H1', '上标日期');
		$resultPHPExcel->getActiveSheet()->setCellValue('I1', '放款日期');
		$resultPHPExcel->getActiveSheet()->setCellValue('J1', '到期日期');
		$resultPHPExcel->getActiveSheet()->setCellValue('K1', '还款方式');
		$resultPHPExcel->getActiveSheet()->setCellValue('L1', '状态');
		$resultPHPExcel->getActiveSheet()->setCellValue('M1', '担保人');
		$i = 1;
		foreach($all as $k => $v){
			$i++;
			$resultPHPExcel->getActiveSheet()->setCellValue('A'.$i, $v['id']);
			$resultPHPExcel->getActiveSheet()->setCellValue('B'.$i, $v['borrow_type'] == 2 ? $v['borrow_name'].'[新]' : $v['borrow_name']);
			$resultPHPExcel->getActiveSheet()->setCellValue('C'.$i, $v['borrow_no']);
			$resultPHPExcel->getActiveSheet()->setCellValue('D'.$i, get_member_info($v['borrow_uid'])['real_name']);
			$resultPHPExcel->getActiveSheet()->setCellValue('E'.$i, $v['borrow_money'] / 10000);
			$resultPHPExcel->getActiveSheet()->setCellValue('F'.$i, $this->config->item('borrow_duration')[$v['borrow_duration']]);
			$resultPHPExcel->getActiveSheet()->setCellValue('G'.$i, date('Y-m-d',$v['add_time']));
			if($v['send_time'] > 0) { 
				$resultPHPExcel->getActiveSheet()->setCellValue('H'.$i, date('Y-m-d',$v['send_time']));
			} else { 
				$resultPHPExcel->getActiveSheet()->setCellValue('H'.$i, '-');
			}
			if($v['endtime'] > 0) {
				$resultPHPExcel->getActiveSheet()->setCellValue('I'.$i, date('Y-m-d',$v['endtime'])); 
			} else { 
				$resultPHPExcel->getActiveSheet()->setCellValue('I'.$i, '-');
			}
			
			if($v['endtime']) {
				$resultPHPExcel->getActiveSheet()->setCellValue('J'.$i, date('Y-m-d',$v['endtime']+$this->config->item('borrow_duration')[$v['borrow_duration']]*86400));
			} else {
				$resultPHPExcel->getActiveSheet()->setCellValue('J'.$i, '-');
			}
			
			$resultPHPExcel->getActiveSheet()->setCellValue('K'.$i, $this->config->item('repayment_type')[$v['repayment_type']]);
			$resultPHPExcel->getActiveSheet()->setCellValue('L'.$i, $this->config->item('borrow_status')[$v['borrow_status']]);
			$resultPHPExcel->getActiveSheet()->setCellValue('M'.$i, $v['guarantor']);
		}
		$outputFileName = '借款数据.xls'; 
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
	
	/** 还款列表 */
	public function repayment_list() {
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
		if(!empty($search['guarantor'])) {
			$data['guarantor'] = trim(trim($search['guarantor']), '\t');
			$where['guarantor'] = $data['guarantor'];
		}
		if(!empty($search['status'])) {
			$data['status'] = trim(trim($search['status']), '\t');
			$where['borrow_status'] = $data['status'];
		}
		$where['del'] = 0;
		$where['borrow_status'] = 4;
		$per_page = 10;
		$current_page = $this->uri->segment(3) ? $this->uri->segment(3) - 1 : 0;
        $offset = $current_page;
        $config['base_url'] = base_url('borrow/repayment_list');
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
		$data['skip_page'] = $this->pagination->create_skip_link();
        $data['p'] = $current_page;
        $borrow = $this->borrow_model->get_borrow_related($per_page, $offset * $per_page, $where);
        $data['borrow'] = $borrow;
		$this->load->view('borrow/repayment_list', $data);
	}
	
	/** 已完成列表 */
	public function complete() {
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
		if(!empty($search['guarantor'])) {
			$data['guarantor'] = trim(trim($search['guarantor']), '\t');
			$where['guarantor'] = $data['guarantor'];
		}
		if(!empty($search['status'])) {
			$data['status'] = trim(trim($search['status']), '\t');
			$where['borrow_status'] = $data['status'];
		}
		$where['borrow_status >='] = 5;
		$per_page = 10;
		$current_page = $this->uri->segment(3) ? $this->uri->segment(3) - 1 : 0;
        $offset = $current_page;
        $config['base_url'] = base_url('borrow/complete');
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
		$data['skip_page'] = $this->pagination->create_skip_link();
        $data['p'] = $current_page;
        $borrow = $this->borrow_model->get_borrow_related($per_page, $offset * $per_page, $where);
		foreach($borrow as $k=>$v) {
			//获取实际还款时间
			$borrow[$k]['real_repayment_time'] = $this->borrow_model->get_detail_one_bybid($v['id'])['repayment_time'];
		}
        $data['borrow'] = $borrow;
		$this->load->view('borrow/complete', $data);
	}
	
	/** 放款 */
	public function putmoney(){
		$id = $this->uri->segment(3);
		record_adminlog($this->router->fetch_class(), $this->router->fetch_method(), $id, '标的列表-放款');
		if(IS_POST){
			if($id <= 0){
				$this->error('加载数据出错!');
			}
			//调取标信息
			$borrow = $this->borrow_model->get_borrow_byid($id);
			//调取借款人信息
			$meminfo = $this->member_model->get_member_info_byuid($borrow['borrow_uid']);
			//调取所有投资人信息
			$investor = $this->borrow_model->get_investor_all($id);
			//发红包是要用到该数据
			$investor_red = $investor;
			//时间戳
			$timestamp = time();
			//最后还款日
			$deadline = $timestamp + $this->config->item('borrow_duration')[$borrow['borrow_duration']]*86400;
			//请求放款接口CG1021,
			//组织放款数据，
			$max_index = count(array_keys($investor)) - 1;
			$this->load->model(array('paytest/paytest_model'));
			$params['subjectNo'] = $borrow['subjectNo'];
			$params['payeeAcctNo'] = $meminfo['acctNo'];
			foreach($investor as $key=>$value) {
				if($value['loan_status']  > 0) {
					continue;
				}
				$params['incomeAmt'] = 0;//round(floatval($value['investor_capital']*$borrow['service_money']/100), 2);
				$params['payerAcctNo'] = $value['acctNo'];
				$params['subjectAuthCode'] = $value['subjectAuthCode'];
				$params['payerAmount'] = $value['investor_capital'];
				if($key === $max_index) {
					$params['subjectFlag'] = '01';
					$params['subjectTerm'] = date('Ymd', $deadline);
				} else {
					$params['subjectFlag'] = '00';
				}
				$head = head($params, 'CG1021', 'putmoney');
				water($meminfo['uid'], $head['merOrderNo'], 'CG1021', $id);
				unset($head['callbackUrl'], $head['registerPhone'], $head['responsePath'], $head['url']);
				$data = $head;
				$data = json_encode($data);
				$url = $this->config->item('Interface').'1021';
				$str = post_curl_test($url, $data);
				write_log($str);
				$tmp_body = $this->paytest_model->excute($str);
				if($tmp_body['head']['respCode'] == '000000') {
					$database['investor'] = array(
						'id' => $value['id'],
						'loan_status' => 1,
						'nid'		  => $tmp_body['head']['merOrderNo']
					);
					$this->borrow_model->set_investor_loan($database['investor']);
					$database = array();
				} else {
					$this->error($tmp_body['head']['respDesc']);
				}
				//$body[$value['id']] = $tmp_body;
			}
			//统一处理返回的数据
			
			//还款时间
			$borrow_duration = $this->config->item('borrow_duration')[$borrow['borrow_duration']];
			//总利息
			$interest = round($borrow['borrow_money'] * (($borrow['borrow_interest_rate'] + $borrow['add_rate']) / 100 / 360) * $borrow_duration,2);
			//还款类型
			$repayment_type = $borrow['repayment_type'];
			//修改主表状态
			$data = array();
			//如果是提前放款，借款金额要修改为实际放款的金额
			$data['borrow'] = array(
				'id'	=> $id,
				'endtime' => time(),
				'borrow_status' => 4,
				'borrow_money' => $borrow['has_borrow']
			);
			//$this->borrow_model->modify_borrow($b_d, $id);
			//第二张表
			//调取所有投资人已放款成功的
			$investor = $this->borrow_model->get_investor_all($id, 1);
			if(empty($investor)) {
				$this->error('已放款完成');
			}
			foreach($investor as $value) {
				$data['investor'][] = array(
					'id' => $value['id'],
					'deadline' => $deadline,
					'status' => 4,
					'loan_status' => 2
				);
			}
			//$this->borrow_model->modify_borrow_investor($b_i, $id);
			//第三张表数据 【一次性到期还本息， 按月付息到期还本】
			//记录投资人原有账户的金额
			$acountinfo = array();
			//借款人
			$memoney_borrow = $this->info_model->get_money($borrow['borrow_uid']);
			$acountinfo['borrow']['account_money'] = $memoney_borrow['account_money'];
			$acountinfo['borrow']['money_collect'] = $memoney_borrow['money_collect'];
			$acountinfo['borrow']['money_freeze'] = $memoney_borrow['money_freeze'];
			//投资人
			foreach($investor as $v) {
				$memoney = $this->info_model->get_money($v['investor_uid']);
				$acountinfo['investor'][$v['investor_uid']]['account_money'] = $memoney['account_money'];
				$acountinfo['investor'][$v['investor_uid']]['money_collect'] = $memoney['money_collect'];
				$acountinfo['investor'][$v['investor_uid']]['money_freeze'] = $memoney['money_freeze'];
			}
			//一次性到期还本息
			if($repayment_type === '1' || ($repayment_type === '2' && $borrow_duration == '33')) {
				$data['borrow']['total'] = 1;
				
				foreach($investor as $value) {
					$data['detail'][] = array(
						'repayment_time' 	=> 0,
						'borrow_id'			=> $id,
						'invest_id'			=> $value['id'],
						'investor_uid'      => $value['investor_uid'],
						'borrow_uid'        => $value['borrow_uid'],
						'capital'  			=> $value['investor_capital'],
						'interest' 			=> round($value['investor_capital'] * (($borrow['borrow_interest_rate'] + $borrow['add_rate']) / 100 / 360) * $borrow_duration,2),
						'receive_capital' 	=> '0.00',
						'receive_interest' 	=> '0.00',
						'status'			=> 4,
						'sort_order'		=> 1,
						'total'             => 1,
						'deadline' 			=> $deadline,
					);
					//$memoney = $this->info_model->get_money($value['investor_uid']);
					//$tmp_money_collect = $value['investor_capital'];
					//$tmp_money_freeze = $memoney['money_freeze'] - $value['investor_capital'];
					$acountinfo['investor'][$value['investor_uid']]['money_collect'] += $value['investor_capital'];
					$acountinfo['investor'][$value['investor_uid']]['money_freeze'] -= $value['investor_capital'];
					// $data['money'][] = array(
						// 'uid'			=> $value['investor_uid'],
						// 'money_freeze' => $tmp_money_freeze,
						// 'money_collect' => $memoney['money_collect'] + $tmp_money_collect
					// );
					$data['log'][] = array(
						'uid' => $value['investor_uid'],
						'type' => 2,//解冻
						'affect_money' => $value['investor_capital'],
						'account_money' => $acountinfo['investor'][$value['investor_uid']]['account_money'],//可用
						'collect_money' => $acountinfo['investor'][$value['investor_uid']]['money_collect'],//待收
						'freeze_money' => $acountinfo['investor'][$value['investor_uid']]['money_freeze'],//冻结
						'info' => '出借完成【' . $borrow['borrow_name'] . '】',
						'add_time' => $timestamp,
						//'add_ip' => $this->input->ip_address(),
						'bid' => $value['borrow_id'],
						'nid'	=> $value['nid']
					);
					//借款人
					//$memoney_borrow = $this->info_model->get_money($borrow['borrow_uid']);
					$acountinfo['borrow']['account_money'] += $value['investor_capital'];
					// $data['money'][] = array(
						// 'uid'			=> $borrow['borrow_uid'],
						// 'account_money' => $memoney_borrow['account_money'] + $value['investor_capital']
					// );
					
					$data['log'][] = array(
						'uid' => $borrow['borrow_uid'],
						'type' => 9,//收款
						'affect_money' => $value['investor_capital'],
						'account_money' => $acountinfo['borrow']['account_money'],//可用
						'collect_money' => $acountinfo['borrow']['money_collect'],//待收
						'freeze_money' => $acountinfo['borrow']['money_freeze'],//冻结
						'info' => $value['investor_capital'] . '元,收款',
						'add_time' => $timestamp,
						//'add_ip' => $this->input->ip_address(),
						'bid' => $value['borrow_id'],
						'nid'	=> $value['nid']
					);
				}
			}
			//按月付息到期还本
			if($repayment_type === '2' && $borrow_duration !== '33') {
				if($borrow_duration == '65') {
					$iterator = 2;
				}
				if($borrow_duration == '97') {
					$iterator = 3;
				}
				$data['borrow']['total'] = $iterator;
				foreach($investor as $value) {
					for($i = 0; $i < $iterator; $i++) {
						if($i + 1 != $iterator) {
							$tmp_interest = round($value['investor_capital'] * (($borrow['borrow_interest_rate'] + $borrow['add_rate']) / 100 / 360) * 30,2);
							$tmp_deadline = get_next_month($timestamp, $i+1);
						} else {
							$tmp_interest = round($value['investor_capital'] * (($borrow['borrow_interest_rate'] + $borrow['add_rate']) / 100 / 360) * ($borrow_duration-30*$i),2);
							$tmp_deadline = $deadline;
						}
						$data['detail'][] = array(
							'repayment_time' 	=> 0,
							'borrow_id'			=> $id,
							'invest_id'			=> $value['id'],
							'investor_uid'      => $value['investor_uid'],
							'borrow_uid'        => $value['borrow_uid'],
							'capital'  			=> (($i + 1) == $iterator) ? $value['investor_capital'] : '0.00',
							'interest' 			=> $tmp_interest,
							'receive_capital' 	=> '0.00',
							'receive_interest' 	=> '0.00',
							'status'			=> 4,
							'sort_order'		=> $i + 1,
							'total'             => $iterator,
							'deadline' 			=> $tmp_deadline
						);
					}
					// $memoney = $this->info_model->get_money($value['investor_uid']);
					// $tmp_money_collect = $value['investor_capital'];
					// $tmp_money_freeze = $memoney['money_freeze'] - $value['investor_capital'];
					$acountinfo['investor'][$value['investor_uid']]['money_collect'] += $value['investor_capital'];
					$acountinfo['investor'][$value['investor_uid']]['money_freeze'] -= $value['investor_capital'];
					// $data['money'][] = array(
						// 'uid'			=> $value['investor_uid'],
						// 'money_freeze' => $tmp_money_freeze,
						// 'money_collect' => $memoney['money_collect'] + $tmp_money_collect
					// );
					$data['log'][] = array(
						'uid' => $value['investor_uid'],
						'type' => 2,
						'affect_money' => $value['investor_capital'],
						'account_money' => $acountinfo['investor'][$value['investor_uid']]['account_money'],//可用
						'collect_money' => $acountinfo['investor'][$value['investor_uid']]['money_collect'],//待收
						'freeze_money' => $acountinfo['investor'][$value['investor_uid']]['money_freeze'],//冻结
						'info' => '出借完成【' . $borrow['borrow_name'] . '】',
						'add_time' => $timestamp,
						//'add_ip' => $this->input->ip_address(),
						'bid' => $value['borrow_id'],
						'nid'	=> $value['nid']
					);
					//借款人
					// $memoney_borrow = $this->info_model->get_money($borrow['borrow_uid']);
					$acountinfo['borrow']['account_money'] += $value['investor_capital'];
					// $data['money'][] = array(
						// 'uid'			=> $borrow['borrow_uid'],
						// 'account_money' => $memoney_borrow['account_money'] + $value['investor_capital']
					// );
					$data['log'][] = array(
						'uid' => $borrow['borrow_uid'],
						'type' => 9,//收款
						'affect_money' => $value['investor_capital'],
						'account_money' => $acountinfo['borrow']['account_money'],//可用
						'collect_money' => $acountinfo['borrow']['money_collect'],//待收
						'freeze_money' => $acountinfo['borrow']['money_freeze'],//冻结
						'info' => $value['investor_capital'] . '元,收款',
						'add_time' => $timestamp,
						//'add_ip' => $this->input->ip_address(),
						'bid' => $value['borrow_id'],
						'nid'	=> $value['nid']
					);
				}
			}
			//计算账户金额变动
			//借款人
			$data['money'][] = array(
				'uid' => $borrow['borrow_uid'],
				'account_money' => $acountinfo['borrow']['account_money'],
				'money_collect' => $acountinfo['borrow']['money_collect'],
				'money_freeze' => $acountinfo['borrow']['money_freeze']
			);
			//发红包
			$data_red = array();
			$data_red['payerAcctNo'] = $this->config->item('mchnt_red');
			foreach($investor_red as $k=>$v) {
				if($v['redid'] > 0) {
					$data_red['payeeAcctNo'] = $v['acctNo'];
					$info_red = $this->member_model->get_packet_byid($v['redid'], $v['investor_uid']);
					$data_red['amount'] = $info_red['money'];
					$data_red['payType'] = '02';
					$data_red['uid'] = $v['investor_uid'];
					$data_red['bid'] = $id;
					$data_red['redid'] = $v['redid'];
					$red_return = $this->red($data_red);
					if($red_return['status'] === true) {
						$data['log'][] = array(
							'uid' => $v['investor_uid'],
							'type' => 6,//投资红包
							'affect_money' => $data_red['amount'],
							'account_money' => $acountinfo['investor'][$v['investor_uid']]['account_money'] + $data_red['amount'],//可用
							'collect_money' => $acountinfo['investor'][$v['investor_uid']]['money_collect'],//待收
							'freeze_money' => $acountinfo['investor'][$v['investor_uid']]['money_freeze'],//冻结
							'info' => $data_red['amount'] . '元,出借红包',
							'add_time' => $timestamp,
							//'add_ip' => $this->input->ip_address(),
							'bid' => $v['borrow_id'],
							'nid'	=> $red_return['no']
						);
						$acountinfo['investor'][$v['investor_uid']]['account_money'] += $data_red['amount'];
					} else {
						$data['log'][] = array(
							'uid' => $v['investor_uid'],
							'type' => 14,//投资红包失败
							'affect_money' => $data_red['amount'],
							'account_money' => $acountinfo['investor'][$v['investor_uid']]['account_money'],//可用
							'collect_money' => $acountinfo['investor'][$v['investor_uid']]['money_collect'],//待收
							'freeze_money' => $acountinfo['investor'][$v['investor_uid']]['money_freeze'],//冻结
							'info' => $data_red['amount'] . '元,出借红包, '. $red_return['desc'],
							'add_time' => $timestamp,
							//'add_ip' => $this->input->ip_address(),
							'bid' => $v['borrow_id'],
							'nid'	=> $red_return['no']
						);
						//$acountinfo['investor'][$v['investor_uid']]['account_money'] += $data_red['amount'];
					}
					
				}
			}
			//投资人
			foreach($acountinfo['investor'] as $k=>$v) {
				$data['money'][] = array(
					'uid' => $k,
					'account_money' => $v['account_money'],
					'money_collect' => $v['money_collect'],
					'money_freeze' => $v['money_freeze']
				);
			}
			//p($data);die;//p($data_red);die;
			$this->borrow_model->loan($data);
			
			//自动发积分, 3-1存管新手标不发积分,2019-5-17暂停一天发积分
			if($borrow['borrow_type'] != 2 && $borrow['borrow_type'] != 5 && (date('Y-m-d') != '2019-05-17')) {
				$this->auto_add_score($id, $borrow['borrow_duration']); 
			}
			
			
			//自动发卡片,2018-12-30 17:23, 2019-2-11 00:00:00 ,停止发放卡片
			/* if(time() > strtotime('2019-01-01 00:00:00') && time() < strtotime('2019-02-11 00:00:00')) {
				$this->load->database();
				$this->db->trans_begin();
				$this->auto_add_card($id, $borrow['borrow_duration']);
				if ($this->db->trans_status() === FALSE) {
					$this->db->trans_rollback();
					log_record('发放卡片失败', '/zcard.log', 'bid-' . $id);
				} else {
					$this->db->trans_commit();
				}
			} */
			
			
			//自动发放抽奖次数
			//$this->auto_add_times($id, $borrow['borrow_duration']);
			
			// 自动发放福袋次数，2019-2-26
			// if($borrow['borrow_type'] != 5) {
				// $this->auto_add_zcash($id, $borrow['borrow_duration']);
			// }
			
			
			$this->success('放款成功', 'borrow/index');
			//p($data);die;
		}
	}
	/** 发放福袋 */
	private function auto_add_zcash($bid, $duration) {
		$investor = $this->borrow_model->get_investor_all($bid, 2);
		$timestamp = time();
		$this->load->model('cj/zcash_model');
		$this->db->trans_begin();
		foreach($investor as $k=>$v) {
			// 活动时间 3.1-3.31，发放现金红包
			if($v['add_time'] < strtotime('2019-03-01 00:00:00') || $v['add_time'] > strtotime('2019-03-31 23:59:59')) {
				continue;
			}
			$zcash = $this->zcash_model->get_by_uid($v['investor_uid']);
			if($duration == 4) {//97天
				$cash_total = 0;
				$investor_capital = $v['investor_capital'] + round($zcash['money97'], 2);
				if($investor_capital >= 20000) {
					$cash_total = intval($investor_capital/20000);
					$investor_capital -= intval($investor_capital/20000)*20000;
				}
			} else if($duration == 2) { // 33天
				$cash_total = 0;
				$investor_capital = $v['investor_capital'] + round($zcash['money33'], 2);
				if($investor_capital >= 50000) {
					$cash_total = intval($investor_capital/50000);
					$investor_capital -= intval($investor_capital/50000)*50000;
				}
			} else {
				continue;
			}
			//插入数据库
			$data = array();
			if(isset($cash_total) && $cash_total > 0) {
				$zcash = $this->zcash_model->get_by_uid($v['investor_uid']);
				$data['zcash'] = [
					'id' => empty($zcash) ? null : $zcash['id'],
					'uid' => $v['investor_uid'],
					'total'	=> intval($zcash['total']) + $cash_total,
				];
				if(empty($zcash)) {
					$data['zcash']['addtime'] = $timestamp;
				} else {
					$data['zcash']['uptime'] = $timestamp;
				}
				$day_duration = $this->config->item('borrow_duration')[$duration];
				$data['zcash']['money'.$day_duration] = $investor_capital;
				$data['record'] = [
					'uid' => $v['investor_uid'],
					'num' => $cash_total,
					'addtime' => time(),
					'bid'	=> $bid,
					'type'	=> 1
				];
				$this->zcash_model->modify_cash($data['zcash']);
				$this->zcash_model->add_record($data['record']);
			} else {
				$data['zcash'] = [
					'id' => empty($zcash) ? null : $zcash['id'],
					'uid' => $v['investor_uid'],
				];
				if(empty($zcash)) {
					$data['zcash']['addtime'] = $timestamp;
				} else {
					$data['zcash']['uptime'] = $timestamp;
				}
				$day_duration = $this->config->item('borrow_duration')[$duration];
				$data['zcash']['money'.$day_duration] = $investor_capital;
				$this->zcash_model->modify_cash($data['zcash']);
			}
		}
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			log_record('发放抽奖次数失败', '/zcard.log', 'bid-' . $bid);
		} else {
			$this->db->trans_commit();
		}
	}
	// 临时放款
	public function test_loan() {die;
		$id = $this->uri->segment(3);
		record_adminlog($this->router->fetch_class(), $this->router->fetch_method(), $id, '标的列表-放款');
		if(IS_POST){
			if($id <= 0){
				$this->error('加载数据出错!');
			}
			//调取标信息
			$borrow = $this->borrow_model->get_borrow_byid($id);
			//调取借款人信息
			$meminfo = $this->member_model->get_member_info_byuid($borrow['borrow_uid']);
			//调取所有投资人信息
			$investor = $this->borrow_model->get_investor_all($id);
			//发红包是要用到该数据
			$investor_red = $investor;
			//时间戳
			$timestamp = time();
			//最后还款日
			$deadline = $timestamp + $this->config->item('borrow_duration')[$borrow['borrow_duration']]*86400;
			//请求放款接口CG1021,
			//组织放款数据，
			$max_index = count(array_keys($investor)) - 1;
			$this->load->model(array('paytest/paytest_model'));
			$params['subjectNo'] = $borrow['subjectNo'];
			$params['payeeAcctNo'] = $meminfo['acctNo'];
			// foreach($investor as $key=>$value) {
				// $params['incomeAmt'] = 0;//round(floatval($value['investor_capital']*$borrow['service_money']/100), 2);
				// $params['payerAcctNo'] = $value['acctNo'];
				// $params['subjectAuthCode'] = $value['subjectAuthCode'];
				// $params['payerAmount'] = $value['investor_capital'];
				// if($key === $max_index) {
					// $params['subjectFlag'] = '01';
					// $params['subjectTerm'] = date('Ymd', $deadline);
				// } else {
					// $params['subjectFlag'] = '00';
				// }
				// $head = head($params, 'CG1021', 'putmoney');
				// water($meminfo['uid'], $head['merOrderNo'], 'CG1021', $id);
				// unset($head['callbackUrl'], $head['registerPhone'], $head['responsePath'], $head['url']);
				// $data = $head;
				// $data = json_encode($data);
				// $url = $this->config->item('Interface').'1021';
				// $str = post_curl_test($url, $data);
				// $tmp_body = $this->paytest_model->excute($str);
				// if($tmp_body['head']['respCode'] == '000000') {
					// $database['investor'] = array(
						// 'id' => $value['id'],
						// 'loan_status' => 1,
						// 'nid'		  => $tmp_body['head']['merOrderNo']
					// );
					// $this->borrow_model->set_investor_loan($database['investor']);
					// $database = array();
				// } else {
					// $this->error($tmp_body['head']['respDesc']);
				// }
				//$body[$value['id']] = $tmp_body;
			// }
			//统一处理返回的数据
			
			//还款时间
			$borrow_duration = $this->config->item('borrow_duration')[$borrow['borrow_duration']];
			//总利息
			$interest = round($borrow['borrow_money'] * (($borrow['borrow_interest_rate'] + $borrow['add_rate']) / 100 / 360) * $borrow_duration,2);
			//还款类型
			$repayment_type = $borrow['repayment_type'];
			//修改主表状态
			$data = array();
			//如果是提前放款，借款金额要修改为实际放款的金额
			$data['borrow'] = array(
				'id'	=> $id,
				'endtime' => time(),
				'borrow_status' => 4,
				'borrow_money' => $borrow['has_borrow']
			);
			//$this->borrow_model->modify_borrow($b_d, $id);
			//第二张表
			//调取所有投资人已放款成功的
			$investor = $this->borrow_model->get_investor_all($id, 1);
			if(empty($investor)) {
				$this->error('已放款完成');
			}
			foreach($investor as $value) {
				$data['investor'][] = array(
					'id' => $value['id'],
					'deadline' => $deadline,
					'status' => 4,
					'loan_status' => 2
				);
			}
			//$this->borrow_model->modify_borrow_investor($b_i, $id);
			//第三张表数据 【一次性到期还本息， 按月付息到期还本】
			//记录投资人原有账户的金额
			$acountinfo = array();
			//借款人
			$memoney_borrow = $this->info_model->get_money($borrow['borrow_uid']);
			$acountinfo['borrow']['account_money'] = $memoney_borrow['account_money'];
			$acountinfo['borrow']['money_collect'] = $memoney_borrow['money_collect'];
			$acountinfo['borrow']['money_freeze'] = $memoney_borrow['money_freeze'];
			//投资人
			foreach($investor as $v) {
				$memoney = $this->info_model->get_money($v['investor_uid']);
				$acountinfo['investor'][$v['investor_uid']]['account_money'] = $memoney['account_money'];
				$acountinfo['investor'][$v['investor_uid']]['money_collect'] = $memoney['money_collect'];
				$acountinfo['investor'][$v['investor_uid']]['money_freeze'] = $memoney['money_freeze'];
			}
			//一次性到期还本息
			if($repayment_type === '1' || ($repayment_type === '2' && $borrow_duration == '33')) {
				$data['borrow']['total'] = 1;
				
				foreach($investor as $value) {
					$data['detail'][] = array(
						'repayment_time' 	=> 0,
						'borrow_id'			=> $id,
						'invest_id'			=> $value['id'],
						'investor_uid'      => $value['investor_uid'],
						'borrow_uid'        => $value['borrow_uid'],
						'capital'  			=> $value['investor_capital'],
						'interest' 			=> round($value['investor_capital'] * (($borrow['borrow_interest_rate'] + $borrow['add_rate']) / 100 / 360) * $borrow_duration,2),
						'receive_capital' 	=> '0.00',
						'receive_interest' 	=> '0.00',
						'status'			=> 4,
						'sort_order'		=> 1,
						'total'             => 1,
						'deadline' 			=> $deadline,
					);
					//$memoney = $this->info_model->get_money($value['investor_uid']);
					//$tmp_money_collect = $value['investor_capital'];
					//$tmp_money_freeze = $memoney['money_freeze'] - $value['investor_capital'];
					$acountinfo['investor'][$value['investor_uid']]['money_collect'] += $value['investor_capital'];
					$acountinfo['investor'][$value['investor_uid']]['money_freeze'] -= $value['investor_capital'];
					// $data['money'][] = array(
						// 'uid'			=> $value['investor_uid'],
						// 'money_freeze' => $tmp_money_freeze,
						// 'money_collect' => $memoney['money_collect'] + $tmp_money_collect
					// );
					$data['log'][] = array(
						'uid' => $value['investor_uid'],
						'type' => 2,//解冻
						'affect_money' => $value['investor_capital'],
						'account_money' => $acountinfo['investor'][$value['investor_uid']]['account_money'],//可用
						'collect_money' => $acountinfo['investor'][$value['investor_uid']]['money_collect'],//待收
						'freeze_money' => $acountinfo['investor'][$value['investor_uid']]['money_freeze'],//冻结
						'info' => '出借完成【' . $borrow['borrow_name'] . '】',
						'add_time' => $timestamp,
						//'add_ip' => $this->input->ip_address(),
						'bid' => $value['borrow_id'],
						'nid'	=> $value['nid']
					);
					//借款人
					//$memoney_borrow = $this->info_model->get_money($borrow['borrow_uid']);
					$acountinfo['borrow']['account_money'] += $value['investor_capital'];
					// $data['money'][] = array(
						// 'uid'			=> $borrow['borrow_uid'],
						// 'account_money' => $memoney_borrow['account_money'] + $value['investor_capital']
					// );
					
					$data['log'][] = array(
						'uid' => $borrow['borrow_uid'],
						'type' => 9,//收款
						'affect_money' => $value['investor_capital'],
						'account_money' => $acountinfo['borrow']['account_money'],//可用
						'collect_money' => $acountinfo['borrow']['money_collect'],//待收
						'freeze_money' => $acountinfo['borrow']['money_freeze'],//冻结
						'info' => $value['investor_capital'] . '元,收款',
						'add_time' => $timestamp,
						//'add_ip' => $this->input->ip_address(),
						'bid' => $value['borrow_id'],
						'nid'	=> $value['nid']
					);
				}
			}
			//按月付息到期还本
			if($repayment_type === '2' && $borrow_duration !== '33') {
				if($borrow_duration == '65') {
					$iterator = 2;
				}
				if($borrow_duration == '97') {
					$iterator = 3;
				}
				$data['borrow']['total'] = $iterator;
				foreach($investor as $value) {
					for($i = 0; $i < $iterator; $i++) {
						if($i + 1 != $iterator) {
							$tmp_interest = round($value['investor_capital'] * (($borrow['borrow_interest_rate'] + $borrow['add_rate']) / 100 / 360) * 30,2);
							$tmp_deadline = get_next_month($timestamp, $i+1);
						} else {
							$tmp_interest = round($value['investor_capital'] * (($borrow['borrow_interest_rate'] + $borrow['add_rate']) / 100 / 360) * ($borrow_duration-30*$i),2);
							$tmp_deadline = $deadline;
						}
						$data['detail'][] = array(
							'repayment_time' 	=> 0,
							'borrow_id'			=> $id,
							'invest_id'			=> $value['id'],
							'investor_uid'      => $value['investor_uid'],
							'borrow_uid'        => $value['borrow_uid'],
							'capital'  			=> (($i + 1) == $iterator) ? $value['investor_capital'] : '0.00',
							'interest' 			=> $tmp_interest,
							'receive_capital' 	=> '0.00',
							'receive_interest' 	=> '0.00',
							'status'			=> 4,
							'sort_order'		=> $i + 1,
							'total'             => $iterator,
							'deadline' 			=> $tmp_deadline
						);
					}
					// $memoney = $this->info_model->get_money($value['investor_uid']);
					// $tmp_money_collect = $value['investor_capital'];
					// $tmp_money_freeze = $memoney['money_freeze'] - $value['investor_capital'];
					$acountinfo['investor'][$value['investor_uid']]['money_collect'] += $value['investor_capital'];
					$acountinfo['investor'][$value['investor_uid']]['money_freeze'] -= $value['investor_capital'];
					// $data['money'][] = array(
						// 'uid'			=> $value['investor_uid'],
						// 'money_freeze' => $tmp_money_freeze,
						// 'money_collect' => $memoney['money_collect'] + $tmp_money_collect
					// );
					$data['log'][] = array(
						'uid' => $value['investor_uid'],
						'type' => 2,
						'affect_money' => $value['investor_capital'],
						'account_money' => $acountinfo['investor'][$value['investor_uid']]['account_money'],//可用
						'collect_money' => $acountinfo['investor'][$value['investor_uid']]['money_collect'],//待收
						'freeze_money' => $acountinfo['investor'][$value['investor_uid']]['money_freeze'],//冻结
						'info' => '出借完成【' . $borrow['borrow_name'] . '】',
						'add_time' => $timestamp,
						//'add_ip' => $this->input->ip_address(),
						'bid' => $value['borrow_id'],
						'nid'	=> $value['nid']
					);
					//借款人
					// $memoney_borrow = $this->info_model->get_money($borrow['borrow_uid']);
					$acountinfo['borrow']['account_money'] += $value['investor_capital'];
					// $data['money'][] = array(
						// 'uid'			=> $borrow['borrow_uid'],
						// 'account_money' => $memoney_borrow['account_money'] + $value['investor_capital']
					// );
					$data['log'][] = array(
						'uid' => $borrow['borrow_uid'],
						'type' => 9,//收款
						'affect_money' => $value['investor_capital'],
						'account_money' => $acountinfo['borrow']['account_money'],//可用
						'collect_money' => $acountinfo['borrow']['money_collect'],//待收
						'freeze_money' => $acountinfo['borrow']['money_freeze'],//冻结
						'info' => $value['investor_capital'] . '元,收款',
						'add_time' => $timestamp,
						//'add_ip' => $this->input->ip_address(),
						'bid' => $value['borrow_id'],
						'nid'	=> $value['nid']
					);
				}
			}
			//计算账户金额变动
			//借款人
			$data['money'][] = array(
				'uid' => $borrow['borrow_uid'],
				'account_money' => $acountinfo['borrow']['account_money'],
				'money_collect' => $acountinfo['borrow']['money_collect'],
				'money_freeze' => $acountinfo['borrow']['money_freeze']
			);
			//发红包
			$data_red = array();
			$data_red['payerAcctNo'] = $this->config->item('mchnt_red');
			foreach($investor_red as $k=>$v) {
				if($v['redid'] > 0) {
					$data_red['payeeAcctNo'] = $v['acctNo'];
					$info_red = $this->member_model->get_packet_byid($v['redid'], $v['investor_uid']);
					$data_red['amount'] = $info_red['money'];
					$data_red['payType'] = '02';
					$data_red['uid'] = $v['investor_uid'];
					$data_red['bid'] = $id;
					$data_red['redid'] = $v['redid'];
					$red_return = $this->red($data_red);
					if($red_return['status'] === true) {
						$data['log'][] = array(
							'uid' => $v['investor_uid'],
							'type' => 6,//投资红包
							'affect_money' => $data_red['amount'],
							'account_money' => $acountinfo['investor'][$v['investor_uid']]['account_money'] + $data_red['amount'],//可用
							'collect_money' => $acountinfo['investor'][$v['investor_uid']]['money_collect'],//待收
							'freeze_money' => $acountinfo['investor'][$v['investor_uid']]['money_freeze'],//冻结
							'info' => $data_red['amount'] . '元,出借红包',
							'add_time' => $timestamp,
							//'add_ip' => $this->input->ip_address(),
							'bid' => $v['borrow_id'],
							'nid'	=> $red_return['no']
						);
						$acountinfo['investor'][$v['investor_uid']]['account_money'] += $data_red['amount'];
					} else {
						$data['log'][] = array(
							'uid' => $v['investor_uid'],
							'type' => 14,//投资红包失败
							'affect_money' => $data_red['amount'],
							'account_money' => $acountinfo['investor'][$v['investor_uid']]['account_money'],//可用
							'collect_money' => $acountinfo['investor'][$v['investor_uid']]['money_collect'],//待收
							'freeze_money' => $acountinfo['investor'][$v['investor_uid']]['money_freeze'],//冻结
							'info' => $data_red['amount'] . '元,出借红包, '. $red_return['desc'],
							'add_time' => $timestamp,
							//'add_ip' => $this->input->ip_address(),
							'bid' => $v['borrow_id'],
							'nid'	=> $red_return['no']
						);
						//$acountinfo['investor'][$v['investor_uid']]['account_money'] += $data_red['amount'];
					}
					
				}
			}
			//投资人
			foreach($acountinfo['investor'] as $k=>$v) {
				$data['money'][] = array(
					'uid' => $k,
					'account_money' => $v['account_money'],
					'money_collect' => $v['money_collect'],
					'money_freeze' => $v['money_freeze']
				);
			}
			//p($data);die;//p($data_red);die;
			$this->borrow_model->loan($data);
			
			//自动发积分
			$this->auto_add_score($id, $borrow['borrow_duration']); 
			
			//自动发卡片,2018-12-30 17:23, 2019-2-11 00:00:00 ,停止发放卡片
			/* if(time() > strtotime('2019-01-01 00:00:00') && time() < strtotime('2019-02-11 00:00:00')) {
				$this->load->database();
				$this->db->trans_begin();
				$this->auto_add_card($id, $borrow['borrow_duration']);
				if ($this->db->trans_status() === FALSE) {
					$this->db->trans_rollback();
					log_record('发放卡片失败', '/zcard.log', 'bid-' . $id);
				} else {
					$this->db->trans_commit();
				}
			} */
			
			
			//自动发放抽奖次数
			//$this->auto_add_times($id, $borrow['borrow_duration']);
			
			$this->success('放款成功', 'borrow/index');
			//p($data);die;
		}
	}
	/** 红包转账使用 */
	public function red($data = array()) {
		foreach($data as $k=>$v) {
			if($k == 'bid') continue;
			if(empty($data)) {
				return array(
					'status' => 0,
					'body' => '参数不正确', 
				);
			}
		}
		
		$params['payerAcctNo'] = $data['payerAcctNo'];
		$params['payeeAcctNo'] = $data['payeeAcctNo'];
		$params['amount'] = $data['amount'];
		$params['payType'] = $data['payType'];//'00':个人->个人'01':个人->商户'02':商户->个人
		$params['remark'] = '出借红包激活';
		$head = head($params, 'CG1008', 'send');
		water($data['uid'], $head['merOrderNo'], 'CG1008', $data['bid']);
		unset($head['callbackUrl'], $head['registerPhone'], $head['responsePath'], $head['url']);
		$head = json_encode($head);
		$url = $this->config->item('Interface').'1008';
		$str = post_curl_test($url, $head);
		$this->load->model(array('paytest/paytest_model'));
		$tmp_body = $this->paytest_model->excute($str);
		
		//回写数据
		$datas = array();
		if($tmp_body['head']['respCode'] === '000000') {
			$datas['merOrderNo'] = $tmp_body['head']['merOrderNo'];
			$datas['money']		= $data['amount'];
			$datas['redid']		= $data['redid'];
			$datas['status']	= 1;
			$this->load->model('water/water_model');
			$this->water_model->edit_water($datas, $tmp_body['head']['merOrderNo']);
			return array('status'=>true, 'no'=>$tmp_body['head']['merOrderNo']);
		} else {
			$datas['rid']		= $data['redid'];
			$datas['uid']		= $data['uid'] ? intval($data['uid']) : 0;
			$datas['bid']		= $data['bid'];
			$datas['money']		= $data['amount'];
			$datas['status']	= 0;
			$datas['addtime']	= time();
			$this->member_model->add_packet_error($datas);
			
			//回写错误日志
			$water_datas['merOrderNo'] = $tmp_body['head']['merOrderNo'];
			$water_datas['money']		= $data['amount'];
			$water_datas['redid']		= $data['redid'];
			$water_datas['status']	= 1;
			$this->load->model('water/water_model');
			$this->water_model->edit_water($water_datas, $tmp_body['head']['merOrderNo']);
			return array('status'=>false, 'no'=>$tmp_body['head']['merOrderNo'], 'desc'=>$tmp_body['head']['respDesc']);
		}
	}
	/** 随机发放 卡片 */
	/* public function auto_add_card($bid, $duration) {
		$investor = $this->borrow_model->get_investor_all($bid, 2);
		$timestamp = time();
		$this->load->model('zcard/zcard_model');
		foreach($investor as $k=>$v) {
			$card_total = 0;
			//如果投资日期不是在2019年1月1号以后，不发卡片
			if($v['add_time'] < strtotime('2019-01-01 00:00:00')) {
				continue;
			}
			// 2019-1-28,33天1.5万，65天1万，97,5000元
			$zcard = $this->zcard_model->get_by_uid($v['investor_uid']);
			if($duration == 2) {//33天
				$investor_capital = $v['investor_capital'] + round($zcard['money33'], 2);
				if($investor_capital >= 15000) {
					$card_total = intval($investor_capital/15000);
					$investor_capital -= intval($investor_capital/15000)*15000;
					
				}
			} else if($duration == 3) {//65天
				$investor_capital = $v['investor_capital'] + round($zcard['money65'], 2);
				if($investor_capital >= 10000) {
					$card_total = intval($investor_capital/10000);
					$investor_capital -= intval($investor_capital/10000)*10000;
					
				}
			} else if($duration == 4) {//97天
				$investor_capital = $v['investor_capital'] + round($zcard['money97'], 2);
				if($investor_capital >= 5000) {
					$card_total = intval($investor_capital/5000);
					$investor_capital -= intval($investor_capital/5000)*5000;
				}
			}
			//插入数据库
			$data = array();
			if($card_total > 0) {
				for($i=0; $i < $card_total; $i++) {
					$zcard = $this->zcard_model->get_by_uid($v['investor_uid']);
					// $value = $this->get_random();
					// $data['detail'] = [
						// 'uid' => $v['investor_uid'],
						// 'type' => $duration,
						// 'num' => $value,
						// 'addtime'=> $timestamp
					// ];
					$data['card'] = [
						'id' => empty($zcard) ? null : $zcard['id'],
						'uid' => $v['investor_uid'],
						//'card'.$value => intval($zcard['card'.$value]) + 1, 
						'total'	=> intval($zcard['total']) + 1,
					];
					if(empty($zcard)) {
						$data['card']['addtime'] = $timestamp;
					} else {
						$data['card']['uptime'] = $timestamp;
					}
					$day_duration = $this->config->item('borrow_duration')[$duration];
					$data['card']['money'. $day_duration] = $investor_capital;
					$data['card']['time'. $day_duration] = intval($zcard['time'.$day_duration]) + 1;
					//$this->zcard_model->insert_detail($data['detail']);
					$this->zcard_model->modify_card($data['card']);
				}
			} else {
				$data['card'] = [
					'id' => empty($zcard) ? null : $zcard['id'],
					'uid' => $v['investor_uid'],
				];
				if(empty($zcard)) {
					$data['card']['addtime'] = $timestamp;
				} else {
					$data['card']['uptime'] = $timestamp;
				}
				$day_duration = $this->config->item('borrow_duration')[$duration];
				$data['card']['money'. $day_duration] = $investor_capital;
				$this->zcard_model->modify_card($data['card']);
			}
		}
	} */
	/* public function auto_add_card($bid, $duration) {
		$investor = $this->borrow_model->get_investor_all($bid, 2);
		$timestamp = time();
		$this->load->model('zcard/zcard_model');
		foreach($investor as $k=>$v) {
			$card_total = 0;
			//如果投资日期不是在2019年1月1号以后，不发卡片
			if($v['add_time'] < strtotime('2019-01-01 00:00:00')) {
				continue;
			}
			$zcard = $this->zcard_model->get_by_uid($v['investor_uid']);
			if($duration == 2) {//33天
				$investor_capital = $v['investor_capital'] + round($zcard['money33'], 2);
				if($investor_capital >= 30000) {
					$card_total = intval($investor_capital/30000);
					$investor_capital -= intval($investor_capital/30000)*30000;
					
				}
			} else if($duration == 3) {//65天
				$investor_capital = $v['investor_capital'] + round($zcard['money65'], 2);
				if($investor_capital >= 20000) {
					$card_total = intval($investor_capital/20000);
					$investor_capital -= intval($investor_capital/20000)*20000;
					
				}
			} else if($duration == 4) {//97天
				$investor_capital = $v['investor_capital'] + round($zcard['money97'], 2);
				if($investor_capital >= 10000) {
					$card_total = intval($investor_capital/10000);
					$investor_capital -= intval($investor_capital/10000)*10000;
					
				}
			}
			
			//插入数据库
			$data = array();
			if($card_total > 0) {
				for($i=0; $i < $card_total; $i++) {
					$zcard = $this->zcard_model->get_by_uid($v['investor_uid']);
					$value = $this->get_random();
					$data['detail'] = [
						'uid' => $v['investor_uid'],
						'type' => $duration,
						'num' => $value,
						'addtime'=> $timestamp
					];
					$data['card'] = [
						'id' => empty($zcard) ? null : $zcard['id'],
						'uid' => $v['investor_uid'],
						'card'.$value => intval($zcard['card'.$value]) + 1,
						'total'	=> intval($zcard['total']) + 1,
					];
					if(empty($zcard)) {
						$data['card']['addtime'] = $timestamp;
					} else {
						$data['card']['uptime'] = $timestamp;
					}
					$day_duration = $this->config->item('borrow_duration')[$duration];
					$data['card']['money'. $day_duration] = $investor_capital;
					$data['card']['time'. $day_duration] = intval($zcard['time'.$day_duration]) + 1;
					$this->zcard_model->insert_detail($data['detail']);
					$this->zcard_model->modify_card($data['card']);
				}
			} else {
				$data['card'] = [
					'id' => empty($zcard) ? null : $zcard['id'],
					'uid' => $v['investor_uid'],
				];
				if(empty($zcard)) {
					$data['card']['addtime'] = $timestamp;
				} else {
					$data['card']['uptime'] = $timestamp;
				}
				$day_duration = $this->config->item('borrow_duration')[$duration];
				$data['card']['money'. $day_duration] = $investor_capital;
				$this->zcard_model->modify_card($data['card']);
			}
		}
	} */
	public function get_random() {
		$random = $this->random10();
		//设置一个权重，决定2,9出现的位置在600次中，600次是一个周期
		$weight2 = 0.3;
		$weight9 = 0.7;
		$this->load->model('zcard/zcard_model');
		//已发卡片数量
		$total = $this->zcard_model->get_total();
		//卡片限制条件决定2,9出现的位置在600次中，600次是一个周期
		//卡片2发放次数
		$card2_total = $this->zcard_model->get_by_num(2);
		//卡片9发放的次数
		$card9_total = $this->zcard_model->get_by_num(9);
		
		$cyc = 600;//周期是600
		//应该出现次数
		$cyc_num = ceil($total/$cyc);
		$cyc_num = $cyc_num > 10 ? 10 : $cyc_num;
		
		//判断是否应该出2或9
		if($random == 2) {
			if(($card2_total < $cyc_num) && ((($total - ($cyc_num-1)*$cyc)/$cyc) > $weight2)) {
				return $random;
			} else {
				$random = $this->random8();
			}
		}
		// && ((($total - ($cyc_num-1)*$cyc)/$cyc) > $weight9)
		if($random == 9) {
			if(($card9_total < $cyc_num) && ((($total - ($cyc_num-1)*$cyc)/$cyc) > $weight9)) {
				return $random;
			} else {
				$random = $this->random8();
			}
		}
		return $random;
	}
	public function random10() {
		$arr = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
		$digit = rand(0, 9);
		return $arr[$digit];
	}
	public function random8() {
		$arr = [1, 3, 4, 5, 6, 7, 8, 10];
		$digit = rand(0, 7);
		return $arr[$digit];
	}
	
	/** 添加积分 */
	private function auto_add_score($bid, $duration) {
		$investor = $this->borrow_model->get_investor_all($bid, 2);
		
		//积分期限, 根据标期限，获得积分不同，33天千分之0.5，65天千分之1, 97天千分之1.5
		//2018-11-13,积分变化，获得积分不同，33天千分之1，65天千分之2.6, 97天千分之4
		//2018-12-2,积分变化，获得积分不同，33天千分之1，65天千分之2, 97天千分之3
		//$duration = 1;
		if($duration == 2) {
			$score_rate = 1;
		} elseif($duration == 3) {
			$score_rate = 2;
		} elseif($duration == 4) {
			$score_rate = 3;
		} else {
			$score_rate = 0;
		}
		
		$ids = array();
		foreach($investor as $k=>$v) {
			array_push($ids, $v['id']);
		}
		//p($ids);
		$res = $this->member_model->get_socre_by_invest_ids($ids);
		$res = array_column($res, 'invest_id');
		foreach($investor as $k=>$v) {
			if(in_array($v['id'], $res)) {//已发积分
				continue;
			}
			//发积分
			$score = round($v['investor_capital']*$score_rate/1000, 2);
			if($score < 0.01) {
				continue;
			}
			//增加积分
			$data = array();
			$data['uid'] = $v['investor_uid'];
			$data['bid'] = $bid;
			$data['invest_id'] = $v['id'];
			$data['score'] = $score;
			$data['genre'] = 2;
			$data['adminid'] = 0;
			$data['addtime'] = time();
			if(!$this->member_model->set_member_info_totalscore($data)) {
				$this->error('自动添加积分出错，请联系管理员'); 
			}
		}
	}
	/** 自动发放抽奖次数 */
	private function auto_add_times($bid, $duration) {
		$investor = $this->borrow_model->get_investor_all($bid, 2);
		$timestamp = time();
		$this->load->model('times/times_model');
		foreach($investor as $k=>$v) {
			$times = $this->times_model->get_times_byuid($v['investor_uid'], $duration);
			//没有数据
			if(empty($times)) {
				//抽奖次数
				$current_times = 0;
				//累积金额
				$investor_capital = $v['investor_capital'];
				if($duration == 2) {//33天
					if($investor_capital >= 50000) {
						$investor_capital = $investor_capital - intval($investor_capital/50000)*50000;
						$current_times = intval($v['investor_capital']/50000);
					}
				} else if($duration == 3) {//65天
					if($investor_capital >= 30000) {
						$investor_capital = $investor_capital - intval($investor_capital/30000)*30000;
						$current_times = intval($v['investor_capital']/30000);
					}
				} else if($duration == 4) {//97天
					if($investor_capital >= 15000) {
						$investor_capital = $investor_capital - intval($investor_capital/15000)*15000;
						$current_times = intval($v['investor_capital']/15000);
					}
				}
				if($current_times > 0) {
					if($timestamp > 1545926400 && $timestamp < 1546271999) {
						$this->times_model->add_member_doubles(array('uid' => $v['investor_uid'], 'doubles' => $current_times));
					} else {
						$this->times_model->add_member_times(array('uid' => $v['investor_uid'], 'times' => $current_times));
					}
				}
				// if($v['investor_uid'] == 27) {
					// p(array('uid' => $v['investor_uid'], 'doubles' => $current_times));
					// p(array('uid' => $v['investor_uid'], 'times' => $current_times));
				// }
				
				$data['times'] = [
					'uid'		=> $v['investor_uid'],
					'type'		=> $duration,
					'money'		=> $investor_capital,
					'times'		=> $current_times,
					'addtime'	=> $timestamp
				];
				if($timestamp > 1545926400 && $timestamp < 1546271999) {
					$data['times']['doubles'] = $data['times']['times'];
					unset($data['times']['times']);
				}
				// if($v['investor_uid'] == 27) {
					// p($data['times']);
				// }
				$this->times_model->add_times($data['times']);
			} else {//已有数据
				//抽奖次数
				if($timestamp > 1545926400 && $timestamp < 1546271999) {
					$current_times = $times['doubles'];
				} else {
					$current_times = $times['times'];
				}
				
				//累积金额
				$investor_capital = $times['money'] + $v['investor_capital'];
				if($duration == 2) {//33天
					if($investor_capital >= 50000) {
						$investor_capital = $investor_capital - intval($investor_capital/50000)*50000;
						$current_times = $current_times + intval(($times['money'] + $v['investor_capital'])/50000);
					}
				} else if($duration == 3) {//65天
					if($investor_capital >= 30000) {
						$investor_capital = $investor_capital - intval($investor_capital/30000)*30000;
						$current_times = $current_times + intval(($times['money'] + $v['investor_capital'])/30000);
					}
				} else if($duration == 4) {//97天
					if($investor_capital >= 15000) {
						$investor_capital = $investor_capital - intval($investor_capital/15000)*15000;
						$current_times = $current_times + intval(($times['money'] + $v['investor_capital'])/15000);
					}
				}
				if($timestamp > 1545926400 && $timestamp < 1546271999) {
					if($current_times - $times['doubles'] > 0) {
						$this->times_model->add_member_doubles(array('uid' => $v['investor_uid'], 'doubles' => intval($current_times - $times['doubles'])));
					}
				} else {
					if(($current_times - $times['times']) > 0) {
						$this->times_model->add_member_times(array('uid' => $v['investor_uid'], 'times' => intval($current_times - $times['times'])));
					} 
				}
				// if($v['investor_uid'] == 27) {
					// p(array('uid' => $v['investor_uid'], 'doubles' => intval($current_times - $times['doubles'])));
					// p(array('uid' => $v['investor_uid'], 'times' => intval($current_times - $times['times'])));
				// }
				
				$data['times1'] = [
					'id'		=> $times['id'],
					'uid'		=> $v['investor_uid'],
					'type'		=> $duration,
					'money'		=> $investor_capital,
					'times'		=> $current_times,
					'uptime'	=> $timestamp
				];
				if($timestamp > 1545926400 && $timestamp < 1546271999) {
					$data['times1']['doubles'] = $data['times1']['times'];
					unset($data['times1']['times']);
				}
				// if($v['investor_uid'] == 27) {
					// p($data['times1']);
				// }
				$this->times_model->modify_times($data['times1']);
			}
		}
	}
	/*
	private function auto_add_times($bid, $duration) {
		$investor = $this->borrow_model->get_investor_all($bid, 2);
		$timestamp = time();
		$this->load->model('times/times_model');
		foreach($investor as $k=>$v) {
			$times = $this->times_model->get_times_byuid($v['investor_uid'], $duration);
			//没有数据
			if(empty($times)) {
				//抽奖次数
				$current_times = 0;
				//累积金额
				$investor_capital = $v['investor_capital'];
				if($duration == 2) {//33天
					if($investor_capital >= 50000) {
						$investor_capital = $investor_capital - intval($investor_capital/50000)*50000;
						$current_times = intval($v['investor_capital']/50000);
					}
				} else if($duration == 3) {//65天
					if($investor_capital >= 30000) {
						$investor_capital = $investor_capital - intval($investor_capital/30000)*30000;
						$current_times = intval($v['investor_capital']/30000);
					}
				} else if($duration == 4) {//97天
					if($investor_capital >= 15000) {
						$investor_capital = $investor_capital - intval($investor_capital/15000)*15000;
						$current_times = intval($v['investor_capital']/15000);
					}
				}
				if($current_times > 0) {
					$this->times_model->add_member_times(array('uid' => $v['investor_uid'], 'times' => $current_times));
				}
				
				$data['times'] = [
					'uid'		=> $v['investor_uid'],
					'type'		=> $duration,
					'money'		=> $investor_capital,
					'times'		=> $current_times,
					'addtime'	=> $timestamp
				];
				$this->times_model->add_times($data['times']);
			} else {//已有数据
				//抽奖次数
				$current_times = $times['times'];
				//累积金额
				$investor_capital = $times['money'] + $v['investor_capital'];
				if($duration == 2) {//33天
					if($investor_capital >= 50000) {
						$investor_capital = $investor_capital - intval($investor_capital/50000)*50000;
						$current_times = $current_times + intval(($times['money'] + $v['investor_capital'])/50000);
					}
				} else if($duration == 3) {//65天
					if($investor_capital >= 30000) {
						$investor_capital = $investor_capital - intval($investor_capital/30000)*30000;
						$current_times = $current_times + intval(($times['money'] + $v['investor_capital'])/30000);
					}
				} else if($duration == 4) {//97天
					if($investor_capital >= 15000) {
						$investor_capital = $investor_capital - intval($investor_capital/15000)*15000;
						$current_times = $current_times + intval(($times['money'] + $v['investor_capital'])/15000);
					}
				}
				if(($current_times - $times['times']) > 0) {
					$this->times_model->add_member_times(array('uid' => $v['investor_uid'], 'times' => intval($current_times - $times['times'])));
				}
				$data['times1'] = [
					'id'		=> $times['id'],
					'uid'		=> $v['investor_uid'],
					'type'		=> $duration,
					'money'		=> $investor_capital,
					'times'		=> $current_times,
					'uptime'	=> $timestamp
				];
				$this->times_model->modify_times($data['times1']);
			}
		}
		//$this->times_model->add_times($data);
	}*/
	//发红包（投资红包和现金红包） 
	public function send() {
		$id = $this->uri->segment(3);
		$data = array();
		if(empty($id)) {
			exit('信息错误！');
		}
		if(IS_POST) {
			$post = $this->input->post(null, true);
			foreach($post as $value) {
				if(is_array($value)) {
					foreach($value as $v) {
						if($value == '' || empty($value)) {
							$this->error('必填项不能为空');
						}
					}
				} else {
					if($value == '' || empty($value)) {
						$this->error('必填项不能为空');
					}
				}
			}
			//时间戳
			$timestamp = time();
			//投资人要列出来
			foreach($post['investor_uid'] as $k=>$v) {
				$investor_uid[] = $k;
			}
			//获取所有的投资者
			$where['borrow_id'] = $id;
			$where['loan_status'] = 2;
			$investor = $this->borrow_model->get_investor_bywhere($where);
			//投资者金额重组
			foreach($investor as $k=>$v) {
				if(!in_array($v['investor_uid'], $investor_uid)) {
					continue;
				}
				if(isset($investors[$v['investor_uid']])) {
					$investors[$v['investor_uid']]['investor_capital'] += $v['investor_capital'];
				} else {
					$investors[$v['investor_uid']]['investor_capital'] = $v['investor_capital'];
				}
			}
			//投资红包或者两者都有
			/*
			if($post['status'] == 'invest' || $post['status'] == 'all') {
				//投资者
				$i = 0;
				foreach($investors as $key=>$value) {
					//投资红包数量
					foreach($post['invest'] as $k=>$v) {
						$data['invest'][$i]['uid'] = $key;
						$data['invest'][$i]['stime'] = $timestamp;
						$data['invest'][$i]['etime'] = $timestamp + intval($post['etime'][$k]) * 3600 * 24;
						$data['invest'][$i]['money'] = intval($value['investor_capital'] * $v)/100;
						$data['invest'][$i]['min_money'] = intval($post['min_money'][$k]/100)*100;
						$data['invest'][$i]['times'] = intval($post['times'][$k]);
						$data['invest'][$i]['type'] = 1;
						$data['invest'][$i]['addtime'] = $timestamp;
						$data['invest'][$i]['status'] = 0;
						$data['invest'][$i]['sid'] = $id;
						$i++;
					}
				}
				
				
				//红包大于2000的需要拆分数据数据2018-10-30
				$invest_count = count($data['invest']);
				$j = $invest_count;
				//$invest_change = array();
				foreach($data['invest'] as $k=>$v) {
					if($v['money'] > 2000) {
						$invest_num = intval($v['money']/2000);
						$invest_yu = $v['money'] % 2000;
						$data['invest'][$k]['money'] = 2000;
						
						for($i = 1; $i <= $invest_num; $i++) {
							
							if($invest_num == $i && $invest_yu < 0.001) {
								continue;
							}
							$data['invest'][$j]['uid'] = $v['uid'];
							$data['invest'][$j]['stime'] = $timestamp;
							$data['invest'][$j]['etime'] = $timestamp + intval($data['etime'][$k]) * 3600 * 24;
							
							$data['invest'][$j]['money'] = $invest_num == $i ? $invest_yu : 2000;
							
							$data['invest'][$j]['min_money'] = intval($data['min_money'][$k]/100)*100;
							$data['invest'][$j]['times'] = intval($data['times'][$k]);
							$data['invest'][$j]['type'] = 1;
							$data['invest'][$j]['addtime'] = $timestamp;
							$data['invest'][$j]['status'] = 0;
							$data['invest'][$j]['sid'] = $id;
							$j++;
						}
					}
					
				}
				
				
				
				
				
				
				
				
				foreach($data['invest'] as $k=>$v) {
					//调取账户金额
					$memoney = $this->member_model->get_members_money_byuid($v['uid']);
					$data['log'][] = array(
						'uid' => $v['uid'],
						'type' => 6,//投资红包
						'affect_money' => $v['money'],
						'account_money' => $memoney['account_money'],//可用
						'collect_money' => $memoney['money_collect'],//待收
						'freeze_money' => $memoney['money_freeze'],//$acountinfo['investor'][$v['investor_uid']]['money_freeze'],//冻结
						'info' => '发放' . $v['money'] . '元,投资红包',
						'add_time' => $timestamp,
						//'add_ip' => $this->input->ip_address(),
						'bid' => $v['sid'],
						'nid'	=> 0,
						
						'add_ip' => '',
						'actualAmt' => '0',
						'pledgedAmt' => '0',
						'preLicAmt' => '0',
						'totalAmt' => '0',
						'acctNo' => '0'
						
					);
				}
				//p($data);die;
			}*/
			//现金红包或者两者都有
			if($post['status'] == 'cash' || $post['status'] == 'all') {
				//投资者
				$i = 0;
				foreach($investors as $key=>$value) {
					//投资红包数量
					foreach($post['cash'] as $k=>$v) {
						$data['cash'][$i]['uid'] = $key;
						$data['cash'][$i]['stime'] = 0;
						$data['cash'][$i]['etime'] = 0;
						$data['cash'][$i]['money'] = intval($value['investor_capital'] * $v)/100;
						$data['cash'][$i]['min_money'] = 0;
						$data['cash'][$i]['times'] = 0;
						$data['cash'][$i]['type'] = 2;
						$data['cash'][$i]['addtime'] = $timestamp;
						$data['cash'][$i]['status'] = 0;
						$data['cash'][$i]['sid'] = $id;
						$data['cash'][$i]['remark'] = $post['remark'][$k];
						$i++;
					}
				}
				
				
				//现金红包红包大于2000的需要拆分数据数据2018-10-30 
				$invest_count = count($data['cash']);
				$j = $invest_count;
				//$invest_change = array();
				foreach($data['cash'] as $k=>$v) {
					if($v['money'] > 2000) {
						$invest_num = intval($v['money']/2000);
						$invest_yu = $v['money'] % 2000;
						$data['cash'][$k]['money'] = 2000;
						
						for($i = 1; $i <= $invest_num; $i++) {
							
							if($invest_num == $i && $invest_yu < 0.001) {
								continue;
							}
							
							$data['cash'][$j]['uid'] = $v['uid'];
							$data['cash'][$j]['stime'] = 0;
							$data['cash'][$j]['etime'] = 0;
							$data['cash'][$j]['money'] = $invest_num == $i ? $invest_yu : 2000;
							$data['cash'][$j]['min_money'] = 0;
							$data['cash'][$j]['times'] = 0;
							$data['cash'][$j]['type'] = 2;
							$data['cash'][$j]['addtime'] = $timestamp;
							$data['cash'][$j]['status'] = 0;
							$data['cash'][$j]['sid'] = $id;
							$data['cash'][$j]['remark'] = empty($data['remark'][$k]) ? '' : $data['remark'][$k];
							$j++;
							
						}
					}
					
				}
				
				
				
				
				
				//投资人账户金额
				foreach($investors as $k=>$v) {
					$memoney = $this->info_model->get_money($k);
					$acountinfo[$k]['account_money'] = $memoney['account_money'];
					$acountinfo[$k]['money_collect'] = $memoney['money_collect'];
					$acountinfo[$k]['money_freeze'] = $memoney['money_freeze'];
				}
				//红包转账
				foreach($data['cash'] as $k=>$v) {
					//$memoney = $this->member_model->get_members_money_byuid($v['uid']);
					$meminfo = $this->member_model->get_member_info_byuid($v['uid']);
					
					//调用接口发放红包
					$params['payerAcctNo'] = $this->config->item('mchnt_red');
					$params['payeeAcctNo'] = $meminfo['acctNo'];
					$params['amount'] = $v['money'];
					$params['payType'] = '02';//'00':个人->个人'01':个人->商户'02':商户->个人
					$params['remark'] = '转账';
					//p($params);die;
					$head = head($params, 'CG1008', 'send');
					//p($head);die;
					water($v['uid'], $head['merOrderNo'], 'CG1008', $id);
					unset($head['callbackUrl'], $head['registerPhone'], $head['responsePath'], $head['url']);
					$head = json_encode($head);
					$url = $this->config->item('Interface').'1008';
					$str = post_curl_test($url, $head);
					$this->load->model(array('paytest/paytest_model'));
					$tmp_body = $this->paytest_model->excute($str);
					//var_dump($tmp_body);
					//回写数据
					$datas = array();
					if($tmp_body['head']['respCode'] === '000000') {
						$datas['merOrderNo'] = $tmp_body['head']['merOrderNo'];
						$datas['money']		= $v['money'];
						$datas['redid']		= 0;
						$datas['status']	= 1;
						$this->load->model('water/water_model');
						$this->water_model->edit_water($datas, $tmp_body['head']['merOrderNo']);
						//账户金额增加
						$acountinfo[$v['uid']]['account_money'] += $v['money'];
						// $data['money'][] = array(
							// 'uid'		=> $v['uid'],
							// 'money_freeze' => $acountinfo[$v['uid']]['money_freeze'],
							// 'money_collect' => $acountinfo[$v['uid']]['money_collect'],
							// 'account_money' => $acountinfo[$v['uid']]['account_money']
						// );
						$data['log'][] = array(
							'uid' => $v['uid'],
							'type' => 7,//现金红包
							'affect_money' => $v['money'],
							'account_money' => $acountinfo[$v['uid']]['account_money'],//可用
							'collect_money' => $acountinfo[$v['uid']]['money_collect'],//待收
							'freeze_money' => $acountinfo[$v['uid']]['money_freeze'],//冻结
							'info' => '发放' . $v['money'] . '元现金红包',
							'add_time' => $timestamp,
							//'add_ip' => $this->input->ip_address(),
							'bid' => $id,
							'nid'	=> $tmp_body['head']['merOrderNo'],
							
							'add_ip' => '',
							'actualAmt' => '0',
							'pledgedAmt' => '0',
							'preLicAmt' => '0',
							'totalAmt' => '0',
							'acctNo' => '0'

							
							
						);
						$data['cash'][$k]['nid'] = $tmp_body['head']['merOrderNo'];//将现金红包状态修改为1
					} else {
						$datas['uid']		= $v['uid'];
						$datas['bid']		= $id;
						$datas['money']		= $v['money'];
						$datas['status']	= 0;
						$datas['addtime']	= time();
						$datas['remark']	= '发放现金红包失败';
						$this->member_model->add_packet_error($datas);
						
						
						//错误的也要记录日志2018-10-30
						$datass['merOrderNo'] = $tmp_body['head']['merOrderNo'];
						$datass['money']		= $v['money'];
						$datass['redid']		= 0;
						$datass['status']		= 1;
						$this->load->model('water/water_model');
						$this->water_model->edit_water($datass, $tmp_body['head']['merOrderNo']);
						
						$data['log'][] = array(
							'uid' => $v['uid'],
							'type' => 14,//现金红包
							'affect_money' => $v['money'],
							'account_money' => $acountinfo[$v['uid']]['account_money'],//可用
							'collect_money' => $acountinfo[$v['uid']]['money_collect'],//待收
							'freeze_money' => $acountinfo[$v['uid']]['money_freeze'],//冻结
							'info' => '发放' . $v['money'] . '元现金红包, ' . $tmp_body['head']['respDesc'],
							'add_time' => $timestamp,
							//'add_ip' => $this->input->ip_address(),
							'bid' => $id,
							'nid'	=> $tmp_body['head']['merOrderNo']
						);
						
						
						
						
					}
				}
				//投资人账户资金重新处理
				foreach($acountinfo as $k=>$v) {
					$data['money'][] = array(
						'uid'		=> $k,
						'money_freeze' => $acountinfo[$k]['money_freeze'],
						'money_collect' => $acountinfo[$k]['money_collect'],
						'account_money' => $acountinfo[$k]['account_money']
					);
				}
				//现金红包不需要记录到红包中
				foreach($data['cash'] as $k=>$v) {
					$data['cashes'][] = array(
						'bid' => $v['sid'],
						'uid' => $v['uid'],
						'money' => $v['money'],
						'remark' => $v['remark'],
						'addtime' => $timestamp,
						'adminid' => UID,
						'nid'	=> $v['nid']
					);
				}
				unset($data['cash']);
				//p($data);die;
			}
			//p($data);die;
			//记录发送红包处理的数据
			//log_record(serialize($data), '/red.log', 'bid-' . $id);
			$this->borrow_model->send($data);
			$this->success('操作成功', '/borrow.html');
		}
		
		//借款信息
		$data['borrow'] = $this->borrow_model->get_borrow_byid($id);
		//投资信息
		$where['borrow_id'] = $id;
		$where['loan_status'] = 2;
		$investor = $this->borrow_model->get_investor_bywhere($where);
		foreach($investor as $v) {
			if(isset($data['investor'][$v['investor_uid']])) {
				$data['investor'][$v['investor_uid']]['investor_capital'] += $v['investor_capital'];
			} else {
				$data['investor'][$v['investor_uid']]['investor_capital'] = $v['investor_capital'];
			}
		}
		$data['id'] = $id;
		$this->load->view('borrow/send', $data);
	}
	//还款列表页面（一次还一期）
	public function repaylist() {
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
		$this->load->view('borrow/repaylist', $data);
	}
	
	/** 获得授权 */
	public function authorize() {
		$bid = $this->uri->segment(3);
		if(empty($bid)) {
			exit('信息错误');
		}
		//p($id_details);
		//获取授权金额
		$money = 0;
		$deadline = 0;
		$details = $this->borrow_model->get_investor_detail_byid($bid);
		foreach($details as $detail) {
			//$detail = $this->borrow_model->get_detail_one($v);
			if($detail['repayment_time'] > 0) {
				continue;
			}
			/* if($detail['sort_order'] < $detail['total']) {
				$money += $detail['interest'];
			} else {
				$money += $detail['interest'] + $detail['capital'];
			} */
			$money += $detail['interest'] + $detail['capital'];
			if($detail['deadline'] > $deadline) {
				$deadline = $detail['deadline'];
			}
		}
		
		//调取借款人信息
		$meminfo = $this->member_model->get_member_info_byuid($detail['borrow_uid']);
		$params['custNo'] = $meminfo['custNo'];
		$params['authType'] = 'CG1010';
		$params['expiryTime'] = date('Ymd', $deadline + 24*3600);
		$params['amount'] = $money;
		$params['callbackUrl'] = 'https://www.jiamanu.com/paytest';
		$params['responsePath'] = 'https://www.jiamanu.com/borrow/repaylist/'.$detail['borrow_id'].'.html';
		
		$data = head($params, 'CG1050', 'authorize');
		water($detail['borrow_uid'], $data['merOrderNo'], 'CG1050', $detail['borrow_id'], $money);
		
		$data['url'] = $this->config->item('Interfaces').'1050';
		$this->load->view('company/jump', $data);
	}
	//还款(一次还一期)
	public function repayment() {
		if(IS_POST) {
			$id_details = $this->input->post('id', true);
			record_adminlog($this->router->fetch_class(), $this->router->fetch_method(), 0, '标的列表-还款, 还款ID为' .$id_details);
			$ids_details = explode(',', $id_details);
			foreach($ids_details as $k=>$v) {
				if(empty($v)) {
					$this->error('加载数据出错!');
				}
			}
			//p($ids_details);die;
			$this->load->model(array('paytest/paytest_model'));
			//$this->load->model(array('borrow/borrow_model', 'member/member_model'));

			foreach($ids_details as $v) {
				$detail = '';
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
					$id_detail = 0;
					$id_detail = $water['bid'];
					
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
						'receive_interest' 	=> $detail['interest'],
						'adminid' 			=> UID
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
							$data = array();
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
	}
	/** 提前还款 */
	public function pre_repayment() {
		/* echo '联系管理员放开操作';
		die; */
		die;
		$id = $this->uri->segment(3);
		if(empty($id)) {
			$this->error('信息错误，请联系管理员');
		}
		if(IS_POST) {
			//调取第一张表中的数据
			$borrow = $this->borrow_model->get_borrow_byid($id);
			
			//调取第二张表中的所有数据
			$where['borrow_id'] = $id;
			$investor = $this->borrow_model->get_investor_bywhere($where);
			//第三张表中的所有数据
			$detail_all = array();
			//需要操作还款的数据
			$detail_repay = array();
			foreach($investor as $k=>$v) {
				//查询还款详情
				$where = array();
				$where['invest_id'] = $v['id'];
				$detail_sort = $this->borrow_model->get_detail_bywhere($where);
				array_multisort(array_column($detail_sort,'sort_order'),SORT_ASC, $detail_sort);
				//重新计算利息
				foreach($detail_sort as $key=>$value) {
					if($value['repayment_time'] > 0) {
						continue;
					}
					
					//判断需要操作还款的数据中，是否已经有了该笔投资的还款
					if(array_search($value['invest_id'], array_column($detail_repay, 'invest_id')) === false) {
						$value['capital'] = round($v['investor_capital'] - $v['receive_capital'], 2);
						//判断是不是第一期，第一期需要从放款日开始计算利息，根据期数计算出利息天数
						if($value['sort_order'] == 1) {
							$days = intval((strtotime(date('Y-m-d')) - strtotime(date('Y-m-d', $borrow['endtime'])))/86400);
						} else {//第一期以后的数据，需要调用上一期的还款日
							$days = intval((strtotime(date('Y-m-d')) - strtotime(date('Y-m-d', $detail_sort[$key-1]['deadline'])))/86400);
						}
						//echo $days, '#';
						if($days > $this->config->item('borrow_duration')[$borrow['borrow_duration']] || $days < 0) {
							$this->error('计算利息出错，请联系客服或者管理员');
						}
						$value['interest'] = round($value['capital'] * (($borrow['borrow_interest_rate'] + $borrow['add_rate']) / 100 / 360) * $days,2);
						
						$detail_all[] = $value;
						$detail_repay[] = $value;
					} else {//已经有还款数据
						$value['capital'] = 0;
						$value['interest'] = 0;
						$value['repayment_time'] = time();
						$value['status'] = 6;
						$value['adminid'] = UID;
						$detail_all[] = $value;
					}
				}
			}
			//更新还款列表
			//$this->borrow_model->modify_detail_all($detail_all);
			//提出所有要还数据的ID
			$ids = array_column($detail_repay, 'id');
			$ids = implode(',', $ids);
			
			//$this->pre_repaydetail($ids);
		}
		
	}
	/** 提前还利息请求接口 */
	private function pre_repaydetail($id_details) {
		/* die; */
		//$id_details = $this->input->post('id', true);
		record_adminlog($this->router->fetch_class(), $this->router->fetch_method(), 0, '标的列表-提前还款, 还款ID为' .$id_details);
		$ids_details = explode(',', $id_details);
		foreach($ids_details as $k=>$v) {
			if(empty($v)) {
				$this->error('加载数据出错!');
			}
		}
		//p($ids_details);die;
		$this->load->model(array('paytest/paytest_model'));
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
			//$deadline = $timestamp + $this->config->item('borrow_duration')[$borrow['borrow_duration']]*86400;
			//请还款接口CG1010,
			//组织放款数据，
			
			$params['subjectNo'] = $borrow['subjectNo'];
			$params['payerAcctNo'] = $meminfo['acctNo'];
			$params['amount'] = $detail['capital'] + $detail['interest'];
			$params['capital'] = $detail['capital'];
			$params['payerAmount'] = $detail['capital'] + $detail['interest'];
			/*
			if($detail['sort_order'] < $detail['total']) {
				$params['amount'] = $detail['capital'];
				$params['capital'] = $detail['capital'];
				$params['payerAmount'] = $detail['capital'];
			} else {
				$params['amount'] = $detail['capital'] + $detail['interest'];
				$params['capital'] = $detail['capital'];
				$params['payerAmount'] = $detail['capital'] + $detail['interest'];
			} */
			//$params['incomeAmt'] = 0;//round(floatval($detail['investor_capital']*$borrow['service_money']/100), 2);
			//p($meminfo['ppwd']);die;
			//$params['payPwd'] = intval($meminfo['ppwd']);
			$params['payeeAcctNo'] = $meminfos['acctNo'];
			
			
			$params_tmp[] = $params;
			//p($params);die;
			
			//p($params);
			/*
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
				//$this->load->model(array('borrow/borrow_model', 'member/member_model'));
				$detail = $this->borrow_model->get_detail_one($id_detail);
				//判断是不是最后一期的还款
				$is_total = ($detail['capital'] > 0.001) ? true : false;
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
				$money = $detail['capital'] + $detail['interest'];
				//时间戳
				$timestamp = time();
				
				//修改主表状态
				$data = array();
				$data['borrow'] = array(
					'id'	=> $id,
					'borrow_status' => $is_last ? 6 : 4,
					'has_pay'		=> $borrow['has_pay'] + $money,
				);
				//第二张表
				//调取所有投资人信息
				$investor = $this->borrow_model->get_investor_one($detail['invest_id']);
				$data['investor'] = array(
					'id' => $investor['id'],
					'receive_capital' => $investor['receive_capital'] + $detail['capital'],
					'receive_interest' => $investor['receive_interest'] + $detail['interest'],
					'status'			=> 6
				);
				//第三张表数据 【一次性到期还本息， 按月付息到期还本】
				$data['detail'] = array(
					'id'				=> $id_detail,
					'repayment_time' 	=> $timestamp,
					'receive_capital' 	=> $detail['capital'],
					'receive_interest' 	=> $detail['interest'],
					'adminid' 			=> UID,
					'status'			=> 6
				);
				//如果是最后一笔
				// if($is_last) {
					// $data['investor_status'] = array(
						// 'borrow_id' => $id,
						// 'status'	=> 5
					// );
					// $data['detail_status'] = array(
						// 'borrow_id' => $id,
						// 'status' => 5
					// );
				// }
				//处理金额
				//投资人
				$memoney = $this->info_model->get_money($investor['investor_uid']);
				$data['money'][] = array(
					'uid'			=> $investor['investor_uid'],
					'account_money'	=> $memoney['account_money'] + $money,
					'money_freeze' => $memoney['money_freeze'],
					'money_collect' => $memoney['money_collect'] - $detail['capital']
				);
				$data['log'][] = array(
					'uid' => $investor['investor_uid'],
					'type' => 9,//收款
					'affect_money' => $water['money'],
					'account_money' => $memoney['account_money'] + $money,//可用
					'collect_money' => $memoney['money_collect'] - $detail['capital'],//待收
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
				// if($is_last) {
					// $params['subjectNo'] = $borrow['subjectNo'];
					// $head = head($params, 'CG1032', 'over');
					// water($borrow['borrow_uid'], $head['merOrderNo'], 'CG1032', $borrow['id']);
					// unset($head['callbackUrl'], $head['registerPhone'], $head['responsePath'], $head['url']);
					// $data = $head;
					// $data = json_encode($data);
					// $url = $this->config->item('Interface').'1032';
					// $str = post_curl_test($url, $data);
					// $this->load->model(array('paytest/paytest_model'));
					// $res_water = $this->paytest_model->excute($str);
					// if($res_water['head']['respCode'] === '000000') {
						// $data = array();
						// $data['water']['status'] = 1;
						////$data['water']['merOrderNo'] = $res_water['head']['merOrderNo'];
						// $this->load->model(array('water/water_model'));
						// $this->water_model->edit_water($data['water'], $res_water['head']['merOrderNo']);
					// }
				// }
				 
			} else {
				if(isset($tmp_body['head']['respDesc'])) {
					$this->error($tmp_body['head']['respDesc']);
				} else {
					$this->error('接口错误');
				}
				
			} */
		}
		p($params_tmp);die;
		$this->success('提前还款成功');
	}
	/** 标的结束 */
	public function bid_over() {die;
		$id = 506;
		$borrow = $this->borrow_model->get_borrow_byid($id);
		if(empty($borrow)) {
			exit('信息错误');
		}
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
			$data = array();
			$data['water']['status'] = 1;
			//$data['water']['merOrderNo'] = $res_water['head']['merOrderNo'];
			$this->load->model(array('water/water_model'));
			$this->water_model->edit_water($data['water'], $res_water['head']['merOrderNo']);
		}
		exit('操作成功');
	}
	/** 标的撤销 */
	public function revoke() {
		if(IS_POST) {
			$id = $this->uri->segment(3);
			//echo $id;die;
			record_adminlog($this->router->fetch_class(), $this->router->fetch_method(), $id, '标的列表-流标');
			if(empty($id)) {
				$this->error('信息错误');
			}
			$borrow = $this->borrow_model->get_borrow_byid($id);
			$this->load->model(array('paytest/paytest_model'));
			$params['subjectNo'] = $borrow['subjectNo'];

			$head = head($params, 'CG1016', 'paymentmoney');
			water($borrow['borrow_uid'], $head['merOrderNo'], 'CG1016', $id);
			unset($head['callbackUrl'], $head['registerPhone'], $head['responsePath'], $head['url']);
			$data = json_encode($head);
			$url = $this->config->item('Interface').'1016';
			$str = post_curl_test($url, $data);
			$tmp_body = $this->paytest_model->excute($str);
			$respCode = $tmp_body['head']['respCode'];
			if($respCode === '000000') {
				$borrow['del'] = time();
				$borrow['borrow_status'] = 1;
				$borrow['has_borrow'] = $borrow['borrow_money'];
				$this->borrow_model->modify_borrow($borrow, $id);
				$this->success($tmp_body['head']['respDesc']);
			}  else {
				if(isset($tmp_body['head']['respDesc'])) {
					$this->error($tmp_body['head']['respDesc']);
				} else {
					$this->error('接口错误');
				}
			}
		}
	}
	//获取借款人姓名
	/*public function getusers(){
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
		
		$per_page = 8;
        $offset = $current_page;
        $config['base_url'] = base_url('borrow/getusers');
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
        $this->pagination->initialize($config);
        $data['totals'] = $config['total_rows'];
        $data['page'] = $this->pagination->create_links();
        $data['p'] = $current_page;
        $member = $this->member_model->get_member_related($per_page, $offset * $per_page, $where);
        $data['member'] = $member;
		$this->load->view('member/getusers', $data);
	}*/
	/** 获取担保人 */
	/*
	public function get_guarantor() {
		$this->load->model(array('guarantor/guarantor_model'));
		$data = array();
		$where = array();
		$current_page = $this->uri->segment(3) ? $this->uri->segment(3) : 1;
		$current_page = $current_page > 0 ? $current_page - 1 : 0;
		$search = $this->input->get(null, true);
		if(!empty($search['skey'])) {
			$data['skey'] = trim(trim($search['skey']), '\t');
			$where['skey'] = $data['skey'];
		}
		$per_page = 8;
        $offset = $current_page;
        $config['base_url'] = base_url('borrow/get_guarantor');
        $config['total_rows'] = $this->guarantor_model->get_guarantor_num($where);
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
        $data['guarantor'] = $this->guarantor_model->get_guarantor_lists($per_page, $offset * $per_page, $where);
		$this->load->view('borrow/guarantor', $data);
	}*/
	/*还款列表页面(一期一期的还)
	public function repaylist() {
		$id = $this->uri->segment(3);
		$data = array();
		$data['borrow'] = $this->borrow_model->get_borrow_byid($id);
		$data['borrow_uid'] = $this->member_model->get_member_info_byuid($data['borrow']['borrow_uid'])['real_name'];
		$data['detail'] = $this->borrow_model->get_investor_detail_byid($id);
		foreach($data['detail'] as $k=>$v) {
			$data['detail'][$k]['investor_name'] = $this->member_model->get_member_info_byuid($v['investor_uid'])['real_name'];
		}
		//
		
		$this->load->view('borrow/repaylist', $data);
	}*/
	/** 标的结束测试 */
	// public function over() {
		// $id = $this->uri->segment(3);
		// $borrow = $this->borrow_model->get_borrow_byid($id);
		// $params['subjectNo'] = $borrow['subjectNo'];

		// $head = head($params, 'CG1032', 'over');
		// water($borrow['borrow_uid'], $head['merOrderNo'], 'CG1032', $borrow['id']);
		// unset($head['callbackUrl'], $head['registerPhone'], $head['responsePath'], $head['url']);
		// $data = $head;
		// $data = json_encode($data);
		// $url = $this->config->item('Interface').'1032';
		// $str = post_curl_test($url, $data);
		// $this->load->model(array('paytest/paytest_model'));
		// $arr = $this->paytest_model->excute($str);
		// p($arr);
	// }
	/*//还款(一期一期的还())
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
	*/
	//流标
	/*
	public function bidders(){
		if($pi = $this->input->post(NULL, TRUE)){
			$id = $pi['id'];
			$binfo = $this->borrow_model->get_borrow_byid($id);
			if($binfo['borrow_status'] != 2 && $binfo['borrow_status'] != 3){
				$info['state'] = 0;
				$info['message'] = '链接出误!';
				$this->output
				    ->set_content_type('application/json', 'utf-8')
				    ->set_output(json_encode($info))
					->_display();
				    exit;
			}else{
				$b_i = array(
					'del' => time(),
					'borrow_status' => 10
				);
				$borrow_investor = $this->borrow_model->get_borrow_investor_byid($id);
				$this->db->trans_begin();
				$this->borrow_model->modify_borrow($b_i, $id);
				$this->borrow_model->modify_borrow_investor(array('status' => 10), $id);
				$this->borrow_model->modify_investor_detail(array('status' => 10), $id);
				//操作金额表
				foreach($borrow_investor as $vo){
					$mchnt_cd = $this->config->item('mchnt_cd');
					$mchnt_txn_ssn = getTxNo20();
					$in_cust = $this->member_model->get_member_info_byuid($vo['investor_uid']);
					$cust_no = $in_cust['phone'];
					$amt = $vo['investor_capital'];
					$rem = '投资解冻'.$amt.'元';
					$sdata = ($amt * 100).'|'.$cust_no.'|'.$mchnt_cd.'|'.$mchnt_txn_ssn.'|'.$rem.'|0.44';
					$signature = rsaSign($sdata, './Content/php_prkey.pem');
					$post_data = array(
						'ver' 			=> '0.44',
						'mchnt_cd' 		=> $mchnt_cd,
						'mchnt_txn_ssn' => $mchnt_txn_ssn,
						'cust_no' 	    => $cust_no,
						'amt' 			=> $amt * 100,
						'rem' 			=> $rem,
						'signature' 	=> $signature,
					);
					$url = $this->config->item('payurl') . 'unFreeze.action';
					$result = post_curl($url,$post_data);
					$simpleXML= new SimpleXMLElement($result);
					$c = (array) $simpleXML->children();
					$d = (array) $c['plain'];
					if($d['resp_code'] == '000'){
						$moneyt = $this->info_model->get_money($vo['investor_uid']);
						$mlogt = array(
							'uid' => $vo['investor_uid'],
							'type' => 8,//转账
							'affect_money' => $amt,
							'account_money' => $moneyt['account_money'] + $amt,//可用
							//'collect_money' => $moneyt['money_collect'],//待收
							'freeze_money' => $moneyt['money_freeze'] - $amt,//冻结
							'info' => $mchnt_txn_ssn . ',流标解冻',
							'add_time' => $time,
							'add_ip' => $this->input->ip_address(),
							'bid' => $vo['borrow_id']
						);
						$umt = array(
							'account_money' => $moneyt['account_money'] + $amt,
							'money_freeze' => $moneyt['money_freeze'] - $amt,
						);
						$this->member_model->up_members_money($umt, $vo['investor_uid']);
						$this->member_model->add_members_moneylog($mlogt);
					}else{
						$this->borrow_model->add_error(array('iid'=>$vo['id'],'nid'=>$mchnt_txn_ssn,'error'=>$d['resp_desc'],'type'=>3, 'addtime'=>time()));
					}
				}
				if($this->db->trans_status() === TRUE){
					$this->db->trans_commit();
					$info['state'] = 1;
					$info['message'] = '流标成功!';
					$info['url'] = '/borrow.html';
					$this->output
					    ->set_content_type('application/json', 'utf-8')
					    ->set_output(json_encode($info))
						->_display();
					    exit;
				}else{
					$this->db->trans_rollback();
					$info['state'] = 0;
					$info['message'] = '流标失败,刷新后重试!';
					$this->output
					    ->set_content_type('application/json', 'utf-8')
					    ->set_output(json_encode($info))
						->_display();
					    exit;
				}
			}
		}else{
			$id = $this->uri->segment(3);
			$data = $this->borrow_model->get_borrow_byid($id);
			$this->load->view('borrow/bidders', $data);
		}
	}*/
	/*
	public function putmoney(){
		$id = $this->uri->segment(3);
		if($pi = $this->input->post(NULL, TRUE)){
			$id = $pi['id'];
			if($id <= 0){
				$info['state'] = 0;
				$info['message'] = '加载数据出错!';
				$this->output
				    ->set_content_type('application/json', 'utf-8')
				    ->set_output(json_encode($info))
					->_display();
				    exit;
			}else{
				$data = $this->borrow_model->get_borrow_byid($id);
				if($data['borrow_status'] != 3){
					$info['state'] = 0;
					$info['message'] = '数据出错.!';
					$this->output
					    ->set_content_type('application/json', 'utf-8')
					    ->set_output(json_encode($info))
						->_display();
					    exit;
				}
				//修改主表状态
				$this->db->trans_begin();
				$b_d = array(
					'endtime' => time(),
					'borrow_status' => 4
				);
				$this->borrow_model->modify_borrow($b_d, $id);
				//第二张表
				$b_i = array(
					'deadline' => time()+$data['borrow_duration'] * 86400,
					'status' => 4
				);
				$this->borrow_model->modify_borrow_investor($b_i, $id);
				//第三张表
				$i_d = array(
					'deadline' => time() + $data['borrow_duration'] * 86400,
					'status' => 4
				);
				$this->borrow_model->modify_investor_detail($i_d, $id);
				//操作金额表
				$borrow_investor = $this->borrow_model->get_borrow_investor_byid($id);
				foreach($borrow_investor as $vo){
					//先结冻再转账
					$mchnt_cd = $this->config->item('mchnt_cd');
					$mchnt_txn_ssn = getTxNo20();
					$in_cust = $this->member_model->get_member_info_byuid($vo['investor_uid']);
					$cust_no = $in_cust['phone'];
					$amt = $vo['investor_capital'];
					$rem = '投资解冻'.$amt.'元';
					$sdata = ($amt * 100).'|'.$cust_no.'|'.$mchnt_cd.'|'.$mchnt_txn_ssn.'|'.$rem.'|0.44';
					$signature = rsaSign($sdata, './Content/php_prkey.pem');
					$post_data = array(
						'ver' 			=> '0.44',
						'mchnt_cd' 		=> $mchnt_cd,
						'mchnt_txn_ssn' => $mchnt_txn_ssn,
						'cust_no' 	    => $cust_no,
						'amt' 			=> $amt * 100,
						'rem' 			=> $rem,
						'signature' 	=> $signature,
					);
					$url = $this->config->item('payurl') . 'unFreeze.action';
					$result = post_curl($url,$post_data);
					$simpleXML= new SimpleXMLElement($result);
					$c = (array) $simpleXML->children();
					$d = (array) $c['plain'];
					if($d['resp_code'] == '000'){
						//转账
						$mchnt_txn_ssn = getTxNo20();
						$contract_no = '';
						$in_cust = $this->member_model->get_member_info_byuid($vo['borrow_uid']);
						$in_cust_no = $in_cust['phone'];
						$out_cust = $this->member_model->get_member_info_byuid($vo['investor_uid']);
						$out_cust_no = $out_cust['phone'];
						$rems = $out_cust['real_name'] . '向' . $in_cust['real_name'] . '投资转账' . $amt . '元(' . $vo['borrow_id'] . ')';
						$sdataz = ($amt * 100).'|'.$contract_no.'|'.$in_cust_no.'|'.$mchnt_cd.'|'.$mchnt_txn_ssn.'|'.$out_cust_no.'|'.$rems.'|0.44';
						$key = rsaSign($sdata, './Content/php_prkey.pem');
						$post_dataz = array(
							'ver' 			=> '0.44',
							'mchnt_cd' 		=> $mchnt_cd,
							'mchnt_txn_ssn' => $mchnt_txn_ssn,
							'out_cust_no' 	=> $out_cust_no,
							'in_cust_no' 	=> $in_cust_no,
							'contract_no'	=> $contract_no,
							'amt' 			=> $amt * 100,
							'rem' 			=> $rems,
							'signature' 	=> $key,
						);
						$url = $this->config->item('payurl') . 'transferBu.action';
						$result = post_curl($url,$post_data);
						$simpleXML= new SimpleXMLElement($result);
						$c = (array) $simpleXML->children();
						$d = (array) $c['plain'];
						if($d['resp_code'] == '000'){
							//操作本地金额表
							//借款人
							$time = time();
							$moneys = $this->info_model->get_money($vo['borrow_uid']);
							$mlog = array(
								'uid' => $vo['borrow_uid'],
								'type' => 5,//转账
								'affect_money' => $vo['investor_capital'],
								'account_money' => $moneys['account_money'] + $amt,//可用
								//'collect_money' => $moneys['money_collect'],//待收
								//'freeze_money' => $moneys['money_freeze'],//冻结
								'info' => $mchnt_txn_ssn . ',借款入账',
								'add_time' => $time,
								'add_ip' => $this->input->ip_address(),
								'bid' => $vo['borrow_id']
							);
							$um = array(
								'account_money' => $moneys['account_money'] + $amt,
							);
							$this->member_model->up_members_money($um, $vo['borrow_uid']);
							$this->member_model->add_members_moneylog($mlog);
							//投资人
							$moneyt = $this->info_model->get_money($vo['investor_uid']);
							$mlogt = array(
								'uid' => $vo['investor_uid'],
								'type' => 4,//转账
								'affect_money' => $vo['investor_capital'],
								'account_money' => $moneyt['account_money'],//可用
								'collect_money' => $moneyt['money_collect'] + $amt,//待收
								'freeze_money' => $moneyt['money_freeze'] - $amt,//冻结
								'info' => $mchnt_txn_ssn . ',投资待收',
								'add_time' => $time,
								'add_ip' => $this->input->ip_address(),
								'bid' => $vo['borrow_id']
							);
							$umt = array(
								'money_collect' => $moneyt['money_collect'] + $amt,
								'money_freeze' => $moneyt['money_freeze'] - $amt,
							);
							$this->member_model->up_members_money($umt, $vo['investor_uid']);
							$this->member_model->add_members_moneylog($mlogt);
						}else{
							$this->borrow_model->add_error(array('iid'=>$vo['id'],'nid'=>$mchnt_txn_ssn,'error'=>$d['resp_desc'],'type'=>2,'addtime'=>time()));
						}
					}else{
						$this->borrow_model->add_error(array('iid'=>$vo['id'],'nid'=>$mchnt_txn_ssn,'error'=>$d['resp_desc'],'addtime'=>time()));
					}
					
				}
				if($this->db->trans_status() === TRUE){
					$this->db->trans_commit();
					$info['state'] = 1;
					$info['message'] = '放款成功!';
					$info['url'] = '/borrow.html';
					$this->output
					    ->set_content_type('application/json', 'utf-8')
					    ->set_output(json_encode($info))
						->_display();
					    exit;
				}else{
					$this->db->trans_rollback();
					$info['state'] = 0;
					$info['message'] = '放款失败,刷新后重试!';
					$this->output
					    ->set_content_type('application/json', 'utf-8')
					    ->set_output(json_encode($info))
						->_display();
					    exit;
				}
			}
		}
		$data = $this->borrow_model->get_borrow_byid($id);
		$this->load->view('borrow/putmoney', $data);
	}
	*/
	
	//还款
	/*
	public function repayment(){
		if($pi = $this->input->post(NULL, TRUE)){
			$id = $pi['id'];
			$type = $pi['type'];
			$binfo = $this->borrow_model->get_borrow_byid($id);
			if($binfo['borrow_status'] != 4 || $type < 5 || $type > 7 ){
				$info['state'] = 0;
				$info['message'] = '数据出误!';
				$this->output
				    ->set_content_type('application/json', 'utf-8')
				    ->set_output(json_encode($info))
					->_display();
				    exit;
			}else{
				$bufo = $this->info_model->get_money($binfo['borrow_uid']);
				$m = $this->borrow_model->get_moneys($id);
				//$rem = $binfo['borrow_money'] * $binfo['borrow_interest_rate'] / 100 / 360 * $binfo['borrow_duration'] + $binfo['borrow_money'];
				$rem = $binfo['borrow_money'] + $m['investor_interest'];
				if($bufo['account_money'] < $rem){
					$info['state'] = 0;
					$info['message'] = '账号金额少于应还金额!';
					$this->output
						->set_content_type('application/json', 'utf-8')
						->set_output(json_encode($info))
						->_display();
						exit;
				}
				$b_i = array(
					'borrow_status' => $type
				);
				$borrow_investor = $this->borrow_model->get_borrow_investor_byid($id);
				$this->db->trans_begin();
				$this->borrow_model->modify_borrow($b_i, $id);
				//操作金额表
				foreach($borrow_investor as $vo){
					//还款转账
					$mchnt_cd = $this->config->item('mchnt_cd');
					$amt = $vo['investor_capital'] + $vo['investor_interest'];
					$mchnt_txn_ssn = getTxNo20();
					$contract_no = '';
					$in_cust = $this->member_model->get_member_info_byuid($vo['investor_uid']);
					$in_cust_no = $in_cust['phone'];
					$out_cust = $this->member_model->get_member_info_byuid($vo['borrow_uid']);
					$out_cust_no = $out_cust['phone'];
					$rem = $out_cust['real_name'] . '向' . $in_cust['real_name'] . '投资转账' . $amt . '元(' . $vo['borrow_id'] . ')';
					$sdataz = ($amt * 100).'|'.$contract_no.'|'.$in_cust_no.'|'.$mchnt_cd.'|'.$mchnt_txn_ssn.'|'.$out_cust_no.'|'.$rems.'|0.44';
					$key = rsaSign($sdata, './Content/php_prkey.pem');
					$post_dataz = array(
						'ver' 			=> '0.44',
						'mchnt_cd' 		=> $mchnt_cd,
						'mchnt_txn_ssn' => $mchnt_txn_ssn,
						'out_cust_no' 	=> $out_cust_no,
						'in_cust_no' 	=> $in_cust_no,
						'contract_no'	=> $contract_no,
						'amt' 			=> $amt * 100,
						'rem' 			=> $rem,
						'signature' 	=> $key,
					);
					$url = $this->config->item('payurl') . 'transferBu.action';
					$result = post_curl($url,$post_data);
					$simpleXML= new SimpleXMLElement($result);
					$c = (array) $simpleXML->children();
					$d = (array) $c['plain'];
					if($d['resp_code'] == '000'){
						//操作本地金额表
						//借款人
						$time = time();
						$moneys = $this->info_model->get_money($vo['borrow_uid']);
						$mlog = array(
							'uid' => $vo['borrow_uid'],
							'type' => 6,//还款
							'affect_money' => $amt,
							'account_money' => $moneys['account_money'] - $amt,//可用
							//'collect_money' => $moneys['money_collect'],//待收
							//'freeze_money' => $moneys['money_freeze'],//冻结
							'info' => $mchnt_txn_ssn . ',还款出账',
							'add_time' => $time,
							'add_ip' => $this->input->ip_address(),
							'bid' => $vo['borrow_id']
						);
						$um = array(
							'account_money' => $moneys['account_money'] - $amt,
						);
						$this->member_model->up_members_money($um, $vo['borrow_uid']);
						$this->member_model->add_members_moneylog($mlog);
						//投资人
						$moneyt = $this->info_model->get_money($vo['investor_uid']);
						$mlogt = array(
							'uid' => $vo['investor_uid'],
							'type' => 7,//回款
							'affect_money' => $amt,
							'account_money' => $moneyt['account_money'] + $amt,//可用
							'collect_money' => $moneyt['money_collect'] - $amt,//待收
							'info' => $mchnt_txn_ssn . ',投资回款',
							'add_time' => $time,
							'add_ip' => $this->input->ip_address(),
							'bid' => $vo['borrow_id']
						);
						$umt = array(
							'money_collect' => $moneyt['money_collect'] - $amt,
							'account_money' => $moneyt['account_money'] + $amt,
						);
						$this->member_model->up_members_money($umt, $vo['investor_uid']);
						$this->member_model->add_members_moneylog($mlogt);
					}else{
						$this->borrow_model->add_error(array('iid'=>$vo['id'],'nid'=>$mchnt_txn_ssn,'error'=>$d['resp_desc'],'type'=>4,'addtime'=>time()));
					}
				}
				$this->db->query("update xm_borrow_investor set receive_capital=investor_capital,receive_interest=investor_interest,status=$type where borrow_id=$id");
				$time = time();
				$this->db->query("update xm_investor_detail set repayment_time=$time,receive_capital=capital,receive_interest=interest,status=$type where borrow_id=$id");
				if($this->db->trans_status() === TRUE){
					$this->db->trans_commit();
					$info['state'] = 1;
					$info['message'] = '还款成功!';
					$info['url'] = '/borrow.html';
					$this->output
					    ->set_content_type('application/json', 'utf-8')
					    ->set_output(json_encode($info))
						->_display();
					    exit;
				}else{
					$this->db->trans_rollback();
					$info['state'] = 0;
					$info['message'] = '还款失败,刷新后重试!';
					$this->output
					    ->set_content_type('application/json', 'utf-8')
					    ->set_output(json_encode($info))
						->_display();
					    exit;
				}
			}
		}else{
			$id = $this->uri->segment(3);
			$type = $this->uri->segment(4);
			if($type == 1){
				$type = 5;
			}elseif($type == 2){
				$type = 6;
			}elseif($type == 3){
				$type = 7;
			}
			$data = $this->borrow_model->get_borrow_byid($id);
			$data['type'] = $type;
			$this->load->view('borrow/repayment', $data);
		}
	}
	*/
	/**
	public function add(){
		if($data = $this->input->post(NULL, TRUE)){
			if(! $data['borrow_uid']){
				$info['state'] = 0;
				$info['message'] = '请完善标借款人姓名项!';
				$this->output
				    ->set_content_type('application/json', 'utf-8')
				    ->set_output(json_encode($info))
					->_display();
				    exit;
			}
			if(! $data['type']){
				$info['state'] = 0;
				$info['message'] = '请完善标收款类型项!';
				$this->output
				    ->set_content_type('application/json', 'utf-8')
				    ->set_output(json_encode($info))
					->_display();
				    exit;
			}
			if(! $data['borrow_name']){
				$info['state'] = 0;
				$info['message'] = '请完善标名称项!';
				$this->output
				    ->set_content_type('application/json', 'utf-8')
				    ->set_output(json_encode($info))
					->_display();
				    exit;
			}
			if(! $data['borrow_money']){
				$info['state'] = 0;
				$info['message'] = '请完善金额项!';
				$this->output
				    ->set_content_type('application/json', 'utf-8')
				    ->set_output(json_encode($info))
					->_display();
				    exit;
			}
			if(! $data['borrow_type']){
				$info['state'] = 0;
				$info['message'] = '请完善类型项!';
				$this->output
				    ->set_content_type('application/json', 'utf-8')
				    ->set_output(json_encode($info))
					->_display();
				    exit;
			}
			if(! $data['borrow_duration']){
				$info['state'] = 0;
				$info['message'] = '请完善期限项!';
				$this->output
				    ->set_content_type('application/json', 'utf-8')
				    ->set_output(json_encode($info))
					->_display();
				    exit;
			}
			if(! $data['borrow_min']){
				$info['state'] = 0;
				$info['message'] = '请完善起投金额项!';
				$this->output
				    ->set_content_type('application/json', 'utf-8')
				    ->set_output(json_encode($info))
					->_display();
				    exit;
			}
			$data['borrow_max'] = $data['borrow_max'] ? $data['borrow_max'] : 0;
			if(! $data['repayment_type']){
				$info['state'] = 0;
				$info['message'] = '请完善还款方式项!';
				$this->output
				    ->set_content_type('application/json', 'utf-8')
				    ->set_output(json_encode($info))
					->_display();
				    exit;
			}
			if(! $data['borrow_interest_rate']){
				$info['state'] = 0;
				$info['message'] = '请完善年化利率项!';
				$this->output
				    ->set_content_type('application/json', 'utf-8')
				    ->set_output(json_encode($info))
					->_display();
				    exit;
			}
			if(!isset($data['service_money'])) {
				$data['service_money'] = 0;
			}
			if(! $data['pic']){
				$info['state'] = 0;
				$info['message'] = '请完善图片项!';
				$this->output
				    ->set_content_type('application/json', 'utf-8')
				    ->set_output(json_encode($info))
					->_display();
				    exit;
			}
			$data['add_rate'] = $data['add_rate'] ? $data['add_rate'] : 0;
			if(! $data['number_time']){
				$info['state'] = 0;
				$info['message'] = '请完善募集时间项!';
				$this->output
				    ->set_content_type('application/json', 'utf-8')
				    ->set_output(json_encode($info))
					->_display();
				    exit;
			}
			if(! $data['borrow_useid']){
				$info['state'] = 0;
				$info['message'] = '请完善借款用途项!';
				$this->output
				    ->set_content_type('application/json', 'utf-8')
				    ->set_output(json_encode($info))
					->_display();
				    exit;
			}
			if(! $data['borrow_info']){
				$info['state'] = 0;
				$info['message'] = '请完善借款信息介绍项!';
				$this->output
				    ->set_content_type('application/json', 'utf-8')
				    ->set_output(json_encode($info))
					->_display();
				    exit;
			}
			$data['add_time'] = time();
			$data['send_time'] = time();
			$data['add_ip'] = $this->input->ip_address();
			$data['borrow_status'] = 1;//1申请，2挂标，3放款，4结单
			if($data['borrow_type'] != 1){
				$d['saddress'] = $data['saddress'];
				$d['age'] = $data['age'];
				$d['htime'] = $data['htime'];
				$d['addtime'] = time();
				$d['uid'] = $data['borrow_uid'];
				if(! $d['saddress'] || ! $d['age'] || ! $d['htime']){
					$info['state'] = 0;
					$massge = $data['type']==1?'个人':'公司';
					$info['message'] = '请完善'.$massge.'信息项!';
					$this->output
						->set_content_type('application/json', 'utf-8')
						->set_output(json_encode($info))
						->_display();
						exit;
				}
				unset($data['saddress'], $data['age'], $data['htime'], $data['userfile']);
				$data['admin_id'] = UID;
				if($this->borrow_model->get_borrow_uinfo_byuid($data['borrow_uid'])){
					if($this->borrow_model->add_borrow($data)){
						$info['state'] = 1;
						$info['message'] = '申请成功!';
						$info['url'] = '/borrow.html';
						$this->output
							->set_content_type('application/json', 'utf-8')
							->set_output(json_encode($info))
							->_display();
							exit;
					}else{
						$info['state'] = 0;
						$info['message'] = '申请失败,刷新后重试!';
						$this->output
							->set_content_type('application/json', 'utf-8')
							->set_output(json_encode($info))
							->_display();
							exit;
					}
				}else{
					$this->db->trans_begin();
					$this->borrow_model->add_borrow($data);
					$this->borrow_model->add_borrow_uinfo($d);
					if($this->db->trans_status() === TRUE){
						$this->db->trans_commit();
						$info['state'] = 1;
						$info['message'] = '申请成功!';
						$info['url'] = '/borrow.html';
						$this->output
							->set_content_type('application/json', 'utf-8')
							->set_output(json_encode($info))
							->_display();
							exit;
					}else{
						$this->db->trans_rollback();
						$info['state'] = 0;
						$info['message'] = '申请失败,刷新后重试!';
						$this->output
							->set_content_type('application/json', 'utf-8')
							->set_output(json_encode($info))
							->_display();
							exit;
					}
				}
			}else{
				unset($data['saddress'], $data['age'], $data['htime'], $data['userfile']);
				$data['admin_id'] = UID;
				//p($data);die;
				if($this->borrow_model->add_borrow($data)){
					$info['state'] = 1;
					$info['message'] = '申请成功!';
					$info['url'] = '/borrow.html';
					$this->output
						->set_content_type('application/json', 'utf-8')
						->set_output(json_encode($info))
						->_display();
						exit;
				}else{
					$info['state'] = 0;
					$info['message'] = '申请失败,刷新后重试!';
					$this->output
						->set_content_type('application/json', 'utf-8')
						->set_output(json_encode($info))
						->_display();
						exit;
				}
			}
		}
		$this->load->view('borrow/add');
	}*/
	
	//挂标
	/*
	public function add(){
		if($data = $this->input->post(NULL, TRUE)){
			if(! $data['borrow_uid']){
				$this->error('请完善标借款人姓名项!');
			}
			if(! $data['type']){
				$this->error('请完善标收款类型项!');
			}
			if(! $data['borrow_name']){
				$this->error('请完善标名称项!');
			}
			if(! $data['borrow_money']){
				$this->error('请完善金额项!');
			} else {
				$data['borrow_money'] = intval($data['borrow_money']);
			}
			if(! $data['borrow_type']){
				$this->error('请完善类型项!');
			}
			if(! $data['borrow_duration']){
				$this->error('请完善期限项!');
			}
			if(! $data['borrow_min']){
				$this->error('请完善起投金额项!');
			}
			$data['borrow_max'] = $data['borrow_max'] ? $data['borrow_max'] : 0;
			if(! $data['repayment_type']){
				$this->error('请完善还款方式项!');
			}
			if(! $data['borrow_interest_rate']){
				$this->error('请完善年化利率项!');
			}
			if(!isset($data['service_money'])) {
				$data['service_money'] = 0;
			}
			if(! $data['pic']){
				$this->error('请完善图片项!');
			}
			$data['add_rate'] = $data['add_rate'] ? $data['add_rate'] : 0;
			if(! $data['number_time']){
				$this->error('请完善募集时间项!');
			}
			// if(! $data['borrow_useid']){
				// $this->error('请完善借款用途项!');
			// }
			if(! $data['borrow_use']){
				$this->error('请完善借款用途项!');
			}
			if(!isset($data['guarantor'])) {
				$data['guarantor'] = '';
			}
			if(empty($data['guarantor'])) {
				$data['guarantor'] = '';//$this->error('请完善担保人项!');
			} else {
				if(count(array_unique($data['guarantor'])) > 20) {
					$this->error('担保人不能超过20个');
				} 
				$data['guarantor'] = implode(',', array_unique($data['guarantor']));
			}
			if(! $data['borrow_info']){
				$this->error('请完善借款信息介绍项!');
			}
			if(! $data['borrow_no']){
				$this->error('请完善标的编号!');
			}
			//判断已申请的金额
			//如果已申请金额大于(企业100万，个人20万，不允许提交)
			$money = $this->borrow_model->has_apply_money($data['borrow_uid']);
			$money = intval($money['borrow_money']);
			if($data['type'] == 1 && $money > 0) {//个人标
				if(($money + $data['borrow_money']) > 200000) {
					$this->error('个人标最多放款20万元');
				}
			} 
			if($data['type'] == 2 && $money > 0) {//企业标
				if(($money + $data['borrow_money']) > 1000000) {
					$this->error('企业标最多放款100万元');
				}
			}
			$data['add_time'] = time();
			//$data['send_time'] = time();
			$data['add_ip'] = $this->input->ip_address();
			$data['borrow_status'] = 1;//1申请，2挂标，3放款，4结单
			unset($data['userfile']);
			$data['admin_id'] = UID;
			if($this->borrow_model->add_borrow($data)) {
				$this->success('申请成功!', '/borrow.html');
			} else {
				$this->error('申请失败,刷新后重试!');
			}
		}
		$this->load->view('borrow/add');
	}*/
	
	//上标
	/*
	public function subject() {
		if(IS_POST){
			$id = $this->uri->segment(3);
			if(empty($id)) {
				$this->error('数据有误');
			}
			//调取标信息
			$borrow = $this->borrow_model->get_borrow_byid($id);
			//调取借款人信息
			$meminfo = $this->member_model->get_member_info_byuid($borrow['borrow_uid']);
			
			//数据有问题，报错
			if(!in_array($borrow['type'], array(1, 2))) {
				$this->error('标的类型错误');
			}
			if(empty($meminfo['idcard'])) {
				$this->error('身份证或营业执照不能为空');
			}
			
			if(empty($meminfo['sealPath'])) {
				$this->error('还未生成企业签章');
			} 
			
			//组织上标数据，请求接口
			$params['merSubjectNo'] = getTxNo20();
			$params['subjectName'] = $borrow['borrow_name'];
			$params['subjectAmt'] = $borrow['borrow_money'];
			$params['subjectRate'] = floatval(round($borrow['borrow_interest_rate']/100, 4));
			$params['subjectPurpose'] = '资金周转';
			$params['payeeAcctNo'] = $meminfo['acctNo'];//'110181805300010215';//
			$params['subjectType'] = $borrow['type'] === '1' ? '00' : '01';
			$params['identificationNo'] = $meminfo['idcard'];
			$params['serviceRate'] = floatval(round($borrow['service_money']/100, 4));
			$params['subjectStartDate'] = date('Ymd');//'20180829';
			$params['SubjectEndDate'] = date('Ymd', time() + ($borrow['number_time']*24*3600));
			//p($params);die;
			$head = head($params, 'CG1012', 'createdo');
			if(empty($head['merOrderNo'])) {
				$this->error('订单号出错，请联系管理员');
			}
			water($meminfo['uid'], $head['merOrderNo'], 'CG1012', $id);
			unset($head['callbackUrl']);
			unset($head['registerPhone']);
			unset($head['responsePath']);
			unset($head['url']);
			$data = $head;
			$data = json_encode($data);
			$url = $this->config->item('Interface').'1012';
			$str = post_curl_test($url, $data);
			
			$this->load->model(array('paytest/paytest_model'));
			$body = $this->paytest_model->excute($str);
			//接口返回信息
			if(!isset($body['head']['respCode'])) {
				$this->error('异常错误，请联系管理员');
			}
			if($body['head']['respCode'] === '000000') {
				//回写借款表，状态修改为2
				$borrow['subjectNo'] = $body['body']['subjectNo'];
				$borrow['borrow_status'] = 2;
				$borrow['send_time'] = time();
				$this->borrow_model->modify_borrow($borrow, $id);
				
				$this->success($body['head']['respDesc'], '/borrow.html');
			} else {
				$this->error($body['head']['respDesc']);
			}

		}
		
	}*/
}