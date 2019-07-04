<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Zdrawc extends Baseaccounts {
	public function __construct() {
		parent::__construct();
		$this->load->library('pagination');
		$this->load->model(array('theme/zdrawc_model','member/member_model'));
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
			$zdrawc = $this->zdrawc_model->get_zdrawc_byuid(QUID);
			$data['zdrawc'] = $zdrawc;
		}
		$this->load->view('theme/zdrawc/index', $data);
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
		$zdrawc = $this->zdrawc_model->get_zdrawc_byuid(QUID);
		if($zdrawc['num'] < 1) {
			$error_no = 1;
		} else {
			$zdrawc['num'] -= 1;
		}
		// 更新主表抽奖次数
		$this->zdrawc_model->update_zdrawc($zdrawc);
		// 记录抽奖详情
		$money = $random;
		$detail = [
			'uid' => $zdrawc['uid'],
			'num' => $random,
			'money' => $money,
			'addtime' => time()
		];
		$this->zdrawc_model->insert_detail($detail);
		
		
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
		$total_money = $this->zdrawc_model->get_total_money(QUID)['money'];
		$total_money = $total_money ? $total_money : 0;
		$this->success($total_money);
	}
	
	/** 测试概率值 */
	public function test_random() {
		$total = 1000000;
		$arr = array();
		for($i = 0; $i < $total; $i++) {
			$random = $this->get_prize();
			$arr[$random] = isset($arr[$random]) ? ($arr[$random] + 1) : 1;
		}
		//sort($arr);
		foreach($arr as $k=>$v) {
			echo $k, ': ', ($v/$total*100) . '%', ' 个数： ', $v, '<br />';
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
					$return_number = $this->create_rand2(200, 999);
					if($return_number == '518' || $return_number == '520') {
						return $this->get_prize();
					} else {
						return $return_number;
					}
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
	
	/** 生成随机数 包含$begin和$end */
	private function create_rand2($begin, $end) {
		return mt_rand($begin, $end);
	}
}