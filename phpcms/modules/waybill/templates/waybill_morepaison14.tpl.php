<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<div class="pad-10">
<form method="post" action="?m=waybill&c=admin_waybill&a=waybill_morepaison&status=<?php echo $status;?>&hid=<?php echo $_GET['hid'];?>" name="myform" id="myform">

<table class="table_form" width="100%" cellspacing="0">
<tbody>
	<tr>
		<td colspan=2>
		<table class="table_form" width="100%" cellspacing="0">
		<?php
		foreach($waybill_array as $v){
		echo '<tr>
		<th><strong>'.L('waybillid').'：</strong></th>
		<td><input name="data['.$v['aid'].'][waybillid]" id="waybillid'.$v['aid'].'" class="input-text" type="text" size="22" value="'.trim($v['waybillid']).'" readonly></td>
		<th><strong>'.L('please_select').'：</strong></th>
		<td>
		<select name="selkaidi'.$v['aid'].'" id="selkaidi'.$v['aid'].'" onchange="selkd('.$v['aid'].');">';
		$allselect_express_cn = $this->select_express_cn();
		foreach($allselect_express_cn as $ex){
			$sel="";
			if($shippingname[$v['aid']]==trim($ex['title'])){
			$sel=" selected";
			}
			echo '<option value="'.$ex['remark'].'" '.$sel.'>'.$ex['title'].'</option>';
		}
		
		echo '</select>
		
		<input name="data['.$v['aid'].'][excompany]" id="excompany'.$v['aid'].'" type="hidden">

		<input name="data['.$v['aid'].'][expressurl]" id="expressurl'.$v['aid'].'" class="input-text" type="hidden" size="15" value=""></td>
		<th><strong>'.L('express_no').'：</strong></th>
		<td><input name="data['.$v['aid'].'][expressnumber]" id="expressnumber'.$v['aid'].'" class="input-text" type="text" size="15" value=""></td>
		
		<th><strong>实际运费(円)：</strong></th>
		<td><input name="data['.$v['aid'].'][factpay]" id="factpay'.$v['aid'].'" class="input-text" type="text" size="15" value=""></td>
		
	</tr>';
	}	
	?>
	</table>
		</td>
	</tr>
	
	
	<tr>
		<th><strong><?php echo L('finish_username')?>：</strong></th>
		<td><input name="username" id="username" class="input-text" type="text" size="30" value="<?php echo $this->username;?>" readonly></td>
	</tr>

	<tr>
		<th><strong><?php echo L('finish_time')?>：</strong></th>
		<td><input name="addtime" id="addtime" class="input-text" type="text" size="30" value="<?php echo date('Y-m-d H:i:s',time());?>" readonly></td>
	</tr>
	<tr>
		<th><strong><?php echo L('common_send_email_notify');?>：</strong></th>
		<td><input name="send_email_nofity" id="send_email_nofity1"  type="radio"  value="1" ><?php echo L('Yes');?>&nbsp;&nbsp;<input name="send_email_nofity" id="send_email_nofity0"  type="radio"  value="0" checked><?php echo L('No');?>&nbsp;&nbsp;</td>
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

<?php
	foreach($waybill_array as $v){
	echo 'selkd('.$v['aid'].');';
	echo "\n";
	
	}
?>

function selkd(id){
	var kdurl=document.getElementById('selkaidi'+id).options[document.getElementById('selkaidi'+id).selectedIndex].value;
	document.getElementById('expressurl'+id).value=kdurl;
	
	document.getElementById('excompany'+id).value=document.getElementById('selkaidi'+id).options[document.getElementById('selkaidi'+id).selectedIndex].text;

}
function kdi(flag){
	if(flag==14){
		document.getElementById('expressnumber0').style.display="";
		document.getElementById('expressurl0').style.display="";
	}else{
		document.getElementById('expressnumber0').style.display="none";
		document.getElementById('expressurl0').style.display="none";
	}
}

</script>