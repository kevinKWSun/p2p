<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Times_model extends CI_Model{
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
	
	/** 根据 uid和期限，调出抽奖次数 */
	public function get_times_byuid($uid, $type) {
		return $this->db->where(array('uid'=>$uid, 'type'=>$type))->get('times')->row_array();
	}
	
	/** 添加数据到 member_info 和times表中*/
	// public function add_times($data) {
		// $this->db->trans_begin();
		// if(isset($data['times'])) {//新增
			// $this->db->insert_batch('times', $data['times']);
		// }
		// if(isset($data['times1'])) {//更新
			// $this->db->update_batch('times', $data['times1'], 'id', 500);
		// }
		// if(isset($data['times2'])) {//
			// $this->db->update_batch('member_info', $data['times2'], 'uid', 500);
		// }
		// if ($this->db->trans_status() === FALSE) {
			// $this->db->trans_rollback();
		// } else {
			// $this->db->trans_commit();
		// }
	// }
	/** 添加抽奖次数 */
	public function add_member_times($data) {
		$this->db->where(array('uid'=>$data['uid']))->set('times', 'times +' . $data['times'], FALSE)->update('members_info');
	}
	/** 添加双倍抽奖次数 */
	public function add_member_doubles($data) {
		$this->db->where(array('uid'=>$data['uid']))->set('doubles', 'doubles +' . $data['doubles'], FALSE)->update('members_info');
	}
	/** 添加总次数 */
	public function add_times($data) {
		$this->db->insert('times', $data);
	}
	/** 修改总次数 */
	public function modify_times($data) {
		$this->db->where(array('id'=>$data['id']))->update('times', $data);
	}

	/** 用户信息（members, members_info, members_status）三张表关联查询 */
	public function get_times_related($page, $per_page, $where = array()) {
		$master = 'times';
		$sub = 'members_info';
		if(isset($where['skey'])) {
			$this->db->like("concat(xm_{$sub}.uid, xm_{$sub}.real_name, xm_{$sub}.idcard, xm_{$sub}.phone)", $where['skey']);
			unset($where['skey']);
		}
		if(!empty($where)) {
			foreach($where as $k=>$v) {
				$this->db->where($k, $v);
			}
		}
		$this->db->select("xm_{$master}.*, xm_{$sub}.real_name, xm_{$sub}.phone, xm_{$sub}.times as single, xm_{$sub}.doubles");
		$this->db->from($master);
		$this->db->limit($page, $per_page);
		$this->db->order_by('id DESC');
		$this->db->join($sub, "$master.uid = $sub.uid", 'left');
		return $this->db->get()->result_array();
	}
	public function get_times_related_num($where = array()) {
		$master = 'times';
		$sub = 'members_info';
		if(isset($where['skey'])) {
			$this->db->like("concat(xm_{$sub}.uid, xm_{$sub}.real_name, xm_{$sub}.idcard)", $where['skey']);
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
}