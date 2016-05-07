<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<div class="pad-10">

<div id="outdata"></div>
<form method="post" action="?m=house&c=admin_house&a=house_jihuo_moreimport&hid=<?php echo $this->hid?>&checkhash=<?php echo $_SESSION['checkhash']?>" name="myform" id="myform" >
<table class="table_form" width="100%" cellspacing="0">
<tbody>
<tr>
		<th ><strong>EMS <?php echo L('excel_import_template');?></strong></th>
		<td><a href="/caches/caches_batch/ems.xls"><?php echo L('download');?></a>
		</td>
	</tr>
	<tr>
		<th ><strong><?php echo L('importfile')?></strong></th>
		<td>
			<?php echo form::upfiles('importpath','importpath','','waybill', '0',50,'', '','xls|xlsx');?>

							
					<span class="tip"><?php echo L('support');?> xls(<font color=red><?php echo L('recommend');?></font>),<?php echo L('xlsx_doc');?></span>
		</td>
	</tr>
	</tbody>
</table>
<center>
<input type="button" name="dosubmit" id="dosubmit" value=" <?php echo L('ok').L('import');?> "  onclick="getdata()">&nbsp;<input type="reset" class="dialog" value=" <?php echo L('clear')?> "></center>
</form>
</div>
</body>
</html>
<script type="text/javascript">
parent.$(".aui_state_highlight").hide();
function getdata(){
	$("#outdata").html('');
	$.post("?m=house&c=admin_house&a=house_jihuo_moreimport&hid=<?php echo $this->hid?>&checkhash=<?php echo $_SESSION['checkhash']?>",{dosubmit:1,importpath:$("#importpath").val()},function(data){
		$("#outdata").html(data);
	});
}

</script>
