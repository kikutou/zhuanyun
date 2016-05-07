<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<style>
.curr{font-weight:bold;color:#ff6600;}
</style>
<div style="display:none;"></div>
<div class="temp_content-menu" style="display:none;">
        <a href='?m=house&c=admin_house&a=house_jihuo&hid=<?php echo $this->hid;?>' ><em><?php echo L('jh_package_manage')?></em></a><span>|</span><a href='?m=house&c=admin_house&a=house_jihuo_waybill&hid=<?php echo $this->hid;?>' <?php if($status!=-1){echo ' class="on"';}?>><em><?php echo L('jh_waybill_manage')?></em></a> <span>|</span><a href='?m=house&c=admin_house&a=house_jihuo_returned&hid=<?php echo $this->hid;?>' ><em><?php echo L('house_jihuo_returned_manage')?></em></a>  </div>

<script>$(".temp_content-menu").remove();</script>
<div class="pad-lr-10">
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td><div class="explain-col"> <b><?php echo L('status');?>：</b>
		 <a <?php if($status==0){echo ' class="curr"';}?> href="?m=house&c=admin_house&a=house_jihuo_waybill&hid=<?php echo $this->hid;?>"><?php echo L('jh_bill_list')?></a> &nbsp;&nbsp; 
		 <?php 
			 $allwaybilltatus = $this->getwaybilltatus();
			 foreach($allwaybilltatus as $row){
			 $sel="";
			 if($row['value']==$status){$sel= ' class="curr"';}
			if($row['value']!=11 && $row['value']!=13 && $row['value']!=15 && $row['value']!=4 && $row['value']!=5 && $row['value']!=6 && $row['value']!=10 && $row['value']!=18){
				
				$str_count = "";
				if($row['value']==1){
					$str_count = " <font color=blue>(".$noinhomecount.")</font>";
				}
				if($row['value']==21){
					$str_count = " <font color=blue>(".$waithandlecount.")</font>";
				}
				if($row['value']==7){
					$str_count = " <font color=blue>(".$payedcount.")</font>";
				}

				if($row['value']==3){
					$str_count = " <font color=blue>(".$status3count.")</font>";
				}

				if($row['value']==8){
					$str_count = " <font color=blue>(".$status8count.")</font>";
				}
				if($row['value']==14){
					$str_count = " <font color=blue>(".$status14count.")</font>";
				}
				if($row['value']==16){
					$str_count = " <font color=blue>(".$status16count.")</font>";
				}

				if($row['value']==19){
					$str_count = " <font color=blue>(".$status19count.")</font>";
				}
				if($row['value']==20){
					$str_count = " <font color=blue>(".$status20count.")</font>";
				}
				if($row['value']==99){
					$str_count = " <font color=blue>(".$status99count.")</font>";
				}

			echo '<a '.$sel.' href="?m=house&c=admin_house&a=house_jihuo_waybill&hid='.$this->hid.'&status='.$row['value'].'">'.L('waybill_status'.$row['value']).$str_count.' </a> &nbsp;';
			}
		 }?>
		 
				</div>
		</td>
		</tr>
		
    </tbody>
</table>

<table width="100%" cellspacing="0" class="search-form">
    <tbody>
	
		<tr>
		<td><div class="explain-col"> <b><?php echo L('operation');?>：</b>
<?php if($status==3){
echo '<input style="display:none;" name="waybill_handle_button" id="waybill_handle_button" type="button" class="button" value="'.L('handle').'" onclick="javascript:waybill_handle();void(0);">&nbsp;&nbsp;
	 <input style="display:none;" name="waybill_morehandle_button" id="waybill_morehandle_button" type="button" class="button" value="'.L('import_batch_handle').'" onclick="javascript:waybill_morehandle();void(0);">&nbsp;&nbsp;
		 <input style="display:none;" name="waybill_morehandle_buttond" id="waybill_morehandle_buttond" type="button" class="button" value="'.L('download_waybill_template').'" onclick="document.getElementById(\'act\').value=\'simpledownload\';document.getElementById(\'my_search\').submit();">&nbsp;&nbsp;';
		?>
		分
<select id='split_number2' name="split_number2" size='1' onchange="document.getElementById('split_number').value=this.value;" >
  <option value='0' selected >请选择分箱数量</option>
  <option value='1'>1</option><option value='2'>2</option><option value='3'>3</option><option value='4'>4</option><option value='5'>5</option>

</select> 
箱 &nbsp;
<button type="button" class="btn-login btn-cancel radius-three fl" tabindex="18" onclick="split_box()">分箱发货</button>&nbsp;&nbsp;<button type="button" class="btn-login btn-cancel radius-three fl" tabindex="18" onclick="union_box()">合箱发货</button>&nbsp;&nbsp;
		<?php
}



 if($status==8){echo ' <input name="waybill_pay_button" id="waybill_pay_button" type="button" class="button" value="'.L('batch_pay').'" onclick="javascript:waybill_morepay();void(0);">&nbsp;&nbsp;';}

 if($status==4){
	 echo '<input name="confirm_handle_button" id="confirm_handle_button" type="button" class="button" value="'.L('confirm_handle').'" onclick="javascript:confirm_handle_waybill(\'\', \'\');void(0);">&nbsp;&nbsp;'; 
	 //
 }
 if($status==18 || $status==7){
//echo '<input name="kaban_handle_button" id="kaban_handle_button" type="button" class="button" value="'.L('outbound_handle').'" onclick="javascript:huodan_build_handle(\'\', \'\');void(0);">&nbsp;&nbsp;';
}
 if($status==10){
//echo ' <input name="kaban_handled_button" id="kaban_handled_button" type="button" class="button" value="出库管理" onclick="javascript:window.location.href=\'/index.php?m=house&c=admin_house&a=house_jihuo_outhouse&hid='.$this->hid.'&checkhash='.$_SESSION['checkhash'].'\';">&nbsp;&nbsp;';
}
if($status==7 || $status==9 || $status==17 || $status==10 || $status==12 || $status==13){
 echo ' <input name="paifasubmit" type="button" class="button" value="'.L('paifa_gotopais').'"  onclick="javascript:paison(\'\', \''.L('paifa_gotopais').'\');void(0);">&nbsp;&nbsp;
	   
	   <input style="display:none;" name="paifasubmit" type="button" class="button" value="'.L('batch_delivery_himself').'"  onclick="javascript:more_paison(\'13\', \''.L('batch_delivery_himself').'\');" >&nbsp;&nbsp;
	   
	   <input style="display:none;" name="paifasubmit" type="button" class="button" value="'.L('batch_delivery_express').'"  onclick="javascript:more_paison(\'14\', \''.L('batch_delivery_express').'\');">&nbsp;&nbsp;

	   <input style="display:none;" name="paifasubmit" type="button" class="button" value="'.L('batch').L('paifa_finish').'"  onclick="javascript:finish(\'\', \''.L('paifa_finish').'\');void(0);">&nbsp;&nbsp;';
}



if($status<4 || $status==8 || $status==21){
	echo '<input name="more_print_button" id="more_print_button" type="button" class="button" value="修改状态" onclick="javascript:move_waybill(\'\', \'\');void(0);">&nbsp;&nbsp;'; 
?>


<input type="button" onclick="javascript:window.location.href='?m=waybill&c=admin_waybill&a=addorder&hid=<?php echo $this->hid;?>&checkhash=<?php echo $_GET['checkhash'];?>';" value="<?php echo L('jh_package_enter_warehouse')?>"/>

<input style="display:none;" name="scanning_outhome" id="scanning_outhome" type="button" class="button" value="扫描枪自动出库" onclick="javascript:window.top.art.dialog({id:'scanning_gun_outstorage'}).close();window.top.art.dialog({id:'scanning_gun_outstorage',iframe:'?m=house&c=admin_house&a=scanning_gun_outstorage&hid=', title:'扫描枪自动出库', width:'850', height:'450', lock:true}, function(){var d = window.top.art.dialog({id:'scanning_gun_outstorage'}).data.iframe;var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'scanning_gun_outstorage'}).close()});void(0);">

<?php
}	
?>
 
		
<span style="color:#ff3300;font-size:14px;"></span>
				</div>
		</td><td align=center>&nbsp;&nbsp;<form name="Qfrm" id="Qfrm" action="/index.php?m=package&c=admin_package&a=Qinhome&checkhash=<?php echo $_GET['checkhash'];?>" method="post"> 用户标识 <input type="text" name="Quid" value="" /> 单号 <input type="text" name="Qcwb" value="" /> 重量 <input type="text" name="Qweight" value="" /> <input type="submit" name="dosubmit" value="快速入库" />
		</form>
		</td>
		</tr>
		
    </tbody>
</table>



	<form name="mysearch" id="my_search" action="" method="get">
	<input type="hidden" name="m" value="<?php echo $_GET['m'];?>"/>
<input type="hidden" name="c" value="<?php echo $_GET['c'];?>"/>
<input type="hidden" name="a"  value="<?php echo $_GET['a'];?>"/>
<input type="hidden" name="hid"  value="<?php echo $this->hid;?>"/>
<input type="hidden" name="status"  value="<?php echo $status;?>"/>
<input type="hidden" name="act" id="act" value=""/>
	
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td><div class="explain-col">
		<span style="/*float:left;*/">
		编号<input size="4" type="text" name="aid" id="aid" value="<?php echo $aid;?>"/>
		标识<input size="7" type="text" name="clientname" id="clientname" value="<?php echo $clientname;?>"/>
		<?php echo L('username');?><input size="8" type="text" name="username" id="username" value="<?php echo $username;?>"/>
		<?php echo L('real_name');?><input size="4" type="text" name="truename" id="truename" value="<?php echo $truename;?>"/>
		运单号<input type="text" size="12" name="keywords" id="keywords" value="<?php echo $keywords;?>"/>

		邮单号<input type="text" size="14" name="keywords2" id="keywords2" value="<?php echo $keywords2;?>"/>
		快递号<input type="text" size="10" name="keywords3" id="keywords3" value="<?php echo $keywords3;?>"/>
		

		<select name="shippingname" id="shippingname" >
		<option value="">发货线路</option>
		<?php
		$fhdatas = getcache('get__enum_data_60', 'commons');
		foreach($fhdatas as $v){
			$sel="";
			if($shippingname==$v['value']){$sel=" selected";}
			echo '<option value="'.$v[value].'" '.$sel.'>'.$v[title].'</option>';
		}
		?>

		</select>

				<div style="display:none;"><?php echo L('time');?><?php echo form::date('start_time', $start_time)?>-<?php echo form::date('end_time', $end_time)?></div>
				<input type="submit" name="gotosearch" value="<?php echo L('now_search');?>"  class="button" onclick="document.getElementById('act').value='';" />
		</span>

				<input type="submit" name="gotosearch1" onclick="document.getElementById('act').value='download';" value="<?php if($status==-1){echo L('pallets');}else{echo L('waybill');}?><?php echo L('export');?>" class="button"/>
		
				</div>
		</td>
		</tr>
    </tbody>
</table>
</form>


<form name="myform" id="mainForm" action="?m=house&c=admin_house&a=listorder" method="post">

<input type="hidden" name="split_number" id="split_number" value="0"/>

<div class="table-list">

<?php if($status!=-1){

echo ' <table width="100%" cellspacing="0">
        <thead>
            <tr>
            <th width="35" align="center"><input type="checkbox"  id="check_box" onclick="selectall(\'aid[]\');"></th>
			
			<th align="center"><font style="color:#333;font-weight:bold;">'.L('number').'</font></th>
			<th align="center"><font style="color:red;font-weight:bold;">用户标识</font></th>
			<th align="center">'.L('username').' / UID</th>
			<th align="center">'.L('truename').'</th>
			
			<th align="center">运单号</th>
			<th align="center">发货线路</th>
			<th style="display:none" align="center">运费(￥)</th>
	
			<th align="center">'.L('status').'</th>
			<th align="center"><a href="/index.php?m=house&c=admin_house&a=house_jihuo_waybill&hid='.$_GET['hid'].'&status='.$_GET['status'].'&act='.$_GET['act'].'&aid='.$_GET['aid'].'&clientname='.$_GET['clientname'].'&username='.$_GET['username'].'&truename='.$_GET['truename'].'&keywords='.$_GET['keywords'].'&keywords2='.$_GET['keywords2'].'&keywords3='.$_GET['keywords3'].'&start_time='.$_GET['start_time'].'&end_time='.$_GET['end_time'].'&gotosearch='.$_GET['gotosearch'].'&checkhash='.$_GET['checkhash'].'&ordertime='.$ordertime.'&shippingname='.$_GET['shippingname'].'" >时间 ↑↓ </a></th>
			<!---<th align="center">发货时间</th>--->
			<!---<th align="center">入库时间</th>--->
			<!---<th align="center">入库人</th>--->
			<th align="center">'.L('operations_manage').'</th>
			<!---<th align="center">内部留言</th>--->
            </tr>
        </thead>
    <tbody>';

if(is_array($datas)){
	$m_db = pc_base::load_model('member_model');
	
	
	foreach($datas as $waybill){
		
		//数据
		//$wsql="select m.amount from t_member m where m.userid='".$waybill['userid']."' order by m.userid desc ";
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
		


		

				$exp="";
				//统计增值服务
				$alladdvalues="";
			

				$waybillallvalues=string2array($waybill['addvalues']);
				foreach($waybillallvalues as  $srv){
						$alladdvalues.=$srv['title'].$srv['currencyname'].$srv['price'].'/'.$srv['unitname']." ";
				}

				$bgc="";
				if($waybill['status']=='6'){ $bgc= 'style="background:#eee"';}
				if(!empty($alladdvalues)){$bgc= 'style="background:#CCFFFF"';}

		echo '<tr '.$bgc.' title="'.$alladdvalues.'">
	<td align="center">';

	 if($waybill['issystem']!='1'){
		echo '<input type="checkbox" name="aid[]" value="'.$waybill['aid'].'" rel="'.$waybill['waybillid'].'" shippingname="'.$waybill['shippingname'].'" alt="'.$waybill['status'].'">';
	 }
    echo '
	</td>
	<td align="center"><font style="color:#333;font-weight:bold;">'.$waybill['aid'].'</font></td>
	<td align="center"><font style="color:red;font-weight:bold;">'.$waybill['takeflag'].'</font></td>
	<td align="center">'.$waybill['username'].' / '.$waybill['userid'].'</td>
	<td align="center">'.$waybill['truename'].'</td>
	
	<td align="center" '.$wayclr.'>'.$waybill['waybillid'].'</td>
	<td align="center">'.$waybill['shippingname'].'</td>
	<td style="display:none" align="center">'.$waybill['payedfee'].'</td>
	<!---<td align="center">'.$waybill['expressnumber'].'</td>--->
	';
	$sty="";

	if($waybill['status']=='6'){ $sty= 'style="color:red"';}
	echo '
	<td align="center" '.$sty.'>'.str_replace(L('enter_xxstorage'),$waybill['placename'],L('waybill_status'.$waybill['status'])).'</td>
	<td align="center">';
	
	if($waybill['status']==3 ){
		echo ($waybill['inhousetime']>0)?date('Y-m-d H:i', $waybill['inhousetime']):'';
	}else if($waybill['status']==21){
		echo ($waybill['handletime']>0)?date('Y-m-d H:i', $waybill['handletime']):'';
	}else if($waybill['status']==8){
		echo ($waybill['comptime']>0)?date('Y-m-d H:i', $waybill['comptime']):'';			
	}else if($waybill['status']==7){
		echo ($waybill['paidtime']>0)?date('Y-m-d H:i', $waybill['paidtime']):'';
	}else if($waybill['status']==14){
		echo ($waybill['sendtime']>0)?date('Y-m-d H:i', $waybill['sendtime']):'';	}
		else{
		echo date('Y-m-d H:i', $waybill['addtime']);
	}
	echo '</td>
	
	
	<!---<td align="center">'.(($waybill['sendtime']>0)?date('Y-m-d H:i:s', $waybill['sendtime']):'').'</td>
	<!---<td align="center">'.(($waybill['operatorid']>0)?date('Y-m-d H:i:s', $waybill['operatorid']):'').'</td>--->
	<!---<td align="center">'. $waybill['operatorname'].'</td>--->
	
	<td align="center">';
	
	if($waybill['status']==0 || $waybill['status']==1){
		echo '<a style="color:green;" href="javascript:instorage('.$waybill['aid'].', \''.safe_replace($waybill['waybillid']).'\',3);void(0);">'.L('put_in_storage').'</a> ';
	 }

	   if($waybill['status']==21){
		echo ' <a href="javascript:handle('.$waybill['aid'].', \''.$waybill['waybillid'].'\');void(0);" style="color:#ff6600">'.L('handle').'</a>&nbsp;';
	  
		//echo ' <a style="color:blue" href="javascript:returned('.$waybill['aid'].', \''.safe_replace($waybill['waybillid']).'\','.$waybill['status'].');void(0);">'.L('return_goods').'</a> ';
		
		if($express_count>1){
			echo ' <a style="color:green" href="javascript:splitbox_package('.$waybill['aid'].', \''.safe_replace($waybill['waybillid']).'\','.$waybill['status'].');void(0);">'.L('splitbox_package').'</a> ';
		}
	 }

	if( $waybill['status']==8 ){	
	echo '
	 <a  href="javascript:pay('.$waybill['aid'].', \''.$waybill['waybillid'].'\', '.$waybill['status'].');void(0);" style="color:red;">'.L('jh_waybill_pay').'</a>';
	 }

	if($waybill['status']<6){
		echo '
	 <a style="display:none" href="javascript:unionbox('.$waybill['aid'].', \''.$waybill['waybillid'].'\');void(0);">'.L('jh_bill_hebox_handle').'</a> ';
	 }

	 if(trim($waybill['expressnumber'])!=""){
		echo ' <a href="'.str_replace("#",trim($waybill['expressnumber']),trim($waybill['expressurl'])).'" target="_blank">'.L('express_tracking').'</a>';
	}
	if($waybill['status']==3){
		//echo ' <a style="color:green" href="index.php?m=waybill&c=admin_waybill&a=sendgoods&aid='.$waybill[aid].'&checkhash='.$_SESSION['checkhash'].'">发货</a>';
	}
	if($waybill['status']==1 || $waybill['status']==3 || $waybill['status']==21){	
	echo '
	<a href="?m=house&c=admin_house&a=printbill&hid='.$this->hid.'&bid='.$waybill['aid'].'" target="_blank">'.L('print').'</a> ';
		}
if($waybill['status']<22){
	echo '<a href="javascript:editwaybill('.$waybill['aid'].', \''.safe_replace($waybill['waybillid']).'\');void(0);">  '.L('edit').'</a>';
}
	echo '
	<a href="?m=house&c=admin_house&a=house_jihuo_showbill&hid='.$this->hid.'&bid='.$waybill['aid'].'" target="_blank">'.L('view').'</a>
	</td>
	<!---<td align="center">
	
	</td>--->
	</tr>
	
	<tr>
	<td colspan="10" style="border-bottom: 2px solid #0079ad;">
	<div style="width: 860px;word-wrap: break-word;">分/合箱: '.$waybill['otherremark'].'    
	<font color=blue>'.str_replace('2','分箱',str_replace('1','合箱',str_replace('0','单票直发',$waybill['srvtype']))).'</font></div>
	<div style="width: 860px;word-wrap: break-word;">备注:'.$waybill['remark'].'</div>
	<p style="width: 860px;word-wrap: break-word;padding-bottom:5px;">
	快递公司:'.$waybill['expressname'].'&nbsp;&nbsp;快递单号:'.$waybill['expressno'].'&nbsp;&nbsp;物品名称:'.$waybill['goodsname'].'
	</p>
	<div style="color:red;border-top:1px dashed #ccc;">
	'.$waybill['messagedata'].'<p><textarea name="messagedata'.$waybill['aid'].'" id="messagedata'.$waybill['aid'].'"></textarea> 
	<input type="button" value="submit" onclick="postMessage(\'messagedata\','.$waybill['aid'].');"></p>
	</div>
	</td>
	</tr>
	';


	}
}

echo '</tbody>
    </table>
  
    <div class="btn"><label for="check_box">'.L('selected_all').'/'.L('cancel').'</label>';

	if($_GET['a']=='house_jihuo'){
echo '<input name="submit" type="submit" class="button" value="'.L('remove_all_selected').'" onClick="document.myform.action=\'?m=package&c=admin_package&a=delete\';return confirm(\''.L('affirm_delete').'\')">&nbsp;&nbsp;';
 } 
 if($_GET['a']!='house_jihuo' && $status==99){
 
 echo '<input name="submit" type="submit" class="button" value="'.L('remove_all_selected').'" onClick="document.myform.action=\'?m=waybill&c=admin_waybill&a=delete\';return confirm(\''.L('affirm_delete').'\')">&nbsp;&nbsp;';
 } 

 if( $status==7 || $status==14|| $status==8 || $status==21 || $status==16){
 echo ' <input style="margin-left:20px;" name="more_print_button" id="more_print_button" type="submit" class="button" value="'.L('batch_print').' '.L('kuaidi_bill').'" onclick="document.myform.action=\'?m=house&c=admin_house&a=more_print\';document.myform.target=\'_blank\';">';
 }
 
 if($status==1 || $status==2 || $status==3 || $status==4 || $status==8){
 echo ' <input style="display:none;" name="buttonkaiban" type="button" class="button" value="'.L('cancel_waybill_handle').'" onclick="javascript:cancel_waybill(\'\', \'\');void(0);">&nbsp;&nbsp;';
 
 }
 
 echo '<input style="margin-left:20px;" name="more_print_button" id="more_print_button" type="submit" class="button" value="'.L('batch_print').' '.L('bar_code').'" onclick="document.myform.action=\'?m=house&c=admin_house&a=more_print_barcode\';document.myform.target=\'_blank\';">&nbsp;&nbsp;

	   </div> 
	   <div id="pages">'.$this->wbdb->pages.'';
 
 }?> &nbsp;&nbsp;<input type="text" value="" size=6 id="pnum"/> <input type="button" value="GO" onclick="window.location.href='<?php echo '/index.php?m=house&c=admin_house&a=house_jihuo_waybill&hid='.$this->hid.'&act=&keywords='.$keywords.'&_number='.$_number.'&userid='.$userid.'&username='.$username.'&truename='.$truename.'&status='.$status.'&start_time='.$start_time.'&end_time='.$end_time.'&gotosearch='.$gotosearch.'&checkhash='.$_GET['checkhash'];?>&page='+document.getElementById('pnum').value;"/></div></div>
 

 <input type="submit" name="dosubmit" id="dosubmit_btn" style="display:none;"/>

</form>
</div>
</body>
</html>
<script type="text/javascript">
function postMessage(message,id){
	var content = $("#"+message+id).val();
	if(content==""){
		window.top.art.dialog({content:"please input content",lock:true,width:'220',height:'70'}, function(){});

		return false;
	}else{
		$.post("/api.php?op=public_api&a=postmessage&uname=<?php echo $this->username;?>&aid="+id,{"messagedata":content},function(data){
			if(data=="1"){
				//alert("send succssfull!");
				window.location.reload();
				//window.location.href="/index.php?m=house&c=admin_house&a=house_jihuo_waybill&hid=<?php echo $this->hid;?>&checkhash=<?php echo $_GET['checkhash'];?>";
			}
		});
	}
}

function splitbox_package(id, title,status) {
	window.top.art.dialog({id:'splitbox'}).close();
	window.top.art.dialog({title:'<?php echo L('splitbox_package')?>--'+title, id:'splitbox', iframe:'?m=package&c=admin_package&a=splitbox&sta='+status+'&aid='+id ,width:'850px',height:'450px'}, function(){var d = window.top.art.dialog({id:'splitbox'}).data.iframe;
	var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'splitbox'}).close()});
}

