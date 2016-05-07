<?php 
defined('IN_ADMIN') or exit('No permission resources.');
//include $this->admin_tpl('header', 'admin');
?>
<?php defined('IN_ADMIN') or exit('No permission resources.'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"<?php if(isset($addbg)) { ?> class="addbg"<?php } ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET;?>" />
<meta http-equiv="X-UA-Compatible" content="IE=7" />
<title><?php echo L('website_manage');?></title>
<link href="<?php echo CSS_PATH?>reset.css" rel="stylesheet" type="text/css" />
<link href="<?php echo CSS_PATH.SYS_STYLE;?>-system.css" rel="stylesheet" type="text/css" />
<link href="<?php echo CSS_PATH?>table_form.css" rel="stylesheet" type="text/css" />
<?php
if(!$this->get_siteid()) showmessage(L('admin_login'),'?m=admin&c=index&a=login');
if(isset($show_dialog)) {
?>
<link href="<?php echo CSS_PATH?>dialog.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>dialog.js"></script>
<?php } ?>
<link rel="shortcut icon" href="/favicon.png" />
	
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>jquery.min.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>admin_common.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>styleswitch.js"></script>
<?php if(isset($show_validator)) { ?>
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>formvalidator.js" charset="UTF-8"></script>
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>formvalidatorregex.js" charset="UTF-8"></script>
<?php } ?>
<script type="text/javascript">
	window.focus();
	var checkhash = '<?php echo $_SESSION['checkhash'];?>';
	<?php if(!isset($show_pc_hash)) { ?>
		window.onload = function(){
		var html_a = document.getElementsByTagName('a');
		var num = html_a.length;
		for(var i=0;i<num;i++) {
			var href = html_a[i].href;
			if(href && href.indexOf('javascript:') == -1) {
				if(href.indexOf('?') != -1) {
					html_a[i].href = href+'&checkhash='+checkhash;
				} else {
					html_a[i].href = href+'?checkhash='+checkhash;
				}
			}
		}

		var html_form = document.forms;
		var num = html_form.length;
		for(var i=0;i<num;i++) {
			var newNode = document.createElement("input");
			newNode.name = 'checkhash';
			newNode.type = 'hidden';
			newNode.value = checkhash;
			html_form[i].appendChild(newNode);
		}
	}
<?php } ?>

</script>
</head>
<body onLoad="buildfile()">
<?php if(!isset($show_header)) { ?>
<div class="subnav">
    <div class="content-menu ib-a blue line-x">
    <?php if(isset($big_menu)) { echo '<a class="add fb" href="'.$big_menu[0].'"><em>'.$big_menu[1].'</em></a>　';} else {$big_menu = '';} ?>
    <?php echo admin::submenu($_GET['menuid'],$big_menu); ?>
    </div>
</div>
<?php } ?>
<style type="text/css">
	html{_overflow-y:scroll}
</style>

<div class="pad-10">


<form method="post"  name="myform" id="myform" onsubmit="return chkfrm()">
<table  width="100%" cellspacing="0">
<tbody>
<tr>
<td colspan=2>
<?php echo L('time')?>：<?php echo form::date('start_time', $start_time)?>-
				<?php echo form::date('end_time', $end_time)?>&nbsp;<input type="button" name="gotosearch" value="<?php echo L('now_search')?>"  class="button" onclick="buildfile()" />&nbsp;&nbsp;
</td>
</tr>
<tr>
		<td ><strong>No.</strong><span style="color:green">(<span  id="no_count">0</span>)</span></td>
		<td ><strong>Status</strong></td>
</tr>
	<tr>
		<td><textarea style="line-height:18px;" name="BatchNo" id="BatchNo" rows="22" cols="50" onkeydown="BatchDo()"></textarea></td>
		<td><div id="BatchNostatus" style="border:solid 1px #ccc;overflow-y:auto;width: 322px; height: 330px;font:12px/1.2 tahoma, arial, 宋体, sans-serif;color:#000;line-height:18px;"></div></td>
	</tr>
	</tbody>
</table>

<input type="submit"  name="dosubmit" id="dosubmit" value=" <?php echo L('ok')?> " class="dialog">&nbsp;<input type="reset" class="dialog" value=" <?php echo L('clear')?> ">


</form>
</div>
 

</body>
<script>
var num=0;

var urls = "/api.php?op=cacheno&hid=<?php echo $this->hid?>&checkhash=<?php echo $_SESSION['checkhash'];?>&uid=<?php echo $this->userid;?>&t="+Math.random();

function buildfile(){
	
	var beginT = $("#start_time").val();
	var endT = $("#end_time").val();

	$.get("?m=house&c=admin_house&a=house_jihuo&hid=<?php echo $this->hid?>&flag=1&t="+Math.random()+"&checkhash=<?php echo $_SESSION['checkhash'];?>&status=-1&start_time="+beginT+"&end_time="+endT,function(data){
		$("#no_count").html(data.count);
		setFos();
	},'json');
}

function chkfrm(){
	if($("#no_count").text()=='0'){
		alert("Plase Select Pi Ci");
		return false;
	}
	return true;
}

function BatchDo(){

	 var event=arguments.callee.caller.arguments[0]||window.event;  
     if (event.keyCode == 13){
		 num +=1;
		 var datas = $("#BatchNo").val();

		 if(datas!=''){
			var arr = datas.split("\n");
			
			$("#BatchNostatus").append("<p id='"+arr[arr.length-1]+"_"+num+"'>"+arr[arr.length-1]+" </p>");
			
			var sHtml="<scr"+"ipt>$.get('"+urls+"&no="+arr[arr.length-1]+"',function(data){$('#"+arr[arr.length-1]+"_"+num+"').css('background-color',data.color); },'json');</scr"+"ipt>";

			var notxt = $("#"+arr[arr.length-1]+"_"+num).text();
			$("#"+arr[arr.length-1]+"_"+num).html(notxt+sHtml);
			
		 }

		
		document.getElementById('BatchNostatus').scrollTop=document.getElementById('BatchNostatus').scrollHeight; 
	}
}

function setFos(){
	if(window.top.art.dialog({id:'scanning_gun_storage'})){
		window.top.art.dialog({id:'scanning_gun_storage'}).data.iframe.document.getElementById('BatchNo').focus();
	}
}

setFos();
</script>
</html>