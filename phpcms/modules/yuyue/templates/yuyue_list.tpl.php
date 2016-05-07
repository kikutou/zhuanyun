<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<div class="pad-lr-10">
<form name="myform" action="?m=yuyue&c=admin_yuyue&a=listorder" method="post">
<div class="table-list">
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
            <th width="35" align="center"><input type="checkbox" value="" id="check_box" onclick="selectall('aid[]');"></th>
			<th width="69" align="center"><?php echo L('number')?></th>
			<th align="center"><?php echo L('shippingtype')?></th>
			<th  align="center"><?php echo L('warehouse')?></th>
			<th  align="center"><?php echo L('yuyue_address')?></th>
			<th  align="center"><?php echo L('yuyuenumber')?></th>
			<th  align="center"><?php echo L('lianxiren')?></th>
			<th  align="center"><?php echo L('mobile')?></th>
			<th  align="center"><?php echo L('email')?></th>
			<th  align="center"><?php echo L('yuyuestatus')?></th>
			<th  align="center"><?php echo L('yuyuetime')?></th>
			<th width="69" align="center"><?php echo L('operations_manage')?></th>
            </tr>
        </thead>
    <tbody>
 <?php 
if(is_array($data)){
	foreach($data as $yuyue){

		$row = $this->getshippingtypename($yuyue['shippingtype']);
echo '<tr>
	<td align="center">
	<input type="checkbox" name="aid[]" value="'.$yuyue['aid'].'">
	</td>
	<td align="center">'.$yuyue['aid'].'</td>
	<td align="center">'.$row['storagename'].'</td>
	<td align="center">'.$row['title'].'</td>
	<td align="center">'.$yuyue['address'].'</td>
	<td align="center">'.$yuyue['number'].'</td>
	<td align="center">'.$yuyue['lianxiren'].'</td>
	<td align="center">'.$yuyue['mobile'].'</td>
	<td align="center">'.$yuyue['email'].'</td>
	<td align="center">&nbsp;</td>
	<td align="center">'.$yuyue['yuyuetime'].'</td>
	<td align="center">
	<a href="javascript:edit('.$yuyue['aid'].', \''.safe_replace($yuyue['title']).'\');void(0);">'.L('edit').'</a>
	</td>
	</tr>';
	}
}
?>
</tbody>
    </table>
  
    <div class="btn"><label for="check_box"><?php echo L('selected_all')?>/<?php echo L('cancel')?></label>
       <input name="submit" type="submit" class="button" value="<?php echo L('remove_all_selected')?>" onClick="document.myform.action='?m=yuyue&c=admin_yuyue&a=delete';return confirm('<?php echo L('affirm_delete')?>')">&nbsp;&nbsp;</div>  </div>
 <div id="pages"><?php echo $this->db->pages;?></div>
</form>
</div>
</body>
</html>
<script type="text/javascript">
function edit(id, title) {
	window.top.art.dialog({id:'edit'}).close();
	window.top.art.dialog({title:'<?php echo L('edit_yuyue')?>--'+title, id:'edit', iframe:'?m=yuyue&c=admin_yuyue&a=edit&aid='+id ,width:'600px',height:'420px'}, function(){var d = window.top.art.dialog({id:'edit'}).data.iframe;
	var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'edit'}).close()});
}
</script>