function returned(id, title,status) {
	window.top.art.dialog({id:'returned'}).close();
	window.top.art.dialog({title:'<?php echo L('returned_package')?>--'+title, id:'returned', iframe:'?m=package&c=admin_package&a=returned&sta='+status+'&aid='+id ,width:'850px',height:'450px'}, function(){var d = window.top.art.dialog({id:'returned'}).data.iframe;
	var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'returned'}).close()});
}

function waybill_morepay(){
	window.top.art.dialog({id:'waybill_morepay'}).close();
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
		window.top.art.dialog({id:'waybill_morepay'}).close();
		window.top.art.dialog({title:'<?php echo L('batch_pay');?>', id:'waybill_morepay', iframe:'?m=waybill&c=admin_waybill&a=waybill_morepay&ids='+str ,width:'580px',height:'480px'}, function(){var d = window.top.art.dialog({id:'waybill_morepay'}).data.iframe;
		var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'waybill_morepay'}).close()});
	}
}
function move_waybill(id, title) {
	window.top.art.dialog({id:'move_waybill'}).close();
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
		window.top.art.dialog({id:'move_waybill'}).close();
		window.top.art.dialog({title:'批量转移'+title, id:'move_waybill', iframe:'?m=waybill&c=admin_waybill&a=move_waybill&ids='+str ,width:'550px',height:'480px'}, function(){var d = window.top.art.dialog({id:'move_waybill'}).data.iframe;
		var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'move_waybill'}).close()});
	}
}

