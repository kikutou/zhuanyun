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
			<th align="center"><?php echo L('title')?></th>
			<?php
			if($_GET['gid']==48 ){
			echo '<th align="center">国家</th>';
			}
			?>
			<th width="168" align="center"><?php echo L('value')?></th>
			<th width="120" align="center"><?php echo L('listorder')?></th>
			<th width="169" align="center"><?php echo L('operations_manage')?></th>
            </tr>
        </thead>
    <tbody>
 <?php 
if(is_array($data)){
	foreach($data as $enum){

echo '<tr>
	<td align="center">
	
	<input type="checkbox" name="aid[]" value="'.$enum['aid'].'" >

	</td>
	<td align="center">'.$enum['aid'].'</td>
	<td align="center">'.$enum['title'].'</td>';
	
	if($_GET['gid']==48 ){
		echo '<td align="center">'.$enum['country'].'</td>';
	}
	echo '
	<td align="center">'.$enum['value'].'</td>
	<td align="center">'.$enum['listorder'].'</td>
	<td align="center">
	 <a href="javascript:edititem('.$enum['aid'].', \''.safe_replace($enum['title']).'\');void(0);">'.L('edit').'</a> 
	</td>
	</tr>';
	}
}
?>
</tbody>
    </table>
  
    <div class="btn"><label for="check_box"><?php echo L('selected_all')?>/<?php echo L('cancel')?></label>
       <input name="submit" type="submit" class="button" value="<?php echo L('remove_allitem_selected')?>" onClick="document.myform.action='?m=enum&c=admin_enum&a=deleteitem';return confirm('<?php echo L('affirm_delete')?>')">&nbsp;&nbsp;</div>  </div>
 <div id="pages"><?php echo $this->db->pages;?></div>
</form>
</div>
</body>
</html>
<script type="text/javascript">
function edititem(id, title) {
	window.top.art.dialog({id:'edititem'}).close();
	window.top.art.dialog({title:'<?php echo L('enum_edititem')?>--'+title, id:'edititem', iframe:'?m=enum&c=admin_enum&a=edititem&gid=<?php echo $gid;?>&aid='+id ,width:'500px',height:'350px'}, function(){var d = window.top.art.dialog({id:'edititem'}).data.iframe;
	var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'edititem'}).close()});
}

</script>