<?php 
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_func('global');
pc_base::load_sys_class('format', '', 0);
class member {
	
	function __construct() {		
		$this->db = pc_base::load_model('content_model');
		$this->siteid = isset($_GET['siteid']) && (intval($_GET['siteid']) > 0) ? intval(trim($_GET['siteid'])) : (param::get_cookie('siteid') ? param::get_cookie('siteid') : 1);
		param::set_cookie('siteid',$this->siteid);	
		$this->wap_site = getcache('wap_site','wap');
		$this->types = getcache('wap_type','wap');
		$this->wap = $this->wap_site[$this->siteid];
		define('WAP_SITEURL', $this->wap['domain'] ? $this->wap['domain'].'index.php?m=wap&siteid='.$this->siteid : APP_PATH.'index.php?m=wap&siteid='.$this->siteid);
		if($this->wap['status']!=1) exit(L('wap_close_status'));
		
		$this->__all__urls = get__all__urls();
		
		$this->WAP_SETTING = string2array($this->wap['setting']);

/*		if(!is_mobile_or_pc()){
			showmessage("很抱歉！应用仅限移动设备访问！");
			exit;
		}*/
		
	}
	
		
	public function login(){
		
		$action = isset($_POST['action']) ? trim($_POST['action']) : '';
		
		$password = isset($_POST['password']) ? trim($_POST['password']) : '';
		$username = isset($_POST['username']) ? trim($_POST['username']) : '';
		$url = isset($_POST['url']) ? trim($_POST['url']) : '';

	
		include template('wap', "login");
	}

	public function register(){
	
		include template('wap', "register");
	}

	public function userinfo(){
	
		include template('wap', "account_manage_info");
	}
	
    
}
?>