<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<div class="pad-lr-10">
<form name="myform" action="?m=house&c=admin_house&a=listorder" method="post">
<div class="table-list">
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
            <th width="35" align="center"><input type="checkbox" value="" id="check_box" onclick="selectall('aid[]');"></th>
			<th width="69" align="center"><?php echo L('number')?></th>
			<th align="center"><?php echo L('house_name')?></th>
		
			<th width="120" align="center"><?php echo L('inputtime')?></th>
			<th width="169" align="center"><?php echo L('operations_manage')?></th>
            </tr>
        </thead>
    <tbody>
 <?php 
if(is_array($data)){
	foreach($data as $house){

  echo '<tr>
	<td align="center">';

	 if($house['issystem']!='1'){
	echo '<input type="checkbox" name="aid[]" value="'.$house['aid'].'" >';
	 }
	echo '</td>
	<td align="center">'.$house['aid'].'</td>
	<td align="center">'.$house['title'].'</td>
	<td align="center">'.date('Y-m-d H:i:s', $house['addtime']).'</td>
	<td align="center">
	 <a href="javascript:edit('.$house['aid'].', \''.safe_replace($house['title']).'\');void(0);">'.L('edit').'</a> 
	</td>
	</tr>';

	}
}
?>
</tbody>
    </table>
  
    <div class="btn"><label for="check_box"><?php echo L('selected_all')?>/<?php echo L('cancel')?></label>
       <input name="submit" type="submit" class="button" value="<?php echo L('remove_all_selected')?>" onClick="document.myform.action='?m=house&c=admin_house&a=delete';return confirm('<?php echo L('affirm_delete')?>')">&nbsp;&nbsp;</div>  </div>
 <div id="pages"><?php echo $this->db->pages;?></div>
</form>
</div>
</body>
</html>
<script type="text/javascript">
function edit(id, title) {
	window.top.art.dialog({id:'edit'}).close();
	window.top.art.dialog({title:'<?php echo L('edit_house')?>--'+title, id:'edit', iframe:'?m=house&c=admin_house&a=edit&aid='+id ,width:'500px',height:'350px'}, function(){var d = window.top.art.dialog({id:'edit'}).data.iframe;
	var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'edit'}).close()});
}


</script>