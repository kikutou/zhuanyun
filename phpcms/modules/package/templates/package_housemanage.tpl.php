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

<form name="myform" action="?m=package&c=admin_package&a=<?php echo $_GET['a'];?>" method="post" id="mainForm">
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
	<td align="center">';
	if(( $package['status']==1 || $package['status']==3) && $package['boxstatus']==0){
		echo '<input type="checkbox" name="aid[]" value="'.$package['aid'].'">';
	}
	echo '</td>
	<td align="center">'.$package['aid'].'</td>
	<td align="center">'.$package['userid'].'</td>
	<td align="center">'.$package['username'].'</td>

	<td align="center">'.$package['waybillid'].'</td>
	<td align="center">'.$package['expressno'].'</td>
	<td align="center">'.$package['totalamount'].'</td>
	<td align="center">'.$package['totalweight'].'</td>
	<td align="center">'.$package['totalprice'].'</td>
	<td align="center">'.$package['otherremark'].'</td>
	<td align="center">'.L('waybill_status'.$package['status']).'</td>
	<td align="center">'.date('Y-m-d H:i:s', $package['addtime']).'</td>
	<td align="center">
	<button type="button" class="btn-login btn-cancel radius-three fl" tabindex="18" onclick="window.location.href=\'/index.php?m=waybill&c=admin_waybill&a=sendgoods&aid='.$package[aid].'&checkhash='.$_SESSION['checkhash'].'\';">发货</button>
	</td>
	</tr>'; 
	}
}
?>
</tbody>
    </table>
  
  <p>
									<table border=0><tr><td>
 分
<select id='split_number' name="split_number" size='1' >
  <option value='0' selected >请选择分箱数量</option>
  <option value='1'>1</option><option value='2'>2</option><option value='3'>3</option><option value='4'>4</option><option value='5'>5</option>

</select> 
箱 </td><td>
<button type="button" class="btn-login btn-cancel radius-three fl" tabindex="18" onclick="split_box()">分箱发货</button>&nbsp;&nbsp;<button type="button" class="btn-login btn-cancel radius-three fl" tabindex="18" onclick="union_box()">合箱发货</button>&nbsp;&nbsp;</td></tr></table>
									</p>
 <div id="pages"><?php echo $this->db->pages;?></div>

<input type="submit" name="dosubmit" id="dosubmit_btn" style="display:none;"/>

</form>
</div>



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

</body>
</html>
