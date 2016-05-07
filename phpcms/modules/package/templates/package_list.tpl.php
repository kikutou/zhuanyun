<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<div class="pad-lr-10">
<form name="myform" action="?m=package&c=admin_package&a=listorder" method="post">
<div class="table-list">
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
            <th width="35" align="center"><input type="checkbox" value="" id="check_box" onclick="selectall('aid[]');"></th>
			<th width="69" align="center"><?php echo L('num')?></th>
			<th align="center">运单号</th>
			<th align="center"><?php echo L('expressno')?></th>
			<th align="center"><?php echo L('amount')?></th>
			<th  align="center"><?php echo L('weight')?></th>
			<th  align="center"><?php echo L('price')?></th>
			<th  align="center"><?php echo L('remark')?></th>
			<th  align="center"><?php echo L('inputtime')?></th>
			<th width="69" align="center"><?php echo L('operations_manage')?></th>
            </tr>
        </thead>
    <tbody>
 <?php 
if(is_array($datas)){
	foreach($datas as $package){
		$bgc='';
if($package['status']==1){
$bgc=' style="background:#CAFF70"';
}

echo '<tr '.$bgc.'>
	<td align="center"><input type="checkbox" name="aid[]" value="'.$package['aid'].'"></td>
	<td align="center">'.$package['aid'].'</td>
	<td align="center">'.$package['waybillid'].'</td>
	<td align="center">'.$package['expressno'].'</td>
	<td align="center">'.$package['totalamount'].'</td>
	<td align="center">'.$package['totalweight'].'</td>
	<td align="center">'.$package['totalprice'].'</td>
	<td align="center">'.$package['remark'].'</td>
	<td align="center">'.date('Y-m-d H:i:s', $package['addtime']).'</td>
	<td align="center">
	<a href="javascript:edit('.$package['aid'].', \''.safe_replace($package['waybillid']).'\');void(0);">'.L('edit').'</a>
	</td>
	</tr>'; 
	}
}
?>
</tbody>
    </table>
  
    <div class="btn"><label for="check_box"><?php echo L('selected_all')?>/<?php echo L('cancel')?></label>
       <input name="submit" type="submit" class="button" value="<?php echo L('remove_all_selected')?>" onClick="document.myform.action='?m=package&c=admin_package&a=delete';return confirm('<?php echo L('affirm_delete')?>')">&nbsp;&nbsp;</div>  </div>
 <div id="pages"><?php echo $this->db->pages;?></div>
</form>
</div>
</body>
</html>
<script type="text/javascript">
function edit(id, title) {
	window.top.art.dialog({id:'edit'}).close();
	window.top.art.dialog({title:'<?php echo L('edit_package')?>--'+title, id:'edit', iframe:'?m=package&c=admin_package&a=edit&aid='+id ,width:'600px',height:'520px'}, function(){var d = window.top.art.dialog({id:'edit'}).data.iframe;
	var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'edit'}).close()});
}
</script>