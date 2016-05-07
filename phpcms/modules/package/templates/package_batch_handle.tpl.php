<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<div class="pad-10">
<form method="post" action="/index.php?m=package&c=admin_package&a=<?php echo $_GET['a']?>&aid=<?php echo $idd?>&hid=<?php echo $this->hid;?>" name="myform2" id="myform2" >

	
 <table border=1 width="100%" align="center" style="border:solid 1px #ccc;" >
					<tr >
					<td align=center >运单号</td>
					<td align=center height="25px" ><?php echo L('expressno')?></td>
					<td align=center ><?php echo L('goodsname')?></td>
					<td align=center><?php echo L('weight_list')?></td>
					<td align=center ><?php echo L('amount_list')?></td>
					<td align=center ><?php echo L('price_list')?></td>
					<td align=center >货物长宽高</td>
					<td align=center ><?php echo L('remark')?></td>
					</tr>
					<?php
					
					foreach($datas as  $k=>$goods ){
						echo '<tr class="goods__row">
						<td align=center><input type="hidden" name="waybill_goods['.$goods['aid'].'][status]" value="3" ><input type="hidden" name="aid[]" value="'.$goods['aid'].'" >'.$goods['waybillid'].'</td>
			<td align="center" height=22>';
			
			echo $goods['expressname'].'/';
			echo $goods['expressno'];
			echo '</td>
			<td align="center">'.$goods['goodsname'].'</td>
			<td align="center"><input type="text" name="waybill_goods['.$goods['aid'].'][totalweight]" value="'.$goods['totalweight'].'" size=5></td>
			<td align="center"><input type="text" name="waybill_goods['.$goods['aid'].'][totalamount]" value="'.$goods['totalamount'].'" size=5></td>
			<td align="center"><input type="text" name="waybill_goods['.$goods['aid'].'][totalprice]" value="'.$goods['totalprice'].'" size=5></td>
			<td align="center">L<input type="text" name="waybill_goods['.$goods['aid'].'][bill_long]" value="'.$goods['bill_long'].'" size=3> x W<input type="text" name="waybill_goods['.$goods['aid'].'][bill_width]" value="'.$goods['bill_width'].'" size=3> x H<input type="text" name="waybill_goods['.$goods['aid'].'][bill_height]" value="'.$goods['bill_height'].'" size=3></td>
			<td align="center"><textarea id="returnremark'.$goods['aid'].'" name="waybill_goods['.$goods['aid'].'][otherremark]"  >'.$goods['otherremark'].'</textarea></td>
            </tr>';
					
					}
					
					?>
					</table>

<input type="submit" name="dosubmit" id="dosubmit" value=" <?php echo L('ok')?> " class="dialog">&nbsp;<input type="reset" class="dialog" value=" <?php echo L('clear')?> ">
<input type="hidden" name="waybill_goods_count" value="<?php echo count($allwaybill_goods);?>"/>
<input type="hidden" name="waybillid" value="<?php echo $an_info['waybillid'];?>"/>
</form>
</div>
<script type="text/javascript">


</script>
</body>
</html>
