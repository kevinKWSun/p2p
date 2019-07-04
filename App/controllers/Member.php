<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Member extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->library('pagination');
		$this->load->model('member/member_model');
		//$this->load->helper('url');
	}

	public function cz(){
		if($p = $this->input->post(NULL, TRUE)){
			if($p['score'] == 0){
				$this->error('无需操作!');
			}
			$moneyslog = array(
				'uid' => $p['uid'],
				'type' => 15,
				'affect_money' => $p['score'],
				'account_money' => $p['score']+$p['account_money'],
				'collect_money' => '0.00',
				'freeze_money' => '0.00',
				'info' => '补站内金额与电子金额差额',
				'add_ip' => $this->input->ip_address(),
				'actualAmt' => '0',
				'pledgedAmt' => '0',
				'preLicAmt' => '0',
				'totalAmt' => '0',
				'acctNo' => '0',
				'nid' => '0',
				'add_time' => time()
			);
			$moneys = array(
				'account_money' => $p['score']+$p['account_money']
			);
			$this->db->trans_begin();
			$this->member_model->up_members_money($moneys, $p['uid']);
			$this->member_model->add_members_moneylog($moneyslog);
			if($this->db->trans_status() === TRUE){
				$this->db->trans_commit();
				$mem = $this->member_model->get_member_byuserid($p['uid']);
				$url = $mem['attribute'] == 1 ? '/member.html' : '/company.html';
				$this->success('操作成功!','/member.html');
			}else{
				$this->db->trans_rollback();
				$this->error('操作失败!');
			}
		}
		$datas = array();
		$id = $this->uri->segment(3);
		if(empty($id)) {
			exit('数据错误');
		}
		$datas['id'] = $id;
		$datas['meminfo'] = $this->member_model->get_member_info_byuid($id);
		$params['acctNo'] = $datas['meminfo']['acctNo'];
		$head = head($params, 'CG2001', 'over');
		unset($head['callbackUrl'], $head['registerPhone'], $head['responsePath'], $head['url']);
		$data = $head;
		$data = json_encode($data);
		//var_dump($data);die;
		$url = $this->config->item('Interface').'2001';
		//var_dump($url);die;
		//请求接口
		$str = post_curl_test($url, $data);
		$this->load->model(array('paytest/paytest_model'));
		$tmp_body = $this->paytest_model->excute($str);
		$actualAmt = isset($tmp_body['body'])?$tmp_body['body']['actualAmt']:0;
		$datas['actualAmt'] = $actualAmt;
		$datas['meminfo']['account_money'] = $this->member_model->get_members_money_byuid($id)['account_money'];
		$this->load->view('member/cz', $datas);
	}
	public function hb(){
		if($p = $this->input->post(NULL, TRUE)){
			$packet = array(
				array(
					'uid' => $p['uid'],
					'stime' => time(),
					'etime' => time()+86400*30,
					'money' => $p['money'],
					'min_money' => $p['moneys'],
					'times' => $p['times'],
					'addtime' => time(),
					'admin_id' => UID
				)
			);
			if($this->member_model->addall_packet($packet)){
				$this->success('操作成功!','/member.html');
			}else{
				$this->error('操作失败!');
			}
		}
		$data = array();
		$id = $this->uri->segment(3);
		if(empty($id)) {
			exit('数据错误');
		}
		$data['id'] = $id;
		$data['meminfo'] = $this->member_model->get_member_info_byuid($id);
		$this->load->view('member/hb', $data);
	}
	//流水
	public function water(){
		$member = $this->member_model->get_water_dz();
		$data['member'] = $member;
		$this->load->view('member/water', $data);
	}
	//资金不一致对照表
	public function dzb(){
		$member = $this->member_model->get_member_money_dz();
		foreach($member as $k=>$v){
			$params['acctNo'] = get_member_info($v['uid'])['acctNo'];
			$head = head($params, 'CG2001', 'over');
			unset($head['callbackUrl'], $head['registerPhone'], $head['responsePath'], $head['url']);
			$data = $head;
			$data = json_encode($data);
			//var_dump($data);die;
			$url = $this->config->item('Interface').'2001';
			//var_dump($url);die;
			//请求接口
			$str = post_curl_test($url, $data);
			$this->load->model(array('paytest/paytest_model'));
			$tmp_body = $this->paytest_model->excute($str);
			$member[$k]['actualAmt'] = isset($tmp_body['body']['actualAmt'])?$tmp_body['body']['actualAmt']:0;
			if($member[$k]['actualAmt'] == $v['account_money']){
				unset($member[$k]);
			}
		}
        $data['member'] = $member;
		$this->load->view('member/dzb', $data);
	}
	//会员列表
	public function index(){
		$data = array();
		$where = array();
		$search = $this->input->get(null, true);
		if(!empty($search['skey'])) {
			$data['skey'] = trim(trim($search['skey']), '\t');
			$where['skey'] = $data['skey'];
		}
		if(!empty($search['codename'])) {
			$data['codename'] = trim(trim($search['codename']), '\t');
			$where['codename'] = $data['codename'];
		}
		$where['members.attribute'] = 1;
		
		$per_page = 100;
		$current_page = $this->uri->segment(3) ? $this->uri->segment(3) - 1 : 0;
        $offset = $current_page;
        $config['base_url'] = base_url('member/index');
        $config['total_rows'] = $this->member_model->get_member_related_num($where);
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
		$data['skip_page'] = $this->pagination->create_skip_link();
        $data['p'] = $current_page;
        $member = $this->member_model->get_member_related($per_page, $offset * $per_page, $where);
		foreach($member as $k=>$v) {
			$money = $this->member_model->get_members_money_byuid($v['id']);
			$member[$k]['account_money'] = $money['account_money'];
			$member[$k]['money_collect'] = $money['money_collect'];
			$member[$k]['money_freeze'] = $money['money_freeze'];
		}
        $data['member'] = $member;
		$this->load->view('member/member', $data);
	}
	/** 会员导出 */
	public function export() {
		$data = array();
		$where = array();
		$search = $this->input->get(null, true);
		if(!empty($search['skey'])) {
			$data['skey'] = trim(trim($search['skey']), '\t');
			$where['skey'] = $data['skey'];
		}
		if(!empty($search['codename'])) {
			$data['codename'] = trim(trim($search['codename']), '\t');
			$where['codename'] = $data['codename'];
		}
		$where['members.attribute'] = 1;
		$config['total_rows'] = $this->member_model->get_member_related_num($where);
		$member = $this->member_model->get_member_related($config['total_rows'], 0, $where);
		foreach($member as $k=>$v) {
			$money = $this->member_model->get_members_money_byuid($v['id']);
			$member[$k]['account_money'] = $money['account_money'];
			$member[$k]['money_collect'] = $money['money_collect'];
			$member[$k]['money_freeze'] = $money['money_freeze'];
		}
		$all = $member;
		$this->load->helper('common');
		$this->load->library('PHPExcel');
		$resultPHPExcel = new PHPExcel();
		$resultPHPExcel->getActiveSheet()->setCellValue('A1', 'ID');
		$resultPHPExcel->getActiveSheet()->setCellValue('B1', '姓名');
		$resultPHPExcel->getActiveSheet()->setCellValue('C1', '手机');
		$resultPHPExcel->getActiveSheet()->setCellValue('D1', '身份证');
		$resultPHPExcel->getActiveSheet()->setCellValue('E1', '客户号码');
		$resultPHPExcel->getActiveSheet()->setCellValue('F1', '账户号码');
		$resultPHPExcel->getActiveSheet()->setCellValue('G1', '账户余额');
		$resultPHPExcel->getActiveSheet()->setCellValue('H1', '待收金额');
		$resultPHPExcel->getActiveSheet()->setCellValue('I1', '冻结金额');
		$resultPHPExcel->getActiveSheet()->setCellValue('J1', '积分');
		$resultPHPExcel->getActiveSheet()->setCellValue('K1', '推荐人');
		$resultPHPExcel->getActiveSheet()->setCellValue('K1', '注册时间');
		$i = 1;
		foreach($all as $k => $v){
			$i++;
			$resultPHPExcel->getActiveSheet()->setCellValue('A'.$i, $v['id']);
			$resultPHPExcel->getActiveSheet()->setCellValue('B'.$i, $v['real_name']);
			$resultPHPExcel->getActiveSheet()->setCellValue('C'.$i, $v['phone']." ");
			$resultPHPExcel->getActiveSheet()->setCellValue('D'.$i, $v['idcard']." ");
			$resultPHPExcel->getActiveSheet()->setCellValue('E'.$i, $v['custNo']." ");
			$resultPHPExcel->getActiveSheet()->setCellValue('F'.$i, $v['acctNo']." ");
			$resultPHPExcel->getActiveSheet()->setCellValue('G'.$i, $v['account_money']);
			$resultPHPExcel->getActiveSheet()->setCellValue('H'.$i, $v['money_collect']);
			$resultPHPExcel->getActiveSheet()->setCellValue('I'.$i, $v['money_freeze']);
			$resultPHPExcel->getActiveSheet()->setCellValue('J'.$i, $v['totalscore']);
			$resultPHPExcel->getActiveSheet()->setCellValue('K'.$i, $v['codename']);
			$resultPHPExcel->getActiveSheet()->setCellValue('L'.$i, date('Y-m-d', $v['reg_time']));
		}
		$outputFileName = '会员信息.xls'; 
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
	public function upcode(){
		if(IS_POST) {
			$post = $this->input->post(NULL, TRUE);
			$phones = intval($post['phones']);
			$codeuid = intval($post['codeuid']);
			if(! $phones) {
				$this->error('手机号不能为空');
			}
			if(! $codeuid) {
				$this->error('推荐邀请码不能为空');
			}
			if(! $this->member_model->get_member_byuserid($codeuid)){
				$this->error('推荐邀请码不存在');
			}
			$phones = explode(',', $phones);
			foreach($phones as $v){
				if($infouser = $this->member_model->get_member_byusername($v)){
					$this->member_model->modify_member(array('codeuid'=>$codeuid),$infouser['id']);
				}
			}
			$this->success('操作成功', '/member.html');
		}
		$this->load->view('member/upcode');
	}
	//冲积分
	public function score() {
		if(IS_POST) {
			$post = $this->input->post(NULL, TRUE);
			$data = array();
			$data['uid'] = intval($post['uid']);
			if(empty($data['uid'])) {
				$this->error('数据错误');
			}
			$data['score'] = round($post['score'], 2);
			//记录操作
			$data['genre'] = 1;//冲积分
			$data['adminid'] = UID;
			$data['addtime'] = time();
			$data['remark'] = trim($post['remark']);
			if($data['score'] < 0) {
				$meminfo = $this->member_model->get_member_info_byuid($data['uid']);
				if(($meminfo['totalscore'] + $data['score']) < 0) {
					$this->error('操作失败，积分剩余：'.$meminfo['totalscore']);
				}
			}
			if($this->member_model->set_member_info_totalscore($data)) {
				$this->success('操作成功');
			} else {
				$this->error('操作失败');
			}
		}
		$data = array();
		$id = $this->uri->segment(3);
		if(empty($id)) {
			exit('数据错误');
		}
		$data['id'] = $id;
		$data['meminfo'] = $this->member_model->get_member_info_byuid($id);
		$this->load->view('member/score', $data);
	}
	
	/** 冲抽奖次数 */
	public function times() {
		$this->load->model('cj/zcash_model');
		if(IS_POST) {
			$post = $this->input->post(NULL, TRUE);
			$uid = intval($post['uid']);
			$times = intval($post['times']);
			$doub = intval($post['doub']);
			$data['times'] = [
				'uid'	=> $uid,
				'num' => $times,
				'adminid' => UID,
				'addtime' => time(),
				'type'	=> 2,
				'remark' => mb_substr($post['remark'], 0, 254),
				'multiple' => $doub
			];
			$zcash = $this->zcash_model->get_by_uid($uid);
			if(!empty($zcash)) {
				$data['zcash'] = [
					'id' => $zcash['id']
				];
				if($doub === 1) {
					$data['zcash']['total'] = $zcash['total'] + $times;
				}
				if($doub === 2) {
					$data['zcash']['doub'] = $zcash['doub'] + $times;
				}
			} else {
				$data['zcash'] = [
					'uid' => $uid,
				];
				if($doub === 1) {
					$data['zcash']['total'] = $times;
				}
				if($doub === 2) {
					$data['zcash']['doub'] = $times;
				}
			}
			if($this->zcash_model->set_total_card($data)) {
				$this->success('操作成功');
			} else {
				$this->error('操作失败');
			}
		}
		$data = array();
		$id = $this->uri->segment(3);
		if(empty($id)) {
			exit('数据错误');
		}
		$data['id'] = $id;
		$data['meminfo'] = $this->member_model->get_member_info_byuid($id);
		$data['zcash'] = $this->zcash_model->get_by_uid($id);
		
		$this->load->view('member/times', $data);
	}
	/** 生成签章 */
	public function esign() {
		$uid = $this->uri->segment(3);
		
		if(empty($uid)) {
			$this->error('信息错误');
		}
		$accountId = '';//账户标识
		//调取个人信息
		$meminfo = $this->member_model->get_member_info_byuid($uid);
		if(empty($meminfo['real_name']) || empty($meminfo['phone']) || empty($meminfo['idcard'])) {
			$this->error('还未实名认证，不能生成签章');
		}
		if(!empty($meminfo['sealPath'])) {
			$this->error('印章已创建,路径为：' . $meminfo['sealPath']);
		}
		//如果没有创建账户，需要重新创建账户
		$this->load->library('EsignAPI/EsignAPI'); 
		$EsignAPI = new EsignAPI();
		if(empty($meminfo['accountId'])) {
			//创建企业账户
			$res = $EsignAPI->addPersonAccount($meminfo);
			//print_r($res);die;
			if(empty($res['errCode'])) {//返回账户标识
				$meminfo['accountId'] = $res['accountId'];
				$this->member_model->up_members_info($meminfo, $meminfo['uid']);
			} else {
				$this->error($res['msg']);
			}
		}
		
		//生成签章
		$res = $EsignAPI->addPersonTemplateSeal($meminfo);
		if(empty($res['errCode'])) {//返回印章base64图片
			$image = set_base64_image($res['imageBase64'], $meminfo['uid']);
			$meminfo['sealPath'] = $image;
			$this->member_model->up_members_info($meminfo, $meminfo['uid']);
			$this->error('印章已创建,路径为：' . $meminfo['sealPath']);
		} else {
			$this->error($res['msg']);
		}
	}
	
}