<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<div class="pad-10">
<form method="post" action="?m=turnway&c=admin_turnway&a=edit&aid=<?php echo $_GET['aid']?>" name="myform" id="myform">
<table class="table_form" width="100%">
<tbody>
	<tr>
		<th><strong><?php echo L('sendid')?>：</strong></th>
		<td>
		<select name="turnway[sendid]" id="sendid" >
		<option value="0">==<?php echo L('please_select')?>==</option>
		<?php foreach($sendaddress as $r){
			$sel=" ";
			if ($an_info['sendid']==$r['linkageid'])
					$sel=" selected";
			echo '<option value="'.$r['linkageid'].'" '.$sel.'>'.$r['name'].'</option>';
		}?>

		</select>
		</td>
	</tr>
	<tr>
		<th><strong><?php echo L('takeid')?>：</strong></th>
		<td>
		<select name="turnway[takeid]" id="takeid"   >
		<option value="0">==<?php echo L('please_select')?>==</option>
		<?php foreach($sendaddress as $r){
			$sel=" ";
			if ($an_info['takeid']==$r['linkageid'])
					$sel=" selected";
			echo '<option value="'.$r['linkageid'].'" '.$sel.'>'.$r['name'].'</option>';
		}?>
		</select>
		</td>
	</tr>
	<tr>
		<th><strong><?php echo L('discount_type')?>：</strong></th>
		<td>
		<select name="turnway[discounttype]" id="discounttype" >
		<option value="">==<?php echo L('no_discount')?>==</option>
		<?php
		foreach($this->getdiscounttype() as $dis){
			$dissel="";
			if (trim($an_info['discounttype'])==trim($dis['value'])){
				$dissel=" selected";
			}
			
			echo '<option value="'.$dis['value'].'" '.$dissel.'>'.$dis['title'].'</option>';
		}
		?>
		</select>
		</td>
	</tr>
	<tr>
		<th ><strong><?php echo L('turnway_title')?></strong></th>
		<td><input name="turnway[title]" id="title" class="input-text" type="text" size="30" value="<?php echo $an_info['title']?>" ></td>
	</tr>
	<tr>
		<th ><strong><?php echo L('turnway_usfee')?>：</strong></th>
		<td><input name="turnway[usfee]" id="usfee" class="input-text" type="text" size="30" value="<?php echo $an_info['usfee']?>"></td>
	</tr>
	<tr>
		<th ><strong><?php echo L('turnway_cnfee')?>：</strong></th>
		<td><input name="turnway[cnfee]" id="cnfee" class="input-text" type="text" size="30" value="<?php echo $an_info['cnfee']?>"></td>
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
	$('#title').formValidator({onshow:"<?php echo L('input_turnway_title')?>",onfocus:"<?php echo L('title_min_3_chars')?>",oncorrect:"<?php echo L('right')?>"}).inputValidator({min:1,onerror:"<?php echo L('title_cannot_empty')?>"}).ajaxValidator({type:"get",url:"",data:"m=turnway&c=admin_turnway&a=public_check_title&aid=<?php echo $_GET['aid']?>",datatype:"html",cached:false,async:'true',success : function(data) {
        if( data == "1" )
		{
            return true;
		}
        else
		{
            return false;
		}
	},
	error: function(){alert("<?php echo L('server_no_data')?>");},
	onerror : "<?php echo L('turnway_exist')?>",
	onwait : "<?php echo L('checking')?>"
	}).defaultPassed();



});


function ajaxOpt(areaid,flag){
	if(flag==1){
		$.post("?m=turnway&c=admin_turnway&a=gettakelists&checkhash=<?php echo $_SESSION['checkhash']?>&areaid="+areaid,function(data){
			var datas = eval(data);
			var takeobj = document.getElementById('takeid');
			takeobj.length=1;
			for(var i=0;i<datas.length;i++){
				var obj = datas[i];
				takeobj.options[i] = new Option(obj.name,obj.linkageid);
			}
			$("#takeid").val("<?php echo $an_info['takeid']?>");
		});
	}else
	{
		
	}
}

//ajaxOpt("<?php echo $an_info['sendid']?>",1);

</script>