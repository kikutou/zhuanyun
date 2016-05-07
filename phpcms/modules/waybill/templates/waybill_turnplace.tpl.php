<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<div class="pad-10">
<form method="post" action="?m=waybill&c=admin_waybill&a=waybill_turnplace" name="myform" id="myform"  >

<table class="table_form" width="100%" cellspacing="0">
<tbody>
	<tr>
		<th><strong><?php echo L('invoice_number');?>：</strong></th>
		<td><textarea name="data[shipno]" id="shipno" class="input-text" type="text" cols="40" rows="10" readonly><?php echo $sno;?></textarea></td>
	</tr>
	
	<tr>
		<th><strong><?php echo L('fahuo_placename')?>：</strong></th>
		<td>
		<select name="data[placeid]" id="placeid"  onchange="document.getElementById('placename').value=this.options[this.selectedIndex].text;"  >
		
		<?php
		foreach($this->getallstorage() as $v){
			$sel="";
			if( $info['storageid']==$v['aid']){$sel= 'selected';}

			echo '<option value="'.$v['aid'].'" '.$sel.'>'.$v['title'].'</option>';
		}
		?>
		</select>
		<input name="data[placename]" id="placename"  type="hidden"  value="<?php echo $info['storagename'];?>">
		<script>
		document.getElementById('placename').value=document.getElementById('placeid').options[document.getElementById('placeid').selectedIndex].text;
		</script>
		</td>
	</tr>
	<tr>
		<th><strong><?php echo L('point_of_destination')?>：</strong></th>
		<td>
		<select name="data[position]" id="position"    >
		
		<option value="zhongzhuan" <?php if( $info['position']=='zhongzhuan'){echo 'selected';}?>><?php echo L('transit_point')?></option>
		<option value="paifa" <?php if( $info['position']=='paifa'){echo 'selected';}?>><?php echo L('distribution_point')?></option>
		</select>
		
		</td>
	</tr>

	<tr>
  		<th><strong><?php echo L('remark')?>：</strong></th>
        <td>
		<textarea name="handle[remark]" id="remark" cols="40" rows=4><?php echo $info['remark'];?></textarea>
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

	$('#shipno').formValidator({onshow:"<?php echo L('invoice_number').L('cannot_empty');?>!",onfocus:"<?php echo L('invoice_number').L('cannot_empty');?>!",oncorrect:"<?php echo L('right')?>"}).inputValidator({min:1,onerror:"<?php echo L('invoice_number').L('cannot_empty');?>!"});
	
	

});
</script>