<?php
defined('IN_ADMIN') or exit('No permission resources.');
$show_dialog = 1;
include $this->admin_tpl('header', 'admin');
?>
<div class="pad-lr-10">
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td><div class="explain-col"> 
		<?php echo L('all_shoppingtype')?>: &nbsp;&nbsp; <a href="?m=shopping&c=shopping"><?php echo L('all')?></a> &nbsp;&nbsp;
		
		<?php
	if(is_array($type_arr)){
	foreach($type_arr as $typeid => $type){
		
	echo '<a href="?m=shopping&c=shopping&typeid='.$typeid.'">'.$type.'</a>&nbsp;';
	}}?>
		</div>
		</td>
		</tr>
    </tbody>
</table>
<form name="myform" id="myform" action="?m=shopping&c=shopping&a=listorder" method="post" >
<div class="table-list">
<table width="100%" cellspacing="0">
	<thead>
		<tr>
			<th width="35" align="center"><input type="checkbox" value="" id="check_box" onclick="selectall('shoppingid[]');"></th>
			<th width="35" align="center"><?php echo L('listorder')?></th>
			<th><?php echo L('shopping_name')?></th>
			<th width="12%" align="center"><?php echo L('logo')?></th>
			<th width="10%" align="center"><?php echo L('typeid')?></th>
			<th width='10%' align="center"><?php echo L('shopping_type')?></th>
			<th width="8%" align="center"><?php echo L('status')?></th>
			<th width="12%" align="center"><?php echo L('operations_manage')?></th>
		</tr>
	</thead>
<tbody>
<?php
if(is_array($infos)){
	foreach($infos as $info){
		echo '<tr>
		<td align="center" width="35"><input type="checkbox" name="shoppingid[]" value="'.$info['shoppingid'].'"></td>
		<td align="center" width="35"><input name=\'listorders['.$info['shoppingid'].']\' type=\'text\' size=\'3\' value=\''.$info['listorder'].'\' class="input-text-c"></td>
		<td><a href="'.$info['url'].'" title="'.L('go_website').'" target="_blank">'.$info['name'].'</a> </td>
		<td align="center" width="12%">';
		
		if($info['shoppingtype']==1){echo '<img src="'.$info['logo'].'" width=83 height=31>'; }
		echo '</td>
		<td align="center" width="10%">'.$type_arr[$info['typeid']].'</td>
		<td align="center" width="10%">';
		if($info['shoppingtype']==0){echo L('word_shopping');}else{echo L('logo_shopping');}


		echo '</td>
		<td width="8%" align="center">';
		if($info['passed']=='0'){echo '<a
			href=\'?m=shopping&c=shopping&a=check&shoppingid='.$info['shoppingid'].'\'
			onClick="return confirm(\''.L('pass_or_not').'\')"><font color=red>'.L('audit').'</font></a>';
			}else{echo L('passed');}
		echo '</td>
		<td align="center" width="12%"><a href="###"
			onclick="edit('.$info['shoppingid'].', \''.new_addslashes($info['name']).'\')"
			title="'.L('edit').'">'.L('edit').'</a> |  <a
			href=\'?m=shopping&c=shopping&a=delete&shoppingid='.$info['shoppingid'].'\'
			onClick="return confirm(\''.L('confirm', array('message' => new_addslashes($info['name']))).'\')">'.L('delete').'</a> 
		</td>
	</tr>';
	}
}
?>
</tbody>
</table>
</div>
<div class="btn"> 
<input name="dosubmit" type="submit" class="button"
	value="<?php echo L('listorder')?>">&nbsp;&nbsp;<input type="submit" class="button" name="dosubmit" onClick="document.myform.action='?m=shopping&c=shopping&a=delete'" value="<?php echo L('delete')?>"/></div>
<div id="pages"><?php echo $pages?></div>
</form>
</div>
<script type="text/javascript">

function edit(id, name) {
	window.top.art.dialog({id:'edit'}).close();
	window.top.art.dialog({title:'<?php echo L('edit')?> '+name+' ',id:'edit',iframe:'?m=shopping&c=shopping&a=edit&shoppingid='+id,width:'700',height:'450'}, function(){var d = window.top.art.dialog({id:'edit'}).data.iframe;var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'edit'}).close()});
}
function checkuid() {
	var ids='';
	$("input[name='shoppingid[]']:checked").each(function(i, n){
		ids += $(n).val() + ',';
	});
	if(ids=='') {
		window.top.art.dialog({content:"<?php echo L('before_select_operations')?>",lock:true,width:'200',height:'50',time:1.5},function(){});
		return false;
	} else {
		myform.submit();
	}
}
//向下移动
function listorder_up(id) {
	$.get('?m=shopping&c=shopping&a=listorder_up&shoppingid='+id,null,function (msg) { 
	if (msg==1) { 
	//$("div [id=\'option"+id+"\']").remove(); 
		alert('<?php echo L('move_success')?>');
	} else {
	alert(msg); 
	} 
	}); 
} 
</script>
</body>
</html>
