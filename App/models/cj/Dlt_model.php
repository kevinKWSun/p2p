<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Dlt_model extends CI_Model{
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
	
	// 插入一条新数据
	public function save($data) {
		return $this->db->insert('dlt', $data);
	}
	
	// 批量插入新数据
	public function batch_save($data) {
		return $this->db->insert_batch('dlt', $data);
	}
	// 查询总数量
	public function get_dlt_nums($where) {
		$master = 'dlt';
		$sub = 'members_info';
		if(!empty($where['phone'])) {
			$this->db->where("xm_{$master}.phone", $where['phone']);
		}
		if(!empty($where['rname'])) {
			$this->db->where("xm_{$sub}.real_name", $where['rname']);
		}
		if(isset($where['time']) && !empty($where['time'])) {
			$this->db->where("xm_{$master}.addtime between ". strtotime($where['time'][0]) . " and ". (strtotime($where['time'][2]) + 86399));
		}
		$this->db->from($master);
		$this->db->join("$sub", "$master.phone = $sub.phone", 'left');
		return $this->db->count_all_results();
	}
	
	// 查询分页数据
	public function get_dlt_lists($page, $per_page, $where) {
		if(!empty($where['phone'])) {
			$this->db->where('phone', $where['phone']);
		}
		$this->db->order_by('id DESC');
		$this->db->limit($page, $per_page);
		return $this->db->get('dlt')->result_array();
	}
	// 关联member_info表
	public function get_dlt_alists($page, $per_page, $where) {
		$master = 'dlt';
		$sub = 'members_info';
		if(!empty($where['phone'])) {
			$this->db->where("xm_{$master}.phone", $where['phone']);
		}
		if(!empty($where['rname'])) {
			$this->db->where("xm_{$sub}.real_name", $where['rname']);
		}
		if(isset($where['time']) && !empty($where['time'])) {
			$this->db->where("xm_{$master}.addtime between ". strtotime($where['time'][0]) . " and ". (strtotime($where['time'][2]) + 86399));
		}
		$this->db->select("$master.*, $sub.real_name, $sub.idcard");
		$this->db->from($master);
		$this->db->join("$sub", "$master.phone = $sub.phone", 'left');
		$this->db->order_by('id DESC');
		$this->db->limit($page, $per_page);
		return $this->db->get()->result_array();
	}
	
	// 查询数据
	public function get_dlt_all() {
		$this->db->order_by('id DESC');
		$this->db->limit(10);
		return $this->db->get('dlt')->result_array();
	}
}

