<?php

defined('IN_PHPCMS') or exit('No permission resources.'); 

/////////////////////////////////////////////handle xls file///////////////////////////////////////////////////////////////////
pc_base::load_sys_class('format', '', 0);
pc_base::load_sys_class('form', '', 0);
pc_base::load_sys_class('reader', '', 0);


$gdb = pc_base::load_model('waybill_goods_model');
$wdb = pc_base::load_model('waybill_model');
$whdb = pc_base::load_model('waybill_history_model');
$this_att_db = pc_base::load_model('attachment_model');


	
	$sql="CREATE TABLE IF NOT EXISTS `t_member_number` (
				  `idd` mediumint(10) unsigned NOT NULL AUTO_INCREMENT,
				  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
				  PRIMARY KEY (`idd`)
				) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;";
			$wdb->query($sql);


		
		/*for($y=2014;$y<=2030;$y++){
			$beginnumber=1;
			if($y==2014){$beginnumber=1000;}

			$sql="CREATE TABLE IF NOT EXISTS `t_ordernumber_".$y."` (
				  `idd` mediumint(10) unsigned NOT NULL AUTO_INCREMENT,
				  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
				  PRIMARY KEY (`idd`)
				) ENGINE=MyISAM AUTO_INCREMENT=".$beginnumber." DEFAULT CHARSET=utf8;";
			$wdb->query($sql);
			
		}*/

/*
	$cwbdatas = $wdb->select("addtime>='".strtotime('2014-12-08 00:00:01')."' and addtime<='".strtotime('2014-12-10 23:23:01')."' and storageid!=13","aid,sysbillid,waybillid",100000);

	$importpath = PHPCMS_PATH."uploadfile/CWB141210121100.xls";
	$data = new Spreadsheet_Excel_Reader();  
	$data->setOutputEncoding('utf-8'); 

	$data->read($importpath); 
	$oknum=0;
	$errnum=0;

	$arr=array();

	echo '<p>ALL count:'.count($cwbdatas).'</p>';

	foreach($cwbdatas as $k=>$row){
		$flag=0;
		for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++)     
		{
			$cwbid=trim($data->sheets[0]['cells'][$i][1]);
		
			if(!empty($cwbid) && $cwbid==trim($row['waybillid'])){
				$flag=1;
			}
		}
		echo '<p>'.$k.':'.$row['waybillid'].' ------'.$flag.'</p>';
		if($flag==1){
			$arr[]=$row['waybillid'];	
		}
	}

	

	///////////////
	echo 're number<br>';
	foreach($arr as $v){
		echo $v."<br>";
	}
*/


?>