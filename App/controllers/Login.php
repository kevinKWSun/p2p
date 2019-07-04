<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Login extends CI_Controller {
	public function __construct(){
        parent::__construct();
        $this->load->model('user/login_model');
    }
    public function excel(){
		$this->load->helper('common');
		$this->load->library('PHPExcel');
		//$this->load->library('PHPExcel/IOFactory');
		//读取
		/* $PHPReader = new PHPExcel_Reader_Excel2007();
		$filePath = './zx.xlsx';
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
		 	$highestColumm = 'C';//$sheet->getHighestColumn();
		 	for ($row = 2; $row <= $highestRow; $row++){
				for ($column = 'A'; $column <= $highestColumm; $column++) {
		 			$data[$row][] = $sheet->getCell($column.$row)->getValue();
		 		}
			}
			p($data);die;
		} */
		//写入
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
		$xlsWriter->save( "php://output" );
    }
	//登录
	public function index(){
		$this->load->helper(array('form', 'url', 'common'));
		$this->load->library('session');
		if($this->input->post()){
			$data['name'] = $this->input->post_get('user_name', TRUE);
			$data['password'] = $this->input->post_get('password', TRUE);
			if(! $data['name']){
				$info['state'] = 0;
				$info['message'] = '请输入账号!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			if(! $data['password']){
				$info['state'] = 0;
				$info['message'] = '请输入密码!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
			}
			$result = $this->login_model->get_user($data['name']);
	        if($result['pwd'] != suny_encrypt($data['password'],'3ed1lio0')){
	        	$info['state'] = 0;
				$info['message'] = '用户名或密码不正确!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
	        }
	        if($result['type'] != 1){
	        	$info['state'] = 0;
				$info['message'] = '已被锁定,请联系管理员!';
				$this->output
			    ->set_content_type('application/json', 'utf-8')
			    ->set_output(json_encode($info))
				->_display();
			    exit;
	        }
	        $info['state'] = 1;
			$info['message'] = '成功!';
			$info['url'] = 'adminr.html';
			$array = array(
                'uid' => $result['id']
            );
            $this->session->set_userdata($array);
			$this->output
		    ->set_content_type('application/json', 'utf-8')
		    ->set_output(json_encode($info))
			->_display();
		    exit;

		}else{
			$this->load->view('user/login');
		}
	}
	//上传图片压缩
	public function img(){
		$dir = iconv('UTF-8', 'GBK', './code/' . date('Y-m-d'));
        if(! file_exists($dir)){
            mkdir ($dir,0777,true);
            echo '创建成功';
        }else{
            echo '文件夹已经存在';
        }
		$this->load->view('user/img');
	}
	public function do_file(){
		$dir = iconv('UTF-8', 'GBK', './file/' . date('Ymd'));
        if(! file_exists($dir)){
            mkdir ($dir,0777,true);
        }
		$config['upload_path']      = './file/' . date('Ymd');
        $config['allowed_types']    = 'xlsx';
        $config['max_size']         = 20 * 1024;
		$config['encrypt_name']     = TRUE;
        $this->load->library('upload', $config);
        if ( ! $this->upload->do_upload('file')){
            //$error = array('error' => $this->upload->display_errors());
            //$data = array('code'=>201,'msg'=>$error);
			$data = array('code'=>201,'msg'=>'上传失败.');
        }else{
            $data = array('upload_data' => $this->upload->data());
			$data = array('code'=>0,'data'=>array('src'=>'/file/' . date('Ymd') . '/' . $data['upload_data']['file_name'],'title'=>''));
        }
		$this->output
		    ->set_content_type('text/html', 'utf-8')
		    ->set_output(json_encode($data))
			->_display();
		    exit;
	}
	public function ydo_upload(){
		$dir = iconv('UTF-8', 'GBK', './code/' . date('Ymd'));
        if(! file_exists($dir)){
            mkdir ($dir,0777,true);
        }
		$config['upload_path']      = './code/' . date('Ymd');
        $config['allowed_types']    = 'gif|jpg|png';
        $config['max_size']         = 5 * 1024;
		$config['encrypt_name']     = TRUE;
        //$config['max_width']        = 1920;
        //$config['max_height']       = 1080;
        $this->load->library('upload', $config);
        if ( ! $this->upload->do_upload('file')){
            //$error = array('error' => $this->upload->display_errors());
            $data = array('code'=>201,'msg'=>'上传失败.');
        }else{
            $data = array('upload_data' => $this->upload->data());
			$data = array('code'=>0,'data'=>array('src'=>'/code/' . date('Ymd') . '/' . $data['upload_data']['file_name'],'title'=>''));
        }
		$this->output
		    ->set_content_type('text/html', 'utf-8')
		    ->set_output(json_encode($data))
			->_display();
		    exit;
	}
	public function do_upload(){
		$dir = iconv('UTF-8', 'GBK', './code/' . date('Ymd'));
        if(! file_exists($dir)){
            mkdir ($dir,0777,true);
        }
		$config['upload_path']      = './code/' . date('Ymd');
        $config['allowed_types']    = 'gif|jpg|png';
        $config['max_size']         = 5 * 1024;
		$config['encrypt_name']     = TRUE;
        //$config['max_width']        = 1920;
        //$config['max_height']       = 1080;
        $this->load->library('upload', $config);
        if ( ! $this->upload->do_upload('file')){
            //$error = array('error' => $this->upload->display_errors());
            $data = array('code'=>201,'msg'=>'上传失败.');
        }else{
            $data = array('upload_data' => $this->upload->data());
			$config['image_library'] = 'gd2';
            $config['source_image'] = './code/' . date('Ymd') . '/' . $data['upload_data']['file_name'];
			$config['create_thumb'] = FALSE;
			$config['maintain_ratio'] = TRUE;
			$config['width']     = 400;
			//$config['height']   = 150;
			
			
			if($this->uri->segment(3) != 1) {
				$this->load->library('image_lib', $config);
				$ret_resize = $this->image_lib->resize();
			} else {
				$ret_resize = true;
			}
			
			if ( ! $ret_resize){
				$data = array('code'=>201,'msg'=>'压缩失败.');
				//echo $this->image_lib->display_errors();
			}else{
				$data = array('code'=>0,'data'=>array('src'=>'/code/' . date('Ymd') . '/' . $data['upload_data']['file_name'],'title'=>''));
			}
        }
		$this->output
		    ->set_content_type('text/html', 'utf-8')
		    ->set_output(json_encode($data))
			->_display();
		    exit;
	}
	public function do_uploads(){
		$dir = iconv('UTF-8', 'GBK', './code/' . date('Ymd'));
        if(! file_exists($dir)){
            mkdir ($dir,0777,true);
        }
		$d = $_FILES;
		$config['upload_path']      = './code/' . date('Ymd');
        $config['allowed_types']    = 'gif|jpg|png';
        $config['max_size']         = 5 * 1024;
		$config['encrypt_name']     = TRUE;
        //$config['max_width']        = 1920;
        //$config['max_height']       = 1080;
        $this->load->library('upload', $config);
		foreach($d['userfile']['name'] as $k => $v){
			$_FILES['userfile'] = array(
				'name' => $v,
				'type' => $d['userfile']['type'][$k],
				'tmp_name' => $d['userfile']['tmp_name'][$k],
				'error' => $d['userfile']['error'][$k],
				'size' => $d['userfile']['size'][$k]
			);
			if ( ! $this->upload->do_upload('userfile')){echo 111;
				//$error = array('error' => $this->upload->display_errors());
				$data[$k] = array('code'=>201,'errorMsg'=>'上传失败.');
				//$data[$k] = array('code'=>201,'errorMsg'=>$error);
			}else{
				$data[$k] = array('upload_data' => $this->upload->data());
				$config['image_library'] = 'gd2';
				$config['source_image'] = './code/' . date('Ymd') . '/' . $data[$k]['upload_data']['file_name'];
				$config['create_thumb'] = FALSE;
				$config['maintain_ratio'] = TRUE;
				$config['width']     = 600;
				//$config['height']   = 150;
				$this->load->library('image_lib', $config);
				if ( ! $this->image_lib->resize()){
					$data[$k] = array('code'=>201,'errorMsg'=>'压缩失败.');
					//echo $this->image_lib->display_errors();
				}else{
					$data[$k] = array('code'=>200,'savepath'=>'/code/' . date('Ymd') . '/' . $data[$k]['upload_data']['file_name']);
				}
			}
		}
		$this->output
			->set_content_type('text/html', 'utf-8')
			->set_output(json_encode($data))
			->_display();
			exit;
	}
}
