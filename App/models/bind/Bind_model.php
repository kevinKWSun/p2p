<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Bind_model extends CI_Model{
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
	
	/** 获取投资人信息 */
	public function get_bind_lists($page = 10, $per_page = 1, $where = array()) {
		$master = 'quickbank_upbind_img';
		$sub = 'members_info';
		$sub2 = 'members_quickbank';
		if(!empty($where)) {
			if(isset($where['username']) && !empty($where['username'])) {
				$this->db->like("concat(xm_{$sub}.uid, xm_{$sub}.real_name, xm_{$sub}.phone)", $where['username']);
			}
		}
		
		$this->db->select("$master.*, $sub.real_name, $sub.phone, $sub.idcard, $sub2.paycard");
        $this->db->limit($page, $per_page);
        $this->db->order_by("$master.status ASC, $master.id DESC");
		$this->db->from($master);
		$this->db->join($sub, "$master.uid = $sub.uid", 'left');
		$this->db->join($sub2, "$master.uid = $sub2.uid", 'left');
		
        $res = $this->db->get()->result_array();
		return $res;
      
	}
	/** 获取投资人数量 */
	public function get_bind_num($where = array()) {
		$master = 'quickbank_upbind_img';
		$sub = 'members_info';
		$sub2 = 'members_quickbank';
		if(!empty($where)) {
			if(isset($where['username']) && !empty($where['username'])) {
				$this->db->like("concat(xm_{$sub}.uid, xm_{$sub}.real_name, xm_{$sub}.phone)", $where['username']);
			}
		}
		$this->db->from($master);
		$this->db->join($sub, "$master.uid = $sub.uid", 'left');
		$this->db->join($sub2, "$master.uid = $sub2.uid", 'left');
        return $this->db->count_all_results();
	}
	
	/** 根据ID获取一条数据 */
	public function get_bind_byid($id) {
		return $this->db->where('id', $id)->get('quickbank_upbind_img')->row_array();
	}
	/** 根据UID获取最后一条数据 */
	public function get_bind_byuid($uid) {
		return $this->db->where('uid', $uid)->order_by('id desc')->get('quickbank_upbind_img')->row_array();
	}
	/** 根据ID修改一条数据 */
	public function modify_bind($data) {
		return $this->db->where('id', $data['id'])->update('quickbank_upbind_img', $data);
	}
	
	
}