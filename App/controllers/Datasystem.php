<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Datasystem extends CI_Controller {
	public function __construct(){
        parent::__construct();
        //$this->load->model('user/login_model');
    }
	public function index(){
		$url = $this->input->get('url');
		$l = array();
		if($url){
			$this->load->helper('common');
			$this->load->library('PHPExcel');
			//读取
			$PHPReader = new PHPExcel_Reader_Excel2007();
			$filePath = './' . $url;
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
				$highestColumm = 'P';//$sheet->getHighestColumn();
				for ($row = 2; $row <= $highestRow; $row++){
					for ($column = 'A'; $column <= $highestColumm; $column++) {
						$data[$row][] = $sheet->getCell($column.$row)->getValue();
					}
				}//p($data);
				foreach($data as $k => $v){
					if($v[15] == '闫帅'){
						if($v[9] == 33){
							if(! isset($l[$v[15]][$v[1]][33])){
								$l[$v[15]][$v[1]][33] = $v[10];
							}else{
								$l[$v[15]][$v[1]][33] += $v[10];
							}
							$l[$v[15]][$v[1]]['name'] = $v[3];
						}elseif($v[9] == 65){
							if(! isset($l[$v[15]][$v[1]][65])){
								$l[$v[15]][$v[1]][65] = $v[10];
							}else{
								$l[$v[15]][$v[1]][65] += $v[10];
							}
							$l[$v[15]][$v[1]]['name'] = $v[3];
						}elseif($v[9] == 97){
							if(! isset($l[$v[15]][$v[1]][97])){
								$l[$v[15]][$v[1]][97] = $v[10];
							}else{
								$l[$v[15]][$v[1]][97] += $v[10];
							}
							$l[$v[15]][$v[1]]['name'] = $v[3];
						}
					}else{
						if($v[9] == 33){
							if(! isset($l[$v[15]][33])){
								$l[$v[15]][33] = $v[10];
							}else{
								$l[$v[15]][33] += $v[10];
							}
						}elseif($v[9] == 65){
							if(! isset($l[$v[15]][65])){
								$l[$v[15]][65] = $v[10];
							}else{
								$l[$v[15]][65] += $v[10];
							}
						}elseif($v[9] == 97){
							if(! isset($l[$v[15]][97])){
								$l[$v[15]][97] = $v[10];
							}else{
								$l[$v[15]][97] += $v[10];
							}
						}
					}
				}
			}
		}
		$d['l'] = $l;
		$this->load->view('datasystem/index', $d);
	}
}