<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Adminr extends MY_Controller {
	public function __construct() {
        parent::__construct();
        $this->load->model(array('auth/admin_model', 'auth/auth_model', 'user/ausers_model'));
    }
	public function index(){
		$data = array(
		    'indextitle' => '系统主页',
		);
		$r = $this->auth_model->getrules('xm_auth_group_access','xm_auth_group', UID);
		if($r){
			$rs = explode(',', $r[0]['rules']);
		}else{
			$rs = array();
		}
		$rule = $this->admin_model->getrule();
		foreach($rule as $k => $v){
			if(! IS_ROOT){
				if(! in_array($v['id'], $rs)){
					unset($rule[$k]);
					continue;
				}
			}
		}
		$data['rule'] = $rule;
		$user = $this->ausers_model->get_ausers_byuid(UID);
		$data['name'] = $user['name'];
		$data['rname'] = $user['realname'];
		//$this->parser->parse('admin', $data);
		$this->load->view('user/admin', $data);
	}
	public function modify(){
		if($this->input->post()){
			$user = $this->ausers_model->get_ausers_byuid(UID);
			$pd0 = trim($this->input->post_get('pd0', TRUE));
			$pd = trim($this->input->post_get('pd', TRUE));
			$pd2 = trim($this->input->post_get('pd2', TRUE));
			if($user['name'] == 'admin'){
				$info['state'] = 0;
				$info['message'] = '管理员密码不能修改!';
				$this->output
				    ->set_content_type('application/json', 'utf-8')
				    ->set_output(json_encode($info))
					->_display();
				    exit;
			}
			if(! is_password($pd0)){
				$info['state'] = 0;
				$info['message'] = '原密码(包含字母和数字,至少6位)!';
				$this->output
				    ->set_content_type('application/json', 'utf-8')
				    ->set_output(json_encode($info))
					->_display();
				    exit;
			}
			//echo suny_encrypt($pd,'3ed1lio0'); echo '-', $user['pwd'];
			if(suny_encrypt($pd0,'3ed1lio0') != $user['pwd']){
				$info['state'] = 0;
				$info['message'] = '原密码不正确!';
				$this->output
				    ->set_content_type('application/json', 'utf-8')
				    ->set_output(json_encode($info))
					->_display();
				    exit;
			}
			if(! is_password($pd)){
				$info['state'] = 0;
				$info['message'] = '新密码(包含字母和数字,至少6位)!';
				$this->output
				    ->set_content_type('application/json', 'utf-8')
				    ->set_output(json_encode($info))
					->_display();
				    exit;
			}
			if($pd != $pd2){
				$info['state'] = 0;
				$info['message'] = '两次密码不一致!';
				$this->output
				    ->set_content_type('application/json', 'utf-8')
				    ->set_output(json_encode($info))
					->_display();
				    exit;
			}
			$data['pwd'] = suny_encrypt($pd,'3ed1lio0');
			if($this->ausers_model->modify_ausers($data, UID)){
				$info['state'] = 1;
				$info['url'] = 'center.html';
				$info['message'] = '修改成功!';
				$this->output
				    ->set_content_type('application/json', 'utf-8')
				    ->set_output(json_encode($info))
					->_display();
				    exit;
			}else{
				$info['state'] = 0;
				$info['message'] = '修改失败,请刷新后重试!';
				$this->output
				    ->set_content_type('application/json', 'utf-8')
				    ->set_output(json_encode($info))
					->_display();
				    exit;
			}
			
		}
		$this->load->view('user/modify');
	}
	public function logout(){
		$this->session->unset_userdata('uid');
		$url = base_url('login.html');
		redirect($url);
	}
	public function center(){
		if (function_exists('gd_info')) {
            $gd = gd_info();
            $gd = $gd['GD Version'];
        } else {
            $gd = "不支持";
        }
        if (false === ($str = @file("/proc/cpuinfo"))) return false;
		$str = implode("", $str);
		@preg_match_all("/model\s+name\s{0,}\:+\s{0,}([\w\s\)\(\@.-]+)([\r\n]+)/s", $str, $model);
		@preg_match_all("/cpu\s+MHz\s{0,}\:+\s{0,}([\d\.]+)[\r\n]+/", $str, $mhz);
		@preg_match_all("/cache\s+size\s{0,}\:+\s{0,}([\d\.]+\s{0,}[A-Z]+[\r\n]+)/", $str, $cache);
		@preg_match_all("/bogomips\s{0,}\:+\s{0,}([\d\.]+)[\r\n]+/", $str, $bogomips);
		if (false !== is_array($model[1]))
		{
			$res['cpu']['num'] = sizeof($model[1]);
			/*
			for($i = 0; $i < $res['cpu']['num']; $i++)
			{
				$res['cpu']['model'][] = $model[1][$i].'&nbsp;('.$mhz[1][$i].')';
				$res['cpu']['mhz'][] = $mhz[1][$i];
				$res['cpu']['cache'][] = $cache[1][$i];
				$res['cpu']['bogomips'][] = $bogomips[1][$i];
			}*/
			if($res['cpu']['num']==1)
				$x1 = '';
			else
				$x1 = ' ×'.$res['cpu']['num'];
			$mhz[1][0] = ' | 频率:'.$mhz[1][0];
			$cache[1][0] = ' | 二级缓存:'.$cache[1][0];
			$bogomips[1][0] = ' | Bogomips:'.$bogomips[1][0];
			$res['cpu']['model'][] = $model[1][0].$mhz[1][0].$cache[1][0].$bogomips[1][0].$x1;
			/* if (false !== is_array($res['cpu']['model'])) $res['cpu']['model'] = implode("<br />", $res['cpu']['model']);
			if (false !== is_array($res['cpu']['mhz'])) $res['cpu']['mhz'] = implode("<br />", $res['cpu']['mhz']);
			if (false !== is_array($res['cpu']['cache'])) $res['cpu']['cache'] = implode("<br />", $res['cpu']['cache']);
			if (false !== is_array($res['cpu']['bogomips'])) $res['cpu']['bogomips'] = implode("<br />", $res['cpu']['bogomips']); */
		}

		// NETWORK

		// UPTIME
		if (false === ($str = @file("/proc/uptime"))) return false;
		$str = explode(" ", implode("", $str));
		$str = trim($str[0]);
		$min = $str / 60;
		$hours = $min / 60;
		$days = floor($hours / 24);
		$hours = floor($hours - ($days * 24));
		$min = floor($min - ($days * 60 * 24) - ($hours * 60));
		if ($days !== 0) $res['uptime'] = $days."天";
		if ($hours !== 0) $res['uptime'] .= $hours."小时";
		$res['uptime'] .= $min."分钟";

		// MEMORY
		if (false === ($str = @file("/proc/meminfo"))) return false;
		$str = implode("", $str);
		preg_match_all("/MemTotal\s{0,}\:+\s{0,}([\d\.]+).+?MemFree\s{0,}\:+\s{0,}([\d\.]+).+?Cached\s{0,}\:+\s{0,}([\d\.]+).+?SwapTotal\s{0,}\:+\s{0,}([\d\.]+).+?SwapFree\s{0,}\:+\s{0,}([\d\.]+)/s", $str, $buf);
		preg_match_all("/Buffers\s{0,}\:+\s{0,}([\d\.]+)/s", $str, $buffers);

		$res['memTotal'] = round($buf[1][0]/1024, 2);
		$res['memFree'] = round($buf[2][0]/1024, 2);
		$res['memBuffers'] = round($buffers[1][0]/1024, 2);
		$res['memCached'] = round($buf[3][0]/1024, 2);
		$res['memUsed'] = $res['memTotal']-$res['memFree'];
		$res['memPercent'] = (floatval($res['memTotal'])!=0)?round($res['memUsed']/$res['memTotal']*100,2):0;

		$res['memRealUsed'] = $res['memTotal'] - $res['memFree'] - $res['memCached'] - $res['memBuffers']; //真实内存使用
		$res['memRealFree'] = $res['memTotal'] - $res['memRealUsed']; //真实空闲
		$res['memRealPercent'] = (floatval($res['memTotal'])!=0)?round($res['memRealUsed']/$res['memTotal']*100,2):0; //真实内存使用率

		$res['memCachedPercent'] = (floatval($res['memCached'])!=0)?round($res['memCached']/$res['memTotal']*100,2):0; //Cached内存使用率

		$res['swapTotal'] = round($buf[4][0]/1024, 2);
		$res['swapFree'] = round($buf[5][0]/1024, 2);
		$res['swapUsed'] = round($res['swapTotal']-$res['swapFree'], 2);
		$res['swapPercent'] = (floatval($res['swapTotal'])!=0)?round($res['swapUsed']/$res['swapTotal']*100,2):0;

		// LOAD AVG
		if (false === ($str = @file("/proc/loadavg"))) return false;
		$str = explode(" ", implode("", $str));
		$str = array_chunk($str, 4);
		$res['loadAvg'] = implode(" ", $str[0]);
		$this->load->view('user/centers', $res);
	}
}