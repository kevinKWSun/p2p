<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class News_model extends CI_Model{
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    public function get_newscate_one($id = '', $typename = ''){
		if($id){
			$this->db->where('id', $id);
		}
		if($typename){
			$this->db->where('type_name', $typename);
		}
        $query=  $this->db->get('newscate');
        $result = $query->row_array();
        return $result;
    }
	public function get_newscate($page = 10, $per_page = 1){
        $this->db->limit($page, $per_page);
        $this->db->order_by('id DESC');
        $query=  $this->db->get('newscate');
        $result = $query->result_array();
        return $result;
    }
    public function get_newscate_num(){
        $sql = $this->db->count_all_results('newscate');
        return $sql;
    }
	public function add_newscate($data = array()){
        $sql = $this->db->insert('newscate',$data);
        return $sql;
    }
	public function up_newscate($data = array(), $id = ''){
        $this->db->where('id', $id);
        $sql = $this->db->update('newscate', $data);
        return $sql;
    }//
	public function get_news($page = 10, $per_page = 1, $a = 'news', $b = 'newscate'){
		$this->db->where('del', 0);
		$this->db->select("$a.*, $b.type_name");
		$this->db->from("$a");
		$this->db->join("$b", "$a.cid=$b.id", 'left');
        $this->db->limit($page, $per_page);
        $this->db->order_by("$a.id DESC");
        $query=  $this->db->get();
        $result = $query->result_array();
        return $result;
    }
    public function get_news_num(){
		$this->db->where('del', 0);
        $sql = $this->db->count_all_results('news');
        return $sql;
    }
	public function get_newscates(){
        $query=  $this->db->get('newscate');
        $result = $query->result_array();
        return $result;
    }
	public function add_news($data = array()){
        $sql = $this->db->insert('news',$data);
        return $sql;
    }
	public function up_news($data = array(), $id = ''){
        $this->db->where('id', $id);
        $sql = $this->db->update('news', $data);
        return $sql;
    }
	public function get_news_one($id = ''){
		$this->db->where('id', $id);
        $query=  $this->db->get('news');
        $result = $query->row_array();
        return $result;
    }
	public function get_news_index($page = 10, $per_page = 1){
		$this->db->where('del', 0);
        $this->db->limit($page, $per_page);
        $this->db->order_by('id DESC');
        $query=  $this->db->get('news');
        $result = $query->result_array();
        return $result;
    }
	
	/** 根据分类ID调取一条数据 */
	public function get_one_bycid($cid) {
		$this->db->where('cid', $cid);
		$this->db->order_by('id', 'DESC');
        $query=  $this->db->get('news');
        $result = $query->row_array();
        return $result;
	}
	
	/** 根据分类获取所有列表分页 */
	public function get_news_all($cid, $page = 10, $per_page = 0) { 
		$this->db->from("news");
		$this->db->where('del', 0);
		$this->db->where("cid", $cid);
        $this->db->limit($page, $per_page);
        $this->db->order_by("id DESC");
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
	}
	/** 根据分类总数量 */
	public function get_news_all_num($cid) {
		$this->db->where('del', 0);
		$this->db->where('cid', $cid);
		$sql = $this->db->count_all_results('news');
        return $sql;
	}
	
	/** 调取分类所有文章 */ 
	public function get_news_alls($cid, $num = 3) {
		$this->db->from("news");
		$this->db->where("cid", $cid);
		$this->db->where('del', 0);
		$this->db->limit($num);
        $this->db->order_by("id DESC");
        $query = $this->db->get();
        $result = $query->result_array();
		//echo $this->db->last_query();
        return $result;
	}
	/** 调取运营列表信息 */
	public function get_news_operation() {
		$this->db->from("news");
		$this->db->where("cid", 10);
		$this->db->where('del', 0);
        $this->db->order_by("addtime DESC");
        $query = $this->db->get();
        $result = $query->result_array();
		//echo $this->db->last_query();
        return $result;
	}
	/** 调取信息披露信息 */
	public function get_news_xp($year) {
		$this->db->from("news");
		$this->db->where("cid", 12);
		$this->db->where('del', 0);
		$this->db->where('year', $year);
        return $this->db->get()->row_array();
	}
}