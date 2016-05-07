<?php

$a = isset($_GET['a']) ? trim($_GET['a']) : '';
$uid = isset($_GET['uid']) ? intval($_GET['uid']) : 0;

switch($a){
	case 'get_line__fee':
		
	$country = isset($_POST['country']) ? trim($_POST['country']) : 0;
	$totalweight = isset($_POST['weight']) ? floatval($_POST['weight']) : 0;

		$totalship = 0;
			$stotalship = "";
			$stotalshipremark = "";

			$totalship123 = 0;
			$stotalship123 = "";
			$stotalshipremark123 = "";
			
			$feedatas = getcache('get__enum_data_48_'.$country, 'commons');
			
			$feedatas123 = getcache('get__enum_data_123_'.$country, 'commons');

			$totalweight = floatval(str_replace("g","",$totalweight));
			foreach($feedatas as $fee){
				$fees = floatval(str_replace("g","",trim($fee['title'])));
				
				
				if($totalweight<=$fees){
					$stotalship = trim($fee['value']);
					$stotalshipremark = trim($fee['remark']);
					break;
				}
			}
			
			$shiparr=explode('|',$stotalship);
			$remarkarr=explode('|',$stotalshipremark);
			
			$newarr=array();	
			foreach($remarkarr as $k=>$item){
				$item = str_replace("航空","air",$item);
				$item = str_replace("海运","sea",$item);
				$newarr[48][$item]=$shiparr[$k];
			}


			///////////////////////////////////////////////////////////////////////////////////////


			foreach($feedatas123 as $fee123){
				$fees123 = floatval(str_replace("g","",trim($fee123['title'])));
				
				
				if($totalweight<=$fees123){
					$stotalship123 = trim($fee123['value']);
					$stotalshipremark123 = trim($fee123['remark']);
					break;
				}
			}
			
			$shiparr123=explode('|',$stotalship123);
			$remarkarr123=explode('|',$stotalshipremark123);
			
			//$newarr123=array();	
			foreach($remarkarr123 as $k123=>$item123){
				$item123 = str_replace("航空","air",$item123);
				$item123 = str_replace("海运","sea",$item123);
				$newarr[123][$item123]=$shiparr123[$k123];
			}

			if(count($newarr)>0){
				print_r(json_encode(array('data'=>$newarr)));
			}else
			{
				print_r(json_encode(array('error'=>1)));
			}
		exit;

	break;
	case 'computtaxfee':
		$shippingid = isset($_GET['shippingid']) ? intval($_GET['shippingid']) : 0;
		$totalweight = isset($_GET['totalweight']) ? floatval($_GET['totalweight']) : 0;
		
		$taxfee = get_operaction_fee($totalweight);
		echo $taxfee;
		exit;
	break;
	case 'get_securefee':
		$shippingid = isset($_GET['shippingid']) ? intval($_GET['shippingid']) : 0;
		$securevalue = isset($_GET['securevalue']) ? floatval($_GET['securevalue']) : 0;
		
		$scfee = get_secure_fee($securevalue,get_common_shipline($shippingid));
		echo $scfee;
		exit;
		break;
	case 'postmessage':

			$wdb = pc_base::load_model('waybill_model');

			$aid = isset($_GET['aid']) ? intval($_GET['aid']) : 0;
			$uname = isset($_GET['uname']) ? trim($_GET['uname']) :"";

			$messagedata = isset($_POST['messagedata']) ? trim($_POST['messagedata']) : "";

			$row = $wdb->get_one(array('aid'=>$aid));
			$oldcontent ="";
			if($row){
				$oldcontent =$row['messagedata'];
			}
			
			$newcontent = "<p align=left>".$uname."[".date("y-m-d H:i",SYS_TIME)."] ".$messagedata."</p>".$oldcontent;
			$wdb->update(array('messagedata'=>$newcontent),array('aid'=>$aid));

			echo 1;
			exit;
			
	break;

	case 'computfee':

		$totalweight = isset($_GET['totalweight']) ? trim($_GET['totalweight']) : 0;
		$shippingid = isset($_GET['shippingid']) ? trim($_GET['shippingid']) : 0;

		$takename = isset($_GET['takename']) ? trim($_GET['takename']) : '';

		$payedfee = get_ship_fee($totalweight,get_common_shipline($shippingid),$takename);

		echo $payedfee;
		exit;
	break;
	case 'flightno_exists':
		$fdb = pc_base::load_model('waybill_flight_model');

		$flightno = isset($_GET['flightno']) ? trim($_GET['flightno']) : '';
		
		if($fdb->get_one(array('flightno'=>$flightno))){
			echo 1;
		}else{
			echo 0;
		}
		exit;

		break;
	case 'batchno_exists':
		$pdb = pc_base::load_model('waybill_batch_model');

		$batchno = isset($_GET['batchno']) ? trim($_GET['batchno']) :  '';
		
		if($pdb->get_one(array('batchno'=>$batchno))){
			echo 1;
		}else{
			echo 0;	
		}

		exit;
		break;

	case 'kabanno_exists':
		$kbdb = pc_base::load_model('waybill_kaban_model');

		$kabanno = isset($_GET['kabanno']) ? trim($_GET['kabanno']) :  '';

		
		if($kbdb->get_one(array('kabanno'=>$kabanno))){
			echo 1;
		}else{
			echo 0;
		}
		exit;
		break;
	case 'getbagno':

		$db = pc_base::load_model('order_databag_model');
		
		$row=$db->get_one("siteid=1 AND isused=0","bagno");
		if($_GET['flag']==2){
		print_r(json_encode(array('bagno'=>date('ymdHis',SYS_TIME))));
		}else{
		if($row){
			$db->delete(array('bagno'=>trim($row['bagno'])));
			print_r(json_encode($row));
			exit;
		}else{
			exit(-1);
		}
		}
		break;
	case 'getuid':
			$key = isset($_GET['key']) ? trim($_GET['key']) : 'userid';
			$flag = isset($_GET['flag']) ? trim($_GET['flag']) : '1';
	
			$udb = pc_base::load_model('member_model');
			$sql="clientname='$key'";
			switch($flag){
				case 2:
					$sql="clientcode='$key'";
					break;
				case 3:
					$sql="userid='$key'";
					break;
				case 4:
					$sql="username='$key'";
					break;
			}
			
			$row=$udb->get_one($sql);
			if($row)
				print_r(json_encode($row));
			else
				echo '-1';
			exit;
		break;
	case 'getshippingmethod':

		$idd = isset($_GET['idd']) ? intval($_GET['idd']) : 0;
		$sendid = isset($_GET['sendid']) ? intval($_GET['sendid']) : 0;
		$datas = array();
		$sendlists = getcache('get__turnway__lists', 'commons');
		
		foreach($sendlists as $k=>$v){
			if(($v['destination']==$idd ) || ( $v['origin']==$idd))
				$datas[]=$v;
		}
		print_r(json_encode($datas));
		exit;
		

		break;
	case 'gettakelists':
		$areaid = isset($_GET['areaid']) ? intval($_GET['areaid']) : 0;
		$datas = array();

		$results = getcache('get__storage__lists', 'commons');

		$sendlists = array();
		$lines = getcache('get__linkage__lists', 'commons');
		foreach($lines as $k=>$v){
			foreach($results as $kk=>$row){
				if($v['linkageid']==$row['area']){
					$v['storagecode']=$row['storagecode'];
					$sendlists[]=$v;
				}
			}
		}
		
		foreach($sendlists as $k=>$v){
			//if($v['linkageid']!=$areaid)
				$datas[]=$v;
		}
		print_r(json_encode($datas));
		exit;
		break;
	case 'expno_exists':

		$data = isset($_POST['data']) ? trim($_POST['data']) : '';
		$result="";
		if($data){
			$res = explode("_",$data);
			$pdb = pc_base::load_model('package_model');
			foreach($res as $v){
				$packageno = trim($v);
				$row = $pdb->get_one("expressno='$packageno' AND returncode='' AND userid='$uid'",'expressno,status');
				if($row){
					if($row['status']!=1)
					$result.="您的包裹仓库里面 邮单号 $packageno 已存在 或 已处理!\n ";
				}
			}
		}
		echo $result;
		exit;


		break;

}




?>