<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mall extends Baseaccount {
	public function __construct() {
		parent::__construct();
		$this->load->library('pagination');
		$this->load->model(array('mall/cate_model', 'cj/cj_model'));
		//$this->load->helper('url');
	}
	public function index(){
		$current_page = $this->uri->segment(3) ? $this->uri->segment(3) : 1;
		$status = $this->input->get('query') ? $this->input->get('query') : 1;
        if($current_page > 0){
            $current_page = $current_page - 1;
        }else if($current_page < 0){
            $current_page = 0;
        }
		$per_page = 8;
        $offset = $current_page;
        $config['base_url'] = base_url('mall/index');
        $config['total_rows'] = $this->cate_model->get_goods_num($status);
        $config['per_page'] = $per_page;
		$config['page_query_string'] = FALSE;
		$config['first_link'] = '首页'; // 第一页显示   
		$config['last_link'] = '末页'; // 最后一页显示   
		$config['next_link'] = '下一页'; // 下一页显示   
		$config['prev_link'] = '上一页'; // 上一页显示   
		$config['cur_tag_open'] = ' <span class="current">'; // 当前页开始样式   
		$config['cur_tag_close'] = '</span>';   
		$config['num_links'] = 10;
		$config['uri_segment'] = 3;
		$config['use_page_numbers'] = TRUE;
		$config['reuse_query_string'] = TRUE;
        $this->pagination->initialize($config); 
        $data['totals'] = $config['total_rows'];
        $data['page'] = $this->pagination->create_links();
        $data['p'] = $current_page;
		$data['status'] = $status;
        $borrow = $this->cate_model->get_goods($status, $per_page, $offset * $per_page);
        $data['borrow'] = $borrow;
		$data['cate'] = $this->cate_model->get_cates();
		$this->load->view('account/mall_v1', $data);
	}
	public function lists(){
		$current_page = $this->uri->segment(3) ? $this->uri->segment(3) : 1;
        if($current_page > 0){
            $current_page = $current_page - 1;
        }else if($current_page < 0){
            $current_page = 0;
        }
		$per_page = 8;
        $offset = $current_page;
        $config['base_url'] = base_url('mall/lists');
        $config['total_rows'] = $this->cate_model->get_order_num(QUID);
        $config['per_page'] = $per_page;
		$config['page_query_string'] = FALSE;
		$config['first_link'] = '首页'; // 第一页显示   
		$config['last_link'] = '末页'; // 最后一页显示   
		$config['next_link'] = '下一页'; // 下一页显示   
		$config['prev_link'] = '上一页'; // 上一页显示   
		$config['cur_tag_open'] = ' <span class="current">'; // 当前页开始样式   
		$config['cur_tag_close'] = '</span>';   
		$config['num_links'] = 10;
		$config['uri_segment'] = 3;
		$config['use_page_numbers'] = TRUE;
		$config['reuse_query_string'] = TRUE;
        $this->pagination->initialize($config); 
        $data['totals'] = $config['total_rows'];
        $data['page'] = $this->pagination->create_links();
        $data['p'] = $current_page;
        $cate = $this->cate_model->get_order(QUID, $per_page, $offset * $per_page);
		
        $data['cate'] = $cate;
		$this->load->view('account/dhlist_v1', $data);
	}
	public function addlist(){
		if($p = $this->input->post(NULL, TRUE)) {
			$data['gid'] = intval($p['id']);
			if($data['gid'] == 11) {
				$this->times();
			}
			$data['num'] = intval($p['num']) ? intval($p['num']) : 1;
			$data['aid'] = intval($p['aid']) ? intval($p['aid']) : '00';
			if(! intval($data['gid'])){
				$info['state'] = 0;
				$info['message'] = '商品数据有误!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			$goods = $this->cate_model->get_goods_one($data['gid']);
			if(! $goods){
				$info['state'] = 0;
				$info['message'] = '商品数据有误或已下架!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			if(! $data['num'] > 0){
				$info['state'] = 0;
				$info['message'] = '数量不能为空!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			if(! $goods['num'] > 0){
				$info['state'] = 0;
				$info['message'] = '库存不足,您可以兑换其他商品!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			if($goods['num'] < $data['num']){
				$info['state'] = 0;
				$info['message'] = '库存数量不足!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			if(! intval($data['aid'])){
				$info['state'] = 0;
				$info['message'] = '请选择地址!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			$address = $this->cate_model->get_address_one($data['aid'], QUID);
			if(! $address){
				$info['state'] = 0;
				$info['message'] = '地址不存在!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			$total = round($goods['score'] * $data['num'], 2);
			if($total > get_member_info(QUID)['totalscore']){
				$info['state'] = 0;
				$info['message'] = '可用积分不够,请修改兑换数量!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			//
			$data['sscore'] = $total;
			$data['amark'] = serialize($address);
			$data['addtime'] = time();
			$data['uid'] = QUID;
			$this->db->trans_begin();
			$this->cate_model->add_order($data);
			$this->cate_model->up_goods_num($data['gid'], $data['num']);
			$this->cate_model->up_userinfo_score(QUID, $total);
			if ($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
				$info['state'] = 0;
				$info['message'] = '操作失败!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}else{
				$this->db->trans_commit();
				$info['state'] = 1;
				$info['message'] = '操作成功!';
				$info['url'] = '/mall/lists.html';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
		}
		$d['info'] = get_member_info(QUID);
		$d['address'] = $this->cate_model->get_address_more(QUID);
		$gid = $this->uri->segment(3);
		$d['goods'] = $this->cate_model->get_goods_one($gid);
		if($gid == 11) {
			$this->load->view('account/add_cj', $d);
		} else {
			$this->load->view('account/add_dh', $d);
		}
		
	}
	/** 兑换抽奖次数 */
	public function times() {
		if($p = $this->input->post(NULL, TRUE)) {
			$data['gid'] = intval($p['id']);
			$data['num'] = intval($p['num']) ? intval($p['num']) : 1;
			//$data['aid'] = intval($p['aid']) ? intval($p['aid']) : '00';
			if(! intval($data['gid'])){
				$info['state'] = 0;
				$info['message'] = '商品数据有误!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			$goods = $this->cate_model->get_goods_one($data['gid']);
			if(! $goods){
				$info['state'] = 0;
				$info['message'] = '商品数据有误或已下架!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			if(! $data['num'] > 0){
				$info['state'] = 0;
				$info['message'] = '数量不能为空!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			if(! $goods['num'] > 0){
				$info['state'] = 0;
				$info['message'] = '库存不足,您可以兑换其他商品!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			if($goods['num'] < $data['num']){
				$info['state'] = 0;
				$info['message'] = '库存数量不足!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			$total = round($goods['score'] * $data['num'], 2);
			if($total > get_member_info(QUID)['totalscore']){
				$info['state'] = 0;
				$info['message'] = '可用积分不够,请修改兑换数量!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			$data['sscore'] = $total;
			$data['amark'] = '';
			$data['addtime'] = time();
			$data['uid'] = QUID;
			
			$data['status'] = 1;
			$data['puptime'] = time();
			$this->db->trans_begin();
			$this->cate_model->add_order($data);
			$this->cate_model->up_goods_num($data['gid'], $data['num']);
			$this->cate_model->up_userinfo_score(QUID, $total);
			$this->cate_model->up_userinfo_times(QUID, $data['num']);
			if ($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
				$info['state'] = 0;
				$info['message'] = '操作失败!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}else{
				$this->db->trans_commit();
				$info['state'] = 1;
				$info['message'] = '操作成功!';
				$info['url'] = '/mall/lists.html';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
		}
	}
	public function address(){
		$current_page = $this->uri->segment(3) ? $this->uri->segment(3) : 1;
        if($current_page > 0){
            $current_page = $current_page - 1;
        }else if($current_page < 0){
            $current_page = 0;
        }
		$per_page = 8;
        $offset = $current_page;
        $config['base_url'] = base_url('mall/address');
        $config['total_rows'] = $this->cate_model->get_address_num(QUID);
        $config['per_page'] = $per_page;
		$config['page_query_string'] = FALSE;
		$config['first_link'] = '首页'; // 第一页显示   
		$config['last_link'] = '末页'; // 最后一页显示   
		$config['next_link'] = '下一页'; // 下一页显示   
		$config['prev_link'] = '上一页'; // 上一页显示   
		$config['cur_tag_open'] = ' <span class="current">'; // 当前页开始样式   
		$config['cur_tag_close'] = '</span>';   
		$config['num_links'] = 10;
		$config['uri_segment'] = 3;
		$config['use_page_numbers'] = TRUE;
		$config['reuse_query_string'] = TRUE;
        $this->pagination->initialize($config); 
        $data['totals'] = $config['total_rows'];
        $data['page'] = $this->pagination->create_links();
        $data['p'] = $current_page;
        $borrow = $this->cate_model->get_address(QUID, $per_page, $offset * $per_page);
        $data['borrow'] = $borrow;
		$this->load->view('account/address_v1', $data);
	}
	public function add(){
		if($p = $this->input->post(NULL, TRUE)) {
			$data['tel'] = $p['tel'];
			$data['address'] = $p['address'];
			$data['realname'] = $p['realname'];
			$data['uid'] = QUID;
			if(! $data['realname']){
				$info['state'] = 0;
				$info['message'] = '真实姓名不能为空!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			if(! is_phone($data['tel'])){
				$info['state'] = 0;
				$info['message'] = '手机号不正确!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			if(! $data['address']){
				$info['state'] = 0;
				$info['message'] = '地址不能为空!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			if($this->cate_model->get_address_one('', QUID, $data['address'])){
				$info['state'] = 0;
				$info['message'] = '地址已存在!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			$data['addtime'] = time();
			if($this->cate_model->add_address($data)){
				$info['state'] = 1;
				$info['message'] = '操作成功!';
				$info['url'] = '/mall/address.html';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}else{
				$info['state'] = 0;
				$info['message'] = '操作失败!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
		}
		$this->load->view('account/add_address');
	}
	public function edit(){
		if($p = $this->input->post(NULL, TRUE)) {
			$id = $p['id'];
			$data['tel'] = $p['tel'];
			$data['address'] = $p['address'];
			$data['realname'] = $p['realname'];
			$data['uid'] = QUID;
			$a = $this->cate_model->get_address_one($id, QUID);
			if(! $a || ! $id){
				$info['state'] = 0;
				$info['message'] = '数据有误,请刷新后再试!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			if(! $data['realname']){
				$info['state'] = 0;
				$info['message'] = '真实姓名不能为空!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			if(! is_phone($data['tel'])){
				$info['state'] = 0;
				$info['message'] = '手机号不正确!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			if(! $data['address']){
				$info['state'] = 0;
				$info['message'] = '地址不能为空!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			if($a['address'] != $data['address']  && $b = $this->cate_model->get_address_one('', QUID, $data['address'])){
				$info['state'] = 0;
				$info['message'] = '地址已存在!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			$data['addtime'] = time();
			if($this->cate_model->up_address($data, $id, QUID)){
				$info['state'] = 1;
				$info['message'] = '操作成功!';
				$info['url'] = '/mall/address.html';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}else{
				$info['state'] = 0;
				$info['message'] = '操作失败!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
		}
		$id = $this->uri->segment(3);
		$d = $this->cate_model->get_address_one($id, QUID);
		$this->load->view('account/edit_address', $d);
	}
	//
	public function prize(){
		echo '活动已结束，如需查看相关信息，请联系客服';die;
		$current_page = $this->uri->segment(3) ? $this->uri->segment(3) : 1;
        if($current_page > 0){
            $current_page = $current_page - 1;
        }else if($current_page < 0){
            $current_page = 0;
        }
		$per_page = 6;
        $offset = $current_page;
        $config['base_url'] = base_url('mall/prize');
        $config['total_rows'] = $this->cj_model->get_order_num(QUID);
        $config['per_page'] = $per_page;
		$config['page_query_string'] = FALSE;
		$config['first_link'] = '首页'; // 第一页显示   
		$config['last_link'] = '末页'; // 最后一页显示   
		$config['next_link'] = '下一页'; // 下一页显示   
		$config['prev_link'] = '上一页'; // 上一页显示   
		$config['cur_tag_open'] = ' <span class="current">'; // 当前页开始样式   
		$config['cur_tag_close'] = '</span>';   
		$config['num_links'] = 10;
		$config['uri_segment'] = 3;
		$config['use_page_numbers'] = TRUE;
		$config['reuse_query_string'] = TRUE;
        $this->pagination->initialize($config); 
        $data['totals'] = $config['total_rows'];
        $data['page'] = $this->pagination->create_links();
        $data['p'] = $current_page;
        $cate = $this->cj_model->get_order(QUID, 0, $per_page, $offset * $per_page);
        $data['cate'] = $cate;
		$this->load->view('account/prize_v1', $data);
	}
	
	/** 卡片兑换 */
	public function zcard() {
		echo '活动已结束，如需查看相关信息，请联系客服';die;
		$this->load->model('zcard/zcard_model');
		$current_page = $this->uri->segment(3) ? $this->uri->segment(3) : 1;
        if($current_page > 0){
            $current_page = $current_page - 1;
        }else if($current_page < 0){
            $current_page = 0;
        }
		$per_page = 8;
        $offset = $current_page;
        $config['base_url'] = base_url('mall/zcard');
        $config['total_rows'] = $this->zcard_model->get_zcard_order_num(QUID);
        $config['per_page'] = $per_page;
		$config['page_query_string'] = FALSE;
		$config['first_link'] = '首页'; // 第一页显示   
		$config['last_link'] = '末页'; // 最后一页显示   
		$config['next_link'] = '下一页'; // 下一页显示   
		$config['prev_link'] = '上一页'; // 上一页显示   
		$config['cur_tag_open'] = ' <span class="current">'; // 当前页开始样式   
		$config['cur_tag_close'] = '</span>';   
		$config['num_links'] = 10;
		$config['uri_segment'] = 3;
		$config['use_page_numbers'] = TRUE;
		$config['reuse_query_string'] = TRUE;
        $this->pagination->initialize($config); 
        $data['totals'] = $config['total_rows'];
        $data['page'] = $this->pagination->create_links();
        $data['p'] = $current_page;
        $zcard = $this->zcard_model->get_zcard_order_list($per_page, $offset * $per_page, QUID);
        $data['zcard'] = $zcard;
		$data['product'] = [
			'1' => '一等奖',
			'2' => '二等奖',
			'3' => '三等奖',
			'4' => '积分兑换6分',
			'5' => '出借红包200元',
			'6' => '出借红包500元'
		];
		$this->load->view('account/zcard', $data);
	}
	/** 福袋兑换 */
	public function zcash() {
		echo '活动已结束，如需查看相关信息，请联系客服';die;
		$this->load->model('cj/zcash_model');
		$current_page = $this->uri->segment(3) ? $this->uri->segment(3) : 1;
        if($current_page > 0){
            $current_page = $current_page - 1;
        }else if($current_page < 0){
            $current_page = 0;
        }
		$per_page = 8;
        $offset = $current_page;
        $config['base_url'] = base_url('mall/zcash');
        $config['total_rows'] = $this->zcash_model->get_zcash_order_num(QUID);
        $config['per_page'] = $per_page;
		$config['page_query_string'] = FALSE;
		$config['first_link'] = '首页'; // 第一页显示   
		$config['last_link'] = '末页'; // 最后一页显示   
		$config['next_link'] = '下一页'; // 下一页显示   
		$config['prev_link'] = '上一页'; // 上一页显示   
		$config['cur_tag_open'] = ' <span class="current">'; // 当前页开始样式   
		$config['cur_tag_close'] = '</span>';   
		$config['num_links'] = 10;
		$config['uri_segment'] = 3;
		$config['use_page_numbers'] = TRUE;
		$config['reuse_query_string'] = TRUE;
        $this->pagination->initialize($config); 
        $data['totals'] = $config['total_rows'];
        $data['page'] = $this->pagination->create_links();
        $data['p'] = $current_page;
        $zcash = $this->zcash_model->get_zcash_order_list($per_page, $offset * $per_page, QUID);
        $data['zcash'] = $zcash;
		$this->load->view('account/zcash', $data);
	}
	
	/** 金优果兑换 */
	public function ztree() {
		$this->load->model('theme/ztrees_model');
		$current_page = $this->uri->segment(3) ? $this->uri->segment(3) : 1;
        if($current_page > 0){
            $current_page = $current_page - 1;
        }else if($current_page < 0){
            $current_page = 0;
        }
		$per_page = 8;
        $offset = $current_page;
        $config['base_url'] = base_url('mall/ztree');
        $config['total_rows'] = $this->ztrees_model->get_ztrees_order_num(QUID);
        $config['per_page'] = $per_page;
		$config['page_query_string'] = FALSE;
		$config['first_link'] = '首页'; // 第一页显示   
		$config['last_link'] = '末页'; // 最后一页显示   
		$config['next_link'] = '下一页'; // 下一页显示   
		$config['prev_link'] = '上一页'; // 上一页显示   
		$config['cur_tag_open'] = ' <span class="current">'; // 当前页开始样式   
		$config['cur_tag_close'] = '</span>';   
		$config['num_links'] = 10;
		$config['uri_segment'] = 3;
		$config['use_page_numbers'] = TRUE;
		$config['reuse_query_string'] = TRUE;
        $this->pagination->initialize($config); 
        $data['totals'] = $config['total_rows'];
        $data['page'] = $this->pagination->create_links();
        $data['p'] = $current_page;
        $zcash = $this->ztrees_model->get_ztrees_order_list($per_page, $offset * $per_page, QUID);
        $data['zcash'] = $zcash;
		$data['trees_prize'] = array(
			'1' => 128, // '128元红包',
			'2' => 300, //'300元红包',
			'3' => 900, //'900元红包',
			'4' => 2100, //'2100元红包',
			'5' => 3700, //'3700元红包',
			'6' => 5800, //'5800元红包'
			'7' => 8800,
			'8' => 200,
			'9' => 220,
			'10' => 240,
			'11' => 260,
			'12' => 280,
			'13' => '388投资红包'
		);
		$ztrees = $this->ztrees_model->get_ztrees_byuid(QUID);
		$data['ztrees'] = $ztrees;
		// 计算总兑换数量
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
		$data['dh_total'] = $dh_total;
		$this->load->view('account/ztree', $data);
	}
	
	/** 518活动兑换 */
	public function zdraw() {
		$this->load->model('theme/zdraw_model');
		$current_page = $this->uri->segment(3) ? $this->uri->segment(3) : 1;
        if($current_page > 0){
            $current_page = $current_page - 1;
        }else if($current_page < 0){
            $current_page = 0;
        }
		$per_page = 8;
        $offset = $current_page;
        $config['base_url'] = base_url('mall/zdraw');
        $config['total_rows'] = $this->zdraw_model->get_zdraw_order_num(QUID);
        $config['per_page'] = $per_page;
		$config['page_query_string'] = FALSE;
		$config['first_link'] = '首页'; // 第一页显示   
		$config['last_link'] = '末页'; // 最后一页显示   
		$config['next_link'] = '下一页'; // 下一页显示   
		$config['prev_link'] = '上一页'; // 上一页显示   
		$config['cur_tag_open'] = ' <span class="current">'; // 当前页开始样式   
		$config['cur_tag_close'] = '</span>';   
		$config['num_links'] = 10;
		$config['uri_segment'] = 3;
		$config['use_page_numbers'] = TRUE;
		$config['reuse_query_string'] = TRUE;
        $this->pagination->initialize($config); 
        $data['totals'] = $config['total_rows'];
        $data['page'] = $this->pagination->create_links();
        $data['p'] = $current_page;
        $zdraw = $this->zdraw_model->get_zdraw_order_list($per_page, $offset * $per_page, QUID);
        $data['zdraw'] = $zdraw;
		$this->load->view('account/zdraw', $data);
	}
}