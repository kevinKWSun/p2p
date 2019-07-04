<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Pay_model extends CI_Model{
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
	public function add($data = array()){
        $this->db->insert('test',$data);
        $sql = $this->db->insert_id();
        return $sql;
    }
}