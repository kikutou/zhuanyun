<?php 
	defined('IN_ADMIN') or exit('No permission resources.');
	include $this->admin_tpl('header', 'admin');
?>
<script type="text/javascript">
<!--
$(function(){
	$.formValidator.initConfig({autotip:true,formid:"myform",onerror:function(msg){}});
	$("#unit").formValidator({onshow:"<?php echo L('input_price_to_change')?>",onfocus:"<?php echo L('number').L('empty')?>"}).inputValidator({min:1,max:999,onerror:"<?php echo L('number').L('empty')?>"}).regexValidator({regexp:"^(([1-9]{1}\\d*)|([0]{1}))(\\.(\\d){1,2})?$",onerror:"<?php echo L('must_be_price')?>"});
	$("#username").formValidator({onshow:"<?php echo L('input').L('username')?>",onfocus:"<?php echo L('username').L('empty')?>"}).inputValidator({min:1,max:999,onerror:"<?php echo L('username').L('empty')?>"}).ajaxValidator({
	    type : "get",
		url : "",
		data :"m=pay&c=payment&a=public_checkname_ajax",
		datatype : "html",
		async:'false',
		success : function(data){
			getusername($("#username").value,4);
            if(data!= 'FALSE')
			{

            	$("#balance").html(data);
                return true;
			}
            else
			{
            	$("#balance").html('');
                return false;
			}
		},
		buttons: $("#dosubmit"),
		onerror : "<?php echo L('user_not_exist')?>",
		onwait : "<?php echo L('checking')?>"
	});
	$("#usernote").formValidator({onshow:"<?php echo L('input').L('reason_of_modify')?>",onfocus:"<?php echo L('usernote').L('empty')?>"}).inputValidator({min:1,max:999,onerror:"<?php echo L('usernote').L('empty')?>"});
})
//-->
</script>
<div class="pad-10">
<div class="common-form">
<form name="myform" action="?m=pay&c=payment&a=<?php echo $_GET['a']?>" method="post" id="myform">
<table width="100%" class="table_form">

<tr style="display:none;">
<td  width="120">币值类型</td> 
<td><input name="money_type" value="0" type="radio" id="money_type1" checked> 人民币 
<input name="money_type" value="1" type="radio" id="money_type2"> 美元</td>
</tr>

<tr>
<td  width="120"><?php echo L('recharge_type')?></td> 
<td><input name="pay_type" value="1" type="radio" id="pay_type" checked> <?php echo L('money')?>  
<input name="pay_type" value="2" type="radio" id="pay_type"> <?php echo L('point')?></td>
</tr>
<tr>
<td  width="120">用户标识</td> 
<td><input type="text" name="clientname" size="15" value="<?php echo $clientname?>" id="takeflag" onblur="getusername(this.value,1);"></td>
</tr>
<tr>
<td  style="display:none" width="120">用户代号</td> 
<td><input style="display:none"  type="text" name="clientcode" size="15" value="<?php echo $clientcode?>" id="takecode" onblur="getusername(this.value,2);"></td>
</tr>
<tr>
<td  width="120"><?php echo L('username')?></td> 
<td><input type="text" name="username" size="15" value="<?php echo $username?>" id="username"><span id="balance"><span></td>
</tr>
<tr>
<td style="display:none" width="120">UserID</td> 
<td><input style="display:none"  type="text" name="userid" size="15" value="" id="userid" onblur="getusername(this.value,3);"></td>
</tr>
<tr>
<td  width="120"><?php echo L('recharge_quota')?></td> 
<td><input name="pay_unit" value="1" type="radio" checked> <?php echo L('increase')?>  <input name="pay_unit" value="0" type="radio"> <?php echo L('reduce')?> <input type="text" name="unit" size="10" value="<?php echo $unit?>" id="unit"></td>
</tr>
<tr>
<td  width="120"><?php echo L('trading').L('usernote')?></td> 
<td><textarea name="usernote"  id="usernote" rows="5" cols="50"></textarea></td>
</tr>
<tr>
<td  width="120"><?php echo L('op_notice')?></td> 
<td><label><input type="checkbox" id="sendemail" name="sendemail" value="1" checked> <?php echo L('op_sendemail')?></label></td>
</tr>
</table>
<div class="bk15"></div>
<input name="dosubmit" type="submit" value="<?php echo L('submit')?>" class="button" id="dosubmit">
</form>
</div>
</body>
</html>
<script type="text/javascript">

$(document).ready(function() {
	$("#paymethod input[type='radio']").click( function () {
		if($(this).val()== 0){
			$("#rate").removeClass('hidden');
			$("#fix").addClass('hidden');
			$("#rate input").val('0');
		} else {
			$("#fix").removeClass('hidden');
			$("#rate").addClass('hidden');
			$("#fix input").val('0');
		}	
	});
});
function category_load(obj)
{
	var modelid = $(obj).attr('value');
	$.get('?m=admin&c=position&a=public_category_load&modelid='+modelid,function(data){
			$('#load_catid').html(data);
		  });
}

function getusername(userid,flag){
	if(userid){
		$.post("/api.php?op=public_api&a=getuid&checkhash=<?php echo checkhash;?>&flag="+flag+"&key="+userid,function(data){
			$("#username").val(data.username);
			$("#userid").val(data.userid);
			$("#takeflag").val(data.clientname);
			$("#takecode").val(data.clientcode);
			$("#truename").val(data.clientname);
			if(flag!=4){
			$.post("/index.php?m=pay&c=payment&a=public_checkname_ajax&checkhash=<?php echo checkhash;?>&username="+data.username,function(data){
				if(data!= 'FALSE')
				{

					$("#balance").html(data);
				
				}
				else
				{
					$("#balance").html('');
					
				}
			});}
		},'json');
	}
}

</script>


