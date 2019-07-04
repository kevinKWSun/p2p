<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Ztrees_model extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->database();
	}
	
	/** 发放苹果记录数量 */
	public function get_change_record_nums($where) {
		$master = 'ztrees_change';
		$sub = 'ztrees_detail';
		$sub2 = 'members_info';
		
		if(!empty($where['name'])) {
			$this->db->like("concat(xm_{$sub2}.uid,xm_{$sub2}.real_name,xm_{$sub2}.phone)", $where['name']);
		}
		if(isset($where['time']) && !empty($where['time'])) {
			$this->db->where("$master.addtime between ". strtotime($where['time'][0]) . " and ". (strtotime($where['time'][2]) + 86399));
		}
		$this->db->from($master);
		$this->db->join($sub, "$master.detail_id = $sub.id", 'left');
		$this->db->join($sub2, "{$sub}.uid = $sub2.uid", 'left');
		return $this->db->count_all_results();
	}
	
	/** 发放苹果记录列表 */
	public function get_change_record_lists($page, $per_page, $where) {
		$master = 'ztrees_change';
		$sub = 'ztrees_detail';
		$sub2 = 'members_info';
		$sub3 = 'user';
		if(!empty($where['name'])) {
			$this->db->like("concat(xm_{$sub2}.uid,xm_{$sub2}.real_name,xm_{$sub2}.phone)", $where['name']);
		}
		if(isset($where['time']) && !empty($where['time'])) {
			$this->db->where("$master.addtime between ". strtotime($where['time'][0]) . " and ". (strtotime($where['time'][2]) + 86399));
		}
		$this->db->select("$master.*, $sub2.real_name, $sub2.phone, $sub2.uid, $sub3.realname");
		$this->db->from($master);
		$this->db->limit($page, $per_page);
		$this->db->order_by("$master.id", "desc");
		$this->db->join($sub, "$master.detail_id = $sub.id", 'left');
		$this->db->join($sub2, "{$sub}.uid = $sub2.uid", 'left');
		$this->db->join($sub3, "{$master}.adminid = $sub3.id", 'left');
		return $this->db->get()->result_array();
	}
	
	/** 新增变更数据 */
	public function insert_change($data) {
		return $this->db->insert('ztrees_change', $data);
	}
	
	/** 根据uid和类型，查询订单 使用情况 */
	public function get_order_by_uid_type($uid, $types) {
		$this->db->where('uid', $uid);
		$this->db->where_in('type', $types);
		return $this->db->select('type')->get('ztrees_order')->result_array();
	}
	
	/** 获取苹果最大数字 */
	public function get_apple_max($uid) {
		return $this->db->where(array('uid'=>$uid, 'used'=>1,'type'=>0))->order_by('sort desc')->get('ztrees_detail')->row_array();
	}
	
	/** 根据水果类型查询已收割水果总数 */
	public function get_detail_used_byuid($uid, $type) {
		$this->db->where('uid', $uid);
		$this->db->where('used', 1);
		$this->db->where('type', $type);
		return $this->db->count_all_results('ztrees_detail');
	}
	
	/** 更新其他水果的概率 */
	public function update_rand($data) {
		return $this->db->where(array('id'=>$data['id']))->update('ztrees_rand', $data);
	}
	
	/** 根据用户UID 修改排序 */
	public function set_ztrees_ssort($uid) {
		return $this->db->where(array('uid'=>$uid))->set('ssort', 'sort', FALSE)->update('ztrees_detail');
	}
	/** 根据用户UIDs查询所有订单数据 */
	public function get_ztrees_order_all_byuids($uids) {
		return $this->db->select('uid, type')->where_in('uid', $uids)->get('ztrees_order')->result_array();
	}
	
	/** 根据用户UID查询所有订单数据 */
	public function get_ztrees_order_all($uid) {
		return $this->db->select('type')->where('uid', $uid)->get('ztrees_order')->result_array();
	}
	
	/** 订单数量,前端显示 */
	public function get_ztrees_order_num($uid) {
		$master = 'ztrees_order';
		$this->db->where('uid', $uid);
		$this->db->from($master);
		//$this->db->join($sub, "$master.uid = $sub.uid", 'left');
		return $this->db->count_all_results();
	}
	
	/** 订单列表,前端显示 */
	public function get_ztrees_order_list($page, $per_page, $uid) {
		$master = 'ztrees_order';
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
		$master = 'ztrees_apple';
		$sub = 'members_info';
		if(!empty($where['name'])) {
			$this->db->like("concat(xm_{$sub}.uid,xm_{$sub}.real_name,xm_{$sub}.phone)", $where['name']);
		}
		if(isset($where['time']) && !empty($where['time'])) {
			$this->db->where('ztrees_apple.itime between '. strtotime($where['time'][0]) . ' and '. (strtotime($where['time'][2]) + 86399));
		}
		$this->db->from($master);
		$this->db->join($sub, "$master.uid = $sub.uid", 'left');
		return $this->db->count_all_results();
	}
	
	/** 发放苹果记录列表 */
	public function get_apple_lists($page, $per_page, $where) {
		$master = 'ztrees_apple';
		$sub = 'members_info';
		if(!empty($where['name'])) {
			$this->db->like("concat(xm_{$sub}.uid,xm_{$sub}.real_name,xm_{$sub}.phone)", $where['name']);
		}
		if(isset($where['time']) && !empty($where['time'])) {
			$this->db->where('ztrees_apple.itime between '. strtotime($where['time'][0]) . ' and '. (strtotime($where['time'][2]) + 86399));
		}
		$this->db->from($master);
		$this->db->limit($page, $per_page);
		$this->db->order_by('id desc');
		$this->db->join($sub, "$master.uid = $sub.uid", 'left');
		return $this->db->get()->result_array();
	}
	
	/** 插入投资日发放苹果总数量 */
	public function insert_apple($data) {
		$this->db->insert('ztrees_apple', $data);
	}
	/** 根据uid调取所有管理员 */
	public function get_ausers_byids($ids) {
		return $this->db->where_in('id', $ids)->get('user')->result_array();
	}
	
	/** 根据id查询多条订单信息 */
	public function get_order_byids($ids) {
		$this->db->where_in('id', $ids);
		return $this->db->get('ztrees_order')->result_array();
	}
	
	/** 修改一条数据 */
	public function modify_order($data) {
		return $this->db->where('id', $data['id'])->update('ztrees_order', $data);
	}
	
	/** 批量订单表修改 */
	public function batch_order($data) {
		return $this->db->update_batch('ztrees_order', $data, 'id');
	}
	
	/** 获取一条订单信息 */
	public function get_order_by_id($id) {
		return $this->db->where(array('id'=>$id))->get('ztrees_order')->row_array();
	}
	
	/** 订单数量 */
	public function get_order_nums($where) {
		$master = 'ztrees_order';
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
		$master = 'ztrees_order';
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
		return $this->db->insert('ztrees_detail_record', $data);
	} 
	
	/** 根据ID查询一条 detail数据 */
	public function get_detail_byid($id) {
		return $this->db->where(array('id'=>$id))->get('ztrees_detail')->row_array();
	}
	
	/** 详情数量 */
	public function get_detail_nums($where) {
		$master = 'ztrees_detail';
		$sub = 'members_info';
		if(!empty($where['name'])) {
			$this->db->like("concat(xm_{$sub}.uid,xm_{$sub}.real_name,xm_{$sub}.phone)", $where['name']);
		}
		if(isset($where['time']) && !empty($where['time'])) {
			$this->db->where('ztrees_detail.uptime between '. strtotime($where['time'][0]) . ' and '. (strtotime($where['time'][2]) + 86399));
		}
		if(isset($where['btime']) && !empty($where['btime'])) {
			$this->db->where("$master.addtime between ". strtotime($where['btime'][0]) . " and ". (strtotime($where['btime'][2]) + 86399));
		}
		$this->db->from($master);
		$this->db->join($sub, "$master.uid = $sub.uid", 'left');
		return $this->db->count_all_results();
	}
	
	/** 详情 */
	public function get_detail_lists($page, $per_page, $where) {
		$master = 'ztrees_detail';
		$sub = 'members_info';
		if(!empty($where['name'])) {
			$this->db->like("concat(xm_{$sub}.uid,xm_{$sub}.real_name,xm_{$sub}.phone)", $where['name']);
		}
		if(isset($where['time']) && !empty($where['time'])) {
			$this->db->where('ztrees_detail.uptime between '. strtotime($where['time'][0]) . ' and '. (strtotime($where['time'][2]) + 86399));
		}
		if(isset($where['btime']) && !empty($where['btime'])) {
			$this->db->where("$master.addtime between ". strtotime($where['btime'][0]) . " and ". (strtotime($where['btime'][2]) + 86399));
		}
		$this->db->from($master);
		$this->db->limit($page, $per_page);
		$this->db->order_by('uptime desc, id desc');
		$this->db->join($sub, "$master.uid = $sub.uid", 'left');
		return $this->db->get()->result_array();
	}
	
	/** 发财树记录总数 */
	public function get_ztrees_nums($where) {
		$master = 'ztrees';
		$sub = 'members_info';
		if(!empty($where)) {
			$this->db->like("concat(xm_{$sub}.uid,xm_{$sub}.real_name,xm_{$sub}.phone)", $where['name']);
		}
		$this->db->from($master);
		$this->db->join($sub, "$master.uid = $sub.uid", 'left');
		return $this->db->count_all_results();
	}
	
	/** 发财树记录 */
	public function get_ztrees_lists($page, $per_page, $where) {
		$master = 'ztrees';
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
		return $this->db->where(array('uid'=>$uid, 'type'=>7))->count_all_results('ztrees_order');
	}
	
	// 根据uid查询红苹果订单数量
	public function get_red_ordernum_byuid($uid, $type) {
		$this->db->where('uid', $uid);
		$this->db->where('type', $type);
		return $this->db->count_all_results('ztrees_order');
	}
	
	// 新增一条订单数据
	public function insert_data($data) {
		return $this->db->insert('ztrees_order', $data);
	}
	
	// 修改苹果详情记录
	public function update_detail($data) {
		return $this->db->where(array('id'=>$data['id']))->update('ztrees_detail', $data);
	}
	
	// 插入一条随即表数据
	public function insert_rand($data) {
		return $this->db->insert('ztrees_rand', $data);
	}
	
	// 查询已经存在的金苹果
	public function get_detail_byrand($uid, $rand) {
		$this->db->where('uid', $uid);
		$this->db->where('used', 0);
		$this->db->where('type', 1);
		$this->db->where_in('ssort', $rand);
		return $this->db->select('ssort')->get('ztrees_detail')->result_array();
	}
	
	// 根据uid 和参数，查询一条概率表
	public function get_rand_byuid($uid, $sort) {
		return $this->db->where(array('uid'=>$uid, 'sort'=>$sort))->get('ztrees_rand')->row_array();
	}
	
	// 根据uid查询20条详情数据
	public function get_detail_byuid20($uid) {
		return $this->db->where(array('uid'=>$uid, 'used'=>0))->order_by('ssort asc')->limit(13, 0)->get('ztrees_detail')->result_array();
	}
	
	// 批量插入详情
	public function batch_insert_detail($data) {
		$this->db->insert_batch('ztrees_detail', $data, true, 1000);
	}
	
	// 根据UID获取一条detail数据
	public function get_detail_byuid($uid) {
		return $this->db->where(array('uid'=>$uid))->order_by('ssort desc')->get('ztrees_detail')->row_array();
	}
	
	// 根据IDS设置导入数据的使用状态
	public function update_data_byids($where) {
		return $this->db->where_in('id', $where)->update('ztrees_data', array('used'=>1));
	}
	
	// 修改一条数据
	public function update_ztrees($data) {
		return $this->db->where(array('id'=>$data['id']))->update('ztrees', $data);
	}
	// 插入一条新数据
	public function insert_ztrees($data) {
		return $this->db->insert('ztrees', $data);
	}
	
	// 根据用户UID查询账户数据 
	public function get_ztrees_byuid($uid) {
		return $this->db->where(array('uid'=>$uid))->get('ztrees')->row_array();
	}
	
	// 查询所有导入的数据
	public function get_data_byused() {
		return $this->db->select('id, uid, money, duration, itime')->where(array('used'=>0, 'status'=>1))->get('ztrees_data')->result_array();
	}
	
	// 根据身份证号查询uid
	public function get_uid_byidcard($data) {
		return $this->db->select('uid, idcard')->where_in('idcard', $data)->get('members_info')->result_array();
	}
	
	// 批量插入导入的数据
	public function insert_ztrees_data($data) {
		$this->db->trans_begin();
		
		$this->db->insert_batch('ztrees_data', $data, true, 1000);
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return false;
		} else {
			$this->db->trans_commit();
			return true;
		}
	}
	
	// 导入数据的总数量
	public function get_ztrees_data_nums($where) {
		if(!empty($where)) {
			$this->db->like("name", $where['name']);
		}
		return $this->db->count_all_results('ztrees_data');
	}
	
	// 导入数据的分页显示
	public function get_ztrees_data_lists($page, $per_page, $where) {
		if(!empty($where)) {
			$this->db->like("name", $where['name']);
		}
		$this->db->limit($page, $per_page);
		$this->db->order_by('id desc');
		return $this->db->get('ztrees_data')->result_array();
	}
}