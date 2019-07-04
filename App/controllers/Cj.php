<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Cj extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->library('pagination');
		$this->load->model(array('cj/cj_model','member/member_model'));
		//$this->load->helper('url');
	}
	
	public function goods(){
		$current_page = $this->uri->segment(3) ? $this->uri->segment(3) : 1;
        if($current_page > 0){
            $current_page = $current_page - 1;
        }else if($current_page < 0){
            $current_page = 0;
        }
		$per_page = 12;
        $offset = $current_page;
        $config['base_url'] = base_url('cj/goods');
        $config['total_rows'] = $this->cj_model->get_goods_num();
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
        $cate = $this->cj_model->get_goods($per_page, $offset * $per_page);
        $data['cate'] = $cate;
		$this->load->view('cj/goods', $data);
	}
	public function gadd(){
		if($p = $this->input->post(NULL, TRUE)) {
			$data['name'] = $p['name'];
			$data['probability'] = $p['probability'];
			$data['num'] = $p['num'];
			$data['img'] = $p['img'];
			$data['types'] = $p['types'];
			if(! $data['img']){
				$info['state'] = 0;
				$info['message'] = '请上传图片!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			if(! $data['name']){
				$info['state'] = 0;
				$info['message'] = '名称不能为空!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			if($this->cj_model->get_goods_one('', $data['name'])){
				$info['state'] = 0;
				$info['message'] = '名称已存在!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			if(! $data['probability']){
				$info['state'] = 0;
				$info['message'] = '概率不能为空!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			if(! $data['num']){
				$info['state'] = 0;
				$info['message'] = '数量不能为空!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			if(! $data['types']){
				$info['state'] = 0;
				$info['message'] = '类别不能为空!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			$data['addtime'] = time();
			$data['admin_id'] = UID;
			if($this->cj_model->add_goods($data)){
				$info['state'] = 1;
				$info['message'] = '操作成功!';
				$info['url'] = '/cj/goods.html';
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
		$this->load->view('cj/gadd', $d);
	}
	public function gedit(){
		if($p = $this->input->post(NULL, TRUE)){
			$data['status'] = $p['gstatus'];
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
			if($this->cj_model->up_goods($data, $id)){
				$info['state'] = 1;
				$info['message'] = '操作成功!';
				$info['url'] = '/cj/goods.html';
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
		$d = $this->cj_model->get_goods_one($id);
		$this->load->view('cj/gedit', $d);
	}
	/*public function order(){
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
        $config['base_url'] = base_url('cj/order');
        $config['total_rows'] = $this->cj_model->get_order_num($uid);
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
        $cate = $this->cj_model->get_order($uid, $per_page, $offset * $per_page);
		foreach($cate as $k=>$v) {
			$meminfo = get_member_info($v['uid']);
			$cate[$k]['user_name'] = $meminfo['real_name'];
			$cate[$k]['user_phone'] = $meminfo['phone'];
		}
        $data['cate'] = $cate;
		$this->load->view('cj/order', $data);
	}*/
	public function order(){
		/* 
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
		} */
		$keywords = $this->input->get(null, true);
        $keyname = "";
		if($keywords){
			$info = $this->member_model->get_member_info_byphone($keywords['skey']);
			if($info){
				$uid = $info['uid'];
			}			
			if($keywords['keyname']){
				$keyname = $keywords['keyname'];
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
        $config['base_url'] = base_url('cj/order');
        $config['total_rows'] = $this->cj_model->get_order_new_num($uid,$keyname);
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
        $cate = $this->cj_model->get_order($uid,$keyname, $per_page, $offset * $per_page);
        $group = $this->cj_model->get_group_activity();
		foreach($cate as $k=>$v) {
			$meminfo = get_member_info($v['uid']);
			$cate[$k]['user_name'] = $meminfo['real_name'];
			$cate[$k]['user_phone'] = $meminfo['phone'];
		}
        $data['cate'] = $cate;
        $data['group'] = $group;
	

		$this->load->view('cj/order', $data);
	}
	public function oedit(){
		if($p = $this->input->post(NULL, TRUE)){
			$data['type'] = $p['status'] ? $p['status'] : 0;
			$data['mark'] = $p['mark'];
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
			if($data['type'] != 1){
				$info['state'] = 0;
				$info['message'] = '链接超时!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			if(! $data['mark']){
				$info['state'] = 0;
				$info['message'] = '备注不能为空!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			if($this->cj_model->up_order($data, $id)){
				$info['state'] = 1;
				$info['message'] = '操作成功!';
				$info['url'] = '/cj/order.html';
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
		$da = $this->cj_model->get_order_one($id);
		$this->load->view('cj/oedit', $da);
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
				$data['type'] = 1;
				$data['padmin_id'] = UID;
				$data['puptime'] = time();
				if($this->cj_model->get_order_lock($data, $ids)){
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
	public function zcard() {
		$this->load->model('zcard/zcard_model');
		$data = array();
		$where = array();
		$search = $this->input->get(null, true);
		if(isset($search['name'])) {
			$where['name'] = trim(trim($search['name']), '\t');
			$data['name'] = $where['name'];
		}
		$current_page = empty($this->uri->segment(3)) ? 0 : intval($this->uri->segment(3)) - 1;
		$per_page = 50;
        $offset = $current_page;
        $config['base_url'] = base_url('cj/zcard');
        $config['total_rows'] = $this->zcard_model->get_zcard_nums($where);
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
        $zcard = $this->zcard_model->get_zcard_lists($per_page, $per_page * $current_page, $where);
        $data['zcard'] = $zcard;
		//p($data);die;
		$this->load->view('cj/zcard', $data);
	}
	
	/** 卡片兑奖信息 */
	public function zcard_order() {
		$this->load->model('zcard/zcard_model');
		$data = array();
		$where = array();
		$search = $this->input->get(null, true);
		if(isset($search['name'])) {
			$where['name'] = trim(trim($search['name']), '\t');
			$data['name'] = $where['name'];
		}
		$current_page = empty($this->uri->segment(3)) ? 0 : intval($this->uri->segment(3)) - 1;
		$per_page = 50;
        $offset = $current_page;
        $config['base_url'] = base_url('cj/zcard_order');
        $config['total_rows'] = $this->zcard_model->get_order_nums($where);
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
        $order = $this->zcard_model->get_order_lists($per_page, $per_page * $current_page, $where);
        $data['order'] = $order;
		$data['product'] = [
			'1' => '一等奖',
			'2' => '二等奖',
			'3' => '三等奖',
			'4' => '积分兑换6分',
			'5' => '出借红包200元',
			'6' => '出借红包500元'
		];
			
		//p($data);die;
		$this->load->view('cj/zcard_order', $data);
	}
	
	/** 卡片处理页面 */
	public function deal_order_ui() {
		$id = $this->uri->segment(3);
		$data['id'] = intval($id);
		$this->load->view('/cj/deal_order_ui', $data);
	}
	/** 卡片兑奖处理 */
	public function deal_zcard_order() {
		$this->load->model('zcard/zcard_model');
		$post = $this->input->post(null, true);
		$id = intval($post['id']);
		$order = $this->zcard_model->get_order_by_id($id);
		$order['puptime'] = time();
		$order['padmin_id'] = UID;
		$order['remark'] = mb_substr($post['remark'], 0, 200);
		$order['status'] = 1;
		if(!$this->zcard_model->modify_order($order)) {
			$this->error('操作失败');
		} else {
			$this->success('操作成功');
		}
	}
	
	/** 大乐透 添加 */
	public function dlt_save() {
		if(IS_POST) {
			$this->load->model(array('cj/dlt_model'));
			$post = $this->input->post(null, true);
			$data = [];
			if(strlen($post['phone']) != 11) {
				$this->error("手机号错误");
			}
			$data['phone'] = $post['phone'];
			
			foreach($post['num'] as $v) {
				if(empty($v)) {
					$this->error("所选号码不能为空");
				}
			}
			$count = count($post['num']);
			if($count != 5) {
				$this->error("所选号码必须是5个");
			}
			// 去重复
			$post['num'] = array_unique($post['num']);
			if($count != count($post['num'])) {
				$this->error("所选号码不能重复");
			}
			
			if(count($post['num']) != 5) {
				$this->error("所选号码必须是5个");
			}
			$data['num'] = implode(',', $post['num']);
			$data['status'] = 0;
			$data['addtime'] = time();
			if($this->dlt_model->save($data)) {
				$this->success('操作成功');
			} else {
				$this->error('操作失败');
			}
		}
	}
	
	/** 大乐透 列表 */
	public function dlt_list() {
		$this->load->model('cj/dlt_model');
		$data = array();
		$where = array();
		$search = $this->input->get(null, true);
		if(isset($search['phone'])) {
			$where['phone'] = trim(trim($search['phone']), '\t');
			$data['phone'] = $where['phone'];
		}
		if(isset($search['rname'])) {
			$where['rname'] = trim(trim($search['rname']), '\t');
			$data['rname'] = $where['rname'];
		}
		if(!empty($search['time'])) {
			$data['time'] = $search['time'];
			$where['time'] = explode(' ', $search['time']);
		}
		$current_page = empty($this->uri->segment(3)) ? 0 : intval($this->uri->segment(3)) - 1;
		$per_page = 50;
        $offset = $current_page;
        $config['base_url'] = base_url('cj/dlt_list');
        $config['total_rows'] = $this->dlt_model->get_dlt_nums($where);
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
        $dlt = $this->dlt_model->get_dlt_alists($per_page, $per_page * $current_page, $where);
        $data['dlt'] = $dlt;
		//p($data);die;
		$this->load->view('cj/dlt', $data);
	}
	
	//导出 export
	public function dlt_export() {
		$this->load->model('cj/dlt_model');
		$data = array();
		$where = array();
		$search = $this->input->get(null, true);
		if(isset($search['phone'])) {
			$where['phone'] = trim(trim($search['phone']), '\t');
			$data['phone'] = $where['phone'];
		}
		if(isset($search['rname'])) {
			$where['rname'] = trim(trim($search['rname']), '\t');
			$data['rname'] = $where['rname'];
		}
		if(!empty($search['time'])) {
			$data['time'] = $search['time'];
			$where['time'] = explode(' ', $search['time']);
		}
		
		$numbers = $this->dlt_model->get_dlt_nums($where);
        $data['invest'] = $this->dlt_model->get_dlt_alists($numbers, 0, $where);
		//p($data['invest']);die;
		
		$all = $data['invest'];//$this->borrow_model->get_borrow_investor_excel($time1,$time2);
		$this->load->helper('common');
		$this->load->library('PHPExcel');
		$resultPHPExcel = new PHPExcel();
		$resultPHPExcel->getActiveSheet()->setCellValue('A1', 'ID');
		$resultPHPExcel->getActiveSheet()->setCellValue('B1', '真实姓名');
		$resultPHPExcel->getActiveSheet()->setCellValue('C1', '手机号');
		$resultPHPExcel->getActiveSheet()->setCellValue('D1', '身份证号');
		$resultPHPExcel->getActiveSheet()->setCellValue('E1', '选中号码');
		$resultPHPExcel->getActiveSheet()->setCellValue('F1', '倍数');
		$resultPHPExcel->getActiveSheet()->setCellValue('G1', '状态');
		$resultPHPExcel->getActiveSheet()->setCellValue('H1', '添加时间');
		$i = 1;
		foreach($all as $k => $v){
			$i++;
			$resultPHPExcel->getActiveSheet()->setCellValue('A'.$i, $v['id']);
			$resultPHPExcel->getActiveSheet()->setCellValue('B'.$i, $v['real_name']);
			$resultPHPExcel->getActiveSheet()->setCellValue('C'.$i, ' '.$v['phone']);
			$resultPHPExcel->getActiveSheet()->setCellValue('D'.$i, ' '.$v['idcard']);
			$resultPHPExcel->getActiveSheet()->setCellValue('E'.$i, $v['num']);
			$resultPHPExcel->getActiveSheet()->setCellValue('F'.$i, $v['multiple']);
			$resultPHPExcel->getActiveSheet()->setCellValue('G'.$i, $v['status'] ? '已处理' : '未处理');
			$resultPHPExcel->getActiveSheet()->setCellValue('H'.$i, date('Y-m-d H:i', $v['addtime']));
		}
		$outputFileName = '大乐透.xls'; 
		$xlsWriter = new PHPExcel_Writer_Excel5($resultPHPExcel); 
		header("Content-Type: application/force-download"); 
		header("Content-Type: application/octet-stream"); 
		header("Content-Type: application/download"); 
		header('Content-Disposition:inline;filename="'.$outputFileName.'"'); 
		header("Content-Transfer-Encoding: binary"); 
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
		header("Pragma: no-cache");
		$xlsWriter->save( "php://output" );
	}
	
	/** 2019-1-28 抽卡片记录 */
	public function zcard_detail() {
		$this->load->model('zcard/zcard_model');
		$data = array();
		$where = array();
		$search = $this->input->get(null, true);
		if(isset($search['name'])) {
			$where['name'] = trim(trim($search['name']), '\t');
			$data['name'] = $where['name'];
		}
		$current_page = empty($this->uri->segment(3)) ? 0 : intval($this->uri->segment(3)) - 1;
		$per_page = 50;
        $offset = $current_page;
        $config['base_url'] = base_url('cj/zcard_detail');
        $config['total_rows'] = $this->zcard_model->get_detail_nums($where);
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
        $detail = $this->zcard_model->get_detail_lists($per_page, $per_page * $current_page, $where);
        $data['detail'] = $detail;
		//p($data);die;
		$this->load->view('cj/zcard_detail', $data);
	}
	
	/** 福袋列表 */
	public function zcash() {
		$this->load->model('cj/zcash_model');
		$data = array();
		$where = array();
		$search = $this->input->get(null, true);
		if(isset($search['name'])) {
			$where['name'] = trim(trim($search['name']), '\t');
			$data['name'] = $where['name'];
		}
		$current_page = empty($this->uri->segment(3)) ? 0 : intval($this->uri->segment(3)) - 1;
		$per_page = 50;
        $offset = $current_page;
        $config['base_url'] = base_url('cj/zcash');
        $config['total_rows'] = $this->zcash_model->get_zcash_nums($where);
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
        $zcash = $this->zcash_model->get_zcash_lists($per_page, $per_page * $current_page, $where);
        $data['zcash'] = $zcash;
		//p($data);die;
		$this->load->view('cj/zcash', $data);
	}
	/** 卡片兑奖信息 */
	public function zcash_order() {
		$this->load->model('cj/zcash_model');
		$data = array();
		$where = array();
		$search = $this->input->get(null, true);
		if(isset($search['name'])) {
			$where['name'] = trim(trim($search['name']), '\t');
			$data['name'] = $where['name'];
		}
		if(!empty($search['time'])) {
			$data['time'] = $search['time'];
			$where['time'] = explode(' ', $search['time']);
		}
		$current_page = empty($this->uri->segment(3)) ? 0 : intval($this->uri->segment(3)) - 1;
		$per_page = 50;
        $offset = $current_page;
        $config['base_url'] = base_url('cj/zcash_order');
        $config['total_rows'] = $this->zcash_model->get_order_nums($where);
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
        $order = $this->zcash_model->get_order_lists($per_page, $per_page * $current_page, $where);
        $data['order'] = $order;
		$total_count = $this->zcash_model->get_detail_count();
		$data['num58'] = round(($this->zcash_model->get_detail_count(58)/$total_count), 4)*100;
		$data['num88'] = round(($this->zcash_model->get_detail_count(88)/$total_count), 4)*100;
		$data['num188'] = round(($this->zcash_model->get_detail_count(188)/$total_count), 4)*100;
		$data['num288'] = round(($this->zcash_model->get_detail_count(288)/$total_count), 4)*100;
		$data['num588'] = round(($this->zcash_model->get_detail_count(588)/$total_count), 4)*100;
		$data['num888'] = round(($this->zcash_model->get_detail_count(888)/$total_count), 4)*100;
		//p($data);die;
		$this->load->view('cj/zcash_order', $data);
	}
	/** 福袋兑换导出 */
	public function zcash_order_export() {
		$this->load->model('cj/zcash_model');
		$data = array();
		$where = array();
		$search = $this->input->get(null, true);
		if(isset($search['name'])) {
			$where['name'] = trim(trim($search['name']), '\t');
			$data['name'] = $where['name'];
		}
		if(!empty($search['time'])) {
			$data['time'] = $search['time'];
			$where['time'] = explode(' ', $search['time']);
		}
		
		$numbers = $this->zcash_model->get_order_nums($where);
        $data['invest'] = $this->zcash_model->get_order_lists($numbers, 0, $where);
		//p($data['invest']);die;
		
		$all = $data['invest'];//$this->borrow_model->get_borrow_investor_excel($time1,$time2);
		$this->load->helper('common');
		$this->load->library('PHPExcel');
		$resultPHPExcel = new PHPExcel();
		$resultPHPExcel->getActiveSheet()->setCellValue('A1', 'ID');
		$resultPHPExcel->getActiveSheet()->setCellValue('B1', '电话');
		$resultPHPExcel->getActiveSheet()->setCellValue('C1', '姓名');
		$resultPHPExcel->getActiveSheet()->setCellValue('D1', '状态');
		$resultPHPExcel->getActiveSheet()->setCellValue('E1', '现金红包');
		$resultPHPExcel->getActiveSheet()->setCellValue('F1', '倍数');
		$resultPHPExcel->getActiveSheet()->setCellValue('G1', '处理时间');
		$resultPHPExcel->getActiveSheet()->setCellValue('H1', '时间');
		$resultPHPExcel->getActiveSheet()->setCellValue('I1', '备注');
		$i = 1;
		foreach($all as $k => $v){
			$i++;
			$resultPHPExcel->getActiveSheet()->setCellValue('A'.$i, $v['id']);
			$resultPHPExcel->getActiveSheet()->setCellValue('B'.$i, ' ' . $v['phone']);
			$resultPHPExcel->getActiveSheet()->setCellValue('C'.$i, $v['real_name']);
			$resultPHPExcel->getActiveSheet()->setCellValue('D'.$i, $v['status'] == 1 ? '已处理' : '待处理');
			$resultPHPExcel->getActiveSheet()->setCellValue('E'.$i, $v['num']);
			$resultPHPExcel->getActiveSheet()->setCellValue('F'.$i, $v['multiple'] . '倍');
			$resultPHPExcel->getActiveSheet()->setCellValue('G'.$i, $v['puptime'] > 0 ? date('Y-m-d H:i', $v['puptime']) : '--');
			$resultPHPExcel->getActiveSheet()->setCellValue('H'.$i, date('Y-m-d H:i', $v['addtime']));
			$resultPHPExcel->getActiveSheet()->setCellValue('I'.$i, $v['remark']);
		}
		$outputFileName = '福袋兑换导出.xls'; 
		$xlsWriter = new PHPExcel_Writer_Excel5($resultPHPExcel); 
		header("Content-Type: application/force-download"); 
		header("Content-Type: application/octet-stream"); 
		header("Content-Type: application/download"); 
		header('Content-Disposition:inline;filename="'.$outputFileName.'"'); 
		header("Content-Transfer-Encoding: binary"); 
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
		header("Pragma: no-cache");
		$xlsWriter->save( "php://output" );
	}
	/** 发放记录 */
	public function zcash_record() {
		$this->load->model(array('cj/zcash_model', 'borrow/borrow_model'));
		$data = array();
		$where = array();
		$search = $this->input->get(null, true);
		if(isset($search['name'])) {
			$where['name'] = trim(trim($search['name']), '\t');
			$data['name'] = $where['name'];
		}
		$current_page = empty($this->uri->segment(3)) ? 0 : intval($this->uri->segment(3)) - 1;
		$per_page = 50;
        $offset = $current_page;
        $config['base_url'] = base_url('cj/zcash_record');
        $config['total_rows'] = $this->zcash_model->get_record_nums($where);
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
        $order = $this->zcash_model->get_record_lists($per_page, $per_page * $current_page, $where);
		foreach($order as $k=>$v) {
			if($v['bid'] > 0) {
				$order[$k]['borrowno'] = $this->borrow_model->get_borrow_byid($v['bid'])['borrow_no'];
			}
		}
        $data['order'] = $order;
		//p($data);die;
		$this->load->view('cj/zcash_record', $data);
	}
	/** 福袋处理页面 */
	public function deal_zcash_ui() {
		$id = $this->uri->segment(3);
		$data['id'] = intval($id);
		$this->load->view('/cj/deal_zcash_ui', $data);
	}
	/** 福袋兑奖处理 */
	public function deal_zcash_order() {
		$this->load->model('cj/zcash_model');
		$post = $this->input->post(null, true);
		$id = intval($post['id']);
		$order = $this->zcash_model->get_order_by_id($id);
		$order['puptime'] = time();
		$order['padmin_id'] = UID;
		$order['remark'] = mb_substr($post['remark'], 0, 200);
		$order['status'] = 1;
		if(!$this->zcash_model->modify_order($order)) {
			$this->error('操作失败');
		} else {
			$this->success('操作成功');
		}
	}
	
	/** 福袋兑奖批量处理 */
	public function batch_zcash_order() {
		$this->load->model('cj/zcash_model');
		$post = $this->input->post(null, true);
		if(IS_POST) {
			$ids = explode(',', $post['ids']);
			$remark = $post['value'];
			if(empty($remark)) {
				$this->error('备注不能为空');
			}
			$results = $this->zcash_model->get_order_byids($ids);
			$timestamp = time();
			foreach($results as $k=>$v) {
				if($v['status'] > 0) {
					$this->error('部分数据已经处理过，请勿重复操作');
				}
				$results[$k]['puptime'] = $timestamp;
				$results[$k]['remark'] = $remark;
				$results[$k]['status'] = 1;
				$results[$k]['padmin_id'] = 1;
			}
			
			// 插入到数据表
			if(!$this->zcash_model->batch_order($results)) {
				$this->error('操作失败');
			} else {
				$this->success('操作成功');
			}
		}
	}
	
	/**
	*  数据导入, 有表头的数据导入
	* @param string $file excel文件
	* @param string $sheet
	* @param int $higthRow 读取最高行数
	* @param String $hightColumn 读取最高列数
	* @param boolean $head 是否需要表头 true:需要
	* @return array 返回解析数据
	*/
	private function importExecl($file = '', $sheet = 0, $higthRow = 0, $hightColumn = '', $head = true){
		$file = iconv("utf-8", "gb2312", $file);   //转码
		
		if(empty($file) OR !file_exists($file)) {
			$this->error('文件不存在');
			//$data = array('code'=>202,'msg'=>'文件不存在.');
		}
		
		$this->load->library('PHPExcel');
		$this->load->library('PHPExcel/IOFactory');
		
		$PHPReader = new PHPExcel_Reader_Excel2007();
		if(! $PHPReader->canRead($file)) {
			$PHPReader = new PHPExcel_Reader_Excel5();
		}
		if(! $PHPReader->canRead($file)) {
			$this->error('文件读取失败');
			//$data = array('code'=>202,'msg'=>'文件读取失败.');
		}
		//创建excel对象
		$PHPExcel = $PHPReader->load($file); 
		
		$excleSheet = $PHPExcel->getSheet($sheet);
		$startRow = $head ? 1 : 2;
		$highestRow = empty($higthRow) ? $excleSheet->getHighestRow() : $higthRow;//echo $highestRow;
		$highestColumm = empty($hightColumn) ? $excleSheet->getHighestColumn() : $hightColumn;//$sheet->getHighestColumn();
		$highestColumm ++;
		
		$res = array();
		for ($row = $startRow; $row <= $highestRow; $row++){
			for ($column = 'A'; $column != $highestColumm; $column++) {
				$cell = $excleSheet->getCell($column.$row);
				$value = $cell->getValue();
				if(mb_substr($value, 0, 1) == '=' && $column == 'F') {
					$res[$row][$column] = $cell->getOldCalculatedValue();
				} else {
					$res[$row][$column] = $value;
				}
			}
		}
		
		return $res;
	}
	
	/** 导入丰付excel 投资数据 */
	public function import_ztree_data() {
		$this->load->model('cj/ztree_model');
		// 导入数据
        if(@is_uploaded_file($_FILES['excel']['tmp_name'])){
			//临时文件路径
			$tmp_name = $_FILES['excel']['tmp_name'];
			//截取文件后缀
			$suffix = explode('.', $_FILES['excel']['name']);
			$suffix = $suffix[count($suffix) - 1];
			if(!in_array($suffix, array('xls', 'xlsx'))) {
				$this->error('文件格式不正确');
				//$data = array('code'=>201,'msg'=>'文件格式不正确.');
			}
			$filePath = dirname(BASEPATH) . '/code/ztree.'.$suffix;
			if(!move_uploaded_file($tmp_name, $filePath)) {
				$this->error('路径不存在');
			}
			
			$res = $this->importExecl($filePath, 0, 0, 'F', FALSE);
			
			// 导入数据库
			$seria_array = array('A'=>'duration', 'B'=>'itime', 'C'=>'name', 'D'=>'idcard', 'E'=>'money', 'F'=>'flag');
			// 去掉数据的空格
			foreach($res as $k=>$v) {
				foreach($v as $key=>$value) {
					$res[$k][$key] = trim($value);
				}
			}
			// 验证数据是否合规
			// 记录所有身份证号到一个数组中
			$idcards = array();
			foreach($res as $k=>$v) {
				
				if(!is_numeric($v['A']) || !in_array($v['A'], array(97, 168, 33))) {
					$this->error('A' . $k . '单元格数据有误');
				}
				if(!preg_match_all("/^2019-\d{2}-\d{2}$/", trim($v['B']))) {
					$this->error('B' . $k . '单元格数据有误');
				}
				if(mb_strlen($v['C']) > 20) {
					$this->error('C' . $k . '单元格数据有误');
				}
				if(mb_strlen($v['D']) > 18) {
					$this->error('D' . $k . '单元格数据有误');
				}
				if(!is_numeric($v['E'])) {
					$this->error('E' . $k . '单元格数据有误');
				}
				if(mb_strlen($v['F']) > 200) {
					$this->error('F' . $k . '单元格数据有误');
				}
				array_push($idcards, $v['D']);
			}
			
			// 身份证号去重复
			$idcards = array_unique($idcards);
			// 查出所有身份证号对应的UID
			$res_idcards = $this->ztree_model->get_uid_byidcard($idcards);
			$idcards = array();
			foreach($res_idcards as $k=>$v) {
				$idcards[$v['idcard']] = $v['uid'];
			}
			unset($res_idcards);
			
			// 组织数据
			$data = array();
			$field = array_flip($seria_array);
			$timestamp = time();
			foreach($res as $k=>$v) {
				$data[] = [
					'uid'		=> isset($idcards[$v[$field['idcard']]]) ? $idcards[$v[$field['idcard']]] : 0,
					'uuid' 		=> md5($v[$field['flag']]),
					'duration' 	=> $v[$field['duration']],
					'itime' 	=> strtotime($v[$field['itime']]),
					'name' 		=> $v[$field['name']],
					'idcard' 	=> $v[$field['idcard']],
					'money' 	=> $v[$field['money']],
					'flag' 		=> $v[$field['flag']],
					'status' 	=> isset($idcards[$v[$field['idcard']]]) ? 1 : 0,
					'addtime'	=> $timestamp,
					'adminid'	=> UID,
				];
			}
			
			// 批量插入数据库
			try {
				$res_return = $this->ztree_model->insert_ztree_data($data);
			} catch(Exception $e) {
				echo $e->getMessage();
			}
			// if($res_return) {
				// $this->success('导入成功！');
			// } else {
				// $this->error('数据不能重复导入！');
			// }
			if(!$res_return) {
				$this->error('数据不能重复导入！');
			}
			$this->ztree_dao_data();
			
		}
	}
	/** 导入页面 */
	public function import_ztreeui() {
		$this->load->model('cj/ztree_model');
		$data = array();
		$where = array();
		$current_page = empty($this->uri->segment(3)) ? 0 : intval($this->uri->segment(3)) - 1;
		$per_page = 50;
        $offset = $current_page;
        $config['base_url'] = base_url('cj/import_ztreeui');
        $config['total_rows'] = $this->ztree_model->get_ztree_data_nums($where);
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
        $ztree = $this->ztree_model->get_ztree_data_lists($per_page, $per_page * $current_page, $where);
        $data['ztree'] = $ztree;
		$this->load->view('cj/import_ztreeui', $data);
	}
	// 设置对应金额并发苹果
	private function ztree_dao_data() {
		$this->load->model('cj/ztree_model');
		$this->load->database();
		// 导入的数据进行处理，
		$dr_data = $this->ztree_model->get_data_byused();
		// 组织数据
		$ztree_ = array();
		$used_data = array();
		$ztree_time = 0;
		foreach($dr_data as $k=>$v) {
			// 判断是否设置过这个用户
			if(isset($ztree_[$v['uid']])) {
				// 设置过该用户
				if(isset($ztree_[$v['uid']]['money'.$v['duration']])) {
					// 设置过该金额
					$ztree_[$v['uid']]['money'.$v['duration']] += $v['money'];
				} else {
					$ztree_[$v['uid']]['money'.$v['duration']] = $v['money'];
				}
			} else {
				// 没有设置过该用户
				$ztree_[$v['uid']]['uid'] = $v['uid'];
				$ztree_[$v['uid']]['money'.$v['duration']] = $v['money'];
			}
			$ztree_time = $v['itime'];
			array_push($used_data, $v['id']);
		}
		unset($dr_data);
		// 查询每个用户的账户
		$ztree_data = array();
		$this->db->trans_begin();
		foreach($ztree_ as $k=>$v) {
			// 根据uid查询用户的数据
			$ztree = array();
			$ztree = $this->ztree_model->get_ztree_byuid($k);
			if(!isset($v['money33'])) {
				$v['money33'] = 0;
			}
			if(!isset($v['money97'])) {
				$v['money97'] = 0;
			}
			if(!isset($v['money168'])) {
				$v['money168'] = 0;
			}
			if(!empty($ztree)) {
				$ztree_data = $this->get_ztree_data($ztree, $v);
				// 生成苹果详情
				if($ztree_data['num'] > 0) {
					$detail_data = $this->set_ztree_detail($ztree_data['num'], $ztree_data['uid']);
					$this->ztree_model->batch_insert_detail($detail_data);
					$apple_data = array(
						'uid' => $ztree_data['uid'],
						'num' => $ztree_data['num'],
						'itime' => $ztree_time,
						'addtime' => time()
					);
					$this->ztree_model->insert_apple($apple_data);
				}
				$ztree_data['num'] += $ztree['num'];
				// 修改数据
				$this->ztree_model->update_ztree($ztree_data);
			} else {
				$ztree_data = $this->get_ztree_data(array(), $v);
				// 生成苹果详情
				if($ztree_data['num'] > 0) {
					$detail_data = $this->set_ztree_detail($ztree_data['num'], $ztree_data['uid']);
					$this->ztree_model->batch_insert_detail($detail_data);
					$apple_data = array(
						'uid' => $ztree_data['uid'],
						'num' => $ztree_data['num'],
						'itime' => $ztree_time,
						'addtime' => time()
					);
					$this->ztree_model->insert_apple($apple_data);
				}
				// 插入数据
				$this->ztree_model->insert_ztree($ztree_data);
			}
		}
		// 将使用过的数据标记处理
		if(!empty($used_data)) {
			$this->ztree_model->update_data_byids($used_data);
		}
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$this->error('操作失败');
		} else {
			$this->db->trans_commit();
			$this->success('操作成功');
		}
	}
	// 生成苹果详情
	private function set_ztree_detail($num, $uid) {
		$this->load->model('cj/ztree_model');
		$detail_data = array();
		$ztree_detail = $this->ztree_model->get_detail_byuid($uid);
		$begin = empty($ztree_detail) ? 1 : ($ztree_detail['sort'] + 1);
		for($i = $begin; $i < ($begin + $num); $i++) {
			$detail_data[] = [
				'uid' => $uid,
				'num' => 1,
				'type' => 0,
				'addtime' => time(),
				'sort' => $i,
				'used' => 0,
				'status' => 0,
				'remark' => ''
			];
		}
		return $detail_data;
	}
	// 获取插入ztree表的数据
	private function get_ztree_data($source, $dest) {
		$ztree_data = array();
		$standard = 100000;
		if(!empty($source) && $source['status'] == 5) {
			// 已经长成苹果树
			$ztree_data['id'] = $source['id'];
			$ztree_data['uid'] = $source['uid'];
			$ztree_data['money33'] = $dest['money33'] + $source['money33'];
			$ztree_data['money97'] = $dest['money97'] + $source['money97'];
			$ztree_data['money168'] = $dest['money168'] + $source['money168'];
			$ztree_data['uptime'] = time();
			
			$dest['money33'] += $source['t33'];
			$dest['money97'] += $source['t97'];
			$dest['money168'] += $source['t168'];
			
			// 直接计算应得苹果数量
			$ztree_total = 0;
			if($dest['money33'] >= 60000) { // 33天
				$ztree_total += intval($dest['money33']/60000);
				$dest['money33'] -= intval($dest['money33']/60000)*60000;
			}
			if($dest['money97'] >= 30000) { // 97天
				$ztree_total += intval($dest['money97']/30000);
				$dest['money97'] -= intval($dest['money97']/30000)*30000;
			}
			if($dest['money168'] >= 15000) { // 168天
				$ztree_total += intval($dest['money168']/15000);
				$dest['money168'] -= intval($dest['money168']/15000)*15000;
			}
			$ztree_data['t33'] = $dest['money33'];
			$ztree_data['t97'] = $dest['money97'];
			$ztree_data['t168'] = $dest['money168'];
			$ztree_data['num'] = $ztree_total;
		} else {
			// 未长成苹果树
			if(!empty($source)) {
				$ztree_data['id'] = $source['id'];
			}
			$ztree_data['uid'] = $dest['uid'];
			$ztree_data['money33'] = isset($source['money33']) ? ($dest['money33'] + $source['money33']) : $dest['money33'];
			$ztree_data['money97'] = isset($source['money97']) ? ($dest['money97'] + $source['money97']) : $dest['money97'];
			$ztree_data['money168'] = isset($source['money168']) ? ($dest['money168'] + $source['money168']) : $dest['money168'];
			if(!empty($source)) {
				$ztree_data['uptime'] = time();
				$dest['money33'] += $source['money33'];
				$dest['money97'] += $source['money97'];
				$dest['money168'] += $source['money168'];
			} else {
				$ztree_data['addtime'] = time();
			}
			
			$total = $dest['money33'] + $dest['money97'] + $dest['money168']*2;
			$tmp_standard = $standard;
			if($total >= $tmp_standard) {
				// 金额大于等于十万
				if($dest['money33'] >= $tmp_standard) {
					$dest['money33'] = $dest['money33'] - $tmp_standard;
				} else {
					$tmp_standard -= $dest['money33'];
					$dest['money33'] = 0;
					if($dest['money97'] >= $tmp_standard) {
						$dest['money97'] = $dest['money97'] - $tmp_standard;
					} else {
						$tmp_standard -= $dest['money97'];
						$dest['money97'] = 0;
						$dest['money168'] = round(($dest['money168'] - $tmp_standard/2), 2);
					}
				}
				
				$ztree_data['status'] = 5;
				// 计算应得苹果数量
				$ztree_total = 0;
				if($dest['money33'] >= 60000) { // 33天
					$ztree_total += intval($dest['money33']/60000);
					$dest['money33'] -= intval($dest['money33']/60000)*60000;
				}
				if($dest['money97'] >= 30000) { // 97天
					$ztree_total += intval($dest['money97']/30000);
					$dest['money97'] -= intval($dest['money97']/30000)*30000;
				}
				if($dest['money168'] >= 15000) { // 168天
					$ztree_total += intval($dest['money168']/15000);
					$dest['money168'] -= intval($dest['money168']/15000)*15000;
				}
				$ztree_data['t33'] = $dest['money33'];
				$ztree_data['t97'] = $dest['money97'];
				$ztree_data['t168'] = $dest['money168'];
				$ztree_data['num'] = $ztree_total;
				
			} else {
				// 金额小于十万
				$ztree_data['num'] = 0;
				$ztree_data['t33'] = 0;
				$ztree_data['t97'] = 0;
				$ztree_data['t168'] = 0;
				$ztree_data['status'] = $this->get_ztree_status($total, $standard);
			}
		}
		return $ztree_data;
	}
	// 获取ztree状态, 根据比率显示 1:<10%2:<30%3:<60%4:<100%5:>=100%
	private function get_ztree_status($money, $standard) {
		if(round($money / $standard, 3) < 0.1) {
			return 1;
		}
		if(round($money / $standard, 3) < 0.3) {
			return 2;
		}
		if(round($money / $standard, 3) < 0.6) {
			return 3;
		}
		if(round($money / $standard, 3) < 1) {
			return 4;
		}
		if(round($money / $standard, 3) >= 1) {
			return 5;
		}
	}
	
	// 获取发财树记录
	public function ztree() {
		$this->load->model('cj/ztree_model');
		$data = array();
		$where = array();
		$search = $this->input->get(null, true);
		if(isset($search['name'])) {
			$where['name'] = trim(trim($search['name']), '\t');
			$data['name'] = $where['name'];
		}
		$current_page = empty($this->uri->segment(3)) ? 0 : intval($this->uri->segment(3)) - 1;
		$per_page = 50;
        $offset = $current_page;
        $config['base_url'] = base_url('cj/ztree');
        $config['total_rows'] = $this->ztree_model->get_ztree_nums($where);
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
        $ztree = $this->ztree_model->get_ztree_lists($per_page, $per_page * $current_page, $where);
        // 已兑换数据
		$uids = array();
		if(!empty($ztree)) {
			foreach($ztree as $k=>$v) {
				// 已兑换苹果数量
				$ztree[$k]['dh_red'] = 0;
				$ztree[$k]['dh_gold'] = 0;
				array_push($uids, $v['uid']);
			}
			$orders = $this->ztree_model->get_ztree_order_all_byuids($uids);
		}
		// 有兑换数据
		$dh_data = array();
		if(!empty($orders)) {
			foreach($orders as $k=>$v) {
				$dh_total['red'] = 0;
				$dh_total['gold'] = 0;
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
				$dh_data[$v['uid']]['red'] = isset($dh_data[$v['uid']]['red']) ? ($dh_data[$v['uid']]['red'] + $dh_total['red']) : $dh_total['red'];
				$dh_data[$v['uid']]['gold'] = isset($dh_data[$v['uid']]['gold']) ? ($dh_data[$v['uid']]['gold'] + $dh_total['gold']) : $dh_total['gold'];
			}
		}
		if(!empty($dh_data)) {
			foreach($dh_data as $k=>$v) {
				foreach($ztree as $key=>$value) {
					if($k == $value['uid']) {
						$ztree[$key]['dh_red'] += $v['red'];
						$ztree[$key]['dh_gold'] += $v['gold'];
					}
				}
			}
		}
		$data['ztree'] = $ztree;
		//p($data);die;
		$this->load->view('cj/ztreea', $data);
	}
	
	/** 发财树详情 */
	public function ztree_detail() {
		$this->load->model('cj/ztree_model');
		$data = array();
		$where = array();
		$search = $this->input->get(null, true);
		if(isset($search['name'])) {
			$where['name'] = trim(trim($search['name']), '\t');
			$data['name'] = $where['name'];
		}
		if(!empty($search['time'])) {
			if(intval($search['time']) > 0) {
				$data['time'] = $search['time'];
				$where['time'] = explode(' ', $search['time']);
			}
			
		}
		$current_page = empty($this->uri->segment(3)) ? 0 : intval($this->uri->segment(3)) - 1;
		$per_page = 50;
        $offset = $current_page;
        $config['base_url'] = base_url('cj/ztree_detail');
        $config['total_rows'] = $this->ztree_model->get_detail_nums($where);
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
        $detail = $this->ztree_model->get_detail_lists($per_page, $per_page * $current_page, $where);
        $data['detail'] = $detail;
		$this->load->view('cj/ztree_detail', $data);
	}
	
	/** 变更金苹果 */
	public function ztree_change() {
		$id = intval($this->uri->segment(3));
		if(empty($id)) {
			$this->error('信息错误');
		}
		
		if(IS_POST) {
			$this->load->model('cj/ztree_model');
			$post = $this->input->post(null, true);
			if(empty($post['value'])) {
				$this->error('备注不能为空');
			}
			
			// 判断是否满足变更的条件
			$detail = $this->ztree_model->get_detail_byid($id);
			if($detail['used'] > 0) {
				$this->error('不满足变更条件');
			}
			
			if($detail['type'] > 0) {
				$this->error('不能重复操作');
			}
			
			// 手动发的红苹果，不能变更为金苹果
			if($detail['status'] > 0) {
				$this->error('手动发放的红苹果，不能变更为金苹果');
			}
			
			// 查询该用户所有概率值
			$sort = ceil($detail['sort']/100);
			$apple_rand = $this->ztree_model->get_rand_byuid($detail['uid'], $sort);
			if(!empty($apple_rand)) {
				if(in_array($detail['sort'], explode(',', $apple_rand['randnum']))) {
					$this->error('该数据不能变更');
				}
			}
			
			// 组织数据
			$ztree = $this->ztree_model->get_ztree_byuid($detail['uid']);
			$ztree['num'] -= 1;
			$ztree['gold'] += 1;
			$detail['remark'] = mb_substr($post['value'], 0, 255);
			$detail['status'] = 1;
			$detail['type'] = 1;
			$detail_data = [
				'detail_id' => $detail['id'],
				'adminid'	=> UID,
				'addtime' 	=> time()
			];
			$this->db->trans_begin();
			$this->ztree_model->update_ztree($ztree);
			$this->ztree_model->update_detail($detail);
			$this->ztree_model->insert_detail_record($detail_data);
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$this->error('操作失败');
			} else {
				$this->db->trans_commit();
				$this->success('操作成功');
			}
		}
	}
	
	/** 发财树兑换 */
	public function ztree_order() {
		$this->load->model('cj/ztree_model');
		$data = array();
		$where = array();
		$search = $this->input->get(null, true);
		if(isset($search['name'])) {
			$where['name'] = trim(trim($search['name']), '\t');
			$data['name'] = $where['name'];
		}
		$current_page = empty($this->uri->segment(3)) ? 0 : intval($this->uri->segment(3)) - 1;
		$per_page = 50;
        $offset = $current_page;
        $config['base_url'] = base_url('cj/ztree_order');
        $config['total_rows'] = $this->ztree_model->get_order_nums($where);
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
        $order = $this->ztree_model->get_order_lists($per_page, $per_page * $current_page, $where);
        $data['order'] = $order;
		$data['tree_prize'] = array(
			'1' => 128, // '128元红包',
			'2' => 300, //'300元红包',
			'3' => 900, //'900元红包',
			'4' => 2100, //'2100元红包',
			'5' => 3700, //'3700元红包',
			'6' => 5800, //'5800元红包'
			'7' => 8800
		);
		//p($data);die;
		$this->load->view('cj/ztree_order', $data);
	}
	
	/** 发财树处理页面 */
	public function deal_ztree_ui() {
		$id = $this->uri->segment(3);
		$data['id'] = intval($id);
		$this->load->view('/cj/deal_ztree_ui', $data);
	}
	
	/** 发财树兑奖处理 */
	public function deal_ztree_order() {
		$this->load->model('cj/ztree_model');
		$post = $this->input->post(null, true);
		$id = intval($post['id']);
		$order = $this->ztree_model->get_order_by_id($id);
		$order['puptime'] = time();
		$order['padmin_id'] = UID;
		$order['remark'] = mb_substr($post['remark'], 0, 200);
		$order['status'] = 1;
		if(!$this->ztree_model->modify_order($order)) {
			$this->error('操作失败');
		} else {
			$this->success('操作成功');
		}
	}
	/** 发财树兑奖批量处理 */
	public function batch_ztree_order() {
		$this->load->model('cj/ztree_model');
		$post = $this->input->post(null, true);
		if(IS_POST) {
			$ids = explode(',', $post['ids']);
			$remark = $post['value'];
			$results = $this->ztree_model->get_order_byids($ids);
			$timestamp = time();
			foreach($results as $k=>$v) {
				if($v['status'] > 0) {
					$this->error('部分数据已经处理过，请勿重复操作');
				}
				$results[$k]['puptime'] = $timestamp;
				$results[$k]['remark'] = $remark;
				$results[$k]['status'] = 1;
				$results[$k]['padmin_id'] = 1;
			}
			
			// 插入到数据表
			if(!$this->ztree_model->batch_order($results)) {
				$this->error('操作失败');
			} else {
				$this->success('操作成功');
			}
		}
	}
	
	/** 发财树导出 */
	public function ztree_export() {
		$this->load->model('cj/ztree_model');
		$data = array();
		$where = array();
		$search = $this->input->get(null, true);
		if(isset($search['name'])) {
			$where['name'] = trim(trim($search['name']), '\t');
			$data['name'] = $where['name'];
		}
		if(!empty($search['time'])) {
			if(intval($search['time']) > 0) {
				$data['time'] = $search['time'];
				$where['time'] = explode(' ', $search['time']);
			}
		}
		
		$numbers = $this->ztree_model->get_order_nums($where);
        $data['invest'] = $this->ztree_model->get_order_lists($numbers, 0, $where);
		$data['tree_prize'] = array(
			'1' => 128, // '128元红包',
			'2' => 300, //'300元红包',
			'3' => 900, //'900元红包',
			'4' => 2100, //'2100元红包',
			'5' => 3700, //'3700元红包',
			'6' => 5800, //'5800元红包'
			'7' => 8800
		);
		
		$all = $data['invest'];
		// 调取所有用户
		$all_users = array();
		foreach($all as $k=>$v) {
			if($v['padmin_id'] > 0) {
				array_push($all_users, $v['padmin_id']);
			}
		} 
		if(!empty($all_users)) {
			$all_users = array_unique($all_users);
			$all_users = $this->ztree_model->get_ausers_byids($all_users);
			$all_users = array_column($all_users, 'realname', 'id');
		}
		$tree_prize = $data['tree_prize'];
		$this->load->helper('common');
		$this->load->library('PHPExcel');
		$resultPHPExcel = new PHPExcel();
		
		$resultPHPExcel->getActiveSheet()->setCellValue('A1', 'ID');
		$resultPHPExcel->getActiveSheet()->setCellValue('B1', '电话');
		$resultPHPExcel->getActiveSheet()->setCellValue('C1', '姓名');
		$resultPHPExcel->getActiveSheet()->setCellValue('D1', '状态');
		$resultPHPExcel->getActiveSheet()->setCellValue('E1', '现金红包');
		$resultPHPExcel->getActiveSheet()->setCellValue('F1', '时间');
		$resultPHPExcel->getActiveSheet()->setCellValue('G1', '处理时间');
		$resultPHPExcel->getActiveSheet()->setCellValue('H1', '操作人');
		$resultPHPExcel->getActiveSheet()->setCellValue('I1', '备注');
		$i = 1;
		foreach($all as $k => $v){
			$i++;
			$resultPHPExcel->getActiveSheet()->setCellValue('A'.$i, $v['id']);
			$resultPHPExcel->getActiveSheet()->setCellValueExplicit('B'.$i, $v['phone'], PHPExcel_Cell_DataType::TYPE_STRING);
			$resultPHPExcel->getActiveSheet()->setCellValue('C'.$i, $v['real_name']);
			$resultPHPExcel->getActiveSheet()->setCellValue('D'.$i, $v['status'] == 1 ? '已处理' : '待处理');
			$resultPHPExcel->getActiveSheet()->setCellValue('E'.$i, $tree_prize[$v['type']]);
			$resultPHPExcel->getActiveSheet()->setCellValue('F'.$i, date('Y-m-d H:i', $v['addtime']));
			$resultPHPExcel->getActiveSheet()->setCellValue('G'.$i, $v['puptime'] > 0 ? date('Y-m-d H:i', $v['puptime']) : '--');
			$resultPHPExcel->getActiveSheet()->setCellValue('H'.$i, $v['padmin_id'] > 0 ? $all_users[$v['padmin_id']] : '');
			$resultPHPExcel->getActiveSheet()->setCellValue('I'.$i, $v['remark']);
		}
		
		$outputFileName = '发财树订单数据.xls'; 
		$xlsWriter = new PHPExcel_Writer_Excel5($resultPHPExcel); 
		header("Content-Type: application/force-download"); 
		header("Content-Type: application/octet-stream"); 
		header("Content-Type: application/download"); 
		header('Content-Disposition:inline;filename="'.$outputFileName.'"'); 
		header("Content-Transfer-Encoding: binary"); 
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
		header("Pragma: no-cache");
		$xlsWriter->save( "php://output" );
	}
	
	/** 发财树发放明细 */
	public function ztree_apple() {
		$this->load->model('cj/ztree_model');
		$data = array();
		$where = array();
		$search = $this->input->get(null, true);
		if(isset($search['name'])) {
			$where['name'] = trim(trim($search['name']), '\t');
			$data['name'] = $where['name'];
		}
		if(!empty($search['time'])) {
			if(intval($search['time']) > 0) {
				$data['time'] = $search['time'];
				$where['time'] = explode(' ', $search['time']);
			}
		}
		$current_page = empty($this->uri->segment(3)) ? 0 : intval($this->uri->segment(3)) - 1;
		$per_page = 50;
        $offset = $current_page;
        $config['base_url'] = base_url('cj/ztree_apple');
        $config['total_rows'] = $this->ztree_model->get_apple_nums($where);
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
        $apple = $this->ztree_model->get_apple_lists($per_page, $per_page * $current_page, $where);
        $data['apple'] = $apple;
		$this->load->view('cj/ztree_apple', $data);
	}
	
	/** 发财树发放明细导出 */
	public function ztree_apple_export() {
		$this->load->model('cj/ztree_model');
		$data = array();
		$where = array();
		$search = $this->input->get(null, true);
		if(isset($search['name'])) {
			$where['name'] = trim(trim($search['name']), '\t');
			$data['name'] = $where['name'];
		}
		if(!empty($search['time'])) {
			if(intval($search['time']) > 0) {
				$data['time'] = $search['time'];
				$where['time'] = explode(' ', $search['time']);
			}
		}
		$numbers = $this->ztree_model->get_apple_nums($where);
        $data['invest'] = $this->ztree_model->get_apple_lists($numbers, 0, $where);
		
		$all = $data['invest'];
		$this->load->helper('common');
		$this->load->library('PHPExcel');
		$resultPHPExcel = new PHPExcel();
		
		$resultPHPExcel->getActiveSheet()->setCellValue('A1', 'ID');
		$resultPHPExcel->getActiveSheet()->setCellValue('B1', '电话');
		$resultPHPExcel->getActiveSheet()->setCellValue('C1', '姓名');
		$resultPHPExcel->getActiveSheet()->setCellValue('D1', '发放苹果数量');
		$resultPHPExcel->getActiveSheet()->setCellValue('E1', '投资日期');
		$resultPHPExcel->getActiveSheet()->setCellValue('F1', '发放日期');
		$i = 1;
		foreach($all as $k => $v){
			$i++;
			$resultPHPExcel->getActiveSheet()->setCellValue('A'.$i, $v['id']);
			$resultPHPExcel->getActiveSheet()->setCellValueExplicit('B'.$i, $v['phone'], PHPExcel_Cell_DataType::TYPE_STRING);
			$resultPHPExcel->getActiveSheet()->setCellValue('C'.$i, $v['real_name']);
			$resultPHPExcel->getActiveSheet()->setCellValue('D'.$i, $v['num']);
			$resultPHPExcel->getActiveSheet()->setCellValue('E'.$i, date('Y-m-d', $v['itime']));
			$resultPHPExcel->getActiveSheet()->setCellValue('F'.$i, date('Y-m-d H:i:s', $v['addtime']));
		}
		
		$outputFileName = '发放苹果数据.xls'; 
		$xlsWriter = new PHPExcel_Writer_Excel5($resultPHPExcel); 
		header("Content-Type: application/force-download"); 
		header("Content-Type: application/octet-stream"); 
		header("Content-Type: application/download"); 
		header('Content-Disposition:inline;filename="'.$outputFileName.'"'); 
		header("Content-Transfer-Encoding: binary"); 
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
		header("Pragma: no-cache");
		$xlsWriter->save( "php://output" );
	}
	
	/** 发放红苹果 */
	public function ztree_provide() {
		
		$uid = intval($this->uri->segment(3));
		if(empty($uid)) {
			$this->error('数据有误，请联系管理员');
		}
		if(IS_POST) {
			$this->load->model('cj/ztree_model');
			$post = $this->input->post(null, true);
			$num = intval($post['value']);
			if(empty($num)) {
				$this->error('发放苹果数量不能为空');
			}
			if($num > 100) {
				$this->error('不允许一次发放苹果数量超过100个');
			}
			$this->db->trans_begin();
			$ztree = $this->ztree_model->get_ztree_byuid($uid);
			if(empty($ztree) || $ztree['status'] != 5) {
				$this->error('还未成长为树');
			}
			$detail_data = $this->set_ztree_detail($num, $uid);
			foreach($detail_data as $k=>$v) {
				$detail_data[$k]['status'] = 2;
				$detail_data[$k]['remark'] = date('Y-m-d H:i:s').'客服手动发放红苹果';
			}
			$this->ztree_model->batch_insert_detail($detail_data);
			$ztree['num'] += $num;
			$ztree['uptime'] = time();
			// 修改数据
			$this->ztree_model->update_ztree($ztree);
			
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$this->error('操作失败');
			} else {
				$this->db->trans_commit();
				$this->success('操作成功');
			}
		}
	}
}