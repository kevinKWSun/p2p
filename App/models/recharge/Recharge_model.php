<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Recharge_model extends CI_Model{
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
	public function get_recharges($one = '', $tow = '', $type = 1){
		$this->db->where("add_time between $one and $tow");
		$this->db->where('type', $type);
		$query=  $this->db->get('members_payonline');
        $result = $query->result_array();
        return $result;
	}
    //page每页总数   per_page第几页从第几个下标开始
    public function get_recharge($page = 10, $per_page = 1){
		$this->db->where('type', 1);
        $this->db->limit($page, $per_page);
        $this->db->order_by('id DESC');
        $query=  $this->db->get('members_payonline');
        $result = $query->result_array();
        return $result;
    }
    //所有
    public function get_recharge_num(){
		$this->db->where('type', 1);
        $sql = $this->db->count_all_results('members_payonline');
        return $sql;
    }
	//增加
    public function get_recharge_one($nid){
		$this->db->where('nid', $nid);
        $query =  $this->db->get('members_payonline');
        return $query->row_array();
    }
    //增加
    public function add_recharge($data = array()){
        $this->db->insert('members_payonline',$data);
        $sql = $this->db->insert_id();
        return $sql;
    }
    //编辑
    public function modify_recharge($data = array(), $id){
        $this->db->where('id', $id);
        $sql = $this->db->update('members_payonline', $data);
        return $sql;
    }
    //删除
    public function del_recharge($id){
        $this->db->where('id', $id);
        $sql = $this->db->delete('members_payonline');
        return $sql;
    }
    public function get_withdraw($page = 10, $per_page = 1){
		$this->db->where('type', 2);
        $this->db->limit($page, $per_page);
        $this->db->order_by('id DESC');
        $query =  $this->db->get('members_payonline');
        $result = $query->result_array();
        return $result;
    }
    //所有
    public function get_withdraw_num(){
		$this->db->where('type', 2);
        $sql = $this->db->count_all_results('members_payonline');
        return $sql;
    }
    //增加
    public function add_withdraw($data = array()){
        $this->db->insert('members_withdraw',$data);
        $sql = $this->db->insert_id();
        return $sql;
    }
    //编辑
    public function modify_withdraw($data = array(), $id){
        $this->db->where('id', $id);
        $sql = $this->db->update('members_withdraw', $data);
        return $sql;
    }
    //删除
    public function del_withdraw($id){
        $this->db->where('id', $id);
        $sql = $this->db->delete('members_withdraw');
        return $sql;
    }
	
	/** 关联充值 提现列表 */
	public function get_payonline_related($page = 10, $per_page = 1, $where = array()) {
		$master = 'members_payonline';
		$sub = 'members_info';
		
		if(isset($where['skey'])) {
			$this->db->like("concat(xm_{$master}.uid, xm_{$sub}.real_name, xm_{$sub}.phone)", $where['skey']);
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
		//echo $this->db->last_query();
		//return $ret;
	}
	/** 关联查询 数量 */
	public function get_payonline_related_num($where = array()) {
		$master = 'members_payonline';
		$sub = 'members_info';
		
		if(isset($where['skey'])) {
			$this->db->like("concat(xm_{$master}.uid, xm_{$sub}.real_name, xm_{$sub}.phone)", $where['skey']);
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

