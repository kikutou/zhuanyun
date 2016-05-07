<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');

$an_info = $this->db->get_one(array('waybillid'=>trim($_GET['waybillid'])));
?>
<div class="pad-10">
<form method="post" action="?m=waybill&c=admin_waybill&a=waybill_paison&hid=<?php echo $_GET['hid'];?>&returncode=<?php echo $_GET['returncode'];?>" name="myform" id="myform"  onsubmit="return submitcheck();">

<table class="table_form" width="100%" cellspacing="0" <?php if(!isset($_GET['returncode'])){echo 'style="display:none;"';}?>>
<tbody>
	<tr>
		<th><strong><?php echo L('returned_back_address')?>：</strong></th>
		<td><?php echo $return_address;?></td>
	</tr>
	<tr>
		<th><strong><?php echo L('returned_back_person')?>：</strong></th>
		<td><?php echo $return_person;?></td>
	</tr>
	<tr>
		<th><strong><?php echo L('returned_back_mobile')?>：</strong></th>
		<td><?php echo $return_mobile;?></td>
	</tr>
	<tr>
		<th><strong><?php echo L('house_jihuo_returned_information').L('remark');?>：</strong></th>
		<td><?php echo $return_remarkf.'/<font color=red>'.$return_remark.'</font>';?></td>
	</tr>
	<tr><td colspan=2 >&nbsp;</td></tr>
</tbody>
</table>

<table class="table_form" width="100%" cellspacing="0">
<tbody>
	<tr>
		<th><strong><?php echo L('waybillid')?>：</strong></th>
		<td><input name="data[waybillid]" id="waybillid" class="input-text" type="text" size="16" value="<?php echo $_GET['waybillid']?>" >
		<strong style="padding-left:10px;">编号：</strong>
		<strong style="color:red;">#<?php echo $an_info['aid'];?></strong>
		<strong style="padding-left:10px;">标识：</strong>
		<strong style="color:red;"><?php echo $an_info['takeflag'];?></strong>		
		</td>
	</tr>
	
	<tr>
		<th ><strong><?php echo L('paisou_method')?>：</strong></th>
		<td>
		
		<input onclick="kdi(this.value)"  name="data[status]" id="status" type="radio" value="14" checked><?php echo L('paisou_method_14');?>&nbsp;
		<span style="display:none;"><input name="data[status]" onclick="kdi(this.value)" id="status" type="radio" value="13"><?php echo L('paisou_method_13');?></span>
		</td>
	</tr>
	<tr id="expressurl0">
		<th><strong><?php echo L('please_select');?>：</strong></th>
		<td>
		<select name="selkaidi" id="selkaidi" onchange="selkd();">
		<?php

		if(!isset($_GET['returncode'])){
			$allselect_express_cn = $this->select_express_cn();
		}else{
			$allselect_express_cn = $this->select_express_com();
		}

		$sel="";
		foreach($allselect_express_cn as $ex){
			$sel="";
			if(trim($ex['title'])==$_GET['shippingname']){$sel=" selected ";}
			echo '<option value="'.$ex['remark'].'" '.$sel.'>'.$ex['title'].'</option>';
		}
		
		?>
		</select><input name="data[excompany]" id="excompany"  type="hidden" >
		<input name="data[expressurl]" id="expressurl" class="input-text" type="hidden" size="30" value=""></td>
	</tr>
	<tr id="expressnumber0" >
		<th><strong><?php echo L('express_no')?>：</strong></th>
		<td><input name="data[expressnumber]" id="expressnumber" class="input-text" type="text" size="30" value=""></td>
	</tr>
	<tr id="factpay">
		<th style="color:red">实际运费：</th>
		<td><input name="data[factpay]" id="factpay" class="input-text" type="text" size="15" value=""></td>
	</tr>

	<tr id="oneselftime0" style="display:none;">
		<th><strong><?php echo L('oneselftime')?>：</strong></th>
		<td><input name="data[sendtime]" id="sendtime" class="input-text" type="text" size="30" value="<?php echo date('Y-m-d',time());?>"></td>
	</tr>
	
	<tr>
		<th><strong><?php echo L('finish_username')?>：</strong></th>
		<td><input name="data[username]" id="username" class="input-text" type="text" size="30" value="<?php echo $this->username;?>"></td>
	</tr>

	<tr>
		<th><strong><?php echo L('finish_time')?>：</strong></th>
		<td><input name="data[addtime]" id="addtime" class="input-text" type="text" size="30" value="<?php echo date('Y-m-d H:i:s',time());?>" ></td>
	</tr>
	<tr>
		<th><strong><?php echo L('common_send_email_notify');?>：</strong></th>
		<td><input name="send_email_nofity" id="send_email_nofity1"  type="radio"  value="1" checked>
		<?php echo L('Yes');?>&nbsp;&nbsp;<input name="send_email_nofity" id="send_email_nofity0"  type="radio"  value="0" >
		<?php echo L('No');?>&nbsp;&nbsp;</td>
	</tr>
	<tr>
  		<th><strong><?php echo L('remark')?>：</strong></th>
        <td>
		<textarea name="handle[remark]" id="remark" cols="30" rows=2></textarea>
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
selkd();
function selkd(){
	var kdurl=document.getElementById('selkaidi').options[document.getElementById('selkaidi').selectedIndex].value;
	document.getElementById('expressurl').value=kdurl;
	document.getElementById('excompany').value=document.getElementById('selkaidi').options[document.getElementById('selkaidi').selectedIndex].text;
	

}

function kdi(flag){
	if(flag==14){
		document.getElementById('expressnumber0').style.display="";
		//document.getElementById('expresspay').style.display="";
		document.getElementById('expressurl0').style.display="";
		document.getElementById('oneselftime0').style.display="none";
	}else{
		document.getElementById('expressnumber0').style.display="none";
		document.getElementById('expressurl0').style.display="none";
		document.getElementById('oneselftime0').style.display="";
	}
}

$(document).ready(function(){
	$.formValidator.initConfig({formid:"myform",autotip:true,onerror:function(msg,obj){}});
	$('#waybillid').formValidator({onshow:"<?php echo L('waybillid').L('cannot_empty');?>",onfocus:"<?php echo  L('waybillid').L('cannot_empty')?>",oncorrect:"<?php echo L('right')?>"}).inputValidator({min:1,onerror:"<?php echo  L('waybillid').L('cannot_empty')?>"});
});

  function submitcheck(){
	  if($("#expressnumber").val()==''){
			alert("请填写快递号！");
			form.expressnumber.focus();
			return false;
		}return true;
  }
</script>