<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Borrowing_model extends CI_Model{
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
	// 后台调用列表数据关联公司信息
	public function get_borrow_related($page = 10, $per_page = 1, $where = array()) {
		$master = 'borrow';
		$sub = 'members_info';
		if(isset($where['skey'])) {
			$this->db->like("concat(xm_{$master}.id, xm_{$master}.borrow_name, xm_{$master}.borrow_no)", $where['skey']);
			unset($where['skey']);
		}
		if(isset($where['name'])) {
			$this->db->like("concat(xm_{$sub}.uid, xm_{$sub}.real_name)", $where['name']);
			unset($where['name']);
		}
		if(isset($where['guarantor'])) {
			$this->db->where("find_in_set(".$where['guarantor'].", guarantor)", NULL, FALSE);
			unset($where['guarantor']);
		}
		if(!empty($where)) {
			foreach($where as $k=>$v) {
				$this->db->where("xm_{$master}.".$k, $v);
			}
		}
		$this->db->limit($page, $per_page);
        $this->db->order_by('id DESC');
		$this->db->from($master);
        $this->db->join($sub, "$master.borrow_uid = $sub.uid", 'left');
		$query = $this->db->get()->result_array();
		return $query;
	}
	public function get_borrow_related_nums($where = array()){ 
		$master = 'borrow';
		$sub = 'members_info';
		if(isset($where['skey'])) {
			$this->db->like("concat(xm_{$master}.id, xm_{$master}.borrow_name, xm_{$master}.borrow_no)", $where['skey']);
			unset($where['skey']);
		}
		if(isset($where['name'])) {
			$this->db->like("concat(xm_{$sub}.uid, xm_{$sub}.real_name)", $where['name']);
			unset($where['name']);
		}
		if(isset($where['guarantor'])) {
			$this->db->where("find_in_set(".$where['guarantor'].", guarantor)", NULL, FALSE);
			unset($where['guarantor']);
		}
		if(!empty($where)) {
			foreach($where as $k=>$v) {
				$this->db->where("xm_{$master}.".$k, $v);
			}
		}
		$this->db->from($master);
        $this->db->join($sub, "$master.borrow_uid = $sub.uid", 'left');
		return $this->db->count_all_results();
    }
	
	/** 编辑数据 */
	public function modify_borrowing($data) {
		$this->db->where('id', $data['id']);
		return $this->db->update('zaudit', $data);
	}
	
	/** 根据id获取已添加数据 */
	public function get_borrowing_byid($id) {
		$this->db->where('id', $id);
		return $this->db->get('zaudit')->row_array();
	}
	
	/** 添加数据 */
    public function add_borrowing($data = array()){
        $this->db->insert('zaudit',$data);
        $sql = $this->db->insert_id();
        return $sql;
    }
	
	/**
	 * 判断已申请金额 
	 * $borrow_uid 	@param 借款人ID
	 */
	public function has_apply_money($borrow_uid) {
		$this->db->where('borrow_uid', $borrow_uid);
		$this->db->where('borrow_status <', 5);
		$this->db->select_sum('borrow_money');
		return $this->db->get('borrow')->row_array();
		
	}
	
	//后台调用列表数据
    public function get_borrowing_list($page = 10, $per_page = 1, $where = array()){
		if(isset($where['skey'])) {
			$this->db->like("concat(id, borrow_name, borrow_no)", $where['skey']);
			unset($where['skey']);
		}
		if(!empty($where)) {
			foreach($where as $k=>$v) {
				$this->db->where($k, $v);
			}
		}
		$this->db->where('del', 0);
        $this->db->limit($page, $per_page);
        $this->db->order_by('id DESC');
        $query=  $this->db->get('zaudit');
        $result = $query->result_array();
        return $result;
    }
    //后台调用列表数据 所有
    public function get_borrowing_nums($where = array()){
		if(isset($where['skey'])) {
			$this->db->like("concat(id, borrow_name, borrow_no)", $where['skey']);
			unset($where['skey']);
		}
		if(!empty($where)) {
			foreach($where as $k=>$v) {
				$this->db->where($k, $v);
			}
		}
		$this->db->where('del', 0);
        return $this->db->count_all_results('zaudit');
    }
	
	
}