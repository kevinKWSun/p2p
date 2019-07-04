<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Recharge extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->library('pagination');
		$this->load->model('recharge/recharge_model');
		//$this->load->helper('url');
	}
	//充值列表
	public function index(){
		$data = array();
		$where = array();
		$search = $this->input->get(null, true);
		$where['members_payonline.type'] = 1;
		if(!empty($search['skey'])) {
			$data['skey'] = trim(trim($search['skey']), '\t');
			$where['skey'] = $data['skey'];
		}
		
	
		$per_page = 100;
		$current_page = $this->uri->segment(3) ? $this->uri->segment(3) - 1 : 0;
        $offset = $current_page;
        $config['base_url'] = base_url('recharge/index');
        $config['total_rows'] = $this->recharge_model->get_payonline_related_num($where);
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
        $recharge = $this->recharge_model->get_payonline_related($per_page, $offset * $per_page, $where);
        $data['recharge'] = $recharge;
		$this->load->view('recharge/index', $data);
	}
	
	//提现列表
	public function withdraw(){
		$data = array();
		$where = array();
		$search = $this->input->get(null, true);
		$where['members_payonline.type'] = 2;
		if(!empty($search['skey'])) {
			$data['skey'] = trim(trim($search['skey']), '\t');
			$where['skey'] = $data['skey'];
		}
		
		
		$per_page = 100;
		$current_page = $this->uri->segment(3) ? $this->uri->segment(3) - 1 : 0;
        $offset = $current_page;
        $config['base_url'] = base_url('recharge/withdraw');
        $config['total_rows'] = $this->recharge_model->get_payonline_related_num($where);
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
        $withdraw = $this->recharge_model->get_payonline_related($per_page, $offset * $per_page, $where);
        $data['withdraw'] = $withdraw;
		$this->load->view('recharge/withdraw', $data);
	}
}