<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Integral_model extends CI_Model{
	public function __construct() {
        parent::__construct();
        $this->load->database();
    }
	
	public function add_integral($data) {
		return $this->db->insert('integral', $data);
	}
	
	
}