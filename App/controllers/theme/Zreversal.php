<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Zreversal extends Baseaccounts {
	public function __construct() {
		parent::__construct();
		$this->load->library('pagination');
		$this->load->database();
		//$this->load->library('session');
		$this->load->model(array('theme/zreversal_model','member/member_model'));
		$this->total = 8; // 一轮卡片总数
	}
	
	/** 首页 */
	public function index() {
		$data = array();
		$data['html'] = '';
		if(QUID) {
			// 查询翻牌表，判断是否还有可以翻的牌
			$zreversal = $this->zreversal_model->get_zreversal_byuid(QUID);
			$data['zreversal'] = $zreversal;
			// 查询一条翻牌详情表数据
			$detail = $this->zreversal_model->get_detail_byuid(QUID);
			if(empty($detail) || $detail['status'] == 1) {
				// 本次翻牌已完成，自动生成一条新的数据 
				$counts = $this->zreversal_model->get_rules_nums();
				$rules = $this->zreversal_model->get_rules();
				$data_detail = array();
				$data_detail['uid'] = QUID;
				$data_detail['rid'] = $this->get_prize($counts, $rules);
				$data_detail['status'] = 0;
				$data_detail['addtime'] = time();
				$data_detail['sort'] = empty($detail) ? 1 : ($detail['sort'] + 1);
				$this->zreversal_model->insert_detail($data_detail);
				$detail = $this->zreversal_model->get_detail_byuid(QUID);
			}
			if(isset($detail['detail']) && !empty($detail['detail'])) {
				// 已有部分翻过
				$detail['detail'] = explode(',', $detail['detail']);
				$detail_counts = count($detail['detail']); 
				$detail_counts_having = 0; // 本轮已确定未翻牌的数量
				// 循环调取商品的图片
				foreach($detail['detail'] as $k=>$v) {
					$img = '';
					if($v != 0) {
						$img = $this->zreversal_model->get_product_byid($v)['img'];
						$data['html'] .= '<li><div><img src="'.$img.'"  /></div></li>';
					} else {
						$data['html'] .= '<li><div class="sponsorFlip"><img src="/src/reversal/img/card-reversal.png"  /></div><div class="sponsorData"><img src="/src/reversal/img/max-man.png"  /></div></li>';
						$detail_counts_having++;
					}
					
				}
				if($zreversal['num'] == 0) {
					// 没有可以翻的
					for($i = $detail_counts; $i < $this->total; $i++) {
						$data['html'] .= '<li><div><img src="/src/reversal/img/card-reversal-gray.png"  /></div></li>';
					}
					
				} else if($zreversal['num'] - $detail_counts_having < $this->total - $detail_counts) {
					// 有部分是可以翻牌
					if($zreversal['num'] - $detail_counts_having > 0) {
						for($i = 0; $i < $zreversal['num'] - $detail_counts_having; $i++) {
							$data['html'] .= '<li><div class="sponsorFlip"><img src="/src/reversal/img/card-reversal.png"  /></div><div class="sponsorData"><img src="/src/reversal/img/max-man.png"  /></div></li>';
						}
					}
					if($zreversal['num'] - $detail_counts_having + $detail_counts > 0) {
						for($i = $zreversal['num'] - $detail_counts_having + $detail_counts; $i < $this->total; $i++) {
							$data['html'] .= '<li><div><img src="/src/reversal/img/card-reversal-gray.png"  /></div></li>';
						}
					}
				} else if($zreversal['num'] - $detail_counts_having >= $this->total - $detail_counts) {
					// 剩余的全部可翻
					for($i = $detail_counts; $i < $this->total; $i++) {
						$data['html'] .= '<li><div class="sponsorFlip"><img src="/src/reversal/img/card-reversal.png"  /></div><div class="sponsorData"><img src="/src/reversal/img/max-man.png"  /></div></li>';
					}
				}
			} else {
				if($zreversal['num'] == 0) {
					for($i = 0; $i < $this->total; $i++) {
						$data['html'] .= '<li><div><img src="/src/reversal/img/card-reversal-gray.png"  /></div></li>';
					}
				} else if($zreversal['num'] < $this->total) {
					// 可以翻牌的，要先查询所翻的商品图片
					// 可以翻的数量是 $zreversal['num']
					for($i = 0; $i < $zreversal['num']; $i++) {
						$data['html'] .= '<li><div class="sponsorFlip"><img src="/src/reversal/img/card-reversal.png"  /></div><div class="sponsorData"><img src="/src/reversal/img/max-man.png"  /></div></li>';
					}
					// 不可翻
					for($i = $zreversal['num']; $i < $this->total; $i++) {
						$data['html'] .= '<li><div><img src="/src/reversal/img/card-reversal-gray.png"  /></div></li>';
					}
				} else if($zreversal['num'] >= $this->total) {
					// 全部是可翻的
					for($i = 0; $i < $this->total; $i++) {
						$data['html'] .= '<li><div class="sponsorFlip"><img src="/src/reversal/img/card-reversal.png"  /></div><div class="sponsorData"><img src="/src/reversal/img/max-man.png"  /></div></li>';
					}
				}
			}
			$data['detail'] = $detail;
			// 兑换奖品列表
			$data['order'] = array();
			// 总数
			$data['order']['html'] = $this->get_order_lists();
		} else {
			for($i = 0; $i < $this->total; $i++) {
				$data['html'] .= '<li><div><img src="/src/reversal/img/card-reversal-gray.png"  /></div></li>';
			}
			$data['order'] = array();
		}
		//$this->session->set_userdata();
		$this->load->view('theme/zreversal/index', $data);
	}
	
	/** 首页列表的分页处理 */
	private function get_order_lists($page_current = 1) {
		$page_size = 3;
		$page_current = isset($page_current) ? $page_current : 1;
		$page_total = $this->zreversal_model->get_before_order_nums(QUID);
		$page_nums = ceil($page_total/$page_size);
		if($page_current < 0 || $page_current > $page_nums) {
			$page_current = 1;
		}
		//echo $page_total;echo $page_nums;
		$page_list = $this->zreversal_model->get_before_order_lists($page_size, ($page_current-1)*$page_size, QUID);
		// 页面显示多少条
		$page_show_num = 6;
		$page_html = '';
		$page_html .= '<div class="reversal-table-container">';
		$page_html .= '<div class="reversal-table">';
		$i = 0;
		foreach($page_list as $key=>$value) {
			$page_html .= '<div class="reversal-table-div">';
			$page_html .= '<ul class="reversal-table-ul-one">';
			if(!empty($value['detail'])) {
				$detail = explode(',', $value['detail']);
				$detail_arr = array();
				foreach($detail as $v) {
					$detail_arr[$v]['num'] = isset($detail_arr[$v]['num']) ? $detail_arr[$v]['num'] += 1 : 1;
				}
				// 调取对应的图片
				$products = $this->zreversal_model->get_product_all();
				$product_arr = array();
				foreach($products as $k=>$v) {
					$product_arr[$v['id']]['img'] = $v['img'];
				}
				// 组织样式
				foreach($detail_arr as $k=>$v) {
					$page_html .= '<li><img src="'. $product_arr[$k]['img'] .'" ><var>('.$detail_arr[$k]['num'].')</var></li>';
				}
				$page_html .= '</ul>';
				$page_html .= '<ul class="reversal-table-ul-two">';
				foreach($detail_arr as $k=>$v) {
					if($v['num'] > 2) {
						$page_html .= '<li><img src="'. $product_arr[$k]['img'] .'" ><var>('.($detail_arr[$k]['num'] - 2).')</var></li>';
					}
				}
				$page_html .= '</ul>';
				
				if($value['otime'] > 0) {
					// 已兑换
					$page_html .= '<a class="reversal-table-a-1 reversal-table-b">兑换</a>';
				} else {
					$page_html .= '<a class="exchange reversal-table-a-1" data-id="'.$value['id'].'" data-url="/zreversal/exchange">兑换</a>';
				}
				$page_html .= '</div>';
			}
			$i++;
		}
		$page_html .= '</div>';
		$page_html .= '</div>';
		// 分页
		$page_html .= '<div class="container large">';
		if($page_nums > 0) {
			$page_html .= '<div class="pagination">';
			$page_html .= '<ul>';
			if($page_current > 1 && $page_current <= $page_nums) {
				// 上一页
				$page_html .= '<li> <a data-href="/zreversal/fenye/' . ($page_current - 1) . '.html"><</a></li>';
			}
			if($page_nums < 6) {
				for($i = 1; $i <= $page_nums; $i++) {
					$page_html .= '<li' . ($page_current == $i ? ' class="active"' : '') . '> <a data-href="/zreversal/fenye/' . $i . '.html">' . $i . '</a></li>';
				}
			} else {
				if($page_current < 6) {
					for($i = 1; $i < 6; $i++) {
						$page_html .= '<li' . ($page_current == $i ? ' class="active"' : '') . '> <a data-href="/zreversal/fenye/' . $i . '.html">' . $i . '</a></li>';
					}
				} else if($page_current > $page_nums - 5) {
					for($i = $page_nums - 5; $i < $page_nums; $i++) {
						$page_html .= '<li' . ($page_current == $i ? ' class="active"' : '') . '> <a data-href="/zreversal/fenye/' . $i . '.html">' . $i . '</a></li>';
					}
				} else {
					for($i = $page_current - 2; $i < $page_current + 3; $i++) {
						$page_html .= '<li' . ($page_current == $i ? ' class="active"' : '') . '> <a data-href="/zreversal/fenye/' . $i . '.html">' . $i . '</a></li>';
					}
				}
			}
			if($page_nums > 5) {
				$page_html .= '<li> <i>...</i></li>';
				$page_html .= '<li' . ($page_current == $i ? ' class="active"' : '') . '> <a data-href="/zreversal/fenye/' . $page_nums . '.html">'.$page_nums.'</a></li>';
			}
			if($page_current < $page_nums && $page_current > 0 && $page_nums > 1) {
				// 下一页
				$page_html .= '<li> <a data-href="/zreversal/fenye/' . ($page_current + 1) . '.html">></a></li>';
			}
			$page_html .= '</ul>';
			if($page_nums > 1) {
				$page_html .= '<input type="text" name="page" />';
				$page_html .= '<span id="js-page" style="cursor:pointer;" data-href="/zreversal/fenye/">';
				$page_html .= '确定';
				$page_html .= '</span>';
			}
			$page_html .= '</div>';
		}
		$page_html .= '</div>';
		return $page_html;
	}
	
	/** 兑换 */
	public function exchange() {
		if(!QUID) $this->error('请登陆');
		if(IS_POST) {
			$post = $this->input->post(null, true);
			$id = intval($post['id']);
			if(empty($id)) {
				$this->error('操作失败，请刷新页面后重试');
			}
			$detail = $this->zreversal_model->get_detail_byid($id);
			if($detail['otime'] > 0) {
				$this->error('已兑换过该商品');
			}
			$detail['otime'] = time();
			if($this->zreversal_model->update_detail($detail)) {
				$this->success('兑换成功');
			} else {
				$this->error('操作失败，请刷新页面后重试');
			}
		}
	}
	
	/** 分页 */
	public function fenye() {
		if(!QUID) $this->error();
		
		$page = $this->uri->segment(3);
		$page = (isset($page) && !empty($page)) ? intval($page) : 1;
		$html = $this->get_order_lists($page);
		$this->success($html);
	}
	
	/** 反转 */
	public function reversal() {
		if(!QUID) {
			$this->error('还未登陆');
		}
		if(IS_POST) {
			$post = $this->input->post(null, true);
			if(isset($post['all'])) {
				$zreversal = $this->zreversal_model->get_zreversal_byuid(QUID);
				if($zreversal['num'] < 1) {
					$this->error('抽奖次数不足..');
				}
				// 获取所有的$index
				$index_arr = array();
				$detail = $this->zreversal_model->get_detail_byuid(QUID);
				$detail_arr = array();
				if(!empty($detail['detail'])) {
					$detail_arr = explode(',', $detail['detail']);
				}
				// 本轮有多少未抽卡片
				$detail_nums = 0;
				foreach($detail_arr as $k=>$v) {
					if($v == 0) {
						array_push($index_arr, $k);
						$detail_nums++;
					}
				}
				//p(($detail_nums + ($this->total - count($detail_arr))));die;
				if($zreversal['num'] >= $detail_nums + ($this->total - count($detail_arr))) {
					for($i = count($detail_arr); $i < $this->total; $i++) {
						array_push($index_arr, $i);
					}
				}
				if($zreversal['num'] < $detail_nums + ($this->total - count($detail_arr))) {
					
					if($zreversal['num'] - $detail_nums > 0) {
						for($i = count($detail_arr); $i < ($zreversal['num'] - $detail_nums + count($detail_arr)); $i++) {
							array_push($index_arr, $i);
						}
					}
					
				}
				//p($index_arr);die;
				// 返回的图片
				$img_arr = array();
				if(empty($index_arr)) {
					$this->error('本轮奖品已抽玩,请刷新页面后操作');
				}
				foreach($index_arr as $index) {
					if(!in_array($index, array(0, 1, 2, 3, 4, 5, 6, 7))) {
						$this->error('请求错误，请刷新页面重试');
					}
					$product = $this->get_zreversal_pic($index, $zreversal);
					array_push($img_arr, $product['img']);
				}
				
				$this->success($img_arr);
			} else {
				$index = intval($post['index']);
				$zreversal = $this->zreversal_model->get_zreversal_byuid(QUID);
				if($zreversal['num'] < 1) {
					$this->error('抽奖次数不足');
				}
				if(!in_array($index, array(0, 1, 2, 3, 4, 5, 6, 7))) {
					$this->error('请求错误，请刷新页面重试');
				}
				$product = $this->get_zreversal_pic($index, $zreversal);
				$this->success($product['img']);
			}
		}
	}
	/** 根据索引 返回生成的图片 */
	private function get_zreversal_pic($index, $zreversal) {
		$error = false;
		$error_msg = array(
			'1' => '抽奖次数不足',
			'2' => '已经抽过，请刷新后查看',
			'3' => '请刷新页面重试!',
			'4' => '请求错误，请刷新页面重新操作',
			'5' => '请求错误，请刷新页面重新操作.'
		);
		// 开启事务
		$this->db->trans_begin();
		do {
			$detail = $this->zreversal_model->get_detail_byuid(QUID);
			$detail_arr = array();
			$detail_arr_num = 0;
			if(!empty($detail['detail'])) {
				$detail_arr = explode(',', $detail['detail']);
				foreach($detail_arr as $k=>$v) {
					if($v == 0) {
						$detail_arr_num++;
					}
				}
			}
			if($detail['status'] == 1) {
				$error = 3;break;
			}
			if(($index + 1 - count($detail_arr) + $detail_arr_num) > $zreversal['num']) {
				$error = 3;break;
			}
			// 获取下次要出现的数字
			if($index + 1 > count($detail_arr)) {
				// 处理已有数据
				for($i = count($detail_arr); $i < $index + 1; $i++) {
					if(!isset($detail_arr[$i])) {
						$detail_arr[$i] = 0;
					}
				}
				$detail_arr[$index] = $this->get_rule_array($detail, 1)[0];
				if(is_null($detail_arr[$index])) {
					$error = 4;break;
				}
				// 调取对应商品的图片
				if($detail_arr[$index] > 0) {
					$product = $this->zreversal_model->get_product_byid($detail_arr[$index]);
				}
				
			} else {
				//echo $detail_arr[$index];die;
				//p($this->get_rule_array($detail, 1)[0]);die;
				if(isset($detail_arr[$index]) && $detail_arr[$index] > 0) {
					$error = 2;break;
				}
				$detail_arr[$index] = $this->get_rule_array($detail, 1)[0];
				if(is_null($detail_arr[$index])) {
					$error = 5;break;
				}
				// 调取对应商品的图片
				if($detail_arr[$index] > 0) {
					$product = $this->zreversal_model->get_product_byid($detail_arr[$index]);
				}
			}
			if(!empty($detail_arr)) {
				// 最后一次修改状态
				$status = false;
				foreach($detail_arr as $k=>$v) {
					if($v == 0) {
						$status = false;break;
					}
					$status = true;
				}
				if($status && (count($detail_arr) == $this->total)) {
					$detail['status'] = 1;
				}
				$detail['detail'] = implode(',', $detail_arr);
				$detail['uptime'] = time();
				$this->zreversal_model->update_detail($detail);
				// 对应的抽奖次数减一
				$zreversal = $this->zreversal_model->get_zreversal_byuid(QUID);
				$zreversal['num'] -= 1;
				if($zreversal['num'] < 0) {
					$error = 1;break;
				}
				$zreversal['uptime'] = time();
				$this->zreversal_model->update_zreversal($zreversal);
			}
			
			//$this->success($product['img']);
		} while(false);
		
		
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$this->error('信息错误， 请联系客服');
		} else {
			if($error) {
				$this->db->trans_rollback();
				$this->error($error_msg[$error]);
			} else {
				$this->db->trans_commit();
				return $product;
				//$this->success($product['img']);
			}
		}
	}
	
	/** 根据规则 返回对应的奖品 */
	private function get_rule_array($detail, $prize_num = 1) {
		if(empty($detail['detail'])) {
			$rand_arr = range(1, $this->total);
			shuffle($rand_arr);
			return array($rand_arr[0]);
		}
		$rid = $detail['rid'];
		$detail_arr = explode(',', $detail['detail']);
		// 过滤掉0
		foreach($detail_arr as $k=>$v) {
			if($v == 0) {
				unset($detail_arr[$k]);
			}
		}
		$detail_arr = array_values($detail_arr);
		// 查询规则
		$rule = $this->zreversal_model->get_rule_byid($rid);
		// 根据规则生成数组
		$arr = array();
		$rule_arr = explode(',', $rule['rule']);
		if(!empty($detail_arr)) {
			foreach($rule_arr as $k=>$v) {
				$tmp = explode('-', $v);
				for($i = 0; $i < $tmp[1]; $i ++) {
					array_push($arr, $tmp[0]);
				}
			}
		}
		$arr_nums = count($arr);
		$arr_original = $arr;
		// 其他数字出现的个数
		$other_nums = $this->total - $arr_nums;
		// 排除掉已出现过的
		foreach($detail_arr as $k=>$v) {
			if(in_array($v, $arr)) {
				foreach($arr as $key=>$value) {
					if($value == $v) {
						unset($arr[$key]);
						unset($detail_arr[$k]);
						break;
					}
				}
			}
		}
		$arr = array_values($arr);
		$detail_arr = array_values($detail_arr);
		// 组织其他数字的数组
		$other_arr = array(); // 已出现的数字
		$other_arrs = array(); // 可能出现的数字
		if($other_nums > 0) {
			// 统计已出现的其他数字的数组
			if(!empty($detail_arr)) {
				$other_arr = $detail_arr;
			}
			$other_nums = $other_nums - count($other_arr);
			if($other_nums > 0) {
				// 查询商品数量
				$counts_product = $this->zreversal_model->get_product_nums();
				$rand_arr = range(1, $counts_product);
				// 去掉规则中的数字
				foreach($rand_arr as $k=>$v) {
					if(in_array($v, $arr_original)) {
						unset($rand_arr[$k]);
					}
				}
				$rand_arr = array_values($rand_arr);
				for($i = 0; $i < $other_nums; $i++) {
					$rand_num = $this->create_one($rand_arr, $other_arr, $rule['maxv']);
					array_push($other_arr, $rand_num);
					array_push($other_arrs, $rand_num);
				}
			}
		}
		
		$final_arr = array_merge($other_arrs, $arr);
		shuffle($final_arr);
		//p($final_arr);die;
		if($prize_num >= count($final_arr)) {
			return $final_arr;
		} else {
			$return_arr = array();
			for($i = 0; $i < $prize_num; $i++) {
				array_push($return_arr, $final_arr[$i]);
			}
			return $return_arr;
		}
	}
	
	
	// 生成一个随机数，判断该随机数是否满足条件，在$arr中，加上$other_arr中的相同的数字不大于$rule['maxv']
	private function create_one($rand_arr, $other_arr, $maxv) {
		$random = $this->create_array_rand($rand_arr);
		$num = 1;
		foreach($other_arr as $v) {
			if($v == $random) {
				$num ++;
			}
		}
		if($num <= $maxv) {
			return $random;
		} else {
			foreach($rand_arr as $k=>$v) {
				if($v == $random) {
					unset($rand_arr[$k]);
				}
			}
			$rand_arr = array_values($rand_arr);
			$this->create_one($rand_arr, $other_arr, $maxv);
		}
	}
	
	// 传一个数组，随机一个数字
	public function create_array_rand($arr) {
		shuffle($arr);
		return $arr[0];
	}
	
	/** 返回奖品列 */
	private function get_prize($counts, $rules) {
		// 比率 1:1000
		$ratio = 10000*$counts;
		// 得到概率值
		$rand_num = $this->create_rand($ratio);
		$rate = 0;
		foreach($rules as $k=>$v) {
			$rate += $v['rat']*$ratio;
			if($rand_num <= $rate) {
				return $v['id'];
			}
		}
	}
	
	/** 生成随机数 */
	private function create_rand($ratio) {
		return mt_rand(1, $ratio);
	}
	
	// 测试生成满足规则的8位数
	private function test_create_one() {
		$uid = 1;
		$detail = $this->zreversal_model->get_detail_byuid($uid);
		$rule = $this->zreversal_model->get_rule_byid($detail['rid']);
		
		$detail_arr = explode(',', $detail['detail']);
		// 过滤掉0
		foreach($detail_arr as $k=>$v) {
			if($v == 0) {
				unset($detail_arr[$k]);
			}
		}
		$detail_arr = array_values($detail_arr);
		// 查询规则
		//k$rule = $this->zreversal_model->get_rule_byid($rid);
		// 根据规则生成数组
		$arr = array();
		$rule_arr = explode(',', $rule['rule']);
		if(!empty($detail_arr)) {
			foreach($rule_arr as $k=>$v) {
				$tmp = explode('-', $v);
				for($i = 0; $i < $tmp[1]; $i ++) {
					array_push($arr, $tmp[0]);
				}
			}
		}
		$arr_nums = count($arr);
		$arr_original = $arr;
		// 其他数字出现的个数
		$other_nums = $this->total - $arr_nums;
		// 排除掉已出现过的
		foreach($detail_arr as $k=>$v) {
			if(in_array($v, $arr)) {
				foreach($arr as $key=>$value) {
					if($value == $v) {
						unset($arr[$key]);
						unset($detail_arr[$k]);
						break;
					}
				}
			}
		}
		$arr = array_values($arr);
		$detail_arr = array_values($detail_arr);
		// 组织其他数字的数组
		$other_arr = array(); // 已出现的数字
		$other_arrs = array(); // 可能出现的数字
		if($other_nums > 0) {
			// 统计已出现的其他数字的数组
			if(!empty($detail_arr)) {
				$other_arr = $detail_arr;
			}
			$other_nums = $other_nums - count($other_arr);
			if($other_nums > 0) {
				// 查询商品数量
				$counts_product = $this->zreversal_model->get_product_nums();
				$rand_arr = range(1, $counts_product);
				// 去掉规则中的数字
				foreach($rand_arr as $k=>$v) {
					if(in_array($v, $arr_original)) {
						unset($rand_arr[$k]);
					}
				}
				$rand_arr = array_values($rand_arr);
				for($i = 0; $i < $other_nums; $i++) {
					$rand_num = $this->create_one($rand_arr, $other_arr, $rule['maxv']);
					array_push($other_arr, $rand_num);
					array_push($other_arrs, $rand_num);
				}
			}
		}
		
		$final_arr = array_merge($other_arrs, $arr);
		print_r($final_arr);
		
	}
	/** 测试概率值 */
	private function test_random() {
		$total = 1000000;
		$arr = array();
		$counts = $this->zreversal_model->get_rules_nums();
		$rules = $this->zreversal_model->get_rules();
		for($i = 0; $i < $total; $i++) {
			$random = $this->get_prize($counts, $rules);
			$arr[$random] = isset($arr[$random]) ? ($arr[$random] + 1) : 1;
		}
		
		$arrs = array();
		foreach($arr as $k=>$v) {
			//echo $k, ': ', $v/$total, ' 个数： ', $v, '<br />';
			foreach($rules as $key=>$value) {
				if($k == $value['id']) {
					$arrs[$value['desc']] = isset($arrs[$value['desc']]) ? $arrs[$value['desc']] + $v : $v;
				}
			}
		}
		
		foreach($arrs as $k=>$v) {
			echo $k, ': ', $v/$total, ' 个数： ', $v, '<br />';
		}
	}
	/** 测试一个概率 */
	private function test_one_random() {
		$counts = $this->zreversal_model->get_rules_nums();
		$rules = $this->zreversal_model->get_rules();
		echo $this->get_prize($counts, $rules);
	}
}