<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<div class="pad-lr-10">
<form name="myform" action="?m=usercoupon&c=admin_usercoupon&a=listorder" method="post">
<div class="table-list">
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
            <th width="35" align="center"><input type="checkbox" value="" id="check_box" onclick="selectall('aid[]');"></th>
			<th width="69" align="center"><?php echo L('couponnumber')?></th>
			<th align="center"><?php echo L('couponname')?></th>
			<th width='100' align="center"><?php echo L('facevalue')?></th>
			<th width="100" align="center"><?php echo L('backpoints')?></th>
			<th width="100" align="center"><?php echo L('haveperson')?></th>
			<th width="120" align="center"><?php echo L('begintime')?></th>
			<th width="120" align="center"><?php echo L('endtime')?></th>
			<th width="120" align="center"><?php echo L('remark')?></th>
			<th width="100" align="center"><?php echo L('usedperson')?></th>
			<th width="70" align="center"><?php echo L('usercoupon_status')?></th>
			<th width="69" align="center"><?php echo L('operations_manage')?></th>
            </tr>
        </thead>
    <tbody>
 <?php 
if(is_array($data)){
	foreach($data as $usercoupon){
echo '<tr>
	<td align="center">
	<input type="checkbox" name="aid[]" value="'.$usercoupon['aid'].'">
	</td>
	<td align="center">'.$usercoupon['aid'].'</td>
	<td align="center">'.$usercoupon['title'].'</td>
	<td align="center">'.$usercoupon['facevalue'].'</td>
	<td align="center">'.$usercoupon['backpoints'].'</td>
	<td align="center"></td>
	<td align="center">'.date('Y-m-d H:i:s', $usercoupon['addtime']).'</td>
	<td align="center">'.date('Y-m-d H:i:s', $usercoupon['addtime']).'</td>
	<td align="center">'.$usercoupon['remark'].'</td>
	<td align="center">'.$usercoupon['usedperson'].'</td>
	<td align="center">'.$usercoupon['usedperson'].'</td>
	<td align="center">
	<a href="javascript:edit('.$usercoupon['aid'].', \''.safe_replace($usercoupon['title']).'\');void(0);">'.L('edit').'</a>
	</td>
	</tr>';
	}
}
?>
</tbody>
    </table>
  
    <div class="btn"><label for="check_box"><?php echo L('selected_all')?>/<?php echo L('cancel')?></label>
       <input name="submit" type="submit" class="button" value="<?php echo L('remove_all_selected')?>" onClick="document.myform.action='?m=usercoupon&c=admin_usercoupon&a=delete';return confirm('<?php echo L('affirm_delete')?>')">&nbsp;&nbsp;</div>  </div>
 <div id="pages"><?php echo $this->db->pages;?></div>
</form>
</div>
</body>
</html>
<script type="text/javascript">
function edit(id, title) {
	window.top.art.dialog({id:'edit'}).close();
	window.top.art.dialog({title:'<?php echo L('edit_usercoupon')?>--'+title, id:'edit', iframe:'?m=usercoupon&c=admin_usercoupon&a=edit&aid='+id ,width:'600px',height:'350px'}, function(){var d = window.top.art.dialog({id:'edit'}).data.iframe;
	var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'edit'}).close()});
}
</script>