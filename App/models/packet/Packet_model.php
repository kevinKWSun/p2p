<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Packet_model extends CI_Model{
	public function __construct() {
        parent::__construct();
        $this->load->database();
    }
	/** 现金红包发放*/
	public function get_packet_list($page, $per_page, $where = array()) {
		// $this->db->where(array('type'=>7));
		// $this->db->limit($page, $per_page);
		// $this->db->order_by('add_time DESC');
		// return $this->db->get('members_moneylog')->result_array();
		$master = 'packet_xj';
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
		$this->db->limit($page, $per_page); 
		$this->db->order_by('id DESC');
		return $this->db->get()->result_array();

	}
	/** 现金红包发放总数*/
	public function get_packet_nums($where = array()) {
		$master = 'packet_xj';
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
	
	/** 现金红包的原因 */
	public function get_packet_remark_bynid($nid) {
		return $this->db->where('nid', $nid)->get('packet_xj')->row_array();
	}
	
	/** 获取投资红包列表 */
	public function get_packet_lists($page, $per_page, $where = array()) {
		$master = 'packet';
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
		$this->db->select("{$master}.*, {$sub}.uid, {$sub}.real_name, {$sub}.phone, {$sub}.idcard");
		$this->db->from($master);
		$this->db->join($sub, "$master.uid = $sub.uid", 'left');
		$this->db->limit($page, $per_page); 
		$this->db->order_by('id DESC');
		return $this->db->get()->result_array();
		
	}
	/** 投资红包总数量 */
	public function get_packet_numss($where = array()) {
		$master = 'packet';
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
	
	/** 红包发放失败记录 */
	public function get_mistake_lists($page, $per_page, $where) {
		$master = 'packet_error';
		if(isset($where['time'])) {
			$this->db->where("xm_{$master}.addtime between ". strtotime($where['time'][0]) . ' and '. (strtotime($where['time'][2]) + 86399));
		}
		if(isset($where['uid'])) {
			$this->db->where_in('uid', $where['uid']);
		}
		
		$this->db->from($master);
		$this->db->order_by("id desc");
		$this->db->limit($page, $per_page);
		return $this->db->get()->result_array();
	}
	
	/** 红包发放失败的数量 */
	public function get_mistake_nums($where) {
		$master = 'packet_error';
		if(isset($where['time'])) {
			$this->db->where("xm_{$master}.addtime between ". strtotime($where['time'][0]) . ' and '. (strtotime($where['time'][2]) + 86399));
		}
		if(isset($where['uid'])) {
			$this->db->where_in('uid', $where['uid']);
		}
		
		$this->db->from($master);
		return $this->db->count_all_results();
	} 
	
	/** 根据姓名或者手机号查询用户uid */
	public function get_info_byname($name) {
		$this->db->select('uid');
		$this->db->like('real_name', $name);
		$this->db->or_like('phone', $name);
		return $this->db->get('members_info')->result_array();
	}
	
	/** 获取一条红包数据 */
	public function get_packet_byid($id) {
		return $this->db->where('id', $id)->get('packet')->row_array();
	}
	
	/** 撤销投资红包 */
	public function revoke_packet($data) {
		return $this->db->where('id', $data['id'])->update('packet', $data);
	}
	/** 添加一条撤销记录 */
	public function insert_revoke($data) {
		return $this->db->insert('packet_revoke', $data);
	}
	/** 获取一条红包数据 */
	public function get_packet_one($id) {
		return $this->db->where('id', $id)->get('packet')->row_array();
	}
}