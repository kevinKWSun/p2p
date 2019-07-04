<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Lotto extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->library('pagination');
		$this->load->library('session');
	}
	public function index() {
		$this->load->model(array('cj/dlt_model'));
		$data['dlt'] = $this->dlt_model->get_dlt_all();
		$random_string = $this->salt(16);
		$this->session->set_userdata(array('lotto'=>$random_string));
		$data['lotto'] = $random_string;
		$this->load->view('cj/lotto', $data);
	}
	
	/** 大乐透 添加 */
	public function dlt_save() {
		$this->load->model(array('cj/dlt_model'));
		$post = $this->input->post(null, true);
		if($post['lotto'] != $this->session->lotto) {
			$info['state'] = 0;
			$info['message'] = '已经提交，请勿重复提交!';
			$this->output
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($info))
			->_display();
			exit;
		}
		if($post['multiple'] > 10) {
			$info['state'] = 0;
			$info['message'] = '倍数最高10倍!';
			$this->output
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($info))
			->_display();
			exit;
		}
		$data = [];
		if(empty($post['phone']) || strlen($post['phone']) != 11) {
			$info['state'] = 0;
			$info['message'] = '手机号错误!';
			$this->output
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($info))
			->_display();
			exit;
		}
		if(!isset($post['num'])) {
			$info['state'] = 0;
			$info['message'] = '数据为空';
			$this->output
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($info))
			->_display();
			exit;
		}
		if(count($post['num']) > 500) {
			$info['state'] = 0;
			$info['message'] = '最多选择500注!';
			$this->output
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($info))
			->_display();
			exit;
		}
		
		$timestamp = time();
		foreach($post['num'] as $key=>$value) {
			$tmp_value = explode(',', $value);
			foreach($tmp_value as $k=>$v) {
				if(empty($v)) {
					unset($tmp_value[$k]);
				}
			}
			$count = count($tmp_value);
			if($count != 5) {
				$info['state'] = 0;
				$info['message'] = '所选号码必须有5个数字!';
				$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($info))
				->_display();
				exit;
			}
			// 去重复
			$tmp_value = array_unique($tmp_value);
			if($count != count($tmp_value)) {
				$info['state'] = 0;
				$info['message'] = '所选号码不能重复!';
				$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($info))
				->_display();
				exit;
			}
			// 组织数据
			$data[] = [
				'num' => implode(',', $tmp_value),
				'status' => 0,
				'addtime' => $timestamp,
				'phone'	=> $post['phone'],
				'multiple' => intval($post['multiple']) ? intval($post['multiple']) : 1
				
			];
		}
		
		// 插入数据库
		if($this->dlt_model->batch_save($data)) {
			$this->session->set_userdata(array('lotto'=>''));
			$info['state'] = 1;
			$info['message'] = '操作成功!';
			$this->output
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($info))
			->_display();
			exit;
		} else {
			$info['state'] = 0;
			$info['message'] = '操作失败!';
			$this->output
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($info))
			->_display();
			exit;
		}
	
	}
	
	private function salt($len = 6){
		$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
		$str = '';
		for( $i = 0; $i < $len; $i++ ){
			$str .= $chars[mt_rand( 0, strlen($chars) -1 )];
		}
		return $str;
	}
	
	/** 大乐透 添加 */
	/* public function dlt_save() {

		$this->load->model(array('cj/dlt_model'));
		$post = $this->input->post(null, true);
		$data = [];
		if(empty($post['phone']) || strlen($post['phone']) != 11) {
			$info['state'] = 0;
			$info['message'] = '手机号错误!';
			$this->output
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($info))
			->_display();
			exit;
		}
		$data['phone'] = $post['phone'];
		
		foreach($post['num'] as $k=>$v) {
			if(empty($v)) {
				unset($post['num'][$k]);
			}
		}
		$count = count($post['num']);
		if($count < 2 || $count > 5) {
			$info['state'] = 0;
				$info['message'] = '所选号码不能少于两个!';
				$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($info))
				->_display();
				exit;
		}
		// 去重复
		$post['num'] = array_unique($post['num']);
		if($count != count($post['num'])) {
			$info['state'] = 0;
				$info['message'] = '所选号码不能重复!';
				$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($info))
				->_display();
				exit;
		}
		
		$data['num'] = implode(',', $post['num']);
		$data['status'] = 0;
		$data['addtime'] = time();
		if($this->dlt_model->save($data)) {
			$info['state'] = 1;
				$info['message'] = '操作成功!';
				$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($info))
				->_display();
				exit;
		} else {
			$info['state'] = 0;
				$info['message'] = '操作失败!';
				$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($info))
				->_display();
				exit;
		}
	
	} */
	
}