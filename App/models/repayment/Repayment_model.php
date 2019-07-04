<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Repayment_model extends CI_Model{
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
	/** 还款信息 */
	public function get_investor_detail_lists($page = 10, $per_page = 1, $where = array()) {
		$master = 'investor_detail';
		$sub = 'borrow';
		if(!empty($where)) {
			if(isset($where['name']) && !empty($where['name'])) {
				$this->db->like("concat(xm_{$sub}.borrow_name, xm_{$sub}.borrow_no)", $where['name']);
			}
			if(isset($where['time']) && !empty($where['time'])) {
				$this->db->where("xm_{$master}.deadline between ". strtotime($where['time'][0]) . ' and '. (strtotime($where['time'][2]) + 86399));
			}
			if(isset($where['status']) && !empty($where['status'])) {
				if($where['status'] == 1) {//已还
					$this->db->where("xm_{$sub}.borrow_status >", 4);
				}
				if($where['status'] == 2) {
					$this->db->where("xm_{$sub}.borrow_status <", 5);
				}
			}
		}
		$this->db->select("xm_{$master}.deadline, xm_{$master}.borrow_id, xm_{$master}.sort_order, xm_{$master}.total");
        $this->db->order_by("xm_{$master}.deadline ASC");
		$this->db->limit($page, $per_page);
		$this->db->group_by("xm_{$master}.deadline, xm_{$master}.borrow_id, xm_{$master}.sort_order, xm_{$master}.total");
		
		$this->db->from($master);
		$this->db->join($sub, "$master.borrow_id = $sub.id", 'left');
		
        return $this->db->get()->result_array();
		// echo $this->db->last_query();
		// return $res;
      
	}
	
	/** 根据还款时间获取一条数据 */
	// public function get_detail_one_bydeadline($deadline) {
		// return $this->db->select('status')->where(array('deadline'=>$deadline))->get('investor_detail')->row_array();
	// }
	
	/** 获取还款数量 */
	public function get_investor_detail_num($where = array()) {
		$master = 'investor_detail';
		$sub = 'borrow';
		if(!empty($where)) {
			if(isset($where['name']) && !empty($where['name'])) {
				$this->db->like("concat(xm_{$sub}.borrow_name, xm_{$sub}.borrow_no)", $where['name']);
			}
			if(isset($where['time']) && !empty($where['time'])) {
				$this->db->where("xm_{$master}.deadline between ". strtotime($where['time'][0]) . ' and '. (strtotime($where['time'][2]) + 86399));
			}
			if(isset($where['status']) && !empty($where['status'])) {
				if($where['status'] == 1) {//已还
					$this->db->where("xm_{$sub}.borrow_status >", 4);
				}
				if($where['status'] == 2) {
					$this->db->where("xm_{$sub}.borrow_status <", 5);
				}
			}
		}
		
		$this->db->select("xm_{$master}.deadline, xm_{$master}.borrow_id, xm_{$master}.sort_order, xm_{$master}.total");
        $this->db->order_by("$master.deadline ASC");
		//$this->db->limit($page, $per_page);
		$this->db->group_by("xm_{$master}.deadline, xm_{$master}.borrow_id, xm_{$master}.sort_order, xm_{$master}.total");
		
		$this->db->from($master);
		$this->db->join($sub, "$master.borrow_id = $sub.id", 'left');
		
        return $this->db->count_all_results();
	}
	
	/** 获取每期数据总和 */
	public function get_investor_detail_by_deadline($deadline) {
		$this->db->where('deadline', $deadline);
		$this->db->select('sum(repayment_time) as repayment_time, sum(capital) as capital, sum(interest) as interest');
		
		return $this->db->get('investor_detail')->row_array();
	}
	
	/** 根据时间调取一条详情数据 */
	public function get_investor_detail_one_by_deadline($deadline) {
		$this->db->where('deadline', $deadline);
		$this->db->order_by('id asc');
		return $this->db->get('investor_detail')->row_array();
	}
	
	/** 根据还款时间，获取还款详情 */
	public function get_repayment_detail_by_deadline($deadline) {
		$master = 'investor_detail';
		$sub = 'borrow';
		$sub2 = 'members_info';
		$sub3 = 'members';
		$sub4 = 'members_info';
		$this->db->where('deadline', $deadline);
		$this->db->select("$master.*, $sub.borrow_name, $sub2.real_name, $sub2.phone, $sub3.codeuid, code.real_name as codename");
		$this->db->from($master);
		$this->db->join($sub, "$master.borrow_id = $sub.id", 'left');
		$this->db->join($sub2, "$master.investor_uid = $sub2.uid", 'left');
		$this->db->join($sub3, "$master.investor_uid = $sub3.id", 'left');
		$this->db->join("$sub4 as code", "$sub3.codeuid = code.uid", 'left');
		return $this->db->get()->result_array();
		
	}
}