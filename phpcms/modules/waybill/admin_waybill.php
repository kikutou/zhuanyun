<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin','admin',0);
pc_base::load_sys_class('format', '', 0);
pc_base::load_sys_class('form', '', 0);
pc_base::load_app_func('global');

class admin_waybill extends admin {

	private $db; public $username;
	public function __construct() {
		parent::__construct();
		//if (!module_exists(ROUTE_M)) showmessage(L('module_not_exists'));
		$this->username = param::get_cookie('admin_username');
		$this->userid = param::get_cookie('admin_userid');
		$this->db = pc_base::load_model('waybill_model');
		$this->hid=isset($_GET['hid']) ? intval($_GET['hid']) : 0;
		
		//$this->db->query("ALTER TABLE t_waybill ADD inhousetime int(11) default '0';");
		//$this->db->query("ALTER TABLE t_waybill ADD handletime int(11) default '0';");
		//$this->db->query("ALTER TABLE t_waybill ADD paidtime int(11) default '0';");

	}
	
	public function init() {
		//运单列表
		$sql = '';

		$sql = '`siteid`=\''.$this->get_siteid().'\'';
		
		$page = max(intval($_GET['page']), 1);
		$data = $this->db->listinfo($sql, '`aid` DESC', $page);
		//$big_menu = array('javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=waybill&c=admin_waybill&a=add\', title:\''.L('waybill_add').'\', width:\'600\', height:\'350\', lock:true}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'add\'}).close()});void(0);', L('waybill_add'));
		include $this->admin_tpl('waybill_list');
	}

	public function get_bill_line($waybillid=''){
		$wdb = pc_base::load_model('waybill_model');

		if(empty($waybillid))
		{$waybillid = trim($_GET['waybillid']);}

		$arr =explode("\n", $waybillid);
		$returndata="";
		foreach($arr as $val){
			$v=trim($val);	
			$r = $wdb->get_one("waybillid='$v' AND returncode='' ");
			$returndata.= $v."[".$r['sendname']." → ".$r['takename']."]<br>";
		}
		return $returndata;
	}

	//确认退货
	public function waybill_confirm_return_goods(){
		$aid = intval($_GET['aid']);
		$wdb = pc_base::load_model('waybill_model');
		$show_validator =1;
		$wgdb = pc_base::load_model('waybill_goods_model');
		$pdb = pc_base::load_model('package_model');
		$historydb = pc_base::load_model('waybill_history_model');
		$an_info = $wdb->get_one(array('aid'=>$aid));
		
		$waybillid = trim($an_info['waybillid']);
		$returncode = trim($an_info['returncode']);

		if(isset($_POST['dosubmit'])) {
		
		$_row = $wgdb->get_one(array('waybillid'=>$waybillid,'returncode'=>$returncode));
		
		$packageid = $_row['packageid'];

		//$pdb->update(array('status'=>11),array('aid'=>$packageid,'returncode'=>$returncode));	

		$wgdb->update(array('returnfee'=>floatval($_POST['returnfee']),'returnremark'=>trim($_POST['returnremark']),'returnname'=>$this->username,'returntime'=>SYS_TIME),array('waybillid'=>$waybillid,'returncode'=>$returncode));

		$wdb->update(array('returnedstatus'=>4),array('aid'=>$aid));
	
			$handle=array();
			$handle['siteid'] = $this->get_siteid();
			$handle['username'] = $this->username;
			$handle['userid'] = $this->userid;
			$handle['addtime'] = SYS_TIME;
			$handle['sysbillid'] = trim($an_info['sysbillid']);
			$handle['waybillid'] = trim($an_info['waybillid']);
			$handle['placeid'] = $this->hid;
			$handle['placename'] =$an_info['storagename'];
			$handle['status'] = $an_info['status'];
			$handle['remark'] = $this->getwaybilltatus($handle['status'],$handle['placeid']).$_POST['returnremark'];

					
			$lid = $historydb->insert($handle);
			
			showmessage(L('operation_success'), HTTP_REFERER, '', 'waybill_confirm_return_goods');

		}else{
			
			if($returncode){

				$_row = $wgdb->get_one(array('waybillid'=>$waybillid,'returncode'=>$returncode));
			
				$return_address = trim($_row['return_address']);
				$return_person = trim($_row['return_person']);
				$return_mobile = trim($_row['return_mobile']);
				$return_remarkf = trim($_row['returnremarkf']);
				$return_remark = trim($_row['returnremark']);

			}
		}
		include $this->admin_tpl('waybill_confirm_return_goods');
	}

	//批量转移
	public function move_waybill(){

		$ids =isset($_GET['ids']) ? trim($_GET['ids']) : '';

		if(isset($_POST['dosubmit'])) {
			
			if(!empty($ids)){
				$arr = explode(",",$ids);
				foreach($arr as $v){
					$this->db->update($_POST['waybill'],array('aid'=>$v));
				}
				showmessage(L('operation_success'), HTTP_REFERER, '', 'move_waybill');
			}else{
				showmessage(L('operation_failure'), HTTP_REFERER, '', 'move_waybill');
			}
				
		} else {
		
			include $this->admin_tpl('waybill_move');
		}
	}


	public function waybill_batch_status(){

		$ids =isset($_GET['ids']) ? trim($_GET['ids']) : '';

		if(isset($_POST['dosubmit'])) {
			
			if(!empty($ids)){
				$arr = explode(",",$ids);
				foreach($arr as $v){
					$this->db->update($_POST['waybill'],array('aid'=>intval($v)));
					$row = $this->db->get_one(array('aid'=>$v));
					$val=$sdb->get_one(array('aid'=>$this->hid),'title');
					$_storagename=trim($val['title']);

					//----------------------------------------------------
					//插入处理记录
					$handle=array();
					$handle['siteid'] = $this->get_siteid();
					$handle['username'] = $this->username;
					$handle['userid'] = $this->userid;
					$handle['addtime'] = SYS_TIME;
					$handle['sysbillid'] = trim($row['sysbillid']);
					$handle['waybillid'] = trim($row['waybillid']);
					$handle['placeid'] = $this->hid;
					$handle['placename'] = $_storagename;
					$handle['status'] = $_POST['waybill']['status'];
					$handle['remark'] = $this->getwaybilltatus($handle['status'],$handle['placeid']).$_POST['data']['remark'];

					//$hr=$historydb->get_one(array('sysbillid'=>$handle['sysbillid'],'placeid'=>$handle['placeid'],'status'=>$handle['status']));
					//if(!$hr){
					$lid = $historydb->insert($handle);
					//}
					//发邮件
					$this->sendemailwaybill($handle['sysbillid'],$handle['remark'],intval($_POST['send_email_nofity']));
					//----------------------------------------------------

				}
				showmessage(L('operation_success'), HTTP_REFERER, '', 'waybill_batch_status');
			}else{
				showmessage(L('operation_failure'), HTTP_REFERER, '', 'waybill_batch_status');
			}
				
		} else {
		
			include $this->admin_tpl('waybill_batch_status');
		}
	}
	
	/**
	 * 添加运单
	 */
	public function add() {
		$show_validator = 1;
		if(isset($_POST['dosubmit'])) {
			$_POST['waybill'] = $this->check($_POST['waybill']);
			if($this->db->insert($_POST['waybill'])) showmessage(L('waybillment_successful_added'), HTTP_REFERER, '', 'add');
		} else {
			//获取站点模板信息
			pc_base::load_app_func('global', 'admin');
			
			include $this->admin_tpl('waybill_add');
		}
	}



	//后台编辑运单
	public function sendgoods() {
		
		$session_storage = 'session_'.pc_base::load_config('system','session_storage');
		pc_base::load_sys_class($session_storage);

		$show_validator = 1;
		$_GET['aid'] = intval($_GET['aid']);
		$aid = $_GET['aid'];
		if(!$_GET['aid']) showmessage(L('illegal_operation'));
		if(isset($_POST['dosubmit'])) {
			
			$_POST['waybill'] = $this->check($_POST['waybill'], 'edit');
			if($_SESSION["isrepeat"] !=0){
				//showmessage(L('operaction_repeat'), "/index.php?m=waybill&c=index&a=init&hid=".$this->hid);
				//exit;
			}
			$where = array('aid' => $_GET['aid']);
			$an_info = $this->db->get_one($where);

			$_SESSION["isrepeat"]=1;

			$address_db = pc_base::load_model('address_model');
			if($_POST['waybill']['takeaddressid']==0){//新增收货地址,要保护进入地址里面
				

				$_POST['take_address']['siteid']=$this->get_siteid();
				$_POST['take_address']['addresstype']=1;
				$_POST['take_address']['addtime'] = SYS_TIME;
				$_POST['take_address']['username'] = $this->_username;
				$_POST['take_address']['userid'] = $objUser->userid;
				
				$uaddr = $address_db->get_one(array('addresstype'=>1,'isdefault'=>1,'userid'=>$objUser->userid));
				if(!$uaddr)
					$_POST['take_address']['isdefault']=1;

				$address_db->insert(safe_array_string($_POST['take_address']));
				$address_idd = $address_db->insert_id();
				$_POST['waybill']['takeaddressid'] = $address_idd;
			}

			

			//保存地址
			$sendaddressname='';
			$sendaddr=$address_db->get_one(array('addresstype'=>2));
			if($sendaddr){
					$_POST['waybill']['sendaddressid'] = $sendaddr['aid'];
					$sendaddressname=$sendaddr['truename'].'|'.$sendaddr['mobile'].' '.'|'.$sendaddr['country'].'|'.$sendaddr['province'].'|'.$sendaddr['city'].'|'.$sendaddr['address'].'|'.$sendaddr['postcode'];
			}
			$_POST['waybill']['sendaddressname']=str_replace("&amp;","&",$sendaddressname);

			$takeaddressname='';
			$takeaddr=$address_db->get_one(array('aid'=>$_POST['waybill']['takeaddressid']));
			if($takeaddr){
				$_POST['waybill']['truename'] = $takeaddr['truename'];
				$_POST['waybill']['takename'] = trim($takeaddr['country']);
				$takeaddressname=$takeaddr['truename'].'|'.$takeaddr['mobile'].' '.'|'.$takeaddr['country'].'|'.$takeaddr['province'].'|'.$takeaddr['city'].'|'.$takeaddr['address'].'|'.$takeaddr['postcode'];
			}
			$_POST['waybill']['takeaddressname']=str_replace("&amp;","&",$takeaddressname);
			$addFee=0;
			//插入增值服务-----------------------------------------------------------------
				$srv_value_array = array();
				if(is_array($_POST['service_value'])){
					foreach($_POST['service_value'] as $key=>$v){
							$m_typeid = $_POST['service_value'][$key]['servicetypeid'];
							$m_price = $_POST['service_value'][$key]['price'];
							$m_currencyid = $_POST['service_value'][$key]['currencyid'];
							$m_unitid = $_POST['service_value'][$key]['unitid'];
							$m_type = $_POST['service_value'][$key]['servicetype'];
							
							$m_title = $_POST['service_value'][$key]['title'];
							$m_currencyname = $_POST['service_value'][$key]['currencyname'];
							$m_unitname = $_POST['service_value'][$key]['unitname'];
							if($m_typeid){
							$srv_value_array[$m_typeid]['currencyid'] = $m_currencyid;
							$srv_value_array[$m_typeid]['unitid'] = $m_unitid;
							$srv_value_array[$m_typeid]['servicetype'] = $m_type;
							$srv_value_array[$m_typeid]['servicetypeid'] = $m_typeid;
							$srv_value_array[$m_typeid]['price'] = $m_price;
							
							if($m_typeid!=13){
							$addFee+=$m_price;}

							$srv_value_array[$m_typeid]['title'] = $m_title;
							$srv_value_array[$m_typeid]['currencyname'] = $m_currencyname;
							$srv_value_array[$m_typeid]['unitname'] = $m_unitname;}
					}
				}

			$_POST['waybill']['addvalues'] = array2string($srv_value_array);
			//-------------------------------------------------------------------------------
			//truename 
			//$_POST['waybill']['truename'] = $this->current_user['lastname'].$this->current_user['firstname'];
			//$_POST['waybill']['storagename'] =  $this->storagename;


			//$_POST['waybill']['goodsname'] = trim($_POST['package']['goodsname']);
			//$_POST['waybill']['expressname'] = trim($_POST['package']['expressname']);
			//$_POST['waybill']['expressno'] = trim($_POST['package']['expressno']);
			//$_POST['waybill']['totalamount'] = trim($_POST['package']['amount']);
			//$_POST['waybill']['totalweight'] = trim($_POST['package']['weight']);
			//$_POST['waybill']['totalprice'] = trim($_POST['package']['price']);

			//$_POST['waybill']['bill_long'] = trim($_POST['package']['package_l']);
			//$_POST['waybill']['bill_width'] = trim($_POST['package']['package_w']);
			//$_POST['waybill']['bill_height'] = trim($_POST['package']['package_h']);
		
			if(isset($_GET['sendgoods'])){
				$wayrate = $this->getwayrate();

				$_POST['waybill']['status']=21;// 丢入待处理
				$payedfee = get_ship_fee($_POST['waybill']['totalweight'],get_common_shipline($_POST['waybill']['shippingid']),trim($an_info['takename']));
				
				$_POST['waybill']['payfeeusd']  = $payedfee+$addFee+floatval($_POST['waybill']['otherfee']);

				$_POST['waybill']['allvaluedfee'] = $addFee;
				$_POST['waybill']['wayrate'] = $wayrate;
				$_POST['waybill']['totalship'] = $payedfee;
				$_POST['waybill']['payedfee'] = floatval($_POST['waybill']['taxfee']) + floatval($_POST['waybill']['wayrate']) * floatval($_POST['waybill']['payfeeusd']) * floatval($_POST['waybill']['otherdiscount']);


			}
			$_POST['waybill']['goodsdatas'] = array2string($_POST['waybill_goods']);
			//-------------------------------------------------------------------------------

			$goodsname="";
			foreach($_POST['waybill_goods'] as $goods){
				if(empty($goodsname)){
					$goodsname = $goods['goodsname'];
				}else{
					$goodsname .=" ". $goods['goodsname'];
				}
			}
			
			$_POST['waybill']['goodsname']  = $goodsname;
			
				
			//print_r($_POST['waybill']);exit;

			if($this->db->update(($_POST['waybill']), array('aid' => $_GET['aid']))){
				
				if(isset($_GET['sendgoods'])){
					showmessage(L('waybilld_a'), "/index.php?m=house&c=admin_house&a=house_jihuo_waybill&hid=".$this->hid);
				}else{
					showmessage(L('waybilld_a'), "/index.php?m=house&c=admin_house&a=house_jihuo_waybill&hid=".$this->hid);
				}
			}

		} else {
			$_SESSION["isrepeat"]=0;// 

			$where = array('aid' => $_GET['aid']);
			$an_info = $this->db->get_one($where);
			$shippingtypes = $this->getshippingtype();
			$addresslist = $this->getaddresslist($an_info['userid']);
			$servicelist = $this->getservicelist2();
			


			$storagedb = pc_base::load_model('storage_model');
			$storage_row = $storagedb->get_one(array('aid'=>$an_info['storageid']));
			$udb = pc_base::load_model('member_model');
			$udb->set_model();
			$urow = $udb->get_one(" userid='$an_info[userid]'");
			$mobile = $urow['mobile'];
			$email = $urow['email'];
			$areadb = pc_base::load_model('linkage_model');
			$area_row=$areadb->get_one(array('linkageid'=>$storage_row['area']));
			$areaname= $area_row['name'];

			

			$waybilltm = str_replace('"date"','"date inp"',form::date('waybill[waybilltime]',date('Y-m-d',$an_info['waybilltime']),''));

			include $this->admin_tpl('waybill_sendgoods');
		}
		
	}

	
	//two bill no
	public function getbill_orderno($org_h,$dst_h,$wid=0,$uid=0,$_clientname=''){
		$userid = $uid;
		if(!is_numeric($org_h)){$org_h=100;}
	
		$num = $wid;
		$ordercode = str_replace("T","0",str_replace("H","",$_clientname)).date('md').str_pad($num,6,"0",STR_PAD_LEFT);
			
	
		return $ordercode;
	}


