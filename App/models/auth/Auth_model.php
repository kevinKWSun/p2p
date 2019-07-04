<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Auth_model extends CI_Model{
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    //权限结点
    public function getrules($a = 'auth_group_access', $b = 'auth_group', $uid, $type = 0){
        if($type == 1){
            $this->db->select("$b.id, $b.title, $b.pid");
        }else{
            $this->db->select("$b.rules,$b.id");
        }
        $this->db->from("$a");
        $this->db->where("$a.uid",$uid);
        $this->db->where("$b.status",1);
        $this->db->join("$b", "$a.group_id=$b.id", 'left');
        $sql = $this->db->get();
        return $sql->result_array();
    }
    public function rule($table, $ids){
        $this->db->select('condition,name');
        $this->db->from($table);
        $this->db->where('status',1);
        $this->db->where_in('id',$ids);
        $sql = $this->db->get();
        return $sql->result_array();
    }
    //查询id是否存在
    public function get_ausers_byuid($id){
        $this->db->where('id', $id);
        $query=  $this->db->get('user');
        $sql = $query->row_array();
        return $sql;
    }
}

