<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<div class="pad-lr-10">
<form name="myform" action="?m=shipline&c=admin_shipline&a=listorder" method="post">
<div class="table-list">
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
            <th width="35" align="center"><input type="checkbox" value="" id="check_box" onclick="selectall('aid[]');"></th>
			<th width="69" align="center"><?php echo L('number')?></th>
			<th align="center"><?php echo L('shipline')?></th>
			<th width='168' align="center"><?php echo L('line')?></th>
			<th width="168" align="center"><?php echo L('code')?></th>
			<th width="150" align="center" style="display:none;"><?php echo L('firstweight')?>/<?php echo L('price')?></th>
			<th width="150" align="center" style="display:none;"><?php echo L('addweight')?>/<?php echo L('price')?></th>
			<th width="120" align="center"><?php echo L('inputtime')?></th>
			<th width="69" align="center"><?php echo L('operations_manage')?></th>
            </tr>
        </thead>
    <tbody>
 <?php 
if(is_array($data)){
	foreach($data as $shipline){
echo '<tr>
	<td align="center">
	<input type="checkbox" name="aid[]" value="'.$shipline['aid'].'">
	</td>
	<td align="center">'.$shipline['aid'].'</td>
	<td align="center">'.$shipline['title'].'</td>
	<td align="center">';
foreach($line as $l){
	if($l['linkageid']==$shipline['origin'])
		echo $l['name'];
}

echo '&nbsp;→&nbsp;';

	foreach($line as $l){
	if($l['linkageid']==$shipline['destination'])
		echo $l['name'];
} 
echo '</td>
	<td align="center">'.$shipline['code'].'</td>
	<td align="center" style="display:none;">'.$shipline['firstweight'].'/'.$shipline['price'].'</td>
	<td align="center" style="display:none;">'.$shipline['addweight'].'/'.$shipline['addprice'].'</td>
	
	<td align="center">'.date('Y-m-d H:i:s', $shipline['addtime']).'</td>
	<td align="center">
	<a href="javascript:edit('.$shipline['aid'].', \''.safe_replace($shipline['title']).'\');void(0);">'.L('edit').'</a>
	</td>
	</tr>';

	}
}
?>
</tbody>
    </table>
  
    <div class="btn"><label for="check_box"><?php echo L('selected_all')?>/<?php echo L('cancel')?></label>
        <input name="submit" type="submit" class="button" value="<?php echo L('remove_all_selected')?>" onClick="document.myform.action='?m=shipline&c=admin_shipline&a=delete';return confirm('<?php echo L('affirm_delete')?>')">&nbsp;&nbsp;</div>  </div>
 <div id="pages"><?php echo $this->db->pages;?></div>
</form>
</div>
</body>
</html>
<script type="text/javascript">
function edit(id, title) {
	window.location.href='?m=shipline&c=admin_shipline&a=edit&aid='+id+'&checkhash=<?php echo $_SESSION[checkhash];?>';
}
</script>