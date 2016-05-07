<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<script language="javascript" type="text/javascript" src="/resource/js/dialog.js"></script>
<script language="javascript" type="text/javascript" src="/resource/js/content_addtop.js"></script>
 <script type="text/javascript" src="/resource/js/swfupload/swf2ckeditor.js"></script>

<script type="text/javascript">
<!--
	var charset = 'utf-8';
	var uploadurl = '/uploadfile/';
//-->
</script>

<div class="pad-10">
<form method="post" action="?m=waybill&c=admin_waybill&a=waybill_handle&ids=<?php echo $ids;?>" name="myform" id="myform" >
<table class="table_form" width="100%" cellspacing="0">
<tbody>
<tr style="display:none">
		<th ><strong><?php echo L('selected_recoeds')?>：</strong></th>
		<td><?php echo $ids;?></td>
	</tr>
	<tr style="display:none">
		<th ><strong><?php echo L('expressnos')?>：</strong></th>
		<td>
		<textarea name="data[packageno]" id="packageno" cols="40" rows=3 onclick="addRows(this)"><?php echo  $packageno;?></textarea><textarea name="data[packageno_status]" id="packageno_status" cols="6" rows=3 readonly></textarea>&nbsp;<input type="button" name="checkbut" value="<?php echo L('unionbox_handcheck');?>" onclick="checkRows()"/>
		</td>
	</tr>
	
	<tr >
	<input type="hidden" value="<?php echo $an_info[firstprice];?>" name="waybill[firstprice]"  id="firstprice" />
	<input type="hidden" value="<?php echo $an_info[addprice];?>" name="waybill[addprice]"  id="addprice" />
	<input type="hidden" value="<?php echo $an_info[addweight];?>" name="waybill[addweight]"  id="addweight" />
	<input type="hidden" value="<?php echo $an_info[firstweight];?>" name="waybill[firstweight]"  id="firstweight" />
	<input type="hidden" value="<?php echo $an_info[shippingname];?>" name="waybill[shippingname]"  id="shippingname" />
	<input type="hidden" value="<?php echo $an_info[otherdiscount];?>" name="waybill[otherdiscount]"  id="otherdiscount" />
	<tr>
		<th><strong><?php echo L('waybillid');?>：</strong></th>
		<td><?php echo $an_info['waybillid'];?>
		<strong style="padding-left:20px;">编号：</strong>
		<strong style="color:red;">#<?php echo $an_info['aid'];?></strong>
		<strong style="padding-left:20px;">标识：</strong>
		<strong style="color:red;"><?php echo $an_info['takeflag'];?></strong>
		</td>
	</tr>

	<th><strong>发货线路:</strong></th>
	<td>
	<select  name="waybill[shippingid]" id="shippingid"   class="inp inp-select"  onchange="document.getElementById('shippingname').value=document.getElementById('shippingid').options[document.getElementById('shippingid').selectedIndex].text;changeLine();">  
	
					<option value="0"> 请选择 </option>
				
					<?php
					 $allturnway=$this->getturnway();
					foreach($allturnway  as $way){
						$sel="";
						if($an_info[shippingid]==$way[aid]){
						$sel=" selected";
						}

						$payedfee = get_ship_fee($an_info['totalweight'],get_common_shipline($way[aid]));

						echo '<option value="'.$way[aid].'" '.$sel.' org="'.$way[origin].'" dst="'.$way[destination].'" price="'.$way[price].'"  addprice="'.$way[addprice].'" addweight="'.$way[addweight].'" firstweight="'.$way[firstweight].'" title="'.$way[title].'" payedfee="'.$payedfee.'" otherdiscount="'.$way[discount].'">'.$way[title].' '.strip_tags($way[content]).'</option>';
					}
					?>
		</select> 
	</td>
	</tr>

	<tr style="display:none">
		<th><strong><?php echo L('unionbox_packnum')?>：</strong></th>
		<td><span id="unionbox_packagenum"><?php echo $allpackages;?></span>&nbsp;&nbsp;<strong><?php echo L('unionbox_packcurrent')?>：</strong><span id="unionbox_packagecurrent">0</span></td>
	</tr>
	<tr style="display:none;">
		<th><strong><?php echo L('unionbox_checkamount')?>：</strong></th>
		<td><input name="waybill[checkamount]" id="checkamount" class="input-text" type="radio"  value="0" checked><?php echo L('unionbox_yes')?>&nbsp;<input name="waybill[checkamount]" id="checkamount" class="input-text" type="radio"  value="1"><?php echo L('unionbox_no')?></td>
	</tr>
	<tr style="display:none;">
		<th><strong><?php echo L('status')?>：</strong></th>
		<td><input name="wbill[status]" id="status4" class="input-text" type="radio"  value="8" checked><?php echo L('waybill_status4')?>&nbsp;<input name="wbill[status]" id="status5" class="input-text" type="radio"  value="5"><?php echo L('waybill_status5')?></td>
	</tr>
	<tr style="display:none;">
	<th><strong><?php echo L('use_formulas');?>：</strong></th>
	<td><div id="gongshi"></div></td>
	</tr>
	<tr style="display:none" >
		<th><strong><?php echo L('waybill_lwh')?>(CM)：</strong></th>
		<td><input name="wbill[bill_long]" id="bill_long"  type="text"  size=10 value="<?php echo $bill_long;?>" onKeyUp="this.value=this.value.replace(/[^0-9.]/gi,'')" onblur="comput();">X<input name="wbill[bill_width]" id="bill_width"  type="text"  size=10 value="<?php echo $bill_width;?>" onKeyUp="this.value=this.value.replace(/[^0-9.]/gi,'')" onblur="comput();">X<input name="wbill[bill_height]" id="bill_height"  type="text" size=10  value="<?php echo $bill_height;?>" onKeyUp="this.value=this.value.replace(/[^0-9.]/gi,'')" onblur="comput();"></td>
	</tr>



	<tr>
		<th><strong style="color:red;"><?php echo L('actual_weight_p')?>：</strong></th>
		<td><input style="color:red" name="wbill[totalweight]" id="totalweight" class="input-text" type="text" size="10" value="<?php echo $totalweight;?>" onblur="comput();">&nbsp;&nbsp;<input name="wbill[factweight]" id="factweight" class="input-text" type="hidden" size="10" value="0.00" ></td>
	</tr>
	
	<tr>
		<th><strong><?php echo L('pay_total')?>(円)：</strong></th>
		<td><input name="wbill[totalship]" id="totalship" class="input-text" type="text" size="10" value="0" onblur="comput();">&nbsp;&nbsp;
		<?php echo L('total_value_added_costs');?>：<input name="wbill[allvaluedfee]" id="allvaluedfee" class="input-text" type="text" size="10" value="<?php echo $allvaluedfee;?>" onblur="comput();"></td>
	</tr>
	<tr style="display:none">
		<th><strong><?php echo L('member_discount');?>：</strong></th>
		<td><input name="wbill[memberdiscount]" id="memberdiscount" class="input-text" type="text" size="10" value="0" onblur="comput();">%&nbsp;&nbsp;<?php echo L('additional_discount');?>：<input name="wbill[otherdiscount]" id="otherdiscount" class="input-text" type="text" size="10" value="<?php echo $otherdiscount;?>" onblur="comput();"></td>
	</tr>
	<tr>
		<th><strong><?php echo L('other_fee');?>：</strong></th>
		<td><input name="wbill[otherfee]" id="otherfee"  type="text"  size=10 value="<?php echo $otherfee;?>" onblur="comput();">&nbsp;&nbsp;
		操作费(円)：<input name="wbill[taxfee]" id="taxfee" class="input-text" type="text" size="10" value="<?php echo $an_info['taxfee'];?>" onblur="comput();">&nbsp;&nbsp;</td>
	</tr>

	<tr style="display:none">
		<th><strong><?php echo L('package_overday');?>：</strong></th>
		<td><input name="wbill[overdayscount]" id="overdayscount"  type="text"  size=10 value="<?php echo $overdayscount;?>" onblur="comput();">&nbsp;&nbsp;<?php echo L('package_overdayfee');?>：<input name="wbill[overdaysfee]" id="overdaysfee" class="input-text" type="text" size="10" value="<?php echo $overdaysfee;?>" onblur="comput();">&nbsp;&nbsp;</td>
	</tr>

	<tr>
		<th><strong><?php echo L('paid_in_fee');?>(円)：</strong></th>
		<td><input onblur="document.getElementById('payedfee').value=parseFloat(parseFloat(this.value)*parseFloat(document.getElementById('wayrate').value)).toFixed(2);" name="waybill[payfeeusd]" id="payfeeusd"  type="text"  size=10 value="<?php echo $payfeeusd;?>">&nbsp;&nbsp;RMB <?php echo L('wayrate');?>：<input name="waybill[wayrate]" id="wayrate" class="input-text" type="text" size="15" value="<?php echo $wayrate;?>" onblur="document.getElementById('payedfee').value=parseFloat(parseFloat(document.getElementById('payfeeusd').value)*parseFloat(document.getElementById('wayrate').value)).toFixed(2);"></td>
	</tr>
	<tr>
		<th><strong><?php echo L('paid_in_fee');?>(元)：</strong></th>
		<td><input onblur="document.getElementById('payfeeusd').value=parseFloat(parseFloat(this.value)/parseFloat(document.getElementById('wayrate').value)).toFixed(2);" name="waybill[payedfee]" id="payedfee"  type="text"  size=30 value="<?php echo $an_info['payedfee'];?>"></td>
	</tr>
	<tr style="display:none;">
		<th><strong><?php echo L('goodsname')?>：</strong></th>
		<td><input name="wbill[goodsname]" id="goodsname"  type="text"  size=30 value="<?php echo $goodsname;?>"></td>
	</tr>

	<tr style="display:none">
		<th><strong><?php echo L('upload_image');?>：</strong></th>
		<td> <?php echo form::images('wbill[picture]','picture',$picture,'waybill', '0',50,'', '','png|jpg|gif');?> </td>
	</tr>

	<tr <?php if($an_info['status']!=19 && $an_info['status']!=20 && $an_info['status']!=21){?> style="display:none;" 
	<?php } ?>>
		
		<td colspan=4>

		<table width="100%" cellspacing="0" style="border:solid 1px #d5dfe8;">
        <thead>
            <tr>
			<th style="text-align:center;display:none;">邮单号</th>
			<th style="text-align:left;font-weight:bold;">预报物品名</th>
			<th style="text-align:center">数量</th>
			<th style="text-align:center">价值</th>
			<th style="text-align:center;display:none;">重量</th>
			<th style="text-align:center">操作</th>
            </tr>
        </thead>
    <tbody>
		<?php
		

		 $goodsdatas=string2array($an_info['goodsdatas']);
	 foreach($goodsdatas as $key=>$goods){
		echo '<tr id="goods'.$key.'">
			<td align="center" style="display:none;">';
			
			echo $an_info['expressname'].'/';
			echo $an_info['expressno'];
			echo '</td>
			<td align="left">'.'<input name="waybill_goods['.$key.'][goodsname]" id="goodsname" class="input-text" type="text" size="35" value="'.$goods['goodsname'].'">'.'</td>
			<td align="center">'.'<input name="waybill_goods['.$key.'][amount]" id="amount" class="input-text" type="text" size="5" value="'.$goods['amount'].'">'.'</td>
			<td align="center">'.'<input name="waybill_goods['.$key.'][price]" id="price" class="input-text" type="text" size="8" value="'.$goods['price'].'">'.'</td>
			<td style="text-align:center;display:none;">'.'<input name="waybill_goods['.$key.'][weight]" id="weight" class="input-text" type="text" size="5" value="'.$goods['weight'].'">'.'</td>
			<td width="40" align=center><a href="javascript:void(0);" onclick="delRow(this,'.$key.');"><font style="color:red;font-size:13px">删除</font></a></td>';
			echo '
            </tr>';
			
	 }
		
		
		?>
		</tbody>
		</table>
		
		</td>
	</tr>
	<?php ?>
	<tr style="display:none;">
		<td  colspan=4 style="border: 2px solid red;">
			<table width="100%" cellspacing="0" style="border:solid 1px #d5dfe8;" id="tb__product">
				<thead>
					<tr>
					<th style="text-align:left;font-weight:bold;">申报物品名</th>
					<th style="text-align:center">数量</th>
					<th style="text-align:center">价值(円)</th>
					<th style="text-align:center">操作</th>
					</tr>
				</thead>
			<tbody>
			<?php
			if(isset($waybill_declaredatas['total'])){
				$key=0;
				foreach($waybill_declaredatas['datas'] as $val){
				$key++;
				?>
			<tr id="trProduct<?php if($key>1){echo $key;}?>" class="goods__row">
					<td align="left"><input id="declarename" name="waybill_declare[<?php echo  $key;?>][declarename]"  class="input-text" size="35" value="<?php echo $val['declarename'];?>"> </td>
					<td align="center"><input id="declareamount" name="waybill_declare[<?php echo  $key;?>][declareamount]"  class="input-text" type="text" size="5" value="<?php echo $val['declareamount'];?>"></td>
					<td align="center"><input id="declareprice" name="waybill_declare[<?php echo  $key;?>][declareprice]"  class="input-text" type="text" size="8"  onblur="blurcheck(this)" value="<?php echo $val['declareprice'];?>"></td>
					<td align="center"><a href="javascript:void(0);" onclick="delRow(this);"><font style="color:red;font-size:13px">删除</font></a></td>
				</tr>

			<?php
				}
			}else{
			?>
				<tr id="trProduct" class="goods__row">
					<td align="left"><input id="declarename" name="waybill_declare[1][declarename]"  class="input-text" size="35"> </td>
					<td align="center"><input id="declareamount" name="waybill_declare[1][declareamount]"  class="input-text" type="text" size="5"></td>
					<td align="center"><input id="declareprice" name="waybill_declare[1][declareprice]"  class="input-text" type="text" size="8"  onblur="blurcheck(this)"></td>
					<td align="center"><a href="javascript:void(0);" onclick="delRow(this);"><font style="color:red;font-size:13px">删除</font></a></td>
				</tr>
				<?php
			}	
			?>
			</tbody>
			</table>
			<table width="100%" >
			<tr>
					<td align="right" colspan=4><input id="declaretotalprice" name="declaretotalprice"  class="input-text" size="8" value="<?php if($waybill_declaredatas){ echo $waybill_declaredatas['total'];}?>">申报总价值</td>
				</tr>
			</table>
			<div style="clear:both;margin-top:10px;">
						<button style="float:left;margin-left:15px;" type="button"  class="btn-login radius-three fl add_row" onclick="javascript:addRow()">继续添加</button>
			</div>
		</td>
	</tr>
	
	<tr>
      <th> <?php echo L('upload_image');?>组图	  </th>
      <td><input name="waybill[imagesurl]" type="hidden" value="">
		<fieldset class="blue pad-10">
        <legend>图片列表</legend><center><div class='onShow' id='nameTip'>您最多可以同时上传 <font color='red'>50</font> 张</div></center><div id="imagesurl" class="picList">
		
		<?php

		if(isset($an_info['imagesurl'])){
			$imagesurl = string2array($an_info['imagesurl']);
			foreach($imagesurl as $img){
				echo '<INPUT class=input-text style="WIDTH: 310px" value="'.$img.'" name="imagesurl_url[]" />';
			}
		}
		
		?>
		
		</div>
		</fieldset>
		<div class="bk10"></div>
		<div class='picBut cu'><a herf='javascript:void(0);' onclick="javascript:flashupload('imagesurl_images', '附件上传','imagesurl',change_images,'50,gif|jpg|jpeg|png|bmp,1','waybill','60','<?php echo upload_key('50,gif|jpg|jpeg|png|bmp,1');?>')"/> 选择图片 </a></div>  </td>
    </tr>

	<!--<tr>
		<th><strong><?php echo L('sendaddressid');?>：</strong></th>
		<td><input name="wbill[sendaddressname]" id="sendaddressname"  type="text"  size=80 value="<?php echo str_replace('|',' ',$_row['sendaddressname']);?>"></td>
	</tr>
	<tr>
		<th><strong><?php echo L('takeaddressid');?>：</strong></th>
		<td><input name="wbill[takeaddressname]" id="takeaddressname"  type="text"  size=80 value="<?php echo str_replace('|',' ',$_row['takeaddressname']);?>"></td>
	</tr>-->
		<tr>
		<th><strong><?php echo L('common_send_email_notify');?>：</strong></th>
		<td><input name="send_email_nofity" id="send_email_nofity1"  type="radio"  value="1" checked><?php echo L('Yes');?>&nbsp;&nbsp;<input name="send_email_nofity" id="send_email_nofity0"  type="radio"  value="0" ><?php echo L('No');?>&nbsp;&nbsp;</td>
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
<input type="submit" onclick="return processings();" name="dosubmit" id="dosubmit" value=" <?php echo L('ok')?> " class="dialog">&nbsp;<input type="reset" class="dialog" value=" <?php echo L('clear')?> ">
<input type="hidden" id="tweightfee" value="<?php echo $tweightfee;?>"/>
<input type="hidden" id="vweightfee" value="<?php echo $vweightfee;?>"/>

