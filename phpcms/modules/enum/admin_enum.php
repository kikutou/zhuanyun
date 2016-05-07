<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin','admin',0);

class admin_enum extends admin {

	private $db; public $username;
	public function __construct() {
		parent::__construct();
		//if (!module_exists(ROUTE_M)) showmessage(L('module_not_exists'));
		$this->username = param::get_cookie('admin_username');
		$this->db = pc_base::load_model('enum_model');
		
		//$this->db->query("UPDATE t_enum set country='中国' where groupid=48;");

	}
	
	public function init() {
		//字典列表
		$sql = '';

		$sql = ' groupid=0 AND `siteid`=\''.$this->get_siteid().'\'';
		
		$page = max(intval($_GET['page']), 1);
		$data = $this->db->listinfo($sql, '`aid` DESC', $page);
		$big_menu = array('javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=enum&c=admin_enum&a=add\', title:\''.L('enum_add').'\', width:\'500\', height:\'350\', lock:true}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'add\'}).close()});void(0);', L('enum_add'));
		include $this->admin_tpl('enum_list');
	}

	public function listitem() {
		//项列表
		$sql = '';
		$gid = intval($_GET['gid']);
		
		$sql = ' groupid='.$gid.' AND `siteid`=\''.$this->get_siteid().'\'';
		
		$an_info = $this->db->get_one("aid='$gid'");

		$page = max(intval($_GET['page']), 1);
		$data = $this->db->listinfo($sql, 'listorder  asc', $page);
		$big_menu = array('javascript:window.top.art.dialog({id:\'additem\',iframe:\'?m=enum&c=admin_enum&a=additem&gid='.$gid.'\', title:\''.L('enum_additem').'--'.$an_info['title'].'\', width:\'500\', height:\'350\', lock:true}, function(){var d = window.top.art.dialog({id:\'additem\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'additem\'}).close()});void(0);', L('enum_additem'));
		include $this->admin_tpl('enum_listitem');
	}
	
	/**
	 * 添加字典
	 */
	public function add() {
		$show_validator = 1;
		if(isset($_POST['dosubmit'])) {
			$_POST['enum'] = $this->check($_POST['enum']);
			if($this->db->insert($_POST['enum'])) showmessage(L('enumment_successful_added'), HTTP_REFERER, '', 'add');
		} else {
			//获取站点模板信息
			pc_base::load_app_func('global', 'admin');
			
			include $this->admin_tpl('enum_add');
		}
	}
	
	/**
	 * 修改字典
	 */
	public function edit() {
		$show_validator = 1;
		$_GET['aid'] = intval($_GET['aid']);
		if(!$_GET['aid']) showmessage(L('illegal_operation'));
		if(isset($_POST['dosubmit'])) {
			$_POST['enum'] = $this->check($_POST['enum'], 'edit');
			if($this->db->update($_POST['enum'], array('aid' => $_GET['aid']))) showmessage(L('enumd_a'), HTTP_REFERER, '', 'edit');
		} else {
			$where = array('aid' => $_GET['aid']);
			$an_info = $this->db->get_one($where);
			include $this->admin_tpl('enum_edit');
		}
	}



	/**
	 * 添加项
	 */
	public function additem() {
		$show_validator = 1;
		$gid = intval($_GET['gid']);

		if(isset($_POST['dosubmit'])) {
			$_POST['enum'] = $this->checkitem($_POST['enum']);
			if($this->db->insert($_POST['enum'])) showmessage(L('enumment_successful_added'), HTTP_REFERER, '', 'additem');
		} else {
			//获取站点模板信息
			pc_base::load_app_func('global', 'admin');
			
			include $this->admin_tpl('enum_additem');
		}
	}
	
	/**
	 * 修改项
	 */
	public function edititem() {
		$show_validator = 1;
		$_GET['aid'] = intval($_GET['aid']);
		if(!$_GET['aid']) showmessage(L('illegal_operation'));
		if(isset($_POST['dosubmit'])) {
			$_POST['enum'] = $this->checkitem($_POST['enum'], 'edititem');
			if($this->db->update($_POST['enum'], array('aid' => $_GET['aid']))) showmessage(L('enumd_a'), HTTP_REFERER, '', 'edititem');
		} else {
			$where = array('aid' => $_GET['aid']);
			$an_info = $this->db->get_one($where);
			include $this->admin_tpl('enum_edititem');
		}
	}
	