function more_print() {
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
	}
}

function editwaybill(id, title) {
	window.top.art.dialog({id:'edit'}).close();
	window.top.art.dialog({title:'<?php echo L('edit_waybill')?>--'+title, id:'edit', iframe:'?m=waybill&c=admin_waybill&a=edit&aid='+id ,width:'700px',height:'450px'}, function(){var d = window.top.art.dialog({id:'edit'}).data.iframe;
	var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'edit'}).close()});
}

function edit(id, title) {
	window.top.art.dialog({id:'edit'}).close();
	window.top.art.dialog({title:'<?php echo L('edit_package')?>--'+title, id:'edit', iframe:'?m=package&c=admin_package&a=edit&aid='+id ,width:'700px',height:'450px'}, function(){var d = window.top.art.dialog({id:'edit'}).data.iframe;
	var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'edit'}).close()});
}

function pay(id, title,status) {
	var ispay=true;
	if(status!=8){
		ispay = confirm("<?php echo L('tips_pay_1');?>");
	}
	if(ispay==true){
	window.top.art.dialog({id:'pay'}).close();
	window.top.art.dialog({title:'<?php echo L('jh_waybill_pay')?>--'+title, id:'pay', iframe:'?m=waybill&c=admin_waybill&a=pay&oid='+id ,width:'750px',height:'450px'}, function(){var d = window.top.art.dialog({id:'pay'}).data.iframe;
	var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'pay'}).close()});
	}
}

