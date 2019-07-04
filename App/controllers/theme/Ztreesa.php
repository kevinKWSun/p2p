<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Ztreesa extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->library('pagination');
		$this->load->model(array('theme/ztrees_model','member/member_model'));
		//$this->load->helper('url');
	}
	
	/** 变更导出 */
	public function chang_export() {
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
		$numbers = $this->ztrees_model->get_change_record_nums($where);
        $data['invest'] = $this->ztrees_model->get_change_record_lists($numbers, 0, $where);
		
		$all = $data['invest'];
		$this->load->helper('common');
		$this->load->library('PHPExcel');
		$resultPHPExcel = new PHPExcel();
		
		$resultPHPExcel->getActiveSheet()->setCellValue('A1', 'ID');
		$resultPHPExcel->getActiveSheet()->setCellValue('B1', '电话');
		$resultPHPExcel->getActiveSheet()->setCellValue('C1', '姓名');
		$resultPHPExcel->getActiveSheet()->setCellValue('D1', '变更前');
		$resultPHPExcel->getActiveSheet()->setCellValue('E1', '变更后');
		$resultPHPExcel->getActiveSheet()->setCellValue('F1', '操作人');
		$resultPHPExcel->getActiveSheet()->setCellValue('G1', '操作时间');
		$i = 1;
		foreach($all as $k => $v){
			$type_before = '';
			$type_after = '';
			switch($v['type_before']) {
				case 0:
					$type_before = '红苹果';break;
				case 1:
					$type_before = '金苹果';break;
				case 8:
					$type_before = '梨';break;
				case 9:
					$type_before = '橘子';break;
				case 10:
					$type_before = '火龙果';break;
				case 11:
					$type_before = '葡萄';break;
				case 12:
					$type_before = '桃子';break;
				case 13:
					$type_before = '投资红包';break;
			}
			switch($v['type_after']) {
				case 0:
					$type_after = '红苹果';break;
				case 1:
					$type_after = '金苹果';break;
				case 8:
					$type_after = '梨';break;
				case 9:
					$type_after = '橘子';break;
				case 10:
					$type_after = '火龙果';break;
				case 11:
					$type_after = '葡萄';break;
				case 12:
					$type_after = '桃子';break;
				case 13:
					$type_after = '投资红包';break;
			}
			$i++;
			$resultPHPExcel->getActiveSheet()->setCellValue('A'.$i, $v['id']);
			$resultPHPExcel->getActiveSheet()->setCellValueExplicit('B'.$i, $v['phone'], PHPExcel_Cell_DataType::TYPE_STRING);
			$resultPHPExcel->getActiveSheet()->setCellValue('C'.$i, $v['real_name']);
			$resultPHPExcel->getActiveSheet()->setCellValue('D'.$i, $type_before);
			$resultPHPExcel->getActiveSheet()->setCellValue('E'.$i, $type_after);
			$resultPHPExcel->getActiveSheet()->setCellValue('F'.$i, $v['realname']);
			$resultPHPExcel->getActiveSheet()->setCellValue('G'.$i, date('Y-m-d H:i:s', $v['addtime']));
		}
		
		$outputFileName = '水果变更记录.xls'; 
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
	
	/** 变更记录 */
	public function change_record() {
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
        $config['base_url'] = base_url('ztreesa/change_record');
        $config['total_rows'] = $this->ztrees_model->get_change_record_nums($where);
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
        $detail = $this->ztrees_model->get_change_record_lists($per_page, $per_page * $current_page, $where);
        $data['detail'] = $detail;
		//p($detail);
		$this->load->view('theme/ztrees_change_record', $data);
	}
	
	/** 发财树详情导出 */
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
		if(!empty($search['btime'])) {
			$data['btime'] = $search['btime'];
			$where['btime'] = explode(' ', $search['btime']);
		}
		$numbers = $this->ztrees_model->get_detail_nums($where);
        $data['invest'] = $this->ztrees_model->get_detail_lists($numbers, 0, $where);
		
		$all = $data['invest'];
		$this->load->helper('common');
		$this->load->library('PHPExcel');
		$resultPHPExcel = new PHPExcel();
		
		$resultPHPExcel->getActiveSheet()->setCellValue('A1', 'ID');
		$resultPHPExcel->getActiveSheet()->setCellValue('B1', '电话');
		$resultPHPExcel->getActiveSheet()->setCellValue('C1', '姓名');
		$resultPHPExcel->getActiveSheet()->setCellValue('D1', '数量');
		$resultPHPExcel->getActiveSheet()->setCellValue('E1', '类型');
		$resultPHPExcel->getActiveSheet()->setCellValue('F1', '发放日期');
		$resultPHPExcel->getActiveSheet()->setCellValue('G1', '收割日期');
		$i = 1;
		foreach($all as $k => $v){
			$type = '';
			switch($v['type']) {
				case 0:
					$type = '红苹果';break;
				case 1:
					$type = '金苹果';break;
				case 8:
					$type = '梨';break;
				case 9:
					$type = '橘子';break;
				case 10:
					$type = '火龙果';break;
				case 11:
					$type = '葡萄';break;
				case 12:
					$type = '桃子';break;
				case 13:
					$type = '投资红包';break;
			}
			$i++;
			$resultPHPExcel->getActiveSheet()->setCellValue('A'.$i, $v['id']);
			$resultPHPExcel->getActiveSheet()->setCellValueExplicit('B'.$i, $v['phone'], PHPExcel_Cell_DataType::TYPE_STRING);
			$resultPHPExcel->getActiveSheet()->setCellValue('C'.$i, $v['real_name']);
			$resultPHPExcel->getActiveSheet()->setCellValue('D'.$i, $v['num']);
			$resultPHPExcel->getActiveSheet()->setCellValue('E'.$i, $type);
			$resultPHPExcel->getActiveSheet()->setCellValue('F'.$i, date('Y-m-d H:i:s', $v['addtime']));
			$resultPHPExcel->getActiveSheet()->setCellValue('G'.$i, date('Y-m-d H:i:s', $v['uptime']));
		}
		
		$outputFileName = '水果详情记录.xls'; 
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
	/** 修改水果界面 */
	public function changeui() {
		$id = intval($this->uri->segment(3));
		$data['id'] = intval($id);
		$data['detail'] = $this->ztrees_model->get_detail_byid($id);
		//类型0：红苹果1：金苹果8:梨9：橘子10：火龙果11：葡萄12:桃子13：出借红包
		$data['type'] = array(
			'0' => '红苹果',
			'1' => '金苹果',
			'8' => '梨',
			'9' => '橘子',
			'10' => '火龙果',
			'11' => '葡萄',
			'12' => '桃子',
			'13' => '出借红包'
		);
		$this->load->view('/theme/ztrees_changeui', $data);
	}
	
	/** 修改水果属性 */
	public function change_type() {
		$error = false;
		$error_msg = array(
			'1' => '该种类的水果已兑换完',
			'2' => '水果种类没有变更',
			'3' => '未收割的水果不能变更种类'
		);
		if(IS_POST) {
			// 接收参数
			$post = $this->input->post(null, true);
			$uid = intval($post['uid']);
			$id = intval($post['id']);
			$type_after = intval($post['after']);
			if(empty($uid) || empty($id)) {
				$this->error('数据错误');
			}
			if(!in_array($type_after, array(0, 1, 8, 9, 10, 11, 12, 13))) {
				$this->error('水果种类不存在');
			}
			
			$this->db->trans_begin();
			do {
				// 查询主表对应的数量
				$ztrees = $this->ztrees_model->get_ztrees_byuid($uid);
				
				// 查询详情表
				$detail = $this->ztrees_model->get_detail_byid($id);
				$type_before = $detail['type'];
				if($type_before == $type_after) {
					$error = 2; break;
				}
				if($detail['used'] == 0) {
					$error = 3; break;
				}
				// 查询这种水果已有的总数量
				$total = $this->ztrees_model->get_detail_used_byuid($uid, $type_before);
				
				// 查询订单已使用情况, 计算总兑换数量
				$total_used = 0;
				//苹果类型1:1个红苹果(128元红包)2：2个红苹果（300元红包）3:5个红苹果（900元红包）4：10个红苹果（2100元红包）5:15个红苹果（3700元红包）6:20个红苹果（5800元红包）7：1个金苹果（8800元红包）
				if($type_before == 0) {
					$types = array(1, 2, 3, 4, 5, 6);
				} else if($type_before == 1) {
					$types = array(7);
				} else if($type_before > 7) {
					$types = array($type_before);
				}
				$ztrees_data = $this->ztrees_model->get_order_by_uid_type($uid, $types);
				foreach($ztrees_data as $k=>$v) {
					switch($v['type']) {
						case 1: 
							$total_used += 1;
						break;
						case 2:
							$total_used += 2;
						break;
						case 3:
							$total_used += 5;
						break;
						case 4:
							$total_used += 10;
						break;
						case 5:
							$total_used += 15;
						break;
						case 6:
							$total_used += 20;
						break;
						case 7:
							$total_used += 1;
						break;
						case 8:
						case 9:
						case 10:
						case 11:
						case 12:
						case 13:
							$total_used += 1;
					}
				}
				// 计算可以修改的数量
				$change_num = $total - $total_used;
				if($change_num < 1) {
					$error = 1; break;
				}
				
				// 修改逻辑
				$detail['type'] = $type_after;
				//var_dump($type_after); echo '<br />';
				if($type_before == 0) {
					// 红苹果修改为其他水果
					$ztrees['num'] -= 1;
					$ztrees['nnum'] -= 1;
					if($type_after == 1) {
						$ztrees['gold'] += 1;
						$ztrees['ngold'] += 1;
					} else {
						$ztrees['other'] += 1;
						$ztrees['nother'] += 1;
					}
				} else if($type_before == 1) {
					// 金苹果修改为其他水果
					$ztrees['gold'] -= 1;
					$ztrees['ngold'] -= 1;
					if($type_after == 0) {
						$ztrees['num'] += 1;
						$ztrees['nnum'] += 1;
					} else {
						$ztrees['other'] += 1;
						$ztrees['nother'] += 1;
					}
				} else if($type_before > 7) {
					// 其他水果修改为其他水果
					$ztrees['other'] -= 1;
					$ztrees['nother'] -= 1;
					if($type_after == 1) {
						$ztrees['gold'] += 1;
						$ztrees['ngold'] += 1;
					} else if($type_after == 0) {
						$ztrees['num'] += 1;
						$ztrees['nnum'] += 1;
					} else {
						$ztrees['other'] += 1;
						$ztrees['nother'] += 1;
					}
				} 
				$ztrees['uptime'] = time();
				$data = array(
					'detail_id' => $id,
					'type_before' => $type_before,
					'type_after' => $type_after,
					'adminid' => UID,
					'addtime' => time()
				);
				$this->ztrees_model->update_ztrees($ztrees);
				$this->ztrees_model->update_detail($detail);
				$this->ztrees_model->insert_change($data);
				
			} while(false);
			
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$this->error('操作失败');
			} else {
				if($error) {
					$this->db->trans_rollback();
					$this->error($error_msg[$error]);
				}
				$this->db->trans_commit();
				$this->success('操作成功');
			}
		}
	}
	
	/** 多发页面 */
	public function provideui() {
		$uid = intval($this->uri->segment(3));
		$data['uid'] = intval($uid);
		//类型0：红苹果1：金苹果8:梨9：橘子10：火龙果11：葡萄12:桃子13：出借红包
		$data['type'] = array(
			'0' => '红苹果',
			'1' => '金苹果',
			'8' => '梨',
			'9' => '橘子',
			'10' => '火龙果',
			'11' => '葡萄',
			'12' => '桃子',
			'13' => '出借红包'
		);
		$this->load->view('/theme/ztrees_provideui', $data);
	}
	
	/** 发各种水果，2019-5-21 */
	public function provide() {
		if(IS_POST) {
			// 接收参数
			$post = $this->input->post(null, true);
			$uid = intval($post['uid']);
			$type = intval($post['type']);
			$num = intval($post['num']);
			$remark = mb_substr($post['remark'], 0, 255);
			if(empty($uid)) {
				$this->error('数据错误');
			}
			// 查询数据库得到的数据
			if($type == 0) {
				$sort_detail = $this->ztrees_model->get_apple_max($uid);
				$sort = !empty($sort_detail) ? $sort_detail['sort'] : 0;
			}
			
			$ztrees_detail = $this->ztrees_model->get_detail_byuid($uid);
			$begin = empty($ztrees_detail) ? 1 : ($ztrees_detail['ssort'] + 1);
			
			if($num > 0) {
				for($i = $begin; $i < ($begin + $num); $i++) {
					if(isset($sort)) {
						$sort += 1;
					}
					// 组织数据
					$data[] = array(
						'uid' => $uid,
						'num' => 1,
						'type' => $type,
						'addtime' => time(),
						'sort' => isset($sort) ? $sort : 0,
						'ssort' => $i,
						'used' => 1,
						'status' => 1,
						'remark' => $remark,
						'uptime' => time()
					);
				}
				
				$this->db->trans_begin();
				$this->ztrees_model->batch_insert_detail($data);
				$ztrees = $this->ztrees_model->get_ztrees_byuid($uid);
				if($type > 1) {
					$ztrees['other'] += $num;
					$ztrees['nother'] += $num;
				} else if($type == 1) {
					$ztrees['gold'] += $num;
					$ztrees['ngold'] += $num;
				} else {
					$ztrees['num'] += $num;
					$ztrees['nnum'] += $num;
				}
				$ztrees['uptime'] = time();
				$this->ztrees_model->update_ztrees($ztrees);
				if ($this->db->trans_status() === FALSE) {
					$this->db->trans_rollback();
					$this->error('操作失败');
				} else {
					$this->db->trans_commit();
					$this->success('操作成功');
				}
			}
		}
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
	
	/** 导入丰付excel 投资数据 */
	public function import_ztrees_data() {
		$this->load->model('theme/ztrees_model');
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
			$filePath = dirname(BASEPATH) . '/code/ztrees.'.$suffix;
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
				
				if(!is_numeric($v['A']) || !in_array($v['A'], array(97, 168, 33))) {
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
			$res_idcards = $this->ztrees_model->get_uid_byidcard($idcards);
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
				$res_return = $this->ztrees_model->insert_ztrees_data($data);
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
			$this->ztrees_dao_data();
			
		}
	}
	/** 导入页面 */
	public function import_ztreesui() {
		$this->load->model('theme/ztrees_model');
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
        $config['base_url'] = base_url('ztreesa/import_ztreesui');
        $config['total_rows'] = $this->ztrees_model->get_ztrees_data_nums($where);
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
        $ztrees = $this->ztrees_model->get_ztrees_data_lists($per_page, $per_page * $current_page, $where);
        $data['ztrees'] = $ztrees;
		$this->load->view('theme/ztrees_importui', $data);
	}
	// 设置对应金额并发苹果
	private function ztrees_dao_data() {
		$this->load->model('theme/ztrees_model');
		$this->load->database();
		// 导入的数据进行处理，
		$dr_data = $this->ztrees_model->get_data_byused();
		// 组织数据
		$ztrees_ = array();
		$used_data = array();
		$ztrees_time = 0;
		foreach($dr_data as $k=>$v) {
			// 判断是否设置过这个用户
			if(isset($ztrees_[$v['uid']])) {
				// 设置过该用户
				if(isset($ztrees_[$v['uid']]['money'.$v['duration']])) {
					// 设置过该金额
					$ztrees_[$v['uid']]['money'.$v['duration']] += $v['money'];
				} else {
					$ztrees_[$v['uid']]['money'.$v['duration']] = $v['money'];
				}
			} else {
				// 没有设置过该用户
				$ztrees_[$v['uid']]['uid'] = $v['uid'];
				$ztrees_[$v['uid']]['money'.$v['duration']] = $v['money'];
			}
			$ztrees_time = $v['itime'];
			array_push($used_data, $v['id']);
		}
		unset($dr_data);
		// 查询每个用户的账户
		$ztrees_data = array();
		$this->db->trans_begin();
		foreach($ztrees_ as $k=>$v) {
			// 根据uid查询用户的数据
			$ztrees = array();
			$ztrees = $this->ztrees_model->get_ztrees_byuid($k);
			if(!isset($v['money33'])) {
				$v['money33'] = 0;
			}
			if(!isset($v['money97'])) {
				$v['money97'] = 0;
			}
			if(!isset($v['money168'])) {
				$v['money168'] = 0;
			}
			if(!empty($ztrees)) {
				if($ztrees['oldtype'] == 0) {
					$this->ztrees_model->set_ztrees_ssort($k);
					$ztrees['oldtype'] = 1;
					$ztrees['old'] = $ztrees['num'] + $ztrees['gold'];
					$ztrees['olduse'] = $ztrees['num'] . '-' . $ztrees['gold'] . '-' . $ztrees['nnum'] . '-' . $ztrees['ngold'];
					$this->ztrees_model->update_ztrees($ztrees);
				}
				$ztrees_data = $this->get_ztrees_data($ztrees, $v);
				// 生成苹果详情
				if($ztrees_data['num'] > 0) {
					$detail_data = $this->set_ztrees_detail($ztrees_data['num'], $ztrees_data['uid']);
					$this->ztrees_model->batch_insert_detail($detail_data);
					$apple_data = array(
						'uid' => $ztrees_data['uid'],
						'num' => $ztrees_data['num'],
						'itime' => $ztrees_time,
						'addtime' => time()
					);
					$this->ztrees_model->insert_apple($apple_data);
				}
				$ztrees_data['num'] += $ztrees['num'];
				// 修改数据
				$this->ztrees_model->update_ztrees($ztrees_data);
			} else {
				$ztrees_data = $this->get_ztrees_data(array(), $v);
				// 生成苹果详情
				if($ztrees_data['num'] > 0) {
					$detail_data = $this->set_ztrees_detail($ztrees_data['num'], $ztrees_data['uid']);
					$this->ztrees_model->batch_insert_detail($detail_data);
					$apple_data = array(
						'uid' => $ztrees_data['uid'],
						'num' => $ztrees_data['num'],
						'itime' => $ztrees_time,
						'addtime' => time()
					);
					$this->ztrees_model->insert_apple($apple_data);
				}
				// 标记为新数据
				$ztrees_data['oldtype'] = 1;
				// 插入数据
				$this->ztrees_model->insert_ztrees($ztrees_data);
			}
		}
		// 将使用过的数据标记处理
		if(!empty($used_data)) {
			$this->ztrees_model->update_data_byids($used_data);
		}
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$this->error('操作失败');
		} else {
			$this->db->trans_commit();
			$this->success('操作成功');
		}
	}
	// 生成苹果详情
	private function set_ztrees_detail($num, $uid) {
		$this->load->model('theme/ztrees_model');
		$detail_data = array();
		$ztrees_detail = $this->ztrees_model->get_detail_byuid($uid);
		$begin = empty($ztrees_detail) ? 1 : ($ztrees_detail['ssort'] + 1);
		for($i = $begin; $i < ($begin + $num); $i++) {
			$detail_data[] = [
				'uid' => $uid,
				'num' => 1,
				'type' => 0,
				'addtime' => time(),
				//'sort' => $i,
				'ssort' => $i,
				'used' => 0,
				'status' => 0,
				'remark' => ''
			];
		}
		return $detail_data;
	}
	// 获取插入ztrees表的数据
	private function get_ztrees_data($source, $dest) {
		$ztrees_data = array();
		$standard = 100000;
		if(!empty($source) && $source['status'] == 5) {
			// 已经长成苹果树
			$ztrees_data['id'] = $source['id'];
			$ztrees_data['uid'] = $source['uid'];
			$ztrees_data['money33'] = $dest['money33'] + $source['money33'];
			$ztrees_data['money97'] = $dest['money97'] + $source['money97'];
			$ztrees_data['money168'] = $dest['money168'] + $source['money168'];
			$ztrees_data['uptime'] = time();
			
			$dest['money33'] += $source['t33'];
			$dest['money97'] += $source['t97'];
			$dest['money168'] += $source['t168'];
			
			// 直接计算应得苹果数量
			$ztrees_total = 0;
			if($dest['money33'] >= 60000) { // 33天
				$ztrees_total += intval($dest['money33']/60000);
				$dest['money33'] -= intval($dest['money33']/60000)*60000;
			}
			if($dest['money97'] >= 30000) { // 97天
				$ztrees_total += intval($dest['money97']/30000);
				$dest['money97'] -= intval($dest['money97']/30000)*30000;
			}
			if($dest['money168'] >= 15000) { // 168天
				$ztrees_total += intval($dest['money168']/15000);
				$dest['money168'] -= intval($dest['money168']/15000)*15000;
			}
			$ztrees_data['t33'] = $dest['money33'];
			$ztrees_data['t97'] = $dest['money97'];
			$ztrees_data['t168'] = $dest['money168'];
			$ztrees_data['num'] = $ztrees_total;
		} else {
			// 未长成苹果树
			if(!empty($source)) {
				$ztrees_data['id'] = $source['id'];
			}
			$ztrees_data['uid'] = $dest['uid'];
			$ztrees_data['money33'] = isset($source['money33']) ? ($dest['money33'] + $source['money33']) : $dest['money33'];
			$ztrees_data['money97'] = isset($source['money97']) ? ($dest['money97'] + $source['money97']) : $dest['money97'];
			$ztrees_data['money168'] = isset($source['money168']) ? ($dest['money168'] + $source['money168']) : $dest['money168'];
			if(!empty($source)) {
				$ztrees_data['uptime'] = time();
				$dest['money33'] += $source['money33'];
				$dest['money97'] += $source['money97'];
				$dest['money168'] += $source['money168'];
			} else {
				$ztrees_data['addtime'] = time();
			}
			
			$total = $dest['money33'] + $dest['money97'] + $dest['money168']*2;
			$tmp_standard = $standard;
			if($total >= $tmp_standard) {
				// 金额大于等于十万
				if($dest['money33'] >= $tmp_standard) {
					$dest['money33'] = $dest['money33'] - $tmp_standard;
				} else {
					$tmp_standard -= $dest['money33'];
					$dest['money33'] = 0;
					if($dest['money97'] >= $tmp_standard) {
						$dest['money97'] = $dest['money97'] - $tmp_standard;
					} else {
						$tmp_standard -= $dest['money97'];
						$dest['money97'] = 0;
						$dest['money168'] = round(($dest['money168'] - $tmp_standard/2), 2);
					}
				}
				
				$ztrees_data['status'] = 5;
				// 计算应得苹果数量
				$ztrees_total = 0;
				if($dest['money33'] >= 60000) { // 33天
					$ztrees_total += intval($dest['money33']/60000);
					$dest['money33'] -= intval($dest['money33']/60000)*60000;
				}
				if($dest['money97'] >= 30000) { // 97天
					$ztrees_total += intval($dest['money97']/30000);
					$dest['money97'] -= intval($dest['money97']/30000)*30000;
				}
				if($dest['money168'] >= 15000) { // 168天
					$ztrees_total += intval($dest['money168']/15000);
					$dest['money168'] -= intval($dest['money168']/15000)*15000;
				}
				$ztrees_data['t33'] = $dest['money33'];
				$ztrees_data['t97'] = $dest['money97'];
				$ztrees_data['t168'] = $dest['money168'];
				$ztrees_data['num'] = $ztrees_total;
				
			} else {
				// 金额小于十万
				$ztrees_data['num'] = 0;
				$ztrees_data['t33'] = 0;
				$ztrees_data['t97'] = 0;
				$ztrees_data['t168'] = 0;
				$ztrees_data['status'] = $this->get_ztrees_status($total, $standard);
			}
		}
		return $ztrees_data;
	}
	// 获取ztrees状态, 根据比率显示 1:<10%2:<30%3:<60%4:<100%5:>=100%
	private function get_ztrees_status($money, $standard) {
		if(round($money / $standard, 3) < 0.1) {
			return 1;
		}
		if(round($money / $standard, 3) < 0.3) {
			return 2;
		}
		if(round($money / $standard, 3) < 0.6) {
			return 3;
		}
		if(round($money / $standard, 3) < 1) {
			return 4;
		}
		if(round($money / $standard, 3) >= 1) {
			return 5;
		}
	}
	
	// 获取发财树记录
	public function ztrees() {
		$this->load->model('theme/ztrees_model');
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
        $config['base_url'] = base_url('ztreesa/ztrees');
        $config['total_rows'] = $this->ztrees_model->get_ztrees_nums($where);
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
        $ztrees = $this->ztrees_model->get_ztrees_lists($per_page, $per_page * $current_page, $where);
		// 已兑换数据
		$uids = array();
		if(!empty($ztrees)) {
			foreach($ztrees as $k=>$v) {
				// 已兑换苹果数量
				$ztrees[$k]['dh_red'] = 0;
				$ztrees[$k]['dh_gold'] = 0;
				array_push($uids, $v['uid']);
			}
			$orders = $this->ztrees_model->get_ztrees_order_all_byuids($uids);
		}
		// 有兑换数据
		$dh_data = array();
		if(!empty($orders)) {
			foreach($orders as $k=>$v) {
				$dh_total['red'] = 0;
				$dh_total['gold'] = 0;
				$dh_total['other'] = 0;
				switch($v['type']) {
					case 1: 
						$dh_total['red'] += 1;
					break;
					case 2:
						$dh_total['red'] += 2;
					break;
					case 3:
						$dh_total['red'] += 5;
					break;
					case 4:
						$dh_total['red'] += 10;
					break;
					case 5:
						$dh_total['red'] += 15;
					break;
					case 6:
						$dh_total['red'] += 20;
					break;
					case 7:
						$dh_total['gold'] += 1;
					break;
					case 8:
					case 9:
					case 10:
					case 11:
					case 12:
					case 13:
						$dh_total['other'] += 1;
				}
				$dh_data[$v['uid']]['red'] = isset($dh_data[$v['uid']]['red']) ? ($dh_data[$v['uid']]['red'] + $dh_total['red']) : $dh_total['red'];
				$dh_data[$v['uid']]['gold'] = isset($dh_data[$v['uid']]['gold']) ? ($dh_data[$v['uid']]['gold'] + $dh_total['gold']) : $dh_total['gold'];
				$dh_data[$v['uid']]['other'] = isset($dh_data[$v['uid']]['other']) ? ($dh_data[$v['uid']]['other'] + $dh_total['other']) : $dh_total['other'];
			}
		}
		foreach($ztrees as $k=>$v) {
			$ztrees[$k]['dh_red'] = 0;
			$ztrees[$k]['dh_gold'] = 0;
			$ztrees[$k]['dh_other'] = 0;
		}
		if(!empty($dh_data)) {
			foreach($dh_data as $k=>$v) {
				foreach($ztrees as $key=>$value) {
					if($k == $value['uid']) {
						$ztrees[$key]['dh_red'] += $v['red'];
						$ztrees[$key]['dh_gold'] += $v['gold'];
						$ztrees[$key]['dh_other'] += $v['other'];
					}
				}
			}
		}
		// foreach($ztrees as $k=>$v) {
			// $ztrees[$k]['red_num'] = $this->ztrees_model->get_detail_used_byuid($v['uid'], 0);
			// $ztrees[$k]['gold_num'] = $this->ztrees_model->get_detail_used_byuid($v['uid'], 1);
			// $ztrees[$k]['pear_num'] = $this->ztrees_model->get_detail_used_byuid($v['uid'], 8);
			// $ztrees[$k]['orange_num'] = $this->ztrees_model->get_detail_used_byuid($v['uid'], 9);
			// $ztrees[$k]['pitaya_num'] = $this->ztrees_model->get_detail_used_byuid($v['uid'], 10);
			// $ztrees[$k]['grape_num'] = $this->ztrees_model->get_detail_used_byuid($v['uid'], 11);
			// $ztrees[$k]['peach_num'] = $this->ztrees_model->get_detail_used_byuid($v['uid'], 12);
			// $ztrees[$k]['packe_num'] = $this->ztrees_model->get_detail_used_byuid($v['uid'], 13);
		// }
		$data['ztrees'] = $ztrees;
		//p($data);die;
		$this->load->view('theme/ztreesa', $data);
	}
	
	/** 发财树详情 */
	public function ztrees_detail() {
		$this->load->model('theme/ztrees_model');
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
		if(!empty($search['btime'])) {
			$data['btime'] = $search['btime'];
			$where['btime'] = explode(' ', $search['btime']);
		}
		$current_page = empty($this->uri->segment(3)) ? 0 : intval($this->uri->segment(3)) - 1;
		$per_page = 50;
        $offset = $current_page;
        $config['base_url'] = base_url('ztreesa/ztrees_detail');
        $config['total_rows'] = $this->ztrees_model->get_detail_nums($where);
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
        $detail = $this->ztrees_model->get_detail_lists($per_page, $per_page * $current_page, $where);
        $data['detail'] = $detail;
		$this->load->view('theme/ztrees_detail', $data);
	}
	
	/** 变更金苹果 */
	public function ztrees_change() {
		$id = intval($this->uri->segment(3));
		if(empty($id)) {
			$this->error('信息错误');
		}
		
		if(IS_POST) {
			$this->load->model('theme/ztrees_model');
			$post = $this->input->post(null, true);
			if(empty($post['value'])) {
				$this->error('备注不能为空');
			}
			
			// 判断是否满足变更的条件
			$detail = $this->ztrees_model->get_detail_byid($id);
			if($detail['used'] > 0) {
				$this->error('不满足变更条件');
			}
			
			if($detail['type'] > 0) {
				$this->error('不能重复操作');
			}
			
			// 手动发的红苹果，不能变更为金苹果
			if($detail['status'] > 0) {
				$this->error('手动发放的红苹果，不能变更为金苹果');
			}
			
			// 查询该用户所有概率值
			$sort = ceil($detail['sort']/100);
			$apple_rand = $this->ztrees_model->get_rand_byuid($detail['uid'], $sort);
			if(!empty($apple_rand)) {
				if(in_array($detail['sort'], explode(',', $apple_rand['randnum']))) {
					$this->error('该数据不能变更');
				}
			}
			
			// 组织数据
			$ztrees = $this->ztrees_model->get_ztrees_byuid($detail['uid']);
			$ztrees['num'] -= 1;
			$ztrees['gold'] += 1;
			$detail['remark'] = mb_substr($post['value'], 0, 255);
			$detail['status'] = 1;
			$detail['type'] = 1;
			$detail_data = [
				'detail_id' => $detail['id'],
				'adminid'	=> UID,
				'addtime' 	=> time()
			];
			$this->db->trans_begin();
			$this->ztrees_model->update_ztrees($ztrees);
			$this->ztrees_model->update_detail($detail);
			$this->ztrees_model->insert_detail_record($detail_data);
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$this->error('操作失败');
			} else {
				$this->db->trans_commit();
				$this->success('操作成功');
			}
		}
	}
	
	/** 发财树兑换 */
	public function ztrees_order() {
		$this->load->model('theme/ztrees_model');
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
        $config['base_url'] = base_url('ztreesa/ztrees_order');
        $config['total_rows'] = $this->ztrees_model->get_order_nums($where);
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
        $order = $this->ztrees_model->get_order_lists($per_page, $per_page * $current_page, $where);
        $data['order'] = $order;
		$data['tree_prize'] = array(
			'1' => 128, // '128元红包',
			'2' => 300, //'300元红包',
			'3' => 900, //'900元红包',
			'4' => 2100, //'2100元红包',
			'5' => 3700, //'3700元红包',
			'6' => 5800, //'5800元红包'
			'7' => 8800, // '8800元现金红包'
			'8' => 200,
			'9' => 220,
			'10' => 240,
			'11' => 260,
			'12' => 280,
			'13' => '388投资红包'
		);
		//p($data);die;
		$this->load->view('theme/ztrees_order', $data);
	}
	
	/** 发财树处理页面 */
	public function deal_ztrees_ui() {
		$id = $this->uri->segment(3);
		$data['id'] = intval($id);
		$this->load->view('/theme/deal_ztrees_ui', $data);
	}
	
	/** 发财树兑奖处理 */
	public function deal_ztrees_order() {
		$this->load->model('theme/ztrees_model');
		$post = $this->input->post(null, true);
		$id = intval($post['id']);
		$order = $this->ztrees_model->get_order_by_id($id);
		$order['puptime'] = time();
		$order['padmin_id'] = UID;
		$order['remark'] = mb_substr($post['remark'], 0, 200);
		$order['status'] = 1;
		if(!$this->ztrees_model->modify_order($order)) {
			$this->error('操作失败');
		} else {
			$this->success('操作成功');
		}
	}
	/** 发财树兑奖批量处理 */
	public function batch_ztrees_order() {
		$this->load->model('theme/ztrees_model');
		$post = $this->input->post(null, true);
		if(IS_POST) {
			$ids = explode(',', $post['ids']);
			$remark = $post['value'];
			$results = $this->ztrees_model->get_order_byids($ids);
			$timestamp = time();
			foreach($results as $k=>$v) {
				if($v['status'] > 0) {
					$this->error('部分数据已经处理过，请勿重复操作');
				}
				$results[$k]['puptime'] = $timestamp;
				$results[$k]['remark'] = $remark;
				$results[$k]['status'] = 1;
				$results[$k]['padmin_id'] = UID;
			}
			
			// 插入到数据表
			if(!$this->ztrees_model->batch_order($results)) {
				$this->error('操作失败');
			} else {
				$this->success('操作成功');
			}
		}
	}
	
	/** 发财树导出 */
	public function ztrees_export() {
		$this->load->model('theme/ztrees_model');
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
		
		$numbers = $this->ztrees_model->get_order_nums($where);
        $data['invest'] = $this->ztrees_model->get_order_lists($numbers, 0, $where);
		$data['tree_prize'] = array(
			'1' => 128, // '128元红包',
			'2' => 300, //'300元红包',
			'3' => 900, //'900元红包',
			'4' => 2100, //'2100元红包',
			'5' => 3700, //'3700元红包',
			'6' => 5800, //'5800元红包'
			'7' => 8800, // '8800元现金红包'
			'8' => 200,
			'9' => 220,
			'10' => 240,
			'11' => 260,
			'12' => 280,
			'13' => '388投资红包'
		);
		
		$all = $data['invest'];
		// 调取所有用户
		$all_users = array();
		foreach($all as $k=>$v) {
			if($v['padmin_id'] > 0) {
				array_push($all_users, $v['padmin_id']);
			}
		} 
		if(!empty($all_users)) {
			$all_users = array_unique($all_users);
			$all_users = $this->ztrees_model->get_ausers_byids($all_users);
			$all_users = array_column($all_users, 'realname', 'id');
		}
		$tree_prize = $data['tree_prize'];
		$this->load->helper('common');
		$this->load->library('PHPExcel');
		$resultPHPExcel = new PHPExcel();
		
		$resultPHPExcel->getActiveSheet()->setCellValue('A1', 'ID');
		$resultPHPExcel->getActiveSheet()->setCellValue('B1', '电话');
		$resultPHPExcel->getActiveSheet()->setCellValue('C1', '姓名');
		$resultPHPExcel->getActiveSheet()->setCellValue('D1', '状态');
		$resultPHPExcel->getActiveSheet()->setCellValue('E1', '现金红包');
		$resultPHPExcel->getActiveSheet()->setCellValue('F1', '时间');
		$resultPHPExcel->getActiveSheet()->setCellValue('G1', '处理时间');
		$resultPHPExcel->getActiveSheet()->setCellValue('H1', '操作人');
		$resultPHPExcel->getActiveSheet()->setCellValue('I1', '备注');
		$i = 1;
		foreach($all as $k => $v){
			$i++;
			$resultPHPExcel->getActiveSheet()->setCellValue('A'.$i, $v['id']);
			$resultPHPExcel->getActiveSheet()->setCellValueExplicit('B'.$i, $v['phone'], PHPExcel_Cell_DataType::TYPE_STRING);
			$resultPHPExcel->getActiveSheet()->setCellValue('C'.$i, $v['real_name']);
			$resultPHPExcel->getActiveSheet()->setCellValue('D'.$i, $v['status'] == 1 ? '已处理' : '待处理');
			$resultPHPExcel->getActiveSheet()->setCellValue('E'.$i, $tree_prize[$v['type']]);
			$resultPHPExcel->getActiveSheet()->setCellValue('F'.$i, date('Y-m-d H:i', $v['addtime']));
			$resultPHPExcel->getActiveSheet()->setCellValue('G'.$i, $v['puptime'] > 0 ? date('Y-m-d H:i', $v['puptime']) : '--');
			$resultPHPExcel->getActiveSheet()->setCellValue('H'.$i, $v['padmin_id'] > 0 ? $all_users[$v['padmin_id']] : '');
			$resultPHPExcel->getActiveSheet()->setCellValue('I'.$i, $v['remark']);
		}
		
		$outputFileName = '发财树订单数据.xls'; 
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
	
	/** 发财树发放明细 */
	public function ztrees_apple() {
		$this->load->model('theme/ztrees_model');
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
        $config['base_url'] = base_url('ztreesa/ztrees_apple');
        $config['total_rows'] = $this->ztrees_model->get_apple_nums($where);
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
        $apple = $this->ztrees_model->get_apple_lists($per_page, $per_page * $current_page, $where);
        $data['apple'] = $apple;
		$this->load->view('theme/ztrees_apple', $data);
	}
	
	/** 发财树发放明细导出 */
	public function ztrees_apple_export() {
		$this->load->model('theme/ztrees_model');
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
		$numbers = $this->ztrees_model->get_apple_nums($where);
        $data['invest'] = $this->ztrees_model->get_apple_lists($numbers, 0, $where);
		
		$all = $data['invest'];
		$this->load->helper('common');
		$this->load->library('PHPExcel');
		$resultPHPExcel = new PHPExcel();
		
		$resultPHPExcel->getActiveSheet()->setCellValue('A1', 'ID');
		$resultPHPExcel->getActiveSheet()->setCellValue('B1', '电话');
		$resultPHPExcel->getActiveSheet()->setCellValue('C1', '姓名');
		$resultPHPExcel->getActiveSheet()->setCellValue('D1', '发放水果数量');
		$resultPHPExcel->getActiveSheet()->setCellValue('E1', '投资日期');
		$resultPHPExcel->getActiveSheet()->setCellValue('F1', '发放日期');
		$i = 1;
		foreach($all as $k => $v){
			$i++;
			$resultPHPExcel->getActiveSheet()->setCellValue('A'.$i, $v['id']);
			$resultPHPExcel->getActiveSheet()->setCellValueExplicit('B'.$i, $v['phone'], PHPExcel_Cell_DataType::TYPE_STRING);
			$resultPHPExcel->getActiveSheet()->setCellValue('C'.$i, $v['real_name']);
			$resultPHPExcel->getActiveSheet()->setCellValue('D'.$i, $v['num']);
			$resultPHPExcel->getActiveSheet()->setCellValue('E'.$i, date('Y-m-d', $v['itime']));
			$resultPHPExcel->getActiveSheet()->setCellValue('F'.$i, date('Y-m-d H:i:s', $v['addtime']));
		}
		
		$outputFileName = '发放水果数据.xls'; 
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
	
	/** 发放红苹果 */
	public function ztrees_provide() {
		
		$uid = intval($this->uri->segment(3));
		if(empty($uid)) {
			$this->error('数据有误，请联系管理员');
		}
		if(IS_POST) {
			$this->load->model('theme/ztrees_model');
			$post = $this->input->post(null, true);
			$num = intval($post['value']);
			if(empty($num)) {
				$this->error('发放苹果数量不能为空');
			}
			if($num > 100) {
				$this->error('不允许一次发放苹果数量超过100个');
			}
			$this->db->trans_begin();
			$ztrees = $this->ztrees_model->get_ztrees_byuid($uid);
			if(empty($ztrees) || $ztrees['status'] != 5) {
				$this->error('还未成长为树');
			}
			$detail_data = $this->set_ztrees_detail($num, $uid);
			foreach($detail_data as $k=>$v) {
				$detail_data[$k]['status'] = 2;
				$detail_data[$k]['remark'] = date('Y-m-d H:i:s').'客服手动发放水果';
			}
			$this->ztrees_model->batch_insert_detail($detail_data);
			$ztrees['num'] += $num;
			$ztrees['uptime'] = time();
			// 修改数据
			$this->ztrees_model->update_ztrees($ztrees);
			
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$this->error('操作失败');
			} else {
				$this->db->trans_commit();
				$this->success('操作成功');
			}
		}
	}
}