<?php
/** 积分活动 */
defined('BASEPATH') OR exit('No direct script access allowed');
class Activity extends MY_Controller {
	public function __construct() {
        parent::__construct();
        $this->load->model(array());
    }
	
	public function index() {
		$this->load->view('activity/index');
	}
}