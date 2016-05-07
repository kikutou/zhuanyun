<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<div class="pad-10">
<form method="post" action="?m=yuyue&c=admin_yuyue&a=add" name="myform" id="myform">
<table class="table_form" width="100%" cellspacing="0">
<tbody>
	<tr>
		<th><strong><?php echo L('userid')?>：</strong></th>
		<td><input name="yuyue[userid]" id="userid" class="input-text" type="text" size="20" value="0" ></td>
	</tr>
	<tr>
		<th ><strong><?php echo L('shippingtype')?>：</strong></th>
		<td>
		<select name="yuyue[shippingtype]"  id="shippingtype">
			<option value="0"><?php echo L('please_select')?></option>
			<?php foreach($shippingtypes as $row){
echo '<option value="'.$row['aid'].'">'.$row['storagename'].'</option>';
				}
			?>
		</select>
		</td>
	</tr>
	<tr>
		<th><strong><?php echo L('yuyue_address')?>：</strong></th>
		<td><input name="yuyue[address]" id="address" class="input-text" type="text" size="10" value="0"></td>
	</tr>
	<tr>
		<th><strong><?php echo L('yuyuetime')?>：</strong></th>
		<td><?php echo form::date('yuyue[yuyuetime]','','')?></td>
	</tr>
	<tr>
		<th><strong><?php echo L('yuyuenumber')?>：</strong></th>
		<td><input name="yuyue[number]" id="number" class="input-text" type="text" size="10" value="0"></td>
	</tr>
	<tr>
		<th><strong><?php echo L('addressid')?>：</strong></th>
		<td><input name="yuyue[addressid]" id="addressid"  type="radio"  value="0" checked><?php echo L('available_style')?></td>
	</tr>
	<tr>
		<th><strong><?php echo L('lianxiren')?>：</strong></th>
		<td><input name="yuyue[lianxiren]" id="lianxiren" class="input-text" type="text" size="20" ></td>
	</tr>
	<tr>
		<th><strong><?php echo L('mobile')?>：</strong></th>
		<td><input name="yuyue[mobile]" id="mobile" class="input-text" type="text" size="20" ></td>
	</tr>
	<tr>
		<th><strong><?php echo L('email')?>：</strong></th>
		<td><input name="yuyue[email]" id="email" class="input-text" type="text" size="20" ></td>
	</tr>
	<tr>
		<th><strong><?php echo L('remark')?>：</strong></th>
		<td><textarea name="yuyue[remark]" id="remark" cols="50" rows=4 ></textarea></td>
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
	$('#yuyuetime').formValidator({onshow:"<?php echo L('yuyuetime_cannot_empty')?>",onfocus:"<?php echo L('yuyuetime_cannot_empty')?>",oncorrect:"<?php echo L('right')?>"}).inputValidator({min:1,onerror:"<?php echo L('yuyuetime_cannot_empty')?>"});
});
</script>