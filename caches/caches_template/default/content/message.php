<?php defined('IN_PHPCMS') or exit('No permission resources.'); ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>提示信息</title>

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
<script type="text/javaScript" src="<?php echo JS_PATH;?>jquery.min.js"></script>
<script language="JavaScript" src="<?php echo JS_PATH;?>admin_common.js"></script>
</head>
<body>
<div id="iAmAbsoluteInsideFixed"> 提示信息
<pre>
<p align=center>

<center><?php echo $msg;?></center>

   <?php if($url_forward=='goback' || $url_forward=='') { ?>
	<a href="javascript:history.back();" >[点这里返回上一页]</a>
	<?php } elseif ($url_forward=="close") { ?>
	
	<div class="ui-dialog-buttonset"><button type="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button" aria-disabled="false" onClick="window.close();"><span class="ui-button-text">关闭</span></button>

	<?php } elseif ($url_forward) { ?>
	<a href="<?php echo $url_forward;?>">如果您的浏览器没有自动跳转，请点击这里</a>
	<script language="javascript">setTimeout("redirect('<?php echo $url_forward;?>');",<?php echo $ms;?>);</script> 
	<?php } ?>
	<?php if($dialog) { ?><script style="text/javascript">window.top.location.reload();window.top.art.dialog({id:"<?php echo $dialog;?>"}).close();</script><?php } ?>

		</p>

</pre>
    </div>
</body>
<script style="text/javascript">
	function close_dialog() {
		window.top.location.reload();window.top.art.dialog({id:"<?php echo $dialog?>"}).close();
	}
</script>
</html>
