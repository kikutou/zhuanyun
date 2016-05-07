<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<div class="pad-10">
<form method="post" action="?m=waybill&c=admin_waybill&a=waybill_fahuo_pifa&oid=<?php echo $oid;?>" name="myform" id="myform"  >
<table class="table_form" width="100%" cellspacing="0">
<tbody>

	<tr>
		<th><strong><?php echo L('fahuo_shipname')?>：</strong></th>
		<td><input name="data[shipname]" id="shipname" class="input-text" type="text" size="30" ></td>
	</tr>
	<tr>
		<th><strong><?php echo L('fahuo_shipno')?>：</strong></th>
		<td><input name="data[shipno]" id="shipno" class="input-text" type="text" size="30" value="<?php echo $shipno;?>" ></td>
	</tr>
	<tr>
		<th><strong><?php echo L('pay_weight')?>：</strong></th>
		<td><input name="data[weight]" id="weight" class="input-text" type="text" size="30" value="<?php echo $info['weight'];?>"></td>
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
		<th><strong><?php echo L('point_of_destination');?>：</strong></th>
		<td>
		<select name="data[position]" id="position"    >
		
		<option value="zhongzhuan" ><?php echo L('transit_point');?></option>
		<option value="paifa" ><?php echo L('distribution_point');?></option>
		</select>
		
		</td>
	</tr>
	<tr>
  		<th><strong><?php echo L('remark')?>：</strong></th>
        <td>
		<textarea name="handle[remark]" id="remark" cols="40" rows=4></textarea>
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
	$('#shipname').formValidator({onshow:"<?php echo L('fahuo_shipname_cannot_be_empty')?>",onfocus:"<?php echo L('fahuo_shipname_cannot_be_empty')?>",oncorrect:"<?php echo L('right')?>"}).inputValidator({min:1,onerror:"<?php echo L('fahuo_shipname_cannot_be_empty')?>"});
	
	$('#shipno').formValidator({onshow:"<?php echo L('fahuo_shipno_cannot_be_empty')?>",onfocus:"<?php echo L('fahuo_shipno_cannot_be_empty')?>",oncorrect:"<?php echo L('right')?>"}).inputValidator({min:1,onerror:"<?php echo L('fahuo_shipno_cannot_be_empty')?>"});
	
	$('#placeid').formValidator({onshow:"<?php echo L('fahuo_placename_cannot_be_empty')?>",onfocus:"<?php echo L('fahuo_placename_cannot_be_empty')?>",oncorrect:"<?php echo L('right')?>"}).inputValidator({min:1,onerror:"<?php echo L('fahuo_placename_cannot_be_empty')?>"});
	

});
</script>