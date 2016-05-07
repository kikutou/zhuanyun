<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<style>
.curr{font-weight:bold;}
</style>
<div class="temp_content-menu" style="display:none;">
        <a href='?m=house&c=admin_house&a=house_jihuo&hid=<?php echo $this->hid;?>' ><em><?php echo L('jh_package_manage')?></em></a><span>|</span><a href='?m=house&c=admin_house&a=house_jihuo_waybill&hid=<?php echo $this->hid;?>' ><em><?php echo L('jh_waybill_manage')?></em></a><span>|</span><a href='?m=house&c=admin_house&a=house_jihuo_outhouse&hid=<?php echo $this->hid;?>' class="on"><em><?php echo L('jh_out_warehouse').L('manage')?></em></a>   </div>

<script>$('.content-menu').html($(".temp_content-menu").html());$(".temp_content-menu").remove();</script>
<div class="pad-lr-10">
<form name="mysearch" id="my_search" action="" method="get">
<input type="hidden" name="m" value="<?php echo $_GET['m'];?>"/>
<input type="hidden" name="c" value="<?php echo $_GET['c'];?>"/>
<input type="hidden" name="a"  value="<?php echo $_GET['a'];?>"/>
<input type="hidden" name="hid"  value="<?php echo $this->hid;?>"/>
<input type="hidden" name="act" id="act" value=""/>

<table width="100%" cellspacing="0" class="search-form"  >
    <tbody>
		<tr>
		<td><div class="explain-col" align=right> 
		<span style="float:left;">
		 <input name="submit1" type="button" class="button" value="<?php echo L('jh_waybill_send_handle');?>" onclick="javascript:huodan_build_handle('', '');void(0);">&nbsp;&nbsp;
		</span>
		<input type="submit" name="gotosearch1" onclick="document.getElementById('act').value='download';" value="<?php echo L('export_card');?>" class="button"/>
				</div>
		</td>
		</tr>
    </tbody>
</table>
</form>

<form name="myform" action="?m=house&c=admin_house&a=listorder" method="post">
<div class="table-list">

<table width="100%" cellspacing="0">
        <thead>
            <tr>
            <th width="35" align="center"><input type="checkbox"  id="check_box" onclick="selectall('aid[]');"></th>
			<th align="center"><?php echo L('number')?></th>
			<th align="center"><?php echo L('kaiban_username')?></th>
			<th align="center"><?php echo L('kaiban_kabanno')?></th>
			<th align="center"><?php echo L('waybillid')?></th>
			<th align="center"><?php echo L('inputtime')?></th>
			<th align="center"><?php echo L('operations_manage')?></th>
            </tr>
        </thead>
		 <?php 
		if(is_array($datas)){
			foreach($datas as $kb){

				echo '<tr>
			<td align="center">
			<input type="checkbox" name="aid[]" value="'.$kb['id'].'" >
			</td>
			<td align="center">'.$kb['id'].'</td>
			<td align="center">'.$kb['username'].'</td>
			<td align="center">'.$kb['kabanno'].'</td>
			<td align="center">'.str_replace(PHP_EOL, '<br/>', $kb['waybillid']).'</td>
			<td align="center">'.date('Y-m-d H:i:s',$kb['addtime']).'</td>
			<td align="center"><a href="?m=house&c=admin_house&a=house_jihuo_back&hid='.$this->hid.'&sta=10&kid='.$kb['id'].'" onclick="return confirm(\''.L('sure').L('go_back').'?\');">'.L('go_back').'</a></td>
			</tr>';
			
			}
		}?>
    <tbody>
	</table>
<div class="btn">
      
	   </div> 
	<div id="pages"><?php echo $this->kbdb->pages;?></div>



</div>
</form>
</div>

</body>
</html>
<script type="text/javascript">
function fahuo(id, title) {
	window.top.art.dialog({id:'fahuo'}).close();
	window.top.art.dialog({title:'<?php echo L('jh_waybill_send_handle')?>--'+title, id:'fahuo', iframe:'?m=waybill&c=admin_waybill&a=waybill_fahuo&billno='+title+'&oid='+id ,width:'500px',height:'450px'}, function(){var d = window.top.art.dialog({id:'fahuo'}).data.iframe;
	var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'fahuo'}).close()});
}

function huodan_build_handle(id, title) {
	var str="";
    $(":checkbox[name='aid[]'][checked]").each(function(){
		if(str=="")
			str+=$(this).val();
		else
			str+=","+$(this).val();
    });
	if(str==""){
		
		window.top.art.dialog({content:"<?php echo L('please_select_qians')?>",lock:true,width:'220',height:'70'}, function(){});

		return false;
	}else{
	window.top.art.dialog({id:'kaiban_handle'}).close();
	window.top.art.dialog({title:'<?php echo L('outbound_handle');?>'+title, id:'kaiban_handle', iframe:'?m=waybill&c=admin_waybill&a=huodan_build_handle&hid=<?php echo $this->hid;?>&ids='+str ,width:'650px',height:'480px'}, function(){var d = window.top.art.dialog({id:'kaiban_handle'}).data.iframe;
	var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'kaiban_handle'}).close()});
	}
}
</script>