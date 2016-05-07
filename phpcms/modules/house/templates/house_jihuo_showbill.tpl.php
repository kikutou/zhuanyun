<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<style>
.curr{font-weight:bold;}
.hide{display:none;}
</style>


<script>$('.content-menu').remove();</script>
<div class="pad-lr-10">
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td><div class="explain-col" style="font-weight:bold;"> 
		 <?php echo L('sysbillid').':'.$info['waybillid'];?>
		</td>
		</tr>
    </tbody>
</table>
<div class="table-list">
<table width="100%" border="0" cellpadding="0" cellspacing="1" bgcolor="#AACDED">
							<tr>
								<td width="13%" height="24" align="right" bgcolor="#EBF4FB">用户标识：</td>
								<td width="40%" bgcolor="#EBF4FB"><span style="color:red;font-size:16px;font-weight:bold;"><?php echo $info['takeflag'];?></span>
								</td>
								<td width="13%" align="right" bgcolor="#F4F5F9"><?php echo L('bill_waytype');?></td>
								<td width="34%" bgcolor="#F4F5F9"><?php echo $info['shippingname'];?></td>
							</tr>
							<tr>
								<td width="13%" height="24" align="right" bgcolor="#EBF4FB">编号：</td>
								<td width="40%" bgcolor="#EBF4FB"><span style="color:red;font-size:16px;font-weight:bold;">#<?php echo $info['aid'];?></span></td>

								<td width="13%" height="24" align="right" bgcolor="#EBF4FB"><?php echo L('bill_username');?></td>
								<td width="40%" bgcolor="#EBF4FB"><?php echo $info['username'];?></td>							
							</tr>
							<tr style="display:none;">
								<td width="13%" height="24" align="right" bgcolor="#F4F5F9"><?php echo L('bill_storageid');?></td>
								<td width="40%" bgcolor="#F4F5F9"><?php echo $info['storagename'];?></td>
								<td width="13%" align="right" bgcolor="#EBF4FB"><?php echo L('bill_phone');?></td>
								<td width="34%" bgcolor="#EBF4FB"><?php echo $info['mobile'];?></td>
							</tr>

							<tr>

								<td width="13%" align="right" bgcolor="#EBF4FB"><?php echo L('bill_takeid');?></td>
								<td width="34%" bgcolor="#EBF4FB"><span style="color:blue;font-weight:bold;"><?php echo $info['takename'];?></span></td>
								<td width="13%" align="right" bgcolor="#EBF4FB"><?php echo L('truename');?>：</td>
								<td width="34%" bgcolor="#EBF4FB"><?php echo $info['truename'];?></td>

							</tr>
                          
                            <tr>
								<td width="13%" height="24" align="right" bgcolor="#F4F5F9" ><?php echo L('bill_remark');?></td>
								<td colspan="3" bgcolor="#F4F5F9"><span style="color:blue;font-weight:bold;"><?php echo $info['remark'];?></span></td>
							</tr>
							
	  </table>
</div>
<br>
<div class="table-list ">
    <table width="100%" cellspacing="0" style="border:solid 1px #d5dfe8;">
        <thead>
			<tr>
			<td align="left" style="line-height:25px;font-weight:bold;border-bottom:none;  word-break: break-all;  padding-right: 8px;" colspan="9">			
			
			邮单号 <?php echo $info['expressno']?>
		
			</tr>
		
            <tr>
		
			<th align="center"><?php echo L('bill_goodsname')?></th>
			<th align="center"><?php echo L('bill_amount')?></th>
			<th align="center"><?php echo L('bill_price')?></th>
			<th align="center" style="display:none;"><?php echo L('bill_weight')?></th>
			
            </tr>
        </thead>
    <tbody>

<!---增值明细，验货照片--->
		<?php
	 $goodsdatas=string2array($info['goodsdatas']);
	 foreach($goodsdatas as $goods){
		echo '<tr>
			<td align="center">'.$goods['goodsname'].'</td>
			<td align="center">'.$goods['amount'].'</td>
			<td align="center">'.$goods['price'].'</td>
			<td align="center" style="display:none;">'.$goods['weight'].'</td>';
			
			
			
			echo '
            </tr>';
			
	 }

		
		
		
		
echo '<tr>
			<td height="70" style="line-height:25px;font-weight:bold;" colspan="9" align=left> '.L('price_list').'：'.$info['totalprice'].'&nbsp;<span style="color:red;font-weight:bold">'.L('weight_list').'：'.$info['totalweight'].'g</span>&nbsp;</td>
			</tr>';

echo '
	</tbody>
    </table>
	';
	
	
