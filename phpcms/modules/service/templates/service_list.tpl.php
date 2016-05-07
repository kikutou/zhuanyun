<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<div class="pad-lr-10">
<form name="myform" action="?m=service&c=admin_service&a=listorder" method="post">
<div class="table-list">
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
            <th width="35" align="center"><input type="checkbox" value="" id="check_box" onclick="selectall('aid[]');"></th>
			<th width="69" align="center"><?php echo L('number')?></th>
			<th width="100" align="center"><?php echo L('service_category')?></th>
			<th align="center" width="100" ><?php echo L('service_name')?></th>
			<th align="center" width="80" ><?php echo L('service_price')?></th>
			<th  align="center"><?php echo L('service_remark')?></th>
			<th width="69" align="center"><?php echo L('operations_manage')?></th>
            </tr>
        </thead>
    <tbody>
 <?php 
if(is_array($data)){
	foreach($data as $service){


echo '<tr>
	<td align="center">
	<input type="checkbox" name="aid[]" value="'.$service['aid'].'">
	</td>
	<td align="center">'.$service['aid'].'</td>
	<td align="center">'.$this->getvaluetype($service['type']).'</td>
	<td align="center">'.$service['title'].'</td>
	<td align="center">'.$this->getcurrencyname($service['currencyid']).$service['price']."/".$this->getunitname($service['unit']).'</td>
	<td align="left">'.$service['remark'].'</td>
	<td align="center">
	<a href="javascript:edit('.$service['aid'].', \''.safe_replace($service['title']).'\');void(0);">'.L('edit').'</a>
	</td>
	</tr>';

	}
}
?>
</tbody>
    </table>
  
    <div class="btn"><label for="check_box"><?php echo L('selected_all')?>/<?php echo L('cancel')?></label>
		<input name="submit" type="submit" class="button" value="<?php echo L('remove_all_selected')?>" onClick="document.myform.action='?m=service&c=admin_service&a=delete';return confirm('<?php echo L('affirm_delete')?>')">&nbsp;&nbsp;</div>  </div>
 <div id="pages"><?php echo $this->db->pages;?></div>
</form>
</div>
</body>
</html>
<script type="text/javascript">
function edit(id, title) {
	window.top.art.dialog({id:'edit'}).close();
	window.top.art.dialog({title:'<?php echo L('edit_service')?>--'+title, id:'edit', iframe:'?m=service&c=admin_service&a=edit&aid='+id ,width:'600px',height:'350px'}, function(){var d = window.top.art.dialog({id:'edit'}).data.iframe;
	var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'edit'}).close()});
}
</script>