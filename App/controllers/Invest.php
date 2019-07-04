<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Invest extends Baseaccounts {
	public function __construct() {
		parent::__construct();
		$this->load->library('pagination');
		$this->load->model(array('borrow/borrow_model', 'account/info_model', 'member/member_model'));
		$this->load->helper(array('url', 'common'));
	}
	//借款列表
	// public function indexa(){
		// $current_page = $this->uri->segment(3) ? $this->uri->segment(3) : 1;
        // if($current_page > 0){
            // $current_page = $current_page - 1;
        // }else if($current_page < 0){
            // $current_page = 0;
        // }
		// $per_page = 8;
        // $offset = $current_page;
        // $config['base_url'] = base_url('invest/index');
        // $config['total_rows'] = $this->borrow_model->get_borrow_num_index();
        // $config['per_page'] = $per_page;
		// $config['page_query_string'] = FALSE;
		// $config['first_link'] = '首页'; // 第一页显示   
		// $config['last_link'] = '末页'; // 最后一页显示   
		// $config['next_link'] = '下一页'; // 下一页显示   
		// $config['prev_link'] = '上一页'; // 上一页显示   
		// $config['cur_tag_open'] = ' <span class="current">'; // 当前页开始样式   
		// $config['cur_tag_close'] = '</span>';   
		// $config['num_links'] = 10;
		// $config['uri_segment'] = 3;
		// $config['use_page_numbers'] = TRUE;
        // $this->pagination->initialize($config); 
        // $data['totals'] = $config['total_rows'];
        // $data['page'] = $this->pagination->create_links();
        // $data['p'] = $current_page;
        // $borrow = $this->borrow_model->get_borrow_index($per_page, $offset * $per_page);
        // $data['borrow'] = $borrow;
		// $this->load->view('index/invest', $data);
	// }
	//借款列表
	public function index(){
		/* $current_page = $this->uri->segment(3) ? $this->uri->segment(3) : 1;
        if($current_page > 0){
            $current_page = $current_page - 1;
        }else if($current_page < 0){
            $current_page = 0;
        }
		$per_page = 8;
        $offset = $current_page;
        $config['base_url'] = base_url('invest/index');
        $config['total_rows'] = $this->borrow_model->get_borrow_num_index();
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
        $this->pagination->initialize($config); 
        $data['totals'] = $config['total_rows'];
        $data['page'] = $this->pagination->create_links();
        $data['p'] = $current_page;
        $borrow = $this->borrow_model->get_borrow_index($per_page, $offset * $per_page);
        $data['borrow'] = $borrow;
		$this->load->view('index/investa', $data); */
		
		$current_page = intval($this->uri->segment(3));
		$current_page = $current_page > 0 ? $current_page - 1 : 0;
		$per_page = 8;
        $offset = $current_page;
        $config['base_url'] = base_url('invest/index');
        $config['total_rows'] = $this->borrow_model->get_borrow_num_index();
		// 总页数
		$page_total = ceil($config['total_rows']/$per_page);
		$data_res = json_decode(get_curl("http://www.jiamanu.cn/api2.aspx?action=getTenderList&currenpage=".($current_page + 1 - $page_total)."&count=".$per_page), true);
		$config['total_rows'] = $page_total * $per_page + $data_res['total'];
		
        $config['per_page'] = $per_page;
		$config['page_query_string'] = FALSE;
		$config['first_link'] = '首页'; // 第一页显示   
		$config['last_link'] = '末页'; // 最后一页显示   
		$config['next_link'] = '下一页'; // 下一页显示   
		$config['prev_link'] = '上一页'; // 上一页显示   
		$config['cur_tag_open'] = ' <span class="current">'; // 当前页开始样式   
		$config['cur_tag_close'] = '</span>';   
		$config['num_links'] = 3;
		$config['uri_segment'] = 3;
		$config['use_page_numbers'] = TRUE;
        $this->pagination->initialize($config); 
        $data['totals'] = $config['total_rows'];
		$data['p'] = $current_page;
        $data['page'] = $this->pagination->create_links();
		if($current_page + 1 > $page_total) {
			$borrow = $data_res['lendbidlist'];
		} else {
			$borrow = $this->borrow_model->get_borrow_index($per_page, $offset * $per_page);
		}
        $data['borrow'] = $borrow;
		if($current_page + 1 > $page_total) {
			$this->load->view('index/investa_personal', $data);
		} else {
			$this->load->view('index/investa', $data);
		}
	}
	/** 个人借款列表 */
	/* public function personal() {
		$data = array();
		$current_page = intval($this->uri->segment(3));
		$current_page = $current_page > 0 ? $current_page - 1 : 0;
		$per_page = 8;
		
		$url = "http://www.jiamanu.cn/api2.aspx?action=getTenderList&currenpage=".($current_page + 1)."&count=".$per_page;
		p($url);
		$data_ret = get_curl($url);
		$data_ret = json_decode($data_ret, true);
		
        $offset = $current_page;
        $config['base_url'] = base_url('invest/personal');
        $config['total_rows'] = $data_ret['total'];
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
        $this->pagination->initialize($config); 
        $data['totals'] = $config['total_rows'];
        $data['page'] = $this->pagination->create_links();
        $data['p'] = $current_page;
        $borrow = $data_ret['lendbidlist'];
        $data['borrow'] = $borrow;
		p($data);
		$this->load->view('index/investa_personal', $data);
	} */
	/*
	public function showa(){
		$id = $this->uri->segment(3);
		if(empty($id)) {
			exit('请求错误！');
		}
		$data = $this->borrow_model->get_borrow_byid($id);
		if(!empty($data['pic'])) {
			$data['pic'] = explode(',', $data['pic']);
		}
		$data['d_i'] = $this->borrow_model->get_borrow_investor_byid($id);
		foreach($data['d_i'] as $k=>$v) {
			// $real_name = get_member_info($v['investor_uid'])['real_name'];
			// $world = mb_substr($real_name, 1, 1);
			$phone = get_member_info($v['investor_uid'])['phone'];
			$world = mb_substr($phone, 3, 5);
			$data['d_i'][$k]['investor_uid'] = str_replace($world, '***', $phone);
		}
		$data['company'] = $this->borrow_model->get_borrow_uinfo_byuid($data['borrow_uid']);
		if(QUID > 0) {
			$data['money'] = $this->info_model->get_money(QUID);
			$data['packet'] = $this->member_model->get_packet_byuid(QUID);
		}
		$this->load->view('index/show', $data);
	}*/
	public function show(){
		$id = $this->uri->segment(3);
		if(empty($id)) {
			exit('请求错误！');
		}
		$data = $this->borrow_model->get_borrow_byid($id);
		if(!empty($data['pic'])) {
			$data['pic'] = explode(',', $data['pic']);
		}
		//出借记录
		$data['d_i'] = $this->borrow_model->get_borrow_investor_byid($id);
		foreach($data['d_i'] as $k=>$v) {
			// $real_name = get_member_info($v['investor_uid'])['real_name'];
			// $world = mb_substr($real_name, 1, 1);
			$phone = get_member_info($v['investor_uid'])['phone'];
			$world = mb_substr($phone, 3, 5);
			$data['d_i'][$k]['investor_uid'] = str_replace($world, '***', $phone);
		}
		$data['meminfo'] = $this->member_model->get_member_info_byuid($data['borrow_uid']);
		$data['company'] = $this->borrow_model->get_borrow_uinfo_byuid($data['borrow_uid']);
		if(QUID > 0) {
			$data['money'] = $this->info_model->get_money(QUID);
			//最小投标期限
			$times = $this->config->item('borrow_duration')[$data['borrow_duration']];
			$data['packet'] = $this->member_model->get_packet_byuid(QUID, $times);
		}
		//借款人信息
		$data['company_info'] = $this->member_model->get_company_info_byuid($data['borrow_uid']);
		//借款历史记录, 以后要扩展为分页的形
		$where = array();
		$where['borrow_status >='] = 4; 
		$where['id <>'] = $id;
		$where['send_time <'] = $data['send_time'];
		$data['borrow_history'] = $this->borrow_model->get_borrow_byborrow_uid($data['borrow_uid'], $where);
		//担保人
		$this->load->model(array('guarantor/guarantor_model'));
		$data['guarantors'] = $this->guarantor_model->get_guarantor_more(explode(',', $data['guarantor']));
		
		//$data['']
		//p($data['d_i']);
		
		$this->load->view('index/showa', $data);
	}
	/** 个人贷页面 */
	public function shows(){ 
		$id = $this->uri->segment(3);
		if(empty($id)) {
			exit('请求错误！');
		}
		
		$data = json_decode(get_curl("http://www.jiamanu.cn/api2.aspx?action=query_borrow_detail&proid=".$id), true);
		//p($data);
		//借款人详情
		$data['personal_info'] = json_decode(get_curl("http://www.jiamanu.cn/api2.aspx?action=tenderInfo&proid=".$id), true);
		//p($data['personal_info']);
		
		// 投资记录
		$data['d_i'] = json_decode(get_curl("http://www.jiamanu.cn/api2.aspx?action=query_product_invest_list&proid=".$id), true);
		//p($data['d_i']);
		
		//车辆图片
		$data['car_images'] = json_decode(get_curl("http://www.jiamanu.cn/api2.aspx?action=relatedData&id=".$id), true);
		//p($data['car_images']);
		$this->load->view('index/showa_personal', $data);
	}
	/** 担保人详情图片 */
	public function showimg() {
		$id = $this->uri->segment(3);
		$this->load->model(array('guarantor/guarantor_model'));
		$data['guarantor'] = $this->guarantor_model->get_guarantor_one($id);
		$data['guarantor']['pic'] = explode(',', $data['guarantor']['pic']);
		//p($data['guarantor']['pic']);
		$this->load->view('index/showimg', $data);
	}
	/** 借款人信息图片 */
	public function showimgb() {
		$id = $this->uri->segment(3);
		$this->load->model(array('guarantor/guarantor_model'));
		$data['guarantor'] = $this->borrow_model->get_borrow_byid($id);
		$data['guarantor']['pic'] = explode(',', $data['guarantor']['pic']);
		//p($data['guarantor']['pic']);
		$this->load->view('index/showimg', $data);
	}
	public function dozt(){
		$id = $this->uri->segment(3);
		if($p = $this->input->post(NULL, TRUE)){
			if(!isset($p['CheckBox1'])) {
				$this->error('请认真阅读并确认合同内容');
			}
			$bid = intval($p['bid']);//标的ID
			$redp  = intval($p['ddlRed']);//红包ID
			//出借金额只能为整数
			$money = intval($p['TextBox1']);//round(floatval($p['TextBox1']), 2);//出借金额
			if(! $bid > 0){
				$this->error('服务器链接出错...');
			}
			if(empty(QUID)) {
				$this->error('请登录后再操作...');
			}
			if($money < 0.001) {
				$this->error('出借金额不能为空');
			}
			$member = $this->member_model->get_member_byuserid(QUID);
			if($member['attribute'] === '2') {
				$this->error('融资用户不能投标');
			}
			$mymoney = $this->info_model->get_money(QUID);
			$info = $this->borrow_model->get_borrow_byid($bid);
			// 判断是否有密码2019-5-17
			if(!empty($info['password'])) {
				if(!isset($p['pwd']) || ($p['pwd'] != $info['password'])) {
					$this->error('出借密码不正确，请修改后再提交');
				}
			}
			if($redp > 0) {
				$packet = $this->member_model->get_packet_byid($redp, QUID);
				if($packet['status'] > 0) {
					$this->error('红包已使用，不能重复使用');
				}
				if($packet['etime'] < time()) {
					$this->error('红包已过期');
				}
				$borrow_duration = $this->config->item('borrow_duration')[$info['borrow_duration']];
				//echo $borrow_duration;
				// if($borrow_duration < 30) {
					// $this->error('标的期限不满足红包使用条件');
				// }
				if($borrow_duration < $packet['times']) {
					$this->error('标的期限不满足红包使用条件');
				}
				if($money < $packet['min_money']) {
					$this->error('出借金额不满足红包使用条件');
				}
				/** 只能使用注册红包，新手标 */
				if($info['borrow_type'] == '2') {
					if(!in_array($packet['money'], array(8, 30, 50, 100))) {
						$this->error('新手标只能使用注册红包');
					}
				}
			}
			//是否出借过新手标
			/* $new_num = $this->borrow_model->get_borrow_investor_type_num(2, QUID);
			if($new_num > 0 && $info['borrow_type'] === '2') {
				$this->error('新手标只能出借一次');
			} */
			if($info['borrow_type'] == '2') {
				$new_num = $this->borrow_model->is_invest_new(QUID);
				if($new_num > 0) {
					$this->error('新手标只能出借一次');
				}
				if($money < 1000) {
					$this->error('新手标最小出借金额为1000元');
				}
				if($money > 50000) {
					$this->error('新手标最大出借金额为50000元');
				}
			}
			/** 500元标  需要添加只能投一次的功能*/
			if($info['borrow_type'] == '5') {
				if($money != 500) {
					$this->error('投标金额必须是500元');
				}
				if($redp > 0) {
					$this->error('该标的不能使用红包');
				}
			}
			if($money > ($info['borrow_money'] - $info['has_borrow'])) {
				$this->error('出借金额大于可投金额');
			}
			if(! $money > $info['borrow_min']){
				$this->error('出借金额小于最小出借金额...');
			}
			if(! $money > $info['borrow_max']){
				$this->error('出借金额大于最大出借金额...');
			}
			if($money > $mymoney['account_money']){
				$this->error('出借金额大于可用金额...');
			}
			$has = $info['has_borrow'] + $money;
			
			$last = $info['borrow_money'] - $has;
			if($last > 0 && $last <  $info['borrow_min']){
				$this->error('剩余金额不足最小出借金额...');
			}
			
			$merOrderNo = getTxNo20();
			water(QUID, $merOrderNo, 'CG1052', $bid, $money, $redp);
			$this->success($merOrderNo);
		}
	}
	public function toub() {
		if(empty(QUID)) {
			exit('请登录后操作');
		}
		$merOrderNo = $this->uri->segment(3);
		if(empty($merOrderNo)) {
			exit('服务器链接出错...');
		}
		
		$this->load->model(array('water/water_model'));
		$water = $this->water_model->get_water_byorder($merOrderNo);
		if(empty($water)) {
			exit('服务器链接出错....');
		}
		
		$this->load->model('contract/contract_model');
		$contract_pdf = $this->contract_model->get_contract_pdf_bynid($merOrderNo);
		if(empty($contract_pdf['pdf_path'])) {
			exit('还未签署合同...'); 
		}

		$mem = $this->member_model->get_member_byuserid(QUID);
		$meminfo = get_member_info(QUID);
		$info = $this->borrow_model->get_borrow_byid($water['bid']);
		
		$params['acctNo']			= $meminfo['acctNo'];
		$params['subjectNo']		= $info['subjectNo'];
		$params['amount']			= $water['money'];
		$params['callbackUrl'] 		= 'https://www.jiamanu.com/paytest';
		$params['responsePath'] 	= 'https://www.jiamanu.com/invest/show/'.$water['bid'].'/'.time().'.html';
		
		$data = head($params, 'CG1052', 'transfer', $merOrderNo);
		//water(QUID, $data['merOrderNo'], 'CG1052', $bid, $money, $rid);
		
		$data['url'] = $this->config->item('Interfaces').'1052';
		$this->load->view('account/jump', $data);
	}
	//出借协议
	public function contract() {
		$data = array();
		$data['borrow_name'] = '';
		$data['contract_num'] = '';
		$data['subject_num'] = '';
		$data['y'] = '';
		$data['m'] = '';
		$data['d'] = '';
		$data['investor_name'] = '';
		$data['investor_idcard_type'] = '';
		$data['investor_idcard'] = '';
		$data['borrow_idcard_type'] = '';
		$data['borrow_idcard'] = '';
		$data['borrow_address'] = '';
		$data['guarantor_name'] = '';
		$data['guarantor_idcard_type'] = '';
		$data['guarantor_idcard'] = '';
		$data['guarantor_html'] = '';
		$data['borrow_title'] = '';
		$data['investor_capital'] = '';
		$data['borrow_duration'] = '';
		$data['borrow_repayment'] = '';
		$data['borrow_total'] = '';
		$data['borrow_rate'] = '';
		$data['start_time'] = '';
		$data['borrow_deadline'] = '';
		$data['borrow_use'] = '';
		$data['borrow_formula'] = '';
		$data['fee_money'] = '';
		$data['fee_rate'] = '';
		$data['fee_day'] = '';
		$data['borrow_complex_rate'] = '';
		$data['day_rate'] = '';
		$data['day_penalty_rate'] = '';
		$data['endtime'] = '';
		$data['guarantor_names'] = '';
		
		$html = contract_build($data, 10);
		$save_path = html2pdf($html, array(), '');
		//file_put_contents('/data/sftp/jmupc/web/contracts/aaa.txt', $save_path);
		//var_dump($save_path);
	}
	//生成合同
	public function build() {
		$merOrderNo = $this->uri->segment(3);
		$data['merOrderNo'] = $merOrderNo;
		$this->load->view('index/build', $data);
	}
	//借款合同
	public function build_one() {
		$merOrderNo = $this->uri->segment(3) ? $this->uri->segment(3) : 0;
		$data = $this->get_contract_info($merOrderNo);
		$html = contract_build($data, 10);
		
		
		//记录生成pdf合同的信息
		$this->load->model('contract/contract_model');
		$contract = $this->contract_model->get_contract_pdf_bynid($merOrderNo);
		
		//如果没有生成过pdf版本合同，要生成pdf版本合同，否则不需要生成
		if(empty($contract)) {
			$save_path = html2pdf($html, array('I', 'F'), '');
			$contract['nid'] = $merOrderNo;
			$contract['contract_num'] = $data['contract_num'];
			$contract['src_path'] = $save_path;
			$contract['addtime'] = time();
			$this->contract_model->modify_contract_pdf($contract);
		} elseif(empty($contract['src_path'])) {
			$save_path = html2pdf($html, array('I', 'F'), '');
			$contract['contract_num'] = $data['contract_num'];
			$contract['src_path'] = $save_path;
			$this->contract_model->modify_contract_pdf($contract);
		} else {
			html2pdf($html, array(), '');
		}
	}
	//借款合同
	public function build_one_v1() {
		$merOrderNo = $this->uri->segment(3) ? $this->uri->segment(3) : 0;
		$data = $this->get_contract_info($merOrderNo);
		$data['y'] = '';
		$data['m'] = '';
		$data['d'] = '';
		$data['start_time'] = '';//借款起息日
		$data['borrow_deadline'] = '';//借款到期日
		$data['endtime'] = '';
		
		$html = contract_build($data, 10);
		
		//记录生成pdf合同的信息
		$this->load->model('contract/contract_model');
		$contract = $this->contract_model->get_contract_pdf_bynid($merOrderNo);
		
		//只用显示，不用生成任何存储数据
		html2pdf($html, array(), '');
		// if(empty($contract)) {
			// $save_path = html2pdf($html, array('I', 'F'), '');
			// $contract['nid'] = $merOrderNo;
			// $contract['contract_num'] = $data['contract_num'];
			// $contract['src_path'] = $save_path;
			// $contract['addtime'] = time();
			// $this->contract_model->modify_contract_pdf($contract);
		// } elseif(empty($contract['src_path'])) {
			// $save_path = html2pdf($html, array('I', 'F'), '');
			// $contract['contract_num'] = $data['contract_num'];
			// $contract['src_path'] = $save_path;
			// $this->contract_model->modify_contract_pdf($contract);
		// } else {
			// html2pdf($html, array(), '');
		// }
	}
	//居间合同
	public function build_two() {
		$merOrderNo = $this->uri->segment(3);

		$data = $this->get_contract_mediation_info($merOrderNo);
		//p($data);die;
		$html = contract_build($data, 11);
		
		//记录生成pdf合同的信息
		$this->load->model('contract/contract_model');
		$contract = $this->contract_model->get_contract_pdf_bynid($merOrderNo);
		//var_dump($contract);
		//如果没有生成过pdf版本合同，要生成pdf版本合同，否则不需要生成
		if(empty($contract)) {
			$save_path = html2pdf($html, array('I', 'F'), '');
			$contract['nid'] = $merOrderNo;
			//$contract['contract_num'] = $data['contract_num'];
			$contract['src1_path'] = $save_path;
			$contract['addtime'] = time();
			$this->contract_model->modify_contract_pdf($contract);
		} elseif(empty($contract['src1_path'])) {
			$save_path = html2pdf($html, array('I', 'F'), '');
			$contract['src1_path'] = $save_path;
			$this->contract_model->modify_contract_pdf($contract);
		} else {
			html2pdf($html, array(), '');
		}
		
	}
	//发送易签宝验证码
	public function send_code() {
		if(IS_POST) {
			if(empty(QUID)) {
				$this->error('还未登陆，请登陆后再操作');
			}
			$accountId = '';//账户标识
			//调取个人信息
			$meminfo = $this->member_model->get_member_info_byuid(QUID);
			$memstatus = $this->member_model->get_members_status_byuserid(QUID);
			if(empty($memstatus['id_status'])) {
				$this->error('还未实名认证，不能签署合同');
			}
			
			//如果没有创建账户，需要重新创建账户
			$this->load->library('EsignAPI/EsignAPI'); 
			$EsignAPI = new EsignAPI();
			if(empty($meminfo['accountId'])) {
				//创建个人账户
				$res = $EsignAPI->addPersonAccount($meminfo);
				//print_r($res);die;
				if(empty($res['errCode'])) {//返回账户标识
					$meminfo['accountId'] = $res['accountId'];
					$this->member_model->up_members_info($meminfo, $meminfo['uid']);
				} else {
					$this->error($res['msg']);
				}
			}
			
			//生成签章
			if(empty($meminfo['sealPath'])) {
				$res = $EsignAPI->addPersonTemplateSeal($meminfo);
				if(empty($res['errCode'])) {//返回印章base64图片
					$image = set_base64_image($res['imageBase64'], $meminfo['uid']);
					$meminfo['sealPath'] = $image;
					if(!$this->member_model->up_members_info($meminfo, $meminfo['uid'])) {
						$this->error('签章生成失败,请联系客户,客服电话021-62127903');
					}
				} else {
					$this->error($res['msg']);
				}
			}
			
			
			//发送验证码
			$res = $EsignAPI->sendCode($meminfo);
			$this->load->model('contract/contract_model');
			$this->contract_model->add_sendcode(array('uid'=>QUID, 'code'=>'', 'addtime'=>time(), 'type'=>1));
			$this->error($res['msg']);
		}
	}
	//签署合同
	public function contract_submit() {
		if(IS_POST) {
			$post = $this->input->post(NULL, TRUE);
			if(empty($post['merOrderNo'])) {
				$this->error('信息错误，请重新投标');
			}
			if(!isset($post['agree_one'])) {
				$this->error('必选选择同意并签署伽满借款合同');
			}
			if(!isset($post['agree_two'])) {
				$this->error('必选选择同意并签署伽满借款合同');
			}
			if(empty($post['pcode'])) {
				$this->error('必须要有验证码');
			}
			
			$this->load->model(array('water/water_model', 'contract/contract_model', 'guarantor/guarantor_model'));
			$contract = $this->contract_model->get_contract_pdf_bynid($post['merOrderNo']);
			
			//判断是否生成过借款合同，如果没有，直接生成
			if(empty($contract)) {
				$data = $this->get_contract_info($post['merOrderNo']);
				$html = contract_build($data, 10);
				//如果没有生成过pdf版本合同，要生成pdf版本合同，否则不需要生成
				$save_path = html2pdf($html, array('F'), '');
				$contract['nid'] = $post['merOrderNo'];
				$contract['contract_num'] = $data['contract_num'];
				$contract['src_path'] = $save_path;
				$contract['addtime'] = time();
				
				$data = $this->get_contract_mediation_info($post['merOrderNo']);
				$html = contract_build($data, 11);
				$save_path = html2pdf($html, array('F'), '');
				$contract['src1_path'] = $save_path;
				
				$this->contract_model->modify_contract_pdf($contract);
			} elseif(empty($contract['src_path']) || empty($contract['src1_path'])) {
				if(empty($contract['src_path'])) {
					$data = $this->get_contract_info($post['merOrderNo']);
					$html = contract_build($data, 10);
					$save_path = html2pdf($html, array('F'), '');
					$contract['src_path'] = $save_path;
					$contract['contract_num'] = $data['contract_num'];
				}
				
				if(empty($contract['src1_path'])) {
					$data = $this->get_contract_mediation_info($post['merOrderNo']);
					$html = contract_build($data, 11);
					
					$save_path = html2pdf($html, array('F'), '');
					$contract['src1_path'] = $save_path;
				}
				$this->contract_model->modify_contract_pdf($contract);
			}
			
			
			//签署合同
			
			//调取合同信息
			$contract = $this->contract_model->get_contract_pdf_bynid($post['merOrderNo']);
			//调取流水信息
			$water = $this->water_model->get_water_byorder($post['merOrderNo']);
			if(empty($water)) {
				$this->error('信息有误，请重新投标');
			}
			//调取借款信息
			$borrow = $this->borrow_model->get_borrow_byid($water['bid']);
			//调取借款人签章
			$borrow_info = $this->member_model->get_member_info_byuid($borrow['borrow_uid']);
			//调取担保人签章
			if(!empty($borrow['guarantor'])) {
				$guarantor = $this->guarantor_model->get_guarantor_more(explode(',', $borrow['guarantor']));
			}
			//调取出借人签章
			$investor = $this->member_model->get_member_info_byuid(QUID);
			
			$this->load->library('EsignAPI/EsignAPI'); 
			$EsignAPI = new EsignAPI();
			
			//出借人签署
			$path = dirname(BASEPATH);
			if(empty($contract['des_path']) && empty($contract['des1_path'])) {
				$res = $EsignAPI->userMultiSignPDF($investor, $path, $post['pcode'], $contract);
				if(!empty($res['failList'])) {//报错
					$this->error($res['failList'][0]['msg']);
				} else {
					if(!empty($res['errCode'])) {//报错
						$this->error($res['msg']);
					} else {
						foreach($res['successList'] as $k=>$v) {
							if($contract['src_path'] == substr($v['filePath'], -58)) {
								$contract['des_path'] = substr($v['dstFilePath'], -58);
								$contract['signServiceId'] = $v['signServiceId'];
								$contract['signDetailUrl'] = $v['signDetailUrl'];
							} else {
								$contract['des1_path'] = substr($v['dstFilePath'], -58);
								$contract['signServiceId1'] = $v['signServiceId'];
								$contract['signDetailUrl1'] = $v['signDetailUrl'];
							}
						}
						$this->contract_model->modify_contract_pdf($contract);
						$this->contract_model->add_sendcode(array('uid'=>QUID, 'code'=>$post['pcode'], 'addtime'=>time(), 'type'=>2));
					}
				}
			}
			
			//借款人签署
			//签署位置
			$signPos = array(
				'posPage' => count_pdf_pages($path . $contract['src_path']),//6,
				'posX' => 150,
				'posY' => 410,
				'key' => '',
				'width' => 150
			);
			$res = $EsignAPI->userSignPDF($borrow_info, $path, $contract['des_path'], $signPos);
			//$this->contract_model->add_ret(array('uid'=>intval($water['uid']), 'bid'=>intval($water['bid']), 'msg'=>serialize($res), 'addtime'=>time(), 'type'=>2));
			if(!empty($res['errCode'])) {
				$this->error($res['msg'] . 1);
			} else {
				$data_water = array(
					'bid' => intval($water['bid']),
					'uid' => $borrow['borrow_uid'],
					'nid' => $post['merOrderNo'],
					'path' => $res['des_path'],
					'signServiceId' => $res['signServiceId']
				);
				$this->contract_model->add_contract_water($data_water);
			}
			//要签署文档的路径
			$sign_path = $res['des_path'];
			
			//担保人签署
			if(!empty($borrow['guarantor'])) {
				$signPos['posY'] = $signPos['posY'] - 62;
				$signPos['width'] = 50;
				//测试数据
				// $v = $guarantor[0];
				// $signPos['posX'] = 50;
				// for($i = 0; $i < 20; $i++) {
					////担保人签署
					// $signPos['posX'] += 50;
					// if($i == 10) {
						// $signPos['posX'] = 50;
						// $signPos['posY'] = $signPos['posY'] - 50;
					// }
					// $res = $EsignAPI->userSignPDF($v, $path, $sign_path, $signPos);
					// if(!empty($res['errCode'])) {
						// $this->error($res['msg'] . 2);
					// } else {
						// $data_water = array(
							// 'uid' => $v['id'],
							// 'nid' => $post['merOrderNo'],
							// 'path' => $res['des_path'],
							// 'signServiceId' => $res['signServiceId'],
							// 'type'	=> 1
						// );
						// $this->contract_model->add_contract_water($data_water);
					// }
					// $sign_path = $res['des_path'];
				// }
				
				
				$i = 0;
				$signPos['posX'] = 50;
				foreach($guarantor as $k=>$v) {
					$signPos['posX'] += 50;
					if($i == 10) {
						$signPos['posX'] = 50;
						$signPos['posY'] = $signPos['posY'] - 50;
					}
					$res = $EsignAPI->userSignPDF($v, $path, $sign_path, $signPos);
					//$this->contract_model->add_ret(array('uid'=>intval($water['uid']), 'bid'=>intval($water['bid']), 'msg'=>serialize($res), 'addtime'=>time(), 'type'=>3));
					if(!empty($res['errCode'])) {
						$this->error($res['msg'] . 2);
					} else {
						$data_water = array(
							'bid' => intval($water['bid']),
							'uid' => $v['id'],
							'nid' => $post['merOrderNo'],
							'path' => $res['des_path'],
							'signServiceId' => $res['signServiceId'],
							'type'	=> 1
						);
						$this->contract_model->add_contract_water($data_water);
					}
					$sign_path = $res['des_path'];
					$i++;
				}
			}
			
			
			//平台签署借款合同
			$signPos['posX'] = 150;
			$signPos['posY'] = $signPos['posY'] - 62;
			$signPos['width'] = 150;
			$res = $EsignAPI->selfSignPDF($path, $sign_path, $signPos);
			//$this->contract_model->add_ret(array('uid'=>intval($water['uid']), 'bid'=>intval($water['bid']), 'msg'=>serialize($res), 'addtime'=>time(), 'type'=>4));
			if(!empty($res['errCode'])) {
				$this->error($res['msg'] . 3);
			} else {
				$data_water = array(
					'bid' => intval($water['bid']),
					'uid' => 0,
					'nid' => $post['merOrderNo'],
					'path' => $res['des_path'],
					'signServiceId' => $res['signServiceId']
				);
				$this->contract_model->add_contract_water($data_water);
			}
			$sign_path = $res['des_path'];
			
			//平台签署居间合同
			$signPos = array(
				'posPage' => count_pdf_pages($path . $contract['src1_path']),//4,//
				'posX' => 260,
				'posY' => 720,
				'key' => '',
				'width' => 150
			);
			//echo $signPos['posPage'];die;
			$res = $EsignAPI->selfSignPDF($path, $contract['des1_path'], $signPos);
			if(!empty($res['errCode'])) {
				$this->error($res['msg'] . 4);
			} else {
				$contract = $this->contract_model->get_contract_pdf_bynid($post['merOrderNo']);
				$contract['pdf_path'] = $res['des_path'];
				$contract['path'] = $sign_path;
				$contract['uptime'] = time();
				$this->contract_model->modify_contract_pdf($contract);
			}
			
			//签署成功，跳转出借页面
			$url = '/invest/toub/' . $post['merOrderNo'] . '.html';
			$this->success('签署成功', $url);
			
			
			
		}
	}
	//生成合同新版
	public function contract_submit_v1() {
		if(IS_POST) {
			$post = $this->input->post(NULL, TRUE);
			if(empty($post['merOrderNo'])) {
				$this->error('信息错误，请重新投标');
			}
			if(!isset($post['agree_one'])) {
				$this->error('必选选择同意并签署伽满借款合同');
			}
			if(!isset($post['agree_two'])) {
				$this->error('必选选择同意并签署伽满借款合同');
			}
			if(empty($post['pcode'])) {
				$this->error('必须要有验证码');
			}
			
			$this->load->model(array('water/water_model', 'contract/contract_model', 'guarantor/guarantor_model'));
			$contract = $this->contract_model->get_contract_pdf_bynid($post['merOrderNo']);
			
			//判断是否生成过借款合同，如果没有，直接生成
			if(empty($contract)) {
				//$data = $this->get_contract_info($post['merOrderNo']);
				//$html = contract_build($data, 10);
				//如果没有生成过pdf版本合同，要生成pdf版本合同，否则不需要生成
				//$save_path = html2pdf($html, array('F'), '');
				$contract['nid'] = $post['merOrderNo'];
				$contract['contract_num'] = $data['contract_num'];
				//$contract['src_path'] = $save_path;
				$contract['addtime'] = time();
				
				$data = $this->get_contract_mediation_info($post['merOrderNo']);
				$html = contract_build($data, 11);
				$save_path = html2pdf($html, array('F'), '');
				$contract['src1_path'] = $save_path;
				
				$this->contract_model->modify_contract_pdf($contract);
			} elseif(empty($contract['src1_path'])) {
				// if(empty($contract['src_path'])) {
					// $data = $this->get_contract_info($post['merOrderNo']);
					// $html = contract_build($data, 10);
					// $save_path = html2pdf($html, array('F'), '');
					// $contract['src_path'] = $save_path;
					// $contract['contract_num'] = $data['contract_num'];
				// }
				
				if(empty($contract['src1_path'])) {
					$data = $this->get_contract_mediation_info($post['merOrderNo']);
					$html = contract_build($data, 11);
					
					$save_path = html2pdf($html, array('F'), '');
					$contract['src1_path'] = $save_path;
				}
				$this->contract_model->modify_contract_pdf($contract);
			}
			
			
			//签署合同
			
			//调取合同信息
			$contract = $this->contract_model->get_contract_pdf_bynid($post['merOrderNo']);
			//调取流水信息
			$water = $this->water_model->get_water_byorder($post['merOrderNo']);
			if(empty($water)) {
				$this->error('信息有误，请重新投标');
			}
			//调取借款信息
			$borrow = $this->borrow_model->get_borrow_byid($water['bid']);
			//调取借款人签章
			//$borrow_info = $this->member_model->get_member_info_byuid($borrow['borrow_uid']);
			//调取担保人签章
			// if(!empty($borrow['guarantor'])) {
				// $guarantor = $this->guarantor_model->get_guarantor_more(explode(',', $borrow['guarantor']));
			// }
			//调取出借人签章
			$investor = $this->member_model->get_member_info_byuid(QUID);
			
			$this->load->library('EsignAPI/EsignAPI'); 
			$EsignAPI = new EsignAPI();
			
			//出借人签署
			$path = dirname(BASEPATH);
			if(empty($contract['des_path']) && empty($contract['des1_path'])) {
				$res = $EsignAPI->userMultiSignPDF_v1($investor, $path, $post['pcode'], $contract);
				if(!empty($res['failList'])) {//报错
					$this->error($res['failList'][0]['msg']);
				} else {
					if(!empty($res['errCode'])) {//报错
						$this->error($res['msg']);
					} else {
						foreach($res['successList'] as $k=>$v) {
							if($contract['src_path'] == substr($v['filePath'], -58)) {
								$contract['des_path'] = substr($v['dstFilePath'], -58);
								$contract['signServiceId'] = $v['signServiceId'];
								$contract['signDetailUrl'] = $v['signDetailUrl'];
							} else {
								$contract['des1_path'] = substr($v['dstFilePath'], -58);
								$contract['signServiceId1'] = $v['signServiceId'];
								$contract['signDetailUrl1'] = $v['signDetailUrl'];
							}
						}
						$this->contract_model->modify_contract_pdf($contract);
					}
				}
			}
			
			//借款人签署
			//签署位置
			// $signPos = array(
				// 'posPage' => count_pdf_pages($path . $contract['src_path']),//6,
				// 'posX' => 150,
				// 'posY' => 410,
				// 'key' => '',
				// 'width' => 150
			// );
			// $res = $EsignAPI->userSignPDF($borrow_info, $path, $contract['des_path'], $signPos);
			// if(!empty($res['errCode'])) {
				// $this->error($res['msg'] . 1);
			// } else {
				// $data_water = array(
					// 'uid' => $borrow['borrow_uid'],
					// 'nid' => $post['merOrderNo'],
					// 'path' => $res['des_path'],
					// 'signServiceId' => $res['signServiceId']
				// );
				// $this->contract_model->add_contract_water($data_water);
			// }
			//要签署文档的路径
			//$sign_path = $res['des_path'];
			
			//担保人签署
			
			// if(!empty($borrow['guarantor'])) {
				// $signPos['posY'] = $signPos['posY'] - 62;
				// $signPos['width'] = 50;

				// $i = 0;
				// $signPos['posX'] = 50;
				// foreach($guarantor as $k=>$v) {
					// $signPos['posX'] += 50;
					// if($i == 10) {
						// $signPos['posX'] = 50;
						// $signPos['posY'] = $signPos['posY'] - 50;
					// }
					// $res = $EsignAPI->userSignPDF($v, $path, $sign_path, $signPos);
					// if(!empty($res['errCode'])) {
						// $this->error($res['msg'] . 2);
					// } else {
						// $data_water = array(
							// 'uid' => $v['id'],
							// 'nid' => $post['merOrderNo'],
							// 'path' => $res['des_path'],
							// 'signServiceId' => $res['signServiceId'],
							// 'type'	=> 1
						// );
						// $this->contract_model->add_contract_water($data_water);
					// }
					// $sign_path = $res['des_path'];
					// $i++;
				// }
			// }
			
			
			//平台签署借款合同
			// $signPos['posX'] = 150;
			// $signPos['posY'] = $signPos['posY'] - 62;
			// $signPos['width'] = 150;
			// $res = $EsignAPI->selfSignPDF($path, $sign_path, $signPos);
			// if(!empty($res['errCode'])) {
				// $this->error($res['msg'] . 3);
			// } else {
				// $data_water = array(
					// 'uid' => 0,
					// 'nid' => $post['merOrderNo'],
					// 'path' => $res['des_path'],
					// 'signServiceId' => $res['signServiceId']
				// );
				// $this->contract_model->add_contract_water($data_water);
			// }
			// $sign_path = $res['des_path'];
			
			//平台签署居间合同
			$signPos = array(
				'posPage' => count_pdf_pages($path . $contract['src1_path']),//4,//
				'posX' => 260,
				'posY' => 720,
				'key' => '',
				'width' => 150
			);
			//echo $signPos['posPage'];die;
			$res = $EsignAPI->selfSignPDF($path, $contract['des1_path'], $signPos);
			if(!empty($res['errCode'])) {
				$this->error($res['msg'] . 4);
			} else {
				$contract = $this->contract_model->get_contract_pdf_bynid($post['merOrderNo']);
				$contract['pdf_path'] = $res['des_path'];
				$contract['path'] = $sign_path;
				$contract['uptime'] = time();
				$this->contract_model->modify_contract_pdf($contract);
			}
			
			//签署成功，跳转出借页面
			$url = '/invest/toub/' . $post['merOrderNo'] . '.html';
			$this->success('签署成功', $url);	
		}
	}
	//生成居间合同所需要的数据
	private function get_contract_mediation_info($merOrderNo) {
		if(empty($merOrderNo)) {
			exit('数据错误');
		}
		//时间戳
		$timestamp = time();
		//调取流水信息
		$this->load->model('water/water_model');
		$water = $this->water_model->get_water_byorder($merOrderNo);
		//借款信息
		$data = array();
		//$investor = $this->borrow_model->get_borrow_investor_by_id($id);
		$borrow = $this->borrow_model->get_borrow_byid($water['bid']);
		foreach($borrow as $k=>$v) {
			$data[$k] = $v;
		}
		
		//出借人信息
		$meminfos = $this->member_model->get_member_info_byuid(QUID);
		$data['investor_name'] = $meminfos['real_name'];
		$data['investor_idcard_type'] = '身份证';
		$data['investor_idcard'] = $meminfos['idcard'];
		//签署日期
		$data['y'] = date('Y', $timestamp);
		$data['m'] = date('m', $timestamp);
		$data['d'] = date('d', $timestamp);
		//签署日期
		$data['endtime'] = date('Y年m月d日', $timestamp);
		//签署地点
		$data['address1'] = '上海';
		$data['address2'] = '上海';
		
		return $data;
	}
	//生成借款合同所需要的数据
	private function get_contract_info($merOrderNo) {
		if(empty($merOrderNo)) {
			exit('数据错误');
		}
		//时间戳
		$timestamp = time();
		//调取流水信息
		$this->load->model('water/water_model');
		$water = $this->water_model->get_water_byorder($merOrderNo);
		//借款信息
		$data = array();
		//$investor = $this->borrow_model->get_borrow_investor_by_id($id);
		$borrow = $this->borrow_model->get_borrow_byid($water['bid']);
		if(empty($borrow)) {
			exit('连接出错，请重新投标');
		}
		foreach($borrow as $k=>$v) {
			$data[$k] = $v;
		}
		//借款人信息
		$meminfo = $this->member_model->get_member_info_byuid($data['borrow_uid']);
		$data['borrow_name'] = $meminfo['real_name'];
		$data['borrow_idcard_type'] = $borrow['type'] > 1 ? '营业执照号' : '身份证';
		$data['borrow_idcard'] = $meminfo['idcard'];
		$company_info = $this->member_model->get_company_info_byuid($data['borrow_uid']);
		$data['borrow_address'] = $borrow['type'] > 1 ? $company_info['reg_address'] : '';
		//出借人信息
		$meminfos = $this->member_model->get_member_info_byuid(QUID);
		$data['investor_name'] = $meminfos['real_name'];
		$data['investor_idcard_type'] = '身份证';
		$data['investor_idcard'] = $meminfos['idcard'];
		$data['investor_capital'] = $water['money'] . '元';
		//担保人
		if(!empty($borrow['guarantor'])) {
			$this->load->model('guarantor/guarantor_model');
			$guarantor = $this->guarantor_model->get_guarantor_more(explode(',', $borrow['guarantor']));
			$data['guarantor_html'] = '';
			$data['guarantor_names'] = '';
			$data['guarantor_idcard'] = '';
			foreach($guarantor as $k=>$v) {
				// if($k > 0) {
					// $data['guarantor_html'] .= '<strong>担保方：' . $v['name'] . '</strong></p><p>证件类型： 身份证&nbsp;</p><p>证件号码：' . $v['idcard'] . '&nbsp;</p><p>地址：' . $v['address'] . '&nbsp;</p><p><br />';
				// } else {
					// $data['guarantor_name'] = $v['name'];
					// $data['guarantor_idcard_type'] = '身份证';
					// $data['guarantor_idcard'] = $v['idcard'];
					// $data['guarantor_address'] = $v['address'];
				// }
				$data['guarantor_names'] .= $v['name'] . ', ';
				$data['guarantor_idcard'] .= $v['idcard'] . ', ';
			}
			$data['guarantor_idcard'] = rtrim($data['guarantor_idcard'], ', ');
			
			//测试数据
			// $data['guarantor_name'] = '刘素平, 刘素平, 刘素平, 刘素平, 刘素平, 刘素平, 刘素平, 刘素平, 刘素平, 刘素平, 刘素平, 刘素平, 刘素平, 刘素平, 刘素平, 刘素平, 刘素平, 刘素平, 刘素平, 刘素平';
			// $data['guarantor_idcard'] = '410221197112101342, 410221197112101342, 410221197112101342, 410221197112101342, 410221197112101342, 410221197112101342, 410221197112101342, 410221197112101342, 410221197112101342, 410221197112101342, 410221197112101342, 410221197112101342, 410221197112101342, 410221197112101342, 410221197112101342, 410221197112101342, 410221197112101342, 410221197112101342, 410221197112101342, 410221197112101342';
			
			
			
			$data['guarantor_idcard_type'] = '身份证';
			//$data['guarantor_names'] = $data['guarantor_name'];
			$data['guarantor_names'] = rtrim($data['guarantor_names'], ', ');
			$data['guarantor_name'] = $data['guarantor_names'];
		} else {
			$data['guarantor_html'] = '';
			$data['guarantor_name'] = '';
			$data['guarantor_idcard_type'] = '';
			$data['guarantor_idcard'] = '';
			$data['guarantor_address'] = '';
			$data['guarantor_names'] = '';
		}
		//借款信息
		//期数
		//$borrow['total'] = 
		$data['borrow_title'] = $borrow['borrow_name'];
		//$data['borrow_money'] = $borrow['borrow_money'] . '元';
		$data['borrow_duration'] = $this->config->item('borrow_duration')[$borrow['borrow_duration']] . '天';
		$data['borrow_repayment'] = $this->config->item('repayment_type')[$borrow['repayment_type']];
		$data['borrow_total'] = $borrow['total'] . '期';
		$data['borrow_rate'] = $borrow['borrow_interest_rate'] + $borrow['add_rate'];
		$data['borrow_endtime'] = date('Y年m月d日', $timestamp);
		$data['borrow_deadline'] = date('Y年m月d日', $timestamp + $this->config->item('borrow_duration')[$borrow['borrow_duration']]*86400);
		$data['borrow_use'] = $borrow['borrow_use'];//$this->config->item('borrow_useid')[$borrow['borrow_useid']];
		$data['borrow_complex_rate'] = $borrow['borrow_interest_rate'] + $borrow['add_rate'] + 12;
		
		//计算公式
		$data['borrow_formula'] = '日利率=借款年化利率÷360×借款期限（天）÷借款期限内总天数';
		//服务费
		$data['fee_rate'] = $borrow['borrow_duration'] - 1;
		$data['fee_money'] = round($borrow['borrow_money'] * $data['fee_rate']/100, 2);
		//$data['fee_rate'] = '3';
		$data['fee_day'] = '3';
		//逾期还款
		$data['day_rate'] = '0.06';
		$data['day_penalty_rate'] = '0.06';
		
		//判断是否有合同号
		$this->load->model('contract/contract_model');
		$contract = $this->contract_model->get_contract_pdf_bynid($merOrderNo);
		if(!empty($contract)) {
			//判断合同日期必须是当天
			if(!empty($contract['contract_num']) && (substr($contract['contract_num'], 0, 8) != substr(date('YmdHis', $timestamp).$water['bid'].QUID, 0, 8))) {
				exit('合同出错，请重新投标');
			}
			if(!empty($contract['contract_num'])) {
				$data['contract_num'] = $contract['contract_num'];
			} else {
				$data['contract_num'] = date('YmdHis', $timestamp).$water['bid'].QUID;
			}
			
		} else {
			$data['contract_num'] = date('YmdHis', $timestamp).$water['bid'].QUID;
		}
		$data['subject_num'] = $borrow['subjectNo'];
		//签署日期
		$data['y'] = date('Y', $timestamp);
		$data['m'] = date('m', $timestamp);
		$data['d'] = date('d', $timestamp);
		//签署日期
		$data['endtime'] = date('Y年m月d日', $timestamp);
		//计息日
		$data['start_time'] = date('Y年m月d日', $timestamp + 86400);
		
		return $data;
	}
	// public function dozt(){
		// $id = $this->uri->segment(3);
		// if($p = $this->input->post(NULL, TRUE)){
			//var_dump($p);die;
			// $bid = $p['bid'];
			// $money = $p['m'];
			// if(! $bid > 0){
				// $message['state'] = 0;
				// $message['message'] = '服务器链接出错...';
				// $this->output
					// ->set_content_type('application/json', 'utf-8')
					// ->set_output(json_encode($message))
					// ->_display();
					// exit;
			// }
			// $mymoney = $this->info_model->get_money(QUID);
			// $info = $this->borrow_model->get_borrow_byid($bid);
			// if(! $money > $info['borrow_min']){
				// $message['state'] = 0;
				// $message['message'] = '出借金额大于最小出借金额...';
				// $this->output
					// ->set_content_type('application/json', 'utf-8')
					// ->set_output(json_encode($message))
					// ->_display();
					// exit;
			// }
			// if(! $money > $info['borrow_max']){
				// $message['state'] = 0;
				// $message['message'] = '出借金额大于最大出借金额...';
				// $this->output
					// ->set_content_type('application/json', 'utf-8')
					// ->set_output(json_encode($message))
					// ->_display();
					// exit;
			// }
			// if(! $money > $mymoney['account_money']){
				// $message['state'] = 0;
				// $message['message'] = '出借金额大于可用金额...';
				// $this->output
					// ->set_content_type('application/json', 'utf-8')
					// ->set_output(json_encode($message))
					// ->_display();
					// exit;
			// }
			// $has = $info['has_borrow'] + $money;
			// $last = $info['borrow_money'] - $has;
			// if($last > 0 && $last <  $info['borrow_min']){
				// $message['state'] = 0;
				// $message['message'] = '剩余金额不足最小出借金额...';
				// $this->output
					// ->set_content_type('application/json', 'utf-8')
					// ->set_output(json_encode($message))
					// ->_display();
					// exit;
			// }
			//支付后台处理
			// $mchnt_cd = $this->config->item('mchnt_cd');
			// $mchnt_txn_ssn = getTxNo20();
			// $in_cust = $this->member_model->get_member_info_byuid(QUID);
			// $cust_no = $in_cust['phone'];
			// $amt = $money * 100;
			// $bname = $info['borrow_name'];
			// $rem = '出借('.$bname.')冻结'.$money.'元';
			// $sdata = $amt.'|'.$cust_no.'|'.$mchnt_cd.'|'.$mchnt_txn_ssn.'|'.$rem.'|0.44';
			// $signature = rsaSign($sdata, './Content/php_prkey.pem');
			// $url = $this->config->item('payurl') . 'transferBuAndFreeze2Freeze.action';
			// $post_data = array(
				// 'ver' 			=> '0.44',
				// 'mchnt_cd' 		=> $mchnt_cd,
				// 'mchnt_txn_ssn' => $mchnt_txn_ssn,
				// 'cust_no' 	=> $cust_no,
				// 'amt' 			=> $amt,
				// 'rem' 			=> $rem,
				// 'signature' 	=> $signature,
			// );
			// $result = post_curl($url,$post_data);
			// $simpleXML= new SimpleXMLElement($result);
			// $c = (array) $simpleXML->children();
			// $d = (array) $c['plain'];
			// if($d['resp_code'] == '000'){
				//处理出借金额,先处理三张表 borrow,borrow_investor,investor_detail
				// $this->db->trans_begin();
				// $borrow = array(
					// 'has_borrow' 	=> $has,
					// 'borrow_times'  => $info['borrow_times'] + 1
				// );
				// $borrow_investor = array(
					// 'borrow_id' 		=> $bid,
					// 'investor_uid'      => QUID,
					// 'borrow_uid'        => $info['borrow_uid'],
					// 'investor_capital'  => $money,
					// 'investor_interest' => round($money * ($info['borrow_interest_rate'] / 100 / 360) * $info['borrow_duration'],2),
					// 'receive_capital' 	=> '0.00',
					// 'receive_interest' 	=> '0.00',
					// 'add_time' 			=> time(),
					// 'deadline' 			=> ($info['borrow_duration'] -1) * 86400 + time(),
				// );
				// $investor_detail = array(
					// 'repayment_time' 	=> 0,
					// 'borrow_id'			=> $bid,
					// 'investor_uid'      => QUID,
					// 'borrow_uid'        => $info['borrow_uid'],
					// 'capital'  => $money,
					// 'interest' => round($money * ($info['borrow_interest_rate'] / 100 / 360) * $info['borrow_duration'],2),
					// 'receive_capital' 	=> '0.00',
					// 'receive_interest' 	=> '0.00',
					// 'sort_order'		=> 1,
					// 'total'             => 1,
					// 'deadline' 			=> ($info['borrow_duration'] -1) * 86400 + time(),
				// );
				// if($has == $info['borrow_money']){
					// $borrow['fulltime'] = time();
					// /* $borrow['borrow_status'] = 3;
					// $borrow_investor['status'] = 3;
					// $investor_detail['status'] = 3; */
					//短信通知后台人员放款
				// }else{
					// $borrow_investor['status'] = $info['borrow_status'];
					// $investor_detail['status'] = $info['borrow_status'];
				// }
				//主表
				// $this->borrow_model->modify_borrow($borrow, $bid);
				//出借总表
				// $invest_id = $this->borrow_model->add_borrow_investor($borrow_investor);
				// $investor_detail['invest_id'] = $invest_id;
				//出借详表
				// $this->borrow_model->add_investor_detail($investor_detail);
				//金额日志
				// $moneys = $this->info_model->get_money(QUID);
				// $mlog = array(
					// 'uid' => QUID,
					// 'type' => 3,//冻结
					// 'affect_money' => $money,
					// 'account_money' => $moneys['account_money'] - $money,//可用
					// 'collect_money' => $moneys['money_collect'],//待收
					// 'freeze_money' => $moneys['money_freeze'] + $money,//冻结
					// 'info' => $mchnt_txn_ssn . ',出借冻结',
					// 'add_time' => time(),
					// 'add_ip' => $this->input->ip_address(),
					// 'bid' => $bid
				// );
				// $um = array(
					// 'account_money' => $moneys['account_money'] - $money,
					// 'money_freeze' => $moneys['money_freeze'] + $money,
				// );
				// $this->member_model->up_members_money($um, QUID);
				// $this->member_model->add_members_moneylog($mlog);
				// if($this->db->trans_status() === TRUE){
					// $this->db->trans_commit();
					// $message['state'] = 1;
					// $message['message'] = '出借成功...';
					// $this->output
						// ->set_content_type('application/json', 'utf-8')
						// ->set_output(json_encode($message))
						// ->_display();
						// exit;
				// }else{
					// $this->db->trans_rollback();
					// $message['state'] = 0;
					// $message['message'] = '出借失败,但第三方支付成功,请联系客服...';
					// $this->output
						// ->set_content_type('application/json', 'utf-8')
						// ->set_output(json_encode($message))
						// ->_display();
						// exit;
				// }
			// }else{
				// $message['state'] = 0;
				// $message['message'] = $d['resp_desc'];//'出借失败,第三方支付操作出错,请联系管理员...';
				// $this->output
					// ->set_content_type('application/json', 'utf-8')
					// ->set_output(json_encode($message))
					// ->_display();
					// exit;
			// }
		// }
	// }
}