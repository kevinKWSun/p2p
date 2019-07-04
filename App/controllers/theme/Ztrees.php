<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Ztrees extends Baseaccounts {
	public function __construct() {
		//echo '页面更新中，请一个小时后再访问';die;
		parent::__construct();
		$this->load->library('pagination');
		$this->load->model(array('theme/ztrees_model','member/member_model'));
		$this->tree_prize = array(
			'1' => 1, // '128元红包',
			'2' => 2, //'300元红包',
			'5' => 3, //'900元红包',
			'10' => 4, //'2100元红包',
			'15' => 5, //'3700元红包',
			'20' => 6, //'5800元红包'
			
			/*'21' => 7, //'8800元红包，1个金苹果'
			'22' => 8, //'200元红包, 1个梨'
			'23' => 9, // '220元红包, 1个橘子'
			'24' => 10, //'240元红包, 1个火龙果'
			'25' => 11, // '260元红包, 1串葡萄'
			'26' => 12, // '280元红包, 1个桃子'
			'27' => 13 // '388出借红包 红包1份'*/
		);
	}
	public function test() {
		echo rand(2,3);
	}
	public function index() {
		$data = array();
		if(QUID > 0) {
			// 查询已收割水果数量
			$data['red_num'] = $this->ztrees_model->get_detail_used_byuid(QUID, 0);
			$data['gold_num'] = $this->ztrees_model->get_detail_used_byuid(QUID, 1);
			$data['pear_num'] = $this->ztrees_model->get_detail_used_byuid(QUID, 8);
			$data['orange_num'] = $this->ztrees_model->get_detail_used_byuid(QUID, 9);
			$data['pitaya_num'] = $this->ztrees_model->get_detail_used_byuid(QUID, 10);
			$data['grape_num'] = $this->ztrees_model->get_detail_used_byuid(QUID, 11);
			$data['peach_num'] = $this->ztrees_model->get_detail_used_byuid(QUID, 12);
			$data['packe_num'] = $this->ztrees_model->get_detail_used_byuid(QUID, 13);
			
			$data['ztrees'] = $this->ztrees_model->get_ztrees_byuid(QUID);
			if(isset($data['ztrees']) && $data['ztrees']['oldtype'] == 0) {
				$this->ztrees_model->set_ztrees_ssort(QUID);
				$data['ztrees']['oldtype'] = 1;
				$data['ztrees']['old'] = $data['ztrees']['num'] + $data['ztrees']['gold'];
				$data['ztrees']['olduse'] = $data['ztrees']['num'] . '-' . $data['ztrees']['gold'] . '-' . $data['ztrees']['nnum'] . '-' . $data['ztrees']['ngold'];
				$this->ztrees_model->update_ztrees($data['ztrees']);
			} 
			
			// 生成对应的概率表
			$ztrees = $data['ztrees'];
			$ztrees_detail= $this->ztrees_model->get_detail_byuid20(QUID);
			// 判断是否有对应的概率表
			if(!empty($ztrees_detail) && !empty($ztrees)) {
				if($ztrees['oldtype'] != 0) {
					// 详情最后一条数据
					$last_detail = end($ztrees_detail);
					// 判断是否有概率表,并生成对应的概率表
					$sort = ceil($last_detail['ssort']/100);
					$rand_data = $this->ztrees_model->get_rand_byuid(QUID, $sort);
					if(empty($rand_data)) {
						// 生成概率表一条数据
						$arr100 = range(($sort - 1)*100 + 1, $sort*100);
						$arr70 = range(($sort - 1)*100 + 1 + 10, $sort*100 - 30);
						$arr30 = range($sort*100 - 30 + 1, $sort*100);
						// 查询arr100已经存在的金苹果
						$rand_detail = $this->ztrees_model->get_detail_byrand(QUID, $arr100);
						if(!empty($rand_detail)) {
							foreach($rand_detail as $k=>$v) {
								if(in_array($v['ssort'], $arr100)) {
									foreach($arr100 as $key=>$value) {
										if($v['ssort'] == $value) {
											unset($arr100[$key]);
										}
									}
									$arr100 = array_values($arr100);
								}
								if(in_array($v['ssort'], $arr70)) {
									foreach($arr70 as $key=>$value) {
										if($v['ssort'] == $value) {
											unset($arr70[$key]);
										}
									}
									$arr70 = array_values($arr70);
								}
								if(in_array($v['ssort'], $arr30)) {
									foreach($arr30 as $key=>$value) {
										if($v['ssort'] == $value) {
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
						// 生成其他水果的概率值
						$tmp_random = array($r1, $r2, $r3);
						foreach($tmp_random as $k=>$v) {
							$random_keys = array_keys($arr100, $v);
							if(!empty($random_keys)) {
								foreach($random_keys as $key) {
									unset($arr100[$key]);
								}
							}
						}
						$arr100 = array_values($arr100);
						// 如果数组不是空数组，随机打乱数组，并取出对应的值
						if(!empty($arr100)) {
							shuffle($arr100);
							$arr100 = array_values($arr100);
							$pear_rand = '';
							$orange_rand = '';
							$pitaya_rand = '';
							$grape_rand = '';
							$peach_rand = '';
							$packet_rand = '';
							foreach($arr100 as $k=>$v) {
								if($k < 14) {
									$pear_rand .= $v . ','; continue;
								}
								if($k < 21) {
									$orange_rand .= $v . ','; continue;
								}
								if($k < 27) {
									$pitaya_rand .= $v . ','; continue;
								}
								if($k < 32) {
									$grape_rand .= $v . ','; continue;
								}
								if($k < 34) {
									$peach_rand .= $v . ','; continue;
								}
								if($k < 52) {
									$packet_rand .= $v . ','; continue;
								}
							}
							$pear_rand = rtrim($pear_rand, ',');
							$orange_rand = rtrim($orange_rand, ',');
							$pitaya_rand = rtrim($pitaya_rand, ',');
							$grape_rand = rtrim($grape_rand, ',');
							$peach_rand = rtrim($peach_rand, ',');
							$packet_rand = rtrim($packet_rand, ',');
						}
						// 判断前一笔金苹果随机数个数
						if($sort > 1) {
							$rand_data_before_count = $this->ztrees_model->get_rand_byuid(QUID, $sort - 1);
							if(count(explode(',',$rand_data_before_count['randnum'])) == 2) {
								$randnum = 3;
							} else {
								$randnum = 2;
							}
						} else {
							$randnum = rand(2,3);
						}
						// 要插入随机表的数据
						$rand_data_data = [
							'uid' => QUID,
							'randnum' => ($randnum == '2') ? ($r1 . ',' . $r3) : ($r1 . ',' . $r2 . ',' . $r3),
							'sort' => $sort,
							'addtime' => time(),
							'pear' => $pear_rand,
							'orange' => $orange_rand,
							'pitaya' => $pitaya_rand,
							'grape' => $grape_rand,
							'peach' => $peach_rand,
							'packet' => $packet_rand
						];
						$this->ztrees_model->insert_rand($rand_data_data);
					} else {
						// 判断是否有其他水果的随机数
						if(empty($rand_data['pear'])) {
							// 生成概率表一条数据
							$arr100 = range(($sort - 1)*100 + 1, $sort*100);
							// 查询arr100已经存在的金苹果
							$rand_detail = $this->ztrees_model->get_detail_byrand(QUID, $arr100);
							if(!empty($rand_detail)) {
								foreach($rand_detail as $k=>$v) {
									if(in_array($v['ssort'], $arr100)) {
										foreach($arr100 as $key=>$value) {
											if($v['ssort'] == $value) {
												unset($arr100[$key]);
											}
										}
										$arr100 = array_values($arr100);
									}
								}
							}
							// 删除已经用于金苹果的随机数
							if(!empty($rand_data['randnum'])) {
								$tmp_random = explode(',', $rand_data['randnum']);
								if(isset($tmp_random[0])) {
									$r1 = $tmp_random[0];
								}
								if(isset($tmp_random[1])) {
									$r2 = $tmp_random[1];
								}
								if(isset($tmp_random[2])) {
									$r3 = $tmp_random[2];
								}
								foreach($tmp_random as $k=>$v) {
									$random_keys = array_keys($arr100, $v);
									if(!empty($random_keys)) {
										foreach($random_keys as $key) {
											unset($arr100[$key]);
										}
									}
								}
								$arr100 = array_values($arr100);
							}
							// 如果数组不是空数组，随机打乱数组，并取出对应的值
							if(!empty($arr100)) {
								shuffle($arr100);
								$arr100 = array_values($arr100);
								$pear_rand = '';
								$orange_rand = '';
								$pitaya_rand = '';
								$grape_rand = '';
								$peach_rand = '';
								$packet_rand = '';
								foreach($arr100 as $k=>$v) {
									if($k < 14) {
										$pear_rand .= $v . ','; continue;
									}
									if($k < 21) {
										$orange_rand .= $v . ','; continue;
									}
									if($k < 27) {
										$pitaya_rand .= $v . ','; continue;
									}
									if($k < 32) {
										$grape_rand .= $v . ','; continue;
									}
									if($k < 34) {
										$peach_rand .= $v . ','; continue;
									}
									if($k < 52) {
										$packet_rand .= $v . ','; continue;
									}
								}
								$pear_rand = rtrim($pear_rand, ',');
								$orange_rand = rtrim($orange_rand, ',');
								$pitaya_rand = rtrim($pitaya_rand, ',');
								$grape_rand = rtrim($grape_rand, ',');
								$peach_rand = rtrim($peach_rand, ',');
								$packet_rand = rtrim($packet_rand, ',');
							}
							// 要插入随机表的数据
							$rand_data_data = [
								'id' => $rand_data['id'],
								'uid' => QUID,
								'pear' => $pear_rand,
								'orange' => $orange_rand,
								'pitaya' => $pitaya_rand,
								'grape' => $grape_rand,
								'peach' => $peach_rand,
								'packet' => $packet_rand
							];
							if(isset($r1) && isset($r2) && isset($r3)) {
								$rand_data_data['randnum'] = (rand(2, 3) == 2) ? ($r1 . ',' .$r3) : ($r1 . ',' . $r2 . ',' . $r3);
							}
							$this->ztrees_model->update_rand($rand_data_data);
						}
					}
				}
			}
			
			// 处理一下，知道水果的具体属性
			$ztrees_detail= $this->ztrees_model->get_detail_byuid20(QUID);
			if(!empty($ztrees_detail)) {
				// 查询该用户的概率表
				$last_detail = end($ztrees_detail);
				// 判断是否有概率表,并生成对应的概率表
				$sort = ceil($last_detail['ssort']/100);
				$rand_data = $this->ztrees_model->get_rand_byuid(QUID, $sort);
				if(ceil($ztrees_detail[0]['ssort']/100) != $sort) {
					$rand_data_before = $this->ztrees_model->get_rand_byuid(QUID, $sort - 1);
				}
				// 金苹果出现位置
				$gold_position = isset($rand_data_before) ? $rand_data['randnum'] . ',' . $rand_data_before['randnum'] : $rand_data['randnum'];
				// 其他水果出现的位置
				$pear_posigion = isset($rand_data_before) ? $rand_data['pear'] . ',' . $rand_data_before['pear'] : $rand_data['pear'];
				$orange_posigion = isset($rand_data_before) ? $rand_data['orange'] . ',' . $rand_data_before['orange'] : $rand_data['orange'];
				$pitaya_posigion = isset($rand_data_before) ? $rand_data['pitaya'] . ',' . $rand_data_before['pitaya'] : $rand_data['pitaya'];
				$grape_posigion = isset($rand_data_before) ? $rand_data['grape'] . ',' . $rand_data_before['grape'] : $rand_data['grape'];
				$peach_posigion = isset($rand_data_before) ? $rand_data['peach'] . ',' . $rand_data_before['peach'] : $rand_data['peach'];
				$packet_posigion = isset($rand_data_before) ? $rand_data['packet'] . ',' . $rand_data_before['packet'] : $rand_data['packet'];
				
				$gold_position = explode(',', $gold_position);
				$pear_posigion = explode(',', $pear_posigion);
				$orange_posigion = explode(',', $orange_posigion);
				$pitaya_posigion = explode(',', $pitaya_posigion);
				$grape_posigion = explode(',', $grape_posigion);
				$peach_posigion = explode(',', $peach_posigion);
				$packet_posigion = explode(',', $packet_posigion);
				
				$red_apple_num = 0;
				$gold_apple_num = 0;
				$gold_apple_num_bat = 0;
				$pear_num = 0;
				$orange_num = 0;
				$pitaya_num = 0;
				$grape_num = 0;
				$peach_num = 0;
				$packet_num = 0;
				foreach($ztrees_detail as $k=>$v) {
					$detail_deal_data = $v;
					if(in_array($v['ssort'], $gold_position)) {
						$ztrees_detail[$k]['type'] = 1;
					} else if($v['type'] == 1) {
						//$ztrees_detail[$k]['type'] = 1;
						$ztrees_detail[$k]['type'] = 15;
					} else if(in_array($v['ssort'], $pear_posigion)) {
						$ztrees_detail[$k]['type'] = 8;
					} else if(in_array($v['ssort'], $orange_posigion)) {
						$ztrees_detail[$k]['type'] = 9;
					} else if(in_array($v['ssort'], $pitaya_posigion)) {
						$ztrees_detail[$k]['type'] = 10;
					} else if(in_array($v['ssort'], $grape_posigion)) {
						$ztrees_detail[$k]['type'] = 11;
					} else if(in_array($v['ssort'], $peach_posigion)) {
						$ztrees_detail[$k]['type'] = 12;
					} else if(in_array($v['ssort'], $packet_posigion)) {
						$ztrees_detail[$k]['type'] = 13;
					}
				}
				$data['ztrees_detail']  = $ztrees_detail;
			} else {
				$data['ztrees_detail'] = '';
			}
			
			
			
			$data['ztrees_order_red_1'] = $this->ztrees_model->get_red_ordernum_byuid(QUID, 1);
			$data['ztrees_order_red_2'] = $this->ztrees_model->get_red_ordernum_byuid(QUID, 2);
			$data['ztrees_order_red_3'] = $this->ztrees_model->get_red_ordernum_byuid(QUID, 3);
			$data['ztrees_order_red_4'] = $this->ztrees_model->get_red_ordernum_byuid(QUID, 4);
			$data['ztrees_order_red_5'] = $this->ztrees_model->get_red_ordernum_byuid(QUID, 5);
			$data['ztrees_order_red_6'] = $this->ztrees_model->get_red_ordernum_byuid(QUID, 6);
			$data['ztrees_order_gold'] = $this->ztrees_model->get_gold_ordernum_byuid(QUID);
		
			$data['ztrees_order_red_8'] = $this->ztrees_model->get_red_ordernum_byuid(QUID, 8);
			$data['ztrees_order_red_9'] = $this->ztrees_model->get_red_ordernum_byuid(QUID, 9);
			$data['ztrees_order_red_10'] = $this->ztrees_model->get_red_ordernum_byuid(QUID, 10);
			$data['ztrees_order_red_11'] = $this->ztrees_model->get_red_ordernum_byuid(QUID, 11);
			$data['ztrees_order_red_12'] = $this->ztrees_model->get_red_ordernum_byuid(QUID, 12);
			$data['ztrees_order_red_13'] = $this->ztrees_model->get_red_ordernum_byuid(QUID, 13);
			
			
			/** 订单数量 */
			$ztrees = $data['ztrees'];
			$dh_total['red'] = 0;
			$dh_total['gold'] = 0;
			$dh_total['other'] = 0;
			$ztrees_data = $this->ztrees_model->get_ztrees_order_all(QUID);
			foreach($ztrees_data as $k=>$v) {
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
					break;
					case 8:
					case 9:
					case 10:
					case 11:
					case 12:
					case 13:
						$dh_total['other'] += 1;
				}
			}
			// 待收割苹果 数
			$dsg_total = ($ztrees['num'] + $ztrees['gold'] + $ztrees['other']) - ($dh_total['red'] + $dh_total['gold'] + $dh_total['other']) - ($ztrees['nnum'] + $ztrees['ngold'] + $ztrees['nother']);
			$data['dsg_total'] = $dsg_total;
			$data['red_order_total'] = $dh_total['red'];
		}
		$this->load->view('theme/ztrees', $data);
	}
	/** 异步请求 兑换苹果 */
	public function apple_exchange() {
		//$this->success('操作成功'); die;
		$error = false;
		$error_msg = array(
			'1' => '可兑换数量不正确，请刷新页面重试',
		);
		$post = $this->input->post(null, true);
		// 苹果类型
		$type = intval($post['type']);
		if(empty($type)) {
			$this->error('信息错误，请联系客服');
		}
		$this->db->trans_begin();
		
		$ztrees = $this->ztrees_model->get_ztrees_byuid(QUID);
		if(!empty($ztrees) && in_array($type, array(1,2,3,4,5,6,7,8,9,10,11,12,13))) {
			$order_data = [
				'uid' => $ztrees['uid'],
				'num' => 1,
				'addtime' => time(),
				'type' => $type
			];
			switch($type) {
				case 1:
					$ztrees['nnum'] -= 1; break;
				case 2:
					$ztrees['nnum'] -= 2; break;
				case 3:
					$ztrees['nnum'] -= 5; break;
				case 4:
					$ztrees['nnum'] -= 10; break;
				case 5:
					$ztrees['nnum'] -= 15; break;
				case 6:
					$ztrees['nnum'] -= 20; break;
				case 7:
					$ztrees['ngold'] -= 1; break;
				case 8:
				case 9:
				case 10:
				case 11:
				case 12:
				case 13:
					$ztrees['nother'] -= 1; break;
			}
			do {
				// 判断对应的水果是否还有剩余
				if(in_array($type, array(1, 2, 3, 4, 5, 6))) {
					// 查询总数量
					$total = $this->ztrees_model->get_detail_used_byuid(QUID, 0);
					// 查询已兑换数量
					$total_used = 0;
					$ztrees_data = $this->ztrees_model->get_order_by_uid_type($uid, array(1, 2, 3, 4, 5, 6));
					foreach($ztrees_data as $k=>$v) {
						switch($v['type']) {
							case 1: 
								$total_used += 1;
							break;
							case 2:
								$total_used += 2;
							break;
							case 3:
								$total_used += 5;
							break;
							case 4:
								$total_used += 10;
							break;
							case 5:
								$total_used += 15;
							break;
							case 6:
								$total_used += 20;
							break;
						}
					}
					$sy_num = $total - $total_used;
					if($sy_num < 1) {
						$error = 1; break;
					}
				} else if($type == 7) {
					// 查询总数量
					$total = $this->ztrees_model->get_detail_used_byuid(QUID, 1);
					// 查询已兑换数量
					$total_used = 0;
					$ztrees_data = $this->ztrees_model->get_order_by_uid_type(QUID, array(7));
					foreach($ztrees_data as $k=>$v) {
						$total_used += 1;
					}
					$sy_num = $total - $total_used;
					if($sy_num < 1) {
						$error = 1; break;
					}
				} else {
					// 查询总数量
					$total = $this->ztrees_model->get_detail_used_byuid(QUID, $type);
					// 查询已兑换数量
					$total_used = 0;
					$ztrees_data = $this->ztrees_model->get_order_by_uid_type(QUID, array($type));
					foreach($ztrees_data as $k=>$v) {
						$total_used += 1;
					}
					$sy_num = $total - $total_used;
					if($sy_num < 1) {
						$error = 1; break;
					}
				}
				$this->ztrees_model->update_ztrees($ztrees);
				$this->ztrees_model->insert_data($order_data);
			} while(false);
		}
		
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$this->error('操作失败');
		} else {
			if($error) {
				$this->db->trans_rollback();
				$this->error($error_msg[$error]);
			}
			$this->db->trans_commit();
			$this->success('操作成功');
		}
	}
	
	/** 异步请求未使用苹果的数量 */
	public function apple_unused() {
		$ztrees_detail = $this->ztrees_model->get_detail_byuid20(QUID);
		if(!empty($ztrees_detail)) {
			// 查询该用户的概率表
			$last_detail = end($ztrees_detail);
			// 判断是否有概率表,并生成对应的概率表
			$sort = ceil($last_detail['ssort']/100);
			$rand_data = $this->ztrees_model->get_rand_byuid(QUID, $sort);
			if(ceil($ztrees_detail[0]['ssort']/100) != $sort) {
				$rand_data_before = $this->ztrees_model->get_rand_byuid(QUID, $sort - 1);
			}
			// 金苹果出现位置
			$gold_position = isset($rand_data_before) ? $rand_data['randnum'] . ',' . $rand_data_before['randnum'] : $rand_data['randnum'];
			// 其他水果出现的位置
			$pear_posigion = isset($rand_data_before) ? $rand_data['pear'] . ',' . $rand_data_before['pear'] : $rand_data['pear'];
			$orange_posigion = isset($rand_data_before) ? $rand_data['orange'] . ',' . $rand_data_before['orange'] : $rand_data['orange'];
			$pitaya_posigion = isset($rand_data_before) ? $rand_data['pitaya'] . ',' . $rand_data_before['pitaya'] : $rand_data['pitaya'];
			$grape_posigion = isset($rand_data_before) ? $rand_data['grape'] . ',' . $rand_data_before['grape'] : $rand_data['grape'];
			$peach_posigion = isset($rand_data_before) ? $rand_data['peach'] . ',' . $rand_data_before['peach'] : $rand_data['peach'];
			$packet_posigion = isset($rand_data_before) ? $rand_data['packet'] . ',' . $rand_data_before['packet'] : $rand_data['packet'];
			
			$gold_position = explode(',', $gold_position);
			$pear_posigion = explode(',', $pear_posigion);
			$orange_posigion = explode(',', $orange_posigion);
			$pitaya_posigion = explode(',', $pitaya_posigion);
			$grape_posigion = explode(',', $grape_posigion);
			$peach_posigion = explode(',', $peach_posigion);
			$packet_posigion = explode(',', $packet_posigion);
			
			$red_apple_num = 0;
			$gold_apple_num = 0;
			$gold_apple_num_bat = 0;
			$pear_num = 0;
			$orange_num = 0;
			$pitaya_num = 0;
			$grape_num = 0;
			$peach_num = 0;
			$packet_num = 0;
			$html = "";
			foreach($ztrees_detail as $k=>$v) {
				$detail_deal_data = $v;
				if(in_array($v['ssort'], $gold_position)) {
					$html .= "<li id='trees-fruit-li-".($k+1)."' class='trees-fruit-li-".($k+1)." apple-golden'></li>";
				} else if($v['type'] == 1) {
					//$html .= "<li id='trees-fruit-li-".($k+1)."' class='trees-fruit-li-".($k+1)." apple-golden'></li>";
					$html .= "<li id='trees-fruit-li-".($k+1)."' class='trees-fruit-li-".($k+1)." cute-gold'></li>";
				} else if(in_array($v['ssort'], $pear_posigion)) {
					$html .= "<li id='trees-fruit-li-".($k+1)."' class='trees-fruit-li-".($k+1)." pear'></li>";
				} else if(in_array($v['ssort'], $orange_posigion)) {
					$html .= "<li id='trees-fruit-li-".($k+1)."' class='trees-fruit-li-".($k+1)." orange'></li>";
				} else if(in_array($v['ssort'], $pitaya_posigion)) {
					$html .= "<li id='trees-fruit-li-".($k+1)."' class='trees-fruit-li-".($k+1)." pitaya'></li>";
				} else if(in_array($v['ssort'], $grape_posigion)) {
					$html .= "<li id='trees-fruit-li-".($k+1)."' class='trees-fruit-li-".($k+1)." grape'></li>";
				} else if(in_array($v['ssort'], $peach_posigion)) {
					$html .= "<li id='trees-fruit-li-".($k+1)."' class='trees-fruit-li-".($k+1)." peach'></li>";
				} else if(in_array($v['ssort'], $packet_posigion)) {
					$html .= "<li id='trees-fruit-li-".($k+1)."' class='trees-fruit-li-".($k+1)." red-packet'></li>";
				} else {
					$html .= "<li id='trees-fruit-li-".($k+1)."' class='trees-fruit-li-".($k+1)." apple-red'></li>";
				}
			}
			$this->success($html);
		} else {
			$this->error(0);
		}
	}
	
	/** 收割苹果 */
	public function tree_sg() {
		//$this->success();die;
		// $ztrees = $this->ztrees_model->get_ztrees_byuid(QUID);
		// p($ztrees);die;
		$this->db->trans_begin();
		// 调取详情
		$ztrees_detail = $this->ztrees_model->get_detail_byuid20(QUID);
		// 版本中的数据
		$ztrees = $this->ztrees_model->get_ztrees_byuid(QUID);
		
		if(!empty($ztrees_detail) && !empty($ztrees)) {
			if($ztrees['oldtype'] != 0) {
				// 详情最后一条数据
				$last_detail = end($ztrees_detail);
				// 判断是否有概率表,并生成对应的概率表
				$sort = ceil($last_detail['ssort']/100);
				$rand_data = $this->ztrees_model->get_rand_byuid(QUID, $sort);
				if(empty($rand_data)) {
					// 生成概率表一条数据
					$arr100 = range(($sort - 1)*100 + 1, $sort*100);
					$arr70 = range(($sort - 1)*100 + 1 + 10, $sort*100 - 30);
					$arr30 = range($sort*100 - 30 + 1, $sort*100);
					// 查询arr100已经存在的金苹果
					$rand_detail = $this->ztrees_model->get_detail_byrand(QUID, $arr100);
					if(!empty($rand_detail)) {
						foreach($rand_detail as $k=>$v) {
							if(in_array($v['ssort'], $arr100)) {
								foreach($arr100 as $key=>$value) {
									if($v['ssort'] == $value) {
										unset($arr100[$key]);
									}
								}
								$arr100 = array_values($arr100);
							}
							if(in_array($v['ssort'], $arr70)) {
								foreach($arr70 as $key=>$value) {
									if($v['ssort'] == $value) {
										unset($arr70[$key]);
									}
								}
								$arr70 = array_values($arr70);
							}
							if(in_array($v['ssort'], $arr30)) {
								foreach($arr30 as $key=>$value) {
									if($v['ssort'] == $value) {
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
					// 生成其他水果的概率值
					$tmp_random = array($r1, $r2, $r3);
					foreach($tmp_random as $k=>$v) {
						$random_keys = array_keys($arr100, $v);
						if(!empty($random_keys)) {
							foreach($random_keys as $key) {
								unset($arr100[$key]);
							}
						}
					}
					$arr100 = array_values($arr100);
					// 如果数组不是空数组，随机打乱数组，并取出对应的值
					if(!empty($arr100)) {
						shuffle($arr100);
						$arr100 = array_values($arr100);
						$pear_rand = '';
						$orange_rand = '';
						$pitaya_rand = '';
						$grape_rand = '';
						$peach_rand = '';
						$packet_rand = '';
						foreach($arr100 as $k=>$v) {
							if($k < 14) {
								$pear_rand .= $v . ','; continue;
							}
							if($k < 21) {
								$orange_rand .= $v . ','; continue;
							}
							if($k < 27) {
								$pitaya_rand .= $v . ','; continue;
							}
							if($k < 32) {
								$grape_rand .= $v . ','; continue;
							}
							if($k < 34) {
								$peach_rand .= $v . ','; continue;
							}
							if($k < 52) {
								$packet_rand .= $v . ','; continue;
							}
						}
						$pear_rand = rtrim($pear_rand, ',');
						$orange_rand = rtrim($orange_rand, ',');
						$pitaya_rand = rtrim($pitaya_rand, ',');
						$grape_rand = rtrim($grape_rand, ',');
						$peach_rand = rtrim($peach_rand, ',');
						$packet_rand = rtrim($packet_rand, ',');
					}
					// 判断前一笔金苹果随机数个数
					if($sort > 1) {
						$rand_data_before_count = $this->ztrees_model->get_rand_byuid(QUID, $sort - 1);
						if(count(explode(',',$rand_data_before_count['randnum'])) == 2) {
							$randnum = 3;
						} else {
							$randnum = 2;
						}
					} else {
						$randnum = rand(2,3);
					}
					// 要插入随机表的数据
					$rand_data_data = [
						'uid' => QUID,
						'randnum' => ($randnum == '2') ? ($r1 . ',' . $r3) : ($r1 . ',' . $r2 . ',' . $r3),
						'sort' => $sort,
						'addtime' => time(),
						'pear' => $pear_rand,
						'orange' => $orange_rand,
						'pitaya' => $pitaya_rand,
						'grape' => $grape_rand,
						'peach' => $peach_rand,
						'packet' => $packet_rand
					];
					$this->ztrees_model->insert_rand($rand_data_data);
				} else {
					// 判断是否有其他水果的随机数
					if(empty($rand_data['pear'])) {
						// 生成概率表一条数据
						$arr100 = range(($sort - 1)*100 + 1, $sort*100);
						// 查询arr100已经存在的金苹果
						$rand_detail = $this->ztrees_model->get_detail_byrand(QUID, $arr100);
						if(!empty($rand_detail)) {
							foreach($rand_detail as $k=>$v) {
								if(in_array($v['ssort'], $arr100)) {
									foreach($arr100 as $key=>$value) {
										if($v['ssort'] == $value) {
											unset($arr100[$key]);
										}
									}
									$arr100 = array_values($arr100);
								}
							}
						}
						// 删除已经用于金苹果的随机数
						if(!empty($rand_data['randnum'])) {
							$tmp_random = explode(',', $rand_data['randnum']);
							if(isset($tmp_random[0])) {
								$r1 = $tmp_random[0];
							}
							if(isset($tmp_random[1])) {
								$r2 = $tmp_random[1];
							}
							if(isset($tmp_random[2])) {
								$r3 = $tmp_random[2];
							}
							foreach($tmp_random as $k=>$v) {
								$random_keys = array_keys($arr100, $v);
								if(!empty($random_keys)) {
									foreach($random_keys as $key) {
										unset($arr100[$key]);
									}
								}
							}
							$arr100 = array_values($arr100);
						}
						// 如果数组不是空数组，随机打乱数组，并取出对应的值
						if(!empty($arr100)) {
							shuffle($arr100);
							$arr100 = array_values($arr100);
							$pear_rand = '';
							$orange_rand = '';
							$pitaya_rand = '';
							$grape_rand = '';
							$peach_rand = '';
							$packet_rand = '';
							foreach($arr100 as $k=>$v) {
								if($k < 14) {
									$pear_rand .= $v . ','; continue;
								}
								if($k < 21) {
									$orange_rand .= $v . ','; continue;
								}
								if($k < 27) {
									$pitaya_rand .= $v . ','; continue;
								}
								if($k < 32) {
									$grape_rand .= $v . ','; continue;
								}
								if($k < 34) {
									$peach_rand .= $v . ','; continue;
								}
								if($k < 52) {
									$packet_rand .= $v . ','; continue;
								}
							}
							$pear_rand = rtrim($pear_rand, ',');
							$orange_rand = rtrim($orange_rand, ',');
							$pitaya_rand = rtrim($pitaya_rand, ',');
							$grape_rand = rtrim($grape_rand, ',');
							$peach_rand = rtrim($peach_rand, ',');
							$packet_rand = rtrim($packet_rand, ',');
						}
						// 要插入随机表的数据
						$rand_data_data = [
							'id' => $rand_data['id'],
							'uid' => QUID,
							'pear' => $pear_rand,
							'orange' => $orange_rand,
							'pitaya' => $pitaya_rand,
							'grape' => $grape_rand,
							'peach' => $peach_rand,
							'packet' => $packet_rand
						];
						if(isset($r1) && isset($r2) && isset($r3)) {
							$rand_data_data['randnum'] = (rand(2, 3) == 2) ? ($r1 . ',' .$r3) : ($r1 . ',' . $r2 . ',' . $r3);
						}
						$this->ztrees_model->update_rand($rand_data_data);
					}
				}
				
				$rand_data = $this->ztrees_model->get_rand_byuid(QUID, $sort);
				if(ceil($ztrees_detail[0]['ssort']/100) != $sort) {
					$rand_data_before = $this->ztrees_model->get_rand_byuid(QUID, $sort - 1);
				}
				// 金苹果出现位置
				$gold_position = isset($rand_data_before) ? $rand_data['randnum'] . ',' . $rand_data_before['randnum'] : $rand_data['randnum'];
				// 其他水果出现的位置
				$pear_posigion = isset($rand_data_before) ? $rand_data['pear'] . ',' . $rand_data_before['pear'] : $rand_data['pear'];
				$orange_posigion = isset($rand_data_before) ? $rand_data['orange'] . ',' . $rand_data_before['orange'] : $rand_data['orange'];
				$pitaya_posigion = isset($rand_data_before) ? $rand_data['pitaya'] . ',' . $rand_data_before['pitaya'] : $rand_data['pitaya'];
				$grape_posigion = isset($rand_data_before) ? $rand_data['grape'] . ',' . $rand_data_before['grape'] : $rand_data['grape'];
				$peach_posigion = isset($rand_data_before) ? $rand_data['peach'] . ',' . $rand_data_before['peach'] : $rand_data['peach'];
				$packet_posigion = isset($rand_data_before) ? $rand_data['packet'] . ',' . $rand_data_before['packet'] : $rand_data['packet'];
				
				$gold_position = explode(',', $gold_position);
				$pear_posigion = explode(',', $pear_posigion);
				$orange_posigion = explode(',', $orange_posigion);
				$pitaya_posigion = explode(',', $pitaya_posigion);
				$grape_posigion = explode(',', $grape_posigion);
				$peach_posigion = explode(',', $peach_posigion);
				$packet_posigion = explode(',', $packet_posigion);

				// 循环处理抽中的苹果
				// 发放记录
				$red_apple_num = 0;
				$gold_apple_num = 0;
				$gold_apple_num_bat = 0;
				$pear_num = 0;
				$orange_num = 0;
				$pitaya_num = 0;
				$grape_num = 0;
				$peach_num = 0;
				$packet_num = 0;
				// 查询最大苹果的数量
				$apple_max_value = $this->ztrees_model->get_apple_max(QUID);
				if(!empty($apple_max_value)) {
					$apple_max_value = $apple_max_value['sort'] + 1;
				} else {
					$apple_max_value = 0;
				}
				foreach($ztrees_detail as $k=>$v) {
					$detail_deal_data = $v;
					if(in_array($v['ssort'], $gold_position)) {
						$detail_deal_data['used'] = 1;
						$detail_deal_data['type'] = 1;
						$detail_deal_data['sort'] = $apple_max_value;
						$detail_deal_data['uptime'] = time();
						$gold_apple_num += 1;
						$apple_max_value++;
					} else if($v['type'] == 1) {
						$detail_deal_data['used'] = 1;
						$detail_deal_data['sort'] = $apple_max_value;
						$detail_deal_data['uptime'] = time();
						$gold_apple_num_bat += 1;
						$apple_max_value++;
					} else if(in_array($v['ssort'], $pear_posigion)) {
						$detail_deal_data['used'] = 1;
						$detail_deal_data['type'] = 8;
						$detail_deal_data['sort'] = 0;
						$detail_deal_data['uptime'] = time();
						$pear_num += 1;
					} else if(in_array($v['ssort'], $orange_posigion)) {
						$detail_deal_data['used'] = 1;
						$detail_deal_data['type'] = 9;
						$detail_deal_data['sort'] = 0;
						$detail_deal_data['uptime'] = time();
						$orange_num += 1;
					} else if(in_array($v['ssort'], $pitaya_posigion)) {
						$detail_deal_data['used'] = 1;
						$detail_deal_data['type'] = 10;
						$detail_deal_data['sort'] = 0;
						$detail_deal_data['uptime'] = time();
						$pitaya_num += 1;
					} else if(in_array($v['ssort'], $grape_posigion)) {
						$detail_deal_data['used'] = 1;
						$detail_deal_data['type'] = 11;
						$detail_deal_data['sort'] = 0;
						$detail_deal_data['uptime'] = time();
						$grape_num += 1;
					} else if(in_array($v['ssort'], $peach_posigion)) {
						$detail_deal_data['used'] = 1;
						$detail_deal_data['type'] = 12;
						$detail_deal_data['sort'] = 0;
						$detail_deal_data['uptime'] = time();
						$peach_num += 1;
					} else if(in_array($v['ssort'], $packet_posigion)) {
						$detail_deal_data['used'] = 1;
						$detail_deal_data['type'] = 13;
						$detail_deal_data['sort'] = 0;
						$detail_deal_data['uptime'] = time();
						$packet_num += 1;
					} else {
						$detail_deal_data['used'] = 1;
						$detail_deal_data['sort'] = $apple_max_value;
						$detail_deal_data['uptime'] = time();
						$red_apple_num += 1;
						$apple_max_value++;
					}
					$this->ztrees_model->update_detail($detail_deal_data);
				}
				// 处理总表ztrees
				$ztrees = $this->ztrees_model->get_ztrees_byuid(QUID);
				$ztrees['nnum'] += $red_apple_num; // 已收割红苹果
				$ztrees['ngold'] += ($gold_apple_num + $gold_apple_num_bat); // 已收割金苹果
				$ztrees['num'] -= ($gold_apple_num + $pear_num + $orange_num + $pitaya_num + $grape_num + $peach_num + $packet_num); // 红苹果总数对应转换成金苹果
				$ztrees['gold'] += $gold_apple_num; // 已经转换金苹果的个数
				$ztrees['other'] += ($pear_num + $orange_num + $pitaya_num + $grape_num + $peach_num + $packet_num);
				$ztrees['nother'] += ($pear_num + $orange_num + $pitaya_num + $grape_num + $peach_num + $packet_num);
				$this->ztrees_model->update_ztrees($ztrees);
			}
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
			$return_data['pear'] = isset($pear_num) ? $pear_num : 0;
			$return_data['orange'] = isset($orange_num) ? $orange_num : 0;
			$return_data['pitaya'] = isset($pitaya_num) ? $pitaya_num : 0;
			$return_data['grape'] = isset($grape_num) ? $grape_num : 0;
			$return_data['peach'] = isset($peach_num) ? $peach_num : 0;
			$return_data['packet'] = isset($packet_num) ? $packet_num : 0;
			if($ztrees['oldtype'] == 0) {
				$this->error('操作失败，请联系客服人员');
			} else {
				$this->success($return_data);
			}
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