if($info['returnedstatus']!=0){
echo '<br>
<table width="100%" cellspacing="0" style="border:solid 1px #d5dfe8;">
 <thead>
  <tr><th align="left" style="font-weight:bold;">'.L('house_jihuo_returned_information').'</th></tr>
 </thead>
 <tbody>
 <tr><td >'.L('returned_bill_returnfee').'：'.$allwaybill_goods[0]['returnfee'].'</td></tr>
 <tr><td >'.L('returned_bill_returntime').'：'.date('Y-m-d H:i:s',$allwaybill_goods[0]['returntime']).'</td></tr>
 <tr><td >'.L('returned_bill_returnname').'：'.$allwaybill_goods[0]['returnname'].'</td></tr>
 <tr><td >'.L('returned_back_address').'：'.$allwaybill_goods[0]['return_address'].'</td></tr>
 <tr><td >'.L('returned_back_person').'：'.$allwaybill_goods[0]['return_person'].'</td></tr>
 <tr><td >'.L('returned_back_mobile').'：'.$allwaybill_goods[0]['return_mobile'].'</td></tr>
 <tr><td >User '.L('returned_bill_returnremark').'：'.$allwaybill_goods[0]['returnremarkf'].'</td></tr>
 <tr><td >Admin '.L('returned_bill_returnremark').'：'.$allwaybill_goods[0]['returnremark'].'</td></tr>
</tbody>
</table>';
	
}	
	
	
	echo '
	
<br>
<table width="100%" cellspacing="0" style="border:solid 1px #d5dfe8;">

			                                    <thead>
													<tr><th align="left" style="font-weight:bold;">'.L('value_service_detail').'</th></tr>
			                                    </thead>
			                                        <tbody>
													<tr>	
			                                            <td scope="col" >';

						$allsrv=string2array($info['addvalues']);
						foreach($allsrv as  $srv){
						
							echo $srv['title'];

							if ($srv[servicetypeid]==13){
								$srv['price'] = $srv['price']." ".$info['otherfee'];
							}
							
							echo '<font color="#FF953F">'.$srv['currencyname'].$srv['price'].'</font>/';
								
							echo $srv['unitname']; 

						}

						
							echo '	</td>
			                                        </tr>
												</table>
												<br>
<table width="100%" cellspacing="0" style="border:solid 1px #d5dfe8;">
 <thead>
													<tr><th align="left" style="font-weight:bold;">'.L('check_picture').'</th></tr>
			                                    </thead>
												<tbody><tr>	
			                                            <td scope="col" >';
														if($info['picture']){
						echo '<p><a href="'.$info['picture'].'" target=_blank> <img src="'.$info['picture'].'" border=0 /></a></p>';
						}


						if($info['imagesurl']!=""){
							$k=1;
							$imagearr = string2array($info['imagesurl']);
							foreach($imagearr as $img){
								echo '<a href="'.$img.'" target=_blank> <img style="max-height:200px;_height:expression(this.height > 200 ? "200px" : this.height);" src="'.$img.'" border=0 /></a>&nbsp;';
								if($k%5==0){
									echo '<br/><br/>';
								}
								$k++;
							}
						}

							echo '</td></tr></tbody></table><br>';

	
		$takeaddress=str_replace('|','&nbsp;&nbsp;',$info['takeaddressname']);
	
	
		$sendaddress=str_replace('|','&nbsp;&nbsp;',$info['sendaddressname']);
	


?>
<!---增值明细，验货照片--->
<!---运费详情--->
 <table width="100%" cellspacing="0" style="border:solid 1px #d5dfe8;">
        <thead>
            <tr>
			<th align="left" colspan=6 style="font-weight:bold;">运单号：<?php echo $info['waybillid'];?> 收件人地址、费用、跟踪信息</th>
            </tr>
        </thead>
    <tbody>
		
			 <tr style="display:none">
			<td align="right"><?php echo L('actual_weight_b');?>：</td>
			<td align="left"><?php echo $info['totalweight'];?></td>
			<td align="right"><?php echo L('goodsname');?>：</td>
			<td align="left"><?php echo $info['goodsname'];?></td>
			<td align="right"></td>
			<td align="left"></td>
            </tr>	
			<tr style="display:none">
			<td align="right"><?php echo L('shipping_address');?>：</td>
			<td align="left" colspan=4><?php echo $sendaddress;?></td>
            </tr>
			<tr>
			<td align="right"><?php echo L('delivery_address');?>：</td>
			<td align="left" colspan=4><?php echo $takeaddress;?></td>
            </tr>
			<tr style="display:none;">
			<td align="right"><?php echo L('volume_weight_p');?>：</td>
			<td align="left" colspan=4><?php echo L('house_long');?>&nbsp;<?php echo $info[bill_long];?>&nbsp;X&nbsp;<?php echo L('house_width');?>&nbsp;&nbsp;<?php echo $info[bill_width];?>&nbsp;X&nbsp;<?php echo L('house_height');?>&nbsp;&nbsp;<?php echo $info[bill_height];?>&nbsp;/&nbsp;<?php echo WB_VOL.' / '.WB_VOL_CM;?>&nbsp;=&nbsp;&nbsp;&nbsp;<?php echo $info[volumeweight];?></td>
            </tr>
			   <?php if($info['volumefee']>0){
			   echo '<tr>			
													<td height="30" align="right" bgcolor="#EBF4FB">'.L('volume_fee').'：&nbsp;</td>		
													<td height="30" colspan="4" bgcolor="#EBF4FB" ><input type="text" name="volumefee" value="'.$info[volumefee].'" class="inp" size=4 readonly />'.L('yuan').'</td>		
													</tr>';
			   
			   }

			   echo '<tr>			
					<td height="30" align="right" >'.L('totalshipfee_1').'(円)：&nbsp;</td>		
					<td height="30" colspan="4"  >'.L('totalshipfee').'<input type="text" name="totalship" value="'.$info[totalship].'" class="inp" size=5  readonly/>  ';
					
					$payedfee = $info['payfeeusd'];
					
					$payedfeermb = $info['payedfee'];
					$taxfee2 = $info['taxfee']/$info['wayrate'];


					echo L('total_value_added_costs').'<input type="text" name="valuedadd" value="'.$info[allvaluedfee].'" class="inp" size=5 readonly />
					保险费(円)<input type="text" name="otherfee" value="'.$info[otherfee].'" class="inp" size=5 readonly /> &nbsp;
					操作费(円)<input type="text" name="tax_fee" value="'.$info[taxfee].'" class="inp" size=5 readonly /> &nbsp;';
					
					
					echo '</td>		
			</tr>';
													
													
				?>

			<tr>
			<td align="right"></td>
			<td align="left" colspan=5>

				&nbsp;<?php echo L('wayrate');?><input type="text" name="wayrate" value="<?php echo $info['wayrate'];?>" class="inp" size=6 readonly style="margin-top:5px;"/> <?php echo L('paid_freight');?>(円)<input type="text" name="payedfee" value="<?php echo $info['payfeeusd'];?>" class="inp" size=6 readonly style="margin-top:5px;"/> <?php echo L('paid_freight');?>(RMB)<input type="text" name="payedfeermb" value="<?php echo $info['payedfee'];?>" class="inp" size=6 readonly style="margin-top:5px;"/> 

			</td>
            </tr>
			
			
			
		
	</tbody>
    </table>
