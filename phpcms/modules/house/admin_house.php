<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin','admin',0);
pc_base::load_sys_class('format', '', 0);
pc_base::load_sys_class('form', '', 0);
pc_base::load_sys_class('reader', '', 0);
pc_base::load_app_func('global');

class admin_house extends admin {

	private $db; public $username;
	public $hid;
	public $kbdb;
	public $wbdb;
	public $packagedb;
	public $whuodandb;
	public $historydb;
	public function __construct() {
		parent::__construct();
		//if (!module_exists(ROUTE_M)) showmessage(L('module_not_exists'));
		$this->username = param::get_cookie('admin_username');
		$this->userid = param::get_cookie('admin_userid');
		$this->db = pc_base::load_model('house_model');
		$this->kbdb = pc_base::load_model('waybill_kaban_model');
		$this->wbdb = pc_base::load_model('waybill_model');
		$this->hid=isset($_GET['hid']) ? intval($_GET['hid']) : 0;
		$this->packagedb = pc_base::load_model('package_model');
		$this->whuodandb = pc_base::load_model('waybill_huodan_model');
		$this->historydb = pc_base::load_model('waybill_history_model');
	}
	
	public function init() {
		//仓库配置列表
		$sql = '';

		$sql = '  `siteid`=\''.$this->get_siteid().'\'';
		
		$page = max(intval($_GET['page']), 1);
		$data = $this->db->listinfo($sql, '`aid` DESC', $page);
		$big_menu = array('javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=house&c=admin_house&a=add\', title:\''.L('house_add').'\', width:\'500\', height:\'350\', lock:true}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'add\'}).close()});void(0);', L('house_add'));
		include $this->admin_tpl('house_list');
	}

	
	public function export_clearance(){
		
		
		$keywords = isset($_GET['keywords']) ? $_GET['keywords'] : '';
		$start_time = isset($_GET['start_time']) ? $_GET['start_time'] : '';
		$end_time = isset($_GET['end_time']) ? $_GET['end_time'] : '';

		$sql = "`siteid`='".$this->get_siteid()."' and status=9 ";

		if($start_time!='' && $end_time!=''){
			$sql.=" and addtime>='".strtotime($start_time.' 00:00:01')."' and addtime<='".strtotime($end_time.' 23:59:59')."' ";
		}


		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
		
		
		
		
		$datas = $this->wbdb->select($sql, '*', 10000,'addtime desc');
		

		
		require_once PHPCMS_PATH.'api/PHPExcel.php';       
		require_once PHPCMS_PATH.'api/PHPExcel/Writer/Excel5.php';  
			



			
			$objPHPExcel = new PHPExcel();
			$objPHPExcel->setActiveSheetIndex(0);    
			$objActSheet = $objPHPExcel->getActiveSheet(); 
			//设置当前活动sheet的名称
			$objActSheet->setTitle("清关单");

			//序号	单号	发货人	收货人	收货人电话	收货人地址	货物重量	货物详单	国内快递

			$excelHeader=array('序号','单号','发货人','收货人','收货人电话','收货人地址','货物重量','货物详单','国内快递');

			foreach($excelHeader as $k=>$v){
				$objActSheet->setCellValueByColumnAndRow($k,1,$v);
			}
			
			
			$rownum=1;
			$_rownumber=0;
			
			
			foreach($datas as $key=>$row){
					
					
					$key= trim($row['waybillid']);
					
					$addr=explode("|",$row['sendaddressname']);//发件
					$send_truename=$addr[0];
					$send_country=$addr[2];
					$send_province=$addr[3];
					$send_city=$addr[4];
					$send_address=$addr[5];
					$send_postcode=$addr[6];
					$send_qu=$addr[7];
					$send_mobile=$addr[1];
					$sendaddress=str_replace("|","&nbsp;&nbsp;",$row['sendaddressname']);
					$send_addrs = $send_province.$send_city.$send_qu.$send_address.$send_postcode;
					//$send_company=$addr['company'];

					$addr=explode("|",$row['takeaddressname']);///收件
					$take_truename=$addr[0];
					$take_country=$addr[2];
					$take_province=$addr[3];
					$take_city=$addr[4];
					$take_address=$addr[5];
					$take_postcode=$addr[6];
					$take_qu=$addr[7];
					$take_mobile=$addr[1];
					$takeaddress=str_replace("|","&nbsp;&nbsp;",$row['takeaddressname']);
					$take_addrs = $take_province.$take_city.$take_qu.$take_address.$take_postcode;
					

					
					$rownum+=1;
					$objActSheet->setCellValueByColumnAndRow(0,$rownum,($rownum-1));
					$objActSheet->setCellValueByColumnAndRow(1,$rownum,$key." ");
					$objActSheet->setCellValueByColumnAndRow(2,$rownum,$send_truename);
					$objActSheet->setCellValueByColumnAndRow(3,$rownum,$take_truename);
					$objActSheet->setCellValueByColumnAndRow(4,$rownum,$take_mobile);
					$objActSheet->setCellValueByColumnAndRow(5,$rownum,$take_addrs);
					$objActSheet->setCellValueByColumnAndRow(6,$rownum,$row['totalweight']);
					$objActSheet->setCellValueByColumnAndRow(7,$rownum,$row['goodsname']."*".$row['totalamount']);
					$objActSheet->setCellValueByColumnAndRow(8,$rownum,$row['expressno']);
					

					
			}
			
			
			

			$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
			//在浏览器导出
			header("Content-Type: application/force-download");
			header('Content-Type: application/vnd.ms-excel');
			header("Content-Disposition: attachment;filename=\"".iconv("UTF-8","GB2312","Clearance_".$key)."_".date('ymdH').".xls\"");
			header('Cache-Control: max-age=0');
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
			header("Pragma: no-cache");
			$objWriter->save("php://output");
		
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		
		


	}

	
	public function batchclearance(){
	
		$ids_array = isset($_POST['aid']) ? ($_POST['aid']) : showmessage(L('operation_failure'), HTTP_REFERER);
		$ids = implode(',',$ids_array);
		$historydb = pc_base::load_model('waybill_history_model');
		$datas = $this->whuodandb->select(" id in($ids) ","*",1000);
		foreach($datas as $huodan){
			$waybillid_arr = explode("\n",trim($huodan['kabanno']));
			foreach($waybillid_arr as $_cwb){
				$cwb = trim($_cwb);
				$cwb_array=array();
				$cwb_array['status'] = 12;//  清关中
				$this->wbdb->update($cwb_array,array('waybillid'=>$cwb));


				//插入处理记录
					$handle=array();
					$handle['siteid'] = $this->get_siteid();
					$handle['username'] = $this->username;
					$handle['userid'] = $this->userid;
					$handle['addtime'] = SYS_TIME;
					$handle['sysbillid'] = $this->get_way_sysbillid($cwb);
					$handle['waybillid'] = $cwb;
					$handle['placeid'] = $huodan['storageid'];
					$handle['placename'] = $huodan['storagename'];
					$handle['status'] = $cwb_array['status'];// 清关中
					$handle['remark'] = $this->getwaybilltatus($handle['status'],$handle['placeid']).$_POST['data']['remark'];

					
					$historydb->insert($handle);


			}

			$this->whuodandb->update(array('handle_status'=>$cwb_array['status']),array('huodanno'=>trim($huodan['huodanno'])));
		}

		showmessage(L('operation_success'), HTTP_REFERER);
		
	}
	
	//删除处理信息
	public function house_history_delete(){
		$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		$this->historydb->delete(array('id'=>$id));
		showmessage(L('operation_success'), HTTP_REFERER);
	}
	
	/**返回当前用户包裹颜色 **/
	private function getpackage_color($expressno, $returncode=''){

		$expcolor=array();
		$expcolor[0]='';
		$expcolor[1]='#FF6600';
	
		$flag=0;
		$cdb = pc_base::load_model('waybill_goods_model');
		$pdb = pc_base::load_model('package_model');
		$row = $cdb->get_one(array("expressno"=>$expressno,'returncode'=>$returncode));
		if($row){
			$wrs = $this->wbdb->get_one(array('waybillid'=>trim($row['waybillid']),'returncode'=>$returncode));
			if($wrs){
				$rs = $pdb->get_one(array("expressno"=>$expressno,'returncode'=>$returncode));
				if($rs['status']!=1){
					$flag=1;
				}

			}
		}
		

		return $expcolor[$flag];
	}

	/**获取所有货运公司**/
	private function getallship2($id=0){
		$cdb = pc_base::load_model('enum_model');
		
			$sql = '  groupid=1 and aid=\''.$id.'\'';
			$datas = $cdb->get_one($sql);
			return $datas;
		
	}
	//删除发货单
	public function house_huodan_delete($aid=0){
			if((!isset($_POST['aid']) || empty($_POST['aid'])) && !$aid) {
				showmessage(L('illegal_operation'));
			} else {
				if(is_array($_POST['aid']) && !$aid) {
					array_map(array($this, 'house_huodan_delete'), $_POST['aid']);
					showmessage(L('operation_success'), HTTP_REFERER);
				} elseif($aid) {
					$aid = intval($aid);
					$this->whuodandb->delete(array('id' => $aid));
				}
			}
	}

	//退回上一步
	public function house_jihuo_back(){
		$sta = isset($_GET['sta']) ? intval($_GET['sta']) : 0;
		$kid = isset($_GET['kid']) ? intval($_GET['kid']) : 0;
		switch($sta){
			case 10://从已处理卡板退回上一步,
				$allkb = $this->kbdb->get_one(array('id'=>$kid));
				if($allkb){
					$allwaybill = explode("\n",trim($allkb['waybillid']));
					foreach($allwaybill as $wbs){
						$this->wbdb->update(array('status'=>18),array('waybillid'=>trim($wbs)));
						$this->historydb->delete(array('status'=>10,'sysbillid'=>$this->get_way_sysbillid(trim($wbs))));
					}
				}
				$this->kbdb->delete(array('id'=>$kid));//删除卡板号
				break;
			case 17://从出库 里面退回上一步
				$allkb = $this->whuodandb->get_one(array('id'=>$kid));
				if($allkb){
					$allwaybill = explode("\n",trim($allkb['kabanno']));
					foreach($allwaybill as $wbs){
						$this->kbdb->update(array('status'=>10),array('kabanno'=>trim($wbs)));

						$allws= $this->kbdb->get_one(array('kabanno'=>trim($wbs)));
						if($allws){
							$all_waybill = explode("\n",trim($allws['waybillid']));
							foreach($all_waybill as $w){
							
							$this->wbdb->update(array('status'=>10),array('waybillid'=>trim($w)));
							$this->historydb->delete(array('status'=>17,'sysbillid'=>$this->get_way_sysbillid(trim($w))));
							}
						}
					}
				}

				$this->whuodandb->delete(array('id'=>$kid));//删除货单号
				break;
		}
		showmessage(L('operation_success'), HTTP_REFERER);
	}

	//sysbillid

	//扫描枪自动入库
	public function scanning_gun_storage(){
		$show_validator = 1;
		$no = trim($_GET['no']);
		if($no){
			/*$result = array('color'=>'#FF6600','time'=>time());
			$row = $this->packagedb->get_one(array('expressno'=>$no,'status'=>1),'expressno');
			if($row){
				$result['color'] = '#CAFF70';
				$data = array();
				$data['operatorid'] = SYS_TIME;
				$data['operatorname'] = $this->username;
				$data['status'] = 2;
				 
				//$this->packagedb->update($data,array('expressno'=>$no));

				//$this->update_all_package_status($no,1);
			}
			
			print_r(json_encode($result));

			exit;*/
		}	
		
		$start_time = date('Y-m-d', time());
		$end_time = date('Y-m-d', time());

		include $this->admin_tpl('house_jihuo_scanning_gun_storage');
	}

	//扫描枪自动出库
	public function scanning_gun_outstorage(){
		$show_validator = 1;
		$no = trim($_GET['no']);
		if($no){
			/*$result = array('color'=>'#FF6600','time'=>time());
			$row = $this->packagedb->get_one(array('expressno'=>$no,'status'=>1),'expressno');
			if($row){
				$result['color'] = '#CAFF70';
				$data = array();
				$data['operatorid'] = SYS_TIME;
				$data['operatorname'] = $this->username;
				$data['status'] = 2;
				 
				//$this->packagedb->update($data,array('expressno'=>$no));

				//$this->update_all_package_status($no,1);
			}
			
			print_r(json_encode($result));

			exit;*/
		}	
		
		$start_time = date('Y-m-d', time());
		$end_time = date('Y-m-d', time());

		include $this->admin_tpl('house_jihuo_scanning_gun_outstorage');
	}

	//获取当前sysbillid
	public function get_way_sysbillid($waybillid){
		$waybillid = trim($waybillid);
		$udb = pc_base::load_model('waybill_model');
		$r = $udb->get_one("waybillid='$waybillid' ");
		return trim($r['sysbillid']);
	}

	//显示订单详细
	public function house_jihuo_showbill(){
		
		$bid = intval($_GET['bid']);
		$wbdb = pc_base::load_model('waybill_model');	
		$sql = array('aid' => $bid);
		$info = $wbdb->get_one($sql);

		include $this->admin_tpl('house_jihuo_showbill');
	}

	//批量打印条码
	public function more_print_barcode(){

		$cdb = pc_base::load_model('waybill_model');
		$ids = isset($_POST['aid']) ? implode(',',$_POST['aid']) : 0;
		$sql = 'aid in('.$ids.') ';
		$datas = $cdb->select($sql,'*',10000,'');
		

		include $this->admin_tpl('more_print_barcode');
	}
	//批量打印
	public function more_print(){
		
		$cdb = pc_base::load_model('waybill_model');
		$ids = isset($_POST['aid']) ? implode(',',$_POST['aid']) : 0;
		$sql = 'aid in('.$ids.') ';
		$datas = $cdb->select($sql,'*',10000,'');


		include $this->admin_tpl('waybill_moreprint');
	}

