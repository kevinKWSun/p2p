<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Eyeapi_model extends CI_Model{
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
	
	/** 添加一个账号 */
	public function add_account($data) {
		return $this->db->insert('eye', $data);
	}
	
	/** 添加token值 */
	public function modify_account($data) {
		return $this->db->where(array('id'=>$data['id']))->update('eye', $data);
	}
	
	/** 查询一条 数据 */
	public function get_account_one($id = 0) {
		$id = intval($id);
		if($id > 0) {
			return $this->db->where(array('id'=>$id))->order_by('id DESC')->get('eye')->row_array();
		} else {
			$this->db->limit(1);
			return $this->db->order_by('id DESC')->get('eye')->row_array();
		}
		
	}
	
	/** 根据用户名查询一条数据 */
	public function get_account_byusername($username) {
		if(empty($username)) {
			return false;
		}
		return $this->db->where(array('username'=>$username))->get('eye')->row_array();
	}
	
	/** 查询是否有token */
	public function get_account_bytoken($token) {
		return $this->db->where(array('token'=>$token))->get('eye')->row_array();
	}
	
	public function get_borrow_related($page = 10, $per_page = 1, $where = array()) {
		$master = 'borrow';
		$sub = 'members_info';
		if(isset($where['fulltime']) && isset($where['endtime'])) {
			//$this->db->where("(xm_{$master}.fulltime between " . $where['fulltime'][0] . " and " . $where['fulltime'][1]);
			//$this->db->or_where("xm_{$master}.endtime between " . $where['endtime'][0] . " and " . $where['endtime'][1] . ")");
			$this->db->where("xm_{$master}.endtime between " . $where['endtime'][0] . " and " . $where['endtime'][1]);
			unset($where['fulltime'], $where['endtime']);
		}
		if(isset($where['send_time'])) {
			$this->db->where("xm_{$master}.send_time between " . $where['send_time'][0] . " and " . $where['send_time'][1]);
			unset($where['send_time']);
		}
		if(!empty($where)) {
			foreach($where as $k=>$v) {
				$this->db->where("xm_{$master}." . $k, $v);
			}
		}
		$this->db->limit($page, $per_page);
        $this->db->order_by('id DESC');
		$this->db->from($master);
        $this->db->join($sub, "$master.borrow_uid = $sub.uid", 'left');
		$query = $this->db->get()->result_array();
		//echo $this->db->last_query();
		return $query;
	}
	
	public function get_borrow_related_nums($where = array()){ 
		$master = 'borrow';
		$sub = 'members_info';
		if(isset($where['fulltime']) && isset($where['endtime'])) {
			// $this->db->where("(xm_{$master}.fulltime between " . $where['fulltime'][0] . " and " . $where['fulltime'][1]);
			// $this->db->or_where("xm_{$master}.endtime between " . $where['endtime'][0] . " and " . $where['endtime'][1] . ")");
			$this->db->where("xm_{$master}.endtime between " . $where['endtime'][0] . " and " . $where['endtime'][1]);
			unset($where['fulltime'], $where['endtime']);
		}
		if(isset($where['send_time'])) {
			$this->db->where("xm_{$master}.send_time between " . $where['send_time'][0] . " and " . $where['send_time'][1]);
			unset($where['send_time']);
		}
		if(!empty($where)) {
			foreach($where as $k=>$v) {
				$this->db->where("xm_{$master}." . $k, $v);
			}
		}
		$this->db->from($master);
        $this->db->join($sub, "$master.borrow_uid = $sub.uid", 'left');
		return $this->db->count_all_results();
    }
	
	/** 获取投资人信息 */
	public function get_borrow_investor_lists($page = 10, $per_page = 1, $where = array()) {
		$master = 'borrow_investor';
		$sub = 'borrow';
		$sub2 = 'members_info';
		if(isset($where['fulltime']) && isset($where['endtime'])) {
			// $this->db->where("(xm_{$sub}.fulltime between " . $where['fulltime'][0] . " and " . $where['fulltime'][1]);
			// $this->db->or_where("xm_{$sub}.endtime between " . $where['endtime'][0] . " and " . $where['endtime'][1] . ")");
			$this->db->where("xm_{$sub}.endtime between " . $where['endtime'][0] . " and " . $where['endtime'][1]);
			unset($where['fulltime'], $where['endtime']);
		}
		if(isset($where['send_time'])) {
			$this->db->where("xm_{$sub}.send_time between " . $where['send_time'][0] . " and " . $where['send_time'][1]);
			unset($where['send_time']);
		}
		if(!empty($where)) {
			foreach($where as $k=>$v) {
				$this->db->where("xm_{$sub}." . $k, $v);
			}
		}
		$this->db->where('investor_capital > ', 50);
		$this->db->select("$master.*, $sub.borrow_duration, $sub.borrow_no, $sub.repayment_type, $sub.id as bid, $sub.borrow_name, $sub2.real_name, $sub2.phone, $sub2.idcard");
        $this->db->limit($page, $per_page);
        $this->db->order_by("$master.id DESC");
		$this->db->from($master);
		$this->db->join($sub, "$master.borrow_id = $sub.id", 'left');
		$this->db->join($sub2, "$master.investor_uid = $sub2.uid", 'left');
		
        $res = $this->db->get()->result_array();
		//echo $this->db->last_query();
		return $res;
      
	}
	/** 获取投资人数量 */
	public function get_borrow_investor_num($where = array()) {
		$master = 'borrow_investor';
		$sub = 'borrow';
		$sub2 = 'members_info';
		if(isset($where['fulltime']) && isset($where['endtime'])) {
			// $this->db->where("(xm_{$sub}.fulltime between " . $where['fulltime'][0] . " and " . $where['fulltime'][1]);
			// $this->db->or_where("xm_{$sub}.endtime between " . $where['endtime'][0] . " and " . $where['endtime'][1] . ")");
			$this->db->where("xm_{$sub}.endtime between " . $where['endtime'][0] . " and " . $where['endtime'][1]);
			unset($where['fulltime'], $where['endtime']);
		}
		if(isset($where['send_time'])) {
			$this->db->where("xm_{$sub}.send_time between " . $where['send_time'][0] . " and " . $where['send_time'][1]);
			unset($where['send_time']);
		}
		if(!empty($where)) {
			foreach($where as $k=>$v) {
				$this->db->where("xm_{$sub}.".$k, $v);
			}
		}
		$this->db->where('investor_capital > ', 50);
		$this->db->from($master);
		$this->db->join($sub, "$master.borrow_id = $sub.id", 'left');
		$this->db->join($sub2, "$master.investor_uid = $sub2.uid", 'left');
        return $this->db->count_all_results();
	}
	
	/** 提前还款 */
	public function get_borrow_relateds($page = 10, $per_page = 1, $where = array()) {
		$master = 'borrow';
		$sub = 'members_info';
		$sub2 = 'borrow_investor';
		if(isset($where['deadline'])) {
			$this->db->where("xm_{$sub2}.deadline between " . $where['deadline'][0] . " and " . $where['deadline'][1]);
			unset($where['deadline']);
		}
		if(!empty($where)) {
			foreach($where as $k=>$v) {
				$this->db->where("xm_{$master}." . $k, $v);
			}
		}
		$this->db->select("$master.*, $sub.*, $sub2.deadline");
		$this->db->limit($page, $per_page);
        $this->db->order_by('id DESC');
		$this->db->from($master);
        $this->db->join($sub, "$master.borrow_uid = $sub.uid", 'left');
		$this->db->join($sub2, "$master.id = $sub2.borrow_id", 'left');
		$query = $this->db->get()->result_array();
		//echo $this->db->last_query();
		return $query;
	}
	public function get_borrow_related_numss($where = array()){ 
		$master = 'borrow';
		$sub = 'members_info';
		$sub2 = 'borrow_investor';
		if(isset($where['deadline'])) {
			$this->db->where("xm_{$sub2}.deadline between " . $where['deadline'][0] . " and " . $where['deadline'][1]);
			unset($where['deadline']);
		}
		if(!empty($where)) {
			foreach($where as $k=>$v) {
				$this->db->where("xm_{$master}." . $k, $v);
			}
		}
		$this->db->from($master);
        $this->db->join($sub, "$master.borrow_uid = $sub.uid", 'left');
		$this->db->join($sub2, "$master.id = $sub2.borrow_id", 'left');
		return $this->db->count_all_results();
    }
	
	/** 提前还款金额 全部本金+所有利息*/
	public function get_repay_money($id) {
		return $this->db->select("sum(receive_capital) as receive_capital, sum(receive_interest) as receive_interest")->where(array('borrow_id'=>$id))->get('investor_detail')->row_array();
		//echo $this->db->last_query();
	}
	
	/** 根据标的ID，调取投资信息 */
	public function get_borrow_investor_one($bid) {
		$master = 'borrow_investor';
		//$sub = 'members_info';
		
		$this->db->where('borrow_id', $bid);
		$this->db->where('loan_status', 2);
		//$this->db->select("$master.*, $sub.real_name, $sub.phone, $sub.idcard");
		$this->db->from($master);
		//$this->db->join($sub, "$master.investor_uid = $sub.uid", 'left');
		
        $res = $this->db->get()->result_array();
		return $res;
	}
	
	/** 网贷之家 提前还款数据 */
	public function get_borrow_repay($page = 10, $per_page = 1, $where = array()) {
		$master = 'borrow';
		$sub = 'investor_detail';
		if(isset($where['repayment_time'])) {
			$this->db->where("xm_{$sub}.repayment_time between " . $where['repayment_time'][0] . " and " . $where['repayment_time'][1]);
			unset($where['repayment_time']);
		}
		$this->db->where("xm_{$sub}.status", 6);
		$this->db->where("xm_{$sub}.sort_order = xm_{$sub}.total");
		$this->db->select("$master.*, $sub.repayment_time");
		$this->db->limit($page, $per_page);
        $this->db->order_by('id DESC');
		$this->db->from($master);
        $this->db->join($sub, "$master.id = $sub.borrow_id", 'left');
		$query = $this->db->get()->result_array();
		//echo $this->db->last_query();
		return $query;
	}
	public function get_borrow_repay_nums($where = array()){ 
		$master = 'borrow';
		$sub = 'investor_detail';
		if(isset($where['repayment_time'])) {
			$this->db->where("xm_{$sub}.repayment_time between " . $where['repayment_time'][0] . " and " . $where['repayment_time'][1]);
			unset($where['repayment_time']);
		}
		$this->db->where("xm_{$sub}.status", 6);
		$this->db->where("xm_{$sub}.sort_order = xm_{$sub}.total");
		$this->db->from($master);
		$this->db->join($sub, "$master.id = $sub.borrow_id", 'left');
		return $this->db->count_all_results();
    }
}