	public function addorder() {
		
		$session_storage = 'session_'.pc_base::load_config('system','session_storage');
		pc_base::load_sys_class($session_storage);
		$udb = pc_base::load_model('member_model');
		$historydb = pc_base::load_model('waybill_history_model');

		$show_validator = 1;
		$_GET['aid'] = intval($_GET['aid']);
		$aid = $_GET['aid'];

		$_POST['waybill']['username'] = trim($_POST['package']['username']);


		if(isset($_POST['dosubmit'])) {


			
			$sysbillid=date('ymdHis',time()).floor(microtime()*1000);
			$waybillid = $sysbillid;
			//--------------end


			$this->hid = intval($_POST['package']['storageid']);

		if(empty($_POST['package']['username']))
		{
			
			showmessage(L('operation_failure')." username  error ");
			exit;
					
		}
		else
		{
			$userData['username'] = trim($_POST['package']['username']);
			/* 插入订单表 *
			$error_no = 0;
			do
			{
					$waybillid = $this->getbill_orderno($org_housecode,$dst_housecode,get__order__counter(),$objUser->userid,trim($_POST['package']['takeflag'])); //获取新订单号
					$error_no = $this->db->count(array('waybillid'=>trim($waybillid)));
			}
			while ($error_no > 0 ); //如果是订单号重复则重新提交数据
			*/
			$waybillid = common____orderno();

			$_POST['waybill']['waybillid'] = $waybillid;
			$_POST['waybill']['sysbillid'] = $waybillid;
			
			$_POST['waybill']['siteid'] =$this->get_siteid();

			$_POST['waybill']['userid'] = trim($_POST['package']['userid']);
			$_POST['waybill']['username'] = trim($_POST['package']['username']);

			$_POST['waybill']['addtime'] = SYS_TIME;
		}

			
		

			

			$_SESSION["isrepeat"]=1;

			$address_db = pc_base::load_model('address_model');
			if($_POST['waybill']['takeaddressid']==0){//新增收货地址,要保护进入地址里面
				
					
				$_POST['take_address']['siteid']=$this->get_siteid();
				$_POST['take_address']['addresstype']=1;
				$_POST['take_address']['addtime'] = SYS_TIME;
				$_POST['take_address']['username'] = $_POST['waybill']['username'];
				$_POST['take_address']['userid'] = $_POST['waybill']['userid'];
				
				

				$address_db->insert(safe_array_string($_POST['take_address']));
				$address_idd = $address_db->insert_id();
				$_POST['waybill']['takeaddressid'] = $address_idd;
			}




			//保存地址
			$sendaddressname='';
			$sendaddr=$address_db->get_one(array('addresstype'=>2));
			if($sendaddr){
				$_POST['waybill']['sendaddressid'] = $sendaddr['aid'];
					$sendaddressname=$sendaddr['truename'].'|'.$sendaddr['mobile'].' '.'|'.$sendaddr['country'].'|'.$sendaddr['province'].'|'.$sendaddr['city'].'|'.$sendaddr['address'].'|'.$sendaddr['postcode'];
			}
			$_POST['waybill']['sendaddressname']=str_replace("&amp;","&",$sendaddressname);

			$takeaddressname='';
			$takeaddr=$address_db->get_one(array('aid'=>$_POST['waybill']['takeaddressid']));
			if($takeaddr){
				$_POST['waybill']['truename'] = $takeaddr['truename'];
				$_POST['waybill']['takename'] = trim($takeaddr['country']);
				$takeaddressname=$takeaddr['truename'].'|'.$takeaddr['mobile'].' '.'|'.$takeaddr['country'].'|'.$takeaddr['province'].'|'.$takeaddr['city'].'|'.$takeaddr['address'].'|'.$takeaddr['postcode'];
			}
			$_POST['waybill']['takeaddressname']=str_replace("&amp;","&",$takeaddressname);
			$addFee=0;
			//插入增值服务-----------------------------------------------------------------
				$srv_value_array = array();
				if(is_array($_POST['service_value'])){
					foreach($_POST['service_value'] as $key=>$v){
							$m_typeid = $_POST['service_value'][$key]['servicetypeid'];
							$m_price = $_POST['service_value'][$key]['price'];
							$m_currencyid = $_POST['service_value'][$key]['currencyid'];
							$m_unitid = $_POST['service_value'][$key]['unitid'];
							$m_type = $_POST['service_value'][$key]['servicetype'];
							
							$m_title = $_POST['service_value'][$key]['title'];
							$m_currencyname = $_POST['service_value'][$key]['currencyname'];
							$m_unitname = $_POST['service_value'][$key]['unitname'];
							if($m_typeid){
							$srv_value_array[$m_typeid]['currencyid'] = $m_currencyid;
							$srv_value_array[$m_typeid]['unitid'] = $m_unitid;
							$srv_value_array[$m_typeid]['servicetype'] = $m_type;
							$srv_value_array[$m_typeid]['servicetypeid'] = $m_typeid;
							$srv_value_array[$m_typeid]['price'] = $m_price;
							
							if($m_typeid!=13){
							$addFee+=$m_price;}

							$srv_value_array[$m_typeid]['title'] = $m_title;
							$srv_value_array[$m_typeid]['currencyname'] = $m_currencyname;
							$srv_value_array[$m_typeid]['unitname'] = $m_unitname;}
					}
				}

			$_POST['waybill']['addvalues'] = array2string($srv_value_array);
			

			$_POST['waybill']['takeflag'] = trim($_POST['package']['takeflag']);
			

			
			$_POST['waybill']['storagename'] =  trim($_POST['package']['storagename']);
			$_POST['waybill']['storageid'] =  trim($_POST['package']['storageid']);

			$_POST['waybill']['takecode'] =  $_POST['package']['takecode'];
		


			$_POST['waybill']['goodsname'] = trim($_POST['package']['goodsname']);
			$_POST['waybill']['expressname'] = trim($_POST['package']['expressname']);
			$_POST['waybill']['expressno'] = trim($_POST['package']['expressno']);
			$_POST['waybill']['totalamount'] = trim($_POST['package']['amount']);
			$_POST['waybill']['totalweight'] = trim($_POST['package']['weight']);
			$_POST['waybill']['totalprice'] = trim($_POST['package']['price']);

			$_POST['waybill']['bill_long'] = trim($_POST['package']['package_l']);
			$_POST['waybill']['bill_width'] = trim($_POST['package']['package_w']);
			$_POST['waybill']['bill_height'] = trim($_POST['package']['package_h']);
		
			
			$wayrate = $this->getwayrate();

			$_POST['waybill']['status']=1;// 
			

			$payedfee = get_ship_fee($_POST['waybill']['totalweight'],get_common_shipline($_POST['waybill']['shippingid']),$_POST['waybill']['takename']);
			
			
		
			
			$_POST['waybill']['payfeeusd']  = $payedfee+$addFee+floatval($_POST['waybill']['otherfee']);

				$_POST['waybill']['allvaluedfee'] = $addFee;
				$_POST['waybill']['wayrate'] = $wayrate;
				$_POST['waybill']['totalship'] = $payedfee;
				$_POST['waybill']['payedfee'] = floatval($_POST['waybill']['wayrate']) * floatval($_POST['waybill']['payfeeusd']) * floatval($_POST['waybill']['otherdiscount']);




			$_POST['waybill']['goodsdatas'] = array2string($_POST['waybill_goods']);
			//-------------------------------------------------------------------------------

			$goodsname="";
			foreach($_POST['waybill_goods'] as $goods){
				if(empty($goodsname)){
					$goodsname = $goods['goodsname'];
				}else{
					$goodsname .=" ". $goods['goodsname'];
				}
			}
			
			$_POST['waybill']['goodsname']  = $goodsname;
				
				
			//print_r($_POST['waybill']);exit;

			if($this->db->insert(($_POST['waybill']))){


				////////////////////////////////////////////////////////////////////////////////////

				$waybillid = $_POST['waybill']['waybillid'];


				//插入处理记录
						$handle=array();

						$handle['siteid'] = $this->get_siteid();
						$handle['username'] = $this->username;
						$handle['userid'] = $this->userid;
						$handle['addtime'] = SYS_TIME;
						$handle['sysbillid'] = trim($_POST['waybill']['sysbillid']);
						$handle['waybillid'] = trim($_POST['waybill']['waybillid']);
						$handle['placeid'] = $_POST['waybill']['storageid'];
						$handle['placename'] = $_POST['waybill']['storagename'];
						$handle['status'] = 1;
						$handle['remark'] = $this->getwaybilltatus($handle['status'],$handle['placeid']).$_POST['data']['remark'];
						
						$historydb->delete(array('waybillid'=>$handle['waybillid'],'status'=>$handle['status']));
						$historydb->insert($handle);

						$handle['status'] = 3;
						$historydb->delete(array('waybillid'=>$handle['waybillid'],'status'=>$handle['status']));
						//$historydb->insert($handle);

/*
						$udb->set_model();
						$urow=$udb->get_one(array('userid' => intval($_POST['waybill']['userid'])));
						$useramount = floatval($urow['amount']);
						$value = floatval($_POST['waybill']['payfeeusd']);

						if($useramount>=$value){
						

						


						$_spend_db = pc_base::load_model('pay_spend_model');
						$spend_row = $_spend_db->get_one("msg='".trim($handle['waybillid'])."' AND value='".trim($value)."'",'id');
						$pay_status =0;
						if($spend_row){$pay_status = 1;}
									
						
						if($pay_status==0){
						//修改账户流水线，财务变动

						
						$payment = L('admin_deduction');//后台扣款
						$spend = pc_base::load_app_class('spend','pay');
						$func = 'amount';
						$res  = $spend->$func($value,$handle['waybillid'],$urow['userid'],$urow['username'],param::get_cookie('userid'),param::get_cookie('admin_username','',1));
						
							
							//$this->db->update(array('status'=>7),array('sysbillid'=>trim($info['sysbillid'])));
							$handle['status']=7;
							$historydb->delete(array('waybillid'=>$handle['waybillid'],'status'=>$handle['status']));
							$historydb->insert($handle);

							//发邮件
							$this->sendemailwaybill($handle['sysbillid'],$handle['remark'],intval($_POST['send_email_nofity']));

							$outresult.="<p>".$waybillid."  <font color=green>扣款成功</font></p>";
						}else{
							
							//发邮件
							$this->sendemailwaybill($handle['sysbillid'],'待付款'.$handle['remark'],intval($_POST['send_email_nofity']));

							$outresult.="<p>".$waybillid." 余额不足 <font color=red>扣款失败</font></p>";
						}
						}else{
							$outresult.="<p>".$waybillid." 余额不足 <font color=red>扣款失败</font></p>";
						}
				/////////////////////////////////////////////////////////////////////////////////////
				*/
				showmessage(L('waybilld_a').$outresult, "/index.php?m=house&c=admin_house&a=house_jihuo_waybill&hid=5");
				
			}

		} else {
			$_SESSION["isrepeat"]=0;// 

			$where = array('aid' => $_GET['aid']);
			$an_info = $this->db->get_one($where);
			$shippingtypes = $this->getshippingtype();
			$addresslist=array();
			//if($an_info){
				$addresslist = $this->getaddresslist($_GET['uid'],trim($_GET['username']));
			//}else{
			//	$addresslist2 = $this->getaddresslist($_GET['uid']);
			//}
			$servicelist = $this->getservicelist2();
			
			

			$storagedb = pc_base::load_model('storage_model');
			$storage_row = $storagedb->get_one(array('aid'=>$an_info['storageid']));
			$udb = pc_base::load_model('member_model');
			$udb->set_model();
			$urow = $udb->get_one(" userid='$an_info[userid]'");
			$mobile = $urow['mobile'];
			$email = $urow['email'];
			$areadb = pc_base::load_model('linkage_model');
			$area_row=$areadb->get_one(array('linkageid'=>$storage_row['area']));
			$areaname= $area_row['name'];

			

			$waybilltm = str_replace('"date"','"date inp"',form::date('waybill[waybilltime]',date('Y-m-d',$an_info['waybilltime']),''));

			include $this->admin_tpl('waybill_addorder');
		}
		
	}

	
	
	/**
	 * 修改运单
	 */
	public function edit() {
		$show_validator = 1;
		$wayrate = $this->getwayrate();
		if(isset($_GET['act'])){
			if($_GET['act']=='gettakelists'){
				$this->gettakelists();
			}else if($_GET['act']=='getshippingmethod'){
				$this->getshippingmethod();
			}
			exit;
		}	

		
		$_GET['aid'] = intval($_GET['aid']);
		if(!$_GET['aid']) showmessage(L('illegal_operation'));
		
		$storagedb = pc_base::load_model('storage_model');
		$allstorage = $storagedb->select("siteid=1 ",'*',10000,'listorder asc');

		if(isset($_POST['dosubmit'])) {
			$_POST['waybill'] = $this->check($_POST['waybill'], 'edit');
			
			if(is_array($_POST['imagesurl_url'])){
			$_POST['waybill']['imagesurl'] = array2string($_POST['imagesurl_url']);
			}

			$_POST['waybill']['declaredatas'] = array2string(array('datas'=>$_POST['waybill_declare'],'total'=>$_POST['declaretotalprice']));

			$_POST['waybill']['goodsdatas'] = array2string($_POST['waybill_goods']);
			$_POST['waybill']['expressno'] = trim($_POST['package']['expressno']);
			
			$_POST['waybill']['truename'] = trim($_POST['package']['truename']);

			if($this->db->update($_POST['waybill'], array('aid' => $_GET['aid']))) showmessage(L('waybilld_a'), HTTP_REFERER, '', 'edit');
		} else {
			$where = array('aid' => $_GET['aid']);
			$an_info = $this->db->get_one($where);

			$waybill_declaredatas = string2array($an_info['declaredatas']);


			include $this->admin_tpl('waybill_edit');
		}
	}

	//取消运单
	public function cancel_waybill_handle(){
		$ids =isset($_GET['ids']) ? trim($_GET['ids']) : '';
		$packdb = pc_base::load_model('package_model');
		$wgdb = pc_base::load_model('waybill_goods_model');
		if(!empty($ids)){
			
			$sysbillid_cancel=$billprefix.date('ymdHis',time()).floor(microtime()*1000);
			$status=6;
			$arr = explode(",",$ids);
			foreach($arr as $v){
				$wrow = $this->db->get_one(array('aid'=>$v));
				$returncode = $wrow['returncode'];

				$this->db->update(array('status'=>6,'returncode'=>$sysbillid_cancel),array('aid'=>$v));
				
				
				$where3="waybillid='$wrow[waybillid]' AND returncode='$returncode'";
							
				$allg = $wgdb->select($where3,"*",1000,"");
							
				$wgdb->update(array('returncode'=>$sysbillid_cancel,'returntime'=>SYS_TIME,'returnname'=>$this->username),$where3);

				foreach($allg as $p){
					$packdb->update(array('issystem'=>0,'status'=>3,'returncode'=>$sysbillid_cancel),array('aid'=>$p['packageid'],'returncode'=>$returncode));
				}
			}
			echo L('operation_success');
			exit;
		}else{
				echo (L('operation_failure'));
				exit;
			}

		
	
	}
	//将卡板合并生成发货单
	public function huodan_build_handle(){
		$kabanno = date('ymdHis',time()).floor(microtime()*1000);
		$sysno = $kabanno;

		$datas=array();
		$show_validator = 1;
		$ids =isset($_GET['ids']) ? trim($_GET['ids']) : '';
		
		$flag =isset($_GET['flag']) ? intval($_GET['flag']) : 0;

		$allpackages=0;
		$allbills="";
		$hddb = pc_base::load_model('waybill_huodan_model');
		
		$kabandb = pc_base::load_model('waybill_kaban_model');

		$sdb = pc_base::load_model('storage_model');
		$wgoods = pc_base::load_model('waybill_goods_model');
		$historydb = pc_base::load_model('waybill_history_model');
		$storageid=0;
		$totalw = 0;
		if(!empty($ids)){
			$sql="aid in($ids)";
			$datas = $this->db->select($sql,'*',10000,'aid ASC');
			$_packages=0;
			$billarr = explode(',',$ids);
			$allpackages=count($billarr);
			$storageid=$this->hid;
			foreach($datas as $v){
				if(empty($allbills)){
					
					$allbills.=trim($v['waybillid']);
				}else
					$allbills.="\n".trim($v['waybillid']);


				$totalw += max($v['volumeweight'],$v['totalweight']);
			}
		}



		if(isset($_POST['dosubmit']) && !empty($ids)) {//处理
				
				$kabannos =isset($_POST['data']['kabanno']) ? trim($_POST['data']['kabanno']) : '';

				$huodanno=trim($_POST['data']['huodanno']);
				
				


				$hdrow=$hddb->get_one(array('huodanno'=>$huodanno));
				if($hdrow){
					showmessage(L('operation_failure').' '.$huodanno.' Exists !', '');
					exit;
				}

				if(empty($kabannos)){
					showmessage(L('operation_failure').' Kaban Number Not Exists !', '');
					exit;
				}

				$kb_array = explode("\n",$kabannos);
				$kb_error=0;
				$kbno="";
				foreach($kb_array as $kb){
					$kbflag = $this->db->get_one(array('waybillid'=>trim($kb),'returncode'=>''));
					if(!$kbflag){
						$kb_error=1;
						$kbno.=$kb.";";
					}
				}

				if($kb_error==1){
					showmessage(L('operation_failure').' '.$kbno.' Kaban Number Not Exists !', '');
					exit;
				}
	
				
					
				$huo_dan=array();
				$huo_dan['siteid'] = $this->get_siteid();
				$huo_dan['username'] = $this->username;
				$huo_dan['userid'] = $this->userid;
				$huo_dan['addtime'] = strtotime($_POST['data']['addtime']);
				$huo_dan['kabanno'] = $_POST['data']['kabanno'];
				$huo_dan['huodanno'] = $huodanno;
				$huo_dan['sysdanno'] = $sysno;
				$huo_dan['remark'] = $_POST['data']['remark'];
				$huo_dan['position'] = $_POST['data']['position'];
				$huo_dan['storageid'] = $_POST['data']['placeid'];

				/*if($_POST['position']=='zhongzhuan')
				{
					$huo_dan['status'] = 12;//  清关中
				}else{*/
					$huo_dan['status'] = 9;// 已出库
				//}
				$huo_dan['storagename'] = trim($_POST['data']['placename']);
				$huo_dan['lastplacename'] = trim($_POST['data']['lastplacename']);
				$huo_dan['shipname'] = trim($_POST['data']['shipname']);
				$huo_dan['weight'] = floatval($_POST['data']['weight']);
				//表示是从已折分卡板里面生成的新发货单
				if($flag==1){
					$huo_dan['unionhuodanno'] = $huodanno;
					$huo_dan['union_status'] = 1;
				}
				//delete from t_waybill_huodan where huodanno='00001111';
			$val=$sdb->get_one(array('aid'=>$this->hid));
			$_storagename=trim($val['title']);
			
			if($hddb->insert($huo_dan)){ //生成货单成功

				$kabannos_array = explode("\n",$kabannos);
				//修改状态为已扫描,即是卡板处理完成
				foreach($kabannos_array as $row){
					$kabanno=trim($row);

					$this->db->update(array('status'=>$huo_dan['status'],'huodanno'=>trim($huodanno)),array('waybillid'=>$kabanno,'returncode'=>''));//修改货单

					//插入处理记录
					$handle=array();
					$handle['siteid'] = $this->get_siteid();
					$handle['username'] = $this->username;
					$handle['userid'] = $this->userid;
					$handle['addtime'] = SYS_TIME;
					$handle['sysbillid'] = $huodanno;
					$handle['waybillid'] = trim($kabanno);
					$handle['placeid'] = $this->hid;
					$handle['placename'] = $_storagename;
					$handle['status'] = $huo_dan['status'];// 已出库
					$handle['remark'] = $this->getwaybilltatus($handle['status'],$handle['placeid']).$_POST['data']['remark'];

					//$hr=$historydb->get_one(array('sysbillid'=>$handle['sysbillid'],'placeid'=>$handle['placeid'],'status'=>$huo_dan['status']));
					//if(!$hr){
					$lid = $historydb->insert($handle);
					//}
					//发邮件
					$this->sendemailwaybill($handle['sysbillid'],$handle['remark'],intval($_POST['send_email_nofity']));
			
				}

				showmessage(L('operation_success'),HTTP_REFERER,'','kaiban_handle');
				exit;
			}else{
				showmessage(L('operation_failure'),HTTP_REFERER,'','kaiban_handle');
				exit;
			}
		
		
		}
		
		include $this->admin_tpl('huodan_build_handle');
	}