	//集货点包裹列表
	public function house_jihuo()
	{
		
		
		$sdb = pc_base::load_model('site_model');
		$row = $sdb->get_one(array('siteid'=>$this->get_siteid()));
		$overday = $row['package_overday'];
		
		$status = isset($_GET['status'])?intval($_GET['status']) : -1;
		
		$keywords = isset($_GET['keywords'])?trim($_GET['keywords']) : '';
		$userid = isset($_GET['userid'])?trim($_GET['userid']) : 0;
		$username = isset($_GET['username'])?trim($_GET['username']) : '';
		$truename = isset($_GET['truename'])?trim($_GET['truename']) : '';
		$_number = isset($_GET['_number'])?trim($_GET['_number']) : '';
		

		$sql = '';
			
		$sql = " 1 ";
			
		if(!empty($keywords)){
			$sql.=" and (username like '%$keywords%' or expressno like '%$keywords%' ) ";
		}

		if($userid>0){
			$sql.=" and userid='$userid' ";
		}

		if($username!=''){
			$sql.=" and username='$username' ";
		}

		if($truename!=''){
			$sql.=" and username='$truename'";
		}

		if($_number!=''){
			$sql.=" and aid='$_number' ";
		}

		
		$sql .= " and  status='1'";
		
		$act = isset($_GET['act']) ? $_GET['act'] : '';
		$start_time = isset($_GET['start_time']) ? $_GET['start_time'] : '';
		$end_time = isset($_GET['end_time']) ? $_GET['end_time'] : '';
		
		if($start_time!='' && $end_time!=''){
			$sql.=" and addtime>='".strtotime($start_time.' 00:00:01')."' and addtime<='".strtotime($end_time.' 23:59:59')."' ";
		}
	
		if($act=='download'){
			require_once PHPCMS_PATH.'api/PHPExcel.php';       
			require_once PHPCMS_PATH.'api/PHPExcel/Writer/Excel5.php';  
			
			
			$objPHPExcel = new PHPExcel();
			$objPHPExcel->setActiveSheetIndex(0);    
			$objActSheet = $objPHPExcel->getActiveSheet(); 
			//设置当前活动sheet的名称
			$objActSheet->setTitle("包裹列表");
			//UserID 客户账号 真实姓名 邮单号 国内快递 数量 重量 物品价值 商品属性 状态 添加时间 
			//标头 箱数/商品URL/商家名称
			
			$excelHeader=array('序号','用户ID','客户账号','真实姓名','邮单号','国内快递','数量','重量','物品价值','物品名称','状态','添加时间','箱数','商品URL','商家名称','仓库','备注');

			foreach($excelHeader as $k=>$v){
				$objActSheet->setCellValueByColumnAndRow($k,1,$v);
			}
			$m_db = pc_base::load_model('member_model');
			$m_db->set_model(10);
			
			

			//数据
			$wsql="select * from t_package where ".$sql." order by addtime desc ";
			$result = $this->db->query($wsql);
			$rownum=1;
			$package_array = $this->db->fetch_array($result);
			foreach($package_array as $num=>$row) {
				$rownum+=1;
			
				
				$objActSheet->setCellValueByColumnAndRow(0,$rownum,$row['aid']);
				$objActSheet->setCellValueByColumnAndRow(1,$rownum,$row['userid']);
				$objActSheet->setCellValueByColumnAndRow(2,$rownum,$row['username']);
				$objActSheet->setCellValueByColumnAndRow(3,$rownum,$row['truename']);
				$objActSheet->setCellValueByColumnAndRow(4,$rownum,'#'.$row['expressno']);
				$objActSheet->setCellValueByColumnAndRow(5,$rownum,$row['expressname']);
				$objActSheet->setCellValueByColumnAndRow(6,$rownum,$row['amount']);
				$objActSheet->setCellValueByColumnAndRow(7,$rownum,$row['weight']);
				$objActSheet->setCellValueByColumnAndRow(8,$rownum,$row['price']);
				$objActSheet->setCellValueByColumnAndRow(9,$rownum,$row['goodsname']);
				$objActSheet->setCellValueByColumnAndRow(10,$rownum,$this->getonestatus($row['status']));
				$objActSheet->setCellValueByColumnAndRow(11,$rownum,date('Y-m-d H:i:s',$row['addtime']));

				$objActSheet->setCellValueByColumnAndRow(12,$rownum,$row['boxcount']);
				$objActSheet->setCellValueByColumnAndRow(13,$rownum,$row['producturl']);
				$objActSheet->setCellValueByColumnAndRow(14,$rownum,$row['sellername']);
				$objActSheet->setCellValueByColumnAndRow(15,$rownum,$row['storagename']);
				$objActSheet->setCellValueByColumnAndRow(16,$rownum,$row['remark']);
			}
			

			$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
			//在浏览器导出
			header("Content-Type: application/force-download");
			header('Content-Type: application/vnd.ms-excel');
			header("Content-Disposition: attachment;filename=\"".iconv("UTF-8","GB2312","PackageList").date('ymdHis').".xls\"");
			header('Cache-Control: max-age=0');
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
			header("Pragma: no-cache");
			$objWriter->save("php://output");
			exit;

			
		}else{	
			if($_GET['flag']=='1'){
				
				$wsql="select * from t_waybill where ".$sql." and status=1 order by addtime desc ";
				$results = $this->wbdb->query($wsql);
				$rownum=0;
				$package_array = $this->wbdb->fetch_array($results);
				
				foreach($package_array as $num=>$row) {


				    $data_content ="<?php ".chr(13);
					
					$data_content .=chr(13).' defined("IN_PHPCMS") or exit("No permission resources.");';
					$data_content .=chr(13).' pc_base::load_app_class("admin","admin",0);';
					$data_content .=chr(13).' $this_username = param::get_cookie("admin_username");';
					$data_content .=chr(13).' $this_userid = param::get_cookie("admin_userid");';
					$data_content .=chr(13).' $db = pc_base::load_model("waybill_model");';

			
					$data_content .=chr(13).' $no = trim($_GET["no"]);';
					$data_content .=chr(13).' $result = array("color"=>"#FF6600","time"=>time());';

					$data_content .=chr(13).' if($no && !empty($this_username)){';
					
					$data_content .=chr(13).' $row = $db->get_one(array("expressno"=>$no,"status"=>1),"expressno");';
					
					$data_content .=chr(13).' $result["color"] = "#CAFF70";';
					
					$data_content .=chr(13).' if($row){';
					$data_content .=chr(13).' $data = array();';
					$data_content .=chr(13).' $data["operatorid"] = SYS_TIME;';
					$data_content .=chr(13).' $data["operatorname"] = $this_username;';
					$data_content .=chr(13).' $data["status"] = 3;';

					$data_content .=chr(13).' $db->update($data,array("expressno"=>$no));';
					$data_content .=chr(13).' update_all_package_status($no);';  // update --------------------------


					$data_content .=chr(13).' }';
					
					$data_content .=chr(13).' }';
					
					$data_content .=chr(13).' print_r(json_encode($result));';

					//-----------------------status -------------------------------------
					$data_content .=chr(13).' function update_all_package_status($expressno,$send_email_nofity=1){';
					$data_content .=chr(13).' $cdb = pc_base::load_model("waybill_goods_model");';
					$data_content .=chr(13).' $pdb = pc_base::load_model("package_model");';
					$data_content .=chr(13).' $wdb = pc_base::load_model("waybill_model");';
					$data_content .=chr(13).' $expressno="'.trim($row['expressno']).'";';
					$data_content .=chr(13).' $rs = $wdb->get_one(array("expressno"=>$expressno));';
					$data_content .=chr(13).' if($rs){';
					$data_content .=chr(13).' $moduledb = pc_base::load_model("module_model");';
					$data_content .=chr(13).' $__mdb = pc_base::load_model("member_model");';
					$data_content .=chr(13).' $userinfo=$__mdb->get_one(array("userid"=>$rs["userid"]));';
					$data_content .=chr(13).' $status_array=array(1=>"未入库",3=>"已入库");';
					$data_content .=chr(13).' if($send_email_nofity==1){//发邮件';
					$data_content .=chr(13).' pc_base::load_sys_func("mail");';
					$data_content .=chr(13).' $member_setting = $moduledb->get_one(array("module"=>"member"), "setting");';
					$data_content .=chr(13).' $member_setting = string2array($member_setting["setting"]);';
					$data_content .=chr(13).' $url = APP_PATH."index.php?m=package&c=index&a=init&hid=".$rs["storageid"];';
					$data_content .=chr(13).' $message = $member_setting["expressnoemail"];';
					$data_content .=chr(13).' $message = str_replace(array("{click}","{url}","{no}","{status}"), array("<a href=\"".$url."\">".L("please_click")."</a>",$url,$expressno,$status_array[$rs["status"]]), $message);';
					$data_content .=chr(13).' sendmail($userinfo["email"], "你的运单号".$expressno.$status_array[$rs["status"]], $message);';
					$data_content .=chr(13).' }';
					$data_content .=chr(13).' }';
			
				
					
			
					$data_content .=chr(13).' $wdb->update(array("status"=>3),array("waybillid"=>$waybillid));//更新运单已入库状态';

					$data_content .=chr(13).' //----------------------------------------------------';
					$data_content .=chr(13).' $historydb = pc_base::load_model("waybill_history_model");';
					$data_content .=chr(13).' $row = $wdb->get_one(array("expressno"=>$expressno, "returncode"=>$returncode));';
					$data_content .=chr(13).' $handle=array();';
					$data_content .=chr(13).' $handle["siteid"] = 1;';
					$data_content .=chr(13).' $handle["username"] = "'.$this->username.'";';
					$data_content .=chr(13).' $handle["userid"] = "'.$this->userid.'";';
					$data_content .=chr(13).' $handle["addtime"] = SYS_TIME;';
					$data_content .=chr(13).' $handle["sysbillid"] = trim($row["sysbillid"]);';
					$data_content .=chr(13).' $handle["waybillid"] = trim($row["waybillid"]);';
					$data_content .=chr(13).' $handle["placeid"] = $row["storagid"];';
					$data_content .=chr(13).' $handle["placename"] = $row["storagename"];';
					$data_content .=chr(13).' $handle["status"] = 3;';
					$data_content .=chr(13).' $handle["remark"] = L("waybill_status".$handle["status"]);	';
					$data_content .=chr(13).' $lid = $historydb->insert($handle);';
					$data_content .=chr(13).' //----------------------------------------------------';


			
				
					$data_content .=chr(13).' }//end update_all_status';
					//-------------------------------------------------------------------




					$data_content .=chr(13).'?>';

					$filename = PHPCMS_PATH.'api/cacheno/'.$this->userid."_".trim($row['expressno']).'.php';
					
					//---------------------------------------------write file --------------------------------------------
					$fp = fopen($filename, 'w+');
					$startTime = microtime();
					do{
						$canWrite = flock( $fp, LOCK_EX );
						if (! $canWrite)
							usleep(round(rand( 0, 100 ) * 1000));
					} while((! $canWrite) && ((microtime() - $startTime) < 1000));

					if ($canWrite){
						fwrite($fp, $data_content);
					}

					fclose($fp);  
					//---------------------------------------------write file --------------------------------------------
					
					
					$rownum++;
				}






				$result = array('count'=>$rownum);
				//$result['count'] = $this->packagedb->count($sql);
				print_r(json_encode($result));
				exit;
			}
			elseif($_GET['flag']=='7'){
				
				$wsql="select * from t_waybill where status=7 order by addtime desc ";
				$results = $this->wbdb->query($wsql);
				$rownum=0;
				$package_array = $this->wbdb->fetch_array($results);
				
				foreach($package_array as $num=>$row) {


				    $data_content ="<?php ".chr(13);
					
					$data_content .=chr(13).' defined("IN_PHPCMS") or exit("No permission resources.");';
					$data_content .=chr(13).' pc_base::load_app_class("admin","admin",0);';
					$data_content .=chr(13).' $this_username = param::get_cookie("admin_username");';
					$data_content .=chr(13).' $this_userid = param::get_cookie("admin_userid");';
					$data_content .=chr(13).' $db = pc_base::load_model("waybill_model");';

			
					$data_content .=chr(13).' $no = trim($_GET["no"]);';
					$data_content .=chr(13).' $result = array("color"=>"#FF6600","time"=>time());';

					$data_content .=chr(13).' if($no && !empty($this_username)){';
					
					$data_content .=chr(13).' $row = $db->get_one(array("expressno"=>$no,"status"=>7),"expressno");';
					
					$data_content .=chr(13).' $result["color"] = "#CAFF70";';
					
					$data_content .=chr(13).' if($row){';
					$data_content .=chr(13).' $data = array();';
					$data_content .=chr(13).' $data["operatorid"] = SYS_TIME;';
					$data_content .=chr(13).' $data["operatorname"] = $this_username;';
					$data_content .=chr(13).' $data["status"] = 10;';

					$data_content .=chr(13).' $db->update($data,array("expressno"=>$no));';
					$data_content .=chr(13).' update_all_package_status($no);';  // update --------------------------


					$data_content .=chr(13).' }';
					
					$data_content .=chr(13).' }';
					
					$data_content .=chr(13).' print_r(json_encode($result));';

					//-----------------------status -------------------------------------
					$data_content .=chr(13).' function update_all_package_status($expressno,$send_email_nofity=1){';
					$data_content .=chr(13).' $cdb = pc_base::load_model("waybill_goods_model");';
					$data_content .=chr(13).' $pdb = pc_base::load_model("package_model");';
					$data_content .=chr(13).' $wdb = pc_base::load_model("waybill_model");';
					$data_content .=chr(13).' $expressno="'.trim($row['expressno']).'";';
					$data_content .=chr(13).' $rs = $wdb->get_one(array("expressno"=>$expressno));';
					$data_content .=chr(13).' if($rs){';
					$data_content .=chr(13).' $moduledb = pc_base::load_model("module_model");';
					$data_content .=chr(13).' $__mdb = pc_base::load_model("member_model");';
					$data_content .=chr(13).' $userinfo=$__mdb->get_one(array("userid"=>$rs["userid"]));';
					$data_content .=chr(13).' $status_array=array(7=>"已付款",10=>"待发货");';
					$data_content .=chr(13).' if($send_email_nofity==1){//发邮件';
					$data_content .=chr(13).' pc_base::load_sys_func("mail");';
					$data_content .=chr(13).' $member_setting = $moduledb->get_one(array("module"=>"member"), "setting");';
					$data_content .=chr(13).' $member_setting = string2array($member_setting["setting"]);';
					$data_content .=chr(13).' $url = APP_PATH."index.php?m=package&c=index&a=init&hid=".$rs["storageid"];';
					$data_content .=chr(13).' $message = $member_setting["expressnoemail"];';
					$data_content .=chr(13).' $message = str_replace(array("{click}","{url}","{no}","{status}"), array("<a href=\"".$url."\">".L("please_click")."</a>",$url,$expressno,$status_array[$rs["status"]]), $message);';
					$data_content .=chr(13).' sendmail($userinfo["email"], "你的运单号".$expressno.$status_array[$rs["status"]], $message);';
					$data_content .=chr(13).' }';
					$data_content .=chr(13).' }';
			
				
					
			
					$data_content .=chr(13).' $wdb->update(array("status"=>10),array("waybillid"=>$waybillid));//更新运单已入库状态';

					$data_content .=chr(13).' //----------------------------------------------------';
					$data_content .=chr(13).' $historydb = pc_base::load_model("waybill_history_model");';
					$data_content .=chr(13).' $row = $wdb->get_one(array("expressno"=>$expressno, "returncode"=>$returncode));';
					$data_content .=chr(13).' $handle=array();';
					$data_content .=chr(13).' $handle["siteid"] = 1;';
					$data_content .=chr(13).' $handle["username"] = "'.$this->username.'";';
					$data_content .=chr(13).' $handle["userid"] = "'.$this->userid.'";';
					$data_content .=chr(13).' $handle["addtime"] = SYS_TIME;';
					$data_content .=chr(13).' $handle["sysbillid"] = trim($row["sysbillid"]);';
					$data_content .=chr(13).' $handle["waybillid"] = trim($row["waybillid"]);';
					$data_content .=chr(13).' $handle["placeid"] = $row["storagid"];';
					$data_content .=chr(13).' $handle["placename"] = $row["storagename"];';
					$data_content .=chr(13).' $handle["status"] = 10;';
					$data_content .=chr(13).' $handle["remark"] = L("waybill_status".$handle["status"]);	';
					$data_content .=chr(13).' $lid = $historydb->insert($handle);';
					$data_content .=chr(13).' //----------------------------------------------------';


			
				
					$data_content .=chr(13).' }//end update_all_status';
					//-------------------------------------------------------------------




					$data_content .=chr(13).'?>';

					$filename = PHPCMS_PATH.'api/cacheno/'.$this->userid."_".trim($row['expressno']).'.php';
					
					//---------------------------------------------write file --------------------------------------------
					$fp = fopen($filename, 'w+');
					$startTime = microtime();
					do{
						$canWrite = flock( $fp, LOCK_EX );
						if (! $canWrite)
							usleep(round(rand( 0, 100 ) * 1000));
					} while((! $canWrite) && ((microtime() - $startTime) < 1000));

					if ($canWrite){
						fwrite($fp, $data_content);
					}

					fclose($fp);  
					//---------------------------------------------write file --------------------------------------------
					
					
					$rownum++;
				}

				$result = array('count'=>$rownum);
				//$result['count'] = $this->packagedb->count($sql);
				print_r(json_encode($result));
				exit;
			}
			
			else{
				$page = max(intval($_GET['page']), 1);
				$datas = $this->wbdb->listinfo($sql, '`aid` DESC', $page);
			}
		}

		include $this->admin_tpl('house_jihuo');
	}

	//异常包裹
	public function house_jihuo_exception(){
		$flag='1';
		$this->house_jihuo_overdate($flag);
	}

	//集货点超期包裹列表
	public function house_jihuo_overdate($flag=''){
		
		
		//$packagedb = pc_base::load_model('package_model');
		$sdb = pc_base::load_model('site_model');
		$row = siteinfo($this->get_siteid());
		$keywords = isset($_POST['keywords'])?trim($_GET['keywords']) : '';
		
		$userid = isset($_GET['userid']) ? intval($_GET['userid']) : 0;
		$username = isset($_POST['username'])?trim($_GET['username']) : '';

		
		
		if(!empty($flag)){
			$sql=" (userid=0 or username='' )";
		}else{
			$sql = " status=2 and (to_days(now())-to_days(FROM_UNIXTIME(addtime,'%Y-%m-%d %H:%i:%s')) )>'".intval(substr($row['package_overday'],0,2))."'  and storageid='".$this->hid."'";
		}

		if(!empty($keywords)){
			$sql.=" and (username like '%$keywords%' or expressno like '%$keywords%' ) ";
		}

		if($userid>0){
			$sql.=" and userid='$userid' ";
		}

		if(!empty($username)){
			$sql.=" and (username like '%$username%' ) ";
		}
		
		$act = isset($_GET['act']) ? $_GET['act'] : '';
		$start_time = isset($_GET['start_time']) ? $_GET['start_time'] : '';
		$end_time = isset($_GET['end_time']) ? $_GET['end_time'] : '';
		
		if($start_time!='' && $end_time!=''){
			$sql.=" and addtime>='".strtotime($start_time.' 00:00:01')."' and addtime<='".strtotime($end_time.' 23:59:59')."' ";
		}//echo $sql;exit;
	
		if($act=='download'){
			require_once PHPCMS_PATH.'api/PHPExcel.php';       
			require_once PHPCMS_PATH.'api/PHPExcel/Writer/Excel5.php';  
			
			
			$objPHPExcel = new PHPExcel();
			$objPHPExcel->setActiveSheetIndex(0);    
			$objActSheet = $objPHPExcel->getActiveSheet(); 
			//设置当前活动sheet的名称
			$objActSheet->setTitle("包裹列表");
			//UserID 客户账号 真实姓名 邮单号 国内快递 数量 重量 物品价值 商品属性 状态 添加时间 
			//标头 
			
			$excelHeader=array('序号','用户ID','客户账号','真实姓名','邮单号','国内快递','数量','重量','物品价值','商品属性','状态','添加时间');

			foreach($excelHeader as $k=>$v){
				$objActSheet->setCellValueByColumnAndRow($k,1,$v);
			}
			$m_db = pc_base::load_model('member_model');
			$m_db->set_model(10);
			
			

			//数据
			$wsql="select * from t_package where ".$sql." order by addtime desc ";
			$result = $this->db->query($wsql);
			$package_datas = $this->db->fetch_array($result);
			$rownum=1;
			foreach($package_datas as $num=>$row) {
				$rownum+=1;
			
				
				$objActSheet->setCellValueByColumnAndRow(0,$rownum,$row['aid']);
				$objActSheet->setCellValueByColumnAndRow(1,$rownum,$row['userid']);
				$objActSheet->setCellValueByColumnAndRow(2,$rownum,$row['username']);
				$objActSheet->setCellValueByColumnAndRow(3,$rownum,$row['truename']);
				$objActSheet->setCellValueByColumnAndRow(4,$rownum,'#'.$row['expressno']);
				$objActSheet->setCellValueByColumnAndRow(5,$rownum,$row['expressname']);
				$objActSheet->setCellValueByColumnAndRow(6,$rownum,$row['amount']);
				$objActSheet->setCellValueByColumnAndRow(7,$rownum,$row['weight']);
				$objActSheet->setCellValueByColumnAndRow(8,$rownum,$row['price']);
				$objActSheet->setCellValueByColumnAndRow(9,$rownum,$row['goodsname']);
				$objActSheet->setCellValueByColumnAndRow(10,$rownum,$this->getonestatus($row['status']));
				$objActSheet->setCellValueByColumnAndRow(11,$rownum,date('Y-m-d H:i:s',$row['addtime']));
			}
			

			$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
			//在浏览器导出
			header("Content-Type: application/force-download");
			header('Content-Type: application/vnd.ms-excel');
			header("Content-Disposition: attachment;filename=\"".iconv("UTF-8","GB2312","包裹列表").date('ymdHis').".xls\"");
			header('Cache-Control: max-age=0');
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
			header("Pragma: no-cache");
			$objWriter->save("php://output");
			exit;

			
		}else{	
		
		$page = max(intval($_GET['page']), 1);
		$datas = $this->packagedb->listinfo($sql, ' addtime DESC', $page);

		}

		include $this->admin_tpl('house_jihuo_overdate');
	}

