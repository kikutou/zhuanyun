<?php
defined('IN_PHPCMS') or exit('No permission resources.'); 
pc_base::load_app_class('ajax_foreground','member');
pc_base::load_sys_class('format', '', 0);
pc_base::load_sys_class('form', '', 0);
pc_base::load_app_func('global','wap');

class spend_list extends ajax_foreground {
	private $spend_db;
	
	function __construct() {
		if (!module_exists(ROUTE_M)) showmessage(L('module_not_exists')); 
		$this->spend_db = pc_base::load_model('pay_spend_model');
		parent::__construct();

		$this->siteid =  isset($_REQUEST['siteid']) && trim($_REQUEST['siteid']) ? intval($_REQUEST['siteid']) : 1;
		$this->hid =  isset($_GET['hid']) && trim($_GET['hid']) ? intval($_GET['hid']) : 0;
		$this->current_user = $this->get_current_userinfo();
		

		$this->wap_site = getcache('wap_site','wap');
		$this->types = getcache('wap_type','wap');
		$this->wap = $this->wap_site[$this->siteid];
		define('WAP_SITEURL', $this->wap['domain'] ? $this->wap['domain'].'index.php?m=wap&siteid='.$this->siteid : APP_PATH.'index.php?m=wap&siteid='.$this->siteid);
		if($this->wap['status']!=1) exit(L('wap_close_status'));
		
		$this->__all__urls = get__all__urls();
		
		$this->WAP_SETTING = string2array($this->wap['setting']);

		if(!is_mobile_or_pc()){
			showmessage("很抱歉！应用仅限移动设备访问！");
			exit;
		}


	}
	
	public function init() {
		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
		$userid  = param::get_cookie('_userid');
		$sql =  " `userid` = '$userid'";
		if (isset($_GET['dosubmit'])) {
			$type = isset($_GET['type']) && intval($_GET['type']) ? intval($_GET['type']) : '';
			$endtime = isset($_GET['endtime'])  &&  trim($_GET['endtime']) ? strtotime(trim($_GET['endtime'])) : '';
			$starttime = isset($_GET['starttime']) && trim($_GET['starttime']) ? strtotime(trim($_GET['starttime'])) : '';
			
			if (!empty($starttime) && empty($endtime)) {
				$endtime = SYS_TIME;
			}
			
			if (!empty($starttime) && !empty($endtime) && $endtime < $starttime) {
				showmessage(L('wrong_time_over_time_to_time_less_than'));
			}
						
			if (!empty($starttime)) {
				$sql .= $sql ? " AND `creat_at` BETWEEN '$starttime' AND '$endtime' " : " `creat_at` BETWEEN '$starttime' AND '$endtime' ";
			}
			
			if (!empty($type)) {
				$sql .= $sql ? " AND `type` = '$type' " : " `type` = '$type'";
			}
			
		}
		$list = $this->spend_db->listinfo($sql, '`id` desc', $page);
		$pages = $this->spend_db->pages;
		include template('wap', 'pay_list');
	}
}