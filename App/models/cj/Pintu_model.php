<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Pintu_model extends CI_Model{
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
	public function get_pics($uid){
		$this->db->where("uid", $uid);
		$query=  $this->db->get('zcard');
        $result = $query->row_array();
        return $result;
	}
}

