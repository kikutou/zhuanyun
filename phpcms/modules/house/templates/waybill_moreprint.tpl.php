<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="robots" content="noarchive">
    <meta name="robots" content="nofollow">
    <title>批量打印快递单</title>
	<script language="javascript" type="text/javascript" src="/resource/js/jquery.min.js"></script>
	<?php
	if(intval($_GET['pflag'])!=3){
	?>
 
<?php
}else{		
	?>

<?php
	}	
?>
</head>
<body style="margin: 0; padding: 0;">

<?php

$pflag = isset($_GET['pflag']) ? ($_GET['pflag']) : 0;
$current_url =  'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."&checkhash=".$_SESSION['checkhash'];


?>




<center <?php if($pflag==0){echo 'style="display:block;"';}else{echo 'style="display:none;"';}?>>

<form id="from001" action="<?php echo $current_url;?>" method="post" target=_blank>
<?php

foreach($_POST['aid'] as $v){
	echo '<input type="hidden" name="aid[]" value="'.$v.'"/>';
}

?>

</form>


<p style="align:center;margin:0 0;" ><h1>
<a href="javascript:void(0);" onclick="document.getElementById('from001').action='<?php echo $current_url."&pflag=1";?>';document.getElementById('from001').submit();">Print Template EMS</a>  | <a href="javascript:void(0);" onclick="document.getElementById('from001').action='<?php echo $current_url."&pflag=3";?>';document.getElementById('from001').submit();">Print Template SAL</a></h1>

</p>
</center>


<div align="center" style="margin: 0 auto;">

   
   <?php
   if($pflag==1){
	?>
	
	<?php
   }
   
   ?>

<?php

$udb = pc_base::load_model('address_model');

	$sinfos= siteinfo(1);
$ii=0;
foreach($datas as $info){
$ii++;
	
				$takeaddr=explode('|',$info['takeaddressname']);
				$sendaddr=explode('|',$info['sendaddressname']);
				$addr=explode("|",$info['sendaddressname']);//发件
				$send_truename=$addr[0];
				$send_country=$addr[2];
				$send_province=$addr[3];
				$send_city=$addr[4];
				$send_address=$addr[5];
				//$send_postcode=$addr[6];
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




if($pflag==1){

?>




<div style="width:296mm;height:152mm;position: relative;top:0;right:0; font-size: 5.5mm;font-family: monospace;/* background: url(http://order.goship.cn/ems.jpg); background-size: 296mm 152mm; */">
	<div style="width:90mm;">
		<div style="position:absolute;top:45mm;left:120mm;"><?php echo $take_truename;?></div> <!----收件人姓名---->
		<div style="position:absolute;top:45mm;left:185mm;"><?php echo $takeaddr[6];?></div>	<!----邮编---->
	</div>
	<div style="position:absolute;top:53mm;left:120mm;word-break:break-all;width:90mm;  text-align: left;">
	<?php //echo $takeaddr[3];?><!----省---->
	<?php //echo $takeaddr[4];?><!----市---->
	<?php //echo $takeaddr[5];?><!----地址---->

	<?php
	
	if(strpos($info['takename'],"中国")===false){
		echo $take_address." ".$take_city." ".$take_province;
	}else{
		echo $takeaddr[3].$takeaddr[4].$takeaddr[5];
	}
	
	?>

	</div>
	<div style="position:absolute;top:77mm;left:140mm;"><?php echo $takeaddr[2] ;?></div><!----国家---->
	<div style="position:absolute;top:79mm;left:175mm;"><?php echo $takeaddr[1];?></div><!----电话---->
	<div style="position:absolute;top:88mm;left:175mm;"><span style="font-family: -webkit-body;font-size: 4mm;">
	(<?php echo $info['aid'];?><!----编号---->
	<?php echo str_replace('H','-',str_replace('HT','-',$info['takeflag']));?>)
	
	<!----用户标识---->
	</span>
	</div>
	<?php
	$declaredatas = string2array($info['declaredatas']);
	
	?>
	<div style="font-size: 4mm;max-height:20mm;">
	
	<?php
	$top=92;
	foreach($declaredatas['datas'] as $val){
		$top+=4.5;
	?>
	
	<div style="position:absolute;top:<?php echo $top;?>mm;left:40mm;"><?php echo $val['declarename'];?></div><!----申报物品名---->
	<div style="position:absolute;top:<?php echo $top;?>mm;left:121mm;"><?php echo $val['declareamount'];?></div><!----申报数量---->
	<div style="position:absolute;top:<?php echo $top;?>mm;left:149mm;"><?php echo $val['declareprice'];?></div><!----申报价值---->
	
	<?php
	}
	?>
	<div style="position:absolute;top:113mm;left:190mm;font-size: 5mm"><?php echo $declaredatas['total'];?></div><!----申报总价---->
	</div>
</div>
	





<?

}

if($pflag==3){?>

<div style="width:296mm;height:152mm;position: relative;top:0;right:0; font-size: 5.5mm;font-family: monospace;/* background: url(http://order.goship.cn/sal.jpg); background-size: 296mm 152mm; */">
	<div style="width:90mm;">
	<div style="position:absolute;top:24mm;left:123mm;"><?php echo $take_truename;?>
	<span style="padding-left:20px;font-size:3mm;">
	(<?php echo $info['aid'];?><!----编号---->
	<?php echo str_replace('H','-',str_replace('HT','-',$info['takeflag']));?>)
	<!----用户标识---->
	</span>
	</div> <!----收件人姓名---->
	
	<div style="position:absolute;top:23mm;left:185mm;"><?php echo $takeaddr[6];?></div>	<!----邮编---->
	
	<div style="position:absolute;top:32mm;left:123mm;word-break:break-all;width:90mm;  text-align: left;">

	<?php
	
	if(strpos($info['takename'],"中国")===false){
		echo $take_address." ".$take_city." ".$take_province;
	}else{
		echo $takeaddr[3].$takeaddr[4].$takeaddr[5];
	}
	
	?>
	
	</div>
	<div style="position:absolute;top:53mm;left:120mm;"><?php echo $takeaddr[1];?></div><!----电话---->
	<div style="position:absolute;top:52mm;left:175mm;"><?php echo $takeaddr[2] ;?></div><!----国家---->
	</div>
	<?php
	$declaredatas = string2array($info['declaredatas']);
	
	?>
	<div style="font-size: 4mm;max-height:20mm;">
	<?php
	$top=64;
	foreach($declaredatas['datas'] as $val){
		$top+=4;
	?>
		<div style="position:absolute;top:<?php echo $top;?>mm;left:40mm;"><?php echo $val['declarename'];?></div><!----申报物品名---->
		<div style="position:absolute;top:<?php echo $top;?>mm;left:88mm;"><?php echo $val['declareamount'];?></div><!----申报数量---->
		<div style="position:absolute;top:<?php echo $top;?>mm;left:157mm;"><?php echo $val['declareprice'];?></div><!----申报价值---->
		<?php
	}
	?>
		<div style="position:absolute;top:89mm;left:145mm;font-size: 4mm"><?php echo $declaredatas['total'];?></div><!----申报总价---->

	</div>
</div>

<?php


}


}										
	?>
	
</div>

	

   
</body>
</html>
