<?php 
defined('IN_PHPCMS') or exit('No permission resources.');

pc_base::load_app_class('ajax_foreground','member');
pc_base::load_sys_class('format', '', 0);
pc_base::load_sys_class('form', '', 0);
pc_base::load_app_func('global');

class package extends ajax_foreground {
	private $hid;
	public $current_user;
	function __construct() {
		parent::__construct();
		$this->http_user_agent = $_SERVER['HTTP_USER_AGENT'];

		$this->db = pc_base::load_model('package_model');
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
		$sql = ' 1 ';

		$status = isset($_REQUEST['status']) ? intval($_REQUEST['status']) : 0;
		$_storageid = $this->hid;

		if(isset($_POST['dosubmit'])) {
			
			$orderno = safe_one_string($_POST['orderno']);
			$starttime = strtotime($_POST['begindate']);
			$endtime = strtotime($_POST['enddate']);

			if($status>0){
				$sql.=" and status='$status' ";
			}
			if(!empty($orderno)){
				$sql.=" and (expressno like '%$orderno%' ) ";
			}

			if(!empty($starttime) && !empty($endtime)){
				$sql.=" and addtime>='$starttime'  and addtime<='$endtime' ";
			}
		}

		if($status>0){
			$sql.=" and status='$status' ";
		}
		if($this->hid>0){
			$sql.=" and storageid='$this->hid' ";
		}

		$sql .= " and siteid='$this->siteid' and userid='$this->_userid' ";
		
		$page = max(intval($_GET['page']), 1);
		$datas = $this->db->listinfo($sql, '`addtime` DESC', $page,50);

		$pages= $this->db->pages;
		
		include template('wap', 'package_list');
	}
	
	

	public function add() {
		
		$show_validator = 1;
		if(isset($_POST['dosubmit'])) {
			$_POST['package']['expressid']= $_POST['expressid'];
			$_POST['package']['expressname']= $_POST['expressname'];
			$_POST['package']['expressno']= $_POST['expressno'];
			$_POST['package']['goodsname']= $_POST['goodsname'];
			$_POST['package']['amount']= $_POST['amount'];
			$_POST['package']['price']= $_POST['price'];
			$_POST['package']['weight']= $_POST['weight'];
			$_POST['package']['remark']= $_POST['remark'];

			$_POST['package']['boxcount']= $_POST['boxcount'];
			$_POST['package']['producturl']= $_POST['producturl'];
			$_POST['package']['sellername']= $_POST['sellername'];


			$_POST['package'] = $this->check($_POST['package']);
			
			$r = $this->db->get_one(array('expressno' => trim($_POST['package']['expressno'])));
			if(!$r){
				if($this->db->insert(safe_array_string($_POST['package']))){ 
					if($_POST){
						die('{"status":true,"msg":"'.L('operation_success').'","url":"'.$this->__all__urls[1]['package'].'"}');
					}else{
						header("Location: ".$this->__all__urls[1]['package']);

						//showmessage(L('operation_success'), $this->__all__urls[1]['package']);
					}
				}
			}else{
				if($_POST){
						die('{"status":false,"msg":"邮单号已存在","url":"'.HTTP_REFERER.'"}');
					}else{
				showmessage("邮单号已存在", HTTP_REFERER);
				}
			}
		} else {
			$shippingtypes = $this->getshippingtype();
			$addresslist = $this->getaddresslist();	

			$packagetm = str_replace('"date"','"date inp"',form::date('package[packagetime]','',''));
			include template('wap', 'package_add');
		}

		
	}


