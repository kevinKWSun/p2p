<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Test extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->library('pagination');
		$this->load->model(array('borrow/borrow_model', 'account/info_model', 'member/member_model'));
		$this->load->helper(array('url', 'common'));
	}
	
	public function create_arr() {
		print_r(range(1, 5));
	}
	
	public function byRandom(){
		$total = 100;
		$num = 8;
		$min = 1;
		$m = 1.8;
		$i = 1;
		while ($i<$num){
			$safe_total = $total/$m-($num-$i)*$min;
			$money = intval(mt_rand($min*100,$safe_total*100)/100);
			if($money > 2000){
				$money = 2000;
			}
			if($money == 0){
				$money = 1;
			}
			$total = $total-$money;
			$data[] = array($money);
			$i++;
		}
		if($total > 2000){
			$total = 2000;
		}
		if($total == 0){
			$total = 1;
		}
		$data[] = array($total);
		shuffle($data);
		p($data);
    }
	public function index() {
		//echo strlen('https://smlweb.tsign.cn/e.html?id=1054639368351813634');
		$aa = 'a:1:{s:5:"value";a:2:{s:5:"signs";s:4:"true";s:4:"body";a:2:{s:4:"body";a:5:{s:6:"acctNo";s:13:"1013000814101";s:9:"actualAmt";s:4:"4.10";s:10:"pledgedAmt";s:4:"0.00";s:9:"preLicAmt";s:4:"0.00";s:8:"totalAmt";s:4:"4.10";}s:4:"head";a:10:{s:7:"bizFlow";s:21:"011201810271038492228";s:10:"merOrderNo";s:20:"18102710387852089525";s:10:"merchantNo";s:15:"131010000011013";s:8:"respCode";s:6:"000000";s:8:"respDesc";s:6:"成功";s:9:"tradeCode";s:6:"CG1045";s:9:"tradeDate";s:8:"20181027";s:9:"tradeTime";s:6:"103849";s:9:"tradeType";s:2:"01";s:7:"version";s:5:"1.0.0";}}}}';
		print_r(unserialize($aa));
	}
}