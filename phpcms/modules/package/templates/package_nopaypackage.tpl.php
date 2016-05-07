<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<div class="pad-lr-10">


  <link rel="stylesheet" type="text/css" href="/resource/js/calendar/jscal2.css"/>
			<link rel="stylesheet" type="text/css" href="/resource/js/calendar/border-radius.css"/>
			<link rel="stylesheet" type="text/css" href="/resource/js/calendar/win2k.css"/>
			<script type="text/javascript" src="/resource/js/calendar/calendar.js"></script>
			<script type="text/javascript" src="/resource/js/calendar/lang/en.js"></script>


<form name="myform_search" action="?m=package&c=admin_package&a=<?php echo $_GET['a'];?>" method="post">
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td><div class="explain-col" align="right"> <span style="float:left;">
		关健字：<input type="text"  name="keywords" id="keywords" value="<?php echo $keywords;?>"/>&nbsp;&nbsp;UserID：<input size="5" type="text" name="userid" id="userid" value="<?php echo $userid;?>"/>&nbsp;<?php echo L('username')?>：<input size="5" type="text" name="username" id="username" value="<?php echo $username;?>"/>&nbsp;&nbsp; <label for="date">起止日期</label>
																<input type="text" name="begindate" id="beginDate" value="" size="15" class="date inp" readonly>&nbsp;
																<script type="text/javascript">
																	Calendar.setup({
																	weekNumbers: true,
																	inputField : "beginDate",
																	trigger    : "beginDate",
																	dateFormat: "%Y-%m-%d",
																	showTime: false,
																	minuteStep: 1,
																	onSelect   : function() {this.hide();}
																	});
																</script>
											                    &nbsp;-&nbsp; 
							                    				<input id="endDate" name="enddate" value="" readonly="readonly" class="date inp " type="text"  type="text" size="15"> <script type="text/javascript">
																	Calendar.setup({
																	weekNumbers: true,
																	inputField : "endDate",
																	trigger    : "endDate",
																	dateFormat: "%Y-%m-%d",
																	showTime: false,
																	minuteStep: 1,
																	onSelect   : function() {this.hide();}
																	});
																</script>  &nbsp;&nbsp;<input type="submit" name="gotosearch" value="<?php echo L('search')?>"  class="button" />
		
				</span></div>
		</td>
		</tr>
    </tbody>
</table>
</form>


<form name="myform" action="?m=package&c=admin_package&a=listorder" method="post">
<div class="table-list">
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
            <th width="35" align="center"><input type="checkbox" value="" id="check_box" onclick="selectall('aid[]');"></th>
			<th width="69" align="center"><?php echo L('num')?></th>
			<th align="center">UID</th>
			<th align="center">UserName</th>
			<th align="center">运单号</th>
			<th align="center"><?php echo L('expressno')?></th>
			<th align="center"><?php echo L('amount')?></th>
			<th  align="center"><?php echo L('weight')?></th>
			<th  align="center"><?php echo L('price')?></th>
			<th  align="center"><?php echo L('remark')?></th>
			<th  align="center">运费</th>
			<th  align="center"><?php echo L('status')?></th>
			<th  align="center"><?php echo L('inputtime')?></th>
			<th width="69" align="center"><?php echo L('operations_manage')?></th>
            </tr>
        </thead>
    <tbody>
 <?php 
if(is_array($datas)){
	foreach($datas as $package){
		$bgc='';
if($package['status']==1){
$bgc=' style="background:#CAFF70"';
}

echo '<tr '.$bgc.'>
	<td align="center"><input type="checkbox" name="aid[]" value="'.$package['aid'].'"></td>
	<td align="center">'.$package['aid'].'</td>
	<td align="center">'.$package['userid'].'</td>
	<td align="center">'.$package['username'].'</td>
	<td align="center">'.$package['waybillid'].'</td>
	<td align="center">'.$package['expressno'].'</td>
	<td align="center">'.$package['totalamount'].'</td>
	<td align="center">'.$package['totalweight'].'</td>
	<td align="center">'.$package['totalprice'].'</td>
	<td align="center">'.$package['otherremark'].'</td>
	<td align="center">'.$package['payedfee'].'</td>
	<td align="center">'.L('waybill_status'.$package['status']).'</td>
	<td align="center">'.date('Y-m-d H:i:s', $package['addtime']).'</td>
	<td align="center">';

	echo '<a href="/index.php?m=house&c=admin_house&a=house_jihuo_showbill&hid='.$this->hid.'&bid='.$package['aid'].'&checkhash='.$_GET['checkhash'].'" >查看</a>&nbsp;&nbsp;<a href="?m=waybill&c=admin_waybill&a=payone&hid='.$this->hid.'&oid='.$package['aid'].'&checkhash='.$_GET['checkhash'].'" onclick="return confirm(\'您确定要扣款?\');">扣款</a>';
	
	echo '</td>
	</tr>'; 
	}
}
?>
</tbody>
    </table>
  


    <p>
	<table border=0><tr><td>
<button name="waybill_pay_button" id="waybill_pay_button" type="button" class="btn-login btn-cancel radius-three fl"  onclick="javascript:waybill_morepay();void(0);">批量扣款</button>&nbsp;&nbsp;</td></tr></table>
<input type="hidden" name="dosubmit" value="1"/>	

</p>


 <div id="pages"><?php echo $this->db->pages;?></div>
</form>
</div>
</body>
</html>
<script type="text/javascript">



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
		
		window.top.art.dialog({content:"请选择操作记录",lock:true,width:'220',height:'70'}, function(){});

		return false;
	}else{
		window.top.art.dialog({id:'waybill_morepay'}).close();
		window.top.art.dialog({title:'批量扣款', id:'waybill_morepay', iframe:'?m=waybill&c=admin_waybill&a=waybill_morepay&ids='+str ,width:'900px',height:'480px'}, function(){var d = window.top.art.dialog({id:'waybill_morepay'}).data.iframe;
		var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'waybill_morepay'}).close()});
	}
}



function edit(id, title) {
	window.top.art.dialog({id:'edit'}).close();
	window.top.art.dialog({title:'<?php echo L('edit_package')?>--'+title, id:'edit', iframe:'?m=package&c=admin_package&a=edit&aid='+id ,width:'600px',height:'520px'}, function(){var d = window.top.art.dialog({id:'edit'}).data.iframe;
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
</script>