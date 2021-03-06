<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<div class="pad-10">
<form method="post" action="?m=currency&c=admin_currency&a=add" name="myform" id="myform">
<table class="table_form" width="100%" cellspacing="0">
<tbody>
	<tr>
		<th width="80"><strong><?php echo L('currency_title')?></strong></th>
		<td><input name="currency[title]" id="title" class="input-text" type="text" size="40" ></td>
	</tr>
	<tr>
		<th><strong><?php echo L('code')?>：</strong></th>
		<td><input name="currency[code]" id="code" class="input-text" type="text" size="30" ></td>
	</tr>
	<tr>
		<th><strong><?php echo L('symbol')?>：</strong></th>
		<td><input name="currency[symbol]" id="symbol" class="input-text" type="text" size="30" ></td>
	</tr>
	<tr>
		<th><strong><?php echo L('exchangerate')?>：</strong></th>
		<td><input name="currency[exchangerate]" id="exchangerate" class="input-text" type="text" size="30" ></td>
	</tr>
	<tr>
  		<th><strong><?php echo L('available_style')?>：</strong></th>
        <td>
		<input name="currency[isdefault]" id="isdefault" class="input-text" type="checkbox"  value="1">
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
	$('#title').formValidator({onshow:"<?php echo L('input_currency_title')?>",onfocus:"<?php echo L('title_min_3_chars')?>",oncorrect:"<?php echo L('right')?>"}).inputValidator({min:1,onerror:"<?php echo L('title_cannot_empty')?>"}).ajaxValidator({type:"get",url:"",data:"m=currency&c=admin_currency&a=public_check_title",datatype:"html",cached:false,async:'true',success : function(data) {
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
	onerror : "<?php echo L('currency_exist')?>",
	onwait : "<?php echo L('checking')?>"
	});


	
	
});
</script>