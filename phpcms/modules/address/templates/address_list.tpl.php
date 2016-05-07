<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<div class="pad-lr-10">
<form name="myform" action="?m=address&c=admin_address&a=listorder" method="post">
<div class="table-list">
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
            <th width="35" align="center"><input type="checkbox" value="" id="check_box" onclick="selectall('aid[]');"></th>
			<th width="69" align="center"><?php echo L('number')?></th>
			<th  align="center"><?php echo L('membername')?></th>
			<th  align="center"><?php echo L('truename')?></th>
			<th  align="center"><?php echo L('placename')?></th>
			<th  align="center"><?php echo L('addressname')?></th>
			<th  align="center"><?php echo L('postcode')?></th>
			<th  align="center"><?php echo L('typename')?></th>
			<th  align="center"><?php echo L('connection')?></th>
			<th width="69" align="center"><?php echo L('operations_manage')?></th>
            </tr>
        </thead>
    <tbody>
 <?php 
if(is_array($data)){
	foreach($data as $address){


echo '<tr>
	<td align="center">
	<input type="checkbox" name="aid[]" value="'.$address['aid'].'">
	</td>
	<td align="center">'.$address['aid'].'</td>
	<td align="center">'.$address['username'].'</td>
	<td align="center">'.$address['truename'].'</td>
	<td align="center">'.$address['province'].' '.$address['city'].'</td>
	<td align="center">'.$address['address'].'</td>
	<td align="center">'.$address['postcode'].'</td>
	<td align="center">';
	 if($address['addresstype']==0){echo L('defaultaddress');}elseif($address['addresstype']==1){echo L('getaddress');}elseif($address['addresstype']==2){echo L('setaddress');}
	echo '
	</td>
	<td align="center">'.$address['mobile'].'</td>
	<td align="center">
	<a href="javascript:edit('.$address['aid'].', \''.safe_replace($address['title']).'\');void(0);">'.L('edit').'</a>
	</td>
	</tr>';
 
	}
}
?>
</tbody>
    </table>
  
    <div class="btn"><label for="check_box"><?php echo L('selected_all')?>/<?php echo L('cancel')?></label>
       <input name="submit" type="submit" class="button" value="<?php echo L('remove_all_selected')?>" onClick="document.myform.action='?m=address&c=admin_address&a=delete';return confirm('<?php echo L('affirm_delete')?>')">&nbsp;&nbsp;</div>  </div>
 <div id="pages"><?php echo $this->db->pages;?></div>
</form>
</div>
</body>
</html>
<script type="text/javascript">
function edit(id, title) {
	window.top.art.dialog({id:'edit'}).close();
	window.top.art.dialog({title:'<?php echo L('edit_address')?>--'+title, id:'edit', iframe:'?m=address&c=admin_address&a=edit&aid='+id ,width:'600px',height:'450px'}, function(){var d = window.top.art.dialog({id:'edit'}).data.iframe;
	var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'edit'}).close()});
}
</script>