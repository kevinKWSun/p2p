<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Menus extends MY_Controller {
	public function index(){
		$this->load->model(array('auth/admin_model', 'auth/auth_model'));
		$this->load->helper('common');
		$id = intval(isset($_GET['pid'])) ? intval($_GET['pid']) : 14;
		if(! $id){
			$rule = '';
		}else{
			$rule = $this->admin_model->getrule_bypid($id);
		}
		$l = array();
		$r = $this->auth_model->getrules('xm_auth_group_access','xm_auth_group', UID);
		if($r){
			$rs = explode(',', $r[0]['rules']);
		}else{
			$rs = array();
		}
		if($rule){
			foreach($rule as $k => $v){
				if(! IS_ROOT){
					if(! in_array($v['id'], $rs)){
						continue;
					}
				}
				$c = $this->admin_model->getrule_bypid($v['id']);
				if($c){
					if($k == 0){
						$l[$k]['icon'] = 'fa fa-folder-open-o';
						echo '<li class="layui-nav-item layui-nav-itemed">';
					}else{
						echo '<li class="layui-nav-item">';
					}
					echo '<a href="javascript:;" lay-tips="'.$v['title'].'">'.$v['title'].'</a>';
					echo '<dl class="layui-nav-child">';
					foreach($c as $kc => $vc){
						if(! IS_ROOT){
							if(! in_array($vc['id'], $rs)){
								continue;
							}
						}
						echo '<dd><a class="do-admin" href="javascript:;" data-url="'.$vc['name'].'" lay-tips="'.$vc['title'].'">'.$vc['title'].'</a></dd>';
					}
					echo '</dl></li>';
				}else{
					if($k == 0){
						echo '<li class="layui-nav-item layui-nav-itemed">';
					}else{
						echo '<li class="layui-nav-item">';
					}
					echo '<a class="do-admin" href="javascript:;" data-url="'.$v['name'].'" lay-tips="'.$v['title'].'">'.$v['title'].'</a></li>';
				}
			}
		}
	}
}