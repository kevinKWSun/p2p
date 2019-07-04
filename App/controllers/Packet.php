<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Packet extends MY_Controller {
	public function __construct() {
        parent::__construct();
		$this->load->library('pagination');
        $this->load->model(array('packet/packet_model', 'member/member_model'));
    }
	/** 现金红包发放 */
	public function index() {
		$data = array();
		$where = array();
		$search = $this->input->get(null, true);
		if(!empty($search['skey'])) {
			$data['skey'] = trim(trim($search['skey']), '\t');
			$where['skey'] = $data['skey'];
		}
		$current_page = intval($this->uri->segment(3)) ? intval($this->uri->segment(3)) - 1 : 0;
		$per_page = 10;
        $offset = $current_page;
        $config['base_url'] = base_url('packet/index');
        $config['total_rows'] = $this->packet_model->get_packet_nums($where);
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
        $data['packet'] = $this->packet_model->get_packet_list($per_page, $offset * $per_page, $where);
		$this->load->view('packet/index', $data);
	}
	
	/** 投资红包列表 */
	public function lists() {
		$data = array();
		$where = array();
		$search = $this->input->get(null, true);
		if(!empty($search['skey'])) {
			$data['skey'] = trim(trim($search['skey']), '\t');
			$where['skey'] = $data['skey'];
		}
		$current_page = intval($this->uri->segment(3)) ? intval($this->uri->segment(3)) - 1 : 0;
		$per_page = 10;
        $offset = $current_page;
        $config['base_url'] = base_url('packet/lists');
        $config['total_rows'] = $this->packet_model->get_packet_numss($where);
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
        $data['packet'] = $this->packet_model->get_packet_lists($per_page, $offset * $per_page, $where);
		//p($data['packet']);
		$this->load->view('packet/lists', $data);
	}
	/** 投资红包撤销 */
	public function revoke() {
		if(IS_POST) {
			$post = $this->input->post(null, true);
			$post['id'] = intval($post['id']);
			//查询红包是否已经使用
			$packet = $this->packet_model->get_packet_one($post['id']);
			if($packet['status'] == 1) {
				$this->error('红包已使用');
			}
			$data['packet'] = [
				'id' => $post['id'],
				'remark' => mb_substr($post['remark'], 0, 299),
				'status' => 1
			];
			
			$data['revoke'] = [
				'pid' => $post['id'],
				'adminid' => UID,
				'addtime' => time()
			];
			if($this->packet_model->revoke_packet($data['packet'])) {
				$this->packet_model->insert_revoke($data['revoke']);
				$this->success('操作成功');
			} else {
				$this->error('操作失败');
			}
		} else {
			$id = $this->uri->segment(3);
			$data['id'] = intval($id);
			$this->load->view('packet/revoke', $data);
		}
		
	}
	/** 发放现金红包 和 投资红包 列表 */
	public function member() {
		$data = array();
		//判断是现金红包还是投资红包
		$data['type'] = $this->uri->segment(3);
		
		$where = array();
		$search = $this->input->get(null, true);
		
		if(!empty($search['skey'])) {
			$data['skey'] = trim(trim($search['skey']), '\t');
			$where['skey'] = $data['skey'];
		}
		$where['members_status.id_status'] = 1;
		$where['members.attribute'] = 1;
		$current_page = $this->uri->segment(3) ? $this->uri->segment(3) : 1;
        $current_page = $current_page > 0 ? $current_page - 1 : 0;
		$per_page = 10;
        $offset = $current_page;
        $config['base_url'] = base_url('packet/member');
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
		//p($data);
		$this->load->view('packet/member', $data);
	}
	/** 发放现金红包页面 */
	public function send() {
		if(IS_POST) {
			$post = $this->input->post(null, true);
			$post['ids'] = explode(',', $post['ids']);
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
			foreach($post['cash'] as $k=>$v) {
				$post['cash'][$k] = intval($v);
				if($post['cash'][$k] < 0.001) {
					$this->error('红包金额不能为0');
				}
			}
			
			
			
			//红包金额大于2000， 拆分红包2018-10-30
			$invest_count = count($post['cash']);
			$j = $invest_count;
			foreach($post['cash'] as $k=>$v) {
				if($v > 2000) {
					$invest_num = intval($v/2000);
					$invest_yu = $v % 2000;
					$post['cash'][$k] = 2000;
					for($i = 1; $i <= $invest_num; $i++) {
						if($invest_num == $i && $invest_yu < 0.001) {
							continue;
						}
						$post['cash'][$j] = $invest_num == $i ? $invest_yu : 2000;
						
						$post['remark'][$j] = $post['remark'][$k];
						
						$j++;
					}
				}
			}
			
			
			
			
			

			
			
			//时间戳
			$timestamp = time();
			//投资人要列出来
			$investor_uid = $post['ids'];
			//p($investor_uid);die;
			//现金红包
			//投资者
			$i = 0;
			//投资人账户金额
			$this->load->model('account/info_model');
			foreach($investor_uid as $v) {
				$memoney = $this->info_model->get_money($v);
				$acountinfo[$v]['account_money'] = $memoney['account_money'];
				$acountinfo[$v]['money_collect'] = $memoney['money_collect'];
				$acountinfo[$v]['money_freeze'] = $memoney['money_freeze'];
			}
			//验证账号都存在
			foreach($investor_uid as $value) {
				$meminfos[$value] = $this->member_model->get_member_info_byuid($value);
				if(empty($meminfos[$value]['acctNo'])) {
					$this->error('客户' . $meminfos[$value]['real_name'] . '还未绑定银行卡');
				}
			}
			//红包转账
			foreach($investor_uid as $value) {
				foreach($post['cash'] as $k=>$v) {
					$meminfo = $meminfos[$value];
					//调用接口发放红包
					$params['payerAcctNo'] = $this->config->item('mchnt_red');
					$params['payeeAcctNo'] = $meminfo['acctNo'];
					$params['amount'] = $v;
					$params['payType'] = '02';//'00':个人->个人'01':个人->商户'02':商户->个人
					$params['remark'] = '转账';
					$head = head($params, 'CG1008', 'send');
					//p($head);die;
					water($value, $head['merOrderNo'], 'CG1008');
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
						$datas['money']		= $v;
						$datas['redid']		= 0;
						$datas['status']	= 1;
						$this->load->model('water/water_model');
						$this->water_model->edit_water($datas, $tmp_body['head']['merOrderNo']);
						//账户金额增加
						$acountinfo[$value]['account_money'] += $v;
						$data['log'][] = array(
							'uid' => $value,
							'type' => 7,//现金红包
							'affect_money' => $v,
							'account_money' => $acountinfo[$value]['account_money'],//可用
							'collect_money' => $acountinfo[$value]['money_collect'],//待收
							'freeze_money' => $acountinfo[$value]['money_freeze'],//冻结
							'info' => '发放' . $v . '元现金红包',
							'add_time' => $timestamp,
							//'add_ip' => $this->input->ip_address(),
							'bid' => 0,
							'nid'	=> $tmp_body['head']['merOrderNo']
						);
						$data['cash'][$k]['nid'] = $tmp_body['head']['merOrderNo'];//将现金红包状态修改为1
					} else {
						$datas['uid']		= $value;
						$datas['bid']		= 0;
						$datas['money']		= $v;
						$datas['status']	= 0;
						$datas['addtime']	= time();
						$datas['remark']	= '发放现金红包失败';
						$this->member_model->add_packet_error($datas);
						
						
						//发送红包失败也需要记录日志2018-10-30 
						$datass['merOrderNo'] = $tmp_body['head']['merOrderNo'];
						$datass['money']		= $v;
						$datass['redid']		= 0;
						$datass['status']	= 1;
						$this->load->model('water/water_model');
						$this->water_model->edit_water($datass, $tmp_body['head']['merOrderNo']);
						//账户金额增加
						//$acountinfo[$value]['account_money'] += $v;
						$data['log'][] = array(
							'uid' => $value,
							'type' => 14,//现金红包
							'affect_money' => $v,
							'account_money' => $acountinfo[$value]['account_money'],//可用
							'collect_money' => $acountinfo[$value]['money_collect'],//待收
							'freeze_money' => $acountinfo[$value]['money_freeze'],//冻结
							'info' => '发放' . $v . '元现金红包, ' . $tmp_body['head']['respDesc'],
							'add_time' => $timestamp,
							//'add_ip' => $this->input->ip_address(),
							'bid' => 0,
							'nid'	=> $tmp_body['head']['merOrderNo']
						);
						$data['cash'][$k]['nid'] = $tmp_body['head']['merOrderNo'];//将现金红包状态修改为1
						
					}
					//现金红包个数
					$data['cashes'][] = array(
						'bid' => 0,
						'uid' => $value,
						'money' => $v,
						'remark' => $post['remark'][$k],
						'addtime' => $timestamp,
						'adminid' => UID,
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
			unset($data['cash']);
			//p($data);die;
			//p($data);die;
			//发送红包记录日志2018-10-30
			//log_record(serialize($data), '/red.log', 'uid-' . serialize($post['ids']));
			$this->load->model(array('borrow/borrow_model'));
			$this->borrow_model->send($data);
			$this->success('操作成功', '/packet/member.html'); 
		}
		$ids = trim($this->input->get('ids', true), ',');
		$data = array();
		$data['ids'] = $ids;
		$data['count'] = count(explode(',', $ids));
		$this->load->view('packet/send', $data);
	}
	
	
	/** 发放投资红包页面 */
	public function sendt() {
		if(IS_POST) {
			$post = $this->input->post(null, true);
			$post['ids'] = explode(',', $post['ids']);
			foreach($post as $value) {
				if(is_array($value)) {
					foreach($value as $v) {
						if($v == '' || empty($v)) {
							$this->error('必填项不能为空');
						}
					}
				} else {
					if($value == '' || empty($value)) {
						$this->error('必填项不能为空');
					}
				}
			}
			
			$datas = array();
			foreach($post['invest'] as $k=>$v) {
				if(is_float($v)) {
					$this->error('红包金额不能有小数');
				}
				$datas['invest'][$k]['money'] = intval($v);
				if($datas['invest'][$k]['money'] > 2000) {
					$this->error('红包金额不能超过2000元');
				}
				$datas['invest'][$k]['min_money'] = $post['min_money'][$k];
				$datas['invest'][$k]['times'] = $post['times'][$k];
				$datas['invest'][$k]['etime'] = $post['etime'][$k];
			}
			//p($data);
			//时间戳
			$timestamp = time();
			//投资人要列出来
			$investor_uid = $post['ids'];
			//p($investor_uid);
			$data = array();
			$i = 0;
			foreach($investor_uid as $key=>$value) {
				//投资红包数量
				foreach($datas['invest'] as $k=>$v) {
					$data['invest'][$i]['uid'] = $value;
					$data['invest'][$i]['bid'] = 0;
					$data['invest'][$i]['stime'] = $timestamp;
					$data['invest'][$i]['etime'] = $timestamp + intval($v['etime']) * 3600 * 24;
					$data['invest'][$i]['money'] = $v['money'];
					$data['invest'][$i]['min_money'] = $v['min_money'];
					$data['invest'][$i]['times'] = intval($v['times']);
					$data['invest'][$i]['type'] = 1;
					$data['invest'][$i]['addtime'] = $timestamp;
					$data['invest'][$i]['status'] = 0;
					$data['invest'][$i]['sid'] = 0;
					$data['invest'][$i]['admin_id'] = UID;
					$i++;
				}
			}
			
			//生成一条记录日志
			foreach($data['invest'] as $k=>$v) {
				//调取账户金额
				$memoney = $this->member_model->get_members_money_byuid($v['uid']);
				$data['log'][] = array(
					'uid' => $v['uid'],
					'type' => 6,//投资红包
					'affect_money' => $v['money'],
					'account_money' => $memoney['account_money'],//可用
					'collect_money' => $memoney['money_collect'],//待收
					'freeze_money' => $memoney['money_freeze'],//冻结
					'info' => '发放' . $v['money'] . '元,出借红包',
					'add_time' => $timestamp,
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
			
			//die;
			//p($post);
			log_record(serialize($data), '/red.log', 'bid-' . implode(',', $post['ids']));
			$this->load->model(array('borrow/borrow_model'));
			if($this->borrow_model->send($data)) {
				$this->success('操作成功', '/packet/member/1.html');
			} else {
				$this->error('操作失败');
			}
		}
		$ids = trim($this->input->get('ids', true), ',');
		$data = array();
		$data['ids'] = $ids;
		$data['count'] = count(explode(',', $ids));
		$this->load->view('packet/sendt', $data);
	}
	/** 红包发放失败记录 */
	public function mistake() {
		$data = array();
		$where = array();
		$search = $this->input->get(null, true);
		if(!empty($search['time'])) {
			if(!empty($search['time'])) {
				$data['time'] = $search['time'];
				$where['time'] = explode(' ', $search['time']);
			}
			
		}
		if(!empty($search['name'])) {
			$data['name'] = trim(trim($search['name']), '\t');
			$where['uid'] = array();
			foreach($this->packet_model->get_info_byname($data['name']) as $v) {
				if(!in_array($v['uid'], $$where['uid'])) {
					$where['uid'][] = $v['uid'];
				}
			}
			//$where['uid'] = implode(',', $where['uid']);
		}
		$current_page = intval($this->uri->segment(3)) ? intval($this->uri->segment(3)) - 1 : 0;
		$per_page = 20;
        $offset = $current_page;
        $config['base_url'] = base_url('packet/mistake');
        $config['total_rows'] = $this->packet_model->get_mistake_nums($where);
        $config['per_page'] = $per_page;
		$config['page_query_string'] = FALSE;
		$config['first_link'] = '首页'; // 第一页显示   
		$config['last_link'] = '末页'; // 最后一页显示   
		$config['next_link'] = '下一页'; // 下一页显示   
		$config['prev_link'] = '上一页'; // 上一页显示   
		$config['cur_tag_open'] = ' <span class="current">'; // 当前页开始样式   
		$config['cur_tag_close'] = '</span>';   
		$config['num_links'] = 20;
		$config['uri_segment'] = 3;
		$config['use_page_numbers'] = TRUE;
		$config['reuse_query_string'] = TRUE;
        $this->pagination->initialize($config); 
        $data['totals'] = $config['total_rows'];
        $data['page'] = $this->pagination->create_links();
        $data['p'] = $current_page;
        $data['mistake'] = $this->packet_model->get_mistake_lists($per_page, $offset * $per_page, $where);
		foreach($data['mistake'] as $k=>$v) {
			if($v['rid'] > 0) {
				$packet = $this->packet_model->get_packet_byid($v['rid']);
				$meminfo = $this->member_model->get_member_info_byuid($packet['uid']);
				$data['mistake'][$k]['real_name'] = $meminfo['real_name'];
				$data['mistake'][$k]['uid'] = $meminfo['uid'];
				$data['mistake'][$k]['phone'] = $meminfo['phone'];
				$data['mistake'][$k]['money'] = $packet['money'];
				$data['mistake'][$k]['remark'] = '';
			} else {
				$meminfo = $this->member_model->get_member_info_byuid($v['uid']);
				$data['mistake'][$k]['uid'] = $meminfo['uid'];
				$data['mistake'][$k]['real_name'] = $meminfo['real_name'];
				$data['mistake'][$k]['phone'] = $meminfo['phone'];
				$data['mistake'][$k]['remark'] = $v['remark'];
			}
		}
		$this->load->view('packet/mistake', $data);
	}
	/** 红包发放失败导出 */
	public function export() {
		$where = array();
		$search = $this->input->get(null, true);
		if(!empty($search['time'])) {
			if(!empty($search['time'])) {
				$data['time'] = $search['time'];
				$where['time'] = explode(' ', $search['time']);
			}
			
		}
		if(!empty($search['name'])) {
			$data['name'] = trim(trim($search['name']), '\t');
			$where['uid'] = array();
			foreach($this->packet_model->get_info_byname($data['name']) as $v) {
				if(!in_array($v['uid'], $$where['uid'])) {
					$where['uid'][] = $v['uid'];
				}
			}
		}
		$count = $this->packet_model->get_mistake_nums($where);
		$data['mistake'] = $this->packet_model->get_mistake_lists($count, 0, $where);
		foreach($data['mistake'] as $k=>$v) {
			if($v['rid'] > 0) {
				$packet = $this->packet_model->get_packet_byid($v['rid']);
				$meminfo = $this->member_model->get_member_info_byuid($packet['uid']);
				$data['mistake'][$k]['real_name'] = $meminfo['real_name'];
				$data['mistake'][$k]['uid'] = $meminfo['uid'];
				$data['mistake'][$k]['phone'] = $meminfo['phone'];
				$data['mistake'][$k]['money'] = $packet['money'];
				$data['mistake'][$k]['remark'] = '';
			} else {
				$meminfo = $this->member_model->get_member_info_byuid($v['uid']);
				$data['mistake'][$k]['uid'] = $meminfo['uid'];
				$data['mistake'][$k]['real_name'] = $meminfo['real_name'];
				$data['mistake'][$k]['phone'] = $meminfo['phone'];
				$data['mistake'][$k]['remark'] = $v['remark'];
			}
		}
		
		$borrow = $data['mistake'];
		$all = $borrow;
		$this->load->helper('common');
		$this->load->library('PHPExcel');
		$resultPHPExcel = new PHPExcel();
		$resultPHPExcel->getActiveSheet()->setCellValue('A1', 'ID');
		$resultPHPExcel->getActiveSheet()->setCellValue('B1', '用户ID');
		$resultPHPExcel->getActiveSheet()->setCellValue('C1', '真实姓名');
		$resultPHPExcel->getActiveSheet()->setCellValue('D1', '手机号');
		$resultPHPExcel->getActiveSheet()->setCellValue('E1', '红包金额');
		$resultPHPExcel->getActiveSheet()->setCellValue('F1', '时间');
		$resultPHPExcel->getActiveSheet()->setCellValue('G1', '备注');
		$i = 1;
		foreach($all as $k => $v){
			$i++;
			$resultPHPExcel->getActiveSheet()->setCellValue('A'.$i, $v['id']);
			$resultPHPExcel->getActiveSheet()->setCellValue('B'.$i, $v['uid']);
			$resultPHPExcel->getActiveSheet()->setCellValue('C'.$i, $v['real_name']);
			$resultPHPExcel->getActiveSheet()->setCellValue('D'.$i, $v['phone'] . ' ');
			$resultPHPExcel->getActiveSheet()->setCellValue('E'.$i, $v['money']);
			$resultPHPExcel->getActiveSheet()->setCellValue('F'.$i, date('Y-m-d H:i', $v['addtime']));
			$resultPHPExcel->getActiveSheet()->setCellValue('G'.$i, $v['remark']);
		}
		$outputFileName = '发放红包失败记录.xls'; 
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
}