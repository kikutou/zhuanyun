<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<div class="pad-10">
<form method="post" action="/index.php?m=waybill&c=admin_waybill&a=kabangoto_paifa" name="myform" id="myform">
<table class="table_form" width="100%" cellspacing="0">
<tbody>

	<tr>
		<th ><strong><?php echo L('kabanno')?>：</strong></th>
		<td>
		<textarea name="data[waybillid]" id="waybillid" cols="40" rows=12 ><?php echo $sno;?></textarea>
		</td>
	</tr>
	<tr>
		<th><strong><?php echo L('fahuo_placename')?>：</strong></th>
		<td>
		<select name="data[placeid]" id="placeid"  onchange="document.getElementById('placename').value=this.options[this.selectedIndex].text;"  >
		<option value="0"><?php echo L('please_select');?></option>
		<?php
		$getallstorage = $this->getallstorage();
		foreach($getallstorage as $v){
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
		<th><strong><?php echo L('operator')?>：</strong></th>
		<td><?php echo $this->username;?></td>
	</tr>

	<tr>
  		<th><strong><?php echo L('remark')?>：</strong></th>
        <td>
		<textarea name="data[remark]" id="remark" cols="40" rows=4></textarea>
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
	$('#placeid').formValidator({onshow:"<?php echo L('fahuo_placename').L('cannot_empty');?>",onfocus:"<?php echo  L('fahuo_placename').L('cannot_empty')?>",oncorrect:"<?php echo L('right')?>"}).inputValidator({min:1,onerror:"<?php echo  L('fahuo_placename').L('cannot_empty')?>"});
});

</script>