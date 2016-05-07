<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<div class="pad-10">
<form method="post" action="?m=waybill&c=admin_waybill&a=waybill_morepaison&status=<?php echo $status;?>&hid=<?php echo $_GET['hid'];?>" name="myform" id="myform">
<input name="data[status]"  id="status" type="hidden" value="<?php echo $status;?>" >
<table class="table_form" width="100%" cellspacing="0">
<tbody>
	<tr>
		<th><strong><?php echo L('waybillid')?>：</strong></th>
		<td><textarea name="data[waybillid]" id="waybillid" class="input-text" cols="30" rows="13" readonly><?php echo $sno;?></textarea></td>
	</tr>
	

	<tr id="oneselftime0" >
		<th><strong><?php echo L('oneselftime')?>：</strong></th>
		<td><input name="data[sendtime]" id="sendtime" class="input-text" type="text" size="30" value="<?php echo date('Y-m-d',time());?>"></td>
	</tr>

	<tr>
		<th><strong><?php echo L('finish_username')?>：</strong></th>
		<td><input name="data[username]" id="username" class="input-text" type="text" size="30" value="<?php echo $this->username;?>" readonly></td>
	</tr>

	<tr>
		<th><strong><?php echo L('finish_time')?>：</strong></th>
		<td><input name="data[addtime]" id="addtime" class="input-text" type="text" size="30" value="<?php echo date('Y-m-d H:i:s',time());?>" readonly></td>
	</tr>
	<tr>
		<th><strong><?php echo L('common_send_email_notify');?>：</strong></th>
		<td><input name="send_email_nofity" id="send_email_nofity1"  type="radio"  value="1" ><?php echo L('Yes');?>&nbsp;&nbsp;<input name="send_email_nofity" id="send_email_nofity0"  type="radio"  value="0" checked><?php echo L('No');?>&nbsp;&nbsp;</td>
	</tr>
	<tr>
  		<th><strong><?php echo L('remark')?>：</strong></th>
        <td>
		<textarea name="handle[remark]" id="remark" cols="30" rows=2></textarea>
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

function kdi(flag){
	if(flag==14){
		document.getElementById('expressnumber0').style.display="";
		document.getElementById('expressurl0').style.display="";
	}else{
		document.getElementById('expressnumber0').style.display="none";
		document.getElementById('expressurl0').style.display="none";
	}
}

$(document).ready(function(){
	$.formValidator.initConfig({formid:"myform",autotip:true,onerror:function(msg,obj){}});
	$('#waybillid').formValidator({onshow:"<?php echo L('waybillid').L('cannot_empty');?>",onfocus:"<?php echo  L('waybillid').L('cannot_empty')?>",oncorrect:"<?php echo L('right')?>"}).inputValidator({min:1,onerror:"<?php echo  L('waybillid').L('cannot_empty')?>"});
});
</script>