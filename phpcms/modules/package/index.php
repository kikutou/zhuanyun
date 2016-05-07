<?php 
defined('IN_PHPCMS') or exit('No permission resources.');

pc_base::load_app_class('foreground','member');
pc_base::load_sys_class('format', '', 0);
pc_base::load_sys_class('form', '', 0);

class index extends foreground {
	private $hid;
	public $current_user;
	function __construct() {
		$this->db = pc_base::load_model('waybill_model');
		//$this->wdb = pc_base::load_model('waybill_model');

		$this->_username = param::get_cookie('_username');
		$this->_userid = intval(param::get_cookie('_userid'));
		$this->siteid =  isset($_REQUEST['siteid']) && trim($_REQUEST['siteid']) ? intval($_REQUEST['siteid']) : 1;
		$this->hid =  isset($_GET['hid']) && trim($_GET['hid']) ? intval($_GET['hid']) : 0;
		$this->current_user = $this->get_current_userinfo();

		$status1count=$this->getwaybillcount(0,1);
		$status3count=$this->getpackagecount(0,3);
		$status21count=$this->getwaybillcount(0,21);
		$status8count=$this->getpackagecount(0,8);
		$status7count=$this->getpackagecount(0,7);
		$status14count=$this->getpackagecount(0,14);
		$status16count=$this->getpackagecount(0,16);
		

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
		$sql = ' 1 ';

		$status = isset($_REQUEST['status']) ? intval($_REQUEST['status']) : 0;

		if(isset($_POST['dosubmit'])) {
			
			$orderno = safe_one_string($_POST['orderno']);
			$starttime = strtotime($_POST['begindate']);
			$endtime = strtotime($_POST['enddate']);

			if($status>0){
				$sql.=" and status='$status' ";
			}
			if(!empty($orderno)){
				$sql.=" and (expressno like '%$orderno%' or waybillid like '%$orderno%' ) ";
			}

			if(!empty($starttime) && !empty($endtime)){
				$sql.=" and addtime>='$starttime'  and addtime<='$endtime' ";
			}
		}

	
			$sql.=" and status='1' ";
		

		$sql .= " and siteid='$this->siteid' and userid='$this->_userid'  ";
		
		$page = max(intval($_GET['page']), 1);
		$datas = $this->db->listinfo($sql, '`addtime` DESC', $page,50);

		$pages= $this->db->pages;
		
		include template('package', 'package_list');
	}
	

	//two bill no
	public function getbill_orderno($org_h,$dst_h,$wid=0,$uid=0,$_clientname=''){
		$userid = $this->_userid;
		if(!is_numeric($org_h)){$org_h=100;}
	
		$num = $wid;
		$ordercode = str_replace("T","0",str_replace("H","",$_clientname)).date('md').str_pad($num,6,"0",STR_PAD_LEFT);
			
	
		return $ordercode;
	}

