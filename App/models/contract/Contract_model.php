<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Contract_model extends CI_Model{
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
	
	/** 获取列表 */
	public function get_contract_list($page = 10, $per_page = 1) {
		$this->db->where('del', 0);
        $this->db->limit($page, $per_page);
        $this->db->order_by('id DESC');
        $query=  $this->db->get('contract');
        $result = $query->result_array();
        return $result;
	}
	/** 获取总数量 */
    public function get_contract_nums(){
		$this->db->where('del', 0);
        $sql = $this->db->count_all_results('contract');
        return $sql;
    }
	/** 添加 */
	public function add_contract($data) {
		return $this->db->insert('contract', $data);
	}
	/** 查询一条 */
	public function get_contract_one($id) {
		return $this->db->where(array('id'=>$id))->get('contract')->row_array();
	}
	
	/** 修改 */
	public function modify_contract($data) {
		return $this->db->where(array('id'=>$data['id']))->update('contract', $data);
	}
	
	/** 根据订单号查询一条已生成的合同 */
	public function get_contract_pdf_bynid($nid) {
		return $this->db->where(array('nid'=>$nid))->get('contract_pdf')->row_array();
	}
	
	/** 根据投资ID，获取一条已生成的合同*/
	public function get_contract_pdf_byinvestid($invest_id) {
		return $this->db->where(array('invest_id'=>$invest_id))->get('contract_pdf')->row_array();
	}
	
	/** 添加或更新一条pdf合同信息 */
	public function modify_contract_pdf($data) {
		if(isset($data['id'])) {
			return $this->db->where(array('id'=>$data['id']))->update('contract_pdf', $data);
		} else {
			return $this->db->insert('contract_pdf', $data);
		}
	}
	
	/* 插入一条成功签署文档的流水 */
	public function add_contract_water($data) {
		return $this->db->insert('contract_water', $data);
	}
	
	/** 根据订单号查询签署记录ID */
	public function get_water_bynid($nid) {
		$this->db->where('uid', 0);
		$this->db->where('nid', $nid);
		$this->db->order_by('id DESC');
		return $this->db->get('contract_water')->row_array();
	}
	
	/** 添加一条验证码数据 */
	public function add_sendcode($data) {
		return $this->db->insert('contract_sendcode', $data);
	}
	
	/** 添加一条易签宝返回数据 */
	public function add_ret($data) {
		return $this->db->insert('contract_ret', $data);
	}
}