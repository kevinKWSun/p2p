<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Company_model extends CI_Model{
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
	
	/** 企业用户信息（members, members_info, members_status）三张表关联查询 */
	public function get_company_related($page, $per_page, $where = array()) {
		$master = 'members';
		$sub = 'members_info';
		$sub2 = 'members_status';
		$sub3 = 'company_info';
		if(isset($where['skey'])) {
			$this->db->like("concat(xm_{$master}.id, xm_{$sub}.real_name, xm_{$master}.user_name, xm_{$sub}.idcard)", $where['skey']);
			unset($where['skey']);
		}
		if(!empty($where)) {
			foreach($where as $k=>$v) {
				$this->db->where($k, $v);
			}
		}
		$this->db->select("xm_{$master}.*, xm_{$sub}.real_name, xm_{$sub}.phone, xm_{$sub}.idcard, xm_{$sub2}.id_status, xm_{$sub3}.industry, xm_{$sub3}.credit, xm_{$sub3}.founding_time, xm_{$sub3}.reg_address, xm_{$sub3}.reg_status, xm_{$sub3}.credit_status, xm_{$sub3}.situation, xm_{$sub3}.sanction, xm_{$sub3}.liabilities, xm_{$sub3}.records, xm_{$sub3}.info, xm_{$sub3}.scope");
		$this->db->limit($page, $per_page);
		$this->db->order_by('id DESC');
		$this->db->from($master);
		$this->db->join($sub, "$master.id = $sub.uid", 'left');
		$this->db->join($sub2, "$master.id = $sub2.uid", 'left');
		$this->db->join($sub3, "$master.id = $sub3.uid", 'left');
		return $this->db->get()->result_array();
		//echo $this->db->last_query();
		//return $res;
	}
	public function get_company_related_num($where = array()) {
		$master = 'members';
		$sub = 'members_info';
		$sub2 = 'members_status';
		$sub3 = 'company_info';
		if(isset($where['skey'])) {
			$this->db->like("concat(xm_{$master}.id, xm_{$sub}.real_name, xm_{$master}.user_name)", $where['skey']);
			unset($where['skey']);
		}
		if(!empty($where)) {
			foreach($where as $k=>$v) {
				$this->db->where($k, $v);
			}
		}
		$this->db->select("xm_{$master}.*, xm_{$sub}.real_name, xm_{$sub}.phone, xm_{$sub}.idcard, xm_{$sub2}.id_status, xm_{$sub3}.industry, xm_{$sub3}.credit, xm_{$sub3}.founding_time, xm_{$sub3}.reg_address, xm_{$sub3}.reg_status, xm_{$sub3}.credit_status, xm_{$sub3}.situation, xm_{$sub3}.sanction, xm_{$sub3}.liabilities, xm_{$sub3}.records, xm_{$sub3}.info, xm_{$sub3}.scope");
		$this->db->from($master);
		$this->db->join($sub, "$master.id = $sub.uid", 'left');
		$this->db->join($sub2, "$master.id = $sub2.uid", 'left');
		$this->db->join($sub3, "$master.id = $sub3.uid", 'left');
		return $this->db->count_all_results();
	}
}