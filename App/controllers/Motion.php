<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 运营报告
 */
class Motion extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->library('pagination');
		$this->load->model('motion/motion_model');
		//$this->load->helper('url');
	}
	
	/** 运营报告数据列表 */
	public function lists() {
		$data = array();
		$where = array();
		$search = $this->input->get(null, true);
		if(!empty($search['skey'])) {
			$data['skey'] = trim(trim($search['skey']), '\t');
			$where['skey'] = $data['skey'];
		}
		$per_page = 10;
		$current_page = $this->uri->segment(3) ? $this->uri->segment(3) - 1 : 0;
        $offset = $current_page;
        $config['base_url'] = base_url('motion/lists');
        $config['total_rows'] = $this->motion_model->get_motion_lists_nums($where);
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
        $motion = $this->motion_model->get_motion_lists($per_page, $offset * $per_page, $where);
        $data['motion'] = $motion;
		$this->load->view('motion/lists', $data);
	}
	
	/** 新增 */
	public function add() {
		if(IS_POST) {
			$post = $this->input->post(null, true);
			$post['addtime'] = time();
			$post['adminid'] = UID; 
			$post['date'] = strtotime($post['date'] . '-01 00:00:00');
			$res = $this->motion_model->get_motion_bydate($post['date']);
			if(!empty($res)) {
				$this->error('当月运营报告已存在, 不能重复添加');
			}
			if($this->motion_model->add_motion($post)) {
				$this->success('操作成功', '/motion/lists.html');
			} else {
				$this->error('操作失败');
			}
		}
		$this->load->view('motion/add');
	}
	
	/** 编辑 */
	public function modify() {
		if(IS_POST) {
			$post = $this->input->post(null, true);
			$current_page = intval($post['current_page']);
			
			$motion = $this->motion_model->get_motion_byid($post['id']);
			foreach($motion as $k=>$v) {
				if(isset($post[$k])) {
					$motion[$k] = $post[$k];
				}
			}
			if($this->motion_model->modify_motion($motion)) {
				$this->success('操作成功', '/motion/' . ($current_page ? "/{$current_page}" : '') . 'lists.html');
			} else {
				$this->error('操作失败');
			}
		}
		$id = $this->uri->segment(3);
		$data['current_page'] = $this->uri->segment(4);
		if(empty($id)) {
			exit('数据错误');
		}
		$data['motion'] = $this->motion_model->get_motion_byid($id);
		$this->load->view('motion/modify', $data);
	}
	
	/** 查看 */
	public function show() {
		$id = $this->uri->segment(3);
		$data['motion'] = $this->motion_model->get_motion_byid($id);
		$this->load->view('motion/show', $data);
	}
}