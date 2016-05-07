<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<div class="pad-lr-10">
<form name="myform" action="?m=coupon&c=admin_coupon&a=listorder" method="post">
<div class="table-list">
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
            <th width="35" align="center"><input type="checkbox" value="" id="check_box" onclick="selectall('aid[]');"></th>
			<th width="69" align="center"><?php echo L('number')?></th>
			<th align="center"><?php echo L('title')?></th>
			<th width="168" align="center"><?php echo L('remark')?></th>
			<th width='168' align="center"><?php echo L('price')?></th>
			<th width="150" align="center"><?php echo L('okdays')?></th>
			<th width="150" align="center"><?php echo L('minamount')?></th>
			<th width="120" align="center"><?php echo L('backpoints')?></th>
			<th width="69" align="center"><?php echo L('operations_manage')?></th>
            </tr>
        </thead>
    <tbody>
 <?php 
if(is_array($data)){
	foreach($data as $coupon){
echo '<tr>
	<td align="center">
	<input type="checkbox" name="aid[]" value="'.$coupon['aid'].'">
	</td>
	<td align="center">'.$coupon['aid'].'</td>
	<td align="center">'.$coupon['title'].'</td>
	<td align="center">'.$coupon['remark'].'</td>
	<td align="center">'.$coupon['price'].'</td>
	<td align="center">'.$coupon['okdays'].'</td>
	<td align="center">'.$coupon['minamount'].'</td>
	<td align="center">'.$coupon['backpoints'].'</td>
	<td align="center">
	<a href="javascript:edit('.$coupon['aid'].', \''.safe_replace($coupon['title']).'\');void(0);">'.L('edit').'</a>
	</td>
	</tr>'; 
	}
}
?>
</tbody>
    </table>
  
    <div class="btn"><label for="check_box"><?php echo L('selected_all')?>/<?php echo L('cancel')?></label>
        <input name="submit" type="submit" class="button" value="<?php echo L('remove_all_selected')?>" onClick="document.myform.action='?m=coupon&c=admin_coupon&a=delete';return confirm('<?php echo L('affirm_delete')?>')">&nbsp;&nbsp;</div>  </div>
 <div id="pages"><?php echo $this->db->pages;?></div>
</form>
</div>
</body>
</html>
<script type="text/javascript">
function edit(id, title) {
	window.top.art.dialog({id:'edit'}).close();
	window.top.art.dialog({title:'<?php echo L('edit_coupon')?>--'+title, id:'edit', iframe:'?m=coupon&c=admin_coupon&a=edit&aid='+id ,width:'600px',height:'350px'}, function(){var d = window.top.art.dialog({id:'edit'}).data.iframe;
	var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'edit'}).close()});
}
</script>