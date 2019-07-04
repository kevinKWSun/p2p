<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Investors extends Baseaccount {
	public function __construct() {
		parent::__construct();
		$this->load->library('pagination');
		$this->load->model(array('account/info_model', 'member/member_model'));
	}
	public function index(){
		$investors = $this->info_model->get_investors(QUID);
		$data['counts'] = count($investors);
		$investor_uids = array();
		if(!empty($investors)) {
			foreach($investors as $v){
				array_push($investor_uids, $v['id']);
			}
			$data['money'] = $this->info_model->get_borrow_investors($investor_uids);
		} else {
			$investor_uids = '';
			$data['money'] = 0;
		}
		
		////分页
		$current_page = $this->uri->segment(3) ? $this->uri->segment(3) : 1;
        if($current_page > 0){
            $current_page = $current_page - 1;
        }else if($current_page < 0){
            $current_page = 0;
        }
		$tel = $this->input->get('tel', TRUE);
		if($tel){
			$minfo = $this->member_model->get_member_byusername($tel);
			$investor_uids = array($minfo['id']);
		}
		$time1 = $this->input->get('time1', TRUE);
		$time2 = $this->input->get('time2', TRUE);
		if($time1 && $time2){
			$time = array(strtotime($time1), strtotime($time2.'23:59:59'));
		}else{
			$time = '';
		}
		$per_page = 8;
        $offset = $current_page;
        $config['base_url'] = base_url('investors/index');
        $config['total_rows'] = $this->info_model->get_borrow_investors_lists_nums($investor_uids, $time, QUID);
        $config['per_page'] = $per_page;
		$config['page_query_string'] = FALSE;
		$config['first_link'] = '首页'; // 第一页显示   
		$config['last_link'] = '末页'; // 最后一页显示   
		$config['next_link'] = '下一页'; // 下一页显示   
		$config['prev_link'] = '上一页'; // 上一页显示   
		$config['cur_tag_open'] = ' <span class="layui-laypage-curr"><em class="layui-laypage-em"></em><em>'; // 当前页开始样式   
		$config['cur_tag_close'] = '</em></span>';   
		$config['num_links'] = 10;
		$config['uri_segment'] = 3;
		$config['use_page_numbers'] = TRUE;
		$config['reuse_query_string'] = TRUE;
        $this->pagination->initialize($config); 
        $data['totals'] = $config['total_rows'];
        $data['page'] = $this->pagination->create_links();
        $data['p'] = $current_page;
        $data['investor'] = $this->info_model->get_borrow_investors_lists($per_page, $offset * $per_page, $investor_uids, $time, QUID);
		////
		$data['time1'] = $time1;
		$data['time2'] = $time2;
		$data['tel'] = $tel;
		$this->load->view('account/investors_v1', $data);
	}
	public function lists(){
		////分页
		$current_page = $this->uri->segment(3) ? $this->uri->segment(3) : 1;
        if($current_page > 0){
            $current_page = $current_page - 1;
        }else if($current_page < 0){
            $current_page = 0;
        }
		$per_page = 8;
        $offset = $current_page;
        $config['base_url'] = base_url('investors/lists');
        $config['total_rows'] = $this->info_model->get_borrow_investors_list_num(QUID);
        $config['per_page'] = $per_page;
		$config['page_query_string'] = FALSE;
		$config['first_link'] = '首页'; // 第一页显示   
		$config['last_link'] = '末页'; // 最后一页显示   
		$config['next_link'] = '下一页'; // 下一页显示   
		$config['prev_link'] = '上一页'; // 上一页显示   
		$config['cur_tag_open'] = ' <span class="layui-laypage-curr"><em class="layui-laypage-em"></em><em>'; // 当前页开始样式   
		$config['cur_tag_close'] = '</em></span>';   
		$config['num_links'] = 10;
		$config['uri_segment'] = 3;
		$config['use_page_numbers'] = TRUE;
		$config['reuse_query_string'] = TRUE;
        $this->pagination->initialize($config); 
        $data['totals'] = $config['total_rows'];
        $data['page'] = $this->pagination->create_links();
        $data['p'] = $current_page;
        $data['investor'] = $this->info_model->get_borrow_investors_list($per_page, $offset * $per_page, QUID);

		$this->load->view('account/investors_list_v1', $data);
	}
}