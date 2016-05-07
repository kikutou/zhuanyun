<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin','admin',0);

class admin_package extends admin {

	private $db; public $username;
	public $hid;

	public function __construct() {
		parent::__construct();
		//if (!module_exists(ROUTE_M)) showmessage(L('module_not_exists'));
		$this->username = param::get_cookie('admin_username');
		$this->userid = param::get_cookie('admin_userid');
		$this->db = pc_base::load_model('waybill_model');
		$this->wdb = pc_base::load_model('waybill_model');
		$this->hid=isset($_GET['hid']) ? intval($_GET['hid']) : 0;


	}
	
	public function init() {
		//包裹列表
		$sql = '';

		$sql = '`siteid`=\''.$this->get_siteid().'\'';
		
		$page = max(intval($_GET['page']), 1);
		$data = $this->db->listinfo($sql, '`aid` DESC', $page);
		$big_menu = array('javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=package&c=admin_package&a=add&hid='.$this->hid.'\', title:\''.L('package_add').'\', width:\'600\', height:\'450\', lock:true}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'add\'}).close()});void(0);', L('package_add'));
		include $this->admin_tpl('package_list');
	}
	



	//two bill no
	public function getbill_orderno($org_h,$dst_h,$wid=0,$uid=0,$_clientname=''){
		$userid = $this->userid;
		if(!is_numeric($org_h)){$org_h=100;}
	
		$num = $wid;
		$ordercode = str_replace("T","0",str_replace("H","",$_clientname)).date('md').str_pad($num,6,"0",STR_PAD_LEFT);
			
	
		return $ordercode;
	}

	public function unionbox(){
	$historydb = pc_base::load_model('waybill_history_model');
			if(isset($_POST['dosubmit'])) {
			
			$aid = isset($_POST['aid']) ? ($_POST['aid']) : array();

			$split_number = isset($_POST['split_number']) ? ($_POST['split_number']) : 0;
			
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
						$remark=$row['remark'];
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
						
						$newrow['addvalues']=array2string(string2array($row['addvalues']));
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
						$newrow['remark'] = $remark;

						$payfeeusd = get_ship_fee($newrow['totalweight'],get_common_shipline($newrow['shippingid']),trim($newrow['takename']));
						
						$newrow['totalship']=$payfeeusd;

						$newrow['payfeeusd']=$payfeeusd + $addFee + $otherfee;
						$newrow['allvaluedfee']=$allvaluedfee;
						
						$newrow['payedfee']= $newrow['payfeeusd'] * $wayrate + $taxfee;
						$newrow['otherfee']=$otherfee;
						$newrow['srvtype']=1;

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
				$this->db->update(array('boxstatus'=>1,'status'=>20,'otherremark'=>''),array('aid'=>$id));
			}

			//////////////////////////////////////////split box//////////////////////////////////////////////////////

			

		}
		

