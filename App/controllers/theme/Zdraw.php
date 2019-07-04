<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Zdraw extends Baseaccounts {
	public function __construct() {
		parent::__construct();
		$this->load->library('pagination');
		$this->load->model(array('theme/zdraw_model','member/member_model'));
		$this->rand_rate = array(
			'518' => '0.05',
			'520' => '0.05',
			'3' => '0.9',
		);
	}
	public function index() {
		$data = array();
		if(QUID > 0) {
			// 抽奖次数
			$zdraw = $this->zdraw_model->get_zdraw_byuid(QUID);
			$data['zdraw'] = $zdraw;
		}
		$this->load->view('theme/zdraw/index', $data);
	}
	
	public function random() {
		$msg = array(
			'1' => '抽奖次数不足'
		);
		$error_no = 0;
		if(!QUID) {
			$this->error('请先登陆');
		}
		$random = $this->get_prize();
		
		// 处理数据
		$this->db->trans_begin();
		$zdraw = $this->zdraw_model->get_zdraw_byuid(QUID);
		if($zdraw['num'] < 1) {
			$error_no = 1;
		} else {
			$zdraw['num'] -= 1;
		}
		// 更新主表抽奖次数
		$this->zdraw_model->update_zdraw($zdraw);
		// 记录抽奖详情
		if($random == 518) {
			$money = 1554;
		} else if($random == 520) {
			$money = 1040;
		} else {
			$money = $random;
		}
		$detail = [
			'uid' => $zdraw['uid'],
			'num' => $random,
			'money' => $money,
			'addtime' => time()
		];
		$this->zdraw_model->insert_detail($detail);
		
		
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$this->error("操作失败，请联系客服");
		} else {
			if($error_no) {
				$this->db->trans_rollback();
				$this->error($msg[$error_no]);
			} else {
				$this->db->trans_commit();
				$this->success($random);
			}
		}
	}
	
	public function getmoney() {
		// 查询未发放的金额
		$total_money = $this->zdraw_model->get_total_money(QUID)['money'];
		$total_money = $total_money ? $total_money : 0;
		$this->success($total_money);
	}
	
	/** 测试概率值 */
	private function test_random() {
		$total = 1000000;
		$arr = array();
		for($i = 0; $i < $total; $i++) {
			$random = $this->get_prize();
			$arr[$random] = isset($arr[$random]) ? ($arr[$random] + 1) : 1;
		}
		
		foreach($arr as $k=>$v) {
			echo $k, ': ', $v/$total, ' 个数： ', $v, '<br />';
		}
	}
	
	/** 返回奖品列 */
	private function get_prize() {
		// 比率 1:1000
		$ratio = 1000;
		// 得到概率值
		$rand_num = $this->create_rand($ratio);
		$rate = 0;
		foreach($this->rand_rate as $k=>$v) {
			$rate += $v*$ratio;
			if($rand_num <= $rate) {
				if($k == 3) {
					if($rand_num == 1000) {
						return 100;
					}
					if(($rand_num == '518') || ($rand_num == '520')) {
						return $this->get_prize();
					}
					return $rand_num;
					
				} else {
					return $k;
				}
			}
		}
	}
	
	/** 生成随机数 */
	private function create_rand($ratio) {
		return mt_rand(1, $ratio);
	}
}