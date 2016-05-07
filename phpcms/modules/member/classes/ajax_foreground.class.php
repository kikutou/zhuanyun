<?php
class ajax_foreground {
	public $db, $memberinfo,$warehouse__lists,$takeaddress__lists;
	private $_member_modelinfo;
	
	public function __construct() {
		self::check_ip();
		$this->db = pc_base::load_model('member_model');

		$phpcms_auth = param::get_cookie('auth');
		if(ROUTE_M =='member' && ROUTE_C =='ajax_index' && in_array(ROUTE_A, array('login', 'register', 'mini','send_newmail'))) {
			if ($phpcms_auth && ROUTE_A != 'mini') {
				if($_POST){
				
				
				
				if(!isset($_GET['platform'])){die('{"status":true,"msg":"'.L('login_success', '', 'member').'","url":"index.php?m=wap&c=index"}');}

				}else{
					//showmessage(L('login_success', '', 'member'), 'index.php?m=wap&c=index');
					header("Location:index.php?m=wap&c=index");

				}
			} else {
				return true;
			}
		} else {
			//判断是否存在auth cookie
			if ($phpcms_auth) {
				$auth_key = $auth_key = md5(pc_base::load_config('system', 'auth_key').$_SERVER['HTTP_USER_AGENT']);
				list($userid, $password) = explode("\t", sys_auth($phpcms_auth, 'DECODE', $auth_key));
				//验证用户，获取用户信息
				$this->memberinfo = $this->db->get_one(array('userid'=>$userid));
				//获取用户模型信息
				$this->db->set_model($this->memberinfo['modelid']);

				$this->_member_modelinfo = $this->db->get_one(array('userid'=>$userid));
				$this->_member_modelinfo = $this->_member_modelinfo ? $this->_member_modelinfo : array();
				$this->db->set_model();
				if(is_array($this->memberinfo)) {
					$this->memberinfo = array_merge($this->memberinfo, $this->_member_modelinfo);
				}
			
				if($this->memberinfo && $this->memberinfo['password'] === $password) {

					if (!defined('SITEID')) {
					   define('SITEID', $this->memberinfo['siteid']);
					}
					
					if($this->memberinfo['groupid'] == 1) {
						param::set_cookie('auth', '');
						param::set_cookie('_userid', '');
						param::set_cookie('_username', '');
						param::set_cookie('_groupid', '');
						if($_POST){
							if(!isset($_GET['platform'])){die('{"status":false,"msg":"'.L('userid_banned_by_administrator', '', 'member').'","url":"index.php?m=member&c=ajax_index&a=login"}');}

						
						}else{
							showmessage(L('userid_banned_by_administrator', '', 'member'), 'index.php?m=member&c=ajax_index&a=login');
						}
					} elseif($this->memberinfo['groupid'] == 7) {
						param::set_cookie('auth', '');
						param::set_cookie('_userid', '');
						param::set_cookie('_groupid', '');
						
						//设置当前登录待验证账号COOKIE，为重发邮件所用
						param::set_cookie('_regusername', $this->memberinfo['username']);
						param::set_cookie('_reguserid', $this->memberinfo['userid']);
						param::set_cookie('_reguseruid', $this->memberinfo['phpssouid']);
						
						param::set_cookie('email', $this->memberinfo['email']);
						if($_POST){
							if(!isset($_GET['platform'])){die('{"status":false,"msg":"'.L('need_emial_authentication', '', 'member').'","url":"index.php?m=member&c=ajax_index&a=register&t=2"}');}
						}else{
							showmessage(L('need_emial_authentication', '', 'member'), 'index.php?m=member&c=ajax_index&a=register&t=2');
						}
					}
				} else {
					param::set_cookie('auth', '');
					param::set_cookie('_userid', '');
					param::set_cookie('_username', '');
					param::set_cookie('_groupid', '');
				}
				unset($userid, $password, $phpcms_auth, $auth_key);
			} else {
				$forward= isset($_GET['forward']) ?  urlencode($_GET['forward']) : urlencode(get_url());
				if($_POST){
					if(!isset($_GET['platform'])){die('{"status":false,"msg":"'.L('please_login', '', 'member').'","url":"index.php?m=member&c=ajax_index&a=login&forward='.$forward.'"}');}
				}else{
					header("Location:index.php?m=wap&c=member&a=login&forward=".$forward);
				}
			}
		}

	}

	/**
	 * 
	 * IP禁止判断 ...
	 */
	final private function check_ip(){
		$this->ipbanned = pc_base::load_model('ipbanned_model');
		$this->ipbanned->check_ip();
 	}

	final public function get_warehouse__lists(){
		//列出所会员仓库
		//$storagedb = pc_base::load_model('storage_model');
		$this->warehouse__lists = getcache('get__storage__lists', 'commons');//$storagedb->select("siteid=1 ",'*',10000,'listorder asc');
		return $this->warehouse__lists;
	}

	//我的包裹数
	final function get_package_count($storageid=0){
		$wdb = pc_base::load_model('package_model');
		$userid = param::get_cookie('_userid');
		$packcount=0;
		$sql="userid='".$userid."' and status=1";
		$sql.=" and storageid='$storageid'";
		$packcount=$wdb->count($sql);
		
		return $packcount;
	}
	//我的运单数
	final function get_waybill_count($storageid=0){
		$wdb = pc_base::load_model('waybill_model');
		$userid = param::get_cookie('_userid');
		$waycount=0;
		$waycount=$wdb->count(array('userid'=>$userid,'storageid'=>$storageid,'status'=>1));
		return $waycount;
	}

	final public function get_takeaddress__lists($storageid){
		//列出所有收货地址
		$adb = pc_base::load_model('ownaddress_model');
		$this->takeaddress__lists = $adb->select("siteid=1 and storageid='$storageid' ",'*',1,'addtime desc');
		return $this->takeaddress__lists;
	}
	
	final public function get_myaddress(){
		$_userid = param::get_cookie('_userid');
		
		$mdb = pc_base::load_model('member_model');
		$mdb->set_model();
		$row = $mdb->get_one(array('userid'=>$_userid),'myaddress');
	
		return $row['myaddress'];
	}
	
	final public function get_current_userinfo(){
		$userid = param::get_cookie('_userid');

		$cmdb = pc_base::load_model('member_model');
		$currentmemberinfo = $cmdb->get_one(array('userid'=>$userid));
		//获取用户模型信息
		$cmdb->set_model($currentmemberinfo['modelid']);
		$currentmember_modelinfo = $cmdb->get_one(array('userid'=>$userid));
		$all_member_modelinfo = $currentmember_modelinfo ? $currentmember_modelinfo : array();
		if(is_array($currentmemberinfo))
		$currentmemberinfo = array_merge($currentmemberinfo, $all_member_modelinfo);

		return $currentmemberinfo;
	}
}