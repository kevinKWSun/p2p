<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Rule_model extends CI_Model{
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    //page每页总数   per_page第几页从第几个下标开始
    public function get_rule($page = 10, $per_page = 1, $keywords = ''){
        if($keywords){
            $this->db->like('title', $keywords);
        }else{
            $this->db->where('pid', 0);
        }
        $this->db->limit($page, $per_page);
        $query=  $this->db->get('auth_rule');
        $result = $query->result_array();
        return $result;
    }
    //所有行
    public function get_rule_num($keywords = ''){
        if($keywords){
            $this->db->like('title', $keywords);
        }else{
            $this->db->where('pid', 0);
        }
        $sql = $this->db->count_all_results('auth_rule');
        return $sql;
    }
    //增加
    public function add_rule($data = array()){
        $sql = $this->db->insert('auth_rule',$data);
        return $sql;
    }
    //编辑
    public function modify_rule($data = array(), $id){
        $this->db->where('id', $id);
        $sql = $this->db->update('auth_rule', $data);
        return $sql;
    }
    //id查
    public function get_rule_byid($id){
        $this->db->where('id', $id);
        $sql = $this->db->get('auth_rule');
        $result = $sql->row_array();
        return $result;
    }
    //title查
    public function get_rule_bytitle($title, $pid = 0){
        $this->db->where('title', $title);
        $this->db->where('pid', $pid);
        $result = $this->db->count_all_results('auth_rule');
        return $result;
    }
    //所有数据
    public function get_rules(){
        //$this->db->select('title,id,pid');
		$this->db->where('status', 1);
        $sql = $this->db->get('auth_rule');
        $result = $sql->result_array();
        return $result;
    }
    //所有数据
    public function get_rbyids($ids){
        $this->db->where_in('id', $ids);
        $sql = $this->db->get('auth_rule');
        $result = $sql->result_array();
        return $result;
    }
    //批量增加
    public function addall_rule($data = array()){
        $sql = $this->db->insert_batch('auth_rule',$data);
        return $sql;
    }
}

