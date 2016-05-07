<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<style>
.curr{font-weight:bold;}
</style>
<div class="temp_content-menu" style="display:none;">
		<a href='?m=house&c=admin_house&a=house_paifa&hid=<?php echo $this->hid;?>' <?php if( $ku==0){echo 'class="on"';}?>><em><?php echo L('zz_enter_warehouse')?></em></a> <span>|</span><a href='?m=house&c=admin_house&a=house_paifa&ku=1&hid=<?php echo $this->hid;?>' <?php if( $ku==1){echo 'class="on"';}?>><em><?php echo L('pf_enter_warehouse_wbno')?></em></a>
		   </div>

<script>//$('.content-menu').html($(".temp_content-menu").html());

$(".temp_content-menu").remove();</script>
<div class="pad-lr-10">

<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td><div class="explain-col" align=right ><span style="float:left;display:none;"> 
		 <a <?php if($sta==1){echo ' class="curr"';}?> href="?m=house&c=admin_house&a=house_paifa&ku=<?php echo $ku;?>&sta=1&hid=<?php echo $this->hid;?>"><?php echo L('handled');?><?php if($ku==2){echo L('pallets');}else if( $ku==1){echo L('waybill');}else{echo L('huodan');}?><?php echo L('List');?></a> &nbsp;|&nbsp; 
		 <a <?php if($sta==0){echo ' class="curr"';}?> href="?m=house&c=admin_house&a=house_paifa&ku=<?php echo $ku;?>&sta=0&hid=<?php echo $this->hid;?>"><?php echo L('nohandled');?><?php if($ku==2){echo L('pallets');}else if( $ku==1){echo L('waybill');}else{echo L('huodan');}?><?php echo L('List');?></a> &nbsp;&nbsp; 
		 </span>

<input type="button" onclick="window.top.art.dialog({id:'moreimport'}).close();window.top.art.dialog({id:'moreimport',iframe:'?m=house&c=admin_house&a=house_jihuo_moreimport&hid=<?php echo $this->hid;?>', title:'<?php echo L('batch_import_expressno')?>', width:'900', height:'450', lock:true}, function(){var d = window.top.art.dialog({id:'moreimport'}).data.iframe;var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'moreimport'}).close()});void(0);" value="<?php echo L('batch_import_expressno')?>"/>
<?php
	
if($ku==0 && $sta==1){
echo '<input name="submit1" type="button" class="button" value="'.L('batch_split_waybill').'" onclick="javascript:more_openhuodan();void(0);">&nbsp;&nbsp;';
}
	
	if($sta==0){
		


		
		echo '<input name="submit1" type="button" class="button" value="'.L('batch_in_storage_handle').'" onclick="javascript:more_incheck('.$ku.');void(0);">&nbsp;&nbsp;';
}	


	if($sta==1 && $ku==1){	 
	 echo ' <input name="paifasubmit" type="button" class="button" value="'.L('paifa_gotopais').'"  onclick="javascript:paison(\'\', \''.L('paifa_gotopais').'\');void(0);">&nbsp;&nbsp;
	   
	   <input name="paifasubmit" type="button" class="button" value="'.L('batch_delivery_himself').'"  onclick="javascript:more_paison(\'13\', \''.L('batch_delivery_himself').'\');">&nbsp;&nbsp;
	   
	   <input name="paifasubmit" type="button" class="button" value="'.L('batch_delivery_express').'"  onclick="javascript:more_paison(\'14\', \''.L('batch_delivery_express').'\');">&nbsp;&nbsp;

	   <input name="paifasubmit" type="button" class="button" value="'.L('batch').L('paifa_finish').'"  onclick="javascript:finish(\'\', \''.L('paifa_finish').'\');void(0);">&nbsp;&nbsp;';
	   
	   }

	$outstr="";
	if($ku==2){$outstr= L('pallets');}else if( $ku==1){$outstr= L('waybill');}else{$outstr= L('huodan');}

	echo '<input name="turnotherplace" type="button" class="button" value="'.$outstr.L('turn_other_place_1').'" onclick="javascript:more_turnplace('.$ku.');void(0);" >&nbsp;&nbsp;';

	if( $ku==1){	
		
	 echo '<input name="paifasubmit2" type="button" class="button" value="'.L('waybill').L('export').'"  onclick="javascript:document.getElementById(\'actact\').value=\'download\';document.getElementById(\'mysearch\').submit();">&nbsp;&nbsp;';}?>

		</div>
		</td>
		</tr>
