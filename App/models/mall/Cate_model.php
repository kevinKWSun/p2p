<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Cate_model extends CI_Model{
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
	
	public function get_score_all_num($uid = ''){
		$this->db->set_dbprefix('');
		$from = "((SELECT b.uid, gname as gid ,b.sscore,b.addtime FROM `xm_y_order` b LEFT JOIN xm_y_goods c ON b.gid = c.id )
			UNION ALL 
			(SELECT uid,`genre` as gid, score as sscore, addtime  FROM `xm_score` ))";
		if($uid){
			$this->db->where("a.uid", $uid);
		}
		$sql = $this->db->count_all_results("$from a");
		return $sql;
	}
	//积分明细
	public function get_score_all($uid = '',$page = 10, $per_page = 1){
		$this->db->set_dbprefix('');
		$from = "((SELECT b.uid, gname as gid ,c.score,b.addtime FROM `xm_y_order` b LEFT JOIN xm_y_goods c ON b.gid = c.id )
			UNION ALL 
			(SELECT uid,`genre` as gid, score, addtime  FROM `xm_score` ))";
		if($uid){
			$this->db->where("a.uid", $uid);
		}
		$this->db->from("$from a");
		$this->db->limit($page, $per_page);
		$this->db->order_by('a.addtime  desc');
		$query=  $this->db->get();
		$result = $query->result_array();
		return $result;			
		
	}
	
	public function get_cates(){
		$this->db->where('status', 1);
        $query=  $this->db->get('y_cate');
        $result = $query->result_array();
        return $result;
    }
	//商品
	public function get_goods($cid, $page = 10, $per_page = 1){
		if($cid){
			$this->db->where('cid', $cid);
		}
		$this->db->where('gstatus', 1);
        $this->db->limit($page, $per_page);
        $this->db->order_by("id DESC");
        $query=  $this->db->get('y_goods');//return $this->db->last_query();
        $result = $query->result_array();
        return $result;
    }
    public function get_goods_num($cid){
		if($cid){
			$this->db->where('cid', $cid);
		}
		$this->db->where('gstatus', 1);
        $sql = $this->db->count_all_results('y_goods');
        return $sql;
    }
	public function get_goods_one($id){
		$this->db->where('id', $id);
		$this->db->where('gstatus', 1);
		$query=  $this->db->get('y_goods');
        $result = $query->row_array();
        return $result;
    }
	//订单
	public function get_order($uid, $page = 10, $per_page = 1, $a = 'y_order', $b = 'y_goods'){
		$this->db->select("$a.*, $b.gname, $b.score, $b.img");
		$this->db->from("$a");
		$this->db->join("$b", "$a.gid=$b.id", 'left');
		$this->db->where('uid', $uid);
        $this->db->limit($page, $per_page);
        $this->db->order_by("{$a}.status ASC, {$a}.id DESC");
        $query=  $this->db->get();
        $result = $query->result_array();
        return $result;
    }
    public function get_order_num($uid){
		$this->db->where('uid', $uid);
        $sql = $this->db->count_all_results('y_order');
        return $sql;
    }
	public function add_order($data = array()){
        $sql = $this->db->insert('y_order',$data);
        return $sql;
    }
	//地址
	public function get_address($uid, $page = 10, $per_page = 1){
		$this->db->where('uid', $uid);
        $this->db->limit($page, $per_page);
        $this->db->order_by("id DESC");
        $query=  $this->db->get('y_address');//return $this->db->last_query();
        $result = $query->result_array();
        return $result;
    }
    public function get_address_num($uid){
		$this->db->where('uid', $uid);
        $sql = $this->db->count_all_results('y_address');
        return $sql;
    }
	public function add_address($data = array()){
        $sql = $this->db->insert('y_address',$data);
        return $sql;
    }
	public function up_address($data = array(), $id = '', $uid = ''){
        $this->db->where('id', $id);
		$this->db->where('uid', $uid);
        $sql = $this->db->update('y_address', $data);
        return $sql;
    }
	public function get_address_one($id = '', $uid = '', $address = ''){
		if($id){
			$this->db->where('id', $id);
		}
		$this->db->where('uid', $uid);
		if($address){
			$this->db->where('address', $address);
		}
        $query=  $this->db->get('y_address');
        $result = $query->row_array();
        return $result;
    }
	public function get_address_more($uid = ''){
		$this->db->where('uid', $uid);
        $query=  $this->db->get('y_address');
        $result = $query->result_array();
        return $result;
    }
	//商品数量
	public function up_goods_num($id, $num){
		$this->db->where('id',$id);
        $this->db->set('num','num - ' . intval($num), FALSE);
        $result =  $this->db->update('y_goods');
        return $result;
	}
	//积分更改
	public function up_userinfo_score($uid, $score){
		$this->db->where('uid',$uid);
        $this->db->set('totalscore','totalscore - ' . $score, FALSE);
        $result =  $this->db->update('members_info');
        return $result;
	}
	/** 抽奖次数更改 */
	public function up_userinfo_times($uid, $times) {
		$this->db->where('uid',$uid);
        $this->db->set('times','times + ' . $times, FALSE);
        $result =  $this->db->update('members_info');
        return $result;
	}
	
	public function get_order_score($uid = '', $a = 'y_order', $b = 'y_goods' ){
		$this->db->select("$b.gname,concat_ws('-','',xm_y_goods.score)as score,$a.addtime");
		$this->db->from("$a");
		$this->db->join("$b", "$a.gid=$b.id", 'left');
		if($uid){
			$this->db->where("$a.uid", $uid);
		}
		$query=  $this->db->get();
		$result = $query->result_array();
		return $result;
		
	}
	public function get_grant_score($uid = ''){
		if($uid){
			$this->db->where("uid", $uid);			
		}
		$this->db->select("genre as gname,score,addtime");
		$this->db->from("score");
		$query=  $this->db->get();
		$result = $query->result_array();
		return $result;
	}
}

