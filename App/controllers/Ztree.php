<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Ztree extends Baseaccounts {
	public function __construct() {
		echo '<h1>升级更新中，请稍后访问....</h1>';die;
		parent::__construct();
		$this->load->library('pagination');
		$this->load->model(array('member/member_model', 'cj/ztree_model'));
		$this->load->database();
		$this->tree_prize = array(
			'1' => 1, // '128元红包',
			'2' => 2, //'300元红包',
			'5' => 3, //'900元红包',
			'10' => 4, //'2100元红包',
			'15' => 5, //'3700元红包',
			'20' => 6 //'5800元红包'
		);
	}
	public function index() {
		$data = array();
		if(QUID > 0) {
			$data['ztree'] = $this->ztree_model->get_ztree_byuid(QUID);
			$data['ztree_detail'] = $this->ztree_model->get_detail_byuid20(QUID);
			$data['ztree_order_red_1'] = $this->ztree_model->get_red_ordernum_byuid(QUID, 1);
			$data['ztree_order_red_2'] = $this->ztree_model->get_red_ordernum_byuid(QUID, 2);
			$data['ztree_order_red_3'] = $this->ztree_model->get_red_ordernum_byuid(QUID, 3);
			$data['ztree_order_red_4'] = $this->ztree_model->get_red_ordernum_byuid(QUID, 4);
			$data['ztree_order_red_5'] = $this->ztree_model->get_red_ordernum_byuid(QUID, 5);
			$data['ztree_order_red_6'] = $this->ztree_model->get_red_ordernum_byuid(QUID, 6);
			$data['ztree_order_gold'] = $this->ztree_model->get_gold_ordernum_byuid(QUID);
			
			// 计算待收苹果数
			$ztree = $data['ztree'];
			$dh_total['red'] = 0;
			$dh_total['gold'] = 0;
			$ztree_data = $this->ztree_model->get_ztree_order_all(QUID);
			foreach($ztree_data as $k=>$v) {
				switch($v['type']) {
					case 1: 
						$dh_total['red'] += 1;
					break;
					case 2:
						$dh_total['red'] += 2;
					break;
					case 3:
						$dh_total['red'] += 5;
					break;
					case 4:
						$dh_total['red'] += 10;
					break;
					case 5:
						$dh_total['red'] += 15;
					break;
					case 6:
						$dh_total['red'] += 20;
					break;
					case 7:
						$dh_total['gold'] += 1;
				}
			}
			// 待收割苹果 数
			$dsg_total = ($ztree['num'] + $ztree['gold']) - ($dh_total['red'] + $dh_total['gold']) - ($ztree['nnum'] + $ztree['ngold']);
			$data['dsg_total'] = $dsg_total;
		}
		$this->load->view('cj/ztree', $data);
	}
	
	/** 异步请求 兑换苹果 */
	public function apple_exchange() {
		$post = $this->input->post(null, true);
		// 苹果数量
		$num = intval($post['num']);
		// 苹果类型
		$type = intval($post['type']);
		$this->db->trans_begin();
		
		$ztree = $this->ztree_model->get_ztree_byuid(QUID);
		if(!empty($ztree) && in_array($num, array(1, 2, 5, 10, 15, 20))) {
			if($type == 1) {
				// 金苹果
				if($ztree['ngold'] > 0) {
					$ztree['ngold'] -= 1;
				}
				$order_data = [
					'uid' => $ztree['uid'],
					'num' => 1,
					'addtime' => time(),
					'type' => 7
				];
			} else {
				// 红苹果
				if($ztree['nnum'] >= $post['num']) {
					$ztree['nnum'] -= $num;
				}
				$order_data = [
					'uid' => $ztree['uid'],
					'num' => $num,
					'addtime' => time(),
					'type' => $this->tree_prize[$num]
				];
			}
			$this->ztree_model->update_ztree($ztree);
			$this->ztree_model->insert_data($order_data);
		}
		
		
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$this->error('操作失败');
		} else {
			$this->db->trans_commit();
			$this->success('操作成功');
		}
	}
	
	/** 异步请求未使用苹果的数量 */
	public function apple_unused() {
		$ztree_detail = $this->ztree_model->get_detail_byuid20(QUID);
		if(!empty($ztree_detail)) {
			$this->success($ztree_detail);
		} else {
			$this->error(0);
		}
	}
	
	/** 收割苹果 */
	public function tree_sg() {
		$this->db->trans_begin();
		// 调取详情
		$ztree_detail = $this->ztree_model->get_detail_byuid20(QUID);
		if(!empty($ztree_detail)) {
			// 详情最后一条数据
			$last_detail = end($ztree_detail);
			// 判断是否有概率表,并生成对应的概率表
			$sort = ceil($last_detail['sort']/100);
			$rand_data = $this->ztree_model->get_rand_byuid(QUID, $sort);
			if(empty($rand_data)) {
				// 生成概率表一条数据
				$arr100 = range(($sort - 1)*100 + 1, $sort*100);
				$arr70 = range(($sort - 1)*100 + 1 + 10, $sort*100 - 30);
				$arr30 = range($sort*100 - 30 + 1, $sort*100);
				// 查询arr100已经存在的金苹果
				$rand_detail = $this->ztree_model->get_detail_byrand(QUID, $arr100);
				if(!empty($rand_detail)) {
					foreach($rand_detail as $k=>$v) {
						if(in_array($v['sort'], $arr100)) {
							foreach($arr100 as $key=>$value) {
								if($v['sort'] == $value) {
									unset($arr100[$key]);
								}
							}
							$arr100 = array_values($arr100);
						}
						if(in_array($v['sort'], $arr70)) {
							foreach($arr70 as $key=>$value) {
								if($v['sort'] == $value) {
									unset($arr70[$key]);
								}
							}
							$arr70 = array_values($arr70);
						}
						if(in_array($v['sort'], $arr30)) {
							foreach($arr30 as $key=>$value) {
								if($v['sort'] == $value) {
									unset($arr30[$key]);
								}
							}
							$arr30 = array_values($arr30);
						}
					}
				}
				$r1 = $this->rand_dom($arr70); // 第一个随机数
				foreach($arr70 as $key=>$value) {
					if($r1 == $value) {
						unset($arr70[$key]);
					}
				}
				$arr70 = array_values($arr70);
				$r2 = $this->rand_dom($arr70); // 第二个随机数
				$r3 = $this->rand_dom($arr30); // 第三个随机数
				// 要插入随机表的数据
				$rand_data_data = [
					'uid' => QUID,
					'randnum' => $r1 . ',' . $r2 . ',' . $r3,
					'sort' => $sort,
					'addtime' => time()
				];
				$this->ztree_model->insert_rand($rand_data_data);
			}
			
			$rand_data = $this->ztree_model->get_rand_byuid(QUID, $sort);
			if(ceil($ztree_detail[0]['sort']/100) != $sort) {
				$rand_data_before = $this->ztree_model->get_rand_byuid(QUID, $sort - 1);
			}
			// 金苹果出现位置
			$gold_position = isset($rand_data_before) ? $rand_data['randnum'] . ',' . $rand_data_before['randnum'] : $rand_data['randnum'];
			$gold_position = explode(',', $gold_position);

			// 循环处理抽中的苹果
			// 发放记录
			$red_apple_num = 0;
			$gold_apple_num = 0;
			$gold_apple_num_bat = 0;
			foreach($ztree_detail as $k=>$v) {
				$detail_deal_data = $v;
				if(in_array($v['sort'], $gold_position)) {
					$detail_deal_data['used'] = 1;
					$detail_deal_data['type'] = 1;
					$detail_deal_data['uptime'] = time();
					$gold_apple_num += 1;
				} else if($v['type'] == 1) {
					$detail_deal_data['used'] = 1;
					$detail_deal_data['uptime'] = time();
					$gold_apple_num_bat += 1;
				} else {
					$detail_deal_data['used'] = 1;
					$detail_deal_data['uptime'] = time();
					$red_apple_num += 1;
				}
				$this->ztree_model->update_detail($detail_deal_data);
			}
			// 处理总表ztree
			$ztree = $this->ztree_model->get_ztree_byuid(QUID);
			$ztree['nnum'] += $red_apple_num; // 已收割红苹果
			$ztree['ngold'] += ($gold_apple_num + $gold_apple_num_bat); // 已收割金苹果
			$ztree['num'] -= $gold_apple_num; // 红苹果总数对应转换成金苹果
			$ztree['gold'] += $gold_apple_num; // 已经转换金苹果的个数
			$ztree = $this->ztree_model->update_ztree($ztree);
		}
		
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$this->error('操作失败');
		} else {
			$this->db->trans_commit();
			$return_data['red_apple'] = isset($red_apple_num) ? $red_apple_num : 0;
			$return_data['gold_apple'] = 0;
			if(isset($gold_apple_num)) {
				$return_data['gold_apple'] += $gold_apple_num;
			}
			if(isset($gold_apple_num_bat)) {
				$return_data['gold_apple'] += $gold_apple_num_bat;
			}
			$this->success($return_data);
		}
	}
	
	/** 产生随机数 */
	public function rand_dom($arr) {
		if(!empty($arr)) {
			shuffle($arr);
			return $arr[0];
		} else {
			return 0;
		}
	}
	
}