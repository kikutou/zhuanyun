<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<div class="pad-10">
<form method="post" action="?m=ownaddress&c=admin_ownaddress&a=add" name="myform" id="myform">
<table class="table_form" width="100%" cellspacing="0">
<tbody>
	<tr>
		<th><strong><?php echo L('userid')?>：</strong></th>
		<td><input name="ownaddress[userid]" id="userid" class="input-text" type="text" size="30" value="0"></td>
	</tr>
	<tr>
		<th><strong><?php echo L('firstname')?>：</strong></th>
		<td><input name="ownaddress[firstname]" id="firstname" class="input-text" type="text" size="10" ><?php echo L('lastname')?>&nbsp;&nbsp;<input name="ownaddress[lastname]" id="lastname" class="input-text" type="text" size="10" ></td>
	</tr>
	
	<tr>
		<th><strong><?php echo L('address')?>：</strong></th>
		<td><input name="ownaddress[address]" id="address" class="input-text" type="text" size="45" ></td>
	</tr>
	<tr>
		<th><strong><?php echo L('placename')?>：</strong></th>
		<td><input name="ownaddress[province]" id="province" class="input-text" type="text" size="10" ><?php echo L('province')?>&nbsp;&nbsp;<input name="ownaddress[city]" id="city" class="input-text" type="text" size="10" ><?php echo L('city')?></td>
	</tr>
	
	
	<tr>
		<th><strong><?php echo L('postcode')?>：</strong></th>
		<td><input name="ownaddress[postcode]" id="postcode" class="input-text" type="text" size="30" ></td>
	</tr>

	<tr>
		<th><strong><?php echo L('mobile')?>：</strong></th>
		<td><input name="ownaddress[mobile]" id="mobile" class="input-text" type="text" size="30" ></td>
	</tr>
	<tr>
		<th><strong><?php echo L('addressusefee')?>：</strong></th>
		<td><input name="ownaddress[addressusefee]" id="addressusefee" class="input-text" type="text" size="10" value="0"></td>
	</tr>
	<tr>
		<th><strong><?php echo L('storageid')?>：</strong></th>
		<td><input name="ownaddress[storageid]" id="storageid" class="input-text" type="text" size="30"  ></td>
	</tr>
	<tr>
		<th><strong><?php echo L('number')?>：</strong></th>
		<td><input name="ownaddress[number]" id="number" class="input-text" type="text" size="10" value="0" ></td>
	</tr>

	
	<tr>
		<th><strong><?php echo L('packageno')?>：</strong></th>
		<td><input name="ownaddress[packageno]" id="packageno" class="input-text" type="text" size="30"  ></td>
	</tr>
	<tr>
		<th><strong><?php echo L('title')?>：</strong></th>
		<td><input name="ownaddress[title]" id="title" class="input-text" type="text" size="30"  ></td>
	</tr>
	<tr>
		<th><strong><?php echo L('weight')?>：</strong></th>
		<td><input name="ownaddress[weight]" id="weight" class="input-text" type="text" size="10" value="0" ></td>
	</tr>
	<tr>
		<th><strong><?php echo L('remark')?>：</strong></th>
		<td><textarea name="ownaddress[remark]" id="remark" cols="50" rows=4></textarea></td>
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
	$('#packageno').formValidator({onshow:"<?php echo L('packageno_cannot_empty')?>",onfocus:"<?php echo L('packageno_cannot_empty')?>",oncorrect:"<?php echo L('right')?>"}).inputValidator({min:1,onerror:"<?php echo L('packageno_cannot_empty')?>"});
});
</script>