function waybill_pay(){
	window.top.art.dialog({id:'pay'}).close();
	var str="",title="",status=0;
    $(":checkbox[name='aid[]'][checked]").each(function(){
		if(str==""){
			str=$(this).val();
			title=$(this).attr("rel");
			status=$(this).attr("alt");
		}
    });
	if(str==""){
		window.top.art.dialog({content:"<?php echo L('please_select_qians')?>",lock:true,width:'220',height:'70'}, function(){});
		return false;
	}else{
		pay(str, title,status);
	}
}


function confirm_handle_waybill(id, title) {
	window.top.art.dialog({id:'pay'}).close();
	var str="",title="",status=0;
    $(":checkbox[name='aid[]'][checked]").each(function(){
		if(str==""){
			str=$(this).val();
			title=$(this).attr("rel");
			status=$(this).attr("alt");
		}
    });
	if(str==""){
		window.top.art.dialog({content:"<?php echo L('please_select_qians')?>",lock:true,width:'220',height:'70'}, function(){});
		return false;
	}else{
		window.top.art.dialog({id:'confirm_handle'}).close();
		window.top.art.dialog({title:'<?php echo L('confirm_handle')?>--'+title, id:'confirm_handle', iframe:'?m=waybill&c=admin_waybill&a=confirm_handle_waybill&oid='+str ,width:'750px',height:'450px'}, function(){var d = window.top.art.dialog({id:'confirm_handle'}).data.iframe;
		var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'confirm_handle'}).close()});
	}
}

