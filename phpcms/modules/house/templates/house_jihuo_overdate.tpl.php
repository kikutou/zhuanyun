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
			<th align="center">UserID</th>
			<th align="center"><?php echo L('username')?></th>
			<th align="center"><?php echo L('truename')?></th>
			<th align="center"><?php echo L('expressno')?></th>
			<th align="center"><?php echo L('expressid')?></th>
			<th align="center"><?php echo L('amount')?></th>
			<th align="center"><?php echo L('weight')?></th>
			<th align="center"><?php echo L('price')?></th>
			<th align="center"><?php echo L('goodsname')?></th>
			<th align="center"><?php echo L('status')?></th>
			<th align="center"><?php echo L('operatetime')?></th>
			<th align="center"><?php echo L('operations_manage')?></th>
            </tr>
        </thead>
    <tbody>
 <?php 
if(is_array($datas)){

	foreach($datas as $pack){
		
echo '<tr>
	<td align="center">';
	 if($pack['issystem']!='1'){
		echo '<input type="checkbox" name="aid[]" value="'.$pack['aid'].'" >';
	 }

	echo '</td>
	<td align="center">'.$pack['userid'].'</td>
	<td align="center">'.$pack['username'].'</td>
	<td align="center">'.$pack['truename'].'</td>
	<td align="center">'.$pack['expressno'].'</td>
	<td align="center">'.$pack['expressname'].'</td>
	<td align="center">'.$pack['amount'].'</td>
	<td align="center">'.$pack['weight'].'</td>
	<td align="center">'.$pack['price'].'</td>
	<td align="left" title="'.$pack['goodsname'].'"><div style="width:120px;height:35px;overflow:hidden;" >'.$pack['goodsname'].'</div></td>
	<td align="center">'.$this->getonestatus($pack['status']).'</td>
	<td align="center">'.date('Y-m-d H:i:s', $pack['addtime']).'</td>
	<td align="center">
	 <a href="javascript:edit('.$pack['aid'].', \''.safe_replace($pack['expressno']).'\');void(0);">'.L('edit').'</a> 
	</td>
	</tr>';

	}
}
?>
</tbody>
    </table>
  
    <div class="btn"><label for="check_box"><?php echo L('selected_all')?>/<?php echo L('cancel')?></label>
       <input name="submit" type="submit" class="button" value="<?php echo L('remove_all_selected')?>" onClick="document.myform.action='?m=package&c=admin_package&a=delete';return confirm('<?php echo L('affirm_delete')?>')">&nbsp;&nbsp;</div>  </div>
 <div id="pages"><?php echo $this->packagedb->pages;?></div>
</form>
</div>
</body>
</html>
<script type="text/javascript">
function edit(id, title) {
	window.top.art.dialog({id:'edit'}).close();
	window.top.art.dialog({title:'<?php echo L('edit_package')?>--'+title, id:'edit', iframe:'?m=package&c=admin_package&a=edit&aid='+id ,width:'500px',height:'450px'}, function(){var d = window.top.art.dialog({id:'edit'}).data.iframe;
	var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'edit'}).close()});
}


</script>