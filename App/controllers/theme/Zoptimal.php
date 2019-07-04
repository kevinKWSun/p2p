<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Zoptimal extends Baseaccounts {
	public function __construct() {
		parent::__construct();
		$this->load->library('pagination');
		$this->load->model(array('theme/zoptimal_model','member/member_model'));
		$this->load->database();
		// 概率
		$this->rand_rate = array(
			'1' => '0.4',
			'2' => '0.4',
			'3' => '0.1',
			'4' => '0.07',
			'5' => '0.03'
		);
	}
	
	/** 开宝箱 */
	public function open_treasure() {
		if(QUID > 0) {
			// $message['column'] = 3;
			// $message['msg'] = '';
			// $this->success($message);
			$error = 0;
			$post = $this->input->post(null, true);
			// 用户奖品的档次
			$num = intval($post['num']);
			if($num < 1 || $num > 15) {
				$this->error('信息错误，请联系客服');
			}
			$num = intval($post['num']);
			// 获取概率值
			if($num == 15) {
				$rate = $this->get_prizes();
			} else {
				$rate = $this->get_prize();
			}
			// 获取用户第几档产品
			$zoptimal = $this->zoptimal_model->get_zoptimal_byuid(QUID);
			$zoptimal_bat = $zoptimal;
			if($zoptimal['round'] < $zoptimal['rounds']) {
				$zoptimal['num'] = 15;
			}
			if($num > $zoptimal['num']) {
				$this->error('未满足开启条件');
			}
			// 判断产品是否已领取
			$detail_num = $this->zoptimal_model->count_zoptimal_by_uid_round_num(QUID, $zoptimal['round'], $num);
			if($detail_num > 0) {
				$this->error('该奖品已领取');
			}
			// 根据档次和列数获取产品的ID
			$rand = $this->zoptimal_model->get_rand_by_num_column($num, $rate);
			//p($rand);
			// 获取产品信息
			$product = $this->zoptimal_model->get_product_byid($rand['pid']);
			$message['msg'] = '<div class = "pop-content">获得<span>'.$product['desc'].'</span><p>'.$product['name'].'</p></div>';
			$message['reflush'] = false;
			
			// 开启事务，处理数据
			$this->db->trans_begin();
			
			if(!empty($product)) {
				// 详情表数据
				$detail = array();
				$detail['uid'] = QUID;
				$detail['pid'] = $product['id'];
				$detail['round'] = $zoptimal['round'];
				$detail['num'] = $num;
				$detail['column'] = $rate;
				$detail['addtime'] = time();
				$this->zoptimal_model->insert_detail($detail);
				// 插入一条统计数据
				$number = array();
				$number = $this->zoptimal_model->get_number_by_uid_num_column(QUID, $num, $rate);
				if(empty($number)) {
					$number['uid'] = QUID;
					$number['pid'] = $product['id'];
					$number['total'] = 1;
					$number['column'] = $rate;
					$number['num'] = $num;
					$number['addtime'] = time();
				} else {
					$number['total'] += 1;
					$number['uptime'] = time();
				}
				// 将列返回
				$message['column'] = $rate;
				$this->zoptimal_model->update_number($number);
				// 查询是否需要更新主表，
				$detail_count = $this->zoptimal_model->count_detail_by_uid_round(QUID, $zoptimal['round']);
				if($detail_count >= 15) {
					$zoptimal_bat['round'] += 1;
					if($zoptimal_bat['round'] <= $zoptimal_bat['rounds']) {
						// 更新主表
						$this->zoptimal_model->update_zoptimal($zoptimal_bat);
						$message['reflush'] = true;
					}
				}
			}
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$this->error("操作失败，请联系客服");
			} else {
				if($error) {
					$this->db->trans_rollback();
					$this->error("操作失败，请联系客服.");
				} else {
					$this->db->trans_commit();
					$this->success($message);
				}
			}
			
			
			
		} else {
			$this->error('login');
		}
	}
	
	/** 返回奖品列 */
	private function get_prizes() {
		// 比率 1:1000
		$ratio = 1000;
		// 得到概率值
		$rand_num = $this->create_rand($ratio);
		$rate = 0;
		$rand_rate = array(
			'3' => '0.7',
			'4' => '0.3'
		);
		foreach($rand_rate as $k=>$v) {
			$rate += $v*$ratio;
			if($rand_num <= $rate) {
				return $k;
				//echo $k;break;
			}
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
				return $k;
			}
		}
	}
	
	/** 生成随机数 */
	private function create_rand($ratio) {
		return mt_rand(1, $ratio);
	}
	
	/** 主页逻辑 */
	public function index() {
		$data = array();
		if(QUID > 0) {
			// 获取第几轮第几档奖品
			$zoptimal = $this->zoptimal_model->get_zoptimal_byuid(QUID);
			// 查询是否需要更新到下一轮
			$detail_counts = $this->zoptimal_model->count_detail_by_uid_round(QUID, $zoptimal['round']);
			if($detail_counts >= 15) {
				$zoptimal['round'] += 1;
				if($zoptimal['round'] <= $zoptimal['rounds']) {
					// 更新主表
					$this->zoptimal_model->update_zoptimal($zoptimal);
				}
				$zoptimal = $this->zoptimal_model->get_zoptimal_byuid(QUID);
			}
			
			if($zoptimal['round'] < $zoptimal['rounds']) {
				$zoptimal['num'] = 15;
			}
			if(!empty($zoptimal)) {
				$data['zoptimal'] = $zoptimal;
			}
			// 本轮已获得已得到的奖品
			$detail = $this->zoptimal_model->get_detail_by_uid_round(QUID, $zoptimal['round']);
			if(!empty($detail)) {
				$detail_count = array();
				foreach($detail as $k=>$v) {
					array_push($detail_count, $v['num']);
				}
				$data['detail_count'] = $detail_count;
			}
			
			
			// 统计表中的数据
			$number = $this->zoptimal_model->get_number_byuid(QUID);
			if(!empty($number)) {
				$card_num = 0;
				$tmp_number = array();
				foreach($number as $k=>$v) {
					if($v['column'] == 5) {
						$card_num += 1;
					} else {
						$tmp_number[$v['num']][$v['column']] = $v['total'];
					}
					
				}
				$data['number'] = $tmp_number;
				$data['card_num'] = $card_num;
			}
			
		}
		
		$this->load->view('theme/zoptimal/index', $data);
	}
	
	/** 测试概率值 */
	private function test_rate() {
		$a = 0;
		$b = 0;
		$c = 0;
		$d = 0;
		$e = 0;
		for($i = 0; $i < 5000; $i++) {
			$rate = $this->get_prize();
			do {
				if($rate == 1) {
					$a += 1; break;
				}
				if($rate == 2) {
					$b += 1; break;
				}
				if($rate == 3) {
					$c += 1; break;
				}
				if($rate == 4) {
					$d += 1; break;
				}
				if($rate == 5) {
					$e += 1; break;
				}
			} while(FALSE);
		}
		echo '循环总数：', ($a + $b + $c + $d + $e), '<br />';
		echo '第一列产品数量：', $a, ' 概率值为：', $a/($a + $b + $c + $d + $e), '<br />';
		echo '第二列产品数量：', $b, ' 概率值为：', $b/($a + $b + $c + $d + $e), '<br />';
		echo '第三列产品数量：', $c, ' 概率值为：', $c/($a + $b + $c + $d + $e), '<br />';
		echo '第四列产品数量：', $d, ' 概率值为：', $d/($a + $b + $c + $d + $e), '<br />';
		echo '第五列产品数量：', $e, ' 概率值为：', $e/($a + $b + $c + $d + $e), '<br />';
	}
	
}