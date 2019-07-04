<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Zreversal_model extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->database();
	}
	
	/** 记录更改规则 */
	public function insert_change($data) {
		return $this->db->insert('zreversal_changerule', $data);
	}
	
	/** 更新规则表 */
	public function update_rule($data) {
		return $this->db->where(array('id'=>$data['id']))->update('zreversal_rule', $data);
	}
	
	/** 获取总概率 */
	public function get_total_rate() {
		return $this->db->select_sum('rat')->get('zreversal_rule')->row_array();
	}
	
	/** 根据ID查询详情表 */
	public function get_detail_byid($id) {
		return $this->db->where(array('id'=>$id))->get('zreversal_detail')->row_array();
	}
	
	/** 前端，查询订单总数 */
	public function get_before_order_nums($uid) {
		$master = 'zreversal_detail';
		$this->db->where('uid', $uid);
		$this->db->where('status', 1);
		$this->db->from($master);
		return $this->db->count_all_results();
	}
	/** 前端，查询订单详情 */
	public function get_before_order_lists($page, $per_page, $uid) {
		$master = 'zreversal_detail';
		$this->db->where('uid', $uid);
		$this->db->where('status', 1);
		$this->db->from($master);
		$this->db->limit($page, $per_page);
		$this->db->order_by("otime asc, id desc");
		return $this->db->get()->result_array();
	}
	
	/** 更新详情表 */
	public function update_detail($data) {
		return $this->db->where(array('id'=>$data['id']))->update('zreversal_detail', $data);
	}
	/** 统计商品的数量 */
	public function get_product_nums() {
		return $this->db->where(array('status'=>0))->count_all_results('zreversal_product');
	}
	/** 查询商品表 */
	public function get_product_all() {
		return $this->db->where(array('status'=>0))->get('zreversal_product')->result_array();
	}
	
	/** 根据ID查询规则 */
	public function get_rule_byid($id) {
		return $this->db->where(array('id'=>$id))->get('zreversal_rule')->row_array();
	}
	
	/** 根据ID调取商品信息 */
	public function get_product_byid($id) {
		return $this->db->where(array('id'=>$id))->get('zreversal_product')->row_array();
	}
	/** 插入一条详情表 */
	public function insert_detail($data) {
		return $this->db->insert('zreversal_detail', $data);
	}
	
	/** 根据uid查询最新一条详情 */
	public function get_detail_byuid($uid) {
		$this->db->order_by('id desc');
		return $this->db->where(array('uid'=>$uid))->get('zreversal_detail')->row_array();
	}
	
	/** 插叙规则总数 */
	public function get_rules_nums() {
		return $this->db->where(array('status'=>0))->count_all_results('zreversal_rule');
	}
	/** 调取所有规则 */
	public function get_rules() {
		return $this->db->where(array('status'=>0))->get('zreversal_rule')->result_array();
	}
	
	/** 记录总数 */
	public function get_zreversal_nums($where) {
		$master = 'zreversal';
		$sub = 'members_info';
		if(!empty($where)) {
			$this->db->like("concat(xm_{$sub}.uid,xm_{$sub}.real_name,xm_{$sub}.phone)", $where['name']);
		}
		$this->db->from($master);
		$this->db->join($sub, "$master.uid = $sub.uid", 'left');
		return $this->db->count_all_results();
	}
	
	/** 记录列表 */
	public function get_zreversal_lists($page, $per_page, $where) {
		$master = 'zreversal';
		$sub = 'members_info';
		if(!empty($where)) {
			$this->db->like("concat(xm_{$sub}.uid,xm_{$sub}.real_name,xm_{$sub}.phone)", $where['name']);
		}
		$this->db->from($master);
		$this->db->limit($page, $per_page);
		$this->db->join($sub, "$master.uid = $sub.uid", 'left');
		return $this->db->get()->result_array();
	}
	
	// 以下是导入excel表格用到的sql语句
	
	/** 修改一条数据 */
	public function update_zreversal($data) {
		return $this->db->where(array('id'=>$data['id']))->update('zreversal', $data);
	}
	/** 插入一条新数据 */
	public function insert_zreversal($data) {
		return $this->db->insert('zreversal', $data);
	}
	
	/** 根据用户UID查询账户数据  */
	public function get_zreversal_byuid($uid) {
		return $this->db->where(array('uid'=>$uid))->get('zreversal')->row_array();
	}
	
	/** 根据IDS设置导入数据的使用状态 */
	public function update_data_byids($where) {
		return $this->db->where_in('id', $where)->update('zreversal_data', array('used'=>1));
	}
	
	/** 查询所有导入的数据 */
	public function get_data_byused() {
		return $this->db->select('id, uid, money, duration, itime')->where(array('used'=>0, 'status'=>1))->get('zreversal_data')->result_array();
	}
	
	/** 导入数据的分页显示 */
	public function get_zreversal_data_lists($page, $per_page, $where) {
		if(!empty($where['name'])) {
			$this->db->like("name", $where['name']);
		}
		$this->db->limit($page, $per_page);
		$this->db->order_by('id desc');
		return $this->db->get('zreversal_data')->result_array();
	}
	
	/** 导入数据的总数量 */
	public function get_zreversal_data_nums($where) {
		if(!empty($where['name'])) {
			$this->db->like("name", $where['name']);
		}
		return $this->db->count_all_results('zreversal_data');
	}
	
	/** 批量插入导入的数据 */
	public function insert_zreversal_data($data) {
		$this->db->trans_begin();
		
		$this->db->insert_batch('zreversal_data', $data, true, 1000);
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return false;
		} else {
			$this->db->trans_commit();
			return true;
		}
	}
	
	/** 根据身份证号查询uid */
	public function get_uid_byidcard($data) {
		return $this->db->select('uid, idcard')->where_in('idcard', $data)->get('members_info')->result_array();
	}
	
}