	//卡板处理 合并运单
	public function waybill_kaban_handle(){
		$kabanno = date('ymdHis',time()).floor(microtime()*1000);
		$datas=array();
		$show_validator = 1;
		$ids =isset($_GET['ids']) ? trim($_GET['ids']) : '';
		$allpackages=0;
		$allbills="";
		$sdb = pc_base::load_model('storage_model');
		$wgoods = pc_base::load_model('waybill_goods_model');
		$historydb = pc_base::load_model('waybill_history_model');
		$storageid=0;
		$storagename="";
		if(!empty($ids)){
			$sql="aid in($ids)";
			$datas = $this->db->select($sql,'*',10000,'aid ASC');
			$_packages=0;
			$billarr = explode(',',$ids);
			$allpackages=count($billarr);

			foreach($datas as $v){
				if(empty($allbills)){
					$storageid=$v['storageid'];
					$storagename=$v['storagename'];
					$allbills.=$v['waybillid'];
				}else
					$allbills.="\n".$v['waybillid'];
			}
		}



		if(isset($_POST['dosubmit']) && !empty($ids)) {//处理
				
				$waybillids = trim($_POST['data']['waybillid']);

				$heboxs = $_POST['data']['packageno_status'];
				$kabanno =  trim($_POST['data']['kabanno']);
				//if(strpos($heboxs,'X')!==false && $_POST['data']['checkamount']==0){
				$allbills=0;
				if(!empty($waybillids)){
					$arrs = explode("\n",$waybillids);
					foreach($arrs as $v){
						if(trim($v)!="")
							$allbills++;
					}
					
				}

				

				$kabandb = pc_base::load_model('waybill_kaban_model');

				$kbr= $kabandb->get_one(array('kabanno'=>$kabanno));
				if($kbr){
					showmessage(L('operation_failure').' '.$kabanno.' exists!','');
					exit;
				}


			
				$kaban=array();
				$kaban['siteid'] = $this->get_siteid();
				$kaban['username'] = $this->username;
				$kaban['userid'] = $this->userid;
				$kaban['addtime'] = SYS_TIME;
				$kaban['waybillid'] = $_POST['data']['waybillid'];
				$kaban['kabanno'] = $kabanno;
				$kaban['remark'] = $_POST['data']['remark'];
				$kaban['storageid'] = $storageid;
				$kaban['storagename'] = $storagename;
				$kaban['status'] = 10;
				if(empty($heboxs) || empty($waybillids) ){//处理失败
					showmessage(L('operation_failure'),HTTP_REFERER);
					exit;
				}else{

					if($kabandb->insert($kaban) ){ //插入卡板成功

						//修改状态为已扫描,即是卡板处理完成
						foreach($datas as $row){
							$this->db->update(array('status'=>10),array('waybillid'=>$row['waybillid'],'returncode'=>$row['returncode']));
							
							//插入处理记录
							$handle=array();
							$handle['siteid'] = $this->get_siteid();
							$handle['username'] = $this->username;
							$handle['userid'] = $this->userid;
							$handle['addtime'] = SYS_TIME;
							$handle['sysbillid'] = trim($row['sysbillid']);
							$handle['waybillid'] = trim($row['waybillid']);
							$handle['placeid'] = $storageid;
							$handle['placename'] = trim($row['storagename']);
							$handle['status'] = 10;//卡板处理
							$handle['remark'] = $this->getwaybilltatus($handle['status'],$handle['placeid']).$_POST['data']['remark'];
							$lid = $historydb->insert($handle);
						
							//发邮件
							$this->sendemailwaybill($handle['sysbillid'],$handle['remark'],intval($_POST['send_email_nofity']));

						}

						showmessage(L('operation_success'),HTTP_REFERER,'','kaiban_handle');
						exit;
					}
				}
			
		
		
		}
		
		include $this->admin_tpl('waybill_kaban_handle');
	}


	//运单处理操作
	public function waybill_handle(){ //处理后的运单将状态改为 待付款
		$show_validator = 1;

		$an_info=array();	

		if($_GET['f']=='unionbox_check'){
			$this->unionbox_check();
		}

		$ids =isset($_GET['ids']) ? intval($_GET['ids']) : 0;
		$allpackages=0;
		
		$pdb = pc_base::load_model('package_model');
		
		$sdb = pc_base::load_model('storage_model');
		$wgoods = pc_base::load_model('waybill_goods_model');
		$historydb = pc_base::load_model('waybill_history_model');
		$twdb = pc_base::load_model('shipline_model');
		
		$mdb = pc_base::load_model('member_model');
		
		$wayrate = $this->getwayrate();

		

		//获取会员组信息
		$grouplist = getcache('grouplist','member');
		
		$datas=array();
		$tweightfee=0;
		$vweightfee=0;
		$totalweight=0;
		$payedfee=0;
		$otherfee=0;
		$volumefee=0;

		if(!empty($ids)){
			$sql="aid='$ids'";
			$_row = $this->db->get_one($sql);
			$an_info = $_row;
			
			$_packages=0;
			if($_row){
				$usr=$mdb->get_one(array('userid'=>intval($_row['userid'])));
				$shipp = $twdb->get_one(array('aid'=>$_row['shippingid']));
				$tweightfee=$shipp['price'];
				$vweightfee=$shipp['volumeprice'];
				$discounttype=trim($shipp['discount']);
				$waybillid=trim($_row['waybillid']);
				$returncode=trim($_row['returncode']);

				$_packages = $wgoods->count("waybillid='$waybillid' AND returncode='$returncode'");
				$allpackages+=$_packages;

				$packarray=$this->getwaybill_goods($waybillid,$returncode);
				$packageno='';

				$waybill_declaredatas = string2array($_row['declaredatas']);
				//print_r($waybill_declaredatas);exit;
				
				$sinfos  = siteinfo($this->get_siteid());
				$package_overday = intval(substr($sinfos['package_overday'],0,2));
				$package_overday2 = intval(substr($sinfos['package_overday'],2,4));
				$package_overdayfee = floatval($sinfos['package_overdayfee']);


				$days=0;
				$overdayscount=0;
				foreach($packarray as $pack){
					$packageno.=trim($pack['expressno'])."\n";

					$pack_row = $pdb->get_one(array('aid'=>$packarray['packageid']),'operatorid');

					if($pack_row['operatorid']>0){
			
						$overtime = date('Y-m-d H:i:s',strtotime("+$package_overday day",$an_info['operatorid']));
						$currenttime = date('Y-m-d H:i:s',SYS_TIME);

						$days = abs(floor((strtotime($overtime)-strtotime($currenttime))/86400));
						
						$overdayscount += $days;
					}

				}

				$bill_long = $_row['bill_long'];
				$bill_width = $_row['bill_width'];
				$bill_height = $_row['bill_height'];
				$factweight = $_row['factweight'];
				$goodsname = $_row['goodsname'];
				$totalweight=$_row['totalweight'];
				$volumeweight=$_row['volumeweight'];
				$payedfee=$_row['payedfee'];
				//$payfeeusd=$_row['payfeeusd'];
				$otherfee=floatval($_row['otherfee']);
				$volumefee=$_row['volumefee'];
				
				$overdaysfee = $overdayscount * $package_overdayfee;
				
				if(!empty($discounttype))
					$memberdiscount=floatval($discounttype)*100;
				else
					$memberdiscount=$_row['memberdiscount'];

				$otherdiscount=$_row['otherdiscount'];


				
				$allvaluedfee=$_row['allvaluedfee'];
				
				
			}
			
			
		}



		if(isset($_POST['dosubmit']) && !empty($ids)) {//处理
			$sql="aid='$ids'";
			$row = $this->db->get_one($sql);
		//
		$heboxs = $_POST['data']['packageno_status'];

		$packageno = $_POST['data']['packageno'];

		/*if(strpos($heboxs,'√')!==false && $_POST['data']['checkamount']==0){
			
				$m_status=4;
		}else{
				$m_status=5;
		}*/

			$m_status=intval($_POST['wbill']['status']);

			$bill_long = $_POST['wbill']['bill_long'];
			$bill_width = $_POST['wbill']['bill_width'];
			$bill_height = $_POST['wbill']['bill_height'];
			$factweight = $_POST['wbill']['factweight'];
			$totalweight = $_POST['wbill']['totalweight'];
			$totalship = $_POST['wbill']['totalship'];
			$goodsname = $_POST['wbill']['goodsname'];
			$volumeweight = $_POST['wbill']['volumeweight'];
			$allvaluedfee = $_POST['wbill']['allvaluedfee'];

			$memberdiscount = $_POST['wbill']['memberdiscount'];
			$otherdiscount = $_POST['wbill']['otherdiscount'];

			$payedfee = $_POST['wbill']['payedfee'];
			$otherfee = $_POST['wbill']['otherfee'];
			$volumefee = $_POST['wbill']['volumefee'];
			$taxfee = $_POST['wbill']['taxfee'];
			$wayrate = $_POST['wbill']['wayrate'];
			$payfeeusd = $_POST['wbill']['payfeeusd'];

			$picture = $_POST['wbill']['picture'];
			$sendaddressname = $_POST['wbill']['sendaddressname'];
			$takeaddressname = $_POST['wbill']['takeaddressname'];

			$overdayscount = $_POST['wbill']['overdayscount'];
			$overdaysfee = $_POST['wbill']['overdaysfee'];

			if($row){	
			//修改状态为处理中
			
		
			if(is_array($_POST['imagesurl_url'])){
			$_POST['waybill']['imagesurl'] = array2string($_POST['imagesurl_url']);
			$this->db->update(array('imagesurl'=>$_POST['waybill']['imagesurl'] ), array('waybillid'=>trim($row['waybillid']),'returncode'=>$returncode)) ;
			}
			
		
			

			$this->db->update(array('goodsdatas'=>array2string($_POST['waybill_goods']),'status'=>$m_status,'bill_long'=>$bill_long,'bill_width'=>$bill_width,'bill_height'=>$bill_height,'factweight'=>$factweight,'goodsname'=>$goodsname,'totalweight'=>$totalweight,'totalship'=>$totalship,'volumeweight'=>$volumeweight,'allvaluedfee'=>$allvaluedfee,'memberdiscount'=>$memberdiscount,'otherdiscount'=>$otherdiscount,'payedfee'=>$payedfee,'otherfee'=>$otherfee,'volumefee'=>$volumefee,'taxfee'=>$taxfee,'wayrate'=>$wayrate,'payfeeusd'=>$payfeeusd,'picture'=>$picture,'overdayscount'=>$overdayscount,'overdaysfee'=>$overdaysfee),array('waybillid'=>trim($row['waybillid']),'returncode'=>$returncode));
			
			unset($_POST['waybill']['checkamount']);
			unset($_POST['waybill']['imagesurl']);

			$_POST['waybill']['declaredatas'] = array2string(array('datas'=>$_POST['waybill_declare'],'total'=>$_POST['declaretotalprice']));

			$_POST['waybill']['comptime'] = SYS_TIME;

			$this->db->update($_POST['waybill'],array('waybillid'=>trim($row['waybillid']),'returncode'=>$returncode));

			//if($m_status==8){//若是已处理,将所有仓库,$returncode
				
				if(!empty($packageno)){
					$m_statusp = $m_status;
					if($m_statusp==4){$m_statusp=3;}
					$packageno_arr = explode("\n",$packageno);
					foreach($packageno_arr as $pv){
						if(trim($pv))
						$pdb->update(array('status'=>$m_statusp),array('expressno'=>trim($pv),'returncode'=>$returncode));
					}
				}
			//}

			//插入处理记录
			$handle=array();

			$handle['siteid'] = $this->get_siteid();
			$handle['username'] = $this->username;
			$handle['userid'] = $this->userid;
			$handle['addtime'] = SYS_TIME;
			$handle['sysbillid'] = trim($row['sysbillid']);
			$handle['waybillid'] = trim($row['waybillid']);
			$handle['placeid'] = $row['storageid'];
			$handle['placename'] = $row['storagename'];
			$handle['status'] = $m_status;
			$handle['remark'] = $this->getwaybilltatus($handle['status'],$row['storageid']).$_POST['history']['remark'];

			$lid = $historydb->insert($handle);

			//发邮件
			$this->sendemailwaybill($handle['sysbillid'],$handle['remark'],intval($_POST['send_email_nofity']));
			
			}


			if(strpos($heboxs,'√')!==false && $_POST['data']['checkamount']==0){
				showmessage(L('operation_success'), HTTP_REFERER, '', 'handle');
				
			}else{
				
				showmessage(L('operation_failure'), HTTP_REFERER, '', 'handle');
			}

			exit;

		}


		include $this->admin_tpl('waybill_handle');
	}

	//合箱处理

