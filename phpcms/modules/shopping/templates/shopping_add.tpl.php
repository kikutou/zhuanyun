<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header','admin');
?>
<script type="text/javascript">
<!--
	$(function(){
	$.formValidator.initConfig({formid:"myform",autotip:true,onerror:function(msg,obj){window.top.art.dialog({content:msg,lock:true,width:'200',height:'50'}, function(){this.close();$(obj).focus();})}});
	$("#shopping_name").formValidator({onshow:"<?php echo L("input").L('shopping_name')?>",onfocus:"<?php echo L("input").L('shopping_name')?>"}).inputValidator({min:1,onerror:"<?php echo L("input").L('shopping_name')?>"});
 	$("#shopping_url").formValidator({onshow:"<?php echo L("input").L('url')?>",onfocus:"<?php echo L("input").L('url')?>"}).inputValidator({min:1,onerror:"<?php echo L("input").L('url')?>"}).regexValidator({regexp:"^http:\/\/[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&]*([^<>])*$",onerror:"<?php echo L('shopping_onerror')?>"})
	 
	})
//-->
</script>

<div class="pad_10">
<form action="?m=shopping&c=shopping&a=add" method="post" name="myform" id="myform">
<input name="shopping[shoppingtype]" type="hidden" value="1"  style="border:0"  class="radio_style">
<table cellpadding="2" cellspacing="1" class="table_form" width="100%">


	<tr>
		<th width="20%"><?php echo L('typeid')?>：</th>
		<td><select name="shopping[typeid]" id="">
		<option value="0"><?php echo L('default_type')?></option>
		<?php
		  $i=0;
		  foreach($types as $typeid=>$type){
		  $i++;
		echo '<option value="'.$type['typeid'].'">'.$type['name'].'</option>'; 
		
		}?>
		</select></td>
	</tr>
	

	
	<tr>
		<th width="100"><?php echo L('shopping_name')?>：</th>
		<td><input type="text" name="shopping[name]" id="shopping_name"
			size="30" class="input-text"></td>
	</tr>
	
	<tr>
		<th width="100"><?php echo L('url')?>：</th>
		<td><input type="text" name="shopping[url]" id="shopping_url"
			size="30" class="input-text"></td>
	</tr>
	
	<tr id="logoshopping">
		<th width="100"><?php echo L('logo')?>：</th>
		<td><?php echo form::images('shopping[logo]', 'logo', '', 'shopping')?></td>
	</tr>
	
	<tr>
		<th width="100"><?php echo L('username')?>：</th>
		<td><input type="text" name="shopping[username]" id="shopping_username"
			size="30" class="input-text"></td>
	</tr>

 
	<tr>
		<th><?php echo L('web_description')?>：</th>
		<td><textarea name="shopping[introduce]" id="introduce" cols="50"
			rows="6"></textarea></td>
	</tr>

 
	<tr>
		<th><?php echo L('elite')?>：</th>
		<td><input name="shopping[elite]" type="radio" value="1" >&nbsp;<?php echo L('yes')?>&nbsp;&nbsp;<input
			name="shopping[elite]" type="radio" value="0" checked>&nbsp;<?php echo L('no')?></td>
	</tr>
	 
	<tr>
		<th><?php echo L('passed')?>：</th>
		<td><input name="shopping[passed]" type="radio" value="1" checked>&nbsp;<?php echo L('yes')?>&nbsp;&nbsp;<input
			name="shopping[passed]" type="radio" value="0">&nbsp;<?php echo L('no')?></td>
	</tr>

<tr>
		<th></th>
		<td><input type="hidden" name="forward" value="?m=shopping&c=shopping&a=add"> <input
		type="submit" name="dosubmit" id="dosubmit" class="dialog"
		value=" <?php echo L('submit')?> "></td>
	</tr>

</table>
</form>
</div>
</body>
</html> 