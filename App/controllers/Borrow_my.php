<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Borrow_my extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->library('pagination');
		$this->load->model(array('member/member_model'));
		$this->load->helper(array('url', 'common'));
	}
	//借款列表
	public function index(){
		
		$current_page = $this->uri->segment(3) ? $this->uri->segment(3) : 1;
        if($current_page > 0){
            $current_page = $current_page - 1;
        }else if($current_page < 0){
            $current_page = 0;
        }
		$per_page = 8;
        $offset = $current_page;
        $config['base_url'] = base_url('borrow_my/index');
        $config['total_rows'] = $this->member_model->get_borrow_my_nums();
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
        $borrow = $this->member_model->get_borrow_my($per_page, $offset * $per_page);
        $data['borrow'] = $borrow;
		$this->load->view('borrow/borrow_my', $data);
	}
	public function add(){
		if($p = $this->input->post(NULL, TRUE)){
			$d['type'] = $p['type'];
			$data['name'] = $p['name'];
			$d['jktpe'] = $p['jktpe'];
			$data['money'] = $p['money'];
			$d['day'] = $p['day'];
			$d['lx'] = $p['lx'];
			$d['yt'] = $p['yt'];
			$d['member'] = $p['member'];
			$d['car'] = $p['car'];
			$data['add_time'] = strtotime($p['add_time']);
			$data['info'] = serialize($d);
			$data['admin_id'] = UID;
			if($this->member_model->add_borrow($data)){
				$info['state'] = 1;
				$info['message'] = '增加成功!';
				$info['url'] = '/borrow_my.html';
				$this->output
				    ->set_content_type('application/json', 'utf-8')
				    ->set_output(json_encode($info))
					->_display();
				    exit;
			}else{
				$info['state'] = 0;
				$info['message'] = '增加失败,刷新后重试!';
				$this->output
				    ->set_content_type('application/json', 'utf-8')
				    ->set_output(json_encode($info))
					->_display();
				    exit;
			}
		}
		$this->load->view('borrow/myadd');
	}
	public function show(){
		$current_page = $this->uri->segment(3);
		$data['one'] = $this->member_model->get_borrow_my_byid($current_page);
		$this->load->view('borrow/show', $data);
	}
}