<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<div class="pad-10">
<form method="post" action="/index.php?m=package&c=admin_package&a=<?php echo $_GET['a']?>&aid=<?php echo $_GET['aid']?>&hid=<?php echo $an_info['storageid'];?>" name="myform2" id="myform2">
<table class="table_form" width="100%">

<?php

$sinfos  = siteinfo($this->get_siteid());
$package_overday = intval(substr($sinfos['package_overday'],0,2));
$package_overday2 = intval(substr($sinfos['package_overday'],2,4));

$package_overdayfee = floatval($sinfos['package_overdayfee']);

?>
	<tbody>
	<tr>
		<th><strong>运单号：</strong></th>
		<td><?php echo $an_info['waybillid'];?>
		<strong style="padding-left:20px;">编号：</strong>
		<strong style="color:red;">#<?php echo $an_info['aid'];?></strong>
		<strong style="padding-left:20px;">标识：</strong>
		<strong style="color:red;"><?php echo $an_info['takeflag'];?></strong>
		</td>
	</tr>
	<tr>
		<th ><strong><?php echo L('expressno')?>：</strong></th>
		<td ><input style="  border: none;background: #fff;" name="package[expressno]" id="expressno" type="text" size="32" readonly value="<?php echo $an_info['expressno'];?>"></td>
	</tr>
	

	<tr style="display:none;">
		<th ><strong><?php echo L('expressid')?>：</strong></th>
		<td> <input id="expressname" name="package[expressname]" value="<?php echo $an_info['expressname'];?>"  type="hidden"> 
		<select name="package[expressid]" id="expressid" onchange="javascript:document.getElementById('expressname').value=document.getElementById('expressid').options[document.getElementById('expressid').selectedIndex].text;">
		<option value="0"><?php echo L('pleaseselect');?></option>
		<?php
		$allship = $this->getallship();
		foreach($allship as $val)
		{
			$sel="";	
			if($an_info['expressname']==$val['title'])
			{
				$sel=" selected";
			}
			echo '<option value="'.$val['aid'].'" '.$sel.'>'.$val['title'].'</option>';
		}
		?>
		</select>
		</td><th><strong><?php echo L('warehouse')?>：</strong></th>
		<td>
		<select name="package[storageid]" id="storageid" >
<?php
	$seltext="";
	foreach($allstorage as $row){
		$sel="";
		if($row['aid']==$an_info['storageid']){$sel=' selected';$seltext=$row['title'];}
		echo '<option value="'.$row['aid'].'" '.$sel.'>'.$row['title'].'</option>';
	}
?>
		</select>
		<input name="package[storagename]" id="storagename"  type="hidden" value="<?php echo $seltext;?>" >
		</td>
	</tr>

<tr style="display:none;">
		<th><strong>编号：</strong></th>
		<td><input name="number" id="number" class="input-text" type="text" size="5" value="<?php echo $an_info['aid'];?>"></td>
		<th ><strong><?php echo L('expressno')?>：</strong></th>
		<td><input name="package[expressno]" id="expressno" class="input-text" type="text" size="22"  value="<?php echo $an_info['expressno'];?>"></td>
		<th style="display:none;"><strong><?php echo L('userid')?>：</strong></th>
		<td style="display:none;"><input name="package[userid]" id="userid" class="input-text" type="text" size="15" value="<?php echo $an_info['userid'];?>" onblur="getusername(this.value,3);"></td>
