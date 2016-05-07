<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin', 'admin', 0);
pc_base::load_sys_class('format', '', 0);
pc_base::load_sys_class('form', '', 0);

class report extends admin {
	private $db;
	public function __construct() {
		parent::__construct();
		$this->db = pc_base::load_model('waybill_model');
	}
	
	//消费统计
	public function payrecord() {
		
		$sql="";
		$act = isset($_GET['act']) ? $_GET['act'] : '';
		$page = max(intval($_GET['page']), 1);
		
		$sql=" status in(7,14) ";
		
		$start_time = isset($_GET['start_time']) ? $_GET['start_time'] : '';
		$end_time = isset($_GET['end_time']) ? $_GET['end_time'] : '';
		
		if($start_time!='' && $end_time!=''){
			$sql.=" and paidtime>='".strtotime($start_time.' 00:00:01')."' and paidtime<='".strtotime($end_time.' 23:59:59')."' ";
		}
		if($act=='download'){
			require_once PHPCMS_PATH.'api/PHPExcel.php';       
			require_once PHPCMS_PATH.'api/PHPExcel/Writer/Excel5.php';  
			
			
			$objPHPExcel = new PHPExcel();
			$objPHPExcel->setActiveSheetIndex(0);    
			$objActSheet = $objPHPExcel->getActiveSheet(); 
			//设置当前活动sheet的名称
			$objActSheet->setTitle("消费统计");
			
			//标头 
			$excelHeader=array('运单号','下单时间','客户名','真实姓名','收件人','重量(g)','扣费金额');
			foreach($excelHeader as $k=>$v){
				$objActSheet->setCellValueByColumnAndRow($k,1,$v);
			}
			$m_db = pc_base::load_model('member_model');
			$m_db->set_model(10);
			
			

			//数据
			$wsql="select * from t_waybill where ".$sql." order by paidtime desc ";
			$result = $this->db->query($wsql);
			$rownum=1;
			foreach($this->db->fetch_array($result) as $num=>$row) {
				$rownum+=1;
				
				$usrow = $m_db->get_one(array('userid'=>$row['userid']));
				$addr=$this->get_takeinfo($row['takeaddressid'],$row['userid']);
				
				$objActSheet->setCellValueByColumnAndRow(0,$rownum,$row['waybillid']);
				$objActSheet->setCellValueByColumnAndRow(1,$rownum,date('Y-m-d',$row['paidtime']));
				$objActSheet->setCellValueByColumnAndRow(2,$rownum,$row['username']);
				$objActSheet->setCellValueByColumnAndRow(3,$rownum,$usrow['lastname'].$usrow['firstname']);
				$objActSheet->setCellValueByColumnAndRow(4,$rownum,$addr['truename']);
				$objActSheet->setCellValueByColumnAndRow(5,$rownum,$row['totalweight']);
				$objActSheet->setCellValueByColumnAndRow(6,$rownum,$row['payedfee']);
			}
			//$this->db->free_result($result);

			$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
			//在浏览器导出
			header("Content-Type: application/force-download");
			header('Content-Type: application/vnd.ms-excel');
			header("Content-Disposition: attachment;filename=\"".iconv("UTF-8","GB2312","消费统计").date('ymdHis').".xls\"");
			header('Cache-Control: max-age=0');
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
			header("Pragma: no-cache");
			$objWriter->save("php://output");
			exit;

			
		}else{
			$datas = $this->db->listinfo($sql, 'addtime DESC', $page);
		}
		include $this->admin_tpl('report_payrecord');
	}