	public function unionbox(){
		$show_validator = 1;
		$oid =isset($_GET['oid']) ? intval($_GET['oid']) : 0;
		$where = array('aid' => $oid);
		$info = $this->db->get_one($where);
		
		$sdb = pc_base::load_model('storage_model');
		$wgoods = pc_base::load_model('waybill_goods_model');
		$sql = 'waybillid=\''.$info['waybillid'].'\'';
		$allpackages = $wgoods->count($sql);

		if(isset($_POST['dosubmit'])) {//处理合箱
			$heboxs = $_POST['data']['packageno_status'];

			if(trim($heboxs)=="" || strpos($heboxs,'X')!==false){
			showmessage(L('unionbox_faild'));
			}else{
			//修改合箱已申核数量
			$this->db->update(array('status'=>9,'checkedamount'=>$_POST['waybill']),array('aid'=>$oid));
			
			//插入处理人备注
			$historydb = pc_base::load_model('waybill_history_model');


			//插入处理记录
			$handle=array();

			$handle['siteid'] = $this->get_siteid();
			$handle['username'] = $this->username;
			$handle['userid'] = $this->userid;
			$handle['addtime'] = SYS_TIME;
			$handle['sysbillid'] = $info['sysbillid'];
			$handle['waybillid'] = $info['waybillid'];
			$handle['placeid'] = $info['storageid'];
			$handle['placename'] = $info['storagename'];
			$handle['status'] = 9;//已出库
			$handle['remark'] = $this->getwaybilltatus($handle['status'],$handle['placeid']).$_POST['data']['remark'];

			$lid = $historydb->insert($handle);

			//发邮件
			$this->sendemailwaybill($handle['sysbillid'],$handle['remark'],intval($_POST['send_email_nofity']));
			
			showmessage(L('unionbox_succeeful'));
			}
			exit;

		}


		include $this->admin_tpl('waybill_unionbox');
	}
	
	//邮单检测
	public function unionbox_check(){
		
		$expressno = isset($_GET['expressno']) ? trim($_GET['expressno']) : '';
		$wgoods = pc_base::load_model('waybill_goods_model');
		$res ='';
		if(!empty($expressno)){
			$arr=explode(',',$expressno);
			$billed=array();
			foreach($arr as $k=>$v){
				$v=trim($v);
				if(!empty($v)){
				$sql = "expressno='$v' AND returncode=''";
				$result = $wgoods->get_one($sql);
				
				if($result && !array_key_exists($v,$billed))
					$res .="√\n";
				else
					$res .="X\n";
				$billed[$v]=$v;
				}
				
			}
		}
		echo $res;
		exit;

	}

	//运单检测
	public function unionbox_check_bill(){
		
		$expressno = isset($_GET['expressno']) ? trim($_GET['expressno']) : '';
		
		$res ='';
		if(!empty($expressno)){
			$arr=explode(',',$expressno);
			$billed=array();
			foreach($arr as $k=>$v){
				$v=trim($v);
				if(!empty($v)){
				$sql = "waybillid='$v' AND returncode=''";
				$result = $this->db->get_one($sql);
				
				if($result && !array_key_exists($v,$billed))
					$res .="√\n";
				else
					$res .="X\n";
				$billed[$v]=$v;
				}
				
			}
		}
		echo $res;
		exit;

	}
	//批量扣款
	public function waybill_morepay(){
		$ids = isset($_GET['ids']) ? trim($_GET['ids']) : '';
		$datass = array(); 
		if(!empty($ids)){
			$datass = $this->db->select("aid in(".trim(str_replace("_",",",$ids)).")");
			$sno_array=explode("_",$ids);
			$sno="";
			foreach($sno_array as $v){
				$row=$this->db->get_one(array('aid'=>trim($v)));
				if($row){
					if(empty($sno))
						$sno=trim($row['waybillid']);
					else
						$sno.="\n".trim($row['waybillid']);
				}

			}
		}


		if(isset($_POST['dosubmit'])) {
			
				$waybills = isset($_POST['data']['waybillid']) ? $_POST['data']['waybillid'] : '';
			
				if(empty($waybills)){
					showmessage(L('operation_failure').' waybill is empty!', '');
					exit;
				}
				
				$udb = pc_base::load_model('member_model');
				$sdb = pc_base::load_model('storage_model');
				$historydb = pc_base::load_model('waybill_history_model');
				$bill_array = explode("\n",$waybills);
				$outresult="";
				foreach($bill_array as $waybillid){

					$where="waybillid='".trim($waybillid)."' and totalweight>0 and payedfee>0 and status!=7";
					$info = $this->db->get_one($where);	
					if($info){
						$udb->set_model();
						$urow=$udb->get_one(array('userid' => intval($info['userid'])));
						$useramount = floatval($urow['amount']);
						$value = floatval($info['payedfee']+$info['taxfee']);

						if($useramount>=$value){
						

						//插入处理记录
						$handle=array();

						$handle['siteid'] = $this->get_siteid();
						$handle['username'] = $this->username;
						$handle['userid'] = $this->userid;
						$handle['addtime'] = SYS_TIME;
						$handle['sysbillid'] = trim($info['sysbillid']);
						$handle['waybillid'] = trim($info['waybillid']);
						$handle['placeid'] = $info['storageid'];
						$handle['placename'] = $info['storagename'];
						$handle['status'] = 7;
						$handle['remark'] = $this->getwaybilltatus($handle['status'],$handle['placeid']).$_POST['data']['remark'];


						$_spend_db = pc_base::load_model('pay_spend_model');
						$spend_row = $_spend_db->get_one("msg='".trim($handle['waybillid'])."' AND value='".trim($value)."'",'id');
						$pay_status =0;
						if($spend_row){$pay_status = 1;}
									
						
						if($pay_status==0){
						//修改账户流水线，财务变动

						
						$payment = L('admin_deduction');//后台扣款
						$spend = pc_base::load_app_class('spend','pay');
						$func = 'amount';
						$res  = $spend->$func($value,$handle['waybillid'],$urow['userid'],$urow['username'],param::get_cookie('userid'),param::get_cookie('admin_username'));
						
							
							$this->db->update(array('status'=>7,'paidtime'=>SYS_TIME),array('sysbillid'=>trim($info['sysbillid'])));
							
							$lid = $historydb->insert($handle);

							//发邮件
							$this->sendemailwaybill($handle['sysbillid'],$handle['remark'],intval($_POST['send_email_nofity']));

							$outresult.="<p>".$waybillid."  <font color=green>扣款成功</font></p>";
						}else{
							
							//发邮件
							$this->sendemailwaybill($handle['sysbillid'],'待付款'.$handle['remark'],intval($_POST['send_email_nofity']));

							$outresult.="<p>".$waybillid." 余额不足 <font color=red>扣款失败</font></p>";
						}
						}else{
							$outresult.="<p>".$waybillid." 余额不足 <font color=red>扣款失败</font></p>";
						}
					
					}else{
						$outresult.="<p>".$waybillid." 未处理 <font color=red>扣款失败</font></p>";
					}

					
				}


				showmessage($outresult, '');
					exit;

		}


	include $this->admin_tpl('waybill_morepay');

	}



	//批量扣款
	public function payone(){
		


	
			
				
				$udb = pc_base::load_model('member_model');
				$sdb = pc_base::load_model('storage_model');
				$historydb = pc_base::load_model('waybill_history_model');
				
				$outresult="";

				$aid =isset($_GET['oid']) ? intval($_GET['oid']) : 0;

					$where="aid='".trim($aid)."'  and status!=7";
					$info = $this->db->get_one($where);	

			

					$where="aid='".trim($aid)."' and totalweight>0 and payedfee>0 and status!=7";
					$info = $this->db->get_one($where);	
					if($info){
						$waybillid = $info['waybillid'];

						$udb->set_model();
						$urow=$udb->get_one(array('userid' => intval($info['userid'])));
						$useramount = floatval($urow['amount']);
						$value = floatval($info['payedfee']);

						if($useramount>=$value){
						

						//插入处理记录
						$handle=array();

						$handle['siteid'] = $this->get_siteid();
						$handle['username'] = $this->username;
						$handle['userid'] = $this->userid;
						$handle['addtime'] = SYS_TIME;
						$handle['sysbillid'] = trim($info['sysbillid']);
						$handle['waybillid'] = trim($info['waybillid']);
						$handle['placeid'] = $info['storageid'];
						$handle['placename'] = $info['storagename'];
						$handle['status'] = 7;
						$handle['remark'] = $this->getwaybilltatus($handle['status'],$handle['placeid']).$_POST['data']['remark'];


						$_spend_db = pc_base::load_model('pay_spend_model');
						$spend_row = $_spend_db->get_one("msg='".trim($handle['waybillid'])."' AND value='".trim($value)."'",'id');
						$pay_status =0;
						if($spend_row){$pay_status = 1;}
									
						
						if($pay_status==0){
						//修改账户流水线，财务变动

						
						$payment = L('admin_deduction');//后台扣款
						$spend = pc_base::load_app_class('spend','pay');
						$func = 'amount';
						$res  = $spend->$func($value,$handle['waybillid'],$urow['userid'],$urow['username'],param::get_cookie('userid'),param::get_cookie('admin_username'));
						
							
							$this->db->update(array('status'=>7),array('sysbillid'=>trim($info['sysbillid'])));
							
							$lid = $historydb->insert($handle);

							//发邮件
							$this->sendemailwaybill($handle['sysbillid'],$handle['remark'],intval($_POST['send_email_nofity']));

							$outresult.="<p>".$waybillid."  <font color=green>扣款成功</font></p>";
						}else{
							
							//发邮件
							$this->sendemailwaybill($handle['sysbillid'],'待付款'.$handle['remark'],intval($_POST['send_email_nofity']));

							$outresult.="<p>".$waybillid." 余额不足 <font color=red>扣款失败</font></p>";
						}
						}else{
							$outresult.="<p>".$waybillid." 余额不足 <font color=red>扣款失败</font></p>";
						}
					
					}else{
						$outresult.="<p>".$waybillid." 未处理 <font color=red>扣款失败</font></p>";
					}

					
				


				showmessage($outresult, '?m=package&c=admin_package&a=nopaypackage&hid='.$this->hid);
					exit;

		




	}

	//签收
	public function waybill_finish(){
		$show_validator = 1;
		
		$ids = isset($_GET['ids']) ? trim($_GET['ids']) : '';

		if(!empty($ids)){
		$sno_array=explode("_",$ids);
		$sno="";
		foreach($sno_array as $v){
			$row=$this->db->get_one(array('aid'=>trim($v)));
			if($row){
				if(empty($sno))
					$sno=trim($row['waybillid']);
				else
					$sno.="\n".trim($row['waybillid']);
			}

		}
		}

		if(isset($_POST['dosubmit'])) {//签收
			
				$sdb = pc_base::load_model('storage_model');
				$historydb = pc_base::load_model('waybill_history_model');

				$waybillid = isset($_POST['data']['waybillid']) ? $_POST['data']['waybillid'] : '';
			
				if(empty($waybillid)){
					showmessage(L('operation_failure').' waybill is empty!', '');
					exit;
				}

				$bill_array = explode("\n",$waybillid);
				$where="";
				foreach($bill_array as $hd){
					if(empty($where))
						$where = "waybillid='".trim($hd)."' ";
					else
						$where .= " OR waybillid='".trim($hd)."' ";
				}

				$where="(".$where.") AND returncode=''";

				if($this->db->update(array('status'=>16),$where)){
					
					//插入处理人备注
					
					$val=$sdb->get_one(array('aid'=>$this->hid));

					foreach($bill_array as $bill){
						$waybillid=trim($bill);
					//插入处理记录
					$handle=array();
					$handle['siteid'] = $this->get_siteid();
					$handle['username'] = $this->username;
					$handle['userid'] = $this->userid;
					$handle['addtime'] = SYS_TIME;
					$handle['sysbillid'] = $this->get_way_sysbillid(trim($waybillid));
					$handle['waybillid'] = trim($waybillid);
					$handle['placeid'] = $this->hid;
					$handle['placename'] = $val['title'];
					$handle['status'] = 16;//中转成功
					$handle['remark'] = $this->getwaybilltatus($handle['status'],$handle['placeid']).$_POST['data']['remark'];

					$lid = $historydb->insert($handle);

					//发邮件
					$this->sendemailwaybill($handle['sysbillid'],$handle['remark'],intval($_POST['send_email_nofity']));
					}

				//'finish'

				showmessage(L('operation_success'),'');
				exit;
			}else{
				showmessage(L('operation_failure'),'');
				exit;
			}
			
		}

		include $this->admin_tpl('waybill_finish');
	}


	//直接转入派发 发货
	public function waybill_fahuo_pifa(){
		$show_validator = 1;
		$fhdb = pc_base::load_model('waybill_fahuo_model');
		$hdb = pc_base::load_model('waybill_huodan_model');
		
		$kbdb = pc_base::load_model('waybill_kaban_model');

		$shipno =isset($_GET['shipno']) ? trim($_GET['shipno']) : '';

		$oid =isset($_GET['oid']) ? intval($_GET['oid']) : 0;
		$where = array('id' => $oid);
		$info = $fhdb->get_one($where);	
		
		
		
		if(isset($_POST['dosubmit'])) {//插入发货记录	
			$shipno=$_POST['data']['shipno'];
			$hrow = $hdb->get_one("huodanno='$shipno'");

			$_POST['data']['siteid'] = $this->get_siteid();
			$_POST['data']['addtime'] = SYS_TIME;
			$_POST['data']['username'] = $this->username;
			$_POST['data']['userid'] = $this->userid;
			$_POST['data']['waybillid'] = $info['kabanno'];
			$_POST['data']['fahuono'] = $shipno;
			$_POST['data']['status']=11;//中转成功
			
			if($fhdb->insert($_POST['data'])){
			
			$hdb->update(array('position'=>$_POST['data']['position'],'handle_status'=>0,'storageid'=>$_POST['data']['placeid'],'storagename'=>$_POST['data']['placename']),array('huodanno'=>$shipno));
			
			//插入处理人备注
			$sdb = pc_base::load_model('storage_model');
			$historydb = pc_base::load_model('waybill_history_model');
			

			$kabanno_array = explode("\n",trim($hrow['kabanno']));

			foreach($kabanno_array as $kb){
				$kb=trim($kb);
				$kbdb->update(array('position'=>$_POST['data']['position'],'handle_status'=>0,'storageid'=>$_POST['data']['placeid'],'storagename'=>$_POST['data']['placename']),array('kabanno'=>$kb));	
				
				$allbk = $kbdb->get_one(array('kabanno'=>$kb));
				$allwbills = explode("\n",$allbk['waybillid']);
				foreach($allwbills as $bill){
					//已发货
					$this->db->update(array('status'=>11,'position'=>$_POST['data']['position'],'handle_status'=>0,'storageid'=>$_POST['data']['placeid']),array('waybillid'=>trim($bill),'returncode'=>$returncode));

					//插入处理记录
					$handle=array();
					$handle['siteid'] = $this->get_siteid();
					$handle['username'] = $this->username;
					$handle['userid'] = $this->userid;
					$handle['addtime'] = SYS_TIME;
					$handle['sysbillid'] = $info['sysbillid'];
					$handle['waybillid'] = trim($bill);
					$handle['placeid'] = $_POST['data']['placeid'];
					$handle['placename'] = $_POST['data']['placename'];
					$handle['status'] = 11;//中转成功
					$handle['remark'] = $this->getwaybilltatus($handle['status'],$handle['placeid']).$_POST['data']['remark'];

					$lid = $historydb->insert($handle);

					//发邮件
						$this->sendemailwaybill($handle['sysbillid'],$handle['remark'],intval($_POST['send_email_nofity']));

				}
	
			}
	
			
			

			showmessage(L('fahuo_succeefuled'), HTTP_REFERER,'','fahuo');
			exit;
			}else{
				showmessage(L('operation_failure'),'','fahuo');
				exit;
			}
		}

		include $this->admin_tpl('waybill_fahuo_pifa');
	}
	

