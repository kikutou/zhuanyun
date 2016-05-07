<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<div class="pad-10">
<form method="post" action="?m=waybill&c=admin_waybill&a=unionbox&oid=<?php echo $oid;?>" name="myform" id="myform">
<table class="table_form" width="100%" cellspacing="0">
<tbody>
	<tr>
		<th width="80"><strong><?php echo L('expressnos')?>：</strong></th>
		<td>
		<textarea name="data[packageno]" id="packageno" cols="40" rows=3 onclick="addRows(this)"></textarea><textarea name="data[packageno_status]" id="packageno_status" cols="6" rows=3></textarea>
		</td>
	</tr>
	<tr>
		<th><strong><?php echo L('unionbox_packagenum')?>：</strong></th>
		<td><span id="unionbox_packagenum"><?php echo $allpackages;?></span>&nbsp;&nbsp;<strong><?php echo L('unionbox_packagecurrent')?>：</strong><span id="unionbox_packagecurrent">0</span></td>
	</tr>
	<tr>
		<th><strong><?php echo L('unionbox_checkamount')?>：</strong></th>
		<td><input name="waybill[checkamount]" id="checkamount" class="input-text" type="radio"  value="0" checked><?php echo L('unionbox_yes')?>&nbsp;<input name="waybill[checkamount]" id="checkamount" class="input-text" type="radio"  value="1"><?php echo L('unionbox_no')?></td>
	</tr>
	
	
	<tr>
		<th><strong><?php echo L('operator')?>：</strong></th>
		<td><?php echo $this->username;?></td>
	</tr>

	<tr>
  		<th><strong><?php echo L('remark')?>：</strong></th>
        <td>
		<textarea name="history[remark]" id="remark" cols="40" rows=4></textarea>
		</td>
	</tr>
	</tbody>
</table>
<input type="submit" name="dosubmit" id="dosubmit" value=" <?php echo L('ok')?> " class="dialog">&nbsp;<input type="reset" class="dialog" value=" <?php echo L('clear')?> ">
</form>
</div>
</body>
</html>
<script type="text/javascript">
$(document).ready(function(){
	$.formValidator.initConfig({formid:"myform",autotip:true,onerror:function(msg,obj){window.top.art.dialog({content:msg,lock:true,width:'220',height:'70'}, function(){this.close();$(obj).focus();})}});
});
var b=0;
//点击新增一行
function addRows(obj,e){
	var allrows = parseInt($("#unionbox_packagenum").text());
	var curr=$("#unionbox_packagecurrent").text();
	if(parseInt(curr)<allrows){
		
		$("#unionbox_packagecurrent").text(parseInt(curr)+1);
	}
		if(b!=0 && obj.rows<(allrows+2)){
		

		obj.rows+=1;
		obj.value+="\n";
		goL(obj.rows,obj);
        var packobj=document.getElementById("packageno_status");         
		packobj.rows+=1;
		var num=1000+packobj.rows-3;
		//packobj.value+=num+"\n";
	
		}else{
			b=1;
		}

		var v=obj.value.split('\n');
		var expressno='';
		document.getElementById('packageno_status').value="";
		for(var i=0;i<allrows;i++){
			if(v[i]!=undefined ){
				if(expressno=='')
					expressno=v[i];
				else
					expressno+=","+v[i];
			}
		}

		
		$.post("?m=waybill&c=admin_waybill&a=unionbox_check&checkhash=<?php echo $_SESSION['checkhash'];?>&t="+Math.random()+"&expressno="+expressno,function(result){
			document.getElementById('packageno_status').value+=result;
		});
		
}

function goL(Line,obj)    
{
  obj.focus(); 
  var v=obj.value.split('\n');
  ch=0;
  var num=1000;
  for(var i=0;i<Line-1;i++){
    ch+=80;
  }
  var o=obj.createTextRange(); 
  o.move("character",ch);
  o.select();

}    

</script>