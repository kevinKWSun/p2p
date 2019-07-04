<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Zoptimala extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->library('pagination');
		$this->load->database();
		$this->load->model(array('theme/zoptimal_model','member/member_model'));
		$this->guiz = array(
			'1' => 0,
			'2' => 1,
			'3' => 2,
			'5' => 3,
			'7' => 4,
			'10' => 5,
			'15' => 6,
			'20' => 7,
			'30' => 8,
			'40' => 9,
			'50' => 10,
			'60' => 11,
			'80' => 12,
			'100' => 13,
			'150' => 14,
		);
		
		// 可以发奖的类目
		$this->items = array(1, 3, 4);
	}
	
	/** 批量编辑 */
	public function batch_red_provide() {
		$post = $this->input->post(null, true);
		if(IS_POST) {
			$ids = explode(',', $post['ids']);
			$remark = $post['value'];
			$results = $this->zoptimal_model->get_red_byids($ids);
			$timestamp = time();
			foreach($results as $k=>$v) {
				if($v['status'] > 0) {
					$this->error('部分数据已经处理过，请勿重复操作');
				}
				$results[$k]['uptime'] = $timestamp;
				$results[$k]['remark'] = mb_substr($remark, 0, 255);
				$results[$k]['status'] = 1;
				$results[$k]['upadminid'] = UID;
			}
			
			// 插入到数据表
			if(!$this->zoptimal_model->batch_red($results)) {
				$this->error('操作失败');
			} else {
				$this->success('操作成功');
			}
		}
	}
	
	/** 出借红包导出 */
	public function ssplit_export() {
		$data = array();
		$where = array();
		$search = $this->input->get(null, true);
		if(isset($search['name'])) {
			$where['name'] = trim(trim($search['name']), '\t');
			$data['name'] = $where['name'];
		}
		if(isset($search['rid'])) {
			$where['rid'] = trim(trim($search['rid']), '\t');
			$data['rid'] = $where['rid'];
		}
		if(!empty($search['time'])) {
			$data['time'] = $search['time'];
			$where['time'] = explode(' ', $search['time']);
		}
		$numbers = $this->zoptimal_model->get_red_nums($where);
        $data['invest'] = $this->zoptimal_model->get_red_lists($numbers, 0, $where);
		
		$all = $data['invest'];
		$this->load->helper('common');
		$this->load->library('PHPExcel');
		$resultPHPExcel = new PHPExcel();
		
		$resultPHPExcel->getActiveSheet()->setCellValue('A1', 'ID');
		$resultPHPExcel->getActiveSheet()->setCellValue('B1', '红包ID');
		$resultPHPExcel->getActiveSheet()->setCellValue('C1', '用户ID');
		$resultPHPExcel->getActiveSheet()->setCellValue('D1', '姓名');
		$resultPHPExcel->getActiveSheet()->setCellValue('E1', '电话');
		$resultPHPExcel->getActiveSheet()->setCellValue('F1', '红包金额');
		$resultPHPExcel->getActiveSheet()->setCellValue('G1', '是否发放');
		$resultPHPExcel->getActiveSheet()->setCellValue('H1', '发放时间');
		$resultPHPExcel->getActiveSheet()->setCellValue('I1', '备注');
		$i = 1;
		foreach($all as $k => $v){
			$i++;
			$resultPHPExcel->getActiveSheet()->setCellValue('A'.$i, $v['id']);
			$resultPHPExcel->getActiveSheet()->setCellValue('B'.$i, $v['rid']);
			$resultPHPExcel->getActiveSheet()->setCellValue('C'.$i, $v['uid']);
			$resultPHPExcel->getActiveSheet()->setCellValue('D'.$i, $v['real_name']);
			$resultPHPExcel->getActiveSheet()->setCellValueExplicit('E'.$i, $v['phone'], PHPExcel_Cell_DataType::TYPE_STRING);
			$resultPHPExcel->getActiveSheet()->setCellValue('F'.$i, $v['money']);
			$resultPHPExcel->getActiveSheet()->setCellValue('G'.$i, $v['status'] ? '已发放' : '未发放');
			$resultPHPExcel->getActiveSheet()->setCellValue('H'.$i, $v['uptime'] ? date('Y-m-d H:i', $v['uptime']) : '--');
			$resultPHPExcel->getActiveSheet()->setCellValue('I'.$i, $v['remark']);
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
	
	/** 出借红包发放 */
	public function red_provide() {
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
			$red = $this->zoptimal_model->get_red_byid($id);
			if($red['status'] == 1) {
				$this->error('已发放');
			}
			
			// 组织数据
			$red['remark'] = mb_substr($post['value'], 0, 255);
			$red['status'] = 1;
			$red['upadminid'] = UID;
			$red['uptime'] = time();
			$this->db->trans_begin();
			
			$this->zoptimal_model->update_red($red);
			// 判断是不是最后一笔操作，如果是，更新detail表
			$is_last_red = $this->zoptimal_model->is_last_red($red['rid']);
			if(!$is_last_red) {
				$detail = $this->zoptimal_model->get_detail_byid($red['rid']);
				$detail['status'] = 1;
				$detail['uptime'] = time();
				$detail['adminid'] = UID;
				$detail['remark'] = $red['remark'];
				$this->zoptimal_model->update_detail($detail);
				
			}
			
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$this->error('操作失败');
			} else {
				$this->db->trans_commit();
				$this->success('操作成功');
			}
		}
	}
	
	/** 红包拆分 */
	public function dossplit() {
		// 查询所有需要拆分的红包
		$detail = $this->zoptimal_model->get_red_all();
		if(empty($detail)) {
			$this->error('没有需要拆分的红包');
		}
		$this->db->trans_begin();
		$red = array();
		foreach($detail as $k=>$v) {
			//拆分红包
			// 所有红包金额
			$money = array();
			$money_arr = explode('、', $v['name']);
			foreach($money_arr as $value) {
				$money_tmp = explode('*', $value);
				$money_num = isset($money_tmp[1]) ? $money_tmp[1] : 1;
				for($i=0; $i<$money_num; $i++) {
					array_push($money, $money_tmp[0]);
				}
			}
			// 组织数据
			foreach($money as $value) {
				$red[] = [
					'uid' => $v['uid'],
					'rid' => $v['id'],
					'money' => $value,
					'addtime'=> time(),
					'adminid'=> UID,
					'status' => 0,
					'ftime' => $v['addtime']
				];
			}
		}
		// 批量插入red
		$this->zoptimal_model->batch_insert_red($red);
		// 批量修改detail中的sp
		foreach($detail as $k=>$v) {
			$detail[$k]['sp'] = 1;
			unset($detail[$k]['name']);
		}
		$this->zoptimal_model->batch_update_detail($detail);
		
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$this->error('操作失败');
		} else {
			$this->db->trans_commit();
			$this->success('操作成功');
		}
		
	}
	
	/** 红包拆分列表 */
	public function ssplit() {
		$data = array();
		$where = array();
		$search = $this->input->get(null, true);
		if(isset($search['name'])) {
			$where['name'] = trim(trim($search['name']), '\t');
			$data['name'] = $where['name'];
		}
		if(isset($search['rid'])) {
			$where['rid'] = trim(trim($search['rid']), '\t');
			$data['rid'] = $where['rid'];
		}
		if(!empty($search['time'])) {
			$data['time'] = $search['time'];
			$where['time'] = explode(' ', $search['time']);
		}
		$current_page = empty($this->uri->segment(3)) ? 0 : intval($this->uri->segment(3)) - 1;
		$per_page = 50;
        $offset = $current_page;
        $config['base_url'] = base_url('zoptimala/ssplit');
        $config['total_rows'] = $this->zoptimal_model->get_red_nums($where);
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
        $detail = $this->zoptimal_model->get_red_lists($per_page, $per_page * $current_page, $where);
        $data['detail'] = $detail;
		$data['items'] = $this->items;
		$this->load->view('theme/zoptimal/ssplit', $data);
	}
	
	/** 发放奖品 */
	public function provide() {
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
			$detail = $this->zoptimal_model->get_detail_byid($id);
			if($detail['status'] == 1) {
				$this->error('已发放');
			}
			
			if(!in_array($detail['column'], $this->items)) {
				$this->error('发放功能还未开放');
			}
			
			
			// 组织数据
			$detail['remark'] = mb_substr($post['value'], 0, 255);
			$detail['status'] = 1;
			$detail['adminid'] = UID;
			$detail['uptime'] = time();
			if($this->zoptimal_model->update_detail($detail)) {
				$this->success('操作成功');
			} else {
				$this->error('操作失败');
			}
		}
	}
	
	/** 抽奖详情 导出 */
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
		$numbers = $this->zoptimal_model->get_detail_nums($where);
        $data['invest'] = $this->zoptimal_model->get_detail_lists($numbers, 0, $where);
		
		$all = $data['invest'];
		$this->load->helper('common');
		$this->load->library('PHPExcel');
		$resultPHPExcel = new PHPExcel();
		
		$resultPHPExcel->getActiveSheet()->setCellValue('A1', 'ID');
		$resultPHPExcel->getActiveSheet()->setCellValue('B1', '用户ID');
		$resultPHPExcel->getActiveSheet()->setCellValue('C1', '姓名');
		$resultPHPExcel->getActiveSheet()->setCellValue('D1', '电话');
		$resultPHPExcel->getActiveSheet()->setCellValue('E1', '奖品');
		$resultPHPExcel->getActiveSheet()->setCellValue('F1', '第几档');
		$resultPHPExcel->getActiveSheet()->setCellValue('G1', '奖品类型');
		$resultPHPExcel->getActiveSheet()->setCellValue('H1', '抽奖时间');
		$resultPHPExcel->getActiveSheet()->setCellValue('I1', '是否发放');
		$resultPHPExcel->getActiveSheet()->setCellValue('J1', '发放时间');
		$resultPHPExcel->getActiveSheet()->setCellValue('K1', '备注');
		$i = 1;
		foreach($all as $k => $v){
			$i++;
			$resultPHPExcel->getActiveSheet()->setCellValue('A'.$i, $v['id']);
			$resultPHPExcel->getActiveSheet()->setCellValue('B'.$i, $v['uid']);
			$resultPHPExcel->getActiveSheet()->setCellValue('C'.$i, $v['real_name']);
			$resultPHPExcel->getActiveSheet()->setCellValueExplicit('D'.$i, $v['phone'], PHPExcel_Cell_DataType::TYPE_STRING);
			$resultPHPExcel->getActiveSheet()->setCellValue('E'.$i, $v['name']);
			$resultPHPExcel->getActiveSheet()->setCellValue('F'.$i, $v['num']);
			$resultPHPExcel->getActiveSheet()->setCellValue('G'.$i, $v['desc']);
			$resultPHPExcel->getActiveSheet()->setCellValue('H'.$i, date('Y-m-d H:i', $v['addtime']));
			$resultPHPExcel->getActiveSheet()->setCellValue('I'.$i, $v['status'] ? '已发放' : '未发放');
			$resultPHPExcel->getActiveSheet()->setCellValue('J'.$i, $v['uptime'] ? date('Y-m-d H:i', $v['uptime']) : '--');
			$resultPHPExcel->getActiveSheet()->setCellValue('K'.$i, $v['remark']);
		}
		
		$outputFileName = '宝箱抽奖详情数据.xls'; 
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
	
	/** 抽奖详情 */
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
        $config['base_url'] = base_url('zoptimala/detail');
        $config['total_rows'] = $this->zoptimal_model->get_detail_nums($where);
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
        $detail = $this->zoptimal_model->get_detail_lists($per_page, $per_page * $current_page, $where);
        $data['detail'] = $detail;
		$data['items'] = $this->items;
		$this->load->view('theme/zoptimal/detail', $data);
	}
	
	
	/** 生成随机数 */
	private function create_rand() {
		echo mt_rand(1, 100);
	}
	
	/** 升级版记录 */
	public function zoptimal() {
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
        $config['base_url'] = base_url('zoptimala/zoptimal');
        $config['total_rows'] = $this->zoptimal_model->get_zoptimal_nums($where);
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
        $zoptimal = $this->zoptimal_model->get_zoptimal_lists($per_page, $per_page * $current_page, $where);
		$data['zoptimal'] = $zoptimal;
		$this->load->view('theme/zoptimal/zoptimal', $data);
	}
	
	/** 导入页面 */
	public function importui() {
		$data = array();
		$where = array();
		$current_page = empty($this->uri->segment(3)) ? 0 : intval($this->uri->segment(3)) - 1;
		$per_page = 50;
        $offset = $current_page;
        $config['base_url'] = base_url('zoptimala/importui');
        $config['total_rows'] = $this->zoptimal_model->get_zoptimal_data_nums($where);
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
        $zoptimal = $this->zoptimal_model->get_zoptimal_data_lists($per_page, $per_page * $current_page, $where);
        $data['zoptimal'] = $zoptimal;
		$this->load->view('theme/zoptimal/importui', $data);
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
			$filePath = dirname(BASEPATH) . '/code/zoptimal.'.$suffix;
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
				if(!is_numeric($v['A']) || !in_array($v['A'], array(97, 168))) {
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
			$res_idcards = $this->zoptimal_model->get_uid_byidcard($idcards);
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
				$res_return = $this->zoptimal_model->insert_zoptimal_data($data);
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
			$this->zoptimal_dao_data();
			
		}
	}
	// 设置对应金的抽奖档
	public function zoptimal_dao_data() {
		// 导入的数据进行处理，
		$dr_data = $this->zoptimal_model->get_data_byused();
		// 组织数据
		$zoptimal_ = array();
		$used_data = array();
		$zoptimal_time = 0;
		foreach($dr_data as $k=>$v) {
			// 判断是否设置过这个用户
			if(isset($zoptimal_[$v['uid']])) {
				// 设置过该用户
				if(isset($zoptimal_[$v['uid']]['money'.$v['duration']])) {
					// 设置过该金额
					$zoptimal_[$v['uid']]['money'.$v['duration']] += $v['money'];
				} else {
					$zoptimal_[$v['uid']]['money'.$v['duration']] = $v['money'];
				}
			} else {
				// 没有设置过该用户
				$zoptimal_[$v['uid']]['uid'] = $v['uid'];
				$zoptimal_[$v['uid']]['money'.$v['duration']] = $v['money'];
			}
			$zoptimal_time = $v['itime'];
			array_push($used_data, $v['id']);
		}
		unset($dr_data);
		// 查询每个用户的账户
		$zoptimal_data = array();
		$this->db->trans_begin();
		foreach($zoptimal_ as $k=>$v) {
			// 根据uid查询用户的数据
			$zoptimal = array();
			$zoptimal = $this->zoptimal_model->get_zoptimal_byuid($k);
			if(!isset($v['money97'])) {
				$v['money97'] = 0;
			}
			if(!isset($v['money168'])) {
				$v['money168'] = 0;
			}
			if(!empty($zoptimal)) {
				$zoptimal_data = $this->get_zoptimal_data($zoptimal, $v);
				$this->zoptimal_model->update_zoptimal($zoptimal_data);
			} else {
				$zoptimal_data = $this->get_zoptimal_data(array(), $v);
				$this->zoptimal_model->insert_zoptimal($zoptimal_data);
			}
			//p($zoptimal_data);
		}
		//die;
		// 将使用过的数据标记处理
		if(!empty($used_data)) {
			$this->zoptimal_model->update_data_byids($used_data);
		}
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$this->error('操作失败');
		} else {
			$this->db->trans_commit();
			$this->success('操作成功');
		}
	}
	// 获取插入zoptimal表的数据
	private function get_zoptimal_data($source, $dest) {
		$zoptimal_data = array();
		if(!empty($source)) {
			// 已有投资数据
			$zoptimal_data['id'] = $source['id'];
			$zoptimal_data['uid'] = $source['uid'];
			$zoptimal_data['money97'] = $dest['money97'] + $source['money97'];
			$zoptimal_data['money168'] = $dest['money168'] + $source['money168'];
			$zoptimal_data['uptime'] = time();
		} else {
			// 已有投资数据
			$zoptimal_data['uid'] = $dest['uid'];
			$zoptimal_data['money97'] = $dest['money97'];
			$zoptimal_data['money168'] = $dest['money168'];
			$zoptimal_data['addtime'] = time();
		}
		// 直接计算属于第几档，
		$sort = floor($zoptimal_data['money97']/10000 + round(($zoptimal_data['money168']*5)/3, 2)/10000);
		$mode = ($sort % 150);
		$inte = intval($sort/150);
		foreach($this->guiz as $k=>$v) {
			if($mode < $k) {
				$tmp_num = ($inte*15 + $v) % 15;
				$tmp_sort = $inte*15 + $v;
				// 重新处理奖品档数
				$zoptimal_data['num'] = ($tmp_sort > 14 && $tmp_num == 0) ? 15 : $tmp_num;
				$zoptimal_data['rounds'] = ($tmp_num > 0 && $tmp_num != 15) ?  floor($tmp_sort/15): (floor($tmp_sort/15) -1);
				break; 
			}
		}
		$sort_money = array_flip($this->guiz);
		if($tmp_num > 0) {
			$zoptimal_data['surplus'] = round(($zoptimal_data['money97'] + $zoptimal_data['money168']*5/3) - floor($tmp_sort/15)*1500000 - $sort_money[$tmp_num - 1]*10000, 2);
		} else {
			$zoptimal_data['surplus'] = round(($zoptimal_data['money97'] + $zoptimal_data['money168']*5/3) - floor($tmp_sort/15)*1500000, 2);
		}
		return $zoptimal_data;
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