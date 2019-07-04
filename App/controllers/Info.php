<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Info extends Baseaccount {
	public function __construct() {
		parent::__construct();
		//$this->load->library('pagination');
		$this->load->model(array('account/info_model','member/member_model'));
		//$this->load->helper('url');
	}
	//首页
	public function index(){
		$m = $this->info_model->get_sy_m(QUID);
		$month = strtotime(date('Y-m-01', time()));
		$i = 0;
		while($i < 12){
			$mh[$i] = date('Y-m-01',strtotime('- ' . $i . ' month', $month));
			$i++;
		}
		foreach($mh as $k => $v){
			$time = array();
			$time[] = strtotime($v);
			$time[] = strtotime(date('Y-m-t 23:59:59', strtotime($v)));
			$ms[$k] = $this->info_model->get_sy_m(QUID, $time);
			$ms[$k]['m'] = date('Y-m', strtotime($v));
		}
		$dsbj = $this->info_model->get_ds_m(QUID);
		$my = $this->info_model->get_money(QUID);
		$data['tm'] = $m['tm'] ? $m['tm'] : '0.00';
		$data['ms'] = $ms;
		$data['ky'] = $my;
		$data['ds'] = $dsbj['c'] - $dsbj['rc'];
		$this->load->view('account/info', $data);
	}
}