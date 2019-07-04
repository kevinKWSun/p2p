<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Admin_model extends CI_Model{
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    //权限结点
    public function getrule(){
        $this->db->where('pid', 0);
        $this->db->where('status', 1);
        //$this->db->order_by('id DESC');
        $query = $this->db->get('auth_rule');
        return $query->result_array();
    }
    //权限结点
    public function getrule_bypid($id){
        $this->db->where('pid', $id);
        $this->db->where('type', 1);
        $this->db->where('status', 1);
        $query = $this->db->get('auth_rule');
        return $query->result_array();
    }
}

