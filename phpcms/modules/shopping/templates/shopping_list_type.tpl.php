<?php
defined('IN_ADMIN') or exit('No permission resources.');
$show_dialog = 1;
include $this->admin_tpl('header', 'admin');
?>
<div class="pad-lr-10">
<form name="myform" action="?m=shopping&c=shopping&a=delete_type" method="post" onsubmit="checkuid();return false;">
<div class="table-list">
<table width="100%" cellspacing="0">
	<thead>
		<tr>
			<th width="35" align="center"><input type="checkbox" value="" id="check_box" onclick="selectall('typeid[]');"></th>
			<th width="80"><?php echo L('shopping_type_listorder')?></th> 
			<th><?php echo L('type_name')?></th>
			<th width="12%" align="center"><?php echo L('type_id')?></th> 
			<th width="20%" align="center"><?php echo L('operations_manage')?></th>
		</tr>
	</thead>
<tbody>

<?php
if(is_array($infos)){
	foreach($infos as $info){
echo '<tr>
		<td align="center" width="35"><input type="checkbox"
			name="typeid[]" value="'.$info['typeid'].'"></td>
		<td align="center"><input name=\'listorders['.$info['typeid'].']\' type=\'text\' size=\'3\' value=\''.$info['listorder'].'\' class="input_center"></td> 
		<td>'.$info['name'].'</td>
		<td align="center" width="12%"> '.$info['typeid'].'</td>
		 <td align="center" width="20%"><a href="###"
			onclick="edit('.$info['typeid'].', \''.new_addslashes($info['name']).'\')"
			title="'.L('edit').'">'.L('edit').'</a> |  <a
			href=\'?m=shopping&c=shopping&a=delete_type&typeid='.$info['typeid'].'\'
			onClick="return confirm(\''.L('confirm', array('message' => new_addslashes($info['name']))).'\')">'.L('delete').'</a>
		</td>
	</tr>';
	}
}
?>
</tbody>
</table>
<div class="btn"><a href="#"
	onClick="javascript:$('input[type=checkbox]').attr('checked', true)"><?php echo L('selected_all')?></a>/<a
	href="#"
	onClick="javascript:$('input[type=checkbox]').attr('checked', false)"><?php echo L('cancel')?></a>
<input name="submit" type="submit" class="button"
	value="<?php echo L('remove_all_selected')?>"
	onClick="return confirm('<?php echo L('confirm', array('message' => L('selected')))?>')">&nbsp;&nbsp;</div>
</form>
<div id="pages" class="text-c"><?php echo $pages;?></div>
</div>
</body>
</html>
<script type="text/javascript">
function edit(id, name) {
	window.top.art.dialog({id:'edit'}).close();
	window.top.art.dialog({title:'<?php echo L('edit')?> '+name+' ',id:'edit',iframe:'?m=shopping&c=shopping&a=edit_type&typeid='+id,width:'450',height:'280'}, function(){var d = window.top.art.dialog({id:'edit'}).data.iframe;var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'edit'}).close()});
}
function checkuid() {
	var ids='';
	$("input[name='typeid[]']:checked").each(function(i, n){
		ids += $(n).val() + ',';
	});
	if(ids=='') {
		window.top.art.dialog({content:"<?php echo L('before_select_operations')?>",lock:true,width:'200',height:'50',time:1.5},function(){});
		return false;
	} else {
		myform.submit();
	}
}
</script>
