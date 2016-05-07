<?php 
defined('IN_PHPCMS') or exit('No permission resources.');
$session_storage = 'session_'.pc_base::load_config('system','session_storage');
pc_base::load_sys_class($session_storage);
pc_base::load_app_class('ajax_foreground','member');
pc_base::load_sys_class('format', '', 0);
pc_base::load_sys_class('form', '', 0);
pc_base::load_app_func('global');

class address extends ajax_foreground {

	function __construct() {
		$this->db = pc_base::load_model('address_model');
		$this->_username = param::get_cookie('_username');
		$this->_userid = intval(param::get_cookie('_userid'));
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
		
		$sql = '';

		$sql = '`siteid`=\''.$this->siteid.'\' and userid=\''.$this->_userid.'\' ';
		
		$page = max(intval($_GET['page']), 1);
		$datas = $this->db->listinfo($sql, '`aid` DESC', $page);
		
		include template('wap', 'address_list');
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

			$data=array();
			$data['addresstype']= intval($_POST['addresstype']);
			$data['truename']= isset($_POST['truename']) ? trim($_POST['truename']) : '';
			$data['country']= isset($_POST['country']) ? trim($_POST['country']) : '';
			$data['province']= isset($_POST['province']) ? trim($_POST['province']) : '';
			$data['city']= isset($_POST['city']) ? trim($_POST['city']) : '';
			$data['address']= isset($_POST['address']) ? trim($_POST['address']) : '';
			$data['company']= isset($_POST['company']) ? trim($_POST['company']) : '';
			$data['mobile']= isset($_POST['mobile']) ? trim($_POST['mobile']) : '';
			$data['email']= isset($_POST['email']) ? trim($_POST['email']) : '';
			$data['idcardtype']= $_POST['idcardtype'];
			$data['idcard']= isset($_POST['idcard']) ? trim($_POST['idcard']) : '';
			$data['postcode']= isset($_POST['postcode']) ? trim($_POST['postcode']) : '';
			

			if($data['truename']==''){
					die('{"status":false,"msg":"姓名不能为空!","url":"'.HTTP_REFERER.'"}');
			}

			if($data['country']==''){
					die('{"status":false,"msg":"国家不能为空!","url":"'.HTTP_REFERER.'"}');
			}

			if($data['province']==''){
					die('{"status":false,"msg":"省/州不能为空!","url":"'.HTTP_REFERER.'"}');
			}

			if($data['city']==''){
					die('{"status":false,"msg":"城市不能为空!","url":"'.HTTP_REFERER.'"}');
			}

			if($data['address']==''){
					die('{"status":false,"msg":"详细地址不能为空!","url":"'.HTTP_REFERER.'"}');
			}

			if($data['mobile']==''){
					die('{"status":false,"msg":"手机号码不能为空!","url":"'.HTTP_REFERER.'"}');
			}

			if($data['idcard']==''){
					die('{"status":false,"msg":"身份证号码不能为空!","url":"'.HTTP_REFERER.'"}');
			}

			if($data['postcode']==''){
					die('{"status":false,"msg":"邮政编码不能为空!","url":"'.HTTP_REFERER.'"}');
			}

			//$_POST['address'] = $this->check($data);

			if($this->db->insert($this->check($data))){
				die('{"status":true,"msg":"'.L('operation_success').'","url":"'.$this->__all__urls[1]['address'].'"}');
			}
		} else {
			
			include template('wap', 'address_add');
		}

		
	}


	public function edit() {
		
		$show_validator = 1;
		$_GET['aid'] = intval($_GET['aid']);
		
		$flag = intval($_GET['flag']);

		if(!$_GET['aid']) showmessage(L('illegal_operation'));
		if(isset($_POST['dosubmit'])) {
			
			$data=array();
			$data['addresstype']= intval($_POST['addresstype']);
			$data['truename']= isset($_POST['truename']) ? trim($_POST['truename']) : '';
			$data['country']= isset($_POST['country']) ? trim($_POST['country']) : '';
			$data['province']= isset($_POST['province']) ? trim($_POST['province']) : '';
			$data['city']= isset($_POST['city']) ? trim($_POST['city']) : '';
			$data['address']= isset($_POST['address']) ? trim($_POST['address']) : '';
			$data['company']= isset($_POST['company']) ? trim($_POST['company']) : '';
			$data['mobile']= isset($_POST['mobile']) ? trim($_POST['mobile']) : '';
			$data['email']= isset($_POST['email']) ? trim($_POST['email']) : '';
			$data['idcardtype']= $_POST['idcardtype'];
			$data['idcard']= isset($_POST['idcard']) ? trim($_POST['idcard']) : '';
			$data['postcode']= isset($_POST['postcode']) ? trim($_POST['postcode']) : '';
			

			if($data['truename']==''){
					die('{"status":false,"msg":"姓名不能为空!","url":"'.HTTP_REFERER.'"}');
			}

			if($data['country']==''){
					die('{"status":false,"msg":"国家不能为空!","url":"'.HTTP_REFERER.'"}');
			}

			if($data['province']==''){
					die('{"status":false,"msg":"省/州不能为空!","url":"'.HTTP_REFERER.'"}');
			}

			if($data['city']==''){
					die('{"status":false,"msg":"城市不能为空!","url":"'.HTTP_REFERER.'"}');
			}

			if($data['address']==''){
					die('{"status":false,"msg":"详细地址不能为空!","url":"'.HTTP_REFERER.'"}');
			}

			if($data['mobile']==''){
					die('{"status":false,"msg":"手机号码不能为空!","url":"'.HTTP_REFERER.'"}');
			}

			if($data['idcard']==''){
					die('{"status":false,"msg":"身份证号码不能为空!","url":"'.HTTP_REFERER.'"}');
			}

			if($data['postcode']==''){
					die('{"status":false,"msg":"邮政编码不能为空!","url":"'.HTTP_REFERER.'"}');
			}


			//$_POST['address'] = $this->check(($data), 'edit');
			if($this->db->update($this->check(($data), 'edit'), array('aid' => intval($_GET['aid'])))){
					die('{"status":true,"msg":"'.L('operation_success').'","url":"'.$this->__all__urls[1]['address'].'"}');
			}
		} else {
			$where = array('aid' => $_GET['aid']);
			$an_info = $this->db->get_one($where);

			include template('wap', 'address_edit');
		}
		
	}


	/**
	 * 批量删除地址簿
	 */
	public function delete($aid = 0) {

		if((!isset($_POST['aid']) || empty($_POST['aid'])) && !$aid) {
				showmessage("参数出错", HTTP_REFERER);
		} else {
			if(is_array($_POST['aid']) && !$aid) {
				array_map(array($this, 'delete'), $_POST['aid']);
				
				header("Location:".HTTP_REFERER);
				//showmessage(L('operation_success'), HTTP_REFERER);
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
		
		$r = $this->db->get_one(array('truename' => $data['truename']));
		
		if ($a=='add') {
			if (is_array($r) && !empty($r)) {
					die('{"status":false,"msg":"地址已存在!","url":"'.HTTP_REFERER.'"}');
			}
			$data['siteid'] = $this->siteid;
			$data['addtime'] = SYS_TIME;
			$data['username'] = $this->_username;
			$data['userid'] = $this->_userid;
			
		} else {
			if ($r['aid'] && ($r['aid']!=$_GET['aid'])) {
					die('{"status":false,"msg":"地址已存在!","url":"'.HTTP_REFERER.'"}');
			}
		}
		return $data;
	}
	
	
}
?>