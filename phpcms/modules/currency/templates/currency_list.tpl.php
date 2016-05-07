<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<div class="pad-lr-10">
<form name="myform" action="?m=currency&c=admin_currency&a=listorder" method="post">
<div class="table-list">
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
            <th width="35" align="center"><input type="checkbox" value="" id="check_box" onclick="selectall('aid[]');"></th>
			<th width="69" align="center"><?php echo L('number')?></th>
			<th align="center"><?php echo L('currency')?></th>
			<th width="168" align="center"><?php echo L('code')?></th>
			<th width='168' align="center"><?php echo L('symbol')?></th>
			<th width="150" align="center"><?php echo L('exchangerate')?></th>
			<th width="150" align="center"><?php echo L('available_style')?></th>
			<th width="120" align="center"><?php echo L('inputtime')?></th>
			<th width="69" align="center"><?php echo L('operations_manage')?></th>
            </tr>
        </thead>
    <tbody>
 <?php 
if(is_array($data)){
	foreach($data as $currency){
 
 echo '<tr>
	<td align="center">
	<input type="checkbox" name="aid[]" value="'.$currency['aid'].'">
	</td>
	<td align="center">'.$currency['aid'].'</td>
	<td align="center">'.$currency['title'].'</td>
	<td align="center">'.$currency['code'].'</td>
	<td align="center">'.$currency['symbol'].'</td>
	<td align="center">'.$currency['exchangerate'].'</td>
	<td align="center">';
	if($currency['isdefault']=='1'){echo '√';}else{echo '╳';}
	echo '
	</td>
	<td align="center">'.date('Y-m-d H:i:s', $currency['addtime']).'</td>
	<td align="center">
	<a href="javascript:edit('.$currency['aid'].', \''.safe_replace($currency['title']).'\');void(0);">'.L('edit').'</a>
	</td>
	</tr>';

	}
}
?>
</tbody>
    </table>
  
    <div class="btn"><label for="check_box"><?php echo L('selected_all')?>/<?php echo L('cancel')?></label>
        <input name='submit' type='submit' class="button" value='<?php echo L('cancel_all_selected')?>' onClick="document.myform.action='?m=currency&c=admin_currency&a=public_approval&isdefault=0'"> <input name='submit' type='submit' class="button" value='<?php echo L('pass_all_selected')?>' onClick="document.myform.action='?m=currency&c=admin_currency&a=public_approval&isdefault=1'">&nbsp;&nbsp;
		<input name="submit" type="submit" class="button" value="<?php echo L('remove_all_selected')?>" onClick="document.myform.action='?m=currency&c=admin_currency&a=delete';return confirm('<?php echo L('affirm_delete')?>')">&nbsp;&nbsp;</div>  </div>
 <div id="pages"><?php echo $this->db->pages;?></div>
</form>
</div>
</body>
</html>
<script type="text/javascript">
function edit(id, title) {
	window.top.art.dialog({id:'edit'}).close();
	window.top.art.dialog({title:'<?php echo L('edit_currency')?>--'+title, id:'edit', iframe:'?m=currency&c=admin_currency&a=edit&aid='+id ,width:'600px',height:'350px'}, function(){var d = window.top.art.dialog({id:'edit'}).data.iframe;
	var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'edit'}).close()});
}
</script>