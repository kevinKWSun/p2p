<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Zcashc extends Baseaccounts {
	public function __construct() {
		parent::__construct();
		$this->load->library('pagination');
		$this->load->database();
		//$this->load->library('session');
		$this->load->model(array('theme/zcashc_model','member/member_model'));
	}
	
	public function index() {
		$data = array();
		if(QUID > 0) {
			$data['zcashc'] = $this->zcashc_model->get_by_uid(QUID);
		}
		// 已抽奖次数
		$data['m188'] = $this->zcashc_model->get_by_num_uid('188', QUID);
		$data['m288'] = $this->zcashc_model->get_by_num_uid('288', QUID);
		$data['m388'] = $this->zcashc_model->get_by_num_uid('388', QUID);
		$data['m588'] = $this->zcashc_model->get_by_num_uid('588', QUID);
		$data['m788'] = $this->zcashc_model->get_by_num_uid('788', QUID);
		$data['m888'] = $this->zcashc_model->get_by_num_uid('888', QUID);
		$this->load->view('theme/zcashc/index', $data);
	}
	
	// 获取福袋金额
	public function get_money() {
		// 接收参数
		$doub = intval($this->input->post('doub', true));
		if(!in_array($doub, array(1, 2))) {
			$this->error('信息有误');
		}
		// 判断用户是否登陆
		if(!QUID) {
			$this->error('请先登陆');
		}
		$zcashc = $this->zcashc_model->get_by_uid(QUID);
		
		if(!$zcashc) {
			$this->error('剩余福袋次数不足');
		} else {
			if($doub === 1 && !$zcashc['total']) {
				$this->error('剩余福袋次数不足');
			}
			if($doub === 2 && !$zcashc['doub']) {
				$this->error('剩余双倍福袋次数不足');
			}
		}
		
		// 抽取福袋金额
		$num = $this->random_by_weight();
		// 记录到数据表中
		if($doub === 1) {
			$zcashc['total'] = $zcashc['total'] - 1;
		}
		if($doub === 2) {
			$zcashc['doub'] = $zcashc['doub'] - 1;
		}
		$data['zcashc'] = $zcashc;
		$data['detail'] = [
			'uid' => QUID,
			'num' => $num,
			'addtime' => time(),
			'multiple'=> $doub
		];
		if($this->zcashc_model->record_random($data)) {
			$this->success($num);
		} else {
			$this->error('操作失败，请联系客服人员');
		}
	}
	
	/** 按权重生成随机数*/
	private function random_by_weight() {
		$num = 0;
		$random = mt_rand(1, 1000);
		if($random <= 30) { // 3%
			$num = 888;
		} else if($random <= 80) { // 5%
			$num = 788;
		} else if($random <= 180) { // 10%
			$num = 588;
		} else if($random <= 680) { // 50%
			$num = 388;
		} else if($random <= 920) { // 24%
			$num = 288;
		} else { // 8%
			$num = 188;
		}
		return $num;
	}
}