	public function edit() {
		
		$show_validator = 1;
		$_GET['aid'] = intval($_GET['aid']);

		$flag = intval($_GET['flag']);

		$aid = $_GET['aid'];
		if(!$_GET['aid']){
			if($_POST){
				die('{"status":false,"msg":"参数出错，操作失败","url":"'.HTTP_REFERER.'"}');
			}else{
				showmessage("参数出错，操作失败");
			}
		}
		if(isset($_POST['dosubmit'])) {
			
			$_POST['package']['expressid']= $_POST['expressid'];
			$_POST['package']['expressname']= $_POST['expressname'];
			$_POST['package']['expressno']= $_POST['expressno'];
			$_POST['package']['goodsname']= $_POST['goodsname'];
			$_POST['package']['amount']= $_POST['amount'];
			$_POST['package']['price']= $_POST['price'];
			$_POST['package']['weight']= $_POST['weight'];
			$_POST['package']['remark']= $_POST['remark'];

			$_POST['package']['boxcount']= $_POST['boxcount'];
			$_POST['package']['producturl']= $_POST['producturl'];
			$_POST['package']['sellername']= $_POST['sellername'];

			$_POST['package'] = $this->check($_POST['package'], 'edit');


			if($this->db->update(safe_array_string($_POST['package']), array('aid' => $_GET['aid']))){
				if($_POST){
					die('{"status":true,"msg":"操作成功","url":"'.$this->__all__urls[1]['package'].'"}');
				}else{
					header("Location: ".$this->__all__urls[1]['package']);
					//showmessage("操作成功", $this->__all__urls[1]['package']);
				}
			}
		} else {
			$where = array('aid' => $_GET['aid']);
			$an_info = $this->db->get_one($where);
			$shippingtypes = $this->getshippingtype();

			$addresslist = $this->getaddresslist();	

			$packagetm = str_replace('"date"','"date inp"',form::date('package[packagetime]',date('Y-m-d',$an_info['packagetime']),''));

			include template('wap', 'package_edit');
		}
		
	}


	/**
	 * 批量删除预约
	 */
	public function delete($aid = 0) {

		if((!isset($_POST['aid']) || empty($_POST['aid'])) && !$aid) {
		
				header("Location:".HTTP_REFERER);
			
		} else {
			if(is_array($_POST['aid']) && !$aid) {
				array_map(array($this, 'delete'), $_POST['aid']);

					header("Location:".HTTP_REFERER);
				
			} elseif($aid) {
				$aid = intval($aid);
				
				$info = $this->db->get_one(array('aid' => $aid,'status'=>1));
				if($info){
					$this->db->delete(array('aid' => $aid));
				}else{
					
						header("Location:".HTTP_REFERER);
					
				}
			}
		}
	}