function unionbox(id, title) {
	window.top.art.dialog({id:'unionbox'}).close();
	window.top.art.dialog({title:'<?php echo L('jh_bill_hebox_handle')?>--'+title, id:'unionbox', iframe:'?m=waybill&c=admin_waybill&a=unionbox&oid='+id ,width:'550px',height:'480px'}, function(){var d = window.top.art.dialog({id:'unionbox'}).data.iframe;
	var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'unionbox'}).close()});
}

function handle(id, title) {
	var str="";
	window.top.art.dialog({id:'handle'}).close();
	window.top.art.dialog({title:'<?php echo L('handle')?>'+title, id:'handle', iframe:'?m=waybill&c=admin_waybill&a=waybill_handle&ids='+id ,width:'750px',height:'480px'}, function(){var d = window.top.art.dialog({id:'handle'}).data.iframe;
	var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'handle'}).close()});
	
}

function waybill_handle(){
	window.top.art.dialog({id:'handle'}).close();
	var str="",title="";
    $(":checkbox[name='aid[]'][checked]").each(function(){
		if(str==""){
			str=$(this).val();
			title=$(this).attr("rel");
		}
    });
	if(str==""){
		window.top.art.dialog({content:"<?php echo L('please_select_qians')?>",lock:true,width:'420',height:'70'}, function(){});
		return false;
	}else{
		handle(str, title);
	}
}

