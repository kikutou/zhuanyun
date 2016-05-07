<?php 
defined('IN_PHPCMS') or exit('No permission resources.');
$session_storage = 'session_'.pc_base::load_config('system','session_storage');
pc_base::load_sys_class($session_storage);
pc_base::load_app_class('foreground','member');
pc_base::load_sys_class('format', '', 0);
pc_base::load_sys_class('form', '', 0);

class index extends foreground {

	function __construct() {
		$this->db = pc_base::load_model('address_model');
		$this->_username = param::get_cookie('_username');
		$this->_userid = intval(param::get_cookie('_userid'));
		$this->siteid =  isset($_REQUEST['siteid']) && trim($_REQUEST['siteid']) ? intval($_REQUEST['siteid']) : 1;
		$this->current_user = $this->get_current_userinfo();
	}
	
	public function init() {
		
		$sql = '';

		$sql = '`siteid`=\''.$this->siteid.'\' and userid=\''.$this->_userid.'\' ';
		
		$page = max(intval($_GET['page']), 1);
		$datas = $this->db->listinfo($sql, '`aid` DESC', $page);
		
		include template('address', 'address_list');
	}

	//我的包裹数
	private function getpackagecount($storageid=0,$status=0){
		$wdb = pc_base::load_model('waybill_model');
		$packcount=0;

		$sql="userid='".$this->current_user['userid']."'";
		if($status>0)
		{
			$sql.=" and status='$status'";
		}

		if($storageid>0){
			$sql.=" and storageid='$storageid'";
			$packcount=$wdb->count($sql);
		}else{
			$packcount=$wdb->count($sql);
		}
		return $packcount;
	}
	//我的运单数
	private function getwaybillcount($storageid=0,$status=0){
		$wdb = pc_base::load_model('waybill_model');
		$waycount=0;
		$sql="userid='".$this->current_user['userid']."'";
		if($status>0)
		{
			$sql.=" and status='$status'";
		}
		if($storageid>0){
			$sql.=" and storageid='$storageid'";
			$waycount=$wdb->count($sql);
		}else{
			$waycount=$wdb->count($sql);
		}
		return $waycount;
	}

	public function setdefault(){
		
		$aid = intval($_GET['aid']);

		$this->db->update(array('isdefault'=>0),array('userid'=>$this->_userid,'addresstype'=>1));
		$this->db->update(array('isdefault'=>1),array('userid'=>$this->_userid,'aid'=>$aid));

		showmessage(L('operation_success'), HTTP_REFERER);
	}

	public function add() {
	
		$show_validator = 1;
		if(isset($_POST['dosubmit'])) {
			$_POST['address'] = $this->check(safe_array_string($_POST['address']));

			//$_POST['address']['address'] .=",#".param::get_cookie('_clientcode');

			if($this->db->insert($_POST['address'])) showmessage(L('addressment_successful_added'), HTTP_REFERER);
		} else {
			
			include template('address', 'address_add');
		}

		
	}


	public function edit() {
		
		$show_validator = 1;
		$_GET['aid'] = intval($_GET['aid']);
		if(!$_GET['aid']) showmessage(L('illegal_operation'));
		if(isset($_POST['dosubmit'])) {
			
			$_POST['address'] = $this->check(safe_array_string($_POST['address']), 'edit');

			//$_POST['address']['address'] .=",#".param::get_cookie('_clientcode');


			if($this->db->update($_POST['address'], array('aid' => $_GET['aid']))) showmessage(L('addressd_a'), HTTP_REFERER);
		} else {
			$where = array('aid' => $_GET['aid']);
			$an_info = $this->db->get_one($where);

			//$adds = explode(',#',$an_info['address']);

			//$an_info['address']= $adds[0];

			include template('address', 'address_edit');
		}
		
	}


	/**
	 * 批量删除地址簿
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
		if($data['truename']=='') showmessage(L('truename_cannot_empty'));

		$r = $this->db->get_one(array('truename' => $data['truename']));
		
		if ($a=='add') {
			if (is_array($r) && !empty($r)) {
				//showmessage(L('address_exist'), HTTP_REFERER);
			}
			$data['siteid'] = $this->siteid;
			$data['addtime'] = SYS_TIME;
			$data['username'] = $this->_username;
			$data['userid'] = $this->_userid;
			
		} else {
			if ($r['aid'] && ($r['aid']!=$_GET['aid'])) {
				showmessage(L('address_exist'), HTTP_REFERER);
			}
		}
		return $data;
	}

	private function getline(){
		$cdb = pc_base::load_model('linkage_model');
		$sql = ' parentid=0 AND keyid=0 ';
		$datas = $cdb->select($sql,'*',10000,'listorder ASC');
		return $datas;
	}
	
	
}
?>