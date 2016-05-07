<?php
defined('IN_PHPCMS') or exit('No permission resources.'); 
pc_base::load_app_class('foreground','member');
pc_base::load_sys_class('format', '', 0);
pc_base::load_sys_class('form', '', 0);

class spend_list extends foreground {
	private $spend_db;
	
	function __construct() {
		if (!module_exists(ROUTE_M)) showmessage(L('module_not_exists')); 
		$this->spend_db = pc_base::load_model('pay_spend_model');
		parent::__construct();

		$this->current_user = $this->get_current_userinfo();

		$status1count=$this->getwaybillcount(0,1);
		$status3count=$this->getpackagecount(0,3);
		$status21count=$this->getwaybillcount(0,21);
		$status8count=$this->getpackagecount(0,8);
		$status7count=$this->getpackagecount(0,7);
		$status14count=$this->getpackagecount(0,14);
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
		include template('pay', 'spend_list');
	}
}

