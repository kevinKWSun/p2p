<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Group extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->library('pagination');
		$this->load->model(array('user/group_model','user/rule_model', 'auth/auth_model'));
		$this->load->helper('url');
	}
	//搜索
	public function solor(){
		$keywords = $this->input->post_get('keywords', TRUE);
		if($this->input->post()){
			if(! $keywords){
				$info['state'] = 0;
				$info['message'] = '请输入关键字!';
				$this->output
				    ->set_content_type('application/json', 'utf-8')
				    ->set_output(json_encode($info))
					->_display();
				    exit;
			}else{
				$info['state'] = 1;
				$info['message'] = urldecode($keywords);
				$this->output
				    ->set_content_type('application/json', 'utf-8')
				    ->set_output(json_encode($info))
					->_display();
				    exit;
			}
		}
		$keywords = urldecode($this->input->get('query', TRUE));
		$current_page = $this->uri->segment(3) ? $this->uri->segment(3) : 1;
        if($current_page > 0){
            $current_page = $current_page - 1;
        }else if($current_page < 0){
            $current_page = 0;
        }
        if(! IS_ROOT){
			$admin_id = UID;
		}else{
			$admin_id = '';
		}
		$per_page = 20;
        $offset = $current_page;
        $config['base_url'] = base_url('group/solor/');
        $config['total_rows'] = $this->group_model->get_group_num($keywords, $admin_id);
        $config['per_page'] = $per_page;
		$config['page_query_string'] = FALSE;
		$config['first_link'] = '首页'; // 第一页显示   
		$config['last_link'] = '末页'; // 最后一页显示   
		$config['next_link'] = '下一页'; // 下一页显示   
		$config['prev_link'] = '上一页'; // 上一页显示   
		$config['cur_tag_open'] = ' <span class="current">'; // 当前页开始样式   
		$config['cur_tag_close'] = '</span>';   
		$config['num_links'] = 10;
		$config['uri_segment'] = 4;
		$config['use_page_numbers'] = TRUE;
		$config['reuse_query_string'] = TRUE;
        $this->pagination->initialize($config); 
        $data['totals'] = $config['total_rows'];
        $data['page'] = $this->pagination->create_links();
        $data['p'] = $current_page;
        $data['group'] = $this->group_model->get_group($per_page, $offset * $per_page, $keywords, $admin_id);
		$this->load->view('user/group', $data);
	}
	//列表
	public function index(){
        $current_page = $this->uri->segment(3) ? $this->uri->segment(3) : 1;
        if($current_page > 0){
            $current_page = $current_page - 1;
        }else if($current_page < 0){
            $current_page = 0;
        }
        if(! IS_ROOT){
			$admin_id = UID;
		}else{
			$admin_id = '';
		}
		$per_page = 20;
        $offset = $current_page;
        $config['base_url'] = base_url('group/index');
        $config['total_rows'] = $this->group_model->get_group_num('', $admin_id);
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
        $data['group'] = $this->group_model->get_group($per_page, $offset * $per_page, '', $admin_id);
		$this->load->view('user/group', $data);
	}
	//增加
	public function add(){
		if($this->input->post()){
			$ids = $this->input->post_get('ids', TRUE);
			$rules = substr($ids, 0, -1);
			$rules = $rules ? $rules : '无';
			$name = trim($this->input->post_get('name', TRUE));
			if(! $name){
				$info['state'] = 0;
				$info['message'] = '权限名称不能为空!';
				$this->output
				    ->set_content_type('application/json', 'utf-8')
				    ->set_output(json_encode($info))
					->_display();
				    exit;
			}
			if($this->group_model->get_group_bytitle($name) > 0){
				$info['state'] = 0;
				$info['message'] = '权限名称已存在!';
				$this->output
				    ->set_content_type('application/json', 'utf-8')
				    ->set_output(json_encode($info))
					->_display();
				    exit;
			}
			$data = array(
				'title' => $name,
				'rules' => $rules,
				'admin_id' => UID
			);
			if($this->group_model->add_group($data)){
				$info['state'] = 1;
				$info['message'] = '增加成功!';
				$info['url'] = '/group.html';
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
		$group['rules'] = $this->_rules();
		$this->load->view('user/groupadd', $group);
	}
	//编辑
	public function edit(){
		if($this->input->post()){
			$ids = $this->input->post_get('ids', TRUE);
			$rules = substr($ids, 0, -1);
			$rules = $rules ? $rules : 'NULL';
			$name = trim($this->input->post_get('name', TRUE));
			$id = $this->input->post_get('id', TRUE);
			if(! $name){
				$info['state'] = 0;
				$info['message'] = '权限名称不能为空!';
				$this->output
				    ->set_content_type('application/json', 'utf-8')
				    ->set_output(json_encode($info))
					->_display();
				    exit;
			}
			$u = $this->group_model->get_group_byid($id);
			if(! IS_ROOT){
				if($u['admin_id'] != UID){
					$info['state'] = 0;
					$info['message'] = '非法操作!';
					$this->output
					    ->set_content_type('application/json', 'utf-8')
					    ->set_output(json_encode($info))
						->_display();
					    exit;
				}
			}
			$data = array(
				'title' => $name,
				'rules' => $rules
			);
			if($u['title'] != $name && $this->group_model->get_group_bytitle($name) > 0){
				$info['state'] = 0;
				$info['message'] = '权限名称已存在!';
				$this->output
				    ->set_content_type('application/json', 'utf-8')
				    ->set_output(json_encode($info))
					->_display();
				    exit;
			}
			if($this->group_model->modify_group($data, $id)){
				$info['state'] = 1;
				$info['message'] = '编辑成功!';
				$info['url'] = '/group.html';
				$this->output
				    ->set_content_type('application/json', 'utf-8')
				    ->set_output(json_encode($info))
					->_display();
				    exit;
			}else{
				$info['state'] = 0;
				$info['message'] = '编辑失败,刷新后重试!';
				$this->output
				    ->set_content_type('application/json', 'utf-8')
				    ->set_output(json_encode($info))
					->_display();
				    exit;
			}
		}
		$id = $this->uri->segment(3);
		$group['d'] = $this->group_model->get_group_byid($id);
		$group['rules'] = $this->_rules();
		$this->load->view('user/groupedit', $group);
	}
	//锁定
	public function lock(){
		if($this->input->post()){
			$ids = $this->input->post_get('ids', TRUE);
			$ids = explode(',', substr($ids, 0, -1));
			if(! $ids){
				$info['state'] = 0;
				$info['message'] = '数据有误!';
				$this->output
				    ->set_content_type('application/json', 'utf-8')
				    ->set_output(json_encode($info))
					->_display();
			    exit;
			}else{
				$data['status'] = 2;
				if(! IS_ROOT){
					$admin_id = UID;
				}else{
					$admin_id = '';
				}
				if($this->group_model->get_group_lock($data, $ids, $admin_id)){
					$info['state'] = 1;
					$info['message'] = '操作成功!';
					$this->output
					    ->set_content_type('application/json', 'utf-8')
					    ->set_output(json_encode($info))
						->_display();
				    exit;
				}else{
					$info['state'] = 0;
					$info['message'] = '操作失败,请刷新后重试!';
					$this->output
					    ->set_content_type('application/json', 'utf-8')
					    ->set_output(json_encode($info))
						->_display();
				    exit;
				}
			}
		}
	}
	//解锁
	public function unlock(){
		if($this->input->post()){
			$ids = $this->input->post_get('ids', TRUE);
			$ids = explode(',', substr($ids, 0, -1));
			if(! $ids){
				$info['state'] = 0;
				$info['message'] = '数据有误!';
				$this->output
				    ->set_content_type('application/json', 'utf-8')
				    ->set_output(json_encode($info))
					->_display();
			    exit;
			}else{
				$data['status'] = 1;
				if(! IS_ROOT){
					$admin_id = UID;
				}else{
					$admin_id = '';
				}
				if($this->group_model->get_group_lock($data, $ids, $admin_id)){
					$info['state'] = 1;
					$info['message'] = '操作成功!';
					$this->output
					    ->set_content_type('application/json', 'utf-8')
					    ->set_output(json_encode($info))
						->_display();
				    exit;
				}else{
					$info['state'] = 0;
					$info['message'] = '操作失败,请刷新后重试!';
					$this->output
					    ->set_content_type('application/json', 'utf-8')
					    ->set_output(json_encode($info))
						->_display();
				    exit;
				}
			}
		}
	}
	private function _rules(){
		$rules = $this->rule_model->get_rules();
		$rule = get_sons_arr($rules);
		return $rule;
	}
}