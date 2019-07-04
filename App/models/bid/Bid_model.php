<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Bid_model extends CI_Model{
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
	
	/** 获取投资人信息 */
	public function get_borrow_investor_lists($page = 10, $per_page = 1, $where = array()) {
		if(!empty($where)) {
			if(isset($where['name']) && !empty($where['name'])) {
				$this->db->like("concat(xm_borrow.borrow_name, xm_borrow.borrow_no)", $where['name']);
			}
			if(isset($where['username']) && !empty($where['username'])) {
				$this->db->like("concat(xm_borrow_investor.investor_uid, xm_members_info.real_name, xm_members_info.phone, xm_members_info.custNo, xm_members_info.acctNo)", $where['username']);
			}
			if(isset($where['codename']) && !empty($where['codename'])) {
				$this->db->like("concat(code.real_name, xm_members.codeuid)", $where['codename']);
			}
			if(isset($where['time']) && !empty($where['time'])) {
				$this->db->where('borrow_investor.add_time between '. strtotime($where['time'][0]) . ' and '. (strtotime($where['time'][2]) + 86399));
			}
			if(isset($where['borrow_id']) && !empty($where['borrow_id'])) {
				$this->db->where('borrow_investor.borrow_id', $where['borrow_id']);
			}
		}
		$master = 'borrow_investor';
		$sub = 'borrow';
		$sub2 = 'members_info';
		$sub3 = 'members';
		$sub4 = 'members_info';
		$this->db->select("$master.*, $sub.borrow_status, $sub.borrow_duration, $sub.borrow_no, $sub.repayment_type, $sub.id as bid, $sub.borrow_name, $sub.borrow_type, $sub2.real_name, $sub2.phone, $sub2.idcard, $sub3.codeuid,$sub3.reg_time,code.real_name as code_name");
        $this->db->limit($page, $per_page);
        $this->db->order_by("$master.id DESC");
		$this->db->from($master);
		$this->db->join($sub, "$master.borrow_id = $sub.id", 'left');
		$this->db->join($sub2, "$master.investor_uid = $sub2.uid", 'left');
		$this->db->join($sub3, "$master.investor_uid = $sub3.id", 'left');
		$this->db->join("$sub4 as code", "$sub3.codeuid = code.uid", 'left');
		
        $res = $this->db->get()->result_array();
		//echo $this->db->last_query();
		//echo $this->db->last_query();
		return $res;
      
	}
	/** 获取投资人数量 */
	public function get_borrow_investor_num($where = array()) {
		if(!empty($where)) {
			if(isset($where['name']) && !empty($where['name'])) {
				$this->db->like("concat(xm_borrow.borrow_name, xm_borrow.borrow_no)", $where['name']);
			}
			if(isset($where['username']) && !empty($where['username'])) {
				$this->db->like("concat(xm_borrow_investor.investor_uid, xm_members_info.real_name, xm_members_info.phone)", $where['username']);
			}
			if(isset($where['codename']) && !empty($where['codename'])) {
				$this->db->like("concat(code.real_name, xm_members.codeuid)", $where['codename']);
			}
			if(isset($where['time']) && !empty($where['time'])) {
				$this->db->where('borrow_investor.add_time between '. strtotime($where['time'][0]) . ' and '. (strtotime($where['time'][2]) + 86399));
			}
			if(isset($where['borrow_id']) && !empty($where['borrow_id'])) {
				$this->db->where('borrow_investor.borrow_id', $where['borrow_id']);
			}
		}
		$master = 'borrow_investor';
		$sub = 'borrow';
		$sub2 = 'members_info';
		$sub3 = 'members';
		$sub4 = 'members_info';
		//$this->db->select("$master.*, $sub.borrow_duration, $sub.repayment_type, $sub.id as bid, $sub.borrow_name, $sub2.real_name, $sub2.phone, $sub2.idcard, $sub3.codeuid, code.real_name as code_name");
		$this->db->from($master);
		$this->db->join($sub, "$master.borrow_id = $sub.id", 'left');
		$this->db->join($sub2, "$master.investor_uid = $sub2.uid", 'left');
		$this->db->join($sub3, "$master.investor_uid = $sub3.id", 'left');
		$this->db->join("$sub4 as code", "$sub3.codeuid = code.uid", 'left');
        return $this->db->count_all_results();
	}
	
	/** 获取交易流水信息 */
	public function get_moneylog_lists($page = 10, $per_page = 1, $where = array()) {
		$master = 'members_moneylog';
		$sub = 'members_info';
		$sub2 = 'members';
		if(isset($where['username']) && !empty($where['username'])) {
			$this->db->like("concat(xm_{$sub}.uid, xm_{$sub}.real_name, xm_{$sub}.phone)", $where['username']);
		}
		$this->db->where("$sub2.attribute", 1);
        $this->db->limit($page, $per_page);
        $this->db->order_by("$master.add_time DESC");
		$this->db->from($master);
		$this->db->select("$master.*, $sub.*");
		$this->db->join($sub, "$master.uid = $sub.uid", 'left');
		$this->db->join($sub2, "$master.uid = $sub2.id", 'left');
		
        $res = $this->db->get()->result_array();
		//echo $this->db->last_query();
		return $res;
      
	}
	/** 获取交易流水数量 */
	public function get_moneylog_num($where = array()) {
		$master = 'members_moneylog';
		$sub = 'members_info';
		$sub2 = 'members';
		if(isset($where['username']) && !empty($where['username'])) {
			$this->db->like("concat(xm_{$sub}.uid, xm_{$sub}.real_name, xm_{$sub}.phone)", $where['username']);
		}
		$this->db->where("$sub2.attribute", 1);
		//$this->db->select("$master.*, $sub.borrow_duration, $sub.repayment_type, $sub.id as bid, $sub.borrow_name, $sub2.real_name, $sub2.phone, $sub2.idcard, $sub3.codeuid, code.real_name as code_name");
		$this->db->from($master);
		$this->db->join($sub, "$master.uid = $sub.uid", 'left');
		$this->db->join($sub2, "$master.uid = $sub2.id", 'left');
        return $this->db->count_all_results();
	}
	
	
}