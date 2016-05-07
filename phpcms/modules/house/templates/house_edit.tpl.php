<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<div class="pad-10">
<form method="post" action="?m=house&c=admin_house&a=edit&aid=<?php echo $_GET['aid']?>" name="myform" id="myform">
<table class="table_form" width="100%">
<tbody>
	<tr>
		<th width="80"><?php echo L('house_title')?></th>
		<td><input name="house[title]" id="title" value="<?php echo htmlspecialchars($an_info['title'])?>" class="input-text" type="text" size="20" ></td>
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
	$('#title').formValidator({onshow:"<?php echo L('input_house_title')?>",onfocus:"<?php echo L('title_min_3_chars')?>",oncorrect:"<?php echo L('right')?>"}).inputValidator({min:1,onerror:"<?php echo L('title_cannot_empty')?>"}).ajaxValidator({type:"get",url:"",data:"m=house&c=admin_house&a=public_check_title&aid=<?php echo $_GET['aid']?>",datatype:"html",cached:false,async:'true',success : function(data) {
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
	onerror : "<?php echo L('house_exist')?>",
	onwait : "<?php echo L('checking')?>"
	}).defaultPassed();



});
</script>