	public function unionbox(){
		$historydb = pc_base::load_model('waybill_history_model');
			if(isset($_POST['dosubmit'])) {
			
			$_oremark = $_POST['remark'];
			
			$aid = isset($_POST['aid']) ? ($_POST['aid']) : array();

			$split_number = isset($_POST['split_number']) ? ($_POST['split_number']) : 0;
			//print_r($_POST);exit;

			//增值服务-----------------------------------------------------------------
				$addFee=0;
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
									$addFee+=floatval($m_price);
								}
								$srv_value_array[$m_typeid]['title'] = $m_title;
								$srv_value_array[$m_typeid]['currencyname'] = $m_currencyname;
								$srv_value_array[$m_typeid]['unitname'] = $m_unitname;}
						}
					}

				
				//-------------------------------------------------------------------------------

			$totalnum=0;
			$otherremark="";

			$expressno="";
			$goodsname="";
			$long=0;
			$width=0;
			$height=0;

			$totalprice=0;
			$totalamount=0;
			$totalweight=0;

			$payfeeusd =0;
			$allvaluedfee = 0;
			$wayrate = 0;
			$totalship = 0;
			$payedfee =0;
			$otherfee = 0;
			$taxfee = 0;
	
			$remark="";
			$allgoodsarray=array();

		

			foreach($aid as $id){
					$totalnum++;
					
					$row = $this->db->get_one(array('aid'=>$id));
					$hisdatas = $historydb->select(array('waybillid'=>trim($row['waybillid'])),"*",1000);

					$datatmps = string2array($row['goodsdatas']);
					foreach($datatmps as $_goods){
						$allgoodsarray[] = $_goods;
					}

					
					

					$wayrate = $row['wayrate'];
					if($otherremark==""){
						$otherremark.="#".$row['aid']."合箱";
						$expressno=$row['expressno'];
						$goodsname=$row['goodsname'];
						$long=$row['bill_long'];
						$width=$row['bill_width'];
						$height=$row['bill_height'];

						$totalprice=$row['totalprice'];
						$totalamount=$row['totalamount'];
						$totalweight=$row['totalweight'];

						$payfeeusd =$row['payfeeusd'];
						$allvaluedfee = $row['allvaluedfee'];
						$taxfee = $row['taxfee'];
						$totalship = $row['totalship'];
						$payedfee =$row['payedfee'];
						$otherfee = $row['otherfee'];
						$remark=$row['remark'];

					}else{
						$remark.="+"+$row['remark'];
						$otherremark.="+"."#".$row['aid']."合箱";
						$expressno.="+".$row['expressno'];
						$goodsname.="+".$row['goodsname'];
						$long+=$row['bill_long'];
						$width+=$row['bill_width'];
						$height+=$row['bill_height'];
						

						$totalprice += $row['totalprice'];
						$totalamount += $row['totalamount'];
						$totalweight += $row['totalweight'];

						$payfeeusd +=$row['payfeeusd'];
						$allvaluedfee += $row['allvaluedfee'];
						$taxfee += $row['taxfee'];
						$totalship += $row['totalship'];
						$payedfee +=$row['payedfee'];
						$otherfee += $row['otherfee'];

					}
					

					

					if($totalnum==count($aid)){

					
					
						$newrow=array();
						$newrow = $row;
						unset($newrow['waybillid']);
						unset($newrow['sysbillid']);
						unset($newrow['aid']);
						unset($newrow['addvalues']);
						unset($newrow['goodsdatas']);
						
						unset($newrow['declaredatas']);
						unset($newrow['imagesurl']);


						/* 插入订单表 */
						$error_no = 0;
						do
						{
								$waybillid = $this->getbill_orderno($org_housecode,$dst_housecode,get__order__counter(),$row['userid'],$row['takeflag']); //获取新订单号
								$error_no = $this->db->count(array('waybillid'=>trim($waybillid)));
						}
						while ($error_no > 0 ); //如果是订单号重复则重新提交数据
						
						$newrow['addvalues']=array2string($srv_value_array);
						$newrow['goodsdatas']=array2string($allgoodsarray);

						$newrow['declaredatas']=array2string(string2array($row['declaredatas']));
						$newrow['imagesurl']=array2string(string2array($row['imagesurl']));

						$newrow['waybillid']=$waybillid;
						$newrow['sysbillid']=date('ymdHis',time()).floor(microtime()*1000);
						$newrow['boxstatus']=1;
						$newrow['expressno'] = $expressno;//
						$newrow['goodsname'] = $goodsname;
						$newrow['bill_long'] = $long;
						$newrow['bill_width'] = $width;
						$newrow['bill_height'] = $height;

						$newrow['totalprice'] = $totalprice;
						$newrow['totalamount'] = $totalamount;
						$newrow['totalweight'] = $totalweight;
								
						$newrow['otherremark']=$otherremark;
						$newrow['status']=21;
						$newrow['remark'] = $_oremark;
						
						$payfeeusd = get_ship_fee($newrow['totalweight'],get_common_shipline($newrow['shippingid']),trim($newrow['takename']));
						
						$newrow['totalship']=$payfeeusd;

						$newrow['payfeeusd']=$payfeeusd + $addFee + $otherfee;
						$newrow['allvaluedfee']=$addFee;
						
						$newrow['payedfee']= $newrow['payfeeusd'] * $wayrate + $taxfee;

						$newrow['otherfee']=$otherfee; 
						$newrow['taxfee']=$taxfee;
						$newrow['srvtype']=1;
						$newrow['handletime'] = SYS_TIME;

						$this->db->insert($newrow);

						//////////////////////////////////////////////////////////////////////////////////
							foreach($hisdatas as $handle){
								unset($handle['id']);
								$handle['waybillid'] = $waybillid;
								$handle['sysbillid'] = $waybillid;
								$historydb->insert($handle);
							}
							//////////////////////////////////////////////////////////////////////////////////

					
					
					}
				$this->db->update(array('boxstatus'=>1,'status'=>20,'otherremark'=>'','handletime'=>SYS_TIME),array('aid'=>$id));
			}

			//////////////////////////////////////////split box//////////////////////////////////////////////////////

			

		}

		showmessage(L('operation_success'), "?m=package&c=index&a=sendedmanage&hid=".$this->hid);
	}

	public function unionbox_pre(){
		
		$aid = isset($_POST['aid']) ? ($_POST['aid']) : array('0'=>0);


		$sql="  status in(1,3) and aid in(".implode(',',$aid).")  ";
		

		$sql .= " and siteid='$this->siteid' and userid='$this->_userid'  ";
		
		$page = max(intval($_GET['page']), 1);
		$datas = $this->db->listinfo($sql, '`addtime` DESC', $page,50);

		$pages= $this->db->pages;
		

		$cdb = pc_base::load_model('service_model');
		$cosql = ' siteid=1 and type=\'co-box\'';
		$co_box_datas = $cdb->select($cosql,'*',10000,'addtime desc');

		
		include template('package', 'package_unionbox_pre');
	}

	public function splitbox_pre(){


		$aid = isset($_POST['aid']) ? ($_POST['aid']) : array('0'=>0);
		$split_number = isset($_POST['split_number']) ? ($_POST['split_number']) : 0;

		$sql="  status in(1,3) and aid in(".implode(',',$aid).")  ";
		

		$sql .= " and siteid='$this->siteid' and userid='$this->_userid'  ";
		
		$page = max(intval($_GET['page']), 1);
		$datas = $this->db->listinfo($sql, '`addtime` DESC', $page,50);

		$pages= $this->db->pages;
		
		$cdb = pc_base::load_model('service_model');
		$binsql = ' type=\'binning\'';
		$binning_datas = $cdb->select($binsql,'*',10000,'addtime desc');
		
		
		include template('package', 'package_splitbox_pre');
	}
	

	public function splitbox(){
		$historydb = pc_base::load_model('waybill_history_model');

		if(isset($_POST['dosubmit'])) {
			
			$aid = isset($_POST['aid']) ? ($_POST['aid']) : array();

			$split_number = isset($_POST['split_number']) ? ($_POST['split_number']) : 0;


			$_oremark = $_POST['remark'];

			//增值服务-----------------------------------------------------------------
				$addFee=0;
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
									$addFee+=floatval($m_price);
								}
								$srv_value_array[$m_typeid]['title'] = $m_title;
								$srv_value_array[$m_typeid]['currencyname'] = $m_currencyname;
								$srv_value_array[$m_typeid]['unitname'] = $m_unitname;}
						}
					}

				
				//-------------------------------------------------------------------------------

			$totalnum=0;
			$otherremark="";

			$expressno="";
			$goodsname="";
			$long=0;
			$width=0;
			$height=0;

			$totalprice=0;
			$totalamount=0;
			$totalweight=0;

			$payfeeusd =0;
			$allvaluedfee = 0;
			$wayrate = 0;
			$totalship = 0;
			$payedfee =0;
			$otherfee = 0;
			$taxfee = 0;
			
			foreach($aid as $id){
				

					$row = $this->db->get_one(array('aid'=>$id));
					
					$hisdatas = $historydb->select(array('waybillid'=>trim($row['waybillid'])),"*",1000);

					$otherremark=$row['waybillid']."分箱";
					for($n=0;$n<$split_number;$n++){
						$newrow = $row;
						unset($newrow['waybillid']);
						unset($newrow['sysbillid']);
						unset($newrow['aid']);
						unset($newrow['addvalues']);
						unset($newrow['goodsdatas']);
						unset($newrow['declaredatas']);

						unset($newrow['imagesurl']);

						/* 插入订单表 */
						$error_no = 0;
						do
						{
								$waybillid = $this->getbill_orderno($org_housecode,$dst_housecode,get__order__counter(),$row['userid'],$row['takeflag']); //获取新订单号
								$error_no = $this->db->count(array('waybillid'=>trim($waybillid)));
						}
						while ($error_no > 0 ); //如果是订单号重复则重新提交数据
						
						$newrow['addvalues']=array2string($srv_value_array);
						$newrow['goodsdatas']=array2string(string2array($row['goodsdatas']));
						$newrow['declaredatas']=array2string(string2array($row['declaredatas']));

						$newrow['imagesurl']=array2string(string2array($row['imagesurl']));

						$newrow['waybillid']=$waybillid;
						$newrow['sysbillid']=date('ymdHis',time()).floor(microtime()*1000);
						$newrow['boxstatus']=1;
						$newrow['status']=21;

						$payfeeusd = get_ship_fee($newrow['totalweight'],get_common_shipline($newrow['shippingid']),trim($newrow['takename']));
						
						$newrow['totalship']=$payfeeusd;

						$newrow['otherremark']=$otherremark;
						$newrow['remark']=$_oremark;

						$newrow['payfeeusd']=$payfeeusd + $addFee + $newrow['otherfee'];
						$newrow['allvaluedfee']=$addFee;
						
						$newrow['payedfee']= $newrow['payfeeusd'] * $newrow['wayrate'] + $newrow['taxfee'];
						$newrow['srvtype']=2;
						$newrow['handletime']=SYS_TIME;
						


						$this->db->insert($newrow);

						//////////////////////////////////////////////////////////////////////////////////
							foreach($hisdatas as $handle){
								unset($handle['id']);
								$handle['waybillid'] = $waybillid;
								$handle['sysbillid'] = $waybillid;
								$historydb->insert($handle);
							}
							//////////////////////////////////////////////////////////////////////////////////


					}
					$this->db->update(array('boxstatus'=>1,'status'=>19,'otherremark'=>$otherremark,'handletime'=>SYS_TIME),array('aid'=>$id));

				
			}

			//////////////////////////////////////////split box//////////////////////////////////////////////////////

			

		}

		showmessage(L('operation_success'), "?m=package&c=index&a=sendedmanage&hid=".$this->hid);
	
	}

	public function housemanage() {
		$sql = ' 1 ';

		$status = isset($_REQUEST['status']) ? intval($_REQUEST['status']) : 0;

		if(isset($_POST['dosubmit'])) {
			
			$orderno = safe_one_string($_POST['orderno']);
			$starttime = strtotime($_POST['begindate']);
			$endtime = strtotime($_POST['enddate']);

			if($status>0){
				$sql.=" and status='$status' ";
			}
			if(!empty($orderno)){
				$sql.=" and (expressno like '%$orderno%' or waybillid like '%$orderno%' ) ";
			}

			if(!empty($starttime) && !empty($endtime)){
				$sql.=" and addtime>='$starttime'  and addtime<='$endtime' ";
			}
		}

		
		$sql.=" and status=3 and srvtype in(0,1,2)  ";
		

		$sql .= " and siteid='$this->siteid' and userid='$this->_userid'  ";
		
		$page = max(intval($_GET['page']), 1);
		$datas = $this->db->listinfo($sql, '`addtime` DESC', $page,50);

		$pages= $this->db->pages;
		
		include template('package', 'package_housemanage');
	}


	public function sendmanage() {
		$sql = ' 1 ';

		$status = isset($_REQUEST['status']) ? intval($_REQUEST['status']) : 0;

		if(isset($_POST['dosubmit'])) {
			
			$orderno = safe_one_string($_POST['orderno']);
			$starttime = strtotime($_POST['begindate']);
			$endtime = strtotime($_POST['enddate']);

		
			if(!empty($orderno)){
				$sql.=" and (expressno like '%$orderno%'  or waybillid like '%$orderno%') ";
			}

			if(!empty($starttime) && !empty($endtime)){
				$sql.=" and addtime>='$starttime'  and addtime<='$endtime' ";
			}
		}


			$sql.=" and (status='10' or status='7' ) ";
		

		$sql .= " and siteid='$this->siteid' and userid='$this->_userid' and storageid='$this->hid' ";
		
		$page = max(intval($_GET['page']), 1);
		$datas = $this->db->listinfo($sql, '`addtime` DESC', $page,50);

		$pages= $this->db->pages;
		
		include template('package', 'package_sendmanage');
	}


	public function nopaypackage() {
		$sql = ' 1 ';
		
	
		$status = isset($_REQUEST['status']) ? intval($_REQUEST['status']) : 0;

		if(isset($_POST['dosubmit'])) {
			
			$orderno = safe_one_string($_POST['orderno']);
			$starttime = strtotime($_POST['begindate']);
			$endtime = strtotime($_POST['enddate']);

		
			if(!empty($orderno)){
				$sql.=" and (expressno like '%$orderno%'  or waybillid like '%$orderno%') ";
			}

			if(!empty($starttime) && !empty($endtime)){
				$sql.=" and addtime>='$starttime'  and addtime<='$endtime' ";
			}
		}


			$sql.=" and ( status='8') ";
		

		$sql .= " and siteid='$this->siteid' and userid='$this->_userid'  ";
		
		$page = max(intval($_GET['page']), 1);
		$datas = $this->db->listinfo($sql, '`addtime` DESC', $page,50);

		$pages= $this->db->pages;
		
		include template('package', 'package_nopaypackage');
	}
	
	public function sendedmanage() {
		$sql = ' 1 ';

		$status = isset($_REQUEST['status']) ? intval($_REQUEST['status']) : 0;

		if(isset($_POST['dosubmit'])) {
			
			$orderno = safe_one_string($_POST['orderno']);
			$starttime = strtotime($_POST['begindate']);
			$endtime = strtotime($_POST['enddate']);

		
			if(!empty($orderno)){
				$sql.=" and (expressno like '%$orderno%' ) ";
			}

			if(!empty($starttime) && !empty($endtime)){
				$sql.=" and addtime>='$starttime'  and addtime<='$endtime' ";
			}
		}


		$sql.=" and status = 21 ";
		

		$sql .= " and siteid='$this->siteid' and userid='$this->_userid'  ";
		
		$page = max(intval($_GET['page']), 1);
		$datas = $this->db->listinfo($sql, '`addtime` DESC', $page,50);

		$pages= $this->db->pages;
		
		include template('package', 'package_sendedmanage');
	}

	public function add() {
		
		$show_validator = 1;
		if(isset($_POST['dosubmit'])) {
			$_POST['package'] = $this->check($_POST['package']);
			
			$r = $this->db->get_one(array('expressno' => trim($_POST['package']['expressno']),'returncode'=>''));
			if(!$r){

			$_POST['package']['takeflag'] = param::get_cookie('_clientname');
			$_POST['package']['takecode'] = param::get_cookie('_clientcode');

			if($this->db->insert(safe_array_string($_POST['package']))) showmessage(L('packagement_successful_added'), "/index.php?m=package&c=index&a=init&hid=".$this->hid);
			}else{
				showmessage(L('expressno_exists'), HTTP_REFERER);
			}
		} else {
			$shippingtypes = $this->getshippingtype();
			$addresslist = $this->getaddresslist();	

			$packagetm = str_replace('"date"','"date inp"',form::date('package[packagetime]','',''));
			include template('package', 'package_add');
		}

		
	}


	public function show() {
		
		$show_validator = 1;
		$_GET['aid'] = intval($_GET['aid']);
		$aid = $_GET['aid'];
		if(!$_GET['aid']) showmessage(L('illegal_operation'));
		if(isset($_POST['dosubmit'])) {
			
			$_POST['package'] = $this->check($_POST['package'], 'edit');


			//if($this->db->update(safe_array_string($_POST['package']), array('aid' => $_GET['aid']))) showmessage(L('packaged_a'), "/index.php?m=package&c=index&a=init&hid=".$this->hid);
		} else {
			$where = array('aid' => $_GET['aid']);
			$an_info = $this->db->get_one($where);
			$shippingtypes = $this->getshippingtype();

			$addresslist = $this->getaddresslist();	

			$packagetm = str_replace('"date"','"date inp"',form::date('package[packagetime]',date('Y-m-d',$an_info['packagetime']),''));

			include template('package', 'package_show');
		}
		
	}


	public function edit() {
		
		$show_validator = 1;
		$_GET['aid'] = intval($_GET['aid']);
		$aid = $_GET['aid'];
		if(!$_GET['aid']) showmessage(L('illegal_operation'));
		if(isset($_POST['dosubmit'])) {
			
			$_POST['package'] = $this->check($_POST['package'], 'edit');

			$_POST['package']['takeflag'] = param::get_cookie('_clientname');
			$_POST['package']['takecode'] = param::get_cookie('_clientcode');
			
			if($this->db->update(safe_array_string($_POST['package']), array('aid' => $_GET['aid']))) showmessage(L('packaged_a'), "/index.php?m=package&c=index&a=init&hid=".$this->hid);
		} else {
			$where = array('aid' => $_GET['aid']);
			$an_info = $this->db->get_one($where);
			$shippingtypes = $this->getshippingtype();

			$addresslist = $this->getaddresslist();	

			$packagetm = str_replace('"date"','"date inp"',form::date('package[packagetime]',date('Y-m-d',$an_info['packagetime']),''));

			include template('package', 'package_edit');
		}
		
	}


	/**
	 * 批量删除预约
	 */
	public function delete($aid = 0) {

		if((!isset($_POST['aid']) || empty($_POST['aid'])) && !$aid) {
			showmessage(L('illegal_operation'));
		} else {
			if(is_array($_POST['aid']) && !$aid) {
				array_map(array($this, 'delete'), $_POST['aid']);
				showmessage(L('operation_success'), HTTP_REFERER);
			} elseif($aid) {
				$aid = intval($aid);
				
				$info = $this->db->get_one(array('aid' => $aid,'status'=>1));
				if($info){
					$this->db->delete(array('aid' => $aid));
				}else{
					showmessage(L('operation_failure'), HTTP_REFERER);
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
			$r = $this->db->get_one("aid!='".$_GET['aid']."' AND expressno='$expressno' AND returncode=''");
			if ($r) {
				exit('1');
			}else{
				exit('0');
			}
		} 
		$r = $this->db->get_one(array('siteid' => $this->siteid,'userid'=>$this->_userid,'storageid'=>$this->hid, 'expressno' => $expressno, 'returncode'=>''), 'aid');
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
		if($data['expressno']=='') showmessage(L('expressno_cannot_empty'));
		
		$r = $this->db->get_one(array('expressno' => $data['expressno'], 'returncode'=>''));
		
		$data['truename'] = $this->current_user['lastname'].$this->current_user['firstname'];


		$all___warehouse__lists = $this->get_warehouse__lists();
		foreach($all___warehouse__lists as $v){
			if($v['aid']==$this->hid){
				$data['storagename'] = $v['title'];
			}
		}


		if ($a=='add') {
			if (is_array($r) && !empty($r)) {
				showmessage(L('expressno_exists'), HTTP_REFERER);
			}
			$data['siteid'] = $this->siteid;
			$data['addtime'] = SYS_TIME;
			$data['username'] = $this->_username;
			$data['userid'] = $this->_userid;
			$data['storageid'] = $this->hid;
			$data['status'] = 1;
			
		} else {
			if ( $r['aid'] && ($r['aid']!=$_GET['aid'])) {
				showmessage(L('expressno_exists'), HTTP_REFERER);
			}
		}


		return $data;
	}


	/**获取转运类别**/
	private function getshippingtype(){
		$datas = getcache('get__storage__lists', 'commons');
		return $datas;
	}

	/**获取转运类别名称**/
	private function getshippingtypename($typeid){
		$datas = getcache('get__storage__lists', 'commons');
		$title=array();
		foreach($datas as $v){
			if($typeid==$v['aid']){
				$title=$v;
			}
		}

		return $title;
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
		$datas = getcache('get__enum_data_1', 'commons');

		if($id>0){
			
			$title=array();
			foreach($datas as $v){
				if($id==$v['aid']){
					$title=$v;
				}
			}
			return $title;
		}else{
			
			return $datas;
		}
	}

	/**获取所有包裹状态**/
	private function getpackagestatus(){
		$datas = getcache('get__enum_data_48', 'commons');
		return $datas;
	}


	/**判断运单物品里面是否存在该邮单号**/
	private function getexpressno_exists($packageid,$returncode=''){
		$cdb = pc_base::load_model('waybill_goods_model');
		$sql = '  packageid=\''.$packageid.'\' and waybillid not in(select waybillid from t_waybill where status=6  AND returncode=\''.$returncode.'\' ) AND returncode=\''.$returncode.'\' ';
		$row = $cdb->get_one($sql,'packageid');
		if($row)
			return true;
		else
			return false;
	
	}

	private function getcurrency($idd=0){
		$cdb = pc_base::load_model('currency_model');
		if($idd==0){
			$sql = '`siteid`=\''.$this->siteid.'\'';
			$datas = $cdb->select($sql,'*',10000);
			return $datas;
		}else{
			$datas = $cdb->get_one("siteid='$this->siteid' AND aid='$idd'",'symbol');
			return $datas['symbol'];
		}
	}

	private function getunits($idd=0){
		$cdb = pc_base::load_model('enum_model');
		if($idd==0){
		$sql = '`siteid`=\''.$this->siteid.'\' AND groupid=2 ';
		$datas = $cdb->select($sql,'*',10000,'listorder ASC');
		return $datas;
		}else{
			
			$datas = $cdb->get_one("siteid='$this->siteid' AND groupid=2 and aid='$idd'",'title');
			return $datas['title'];
		}
	}
	
}
?>