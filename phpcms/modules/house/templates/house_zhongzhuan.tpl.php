<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<style>
.curr{font-weight:bold;color:#ff5500;}
</style>
<div class="temp_content-menu" style="display:none;">
        <a <?php if ($h==0 || $h==1  || $h==5 || $h==6){echo 'class="on"';}?> href='?m=house&c=admin_house&a=house_zhongzhuan&hid=<?php echo $this->hid;?>' ><em><?php echo L('zz_enter_warehouse');?></em></a>  <span>|</span><a <?php if ($h==2 || $h==3 || $h==4){echo 'class="on"';}?> href='?m=house&c=admin_house&a=house_zhongzhuan&hid=<?php echo $this->hid;?>&h=4&kb=2' ><em><?php echo L('jh_out_warehouse')?><?php echo L('manage_1');?></em></a>  </div>

<script>//$('.content-menu').html($(".temp_content-menu").html());
$(".temp_content-menu").remove();</script>
<div class="pad-lr-10">

<table width="100%" cellspacing="0" class="search-form" style="display:none;">
    <tbody>
		<tr>
		<td><div class="explain-col" align=right>
		<span style="float:left">
		<?php if ($h==0 || $h==1 || $h==5 || $h==6){ 
		
		$sel111="";	
		if($h==0 && $sp==''){$sel111= ' class="curr"';}

		$sel22="";
		if($h==1){$sel22= ' class="curr"';}

		echo '<a '.$sel111.' href="?m=house&c=admin_house&a=house_zhongzhuan&h=0&hid='.$this->hid.'">'.L('processed_order_list').'</a> &nbsp;|&nbsp; 
		 <a '.$sel22.' href="?m=house&c=admin_house&a=house_zhongzhuan&h=1&hid='.$this->hid.'">'.L('order_list_of_untreated').'</a>';
			
			
}else{			
$sel33="";
if ($h==3){$sel33= 'class="curr"';}
$sel4="";
if ($h==4){$sel4= 'class="curr"';}


}			

 echo '</span> ';

 if ( $h==0 || $h==1){ 
	if($h==1){
	echo '<input name="submit1" type="button" class="button" value="'.L('batch_in_storage_handle').'" onclick="javascript:more_incheck();void(0);">&nbsp;&nbsp;';}else{
echo '<input name="submit1" type="button" class="button" value="'.L('batch_split_waybill').'" onclick="javascript:more_openhuodan();void(0);">&nbsp;&nbsp;';
}

echo '<input name="turnotherplace" type="button" class="button" value="'.L('huodan').L('turn_other_place_1').'" onclick="javascript:more_turnplace();void(0);">&nbsp;&nbsp;';
}else{

	if ($h==4){
	echo '
	
	<input name="submit" type="button" class="button" value="'.L('waybill_batch_status').'" onclick="javascript:waybill_batch_status(\'0\', \'\');void(0);">&nbsp;&nbsp;

	<input name="submit" type="button" class="button" value="'.L('outbound_handle').'" onclick="javascript:huodan_build_handle(\'0\', \'\');void(0);">&nbsp;&nbsp;
		
		<input name="submit" type="button" class="button" value="'.L('waybill_goto_paifadian').'" onclick="javascript:waybillgoto_paifa(\'0\', \'\');void(0);">&nbsp;&nbsp;';
	}else{
		echo '<input name="submit" type="button" class="button" value="'.L('waybill_goto_paifadian').'" onclick="javascript:waybillgoto_paifa(\'0\', \'\');void(0);">&nbsp;&nbsp;';
	}
			
}			
?>

</div>
		</td>
		</tr>
</table>


<form name="mysearch" action="?" method="get">

<input type="hidden" name="m" value="<?php echo $_GET['m'];?>"/>
<input type="hidden" name="c" value="<?php echo $_GET['c'];?>"/>
<input type="hidden" name="a" id="saerchA" value="<?php echo $_GET['a'];?>"/>
<input type="hidden" name="hid"  value="<?php echo $this->hid;?>"/>
<input type="hidden" name="kb"  value="<?php echo $kb;?>"/>
<input type="hidden" name="h"  value="<?php echo $h;?>"/>
<div class="explain-col"> 
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td>
		<?php echo L('keywords');?>：<input type="text" name="keywords" value="<?php echo $keywords;?>"/>&nbsp;&nbsp;<?php echo L('by_in_storage_time');?>：<?php echo form::date('start_time', $start_time);
		echo '-';
		echo form::date('end_time', $end_time);echo '&nbsp;';
				if($kb==2){
				
				echo '<select name="status" id="status">
		<option value="">'.L('please_select').'</option>';
				$allsta=$this->getwaybilltatus();	
				foreach($allsta as $row){
			$selsta="";
			if($row['value']==$status){$selsta= ' selected';}

			echo '<option value="'.$row['value'].'" '.$selsta.'>'.$row['title'].'</option>';
		 
		 }
		 echo '</select>';
		 
		 }?>
		&nbsp;<input type="submit" name="gotosearch" value="<?php echo L('now_search');?>" class="button"/>
		
				
		</td>
		<td align=right>
		<input name="dosubmit" type="submit" class="button" value="导出清关单" onclick="javascript:export_clearance();">
		&nbsp;&nbsp;<input name="turnotherplace" type="button" class="button" value="清关处理" onclick="javascript:more_clearance();">&nbsp;&nbsp;
		</td>
		</tr>
    </tbody>
</table></div>
</form>

<form name="myform_clearance" action="?m=house&c=admin_house&a=batchclearance" method="post">

<input type="submit" id="clearance_btn" value="submit" style="display:none;"/>
<?php if ($kb==0){

echo '<div class="table-list">
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
            <th width="35" align="center"><input type="checkbox"  id="check_box" onclick="selectall(\'aid[]\');"></th>
			<th align="center">'.L('number').'</th>
			<th align="center">'.L('kaiban_username').'</th>
			<th align="center">'.L('jh_ship_method').'</th>
			<th align="center">'.L('huodan_number').'</th>
			<th align="center">'.L('waybillid').'</th>
			<th align="center">'.L('weight').'</th>
			
			
			<th align="center" >'.L('consignment_time').'</th>
			<th align="center">状态</th>
            </tr>
        </thead>
    <tbody>';


if(is_array($datas)){
	

	foreach($datas as $row){

	echo '<tr>
	<td align="center">
	<input type="checkbox" name="aid[]" value="'.$row['id'].'" rel="'.$row['huodanno'].'">
	</td>
	<td align="center">'.$row['id'].'</td>
	<td align="center">'.$row['username'].'</td>
	<td align="center">'.$row['shipname'].'</td>
	<td align="center">'.$row['huodanno'].'</td>
	<td align="center">'.$this->get_bill_line($row['kabanno']).'</td>
	<td align="center">'.$row['weight'].'</td>
	
	
	<td align="center" >'.date('Y-m-d H:i:s', $row['addtime']).'</td>
	<td align="center">';		
	if($row['handle_status']==0){
		echo '<font color=green>未处理</font>';
	}else{
		echo '<font >已处理</font>';
	}

	echo '</td>
	</tr>';
	}
}
echo '</tbody>
    </table>
  
    <div class="btn" >
	 
	   </div>  </div>
 <div id="pages">'.$this->whuodandb->pages.'</div>';

}else if ($kb==2){//已折分运单管理
	 
echo ' <div class="table-list">
		<table width="100%" cellspacing="0">
        <thead>
            <tr>
            <th width="35" align="center"><input type="checkbox"  id="check_box" onclick="selectall(\'aid[]\');"></th>
			<th align="center">'.L('number').'</th>
			<th align="center">UserID</th>
			<th align="center">'.L('username').'</th>
			<th align="center">'.L('truename').'</th>
			
			<th align="center">'.L('waybillid').'</th>
			<th align="center">'.L('expressno').'</th>
			<th align="center">'.L('takeperson').'</th>
			<th align="center">'.L('takecity').'</th>
			<th align="center">'.L('status').'</th>
			<th align="center">'.L('split_time').'</th>
			<th align="center" style="display:none">'.L('inputtime').'</th>
			<th align="center">'.L('operations_manage').'</th>
            </tr>
        </thead>
		<tbody>';

if(is_array($datas)){
	

	foreach($datas as $waybill){
		
		
		$takeperson="";
		$takecity="";
		

		$addr=explode("|",$waybill['takeaddressname']);
		
		$takeperson=$addr[0];
		$takecity=$addr[4];
		$mobile=$addr[1];

			
		

	echo '<tr>
	<td align="center">';
	//if($waybill['status']!=16){
		echo '
	<input type="checkbox" name="aid[]" value="'.$waybill['aid'].'" rel="'.$waybill['waybillid'].'" >
	';
	//}

	echo '
	</td>
	<td align="center">'.$waybill['aid'].'</td>
	<td align="center">'.$waybill['userid'].'</td>
	<td align="center">'.$waybill['username'].'</td>
	<td align="center">'.$waybill['truename'].'</td>
	
	<td align="center">'.$this->get_bill_line($waybill['waybillid']).'</td>
	<td align="center">';
		$allgoodsexps = $this->getwaybill_goods($waybill['waybillid']);
		foreach($allgoodsexps as $row){
			echo $row['expressno'].'<br>';
		}

	echo '</td>
	<td align="center">'.$takeperson.'</td>
	<td align="center">'.$takecity.'</td>
	<td align="center">'.str_replace(L('enter_xxstorage'),$waybill['placename'],$this->getwaybilltatus($waybill['status'])).'</td>
	<td align="center">'.date('Y-m-d H:i:s', $waybill['splittime']).'</td>
	<td align="center" style="display:none">'.date('Y-m-d H:i:s', $waybill['addtime']).'</td>
	<td align="center"><a href="?m=house&c=admin_house&a=house_jihuo_showbill&hid='.$this->hid.'&bid='.$waybill['aid'].'" >'.L('view').'</a>  | 
	 <a href="?m=house&c=admin_house&a=printbill&hid='.$this->hid.'&bid='.$waybill['aid'].'" target="_blank">'.L('print').'</a> ';
	 
	  if($waybill['status']==4 || $waybill['status']==3){ echo ' | 
	 <a  href="javascript:pay('.$waybill['aid'].', \''.$waybill['waybillid'].'\');void(0);">'.L('jh_waybill_pay').'</a>';
	 
	 }

	if($waybill['status']<6){ echo ' | 
	 <a href="javascript:unionbox('.$waybill['aid'].', \''.$waybill['waybillid'].'\');void(0);">'.L('jh_bill_hebox_handle').'</a> ';
	 }

	echo '
	</td>
	</tr>';
	
	}
}


echo '</tbody>
		</table>

		<div class="btn" >
	  
	   
	   </div> 
 <div id="pages">'.$this->wbdb->pages.'</div>
	  
		</div>';
	   
   }
?>
</form>
</div>
</body>
</html>
<script type="text/javascript">

function export_clearance(){
	//
	$("#saerchA").val("export_clearance");
	
}

function more_clearance(){
	var str="";
    $(":checkbox[name='aid[]'][checked]").each(function(){
		if(str=="")
			str=$(this).val();
		else
			str+="_"+$(this).val();
    });
	if(str==""){
	
		window.top.art.dialog({content:"<?php echo L('please_select_qians')?>",lock:true,width:'220',height:'70'}, function(){});

		return false;
	}else{
		
		$("#clearance_btn").click();
	}
}

function more_incheck(){

		var str="";
    $(":checkbox[name='aid[]'][checked]").each(function(){
		if(str=="")
			str=$(this).val();
		else
			str+="_"+$(this).val();
    });
	if(str==""){
	
		window.top.art.dialog({content:"<?php echo L('please_select_qians')?>",lock:true,width:'220',height:'70'}, function(){});

		return false;
	}else{
		
		incheck('',str);
	}
	
}
//入库检查
function incheck(id, title) {
	window.top.art.dialog({id:'incheck'}).close();
	window.top.art.dialog({title:'<?php echo L('fahuo_enterway_handle')?>'+title, id:'incheck', iframe:'?m=waybill&c=admin_waybill&a=waybill_ruku_check&no='+title+'&hid=<?php echo $this->hid;?>' ,width:'600px',height:'450px'}, function(){var d = window.top.art.dialog({id:'incheck'}).data.iframe;
	var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'incheck'}).close()});
}

