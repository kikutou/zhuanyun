<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<div class="pad-lr-10">
<form name="myform" action="?m=enum&c=admin_enum&a=listorder" method="post">
<div class="table-list">
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
            <th width="35" align="center"><input type="checkbox" value="" id="check_box" onclick="selectall('aid[]');"></th>
			<th width="69" align="center"><?php echo L('number')?></th>
			<th align="center"><?php echo L('grouptitle')?></th>
			<th width="168" align="center"><?php echo L('issystem')?></th>
			<th width="120" align="center"><?php echo L('inputtime')?></th>
			<th width="169" align="center"><?php echo L('operations_manage')?></th>
            </tr>
        </thead>
    <tbody>
 <?php 
if(is_array($data)){
	foreach($data as $enum){


echo '<tr>
	<td align="center">';
	if($enum['issystem']!='1'){
		echo '
	<input type="checkbox" name="aid[]" value="'.$enum['aid'].'" >';
	}
	echo '
	</td>
	<td align="center">'.$enum['aid'].'</td>
	<td align="center">'.$enum['title'].'</td>
	<td align="center">';
	if($enum['issystem']=='1'){echo '√';}else{echo '╳';}
	
	echo '</td>
	<td align="center">'.date('Y-m-d H:i:s', $enum['addtime']).'</td>
	<td align="center">
	<a href="?m=enum&c=admin_enum&a=listitem&gid='.$enum['aid'].'">'.L('manage').'</a> | <a href="javascript:edit('.$enum['aid'].', \''.safe_replace($enum['title']).'\');void(0);">'.L('edit').'</a> | <a href="javascript:additem('.$enum['aid'].', \''.safe_replace($enum['title']).'\');void(0);">'.L('add').'</a>
	</td>
	</tr>';

	}
}
?>
</tbody>
    </table>
  
    <div class="btn"><label for="check_box"><?php echo L('selected_all')?>/<?php echo L('cancel')?></label>
       <input name="submit" type="submit" class="button" value="<?php echo L('remove_all_selected')?>" onClick="document.myform.action='?m=enum&c=admin_enum&a=delete';return confirm('<?php echo L('affirm_delete')?>')">&nbsp;&nbsp;</div>  </div>
 <div id="pages"><?php echo $this->db->pages;?></div>
</form>
</div>
</body>
</html>
<script type="text/javascript">
function edit(id, title) {
	window.top.art.dialog({id:'edit'}).close();
	window.top.art.dialog({title:'<?php echo L('edit_enum')?>--'+title, id:'edit', iframe:'?m=enum&c=admin_enum&a=edit&aid='+id ,width:'500px',height:'350px'}, function(){var d = window.top.art.dialog({id:'edit'}).data.iframe;
	var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'edit'}).close()});
}

function additem(id, title) {
	window.top.art.dialog({id:'additem'}).close();
	window.top.art.dialog({title:'<?php echo L('enum_additem')?>--'+title, id:'additem', iframe:'?m=enum&c=admin_enum&a=additem&gid='+id ,width:'500px',height:'350px'}, function(){var d = window.top.art.dialog({id:'additem'}).data.iframe;
	var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'additem'}).close()});
}
</script>