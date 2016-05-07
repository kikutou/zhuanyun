<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<div class="pad-10">
<form method="post" action="?m=waybill&c=admin_waybill&a=waybill_union_bill&ids=<?php echo $ids;?>" name="myform" id="myform"  >
<table class="table_form" width="100%" cellspacing="0">
<tbody>
	<tr>
		<th><strong><?php echo L('huodan_huodanno')?>：</strong></th>
		<td><input name="data[huodanno]" id="huodanno" class="input-text" type="text" size="30" value="" ></td>
	</tr>
	

	<tr>
		<th><strong><?php echo L('fahuo_placename')?>：</strong></th>
		<td>
		<select name="data[placeid]" id="placeid"  onchange="document.getElementById('placename').value=this.options[this.selectedIndex].text;"  >
		<option value="0"><?php echo L('please_select');?></option>
		<?php
		foreach($this->getallstorage() as $v){
			echo '<option value="'.$v['aid'].'">'.$v['title'].'</option>';
		}
		?>
		</select>
		<input name="data[placename]" id="placename"  type="hidden" >
		</td>
	</tr>
	
	<tr>
		<th><strong><?php echo L('finish_username')?>：</strong></th>
		<td><input name="data[username]" id="username" class="input-text" type="text" size="30" value="<?php echo $this->username;?>"></td>
	</tr>
	<tr>
		<th><strong><?php echo L('finish_time')?>：</strong></th>
		<td><input name="data[addtime]" id="addtime" class="input-text" type="text" size="30" value="<?php echo date('Y-m-d H:i:s',time());?>" ></td>
	</tr>
	<tr>
  		<th><strong><?php echo L('remark')?>：</strong></th>
        <td>
		<textarea name="handle[remark]" id="remark" cols="50" rows=4></textarea>
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
	
	
	$('#placeid').formValidator({onshow:"<?php echo L('fahuo_placename_cannot_be_empty')?>",onfocus:"<?php echo L('fahuo_placename_cannot_be_empty')?>",oncorrect:"<?php echo L('right')?>"}).inputValidator({min:1,onerror:"<?php echo L('fahuo_placename_cannot_be_empty')?>"});

});
</script>