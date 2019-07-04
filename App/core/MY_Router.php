<?php
if (!defined('BASEPATH')) {  exit ('No direct script access allowed');}

class MY_Router extends CI_Router {
	public function __construct() {
		parent::__construct();
		/** 优果树第二版 逻辑 */
		switch($this->class) {
			case 'ztrees':
			case 'ztreesa':
			case 'zoptimal':
			case 'zoptimala':
			case 'zdraw':
			case 'zdrawa':
			case 'zreversal':
			case 'zreversala':
			case 'zcashc':
			case 'zcashca':
			case 'zdrawc':
			case 'zdrawca':
				$this->directory = 'theme/';
			break;
			case 'ztree': // 发财树重定向到第二版
				header("Location: https://www.jiamanu.com/ztrees.html"); exit();
			default:
			break;
		}
	}
}