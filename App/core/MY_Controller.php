<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//后台验证
class MY_Controller extends CI_Controller {
	public function __construct(){
        parent::__construct();
        $this->load->helper(array('common', 'url'));
        $this->load->library(array('parser', 'auth', 'user_agent'));
        if(defined('UID')) return ;
	    define('UID',is_login());
		if( ! UID){
		   $url = base_url('login.html');
		   redirect($url);
		}
        define('IS_ROOT', is_administrator());
        if(! IS_ROOT){
            $a = $this->uri->segment(1);
            $b = $this->uri->segment(2);
            if($b){
                if($b == 'index'){
                    $rule  = strtolower('/' . $a);
                }else{
                    $rule  = strtolower('/' . $a . '/' .$b);
                }
            }else{
                $rule  = strtolower('/' . $a);
            }
            if (! $this->checkRule($rule) && $rule != '/adminr/center' && $rule != '/adminr' && $rule != '/menus' && $rule != '/adminr/logout'){
                exit('<center><font color=red size=+2>403_未授权访问!</font></center>');
            }
        }
		//判断是否是post方法
		define('IS_POST',strtolower($_SERVER["REQUEST_METHOD"]) == 'post'); 
    }
    /**
     * 权限检测
     * @param string  $rule    检测的规则
     * @param string  $mode    check模式
     * @return boolean
     */
    final protected function checkRule($rule, $type=1, $mode='url'){
        if(! $this->auth->check($rule, UID, $type, $mode)){
            return false;
        }
        return true;
    }
	
	/**
     * 报错信息
     * @param string  $message    错误信息
     */
    protected function error($message = '') {
        $info['state'] = 0;
		$info['message'] = $message;
		$this->output
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($info))
			->_display();
			exit;
    }
	/**
     * 成功信息
     * @param string  $message    	成功信息
	 * @param string  $url 		    跳转url
     */
    protected function success($message = '', $url = '') {
        $info['state'] = 1;
		$info['message'] = $message;
		empty($url) || $info['url'] = $url;
		$this->output
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($info))
			->_display();
			exit;
    }
}
//会员中心验证
class Baseaccount extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->helper(array('common', 'url'));
        $this->load->library(array('parser', 'auth', 'user_agent'));
        if(defined('QUID')) return ;
        define('QUID',is_qlogin());
        if( ! QUID){
           $url = base_url('/suny/login.html');
           redirect($url);
        }else{
			$a = $this->uri->segment(1);
			$b = $this->uri->segment(2);
			define('CONTROLLER', $a);
			define('METHOD', $b);
			$url = base_url('/safe.html');
			$this->load->model('member/member_model');
			$mstatus = $this->member_model->get_members_status_byuserid(QUID);
			if($mstatus['id_status'] != 1){
				define('IS_CHECK', FALSE);
				if($a != 'safe' && $a != 'infos' && $b != 'authentication'){
					redirect($url);
				}
			}else{
				define('IS_CHECK', TRUE);
				
			}
			$bank = $this->member_model->get_bank_byuid('', QUID, 1);
			$member = $this->member_model->get_member_byuserid(QUID);
			if(! $bank){
				define('IS_BANK', FALSE);
				if($member['type'] !== '2') {
					if($a != 'safe' && $a != 'infos' && $b != 'authentication'){
						redirect($url);
					}
				}
			}else{
				define('IS_BANK', TRUE);
			}
			if($member['type'] === '2') {
				define('IS_COMPANY', TRUE);
			} else {
				define('IS_COMPANY', FALSE);
			}
			if($member['attribute'] === '2') {
				define('IS_FINANCE', TRUE);
			} else {
				define('IS_FINANCE', FALSE);
			}
		}
		define('IS_POST',strtolower($_SERVER["REQUEST_METHOD"]) == 'post'); 
    }
	protected function error($message = '') {
        $info['state'] = 0;
		$info['message'] = $message;
		$this->output
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($info))
			->_display();
			exit;
    }
	/**
     * 成功信息
     * @param string  $message    	成功信息
	 * @param string  $url 		    跳转url
     */
    protected function success($message = '', $url = '') {
        $info['state'] = 1;
		$info['message'] = $message;
		empty($url) || $info['url'] = $url;
		$this->output
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($info))
			->_display();
			exit;
    }
}
//验证UID
class Baseaccounts extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->helper(array('common', 'url'));
        $this->load->library(array('parser', 'auth', 'user_agent'));
        if(defined('QUID')) return ;
        define('QUID',is_qlogin());
		define('IS_POST',strtolower($_SERVER["REQUEST_METHOD"]) == 'post'); 
    }
	/**
     * 报错信息
     * @param string  $message    错误信息
     */
    protected function error($message = '') {
        $info['state'] = 0;
		$info['message'] = $message;
		$this->output
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($info))
			->_display();
			exit;
    }
	/**
     * 成功信息
     * @param string  $message    	成功信息
	 * @param string  $url 		    跳转url
     */
    protected function success($message = '', $url = '') {
        $info['state'] = 1;
		$info['message'] = $message;
		empty($url) || $info['url'] = $url;
		$this->output
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($info))
			->_display();
			exit;
    }
}