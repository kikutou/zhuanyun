<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<div class="pad-10">
<form method="post" action="?m=enum&c=admin_enum&a=additem" name="myform" id="myform">
<table class="table_form" width="100%" cellspacing="0">
<tbody>
	<?php
	if($_GET['gid']==48 || $_GET['gid']==123 ){

		$areadb = pc_base::load_model('linkage_model');
			
		$enabled_addrlinks = $areadb->select("parentid=0 and keyid=0","*",1000);

	?>
	<tr>
		<th><strong>国家</strong></th>
		<td>
		<select name="enum[country]" id="country"  >
		<?php
		foreach($enabled_addrlinks as $v){
			echo '<option value="'.$v['name'].'">'.$v['name'].'</option>';
		}
		?>
		</select>
		</td>
	</tr>
	<?php
	}
	?>
	<tr>
		<th width="80"><strong><?php echo L('title')?></strong></th>
		<td>
		
		<input name="enum[title]" id="title" class="input-text" type="text" size="20" >
	
		</td>
	</tr>

	<tr>
		<th width="80"><strong><?php echo L('value')?></strong></th>
		<td><input name="enum[value]" id="value" class="input-text" type="text" size="20" ></td>
	</tr>

	

	<tr>
		<th width="80"><strong><?php if($_GET['gid']==48 || $_GET['gid']==152){echo '费用类型';}else{ echo L('url');}?></strong></th>
		<td>
		
		<?php
		
		if($_GET['gid']==48 || $_GET['gid']==152 || $_GET['gid']==123){
			$datasItem = getcache('get__enum_data_60', 'commons');
			$remark="";
			foreach($datasItem as $item){
				if($remark==""){$remark=$item['value'];}else{$remark.="|".$item['value'];}
			}

			echo '<input name="enum[remark]" id="remark" class="input-text" type="text" size="20" value="'.$remark.'" readonly>';
		
		}else{
			echo '<input name="enum[remark]" id="remark" class="input-text" type="text" size="20" >'.L('url_tip');
		}
		
		?>
		</td>
	</tr>

	<tr>
		<th width="80"><strong><?php echo L('listorder')?></strong></th>
		<td><input name="enum[listorder]" id="listorder" class="input-text" type="text" size="10" value="0"></td>
	</tr>

	</tbody>
</table>
<input name="enum[groupid]" id="groupid"  type="hidden" value="<?php echo $gid;?>" >
<input type="submit" name="dosubmit" id="dosubmit" value=" <?php echo L('ok')?> " class="dialog">&nbsp;<input type="reset" class="dialog" value=" <?php echo L('clear')?> ">
</form>
</div>
</body>
</html>
<script type="text/javascript">


$(document).ready(function(){
	$.formValidator.initConfig({formid:"myform",autotip:true,onerror:function(msg,obj){window.top.art.dialog({content:msg,lock:true,width:'220',height:'70'}, function(){this.close();$(obj).focus();})}});
	$('#title').formValidator({onshow:"<?php echo L('input_enum_itemtitle')?>",onfocus:"<?php echo L('itemtitle_min_3_chars')?>",oncorrect:"<?php echo L('right')?>"}).inputValidator({min:1,onerror:"<?php echo L('itemtitle_cannot_empty')?>"}).ajaxValidator({type:"get",url:"",data:"m=enum&c=admin_enum&a=public_check_itemtitle&gid=<?php echo $gid;?>",datatype:"html",cached:false,async:'true',success : function(data) {
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
	onerror : "<?php echo L('enumitem_exist')?>",
	onwait : "<?php echo L('checking')?>"
	});


	
	
});
</script>