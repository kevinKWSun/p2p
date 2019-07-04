<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Motion_model extends CI_Model{
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
	
	public function get_motion_lists($page = 10, $per_page = 1, $where = array()) {
		$this->db->order_by('id DESC');
		return $this->db->get('motion')->result_array();
	}
	
	public function get_motion_lists_nums($where = array()) {
		return $this->db->count_all_results('motion');
	}
	
	public function add_motion($data) {
		return $this->db->insert('motion', $data);
	}
	
	public function modify_motion($data) {
		return $this->db->where('id', $data['id'])->update('motion', $data);
	}
	
	/** 根据日期 查询是否有当月的运营报告 */
	public function get_motion_bydate($date) {
		return $this->db->where('date', $date)->get('motion')->row_array();
	}
	
	/** 获取一条数据，根据ID */
	public function get_motion_byid($id) {
		return $this->db->where('id', $id)->get('motion')->row_array();
	}
	
	
	public function get_motion_date_list(){	
		$this->db->order_by('date desc');
		$this->db->select('date');
		$query=  $this->db->get('motion');
		$result = $query->result_array();
		return $result;	
	}
	//按照时间戳 查询报告
	// public function get_motion_date($page = 1, $per_page = 1){
		// $this->db->limit($page, $per_page);
		// $this->db->order_by('date desc');
		// $query=  $this->db->get('motion');
		// $result = $query->row_array();
		// return $result;	
	// }
	//按照时间戳 查询报告
	public function get_motion_date($year_month){
		$this->db->where("from_unixtime(date,'%Y-%m')", $year_month);
		$query=  $this->db->get('motion');
		$result = $query->row_array();
		return $result;	
	}
}