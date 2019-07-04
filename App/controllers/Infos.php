<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Infos extends Baseaccount {
	public function __construct() {
		parent::__construct();
		$this->load->model(array('account/info_model', 'member/member_model'));
	}
	public function index(){
		$data['info'] = $this->member_model->get_member_info_byuid(QUID);
		$this->load->view('account/infos_v1', $data);
	}
}