	//批量折分所有卡板的运单出来
	public function waybill_more_openkaban(){
		$show_validator = 1;
		
	
		$kbdb = pc_base::load_model('waybill_kaban_model');
		$sdb = pc_base::load_model('storage_model');
		
		$ids=trim($_GET['ids']);

		$okdan="";
		$nokdan="";
		$allpackages=0;
		$notallpackages=0;
		if(!empty($ids)){
			$hdarr = explode('_',$ids);
			foreach($hdarr as $v){
				$_hdr = $kbdb->get_one(array('id'=>intval($v)));
				if($_hdr['split_status']==1){
					$allpackages+=1;
					if(empty($okdan))
					$okdan=trim($_hdr['kabanno']);
					else
					$okdan.="\n".trim($_hdr['kabanno']);
				}else{
					$notallpackages+=1;
					if(empty($nokdan))
					$nokdan=trim($_hdr['kabanno']);
					else
					$nokdan.="\n".trim($_hdr['kabanno']);
				}
			}
		}

		//折分所有卡板的运单出来
		if(isset($_POST['dosubmit']) ) {
			
			$kabannos = trim($_POST['data']['kabanno']);
			if(!empty($kabannos)){
			
			$send_email_array=array();

			$data_array = explode("\n",$kabannos);
			foreach($data_array as $kbval){
			
			$kabanno=trim($kbval);

			$hrow = $kbdb->get_one(array('kabanno'=>$kabanno));
			$kb_array = explode("\n",trim($hrow['waybillid']));
			
			$val=$sdb->get_one(array('aid'=>$hrow['storageid']));
			$_storagename = $val['title'];
			$_storageid = $hrow['storageid'];

			$chknum=0;
			foreach($kb_array as $v){

				$kbno=trim($v);
				
				//已分折运单
				$wdan=array();
				$wdan['kabanno'] = $kabanno;
				//$wdan['status'] = 12;
				$wdan['split_status'] = 1;//运单已拆开
				$wdan['position'] = 'zhongzhuan';
				$wdan['splittime'] = SYS_TIME;
				$wdan['placeid'] = $_storageid;
				$wdan['placename'] = $_storagename;
				
				if($this->db->update($wdan,array('waybillid'=>$kbno,'returncode'=>$returncode))){ //折分成功记录处理操作
					$chknum++;
					$historydb = pc_base::load_model('waybill_history_model');
					//插入处理记录
					$handle=array();
					$handle['siteid'] = $this->get_siteid();
					$handle['username'] = $this->username;
					$handle['userid'] = $this->userid;
					$handle['addtime'] = SYS_TIME;
					$handle['sysbillid'] = $this->get_way_sysbillid(trim($kbno));
					$handle['waybillid'] = trim($kbno);
					$handle['placeid'] = $_storageid;
					$handle['placename'] = $_storagename;
					$handle['status'] = 12;//进入下一个仓库
					$handle['isshow'] = intval($_POST['isshow']);
					$handle['delete_flag'] = 'kb_'.$kabanno;
					//$handle['remark'] = $this->getwaybilltatus($handle['status'],$handle['placeid']).$_POST['data']['remark'];
					$handle['remark'] = $_POST['data']['remark'];
					//$lid = $historydb->insert($handle);
					$send_email_array[] = $handle;
					
					
				}
				
			}

			if($chknum==count($kb_array)){//更新卡板状态已拆
				$kbdb->update(array('split_status'=>2,'splittime'=>SYS_TIME),array('kabanno'=>$kabanno));
			}
			}

			foreach($send_email_array as  $handle){
			//发邮件
						$this->sendemailwaybill($handle['sysbillid'],$handle['remark'],intval($_POST['send_email_nofity']));
			}

			showmessage(L('operation_success'), HTTP_REFERER,'','openkaban');
			}else{
				showmessage(L('operation_failure'));
				exit;
			}

		}

		include $this->admin_tpl('waybill_more_openkaban');
	}

	//折分所有卡板的运单出来
	public function waybill_openkaban(){
		$show_validator = 1;
		
	
		$kbdb = pc_base::load_model('waybill_kaban_model');
		$sdb = pc_base::load_model('storage_model');
		

		//折分所有卡板的运单出来
		if(isset($_POST['dosubmit']) ) {
			
			$waybillid = trim($_POST['data']['waybillid']);
			if(!empty($waybillid)){
			
			$send_email_array=array();

			$kabanno = trim($_POST['data']['kabanno']);
			$hrow = $kbdb->get_one(array('kabanno'=>$kabanno));
			$kb_array = explode("\n",trim($hrow['waybillid']));
			
		
			$_storagename = $hrow['storagename'];
			$_storageid = $hrow['storageid'];

			$chknum=0;
			foreach($kb_array as $v){

				$kbno=trim($v);
				
				//已分折运单
				$wdan=array();
				$wdan['kabanno'] = $kabanno;
				//$wdan['status'] = 12;
				$wdan['split_status'] = 1;//运单已拆开
				$wdan['position'] = $_POST['position'];
				$wdan['splittime'] = SYS_TIME;
				$wdan['placeid'] = $_storageid;
				$wdan['placename'] = $_storagename;
				
				if($this->db->update($wdan,array('waybillid'=>$kbno,'returncode'=>$returncode))){ //折分成功记录处理操作
					$chknum++;
					$historydb = pc_base::load_model('waybill_history_model');
					//插入处理记录
					$handle=array();
					$handle['siteid'] = $this->get_siteid();
					$handle['username'] = $this->username;
					$handle['userid'] = $this->userid;
					$handle['addtime'] = SYS_TIME;
					$handle['sysbillid'] = $this->get_way_sysbillid(trim($kbno));
					$handle['waybillid'] = trim($kbno);
					$handle['placeid'] = $_storageid;
					$handle['placename'] = $_storagename;
					$handle['status'] = 12;//进入下一个仓库
					$handle['isshow'] = intval($_POST['isshow']);
					$handle['delete_flag'] = 'kb_'.$kabanno;
					//$handle['remark'] = $this->getwaybilltatus($handle['status'],$handle['placeid']).$_POST['data']['remark'];
					$handle['remark'] = $_POST['data']['remark'];
					//$lid = $historydb->insert($handle);
					
					$send_email_array[] = $handle;
					
					
				}
				
			}

			if($chknum==count($kb_array)){//更新卡板状态已拆
				$kbdb->update(array('split_status'=>2,'splittime'=>SYS_TIME),array('kabanno'=>$kabanno));
			}

			foreach($send_email_array as $handle){
			//发邮件
				$this->sendemailwaybill($handle['sysbillid'],$handle['remark'],intval($_POST['send_email_nofity']));
			}

			showmessage(L('operation_success'), HTTP_REFERER,'','openkaban');
			}else{
				showmessage(L('operation_failure'));
				exit;
			}

		}

		include $this->admin_tpl('waybill_openkaban');
	}


	//将卡板转到派发点
	public function kabangoto_paifa(){
		$show_validator = 1;

		$ids = isset($_GET['ids']) ? trim($_GET['ids']) : '';

		$kbdb = pc_base::load_model('waybill_kaban_model');
		$historydb = pc_base::load_model('waybill_history_model');

		$sdb = pc_base::load_model('storage_model');

		$sno_array=explode("_",$ids);
		$sno="";
		foreach($sno_array as $v){
			$row=$kbdb->get_one(array('id'=>trim($v)));
			if($row){
				if(empty($sno))
					$sno=trim($row['kabanno']);
				else
					$sno.="\n".trim($row['kabanno']);
			}

		}

		
		//折分所有卡板的运单出来
		if(isset($_POST['dosubmit']) ) {
			
			$kabanno = trim($_POST['data']['waybillid']);
			
			if(!empty($kabanno)){
			$kb_array = explode("\n",$kabanno);
	
			foreach($kb_array as $v){
				$v=trim($v);
				$wdan=array();
				$wdan['storageid'] = $_POST['data']['placeid'];
				$wdan['storagename'] = $_POST['data']['placename'];
				$wdan['paifa'] = $v;

				$wdan['position'] = trim($_POST['data']['position']);
				$wdan['handle_status']=0;

				if($kbdb->update($wdan,array('kabanno'=>$v))){ //折分成功记录处理操作

					//修改运单转换目的点
					$wbill = $kbdb->get_one(array('kabanno'=>$v));
					if($wbill){
						$wbills = explode("\n",trim($wbill['waybillid']));
						foreach($wbills as $wb){
							$this->db->update(array('position'=>$wdan['position'],'handle_status'=>0,'placeid'=>$_POST['data']['placeid'],'placename'=>$_POST['data']['placename']),array('waybillid'=>trim($wb),'returncode'=>$returncode));	

							$currku = $sdb->get_one(array('aid'=>$this->hid));
					
							//插入处理记录
							$handle=array();
							$handle['siteid'] = $this->get_siteid();
							$handle['username'] = $this->username;
							$handle['userid'] = $this->userid;
							$handle['addtime'] = SYS_TIME;
							$handle['sysbillid'] = $v;
							$handle['waybillid'] = $v;
							$handle['placeid'] = $currku['aid'];
							$handle['placename'] = $currku['title'];
							$handle['status'] = 12;//进入下一个仓库
							$handle['remark'] = $this->getwaybilltatus($handle['status'],$handle['placeid']).$_POST['data']['remark'];

							//$lid = $historydb->insert($handle);

							//发邮件
							$this->sendemailwaybill($handle['sysbillid'],$handle['remark'],intval($_POST['send_email_nofity']));


						}
					}

					
					
				}
				
			}

			showmessage(L('operation_success'), HTTP_REFERER,'','goto_paifa');
			}else{
				showmessage(L('operation_failure'));
				exit;
			}

		}

		include $this->admin_tpl('kabangoto_paifa');
	}
	
	//批量派送方式
	public function waybill_morepaison(){
		$status = intval($_GET['status']);
		$ids = isset($_GET['ids']) ? trim($_GET['ids']) : '';

		$historydb = pc_base::load_model('waybill_history_model');
		$cdb = pc_base::load_model('storage_model');
		
		$sno_array=explode("_",$ids);
		$waybill_array=array();
		$shippingname = array();

		$sno="";
		foreach($sno_array as $v){
			$row=$this->db->get_one(array('aid'=>trim($v)));
			if($row){
				if(empty($sno))
					$sno=trim($row['waybillid']);
				else
					$sno.="\n".trim($row['waybillid']);

				$waybill_array[]=$row;
				$shippingname[trim($row['aid'])] = $row['shippingname'];
			}

		}

		if(isset($_POST['dosubmit']) ) {

			
			if($status==13){ //zc
				$waybillids = trim($_POST['data']['waybillid']);
				if(empty($waybillids)){
					showmessage(L('operation_failure').' waybill is empty!');
					exit;
				}
				
				$outsbill="";
				$wbills = explode("\n",$waybillids);
				$row = $cdb->get_one("aid='".$this->hid."'");
				foreach($wbills as $wb){
					$waybillid=trim($wb);
					
					if($this->db->update(array('status'=>$status,'sendtime'=>strtotime($_POST['data']['sendtime'])),array('waybillid'=>$waybillid))){
					
					
					
					//插入处理记录
					$handle=array();
					$handle['siteid'] = $this->get_siteid();
					$handle['username'] = $this->username;
					$handle['userid'] = $this->userid;
					$handle['addtime'] = SYS_TIME;
					$handle['sysbillid'] = $this->get_way_sysbillid(trim($waybillid));
					$handle['waybillid'] = trim($waybillid);
					$handle['placeid'] = $this->hid;
					$handle['placename'] = $row['title'];
					$handle['status'] = $status;
					$handle['remark'] = $this->getwaybilltatus($handle['status'],$handle['placeid']).$_POST['handle']['remark'];

					$lid = $historydb->insert($handle);

					//发邮件
					$this->sendemailwaybill($handle['sysbillid'],$handle['remark'],intval($_POST['send_email_nofity']));
					
					$outsbill.="<p>".$waybillid." <font color=green>派送自取成功</font></p>";
				
					}else{
						
						$outsbill.="<p>".$waybillid." <font color=red>派送自取失败</font></p>";
					}
				
				}
			showmessage($outsbill);
			exit;
			
			}else{//14 kd
				$outsbill="";
				$waybillids = isset($_POST['data']) ? $_POST['data'] :'';
				if(is_array($waybillids)){
					$row = $cdb->get_one("aid='".$this->hid."'");
					foreach($waybillids as $k=>$val){

						$waybillid=trim($val['waybillid']);
						$expressnumber=trim($val['expressnumber']);
						$expressurl=trim($val['expressurl']);
						$excompany=trim($val['excompany']);
						$factpay=trim($val['factpay']);

						if($this->db->update(array('factpay'=>$factpay,'excompany'=>$excompany,'status'=>$status,'expressnumber'=>$expressnumber,'expressurl'=>$expressurl,'sendtime'=>SYS_TIME),array('waybillid'=>$waybillid,'returncode'=>$returncode))){
						
						
						//插入处理记录
						$handle=array();
						$handle['siteid'] = $this->get_siteid();
						$handle['username'] = $this->username;
						$handle['userid'] = $this->userid;
						$handle['addtime'] = SYS_TIME;
						$handle['sysbillid'] = $this->get_way_sysbillid(trim($waybillid));
						$handle['waybillid'] = trim($waybillid);
						$handle['placeid'] = $this->hid;
						$handle['placename'] = $row['title'];
						$handle['status'] = $status;
						$handle['remark'] = $this->getwaybilltatus($handle['status'],$handle['placeid']).$_POST['handle']['remark'];

						$lid = $historydb->insert($handle);

						//发邮件
						$this->sendemailwaybill($handle['sysbillid'],$handle['remark'],intval($_POST['send_email_nofity']));
						
						$outsbill.="<p>".$waybillid." <font color=green>派送快递成功</font></p>";
					
						}else{
							
							$outsbill.="<p>".$waybillid." <font color=red>派送快递失败</font></p>";
						}
					}
						showmessage($outsbill);
						exit;
				}else{
					showmessage(L('operation_failure').' waybill is empty!');
					exit;
				}

			}

		}


		include $this->admin_tpl('waybill_morepaison'.$status);
		
	}

	//派送方式
	public function waybill_paison(){
		$show_validator = 1;
		
		$returncode = trim($_GET['returncode']);
		$waybillid = trim($_GET['waybillid']);

		$wgdb = pc_base::load_model('waybill_goods_model');
		$pdb = pc_base::load_model('package_model');

		if($returncode){
		$_row = $wgdb->get_one(array('waybillid'=>$waybillid,'returncode'=>$returncode));
		
			$return_address = trim($_row['return_address']);
			$return_person = trim($_row['return_person']);
			$return_mobile = trim($_row['return_mobile']);
			$return_remarkf = trim($_row['returnremarkf']);
			$return_remark = trim($_row['returnremark']);

		}
		if(isset($_POST['dosubmit']) ) {

			

			$waybillid = trim($_POST['data']['waybillid']);
			//$factpay = trim($_POST['data']['factpay']);
			$wdan=array();
			$wdan['status'] = intval($_POST['data']['status']);
			
			$wdan['expressnumber'] = trim($_POST['data']['expressnumber']);
			$wdan['expressurl'] = trim($_POST['data']['expressurl']);
			$wdan['excompany'] = trim($_POST['data']['excompany']);
			$wdan['factpay'] = trim($_POST['data']['factpay']);
			
			if($wdan['status']==13){
				$wdan['sendtime'] = strtotime($_POST['data']['sendtime']);
			}else{
				$wdan['sendtime'] = SYS_TIME;
			}
			
			$r = $this->db->get_one(array('waybillid'=>$waybillid));
			$this->db->update($wdan,array('waybillid'=>$waybillid,'returncode'=>$returncode));	
	
			$pdb->update(array('status'=>11),array('returncode'=>$returncode,'status'=>15));

			//if(){
				
					$historydb = pc_base::load_model('waybill_history_model');
					
					$cdb = pc_base::load_model('storage_model');
					$row = $cdb->get_one("aid='".$this->hid."'");
					
					//插入处理记录
					$handle=array();
					$handle['siteid'] = $this->get_siteid();
					$handle['username'] = $this->username;
					$handle['userid'] = $this->userid;
					$handle['addtime'] = SYS_TIME;
					$handle['sysbillid'] = trim($r['sysbillid']);
					$handle['waybillid'] = trim($waybillid);
					//$handle['factpay'] = trim($factpay);
					$handle['placeid'] = $this->hid;
					$handle['placename'] = $row['title'];
					$handle['status'] = $wdan['status'];//进入下一个仓库
					$handle['remark'] = $this->getwaybilltatus($handle['status'],$handle['placeid']).$_POST['data']['remark'];

					$lid = $historydb->insert($handle);

					//发邮件
						$this->sendemailwaybill($handle['sysbillid'],$handle['remark'],intval($_POST['send_email_nofity']));
				
				showmessage(L('operation_success'), HTTP_REFERER,'','paison');
			/*}else{
				showmessage(L('operation_failure'));
				exit;
			}*/
		}

		include $this->admin_tpl('waybill_paison');
	}

	//将运单转到派发点
	public function waybill_gotopaifa(){
		$show_validator = 1;

		$ids = isset($_GET['ids']) ? trim($_GET['ids']) : '';

		$historydb = pc_base::load_model('waybill_history_model');
		
		if(isset($_POST['dosubmit']) ) {
			
			$waybillid = trim($_POST['data']['waybillid']);
			
			if(!empty($waybillid)){
			$kb_array = explode("\n",$waybillid);
	
			foreach($kb_array as $v){

				$oldbill = $this->db->get_one(array('waybillid'=>$v,'returncode'=>$returncode));
				$_placeid= $oldbill['placeid'];
				$_placename= $oldbill['placename'];
				$v=trim($v);
				$wdan=array();
				$wdan['placeid'] = $_POST['data']['placeid'];
				$wdan['placename'] = $_POST['data']['placename'];
				$wdan['position'] = $_POST['data']['position'];
				$wdan['paifa'] = $v;
				$wdan['handle_status']=0;
				
				if($this->db->update($wdan,array('waybillid'=>trim($v)))){ //折分成功记录处理操作
					
					//插入处理记录
					$handle=array();
					$handle['siteid'] = $this->get_siteid();
					$handle['username'] = $this->username;
					$handle['userid'] = $this->userid;
					$handle['addtime'] = SYS_TIME;
					$handle['sysbillid'] = $this->get_way_sysbillid(trim($v));
					$handle['waybillid'] = trim($v);
					$handle['placeid'] = $_placeid;
					$handle['placename'] = $_placename;
					$handle['status'] = 12;//进入下一个仓库
					$handle['isshow'] = intval($_POST['isshow']);
					$handle['remark'] = $this->getwaybilltatus($handle['status'],$handle['placeid']).$_POST['data']['remark'];

					//$lid = $historydb->insert($handle);

					//发邮件
					$this->sendemailwaybill($handle['sysbillid'],$handle['remark'],intval($_POST['send_email_nofity']));
					
				}
				
			}

			showmessage(L('operation_success'), HTTP_REFERER,'','goto_paifa');
			}else{
				showmessage(L('operation_failure'));
				exit;
			}

		}

		include $this->admin_tpl('waybill_gotopaifa');
	}

