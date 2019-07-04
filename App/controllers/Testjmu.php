<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Testjmu extends MY_Controller {
	public function __construct() {
        parent::__construct();
        $this->load->model(array('member/member_model'));
    }//get_member_byusername//modify_member
	public function index(){
		$this->load->helper('common');
		
		$a = get_curl('http://www.jiamanu.cn/api2.aspx?action=getSYTotle');
		echo str_replace(',','',json_decode($a, TRUE)['totle']);
		die;
		
		$this->load->helper('common');
		
		echo MD5(suny_encrypt('abcd123', $this->input->get('salt')));
		
		die;
		$this->load->library('PHPExcel');
		//$this->load->library('PHPExcel/IOFactory');
		//读取
		$PHPReader = new PHPExcel_Reader_Excel2007();
		$filePath = './user.xlsx';
		if(! $PHPReader->canRead($filePath)){
		 	$PHPReader = new PHPExcel_Reader_Excel5();
		 	if(! $PHPReader->canRead($filePath)){
		 		echo 'no Excel';
		 		return ;
		 	}
		}else{
		 	$PHPExcel = $PHPReader->load($filePath); 
		 	$sheet = $PHPExcel->getSheet(0);
		 	$highestRow = $sheet->getHighestRow();//echo $highestRow;
		 	$highestColumm = 'E';//$sheet->getHighestColumn();
		 	for ($row = 2; $row <= $highestRow; $row++){
				for ($column = 'A'; $column <= $highestColumm; $column++) {
		 			$data[$row][] = $sheet->getCell($column.$row)->getValue();
		 		}
			}
			
		}
		foreach($data as $v){
			$tj=$this->member_model->get_member_byusername($v[4]);//推荐人
			$tz=$this->member_model->get_member_byusername($v[2]);//投资人
			//p($tz['id']);echo '<br>';
			if($tj['id'] && $tz['id']){
			//echo '<br>',$tz['id'],'=';
				if($this->member_model->modify_member(array('codeuid'=>$tj['id']),$tz['id'])){
					echo 'ok','=<br>';
				}else{
					echo $tz['id'],',';
				}
			}else{
				echo '<br>',$tz['id'],'/';
			}
		}
		//写入
		/* $resultPHPExcel = new PHPExcel();
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
		$resultPHPExcel->getActiveSheet()->setCellValue('A2', '1');
		$resultPHPExcel->getActiveSheet()->setCellValue('B2', '2');
		$resultPHPExcel->getActiveSheet()->setCellValue('C2', '3');
		$resultPHPExcel->getActiveSheet()->setCellValue('D2', '4');
		$resultPHPExcel->getActiveSheet()->setCellValue('E2', '5');
		$resultPHPExcel->getActiveSheet()->setCellValue('F2', '6');
		$resultPHPExcel->getActiveSheet()->setCellValue('G2', '7');
		$resultPHPExcel->getActiveSheet()->setCellValue('H2', '8');
		$resultPHPExcel->getActiveSheet()->setCellValue('I2', '9');
		$resultPHPExcel->getActiveSheet()->setCellValue('J2', '10');
		$resultPHPExcel->getActiveSheet()->setCellValue('K2', '11');
		$resultPHPExcel->getActiveSheet()->setCellValue('L2', '12');
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