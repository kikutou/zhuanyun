<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<div class="pad-10">
<form method="post" action="/index.php?m=package&c=admin_package&a=<?php echo $_GET['a']?>&aid=<?php echo $idd?>&hid=<?php echo $an_info['storageid'];?>" name="myform2" id="myform2" onsubmit="return chkselpackage();">
<table class="table_form" width="100%">
	<tbody>
	<tr>
		<th ><strong><?php echo L('waybill')?>：</strong></th>
		<td> <?php echo $an_info['waybillid'];?> 
		
		</td><th><strong><?php echo L('warehouse')?>：</strong></th>
		<td>
		<?php echo $an_info['storagename'];?>
		</td>
	</tr>


	<tr>
		<th ><strong><?php echo L('username')?>：</strong></th>
		<td  ><?php echo $an_info['username'];?></td>
		<th ><strong><?php echo L('truename')?>：</strong></th>
		<td  ><span id="truename"></span></td>
	</tr>
	
	
	<tr>
		<th ><strong><?php echo L('amount')?>：</strong></th>
		<td  ><?php echo $an_info['totalamount'];?></td>
		<th><strong><?php echo L('weight')?>：</strong></th>
		<td ><?php echo $an_info['totalweight'];?></td>
	</tr>

	<tr>
		<th><strong><?php echo L('price')?>：</strong></th>
		<td ><?php echo $an_info['totalprice'];?></td>
		<th><strong><?php echo L('common_send_email_notify')?>：</strong></th>
		<td ><input name="send_email_nofity" id="send_email_nofity1"  type="radio"  value="1" checked><?php echo L('yes')?>&nbsp;&nbsp;<input name="send_email_nofity" id="send_email_nofity0"  type="radio"  value="0" ><?php echo L('No')?>&nbsp;&nbsp;</td>
	</tr>
	<tr>
	<td colspan=4>
	
 <table border=1 width="97%" align="center" style="border:solid 1px #ccc;" >
					<tr >
					<td align=center >&nbsp;</td>
					<td align=center height="25px" ><?php echo L('expressno')?></td>
					<td align=center ><?php echo L('goodsname')?></td>
					<td align=center><?php echo L('weight_list')?></td>
					<td align=center ><?php echo L('amount_list')?></td>
					<td align=center ><?php echo L('price_list')?></td>
					<td align=center ><?php echo L('returned_fee')?></td>
					<td align=center ><?php echo L('remark')?></td>
					</tr>
					<?php
					
					foreach($allwaybill_goods as  $k=>$goods ){
						echo '<tr class="goods__row">
						<td align=center><input type="hidden" name="waybill_goods['.$goods['id'].'][id]" value="'.$goods['id'].'" ><input type="hidden" name="waybill_goods['.$goods['id'].'][weight]" value="'.$goods['weight'].'" ><input type="hidden" name="waybill_goods['.$goods['id'].'][amount]" value="'.$goods['amount'].'" ><input type="hidden" name="waybill_goods['.$goods['id'].'][price]" value="'.$goods['price'].'" ><input type="checkbox" name="waybill_goods_packageid[]" value="'.$goods['id'].'" ><input type="hidden" name="waybill_goods['.$goods['id'].'][packageid]" value="'.$goods['packageid'].'" ><input type="hidden" name="waybill_goods['.$goods['id'].'][goodsname]" value="'.$goods['goodsname'].'" ><input type="hidden" name="waybill_goods['.$goods['id'].'][returncode]" value="'.$goods['returncode'].'" ></td>
			<td align="center" height=22>';
			
			echo $goods['expressname'].'/';
			echo $goods['expressno'];
			echo '<input id="expressno'.$goods['id'].'" name="waybill_goods['.$goods['id'].'][expressno]" type="hidden"  value="'.$goods['expressno'].'"/><input id="expressid'.$goods['id'].'" name="waybill_goods['.$goods['id'].'][expressid]" type="hidden"  value="'.$goods['expressid'].'"/><input id="expressname'.$goods['id'].'" name="waybill_goods['.$goods['id'].'][expressname]" type="hidden"  value="'.$goods['expressname'].'"/></td>
			<td align="center">'.$goods['goodsname'].'</td>
			<td align="center">'.$goods['weight'].'</td>
			<td align="center">'.$goods['amount'].'</td>
			<td align="center">'.$goods['price'].'</td>
			<td align="center"><input id="returnfee'.$goods['id'].'" name="waybill_goods['.$goods['id'].'][returnfee]" type="text"  value="'.$goods['returnfee'].'"/></td>
			<td align="center"><textarea id="returnremark'.$goods['id'].'" name="waybill_goods['.$goods['id'].'][returnremark]"  >'.$goods['returnremark'].'</textarea></td>
            </tr>';
					
					}
					
					?>
					</table>
	</td>
	</tr>
	
	
	
	
	</tbody>
</table>
<input type="submit" name="dosubmit" id="dosubmit" value=" <?php echo L('ok')?> " class="dialog">&nbsp;<input type="reset" class="dialog" value=" <?php echo L('clear')?> ">
<input type="hidden" name="waybill_goods_count" value="<?php echo count($allwaybill_goods);?>"/>
<input type="hidden" name="waybillid" value="<?php echo $an_info['waybillid'];?>"/>
</form>
</div>
<script type="text/javascript">



function chkselpackage(){
	window.top.art.dialog({id:'kaiban_handle'}).close();
	var str="";
    $(":checkbox[name^='waybill_goods'][checked]").each(function(){
		if(str=="")
			str+=$(this).val();
		else
			str+=","+$(this).val();
    });
	if(str==""){
		
		window.top.art.dialog({content:"<?php echo L('please_select').L('returned_package').L('package');?>",lock:true,width:'220',height:'70'}, function(){});

		return false;
	}else{
		return true;
	}
}

function getusername(userid){
	if(userid){
		$.post("?m=package&c=admin_package&a=returned&checkhash=<?php echo $_SESSION['checkhash'];?>&uid="+userid,function(data){
			$("#username").html(data);
		});
		$.post("?m=package&c=admin_package&a=returned&checkhash=<?php echo $_SESSION['checkhash'];?>&tid="+userid,function(data){
			$("#truename").html(data);
		});
	}
}
getusername(<?php echo $an_info['userid'];?>);
</script>
</body>
</html>
