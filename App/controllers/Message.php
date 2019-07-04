<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Message extends Baseaccounts {
	public function __construct() {
		parent::__construct();
		$this->load->library('pagination'); 
		$this->load->model(array('news/news_model'));
		//$this->load->helper('url');
		$this->data = array();
		$this->load->library('parser');
		$this->data['top'] = $this->parser->parse('index/topa', array(), TRUE);
		$this->data['foot'] = $this->parser->parse('index/foota', array(), TRUE);
		$this->data['menu'] = $this->get_menu();
	}
	
	//关于伽满优
	
	//公司简介
	public function index() {
		$this->data['title_img'] = 'gyjmy.png';
		$this->data['name'] = '公司简介';
		$this->data['url'] = '/message.html';
		$this->data['images'] = array(
			array('src' => '<br />'),
			array('src' => '/images/341781438677823650.jpg', 'title' => '公司简介')
		);
		$this->load->view('message/index', $this->data); 
	}
	
	//高层披露
	public function shareholder() {
		$this->data['title_img'] = 'gyjmy.png';
		$this->data['name'] = '高层披露';
		$this->data['url'] = '/message/shareholder.html';
		$this->data['images'] = array(
			array('src' => '/images/shareholder/gaocengpolou.png', 'title' => '高层披露'),
		);
		$this->load->view('message/index', $this->data); 
	}
	
	//公司历程
	public function process() {
		$this->data['title_img'] = 'gyjmy.png';
		$this->data['name'] = '公司历程';
		$this->data['url'] = '/message/process.html';
		$this->data['images'] = array(
			array('src' => '/images/process/process.png', 'title' => '公司历程')
		);
		$this->load->view('message/index', $this->data); 
	}
	
	//荣誉资质
	public function aptitude() {
		$this->data['title_img'] = 'gyjmy.png';
		$this->data['name'] = '荣誉资质';
		$this->data['url'] = '/message/aptitude.html';
		$this->data['images'] = array(
			array('src' => '/images/aptitude/aptitude.jpg', 'title' => '荣誉资质.jpg')
		);
		$this->load->view('message/index', $this->data); 
	}
	
	//放心出借
	public function safety() {
		$this->data['title_img'] = 'gyjmy.png';
		$this->data['name'] = '放心出借';
		$this->data['url'] = '/message/safety.html';
		$this->data['images'] = array(
			array('src' => '/images/safety/safety_1.jpg', 'title' => '放心出借.jpg'),
		);
		$this->load->view('message/index', $this->data); 
	}
	
	//诚聘英才
	public function joinus() {
		$this->data['title_img'] = 'gyjmy.png';
		$this->data['name'] = '诚聘英才';
		$this->data['url'] = '/message/joinus.html';
		$this->data['images'] = array(
			array('src' => '/images/joinus/joinus.jpg', 'title' => '诚聘英才.jpg'),
		);
		$this->load->view('message/index', $this->data); 
	}
	
	//合作伙伴
	// public function partner() {
		// $this->data['name'] = '合作伙伴';
		// $this->data['url'] = '/message/partner.html';
		// $this->data['images'] = array(
			// array('src' => '/images/partner/partner.jpg', 'title' => '合作伙伴.jpg'),
		// );
		// $this->load->view('message/index', $this->data); 
	// }
	
	//联系我们
	public function contact() {
		$this->data['title_img'] = 'gyjmy.png';
		$this->data['name'] = '联系我们';
		$this->data['url'] = '/message/contact.html';
		$this->data['images'] = array(
			array('src' => '/images/contact/contract.jpg', 'title' => '联系我们'),
		);
		$this->load->view('message/index', $this->data); 
	}
	

	//APP下载
	public function dowload() {
		$this->load->view('message/dowload', $this->data); 
	}
	
	//文章详情页面
	public function detail() {
		//$this->load->view('message/detail', $this->data);  
		//$this->
		//获取分类
		$this->data['name'] = '平台公告';
		$cate = $this->uri->segment(3);
		$this->data['title_img'] = 'gyjmy.png';
		foreach($this->data['menu'] as $k=>$v) {
			if($v['id'] == $cate) {
				$this->data['name'] = $v['name'];
				$this->data['url'] = $v['url'];
				break;
			}
			
		}
		
		//获取文章ID
		$id = $this->uri->segment(4);
		$this->data['row'] = $this->news_model->get_news_one($id);
		$this->load->view('message/detail', $this->data);  
	}
	
	//公用分页数据
	private function currency($cid, $index) {
		$current_page = $this->uri->segment(3) ? $this->uri->segment(3) : 1;
        if($current_page > 0){
            $current_page = $current_page - 1;
        }else if($current_page < 0){
            $current_page = 0;
        }
		$per_page = 10;
        $offset = $current_page;
        $config['base_url'] = base_url($index);
        $config['total_rows'] = $this->news_model->get_news_all_num($cid);
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
        $this->pagination->initialize($config); 
        $this->data['totals'] = $config['total_rows'];
        $this->data['page'] = $this->pagination->create_links();
        $this->data['p'] = $current_page;
        $news = $this->news_model->get_news_all($cid, $per_page, $offset * $per_page);
        $this->data['list'] = $news;
	} 
	
	//平台公告
	// public function plate_notice() {
		// $this->currency(7, 'message/plate_notice');
		// $this->data['name'] = '平台公告';
		// $this->data['url'] = '/message/plate_notice.html';

		// $this->data['cate'] = 12;
		// $this->load->view('message/list', $this->data);  
	// }

	//最新公告
	public function newest_notice() {
		$this->data['title_img'] = 'gyjmy.png';
		$this->currency(8, 'message/newest_notice');
		$this->data['name'] = '最新公告';
		$this->data['url'] = '/message/newest_notice.html';

		$this->data['cate'] = 6;
		$this->load->view('message/list', $this->data);   
	}
	
	//回款公告
	public function payments() {
		$this->data['title_img'] = 'gyjmy.png';
		$this->currency(9, 'message/payments');
		$this->data['name'] = '回款公告';
		$this->data['url'] = '/message/payments.html';

		$this->data['cate'] = 7;
		$this->load->view('message/list', $this->data);   
	}
	
	//媒体报道
	public function media() {
		$this->data['title_img'] = 'gyjmy.png';
		$this->currency(2, 'message/media');
		$this->data['name'] = '媒体报道';
		$this->data['url'] = '/message/media.html';

		$this->data['cate'] = 15;
		$this->load->view('message/list', $this->data);   
	}
	
	//导航
	// private function get_menu() {
		// return array(
			// array('id' => 1, 'name' => '公司简介', 'url' => '/message.html'),
			// array('id' => 2, 'name' => '公司资质', 'url' => '/message/aptitude.html'),
			// array('id' => 3, 'name' => '高层披露', 'url' => '/message/shareholder.html'),
			// array('id' => 4, 'name' => '组织架构', 'url' => '/message/structure.html'),
			// array('id' => 5, 'name' => '金融备案', 'url' => '/message/record.html'),
			// array('id' => 6, 'name' => '银行存管', 'url' => '/message/deposit.html'),
			// array('id' => 7, 'name' => '三级等保', 'url' => '/message/insurance.html'),
			// array('id' => 8, 'name' => '电子签名', 'url' => '/message/esignature.html'),
			// array('id' => 9, 'name' => '业务流程', 'url' => '/message/flow.html'),
			// array('id' => 10, 'name' => '公司历程', 'url' => '/message/process.html'),
			// array('id' => 11, 'name' => '放心投资', 'url' => '/message/safety.html'),
			// array('id' => 12, 'name' => '平台公告', 'url' => '/message/plate_notice.html'),
			// array('id' => 13, 'name' => '最新公告', 'url' => '/message/newest_notice.html'),
			// array('id' => 14, 'name' => '回款公告', 'url' => '/message/payments.html'),
			// array('id' => 15, 'name' => '媒体报道', 'url' => '/message/media.html'),
			// array('id' => 16, 'name' => '联系我们', 'url' => '/message/contact.html'),
			// array('id' => 17, 'name' => 'APP下载', 'url' => '/message/dowload.html')
		// );
	// }
	private function get_menu() {
		return array(
			array('id' => 1, 'name' => '公司简介', 'url' => '/message.html'),
			array('id' => 2, 'name' => '高层披露', 'url' => '/message/shareholder.html'),
			array('id' => 3, 'name' => '公司历程', 'url' => '/message/process.html'),
			//array('id' => 4, 'name' => '荣誉资质', 'url' => '/message/aptitude.html'),
			array('id' => 5, 'name' => '放心出借', 'url' => '/message/safety.html'),
			array('id' => 6, 'name' => '最新公告', 'url' => '/message/newest_notice.html'),
			array('id' => 7, 'name' => '回款公告', 'url' => '/message/payments.html'),
			array('id' => 8, 'name' => '诚聘英才', 'url' => '/message/joinus.html'),
			//array('id' => 14, 'name' => '合作伙伴', 'url' => '/message/partner.html'),
			array('id' => 9, 'name' => '联系我们', 'url' => '/message/contact.html')
			//array('id' => 17, 'name' => 'APP下载', 'url' => '/message/dowload.html')
		);
	}
	
	/**帮助中心 */
	
	//新手指引
	public function novice() {
		$this->data['title_img'] = 'help_left.png';
		$this->data['menu'] = $this->get_help_menu();
		$this->data['name'] = '新手指引';
		$this->data['url'] = '/message/novice.html';
		$this->data['con'] = $this->news_model->get_one_bycid(3);
		$this->load->view('message/help', $this->data); 
	}
	
	//我要出借
	public function invest() {
		$this->data['title_img'] = 'help_left.png';
		$this->data['menu'] = $this->get_help_menu();
		$this->data['name'] = '我要出借';
		$this->data['url'] = '/message/invest.html';
		$this->data['con'] = $this->news_model->get_one_bycid(4);
		$this->load->view('message/help', $this->data); 
	}
	
	//名词解释
	public function noun() {
		$this->data['title_img'] = 'help_left.png';
		$this->data['menu'] = $this->get_help_menu();
		$this->data['name'] = '名词解释';
		$this->data['url'] = '/message/noun.html';
		$this->data['con'] = $this->news_model->get_one_bycid(5);
		$this->load->view('message/help', $this->data); 
	}
	
	//常见问题
	// public function question() {
		// $this->data['menu'] = $this->get_help_menu();
		// $this->data['name'] = '常见问题';
		// $this->data['url'] = '/message/question.html';
		// $this->data['con'] = $this->news_model->get_one_bycid(6);
		// $this->load->view('message/help', $this->data); 
	// }
	
	//帮助中心导航
	private function get_help_menu() {
		return array(
			array('name' => '新手指引', 'url' => '/message/novice.html'),
			array('name' => '我要出借', 'url' => '/message/invest.html'),
			array('name' => '名词解释', 'url' => '/message/noun.html'),
			//array('name' => '常见问题', 'url' => '/message/question.html'),
		);
	}
	
	/**信息披露 */
	
	//组织信息
	public function structure() {
		$this->data['title_img'] = 'info.png';
		$this->data['menu'] = $this->get_disclosure_menu();
		$this->data['name'] = '组织信息';
		$this->data['url'] = '/message/structure.html';
		$this->data['images'] = array(
			array('src' => '/images/structure/structure.png', 'title' => '组织信息'),
		);
		$this->load->view('message/index', $this->data); 
	}
	
	//金融备案
	// public function record() {
		// $this->data['title_img'] = 'info.png';
		// $this->data['menu'] = $this->get_disclosure_menu();
		// $this->data['name'] = '金融备案';
		// $this->data['url'] = '/message/record.html';
		// $this->data['images'] = array(
			// array('src' => '/images/record/record.png', 'title' => '金融备案'),
		// );
		// $this->load->view('message/index', $this->data); 
	// }
	
	//银行存管
	public function deposit() {
		$this->data['title_img'] = 'info.png';
		$this->data['menu'] = $this->get_disclosure_menu();
		$this->data['name'] = '银行存管';
		$this->data['url'] = '/message/deposit.html';
		$this->data['images'] = array(
			array('src' => '/images/deposit/deposit.jpg', 'title' => '银行存管'),
		);
		$this->load->view('message/index', $this->data); 
	}
	
	//三级等保
	// public function insurance() {
		// $this->data['title_img'] = 'info.png';
		// $this->data['menu'] = $this->get_disclosure_menu();
		// $this->data['name'] = '三级等保';
		// $this->data['url'] = '/message/insurance.html';
		// $this->data['images'] = array(
			// array('src' => '/images/insurance/1.png', 'title' => '三级等保'),
		// );
		// $this->load->view('message/index', $this->data); 
	// }
	
	//电子签章
	public function esignature() {
		$this->data['title_img'] = 'info.png';
		$this->data['menu'] = $this->get_disclosure_menu();
		$this->data['name'] = '电子签章';
		$this->data['url'] = '/message/esignature.html';
		$this->data['images'] = array(
			array('src' => '/images/esignature/1.png', 'title' => '电子签章'),
		);
		$this->load->view('message/index', $this->data); 
	}
	
	//业务流程
	public function flow() {
		$this->data['title_img'] = 'info.png';
		$this->data['menu'] = $this->get_disclosure_menu();
		$this->data['name'] = '业务流程';
		$this->data['url'] = '/message/flow.html';
		$this->data['images'] = array(
			array('src' => '/images/flow/flow.jpg', 'title' => '业务流程'),
		);
		$this->load->view('message/index', $this->data); 
	}
	
	//风控体系
	public function risk() {
		$this->data['title_img'] = 'info.png';
		$this->data['menu'] = $this->get_disclosure_menu();
		$this->data['name'] = '风控体系';
		$this->data['url'] = '/message/risk.html';
		$this->data['images'] = array(
			array('src' => '/images/risk/risk.jpg', 'title' => '风控体系'),
		);
		$this->load->view('message/index', $this->data); 
	}
	
	//审计报告
	public function audit() {
		$this->data['title_img'] = 'info.png';
		$this->data['menu'] = $this->get_disclosure_menu();
		$this->data['name'] = '审计报告';
		$this->data['url'] = '/message/audit.html';
		$this->data['images'] = array(
			array('src' => '/images/audit/audit.jpg', 'title' => '审计报告'),
		);
		$this->load->view('message/index', $this->data); 
	}
	
	//运营报告
	public function operation() {
		$this->data['title_img'] = 'info.png';
		$this->data['menu'] = $this->get_disclosure_menu();
		$this->data['name'] = '运营报告';
		$this->data['url'] = '/message/operation.html';
		
		$new = $this->news_model->get_news_operation();
		$news = array();
		$i = 0;
		foreach($new as $k=>$v) {
			if($i == 0) {
				$this->data['year'] = $v['year'];
			}
			$news[$v['year']][$i]['id'] = $v['id'];
			$news[$v['year']][$i]['title'] = $v['title'];
			$i++;
		}
		$this->data['news'] = $news;
		
		$this->load->model('motion/motion_model');
		$timstamp = time();
		$year = date("Y", $timstamp);
		
		$month = intval(date("m", $timstamp)) - 1;
		if(!$month) {
			$year = intval($year) - 1;
			$month = 12;
		}
		$this->data['year'] = $year;
		$this->data['month'] = $month;
		$this->data['motion'] = $this->motion_model->get_motion_bydate(strtotime($year . '-' . $month));
		$this->load->view('message/operation', $this->data); 
	}
	
	//运营报告详情
	public function operation_detail() {
		$id = $this->uri->segment(3);
		$news = $this->news_model->get_news_one($id);
		preg_match_all("/(href|src)=([\"|']?)([^\"'>]+.(jpg|JPG|jpeg|JPEG|gif|GIF|png|PNG))/i", $news['content'],$out,PREG_PATTERN_ORDER);
		$news['content'] = $out[3];
		$this->data['news'] = $news;
		$this->load->view('message/operation_detail', $this->data); 
	}
	
	//法人承诺书
	public function legal() {
		$this->currency(11, 'message/legal');
		$this->data['title_img'] = 'info.png';
		$this->data['menu'] = $this->get_disclosure_menu();
		$this->data['name'] = '法人承诺书';
		$this->data['url'] = '/message/legal.html';
		$this->data['html'] = '<div class="frqrh_v1">
							<strong> 《网络借贷信息中介机构业务活动信息披露指引》(银监办发[2017]113号)第十七条要求：网络借贷信息中介机构的董事、监事、高级管理人员应
								当忠实、勤勉、尽职，保证披露的信息真实、准确、完整、及时。网络借贷信息中介机构信息披露专栏内容均应当有网络借贷信息中介机构法定代
								表人的签字确认。
							</strong>
						</div>';
		$this->data['images'] = array(
			array('src' => '/images/legal/legal.jpg', 'title' => '法人承诺书'),
		);
		$this->load->view('message/index', $this->data); 
	}
	
	//法人承诺书详情
	// public function legal_detail() {
		// $id = $this->uri->segment(3);
		// $detail = $this->news_model->get_news_one($id);
		// $this->load->view('message/legal_detail', $detail); 
	// }
	
	//信息披露导航
	private function get_disclosure_menu() {
		return array(
			array('name' => '组织信息', 'url' => '/message/structure.html'),
			//array('name' => '金融备案', 'url' => '/message/record.html'),
			array('name' => '银行存管', 'url' => '/message/deposit.html'),
			//array('name' => '三级等保', 'url' => '/message/insurance.html'),
			array('name' => '电子签章', 'url' => '/message/esignature.html'),
			array('name' => '业务流程', 'url' => '/message/flow.html'),
			array('name' => '风控体系', 'url' => '/message/risk.html'),
			array('name' => '审计报告', 'url' => '/message/audit.html'),
			array('name' => '运营报告', 'url' => '/message/operation.html'),
			array('name' => '法人承诺书', 'url' => '/message/legal.html')
		);
	}
}