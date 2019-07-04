<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Ausers extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->library('pagination');
		$this->load->model(array('user/ausers_model', 'auth/auth_model'));
		$this->load->helper('url');
	}
	//搜索
	public function solor(){
		$keywords = $this->input->post_get('keywords', TRUE);
		if($this->input->post()){
			if(! $keywords){
				$info['state'] = 0;
				$info['message'] = '请输入关键字!';
				$this->output
				    ->set_content_type('application/json', 'utf-8')
				    ->set_output(json_encode($info))
					->_display();
				    exit;
			}else{
				$info['state'] = 1;
				$info['message'] = urldecode($keywords);
				$this->output
				    ->set_content_type('application/json', 'utf-8')
				    ->set_output(json_encode($info))
					->_display();
				    exit;
			}
		}
		$keywords = urldecode($this->input->get('query', TRUE));
		$current_page = $this->uri->segment(3) ? $this->uri->segment(3) : 1;
        if($current_page > 0){
            $current_page = $current_page - 1;
        }else if($current_page < 0){
            $current_page = 0;
        }
		if(! IS_ROOT){
			$admin_id = UID;
		}else{
			$admin_id = '';
		}
		$per_page = 20;
        $offset = $current_page;
        $config['base_url'] = base_url('ausers/solor/');
        $config['total_rows'] = $this->ausers_model->get_ausers_num($keywords, $admin_id);
        $config['per_page'] = $per_page;
		$config['page_query_string'] = FALSE;
		$config['first_link'] = '首页'; // 第一页显示   
		$config['last_link'] = '末页'; // 最后一页显示   
		$config['next_link'] = '下一页'; // 下一页显示   
		$config['prev_link'] = '上一页'; // 上一页显示   
		$config['cur_tag_open'] = ' <span class="current">'; // 当前页开始样式   
		$config['cur_tag_close'] = '</span>';   
		$config['num_links'] = 10;
		$config['uri_segment'] = 4;
		$config['use_page_numbers'] = TRUE;
		$config['reuse_query_string'] = TRUE;
        $this->pagination->initialize($config); 
        $data['totals'] = $config['total_rows'];
        $data['page'] = $this->pagination->create_links();
        $data['p'] = $current_page;
        $ausers = $this->ausers_model->get_ausers($per_page, $offset * $per_page, $keywords, $admin_id);
        foreach($ausers as $k => $v){
        	$group = $this->auth_model->getrules('xm_auth_group_access','xm_auth_group', $v['id'], 1);
        	$ausers[$k]['gid'] = $group ? $group[0]['title'] : '无';
        }
        $data['ausers'] = $ausers;
		$this->load->view('user/ausers', $data);
	}
	//列表
	public function index(){
        $current_page = $this->uri->segment(3) ? $this->uri->segment(3) : 1;
        if($current_page > 0){
            $current_page = $current_page - 1;
        }else if($current_page < 0){
            $current_page = 0;
        }
		if(! IS_ROOT){
			$admin_id = UID;
		}else{
			$admin_id = '';
		}
		$per_page = 20;
        $offset = $current_page;
        $config['base_url'] = base_url('ausers/index');
        $config['total_rows'] = $this->ausers_model->get_ausers_num('', $admin_id);
        $config['per_page'] = $per_page;
		$config['page_query_string'] = FALSE;
		$config['first_link'] = '首页'; // 第一页显示   
		$config['last_link'] = '末页'; // 最后一页显示   
		$config['next_link'] = '下一页'; // 下一页显示   
		$config['prev_link'] = '上一页'; // 上一页显示   
		$config['cur_tag_open'] = ' <span class="layui-laypage-curr"><em class="layui-laypage-em"></em><em>'; // 当前页开始样式   
		$config['cur_tag_close'] = '</em></span>';   
		$config['num_links'] = 10;
		$config['uri_segment'] = 3;
		$config['use_page_numbers'] = TRUE;
        $this->pagination->initialize($config); 
        $data['totals'] = $config['total_rows'];
        $data['page'] = $this->pagination->create_links();
        $data['p'] = $current_page;
        $ausers = $this->ausers_model->get_ausers($per_page, $offset * $per_page, '', $admin_id);
        foreach($ausers as $k => $v){
        	$group = $this->auth_model->getrules('xm_auth_group_access','xm_auth_group', $v['id'], 1);
        	$ausers[$k]['gid'] = $group ? $group[0]['title'] : '无';
        }
        $data['ausers'] = $ausers;
		$this->load->view('user/ausers', $data);
	}
	//增加
	public function add(){
		if($this->input->post()){
			$lname = trim($this->input->post_get('lname', TRUE));
			$rname = trim($this->input->post_get('rname', TRUE));
			$pd = trim($this->input->post_get('pd', TRUE));
			$pd2 = trim($this->input->post_get('pd2', TRUE));
			$gids = trim($this->input->post_get('gids', TRUE));
			if(! $gids){
				$info['state'] = 0;
				$info['message'] = '请选择所属权限!';
				$this->output
				    ->set_content_type('application/json', 'utf-8')
				    ->set_output(json_encode($info))
					->_display();
				    exit;
			}
			if(! is_password($lname, 2)){
				$info['state'] = 0;
				$info['message'] = '登录名称(由字母组成,至少3位)!';
				$this->output
				    ->set_content_type('application/json', 'utf-8')
				    ->set_output(json_encode($info))
					->_display();
				    exit;
			}
			if(! $rname){
				$info['state'] = 0;
				$info['message'] = '真实姓名不能为空!';
				$this->output
				    ->set_content_type('application/json', 'utf-8')
				    ->set_output(json_encode($info))
					->_display();
				    exit;
			}
			if(! is_password($pd)){
				$info['state'] = 0;
				$info['message'] = '登录密码(包含字母和数字,至少6位)!';
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
			if($this->ausers_model->get_ausers_byname($lname) > 0){
				$info['state'] = 0;
				$info['message'] = '登录名称已存在!';
				$this->output
				    ->set_content_type('application/json', 'utf-8')
				    ->set_output(json_encode($info))
					->_display();
				    exit;
			}
			$data = array(
				'name' => $lname,
				'realname' => $rname,
				'pwd' => suny_encrypt($pd,'3ed1lio0'),
				'gid' => $gids,
				'addtime' => time(),
				'admin_id' => UID
			);
			if(! IS_ROOT){
				$gid = $this->auth_model->getrules('xm_auth_group_access','xm_auth_group', UID);
				if($gid[0]['id'] != $gids){
					$info['state'] = 0;
					$info['message'] = '服务器链接超时!';
					$this->output
					    ->set_content_type('application/json', 'utf-8')
					    ->set_output(json_encode($info))
						->_display();
					    exit;
				}
			}
			$this->db->trans_begin();
			$uid = $this->ausers_model->add_ausers($data);
			$ag = array(
				'uid' => $uid,
				'group_id' => $gids
			);
			$this->ausers_model->get_ag_add($ag);
			if($this->db->trans_status() === TRUE){
				$this->db->trans_commit();
				$info['state'] = 1;
				$info['message'] = '增加成功!';
				$info['url'] = '/ausers.html';
				$this->output
				    ->set_content_type('application/json', 'utf-8')
				    ->set_output(json_encode($info))
					->_display();
				    exit;
			}else{
				$this->db->trans_rollback();
				$info['state'] = 0;
				$info['message'] = '增加失败,刷新后重试!';
				$this->output
				    ->set_content_type('application/json', 'utf-8')
				    ->set_output(json_encode($info))
					->_display();
				    exit;
			}
		}
		$gs['group'] = $this->_getgroup('');
		$this->load->view('user/useradd', $gs);
	}
	//编辑
	public function edit(){
		if($this->input->post()){
			$uid = trim($this->input->post_get('d', TRUE));
			$lname = trim($this->input->post_get('lname', TRUE));
			$rname = trim($this->input->post_get('rname', TRUE));
			$pd = trim($this->input->post_get('pd', TRUE));
			$pd2 = trim($this->input->post_get('pd2', TRUE));
			$gids = trim($this->input->post_get('gids', TRUE));
			if(! intval($uid)){
				$info['state'] = 0;
				$info['message'] = '数据有误!';
				$this->output
				    ->set_content_type('application/json', 'utf-8')
				    ->set_output(json_encode($info))
					->_display();
				    exit;
			}
			if(! $gids){
				$info['state'] = 0;
				$info['message'] = '请选择所属权限!';
				$this->output
				    ->set_content_type('application/json', 'utf-8')
				    ->set_output(json_encode($info))
					->_display();
				    exit;
			}
			if(! is_password($lname, 2)){
				$info['state'] = 0;
				$info['message'] = '登录名称(由字母组成,至少3位)!';
				$this->output
				    ->set_content_type('application/json', 'utf-8')
				    ->set_output(json_encode($info))
					->_display();
				    exit;
			}
			if(! $rname){
				$info['state'] = 0;
				$info['message'] = '真实姓名不能为空!';
				$this->output
				    ->set_content_type('application/json', 'utf-8')
				    ->set_output(json_encode($info))
					->_display();
				    exit;
			}
			if($pd){
				if(! is_password($pd)){
					$info['state'] = 0;
					$info['message'] = '登录密码(包含字母和数字,至少6位)!';
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
			}
			$u = $this->ausers_model->get_ausers_byuid($uid);
			if(! IS_ROOT){
				if($u['admin_id'] != UID){
					$info['state'] = 0;
					$info['message'] = '非法操作!';
					$this->output
					    ->set_content_type('application/json', 'utf-8')
					    ->set_output(json_encode($info))
						->_display();
					    exit;
				}
				$gid = $this->auth_model->getrules('xm_auth_group_access','xm_auth_group', UID);
				if($gid[0]['id'] != $gids){
					$info['state'] = 0;
					$info['message'] = '服务器链接超时!';
					$this->output
					    ->set_content_type('application/json', 'utf-8')
					    ->set_output(json_encode($info))
						->_display();
					    exit;
				}
			}
			if($u['name'] != $lname && $this->ausers_model->get_ausers_byname($lname) > 0){
				$info['state'] = 0;
				$info['message'] = '登录名称已存在!';
				$this->output
				    ->set_content_type('application/json', 'utf-8')
				    ->set_output(json_encode($info))
					->_display();
				    exit;
			}
			if($pd){
				$data = array(
					'name' => $lname,
					'realname' => $rname,
					'pwd' => suny_encrypt($pd,'3ed1lio0'),
					'gid' => $gids,
					'uptime' => time()
				);
			}else{
				$data = array(
					'name' => $lname,
					'realname' => $rname,
					//'pwd' => suny_encrypt($pd,'3ed1lio0'),
					'gid' => $gids,
					'uptime' => time()
				);
			}
			$this->db->trans_begin();
			$this->ausers_model->modify_ausers($data, $uid);
			$this->ausers_model->get_ag_del($uid);
			$ag = array(
				'uid' => $uid,
				'group_id' => $gids
			);
			$this->ausers_model->get_ag_add($ag);
			if($this->db->trans_status() === TRUE){
				$this->db->trans_commit();
				$info['state'] = 1;
				$info['message'] = '编辑成功!';
				$info['url'] = '/ausers.html';
				$this->output
				    ->set_content_type('application/json', 'utf-8')
				    ->set_output(json_encode($info))
					->_display();
				    exit;
			}else{
				$this->db->trans_rollback();
				$info['state'] = 0;
				$info['message'] = '编辑失败,刷新后重试!';
				$this->output
				    ->set_content_type('application/json', 'utf-8')
				    ->set_output(json_encode($info))
					->_display();
				    exit;
			}
		}
		$id = $this->uri->segment(3);
		$datas = $this->ausers_model->get_ausers_byuid($id);
		if(! IS_ROOT){
			$admin_id = UID;
		}else{
			$admin_id = '';
		}
		$datas['group'] = $this->_getgroup($admin_id);
		//$datas['group'] = $this->_getgroup();
		$this->load->view('user/useredit', $datas);
	}
	//锁定
	public function lock(){
		if($this->input->post()){
			$ids = $this->input->post_get('ids', TRUE);
			$ids = explode(',', substr($ids, 0, -1));
			if(! $ids){
				$info['state'] = 0;
				$info['message'] = '数据有误!';
				$this->output
				    ->set_content_type('application/json', 'utf-8')
				    ->set_output(json_encode($info))
					->_display();
			    exit;
			}else{
				$data['type'] = 2;
				if(! IS_ROOT){
					$admin_id = UID;
				}else{
					$admin_id = '';
				}
				if($this->ausers_model->get_ausers_lock($data, $ids, $admin_id)){
					$info['state'] = 1;
					$info['message'] = '操作成功!';
					$this->output
					    ->set_content_type('application/json', 'utf-8')
					    ->set_output(json_encode($info))
						->_display();
				    exit;
				}else{
					$info['state'] = 0;
					$info['message'] = '操作失败,请刷新后重试!';
					$this->output
					    ->set_content_type('application/json', 'utf-8')
					    ->set_output(json_encode($info))
						->_display();
				    exit;
				}
			}
		}
	}
	//解锁
	public function unlock(){
		if($this->input->post()){
			$ids = $this->input->post_get('ids', TRUE);
			$ids = explode(',', substr($ids, 0, -1));
			if(! $ids){
				$info['state'] = 0;
				$info['message'] = '数据有误!';
				$this->output
				    ->set_content_type('application/json', 'utf-8')
				    ->set_output(json_encode($info))
					->_display();
			    exit;
			}else{
				$data['type'] = 1;
				if(! IS_ROOT){
					$admin_id = UID;
				}else{
					$admin_id = '';
				}
				if($this->ausers_model->get_ausers_lock($data, $ids, $admin_id)){
					$info['state'] = 1;
					$info['message'] = '操作成功!';
					$this->output
					    ->set_content_type('application/json', 'utf-8')
					    ->set_output(json_encode($info))
						->_display();
				    exit;
				}else{
					$info['state'] = 0;
					$info['message'] = '操作失败,请刷新后重试!';
					$this->output
					    ->set_content_type('application/json', 'utf-8')
					    ->set_output(json_encode($info))
						->_display();
				    exit;
				}
			}
		}
	}
	//删除
	public function del(){die;
		if($this->input->post()){
			$ids = $this->input->post_get('ids', TRUE);
			$ids = explode(',', substr($ids, 0, -1));
			if(! $ids){
				$info['state'] = 0;
				$info['message'] = '数据有误!';
				$this->output
				    ->set_content_type('application/json', 'utf-8')
				    ->set_output(json_encode($info))
					->_display();
			    exit;
			}else{
				$data['status'] = 2;
				if(! IS_ROOT){
					$admin_id = UID;
				}else{
					$admin_id = '';
				}
				if($this->ausers_model->get_ausers_lock($data, $ids, $admin_id)){
					$info['state'] = 1;
					$info['message'] = '操作成功!';
					$this->output
					    ->set_content_type('application/json', 'utf-8')
					    ->set_output(json_encode($info))
						->_display();
				    exit;
				}else{
					$info['state'] = 0;
					$info['message'] = '操作失败,请刷新后重试!';
					$this->output
					    ->set_content_type('application/json', 'utf-8')
					    ->set_output(json_encode($info))
						->_display();
				    exit;
				}
			}
		}
	}
	//获取权限组
	private function _getgroup($admin_id = '', $pid = 0){
		$group = $this->ausers_model->get_group($admin_id);
		if(! $admin_id){
			$groups = get_son_arr($group, '', $pid);
		}else{
			$group = $this->auth_model->getrules('xm_auth_group_access','xm_auth_group', UID, 1);
			$groups = get_son_arr($group, '', $pid);;
		}
		return $groups;
	}
}