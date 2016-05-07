<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<style>
.curr{font-weight:bold;color:#ff6600;}
</style>
<div style="display:none;"></div>
<div class="temp_content-menu" style="display:none;">
        <a href='?m=house&c=admin_house&a=house_jihuo&hid=<?php echo $this->hid;?>' ><em><?php echo L('jh_package_manage')?></em></a><span>|</span><a href='?m=house&c=admin_house&a=house_jihuo_waybill&hid=<?php echo $this->hid;?>' ><em><?php echo L('jh_waybill_manage')?></em></a><span>|</span><a href='?m=house&c=admin_house&a=house_jihuo_returned&hid=<?php echo $this->hid;?>' <?php echo ' class="on"';?>><em><?php echo L('house_jihuo_returned_manage')?></em></a>  </div>

<script>$(".temp_content-menu").remove();</script>
<div class="pad-lr-10">
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td><div class="explain-col"> <b><?php echo L('status');?>：</b>
		
		 <?php 
			 $allwaybilltatus = $this->getwaybilltatus();
			 foreach($allwaybilltatus as $row){
			 $sel="";
			 if($row['value']==$status){$sel= ' class="curr"';}
			if($row['value']==7 || $row['value']==8 || $row['value']==9 || $row['value']==11 || ($row['value']>=13 && $row['value']<=16)){
			echo '<a '.$sel.' href="?m=house&c=admin_house&a='.$_GET['a'].'&hid='.$this->hid.'&status='.$row['value'].'">'.L('waybill_status'.$row['value']).'</a> &nbsp;';
			}
		 }?>
		 
				</div>
		</td>
		</tr>
		
    </tbody>
</table>

<table width="100%" cellspacing="0" class="search-form" style="display:none;">
    <tbody>
		<tr>
		<td><div class="explain-col"> <b><?php echo L('operation');?>：</b>

		

				</div>
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
		<td><div class="explain-col" align=right>  <span style="float:left;">
		<?php echo L('keywords');?>：<input type="text" size="8" name="keywords" id="keywords" value="<?php echo $keywords;?>"/>&nbsp;<?php echo L('number');?>：<input size="8" type="text" name="_number" id="_number" value="<?php echo $_number;?>"/>&nbsp;UserID：<input size="8" type="text" name="userid" id="userid" value="<?php echo $userid;?>"/>&nbsp;<?php echo L('username');?>：<input size="8" type="text" name="username" id="username" value="<?php echo $username;?>"/>&nbsp;<?php echo L('real_name');?>：<input size="10" type="text" name="truename" id="truename" value="<?php echo $truename;?>"/>&nbsp;
		&nbsp;<?php echo L('time');?>：<?php echo form::date('start_time', $start_time)?>-
				<?php echo form::date('end_time', $end_time)?>&nbsp;<input type="submit" name="gotosearch" value="<?php echo L('now_search');?>"  class="button" onclick="document.getElementById('act').value='';" />&nbsp;</span><input type="submit" name="gotosearch1" onclick="document.getElementById('act').value='download';" value="<?php if($status==-1){echo L('pallets');}else{echo L('waybill');}?><?php echo L('export');?>" class="button"/>
		
				</div>
		</td>
		</tr>
    </tbody>
</table>
</form>


<form name="myform" id="myform_list" action="?m=house&c=admin_house&a=listorder" method="post">
<div class="table-list">

<?php if($status==-1){
echo '<table width="100%" cellspacing="0">
        <thead>
            <tr>
            <th width="35" align="center"><input type="checkbox"  id="check_box" onclick="selectall(\'aid[]\');"></th>
			<th align="center">序号</th>
			<th align="center">'.L('kaiban_username').'</th>
			<th align="center">'.L('kaiban_kabanno').'</th>
			<th align="center">'.L('waybillid').'</th>
			<th align="center">'.L('inputtime').'</th>
			<th align="center">'.L('operations_manage').'</th>
            </tr>
        </thead>'; 

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
		}

echo '<tbody>
	</table>
<div class="btn">
       <input name="submit" type="button" class="button" value="'.L('huodan_build_handle').'" onclick="javascript:huodan_build_handle(\'\', \'\');void(0);">&nbsp;&nbsp;
	   </div> 
	<div id="pages">'.$this->kbdb->pages.'</div>';


}else{

echo ' <table width="100%" cellspacing="0">
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
			<th align="center">'.L('phone').'</th>
			<th align="center">'.L('status').'</th>
			<th align="center">'.L('inputtime').'</th>
			<th align="center">'.L('operations_manage').'</th>
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
		


		$allexp=$this->getwaybill_goods(trim($waybill['waybillid']), $waybill['returncode']);
				$exp="";
				//统计增值服务
				$alladdvalues="";

				foreach($allexp as $v){
					//$exp.=$v['expressno']."<br>";
					$shipp = $this->getallship2($v['expressid']);
					$exurl=str_replace('#',$v['expressno'],$shipp['remark']);
					$exp.='<a href="'.$exurl.'" target=_blank style="color:'. $this->getpackage_color($v['expressno'],$v['returncode']).'" rel='.$v['expressid'].'>'.$v['expressno'].'</a><br>';
				
			
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

		echo '<tr '.$bgc.' title="'.$alladdvalues.'">
	<td align="center">';

	 if($waybill['issystem']!='1'){
		echo '<input type="checkbox" name="aid[]" value="'.$waybill['aid'].'" rel="'.$waybill['waybillid'].'" alt="'.$waybill['status'].'">';
	 }
    echo '
	</td>
	<td align="center">'.$waybill['aid'].'</td>
	<td align="center">'.$waybill['userid'].'</td>
	<td align="center">'.$waybill['username'].'</td>
	<td align="center">'.$waybill['truename'].'</td>
	<td align="center" '.$wayclr.'>'.$waybill['waybillid'].'</td>
	<td align="center">'.$exp.'</td>
	<td align="center">'.$takeperson.'</td>
	<td align="center">'.$takecity.'</td>
	<td align="center">'.$mobile.'</td>';
	$sty="";
	if($waybill['status']=='6'){ $sty= 'style="color:red"';}
	echo '<td align="center" '.$sty.'>'.str_replace(L('enter_xxstorage'),$waybill['placename'],L('waybill_status'.$waybill['status'])).'</td>
	<td align="center">'.date('Y-m-d H:i:s', $waybill['addtime']).'</td>
	<td align="center">';
	
	   if($waybill['status']==3){
		echo ' <a href="javascript:handle('.$waybill['aid'].', \''.$waybill['waybillid'].'\');void(0);" style="color:#ff6600">'.L('handle').'</a>&nbsp;';
	   }


	  


	if($waybill['status']==1 || $waybill['status']==3){
		echo ' <a style="color:blue" href="javascript:returned('.$waybill['aid'].', \''.safe_replace($waybill['waybillid']).'\','.$waybill['status'].');void(0);">'.L('return_goods').'</a> ';
	 }

	if( $waybill['status']==8 ){	
	echo '
	 <a  href="javascript:pay('.$waybill['aid'].', \''.$waybill['waybillid'].'\', '.$waybill['status'].');void(0);" style="color:green;">'.L('jh_waybill_pay').'</a>';
	 }

	if($waybill['status']<6){
		echo '
	 <a style="display:none" href="javascript:unionbox('.$waybill['aid'].', \''.$waybill['waybillid'].'\');void(0);">'.L('jh_bill_hebox_handle').'</a> ';
	 }
	 if(trim($waybill['expressnumber'])!=""){
		echo ' | <a href="'.str_replace("#",trim($waybill['expressnumber']),trim($waybill['expressurl'])).'" target="_blank">'.L('express_tracking').'</a>';
	}
	
if($waybill['returnedstatus']==3 && ($waybill['status']==15 || $waybill['status']==11)){

	echo '<a style="color:#ff6600;" href="javascript:delivery_returned_handle_waybill(\''.$waybill['returncode'].'\', \''.safe_replace($waybill['waybillid']).'\');void(0);">'.L('delivery_returned_handle_waybill').'</a>';
}

 if($waybill['returnedstatus']==2 && $waybill['status']==15){
			echo ' <a style="color:blue" href="javascript:waybill_confirm_return_goods(\''.$waybill['aid'].'\', \''.safe_replace($waybill['waybillid']).'\');void(0);">'.L('confirm_common').L('return_goods').'</a> ';
		}
echo '
	<a href="?m=house&c=admin_house&a=printbill&hid='.$this->hid.'&bid='.$waybill['aid'].'" target="_blank">'.L('print').'</a> ';
	echo '
	<a href="?m=house&c=admin_house&a=house_jihuo_showbill&hid='.$this->hid.'&bid='.$waybill['aid'].'" >'.L('view').'</a>
	</td>
	</tr>';


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

 if( $status==18 || $status==7){
 echo ' <input name="buttonkaiban" type="button" class="button" value="'.L('outbound_handle').'" onclick="javascript:huodan_build_handle(\'\', \'\');void(0);">&nbsp;&nbsp;
	   ';
 }
 
 if($status==1 || $status==2 || $status==3 || $status==4 || $status==8){
 echo ' <input name="buttonkaiban" type="button" class="button" value="'.L('cancel_waybill_handle').'" onclick="javascript:cancel_waybill(\'\', \'\');void(0);">&nbsp;&nbsp;';
 
 }
 
 echo '<input name="more_print_button" id="more_print_button" type="submit" class="button" value="'.L('batch_print').'" onclick="document.myform.action=\'?m=house&c=admin_house&a=more_print\';document.myform.target=\'_blank\';">&nbsp;&nbsp;
		<input name="more_print_button" id="more_print_button" type="submit" class="button" value="'.L('batch_print').' '.L('bar_code').'" onclick="document.myform.action=\'?m=house&c=admin_house&a=more_print_barcode\';document.myform.target=\'_blank\';">&nbsp;&nbsp;

	   </div> 
	   <div id="pages">'.$this->wbdb->pages.'';
 
 }?> &nbsp;&nbsp;<input type="text" value="" size=6 id="pnum"/> <input type="button" value="GO" onclick="window.location.href='<?php echo '/index.php?m=house&c=admin_house&a=house_jihuo_waybill&hid='.$this->hid.'&act=&keywords='.$keywords.'&_number='.$_number.'&userid='.$userid.'&username='.$username.'&truename='.$truename.'&status='.$status.'&start_time='.$start_time.'&end_time='.$end_time.'&gotosearch='.$gotosearch.'&checkhash='.$_GET['checkhash'];?>&page='+document.getElementById('pnum').value;"/></div></div>
 
</form>
</div>
</body>
</html>
<script type="text/javascript">


function waybill_confirm_return_goods(id, title) {
		
		window.top.art.dialog({id:'waybill_confirm_return_goods'}).close();
		window.top.art.dialog({title:'<?php echo L('confirm_common').L('return_goods');?>--'+title, id:'waybill_confirm_return_goods', iframe:'?m=waybill&c=admin_waybill&a=waybill_confirm_return_goods&hid=<?php echo $this->hid;?>&aid='+id+'&waybillid='+title ,width:'700px',height:'450px'}, function(){var d = window.top.art.dialog({id:'waybill_confirm_return_goods'}).data.iframe;
		var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'waybill_confirm_return_goods'}).close()});
	
}

function delivery_returned_handle_waybill(id, title) {
		
		window.top.art.dialog({id:'paison'}).close();
		window.top.art.dialog({title:'<?php echo L('paifa_gotopais')?>--'+title, id:'paison', iframe:'?m=waybill&c=admin_waybill&a=waybill_paison&hid=<?php echo $this->hid;?>&returncode='+id+'&waybillid='+title ,width:'700px',height:'450px'}, function(){var d = window.top.art.dialog({id:'paison'}).data.iframe;
		var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'paison'}).close()});
	
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

</script>