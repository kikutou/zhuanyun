<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin','admin',0);
pc_base::load_app_func('util');

class kuaidi extends admin {
	function __construct() {
		parent::__construct();
		$this->M = new_html_special_chars(getcache('kuaidi', 'commons'));
		$this->db = pc_base::load_model('kuaidi_model');
		$this->db2 = pc_base::load_model('type_model');
		$this->siteid = $this->get_siteid();
	}

	public function init() {
		if($_GET['typeid']!=''){
			$where = array('typeid'=>$_GET['typeid'],'siteid'=>$this->siteid);
		}else{
			$where = array('siteid'=>$this->siteid);
		}
 		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
		$infos = $this->db->listinfo($where,$order = 'listorder DESC,kdid DESC',$page, $pages = '9');
		$pages = $this->db->pages;
		$types = $this->db2->listinfo(array('module'=>ROUTE_M,'siteid'=>$this->siteid),$order = 'typeid DESC');
		$types = new_html_special_chars($types);
 		$type_arr = array ();
 		foreach($types as $typeid=>$type){
			$type_arr[$type['typeid']] = $type['name'];
		}
		$big_menu = array('javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=kuaidi&c=kuaidi&a=add\', title:\''.L('kd_add').'\', width:\'700\', height:\'450\'}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'add\'}).close()});void(0);', L('kd_add'));
		include $this->admin_tpl('kuaidi_list');
	}

	//添加快递
 	public function add() {
 		if(isset($_POST['dosubmit'])) {
			pc_base::load_sys_func('iconv');
			$_POST['kuaidi']['addtime'] = SYS_TIME;
			$_POST['kuaidi']['siteid'] = $this->siteid;
			if(empty($_POST['kuaidi']['name'])) {
				showmessage(L('name_noempty'),HTTP_REFERER);
			}
			if(empty($_POST['kuaidi']['fullname'])) {
				$_POST['kuaidi']['fullname'] = $_POST['kuaidi']['name'];
			}
			$name = CHARSET == 'gbk' ? $_POST['kuaidi']['name'] : iconv('utf-8','gbk',$_POST['kuaidi']['name']);
			$letters = gbk_to_pinyin($name);
			$_POST['kuaidi']['letter'] = strtolower(implode('', $letters));
			$kdid = $this->db->insert($_POST['kuaidi'],true);
			if(!$kdid) return FALSE; 
 			$siteid = $this->siteid;
	 		//更新附件状态
			if(pc_base::load_config('system','attachment_stat') & $_POST['kuaidi']['logo']) {
				$this->attachment_db = pc_base::load_model('attachment_model');
				$this->attachment_db->api_update($_POST['kuaidi']['logo'],'kuaidi-'.$kdid,1);
			}
			showmessage(L('operation_success'),HTTP_REFERER,'', 'add');
		} else {
			//获取站点模板信息
			pc_base::load_app_func('global', 'admin');
			$siteid = $this->siteid;
			$template_list = template_list($siteid, 0);
			$site = pc_base::load_app_class('sites','admin');
			$info = $site->get_by_id($siteid);
			foreach ($template_list as $k=>$v) {
				$template_list[$v['dirname']] = $v['name'] ? $v['name'] : $v['dirname'];
				unset($template_list[$k]);
			}
			$show_validator = $show_scroll = $show_header = true;
			pc_base::load_sys_class('form', '', 0);
			$types = $this->db2->get_types($siteid);
			//print_r($types);exit;
 			include $this->admin_tpl('kuaidi_add');
		}

	}

