<?php 
defined('IN_PHPCMS') or exit('No permission resources.');
$session_storage = 'session_'.pc_base::load_config('system','session_storage');
pc_base::load_sys_class($session_storage);
pc_base::load_app_class('foreground','member');
pc_base::load_sys_class('format', '', 0);
pc_base::load_sys_class('form', '', 0);

class index extends foreground {

	private $hid;

	function __construct() {
		$this->db = pc_base::load_model('ownaddress_model');
		$this->_username = param::get_cookie('_username');
		$this->_userid = intval(param::get_cookie('_userid'));
		$this->siteid =  isset($_REQUEST['siteid']) ? intval($_REQUEST['siteid']) : 1;
		$this->hid =  isset($_GET['hid']) && trim($_GET['hid']) ? intval($_GET['hid']) : 0;
	}
	
	public function init() {
		
		$sql = '';

		$sql = '`siteid`=\''.$this->siteid.'\' and userid=\''.$this->_userid.'\' and storageid=\''.$this->hid.'\'   ';
		
		$page = max(intval($_GET['page']), 1);
		$datas = $this->db->listinfo($sql, '`aid` DESC', $page);
		
		include template('ownaddress', 'ownaddress_list');
	}

	public function add() {
	
		$show_validator = 1;
		if(isset($_POST['dosubmit'])) {
			$_POST['ownaddress'] = $this->check(safe_array_string($_POST['ownaddress']));
			$packages = array();
			$packages['siteid'] = $this->siteid;
			$packages['addtime'] = SYS_TIME;
			$packages['username'] = $this->_username;
			$packages['userid'] = $this->_userid;
			$packages['storageid'] = $this->hid;
			$packages['status'] = 2;//包裹已入库状态

			$packages['expressid'] = $_POST['ownaddress']['expressid'];
			$packages['expressno'] = $_POST['ownaddress']['expressno'];
			$packages['goodsname'] = $_POST['ownaddress']['goodsname'];
			$packages['amount'] = $_POST['ownaddress']['amount'];
			$packages['weight'] = $_POST['ownaddress']['weight'];
			$packages['price'] = $_POST['ownaddress']['price'];
			$packages['remark'] = $_POST['ownaddress']['remark'];

			$pdb = pc_base::load_model('package_model');
			//$pdb->insert($packages);//添加到包裹里面去

			if($this->db->insert($_POST['ownaddress'])){
	
				showmessage(L('ownaddressment_successful_added'), HTTP_REFERER);
			}
		} else {
			
			include template('ownaddress', 'ownaddress_add');
		}

		
	}


	public function edit() {
		
		$show_validator = 1;
		$_GET['aid'] = intval($_GET['aid']);
		if(!$_GET['aid']) showmessage(L('illegal_operation'));
		if(isset($_POST['dosubmit'])) {
			
			$_POST['ownaddress'] = $this->check(safe_array_string($_POST['ownaddress']), 'edit');

			if($this->db->update($_POST['ownaddress'], array('aid' => $_GET['aid']))) showmessage(L('ownaddressd_a'), HTTP_REFERER);
		} else {
			$where = array('aid' => $_GET['aid']);
			$an_info = $this->db->get_one($where);

			include template('ownaddress', 'ownaddress_edit');
		}
		
	}


	/**
	 * 批量删除私人地址簿
	 */
	public function delete($aid = 0) {

		if((!isset($_POST['aid']) || empty($_POST['aid'])) && !$aid) {
			showmessage(L('illegal_operation'));
		} else {
			if(is_array($_POST['aid']) && !$aid) {
				array_map(array($this, 'delete'), $_POST['aid']);
				showmessage(L('operation_success'), HTTP_REFERER);
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
		if($data['firstname']=='' || $data['lastname']=='') showmessage(L('firstname').'/'.L('lastname').L('cannot_empty'));
		if($data['address']=='' ) showmessage(L('address').L('cannot_empty'));
		if($data['expressno']=='' ) showmessage(L('expressno').L('cannot_empty'));

		$r = $this->db->get_one(array('expressno' => $data['expressno']));
		
		if ($a=='add') {
			if (is_array($r) && !empty($r)) {
				showmessage(L('ownaddress_exist'), HTTP_REFERER);
			}
			$data['siteid'] = $this->siteid;
			$data['addtime'] = SYS_TIME;
			$data['username'] = $this->_username;
			$data['userid'] = $this->_userid;
			$data['storageid'] = $this->hid;
			
		} else {
			if ($r['aid'] && ($r['aid']!=$_GET['aid'])) {
				showmessage(L('ownaddress_exist'), HTTP_REFERER);
			}
		}
		return $data;
	}
	

	/**获取私人地址列表**/
	private function getownaddress(){
		$cdb = pc_base::load_model('enum_model');
		$sql = '`siteid`=\''.$this->siteid.'\' AND groupid=44 ';
		$datas = $cdb->select($sql,'*',10000,'listorder ASC');
		return $datas;
	}
	
	/**获取所有货运公司**/
	private function getallship(){
		$cdb = pc_base::load_model('enum_model');
		$sql = '`siteid`=\''.$this->siteid.'\' AND groupid=1 ';
		$datas = $cdb->select($sql,'*',10000,'listorder ASC');
		return $datas;
	}

}
?>