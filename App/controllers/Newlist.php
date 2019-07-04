<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Newlist extends Baseaccounts {
	public function __construct() {
		parent::__construct();
		$this->load->library('pagination');
		$this->load->model('member/member_model');
		//$this->load->helper('url');
	}
	//新闻列表
	public function about(){
		$id = $this->uri->segment(3);
		switch($id){
			case 1:
				$this->load->view('index/about');
			break;
			case 2:
				$this->load->view('index/partner');
			break;
			case 3:
				$this->load->view('index/help');
			break;
			case 4:
				$this->load->view('index/join');
			break;
			case 5:
				$this->load->view('index/relation');
			break;
			default :
				$this->load->view('index/about');
			break;
		}
	}
	public function index(){
		$current_page = $this->uri->segment(3) ? $this->uri->segment(3) : 1;
        if($current_page > 0){
            $current_page = $current_page - 1;
        }else if($current_page < 0){
            $current_page = 0;
        }
		$per_page = 8;
        $offset = $current_page;
        $config['base_url'] = base_url('newlist/index');
        $config['total_rows'] = $this->member_model->get_news_num();
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
        $news = $this->member_model->get_news($per_page, $offset * $per_page);
        $data['news'] = $news;
		$this->load->view('index/newlist', $data);
	}
	public function news(){
		$id = $this->uri->segment(3);
		$d = $this->member_model->get_news_one($id);
		$this->load->view('index/news', $d);
	}
}