</table>

		<form name="mysearch" id="mysearch" action="?" method="get">
		<input type="hidden" name="m" value="<?php echo $_GET['m'];?>"/>
<input type="hidden" name="c" value="<?php echo $_GET['c'];?>"/>
<input type="hidden" name="a"  value="<?php echo $_GET['a'];?>"/>
<input type="hidden" name="hid"  value="<?php echo $this->hid;?>"/>
<input type="hidden" name="sta"  value="<?php echo $sta;?>"/>
<input type="hidden" name="ku"  value="<?php echo $ku;?>"/>

<input type="hidden" name="act" id="actact"  value=""/>

<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td><div class="explain-col"> 
		<?php echo L('keywords');?>/扫描运单号：<input type="text" id="keywords" name="keywords" value="<?php echo $keywords;?>" onkeydown="BatchDo()"/>&nbsp;&nbsp;UserID：<input type="text" name="uid" value="<?php echo $uid;?>"/>&nbsp;&nbsp;<?php echo L('time');?>：<?php echo form::date('start_time', $start_time);echo '-'; echo form::date('end_time', $end_time);
				echo '&nbsp;';
				
				if($ku==1){

					echo '<select name="status" id="status">
		<option value="">'.L('please_select').'</option>';
				$allwaybilltatus = $this->getwaybilltatus();	
				foreach($allwaybilltatus as $row){
			$vls="";
			if($row['value']==$status){$vls= ' selected';}

			echo '<option value="'.$row['value'].'" '.$vls.'>'.$row['title'].'</option>';
		 
		 }
		 echo '</select>';
		 }?>
		&nbsp;<input type="submit" id="gotosearch" name="gotosearch" value="<?php echo L('now_search');?>" class="button" onclick="javascript:document.getElementById('actact').value='';"/>
		
				</div>
		</td>
		</tr>
    </tbody>
</table>
</form>
<form name="myform" action="?m=house&c=admin_house&a=listorder" method="post">


