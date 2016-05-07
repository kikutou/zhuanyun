<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<div class="pad-10">


<form method="post" action="?m=house&c=admin_house&a=house_waybill_morehandle&hid=<?php echo $this->hid?>" name="myform" id="myform">
<table class="table_form" width="100%" cellspacing="0">
<tbody>
<tr>
		<td colspan=2 align=center style="color:red" height="40"><?php echo L('note_import_say');?></td>
	</tr>
	<tr>
		<th ><strong><?php echo L('importfile')?></strong></th>
		<td>
			<?php echo form::upfiles('importpath','importpath','','package', '0',50,'', '','xls|xlsx');?>					
		<span class="tip"><?php echo L('support');?>xls(<font color=red><?php echo L('recommend');?></font>),<?php echo L('xlsx_doc');?></span>
		</td>
	</tr>
	</tbody>
</table>
<input type="submit" name="dosubmit" id="dosubmit" value=" <?php echo L('ok')?> " class="dialog">&nbsp;<input type="reset" class="dialog" value=" <?php echo L('clear')?> ">
</form>
</div>
</body>
</html>