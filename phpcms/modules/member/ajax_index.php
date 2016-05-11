<?php
/**
 * 会员前台管理中心、账号管理、收藏操作类
 */

defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('ajax_foreground');
pc_base::load_sys_class('format', '', 0);
pc_base::load_sys_class('form', '', 0);
pc_base::load_app_func('global','wap');

class ajax_index extends ajax_foreground {

	private $times_db;
	
	function __construct() {
		parent::__construct();
		$this->http_user_agent = $_SERVER['HTTP_USER_AGENT'];

		$this->siteid = isset($_GET['siteid']) && (intval($_GET['siteid']) > 0) ? intval(trim($_GET['siteid'])) : (param::get_cookie('siteid') ? param::get_cookie('siteid') : 1);
		param::set_cookie('siteid',$this->siteid);	
		$this->wap_site = getcache('wap_site','wap');
		$this->types = getcache('wap_type','wap');
		$this->wap = $this->wap_site[$this->siteid];
		define('WAP_SITEURL', $this->wap['domain'] ? $this->wap['domain'].'index.php?m=wap&siteid='.$this->siteid : APP_PATH.'index.php?m=wap&siteid='.$this->siteid);
		if($this->wap['status']!=1) exit(L('wap_close_status'));
		
		$this->__all__urls = get__all__urls();
		
		$this->WAP_SETTING = string2array($this->wap['setting']);

	}

	public function init() {
		$memberinfo = $this->memberinfo;
		//初始化phpsso
		$phpsso_api_url = $this->_init_phpsso();
		//获取头像数组
		$avatar = $this->client->ps_getavatar($this->memberinfo['phpssouid']);
		
		$waycount=$this->getwaybillcount(0,0);
		$packcount=$this->getpackagecount(0,0);
		$inwaycount=$this->getwaybillcount(0,3);
		$inpackcount=$this->getpackagecount(0,2);

		$grouplist = getcache('grouplist');
		$memberinfo['groupname'] = $grouplist[$memberinfo[groupid]]['name'];
		header("Location:index.php?m=wap&c=index");
		include template('wap', 'index');
	}

