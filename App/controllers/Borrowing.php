<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Borrowing extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->library('pagination');
		$this->load->model(array('borrowing/borrowing_model'));
		$this->load->helper(array('url', 'common'));
	}
	
	/** 已删除列表 */
	public function delt() {
		$data = array();
		$where = array();
		$search = $this->input->get(null, true);
		if(!empty($search['skey'])) {
			$data['skey'] = trim(trim($search['skey']), '\t');
			$where['skey'] = $data['skey'];
		}
		if(!empty($search['name'])) {
			$data['name'] = trim(trim($search['name']), '\t');
			$where['name'] = $data['name'];
		}
		if(!empty($search['guarantor'])) {
			$data['guarantor'] = trim(trim($search['guarantor']), '\t');
			$where['guarantor'] = $data['guarantor'];
		}
		if(!empty($search['status'])) {
			$data['status'] = trim(trim($search['status']), '\t');
			$where['borrow_status'] = $data['status'];
		}
		$where['del > '] = 0;
		$per_page = 10;
		$current_page = $this->uri->segment(3) ? $this->uri->segment(3) - 1 : 0;
        $offset = $current_page;
        $config['base_url'] = base_url('borrowing/delt');
        $config['total_rows'] = $this->borrowing_model->get_borrow_related_nums($where);
        $config['per_page'] = $per_page;
		$config['page_query_string'] = FALSE;
		$config['first_link'] = '首页'; // 第一页显示   
		$config['last_link'] = '末页'; // 最后一页显示   
		$config['next_link'] = '下一页'; // 下一页显示   
		$config['prev_link'] = '上一页'; // 上一页显示   
		$config['cur_tag_open'] = ' <span class="current">'; // 当前页开始样式   
		$config['cur_tag_close'] = '</span>';   
		$config['num_links'] = 10;
		$config['uri_segment'] = 3;
		$config['use_page_numbers'] = TRUE;
		$config['reuse_query_string'] = TRUE;
        $this->pagination->initialize($config); 
        $data['totals'] = $config['total_rows'];
        $data['page'] = $this->pagination->create_links();
		$data['skip_page'] = $this->pagination->create_skip_link();
        $data['p'] = $current_page;
        $borrow = $this->borrowing_model->get_borrow_related($per_page, $offset * $per_page, $where);
        $data['borrow'] = $borrow;
		$this->load->view('borrowing/delt', $data);
	}
	
	/** 录入数据 */
	public function entry() {
		$data = array();
		$where = array();
		$search = $this->input->get(null, true);
		if(!empty($search['skey'])) {
			$data['skey'] = trim(trim($search['skey']), '\t');
			$where['skey'] = $data['skey'];
		}
		$where['audit'] = 0;
		$per_page = 10;
		$current_page = $this->uri->segment(3) ? $this->uri->segment(3) - 1 : 0;
        $offset = $current_page;
        $config['base_url'] = base_url('borrowing/entry');
        $config['total_rows'] = $this->borrowing_model->get_borrowing_nums($where);
        $config['per_page'] = $per_page;
		$config['page_query_string'] = FALSE;
		$config['first_link'] = '首页'; // 第一页显示   
		$config['last_link'] = '末页'; // 最后一页显示   
		$config['next_link'] = '下一页'; // 下一页显示   
		$config['prev_link'] = '上一页'; // 上一页显示   
		$config['cur_tag_open'] = ' <span class="current">'; // 当前页开始样式   
		$config['cur_tag_close'] = '</span>';   
		$config['num_links'] = 10;
		$config['uri_segment'] = 3;
		$config['use_page_numbers'] = TRUE;
		$config['reuse_query_string'] = TRUE;
        $this->pagination->initialize($config); 
        $data['totals'] = $config['total_rows'];
        $data['page'] = $this->pagination->create_links();
		$data['skip_page'] = $this->pagination->create_skip_link();
        $data['p'] = $current_page;
        $borrowing = $this->borrowing_model->get_borrowing_list($per_page, $offset * $per_page, $where);
		foreach($borrowing as $k=>$v) {
			$borrowing[$k]['having_borrow_money'] = $this->borrowing_model->has_apply_money($v['borrow_uid']);
		}
        $data['borrowing'] = $borrowing;
		$this->load->view('borrowing/entry', $data);
	}
	
	/** 审核列表 */
	public function audit() {
		$data = array();
		$where = array();
		$search = $this->input->get(null, true);
		if(!empty($search['skey'])) {
			$data['skey'] = trim(trim($search['skey']), '\t');
			$where['skey'] = $data['skey'];
		}
		$where['audit'] = 1;
		$per_page = 10;
		$current_page = $this->uri->segment(3) ? $this->uri->segment(3) - 1 : 0;
        $offset = $current_page;
        $config['base_url'] = base_url('borrowing/audit');
        $config['total_rows'] = $this->borrowing_model->get_borrowing_nums($where);
        $config['per_page'] = $per_page;
		$config['page_query_string'] = FALSE;
		$config['first_link'] = '首页'; // 第一页显示   
		$config['last_link'] = '末页'; // 最后一页显示   
		$config['next_link'] = '下一页'; // 下一页显示   
		$config['prev_link'] = '上一页'; // 上一页显示   
		$config['cur_tag_open'] = ' <span class="current">'; // 当前页开始样式   
		$config['cur_tag_close'] = '</span>';   
		$config['num_links'] = 10;
		$config['uri_segment'] = 3;
		$config['use_page_numbers'] = TRUE;
		$config['reuse_query_string'] = TRUE;
        $this->pagination->initialize($config); 
        $data['totals'] = $config['total_rows'];
        $data['page'] = $this->pagination->create_links();
		$data['skip_page'] = $this->pagination->create_skip_link();
        $data['p'] = $current_page;
        $borrowing = $this->borrowing_model->get_borrowing_list($per_page, $offset * $per_page, $where);
		foreach($borrowing as $k=>$v) {
			$borrowing[$k]['having_borrow_money'] = $this->borrowing_model->has_apply_money($v['borrow_uid']);
		}
        $data['borrowing'] = $borrowing;
		$this->load->view('borrowing/audit', $data);
	}
	
	/** 运营 列表 */
	public function operate() {
		$data = array();
		$where = array();
		$search = $this->input->get(null, true);
		if(!empty($search['skey'])) {
			$data['skey'] = trim(trim($search['skey']), '\t');
			$where['skey'] = $data['skey'];
		}
		$where['audit'] = 2;
		$per_page = 10;
		$current_page = $this->uri->segment(3) ? $this->uri->segment(3) - 1 : 0;
        $offset = $current_page;
        $config['base_url'] = base_url('borrowing/operate');
        $config['total_rows'] = $this->borrowing_model->get_borrowing_nums($where);
        $config['per_page'] = $per_page;
		$config['page_query_string'] = FALSE;
		$config['first_link'] = '首页'; // 第一页显示   
		$config['last_link'] = '末页'; // 最后一页显示   
		$config['next_link'] = '下一页'; // 下一页显示   
		$config['prev_link'] = '上一页'; // 上一页显示   
		$config['cur_tag_open'] = ' <span class="current">'; // 当前页开始样式   
		$config['cur_tag_close'] = '</span>';   
		$config['num_links'] = 10;
		$config['uri_segment'] = 3;
		$config['use_page_numbers'] = TRUE;
		$config['reuse_query_string'] = TRUE;
        $this->pagination->initialize($config); 
        $data['totals'] = $config['total_rows'];
        $data['page'] = $this->pagination->create_links();
		$data['skip_page'] = $this->pagination->create_skip_link();
        $data['p'] = $current_page;
        $borrowing = $this->borrowing_model->get_borrowing_list($per_page, $offset * $per_page, $where);
		foreach($borrowing as $k=>$v) {
			$borrowing[$k]['having_borrow_money'] = $this->borrowing_model->has_apply_money($v['borrow_uid']);
		}
        $data['borrowing'] = $borrowing;
		$this->load->view('borrowing/operate', $data);
	}
	
	/** 上标 */
	public function subject() {
		$id = $this->uri->segment(3);
		record_adminlog($this->router->fetch_class(), $this->router->fetch_method(), $id, '运营列表-上标');
		if(IS_POST){
			if(empty($id)) {
				$this->error('数据有误');
			}
			$this->load->model(array('borrow/borrow_model', 'member/member_model'));
			//第一步：复制一份数据到xm_borrow表中
			$borrowing_id = $id;
			$borrowing = $this->borrowing_model->get_borrowing_byid($id);
			if($borrowing['audit'] != 2) {
				$this->error('还未到上标流程');
			}
			//更新状态
			$borrowing['audit'] = 3;
			$this->borrowing_model->modify_borrowing($borrowing);
			
			//如果zaudit更新字段，这里必须更新
			unset($borrowing['id'],$borrowing['audit'], $borrowing['remark'], $borrowing['back']);
			if(! $return_id = $this->borrow_model->add_borrow($borrowing)) {
				$this->error('复制上标数据错误');
			} else {
				$id = $return_id;
			}
			//print_r($borrowing);echo $id;die;
			//第二步：原来的上标操作
			
			
			//$this->load->model('borrow/borrow_model');
			if(empty($id)) {
				$this->error('数据有误');
			}
			//调取标信息
			$borrow = $this->borrow_model->get_borrow_byid($id);
			//调取借款人信息
			$meminfo = $this->member_model->get_member_info_byuid($borrow['borrow_uid']);
			
			//数据有问题，报错
			if(!in_array($borrow['type'], array(1, 2))) {
				$this->error('标的类型错误');
			}
			if(empty($meminfo['idcard'])) {
				$this->error('身份证或营业执照不能为空');
			}
			
			if(empty($meminfo['sealPath'])) {
				$this->error('还未生成企业签章');
			} 
			
			//组织上标数据，请求接口
			$params['merSubjectNo'] = getTxNo20();
			$params['subjectName'] = $borrow['borrow_name'];
			$params['subjectAmt'] = $borrow['borrow_money'];
			$params['subjectRate'] = floatval(round($borrow['borrow_interest_rate']/100, 4));
			$params['subjectPurpose'] = '资金周转';
			$params['payeeAcctNo'] = $meminfo['acctNo'];//'110181805300010215';//
			$params['subjectType'] = $borrow['type'] === '1' ? '00' : '01';
			$params['identificationNo'] = $meminfo['idcard'];
			$params['serviceRate'] = floatval(round($borrow['service_money']/100, 4));
			$params['subjectStartDate'] = date('Ymd');//'20180829';
			$params['SubjectEndDate'] = date('Ymd', time() + ($borrow['number_time']*24*3600));
			//p($params);die;
			$head = head($params, 'CG1012', 'createdo');
			if(empty($head['merOrderNo'])) {
				$this->error('订单号出错，请联系管理员');
			}
			water($meminfo['uid'], $head['merOrderNo'], 'CG1012', $id);
			unset($head['callbackUrl']);
			unset($head['registerPhone']);
			unset($head['responsePath']);
			unset($head['url']);
			$data = $head;
			$data = json_encode($data);
			$url = $this->config->item('Interface').'1012';
			$str = post_curl_test($url, $data);
			
			$this->load->model(array('paytest/paytest_model'));
			$body = $this->paytest_model->excute($str);
			//接口返回信息
			if(!isset($body['head']['respCode'])) {
				$this->error('异常错误，请联系管理员');
			}
			if($body['head']['respCode'] === '000000') {
				//回写借款表，状态修改为2
				$borrow['subjectNo'] = $body['body']['subjectNo'];
				$borrow['borrow_status'] = 2;
				$borrow['send_time'] = time();
				$this->borrow_model->modify_borrow($borrow, $id);
				
				$this->success($body['head']['respDesc'], '/borrow.html');
			} else {
				//如果上标失败，恢复标的的上标状态
				$borrowing = $this->borrowing_model->get_borrowing_byid($borrowing_id);
				$borrowing['auid'] = 2;
				$this->borrowing_model->modify_borrowing($borrowing);
				$this->error($body['head']['respDesc']);
			}
		}
	}
	//获取借款人姓名
	public function getusers(){
		$this->load->model(array('member/member_model'));
		$data = array();
		$where = array();
		$search = $this->input->get(null, true);
		if(!empty($search['skey'])) {
			$data['skey'] = trim(trim($search['skey']), '\t');
			$where['skey'] = $data['skey'];
		}
		$where['members.attribute'] = 2;
		$current_page = $this->uri->segment(3) ? $this->uri->segment(3) : 1;
		$current_page = $current_page > 0 ? $current_page - 1 : 0;
		
		$per_page = 8;
        $offset = $current_page;
        $config['base_url'] = base_url('borrowing/getusers');
        $config['total_rows'] = $this->member_model->get_member_related_num($where);
        $config['per_page'] = $per_page;
		$config['page_query_string'] = FALSE;
		$config['first_link'] = '首页'; // 第一页显示   
		$config['last_link'] = '末页'; // 最后一页显示   
		$config['next_link'] = '下一页'; // 下一页显示   
		$config['prev_link'] = '上一页'; // 上一页显示   
		$config['cur_tag_open'] = ' <span class="current">'; // 当前页开始样式
		$config['cur_tag_close'] = '</span>';
		$config['num_links'] = 10;
		$config['uri_segment'] = 3;
		$config['use_page_numbers'] = TRUE;
		$config['reuse_query_string'] = TRUE;
        $this->pagination->initialize($config);
        $data['totals'] = $config['total_rows'];
        $data['page'] = $this->pagination->create_links();
		$data['skip_page'] = $this->pagination->create_skip_link();
        $data['p'] = $current_page;
        $member = $this->member_model->get_member_related($per_page, $offset * $per_page, $where);
        $data['member'] = $member;
		$this->load->view('member/getusers', $data);
	}
	/** 获取担保人 */
	public function get_guarantor() {
		$this->load->model(array('guarantor/guarantor_model'));
		$data = array();
		$where = array();
		$current_page = $this->uri->segment(3) ? $this->uri->segment(3) : 1;
		$current_page = $current_page > 0 ? $current_page - 1 : 0;
		$search = $this->input->get(null, true);
		if(!empty($search['skey'])) {
			$data['skey'] = trim(trim($search['skey']), '\t');
			$where['skey'] = $data['skey'];
		}
		$per_page = 8;
        $offset = $current_page;
        $config['base_url'] = base_url('borrowing/get_guarantor');
        $config['total_rows'] = $this->guarantor_model->get_guarantor_num($where);
        $config['per_page'] = $per_page;
		$config['page_query_string'] = FALSE;
		$config['first_link'] = '首页'; // 第一页显示   
		$config['last_link'] = '末页'; // 最后一页显示   
		$config['next_link'] = '下一页'; // 下一页显示   
		$config['prev_link'] = '上一页'; // 上一页显示   
		$config['cur_tag_open'] = ' <span class="current">'; // 当前页开始样式   
		$config['cur_tag_close'] = '</span>';   
		$config['num_links'] = 10;
		$config['uri_segment'] = 3;
		$config['use_page_numbers'] = TRUE;
		$config['reuse_query_string'] = TRUE;
        $this->pagination->initialize($config); 
        $data['totals'] = $config['total_rows'];
        $data['page'] = $this->pagination->create_links();
		$data['skip_page'] = $this->pagination->create_skip_link();
        $data['p'] = $current_page;
        $data['guarantor'] = $this->guarantor_model->get_guarantor_lists($per_page, $offset * $per_page, $where);
		$this->load->view('borrow/guarantor', $data);
	}
	
	/** 运营退回 */
	public function operate_back() {
		$id = $this->uri->segment(3);
		if(IS_POST) {
			$remark = $this->input->post('value', TRUE);
			if(empty($remark)) {
				$this->error('必须写明退回原因');
			}
			$borrowing = $this->borrowing_model->get_borrowing_byid($id);
			$borrowing['remark'] = $remark;
			$borrowing['audit'] = 0;
			$borrowing['back'] = 1;
			if($this->borrowing_model->modify_borrowing($borrowing)) {
				record_adminlog($this->router->fetch_class(), $this->router->fetch_method(), $id, '运营列表-退回');
				$this->success('操作成功');
			} else {
				$this->error('操作失败');
			}
		}
	}
	/** 运营编辑 */
	public function operate_modify() {
		if(IS_POST) {
			$data = $this->input->post(NULL, TRUE);
			
			if(! $data['borrow_uid']){
				$this->error('请完善标借款人姓名项!');
			}
			if(! $data['type']){
				$this->error('请完善标收款类型项!');
			}
			if(! $data['borrow_name']){
				$this->error('请完善标名称项!');
			}
			if(! $data['borrow_money']){
				$this->error('请完善金额项!');
			} else {
				$data['borrow_money'] = intval($data['borrow_money']);
			}
			if(! $data['borrow_type']){
				$this->error('请完善类型项!');
			}
			if(! $data['borrow_duration']){
				$this->error('请完善期限项!');
			}
			if(! $data['borrow_min']){
				$this->error('请完善起投金额项!');
			}
			$data['borrow_max'] = $data['borrow_max'] ? $data['borrow_max'] : 0;
			if(! $data['repayment_type']){
				$this->error('请完善还款方式项!');
			}
			if(! $data['borrow_interest_rate']){
				$this->error('请完善年化利率项!');
			}
			if(!isset($data['service_money'])) {
				$data['service_money'] = 0;
			}
			if(empty($data['pic'])){
				unset($data['pic']);
			}
			$data['add_rate'] = $data['add_rate'] ? $data['add_rate'] : 0;
			if(! $data['number_time']){
				$this->error('请完善募集时间项!');
			}
			if(! $data['borrow_use']){
				$this->error('请完善借款用途项!');
			}
			if(empty($data['guarantor'])) {
				$data['guarantor'] = '';
			} else {
				if(count(array_unique($data['guarantor'])) > 20) {
					$this->error('担保人不能超过20个');
				} 
				$data['guarantor'] = implode(',', array_unique($data['guarantor']));
			}
			if(! $data['borrow_info']){
				$this->error('请完善借款信息介绍项!');
			}
			if(! $data['borrow_no']){
				$this->error('请完善标的编号!');
			}
			$data['add_time'] = time();
			//$data['send_time'] = time();
			$data['add_ip'] = $this->input->ip_address();
			$data['borrow_status'] = 0;//1申请，2挂标，3放款，4结单
			$current_page = $data['current_page'];
			unset($data['userfile'], $data['current_page']);
			$data['admin_id'] = UID;
			//$data['audit'] = $this->uri->segment(3) == 1 ? 0 : 1;
			//p($data);
			if($this->borrowing_model->modify_borrowing($data)) {
				record_adminlog($this->router->fetch_class(), $this->router->fetch_method(), $data['id'], '运营列表-修改');
				$url = '/borrowing/operate' . ($current_page ? "/{$current_page}" : '') . '.html';
				$this->success('操作成功!', $url);
			} else {
				$this->error('操作失败,刷新后重试!');
			}
		}
		$data = array();
		$data['current_page'] = $this->uri->segment(4);
		$id = $this->uri->segment(3);
		$data['borrowing'] = $this->borrowing_model->get_borrowing_byid($id);
		//担保人
		if(!empty($data['borrowing']['guarantor'])) {
			$this->load->model('guarantor/guarantor_model');
			$data['borrowing']['guarantor'] = explode(',', $data['borrowing']['guarantor']);
			foreach($data['borrowing']['guarantor'] as $k=>$v) {
				$data['borrowing']['guarantor'][$k] = $this->guarantor_model->get_guarantor_one($v);
			}
		}
		
		$this->load->view('borrowing/operate_modify', $data);
	}
	
	/** 审核 */
	public function confirm() {
		$id = $this->uri->segment(3);
		if(IS_POST) {
			$borrowing = $this->borrowing_model->get_borrowing_byid($id);
			$borrowing['audit'] = 2;
			if($this->borrowing_model->modify_borrowing($borrowing)) {
				record_adminlog($this->router->fetch_class(), $this->router->fetch_method(), $id, '审核数据-审核');
				$this->success('操作成功');
			} else {
				$this->error('操作失败');
			}
		}
	}
	
	/** 退回 */
	public function back() {
		$id = $this->uri->segment(3);
		if(IS_POST) {
			$remark = $this->input->post('value', TRUE);
			if(empty($remark)) {
				$this->error('必须写明退回原因');
			}
			$borrowing = $this->borrowing_model->get_borrowing_byid($id);
			$borrowing['remark'] = $remark;
			$borrowing['audit'] = 0;
			if($this->borrowing_model->modify_borrowing($borrowing)) {
				record_adminlog($this->router->fetch_class(), $this->router->fetch_method(), $id, '审核数据-退回');
				$this->success('操作成功');
			} else {
				$this->error('操作失败');
			}
		}
	}
	
	/** 审核编辑 */
	public function audit_modify() {
		if(IS_POST) {
			$data = $this->input->post(NULL, TRUE);
			
			if(! $data['borrow_uid']){
				$this->error('请完善标借款人姓名项!');
			}
			if(! $data['type']){
				$this->error('请完善标收款类型项!');
			}
			if(! $data['borrow_name']){
				$this->error('请完善标名称项!');
			}
			if(! $data['borrow_money']){
				$this->error('请完善金额项!');
			} else {
				$data['borrow_money'] = intval($data['borrow_money']);
			}
			if(! $data['borrow_type']){
				$this->error('请完善类型项!');
			}
			if(! $data['borrow_duration']){
				$this->error('请完善期限项!');
			}
			if(! $data['borrow_min']){
				$this->error('请完善起投金额项!');
			}
			$data['borrow_max'] = $data['borrow_max'] ? $data['borrow_max'] : 0;
			if(! $data['repayment_type']){
				$this->error('请完善还款方式项!');
			}
			if(! $data['borrow_interest_rate']){
				$this->error('请完善年化利率项!');
			}
			if(!isset($data['service_money'])) {
				$data['service_money'] = 0;
			}
			if(empty($data['pic'])){
				unset($data['pic']);
			}
			$data['add_rate'] = $data['add_rate'] ? $data['add_rate'] : 0;
			if(! $data['number_time']){
				$this->error('请完善募集时间项!');
			}
			if(! $data['borrow_use']){
				$this->error('请完善借款用途项!');
			}
			if(empty($data['guarantor'])) {
				$data['guarantor'] = '';
			} else {
				if(count(array_unique($data['guarantor'])) > 20) {
					$this->error('担保人不能超过20个');
				} 
				$data['guarantor'] = implode(',', array_unique($data['guarantor']));
			}
			if(! $data['borrow_info']){
				$this->error('请完善借款信息介绍项!');
			}
			if(! $data['borrow_no']){
				$this->error('请完善标的编号!');
			}
			$data['add_time'] = time();
			//$data['send_time'] = time();
			$data['add_ip'] = $this->input->ip_address();
			$data['borrow_status'] = 0;//1申请，2挂标，3放款，4结单
			$current_page = $data['current_page'];
			unset($data['userfile'], $data['current_page']);
			$data['admin_id'] = UID;
			//$data['audit'] = $this->uri->segment(3) == 1 ? 0 : 1;
			//p($data);
			if($this->borrowing_model->modify_borrowing($data)) {
				record_adminlog($this->router->fetch_class(), $this->router->fetch_method(), $data['id'], '审核数据-修改');
				$url = '/borrowing/audit' . ($current_page ? "/{$current_page}" : '') . '.html';
				$this->success('操作成功!', $url);
			} else {
				$this->error('操作失败,刷新后重试!');
			}
		}
		$data = array();
		$data['current_page'] = $this->uri->segment(4);
		$id = $this->uri->segment(3);
		$data['borrowing'] = $this->borrowing_model->get_borrowing_byid($id);
		//担保人
		if(!empty($data['borrowing']['guarantor'])) {
			$this->load->model('guarantor/guarantor_model');
			$data['borrowing']['guarantor'] = explode(',', $data['borrowing']['guarantor']);
			foreach($data['borrowing']['guarantor'] as $k=>$v) {
				$data['borrowing']['guarantor'][$k] = $this->guarantor_model->get_guarantor_one($v);
			}
		}
		
		$this->load->view('borrowing/audit_modify', $data);
	}
	
	/** 查看页面 */
	public function show() {
		$data = array();
		$data['current_page'] = $this->uri->segment(4);
		$id = $this->uri->segment(3);
		$data['borrowing'] = $this->borrowing_model->get_borrowing_byid($id);
		//担保人
		if(!empty($data['borrowing']['guarantor'])) {
			$this->load->model('guarantor/guarantor_model');
			$data['borrowing']['guarantor'] = explode(',', $data['borrowing']['guarantor']);
			foreach($data['borrowing']['guarantor'] as $k=>$v) {
				$data['borrowing']['guarantor'][$k] = $this->guarantor_model->get_guarantor_one($v);
			}
		}
		
		$this->load->view('borrowing/show', $data);
	}
	/** 查看图片 */
	public function showimg() {
		$id = $this->uri->segment(3);
		$this->load->model(array('guarantor/guarantor_model'));
		$data['guarantor'] = $this->guarantor_model->get_guarantor_one($id);
		$data['guarantor']['pic'] = explode(',', $data['guarantor']['pic']);
		$this->load->view('index/showimg', $data);
	}
	
	/** 删除 */
	public function del() {
		if(IS_POST) {
			$id = $this->uri->segment(3);
			$borrowing = $this->borrowing_model->get_borrowing_byid($id);
			$borrowing['del'] = time();
			if($borrowing = $this->borrowing_model->modify_borrowing($borrowing)) {
				record_adminlog($this->router->fetch_class(), $this->router->fetch_method(), $id, '录入数据-删除');
				$this->success('删除成功');
			} else {
				$this->error('删除失败');
			}
		}
	}
	
	/** 编辑，提交数据 */
	public function modify() {
		if(IS_POST) {
			$data = $this->input->post(NULL, TRUE);
			
			if(! $data['borrow_uid']){
				$this->error('请完善标借款人姓名项!');
			}
			if(! $data['type']){
				$this->error('请完善标收款类型项!');
			}
			if(! $data['borrow_name']){
				$this->error('请完善标名称项!');
			}
			if(! $data['borrow_money']){
				$this->error('请完善金额项!');
			} else {
				$data['borrow_money'] = intval($data['borrow_money']);
			}
			if(! $data['borrow_type']){
				$this->error('请完善类型项!');
			}
			if(! $data['borrow_duration']){
				$this->error('请完善期限项!');
			}
			if(! $data['borrow_min']){
				$this->error('请完善起投金额项!');
			}
			$data['borrow_max'] = $data['borrow_max'] ? $data['borrow_max'] : 0;
			if(! $data['repayment_type']){
				$this->error('请完善还款方式项!');
			}
			if(! $data['borrow_interest_rate']){
				$this->error('请完善年化利率项!');
			}
			if(!isset($data['service_money'])) {
				$data['service_money'] = 0;
			}
			if(empty($data['pic'])){
				unset($data['pic']);
			}
			$data['add_rate'] = $data['add_rate'] ? $data['add_rate'] : 0;
			if(! $data['number_time']){
				$this->error('请完善募集时间项!');
			}
			if(! $data['borrow_use']){
				$this->error('请完善借款用途项!');
			}
			if(empty($data['guarantor'])) {
				$data['guarantor'] = '';
			} else {
				if(count(array_unique($data['guarantor'])) > 20) {
					$this->error('担保人不能超过20个');
				} 
				$data['guarantor'] = implode(',', array_unique($data['guarantor']));
			}
			if(! $data['borrow_info']){
				$this->error('请完善借款信息介绍项!');
			}
			if(! $data['borrow_no']){
				$this->error('请完善标的编号!');
			}
			if(! $data['grade']){
				$this->error('请完善项目风险等级!');
			}
			$data['add_time'] = time();
			//$data['send_time'] = time();
			$data['add_ip'] = $this->input->ip_address();
			$data['borrow_status'] = 0;//1申请，2挂标，3放款，4结单
			$current_page = $data['current_page'];
			unset($data['userfile'], $data['current_page']);
			$data['admin_id'] = UID;
			$data['audit'] = $this->uri->segment(3) == 1 ? 0 : 1;
			
			//调取元数据
			$borrowing = $this->borrowing_model->get_borrowing_byid($data['id']);
			
			//$data['audit'] = ($data['audit'] && $borrowing['back']) ? 2 : $data['audit'];
			//p($data);
			if($this->borrowing_model->modify_borrowing($data)) {
				if($data['audit'] == 1) {
					record_adminlog($this->router->fetch_class(), $this->router->fetch_method(), $data['id'], '录入数据-修改');
				} else {
					record_adminlog($this->router->fetch_class(), $this->router->fetch_method(), $data['id'], '录入数据-提交');
				}
				$url = '/borrowing/entry' . ($current_page ? "/{$current_page}" : '') . '.html';
				$this->success('操作成功!', $url);
			} else {
				$this->error('操作失败,刷新后重试!');
			}
		}
		$data = array();
		$data['current_page'] = $this->uri->segment(4);
		$id = $this->uri->segment(3);
		$data['borrowing'] = $this->borrowing_model->get_borrowing_byid($id);
		//担保人
		if(!empty($data['borrowing']['guarantor'])) {
			$this->load->model('guarantor/guarantor_model');
			$data['borrowing']['guarantor'] = explode(',', $data['borrowing']['guarantor']);
			foreach($data['borrowing']['guarantor'] as $k=>$v) {
				$data['borrowing']['guarantor'][$k] = $this->guarantor_model->get_guarantor_one($v);
			}
		}
		
		$this->load->view('borrowing/modify', $data);
	}
	
	/** 新增数据 */
	public function add() {
		if($data = $this->input->post(NULL, TRUE)){
			if(! $data['borrow_uid']){
				$this->error('请完善标借款人姓名项!');
			}
			if(! $data['type']){
				$this->error('请完善标收款类型项!');
			}
			if(! $data['borrow_name']){
				$this->error('请完善标名称项!');
			}
			if(! $data['borrow_money']){
				$this->error('请完善金额项!');
			} else {
				$data['borrow_money'] = intval($data['borrow_money']);
			}
			if(! $data['borrow_type']){
				$this->error('请完善类型项!');
			}
			if(! $data['borrow_duration']){
				$this->error('请完善期限项!');
			}
			if(! $data['borrow_min']){
				$this->error('请完善起投金额项!');
			}
			$data['borrow_max'] = $data['borrow_max'] ? $data['borrow_max'] : 0;
			if(! $data['repayment_type']){
				$this->error('请完善还款方式项!');
			}
			if(! $data['borrow_interest_rate']){
				$this->error('请完善年化利率项!');
			}
			if(!isset($data['service_money'])) {
				$data['service_money'] = 0;
			}
			if(! $data['pic']){
				$this->error('请完善图片项!');
			}
			$data['add_rate'] = $data['add_rate'] ? $data['add_rate'] : 0;
			if(! $data['number_time']){
				$this->error('请完善募集时间项!');
			}
			if(! $data['borrow_use']){
				$this->error('请完善借款用途项!');
			}
			if(!isset($data['guarantor'])) {
				$data['guarantor'] = '';
			}
			if(empty($data['guarantor'])) {
				$data['guarantor'] = '';
			} else {
				if(count(array_unique($data['guarantor'])) > 20) {
					$this->error('担保人不能超过20个');
				} 
				$data['guarantor'] = implode(',', array_unique($data['guarantor']));
			}
			if(! $data['borrow_info']){
				$this->error('请完善借款信息介绍项!');
			}
			if(! $data['borrow_no']){
				$this->error('请完善标的编号!');
			}
			if(! $data['grade']){
				$this->error('请完善项目风险等级!');
			}
			$data['add_time'] = time();
			$data['add_ip'] = $this->input->ip_address();
			$data['borrow_status'] = 0;//1申请，2挂标，3放款，4结单
			unset($data['userfile']); 
			$data['admin_id'] = UID;
			if($return_id = $this->borrowing_model->add_borrowing($data)) {
				record_adminlog($this->router->fetch_class(), $this->router->fetch_method(), $return_id, '录入数据-新增');
				$this->success('申请成功!', '/borrowing/entry.html');
			} else {
				$this->error('申请失败,刷新后重试!');
			}
		}
		$this->load->view('borrowing/add');
	}
}