<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Huodong extends Baseaccounts {
	public function __construct() {
		parent::__construct();
		//$this->load->library('pagination'); 
		//$this->load->model(array('news/news_model'));
		//$this->load->helper('url');
		$this->data = array();
		$this->load->library('parser');
		$this->data['top'] = $this->parser->parse('index/topa', array(), TRUE);
		$this->data['foot'] = $this->parser->parse('index/foota', array(), TRUE);
		//$this->data['menu'] = $this->get_menu();
	}
	public function wsj(){
		
		$this->load->view('huodong/wsj', $this->data);
	}
	public function eleven(){
		$this->load->view('huodong/eleven', $this->data);
	}
	public function twelve(){
		$this->load->view('huodong/12', $this->data);
	}
	public function one_19(){
		$this->load->view('huodong/one_19', $this->data);
	}
	public function tow_19(){
		$this->load->view('huodong/tow_19', $this->data);
	}
}