<?php if( $ku==2){//卡板管理

echo '<div class="table-list">
    <table width="100%" cellspacing="0">
         <thead>
            <tr>
            <th width="35" align="center"><input type="checkbox"  id="check_box" onclick="selectall(\'aid[]\');"></th>
			<th align="center">'.L('number').'</th>
			<th align="center">'.L('kaiban_username').'</th>
			<th align="center">'.L('kaiban_kabanno').'</th>
			<th align="center">'.L('waybillid').'</th>
			<th align="center">'.L('split_time').'</th>
			<th align="center">'.L('inputtime').'</th>
            </tr>
        </thead>
		 <tbody>';
 
if(is_array($datas)){
	foreach($datas as $row){

echo '<tr>
	<td align="center">
	<input type="checkbox" name="aid[]" value="'.$row['id'].'" rel="'.$row['kabanno'].'">
	</td>
	<td align="center">'.$row['id'].'</td>
	<td align="center">'.$row['username'].'</td>
	<td align="center">'.$row['kabanno'].'</td>
	<td align="center">'.str_replace("\n","<br>",$row['waybillid']).'</td>
	<td align="center">'.date('Y-m-d H:i:s', $row['splittime']).'</td>
	<td align="center">'.date('Y-m-d H:i:s', $row['addtime']).'</td>
	</tr>';

	}
}


echo '</tbody>
	</table>
	 <div class="btn" >

	   </div> 
	   <div id="pages">'.$this->kbdb->pages.'</div>
	</div>';

}else if($ku==1){//运单管理
	
	echo '<div class="table-list">
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
			<th align="center">快递公司</th>
			<th align="center">快递单号</th>
			<th align="center">'.L('status').'</th>
			<th align="center">'.L('inputtime').'</th>
			<th align="center">'.L('operations_manage').'</th>
            </tr>
        </thead>
    <tbody>';

if(is_array($datas)){

	$m_db = pc_base::load_model('member_model');
	


	foreach($datas as $waybill){
	$splittime=$waybill['splittime'];
	

		//数据
		$usrresult = $m_db->get_one(array('userid'=>$waybill['userid']),'amount');
		$useramount=floatval($usrresult['amount']);
		
		
		$wayclr='';//
		if($useramount>=floatval($waybill['payedfee']) && floatval($waybill['payedfee'])>0 && floatval($waybill['totalweight'])>0 && $waybill['status']==8){
			$wayclr=' style="color:green;font-weight:bold;"';
		}
		
		
		$takeperson="";
		$takecity="";
		$mobile="";
		
		$addr=explode("|",$waybill['takeaddressname']);
		
		$takeperson=$addr[0];
		$takecity=$addr[4];
		$mobile=$addr[1];
	


		$allexp=$this->getwaybill_goods(trim($waybill['waybillid']),$waybill['returncode']);
				$exp="";
				//统计增值服务
				$alladdvalues="";

				foreach($allexp as $v){
					//$exp.=$v['expressno']."<br>";
					$shipp = $this->getallship2($v['expressid']);
					$exurl=str_replace('#',$v['expressno'],$shipp['remark']);
					$exp.='<a href="'.$exurl.'" target=_blank style="color:'. $this->getpackage_color($v['expressno']).'" rel='.$v['expressid'].'>'.$v['expressno'].'</a><br>';
				
					$goodaddvalues = string2array($v['addvalues']);

				
					foreach($goodaddvalues as $addval){
						
						$val = explode("|",$addval);

						$alladdvalues.=$val[1].$val[7].$val[6].'/'.$val[4]." ";
						
					}

				}
				
				
				$waybillallvalues=string2array($waybill['addvalues']);
				foreach($waybillallvalues as  $srv){
						$alladdvalues.=$srv['title'].$srv['currencyname'].$srv['price'].'/'.$srv['unitname']." ";
				}

				$bgc="";
				if($waybill['status']=='6'){ $bgc= 'style="background:#eee"';}
				if(!empty($alladdvalues)){$bgc= 'style="background:#CCFFFF"';}


		echo '<tr '. $bgc.' title="'.$alladdvalues.'">
	<td align="center">';
	 if($waybill['issystem']!='1' && $waybill['status']!=16){
		echo '<input type="checkbox" name="aid[]" value="'.$waybill['aid'].'" rel="'.$waybill['waybillid'].'" >';
	 }
	echo '</td>
	<td align="center">'.$waybill['aid'].'</td>
	<td align="center">'.$waybill['userid'].'</td>
	<td align="center">'.$waybill['username'].'</td>
	<td align="center">'.$waybill['truename'].'</td>
	
	<td align="center">'.$this->get_bill_line($waybill['waybillid']).'</td>
	<td align="center">'.$waybill['expressno'].'</td>
	<td align="center">'.$waybill['excompany'].'</td>
	<td align="center">'.$waybill['expressnumber'].'</td>
	<td align="center">'.str_replace(L('enter_xxstorage'),trim($waybill['placename']),$this->getwaybilltatus($waybill['status'])).'</td>
	
	<td align="center">'.date('Y-m-d H:i:s', $waybill['addtime']).'</td>
	<td align="center"><a href="?m=house&c=admin_house&a=house_jihuo_showbill&hid='.$this->hid.'&bid='.$waybill['aid'].'" >'.L('view').'</a>  | 
	 <a href="?m=house&c=admin_house&a=printbill&hid='.$this->hid.'&bid='.$waybill['aid'].'" target="_blank">'.L('print').'</a> ';

	
 if(trim($waybill['expressnumber'])!="" && $waybill['status']==14){
		echo ' | <a href="'.str_replace("#",trim($waybill['expressnumber']),trim($waybill['expressurl'])).'" target="_blank">'.L('express_tracking').'</a>';
	}

	echo '
	</td>
	</tr>';

 
	}
}

echo '</tbody>
    </table>
  
    <div class="btn">
	   
	  

	      
	   </div>  </div>
 <div id="pages">'.$this->wbdb->pages.'</div>';

}else if($ku==0){//入库货单号
echo '<div class="table-list">
    <table width="100%" cellspacing="0">
         <thead>
            <tr>
            <th width="35" align="center"><input type="checkbox"  id="check_box" onclick="selectall(\'aid[]\');"></th>
			<th align="center">'.L('number').'</th>
			<th align="center">'.L('kaiban_username').'</th>
			<th align="center">'.L('huodan_number').'</th>
			<th align="center">'.L('waybillid').'</th>
			<th align="center">'.L('operatetime').'</th>
			<th align="center">'.L('inputtime').'</th>
			<th align="center">'.L('operations_manage').'</th>
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
	<td align="center">'.$row['huodanno'].'</td>
	<td align="center">'.$this->get_bill_line($row['kabanno']).'</td>
	<td align="center">'.(($row['splittime']>0)?date('Y-m-d H:i:s', $row['splittime']):'').'</td>
	<td align="center">'.date('Y-m-d H:i:s', $row['addtime']).'</td>
	<td align="center">';
	 if($row['split_status']==0){
		 if($sta==1 && $ku==0){
	echo '<a style="color:green;" href="javascript:if(confirm(\''.L('tips_sure_split').'\')){openhuodan('.$row['id'].', \''.safe_replace($row['huodanno']).'\');void(0);}">'.L('split_waybill').'</a> ';
		 }
		 }else{
		if($this->isbackhuodan($row['id'])=='1'){
			echo '<a  href="?m=house&c=admin_house&a=house_backhuodan&id='.$row['id'].'" onclick="return confirm(\''.L('sure_recovery_has_been_removed_the_waybill').'\');">'.L('recovery_has_been_removed_the_waybill').'</a>';
		}
		}
	echo '</td>
	</tr>';
	}
}