	public function house_jihuo_returned(){
		$status = isset($_GET['status'])?intval($_GET['status']) : 0;
		$keywords = isset($_GET['keywords'])?trim($_GET['keywords']) : '';
		
		$act = isset($_GET['act']) ? $_GET['act'] : '';
		$start_time = isset($_GET['start_time']) ? $_GET['start_time'] : '';
		$end_time = isset($_GET['end_time']) ? $_GET['end_time'] : '';
		
		$userid = isset($_GET['userid'])?trim($_GET['userid']) : 0;
		$username = isset($_GET['username'])?trim($_GET['username']) : '';
		$truename = isset($_GET['truename'])?trim($_GET['truename']) : '';
		$_number = isset($_GET['_number'])?trim($_GET['_number']) : '';

		if($status==-1){

		}else{
		
		$sql = " siteid='".$this->get_siteid()."' and storageid='$this->hid' AND returnedstatus!=0 ";
		if($status>0)
			$sql.=" and status='$status'";
		
	
		if(!empty($keywords)){

			$gdb = pc_base::load_model('waybill_goods_model');
			$rss = $gdb->get_one("expressno='$keywords'");
			$waybillids = "";

			if($rss){
				$waybillids = " or waybillid='".trim($rss['waybillid'])."' ";
			}
			
			$sql.=" and ( username like '%$keywords%' or waybillid like '%$keywords%' $waybillids) ";
			
		}

		
		if($userid>0){
			$sql.=" and userid='$userid' ";
		}

		if($username!=''){
			$sql.=" and username='$username' ";
		}

		if($truename!=''){
			$sql.=" and truename='$truename' ";
		}

		if($_number!=''){
			$sql.=" and aid='$_number' ";
		}
		

		if($start_time!='' && $end_time!=''){
			$sql.=" and addtime>='".strtotime($start_time.' 00:00:01')."' and addtime<='".strtotime($end_time.' 23:59:59')."' ";
		}
		
	
		if($act=='download'){
			require_once PHPCMS_PATH.'api/PHPExcel.php';       
			require_once PHPCMS_PATH.'api/PHPExcel/Writer/Excel5.php';  
			
			
			$objPHPExcel = new PHPExcel();
			$objPHPExcel->setActiveSheetIndex(0);    
			$objActSheet = $objPHPExcel->getActiveSheet(); 
			//设置当前活动sheet的名称
			$objActSheet->setTitle("Waybill List");
			//客户账号 真实姓名 运单号 邮单号 收件人 收货城市 状态 添加时间 
			//标头 
			/**
			发 货 地： 美国 收 货 地： 中国大陆 
快递公司： 美国波特兰仓库 运单类型： 渠道B免税（普通货物） 

			*/
			
			$excelHeader=array('序号','用户ID','客户账号','真实姓名','运单号','邮单号','邮单商品名称','邮单数量','邮单重量','邮单价格','收件人','收货城市','联系方式','增值服务','状态','添加时间','备注','系统单号','长(英寸)','宽(英寸)','高(英寸)','体积重','体积费','实际重量(磅)','额外收费重量(磅)','总运费','总增值费','会员折扣%','额外折扣%','其它费用','实付费用','运单物品名称','发送邮件通知(1=是,0=否)','发货地','收货地','仓库','运单类型','发货地ID','收货地ID','仓库ID','运单类型ID','收货地址');

			foreach($excelHeader as $k=>$v){
				$objActSheet->setCellValueByColumnAndRow($k,1,$v);
			}
			$m_db = pc_base::load_model('member_model');
			$m_db->set_model(10);
			$twdb = pc_base::load_model('shipline_model');
		
			

			//数据
			$wsql="select * from t_waybill where ".$sql." order by userid desc,addtime desc ";
			$result = $this->db->query($wsql);
			$alldatas = $this->db->fetch_array($result);
			$rownum=1;
			$goodssrv=$this->getgoodsservice();
			$allsrv=$this->getallservicelist();

			foreach($alldatas as $num=>$row) {
				$rownum+=1;

			
				
				$takeperson="";
				$takecity="";
				$mobile="";
				
				$addr=explode("|",$row['takeaddressname']);

			
				$takeperson=$addr[0];
				$takecity=$addr[4];
				$mobile=$addr[1];
				
				
				$takeaddress=$row['takeaddressname'];

				$allexp=$this->getwaybill_goods($row['waybillid'], $row['returncode']);
				$exp="";
				$expname="";
				$expamount="";
				$expweight="";
				$expprice="";
				foreach($allexp as $v){
					$exp.="#".$v['expressno']."\n";
					$expname.=$v['goodsname']."\n";
					$expamount.=$v['amount']."\n";
					$expweight.=$v['weight']."\n";
					$expprice.=$v['price']."\n";
				
			
					//统计增值服务
					$alladdvalues="";
					if($v['addvalues']){
					$goodaddvalues = string2array($v['addvalues']);

					foreach($goodaddvalues as $addval){
						
						$val = explode("|",$addval);

						$alladdvalues.=$val[1].$val[7].$val[6].'/'.$val[4]." ";
						
					}
					}

				}
				
				if($row['addvalues']){
				$waybillallvalues=string2array($row['addvalues']);


				foreach($waybillallvalues as  $srv){
					$alladdvalues.=$srv['title'].$srv['currencyname'].$srv['price'].'/'.$srv['unitname']." ";
				}
				}
				
			//'序号','用户ID','客户账号','真实姓名','运单号','邮单号','邮单商品名称','邮单数量','邮单重量','邮单价格','收件人','收货城市','联系方式','增值服务','状态','添加时间','备注'
				$objActSheet->setCellValueByColumnAndRow(0,$rownum,$row['aid']);
				$objActSheet->setCellValueByColumnAndRow(1,$rownum,$row['userid']);
				$objActSheet->setCellValueByColumnAndRow(2,$rownum,$row['username']);
				$objActSheet->setCellValueByColumnAndRow(3,$rownum,$row['truename']);
				$objActSheet->setCellValueByColumnAndRow(4,$rownum,"#".$row['waybillid']);
				$objActSheet->setCellValueByColumnAndRow(5,$rownum,$exp);
				$objActSheet->setCellValueByColumnAndRow(6,$rownum,$expname);
				$objActSheet->setCellValueByColumnAndRow(7,$rownum,$expamount);
				$objActSheet->setCellValueByColumnAndRow(8,$rownum,$expweight);
				$objActSheet->setCellValueByColumnAndRow(9,$rownum,$expprice);
				$objActSheet->setCellValueByColumnAndRow(10,$rownum,$takeperson);
				$objActSheet->setCellValueByColumnAndRow(11,$rownum,$takecity);
				$objActSheet->setCellValueByColumnAndRow(12,$rownum,"#".$mobile);
				$objActSheet->setCellValueByColumnAndRow(13,$rownum,$alladdvalues);//增值服务
				$objActSheet->setCellValueByColumnAndRow(14,$rownum,str_replace(L('enter_xxstorage'),$this->getstoragename($row['placeid']),$this->getwaybilltatus($row['status'])));
				$objActSheet->setCellValueByColumnAndRow(15,$rownum,date('Y-m-d H:i:s',$row['addtime']));
				$objActSheet->setCellValueByColumnAndRow(16,$rownum,$row['remark']);

				//'系统单号','长(英寸)','宽(英寸)','高(英寸)','体积重','体积费','实际重量(磅)','额外收费重量(磅)','总运费','总增值费','会员折扣%','额外折扣%','其它费用','实付费用','导入必填-运单物品名称','发送邮件通知(1=是,0=否)'

				$objActSheet->setCellValueByColumnAndRow(17,$rownum,$row['sysbillid']);
				$objActSheet->setCellValueByColumnAndRow(18,$rownum,floatval($row['bill_long']));
				$objActSheet->setCellValueByColumnAndRow(19,$rownum,floatval($row['bill_width']));
				$objActSheet->setCellValueByColumnAndRow(20,$rownum,floatval($row['bill_height']));
				$objActSheet->setCellValueByColumnAndRow(21,$rownum,floatval($row['volumeweight']));
				$objActSheet->setCellValueByColumnAndRow(22,$rownum,floatval($row['volumefee']));
				$objActSheet->setCellValueByColumnAndRow(23,$rownum,floatval($row['totalweight']));
				$objActSheet->setCellValueByColumnAndRow(24,$rownum,floatval($row['factweight']));
				$objActSheet->setCellValueByColumnAndRow(25,$rownum,floatval($row['totalship']));
				$objActSheet->setCellValueByColumnAndRow(26,$rownum,floatval($row['allvaluedfee']));
				$objActSheet->setCellValueByColumnAndRow(27,$rownum,floatval($row['memberdiscount']));
				$objActSheet->setCellValueByColumnAndRow(28,$rownum,floatval($row['otherdiscount']));
				$objActSheet->setCellValueByColumnAndRow(29,$rownum,floatval($row['otherfee']));
				$objActSheet->setCellValueByColumnAndRow(30,$rownum,floatval($row['payedfee']));
				$objActSheet->setCellValueByColumnAndRow(31,$rownum,$row['goodsname']);
				$objActSheet->setCellValueByColumnAndRow(32,$rownum,0);

				//'发货地','收货地','仓库','运单类型','导入必填-发货地ID','导入必填-收货地ID','导入必填-仓库ID','导入必填-运单类型ID'

				
			
									
				$sendname= $row['sendname'];
				$takename= $row['takename'];
									
				


				$objActSheet->setCellValueByColumnAndRow(33,$rownum,$sendname);
				$objActSheet->setCellValueByColumnAndRow(34,$rownum,$takename);
				$objActSheet->setCellValueByColumnAndRow(35,$rownum,$row['storagename']);
				$objActSheet->setCellValueByColumnAndRow(36,$rownum,$row['shippingname']);

				$objActSheet->setCellValueByColumnAndRow(37,$rownum,$row['sendid']);
				$objActSheet->setCellValueByColumnAndRow(38,$rownum,$row['takeid']);
				$objActSheet->setCellValueByColumnAndRow(39,$rownum,$row['storageid']);
				$objActSheet->setCellValueByColumnAndRow(40,$rownum,$row['shippingid']);
				$objActSheet->setCellValueByColumnAndRow(41,$rownum,$takeaddress);
				
			}
			

			$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
			//在浏览器导出
			header("Content-Type: application/force-download");
			header('Content-Type: application/vnd.ms-excel');
			header("Content-Disposition: attachment;filename=\"".iconv("UTF-8","GB2312","Waybill List").date('ymdHis').".xls\"");
			header('Cache-Control: max-age=0');
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
			header("Pragma: no-cache");
			$objWriter->save("php://output");
			exit;

			
		}else if($act=='simpledownload'){
			require_once PHPCMS_PATH.'api/PHPExcel.php';       
			require_once PHPCMS_PATH.'api/PHPExcel/Writer/Excel5.php';  
			
			
			$objPHPExcel = new PHPExcel();
			$objPHPExcel->setActiveSheetIndex(0);    
			$objActSheet = $objPHPExcel->getActiveSheet(); 
			//设置当前活动sheet的名称
			$objActSheet->setTitle("Waybill List");
			//客户账号 真实姓名 运单号 邮单号 收件人 收货城市 状态 添加时间 
			//标头 
			/**
			发 货 地： 美国 收 货 地： 中国大陆 
快递公司： 美国波特兰仓库 运单类型： 渠道B免税（普通货物） 

			*/
			
			//$excelHeader=array('序号','导入必填-用户ID','客户账号','真实姓名','导入必填-运单号','邮单号','邮单商品名称','邮单数量','邮单重量','邮单价格','收件人','收货城市','联系方式','增值服务','状态','添加时间','备注','系统单号','导入必填-长(英寸)','导入必填-宽(英寸)','导入必填-高(英寸)','体积重','体积费','导入必填-实际重量(磅)','额外收费重量(磅)','总运费','总增值费','会员折扣%','额外折扣%','其它费用','实付费用','运单物品名称','发送邮件通知(1=是,0=否)','发货地','收货地','仓库','运单类型','导入必填-发货地ID','导入必填-收货地ID','导入必填-仓库ID','导入必填-运单类型ID');

			$excelHeader=array('NO','UserID','User-Name','Real-Name','Tracking-Number','Usa-Tracking-Number','Length(in)','Width(in)','Height(in)','Actual-Weight','Extra-Discount','Other-Costs','Send-Email(1=yes,0=no)');
			

			foreach($excelHeader as $k=>$v){
				$objActSheet->setCellValueByColumnAndRow($k,1,$v);
			}
			$m_db = pc_base::load_model('member_model');
			$m_db->set_model(10);
			$twdb = pc_base::load_model('shipline_model');
		
			

			//数据
			$wsql="select * from t_waybill where ".$sql." order by userid desc,addtime desc ";
			$result = $this->db->query($wsql);
			$alldatas = $this->db->fetch_array($result);
			$rownum=1;
			$goodssrv=$this->getgoodsservice();
			$allsrv=$this->getallservicelist();
			foreach($alldatas as $num=>$row) {
				$rownum+=1;

				
				
				$takeperson="";
				$takecity="";
				$mobile="";
				$addr=explode("|",$row['takeaddressname']);

			
				$takeperson=$addr[0];
				$takecity=$addr[4];
				$mobile=$addr[1];

				

				$allexp=$this->getwaybill_goods($row['waybillid'], $row['returncode']);
				$exp="";
				$expname="";
				$expamount="";
				$expweight="";
				$expprice="";
				foreach($allexp as $v){
					$exp.="#".$v['expressno']."\n";
					$expname.=$v['goodsname']."\n";
					$expamount.=$v['amount']."\n";
					$expweight.=$v['weight']."\n";
					$expprice.=$v['price']."\n";
				
			
					//统计增值服务
					$alladdvalues="";
					if($v['addvalues']){
					$goodaddvalues = string2array($v['addvalues']);

					
					foreach($goodaddvalues as $addval){
						
						$val = explode("|",$addval);

						$alladdvalues.=$val[1].$val[7].$val[6].'/'.$val[4]." ";
						
					}
					}

				}

				if($row['addvalues']){
					$waybillallvalues=string2array($row['addvalues']);
					foreach($waybillallvalues as  $srv){
							$alladdvalues.=$srv['title'].$srv['currencyname'].$srv['price'].'/'.$srv['unitname']." ";
					}
				}
			//'序号','用户ID','客户账号','真实姓名','运单号','邮单号','邮单商品名称','邮单数量','邮单重量','邮单价格','收件人','收货城市','联系方式','增值服务','状态','添加时间','备注'
				$objActSheet->setCellValueByColumnAndRow(0,$rownum,$row['aid']);
				$objActSheet->setCellValueByColumnAndRow(1,$rownum,$row['userid']);
				$objActSheet->setCellValueByColumnAndRow(2,$rownum,$row['username']);
				$objActSheet->setCellValueByColumnAndRow(3,$rownum,$row['truename']);
				$objActSheet->setCellValueByColumnAndRow(4,$rownum,"#".$row['waybillid']);
				$objActSheet->setCellValueByColumnAndRow(5,$rownum,$exp);
				$objActSheet->setCellValueByColumnAndRow(6,$rownum,floatval($row['bill_long']));
				$objActSheet->setCellValueByColumnAndRow(7,$rownum,floatval($row['bill_width']));
				$objActSheet->setCellValueByColumnAndRow(8,$rownum,floatval($row['bill_height']));
				$objActSheet->setCellValueByColumnAndRow(9,$rownum,floatval($row['totalweight']));
				$objActSheet->setCellValueByColumnAndRow(10,$rownum,floatval($row['otherdiscount']));
				$objActSheet->setCellValueByColumnAndRow(11,$rownum,floatval($row['otherfee']));
				$objActSheet->setCellValueByColumnAndRow(12,$rownum,1);

			}
			

			$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
			//在浏览器导出
			header("Content-Type: application/force-download");
			header('Content-Type: application/vnd.ms-excel');
			header("Content-Disposition: attachment;filename=\"".iconv("UTF-8","GB2312","tracking-number").date('ymdHis').".xls\"");
			header('Cache-Control: max-age=0');
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
			header("Pragma: no-cache");
			$objWriter->save("php://output");
			exit;
		}else{

			$page = max(intval($_GET['page']), 1);
			$datas = $this->wbdb->listinfo($sql, ' addtime desc', $page,25);

		}

		}

		include $this->admin_tpl('house_jihuo_returned');
	
	}


	