	/**
	 * ajax检测包裹邮单号是否重复
	 */
	public function public_check_expressno() {
		$_GET['aid']=intval($_GET['aid']);
		
		

		if (!$_GET['expressno']) exit(0);
		
		$expressno=trim($_GET['expressno']);

		if (CHARSET=='gbk') {
			$expressno = iconv('UTF-8', 'GBK', $expressno);
		}
		
		if ($_GET['aid']) {
			$r = $this->db->get_one("aid!='".$_GET['aid']."' AND expressno='$expressno'");
			if ($r) {
				exit('1');
			}else{
				exit('0');
			}
		} 
		$r = $this->db->get_one(array('siteid' => $this->siteid,'userid'=>$this->_userid,'storageid'=>$this->hid, 'expressno' => $expressno), 'aid');
		if($r) {
			exit('1');
		} else {
			exit('0');
		}
	}

	
	/**
	 * 验证表单数据
	 * @param  		array 		$data 表单数组数据
	 * @param  		string 		$a 当表单为添加数据时，自动补上缺失的数据。
	 * @return 		array 		验证后的数据
	 */
	private function check($data = array(), $a = 'add') {
		if($this->hid==0){
			if($_POST){
						die('{"status":false,"msg":"请选择仓库","url":"'.HTTP_REFERER.'"}');
					}else{
			showmessage("请选择仓库");
					}
		}

		if(intval($data['expressid'])==0){
			if($_POST){
						die('{"status":false,"msg":"请选择快递","url":"'.HTTP_REFERER.'"}');
					}else{
			showmessage("请选择快递");
					}
		}

		if($data['expressno']==''){
			if($_POST){
						die('{"status":false,"msg":"邮单号不能为空","url":"'.HTTP_REFERER.'"}');
					}else{
			showmessage("邮单号不能为空");
					}
		}

		if($data['goodsname']==''){
			if($_POST){
						die('{"status":false,"msg":"物品名称不能为空","url":"'.HTTP_REFERER.'"}');
					}else{
			showmessage("物品名称不能为空");
					}
		}

		if($data['amount']==''){
			if($_POST){
						die('{"status":false,"msg":"物品数量不能为空","url":"'.HTTP_REFERER.'"}');
					}else{
			showmessage("物品数量不能为空");
					}
		}

		if($data['weight']==''){
			if($_POST){
						die('{"status":false,"msg":"物品重量不能为空","url":"'.HTTP_REFERER.'"}');
					}else{
			showmessage("物品重量不能为空");
					}
		}

		if($data['price']==''){
			if($_POST){
						die('{"status":false,"msg":"物品价值不能为空","url":"'.HTTP_REFERER.'"}');
					}else{
			showmessage("物品价值不能为空");
					}
		}

		if($data['boxcount']==''){
			if($_POST){
						die('{"status":false,"msg":"箱数不能为空","url":"'.HTTP_REFERER.'"}');
					}else{
			showmessage("箱数不能为空");
					}
		}

		if($data['producturl']==''){
			if($_POST){
						die('{"status":false,"msg":"商品URL不能为空","url":"'.HTTP_REFERER.'"}');
					}else{
			showmessage("商品URL不能为空");
					}
		}

		if($data['sellername']==''){
			if($_POST){
						die('{"status":false,"msg":"商家名称不能为空","url":"'.HTTP_REFERER.'"}');
					}else{
			showmessage("商家名称不能为空");
					}
		}

		$r = $this->db->get_one(array('expressno' => $data['expressno']));
		
		

		$data['truename'] = $this->current_user['lastname'].$this->current_user['firstname'];
		$data['storageid'] = $this->hid;
		$all___warehouse__lists = $this->get_warehouse__lists();
		foreach($all___warehouse__lists as $v){
			if($v['aid']==$this->hid){
				$data['storagename'] = $v['title'];
			}
		}

		if ($a=='add') {
			if (is_array($r) && !empty($r)) {
				if($_POST){
						die('{"status":false,"msg":"邮单号已存在","url":"'.HTTP_REFERER.'"}');
					}else{
				showmessage("邮单号已存在", HTTP_REFERER);
				}
			}
			$data['siteid'] = $this->siteid;
			$data['addtime'] = SYS_TIME;
			$data['username'] = $this->_username;
			$data['userid'] = $this->_userid;
			
			$data['status'] = 1;
			
		} else {
			if ($r['aid'] && ($r['aid']!=$_GET['aid'])) {
				if($_POST){
						die('{"status":false,"msg":"邮单号已存在","url":"'.HTTP_REFERER.'"}');
					}else{
				showmessage("邮单号已存在", HTTP_REFERER);
					}
			}
		}


		return $data;
	}


	/**获取转运类别**/
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
	
	/**获取所有货运公司**/
	private function getallship($id=0){
		$cdb = pc_base::load_model('enum_model');
		$sql = '`siteid`=\''.$this->siteid.'\' AND groupid=1 ';
		if($id>0){
			$sql.=' and aid=\''.$id.'\' ';
			$datas = $cdb->get_one($sql);
			return $datas;
		}else{
			$datas = $cdb->select($sql,'*',10000,'listorder ASC');
			return $datas;
		}
	}

	/**获取所有包裹状态**/
	private function getpackagestatus(){
		$cdb = pc_base::load_model('enum_model');
		$sql = '`siteid`=\''.$this->siteid.'\' AND groupid=48 ';
		$datas = $cdb->select($sql,'*',10000,'listorder ASC');
		return $datas;
	}


	/**判断运单物品里面是否存在该邮单号**/
	private function getexpressno_exists($expressno){
		$cdb = pc_base::load_model('waybill_goods_model');
		$sql = '  expressno=\''.$expressno.'\' and waybillid not in(select waybillid from t_waybill where status=6) ';
		$row = $cdb->get_one($sql);
		if($row)
			return true;
		else
			return false;
	
	}
	
}
?>