<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<div class="pad-10">
<form method="post" action="?m=service&c=admin_service&a=add" name="myform" id="myform">
<table class="table_form" width="100%" cellspacing="0">
<tbody>
	<tr>
		<th><strong><?php echo L('service_type')?>：</strong></th>
		<td><select name="service[type]" id="service_type">
		<?
		foreach($this->valuecategory() as $v){
			echo '<option value="'.$v['value'].'">'.$v['title'].'</option>';
		}
		?>	
		</select>
	 </td>
	</tr>
	<tr>
		<th width="80"><strong><?php echo L('service_title')?></strong></th>
		<td><input name="service[title]" id="title" class="input-text" type="text" size="40" ></td>
	</tr>
	<tr>
		<th width="80"><strong><?php echo L('service_price')?>：</strong></th>
		<td><select name="service[currencyid]" id="currencyid">
		<?php 
			$_row = $this->getdefaultcurrency();
			foreach($this->getcurrency() as $row){
			$sels="";
			if ($_row['aid']==$row[aid]){$sels= ' selected';}

			echo '<option value="'.$row[aid].'" '.$sels.'>'.$row[title].'('.$row[symbol].')</option>';
				
			}?>
		</select><input name="service[price]" id="price" class="input-text" type="text" size="10" value="0.00">&nbsp;/&nbsp;<select name="service[unit]" id="unit">
		<?php 
			foreach($this->getunits() as $rs){
			echo '<option value="'.$rs[aid].'">'.$rs[title].'</option>';
				
			}?>
		</select></td>
	</tr>
	
	<tr>
		<th><strong><?php echo L('service_remark')?>：</strong></th>
		<td><textarea name="service[remark]" rows="2" cols="20" id="remark"  class="input-text" rows="0" cols="0" style="height:65px;width:70%"></textarea></td>
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
	$('#title').formValidator({onshow:"<?php echo L('input_service_title')?>",onfocus:"<?php echo L('title_min_3_chars')?>",oncorrect:"<?php echo L('right')?>"}).inputValidator({min:1,onerror:"<?php echo L('title_cannot_empty')?>"}).ajaxValidator({type:"get",url:"",data:"m=service&c=admin_service&a=public_check_title",datatype:"html",cached:false,async:'true',success : function(data) {
        if( data == "1" )
		{
            return true;
		}
        else
		{
            return false;
		}
	},
	error: function(){alert("<?php echo L('server_no_data')?>");},
	onerror : "<?php echo L('service_exist')?>",
	onwait : "<?php echo L('checking')?>"
	});
});
</script>