	//集货点运单列表
	public function house_jihuo_waybill(){
		
		$status = isset($_GET['status'])?intval($_GET['status']) : 0;
		$keywords = isset($_GET['keywords'])?trim($_GET['keywords']) : '';
		$keywords2 = isset($_GET['keywords2'])?trim($_GET['keywords2']) : '';
		$keywords3 = isset($_GET['keywords3'])?trim($_GET['keywords3']) : '';
		$clientname = isset($_GET['clientname'])?trim($_GET['clientname']) : '';
		$aid = isset($_GET['aid'])?trim($_GET['aid']) : '';
		$act = isset($_GET['act']) ? $_GET['act'] : '';
		$start_time = isset($_GET['start_time']) ? $_GET['start_time'] : '';
		$end_time = isset($_GET['end_time']) ? $_GET['end_time'] : '';
		
		$userid = isset($_GET['userid'])?trim($_GET['userid']) : 0;
		$username = isset($_GET['username'])?trim($_GET['username']) : '';
		$truename = isset($_GET['truename'])?trim($_GET['truename']) : '';
		$_number = isset($_GET['_number'])?trim($_GET['_number']) : '';

		$shippingname = isset($_GET['shippingname'])?trim($_GET['shippingname']) : '';
		
		$ordertime = isset($_GET['ordertime'])?trim($_GET['ordertime']) : 0;
		$ordertime_ordby=" addtime ";
		
		if($status==3){
			$ordertime_ordby=" inhousetime ";
		}
		
		if($status==21){
			$ordertime_ordby=" handletime ";
		}

		if($status==7){
			$ordertime_ordby=" paidtime ";
		}

		if($status==14){
			$ordertime_ordby=" sendtime ";
		}
		
		if($ordertime==0){
			$ordertime = 1;
			$ordertime_ordby.=" desc ";
		}else{
			$ordertime = 0;
			$ordertime_ordby.=" asc ";
		}

		

		if($status==-1){

		}else{
		
		$sql = " siteid='".$this->get_siteid()."'  AND returnedstatus=0   ";
		if($status>0)
			$sql.=" and status='$status'";
		
	
		if(!empty($keywords)){

			$gdb = pc_base::load_model('waybill_goods_model');
			$rss = $gdb->get_one("expressno='$keywords'");
			$waybillids = "";

			if($rss){
				$waybillids = " or waybillid='".trim($rss['waybillid'])."' ";
			}
			
			$sql.=" and ( username like '%$keywords%' or waybillid like '%$keywords%' $waybillids) ";
			
		}
		
		if(!empty($keywords2)){

			$gdb = pc_base::load_model('waybill_goods_model');
			$rss = $gdb->get_one("expressno='$keywords2'");
			$expressnos = "";

			if($rss){
				$expressnos = " or expressno='".trim($rss['expressno'])."' ";
			}
			
			$sql.=" and ( username like '%$keywords2%' or expressno like '%$keywords2%' $expressnos) ";
			
		}
		if(!empty($keywords3)){

			$gdb = pc_base::load_model('waybill_model');
			$rss = $gdb->get_one("expressnumber='$keywords3'");
			$expressnos = "";

			if($rss){
				$expressnos = " or expressnumber='".trim($rss['expressnumber'])."' ";
			}
			
			$sql.=" and ( username like '%$keywords3%' or expressnumber like '%$keywords3%' $expressnos) ";
			
		}
		if($aid!=''){
			$sql.=" and aid='$aid' ";
		}
		
		if($clientname!=''){
			$sql.=" and (takeflag='$clientname' or  takeflag like '%$clientname%') ";
		}
		
		if($userid>0){
			$sql.=" and userid='$userid' ";
		}

		if($username!=''){
			$sql.=" and username='$username' ";
		}

		if($truename!=''){
			$sql.=" and truename='$truename' ";
		}

		if($_number!=''){
			$sql.=" and aid='$_number' ";
		}
		
		if($shippingname!=''){
			
			$sql.=" and shippingname='$shippingname' ";
		}

		if($start_time!='' && $end_time!=''){
			$sql.=" and addtime>='".strtotime($start_time.' 00:00:01')."' and addtime<='".strtotime($end_time.' 23:59:59')."' ";
		}


		$noinhomecount = $this->wbdb->count("status=1 and siteid=1"); 
		$waithandlecount = $this->wbdb->count("status=21 and siteid=1"); 
		$payedcount = $this->wbdb->count("status=7 and siteid=1"); 

		$status3count = $this->wbdb->count("status=3 and siteid=1"); 
		$status8count = $this->wbdb->count("status=8 and siteid=1"); 
		$status14count = $this->wbdb->count("status=14 and siteid=1");
		$status16count = $this->wbdb->count("status=16 and siteid=1");
		$status19count = $this->wbdb->count("status=19 and siteid=1");
		$status20count = $this->wbdb->count("status=20 and siteid=1");
		$status99count = $this->wbdb->count("status=99 and siteid=1");
		
	
		if($act=='download'){
			require_once PHPCMS_PATH.'api/PHPExcel.php';       
			require_once PHPCMS_PATH.'api/PHPExcel/Writer/Excel5.php';  
			
			
			$objPHPExcel = new PHPExcel();
			$objPHPExcel->setActiveSheetIndex(0);    
			$objActSheet = $objPHPExcel->getActiveSheet(); 
			//设置当前活动sheet的名称
			$objActSheet->setTitle("运单列表");
			//客户账号 真实姓名 运单号 邮单号 收件人 收货城市 状态 添加时间 
			//标头 
			/**
			发 货 地： 美国 收 货 地： 中国大陆 
快递公司： 美国波特兰仓库 运单类型： 渠道B免税（普通货物） 

			*/
			
			$excelHeader=array('序号','用户ID','客户账号','真实姓名','运单号','邮单号','邮单商品名称','邮单数量','邮单重量','邮单价格','收件人','收货城市','联系方式','增值服务','状态','添加时间','备注','系统单号','长(英寸)','宽(英寸)','高(英寸)','体积重','体积费','实际重量(磅)','额外收费重量(磅)','总运费','总增值费','会员折扣%','额外折扣%','其它费用','实付费用','运单物品名称','发送邮件通知(1=是,0=否)','发货地','收货地','仓库','运单类型','发货地ID','收货地ID','仓库ID','运单类型ID','收货地址');

			foreach($excelHeader as $k=>$v){
				$objActSheet->setCellValueByColumnAndRow($k,1,$v);
			}
			$m_db = pc_base::load_model('member_model');
			$m_db->set_model(10);
			$twdb = pc_base::load_model('shipline_model');
		
			

			//数据
			$wsql="select * from t_waybill where ".$sql." order by userid desc,addtime desc ";
			$result = $this->db->query($wsql);
			$alldatas = $this->db->fetch_array($result);
			$rownum=1;
			$goodssrv=$this->getgoodsservice();
			$allsrv=$this->getallservicelist();

			foreach($alldatas as $num=>$row) {
				$rownum+=1;

			
				
				$takeperson="";
				$takecity="";
				$mobile="";
				
				$addr=explode("|",$row['takeaddressname']);

			
				$takeperson=$addr[0];
				$takecity=$addr[4];
				$mobile=$addr[1];
				
				
				$takeaddress=$row['takeaddressname'];

				$allexp=$this->getwaybill_goods($row['waybillid'], $row['returncode']);
				$exp="";
				$expname="";
				$expamount="";
				$expweight="";
				$expprice="";
				foreach($allexp as $v){
					$exp.="#".$v['expressno']."\n";
					$expname.=$v['goodsname']."\n";
					$expamount.=$v['amount']."\n";
					$expweight.=$v['weight']."\n";
					$expprice.=$v['price']."\n";
				
			
					//统计增值服务
					$alladdvalues="";
					if($v['addvalues']){
					$goodaddvalues = string2array($v['addvalues']);

					foreach($goodaddvalues as $addval){
						
						$val = explode("|",$addval);

						$alladdvalues.=$val[1].$val[7].$val[6].'/'.$val[4]." ";
						
					}
					}

				}
				
				if($row['addvalues']){
				$waybillallvalues=string2array($row['addvalues']);


				foreach($waybillallvalues as  $srv){
					$alladdvalues.=$srv['title'].$srv['currencyname'].$srv['price'].'/'.$srv['unitname']." ";
				}
				}
				
			//'序号','用户ID','客户账号','真实姓名','运单号','邮单号','邮单商品名称','邮单数量','邮单重量','邮单价格','收件人','收货城市','联系方式','增值服务','状态','添加时间','备注'
				$objActSheet->setCellValueByColumnAndRow(0,$rownum,$row['aid']);
				$objActSheet->setCellValueByColumnAndRow(1,$rownum,$row['userid']);
				$objActSheet->setCellValueByColumnAndRow(2,$rownum,$row['username']);
				$objActSheet->setCellValueByColumnAndRow(3,$rownum,$row['truename']);
				$objActSheet->setCellValueByColumnAndRow(4,$rownum,"#".$row['waybillid']);
				$objActSheet->setCellValueByColumnAndRow(5,$rownum,$exp);
				$objActSheet->setCellValueByColumnAndRow(6,$rownum,$expname);
				$objActSheet->setCellValueByColumnAndRow(7,$rownum,$expamount);
				$objActSheet->setCellValueByColumnAndRow(8,$rownum,$expweight);
				$objActSheet->setCellValueByColumnAndRow(9,$rownum,$expprice);
				$objActSheet->setCellValueByColumnAndRow(10,$rownum,$takeperson);
				$objActSheet->setCellValueByColumnAndRow(11,$rownum,$takecity);
				$objActSheet->setCellValueByColumnAndRow(12,$rownum,"#".$mobile);
				$objActSheet->setCellValueByColumnAndRow(13,$rownum,$alladdvalues);//增值服务
				$objActSheet->setCellValueByColumnAndRow(14,$rownum,str_replace(L('enter_xxstorage'),$this->getstoragename($row['placeid']),$this->getwaybilltatus($row['status'])));
				$objActSheet->setCellValueByColumnAndRow(15,$rownum,date('Y-m-d H:i:s',$row['addtime']));
				$objActSheet->setCellValueByColumnAndRow(16,$rownum,$row['remark']);

				//'系统单号','长(英寸)','宽(英寸)','高(英寸)','体积重','体积费','实际重量(磅)','额外收费重量(磅)','总运费','总增值费','会员折扣%','额外折扣%','其它费用','实付费用','导入必填-运单物品名称','发送邮件通知(1=是,0=否)'

				$objActSheet->setCellValueByColumnAndRow(17,$rownum,$row['sysbillid']);
				$objActSheet->setCellValueByColumnAndRow(18,$rownum,floatval($row['bill_long']));
				$objActSheet->setCellValueByColumnAndRow(19,$rownum,floatval($row['bill_width']));
				$objActSheet->setCellValueByColumnAndRow(20,$rownum,floatval($row['bill_height']));
				$objActSheet->setCellValueByColumnAndRow(21,$rownum,floatval($row['volumeweight']));
				$objActSheet->setCellValueByColumnAndRow(22,$rownum,floatval($row['volumefee']));
				$objActSheet->setCellValueByColumnAndRow(23,$rownum,floatval($row['totalweight']));
				$objActSheet->setCellValueByColumnAndRow(24,$rownum,floatval($row['factweight']));
				$objActSheet->setCellValueByColumnAndRow(25,$rownum,floatval($row['totalship']));
				$objActSheet->setCellValueByColumnAndRow(26,$rownum,floatval($row['allvaluedfee']));
				$objActSheet->setCellValueByColumnAndRow(27,$rownum,floatval($row['memberdiscount']));
				$objActSheet->setCellValueByColumnAndRow(28,$rownum,floatval($row['otherdiscount']));
				$objActSheet->setCellValueByColumnAndRow(29,$rownum,floatval($row['otherfee']));
				$objActSheet->setCellValueByColumnAndRow(30,$rownum,floatval($row['payedfee']));
				$objActSheet->setCellValueByColumnAndRow(31,$rownum,$row['goodsname']);
				$objActSheet->setCellValueByColumnAndRow(32,$rownum,0);

				//'发货地','收货地','仓库','运单类型','导入必填-发货地ID','导入必填-收货地ID','导入必填-仓库ID','导入必填-运单类型ID'

				
			
									
				$sendname= $row['sendname'];
				$takename= $row['takename'];
									
				


				$objActSheet->setCellValueByColumnAndRow(33,$rownum,$sendname);
				$objActSheet->setCellValueByColumnAndRow(34,$rownum,$takename);
				$objActSheet->setCellValueByColumnAndRow(35,$rownum,$row['storagename']);
				$objActSheet->setCellValueByColumnAndRow(36,$rownum,$row['shippingname']);

				$objActSheet->setCellValueByColumnAndRow(37,$rownum,$row['sendid']);
				$objActSheet->setCellValueByColumnAndRow(38,$rownum,$row['takeid']);
				$objActSheet->setCellValueByColumnAndRow(39,$rownum,$row['storageid']);
				$objActSheet->setCellValueByColumnAndRow(40,$rownum,$row['shippingid']);
				$objActSheet->setCellValueByColumnAndRow(41,$rownum,$takeaddress);
				
			}
			

			$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
			//在浏览器导出
			header("Content-Type: application/force-download");
			header('Content-Type: application/vnd.ms-excel');
			header("Content-Disposition: attachment;filename=\"".iconv("UTF-8","GB2312","运单列表").date('ymdHis').".xls\"");
			header('Cache-Control: max-age=0');
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
			header("Pragma: no-cache");
			$objWriter->save("php://output");
			exit;

			
		}else if($act=='simpledownload'){
			require_once PHPCMS_PATH.'api/PHPExcel.php';       
			require_once PHPCMS_PATH.'api/PHPExcel/Writer/Excel5.php';  
			
			
			$objPHPExcel = new PHPExcel();
			$objPHPExcel->setActiveSheetIndex(0);    
			$objActSheet = $objPHPExcel->getActiveSheet(); 
			//设置当前活动sheet的名称
			$objActSheet->setTitle("运单列表");
			//客户账号 真实姓名 运单号 邮单号 收件人 收货城市 状态 添加时间 
			//标头 
			/**
			发 货 地： 美国 收 货 地： 中国大陆 
快递公司： 美国波特兰仓库 运单类型： 渠道B免税（普通货物） 

			*/
			
			//$excelHeader=array('序号','导入必填-用户ID','客户账号','真实姓名','导入必填-运单号','邮单号','邮单商品名称','邮单数量','邮单重量','邮单价格','收件人','收货城市','联系方式','增值服务','状态','添加时间','备注','系统单号','导入必填-长(英寸)','导入必填-宽(英寸)','导入必填-高(英寸)','体积重','体积费','导入必填-实际重量(磅)','额外收费重量(磅)','总运费','总增值费','会员折扣%','额外折扣%','其它费用','实付费用','运单物品名称','发送邮件通知(1=是,0=否)','发货地','收货地','仓库','运单类型','导入必填-发货地ID','导入必填-收货地ID','导入必填-仓库ID','导入必填-运单类型ID');

			$excelHeader=array('NO','UserID','User-Name','Real-Name','Tracking-Number','Usa-Tracking-Number','Length(in)','Width(in)','Height(in)','Actual-Weight','Extra-Discount','Other-Costs','Send-Email(1=yes,0=no)');
			

			foreach($excelHeader as $k=>$v){
				$objActSheet->setCellValueByColumnAndRow($k,1,$v);
			}
			$m_db = pc_base::load_model('member_model');
			$m_db->set_model(10);
			$twdb = pc_base::load_model('shipline_model');
		
			

			//数据
			$wsql="select * from t_waybill where ".$sql." order by userid desc,addtime desc ";
			$result = $this->db->query($wsql);
			$alldatas = $this->db->fetch_array($result);
			$rownum=1;
			$goodssrv=$this->getgoodsservice();
			$allsrv=$this->getallservicelist();
			foreach($alldatas as $num=>$row) {
				$rownum+=1;

				
				
				$takeperson="";
				$takecity="";
				$mobile="";
				$addr=explode("|",$row['takeaddressname']);

			
				$takeperson=$addr[0];
				$takecity=$addr[4];
				$mobile=$addr[1];

				

				$allexp=$this->getwaybill_goods($row['waybillid'], $row['returncode']);
				$exp="";
				$expname="";
				$expamount="";
				$expweight="";
				$expprice="";
				foreach($allexp as $v){
					$exp.="#".$v['expressno']."\n";
					$expname.=$v['goodsname']."\n";
					$expamount.=$v['amount']."\n";
					$expweight.=$v['weight']."\n";
					$expprice.=$v['price']."\n";
				
			
					//统计增值服务
					$alladdvalues="";
					if($v['addvalues']){
					$goodaddvalues = string2array($v['addvalues']);

					
					foreach($goodaddvalues as $addval){
						
						$val = explode("|",$addval);

						$alladdvalues.=$val[1].$val[7].$val[6].'/'.$val[4]." ";
						
					}
					}

				}

				if($row['addvalues']){
					$waybillallvalues=string2array($row['addvalues']);
					foreach($waybillallvalues as  $srv){
							$alladdvalues.=$srv['title'].$srv['currencyname'].$srv['price'].'/'.$srv['unitname']." ";
					}
				}
			//'序号','用户ID','客户账号','真实姓名','运单号','邮单号','邮单商品名称','邮单数量','邮单重量','邮单价格','收件人','收货城市','联系方式','增值服务','状态','添加时间','备注'
				$objActSheet->setCellValueByColumnAndRow(0,$rownum,$row['aid']);
				$objActSheet->setCellValueByColumnAndRow(1,$rownum,$row['userid']);
				$objActSheet->setCellValueByColumnAndRow(2,$rownum,$row['username']);
				$objActSheet->setCellValueByColumnAndRow(3,$rownum,$row['truename']);
				$objActSheet->setCellValueByColumnAndRow(4,$rownum,"#".$row['waybillid']);
				$objActSheet->setCellValueByColumnAndRow(5,$rownum,$exp);
				$objActSheet->setCellValueByColumnAndRow(6,$rownum,floatval($row['bill_long']));
				$objActSheet->setCellValueByColumnAndRow(7,$rownum,floatval($row['bill_width']));
				$objActSheet->setCellValueByColumnAndRow(8,$rownum,floatval($row['bill_height']));
				$objActSheet->setCellValueByColumnAndRow(9,$rownum,floatval($row['totalweight']));
				$objActSheet->setCellValueByColumnAndRow(10,$rownum,floatval($row['otherdiscount']));
				$objActSheet->setCellValueByColumnAndRow(11,$rownum,floatval($row['otherfee']));
				$objActSheet->setCellValueByColumnAndRow(12,$rownum,1);

			}
			

			$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
			//在浏览器导出
			header("Content-Type: application/force-download");
			header('Content-Type: application/vnd.ms-excel');
			header("Content-Disposition: attachment;filename=\"".iconv("UTF-8","GB2312","tracking-number").date('ymdHis').".xls\"");
			header('Cache-Control: max-age=0');
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
			header("Pragma: no-cache");
			$objWriter->save("php://output");
			exit;
		}else{

			$page = max(intval($_GET['page']), 1);
			$datas = $this->wbdb->listinfo($sql, $ordertime_ordby, $page,25);

		}

		}

		include $this->admin_tpl('house_jihuo_waybill');
	}

	//根据ID返回货币名称
	private function getcurrencyname($cid){
		
		$cdb = pc_base::load_model('currency_model');
		$sql = "aid='$cid'";
		$datas = $cdb->get_one($sql,'symbol');

		$name=$datas['symbol'];
		
		return $name;
	}
	/**获取所有单位**/
	private function getunits(){
		$cdb = pc_base::load_model('enum_model');
		$sql = '`siteid`=1 AND groupid=2 ';
		$datas = $cdb->select($sql,'*',10000,'listorder ASC');
		return $datas;
	}
	
	/**获取所有货币**/
	private function getcurrency(){
		$cdb = pc_base::load_model('currency_model');
		$sql = '`siteid`=1';
		$datas = $cdb->select($sql,'*',10000);
		return $datas;
	}
	//根据ID返回单位名称
	private function getunitname($cid){
		$cdb = pc_base::load_model('enum_model');
		$sql = "`siteid`=1 AND groupid=2 and aid='$cid' ";
		$datas = $cdb->get_one($sql,'title');
		$name=$datas['title'];
		return $name;
	}

