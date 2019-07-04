<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/** 积分记录 */
class Score extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->library('pagination');
		$this->load->model('score/score_model');
		//$this->load->helper('url');
	}
	/** 积分列表 */
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
        $config['base_url'] = base_url('score/lists');
        $config['total_rows'] = $this->score_model->get_score_related_num($where);
		
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
        $score = $this->score_model->get_score_related($per_page, $offset * $per_page, $where);
        $data['score'] = $score;
		$this->load->view('score/lists', $data);
	}
}