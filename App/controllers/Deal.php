<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/** 数据管理 */
class Deal extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->library('pagination');
		$this->load->model(array('water/water_model','member/member_model','borrow/borrow_model'));
	}
	
	/** 投标查询 */
	public function bid() {
		$data = array();
		$where = array();
		$search = $this->input->get(null, true);
		//根据流水号查询
		if(isset($search) && !empty($search['flow'])) {
			$data['flow'] = trim(trim($search['flow']), '\t');
			$where['flow'] = $data['flow'];
		}
		
		if(isset($where['flow'])) {
			$flag = substr($where['flow'], 0, 1);
			//查询业务流水号
			if(empty($flag) && strlen($where['flow']) == 21) {
				$data['water'] = $this->water_model->get_water_bybiz($where['flow']);
			} else {//查询商户点单号
				$data['water'] = $this->water_model->get_water_byorder($where['flow']);
			}
			//投标用户信息
			$data['member'] = $this->member_model->get_member_info_byuid($data['water']['uid']);
			//投标信息
			$data['borrow'] = $this->borrow_model->get_borrow_byid($data['water']['bid']);
			
		}
		
		$this->load->view('deal/bid', $data);
	}
	
	/** 查看资管平台投资信息 */
	public function show() {
		$id = $this->uri->segment(3);
		$water = $this->water_model->get_water_byid($id);
		//调用投标查询接口
		$params['code'] = 'CG1052';//参数变成code
		$params['queryFlow'] = $water['merOrderNo'];
		$params['flowType'] = '01';
		$head = head($params, 'CG2002', 'chaxun');
		//water($meminfo['uid'], $head['merOrderNo'], 'CG2002', $id);
		unset($head['callbackUrl'], $head['registerPhone'], $head['responsePath'], $head['url']);
		$data = $head;
		$data = json_encode($data);
		$url = $this->config->item('Interface').'2002';
		$res = post_curl_test($url, $data);
		$arr = json_decode($res, true);;
		//解密
		$params['key'] = $arr['keyEnc'];
		$params['bodys'] = $arr['jsonEnc'];
		$params['sign'] = $arr['sign'];
		$data = decrypt($params);
		p($data);die;

		print_r($tmp_body);die;
		if($tmp_body['head']['respCode'] == '000000') {
			$data['res'] = $tmp_body['body'];
			p($tmp_body);
		} else {
			echo $tmp_body['head']['respDesc'];
			//$this->error($tmp_body['head']['respDesc']);
		}
		$this->load->view('deal/show', $data);
	}
	
	/** 根据数据补发投资信息 */
	public function reissue() {
		// $test = 'a:1:{s:5:"value";a:2:{s:5:"signs";s:4:"true";s:4:"body";a:2:{s:4:"body";a:2:{s:6:"acctNo";s:13:"1013000817401";s:15:"subjectAuthCode";s:21:"011201810290209022389";}s:4:"head";a:10:{s:7:"bizFlow";s:21:"012201810290208552539";s:10:"merOrderNo";s:20:"18102902088886628352";s:10:"merchantNo";s:15:"131010000011013";s:8:"respCode";s:6:"000000";s:8:"respDesc";s:6:"成功";s:9:"tradeCode";s:6:"CG1052";s:9:"tradeDate";s:8:"20181029";s:9:"tradeTime";s:6:"020902";s:9:"tradeType";s:2:"01";s:7:"version";s:5:"1.0.0";}}}}';
		// $test = unserialize($test);
		// p($test['value']['body']);
		
		$str = array('merchantNo'=>'131010000011013', 'merOrderNo'=>'18120309233585331092', 'tradeCode'=>'CG1052', 'bizFlow'=>'012201812030923286124', 'url'=>'https://www.jiamanu.com/paytest', 'json'=>'{"body":{"acctNo":"1013000822401","subjectAuthCode":"012201812030923356125"},"head":{"bizFlow":"012201812030923286124","merOrderNo":"18120309233585331092","merchantNo":"131010000011013","respCode":"000000","respDesc":"成功","tradeCode":"CG1052","tradeDate":"20181203","tradeTime":"092335","tradeType":"01","version":"1.0.0"}}');
		
		$str['body'] = json_decode($str['json'], true);
		unset($str['json']);
		p($str['body']);
		//p(serialize($str['body']));
		die;
		
		
		
		// $this->load->model('paytest/paytest_model');
		// $this->paytest_model->invest($str['body']);
		
		
		
		
	}
	
	/** 抽奖剩余金额 */
	public function times_lists() {
		$data = array();
		$where = array();
		$this->load->model('times/times_model');
		$search = $this->input->get(null, true);
		if(!empty($search['skey'])) {
			$data['skey'] = trim(trim($search['skey']), '\t');
			$where['skey'] = $data['skey'];
		}
		
		$per_page = 50;
		$current_page = $this->uri->segment(3) ? $this->uri->segment(3) - 1 : 0;
        $offset = $current_page;
        $config['base_url'] = base_url('deal/times_lists');
        $config['total_rows'] = $this->times_model->get_times_related_num($where); //echo  $config['total_rows'];die;
        $config['per_page'] = $per_page;
		$config['page_query_string'] = FALSE;
		$config['first_link'] = '首页'; // 第一页显示   
		$config['last_link'] = '末页'; // 最后一页显示   
		$config['next_link'] = '下一页'; // 下一页显示   
		$config['prev_link'] = '上一页'; // 上一页显示   
		$config['cur_tag_open'] = ' <span class="current">'; // 当前页开始样式   
		$config['cur_tag_close'] = '</span>';   
		$config['num_links'] = 10;
		$config['uri_segment'] = 3;
		$config['use_page_numbers'] = TRUE;
		$config['reuse_query_string'] = TRUE;
        $this->pagination->initialize($config); 
        $data['totals'] = $config['total_rows'];
        $data['page'] = $this->pagination->create_links();
        $data['p'] = $current_page;
        $times = $this->times_model->get_times_related($per_page, $offset * $per_page, $where);
        $data['times'] = $times;
		//p($times);
		$this->load->view('deal/times_lists', $data);
	} 
	
}