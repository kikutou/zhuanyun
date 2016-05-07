<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<div class="pad-10">
<form method="post" action="?m=waybill&c=admin_waybill&a=move_waybill&ids=<?php echo $_GET['ids'];?>" name="myform" id="myform">
<table class="table_form" width="100%" cellspacing="0">
<tbody>
<tr>
		<th><strong><?php echo L('selected_records');?>：</strong></th>
		<td><?php echo $_GET['ids'];?></td>
	</tr>
		<tr>
		<th><strong>转到目标：</strong></th>
		<td>
		<select name="waybill[status]" id="status" >
<?php
	foreach($this->getallwaybilltatus() as $row){
		$sel="";
		if($row['value']==$an_info['status']){$sel=' selected';}
		if(($row['value']>0 && $row['value']<9) || $row['value']==99 || $row['value']==18){
		echo '<option value="'.$row['value'].'" '.$sel.'>'.$row['title'].'</option>';
		}
	}
?>
		</select>
		</td>
	</tr>
	
	
	</tbody>
</table>
<input type="submit" name="dosubmit" id="dosubmit" value=" <?php echo L('ok')?> " class="dialog">&nbsp;<input type="reset" class="dialog" value=" <?php echo L('clear')?> ">
</form>
</div>
</body>
</html>
