<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ausers_model extends CI_Model{
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    //page每页总数   per_page第几页从第几个下标开始
    public function get_ausers($page = 10, $per_page = 1, $keywords = '', $admin_id){
        if($keywords){
            $this->db->like('name', $keywords);
        }
        if($admin_id){
            $this->db->where('admin_id', $admin_id);
        }
		$this->db->where('id>', 1);
        $this->db->where('status', 1);
        $this->db->limit($page, $per_page);
        $this->db->order_by('id DESC');
        $query=  $this->db->get('user');
        $result = $query->result_array();
        return $result;
    }
    //所有
    public function get_ausers_num($keywords = '', $admin_id){
        if($keywords){
            $this->db->like('name', $keywords);
        }
        if($admin_id){
            $this->db->where('admin_id', $admin_id);
        }
		$this->db->where('id>', 1);
        $this->db->where('status', 1);
        $sql = $this->db->count_all_results('user');
        return $sql;
    }
    //锁、解锁
    public function get_ausers_lock($data, $ids = array(), $admin_id = ''){
        if($admin_id){
            $this->db->where('admin_id', $admin_id);
        }
        $this->db->where_in('id', $ids);
        $sql = $this->db->update('user', $data);
        return $sql;
    }
    //删除
    public function get_ausers_del($ids = array()){die;
        $this->db->where_in('id', $ids);
        $sql = $this->db->delete('user');
        return $sql;
    }
    //增加
    public function add_ausers($data = array()){
        $this->db->insert('user',$data);
        $sql = $this->db->insert_id();
        return $sql;
    }
    //查询用户是否存在
    public function get_ausers_byname($name){
        $this->db->where('name', $name);
        $sql = $this->db->count_all_results('user');
        return $sql;
    }
    //查询id是否存在
    public function get_ausers_byuid($id){
        $this->db->where('id', $id);
        $query=  $this->db->get('user');
        $sql = $query->row_array();
        return $sql;
    }
    //编辑
    public function modify_ausers($data = array(), $uid){
        $this->db->where('id', $uid);
        $sql = $this->db->update('user', $data);
        return $sql;
    }//权限组
    public function get_group($admin_id){
        $this->db->select('id,title,pid');
        if($admin_id){
            $this->db->where('admin_id', $admin_id);
        }
        $this->db->where('status', 1);
        $this->db->order_by('id DESC');
        $query=  $this->db->get('auth_group');
        $result = $query->result_array();
        return $result;
    }
    //删除
    public function get_ag_del($uid){
        $this->db->where('uid', $uid);
        $sql = $this->db->delete('auth_group_access');
        return $sql;
    }
    public function get_ag_add($data){
        $sql = $this->db->insert('auth_group_access', $data);
        return $sql;
    }
}

