<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin','admin',0);

class admin_service extends admin {

	private $db; public $username;
	public function __construct() {
		parent::__construct();
		//if (!module_exists(ROUTE_M)) showmessage(L('module_not_exists'));
		$this->username = param::get_cookie('admin_username');
		$this->db = pc_base::load_model('service_model');
	}
	
	public function init() {
		//服务列表
		$sql = '';

		$sql = '`siteid`=\''.$this->get_siteid().'\'';
		
		$page = max(intval($_GET['page']), 1);
		$data = $this->db->listinfo($sql, '`aid` DESC', $page);
		$big_menu = array('javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=service&c=admin_service&a=add\', title:\''.L('service_add').'\', width:\'600\', height:\'350\', lock:true}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'add\'}).close()});void(0);', L('service_add'));
		include $this->admin_tpl('service_list');
	}
	
	/**
	 * 添加服务
	 */
	public function add() {
		$show_validator = 1;
		if(isset($_POST['dosubmit'])) {
			$_POST['service'] = $this->check($_POST['service']);
			if($this->db->insert($_POST['service'])) showmessage(L('servicement_successful_added'), HTTP_REFERER, '', 'add');
		} else {
			//获取站点模板信息
			pc_base::load_app_func('global', 'admin');
			
			include $this->admin_tpl('service_add');
		}
	}
	
	/**
	 * 修改服务
	 */
	public function edit() {
		$show_validator = 1;
		$_GET['aid'] = intval($_GET['aid']);
		if(!$_GET['aid']) showmessage(L('illegal_operation'));
		if(isset($_POST['dosubmit'])) {
			$_POST['service'] = $this->check($_POST['service'], 'edit');
			if($this->db->update($_POST['service'], array('aid' => $_GET['aid']))) showmessage(L('serviced_a'), HTTP_REFERER, '', 'edit');
		} else {
			$where = array('aid' => $_GET['aid']);
			$an_info = $this->db->get_one($where);
			include $this->admin_tpl('service_edit');
		}
	}
	
	/**
	 * ajax检测服务标题是否重复
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
	 * 批量修改服务状态 使其成为审核、未审核状态
	 */
	public function public_approval($aid = 0) {
		if((!isset($_POST['aid']) || empty($_POST['aid'])) && !$aid) {
			showmessage(L('illegal_operation'));
		} else {
			if(is_array($_POST['aid']) && !$aid) {
				array_map(array($this, 'public_approval'), $_POST['aid']);
				showmessage(L('service_passed'), HTTP_REFERER);
			} elseif($aid) {
				$aid = intval($aid);
				
				$this->db->update(array('default' => $_GET['default']), array('aid' => $aid));
				return true;
			}
		}
	}
	
	/**
	 * 批量删除服务
	 */
	public function delete($aid = 0) {
		if((!isset($_POST['aid']) || empty($_POST['aid'])) && !$aid) {
			showmessage(L('illegal_operation'));
		} else {
			if(is_array($_POST['aid']) && !$aid) {
				array_map(array($this, 'delete'), $_POST['aid']);
				showmessage(L('service_deleted'), HTTP_REFERER);
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
				showmessage(L('service_exist'), HTTP_REFERER);
			}
			$data['siteid'] = $this->get_siteid();
			$data['addtime'] = SYS_TIME;
			$data['username'] = $this->username;
			
		} else {
			if ($r['aid'] && ($r['aid']!=$_GET['aid'])) {
				showmessage(L('service_exist'), HTTP_REFERER);
			}
		}
		return $data;
	}


	//获取增值服务列表
	private function valuecategory(){
		$cdb = pc_base::load_model('enum_model');
		$sql = '`siteid`=\''.$this->get_siteid().'\' AND groupid=20 ';
		$datas = $cdb->select($sql,'*',10000,'listorder ASC');
		return $datas;

	}
	
	//获取增值分类名称
	private function getvaluetype($val){
		$valname="";
		foreach($this->valuecategory() as $r){
			if($r['value']==$val)
				$valname=$r['title'];
		}
		return $valname;
	}

	/**获取所有货币**/
	private function getcurrency(){
		$cdb = pc_base::load_model('currency_model');
		$sql = '`siteid`=\''.$this->get_siteid().'\'';
		$datas = $cdb->select($sql,'*',10000);
		return $datas;
	}

	/**获取所有默认货币单位**/
	private function getdefaultcurrency(){
		$cdb = pc_base::load_model('currency_model');
		$sql = '`siteid`=\''.$this->get_siteid().'\' AND `isdefault`=1';
		$datas = $cdb->get_one($sql);

		
		return $datas;
	}

	/**获取所有单位**/
	private function getunits(){
		$cdb = pc_base::load_model('enum_model');
		$sql = '`siteid`=\''.$this->get_siteid().'\' AND groupid=2 ';
		$datas = $cdb->select($sql,'*',10000,'listorder ASC');
		return $datas;
	}

	//根据ID返回货币名称
	private function getcurrencyname($cid){
		$name="";
		foreach($this->getcurrency() as $v){
			if($v[aid]==$cid)
				$name=$v[symbol];
		}
		return $name;
	}

	//根据ID返回单位名称
	private function getunitname($cid){
		$name="";
		foreach($this->getunits() as $v){
			if($v[aid]==$cid)
				$name=$v[title];
		}
		return $name;
	}

}
?>