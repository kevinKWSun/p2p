<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class TestAdmin extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->library('pagination');
		$this->load->model(array('borrow/borrow_model', 'member/member_model', 'account/info_model', 'water/water_model'));
		$this->load->helper(array('url', 'common'));
		$this->load->database();
	}
	// 测试
	public function test() {
		echo 1111;die;
	}
	/** 测试发福袋次数 */
	public function send_zcash() {die;
		$id = $this->uri->segment(3);
		echo $id;
		$borrow = $this->borrow_model->get_borrow_byid($id);
		$this->auto_add_zcash($id, $borrow['borrow_duration']);

	}
	/** 发放抽奖次数 */
	private function auto_add_zcash($bid, $duration) {
		$investor = $this->borrow_model->get_investor_all($bid, 2);
		$timestamp = time();
		$this->load->model('cj/zcash_model');
		$this->db->trans_begin();
		foreach($investor as $k=>$v) {
			// 活动时间 3.1-2.28，发放现金红包
			if($v['add_time'] < strtotime('2019-03-01 00:00:00') || $v['add_time'] < strtotime('2019-03-31 23:59:59')) {
				continue;
			}
			$zcash = $this->zcash_model->get_by_uid($v['investor_uid']);
			if($duration == 4) {//97天
				$cash_total = 0;
				$investor_capital = $v['investor_capital'] + round($zcash['money97'], 2);
				if($investor_capital >= 20000) {
					$cash_total = intval($investor_capital/20000);
					$investor_capital -= intval($investor_capital/20000)*20000;
				}
			} else if($duration == 2) { // 33天
				$cash_total = 0;
				$investor_capital = $v['investor_capital'] + round($zcash['money33'], 2);
				if($investor_capital >= 50000) {
					$cash_total = intval($investor_capital/50000);
					$investor_capital -= intval($investor_capital/50000)*50000;
				}
			} else {
				continue;
			}
			//插入数据库
			$data = array();
			if(isset($cash_total) && $cash_total > 0) {
				$zcash = $this->zcash_model->get_by_uid($v['investor_uid']);
				$data['zcash'] = [
					'id' => empty($zcash) ? null : $zcash['id'],
					'uid' => $v['investor_uid'],
					'total'	=> intval($zcash['total']) + $cash_total,
				];
				if(empty($zcash)) {
					$data['zcash']['addtime'] = $timestamp;
				} else {
					$data['zcash']['uptime'] = $timestamp;
				}
				$day_duration = $this->config->item('borrow_duration')[$duration];
				$data['zcash']['money'.$day_duration] = $investor_capital;
				$data['record'] = [
					'uid' => $v['investor_uid'],
					'num' => $cash_total,
					'addtime' => time(),
					'bid'	=> $bid,
					'type'	=> 1
				];
				$this->zcash_model->modify_cash($data['zcash']);
				$this->zcash_model->add_record($data['record']);
			} else {
				$data['zcash'] = [
					'id' => empty($zcash) ? null : $zcash['id'],
					'uid' => $v['investor_uid'],
				];
				if(empty($zcash)) {
					$data['zcash']['addtime'] = $timestamp;
				} else {
					$data['zcash']['uptime'] = $timestamp;
				}
				$day_duration = $this->config->item('borrow_duration')[$duration];
				$data['zcash']['money'.$day_duration] = $investor_capital;
				$this->zcash_model->modify_cash($data['zcash']);
			}
		}
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			log_record('发放抽奖次数失败', '/zcard.log', 'bid-' . $bid);
		} else {
			$this->db->trans_commit();
		}
	}
	
	/** 将以前用户首投补充进去 */
	public function user_first() {die;
		$result = $this->db->select('investor_uid')->get('borrow_investor')->result_array();
		$tmp = [];
		foreach($result as $v) {
			if(!in_array($v['investor_uid'], $tmp)) {
				array_push($tmp, $v['investor_uid']);
			}
		}
		
		//p($tmp);
		foreach($tmp as $v) {
			$row_tmp = [];
			$this->db->order_by('id asc');
			$row_tmp = $this->db->where('investor_uid', $v)->get('borrow_investor')->row_array();
			$row_tmp['first'] = 1;
			$this->db->where('id', $row_tmp['id'])->update('borrow_investor', $row_tmp);
		}
	}
	/** 放款数据显示 */
	public function loan_data() {
		$aa = 'a:1:{s:5:"value";a:2:{s:5:"signs";s:4:"true";s:4:"body";a:2:{s:4:"body";a:1:{s:4:"list";a:1:{i:0;a:4:{s:7:"bizFlow";s:21:"004201902101002233551";s:11:"payerAcctNo";s:13:"1013000843101";s:10:"resultCode";s:6:"000000";s:15:"subjectAuthCode";s:21:"011201902101000407921";}}}s:4:"head";a:10:{s:7:"bizFlow";s:21:"004201902101002233548";s:10:"merOrderNo";s:20:"19021010028591169119";s:10:"merchantNo";s:15:"131010000011013";s:8:"respCode";s:6:"000000";s:8:"respDesc";s:6:"成功";s:9:"tradeCode";s:6:"CG1021";s:9:"tradeDate";s:8:"20190210";s:9:"tradeTime";s:6:"100223";s:9:"tradeType";s:2:"01";s:7:"version";s:5:"1.0.0";}}}}';
		p(unserialize($aa));
		
		$bb = '{"body":{"list":[{"bizFlow":"007201902101002483524","payerAcctNo":"1013000824001","resultCode":"000000","subjectAuthCode":"011201902101000457932"}]},"head":{"bizFlow":"007201902101002483521","merOrderNo":"19021010020415941467","merchantNo":"131010000011013","respCode":"000000","respDesc":"成功","tradeCode":"CG1021","tradeDate":"20190210","tradeTime":"100248","tradeType":"01","version":"1.0.0"}}';
		$bb = json_decode($bb, true);
		$data = array();
		$data['value']['signs'] = 'true';
		$data['value']['body'] = $bb;
		p($data);
		echo serialize($data);
		p($bb);
		
	}
	/** 写一条water数据 */
	public function insert_water() {
	}
	
	/** 插入还款的数据 */
	public function insert_investor_detail() {die;
		$tmp_body = '';
		$this->load->model(array('water/water_model'));
		$respCode = $tmp_body['head']['respCode'];
		
		if($respCode === '000000') {
			$merOrderNo = $tmp_body['head']['merOrderNo'];
			$water = $this->water_model->get_water_byorder($merOrderNo);
			$id_detail = $water['bid'];
			
			$detail = $this->borrow_model->get_detail_one($id_detail);
			//判断是不是最后一期的还款
			$is_total = $detail['sort_order'] === $detail['total'] ? true : false;
			$id = $detail['borrow_id'];
			//调取标信息
			$borrow = $this->borrow_model->get_borrow_byid($id);
			//调取借款人信息
			$meminfo = $this->member_model->get_member_info_byuid($borrow['borrow_uid']);
			//调取投资人的账户信息
			$meminfos = $this->member_model->get_member_info_byuid($detail['investor_uid']);
			//判断是不是最后一笔还款
			//>0,不是最后一笔还款，否则这是最后一笔还款
			$res_detail = $this->borrow_model->is_last_detail($id_detail, $id);
			//$res_detail = 0;
			$is_last = $res_detail > 0 ? false : true;
			//操作金额
			$money = $is_total ? ($detail['capital'] + $detail['interest']) : $detail['interest'];
			//时间戳
			$timestamp = time();
			
			//修改主表状态
			$data = array();
			$data['borrow'] = array(
				'id'	=> $id,
				'borrow_status' => $is_last ? 5 : 4,
				'has_pay'		=> $borrow['has_pay'] + $money,
			);
			//第二张表
			//调取所有投资人信息
			$investor = $this->borrow_model->get_investor_one($detail['invest_id']);
			$data['investor'] = array(
				'id' => $investor['id'],
				'receive_capital' => $is_total ? ($investor['receive_capital'] + $detail['capital']) : $investor['receive_capital'],
				'receive_interest' => $investor['receive_interest'] + $detail['interest']
			);
			//第三张表数据 【一次性到期还本息， 按月付息到期还本】
			$data['detail'] = array(
				'id'				=> $id_detail,
				'repayment_time' 	=> $timestamp,
				'receive_capital' 	=> $is_total ? $detail['capital'] : 0,
				'receive_interest' 	=> $detail['interest'],
				'adminid' 			=> UID
			);
			//如果是最后一笔
			if($is_last) {
				$data['investor_status'] = array(
					'borrow_id' => $id,
					'status'	=> 5
				);
				$data['detail_status'] = array(
					'borrow_id' => $id,
					'status' => 5
				);
			}
			//处理金额
			//投资人
			$memoney = $this->info_model->get_money($investor['investor_uid']);
			$data['money'][] = array(
				'uid'			=> $investor['investor_uid'],
				'account_money'	=> $memoney['account_money'] + $money,
				'money_freeze' => $memoney['money_freeze'],
				'money_collect' => $is_total ? ($memoney['money_collect'] - $detail['capital']) : $memoney['money_collect']
			);
			$data['log'][] = array(
				'uid' => $investor['investor_uid'],
				'type' => 9,//收款
				'affect_money' => $water['money'],
				'account_money' => $memoney['account_money'] + $money,//可用
				'collect_money' => $is_total ? ($memoney['money_collect'] - $detail['capital']) : $memoney['money_collect'],//待收
				'freeze_money' => $memoney['money_freeze'],//解冻
				'info' => $is_total ? '收取本金' . $detail['capital'] . '元，利息' . $detail['interest'] . '元' : '收取利息'. $detail['interest'] . '元',
				'add_time' => $timestamp,
				//'add_ip' => $this->input->ip_address(),
				'bid' 	=> $id,
				'nid'	=> $merOrderNo
			);
			//借款人
			$memoney_borrow = $this->info_model->get_money($borrow['borrow_uid']);
			//p($memoney_borrow);p($water);die;
			$data['money'][] = array(
				'uid'			=> $borrow['borrow_uid'],
				'account_money' => $memoney_borrow['account_money'] - $water['money'],
				'money_freeze' => $memoney_borrow['money_freeze'],
				'money_collect' => $memoney_borrow['money_collect']
			);
			
			if($water['money'] - $money > 0.001) {
				$info = ',平台服务费' . ($water['money'] - $money) . '元';
			} else {
				$info = '';
			}
			$data['log'][] = array(
				'uid' => $borrow['borrow_uid'],
				'type' => 8,//还款
				'affect_money' => $water['money'],
				'account_money' => $memoney_borrow['account_money'] - $water['money'],//可用
				'collect_money' => $memoney_borrow['money_collect'],//待收
				'freeze_money' => $memoney_borrow['money_freeze'],//冻结
				'info' => $is_total ? '还款本金' . $detail['capital'] . '元，利息' . $detail['interest'] . '元'.$info : '还款利息'. $detail['interest'] . '元'.$info,
				'add_time' => $timestamp,
				//'add_ip' => $this->input->ip_address(),
				'bid' => $id,
				'nid' => $merOrderNo
			);
			$data['water']['status'] = 1;
			$data['water']['merOrderNo'] = $merOrderNo;
			$this->borrow_model->repayment($data);
			
			//p($data);
			//标的结束
			if($is_last) {
				$params['subjectNo'] = $borrow['subjectNo'];
				$head = head($params, 'CG1032', 'over');
				water($borrow['borrow_uid'], $head['merOrderNo'], 'CG1032', $borrow['id']);
				unset($head['callbackUrl'], $head['registerPhone'], $head['responsePath'], $head['url']);
				$data = $head;
				$data = json_encode($data);
				$url = $this->config->item('Interface').'1032';
				$str = post_curl_test($url, $data);
				$this->load->model(array('paytest/paytest_model'));
				$res_water = $this->paytest_model->excute($str);
				if($res_water['head']['respCode'] === '000000') {
					$data = array();
					$data['water']['status'] = 1;
					//$data['water']['merOrderNo'] = $res_water['head']['merOrderNo'];
					$this->load->model(array('water/water_model'));
					$this->water_model->edit_water($data['water'], $res_water['head']['merOrderNo']);
				}
			}
			
		}
	}
	
	/** 插入投标失败的数据 */
	public function toub() {
		$str = "merchantNo=131010000011013&merOrderNo=19021010465907707847&jsonEnc=d128f395f83024476d5c245cabb9b732247fb8ed2424f0d30a5354998894467cda03ce0493c2e32bbe94373ad7983f96df3af9f6b884995246caea7812fa50298e14d87512faa2139c25848cd6eeb96bdf52fbcf1bf4f78621fe9544af652057f52583205d516389c4f383f240c3ba962d1128b3d299fba21c8d642492a87af1f152c77dc1f412dc19a140c2b69d669c7e1548fecbff300b22255fd9fae9ac6021e5cf90b92c4c3930f7bd10087e4e109001da792a6a1f0bb18aad7de3f0a6837243ebd776184aab6bedeede045a182aa499fe4eb4fc86be2cbcd1a4e46a0d5712de34ad89237aec4b8108250f757fa4f6e8413438ef6444a07c53afb8229936b626bc0d781e84943e27dce62b098bdb728e5bd06e90ece52e5753c2c700824d815d520ef825590a4a8c28952bb7680f1c7b27f98ba708412731c08bd4507c5881d3842586974f27&keyEnc=4228d8ba4a10aa1a10cb6e159f19b7f98d45ab771709d0b054301b24ed0096d29a5a9d628b9f309546af9bb5e456da04284933032e9713cbd080e831052a5d95535aa921b0df221dfa760d11afd8213b28b6f0d186372bca9ba6875fedf2d10d157b0d7c148117c15b46e0e906994ecc8454be09e265da9911df471e1ed267fc93df4c6699243caf6625feb6965da1c678a6ab66216ea1c1ba1b6dbbad9d5c6c31d9aec23c452a60bde08b50352a83f525f639a04f002819ae15bc059120beb411583e17b0c1d03794c9bd6fe7e38f0ddc1141b574aa37969911b5558f33a8a54178b763fc8a86707fbb37d05285c4de7a7a3dd658db67b163ebcc6802f8b56c&sign=6abffae5d460fcce3de3d3d3e636b97fa24d66125afdc90749e0f0fe902ebea03c4019103f8451c881f04cc64cf6f1a3f2c9bffe25f7b1699e0b1fd433d3fb04e702ff535deb463c9fcd39d58696960f76e4e17390a2466d3d6565d57e3ce20955ff83a17ed2348470b6aa957832ca1b7ac10ed436a1f43003c04553c0f9e1acee465c8ea43f451bade1fa218ba564e6bc0bf0a056778e9e14ab679d9f8785d8e4f6c4de8c37e257232d80278087e60740f7739224e846a6fd250fa8ca88c130a95c035067b66bfd9322ebd3171b738457d16dddd8b95bfa3fd0f4d29da2a436172b6b66b253da8f874ba9a2dedf76fce4c70ae2a2e9644e1c107ee2a6ca440c";
		
		// $this->load->model(array('paytest/paytest_model'));
		// $this->paytest_model->excute($str); 
	}
	
	/** 插入发放红包失败uid */
	public function set_packet_error_uid() {/* 
		$error = $this->db->where('uid', 0)->get('packet_error')->result_array();
		foreach($error as $k=>$v) {
			if($v['rid'] > 0) {
				$packet = $this->db->where('id', $v['rid'])->get('packet')->row_array();
				$v['uid'] = $packet['uid'];
				$this->db->where('id', $v['id'])->update('packet_error', $v);
			}
		} */
	}
	/** 删除红包撤销重复数据*/
	public function del_revoke() {
		/* $packets = $this->db->where('addtime', 1547102992)->get('packet_revoke')->result_array();
		foreach($packets as $k=>$v) {
			echo $v['pid'], ',';
		} */
		/* $count_revoke = $this->db->count_all_results('packet_revoke');
		$count_packet = $this->db->where('remark', 1)->count_all_results('packet');
		echo $count_revoke, ',' , $count_packet; */
		
		
		
		/* $del_packets = $this->db->where('addtime', 1547102992)->get('packet_revoke')->result_array();
		foreach($del_packets as $k=>$v) {
			$packet = $this->db->where(array('addtime <>'=>1547102992, 'pid'=>$v['pid']))->get('packet_revoke')->row_array();
			if(!empty($packet)) {
				echo $v['id'], ',';
				$this->db->where('id', $v['id'])->delete('packet_revoke');
			}
		} */
		
	}
	/** 红包撤销 数据 */
	/* public function packet_revoke() {
		$packets = $this->db->where('remark', 1)->get('packet')->result_array();
		//$this->load->model('packet/packet_model');
		foreach($packets as $k=>$v) {
			$data = array();
			$data['pid'] = $v['id'];
			$data['adminid'] = 10;
			$data['addtime'] = time();
			$this->db->insert('packet_revoke', $data);
		}
	} */
	/** 循环插入待收金额 */
	public function test_collect() {die;
		$bid = 294;
		/* $investor = $this->db->where(array('borrow_id'=>$bid))->get('borrow_investor')->result_array();
		//p($investor);
		foreach($investor as $k=>$v) {
			$mem_money = $this->db->where(array('uid'=>$v['investor_uid']))->get('members_money')->row_array();
			//echo $v['investor_uid'],',';
			$mem_money['money_collect'] = $mem_money['money_collect'] + $v['investor_capital'];
			$this->db->where(array('uid'=>$v['investor_uid']))->update('members_money', $mem_money);
			$data['log'] = array(
				'uid' => $v['investor_uid'],
				'type' => 13,//补发待收
				'affect_money' => $v['investor_capital'],
				'account_money' => $mem_money['account_money'],//可用
				'collect_money' => $mem_money['money_collect'] + $v['investor_capital'],//待收
				'freeze_money' => $mem_money['money_freeze'],//冻结
				'info' => '补发' . $v['investor_capital'] . '元待收金额, ',
				'add_time' => time(),
				//'add_ip' => $this->input->ip_address(),
				'bid' => 0,
				'nid'	=> 0
			);
			$this->db->insert('members_moneylog', $data['log']);
		} */
	}
	
	public function clear_zcard() {
		die;
		/* $zcard = $this->db->select('id')->get('zcard')->result_array();
		foreach($zcard as $k=>$v) {
			$sql = "UPDATE xm_zcard SET card1=0,card2=0,card3=0,card4=0,card5=0,card6=0,card7=0,card8=0,card9=0,card10=0 WHERE id=" . $v['id'];
			$this->db->simple_query($sql);
		} */
	}
	/** 时间戳 */
	public function get_timestamp() {
		/* echo strtotime('2019-01-01 00:00:00');
		echo date('Y-m-d H:i:s', 1546272000); */
	}
	/** 写个方法， 循环发卡片 */
	public function send_card() {die;
		
		$id = $this->uri->segment(3);
		echo $id;
		$borrow = $this->borrow_model->get_borrow_byid($id);
		$this->load->database();
		$this->db->trans_begin();
		$this->auto_add_card($id, $borrow['borrow_duration']);
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			log_record('发放卡片失败', '/zcard.log', 'bid-' . $id);
		} else {
			$this->db->trans_commit();
		}
		/*
		for($i=0; $i<200; $i++) {
			$this->load->database();
			$this->db->trans_begin();
			$borrow = $this->borrow_model->get_borrow_byid($i);
			$this->auto_add_card($i, $borrow['borrow_duration']);
			if(empty($borrow)) {
				continue;
			}
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				echo 3333;
			} else {
				$this->db->trans_commit();
			}
		}*/
	}
	/** 随机发放 卡片 */
	public function auto_add_card($bid, $duration) {
		// $bid = 1;
		// $duration = 3;
		die;
		
		
		$investor = $this->borrow_model->get_investor_all($bid, 2);
		$timestamp = time();
		$this->load->model('zcard/zcard_model');
		foreach($investor as $k=>$v) {
			$card_total = 0;
			//如果投资日期不是在2019年1月1号以后，不发卡片
			if($v['add_time'] < strtotime('2019-01-01 00:00:00')) {
				continue;
			}
			$zcard = $this->zcard_model->get_by_uid($v['investor_uid']);
			if($duration == 2) {//33天
				$investor_capital = $v['investor_capital'] + round($zcard['money33'], 2);
				if($investor_capital >= 30000) {
					$card_total = intval($investor_capital/30000);
					$investor_capital -= intval($investor_capital/30000)*30000;
					
				}
			} else if($duration == 3) {//65天
				$investor_capital = $v['investor_capital'] + round($zcard['money65'], 2);
				if($investor_capital >= 20000) {
					$card_total = intval($investor_capital/20000);
					$investor_capital -= intval($investor_capital/20000)*20000;
					
				}
			} else if($duration == 4) {//97天
				$investor_capital = $v['investor_capital'] + round($zcard['money97'], 2);
				if($investor_capital >= 10000) {
					$card_total = intval($investor_capital/10000);
					$investor_capital -= intval($investor_capital/10000)*10000;
					
				}
			}
			
			//插入数据库
			$data = array();
			if($card_total > 0) {
				for($i=0; $i < $card_total; $i++) {
					$zcard = $this->zcard_model->get_by_uid($v['investor_uid']);
					$value = $this->get_random();
					$data['detail'] = [
						'uid' => $v['investor_uid'],
						'type' => $duration,
						'num' => $value,
						'addtime'=> $timestamp
					];
					$data['card'] = [
						'id' => empty($zcard) ? null : $zcard['id'],
						'uid' => $v['investor_uid'],
						'card'.$value => intval($zcard['card'.$value]) + 1,
						'total'	=> intval($zcard['total']) + 1,
					];
					if(empty($zcard)) {
						$data['card']['addtime'] = $timestamp;
					} else {
						$data['card']['uptime'] = $timestamp;
					}
					$day_duration = $this->config->item('borrow_duration')[$duration];
					$data['card']['money'. $day_duration] = $investor_capital;
					$data['card']['time'. $day_duration] = intval($zcard['time'.$day_duration]) + 1;
					$this->zcard_model->insert_detail($data['detail']);
					$this->zcard_model->modify_card($data['card']);
				}
			} else {
				$data['card'] = [
					'id' => empty($zcard) ? null : $zcard['id'],
					'uid' => $v['investor_uid'],
				];
				if(empty($zcard)) {
					$data['card']['addtime'] = $timestamp;
				} else {
					$data['card']['uptime'] = $timestamp;
				}
				$day_duration = $this->config->item('borrow_duration')[$duration];
				$data['card']['money'. $day_duration] = $investor_capital;
				$this->zcard_model->modify_card($data['card']);
			}
		}
	}
	private function get_random() {
		$random = $this->random10();
		//设置一个权重，决定2,9出现的位置在600次中，600次是一个周期
		$weight2 = 0.3;
		$weight9 = 0.7;
		$this->load->model('zcard/zcard_model');
		//已发卡片数量
		$total = $this->zcard_model->get_total();
		//卡片限制条件决定2,9出现的位置在600次中，600次是一个周期
		//卡片2发放次数
		$card2_total = $this->zcard_model->get_by_num(2);
		//卡片9发放的次数
		$card9_total = $this->zcard_model->get_by_num(9);
		
		$cyc = 600;//周期是600
		//应该出现次数
		$cyc_num = ceil($total/$cyc);
		$cyc_num = $cyc_num > 10 ? 10 : $cyc_num;
		
		//判断是否应该出2或9
		if($random == 2) {
			if(($card2_total < $cyc_num) && ((($total - ($cyc_num-1)*$cyc)/$cyc) > $weight2)) {
				return $random;
			} else {
				$random = $this->random8();
			}
		}
		// && ((($total - ($cyc_num-1)*$cyc)/$cyc) > $weight9)
		if($random == 9) {
			if(($card9_total < $cyc_num) && ((($total - ($cyc_num-1)*$cyc)/$cyc) > $weight9)) {
				return $random;
			} else {
				$random = $this->random8();
			}
		}
		return $random;
	}
	public function random10() {
		$arr = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
		$digit = rand(0, 9);
		return $arr[$digit];
	}
	public function random8() {
		$arr = [1, 3, 4, 5, 6, 7, 8, 10];
		$digit = rand(0, 7);
		return $arr[$digit];
	}
	
	
	
	
	
	public function test_doubles() {
		// $this->load->model('times/times_model');
		// $this->times_model->add_member_doubles(array('uid' => 1, 'doubles' => 1));
	}
	/** 增加双倍抽奖次数 */
	//public function auto_add_doubles($bid, $duration) {
	/*public function auto_add_doubles() {
		$bid = 2;
		$duration = 3;
		
		$investor = $this->borrow_model->get_investor_all($bid, 2);
		$timestamp = time();
		$this->load->model('times/times_model');
		foreach($investor as $k=>$v) {
			$times = $this->times_model->get_times_byuid($v['investor_uid'], $duration);
			//没有数据
			if(empty($times)) {
				//抽奖次数
				$current_times = 0;
				//累积金额
				$investor_capital = $v['investor_capital'];
				if($duration == 2) {//33天
					if($investor_capital >= 50000) {
						$investor_capital = $investor_capital - intval($investor_capital/50000)*50000;
						$current_times = intval($v['investor_capital']/50000);
					}
				} else if($duration == 3) {//65天
					if($investor_capital >= 30000) {
						$investor_capital = $investor_capital - intval($investor_capital/30000)*30000;
						$current_times = intval($v['investor_capital']/30000);
					}
				} else if($duration == 4) {//97天
					if($investor_capital >= 15000) {
						$investor_capital = $investor_capital - intval($investor_capital/15000)*15000;
						$current_times = intval($v['investor_capital']/15000);
					}
				}
				if($current_times > 0) {
					if($timestamp > 1545926400 && $timestamp < 1546271999) {
						//$this->times_model->add_member_doubles(array('uid' => $v['investor_uid'], 'doubles' => $current_times));
					} else {
						//$this->times_model->add_member_times(array('uid' => $v['investor_uid'], 'times' => $current_times));
					}
					
				}
				if($v['investor_uid'] == 27) {
					p(array('uid' => $v['investor_uid'], 'doubles' => $current_times));
					p(array('uid' => $v['investor_uid'], 'times' => $current_times));
				}
				
				$data['times'] = [
					'uid'		=> $v['investor_uid'],
					'type'		=> $duration,
					'money'		=> $investor_capital,
					'times'		=> $current_times,
					'addtime'	=> $timestamp
				];
				if($timestamp > 1545926400 && $timestamp < 1546271999) {
					$data['times']['doubles'] = $data['times']['times'];
					unset($data['times']['times']);
				}
				if($v['investor_uid'] == 27) {
					p($data['times']);
				}
				//$this->times_model->add_times($data['times']);
			} else {//已有数据
				//抽奖次数
				if($timestamp > 1545926400 && $timestamp < 1546271999) {
					$current_times = $times['doubles'];
				} else {
					$current_times = $times['times'];
				}
				
				//累积金额
				$investor_capital = $times['money'] + $v['investor_capital'];
				if($duration == 2) {//33天
					if($investor_capital >= 50000) {
						$investor_capital = $investor_capital - intval($investor_capital/50000)*50000;
						$current_times = $current_times + intval(($times['money'] + $v['investor_capital'])/50000);
					}
				} else if($duration == 3) {//65天
					if($investor_capital >= 30000) {
						$investor_capital = $investor_capital - intval($investor_capital/30000)*30000;
						$current_times = $current_times + intval(($times['money'] + $v['investor_capital'])/30000);
					}
				} else if($duration == 4) {//97天
					if($investor_capital >= 15000) {
						$investor_capital = $investor_capital - intval($investor_capital/15000)*15000;
						$current_times = $current_times + intval(($times['money'] + $v['investor_capital'])/15000);
					}
				}
				if($timestamp > 1545926400 && $timestamp < 1546271999) {
					if($current_times - $times['doubles'] > 0) {
						//$this->times_model->add_member_doubles(array('uid' => $v['investor_uid'], 'doubles' => intval($current_times - $times['doubles'])));
					}
				} else {
					if(($current_times - $times['times']) > 0) {
						//$this->times_model->add_member_times(array('uid' => $v['investor_uid'], 'times' => intval($current_times - $times['times'])));
					} 
				}
				if($v['investor_uid'] == 27) {
					p(array('uid' => $v['investor_uid'], 'doubles' => intval($current_times - $times['doubles'])));
					p(array('uid' => $v['investor_uid'], 'times' => intval($current_times - $times['times'])));
				}
				
				$data['times1'] = [
					'id'		=> $times['id'],
					'uid'		=> $v['investor_uid'],
					'type'		=> $duration,
					'money'		=> $investor_capital,
					'times'		=> $current_times,
					'uptime'	=> $timestamp
				];
				if($timestamp > 1545926400 && $timestamp < 1546271999) {
					$data['times1']['doubles'] = $data['times1']['times'];
					unset($data['times1']['times']);
				}
				if($v['investor_uid'] == 27) {
					p($data['times1']);
				}
				//$this->times_model->modify_times($data['times1']);
			}
		}
	}*/
	/** 测试 计算 */
	public function jisuan() {
		$times = $this->db->where(array('uid'=>620, 'type'=>4))->get('times')->row_array();
		echo $aaa = $times['money'];
		echo intval($aaa/15000)*15000;
	}
	
	/** 倒计时测试页面 */
	public function djs() {
		$this->load->view('testadmin/djs');
	}
	
	/*导出红包发放失败的列表 */
	public function get_packet_error_list() {die; 
		//114
		//146
		//208
		//(213-222]
		//(222,265]
		//(265,270]
		$list = $this->db->where('id >', 265)->get('packet_error')->result_array();
		foreach($list as $k=>$v) {
			if($v['rid'] > 0) {
				$packet = $this->db->where('id', $v['rid'])->get('packet')->row_array();
				$meminfo = $this->db->where('uid', $packet['uid'])->get('members_info')->row_array();
				echo $meminfo['real_name'], '&nbsp;', $meminfo['phone'], '&nbsp;', $packet['money'], '&nbsp;', date('Y-m-d H:i', $v['addtime']), '<br />';
			} else {
				$meminfo = $this->db->where('uid', $v['uid'])->get('members_info')->row_array();
				echo $meminfo['real_name'], '&nbsp;', $meminfo['phone'], '&nbsp;', $v['money'], '&nbsp;', date('Y-m-d H:i', $v['addtime']),  '&nbsp;', $v['remark'], '<br />';
			}
			
			//p($packet);
		}
		//p($list);
	}
	/** 修改次数 */
	public function set_timesa() {
		// $times = $this->db->where('id > ', 43)->result_array();
		// foreach($tiems)
		// $this->db->where(array('id>'=>43))->update('times', $data);
	}
	/** 增加投资金额 */
	public function set_times() {die;
		// $data = array(
			// array(15,0,10000,10000),
			// array(22,0,10000,3800),
			// array(23,0,0,5000),
			// array(24,0,0,5000),
			// array(25,30000,0,10000),
			// array(30,0,21000,4000),
			// array(31,0,0,5000),
			// array(32,0,0,5000),
			// array(33,0,0,5000),
			// array(34,10000,0,5000),
			// array(35,30000,0,0),
			// array(36,0,0,5000),
			// array(37,0,10000,10000),
			// array(39,0,10000,5000),
			// array(40,5000,0,0),
			// array(44,0,20000,5000),
			// array(48,0,5000,0),
			// array(49,0,0,10000),
			// array(51,0,0,1500),
			// array(52,8000,0,0),
			// array(55,10000,0,0),
			// array(56,0,0,5000),
			// array(58,0,0,5000),
			// array(60,5000,0,0),
			// array(62,0,0,5000),
			// array(63,0,0,10800),
			// array(67,30000,0,0),
			// array(68,20000,0,0),
			// array(70,5200,0,0),
			// array(71,0,0,10100),
			// array(76,25000,0,0),
			// array(78,1000,0,0),
			// array(79,0,0,7000),
			// array(84,40000,0,5000),
			// array(86,0,10000,0),
			// array(87,0,0,5000),
			// array(90,0,25000,0),
			// array(93,0,0,5000),
			// array(96,0,10000,0),
			// array(99,0,0,10000),
			// array(101,0,0,5000),
			// array(102,0,15000,0),
			// array(105,9000,0,5000),
			// array(106,5000,0,0),
			// array(108,5000,0,0),
			// array(112,0,0,10000),
			// array(115,0,0,10000),
			// array(119,0,20000,6000),
			// array(122,0,0,10900),
			// array(123,0,0,11100),
			// array(127,40000,0,0),
			// array(130,0,0,10000),
			// array(132,0,0,10000),
			// array(136,5000,0,900),
			// array(137,0,0,10000),
			// array(138,5000,0,0),
			// array(139,5000,0,0),
			// array(141,5000,0,0),
			// array(142,5000,0,0),
			// array(143,0,0,5000),
			// array(145,10000,10000,0),
			// array(147,0,0,5000),
			// array(152,0,0,10000),
			// array(155,0,6000,0),
			// array(156,42000,0,0),
			// array(158,0,10000,0),
			// array(164,0,0,5000),
			// array(165,0,0,5000),
			// array(167,0,0,5000),
			// array(174,5000,0,5000),
			// array(179,0,0,5000),
			// array(183,5000,0,0),
			// array(185,0,0,5000),
			// array(187,5000,0,0),
			// array(189,2000,0,0),
			// array(191,0,10000,0),
			// array(193,10000,0,0),
			// array(194,0,20000,0),
			// array(195,0,0,10000),
			// array(196,0,0,10000),
			// array(199,0,20000,5000),
			// array(202,5000,0,0),
			// array(206,0,5000,0),
			// array(212,0,0,10000),
			// array(219,0,0,5000),
			// array(220,10000,0,0),
			// array(223,20000,0,5000),
			// array(236,0,10000,0),
			// array(239,10000,0,0),
			// array(240,10000,0,0),
			// array(246,0,5000,0),
			// array(248,5000,0,0),
			// array(257,0,0,5000),
			// array(261,5000,0,0),
			// array(265,5000,0,0),
			// array(267,5000,0,0),
			// array(278,0,0,5000),
			// array(279,7000,0,0),
			// array(280,13000,0,0),
			// array(281,40000,0,0),
			// array(282,5000,0,0),
			// array(291,8000,0,0),
			// array(292,8000,1000,0),
			// array(297,30000,0,0),
			// array(298,0,5000,0),
			// array(308,5000,0,0),
			// array(318,5000,0,0),
			// array(320,10000,0,0),
			// array(324,0,0,5000),
			// array(337,7000,0,0),
			// array(366,5000,0,0),
			// array(369,5000,0,0),
			// array(382,12300,0,10000),
			// array(392,0,20000,5000),
			// array(410,22000,1000,5000),
			// array(421,300,0,5000),
			// array(425,5000,0,0),
			// array(428,5000,0,0),
			// array(432,5000,0,0),
			// array(456,6800,0,0),
			// array(474,5000,0,0),
			// array(475,5000,0,0),
			// array(509,5000,0,0),
			// array(510,5000,0,0),
			// array(519,5000,0,0),
			// array(549,0,10000,0),
			// array(556,5000,0,0),
			// array(557,5000,0,0),
			// array(568,5000,0,0),
			// array(569,5000,0,0),
			// array(571,1000,0,0),
			// array(573,1000,0,0),
			// array(574,1000,0,0),
			// array(575,1000,0,0),
			// array(576,1000,0,0),
			// array(577,10000,0,0),
			// array(578,1000,0,0),
			// array(581,1000,0,0),
			// array(582,1000,0,0),
			// array(584,0,21000,10000),
			// array(585,6800,0,0),
			// array(593,1000,0,0),
			// array(597,1000,0,0),
			// array(598,1000,0,0),
			// array(601,1000,0,0),
			// array(603,1000,0,0),
			// array(604,1000,0,0),
			// array(605,1000,0,0),
			// array(607,1000,0,0),
			// array(617,8000,0,0),
			// array(618,5000,0,0),
			// array(619,1000,0,0),
			// array(620,0,0,10000),
			// array(621,23000,0,0),
			// array(622,1000,0,0),
			// array(623,0,0,5000),
			// array(628,1000,0,0),
			// array(652,1000,0,0),
			// array(653,1000,0,0),
			// array(654,1000,0,0),
			// array(655,1000,0,0),
			// array(656,1000,0,0),
			// array(657,1000,0,0),
			// array(659,1000,0,0),
			// array(660,1000,0,0),
			// array(668,0,0,5000),
			// array(669,1000,0,0),
			// array(676,1000,0,0),
			// array(677,17000,0,0),
		// );
		// $this->load->model('times/times_model');
		// foreach($data as $k=>$v) {
			// if($v[1] > 0) {
				// $times = $this->times_model->get_times_byuid($v[0], 2);
				// if(!empty($times)) {
					// $tmp_data = array(
						// 'id'		=> $times['id'],
						// 'uid'		=> $v[0],
						// 'type'		=> 2,
						// 'money'		=> $times['money'] + $v[1],
						// 'times'		=> $times['times']
					// );
					// $this->times_model->modify_times($tmp_data);
				// } else {
					// $tmp_data = array(
						// 'uid'		=> $v[0],
						// 'type'		=> 2,
						// 'money'		=> $v[1],
						// 'times'		=> 0,
						// 'addtime'	=> time()
					// );
					// $this->times_model->add_times($tmp_data);
				// }
				////p($tmp_data);
			// }
			
			// if($v[2] > 0) {
				// $times = $this->times_model->get_times_byuid($v[0], 3);
				// if(!empty($times)) {
					// $tmp_data = array(
						// 'id'		=> $times['id'],
						// 'uid'		=> $v[0],
						// 'type'		=> 3,
						// 'money'		=> $times['money'] + $v[2],
						// 'times'		=> $times['times']
					// );
					// $this->times_model->modify_times($tmp_data);
				// } else {
					// $tmp_data = array(
						// 'uid'		=> $v[0],
						// 'type'		=> 3,
						// 'money'		=> $v[2],
						// 'times'		=> 0,
						// 'addtime'	=> time()
					// );
					// $this->times_model->add_times($tmp_data);
				// }
				/////p($tmp_data);
			// }
			// if($v[3] > 0) {
				// $times = $this->times_model->get_times_byuid($v[0], 4);
				// if(!empty($times)) {
					// $tmp_data = array(
						// 'id'		=> $times['id'],
						// 'uid'		=> $v[0],
						// 'type'		=> 4,
						// 'money'		=> $times['money'] + $v[3],
						// 'times'		=> $times['times']
					// );
					// $this->times_model->modify_times($tmp_data);
				// } else {
					// $tmp_data = array(
						// 'uid'		=> $v[0],
						// 'type'		=> 4,
						// 'money'		=> $v[3],
						// 'times'		=> 0,
						// 'addtime'	=> time()
					// );
					// $this->times_model->add_times($tmp_data);
				// }
			// }
			
			////echo '<br />';
		// }
		//p($data);
	}
	
	/** 测试还款，前端js响应时间太慢 */
	public function test_ajax() {
		if($post = $this->input->post(null, true)) {
			sleep(5);
			
			$this->error("测试中");
			
		}
		$this->load->view('testadmin/test_ajax', $data);
	}
	/** 自动发放抽奖次数 */
	public function auto_add_times($bid, $duration) {
		/*
		$investor = $this->borrow_model->get_investor_all($bid, 2);
		$timestamp = time();
		$this->load->model('times/times_model');
		foreach($investor as $k=>$v) {
			$times = $this->times_model->get_times_byuid($v['investor_uid'], $duration);
			//没有数据
			if(empty($times)) {
				//抽奖次数
				$current_times = 0;
				//累积金额
				$investor_capital = $v['investor_capital'];
				if($duration == 2) {//33天
					if($investor_capital >= 50000) {
						$investor_capital = $investor_capital - intval($investor_capital/50000)*50000;
						$current_times = intval($v['investor_capital']/50000);
					}
				} else if($duration == 3) {//65天
					if($investor_capital >= 30000) {
						$investor_capital = $investor_capital - intval($investor_capital/30000)*30000;
						$current_times = intval($v['investor_capital']/30000);
					}
				} else if($duration == 4) {//97天
					if($investor_capital >= 15000) {
						$investor_capital = $investor_capital - intval($investor_capital/15000)*15000;
						$current_times = intval($v['investor_capital']/15000);
					}
				}
				if($current_times > 0) {
					$this->times_model->add_member_times(array('uid' => $v['investor_uid'], 'times' => $current_times));
				}
				
				$data['times'] = [
					'uid'		=> $v['investor_uid'],
					'type'		=> $duration,
					'money'		=> $investor_capital,
					'times'		=> $current_times,
					'addtime'	=> $timestamp
				];
				$this->times_model->add_times($data['times']);
			} else {//已有数据
				//抽奖次数
				$current_times = $times['times'];
				//累积金额
				$investor_capital = $times['money'] + $v['investor_capital'];
				if($duration == 2) {//33天
					if($investor_capital >= 50000) {
						$investor_capital = $investor_capital - intval($investor_capital/50000)*50000;
						$current_times = $current_times + intval(($times['money'] + $v['investor_capital'])/50000);
					}
				} else if($duration == 3) {//65天
					if($investor_capital >= 30000) {
						$investor_capital = $investor_capital - intval($investor_capital/30000)*30000;
						$current_times = $current_times + intval(($times['money'] + $v['investor_capital'])/30000);
					}
				} else if($duration == 4) {//97天
					if($investor_capital >= 15000) {
						$investor_capital = $investor_capital - intval($investor_capital/15000)*15000;
						$current_times = $current_times + intval(($times['money'] + $v['investor_capital'])/15000);
					}
				}
				if(($current_times - $times['times']) > 0) {
					$this->times_model->add_member_times(array('uid' => $v['investor_uid'], 'times' => intval($current_times - $times['times'])));
				}
				$data['times1'] = [
					'id'		=> $times['id'],
					'uid'		=> $v['investor_uid'],
					'type'		=> $duration,
					'money'		=> $investor_capital,
					'times'		=> $current_times,
					'uptime'	=> $timestamp
				];
				$this->times_model->modify_times($data['times1']);
			}
		}*/
		//$this->times_model->add_times($data);
	}
	public function test_strlen() {
		echo strlen('011201810261753202170');
	}
	/** 风险等级 */
	public function set_grade() {
		// $borrow = $this->db->where(array('grade'=>''))->get('borrow')->result_array();
		// foreach($borrow as $k=>$v) {
			// if(!empty($v['grade'])) {
				// continue;
			// }
			// $mant = rand(65, 66);
			// if($mant == 65) {
				// $mant = 'A';
			// } else if($mant == 66) {
				// $mant = 'B';
			// }
			// $v['grade'] = $mant;
			// $this->db->where(array('id'=>$v['id']))->update('borrow', $v);
		// }
	}
	
	
	//测试mysql锁表
	/*
	public function test_table() {
		//$info = $this->db->query("select * from xm_borrow where id=" . intval($bid) . ' for update')->row_array();
		$info = $this->db->query("select * from xm_borrow where id=" . 2 . ' for update')->row_array();
		print_r($info);echo '<br />';
		$info = $this->db->where('id', 2)->get('borrow')->row_array();
		print_r($info);
	}*/
	
	//提前还款
	// public function pre_repayment() {
		// if(IS_POST) {
			// $id_details = $this->input->post('id', true);
			////record_adminlog($this->router->fetch_class(), $this->router->fetch_method(), 0, '标的列表-提前还款, 还款ID为' .$id_details);
			// $ids_details = explode(',', $id_details);
		// }
	// }
	public function index() {
		//echo BASEPATH;
		 //$func = $this->router->fetch_method();echo $func;
		$id = 3;
		//调取标信息
			// $borrow = $this->borrow_model->get_borrow_byid($id);
			////调取借款人信息
			// $meminfo = $this->member_model->get_member_info_byuid($borrow['borrow_uid']);
			
			////数据有问题，报错
			// if(!in_array($borrow['type'], array(1, 2))) {
				////$this->error('标的类型错误');
			// }
			// if(empty($meminfo['idcard'])) {
				////$this->error('身份证或营业执照不能为空');
			// }
			
			// if(empty($meminfo['sealPath'])) {
				// $this->error('还未生成企业签章');
			// } 
			
			// echo $meminfo['sealPath'];
			
		echo 'success';
	}
	
	/** 积分表添加bid */
	/*public function set_bid() {
		$score = $this->db->get('score')->result_array();
		foreach($score as $k=>$v) {
			if($v['invest_id'] > 0) {
				$bid = $this->borrow_model->get_borrow_investor_byid('', $v['invest_id'])[0]['borrow_id'];
				$v['bid'] = $bid;
				$this->db->where('id', $v['id'])->update('score', $v);
				//var_dump($bid);
				//die;
			}
		}
	}*/
	/*
	public function set_score() { 
		$investor = $this->borrow_model->get_investor_all(51, 2);
		$duration = 1;
		if($duration == 1) {
			$score_rate = 0.5;
		} elseif($duration == 2) {
			$score_rate = 1;
		} elseif($duration == 3) {
			$score_rate = 1.5;
		} else {
			$score_rate = 0;
		}
		$ids = array();
		foreach($investor as $k=>$v) {
			array_push($ids, $v['id']);
		}
		
		$res = $this->member_model->get_socre_by_invest_ids($ids);
		$res = array_column($res, 'invest_id');
		foreach($investor as $k=>$v) {
			// if(in_array($v['id'], $res)) {//已发积分
				// continue;
			// }
			//发积分
			$score = round($v['investor_capital']*$score_rate/1000, 2);
			if($score < 0.01) {
				continue;
			}
			//增加积分
			$data = array();
			$data['uid'] = $v['investor_uid'];
			$data['invest_id'] = $v['id'];
			$data['score'] = $score;
			$data['genre'] = 2;
			$data['adminid'] = 0;
			$data['addtime'] = time();
			if(!$this->member_model->set_member_info_totalscore($data)) {
				$this->error('自动添加积分出错，请联系管理员');
			}
		}
	}*/
	//自动发积分 , 需要参数，bid,duration
	
	/*public function test_score() {
		//test_score($bid, $duration)
		$investor = $this->borrow_model->get_investor_all(1, 2);
		
		//积分期限, 根据标期限，获得积分不同，33天千分之0.5，65天千分之1, 97天千分之1.5
		$duration = 1;
		if($duration == 1) {
			$score_rate = 0.5;
		} elseif($duration == 2) {
			$score_rate = 1;
		} elseif($duration == 3) {
			$score_rate = 1.5;
		} else {
			$score_rate = 0;
		}
		
		$ids = array();
		foreach($investor as $k=>$v) {
			array_push($ids, $v['id']);
		}
		//p($ids);
		$res = $this->member_model->get_socre_by_invest_ids($ids);
		$res = array_column($res, 'invest_id');
		foreach($investor as $k=>$v) {
			if(in_array($v['id'], $res)) {//已发积分
				continue;
			}
			//发积分
			$score = round($v['investor_capital']*$score_rate/1000, 2);
			if($score < 0.01) {
				continue;
			}
			//增加积分
			$data = array();
			$data['uid'] = $v['investor_uid'];
			$data['invest_id'] = $v['id'];
			$data['score'] = $score;
			$data['genre'] = 2;
			$data['adminid'] = 0;
			$data['addtime'] = time();
			//p($data);die;
			if(!$this->member_model->set_member_info_totalscore($data)) {
				$this->error('自动添加积分出错，请联系管理员');
			}
		}
	} */
	public function test_password() {
		//echo $this->db->last_query();
		// $member = $this->member_model->get_member_byusername('15221341326');
		// $datas['tel'] = 15221341326;
		
		
		////重置原始密码
		// $salt = salt();
		// $user_pwd = mt_rand(0,9) . rand(12345, 67899);
		// $user_pwdmd5 = MD5(suny_encrypt($user_pwd, $salt));
		// if(send_sms($datas['tel'], '你的原始密码已重置为:', $user_pwd)) {
			// $member['salt'] = $salt;
			// $member['user_pass'] = $user_pwdmd5;
			// if($member['id'] > 0) {
				// $this->member_model->modify_member($member, $member['id']);
			// }
		// }
	}
	public function test_rand() {
		echo rand(12345, 67899);
	}
	
	/** 修改担保人性别 */
	public function modify_sex() {
		//$this->load->model('guarantor/guarantor_model');
		$this->db->where('sex', 1);
		//$this->db->select('id, name, idcard, sex');
		// $data = $this->db->get('company_guarantor')->result_array();
		// foreach($data as $k=>$v) {
			// if(is_numeric($v['idcard'])) {
				// if($v['idcard'] % 2 == 0 && $v['sex'] == '1') {
					//echo $v['id'], '<br />';
					// $v['sex'] = 2;
					// $this->db->where('id', $v['id']);
					// $this->db->update('company_guarantor', $v);
					
					// echo $this->db->last_query();
				// }
			// } else if(substr($v['idcard'], -1, -2) == 'Y') {
				// echo '女';
			// }
			
		// }
		
		// echo 1111;
		
	}
	
	/** 获取控制器和方法名 */
	public function get_contrller_and_method() {
		// $con = $this->router->fetch_class();
		// $func = $this->router->fetch_method();
		// echo $con;
		// echo $func;
	}
	
	public function record_adminlog() {
		//record_adminlog($this->router->fetch_class(), $this->router->fetch_method(), 11, '22');
		// $bid, $controller, $method, $info
		// $data['bid']		= $bid;
		// $data['controller'] = $controller;
		// $data['method'] 	= $method;
		// $data['info']		= $info;
		// $data['addtime']	= time();
		// $data['adminid']	= UID;
		// $data['bid']		= 1;
		// $data['controller'] = 1;
		// $data['method'] 	= 1;
		// $data['info']		= 1;
		// $data['addtime']	= time();
		// $data['adminid']	= UID;
		// $this->load->model('adminlog/adminlog_model');
		// echo 222222;
		// if($this->adminlog_model->add_adminlog($data)) {
			// echo 'success';
		// } else {
			// echo 'error';
		// }
	}
	
	/** 提现记录补充 */
	public function set_tx_data() { die;
		$money = 400000;
		$data['money']['account_money'] = 0.00;
		//回写表members_money_log
		//$money_log = $this->member_model->get_moneylog_bynid($merOrderNo);
		$data['log']['uid'] = 186;
		$data['log']['type'] = 10;
		$data['log']['affect_money'] = 400000;
		$data['log']['account_money'] = 0.00;
		$data['log']['collect_money'] = 0.00;
		$data['log']['freeze_money'] = 0.00;
		//$in_cust = $this->member_model->get_member_info_byuid($water['uid']);
		$data['log']['info'] = "提现" . $money . "元，提现成功";//$in_cust['real_name'] . "向自己账户中充值" . $money . "元，充值成功";//.",".$body['body']['actualAmt'].",".$members_money['account_money'].",".$data['money']['account_money'];
		$data['log']['add_time'] = strtotime('2018-10-31 14:30:02');
		$data['log']['actualAmt'] = 0;
		$data['log']['pledgedAmt'] = 0;//;
		$data['log']['preLicAmt'] = 0;
		$data['log']['totalAmt'] = 0;
		$data['log']['acctNo'] = 0;
		$data['log']['nid'] = '18103114294712685452';
		//回写表members_payonline
		$payonline = $this->member_model->get_quick_bank($water['uid']);
		$data['payonline']['uid'] = 186;
		$data['payonline']['nid'] = '18103114294712685452';
		$data['payonline']['money'] = 400000;
		$data['payonline']['way'] = '';
		
		$data['payonline']['bank'] = isset($payonline['paycard']) ? $payonline['paycard'] : '';
		$data['payonline']['status'] = 1;
		$data['payonline']['add_time'] = strtotime('2018-10-31 14:30:02');
		$data['payonline']['remark'] = isset($payonline['bank_address']) ? $payonline['bank_address'] : '';
		$data['payonline']['type'] = 2;//提现
		//修改流水表状态为1
		$data['water']['status'] = 1;
		p($data);
		die;
		// if(!$this->member_model->recharge($data, 186, '18103114294712685452')) {
			// echo 'error';
		// } else {
			// echo 'success';
		// }
	}
	
	/** 现金红包补数据 */
	public function get_red_data() {
		$aaa = 'a:3:{s:3:"log";a:1:{i:0;a:10:{s:3:"uid";s:2:"63";s:4:"type";i:7;s:12:"affect_money";i:300;s:13:"account_money";d:1238;s:13:"collect_money";s:8:"70000.00";s:12:"freeze_money";s:9:"208000.00";s:4:"info";s:24:"发放300元现金红包";s:8:"add_time";i:1540951815;s:3:"bid";i:0;s:3:"nid";s:20:"18103110101858935469";}}s:6:"cashes";a:1:{i:0;a:7:{s:3:"bid";i:0;s:3:"uid";s:2:"63";s:5:"money";i:300;s:6:"remark";s:45:"2018-10-29日（18102904）补发现金红包";s:7:"addtime";i:1540951815;s:7:"adminid";s:1:"1";s:3:"nid";s:20:"18103110101858935469";}}s:5:"money";a:1:{i:0;a:4:{s:3:"uid";i:63;s:12:"money_freeze";s:9:"208000.00";s:13:"money_collect";s:8:"70000.00";s:13:"account_money";d:1238;}}}';
		$bbb = unserialize($aaa);
		print_r($bbb); 
		
		//$bbb['cashes'][13]['remark'] = '18103105 出借红包';
		
		//print_r($bbb['cashes']);
		
		// if(isset($bbb['cashes'])) {
			// if($this->db->insert_batch('packet_xj', $bbb['cashes'], true, 1000)) {
				// echo 'success';
			// } else {
				// echo 'error';
			// }
		// }
	}
	
	public function get_data() {
		//log_record('test', '/red.log', 111);
		$aaa = 'a:10:{s:4:"name";s:9:"冯海东";s:5:"phone";s:11:"13738431122";s:6:"idcard";s:18:"330421197811052333";s:7:"addtime";i:1540906040;s:3:"sex";i:1;s:4:"mode";s:10:"宝马435i";s:5:"price";d:300000;s:3:"pic";s:675:"/code/20181030/c1c48580ff73ee83692666d10cb59a40.png,/code/20181030/3a72096040149b3e49bf4499bb1b9e8e.png,/code/20181030/abc0f9a109553794aa955d5b6a4c9d49.png,/code/20181030/a5bb41de5c0c4dee9566001416553b38.png,/code/20181030/913da12861453bcb14771d2588802b43.png,/code/20181030/6b9e17077287d58a2e809280f1db6940.png,/code/20181030/e12070b2efa85a4e80159bcdc9133aed.png,/code/20181030/84675a63a03322bfd3bd7f32b8111fb4.png,/code/20181030/9318cc608c274851a4fe1ea555f950a1.png,/code/20181030/780da9f9f3ec424ce6655eedba0c0e35.png,/code/20181030/7fb6cc3c8a37c1bcd586679a99daa408.png,/code/20181030/d9bd590b50c9bad58e8f063ac90d0bef.png,/code/20181030/72b10df21540459e69c59ce3f7aa37b4.png";s:9:"accountId";s:32:"3F92BA8976CB470894DA3DB6D5C7CE9F";s:8:"sealPath";s:47:"/contracts/seal/015409060401810302101014734.jpg";}';
		print_r(unserialize($aaa));
	}
	
	/** 测试数据 */
	public function test_data() {
		
		$aa = 'a:1:{s:5:"value";a:2:{s:5:"signs";s:4:"true";s:4:"body";a:2:{s:4:"body";a:9:{s:11:"payeeAcctNo";s:13:"1013000818701";s:14:"payeeActualAmt";d:10;s:15:"payeePledgedAmt";d:0;s:14:"payeePreLicAmt";d:20000;s:13:"payeeTotalAmt";d:20010;s:11:"payerAcctNo";s:17:"13101000001101306";s:14:"payerActualAmt";d:10323;s:15:"payerPledgedAmt";d:0;s:13:"payerTotalAmt";d:10323;}s:4:"head";a:10:{s:7:"bizFlow";s:21:"007201810301447039158";s:10:"merOrderNo";s:20:"18103014476660417028";s:10:"merchantNo";s:15:"131010000011013";s:8:"respCode";s:6:"000000";s:8:"respDesc";s:6:"成功";s:9:"tradeCode";s:6:"CG1008";s:9:"tradeDate";s:8:"20181030";s:9:"tradeTime";s:6:"144703";s:9:"tradeType";s:2:"01";s:7:"version";s:5:"1.0.0";}}}}';
		print_r(unserialize($aa));
	}
	
	/** 测试发红包 */
	public function test_send() {
		$id = $this->uri->segment(3);
		$data = array();
		if(empty($id)) {
			exit('信息错误！');
		}
		if(IS_POST) {
			$post = $this->input->post(null, true);
			foreach($post as $value) {
				if(is_array($value)) {
					foreach($value as $v) {
						if($value == '' || empty($value)) {
							$this->error('必填项不能为空111');
						}
					}
				} else {
					if($value == '' || empty($value)) {
						$this->error('必填项不能为空111');
					}
				}
			}
			//时间戳
			$timestamp = time();
			//投资人要列出来
			foreach($post['investor_uid'] as $k=>$v) {
				$investor_uid[] = $k;
			}
			//获取所有的投资者
			$where['borrow_id'] = $id;
			$where['loan_status'] = 2;
			$investor = $this->borrow_model->get_investor_bywhere($where);
			//投资者金额重组
			foreach($investor as $k=>$v) {
				if(!in_array($v['investor_uid'], $investor_uid)) {
					continue;
				}
				if(isset($investors[$v['investor_uid']])) {
					$investors[$v['investor_uid']]['investor_capital'] += $v['investor_capital'];
				} else {
					$investors[$v['investor_uid']]['investor_capital'] = $v['investor_capital'];
				}
			}
			//投资红包或者两者都有
			if($post['status'] == 'invest' || $post['status'] == 'all') {
				//投资者
				$i = 0;
				foreach($investors as $key=>$value) {
					//投资红包数量
					foreach($post['invest'] as $k=>$v) {
						$data['invest'][$i]['uid'] = $key;
						$data['invest'][$i]['stime'] = $timestamp;
						$data['invest'][$i]['etime'] = $timestamp + intval($post['etime'][$k]) * 3600 * 24;
						$data['invest'][$i]['money'] = 300;
						$data['invest'][$i]['min_money'] = intval($post['min_money'][$k]/100)*100;
						$data['invest'][$i]['times'] = intval($post['times'][$k]);
						$data['invest'][$i]['type'] = 1;
						$data['invest'][$i]['addtime'] = $timestamp;
						$data['invest'][$i]['status'] = 0;
						$data['invest'][$i]['sid'] = $id;
						$i++;
					}
				}
				
				//红包大于2000的需要拆分数据数据
				$invest_count = count($data['invest']);
				$j = $invest_count;
				//$invest_change = array();
				foreach($data['invest'] as $k=>$v) {
					if($v['money'] > 2000) {
						$invest_num = intval($v['money']/2000);
						$invest_yu = $v['money'] % 2000;
						$data['invest'][$k]['money'] = 2000;
						
						for($i = 1; $i <= $invest_num; $i++) {
							
							if($invest_num == $i && $invest_yu < 0.001) {
								continue;
							}
							$data['invest'][$j]['uid'] = $v['uid'];
							$data['invest'][$j]['stime'] = $timestamp;
							$data['invest'][$j]['etime'] = $timestamp + intval($post['etime'][$k]) * 3600 * 24;
							
							$data['invest'][$j]['money'] = $invest_num == $i ? $invest_yu : 2000;
							
							$data['invest'][$j]['min_money'] = intval($post['min_money'][$k]/100)*100;
							$data['invest'][$j]['times'] = intval($post['times'][$k]);
							$data['invest'][$j]['type'] = 1;
							$data['invest'][$j]['addtime'] = $timestamp;
							$data['invest'][$j]['status'] = 0;
							$data['invest'][$j]['sid'] = $id;
							$j++;
						}
					}
					
				}

				p($data['invest']);
				echo 1111;die;
				die;
				foreach($data['invest'] as $k=>$v) {
					//调取账户金额
					$memoney = $this->member_model->get_members_money_byuid($v['uid']);
					$data['log'][] = array(
						'uid' => $v['uid'],
						'type' => 6,//投资红包
						'affect_money' => $v['money'],
						'account_money' => $memoney['account_money'],//可用
						'collect_money' => $memoney['money_collect'],//待收
						'freeze_money' => $memoney['money_freeze'],//$acountinfo['investor'][$v['investor_uid']]['money_freeze'],//冻结
						'info' => '发放' . $v['money'] . '元,投资红包',
						'add_time' => $timestamp,
						//'add_ip' => $this->input->ip_address(),
						'bid' => $v['sid'],
						'nid'	=> 0
					);
				}
				//p($data);die;
			}
			//现金红包或者两者都有
			if($post['status'] == 'cash' || $post['status'] == 'all') {
				//投资者
				$i = 0;
				foreach($investors as $key=>$value) {
					//投资红包数量
					foreach($post['cash'] as $k=>$v) {
						$data['cash'][$i]['uid'] = $key;
						$data['cash'][$i]['stime'] = 0;
						$data['cash'][$i]['etime'] = 0;
						$data['cash'][$i]['money'] = 0.03;//intval($value['investor_capital'] * $v)/100;
						$data['cash'][$i]['min_money'] = 0;
						$data['cash'][$i]['times'] = 0;
						$data['cash'][$i]['type'] = 2;
						$data['cash'][$i]['addtime'] = $timestamp;
						$data['cash'][$i]['status'] = 0;
						$data['cash'][$i]['sid'] = $id;
						$data['cash'][$i]['remark'] = $post['remark'][$k];
						$i++;
					}
				}
				p($data['cash']);
				
				$data['cash'][0]['money'] = 20019;
				
				//现金红包红包大于2000的需要拆分数据数据
				$invest_count = count($data['cash']);
				$j = $invest_count;
				//$invest_change = array();
				foreach($data['cash'] as $k=>$v) {
					if($v['money'] > 2000) {
						$invest_num = intval($v['money']/2000);
						$invest_yu = $v['money'] % 2000;
						$data['cash'][$k]['money'] = 2000;
						
						for($i = 1; $i <= $invest_num; $i++) {
							
							if($invest_num == $i && $invest_yu < 0.001) {
								continue;
							}
							
							$data['cash'][$j]['uid'] = $v['uid'];
							$data['cash'][$j]['stime'] = 0;
							$data['cash'][$j]['etime'] = 0;
							$data['cash'][$j]['money'] = $invest_num == $i ? $invest_yu : 2000;
							$data['cash'][$j]['min_money'] = 0;
							$data['cash'][$j]['times'] = 0;
							$data['cash'][$j]['type'] = 2;
							$data['cash'][$j]['addtime'] = $timestamp;
							$data['cash'][$j]['status'] = 0;
							$data['cash'][$j]['sid'] = $id;
							$data['cash'][$j]['remark'] = $post['remark'][$k];
							$j++;
							
						}
					}
					
				}
				
				
				print_r($data['cash']);
				die;
				
				
				
				
				
				
				
				//投资人账户金额
				foreach($investors as $k=>$v) {
					$memoney = $this->info_model->get_money($k);
					$acountinfo[$k]['account_money'] = $memoney['account_money'];
					$acountinfo[$k]['money_collect'] = $memoney['money_collect'];
					$acountinfo[$k]['money_freeze'] = $memoney['money_freeze'];
				}
				//红包转账
				foreach($data['cash'] as $k=>$v) {
					//$memoney = $this->member_model->get_members_money_byuid($v['uid']);
					$meminfo = $this->member_model->get_member_info_byuid($v['uid']);
					
					//调用接口发放红包
					$params['payerAcctNo'] = $this->config->item('mchnt_red');
					$params['payeeAcctNo'] = $meminfo['acctNo'];
					$params['amount'] = $v['money'];
					$params['payType'] = '02';//'00':个人->个人'01':个人->商户'02':商户->个人
					//p($params);die;
					$head = head($params, 'CG1008', 'send');
					//p($head);die;
					//water($v['uid'], $head['merOrderNo'], 'CG1008', $id);
					unset($head['callbackUrl'], $head['registerPhone'], $head['responsePath'], $head['url']);
					$head = json_encode($head);
					//$url = $this->config->item('Interface').'1008';
					//$str = post_curl_test($url, $head);
					$this->load->model(array('paytest/paytest_model'));
					//$tmp_body = $this->paytest_model->excute($str);
					$aaa = 'a:1:{s:5:"value";a:2:{s:5:"signs";s:4:"true";s:4:"body";a:2:{s:4:"body";a:9:{s:11:"payeeAcctNo";s:13:"1013000818701";s:14:"payeeActualAmt";d:10;s:15:"payeePledgedAmt";d:0;s:14:"payeePreLicAmt";d:20000;s:13:"payeeTotalAmt";d:20010;s:11:"payerAcctNo";s:17:"13101000001101306";s:14:"payerActualAmt";d:10323;s:15:"payerPledgedAmt";d:0;s:13:"payerTotalAmt";d:10323;}s:4:"head";a:10:{s:7:"bizFlow";s:21:"007201810301447039158";s:10:"merOrderNo";s:20:"18103014476660417028";s:10:"merchantNo";s:15:"131010000011013";s:8:"respCode";s:6:"000000";s:8:"respDesc";s:6:"成功";s:9:"tradeCode";s:6:"CG1008";s:9:"tradeDate";s:8:"20181030";s:9:"tradeTime";s:6:"144703";s:9:"tradeType";s:2:"01";s:7:"version";s:5:"1.0.0";}}}}';
					
					//var_dump($tmp_body);
					//回写数据
					$datas = array();
					if($tmp_body['head']['respCode'] === '000000') {
						$datas['merOrderNo'] = $tmp_body['head']['merOrderNo'];
						$datas['money']		= $v['money'];
						$datas['redid']		= 0;
						$datas['status']	= 1;
						//$this->load->model('water/water_model');
						//$this->water_model->edit_water($datas, $tmp_body['head']['merOrderNo']);
						//账户金额增加
						$acountinfo[$v['uid']]['account_money'] += $v['money'];
						// $data['money'][] = array(
							// 'uid'		=> $v['uid'],
							// 'money_freeze' => $acountinfo[$v['uid']]['money_freeze'],
							// 'money_collect' => $acountinfo[$v['uid']]['money_collect'],
							// 'account_money' => $acountinfo[$v['uid']]['account_money']
						// );
						$data['log'][] = array(
							'uid' => $v['uid'],
							'type' => 7,//现金红包
							'affect_money' => $v['money'],
							'account_money' => $acountinfo[$v['uid']]['account_money'],//可用
							'collect_money' => $acountinfo[$v['uid']]['money_collect'],//待收
							'freeze_money' => $acountinfo[$v['uid']]['money_freeze'],//冻结
							'info' => '发放' . $v['money'] . '元现金红包',
							'add_time' => $timestamp,
							//'add_ip' => $this->input->ip_address(),
							'bid' => $id,
							'nid'	=> $tmp_body['head']['merOrderNo']
						);
						$data['cash'][$k]['nid'] = $tmp_body['head']['merOrderNo'];//将现金红包状态修改为1
					} else {
						$datas['uid']		= $v['uid'];
						$datas['bid']		= $id;
						$datas['money']		= $v['money'];
						$datas['status']	= 0;
						$datas['addtime']	= time();
						$datas['remark']	= '发放现金红包失败';
						//$this->member_model->add_packet_error($datas);
						
						
						
						
						
						//错误的也要记录日志
						$datass['merOrderNo'] = $tmp_body['head']['merOrderNo'];
						$datass['money']		= $v['money'];
						$datass['redid']		= 0;
						$datass['status']	= 1;
						//$this->load->model('water/water_model');
						//$this->water_model->edit_water($datas, $tmp_body['head']['merOrderNo']);
						//账户金额增加
						//$acountinfo[$v['uid']]['account_money'] += $v['money'];
						// $data['money'][] = array(
							// 'uid'		=> $v['uid'],
							// 'money_freeze' => $acountinfo[$v['uid']]['money_freeze'],
							// 'money_collect' => $acountinfo[$v['uid']]['money_collect'],
							// 'account_money' => $acountinfo[$v['uid']]['account_money']
						// );
						
						
						
						
						
						
						
						
						
						
						
						
						$data['log'][] = array(
							'uid' => $v['uid'],
							'type' => 14,//现金红包
							'affect_money' => $v['money'],
							'account_money' => $acountinfo[$v['uid']]['account_money'],//可用
							'collect_money' => $acountinfo[$v['uid']]['money_collect'],//待收
							'freeze_money' => $acountinfo[$v['uid']]['money_freeze'],//冻结
							'info' => '发放' . $v['money'] . '元现金红包,' . $tmp_body['head']['respDesc'],
							'add_time' => $timestamp,
							//'add_ip' => $this->input->ip_address(),
							'bid' => $id,
							'nid'	=> $tmp_body['head']['merOrderNo']
						);
						
						
						
						
						
						
						
						
						
						
					}
				}
				//投资人账户资金重新处理
				foreach($acountinfo as $k=>$v) {
					$data['money'][] = array(
						'uid'		=> $k,
						'money_freeze' => $acountinfo[$k]['money_freeze'],
						'money_collect' => $acountinfo[$k]['money_collect'],
						'account_money' => $acountinfo[$k]['account_money']
					);
				}
				//现金红包不需要记录到红包中
				foreach($data['cash'] as $k=>$v) {
					$data['cashes'][] = array(
						'bid' => $v['sid'],
						'uid' => $v['uid'],
						'money' => $v['money'],
						'remark' => $v['remark'],
						'addtime' => $timestamp,
						'adminid' => UID,
						'nid'	=> $v['nid']
					);
				}
				unset($data['cash']);
				//p($data);die;
			}
			p($data);die;
			//$this->borrow_model->send($data);
			//$this->success('操作成功', '/borrow.html');
		}
		
		//借款信息
		$data['borrow'] = $this->borrow_model->get_borrow_byid($id);
		//投资信息
		$where['borrow_id'] = $id;
		$where['loan_status'] = 2;
		$investor = $this->borrow_model->get_investor_bywhere($where);
		foreach($investor as $v) {
			if(isset($data['investor'][$v['investor_uid']])) {
				$data['investor'][$v['investor_uid']]['investor_capital'] += $v['investor_capital'];
			} else {
				$data['investor'][$v['investor_uid']]['investor_capital'] = $v['investor_capital'];
			}
		}
		$data['id'] = $id;
		$this->load->view('testadmin/send', $data);
	}

	
	/** 测试变更手机号 */
	public function test_changphone() {
		$mem_body = $data['value']['body'];
		$phone = $mem_body['body']['newMobile'];
		$custNo = $mem_body['body']['custNo'];
		$this->load->model(array('member/member_model'));
		$count = $this->member_model->get_member_phone_num($phone);
		if($count < 1) {
			$meminfo = $this->member_model->get_member_info_bycustno($custNo);
			$data_mem['member'] = array(
				'id'	=> $meminfo['uid'],
				'user_name' => $phone
			);
			$data_mem['meminfo'] = array(
				'uid' => $meminfo['uid'],
				'phone' => $phone
			);
			$this->member_model->change_phone($data_mem);
		}
	}
	/** 测试接口  查询红包账户余额*/
	public function test_red() {
		if(empty($id)) {
			$id = 0;
		}
		$params['acctNo'] = $this->config->item('mchnt_red');
		//echo $params['acctNo'];
		$head = head($params, '2001', 'putmoney');
		
		//water($meminfo['uid'], $head['merOrderNo'], 'CG2001', $id);
		unset($head['callbackUrl'], $head['registerPhone'], $head['responsePath'], $head['url']);
		$data = $head;
		p($data);
		$data = json_encode($data);
		//$data['value']['body'] = json_decode($data['value']['body'], true);
		$url = $this->config->item('Interface').'2001';
		$str = post_curl_test($url, $data);
		$arr = json_decode($str, true);
		
		$params['key'] = $arr['keyEnc'];
		
		$params['bodys'] = $arr['jsonEnc'];
		$params['sign'] = $arr['sign'];
		$data = '';
		$data = decrypt($params);
		$data = (array)json_decode($data, true);
		//$data['value'] = (array())
		//echo $data['value']['body'];
		$data['value']['body'] = json_decode($data['value']['body'], true);
		p($data);
		die;
		
		
	}
	
	/* 调取投资红包失败的账户 */
	public function get_tz_red() {
		$this->db->where('bid', 6);
		$this->db->where('type', 6);
		//$this->db->where()
		$res = $this->db->get('members_moneylog')->result_array();
		
		$this->load->model(array('member/member_model'));
		foreach($res as $k=>$v) {
			$res['k']['real_name'] = $this->member_model->get_member_info_byuid($v['uid'])['real_name'];
			echo $res['k']['real_name'], '&nbsp;&nbsp;', $v['info'], '&nbsp;&nbsp;', $v['uid'], '<br />';
		}
		//print_r($res);
	}
	
	/** 测试 绑卡修改手机号*/
	public function test_bindcard() {
		$a = 'a:1:{s:5:"value";a:2:{s:5:"signs";s:4:"true";s:4:"body";a:2:{s:4:"body";a:8:{s:6:"acctNo";s:13:"1013000814101";s:10:"bankMobile";s:11:"15221341326";s:8:"bankName";s:18:"中国工商银行";s:6:"cardNo";s:14:"621226****0764";s:6:"certNo";s:18:"410522198501012877";s:8:"custName";s:9:"陈方杰";s:6:"custNo";s:18:"110131810270008141";s:13:"registerPhone";s:11:"15221341326";}s:4:"head";a:10:{s:7:"bizFlow";s:21:"011201810270954032219";s:10:"merOrderNo";s:20:"18102709541304433729";s:10:"merchantNo";s:15:"131010000011013";s:8:"respCode";s:6:"000000";s:8:"respDesc";s:6:"成功";s:9:"tradeCode";s:6:"CG1044";s:9:"tradeDate";s:8:"20181027";s:9:"tradeTime";s:6:"095523";s:9:"tradeType";s:2:"01";s:7:"version";s:5:"1.0.0";}}}}';
		$d = unserialize($a);
		$body = $d['value']['body'];
		p($body);
		$body['body']['registerPhone'] = 15221341325;
		// $a = json_decode($a, true);
		// $body = $a;
		// p($body);
		//写入members_quickbank表，要记录body里边的客户号码（custNo），
		//账户号码（acctNo），bankMobile（bankMobile），注册手机号（registerPhone），证件号码（certNo）必填字段，
		$data = array();
		if(empty($body['body'])) {
			return false;
		}
		$this->load->model(array('member/member_model'));
		$meminfo = $this->member_model->get_member_info_byphone($body['body']['registerPhone']);
		echo $memfino['uid'];die;
		if(empty($meminfo['uid'])) {
			log_record(serialize($a), '', '手机号不存在' . $body['body']['registerPhone']);
		}
		$data['quick'] = array(
			'uid' 		=> $meminfo['uid'],
			'tel' 		=> $body['body']['bankMobile'],
			'paycard'	=> $body['body']['cardNo'],
			'number'	=> $body['head']['bizFlow'],
			'real_name'	=> $body['body']['custName'],
			'card'		=> $body['body']['certNo'],
			'paystatus'	=> 1,
			'bank_address'=> $body['body']['bankName'],
			'addtime'	=> time()
		);
		$data['meminfo'] = array(
			'idcard' 	=> $body['body']['certNo'],
			'real_name'	=> $body['body']['custName'],
			'custNo'	=> $body['body']['custNo'],
			'acctNo'	=> $body['body']['acctNo'],
			'accountId' => 1
		);
		$data['memstatus'] = array(
			'id_status' 	=> 1,
			'phone_status'	=> 1
		);
		$this->member_model->update_members_bindcard($data);
	}
}