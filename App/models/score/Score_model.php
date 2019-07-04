<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Score_model extends CI_Model{
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
	
	/** 获取已发放积分 */
	public function get_score($page = 10, $per_page = 1, $uid) {
		$this->db->where('uid', $uid);
		$this->db->limit($page, $per_page);
		$this->db->order_by('id DESC');
		return $this->db->get('score')->result_array();
	}
	
	/** 获取已发放积分数量 */
	public function get_score_num($uid) {
		$this->db->where('uid', $uid);
		return $this->db->count_all_results('score');
	}
	
	/** 查询积分 记录条数 */
	public function get_score_related_num($where = array()) {
		$master = 'score';
		$sub = 'members_info';
		if(isset($where['skey'])) {
			$this->db->like("concat(xm_{$sub}.uid, xm_{$sub}.real_name, xm_{$sub}.phone)", $where['skey']);
			unset($where['skey']);
		}
		if(!empty($where)) {
			foreach($where as $k=>$v) {
				$this->db->where($k, $v);
			}
		}
		$this->db->from($master);
		$this->db->join($sub, "$master.uid = $sub.uid", 'left');
		return $this->db->count_all_results();
	}
	
	/** 查询积分记录 */
	public function get_score_related($page, $per_page, $where = array()) {
		$master = 'score';
		$sub = 'members_info';
		if(isset($where['skey'])) {
			$this->db->like("concat(xm_{$sub}.uid, xm_{$sub}.real_name, xm_{$sub}.phone)", $where['skey']);
			unset($where['skey']);
		}
		if(!empty($where)) {
			foreach($where as $k=>$v) {
				$this->db->where($k, $v);
			}
		}
		
		$this->db->limit($page, $per_page);
		$this->db->order_by('id DESC');
		$this->db->from($master);
		$this->db->join($sub, "$master.uid = $sub.uid", 'left');
		return $this->db->get()->result_array();
	}

}