	/**
	 * ajax检测字典标题是否重复
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
	 * ajax检测项标题是否重复
	 */
	public function public_check_itemtitle() {exit('1');
		if (!$_GET['title']) exit(0);
		if (CHARSET=='gbk') {
			$_GET['title'] = iconv('UTF-8', 'GBK', $_GET['title']);
		}
		$title = $_GET['title'];
		$gid = intval($_GET['gid']);
		if ($_GET['aid']) {
			$r = $this->db->get_one(array('aid' => intval($_GET['aid']),'groupid'=>$gid));
			if ($r['title'] == $title) {
				exit('1');
			}
		} 
		$r = $this->db->get_one(array('siteid' => $this->get_siteid(), 'title' => $title,'groupid'=>$gid), 'aid');
		if($r['aid']) {
			exit('0');
		} else {
			exit('1');
		}
	}
	
	/**
	 * 批量修改字典状态 使其成为审核、未审核状态
	 */
	public function public_approval($aid = 0) {
		if((!isset($_POST['aid']) || empty($_POST['aid'])) && !$aid) {
			showmessage(L('illegal_operation'));
		} else {
			if(is_array($_POST['aid']) && !$aid) {
				array_map(array($this, 'public_approval'), $_POST['aid']);
				showmessage(L('enum_passed'), HTTP_REFERER);
			} elseif($aid) {
				$aid = intval($aid);
				
				$this->db->update(array('default' => $_GET['default']), array('aid' => $aid));
				return true;
			}
		}
	}
	
	/**
	 * 批量删除字典
	 */
	public function delete($aid = 0) {
		if((!isset($_POST['aid']) || empty($_POST['aid'])) && !$aid) {
			showmessage(L('illegal_operation'));
		} else {
			if(is_array($_POST['aid']) && !$aid) {
				array_map(array($this, 'delete'), $_POST['aid']);
				showmessage(L('enum_deleted'), HTTP_REFERER);
			} elseif($aid) {
				$aid = intval($aid);

				$this->db->delete(array('groupid' => $aid));

				$this->db->delete(array('aid' => $aid));
			}
		}
	}

	/**
	 * 批量删除项
	 */
	public function deleteitem($aid = 0) {
		if((!isset($_POST['aid']) || empty($_POST['aid'])) && !$aid) {
			showmessage(L('illegal_operation'));
		} else {
			if(is_array($_POST['aid']) && !$aid) {
				array_map(array($this, 'delete'), $_POST['aid']);
				showmessage(L('enumitem_deleted'), HTTP_REFERER);
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
		$r = $this->db->get_one(array('title' => $data['title'],'groupid'=>0));
		
		if ($a=='add' ) {
			if (is_array($r) && !empty($r)) {
				showmessage(L('enum_exist'), HTTP_REFERER);
			}
			$data['siteid'] = $this->get_siteid();
			$data['addtime'] = SYS_TIME;
			$data['username'] = $this->username;
			
		} else {
			if ($r['aid'] && ($r['aid']!=$_GET['aid'])) {
				showmessage(L('enum_exist'), HTTP_REFERER);
			}
		}
		return $data;
	}

	private function checkitem($data = array(), $a = 'additem') {
		if($data['title']=='') showmessage(L('title_cannot_empty'));
		$r = $this->db->get_one(array('title' => $data['title'],'groupid'=>$data['groupid']));
		
		if ( $a=='additem') {
			if (is_array($r) && !empty($r)) {
				//showmessage(L('enum_exist'), HTTP_REFERER);
			}
			$data['siteid'] = $this->get_siteid();
			$data['addtime'] = SYS_TIME;
			$data['username'] = $this->username;
			
		} else {
			if ($r['aid'] && ($r['aid']!=$_GET['aid'])) {
				//showmessage(L('enum_exist'), HTTP_REFERER);
			}
		}
		return $data;
	}



}
?>