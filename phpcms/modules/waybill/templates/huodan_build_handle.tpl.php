<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<div class="pad-10">
<form method="post" action="/index.php?m=waybill&c=admin_waybill&a=huodan_build_handle&flag=<?php echo $flag;?>&hid=<?php echo $this->hid;?>&ids=<?php echo $ids;?>" name="myform" id="myform">

<input name="position" id="position" type="hidden" value="<?php echo $_GET['p'];?>"/>

<table class="table_form" width="100%" cellspacing="0">
<tbody>
	
	<tr>
		<th ><strong><?php echo L('invoice_number');?>：</strong></th>
		<td style="color:#ff6600"><input name="data[huodanno]" id="huodanno" class="input-text" type="text" size="30" value=""><br><?php echo L('invoice_tips_1');?></td>
	</tr>
	<tr>
		<th><strong><?php echo L('invoice_time');?>：</strong></th>
		<td><link rel="stylesheet" type="text/css" href="/resource/js/calendar/jscal2.css"/>
			<link rel="stylesheet" type="text/css" href="/resource/js/calendar/border-radius.css"/>
			<link rel="stylesheet" type="text/css" href="/resource/js/calendar/win2k.css"/>
			<script type="text/javascript" src="/resource/js/calendar/calendar.js"></script>
			<script type="text/javascript" src="/resource/js/calendar/lang/en.js"></script><input type="text" name="data[addtime]" id="addtime" value="<?php echo date('Y-m-d H:i',time());?>" size="20" class="date" readonly>&nbsp;<script type="text/javascript">
			Calendar.setup({
			weekNumbers: true,
		    inputField : "addtime",
		    trigger    : "addtime",
		    dateFormat: "%Y-%m-%d %H:%M",
		    showTime: true,
		    minuteStep: 1,
		    onSelect   : function() {this.hide();}
			});
        </script>&nbsp;&nbsp;<strong><?php echo L('fahuo_shipname');?>(TNT/FEDEX)：</strong> <input name="data[shipname]" id="shipname" class="input-text" type="text" size="10" ></td>
	</tr>
	<tr>
		<th ><strong><?php echo L('selected_danbill_recoeds')?>(<span id="unionbox_packagenum" style="color:#ff6600"><?php echo $allpackages;?></span>)：</strong></th>
		<td>
		<textarea name="data[kabanno]" id="kabanno" cols="40" rows=8 style="display:none;"><?php echo $allbills;?></textarea><textarea name="data_bills" id="data_bills" cols="60" rows=8 readonly><?php echo str_replace("<br>","\n",$this->get_bill_line($allbills));?></textarea>
		</td>
	</tr>
	
	<tr style="display:none;">
		<th><strong><?php echo L('fahuo_placename')?>：</strong></th>
		<td>
		<select name="data[placeid]" id="placeid"  onchange="document.getElementById('placename').value=this.options[this.selectedIndex].text;"  >
		
		<?php
		$allstroage = $this->getallstorage();
		foreach($allstroage as $k=>$v){
			$dd="";
			if ($k==1){$dd= 'selected';}
			echo '<option value="'.$v['aid'].'" '.$dd.'>'.$v['title'].'</option>';
		}
		?>
		</select>
		<input name="data[placename]" id="placename"  type="hidden" ><strong><?php echo L('point_of_destination');?>：</strong> <select name="data[position]" id="position"    >
		<option value="zhongzhuan" selected><?php echo L('transit_point');?></option>
		<!--<option value="paifa" selected><?php echo L('distribution_point');?></option>-->
		</select>
		<strong><?php echo L('pay_weight')?>：</strong><input name="data[weight]" id="weight" class="input-text" type="text" size="10" value="<?php echo $totalw;?>" onKeyUp="this.value=this.value.replace(/[^0-9.]/gi,'')">
		</td>
	</tr>
	
	<tr>
		<th><strong>邮件通知：</strong></th>
		<td><input name="data[lastplacename]" id="lastplacename" class="input-text" type="hidden" size="10" value=""><strong></strong><input name="send_email_nofity" id="send_email_nofity1"  type="radio"  value="1" checked><?php echo L('Yes');?>&nbsp;&nbsp;<input name="send_email_nofity" id="send_email_nofity0"  type="radio"  value="0" ><?php echo L('No');?>&nbsp;&nbsp;</td>
	</tr>
	
	<tr>
		<th><strong><?php echo L('operator')?>：</strong></th>
		<td><?php echo $this->username;?></td>
	</tr>

	<tr>
  		<th><strong><?php echo L('remark')?>：</strong></th>
        <td>
		<textarea name="data[remark]" id="remark" cols="40" rows=2></textarea>
		</td>
	</tr>
	</tbody>
</table>
<input type="submit" name="dosubmit" id="dosubmit" value=" <?php echo L('ok')?> " class="dialog">&nbsp;<input type="reset" class="dialog" value=" <?php echo L('clear')?> ">
</form>
</div>

<script>
		document.getElementById('placename').value=document.getElementById('placeid').options[document.getElementById('placeid').selectedIndex].text;
		</script>


</body>
</html>
<script type="text/javascript">


$(document).ready(function(){
	$.formValidator.initConfig({formid:"myform",autotip:true,onerror:function(msg,obj){}});
	$('#huodanno').formValidator({onshow:"<?php echo L('invoice_number').L('huodan_cannot_be_empty');?>",onfocus:"<?php echo  L('invoice_number').L('cannot_empty')?>",oncorrect:"<?php echo L('right')?>"}).inputValidator({min:1,onerror:"<?php echo  L('invoice_number').L('cannot_empty')?>"});
});
</script>