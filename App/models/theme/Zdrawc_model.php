<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Zdrawc_model extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->database();
	}
	
	/** 批量修改详情表 */
	public function batch_detail($data) {
		return $this->db->update_batch('zdrawc_detail', $data, 'id');
	}
	
	/** 根据IDS获取用户抽奖详情 */
	public function get_detail_byids($ids) {
		$this->db->where_in('id', $ids);
		return $this->db->get('zdrawc_detail')->result_array();
	}
	/** 抽奖数量,前端显示 */
	public function get_zdrawc_order_num($uid) {
		$master = 'zdrawc_detail';
		$this->db->where('uid', $uid);
		$this->db->from($master);
		return $this->db->count_all_results();
	}
	
	/** 抽奖列表,前端显示 */
	public function get_zdrawc_order_list($page, $per_page, $uid) {
		$master = 'zdrawc_detail';
		$this->db->where('uid', $uid);
		$this->db->from($master);
		$this->db->limit($page, $per_page);
		$this->db->order_by('status asc, id desc');
		return $this->db->get()->result_array();
	}
	
	/** 获取未发放的总金额 */
	public function get_total_money($uid) {
		return $this->db->select_sum('money')->where(array('uid'=>$uid,'status'=>0))->get('zdrawc_detail')->row_array();
	}
	
	/** 更新一条详情数据 */
	public function update_detail($data) {
		return $this->db->where(array('id'=>$data['id']))->update('zdrawc_detail', $data);
	}
	/** 获取一条抽奖数据 */
	public function get_detail_byid($id) {
		return $this->db->where(array('id'=>$id))->get('zdrawc_detail')->row_array();
	}
	
	/** 518抽奖详情详情 */
	public function get_detail_lists($page, $per_page, $where) {
		$master = 'zdrawc_detail';
		$sub = 'members_info';
		if(!empty($where['name'])) {
			$this->db->like("concat(xm_{$sub}.uid,xm_{$sub}.real_name,xm_{$sub}.phone)", $where['name']);
		}
		if(isset($where['time']) && !empty($where['time'])) {
			$this->db->where("{$master}.addtime between ". strtotime($where['time'][0]) . ' and '. (strtotime($where['time'][2]) + 86399));
		}
		$this->db->from($master);
		$this->db->select("{$master}.*, {$sub}.real_name, {$sub}.phone");
		$this->db->limit($page, $per_page);
		$this->db->order_by("{$master}.id desc");
		$this->db->join($sub, "$master.uid = $sub.uid", 'left');
		return $this->db->get()->result_array();
	}
	
	/** 518抽奖详情总数 */
	public function get_detail_nums($where) {
		$master = 'zdrawc_detail';
		$sub = 'members_info';
		if(!empty($where['name'])) {
			$this->db->like("concat(xm_{$sub}.uid,xm_{$sub}.real_name,xm_{$sub}.phone)", $where['name']);
		}
		if(isset($where['time']) && !empty($where['time'])) {
			$this->db->where("{$master}.addtime between ". strtotime($where['time'][0]) . ' and '. (strtotime($where['time'][2]) + 86399));
		}
		$this->db->from($master);
		$this->db->join($sub, "$master.uid = $sub.uid", 'left');
		return $this->db->count_all_results();
	}
	
	/** 插入一条奖品数据 */
	public function insert_detail($data) {
		return $this->db->insert('zdrawc_detail', $data);
	}
	
	/** 发财树记录总数 */
	public function get_zdrawc_nums($where) {
		$master = 'zdrawc';
		$sub = 'members_info';
		if(!empty($where)) {
			$this->db->like("concat(xm_{$sub}.uid,xm_{$sub}.real_name,xm_{$sub}.phone)", $where['name']);
		}
		$this->db->from($master);
		$this->db->join($sub, "$master.uid = $sub.uid", 'left');
		return $this->db->count_all_results();
	}
	
	/** 发财树记录 */
	public function get_zdrawc_lists($page, $per_page, $where) {
		$master = 'zdrawc';
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
	public function update_zdrawc($data) {
		return $this->db->where(array('id'=>$data['id']))->update('zdrawc', $data);
	}
	// 插入一条新数据
	public function insert_zdrawc($data) {
		return $this->db->insert('zdrawc', $data);
	}
	
	// 根据IDS设置导入数据的使用状态
	public function update_data_byids($where) {
		return $this->db->where_in('id', $where)->update('zdrawc_data', array('used'=>1));
	}
	
	// 根据用户UID查询账户数据 
	public function get_zdrawc_byuid($uid) {
		return $this->db->where(array('uid'=>$uid))->get('zdrawc')->row_array();
	}
	
	// 查询所有导入的数据
	public function get_data_byused() {
		return $this->db->select('id, uid, money, duration, itime')->where(array('used'=>0, 'status'=>1))->get('zdrawc_data')->result_array();
	}
	
	// 导入数据的分页显示
	public function get_zdrawc_data_lists($page, $per_page, $where) {
		if(!empty($where['name'])) {
			$this->db->like("name", $where['name']);
		}
		$this->db->limit($page, $per_page);
		$this->db->order_by('id desc');
		return $this->db->get('zdrawc_data')->result_array();
	}
	
	// 导入数据的总数量
	public function get_zdrawc_data_nums($where) {
		if(!empty($where['name'])) {
			$this->db->like("name", $where['name']);
		}
		return $this->db->count_all_results('zdrawc_data');
	}
	
	// 批量插入导入的数据
	public function insert_zdrawc_data($data) {
		$this->db->trans_begin();
		
		$this->db->insert_batch('zdrawc_data', $data, true, 1000);
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