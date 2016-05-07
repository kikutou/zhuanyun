<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<div class="pad-10">
<form method="post" action="/index.php?m=waybill&c=admin_waybill&a=waybill_openkaban" name="myform" id="myform">
<table class="table_form" width="100%" cellspacing="0">
<tbody>
	<tr>
		<th width="80"><strong><?php echo L('kabanno')?>：</strong></th>
		<td><input name="data[kabanno]" id="kabanno" class="input-text" type="text" size="30" value="<?php echo $_GET['no'];?>" onblur="getkabanlist();"></td>
	</tr>
	<tr>
		<th ><strong><?php echo L('waybillid')?>：</strong></th>
		<td>
		<textarea name="data[waybillid]" id="waybillid" cols="40" rows=15 ><?php echo $allbills;?></textarea>
		</td>
	</tr>
	<tr>
		<th><strong><?php echo L('waybill_count')?>：</strong></th>
		<td><span id="unionbox_packagenum"><?php echo $allpackages;?></span>&nbsp;&nbsp;</td>
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
		<textarea name="data[remark]" id="remark" cols="40" rows=4></textarea>
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
	$('#kabanno').formValidator({onshow:"<?php echo L('kabanno').L('cannot_empty');?>",onfocus:"<?php echo  L('kabanno').L('cannot_empty')?>",oncorrect:"<?php echo L('right')?>"}).inputValidator({min:1,onerror:"<?php echo  L('kabanno').L('cannot_empty')?>"});
});

function getkabanlist(){
	var kabanno = $('#kabanno').val();
	$.get("?m=waybill&c=admin_waybill&a=waybill_openkaban_getwaybill&checkhash=<?php echo $_SESSION['checkhash'];?>&kabanno="+kabanno,function(data){
		if(data){
			var arr=data.split("\n");
			$("#unionbox_packagenum").text(arr.length);
			$("#waybillid").val(data);
		}else{
			alert("<?php echo L('waybill_tip_cannot_empty');?>");
			$("#kabanno").val("");
			$("#kabanno").focus();
			return false;
		}
	});
}

getkabanlist();
</script>