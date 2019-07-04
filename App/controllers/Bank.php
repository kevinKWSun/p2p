<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Bank extends Baseaccount {
	public function __construct() {
		parent::__construct();
		//$this->load->library('pagination');
		$this->load->model('member/member_model');
		//$this->load->helper('url');
	}
	//首页
	public function index(){
		$data['b'] = $this->member_model->get_bank_byuid('', QUID, 1);
		$this->load->view('account/bank', $data);
	}
}