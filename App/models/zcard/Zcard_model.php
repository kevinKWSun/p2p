<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Zcard_model extends CI_Model{
	public function __construct() {
        parent::__construct();
        $this->load->database();
    }
	
	/** 根据用户ID查询一条用户数据 */
	public function get_by_uid($uid) {
		return $this->db->where('uid', $uid)->get('zcard')->row_array();
	}
	
	/** 查询总发卡次数 */
	public function get_total() {
		$this->db->select('id');
		return $this->db->count_all_results('zcard_detail');
	}
	
	/** 根据卡片数字，查询该卡片已出现次数 */
	public function get_by_num($num) {
		return $this->db->where('num', $num)->count_all_results('zcard_detail');
	}
	
	/** 添加一条发卡数据 */
	public function modify_card($data) {
		if($data['id'] > 0) {
			return $this->db->where('id', $data['id'])->update('zcard', $data);
		} else {
			return $this->db->insert('zcard', $data);
		}
	}
	/** 添加一条详情 */
	public function insert_detail($data) {
		return $this->db->insert('zcard_detail', $data);
	}
	
	/** 卡片记录列表 */
	public function get_zcard_lists($page, $per_page, $where) {
		$master = 'zcard';
		$sub = 'members_info';
		if(!empty($where)) {
			$this->db->like("concat(xm_{$sub}.uid,xm_{$sub}.real_name,xm_{$sub}.phone)", $where['name']);
		}
		$this->db->from($master);
		$this->db->limit($page, $per_page);
		$this->db->join($sub, "$master.uid = $sub.uid", 'left');
		return $this->db->get()->result_array();
	}
	
	/** 卡片总数量 */
	public function get_zcard_nums($where) {
		$master = 'zcard';
		$sub = 'members_info';
		if(!empty($where)) {
			$this->db->like("concat(xm_{$sub}.uid,xm_{$sub}.real_name,xm_{$sub}.phone)", $where['name']);
		}
		$this->db->from($master);
		$this->db->join($sub, "$master.uid = $sub.uid", 'left');
		return $this->db->count_all_results();
	}
	
	/** 插入一条订单表 */
	public function insert_order($data) {
		return $this->db->insert('zcard_order', $data);
	}
	
	/** 根据用户UID查询用户 */
	public function get_order_by_uid($uid) {
		return $this->db->where('uid', $uid)->get('zcard_order')->result_array();
	}
	
	/** 订单数量 */
	public function get_order_nums($where) {
		$master = 'zcard_order';
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
		$master = 'zcard_order';
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
	
	/** 根据ID查询一条数据 */
	public function get_order_by_id($id) {
		return $this->db->where('id', $id)->get('zcard_order')->row_array();
	}
	
	/** 修改一条数据 */
	public function modify_order($data) {
		return $this->db->where('id', $data['id'])->update('zcard_order', $data);
	}
	
	
	/** 订单数量,前端显示 */
	public function get_zcard_order_num($uid) {
		$master = 'zcard_order';
		$this->db->where('uid', $uid);
		$this->db->from($master);
		//$this->db->join($sub, "$master.uid = $sub.uid", 'left');
		return $this->db->count_all_results();
	}
	
	/** 订单列表,前端显示 */
	public function get_zcard_order_list($page, $per_page, $uid) {
		$master = 'zcard_order';
		//$sub = 'members_info';
		$this->db->where('uid', $uid);
		$this->db->from($master);
		$this->db->limit($page, $per_page);
		$this->db->order_by('status asc, id desc');
		//$this->db->join($sub, "$master.uid = $sub.uid", 'left');
		return $this->db->get()->result_array();
	}
	
	/** 手动插入抽卡片次数 */
	public function set_total_card($data) {
		$this->db->trans_begin();
		
		if(isset($data['times'])) {
			$this->db->insert('zcard_times', $data['times']);
			
		}
		if(isset($data['zcard'])) {
			if(isset($data['zcard']['id'])) {
				$this->db->where('id', $data['zcard']['id'])->update('zcard', $data['zcard']);
			} else {
				$this->db->insert('zcard', $data['zcard']);
			}
		}
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return false;
		} else {
			$this->db->trans_commit();
			return true;
		}
	}
	
	/** 详情数量 */
	public function get_detail_nums($where) {
		$master = 'zcard_detail';
		$sub = 'members_info';
		if(!empty($where)) {
			$this->db->like("concat(xm_{$sub}.uid,xm_{$sub}.real_name,xm_{$sub}.phone)", $where['name']);
		}
		$this->db->from($master);
		$this->db->join($sub, "$master.uid = $sub.uid", 'left');
		return $this->db->count_all_results();
	}
	/** 详情 */
	public function get_detail_lists($page, $per_page, $where) {
		$master = 'zcard_detail';
		$sub = 'members_info';
		if(!empty($where)) {
			$this->db->like("concat(xm_{$sub}.uid,xm_{$sub}.real_name,xm_{$sub}.phone)", $where['name']);
		}
		$this->db->from($master);
		$this->db->limit($page, $per_page);
		$this->db->order_by('id desc');
		$this->db->join($sub, "$master.uid = $sub.uid", 'left');
		return $this->db->get()->result_array();
	}
	
}