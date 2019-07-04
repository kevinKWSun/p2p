<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Bid extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->library('pagination');
		$this->load->model(array('bid/bid_model'));
	}
	//首页
	public function index(){
		$data = array();
		$where = array();
		$search = $this->input->get(null, true);
		if(isset($search) && !empty($search['name'])) {
			$data['name'] = trim(trim($search['name']), '\t');
			$where['name'] = $data['name'];
		}
		if(!empty($search['username'])) {
			$data['username'] = trim(trim($search['username']), '\t');
			$where['username'] = $data['username'];
		}
		if(!empty($search['codename'])) {
			$data['codename'] = trim(trim($search['codename']), '\t');
			$where['codename'] = $data['codename'];
		}
		if(!empty($search['time'])) {
			$data['time'] = $search['time'];
			$where['time'] = explode(' ', $search['time']);
		}
		
		$current_page  = intval($this->uri->segment(3));
		$current_page = $current_page > 0 ? $current_page - 1 : 0;
		$per_page = 10;
        $offset = $current_page;
        $config['base_url'] = base_url('bid/index');
        $config['total_rows'] = $this->bid_model->get_borrow_investor_num($where);
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
        $data['invest'] = $this->bid_model->get_borrow_investor_lists($per_page, $offset * $per_page, $where);
		//p($data['invest']);
		$this->load->view('bid/index', $data);
	}
	
	//导出 export
	public function export() {
		$data = array();
		$where = array();
		$search = $this->input->get(null, true);
		if(isset($search) && !empty($search['name'])) {
			$data['name'] = trim(trim($search['name']), '\t');
			$where['name'] = $data['name'];
		}
		if(!empty($search['username'])) {
			$data['username'] = trim(trim($search['username']), '\t');
			$where['username'] = $data['username'];
		}
		if(!empty($search['codename'])) {
			$data['codename'] = trim(trim($search['codename']), '\t');
			$where['codename'] = $data['codename'];
		}
		if(!empty($search['time'])) {
			$data['time'] = $search['time'];
			$where['time'] = explode(' ', $search['time']);
		}
		
		$numbers = $this->bid_model->get_borrow_investor_num($where);
        $data['invest'] = $this->bid_model->get_borrow_investor_lists($numbers, 0, $where);
		//p($data['invest']);die;
		
		$all = $data['invest'];//$this->borrow_model->get_borrow_investor_excel($time1,$time2);
		$this->load->helper('common');
		$this->load->library('PHPExcel');
		$resultPHPExcel = new PHPExcel();
		
		
		
		$resultPHPExcel->getActiveSheet()->setCellValue('A1', '出借ID');
		$resultPHPExcel->getActiveSheet()->setCellValue('B1', '出借用户ID');
		$resultPHPExcel->getActiveSheet()->setCellValue('C1', '真实姓名');
		$resultPHPExcel->getActiveSheet()->setCellValue('D1', '手机号');
		$resultPHPExcel->getActiveSheet()->setCellValue('E1', '身份证');
		$resultPHPExcel->getActiveSheet()->setCellValue('F1', '注册时间');
		$resultPHPExcel->getActiveSheet()->setCellValue('G1', '标的ID');
		$resultPHPExcel->getActiveSheet()->setCellValue('H1', '标题');
		$resultPHPExcel->getActiveSheet()->setCellValue('I1', '还款类型');
		$resultPHPExcel->getActiveSheet()->setCellValue('J1', '期限');
		$resultPHPExcel->getActiveSheet()->setCellValue('K1', '出借金额');
		$resultPHPExcel->getActiveSheet()->setCellValue('L1', '出借日期');
		$resultPHPExcel->getActiveSheet()->setCellValue('M1', '到期日');
		$resultPHPExcel->getActiveSheet()->setCellValue('N1', '状态');
		$resultPHPExcel->getActiveSheet()->setCellValue('O1', '推荐人ID');
		$resultPHPExcel->getActiveSheet()->setCellValue('P1', '推荐人');
		$resultPHPExcel->getActiveSheet()->setCellValue('Q1', '首投');
		$i = 1;
		foreach($all as $k => $v){
			$i++;
			$resultPHPExcel->getActiveSheet()->setCellValue('A'.$i, $v['id']);
			$resultPHPExcel->getActiveSheet()->setCellValue('B'.$i, $v['investor_uid']);
			$resultPHPExcel->getActiveSheet()->setCellValue('C'.$i, $v['real_name']);
			$resultPHPExcel->getActiveSheet()->setCellValueExplicit('D'.$i,$v['phone'],PHPExcel_Cell_DataType::TYPE_STRING);
			$resultPHPExcel->getActiveSheet()->setCellValueExplicit('E'.$i,$v['idcard'],PHPExcel_Cell_DataType::TYPE_STRING);
			$resultPHPExcel->getActiveSheet()->setCellValue('F'.$i, date('Y-m-d', $v['reg_time']));
			$resultPHPExcel->getActiveSheet()->setCellValue('G'.$i, $v['bid'] . '/' . $v['borrow_no']);
			$resultPHPExcel->getActiveSheet()->setCellValue('H'.$i, $v['borrow_type'] == 2 ? $v['borrow_name'].'[新]' : $v['borrow_name']);
			$resultPHPExcel->getActiveSheet()->setCellValue('I'.$i, $this->config->item('repayment_type')[$v['repayment_type']]);
			$resultPHPExcel->getActiveSheet()->setCellValue('J'.$i, $this->config->item('borrow_duration')[$v['borrow_duration']]);
			$resultPHPExcel->getActiveSheet()->setCellValue('K'.$i, $v['investor_capital']);
			$resultPHPExcel->getActiveSheet()->setCellValue('L'.$i, date('Y-m-d H:i:s', $v['add_time']));
			$resultPHPExcel->getActiveSheet()->setCellValue('M'.$i, $v['deadline'] > 0 ? date('Y-m-d H:i:s', $v['deadline']) : '-');
			$resultPHPExcel->getActiveSheet()->setCellValue('N'.$i, $this->config->item('borrow_status')[$v['borrow_status']]);
			$resultPHPExcel->getActiveSheet()->setCellValue('O'.$i, $v['codeuid'] > 0 ? $v['codeuid'] : '-');
			$resultPHPExcel->getActiveSheet()->setCellValue('P'.$i, !empty($v['code_name']) ? $v['code_name'] : '-');
			$resultPHPExcel->getActiveSheet()->setCellValue('Q'.$i, !empty($v['first']) ? '是' : '否');
		}
		
		$outputFileName = '出借数据.xls'; 
		$xlsWriter = new PHPExcel_Writer_Excel5($resultPHPExcel); 
		header("Content-Type: application/force-download"); 
		header("Content-Type: application/octet-stream"); 
		header("Content-Type: application/download"); 
		header('Content-Disposition:inline;filename="'.$outputFileName.'"'); 
		header("Content-Transfer-Encoding: binary"); 
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
		header("Pragma: no-cache");
		$xlsWriter->save( "php://output" );
	}
	
	/** 交易流水 */
	public function flow() {
		$data = array();
		$where = array();
		$search = $this->input->get(null, true);
		if(!empty($search['username'])) {
			$data['username'] = trim(trim($search['username']), '\t');
			$where['username'] = $data['username'];
		}
		
		$current_page  = intval($this->uri->segment(3));
		$current_page = $current_page > 0 ? $current_page - 1 : 0;
		$per_page = 50;
        $offset = $current_page;
        $config['base_url'] = base_url('bid/flow');
        $config['total_rows'] = $this->bid_model->get_moneylog_num($where);
        $config['per_page'] = $per_page;
		$config['page_query_string'] = FALSE;
		$config['first_link'] = '首页'; // 第一页显示   
		$config['last_link'] = '末页'; // 最后一页显示   
		$config['next_link'] = '下一页'; // 下一页显示   
		$config['prev_link'] = '上一页'; // 上一页显示   
		$config['cur_tag_open'] = ' <span class="current">'; // 当前页开始样式   
		$config['cur_tag_close'] = '</span>';   
		$config['num_links'] = 50;
		$config['uri_segment'] = 3;
		$config['use_page_numbers'] = TRUE;
		$config['reuse_query_string'] = TRUE; 
        $this->pagination->initialize($config); 
        $data['totals'] = $config['total_rows'];
        $data['page'] = $this->pagination->create_links();
		$data['skip_page'] = $this->pagination->create_skip_link();
        $data['p'] = $current_page;
        $data['log'] = $this->bid_model->get_moneylog_lists($per_page, $offset * $per_page, $where);
		//p($data['log']);
		$this->load->view('bid/flow', $data);
	}
}