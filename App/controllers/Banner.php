<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Banner extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->library('pagination');
		$this->load->model(array('banner/banner_model'));
		$this->cate = array(
			'1' => '手机端'
		);
	}
	/** 列表 */
	public function index() {
		$data = array();
		$where = array();
		$search = $this->input->get(null, true);
		if(isset($search) && !empty($search['name'])) {
			$data['name'] = trim(trim($search['name']), '\t');
			$where['name'] = $data['name'];
		}
		
		$current_page  = intval($this->uri->segment(3));
		$current_page = $current_page > 0 ? $current_page - 1 : 0;
		$per_page = 10;
        $offset = $current_page;
        $config['base_url'] = base_url('banner/index');
        $config['total_rows'] = $this->banner_model->get_banner_lists_nums($where);
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
		$data['skip_page'] = $this->pagination->create_skip_link();
        $data['p'] = $current_page;
        $data['banner'] = $this->banner_model->get_banner_lists($per_page, $offset * $per_page, $where);
		$data['cate'] = $this->cate;
		$this->load->view('banner/index', $data);
	}
	
	/** 新增 */
	public function add() {
		if(IS_POST) {
			$post = $this->input->post(null, true);
			$data['name'] = substr($post['name'], 0, 20);
			$data['info'] = substr($post['info'], 0, 50);
			$data['img'] = substr($post['img'], 0, 255);
			$data['sort'] = intval($post['sort']);
			$data['type'] = intval($post['type']);
			if($this->banner_model->add($data)) {
				$this->success('操作成功', '/banner.html');
			} else {
				$this->error('操作失败');
			}
		}
		$data = array();
		$data['cate'] = $this->cate;
		$this->load->view('banner/add', $data);
	}
	
	/** 编辑 */
	public function modify() {
		if(IS_POST) {
			$post = $this->input->post(null, true);
			$id = intval($post['id']);
			$banner = $this->banner_model->get_banner_byid($id);
			$banner['name'] = substr($post['name'], 0, 20);
			$banner['info'] = substr($post['info'], 0, 50);
			$banner['img'] = substr($post['img'], 0, 255);
			$banner['sort'] = intval($post['sort']);
			$banner['type'] = intval($post['type']);
			if($this->banner_model->modify($banner)) {
				$current_page = intval($post['current_page']);
				$url = '/banner/index' . ($current_page ? "/{$current_page}" : '') . '.html';
				$this->success('操作成功', $url);
			} else {
				$this->error('操作失败');
			}
		}
		$data = array();
		$data['current_page'] = $this->uri->segment(4);
		$id = $this->uri->segment(3);
		$data['cate'] = $this->cate;
		$data['banner'] = $this->banner_model->get_banner_byid($id);
		$this->load->view('banner/modify', $data);
	}
	/** 删除 */
	public function del() {
		if(IS_POST) {
			$id = intval($this->uri->segment(3));
			$banner = $this->banner_model->get_banner_byid($id);
			$banner['del'] = -1;
			if($this->banner_model->modify($banner)) {
				$current_page = intval($post['current_page']);
				$url = '/banner/index' . ($current_page ? "/{$current_page}" : '') . '.html';
				$this->success('操作成功', $url);
			} else {
				$this->error('操作失败');
			}
		}
	}
}