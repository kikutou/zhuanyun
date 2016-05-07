<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<div class="pad-10">
<form method="post" action="?m=waybill&c=admin_waybill&a=waybill_morepay&ids=<?php echo $_GET['ids'];?>" name="myform" id="myform">
<table class="table_form" width="100%" cellspacing="0">
<tbody>
	<tr style="display:none;">
		<th><strong><?php echo L('pay_note');?>：</strong></th>
		<td style="color:#ff6600"><?php echo L('tips_pay_1');?></td>
	</tr>
	<tr>
		<th><strong><?php echo L('selected_records');?>：</strong></th>
		<td><?php echo str_replace("_",",",$_GET['ids']);?></td>
	</tr>
		<tr style="display:none;">
		<th><strong><?php echo L('pay_waybill');?>：</strong></th>
		<td>
		<textarea name="data[waybillid]" id="waybillid" cols="40" rows="13" readonly><?php echo $sno;?></textarea>
		</td>
	</tr>
	<tr>
	<td colspan=2>
	
	<table class="table-date">
			                                    
			                                        <thead>
			                                        	<tr>
                                                         
														<th scope="col" >订单号</th>
														<th scope="col" align=center><center>发货渠道</center></th>
														<th scope="col" >预期重量</th>
														<th scope="col" >预估运费</th>
														<th scope="col" >其它费用</th>
														<th scope="col" >总费用(USD) </th>
														<th scope="col" >总费用(RMB) </th>
			                                        	</tr>
			                                        </thead>
			                                        <tbody>
			                                        
			                                      
													<?php
													foreach($datass as $package){
														echo '<tr>
																	
																	<td scope="col" ><input type="hidden" name="aid[]" value="'.$package[aid].'">'.$package[waybillid].'</td>
																	<td scope="col" >'.$package[shippingname].'</td>
																	<td scope="col" >'.$package[totalweight].'</td>
																	<td scope="col" >'.$package[totalship].'</td>
																	<td scope="col" >'.$package[allvaluedfee].'</td>
																	<td scope="col" >'.$package[payfeeusd].'</td>
																	<td scope="col" >'.$package[payedfee].'</td>
																</tr>';
													
													}
													?>
													
															

																
												
			                                        </tbody>
			                                    </table>
	</td>
	</tr>
	
	<tr>
		<th><strong><?php echo L('common_send_email_notify');?>：</strong></th>
		<td><input name="send_email_nofity" id="send_email_nofity1"  type="radio"  value="1" checked><?php echo L('Yes');?>&nbsp;&nbsp;<input name="send_email_nofity" id="send_email_nofity0"  type="radio"  value="0" ><?php echo L('No');?>&nbsp;&nbsp;</td>
	</tr>
	<tr>
		<th><strong><?php echo L('pay_operator')?>：</strong></th>
		<td><?php echo $this->username;?></td>
	</tr>

	<tr>
  		<th><strong><?php echo L('remark')?>：</strong></th>
        <td>
		<textarea name="data[remark]" id="remark" cols="50" rows=2></textarea>
		</td>
	</tr>
	</tbody>
</table>
<input type="submit" name="dosubmit" id="dosubmit" value=" <?php echo L('ok')?> " class="dialog">&nbsp;<input type="reset" class="dialog" value=" <?php echo L('clear')?> ">
</form>
</div>
</body>
</html>
