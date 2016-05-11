<?php 
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('ajax_foreground','member');
pc_base::load_sys_class('format', '', 0);
pc_base::load_sys_class('form', '', 0);
pc_base::load_app_func('global','wap');

class waybill extends ajax_foreground{
	private $hid;
	public $current_user;
	function __construct() {
		$this->db = pc_base::load_model('waybill_model');
		$this->_username = param::get_cookie('_username');
		$this->_userid = intval(param::get_cookie('_userid'));
		$this->siteid =  isset($_REQUEST['siteid']) && trim($_REQUEST['siteid']) ? intval($_REQUEST['siteid']) : 1;
		$this->hid =  isset($_GET['hid']) && trim($_GET['hid']) ? intval($_GET['hid']) : 0;
		$this->current_user = $this->get_current_userinfo();
		if(!isset($this->_userid) || $this->_userid==0 ){
			showmessage(L('please_login', '', 'member'), 'index.php?m=member&c=ajax_index&a=login');
			exit;
		}

		$storagedb = pc_base::load_model('storage_model');
		$srows=$storagedb->get_one(array('aid'=>$this->hid),'title');
		if($srows){
			$this->storagename=trim($srows['title']);
		}

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


/*		if(!is_mobile_or_pc()){
			showmessage("很抱歉！应用仅限移动设备访问！");
			exit;
		}*/
		

	}
	
	public function init() {
		
		$sql = " status!=99 ";

		$status = intval($_REQUEST['status']);

		if(isset($_POST['dosubmit'])) {
			
			$orderno = safe_one_string($_POST['orderno']);
			$starttime = strtotime($_POST['starttime']);
			$endtime = strtotime($_POST['endtime']);

			if($status>0){
				$sql.=" and status='$status' ";
			}
			if(!empty($orderno)){
				$sql.=" and (sysbillid like '%$orderno%' or waybillid like '%$orderno%') ";
			}

			if(!empty($starttime) && !empty($endtime)){
				$sql.=" and addtime>='$starttime'  and addtime<='$endtime' ";
			}
		}

		if($status>0){
			$sql.=" and status='$status' ";
			
			if($status==3){
				$sql .=" and srvtype in(0,1,2) ";
			}
			
		}
		

		

		$sql .= " and  userid='$this->_userid' ";
		
		
		$page = max(intval($_GET['page']), 1);
		$datas = $this->db->listinfo($sql, '`aid` DESC', $page);
		
		$pages= $this->db->pages;

		include template('wap', 'waybill_list');
	}

	public function editline() {
		

		$this->db2 = pc_base::load_model('currency_model');

		$session_storage = 'session_'.pc_base::load_config('system','session_storage');
		pc_base::load_sys_class($session_storage);

		$show_validator = 1;
		$_GET['aid'] = intval($_GET['aid']);
		$aid = $_GET['aid'];
		if(!$_GET['aid']) showmessage(L('illegal_operation'));
		if(isset($_POST['dosubmit'])) {
			$wayrate = $this->getwayrate();
			$_POST['waybill'] = $this->check($_POST['waybill'], 'edit');
		

			$_SESSION["isrepeat"]=1;

		
			$address_db = pc_base::load_model('address_model');
			if($_POST['waybill']['takeaddressid']==0){//�����ջ���ַ,Ҫ���������ַ����
				

				$_POST['take_address']['siteid']=$this->siteid;
				$_POST['take_address']['addresstype']=1;
				$_POST['take_address']['addtime'] = SYS_TIME;
				$_POST['take_address']['username'] = $this->_username;
				$_POST['take_address']['userid'] = $this->_userid;
				
				$uaddr = $address_db->get_one(array('addresstype'=>1,'isdefault'=>1,'userid'=>$this->_userid));
				if(!$uaddr)
					$_POST['take_address']['isdefault']=1;

				$address_db->insert(safe_array_string($_POST['take_address']));
				$address_idd = $address_db->insert_id();
				$_POST['waybill']['takeaddressid'] = $address_idd;
			}


			$takeaddressname='';
			$takeaddr=$address_db->get_one(array('aid'=>$_POST['waybill']['takeaddressid']));
			if($takeaddr){
				$_POST['waybill']['truename'] = $takeaddr['truename'];
				$_POST['waybill']['takename'] = trim($takeaddr['country']);

				$takeaddressname=$takeaddr['truename'].'|'.$takeaddr['mobile'].' '.'|'.$takeaddr['country'].'|'.$takeaddr['province'].'|'.$takeaddr['city'].'|'.$takeaddr['address'].'|'.$takeaddr['postcode'];
			}
			$_POST['waybill']['takeaddressname']=str_replace("&amp;","&",$takeaddressname);
			

			$an_info = $this->db->get_one( array('aid' => $_GET['aid']));
			
			$addFee = $an_info['allvaluedfee']; 
			$otherfee = $an_info['otherfee']; 
			$_POST['waybill']['otherfee'] = $otherfee;

			$payedfee = get_ship_fee($an_info['totalweight'],get_common_shipline($_POST['waybill']['shippingid']),trim($_POST['waybill']['takename']));
			
			if($an_info['status']!=8){
				$_POST['waybill']['status'] = 21;
			}

			//������ֵ����-----------------------------------------------------------------
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

			$_POST['waybill']['addvalues'] = array2string($srv_value_array);
			//-------------------------------------------------------------------------------
				
			$_POST['waybill']['payfeeusd']  = $payedfee+$addFee+floatval($_POST['waybill']['otherfee']);

				$_POST['waybill']['allvaluedfee'] = $addFee;
				$_POST['waybill']['wayrate'] = $wayrate;
				$_POST['waybill']['totalship'] = $payedfee;
				//$_POST['waybill']['weight'] = 0;
				$_POST['waybill']['payedfee'] = ($an_info['taxfee']) + floatval($_POST['waybill']['wayrate']) * floatval($_POST['waybill']['payfeeusd']);	
		
			$_POST['waybill']['handletime'] = SYS_TIME;

			if($this->db->update(($_POST['waybill']), array('aid' => $_GET['aid']))){


				$historydb = pc_base::load_model('waybill_history_model');
			
				//���봦���¼
				$handle=array();
				$handle['siteid'] = 1;
				$handle['username'] = $this->_username;
				$handle['userid'] = $this->_userid;
				$handle['addtime'] = SYS_TIME;
				$handle['sysbillid'] = trim($an_info['sysbillid']);
				$handle['waybillid'] = trim($an_info['waybillid']);
				$handle['placeid'] = $an_info['storageid'];
				$handle['placename'] = $an_info['storagename'];
				$handle['status'] = $_POST['waybill']['status'];
				$historydb->delete(array('status'=>$handle['status'],'waybillid'=>$handle['waybillid'] ));
				$handle['remark'] = L('waybill_status'.$handle['status']);	
				$historydb->insert($handle);
				
				if($an_info['status']==8){
					showmessage(L('waybilld_a'), "/index.php?m=wap&c=waybill&a=init&status=8");
				}else{
					showmessage(L('waybilld_a'), "/index.php?m=wap&c=waybill&a=init");
				}
			}

		} else {
			$_SESSION["isrepeat"]=0;// 

			$where = array('aid' => $_GET['aid']);
			$an_info = $this->db->get_one($where);
			$an_info2 = $this->db2->get_one(array('aid' => 4));
			$shippingtypes = $this->getshippingtype();
			$addresslist = $this->getaddresslist();
			$servicelist = $this->getservicelist();
			
			$storagedb = pc_base::load_model('storage_model');
			$storage_row = $storagedb->get_one(array('aid'=>$an_info['storageid']));
			$udb = pc_base::load_model('member_model');
			$udb->set_model();
			$urow = $udb->get_one(" userid='$this->_userid'");
			$mobile = $urow['mobile'];
			$email = $urow['email'];
			$areadb = pc_base::load_model('linkage_model');
			$area_row=$areadb->get_one(array('linkageid'=>$storage_row['area']));
			$areaname= $area_row['name'];

			

			$waybilltm = str_replace('"date"','"date inp"',form::date('waybill[waybilltime]',date('Y-m-d',$an_info['waybilltime']),''));

			include template('wap', 'waybill_editline');
		}
		
	}



