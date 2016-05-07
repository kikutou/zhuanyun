<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>单个打印</title>
<style type="text/css">
.divw
{	 display:none; 
	width:800px;
}
.lefttop
{
	float:left;
	height:120px;
}
.left1
{
	float:left;
	width:350px;
}
.left2
{
	float:left;
	width:50px;
}
.left3
{
	float:left;
	width:400px;
}
.tab
{
	border-left:#000 1px solid;
	border-top:#000 1px solid;
}
.tab td
{
	border-bottom:#000 1px solid;
	border-right:#000 1px solid;
	padding-left:8px;
	font:"微软雅黑";
	font-size:12px;
	height:50px;
	line-height:15px;
}
.tab2
{
	border-left:#000 1px solid;
	
}
.tab2 td
{
	border-bottom:#000 1px solid;
	border-right:#000 1px solid;
	padding-left:8px;
	font:"微软雅黑";
	font-size:12px;
	height:30px;

}
</style>
</head>

<body>
<?php 
		$sinfos= siteinfo(1);
		$takeaddress=str_replace('|','&nbsp;&nbsp;',$info['takeaddressname']);
		$sendaddress=str_replace('|','&nbsp;&nbsp;',$info['sendaddressname']);
		$takeaddr=explode('|',$info['takeaddressname']);
		$sendaddr=explode('|',$info['sendaddressname']);
	?>


<style type="text/css">
.bill_print {margin:0 auto;width:500px;height:480px;padding:10px;position: relative; }
.bill_print_time {float: right;margin-top: 0;}
.bill_print_number {position:absolute;bottom:0px;left:0px;}

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


<div class="bill_print">
	<div>
		<div class="bill_print_flag"><?php echo $info['takeflag'];?></div>
		<div class="bill_print_aid">#<?php echo $info['aid'];?></div>
	</div>
	<div class="bill_print_line">
		<div class="bill_print_name">&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;姓名:<?php echo $takeaddr[0];?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<?php echo str_replace('2','分箱',str_replace('1','合箱',str_replace('0','单票直发',$info['srvtype'])));?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<?php date_default_timezone_set("Asia/Shanghai"); echo  date("Y-m-d H:i:s");?>
		</div>
	</div>	
	<div class="bill_print_line">	
		<div class="bill_print_text"><?php echo $takeaddr[2] ;?>&nbsp;<?php echo $takeaddr[3];?>&nbsp;<?php echo $takeaddr[4];?>&nbsp;<?php echo $takeaddr[5];?>&nbsp;<?php echo $takeaddr[6];?> &nbsp;&nbsp;&nbsp; <?php echo $takeaddr[1];?></div>
	</div>	
	<div class="bill_print_line">增值服务:
	<?php
		$allsrv=string2array($info['addvalues']);
						foreach($allsrv as  $srv){
							echo $srv['title'];
							if ($srv[servicetypeid]==13){
								$srv['price'] = $srv['price']." ".$info['otherfee'];
							}
							echo '<font color="#FF953F">'.$srv['price'].'</font>/';
						}
?>
	</div>	
	<div class="bill_print_line">分合箱:<?php echo $info['otherremark'];?></div>
<div class="bill_print_remark">备注:<?php echo $info['remark'];?></div>	
	<div class="bill_print_line">邮单号:<?php echo $info['expressno'];?></div>
	
		
		
	<div class="bill_print_line">物品名:
		<?php 
			$goodsdatas=string2array($info['goodsdatas']);
			foreach($goodsdatas as $key=>$goods)
			{
		echo'
			<div style="display:inline;padding-left:5px;"><span style="font-weight:bold;">(</span>'.$goods['goodsname'].'='.$goods['amount'].'<span style="font-weight:bold;">)</span></div>
			';
			}
		?>
	</div>

	<div class="bill_print_number"><img style="width: 330px;" src="?m=barcode&c=admin_barcode&a=init&hid=<?php echo $info['storageid'];?>?&data=<?php echo $info['waybillid'];?>" /></div>
	<div class="bill_print_yin">印:</div>
	<div class="bill_print_weight">重量:<?php echo $info['totalweight'];?>g</div>
</div>
<div style="height:25px;"></div>
<div class="bill_print">
	<div >
		<div class="bill_print_aid1">#<?php echo $info['aid'];?></div>
		<div class="bill_print_flag1"><?php echo $info['takeflag'];?></div>
		<div class="bill_print_text1">姓名:<?php echo $takeaddr[0];?></div>
	</div>
</div>
</body>
</html>