</form>
</div>

<input type="hidden" name="lastindex" id="lastindex" value="1"/>


</body>
</html>
<script type="text/javascript">

$(document).ready(function(){
	$.formValidator.initConfig({formid:"myform",autotip:true,onerror:function(msg,obj){window.top.art.dialog({content:msg,lock:true,width:'220',height:'70'}, function(){this.close();$(obj).focus();})}});
});



function processings(){
	if($("#volumeweight").val()==0 && $("#totalweight").val()==0){
		alert("<?php echo L('volumeweight').' Or '.L('actual_weight_p').'  Cannot be 0 ';?>");
		return false;
	}
}

function checkRows(){
	var allrows = parseInt($("#unionbox_packagenum").text());
	var obj=document.getElementById('packageno');
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
		obj.rows=allrows+1;
		document.getElementById('packageno_status').rows=allrows+1;

	$.post("?m=waybill&c=admin_waybill&a=waybill_handle&f=unionbox_check&checkhash=<?php echo $_SESSION['checkhash'];?>&t="+Math.random()+"&expressno="+expressno,function(result){
			document.getElementById('packageno_status').value+=result;
	});

}
checkRows();//check
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

		/*var v=obj.value.split('\n');
		var expressno='';
		document.getElementById('packageno_status').value="";
		for(var i=0;i<allrows;i++){
			if(v[i]!=undefined ){
				if(expressno=='')
					expressno=v[i];
				else
					expressno+=","+v[i];
			}
		}*/

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