		//ajax 返回 waybilllist
	public function waybill_openkaban_getwaybill(){
		$kdb = pc_base::load_model('waybill_kaban_model');
		$kabanno = trim($_GET['kabanno']);
		$row = $kdb->get_one("kabanno='$kabanno' ");
		if($row)
			echo $row['waybillid'];
		else
			echo '';
		exit;
	}
	//ajax 返回 kabanlist
	public function waybill_openhuodan_getkaban(){
		$hdb = pc_base::load_model('waybill_huodan_model');
		$huodanno = trim($_GET['huodanno']);
		
		$row = $hdb->get_one("huodanno='$huodanno' and handle_status=1");
		if($row)
			echo $row['kabanno'];
		else
			echo '';
		exit;
	}

	public function waybill_openhuodan_getbill_line(){
		$hdb = pc_base::load_model('waybill_huodan_model');
		$huodanno = trim($_GET['huodanno']);
		
		$row = $hdb->get_one("huodanno='$huodanno' and handle_status=1");
		if($row)
			echo str_replace("<br>","\n",$this->get_bill_line(trim($row['kabanno'])));
		else
			echo '';
		exit;
	}

	//拆分货单
	public function waybill_openhuodan(){
		$show_validator = 1;
		
		if($_GET['f']=='waybill_openhuodan_getkaban'){
			$this->waybill_openhuodan_getkaban();
		}

		if($_GET['f']=='waybill_openhuodan_getbill_line'){
			$this->waybill_openhuodan_getbill_line();
		}

		if($_GET['f']=='waybill_openkaban_getwaybill'){
			$this->waybill_openkaban_getwaybill();
		}
	
		$kbdb = pc_base::load_model('waybill_kaban_model');

		$hdb = pc_base::load_model('waybill_huodan_model');
		$sdb = pc_base::load_model('storage_model');

		//折分所有货单的卡板出来
		if(isset($_POST['dosubmit']) ) {
			
			$kabanno = trim($_POST['data']['kabanno']);
			if(!empty($kabanno)){
			
			$send_email_array=array();

			$huodanno = trim($_POST['data']['huodanno']);
			$hrow = $hdb->get_one(array('huodanno'=>$huodanno));
			
			$_storagename = $hrow['storagename'];
			$_storageid = $hrow['storageid'];
			$kb_array = explode("\n",trim($hrow['kabanno']));
			$chknum=0;
			foreach($kb_array as $w){
				$wbill = trim($w);

				//已分折运单
				$wdan=array();
				$wdan['kabanno'] = $wbill;
				//$wdan['status'] = 12;
				$wdan['split_status'] = 1;//运单已拆开
				$wdan['position'] = trim($_POST['position']);
				$wdan['splittime'] = SYS_TIME;
				$wdan['placeid'] = $_storageid;
				$wdan['placename'] = $_storagename;
				
				if($this->db->update($wdan,array('waybillid'=>$wbill,'returncode'=>$returncode))){ //折分成功记录处理操作
				$chknum++;
				$historydb = pc_base::load_model('waybill_history_model');
				//插入处理记录
	
				$handle=array();
				$handle['siteid'] = $this->get_siteid();
				$handle['username'] = $this->username;
				$handle['userid'] = $this->userid;
				$handle['addtime'] = SYS_TIME;
				$handle['sysbillid'] = $this->get_way_sysbillid(trim($wbill));
				$handle['waybillid'] = trim($wbill);
				$handle['placeid'] = $_storageid;
				$handle['placename'] = $_storagename;
				$handle['status'] = 17;//进入下一个仓库
				$handle['isshow'] = intval($_POST['isshow']);
				$handle['delete_flag'] = 'hd_'.$huodanno;//进入下一个仓库
						
				//$handle['remark'] =$this->getwaybilltatus($handle['status'],$handle['placeid']).$_POST['data']['remark'];
				$handle['remark'] =$_POST['data']['remark'];

						//$lid = $historydb->insert($handle);
				$send_email_array[] = $handle;
				}
			}

			if($chknum==count($kb_array)){
				$hdb->update(array('split_status'=>1,'splittime'=>SYS_TIME),array('huodanno'=>$huodanno));
			}

			foreach($send_email_array as $handle){
				//发邮件
				//$this->sendemailwaybill($handle['sysbillid'],$handle['remark'],intval($_POST['send_email_nofity']));
			}

			showmessage(L('operation_success'), HTTP_REFERER,'','openhuodan');
			}else{
				showmessage(L('operation_failure'), HTTP_REFERER,'','openhuodan');
				exit;
			}

		}

		include $this->admin_tpl('waybill_openhuodan');
	}

	//批量拆分货单
	public function waybill_more_openhuodan(){
		$show_validator = 1;
		$ids = trim($_GET['ids']);
		
		$kbdb = pc_base::load_model('waybill_kaban_model');

		$hdb = pc_base::load_model('waybill_huodan_model');
		$sdb = pc_base::load_model('storage_model');
		$okdan="";
		$nokdan="";
		$allpackages=0;
		$notallpackages=0;
		if(!empty($ids)){
			$hdarr = explode('_',$ids);
			foreach($hdarr as $v){
				$_hdr = $hdb->get_one(array('id'=>intval($v)));
				if($_hdr['split_status']==0){
					$allpackages+=1;
					if(empty($okdan))
					$okdan=trim($_hdr['huodanno']);
					else
					$okdan.="\n".trim($_hdr['huodanno']);
				}else{
					$notallpackages+=1;
					if(empty($nokdan))
					$nokdan=trim($_hdr['huodanno']);
					else
					$nokdan.="\n".trim($_hdr['huodanno']);
				}
			}
		}


		//折分所有货单的卡板出来
		if(isset($_POST['dosubmit']) ) {
			
			$danno = trim($_POST['data']['huodanno']);
			if(!empty($danno)){
			
			$send_email_array=array();

			$huodannos = trim($_POST['data']['huodanno']);

			$hdno_array = explode("\n",$huodannos);

			foreach($hdno_array as $hdval){
				
			$huodanno=trim($hdval);

			$hrow = $hdb->get_one(array('huodanno'=>$huodanno));
			
			$_storagename = $hrow['storagename'];
			$_storageid = $hrow['storageid'];
			$kb_array = explode("\n",trim($hrow['kabanno']));
			$chknum=0;
			foreach($kb_array as $w){
				$wbill = trim($w);
				
				
				//已分折运单
				$wdan=array();
				$wdan['kabanno'] = $wbill;
				//$wdan['status'] = 12;
				$wdan['split_status'] = 1;//运单已拆开
				$wdan['position'] = trim($_POST['position']);
				$wdan['splittime'] = SYS_TIME;
				$wdan['placeid'] = $_storageid;
				$wdan['placename'] = $_storagename;
				
				if($this->db->update($wdan,array('waybillid'=>$wbill,'returncode'=>$returncode))){ //折分成功记录处理操作
					$chknum++;
					$historydb = pc_base::load_model('waybill_history_model');
					
					$wbill = trim($w);
					$handle=array();
					$handle['siteid'] = $this->get_siteid();
					$handle['username'] = $this->username;
					$handle['userid'] = $this->userid;
					$handle['addtime'] = SYS_TIME;
					$handle['sysbillid'] = $this->get_way_sysbillid(trim($wbill));
					$handle['waybillid'] = trim($wbill);
					$handle['placeid'] = $_storageid;
					$handle['placename'] = $_storagename;
					$handle['status'] = 12;//进入下一个仓库
					$handle['isshow'] = intval($_POST['isshow']);
					$handle['delete_flag'] = 'hd_'.$huodanno;//进入下一个仓库
						
					//$handle['remark'] =$this->getwaybilltatus($handle['status'],$handle['placeid']).$_POST['data']['remark'];
					$handle['remark'] =$_POST['data']['remark'];

					//$lid = $historydb->insert($handle);
					$send_email_array[] = $handle;
				}
			}

			if($chknum==count($kb_array)){
				$hdb->update(array('split_status'=>1,'splittime'=>SYS_TIME),array('huodanno'=>$huodanno));
			}
			}

			foreach($send_email_array as $handle){
				//发邮件
				$this->sendemailwaybill($handle['sysbillid'],$handle['remark'],intval($_POST['send_email_nofity']));
			}
			showmessage(L('operation_success'), HTTP_REFERER,'','openhuodan');
			}else{
				showmessage(L('operation_failure'), HTTP_REFERER,'','openhuodan');
				exit;
			}

		}

		include $this->admin_tpl('waybill_more_openhuodan');
	}

	//合并单号
	public function waybill_union_bill(){
		
		$show_validator = 1;
		
		$hdb = pc_base::load_model('waybill_huodan_model');

		//重新生成新的货单号
		if(isset($_POST['dosubmit']) ) {
			
			//插入处理记录
			$hdan=array();
			$hdan['siteid'] = $this->get_siteid();
			$hdan['username'] = $this->username;
			$hdan['userid'] = $this->userid;
			$hdan['addtime'] = SYS_TIME;
			$hdan['huodanno'] = $_POST['data']['huodanno'];
			$hdan['storageid'] = $_POST['data']['placeid'];
			$hdan['storagename'] = $_POST['data']['placename'];
			$hdan['unionhuodanno'] = $_POST['data']['huodanno'];
			$hdan['remark'] = $_POST['handle']['remark'];
			$hdan['status'] = 12;//进入下一个仓库
			if($hdb->update($hdan,array('huodanno'=>$hdan['huodanno']))){//更新处理操作记录
				
				$historydb = pc_base::load_model('waybill_history_model');
				//插入处理记录
				$handle=array();
				$handle['siteid'] = $this->get_siteid();
				$handle['username'] = $this->username;
				$handle['userid'] = $this->userid;
				$handle['addtime'] = SYS_TIME;
				$handle['sysbillid'] = $hdan['sysdanno'];
				$handle['waybillid'] = $hdan['huodanno'];
				$handle['placeid'] = $hdan['storageid'];
				$handle['placename'] = $hdan['storagename'];
				$handle['status'] = 12;//进入下一个仓库
				$handle['remark'] = $this->getwaybilltatus($handle['status'],$handle['placeid']).$_POST['data']['remark'];

				$lid = $historydb->insert($handle);

				//发邮件
				$this->sendemailwaybill($handle['sysbillid'],$handle['remark'],intval($_POST['send_email_nofity']));

				showmessage(L('operation_success'), HTTP_REFERER,'','union');
				exit;
			}else
			{
				showmessage(L('operation_failure'), HTTP_REFERER,'','union');
				exit;
			}

			
		}

		include $this->admin_tpl('waybill_union_bill');
	}


	//运单号入库检查
	public function waybill_wbruku_check(){
		
		$show_validator = 1;
		$kbdb = pc_base::load_model('waybill_kaban_model');
		$historydb = pc_base::load_model('waybill_history_model');	
		$sdb = pc_base::load_model('storage_model');

		$ids = isset($_GET['oid']) ? trim($_GET['oid']) : '';

		if(!empty($ids)){
		$sno_array=explode("_",$ids);
		$sno="";
		foreach($sno_array as $v){
			$row=$this->db->get_one(array('aid'=>trim($v)));
			if($row){
				if(empty($sno))
					$sno=trim($row['waybillid']);
				else
					$sno.="\n".trim($row['waybillid']);
			}

		}
		}


		if(isset($_POST['dosubmit'])) {//入库检查处理

			$waybillid = isset($_POST['data']['waybillid']) ? $_POST['data']['waybillid'] : '';
			
			if(empty($waybillid)){
				showmessage(L('operation_failure').' waybill is empty!', '');
				exit;
			}

			$bill_array = explode("\n",$waybillid);
			$where="";
			foreach($bill_array as $hd){
				if(empty($where))
					$where = "waybillid='".trim($hd)."' ";
				else
					$where .= " OR waybillid='".trim($hd)."' ";
			}
		
			$val = $sdb->get_one(array('aid'=>$this->hid));//查当前仓库入库

			$_storagename = trim($val['title']);
			
			$where = "(".$where.") AND returncode='' ";
			if($this->db->update(array('status'=>12,'handle_status'=>1,'placeid'=>$this->hid,'placename'=>$_storagename),$where)){//已入库
				foreach($bill_array as $way){
					$waybillid=trim($way);

				//更新运单状态---------------	
				//插入处理人备注
				//插入处理记录
				$handle=array();
				$handle['siteid'] = $this->get_siteid();
				$handle['username'] = $this->username;
				$handle['userid'] = $this->userid;
				$handle['addtime'] = SYS_TIME;
				$handle['sysbillid'] = $this->get_way_sysbillid(trim($waybillid));
				$handle['waybillid'] = trim($waybillid);
				$handle['placeid'] = $this->hid;
				$handle['placename'] = $_storagename;
				$handle['status'] = 12;
				$handle['remark'] = $this->getwaybilltatus($handle['status'],$handle['placeid']).$_POST['handle']['remark'];;
			
				$hr=$historydb->get_one(array('sysbillid'=>$handle['sysbillid'],'placeid'=>$handle['placeid'],'status'=>12));
				if(!$hr){
					$lid = $historydb->insert($handle);
				}
				//发邮件
				$this->sendemailwaybill($handle['sysbillid'],$handle['remark'],intval($_POST['send_email_nofity']));
				}
				showmessage(L('operation_success'), HTTP_REFERER,'','incheck');
				exit;
			}else
			{
				showmessage(L('operation_failure'), HTTP_REFERER,'','incheck');
				exit;
			}
			
		}


		include $this->admin_tpl('waybill_wbruku_check');
	}


	//卡板号入库检查
	public function waybill_kbruku_check(){
		
		$show_validator = 1;
		$kbdb = pc_base::load_model('waybill_kaban_model');
		$historydb = pc_base::load_model('waybill_history_model');	
		$sdb = pc_base::load_model('storage_model');

		$ids = isset($_GET['oid']) ? trim($_GET['oid']) : '';

		if(!empty($ids)){
		$sno_array=explode("_",$ids);
		$sno="";
		foreach($sno_array as $v){
			$row=$kbdb->get_one(array('id'=>trim($v)));
			if($row){
				if(empty($sno))
					$sno=trim($row['kabanno']);
				else
					$sno.="\n".trim($row['kabanno']);
			}

		}
		}

		if(isset($_POST['dosubmit'])) {//入库检查处理

			
			
			$kabanno = isset($_POST['data']['kabanno']) ? $_POST['data']['kabanno'] : '';
			
			if(empty($kabanno)){
				showmessage(L('operation_failure').' kabanno is empty!', '');
				exit;
			}

			$bill_array = explode("\n",$kabanno);
			$where="";
			foreach($bill_array as $hd){
				if(empty($where))
					$where = "kabanno='".trim($hd)."' ";
				else
					$where .= " OR kabanno='".trim($hd)."' ";
			}
			
			
		
		

			$val = $sdb->get_one(array('aid'=>$this->hid));//查当前仓库,入库
			$_storagename = $val['title'];


			if($kbdb->update(array('status'=>12,'handle_status'=>1),$where)){//中转成功	,已入库

				
				
				//更新运单状态---------------
				foreach($bill_array as $kb){

				$info = $kbdb->get_one(array('kabanno'=>trim($kb)));

				$wbill_array = explode("\n",trim($info['waybillid']));
				foreach($wbill_array as $bill){
					$bill = trim($bill);
					$this->db->update(array('status'=>12,'handle_status'=>1,'placeid'=>$this->hid,'placename'=>$_storagename),array('waybillid'=>trim($bill),'returncode'=>$returncode));
						
						
				//插入处理人备注
				//插入处理记录
				$handle=array();
				$handle['siteid'] = $this->get_siteid();
				$handle['username'] = $this->username;
				$handle['userid'] = $this->userid;
				$handle['addtime'] = SYS_TIME;
				$handle['sysbillid'] = $info['sysdanno'];
				$handle['waybillid'] = trim($bill);
				$handle['placeid'] = $this->hid;
				$handle['placename'] = $_storagename;
				$handle['status'] = 12;
				$handle['remark'] = $this->getwaybilltatus($handle['status'],$handle['placeid']).$_POST['handle']['remark'];;

				$lid = $historydb->insert($handle);

				//发邮件
				$this->sendemailwaybill($handle['sysbillid'],$handle['remark'],intval($_POST['send_email_nofity']));
						
						}
					
			

				}
		
				showmessage(L('operation_success'), HTTP_REFERER,'','incheck');
				exit;
			}else
			{
				showmessage(L('operation_failure'), HTTP_REFERER,'','incheck');
				exit;
			}
			
		}


		include $this->admin_tpl('waybill_kbruku_check');
	}

