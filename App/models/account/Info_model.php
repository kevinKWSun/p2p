<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Info_model extends CI_Model{
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
	
	public function get_money_log($page = 10, $per_page = 1, $uids,$type = 0){
		if($type){
			$this->db->where('type', $type);
		}
		$this->db->where('uid', $uids);
		$this->db->limit($page, $per_page);
		$this->db->order_by('add_time DESC');
		$query=  $this->db->get('members_moneylog');
		$result = $query->result_array();
		return $result;
	}
	//所有
	public function get_money_log_num($uids, $type = 0){
		if($type){
			$this->db->where('type', $type);
		}
		$this->db->where('uid', $uids);
		$sql = $this->db->count_all_results('members_moneylog');
		return $sql;
	}
	
	//按月份交易流水
	public function get_month_moneylog($time, $uid = 0, $type = 0){
		if($time){
			$this->db->where("add_time  between " .$time['begin']." and ".$time['end']);
		}
		if($type){
			$this->db->where('type', $type);
		}
		if($uid){
			$this->db->where('uid', $uid);
		}
		$this->db->order_by('add_time DESC');
		$query=  $this->db->get('members_moneylog');
		$result = $query->result_array();
		return $result;
	}
	//回款日期
	public function get_investor_detail_date($uid){
		$this->db->select("date_format(from_unixtime(deadline),'%Y-%m-%d') as times");		
		$this->db->group_by("times");
		$this->db->where('investor_uid', $uid);
		$query = $this->db->get('investor_detail'); 
		return $query->result_array();		
	}
	//按日期查询回款金额
	public function get_investor_detail_days($uid,$days){
		$this->db->select("capital,interest,receive_interest,receive_capital");
		$this->db->where('investor_uid', $uid);
		$this->db->where('status <>', 6);
		$this->db->where("date_format(from_unixtime(deadline),'%Y-%m-%d')", $days);
		$query = $this->db->get('investor_detail'); 
		return $query->result_array();	
	}
	//查询本月应回款数据
	public function get_investor_month_info($uid,$month){
		$this->db->where('investor_uid', $uid);
		$this->db->where("date_format(from_unixtime(deadline),'%Y-%m')", $month);
		$this->db->select_sum('interest', 'month_interest');
		$this->db->select_sum('capital', 'month_capital');		
		$query = $this->db->get('investor_detail'); 
		return $query->row_array();	
	}
	//查询本月已回款数据
	public function get_investor_current_info($uid,$time,$current){
		$this->db->where('investor_uid', $uid);
		$this->db->where('repayment_time > ', $time);
		$this->db->where('repayment_time < ', $current);		
		$this->db->select_sum('receive_interest', 'current_interest');
		$this->db->select_sum('receive_capital', 'current_capital');
		$query = $this->db->get('investor_detail'); 
		return $query->row_array();
	}	
	//累计收益
	public function ljsy($uid){
		$this->db->where('repayment_time > ', 0);
		$this->db->where('investor_uid', $uid);
		$this->db->select_sum('receive_interest', 'ljsy');
		$query = $this->db->get('investor_detail'); 
		return $query->row_array();
	}
	//待收收益
	public function get_sy_m($uid){
		$this->db->where('repayment_time = ', 0);
		$this->db->where('investor_uid', $uid);
		$this->db->select_sum('receive_interest', 'tm');
		$this->db->select_sum('interest', 'tml');
		$query = $this->db->get('investor_detail'); 
		return $query->row_array();
	}
	//提现会员信息
	public function get_members_quickbank($uid){
		$this->db->where('uid', $uid);
		$query=  $this->db->get('members_quickbank');
		$result = $query->row_array();
		return $result;
	}
	//可用余额 members_money
	public function get_money($uid){
        $this->db->where('uid', $uid);
        $query=  $this->db->get('members_money');
        $result = $query->row_array();
        return $result;
    }
	//待收本金 borrow_investor
	public function get_ds_m($uid){
		$this->db->where('investor_uid', $uid);
		$this->db->where_in('status', array(2,4));
		$this->db->select_sum('investor_capital', 'c');
		$this->db->select_sum('receive_capital', 'rc');
		$query = $this->db->get('borrow_investor'); 
		return $query->row_array();
	}
	public function get_packets_money($uid){
		$this->db->where('uid', $uid);
		$this->db->where('type', 1);
		$this->db->where('status < ', 1);
		$this->db->where('etime > ', time());
		$this->db->select_sum('money', 'packets');
		$query = $this->db->get('packet'); 
		return $query->row_array();
	}
	public function get_today_money($uid){
		$this->db->where('uid', $uid);
		$this->db->where('type', 1);
		$this->db->where('status', 1);
		$this->db->where('add_time between '.strtotime(date('Y-m-d', time())).' and '.strtotime(date('Y-m-d 23:59:59', time())));
		$this->db->select_sum('money', 'money');
		$query = $this->db->get('members_payonline'); 
		return $query->row_array();
	}
	public function get_investors($codeuid){
		$this->db->select('id');
		$this->db->where('codeuid', $codeuid);
		$query = $this->db->get('members'); 
		return $query->result_array();
	}
	//推荐出借人列表
	public function get_borrow_investors_list($page = 10, $per_page = 1, $codeuid){
		$this->db->where('codeuid', $codeuid);
		$this->db->limit($page, $per_page);
		$query = $this->db->get('members'); 
		return $query->result_array();
	}
	public function get_borrow_investors_list_num($codeuid){
		$this->db->where('codeuid', $codeuid);
		$sql = $this->db->count_all_results('members');
        return $sql;
	}
	public function get_borrow_investors($buid, $status=array(4,5,6,7,8,9)){
		$this->db->select('sum(investor_capital) as money');
		$this->db->where_in('investor_uid', $buid);
		$this->db->where_in('status', $status);
		$query = $this->db->get('borrow_investor'); 
		return $query->row_array();//get_investorss
	}
	public function get_borrow_investors_lists($page = 10, $per_page = 1, $uids, $time = '', $uid = 0, $a = 'borrow_investor', $b = 'members', $c = 'members_info', $d = 'borrow'){
		$this->db->from("$a");
		$this->db->select("$a.borrow_id,$a.investor_capital,$a.add_time,$b.reg_time,$c.phone,$c.real_name,$d.borrow_name,$d.borrow_duration,$d.borrow_type");
		$this->db->where_in("$a.investor_uid", $uids);
		$this->db->where_in("$a.status", array(4,5,6,7,8,9));
		if(!empty($uid)) {
			$this->db->where("$b.codeuid", $uid);
		}
		if($time){
			$this->db->where("$a.add_time  between " .$time[0]." and ".$time[1]);
		}
        $this->db->limit($page, $per_page);
        $this->db->order_by("$a.id DESC");
		$this->db->join("$b", "$a.investor_uid=$b.id", 'left');
		$this->db->join("$c", "$a.investor_uid=$c.uid", 'left');
		$this->db->join("$d", "$a.borrow_id=$d.id", 'left');
        $query=  $this->db->get();
        $result = $query->result_array();
        return $result;
    }
	public function get_borrow_investors_lists_nums($uids, $time = '', $uid = 0){
		$a = 'borrow_investor'; 
		$b = 'members'; 
		$c = 'members_info'; 
		$d = 'borrow';
		$this->db->where_in("$a.investor_uid", $uids);
		if(!empty($uid)) {
			$this->db->where("$b.codeuid", $uid);
		}
		if($time){
			$this->db->where("$a.add_time  between " .$time[0]." and ".$time[1]);
		}
		$this->db->from("$a");
		$this->db->join("$b", "$a.investor_uid=$b.id", 'left');
		$this->db->join("$c", "$a.investor_uid=$c.uid", 'left');
		$this->db->join("$d", "$a.borrow_id=$d.id", 'left');
		$sql = $this->db->count_all_results();
        return $sql;
	}
	public function get_borrow_investors_lists_num($uids, $time = ''){
		$this->db->where_in('investor_uid', $uids);
		$this->db->where_in('status', array(4,5,6,7,8,9));
		
		if($time){
			$this->db->where("add_time  between " .$time[0]." and ".$time[1]);
		}
		$sql = $this->db->count_all_results('borrow_investor');
        return $sql;
	}
	//交易流水
	public function get_moneylog($page = 10, $per_page = 1, $uids, $time = '', $type = 0){
		if($time){
			$this->db->where("add_time  between " .$time[0]." and ".$time[1]);
		}
		if($type){
			$this->db->where('type', $type);
		}
		$this->db->where('uid', $uids);
        $this->db->limit($page, $per_page);
        $this->db->order_by('add_time DESC');
        $query=  $this->db->get('members_moneylog');
        $result = $query->result_array();
        return $result;
    }
    //所有
    public function get_moneylog_num($uids, $time = '', $type = 0){
		if($time){
			$this->db->where("add_time  between " .$time[0]." and ".$time[1]);
		}
		if($type){
			$this->db->where('type', $type);
		}
		$this->db->where('uid', $uids);
        $sql = $this->db->count_all_results('members_moneylog');
        return $sql;
    }
	/* 
    //page每页总数   per_page第几页从第几个下标开始
    public function get_member($page = 10, $per_page = 1){
        $this->db->limit($page, $per_page);
        $this->db->order_by('id DESC');
        $query=  $this->db->get('members');
        $result = $query->result_array();
        return $result;
    }
    //所有
    public function get_member_num(){
        $sql = $this->db->count_all_results('members');
        return $sql;
    }
    //增加
    public function add_member($data = array()){
        $this->db->insert('members',$data);
        $sql = $this->db->insert_id();
        return $sql;
    }
    //编辑
    public function modify_member($data = array(), $id){
        $this->db->where('id', $id);
        $sql = $this->db->update('members', $data);
        return $sql;
    }
    //会员信息
    public function get_member_info_byuid($uid){
        $this->db->where('uid', $uid);
        $query=  $this->db->get('members_info');
        $result = $query->row_array();
        return $result;
    }
    //查询金额表
    public function get_members_moneylog($uid){
        $this->db->where('uid', $uid);
        $query=  $this->db->get('members_moneylog');
        $result = $query->row_array();
        return $result;
    }
    //增加金额日志表
    public function add_members_moneylog($data = array()){
        $sql = $this->db->insert('members_moneylog',$data);
        return $sql;
    }
    // //编辑金额日志表
    // public function up_members_moneylog($data = array(), $uid){
    //     $this->db->where('uid', $uid);
    //     $sql = $this->db->update('members_moneylog', $data);
    //     return $sql;
    // }
    //增加金额表
    public function add_members_money($data = array()){
        $sql = $this->db->insert('members_money',$data);
        return $sql;
    }
    //编辑金额表
    public function up_members_money($data = array(), $uid){
        $this->db->where('uid', $uid);
        $sql = $this->db->update('members_money', $data);
        return $sql;
    }
    //会员登录信息
    public function get_member_byusername($user_name){
        $this->db->where('user_name', $user_name);
        $query=  $this->db->get('members');
        $result = $query->row_array();
        return $result;
    } */
}