function comput(){
	//var bill_long=parseFloat($("#bill_long").val()).toFixed(2);
	//var bill_width=parseFloat($("#bill_width").val()).toFixed(2);
	//var bill_height=parseFloat($("#bill_height").val()).toFixed(2);
	var totalweight=parseFloat($("#totalweight").val()).toFixed(2);//实际重量
	var pay_feeweight=0;//收费重量
	var tweightfee=parseFloat($("#tweightfee").val()).toFixed(2);//航线运费
	var vweightfee=parseFloat($("#vweightfee").val()).toFixed(2);//体积重运费
	var totalship=parseFloat($("#totalship").val()).toFixed(2);//总运费
	var allvaluedfee=parseFloat($("#allvaluedfee").val()).toFixed(2);//增值费用

	var otherfee=parseFloat($("#otherfee").val());//其它费用
	var taxfee=parseFloat($("#taxfee").val());//操作费用
	var fee="<?php echo $package_overdayfee;?>";
	$("#overdaysfee").val(($("#overdayscount").val()));

	var overdaysfee=parseFloat($("#overdaysfee").val());
 
	//var volumeweight=bill_long*bill_width*bill_height/<?php echo WB_VOL;?>/<?php echo WB_VOL_CM;?>;//体积重
	var volumeweight=1;//体积重
	var memberdiscount=parseFloat($("#memberdiscount").val()) * 0.01;//会员折扣
	var otherdiscount=parseFloat($("#otherdiscount").val());//额外折扣
	var discount = parseFloat(memberdiscount)+parseFloat(otherdiscount);//需要打多少折
	var wayrate = parseFloat($("#wayrate").val());

	var volumefee=0;//体积费


	if(discount==0)
		discount=1;//不打折
	
		


	volumeweight = parseFloat(volumeweight).toFixed(2);
	$("#volumeweight").val(volumeweight);
 
	if(parseFloat(volumeweight)>parseFloat(totalweight)){//体积重>实际重
		//运费是=实际重量*实际重量运费 +（体积重-实际重量）*体积重运费
			
		totalship = totalship;
	
		$("#showlinefee").val(tweightfee);
		$("#gongshi").html("<font color=red>总运费=体积重量*航线运费</font>");
		

	}else{
		
		$("#gongshi").html("<font color=red>总运费=实际重量*航线运费</font>");
		$("#showlinefee").val(tweightfee);
		totalship=totalship;
		pay_feeweight=0;
		volumefee=0;
	}


	
	

	$.ajax({ 
	  type: 'POST', 
	  url: "/api.php?op=public_api&a=computfee&takename=<?php echo $an_info[takename];?>&shippingid="+$("#shippingid").val()+"&totalweight="+$("#totalweight").val()+"", 

	  dataType: 'text', 
	  async:false,
	  success: function(data){
		totalship = data;
	  }
	}); 

	$.ajax({ 
	  type: 'POST', 
	  url: "/api.php?op=public_api&a=computtaxfee&shippingid="+$("#shippingid").val()+"&totalweight="+$("#totalweight").val()+"&t="+Math.random(), 

	  dataType: 'text', 
	  async:false,
	  success: function(data){
	
		$("#taxfee").val(data);
	  }
	});
	
	pay_feeweight=parseFloat(pay_feeweight).toFixed(2);
	totalship=parseFloat(totalship).toFixed(2);
	payedfee=parseFloat(totalship)*discount+parseFloat(allvaluedfee)+otherfee + overdaysfee + taxfee;//打折
	//payedfee=parseFloat(totalship)+parseFloat(allvaluedfee);
	payedfee=parseFloat(payedfee).toFixed(2);
 
	$("#factweight").val(pay_feeweight);//收费重量
	$("#totalship").val(totalship);//总运费
	
	var tax=parseFloat($("#taxfee").val());
	$("#payfeeusd").val(payedfee);//实扣费用JP
	var rmbf=parseFloat(payedfee*wayrate);

	$("#payedfee").val(parseFloat(rmbf).toFixed(2));//实扣费用RMB
	$("#volumefee").val(parseFloat(volumefee).toFixed(2));//体积费用
 
   
}

