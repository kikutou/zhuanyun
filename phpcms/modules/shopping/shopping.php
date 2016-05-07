<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin','admin',0);
class shopping extends admin {
	function __construct() {
		parent::__construct();
		$this->M = new_html_special_chars(getcache('shopping', 'commons'));
		$this->db = pc_base::load_model('shopping_model');
		$this->db2 = pc_base::load_model('type_model');
	}

	public function init() {
		if($_GET['typeid']!=''){
			$where = array('typeid'=>intval($_GET['typeid']),'siteid'=>$this->get_siteid());
		}else{
			$where = array('siteid'=>$this->get_siteid());
		}
 		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
		$infos = $this->db->listinfo($where,$order = 'listorder DESC,shoppingid DESC',$page, $pages = '9');
		$pages = $this->db->pages;
		$types = $this->db2->listinfo(array('module'=>ROUTE_M,'siteid'=>$this->get_siteid()),$order = 'typeid DESC');
		$types = new_html_special_chars($types);
 		$type_arr = array ();
 		foreach($types as $typeid=>$type){
			$type_arr[$type['typeid']] = $type['name'];
		}
		$big_menu = array('javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=shopping&c=shopping&a=add\', title:\''.L('shopping_add').'\', width:\'700\', height:\'450\'}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'add\'}).close()});void(0);', L('shopping_add'));
		include $this->admin_tpl('shopping_list');
	}

	/*
	 *判断标题重复和验证 
	 */
	public function public_name() {
		$shopping_title = isset($_GET['shopping_name']) && trim($_GET['shopping_name']) ? (pc_base::load_config('system', 'charset') == 'gbk' ? iconv('utf-8', 'gbk', trim($_GET['shopping_name'])) : trim($_GET['shopping_name'])) : exit('0');
			
		$shoppingid = isset($_GET['shoppingid']) && intval($_GET['shoppingid']) ? intval($_GET['shoppingid']) : '';
		$data = array();
		if ($shoppingid) {

			$data = $this->db->get_one(array('shoppingid'=>$shoppingid), 'name');
			if (!empty($data) && $data['name'] == $shopping_title) {
				exit('1');
			}
		}
		if ($this->db->get_one(array('name'=>$shopping_title), 'shoppingid')) {
			exit('0');
		} else {
			exit('1');
		}
	}
	 
	//添加分类时，验证分类名是否已存在
	public function public_check_name() {
		$type_name = isset($_GET['type_name']) && trim($_GET['type_name']) ? (pc_base::load_config('system', 'charset') == 'gbk' ? iconv('utf-8', 'gbk', trim($_GET['type_name'])) : trim($_GET['type_name'])) : exit('0');
		$type_name = safe_replace($type_name);
 		$typeid = isset($_GET['typeid']) && intval($_GET['typeid']) ? intval($_GET['typeid']) : '';
 		$data = array();
		if ($typeid) {
 			$data = $this->db2->get_one(array('typeid'=>$typeid), 'name');
			if (!empty($data) && $data['name'] == $type_name) {
				exit('1');
			}
		}
		if ($this->db2->get_one(array('name'=>$type_name), 'typeid')) {
			exit('0');
		} else {
			exit('1');
		}
	}
	 
	//添加购物指南
 	public function add() {
 		if(isset($_POST['dosubmit'])) {
			$_POST['shopping']['addtime'] = SYS_TIME;
			$_POST['shopping']['siteid'] = $this->get_siteid();
			if(empty($_POST['shopping']['name'])) {
				showmessage(L('sitename_noempty'),HTTP_REFERER);
			} else {
				$_POST['shopping']['name'] = safe_replace($_POST['shopping']['name']);
			}
			if ($_POST['shopping']['logo']) {
				$_POST['shopping']['logo'] = safe_replace($_POST['shopping']['logo']);
			}
			$data = new_addslashes($_POST['shopping']);
			$shoppingid = $this->db->insert($data,true);
			if(!$shoppingid) return FALSE; 
 			$siteid = $this->get_siteid();
	 		//更新附件状态
			if(pc_base::load_config('system','attachment_stat') & $_POST['shopping']['logo']) {
				$this->attachment_db = pc_base::load_model('attachment_model');
				$this->attachment_db->api_update($_POST['shopping']['logo'],'shopping-'.$shoppingid,1);
			}
			showmessage(L('operation_success'),HTTP_REFERER,'', 'add');
		} else {
			$show_validator = $show_scroll = $show_header = true;
			pc_base::load_sys_class('form', '', 0);
 			$siteid = $this->get_siteid();
			$types = $this->db2->get_types($siteid);
			
			//print_r($types);exit;
 			include $this->admin_tpl('shopping_add');
		}

	}
	
