<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Cli extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model(array('member/member_model', 'recharge/recharge_model', 'account/info_model'));
		$this->load->helper(array('common', 'url'));
	}
	public function index(){
		if($p = $this->input->post(NULL, TRUE)){
			$info = $p['resp_desc'];
			$card = $p['capAcntNo'];
			$uid = $p['user_id_from'];
			$num = $p['mchnt_txn_ssn'];
			$key = $p['signature'];
			$resp_code = $p['resp_code'];
			if($resp_code == '0000'){
				$data = $p['bank_nm'].'|'.$p['capAcntNo'].'|'.$p['certif_id'].'|'.$p['city_id'].'|'.$p['cust_nm'].'|'.$p['email'].'|'.$p['mchnt_cd'].'|'.$p['mchnt_txn_ssn'].'|'.$p['mobile_no'].'|'.$p['parent_bank_id'].'|'.$p['resp_code'].'|'.$p['user_id_from'];
				$s = rsaVerify($data, './Content/php_pbkey.pem', $key);
				if($s){
					$mbank = $this->member_model->get_bank_byuid('', $uid, '');
					if($mbank['status'] == 1){
						exit("<script>alert('已绑定过银行卡,不能再次绑定');window.close();</script>");
					}else{
						if($mbank['card'] == $card && $mbank['num'] == $num){
							if(! $this->member_model->up_bank(array('status' => 1), QUID)){
								exit("<script>alert('绑定成功,但服务器操作失败,请联系客服....');window.close();</script>");
							}else{
								exit("<script>alert('绑定成功...');window.close();</script>");
							}
						}
					}
				}
			}else{
				exit("<script>alert('$info');window.close();</script>");
			}
		}
	}
	public function back(){
		$p = json_encode($_POST);
		$fp = fopen("/data/sftp/cd_shaiyy_com/web/log.txt","a");
		flock($fp, LOCK_EX) ;
		fwrite($fp,"绑卡回写信息：".strftime("%Y%m%d%H%M%S",time())."\n".$p."\n");
		flock($fp, LOCK_UN);
		fclose($fp);
	}
	public function qback(){
		if($p = $this->input->post(NULL, TRUE)){
			$login_id = $p['login_id'];
			$amt = $p['amt'];
			$resp_code = $p['resp_code'];
			$rem = $p['rem'];
			$mchnt_cd = $p['mchnt_cd'];
			$signature = $p['signature'];
			$mchnt_txn_ssn = $p['mchnt_txn_ssn'];
			if($resp_code == '0000'){
				$data = $amt.'|'. $login_id .'|'. $mchnt_cd .'|'.$mchnt_txn_ssn.'|'.$rem .'|'.$resp_code;
				$s = rsaVerify($data, './Content/php_pbkey.pem', $signature);
				if($s){
					$this->db->trans_begin();
					$recharge = $this->recharge_model->get_recharge_one($mchnt_txn_ssn);
					if(! $recharge){
						exit("<script>alert('数据有误,请与管理员联系...');window.close();</script>");
					}
					if($recharge['status'] == 1){
						exit("<script>alert('已绑定过银行卡,不能再次绑定');window.close();</script>");
					}else{
						if($recharge['money'] == $amt / 100){
							//成功后写数据库
							$money = $this->info_model->get_money($recharge['uid']);
							$mlog = array(
								'uid' => $recharge['uid'],
								'type' => 1,
								'affect_money' => $recharge['money'],
								'account_money' => $money['account_money'] + $recharge['money'],//可用
								'collect_money' => $money['money_collect'],//待收
								'freeze_money' => $money['money_freeze'],//冻结
								'info' => 'PC充值',
								'add_time' => time(),
								'add_ip' => $this->input->ip_address(),
								'bid' => $mchnt_txn_ssn
							);
							$um = array(
								'account_money' => $money['account_money'] + $recharge['money'],
							);
							$this->member_model->up_members_money($um, $recharge['uid']);
							$this->member_model->add_members_moneylog($mlog);
							$this->recharge_model->modify_recharge(array('status' => 1), $recharge['id']);
							if($this->db->trans_status() === TRUE){
								$this->db->trans_commit();
								exit("<script>alert('充值成功...');window.close();</script>");
							}else{
								$this->db->trans_rollback();
								exit("<script>alert('充值成功,但服务器操作失败,请联系客服...');window.close();</script>");
							}
						}
					}
				}
			}else{
				exit("<script>alert('$rem');window.close();</script>");
			}
		}
	}
	public function cback(){
		print_r($this->input->post(NULL, TRUE));
		$p = json_encode($_POST);
		$fp = fopen("/data/sftp/cd_shaiyy_com/web/log.txt","a");
		flock($fp, LOCK_EX) ;
		fwrite($fp,"充值回写信息：".strftime("%Y%m%d%H%M%S",time())."\n".$p."\n");
		flock($fp, LOCK_UN);
		fclose($fp);
	}
	public function tqback(){
		if($p = $this->input->post(NULL, TRUE)){
			$login_id = $p['login_id'];
			$amt = $p['amt'];
			$resp_code = $p['resp_code'];
			$rem = $p['rem'];
			$mchnt_cd = $p['mchnt_cd'];
			$signature = $p['signature'];
			$mchnt_txn_ssn = $p['mchnt_txn_ssn'];
			if($resp_code == '0000'){
				$data = $amt.'|'. $login_id .'|'. $mchnt_cd .'|'.$mchnt_txn_ssn.'|'.$rem .'|'.$resp_code;
				$s = rsaVerify($data, './Content/php_pbkey.pem', $signature);
				if($s){
					$this->db->trans_begin();
					$recharge = $this->recharge_model->get_recharge_one($mchnt_txn_ssn);
					if(! $recharge){
						exit("<script>alert('数据有误,请与管理员联系...');window.close();</script>");
					}
					if($recharge['status'] == 1){
						exit("<script>alert('已绑定过银行卡,不能再次绑定');window.close();</script>");
					}else{
						if($recharge['money'] == $amt / 100){
							//成功后写数据库
							$money = $this->info_model->get_money($recharge['uid']);
							$mlog = array(
								'uid' => $recharge['uid'],
								'type' => 2,
								'affect_money' => $recharge['money'],
								'account_money' => $money['account_money'] - $recharge['money'],//可用
								'collect_money' => $money['money_collect'],//待收
								'freeze_money' => $money['money_freeze'],//冻结
								'info' => 'PC提现',
								'add_time' => time(),
								'add_ip' => $this->input->ip_address(),
								'bid' => $mchnt_txn_ssn
							);
							$um = array(
								'account_money' => $money['account_money'] - $recharge['money'],
							);
							$this->member_model->up_members_money($um, $recharge['uid']);
							$this->member_model->add_members_moneylog($mlog);
							$this->recharge_model->modify_recharge(array('status' => 1), $recharge['id']);
							if($this->db->trans_status() === TRUE){
								$this->db->trans_commit();
								exit("<script>alert('提现成功...');window.close();</script>");
							}else{
								$this->db->trans_rollback();
								exit("<script>alert('提现成功,但服务器操作失败,请联系客服...');window.close();</script>");
							}
						}
					}
				}
			}else{
				exit("<script>alert('$rem');window.close();</script>");
			}
		}
	}
	public function tcback(){
		print_r($this->input->post(NULL, TRUE));
		$p = json_encode($_POST);
		$fp = fopen("/data/sftp/cd_shaiyy_com/web/log.txt","a");
		flock($fp, LOCK_EX) ;
		fwrite($fp,"提现回写信息：".strftime("%Y%m%d%H%M%S",time())."\n".$p."\n");
		flock($fp, LOCK_UN);
		fclose($fp);
	}
	public function excel(){
		//$this->load->helper('common');
		//$citys = json_decode($this->config->item('city'), TRUE);
		//p($citys);
		//读取
		$this->load->library('PHPExcel/IOFactory');
		$PHPReader = new PHPExcel_Reader_Excel5();
		$filePath = './area.xls';
		$PHPExcel = $PHPReader->load($filePath); 
		$sheet = $PHPExcel->getSheet(0);
		$highestRow = $sheet->getHighestRow();
		$highestColumm = 'D';//$sheet->getHighestColumn();
		for ($row = 2; $row <= $highestRow; $row++){
			for ($column = 'C'; $column <= $highestColumm; $column++) {
				$data[$row][] = $sheet->getCell($column.$row)->getValue();
			}
		}
		p($data);die;
		/* //导出
		$this->load->library('PHPExcel');
		$resultPHPExcel = new PHPExcel();
		//设置参数 
		$resultPHPExcel->getActiveSheet()->setCellValue('A1', '部门职位');
		$resultPHPExcel->getActiveSheet()->setCellValue('B1', '业务员');
		$resultPHPExcel->getActiveSheet()->setCellValue('C1', '放款额(万元)');
		$resultPHPExcel->getActiveSheet()->setCellValue('D1', 'P2P新单(元)');
		$resultPHPExcel->getActiveSheet()->setCellValue('E1', '续单(元)');
		$resultPHPExcel->getActiveSheet()->setCellValue('F1', '垫资(元)');
		$resultPHPExcel->getActiveSheet()->setCellValue('G1', '银行过桥(元)');
		$resultPHPExcel->getActiveSheet()->setCellValue('H1', '民间过桥(元)');
		$resultPHPExcel->getActiveSheet()->setCellValue('I1', '短借(元)');
		$resultPHPExcel->getActiveSheet()->setCellValue('J1', '总笔数(笔)');
		$resultPHPExcel->getActiveSheet()->setCellValue('K1', '基数(元)');
		$resultPHPExcel->getActiveSheet()->setCellValue('L1', '违约金(元)');
		$resultPHPExcel->getActiveSheet()->setCellValue('A2', '部门职位');
		$resultPHPExcel->getActiveSheet()->setCellValue('B2', '业务员');
		$resultPHPExcel->getActiveSheet()->setCellValue('C2', '放款额(万元)');
		$resultPHPExcel->getActiveSheet()->setCellValue('D2', 'P2P新单(元)');
		$resultPHPExcel->getActiveSheet()->setCellValue('E2', '续单(元)');
		$resultPHPExcel->getActiveSheet()->setCellValue('F2', '垫资(元)');
		$resultPHPExcel->getActiveSheet()->setCellValue('G2', '银行过桥(元)');
		$resultPHPExcel->getActiveSheet()->setCellValue('H2', '民间过桥(元)');
		$resultPHPExcel->getActiveSheet()->setCellValue('I2', '短借(元)');
		$resultPHPExcel->getActiveSheet()->setCellValue('J2', '总笔数(笔)');
		$resultPHPExcel->getActiveSheet()->setCellValue('K2', '基数(元)');
		$resultPHPExcel->getActiveSheet()->setCellValue('L2', '违约金(元)');
		//设置导出文件名
		$outputFileName = '个人总单.xls'; 
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
		$xlsWriter->save( "php://output" ); */
	}
}