comput();

function changeLine(){
	
		//document.getElementById('shippingid').options[document.getElementById('shippingid').selectedIndex].value;
		
		var sendid = $("#shippingid").find("option:selected").attr('org'); 
		var takeid = $("#shippingid").find("option:selected").attr('dst'); 
		var payedfee = parseFloat($("#shippingid").find("option:selected").attr('payedfee')); 

		$("#firstprice").val($("#shippingid").find("option:selected").attr('price'));
		$("#addprice").val($("#shippingid").find("option:selected").attr('addprice'));
		$("#addweight").val($("#shippingid").find("option:selected").attr('addweight'));
		$("#firstweight").val($("#shippingid").find("option:selected").attr('firstweight'));

		$("#otherdiscount").val($("#shippingid").find("option:selected").attr('otherdiscount'));

comput();
  }


  function addRow(){ 
		var num = $(".goods__row").size()+1;
		var mClone = $("#trProduct").clone(true);

		mClone[0].id="trProduct"+num;
		mClone.find("input[id^='declarename']").val("");
		mClone.find("input[id^='declareamount']").val("");
		mClone.find("input[id^='declareprice']").val("");
		mClone.appendTo($("#tb__product"));
		orderRow();
	}


	function orderRow(){
		$("#lastindex").val(1);
		$(".goods__row").each(function(i){
			//$(this).find("td:first").html($("#lastindex").val());
			var num = $("#lastindex").val();
			

			$(this).find("input[id^='declarename']").attr("id","declarename"+num);
			$(this).find("input[id^='declareamount']").attr("id","declareamount"+num);
			$(this).find("input[id^='declareprice']").attr("id","declareprice"+num);
			$(this).find("input[id^='declarename']").attr("name","waybill_declare["+num+"][declarename]");
			$(this).find("input[id^='declareamount']").attr("name","waybill_declare["+num+"][declareamount]");
			$(this).find("input[id^='declareprice']").attr("name","waybill_declare["+num+"][declareprice]");
			
			num = parseInt(num)+1;
			$("#lastindex").val(num);
		});
	}

	function delRow(obj){
		var delid = $(obj).parent().parent().attr("id");
		if(delid!='trProduct'){
			$("#"+delid).remove();
			orderRow();
		}
		computtotal();
		
	}

	//计算
	function computtotal(){
		
		var totalprice=0;
		$(".goods__row").each(function(){
			if($(this).find("input[id^='declareprice']").val()!="")
			totalprice += parseInt($(this).find("input[id^='declareprice']").val());
		});

	
		$("#declaretotalprice").val(totalprice);
	}

	function blurcheck(obj){
	

		if( obj.name.indexOf('[declareprice]')!=-1){
			computtotal();//运算价格与数量
		}
	}
	
	computtotal();

</script>

