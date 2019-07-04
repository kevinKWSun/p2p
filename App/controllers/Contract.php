<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Contract extends MY_Controller {
	public function __construct() {
        parent::__construct();
        $this->load->library('pagination');
		$this->load->model(array('water/water_model', 'guarantor/guarantor_model', 'borrow/borrow_model', 'member/member_model', 'contract/contract_model'));
    }
	
	/** 批量签署pdf */
	public function build_more() {
		$id = $this->uri->segment(3);
		if(empty($id)) {
			exit('信息错误');
		}
		$this->load->model('bid/bid_model');
		$data = array();
		$where['borrow_id'] = $id;
		$numbers = $this->bid_model->get_borrow_investor_num($where);
        $data['invest'] = $this->bid_model->get_borrow_investor_lists($numbers, 0, $where);
		foreach($data['invest'] as $k=>$v) {
			$data['invest'][$k]['contract_status'] = $this->contract_model->get_contract_pdf_byinvestid($v['id'])['status'];
			if($data['invest'][$k]['contract_status'] > 0) {
				continue;
			}
			$this->bulid_contract($v['id']);
		}
		$this->success('批量生成成功');
	}
	
	/** 生成一笔合同 */
	public function build_one() {
		$id = $this->uri->segment(3);
		
		if(empty($id)) {
			$this->error('信息错误，请联系管理员');
		}
		$this->bulid_contract($id);
		$this->success('操作成功');
		// if(isset($ret) && $ret === false) {
			// $this->error('操作失败');
		// } else {
			// $this->success('操作成功');
		// }
		
	}
	
	/** 生成合同 */
	private function bulid_contract($invest_id) {
		$contract = $this->contract_model->get_contract_pdf_byinvestid($invest_id);
		
		if(empty($contract) || empty($contract['nid'])) {
			$this->error('信息有误，请联系管理员');
		}
		if(empty($contract['src_path'])) {
			if(empty($contract['src_path'])) {
				$data = $this->get_contract_info($contract['nid']);
				$html = contract_build($data, 10);
				$save_path = html2pdf($html, array('F'), '');
				$contract['src_path'] = $save_path;
				$contract['contract_num'] = $data['contract_num'];
			}
			$this->contract_model->modify_contract_pdf($contract);
		}
		
		//签署合同
		
		//调取合同信息
		$contract = $this->contract_model->get_contract_pdf_bynid($contract['nid']);
		//调取流水信息
		$water = $this->water_model->get_water_byorder($contract['nid']);
		
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
		$investor = $this->member_model->get_member_info_byuid($water['uid']);
		
		$this->load->library('EsignAPI/EsignAPI'); 
		$EsignAPI = new EsignAPI();
		
		//出借人签署
		$path = dirname(BASEPATH);
		if(empty($contract['des_path'])) {
			$signPos = array(
				'posPage' => count_pdf_pages($path . $contract['src_path']),//6,
				'posX' => 150,
				'posY' => 495,
				'key' => '',
				'width' => 50
			);
			
			$res = $EsignAPI->userSignPDF($investor, $path, $contract['src_path'], $signPos);
			if(!empty($res['errCode'])) {
				$this->error($res['msg']);
			} else {
				$contract['des_path'] 		= $res['des_path'];
				$contract['signServiceId']  = $res['signServiceId'];
				$contract['signDetailUrl']  = '';
				$contract['uptime']  		= time();
				$contract_return = $this->contract_model->modify_contract_pdf($contract);
			}
		}
		$sign_path = (isset($res['des_path']) && $res['des_path']) ? $res['des_path'] : $contract['des_path'];
		//借款人签署
		//签署位置
		$signPos = array(
			'posPage' => 6,
			'posX' => 150,
			'posY' => 410,
			'key' => '',
			'width' => 150
		);
		$res = $EsignAPI->userSignPDF($borrow_info, $path, $contract['des_path'], $signPos);
		if(!empty($res['errCode'])) {
			$this->error($res['msg'] . 1);
		} 
		// else {
			// $data_water = array(
				// 'uid' => $borrow['borrow_uid'],
				// 'nid' => $water['merOrderNo'],
				// 'path' => $res['des_path'],
				// 'signServiceId' => $res['signServiceId']
			// );
			// $this->contract_model->add_contract_water($data_water);
		//}
		//要签署文档的路径
		unlink($path . $sign_path);//删除文件
		$sign_path = $res['des_path'];
		
		//担保人签署
		if(!empty($borrow['guarantor'])) {
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
				} 
				// else {
					// $data_water = array(
						// 'uid' => $v['id'],
						// 'nid' => $water['merOrderNo'],
						// 'path' => $res['des_path'],
						// 'signServiceId' => $res['signServiceId'],
						// 'type'	=> 1
					// );
					// $this->contract_model->add_contract_water($data_water);
				// }
				unlink($path . $sign_path);//删除文件
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
				'nid' => $water['merOrderNo'],
				'path' => $res['des_path'],
				'signServiceId' => $res['signServiceId']
			);
			$this->contract_model->add_contract_water($data_water);
		}
		unlink($path . $sign_path);//删除文件
		$sign_path = $res['des_path'];
		$contract['path'] = $sign_path;
		$contract['uptime'] = time();
		if(!$this->contract_model->modify_contract_pdf($contract)) {
			$this->error('操作失败');
		}
		//合同保全服务
		$contract = $this->contract_model->get_water_bynid($water['merOrderNo']);
		$res_esign = $EsignAPI->saveWitnessGuide($contract['signServiceId']);
		if(empty($res_esign['errCode'])) {
			$contract_pdf = $this->contract_model->get_contract_pdf_bynid($water['merOrderNo']);
			$contract_pdf['status'] = 1;
			//$contract_pdf['invest_id'] = $borrow_investor_id;
			$this->contract_model->modify_contract_pdf($contract_pdf);
		} else {
			$this->error($res_esign['errCode']);
		}
	}
	
	/** 查看合同 */
	public function show() {
		$id = $this->uri->segment(3);
		
		if(empty($id)) {
			exit('信息错误，请联系客服');
		}
		
		//投资信息
		$contract_info = $this->contract_model->get_contract_pdf_byinvestid($id);
		if(empty($contract_info['path'])) {
			exit('合同还未生成，如有问题，请联系管理员');
		}
		$basedir = dirname(BASEPATH);
		$this->output
				->set_content_type('pdf') 
				->set_output(file_get_contents($basedir . $contract_info['path']));
	}
	
	/** 详情 */
	public function detail() {
		$id = $this->uri->segment(3);
		if(empty($id)) {
			exit('信息错误');
		}
		$this->load->model('bid/bid_model');
		$data = array();
		$where['borrow_id'] = $id;
		$numbers = $this->bid_model->get_borrow_investor_num($where);
        $data['invest'] = $this->bid_model->get_borrow_investor_lists($numbers, 0, $where);
		foreach($data['invest'] as $k=>$v) {
			$data['invest'][$k]['contract_status'] = $this->contract_model->get_contract_pdf_byinvestid($v['id'])['status'];
		}
		$data['id'] = $id;
		$this->load->view('contract/detail', $data);
	}
	
	/*生成合同列表 */
	public function lists() {
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
		$per_page = 10;
		$current_page = $this->uri->segment(3) ? $this->uri->segment(3) - 1 : 0;
        $offset = $current_page;
        $config['base_url'] = base_url('contract/lists');
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
        $data['borrow'] = $borrow;
		$this->load->view('contract/lists', $data);
	}
	
	public function index() {
		$data = array();
		$current_page  = intval($this->uri->segment(3));
		$current_page = $current_page > 0 ? $current_page - 1 : 0;
		$per_page = 10;
        $offset = $current_page;
        $config['base_url'] = base_url('contract/index');
        $config['total_rows'] = $this->contract_model->get_contract_nums();
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
        $data['contract'] = $this->contract_model->get_contract_list($per_page, $offset * $per_page);

		$this->load->view('contract/list', $data);
	}
	/** 添加 */
	public function add() {
		if(IS_POST) {
			$post = $this->input->post(NULL);
			$post['mold'] = $post['editorValue'];
			unset($post['editorValue']);
			$post['addtime'] = time();
			if($this->contract_model->add_contract($post)) {
				$this->success('操作成功', '/contract/index.html');
			} else {
				$this->error('操作失败');
			}
		}
		$this->load->view('contract/add');
	}
	
	/** 禁用状态 */
	public function status() {
		$id = $this->uri->segment(3);
		if(empty($id)) {
			$this->error('信息错误');
		}
		if(IS_POST) {
			$contract = $this->contract_model->get_contract_one($id);
			$contract['status']  = $contract['status'] ? 0 : 1;
			if($this->contract_model->modify_contract($contract)) {
				$this->success('操作成功', '/contract/index.html');
			} else {
				$this->error('操作失败');
			}
		}
	}
	
	/** 修改内容 */
	public function modify() {
		if(IS_POST) {
			$post = $this->input->post(NULL);
			if(empty($post['id'])) $this->error('信息错误');
			$post['mold'] = $post['editorValue'];
			unset($post['editorValue']);
			if($this->contract_model->modify_contract($post)) {
				$this->success('操作成功', '/contract/index.html');
			} else {
				$this->error('操作失败');
			}
		}
		$id = $this->uri->segment(3);
		if(empty($id)) exit('信息错误');
		$data['contract'] = $this->contract_model->get_contract_one($id);
		$this->load->view('contract/modify', $data);
	}
	
	/** 删除内容 */
	public function delte() {
		$id = $this->uri->segment(3);
		if(empty($id)) {
			$this->error('信息错误');
		}
		if(IS_POST) {
			$contract = $this->contract_model->get_contract_one($id);
			$contract['del']  = 1;
			if($this->contract_model->modify_contract($contract)) {
				$this->success('操作成功', '/contract/index.html');
			} else {
				$this->error('操作失败');
			}
		}
	}
	//生成借款合同所需要的数据
	private function get_contract_info($merOrderNo) {
		if(empty($merOrderNo)) {
			$this->error('信息错误');
		}
		//时间戳
		$timestamp = time();
		//调取流水信息
		$water = $this->water_model->get_water_byorder($merOrderNo);
		//借款信息
		$data = array();
		//$investor = $this->borrow_model->get_borrow_investor_by_id($id);
		$borrow = $this->borrow_model->get_borrow_byid($water['bid']);
		//如果有放款时间
		
		if(empty($borrow)) {
			$this->error('连接出错');
		}
		if(!$borrow['endtime']) {
			$this->error('还未放款，不能生成合同');
		}
		if($borrow['endtime']) {
			$timestamp = $borrow['endtime'];
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
		//投资人信息
		$meminfos = $this->member_model->get_member_info_byuid($water['uid']);
		$data['investor_name'] = $meminfos['real_name'];
		$data['investor_idcard_type'] = '身份证';
		$data['investor_idcard'] = $meminfos['idcard'];
		$data['investor_capital'] = $water['money'] . '元';
		//担保人
		if(!empty($borrow['guarantor'])) {
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
		$data['borrow_use'] = $borrow['borrow_use'];//$this->config->item('borrow_useid')[$borrow['borrow_useid']];
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
		$contract = $this->contract_model->get_contract_pdf_bynid($merOrderNo);
		if(!empty($contract)) {
			//判断合同日期必须是当天
			// if(!empty($contract['contract_num']) && (substr($contract['contract_num'], 0, 8) != substr(date('YmdHis', $timestamp).$water['bid'].QUID, 0, 8))) {
				// exit('合同出错，请重新投标');
			// }
			if(!empty($contract['contract_num'])) {
				$data['contract_num'] = $contract['contract_num'];
			} else {
				$data['contract_num'] = date('YmdHis', $timestamp).$water['bid'].$water['uid'];
			}
			
		} else {
			$data['contract_num'] = date('YmdHis', $timestamp).$water['bid'].$water['uid'];
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
}