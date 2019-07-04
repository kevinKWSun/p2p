<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Pintu extends Baseaccounts {
	public function __construct() {
		echo '活动结束';die;
		parent::__construct();
		$this->load->library('pagination');
		$this->load->model(array('cj/cj_model','member/member_model', 'zcard/zcard_model'));
		//$this->load->helper('url');
	}
	public function index(){
		$data = array();
		if(QUID > 0) {
			$data['zcard'] = $this->zcard_model->get_by_uid(QUID);
			//中奖信息
			$order = $this->zcard_model->get_order_by_uid(QUID);
			$data['order']['num1'] = 0;
			$data['order']['num2'] = 0;
			$data['order']['num3'] = 0;
			$data['order']['num4'] = 0;
			$data['order']['num5'] = 0;
			$data['order']['num6'] = 0;
			foreach($order as $v) {
				if($v['type'] == 1) {
					$data['order']['num1'] += $v['nums'];
				}
				if($v['type'] == 2) {
					$data['order']['num2'] += $v['nums'];
				}
				if($v['type'] == 3) {
					$data['order']['num3'] += $v['nums'];
				}
				if($v['type'] == 4) {
					$data['order']['num4'] += $v['nums'];
				}
				if($v['type'] == 5) {
					$data['order']['num5'] += $v['nums'];
				}
				if($v['type'] == 6) {
					$data['order']['num6'] += $v['nums'];
				}
			}
		}
		$this->load->view('cj/pintu', $data);
	}
	public function c(){
		if(time() > strtotime('2019-02-20')){
			$res['yes']['check'] = 5;
			$this->output
			    ->set_output(json_encode($res))
				->_display();
			    exit;
		}
		if(! QUID){
			$res['yes']['check'] = 0;
			$this->output
			    ->set_output(json_encode($res))
				->_display();
			    exit;
		}else{
			$zcard = $this->zcard_model->get_by_uid(QUID);
			if($zcard['total'] < 1){
				$res['yes']['check'] = 1;
				$this->output
					->set_output(json_encode($res))
					->_display();
					exit;
			}
		}
		$prizes = array(
			array('id' => 1, 'img' => '/pintu/img/cutting-01.png'),
			array('id' => 2, 'img' => '/pintu/img/cutting-02.png'),
			array('id' => 3, 'img' => '/pintu/img/cutting-03.png'),
			array('id' => 4, 'img' => '/pintu/img/cutting-04.png'),
			array('id' => 5, 'img' => '/pintu/img/cutting-05.png'),
			array('id' => 6, 'img' => '/pintu/img/cutting-06.png'),
			array('id' => 7, 'img' => '/pintu/img/cutting-07.png'),
			array('id' => 8, 'img' => '/pintu/img/cutting-08.png'),
			array('id' => 9, 'img' => '/pintu/img/cutting-09.png'),
			array('id' => 10, 'img' => '/pintu/img/cutting-10.png')
		);
		$j = 0;
		foreach($prizes as $v){
			$prize_arr[$j] = $v;
			$j++;
		}
		//获取随机数
		$rid = $this->get_random();
		
		$res['yes']['img'] = '<img class="active" src="'.$prizes[$rid-1]['img'].'" style="border-top:1px solid #FF0000;"/>';
		$res['yes']['id'] = $prize_arr[$rid-1]['id'];
		
		//入库逻辑
		$this->db->trans_begin();
		$this->db->query("select * from xm_zcard where uid=".QUID." for update");
		$zcard = $this->zcard_model->get_by_uid(QUID);
		$data['zcard'] = [
			'id' => $zcard['id'],
			'total' => $zcard['total'] - 1,
			'card'.$rid => $zcard['card'.$rid] + 1
		];
		
		$data['detail'] = [
			'uid' => QUID,
			'num' => $rid,
			'addtime' => time()
		];
		$this->zcard_model->modify_card($data['zcard']);
		$this->zcard_model->insert_detail($data['detail']);
		
		if($this->db->trans_status() === TRUE){
			$this->db->trans_commit();
			$res['yes']['check'] = 10;
		}else{
			$this->db->trans_rollback();
			$res['yes']['check'] = 2;
			$this->output
				->set_output(json_encode($res))
				->_display();
				exit;
		}

		unset($prize_arr[$rid-1]);
		shuffle($prize_arr);
		for ($i = 0; $i < count($prize_arr); $i++) {
			$pr[] = '<img src="'.$prize_arr[$i]['img'].'"/>';
		}
		$res['no'] = $pr;
		echo json_encode($res);
	}
	
	/** 兑奖的方法 */
	public function duijiang() {
		$post = $this->input->post(null, true);
		foreach($post as $k=>$v) {
			$post[$k] = intval($v);
		}
		//调取用户的卡片信息
		$zcard = $this->zcard_model->get_by_uid(QUID);
		//判断使用的卡片数量是否超过真实数量
		for($i=1; $i <= 10; $i++) {
			if($zcard['card'.$i] < $post['end'.$i]) {
				$this->error('卡片'.$i.'超过可使用数量');
			}
		}
		//根据兑奖规则，折算奖品
		switch($post['rel']) {
			case 1:
				//一等奖, 组织卡片数组
				$cards = array();
				for($i = 1; $i <= 10; $i++) {
					if($post['end'.$i] < 1) {
						$this->error('不满足一等奖兑换条件');
					} /* else {
						$cards[] = $post['end'.$i];
					} */
				}
				//获得兑换套数，取得卡片最少数
				$min = 1;//min($cards);
				
				// 插入数据库
				$this->db->trans_begin();
				$this->db->query("select * from xm_zcard where uid=".QUID." for update");
				$zcard = $this->zcard_model->get_by_uid(QUID);
				$data['zcard'] = [
					'id' => $zcard['id'],
					'card1' => $zcard['card1'] - $min,
					'card2' => $zcard['card2'] - $min,
					'card3' => $zcard['card3'] - $min,
					'card4' => $zcard['card4'] - $min,
					'card5' => $zcard['card5'] - $min,
					'card6' => $zcard['card6'] - $min,
					'card7' => $zcard['card7'] - $min,
					'card8' => $zcard['card8'] - $min,
					'card9' => $zcard['card9'] - $min,
					'card10' => $zcard['card10'] - $min,
				];
				for($i = 1; $i <= 10; $i++) {
					if($data['zcard']['card'.$i] < 0) {
						$this->error('兑换出错，请联系客服');
					}
				}
				$data['order'] = [
					'uid' => QUID,
					'add_time' => time(),
					'type'	=> 1,
					'nums'	=> $min,
					'mark'	=> '1,2,3,4,5,6,7,8,9,10'
				];
				$this->zcard_model->modify_card($data['zcard']);
				$this->zcard_model->insert_order($data['order']);
				
				if($this->db->trans_status() === TRUE){
					$this->db->trans_commit();
					$res['yes']['check'] = 10;
					$this->success();
				}else{
					$this->db->trans_rollback();
					$res['yes']['check'] = 2;
					$this->output
						->set_output(json_encode($res))
						->_display();
						exit;
				}
			break;
			case 2:
				//二等奖, 组织卡片数组
				$cards = [3,4,5,8,9,10];
				foreach($cards as $v) {
					if($post['end'.$v] < 1) {
						$this->error('不满足二等奖兑换条件');
					}
				}
				//获得兑换套数，取得卡片最少数
				$min = 1;
				
				// 插入数据库
				$this->db->trans_begin();
				$this->db->query("select * from xm_zcard where uid=".QUID." for update");
				$zcard = $this->zcard_model->get_by_uid(QUID);
				$data['zcard'] = [
					'id' => $zcard['id'],
					'card3' => $zcard['card3'] - $min,
					'card4' => $zcard['card4'] - $min,
					'card5' => $zcard['card5'] - $min,
					'card8' => $zcard['card8'] - $min,
					'card9' => $zcard['card9'] - $min,
					'card10' => $zcard['card10'] - $min,
				];
				foreach($cards as $v) {
					if($data['zcard']['card'.$v] < 0) {
						$this->error('兑换出错，请联系客服');
					}
				}
				$data['order'] = [
					'uid' => QUID,
					'add_time' => time(),
					'type'	=> 2,
					'nums'	=> $min,
					'mark'	=> '3,4,5,8,9,10'
				];
				$this->zcard_model->modify_card($data['zcard']);
				$this->zcard_model->insert_order($data['order']);
				
				if($this->db->trans_status() === TRUE){
					$this->db->trans_commit();
					$res['yes']['check'] = 10;
					$this->success();
				}else{
					$this->db->trans_rollback();
					$res['yes']['check'] = 2;
					$this->output
						->set_output(json_encode($res))
						->_display();
						exit;
				}
			break;
			case 3:
				//二等奖, 组织卡片数组
				$cards = [1,2,6,7];
				foreach($cards as $v) {
					if($post['end'.$v] < 1) {
						$this->error('不满足三等奖兑换条件');
					}
				}
				//获得兑换套数，取得卡片最少数
				$min = 1;
				
				// 插入数据库
				$this->db->trans_begin();
				$this->db->query("select * from xm_zcard where uid=".QUID." for update");
				$zcard = $this->zcard_model->get_by_uid(QUID);
				$data['zcard'] = [
					'id' => $zcard['id'],
					'card1' => $zcard['card1'] - $min,
					'card2' => $zcard['card2'] - $min,
					'card6' => $zcard['card6'] - $min,
					'card7' => $zcard['card7'] - $min,
				];
				foreach($cards as $v) {
					if($data['zcard']['card'.$v] < 0) {
						$this->error('兑换出错，请联系客服');
					}
				}
				$data['order'] = [
					'uid' => QUID,
					'add_time' => time(),
					'type'	=> 3,
					'nums'	=> $min,
					'mark'	=> '1,2,6,7'
				];
				$this->zcard_model->modify_card($data['zcard']);
				$this->zcard_model->insert_order($data['order']);
				
				if($this->db->trans_status() === TRUE){
					$this->db->trans_commit();
					$res['yes']['check'] = 10;
					$this->success();
				}else{
					$this->db->trans_rollback();
					$res['yes']['check'] = 2;
					$this->output
						->set_output(json_encode($res))
						->_display();
						exit;
				}
			break;
			case 4:
				//总卡片数
				$cards_total = 0;
				$mark = '';
				for($i = 1; $i <= 10; $i++) {
					$cards_total += $post['end'.$i];
					if($post['end'.$i] >= 1) {
						for($j = 1; $j <= $post['end'.$i]; $j++) {
							$mark .= $i.',';
						}
					}
				}
				if($cards_total > 200) {
					$this->error('一次最多兑换200张');
				}
				$mark = trim($mark, ',');
				if($cards_total < 1) {
					$this->error('不满足积分兑换条件');
				}
				// 插入数据库
				$this->db->trans_begin();
				$this->db->query("select * from xm_zcard where uid=".QUID." for update");
				$this->db->query("select * from xm_members_info where uid=".QUID." for update");
				$zcard = $this->zcard_model->get_by_uid(QUID);
				$data['zcard']['id'] = $zcard['id'];
				for($i = 1; $i <= 10; $i++) {
					$data['zcard']['card'.$i] = $zcard['card'.$i] - $post['end'.$i];
				}
				for($i = 1; $i <= 10; $i++) {
					if($data['zcard']['card'.$i] < 0) {
						$this->error('兑换出错，请联系客服');
					}
				}
				$data['order'] = [
					'uid' => QUID,
					'add_time' => time(),
					'type'	=> 4,
					'nums'	=> $cards_total,
					'mark'	=> $mark,
					'status' => 1
				];
				$data['score']['uid'] = QUID;
				$data['score']['bid'] = 0;
				$data['score']['invest_id'] = 0;
				$data['score']['score'] = $cards_total*6;
				$data['score']['genre'] = 3;
				$data['score']['adminid'] = 0;
				$data['score']['addtime'] = time();
				$data['score']['remark'] = '卡片兑换';
				$this->zcard_model->modify_card($data['zcard']);
				$this->zcard_model->insert_order($data['order']);
				$this->member_model->set_member_info_totalscores($data['score']);
				
				if($this->db->trans_status() === TRUE){
					$this->db->trans_commit();
					$res['yes']['check'] = 10;
					$this->success();
				}else{
					$this->db->trans_rollback();
					$res['yes']['check'] = 2;
					$this->output
						->set_output(json_encode($res))
						->_display();
						exit;
				}
			break;
			case 5:
				//总卡片数
				$cards_total = 0;
				$mark = '';
				for($i = 1; $i <= 10; $i++) {
					$cards_total += $post['end'.$i];
					if($post['end'.$i] >= 1) {
						for($j = 1; $j <= $post['end'.$i]; $j++) {
							$mark .= $i.',';
						}
					}
				}
				if($cards_total < 1) {
					$this->error('不满足积分兑换条件');
				}
				if($cards_total > 200) {
					$this->error('一次最多兑换200张');
				}
				if(($cards_total % 5) != 0) {
					$this->error('数量不是5的倍数...');
				}
				$mark = trim($mark, ',');
				
				// 插入数据库,自动发红包
				$this->db->trans_begin();
				$this->db->query("select * from xm_zcard where uid=".QUID." for update");
				$zcard = $this->zcard_model->get_by_uid(QUID);
				$data['zcard']['id'] = $zcard['id'];
				for($i = 1; $i <= 10; $i++) {
					$data['zcard']['card'.$i] = $zcard['card'.$i] - $post['end'.$i];
				}
				for($i = 1; $i <= 10; $i++) {
					if($data['zcard']['card'.$i] < 0) {
						$this->error('兑换出错，请联系客服');
					}
				}
				$data['order'] = [
					'uid' => QUID,
					'add_time' => time(),
					'type'	=> 5,
					'nums'	=> $cards_total/5,
					'mark'	=> $mark,
					'status' => 1
				];
				
				$memoney = $this->member_model->get_members_money_byuid(QUID);
				for($i = 1; $i <= $cards_total/5; $i++) {
					$data['invest'][$i-1]['uid'] = QUID;
					$data['invest'][$i-1]['bid'] = 0;
					$data['invest'][$i-1]['stime'] = time();
					$data['invest'][$i-1]['etime'] = time() + 15 * 3600 * 24;
					$data['invest'][$i-1]['money'] = 200;
					$data['invest'][$i-1]['min_money'] = 30000;
					$data['invest'][$i-1]['times'] = 97;
					$data['invest'][$i-1]['type'] = 1;
					$data['invest'][$i-1]['addtime'] = time();
					$data['invest'][$i-1]['status'] = 0;
					$data['invest'][$i-1]['sid'] = 0;
					$data['invest'][$i-1]['admin_id'] = 0;
					$data['invest'][$i-1]['remark'] = '卡片兑换出借红包';
				}
				for($i = 1; $i <= $cards_total/5; $i++) {
					$data['log'][$i-1] = [
						'uid' => QUID,
						'type' => 6,//出借红包
						'affect_money' => 200,
						'account_money' => $memoney['account_money'],//可用
						'collect_money' => $memoney['money_collect'],//待收
						'freeze_money' => $memoney['money_freeze'],//冻结
						'info' => '卡片兑换200元,出借红包',
						'add_time' => time(),
						'bid' => 0,
						'nid'	=> 0,
						'add_ip' => '',
						'actualAmt' => '0',
						'pledgedAmt' => '0',
						'preLicAmt' => '0',
						'totalAmt' => '0',
						'acctNo' => '0'
					];
				}
				
				
				$this->load->model('borrow/borrow_model');
				$this->zcard_model->modify_card($data['zcard']);
				$this->zcard_model->insert_order($data['order']);
				$this->borrow_model->sends($data);
				
				if($this->db->trans_status() === TRUE){
					$this->db->trans_commit();
					$res['yes']['check'] = 10;
					$this->success();
				}else{
					$this->db->trans_rollback();
					$res['yes']['check'] = 2;
					$this->output
						->set_output(json_encode($res))
						->_display();
						exit;
				}
			break;
			case 6:
				//总卡片数
				$cards_total = 0;
				$mark = '';
				for($i = 1; $i <= 10; $i++) {
					$cards_total += $post['end'.$i];
					if($post['end'.$i] >= 1) {
						for($j = 1; $j <= $post['end'.$i]; $j++) {
							$mark .= $i.',';
						}
					}
				}
				if($cards_total < 1) {
					$this->error('不满足积分兑换条件');
				}
				if($cards_total > 200) {
					$this->error('一次最多兑换200张');
				}
				if(($cards_total % 10) != 0) {
					$this->error('数量不是10的倍数...');
				}
				$mark = trim($mark, ',');
				
				// 插入数据库,自动发红包
				$this->db->trans_begin();
				$this->db->query("select * from xm_zcard where uid=".QUID." for update");
				$zcard = $this->zcard_model->get_by_uid(QUID);
				$data['zcard']['id'] = $zcard['id'];
				for($i = 1; $i <= 10; $i++) {
					$data['zcard']['card'.$i] = $zcard['card'.$i] - $post['end'.$i];
				}
				for($i = 1; $i <= 10; $i++) {
					if($data['zcard']['card'.$i] < 0) {
						$this->error('兑换出错，请联系客服');
					}
				}
				$data['order'] = [
					'uid' => QUID,
					'add_time' => time(),
					'type'	=> 6,
					'nums'	=> $cards_total/10,
					'mark'	=> $mark,
					'status' => 1
				];
				
				$memoney = $this->member_model->get_members_money_byuid(QUID);
				for($i = 1; $i <= $cards_total/10; $i++) {
					$data['invest'][$i-1]['uid'] = QUID;
					$data['invest'][$i-1]['bid'] = 0;
					$data['invest'][$i-1]['stime'] = time();
					$data['invest'][$i-1]['etime'] = time() + 15 * 3600 * 24;
					$data['invest'][$i-1]['money'] = 500;
					$data['invest'][$i-1]['min_money'] = 50000;
					$data['invest'][$i-1]['times'] = 97;
					$data['invest'][$i-1]['type'] = 1;
					$data['invest'][$i-1]['addtime'] = time();
					$data['invest'][$i-1]['status'] = 0;
					$data['invest'][$i-1]['sid'] = 0;
					$data['invest'][$i-1]['admin_id'] = 0;
					$data['invest'][$i-1]['remark'] = '卡片兑换出借红包';
				}
				for($i = 1; $i <= $cards_total/10; $i++) {
					$data['log'][$i-1] = [
						'uid' => QUID,
						'type' => 6,//出借红包
						'affect_money' => 500,
						'account_money' => $memoney['account_money'],//可用
						'collect_money' => $memoney['money_collect'],//待收
						'freeze_money' => $memoney['money_freeze'],//冻结
						'info' => '卡片兑换500元,出借红包',
						'add_time' => time(),
						'bid' => 0,
						'nid'	=> 0,
						'add_ip' => '',
						'actualAmt' => '0',
						'pledgedAmt' => '0',
						'preLicAmt' => '0',
						'totalAmt' => '0',
						'acctNo' => '0'
					];
				}
				
				
				$this->load->model('borrow/borrow_model');
				$this->zcard_model->modify_card($data['zcard']);
				$this->zcard_model->insert_order($data['order']);
				$this->borrow_model->sends($data);
				
				if($this->db->trans_status() === TRUE){
					$this->db->trans_commit();
					$res['yes']['check'] = 10;
					$this->success();
				}else{
					$this->db->trans_rollback();
					$res['yes']['check'] = 2;
					$this->output
						->set_output(json_encode($res))
						->_display();
						exit;
				}
			break;
			default:
				$this->error('信息错误，请联系客服');
		}
	}
	/** 测试方法 */
	/* public function test_get_random() {
		for($i=0;$i<7000;$i++) {
			$rid = $this->get_random();
			
			//入库逻辑
			$this->db->trans_begin();
			$this->db->query("select * from xm_zcard where uid=".QUID." for update");
			$zcard = $this->zcard_model->get_by_uid(QUID);
			$data['zcard'] = [
				'id' => $zcard['id'],
				'total' => $zcard['total'] - 1,
				'card'.$rid => $zcard['card'.$rid] + 1
			];
			
			$data['detail'] = [
				'uid' => QUID,
				'num' => $rid,
				'addtime' => time()
			];
			$this->zcard_model->modify_card($data['zcard']);
			$this->zcard_model->insert_detail($data['detail']);
			
			if($this->db->trans_status() === TRUE){
				$this->db->trans_commit();
			}else{
				$this->db->trans_rollback();
				$this->output
					->set_output(json_encode($res))
					->_display();
					exit;
			}
			sleep(0.5);
		}
		
		
	} */
	public function get_random() {
		
		//已发卡片数量
		$total = $this->zcard_model->get_total();
		//卡片限制条件决定5,7出现的位置在600次中，600次是一个周期
		//卡片5发放次数
		$card5_total = $this->zcard_model->get_by_num(5);
		//卡片7发放的次数
		$card7_total = $this->zcard_model->get_by_num(7);
		
		$cyc = 500;//周期是600,2019-1-26,周期变为300，2019-2-12，周期修改为500,300出一次5,500出一次7
		//应该出现次数
		
		// 重新计算总次数 , 6448 是现有的总数，10 已发的套数
		/* $total = ($total - 6308);
		$card5_total = $card5_total - 10;
		$card7_total = $card7_total - 10; */
		$total = ($total - 13907);
		$card5_total = $card5_total - 35;
		$card7_total = $card7_total - 35;
		
		// 重新计算总次数 , 10 新发多少套
		$cyc_num = ceil($total/$cyc);
		// 判断
		if($card5_total < $cyc_num && (($total - ($cyc_num-1)*$cyc)) == 300) {
			return 5;
		} else {
			if($card7_total < $cyc_num && (($total - ($cyc_num-1)*$cyc)) == 500) {
				return 7;
			} else {
				return $this->random8();
			}
		}
	}
	/*public function get_random() {
		$random = $this->random10();
		//设置一个权重，决定5,7出现的位置在600次中，600次是一个周期
		$weight5 = 0.3;
		$weight7 = 0.7;
		//已发卡片数量
		$total = $this->zcard_model->get_total();
		//卡片限制条件决定5,7出现的位置在600次中，600次是一个周期
		//卡片5发放次数
		$card5_total = $this->zcard_model->get_by_num(5);
		//卡片7发放的次数
		$card7_total = $this->zcard_model->get_by_num(7);
		
		$cyc = 600;//周期是600
		//应该出现次数
		
		// 重新计算总次数 , 6448 是现有的总数，10 已发的套数
		//$total = ($total - 6448);
		//$card5_total = $card5_total - 10;
		//$card7_total = $card7_total - 10;
		
		$cyc_num = ceil($total/$cyc);
		$cyc_num = $cyc_num > 10 ? 10 : $cyc_num;
		
		// 重新计算总次数 , 10 新发多少套
		//$cyc_num = ceil($total/$cyc);
		//$cyc_num = $cyc_num > 10 ? 10 : $cyc_num;
		
		//判断是否应该出5或7
		if($random == 5) {
			if(($card5_total < $cyc_num) && ((($total - ($cyc_num-1)*$cyc)/$cyc) > $weight5)) {
				return $random;
			} else {
				$random = $this->random8();
			}
		}
		if($random == 7) {
			if(($card7_total < $cyc_num) && ((($total - ($cyc_num-1)*$cyc)/$cyc) > $weight7)) {
				return $random;
			} else {
				$random = $this->random8();
			}
		}
		return $random;
	}*/
	public function random10() {
		$arr = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
		$digit = rand(0, 9);
		return $arr[$digit];
	}
	public function random8() {
		$arr = [1, 2, 3, 4, 6, 8, 9, 10];
		$digit = rand(0, 7);
		return $arr[$digit];
	}
}