		showmessage(L('operation_success'), HTTP_REFERER);
	}


	public function splitbox(){
		if(isset($_POST['dosubmit'])) {
			
			$aid = isset($_POST['aid']) ? ($_POST['aid']) : array();
			$historydb = pc_base::load_model('waybill_history_model');
			$split_number = isset($_POST['split_number']) ? ($_POST['split_number']) : 0;
		//echo $split_number ;exit;

		
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

					$otherremark=$row['aid']."分箱";
					for($n=0;$n<$split_number;$n++){
						$newrow = $row;
						unset($newrow['aid']);
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
						
						$newrow['addvalues']=array2string(string2array($row['addvalues']));
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

						$newrow['payfeeusd']=$payfeeusd + $addFee + $newrow['otherfee'];
						$newrow['allvaluedfee']=$addFee;
						
						$newrow['payedfee']= $newrow['payfeeusd'] * $newrow['wayrate'] + $newrow['taxfee'];

						$newrow['srvtype']=2;


						

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
					$this->db->update(array('boxstatus'=>1,'status'=>19,'otherremark'=>$otherremark),array('aid'=>$id));

				
			}

			//////////////////////////////////////////split box//////////////////////////////////////////////////////

			

		}

		showmessage(L('operation_success'), HTTP_REFERER);
	
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

		
		$sql.=" and status in(1,3) ";
		

		//$sql .= "  and storageid='$this->hid' ";

		
		
		$page = max(intval($_GET['page']), 1);
		$datas = $this->db->listinfo($sql, '`addtime` DESC', $page,50);

		$pages= $this->db->pages;
		
		include $this->admin_tpl('package_housemanage');
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
		

		$sql .= "   and storageid='$this->hid' ";
		
		$page = max(intval($_GET['page']), 1);
		$datas = $this->db->listinfo($sql, '`addtime` DESC', $page,50);

		$pages= $this->db->pages;
		
		include $this->admin_tpl('package_sendmanage');
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
		

		$sql .= " and siteid='1'  ";
		
		$page = max(intval($_GET['page']), 1);
		$datas = $this->db->listinfo($sql, '`addtime` DESC', $page,50);

		$pages= $this->db->pages;

		include $this->admin_tpl('package_nopaypackage');
		
		
	}
	
	public function sendedmanage() {
		$sql = ' 1 ';

		$status = isset($_REQUEST['status']) ? intval($_REQUEST['status']) : 0;

		if(isset($_POST['dosubmit'])) {
			

			$ids = isset($_POST['aid']) ? $_POST['aid'] : array(0);
			
			$this->db->update(array('status'=>3),"aid in(".implode(',',$ids).")");

			showmessage(L('operation_success'));
		}


		$sql.=" and status='21' ";
		

		//$sql .= "  and storageid='$this->hid' ";
		
		$page = max(intval($_GET['page']), 1);
		$datas = $this->db->listinfo($sql, '`addtime` DESC', $page,50);

		$pages= $this->db->pages;
		
		include $this->admin_tpl('package_sendedmanage');
	}

	/**
	 * 添加包裹
	 */
	public function add() {
		$show_validator = 1;

		if($_GET['uid']){
			$this->getusername();
		}

		if($_GET['tid']){
			$this->gettruename();
		}




			$storagedb = pc_base::load_model('storage_model');
		$allstorage = $storagedb->select("siteid=1 ",'*',10000,'listorder asc');
		if(isset($_POST['dosubmit'])) {
			$_POST['package'] = $this->check($_POST['package']);


			
			//生成系统订单号
			

			$_rs =siteinfo(1); 
			$billprefix="";
			if($_rs){
				$billprefix=$_rs['billprefix'];
			}
			$sysbillid=$billprefix.date('ymdHis',time()).floor(microtime()*1000);
			//--------------end
			
			/* 插入订单表 */
			$error_no = 0;
			do
			{
				$waybillid = $this->getbill_orderno($org_housecode,$dst_housecode,get__order__counter(),$_POST['package']['userid'],$row['takeflag']); //获取新订单号
				$error_no = $this->db->count(array('waybillid'=>trim($waybillid)));
			}
			while ($error_no > 0 ); //如果是订单号重复则重新提交数据

				

			$_POST['package']['waybillid']=$waybillid;
			$_POST['package']['sysbillid']=$sysbillid;

			//--------------end


			if($this->db->insert($_POST['package'])){
				//修改状态send_email_nofity
				//$this->update_all_status($_POST['package']['expressno'],intval($_POST['send_email_nofity']));



				$historydb = pc_base::load_model('waybill_history_model');
			
				//插入处理记录
				$handle=array();
				$handle['siteid'] = 1;
				$handle['username'] = $this->username;
				$handle['userid'] = $this->userid;
				$handle['addtime'] = SYS_TIME;
				$handle['sysbillid'] = trim($_POST['package']['sysbillid']);
				$handle['waybillid'] = trim($_POST['package']['waybillid']);
				$handle['placeid'] = $_POST['package']['storageid'];
				$handle['placename'] = $_POST['package']['storagename'];
				$handle['status'] = 1;
				$handle['remark'] = L('waybill_status'.$handle['status']);	
				$historydb->insert($handle);



				showmessage(L('packagement_successful_added'), HTTP_REFERER, '', 'add');
			}
		} else {
			//获取站点模板信息
			pc_base::load_app_func('global', 'admin');
			
			include $this->admin_tpl('package_add');
		}
	}
	
	public function package_batch_handle(){
	
		$ids = isset($_GET['ids']) ? trim($_GET['ids']) : 0;



		if(isset($_POST['dosubmit'])) {
		
			$ids = isset($_POST['aid']) ? $_POST['aid'] : array();
			foreach($ids as  $v){
				$this->db->update($_POST['waybill_goods'][$v],array('aid'=>$v));
			}

			showmessage(L('operation_success'), HTTP_REFERER, '', 'batch_handle');
		}


		$datas  = $this->db->select("aid in(".$ids.")","*",1000);
		

		include $this->admin_tpl('package_batch_handle');
	}
	
	/**
	 * 仓库修改包裹
	 */
	public function edit_pack() {
		$show_validator = 1;

		
		$_GET['aid'] = intval($_GET['aid']);
		$storagedb = pc_base::load_model('storage_model');
		$allstorage = $storagedb->select("siteid=1 ",'*',10000,'listorder asc');
		if(!$_GET['aid']) showmessage(L('illegal_operation'));
		if(isset($_POST['dosubmit'])) {
			$_POST['package'] = $this->check($_POST['package'], 'edit_pack');
			
						
			$wayrate = $this->getwayrate();

			$an_info = $this->db->get_one( array('aid' => $_GET['aid']));
			
			$addFee = $an_info['allvaluedfee']; 
			$otherfee = $an_info['otherfee']; 
			$otherdiscount = $an_info['otherdiscount']; 
			if($otherdiscount==0){$otherdiscount=1;}

			$_POST['package']['otherfee'] = $otherfee ;

			$payedfee = get_ship_fee($_POST['package']['totalweight'],get_common_shipline($an_info['shippingid']),trim($an_info['takename']));
			
			$taxfee = get_operaction_fee($_POST['package']['totalweight']);
			//echo $payedfee;exit;
			$payedfee = $otherdiscount * $payedfee;

			$_POST['package']['taxfee'] = $taxfee;

			$_POST['package']['allvaluedfee'] = $addFee;
			$_POST['package']['wayrate'] = $wayrate;
			$_POST['package']['totalship'] = $payedfee;
			$_POST['package']['payfeeusd'] = $payedfee+$addFee+floatval($_POST['package']['otherfee']);
			$_POST['package']['payedfee']  =  $taxfee + floatval($_POST['package']['payfeeusd'])* floatval($wayrate);

			if(isset($_POST['waybill_goods'])){
				
				$_POST['package']['goodsdatas'] = array2string($_POST['waybill_goods']);

			}
			
			$_POST['package']['operatorname'] = $this->username;
			$_POST['package']['operatorid'] = SYS_TIME;

			$_POST['package']['inhousetime'] = SYS_TIME;

			if($this->db->update($_POST['package'], array('aid' => $_GET['aid']))){

				
				//$historydb2 = pc_base::load_model('waybill_model');
				$historydb = pc_base::load_model('waybill_history_model');
			
				//插入处理记录
				$handle=array();
				$handle['siteid'] = 1;
				//$handle2['weight'] = trim($_POST['package']['weight2']);
				//$handle2['amount'] = trim($_POST['package']['amount2']);
				//$handle2['price'] = trim($_POST['price2']);
				$handle['username'] = $this->username;
				$handle['userid'] = $this->userid;
				$handle['addtime'] = SYS_TIME;
				$handle['sysbillid'] = trim($an_info['sysbillid']);
				$handle['waybillid'] = trim($an_info['waybillid']);
				$handle['placeid'] = $an_info['storageid'];
				$handle['placename'] = $an_info['storagename'];
				$handle['status'] = 3;
				$historydb->delete(array('status'=>$handle['status'],'waybillid'=>$handle['waybillid'] ));
				$handle['remark'] = L('waybill_status'.$handle['status']);	
				$historydb->insert($handle);

				//$handle['status'] = 8;
				//$historydb->delete(array('status'=>$handle['status'],'waybillid'=>$handle['waybillid'] ));
				//$handle['remark'] = L('waybill_status'.$handle['status']);	
				//$historydb->insert($handle);
				//$historydb2->insert($handle2);



				//修改状态
				$this->update_all_status(trim($an_info['waybillid']),intval($_POST['send_email_nofity']),$returncode,$status,1);
				showmessage(L('packaged_a'), HTTP_REFERER, '', 'edit_pack');
			}
		} else {
			$where = array('aid' => $_GET['aid']);
			$an_info = $this->db->get_one($where);
			include $this->admin_tpl('package_edit');
		}
	}




