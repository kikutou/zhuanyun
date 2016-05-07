<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<div class="pad-10">
<form method="post" action="?m=waybill&c=admin_waybill&a=pay&oid=<?php echo $oid;?>" name="myform" id="myform"  onsubmit="comput();">
<table class="table_form" width="100%" cellspacing="0">
<tbody>
<tr style="display:none">
		<th ><strong><?php echo L('selected_recoeds')?>：</strong></th>
		<td><?php echo $ids;?></td>
	</tr>
	
	<tr style="display:none">
	<th><strong><?php echo L('use_formulas');?>：</strong></th>
	<td><div id="gongshi"></div></td>
	</tr>
	<tr style="display:none">
		<th><strong><?php echo L('waybill_lwh')?>(CM)：</strong></th>
		<td><input name="data[bill_long]" id="bill_long"  type="text"  size=10 value="<?php echo $bill_long;?>" onKeyUp="this.value=this.value.replace(/[^0-9.]/gi,'')" onblur="comput();">X<input name="data[bill_width]" id="bill_width"  type="text"  size=10 value="<?php echo $bill_width;?>" onKeyUp="this.value=this.value.replace(/[^0-9.]/gi,'')" onblur="comput();">X<input name="data[bill_height]" id="bill_height"  type="text" size=10  value="<?php echo $bill_height;?>" onKeyUp="this.value=this.value.replace(/[^0-9.]/gi,'')" onblur="comput();"></td>
	</tr>
	

	<tr>
		<th><strong><?php echo L('actual_weight_p')?>：</strong></th>
		<td><input readonly="readonly" name="data[totalweight]" id="totalweight" class="input-text" type="text" size="10" value="<?php echo $totalweight;?>" >&nbsp;&nbsp;<?php //echo L('shipping_line');?><input readonly="readonly" name="showlinefee" id="showlinefee" class="input-text" type="text" size="10" value="0" style="display:none"><input readonly="readonly" name="data[factweight]" id="factweight" class="input-text" type="hidden" size="10" value="0.00" ></td>
	</tr>
	
	<tr>
		<th><strong><?php echo L('pay_total')?>：</strong></th>
		<td><input readonly="readonly" name="data[totalship]" id="totalship" class="input-text" type="text" size="10" value="<?php echo $totalship;?>"  >&nbsp;&nbsp;<?php echo L('total_value_added_costs');?>：<input readonly="readonly" name="data[allvaluedfee]" id="allvaluedfee" class="input-text" type="text" size="10" value="<?php echo $allvaluedfee;?>" ></td>
	</tr>
	<tr>
		<th><strong><?php echo L('member_discount');?>：</strong></th>
		<td><input readonly="readonly" name="data[memberdiscount]" id="memberdiscount" class="input-text" type="text" size="10" value="<?php echo $memberdiscount;?>" >%&nbsp;&nbsp;<?php echo L('additional_discount');?>：<input readonly="readonly" name="data[otherdiscount]" id="otherdiscount" class="input-text" type="text" size="10" value="<?php echo $otherdiscount;?>" >%</td>
	</tr>
	<tr>
		<th><strong><?php echo L('other_fee');?>：</strong></th>
		<td><input readonly="readonly" name="data[otherfee]" id="otherfee"  type="text"  size=10 value="<?php echo $otherfee;?>" >&nbsp;&nbsp;
		操作费：<input readonly="readonly" name="data[taxfee]" id="taxfee" class="input-text" type="text" size="10" value="<?php echo $taxfee;?>" >&nbsp;&nbsp;</td>
	</tr>
	<tr>
		<th><strong><?php echo L('package_overday');?>：</strong></th>
		<td><input readonly="readonly" name="wbill[overdayscount]" id="overdayscount"  type="text"  size=10 value="<?php echo $overdayscount;?>" >&nbsp;&nbsp;<?php echo L('package_overdayfee');?>：<input readonly="readonly" name="wbill[overdaysfee]" id="overdaysfee" class="input-text" type="text" size="10" value="<?php echo $overdaysfee;?>" >&nbsp;&nbsp;</td>
	</tr>

	<tr>
		<th><strong><?php echo L('paid_in_fee');?>(円)：</strong></th>
		<td><input readonly="readonly" name="data[payfeeusd]" id="payfeeusd"  type="text"  size=10 value="<?php echo $payfeeusd;?>" ">&nbsp;&nbsp;RMB <?php echo L('wayrate');?>：<input readonly="readonly" name="data[wayrate]" id="wayrate" class="input-text" type="text" size="15" value="<?php echo $wayrate;?>" ></td>
	</tr>
	<tr>
		<th><strong><?php echo L('paid_in_fee');?>(RMB)：</strong></th>
		<td><input readonly="readonly" name="data[payedfee]" id="payedfee"  type="text"  size=10 value="<?php echo $payedfee;?>"></td>
	</tr>
	<tr>
		<th><strong><?php echo L('goodsname')?>：</strong></th>
		<td><input name="data[goodsname]" id="goodsname"  type="text"  size=30 value="<?php echo $goodsname;?>"></td>
	</tr>
		<tr>
		<th><strong><?php echo L('common_send_email_notify');?>：</strong></th>
		<td><input name="send_email_nofity" id="send_email_nofity1"  type="radio"  value="1" checked><?php echo L('Yes');?>&nbsp;&nbsp;<input name="send_email_nofity" id="send_email_nofity0"  type="radio"  value="0" ><?php echo L('No');?>&nbsp;&nbsp;</td>
	</tr>
	<tr>
		<th><strong><?php echo L('operator')?>：</strong></th>
		<td><?php echo $this->username;?></td>
	</tr>

	<tr>
  		<th><strong><?php echo L('remark')?>：</strong></th>
        <td>
		<textarea name="history[remark]" id="remark" cols="40" rows=4></textarea>
		</td>
	</tr>
	</tbody>
