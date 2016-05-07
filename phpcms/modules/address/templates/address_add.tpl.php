<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<div class="pad-10">
<form method="post" action="?m=address&c=admin_address&a=add" name="myform" id="myform">
<table class="table_form" width="100%" cellspacing="0">
<tbody>
	<tr>
		<th ><strong><?php echo L('address_title')?></strong></th>
		<td> <select name="address[addresstype]" id="addresstype">
				<option value="1"><?php echo L('getaddress')?></option>
				<option value="2"><?php echo L('setaddress')?></option>
			</select>
</td>
	</tr>
	<tr>
		<th><strong><?php echo L('truename')?>：</strong></th>
		<td><input name="address[truename]" id="truename" class="input-text" type="text" size="30" ></td>
	</tr>
	<tr>
		<th><strong><?php echo L('country')?>：</strong></th>
		<td><input name="address[country]" id="country" class="input-text" type="text" size="30" ></td>
	</tr>
	<tr>
		<th><strong><?php echo L('placename')?>：</strong></th>
		<td><input name="address[province]" id="province" class="input-text" type="text" size="10" ><?php echo L('province')?>&nbsp;&nbsp;<input name="address[city]" id="city" class="input-text" type="text" size="10" ><?php echo L('city')?></td>
	</tr>
	<tr>
		<th><strong><?php echo L('addressname')?>：</strong></th>
		<td><input name="address[address]" id="address" class="input-text" type="text" size="30" ></td>
	</tr>
	<tr>
		<th><strong><?php echo L('company')?>：</strong></th>
		<td><input name="address[company]" id="company" class="input-text" type="text" size="30" ></td>
	</tr>
	<tr>
		<th><strong><?php echo L('postcode')?>：</strong></th>
		<td><input name="address[postcode]" id="postcode" class="input-text" type="text" size="30" ></td>
	</tr>
	<tr>
		<th><strong><?php echo L('mobile')?>：</strong></th>
		<td><input name="address[mobile]" id="mobile" class="input-text" type="text" size="30" ></td>
	</tr>
	<tr>
		<th><strong><?php echo L('email')?>：</strong></th>
		<td><input name="address[email]" id="email" class="input-text" type="text" size="30" ></td>
	</tr>
	<tr>
		<th><strong><?php echo L('idcardtype')?>：</strong></th>
		<td><input name="address[idcardtype]" id="idcardtype_0"  type="radio" checked ><?php echo L('idcardcn')?>&nbsp;&nbsp;<input name="address[idcardtype]" id="idcardtype_1"  type="radio"  ><?php echo L('idcardncn')?></td>
	</tr>
	<tr>
  		<th><strong><?php echo L('setdefault')?>：</strong></th>
        <td>
		<input name="isdefault" id="isdefault" class="input-text" type="checkbox"  value="1">&nbsp;<font color=red><?php echo L('selecteddesc')?></font>
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
	$('#truename').formValidator({onshow:"<?php echo L('truename_cannot_empty')?>",onfocus:"<?php echo L('title_min_3_chars')?>",oncorrect:"<?php echo L('right')?>"}).inputValidator({min:1,onerror:"<?php echo L('truename_cannot_empty')?>"});
});
</script>