	/**根据运单号获取增值服务**/
	public function getwaybill_servicelist($waybillid,$waybill_goodsid){
		$cdb = pc_base::load_model('waybill_serviceitem_model');
		$sql = " waybillid='$waybillid' and waybill_goodsid='$waybill_goodsid'";
		$datas = $cdb->select($sql,'*',10000,'id desc');
		return $datas;
	}

	/**获取增值服务**/
	public function getservicelist($ids){
		$cdb = pc_base::load_model('waybill_serviceitem_model');
		$scdb = pc_base::load_model('service_model');
		$sql = "  waybillid='$ids'";
		$datas = $cdb->select($sql,'*',10000,'id desc');

		$arr=array();
		
		foreach($datas as $row){
			$r = $scdb->get_one(array('aid'=>$row['servicetypeid']));
			$row['servicename'] = $r['title'];
			$arr[]=$row;	
		}

		return $arr;
	}
	
		/**获取所有订单打包增值服务**/
	private function getgoodsservice(){
		$cdb = pc_base::load_model('service_model');
		$sql = '`siteid`=1 AND type=\'order\' ';
		$datas = $cdb->select($sql,'*',10000,'aid asc');
		return $datas;
	}

	/**获取所有增值服务**/
	private function getallservicelist(){
		$cdb = pc_base::load_model('service_model');
		$sql = ' siteid=1 and type=\'value-added\'';
		$datas = $cdb->select($sql,'*',10000,'addtime desc');
		return $datas;
	}
	
	//集货点出库列表y
	public function house_jihuo_outhouse(){
		
		
		/*$sql = " siteid='".$this->get_siteid()."' and storageid='$this->hid' and status=17 ";

		
		$page = max(intval($_GET['page']), 1);
		$billdata = $this->whuodandb->listinfo($sql, 'addtime DESC', $page);*/

		$sql = " siteid='".$this->get_siteid()."' and storageid='$this->hid' and status=10 ";

			if(!empty($keywords)){
				$sql.=" and (userid='$keywords' or username like '%$keywords%' or kabanno like '%$keywords%') ";
			}

			if($start_time!='' && $end_time!=''){
				$sql.=" and addtime>='".strtotime($start_time.' 00:00:01')."' and addtime<='".strtotime($end_time.' 23:59:59')."' ";
			}


				if($act=='download'){
			require_once PHPCMS_PATH.'api/PHPExcel.php';       
			require_once PHPCMS_PATH.'api/PHPExcel/Writer/Excel5.php';  
			
			
			$objPHPExcel = new PHPExcel();
			$objPHPExcel->setActiveSheetIndex(0);    
			$objActSheet = $objPHPExcel->getActiveSheet(); 
			//设置当前活动sheet的名称
			$objActSheet->setTitle("卡板列表");
			//ID 操作者 卡板编号 运单号 添加时间  
			//标头 
			
			$excelHeader=array('ID','操作者','卡板编号','运单号','添加时间');

			foreach($excelHeader as $k=>$v){
				$objActSheet->setCellValueByColumnAndRow($k,1,$v);
			}
			$m_db = pc_base::load_model('member_model');
			$m_db->set_model(10);
			
			

			//数据
			$wsql="select * from t_waybill_kaban where ".$sql." order by addtime desc ";
			$result = $this->db->query($wsql);
			$kaban_datas = $this->db->fetch_array($result);

			$rownum=1;
			foreach($kaban_datas as $num=>$row) {
				$rownum+=1;
			
				$objActSheet->setCellValueByColumnAndRow(0,$rownum,$row['id']);
				$objActSheet->setCellValueByColumnAndRow(1,$rownum,$row['username']);
				$objActSheet->setCellValueByColumnAndRow(2,$rownum,$row['kabanno']);
				$objActSheet->setCellValueByColumnAndRow(3,$rownum,$row['waybillid']);
				$objActSheet->setCellValueByColumnAndRow(4,$rownum,date('Y-m-d H:i:s',$row['addtime']));
			}
			

			$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
			//在浏览器导出
			header("Content-Type: application/force-download");
			header('Content-Type: application/vnd.ms-excel');
			header("Content-Disposition: attachment;filename=\"".iconv("UTF-8","GB2312","卡板列表").date('ymdHis').".xls\"");
			header('Cache-Control: max-age=0');
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
			header("Pragma: no-cache");
			$objWriter->save("php://output");
			exit;

			
		}else{

			$page = max(intval($_GET['page']), 1);
			$datas = $this->kbdb->listinfo($sql, 'addtime desc', $page);
		}


		include $this->admin_tpl('house_jihuo_outhouse');
	}

	//根据运单获取发货信息
	public function house_fahuo_info($waybillid){
		$wbdb = pc_base::load_model('waybill_fahuo_model');
		$r = $wbdb->get_one("waybillid='".$waybillid."'");
		return $r;
	}

	//修改包裹状态时，判断当前包裹运单是否全部已入库
	//更新运单状态
	private function update_all_package_status($expressno,$send_email_nofity=1){
		
		$cdb = pc_base::load_model('waybill_goods_model');
		$pdb = pc_base::load_model('package_model');
		$wdb = pc_base::load_model('waybill_model');
		
		$expressno=trim($expressno);

		$rs = $pdb->get_one(array('expressno'=>$expressno));
		if($rs){
			$moduledb = pc_base::load_model('module_model');
			$__mdb = pc_base::load_model('member_model');
			$userinfo=$__mdb->get_one(array('userid'=>$rs['userid']));
			$status_array=array(1=>'未入库',2=>'已入库',3=>'已处理');

			//发邮件
			if($send_email_nofity==1){
			pc_base::load_sys_func('mail');
			$member_setting = $moduledb->get_one(array('module'=>'member'), 'setting');
			$member_setting = string2array($member_setting['setting']);
			$url = APP_PATH."index.php?m=package&c=index&a=init&hid=".$rs['storageid'];
			$message = $member_setting['expressnoemail'];
			$message = str_replace(array('{click}','{url}','{no}','{status}'), array('<a href="'.$url.'">'.L('please_click').'</a>',$url,$expressno,$status_array[$rs['status']]), $message);
			sendmail($userinfo['email'], '你的邮单号'.$expressno.$status_array[$rs['status']], $message);
			}

		}






		$exp=$cdb->get_one(array('expressno'=>$expressno));
		if($exp){
			$waybillid= trim($exp['waybillid']);
			
			$sql = "  waybillid='$waybillid'  ";
			$billgoods = $cdb->select($sql,'*',10000,'id asc');
			$status1=0;
			$status2=0;
			
			foreach($billgoods as $val){
				$row = $pdb->get_one(array('expressno'=>trim($val['expressno'])));
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
				$wdb->update(array('status'=>3),array('waybillid'=>$waybillid));//更新运单已入库状态
			}elseif($status2>0)//表示全部入库中
			{
				//$wdb->update(array('status'=>2),array('waybillid'=>$waybillid));//更新运单入库中状态
			}else{
				$wdb->update(array('status'=>1),array('waybillid'=>$waybillid));//更新运单未入库状态
			}

		}

	}//end update_all_status