	/**
	 * 说明:异步更新排序 
	 * @param  $optionid
	 */
	public function listorder_up() {
		$result = $this->db->update(array('listorder'=>'+=1'),array('shoppingid'=>$_GET['shoppingid']));
		if($result){
			echo 1;
		} else {
			echo 0;
		}
	}
	
	//更新排序
 	public function listorder() {
		if(isset($_POST['dosubmit'])) {
			foreach($_POST['listorders'] as $shoppingid => $listorder) {
				$shoppingid = intval($shoppingid);
				$this->db->update(array('listorder'=>$listorder),array('shoppingid'=>$shoppingid));
			}
			showmessage(L('operation_success'),HTTP_REFERER);
		} 
	}
	
	//添加购物指南分类
 	public function add_type() {
		if(isset($_POST['dosubmit'])) {
			if(empty($_POST['type']['name'])) {
				showmessage(L('typename_noempty'),HTTP_REFERER);
			}
			$_POST['type']['siteid'] = $this->get_siteid(); 
			$_POST['type']['module'] = ROUTE_M;
 			$this->db2 = pc_base::load_model('type_model');
			$typeid = $this->db2->insert($_POST['type'],true);
			if(!$typeid) return FALSE;
			showmessage(L('operation_success'),HTTP_REFERER);
		} else {
			$show_validator = $show_scroll = true;
			$big_menu = array('javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=shopping&c=shopping&a=add\', title:\''.L('shopping_add').'\', width:\'700\', height:\'450\'}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'add\'}).close()});void(0);', L('shopping_add'));
 			include $this->admin_tpl('shopping_type_add');
		}

	}
	
	/**
	 * 删除分类
	 */
	public function delete_type() {
		if((!isset($_GET['typeid']) || empty($_GET['typeid'])) && (!isset($_POST['typeid']) || empty($_POST['typeid']))) {
			showmessage(L('illegal_parameters'), HTTP_REFERER);
		} else {
			if(is_array($_POST['typeid'])){
				foreach($_POST['typeid'] as $typeid_arr) {
 					$this->db2->delete(array('typeid'=>$typeid_arr));
				}
				showmessage(L('operation_success'),HTTP_REFERER);
			}else{
				$typeid = intval($_GET['typeid']);
				if($typeid < 1) return false;
				$result = $this->db2->delete(array('typeid'=>$typeid));
				if($result)
				{
					showmessage(L('operation_success'),HTTP_REFERER);
				}else {
					showmessage(L("operation_failure"),HTTP_REFERER);
				}
			}
		}
	}
	
	//:分类管理
 	public function list_type() {
		$this->db2 = pc_base::load_model('type_model');
		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
		$infos = $this->db2->listinfo(array('module'=> ROUTE_M,'siteid'=>$this->get_siteid()),$order = 'listorder DESC',$page, $pages = '10');
		$big_menu = array('javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=shopping&c=shopping&a=add\', title:\''.L('shopping_add').'\', width:\'700\', height:\'450\'}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'add\'}).close()});void(0);', L('shopping_add'));
		$pages = $this->db2->pages;
		include $this->admin_tpl('shopping_list_type');
	}
 
