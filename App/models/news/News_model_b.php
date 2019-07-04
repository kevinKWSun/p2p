<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class News_model extends CI_Model{
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    //page每页总数   per_page第几页从第几个下标开始
    public function get_newscate($page = 10, $per_page = 1){
        $this->db->limit($page, $per_page);
        $this->db->order_by('id DESC');
        $query=  $this->db->get('newscate');
        $result = $query->result_array();
        return $result;
    }
    //所有
    public function get_newscate_num(){
        $sql = $this->db->count_all_results('newscate');
        return $sql;
    }
    //增加
    public function add_newscate($data = array()){
        $this->db->insert('newscate',$data);
        $sql = $this->db->insert_id();
        return $sql;
    }
    //编辑
    public function modify_newscate($data = array(), $id){
        $this->db->where('id', $id);
        $sql = $this->db->update('newscate', $data);
        return $sql;
    }
    //查询
    public function get_newscate_byid($id){
        $this->db->where('id', $id);
        $query=  $this->db->get('newscate');
        $result = $query->row_array();
        return $result;
    }
    //page每页总数   per_page第几页从第几个下标开始
    public function get_news($page = 10, $per_page = 1){
        $this->db->limit($page, $per_page);
        $this->db->order_by('id DESC');
        $query=  $this->db->get('news');
        $result = $query->result_array();
        return $result;
    }
    //所有
    public function get_news_num(){
        $sql = $this->db->count_all_results('news');
        return $sql;
    }
    //增加
    public function add_news($data = array()){
        $this->db->insert('news',$data);
        $sql = $this->db->insert_id();
        return $sql;
    }
    //编辑
    public function modify_news($data = array(), $id){
        $this->db->where('id', $id);
        $sql = $this->db->update('news', $data);
        return $sql;
    }
    //查询
    public function get_news_byid($id){
        $this->db->where('id', $id);
        $query=  $this->db->get('news');
        $result = $query->row_array();
        return $result;
    }
}