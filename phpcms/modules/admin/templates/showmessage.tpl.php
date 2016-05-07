<?php defined('IN_ADMIN') or exit('No permission resources.'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET;?>" />
<title><?php echo L('message_tips');?></title>

<style>
*{
	margin:0;
	padding:0;
}

body{
	font-family:"微软雅黑";
	font-size:14px;
	color:#666;
	background-color:#f8f8f8;
}

#iAmAbsoluteInsideFixed{
	border-radius:10px;
	width:380px;
	background-color:#DCF4F9;
	border:1px solid white;
	padding:15px;
	margin-top:10%;
	margin-left:auto;
	margin-right:auto;
}

pre{
	-moz-border-radius: 10px;
    -webkit-border-radius: 10px;
    border-radius: 10px;
	background-color:rgba(255, 255, 255, 0.35) ;
	-pie-background:rgba(255,255,255,.35);/*IE6-8*/ 
	color:#666;
	margin-top:10px;
	padding:10px;
	min-height:100px;
	text-align:center;
}

pre a{
	color:#999;
	font-weight:normal;
}
</style>
<script type="text/javaScript" src="<?php echo JS_PATH?>jquery.min.js"></script>
<script language="JavaScript" src="<?php echo JS_PATH?>admin_common.js"></script>
</head>
<body>
<div id="iAmAbsoluteInsideFixed"> <?php echo L('message_tips');?>
<pre>
<p align=center>

<center><?php echo $msg?></center>

  <?php if($url_forward=='goback' || $url_forward=='') {?>
	<a href="javascript:history.back();" >[<?php echo L('return_previous');?>]</a>
	<?php } elseif($url_forward=="close") {?>
	
	<div class="ui-dialog-buttonset"><button type="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button" aria-disabled="false" onClick="window.close();"><span class="ui-button-text"><?php echo L('close');?></span></button>

	<?php } elseif($url_forward=="blank") {?>
	
	<?php } elseif($url_forward) { 
		if(strpos($url_forward,'&checkhash')===false) $url_forward .= '&checkhash='.$_SESSION['checkhash'];
	?>
	<a href="<?php echo $url_forward?>"><?php echo L('click_here');?></a>
	<script language="javascript">setTimeout("redirect('<?php echo $url_forward?>');",<?php echo $ms?>);</script> 
	<?php }?>
	<?php if($returnjs) { ?> <script style="text/javascript"><?php echo $returnjs;?></script><?php } ?>
	<?php if ($dialog):?><script style="text/javascript">window.top.right.location.reload();window.top.art.dialog({id:"<?php echo $dialog?>"}).close();</script><?php endif;?>
		</p>

</pre>
    </div>
</body>
<script style="text/javascript">
	function close_dialog() {
		<?php if($dialog){echo 'window.top.location.reload();window.top.art.dialog({id:"'.$dialog.'"}).close();';}?>
	}

	
</script>
</html>