</tr>

	<tr style="display:none;">
		<th style="display:none;"><strong>仓位：</strong></th>
		<td style="display:none;"><input name="package[storageplace]" id="storageplace" class="input-text" type="text" size="15" value="<?php echo $an_info['storageplace'];?>"></td>
		<th style="display:none;"><strong>入库编号：</strong></th>
		<td style="display:none;" colspan=3><input name="package[storagecode]" id="storagecode" class="input-text" type="text" size="15" value="<?php echo $an_info['storagecode'];?>"></td>
		
		
	</tr>
	<tr style="display:none;">
		<th style="display:none;"><strong>收件标识：</strong></th>
		<td style="display:none;" ><input name="package[takeflag]" id="takeflag" class="input-text" type="text" size="15" value="<?php echo $an_info['takeflag'];?>" onblur="getusername(this.value,1);"></td>
		<th style="display:none;"><strong>收件代码：</strong></th>
		<td  style="display:none;" colspan=3><input name="package[takecode]" id="takecode" class="input-text" type="text" size="15" value="<?php echo $an_info['takecode'];?>" onblur="getusername(this.value,2);"></td>
	</tr>
	


	
	
	<tr style="display:none;">
		<th style="display:none;"><strong><?php echo L('username')?>：</strong></th>
		<td  ><input style="display:none;" name="package[username]" id="username" class="input-text" type="text" size="15" value="<?php echo $an_info['username'];?>" onblur="getusername(this.value,4);"></td>
		<th style="display:none;"><strong><?php echo L('truename')?>：</strong></th>
		<td  style="display:none;" colspan=3><input name="package[truename]" id="truename" class="input-text" type="text" size="15" value="<?php echo $an_info['truename'];?>"></td>

	</tr>
	
	<tr style="display:none;">
		<th style="display:none;"><strong><?php echo L('goodsname')?>：</strong></th>
		<td  colspan=3><input style="display:none;" name="package[goodsname]" id="goodsname" class="input-text" type="text" size="40" value="<?php echo $an_info['goodsname'];?>"></td>
	</tr>
	
	<tr style="display:none;">
		<th>&nbsp;</th>
		<td  colspan=3><strong>
		<?php echo L('amount')?>：</strong><input name="package[totalamount]" id="totalamount" class="input-text" type="text" size="15" value="<?php echo $an_info['totalamount'];?>">
		<strong style="color:red;font-weight: bolder;">
		
		 <strong><?php echo L('price')?>(円)：</strong> <input name="package[totalprice]" id="totalprice" class="input-text" type="text" size="15" value="<?php echo $an_info['totalprice'];?>"></td>
	</tr>
	<tr>
	<th style="color:red;font-weight: bolder;"><?php echo L('weight')?>(g)：</th>
	<td><input style="color:red;font-weight: bolder;" name="package[totalweight]" id="totalweight" class="input-text" type="text" onfocus="this.select();" size="15" value="<?php echo $an_info['totalweight'];?>">
	</td>
	<tr>
		
		<td  colspan=4>

		<table width="100%" cellspacing="0" style="border:solid 1px #d5dfe8;">
        <thead>
            <tr>
			<th style="text-align:center;display:none;" >邮单号</th>
			<th style="text-align:center">物品名称</th>
			<th style="text-align:center">数量</th>
			<th style="text-align:center">价值</th>
			<th style="text-align:center;display:none;">重量</th>
			
            </tr>
        </thead>
    <tbody>
		<?php
		

		 $goodsdatas=string2array($an_info['goodsdatas']);
	 foreach($goodsdatas as $key=>$goods){
		echo '<tr class="goods__row">
			<td align="center" style="display:none;">';
			
			echo $an_info['expressname'].'/';
			echo $an_info['expressno'];
			echo '</td>
			<td align="center">'.'<input name="waybill_goods['.$key.'][goodsname]" id="goodsname'.$key.'" class="input-text" type="text" size="30" value="'.$goods['goodsname'].'">'.'</td>
			<td align="center">'.'<input name="waybill_goods['.$key.'][amount]" id="amount'.$key.'" class="input-text" type="text" size="5" value="'.$goods['amount'].'" onblur="computtotal();">'.'</td>
			<td align="center">'.'<input name="waybill_goods['.$key.'][price]" id="price'.$key.'" class="input-text" type="text" size="5" value="'.$goods['price'].'"  onblur="computtotal();">'.'</td>
			<td style="display:none;" align="center">'.'<input name="waybill_goods['.$key.'][weight]" id="weight'.$key.'" class="input-text" type="text" size="5" value="'.$goods['weight'].'">'.'</td>';
			
			
			
			echo '
            </tr>';
			
	 }
		
		
		?>
		</tbody>
		</table>
		
		</td>
	</tr>
	
	<tr style="display:none" >
		<th ><strong>货物长宽高：</strong></strong></th>
		<td colspan=2>长：<input name="package[bill_long]" id="package_l" class="input-text" type="text" size="6" value="<?php echo $an_info['bill_long'];?>"> cm 宽： 
		<input name="package[bill_width]" id="package_w" class="input-text" type="text" size="6" value="<?php echo $an_info['bill_width'];?>"> cm 高： 
		<input name="package[bill_height]" id="package_h" class="input-text" type="text" size="6" value="<?php echo $an_info['bill_height'];?>">cm </td>
		</tr>

	<tr>
		<th><strong><?php echo L('status')?>：</strong></th>
		<td  colspan=3>
		<select name="package[status]" id="status" >
		<?php
		$allgetpackagestatus = $this->getpackagestatus();
		foreach($allgetpackagestatus as $k=>$r){
			$sel="";
			if( $r['value'] == intval($_GET['sta'])){$sel= 'selected';}
		
		echo '<option value="'.$r['value'].'" '.$sel.'>'.$r['title'].'</option>';

		//echo '<input name="package[status]" id="status'.$k.'"  type="radio"  value="'.$r['value'].'" '.$sel.'>'.L('package_status'.$r['value']).'&nbsp;&nbsp;';
		
		}
		?>
		</select>
		</td>
	</tr>
	<tr>
		<th><strong><?php echo L('common_send_email_notify')?>：</strong></th>
		<td  colspan=3><input name="send_email_nofity" id="send_email_nofity1"  type="radio"  value="1" checked>
		<?php echo L('Yes')?>&nbsp;&nbsp;<input name="send_email_nofity" id="send_email_nofity0"  type="radio"  value="0">
		<?php echo L('No')?>&nbsp;&nbsp;</td>
	</tr>
	<tr>
  		<th><strong><?php echo L('remark')?>：</strong></th>
        <td  colspan=3>
		<textarea name="package[remark]" id="remark" cols="50" rows=2><?php echo $an_info['remark'];?></textarea>
		</td>
	</tr>
	</tbody>