echo '</tbody>
	</table>
	 <div class="btn" >
	   </div> 
	   <div id="pages">'.$this->whuodandb->pages.'</div>
	</div>';

}
?>




</form>
</div>
</body>
</html>
<script type="text/javascript">

//折分货单处理
function openhuodan(id, title) {
	window.top.art.dialog({id:'openhuodan'}).close();
	window.top.art.dialog({title:'<?php echo L('kaiban_openhuodan')?>'+title, id:'openhuodan', iframe:'?m=waybill&c=admin_waybill&a=waybill_openhuodan&position=paifa&no='+title+'&oid='+id ,width:'700px',height:'450px'}, function(){var d = window.top.art.dialog({id:'openhuodan'}).data.iframe;
	var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'openhuodan'}).close()});
}


//折分卡板处理
function openkaban(id, title) {
	window.top.art.dialog({id:'openkaban'}).close();
	window.top.art.dialog({title:'<?php echo L('waybill_openkaiban')?>'+title, id:'openkaban', iframe:'?m=waybill&c=admin_waybill&a=waybill_openkaban&position=paifa&no='+title+'&oid='+id ,width:'700px',height:'450px'}, function(){var d = window.top.art.dialog({id:'openkaban'}).data.iframe;
	var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'openkaban'}).close()});
}

//转换地点
function turnplace(id, title) {
	window.top.art.dialog({id:'turnplace'}).close();
	window.top.art.dialog({title:'<?php echo L('turn_other_place')?>--'+title, id:'turnplace', iframe:'?m=waybill&c=admin_waybill&a=waybill_turnplace&shipno='+title+'&oid='+id ,width:'500px',height:'450px'}, function(){var d = window.top.art.dialog({id:'turnplace'}).data.iframe;
	var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'turnplace'}).close()});
}

function more_turnplace(flag){
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
		if(flag==0){
			turnplace('',str);
		}else if(flag==1){
			waybillgoto_paifa('',str);
		}else if(flag==2){
			kabangoto_paifa('',str);
		}
	}
}

//卡板直接转到派发点
function kabangoto_paifa(id, title) {

	var str=title;
	window.top.art.dialog({id:'goto_paifa'}).close();
	window.top.art.dialog({title:'<?php echo L('kaban_goto_paifadian')?>'+title, id:'goto_paifa', iframe:'?m=waybill&c=admin_waybill&a=kabangoto_paifa&ids='+str ,width:'500px',height:'450px'}, function(){var d = window.top.art.dialog({id:'goto_paifa'}).data.iframe;
	var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'goto_paifa'}).close()});

	
}



//入库检查
function incheck(id, title) {
	window.top.art.dialog({id:'incheck'}).close();
	window.top.art.dialog({title:'<?php echo L('fahuo_enterway_handle')?>'+title, id:'incheck', iframe:'?m=waybill&c=admin_waybill&a=waybill_ruku_check&hid=<?php echo $this->hid;?>&no='+title ,width:'500px',height:'450px'}, function(){var d = window.top.art.dialog({id:'incheck'}).data.iframe;
	var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'incheck'}).close()});
}

function more_incheck(flag){

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
		
		

		if(flag==0){
			incheck('',str);
		}else if(flag==1){
			wbincheck('',str);
		}else if(flag==2){
			kbincheck('',str);
		}


	}
	
}

//卡板检查
function kbincheck(id, title) {
	window.top.art.dialog({id:'incheck'}).close();
	window.top.art.dialog({title:'<?php echo L('fahuo_enterway_handle')?>'+title, id:'incheck', iframe:'?m=waybill&c=admin_waybill&a=waybill_kbruku_check&hid=<?php echo $this->hid;?>&oid='+title ,width:'500px',height:'450px'}, function(){var d = window.top.art.dialog({id:'incheck'}).data.iframe;
	var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'incheck'}).close()});
}