	public function edit() {
		if(isset($_POST['dosubmit'])){
 			$shoppingid = intval($_GET['shoppingid']);
			if($shoppingid < 1) return false;
			if(!is_array($_POST['shopping']) || empty($_POST['shopping'])) return false;
			if((!$_POST['shopping']['name']) || empty($_POST['shopping']['name'])) return false;
			$this->db->update($_POST['shopping'],array('shoppingid'=>$shoppingid));
			//更新附件状态
			if(pc_base::load_config('system','attachment_stat') & $_POST['shopping']['logo']) {
				$this->attachment_db = pc_base::load_model('attachment_model');
				$this->attachment_db->api_update($_POST['shopping']['logo'],'shopping-'.$shoppingid,1);
			}
			showmessage(L('operation_success'),'?m=shopping&c=shopping&a=edit','', 'edit');
			
		}else{
 			$show_validator = $show_scroll = $show_header = true;
			pc_base::load_sys_class('form', '', 0);
			$types = $this->db2->listinfo(array('module'=> ROUTE_M,'siteid'=>$this->get_siteid()),$order = 'typeid DESC');
 			$type_arr = array ();
			foreach($types as $typeid=>$type){
				$type_arr[$type['typeid']] = $type['name'];
			}
			//解出链接内容
			$info = $this->db->get_one(array('shoppingid'=>$_GET['shoppingid']));
			if(!$info) showmessage(L('shopping_exit'));
			extract($info); 
 			include $this->admin_tpl('shopping_edit');
		}

	}
	
	/**
	 * 修改购物指南 分类
	 */
	public function edit_type() {
		if(isset($_POST['dosubmit'])){ 
			$typeid = intval($_GET['typeid']); 
			if($typeid < 1) return false;
			if(!is_array($_POST['type']) || empty($_POST['type'])) return false;
			if((!$_POST['type']['name']) || empty($_POST['type']['name'])) return false;
			$this->db2->update($_POST['type'],array('typeid'=>$typeid));
			showmessage(L('operation_success'),'?m=shopping&c=shopping&a=list_type','', 'edit');
			
		}else{
 			$show_validator = $show_scroll = $show_header = true;
			//解出分类内容
			$info = $this->db2->get_one(array('typeid'=>$_GET['typeid']));
			if(!$info) showmessage(L('shoppingtype_exit'));
			extract($info);
			include $this->admin_tpl('shopping_type_edit');
		}

	}

	/**
	 * 删除购物指南  
	 * @param	intval	$sid	购物指南ID，递归删除
	 */
	public function delete() {
  		if((!isset($_GET['shoppingid']) || empty($_GET['shoppingid'])) && (!isset($_POST['shoppingid']) || empty($_POST['shoppingid']))) {
			showmessage(L('illegal_parameters'), HTTP_REFERER);
		} else {
			if(is_array($_POST['shoppingid'])){
				foreach($_POST['shoppingid'] as $shoppingid_arr) {
 					//批量删除购物指南
					$this->db->delete(array('shoppingid'=>$shoppingid_arr));
					//更新附件状态
					if(pc_base::load_config('system','attachment_stat')) {
						$this->attachment_db = pc_base::load_model('attachment_model');
						$this->attachment_db->api_delete('shopping-'.$shoppingid_arr);
					}
				}
				showmessage(L('operation_success'),'?m=shopping&c=shopping');
			}else{
				$shoppingid = intval($_GET['shoppingid']);
				if($shoppingid < 1) return false;
				//删除购物指南
				$result = $this->db->delete(array('shoppingid'=>$shoppingid));
				//更新附件状态
				if(pc_base::load_config('system','attachment_stat')) {
					$this->attachment_db = pc_base::load_model('attachment_model');
					$this->attachment_db->api_delete('shopping-'.$shoppingid);
				}
				if($result){
					showmessage(L('operation_success'),'?m=shopping&c=shopping');
				}else {
					showmessage(L("operation_failure"),'?m=shopping&c=shopping');
				}
			}
			showmessage(L('operation_success'), HTTP_REFERER);
		}
	}
	 
