<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<div class="pad-lr-10">
<form name="myform" action="?m=storage&c=admin_storage&a=listorder" method="post">
<div class="table-list">
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
            <th width="35" align="center"><input type="checkbox" value="" id="check_box" onclick="selectall('aid[]');"></th>
			<th width="69" align="center"><?php echo L('number')?></th>
			<th align="center"><?php echo L('storagename')?></th>
			<th width="100" align="center"><?php echo L('storagecode')?></th>
			<th width='168' align="center"><?php echo L('address')?></th>
			<th width="100" align="center"><?php echo L('instoragefee')?></th>
			<th width="100" align="center"><?php echo L('savestoragefee')?></th>
			<th width="100" align="center"><?php echo L('changestoragefee')?></th>
			<th width="100" align="center"><?php echo L('operatefee')?></th>
			<th width="69" align="center"><?php echo L('operations_manage')?></th>
            </tr>
        </thead>
    <tbody>
 <?php 
if(is_array($data)){
	foreach($data as $storage){
echo '<tr>
	<td align="center">
	<input type="checkbox" name="aid[]" value="'.$storage['aid'].'">
	</td>
	<td align="center">'.$storage['aid'].'</td>
	<td align="center">'.$storage['title'].'</td>
	<td align="center">'.$storage['storagecode'].'</td>
	<td align="center">'.$storage['address'].'</td>
	<td align="center">'.$storage['instoragefee'].'</td>
	<td align="center">'.$storage['savestoragefee'].'</td>
	<td align="center">'.$storage['changestoragefee'].'</td>
	<td align="center">'.$storage['operatefee'].'</td>
	<td align="center">
	<a href="javascript:edit('.$storage['aid'].', \''.safe_replace($storage['title']).'\');void(0);">'.L('edit').'</a>
	</td>
	</tr>'; 
	}
}
?>
</tbody>
    </table>
  
    <div class="btn"><label for="check_box"><?php echo L('selected_all')?>/<?php echo L('cancel')?></label>
       <input name="submit" type="submit" class="button" value="<?php echo L('remove_all_selected')?>" onClick="document.myform.action='?m=storage&c=admin_storage&a=delete';return confirm('<?php echo L('affirm_delete')?>')">&nbsp;&nbsp;</div>  </div>
 <div id="pages"><?php echo $this->db->pages;?></div>
</form>
</div>
</body>
</html>
<script type="text/javascript">
function edit(id, title) {
	window.top.art.dialog({id:'edit'}).close();
	window.top.art.dialog({title:'<?php echo L('edit_storage')?>--'+title, id:'edit', iframe:'?m=storage&c=admin_storage&a=edit&aid='+id ,width:'700px',height:'500px'}, function(){var d = window.top.art.dialog({id:'edit'}).data.iframe;
	var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'edit'}).close()});
}
</script>