	//入库单号入库检查
	public function waybill_ruku_check(){
		
		$show_validator = 1;
		$fhdb = pc_base::load_model('waybill_fahuo_model');
		$hdb = pc_base::load_model('waybill_huodan_model');
		$kbdb = pc_base::load_model('waybill_kaban_model');
		$historydb = pc_base::load_model('waybill_history_model');
			
		$sdb = pc_base::load_model('storage_model');
		
		$sno_array=explode("_",trim($_GET['no']));
		$sno="";
		foreach($sno_array as $v){
			$row=$hdb->get_one(array('id'=>trim($v)));
			if($row){
				if(empty($sno))
					$sno=trim($row['huodanno']);
				else
					$sno.="\n".trim($row['huodanno']);
			}

		}

		if(isset($_POST['dosubmit'])) {//入库检查处理

			$huodanno = $_POST['data']['huodanno'];
			$weight = $_POST['data']['weight'];

			if(empty($huodanno)){
				showmessage(L('operation_failure').' huodanno is empty!', '');
				exit;
			}
			
			$huodanno_array = explode("\n",$huodanno);
			$where="";
			foreach($huodanno_array as $hd){
				if(empty($where))
					$where = "huodanno='".trim($hd)."' ";
				else
					$where .= " OR huodanno='".trim($hd)."' ";
			}


			$val = $sdb->get_one(array('aid'=>$this->hid));//查当前仓库,入库
			$_storagename = $val['title'];

			$status = 17;
			if($hdb->update(array('status'=>$status,'handle'=>1,'handle_status'=>1,'splittime'=>SYS_TIME),$where)){//中转成功	,已入库

				//$fhdb->update(array('status'=>12),array('fahuono' => $huodanno));//
				
				//更新运单状态---------------
				
				$resultinfo = $hdb->select($where,"kabanno",30,'');
				foreach($resultinfo as $info){
				$kabanno = trim($info['kabanno']);
				$kb_array = explode("\n",$kabanno);
				
				foreach($kb_array as $kb){

					
					
					
					//$kbval = $kbdb->get_one(array('kabanno'=>$kaban_no));
					
					//$kbdb->update(array('handle_status'=>1),array('kabanno'=>$kaban_no));	

					//if($kbval){
						//$wbill_array = explode("\n",trim($kbval['waybillid']));
						//foreach($wbill_array as $bill){
							
						$wbills = trim($kb);
						//$this->db->update(array('status'=>12,'handle_status'=>1,'placeid'=>$this->hid,'placename'=>$_storagename),array('waybillid'=>$wbills));
						
						$this->db->update(array('handle_status'=>1,'status'=>$status,'placeid'=>$this->hid,'placename'=>$_storagename),array('waybillid'=>$wbills,'returncode'=>$returncode));
						
						
						//插入处理人备注
						//插入处理记录
						$handle=array();
						$handle['siteid'] = $this->get_siteid();
						$handle['username'] = $this->username;
						$handle['userid'] = $this->userid;
						$handle['addtime'] = SYS_TIME;
						$handle['sysbillid'] = $this->get_way_sysbillid(trim($wbills));
						$handle['waybillid'] = trim($wbills);
						$handle['placeid'] = $this->hid;
						$handle['placename'] = $_storagename;
						$handle['status'] = $status;
						$handle['remark'] = $this->getwaybilltatus($handle['status'],$handle['placeid']).$_POST['handle']['remark'];;
						//$handle['remark'] = $_POST['handle']['remark'];;
						
						//$hr=$historydb->get_one(array('sysbillid'=>$handle['sysbillid'],'placeid'=>$handle['placeid'],'status'=>12));
						//if(!$hr){
							$lid = $historydb->insert($handle);
						//}
						//发邮件
						$this->sendemailwaybill($handle['sysbillid'],$handle['remark'],intval($_POST['send_email_nofity']));

								
						//}//end wbill_array
					//}//end kbval
				}//----------------------------end kb_array
				}
				
		
				showmessage(L('operation_success'), HTTP_REFERER,'','incheck');
				exit;
			}else
			{
				showmessage(L('operation_failure'), HTTP_REFERER,'','incheck');
				exit;
			}
			
		}


		include $this->admin_tpl('waybill_ruku_check');
	}

	//发货处理
	public function waybill_fahuo(){
		$show_validator = 1;
		
		$hdb = pc_base::load_model('waybill_huodan_model');
		$kbdb = pc_base::load_model('waybill_kaban_model');

		$sdb = pc_base::load_model('storage_model');

		$oid =isset($_GET['oid']) ? intval($_GET['oid']) : 0;
		$where = array('id' => $oid);
		$info = $hdb->get_one($where);	
		
		

		if(isset($_POST['dosubmit'])) {//插入发货记录	

			$_storagename=$info['storagename'];

			$fhdb = pc_base::load_model('waybill_fahuo_model');
			$_POST['data']['siteid'] = $this->get_siteid();
			$_POST['data']['addtime'] = SYS_TIME;
			$_POST['data']['username'] = $this->username;
			$_POST['data']['userid'] = $this->userid;
			$_POST['data']['status']=9;//已出库
			
			$resultid = $fhdb->insert($_POST['data']);


			$hdb->update(array('status'=>9,'storageid'=>$_POST['data']['placeid'],'storagename'=>$_POST['data']['placename'],'position'=>trim($_POST['data']['position']),'handle_status'=>0),$where);//修改货单号状态
			
			//已出库
			
			$kabanno = trim($info['kabanno']);
			$kb_array = explode("\n",$kabanno);
			
			//插入处理人备注
			$sdb = pc_base::load_model('storage_model');
			$historydb = pc_base::load_model('waybill_history_model');


			foreach($kb_array as $kb){
				$kbval = $kbdb->get_one(array('kabanno'=>trim($kb)));
				$kbdb->update(array('position'=>trim($_POST['data']['position']),'handle_status'=>0),array('kabanno'=>trim($kb)));
				if($kbval){
					$wbill_array = explode("\n",trim($kbval['waybillid']));
					foreach($wbill_array as $bill){
						$this->db->update(array('status'=>9,'position'=>trim($_POST['data']['position']),'handle_status'=>0,'placeid'=>$info['storageid'],'placename'=>$_storagename),array('waybillid'=>trim($bill),'returncode'=>$returncode));

						//插入处理记录
				$handle=array();
				$handle['siteid'] = $this->get_siteid();
				$handle['username'] = $this->username;
				$handle['userid'] = $this->userid;
				$handle['addtime'] = SYS_TIME;
				$handle['sysbillid'] = $this->get_way_sysbillid(trim($bill));
				$handle['waybillid'] = trim($bill);
				$handle['placeid'] = $info['storageid'];
				$handle['placename'] = $_storagename;
				$handle['status'] = 9;//已出库
				$handle['remark'] = $this->getwaybilltatus($handle['status'],$handle['placeid']).$_POST['handle']['remark'];

				$lid = $historydb->insert($handle);
				//发邮件
				$this->sendemailwaybill($handle['sysbillid'],$handle['remark'],intval($_POST['send_email_nofity']));

					}
				}
			}
			
			
			
			

			showmessage(L('fahuo_succeefuled'), HTTP_REFERER,'','fahuo');
			exit;
		}

		include $this->admin_tpl('waybill_fahuo');
	}


	//转换地点
	public function waybill_turnplace(){
		$show_validator = 1;
		
		$sdb = pc_base::load_model('storage_model');	

		$hdb = pc_base::load_model('waybill_huodan_model');
		$kbdb = pc_base::load_model('waybill_kaban_model');

		$sno_array=explode("_",trim($_GET['shipno']));
		$sno="";
		foreach($sno_array as $v){
			$row=$hdb->get_one(array('id'=>trim($v)));
			if($row){
				if(empty($sno))
					$sno=trim($row['huodanno']);
				else
					$sno.="\n".trim($row['huodanno']);
			}

		}


		if(isset($_POST['dosubmit'])) {//转换地点	
			//$fhdb = pc_base::load_model('waybill_fahuo_model');

			$huodanno = $_POST['data']['shipno'];
			

			if(empty($huodanno)){
				showmessage(L('operation_failure'), '');
				exit;
			}
			
			$huodanno_array = explode("\n",$huodanno);
			$where="";
			foreach($huodanno_array as $hd){
				if(empty($where))
					$where = "huodanno='".trim($hd)."' ";
				else
					$where .= " OR huodanno='".trim($hd)."' ";
			}

			
			$hd_row=$hdb->select($where,"storagename,storageid,huodanno,kabanno",30,'');
			

			//$resultid = $fhdb->update($_POST['data'],array('shipno'=>$_POST['data']['shipno']));

			//修改货单号状态
			
			$hdan = array();
			$hdan['storageid']=	$_POST['data']['placeid'];
			$hdan['storagename']=	$_POST['data']['placename'];
			$hdan['position']=	trim($_POST['data']['position']);
			$hdan['handle_status']=	0;
			$hdan['status'] = 9;
			
			
			foreach($hd_row as $s_row){

			$_placename= trim($s_row['storagename']);
			$_placeid= $s_row['storageid'];

			$hdb->update($hdan,array('huodanno'=>trim($s_row['huodanno'])));
			
			$kb_array = explode("\n",trim($s_row['kabanno']));
			
			foreach($kb_array as $v){

				$kbv=trim($v);
				$kbdb->update(array('position'=>trim($_POST['data']['position']),'handle_status'=>0),array('kabanno'=>trim($v)));
				
				$wayb = $kbdb->get_one("kabanno='$kbv'");
				$wb_array = explode("\n",trim($wayb['waybillid']));

				foreach($wb_array as $wb){
					$wb=trim($wb);

					$this->db->update(array('position'=>trim($_POST['data']['position']),'handle_status'=>0,'placeid'=>$_placeid,'placename'=>$_placename),array('waybillid'=>$wb,'returncode'=>$returncode));
				}

			}
			}

			showmessage(L('operation_success'), HTTP_REFERER,'','turnplace');
			exit;
		}

		include $this->admin_tpl('waybill_turnplace');
	}

	public function nopay_sendemail(){
		$this->sendemailwaybill($_POST['sysbillid'],$_POST['remark']);
	}

	//Confirm handle waybill
	public function confirm_handle_waybill(){
		
		$sdb = pc_base::load_model('storage_model');

		$historydb = pc_base::load_model('waybill_history_model');
		$show_validator = 1;
		$oid =isset($_GET['oid']) ? intval($_GET['oid']) : 0;
		$where = array('aid' => $oid);
		$info = $this->db->get_one($where);
		
		$udb = pc_base::load_model('member_model');
		$udb->set_model();
		$urow=$udb->get_one(array('userid' => $info['userid']));
		$useramount = $urow['amount'];	

		
		$sinfos  = siteinfo($this->get_siteid());
		$package_overday = intval(substr($sinfos['package_overday'],0,2));
		$package_overday2 = intval(substr($sinfos['package_overday'],2,4));

		$package_overdayfee = floatval($sinfos['package_overdayfee']);

		
		//获取实际重及体积重
		$twdb = pc_base::load_model('shipline_model');
		$shipp = $twdb->get_one(array('aid'=>$info['shippingid']));

			
		$tweightfee=$shipp['price'];
		$vweightfee=$shipp['volumeprice'];
		$discounttype=trim($shipp['discount']);
		$wayrate = $this->getwayrate();

		if(isset($_POST['dosubmit'])) {
			
			

			//插入处理记录
			$handle=array();
			$handle['siteid'] = $this->get_siteid();
			$handle['username'] = $this->username;
			$handle['userid'] = $this->userid;
			$handle['addtime'] = SYS_TIME;
			$handle['sysbillid'] = trim($info['sysbillid']);
			$handle['waybillid'] = trim($info['waybillid']);
			$handle['placeid'] = $info['storageid'];
			$handle['placename'] = $info['storagename'];
			$handle['status'] = 8;
			$handle['remark'] = $this->getwaybilltatus($handle['status'],$handle['placeid']).$_POST['data']['remark'];

			

			//进行扣款操作记录
			if($_POST['data']['remark'])
				unset($_POST['data']['remark']);
			
			$_POST['data']['status']=$handle['status'];//已扣款



			$this->db->update($_POST['data'],array('aid'=>$oid));
				
			$lid = $historydb->insert($handle);

			//发邮件
			$this->sendemailwaybill($handle['sysbillid'],$handle['remark'],intval($_POST['send_email_nofity']));

			showmessage(L('operation_success'), HTTP_REFERER, '', 'confirm_handle');
			exit;
			

		}

		
		$bill_long = $info['bill_long'];
		$bill_width = $info['bill_width'];
		$bill_height = $info['bill_height'];
		$factweight = $info['factweight'];
		$goodsname = $info['goodsname'];
		$totalweight=$info['totalweight'];
		$volumeweight=$info['volumeweight'];
		$payedfee=$info['payedfee'];
		$otherfee=floatval($info['otherfee']);
		$volumefee=$info['volumefee'];

		$overdayscount=$info['overdayscount'];
		$overdaysfee=$info['overdaysfee'];

		


		if(!empty($discounttype))
					$memberdiscount=floatval($discounttype)*100;
				else
					$memberdiscount=$info['memberdiscount'];

				$otherdiscount=$info['otherdiscount'];


				if(floatval($info['allvaluedfee'])>0)
					$allvaluedfee=$info['allvaluedfee'];
				else
					$allvaluedfee=$this->getallvaluedfee(trim($info['waybillid']),$info['returncode']);

		include $this->admin_tpl('waybill_confirm_handle');
	}
	

	
	//运单扣款处理
	public function pay(){
		
		$sdb = pc_base::load_model('storage_model');

		$historydb = pc_base::load_model('waybill_history_model');
		$show_validator = 1;
		$oid =isset($_GET['oid']) ? intval($_GET['oid']) : 0;
		$where = array('aid' => $oid);
		$info = $this->db->get_one($where);
		
		$udb = pc_base::load_model('member_model');
		$udb->set_model();
		$urow=$udb->get_one(array('userid' => $info['userid']));
		$useramount = $urow['amount'];	

		
		$sinfos  = siteinfo($this->get_siteid());
		$package_overday = intval(substr($sinfos['package_overday'],0,2));
		$package_overday2 = intval(substr($sinfos['package_overday'],2,4));

		$package_overdayfee = floatval($sinfos['package_overdayfee']);

		
		//获取实际重及体积重
		$twdb = pc_base::load_model('shipline_model');
		$shipp = $twdb->get_one(array('aid'=>$info['shippingid']));

			
		$tweightfee=$shipp['price'];
		
		$vweightfee=$shipp['volumeprice'];
		$discounttype=trim($shipp['discount']);
		$wayrate = $this->getwayrate();

		if(isset($_POST['dosubmit'])) {
			
			

			//插入处理记录
			$handle=array();
			$handle['siteid'] = $this->get_siteid();
			$handle['username'] = $this->username;
			$handle['userid'] = $this->userid;
			$handle['addtime'] = SYS_TIME;
			$handle['sysbillid'] = trim($info['sysbillid']);
			$handle['waybillid'] = trim($info['waybillid']);
			$handle['placeid'] = $info['storageid'];
			$handle['placename'] = $info['storagename'];
			$handle['status'] = 7;
			$handle['remark'] = $this->getwaybilltatus($handle['status'],$handle['placeid']).$_POST['data']['remark'];

			

			//进行扣款操作记录
			if($_POST['data']['remark'])
				unset($_POST['data']['remark']);
			
			
			$_spend_db = pc_base::load_model('pay_spend_model');
			$spend_row = $_spend_db->get_one("msg='".trim($handle['waybillid'])."' AND value='".trim($value)."'",'id');
			$pay_status =0;
			if($spend_row){$pay_status = 1;}
									
						
			if($pay_status==0){	
			//修改账户流水线，财务变动

			$value = floatval($_POST['data']['payedfee']);
			$payment = L('admin_deduction');//后台扣款
			$spend = pc_base::load_app_class('spend','pay');
			$func = 'amount';
			$res  = $spend->$func($value,$handle['waybillid'],$urow['userid'],$urow['username'],param::get_cookie('userid'),param::get_cookie('admin_username'));
		
				$_POST['data']['status']=7;//已扣款
				$this->db->update(array('status'=>$_POST['data']['status'],'paidtime'=>SYS_TIME),array('aid'=>$oid));
			$historydb->delete(array('status'=>$_POST['data']['status'],'waybillid'=>$handle['waybillid']));
				$lid = $historydb->insert($handle);

				//发邮件
				$this->sendemailwaybill($handle['sysbillid'],$handle['remark'],intval($_POST['send_email_nofity']));

				showmessage(L('pay_money_succeeful'), HTTP_REFERER,'','pay');
				exit;
			}else{
				//$_POST['data']['status']=8;//待付款
				//$this->db->update($_POST['data'],array('aid'=>$oid));

				//发邮件
				$this->sendemailwaybill($handle['sysbillid'],'待付款'.$handle['remark'],intval($_POST['send_email_nofity']));

				showmessage(L('pay_money_faild'), HTTP_REFERER,'','pay');
				exit;
			}

		}

		
		$bill_long = $info['bill_long'];
		$bill_width = $info['bill_width'];
		$bill_height = $info['bill_height'];
		$shippingid=$info['shippingid'];
		$factweight = $info['factweight'];
		$goodsname = $info['goodsname'];
		$totalweight=$info['totalweight'];
		$volumeweight=$info['volumeweight'];
		$totalship=$info['totalship'];
		$payfeeusd=$info['payfeeusd'];
		$payedfee=$info['payedfee'];
		$otherfee=floatval($info['otherfee']);
		$volumefee=$info['volumefee'];

		$overdayscount=$info['overdayscount'];
		$overdaysfee=$info['overdaysfee'];
		$taxfee = $info['taxfee'];

		/*$valued = $this->getservicelist($info['waybillid']);
		
		$valuedtotal=0;
		foreach($valued as $kk=>$v){
			$valuedtotal+=$v['price'];
		}*/


		if(!empty($discounttype))
					$memberdiscount=floatval($discounttype)*100;
				else
					$memberdiscount=$info['memberdiscount'];

				$otherdiscount=$info['otherdiscount'];


				
					$allvaluedfee=$info['allvaluedfee'];
				

		include $this->admin_tpl('waybill_pay');
	}
	
