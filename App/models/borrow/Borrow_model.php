<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Borrow_model extends CI_Model{
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
	public function get_investor_detail_list($invest_id = 0){
		if($invest_id){
			$this->db->where('invest_id', $invest_id);
		}
		$this->db->where('(status > 4 or repayment_time > 0)');

		$this->db->order_by('deadline', 'ASC');
		$query = $this->db->get('investor_detail');
		$result = $query->result_array();
		return $result;
	}
	public function get_totalmoney(){
		//$this->db->where('status >=', 4);
		$this->db->select_sum('investor_capital', 'totalmoney');
		$query=  $this->db->get('borrow_investor');
		$result = $query->row_array();
        return $result;
	}
	public function get_totalmember(){
		$sql = $this->db->count_all_results('borrow_investor');
		return $sql;
	}
	public function get_totalsmoney(){
		//$this->db->where('status >=', 4);
		$this->db->select_sum('receive_interest', 'totalsmoney');
		$query=  $this->db->get('borrow_investor');
		$result = $query->row_array();
        return $result;
	}
    //page每页总数   per_page第几页从第几个下标开始
    public function get_borrow($page = 10, $per_page = 1, $borrow_uid = 0){
		if($borrow_uid > 0) {
			$this->db->where('id >', 0);
			$this->db->where('borrow_uid', $borrow_uid);
			$this->db->where('borrow_status > ', 1);
		}
        $this->db->limit($page, $per_page);
        $this->db->order_by('id DESC');
        $query=  $this->db->get('borrow');
        $result = $query->result_array();
        return $result;
    }
	//所有
    public function get_borrow_num($borrow_uid = 0){
		if($borrow_uid > 0) {
			$this->db->where('borrow_uid', $borrow_uid);
			$this->db->where('borrow_status > ', 1);
		}
        $sql = $this->db->count_all_results('borrow');
        return $sql;
    }
	//后台调用列表数据
    public function get_borrow_list($page = 10, $per_page = 1, $where = array()){
		if(isset($where['skey'])) {
			$this->db->like("concat(id, borrow_name, borrow_no)", $where['skey']);
			unset($where['skey']);
		}
		if(!empty($where)) {
			foreach($where as $k=>$v) {
				$this->db->where($k, $v);
			}
		}
        $this->db->limit($page, $per_page);
        $this->db->order_by('id DESC');
        $query=  $this->db->get('borrow');
        $result = $query->result_array();
        return $result;
    }
    //后台调用列表数据 所有
    public function get_borrow_nums($where = array()){
		if(isset($where['skey'])) {
			$this->db->like("concat(id, borrow_name, borrow_no)", $where['skey']);
			unset($where['skey']);
		}
		if(!empty($where)) {
			foreach($where as $k=>$v) {
				$this->db->where($k, $v);
			}
		}
        return $this->db->count_all_results('borrow');
    }
	
	// 后台调用列表数据关联公司信息
	public function get_borrow_related($page = 10, $per_page = 1, $where = array()) {
		$master = 'borrow';
		$sub = 'members_info';
		if(isset($where['skey'])) {
			$this->db->like("concat(xm_{$master}.id, xm_{$master}.borrow_name, xm_{$master}.borrow_no)", $where['skey']);
			unset($where['skey']);
		}
		if(isset($where['name'])) {
			$this->db->like("concat(xm_{$sub}.uid, xm_{$sub}.real_name)", $where['name']);
			unset($where['name']);
		}
		if(isset($where['guarantor'])) {
			$this->db->where("find_in_set(".$where['guarantor'].", guarantor)", NULL, FALSE);
			unset($where['guarantor']);
		}
		if(!empty($where)) {
			foreach($where as $k=>$v) {
				$this->db->where("xm_{$master}.".$k, $v);
			}
		}
		$this->db->limit($page, $per_page);
        $this->db->order_by('id DESC');
		$this->db->from($master);
        $this->db->join($sub, "$master.borrow_uid = $sub.uid", 'left');
		$query = $this->db->get()->result_array();
		return $query;
	}
	public function get_borrow_related_nums($where = array()){ 
		$master = 'borrow';
		$sub = 'members_info';
		if(isset($where['skey'])) {
			$this->db->like("concat(xm_{$master}.id, xm_{$master}.borrow_name, xm_{$master}.borrow_no)", $where['skey']);
			unset($where['skey']);
		}
		if(isset($where['name'])) {
			$this->db->like("concat(xm_{$sub}.uid, xm_{$sub}.real_name)", $where['name']);
			unset($where['name']);
		}
		if(isset($where['guarantor'])) {
			$this->db->where("find_in_set(".$where['guarantor'].", guarantor)", NULL, FALSE);
			unset($where['guarantor']);
		}
		if(!empty($where)) {
			foreach($where as $k=>$v) {
				$this->db->where("xm_{$master}.".$k, $v);
			}
		}
		$this->db->from($master);
        $this->db->join($sub, "$master.borrow_uid = $sub.uid", 'left');
		return $this->db->count_all_results();
    }
	
	/** 获取借款记录 */
	public function get_borrow_byborrow_uid($uid, $where = array()) {
		if(!empty($where)) {
			foreach($where as $k=>$v) {
				$this->db->where($k, $v);
			}
		}
		$res = $this->db->where('borrow_uid', $uid)->get('borrow')->result_array();
		return $res;
		
	}
	
    //增加
    public function add_borrow($data = array()){
        $this->db->insert('borrow',$data);
        $sql = $this->db->insert_id();
        return $sql;
    }
	public function add_borrow_uinfo($data = array()){
        $this->db->insert('borrow_uinfo',$data);
        $sql = $this->db->insert_id();
        return $sql;
    }
	public function get_borrow_uinfo_byuid($uid){
        $this->db->where('uid', $uid);
        $query=  $this->db->get('borrow_uinfo');
        $result = $query->row_array();
        return $result;
    }
    //编辑主表
    public function modify_borrow($data = array(), $id){
        $this->db->where('id', $id);
        $sql = $this->db->update('borrow', $data);
        return $sql;
    }
	//增加第二张表
    public function add_borrow_investor($data = array()){
        $this->db->insert('borrow_investor',$data);
        $sql = $this->db->insert_id();
        return $sql;
    }
    //编辑第二张表
    public function modify_borrow_investor($data = array(), $id = '', $kid = ''){
        if($id){
            $this->db->where('borrow_id', $id);
        }
        if($kid){
            $this->db->where('id', $kid);
        }
        $sql = $this->db->update('borrow_investor', $data);
        return $sql;
    }
    //查询第二张表
    public function get_borrow_investor_byid($id = '', $kid = ''){
        if($id){
            $this->db->where('borrow_id', $id);
        }
        if($kid){
            $this->db->where('id', $kid);
        }
        $query=  $this->db->get('borrow_investor');
        $result = $query->result_array();
        return $result;
    }
	/** 查询第二张表 根据查询条件*/
	public function get_investor_bywhere($where) {
		foreach($where as $k=>$v) {
			$this->db->where($k, $v);
		}
		return $this->db->get('borrow_investor')->result_array();
	}
    //查询第三张表
    public function get_investor_detail_byid($id = '', $kid = '', $iid = ''){
        if($id){
            $this->db->where('borrow_id', $id);
        }
        if($iid){
            $this->db->where('invest_id', $iid);
        }
        if($kid){
            $this->db->where('id', $kid);
        }
		$this->db->order_by('deadline', 'ASC');
        $query=  $this->db->get('investor_detail');
        $result = $query->result_array();
        return $result;
    }
	/** 批量更新还款详情表（第三张表）*/
	public function modify_detail_all($data) {
		return $this->db->update_batch('investor_detail', $data, 'id', 500);
	}
	//获取一条数据
	public function get_detail_one($id) {
		return $this->db->where(array('id'=>$id))->get('investor_detail')->row_array();
	}
	/** 根据查询条件获取第三张表数据 */
	public function get_detail_bywhere($where) {
		foreach($where as $k=>$v) {
			$this->db->where($k, $v);
		}
		$this->db->order_by('id asc');
		return $this->db->get('investor_detail')->result_array();
	}
	/** 根据borrow_id获取第三张表最后一次还款数据 */
	public function get_detail_one_bybid($bid) {
		return $this->db->where(array('borrow_id'=>$bid))->order_by('id DESC')->get('investor_detail')->row_array();
	}
	//判断是否是最后一笔还款
	public function is_last_detail($id, $bid) {
		$this->db->where('id <>', $id);
		$this->db->where('repayment_time =', 0);
		$this->db->where('borrow_id = ', $bid);
		$this->db->where('capital >', 0);
		return $this->db->count_all_results('investor_detail');
	}
	//增加第三张表
    public function add_investor_detail($data = array()){
        $this->db->insert('investor_detail',$data);
        $sql = $this->db->insert_id();
        return $sql;
    }
    //编辑第三张表
    public function modify_investor_detail($data = array(), $id = '', $kid = '', $iid = ''){
        if($id){
            $this->db->where('borrow_id', $id);
        }
        if($iid){
            $this->db->where('invest_id', $iid);
        }
        if($kid){
            $this->db->where('id', $kid);
        }
        $sql = $this->db->update('investor_detail', $data);
        return $sql;
    }
	//uuid
	public function add_uuid($data = array()){
        $this->db->insert('uuid',$data);
        $sql = $this->db->insert_id();
        return $sql;
    }
	public function up_uuid($data = array()){
        return $this->db->where('id', $data['id'])->update('uuid', $data);
    }
	public function add_outfind($data = array()){
        $this->db->insert('outfind',$data);
        $sql = $this->db->insert_id();
        return $sql;
    }
	public function get_uuid_byuuid($id){
        $this->db->where('uuid', $id);
        $query=  $this->db->get('uuid');
        $result = $query->row_array();
        return $result;
    }
    //通过ID查询数据
    public function get_borrow_byid($id){
        $this->db->where('id', $id);
        $query=  $this->db->get('borrow');
        $result = $query->row_array();
        return $result;
    }
    //前台数据
    public function get_borrow_index($page = 10, $per_page = 1){
        $this->db->where('del', 0);
		$this->db->where('borrow_status >=', 2);
		$this->db->where('id>', 1);
        $this->db->limit($page, $per_page);
        $this->db->order_by('borrow_status ASC, endtime DESC');
        $query=  $this->db->get('borrow');
        $result = $query->result_array();
        return $result;
    }
    //前台条数数据
    public function get_borrow_num_index(){
        $this->db->where('del', 0);
		 $this->db->where('id>', 1);
		$this->db->where('borrow_status >=', 2);
        $sql = $this->db->count_all_results('borrow');
        return $sql;
    }
	//放款解冻失败记录
	public function add_error($data){
		return $this->db->insert('error',$data);
	}
	//求合
	public function get_moneys($id){
		$this->db->where('borrow_id', $id);
		$this->db->select_sum('investor_interest');
		$query = $this->db->get('borrow_investor');
		$result = $query->row_array();
        return $result;
	}
	public function get_moneys_hk($investor_uid, $borrow_uid, $one = '', $tow = ''){
		if($borrow_uid){
			$this->db->where('borrow_uid', $borrow_uid);
		}
		if($investor_uid){
			$this->db->where('investor_uid', $investor_uid);
		}
		$this->db->where('status', 4);
		if($one && $tow){
			$this->db->where("deadline between $one and $tow");
		}
		$this->db->select_sum('investor_capital');
		$this->db->select_sum('investor_interest');
		$query = $this->db->get('borrow_investor');
		$result = $query->row_array();
        return $result;
	}
	//导出投资数据 2018-10-30 11:24
	public function get_borrow_investor_excel($one = '', $tow = '', $a = 'borrow_investor', $b = 'borrow'){
		$this->db->from("$a");
		$this->db->select("$a.id as invest_id, $a.*, $b.borrow_name,$b.borrow_duration,$b.borrow_status,$b.borrow_no,$b.repayment_type");
		$this->db->join("$b", "$a.borrow_id=$b.id", 'left');
		if($one && $tow){
			$this->db->where("$a.add_time between $one and $tow");
		}
        //$this->db->order_by('$a.add_time ASC');
        $query=  $this->db->get();
        $result = $query->result_array();
		//echo $this->db->last_query();die;
        return $result;
    }
	public function get_investor_detail_byid_uid($id = '', $uid = ''){
		$this->db->where("invest_id", $id);
		$this->db->where("investor_uid", $uid);
		$this->db->where("repayment_time", 0);
		$this->db->order_by('id ASC');
		$query=  $this->db->get('investor_detail');
        $result = $query->row_array();
        return $result;
	}
	public function get_investor_detail_byid_uid_status($id = '', $uid = '', $status = 0){
		$this->db->where("invest_id", $id);
		$this->db->where("investor_uid", $uid);
		if($status < 5){
			$this->db->where("repayment_time", 0);
		}
		$this->db->order_by('id ASC');
		$query=  $this->db->get('investor_detail');
        $result = $query->row_array();
        return $result;
	}
	//投资者
	public function get_borrow_investor($page = 10, $per_page = 1, $status = '', $investor_uid, $a = 'borrow_investor', $b = 'borrow'){
		$this->db->from("$a");
		if($investor_uid){
			$this->db->where("$a.investor_uid", $investor_uid);
		}
		if($status == 2){
			$this->db->where_in("$b.borrow_status", array(2,3));
		}else if($status == 4){
			$this->db->where("$b.borrow_status", $status);
		} else if($status == 5){
			$this->db->where("$b.borrow_status >=", 5);
		}
		$this->db->select("$a.id as invest_id, $a.*, $b.borrow_name,$b.borrow_money,$b.add_rate,$b.borrow_duration,$b.borrow_interest_rate,$b.borrow_status,$b.endtime,$b.borrow_no,$b.repayment_type");
		$this->db->join("$b", "$a.borrow_id=$b.id", 'left');
        $this->db->limit($page, $per_page);
        $this->db->order_by('add_time ASC');
        $query=  $this->db->get();
        $result = $query->result_array();
        return $result;
    }
    //所有
    public function get_borrow_investor_num($status = '', $investor_uid, $a = 'borrow_investor', $b = 'borrow'){
		$this->db->from("$a");
		if($investor_uid){
			$this->db->where("$a.investor_uid", $investor_uid);
		}
		if($status == 2){
			$this->db->where_in("$b.borrow_status", array(2,3));
		}else if($status == 4){
			$this->db->where("$b.borrow_status", $status);
		} else if($status == 5){
			$this->db->where("$b.borrow_status >=", 5);
		}
		$this->db->join("$b", "$a.borrow_id=$b.id", 'left');
        $sql = $this->db->count_all_results();
        return $sql;
    }
	// 判断是否投资过新手标
	public function get_borrow_investor_type_num($type = '', $investor_uid) {
		$a = 'borrow_investor';
		$b = 'borrow';
		$this->db->from("$a");
		$this->db->where("$a.investor_uid", $investor_uid);
		$this->db->where("$b.borrow_type", $type);
		$this->db->join("$b", "$a.borrow_id=$b.id", 'left');
        $sql = $this->db->count_all_results();
		//echo $this->db->last_query();
        return $sql;
	}
	// 判断是否投资过新手标
	public function is_invest_new($investor_uid) {
		$this->db->where('investor_uid', $investor_uid);
		$this->db->where('new', 1);
		return $this->db->count_all_results('borrow_investor');
	}
	//借款者
	public function get_borrows($page = 10, $per_page = 1, $status = '', $borrow_uid){
		$this->db->where('borrow_uid', $borrow_uid);
		$this->db->where('borrow_status', $status);
		$this->db->limit($page, $per_page);
        $this->db->order_by('id DESC');
        $query=  $this->db->get('borrow');
        $result = $query->result_array();
        return $result;
    }
    //所有
    public function get_borrows_num($status = '', $borrow_uid){
		$this->db->where('borrow_status', $status);
		$this->db->where('borrow_uid', $borrow_uid);
        $sql = $this->db->count_all_results('borrow');
        return $sql;
    }
	// 获取新手标
	public function get_borrow_new() {
		$this->db->where('borrow_type', 2);
		$this->db->where('borrow_status', 2);
		$this->db->order_by("borrow_status ASC, id ASC");
		return $this->db->get('borrow')->row_array();
	}
	//根据传入条件查询借款信息
	public function get_borrows_bywhere($where, $number) {
		if(!empty($where) && is_array($where)) {
			foreach($where as $k=>$v) {
				$this->db->where($k, $v);
			}
		}
		$this->db->order_by("borrow_status ASC");
		$this->db->limit($number);
		$query = $this->db->get('borrow');
		return $query->result_array();
	}
	//合同
	public function agreement(){
		$this->db->where('id', 1);
		$query=  $this->db->get('agreement');
        $result = $query->row_array();
        return $result;
	}
	public function get_borrow_investor_by_id($investor_id, $a = 'borrow_investor', $b = 'borrow'){
		$this->db->from("$a");
		$this->db->select("$a.*,$b.endtime,$b.borrow_money,$b.borrow_duration,$b.borrow_interest_rate,$b.add_rate,$b.borrow_useid,$b.repayment_type,$b.borrow_useid");
		$this->db->where("$a.id", $investor_id);
		$this->db->where_in("$b.borrow_status", array(4,5,6,7,8,9));
		$this->db->join("$b", "$a.borrow_id=$b.id", 'left');
        $query = $this->db->get();
        $result = $query->row_array();
        return $result;
    }
	//调取所有投资人信息（第二张表）
	public function get_investor_all($bid, $loan_status = 0) {
		$this->db->where('borrow_id', $bid);
		$this->db->where('loan_status', $loan_status);
		return $this->db->get('borrow_investor')->result_array();
	}
	//将investor的loan_status状态修改为1
	public function set_investor_loan($data) {
		return $this->db->where('id', $data['id'])->update('borrow_investor', $data);
	}
	//获取第二张表的一条数据
	public function get_investor_one($id) {
		return $this->db->where('id', $id)->get('borrow_investor')->row_array();
	}
	
	//放款
	public function loan($data) {
		$this->db->trans_begin();
		if(isset($data['borrow'])) {
			$this->db->where('id', $data['borrow']['id'])->update('borrow', $data['borrow']);
		}
		if(isset($data['investor'])) {
			$this->db->update_batch('borrow_investor', $data['investor'], 'id', 100);
		}
		if(isset($data['detail'])) {
			$this->db->insert_batch('investor_detail', $data['detail'], true, 500);
		}
		if(isset($data['money'])) {
			$this->db->update_batch('members_money', $data['money'], 'uid', 100);
		}
		if(isset($data['log'])) {
			$this->db->insert_batch('members_moneylog', $data['log'], true, 500);
		}
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
		} else {
			$this->db->trans_commit();
		}
	}
	
	//还款
	public function repayment($data) {
		$this->db->trans_begin();
		if(isset($data['borrow'])) {
			$this->db->where('id', $data['borrow']['id'])->update('borrow', $data['borrow']);
		}
		if(isset($data['investor'])) {
			$this->db->where('id', $data['investor']['id'])->update('borrow_investor', $data['investor']);
		}
		if(isset($data['detail'])) {
			$this->db->where('id', $data['detail']['id'])->update('investor_detail', $data['detail']);
		}
		if(isset($data['investor_status'])) {
			$this->db->where('borrow_id', $data['investor_status']['borrow_id'])->update('borrow_investor', $data['investor_status']);
		}
		if(isset($data['detail_status'])) {
			$this->db->where('borrow_id', $data['detail_status']['borrow_id'])->update('investor_detail', $data['detail_status']);
		}
		if(isset($data['money'])) {
			$this->db->update_batch('members_money', $data['money'], 'uid', 100);
		}
		if(isset($data['log'])) {
			$this->db->insert_batch('members_moneylog', $data['log'], true, 500);
		}
		if(isset($data['water'])) {
			$this->db->where('merOrderNo', $data['water']['merOrderNo'])->update('water', $data['water']);
		}
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
		} else {
			$this->db->trans_commit();
		}
	}
	
	//
	public function get_borrow_info_bybid($bid){
		$this->db->select('borrow_name');
		$this->db->where('id', $bid);
		$query=  $this->db->get('borrow');
        $result = $query->row_array();
        return $result;
	}
	//插入授权
	public function add_authorize($data) {
		$this->db->insert('authorize', $data);
	}
	
	/** 批量插入红包 */
	public function send($data) {
		$this->db->trans_begin();
		// if(isset($data['cash'])) {
			// $this->db->insert_batch('packet', $data['cash'], true, 500);
		// }
		if(isset($data['cashes'])) {
			$this->db->insert_batch('packet_xj', $data['cashes'], true, 1000);
		}
		if(isset($data['invest'])) {
			$this->db->insert_batch('packet', $data['invest'], true, 1000);
		}
		if(isset($data['log'])) {
			$this->db->insert_batch('members_moneylog', $data['log'], true, 1000);
		}
		if(isset($data['money'])) {
			$this->db->update_batch('members_money', $data['money'], 'uid', 1000);
		}
		
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return false;
		} else {
			$this->db->trans_commit();
			return true;
		}
	}
	/** 批量插入红包(无事务) */
	public function sends($data) {

		if(isset($data['cashes'])) {
			$this->db->insert_batch('packet_xj', $data['cashes'], true, 1000);
		}
		if(isset($data['invest'])) {
			$this->db->insert_batch('packet', $data['invest'], true, 1000);
		}
		if(isset($data['log'])) {
			$this->db->insert_batch('members_moneylog', $data['log'], true, 1000);
		}
		if(isset($data['money'])) {
			$this->db->update_batch('members_money', $data['money'], 'uid', 1000);
		}
	}
	
	
	/**
	 * 判断已申请金额 
	 * $borrow_uid 	@param 借款人ID
	 */
	public function has_apply_money($borrow_uid) {
		$this->db->where('borrow_uid', $borrow_uid);
		$this->db->where('borrow_status <', 5);
		$this->db->select_sum('borrow_money');
		return $this->db->get('borrow')->row_array();
		
	}
}