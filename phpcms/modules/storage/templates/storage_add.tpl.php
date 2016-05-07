<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<div class="pad-10">
<form method="post" action="?m=storage&c=admin_storage&a=add" name="myform" id="myform">
<table class="table_form" width="100%" cellspacing="0">
<tbody>
 <tr>
           <th><strong><?php echo L('storagename')?>：</strong></th>
           <td>
               <select name="storage[area]" id="area">
				<option  value="0"><?php echo L('please_select')?></option>
				<?php 
					foreach($line as $rs){
				echo '<option value="'.$rs[linkageid].'">'.$rs[name].'</option>';
							
				}?>
			 
			</select>&nbsp;→&nbsp;
					   <input name="storage[storagename]" type="text" value="" id="storagename" class="input-text"  size="30" /><input id="isdefault" type="checkbox" name="storage[isdefault]" value="1"/><label for="isdefault"><?php echo L('available_style')?></label>
           </td>
       </tr>
	   <tr>
		<th><strong><?php echo L('storagedian')?>：</strong></th>
		<td><input name="storage[jihuodian]" id="jihuodian"  type="checkbox" value="1" checked><?php echo L('jihuodian')?>&nbsp;&nbsp;<input name="storage[zhongzhuandian]" id="zhongzhuandian"  type="checkbox" value="1" checked><?php echo L('zhongzhuandian')?>&nbsp;&nbsp;<input name="storage[paifadian]" id="paifadian"  type="checkbox" value="1" checked><?php echo L('paifadian')?>

		<input type="hidden" name="jihuodian_name" id="jihuodian_name" value="<?php echo L('jihuodian')?>"/>
		<input type="hidden" name="zhongzhuandian_name" id="zhongzhuandian_name" value="<?php echo L('zhongzhuandian')?>"/>
		<input type="hidden" name="paifadian_name" id="paifadian_name" value="<?php echo L('paifadian')?>"/>
		</td>
	</tr>
	<tr>
		<th><strong><?php echo L('storagecode')?>：</strong></th>
		<td><input name="storage[storagecode]" id="storagecode" class="input-text" type="text" size="20" ></td>
	</tr>
	<tr>
		<th width="80"><strong><?php echo L('storage_title')?></strong></th>
		<td><input name="storage[title]" id="title" class="input-text" type="text" size="40" ></td>
	</tr>
	
	<tr>
		<th><strong><?php echo L('currency')?>：</strong></th>
		<td><select name="storage[currency]" id="currency">
	<option value="0"><?php echo L('please_select')?></option>
	<?php 
			foreach($currency as $row){
			
			echo '<option value="'.$row[aid].'">'.$row[title].'('.$row[symbol].')</option>';
				
			}?>
 
</select>
</td>
	</tr>
	<tr>
		<th><strong><?php echo L('instoragefee')?>：</strong></th>
		<td><input name="storage[instoragefee]" id="instoragefee" class="input-text" type="text" size="10" ><span class="gray">/<?php echo L('tip_unit_weight')?></span>
		</td>
	</tr>
	<tr>
		<th><strong><?php echo L('savestoragefee')?>：</strong></th>
		<td><input name="storage[savestoragefee]" id="savestoragefee" class="input-text" type="text" size="10" ><span class="gray">/<?php echo L('tip_day')?>/<?php echo L('tip_unit_weight')?></span>
		</td>
	</tr>
	<tr>
		<th><strong><?php echo L('changestoragefee')?>：</strong></th>
		<td><input name="storage[changestoragefee]" id="changestoragefee" class="input-text" type="text" size="10" ><span class="gray">/<?php echo L('tip_unit_weight')?></span>
		</td>
	</tr>
	<tr>
		<th><strong><?php echo L('operatefee')?>：</strong></th>
		<td><input name="storage[operatefee]" id="operatefee" class="input-text" type="text" size="10" ><span class="gray">/<?php echo L('tip_piece')?></span>
		</td>
	</tr>
	<tr>
		<th><strong><?php echo L('address')?>：</strong></th>
		<td><input name="storage[address]" id="address" class="input-text" type="text" size="40" >
		</td>
	</tr>
	<tr>
		<th><strong><?php echo L('city')?>：</strong></th>
		<td><input name="storage[city]" id="city" class="input-text" type="text" size="15" >
		</td>
	</tr>
	<tr>
		<th><strong><?php echo L('province')?>：</strong></th>
		<td><input name="storage[province]" id="province" class="input-text" type="text" size="15" >
		</td>
	</tr>
	<tr>
		<th><strong><?php echo L('zipcode')?>：</strong></th>
		<td><input name="storage[zipcode]" id="zipcode" class="input-text" type="text" size="10" >
		</td>
	</tr>
	<tr>
		<th><strong><?php echo L('phone')?>：</strong></th>
		<td><input name="storage[phone]" id="phone" class="input-text" type="text" size="10" >
		</td>
	</tr>

	<tr>
		<th><strong><?php echo L('note')?>：</strong></th>
		<td><textarea name="storage[note]" id="note" class="input-text" cols="60" rows="5"></textarea>
		</td>
	</tr>
	  <tr>
		<th><strong><?php echo L('xunistorage')?>：</strong></th>
		<td><input name="storage[xunistorage]" id="xunistorage1"  type="radio" value="1" ><?php echo L('yesdefault')?>&nbsp;&nbsp;
		<input name="storage[xunistorage]" id="xunistorage0"  type="radio" value="0" ><?php echo L('nodefault')?>&nbsp;&nbsp;
		</td>
	</tr>
	 <tr>
		<th><strong><?php echo L('homeplace')?>：</strong></th>
		<td><input name="storage[homeplace]" id="homeplace1"  type="radio" value="1" ><?php echo L('yesdefault')?>&nbsp;&nbsp;
		<input name="storage[homeplace]" id="homeplace2"  type="radio" value="0" ><?php echo L('nodefault')?>&nbsp;&nbsp;
		</td>
	</tr>
   <tr>
		<th><strong><?php echo L('listorder')?>：</strong></th>
		<td><input name="storage[listorder]" id="listorder" class="input-text" type="text" size="6" value="0" >
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
	$('#title').formValidator({onshow:"<?php echo L('input_storage_title')?>",onfocus:"<?php echo L('title_min_3_chars')?>",oncorrect:"<?php echo L('right')?>"}).inputValidator({min:1,onerror:"<?php echo L('title_cannot_empty')?>"}).ajaxValidator({type:"get",url:"",data:"m=storage&c=admin_storage&a=public_check_title",datatype:"html",cached:false,async:'true',success : function(data) {
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
	onerror : "<?php echo L('storage_exist')?>",
	onwait : "<?php echo L('checking')?>"
	});
	
});
</script>