<?php
defined('IN_ADMIN') or exit('No permission resources.');
$show_dialog = 1;
include $this->admin_tpl('header', 'admin');
?>

<div class="pad-lr-10">
  <table width="100%" cellspacing="0" class="search-form">
    <tbody>
      <tr>
        <td><div class="explain-col"> <?php echo L('all_type')?>: &nbsp;&nbsp; <a href="?m=kuaidi&c=kuaidi"><?php echo L('all')?></a> &nbsp;&nbsp; <a href="?m=kuaidi&c=kuaidi&typeid=0">默认分类</a>&nbsp;
        <?php
		if(is_array($type_arr)){
		foreach($type_arr as $typeid => $type){
		?><a href="?m=kuaidi&c=kuaidi&typeid=<?php echo $typeid;?>"><?php echo $type;?></a> &nbsp; <?php }}?>
          </div></td>
      </tr>
    </tbody>
  </table>
  <form name="myform" id="myform" action="?m=kuaidi&c=kuaidi&a=listorder" method="post">
    <div class="table-list">
      <table width="100%" cellspacing="0">
        <thead>
          <tr>
            <th width="35" align="center"><input type="checkbox" value="" id="check_box" onclick="selectall('guestid[]');"></th>
            <th width="35" align="center"><?php echo L('listorder')?></th>
            <th align="center"><?php echo L('kd_name')?></th>
            <th width="15%" align="center"><?php echo L('logo')?></th>
			<th width="10%" align="center"><?php echo L('code')?></th>
			<th width="10%" align="center"><?php echo L('typeid')?></th>
			<th width="8%" align="center"><?php echo L('status')?></th>
			<th width="12%" align="center"><?php echo L('operations_manage')?></th>
          </tr>
        </thead>
        <tbody>
          <?php
if(is_array($infos)){
	foreach($infos as $info){
		?>
          <tr>
            <td align="center" width="35"><input type="checkbox" name="kdid[]" value="<?php echo $info['kdid']?>"></td>
            <td align="center" width="35"><input name='listorders[<?php echo $info['kdid']?>]' type='text' size='3' value='<?php echo $info['listorder']?>' class="input-text-c"></td>
            <td align="left"><a href="?m=kuaidi&c=index&a=show&id=<?php echo $info['kdid']?>" target="_blank"><span><?php echo $info['name']?></span></a> <? if($info['common']) {echo '<img src="'.IMG_PATH.'icon/small_elite.gif" title="'.L('common').'">';} ?></td>
            <td align="center"><?php if($info['logo']!=''){?><img src="<?php echo $info['logo'];?>" width=90 height=30><?php }?></td>
            <td align="left"><?php echo $info['code'];?></td>
            <td align="center"><?php if($info['typeid']==0){echo "默认分类";}else{echo $type_arr[$info['typeid']];}?></td>
            <td align="center"><?php if($info['passed']=='0'){?><a href='?m=kuaidi&c=kuaidi&a=check&kdid=<?php echo $info['kdid']?>'
			onClick="return confirm('<?php echo L('pass_or_not')?>')"><font color=red><?php echo L('audit')?></font></a><?php }else{echo L('passed');}?></td>
            <td align="center"><a href="###"
			onclick="edit(<?php echo $info['kdid']?>, '<?php echo new_addslashes($info['name'])?>')"
			title="<?php echo L('edit')?>"><?php echo L('edit')?></a> | <a
			href='?m=kuaidi&c=kuaidi&a=delete&kdid=<?php echo $info['kdid']?>'
			onClick="return confirm('<?php echo L('confirm', array('message' => new_addslashes($info['name'])))?>')"><?php echo L('delete')?></a></td>
          </tr>
          <?php
	}
}
?>
        </tbody>
      </table>
    </div>
    <div class="btn">
      <input name="dosubmit" type="submit" class="button"  value="<?php echo L('listorder')?>">&nbsp;&nbsp;<input type="submit" class="button" name="dosubmit" onClick="document.myform.action='?m=kuaidi&c=kuaidi&a=delete'" value="<?php echo L('delete')?>"/>
    </div>
    <div id="pages"><?php echo $pages?></div>
  </form>
</div>
<script type="text/javascript">

function edit(id, name) {
	window.top.art.dialog({id:'edit'}).close();
	window.top.art.dialog({title:'<?php echo L('edit')?> '+name+' ',id:'edit',iframe:'?m=kuaidi&c=kuaidi&a=edit&kdid='+id,width:'700',height:'450'}, function(){var d = window.top.art.dialog({id:'edit'}).data.iframe;var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'edit'}).close()});
}
function checkuid() {
	var ids='';
	$("input[name='kdid[]']:checked").each(function(i, n){
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
	$.get('?m=kuaidi&c=kuaidi&a=listorder_up&kdid='+id,null,function (msg) { 
	if (msg==1) { 
	//$("div [id=\'option"+id+"\']").remove(); 
		alert('<?php echo L('move_success')?>');
	} else {
	alert(msg); 
	} 
	}); 
} 
</script>
</body></html>