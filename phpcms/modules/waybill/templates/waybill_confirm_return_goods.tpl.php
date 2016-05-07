<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<div class="pad-10">
<form method="post" action="?m=waybill&c=admin_waybill&a=waybill_confirm_return_goods&aid=<?php echo $_GET['aid']?>" name="myform" id="myform">

<table class="table_form" width="100%" cellspacing="0" >
<tbody>
	<tr>
		<th><strong><?php echo L('waybillid')?>：</strong></th>
		<td><?php echo $waybillid;?></td>
	</tr>
	<tr>
		<th><strong><?php echo L('returned_back_address')?>：</strong></th>
		<td><?php echo $return_address;?></td>
	</tr>
	<tr>
		<th><strong><?php echo L('returned_back_person')?>：</strong></th>
		<td><?php echo $return_person;?></td>
	</tr>
	<tr>
		<th><strong><?php echo L('returned_back_mobile')?>：</strong></th>
		<td><?php echo $return_mobile;?></td>
	</tr>
	<tr>
		<th><strong><?php echo L('house_jihuo_returned_information').L('remark');?>：</strong></th>
		<td><?php echo $return_remarkf;?></td>
	</tr>
	<tr>
		<th ><strong><?php echo L('returned_fee')?>：</strong></th>
		<td ><input name="returnfee" id="returnfee" class="input-text" type="text" size="20" value=""></td>
	</tr>
	<tr>
		<th ><strong><?php echo L('remark')?>：</strong></th>
		<td ><textarea name="returnremark" id="remark" cols="50" rows=2></textarea></td>
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
	$.formValidator.initConfig({formid:"myform",autotip:true,onerror:function(msg,obj){window.top.art.dialog({content:msg,lock:true,width:'220',height:'70'}, function(){this.close();$(obj).focus();})}});
	$('#returnfee').formValidator({onshow:"<?php echo L('returned_fee').L('cannot_empty');?>",onfocus:"<?php echo L('returned_fee').L('cannot_empty');?>",oncorrect:"<?php echo L('right')?>"}).inputValidator({min:1,onerror:"<?php echo L('returned_fee').L('cannot_empty');?>"});
});


</script>