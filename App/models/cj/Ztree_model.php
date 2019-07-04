<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Ztree_model extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->database();
	}
	/** 根据用户UIDs查询所有订单数据 */
	public function get_ztree_order_all_byuids($uids) {
		return $this->db->select('uid, type')->where_in('uid', $uids)->get('ztree_order')->result_array();
	}

	/** 根据用户UID查询所有订单数据 */
	public function get_ztree_order_all($uid) {
		return $this->db->select('type')->where('uid', $uid)->get('ztree_order')->result_array();
	}
	
	/** 订单数量,前端显示 */
	public function get_ztree_order_num($uid) {
		$master = 'ztree_order';
		$this->db->where('uid', $uid);
		$this->db->from($master);
		//$this->db->join($sub, "$master.uid = $sub.uid", 'left');
		return $this->db->count_all_results();
	}
	
	/** 订单列表,前端显示 */
	public function get_ztree_order_list($page, $per_page, $uid) {
		$master = 'ztree_order';
		//$sub = 'members_info';
		$this->db->where('uid', $uid);
		$this->db->from($master);
		$this->db->limit($page, $per_page);
		$this->db->order_by('status asc, id desc');
		//$this->db->join($sub, "$master.uid = $sub.uid", 'left');
		return $this->db->get()->result_array();
	}
	
	
	/** 发放苹果记录数量 */
	public function get_apple_nums($where) {
		$master = 'ztree_apple';
		$sub = 'members_info';
		if(!empty($where['name'])) {
			$this->db->like("concat(xm_{$sub}.uid,xm_{$sub}.real_name,xm_{$sub}.phone)", $where['name']);
		}
		if(isset($where['time']) && !empty($where['time'])) {
			$this->db->where('ztree_apple.itime between '. strtotime($where['time'][0]) . ' and '. (strtotime($where['time'][2]) + 86399));
		}
		$this->db->from($master);
		$this->db->join($sub, "$master.uid = $sub.uid", 'left');
		return $this->db->count_all_results();
	}
	
	/** 发放苹果记录列表 */
	public function get_apple_lists($page, $per_page, $where) {
		$master = 'ztree_apple';
		$sub = 'members_info';
		if(!empty($where['name'])) {
			$this->db->like("concat(xm_{$sub}.uid,xm_{$sub}.real_name,xm_{$sub}.phone)", $where['name']);
		}
		if(isset($where['time']) && !empty($where['time'])) {
			$this->db->where('ztree_apple.itime between '. strtotime($where['time'][0]) . ' and '. (strtotime($where['time'][2]) + 86399));
		}
		$this->db->from($master);
		$this->db->limit($page, $per_page);
		$this->db->order_by('id desc');
		$this->db->join($sub, "$master.uid = $sub.uid", 'left');
		return $this->db->get()->result_array();
	}
	
	/** 插入投资日发放苹果总数量 */
	public function insert_apple($data) {
		$this->db->insert('ztree_apple', $data);
	}
	/** 根据uid调取所有管理员 */
	public function get_ausers_byids($ids) {
		return $this->db->where_in('id', $ids)->get('user')->result_array();
	}
	
	/** 根据id查询多条订单信息 */
	public function get_order_byids($ids) {
		$this->db->where_in('id', $ids);
		return $this->db->get('ztree_order')->result_array();
	}
	
	/** 修改一条数据 */
	public function modify_order($data) {
		return $this->db->where('id', $data['id'])->update('ztree_order', $data);
	}
	
	/** 批量订单表修改 */
	public function batch_order($data) {
		return $this->db->update_batch('ztree_order', $data, 'id');
	}
	
	/** 获取一条订单信息 */
	public function get_order_by_id($id) {
		return $this->db->where(array('id'=>$id))->get('ztree_order')->row_array();
	}
	
	/** 订单数量 */
	public function get_order_nums($where) {
		$master = 'ztree_order';
		$sub = 'members_info';
		if(!empty($where)) {
			$this->db->like("concat(xm_{$sub}.uid,xm_{$sub}.real_name,xm_{$sub}.phone)", $where['name']);
		}
		$this->db->from($master);
		$this->db->join($sub, "$master.uid = $sub.uid", 'left');
		return $this->db->count_all_results();
	}
	
	/** 订单列表 */
	public function get_order_lists($page, $per_page, $where) {
		$master = 'ztree_order';
		$sub = 'members_info';
		if(!empty($where)) {
			$this->db->like("concat(xm_{$sub}.uid,xm_{$sub}.real_name,xm_{$sub}.phone)", $where['name']);
		}
		$this->db->from($master);
		$this->db->limit($page, $per_page);
		$this->db->order_by('status asc, id desc');
		$this->db->join($sub, "$master.uid = $sub.uid", 'left');
		return $this->db->get()->result_array();
	}
	
	/** 添加一条变更记录 */
	public function insert_detail_record($data) {
		return $this->db->insert('ztree_detail_record', $data);
	} 
	
	/** 根据ID查询一条 detail数据 */
	public function get_detail_byid($id) {
		return $this->db->where(array('id'=>$id))->get('ztree_detail')->row_array();
	}
	
	/** 详情数量 */
	public function get_detail_nums($where) {
		$master = 'ztree_detail';
		$sub = 'members_info';
		if(!empty($where['name'])) {
			$this->db->like("concat(xm_{$sub}.uid,xm_{$sub}.real_name,xm_{$sub}.phone)", $where['name']);
		}
		if(isset($where['time']) && !empty($where['time'])) {
			$this->db->where('ztree_detail.uptime between '. strtotime($where['time'][0]) . ' and '. (strtotime($where['time'][2]) + 86399));
		}
		$this->db->from($master);
		$this->db->join($sub, "$master.uid = $sub.uid", 'left');
		return $this->db->count_all_results();
	}
	
	/** 详情 */
	public function get_detail_lists($page, $per_page, $where) {
		$master = 'ztree_detail';
		$sub = 'members_info';
		if(!empty($where['name'])) {
			$this->db->like("concat(xm_{$sub}.uid,xm_{$sub}.real_name,xm_{$sub}.phone)", $where['name']);
		}
		if(isset($where['time']) && !empty($where['time'])) {
			$this->db->where('ztree_detail.uptime between '. strtotime($where['time'][0]) . ' and '. (strtotime($where['time'][2]) + 86399));
		}
		$this->db->from($master);
		$this->db->limit($page, $per_page);
		$this->db->order_by('uptime desc, id desc');
		$this->db->join($sub, "$master.uid = $sub.uid", 'left');
		return $this->db->get()->result_array();
	}
	
	/** 发财树记录总数 */
	public function get_ztree_nums($where) {
		$master = 'ztree';
		$sub = 'members_info';
		if(!empty($where)) {
			$this->db->like("concat(xm_{$sub}.uid,xm_{$sub}.real_name,xm_{$sub}.phone)", $where['name']);
		}
		$this->db->from($master);
		$this->db->join($sub, "$master.uid = $sub.uid", 'left');
		return $this->db->count_all_results();
	}
	
	/** 发财树记录 */
	public function get_ztree_lists($page, $per_page, $where) {
		$master = 'ztree';
		$sub = 'members_info';
		if(!empty($where)) {
			$this->db->like("concat(xm_{$sub}.uid,xm_{$sub}.real_name,xm_{$sub}.phone)", $where['name']);
		}
		$this->db->from($master);
		$this->db->limit($page, $per_page);
		$this->db->join($sub, "$master.uid = $sub.uid", 'left');
		return $this->db->get()->result_array();
	}
	
	// 根据uid查询金苹果订单数量
	public function get_gold_ordernum_byuid($uid) {
		return $this->db->where(array('uid'=>$uid, 'type'=>7))->count_all_results('ztree_order');
	}
	
	// 根据uid查询红苹果订单数量
	public function get_red_ordernum_byuid($uid, $type) {
		$this->db->where('uid', $uid);
		$this->db->where('type', $type);
		return $this->db->count_all_results('ztree_order');
	}
	
	// 新增一条订单数据
	public function insert_data($data) {
		return $this->db->insert('ztree_order', $data);
	}
	
	// 修改苹果详情记录
	public function update_detail($data) {
		return $this->db->where(array('id'=>$data['id']))->update('ztree_detail', $data);
	}
	
	// 插入一条随即表数据
	public function insert_rand($data) {
		return $this->db->insert('ztree_rand', $data);
	}
	
	// 查询已经存在的金苹果
	public function get_detail_byrand($uid, $rand) {
		$this->db->where('uid', $uid);
		$this->db->where('used', 0);
		$this->db->where('type', 1);
		$this->db->where_in('sort', $rand);
		return $this->db->select('sort')->get('ztree_detail')->result_array();
	}
	
	// 根据uid 和参数，查询一条概率表
	public function get_rand_byuid($uid, $sort) {
		return $this->db->where(array('uid'=>$uid, 'sort'=>$sort))->get('ztree_rand')->row_array();
	}
	
	// 根据uid查询20条详情数据
	public function get_detail_byuid20($uid) {
		return $this->db->where(array('uid'=>$uid, 'used'=>0))->order_by('sort asc')->limit(20, 0)->get('ztree_detail')->result_array();
	}
	
	// 批量插入详情
	public function batch_insert_detail($data) {
		$this->db->insert_batch('ztree_detail', $data, true, 1000);
	}
	
	// 根据UID获取一条detail数据
	public function get_detail_byuid($uid) {
		return $this->db->where(array('uid'=>$uid))->order_by('sort desc')->get('ztree_detail')->row_array();
	}
	
	// 根据IDS设置导入数据的使用状态
	public function update_data_byids($where) {
		return $this->db->where_in('id', $where)->update('ztree_data', array('used'=>1));
	}
	
	// 修改一条数据
	public function update_ztree($data) {
		return $this->db->where(array('id'=>$data['id']))->update('ztree', $data);
	}
	// 插入一条新数据
	public function insert_ztree($data) {
		return $this->db->insert('ztree', $data);
	}
	
	// 根据用户UID查询账户数据 
	public function get_ztree_byuid($uid) {
		return $this->db->where(array('uid'=>$uid))->get('ztree')->row_array();
	}
	
	// 查询所有导入的数据
	public function get_data_byused() {
		return $this->db->select('id, uid, money, duration, itime')->where(array('used'=>0, 'status'=>1))->get('ztree_data')->result_array();
	}
	
	// 根据身份证号查询uid
	public function get_uid_byidcard($data) {
		return $this->db->select('uid, idcard')->where_in('idcard', $data)->get('members_info')->result_array();
	}
	
	// 批量插入导入的数据
	public function insert_ztree_data($data) {
		$this->db->trans_begin();
		
		$this->db->insert_batch('ztree_data', $data, true, 1000);
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return false;
		} else {
			$this->db->trans_commit();
			return true;
		}
	}
	
	// 导入数据的总数量
	public function get_ztree_data_nums($where) {
		return $this->db->count_all_results('ztree_data');
	}
	
	// 导入数据的分页显示
	public function get_ztree_data_lists($page, $per_page, $where) {
		$this->db->limit($page, $per_page);
		$this->db->order_by('id desc');
		return $this->db->get('ztree_data')->result_array();
	}
	
}