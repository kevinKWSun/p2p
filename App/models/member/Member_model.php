<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Member_model extends CI_Model{
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
	/**调取红包信息 */
	public function get_packet_all($uid) {		
		$this->db->where('uid', $uid);
		$query=  $this->db->get('packet');
		$result = $query->result_array();
		return $result;	
	}
	//新增 获取银行卡状态
	public function get_upbind_status_img($id){
		$this->db->where('uid', $id);
		$this->db->order_by('id DESC');
		$query=  $this->db->get('quickbank_upbind_img');
		$result = $query->row_array();
		return $result;	
	}
	
	/*//新增 增加银行卡图像
	public function update_upbind_img($data = array()){
		$sql = $this->db->insert('quickbank_upbind', $data);
		return $sql;
	}
	//新增 根据uid 获取最新的数据
	public function get_new_upbind($id){
		$this->db->where('uid', $id);
		$this->db->order_by('id DESC');
		$query=  $this->db->get('quickbank_upbind');
        $result = $query->row_array();
        return $result;		
	}*/
	public function add_upbind_img($data){
		$sql = $this->db->insert('quickbank_upbind_img', $data);
		return $sql;
	}
	
	public function get_borrow_new_my($page = 10, $per_page = 1, $uid = 0){
		//if($uid > 0) $this->db->where('borrow_uid', $uid);
		$this->db->limit($page, $per_page);
		$this->db->order_by('id DESC');
		$query=  $this->db->get('borrow');
		$result = $query->result_array();
		return $result;
	}
	//所有
	public function get_borrow_new_my_nums($uid = 0){
		//if($uid > 0) $this->db->where('borrow_uid', $uid);
		$sql = $this->db->count_all_results('borrow');
		return $sql;
	}
	
    //page每页总数   per_page第几页从第几个下标开始
    public function get_member($page = 10, $per_page = 1, $type = 1){
		$this->db->where('type', $type);
        $this->db->limit($page, $per_page);
        $this->db->order_by('id DESC');
        $query=  $this->db->get('members');
        $result = $query->result_array();
        return $result;
    }
    //所有
    public function get_member_nums($type = 2){
		$this->db->where('type', $type);
        $sql = $this->db->count_all_results('members');
        return $sql;
    }
	public function get_member_money_dz(){
        $query=  $this->db->get('members_money');
        $result = $query->result_array();
        return $result;
    }
	public function get_water_dz(){
		$this->db->where('respCode!=', '');
		$this->db->where('respCode!=', '000000');
		$this->db->where('tradeCode', 'CG1008');
        $query=  $this->db->get('water');
        $result = $query->result_array();
        return $result;
    }
	public function get_members($page = 10, $per_page = 1, $type = 2, $a = 'members', $b = 'borrow_uinfo'){
		$this->db->from("$a");
		$this->db->where("$a.type", $type);
        $this->db->limit($page, $per_page);
        $this->db->order_by("$a.id DESC");
		$this->db->join("$b", "$a.id=$b.uid", 'left');
        $query=  $this->db->get();
        $result = $query->result_array();
        return $result;
    }
    //所有
    public function get_member_num($type = 1){
		$this->db->where('type', $type);
        $sql = $this->db->count_all_results('members');
        return $sql;
    }
    //增加
    public function add_member($data = array()){
        $this->db->insert('members',$data);
        $sql = $this->db->insert_id();
        return $sql;
    }
	//
	public function add_borrow($data = array()){
        $this->db->insert('borrow_my',$data);
        $sql = $this->db->insert_id();
        return $sql;
    }
	public function get_borrow_my_byusername($user_name){
        $this->db->where('phone', $user_name);
        $query=  $this->db->get('borrow_my');
        $result = $query->row_array();
        return $result;
    }
	public function get_borrow_my_byid($user_name){
        $this->db->where('id', $user_name);
        $query=  $this->db->get('borrow_my');
        $result = $query->row_array();
        return $result;
    }
	public function get_borrow_my($page = 10, $per_page = 1, $uid = 0){
		if($uid > 0) $this->db->where('uid', $uid);
        $this->db->limit($page, $per_page);
        $this->db->order_by('id DESC');
        $query=  $this->db->get('borrow_my');
        $result = $query->result_array();
        return $result;
    }
    //所有
    public function get_borrow_my_nums($uid = 0){
		if($uid > 0) $this->db->where('uid', $uid);
        $sql = $this->db->count_all_results('borrow_my');
        return $sql;
    }
	//
	public function add_member_info($data = array()){
        $sql = $this->db->insert('members_info',$data);
        return $sql;
    }
	public function add_member_status($data = array()){
        $sql = $this->db->insert('members_status',$data);
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
	public function get_member_info_byrc($idcard, $real_name){
        $this->db->where('idcard', $idcard);
		$this->db->or_where('real_name', $real_name);
        $query=  $this->db->get('members_info');
        $result = $query->row_array();
        return $result;
    }
	/** 判断是否存在账户号和客户号 */
	public function get_member_info_byacc($acctNo) {
		$this->db->where('custNo', $acctNo);
		$this->db->or_where('acctNo', $acctNo);
		return $this->db->count_all_results('members_info');
		// echo $this->db->last_query();
		// return $ret;
	}
	/** 根据手机号获取用户信息 */
	public function get_member_info_byphone($phone) {
		$this->db->where('phone', $phone);
		$this->db->or_where('real_name', $phone);
		$this->db->order_by("uid desc");
		$query = $this->db->get('members_info');
		return $query->row_array();
	}
	public function get_m_bycodeuid($codeuid){
		$this->db->where('id', $codeuid);
		//$this->db->or_where('user_name', $codeuid);
        $query=  $this->db->get('members');
        $result = $query->row_array();
        return $result;
	}
    //查询资金表
    public function get_members_moneylog($page = 10, $per_page = 1, $uid, $type = ''){
		$this->db->limit($page, $per_page);
		if($uid){
			$this->db->where('uid', $uid);
		}
		if($type){
			$this->db->where_in('type', $type);
		}
		$this->db->order_by('add_time DESC');
        $query=  $this->db->get('members_moneylog');
        $result = $query->result_array();
        return $result;
    }
	//获取一条
	public function get_moneylog_bynid($nid) {
		return $this->db->where('nid', $nid)->get('members_moneylog')->row_array();
	}
	//所有
    public function get_members_moneylog_num($uid, $type = ''){
		if($uid){
			$this->db->where('uid', $uid);
		}
		if($type){
			$this->db->where_in('type', $type);
		}
        $sql = $this->db->count_all_results('members_moneylog');
        return $sql;
    }
    //增加金额日志表
    public function add_members_moneylog($data = array()){
        $sql = $this->db->insert('members_moneylog',$data);
        return $sql;
    }
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
	//查询金额表
	public function get_members_money_byuid($uid) {
		$this->db->where('uid', $uid);
		return $this->db->get('members_money')->row_array();
	}
	public function up_members_info($data = array(), $uid){
        $this->db->where('uid', $uid);
        $sql = $this->db->update('members_info', $data);
        return $sql;
    }
	public function up_members_status($data = array(), $uid){
        $this->db->where('uid', $uid);
        $sql = $this->db->update('members_status', $data);
        return $sql;
    }
    //会员登录信息
    public function get_member_byusername($user_name){
        $this->db->where('user_name', $user_name);
        $query=  $this->db->get('members');
        $result = $query->row_array();
        return $result;
    }
	public function get_member_byuserid($id){
        $this->db->where('id', $id);
        $query=  $this->db->get('members');
        $result = $query->row_array();
        return $result;
    }
	public function get_members_status_byuserid($uid){
        $this->db->where('uid', $uid);
        $query=  $this->db->get('members_status');
        $result = $query->row_array();
        return $result;
    }
	//提现bank
	public function get_bank($page = 10, $per_page = 1){
        $this->db->limit($page, $per_page);
        $this->db->order_by('addtime DESC');
        $query=  $this->db->get('members_quickbank');
        $result = $query->result_array();
        return $result;
    }
	public function get_bank_num(){
        $sql = $this->db->count_all_results('members_quickbank');
        return $sql;
    }
	public function get_bank_byuid($id = '', $uid, $status){
		if($id){
			$this->db->where('id', $id);
		}
		if($uid){
			$this->db->where('uid', $uid);
		}
		if($status == 1){
			$this->db->where('paystatus', $status);
		}
        $query=  $this->db->get('members_quickbank');
        $result = $query->row_array();
        return $result;
    }
	public function add_bank($data = array()){
        $sql = $this->db->insert('members_quickbank',$data);
        return $sql;
    }
	public function up_bank($data = array(), $uid , $id = ''){
		if($id){
			$this->db->where('id', $id);
		}
        $this->db->where('uid', $uid);
        $sql = $this->db->update('members_quickbank', $data);
        return $sql;
    }
	public function add_code($data = array()){
        $sql = $this->db->insert('code',$data);
        return $sql;
    }
	public function get_code($uid = 0, $tel = 0, $type = 0){
		if(! $uid && ! $tel){
			return FALSE;
		}
		if($uid){
			$this->db->where('uid', $uid);
		}
		if($tel){
			$this->db->where('tel', $tel);
		}
		$this->db->where('type', $type);
		$this->db->order_by('time DESC');
        $sql = $this->db->get('code');
		$result = $sql->row_array();
        return $result;
    }
	//进出
	public function get_members_moneylogs( $uid, $type = ''){
		if($uid){
			$this->db->where('uid', $uid);
		}
		if($type){
			$this->db->where_in('type', $type);
		}
		$this->db->select_sum('affect_money');
        $query=  $this->db->get('members_moneylog');
        $result = $query->row_array();
        return $result;
    }
	//newscate
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
		$this->db->from("$a");
		$this->db->join("$b", "$a.cid=$b.id", 'left');
        $this->db->limit($page, $per_page);
        $this->db->order_by("$a.id DESC");
        $query=  $this->db->get();
        $result = $query->result_array();
        return $result;
    }
    public function get_news_num(){
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
        $this->db->limit($page, $per_page);
        $this->db->order_by('id DESC');
        $query=  $this->db->get('news');
        $result = $query->result_array();
        return $result;
    }
	public function addall_packet($data = array()){
        $sql = $this->db->insert_batch('packet',$data);
        return $sql;
    }
	
	/** 调取用户所有可用红包 */
	public function get_packet_byuid($uid, $times = '') {
		$this->db->order_by("id desc");
		$this->db->where("status", 0);
		if(!empty($times)) {
			$this->db->where('times <= ', $times);
		}
		$this->db->where("etime > ", time());
		$this->db->where("uid", $uid);
		$query = $this->db->get('packet');
		return $query->result_array();
	}
	
	//用户红包
	public function get_member_packets($page = 10, $per_page = 1, $uid, $status = 0){
		if($status == 2){
			$this->db->where("etime < ", time());
		}elseif($status == 1){
			$this->db->where("status", $status);
		}elseif($status == 0){
			$this->db->where("status", $status);
			$this->db->where("etime > ", time());
		}
		$this->db->select('a.*,b.reg_time');
		$this->db->from('packet a');
		$this->db->join('xm_members b','a.uid = b.id','left');
		
		$this->db->where("uid", $uid);
		
		$this->db->limit($page, $per_page);
		$this->db->order_by('id DESC');
		$query=  $this->db->get();
		$result = $query->result_array();
		return $result;
	}

	//红包
	public function get_packets($page = 10, $per_page = 1, $uid, $status = 0){
		if($status == 2){
			$this->db->where("etime < ", time());
			$this->db->where("status", 0);
		}elseif($status == 1){
			$this->db->where("status", $status);
		}elseif($status == 0){
			$this->db->where("status", $status);
			$this->db->where("etime > ", time());
		}
		$this->db->where("uid", $uid);
        $this->db->limit($page, $per_page);
        $this->db->order_by('id DESC');
        $query=  $this->db->get('packet');
        $result = $query->result_array();
        return $result;
    }
    //所有
    public function get_packets_nums($uid, $status = 0){
		if($status == 2){
			$this->db->where("etime < ", time());
		}elseif($status == 1){
			$this->db->where("status", $status);
		}elseif($status == 0){
			$this->db->where("status", $status);
			$this->db->where("etime > ", time());
		}else{
			$this->db->where("status", 0);
		}
		$this->db->where("uid", $uid);
        $sql = $this->db->count_all_results('packet');
        return $sql;
    }
	
	/** 绑卡 */
	public function update_members_bindcard($data) {
		$this->db->trans_begin();
		//判断
		$uid = $data['quick']['uid'];
		$count_quick = $this->db->where('uid', $uid)->count_all_results('members_quickbank');
		if($count_quick > 0) {
			$this->db->where(array('uid'=>$uid))->update('members_quickbank', $data['quick']);
		} else {
			$this->db->insert('members_quickbank', $data['quick']);
		}
		$count_meminfo = $this->db->where('uid', $uid)->count_all_results('members_info');
		if($count_meminfo > 0) {
			$this->db->where(array('uid'=>$uid))->update('members_info', $data['meminfo']);
		} else {
			$this->db->insert('members_info', $data['meminfo']);
		}
		$count_memstatus = $this->db->where('uid', $uid)->count_all_results('members_status');
		if($count_memstatus > 0) {
			$this->db->where(array('uid'=>$uid))->update('members_status', $data['memstatus']);
		} else {
			$this->db->insert('members_status', $data['memstatus']);
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
		} else {
			$this->db->trans_commit();
		}
	}
	/** 根据客户号码 获取用户信息 */
	public function get_member_info_bycust($custNo){
        $this->db->where('custNo', $custNo);
        $query=  $this->db->get('members_info');
        $result = $query->row_array();
        return $result;
    }
	/** 根据客户号码 调取 绑卡信息 */
	public function get_quick_bank($uid) {
		$this->db->where('uid', $uid);
		return $this->db->get('members_quickbank')->row_array();
	}
	/** 更新绑定 */
	public function update_upbind($data) {
		$this->db->trans_begin();
		$uid = $data['quick']['uid'];
		$this->db->where(array('uid'=>$uid))->update('members_quickbank', $data['quick']);
		$this->db->insert('quickbank_upbind', $data['bak']);
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
		} else {
			$this->db->trans_commit();
		}
	}
	/** 网关充值,页面充值，提现 */
	public function recharge($data, $uid, $merOrderNo) {
		$this->db->trans_begin();
		if(isset($data['money'])) {
			$this->up_members_money($data['money'], $uid);
		}
		if(isset($data['log'])) {
			$count_logs = $this->db->where(array('nid'=>$merOrderNo))->count_all_results('members_moneylog');
			if($count_logs > 0) {
				$this->db->where(array('nid'=>$merOrderNo))->update('members_moneylog', $data['log']);
			} else {
				$this->add_members_moneylog($data['log']);
			}
			
		}
		if(isset($data['payonline'])) {
			$this->add_members_payonline($data['payonline']);
		}
		if(isset($data['water'])) {
			$this->db->where('merOrderNo', $merOrderNo);
			$this->db->update('water', $data['water']);
		}
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return false;
		} else {
			$this->db->trans_commit();
			return true;
		}
	}
	/** 查询充值记录，根据订单号 */
	public function get_members_payonline_bynid($nid) {
		$this->db->where('nid', $nid);
		return $this->db->count_all_results('members_payonline');
	}
	/** 根据订单号插入数据 充值记录 */
	public function add_members_payonline($data) {
		return $this->db->insert('members_payonline',$data);
	}
	/** 投资 */
	public function invest($data, $investor_uid, $merOrderNo, $bid) {
		$this->db->trans_begin();
		if(isset($data['money'])) {
			$this->up_members_money($data['money'], $investor_uid);
		}
		if(isset($data['log'])) {
			$count_logs = $this->db->where(array('nid'=>$merOrderNo))->count_all_results('members_moneylog');
			if($count_logs > 0) {
				$this->db->where(array('nid'=>$merOrderNo))->update('members_moneylog', $data['log']);
			} else {
				$this->add_members_moneylog($data['log']);
			}
		}
		if(isset($data['borrow'])) {
			$this->db->where('id', $bid)->update('borrow', $data['borrow']);
		}
		if(isset($data['investor'])) {
			$this->db->insert('borrow_investor', $data['investor']);
			$borrow_investor_id = $this->db->insert_id();
		}
		if(isset($data['water'])) {
			$this->db->where('merOrderNo', $merOrderNo)->update('water', $data['water']);
		}
		if(isset($data['red'])) {
			$this->db->where('id', $data['red']['id'])->update('packet', $data['red']);
		}
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
		} else {
			$this->db->trans_commit();
			return $borrow_investor_id;
		}
	}
	/** 满标 */
	public function full($data, $bid) {
		$this->db->trans_begin();
		$this->db->where(array('id'=>$bid))->update('borrow', $data['borrow']);
		$this->db->where(array('borrow_id'=>$bid))->update('borrow_investor', $data['investor']);
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
		} else {
			$this->db->trans_commit();
		}
	}
	/** 根据ID调取红包信息 */
	public function get_packet_byid($id, $uid) {
		return $this->db->where(array('id'=>$id, 'uid'=>$uid))->get('packet')->row_array();
	}
	/** 红包发送失败 */
	public function add_packet_error($data) {
		return $this->db->insert('packet_error',$data);
	}
	/** 根据手机号获取用户数量 */
	public function get_member_phone_num($phone) {
		return $this->db->where('user_name', $phone)->count_all_results('members');
	}
	/** 根据客户号码获取用户信息*/
	public function get_member_info_bycustno($custNo) {
		return $this->db->where('custNo', $custNo)->get('members_info')->row_array();
	}
	/** 变更注册手机号 */
	public function change_phone($data) {
		$this->db->trans_begin();
		if(isset($data['member'])) {
			$this->db->where('id', $data['member']['id'])->update('members', $data['member']);
		}
		if(isset($data['meminfo'])) {
			$this->db->where('uid', $data['meminfo']['uid'])->update('members_info', $data['meminfo']);
		}
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
		} else {
			$this->db->trans_commit();
		}
	}
	/** 用户信息（members, members_info, members_status）三张表关联查询 */
	public function get_member_related($page, $per_page, $where = array()) {
		$master = 'members';
		$sub = 'members_info';
		$sub2 = 'members_status';
		$sub3 = 'members_info';
		if(isset($where['skey'])) {
			$this->db->like("concat(xm_{$master}.id, IFNULL(xm_{$sub}.real_name,''), xm_{$master}.user_name, IFNULL(xm_{$sub}.idcard,''))", $where['skey']);
			unset($where['skey']);
		}
		if(isset($where['codename'])) {
			$this->db->like('minfo.real_name', $where['codename']);
			unset($where['codename']);
		}
		if(!empty($where)) {
			foreach($where as $k=>$v) {
				$this->db->where($k, $v);
			}
		}
		$this->db->select("$master.*, $sub.*, $sub2.*, minfo.real_name as codename");
		$this->db->limit($page, $per_page);
		$this->db->order_by('id DESC');
		$this->db->from($master);
		$this->db->join($sub, "$master.id = $sub.uid", 'left');
		$this->db->join($sub2, "$master.id = $sub2.uid", 'left');
		$this->db->join($sub3 . ' minfo', "$master.codeuid = minfo.uid", 'left');
		return $this->db->get()->result_array();
		/* echo $this->db->last_query();
		return $res; */
	}
	public function get_member_related_num($where = array()) {
		$master = 'members';
		$sub = 'members_info';
		$sub2 = 'members_status';
		$sub3 = 'members_info';
		if(isset($where['skey'])) {
			$this->db->like("concat(xm_{$master}.id, IFNULL(xm_{$sub}.real_name,''), xm_{$master}.user_name, IFNULL(xm_{$sub}.idcard,''))", $where['skey']);
			unset($where['skey']);
		}
		if(isset($where['codename'])) {
			$this->db->like('minfo.real_name', $where['codename']);
			unset($where['codename']);
		}
		if(!empty($where)) {
			foreach($where as $k=>$v) {
				$this->db->where($k, $v);
			}
		}
		$this->db->select("$master.*, $sub.*, $sub2.*, minfo.real_name as codename");
		$this->db->from($master);
		$this->db->join($sub, "$master.id = $sub.uid", 'left');
		$this->db->join($sub2, "$master.id = $sub2.uid", 'left');
		$this->db->join($sub3 . ' minfo', "$master.codeuid = minfo.uid", 'left');
		return $this->db->count_all_results();
	}
	
	//添加积分
	public function set_member_info_totalscore($data) {
		$this->db->trans_begin();
		$this->db->where('uid', $data['uid']);
		$this->db->set('totalscore', 'totalscore +' . $data['score'], FALSE)->update('members_info');
		$this->db->insert('score', $data);
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return false;
		} else {
			$this->db->trans_commit();
			return true;
		}
	}
	//添加积分（没有事务）
	public function set_member_info_totalscores($data) {
		$this->db->where('uid', $data['uid']);
		$this->db->set('totalscore', 'totalscore +' . $data['score'], FALSE)->update('members_info');
		$this->db->insert('score', $data);
	}
	/** 添加抽奖次数 */
	public function set_member_info_times($data) {
		$this->db->trans_begin();
		$this->db->where('uid', $data['uid']);
		if(isset($data['times'])) {
			if($data['times'] < 0) {
				$this->db->set('times', 'times ' . $data['times'], FALSE)->update('members_info');
				
			} else {
				$this->db->set('times', 'times +' . $data['times'], FALSE)->update('members_info');
			}
			
		} 
		if(isset($data['doubles'])) {
			if($data['doubles'] < 0) {
				$this->db->set('doubles', 'doubles ' . $data['doubles'], FALSE)->update('members_info');
			} else {
				$this->db->set('doubles', 'doubles +' . $data['doubles'], FALSE)->update('members_info');
			}
			
		}
		$this->db->insert('score_times', $data);
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return false;
		} else {
			$this->db->trans_commit();
			return true;
		}
	}
	//根据invest_id,调取是否已发积分
	public function get_socre_by_invest_ids($ids) {
		$this->db->select('invest_id');
		$this->db->where_in('invest_id', $ids);
		return $this->db->get('score')->result_array();
		
	}
	
	//企业信息
	public function get_company_info_byuid($uid) {
		$this->db->where('uid', $uid);
		return $this->db->get('company_info')->row_array();
	}
	public function up_company_info($data) {
		if(isset($data['id'])) {//更新
			return $this->db->where('id', $data['id'])->update('company_info', $data);
		} else {//新增
			$data['addtime'] = time();
			return $this->db->insert('company_info', $data);
		}
	}
}