//导入下一个仓库
function nextstorage(id, title) {
		window.top.art.dialog({id:'union'}).close();
		window.top.art.dialog({title:'<?php echo L('goto_next_warehouse')?>'+title, id:'union', iframe:'?m=waybill&c=admin_waybill&a=waybill_union_bill&ids=' ,width:'500px',height:'450px'}, function(){var d = window.top.art.dialog({id:'union'}).data.iframe;
		var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'union'}).close()});
}

//发货处理
function fahuo(id, title) {
	window.top.art.dialog({id:'fahuo'}).close();
	window.top.art.dialog({title:'<?php echo L('zhongzhang_send_handle')?>--'+title, id:'fahuo', iframe:'?m=waybill&c=admin_waybill&a=waybill_fahuo_pifa&shipno='+title+'&oid='+id ,width:'600px',height:'450px'}, function(){var d = window.top.art.dialog({id:'fahuo'}).data.iframe;
	var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'fahuo'}).close()});
}

//转换地点
function turnplace(id, title) {
	window.top.art.dialog({id:'turnplace'}).close();
	window.top.art.dialog({title:'<?php echo L('huodan').L('turn_other_place_1');?>'+title, id:'turnplace', iframe:'?m=waybill&c=admin_waybill&a=waybill_turnplace&shipno='+title+'&oid='+id ,width:'600px',height:'450px'}, function(){var d = window.top.art.dialog({id:'turnplace'}).data.iframe;
	var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'turnplace'}).close()});
}

