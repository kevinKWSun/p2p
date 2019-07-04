<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Zcashc_model extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->database();
	}
	
	/** 出现次数 */
	public function get_detail_count($num = 0) {
		if(!$num) {
			return $this->db->count_all_results('zcashc_detail');
		} else {
			return $this->db->where('num', $num)->count_all_results('zcashc_detail');
		}
	}
	
	/** 批量订单表修改 */
	public function batch_order($data) {
		return $this->db->update_batch('zcashc_detail', $data, 'id');
	}
	/** 根据id查询多条福袋信息 */
	public function get_order_byids($ids) {
		$this->db->where_in('id', $ids);
		return $this->db->get('zcashc_detail')->result_array();
	}
	
	/** 抽取一次福袋 */
	public function record_random($data) {
		$this->db->trans_begin();
		$this->db->where('id', $data['zcashc']['id'])->update('zcashc', $data['zcashc']);
		$this->db->insert('zcashc_detail', $data['detail']);
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return false;
		} else {
			$this->db->trans_commit();
			return true;
		}
	}
	
	/** 根据用户ID查询一条用户数据 */
	public function get_by_uid($uid) {
		return $this->db->where('uid', $uid)->get('zcashc')->row_array();
	}
	
	/** 查询总抽奖次数 */
	public function get_total() {
		$this->db->select('id');
		return $this->db->count_all_results('zcashc_detail');
	}
	
	/** 根据抽奖的金额，查询该金额已出现次数 */
	public function get_by_num($num) {
		$this->db->where('num', $num);
		//$this->db->where('uid', )
		return $this->db->count_all_results('zcashc_detail');
	}
	
	/** 根据抽奖的金额，查询该金额已出现次数 */
	public function get_by_num_uid($num, $uid) {
		$this->db->where('num', $num);
		$this->db->where('uid', $uid);
		return $this->db->count_all_results('zcashc_detail');
	}
	
	/** 添加一条发抽奖次数数据 */
	public function modify_cash($data) {
		if($data['id'] > 0) {
			return $this->db->where('id', $data['id'])->update('zcashc', $data);
		} else {
			return $this->db->insert('zcashc', $data);
		}
	}
	
	/** 添加一条记录 */
	public function add_record($data) {
		return $this->db->insert('zcashc_record', $data);
	}
	/** 添加一条详情 */
	public function insert_detail($data) {
		return $this->db->insert('zcashc_detail', $data);
	}
	
	/** 卡片记录列表 */
	public function get_zcashc_lists($page, $per_page, $where) {
		$master = 'zcashc';
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
	public function get_zcashc_nums($where) {
		$master = 'zcashc';
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
		return $this->db->insert('zcashc_detail', $data);
	}
	
	/** 根据用户UID查询用户 */
	public function get_order_by_uid($uid) {
		return $this->db->where('uid', $uid)->get('zcashc_detail')->result_array();
	}
	
	/** 订单数量 */
	public function get_order_nums($where) {
		$master = 'zcashc_detail';
		$sub = 'members_info';
		if(!empty($where)) {
			if(isset($where['name']) && !empty($where['name'])) {
				$this->db->like("concat(xm_{$sub}.uid,xm_{$sub}.real_name,xm_{$sub}.phone)", $where['name']);
			}
			if(isset($where['time']) && !empty($where['time'])) {
				$this->db->where("xm_{$master}.addtime between ". strtotime($where['time'][0]) . ' and '. (strtotime($where['time'][2]) + 86399));
			}
		}
		$this->db->from($master);
		$this->db->join($sub, "$master.uid = $sub.uid", 'left');
		return $this->db->count_all_results();
	}
	
	/** 订单列表 */
	public function get_order_lists($page, $per_page, $where) {
		$master = 'zcashc_detail';
		$sub = 'members_info';
		if(!empty($where)) {
			if(isset($where['name']) && !empty($where['name'])) {
				$this->db->like("concat(xm_{$sub}.uid,xm_{$sub}.real_name,xm_{$sub}.phone)", $where['name']);
			}
			if(isset($where['time']) && !empty($where['time'])) {
				$this->db->where("xm_{$master}.addtime between ". strtotime($where['time'][0]) . ' and '. (strtotime($where['time'][2]) + 86399));
			}
		}
		$this->db->from($master);
		$this->db->limit($page, $per_page);
		$this->db->order_by('status asc, id desc');
		$this->db->join($sub, "$master.uid = $sub.uid", 'left');
		return $this->db->get()->result_array();
	}
	
	/** 根据ID查询一条数据 */
	public function get_order_by_id($id) {
		return $this->db->where('id', $id)->get('zcashc_detail')->row_array();
	}
	
	/** 修改一条数据 */
	public function modify_order($data) {
		return $this->db->where('id', $data['id'])->update('zcashc_detail', $data);
	}
	
	
	/** 订单数量,前端显示 */
	public function get_zcashc_order_num($uid) {
		$master = 'zcashc_detail';
		$this->db->where('uid', $uid);
		$this->db->from($master);
		//$this->db->join($sub, "$master.uid = $sub.uid", 'left');
		return $this->db->count_all_results();
	}
	
	/** 订单列表,前端显示 */
	public function get_zcashc_order_list($page, $per_page, $uid) {
		$master = 'zcashc_detail';
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
			$this->db->insert('zcashc_record', $data['times']);
		}
		if(isset($data['zcashc'])) {
			if(isset($data['zcashc']['id'])) {
				$this->db->where('id', $data['zcashc']['id'])->update('zcashc', $data['zcashc']);
			} else {
				$this->db->insert('zcashc', $data['zcashc']);
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
		$master = 'zcashc_detail';
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
		$master = 'zcashc_detail';
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
	/** 记录数量 */
	public function get_record_nums($where) {
		$master = 'zcashc_record';
		$sub = 'members_info';
		if(!empty($where)) {
			$this->db->like("concat(xm_{$sub}.uid,xm_{$sub}.real_name,xm_{$sub}.phone)", $where['name']);
		}
		$this->db->from($master);
		$this->db->join($sub, "$master.uid = $sub.uid", 'left');
		return $this->db->count_all_results();
	}
	
	/** 福袋发放记录列表 */
	public function get_record_lists($page, $per_page, $where) {
		$master = 'zcashc_record';
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
	
	/** 修改一条数据 */
	public function update_zcashc($data) {
		return $this->db->where(array('id'=>$data['id']))->update('zcashc', $data);
	}
	/** 插入一条新数据 */
	public function insert_zcashc($data) {
		return $this->db->insert('zcashc', $data);
	}
	
	/** 根据用户UID查询账户数据  */
	public function get_zcashc_byuid($uid) {
		return $this->db->where(array('uid'=>$uid))->get('zcashc')->row_array();
	}
	
	/** 根据IDS设置导入数据的使用状态 */
	public function update_data_byids($where) {
		return $this->db->where_in('id', $where)->update('zcashc_data', array('used'=>1));
	}
	
	/** 查询所有导入的数据 */
	public function get_data_byused() {
		return $this->db->select('id, uid, money, duration, itime')->where(array('used'=>0, 'status'=>1))->get('zcashc_data')->result_array();
	}
	
	/** 导入数据的分页显示 */
	public function get_zcashc_data_lists($page, $per_page, $where) {
		if(!empty($where['name'])) {
			$this->db->like("name", $where['name']);
		}
		$this->db->limit($page, $per_page);
		$this->db->order_by('id desc');
		return $this->db->get('zcashc_data')->result_array();
	}
	
	/** 导入数据的总数量 */
	public function get_zcashc_data_nums($where) {
		if(!empty($where['name'])) {
			$this->db->like("name", $where['name']);
		}
		return $this->db->count_all_results('zcashc_data');
	}
	
	/** 批量插入导入的数据 */
	public function insert_zcashc_data($data) {
		$this->db->trans_begin();
		
		$this->db->insert_batch('zcashc_data', $data, true, 1000);
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