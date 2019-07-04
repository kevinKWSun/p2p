<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Pay extends Baseaccount {
	public function __construct() {
		parent::__construct();
		$this->load->library('pagination');
		$this->load->model(array('account/area_model', 'account/info_model', 'member/member_model', 'recharge/recharge_model'));
		$this->load->helper('common');
	}
	//首页
	public function index(){
		$my['money'] = $this->info_model->get_money(QUID);
		$my['bank'] = $this->member_model->get_bank_byuid('', QUID, 1);
		$my['banks'] = $this->config->item('bank');
		$my['info'] = $this->member_model->get_member_info_byuid(QUID);
		$my['area'] = $this->area_model->getarea(0);
		//
		$current_page = $this->uri->segment(3) ? $this->uri->segment(3) : 1;
		$type = $this->uri->segment(4) ? $this->uri->segment(4) : array(1,2);
        if($current_page > 0){
            $current_page = $current_page - 1;
        }else if($current_page < 0){
            $current_page = 0;
        }
		$per_page = 8;
        $offset = $current_page;
        $config['base_url'] = base_url('pay/index');
        $config['total_rows'] = $this->member_model->get_members_moneylog_num(QUID, $type);
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
        $my['totals'] = $config['total_rows'];
        $my['page'] = $this->pagination->create_links();
        $my['p'] = $current_page;
        $recharge = $this->member_model->get_members_moneylog($per_page, $offset * $per_page, QUID, $type);
		$my['log'] = $recharge;
		$this->load->view('account/pay', $my);
	}
	public function getarea(){
		$id = $this->uri->segment(3);
		$type = $this->uri->segment(4);
		if($type == 1){
			$city = 'area';
		}elseif($type == 2){
			$city = 'city';
		}
		if($id){
			$areas = $this->area_model->getarea($id);
			echo "<option value='0' rel='0'>请选择</option>";
			foreach($areas as $v){
				$name = $v['name'];
				if($type == 2 && $name == '市辖区'){
					continue;
				}
				$pid = $v['id'];
				echo "<option value='$name' rel='$pid'>$name</option>";
			}
			echo '</select>';
		}
	}
	public function add(){
		if($p = $this->input->post(NULL, TRUE)){
			$b = $this->member_model->get_bank_byuid('', QUID, 1);
			if($b){
				exit("<script>alert('已绑定过银行卡...');window.close();</script>");
				$info['state'] = 0;
				$info['message'] = '已绑定过银行卡!';
				$this->output
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($info))
					->_display();
					exit;
			}else{
				$info = $this->member_model->get_members_status_byuserid(QUID);
				if(! $info['id_status']){
					exit("<script>alert('请先进行实名认证...');window.close();</script>");
					$info['state'] = 0;
					$info['message'] = '请先进行实名认证!';
					$this->output
						->set_content_type('application/json', 'utf-8')
						->set_output(json_encode($info))
						->_display();
						exit;
				}
				$phone = $this->member_model->get_member_info_byuid(QUID);
				$data['uid'] = QUID;
				$data['card'] = $p['account'];
				if(! $data['card']){
					exit("<script>alert('卡号不正确...');window.close();</script>");
					$info['state'] = 0;
					$info['message'] = '卡号不正确!';
					$this->output
						->set_content_type('application/json', 'utf-8')
						->set_output(json_encode($info))
						->_display();
						exit;
				}
				$data['bid'] = $p['bank'];
				if(! $data['bid']){
					exit("<script>alert('请选择开户行...');window.close();</script>");
					$info['state'] = 0;
					$info['message'] = '请选择开户行!';
					$this->output
						->set_content_type('application/json', 'utf-8')
						->set_output(json_encode($info))
						->_display();
						exit;
				}
				$province = $p['province'];
				$area = $p['area'];
				$city = isset($p['city']) ? $p['city'] : 0;
				if(! $province || ! $area || ! $city){
					exit("<script>alert('请选择开户地区...');window.close();</script>");
					$info['state'] = 0;
					$info['message'] = '请选择开户地区!';
					$this->output
						->set_content_type('application/json', 'utf-8')
						->set_output(json_encode($info))
						->_display();
						exit;
				}
				$citys = json_decode($this->config->item('city'), TRUE);
				$key = 0;
				if($key = array_search($city, $citys)){
					
				}else{
					if($key = array_search($area, $citys)){
						
					}else{
						$key = array_search($province, $citys);
					}
				}
				$data['city'] = $province . ' ' . $area . ' ' . $city;
				$data['bname'] = $p['bankBranchName'];
				if(! $data['bname']){
					exit("<script>alert('请填写开户支行名称...');window.close();</script>");
					$info['state'] = 0;
					$info['message'] = '请填写开户支行名称!';
					$this->output
						->set_content_type('application/json', 'utf-8')
						->set_output(json_encode($info))
						->_display();
						exit;
				}
				$data['addtime'] = time();
				$certif_tp = 0;
				$weburl = $this->config->item('weburl');
				$back_notify_url = $weburl . 'cli/back.html';
				$bank_nm = $data['bname'];
				$capAcntNo = $data['card'];
				$certif_id = $phone['idcard'];
				$city_id = $key;
				$cust_nm = $phone['real_name'];
				$email = '';
				$mchnt_cd = $this->config->item('mchnt_cd');
				$mchnt_txn_ssn = getTxNo20();
				$mobile_no = $phone['phone'];
				$page_notify_url = $weburl . 'cli.html';
				$parent_bank_id = $data['bid'];
				$user_id_from = $phone['uid'];
				$ver = '0.44';
				$sdata = $back_notify_url . '|' . $bank_nm . '|' . $capAcntNo . '|' . $certif_id . '|' . $city_id . '|' . $cust_nm . '|' . $email . '|' . $mchnt_cd . '|' . $mchnt_txn_ssn . '|' . $mobile_no . '|' . $page_notify_url . '|' . $parent_bank_id . '|' . $user_id_from;
				$signature = rsaSign($sdata, './Content/php_prkey.pem');
				$data['num'] = $mchnt_txn_ssn;
				if($this->member_model->get_bank_byuid('', QUID, '')){
					if(! $this->member_model->up_bank($data, QUID)){
						exit("<script>alert('服务器链接超时....');window.close();</script>");
					}
				}else{
					if(! $this->member_model->add_bank($data)){
						exit("<script>alert('服务器链接超时...');window.close();</script>");
					}
				}
				$url = $this->config->item('payurl') . 'webReg.action';
				echo "
						<!DOCTYPE>
						<html>
						<head>
						<meta charset=utf-8' />
						<title>正在转向绑卡页面...</title>
						</head>
						<body onload='document.autoform.submit()'>
						<form id='autoform' name='autoform' method='post' action='$url' style='height:0px; overflow:hidden;'>
							<input name='ver' value='$ver' /><br>
							<input name='mchnt_cd' value='$mchnt_cd' /><br>
							<input name='mchnt_txn_ssn' value='$mchnt_txn_ssn' /><br>
							<input name='user_id_from' value='$user_id_from' /><br>
							<input name='mobile_no' value='$mobile_no' /><br>
							<input name='cust_nm' value='$cust_nm' /><br>
							<input name='certif_tp' value='$certif_tp' /><br>
							<input name='certif_id' value='$certif_id' /><br>
							<input name='email' value='$email' /><br>
							<input name='city_id' value='$city_id' /><br>
							<input name='parent_bank_id' value='$parent_bank_id' /><br>
							<input name='bank_nm' value='$bank_nm' /><br>
							<input name='capAcntNo' value='$capAcntNo' /><br>
							<input name='page_notify_url' value='$page_notify_url' /><br>
							<input name='back_notify_url' value='$back_notify_url' /><br>
							<input name='signature' value='$signature' /><br>
							<input type='submit' />
						</form>
						</body>
						</html>
				";
			}
		}
	}
	public function adds(){
		if($p = $this->input->post(NULL, TRUE)){
			$transferAmt = $p['transferAmt'];
			$bankType = $p['bankType'];
			$mchnt_cd = $this->config->item('mchnt_cd');
			if($transferAmt < 100){
				exit("<script>alert('充值金额最少为100元...');window.close();</script>");
			}
			if(! $bankType){
				exit("<script>alert('请选择银行...');window.close();</script>");
			}
			$mchnt_txn_ssn = getTxNo20();
			$info = $this->member_model->get_member_info_byuid(QUID);
			$login_id = $info['phone'];
			$amt = $transferAmt * 100;
			$order_pay_type = 'B2B';
			$iss_ins_cd = $bankType;
			$weburl = $this->config->item('weburl');
			$page_notify_url = $weburl . 'cli/qback.html';
			$back_notify_url = $weburl . 'cli/cback.html';
			$signature = $amt . '|' . $back_notify_url . '|' . $iss_ins_cd . '|' . $login_id . '|' . $mchnt_cd . '|' . $mchnt_txn_ssn . '|' . $order_pay_type . '|' . $page_notify_url;
			$signature = rsaSign($signature, './Content/php_prkey.pem');
			$data['uid'] = QUID;
			$data['nid'] = $mchnt_txn_ssn;
			$data['money'] = $transferAmt;
			$data['way'] = '宝富支付';
			$data['bankid'] = $bankType;
			$data['add_time'] = time();
			$data['add_ip'] = $this->input->ip_address();
			if(! $this->recharge_model->add_recharge($data)){
				exit("<script>alert('服务器链接超时...');window.close();</script>");
			}
			$url = $this->config->item('payurl') . '500012.action';
			echo "
					<!DOCTYPE>
					<html>
					<head>
					<meta charset=utf-8' />
					<title>正在转向充值页面...</title>
					</head>
					<body onload='document.autoform.submit()'>
					<form id='autoform' name='autoform' method='post' action='$url' style='height:0px; overflow:hidden;'>
						<input name='mchnt_cd' value='$mchnt_cd' /><br>
						<input name='mchnt_txn_ssn' value='$mchnt_txn_ssn' /><br>
						<input name='login_id' value='$login_id' /><br>
						<input name='amt' value='$amt' /><br>
						<input name='order_pay_type' value='$order_pay_type' /><br>
						<input name='iss_ins_cd' value='$iss_ins_cd' /><br>
						<input name='page_notify_url' value='$page_notify_url' /><br>
						<input name='back_notify_url' value='$back_notify_url' /><br>
						<input name='signature' value='$signature' /><br>
						<input type='submit' />
					</form>
					</body>
					</html>
			";
		}
	}
	public function withdraw(){
		if($p = $this->input->post(NULL, TRUE)){
			$transferAmt = $p['withdrawAmt'];
			$bankType = $p['bid'];
			$mchnt_cd = $this->config->item('mchnt_cd');
			if($transferAmt < 100){
				exit("<script>alert('提现金额最少为100元...');window.close();</script>");
			}
			$money = $this->info_model->get_money(QUID);
			if($transferAmt > $money['account_money']){
				exit("<script>alert('提现金额不能大于可用金额...');window.close();</script>");
			}
			if(! $bankType){
				exit("<script>alert('数据有误...');window.close();</script>");
			}
			$mchnt_txn_ssn = getTxNo20();
			$info = $this->member_model->get_member_info_byuid(QUID);
			$login_id = $info['phone'];
			$amt = $transferAmt * 100;
			$weburl = $this->config->item('weburl');
			$page_notify_url = $weburl . 'cli/tqback.html';
			$back_notify_url = $weburl . 'cli/tcback.html';
			$signature = $amt . '|' . $back_notify_url . '|' . $login_id . '|' . $mchnt_cd . '|' . $mchnt_txn_ssn . '|' . $page_notify_url;
			$signature = rsaSign($signature, './Content/php_prkey.pem');
			$data['uid'] = QUID;
			$data['nid'] = $mchnt_txn_ssn;
			$data['money'] = $transferAmt;
			$data['way'] = '宝富支付';
			$data['bankid'] = $bankType;
			$data['add_time'] = time();
			$data['add_ip'] = $this->input->ip_address();
			$data['type'] = 2;
			if(! $this->recharge_model->add_recharge($data)){
				exit("<script>alert('服务器链接超时...');window.close();</script>");
			}
			$url = $this->config->item('payurl') . '500003.action';
			echo "
					<!DOCTYPE>
					<html>
					<head>
					<meta charset=utf-8' />
					<title>正在转向提现页面...</title>
					</head>
					<body onload='document.autoform.submit()'>
					<form id='autoform' name='autoform' method='post' action='$url' style='height:0px; overflow:hidden;'>
						<input name='mchnt_cd' value='$mchnt_cd' /><br>
						<input name='mchnt_txn_ssn' value='$mchnt_txn_ssn' /><br>
						<input name='login_id' value='$login_id' /><br>
						<input name='amt' value='$amt' /><br>
						<input name='page_notify_url' value='$page_notify_url' /><br>
						<input name='back_notify_url' value='$back_notify_url' /><br>
						<input name='signature' value='$signature' /><br>
						<input type='submit' />
					</form>
					</body>
					</html>
			";
		}
	}
}