function kaban_handle(id, title) {
	window.top.art.dialog({id:'kaiban_handle'}).close();
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
	window.top.art.dialog({title:'<?php echo L('kaiban_handle')?>'+title, id:'kaiban_handle', iframe:'?m=waybill&c=admin_waybill&a=waybill_kaban_handle&ids='+str ,width:'550px',height:'480px'}, function(){var d = window.top.art.dialog({id:'kaiban_handle'}).data.iframe;
	var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'kaiban_handle'}).close()});
	}
}

function waybill_morehandle(){
	window.top.art.dialog({id:'moreadd'}).close();window.top.art.dialog({id:'moreadd',iframe:'?m=house&c=admin_house&a=house_waybill_morehandle&hid=<?php echo $this->hid;?>', title:'<?php echo L('import_batch_handle');?>', width:'900', height:'500', lock:true}, function(){var d = window.top.art.dialog({id:'moreadd'}).data.iframe;var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'moreadd'}).close()});
}



//运单取消
function cancel_waybill(id, title) {
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

		$.get('?m=waybill&c=admin_waybill&a=cancel_waybill_handle&checkhash=<?php echo $_SESSION['checkhash'];?>&ids='+str,function(data){
			window.top.art.dialog({content:data,lock:true,width:'220',height:'70'}, function(){});
		});

	}
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
	window.top.art.dialog({title:'<?php echo L('outbound_handle');?>'+title, id:'kaiban_handle', iframe:'?m=waybill&c=admin_waybill&a=huodan_build_handle&hid=<?php echo $this->hid;?>&ids='+str ,width:'750px',height:'480px'}, function(){var d = window.top.art.dialog({id:'kaiban_handle'}).data.iframe;
	var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'kaiban_handle'}).close()});
	}
}

