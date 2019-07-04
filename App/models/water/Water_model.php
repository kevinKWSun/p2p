<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Water_model extends CI_Model{
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    //新增
    public function add_water($data){
        $this->db->insert('water',$data);
        $sql = $this->db->insert_id();
        //return $sql;
    }
	
	//修改
	public function edit_water($data, $merOrderNo) {
		$this->db->where('merOrderNo', $merOrderNo);
		$this->db->update('water', $data);
	}
	
	//查询根据订单号
    public function get_water_byorder($merOrderNo) {
		$this->db->where('merOrderNo', $merOrderNo);
		return $this->db->get('water')->row_array();
	}
	
	//根据业务流水号查询
	public function get_water_bybiz($bizFlow) {
		$this->db->where('bizFlow', $bizFlow);
		return $this->db->get('water')->row_array();
	}
	
	/** 根据ID查询流水信息 */
	public function get_water_byid($id) {
		$this->db->where('id', $id);
		return $this->db->get('water')->row_array();
	}
}

