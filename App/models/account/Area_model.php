<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Area_model extends CI_Model{
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
	public function getarea($id = ''){
		if($id === 0 || $id > 0){
			$this->db->where('pid', $id);
		}
        $query=  $this->db->get('area');
        $result = $query->result_array();
        return $result;
	}
}

