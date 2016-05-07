<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<div class="pad-10">
<form method="post" action="?m=shipline&c=admin_shipline&a=edit&aid=<?php echo $_GET['aid']?>" name="myform" id="myform">

<table class="table_form" width="100%" cellspacing="0">
<tbody>
	<tr>
		<th ><strong><?php echo L('shipline_title')?></strong></th>
		<td colspan="3"><input name="shipline[title]" id="title" class="input-text" type="text" size="60" value="<?php echo htmlspecialchars($an_info['title'])?>"><input id="support" type="checkbox" name="shipline[support]" value="1"  <?php if ($an_info['support']==1){echo ' checked';}?>/><label for="support"><?php echo L('support_the_cash_on_delivery')?></label></td>
	</tr>
	<tr>
		<th><strong><?php echo L('code')?>：</strong></th>
		<td><input name="shipline[code]" id="code" class="input-text" type="text" size="20" value="<?php echo ($an_info['code'])?>"></td>
		
		<th><strong><?php echo L('origin')?>：</strong></th>
		<td>
		      <select name="shipline[origin]" id="origin">
	<option value="0"><?php echo L('please_select')?></option>
	<?php 
		foreach($line as $rs){
	
$seld="";
if ($an_info['origin']==$rs['linkageid']){$seld= ' selected';}
echo '<option value="'.$rs[linkageid].'" '.$seld.'>'.$rs[name].'</option>';
				
	}?>
 
</select>
                      &nbsp;→&nbsp;
                      <select name="shipline[destination]" id="destination">
	<option value="0"><?php echo L('please_select')?></option>
	<?php 
		foreach($line as $rs){
		$sds="";
		if ($an_info['destination']==$rs['linkageid']){$sds= ' selected';}

	echo '<option value="'.$rs[linkageid].'" '.$sds.'>'.$rs[name].'</option>';
				
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

			$sele="";
			if ($an_info['weight']==$_r['aid']){$sele= ' selected';}

			echo '<option value="'.$_r[aid].'" '.$sele.'>'.$_r[title].'('.$_r[value].')</option>';
				
			}?>
			</select>
		</td>
		
		<th><strong><?php echo L('currency')?>：</strong></th>
		<td> <select name="shipline[currency]" id="currency">
			<option value="0"><?php echo L('please_select')?></option>
			<?php 
			foreach($currency as $row){
			
			$ses="";
			if ($an_info['currency']==$row['aid']){$ses= ' selected';}
			echo '<option value="'.$row[aid].'" '.$ses.'>'.$row[title].'('.$row[symbol].')</option>';
				
			}?>
		</select>
</td>
	</tr>
	<tr style="display:none;">
		<th><strong><?php echo L('firstweight')?>：</strong></th>
		<td><input name="shipline[firstweight]" id="firstweight" class="input-text" type="text" size="20" value="<?php echo ($an_info['firstweight'])?>"></td>
		
		<th><strong><?php echo L('addweight')?>：</strong></th>
		<td><input name="shipline[addweight]" id="addweight" class="input-text" type="text" size="20" value="<?php echo ($an_info['addweight'])?>"></td>
		
	</tr>
	<tr style="display:none;">
	<th><strong><?php echo L('firstprice')?>：</strong></th>
		<td><input name="shipline[price]" id="firstprice" class="input-text" type="text" size="20" value="<?php echo ($an_info['price'])?>"></td>
		

		<th><strong><?php echo L('addprice')?>：</strong></th>
		<td><input name="shipline[addprice]" id="addprice" class="input-text" type="text" size="20" value="<?php echo ($an_info['addprice'])?>"></td>
	</tr>
	<tr style="display:none;">
	
		
		<th><strong><?php echo L('heavyvolume')?>：</strong></th>
		<td><input name="shipline[heavyvolume]" id="heavyvolume" class="input-text" type="text" size="20" value="<?php echo ($an_info['heavyvolume'])?>"></td>
		<th><strong><?php echo L('volumeprice')?>：</strong></th>
		<td><input name="shipline[volumeprice]" id="volumeprice" class="input-text" type="text" size="20" value="<?php echo ($an_info['volumeprice'])?>"></td>
	</tr>
	<tr>
		<th><strong><?php echo L('minweight')?>：</strong></th>
		<td><input name="shipline[minweight]" id="minweight" class="input-text" type="text" size="10"  value="<?php echo ($an_info['minweight'])?>"><span class="gray"><?php echo L('tips_0001')?></span></td>
		
		<th><strong><?php echo L('minprice')?>：</strong></th>
		<td><input name="shipline[minprice]" id="minprice" class="input-text" type="text" size="20" value="<?php echo ($an_info['minprice'])?>"></td>
	</tr>
	<tr>
		<th><strong><?php echo L('maxweight')?>：</strong></th>
		<td><input name="shipline[maxweight]" id="maxweight" class="input-text" type="text" size="10" value="<?php echo ($an_info['maxweight'])?>"><span class="gray"><?php echo L('tips_0001')?></span></td>
		
		<th style="display:none;"><strong><?php echo L('freeweight')?>：</strong></th>
		<td style="display:none;"><input name="shipline[freeweight]" id="freeweight" class="input-text" type="text" size="20" value="<?php echo ($an_info['freeweight'])?>"></td>
	</tr>
	
	<tr>
		<th><strong><?php echo L('discount')?>：</strong></th>
		<td><input name="shipline[discount]" id="discount" class="input-text" type="text" size="10" value="<?php echo ($an_info['discount'])?>">（<?php echo L('tips_0002')?>）</td>
		
		<th><strong><?php echo L('addoption')?>：</strong></th>
		<td><?php foreach($lineaddoption as $k=>$v){
			$sedo="";
			if ($an_info['addoption']==$v['value']){$sedo= ' checked';}
			echo '<input name="shipline[addoption]" id="addoption_'.$k.'" class="input-text" type="radio" value="'.$v['value'].'" '.$sedo.'><label for="addoption_'.$k.'">'.$v['value'].'</label>';
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
		<td colspan="3"><input name="shipline[listorder]" id="listorder" class="input-text" type="text" size="10" value="<?php echo ($an_info['listorder'])?>"></td>
	</tr>
	<tr style="display:none;">
		<th ><strong><?php echo L('content')?>：</strong></th>
		<td colspan="3"><textarea name="shipline[content]" id="content"><?php echo ($an_info['content'])?></textarea><?php echo form::editor('content');?></td>
	</tr>
	<tr style="display:none;">
		<th ><strong><?php echo L('agreement')?>：</strong></th>
		<td colspan="3"><textarea name="shipline[agreement]" id="agreement"><?php echo ($an_info['agreement'])?></textarea><?php echo form::editor('agreement');?></td>
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
	$.formValidator.initConfig({formid:"myform",autotip:true,onerror:function(msg,obj){window.top.art.dialog({content:msg,lock:true,width:'220',height:'70'}, function(){this.close();$(obj).focus();})}});
	$('#title').formValidator({onshow:"<?php echo L('input_shipline_title')?>",onfocus:"<?php echo L('title_min_3_chars')?>",oncorrect:"<?php echo L('right')?>"}).inputValidator({min:1,onerror:"<?php echo L('title_cannot_empty')?>"}).ajaxValidator({type:"get",url:"",data:"m=shipline&c=admin_shipline&a=public_check_title&aid=<?php echo $_GET['aid']?>",datatype:"html",cached:false,async:'true',success : function(data) {
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
	}).defaultPassed();

	

});
</script>