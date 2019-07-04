<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Zreversala extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->library('pagination');
		$this->load->database();
		$this->load->model(array('theme/zreversal_model','member/member_model'));
	}
	
	/** 修改概率 */
	public function change_rat() {
		$id = intval($this->uri->segment(3));
		if(empty($id)) {
			$this->error('信息错误');
		}
		
		if(IS_POST) {
			$post = $this->input->post(null, true);
			$post['value'] = floatVal($post['value']);
			if(empty($post['value'])) {
				$this->error('概率值不能为空');
			}
			
			// 判断是否满足变更的条件
			$rule = $this->zreversal_model->get_rule_byid($id);
			if($rule['status'] == 1) {
				$this->error('已禁用，不能修改');
			}
			if($post['value'] >= 1) {
				$this->error('请输入小于1的数字');
			}
			$a = explode('.', $post['value']);
			if(strlen($a[1]) > 4) {
				$this->error('最多四位小数');
			}
			
			// 组织数据
			$change = array(
				'rid' => $rule['id'],
				'rat_before' => $rule['rat'],
				'addtime' => time(),
				'adminid' => UID
			);
			$rule['rat'] = $post['value'];
			$rule['adminid'] = UID;
			$rule['uptime'] = time();
			
			if($this->zreversal_model->update_rule($rule)) {
				$this->zreversal_model->insert_change($change);
				$this->success('操作成功');
			} else {
				$this->error('操作失败');
			}
		}
	}
	
	/** 测试概率 */
	public function test() {
		$total = 100000;
		$arr = array();
		$counts = $this->zreversal_model->get_rules_nums();
		$rules = $this->zreversal_model->get_rules();
		for($i = 0; $i < $total; $i++) {
			$random = $this->get_prize($counts, $rules);
			$arr[$random] = isset($arr[$random]) ? ($arr[$random] + 1) : 1;
		}
		
		$arrs = array();
		foreach($arr as $k=>$v) {
			//echo $k, ': ', $v/$total, ' 个数： ', $v, '<br />';
			foreach($rules as $key=>$value) {
				if($k == $value['id']) {
					$arrs[$value['desc']] = isset($arrs[$value['desc']]) ? $arrs[$value['desc']] + $v : $v;
				}
			}
		}
		echo '测试值：100000次<br />';
		foreach($arrs as $k=>$v) {
			echo $k, '======= 概率: ', ($v/$total)*100, '%, 个数: ', $v, '<br />';
		}
	}
	
	/** 返回奖品列 */
	private function get_prize($counts, $rules) {
		// 比率 1:1000
		$ratio = 10000*$counts;
		// 得到概率值
		$rand_num = $this->create_rand($ratio);
		$rate = 0;
		foreach($rules as $k=>$v) {
			$rate += $v['rat']*$ratio;
			if($rand_num <= $rate) {
				return $v['id'];
			}
		}
	}
	
	/** 生成随机数 */
	private function create_rand($ratio) {
		return mt_rand(1, $ratio);
	}
	
	
	/** 总概率 */
	public function total_rate() {
		$total = $this->zreversal_model->get_total_rate();
		$this->success($total['rat']*100);
	}
	
	/** 概率列表 */
	public function prob() {
		$data = array();
        $rule = $this->zreversal_model->get_rules();
		$data['rule'] = $rule;
		$this->load->view('theme/zreversal/prob', $data);
	}
	
	/** 翻牌活动记录 */
	public function zreversal() {
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
        $config['base_url'] = base_url('zreversala/zreversal');
        $config['total_rows'] = $this->zreversal_model->get_zreversal_nums($where);
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
        $zreversal = $this->zreversal_model->get_zreversal_lists($per_page, $per_page * $current_page, $where);
		$data['zreversal'] = $zreversal;
		$this->load->view('theme/zreversal/zreversal', $data);
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
        $config['base_url'] = base_url('zreversala/importui');
        $config['total_rows'] = $this->zreversal_model->get_zreversal_data_nums($where);
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
        $zreversal = $this->zreversal_model->get_zreversal_data_lists($per_page, $per_page * $current_page, $where);
        $data['zreversal'] = $zreversal;
		$this->load->view('theme/zreversal/importui', $data);
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
			$filePath = dirname(BASEPATH) . '/code/zreversal.'.$suffix;
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
			$res_idcards = $this->zreversal_model->get_uid_byidcard($idcards);
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
				$res_return = $this->zreversal_model->insert_zreversal_data($data);
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
			$this->zreversal_dao_data();
			
		}
	}
	// 设置对应金的抽奖档
	protected function zreversal_dao_data() {
		// 导入的数据进行处理，
		$dr_data = $this->zreversal_model->get_data_byused();
		// 组织数据
		$zreversal_ = array();
		$used_data = array();
		$zreversal_time = 0;
		foreach($dr_data as $k=>$v) {
			// 判断是否设置过这个用户
			if(isset($zreversal_[$v['uid']])) {
				// 设置过该用户
				if(isset($zreversal_[$v['uid']]['money'.$v['duration']])) {
					// 设置过该金额
					$zreversal_[$v['uid']]['money'.$v['duration']] += $v['money'];
				} else {
					$zreversal_[$v['uid']]['money'.$v['duration']] = $v['money'];
				}
			} else {
				// 没有设置过该用户
				$zreversal_[$v['uid']]['uid'] = $v['uid'];
				$zreversal_[$v['uid']]['money'.$v['duration']] = $v['money'];
			}
			$zreversal_time = $v['itime'];
			array_push($used_data, $v['id']);
		}
		unset($dr_data);
		// 查询每个用户的账户
		$zreversal_data = array();
		$this->db->trans_begin();
		foreach($zreversal_ as $k=>$v) {
			// 根据uid查询用户的数据
			$zreversal = array();
			$zreversal = $this->zreversal_model->get_zreversal_byuid($k);
			if(!isset($v['money45'])) {
				$v['money45'] = 0;
			}
			if(!isset($v['money75'])) {
				$v['money75'] = 0;
			}
			if(!empty($zreversal)) {
				$zreversal_data = $this->get_zreversal_data($zreversal, $v);
				$zreversal_data['total'] = $zreversal_data['num'] + $zreversal['total'];
				$zreversal_data['num'] = $zreversal_data['num'] + $zreversal['num'];
				$this->zreversal_model->update_zreversal($zreversal_data);
			} else {
				$zreversal_data = $this->get_zreversal_data(array(), $v);
				$zreversal_data['total'] = $zreversal_data['num'];
				$this->zreversal_model->insert_zreversal($zreversal_data);
			}
			//p($zreversal_data);
		}
		//die; 
		// 将使用过的数据标记处理
		if(!empty($used_data)) {
			$this->zreversal_model->update_data_byids($used_data);
		}
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$this->error('操作失败');
		} else {
			$this->db->trans_commit();
			$this->success('操作成功');
		}
	}
	// 获取插入zreversal表的数据, $dest是本次投资的金额
	private function get_zreversal_data($source, $dest) {
		$zreversal_data = array();
		if(!empty($source)) {
			// 已有投资数据
			$zreversal_data['id'] = $source['id'];
			$zreversal_data['uid'] = $source['uid'];
			$zreversal_data['money45'] = $dest['money45'] + $source['money45'];
			$zreversal_data['money75'] = $dest['money75'] + $source['money75'];
			$zreversal_data['t45'] = $dest['money45'] + $source['t45'];
			$zreversal_data['t75'] = $dest['money75'] + $source['t75'];
			$zreversal_data['uptime'] = time();
		} else {
			// 没有投资数据
			$zreversal_data['uid'] = $dest['uid'];
			$zreversal_data['money45'] = $dest['money45'];
			$zreversal_data['money75'] = $dest['money75'];
			$zreversal_data['t45'] = $dest['money45'];
			$zreversal_data['t75'] = $dest['money75'];
			$zreversal_data['addtime'] = time();
		}
		// 活动逻辑
		$zreversal_total = 0;
		if($zreversal_data['t45'] >= 30000) { // 80天
			$zreversal_total += intval($zreversal_data['t45']/30000);
			$zreversal_data['t45'] = $zreversal_data['t45'] - intval($zreversal_data['t45']/30000)*30000;
		}
		if($zreversal_data['t75'] >= 20000) { // 130天
			$zreversal_total += intval($zreversal_data['t75']/20000);
			$zreversal_data['t75'] = $zreversal_data['t75'] - intval($zreversal_data['t75']/20000)*20000;
		}
		$zreversal_data['num'] = $zreversal_total;
		return $zreversal_data;
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