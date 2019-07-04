<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Suny extends Baseaccounts {
	public function __construct() {
		parent::__construct();
		//$this->load->library('pagination');
		$this->load->model(array('borrow/borrow_model', 'member/member_model'));
		//$this->load->helper('url');
	}
	//首页
	/*
	public function indexa(){
		$data['borrows'] = $this->borrow_model->get_borrow_index(3, 0);
		$data['news'] = $this->member_model->get_news_index(5, 0);
		
		//最新公告，
		$this->load->model('news/news_model');
		$data['newest_cate'] = 13;
		$data['newest'] = $this->news_model->get_news_alls(8);
		
		
		
		//回款公告，
		//$this->load->model('news/news_model');
		$data['payments_cate'] = 14;
		$data['payments'] = $this->news_model->get_news_alls(9, 5);
		
		//媒体报道，
		//$this->load->model('news/news_model');
		$data['media_cate'] = 15;
		$data['media'] = $this->news_model->get_news_all(2, 3, 0);
		foreach($data['media'] as $k=>$v) {
			$data['media'][$k]['content'] = mb_substr(strip_tags($v['content']), 0, 65);
		}
		
		//产品推荐，热门产品
		$where = array();
		$where['borrow_type >'] = 2;
		$where['borrow_status >='] = 2;
		$data['borrow'] = $this->borrow_model->get_borrows_bywhere($where, 4);
		//var_dump($data['borrow']);
		
		//体验专区
		//$where = array('borrow_type >' => 1);
		//$data['tiyan'] = $this->borrow_model->get_borrows_bywhere($where, 1)[0];
		//新手专区
		$where = array();
		$where['borrow_type'] = 2;
		$where['borrow_status >='] = 2;
		$xinshou = $this->borrow_model->get_borrows_bywhere($where, 1); 
		if($xinshou) {
			$data['xinshou'] = $xinshou[0];
		}
		//p($data['xinshou']);
		
		$this->load->view('index/index',$data);
	}*/
	public function index(){
		$data['borrows'] = $this->borrow_model->get_borrow_index(3, 0);
		$data['news'] = $this->member_model->get_news_index(5, 0);
		$data['totalmember'] = $this->borrow_model->get_totalmember();
		$data['totalmoney'] = $this->borrow_model->get_totalmoney()['totalmoney'];
		$data['totalsmoney'] = $this->borrow_model->get_totalsmoney()['totalsmoney'];
		$a = get_curl('http://www.jiamanu.cn/api2.aspx?action=getSYTotle');
		$a = str_replace(',','',json_decode($a, TRUE)['totle']);
		$data['totalsmoney'] = $data['totalsmoney'] + $a;
		//最新公告，
		$this->load->model('news/news_model');
		$data['newest_cate'] = 6;
		$data['newest'] = $this->news_model->get_news_alls(8);
		//平台公告，
		$this->load->model('news/news_model');
		$data['pt_cate'] = 12;
		$data['ptwest'] = $this->news_model->get_news_alls(7, 5);
		
		//回款公告，
		//$this->load->model('news/news_model');
		$data['payments_cate'] = 7;
		$data['payments'] = $this->news_model->get_news_alls(9, 5);
		
		//媒体报道，
		//$this->load->model('news/news_model');
		$data['media_cate'] = 15;
		$data['media'] = $this->news_model->get_news_all(2, 3, 0);
		foreach($data['media'] as $k=>$v) {
			if(! $v['img']) {
				$data['media'][$k]['img'] = '/images/new/img/mtbd.png';
			}
			$data['media'][$k]['content'] = mb_substr(strip_tags($v['content']), 0, 65);
		}
		
		// 新手标
		$borrow_new = $this->borrow_model->get_borrow_new();
		$data['borrow_new'] = $borrow_new;
		
		//产品推荐，热门产品
		$where = array();
		$where['borrow_type >'] = 2;
		$where['borrow_status >='] = 2;
		$where['id >'] = 1;
		$data['borrow'] = $this->borrow_model->get_borrows_bywhere($where, 4);
		//热门产品
		$where = array();
		$where['borrow_type >'] = 2;
		$where['borrow_status >='] = 2;
		$where['id >'] = 1;
		$data['nborrow'] = $this->borrow_model->get_borrows_bywhere($where, 4);
		//var_dump($data['borrow']);
		
		//体验专区
		//$where = array('borrow_type >' => 1);
		//$data['tiyan'] = $this->borrow_model->get_borrows_bywhere($where, 1)[0];
		//新手专区
		$where = array();
		$where['borrow_type'] = 2;
		$where['borrow_status >='] = 2;
		$xinshou = $this->borrow_model->get_borrows_bywhere($where, 1); 
		if($xinshou) {
			$data['xinshou'] = $xinshou[0];
		}
		//p($data['xinshou']);
		$this->load->view('index/indexa',$data);
	}
	//登录
	/*
	public function logina(){
		$this->load->library('session');
		//$this->load->database();
		if($this->input->post()){
			$data['name'] = $this->input->post_get('user_name', TRUE);
			$data['password'] = $this->input->post_get('user_pass', TRUE);
			$url = $this->input->post_get('ret_url', TRUE);
			if(! $data['name']){
				$info['state'] = 0;
				$info['message'] = '请输入手机号!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			if(! $data['password']){
				$info['state'] = 0;
				$info['message'] = '请输入密码!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			$result = $this->member_model->get_member_byusername($data['name']);
			// if($result['is_ban'] == 1 || $result['type'] == 2){
	        	// $info['state'] = 0;
				// $info['message'] = '已被锁定,请联系客服人员!';
				// $this->output
			    // ->set_content_type('application/json', 'utf-8')
			    // ->set_output(json_encode($info))
				// ->_display();
			    // exit;
	        // }
			if($result['user_pass'] != md5(suny_encrypt($data['password'], $result['salt']))){
				$co = $this->member_model->get_code('', $data['name'], 2);
				if(! $co || $co['code'] != $data['password'] || $co['time'] < time()){
					$info['state'] = 0;
					$info['message'] = '手机验证码已过期或密码不正确!';
					$this->output
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($info))
					->_display();
					exit;
				}
			}else{
				$this->load->helper('cookie');
				delete_cookie('tcocd');
			}
	        // if($result['user_pass'] != md5(suny_encrypt($data['password'], $result['salt']))){
	        	// $info['state'] = 0;
				// $info['message'] = '用户名或密码不正确!';
				// $this->output
			    // ->set_content_type('application/json', 'utf-8')
			    // ->set_output(json_encode($info))
				// ->_display();
			    // exit;
	        // } 
	        $info['state'] = 1;
			$info['message'] = '成功!';
			$info['url'] = $url ? $url : '/account.html';
			$array = array(
                'quid' => $result['id']
            );
			send_sms($data['name'], '您的账号已登录,如果不是本人操作请修改密码!');
            $this->session->set_userdata($array);
			//
			
			$this->output
		    ->set_content_type('application/json', 'utf-8')
		    ->set_output(json_encode($info))
			->_display();
		    exit;

		}
		$this->load->view('index/login');
	}*/
	public function forget(){
		$this->load->helper('cookie');
		$phone = $this->input->cookie('phone');
		if($phone){
			$d['phone'] = suny_decrypt($phone);
		}else{
			$d['phone'] = '';
		}
		$this->load->view('index/foget', $d);
	}
	public function login(){
		$this->load->library('session');
		$this->load->helper('cookie');
		//$this->load->database();
		if($this->input->post()){
			//$data = $this->input->post(NULL, TRUE);
			$data['name'] = $this->input->post_get('phone', TRUE);
			$data['password'] = $this->input->post_get('user_pass', TRUE);
			$agree = $this->input->post_get('agree', TRUE);
			$url = $this->input->post_get('ret_url', TRUE);
			if(! $data['name']){
				$info['state'] = 0;
				$info['message'] = '请输入手机号!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			if(! $data['password']){
				$info['state'] = 0;
				$info['message'] = '请输入密码!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			$result = $this->member_model->get_member_byusername($data['name']);
			// if($result['is_ban'] == 1 || $result['type'] == 2){
	        	// $info['state'] = 0;
				// $info['message'] = '已被锁定,请联系客服人员!';
				// $this->output
			    // ->set_content_type('application/json', 'utf-8')
			    // ->set_output(json_encode($info))
				// ->_display();
			    // exit;
	        // }
			// if(isset($data['agree'])) {
				// $this->input->set_cookie("name",$data['name'], time() + 7*24*3600);
			// } else {
				// $this->input->set_cookie("name",'', time() - 1);
			// }
			if($result['user_pass'] != md5(suny_encrypt($data['password'], $result['salt']))){
				$co = $this->member_model->get_code('', $data['name'], 2);
				if(! $co || $co['code'] != $data['password'] || $co['time'] < time()){
					$info['state'] = 0;
					$info['message'] = '用户名或密码不正确!';
					$this->output
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($info))
					->_display();
					exit;
				}
			}else{
				
				delete_cookie('tcocd');
			}
	        /* if($result['user_pass'] != md5(suny_encrypt($data['password'], $result['salt']))){
	        	$info['state'] = 0;
				$info['message'] = '用户名或密码不正确!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
	        } */
	        $info['state'] = 1;
			$info['message'] = '成功!';
			$info['url'] = $url ? $url : '/account.html';
			$array = array(
                'quid' => $result['id']
            );
			//send_sms($data['name'], '您的账号已登录,如果不是本人操作请修改密码!');
            $this->session->set_userdata($array);
			if($agree){
				$cookie = array(
					'name'   => 'phone',
					'value'  => suny_encrypt($data['name']),
					'expire' => time()+100*86400
				);
				$this->input->set_cookie($cookie);
			}else{
				delete_cookie('phone');
			}
			$this->output
		    ->set_content_type('application/json', 'utf-8')
		    ->set_output(json_encode($info))
			->_display();
		    exit;

		}
		$phone = $this->input->cookie('phone');
		if($phone){
			$d['phone'] = suny_decrypt($phone);
		}else{
			$d['phone'] = '';
		}
		$d['url'] = $this->input->post_get('ret_url', TRUE);
		$this->load->view('index/logina', $d);
	}
	public function regs(){
		$post = $this->input->post(NULL, TRUE);
		$this->load->view('index/regs');
	}
	//注册
	public function reg(){
		if($post = $this->input->post(NULL, TRUE)){
			$data['user_name'] = $post['phone'];
			$data['user_pass'] = $post['user_pass'];
			$code['pcode'] = $post['pcode'];
			$data['reg_time'] = time();
			$data['reg_ip'] = $this->input->ip_address();
			$data['salt'] = salt();
			$data['type'] = intval($post['type']);
			$data['attribute'] = intval($post['attribute']);
			
			//1出借人或2融资人
			if(!in_array($data['attribute'], array(1, 2))) {
				$info['state'] = 0;
				$info['message'] = '请选择属性!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			//2企业或1个人
			if(!in_array($data['type'], array(1, 2))) {
				$info['state'] = 0;
				$info['message'] = '请选择注册类型!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			
			if($data['type'] == 2 && $data['attribute'] !=2 ) {
				$info['state'] = 0;
				$info['message'] = '公司用户只能作为融资人';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			
			if (! is_phone($data['user_name'])){
				$info['state'] = 0;
				$info['message'] = '请填写真实的手机号码!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			if($this->member_model->get_member_byusername($data['user_name'])){
				$info['state'] = 0;
				$info['message'] = '手机号码已被注册过!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			if (! is_password($data['user_pass'])){
				$info['state'] = 0;
				$info['message'] = '密码必须由字母和数字组成!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			$co = $this->member_model->get_code('', $data['user_name'], 1);
			if(! $co || $co['code'] != $code['pcode'] || $co['time'] < time()){
				$info['state'] = 0;
				$info['message'] = '手机验证码已过期或不正确!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}else{
				$this->load->helper('cookie');
				delete_cookie('tcocd');
			}
			if(intval($post['codeuid'])){
				$m_u = $this->member_model->get_m_bycodeuid(intval($post['codeuid']));
				if($m_u){
					$data['codeuid'] = intval($post['codeuid']);
				}
			}
			$data['user_pass'] = MD5(suny_encrypt($data['user_pass'], $data['salt']));
			$this->db->trans_begin();
			$m = $this->member_model->add_member($data);
			$datam = array(
				'uid' => $m,
				'money_freeze' => '0.00',
				'money_collect' => '0.00',
				'account_money' => '0.00',
				//'ty_money' => '6600.00',
				'max_time' => time()+86400*30
			);
			$datas = array(
				'uid' => $m,
				'phone_status' => 1
			);
			$datai = array(
				'uid' => $m,
				'phone' => $data['user_name']
			);
			$this->member_model->add_members_money($datam);
			$this->member_model->add_member_status($datas);
			$this->member_model->add_member_info($datai);
			//红包及体验金操作如下, 2019-2-11，红包有效期修改为180天
			$packet = array(
				array(
					'uid' => $m,
					'stime' => time(),
					'etime' => time()+86400*180,
					'money' => '100.00',
					'min_money' => '8000.00',
					'times' => '30',
					'addtime' => time()
				),
				array(
					'uid' => $m,
					'stime' => time(),
					'etime' => time()+86400*180,
					'money' => '50.00',
					'min_money' => '3000.00',
					'times' => '30',
					'addtime' => time()
				),
				array(
					'uid' => $m,
					'stime' => time(),
					'etime' => time()+86400*180,
					'money' => '30.00',
					'min_money' => '1500.00',
					'times' => '30',
					'addtime' => time()
				),
				array(
					'uid' => $m,
					'stime' => time(),
					'etime' => time()+86400*180,
					'money' => '8.00',
					'min_money' => '300.00',
					'times' => '30',
					'addtime' => time()
				),
			);
			$this->member_model->addall_packet($packet);
			if($this->db->trans_status() === TRUE){
				$this->db->trans_commit();
				$info['state'] = 1;
				$info['message'] = '注册成功!';
				$info['url'] = '/suny/login.html';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}else{
				$this->db->trans_rollback();
				$info['state'] = 0;
				$info['message'] = '注册失败!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
		}
		$type = $this->input->get('type');
		$d['type'] = $type ? 2 : 1;
		$this->load->view('index/regs', $d);
	}
	public function borrowbefor(){
		$this->load->view('index/borrowbefor');
	}
	public function foot() {
		$this->load->view('index/foot');
	}
	// public function borrow(){
		// if($post = $this->input->post(NULL, TRUE)){
			// $data['phone'] = $post['phone'];
			// $data['money'] = $post['user_pass'];
			// $code['pcode'] = $post['pcode'];
			// $data['add_time'] = time();
			// $data['name'] = $post['name'];
			// if (! $data['name']){
				// $info['state'] = 0;
				// $info['message'] = '请填写真实姓名!';
				// $this->output
			    // ->set_content_type('application/json', 'utf-8')
			    // ->set_output(json_encode($info))
				// ->_display();
			    // exit;
			// }
			// if (! $data['money']){
				// $info['state'] = 0;
				// $info['message'] = '请填写借款金额!';
				// $this->output
			    // ->set_content_type('application/json', 'utf-8')
			    // ->set_output(json_encode($info))
				// ->_display();
			    // exit;
			// }
			// if (! is_phone($data['phone'])){
				// $info['state'] = 0;
				// $info['message'] = '请填写真实的手机号码!';
				// $this->output
			    // ->set_content_type('application/json', 'utf-8')
			    // ->set_output(json_encode($info))
				// ->_display();
			    // exit;
			// }
			// if($this->member_model->get_borrow_my_byusername($data['phone'])){
				// $info['state'] = 0;
				// $info['message'] = '已申请过借款过!';
				// $this->output
			    // ->set_content_type('application/json', 'utf-8')
			    // ->set_output(json_encode($info))
				// ->_display();
			    // exit;
			// }
			// $co = $this->member_model->get_code('', $data['phone'], 1);
			// if(! $co || $co['code'] != $code['pcode'] || $co['time'] < time()){
				// $info['state'] = 0;
				// $info['message'] = '手机验证码已过期或不正确!';
				// $this->output
			    // ->set_content_type('application/json', 'utf-8')
			    // ->set_output(json_encode($info))
				// ->_display();
			    // exit;
			// }else{
				// $this->load->helper('cookie');
				// delete_cookie('tcocd');
			// }
			// $m = $this->member_model->add_borrow($data);
			// if($m){
				// $info['state'] = 1;
				// $info['message'] = '申请成功!';
				// $info['url'] = '/suny/borrow.html';
				// $this->output
			    // ->set_content_type('application/json', 'utf-8')
			    // ->set_output(json_encode($info))
				// ->_display();
			    // exit;
			// }else{
				// $info['state'] = 0;
				// $info['message'] = '申请失败!';
				// $this->output
			    // ->set_content_type('application/json', 'utf-8')
			    // ->set_output(json_encode($info))
				// ->_display();
			    // exit;
			// }
		// }
		// $this->load->view('index/borrow_');
	// }
	public function borrow() {
		if($p = $this->input->post(NULL, TRUE)){
			$d['type'] = $p['type'];
			$data['name'] = $p['name'];
			$d['jktpe'] = $p['jktpe'];
			$data['money'] = $p['money'];
			$d['day'] = $p['day'];
			$d['lx'] = $p['lx'];
			$d['yt'] = $p['yt'];
			$d['member'] = $p['member'];
			$d['car'] = $p['car'];
			if(empty($p['phone'])) {
				$this->error('手机号不能为空');
			}
			if(!is_phone($p['phone'])) {
				$this->error('请填写真实的手机号码');
			}
			//验证手机号是否重复
			$meminfo = $this->member_model->get_member_info_byphone($p['phone']);
			if(!empty($meminfo)) {
				$this->error('已提交过数据，不能重复提交，如有问题，请联系客服');
			}
			$data['add_time'] = strtotime($p['add_time']);
			$data['info'] = serialize($d);
			$data['phone'] = $p['phone'];
			//p($data);die;
			if($this->member_model->add_borrow($data)){
				$this->success('提交成功');
			} else {
				$this->error('提交失败');
			}
		}
		$this->load->view('index/borrowa');
	}
	//退出
	public function out(){
		$this->session->unset_userdata('quid');
		$url = base_url('/suny/login.html');
		redirect($url);
	}
	public function captcha(){
		$this->load->helper('captcha');
		$vals = array(
			//'word'      => 'Random word',
			'img_path'  => './code/',
			'img_url'   => $this->config->item('weburl') . 'code/',
			'img_width' => 100,
			'img_height'    => 35,
			'expiration'    => 300,
			'word_length'   => 4,
			'font_size' => 20,
			'img_id'    => 'Imageid',
			'font_path'	=> '/data/sftp/jmupc/web/font/STXINGKA.TTF',//STXINGKA LHANDW  FZSTK
			//'pool'      => '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
			'pool'      => '0123456789',
			/* 'colors'    => array(
				//'background' => array(60, 255, 40),
				//'border' => array(255, 255, 255),
				'text' => array(100, 0, 0),
				'grid' => array(0, 120, 0)
			) */
		);
		$cap = create_captcha($vals);
		$cookie = array(
			'name'   => 'tcocd',
			'value'  => MD5(suny_encrypt($cap['word'])),
			'expire' => $cap['time'],
		);
		$this->input->set_cookie($cookie);
		echo $cap['image'];//$cap['word'],$cap['image'];p($cap);
	}
	public function send(){
		if($post = $this->input->post(NULL, TRUE)){
			$data['user_name'] = $post['phone'];
			$code['tcode'] = $post['tcode'];
			if (! is_phone($data['user_name'])){
				$info['state'] = 0;
				$info['message'] = '请填写真实的手机号码!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			if($post['type'] == 'j'){
				if($this->member_model->get_borrow_my_byusername($data['user_name'])){
					$info['state'] = 0;
					$info['message'] = '已申请过借款过!';
					$this->output
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($info))
					->_display();
					exit;
				}
			}
			if($post['type'] != 'j' && $this->member_model->get_member_byusername($data['user_name'])){
				$info['state'] = 0;
				$info['message'] = '手机号码已存被注册过!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			$tcocd = $this->input->cookie('tcocd');
			if ($tcocd != MD5(suny_encrypt($code['tcode']))){
				$info['state'] = 0;
				$info['message'] = '图形验证码过期或不正确!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			$datas['tel'] = $data['user_name'];
			$datas['type'] = 1;
			$datas['code'] = mt_rand(0,9) . mt_rand(0,9) . mt_rand(0,9) . mt_rand(0,9);
			$datas['time'] = time() + 300;
			if($post['type'] == 'j'){
				$datas['content'] = '(5分钟内有效) 您的借款码为：';
			}else{
				$datas['content'] = '(5分钟内有效) 您的注册码为：';
			}
			if($this->member_model->add_code($datas)){
				if(send_sms($datas['tel'], $datas['content'], $datas['code'])){
					$info['state'] = 1;
					$info['message'] = '发送成功!';
					$this->output
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($info))
					->_display();
					exit;
				}else{
					$info['state'] = 0;
					$info['message'] = '发送失败,请联系客服!';
					$this->output
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($info))
					->_display();
					exit;
				}
			}else{
				$info['state'] = 0;
				$info['message'] = '发送失败,刷新后重试!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
		}
	}
	public function sends(){
		if($post = $this->input->post(NULL, TRUE)){
			$data['user_name'] = $post['phone'];
			if (! is_phone($data['user_name'])){
				$info['state'] = 0;
				$info['message'] = '请填写真实的手机号码!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			$tcocd = $this->input->cookie('tcocd');
			if ($tcocd != MD5(suny_encrypt($post['tcode']))){
				$info['state'] = 0;
				$info['message'] = '图形验证码过期或不正确!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			if(! $member = $this->member_model->get_member_byusername($data['user_name'])){
				$info['state'] = 0;
				$info['message'] = '链接服务器失败!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			$datas['tel'] = $data['user_name'];
			$datas['type'] = 2;
			$datas['code'] = mt_rand(0,9) . mt_rand(0,9) . mt_rand(0,9) . mt_rand(0,9);
			$datas['time'] = time() + 300;
			
			//重置原始密码
			$salt = salt();
			$user_pwd = mt_rand(1,9) . rand(12345, 67899);
			
			$datas['content'] = '(5分钟内有效) 您的登录码为：' . $datas['code'] . '  你的原始密码已重置为:' . $user_pwd;
			if($this->member_model->add_code($datas)){
				if(send_sms($datas['tel'], $datas['content'], '')){
					//重置原始密码
					$member['salt'] = $salt;
					$member['user_pass'] = MD5(suny_encrypt($user_pwd, $salt));
					if($member['id'] > 0) {
						$this->member_model->modify_member($member, $member['id']);
					}

					$info['state'] = 1;
					$info['message'] = '发送成功!';
					$this->output
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($info))
					->_display();
					exit;
				}else{
					$info['state'] = 0;
					$info['message'] = '发送失败,请联系客服!';
					$this->output
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($info))
					->_display();
					exit;
				}
			}else{
				$info['state'] = 0;
				$info['message'] = '发送失败,刷新后重试!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
		}
	}
	//注册协议
	public function contract() {
		$this->load->library('Tcpdf/Tcpdf'); 

		$tcpdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT,true, 'UTF-8', false);
		#var_dump($tcpdf);
		
		//设置作者，标题，文件属性
		$tcpdf->SetCreator('');
		$tcpdf->SetAuthor('th');
		$tcpdf->SetTitle('注册协议');
		$tcpdf->SetSubject('SUBJECT');
		$tcpdf->SetKeywords('PDF, TCPDF');
		// 设置页眉和页脚信息
		$tcpdf->setHeaderData('', 0, '', '上海童汇信息科技有限公司', array(0,0,0), array(0,0,0));
		$tcpdf->setFooterData(array(0,0,0), array(0,0,0));
		
		// 设置页眉和页脚字体
		$tcpdf->setHeaderFont(Array('stsongstdlight', '', '10'));
		$tcpdf->setFooterFont(Array('helvetica', '', '8'));

		//设置文档对齐，间距，字体，图片
		$tcpdf->SetCreator(PDF_CREATOR);
		$tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		$tcpdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

		//设置页眉页脚 边距
		$tcpdf->setHeaderMargin(PDF_MARGIN_HEADER);
		$tcpdf->setFooterMargin(PDF_MARGIN_FOOTER);
		
		//自动分页
		$tcpdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		$tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		$tcpdf->setFontSubsetting(true);
		#$tcpdf->setPageMark();
		//设置正文字体，大小   （stsongstdlight，网上说这个字体支持的文字更全，支持中文不乱码）
		$tcpdf->SetFont('stsongstdlight', '', 10);

		//创建页面，渲染PDF
		$tcpdf->AddPage();
		$data = array();
		$html = contract_build($data, 2);
		#$html = strip_tags($html);
		$tcpdf->writeHTML($html, true, false, true, true, ''); 
		$tcpdf->lastPage();

		//PDF输出   I：在浏览器中打开，D：下载，F：在服务器生成pdf ，S：只返回pdf的字符串

		#ob_clean();
		$tcpdf->Output('/data/sftp/test/web/tcpdf/download/aaa.pdf','I');
		#生成pdf文件
		#$tcpdf->Output('/data/sftp/test/web/tcpdf/download/aaa.pdf','F');
	}
}

