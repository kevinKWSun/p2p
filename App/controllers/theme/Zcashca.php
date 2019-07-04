<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Zcashca extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->library('pagination');
		$this->load->database();
		$this->load->model(array('theme/zcashc_model','member/member_model'));
	}
	public function zcashc_order() {
		$data = array();
		$where = array();
		$search = $this->input->get(null, true);
		if(isset($search['name'])) {
			$where['name'] = trim(trim($search['name']), '\t');
			$data['name'] = $where['name'];
		}
		if(!empty($search['time'])) {
			$data['time'] = $search['time'];
			$where['time'] = explode(' ', $search['time']);
		}
		$current_page = empty($this->uri->segment(3)) ? 0 : intval($this->uri->segment(3)) - 1;
		$per_page = 50;
        $offset = $current_page;
        $config['base_url'] = base_url('zcashca/zcashc_order');
        $config['total_rows'] = $this->zcashc_model->get_order_nums($where);
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
        $order = $this->zcashc_model->get_order_lists($per_page, $per_page * $current_page, $where);
        $data['order'] = $order;
		$total_count = $this->zcashc_model->get_detail_count();
		$total_count = $total_count ? $total_count : 1;
		if($total_count > 0) {
			$data['num188'] = round(($this->zcashc_model->get_detail_count(188)/$total_count), 4)*100;
			$data['num288'] = round(($this->zcashc_model->get_detail_count(288)/$total_count), 4)*100;
			$data['num388'] = round(($this->zcashc_model->get_detail_count(388)/$total_count), 4)*100;
			$data['num588'] = round(($this->zcashc_model->get_detail_count(588)/$total_count), 4)*100;
			$data['num788'] = round(($this->zcashc_model->get_detail_count(788)/$total_count), 4)*100;
			$data['num888'] = round(($this->zcashc_model->get_detail_count(888)/$total_count), 4)*100;
		}
		
		//p($data);die;
		$this->load->view('theme/zcashc/zcashc_order', $data);
	}
	
	/** 福袋兑换导出 */
	public function zcashc_order_export() {
		$data = array();
		$where = array();
		$search = $this->input->get(null, true);
		if(isset($search['name'])) {
			$where['name'] = trim(trim($search['name']), '\t');
			$data['name'] = $where['name'];
		}
		if(!empty($search['time'])) {
			$data['time'] = $search['time'];
			$where['time'] = explode(' ', $search['time']);
		}
		
		$numbers = $this->zcashc_model->get_order_nums($where);
        $data['invest'] = $this->zcashc_model->get_order_lists($numbers, 0, $where);
		//p($data['invest']);die;
		
		$all = $data['invest'];//$this->borrow_model->get_borrow_investor_excel($time1,$time2);
		$this->load->helper('common');
		$this->load->library('PHPExcel');
		$resultPHPExcel = new PHPExcel();
		$resultPHPExcel->getActiveSheet()->setCellValue('A1', 'ID');
		$resultPHPExcel->getActiveSheet()->setCellValue('B1', '电话');
		$resultPHPExcel->getActiveSheet()->setCellValue('C1', '姓名');
		$resultPHPExcel->getActiveSheet()->setCellValue('D1', '状态');
		$resultPHPExcel->getActiveSheet()->setCellValue('E1', '现金红包');
		$resultPHPExcel->getActiveSheet()->setCellValue('F1', '倍数');
		$resultPHPExcel->getActiveSheet()->setCellValue('G1', '处理时间');
		$resultPHPExcel->getActiveSheet()->setCellValue('H1', '时间');
		$resultPHPExcel->getActiveSheet()->setCellValue('I1', '备注');
		$i = 1;
		foreach($all as $k => $v){
			$i++;
			$resultPHPExcel->getActiveSheet()->setCellValue('A'.$i, $v['id']);
			$resultPHPExcel->getActiveSheet()->setCellValue('B'.$i, ' ' . $v['phone']);
			$resultPHPExcel->getActiveSheet()->setCellValue('C'.$i, $v['real_name']);
			$resultPHPExcel->getActiveSheet()->setCellValue('D'.$i, $v['status'] == 1 ? '已处理' : '待处理');
			$resultPHPExcel->getActiveSheet()->setCellValue('E'.$i, $v['num']);
			$resultPHPExcel->getActiveSheet()->setCellValue('F'.$i, $v['multiple'] . '倍');
			$resultPHPExcel->getActiveSheet()->setCellValue('G'.$i, $v['puptime'] > 0 ? date('Y-m-d H:i', $v['puptime']) : '--');
			$resultPHPExcel->getActiveSheet()->setCellValue('H'.$i, date('Y-m-d H:i', $v['addtime']));
			$resultPHPExcel->getActiveSheet()->setCellValue('I'.$i, $v['remark']);
		}
		$outputFileName = '福袋兑换导出.xls'; 
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
	/** 福袋处理页面 */
	public function deal_zcashc_ui() {
		$id = $this->uri->segment(3);
		$data['id'] = intval($id);
		$this->load->view('theme/zcashc/deal_zcashc_ui', $data);
	}
	/** 福袋兑奖处理 */
	public function deal_zcashc_order() {
		$post = $this->input->post(null, true);
		$id = intval($post['id']);
		$order = $this->zcashc_model->get_order_by_id($id);
		$order['puptime'] = time();
		$order['padmin_id'] = UID;
		$order['remark'] = mb_substr($post['remark'], 0, 200);
		$order['status'] = 1;
		if(!$this->zcashc_model->modify_order($order)) {
			$this->error('操作失败');
		} else {
			$this->success('操作成功');
		}
	}
	
	/** 福袋兑奖批量处理 */
	public function batch_zcashc_order() {
		$post = $this->input->post(null, true);
		if(IS_POST) {
			$ids = explode(',', $post['ids']);
			$remark = $post['value'];
			if(empty($remark)) {
				$this->error('备注不能为空');
			}
			$results = $this->zcashc_model->get_order_byids($ids);
			$timestamp = time();
			foreach($results as $k=>$v) {
				if($v['status'] > 0) {
					$this->error('部分数据已经处理过，请勿重复操作');
				}
				$results[$k]['puptime'] = $timestamp;
				$results[$k]['remark'] = $remark;
				$results[$k]['status'] = 1;
				$results[$k]['padmin_id'] = 1;
			}
			
			// 插入到数据表
			if(!$this->zcashc_model->batch_order($results)) {
				$this->error('操作失败');
			} else {
				$this->success('操作成功');
			}
		}
	}
	
	/** 活动记录 */
	public function zcashc() {
		$data = array();
		$where = array();
		$search = $this->input->get(null, true);
		if(isset($search['name'])) {
			$where['name'] = trim(trim($search['name']), '\t');
			$data['name'] = $where['name'];
		}
		$current_page = empty($this->uri->segment(3)) ? 0 : intval($this->uri->segment(3)) - 1;
		$per_page = 50;
        $offset = $current_page;
        $config['base_url'] = base_url('zcashca/zcashc');
        $config['total_rows'] = $this->zcashc_model->get_zcashc_nums($where);
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
        $zcashc = $this->zcashc_model->get_zcashc_lists($per_page, $per_page * $current_page, $where);
		$data['zcashc'] = $zcashc;
		$this->load->view('theme/zcashc/zcashc', $data);
	}
	
	/** 导入页面 */
	public function importui() {
		$data = array();
		$where = array();
		$search = $this->input->get(null, true);
		if(isset($search['name'])) {
			$where['name'] = trim(trim($search['name']), '\t');
			$data['name'] = $where['name'];
		}
		$current_page = empty($this->uri->segment(3)) ? 0 : intval($this->uri->segment(3)) - 1;
		$per_page = 50;
        $offset = $current_page;
        $config['base_url'] = base_url('zcashca/importui');
        $config['total_rows'] = $this->zcashc_model->get_zcashc_data_nums($where);
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
        $zcashc = $this->zcashc_model->get_zcashc_data_lists($per_page, $per_page * $current_page, $where);
        $data['zcashc'] = $zcashc;
		$this->load->view('theme/zcashc/importui', $data);
	}
	
	/** 导入丰付excel 投资数据 */
	public function import() {
		// 导入数据
        if(@is_uploaded_file($_FILES['excel']['tmp_name'])){
			//临时文件路径
			$tmp_name = $_FILES['excel']['tmp_name'];
			//截取文件后缀
			$suffix = explode('.', $_FILES['excel']['name']);
			$suffix = $suffix[count($suffix) - 1];
			if(!in_array($suffix, array('xls', 'xlsx'))) {
				$this->error('文件格式不正确');
				//$data = array('code'=>201,'msg'=>'文件格式不正确.');
			}
			$filePath = dirname(BASEPATH) . '/code/zcashc.'.$suffix;
			if(!move_uploaded_file($tmp_name, $filePath)) {
				$this->error('路径不存在');
			}
			
			$res = $this->importExecl($filePath, 0, 0, 'F', FALSE);
			
			// 导入数据库
			$seria_array = array('A'=>'duration', 'B'=>'itime', 'C'=>'name', 'D'=>'idcard', 'E'=>'money', 'F'=>'flag');
			// 去掉数据的空格
			foreach($res as $k=>$v) {
				foreach($v as $key=>$value) {
					$res[$k][$key] = trim($value);
				}
			}
			// 验证数据是否合规
			// 记录所有身份证号到一个数组中
			$idcards = array();
			foreach($res as $k=>$v) {
				if(!is_numeric($v['A']) || !in_array($v['A'], array(45, 75))) {
					$this->error('A' . $k . '单元格数据有误');
				}
				if(!preg_match_all("/^2019-\d{2}-\d{2}$/", trim($v['B']))) {
					$this->error('B' . $k . '单元格数据有误');
				}
				if(mb_strlen($v['C']) > 20) {
					$this->error('C' . $k . '单元格数据有误');
				}
				if(mb_strlen($v['D']) > 18) {
					$this->error('D' . $k . '单元格数据有误');
				}
				if(!is_numeric($v['E'])) {
					$this->error('E' . $k . '单元格数据有误');
				}
				if(mb_strlen($v['F']) > 200) {
					$this->error('F' . $k . '单元格数据有误');
				}
				array_push($idcards, $v['D']);
			}
			
			// 身份证号去重复
			$idcards = array_unique($idcards);
			// 查出所有身份证号对应的UID
			$res_idcards = $this->zcashc_model->get_uid_byidcard($idcards);
			$idcards = array();
			foreach($res_idcards as $k=>$v) {
				$idcards[$v['idcard']] = $v['uid'];
			}
			unset($res_idcards);
			
			// 组织数据
			$data = array();
			$field = array_flip($seria_array);
			$timestamp = time();
			foreach($res as $k=>$v) {
				$data[] = [
					'uid'		=> isset($idcards[$v[$field['idcard']]]) ? $idcards[$v[$field['idcard']]] : 0,
					'uuid' 		=> md5($v[$field['flag']]),
					'duration' 	=> $v[$field['duration']],
					'itime' 	=> strtotime($v[$field['itime']]),
					'name' 		=> $v[$field['name']],
					'idcard' 	=> $v[$field['idcard']],
					'money' 	=> $v[$field['money']],
					'flag' 		=> $v[$field['flag']],
					'status' 	=> isset($idcards[$v[$field['idcard']]]) ? 1 : 0,
					'addtime'	=> $timestamp,
					'adminid'	=> UID,
				];
			}
			
			// 批量插入数据库
			try {
				$res_return = $this->zcashc_model->insert_zcashc_data($data);
			} catch(Exception $e) {
				echo $e->getMessage();
			}
			// if($res_return) {
				// $this->success('导入成功！');
			// } else {
				// $this->error('数据不能重复导入！');
			// }
			if(!$res_return) {
				$this->error('数据不能重复导入！');
			}
			$this->zcashc_dao_data();
			
		}
	}
	// 设置对应金的抽奖档
	private function zcashc_dao_data() {
		// 导入的数据进行处理，
		$dr_data = $this->zcashc_model->get_data_byused();
		// 组织数据
		$zcashc_ = array();
		$used_data = array();
		$zcashc_time = 0;
		foreach($dr_data as $k=>$v) {
			// 判断是否设置过这个用户
			if(isset($zcashc_[$v['uid']])) {
				// 设置过该用户
				if(isset($zcashc_[$v['uid']]['money'.$v['duration']])) {
					// 设置过该金额
					$zcashc_[$v['uid']]['money'.$v['duration']] += $v['money'];
				} else {
					$zcashc_[$v['uid']]['money'.$v['duration']] = $v['money'];
				}
			} else {
				// 没有设置过该用户
				$zcashc_[$v['uid']]['uid'] = $v['uid'];
				$zcashc_[$v['uid']]['money'.$v['duration']] = $v['money'];
			}
			$zcashc_time = $v['itime'];
			array_push($used_data, $v['id']);
		}
		unset($dr_data);
		// 查询每个用户的账户
		$zcashc_data = array();
		$this->db->trans_begin();
		foreach($zcashc_ as $k=>$v) {
			// 根据uid查询用户的数据
			$zcashc = array();
			$zcashc = $this->zcashc_model->get_zcashc_byuid($k);
			if(!isset($v['money45'])) {
				$v['money45'] = 0;
			}
			if(!isset($v['money75'])) {
				$v['money75'] = 0;
			}
			if(!empty($zcashc)) {
				$zcashc_data = $this->get_zcashc_data($zcashc, $v);
				$zcashc_data['totals'] = $zcashc_data['total'] + $zcashc['totals'];
				$zcashc_data['total'] = $zcashc_data['total'] + $zcashc['total'];
				$this->zcashc_model->update_zcashc($zcashc_data);
			} else {
				$zcashc_data = $this->get_zcashc_data(array(), $v);
				$zcashc_data['totals'] = $zcashc_data['total'];
				$this->zcashc_model->insert_zcashc($zcashc_data);
			}
		}
		//die; 
		// 将使用过的数据标记处理
		if(!empty($used_data)) {
			$this->zcashc_model->update_data_byids($used_data);
		}
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$this->error('操作失败');
		} else {
			$this->db->trans_commit();
			$this->success('操作成功');
		}
	}
	// 获取插入zcashc表的数据, $dest是本次投资的金额
	private function get_zcashc_data($source, $dest) {
		$zcashc_data = array();
		if(!empty($source)) {
			// 已有投资数据
			$zcashc_data['id'] = $source['id'];
			$zcashc_data['uid'] = $source['uid'];
			$zcashc_data['money45'] = $dest['money45'] + $source['money45'];
			$zcashc_data['money75'] = $dest['money75'] + $source['money75'];
			$zcashc_data['t45'] = $dest['money45'] + $source['t45'];
			$zcashc_data['t75'] = $dest['money75'] + $source['t75'];
			$zcashc_data['uptime'] = time();
		} else {
			// 没有投资数据
			$zcashc_data['uid'] = $dest['uid'];
			$zcashc_data['money45'] = $dest['money45'];
			$zcashc_data['money75'] = $dest['money75'];
			$zcashc_data['t45'] = $dest['money45'];
			$zcashc_data['t75'] = $dest['money75'];
			$zcashc_data['addtime'] = time();
		}
		// 活动逻辑
		$zcashc_total = 0;
		if($zcashc_data['t45'] >= 20000) { // 80天
			$zcashc_total += intval($zcashc_data['t45']/20000);
			$zcashc_data['t45'] = $zcashc_data['t45'] - intval($zcashc_data['t45']/20000)*20000;
		}
		$zcashc_data['t75'] = $zcashc_data['t45'] + $zcashc_data['t75'];
		$zcashc_data['t45'] = 0;
		if($zcashc_data['t75'] >= 20000) { // 130天
			$zcashc_total += intval(($zcashc_data['t75'])/20000);
			$zcashc_data['t75'] = $zcashc_data['t75'] - intval($zcashc_data['t75']/20000)*20000;
		}
		$zcashc_data['t45'] = 0;
		$zcashc_data['total'] = $zcashc_total;
		return $zcashc_data;
	}
	
	/**
	*  数据导入, 有表头的数据导入
	* @param string $file excel文件
	* @param string $sheet
	* @param int $higthRow 读取最高行数
	* @param String $hightColumn 读取最高列数
	* @param boolean $head 是否需要表头 true:需要
	* @return array 返回解析数据
	*/
	private function importExecl($file = '', $sheet = 0, $higthRow = 0, $hightColumn = '', $head = true){
		$file = iconv("utf-8", "gb2312", $file);   //转码
		
		if(empty($file) OR !file_exists($file)) {
			$this->error('文件不存在');
			//$data = array('code'=>202,'msg'=>'文件不存在.');
		}
		
		$this->load->library('PHPExcel');
		$this->load->library('PHPExcel/IOFactory');
		
		$PHPReader = new PHPExcel_Reader_Excel2007();
		if(! $PHPReader->canRead($file)) {
			$PHPReader = new PHPExcel_Reader_Excel5();
		}
		if(! $PHPReader->canRead($file)) {
			$this->error('文件读取失败');
			//$data = array('code'=>202,'msg'=>'文件读取失败.');
		}
		//创建excel对象
		$PHPExcel = $PHPReader->load($file); 
		
		$excleSheet = $PHPExcel->getSheet($sheet);
		$startRow = $head ? 1 : 2;
		$highestRow = empty($higthRow) ? $excleSheet->getHighestRow() : $higthRow;//echo $highestRow;
		$highestColumm = empty($hightColumn) ? $excleSheet->getHighestColumn() : $hightColumn;//$sheet->getHighestColumn();
		$highestColumm ++;
		
		$res = array();
		for ($row = $startRow; $row <= $highestRow; $row++){
			for ($column = 'A'; $column != $highestColumm; $column++) {
				$cell = $excleSheet->getCell($column.$row);
				$value = $cell->getValue();
				if(mb_substr($value, 0, 1) == '=' && $column == 'F') {
					$res[$row][$column] = $cell->getOldCalculatedValue();
				} else {
					$res[$row][$column] = $value;
				}
			}
		}
		
		return $res;
	}
}