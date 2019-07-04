<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Cate extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->library('pagination');
		$this->load->model(array('cate/cate_model','member/member_model'));
		//$this->load->helper('url');
	}
	public function index(){
		$current_page = $this->uri->segment(3) ? $this->uri->segment(3) : 1;
        if($current_page > 0){
            $current_page = $current_page - 1;
        }else if($current_page < 0){
            $current_page = 0;
        }
		$per_page = 8;
        $offset = $current_page;
        $config['base_url'] = base_url('cate/index');
        $config['total_rows'] = $this->cate_model->get_cate_num();
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
        $this->pagination->initialize($config); 
        $data['totals'] = $config['total_rows'];
        $data['page'] = $this->pagination->create_links();
        $data['p'] = $current_page;
        $cate = $this->cate_model->get_cate($per_page, $offset * $per_page);
        $data['cate'] = $cate;
		$this->load->view('cate/cate', $data);
	}
	public function add(){
		if($p = $this->input->post(NULL, TRUE)){
			$data['name'] = $p['lname'];
			if(! $data['name']){
				$info['state'] = 0;
				$info['message'] = '分类名称不能为空!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			if($this->cate_model->get_cate_one('', $data['name'])){
				$info['state'] = 0;
				$info['message'] = '分类已存在!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			$data['addtime'] = time();
			if($this->cate_model->add_cate($data)){
				$info['state'] = 1;
				$info['message'] = '操作成功!';
				$info['url'] = '/cate.html';
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
		$this->load->view('cate/cateadd');
	}
	public function edit(){
		if($p = $this->input->post(NULL, TRUE)){
			$data['status'] = $p['status'] ? $p['status'] : 0;
			$id = $p['id'];
			if(! $id){
				$info['state'] = 0;
				$info['message'] = '数据有误!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			if($this->cate_model->up_cate($data, $id)){
				$info['state'] = 1;
				$info['message'] = '操作成功!';
				$info['url'] = '/cate.html';
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
		$d = $this->cate_model->get_cate_one($id);
		$this->load->view('cate/cateedit', $d);
	}
	public function goods(){
		$current_page = $this->uri->segment(3) ? $this->uri->segment(3) : 1;
        if($current_page > 0){
            $current_page = $current_page - 1;
        }else if($current_page < 0){
            $current_page = 0;
        }
		$per_page = 8;
        $offset = $current_page;
        $config['base_url'] = base_url('cate/goods');
        $config['total_rows'] = $this->cate_model->get_goods_num();
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
        $this->pagination->initialize($config); 
        $data['totals'] = $config['total_rows'];
        $data['page'] = $this->pagination->create_links();
        $data['p'] = $current_page;
        $cate = $this->cate_model->get_goods($per_page, $offset * $per_page);
        $data['cate'] = $cate;
		$this->load->view('cate/goods', $data);
	}
	public function gadd(){
		if($p = $this->input->post(NULL, TRUE)) {
			$data['cid'] = $p['cid'];
			$data['gname'] = $p['title'];
			$data['mark'] = $this->input->post('content');
			$data['img'] = $p['img'];
			$data['score'] = $p['score'];
			//$data['price'] = $p['price'];
			//$data['num'] = $p['num'];
			if(! $data['img']){
				$info['state'] = 0;
				$info['message'] = '请上传图片!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			if(! $data['cid']){
				$info['state'] = 0;
				$info['message'] = '请选择分类名称!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			if(! $data['gname']){
				$info['state'] = 0;
				$info['message'] = '名称不能为空!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			if($this->cate_model->get_goods_one('', $data['gname'])){
				$info['state'] = 0;
				$info['message'] = '名称已存在!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			if(! $data['score']){
				$info['state'] = 0;
				$info['message'] = '积分不能为空!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			/* if(! $data['num']){
				$info['state'] = 0;
				$info['message'] = '存量不能为空!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			} */
			if(! $data['mark']){
				$info['state'] = 0;
				$info['message'] = '详情不能为空!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			$data['addtime'] = time();
			if($this->cate_model->add_goods($data)){
				$info['state'] = 1;
				$info['message'] = '操作成功!';
				$info['url'] = '/cate/goods.html';
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
		$d['cate'] = $this->cate_model->get_cates(1);
		$this->load->view('cate/gadd', $d);
	}
	public function gedit(){
		if($p = $this->input->post(NULL, TRUE)){
			$data['gstatus'] = $p['gstatus'] ? $p['gstatus'] : 0;
			$id = $p['id'];
			if(! $id){
				$info['state'] = 0;
				$info['message'] = '数据有误!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			if($this->cate_model->up_goods($data, $id)){
				$info['state'] = 1;
				$info['message'] = '操作成功!';
				$info['url'] = '/cate/goods.html';
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
		$d = $this->cate_model->get_goods_one($id);
		$d['cate'] = $this->cate_model->get_cates();
		$this->load->view('cate/gedit', $d);
	}
	public function order(){
		$keywords = $this->input->post_get('keywords', TRUE);
		if($this->input->post()){
			if(! $keywords){
				$info['state'] = 0;
				$info['message'] = '请输入关键字!';
				$this->output
				    ->set_content_type('application/json', 'utf-8')
				    ->set_output(json_encode($info))
					->_display();
				    exit;
			}else{
				$info['state'] = 1;
				$info['message'] = urldecode($keywords);
				$this->output
				    ->set_content_type('application/json', 'utf-8')
				    ->set_output(json_encode($info))
					->_display();
				    exit;
			}
		}
		$keywords = urldecode($this->input->get('query', TRUE));
		if($keywords){
			$info = $this->member_model->get_member_info_byphone($keywords);
			if($info){
				$uid = $info['uid'];
			}
		}
		$uid = isset($uid) ? $uid : 0;
		$current_page = $this->uri->segment(3) ? $this->uri->segment(3) : 1;
        if($current_page > 0){
            $current_page = $current_page - 1;
        }else if($current_page < 0){
            $current_page = 0;
        }
		$per_page = 100;
        $offset = $current_page;
        $config['base_url'] = base_url('cate/order');
        $config['total_rows'] = $this->cate_model->get_order_num($uid);
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
        $cate = $this->cate_model->get_order($uid, $per_page, $offset * $per_page);
		foreach($cate as $k=>$v) {
			$meminfo = get_member_info($v['uid']);
			$cate[$k]['user_name'] = $meminfo['real_name'];
			$cate[$k]['user_phone'] = $meminfo['phone'];
		}
        $data['cate'] = $cate;
		$this->load->view('cate/order', $data);
	}
	public function oedit(){
		if($p = $this->input->post(NULL, TRUE)){
			$data['status'] = $p['status'] ? $p['status'] : 0;
			$data['ordernum'] = $p['ordernum'];
			$data['ordername'] = $p['ordername'];
			$address = $p['address'];
			$data['uptime'] = time();
			$data['admin_id'] = UID;
			$id = $p['id'];
			if(! $id){
				$info['state'] = 0;
				$info['message'] = '数据有误!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			if($data['status'] != 1){
				$info['state'] = 0;
				$info['message'] = '链接超时!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			if(! $data['ordername']){
				$info['state'] = 0;
				$info['message'] = '快递名称不能为空!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			if(! $data['ordernum']){
				$info['state'] = 0;
				$info['message'] = '快递单号不能为空!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			if(! $address){
				$info['state'] = 0;
				$info['message'] = '快递地址不能为空!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			$da = $this->cate_model->get_order_one($id);
			$addresses = unserialize($da['amark']);
			$addresses['address'] = $address;
			$data['amark'] = serialize($addresses);
			if($this->cate_model->up_order($data, $id)){
				$info['state'] = 1;
				$info['message'] = '操作成功!';
				$info['url'] = '/cate/order.html';
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
		$da = $this->cate_model->get_order_one($id);
		$info = unserialize($da['amark']);
		$d['address'] = $info['address'];
		$d['tel'] = $info['tel'];
		$d['realname'] = $info['realname'];
		$d['id'] = $id;
		$this->load->view('cate/oedit', $d);
	}
	public function allfh(){
		if($this->input->post()){
			$ids = $this->input->post_get('ids', TRUE);
			$ids = explode(',', substr($ids, 0, -1));
			if(! $ids){
				$info['state'] = 0;
				$info['message'] = '数据有误!';
				$this->output
				    ->set_content_type('application/json', 'utf-8')
				    ->set_output(json_encode($info))
					->_display();
			    exit;
			}else{
				$data['status'] = 1;
				$data['padmin_id'] = UID;
				$data['puptime'] = time();
				if($this->cate_model->get_order_lock($data, $ids)){
					$info['state'] = 1;
					$info['message'] = '操作成功!';
					$this->output
					    ->set_content_type('application/json', 'utf-8')
					    ->set_output(json_encode($info))
						->_display();
				    exit;
				}else{
					$info['state'] = 0;
					$info['message'] = '操作失败,请刷新后重试!';
					$this->output
					    ->set_content_type('application/json', 'utf-8')
					    ->set_output(json_encode($info))
						->_display();
				    exit;
				}
			}
		}
	}
}