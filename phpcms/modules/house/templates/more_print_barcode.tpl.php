<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <meta name="robots" content="noarchive">
    <meta name="robots" content="nofollow">
    <title>批量打印快递单</title>
 <style>
body{/* font-family:宋体;font-size:12pt; */}
.tb1{border:1.0pt solid windowtext;}
.tb1tdbottom{border-bottom:1.0pt solid windowtext;}
.tb1tdright{border-right:1.0pt solid windowtext;}
.bw{background:#ffffff;}
.f400{font-weight:400;color:black;font-size:18.0pt;}
.f8{font-weight:600;font-family:宋体;font-size:12pt;color:black;}
.f8a{font-weight:600;font-family:宋体;font-size:12pt;}
table th{font-weight:600;font-size:12pt;text-align:left;}
table td{padding:3px;}

.bill_print {margin:0 auto;width:500px;height:480px;padding:10px;position: relative;}
.bill_print_time {float: right;margin-top: 0;}
.bill_print_number {position:absolute;bottom:0px;left:0px;width:360px;}

.bill_print_yin {position:absolute;bottom:90px;right:220px;}
.bill_print_weight {position:absolute;bottom:120px;right:10px;}
.bill_print_flag {font-weight: bold;font-size: 46px;position:absolute;bottom:0px;right:0px;}
.bill_print_aid{font-weight: bold;font-size: 30px;position:absolute;bottom:50px;right:10px;}

.bill_print_line {border-bottom: 1px dashed #ccc;padding: 5px 0;word-break:break-all;font-family: "Microsoft YaHei", Verdana, Arial, Tahoma, "宋体";font-size: 14px;}
.bill_print_text {display:inline;padding-right: 10px;font-family: "Microsoft YaHei", Verdana, Arial, Tahoma, "宋体";font-size: 14px;}
.bill_print_remark {border-bottom: 1px dashed #ccc;padding: 3px 0;word-break:break-all;font-family: "Microsoft YaHei", Verdana, Arial, Tahoma, "宋体";font-size: 16px;  font-weight: bolder;}
.bill_print_name {font-family: "Microsoft YaHei", Verdana, Arial, Tahoma, "宋体";font-size:16px;  font-weight: bolder;}

.bill_print_aid1{font-weight: bold;font-size: 46px;position:absolute;top:150px;left:160px;}
.bill_print_flag1{font-weight: bold;font-size: 46px;position:absolute;top:230px;left:160px;}
.bill_print_text1{font-weight: bold;font-size: 46px;position:absolute;top:310px;left:160px;}
.bill_print_srvtype {text-align:right;}

</style>
<body>


<?php
$cdb = pc_base::load_model('enum_model');
$hd_db = pc_base::load_model('waybill_huodan_model');
$udb = pc_base::load_model('address_model');


?>

</head>
<body>

    <form method="post" action="" id="form1">

<?php

$i=0;
foreach($datas as $info){

	$addr=explode("|",$info['sendaddressname']);//发件
				$send_truename=$addr[0];
				$send_country=$addr[2];
				$send_province=$addr[3];
				$send_city=$addr[4];
				$send_address=$addr[5];
				$send_postcode=$addr[6];
				$send_mobile=$addr[1];
				$sendaddress=str_replace("|","&nbsp;&nbsp;",$info['sendaddressname']);
				//$send_company=$addr['company'];

				$addr=explode("|",$info['takeaddressname']);///收件
				$take_truename=$addr[0];
				$take_country=$addr[2];
				$take_province=$addr[3];
				$take_city=$addr[4];
				$take_address=$addr[5];
				$take_postcode=$addr[6];
				$take_mobile=$addr[1];
				$takeaddress=str_replace("|","&nbsp;&nbsp;",$info['takeaddressname']);
				//$take_company=$addr['company'];


$truename= $take_truename;

echo '




<div class="bill_print">
	<div>
		<div class="bill_print_flag">'.$info['takeflag'].'</div>
		<div class="bill_print_aid">#'.$info['aid'].'</div>
	</div>



	<div class="bill_print_line">
		<div class="bill_print_name">&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;姓名:'.$addr[0].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		'.str_replace('2','分箱',str_replace('1','合箱',str_replace('0','单票直发',$info['srvtype']))).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		'.date("Y-m-d H:i:s").'
		</div>
	</div>
	<div class="bill_print_line">	
		<div class="bill_print_text">'.$take_country.'&nbsp;'.$take_province.'&nbsp;'.$take_city.'&nbsp;'.$take_address.'&nbsp;'.$take_postcode.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$take_mobile.'
		<br>
		</div>
	</div>
	
		<div class="bill_print_line">增值服务:
';			
						$temp_price=0;
					$allsrv=string2array($info['addvalues']);
						foreach($allsrv as  $srv){
							echo $srv['title'];
							if ($srv[servicetypeid]==13){
								$srv['price'] = $srv['price']." ".$info['otherfee'];
							}
							echo '<font color="#FF953F">'.$srv['price'].'</font>/';
						}
						echo '
	</div>
	<div class="bill_print_line">分合箱:'.$info['otherremark'].'</div>
	<div class="bill_print_remark">备注:'.$info['remark'].'</div>	
	<div class="bill_print_line">邮单号:'.$info['expressno'].'</div>
	<div class="bill_print_line">物品名:
';	 
			$goodsdatas=string2array($info['goodsdatas']);
			foreach($goodsdatas as $key=>$goods)
			{
		echo'
			<div style="display:inline;padding-left:5px;"><span style="font-weight:bold;">(</span>'.$goods['goodsname'].'='.$goods['amount'].'<span style="font-weight:bold;">)</span></div>
			';
			}
echo '
	
	
	</div>
	


	<div class="bill_print_number"><img style="width: 330px;" src="?m=barcode&c=admin_barcode&a=init&hid='.$info['storageid'].'?&data='.$info['waybillid'].'"></div>
	<div class="bill_print_yin">印:</div>
	<div class="bill_print_weight">重量:'.$info['totalweight'].'g</div>
</div>

<div style="height:25px;"></div>
<div class="bill_print">
	<div>
		<div class="bill_print_aid1">#'.$info['aid'].'</div>
		<div class="bill_print_flag1">'.$info['takeflag'].'</div>
		<div class="bill_print_text1">姓名:'.$addr[0].'</div>
	</div>
</div>

   ';

	$i++;
}										
	?>
    </form>
</body>
</html>
