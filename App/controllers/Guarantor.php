<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Guarantor extends MY_Controller {
	public function __construct() {
        parent::__construct();
        $this->load->library('pagination');
		$this->load->model('guarantor/guarantor_model');
    }
	
	/** 担保人列表 */
	public function index() {
		$data = array();
		$where = array();
		$search = $this->input->get(null, true);
		if(!empty($search['skey'])) {
			$data['skey'] = trim(trim($search['skey']), '\t');
			$where['skey'] = $data['skey'];
		}
		if(!empty($search['guarantor_id'])) {
			$data['guarantor_id'] = trim(trim($search['guarantor_id']), '\t');
			$where['guarantor_id'] = $data['guarantor_id'];
		}
		$per_page = 10;
		$current_page = $this->uri->segment(3) ? $this->uri->segment(3) - 1 : 0;
        $offset = $current_page;
        $config['base_url'] = base_url('guarantor/index');
        $config['total_rows'] = $this->guarantor_model->get_guarantor_num($where);
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
        $data['guarantor'] = $this->guarantor_model->get_guarantor_lists($per_page, $offset * $per_page, $where);
		$this->load->view('guarantor/index', $data);
	}
	
	/** 导出 */
	public function export() {
		$data = array();
		$where = array();
		$search = $this->input->get(null, true);
		if(!empty($search['skey'])) {
			$data['skey'] = trim(trim($search['skey']), '\t');
			$where['skey'] = $data['skey'];
		}
		if(!empty($search['guarantor_id'])) {
			$data['guarantor_id'] = trim(trim($search['guarantor_id']), '\t');
			$where['guarantor_id'] = $data['guarantor_id'];
		}
		$config['total_rows'] = $this->guarantor_model->get_guarantor_num($where);
		$guarantor = $this->guarantor_model->get_guarantor_lists($config['total_rows'], 0, $where);
		$all = $guarantor;
		$this->load->helper('common');
		$this->load->library('PHPExcel');
		$resultPHPExcel = new PHPExcel();
		$resultPHPExcel->getActiveSheet()->setCellValue('A1', 'ID');
		$resultPHPExcel->getActiveSheet()->setCellValue('B1', '姓名');
		$resultPHPExcel->getActiveSheet()->setCellValue('C1', '手机号');
		$resultPHPExcel->getActiveSheet()->setCellValue('D1', '身份证号');
		$resultPHPExcel->getActiveSheet()->setCellValue('E1', '车辆型号');
		$resultPHPExcel->getActiveSheet()->setCellValue('F1', '添加时间');
		$resultPHPExcel->getActiveSheet()->setCellValue('G1', '评估价');
		

		$i = 1;
		foreach($all as $k => $v){
			$i++;
			$resultPHPExcel->getActiveSheet()->setCellValue('A'.$i, $v['id']);
			$resultPHPExcel->getActiveSheet()->setCellValue('B'.$i, $v['name']);
			$resultPHPExcel->getActiveSheet()->setCellValue('C'.$i, $v['phone']." ");
			$resultPHPExcel->getActiveSheet()->setCellValue('D'.$i, $v['idcard']." ");
			$resultPHPExcel->getActiveSheet()->setCellValue('E'.$i, $v['mode']);
			$resultPHPExcel->getActiveSheet()->setCellValue('F'.$i, date('Y-m-d', $v['addtime']));
			$resultPHPExcel->getActiveSheet()->setCellValue('G'.$i, $v['price']);
			
		}
		$outputFileName = '担保人信息.xls'; 
		$xlsWriter = new PHPExcel_Writer_Excel5($resultPHPExcel); 
		header("Content-Type: application/force-download"); 
		header("Content-Type: application/octet-stream"); 
		header("Content-Type: application/download"); 
		header('Content-Disposition:inline;filename="'.$outputFileName.'"'); 
		header("Content-Transfer-Encoding: binary"); 
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
		header("Pragma: no-cache");
		$xlsWriter->save( "php://output" );
	}
	/** 添加 */
	public function add() {
		if(IS_POST) {
			$post = $this->input->post(NULL, TRUE);
			if(! $post['name']){
				$this->error('请完善标姓名!');
			}
			if(! intval($post['phone'])){
				$this->error('请完善手机号!');
			}
			if(! $post['idcard']){
				$this->error('请完善身份证号!');
			}
			if(! intval($post['sex'])){
				$this->error('请完善性别!');
			} 
			if(! $post['mode']){
				$this->error('请完善车辆型号!');
			}
			if(! round(floatval($post['price']), 2)){
				$this->error('请完善评估价!');
			}
			if(! $post['pic']){
				$this->error('请完善图片!');
			}
			$data['name'] = trim($post['name']);
			$data['phone'] = trim($post['phone']);
			$data['idcard'] = trim($post['idcard']);
			//$data['address'] = $post['address'];
			$data['addtime'] = time();
			$data['sex'] = intval($post['sex']);
			$data['mode'] = $post['mode'];
			$data['price'] = round(floatval($post['price']), 2);
			$data['pic'] = $post['pic'];
			
			//自动生成签章，如果不能生成，不能提交担保人信息
			if(empty($data['name']) || empty($data['phone']) || empty($data['idcard'])) {
				$this->error('姓名/手机号/身份证为空');
			}
			$this->load->library('EsignAPI/EsignAPI'); 
			$EsignAPI = new EsignAPI();
			//创建账户
			$data['real_name'] = $data['name'];
			$res = $EsignAPI->addPersonAccount($data);
			if(empty($res['errCode'])) {//返回账户标识
				$data['accountId'] = $res['accountId'];
			} else {
				$this->error($res['msg']);
			}
			//生成签章
			$type = mb_strlen($data['name']) > 3 ? 'HWXK' : '';
			$res = $EsignAPI->addPersonTemplateSeal($data, $type);
			if(empty($res['errCode'])) {//返回印章base64图片
				$image = set_base64_image($res['imageBase64'], 0);
				$data['sealPath'] = $image;
				unset($data['real_name']);
			} else {
				$this->error($res['msg']);
			}
			//log_record(serialize($data));
			if($this->guarantor_model->add_guarantor($data)) {
				$this->success('操作成功', '/guarantor.html');
			} else {
				$this->error('操作失败');
			}
		}
		$data = array();
		$this->load->view('guarantor/add', $data);
	}
	
	/** 编辑 */
	public function modify() {
		if(IS_POST) {
			$post = $this->input->post(NULL, TRUE);
			$id = intval($post['id']);
			if(empty($id)) {
				$this->error('数据错误');
			}
			if(! $post['name']){
				$this->error('请完善标姓名!');
			}
			if(! intval($post['phone'])){
				$this->error('请完善手机号!');
			}
			if(! intval($post['sex'])){
				$this->error('请完善性别!');
			} 
			if(! $post['mode']){
				$this->error('请完善车辆型号!');
			}
			if(! round(floatval($post['price']), 2)){
				$this->error('请完善评估价!');
			}
			$data = $this->guarantor_model->get_guarantor_one($id);
			if($data['name'] == $post['name'] && $data['phone'] == $post['phone']) {
				$flag = true;
			} else {
				$flag = false;
			}
			
			$flag = false;
			$data['id'] = $post['id'];
			$data['name'] = $post['name'];
			$data['phone'] = intval($post['phone']);
			//$data['idcard'] = $post['idcard'];
			//$data['address'] = $post['address'];
			$data['sex'] = intval($post['sex']);
			$data['mode'] = $post['mode'];
			$data['price'] = round(floatval($post['price']), 2);
			if(!empty($post['pic'])) {
				$data['pic'] = $post['pic'];
			}
			
			if($flag) {
				$this->load->library('EsignAPI/EsignAPI'); 
				$EsignAPI = new EsignAPI();
				//更新账户
				$data['real_name'] = $data['name'];
				$res = $EsignAPI->updatePersonAccount($data);
				if(!empty($res['errCode'])) {//返回账户标识
					$this->error($res['msg']);
				}
				//重新生成签章
				$type = mb_strlen($data['name']) > 3 ? 'HWXK' : '';
				$res = $EsignAPI->addPersonTemplateSeal($data, $type);
				if(empty($res['errCode'])) {//返回印章base64图片
					$image = set_base64_image($res['imageBase64'], 0);
					$data['sealPath'] = $image;
					unset($data['real_name']);
				} else {
					$this->error($res['msg']);
				}
			}
			
			if($this->guarantor_model->modify_guarantor($data)) {
				$this->success('操作成功');
			} else {
				$this->error('操作失败');
			}
		}
		$id = $this->uri->segment(3);
		if(empty($id)) {
			exit('数据错误');
		}
		
		$data['guarantor'] = $this->guarantor_model->get_guarantor_one($id);
		$this->load->view('guarantor/modify', $data);
	}
	
	/** 判断信息重复 */
	public function is_repeated() {
		$status = $this->uri->segment(3);
		if(IS_POST) {
			//判断手机号是否重复
			if($status == 1) {
				$phone = $this->input->post('phone', true);
				if(!$phone) {
					$this->error('<span style="color:red;">手机号不能为空</span>');
				}
				if(!is_phone($phone)) {
					$this->error('<span style="color:red;">手机号格式不正确</span>');
				}
				$where['phone'] = $phone;
				$guarantor = $this->guarantor_model->get_guarantor_bywhere($where);
				if(!empty($guarantor)) {
					$this->error('<span style="color:red;">手机号重复</span>');
				}
			}
			//判断身份证号是否重复
			if($status == 2) {
				$idcard = $this->input->post('idcard', true);
				if(!$idcard) {
					$this->error('<span style="color:red;">身份证号不能为空</span>');
				}
				$where['idcard'] = $idcard;
				$guarantor = $this->guarantor_model->get_guarantor_bywhere($where);
				if(!empty($guarantor)) {
					$this->error('<span style="color:red;">身份证号重复</span>');
				}
			}
			//编辑时判断手机号是否重复
			if($status == 3) {
				$uid = $this->input->post('uid', true);
				$phone = $this->input->post('phone', true);
				if(!$phone) {
					$this->error('<span style="color:red;">手机号不能为空</span>');
				}
				if(!is_phone($phone)) {
					$this->error('<span style="color:red;">手机号格式不正确</span>');
				}
				$where['phone'] = $phone;
				$where['id <>'] = $uid;
				$guarantor = $this->guarantor_model->get_guarantor_bywhere($where);
				if(!empty($guarantor)) {
					$this->error('<span style="color:red;">手机号重复</span>');
				}
			}
		}
		
	}
	
	/** 生成签章 */
	public function esign() {
		$uid = $this->uri->segment(3);
		
		if(empty($uid)) {
			$this->error('信息错误');
		}
		$accountId = '';//账户标识
		//调取个人信息
		$meminfo = $this->guarantor_model->get_guarantor_one($uid);
		$meminfo['real_name'] = $meminfo['name'];
		if(empty($meminfo['name']) || empty($meminfo['phone']) || empty($meminfo['idcard'])) {
			$this->error('还未实名认证，不能生成签章');
		}
		if(!empty($meminfo['sealPath'])) {
			$this->error('印章已创建,路径为：' . $meminfo['sealPath']);
		}
		//如果没有创建账户，需要重新创建账户
		$this->load->library('EsignAPI/EsignAPI'); 
		$EsignAPI = new EsignAPI();
		if(empty($meminfo['accountId'])) {
			//创建个人账户
			$res = $EsignAPI->addPersonAccount($meminfo);
			//print_r($res);die;
			if(empty($res['errCode'])) {//返回账户标识
				$meminfo['accountId'] = $res['accountId'];
				unset($meminfo['real_name']);
				$this->guarantor_model->modify_guarantor($meminfo);
			} else {
				$this->error($res['msg']);
			}
		}
		
		//生成签章
		$meminfo['real_name'] = $meminfo['name'];
		
		$res = $EsignAPI->addPersonTemplateSeal($meminfo);
		if(empty($res['errCode'])) {//返回印章base64图片
			$image = set_base64_image($res['imageBase64'], $meminfo['id']);
			$meminfo['sealPath'] = $image;
			unset($meminfo['real_name']);
			$this->guarantor_model->modify_guarantor($meminfo);
			$this->error('印章已创建,路径为：' . $meminfo['sealPath']);
		} else {
			$this->error($res['msg']);
		}
	}
	
	
}