	public function unionbox(){
		$historydb = pc_base::load_model('waybill_history_model');
			if(isset($_POST['dosubmit'])) {
			
			$_oremark = $_POST['remark'];
			
			$aid = isset($_POST['aid']) ? ($_POST['aid']) : array();

			$split_number = isset($_POST['split_number']) ? ($_POST['split_number']) : 0;
			//print_r($_POST);exit;

			//��ֵ����-----------------------------------------------------------------
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
						$otherremark.="#".$row['aid']."����";
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
						$otherremark.="+"."#".$row['aid']."����";
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


						/* ���붩���� */
						$error_no = 0;
						do
						{
								$waybillid = $this->getbill_orderno($org_housecode,$dst_housecode,get__order__counter(),$row['userid'],$row['takeflag']); //��ȡ�¶�����
								$error_no = $this->db->count(array('waybillid'=>trim($waybillid)));
						}
						while ($error_no > 0 ); //����Ƕ������ظ��������ύ����
						
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

		showmessage(L('operation_success'), "?m=wap&c=waybill&a=init");
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

		
		include template('wap', 'waybill_unionbox_pre');
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
		
		
		include template('wap', 'waybill_splitbox_pre');
	}
	

	public function splitbox(){
		$historydb = pc_base::load_model('waybill_history_model');

		if(isset($_POST['dosubmit'])) {
			
			$aid = isset($_POST['aid']) ? ($_POST['aid']) : array();

			$split_number = isset($_POST['split_number']) ? ($_POST['split_number']) : 0;


			$_oremark = $_POST['remark'];

			//��ֵ����-----------------------------------------------------------------
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

					$otherremark=$row['waybillid']."����";
					for($n=0;$n<$split_number;$n++){
						$newrow = $row;
						unset($newrow['waybillid']);
						unset($newrow['sysbillid']);
						unset($newrow['aid']);
						unset($newrow['addvalues']);
						unset($newrow['goodsdatas']);
						unset($newrow['declaredatas']);

						unset($newrow['imagesurl']);

						/* ���붩���� */
						$error_no = 0;
						do
						{
								$waybillid = $this->getbill_orderno($org_housecode,$dst_housecode,get__order__counter(),$row['userid'],$row['takeflag']); //��ȡ�¶�����
								$error_no = $this->db->count(array('waybillid'=>trim($waybillid)));
						}
						while ($error_no > 0 ); //����Ƕ������ظ��������ύ����
						
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

		showmessage(L('operation_success'), "?m=wap&c=waybill&a=init");
	
	}

	private function sendemailwaybill($sysbillid,$statusinfo,$send_email_nofity=1){
			
			$sysres = $this->db->get_one(array('sysbillid'=>$sysbillid));
			if($sysres && $send_email_nofity==1){

			$moduledb = pc_base::load_model('module_model');
			$__mdb = pc_base::load_model('member_model');
			$userinfo=$__mdb->get_one(array('userid'=>$sysres['userid']));

			//$status_array=array(1=>'δ���',2=>'�����',3=>'�����',4=>'�Ѵ���',5=>'�����쳣',6=>'��ȡ��',7=>'�Ѹ���',8=>'������',9=>'�ѳ���',10=>'��ɨ��',11=>'��ת�ɹ�',12=>'����xx�ֿ�',13=>'�ȴ���ȡ',14=>'��ݸ���',15=>'�ȴ��⳥',16=>'��ǩ��',17=>'������');

			//���ʼ�
			pc_base::load_sys_func('mail');
			$member_setting = $moduledb->get_one(array('module'=>'member'), 'setting');
			$member_setting = string2array($member_setting['setting']);
			$url = APP_PATH."index.php?m=waybill&c=index&a=init&hid=".$sysres['storageid'];
			$message = $member_setting['expressnoemail'];
			$message = str_replace(array('{click}','{url}','{no}','{status}'), array('<a href="'.$url.'">'.L('please_click').'</a>',$url,$sysres['waybillid'],$statusinfo), $message);
			sendmail($userinfo['email'], '����˵���'.$sysres['waybillid'].$statusinfo, $message);
			}
	}
	//ǩ��
	public function waybill_finish(){
		
				$bid = isset($_GET['bid']) ? intval($_GET['bid']) : 0;

				$sdb = pc_base::load_model('storage_model');
				$historydb = pc_base::load_model('waybill_history_model');
				
				$way=$this->db->get_one(array('aid'=>$bid));
				if($way['status']!=16){
					if($this->db->update(array('status'=>16),array('aid'=>$bid))){
					
						//���봦���˱�ע
					
						$val=$sdb->get_one(array('aid'=>$way['storageid']));
						//���봦���¼
						$handle=array();
						$handle['siteid'] = $this->siteid;
						$handle['username'] = $this->_username;
						$handle['userid'] = $this->_userid;
						$handle['addtime'] = SYS_TIME;
						$handle['sysbillid'] = trim($way['sysbillid']);
						$handle['waybillid'] = trim($way['waybillid']);
						$handle['placeid'] = $way['storageid'];
						$handle['placename'] = $val['title'];
						$handle['status'] = 16;//��ת�ɹ�
						$handle['remark'] = $this->getonestatus($handle['status']);

						$lid = $historydb->insert($handle);

						//���ʼ�
						$this->sendemailwaybill($handle['sysbillid'],$handle['remark'],1);

					showmessage(L('operation_success'),'/index.php?m=waybill&c=index&a=init&hid='.$way['storageid']);
					exit;
				}

			}else{
				showmessage(L('operation_failure'),'/index.php?m=waybill&c=index&a=init&hid='.$way['storageid']);
					exit;
			}
		
	}

	//����ת�˷�ʽ
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

	//��������
	public function waybill_onlinequestion(){
		$bid = intval($_GET['bid']);
		$info = $this->db->get_one("siteid='".$this->siteid."' and aid='$bid'");
		$waybillid = trim($info['waybillid']);
		$sysbillid = trim($info['sysbillid']);

		$handledb = pc_base::load_model('waybill_history_model');

		$udb = pc_base::load_model('member_model');
		$udb->set_model();
		$urow = $udb->get_one(" userid='$this->_userid'");
		$mobile = $urow['mobile'];
		
		$s_row = $this->getshippingtypename($info['storageid']);
		if($s_row){
			$storagename=$s_row['title'];
		}

		$cdb = pc_base::load_model('enum_model');
		$sql = '`siteid`=\''.$this->siteid.'\' AND groupid=62 ';
		$datas = $cdb->select($sql,'*',10000,'listorder ASC');


		include template('wap', 'waybill_onlinequestion');	
	}

	//�˵��б�
	public function waybill_detail(){
		$bid = intval($_GET['bid']);
		$info = $this->db->get_one("siteid='".$this->siteid."' and aid='$bid'");
		$waybillid = trim($info['waybillid']);
		$sysbillid = trim($info['sysbillid']);

		$handledb = pc_base::load_model('waybill_history_model');

		$udb = pc_base::load_model('member_model');
		$udb->set_model();
		$urow = $udb->get_one(" userid='$this->_userid'");
		$mobile = $urow['mobile'];
		
		$s_row = $this->getshippingtypename($info['storageid']);
		if($s_row){
			$storagename=$s_row['title'];
		}

		$factweight=$info['factweight'];//ʵ������
		$volumeweight=$info['volumeweight'];//ʵ������
		$totalship = 0;//���˷�
	    
		$servicelist = $this->getservicelist($waybillid);

			
		$handledata = $handledb->select("sysbillid='$sysbillid' ","*",1000,"addtime asc");


		include template('wap', 'waybill_detail');	
	}

	//�˵������б�
	public function waybill_paydetail(){
		$bid = intval($_GET['bid']);
		$info = $this->db->get_one("siteid='".$this->siteid."' and aid='$bid'");
		
		$udb = pc_base::load_model('member_model');
		$udb->set_model();
		$urow = $udb->get_one(" userid='$this->_userid'");
		$mobile = $urow['mobile'];

		$s_row = $this->getshippingtypename($info['storageid']);
		if($s_row){
			$storagename=$s_row['title'];
		}

		include template('wap', 'waybill_paydetail');	
	}

	//��ӡ

	public function printbill(){
		$bid = intval($_GET['bid']);
		$info = $this->db->get_one("siteid='".$this->siteid."' and aid='$bid'");
		
		$udb = pc_base::load_model('member_model');
		$udb->set_model();
		$urow = $udb->get_one(" userid='".$info['userid']."'");
		$mobile = $urow['mobile'];

		

		$send_truename="";
		$send_country="";
		$send_province="";
		$send_city="";
		$send_address="";
		$send_postcode="";
		$send_mobile="";

		$take_truename="";
		$take_country="";
		$take_province="";
		$take_city="";
		$take_address="";
		$take_postcode="";
		$take_mobile="";

		$sendaddressname = 	$info['sendaddressname'];
		$takeaddressname = 	$info['takeaddressname'];

			

				$addr=explode("|",$sendaddressname);//����
				$send_truename=$addr[0];
				$send_country=$addr[2];
				$send_province=$addr[3];
				$send_city=$addr[4];
				$send_address=$addr[5];
				$send_postcode=$addr[6];
				$send_mobile=$addr[1];
				//$send_company=$addr['company'];

				$addr=explode("|",$takeaddressname);///�ռ�
				$take_truename=$addr[0];
				$take_country=$addr[2];
				$take_province=$addr[3];
				$take_city=$addr[4];
				$take_address=$addr[5];
				$take_postcode=$addr[6];
				$take_mobile=$addr[1];
				//$take_company=$addr['company'];
			
		

		include template('wap', 'waybill_print');

	}
	
	//��ȡ�ͻ����������˵���
	public function getbillcode($num){
		
		$row = $this->db->get_one("siteid='".$this->siteid."' and userid='$this->_userid'","*","addtime desc");
		//UK1000000100001KD2
		if($row){
			if($num==7)//�ͻ�
				$sourcenumber=intval(substr($row['waybillid'],3,$num))+1;
			else
				$sourcenumber=intval(substr($row['waybillid'],10,$num))+1;
		}else{
				$sourcenumber=1;
		}

		if($num==7){//�ͻ�
			return substr(strval($sourcenumber+10000000),1,$num);
		}else{//����
			return substr(strval($sourcenumber+100000),1,$num);
		}
	}


	//two bill no
	public function getbill_orderno($org_h,$dst_h,$wid=0){
		$userid = $this->_userid;
		if(!is_numeric($org_h)){$org_h=100;}
		
		$num = $userid+10000+$wid;

		$ordercode = substr($org_h,0,3)+substr((date('Y')-10),2,2).date('m').str_pad($num,8,"0",STR_PAD_LEFT);
		
		return $ordercode;
	}

	public function add() {
		
		$package_array = $_POST['aid'];
		$session_storage = 'session_'.pc_base::load_config('system','session_storage');
		pc_base::load_sys_class($session_storage);

		$show_validator = 1;
		if(isset($_POST['dosubmit'])) {

			$this->hid = intval($_POST['waybill']['storageid']);

			$_POST['waybill'] = $this->check($_POST['waybill']);
			
			if($_SESSION["isrepeat"] !=0){
				

				showmessage(L('operaction_repeat'), "/index.php?m=wap&c=waybill&a=init&hid=".$this->hid);
				exit;
					
			}

			$_SESSION["isrepeat"]=1;
			
			$packagedb = pc_base::load_model('package_model');
			$gdb = pc_base::load_model('waybill_goods_model');
			

			//if package isexsits
			$repeatdata='';
			foreach($_POST['waybill_goods'] as $key=>$v){

				$expressno = safe_one_string(trim($_POST['waybill_goods'][$key]['expressno']));
				$expressno=trim($expressno);
				$pack = $gdb->get_one(array('expressno'=>trim($expressno)));
				if($pack){
					$repeatdata.=$expressno.'<br/>';
				}
			}

			if(!empty($repeatdata)){
				

				showmessage(L('operaction_packagerepeat').$repeatdata);
				exit;
					
			}

			//�����˵���--begin
			/**
				�˵����ɹ���18λ:   �ֿ����������ĸ��һ������  �ͻ�����7λ���ֻ���ĸ  �����˵���5λ���ֻ���ĸ  Ŀ�ĵص�ַ����2λ��ĸ��һ������ 
			**/
			$waybillid="";
			$org_housecode = "000";//�ֿ����3λ //������ (���Һ͵���)
			$dst_housecode = "000";//�ֿ����3λ //�ջ��� (���Һ͵���)
			$allsendlists = $this->getsendlists();
			foreach($allsendlists as $addr){
				if(intval($_POST['waybill']['sendid'])==$addr['linkageid']){
					$org_housecode = $addr['storagecode'];
				}
				if(intval($_POST['waybill']['takeid'])==$addr['linkageid']){
					$dst_housecode = $addr['storagecode'];
				}
			}

			$seven=str_pad(dechex($this->_userid),7,'0',STR_PAD_LEFT);//�ͻ�����7λ
			$five=$this->getbillcode(5);//���˴���5λ
			$waybillid=$org_housecode.$seven.$five.$dst_housecode;
			//�����˵��� ����end

			//����ϵͳ������
			$sitedb = pc_base::load_model('site_model');
			$_rs = $sitedb->get_one("siteid='".$this->siteid."'");
			$billprefix="";
			if($_rs){
				$billprefix=$_rs['billprefix'];
			}
			$sysbillid=$billprefix.date('ymdHis',time()).floor(microtime()*1000);
			//--------------end

			$waybillid = common____orderno();
			

			$_POST['waybill']['waybillid']=$waybillid;
			$_POST['waybill']['sysbillid']=$sysbillid;
			//$_POST['waybill']['status']=1;//δ���
			
			$address_db = pc_base::load_model('address_model');
			$_POST['waybill']['storageid'] = intval($_POST['storageidd']);

			$_POST['waybill']['status']=1;//δ���
			
			$address_db = pc_base::load_model('address_model');
			if($_POST['waybill']['takeaddressid']==0){//�����ջ���ַ,Ҫ���������ַ����
				
				$_POST['take_address']['siteid']=$this->siteid;
				$_POST['take_address']['addresstype']=1;
				$_POST['take_address']['addtime'] = SYS_TIME;
				$_POST['take_address']['username'] = $this->_username;
				$_POST['take_address']['userid'] = $this->_userid;
				

				$address_db->insert(safe_array_string($_POST['take_address']));
				$address_idd = $address_db->insert_id();
				
				$_POST['waybill']['takeaddressid'] = $address_idd;
			}



			//�����ַ
			$sendaddressname='';
			$sendaddr=$address_db->get_one(array('aid'=>2));
			if($sendaddr){
					$sendaddressname=$sendaddr['truename'].'|'.$sendaddr['mobile'].' '.'|'.$sendaddr['country'].'|'.$sendaddr['province'].'|'.$sendaddr['city'].'|'.$sendaddr['address'].'|'.$sendaddr['postcode'];
			}
			$_POST['waybill']['sendaddressname']=str_replace("&amp;","&",$sendaddressname);

			$takeaddressname='';
			$takeaddr=$address_db->get_one(array('aid'=>$_POST['waybill']['takeaddressid']));
			if($takeaddr){
				
				$_POST['waybill']['takename'] = trim($takeaddr['country']);
				$_POST['waybill']['truename'] = $takeaddr['truename'];

				$takeaddressname=$takeaddr['truename'].'|'.$takeaddr['mobile'].' '.'|'.$takeaddr['country'].'|'.$takeaddr['province'].'|'.$takeaddr['city'].'|'.$takeaddr['address'].'|'.$takeaddr['postcode'];
			}
			$_POST['waybill']['takeaddressname']=str_replace("&amp;","&",$takeaddressname);

			//������ֵ����-----------------------------------------------------------------
				$addFee=0;

			
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

			$_POST['waybill']['addvalues'] = array2string($srv_value_array);
			$_POST['waybill']['goodsdatas'] = array2string($_POST['waybill_goods']);
			//-------------------------------------------------------------------------------
			//truename 
			//$_POST['waybill']['truename'] = $this->current_user['truename'].$this->current_user['firstname'];
			//$_POST['waybill']['storagename'] =  $this->storagename;

			$goods_names = "";
			foreach($_POST['waybill_goods'] as $key=>$v){
				if(empty($goods_names))
				$goods_names = safe_one_string(trim($_POST['waybill_goods'][$key]['goodsname']));	
				else
				$goods_names .= "_".safe_one_string(trim($_POST['waybill_goods'][$key]['goodsname']));
			}
			
			$_POST['waybill']['goodsname'] = $goods_names;
			$_POST['waybill']['takecode'] = $this->current_user['clientcode'];
			$_POST['waybill']['takeflag'] = $this->current_user['clientname'];

			$payedfee = 0;
			
		
			$wayrate = $this->getwayrate();
			
			$_POST['waybill']['payfeeusd']  = 0;

			$_POST['waybill']['allvaluedfee'] = $addFee;
			$_POST['waybill']['wayrate'] = $wayrate;
			$_POST['waybill']['totalship'] = $payedfee;
			$_POST['waybill']['payedfee'] = floatval($_POST['waybill']['wayrate']) * floatval($_POST['waybill']['payfeeusd']);


			if($this->db->insert($_POST['waybill'])){
				
				$lastinsertid = $this->db->insert_id();
				
				
				$historydb = pc_base::load_model('waybill_history_model');
			
				//���봦���¼
				$handle=array();
				$handle['siteid'] = 1;
				$handle['username'] = $this->current_user['username'];
				$handle['userid'] = $this->current_user['userid'];
				$handle['addtime'] = SYS_TIME;
				$handle['sysbillid'] = trim($_POST['waybill']['sysbillid']);
				$handle['waybillid'] = trim($_POST['waybill']['waybillid']);
				$handle['placeid'] = $_POST['waybill']['storageid'];
				$handle['placename'] = $_POST['waybill']['storagename'];
				$handle['status'] = 1;
				$handle['remark'] = L('waybill_status'.$handle['status']);	
				$historydb->insert($handle);
				
				showmessage(L('waybillment_successful_added'), "/index.php?m=wap&c=waybill&a=init&hid=".$this->hid);
					
			}
		} else {

			$shippingtypes = $this->getshippingtype();
			$addresslist = $this->getaddresslist();
			$servicelist = $this->getservicelist();
			$_SESSION["isrepeat"]=0;
			$waybilltm = str_replace('"date"','"date inp"',form::date('waybill[waybilltime]','',''));
			

			$storagedb = pc_base::load_model('storage_model');
			$storage_row = $storagedb->get_one(array('aid'=>$this->hid));
			$udb = pc_base::load_model('member_model');
			$udb->set_model();
			$urow = $udb->get_one(" userid='$this->_userid'");
			$mobile = $urow['mobile'];
			$email = $urow['email'];
			$areadb = pc_base::load_model('linkage_model');
			$area_row=$areadb->get_one(array('linkageid'=>$storage_row['area']));
			$areaname= $area_row['name'];

			$allturnway=$this->getturnway();
			$allship = $this->getallship();


			include template('wap', 'waybill_add');
		}

		
	}


	public function edit() {
		$allship = $this->getallship();
		$session_storage = 'session_'.pc_base::load_config('system','session_storage');
		pc_base::load_sys_class($session_storage);

		$show_validator = 1;
		$_GET['aid'] = intval($_GET['aid']);
		$aid = $_GET['aid'];
		if(!$_GET['aid']) showmessage(L('illegal_operation'));
		if(isset($_POST['dosubmit'])) {
			

			$_POST['waybill'] = $this->check($_POST['waybill'], 'edit');
			/*if($_SESSION["isrepeat"] !=0){
				showmessage(L('operaction_repeat'), "/index.php?m=wap&c=waybill&a=init&hid=".$this->hid);
				exit;
			}

			$_SESSION["isrepeat"]=1;*/

			$address_db = pc_base::load_model('address_model');
			if($_POST['waybill']['takeaddressid']==0){//�����ջ���ַ,Ҫ���������ַ����
				

				$_POST['take_address']['siteid']=$this->siteid;
				$_POST['take_address']['addresstype']=1;
				$_POST['take_address']['addtime'] = SYS_TIME;
				$_POST['take_address']['username'] = $this->_username;
				$_POST['take_address']['userid'] = $this->_userid;
				
				$uaddr = $address_db->get_one(array('addresstype'=>1,'isdefault'=>1,'userid'=>$this->_userid));
				if(!$uaddr)
					$_POST['take_address']['isdefault']=1;

				$address_db->insert(safe_array_string($_POST['take_address']));
				$address_idd = $address_db->insert_id();
				$_POST['waybill']['takeaddressid'] = $address_idd;
			}
			/*

			if($_POST['waybill']['sendaddressid']==0){//����������ַ,Ҫ���������ַ����
				
				$_POST['send_address']['siteid']=$this->siteid;
				$_POST['send_address']['addtime'] = SYS_TIME;
				$_POST['send_address']['username'] = $this->_username;
				$_POST['send_address']['userid'] = $this->_userid;

				$uaddr = $address_db->get_one(array('addresstype'=>2,'isdefault'=>1,'userid'=>$this->_userid));
				if(!$uaddr)
					$_POST['take_address']['isdefault']=1;

				$address_db->insert(safe_array_string($_POST['send_address']));
				$address_sidd = $address_db->insert_id();
				$_POST['waybill']['sendaddressid'] = $address_sidd;
			}*/

			//�����ַ
			$sendaddressname='';
			$sendaddr=$address_db->get_one(array('aid'=>2));
			if($sendaddr){
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

			//������ֵ����-----------------------------------------------------------------
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
			$_POST['waybill']['allvaluedfee'] = $addFee;
			$_POST['waybill']['addvalues'] = array2string($srv_value_array);
			//-------------------------------------------------------------------------------
			

			$goods_names = "";
			foreach($_POST['waybill_goods'] as $key=>$v){
				if(empty($goods_names))
				$goods_names = safe_one_string(trim($_POST['waybill_goods'][$key]['goodsname']));	
				else
				$goods_names .= "_".safe_one_string(trim($_POST['waybill_goods'][$key]['goodsname']));
			}
			
			$_POST['waybill']['goodsname'] = $goods_names;
			$_POST['waybill']['goodsdatas'] = array2string($_POST['waybill_goods']);

			$an_info = $this->db->get_one(array('aid' => $_GET['aid']));

			$payedfee = get_ship_fee($_POST['waybill']['totalweight'],get_common_shipline($_POST['waybill']['shippingid']),trim($an_info['takename']));
			
			
				
			
		
				$wayrate = $this->getwayrate();
			$_POST['waybill']['payfeeusd']  = $payedfee+$addFee+floatval($_POST['waybill']['otherfee']);

				$_POST['waybill']['allvaluedfee'] = $addFee;
				$_POST['waybill']['wayrate'] = $wayrate;
				$_POST['waybill']['totalship'] = $payedfee;
				$_POST['waybill']['payedfee'] = ($an_info['taxfee']) + floatval($_POST['waybill']['wayrate']) * floatval($_POST['waybill']['payfeeusd']);


			if($this->db->update(($_POST['waybill']), array('aid' => $_GET['aid']))){
				
				showmessage(L('waybilld_a'), "/index.php?m=wap&c=waybill&a=init&hid=".$this->hid);
					
			}

		} else {
			$_SESSION["isrepeat"]=0;// 

			$where = array('aid' => $_GET['aid']);
			$an_info = $this->db->get_one($where);
			$shippingtypes = $this->getshippingtype();
			$addresslist = $this->getaddresslist();
			$servicelist = $this->getservicelist();
			
			$storagedb = pc_base::load_model('storage_model');
			$storage_row = $storagedb->get_one(array('aid'=>$an_info['storageid']));
			$udb = pc_base::load_model('member_model');
			$udb->set_model();
			$urow = $udb->get_one(" userid='$this->_userid'");
			$mobile = $urow['mobile'];
			$email = $urow['email'];
			$areadb = pc_base::load_model('linkage_model');
			$area_row=$areadb->get_one(array('linkageid'=>$storage_row['area']));
			$areaname= $area_row['name'];

			

			$waybilltm = str_replace('"date"','"date inp"',form::date('waybill[waybilltime]',date('Y-m-d',$an_info['waybilltime']),''));

			include template('wap', 'waybill_edit');
		}
		
	}


	/**
	 * ����ɾ��ԤԼ
	 */
	public function delete($aid = 0) {

		if((!isset($_POST['aid']) || empty($_POST['aid'])) && !$aid) {
			showmessage(L('illegal_operation'));
		} else {
			if(is_array($_POST['aid']) && !$aid) {
				array_map(array($this, 'delete'), $_POST['aid']);
				header("Location:".HTTP_REFERER);
			} elseif($aid) {
				$aid = intval($aid);
				$waybillid="";
				$row = $this->db->get_one(array('aid' => $aid));
				if($row)
				{
					if($row['status']==1){
					$waybillid=$row['waybillid'];	
				
					$this->db->delete(array('aid' => $aid));
					
					//ɾ���ӹ�������
					$gdb = pc_base::load_model('waybill_goods_model');//�˵���Ʒ
					$srvdb = pc_base::load_model('waybill_serviceitem_model');//�˵�������
					$historydb = pc_base::load_model('waybill_history_model');//��ʷ��¼
					/*$packdb = pc_base::load_model('package_model');//����

					$packall = $gdb->select(array('waybillid' => $waybillid),"*",1000,"");
					foreach($packall as $pack){
						$expressno= trim($pack['expressno']);
						$packdb->delete(array('expressno' => $expressno));
					}*/

					$gdb->delete(array('waybillid' => $waybillid));
					$srvdb->delete(array('waybillid' => $waybillid));
					$historydb->delete(array('waybillid' => $waybillid));

					}

				}

			}
		}
	}
	
	/**
	 * ��֤������
	 * @param  		array 		$data ����������
	 * @param  		string 		$a ����Ϊ�������ʱ���Զ�����ȱʧ�����ݡ�
	 * @return 		array 		��֤�������
	 */
	private function check($data = array(), $a = 'add') {
		//if($data['truename']=='') showmessage(L('truename_cannot_empty'));
		
		//$r = $this->db->get_one(array('truename' => $data['truename']));
		
		
		if ($a=='add') {
			/*if (is_array($r) && !empty($r)) {
				showmessage(L('waybill_exist'), HTTP_REFERER);
			}*/
			$data['siteid'] = $this->siteid;
			$data['addtime'] = SYS_TIME;
			$data['username'] = $this->_username;
			$data['userid'] = $this->_userid;
			$data['storageid'] = $this->hid;
			
		} else {
			/*if ($r['aid'] && ($r['aid']!=$_GET['aid'])) {
				showmessage(L('waybill_exist'), HTTP_REFERER);
			}*/
		}

		

		return $data;
	}


	//�����˵�״̬
	private function updatewaybill_status($waybillid){
		$billgoods = $this->getwaybill_goods($waybillid);
		$pdb = pc_base::load_model('package_model');
		$wdb = pc_base::load_model('waybill_model');
		$status1=0;
		$status2=0;
		
		
		foreach($billgoods as $val){
			$row = $pdb->get_one(array('expressno'=>$val['expressno']));
			if($row['status']==1){
				$status1+=1;
			}

			if($row['status']==2){
				$status2+=1;
			}
		}

		if(count($billgoods)==$status2)//��ʾȫ�������
		{
			$wdb->update(array('status'=>3),array('waybillid'=>$waybillid));//�����˵������״̬
		}elseif($status2>0)//��ʾȫ�������
		{
			$wdb->update(array('status'=>2),array('waybillid'=>$waybillid));//�����˵������״̬
		}else{
			$wdb->update(array('status'=>1),array('waybillid'=>$waybillid));//�����˵������״̬
		}

	}


	/**�����˵��Ż�ȡ�˵���Ʒ��ϸ**/
	private function getwaybill_goods($waybillid){
		$cdb = pc_base::load_model('waybill_goods_model');
		$sql = "  waybillid='$waybillid'  ";
		$datas = $cdb->select($sql,'*',10000,'number asc');
		return $datas;
	}

	/**�����˵��Ż�ȡ��ֵ����**/
	private function getwaybill_servicelist($waybillid,$waybill_goodsid){
		$cdb = pc_base::load_model('waybill_serviceitem_model');
		$sql = " waybillid='$waybillid' and waybill_goodsid='$waybill_goodsid'";
		$datas = $cdb->select($sql,'*',10000,'id desc');
		return $datas;
	}

	

	/**��ȡת�����**/
	private function getshippingtype(){
		$cdb = pc_base::load_model('storage_model');
		$sql = ' siteid=\''.$this->siteid.'\' ';
		$datas = $cdb->select($sql,'*',10000,'listorder ASC');
		return $datas;
	}

	/**��ȡת���������**/
	private function getshippingtypename($typeid){
		$cdb = pc_base::load_model('storage_model');
		$sql = ' siteid=\''.$this->siteid.'\' and  aid='.$typeid.'  ';
		$datas = $cdb->get_one($sql);
		return $datas;
	}
	
	/**��ȡ�ջ���ַ**/
	private function getaddresslist(){
		$cdb = pc_base::load_model('address_model');
		$sql = ' siteid=\''.$this->siteid.'\' and userid='.$this->_userid.' ';
		$datas = $cdb->select($sql,'*',10000,'addtime desc');
		return $datas;
	}

	/**��ȡ��ֵ����**/
	private function getservicelist(){
		$cdb = pc_base::load_model('service_model');
		$sql = ' siteid=\''.$this->siteid.'\' and type=\'value-added\'';
		$datas = $cdb->select($sql,'*',10000,'addtime desc');
		return $datas;
	}



	/** ��ȡ������ (���Һ͵���) **/
	private function getsendlists(){
		$cdb = pc_base::load_model('storage_model');
		$sql = ' siteid=\''.$this->siteid.'\'  ';
		$results = $cdb->select($sql,'*',10000,'listorder ASC');

		$datas = array();
		$lines = $this->getline();
		foreach($lines as $k=>$v){
			foreach($results as $kk=>$row){
				if($v['linkageid']==$row['area']){
					$v['storagecode']=$row['storagecode'];
					$datas[]=$v;
				}
			}
		}
		return $datas;
	}

	/** ��ȡ�ջ��� (���Һ͵���) **/
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

	/**��ȡ���ҵ���**/
	private function getline(){
		$cdb = pc_base::load_model('linkage_model');
		$sql = ' parentid=0 AND keyid=0 ';
		$datas = $cdb->select($sql,'*',10000,'listorder ASC');
		return $datas;
	}

	/**��ȡת�˷�ʽ**/
	private function getturnway(){
		$cdb = pc_base::load_model('shipline_model');
		$sql = ' siteid=\''.$this->siteid.'\' ';
		$datas = $cdb->select($sql,'*',10000,'');
		return $datas;
	}

	

	/**��ȡ���л��˹�˾**/
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

	
	//��ȡ��ֵ��������
	private function getvaluetype($val){
		$valname="";
		foreach($this->valuecategory() as $r){
			if($r['value']==$val)
				$valname=$r['title'];
		}
		return $valname;
	}

	/**��ȡ���л���**/
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

	/**��ȡ����Ĭ�ϻ��ҵ�λ**/
	private function getdefaultcurrency(){
		$cdb = pc_base::load_model('currency_model');
		$sql = '`siteid`=\''.$this->siteid.'\' AND `isdefault`=1';
		$datas = $cdb->get_one($sql);

		
		return $datas;
	}

	/**��ȡ���е�λ**/
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

	/**��ȡ���ж��������ֵ����**/
	private function getgoodsservice(){
		$cdb = pc_base::load_model('service_model');
		$sql = '`siteid`=\''.$this->siteid.'\' AND type=\'order\' ';
		$datas = $cdb->select($sql,'*',10000,'');
		return $datas;
	}
	

	//����ID���ػ�������
	private function getcurrencyname($cid){
		

		return $this->getcurrency($cid);
	}

	//����ID���ص�λ����
	private function getunitname($cid){
		
		return $this->getunits($cid);
	}

	/**��ȡ���ж���״̬**/
	private function getorderstatus(){
		$cdb = pc_base::load_model('enum_model');
		$sql = '`siteid`=\''.$this->siteid.'\' AND groupid=9 and value!=99 ';
		$datas = $cdb->select($sql,'*',10000,'listorder ASC');
		return $datas;
	}

	/**��ȡ��������״̬**/
	private function getonestatus($id,$storageid=0){
		$cdb = pc_base::load_model('enum_model');
		$sql = '`siteid`=\''.$this->siteid.'\' AND groupid=9 and value=\''.$id.'\' ';
		$datas = $cdb->get_one($sql);
		
		
			$statusname="";
			if($id==12 && $storageid>0)//����xx�ֿ�
			{
				$statusname=str_replace(L('enter_xxstorage'),$this->getstoragename($storageid),$datas['title']);
			}else{
				$statusname=$datas['title'];
			}
	

		return $statusname;
	}

	//��ȡ��ǰ�ֿ�
	public function getstoragename($storageid){
		$udb = pc_base::load_model('storage_model');
		$r = $udb->get_one("aid='$storageid' ");
		return $r['title'];
	}



	/**��ȡ���е�ǰ�û�����**/
	private function getmypackage($package_array){
		$cdb = pc_base::load_model('package_model');
		$datas=array();
		if(is_array($package_array)){
			
		$sql = " siteid='$this->siteid'  and userid='$this->_userid' and status!=3  and aid in(".implode(',',$package_array).")";
		
		$datas = $cdb->select($sql,'*',10000,'addtime desc');
		}
		return $datas;
	}

	/**���ص�ǰ�û�������ɫ **/
	private function getpackage_color($expressno){

		$expcolor=array();
		$expcolor[0]='';
		$expcolor[1]='#FF6600';
	
		$flag=0;
		$cdb = pc_base::load_model('waybill_goods_model');
		$pdb = pc_base::load_model('package_model');
		$row = $cdb->get_one(array("expressno"=>$expressno));
		if($row){
			$wrs = $this->db->get_one(array('waybillid'=>trim($row['waybillid'])));
			if($wrs){
				$rs = $pdb->get_one(array("expressno"=>$expressno));
				if($rs['status']!=1){
					$flag=1;
				}

			}
		}
		

		return $expcolor[$flag];
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

	
}
?>