</table>
<input type="submit" name="dosubmit" id="dosubmit" value=" <?php echo L('ok')?> " class="dialog">&nbsp;<input type="reset" class="dialog" value=" <?php echo L('clear')?> ">

</form>
</div>
<script type="text/javascript">



function getusername(userid,flag){
	if(userid){
		$.post("/api.php?op=public_api&a=getuid&checkhash=<?php echo $_SESSION['checkhash'];?>&flag="+flag+"&key="+userid,function(data){
			$("#username").val(data.username);
			$("#userid").val(data.userid);
			$("#takeflag").val(data.clientname);
			$("#takecode").val(data.clientcode);
			//$("#truename").val(data.clientname);
		},'json');
	}
}

function computtotal(){
		
		var totalamount=0,totalweight=0,totalprice=0;
		$(".goods__row").each(function(){
			
			totalamount += parseInt($(this).find("input[id^='amount']").val());
			//totalweight += parseInt($(this).find("input[id^='weight']").val());
			totalprice +=parseFloat($(this).find("input[id^='price']").val())*parseInt($(this).find("input[id^='amount']").val());
		});

		$("#totalamount").val(totalamount);
		//$("#totalweight").val(totalweight);
		$("#totalprice").val(totalprice);
	}
//getusername(<?php echo $an_info['userid'];?>,3);
</script>
</body>
</html>
