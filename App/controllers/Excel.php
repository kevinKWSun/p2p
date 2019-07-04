<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Excel extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model(array('borrow/borrow_model', 'recharge/recharge_model'));
	}
	//首页
	public function index() {
		if($p = $this->input->post(NULL, TRUE)){
			$this->success($p['keywords'], '/excel.html');
		} else {
			if($this->uri->segment(3) == 1){
				$this->load->view('bid/excel');
			}else{
				$search = $this->input->get('query', TRUE);
				if($search){
					$times = explode(' - ', $search);
					$time1 = strtotime($times[0]);
					$time2 = strtotime($times[1] . ' 23:59:59');
				}else{
					$time1 = '';
					$time2 = '';
				}
				$all = $this->borrow_model->get_borrow_investor_excel($time1,$time2);
				$this->load->helper('common');
				$this->load->library('PHPExcel');
				$resultPHPExcel = new PHPExcel();
				$resultPHPExcel->getActiveSheet()->setCellValue('A1', '出借ID');
				$resultPHPExcel->getActiveSheet()->setCellValue('B1', '出借用户ID');
				$resultPHPExcel->getActiveSheet()->setCellValue('C1', '真实姓名');
				$resultPHPExcel->getActiveSheet()->setCellValue('D1', '手机号');
				$resultPHPExcel->getActiveSheet()->setCellValue('E1', '身份证');
				$resultPHPExcel->getActiveSheet()->setCellValue('F1', '标的ID');
				$resultPHPExcel->getActiveSheet()->setCellValue('G1', '标题');
				$resultPHPExcel->getActiveSheet()->setCellValue('H1', '还款类型');
				$resultPHPExcel->getActiveSheet()->setCellValue('I1', '期限');
				$resultPHPExcel->getActiveSheet()->setCellValue('J1', '出借金额');
				$resultPHPExcel->getActiveSheet()->setCellValue('K1', '出借日期');
				$resultPHPExcel->getActiveSheet()->setCellValue('L1', '到期日');
				$resultPHPExcel->getActiveSheet()->setCellValue('M1', '状态');
				$resultPHPExcel->getActiveSheet()->setCellValue('N1', '推荐人ID');
				$resultPHPExcel->getActiveSheet()->setCellValue('O1', '推荐人');
				$i = 1;
				foreach($all as $k => $v){
					$i++;
					$resultPHPExcel->getActiveSheet()->setCellValue('A'.$i, $v['id']);
					$resultPHPExcel->getActiveSheet()->setCellValue('B'.$i, $v['investor_uid']);
					$resultPHPExcel->getActiveSheet()->setCellValue('C'.$i, get_member_info($v['investor_uid'])['real_name']);
					$resultPHPExcel->getActiveSheet()->setCellValue('D'.$i, ' '.get_member_info($v['investor_uid'])['phone']);
					$resultPHPExcel->getActiveSheet()->setCellValue('E'.$i, ' '.get_member_info($v['investor_uid'])['idcard']);
					$resultPHPExcel->getActiveSheet()->setCellValue('F'.$i, $v['borrow_no']);
					$resultPHPExcel->getActiveSheet()->setCellValue('G'.$i, $v['borrow_name']);
					$resultPHPExcel->getActiveSheet()->setCellValue('H'.$i, $this->config->item('repayment_type')[$v['repayment_type']]);
					$resultPHPExcel->getActiveSheet()->setCellValue('I'.$i, $this->config->item('borrow_duration')[$v['borrow_duration']]);
					$resultPHPExcel->getActiveSheet()->setCellValue('J'.$i, $v['investor_capital']);
					$resultPHPExcel->getActiveSheet()->setCellValue('K'.$i, date('Y-m-d H:i:s',$v['add_time']));
					$resultPHPExcel->getActiveSheet()->setCellValue('L'.$i, date('Y-m-d H:i:s',$v['deadline']));
					$resultPHPExcel->getActiveSheet()->setCellValue('M'.$i, $this->config->item('borrow_status')[$v['borrow_status']]);
					$resultPHPExcel->getActiveSheet()->setCellValue('N'.$i, get_members($v['investor_uid'])['codeuid']);
					$resultPHPExcel->getActiveSheet()->setCellValue('O'.$i, get_member_info(get_members($v['investor_uid'])['codeuid'])['real_name']);
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
		}
		
	}
	public function recharge(){
		$time1 = $this->input->get('time1', TRUE);
		$time2 = $this->input->get('time2', TRUE);
		if(! $time1){
			$time1 = '2018-10-01';
		}
		if(! $time2){
			$time2 = date('Y-m-d', time());
		}
		$time1 = strtotime($time1);
		$time2 = strtotime($times . ' 23:59:59');
		$all = $this->recharge_model->get_recharges($time1,$time2, 1);
		$this->load->helper('common');
		$this->load->library('PHPExcel');
		$resultPHPExcel = new PHPExcel();
		$resultPHPExcel->getActiveSheet()->setCellValue('A1', '姓名');
		$resultPHPExcel->getActiveSheet()->setCellValue('B1', '手机');
		$resultPHPExcel->getActiveSheet()->setCellValue('C1', '充值方式');
		$resultPHPExcel->getActiveSheet()->setCellValue('D1', '充值金额');
		$resultPHPExcel->getActiveSheet()->setCellValue('E1', '充值时间');
		$resultPHPExcel->getActiveSheet()->setCellValue('F1', '充值状态');
		$resultPHPExcel->getActiveSheet()->setCellValue('G1', '充值银行');
		$i = 1;
		foreach($all as $k => $v){
			$i++;
			$resultPHPExcel->getActiveSheet()->setCellValue('A'.$i, get_member_info($v['uid'])['real_name']);
			if(strpos($v['nid'], 'p-mer') === FALSE) { $t='快捷'; } else { $t='网银'; }
			$resultPHPExcel->getActiveSheet()->setCellValue('B'.$i, get_member_info($v['uid'])['phone']);
			$resultPHPExcel->getActiveSheet()->setCellValue('C'.$i, $t);
			$resultPHPExcel->getActiveSheet()->setCellValue('D'.$i, $v['money']);
			$resultPHPExcel->getActiveSheet()->setCellValue('E'.$i, date('Y-m-d H:i', $v['add_time']));
			$resultPHPExcel->getActiveSheet()->setCellValue('F'.$i, $v['status']?'成功':'失败');
			$resultPHPExcel->getActiveSheet()->setCellValue('G'.$i, $v['remark']);
		}
		$outputFileName = '充值.xls'; 
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
	public function withdraw(){
		$time1 = $this->input->get('time1', TRUE);
		$time2 = $this->input->get('time2', TRUE);
		if(! $time1){
			$time1 = '2018-10-01';
		}
		if(! $time2){
			$time2 = date('Y-m-d', time());
		}
		$time1 = strtotime($time1);
		$time2 = strtotime($time2 . ' 23:59:59');
		$all = $this->recharge_model->get_recharges($time1,$time2, 2);
		$this->load->helper('common');
		$this->load->library('PHPExcel');
		$resultPHPExcel = new PHPExcel();
		$resultPHPExcel->getActiveSheet()->setCellValue('A1', '姓名');
		$resultPHPExcel->getActiveSheet()->setCellValue('B1', '手机');
		$resultPHPExcel->getActiveSheet()->setCellValue('C1', '提现方式');
		$resultPHPExcel->getActiveSheet()->setCellValue('D1', '提现金额');
		$resultPHPExcel->getActiveSheet()->setCellValue('E1', '提现时间');
		$resultPHPExcel->getActiveSheet()->setCellValue('F1', '提现状态');
		$resultPHPExcel->getActiveSheet()->setCellValue('G1', '提现银行');
		$i = 1;
		foreach($all as $k => $v){
			$i++;
			$resultPHPExcel->getActiveSheet()->setCellValue('A'.$i, get_member_info($v['uid'])['real_name']);
			$resultPHPExcel->getActiveSheet()->setCellValue('B'.$i, get_member_info($v['uid'])['phone']);
			$resultPHPExcel->getActiveSheet()->setCellValue('C'.$i, '银行');
			$resultPHPExcel->getActiveSheet()->setCellValue('D'.$i, $v['money']);
			$resultPHPExcel->getActiveSheet()->setCellValue('E'.$i, date('Y-m-d H:i', $v['add_time']));
			$resultPHPExcel->getActiveSheet()->setCellValue('F'.$i, $v['status']?'成功':'失败');
			$resultPHPExcel->getActiveSheet()->setCellValue('G'.$i, $v['remark']);
		}
		$outputFileName = '提现.xls'; 
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
	public function order() {
		
		if($p = $this->input->post(NULL, TRUE)){
			$this->success($p['keywords'], '/excel.html');
		} else {
			if($this->uri->segment(3) == 1){
				$this->load->view('bid/excel');
			}else{
				$search = $this->input->get('query', TRUE);
				if($search){
					$times = explode(' - ', $search);
					$time1 = strtotime($times[0]);
					$time2 = strtotime($times[1] . ' 23:59:59');
				}else{
					$time1 = '';
					$time2 = '';
				}
				$this->load->model('cate/cate_model');
				$all = $this->cate_model->get_cates_time($time1,$time2);
				$this->load->helper('common');
				$this->load->library('PHPExcel');
				$resultPHPExcel = new PHPExcel();
				$resultPHPExcel->getActiveSheet()->setCellValue('A1', '客户姓名');
				$resultPHPExcel->getActiveSheet()->setCellValue('B1', '客户手机');
				$resultPHPExcel->getActiveSheet()->setCellValue('C1', '商品名称');
				$resultPHPExcel->getActiveSheet()->setCellValue('D1', '应付积分');
				$resultPHPExcel->getActiveSheet()->setCellValue('E1', '实付积分');
				$resultPHPExcel->getActiveSheet()->setCellValue('F1', '地址');
				$resultPHPExcel->getActiveSheet()->setCellValue('G1', '电话');
				$resultPHPExcel->getActiveSheet()->setCellValue('H1', '姓名');
				$resultPHPExcel->getActiveSheet()->setCellValue('I1', '状态');
				$resultPHPExcel->getActiveSheet()->setCellValue('J1', '发货时间');
				$resultPHPExcel->getActiveSheet()->setCellValue('K1', '时间');
				$resultPHPExcel->getActiveSheet()->setCellValue('L1', 'ID');
				$i = 1;
				foreach($all as $k => $v){
					$i++;
					$resultPHPExcel->getActiveSheet()->setCellValue('A'.$i, get_member_info($v['uid'])['real_name']);
					$resultPHPExcel->getActiveSheet()->setCellValue('B'.$i, get_member_info($v['uid'])['phone']);
					$resultPHPExcel->getActiveSheet()->setCellValue('C'.$i, $v['gname']);
					$resultPHPExcel->getActiveSheet()->setCellValue('D'.$i, $v['score']*$v['num']);
					$resultPHPExcel->getActiveSheet()->setCellValue('E'.$i, $v['sscore']);
					$resultPHPExcel->getActiveSheet()->setCellValue('F'.$i, unserialize($v['amark'])['address']);
					$resultPHPExcel->getActiveSheet()->setCellValue('G'.$i, unserialize($v['amark'])['tel']);
					$resultPHPExcel->getActiveSheet()->setCellValue('H'.$i, unserialize($v['amark'])['realname']);
					$resultPHPExcel->getActiveSheet()->setCellValue('I'.$i, $v['status'] ? '已发货' : '待发货');
					$resultPHPExcel->getActiveSheet()->setCellValue('J'.$i, $v['uptime']?date('Y-m-d H:i', $v['uptime']):'--');
					$resultPHPExcel->getActiveSheet()->setCellValue('K'.$i, date('Y-m-d',$v['addtime']));
					$resultPHPExcel->getActiveSheet()->setCellValue('L'.$i, $v['id']);
				}
				$outputFileName = '订单数据.xls'; 
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
		
	}
	public function cj_order() {
		
		$search = $this->input->get('query', TRUE);
		if($search){
			$times = explode(' - ', $search);
			$time1 = strtotime($times[0]);
			$time2 = strtotime($times[1] . ' 23:59:59');
		}else{
			$time1 = '';
			$time2 = '';
		}
		$this->load->model('cj/cj_model');
		$all = $this->cj_model->get_order_times($time1,$time2);
		$this->load->helper('common');
		$this->load->library('PHPExcel');
		$resultPHPExcel = new PHPExcel();
		$resultPHPExcel->getActiveSheet()->setCellValue('A1', '客户姓名');
		$resultPHPExcel->getActiveSheet()->setCellValue('B1', '客户手机');
		$resultPHPExcel->getActiveSheet()->setCellValue('C1', '商品名称');
		$resultPHPExcel->getActiveSheet()->setCellValue('D1', '数量');
		$resultPHPExcel->getActiveSheet()->setCellValue('E1', '状态');
		$resultPHPExcel->getActiveSheet()->setCellValue('F1', '处理时间');
		$resultPHPExcel->getActiveSheet()->setCellValue('G1', '时间');
		$resultPHPExcel->getActiveSheet()->setCellValue('H1', '备注');
		$resultPHPExcel->getActiveSheet()->setCellValue('I1', 'ID');
		$i = 1;
		foreach($all as $k => $v){
			$i++;
			$resultPHPExcel->getActiveSheet()->setCellValue('A'.$i, get_member_info($v['uid'])['real_name']);
			$resultPHPExcel->getActiveSheet()->setCellValue('B'.$i, get_member_info($v['uid'])['phone']);
			$resultPHPExcel->getActiveSheet()->setCellValue('C'.$i, $v['name']);
			$resultPHPExcel->getActiveSheet()->setCellValue('D'.$i, $v['nums']);
			$resultPHPExcel->getActiveSheet()->setCellValue('E'.$i, $v['type'] ? '已处理' : '待处理');
		    if($v['uptime']){
				$resultPHPExcel->getActiveSheet()->setCellValue('F'.$i, date('Y-m-d',$v['uptime']));
			}elseif($v['puptime']){
				$resultPHPExcel->getActiveSheet()->setCellValue('F'.$i, date('Y-m-d',$v['puptime']));
			}else{
				$resultPHPExcel->getActiveSheet()->setCellValue('F'.$i, '--');
			}
			$resultPHPExcel->getActiveSheet()->setCellValue('G'.$i, $v['add_time']?date('Y-m-d H:i', $v['add_time']):'--');
			$resultPHPExcel->getActiveSheet()->setCellValue('H'.$i, $v['mark']);
			$resultPHPExcel->getActiveSheet()->setCellValue('I'.$i, $v['id']);
		}
		$outputFileName = '抽奖订单数据.xls'; 
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