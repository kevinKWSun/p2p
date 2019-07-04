<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Prize extends Baseaccounts {
	public function __construct() {
		echo '活动结束';die;
		parent::__construct();
		$this->load->library('pagination');
		$this->load->model(array('cj/cj_model','member/member_model'));
		//$this->load->helper('url');
	}
	public function index(){
		$rel = 1;
		if(time() > strtotime('2019-01-11')){
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
			$rel = $this->input->get('rel', TRUE);
			$rel = $rel ? $rel : 1;
			if($rel == 1){
				if(get_member_info(QUID)['times'] < 1){
					$res['yes']['check'] = 1;
					$this->output
						->set_output(json_encode($res))
						->_display();
						exit;
				}
			}elseif($rel == 2){
				if(get_member_info(QUID)['doubles'] < 1){
					$res['yes']['check'] = 1;
					$this->output
						->set_output(json_encode($res))
						->_display();
						exit;
				}
			}
		}
		$prizes = $this->cj_model->get_goods(12, 0, 1);
		$j = 0;
		krsort($prizes);
		foreach($prizes as $v){
			$prize_arr[$j] = $v;
			$j++;
		}
		foreach ($prize_arr as $key => $val) {
			$arr[$val['id']] = $val['probability'];
			if($val['id'] != 11 && $val['id'] != 5 && $val['id'] != 6 && $val['id'] != 7 && $val['id'] != 8 && $val['id'] != 9 && $val['id'] != 10 && $val['id'] != 12){
				$nums[$val['id']] = $val['num'];
			}
		}
		$rid = $this->get_rand($arr, $nums);
		$res['yes']['img'] = '<img class="active" src="'.$prize_arr[$rid-1]['img'].'"/>';
		$res['yes']['id'] = $prize_arr[$rid-1]['id'];
		$data = array(
			'uid' => QUID,
			'aid' => $prize_arr[$rid-1]['id'],
			'add_time' => time(),
		);
		if(QUID != 170){
			$this->db->trans_begin();
			$this->db->query("select * from xm_activity where id=".$data['aid']." for update");
			$this->db->query("select * from xm_members_info where uid=".QUID." for update");
			if($rel == 2){
				$data['nums'] = 2;
				$data['types'] = 2;
				$this->cj_model->up_userinfo_doubles(QUID, 1);
			}else{
				$this->cj_model->up_userinfo_times(QUID, 1);///////////////
			}
			$this->cj_model->add_order($data);
			$this->cj_model->up_prize_num($prize_arr[$rid-1]['id'], 1);
			if($this->db->trans_status() === TRUE){
				$this->db->trans_commit();
			}else{
				$this->db->trans_rollback();
				$res['yes']['check'] = 2;
				$this->output
					->set_output(json_encode($res))
					->_display();
					exit;
			}
		}
		unset($prize_arr[$rid-1]);
		shuffle($prize_arr);
		for ($i = 0; $i < count($prize_arr); $i++) {
			$pr[] = '<img src="'.$prize_arr[$i]['img'].'"/>';
		}
		$res['no'] = $pr;
		echo json_encode($res);
	}
	public function prize(){
		$prizes = $this->cj_model->get_goods(19, 0);
		$data['prizes'] = $prizes;
		foreach($prizes as $k=>$v){
			if(QUID > 0){
				$unum = $this->cj_model->get_order_num(QUID, $v['id']);
				$data['prizes'][$k]['unum'] = $unum;
			}else{
				$data['prizes'][$k]['unum'] = 0;
			}
		}
		if(QUID){
			$data['totalscore'] = get_member_info(QUID)['times'];
			$data['totalscores'] = get_member_info(QUID)['doubles'];
			$data['times'] = $this->cj_model->get_order_nums(QUID);
			$data['img_1'] = $this->cj_model->get_order_num(QUID, 13);
			$data['img_2'] = $this->cj_model->get_order_num(QUID, 14);
			$data['img_3'] = $this->cj_model->get_order_num(QUID, 15);
			$data['img_4'] = $this->cj_model->get_order_num(QUID, 16);
			$data['img_5'] = $this->cj_model->get_order_num(QUID, 17);
			$data['img_6'] = $this->cj_model->get_order_num(QUID, 18);
			$data['img_7'] = $this->cj_model->get_order_num(QUID, 19);
			/* if(QUID==3){
				$data['img_1'] = 1;
				$data['times'] = 30;
			} */
		}else{
			$data['totalscore'] = 0;
			$data['totalscores'] = 0;
			$data['times'] = 0;
			$data['img_1'] = 0;
			$data['img_2'] = 0;
			$data['img_3'] = 0;
			$data['img_4'] = 0;
			$data['img_5'] = 0;
			$data['img_6'] = 0;
			$data['img_7'] = 0;
		}
		$this->load->view('cj/prize', $data);
	}
	private function get_rand($proArr, $num = '') {
		$result = '';
		if(! $result){
			arsort($num);
			reset($num);
			$result = key($num);
		}
		$proSum = 100;//array_sum($proArr);
		$now = time();
		foreach ($proArr as $key => $proCur) {
			$randNum = mt_rand(1, $proSum);
			$x = $this->cj_model->get_goods_one($key);
			if($proCur == 0 || ($x['num'] <= 0 && ($key == 7 || $key == 8 || $key == 9 || $key == 10 || $key == 12))){
				continue;
			}else{
				if ($randNum <= $proCur) {
					/* if($key == 7 || $key == 8 || $key == 9 || $key == 10 || $key == 12){
						$w = $this->cj_model->get_order_byuid('', $key);
						if($w){
							if($w['uid'] == QUID){
								continue;
							}else{
								$oldtime = $w['add_time'];
								$newtime = strtotime(date('Y-m-d', $oldtime));
								$oneday = $newtime + 86400;
								$towday = $newtime + 86400 * 2;
								$threeday = $newtime + 86400 * 3;
								if($now < $oneday && $key == 7){
									continue;
								}elseif($now < $towday && $key == 8){
									continue;
								}elseif($now < $threeday && $key == 9){
									continue;
								}
							}
						}
					} */
					//$this->cj_model->get_by_times();
					///////
					/* if($this->cj_model->get_order_byuid(QUID, 7)||$this->cj_model->get_order_byuid(QUID, 8)||$this->cj_model->get_order_byuid(QUID, 9)||$this->cj_model->get_order_byuid(QUID, 10)||$this->cj_model->get_order_byuid(QUID, 12)){
						continue;
					}else{
						$time = date('Y-m-d', time());
						$times = array(strtotime($time),time());
						if(! $this->cj_model->get_by_times($times, 7) && strtotime($time . ' 12:05:00') < time() && time() < strtotime('2018-12-29')){
							$key = 1;
						}
					} */
					/////
					/* $time = date('Y-m-d', time());
					$times = array(strtotime($time),time());
					if(! $this->cj_model->get_by_times($times, 12) && QUID==63){ 
						$key = 1; 
					} */
					/* $tomotor = strtotime('2018-12-15');
					if($tomotor > time()){
						$time = date('Y-m-d', time());
						$times = array(strtotime($time),time());
						if(! $this->cj_model->get_by_times($times, 7)){
							$key = 7;
						}
					}else{
						//2018-12-15,报错的地方
						//$times = array(strtotime($tomotor),time());
						$times = array($tomotor,time());
						if(! $this->cj_model->get_by_times($times, 10) && $this->cj_model->get_by_times($times) > 152 && time() < strtotime('2018-12-15 23:59:59')){
							$key = 10;
						}
					} */
					$result = $key;
					break;
				} else {
					$proSum -= $proCur;
				}
			}
		}
		
		unset($proArr);
		return $result;
	}
	public function box(){
		if($p = $this->input->post(NULL, TRUE)){
			$res = $p['res'];
			$info['status'] = 0;
			if(! QUID){
				$this->output
					->set_output(json_encode($info))
					->_display();
					exit;
			}
			$id = 0;
			$times = $this->cj_model->get_order_num(QUID);
			$cdui = TRUE;
			switch($res){
				case 1: 
					$id = 13;
					if($times < 25){
						$cdui = FALSE;
					}
				break;
				case 2: 
					$id = 14;
					if($times < 50){
						$cdui = FALSE;
					}
				break;
				case 3: 
					$id = 15;
					if($times < 75){
						$cdui = FALSE;
					}
				break;
				case 4: 
					$id = 16;
					if($times < 100){
						$cdui = FALSE;
					}
				break;
				case 5: 
					$id = 17;
					if($times < 120){
						$cdui = FALSE;
					}
				break;
				case 6: 
					$id = 18;
					if($times < 200){
						$cdui = FALSE;
					}
				break;
				case 7: 
					$id = 19;
					if($times < 300){
						$cdui = FALSE;
					}
				break;
				default : 
					$cdui = FALSE;
			}
			if($cdui == FALSE){
				$this->output
					->set_output(json_encode($info))
					->_display();
					exit;
			}
			if($this->cj_model->get_order_num(QUID,$id)){
				$this->output
					->set_output(json_encode($info))
					->_display();
					exit;
			}
			$data = array(
				'uid' => QUID,
				'aid' => $id,
				'add_time' => time(),
			);
			$g = $this->cj_model->get_goods_one($id);
			$this->db->trans_begin();
			$this->db->query("select * from xm_activity where id=".$id." for update");
			$this->cj_model->add_order($data);
			$this->cj_model->up_prize_num($id, 1);
			if($this->db->trans_status() === TRUE){
				$this->db->trans_commit();
				$info['status'] = 1;
				$info['info'] = $g['name'];
				$info['id'] = $id;
				$this->output
					->set_output(json_encode($info))
					->_display();
					exit;
			}else{
				$this->db->trans_rollback();
				$info['status'] = 2;
				$info['info'] = '服务器链接超时...';
				$this->output
					->set_output(json_encode($info))
					->_display();
					exit;
			}
		}
	}
}