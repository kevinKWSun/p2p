<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Zdrawca extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->library('pagination');
		$this->load->database();
		$this->load->model(array('theme/zdrawc_model','member/member_model'));
	}
	
	/** 批量编辑 */
	public function batch_detail() {
		$post = $this->input->post(null, true);
		if(IS_POST) {
			$ids = explode(',', $post['ids']);
			$remark = $post['value'];
			$results = $this->zdrawc_model->get_detail_byids($ids);
			$timestamp = time();
			foreach($results as $k=>$v) {
				if($v['status'] > 0) {
					$this->error('部分数据已经处理过，请勿重复操作');
				}
				$results[$k]['uptime'] = $timestamp;
				$results[$k]['remark'] = mb_substr($remark, 0, 255);
				$results[$k]['status'] = 1;
				$results[$k]['adminid'] = UID;
			}
			
			// 插入到数据表
			if(!$this->zdrawc_model->batch_detail($results)) {
				$this->error('操作失败');
			} else {
				$this->success('操作成功');
			}
		}
	}
	
	/** 编辑 */
	public function modify() {
		$id = intval($this->uri->segment(3));
		if(empty($id)) {
			$this->error('信息错误');
		}
		
		if(IS_POST) {
			$post = $this->input->post(null, true);
			if(empty($post['value'])) {
				$this->error('备注不能为空');
			}
			
			// 判断是否满足变更的条件
			$detail = $this->zdrawc_model->get_detail_byid($id);
			if($detail['status'] == 1) {
				$this->error('已发放');
			}
			
			// 组织数据
			$detail['remark'] = mb_substr($post['value'], 0, 255);
			$detail['status'] = 1;
			$detail['adminid'] = UID;
			$detail['uptime'] = time();
			
			if($this->zdrawc_model->update_detail($detail)) {
				$this->success('操作成功');
			} else {
				$this->error('操作失败');
			}
		}
	}
	
	/** 详情导出 */
	public function detail_export() {
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
		$numbers = $this->zdrawc_model->get_detail_nums($where);
        $data['invest'] = $this->zdrawc_model->get_detail_lists($numbers, 0, $where);
		
		$all = $data['invest'];
		$this->load->helper('common');
		$this->load->library('PHPExcel');
		$resultPHPExcel = new PHPExcel();
		
		$resultPHPExcel->getActiveSheet()->setCellValue('A1', 'ID');
		$resultPHPExcel->getActiveSheet()->setCellValue('B1', '用户ID');
		$resultPHPExcel->getActiveSheet()->setCellValue('C1', '姓名');
		$resultPHPExcel->getActiveSheet()->setCellValue('D1', '电话');
		$resultPHPExcel->getActiveSheet()->setCellValue('E1', '数字');
		$resultPHPExcel->getActiveSheet()->setCellValue('F1', '金额');
		$resultPHPExcel->getActiveSheet()->setCellValue('G1', '抽奖时间');
		$resultPHPExcel->getActiveSheet()->setCellValue('H1', '是否发放');
		$resultPHPExcel->getActiveSheet()->setCellValue('I1', '发放时间');
		$resultPHPExcel->getActiveSheet()->setCellValue('J1', '备注');
		$i = 1;
		foreach($all as $k => $v){
			$i++;
			$resultPHPExcel->getActiveSheet()->setCellValue('A'.$i, $v['id']);
			$resultPHPExcel->getActiveSheet()->setCellValue('B'.$i, $v['uid']);
			$resultPHPExcel->getActiveSheet()->setCellValue('C'.$i, $v['real_name']);
			$resultPHPExcel->getActiveSheet()->setCellValueExplicit('D'.$i, $v['phone'], PHPExcel_Cell_DataType::TYPE_STRING);
			$resultPHPExcel->getActiveSheet()->setCellValue('E'.$i,$v['num']);
			$resultPHPExcel->getActiveSheet()->setCellValue('F'.$i, $v['money']);
			$resultPHPExcel->getActiveSheet()->setCellValue('G'.$i, date('Y-m-d H:i', $v['addtime']));
			$resultPHPExcel->getActiveSheet()->setCellValue('H'.$i, $v['status'] ? '已发放' : '未发放');
			$resultPHPExcel->getActiveSheet()->setCellValue('I'.$i, $v['uptime'] ? date('Y-m-d H:i', $v['uptime']) : '--');
			$resultPHPExcel->getActiveSheet()->setCellValue('J'.$i, $v['remark']);
		}
		
		$outputFileName = '宝箱出借红包数据.xls'; 
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
	
	/** 详情 */
	public function detail() {
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
        $config['base_url'] = base_url('zdrawca/detail');
        $config['total_rows'] = $this->zdrawc_model->get_detail_nums($where);
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
        $detail = $this->zdrawc_model->get_detail_lists($per_page, $per_page * $current_page, $where);
        $data['detail'] = $detail;
		$this->load->view('theme/zdrawc/detail', $data);
	}
	
	/** 升级版记录 */
	public function zdrawc() {
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
        $config['base_url'] = base_url('zdrawca/zdrawc');
        $config['total_rows'] = $this->zdrawc_model->get_zdrawc_nums($where);
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
        $zdrawc = $this->zdrawc_model->get_zdrawc_lists($per_page, $per_page * $current_page, $where);
		$data['zdrawc'] = $zdrawc;
		$this->load->view('theme/zdrawc/zdrawc', $data);
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
        $config['base_url'] = base_url('zdrawca/importui');
        $config['total_rows'] = $this->zdrawc_model->get_zdrawc_data_nums($where);
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
        $zdrawc = $this->zdrawc_model->get_zdrawc_data_lists($per_page, $per_page * $current_page, $where);
        $data['zdrawc'] = $zdrawc;
		$this->load->view('theme/zdrawc/importui', $data);
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
			$filePath = dirname(BASEPATH) . '/code/zdrawc.'.$suffix;
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
			$res_idcards = $this->zdrawc_model->get_uid_byidcard($idcards);
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
				$res_return = $this->zdrawc_model->insert_zdrawc_data($data);
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
			$this->zdrawc_dao_data();
			
		}
	}
	// 设置对应金的抽奖档
	protected function zdrawc_dao_data() {
		// 导入的数据进行处理，
		$dr_data = $this->zdrawc_model->get_data_byused();
		// 组织数据
		$zdrawc_ = array();
		$used_data = array();
		$zdrawc_time = 0;
		foreach($dr_data as $k=>$v) {
			// 判断是否设置过这个用户
			if(isset($zdrawc_[$v['uid']])) {
				// 设置过该用户
				if(isset($zdrawc_[$v['uid']]['money'.$v['duration']])) {
					// 设置过该金额
					$zdrawc_[$v['uid']]['money'.$v['duration']] += $v['money'];
				} else {
					$zdrawc_[$v['uid']]['money'.$v['duration']] = $v['money'];
				}
			} else {
				// 没有设置过该用户
				$zdrawc_[$v['uid']]['uid'] = $v['uid'];
				$zdrawc_[$v['uid']]['money'.$v['duration']] = $v['money'];
			}
			$zdrawc_time = $v['itime'];
			array_push($used_data, $v['id']);
		}
		unset($dr_data);
		// 查询每个用户的账户
		$zdrawc_data = array();
		$this->db->trans_begin();
		foreach($zdrawc_ as $k=>$v) {
			// 根据uid查询用户的数据
			$zdrawc = array();
			$zdrawc = $this->zdrawc_model->get_zdrawc_byuid($k);
			if(!isset($v['money45'])) {
				$v['money45'] = 0;
			}
			if(!isset($v['money75'])) {
				$v['money75'] = 0;
			}
			if(!empty($zdrawc)) {
				$zdrawc_data = $this->get_zdrawc_data($zdrawc, $v);
				$zdrawc_data['total'] = $zdrawc_data['num'] + $zdrawc['total'];
				$zdrawc_data['num'] = $zdrawc_data['num'] + $zdrawc['num'];
				$this->zdrawc_model->update_zdrawc($zdrawc_data);
			} else {
				$zdrawc_data = $this->get_zdrawc_data(array(), $v);
				$zdrawc_data['total'] = $zdrawc_data['num'];
				$this->zdrawc_model->insert_zdrawc($zdrawc_data);
			}
			//p($zdrawc_data);
		}
		//die; 
		// 将使用过的数据标记处理
		if(!empty($used_data)) {
			$this->zdrawc_model->update_data_byids($used_data);
		}
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$this->error('操作失败');
		} else {
			$this->db->trans_commit();
			$this->success('操作成功');
		}
	}
	// 获取插入zdrawc表的数据, $dest是本次投资的金额
	private function get_zdrawc_data($source, $dest) {
		$zdrawc_data = array();
		if(!empty($source)) {
			// 已有投资数据
			$zdrawc_data['id'] = $source['id'];
			$zdrawc_data['uid'] = $source['uid'];
			$zdrawc_data['money45'] = $dest['money45'] + $source['money45'];
			$zdrawc_data['money75'] = $dest['money75'] + $source['money75'];
			$zdrawc_data['t45'] = $dest['money45'] + $source['t45'];
			$zdrawc_data['t75'] = $dest['money75'] + $source['t75'];
			$zdrawc_data['uptime'] = time();
		} else {
			// 没有投资数据
			$zdrawc_data['uid'] = $dest['uid'];
			$zdrawc_data['money45'] = $dest['money45'];
			$zdrawc_data['money75'] = $dest['money75'];
			$zdrawc_data['t45'] = $dest['money45'];
			$zdrawc_data['t75'] = $dest['money75'];
			$zdrawc_data['addtime'] = time();
		}
		// 活动逻辑
		$zdrawc_total = 0;
		if($zdrawc_data['t45'] >= 20000) { // 80天
			$zdrawc_total += intval($zdrawc_data['t45']/20000);
			$zdrawc_data['t45'] = $zdrawc_data['t45'] - intval($zdrawc_data['t45']/20000)*20000;
		}
		$zdrawc_data['t75'] = $zdrawc_data['t45'] + $zdrawc_data['t75'];
		$zdrawc_data['t45'] = 0;
		if($zdrawc_data['t75'] >= 20000) { // 130天
			$zdrawc_total += intval($zdrawc_data['t75']/20000);
			$zdrawc_data['t75'] = $zdrawc_data['t75'] - intval($zdrawc_data['t75']/20000)*20000;
		}
		$zdrawc_data['num'] = $zdrawc_total;
		return $zdrawc_data;
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