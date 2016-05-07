<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<div class="pad-10">
<form method="post" action="?m=waybill&c=admin_waybill&a=add" name="myform" id="myform">
<table class="table_form" width="100%" cellspacing="0">
<tbody>
	<tr>
		<th width="80"><strong><?php echo L('number')?>：</strong></th>
		<td><input name="waybill[number]" id="number" class="input-text" type="text" size="30" ></td>
	</tr>
	<tr>
		<th><strong><?php echo L('weight')?>：</strong></th>
		<td><input name="waybill[weight]" id="weight" class="input-text" type="text" size="30" ></td>
	</tr>
	<tr>
		<th><strong><?php echo L('price')?>：</strong></th>
		<td><input name="waybill[price]" id="price" class="input-text" type="text" size="30" ></td>
	</tr>

	<tr>
  		<th><strong><?php echo L('remark')?>：</strong></th>
        <td>
		<textarea name="waybill[remark]" id="remark" cols="50" rows=4></textarea>
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
	$.formValidator.initConfig({formid:"myform",autotip:true,onerror:function(msg,obj){window.top.art.dialog({content:msg,lock:true,width:'220',height:'70'}, function(){this.close();$(obj).focus();})}});
	$('#number').formValidator({onshow:"<?php echo L('number').L('cannot_empty');?>",onfocus:"<?php echo  L('number').L('cannot_empty')?>",oncorrect:"<?php echo L('right')?>"}).inputValidator({min:1,onerror:"<?php echo  L('number').L('cannot_empty')?>"});
});
</script>