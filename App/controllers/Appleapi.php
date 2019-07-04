<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Appleapi extends CI_Controller {
	/* public function __construct() {
		parent::__construct();
		$this->load->model('borrow/borrow_model');
	} */
	public function get_byuuids(){die();
		if($p = $this->input->post(NULL, TRUE)){
			$uid = $p['id'];//jmu_0000001
			$pwd = $p['pwd'];//^6l1*O0I
			$uuid = $p['uuid'];
			$key = $p['key'];
			$localkey = md5('jmu_0000001' . '|' . '^6l1*O0I' . '|' . $uuid);
			$data = array();
			$data['ip'] = $this->input->ip_address();
			$data['uuid'] = $uuid;
			$data['addtime'] = time();
			$data['uid'] = $uid;
			if($key != $localkey){
				$data['status'] = 2;
				$this->borrow_model->add_outfind($data);
				$info['status'] = 0;
				$info['code'] = '001';
				$info['message'] = '参数不正确!';
				$this->output
			    //->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}else{
				if(! $uu = $this->borrow_model->get_uuid_byuuid($uuid)){
					$data['status'] = 0;
					$this->borrow_model->add_outfind($data);
					$info['status'] = 0;
					$info['code'] = '002';
					$info['message'] = '可以下载!';
					$this->output
					//->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($info))
					->_display();
					exit;
				}else{
					$data['status'] = 1;
					$this->borrow_model->add_outfind($data);
					$info['status'] = 1;
					$info['code'] = '003';
					$info['message'] = '用户已下载';
					$this->output
					//->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($info))
					->_display();
					exit;
				}
			}
		}
	}
}