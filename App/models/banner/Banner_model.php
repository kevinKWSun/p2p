<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Banner_model extends CI_Model {
	public function __construct() {
        parent::__construct();
        $this->load->database();
    }
	
	/** 分页查询列表 */
	public function get_banner_lists($page = 10, $per_page = 1, $where = array()) {
		$this->db->where('del', 0);
		$this->db->order_by('sort desc, id desc');
		return $this->db->get('banner')->result_array();
	}
	/** 分页查询列表 总记录数 */
	public function get_banner_lists_nums($where = array()) {
		return $this->db->count_all_results('banner');
	}
	
	/** 新增一条数据 */
	public function add($data) {
		return $this->db->insert('banner', $data);
	}
	
	/** 根据ID查询一条数据 */
	public function get_banner_byid($id) {
		return $this->db->where(array('id'=>$id))->get('banner')->row_array();
	}
	
	/** 根据ID修改一条数据 */
	public function modify($data) {
		return $this->db->where('id', $data['id'])->update('banner', $data);
	}
}