public function Qinhome() {
		$show_validator = 1;

		
		$an_info = $this->db->get_one( array('takeflag' => trim($_POST['Quid']),'status'=>1,'waybillid' => trim($_POST['Qcwb'])));


		if(isset($_POST['dosubmit']) && $an_info) {
			
			
			$_POST['package'] = array();			
			$wayrate = $this->getwayrate();

			//$an_info = $this->db->get_one( array('aid' => $_GET['aid']));
			
			$addFee = $an_info['allvaluedfee']; 
			$otherfee = $an_info['otherfee']; 
			$otherdiscount = $an_info['otherdiscount']; 
			if($otherdiscount==0){$otherdiscount=1;}

			$_POST['package']['otherfee'] = $otherfee ;

			$_POST['package']['totalweight'] = $_POST['Qweight'] ;

			$payedfee = get_ship_fee($_POST['Qweight'],get_common_shipline($an_info['shippingid']),trim($an_info['takename']));
			
			$taxfee = get_operaction_fee($_POST['Qweight']);
			//echo $payedfee;exit;
			$payedfee = $otherdiscount * $payedfee;

			$_POST['package']['taxfee'] = $taxfee;

			$_POST['package']['allvaluedfee'] = $addFee;
			$_POST['package']['wayrate'] = $wayrate;
			$_POST['package']['totalship'] = $payedfee;
			$_POST['package']['payfeeusd'] = $payedfee+$addFee+floatval($an_info['otherfee']);
			$_POST['package']['payedfee']  =  $taxfee + floatval($an_info['payfeeusd'])* floatval($wayrate);

			
			
			$_POST['package']['operatorname'] = $this->username;
			$_POST['package']['operatorid'] = SYS_TIME;

			$_POST['package']['inhousetime'] = SYS_TIME;

			if($this->db->update($_POST['package'], array('aid' => $an_info['aid']))){

				
				//$historydb2 = pc_base::load_model('waybill_model');
				$historydb = pc_base::load_model('waybill_history_model');
			
				//插入处理记录
				$handle=array();
				$handle['siteid'] = 1;
			
				$handle['username'] = $this->username;
				$handle['userid'] = $this->userid;
				$handle['addtime'] = SYS_TIME;
				$handle['sysbillid'] = trim($an_info['sysbillid']);
				$handle['waybillid'] = trim($an_info['waybillid']);
				$handle['placeid'] = $an_info['storageid'];
				$handle['placename'] = $an_info['storagename'];
				$handle['status'] = 3;
				$historydb->delete(array('status'=>$handle['status'],'waybillid'=>$handle['waybillid'] ));
				$handle['remark'] = L('waybill_status'.$handle['status']);	
				$historydb->insert($handle);

				


				//修改状态
				$this->update_all_status(trim($an_info['waybillid']),1,$returncode,$status,1);
				showmessage("快速入库成功", HTTP_REFERER);
			}
		} else{
			showmessage("error", HTTP_REFERER);
		}
	}
	/**
	 * 修改包裹
	 */
	public function edit() {
		$show_validator = 1;

		if($_GET['uid']){
			$this->getusername();
		}

		if($_GET['tid']){
			$this->gettruename();
		}


		$_GET['aid'] = intval($_GET['aid']);
		
		$storagedb = pc_base::load_model('storage_model');
		$allstorage = $storagedb->select("siteid=1 ",'*',10000,'listorder asc');

		if(!$_GET['aid']) showmessage(L('illegal_operation'));
		if(isset($_POST['dosubmit'])) {
			$_POST['package'] = $this->check($_POST['package'], 'edit');
			
			/*$row=$this->db->get_one(array('aid' => $_GET['aid']));
			if($row){
				$returncode = trim($row['returncode']);
				$status = trim($row['status']);
				$expressno=trim($row['expressno']);
				$newexpressno=trim($_POST['package']['expressno']);
				if($expressno!=$newexpressno){
					$this->update_waybill_expressno($expressno,$newexpressno,$_GET['aid']);
				}
			}*/
			if(isset($_POST['waybill_goods'])){
				
				$_POST['package']['goodsdatas'] = array2string($_POST['waybill_goods']);

			}

			if($this->db->update($_POST['package'], array('aid' => $_GET['aid']))){
				//修改状态
				$this->update_all_status($_POST['package']['expressno'],intval($_POST['send_email_nofity']),$returncode,$status);
			
					showmessage(L('packaged_a'), HTTP_REFERER, '', 'edit');
			}
		} else {
			$where = array('aid' => $_GET['aid']);
			$an_info = $this->db->get_one($where);

			

			include $this->admin_tpl('package_edit');
		}
	}


	public function returned() {
		$show_validator = 1;

		if($_GET['uid']){
			$this->getusername();
		}

		if($_GET['tid']){
			$this->gettruename();
		}

		$idd = intval($_GET['aid']);
		
		$cdb = pc_base::load_model('waybill_goods_model');
		$wdb = pc_base::load_model('waybill_model');
		

		if(!$idd) showmessage(L('illegal_operation'));

		$where = array('aid' => $idd);
		$an_info = $wdb->get_one($where);


		if(isset($_POST['dosubmit'])) {
			
			$allcount = count($_POST['waybill_goods']);
			$waybill_goods_count = intval($_POST['waybill_goods_count']);
			$num=1;
			
			$sysbillid=$billprefix.date('ymdHis',time()).floor(microtime()*1000);

			$waybillid = trim($_POST['waybillid']);
			$status = 15;
			
			unset($an_info['aid']);

			
			
			
			
			$pack_array=array();
			

			foreach($_POST['waybill_goods_packageid'] as $goods){
				$k = $goods;

				$waybill_array = array();
				$waybill_array = $an_info;
				$pack_array[$k]=$k;

				$returncode = $sysbillid.$num;

				$_POST['waybill_goods'][$k]['returntime'] = SYS_TIME;
				$_POST['waybill_goods'][$k]['returnname'] = $this->username;
				$_POST['waybill_goods'][$k]['returncode'] = $returncode;
				$packageid = $_POST['waybill_goods'][$k]['packageid'];

				$where1 = "packageid='$packageid' AND returncode=''";
				$where2 = "aid='$packageid' AND returncode=''";
				$where3 = "waybillid='$waybillid' AND returncode=''";
				
				$waybill_array['returncode'] = $returncode;
				$waybill_array['status'] = $status;
				$waybill_array['returnedstatus'] = 4;

				$waybill_array['goodsname'] = trim($_POST['waybill_goods'][$k]['goodsname']);

				

				unset($_POST['waybill_goods'][$k]['id']);

				$cdb->update($_POST['waybill_goods'][$k],$where1);
				$this->db->update(array('returncode'=>$returncode,'status'=>$status),$where2);

				if($num==$waybill_goods_count){//the last one
					
					$totalamount = $_POST['waybill_goods'][$k]['amount'];
					$totalprice = $_POST['waybill_goods'][$k]['price'];
					$totalweight = $_POST['waybill_goods'][$k]['weight'];
					$othername = $waybill_array['goodsname'];

					$wdb->update(array('goodsname'=>$othername,'totalamount'=>$totalamount,'totalprice'=>$totalprice,'totalweight'=>$totalweight,'returncode'=>$returncode,'status'=>$status,'returnedstatus'=>$waybill_array['returnedstatus'] ),$where3);
				
				}else{
					$waybill_array['addtime'] = SYS_TIME;
					
					$waybill_array['totalamount'] = $_POST['waybill_goods'][$k]['amount'];
					$waybill_array['totalprice'] = $_POST['waybill_goods'][$k]['price'];
					$waybill_array['totalweight'] = $_POST['waybill_goods'][$k]['weight'];
					$waybill_array['addvalues'] = array2string(string2array($an_info['addvalues'])); 

					$wbillid = $wdb->insert($waybill_array,true);	
					$lastinsertid = $wdb->insert_id();

					$wdb->update(array('waybillid'=>$waybillid."-".$lastinsertid),array('aid'=>$lastinsertid));
					$cdb->update(array('waybillid'=>$waybillid."-".$lastinsertid),array('returncode'=>$returncode));
				}

				$num++;
			}
			
			//update package number
			$totalamount=0;
			$totalprice=0;
			$totalweight=0;
	
			
			
			$all_waybill_goods =$_POST['waybill_goods'];
			$othername = "";
			$flagg=false;
			foreach($all_waybill_goods as $k=>$goods){
				if(!array_key_exists($k,$pack_array)){
					$totalamount += $goods['amount'];
					$totalprice += $goods['price'];
					$totalweight += $goods['weight'];
					
					if($othername){
						$othername = $waybill_array['goodsname'];
					}else{
						$othername .="_". $waybill_array['goodsname'];
					}
					$flagg=true;
				}
			}
			if($flagg){
				$wdb->update(array('goodsname'=>$othername,'totalamount'=>$totalamount,'totalprice'=>$totalprice,'totalweight'=>$totalweight),array('waybillid'=>$waybillid));
			}

			showmessage(L('operation_success'),'?m=house&c=admin_house&a=house_jihuo_returned&hid='.$this->hid,'','returned');

		} else {
			

			$allwaybill_goods =$cdb->select(array('waybillid'=>trim($an_info['waybillid']),'returncode'=>$an_info['returncode']),'*',10000,'id asc');

			include $this->admin_tpl('package_returned');
		}
	}



	//分箱包裹
	public function returned_splitbox() {
		$show_validator = 1;

		if($_GET['uid']){
			$this->getusername();
		}

		if($_GET['tid']){
			$this->gettruename();
		}

		$idd = intval($_GET['aid']);
		
		$cdb = pc_base::load_model('waybill_goods_model');
		$wdb = pc_base::load_model('waybill_model');
		

		if(!$idd) showmessage(L('illegal_operation'));

		$where = array('aid' => $idd);
		$an_info = $wdb->get_one($where);


		if(isset($_POST['dosubmit'])) {
			
			$allcount = count($_POST['waybill_goods']);
			$waybill_goods_count = intval($_POST['waybill_goods_count']);
			$num=1;
			
			$sysbillid=$billprefix.date('ymdHis',time()).floor(microtime()*1000);

			$waybillid = trim($_POST['waybillid']);
			$status = 15;
			
			unset($an_info['aid']);

			
			$pack_array=array();
			

			foreach($_POST['waybill_goods_packageid'] as $goods){
				$k = $goods;

				$waybill_array = array();
				$waybill_array = $an_info;
				$pack_array[$k]=$k;

				$returncode = $sysbillid.$num;

				//$_POST['waybill_goods'][$k]['returntime'] = SYS_TIME;
				//$_POST['waybill_goods'][$k]['returnname'] = $this->username;
				//$_POST['waybill_goods'][$k]['returncode'] = $returncode;
				$packageid = $_POST['waybill_goods'][$k]['packageid'];

				$where1 = "packageid='$packageid' AND returncode=''";
				//$where2 = "aid='$packageid' AND returncode=''";
				$where3 = "waybillid='$waybillid' AND returncode=''";
				
				//$waybill_array['returncode'] = $returncode;
				//$waybill_array['status'] = $status;
				//$waybill_array['returnedstatus'] = 4;

				$waybill_array['goodsname'] = trim($_POST['waybill_goods'][$k]['goodsname']);

				
				
				unset($_POST['waybill_goods'][$k]['id']);

				$cdb->update($_POST['waybill_goods'][$k],$where1);
				//$this->db->update(array('returncode'=>$returncode,'status'=>$status),$where2);

				if($num==$waybill_goods_count){//the last one
					
					$totalamount = $_POST['waybill_goods'][$k]['amount'];
					$totalprice = $_POST['waybill_goods'][$k]['price'];
					$totalweight = $_POST['waybill_goods'][$k]['weight'];
					$othername = $waybill_array['goodsname'];

					$wdb->update(array('goodsname'=>$othername,'totalamount'=>$totalamount,'totalprice'=>$totalprice,'totalweight'=>$totalweight ),$where3);
				
				}else{
					$waybill_array['addtime'] = SYS_TIME;
					
					$waybill_array['totalamount'] = $_POST['waybill_goods'][$k]['amount'];
					$waybill_array['totalprice'] = $_POST['waybill_goods'][$k]['price'];
					$waybill_array['totalweight'] = $_POST['waybill_goods'][$k]['weight'];
					$waybill_array['addvalues'] = array2string(string2array($an_info['addvalues'])); 

					$wbillid = $wdb->insert($waybill_array,true);	
					$lastinsertid = $wdb->insert_id();

					$wdb->update(array('waybillid'=>$waybillid."-".$lastinsertid),array('aid'=>$lastinsertid));
					$cdb->update(array('waybillid'=>$waybillid."-".$lastinsertid),array('id'=>$k));
				}

				$num++;
			}
			
			//update package number
			$totalamount=0;
			$totalprice=0;
			$totalweight=0;
	
			
			
			$all_waybill_goods =$_POST['waybill_goods'];
			$othername = "";
			$flagg=false;
			foreach($all_waybill_goods as $k=>$goods){
				if(!array_key_exists($k,$pack_array)){
					$totalamount += $goods['amount'];
					$totalprice += $goods['price'];
					$totalweight += $goods['weight'];
					
					if($othername){
						$othername = $waybill_array['goodsname'];
					}else{
						$othername .="_". $waybill_array['goodsname'];
					}
					$flagg=true;
				}
			}
			if($flagg){
				$wdb->update(array('goodsname'=>$othername,'totalamount'=>$totalamount,'totalprice'=>$totalprice,'totalweight'=>$totalweight),array('waybillid'=>$waybillid));
			}

			showmessage(L('operation_success'),'?m=house&c=admin_house&a=house_jihuo_waybill&hid='.$this->hid,'','splitbox');

		} else {
			

			$allwaybill_goods =$cdb->select(array('waybillid'=>trim($an_info['waybillid']),'returncode'=>$an_info['returncode']),'*',10000,'id asc');

			include $this->admin_tpl('package_splitbox');
		}
	}

	//更新运单邮单号
	public function update_waybill_expressno($expressno,$newexpressno,$packageid=0){
		
		$wgdb = pc_base::load_model('waybill_goods_model');

		$wgdb->update(array('expressno'=>trim($newexpressno)),array('expressno'=>trim($expressno),'packageid'=>$packageid));
	
	}
	
	/**
	 * ajax检测包裹邮单号是否重复
	 */
	public function public_check_expressno() {
		$aid=$_GET['aid'];
		if (!$_GET['expressno']) exit(0);
		$expressno = $_GET['expressno'];

		if (CHARSET=='gbk') {
			$expressno = iconv('UTF-8', 'GBK', $expressno);
		}

		
		if ($aid) {
			$r = $this->db->get_one("aid!='".$aid."' AND expressno='".$expressno."' AND returncode=''");
			if ($r) {
				exit('1');
			}else{
				exit('0');
			}
		}

		$r = $this->db->get_one(array('siteid' => $this->get_siteid(),'storageid'=>$this->hid, 'expressno' => $expressno, 'returncode'=>''), 'aid');
		if($r) {
			exit('1');
		} else {
			exit('0');
		}
	}
	
	/**
	 * 批量修改包裹状态 使其成为审核、未审核状态
	 */
	public function public_approval($aid = 0) {
		if((!isset($_POST['aid']) || empty($_POST['aid'])) && !$aid) {
			showmessage(L('illegal_operation'));
		} else {
			if(is_array($_POST['aid']) && !$aid) {
				array_map(array($this, 'public_approval'), $_POST['aid']);
				showmessage(L('package_passed'), HTTP_REFERER);
			} elseif($aid) {
				$aid = intval($aid);
				
				$this->db->update(array('isdefault' => $_GET['isdefault']), array('aid' => $aid));
				return true;
			}
		}
	}
	
	/**
	 * 批量删除包裹
	 */
	public function delete($aid = 0) {
		if((!isset($_POST['aid']) || empty($_POST['aid'])) && !$aid) {
			showmessage(L('illegal_operation'));
		} else {
			if(is_array($_POST['aid']) && !$aid) {
				array_map(array($this, 'delete'), $_POST['aid']);
				showmessage(L('package_deleted'), HTTP_REFERER);
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
		if($data['expressno']=='') showmessage(L('expressno_cannot_empty'));
	
		$r = $this->db->get_one(array('expressno' => $data['expressno'], 'returncode'=>''));
		
		if($r['status']==1 && $data['status']==2){
			$data['operatorid'] = SYS_TIME;
			$data['operatorname'] = $this->username;
		}

		if ($a=='add') {
			if (is_array($r) && !empty($r)) {
				//showmessage(L('package_exist'), HTTP_REFERER);
			}
			$data['siteid'] = $this->get_siteid();
			$data['addtime'] = SYS_TIME;
			
			
		} else {
			if ($r && ($r['aid']!=$_GET['aid'])) {
				//showmessage(L('package_exist'), HTTP_REFERER);
			}
		}
		

		return $data;
	}

	public function getusername(){
		$uid = intval($_GET['uid']);
		$udb = pc_base::load_model('member_model');
		$row=$udb->get_one("userid='$uid'");
		print_r($row['username']);
		exit;
	}
	public function gettruename(){
		$uid = intval($_GET['tid']);
		$udb = pc_base::load_model('member_model');
		$udb->set_model(10);
		$row=$udb->get_one("userid='$uid'");
		print_r($row['lastname'].$row['firstname']);
		exit;
	}

	/**获取所有包裹状态**/
	private function getpackagestatus(){
		$datas = getcache('get__enum_data_9', 'commons');
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

	
	//修改包裹状态时，判断当前包裹运单是否全部已入库
	//更新运单状态
	private function update_all_status($expressno,$send_email_nofity=1,$returncode='',$status='',$iscwb=0){
		
		$cdb = pc_base::load_model('waybill_goods_model');
		$pdb = pc_base::load_model('package_model');
		$wdb = pc_base::load_model('waybill_model');
		
		$moduledb = pc_base::load_model('module_model');
		$__mdb = pc_base::load_model('member_model');

		$expressno=trim($expressno);

		
		if($iscwb==1){
			$rs = $wdb->get_one(array('waybillid'=>$expressno));
		}else{
			$goodsrs = $cdb->get_one(array('expressno'=>$expressno));
		
			$rs = $wdb->get_one(array('waybillid'=>trim($goodsrs['waybillid'])));
		}
		if($rs){
			
			$userinfo=$__mdb->get_one(array('userid'=>$rs['userid']));
			$status_array=array(1=>'未入库',3=>'已入库');

			//发邮件
			if($send_email_nofity==1){
			pc_base::load_sys_func('mail');
			$member_setting = $moduledb->get_one(array('module'=>'member'), 'setting');
			$member_setting = string2array($member_setting['setting']);
			$url = APP_PATH."index.php?m=package&c=index&a=init&hid=".$rs['storageid'];
			$message = $member_setting['waybillemail'];
			$message = str_replace(array('{click}','{url}','{no}','{status}'), array('<a href="'.$url.'">'.L('please_click').'</a>',$url,$expressno,$status_array[$rs['status']]), $message);
			sendmail($userinfo['email'], '你的运单号'.$expressno.$status_array[$rs['status']], $message);
			}

		}






		$exp=$cdb->get_one(array('expressno'=>$expressno, 'returncode'=>$returncode));
		if($exp){
			$waybillid= trim($exp['waybillid']);
			
			$sql = "  waybillid='$waybillid' AND  returncode='$returncode'";
			$billgoods = $cdb->select($sql,'*',10000,'id asc');
			$status1=0;
			$status2=0;
			
			foreach($billgoods as $val){
				$row = $pdb->get_one(array('expressno'=>trim($val['expressno']), 'returncode'=>$returncode));
				if($row){
					if($row['status']==1){
						$status1+=1;
					}

					if($row['status']==2){
						$status2+=1;
					}
				}
			}

			if(count($billgoods)==$status2)//表示全部已入库
			{
				$wdb->update(array('status'=>3),array('waybillid'=>$waybillid, 'returncode'=>$returncode));//更新运单已入库状态
				
				
				//----------------------------------------------------
				$historydb = pc_base::load_model('waybill_history_model');
				$row = $wdb->get_one(array('waybillid'=>$waybillid, 'returncode'=>$returncode));
				//插入处理记录
				$handle=array();
				$handle['siteid'] = 1;
				$handle['username'] = $this->username;
				$handle['userid'] = $this->userid;
				$handle['addtime'] = SYS_TIME;
				$handle['sysbillid'] = trim($row['sysbillid']);
				$handle['waybillid'] = trim($row['waybillid']);
				$handle['placeid'] = $this->hid;
				$handle['placename'] = $row['storagename'];
				$handle['status'] = 3;
				$handle['remark'] = L('waybill_status'.$handle['status']);	
				$lid = $historydb->insert($handle);
				
				//-----------------------------------------------------	

			}elseif($status2>0)//表示全部入库中
			{
				//$wdb->update(array('status'=>2),array('waybillid'=>$waybillid, 'returncode'=>$returncode));//更新运单入库中状态
			}else{
				$wdb->update(array('status'=>1),array('waybillid'=>$waybillid, 'returncode'=>$returncode));//更新运单未入库状态
			}

		}

	}//end update_all_status


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

}
?>