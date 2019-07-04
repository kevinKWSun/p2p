<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Group_model extends CI_Model{
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    //page每页总数   per_page第几页从第几个下标开始
    public function get_group($page = 10, $per_page = 1, $keywords = '', $admin_id){
        if($keywords){
            $this->db->like('title', $keywords);
        }
        if($admin_id){
            $this->db->where('admin_id', $admin_id);
        }
        $this->db->limit($page, $per_page);
        $this->db->order_by('id DESC');
        $query=  $this->db->get('auth_group');
        $result = $query->result_array();
        return $result;
    }
    //所有
    public function get_group_num($keywords = '', $admin_id){
        if($keywords){
            $this->db->like('title', $keywords);
        }
        if($admin_id){
            $this->db->where('admin_id', $admin_id);
        }
        $sql = $this->db->count_all_results('auth_group');
        return $sql;
    }
    //锁、解锁
    public function get_group_lock($data, $ids = array(), $admin_id = ''){
        if($admin_id){
            $this->db->where('admin_id', $admin_id);
        }
        $this->db->where_in('id', $ids);
        $sql = $this->db->update('auth_group', $data);
        return $sql;
    }
    //增加
    public function add_group($data = array()){
        $sql = $this->db->insert('auth_group',$data);
        return $sql;
    }
    //查询用户是否存在
    public function get_group_bytitle($title){
        $this->db->where('title', $title);
        $sql = $this->db->count_all_results('auth_group');
        return $sql;
    }
    //查询id是否存在
    public function get_group_byid($id){
        $this->db->where('id', $id);
        $query=  $this->db->get('auth_group');
        $sql = $query->row_array();
        return $sql;
    }
    //编辑
    public function modify_group($data = array(), $id){
        $this->db->where('id', $id);
        $sql = $this->db->update('auth_group', $data);
        return $sql;
    }
    //查询admin_id是否存在
    public function get_group_byaid($admin_id){
        $this->db->select('pid');
        $this->db->where('admin_id', $admin_id);
        $query=  $this->db->get('auth_group');
        $sql = $query->row_array();
        return $sql;
    }
}