<!---运费详情--->
	
<br>

<!---走件流程--->
	<table cellpadding="3" cellspacing="1" width="100%" style="border:solid 1px #d5dfe8;"> 
	
	<thead>
  <tr style="background:#eeefff">
    <th align="center" height="25"><?php echo L('handletime');?></th>
	<th align="center" style="display:none;"><?php echo L('handleplace');?></th>
	<th align="center"><?php echo L('handleinformation');?></th>
	<th align="center"><?php echo L('handler');?></th>
	<th align="center"><?php echo L('operation');?></th>
  </tr>
   </thead>
  <?php
  $allhouse_showhandle = $this->house_showhandle($info['waybillid']);
  $siteinfos = siteinfo(1);
	$_edb = pc_base::load_model('enum_model');
	$handle_datas  = $_edb->select("groupid=52 ","*",1000,"listorder asc");	



	if ($siteinfos['wbinfocustom']==0){

  foreach($allhouse_showhandle as $val){
$isshow="";
	   if($val['isshow']=='1'){$isshow= 'style="background:#ffd8dd"';}

	echo '<tr '.$isshow.'>
    <th align="center" height="25">'.date('Y-m-d H:i:s',$val['addtime']).'</th>
	<th align="center" style="display:none;">'.$val['placename'].'</th>
	<th align="center">'.$val['remark'].'</th>
	<th align="center">'.$val['username'].'</th>
	<th align="center">';

	if($info['status']!=16){
		echo '<a href="?m=house&c=admin_house&a=house_history_delete&hid='.$this->hid.'&id='.$val['id'].'" onclick="return confirm(\''.L('affirm_delete').'\')">'.L('delete').'</a>';
	}

	echo '</th>
  </tr>';
  
  }

	}else{
		
		foreach($handle_datas as $hbill){
		foreach($allhouse_showhandle as $val){
		$isshow="";
			   if($val['status']==$hbill['value']){

				 
					$hds="";
					if($val['status']==9){
						$hds=$info['huodanno'];
					}
					
					if ($val[status]==14){
					$hds=$info[excompany].',单号:<a href="'.str_replace('#',$info[expressnumber],$info[expressurl]).'" target=_blank>'.$info[expressnumber].'</a>';
					}

					

			echo '<tr '.$isshow.'>
			<th align="center" height="25">'.date('Y-m-d H:i:s',$val['addtime']).'</th>
			<th align="center" style="display:none;">'.$hbill['title'].'</th>
			<th align="center">'.$hbill['remark'].'</th>
			<th align="center">'.$val['username'].'</th>
			<th align="center">';

			if($info['status']!=16){
				echo '<a href="?m=house&c=admin_house&a=house_history_delete&hid='.$this->hid.'&id='.$val['id'].'" onclick="return confirm(\''.L('affirm_delete').'\')">'.L('delete').'</a>';
			}

			echo '</th>
		  </tr>';}
		  
		  }
		}

	}



  ?>
 
  </table>
<!---走件流程--->
</div>
</div>

<p>&nbsp;</p>



</body>
</html>