	//批量导入快递号
	public function house_jihuo_moreimport(){
		
	

		if(isset($_POST['dosubmit'])) {//导入操作

			$importpath = PHPCMS_PATH.trim($_POST['importpath']);
			$data = new Spreadsheet_Excel_Reader(); 
			$data->setOutputEncoding('utf-8'); 
			
			//邮单号 客户编号 客户姓名  商品属性 物品数量  物品重量 物品价值 到货入库 备注

			$_wbdb = pc_base::load_model('waybill_model');
			$historydb = pc_base::load_model('waybill_history_model');
			$sdb = pc_base::load_model('storage_model');
			$cdb = pc_base::load_model('enum_model');
			if(empty($_POST['importpath'])){
				echo "&nbsp;&nbsp;".date('Y-m-d H:i:s')."&nbsp;<font color='red'>请上传 EMS xls导入文件! <br/></font>";
				exit;
			}

			if(file_exists($importpath))
			{
				$data->read($importpath); 
				$oknum=0;
				$errnum=0;

				if(trim($data->sheets[0]['cells'][1][2])!="运单号" ||  trim($data->sheets[0]['cells'][1][6])!="国内快递" ||  trim($data->sheets[0]['cells'][1][7])!="快递号" ){
					echo "&nbsp;&nbsp;".date('Y-m-d H:i:s')."&nbsp;<font color='red'>请上传正确xls导入文件! <br/></font>";
					
				}

				for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++)     
				{
					
					
					$waybillid=trim(str_replace(PHP_EOL, '',$data->sheets[0]['cells'][$i][2]));
					$emsname=trim(str_replace(PHP_EOL, '',$data->sheets[0]['cells'][$i][6]));
					$expressnumber=trim(str_replace(PHP_EOL, '',$data->sheets[0]['cells'][$i][7]));
					
					if(!empty($waybillid) && !empty($emsname) && !empty($expressnumber)){

					
						$val = $cdb->get_one(" groupid=69 and title='".$emsname."' ",'remark');
						$expressurl=str_replace("#",$expressnumber,trim($val['remark']));
						
						$where="waybillid='$waybillid' and status not in(1,2,3,5,8,14,15,16,99)";

						if($_wbdb->update(array('status'=>14,'expressnumber'=>$expressnumber,'expressurl'=>$expressurl,'sendtime'=>SYS_TIME),$where)){
						
						$row = $_wbdb->get_one(array('waybillid'=>$waybillid));

						if(!$row['placeid'] && !$row['placename']){
							$house_row=$sdb->get_one(array('aid'=>$this->hid));
							if($house_row){
								$row['placeid'] = $this->hid;
								$row['placename'] = $house_row['storagename'];
							}
						}

						//插入处理记录
						$handle=array();
						$handle['siteid'] = $this->get_siteid();
						$handle['username'] = $this->username;
						$handle['userid'] = $this->userid;
						$handle['addtime'] = SYS_TIME;
						$handle['sysbillid'] = $row['sysbillid'];
						$handle['waybillid'] = trim($waybillid);
						$handle['placeid'] = $row['placeid'];
						$handle['placename'] = $row['placename'];
						$handle['status'] = 14;
						$handle['remark'] = '快递跟踪';
						
						$lid = $historydb->insert($handle);

						echo "&nbsp;&nbsp;".date('Y-m-d H:i:s')."&nbsp;<font color='green'>导入  ".$waybillid."  邮单号 $expressnumber 已成功! <br/></font>";
						
						}else{
							echo "&nbsp;&nbsp;".date('Y-m-d H:i:s')."&nbsp;<font color='red'>导入  ".$waybillid."  邮单号 $expressnumber 已失败! <br/></font>";
						
						}
					

					}//end if empty
				}// end for
			}//end if 

		
		}else{

			include $this->admin_tpl('house_jihuo_moreimport');
		}
	}

	//集货点包裹批量添加
	public function house_jihuo_moreadd(){
		
	

		if(isset($_POST['dosubmit'])) {//导入操作

			$importpath = PHPCMS_PATH.trim($_POST['importpath']);
			$data = new Spreadsheet_Excel_Reader();  
			$data->setOutputEncoding('utf-8'); 
			
			//邮单号 客户编号 客户姓名  商品属性 物品数量  物品重量 物品价值 到货入库 备注

			$mdb = pc_base::load_model('member_model');
			$pdb = pc_base::load_model('package_model');
			
			if(empty($_POST['importpath'])){
				echo "&nbsp;&nbsp;".date('Y-m-d H:i:s')."&nbsp;<font color='red'>请上传包裹xls导入文件! <br/></font>";
				exit;
			}

			if(file_exists($importpath))
			{
				$data->read($importpath); 
				$oknum=0;
				$errnum=0;

				if( $data->sheets[0]['cells'][1][7]!="物品重量" ||  $data->sheets[0]['cells'][1][11]!="备注" ){
					echo "&nbsp;&nbsp;".date('Y-m-d H:i:s')."&nbsp;<font color='red'>请上传正确包裹xls导入文件! <br/></font>";
					exit;
				}

				for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++)     
				{
					
					$storageid=trim($data->sheets[0]['cells'][$i][2]);
					if(empty($storageid)){
						$storageid=$this->hid;
					}

					$expressno=trim($data->sheets[0]['cells'][$i][3]);
					$expressno=str_replace("#","",trim($expressno));
					$userid=trim(str_replace(PHP_EOL, '',$data->sheets[0]['cells'][$i][4]));
					$goodsname=trim(str_replace(PHP_EOL, '',$data->sheets[0]['cells'][$i][5]));

					$issend=trim(str_replace(PHP_EOL, '',$data->sheets[0]['cells'][$i][10]));
					$isdanno=trim(str_replace(PHP_EOL, '',$data->sheets[0]['cells'][$i][12]));
					
					$mdb->set_model();
					$urow=$mdb->get_one(array('userid'=>$userid),'username',1);
					$_username = trim($urow['username']);
					$mdb->set_model(10);
					$usrow=$mdb->get_one(array('userid'=>$userid),'firstname,lastname',1);
					$truename=$usrow['lastname'].$usrow['firstname'];

					$infos=array();
					
					$infos['expressid']=trim($data->sheets[0]['cells'][$i][1]);
					$infos['expressname']=$this->getallship($infos['expressid']);
					$infos['truename']=$truename;

					$infos['storageid'] = $storageid;
					$infos['expressno']=$expressno;
					$infos['userid']=$userid;
					$infos['username']=$_username;
					$infos['goodsname']=$goodsname;
					$infos['amount']=trim(str_replace(PHP_EOL, '',$data->sheets[0]['cells'][$i][6]));
					$infos['weight']=trim(str_replace(PHP_EOL, '',$data->sheets[0]['cells'][$i][7]));
					$infos['price']=trim(str_replace(PHP_EOL, '',$data->sheets[0]['cells'][$i][8]));
					$infos['status']=trim(str_replace(PHP_EOL, '',$data->sheets[0]['cells'][$i][9]));
					$infos['remark']=trim(str_replace(PHP_EOL, '',$data->sheets[0]['cells'][$i][11]));
					$infos['operatorid'] = $this->userid;
					$infos['operatorname'] = $this->username;
					
					if(empty($infos['username']) || empty($expressno)){
							echo "&nbsp;&nbsp;".date('Y-m-d H:i:s')."&nbsp;<font color='red'>导入用户 ".$infos['username']."($userid) 或 邮单号 $expressno 不存在! <br/></font>";
					}else{

					$r = $pdb->get_one("expressno='".$infos['expressno']."'");
					
					

					if($r){

						if($isdanno==1){
							$pdb->update($infos,array('expressno'=>$infos['expressno']));
							$this->update_all_package_status($expressno,intval($issend));
						}
						
						echo "&nbsp;&nbsp;".date('Y-m-d H:i:s')."&nbsp;<font color='#ff6600'>导入用户 ".$infos['username']."($userid) 邮单号 $expressno 已存在! <br/></font>";
					}else{
						
						$infos['siteid'] = $this->get_siteid();
						$infos['addtime'] = SYS_TIME;
						
						if($pdb->insert($infos)){
								
								$this->update_all_package_status($expressno,intval($issend));

								echo "&nbsp;&nbsp;".date('Y-m-d H:i:s')."&nbsp;<font color='green'>导入用户 ".$infos['username']."($userid) 邮单号 $expressno 已成功! <br/></font>";
						}
						
					}

					}//end if empty
				}// end for
			}//end if 

		
		}

		include $this->admin_tpl('house_jihuo_moreadd');
	}

	//获取总增值费用
	public function getallvaluedfee($waybillid, $returncode=''){

			$valuedfee=0;			
			$number=0;
			$allwaybill_goods = $this->getwaybill_goods(trim($waybillid), $returncode);

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
			$info=$this->wbdb->get_one(array('waybillid'=>trim($waybillid),'returncode'=>$returncode),'addvalues');
			$allsrvaddvalues=string2array($info['addvalues']);

			foreach($allsrvaddvalues  as $bill){
						
				if($bill['title']=='保险'){
						$valuedfee+=floatval($bill['price'])*3/100;//保险费
				}else{
						$valuedfee+=floatval($bill['price']);
				}
						
			}
			
			
			return $valuedfee;//增值fee
	
	}

	public function house_waybill_morehandle(){
		
		if(isset($_POST['dosubmit'])) {//导入操作

			$importpath = PHPCMS_PATH.trim($_POST['importpath']);
			$data = new Spreadsheet_Excel_Reader();  
			$data->setOutputEncoding('utf-8'); 
			
			//邮单号 客户编号 客户姓名  商品属性 物品数量  物品重量 物品价值 到货入库 备注

			$mdb = pc_base::load_model('member_model');
			$twdb = pc_base::load_model('shipline_model');
			//获取会员组信息
			$grouplist = getcache('grouplist','member');

			if(empty($_POST['importpath'])){
				echo "&nbsp;&nbsp;".date('Y-m-d H:i:s')."&nbsp;<font color='red'>请上传运单xls导入文件! <br/></font>";
				exit;
			}

			if(file_exists($importpath))
			{
				$data->read($importpath); 
				$oknum=0;
				$errnum=0;
				
				if(count($data->sheets[0]['cells'][1])!=13 || $data->sheets[0]['cells'][1][1]!="NO" ||  $data->sheets[0]['cells'][1][2]!="UserID" ||  $data->sheets[0]['cells'][1][5]!="Tracking-Number" ||  $data->sheets[0]['cells'][1][7]!="Length(in)" ||  $data->sheets[0]['cells'][1][8]!="Width(in)" ||  $data->sheets[0]['cells'][1][9]!="Height(in)" ||  $data->sheets[0]['cells'][1][10]!="Actual-Weight" ){
					echo "&nbsp;&nbsp;".date('Y-m-d H:i:s')."&nbsp;<font color='red'>请上传正确运单xls导入文件! <br/></font>";
					exit;
				}

				for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++)     
				{
					
					$waybillid=trim(str_replace("#","",trim(str_replace(PHP_EOL, '',$data->sheets[0]['cells'][$i][5]))));

					$rs=$this->wbdb->get_one(array('waybillid'=>$waybillid));

					
					$userid=trim(str_replace(PHP_EOL, '',$data->sheets[0]['cells'][$i][2]));
					

					$bill_long=trim(str_replace(PHP_EOL, '',$data->sheets[0]['cells'][$i][7]));//bill_long
					$bill_width=trim(str_replace(PHP_EOL, '',$data->sheets[0]['cells'][$i][8]));//bill_long
					$bill_height=trim(str_replace(PHP_EOL, '',$data->sheets[0]['cells'][$i][9]));//bill_long

					$totalweight=trim(str_replace(PHP_EOL, '',$data->sheets[0]['cells'][$i][10]));//totalweight//实际重量

					$factweight=0;
					$totalship=0;
					$allvaluedfee=0;
					$memberdiscount=0;
					$otherdiscount=trim(str_replace(PHP_EOL, '',$data->sheets[0]['cells'][$i][11]));//otherdiscount
					$otherfee=trim(str_replace(PHP_EOL, '',$data->sheets[0]['cells'][$i][12]));//otherfee //其它费用
					$payedfee=0;
					

					$issend=trim(str_replace(PHP_EOL, '',$data->sheets[0]['cells'][$i][13]));//send email

					$sendid = $rs['sendid'];//
					$takeid = $rs['takeid'];//
					$storageid = $rs['storageid'];//
					$shippingid = $rs['shippingid'];//
					$sysbillid = $rs['sysbillid'];//

					$urow=$mdb->get_one(array('userid'=>$userid),'username',1);
					if($urow && trim($waybillid)!=""){

						if(floatval($allvaluedfee)<=0){
							$allvaluedfee=$this->getallvaluedfee(trim($waybillid), $rs['returncode']);
						}

						$useramount = floatval($urow['amount']);	

						//获取实际重及体积重
		
						$shipp = $twdb->get_one(array('aid'=>intval($shippingid)));
						$tweightfee=0;
						$vweightfee=0;
						if($shipp){
							$tweightfee=floatval($shipp['price']);//实际重运费
							$vweightfee=floatval($shipp['volumeprice']);//体积重运费
							$discounttype=trim($shipp['discount']);
						}

						if(!empty($discounttype)){
							$memberdiscount=floatval($discounttype)*100;
						}

						$memberdiscount = floatval($memberdiscount)/100;//会员折扣
						$otherdiscount = floatval($otherdiscount)/100;//额外折扣
						
						$volumefee=0;//体积费
						$discount = floatval($memberdiscount)+floatval($otherdiscount);
						if($discount==0){
							$discount =1;//不打折
						}

						$volumeweight=$bill_long*$bill_width*$bill_height/139;//体积重

						if(floatval($volumeweight)>floatval($totalweight)){//体积重>实际重
						//运费是=实际重量*实际重量运费 +（体积重-实际重量）*体积重运费
						//收费重量
						/*
						收费重量（磅）
						1、如果实际重量>=体积重量，收费重量（磅）=实际重量
						2、如果实际重量<体积重量，收费重量（磅）=实际重量+体积重*线路体积重量运费/线路实际重量运费）
						*/
						if(floatval($volumeweight)>1.6*floatval($totalweight)){
							$pay_feeweight=floatval($volumeweight)-1.6*floatval($totalweight);
						}else{
							$pay_feeweight=0;
						}
						$totalship = $totalweight*$tweightfee+$pay_feeweight*$vweightfee;
						
						$volumefee = $pay_feeweight*$vweightfee;
							$gongshi="<font color=red>总运费$totalship=实际重量$totalweight*实际重量运费$tweightfee +额外收费重量$pay_feeweight*体积重运费$vweightfee</font>";
						}else{
								
								
								

								$totalship=$totalweight*$tweightfee;
								$pay_feeweight=0;
								$volumefee=0;
								$gongshi="<font color=red>总运费$totalship=实际重量$totalweight*实际重量运费$tweightfee</font>";
						}

						$pay_feeweight=round(floatval($pay_feeweight),2);
						$totalship=round(floatval($totalship),2);
						$payedfee=floatval($totalship)*$discount+floatval($allvaluedfee)+$otherfee;//打折
						$payedfee=round($payedfee,2);

						$infos=array();
						$infos['status']=4;
						$infos['bill_long']=$bill_long;
						$infos['bill_width']=$bill_width;
						$infos['bill_height']=$bill_height;
						$infos['factweight']=$factweight;
						$infos['goodsname']=$goodsname;
						$infos['totalweight']=$totalweight;
						$infos['totalship']=$totalship;
						$infos['volumeweight']=$volumeweight;
						$infos['allvaluedfee']=$allvaluedfee;
						$infos['memberdiscount']=$memberdiscount;
						$infos['otherdiscount']=$otherdiscount;
						$infos['payedfee']=$payedfee;
						$infos['otherfee']=$otherfee;
						$infos['volumefee']=$volumefee;
						$infos['sendid']=$sendid;
						$infos['takeid']=$takeid;
						$infos['storageid']=$storageid;
						$infos['shippingid']=$shippingid;

						if(floatval($payedfee)>0 && floatval($bill_long)>0 && floatval($bill_width)>0 && floatval($bill_height)>0 && floatval($totalweight)>0){
							if($this->wbdb->update($infos,array('waybillid'=>trim($waybillid)))){
								echo "&nbsp;&nbsp;".date('Y-m-d H:i:s')."&nbsp;<font color='green'>导入用户 ($userid) 或 运单号 $waybillid 处理成功!  ".$gongshi."<br/></font>";

								//发邮件
								$this->sendemailwaybill($sysbillid,$this->getwaybilltatus($infos['status'],$infos['storageid']),intval($issend));
							}
						}else{
							echo "&nbsp;&nbsp;".date('Y-m-d H:i:s')."&nbsp;<font color='blue'>导入用户 ($userid) 或 运单号 $waybillid 未处理! <br/></font>";
						}

					}else{
						echo "&nbsp;&nbsp;".date('Y-m-d H:i:s')."&nbsp;<font color='red'>导入用户 ($userid) 或 运单号 $waybillid 不存在! <br/></font>";
					}

					
					
					
					
				}// end for
			}//end if 

		
		}

		include $this->admin_tpl('house_waybill_morehandle');
	}
	//运单打印

	public function printbill(){
		$bid = intval($_GET['bid']);
		
		$wdb = pc_base::load_model('waybill_model');

		$info = $wdb->get_one("siteid='".$this->get_siteid()."' and aid='$bid'");
		
		$udb = pc_base::load_model('member_model');
		$udb->set_model();
		$urow = $udb->get_one(" userid='".$info['userid']."'");
		$mobile = $urow['mobile'];
		$udb->set_model(10);
		$urow = $udb->get_one(" userid='".$info['userid']."'");
		

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

		foreach( $this->getaddresslist() as  $addr ){
			

			if ($addr['aid']==$info['sendaddressid']){//发件
				$send_truename=$addr['truename'];
				$send_country=$addr['country'];
				$send_province=$addr['province'];
				$send_city=$addr['city'];
				$send_address=$addr['address'];
				$send_postcode=$addr['postcode'];
				$send_mobile=$addr['mobile'];
				$send_company=$addr['company'];
			}

			if ($addr['aid']==$info['takeaddressid']){//收件
				$take_truename=$addr['truename'];
				$take_country=$addr['country'];
				$take_province=$addr['province'];
				$take_city=$addr['city'];
				$take_address=$addr['address'];
				$take_postcode=$addr['postcode'];
				$take_mobile=$addr['mobile'];
				$take_company=$addr['company'];
			}
		}

		
		include $this->admin_tpl('waybill_print');

	}

	//中转点
	public function house_zhongzhuan(){
		
		$h = isset($_GET['h']) ? intval($_GET['h']) : 1;
		

		$kb = isset($_GET['kb']) ? intval($_GET['kb']) : 0;
		//$wbdb = pc_base::load_model('waybill_model');
		//$hdb = pc_base::load_model('waybill_huodan_model');
		$fhdb = pc_base::load_model('waybill_fahuo_model');
		//$kbdb = pc_base::load_model('waybill_kaban_model');

		$keywords = isset($_GET['keywords']) ? trim($_GET['keywords']) : '';
		
		$sp = isset($_GET['sp']) ? trim($_GET['sp']) : '';

		$status = isset($_GET['status']) ? trim($_GET['status']) : '';

		
		$addsql="";

		$start_time = isset($_GET['start_time']) ? $_GET['start_time'] : '';
		$end_time = isset($_GET['end_time']) ? $_GET['end_time'] : '';
		
		if($start_time!='' && $end_time!=''){
			$_tsql=" and splittime>='".strtotime($start_time.' 00:00:01')."' and splittime<='".strtotime($end_time.' 23:59:59')."' ";
		}

		if(!empty($split_status)){
			//$_tsql.=" and split_status='$split_status'";
		}
		
		if($kb==1){//已折分的卡板列表
		
		$sql = "";
		$sql = "`siteid`='".$this->get_siteid()."' and storageid='$this->hid' and position='zhongzhuan' and (split_status=1 or split_status=2) $_tsql";
		if(!empty($keywords))
		{
				$sql.=" and (userid='$keywords' or username like '%$keywords%' or kabanno like '%$keywords%' )";
		}

		$page = max(intval($_GET['page']), 1);
		$datas = $this->kbdb->listinfo($sql, 'splittime DESC', $page);
		}else if($kb==2){//已折分的运单列表
		
			$sql = "";
			$sql = "`siteid`='".$this->get_siteid()."' and placeid='$this->hid' and position='zhongzhuan' and split_status=1 $_tsql";
			
			if(!empty($keywords))
			{
				$sql.=" and (userid='$keywords' or username like '%$keywords%' or waybillid like '%$keywords%' )";
			}

			if(!empty($status)){
				$sql.=" and status='$status'";
			}

			$page = max(intval($_GET['page']), 1);
			$datas = $this->wbdb->listinfo($sql, 'splittime desc,userid asc', $page,50);

		}else{	
		$sql = "";
		$sql = "`siteid`='".$this->get_siteid()."' and storageid='$this->hid' and position='zhongzhuan' $_tsql";
		
		if(!empty($keywords))
		{
			$sql.=" and (userid='$keywords' or username like '%$keywords%' or huodanno like '%$keywords%' or kabanno like '%$keywords%')";
		}
		
		
		if($h==1){//未处理
			//$sql.=" and status in(9,12,17) and handle_status=0  ";
		}else if($h==0){
			//$sql.=" and handle_status=1 ";
		}

		$sql.=" and status in(9,12,17)  and handle_status=0 ";
		
		$sql.=$addsql;

		//echo $sql;exit;

		$page = max(intval($_GET['page']), 1);
		$_datas = $this->whuodandb->listinfo($sql, 'addtime desc,handle_status asc', $page);
		$datas=array();
		foreach($_datas as $v){
			
			if(trim($v['shipname'])==""){
				$rows = $fhdb->get_one(array('fahuono'=>trim($v['huodanno'])));
				$v['shipname']=trim($rows['shipname']);
			}
			$datas[]=$v;
		}
		

		}

		include $this->admin_tpl('house_zhongzhuan');
	}
	
	public function house_backhuodan(){
		$id = intval($_GET['id']);	
		
		$row=$this->whuodandb->get_one(array('id'=>$id));
		if($row){
			$kabannos = explode("\n",trim($row['kabanno']));
			foreach($kabannos as $kb){
				$this->wbdb->update(array('split_status'=>0),array('waybillid'=>trim($kb)));
			}

			$this->historydb->delete(array('delete_flag'=>'hd_'.trim($row['huodanno'])));
		}
		$this->whuodandb->update(array('split_status'=>0),array('id'=>$id));
		
		showmessage(L('operation_success'), HTTP_REFERER,'');

	}

	public function house_backkaban(){
		$id = intval($_GET['id']);	
		
		$row=$this->kbdb->get_one(array('id'=>$id));
		if($row){
			$waybillids = explode("\n",trim($row['waybillid']));
			foreach($waybillids as $kb){
				$this->wbdb->update(array('split_status'=>0),array('waybillid'=>trim($kb)));
			}

			$this->historydb->delete(array('delete_flag'=>'kb_'.trim($row['kabanno'])));
		}
		$this->kbdb->update(array('split_status'=>1),array('id'=>$id));
		
		showmessage(L('operation_success'), HTTP_REFERER,'');

	}

	public function isbackhuodan($id){
		$flag=0;
		$row=$this->whuodandb->get_one(array('id'=>$id));
		if($row){
			$kabannos = explode("\n",trim($row['kabanno']));
			foreach($kabannos as $kb){
				$kbrow=$this->wbdb->get_one(array('waybillid'=>trim($kb)));
				if($kbrow){
					$flag = $kbrow['split_status'];
				}
			}
		}
		
		return $flag;
	}

	public function isbackkaban($id){
		$flag=1;
		$row=$this->kbdb->get_one(array('id'=>$id));
		if($row){
			$kabannos = explode("\n",trim($row['waybillid']));
			$flagg=$row['split_status'];
			
			foreach($kabannos as $kb){
				$kbrow=$this->wbdb->get_one(array('waybillid'=>trim($kb)));
				if(!($kbrow['position']=='zhongzhuan' && $flagg==2)){
					$flag = 0 ;
				}
			}
		}
		
		return $flag;
	}

	//派发点
	public function house_paifa(){
		
		$hid = intval($_GET['hid']);
		$ku = isset($_GET['ku']) ? intval($_GET['ku']) : 0 ;
		$sta = isset($_GET['sta']) ? intval($_GET['sta']) : 0;
		$status = isset($_GET['status']) ? intval($_GET['status']) : '';
		$uid = isset($_GET['uid']) ? intval($_GET['uid']) : 0 ;

		$act = isset($_GET['act']) ? trim($_GET['act']) : '';

		//$wdb = pc_base::load_model('waybill_model');
		//$kbdb = pc_base::load_model('waybill_kaban_model');
		$hdb = pc_base::load_model('waybill_huodan_model');
		
		$keywords = isset($_GET['keywords']) ? trim($_GET['keywords']) : '';
		
		$addsql="";
		
		$start_time = isset($_GET['start_time']) ? $_GET['start_time'] : '';
		$end_time = isset($_GET['end_time']) ? $_GET['end_time'] : '';
		
		if($start_time!='' && $end_time!=''){
			$_tsql=" and splittime>='".strtotime($start_time.' 00:00:01')."' and splittime<='".strtotime($end_time.' 23:59:59')."' ";
		}

		if($ku==2){
			
			if(!empty($keywords))
			{
				$addsql=" and (userid='$keywords' or username like '%$keywords%' or waybillid like '%$keywords%' or kabanno like '%$keywords%' ) ";
			}

			$sql = "";
			$sql = "`siteid`='".$this->get_siteid()."' and storageid='$this->hid' and position='paifa' and handle_status='$sta' $addsql $_tsql";
			$page = max(intval($_GET['page']), 1);
			$datas = $this->kbdb->listinfo($sql, 'splittime DESC', $page);
			
		}else if($ku==1){
			if(!empty($keywords))
			{
				$addsql=" and ( username like '%$keywords%' or waybillid like '%$keywords%')    ";
			}

			if($uid>0){
				$addsql.=" and userid='".$uid."' ";
			}
			
			$sql = "siteid='".$this->get_siteid()."'    $addsql $_tsql";

			
			$sql.=" and status in(7,12) ";
			



			$page = max(intval($_GET['page']), 1);
			
			$datas = $this->wbdb->listinfo($sql, 'splittime DESC,userid asc', $page,50);


			if($act=='download'){
			require_once PHPCMS_PATH.'api/PHPExcel.php';       
			require_once PHPCMS_PATH.'api/PHPExcel/Writer/Excel5.php';  
			
			
			$objPHPExcel = new PHPExcel();
			$objPHPExcel->setActiveSheetIndex(0);    
			$objActSheet = $objPHPExcel->getActiveSheet(); 
			//设置当前活动sheet的名称
			$objActSheet->setTitle("运单列表");
			//客户账号 真实姓名 运单号 邮单号 收件人 收货城市 状态 添加时间 
			//标头 
			/**
			发 货 地： 美国 收 货 地： 中国大陆 
快递公司： 美国波特兰仓库 运单类型： 渠道B免税（普通货物） 

			*/
			
			$excelHeader=array('序号','用户ID','客户账号','真实姓名','运单号','邮单号','邮单商品名称','邮单数量','邮单重量','邮单价格','收件人','收货城市','联系方式','增值服务','状态','添加时间','备注','系统单号','长(英寸)','宽(英寸)','高(英寸)','体积重','体积费','实际重量(磅)','额外收费重量(磅)','总运费','总增值费','会员折扣%','额外折扣%','其它费用','实付费用','运单物品名称','发送邮件通知(1=是,0=否)','发货地','收货地','仓库','运单类型','发货地ID','收货地ID','仓库ID','运单类型ID','收货地址');

			foreach($excelHeader as $k=>$v){
				$objActSheet->setCellValueByColumnAndRow($k,1,$v);
			}
			$m_db = pc_base::load_model('member_model');
			
			$twdb = pc_base::load_model('shipline_model');
		
			

			//数据
			$wsql="select * from t_waybill where ".$sql." order by userid desc,addtime desc ";
			$result = $this->db->query($wsql);
			$alldatas = $this->db->fetch_array($result);
			$rownum=1;
			

			foreach($alldatas as $num=>$row) {
				$rownum+=1;

				
				
				$takeperson="";
				$takecity="";
				$mobile="";
				

				$addr=explode("|",$row['takeaddressname']);
		
				$takeperson=$addr[0];
				$takecity=$addr[4];
				$mobile=$addr[1];

				
				
				$takeaddress=$addr['truename'].'  '.$addr['mobile'].' '.$addr['country'].$addr['province'].$addr['city'].' '.$addr['address'].'.  '.$addr['postcode'];

				$allexp=$this->getwaybill_goods($row['waybillid'], $row['returncode']);
				$exp="";
				$expname="";
				$expamount="";
				$expweight="";
				$expprice="";
				
				$alladdvalues="";

				foreach($allexp as $v){
					$exp.="#".$v['expressno']."\n";
					$expname.=$v['goodsname']."\n";
					$expamount.=$v['amount']."\n";
					$expweight.=$v['weight']."\n";
					$expprice.=$v['price']."\n";
				
			
					//统计增值服务
					if($v['addvalues']){
					$goodaddvalues = string2array($v['addvalues']);

					
					foreach($goodaddvalues as $addval){
						
						$val = explode("|",$addval);

						$alladdvalues.=$val[1].$val[7].$val[6].'/'.$val[4]." ";
						
					}
					}

				}

				if($waybill['addvalues']){
					$waybillallvalues=string2array($waybill['addvalues']);
					foreach($waybillallvalues as  $srv){
							$alladdvalues.=$srv['title'].$srv['currencyname'].$srv['price'].'/'.$srv['unitname']." ";
					}
				}
			//'序号','用户ID','客户账号','真实姓名','运单号','邮单号','邮单商品名称','邮单数量','邮单重量','邮单价格','收件人','收货城市','联系方式','增值服务','状态','添加时间','备注'
				$objActSheet->setCellValueByColumnAndRow(0,$rownum,$row['aid']);
				$objActSheet->setCellValueByColumnAndRow(1,$rownum,$row['userid']);
				$objActSheet->setCellValueByColumnAndRow(2,$rownum,$row['username']);
				$objActSheet->setCellValueByColumnAndRow(3,$rownum,$row['truename']);
				$objActSheet->setCellValueByColumnAndRow(4,$rownum,"#".$row['waybillid']);
				$objActSheet->setCellValueByColumnAndRow(5,$rownum,$exp);
				$objActSheet->setCellValueByColumnAndRow(6,$rownum,$expname);
				$objActSheet->setCellValueByColumnAndRow(7,$rownum,$expamount);
				$objActSheet->setCellValueByColumnAndRow(8,$rownum,$expweight);
				$objActSheet->setCellValueByColumnAndRow(9,$rownum,$expprice);
				$objActSheet->setCellValueByColumnAndRow(10,$rownum,$takeperson);
				$objActSheet->setCellValueByColumnAndRow(11,$rownum,$takecity);
				$objActSheet->setCellValueByColumnAndRow(12,$rownum,"#".$mobile);
				$objActSheet->setCellValueByColumnAndRow(13,$rownum,$alladdvalues);//增值服务
				$objActSheet->setCellValueByColumnAndRow(14,$rownum,str_replace(L('enter_xxstorage'),$row['placename'],$this->getwaybilltatus($row['status'])));
				$objActSheet->setCellValueByColumnAndRow(15,$rownum,date('Y-m-d H:i:s',$row['addtime']));
				$objActSheet->setCellValueByColumnAndRow(16,$rownum,$row['remark']);

				//'系统单号','长(英寸)','宽(英寸)','高(英寸)','体积重','体积费','实际重量(磅)','额外收费重量(磅)','总运费','总增值费','会员折扣%','额外折扣%','其它费用','实付费用','导入必填-运单物品名称','发送邮件通知(1=是,0=否)'

				$objActSheet->setCellValueByColumnAndRow(17,$rownum,$row['sysbillid']);
				$objActSheet->setCellValueByColumnAndRow(18,$rownum,floatval($row['bill_long']));
				$objActSheet->setCellValueByColumnAndRow(19,$rownum,floatval($row['bill_width']));
				$objActSheet->setCellValueByColumnAndRow(20,$rownum,floatval($row['bill_height']));
				$objActSheet->setCellValueByColumnAndRow(21,$rownum,floatval($row['volumeweight']));
				$objActSheet->setCellValueByColumnAndRow(22,$rownum,floatval($row['volumefee']));
				$objActSheet->setCellValueByColumnAndRow(23,$rownum,floatval($row['totalweight']));
				$objActSheet->setCellValueByColumnAndRow(24,$rownum,floatval($row['factweight']));
				$objActSheet->setCellValueByColumnAndRow(25,$rownum,floatval($row['totalship']));
				$objActSheet->setCellValueByColumnAndRow(26,$rownum,floatval($row['allvaluedfee']));
				$objActSheet->setCellValueByColumnAndRow(27,$rownum,floatval($row['memberdiscount']));
				$objActSheet->setCellValueByColumnAndRow(28,$rownum,floatval($row['otherdiscount']));
				$objActSheet->setCellValueByColumnAndRow(29,$rownum,floatval($row['otherfee']));
				$objActSheet->setCellValueByColumnAndRow(30,$rownum,floatval($row['payedfee']));
				$objActSheet->setCellValueByColumnAndRow(31,$rownum,$row['goodsname']);
				$objActSheet->setCellValueByColumnAndRow(32,$rownum,0);

				//'发货地','收货地','仓库','运单类型','导入必填-发货地ID','导入必填-收货地ID','导入必填-仓库ID','导入必填-运单类型ID'
					
				$sendname= $row['sendname'];		
				$takename= $row['takename'];

				$objActSheet->setCellValueByColumnAndRow(33,$rownum,$sendname);
				$objActSheet->setCellValueByColumnAndRow(34,$rownum,$takename);
				$objActSheet->setCellValueByColumnAndRow(35,$rownum,$row['storagename']);
				$objActSheet->setCellValueByColumnAndRow(36,$rownum,$row['shippingname']);
				$objActSheet->setCellValueByColumnAndRow(37,$rownum,$row['sendid']);
				$objActSheet->setCellValueByColumnAndRow(38,$rownum,$row['takeid']);
				$objActSheet->setCellValueByColumnAndRow(39,$rownum,$row['storageid']);
				$objActSheet->setCellValueByColumnAndRow(40,$rownum,$row['shippingid']);
				$objActSheet->setCellValueByColumnAndRow(41,$rownum,$takeaddress);
				
			}
			

			$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
			//在浏览器导出
			header("Content-Type: application/force-download");
			header('Content-Type: application/vnd.ms-excel');
			header("Content-Disposition: attachment;filename=\"".iconv("UTF-8","GB2312","运单列表").date('ymdHis').".xls\"");
			header('Cache-Control: max-age=0');
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
			header("Pragma: no-cache");
			$objWriter->save("php://output");
			exit;

			
		}else if($act=='simpledownload'){
			require_once PHPCMS_PATH.'api/PHPExcel.php';       
			require_once PHPCMS_PATH.'api/PHPExcel/Writer/Excel5.php';  
			
			
			$objPHPExcel = new PHPExcel();
			$objPHPExcel->setActiveSheetIndex(0);    
			$objActSheet = $objPHPExcel->getActiveSheet(); 
			//设置当前活动sheet的名称
			$objActSheet->setTitle("运单列表");
			//客户账号 真实姓名 运单号 邮单号 收件人 收货城市 状态 添加时间 
			//标头 
			/**
			发 货 地： 美国 收 货 地： 中国大陆 
快递公司： 美国波特兰仓库 运单类型： 渠道B免税（普通货物） 

			*/
			
			//$excelHeader=array('序号','导入必填-用户ID','客户账号','真实姓名','导入必填-运单号','邮单号','邮单商品名称','邮单数量','邮单重量','邮单价格','收件人','收货城市','联系方式','增值服务','状态','添加时间','备注','系统单号','导入必填-长(英寸)','导入必填-宽(英寸)','导入必填-高(英寸)','体积重','体积费','导入必填-实际重量(磅)','额外收费重量(磅)','总运费','总增值费','会员折扣%','额外折扣%','其它费用','实付费用','运单物品名称','发送邮件通知(1=是,0=否)','发货地','收货地','仓库','运单类型','导入必填-发货地ID','导入必填-收货地ID','导入必填-仓库ID','导入必填-运单类型ID');

			$excelHeader=array('NO','UserID','User-Name','Real-Name','Tracking-Number','Usa-Tracking-Number','Length(in)','Width(in)','Height(in)','Actual-Weight','Extra-Discount','Other-Costs','Send-Email(1=yes,0=no)');
			

			foreach($excelHeader as $k=>$v){
				$objActSheet->setCellValueByColumnAndRow($k,1,$v);
			}
			$m_db = pc_base::load_model('member_model');
			
			$twdb = pc_base::load_model('shipline_model');
		
			

			//数据
			$wsql="select * from t_waybill where ".$sql." order by userid desc,addtime desc ";
			$result = $this->db->query($wsql);
			$waybill_datas = $this->db->fetch_array($result);
			$rownum=1;
			
			foreach($waybill_datas as $num=>$row) {
				$rownum+=1;
				
				$takeperson="";
				$takecity="";
				$mobile="";

				$addr=explode("|",$waybill['takeaddressname']);
		
				$takeperson=$addr[0];
				$takecity=$addr[4];
				$mobile=$addr[1];
				

				$allexp=$this->getwaybill_goods($row['waybillid'], $row['returncode']);
				$exp="";
				$expname="";
				$expamount="";
				$expweight="";
				$expprice="";
				foreach($allexp as $v){
					$exp.="#".$v['expressno']."\n";
					$expname.=$v['goodsname']."\n";
					$expamount.=$v['amount']."\n";
					$expweight.=$v['weight']."\n";
					$expprice.=$v['price']."\n";
				
			
					//统计增值服务
					$alladdvalues="";
					if($v['addvalues']){
					$goodaddvalues = string2array($v['addvalues']);

					foreach($goodaddvalues as $addval){
						
						$val = explode("|",$addval);

						$alladdvalues.=$val[1].$val[7].$val[6].'/'.$val[4]." ";
						
					}
					}

				}

				if($row['addvalues']){
					$waybillallvalues=string2array($row['addvalues']);
					foreach($waybillallvalues as  $srv){
							$alladdvalues.=$srv['title'].$srv['currencyname'].$srv['price'].'/'.$srv['unitname']." ";
					}
				}
			//'序号','用户ID','客户账号','真实姓名','运单号','邮单号','邮单商品名称','邮单数量','邮单重量','邮单价格','收件人','收货城市','联系方式','增值服务','状态','添加时间','备注'
				$objActSheet->setCellValueByColumnAndRow(0,$rownum,$row['aid']);
				$objActSheet->setCellValueByColumnAndRow(1,$rownum,$row['userid']);
				$objActSheet->setCellValueByColumnAndRow(2,$rownum,$row['username']);
				$objActSheet->setCellValueByColumnAndRow(3,$rownum,$row['truename']);
				$objActSheet->setCellValueByColumnAndRow(4,$rownum,"#".$row['waybillid']);
				$objActSheet->setCellValueByColumnAndRow(5,$rownum,$exp);
				$objActSheet->setCellValueByColumnAndRow(6,$rownum,floatval($row['bill_long']));
				$objActSheet->setCellValueByColumnAndRow(7,$rownum,floatval($row['bill_width']));
				$objActSheet->setCellValueByColumnAndRow(8,$rownum,floatval($row['bill_height']));
				$objActSheet->setCellValueByColumnAndRow(9,$rownum,floatval($row['totalweight']));
				$objActSheet->setCellValueByColumnAndRow(10,$rownum,floatval($row['otherdiscount']));
				$objActSheet->setCellValueByColumnAndRow(11,$rownum,floatval($row['otherfee']));
				$objActSheet->setCellValueByColumnAndRow(12,$rownum,1);

			}
			

			$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
			//在浏览器导出
			header("Content-Type: application/force-download");
			header('Content-Type: application/vnd.ms-excel');
			header("Content-Disposition: attachment;filename=\"".iconv("UTF-8","GB2312","tracking-number").date('ymdHis').".xls\"");
			header('Cache-Control: max-age=0');
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
			header("Pragma: no-cache");
			$objWriter->save("php://output");
			exit;
		}






		}else if($ku==0){

			if(!empty($keywords))
			{
				$addsql=" and (userid='$keywords' or username like '%$keywords%' or huodanno like '%$keywords%' or kabanno like '%$keywords%' )  ";
			}
		
			$sql = "siteid='".$this->get_siteid()."' and storageid='$this->hid' and position='paifa'  and handle_status='$sta' $addsql $_tsql";
			
			$page = max(intval($_GET['page']), 1);
			$datas = $this->whuodandb->listinfo($sql, 'splittime desc,userid asc', $page);







			if($act=='download'){
			require_once PHPCMS_PATH.'api/PHPExcel.php';       
			require_once PHPCMS_PATH.'api/PHPExcel/Writer/Excel5.php';  
			
			
			$objPHPExcel = new PHPExcel();
			$objPHPExcel->setActiveSheetIndex(0);    
			$objActSheet = $objPHPExcel->getActiveSheet(); 
			//设置当前活动sheet的名称
			$objActSheet->setTitle("运单列表");
			//客户账号 真实姓名 运单号 邮单号 收件人 收货城市 状态 添加时间 
			//标头 
			/**
			发 货 地： 美国 收 货 地： 中国大陆 
快递公司： 美国波特兰仓库 运单类型： 渠道B免税（普通货物） 

			*/
			
			$excelHeader=array('序号','用户ID','客户账号','真实姓名','运单号','邮单号','邮单商品名称','邮单数量','邮单重量','邮单价格','收件人','收货城市','联系方式','增值服务','状态','添加时间','备注','系统单号','长(英寸)','宽(英寸)','高(英寸)','体积重','体积费','实际重量(磅)','额外收费重量(磅)','总运费','总增值费','会员折扣%','额外折扣%','其它费用','实付费用','运单物品名称','发送邮件通知(1=是,0=否)','发货地','收货地','仓库','运单类型','发货地ID','收货地ID','仓库ID','运单类型ID','收货地址');

			foreach($excelHeader as $k=>$v){
				$objActSheet->setCellValueByColumnAndRow($k,1,$v);
			}
			$m_db = pc_base::load_model('member_model');
			
			$twdb = pc_base::load_model('shipline_model');
		
			

			//数据
			$wsql="select * from t_waybill where ".$sql." order by userid desc,addtime desc ";
			$result = $this->db->query($wsql);
			$alldatas = $this->db->fetch_array($result);
			$rownum=1;
			

			foreach($alldatas as $num=>$row) {
				$rownum+=1;

				
				
				$takeperson="";
				$takecity="";
				$mobile="";
				$addr=explode("|",$waybill['takeaddressname']);
		
				$takeperson=$addr[0];
				$takecity=$addr[4];
				$mobile=$addr[1];
				
				$takeaddress=str_replace("|","  ",$waybill['takeaddressname']);

				$allexp=$this->getwaybill_goods($row['waybillid'], $row['returncode']);
				$exp="";
				$expname="";
				$expamount="";
				$expweight="";
				$expprice="";
				foreach($allexp as $v){
					$exp.="#".$v['expressno']."\n";
					$expname.=$v['goodsname']."\n";
					$expamount.=$v['amount']."\n";
					$expweight.=$v['weight']."\n";
					$expprice.=$v['price']."\n";
				
			
					//统计增值服务
					$alladdvalues="";

					if($v['addvalues']){
					$goodaddvalues = string2array($v['addvalues']);

					
					foreach($goodaddvalues as $addval){
						
						$val = explode("|",$addval);

						$alladdvalues.=$val[1].$val[7].$val[6].'/'.$val[4]." ";
						
					}
					}

				}

				if($row['addvalues']){
					$waybillallvalues=string2array($row['addvalues']);
					foreach($waybillallvalues as  $srv){
							$alladdvalues.=$srv['title'].$srv['currencyname'].$srv['price'].'/'.$srv['unitname']." ";
					}
				}
			//'序号','用户ID','客户账号','真实姓名','运单号','邮单号','邮单商品名称','邮单数量','邮单重量','邮单价格','收件人','收货城市','联系方式','增值服务','状态','添加时间','备注'
				$objActSheet->setCellValueByColumnAndRow(0,$rownum,$row['aid']);
				$objActSheet->setCellValueByColumnAndRow(1,$rownum,$row['userid']);
				$objActSheet->setCellValueByColumnAndRow(2,$rownum,$row['username']);
				$objActSheet->setCellValueByColumnAndRow(3,$rownum,$row['truename']);
				$objActSheet->setCellValueByColumnAndRow(4,$rownum,"#".$row['waybillid']);
				$objActSheet->setCellValueByColumnAndRow(5,$rownum,$exp);
				$objActSheet->setCellValueByColumnAndRow(6,$rownum,$expname);
				$objActSheet->setCellValueByColumnAndRow(7,$rownum,$expamount);
				$objActSheet->setCellValueByColumnAndRow(8,$rownum,$expweight);
				$objActSheet->setCellValueByColumnAndRow(9,$rownum,$expprice);
				$objActSheet->setCellValueByColumnAndRow(10,$rownum,$takeperson);
				$objActSheet->setCellValueByColumnAndRow(11,$rownum,$takecity);
				$objActSheet->setCellValueByColumnAndRow(12,$rownum,"#".$mobile);
				$objActSheet->setCellValueByColumnAndRow(13,$rownum,$alladdvalues);//增值服务
				$objActSheet->setCellValueByColumnAndRow(14,$rownum,str_replace(L('enter_xxstorage'),$row['placename'],$this->getwaybilltatus($row['status'])));
				$objActSheet->setCellValueByColumnAndRow(15,$rownum,date('Y-m-d H:i:s',$row['addtime']));
				$objActSheet->setCellValueByColumnAndRow(16,$rownum,$row['remark']);

				//'系统单号','长(英寸)','宽(英寸)','高(英寸)','体积重','体积费','实际重量(磅)','额外收费重量(磅)','总运费','总增值费','会员折扣%','额外折扣%','其它费用','实付费用','导入必填-运单物品名称','发送邮件通知(1=是,0=否)'

				$objActSheet->setCellValueByColumnAndRow(17,$rownum,$row['sysbillid']);
				$objActSheet->setCellValueByColumnAndRow(18,$rownum,floatval($row['bill_long']));
				$objActSheet->setCellValueByColumnAndRow(19,$rownum,floatval($row['bill_width']));
				$objActSheet->setCellValueByColumnAndRow(20,$rownum,floatval($row['bill_height']));
				$objActSheet->setCellValueByColumnAndRow(21,$rownum,floatval($row['volumeweight']));
				$objActSheet->setCellValueByColumnAndRow(22,$rownum,floatval($row['volumefee']));
				$objActSheet->setCellValueByColumnAndRow(23,$rownum,floatval($row['totalweight']));
				$objActSheet->setCellValueByColumnAndRow(24,$rownum,floatval($row['factweight']));
				$objActSheet->setCellValueByColumnAndRow(25,$rownum,floatval($row['totalship']));
				$objActSheet->setCellValueByColumnAndRow(26,$rownum,floatval($row['allvaluedfee']));
				$objActSheet->setCellValueByColumnAndRow(27,$rownum,floatval($row['memberdiscount']));
				$objActSheet->setCellValueByColumnAndRow(28,$rownum,floatval($row['otherdiscount']));
				$objActSheet->setCellValueByColumnAndRow(29,$rownum,floatval($row['otherfee']));
				$objActSheet->setCellValueByColumnAndRow(30,$rownum,floatval($row['payedfee']));
				$objActSheet->setCellValueByColumnAndRow(31,$rownum,$row['goodsname']);
				$objActSheet->setCellValueByColumnAndRow(32,$rownum,0);

				//'发货地','收货地','仓库','运单类型','导入必填-发货地ID','导入必填-收货地ID','导入必填-仓库ID','导入必填-运单类型ID'

				$sendname= $row['sendname'];		
				$takename= $row['takename'];

				$objActSheet->setCellValueByColumnAndRow(33,$rownum,$sendname);
				$objActSheet->setCellValueByColumnAndRow(34,$rownum,$takename);
				$objActSheet->setCellValueByColumnAndRow(35,$rownum,$row['storagename']);
				$objActSheet->setCellValueByColumnAndRow(36,$rownum,$row['shippingname']);

				$objActSheet->setCellValueByColumnAndRow(37,$rownum,$row['sendid']);
				$objActSheet->setCellValueByColumnAndRow(38,$rownum,$row['takeid']);
				$objActSheet->setCellValueByColumnAndRow(39,$rownum,$row['storageid']);
				$objActSheet->setCellValueByColumnAndRow(40,$rownum,$row['shippingid']);
				$objActSheet->setCellValueByColumnAndRow(41,$rownum,$takeaddress);
				
			}
			

			$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
			//在浏览器导出
			header("Content-Type: application/force-download");
			header('Content-Type: application/vnd.ms-excel');
			header("Content-Disposition: attachment;filename=\"".iconv("UTF-8","GB2312","运单列表").date('ymdHis').".xls\"");
			header('Cache-Control: max-age=0');
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
			header("Pragma: no-cache");
			$objWriter->save("php://output");
			exit;

			
		}else if($act=='simpledownload'){
			require_once PHPCMS_PATH.'api/PHPExcel.php';       
			require_once PHPCMS_PATH.'api/PHPExcel/Writer/Excel5.php';  
			
			
			$objPHPExcel = new PHPExcel();
			$objPHPExcel->setActiveSheetIndex(0);    
			$objActSheet = $objPHPExcel->getActiveSheet(); 
			//设置当前活动sheet的名称
			$objActSheet->setTitle("运单列表");
			//客户账号 真实姓名 运单号 邮单号 收件人 收货城市 状态 添加时间 
			//标头 
			/**
			发 货 地： 美国 收 货 地： 中国大陆 
快递公司： 美国波特兰仓库 运单类型： 渠道B免税（普通货物） 

			*/
			
			//$excelHeader=array('序号','导入必填-用户ID','客户账号','真实姓名','导入必填-运单号','邮单号','邮单商品名称','邮单数量','邮单重量','邮单价格','收件人','收货城市','联系方式','增值服务','状态','添加时间','备注','系统单号','导入必填-长(英寸)','导入必填-宽(英寸)','导入必填-高(英寸)','体积重','体积费','导入必填-实际重量(磅)','额外收费重量(磅)','总运费','总增值费','会员折扣%','额外折扣%','其它费用','实付费用','运单物品名称','发送邮件通知(1=是,0=否)','发货地','收货地','仓库','运单类型','导入必填-发货地ID','导入必填-收货地ID','导入必填-仓库ID','导入必填-运单类型ID');

			$excelHeader=array('NO','UserID','User-Name','Real-Name','Tracking-Number','Usa-Tracking-Number','Length(in)','Width(in)','Height(in)','Actual-Weight','Extra-Discount','Other-Costs','Send-Email(1=yes,0=no)');
			

			foreach($excelHeader as $k=>$v){
				$objActSheet->setCellValueByColumnAndRow($k,1,$v);
			}
			$m_db = pc_base::load_model('member_model');
			$twdb = pc_base::load_model('shipline_model');
		
			

			//数据
			$wsql="select * from t_waybill where ".$sql." order by userid desc,addtime desc ";
			$result = $this->db->query($wsql);
			$waybill_datas = $this->db->fetch_array($result);
			$rownum=1;
		
			foreach($waybill_datas as $num=>$row) {
				$rownum+=1;

				
				
				$takeperson="";
				$takecity="";
				$mobile="";
				
				$addr=explode("|",$waybill['takeaddressname']);
		
				$takeperson=$addr[0];
				$takecity=$addr[4];
				$mobile=$addr[1];

				$allexp=$this->getwaybill_goods($row['waybillid'], $row['returncode']);
				$exp="";
				$expname="";
				$expamount="";
				$expweight="";
				$expprice="";
				foreach($allexp as $v){
					$exp.="#".$v['expressno']."\n";
					$expname.=$v['goodsname']."\n";
					$expamount.=$v['amount']."\n";
					$expweight.=$v['weight']."\n";
					$expprice.=$v['price']."\n";
				
			
					//统计增值服务
					$alladdvalues="";

					if($v['addvalues']){
						$goodaddvalues = string2array($v['addvalues']);

						foreach($goodaddvalues as $addval){
							
							$val = explode("|",$addval);

							$alladdvalues.=$val[1].$val[7].$val[6].'/'.$val[4]." ";
							
						}
					}

				}

				if($row['addvalues']){
					$waybillallvalues=string2array($row['addvalues']);
					foreach($waybillallvalues as  $srv){
							$alladdvalues.=$srv['title'].$srv['currencyname'].$srv['price'].'/'.$srv['unitname']." ";
					}
				}
			//'序号','用户ID','客户账号','真实姓名','运单号','邮单号','邮单商品名称','邮单数量','邮单重量','邮单价格','收件人','收货城市','联系方式','增值服务','状态','添加时间','备注'
				$objActSheet->setCellValueByColumnAndRow(0,$rownum,$row['aid']);
				$objActSheet->setCellValueByColumnAndRow(1,$rownum,$row['userid']);
				$objActSheet->setCellValueByColumnAndRow(2,$rownum,$row['username']);
				$objActSheet->setCellValueByColumnAndRow(3,$rownum,$row['truename']);
				$objActSheet->setCellValueByColumnAndRow(4,$rownum,"#".$row['waybillid']);
				$objActSheet->setCellValueByColumnAndRow(5,$rownum,$exp);
				$objActSheet->setCellValueByColumnAndRow(6,$rownum,floatval($row['bill_long']));
				$objActSheet->setCellValueByColumnAndRow(7,$rownum,floatval($row['bill_width']));
				$objActSheet->setCellValueByColumnAndRow(8,$rownum,floatval($row['bill_height']));
				$objActSheet->setCellValueByColumnAndRow(9,$rownum,floatval($row['totalweight']));
				$objActSheet->setCellValueByColumnAndRow(10,$rownum,floatval($row['otherdiscount']));
				$objActSheet->setCellValueByColumnAndRow(11,$rownum,floatval($row['otherfee']));
				$objActSheet->setCellValueByColumnAndRow(12,$rownum,1);

			}
			

			$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
			//在浏览器导出
			header("Content-Type: application/force-download");
			header('Content-Type: application/vnd.ms-excel');
			header("Content-Disposition: attachment;filename=\"".iconv("UTF-8","GB2312","tracking-number").date('ymdHis').".xls\"");
			header('Cache-Control: max-age=0');
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
			header("Pragma: no-cache");
			$objWriter->save("php://output");
			exit;
		}



















		}else if($ku==3){
		
			$sql = "";
			$sql = "`siteid`='".$this->get_siteid()."' and storageid='$this->hid' and position='paifa'  and handle_status='$sta' $_tsql";
			$page = max(intval($_GET['page']), 1);
			$datas = $this->kbdb->listinfo($sql, 'splittime desc,userid asc', $page);
			
		}else if($ku==4){
			$sql = "siteid='".$this->get_siteid()."' and storageid='$this->hid' and position='paifa'  and handle_status='$sta' $_tsql" ;
			$page = max(intval($_GET['page']), 1);
			$datas = $this->wbdb->listinfo($sql, 'splittime desc,userid asc', $page);
		}

		include $this->admin_tpl('house_paifa');
	}
	
	/**
	 * 添加仓库配置
	 */
	public function add() {
		$show_validator = 1;
		if(isset($_POST['dosubmit'])) {
			$_POST['house'] = $this->check($_POST['house']);

			if($this->db->insert($_POST['house'])){
				
				$lastinsertid = $this->db->insert_id();

				//添加仓库成功后，加入菜单选项
				//1577 添加
				$menu_db = pc_base::load_model('menu_model');

				$parentid = $menu_db->insert(array('name'=>'house__'.$lastinsertid, 'parentid'=>1577, 'm'=>'house', 'c'=>'admin_house', 'a'=>'init', 'data'=>'hid='.$lastinsertid, 'listorder'=>0, 'display'=>'1'), true);
				
				$menu_db->insert(array('name'=>'house_jihuo', 'parentid'=>$parentid, 'm'=>'house', 'c'=>'admin_house', 'a'=>'house_jihuo', 'data'=>'hid='.$lastinsertid, 'listorder'=>1, 'display'=>'1'));
				$menu_db->insert(array('name'=>'house_zhongzhuan', 'parentid'=>$parentid, 'm'=>'house', 'c'=>'admin_house', 'a'=>'house_zhongzhuan', 'data'=>'hid='.$lastinsertid, 'listorder'=>2, 'display'=>'1'));
				$menu_db->insert(array('name'=>'house_paifa', 'parentid'=>$parentid, 'm'=>'house', 'c'=>'admin_house', 'a'=>'house_paifa', 'data'=>'hid='.$lastinsertid, 'listorder'=>3, 'display'=>'1'));
				
				$this->editlanguage($_POST['house']['title'],'house__'.$lastinsertid);//更新语言


				showmessage(L('housement_successful_added'), HTTP_REFERER, '', 'add');
			}
		} else {
			//获取站点模板信息
			pc_base::load_app_func('global', 'admin');
			
			include $this->admin_tpl('house_add');
		}
	}
	
	/**
	 * 修改仓库配置
	 */
	public function edit() {
		$show_validator = 1;
		$idd = intval($_GET['aid']);
		if(!$idd) showmessage(L('illegal_operation'));
		if(isset($_POST['dosubmit'])) {
			$_POST['house'] = $this->check($_POST['house'], 'edit');
			if($this->db->update($_POST['house'], array('aid' => $idd))){

				$this->editlanguage($_POST['house']['title'],'house__'.$idd);//更新语言

				showmessage(L('housed_a'), HTTP_REFERER, '', 'edit');
			}
		} else {
			$where = array('aid' => $_GET['aid']);
			$an_info = $this->db->get_one($where);
			include $this->admin_tpl('house_edit');
		}
	}



	
	
	/**
	 * ajax检测仓库配置标题是否重复
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
	 * 批量修改仓库配置状态 使其成为审核、未审核状态
	 */
	public function public_approval($aid = 0) {
		if((!isset($_POST['aid']) || empty($_POST['aid'])) && !$aid) {
			showmessage(L('illegal_operation'));
		} else {
			if(is_array($_POST['aid']) && !$aid) {
				array_map(array($this, 'public_approval'), $_POST['aid']);
				showmessage(L('house_passed'), HTTP_REFERER);
			} elseif($aid) {
				$aid = intval($aid);
				
				$this->db->update(array('default' => $_GET['default']), array('aid' => $aid));
				return true;
			}
		}
	}
	
	/**
	 * 批量删除仓库配置
	 */
	public function delete($aid = 0) {
		if((!isset($_POST['aid']) || empty($_POST['aid'])) && !$aid) {
			showmessage(L('illegal_operation'));
		} else {
			if(is_array($_POST['aid']) && !$aid) {
				array_map(array($this, 'delete'), $_POST['aid']);
				showmessage(L('house_deleted'), HTTP_REFERER);
			} elseif($aid) {
				$aid = intval($aid);
				$this->db->delete(array('aid' => $aid));

				//删除菜单列表中仓库
				$menu_db = pc_base::load_model('menu_model');
				$r = $menu_db->get_one("name='house__$aid'");
				if($r){
					$menu_db->delete(array('parentid' => $r['id']));
					$menu_db->delete(array('id' => $r['id']));
				}

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
		if($data['title']=='') showmessage(L('title_cannot_empty'));
		$r = $this->db->get_one(array('title' => $data['title']));
		
		if ($a=='add' ) {
			if (is_array($r) && !empty($r)) {
				showmessage(L('house_exist'), HTTP_REFERER);
			}
			$data['siteid'] = $this->get_siteid();
			$data['addtime'] = SYS_TIME;
			$data['username'] = $this->username;
			
		} else {
			if ($r['aid'] && ($r['aid']!=$_GET['aid'])) {
				showmessage(L('house_exist'), HTTP_REFERER);
			}
		}
		return $data;
	}


	//修改语言
	private function editlanguage($language,$key){
		
		//修改语言文件
		$file = PC_PATH.'languages'.DIRECTORY_SEPARATOR.pc_base::load_config('system', 'lang').DIRECTORY_SEPARATOR.'system_menu.lang.php';
	
		require $file;
		if(!isset($LANG[$key])) {
				$content = file_get_contents($file);
				$content = substr($content,0,-2);
				$data = $content."\$LANG['$key'] = '$language';\r\n?>";
				file_put_contents($file,$data);
		} elseif(isset($LANG[$key]) && $LANG[$key]!=$language) {
				$content = file_get_contents($file);
				$content = str_replace($LANG[$key],$language,$content);
				file_put_contents($file,$content);
		}

	}

	/**获取单个订单状态**/
	private function getonestatus($id){
		$datas = getcache('get__enum_data_48', 'commons');
		$title="";
			foreach($datas as $v){
				if($id==$v['value']){
					$title=$v['title'];
				}
			}

		return $title;
	}

	/**获取包裹状态**/
	private function getpackstatus(){
		$datas = getcache('get__enum_data_48', 'commons');
		return $datas;
	}


	/**获取运单订单状态**/
	private function getwaybilltatus($id=''){
		$datas = getcache('get__enum_data_9', 'commons');

		if($id>0){
			
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
	private function getallship($id=0){
		$datas = getcache('get__enum_data_1', 'commons');

		if($id>0){
			
			$title="";
			foreach($datas as $v){
				if($id==$v['aid']){
					$title=$v['title'];
				}
			}
			return $title;
		}else{
			
			return $datas;
		}
	}

	

	/**根据运单号获取运单物品详细**/
	private function getwaybill_goods($waybillid, $returncode=''){
		$cdb = pc_base::load_model('waybill_goods_model');
		$sql = "  waybillid='$waybillid' AND returncode='$returncode'";
		$datas = $cdb->select($sql,'*',10000,'number asc');
		
		return $datas;
	}

	/**获取收货地址**/
	private function getaddresslist(){
		$cdb = pc_base::load_model('address_model');
		$sql = '';
		$datas = $cdb->select($sql,'*',10000,'addtime desc');
		return $datas;
	}

	/**获取收货地址**/
	private function get_oneaddresslist($id){
		$cdb = pc_base::load_model('address_model');
		$sql = '';
		$datas = $cdb->get_one(array('aid'=>$id),'*');
		return $datas;
	}

	/** 获取发货地 (国家和地区) **/
	private function getsendlists(){
		
		$sql = '';
		$results = getcache('get__storage__lists', 'commons');

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

	/** 获取收货地 (国家和地区) **/
	public function gettakelists(){

		$areaid = isset($_GET['areaid']) ? intval($_GET['areaid']) : 0;
		$datas = array();
		$sendlists = $this->getsendlists();
		
		foreach($sendlists as $k=>$v){
			if($v['linkageid']!=$areaid)
				$datas[]=$v;
		}
		print_r(json_encode($datas));
		exit;
	}

	/**获取国家地区**/
	private function getline(){
		$datas = getcache('get__linkage__lists', 'commons');

		return $datas;
	}

	/**获取转运方式**/
	private function getturnway(){
		$datas = getcache('get__turnway__lists', 'commons');

		return $datas;
	}

	public function get_warehouse__lists(){
		//列出所会员仓库
		$this->warehouse__lists = getcache('get__storage__lists', 'commons');
		return $this->warehouse__lists;
	}

	public function getuserinfo($userid){
		$udb = pc_base::load_model('member_model');
		$udb->set_model();
		return $udb->get_one("userid='$userid' ");
	}

	//获取当前仓库
	public function getstoragename($storageid){
		$datas = getcache('get__storage__lists', 'commons');
		$title="";
		foreach($datas as $v){
			if($v['aid']==$storageid)
				$title=$v['title'];
		}

		return $title;
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

	//显示处理操作结果
	public function house_showhandle($sysbillid){
		$sysbillid=trim($sysbillid);
		$cdb = pc_base::load_model('waybill_history_model');
		$sql = "waybillid='$sysbillid'";
		$datas = $cdb->select($sql,'*',10000,' addtime asc');
		return $datas;
	}


	public function get_kaban_huodanexists($kabanno,$flag=0){
		$kabanno=trim($kabanno);
		$hdb = pc_base::load_model('waybill_huodan_model');
		$r = $hdb->get_one("kabanno like '%$kabanno%' and unionhuodanno!=''");
		if($r){
			if($flag==0)
				return trim($r['unionhuodanno']);
			else
				return "<font color=red>已合成货单</font>";
		}else{
			if($flag==0)
				return "";
			else
				return "<font color=green>未合成货单</font>";
		}
	}

	private function sendemailwaybill($sysbillid,$statusinfo,$send_email_nofity=1){
			
			$sysres = $this->wbdb->get_one(array('sysbillid'=>$sysbillid));
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
			$message = $member_setting['expressnoemail'];
			$message = str_replace(array('{click}','{url}','{no}','{status}'), array('<a href="'.$url.'">'.L('please_click').'</a>',$url,$sysres['waybillid'],$statusinfo), $message);
			sendmail($userinfo['email'], '你的运单号'.$sysres['waybillid'].$statusinfo, $message);
			}
	}

}
?>