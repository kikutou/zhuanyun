<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin','admin',0);

class admin_shipline extends admin {

	private $db; public $username;
	public function __construct() {
		parent::__construct();
		//if (!module_exists(ROUTE_M)) showmessage(L('module_not_exists'));
		$this->username = param::get_cookie('admin_username');
		$this->db = pc_base::load_model('shipline_model');
	}
	
	public function init() {
		//线路列表
		$sql = '';
		
		$defaultcurrency = $this->getdefaultcurrency();
		$line = $this->getline();


		$sql = '`siteid`=\''.$this->get_siteid().'\'';
		
		$page = max(intval($_GET['page']), 1);
		$data = $this->db->listinfo($sql, '`aid` DESC', $page);
		$big_menu = array('javascript:window.location.href=\'?m=shipline&c=admin_shipline&a=add&checkhash='.$_SESSION['checkhash'].'\'', L('shipline_add'));
		include $this->admin_tpl('shipline_list');
	}
	
	/**
	 * 添加线路
	 */
	public function add() {
		$show_validator = 1;
		if(isset($_POST['dosubmit'])) {
			$_POST['shipline'] = $this->check($_POST['shipline']);

			if(!isset($_POST['shipline']['support']))
				$_POST['shipline']['support'] = 0;

			if($this->db->insert($_POST['shipline'])) showmessage(L('shiplinement_successful_added'), HTTP_REFERER);
		} else {
			$big_menu = array('javascript:window.location.href=\'?m=shipline&c=admin_shipline&a=init&checkhash='.$_SESSION['checkhash'].'\'', L('shipline_manage'));

			//获取站点模板信息
			pc_base::load_app_func('global', 'admin');
			pc_base::load_sys_class('form', '', 0);

			$currency = $this->getcurrency();
			
			$weight = $this->getweight();

			$line = $this->getline();

			$lineaddoption = $this->getlineaddoption();


			include $this->admin_tpl('shipline_add');
		}
	}
	
	/**
	 * 修改线路
	 */
	public function edit() {
		$show_validator = 1;
		pc_base::load_sys_class('form', '', 0);
		$_GET['aid'] = intval($_GET['aid']);
		if(!$_GET['aid']) showmessage(L('illegal_operation'));
		if(isset($_POST['dosubmit'])) {
			$_POST['shipline'] = $this->check($_POST['shipline'], 'edit');
			if(!isset($_POST['shipline']['support']))
				$_POST['shipline']['support'] = 0;

			if($this->db->update($_POST['shipline'], array('aid' => $_GET['aid']))) showmessage(L('shiplined_a'), HTTP_REFERER);
		} else {
			$where = array('aid' => $_GET['aid']);
			$an_info = $this->db->get_one($where);

			$currency = $this->getcurrency();
			
			$weight = $this->getweight();

			$line = $this->getline();

			$lineaddoption = $this->getlineaddoption();

			include $this->admin_tpl('shipline_edit');
		}
	}
	
	/**
	 * ajax检测线路标题是否重复
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
	 * 批量修改线路状态 使其成为审核、未审核状态
	 */
	public function public_approval($aid = 0) {
		if((!isset($_POST['aid']) || empty($_POST['aid'])) && !$aid) {
			showmessage(L('illegal_operation'));
		} else {
			if(is_array($_POST['aid']) && !$aid) {
				array_map(array($this, 'public_approval'), $_POST['aid']);
				showmessage(L('shipline_passed'), HTTP_REFERER);
			} elseif($aid) {
				$aid = intval($aid);
				
				$this->db->update(array('default' => $_GET['default']), array('aid' => $aid));
				return true;
			}
		}
	}
	
	/**
	 * 批量删除线路
	 */
	public function delete($aid = 0) {
		if((!isset($_POST['aid']) || empty($_POST['aid'])) && !$aid) {
			showmessage(L('illegal_operation'));
		} else {
			if(is_array($_POST['aid']) && !$aid) {
				array_map(array($this, 'delete'), $_POST['aid']);
				showmessage(L('shipline_deleted'), HTTP_REFERER);
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
				showmessage(L('shipline_exist'), HTTP_REFERER);
			}
			$data['siteid'] = $this->get_siteid();
			$data['addtime'] = SYS_TIME;
			$data['username'] = $this->username;
			
		} else {
			if ($r['aid'] && ($r['aid']!=$_GET['aid'])) {
				showmessage(L('shipline_exist'), HTTP_REFERER);
			}
		}
		return $data;
	}

	/**获取所有货币**/
	private function getcurrency(){
		$cdb = pc_base::load_model('currency_model');
		$sql = '`siteid`=\''.$this->get_siteid().'\'';
		$datas = $cdb->select($sql,'*',10000);
		return $datas;
	}

	/**获取所有重量单位**/
	private function getweight(){
		$cdb = pc_base::load_model('enum_model');
		$sql = '`siteid`=\''.$this->get_siteid().'\' AND groupid=2 ';
		$datas = $cdb->select($sql,'*',10000,'listorder ASC');
		return $datas;
	}

	/**获取所有线路**/
	private function getline(){
		$cdb = pc_base::load_model('linkage_model');
		$sql = ' parentid=0 AND keyid=0 ';
		$datas = $cdb->select($sql,'*',10000,'listorder ASC');
		return $datas;
	}

	/**获取线路附加项**/
	private function getlineaddoption(){
		$cdb = pc_base::load_model('enum_model');
		$sql = '`siteid`=\''.$this->get_siteid().'\' AND groupid=3 ';
		$datas = $cdb->select($sql,'*',10000,'listorder ASC');
		return $datas;
	}

	/**获取所有默认货币单位**/
	private function getdefaultcurrency(){
		$cdb = pc_base::load_model('currency_model');
		$sql = '`siteid`=\''.$this->get_siteid().'\' AND `isdefault`=1';
		$datas = $cdb->get_one($sql);

		
		return $datas;
	}

}
?>