//运单检查
function wbincheck(id, title) {
	window.top.art.dialog({id:'incheck'}).close();
	window.top.art.dialog({title:'<?php echo L('fahuo_enterway_handle')?>'+title, id:'incheck', iframe:'?m=waybill&c=admin_waybill&a=waybill_wbruku_check&hid=<?php echo $this->hid;?>&oid='+title ,width:'500px',height:'450px'}, function(){var d = window.top.art.dialog({id:'incheck'}).data.iframe;
	var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'incheck'}).close()});
}

function edit(id, title) {
	window.top.art.dialog({id:'edit'}).close();
	window.top.art.dialog({title:'<?php echo L('edit_package')?>--'+title, id:'edit', iframe:'?m=package&c=admin_package&a=edit&aid='+id ,width:'500px',height:'450px'}, function(){var d = window.top.art.dialog({id:'edit'}).data.iframe;
	var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'edit'}).close()});
}

function finish(id, title) {
		
		var str="";
		$(":checkbox[name='aid[]'][checked]").each(function(){
			if(str=="")
				str=$(this).val();
			else
				str+="_"+$(this).val();
		});

		window.top.art.dialog({id:'finish'}).close();
		window.top.art.dialog({title:'<?php echo L('paifa_finish')?>--'+title, id:'finish', iframe:'?m=waybill&c=admin_waybill&a=waybill_finish&hid=<?php echo $this->hid;?>&ids='+str ,width:'500px',height:'450px'}, function(){var d = window.top.art.dialog({id:'finish'}).data.iframe;
		var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'finish'}).close()});
	
}


function more_paison(id, title) {
		
		var str="";
    $(":checkbox[name='aid[]'][checked]").each(function(){
		if(str=="")
			str+=$(this).val();
		else
			str+="_"+$(this).val();
    });
	if(str==""){
	
		window.top.art.dialog({content:"<?php echo L('please_select_qians')?>",lock:true,width:'220',height:'70'}, function(){});

		return false;
	}else{
		var tit="<?php echo L('batch_delivery_himself');?>";
		if(id==14)
			tit="<?php echo L('batch_delivery_express');?>";

		window.top.art.dialog({id:'paison'}).close();
		window.top.art.dialog({title:tit, id:'paison', iframe:'?m=waybill&c=admin_waybill&a=waybill_morepaison&status='+id+'&ids='+str+'&hid=<?php echo $this->hid;?>' ,width:'650px',height:'450px'}, function(){var d = window.top.art.dialog({id:'paison'}).data.iframe;
		var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'paison'}).close()});
	}
	
}




function paison(id, title) {
		var str="";
		$(":checkbox[name='aid[]'][checked]").each(function(){
			if(str=="")
				str=$(this).attr('rel');
			
		});
		window.top.art.dialog({id:'paison'}).close();
		window.top.art.dialog({title:'<?php echo L('paifa_gotopais')?>--'+title, id:'paison', iframe:'?m=waybill&c=admin_waybill&a=waybill_paison&hid=<?php echo $this->hid;?>&waybillid='+str ,width:'500px',height:'450px'}, function(){var d = window.top.art.dialog({id:'paison'}).data.iframe;
		var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'paison'}).close()});
	
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
	window.top.art.dialog({title:'<?php echo L('waybill_goto_paifadian')?>'+title, id:'goto_paifa', iframe:'?m=waybill&c=admin_waybill&a=waybill_gotopaifa&ids='+str ,width:'500px',height:'450px'}, function(){var d = window.top.art.dialog({id:'goto_paifa'}).data.iframe;
	var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'goto_paifa'}).close()});

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
	window.top.art.dialog({title:'<?php echo L('kaiban_openhuodan')?>', id:'openhuodan', iframe:'?m=waybill&c=admin_waybill&a=waybill_more_openhuodan&position=paifa&ids='+str ,width:'700px',height:'500px'}, function(){var d = window.top.art.dialog({id:'openhuodan'}).data.iframe;
	var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'openhuodan'}).close()});
	}
}

function BatchDo(){
	   	
	 var event=arguments.callee.caller.arguments[0]||window.event;  
     if (event.keyCode == 13){
		document.getElementById('gotosearch').click();
		
	}
}



</script>