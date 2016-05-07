<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<div class="pad-10">
<form method="post" action="?m=package&c=admin_package&a=add" name="myform" id="myform">

<input name="package[storageid]" id="storageid" type="hidden" value="<?php echo intval($_GET['hid']);?>" >

<table class="table_form" width="100%" cellspacing="0">
<tbody>

	<tr>
		<th><strong><?php echo L('expressid')?>：</strong></th>
		<td ><input id="expressname" name="package[expressname]"   type="hidden">  
		<select name="package[expressid]" id="expressid" onchange="javascript:document.getElementById('expressname').value=document.getElementById('expressid').options[document.getElementById('expressid').selectedIndex].text;">
		<option value="0"><?php echo L('pleaseselect');?></option>
		<?php
		$allship = $this->getallship();

		foreach($allship as $val)
		{
			echo '<option value="'.$val['aid'].'">'.$val['title'].'</option>';
		}
		?>
		</select>
		</td><th><strong><?php echo L('warehouse')?>：</strong></th>
		<td  >
		<select name="package[storageid]" id="storageid" >
<?php
	$seltext="";
	foreach($allstorage as $row){
		$sel="";
		if($this->hid==$row['aid']){$sel=" selected";$seltext=$row['title'];}
		echo '<option value="'.$row['aid'].'" '.$sel.'>'.$row['title'].'</option>';
	}
?>
		</select>
		
		<input name="package[storagename]" id="storagename"  type="hidden" value="<?php echo $seltext;?>" >

		</td>
	</tr>
	<tr>
		<th ><strong><?php echo L('expressno')?>：</strong></th>
		<td  ><input name="package[expressno]" id="expressno" class="input-text" type="text" size="15" ></td>
		<th ><strong><?php echo L('userid')?>：</strong></th>
		<td  ><input name="package[userid]" id="userid" class="input-text" type="text" size="15" onblur="getusername(this.value,3);" ></td>
	</tr>
	<tr>
		<th ><strong>仓位：</strong></th>
		<td  ><input name="package[storageplace]" id="storageplace" class="input-text" type="text" size="15" ></td>
		<th ><strong>入库编号：</strong></th>
		<td  colspan=3><input name="package[storagecode]" id="storagecode" class="input-text" type="text" size="15" ></td>
	</tr>
	<tr>
		<th ><strong>收件标识：</strong></th>
		<td  ><input name="package[takeflag]" id="takeflag" class="input-text" type="text" size="15" onblur="getusername(this.value,1);"></td>
		<th ><strong>收件代号：</strong></th>
		<td  colspan=3><input name="package[takecode]" id="takecode" class="input-text" type="text" size="15" onblur="getusername(this.value,2);"></td>
	</tr>
	
	
	
	<tr>
		<th ><strong><?php echo L('username')?>：</strong></th>
		<td  ><input name="package[username]" id="username" class="input-text" type="text" size="15" onblur="getusername(this.value,4);"></td>
		<th ><strong><?php echo L('truename')?>：</strong></th>
		<td  ><input name="package[truename]" id="truename" class="input-text" type="text" size="15" ></td>
	</tr>
	
	<tr>
		<th ><strong><?php echo L('goodsname')?>：</strong></th>
		<td  colspan=3><input name="package[goodsname]" id="goodsname" class="input-text" type="text" size="40" ></td>
	</tr>
	<tr><th >&nbsp;</th>
		<td colspan=3><span style="display:none;"><strong><?php echo L('amount')?>：</strong><input name="package[totalamount]" id="amount" class="input-text" type="text" size="15" value="0"></span><strong><?php echo L('weight')?>(g)：</strong><input name="package[totalweight]" id="weight" class="input-text" type="text" size="15" value="0"><strong><?php echo L('price')?>：</strong><input name="package[totalprice]" id="price" class="input-text" type="text" size="15" value="0.00"></td>
		
	</tr>
	
	
	<tr style="display:none;">
		<th ><strong>货物长宽高：</strong></strong></th>
		<td colspan=2>长：<input name="package[bill_long]" id="package_l" class="input-text" type="text" size="6" > cm 宽： <input name="package[bill_width]" id="package_w" class="input-text" type="text" size="6"> cm 高： <input name="package[bill_height]" id="package_h" class="input-text" type="text" size="6" >cm </td>
		</tr>
	<tr>
		<th><strong><?php echo L('status')?>：</strong></th>
		<td  colspan=3>
		<select name="package[status]" id="status" >
		<?php
		$allgetpackagestatus = $this->getpackagestatus();

		foreach($allgetpackagestatus as $k=>$r){
			$sel="";
			if($k==1){$sel= 'selected';}
		
		echo '<option value="'.$r['value'].'" '.$sel.'>'.$r['title'].'</option>';
	
		}
		?>
		</select>
		</td>
	</tr>
	<tr>
		<th><strong><?php echo L('common_send_email_notify')?>：</strong></th>
		<td  colspan=3><input name="send_email_nofity" id="send_email_nofity1"  type="radio"  value="1" checked><?php echo L('Yes')?>&nbsp;&nbsp;<input name="send_email_nofity" id="send_email_nofity0"  type="radio"  value="0" ><?php echo L('No')?>&nbsp;&nbsp;</td>
	</tr>
	<tr>
  		<th><strong><?php echo L('remark')?>：</strong></th>
        <td  colspan=3>
		<textarea name="package[remark]" id="remark" cols="50" rows=1></textarea>
		</td>
	</tr>
	</tbody>
</table>
<input type="submit" name="dosubmit" id="dosubmit" value=" <?php echo L('ok')?> " class="dialog">&nbsp;<input type="reset" class="dialog" value=" <?php echo L('clear')?> ">
</form>
</div>
<script type="text/javascript">


$(document).ready(function(){
	$.formValidator.initConfig({formid:"myform",autotip:true,onerror:function(msg,obj){}});
	$('#expressno').formValidator({onshow:"<?php echo L('expressno').L('cannot_empty');?>",onfocus:"<?php echo  L('expressno').L('cannot_empty')?>",oncorrect:"<?php echo L('right')?>"}).inputValidator({min:1,onerror:"<?php echo  L('expressno').L('cannot_empty')?>"}).ajaxValidator({
						type : "post",
						url  : "/index.php?m=package&c=admin_package&a=public_check_expressno&hid=<?php echo intval($_GET['hid']);?>",
						success: function(data){
							if(data == "0"){
								return true;
							}else if(data == "1"){
								return false;
							}
						},
						onerror: "<?php echo L('package_exist');?>"
	});
});

function getusername(userid,flag){
	if(userid){
		$.post("/api.php?op=public_api&a=getuid&checkhash=<?php echo $_SESSION['checkhash'];?>&flag="+flag+"&key="+userid,function(data){
			$("#username").val(data.username);
			$("#userid").val(data.userid);
			$("#takeflag").val(data.clientname);
			$("#takecode").val(data.clientcode);
			$("#truename").val(data.clientname);
		},'json');
	}
}


</script>
</body>
</html>
