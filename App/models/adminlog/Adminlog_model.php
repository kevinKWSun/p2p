<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Adminlog_model extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->database();
	}
	
	/** 插入一条记录 */
	public function add_adminlog($data) {
		return $this->db->insert('adminlog', $data);
	}
}