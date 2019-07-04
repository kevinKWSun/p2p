<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Trade extends Baseaccount {
	public function __construct() {
		parent::__construct();
		$this->load->library('pagination');
		$this->load->model('account/info_model');
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
		$type = $this->input->get('type', TRUE) ? $this->input->get('type') : 0;
		$time1 = $this->input->get('time1', TRUE);
		$time2 = $this->input->get('time2', TRUE);
		if($time1 && $time2){
			$time = array(strtotime($time1), strtotime($time2.' 23:59:59'));
		}else{
			$time = '';
		}
		$date = $this->input->get('data', TRUE) ? $this->input->get('data') : 0;
		$now = date('Y-m-d', time());
		switch($date){
			case 1:
			$time = array(strtotime($now), strtotime($now.' 23:59:59'));
			break;
			case 2:
			$time = array(strtotime($now)-86400*30, strtotime($now.' 23:59:59'));
			break;
			case 3:
			$time = array(strtotime($now)-86400*30*3, strtotime($now.' 23:59:59'));
			break;
			case 4:
			$time = array(strtotime($now)-86400*30*6, strtotime($now.' 23:59:59'));
			break;
			case 5:
			$time = array(strtotime($now)-86400*30*12, strtotime($now.' 23:59:59'));
			break;
		} 
		$data['date'] = $date;
		$per_page = 8;
        $offset = $current_page;
        $config['base_url'] = base_url('trade/index');
        $config['total_rows'] = $this->info_model->get_moneylog_num(QUID, $time, $type);
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
        $moneylog = $this->info_model->get_moneylog($per_page, $offset * $per_page, QUID, $time, $type);
		foreach($moneylog as $k => $v){
			if(($v['type'] == 9 || $v['type'] == 6) && $v['bid'] > 0){
				$this->load->model('borrow/borrow_model');
				$borrow_name = $this->borrow_model->get_borrow_byid($v['bid'])['borrow_name'];
				$moneylog[$k]['info'] = $v['info'] . '[' . $borrow_name . ']';
			}
		}
        $data['moneylog'] = $moneylog;
		$data['types'] = $this->config->item('money_logs');
		$this->load->view('account/trade_v1', $data);
	}
}