	//运单统计
	public function waybillstat() {
	
		$sql="";

		$page = max(intval($_GET['page']), 1);
		
		$sql=" status in(7,11,12,14,16) ";
		
		$start_time = isset($_GET['start_time']) ? $_GET['start_time'] : '';
		$end_time = isset($_GET['end_time']) ? $_GET['end_time'] : '';
		
		if($start_time!='' && $end_time!=''){
			$sql.=" and addtime>='".strtotime($start_time.' 00:00:01')."' and addtime<='".strtotime($end_time.' 23:59:59')."' ";
		}
		
		$act = isset($_GET['act']) ? $_GET['act'] : '';

		if($act=='download'){
			require_once PHPCMS_PATH.'api/PHPExcel.php';       
			require_once PHPCMS_PATH.'api/PHPExcel/Writer/Excel5.php';  
			
			
			$objPHPExcel = new PHPExcel();
			$objPHPExcel->setActiveSheetIndex(0);    
			$objActSheet = $objPHPExcel->getActiveSheet(); 
			//设置当前活动sheet的名称
			$objActSheet->setTitle("运单统计");
			//运单号 客户名 重量 操作费 总付款 物品名称 收货人 收货地址 联系方式 添加时间 
			//标头 
			$excelHeader=array('运单号','客户名','重量','操作费','总付款','物品名称','收货人','收货地址','联系方式','添加时间');
			foreach($excelHeader as $k=>$v){
				$objActSheet->setCellValueByColumnAndRow($k,1,$v);
			}
			$m_db = pc_base::load_model('member_model');
			$m_db->set_model(10);
			
			

			//数据
			$wsql="select * from t_waybill where ".$sql." order by addtime desc ";
			$result = $this->db->query($wsql);
			$rownum=1;
			foreach($this->db->fetch_array($result) as $num=>$row) {
				$rownum+=1;
				
				$usrow = $m_db->get_one(array('userid'=>$row['userid']));
				$addr=$this->get_takeinfo($row['takeaddressid'],$row['userid']);

				$allexp=$this->getwaybill_goods($row['waybillid']);
				$exp="";
				foreach($allexp as $v){
					$exp.=$v['expressno']."\n";
				}
				
				$objActSheet->setCellValueByColumnAndRow(0,$rownum,$row['waybillid']);
				$objActSheet->setCellValueByColumnAndRow(1,$rownum,$row['username']);
				$objActSheet->setCellValueByColumnAndRow(2,$rownum,$row['totalweight']);
				$objActSheet->setCellValueByColumnAndRow(3,$rownum,$row['taxfee']);
				$objActSheet->setCellValueByColumnAndRow(4,$rownum,$row['payedfee']);				
				$objActSheet->setCellValueByColumnAndRow(5,$rownum,$row['goodsname']);
				$objActSheet->setCellValueByColumnAndRow(6,$rownum,$addr['truename']);
				$objActSheet->setCellValueByColumnAndRow(7,$rownum,$addr['address']);
				$objActSheet->setCellValueByColumnAndRow(8,$rownum,$addr['mobile']);
				$objActSheet->setCellValueByColumnAndRow(9,$rownum,date('Y-m-d',$row['addtime']));
			}
			//$this->db->free_result($result);

			$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
			//在浏览器导出
			header("Content-Type: application/force-download");
			header('Content-Type: application/vnd.ms-excel');
			header("Content-Disposition: attachment;filename=\"".iconv("UTF-8","GB2312","运单统计").date('ymdHis').".xls\"");
			header('Cache-Control: max-age=0');
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
			header("Pragma: no-cache");
			$objWriter->save("php://output");
			exit;

			
		}else{
			$datas = $this->db->listinfo($sql, 'addtime DESC', $page);
		}
		include $this->admin_tpl('report_waybillstat');
	}

	//实际运费
	public function fact_pay() {
		
		$sql="";
		$act = isset($_GET['act']) ? $_GET['act'] : '';
		$page = max(intval($_GET['page']), 1);
		
		$sql=" status in(7,11,12,14,16) ";
		
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
			$objActSheet->setTitle("实际运费");
			
			//标头 
			$excelHeader=array('运单号','下单时间','客户名','真实姓名','收货人','重量(LBS)','实际运费');
			foreach($excelHeader as $k=>$v){
				$objActSheet->setCellValueByColumnAndRow($k,1,$v);
			}
			$m_db = pc_base::load_model('member_model');
			$m_db->set_model(10);
			
			

			//数据
			$wsql="select * from t_waybill where ".$sql." order by addtime desc ";
			$result = $this->db->query($wsql);
			$rownum=1;
			foreach($this->db->fetch_array($result) as $num=>$row) {
				$rownum+=1;
				
				$usrow = $m_db->get_one(array('userid'=>$row['userid']));
				$addr=$this->get_takeinfo($row['takeaddressid'],$row['userid']);
				
				$objActSheet->setCellValueByColumnAndRow(0,$rownum,$row['waybillid']);
				$objActSheet->setCellValueByColumnAndRow(1,$rownum,date('Y-m-d',$row['addtime']));
				$objActSheet->setCellValueByColumnAndRow(2,$rownum,$row['username']);
				$objActSheet->setCellValueByColumnAndRow(3,$rownum,$usrow['lastname'].$usrow['firstname']);
				$objActSheet->setCellValueByColumnAndRow(4,$rownum,$addr['truename']);
				$objActSheet->setCellValueByColumnAndRow(5,$rownum,$row['totalweight']);
				$objActSheet->setCellValueByColumnAndRow(6,$rownum,$row['factpay']);
			}
			//$this->db->free_result($result);

			$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
			//在浏览器导出
			header("Content-Type: application/force-download");
			header('Content-Type: application/vnd.ms-excel');
			header("Content-Disposition: attachment;filename=\"".iconv("UTF-8","GB2312","实际运费统计").date('ymdHis').".xls\"");
			header('Cache-Control: max-age=0');
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
			header("Pragma: no-cache");
			$objWriter->save("php://output");
			exit;

			
		}else{
			$datas = $this->db->listinfo($sql, 'addtime DESC', $page);
		}
		include $this->admin_tpl('fact_pay');
	}


	//订单统计
	public function orderstat() {
	
		include $this->admin_tpl('report_orderstat');
	}

	//获取收货信息
	public function get_takeinfo($id,$uid){
		$addrdb = pc_base::load_model('address_model');
		return $addrdb->get_one(array('aid'=>$id,'userid'=>$uid));
	}

	/**根据运单号获取运单物品详细**/
	private function getwaybill_goods($waybillid){
		$cdb = pc_base::load_model('waybill_goods_model');
		$sql = "  waybillid='$waybillid'  ";
		$datas = $cdb->select($sql,'*',10000,'number asc');
		return $datas;
	}

	
}