function instorage(id, title,status) {
	window.top.art.dialog({id:'edit_pack'}).close();
	window.top.art.dialog({title:'<?php echo L('edit_package')?>--'+title, id:'edit_pack', iframe:'?m=package&c=admin_package&a=edit_pack&sta='+status+'&aid='+id ,width:'700px',height:'500px'}, function(){var d = window.top.art.dialog({id:'edit_pack'}).data.iframe;
	var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'edit_pack'}).close()});
}

</script>

<script type="text/javascript">
   function allchk(obj){
     $("input[name='aid[]']").attr("checked",obj.checked);
   }

	function split_box(){
	
		if($("#split_number").val()==0){
			alert("请选择分箱数量");
			return false;
		}
		var str="";
		$("input[name='aid[]']:checked").each(function(data){
			if(str==""){str=$(this).val();}else{
				str+=","+$(this).val();
			}
		});

		if(str==""){
			alert("请勾选需要操作的记录");
			return false;
		}else{
		
		
			$("#mainForm").attr("action","/index.php?m=package&c=admin_package&a=splitbox&hid=<?php echo $this->hid;?>");
			$("#dosubmit_btn").click();
		
		}

	}

	function union_box(){
		
		var str="";
		$("input[name='aid[]']:checked").each(function(data){
			if(str==""){str=$(this).val();}else{
				str+=","+$(this).val();
			}
		});

		if(str==""){
			alert("请勾选需要操作的记录");
			return false;
		}else{
		
		
			$("#mainForm").attr("action","/index.php?m=package&c=admin_package&a=unionbox&hid=<?php echo $this->hid;?>");
			$("#dosubmit_btn").click();
		
		}
	}

   </script>


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
		window.top.art.dialog({title:tit, id:'paison', iframe:'?m=waybill&c=admin_waybill&a=waybill_morepaison&status='+id+'&ids='+str+'&hid=<?php echo $this->hid;?>' ,width:'830px',height:'450px'}, function(){var d = window.top.art.dialog({id:'paison'}).data.iframe;
		var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'paison'}).close()});
	}
	
}




function paison(id, title) {
		var str="";
		var shippingname="";
		$(":checkbox[name='aid[]'][checked]").each(function(){
			if(str==""){
				str=$(this).attr('rel');
				shippingname=$(this).attr('shippingname');
			}
			
		});
		window.top.art.dialog({id:'paison'}).close();
		window.top.art.dialog({title:'<?php echo L('paifa_gotopais')?>--'+title, id:'paison', iframe:'?m=waybill&c=admin_waybill&a=waybill_paison&hid=<?php echo $this->hid;?>&shippingname='+shippingname+'&waybillid='+str ,width:'500px',height:'450px'}, function(){var d = window.top.art.dialog({id:'paison'}).data.iframe;
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

	function delRow(obj){
		var delid = $(obj).parent().parent().attr("id");
		if(delid!='trProduct'){
			$("#"+delid).remove();
			orderRow();
		}
		computtotal();
		
	}

</script>