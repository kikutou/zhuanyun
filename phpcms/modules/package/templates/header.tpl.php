<style>
.curr{font-weight:bold;}
</style>
<div class="temp_content-menu" style="display:none;">
        <a href='?m=house&c=admin_house&a=house_jihuo&hid=<?php echo $this->hid;?>' class="on"><em><?php echo L('jh_package_manage')?></em></a><span>|</span><a href='?m=house&c=admin_house&a=house_jihuo_waybill&hid=<?php echo $this->hid;?>' ><em><?php echo L('jh_waybill_manage')?></em></a> <span>|</span><a href='?m=house&c=admin_house&a=house_jihuo_returned&hid=<?php echo $this->hid;?>' ><em><?php echo L('house_jihuo_returned_manage')?></em></a>   </div>

<script>$(".temp_content-menu").remove();</script>
<div class="pad-lr-10">
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td><div class="explain-col"> 
		 <a <?php if($_GET['a']=='house_jihuo'){echo ' class="curr"';}?> href="?m=house&c=admin_house&a=house_jihuo&hid=<?php echo $this->hid;?>"><?php echo L('jh_package_all')?></a> &nbsp;&nbsp;
		 <a href="javascript:window.top.art.dialog({id:'add'}).close();window.top.art.dialog({id:'add',iframe:'?m=package&c=admin_package&a=add&hid=<?php echo $this->hid;?>', title:'<?php echo L('jh_package_enter_warehouse')?>', width:'700', height:'500', lock:true}, function(){var d = window.top.art.dialog({id:'add'}).data.iframe;var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'add'}).close()});void(0);"><?php echo L('jh_package_enter_warehouse')?></a> &nbsp;&nbsp;
		 <a <?php if($_GET['a']=='house_jihuo_overdate'){echo ' class="curr"';}?> href="?m=house&c=admin_house&a=house_jihuo_overdate&hid=<?php echo $this->hid;?>"><?php echo L('jh_package_expire')?></a> &nbsp;&nbsp;
		 <a href="javascript:window.top.art.dialog({id:'moreadd'}).close();window.top.art.dialog({id:'moreadd',iframe:'?m=house&c=admin_house&a=house_jihuo_moreadd&hid=<?php echo $this->hid;?>', title:'<?php echo L('jh_package_moreadd')?>', width:'900', height:'500', lock:true}, function(){var d = window.top.art.dialog({id:'moreadd'}).data.iframe;var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'moreadd'}).close()});void(0);" ><?php echo L('jh_package_moreadd')?></a> &nbsp;&nbsp;
		
		<!--<a <?php if($_GET['a']=='house_jihuo_exception'){echo ' class="curr"';}?> href="?m=house&c=admin_house&a=house_jihuo_exception&hid=<?php echo $this->hid;?>"><?php echo L('status5');?></a> &nbsp;&nbsp;
		-->
		<a href="javascript:window.top.art.dialog({id:'moreimport'}).close();window.top.art.dialog({id:'moreimport',iframe:'?m=house&c=admin_house&a=house_jihuo_moreimport&hid=<?php echo $this->hid;?>', title:'<?php echo L('batch_import_expressno')?>', width:'900', height:'450', lock:true}, function(){var d = window.top.art.dialog({id:'moreimport'}).data.iframe;var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'moreimport'}).close()});void(0);"  style="color:#ff6600"><?php echo L('batch_import_expressno')?></a> &nbsp;&nbsp;

		<a href="javascript:window.top.art.dialog({id:'scanning_gun_storage'}).close();window.top.art.dialog({id:'scanning_gun_storage',iframe:'?m=house&c=admin_house&a=scanning_gun_storage&hid=<?php echo $this->hid;?>', title:'<?php echo L('scanning_gun_storage')?>', width:'850', height:'450', lock:true}, function(){var d = window.top.art.dialog({id:'scanning_gun_storage'}).data.iframe;var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'scanning_gun_storage'}).close()});void(0);"  style="color:blue"><?php echo L('scanning_gun_storage')?></a> &nbsp;&nbsp;

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
<input type="hidden" name="act" id="act" value=""/>

<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td><div class="explain-col" align="right"> <span style="float:left;">
		<?php echo L('keywords')?>：<input type="text" size="5" name="keywords" id="keywords" value="<?php echo $keywords;?>"/>&nbsp;<?php echo L('number')?>：<input size="5" type="text" name="_number" id="_number" value="<?php echo $_number;?>"/>&nbsp;UserID：<input size="5" type="text" name="userid" id="userid" value="<?php echo $userid;?>"/>&nbsp;<?php echo L('username')?>：<input size="5" type="text" name="username" id="username" value="<?php echo $username;?>"/>&nbsp;<?php echo L('real_name')?>：<input size="5" type="text" name="truename" id="truename" value="<?php echo $truename;?>"/>&nbsp;
		<select name="status" id="status">
		<option value="-1"><?php echo L('please_select')?></option>
		<?php
		$allgetpackstatus = $this->getpackstatus();
		foreach($allgetpackstatus as $v){
			$sel='';
			if($status==$v['value'])
				$sel=' selected';
			
			echo '<option value="'.$v['value'].'" '.$sel.'>'.L('package_status'.$v['value']).'</option>';
		}
		?>
		</select>&nbsp;<?php echo L('time')?>：<?php echo form::date('start_time', $start_time)?>-
				<?php echo form::date('end_time', $end_time)?>&nbsp;<input type="submit" name="gotosearch" value="<?php echo L('now_search')?>"  class="button" onclick="document.getElementById('act').value='';" />
		
				</span><input type="submit" name="gotosearch1" onclick="document.getElementById('act').value='download';" value="<?php echo L('export_package')?>" class="button"/></div>
		</td>
		</tr>
    </tbody>
</table>
</form>
