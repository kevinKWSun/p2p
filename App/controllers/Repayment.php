<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/** 还款统计 */
class Repayment extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->library('pagination');
		$this->load->model(array('repayment/repayment_model', 'borrow/borrow_model'));
		//$this->load->helper('url');
	}
	
	public function index() {
		$data = array();
		$where = array();
		$search = $this->input->get(null, true);
		if(isset($search) && !empty($search['name'])) {
			$data['name'] = trim(trim($search['name']), '\t');
			$where['name'] = $data['name'];
		}
		if(!empty($search['time'])) {
			$data['time'] = $search['time'];
			$where['time'] = explode(' ', $search['time']);
		}
		if(!empty($search['status'])) {
			$data['status'] = $search['status'];
			$where['status'] = $data['status'];
		}
		
		$current_page = intval($this->uri->segment(3)) ? intval($this->uri->segment(3)) - 1 : 0;
		$per_page = 10;
        $offset = $current_page;
        $config['base_url'] = base_url('repayment/index');
        $config['total_rows'] = $this->repayment_model->get_investor_detail_num($where);
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
        $data['detail'] = $this->repayment_model->get_investor_detail_lists($per_page, $offset * $per_page, $where);
		$this->load->model(array('user/ausers_model', 'member/member_model'));
		foreach($data['detail'] as $k=>$v) {
			$data['detail'][$k]['borrow'] = $this->borrow_model->get_borrow_byid($v['borrow_id']);
			//echo $v['deadline'];
			$data['detail'][$k]['detail'] = $this->repayment_model->get_investor_detail_by_deadline($v['deadline']);
			$data['detail'][$k]['status'] = $this->repayment_model->get_investor_detail_one_by_deadline($v['deadline']);
			//p($data['detail'][$k]['status']);
			if($data['detail'][$k]['detail']['repayment_time'] > 0) {
				$adminid = $this->repayment_model->get_investor_detail_one_by_deadline($v['deadline'])['adminid'];
				if($adminid > 0) {
					$data['detail'][$k]['detail']['adminname'] = $this->ausers_model->get_ausers_byuid($adminid)['realname'];
				} else {
					$data['detail'][$k]['detail']['adminname'] = $this->member_model->get_member_info_byuid($data['detail'][$k]['borrow']['borrow_uid'])['real_name'];
				}
			} else {
				$data['detail'][$k]['detail']['adminname'] = '';
			}
		}
		//p($data);
		$this->load->view('repayment/index', $data);
	}
	
	/** 还款详情 */
	public function detail() {
		$deadline = $this->uri->segment(3);
		$data = array();
		$data['detail'] = $this->repayment_model->get_repayment_detail_by_deadline($deadline);
		$this->load->view('repayment/detail', $data);
	}
	
	/** 导出 */
	public function export() {
		$search = $this->input->get(null, true);
		if(isset($search) && !empty($search['name'])) {
			$data['name'] = trim(trim($search['name']), '\t');
			$where['name'] = $data['name'];
		}
		if(!empty($search['time'])) {
			$data['time'] = $search['time'];
			$where['time'] = explode(' ', $search['time']);
		}
		if(!empty($search['status'])) {
			$data['status'] = $search['status'];
			$where['status'] = $data['status'];
		}
		$numbers = $this->repayment_model->get_investor_detail_num($where);
		$data['detail'] = $this->repayment_model->get_investor_detail_lists($numbers, 0, $where);
		$this->load->model(array('user/ausers_model', 'member/member_model'));
		foreach($data['detail'] as $k=>$v) {
			$data['detail'][$k]['borrow'] = $this->borrow_model->get_borrow_byid($v['borrow_id']);
			//echo $v['deadline'];
			$data['detail'][$k]['detail'] = $this->repayment_model->get_investor_detail_by_deadline($v['deadline']);
			$data['detail'][$k]['status'] = $this->repayment_model->get_investor_detail_one_by_deadline($v['deadline']);
			//p($data['detail'][$k]['status']);
			if($data['detail'][$k]['detail']['repayment_time'] > 0) {
				$adminid = $this->repayment_model->get_investor_detail_one_by_deadline($v['deadline'])['adminid'];
				if($adminid > 0) {
					$data['detail'][$k]['detail']['adminname'] = $this->ausers_model->get_ausers_byuid($adminid)['realname'];
				} else {
					$data['detail'][$k]['detail']['adminname'] = $this->member_model->get_member_info_byuid($data['detail'][$k]['borrow']['borrow_uid'])['real_name'];
				}
			} else {
				$data['detail'][$k]['detail']['adminname'] = '';
			}
		}
		
		$all = $data['detail'];;
		$this->load->helper('common');
		$this->load->library('PHPExcel');
		$resultPHPExcel = new PHPExcel();
		$resultPHPExcel->getActiveSheet()->setCellValue('A1', '还款ID');
		$resultPHPExcel->getActiveSheet()->setCellValue('B1', '标的ID');
		$resultPHPExcel->getActiveSheet()->setCellValue('C1', '标题');
		$resultPHPExcel->getActiveSheet()->setCellValue('D1', '还款日期');
		$resultPHPExcel->getActiveSheet()->setCellValue('E1', '期数');
		$resultPHPExcel->getActiveSheet()->setCellValue('F1', '还款方式');
		$resultPHPExcel->getActiveSheet()->setCellValue('G1', '待还总额');
		$resultPHPExcel->getActiveSheet()->setCellValue('H1', '待还本金');
		$resultPHPExcel->getActiveSheet()->setCellValue('I1', '待还利息');
		$resultPHPExcel->getActiveSheet()->setCellValue('J1', '状态');
		$resultPHPExcel->getActiveSheet()->setCellValue('K1', '还款操作人');
		$i = 1;
		foreach($all as $k => $v){
			$i++;
			$resultPHPExcel->getActiveSheet()->setCellValue('A'.$i, $v['borrow']['borrow_no']);
			$resultPHPExcel->getActiveSheet()->setCellValue('B'.$i, $v['borrow']['id']);
			$resultPHPExcel->getActiveSheet()->setCellValue('C'.$i, $v['borrow']['borrow_name']);
			$resultPHPExcel->getActiveSheet()->setCellValue('D'.$i, date('Y-m-d', $v['deadline']));
			$resultPHPExcel->getActiveSheet()->setCellValue('E'.$i, $v['sort_order'],'/',$v['total']);
			$resultPHPExcel->getActiveSheet()->setCellValue('F'.$i, $this->config->item('repayment_type')[$v['borrow']['repayment_type']]);
			$resultPHPExcel->getActiveSheet()->setCellValue('G'.$i, ($v['detail']['capital'] + $v['detail']['interest']));
			$resultPHPExcel->getActiveSheet()->setCellValue('H'.$i, $v['detail']['capital']);
			$resultPHPExcel->getActiveSheet()->setCellValue('I'.$i, $v['detail']['interest']);
			if($v['status']['status'] > 4 || $v['detail']['repayment_time'] > 0) {
				if($v['status']['total'] > 1) {
					$status = '第' . $v['status']['sort_order'] . '期已还';
				} else {
					$status = '已还';
				}
			} else {
				$status = '未还';
			}
			$resultPHPExcel->getActiveSheet()->setCellValue('J'.$i, $status);
			$resultPHPExcel->getActiveSheet()->setCellValue('K'.$i, $v['detail']['adminname']);
		}
		$outputFileName = '还款统计.xls'; 
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
	
	public function export_detail() {
		$deadline = $this->uri->segment(3);
		$data['detail'] = $this->repayment_model->get_repayment_detail_by_deadline($deadline);
		$all = $data['detail'];;
		$this->load->helper('common');
		$this->load->library('PHPExcel');
		$resultPHPExcel = new PHPExcel();
		$resultPHPExcel->getActiveSheet()->setCellValue('A1', '序号');
		$resultPHPExcel->getActiveSheet()->setCellValue('B1', '标题');
		$resultPHPExcel->getActiveSheet()->setCellValue('C1', '出借用户ID');
		$resultPHPExcel->getActiveSheet()->setCellValue('D1', '真实姓名');
		$resultPHPExcel->getActiveSheet()->setCellValue('E1', '联系方式');
		$resultPHPExcel->getActiveSheet()->setCellValue('F1', '推荐人');
		$resultPHPExcel->getActiveSheet()->setCellValue('G1', '还款总额');
		$resultPHPExcel->getActiveSheet()->setCellValue('H1', '应还本金');
		$resultPHPExcel->getActiveSheet()->setCellValue('I1', '应还利息');
		$resultPHPExcel->getActiveSheet()->setCellValue('J1', '期数');
		$resultPHPExcel->getActiveSheet()->setCellValue('K1', '状态');
		$i = 1;
		foreach($all as $k => $v){
			$i++;
			$resultPHPExcel->getActiveSheet()->setCellValue('A'.$i, $k+1);
			$resultPHPExcel->getActiveSheet()->setCellValue('B'.$i, $v['borrow_name']);
			$resultPHPExcel->getActiveSheet()->setCellValue('C'.$i, $v['investor_uid']);
			$resultPHPExcel->getActiveSheet()->setCellValue('D'.$i, $v['real_name']);
			$resultPHPExcel->getActiveSheet()->setCellValue('E'.$i, $v['phone']);
			$resultPHPExcel->getActiveSheet()->setCellValue('F'.$i, $v['codename']);
			$resultPHPExcel->getActiveSheet()->setCellValue('G'.$i, $v['capital'] + $v['interest']);
			$resultPHPExcel->getActiveSheet()->setCellValue('H'.$i, $v['capital']);
			$resultPHPExcel->getActiveSheet()->setCellValue('I'.$i, $v['interest']);
			$resultPHPExcel->getActiveSheet()->setCellValue('J'.$i, $v['sort_order'],'/',$v['total']);
			$resultPHPExcel->getActiveSheet()->setCellValue('K'.$i, ($v['status'] > 4 || $v['repayment_time'] > 0) ? '已收' : '待收');
		}
		$outputFileName = '还款详情.xls'; 
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
}