	/**
	 * 投票模块配置
	 */
	public function setting() {
		//读取配置文件
		$data = array();
 		$siteid = $this->get_siteid();//当前站点 
		//更新模型数据库,重设setting 数据. 
		$m_db = pc_base::load_model('module_model');
		$data = $m_db->select(array('module'=>'shopping'));
		$setting = string2array($data[0]['setting']);
		$now_seting = $setting[$siteid]; //当前站点配置
		if(isset($_POST['dosubmit'])) {
			//多站点存储配置文件
 			$setting[$siteid] = $_POST['setting'];
  			setcache('shopping', $setting, 'commons');  
			//更新模型数据库,重设setting 数据. 
  			$m_db = pc_base::load_model('module_model'); //调用模块数据模型
			$set = array2string($setting);
			$m_db->update(array('setting'=>$set), array('module'=>ROUTE_M));
			showmessage(L('setting_updates_successful'), '?m=shopping&c=shopping&a=init');
		} else {
			@extract($now_seting);
			$big_menu = array('javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=shopping&c=shopping&a=add\', title:\''.L('shopping_add').'\', width:\'700\', height:\'450\'}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'add\'}).close()});void(0);', L('shopping_add'));
 			include $this->admin_tpl('setting');
		}
	}
	
  	//批量审核申请 ...
 	public function check_register(){
		if(isset($_POST['dosubmit'])) {
			if((!isset($_GET['shoppingid']) || empty($_GET['shoppingid'])) && (!isset($_POST['shoppingid']) || empty($_POST['shoppingid']))) {
				showmessage(L('illegal_parameters'), HTTP_REFERER);
			} else {
				if(is_array($_POST['shoppingid'])){//批量审核
					foreach($_POST['shoppingid'] as $shoppingid_arr) {
						$this->db->update(array('passed'=>1),array('shoppingid'=>$shoppingid_arr));
					}
					showmessage(L('operation_success'),'?m=shopping&c=shopping');
				}else{//单个审核
					$shoppingid = intval($_GET['shoppingid']);
					if($shoppingid < 1) return false;
					$result = $this->db->update(array('passed'=>1),array('shoppingid'=>$shoppingid));
					if($result){
						showmessage(L('operation_success'),'?m=shopping&c=shopping');
					}else {
						showmessage(L("operation_failure"),'?m=shopping&c=shopping');
					}
				}
			}
		}else {//读取未审核列表
			$where = array('siteid'=>$this->get_siteid(),'passed'=>0);
			$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
			$infos = $this->db->listinfo($where,'shoppingid DESC',$page, $pages = '9');
			$pages = $this->db->pages;
			$big_menu = array('javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=shopping&c=shopping&a=add\', title:\''.L('shopping_add').'\', width:\'700\', height:\'450\'}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'add\'}).close()});void(0);', L('shopping_add'));
			include $this->admin_tpl('check_register_list');
		}
		
	}
	
 	//单个审核申请
 	public function check(){
		if((!isset($_GET['shoppingid']) || empty($_GET['shoppingid'])) && (!isset($_POST['shoppingid']) || empty($_POST['shoppingid']))) {
			showmessage(L('illegal_parameters'), HTTP_REFERER);
		} else { 
			$shoppingid = intval($_GET['shoppingid']);
			if($shoppingid < 1) return false;
			//删除购物指南
			$result = $this->db->update(array('passed'=>1),array('shoppingid'=>$shoppingid));
			if($result){
				showmessage(L('operation_success'),'?m=shopping&c=shopping');
			}else {
				showmessage(L("operation_failure"),'?m=shopping&c=shopping');
			}
			 
		}
	}

    
	
	/**
	 * 说明:对字符串进行处理
	 * @param $string 待处理的字符串
	 * @param $isjs 是否生成JS代码
	 */
	function format_js($string, $isjs = 1){
		$string = addslashes(str_replace(array("\r", "\n"), array('', ''), $string));
		return $isjs ? 'document.write("'.$string.'");' : $string;
	}
 
 
	
}
?>