	//修改快递
	public function edit() {
		if(isset($_POST['dosubmit'])){
			pc_base::load_sys_func('iconv');
 			$kdid = intval($_GET['kdid']);
			if($kdid < 1) return false;
			if(!is_array($_POST['kuaidi']) || empty($_POST['kuaidi'])) return false;
			if((!$_POST['kuaidi']['name']) || empty($_POST['kuaidi']['name'])) return false;
			if(empty($_POST['kuaidi']['fullname'])) {
				$_POST['kuaidi']['fullname'] = $_POST['kuaidi']['name'];
			}
			$name = CHARSET == 'gbk' ? $_POST['kuaidi']['name'] : iconv('utf-8','gbk',$_POST['kuaidi']['name']);
			$letters = gbk_to_pinyin($name);
			$_POST['kuaidi']['letter'] = strtolower(implode('', $letters));
			$this->db->update($_POST['kuaidi'],array('kdid'=>$kdid));
			//更新附件状态
			if(pc_base::load_config('system','attachment_stat') & $_POST['kuaidi']['logo']) {
				$this->attachment_db = pc_base::load_model('attachment_model');
				$this->attachment_db->api_update($_POST['kuaidi']['logo'],'kuaidi-'.$kdid,1);
			}
			showmessage(L('operation_success'),'?m=kuaidi&c=kuaidi&a=edit','', 'edit');
			
		}else{
			//获取站点模板信息
			//pc_base::load_app_func('global', 'admin');
			$siteid = $this->siteid;
			$template_list = template_list($siteid, 0);
			foreach ($template_list as $k=>$v) {
				$template_list[$v['dirname']] = $v['name'] ? $v['name'] : $v['dirname'];
				unset($template_list[$k]);
			}
 			$show_validator = $show_scroll = $show_header = true;
			pc_base::load_sys_class('form', '', 0);
			$types = $this->db2->listinfo(array('module'=> ROUTE_M,'siteid'=>$this->siteid),$order = 'typeid DESC',$page, $pages = '10');
 			$type_arr = array ();
			foreach($types as $typeid=>$type){
				$type_arr[$type['typeid']] = $type['name'];
			}
			//解出链接内容
			$info = $this->db->get_one(array('kdid'=>$_GET['kdid']));
			if(!$info) showmessage(L('kuaidi_exit'));
			extract($info); 
			//print_r($info);exit;
 			include $this->admin_tpl('kuaidi_edit');
		}

	}
	 
