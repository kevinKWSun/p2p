<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Record extends Baseaccount {
	public function __construct() {
		parent::__construct();
		$this->load->library('pagination');
		$this->load->model(array('borrow/borrow_model','member/member_model'));
		//$this->load->helper('url');
	}
	//首页
	public function index(){
		/* $one = strtotime(date('Y-m-d'));
		$tow = strtotime(date('Y-m-d') . ' 23:59:59');
		$data['day'] = $this->borrow_model->get_moneys_hk(QUID, '', $one, $tow);
		$one = strtotime(date('Y-m-01'));
		$tow = strtotime(date('Y-m-t') . ' 23:59:59');
		$data['month'] = $this->borrow_model->get_moneys_hk(QUID, '', $one, $tow); */
		$current_page = $this->uri->segment(3) ? $this->uri->segment(3) : 1;
		$status = $this->input->get('query') ? $this->input->get('query') : 0;//4,2,5
        if($current_page > 0){
            $current_page = $current_page - 1;
        }else if($current_page < 0){
            $current_page = 0;
        }
		$per_page = 10;
        $offset = $current_page;
        $config['base_url'] = base_url('record/index');
        $config['total_rows'] = $this->borrow_model->get_borrow_investor_num($status, QUID);
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
		$data['status'] = $status;
        $borrow = $this->borrow_model->get_borrow_investor($per_page, $offset * $per_page, $status, QUID);
        $data['borrow'] = $borrow;
		$this->load->view('account/record_v1', $data);
	}
	public function agreement(){
		$id = $this->uri->segment(3);
		$type = $this->uri->segment(4);
		
		if(empty($id) || empty($type)) {
			exit('信息错误，请联系客服');
		}
		
		//投资信息
		$this->load->model(array('contract/contract_model'));
		$contract_info = $this->contract_model->get_contract_pdf_byinvestid($id);
		if(empty($contract_info)) {
			exit('合同还未生成，如有问题，请联系客服人员');
		}
		$basedir = dirname(BASEPATH);
		if($type == 1) {//借款合同
			if(!$contract_info['path']) {
				exit('合同还未生成，如有问题，请联系客服人员');
			}
			$this->output
				->set_content_type('pdf') 
				->set_output(file_get_contents($basedir . $contract_info['path']));
		} else {
			if(!$contract_info['pdf_path']) {
				exit('合同还未生成，如有问题，请联系客服人员');
			}
			$this->output
				->set_content_type('pdf') 
				->set_output(file_get_contents($basedir . $contract_info['pdf_path']));
		}
		
		
		
		/*$id = $this->uri->segment(3) ? $this->uri->segment(3) : 0;
		if(empty($id)) {
			exit('数据错误');
		}
		
		//借款信息
		$data = array();
		$investor = $this->borrow_model->get_borrow_investor_by_id($id);
		$borrow = $this->borrow_model->get_borrow_byid($investor['borrow_id']);
		
		foreach($investor as $k=>$v) {
			$data[$k] = $v;
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
		$meminfos = $this->member_model->get_member_info_byuid($data['investor_uid']);
		if($data['investor_uid'] != QUID) {
			exit('信息错误');
		}
		$data['investor_name'] = $meminfos['real_name'];
		$data['investor_idcard_type'] = '身份证';
		$data['investor_idcard'] = $meminfos['idcard'];
		$data['investor_capital'] = $investor['investor_capital'] . '元';
		//担保人
		if(!empty($borrow['guarantor'])) {
			$this->load->model('guarantor/guarantor_model');
			$guarantor = $this->guarantor_model->get_guarantor_more(explode(',', $borrow['guarantor']));
			$data['guarantor_html'] = '';
			$data['guarantor_names'] = '';
			foreach($guarantor as $k=>$v) {
				if($k > 0) {
					$data['guarantor_html'] .= '<strong>担保方：' . $v['name'] . '</strong></p><p>证件类型： 身份证&nbsp;</p><p>证件号码：' . $v['idcard'] . '&nbsp;</p><p>地址：' . $v['address'] . '&nbsp;</p><p><br />';
				} else {
					$data['guarantor_name'] = $v['name'];
					$data['guarantor_idcard_type'] = '身份证';
					$data['guarantor_idcard'] = $v['idcard'];
					$data['guarantor_address'] = $v['address'];
				}
				$data['guarantor_names'] .= $v['name'] . ', ';
			}
			$data['guarantor_names'] = rtrim($data['guarantor_names'], ', ');
		} else {
			$data['guarantor_html'] = '';
			$data['guarantor_name'] = '';
			$data['guarantor_idcard_type'] = '';
			$data['guarantor_idcard'] = '';
			$data['guarantor_address'] = '';
			$data['guarantor_names'] = '';
		}
		//借款信息
		$data['borrow_title'] = $borrow['borrow_name'];
		//$data['borrow_money'] = $borrow['borrow_money'] . '元';
		$data['borrow_duration'] = $this->config->item('borrow_duration')[$borrow['borrow_duration']] . '天';
		$data['borrow_repayment'] = $this->config->item('repayment_type')[$borrow['repayment_type']];
		$data['borrow_total'] = $borrow['total'] . '期';
		$data['borrow_rate'] = $borrow['borrow_interest_rate'] + $borrow['add_rate'];
		$data['borrow_endtime'] = date('Y年m月d日', $borrow['endtime']);
		$data['borrow_deadline'] = date('Y年m月d日', $investor['deadline']);
		$data['borrow_use'] = $this->config->item('borrow_useid')[$borrow['borrow_useid']];
		
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
		
		$data['contract_num'] = date('Ymd', $data['endtime']).$data['borrow_id'].$id;
		$data['subject_num'] = date('Ymd', $data['send_time']).$data['borrow_id'];
		//签署日期
		$data['y'] = date('Y', $data['endtime']);
		$data['m'] = date('m', $data['endtime']);
		$data['d'] = date('d', $data['endtime']);
		//放款时间
		//$data['endtime'] = date('Y年m月d日', $borrow['endtime']);
		//计息日
		$data['start_time'] = date('Y年m月d日', $borrow['endtime'] + 86400);
		
		$data['mold'] = contract_build($data, 10);

		$this->load->view('account/ht', $data);*/
	}
}