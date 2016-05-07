<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<div class="pad-10">
<form method="post" action="?m=service&c=admin_service&a=edit&aid=<?php echo $_GET['aid']?>" name="myform" id="myform">
<table class="table_form" width="100%">
<tbody>
	<tr>
		<th><?php echo L('service_type')?>：</th>
		<td><select name="service[type]" id="service_type">
		<?
		foreach($this->valuecategory() as $v){
			$sel="";
			if ($an_info['type']==$v['value']){$sel=" selected";}
			echo '<option value="'.$v['value'].'" '.$sel.'>'.$v['title'].'</option>';
		}
		?>
		</select></td>
	</tr>
	<tr>
		<th width="80"><?php echo L('service_title')?></th>
		<td><input name="service[title]" id="title" value="<?php echo htmlspecialchars($an_info['title'])?>" class="input-text" type="text" size="40" ></td>
	</tr>
	<tr>
		<th width="80"><strong><?php echo L('service_price')?>：</strong></th>
		<td><select name="service[currencyid]" id="currencyid">
		<?php 
			
			foreach($this->getcurrency() as $row){
			$sell="";
			 if ($an_info['currencyid']==$row[aid]){$sell= ' selected';}

			echo '<option value="'.$row[aid].'" '.$sell.'>'.$row[title].'('.$row[symbol].')</option>';
				
			}?>
		</select><input name="service[price]" id="price" class="input-text" type="text" size="10" value="<?php echo ($an_info['price'])?>">&nbsp;/&nbsp;<select name="service[unit]" id="unit">
		<?php 
			foreach($this->getunits() as $rs){
			$sels="";
			if ($an_info['unit']==$rs[aid]){$sels= ' selected';}
			echo '<option value="'.$rs[aid].'" '.$sels.'>'.$rs[title].'</option>';
				
			}?>
		</select></td>
	</tr>
	<tr>
		<th><strong><?php echo L('service_remark')?>：</strong></th>
		<td><textarea name="service[remark]" rows="2" cols="20" id="remark"  class="input-text" rows="0" cols="0" style="height:65px;width:70%"><?php echo ($an_info['remark'])?></textarea></td>
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
	$('#title').formValidator({onshow:"<?php echo L('input_service_title')?>",onfocus:"<?php echo L('title_min_3_chars')?>",oncorrect:"<?php echo L('right')?>"}).inputValidator({min:1,onerror:"<?php echo L('title_cannot_empty')?>"}).ajaxValidator({type:"get",url:"",data:"m=service&c=admin_service&a=public_check_title&aid=<?php echo $_GET['aid']?>",datatype:"html",cached:false,async:'true',success : function(data) {
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
	}).defaultPassed();
	$('#starttime').formValidator({onshow:"<?php echo L('select_stardate')?>",onfocus:"<?php echo L('select_stardate')?>",oncorrect:"<?php echo L('right_all')?>"}).defaultPassed();
	$('#endtime').formValidator({onshow:"<?php echo L('select_downdate')?>",onfocus:"<?php echo L('select_downdate')?>",oncorrect:"<?php echo L('right_all')?>"}).defaultPassed();
	$("#content").formValidator({autotip:true,onshow:"",onfocus:"<?php echo L('servicements_cannot_be_empty')?>"}).functionValidator({
	    fun:function(val,elem){
	    //获取编辑器中的内容
		var oEditor = CKEDITOR.instances.content;
		var data = oEditor.getData();
        if(data==''){
		    return "<?php echo L('servicements_cannot_be_empty')?>"
	    } else {
			return true;
		}
	}
	}).defaultPassed();
	$('#style').formValidator({onshow:"<?php echo L('select_style')?>",onfocus:"<?php echo L('select_style')?>",oncorrect:"<?php echo L('right_all')?>"}).inputValidator({min:1,onerror:"<?php echo L('select_style')?>"}).defaultPassed();
});
</script>