function more_turnplace(){
		var str="";
    $(":checkbox[name='aid[]'][checked]").each(function(){
		if(str=="")
			str=$(this).val();
		else
			str+="_"+$(this).val();
    });
	if(str==""){
	
		window.top.art.dialog({content:"<?php echo L('please_select_qians')?>",lock:true,width:'220',height:'70'}, function(){});

		return false;
	}else{
		
		turnplace('',str);
	}
}

//批量折分货单处理
function more_openhuodan() {
	var str="";
    $(":checkbox[name='aid[]'][checked]").each(function(){
		if(str=="")
			str=$(this).val();
		else
			str+="_"+$(this).val();
    });
	if(str==""){
	
		window.top.art.dialog({content:"<?php echo L('please_select_qians')?>",lock:true,width:'220',height:'70'}, function(){});

		return false;
	}else{

	window.top.art.dialog({id:'openhuodan'}).close();
	window.top.art.dialog({title:'<?php echo L('kaiban_openhuodan')?>', id:'openhuodan', iframe:'?m=waybill&c=admin_waybill&a=waybill_more_openhuodan&position=zhongzhuan&ids='+str ,width:'700px',height:'500px'}, function(){var d = window.top.art.dialog({id:'openhuodan'}).data.iframe;
	var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'openhuodan'}).close()});
	}
}

