<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class Lucky extends Baseaccounts {
	public function __construct() {
		echo '活动结束';die;
		parent::__construct();
		$this->load->library('pagination');
		$this->load->model(array('cj/cj_model','member/member_model', 'cj/zcash_model'));
	}
	
	public function index() {
		$data = array();
		if(QUID > 0) {
			$data['zcash'] = $this->zcash_model->get_by_uid(QUID);
		}
		// 已抽奖次数
		$data['m58'] = $this->zcash_model->get_by_num_uid('58', QUID);
		$data['m88'] = $this->zcash_model->get_by_num_uid('88', QUID);
		$data['m188'] = $this->zcash_model->get_by_num_uid('188', QUID);
		$data['m288'] = $this->zcash_model->get_by_num_uid('288', QUID);
		$data['m588'] = $this->zcash_model->get_by_num_uid('588', QUID);
		$data['m888'] = $this->zcash_model->get_by_num_uid('888', QUID);
		$this->load->view('cj/lucky', $data);
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
		$zcash = $this->zcash_model->get_by_uid(QUID);
		
		if(!$zcash) {
			$this->error('剩余福袋次数不足');
		} else {
			if($doub === 1 && !$zcash['total']) {
				$this->error('剩余单倍福袋次数不足');
			}
			if($doub === 2 && !$zcash['doub']) {
				$this->error('剩余双倍福袋次数不足');
			}
		}
		
		// 抽取福袋金额
		$num = $this->random_by_weight();
		// 记录到数据表中
		if($doub === 1) {
			$zcash['total'] = $zcash['total'] - 1;
		}
		if($doub === 2) {
			$zcash['doub'] = $zcash['doub'] - 1;
		}
		$data['zcash'] = $zcash;
		$data['detail'] = [
			'uid' => QUID,
			'num' => $num,
			'addtime' => time(),
			'multiple'=> $doub
		];
		if($this->zcash_model->record_random($data)) {
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
			$num = 588;
		} else if($random <= 180) { // 10%
			$num = 288;
		} else if($random <= 680) { // 50%
			$num = 188;
		} else if($random <= 920) { // 24%
			$num = 88;
		} else { // 8%
			$num = 58;
		}
		/* if($random <= 28) { // 3%
			$num = 888;
		} else if($random <= 76) { // 5%-0.2%
			$num = 588;
		} else if($random <= 176) { // 10%
			$num = 288;
		} else if($random <= 678) { // 50%+0.2%
			$num = 188;
		} else if($random <= 922) { // 24%+0.2%
			$num = 88;
		} else { // 8%
			$num = 58;
		} */
		return $num;
	}
}