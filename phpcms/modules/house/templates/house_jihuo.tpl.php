<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');

include $this->admin_tpl('header', 'house');
?>

<form name="myform" action="?m=house&c=admin_house&a=listorder" method="post">
<div class="table-list">
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
            <th width="35" align="center"><input type="checkbox"  id="check_box" onclick="selectall('aid[]');"></th>	
			<th align="center"><?php echo L('number')?></th>
		
			<th align="center"><?php echo L('username')?></th>
			<th align="center"><?php echo L('truename')?></th>
			<th align="center"><?php echo "运单号";?></th>
			<th align="center"><?php echo L('expressno')?></th>
			
			<th align="center"><?php echo L('amount')?></th>
			<th align="center"><?php echo L('weight')?></th>
			
			<th align="center"><?php echo L('price')?></th>
			<th align="center">LXWXH</th>
			<th align="center"><?php echo L('goodsname')?></th>
			<th align="center"><?php echo L('status')?></th>
			<th align="center"><?php echo L('inputtime')?></th>
			<!--<th align="center"><?php echo L('operatetime')?></th>
			<th align="center"><?php echo L('operator')?></th>-->
			<th align="center"><?php echo L('operations_manage')?></th>
            </tr>
        </thead>
    <tbody>
 <?php 
if(is_array($datas)){
	$m_db = pc_base::load_model('member_model');
	$m_db->set_model(10);

	foreach($datas as $pack){

	
	$styles="";
	if( $pack['status']==2 && round((time()-$pack['addtime'])/3600/24)>$overday){$styles= ' style="background:#ffcc99"';}
	if( $pack['status']==1 ){$styles= ' style="background:#CAFF70"';}

echo '<tr '.$styles.'>
	<td align="center">';

	 if($pack['issystem']!='1'){
	echo '<input type="checkbox" name="aid[]" value="'.$pack['aid'].'" >';
	 }
	 echo '
	</td>
	<td align="center">'.$pack['aid'].'</td>

	<td align="center">'.$pack['username'].'</td>
	<td align="center">'.$pack['truename'].'</td>
	<td align="center">'.$pack['waybillid'].'</td>
	<td align="center">'.$pack['expressname'].$pack['expressno'].'</td>
	
	<td align="center">'.$pack['totalamount'].'</td>
	<td align="center">'.$pack['totalweight'].'</td>
	
	<td align="center">'.$pack['totalprice'].'</td>
	<td align="center">'.$pack['bill_long'].'x'.$pack['bill_width'].'x'.$pack['bill_height'].'</td>
	<td align="left" title="'.$pack['goodsname'].'"><div >'.$pack['goodsname'].'</div></td>
	<td align="center">'.L('waybill_status'.$pack['status']).'</td>
	<td align="center">'.date('Y-m-d H:i:s', $pack['addtime']).'</td>
	
	<td align="center">
	 <a href="javascript:edit('.$pack['aid'].', \''.safe_replace($pack['expressno']).'\',0);void(0);">'.L('edit').'</a> &nbsp;&nbsp;';
	 
	 if($pack['status']==0 || $pack['status']==1){
		echo '<a href="javascript:instorage('.$pack['aid'].', \''.safe_replace($pack['waybillid']).'\',3);void(0);">'.L('put_in_storage').'</a> ';
	 }

	
	 echo '
	</td>
	</tr>';
 
	}
}
?>
</tbody>
    </table>
  
    <div class="btn" style="display:;"><label for="check_box"><?php echo L('selected_all')?>/<?php echo L('cancel')?></label>
    

	   <input <?php if($status!=99){echo 'style="display:none;"';}?> name="submit" type="submit" class="button" value="<?php echo L('remove_all_selected')?>" onClick="document.myform.action='?m=package&c=admin_package&a=delete';return confirm('<?php echo L('affirm_delete')?>')">&nbsp;&nbsp;
	   
	   </div>  </div>
 <div id="pages"><?php echo $this->packagedb->pages;?>&nbsp;&nbsp;<input type="text" value="" size=6 id="pnum"/> <input type="button" value="GO" onclick="window.location.href='<?php echo '/index.php?m=house&c=admin_house&a=house_jihuo&hid='.$this->hid.'&act=&keywords='.$keywords.'&_number='.$_number.'&userid='.$userid.'&username='.$username.'&truename='.$truename.'&status='.$status.'&start_time='.$start_time.'&end_time='.$end_time.'&gotosearch='.$gotosearch.'&checkhash='.$_GET['checkhash'];?>&page='+document.getElementById('pnum').value;"/></div>
</form>
</div>
</body>
</html>
<script type="text/javascript">
function edit(id, title,status) {
	window.top.art.dialog({id:'edit'}).close();
	window.top.art.dialog({title:'<?php echo L('edit_package')?>--'+title, id:'edit', iframe:'?m=package&c=admin_package&a=edit&sta='+status+'&aid='+id ,width:'700px',height:'500px'}, function(){var d = window.top.art.dialog({id:'edit'}).data.iframe;
	var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'edit'}).close()});
}

function instorage(id, title,status) {
	window.top.art.dialog({id:'edit_pack'}).close();
	window.top.art.dialog({title:'<?php echo L('edit_package')?>--'+title, id:'edit_pack', iframe:'?m=package&c=admin_package&a=edit_pack&sta='+status+'&aid='+id ,width:'700px',height:'500px'}, function(){var d = window.top.art.dialog({id:'edit_pack'}).data.iframe;
	var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'edit_pack'}).close()});
}

</script>