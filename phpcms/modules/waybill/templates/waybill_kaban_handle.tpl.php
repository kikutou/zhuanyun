<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<div class="pad-10">
<form method="post" action="/index.php?m=waybill&c=admin_waybill&a=waybill_kaban_handle&ids=<?php echo $ids;?>" name="myform" id="myform">
<table class="table_form" width="100%" cellspacing="0">
<tbody>
	<tr>
		<th ><strong><?php echo L('kabanno')?>：</strong></th>
		<td><input name="data[kabanno]" id="kabanno" class="input-text" type="text" size="30" value="<?php echo $kabanno;?>"></td>
	</tr>
	<tr>
		<th ><strong><?php echo L('selected_bill_recoeds')?>：</strong></th>
		<td>
		<textarea name="data[waybillid]" id="waybillid" cols="30" rows=13 ><?php echo $allbills;?></textarea><textarea name="data[packageno_status]" id="packageno_status" cols="6" rows=13 readonly></textarea>&nbsp;<input type="button" name="checkbut" value="<?php echo L('unionbox_handcheck');?>" onclick="checkRows()"/>
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
		<th><strong><?php echo L('operator')?>：</strong></th>
		<td><?php echo $this->username;?></td>
	</tr>

	<tr>
  		<th><strong><?php echo L('remark')?>：</strong></th>
        <td>
		<textarea name="data[remark]" id="remark" cols="30" rows=3></textarea>
		</td>
	</tr>
	</tbody>
</table>
<input type="submit" name="dosubmit" id="dosubmit" value=" <?php echo L('ok')?> " class="dialog">&nbsp;<input type="reset" class="dialog" value=" <?php echo L('clear')?> ">
</form>
</div>


<script type="text/javascript">
checkRows();
function checkRows(){
	var allrows = parseInt($("#unionbox_packagenum").text());
	var obj=document.getElementById('waybillid');
	var v=obj.value.split('\n');
		var expressno='';
		document.getElementById('packageno_status').value="";
		for(var i=0;i<allrows;i++){
			if(v[i]!=undefined ){
				if(expressno=='')
					expressno=v[i];
				else
					expressno+=","+v[i];
			}
		}

	$.post("?m=waybill&c=admin_waybill&a=unionbox_check_bill&checkhash=<?php echo $_SESSION['checkhash'];?>&t="+Math.random()+"&expressno="+expressno,function(result){
			document.getElementById('packageno_status').value+=result;
	});

}
</script>

</body>
</html>
