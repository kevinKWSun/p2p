<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Cate_model extends CI_Model{
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
	//分类
    public function get_cate_one($id = '', $typename = ''){
		if($id){
			$this->db->where('id', $id);
		}
		if($typename){
			$this->db->where('name', $typename);
		}
        $query=  $this->db->get('y_cate');
        $result = $query->row_array();
        return $result;
    }
	
	public function get_cates_time($one = '', $tow = '', $a = 'y_order', $b = 'y_goods'){
		$this->db->select("$a.*, $b.gname, $b.score, $b.img");
		$this->db->from("$a");
		$this->db->join("$b", "$a.gid=$b.id", 'left');
        if($one && $tow){
			$this->db->where("$a.addtime between $one and $tow");
		}
        $query=  $this->db->get();
        $result = $query->result_array();
        return $result;
    }
	/* public function get_cates_time($one = '', $tow = ''){
        if($one && $tow){
			$this->db->where("addtime between $one and $tow");
		}
        $query=  $this->db->get('y_order');
        $result = $query->result_array();
        return $result;
    } */
	public function get_cate($page = 10, $per_page = 1){
        $this->db->limit($page, $per_page);
        $this->db->order_by('id DESC');
        $query=  $this->db->get('y_cate');
        $result = $query->result_array();
        return $result;
    }
    public function get_cate_num(){
        $sql = $this->db->count_all_results('y_cate');
        return $sql;
    }
	public function add_cate($data = array()){
        $sql = $this->db->insert('y_cate',$data);
        return $sql;
    }
	public function up_cate($data = array(), $id = ''){
        $this->db->where('id', $id);
        $sql = $this->db->update('y_cate', $data);
        return $sql;
    }
	//商品
	public function get_goods($page = 10, $per_page = 1, $a = 'y_goods', $b = 'y_cate'){
		$this->db->select("$a.*, $b.name");
		$this->db->from("$a");
		$this->db->join("$b", "$a.cid=$b.id", 'left');
        $this->db->limit($page, $per_page);
        $this->db->order_by("$a.id DESC");
        $query=  $this->db->get();
        $result = $query->result_array();
        return $result;
    }
    public function get_goods_num(){
        $sql = $this->db->count_all_results('y_goods');
        return $sql;
    }
	public function get_cates($status = ''){
		if($status){
			$this->db->where('status', $status);
		}
        $query=  $this->db->get('y_cate');
        $result = $query->result_array();
        return $result;
    }
	public function add_goods($data = array()){
        $sql = $this->db->insert('y_goods',$data);
        return $sql;
    }
	public function up_goods($data = array(), $id = ''){
        $this->db->where('id', $id);
        $sql = $this->db->update('y_goods', $data);
        return $sql;
    }
	public function get_goods_one($id = '', $name = ''){
		if($id){
			$this->db->where('id', $id);
		}
		if($name){
			$this->db->where('gname', $name);
		}
        $query=  $this->db->get('y_goods');
        $result = $query->row_array();
        return $result;
    }
	//订单
	public function get_order($uid = 0, $page = 10, $per_page = 1, $a = 'y_order', $b = 'y_goods'){
		$this->db->select("$a.*, $b.gname, $b.score, $b.img");
		$this->db->from("$a");
		$this->db->join("$b", "$a.gid=$b.id", 'left');
		if($uid){
			$this->db->where('uid', $uid);
		}
        $this->db->limit($page, $per_page);
        $this->db->order_by("{$a}.status ASC, {$a}.id DESC");
        $query =  $this->db->get();
        $result = $query->result_array();
        return $result;
    }
    public function get_order_num($uid = 0){
		if($uid){
			$this->db->where('uid', $uid);
		}
        $sql = $this->db->count_all_results('y_order');
        return $sql;
    }
	public function add_order($data = array()){
        $sql = $this->db->insert('y_order',$data);
        return $sql;
    }
	public function up_order($data = array(), $id = ''){
        $this->db->where('id', $id);
        $sql = $this->db->update('y_order', $data);
        return $sql;
    }
	public function get_order_one($id = '', $a = 'y_order', $b = 'y_goods'){
		$this->db->where("$a.id", $id);
		$this->db->select("$a.*, $b.gname, $b.score, $b.img");
		$this->db->from("$a");
		$this->db->join("$b", "$a.gid=$b.id", 'left');
        $query=  $this->db->get();
        $result = $query->row_array();
        return $result;
    }
	public function get_order_lock($data, $ids = array()){
        $this->db->where_in('id', $ids);
        $sql = $this->db->update('y_order', $data);
        return $sql;
    }
}

