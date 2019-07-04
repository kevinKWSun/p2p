<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Guarantor_model extends CI_Model{
	public function __construct() {
        parent::__construct();
        $this->load->database();
    }
	
	public function get_guarantor_lists($per_page, $offset, $where = array()) {
		if(isset($where['skey'])) {
			$this->db->like("concat(name, phone, idcard)", $where['skey']);
			unset($where['skey']);
		}
		if(isset($where['guarantor_id'])) {
			$this->db->where('id', $where['guarantor_id']);
			unset($where['guarantor_id']);
		}
		if(!empty($where)) {
			foreach($where as $k=>$v) {
				$this->db->where($k, $v);
			}
		}
		$this->db->limit($per_page, $offset);
		$this->db->order_by('id DESC');
		
		return $this->db->get('company_guarantor')->result_array();
		// echo $this->db->last_query();
		// return $ret;
	}
	
	public function get_guarantor_num($where = array()) {
		if(isset($where['skey'])) {
			$this->db->like("concat(name, phone, idcard)", $where['skey']);
			unset($where['skey']);
		}
		if(isset($where['guarantor_id'])) {
			$this->db->where('id', $where['guarantor_id']);
			unset($where['guarantor_id']);
		}
		if(!empty($where)) {
			foreach($where as $k=>$v) {
				$this->db->where($k, $v);
			}
		}
		return $this->db->count_all_results('company_guarantor');
	}
	
	/** 添加 */
	public function add_guarantor($data) {
		return $this->db->insert('company_guarantor', $data);
	}
	
	/** 编辑 */
	public function modify_guarantor($data) {
		$this->db->where('id', $data['id']);
		return $this->db->update('company_guarantor', $data);
	}
	
	/** 获取一条数据，根据id */
	public function get_guarantor_one($id) {
		$this->db->where('id', $id);
		return $this->db->get('company_guarantor')->row_array();
	}
	
	/** 根据ID获取多条数据 */
	public function get_guarantor_more($ids) {
		$this->db->where_in('id', $ids);
		return $this->db->get('company_guarantor')->result_array();
	}
	
	/** 根据查询条件查询数据 */
	public function get_guarantor_bywhere($where) {
		if(!empty($where)) {
			foreach($where as $k=>$v) {
				$this->db->where($k, $v);
			} 
		}
		return $this->db->get('company_guarantor')->row_array();
	}
	
	
}