//折分货单处理
function openhuodan(id, title) {
	window.top.art.dialog({id:'openhuodan'}).close();
	window.top.art.dialog({title:'<?php echo L('kaiban_openhuodan')?>'+title, id:'openhuodan', iframe:'?m=waybill&c=admin_waybill&a=waybill_openhuodan&position=zhongzhuan&no='+title+'&oid='+id ,width:'700px',height:'450px'}, function(){var d = window.top.art.dialog({id:'openhuodan'}).data.iframe;
	var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'openhuodan'}).close()});
}

//批量折分货单处理
function more_openkaban() {
	var str="";
    $(":checkbox[name='aid[]'][checked]").each(function(){
		if(str=="")
			str=$(this).val();
		else
			str+="_"+$(this).val();
    });
	if(str==""){
	
		window.top.art.dialog({content:"<?php echo L('please_select_qians')?>",lock:true,width:'220',height:'70'}, function(){});

		return false;
	}else{

	window.top.art.dialog({id:'openkaban'}).close();
	window.top.art.dialog({title:'<?php echo L('waybill_openkaiban')?>', id:'openkaban', iframe:'?m=waybill&c=admin_waybill&a=waybill_more_openkaban&ids='+str ,width:'700px',height:'500px'}, function(){var d = window.top.art.dialog({id:'openkaban'}).data.iframe;
	var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'openkaban'}).close()});
	}
}

