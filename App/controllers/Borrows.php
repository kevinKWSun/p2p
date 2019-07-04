<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Borrows extends Baseaccount {
	public function __construct() {
		parent::__construct();
		$this->load->library('pagination');
		$this->load->model('member/member_model');
		$this->load->helper(array('url', 'common'));
	}
	//首页
	public function index(){
		$current_page = $this->uri->segment(3) ? $this->uri->segment(3) : 1;
        if($current_page > 0){
            $current_page = $current_page - 1;
        }else if($current_page < 0){
            $current_page = 0;
        }
		//$status = $this->input->get('query') ? $this->input->get('query') : 4;
		$per_page = 8;
        $offset = $current_page;
        $config['base_url'] = base_url('borrow/index');
        $config['total_rows'] = $this->member_model->get_members_moneylog_num(QUID);
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
        $moneylog = $this->member_model->get_members_moneylog($per_page, $offset * $per_page, QUID);
        $data['moneylog'] = $moneylog;
		$data['in'] = $this->member_model->get_members_moneylogs(QUID, array(1,5,7,8));
		$data['out'] = $this->member_model->get_members_moneylogs(QUID, array(2,3,4,6));
		$this->load->view('account/borrows', $data);
	}
}