</table>
<input type="submit" onclick="return paybill();" name="dosubmit" id="dosubmit" value=" <?php echo L('ok')?> " class="dialog">&nbsp;<input type="reset" class="dialog" value=" <?php echo L('clear')?> ">
<input type="hidden" id="tweightfee" value="<?php echo $tweightfee;?>"/>
<input type="hidden" id="vweightfee" value="<?php echo $vweightfee;?>"/>
<input type="hidden" id="user_amount" value="<?php echo $useramount;?>"/>
</form>
</div>
</body>
</html>
<script type="text/javascript">
$(document).ready(function(){
	$.formValidator.initConfig({formid:"myform",autotip:true,onerror:function(msg,obj){window.top.art.dialog({content:msg,lock:true,width:'220',height:'70'}, function(){this.close();$(obj).focus();})}});
	
});



function comput2(){
	//var bill_long=parseFloat($("#bill_long").val()).toFixed(2);
	//var bill_width=parseFloat($("#bill_width").val()).toFixed(2);
	//var bill_height=parseFloat($("#bill_height").val()).toFixed(2);
	var totalweight=parseFloat($("#totalweight").val()).toFixed(2);//实际重量
	var pay_feeweight=0;//收费重量
	var tweightfee=parseFloat($("#tweightfee").val()).toFixed(2);//航线运费
	var vweightfee=parseFloat($("#vweightfee").val()).toFixed(2);//体积重运费
	var vweightfee=0;//体积重运费
	var totalship=0;//总运费
	var allvaluedfee=parseFloat($("#allvaluedfee").val()).toFixed(2);//增值费用

	var otherfee=parseFloat($("#otherfee").val());//其它费用

	var fee="<?php echo $package_overdayfee;?>";
	$("#overdaysfee").val(($("#overdayscount").val()*fee));

	var overdaysfee=parseFloat($("#overdaysfee").val());
 
	var volumeweight=0;//体积重
	var memberdiscount=parseFloat($("#memberdiscount").val()) * 0.01;//会员折扣
	var otherdiscount=parseFloat($("#otherdiscount").val())* 0.01;//额外折扣
	var discount = parseFloat(memberdiscount)+parseFloat(otherdiscount);//需要打多少折
	var wayrate = $("#wayrate").val();

	var volumefee=0;//体积费

	if(discount==0)
		discount=1;//不打折


	volumeweight =0;
	$("#volumeweight").val(volumeweight);
 
	if(parseFloat(volumeweight)>parseFloat(totalweight)){//体积重>实际重
		//运费是=实际重量*实际重量运费 +（体积重-实际重量）*体积重运费
			
		totalship = totalship;
	
		$("#showlinefee").val(tweightfee);
		//$("#gongshi").html("<font color=red>总运费=体积重量*航线运费</font>");
		

	}else{
		
		//$("#gongshi").html("<font color=red>总运费=实际重量*航线运费</font>");
		$("#showlinefee").val(tweightfee);
		totalship=totalship;
		pay_feeweight=0;
		volumefee=0;
	}
	
	pay_feeweight=parseFloat(pay_feeweight).toFixed(2);
	totalship=parseFloat(totalship).toFixed(2);
	payedfee=parseFloat(totalship)*discount+parseFloat(allvaluedfee)+otherfee + overdaysfee + parseFloat($("#taxfee").val());//打折
	//payedfee=parseFloat(totalship)+parseFloat(allvaluedfee);
	payedfee=parseFloat(payedfee).toFixed(2);
 
	$("#factweight").val(pay_feeweight);//收费重量
	$("#totalship").val(totalship);//总运费
	
	$("#payfeeusd").val(payedfee);//实扣费用円

	$("#payedfee").val(parseFloat(payedfee*wayrate).toFixed(2));//实扣费用RMB
	$("#volumefee").val(parseFloat(volumefee).toFixed(2));//体积费用
 
}


function paybill(){
	var payedfee=parseFloat($("#payedfee").val());//实扣费用
	var user_amount=parseFloat($("#user_amount").val());
	
	if(user_amount<payedfee){
		
		$.post("/index.php?m=waybill&c=admin_waybill&a=nopay_sendemail&checkhash=<?php echo $_SESSION['checkhash'];?>",{sysbillid:"<?php echo $info ['sysbillid'];?>",remark:"<?php echo L('balance_is_insufficient');?>!"},function(data){
		
		});

		window.top.art.dialog({content:"<?php echo L('balance_is_insufficient');?>!",lock:true,width:'220',height:'70'});
		return false;
	}

	return true;
}

//comput();
</script>