	//我的包裹数
	private function getpackagecount($storageid=0,$status=0){
		$wdb = pc_base::load_model('package_model');
		$packcount=0;

		$sql="userid='".$this->memberinfo['userid']."'";
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
		$sql="userid='".$this->memberinfo['userid']."'";
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
	
	//我的转运地址设置
	public function myaddress(){
		$memberinfo = $this->memberinfo;
		if(isset($_POST['dosubmit'])) {
			$mdb = pc_base::load_model('member_model');
			$chkstr="";
			if(is_array($_POST['info'])){
				$chkstr = implode(',',$_POST['info']);
			}
				$mdb->update(array('myaddress'=>$chkstr),array('userid'=>$memberinfo['userid']));
			
			showmessage(L('operation_success'), 'index.php?m=member&siteid=1');
		}
		include template('member', 'myaddress');
	}

	/**获取所有地区**/
	private function getplace(){
		$cdb = pc_base::load_model('linkage_model');
		$sql = ' parentid=0 AND keyid=0 ';
		$datas = $cdb->select($sql,'*',10000,'listorder ASC');
		return $datas;
	}

	public function register() {
		$this->_session_start();
		
		$callback_data = "";
		$callback_datas = "";

		//获取用户siteid
		$siteid = isset($_REQUEST['siteid']) && trim($_REQUEST['siteid']) ? intval($_REQUEST['siteid']) : 1;
		
		$s_info = siteinfo($siteid);
		$m__db = pc_base::load_model('member_model');
		$code = isset($_GET['code'])  ? intval($_GET['code']) : 0;
		if($code>0){
			
			$mymem=$m__db->get_one(array('userid'=>$code));
			$u_name=$mymem['username'];
		}
		//定义站点id常量
		if (!defined('SITEID')) {
		   define('SITEID', $siteid);
		}
		
		//加载用户模块配置
		$member_setting = getcache('member_setting');
		if(!$member_setting['allowregister']) {
			//showmessage(L('deny_register'), 'index.php?m=member&c=index&a=login');
			$callback_data = '{"status":false,"msg":"'.L('deny_register').'","url":"index.php?m=member&c=ajax_index&a=login"}';
			$callback_datas .= $callback_data.",";
			if(!isset($_GET['platform'])){die($callback_data);}
		}
		//加载短信模块配置
 		$sms_setting_arr = getcache('sms','sms');
		$sms_setting = $sms_setting_arr[$siteid];		
		
		header("Cache-control: private");
		if(isset($_POST['dosubmit'])) {
			if($member_setting['enablcodecheck']=='1'){//开启验证码
				if ((empty($_SESSION['connectid']) && $_SESSION['code'] != strtolower($_POST['code'])) || empty($_SESSION['code'])) {
					//showmessage(L('code_error'));

					$callback_data = '{"status":false,"msg":"'.L('code_error').'","url":"index.php?m=member&c=ajax_index&a=login"}';
					$callback_datas .= $callback_data.",";
					if(!isset($_GET['platform'])){die($callback_data);}

				} else {
					$_SESSION['code'] = '';
				}
			}
			
			
			
			$userinfo = array();
			$userinfo['encrypt'] = create_randomstr(6);
			
			if((isset($_POST['username']) && is_username($_POST['username']) && !empty($_POST['username']))){
				$userinfo['username'] =   trim($_POST['username']) ;
			}else{
				$callback_data = '{"status":false,"msg":"'.L('username_empty').'","url":"'.HTTP_REFERER.'"}';
				$callback_datas .= $callback_data.",";
				if(!isset($_GET['platform'])){die($callback_data);}
			}

			$userinfo['nickname'] = (isset($_POST['nickname']) && is_username($_POST['nickname'])) ? $_POST['nickname'] : '';
			if((isset($_POST['firstname']) && !empty($_POST['firstname']))){
				$firstname =   trim($_POST['firstname']) ;
			}else{
				$callback_data = '{"status":false,"msg":"请填真实的名(拼音)","url":"'.HTTP_REFERER.'"}';
				$callback_datas .= $callback_data.",";
				if(!isset($_GET['platform'])){die($callback_data);}
			}
			if((isset($_POST['lastname']) && !empty($_POST['lastname']))){
				$lastname =   trim($_POST['lastname']);
			}else{
				$callback_data = '{"status":false,"msg":"请填真实的姓(拼音)","url":"'.HTTP_REFERER.'"}';
				$callback_datas .= $callback_data.",";
				if(!isset($_GET['platform'])){die($callback_data);}
			}


			$promotion = (isset($_POST['promotion']) ) ? trim($_POST['promotion']) : '';
			$truename = (isset($_POST['truename']) ) ? trim($_POST['truename']) : '';
			
			if((isset($_POST['email']) && is_email($_POST['email']) && !empty($_POST['email']))){
				$userinfo['email'] =   trim($_POST['email']);
			}else{
				$callback_data = '{"status":false,"msg":"'.L('input').L('email').L('or').L('email_already_exist').'","url":"'.HTTP_REFERER.'"}';
				$callback_datas .= $callback_data.",";
				if(!isset($_GET['platform'])){die($callback_data);}
			}

			if((isset($_POST['password']) && !empty($_POST['password']) )){
				$userinfo['password'] =   trim($_POST['password']);
			}else{
				$callback_data = '{"status":false,"msg":"'.L('input').L('password').'","url":"'.HTTP_REFERER.'"}';
				$callback_datas .= $callback_data.",";
				if(!isset($_GET['platform'])){die($callback_data);}
			}
			if(isset($_POST['pwdconfirm'])){
				$pwdconfirm =  trim($_POST['pwdconfirm']);
			}else{
				$callback_data = '{"status":false,"msg":"'.L('input').L('cofirmpwd').'","url":"'.HTTP_REFERER.'"}';
				$callback_datas .= $callback_data.",";
				if(!isset($_GET['platform'])){die($callback_data);}
			}

			if($userinfo['password']!=$pwdconfirm){
				
				$callback_data = '{"status":false,"msg":"'.L('passwords_not_match').'","url":"'.HTTP_REFERER.'"}';
				$callback_datas .= $callback_data.",";
				if(!isset($_GET['platform'])){die($callback_data);}
				
			}

			$userinfo['modelid'] = isset($_POST['modelid']) ? intval($_POST['modelid']) : 10;
			$userinfo['regip'] = ip();
			$userinfo['point'] = $member_setting['defualtpoint'] ? $member_setting['defualtpoint'] : 0;
			$userinfo['amount'] = $member_setting['defualtamount'] ? $member_setting['defualtamount'] : 0;
			$userinfo['regdate'] = $userinfo['lastdate'] = SYS_TIME;
			$userinfo['siteid'] = $siteid;
			$userinfo['connectid'] = isset($_SESSION['connectid']) ? $_SESSION['connectid'] : '';
			$userinfo['from'] = isset($_SESSION['from']) ? $_SESSION['from'] : '';


			/////////////////////////////////////////////////////////////////////////////////////////////////////
			$userinfo['clientname'] = get__rand__char(5);
			/* 插入订单表 */
			$error_no = $m__db->count(array('clientname'=>$userinfo['clientname']));
			do
			{
					$userinfo['clientname'] = get__rand__char(5); //获取新订单号
					$error_no = $m__db->count(array('clientname'=>$userinfo['clientname']));
			}
			while ($error_no > 0 ); //如果是订单号重复则重新提交数据

			$userinfo['clientcode'] = get__rand__char(6,1);
			/* 插入订单表 */
			$error_no = $m__db->count(array('clientcode'=>$userinfo['clientcode']));
			do
			{
					$userinfo['clientcode'] = get__rand__char(6,1); //获取新订单号
					$error_no = $m__db->count(array('clientcode'=>$userinfo['clientcode']));
			}
			while ($error_no > 0 ); //如果是订单号重复则重新提交数据

//////////////////////////////////////////////////////////////////////////////////////////////////////////
			
			//手机强制验证
			
			if($member_setting[mobile_checktype]=='1'){
				//取用户手机号
				$mobile_verify = $_POST['mobile_verify'] ? intval($_POST['mobile_verify']) : '';
				if($mobile_verify=='') die('{"status":false,"msg":"请提供正确的手机验证码！","url":"'.HTTP_REFERER.'"}');
 				$sms_report_db = pc_base::load_model('sms_report_model');
				$posttime = SYS_TIME-360;
				$where = "`id_code`='$mobile_verify' AND `posttime`>'$posttime'";
				$r = $sms_report_db->get_one($where,'*','id DESC');
 				if(!empty($r)){
					$userinfo['mobile'] = $r['mobile'];
				}else{
					//showmessage('未检测到正确的手机号码！', HTTP_REFERER);
					die('{"status":false,"msg":"未检测到正确的手机号码！","url":"'.HTTP_REFERER.'"}');
				}
 			}elseif($member_setting['mobile_checktype']=='2'){
				//获取验证码，直接通过POST，取mobile值
				$userinfo['mobile'] = isset($_POST['mobile']) ? $_POST['mobile'] : '';
			} 
			if($userinfo['mobile']!=""){
				if(!preg_match('/^1([0-9]{9})/',$userinfo['mobile'])) {
					//showmessage('请提供正确的手机号码！', HTTP_REFERER);
					$callback_data = '{"status":false,"msg":"请提供正确的手机号码！","url":"'.HTTP_REFERER.'"}';
					$callback_datas .= $callback_data.",";
					if(!isset($_GET['platform'])){die($callback_data);}
				}
			} 
 			unset($_SESSION['connectid'], $_SESSION['from']);
			
			if($member_setting['enablemailcheck']) {	//是否需要邮件验证
				$userinfo['groupid'] = 7;
			} elseif($member_setting['registerverify']) {	//是否需要管理员审核
				$modelinfo_str = $userinfo['modelinfo'] = isset($_POST['info']) ? array2string(array_map('htmlspecialchars',$_POST['info'])) : '';
				$this->verify_db = pc_base::load_model('member_verify_model');
				unset($userinfo['lastdate'],$userinfo['connectid'],$userinfo['from']);
				$userinfo = array_map('htmlspecialchars',$userinfo);
				$userinfo['modelinfo'] = $modelinfo_str;
				$this->verify_db->insert($userinfo);
				//showmessage(L('operation_success'), 'index.php?m=member&c=index&a=register&t=3');
				
				$callback_data = '{"status":true,"msg":"'.L('operation_success').'","url":"index.php?m=member&c=ajax_index&a=register&t=3"}';
				$callback_datas .= $callback_data.",";
				if(!isset($_GET['platform'])){die($callback_data);}

			} else {
				//查看当前模型是否开启了短信验证功能
				$model_field_cache = getcache('model_field_'.$userinfo['modelid'],'model');
				
				if(isset($model_field_cache['mobile']) && $model_field_cache['mobile']['disabled']==0) {
					$mobile = $_POST['info']['mobile'];
					if(!preg_match('/^1([0-9]{10})/',$mobile)) die('{"status":false,"msg":"'.L('input_right_mobile').'","url":"'.HTTP_REFERER.'"}');
					$sms_report_db = pc_base::load_model('sms_report_model');
					$posttime = SYS_TIME-300;
					$where = "`mobile`='$mobile' AND `posttime`>'$posttime'";
					$r = $sms_report_db->get_one($where);
					if(!$r || $r['id_code']!=$_POST['mobile_verify']) die('{"status":false,"msg":"'.L('error_sms_code').'","url":"'.HTTP_REFERER.'"}');
				}
				$userinfo['groupid'] = $this->_get_usergroup_bypoint($userinfo['point']);
			}
			
			if(pc_base::load_config('system', 'phpsso')) {
				$this->_init_phpsso();
				$status = $this->client->ps_member_register($userinfo['username'], $userinfo['password'], $userinfo['email'], $userinfo['regip'], $userinfo['encrypt']);
				
				if($status > 0) {
					$userinfo['phpssouid'] = $status;
					//传入phpsso为明文密码，加密后存入phpcms_v9
					$password = $userinfo['password'];
					$userinfo['password'] = password($userinfo['password'], $userinfo['encrypt']);
					
					//推荐人积分计算
					$promotion_point=0;
					$m_promo=$m__db->get_one(array('username'=>$promotion));
					if($m_promo){//若推荐人为真
						$userinfo['point'] = $s_info['regperson'];
						$promotion_point=intval($m_promo['point'])+intval($s_info['introducer']);
						$m__db->update(array('point'=>$promotion_point),array('username'=>$promotion));
						$userinfo['intropoint'] = intval($s_info['introducer']);
						$userinfo['promotion'] = $promotion;

					}

					$storagedb = pc_base::load_model('storage_model');
					$warehouse__lists = $storagedb->select("siteid=1 ",'*',10000,'listorder asc');

					$my_address='';
					foreach($warehouse__lists as $vrow){
						if($vrow['xunistorage']==0){
							if(empty($my_address))
								$my_address=$vrow['aid'];
							else
								$my_address.=','.$vrow['aid'];
						}
					}

					$userinfo['myaddress'] = $my_address;


					$userid = $this->db->insert($userinfo, 1);
					
					//会员注册成功自动给它添加默认地址
					//默认发货地址为美国仓库地址
					//默认的收货地址自取的为香港的仓库地址
					$c_area = pc_base::load_model('linkage_model');
					$addrdb = pc_base::load_model('address_model');
					$all___warehouse__lists = $this->get_warehouse__lists();
					foreach($all___warehouse__lists as $home){
						
						$val = $c_area->get_one(array('linkageid'=>$home['area']));

						$addrs=array();
						
						$addrs['truename']=$userinfo['clientname'];
						$addrs['country']=$val['name'];
						$addrs['province']=$home['province'];
						$addrs['city']=$home['city'];
						$addrs['company']=$home['company'];
						$addrs['postcode']=$home['zipcode'];
						$addrs['mobile']=$home['phone'];
						$addrs['email']=$userinfo['email'];
						$addrs['isdefault']=1;
						$addrs['siteid']=1;
						$addrs['addtime'] = SYS_TIME;
						$addrs['username'] = $userinfo['username'];
						$addrs['userid'] = $userid;
						$addrs['storageid']=$home['aid'];//指定仓库ID ，用于后台地址修改自动改变
						
						if($home['homeplace']==1){//国内地址
							$addrs['address']=$home['address'];
							$addrs['addresstype']=1;
						}else{
							$addrs['address']=$home['address'].",#".$userinfo['clientcode'];
							$addrs['addresstype']=2;
						}
						if($home['xunistorage']==0){
							$addrdb->insert($addrs);//新注册账号添加默认地址
						}
					}



					if($member_setting['choosemodel']) {	//如果开启选择模型
						//通过模型获取会员信息					
						require_once CACHE_MODEL_PATH.'member_input.class.php';
				        require_once CACHE_MODEL_PATH.'member_update.class.php';
						$member_input = new member_input($userinfo['modelid']);
						$user_model_info = array();

						//if(is_array($_POST['info'])){
						//$_POST['info'] = array_map('htmlspecialchars',$_POST['info']);
						//$user_model_info = $member_input->get($_POST['info']);
						$user_model_info['userid'] = $userid;
						$user_model_info['firstname'] = $firstname;
						$user_model_info['lastname'] = $lastname;

						if($truename){$user_model_info['truename'] = $truename;}
						else{$user_model_info['truename'] = $lastname.$firstname;}
						
						$user_model_info['pingyingname'] = $lastname.$firstname;
	
						//插入会员模型数据
						$this->db->set_model($userinfo['modelid']);
						$this->db->insert($user_model_info);
						//}
					}
					
					if($userid > 0) {
						//执行登陆操作
						if(!$cookietime) $get_cookietime = param::get_cookie('cookietime');
						$_cookietime = $cookietime ? intval($cookietime) : ($get_cookietime ? $get_cookietime : 0);
						$cookietime = $_cookietime ? TIME + $_cookietime : 0;
						
						if($userinfo['groupid'] == 7) {
							param::set_cookie('_username', $userinfo['username'], $cookietime);
							param::set_cookie('email', $userinfo['email'], $cookietime);							
						} else {
							$phpcms_auth_key = md5(pc_base::load_config('system', 'auth_key').$this->http_user_agent);
							$phpcms_auth = sys_auth($userid."\t".$userinfo['password'], 'ENCODE', $phpcms_auth_key);
							
							param::set_cookie('auth', $phpcms_auth, $cookietime);
							param::set_cookie('_userid', $userid, $cookietime);
							param::set_cookie('_username', $userinfo['username'], $cookietime);
							param::set_cookie('_nickname', $userinfo['nickname'], $cookietime);
							param::set_cookie('_groupid', $userinfo['groupid'], $cookietime);
							param::set_cookie('cookietime', $_cookietime, $cookietime);

							param::set_cookie('_clientcode', $userinfo['clientcode'], $cookietime);
							param::set_cookie('_clientname', $userinfo['clientname'], $cookietime);
						}
					}
					//如果需要邮箱认证
					if($member_setting['enablemailcheck']) {
						pc_base::load_sys_func('mail');
						$phpcms_auth_key = md5(pc_base::load_config('system', 'auth_key'));
						$code = sys_auth($userid.'|'.$phpcms_auth_key, 'ENCODE', $phpcms_auth_key);
						$url = APP_PATH."index.php?m=member&c=index&a=register&code=$code&verify=1";
						$message = $member_setting['registerverifymessage'];
						$message = str_replace(array('{click}','{url}','{username}','{email}','{password}'), array('<a href="'.$url.'">'.L('please_click').'</a>',$url,$userinfo['username'],$userinfo['email'],$password), $message);
 						sendmail($userinfo['email'], L('reg_verify_email'), $message);
						//设置当前注册账号COOKIE，为第二步重发邮件所用
						param::set_cookie('_regusername', $userinfo['username'], $cookietime);
						param::set_cookie('_reguserid', $userid, $cookietime);
						param::set_cookie('_reguseruid', $userinfo['phpssouid'], $cookietime);
						//showmessage(L('operation_success'), 'index.php?m=member&c=index&a=register&t=2');
						
						$callback_data = '{"status":true,"userid":"'.$userid.'","username":"'.$userinfo['username'].'","clientcode":"'.$userinfo['clientcode'].'","clientname":"'.$userinfo['clientname'].'","msg":"'.L('operation_success').'","url":"index.php?m=member&c=ajax_index&a=register&t=2"}';
						$callback_datas .= $callback_data.",";
						if(!isset($_GET['platform'])){die($callback_data);}

						

					} else {
						//如果不需要邮箱认证、直接登录其他应用
						$synloginstr = $this->client->ps_member_synlogin($userinfo['phpssouid']);
						if($synloginstr=='0'){$synloginstr='';}
						$forward = isset($_POST['forward']) && !empty($_POST['forward']) ? urldecode($_POST['forward']) : 'index.php?m=member&c=ajax_index&a=init';
						
						$callback_data = '{"status":true,"userid":"'.$userid.'","username":"'.$userinfo['username'].'","clientcode":"'.$userinfo['clientcode'].'","clientname":"'.$userinfo['clientname'].'","msg":"'.L('operation_success').$synloginstr.'","url":"'.$forward.'"}';
						$callback_datas .= $callback_data.",";
						if(!isset($_GET['platform'])){die($callback_data);}

						//showmessage(L('operation_success').$synloginstr, $forward);
						
					}

					
					
				}
			} else {
				//showmessage(L('enable_register').L('enable_phpsso'), 'index.php?m=member&c=index&a=login');
				$callback_data = '{"status":false,"msg":"'.L('enable_register').L('enable_phpsso').'","url":"index.php?m=member&c=ajax_index&a=login"}';
				$callback_datas .= $callback_data.",";
				if(!isset($_GET['platform'])){die($callback_data);}

			}
			//showmessage(L('operation_failure'), HTTP_REFERER);
			
			
			$callback_data = '{"status":false,"msg":"'.L('operation_failure').'","url":"'.HTTP_REFERER.'"}';
			$callback_datas .= $callback_data.",";
			if(!isset($_GET['platform'])){die($callback_data);}

		} else {
			if(!pc_base::load_config('system', 'phpsso')) {
				//showmessage(L('enable_register').L('enable_phpsso'), 'index.php?m=member&c=index&a=login');
				
				$callback_data = '{"status":false,"msg":"'.L('enable_register').L('enable_phpsso').'","url":"index.php?m=member&c=ajax_index&a=login"}';
				$callback_datas .= $callback_data.",";
				if(!isset($_GET['platform'])){die($callback_data);}

			}
			
			if(!empty($_GET['verify'])) {
				$code = isset($_GET['code']) ? trim($_GET['code']) : die('{"status":false,"msg":"'.L('enable_register').L('operation_failure').'","url":"index.php?m=wap&c=index"}');
				$phpcms_auth_key = md5(pc_base::load_config('system', 'auth_key'));
				$code_res = sys_auth($code, 'DECODE', $phpcms_auth_key);
				$code_arr = explode('|', $code_res);
				$userid = isset($code_arr[0]) ? $code_arr[0] : '';
				$userid = is_numeric($userid) ? $userid : die('{"status":false,"msg":"'.L('enable_register').L('operation_failure').'","url":"index.php?m=wap&c=index"}');

				$this->db->update(array('groupid'=>$this->_get_usergroup_bypoint()), array('userid'=>$userid));
				//showmessage(L('operation_success'), 'index.php?m=member&c=index');
				
				$callback_data = '{"status":true,"msg":"'.L('operation_success').'","url":"index.php?m=wap&c=index"}';
				$callback_datas .= $callback_data.",";
				if(!isset($_GET['platform'])){die($callback_data);}

			} elseif(!empty($_GET['protocol'])) {

				include template('member', 'protocol');
			} else {
				//过滤非当前站点会员模型
				$modellist = getcache('member_model', 'commons');
				foreach($modellist as $k=>$v) {
					if($v['siteid']!=$siteid || $v['disabled']) {
						unset($modellist[$k]);
					}
				}
				if(empty($modellist)) {
					//showmessage(L('site_have_no_model').L('deny_register'), HTTP_REFERER);
					
					
					$callback_data = '{"status":false,"msg":"'.L('site_have_no_model').L('deny_register').'","url":"'.HTTP_REFERER.'"}';
					$callback_datas .= $callback_data.",";
					if(!isset($_GET['platform'])){die($callback_data);}


				}
				//是否开启选择会员模型选项
				if($member_setting['choosemodel']) {
					$arr_rev = array_reverse($modellist);
					$first_model = array_pop($arr_rev);
					$modelid = isset($_GET['modelid']) && in_array($_GET['modelid'], array_keys($modellist)) ? intval($_GET['modelid']) : $first_model['modelid'];

					if(array_key_exists($modelid, $modellist)) {
						//获取会员模型表单
						require CACHE_MODEL_PATH.'member_form.class.php';
						$member_form = new member_form($modelid);
						$this->db->set_model($modelid);
						$forminfos = $forminfos_arr = $member_form->get();

						//万能字段过滤
						foreach($forminfos as $field=>$info) {
							if($info['isomnipotent']) {
								unset($forminfos[$field]);
							} else {
								if($info['formtype']=='omnipotent') {
									foreach($forminfos_arr as $_fm=>$_fm_value) {
										if($_fm_value['isomnipotent']) {
											$info['form'] = str_replace('{'.$_fm.'}',$_fm_value['form'], $info['form']);
										}
									}
									$forminfos[$field]['form'] = $info['form'];
								}
							}
						}
						
						$formValidator = $member_form->formValidator;
					}
				}
				$description = $modellist[$modelid]['description'];
				
				
			}

			

		}

			if(isset($_GET['platform'])){
				
				param::set_cookie('auth', '');
				param::set_cookie('_userid', '');
				param::set_cookie('_username', '');
				param::set_cookie('_groupid', '');
				param::set_cookie('_nickname', '');
				param::set_cookie('cookietime', '');

				return substr($callback_datas, 0, -1);
			}

	}
 	
	
	/*
	 * 测试邮件配置
	 */
	public function send_newmail() {
		$_username = param::get_cookie('_regusername');
		$_userid = param::get_cookie('_reguserid');
		$_ssouid = param::get_cookie('_reguseruid');
		$newemail = $_GET['newemail'];

		if($newemail==''){//邮箱为空，直接返回错误
			return '2';
		}
		$this->_init_phpsso();
		$status = $this->client->ps_checkemail($newemail);
		if($status=='-5'){//邮箱被占用
			exit('-1');
		}
		if ($status==-1) {
			$status = $this->client->ps_get_member_info($newemail, 3);
			if($status) {
				$status = unserialize($status);	//接口返回序列化，进行判断
				if (!isset($status['uid']) || $status['uid'] != intval($_ssouid)) {
					exit('-1');
				}
			} else {
				exit('-1');
			}
		}
		//验证邮箱格式
		pc_base::load_sys_func('mail');
		$phpcms_auth_key = md5(pc_base::load_config('system', 'auth_key'));
		$code = sys_auth($_userid.'|'.$phpcms_auth_key, 'ENCODE', $phpcms_auth_key);
		$url = APP_PATH."index.php?m=member&c=index&a=register&code=$code&verify=1";
		
		//读取配置获取验证信息
		$member_setting = getcache('member_setting');
		$message = $member_setting['registerverifymessage'];
		$message = str_replace(array('{click}','{url}','{username}','{email}','{password}'), array('<a href="'.$url.'">'.L('please_click').'</a>',$url,$_username,$newemail,$password), $message);
		
 		if(sendmail($newemail, L('reg_verify_email'), $message)){
			//更新新的邮箱，用来验证
 			$this->db->update(array('email'=>$newemail), array('userid'=>$_userid));
			$this->client->ps_member_edit($_username, $newemail, '', '', $_ssouid);
			$return = '1';
		}else{
			$return = '2';
		}
		echo $return;
   	}
	
	public function account_manage() {
		$memberinfo = $this->memberinfo;
		//初始化phpsso
		$phpsso_api_url = $this->_init_phpsso();
		//获取头像数组
		$avatar = $this->client->ps_getavatar($this->memberinfo['phpssouid']);
	
		$grouplist = getcache('grouplist');
		$member_model = getcache('member_model', 'commons');

		//获取用户模型数据
		$this->db->set_model($this->memberinfo['modelid']);
		$member_modelinfo_arr = $this->db->get_one(array('userid'=>$this->memberinfo['userid']));
		$model_info = getcache('model_field_'.$this->memberinfo['modelid'], 'model');
		foreach($model_info as $k=>$v) {
			if($v['formtype'] == 'omnipotent') continue;
			if($v['formtype'] == 'image') {
				$member_modelinfo[$v['name']] = "<a href='$member_modelinfo_arr[$k]' target='_blank'><img src='$member_modelinfo_arr[$k]' height='40' widht='40' onerror=\"this.src='$phpsso_api_url/statics/images/member/nophoto.gif'\"></a>";
			} elseif($v['formtype'] == 'datetime' && $v['fieldtype'] == 'int') {	//如果为日期字段
				$member_modelinfo[$v['name']] = format::date($member_modelinfo_arr[$k], $v['format'] == 'Y-m-d H:i:s' ? 1 : 0);
			} elseif($v['formtype'] == 'images') {
				$tmp = string2array($member_modelinfo_arr[$k]);
				$member_modelinfo[$v['name']] = '';
				if(is_array($tmp)) {
					foreach ($tmp as $tv) {
						$member_modelinfo[$v['name']] .= " <a href='$tv[url]' target='_blank'><img src='$tv[url]' height='40' widht='40' onerror=\"this.src='$phpsso_api_url/statics/images/member/nophoto.gif'\"></a>";
					}
					unset($tmp);
				}
			} elseif($v['formtype'] == 'box') {	//box字段，获取字段名称和值的数组
				$tmp = explode("\n",$v['options']);
				if(is_array($tmp)) {
					foreach($tmp as $boxv) {
						$box_tmp_arr = explode('|', trim($boxv));
						if(is_array($box_tmp_arr) && isset($box_tmp_arr[1]) && isset($box_tmp_arr[0])) {
							$box_tmp[$box_tmp_arr[1]] = $box_tmp_arr[0];
							$tmp_key = intval($member_modelinfo_arr[$k]);
						}
					}
				}
				if(isset($box_tmp[$tmp_key])) {
					$member_modelinfo[$v['name']] = $box_tmp[$tmp_key];
				} else {
					$member_modelinfo[$v['name']] = $member_modelinfo_arr[$k];
				}
				unset($tmp, $tmp_key, $box_tmp, $box_tmp_arr);
			} elseif($v['formtype'] == 'linkage') {	//如果为联动菜单
				$tmp = string2array($v['setting']);
				$tmpid = $tmp['linkageid'];
				$linkagelist = getcache($tmpid, 'linkage');
				$fullname = $this->_get_linkage_fullname($member_modelinfo_arr[$k], $linkagelist);

				$member_modelinfo[$v['name']] = substr($fullname, 0, -1);
				unset($tmp, $tmpid, $linkagelist, $fullname);
			} else {
				$member_modelinfo[$v['name']] = $member_modelinfo_arr[$k];
			}
		}

		include template('member', 'account_manage');
	}

	public function account_manage_avatar() {
		$memberinfo = $this->memberinfo;
		//初始化phpsso
		$phpsso_api_url = $this->_init_phpsso();
		$ps_auth_key = pc_base::load_config('system', 'phpsso_auth_key');
		$auth_data = $this->client->auth_data(array('uid'=>$this->memberinfo['phpssouid'], 'ps_auth_key'=>$ps_auth_key), '', $ps_auth_key);
		$upurl = base64_encode($phpsso_api_url.'/index.php?m=phpsso&c=index&a=uploadavatar&auth_data='.$auth_data);
		//获取头像数组
		$avatar = $this->client->ps_getavatar($this->memberinfo['phpssouid']);
		
		include template('member', 'account_manage_avatar');
	}

	public function account_manage_security() {
		$memberinfo = $this->memberinfo;
		include template('member', 'account_manage_security');
	}
	//推荐奖励
	public function promotion(){
		$memberinfo = $this->memberinfo;

		$pagesize = 20;
		$page = max(intval($_GET['page']),1);
		$infos = $this->db->listinfo(array('promotion'=>$memberinfo['username']),'regdate DESC',$page);

		include template('member', 'account_promotion');
	}
	public function account_manage_info() {
		$show_validator = 1;
		//die('{"status":false,"msg":"请正确填写邮箱","url":"'.HTTP_REFERER.'"}');
		$place = $this->getplace();	

		if(isset($_POST['dosubmit'])) {
			
			/*
			pingyingname: pingyingname,
			truename: truename,
			firstname: firstname,
			lastname: lastname,
			email: email,
			sex: sex,
			country: country,
			province: province,
			city: city,
			address: address,
			placeid: placeid,
			idcard: idcard,
			mobile: mobile,
			telephone: telephone,
			postcode: postcode,
			nowconnect: nowconnect,
			msn: msn,
			remark: remark,
			forward: forward,
			*/
			//$_POST['info']['pingyingname'] = !empty($_POST['pingyingname']) ? safe_replace($_POST['pingyingname']) : '';
			$_POST['info']['truename'] = !empty($_POST['truename']) ? safe_replace($_POST['truename']) : die('{"status":false,"msg":"请保持与身份证上姓名相同","url":"'.HTTP_REFERER.'"}');
			//$_POST['info']['firstname'] = !empty($_POST['firstname']) ? safe_replace($_POST['firstname']) : die('{"status":false,"msg":"请正确填写英文名称","url":"'.HTTP_REFERER.'"}');
			//$_POST['info']['lastname'] = !empty($_POST['lastname']) ? safe_replace($_POST['lastname']) : die('{"status":false,"msg":"请正确填写英文姓别","url":"'.HTTP_REFERER.'"}');
			$_POST['info']['email'] = !empty($_POST['email']) ? safe_replace($_POST['email']) : die('{"status":false,"msg":"请正确填写邮箱","url":"'.HTTP_REFERER.'"}');
			$_POST['info']['sex'] = intval($_POST['sex']);
			$_POST['info']['country'] = !empty($_POST['country']) ? safe_replace($_POST['country']) : die('{"status":false,"msg":"请正确填写国家","url":"'.HTTP_REFERER.'"}');
			$_POST['info']['province'] = !empty($_POST['province']) ? safe_replace($_POST['province']) : die('{"status":false,"msg":"请正确填写省/州","url":"'.HTTP_REFERER.'"}');
			$_POST['info']['city'] = !empty($_POST['city']) ? safe_replace($_POST['city']) : die('{"status":false,"msg":"请正确填写城市","url":"'.HTTP_REFERER.'"}');
			$_POST['info']['address'] = !empty($_POST['address']) ? safe_replace($_POST['address']) : die('{"status":false,"msg":"请正确填写地址","url":"'.HTTP_REFERER.'"}');
			$_POST['info']['placeid'] = intval($_POST['placeid']);
			$_POST['info']['idcard'] = !empty($_POST['idcard']) ? safe_replace($_POST['idcard']) : die('{"status":false,"msg":"请输入身份证号码","url":"'.HTTP_REFERER.'"}'); 
			$_POST['info']['mobile'] = !empty($_POST['mobile']) ? safe_replace($_POST['mobile']) : die('{"status":false,"msg":"请输入手机号码","url":"'.HTTP_REFERER.'"}');
			$_POST['info']['telephone'] = !empty($_POST['telephone']) ? safe_replace($_POST['telephone']) : '';

			$_POST['info']['postcode'] = !empty($_POST['postcode']) ? safe_replace($_POST['postcode']) : '';
			$_POST['info']['nowconnect'] = !empty($_POST['nowconnect']) ? safe_replace($_POST['nowconnect']) : '';
			//$_POST['info']['msn'] = !empty($_POST['msn']) ? safe_replace($_POST['msn']) : '';
			$_POST['info']['remark'] = !empty($_POST['remark']) ? safe_replace($_POST['remark']) : '';

			if(!preg_match('/^1([0-9]{9})/',$_POST['info']['mobile'])) {
				die('{"status":false,"msg":"请提供正确的手机号码！","url":"'.HTTP_REFERER.'"}');
			}
			


			//判断收货地址是否存在
			$addressdb = pc_base::load_model('address_model');
			$addr = $addressdb->get_one(array('userid'=>$this->memberinfo['userid'],'addresstype'=>1));
			if(!$addr){//不存在自动添加一个
				$country = $_POST['info']['country'];
				$province = $_POST['info']['province'];
				$city = $_POST['info']['city'];
				$address = $_POST['info']['address'];
				$email = $_POST['info']['email'];
				$postcode = $_POST['info']['postcode'];
				$truename = $_POST['info']['truename'];
				$mobile = $_POST['info']['mobile'];
				

				$address_arr=array();
				$address_arr['country'] = $country;
				$address_arr['province'] = $province;
				$address_arr['city'] = $city;
				$address_arr['address'] = $address;
				$address_arr['email'] = $email;
				$address_arr['postcode'] = $postcode;
				$address_arr['truename'] = $truename;
				$address_arr['mobile'] = $mobile;
				$address_arr['addresstype'] = 1;
				$address_arr['userid'] = $this->memberinfo['userid'];
				$address_arr['username'] = $this->memberinfo['username'];
				$address_arr['siteid'] = 1;
				if(!empty($country) &&  !empty($province) && !empty($city) && !empty($address) && !empty($email) && !empty($truename) && !empty($mobile))
					$addressdb->insert(safe_array_string($address_arr));
			}

			//更新用户昵称
			$nickname = isset($_POST['nickname']) && is_username(trim($_POST['nickname'])) ? trim($_POST['nickname']) : '';
			if($nickname) {
				$this->db->update(array('nickname'=>$nickname), array('userid'=>$this->memberinfo['userid']));
				if(!isset($cookietime)) {
					$get_cookietime = param::get_cookie('cookietime');
				}
				$_cookietime = $cookietime ? intval($cookietime) : ($get_cookietime ? $get_cookietime : 0);
				$cookietime = $_cookietime ? TIME + $_cookietime : 0;
				param::set_cookie('_nickname', $nickname, $cookietime);
			}
			require_once CACHE_MODEL_PATH.'member_input.class.php';
			require_once CACHE_MODEL_PATH.'member_update.class.php';
			$member_input = new member_input($this->memberinfo['modelid']);


			$email = safe_replace($_POST['info']['email']);
			$mobile = safe_one_string($_POST['info']['mobile']);
			
			$this->db->update(array('mobile'=>$mobile,'email'=>$email), array('userid'=>$this->memberinfo['userid']));

			unset($_POST['info']['email']);
			unset($_POST['info']['mobile']);

			$modelinfo = $member_input->get(safe_array_string($_POST['info']));

			$this->db->set_model($this->memberinfo['modelid']);
			$membermodelinfo = $this->db->get_one(array('userid'=>$this->memberinfo['userid']));
			if(!empty($membermodelinfo)) {
				$this->db->update($modelinfo, array('userid'=>$this->memberinfo['userid']));
			} else {
				$modelinfo['userid'] = $this->memberinfo['userid'];
				$this->db->insert($modelinfo);
			}
			if($_POST){
				die('{"status":true,"msg":"'.L('operation_success').'","url":"'.HTTP_REFERER.'"}');
			}else{
				showmessage(L('operation_success'), 'index.php?m=wap&c=index');
			}
		} else {
			$memberinfo = $this->memberinfo;
			//获取会员模型表单
			require CACHE_MODEL_PATH.'member_form.class.php';
			$member_form = new member_form($this->memberinfo['modelid']);
			$this->db->set_model($this->memberinfo['modelid']);
			
			$membermodelinfo = $this->db->get_one(array('userid'=>$this->memberinfo['userid']));
			$forminfos = $forminfos_arr = $member_form->get($membermodelinfo);

			//万能字段过滤
			foreach($forminfos as $field=>$info) {
				if($info['isomnipotent']) {
					unset($forminfos[$field]);
				} else {
					if($info['formtype']=='omnipotent') {
						foreach($forminfos_arr as $_fm=>$_fm_value) {
							if($_fm_value['isomnipotent']) {
								$info['form'] = str_replace('{'.$_fm.'}',$_fm_value['form'], $info['form']);
							}
						}
						$forminfos[$field]['form'] = $info['form'];
					}
				}
			}
						
			$formValidator = $member_form->formValidator;

			include template('wap', 'account_manage_info');
		}
	}
	
	public function account_manage_password() {
		$show_validator=1;
		if(isset($_POST['dosubmit'])) {
			$updateinfo = array();
			if(!is_password($_POST['info']['password'])) {
				showmessage(L('password_format_incorrect'), HTTP_REFERER);
			}
			if($this->memberinfo['password'] != password($_POST['info']['password'], $this->memberinfo['encrypt'])) {
				showmessage(L('old_password_incorrect'), HTTP_REFERER);
			}
			//修改会员邮箱
			if($this->memberinfo['email'] != $_POST['info']['email'] && is_email($_POST['info']['email'])) {
				$email = $_POST['info']['email'];
				$updateinfo['email'] = $_POST['info']['email'];
			} else {
				$email = '';
			}
			$newpassword = password($_POST['info']['newpassword'], $this->memberinfo['encrypt']);
			$updateinfo['password'] = $newpassword;
			
			$this->db->update($updateinfo, array('userid'=>$this->memberinfo['userid']));
			if(pc_base::load_config('system', 'phpsso')) {
				//初始化phpsso
				$this->_init_phpsso();
				$res = $this->client->ps_member_edit('', $email, $_POST['info']['password'], $_POST['info']['newpassword'], $this->memberinfo['phpssouid'], $this->memberinfo['encrypt']);
				
				$message_error = array('-1'=>L('user_not_exist'), '-2'=>L('old_password_incorrect'), '-3'=>L('email_already_exist'), '-4'=>L('email_error'), '-5'=>L('param_error'));
				if ($res!='1') showmessage($message_error[$res]);
			}

			showmessage(L('password_edit_success'), HTTP_REFERER);
		} else {
			$show_validator = true;
			$memberinfo = $this->memberinfo;
			
			include template('member', 'account_manage_password');
		}
	}
	
	public function account_manage_upgrade() {
		$memberinfo = $this->memberinfo;
		$grouplist = getcache('grouplist');
		if(empty($grouplist[$memberinfo['groupid']]['allowupgrade'])) {
			showmessage(L('deny_upgrade'), HTTP_REFERER);
		}
		if(isset($_POST['upgrade_type']) && intval($_POST['upgrade_type']) < 0) {
			showmessage(L('operation_failure'), HTTP_REFERER);
		}

		if(isset($_POST['upgrade_date']) && intval($_POST['upgrade_date']) < 0) {
			showmessage(L('operation_failure'), HTTP_REFERER);
		}

		if(isset($_POST['dosubmit'])) {
			$groupid = isset($_POST['groupid']) ? intval($_POST['groupid']) : showmessage(L('operation_failure'), HTTP_REFERER);
			
			$upgrade_type = isset($_POST['upgrade_type']) ? intval($_POST['upgrade_type']) : showmessage(L('operation_failure'), HTTP_REFERER);
			$upgrade_date = !empty($_POST['upgrade_date']) ? intval($_POST['upgrade_date']) : showmessage(L('operation_failure'), HTTP_REFERER);

			//消费类型，包年、包月、包日，价格
			$typearr = array($grouplist[$groupid]['price_y'], $grouplist[$groupid]['price_m'], $grouplist[$groupid]['price_d']);
			//消费类型，包年、包月、包日，时间
			$typedatearr = array('366', '31', '1');
			//消费的价格
			$cost = $typearr[$upgrade_type]*$upgrade_date;
			//购买时间
			$buydate = $typedatearr[$upgrade_type]*$upgrade_date*86400;
			$overduedate = $memberinfo['overduedate'] > SYS_TIME ? ($memberinfo['overduedate']+$buydate) : (SYS_TIME+$buydate);

			if($memberinfo['amount'] >= $cost) {
				$this->db->update(array('groupid'=>$groupid, 'overduedate'=>$overduedate, 'vip'=>1), array('userid'=>$memberinfo['userid']));
				//消费记录
				pc_base::load_app_class('spend','pay',0);
				spend::amount($cost, L('allowupgrade'), $memberinfo['userid'], $memberinfo['username']);
				showmessage(L('operation_success'), 'index.php?m=member&c=index&a=init');
			} else {
				showmessage(L('operation_failure'), HTTP_REFERER);
			}

		} else {
			
			$groupid = isset($_GET['groupid']) ? intval($_GET['groupid']) : '';
			//初始化phpsso
			$phpsso_api_url = $this->_init_phpsso();
			//获取头像数组
			$avatar = $this->client->ps_getavatar($this->memberinfo['phpssouid']);
			
			
			$memberinfo['groupname'] = $grouplist[$memberinfo[groupid]]['name'];
			$memberinfo['grouppoint'] = $grouplist[$memberinfo[groupid]]['point'];
			unset($grouplist[$memberinfo['groupid']]);
			include template('member', 'account_manage_upgrade');
		}
	}
	
	public function login() {

		$this->_session_start();
		//获取用户siteid
		$siteid = isset($_REQUEST['siteid']) && trim($_REQUEST['siteid']) ? intval($_REQUEST['siteid']) : 1;
		//定义站点id常量
		if (!defined('SITEID')) {
		   define('SITEID', $siteid);
		}
		
		if(isset($_POST['dosubmit'])) {

/*			if($member_setting['enablcodecheck']=='1'){//开启验证码

				if(empty($_SESSION['connectid'])) {
					//判断验证码
					$code = isset($_POST['code']) && trim($_POST['code']) ? trim($_POST['code']) : die('{"status":false,"msg":"'.L('input_code').'","url":"'.HTTP_REFERER.'"}');
					if ($_SESSION['code'] != strtolower($code)) {
						//showmessage(L('code_error'), HTTP_REFERER);
						die('{"status":false,"msg":"'.L('code_error').'","url":"'.HTTP_REFERER.'"}');
					}
				}

			}*/
			
			$username = isset($_POST['username']) && trim($_POST['username']) ? trim($_POST['username']) : die('{"status":false,"msg":"'.L('username_empty').'","url":"'.HTTP_REFERER.'"}');
			$password = isset($_POST['password']) && trim($_POST['password']) ? trim($_POST['password']) : die('{"status":false,"msg":"'.L('password_empty').'","url":"'.HTTP_REFERER.'"}');
			$cookietime = intval($_POST['cookietime']);
			$synloginstr = ''; //同步登陆js代码
			
			if(pc_base::load_config('system', 'phpsso')) {
				$this->_init_phpsso();
				$status = $this->client->ps_member_login($username, $password);

				$memberinfo = unserialize($status);
				
				if(isset($memberinfo['userid'])) {
					//查询帐号
					$r = $this->db->get_one(array('phpssouid'=>$memberinfo['userid']));
					if(!$r) {
						//插入会员详细信息，会员不存在 插入会员
						$info = array(
									'phpssouid'=>$memberinfo['userid'],
						 			'username'=>$memberinfo['username'],
						 			'password'=>$memberinfo['password'],
						 			'encrypt'=>$memberinfo['encrypt'],
						 			'email'=>$memberinfo['email'],
						 			'regip'=>$memberinfo['regip'],
						 			'regdate'=>$memberinfo['regdate'],
						 			'lastip'=>$memberinfo['lastip'],
						 			'lastdate'=>$memberinfo['lastdate'],
						 			'groupid'=>$this->_get_usergroup_bypoint(),	//会员默认组
						 			'modelid'=>10,	//普通会员
									);
									
						//如果是connect用户
						if(!empty($_SESSION['connectid'])) {
							$userinfo['connectid'] = $_SESSION['connectid'];
						}
						if(!empty($_SESSION['from'])) {
							$userinfo['from'] = $_SESSION['from'];
						}
						unset($_SESSION['connectid'], $_SESSION['from']);
						
						$this->db->insert($info);
						unset($info);
						$r = $this->db->get_one(array('phpssouid'=>$memberinfo['uid']));
					}
					$password = $r['password'];
					$synloginstr = $this->client->ps_member_synlogin($r['phpssouid']);
 				} else {
					if($status == -1) {	//用户不存在
						//showmessage(L('user_not_exist'), 'index.php?m=member&c=index&a=login');
						
						die('{"status":false,"msg":"'.L('user_not_exist').'","url":"index.php?m=member&c=ajax_index&a=login"}');

					} elseif($status == -2) { //密码错误
						//showmessage(L('password_error'), 'index.php?m=member&c=index&a=login');
						
						die('{"status":false,"msg":"'.L('password_error').'","url":"index.php?m=member&c=ajax_index&a=login"}');

					} else {
						//showmessage(L('login_failure'), 'index.php?m=member&c=index&a=login');
						die('{"status":false,"msg":"'.L('login_failure').'","url":"index.php?m=member&c=ajax_index&a=login"}');
					}
				}
				
			} else {
				//密码错误剩余重试次数
				$this->times_db = pc_base::load_model('times_model');
				$rtime = $this->times_db->get_one(array('username'=>$username));
				if($rtime['times'] > 4) {
					$minute = 60 - floor((SYS_TIME - $rtime['logintime']) / 60);
					//showmessage(L('wait_1_hour', array('minute'=>$minute)));
					die('{"status":false,"msg":"'.L('wait_1_hour', array('minute'=>$minute)).'","url":"index.php?m=member&c=ajax_index&a=login"}');

				}
				
				//查询帐号
				$r = $this->db->get_one(array('username'=>$username));

				if(!$r) die('{"status":false,"msg":"'.L('user_not_exist').'","url":"index.php?m=member&c=ajax_index&a=login"}');
				
				//验证用户密码
				$password = md5(md5(trim($password)).$r['encrypt']);
				if($r['password'] != $password) {				
					$ip = ip();
					if($rtime && $rtime['times'] < 5) {
						$times = 5 - intval($rtime['times']);
						$this->times_db->update(array('ip'=>$ip, 'times'=>'+=1'), array('username'=>$username));
					} else {
						$this->times_db->insert(array('username'=>$username, 'ip'=>$ip, 'logintime'=>SYS_TIME, 'times'=>1));
						$times = 5;
					}
					//showmessage(L('password_error', array('times'=>$times)), 'index.php?m=member&c=index&a=login', 3000);
					 die('{"status":false,"msg":"'.L('password_error', array('times'=>$times)).'","url":"index.php?m=member&c=ajax_index&a=login"}');
				}
				$this->times_db->delete(array('username'=>$username));
			}
			
			//如果用户被锁定
			if($r['islock']) {
				//showmessage(L('user_is_lock'));
				die('{"status":false,"msg":"'.L('user_is_lock').'","url":"index.php?m=member&c=ajax_index&a=login"}');
			}
			
			$userid = $r['userid'];
			$groupid = $r['groupid'];
			$username = $r['username'];
			$nickname = empty($r['nickname']) ? $username : $r['nickname'];
			
			$updatearr = array('lastip'=>ip(), 'lastdate'=>SYS_TIME);
			//vip过期，更新vip和会员组
			if($r['overduedate'] < SYS_TIME) {
				$updatearr['vip'] = 0;
			}		

			//检查用户积分，更新新用户组，除去邮箱认证、禁止访问、游客组用户、vip用户，如果该用户组不允许自助升级则不进行该操作		
			if($r['point'] >= 0 && !in_array($r['groupid'], array('1', '7', '8')) && empty($r[vip])) {
				$grouplist = getcache('grouplist');
				if(!empty($grouplist[$r['groupid']]['allowupgrade'])) {	
					$check_groupid = $this->_get_usergroup_bypoint($r['point']);
	
					if($check_groupid != $r['groupid']) {
						$updatearr['groupid'] = $groupid = $check_groupid;
					}
				}
			}


			//------------------------------Auto  Begin

			$_siteinfos = siteinfo(1);
			$this_wdb = pc_base::load_model('waybill_model');
			$this_wgdb = pc_base::load_model('waybill_goods_model');
			$this_pdb = pc_base::load_model('package_model');
			$__mdb = pc_base::load_model('member_model');


			$package_overday = intval(substr($_siteinfos['package_overday'],0,2));
			$package_overday2 = intval(substr($_siteinfos['package_overday'],2,4));
			$package_overdayfee = floatval($_siteinfos['package_overdayfee']);
			$_over_time_ =  86400 *  $package_overday;
			$_over_time2_ =  86400 *  $package_overday2;

			$this_pdb->query("UPDATE t_package  SET status='18' where operatorid>0 AND (operatorid+".$_over_time2_.")<".SYS_TIME." and status=2 and userid='".$userid."' order by addtime desc ");

			$psql="select * from t_package where operatorid>0 AND (operatorid+".$_over_time_.")<".SYS_TIME." and status=2 and userid='".$userid."' order by addtime desc ";

			$presult = $this_pdb->query($psql);
			$pdata_array = $this_pdb->fetch_array($presult);
			foreach($pdata_array as $_k=>$rs) {
				$wrow = $this_wgdb->get_one(array('packageid'=>$rs['aid'],'returncode'=>$rs['returncode']));
				if($wrow){
					$_waybillid = trim($wrow['waybillid']);
					
					$ways = $this_wdb->get_one(array('waybillid'=>trim($_waybillid),'returncode'=>$rs['returncode']));

					$_wayrate = trim($ways['wayrate']);
					$_allvaluedfee = trim($ways['allvaluedfee']);
					$_overdaysfee = floatval($ways['overdaysfee']);
					$_overdayscount = $ways['overdayscount'];
					$_payfeeusd = floatval($ways['payfeeusd']);
					
					$overtime = date('Y-m-d H:i:s',strtotime("+$package_overday day",$rs['operatorid']));
					$currenttime = date('Y-m-d H:i:s',SYS_TIME);
					$new_days = abs(floor((strtotime($overtime)-strtotime($currenttime))/86400));

					$new_overdaysfee = number_format(($new_days * $package_overdayfee), 2, '.', '');
					
					if($_payfeeusd>0){
						$_payfeeusd_new = $_payfeeusd - $_overdaysfee + $new_overdaysfee; //sub old fee
						$this_wdb->update(array('payfeeusd'=>$_payfeeusd_new,'payedfee'=>($_payfeeusd_new*$_wayrate),'overdayscount'=>$new_days,'overdaysfee'=>$new_overdaysfee),array('waybillid'=>trim($_waybillid),'returncode'=>$rs['returncode']));
					}
				}
			}

			/***
			[运费+总增值费+其它费用+税金费用+包裹超期费用(美元/天)]=实付运费(USD)*汇率=实付运费(RMB)
			**/
			
			//------------------------------Auto Finsh Begin
			if($_siteinfos['autofinsh']){
				
				$moduledb = pc_base::load_model('module_model');
				$historydb = pc_base::load_model('waybill_history_model');//历史记录
				$_limit_time_ =  86400 *  intval($_siteinfos['autofinsh']);

				$wsql="select * from t_waybill where sendtime>0 AND (sendtime+".$_limit_time_.")<".SYS_TIME." and status in(7,18,10,9,12,13,14,15) and userid='".$userid."' order by addtime desc ";
				$result = $this_wdb->query($wsql);
				$data_array = $this_wdb->fetch_array($result);
				foreach($data_array as $num=>$row) {
					if($this_wdb->update(array('status'=>16),array('userid'=>$userid,'waybillid'=>trim($row['waybillid'])))){
					
						
						//插入处理记录
						$handle=array();
						$handle['siteid'] = trim($row['siteid']);
						$handle['username'] = trim($row['username']);
						$handle['userid'] = trim($row['userid']);
						$handle['addtime'] = SYS_TIME;
						$handle['sysbillid'] = trim($row['sysbillid']);
						$handle['waybillid'] = trim($row['waybillid']);
						$handle['placeid'] = $row['placeid'];
						$handle['placename'] = $row['placename'];
						$handle['status'] = 16;
						$handle['remark'] = "已签收";
						$statusinfo=$handle['remark'];	

						$lid = $historydb->insert($handle);

						//发邮件						
						$userinfo=$__mdb->get_one(array('userid'=>$row['userid']));

						pc_base::load_sys_func('mail');
						$member_setting = $moduledb->get_one(array('module'=>'member'), 'setting');
						$member_setting = string2array($member_setting['setting']);
						$url = APP_PATH."index.php?m=waybill&c=index&a=init&hid=".$row['storageid'];
						$message = $member_setting['expressnoemail'];
						$message = str_replace(array('{click}','{url}','{no}','{status}'), array('<a href="'.$url.'">'.L('please_click').'</a>',$url,$row['waybillid'],$statusinfo), $message);
						sendmail($userinfo['email'], '你的运单号'.$row['waybillid'].$statusinfo, $message);
				
					}

				}

			}
			
			//------------------------------Auto Finsh End


			//如果是connect用户
			if(!empty($_SESSION['connectid'])) {
				$updatearr['connectid'] = $_SESSION['connectid'];
			}
			if(!empty($_SESSION['from'])) {
				$updatearr['from'] = $_SESSION['from'];
			}
			unset($_SESSION['connectid'], $_SESSION['from']);
						
			$this->db->update($updatearr, array('userid'=>$userid));
			
			if(!isset($cookietime)) {
				$get_cookietime = param::get_cookie('cookietime');
			}
			$_cookietime = $cookietime ? intval($cookietime) : ($get_cookietime ? $get_cookietime : 0);
			$cookietime = $_cookietime ? SYS_TIME + $_cookietime : 0;
			
			$phpcms_auth_key = md5(pc_base::load_config('system', 'auth_key').$this->http_user_agent);
			$phpcms_auth = sys_auth($userid."\t".$password, 'ENCODE', $phpcms_auth_key);
			
			param::set_cookie('auth', $phpcms_auth, $cookietime);
			param::set_cookie('_userid', $userid, $cookietime);
			param::set_cookie('_username', $username, $cookietime);
			param::set_cookie('_groupid', $groupid, $cookietime);
			param::set_cookie('_nickname', $nickname, $cookietime);
			//param::set_cookie('cookietime', $_cookietime, $cookietime);
			$forward = isset($_POST['forward']) && !empty($_POST['forward']) ? urldecode($_POST['forward']) : 'index.php?m=wap&c=index';
			if($synloginstr=='0'){$synloginstr='';}
			//showmessage(L('login_success').$synloginstr, $forward);

			die('{"status":true,"msg":"'.L('login_success').$synloginstr.'","url":"'.$forward.'"}');

		} else {
			$setting = pc_base::load_config('system');
			$forward = isset($_GET['forward']) && trim($_GET['forward']) ? urlencode($_GET['forward']) : '';
			
			$siteid = isset($_REQUEST['siteid']) && trim($_REQUEST['siteid']) ? intval($_REQUEST['siteid']) : 1;
			$siteinfo = siteinfo($siteid);

			include template('wap', 'login');
		}
	}
  	
	public function logout() {
		$setting = pc_base::load_config('system');
		//snda退出
		if($setting['snda_enable'] && param::get_cookie('_from')=='snda') {
			param::set_cookie('_from', '');
			$forward = isset($_GET['forward']) && trim($_GET['forward']) ? urlencode($_GET['forward']) : '';
			$logouturl = 'https://cas.sdo.com/cas/logout?url='.urlencode(APP_PATH.'index.php?m=member&c=ajax_index&a=logout&forward='.$forward);
			header('Location: '.$logouturl);
		} else {
			$synlogoutstr = '';	//同步退出js代码
			if(pc_base::load_config('system', 'phpsso')) {
				$this->_init_phpsso();
				$synlogoutstr = $this->client->ps_member_synlogout();			
			}
			
			param::set_cookie('auth', '');
			param::set_cookie('_userid', '');
			param::set_cookie('_username', '');
			param::set_cookie('_groupid', '');
			param::set_cookie('_nickname', '');
			param::set_cookie('cookietime', '');
			$forward = isset($_GET['forward']) && trim($_GET['forward']) ? $_GET['forward'] : 'index.php?m=member&c=ajax_index&a=login';
			//showmessage(L('logout_success').$synlogoutstr, $forward);
			
			header("Location: ".$forward);
		}
	}

	/**
	 * 我的收藏
	 * 
	 */
	public function favorite() {
		$this->favorite_db = pc_base::load_model('favorite_model');
		$memberinfo = $this->memberinfo;
		if(isset($_GET['id']) && trim($_GET['id'])) {
			$this->favorite_db->delete(array('userid'=>$memberinfo['userid'], 'id'=>intval($_GET['id'])));
			showmessage(L('operation_success'), HTTP_REFERER);
		} else {
			$page = isset($_GET['page']) && trim($_GET['page']) ? intval($_GET['page']) : 1;
			$favoritelist = $this->favorite_db->listinfo(array('userid'=>$memberinfo['userid']), 'id DESC', $page, 10);
			$pages = $this->favorite_db->pages;

			include template('member', 'favorite_list');
		}
	}
	
	/**
	 * 我的好友
	 */
	public function friend() {
		$memberinfo = $this->memberinfo;
		$this->friend_db = pc_base::load_model('friend_model');
		if(isset($_GET['friendid'])) {
			$this->friend_db->delete(array('userid'=>$memberinfo['userid'], 'friendid'=>intval($_GET['friendid'])));
			showmessage(L('operation_success'), HTTP_REFERER);
		} else {
			//初始化phpsso
			$phpsso_api_url = $this->_init_phpsso();
	
			//我的好友列表userid
			$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
			$friendids = $this->friend_db->listinfo(array('userid'=>$memberinfo['userid']), '', $page, 10);
			$pages = $this->friend_db->pages;
			foreach($friendids as $k=>$v) {
				$friendlist[$k]['friendid'] = $v['friendid'];
				$friendlist[$k]['avatar'] = $this->client->ps_getavatar($v['phpssouid']);
				$friendlist[$k]['is'] = $v['is'];
			}
			include template('member', 'friend_list');
		}
	}
	

	//付款运单
	public function pay_bill(){
		$memberinfo = $this->memberinfo;
		//加载用户模块配置
		$member_setting = getcache('member_setting');
		$this->_init_phpsso();
		$setting = $this->client->ps_getcreditlist();
		$outcredit = unserialize($setting);
		$setting = $this->client->ps_getapplist();
		$applist = unserialize($setting);
		
		//获取实际重及体积重
		
		
		
		if(isset($_POST['dosubmit'])) {
			
			$account_total = isset($_POST['account_total']) ? floatval($_POST['account_total']) : 0;
			$oid = isset($_POST['oid']) ? intval($_POST['oid']) : 0;
			if($oid>0 && $account_total>0){
				
				$twdb = pc_base::load_model('waybill_model');
				$bill = $twdb->get_one(array('aid'=>$oid));
				
				if($memberinfo['amount']>=$account_total){//有足够款可以直接扣掉
					$value = floatval($account_total);
					$waybillid=trim($bill['waybillid']);
					$msg = $memberinfo['username'].'已成功付款运单：'.$waybillid;
					$spend = pc_base::load_app_class('spend','pay');
					
					$func = 'amount';
					$spend->$func($value,$msg,$memberinfo['userid'],$memberinfo['username'],0,$memberinfo['username']);

					//扣款成功
					$waydb = pc_base::load_model('waybill_model');
					$waydb->update(array('status'=>7),array('aid'=>$oid));

					//插入记录
					

					//插入处理记录
					$historydb = pc_base::load_model('waybill_history_model');
					$sdb = pc_base::load_model('storage_model');
					$val = $sdb->get_one(array('aid'=>trim($bill['storageid'])));//查当前仓库,入库
					$_storagename = $val['title'];

					$handle=array();
					$handle['siteid'] = 1;
					$handle['username'] = $memberinfo['username'];
					$handle['userid'] = $memberinfo['userid'];
					$handle['addtime'] = SYS_TIME;
					$handle['sysbillid'] = trim($bill['sysbillid']);
					$handle['waybillid'] = trim($waybillid);
					$handle['placeid'] = trim($bill['storageid']);
					$handle['placename'] = $_storagename;
					$handle['status'] = 7;
					$handle['remark'] = "余额支付--已付款";

					$lid = $historydb->insert($handle);


					showmessage($msg, "/index.php?m=waybill&c=index&a=init&hid=".$bill['storageid']);
				}else{
					showmessage("账户余额不足，请充值后再付款或选用在线支付!", HTTP_REFERER);
				}
			
			}else{
				showmessage("非法操作!", HTTP_REFERER);
			}
		
		}
	}

	/**
	 * 积分兑换
	 */
	public function change_credit() {
		$memberinfo = $this->memberinfo;
		//加载用户模块配置
		$member_setting = getcache('member_setting');
		$this->_init_phpsso();
		$setting = $this->client->ps_getcreditlist();
		$outcredit = unserialize($setting);
		$setting = $this->client->ps_getapplist();
		$applist = unserialize($setting);
		
		if(isset($_POST['dosubmit'])) {
			//本系统积分兑换数
			$fromvalue = intval($_POST['fromvalue']);
			//本系统积分类型
			$from = $_POST['from'];
			$toappid_to = explode('_', $_POST['to']);
			//目标系统appid
			$toappid = $toappid_to[0];
			//目标系统积分类型
			$to = $toappid_to[1];
			if($from == 1) {
				if($memberinfo['point'] < $fromvalue) {
					showmessage(L('need_more_point'), HTTP_REFERER);
				}
			} elseif($from == 2) {
				if($memberinfo['amount'] < $fromvalue) {
					showmessage(L('need_more_amount'), HTTP_REFERER);
				}
			} else {
				showmessage(L('credit_setting_error'), HTTP_REFERER);
			}

			$status = $this->client->ps_changecredit($memberinfo['phpssouid'], $from, $toappid, $to, $fromvalue);
			if($status == 1) {
				if($from == 1) {
					$this->db->update(array('point'=>"-=$fromvalue"), array('userid'=>$memberinfo['userid']));
				} elseif($from == 2) {
					$this->db->update(array('amount'=>"-=$fromvalue"), array('userid'=>$memberinfo['userid']));
				}
				showmessage(L('operation_success'), HTTP_REFERER);
			} else {
				showmessage(L('operation_failure'), HTTP_REFERER);
			}
		} elseif(isset($_POST['buy'])) {
			if(!is_numeric($_POST['money']) || $_POST['money'] < 0) {
				showmessage(L('money_error'), HTTP_REFERER);
			} else {
				$money = intval($_POST['money']);
			}
			
			if($memberinfo['amount'] < $money) {
				showmessage(L('short_of_money'), HTTP_REFERER);
			}
			//此处比率读取用户配置
			$point = $money*$member_setting['rmb_point_rate'];
			$this->db->update(array('point'=>"+=$point"), array('userid'=>$memberinfo['userid']));
			//加入消费记录，同时扣除金钱
			pc_base::load_app_class('spend','pay',0);
			spend::amount($money, L('buy_point'), $memberinfo['userid'], $memberinfo['username']);
			showmessage(L('operation_success'), HTTP_REFERER);
		} else {
			$credit_list = pc_base::load_config('credit');
			
			include template('member', 'change_credit');
		}
	}
	
	//mini登陆条
	public function mini() {
		$_username = param::get_cookie('_username');
		$_userid = param::get_cookie('_userid');
		$siteid = isset($_GET['siteid']) ? intval($_GET['siteid']) : '';
		//定义站点id常量
		if (!defined('SITEID')) {
		   define('SITEID', $siteid);
		}
		
		$snda_enable = pc_base::load_config('system', 'snda_enable');
		include template('member', 'mini');
	}
	
	/**
	 * 初始化phpsso
	 * about phpsso, include client and client configure
	 * @return string phpsso_api_url phpsso地址
	 */
	private function _init_phpsso() {
		pc_base::load_app_class('client', '', 0);
		define('APPID', pc_base::load_config('system', 'phpsso_appid'));
		$phpsso_api_url = pc_base::load_config('system', 'phpsso_api_url');
		$phpsso_auth_key = pc_base::load_config('system', 'phpsso_auth_key');
		$this->client = new client($phpsso_api_url, $phpsso_auth_key);
		return $phpsso_api_url;
	}
	
	protected function _checkname($username) {
		$username =  trim($username);
		if ($this->db->get_one(array('username'=>$username))){
			return false;
		}
		return true;
	}
	
	private function _session_start() {
		$session_storage = 'session_'.pc_base::load_config('system','session_storage');
		pc_base::load_sys_class($session_storage);
	}
	
	/*
	 * 通过linkageid获取名字路径
	 */
	protected function _get_linkage_fullname($linkageid,  $linkagelist) {
		$fullname = '';
		if($linkagelist['data'][$linkageid]['parentid'] != 0) {
			$fullname = $this->_get_linkage_fullname($linkagelist['data'][$linkageid]['parentid'], $linkagelist);
		}
		//所在地区名称
		$return = $fullname.$linkagelist['data'][$linkageid]['name'].'>';
		return $return;
	}
	
	/**
	 *根据积分算出用户组
	 * @param $point int 积分数
	 */
	protected function _get_usergroup_bypoint($point=0) {
		$groupid = 2;
		if(empty($point)) {
			$member_setting = getcache('member_setting');
			$point = $member_setting['defualtpoint'] ? $member_setting['defualtpoint'] : 0;
		}
		$grouplist = getcache('grouplist');
		
		foreach ($grouplist as $k=>$v) {
			$grouppointlist[$k] = $v['point'];
		}
		arsort($grouppointlist);

		//如果超出用户组积分设置则为积分最高的用户组
		if($point > max($grouppointlist)) {
			$groupid = key($grouppointlist);
		} else {
			foreach ($grouppointlist as $k=>$v) {
				if($point >= $v) {
					$groupid = $tmp_k;
					break;
				}
				$tmp_k = $k;
			}
		}
		return $groupid;
	}
				
	/**
	 * 检查用户名
	 * @param string $username	用户名
	 * @return $status {-4：用户名禁止注册;-1:用户名已经存在 ;1:成功}
	 */
	public function public_checkname_ajax() {
		$username = isset($_GET['username']) && trim($_GET['username']) ? trim($_GET['username']) : exit(0);
		if(CHARSET != 'utf-8') {
			$username = iconv('utf-8', CHARSET, $username);
			$username = addslashes($username);
		}
		$username = safe_replace($username);
		//首先判断会员审核表
		$this->verify_db = pc_base::load_model('member_verify_model');
		if($this->verify_db->get_one(array('username'=>$username))) {
			exit('0');
		}
	
		//$this->_init_phpsso();
		//$status = $this->client->ps_checkname($username);
		$mdb = pc_base::load_model('member_model');
		
		//if($status == -4 || $status == -1) {
		if($mdb->get_one(array('username'=>$username))) {
			exit('0');
		} else {
			exit('1');
		}
	}
	
	/**
	 * 检查用户昵称
	 * @param string $nickname	昵称
	 * @return $status {0:已存在;1:成功}
	 */
	public function public_checknickname_ajax() {
		exit('1');
		$nickname = isset($_GET['nickname']) && trim($_GET['nickname']) ? trim($_GET['nickname']) : exit('0');
		if(CHARSET != 'utf-8') {
			$nickname = iconv('utf-8', CHARSET, $nickname);
			$nickname = addslashes($nickname);
		} 
		//首先判断会员审核表
		$this->verify_db = pc_base::load_model('member_verify_model');
		if($this->verify_db->get_one(array('nickname'=>$nickname))) {
			exit('0');
		}
		if(isset($_GET['userid'])) {
			$userid = intval($_GET['userid']);
			//如果是会员修改，而且NICKNAME和原来优质一致返回1，否则返回0
			$info = get_memberinfo($userid);
			if($info['nickname'] == $nickname){//未改变
				exit('1');
			}else{//已改变，判断是否已有此名
				$where = array('nickname'=>$nickname);
				$res = $this->db->get_one($where);
				if($res) {
					exit('0');
				} else {
					exit('1');
				}
			}
 		} else {
			$where = array('nickname'=>$nickname);
			$res = $this->db->get_one($where);
			if($res) {
				exit('0');
			} else {
				exit('1');
			}
		} 
	}
	
	/**
	 * 检查邮箱
	 * @param string $email
	 * @return $status {-1:email已经存在 ;-5:邮箱禁止注册;1:成功}
	 */
	public function public_checkemail_ajax() {
		//$this->_init_phpsso();
		$email = isset($_GET['email']) && trim($_GET['email']) ? trim($_GET['email']) : exit(0);
		$mdb = pc_base::load_model('member_model');
		
	
		if($mdb->get_one(array('email'=>$email))) {
			exit('-1');
		}else{
			exit('1');
		}

		/*$status = $this->client->ps_checkemail($email);
		if($status == -5) {	//禁止注册
			exit('0');
		} elseif($status == -1) {	//用户名已存在，但是修改用户的时候需要判断邮箱是否是当前用户的
			if(isset($_GET['phpssouid'])) {	//修改用户传入phpssouid
				$status = $this->client->ps_get_member_info($email, 3);
				if($status) {
					$status = unserialize($status);	//接口返回序列化，进行判断
					if (isset($status['uid']) && $status['uid'] == intval($_GET['phpssouid'])) {
						exit('1');
					} else {
						exit('0');
					}
				} else {
					exit('0');
				}
			} else {
				exit('0');
			}
		} else {
			exit('1');
		}*/
	}
	
	public function public_sina_login() {
		define('WB_AKEY', pc_base::load_config('system', 'sina_akey'));
		define('WB_SKEY', pc_base::load_config('system', 'sina_skey'));
		pc_base::load_app_class('weibooauth', '' ,0);
		$this->_session_start();
					
		if(isset($_GET['callback']) && trim($_GET['callback'])) {
			$o = new WeiboOAuth(WB_AKEY, WB_SKEY, $_SESSION['keys']['oauth_token'], $_SESSION['keys']['oauth_token_secret']);
			$_SESSION['last_key'] = $o->getAccessToken($_REQUEST['oauth_verifier']);
			$c = new WeiboClient(WB_AKEY, WB_SKEY, $_SESSION['last_key']['oauth_token'], $_SESSION['last_key']['oauth_token_secret']);
			//获取用户信息
			$me = $c->verify_credentials();
			if(CHARSET != 'utf-8') {
				$me['name'] = iconv('utf-8', CHARSET, $me['name']);
				$me['location'] = iconv('utf-8', CHARSET, $me['location']);
				$me['description'] = iconv('utf-8', CHARSET, $me['description']);
				$me['screen_name'] = iconv('utf-8', CHARSET, $me['screen_name']);
			}
			if(!empty($me['id'])) {
 				//检查connect会员是否绑定，已绑定直接登录，未绑定提示注册/绑定页面
				$where = array('connectid'=>$me['id'], 'from'=>'sina');
				$r = $this->db->get_one($where);
				
				//connect用户已经绑定本站用户
				if(!empty($r)) {
					//读取本站用户信息，执行登录操作
					
					$password = $r['password'];
					$this->_init_phpsso();
					$synloginstr = $this->client->ps_member_synlogin($r['phpssouid']);
					$userid = $r['userid'];
					$groupid = $r['groupid'];
					$username = $r['username'];
					$nickname = empty($r['nickname']) ? $username : $r['nickname'];
					$this->db->update(array('lastip'=>ip(), 'lastdate'=>SYS_TIME, 'nickname'=>$me['name']), array('userid'=>$userid));
					
					if(!$cookietime) $get_cookietime = param::get_cookie('cookietime');
					$_cookietime = $cookietime ? intval($cookietime) : ($get_cookietime ? $get_cookietime : 0);
					$cookietime = $_cookietime ? TIME + $_cookietime : 0;
					
					$phpcms_auth_key = md5(pc_base::load_config('system', 'auth_key').$this->http_user_agent);
					$phpcms_auth = sys_auth($userid."\t".$password, 'ENCODE', $phpcms_auth_key);
					
					param::set_cookie('auth', $phpcms_auth, $cookietime);
					param::set_cookie('_userid', $userid, $cookietime);
					param::set_cookie('_username', $username, $cookietime);
					param::set_cookie('_groupid', $groupid, $cookietime);
					param::set_cookie('cookietime', $_cookietime, $cookietime);
					param::set_cookie('_nickname', $nickname, $cookietime);
					$forward = isset($_GET['forward']) && !empty($_GET['forward']) ? $_GET['forward'] : 'index.php?m=member&c=index';
					showmessage(L('login_success').$synloginstr, $forward);
					
				} else {
 					//弹出绑定注册页面
					$_SESSION = array();
					$_SESSION['connectid'] = $me['id'];
					$_SESSION['from'] = 'sina';
					$connect_username = $me['name'];
					
					//加载用户模块配置
					$member_setting = getcache('member_setting');
					if(!$member_setting['allowregister']) {
						showmessage(L('deny_register'), 'index.php?m=member&c=index&a=login');
					}
					
					//获取用户siteid
					$siteid = isset($_REQUEST['siteid']) && trim($_REQUEST['siteid']) ? intval($_REQUEST['siteid']) : 1;
					//过滤非当前站点会员模型
					$modellist = getcache('member_model', 'commons');
					foreach($modellist as $k=>$v) {
						if($v['siteid']!=$siteid || $v['disabled']) {
							unset($modellist[$k]);
						}
					}
					if(empty($modellist)) {
						showmessage(L('site_have_no_model').L('deny_register'), HTTP_REFERER);
					}
					
					$modelid = 10; //设定默认值
					if(array_key_exists($modelid, $modellist)) {
						//获取会员模型表单
						require CACHE_MODEL_PATH.'member_form.class.php';
						$member_form = new member_form($modelid);
						$this->db->set_model($modelid);
						$forminfos = $forminfos_arr = $member_form->get();

						//万能字段过滤
						foreach($forminfos as $field=>$info) {
							if($info['isomnipotent']) {
								unset($forminfos[$field]);
							} else {
								if($info['formtype']=='omnipotent') {
									foreach($forminfos_arr as $_fm=>$_fm_value) {
										if($_fm_value['isomnipotent']) {
											$info['form'] = str_replace('{'.$_fm.'}',$_fm_value['form'], $info['form']);
										}
									}
									$forminfos[$field]['form'] = $info['form'];
								}
							}
						}
						
						$formValidator = $member_form->formValidator;
					}
					include template('member', 'connect');
				}
			} else {
				showmessage(L('login_failure'), 'index.php?m=member&c=index&a=login');
			}
		} else {
			$o = new WeiboOAuth(WB_AKEY, WB_SKEY);
			$keys = $o->getRequestToken();
			$aurl = $o->getAuthorizeURL($keys['oauth_token'] ,false , APP_PATH.'index.php?m=member&c=index&a=public_sina_login&callback=1');
			$_SESSION['keys'] = $keys;
			
			
			include template('member', 'connect_sina');
		}
	}
	
	/**
	 * 盛大通行证登陆
	 */
	public function public_snda_login() {
		define('SNDA_AKEY', pc_base::load_config('system', 'snda_akey'));
		define('SNDA_SKEY', pc_base::load_config('system', 'snda_skey'));
		define('SNDA_CALLBACK', urlencode(APP_PATH.'index.php?m=member&c=index&a=public_snda_login&callback=1'));
		
		pc_base::load_app_class('OauthSDK', '' ,0);
		$this->_session_start();		
		if(isset($_GET['callback']) && trim($_GET['callback'])) {
					
			$o = new OauthSDK(SNDA_AKEY, SNDA_SKEY, SNDA_CALLBACK);
			$code = $_REQUEST['code'];
			$accesstoken = $o->getAccessToken($code);
		
			if(is_numeric($accesstoken['sdid'])) {
				$userid = $accesstoken['sdid'];
			} else {
				showmessage(L('login_failure'), 'index.php?m=member&c=index&a=login');
			}

			if(!empty($userid)) {
				
				//检查connect会员是否绑定，已绑定直接登录，未绑定提示注册/绑定页面
				$where = array('connectid'=>$userid, 'from'=>'snda');
				$r = $this->db->get_one($where);
				
				//connect用户已经绑定本站用户
				if(!empty($r)) {
					//读取本站用户信息，执行登录操作
					$password = $r['password'];
					$this->_init_phpsso();
					$synloginstr = $this->client->ps_member_synlogin($r['phpssouid']);
					$userid = $r['userid'];
					$groupid = $r['groupid'];
					$username = $r['username'];
					$nickname = empty($r['nickname']) ? $username : $r['nickname'];
					$this->db->update(array('lastip'=>ip(), 'lastdate'=>SYS_TIME, 'nickname'=>$me['name']), array('userid'=>$userid));
					if(!$cookietime) $get_cookietime = param::get_cookie('cookietime');
					$_cookietime = $cookietime ? intval($cookietime) : ($get_cookietime ? $get_cookietime : 0);
					$cookietime = $_cookietime ? TIME + $_cookietime : 0;
					
					$phpcms_auth_key = md5(pc_base::load_config('system', 'auth_key').$this->http_user_agent);
					$phpcms_auth = sys_auth($userid."\t".$password, 'ENCODE', $phpcms_auth_key);
					
					param::set_cookie('auth', $phpcms_auth, $cookietime);
					param::set_cookie('_userid', $userid, $cookietime);
					param::set_cookie('_username', $username, $cookietime);
					param::set_cookie('_groupid', $groupid, $cookietime);
					param::set_cookie('cookietime', $_cookietime, $cookietime);
					param::set_cookie('_nickname', $nickname, $cookietime);
					param::set_cookie('_from', 'snda');
					$forward = isset($_GET['forward']) && !empty($_GET['forward']) ? $_GET['forward'] : 'index.php?m=member&c=index';
					showmessage(L('login_success').$synloginstr, $forward);
				} else {				
					//弹出绑定注册页面
					$_SESSION = array();
					$_SESSION['connectid'] = $userid;
					$_SESSION['from'] = 'snda';
					$connect_username = $userid;
					include template('member', 'connect');
				}
			}	
		} else {
			$o = new OauthSDK(SNDA_AKEY, SNDA_SKEY, SNDA_CALLBACK);
			$accesstoken = $o->getSystemToken();		
			$aurl = $o->getAuthorizeURL();
			
			include template('member', 'connect_snda');
		}
		
	}
	
	
	/**
	 * QQ号码登录
	 * 该函数为QQ登录回调地址
	 */
	public function public_qq_loginnew(){
                $appid = pc_base::load_config('system', 'qq_appid');
                $appkey = pc_base::load_config('system', 'qq_appkey');
                $callback = pc_base::load_config('system', 'qq_callback');
                pc_base::load_app_class('qqapi','',0);
                $info = new qqapi($appid,$appkey,$callback);
                $this->_session_start();
                if(!isset($_GET['code'])){
                         $info->redirect_to_login();
                }else{
					$code = $_GET['code'];
					$openid = $_SESSION['openid'] = $info->get_openid($code);
					if(!empty($openid)){
						$r = $this->db->get_one(array('connectid'=>$openid,'from'=>'qq'));
						
						 if(!empty($r)){
								//QQ已存在于数据库，则直接转向登陆操作
								$password = $r['password'];
								$this->_init_phpsso();
								$synloginstr = $this->client->ps_member_synlogin($r['phpssouid']);
								$userid = $r['userid'];
								$groupid = $r['groupid'];
								$username = $r['username'];
								$nickname = empty($r['nickname']) ? $username : $r['nickname'];
								$this->db->update(array('lastip'=>ip(), 'lastdate'=>SYS_TIME, 'nickname'=>$me['name']), array('userid'=>$userid));
								if(!$cookietime) $get_cookietime = param::get_cookie('cookietime');
								$_cookietime = $cookietime ? intval($cookietime) : ($get_cookietime ? $get_cookietime : 0);
								$cookietime = $_cookietime ? TIME + $_cookietime : 0;
								$phpcms_auth_key = md5(pc_base::load_config('system', 'auth_key').$this->http_user_agent);
								$phpcms_auth = sys_auth($userid."\t".$password, 'ENCODE', $phpcms_auth_key);
								param::set_cookie('auth', $phpcms_auth, $cookietime);
								param::set_cookie('_userid', $userid, $cookietime);
								param::set_cookie('_username', $username, $cookietime);
								param::set_cookie('_groupid', $groupid, $cookietime);
								param::set_cookie('cookietime', $_cookietime, $cookietime);
								param::set_cookie('_nickname', $nickname, $cookietime);
								$forward = isset($_GET['forward']) && !empty($_GET['forward']) ? $_GET['forward'] : 'index.php?m=member&c=index';
								showmessage(L('login_success').$synloginstr, $forward);
						}else{	
								//未存在于数据库中，跳去完善资料页面。页面预置用户名（QQ返回是UTF8编码，如有需要进行转码）
								$user = $info->get_user_info();
 								$_SESSION['connectid'] = $openid;
								$_SESSION['from'] = 'qq';
								if(CHARSET != 'utf-8') {//转编码
									$connect_username = iconv('utf-8', CHARSET, $user['nickname']); 
								} else {
									 $connect_username = $user['nickname']; 
								}
 								include template('member', 'connect');
						}
					}
                }
    }
	
	/**
	 * QQ微博登录
	 */
	public function public_qq_login() {
		define('QQ_AKEY', pc_base::load_config('system', 'qq_akey'));
		define('QQ_SKEY', pc_base::load_config('system', 'qq_skey'));
		pc_base::load_app_class('qqoauth', '' ,0);
		$this->_session_start();
		if(isset($_GET['callback']) && trim($_GET['callback'])) {
			$o = new WeiboOAuth(QQ_AKEY, QQ_SKEY, $_SESSION['keys']['oauth_token'], $_SESSION['keys']['oauth_token_secret']);
			$_SESSION['last_key'] = $o->getAccessToken($_REQUEST['oauth_verifier']);
			
			if(!empty($_SESSION['last_key']['name'])) {
				//检查connect会员是否绑定，已绑定直接登录，未绑定提示注册/绑定页面
				$where = array('connectid'=>$_REQUEST['openid'], 'from'=>'qq');
				$r = $this->db->get_one($where);
				
				//connect用户已经绑定本站用户
				if(!empty($r)) {
					//读取本站用户信息，执行登录操作
					$password = $r['password'];
					$this->_init_phpsso();
					$synloginstr = $this->client->ps_member_synlogin($r['phpssouid']);
					$userid = $r['userid'];
					$groupid = $r['groupid'];
					$username = $r['username'];
					$nickname = empty($r['nickname']) ? $username : $r['nickname'];
					$this->db->update(array('lastip'=>ip(), 'lastdate'=>SYS_TIME, 'nickname'=>$me['name']), array('userid'=>$userid));
					if(!$cookietime) $get_cookietime = param::get_cookie('cookietime');
					$_cookietime = $cookietime ? intval($cookietime) : ($get_cookietime ? $get_cookietime : 0);
					$cookietime = $_cookietime ? TIME + $_cookietime : 0;
					
					$phpcms_auth_key = md5(pc_base::load_config('system', 'auth_key').$this->http_user_agent);
					$phpcms_auth = sys_auth($userid."\t".$password, 'ENCODE', $phpcms_auth_key);
					
					param::set_cookie('auth', $phpcms_auth, $cookietime);
					param::set_cookie('_userid', $userid, $cookietime);
					param::set_cookie('_username', $username, $cookietime);
					param::set_cookie('_groupid', $groupid, $cookietime);
					param::set_cookie('cookietime', $_cookietime, $cookietime);
					param::set_cookie('_nickname', $nickname, $cookietime);
					param::set_cookie('_from', 'snda');
					$forward = isset($_GET['forward']) && !empty($_GET['forward']) ? $_GET['forward'] : 'index.php?m=member&c=index';
					showmessage(L('login_success').$synloginstr, $forward);
				} else {				
					//弹出绑定注册页面
					$_SESSION = array();
					$_SESSION['connectid'] = $_REQUEST['openid'];
					$_SESSION['from'] = 'qq';
					$connect_username = $_SESSION['last_key']['name'];

					//加载用户模块配置
					$member_setting = getcache('member_setting');
					if(!$member_setting['allowregister']) {
						showmessage(L('deny_register'), 'index.php?m=member&c=index&a=login');
					}
					
					//获取用户siteid
					$siteid = isset($_REQUEST['siteid']) && trim($_REQUEST['siteid']) ? intval($_REQUEST['siteid']) : 1;
					//过滤非当前站点会员模型
					$modellist = getcache('member_model', 'commons');
					foreach($modellist as $k=>$v) {
						if($v['siteid']!=$siteid || $v['disabled']) {
							unset($modellist[$k]);
						}
					}
					if(empty($modellist)) {
						showmessage(L('site_have_no_model').L('deny_register'), HTTP_REFERER);
					}
					
					$modelid = 10; //设定默认值
					if(array_key_exists($modelid, $modellist)) {
						//获取会员模型表单
						require CACHE_MODEL_PATH.'member_form.class.php';
						$member_form = new member_form($modelid);
						$this->db->set_model($modelid);
						$forminfos = $forminfos_arr = $member_form->get();

						//万能字段过滤
						foreach($forminfos as $field=>$info) {
							if($info['isomnipotent']) {
								unset($forminfos[$field]);
							} else {
								if($info['formtype']=='omnipotent') {
									foreach($forminfos_arr as $_fm=>$_fm_value) {
										if($_fm_value['isomnipotent']) {
											$info['form'] = str_replace('{'.$_fm.'}',$_fm_value['form'], $info['form']);
										}
									}
									$forminfos[$field]['form'] = $info['form'];
								}
							}
						}
						
						$formValidator = $member_form->formValidator;
					}	
					include template('member', 'connect');
				}
			} else {
				showmessage(L('login_failure'), 'index.php?m=member&c=index&a=login');
			}
		} else {
			$oauth_callback = APP_PATH.'index.php?m=member&c=index&a=public_qq_login&callback=1';
			$oauth_nonce = md5(SYS_TIME);
			$oauth_signature_method = 'HMAC-SHA1';
			$oauth_timestamp = SYS_TIME;
			$oauth_version = '1.0';

			$url = "https://open.t.qq.com/cgi-bin/request_token?oauth_callback=$oauth_callback&oauth_consumer_key=".QQ_AKEY."&oauth_nonce=$oauth_nonce&oauth_signature=".QQ_SKEY."&oauth_signature_method=HMAC-SHA1&oauth_timestamp=$oauth_timestamp&oauth_version=$oauth_version"; 
			$o = new WeiboOAuth(QQ_AKEY, QQ_SKEY);
			
			$keys = $o->getRequestToken(array('callback'=>$oauth_callback));
			$_SESSION['keys'] = $keys;
			$aurl = $o->getAuthorizeURL($keys['oauth_token'] ,false , $oauth_callback);
			
			include template('member', 'connect_qq');	
		}

	}

	/**
	 * 找回密码
	 * 新增加短信找回方式 
	 */
	public function public_forget_password () {
		
		$email_config = getcache('common', 'commons');
		
		//SMTP MAIL 二种发送模式
 		if($email_config['mail_type'] == '1'){
			if(empty($email_config['mail_user']) || empty($email_config['mail_password'])) {
				showmessage(L('email_config_empty'), HTTP_REFERER);
			}
		}
		$this->_session_start();
		$member_setting = getcache('member_setting');
		if(isset($_POST['dosubmit'])) {
			if ($_SESSION['code'] != strtolower($_POST['code'])) {
				showmessage(L('code_error'), HTTP_REFERER);
			}
			
			$memberinfo = $this->db->get_one(array('email'=>$_POST['email']));
			if(!empty($memberinfo['email'])) {
				$email = $memberinfo['email'];
			} else {
				showmessage(L('email_error'), HTTP_REFERER);
			}
			
			pc_base::load_sys_func('mail');
			$phpcms_auth_key = md5(pc_base::load_config('system', 'auth_key').$this->http_user_agent);

			$code = sys_auth($memberinfo['userid']."\t".SYS_TIME, 'ENCODE', $phpcms_auth_key);

			$url = APP_PATH."index.php?m=member&c=index&a=public_forget_password&code=$code";
			$message = $member_setting['forgetpassword'];
			$message = str_replace(array('{click}','{url}'), array('<a href="'.$url.'">'.L('please_click').'</a>',$url), $message);
			//获取站点名称
			$sitelist = getcache('sitelist', 'commons');
			
			if(isset($sitelist[$memberinfo['siteid']]['name'])) {
				$sitename = $sitelist[$memberinfo['siteid']]['name'];
			} else {
				$sitename = 'ANXIN-EX';
			}
			sendmail($email, L('forgetpassword'), $message, '', '', $sitename);
			showmessage(L('operation_success'), 'index.php?m=member&c=index&a=login');
		} elseif($_GET['code']) {
			$phpcms_auth_key = md5(pc_base::load_config('system', 'auth_key').$this->http_user_agent);
			$hour = date('y-m-d h', SYS_TIME);
			$code = sys_auth(trim($_GET['code']), 'DECODE', $phpcms_auth_key);
			$code = explode("\t", $code);

			if(is_array($code) && is_numeric($code[0]) && date('y-m-d h', SYS_TIME) == date('y-m-d h', $code[1])) {
				$memberinfo = $this->db->get_one(array('userid'=>$code[0]));
				
				if(empty($memberinfo['phpssouid'])) {
					showmessage(L('operation_failure'), 'index.php?m=member&c=index&a=login');
				}
				$updateinfo = array();
				$password = random(8);
				$updateinfo['password'] = password($password, $memberinfo['encrypt']);
				
				$this->db->update($updateinfo, array('userid'=>$code[0]));
				if(pc_base::load_config('system', 'phpsso')) {
					//初始化phpsso
					$this->_init_phpsso();
					$this->client->ps_member_edit('', $email, '', $password, $memberinfo['phpssouid'], $memberinfo['encrypt']);
				}
	
				showmessage(L('operation_success').L('newpassword').':'.$password);

			} else {
				showmessage(L('operation_failure'), 'index.php?m=member&c=index&a=login');
			}

		} else {
			$siteid = isset($_REQUEST['siteid']) && trim($_REQUEST['siteid']) ? intval($_REQUEST['siteid']) : 1;
			$siteinfo = siteinfo($siteid);
			
			include template('member', 'forget_password');
		}
	}
	
	/**
	*通过手机修改密码
	*方式：用户发送HHPWD afei985#821008 至 1065788 ，PHPCMS进行转发到网站运营者指定的回调地址，在回调地址程序进行密码修改等操作,处理成功时给用户发条短信确认。
	*phpcms 以POST方式传递相关数据到回调程序中
	*要求：网站中会员系统，mobile做为主表字段，并且唯一（如已经有手机号码，把号码字段转为主表字段中）
	*/
	
	public function public_changepwd_bymobile(){
		$phone = $_REQUEST['phone'];
		$msg = $_REQUEST['msg'];
		$sms_key = $_REQUEST['sms_passwd'];
		$sms_pid = $_REQUEST['sms_pid'];
		if(empty($phone) || empty($msg) || empty($sms_key) || empty($sms_pid)){
			return false;
		}
		if(!preg_match('/^1([0-9]{9})/',$phone)) {
			return false;
		}
		//判断是否PHPCMS请求的接口
		pc_base::load_app_func('global','sms');
		pc_base::load_app_class('smsapi', 'sms', 0);
		$this->sms_setting_arr = getcache('sms');
		$siteid = $_REQUEST['siteid'] ? $_REQUEST['siteid'] : 1;
		if(!empty($this->sms_setting_arr[$siteid])) {
			$this->sms_setting = $this->sms_setting_arr[$siteid];
		} else {
			$this->sms_setting = array('userid'=>'', 'productid'=>'', 'sms_key'=>'');
		}
		if($sms_key != $this->sms_setting['sms_key'] || $sms_pid != $this->sms_setting['productid']){
			return false;
		}
		//取用户名
		$msg_array = explode("@@",$str);
		$newpwd = $msg_array[1];
		$username = $msg_array[2];
		$array = $this->db->get_one(array('mobile'=>$phone,'username'=>$username));
		if(empty($array)){
			echo 1;
		}else{
			$result = $this->db->update(array('password'=>$newpwd),array('mobile'=>$phone,'username'=>$username));
			if($result){
				//修改成功，发送短信给用户回执
 				//检查短信余额
				if($this->sms_setting['sms_key']) {
					$smsinfo = $this->smsapi->get_smsinfo();
				}
				if($smsinfo['surplus'] < 1) {
 					echo 1;
				}else{
 					$this->smsapi = new smsapi($this->sms_setting['userid'], $this->sms_setting['productid'], $this->sms_setting['sms_key']);
					$content = '你好,'.$username.',你的新密码已经修改成功：'.$newpwd.' ,请妥善保存！';
					$return = $this->smsapi->send_sms($phone, $content, SYS_TIME, CHARSET);
					echo 1;
				}
 			}
		}
	}
	
	/**
	 * 手机短信方式找回密码
	 */
	public function public_forget_password_mobile () {
 		$email_config = getcache('common', 'commons'); 
		$this->_session_start();
		$member_setting = getcache('member_setting');
		if(isset($_POST['dosubmit'])) {
		//处理提交申请，以手机号为准
			if ($_SESSION['code'] != strtolower($_POST['code'])) {
				showmessage(L('code_error'), HTTP_REFERER);
			}
			$mobile = $_POST['mobile'];
			$mobile_verify = intval($_POST['mobile_verify']);
			$password = $_POST['password'];
			$pwdconfirm = $_POST['pwdconfirm'];
			if($password != $pwdconfirm){
				showmessage(L('passwords_not_match'), HTTP_REFERER);
			}
			//验证手机号和传递的验证码是否匹配
			$sms_report_db = pc_base::load_model('sms_report_model');
			$sms_report_array = $sms_report_db->get_one(array("mobile">$mobile,'in_code'=>$mobile_verify));
			if(empty($sms_report_array)){
				showmessage("手机和验证码不对应，请通过正常渠道修改密码！", HTTP_REFERER);
			}
			//更新密码
			$updateinfo = array();
			$updateinfo['password'] = $password;
 			$this->db->update($updateinfo, array('userid'=>$this->memberinfo['userid']));
			if(pc_base::load_config('system', 'phpsso')) {
				//初始化phpsso
				$this->_init_phpsso();
				$res = $this->client->ps_member_edit('', $email, $_POST['info']['password'], $_POST['info']['newpassword'], $this->memberinfo['phpssouid'], $this->memberinfo['encrypt']);
			}
			
			
			
			$memberinfo = $this->db->get_one(array('email'=>$_POST['email']));
			if(!empty($memberinfo['email'])) {
				$email = $memberinfo['email'];
			} else {
				showmessage(L('email_error'), HTTP_REFERER);
			}
			
			pc_base::load_sys_func('mail');
			$phpcms_auth_key = md5(pc_base::load_config('system', 'auth_key').$this->http_user_agent);

			$code = sys_auth($memberinfo['userid']."\t".SYS_TIME, 'ENCODE', $phpcms_auth_key);

			$url = APP_PATH."index.php?m=member&c=index&a=public_forget_password&code=$code";
			$message = $member_setting['forgetpassword'];
			$message = str_replace(array('{click}','{url}'), array('<a href="'.$url.'">'.L('please_click').'</a>',$url), $message);
			//获取站点名称
			$sitelist = getcache('sitelist', 'commons');
			
			if(isset($sitelist[$memberinfo['siteid']]['name'])) {
				$sitename = $sitelist[$memberinfo['siteid']]['name'];
			} else {
				$sitename = 'PHPCMS_V9_MAIL';
			}
			sendmail($email, L('forgetpassword'), $message, '', '', $sitename);
			showmessage(L('operation_success'), 'index.php?m=member&c=index&a=login');
		} else {
			$siteid = isset($_REQUEST['siteid']) && trim($_REQUEST['siteid']) ? intval($_REQUEST['siteid']) : 1;
			$siteinfo = siteinfo($siteid);
 			include template('member', 'forget_password_mobile');
		}
	}
}
?>