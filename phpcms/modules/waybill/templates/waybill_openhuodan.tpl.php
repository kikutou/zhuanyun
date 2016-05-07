<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<div class="pad-10">
<form method="post" action="/index.php?m=waybill&c=admin_waybill&a=waybill_openhuodan" name="myform" id="myform">
<input type="hidden" name="position" value="<?php echo $_GET['position'];?>"/>
<table class="table_form" width="100%" cellspacing="0">
<tbody>
	<tr>
		<th width="80"><strong><?php echo L('huodan_huodanno')?>：</strong></th>
		<td><input name="data[huodanno]" id="huodanno" class="input-text" type="text" size="30" value="<?php echo trim($_GET['no']);?>" onblur="getkabanlist()"></td>
	</tr>
	<tr>
		<th ><strong><?php echo L('huodan_kabanno_list')?>(<span id="unionbox_packagenum" style="color:#ff6600"><?php echo $allpackages;?></span>)：</strong></th>
		<td>
		<textarea name="data[kabanno]" id="kabanno" cols="40" rows=13 style="display:none;"><?php echo $allbills;?></textarea><textarea name="showbill" id="showbill" cols="60" rows=13 readonly></textarea>
		</td>
	</tr>
	
	<tr>
		<th><strong><?php echo L('common_send_email_notify');?>：</strong></th>
		<td><input name="send_email_nofity" id="send_email_nofity1"  type="radio"  value="1" checked><?php echo L('Yes');?>&nbsp;&nbsp;<input name="send_email_nofity" id="send_email_nofity0"  type="radio"  value="0" ><?php echo L('No');?>&nbsp;&nbsp;</td>
	</tr>
	<tr>
		<th><strong><?php echo L('show_handle');?>：</strong></th>
		<td><input name="isshow" id="isshow1"  type="radio"  value="0" checked><?php echo L('Yes');?>&nbsp;&nbsp;<input name="isshow" id="isshow0"  type="radio"  value="1" ><?php echo L('No');?>&nbsp;&nbsp;</td>
	</tr>
	<tr>
		<th><strong><?php echo L('operator')?>：</strong></th>
		<td><?php echo $this->username;?></td>
	</tr>

	<tr>
  		<th><strong><?php echo L('remark')?>：</strong></th>
        <td>
		<textarea name="data[remark]" id="remark" cols="40" rows=2></textarea>
		</td>
	</tr>
	</tbody>
</table>
<input type="submit" name="dosubmit" id="dosubmit" value=" <?php echo L('ok')?> " class="dialog">&nbsp;<input type="reset" class="dialog" value=" <?php echo L('clear')?> ">
</form>
</div>
</body>
</html>
<script type="text/javascript">


$(document).ready(function(){
	$.formValidator.initConfig({formid:"myform",autotip:true,onerror:function(msg,obj){}});
	$('#huodanno').formValidator({onshow:"<?php echo L('huodan_huodanno').L('cannot_empty');?>",onfocus:"<?php echo  L('huodan_huodanno').L('cannot_empty')?>",oncorrect:"<?php echo L('right')?>"}).inputValidator({min:1,onerror:"<?php echo  L('huodan_huodanno').L('cannot_empty')?>"});
});
var huodanno = $('#huodanno').val();
function getkabanlist(){
	
	$.get("?m=waybill&c=admin_waybill&a=waybill_openhuodan&f=waybill_openhuodan_getkaban&checkhash=<?php echo $_SESSION['checkhash'];?>&huodanno="+huodanno,function(data){
		if(data){
			var arr=data.split("\n");
			$("#unionbox_packagenum").text(arr.length);
			$("#kabanno").val(data);
		}else{
			alert("<?php echo L('kaban_tip_cannot_empty');?>");
			$("#huodanno").val("");
			$("#huodanno").focus();
			return false;
		}
	});
}

$.get("?m=waybill&c=admin_waybill&a=waybill_openhuodan&f=waybill_openhuodan_getbill_line&checkhash=<?php echo $_SESSION['checkhash'];?>&huodanno="+huodanno,function(data){
	
	$("#showbill").val(data);
});

getkabanlist();
</script>