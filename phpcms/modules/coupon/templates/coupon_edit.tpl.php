<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<div class="pad-10">
<form method="post" action="?m=coupon&c=admin_coupon&a=edit&aid=<?php echo $_GET['aid']?>" name="myform" id="myform">
<table class="table_form" width="100%">
	<tbody>
	<tr>
		<th ><strong><?php echo L('coupon_title')?></strong></th>
		<td><input name="coupon[title]" id="title" class="input-text" type="text" size="40" value="<?php echo htmlspecialchars($an_info['title'])?>"></td>
	</tr>
	<tr>
		<th><strong><?php echo L('remark')?>：</strong></th>
		<td><textarea name="coupon[remark]" id="remark" class="input-text"  cols="60" rows="5"><?php echo ($an_info['remark'])?></textarea></td>
	</tr>
	<tr>
		<th><strong><?php echo L('price')?>：</strong></th>
		<td><input name="coupon[price]" id="price" class="input-text" type="text" size="10" value="<?php echo ($an_info['price'])?>"></td>
	</tr>
	<tr>
		<th><strong><?php echo L('backpoints')?>：</strong></th>
		<td><input name="coupon[backpoints]" id="backpoints" class="input-text" type="text" size="10" value="<?php echo ($an_info['backpoints'])?>"></td>
	</tr>
	<tr>
  		<th><strong><?php echo L('okdays')?>：</strong></th>
        <td>
		<input name="coupon[okdays]" id="okdays" class="input-text" type="text"  size="10" value="<?php echo ($an_info['okdays'])?>">
		</td>
	</tr>
	<tr>
  		<th><strong><?php echo L('minamount')?>：</strong></th>
        <td>
		<input name="coupon[minamount]" id="minamount" class="input-text" type="text"  size="10" value="<?php echo ($an_info['minamount'])?>">（为0表示不限）
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
	$('#title').formValidator({onshow:"<?php echo L('input_coupon_title')?>",onfocus:"<?php echo L('title_min_3_chars')?>",oncorrect:"<?php echo L('right')?>"}).inputValidator({min:1,onerror:"<?php echo L('title_cannot_empty')?>"}).ajaxValidator({type:"get",url:"",data:"m=coupon&c=admin_coupon&a=public_check_title&aid=<?php echo $_GET['aid']?>",datatype:"html",cached:false,async:'true',success : function(data) {
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
	onerror : "<?php echo L('coupon_exist')?>",
	onwait : "<?php echo L('checking')?>"
	}).defaultPassed();



});
</script>