<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Investor extends Baseaccount {
	public function __construct() {
		parent::__construct();
	}
	public function index(){
		$this->load->view('account/investor_v1');
	}
}