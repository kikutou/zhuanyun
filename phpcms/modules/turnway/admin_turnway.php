<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin','admin',0);

class admin_turnway extends admin {

	private $db; public $username;
	public function __construct() {
		parent::__construct();
		//if (!module_exists(ROUTE_M)) showmessage(L('module_not_exists'));
		$this->username = param::get_cookie('admin_username');
		$this->db = pc_base::load_model('turnway_model');
	}
	
	public function init() {
		//转运方式列表
		$sql = '';

		$sql = '`siteid`=\''.$this->get_siteid().'\'';
		
	

		$page = max(intval($_GET['page']), 1);
		$data = $this->db->listinfo($sql, '`aid` DESC', $page);
		$big_menu = array('javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=turnway&c=admin_turnway&a=add\', title:\''.L('turnway_add').'\', width:\'600\', height:\'350\', lock:true}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'add\'}).close()});void(0);', L('turnway_add'));
		include $this->admin_tpl('turnway_list');
	}
	
	/**
	 * 添加转运方式
	 */
	public function add() {
		$show_validator = 1;
		if(isset($_POST['dosubmit'])) {
			$_POST['turnway'] = $this->check($_POST['turnway']);
			if($this->db->insert($_POST['turnway'])) showmessage(L('turnwayment_successful_added'), HTTP_REFERER, '', 'add');
		} else {
			//获取站点模板信息
			pc_base::load_app_func('global', 'admin');
			$sendaddress = $this->getsendlists();
			

			include $this->admin_tpl('turnway_add');
		}
	}
	
	/**
	 * 修改转运方式
	 */
	public function edit() {
		$show_validator = 1;
		$_GET['aid'] = intval($_GET['aid']);
		if(!$_GET['aid']) showmessage(L('illegal_operation'));
		if(isset($_POST['dosubmit'])) {
			$_POST['turnway'] = $this->check($_POST['turnway'], 'edit');
			if($this->db->update($_POST['turnway'], array('aid' => $_GET['aid']))) showmessage(L('turnwayd_a'), HTTP_REFERER, '', 'edit');
		} else {
			$sendaddress = $this->getsendlists();
			$where = array('aid' => $_GET['aid']);
			$an_info = $this->db->get_one($where);
			
			

			include $this->admin_tpl('turnway_edit');
		}
	}
	
	/**
	 * ajax检测转运方式标题是否重复
	 */
	public function public_check_title() {
		if (!$_GET['title']) exit(0);
		if (CHARSET=='gbk') {
			$_GET['title'] = iconv('UTF-8', 'GBK', $_GET['title']);
		}
		$title = $_GET['title'];
		if ($_GET['aid']) {
			$r = $this->db->get_one(array('aid' => $_GET['aid']));
			if ($r['title'] == $title) {
				exit('1');
			}
		} 
		$r = $this->db->get_one(array('siteid' => $this->get_siteid(), 'title' => $title), 'aid');
		if($r['aid']) {
			exit('0');
		} else {
			exit('1');
		}
	}
	
	/**
	 * 批量修改转运方式状态 使其成为审核、未审核状态
	 */
	public function public_approval($aid = 0) {
		if((!isset($_POST['aid']) || empty($_POST['aid'])) && !$aid) {
			showmessage(L('illegal_operation'));
		} else {
			if(is_array($_POST['aid']) && !$aid) {
				array_map(array($this, 'public_approval'), $_POST['aid']);
				showmessage(L('turnway_passed'), HTTP_REFERER);
			} elseif($aid) {
				$aid = intval($aid);
				
				$this->db->update(array('isdefault' => $_GET['isdefault']), array('aid' => $aid));
				return true;
			}
		}
	}
	
	/**
	 * 批量删除转运方式
	 */
	public function delete($aid = 0) {
		if((!isset($_POST['aid']) || empty($_POST['aid'])) && !$aid) {
			showmessage(L('illegal_operation'));
		} else {
			if(is_array($_POST['aid']) && !$aid) {
				array_map(array($this, 'delete'), $_POST['aid']);
				showmessage(L('turnway_deleted'), HTTP_REFERER);
			} elseif($aid) {
				$aid = intval($aid);
				$this->db->delete(array('aid' => $aid));
			}
		}
	}
	
	/**
	 * 验证表单数据
	 * @param  		array 		$data 表单数组数据
	 * @param  		string 		$a 当表单为添加数据时，自动补上缺失的数据。
	 * @return 		array 		验证后的数据
	 */
	private function check($data = array(), $a = 'add') {
		if($data['title']=='') showmessage(L('title_cannot_empty'));
		
		$r = $this->db->get_one(array('title' => $data['title']));
		
		if ($a=='add') {
			if (is_array($r) && !empty($r)) {
				showmessage(L('turnway_exist'), HTTP_REFERER);
			}
			$data['siteid'] = $this->get_siteid();
			$data['addtime'] = SYS_TIME;
			$data['username'] = $this->username;
			
		} else {
			if ($r['aid'] && ($r['aid']!=$_GET['aid'])) {
				showmessage(L('turnway_exist'), HTTP_REFERER);
			}
		}
		return $data;
	}

	
	/** 获取发货地址 **/
	private function getsendlists(){
		return $this->getline();
	}

	/** 获取收货地址 **/
	public function gettakelists(){

		$areaid = isset($_GET['areaid']) ? intval($_GET['areaid']) : 0;
		$datas = array();
		$datas = $this->getline($areaid);
		print_r(json_encode($datas));
		exit;
	}

	/**获取国家地区**/
	private function getline($id=0){
		$cdb = pc_base::load_model('linkage_model');
		$sql = ' parentid=0 AND keyid=0 ';
		if($id>0)
		{
			$sql.=" and linkageid not in($id)";
		}
		$datas = $cdb->select($sql,'*',10000,'listorder ASC');
		return $datas;
	}

	/**获取单条国家地区**/
	private function getoneline($areaid){
		$cdb = pc_base::load_model('linkage_model');
		$sql = ' parentid=0 AND keyid=0 and linkageid=\''.$areaid.'\' ';
		$datas = $cdb->get_one($sql);
		return $datas;
	}


	/**获取折扣**/
	private function getdiscounttype(){
		$cdb = pc_base::load_model('enum_model');
		$sql = '`siteid`=\''.$this->get_siteid().'\' AND groupid=55 ';
		$datas = $cdb->select($sql,'*',10000,'listorder ASC');
		return $datas;
	}


}
?>