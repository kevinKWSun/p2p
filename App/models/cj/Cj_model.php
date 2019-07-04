<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Cj_model extends CI_Model{
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
	//商品
	public function get_goods($page = 10, $per_page = 1, $types = 0){
		if($types > 0){
			$this->db->where('types', $types);
		}
        $this->db->order_by("id DESC");
		$this->db->limit($page, $per_page);
        $query=  $this->db->get('activity');
        $result = $query->result_array();
        return $result;
    }
    public function get_goods_num($types = 0){
		if($types > 0){
			$this->db->where('types', $types);
		}
        $sql = $this->db->count_all_results('activity');
        return $sql;
    }

	public function add_goods($data = array()){
        $sql = $this->db->insert('activity',$data);
        return $sql;
    }
	public function up_goods($data = array(), $id = ''){
        $this->db->where('id', $id);
        $sql = $this->db->update('activity', $data);
        return $sql;
    }
	public function get_goods_one($id = '',$name = ''){
		if($id){
			$this->db->where('id', $id);
		}
		if($name){
			$this->db->where('name', $name);
		}
        $query=  $this->db->get('activity');
        $result = $query->row_array();
        return $result;
    }
	//订单
	/*public function get_order($uid = 0, $page = 10, $per_page = 1, $a = 'activity_u', $b = 'activity'){
		$this->db->select("$a.*, $b.name, $b.img");
		$this->db->from("$a");
		$this->db->join("$b", "$a.aid=$b.id", 'left');
		if($uid){
			$this->db->where('uid', $uid);
		}
        $this->db->limit($page, $per_page);
        $this->db->order_by("type ASC, id DESC");
        $query =  $this->db->get();
        $result = $query->result_array();
        return $result;
    }*/
	//获取中奖名称  新增
	public function get_group_activity(){
		$this->db->select("name");
		$this->db->group_by("name");
		$query=  $this->db->get('activity');
		$result = $query->result_array();
		return $result;
	}
	//订单
	public function get_order($uid = 0,$keyname=0, $page = 10, $per_page = 1, $a = 'activity_u', $b = 'activity'){
		$this->db->select("$a.*, $b.name, $b.img");
		$this->db->from("$a");
		$this->db->join("$b", "$a.aid=$b.id", 'left');
		if($uid){
			$this->db->where('uid', $uid);
		}
		if($keyname){
			$this->db->where('name', $keyname);
		}
		$this->db->limit($page, $per_page);
		$this->db->order_by("type ASC, id DESC");
		$query =  $this->db->get();
		$result = $query->result_array();
		return $result;
	}
	public function get_order_new_num($uid = 0, $keyname=0, $a = 'activity_u', $b = 'activity'){
		$this->db->select("$a.*, $b.name, $b.img");
		$this->db->from("$a");
		$this->db->join("$b", "$a.aid=$b.id", 'left');
		if($uid){
			$this->db->where('uid', $uid);
		}
		if($keyname){
			$this->db->where('name', $keyname);
		}
		return $this->db->count_all_results();  
	}
	public function get_order_nums($uid = 0){
		if($uid){
			$this->db->where('uid', $uid);
		}
		$this->db->where('aid < ', 13);
        $sql = $this->db->count_all_results('activity_u');
        return $sql;
    }
    public function get_order_num($uid = 0, $aid = 0){
		if($uid){
			$this->db->where('uid', $uid);
		}
		if($aid){
			$this->db->where('aid', $aid);
		}
        $sql = $this->db->count_all_results('activity_u');
        return $sql;
    }
	public function add_order($data = array()){
        $sql = $this->db->insert('activity_u',$data);
        return $sql;
    }
	public function up_order($data = array(), $id = ''){
        $this->db->where('id', $id);
        $sql = $this->db->update('activity_u', $data);
        return $sql;
    }
	public function get_order_one($id = '', $a = 'activity_u', $b = 'activity'){
		$this->db->where("$a.id", $id);
		$this->db->select("$a.*, $b.name, $b.img");
		$this->db->from("$a");
		$this->db->join("$b", "$a.aid=$b.id", 'left');
        $query=  $this->db->get();
        $result = $query->row_array();
        return $result;
    }
	public function get_order_byuid($uid = '',$aid = ''){
		if($uid){
			$this->db->where("uid", $uid);
		}
		if($aid){
			$this->db->where("aid", $aid);
		}
		$this->db->order_by('id DESC');
		$query=  $this->db->get('activity_u');
        $result = $query->row_array();
        return $result;
	}
	public function get_by_times($time, $aid = 0, $uid = 0){
		$one = $time[0];
		$tow = $time[1];
		$this->db->where("add_time between $one and $tow");
		if($aid){
			$this->db->where("aid", $aid);
		}
		if($uid){
			$this->db->where("uid", $uid);
		}
		$sql = $this->db->count_all_results('activity_u');
		return $sql;
	}
	public function get_order_lock($data, $ids = array()){
        $this->db->where_in('id', $ids);
        $sql = $this->db->update('activity_u', $data);
        return $sql;
    }
	//
	public function up_userinfo_score($uid, $score){
		$this->db->where('uid',$uid);
        $this->db->set('totalscore','totalscore - ' . $score, FALSE);
        $result =  $this->db->update('members_info');
        return $result;
	}
	public function up_userinfo_times($uid, $times){
		$this->db->where('uid',$uid);
        $this->db->set('times','times - ' . $times, FALSE);
        $result =  $this->db->update('members_info');
        return $result;
	}
	public function up_userinfo_doubles($uid, $times){
		$this->db->where('uid',$uid);
        $this->db->set('doubles','doubles - ' . $times, FALSE);
        $result =  $this->db->update('members_info');
        return $result;
	}
	public function up_prize_num($id, $num){
		$this->db->where('id',$id);
        $this->db->set('num','num - ' . $num, FALSE);
        $result =  $this->db->update('activity');
        return $result;
	}
	public function get_order_times($one = '', $tow = '', $a = 'activity_u', $b = 'activity'){
		$this->db->select("$a.*, $b.name, $b.img");
		$this->db->from("$a");
		$this->db->join("$b", "$a.aid=$b.id", 'left');
		if($one && $tow){
			$this->db->where("$a.add_time between $one and $tow");
		}
        $this->db->order_by("type ASC, id DESC");
        $query =  $this->db->get();
        $result = $query->result_array();
        return $result;;
	}
}