	//添加分类时，验证分类名是否已存在
	public function public_check_name() {
		$type_name = isset($_GET['type_name']) && trim($_GET['type_name']) ? (pc_base::load_config('system', 'charset') == 'gbk' ? iconv('utf-8', 'gbk', trim($_GET['type_name'])) : trim($_GET['type_name'])) : exit('0');
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
	 
	    
	/**
	 * 说明:异步更新排序 
	 * @param  $optionid
	 */
	public function listorder_up() {
		$result = $this->db->update(array('listorder'=>'+=1'),array('kdid'=>$_GET['kdid']));
		if($result){
			echo 1;
		} else {
			echo 0;
		}
	}
	
	//更新排序
 	public function listorder() {
		if(isset($_POST['dosubmit'])) {
			foreach($_POST['listorders'] as $kdid => $listorder) {
				$this->db->update(array('listorder'=>$listorder),array('kdid'=>$kdid));
			}
			showmessage(L('operation_success'),HTTP_REFERER);
		} 
	}
	
	//添加分类
 	public function add_type() {
		if(isset($_POST['dosubmit'])) {
			if(empty($_POST['type']['name'])) {
				showmessage(L('typename_noempty'),HTTP_REFERER);
			}
			$_POST['type']['siteid'] = $this->siteid; 
			$_POST['type']['module'] = ROUTE_M;
 			$this->db2 = pc_base::load_model('type_model');
			$typeid = $this->db2->insert($_POST['type'],true);
			if(!$typeid) return FALSE;
			showmessage(L('operation_success'),HTTP_REFERER);
		} else {
			$show_validator = $show_scroll = true; 
			$big_menu = array('javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=kuaidi&c=kuaidi&a=add\', title:\''.L('kd_add').'\', width:\'700\', height:\'450\'}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'add\'}).close()});void(0);', L('kd_add'));
 			include $this->admin_tpl('kuaidi_type_add');
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
		$infos = $this->db2->listinfo(array('module'=> ROUTE_M,'siteid'=>$this->siteid),$order = 'listorder DESC',$page, $pages = '10');
		$big_menu = array('javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=kuaidi&c=kuaidi&a=add\', title:\''.L('kd_add').'\', width:\'700\', height:\'450\'}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'add\'}).close()});void(0);', L('kd_add'));
		include $this->admin_tpl('kuaidi_list_type');
	}
 
	
	/**
	 * 修改快递分类
	 */
	public function edit_type() {
		if(isset($_POST['dosubmit'])){ 
			$typeid = intval($_GET['typeid']); 
			if($typeid < 1) return false;
			if(!is_array($_POST['type']) || empty($_POST['type'])) return false;
			if((!$_POST['type']['name']) || empty($_POST['type']['name'])) return false;
			$this->db2->update($_POST['type'],array('typeid'=>$typeid));
			showmessage(L('operation_success'),'?m=kuaidi&c=kuaidi&a=list_type','', 'edit');
		}else{
 			$show_validator = $show_scroll = $show_header = true;
			//解出分类内容
			$info = $this->db2->get_one(array('typeid'=>$_GET['typeid']));
			if(!$info) showmessage(L('linktype_exit'));
			extract($info);
			include $this->admin_tpl('kuaidi_type_edit');
		}

	}

	/**
	 * 删除快递  
	 * @param	intval	$sid	快递ID，递归删除
	 */
	public function delete() {
  		if((!isset($_GET['kdid']) || empty($_GET['kdid'])) && (!isset($_POST['kdid']) || empty($_POST['kdid']))) {
			showmessage(L('illegal_parameters'), HTTP_REFERER);
		} else {
			if(is_array($_POST['kdid'])){
				foreach($_POST['kdid'] as $kdid_arr) {
 					//批量删除快递
					$this->db->delete(array('kdid'=>$kdid_arr));
				}
				showmessage(L('operation_success'),'?m=kuaidi&c=kuaidi');
			}else{
				$kdid = intval($_GET['kdid']);
				if($kdid < 1) return false;
				//删除快递
				$result = $this->db->delete(array('kdid'=>$kdid));
				 
				if($result){
					showmessage(L('operation_success'),'?m=kuaidi&c=kuaidi');
				}else {
					showmessage(L("operation_failure"),'?m=kuaidi&c=kuaidi');
				}
			}
			showmessage(L('operation_success'), HTTP_REFERER);
		}
	}
	 
	/**
	 * 快递模块配置
	 */
	public function setting() {
		//读取配置文件
 		$siteid = $this->siteid;//当前站点 
		$module_db = pc_base::load_model('module_model');
		$info = $module_db->get_one(array('module'=>ROUTE_M));
		$setting = string2array($info['setting']);//当前站点配置
		if(isset($_POST['dosubmit'])) {
			//多站点存储配置文件
 			$setting[$siteid] = $_POST['setting'];
  			setcache('kuaidi', $setting, 'commons');
			//更新模型数据库,重设setting 数据. 
			$set = array2string($setting);
			$info = $_POST['info'];
			$info['setting'] = $set;
			//print_r($info);exit;
			$module_db->update($info, array('module'=>ROUTE_M));
			showmessage(L('setting_updates_successful'), '?m=kuaidi&c=kuaidi&a=init');
		} else {
			//获取站点模板信息
			pc_base::load_sys_class('form','',0);
			$siteid = $this->siteid;
			$temp_list = template_list($siteid, 0);
			foreach ($temp_list as $k=>$v) {
				$temp_list[$v['dirname']] = $v['name'] ? $v['name'] : $v['dirname'];
				unset($temp_list[$k]);
			}
			$show_validator = $show_scroll = true;
			$nowsetting = $setting[$siteid];
			extract($nowsetting);
			extract($info);
			$big_menu = array('javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=kuaidi&c=kuaidi&a=add\', title:\''.L('kd_add').'\', width:\'700\', height:\'450\'}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'add\'}).close()});void(0);', L('kd_add'));
			//print_r($nowsetting);exit;
 			include $this->admin_tpl('setting');
		}
	}
	
	/**
	 * 更新缓存
	 */
	public function cache() {
		$module_db = pc_base::load_model('module_model');
		$info = $module_db->get_one(array('module'=>ROUTE_M));
		$setting = string2array($info['setting']);//当前站点配置
  		setcache('kuaidi', $setting, 'commons');
		$kuaidi_list = $this->kuaidi_list = array();
		$this->kuaidi_list = $this->db->select(array('siteid'=>$this->siteid,'passed'=>'1'),'*',10000,'letter asc, listorder desc');
		foreach($this->kuaidi_list as $r) {
			unset($r['introduce']);
			$kuaidi_list[$r['kdid']] = $r;
		}
		setcache('kuaidi_list_'.$this->siteid,$kuaidi_list,'commons');
		return true;
	}
	/**
	 * 更新缓存并修复栏目
	 */
	public function public_cache() {
		$this->cache();
		showmessage(L('operation_success'),'?m=kuaidi&c=kuaidi');
	}

 	//单个审核申请
 	public function check(){
		if((!isset($_GET['kdid']) || empty($_GET['kdid'])) && (!isset($_POST['kdid']) || empty($_POST['kdid']))) {
			showmessage(L('illegal_parameters'), HTTP_REFERER);
		} else { 
			$kdid = intval($_GET['kdid']);
			if($kdid < 1) return false;
			//删除留言板
			$result = $this->db->update(array('passed'=>1),array('kdid'=>$kdid));
			if($result){
				showmessage(L('operation_success'),'?m=kuaidi&c=kuaidi');
			}else {
				showmessage(L("operation_failure"),'?m=kuaidi&c=kuaidi');
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