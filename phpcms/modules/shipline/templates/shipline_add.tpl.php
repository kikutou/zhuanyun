<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>

<div class="pad-10">
<form method="post" action="?m=shipline&c=admin_shipline&a=add" name="myform" id="myform">
<table class="table_form" width="100%" cellspacing="0">
<tbody>
	<tr>
		<th ><strong><?php echo L('shipline_title')?></strong></th>
		<td colspan="3"><input name="shipline[title]" id="title" class="input-text" type="text" size="60" ><input id="support" type="checkbox" name="shipline[support]" value="1"/><label for="support"><?php echo L('support_the_cash_on_delivery')?></label></td>
	</tr>
	<tr>
		<th><strong><?php echo L('code')?>：</strong></th>
		<td><input name="shipline[code]" id="code" class="input-text" type="text" size="20" ></td>
		
		<th><strong><?php echo L('origin')?>：</strong></th>
		<td>
		      <select name="shipline[origin]" id="origin">
	<option value="0"><?php echo L('please_select')?></option>
	<?php 
		foreach($line as $rs){
	echo '<option value="'.$rs[linkageid].'">'.$rs[name].'</option>';
				
	}?>
 
</select>
                      &nbsp;→&nbsp;
                      <select name="shipline[destination]" id="destination">
	<option value="0"><?php echo L('please_select')?></option>
	<?php 
		foreach($line as $rs){
	echo '<option value="'.$rs[linkageid].'">'.$rs[name].'</option>';
				
	}?>
 
</select>
		</td>
	</tr>
	<tr>
		<th><strong><?php echo L('weight')?>：</strong></th>
		<td><select name="shipline[weight]" id="weight">
				<option value="0"><?php echo L('please_select')?></option>
				<?php 
			foreach($weight as $_r){
			echo '<option value="'.$_r[aid].'">'.$_r[title].'('.$_r[value].')</option>';
				
			}?>
			</select>
		</td>
		
		<th><strong><?php echo L('currency')?>：</strong></th>
		<td> <select name="shipline[currency]" id="currency">
			<option value="0"><?php echo L('please_select')?></option>
			<?php 
			foreach($currency as $row){
			echo '<option value="'.$row[aid].'">'.$row[title].'('.$row[symbol].')</option>';
				
			}?>
		</select>
</td>
	</tr>
	<tr>
		<th><strong><?php echo L('firstweight')?>：</strong></th>
		<td><input name="shipline[firstweight]" id="firstweight" class="input-text" type="text" size="20" ></td>
		
		
		<th><strong><?php echo L('addweight')?>：</strong></th>
		<td><input name="shipline[addweight]" id="addweight" class="input-text" type="text" size="20" ></td>
	</tr>
	<tr>
		
	<th><strong><?php echo L('firstprice')?>：</strong></th>
		<td><input name="shipline[price]" id="firstprice" class="input-text" type="text" size="20" ></td>
		
		
		<th><strong><?php echo L('addprice')?>：</strong></th>
		<td><input name="shipline[addprice]" id="addprice" class="input-text" type="text" size="20" ></td>
	</tr>
	<tr>
		
		<th><strong><?php echo L('heavyvolume')?>：</strong></th>
		<td><input name="shipline[heavyvolume]" id="heavyvolume" class="input-text" type="text" size="20" ></td>
	
		
		<th><strong><?php echo L('volumeprice')?>：</strong></th>
		<td><input name="shipline[volumeprice]" id="volumeprice" class="input-text" type="text" size="20" value="0"></td>
	</tr>
	<tr>
		<th><strong><?php echo L('minweight')?>：</strong></th>
		<td><input name="shipline[minweight]" id="minweight" class="input-text" type="text" size="10" value="0" ><span class="gray"><?php echo L('tips_0001')?></span></td>
		
		<th><strong><?php echo L('minprice')?>：</strong></th>
		<td><input name="shipline[minprice]" id="minprice" class="input-text" type="text" size="20" value="0"></td>
	</tr>
	<tr>
		<th><strong><?php echo L('maxweight')?>：</strong></th>
		<td><input name="shipline[maxweight]" id="maxweight" class="input-text" type="text" size="10" value="0"><span class="gray"><?php echo L('tips_0001')?></span></td>
		
		<th><strong><?php echo L('freeweight')?>：</strong></th>
		<td><input name="shipline[freeweight]" id="freeweight" class="input-text" type="text" size="20" value="0"></td>
	</tr>
	
	<tr>
		<th><strong><?php echo L('discount')?>：</strong></th>
		<td><input name="shipline[discount]" id="discount" class="input-text" type="text" size="10" >（<?php echo L('tips_0002')?>）</td>
		
		<th><strong><?php echo L('addoption')?>：</strong></th>
		<td>
		<?php foreach($lineaddoption as $k=>$v){

	$chks="";
	if ($k==0){$chks= ' checked';}

echo '<input name="shipline[addoption]" id="addoption_'.$k.'" class="input-text" type="radio" value="'.$v['value'].'" '.$chks.'><label for="addoption_'.$k.'">'.$v['value'].'</label>';
		}
		?></td>
	</tr>

	<tr>
		<th><strong><?php echo L('mustagree')?>：</strong></th>
		<td><input name="shipline[mustagree]" id="mustagree" type="checkbox"   /><label for="mustagree"><?php echo L('tips_0003')?></label></td>
		
		<th><strong><?php echo L('usepoints')?>：</strong></th>
		<td><input name="shipline[usepoints]" id="usepoints" type="checkbox"   /><label for="usepoints"><?php echo L('tips_0004')?></label></td>
	</tr>
	
	<tr>
		<th ><strong><?php echo L('listorder')?>：</strong></th>
		<td colspan="3"><input name="shipline[listorder]" id="listorder" class="input-text" type="text" size="10" value="0"></td>
	</tr>
	<tr>
		<th ><strong><?php echo L('content')?>：</strong></th>
		<td colspan="3"><textarea name="shipline[content]" id="content"></textarea><?php echo form::editor('content');?></td>
	</tr>
	<tr>
		<th ><strong><?php echo L('agreement')?>：</strong></th>
		<td colspan="3"><textarea name="shipline[agreement]" id="agreement"></textarea><?php echo form::editor('agreement');?></td>
	</tr>
	

	</tbody>
</table>
<p align=center>
<input type="submit" name="dosubmit" id="dosubmit" value=" <?php echo L('ok')?> " class="aui_state_highlight">&nbsp;<input type="reset" class="dialog" value=" <?php echo L('clear')?> ">

</p>
</form>
</div>
</body>
</html>
<script type="text/javascript">


$(document).ready(function(){
	$.formValidator.initConfig({formid:"myform",autotip:true,onerror:function(msg,obj){}});
	$('#title').formValidator({onshow:"<?php echo L('input_shipline_title')?>",onfocus:"<?php echo L('title_min_3_chars')?>",oncorrect:"<?php echo L('right')?>"}).inputValidator({min:1,onerror:"<?php echo L('title_cannot_empty')?>"}).ajaxValidator({type:"get",url:"",data:"m=shipline&c=admin_shipline&a=public_check_title",datatype:"html",cached:false,async:'true',success : function(data) {
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
	onerror : "<?php echo L('shipline_exist')?>",
	onwait : "<?php echo L('checking')?>"
	});
});
</script>