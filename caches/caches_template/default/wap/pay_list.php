<?php defined('IN_PHPCMS') or exit('No permission resources.'); ?><html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=no;">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="format-detection" content="telephone=no">
<title><?php echo $this->__all__urls[0]['amount'];?>--<?php echo $this->wap['sitename'];?></title>

<link href="/resource/wap/css/lists.css" rel="stylesheet" type="text/css" />

<script src="/resource/wap/js/jquery.min.js" type="text/javascript"></script>
<style>
</style>
</head>

<body class="mode_webapp">

<?php include template("wap","header_min"); ?>



<link href="/resource/wap/css/news3_.css" rel="stylesheet" type="text/css" />

<div id="ui-header">
<div class="fixed">
<a class="ui-title" id="popmenu"><?php echo $this->__all__urls[0]['amount'];?></a>
<a class="ui-btn-left_pre" href="javascript:history.go(-1);"></a><a class="ui-btn-right_home" href="<?php echo WAP_SITEURL;?>"></a>
</div>
</div>

    
<?php if(!$__is__userinfo__full[value]) { ?>

 <form action="" method="post" name="mainForm" id="mainForm">
 <input type="hidden" name="forward" id="forward" value="<?php echo urlencode($_GET['forward']);?>" />


<div class="contact-info" style=" margin-top:10px;">

<ul>
<li><table border=0 width="100%"><tr><td><p>余额: <font color=red><?php echo $this->current_user['amount'];?>元</font></p><p>积分: <font color=red><?php echo $this->current_user['point'];?></font></p></td><td>
<input type="button" id="address_add"  class="submit_b"  value="充 值" style="width:100%" onclick="window.location.href='<?php echo $this->__all__urls[1]['addmoney'];?>';"></td></tr></table> 
</li>
</ul>
<ul>
<li>

<table width="100%" border="0" cellpadding="0" cellspacing="0" class="cpbiaoge" >
 <tr>
    <th width="50%" class="cc" style="<?php if($_GET[c]!='deposit') { ?>background:#fff;<?php } ?>cursor:pointer" onclick="window.location.href='index.php?m=wap&c=deposit&a=init'">充值记录</th>
    <th width="50%" class="cc" style="<?php if($_GET[c]!='spend_list') { ?>background:#fff;<?php } ?>border-left:solid 1px #ededed;cursor:pointer" onclick="window.location.href='index.php?m=wap&c=spend_list&a=init'">消费记录</th>
  </tr>
  </table>
 <table width="100%" border="0" cellpadding="0" cellspacing="0" class="cpbiaoge" style="border-top:none;"> 
  <?php if($_GET[c]=='deposit') { ?>
  <tr>
    <th>流水号</th>
    <th width="70" class="cc">金额(元)</th>
    <th width="55" class="cc">状态</th>
  </tr>
  <?php $n=1;if(is_array($infos)) foreach($infos AS $info) { ?>
   <tr>
 
    <td><?php echo $info['trade_sn'];?></td>
   
    <td width="55" class="cc"><span class="color-red"><?php echo $info['money'];?> <?php echo $info['type']==1 ? '':'积分'?></span></td>
	<td width="70" class="cc"><?php echo __L__($info['status']);?></td>
  </tr>
  <tr>
  <td colspan=5 style="border-top:none"><?php echo $info['payment'];?> 时间: <?php echo date('y-m-d H:i:s',$info['addtime']);?> </td>
  </tr>
  <?php $n++;}unset($n); ?>

  <?php } ?>

   <?php if($_GET[c]=='spend_list') { ?>
  <tr>
    <th>消费内容</th>
	<th  class="cc">创建时间</th>
    <th width="70" class="cc">金额(元)</th>
    
  </tr>
  <?php $n=1;if(is_array($list)) foreach($list AS $info) { ?>
   <tr>
    <td><?php echo $info['msg'];?></td>
    <td width="70" class="cc"><?php echo format::date($info['creat_at'], 1);?></td>
    <td width="55" class="cc"><span class="color-red"><?php echo $info['value'];?> <?php echo $info['type']==1 ? '':'积分'?></span></td>
  </tr>
  <?php $n++;}unset($n); ?>

  <?php } ?>
  </table>
</li>
</ul>
<ul>

<li>
		<div class="pages"><?php echo $pages;?></div>			
</li>
</ul>
   
    </div>

    
    
    <div class="footReturn" style="margin-bottom:80px;">
	
	
	 
<div class="window" id="windowcenter">
<div id="title" class="wtitle">操作提示<span class="close" id="alertclose"></span></div>
<div class="content">
<div id="txt"></div>
</div>
</div>
</div></form>
     <script type="text/javascript">
   function allchk(obj){
     $("input[name='aid[]']").attr("checked",obj.checked);
   }
   </script>
<script type="text/javascript"> 


var oLay = document.getElementById("overlay");	


$("#windowclosebutton").click(function () { 
$("#windowcenter").slideUp(500);
oLay.style.display = "none";

}); 
$("#alertclose").click(function () { 
$("#windowcenter").slideUp(500);
oLay.style.display = "none";

}); 

function alert(title,url){ 

$("#windowcenter").slideToggle("slow"); 
$("#txt").html(title);
if(url!=''){setTimeout('$("#windowcenter").slideUp(500,function(){window.location.href ="'+url+'";})',4000);}else{
	setTimeout('$("#windowcenter").slideUp(500)',4000);
}
} 

</script>
<?php } ?>
<?php include template("wap","footer"); ?>
</body>
</html>