	/**
	 * ajax检测运单标题是否重复
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
	 * 批量修改运单状态 使其成为审核、未审核状态
	 */
	public function public_approval($aid = 0) {
		if((!isset($_POST['aid']) || empty($_POST['aid'])) && !$aid) {
			showmessage(L('illegal_operation'));
		} else {
			if(is_array($_POST['aid']) && !$aid) {
				array_map(array($this, 'public_approval'), $_POST['aid']);
				showmessage(L('waybill_passed'), HTTP_REFERER);
			} elseif($aid) {
				$aid = intval($aid);
				
				$this->db->update(array('isdefault' => $_GET['isdefault']), array('aid' => $aid));
				return true;
			}
		}
	}
	
	/**
	 * 批量删除运单
	 */
	public function delete($aid = 0) {
		if((!isset($_POST['aid']) || empty($_POST['aid'])) && !$aid) {
			showmessage(L('illegal_operation'));
		} else {
			if(is_array($_POST['aid']) && !$aid) {
				array_map(array($this, 'delete'), $_POST['aid']);
				showmessage(L('waybill_deleted'), HTTP_REFERER);
			} elseif($aid) {
				$aid = intval($aid);
				
				
				$waybillid="";
				$row = $this->db->get_one(array('aid' => $aid));
				
				if($row)
				{
					
					$waybillid=trim($row['waybillid']);	
					$returncode=trim($row['returncode']);	
					
					$this->db->delete(array('aid' => $aid));
					
					//删除子关联数据
					$gdb = pc_base::load_model('waybill_goods_model');//运单物品
					$srvdb = pc_base::load_model('waybill_serviceitem_model');//运单服务项
					$historydb = pc_base::load_model('waybill_history_model');//历史记录
					/*$packdb = pc_base::load_model('package_model');//包裹

					$packall = $gdb->select(array('waybillid' => $waybillid),"*",1000,"");
					//foreach($packall as $pack){
						//$expressno= trim($pack['expressno']);
						//$packdb->delete(array('expressno' => $expressno));
					//}*/
					$gdb->delete(array('waybillid' => $waybillid,'returncode'=>$returncode));
					$srvdb->delete(array('waybillid' => $waybillid,'returncode'=>$returncode));
					$historydb->delete(array('waybillid' => $waybillid,'returncode'=>$returncode));

					
				}
				//--------------------


			}
		}
	}
	

	/**
	 * 批量删除发货运单
	 */
	public function fahuo_delete($aid = 0) {
		
		$fhdb = pc_base::load_model('waybill_fahuo_model');

		if((!isset($_POST['aid']) || empty($_POST['aid'])) && !$aid) {
			showmessage(L('illegal_operation'));
		} else {
			if(is_array($_POST['aid']) && !$aid) {
				array_map(array($this, 'delete'), $_POST['aid']);
				showmessage(L('waybill_deleted'), HTTP_REFERER);
			} elseif($aid) {
				$aid = intval($aid);
				$fhdb->fahuo_delete(array('id' => $aid));
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
		//if($data['title']=='') showmessage(L('title_cannot_empty'));
	
		///$r = $this->db->get_one(array('title' => $data['title']));
		
		if ($a=='add') {
			/*if (is_array($r) && !empty($r)) {
				showmessage(L('waybill_exist'), HTTP_REFERER);
			}*/
			$data['siteid'] = $this->get_siteid();
			$data['addtime'] = SYS_TIME;
			$data['username'] = $this->username;
			if(intval($data['userid']==0))
				$data['userid'] = $this->userid;
			
		} else {
			/*if ($r['aid'] && ($r['aid']!=$_GET['aid'])) {
				showmessage(L('waybill_exist'), HTTP_REFERER);
			}*/
		}
		return $data;
	}

	/**获取增值服务**/
	private function getservicelist($ids){
		$cdb = pc_base::load_model('waybill_serviceitem_model');
		$scdb = pc_base::load_model('service_model');
		$sql = '  waybillid=\'$ids\'';
		$datas = $cdb->select($sql,'*',10000,'id desc');

		$arr=array();
		
		foreach($datas as $row){
			$r = $scdb->get_one(array('aid'=>$row['servicetypeid']));
			$row['servicename'] = $r['title'];
			$arr[]=$row;	
		}

		return $arr;
	}


	//根据ID返回货币名称
	private function getcurrencyname($cid){
		return $this->getcurrency($cid);
	}
	/**获取所有货币**/
	private function getcurrency($idd=0){
		$cdb = pc_base::load_model('currency_model');
		if($idd==0){
			$sql = '`siteid`=1';
			$datas = $cdb->select($sql,'*',10000);
			return $datas;
		}else{
			$datas = $cdb->get_one("siteid='1' AND aid='$idd'",'symbol');
			return $datas['symbol'];
		}
	}

	//根据ID返回单位名称
	private function getunitname($cid){

		return $this->getunits($cid);
	}

	/**获取所有单位**/
	private function getunits($idd=0){
		$cdb = pc_base::load_model('enum_model');
		if($idd==0){
		$sql = '`siteid`=1 AND groupid=2 ';
		$datas = $cdb->select($sql,'*',10000,'listorder ASC');
		return $datas;
		}else{
			
			$datas = $cdb->get_one("siteid='1' AND groupid=2 and aid='$idd'",'title');
			return $datas['title'];
		}
	}	

	/**获取增值服务**/
	private function getservicelist2(){
		$cdb = pc_base::load_model('service_model');
		$sql = ' siteid=1 and type=\'value-added\'';
		$datas = $cdb->select($sql,'*',10000,'addtime desc');
		return $datas;
	}

	/** 获取发货地 (国家和地区) **/
	private function getsendlists(){
	
		$sql = '';
		$results = getcache('get__storage__lists', 'commons');

		$datas = array();
		$datas = $this->getline();
		/*foreach($lines as $k=>$v){
			foreach($results as $kk=>$row){
				if($v['linkageid']==$row['area']){
					$v['storagecode']=$row['storagecode'];
					$datas[]=$v;
				}
			}
		}*/
		return $datas;
	}

	//返回转运方式
	public function getshippingmethod(){
		
		$idd = isset($_GET['idd']) ? intval($_GET['idd']) : 0;
		$sendid = isset($_GET['sendid']) ? intval($_GET['sendid']) : 0;
		$datas = array();
		$sendlists =  $this->getturnway();
		
		foreach($sendlists as $k=>$v){
			if(($v['destination']==$idd && $v['origin']==$sendid) || ($v['destination']==$sendid && $v['origin']==$idd))
				$datas[]=$v;
		}
		print_r(json_encode($datas));
		exit;
	}

/** 获取收货地 (国家和地区) **/
	public function gettakelists(){

		$areaid = isset($_GET['areaid']) ? intval($_GET['areaid']) : 0;
		$datas = array();
		$sendlists = $this->getsendlists();
		
		foreach($sendlists as $k=>$v){
			//if($v['linkageid']!=$areaid)
				$datas[]=$v;
		}
		print_r(json_encode($datas));
		exit;
	}

	/**获取国家地区**/
	private function getline($id=0){
		$datas = getcache('get__linkage__lists', 'commons');

		if($id>0){
			
			$title="";
			foreach($datas as $v){
				if($id==$v['linkageid']){
					$title=$v['name'];
				}
			}
			return $title;
		}else{
			
			return $datas;
		}
	}

	/**获取国家地区**/
	private function getallstorage($id=0){
		$datas = getcache('get__storage__lists', 'commons');
		return $datas;
		
	}

	/**获取运单订单状态**/
	private function getwaybilltatus($id=0,$storageid){
		$cdb = pc_base::load_model('enum_model');
		
			$sql = ' groupid=9 and value=\''.$id.'\' ';
			$datas = $cdb->get_one($sql);
			$statusname="";
			if($id==17)//已入xx仓库
			{
				$statusname=str_replace(L('enter_xxstorage'),$this->getstoragename($storageid),$datas['title']);
			}else{
				$statusname=$datas['title'];
			}
			return $statusname.' ';
		
	}


	//sysbillid

	//获取当前sysbillid
	public function get_way_sysbillid($waybillid,$returncode=''){
		$waybillid = trim($waybillid);
		$udb = pc_base::load_model('waybill_model');
		$r = $udb->get_one("waybillid='$waybillid' AND returncode='$returncode'  ");
		return trim($r['sysbillid']);
	}

	//获取当前仓库
	public function getstoragename($storageid){
		$datas = getcache('get__storage__lists', 'commons');
		$title="";
		foreach($datas as $v){
			if($storageid==$v['aid']){
				$title=$v['title'];
			}
		}

		return $title;
	}
	
	/**根据运单号获取运单物品详细**/
	private function getwaybill_goods($waybillid, $returncode=''){
		$cdb = pc_base::load_model('waybill_goods_model');
		$sql = "  waybillid='$waybillid' AND returncode='$returncode' ";
		$datas = $cdb->select($sql,'*',10000,'number asc');

		return $datas;
	}

	/**根据运单号获取增值服务**/
	private function getwaybill_servicelist($waybillid,$waybill_goodsid,$returncode=''){
		$cdb = pc_base::load_model('waybill_serviceitem_model');
		$sql = " waybillid='$waybillid' and waybill_goodsid='$waybill_goodsid' AND returncode='$returncode' ";
		$datas = $cdb->select($sql,'*',10000,'id desc');
		return $datas;
	}

	/**选择国内快递公司**/
	private function select_express_cn($id=''){
		$datas = getcache('get__enum_data_69', 'commons');

		if($id>0){
			
			$title="";
			foreach($datas as $v){
				if($id==$v['value']){
					$title=$v;
				}
			}
			return $title;
		}else{
			
			return $datas;
		}
	}

	/**选择国际快递公司**/
	private function select_express_com($id=''){
		$datas = getcache('get__enum_data_83', 'commons');

		if($id>0){
			
			$title="";
			foreach($datas as $v){
				if($id==$v['value']){
					$title=$v;
				}
			}
			return $title;
		}else{
			
			return $datas;
		}
	}

	//获取总增值费用
	public function getallvaluedfee($waybillid, $returncode=''){

			$valuedfee=0;
			$number=0;
			$allwaybill_goods = $this->getwaybill_goods(trim($waybillid),$returncode);

			foreach($allwaybill_goods as  $k=>$goods){
					$number=$goods['number'];

					if ($goods['addvalues']){

						$goodaddvalues = string2array($goods['addvalues']);

						foreach($goodaddvalues as $addval){
				
							$val = explode("|",$addval);
							
							$valuedfee+=floatval($val[6]);
							
						}
					}
				}
			
			$info=$this->db->get_one(array('waybillid'=>trim($waybillid),'returncode'=>$returncode),"addvalues");
			$goodaddvalues = string2array($info['addvalues']);
		
			
			foreach($goodaddvalues  as $bill){
						
				if($bill['title']=='保险'){
						$valuedfee+=floatval($bill['price'])*3/100;//保险费
				}else{
						$valuedfee+=floatval($bill['price']);
				}
						
			}
			
			
			return $valuedfee;//增值fee
	
	}


	private function sendemailwaybill($sysbillid,$statusinfo,$send_email_nofity=1){
			
			$sysres = $this->db->get_one(array('sysbillid'=>$sysbillid));
			if($sysres && $send_email_nofity==1){

			$moduledb = pc_base::load_model('module_model');
			$__mdb = pc_base::load_model('member_model');
			$userinfo=$__mdb->get_one(array('userid'=>$sysres['userid']));

			//$status_array=array(1=>'未入库',2=>'入库中',3=>'已入库',4=>'已处理',5=>'处理异常',6=>'已取消',7=>'已付款',8=>'待付款',9=>'已出库',10=>'已扫描',11=>'中转成功',12=>'已入xx仓库',13=>'等待自取',14=>'快递跟踪',15=>'等待赔偿',16=>'已签收',17=>'待发货');

			//发邮件
			pc_base::load_sys_func('mail');
			$member_setting = $moduledb->get_one(array('module'=>'member'), 'setting');
			$member_setting = string2array($member_setting['setting']);
			$url = APP_PATH."index.php?m=waybill&c=index&a=init&hid=".$sysres['storageid'];
			$message = $member_setting['waybillemail'];
			$message = str_replace(array('{click}','{url}','{no}','{status}'), array('<a href="'.$url.'">'.L('please_click').'</a>',$url,$sysres['waybillid'],$statusinfo), $message);
			sendmail($userinfo['email'], '你的运单号'.$sysres['waybillid'].$statusinfo, $message);
			}
	}

	/**获取运单订单状态**/
	private function getallwaybilltatus($id=''){
		$datas = getcache('get__enum_data_9', 'commons');

		if(!empty($id)){
			$title="";
			foreach($datas as $v){
				if($id==$v['value']){
					$title=$v['title'];
				}
			}
			return $title;
		}else{
			
			return $datas;
		}
	}

	/**获取所有货运公司**/
	private function getallship($id=''){
		$datas = getcache('get__enum_data_1', 'commons');

		if(!empty($id)){
			$title="";
			foreach($datas as $v){
				if($id==$v['value']){
					$title=$v['title'];
				}
			}
			return $title;
		}else{
			
			return $datas;
		}
	}

	/**获取转运方式**/
	private function getturnway(){
		$datas = getcache('get__turnway__lists', 'commons');
		return $datas;
	}


	public function getwayrate(){
		$datas = getcache('get__currency__lists', 'commons');
		$title="";
		foreach($datas as $v){
			if('JPY'==$v['code']){
				$title=$v['exchangerate'];
			}
		}
		return $title;
	}

	/**获取转运类别**/
	private function getshippingtype(){
		$datas = getcache('get__storage__lists', 'commons');
		return $datas;
	}

	/**获取收货地址**/
	private function getaddresslist($uid=0,$uname=''){
		$cdb = pc_base::load_model('address_model');
		if($uid>0){

			$sql = ' siteid=1 and (userid='.$uid.' OR username=\''.$uname.'\') ';
		}else{
			$sql="siteid=1";
		}
		$datas = $cdb->select($sql,'*',10000,'addtime desc');
		return $datas;
	}

	private function getaddresslist2(){
		$cdb = pc_base::load_model('address_model');
		if($uid>0){
			$sql = ' siteid=1 and userid='.$uid.' ';
		}else{
			$sql="siteid=1 and addresstype=2";
		}
		$datas = $cdb->select($sql,'*',10000,'addtime desc');
		return $datas;
	}

}
?>