<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<div class="pad-lr-10">
<form name="myform" action="?m=ownaddress&c=admin_ownaddress&a=listorder" method="post">
<div class="table-list">
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
            <th width="35" align="center"><input type="checkbox" value="" id="check_box" onclick="selectall('aid[]');"></th>
			<th width="69" align="center"><?php echo L('num')?></th>
			<th  align="center"><?php echo L('packageno')?></th>
			<th  align="center"><?php echo L('title')?></th>
			<th  align="center"><?php echo L('shaddress')?></th>
			<th  align="center"><?php echo L('ispay')?></th>
			<th  align="center"><?php echo L('inputtime')?></th>
			<th  align="center"><?php echo L('remark')?></th>
			
			<th width="69" align="center"><?php echo L('operations_manage')?></th>
            </tr>
        </thead>
    <tbody>
 <?php 
if(is_array($data)){
	foreach($data as $ownaddress){
echo '<tr>
	<td align="center">
	<input type="checkbox" name="aid[]" value="'.$ownaddress['aid'].'">
	</td>
	<td align="center">'.$ownaddress['aid'].'</td>
	<td align="center">'.$ownaddress['packageno'].'</td>
	<td align="center">'.$ownaddress['title'].'</td>
	<td align="center">'.$ownaddress['address'].'</td>
	<td align="center">'.$ownaddress['addressusefee'].'</td>
	<td align="center">'.date('Y-m-d',$ownaddress['addtime']).'</td>
	<td align="center">'.$ownaddress['remark'].'</td>
	<td align="center">
	<a href="javascript:edit('.$ownaddress['aid'].', \''.safe_replace($ownaddress['title']).'\');void(0);">'.L('edit').'</a>
	</td>
	</tr>'; 
	}
}
?>
</tbody>
    </table>
  
    <div class="btn"><label for="check_box"><?php echo L('selected_all')?>/<?php echo L('cancel')?></label>
       <input name="submit" type="submit" class="button" value="<?php echo L('remove_all_selected')?>" onClick="document.myform.action='?m=ownaddress&c=admin_ownaddress&a=delete';return confirm('<?php echo L('affirm_delete')?>')">&nbsp;&nbsp;</div>  </div>
 <div id="pages"><?php echo $this->db->pages;?></div>
</form>
</div>
</body>
</html>
<script type="text/javascript">
function edit(id, title) {
	window.top.art.dialog({id:'edit'}).close();
	window.top.art.dialog({title:'<?php echo L('edit_ownaddress')?>--'+title, id:'edit', iframe:'?m=ownaddress&c=admin_ownaddress&a=edit&aid='+id ,width:'600px',height:'550px'}, function(){var d = window.top.art.dialog({id:'edit'}).data.iframe;
	var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'edit'}).close()});
}
</script>