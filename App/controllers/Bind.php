<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 *	变更银行卡审核流程
 */
class Bind extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->library('pagination');
		$this->load->model(array('bind/bind_model'));
	}
	
	public function lists() {
		$data = array();
		$where = array();
		$search = $this->input->get(null, true);
		if(!empty($search['username'])) {
			$data['username'] = trim(trim($search['username']), '\t');
			$where['username'] = $data['username'];
		}
		
		$current_page  = intval($this->uri->segment(3));
		$current_page = $current_page > 0 ? $current_page - 1 : 0;
		$per_page = 10;
        $offset = $current_page;
        $config['base_url'] = base_url('bind/lists');
        $config['total_rows'] = $this->bind_model->get_bind_num($where);
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
        $data['bind'] = $this->bind_model->get_bind_lists($per_page, $offset * $per_page, $where);
		$this->load->view('bind/lists', $data);
	}
	
	/** 审核 */
	public function audit() {
		if(IS_POST) {
			$id = $this->uri->segment(3);
			$status = $this->uri->segment(4);
			if(!$id || !$status) {
				exit();
			}
			//获取一条数据
			$bind = $this->bind_model->get_bind_byid($id);
			if(in_array($status, array(1, 2))) {
				$bind['status'] = $status;
				$bind['auditor'] = UID;
				$bind['auditor_uptime'] = time();
			}
			
			if($status == 2) {
				$this->load->model('member/member_model');
				$meminfo = $this->member_model->get_member_info_byuid($bind['uid']);
				//send_sms($meminfo['phone'], '你上传的图片不符合银行要求，请重新上传', '');
				send_sms($meminfo['phone'], '关于您提交的更换银行卡申请，未能通过银行审核，暂时不能换卡；请您重新上传身份验证照片，感谢您的配合', '');
			}
			if($status == 1) {
				$this->load->model('member/member_model');
				$meminfo = $this->member_model->get_member_info_byuid($bind['uid']);
				send_sms($meminfo['phone'], '关于您提交的更换银行卡申请，已审核通过，请到会员中心修改银行卡', '');
			}
			if($this->bind_model->modify_bind($bind)) {
				$this->success('操作成功'); 
			} else {
				$this->error('操作失败，请联系管理员');
			}
		}
	}
}