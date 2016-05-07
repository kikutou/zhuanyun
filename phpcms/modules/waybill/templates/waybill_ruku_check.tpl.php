<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');




?>
<div class="pad-10">
<form method="post" action="?m=waybill&c=admin_waybill&a=waybill_ruku_check&hid=<?php echo $this->hid;?>" name="myform" id="myform"  >
<table class="table_form" width="100%" cellspacing="0">
<tbody>

	<tr>
		<th><strong><?php echo L('huodan_huodanno')?>：</strong></th>
		<td><textarea name="data[huodanno]" id="huodanno" class="input-text"  cols="30" rows="6" readonly><?php echo $sno;?></textarea></td>
	</tr>
	
	<tr>
		<th><strong><?php echo L('zhongzhuang_username')?>：</strong></th>
		<td><input name="data[username]" id="username" class="input-text" type="text" size="30" value="<?php echo $this->username;?>" readonly>
		</td>
	</tr>
	<tr>
		<th><strong><?php echo L('zhongzhuang_addtime')?>：</strong></th>
		<td><input name="data[addtime]" id="addtime" class="input-text" type="text" size="30" value="<?php echo date("Y-m-d H:i:s",time());?>" readonly></td>
	</tr>
	<tr>
		<th><strong><?php echo L('common_send_email_notify');?>：</strong></th>
		<td><input name="send_email_nofity" id="send_email_nofity1"  type="radio"  value="1" checked><?php echo L('Yes');?>&nbsp;&nbsp;<input name="send_email_nofity" id="send_email_nofity0"  type="radio"  value="0" ><?php echo L('No');?>&nbsp;&nbsp;</td>
	</tr>
	<tr>
  		<th><strong><?php echo L('remark')?>：</strong></th>
        <td>
		<textarea name="handle[remark]" id="remark" cols="30" rows=4></textarea>
		</td>
	</tr>
	</tbody>
</table>
<input type="submit"  name="dosubmit" id="dosubmit" value=" <?php echo L('ok')?> " class="dialog">&nbsp;<input type="reset" class="dialog" value=" <?php echo L('clear')?> ">

</form>
</div>
</body>
</html>
<script type="text/javascript">


$(document).ready(function(){
	$.formValidator.initConfig({formid:"myform",autotip:true,onerror:function(msg,obj){}});
	
	
	$('#huodanno').formValidator({onshow:"<?php echo L('huodan_cannot_be_empty')?>",onfocus:"<?php echo L('huodan_cannot_be_empty')?>",oncorrect:"<?php echo L('right')?>"}).inputValidator({min:1,onerror:"<?php echo L('huodan_cannot_be_empty')?>"});
	
	$('#weight').formValidator({onshow:"<?php echo L('weight_cannot_be_empty')?>",onfocus:"<?php echo L('weight_cannot_be_empty')?>",oncorrect:"<?php echo L('right')?>"}).inputValidator({min:1,onerror:"<?php echo L('weight_cannot_be_empty')?>"});

});
</script>