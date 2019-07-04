<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Zoptimal_model extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->database();
	}
	
	/** 批量修改详情表 */
	public function batch_red($data) {
		return $this->db->update_batch('zoptimal_red', $data, 'id');
	}
	
	/** 根据IDS获取用户抽奖详情 */
	public function get_red_byids($ids) {
		$this->db->where_in('id', $ids);
		return $this->db->get('zoptimal_red')->result_array();
	}
	
	/** 判断是否是最后一笔红包 */
	public function is_last_red($rid) {
		return $this->db->where(array('rid'=>$rid, 'status'=>0))->count_all_results('zoptimal_red');
	}
	/** 更新红包 */
	public function update_red($data) {
		return $this->db->where(array('id'=>$data['id']))->update('zoptimal_red', $data);
	}
	/** 根据ID查询红包 */
	public function get_red_byid($id) {
		return $this->db->where(array('id'=>$id))->get('zoptimal_red')->row_array();
	}
	
	/** 批量修改详情 */
	public function batch_update_detail($data) {
		return $this->db->update_batch('zoptimal_detail', $data, 'id', 1000);
	}
	
	/** 批量插入红包 */
	public function batch_insert_red($data) {
		return $this->db->insert_batch('zoptimal_red', $data, true, 1000);
	}
	
	/** 查询所有需要拆分的红包 */
	public function get_red_all() {
		$master = 'zoptimal_detail';
		$sub = 'zoptimal_product';
		$this->db->from($master);
		$this->db->select("{$master}.*, {$sub}.name");
		$this->db->order_by("{$master}.id asc");
		$this->db->where(array('sp'=>0, "{$master}.column"=>2));
		$this->db->join($sub, "$master.pid = $sub.id", 'left');
		return $this->db->get()->result_array();
	}
	
	/** 宝箱红包详情 */
	public function get_red_lists($page, $per_page, $where) {
		$master = 'zoptimal_red';
		$sub = 'members_info';
		if(!empty($where['name'])) {
			$this->db->like("concat(xm_{$sub}.uid,xm_{$sub}.real_name,xm_{$sub}.phone)", $where['name']);
		}
		if(!empty($where['rid'])) {
			$this->db->where("rid", $where['rid']);
		}
		if(isset($where['time']) && !empty($where['time'])) {
			$this->db->where("{$master}.ftime between ". strtotime($where['time'][0]) . ' and '. (strtotime($where['time'][2]) + 86399));
		}
		$this->db->from($master);
		$this->db->select("{$master}.*, {$sub}.real_name, {$sub}.phone");
		$this->db->limit($page, $per_page);
		$this->db->order_by("status asc, {$master}.id asc");
		$this->db->join($sub, "$master.uid = $sub.uid", 'left');
		return $this->db->get()->result_array();
	}
	
	/** 宝箱红包总数 */
	public function get_red_nums($where) {
		$master = 'zoptimal_red';
		$sub = 'members_info';
		if(!empty($where['name'])) {
			$this->db->like("concat(xm_{$sub}.uid,xm_{$sub}.real_name,xm_{$sub}.phone)", $where['name']);
		}
		if(!empty($where['rid'])) {
			$this->db->where("rid", $where['rid']);
		}
		if(isset($where['time']) && !empty($where['time'])) {
			$this->db->where("{$master}.ftime between ". strtotime($where['time'][0]) . ' and '. (strtotime($where['time'][2]) + 86399));
		}
		$this->db->from($master);
		$this->db->join($sub, "$master.uid = $sub.uid", 'left');
		return $this->db->count_all_results();
	}
	
	/** 更新一条详情数据 */
	public function update_detail($data) {
		return $this->db->where(array('id'=>$data['id']))->update('zoptimal_detail', $data);
	}
	/** 获取一条抽奖数据 */
	public function get_detail_byid($id) {
		return $this->db->where(array('id'=>$id))->get('zoptimal_detail')->row_array();
	}
	
	/** 宝箱抽奖详情详情 */
	public function get_detail_lists($page, $per_page, $where) {
		$master = 'zoptimal_detail';
		$sub = 'members_info';
		$sub2 = 'zoptimal_product';
		if(!empty($where['name'])) {
			$this->db->like("concat(xm_{$sub}.uid,xm_{$sub}.real_name,xm_{$sub}.phone)", $where['name']);
		}
		if(isset($where['time']) && !empty($where['time'])) {
			$this->db->where("{$master}.addtime between ". strtotime($where['time'][0]) . ' and '. (strtotime($where['time'][2]) + 86399));
		}
		$this->db->from($master);
		$this->db->select("{$master}.*, {$sub}.real_name, {$sub}.phone, {$sub2}.name, {$sub2}.desc");
		$this->db->limit($page, $per_page);
		$this->db->order_by("{$master}.id desc");
		$this->db->join($sub, "$master.uid = $sub.uid", 'left');
		$this->db->join($sub2, "$master.pid = $sub2.id", 'left');
		return $this->db->get()->result_array();
	}
	
	/** 宝箱抽奖详情总数 */
	public function get_detail_nums($where) {
		$master = 'zoptimal_detail';
		$sub = 'members_info';
		$sub2 = 'zoptimal_product';
		if(!empty($where['name'])) {
			$this->db->like("concat(xm_{$sub}.uid,xm_{$sub}.real_name,xm_{$sub}.phone)", $where['name']);
		}
		if(isset($where['time']) && !empty($where['time'])) {
			$this->db->where("{$master}.addtime between ". strtotime($where['time'][0]) . ' and '. (strtotime($where['time'][2]) + 86399));
		}
		$this->db->from($master);
		$this->db->join($sub, "$master.uid = $sub.uid", 'left');
		$this->db->join($sub2, "$master.pid = $sub2.id", 'left');
		return $this->db->count_all_results();
	}
	
	/** 根据UID和行数 统计数量 */
	public function count_zoptimal_by_uid_round_num($uid, $round, $num) {
		return $this->db->where(array('uid'=>$uid, 'round'=>$round, 'num'=>$num))->count_all_results('zoptimal_detail');
	}
	/** 根据UID获取统计表中的数据 */
	public function get_number_byuid($uid) {
		return $this->db->where(array('uid'=>$uid))->get('zoptimal_number')->result_array();
	}
	
	/** 根据第几轮查询所有详情表 */
	public function get_detail_by_uid_round($uid, $round) {
		return $this->db->where(array('uid'=>$uid, 'round'=>$round))->get('zoptimal_detail')->result_array();
	}
	
	/** 根据第几轮统计详情表个数 */
	public function count_detail_by_uid_round($uid, $round) {
		return $this->db->where(array('uid'=>$uid, 'round'=>$round))->count_all_results('zoptimal_detail');
	}
	
	/** 插入一条统计数据 */
	public function update_number($data) {
		if(isset($data['id'])) {
			return $this->db->where(array('id'=>$data['id']))->update('zoptimal_number', $data);
		} else {
			return $this->db->insert('zoptimal_number', $data);
		}
	}
	
	/** 查询一条统计数据 */
	public function get_number_by_uid_num_column($uid, $num, $column) {
		return $this->db->where(array('uid'=>$uid, 'num'=>$num, 'column'=>$column))->get('zoptimal_number')->row_array();
	}
	
	/** 插入一条奖品数据 */
	public function insert_detail($data) {
		return $this->db->insert('zoptimal_detail', $data);
	}
	
	/** 获取商品信息 */
	public function get_product_byid($id) {
		return $this->db->where(array('id'=>$id))->get('zoptimal_product')->row_array();
	}
	
	/** 查询第几档，第几列，查出对应的奖品编号 */
	public function get_rand_by_num_column($num, $column) {
		return $this->db->where(array('num'=>$num, 'column'=>$column))->get('zoptimal_rand')->row_array();
	}
	
	/** 发财树记录总数 */
	public function get_zoptimal_nums($where) {
		$master = 'zoptimal';
		$sub = 'members_info';
		if(!empty($where)) {
			$this->db->like("concat(xm_{$sub}.uid,xm_{$sub}.real_name,xm_{$sub}.phone)", $where['name']);
		}
		$this->db->from($master);
		$this->db->join($sub, "$master.uid = $sub.uid", 'left');
		return $this->db->count_all_results();
	}
	
	/** 发财树记录 */
	public function get_zoptimal_lists($page, $per_page, $where) {
		$master = 'zoptimal';
		$sub = 'members_info';
		if(!empty($where)) {
			$this->db->like("concat(xm_{$sub}.uid,xm_{$sub}.real_name,xm_{$sub}.phone)", $where['name']);
		}
		$this->db->from($master);
		$this->db->limit($page, $per_page);
		$this->db->join($sub, "$master.uid = $sub.uid", 'left');
		return $this->db->get()->result_array();
	}
	
	// 修改一条数据
	public function update_zoptimal($data) {
		return $this->db->where(array('id'=>$data['id']))->update('zoptimal', $data);
	}
	// 插入一条新数据
	public function insert_zoptimal($data) {
		return $this->db->insert('zoptimal', $data);
	}
	
	// 根据IDS设置导入数据的使用状态
	public function update_data_byids($where) {
		return $this->db->where_in('id', $where)->update('zoptimal_data', array('used'=>1));
	}
	
	// 根据用户UID查询账户数据 
	public function get_zoptimal_byuid($uid) {
		return $this->db->where(array('uid'=>$uid))->get('zoptimal')->row_array();
	}
	
	// 查询所有导入的数据
	public function get_data_byused() {
		return $this->db->select('id, uid, money, duration, itime')->where(array('used'=>0, 'status'=>1))->get('zoptimal_data')->result_array();
	}
	
	// 导入数据的分页显示
	public function get_zoptimal_data_lists($page, $per_page, $where) {
		$this->db->limit($page, $per_page);
		$this->db->order_by('id desc');
		return $this->db->get('zoptimal_data')->result_array();
	}
	
	// 导入数据的总数量
	public function get_zoptimal_data_nums($where) {
		return $this->db->count_all_results('zoptimal_data');
	}
	
	// 批量插入导入的数据
	public function insert_zoptimal_data($data) {
		$this->db->trans_begin();
		
		$this->db->insert_batch('zoptimal_data', $data, true, 1000);
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return false;
		} else {
			$this->db->trans_commit();
			return true;
		}
	}
	
	// 根据身份证号查询uid
	public function get_uid_byidcard($data) {
		return $this->db->select('uid, idcard')->where_in('idcard', $data)->get('members_info')->result_array();
	}
}