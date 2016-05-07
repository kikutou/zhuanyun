<?php 
defined('IN_PHPCMS') or exit('No permission resources.');

pc_base::load_app_class('foreground','member');
pc_base::load_sys_class('format', '', 0);
pc_base::load_sys_class('form', '', 0);


class index extends foreground {
	function __construct() {
		$this->db = pc_base::load_model('yuyue_model');
		$this->_username = param::get_cookie('_username');
		$this->_userid = intval(param::get_cookie('_userid'));
		$this->siteid =  isset($_REQUEST['siteid']) && trim($_REQUEST['siteid']) ? intval($_REQUEST['siteid']) : 1;
	}
	
	public function init() {
		$sql = '';
		
		$sql = '`siteid`=\''.$this->siteid.'\' and userid=\''.$this->_userid.'\' ';
		
		$page = max(intval($_GET['page']), 1);
		$datas = $this->db->listinfo($sql, '`aid` DESC', $page);
		
		include template('yuyue', 'yuyue_list');
	}
	

	public function add() {
		
		$show_validator = 1;
		if(isset($_POST['dosubmit'])) {
			$_POST['yuyue'] = $this->check(safe_array_string($_POST['yuyue']));
			
		

			if($this->db->insert($_POST['yuyue'])) showmessage(L('yuyuement_successful_added'), HTTP_REFERER);
		} else {
			$shippingtypes = $this->getshippingtype();
			$addresslist = $this->getaddresslist();	

			$yuyuetm = str_replace('"date"','"date inp"',form::date('yuyue[yuyuetime]','',''));
			include template('yuyue', 'yuyue_add');
		}

		
	}


	public function edit() {
		
		$show_validator = 1;
		$_GET['aid'] = intval($_GET['aid']);
		$aid = $_GET['aid'];
		if(!$_GET['aid']) showmessage(L('illegal_operation'));
		if(isset($_POST['dosubmit'])) {
			
			$_POST['yuyue'] = $this->check(safe_array_string($_POST['yuyue']), 'edit');
		
			if($this->db->update($_POST['yuyue'], array('aid' => $_GET['aid']))) showmessage(L('yuyued_a'), HTTP_REFERER);
		} else {
			$where = array('aid' => $_GET['aid']);
			$an_info = $this->db->get_one($where);
			$shippingtypes = $this->getshippingtype();

			$addresslist = $this->getaddresslist();	

			$yuyuetm = str_replace('"date"','"date inp"',form::date('yuyue[yuyuetime]',date('Y-m-d',$an_info['yuyuetime']),''));

			include template('yuyue', 'yuyue_edit');
		}
		
	}


	/**
	 * 批量删除预约
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
		//if($data['truename']=='') showmessage(L('truename_cannot_empty'));
		
		//$r = $this->db->get_one(array('truename' => $data['truename']));
		
		
		if ($a=='add') {
			/*if (is_array($r) && !empty($r)) {
				showmessage(L('yuyue_exist'), HTTP_REFERER);
			}*/
			$data['siteid'] = $this->siteid;
			$data['addtime'] = SYS_TIME;
			$data['username'] = $this->_username;
			$data['userid'] = $this->_userid;
			$data['yuyuetime'] = strtotime($data['yuyuetime']);
			
		} else {
			/*if ($r['aid'] && ($r['aid']!=$_GET['aid'])) {
				showmessage(L('yuyue_exist'), HTTP_REFERER);
			}*/
		}
		return $data;
	}


	/**获取仓库**/
	private function getshippingtype(){
		$cdb = pc_base::load_model('storage_model');
		$sql = ' siteid=1 ';
		$datas = $cdb->select($sql,'*',10000,'listorder ASC');
		return $datas;
	}

	/**获取转运类别名称**/
	private function getshippingtypename($typeid){
		$cdb = pc_base::load_model('storage_model');
		$sql = ' siteid=1 and  aid='.$typeid.' ';
		$datas = $cdb->get_one($sql);
		return $datas;
	}
	
	/**获取收货地址**/
	private function getaddresslist(){
		$cdb = pc_base::load_model('address_model');
		$sql = ' siteid=1 and userid='.$this->_userid.' ';
		$datas = $cdb->select($sql,'*',10000,'addtime desc');
		return $datas;
	}

}
?>