<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Rule extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->library('pagination');
		$this->load->model('user/rule_model');
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
		$per_page = 20;
        $offset = $current_page;
        $config['base_url'] = base_url('rule/solor/');
        $config['total_rows'] = $this->rule_model->get_rule_num($keywords);
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
        $rule = $this->rule_model->get_rule($per_page, $offset * $per_page, $keywords);
        foreach($rule as $k => $v){
        	$rule[$k]['child'] = $this->_rules($v['id']);
        }
        $data['rule'] = $rule;
		$this->load->view('user/rule', $data);
	}
	//列表
	public function index(){
        $current_page = $this->uri->segment(3) ? $this->uri->segment(3) : 1;
        if($current_page > 0){
            $current_page = $current_page - 1;
        }else if($current_page < 0){
            $current_page = 0;
        }
		$per_page = 20;
        $offset = $current_page;
        $config['base_url'] = base_url('rule/index');
        $config['total_rows'] = $this->rule_model->get_rule_num();
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
        $rule = $this->rule_model->get_rule($per_page, $offset * $per_page, '');
        foreach($rule as $k => $v){
        	$rule[$k]['child'] = $this->_rules($v['id']);
        }
        $data['rule'] = $rule;
		$this->load->view('user/rule', $data);
	}
	//增加
	public function add(){
		if($this->input->post()){
			$name = trim($this->input->post_get('name', TRUE));
			$title = trim($this->input->post_get('title', TRUE));
			$gids = trim($this->input->post_get('gids', TRUE));
			$type = intval($this->input->post_get('type', TRUE));
			if(! $title){
				$info['state'] = 0;
				$info['message'] = '必填,规则名称!';
				$this->output
				    ->set_content_type('application/json', 'utf-8')
				    ->set_output(json_encode($info))
					->_display();
				    exit;
			}
			if($this->rule_model->get_rule_bytitle($title, $gids) > 0){
				$info['state'] = 0;
				$info['message'] = '规则名称已存在!';
				$this->output
				    ->set_content_type('application/json', 'utf-8')
				    ->set_output(json_encode($info))
					->_display();
				    exit;
			}
			$data = array(
				'name' => $name,
				'title' => $title,
				'pid' => $gids,
				'type' => $type ? $type : 2
			);
			if($this->rule_model->add_rule($data)){
				$info['state'] = 1;
				$info['message'] = '增加成功!';
				$info['url'] = '/rule.html';
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
		$rules['rule'] = $this->_rules();
		$this->load->view('user/ruleadd', $rules);
	}
	//编辑
	public function edit(){
		if($this->input->post()){
			$id = trim($this->input->post_get('d', TRUE));
			$title = trim($this->input->post_get('title', TRUE));
			$name = trim($this->input->post_get('name', TRUE));
			$gids = trim($this->input->post_get('gids', TRUE));
			$type = intval($this->input->post_get('type', TRUE));
			$status = intval($this->input->post_get('status', TRUE));
			if(! $title){
				$info['state'] = 0;
				$info['message'] = '必填,规则名称!';
				$this->output
				    ->set_content_type('application/json', 'utf-8')
				    ->set_output(json_encode($info))
					->_display();
				    exit;
			}
			$u = $this->rule_model->get_rule_byid($id);
			if($u['title'] != $title && $this->rule_model->get_rule_bytitle($title, $gids) > 0){
				$info['state'] = 0;
				$info['message'] = '规则名称已存在!';
				$this->output
				    ->set_content_type('application/json', 'utf-8')
				    ->set_output(json_encode($info))
					->_display();
				    exit;
			}
			$data = array(
				'name' => $name,
				'title' => $title,
				'pid' => $gids,
				'type' => $type ? $type : 2,
				'status' => $status ? $status : 2
			);
			if($this->rule_model->modify_rule($data, $id)){
				$info['state'] = 1;
				$info['message'] = '编辑成功!';
				$info['url'] = '/rule.html';
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
		$data = $this->rule_model->get_rule_byid($id);
		$data['rule'] = $this->_rules();
		$this->load->view('user/ruleedit', $data);
	}
	//批量增加
	public function addall(){
		if($this->input->post()){
			$name = $this->input->post_get('name', TRUE);
			$name = explode(',', substr($name, 0, -1));
			$title = $this->input->post_get('title', TRUE);
			$title = explode(',', substr($title, 0, -1));
			$gids = trim($this->input->post_get('gids', TRUE));
			$type = trim($this->input->post_get('type', TRUE));
			if(count($title) > 0){
				foreach($title as $k => $v){
					if(! $v){
						$info['state'] = 0;
						$info['message'] = '必填,规则名称!';
						$this->output
						    ->set_content_type('application/json', 'utf-8')
						    ->set_output(json_encode($info))
							->_display();
						    exit;
					}
					if($this->rule_model->get_rule_bytitle($v, $gids) > 0){
						$info['state'] = 0;
						$info['message'] = '规则名称已存在!';
						$this->output
						    ->set_content_type('application/json', 'utf-8')
						    ->set_output(json_encode($info))
							->_display();
						    exit;
					}
					$data[$k] = array(
						'name' => $name[$k],
						'title' => $v,
						'pid' => $gids,
						'type' => $type ? $type : 2
					);
				}
			}else{
				$info['state'] = 0;
				$info['message'] = '请先完善必填项!';
				$this->output
				    ->set_content_type('application/json', 'utf-8')
				    ->set_output(json_encode($info))
					->_display();
				    exit;
			}
			if($this->rule_model->addall_rule($data)){
				$info['state'] = 1;
				$info['message'] = '增加成功!';
				$info['url'] = '/rule.html';
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
		$rules['id'] = $this->uri->segment(3) ? $this->uri->segment(3) : 0;
		$rules['rule'] = $this->_rules();
		$this->load->view('user/addall', $rules);
	}
	private function _rules($pid = 0){
		$rules = $this->rule_model->get_rules();
		$rule = get_son_arr($rules, '', $pid);
		return $rule;
	}
}