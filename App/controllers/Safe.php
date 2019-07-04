<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Safe extends Baseaccount {
	public function __construct() {
		parent::__construct();
		$this->load->model(array('account/info_model', 'member/member_model'));
	}
	public function index(){
		if ($this->agent->is_mobile()){
			
		}else{
			$this->load->view('account/safe_v1');
		}
	}
	/** 认证合同页面2019-3-22 */
	public function autherui() {
		$this->load->view('account/autherui');
	}
	/** 认证合同 */
	public function auther() {
		if($p = $this->input->post(NULL, TRUE)) {
			if(!isset($p['agree_one']) || !isset($p['agree_two'])) {
				$this->error('同意协议，才能继续');
			} else {
				$this->success('成功', '/account/authentication.html');
			}
		}
	}
	/** 鄂托克前旗农商银行网络交易资金账户服务三方协议样本 */
	public function build_one() {
		$this->load->view('account/safe_build_one');
	}
	/** 免密授权书样本 */
	public function build_two() {
		$this->load->view('account/safe_build_two');
	}
	public function cg(){
		$data['info'] = $this->member_model->get_member_info_byuid(QUID);
		$this->load->view('account/cg', $data);
	}
	public function modifytel() {
		$data['info'] = get_member_info(QUID);
		$this->load->view('account/modifytel', $data);
	}
	public function domodify() {
		if($p = $this->input->post(NULL, TRUE)){
			$meminfo = get_member_info(QUID);
			$tel = $p['tel'];
			if(! is_phone($tel)) {
				$this->error('该手机号不正确');
			}
			if($tel == $meminfo['phone']) {
				$this->error('不能与原手机号相同');
			}
		}else{
			$mem = $this->member_model->get_member_byuserid(QUID);
			$meminfo = get_member_info(QUID);
			$tel = $this->uri->segment(3);
			$params['custNo'] = $meminfo['custNo'];
			$params['callbackUrl'] 		= 'https://www.jiamanu.com/paytest';
			$params['responsePath'] 	= 'https://www.jiamanu.com/safe.html';
			$params['custType'] 		= $mem['type'] === '2' ? '03' : '00';
			$params['registerPhone']        = $meminfo['phone'];
			//$params['oldMobile']        = $meminfo['phone'];
			$params['newMobile']        = $tel;
			$data = head($params, 'CG1049');
			water(QUID, $data['merOrderNo'], 'CG1049');
			
			$data['url'] = $this->config->item('Interfaces').'1049';
			$this->load->view('account/jump', $data);
		}
	}
	
	public function mfpassword(){
		if($m = $this->input->post(NULL, TRUE)){
			$ypass = $m['ypass'];
			$member = $this->member_model->get_member_byuserid(QUID);
			if($member['user_pass'] != md5(suny_encrypt($ypass, $member['salt']))){
	        	$info['state'] = 0;
				$info['message'] = '原密码不正确!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
	        }
			$newpassword = $m['newpass'];
			$data['user_pass'] = $m['pass'];
			if (! is_password($data['user_pass'])){
				$info['state'] = 0;
				$info['message'] = '密码必须由字母和数字组成!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			if($newpassword != $data['user_pass']){
				$info['state'] = 0;
				$info['message'] = '两次新密码不一致!';
				$this->output
				    ->set_content_type('application/json', 'utf-8')
				    ->set_output(json_encode($info))
					->_display();
				    exit;
			}
			if($ypass == $newpassword){
				$info['state'] = 0;
				$info['message'] = '新密码没有改变!';
				$this->output
				    ->set_content_type('application/json', 'utf-8')
				    ->set_output(json_encode($info))
					->_display();
				    exit;
			}
			$data['user_pass'] = MD5(suny_encrypt($data['user_pass'], $member['salt']));
			if($this->member_model->modify_member($data, QUID)){
				$info['state'] = 1;
				$info['message'] = '操作成功!';
				$this->output
				    ->set_content_type('application/json', 'utf-8')
				    ->set_output(json_encode($info))
					->_display();
				    exit;
			}else{
				$info['state'] = 0;
				$info['message'] = '操作失败!';
				$this->output
				    ->set_content_type('application/json', 'utf-8')
				    ->set_output(json_encode($info))
					->_display();
				    exit;
			}
		}
	}
}