<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Invite extends Baseaccount {
	public function __construct() {
		parent::__construct();
		$this->load->library('pagination');
		$this->load->model(array('borrow/borrow_model','member/member_model'));
		//$this->load->helper('url');
	}
	//首页
	public function index(){
		$one = strtotime(date('Y-m-d'));
		$tow = strtotime(date('Y-m-d') . ' 23:59:59');
		$data['day'] = $this->borrow_model->get_moneys_hk('', QUID, $one, $tow);
		$one = strtotime(date('Y-m-01'));
		$tow = strtotime(date('Y-m-t') . ' 23:59:59');
		$data['month'] = $this->borrow_model->get_moneys_hk('', QUID, $one, $tow);
		$current_page = $this->uri->segment(3) ? $this->uri->segment(3) : 1;
		$status = $this->input->get('query') ? $this->input->get('query') : 4;//4,2,5
        if($current_page > 0){
            $current_page = $current_page - 1;
        }else if($current_page < 0){
            $current_page = 0;
        }
		$per_page = 10;
        $offset = $current_page;
        $config['base_url'] = base_url('invite/index');
        $config['total_rows'] = $this->borrow_model->get_borrows_num($status, QUID);
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
        $borrow = $this->borrow_model->get_borrows($per_page, $offset * $per_page, $status, QUID);
        $data['borrow'] = $borrow;
		$this->load->view('account/invete', $data);
	}
}