//折分卡板处理
function openkaban(id, title) {
	window.top.art.dialog({id:'openkaban'}).close();
	window.top.art.dialog({title:'<?php echo L('waybill_openkaiban')?>'+title, id:'openkaban', iframe:'?m=waybill&c=admin_waybill&a=waybill_openkaban&position=zhongzhuan&no='+title+'&oid='+id ,width:'700px',height:'450px'}, function(){var d = window.top.art.dialog({id:'openkaban'}).data.iframe;
	var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'openkaban'}).close()});
}

//卡板直接转到派发点
function kabangoto_paifa(id, title) {

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
	window.top.art.dialog({id:'goto_paifa'}).close();
	window.top.art.dialog({title:'<?php echo L('kaban_goto_paifadian')?>'+title, id:'goto_paifa', iframe:'?m=waybill&c=admin_waybill&a=kabangoto_paifa&ids='+str ,width:'600px',height:'450px'}, function(){var d = window.top.art.dialog({id:'goto_paifa'}).data.iframe;
	var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'goto_paifa'}).close()});

	}
}

//运单直接转到派发点
function waybillgoto_paifa(id, title) {

		var str="";
    $(":checkbox[name='aid[]'][checked]").each(function(){
		if(str=="")
			str+=$(this).attr("rel");
		else
			str+=","+$(this).attr("rel");
    });
	if(str==""){
	
		window.top.art.dialog({content:"<?php echo L('please_select_qians')?>",lock:true,width:'220',height:'70'}, function(){});

		return false;
	}else{
	window.top.art.dialog({id:'goto_paifa'}).close();
	window.top.art.dialog({title:'<?php echo L('waybill_goto_paifadian')?>'+title, id:'goto_paifa', iframe:'?m=waybill&c=admin_waybill&a=waybill_gotopaifa&ids='+str ,width:'600px',height:'450px'}, function(){var d = window.top.art.dialog({id:'goto_paifa'}).data.iframe;
	var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'goto_paifa'}).close()});

	}
}

function huodan_build_handle(id, title) {
	var str="";
	var hav="no";
    $(":checkbox[name='aid[]'][checked]").each(function(){
		if(str=="")
			str+=$(this).val();
		else
			str+=","+$(this).val();

		if($(this).attr("alt"))
			hav="yes";
    });


	if(str=="" || hav=='yes'){
		if(hav=='yes'){
		window.top.art.dialog({content:"<?php echo L('consolidated_list_card_number_selected');?>",lock:true,width:'220',height:'70'}, function(){});
		}else{
		window.top.art.dialog({content:"<?php echo L('please_select_qians')?>",lock:true,width:'220',height:'70'}, function(){});
		}
		return false;
	}else{
	window.top.art.dialog({id:'kaiban_handle'}).close();
	window.top.art.dialog({title:'<?php echo L('outbound_handle');?>'+title, id:'kaiban_handle', iframe:'?m=waybill&c=admin_waybill&a=huodan_build_handle&p=zhongzhuan&flag=1&hid=<?php echo $this->hid;?>&ids='+str ,width:'650px',height:'480px'}, function(){var d = window.top.art.dialog({id:'kaiban_handle'}).data.iframe;
	var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'kaiban_handle'}).close()});
	}
}


function waybill_batch_status(id, title) {
	var str="";
	var hav="no";
    $(":checkbox[name='aid[]'][checked]").each(function(){
		if(str=="")
			str+=$(this).val();
		else
			str+=","+$(this).val();		
    });

	if(str==""){
		window.top.art.dialog({content:"<?php echo L('please_select_qians')?>",lock:true,width:'220',height:'70'}, function(){});
	}else{
	window.top.art.dialog({id:'waybill_batch_status'}).close();
	window.top.art.dialog({title:'<?php echo L('waybill_batch_status');?>'+title, id:'kaiban_handle', iframe:'?m=waybill&c=admin_waybill&a=waybill_batch_status&p=zhongzhuan&flag=1&hid=<?php echo $this->hid;?>&ids='+str ,width:'550px',height:'480px'}, function(){var d = window.top.art.dialog({id:'waybill_batch_status'}).data.iframe;
	var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'waybill_batch_status'}).close()});
	}
}
</script>