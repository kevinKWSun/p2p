<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class News extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->library('pagination');
		$this->load->model('news/news_model');
		//$this->load->helper('url');
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
        $config['base_url'] = base_url('news/index');
        $config['total_rows'] = $this->news_model->get_news_num();
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
        $news = $this->news_model->get_news($per_page, $offset * $per_page);
        $data['news'] = $news;
		$this->load->view('news/news', $data);
	}
	public function cate(){
		$current_page = $this->uri->segment(3) ? $this->uri->segment(3) : 1;
        if($current_page > 0){
            $current_page = $current_page - 1;
        }else if($current_page < 0){
            $current_page = 0;
        }
		$per_page = 8;
        $offset = $current_page;
        $config['base_url'] = base_url('news/cate');
        $config['total_rows'] = $this->news_model->get_newscate_num();
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
        $cate = $this->news_model->get_newscate($per_page, $offset * $per_page);
        $data['cate'] = $cate;
		$this->load->view('news/cate', $data);
	}
	public function cadd(){
		if($p = $this->input->post(NULL, TRUE)){
			$data['type_name'] = $p['lname'];
			if(! $data['type_name']){
				$info['state'] = 0;
				$info['message'] = '分类名称不能为空!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			if($this->news_model->get_newscate_one('', $data['type_name'])){
				$info['state'] = 0;
				$info['message'] = '分类已存在!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			$data['add_time'] = time();
			if($this->news_model->add_newscate($data)){
				$info['state'] = 1;
				$info['message'] = '操作成功!';
				$info['url'] = '/news/cate.html';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}else{
				$info['state'] = 0;
				$info['message'] = '操作失败!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
		}
		$this->load->view('news/cateadd');
	}
	public function cedit(){
		if($p = $this->input->post(NULL, TRUE)){
			$data['type_name'] = $p['lname'];
			$id = $p['id'];
			if(! $id){
				$info['state'] = 0;
				$info['message'] = '数据有误!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			if(! $data['type_name']){
				$info['state'] = 0;
				$info['message'] = '分类名称不能为空!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			$c = $this->news_model->get_newscate_one('', $data['type_name']);
			if(isset($c['id']) && $c['id'] != $id){
				$info['state'] = 0;
				$info['message'] = '分类已存在!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			if($this->news_model->up_newscate($data, $id)){
				$info['state'] = 1;
				$info['message'] = '操作成功!';
				$info['url'] = '/news/cate.html';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}else{
				$info['state'] = 0;
				$info['message'] = '操作失败!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
		}
		$id = $this->uri->segment(3);
		$d = $this->news_model->get_newscate_one($id);
		$this->load->view('news/cateedit', $d);
	}
	public function add(){
		if($p = $this->input->post(NULL, TRUE)) {
			$data['cid'] = $p['cid'];
			$data['title'] = $p['title'];
			$data['content'] = $this->input->post('editorValue');
			$data['img'] = $p['img'];
			$data['year'] = $p['year'];
			if(! $data['cid']){
				$info['state'] = 0;
				$info['message'] = '请选择分类名称!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			if(! $data['title']){
				$info['state'] = 0;
				$info['message'] = '名称不能为空!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			$data['addtime'] = time();
			$data['year'] = !empty($data['year']) ? $data['year'] : date('Y', time());
			$data['admin_id'] = UID;
			if($this->news_model->add_news($data)){
				$info['state'] = 1;
				$info['message'] = '操作成功!';
				$info['url'] = '/news.html';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}else{
				$info['state'] = 0;
				$info['message'] = '操作失败!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
		}
		$d['cate'] = $this->news_model->get_newscates();
		$this->load->view('news/newsadd', $d);
	}
	public function edit(){
		$id = $this->uri->segment(3);
		$d = $this->news_model->get_news_one($id);
		if($p = $this->input->post(NULL, TRUE)){
			$data['cid'] = $p['cid'];
			$data['title'] = $p['title'];
			$data['content'] = $this->input->post('editorValue');
			//p($data['content']);
			$id = $p['id'];
			$data['img'] = $p['img'];
			if(! $id){
				$info['state'] = 0;
				$info['message'] = '数据有误!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			if(! $data['cid']){
				$info['state'] = 0;
				$info['message'] = '请选择分类名称!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			if(! $data['title']){
				$info['state'] = 0;
				$info['message'] = '名称不能为空!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			$data['admin_id'] = UID;
			if($this->news_model->up_news($data, $id)){
				$info['state'] = 1;
				$info['message'] = '操作成功!';
				$info['url'] = '/news.html';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}else{
				$info['state'] = 0;
				$info['message'] = '操作失败!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
		}
		$d['cate'] = $this->news_model->get_newscates();
		$this->load->view('news/newsedit', $d);
	}
	public function show(){
		$id = $this->uri->segment(3);
		$d = $this->news_model->get_news_one($id);
		$d['cate'] = $this->news_model->get_newscates();
		$this->load->view('news/show', $d);
	}
	
	/** 删除 */
	public function del() {
		$id = $this->uri->segment(3);
		$data = $this->news_model->get_news_one($id);
		$data['del'] = '-1';
		if($this->news_model->up_news($data, $data['id'])) {
			$this->success('删除成功');
		} else {
			$this->error('删除失败');
		}
	}
}