<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<script language="javascript" type="text/javascript" src="/resource/js/dialog.js"></script>
<script language="javascript" type="text/javascript" src="/resource/js/content_addtop.js"></script>

<script type="text/javascript">
<!--
	var charset = 'utf-8';
	var uploadurl = '/uploadfile/';
//-->
</script>

<div class="pad-10">
<form method="post" action="?m=waybill&c=admin_waybill&a=edit&aid=<?php echo $_GET['aid']?>?>" name="myform" id="myform">
<table class="table_form" width="100%">


	<tbody>
	<tr>
		<th><strong><?php echo L('waybillid');?>：</strong></th>
		<td><?php echo $an_info['waybillid'];?>
		<strong style="padding-left:20px;">编号：</strong>
		<strong style="color:red;">#<?php echo $an_info['aid'];?></strong>
		<strong style="padding-left:20px;">标识：</strong>
		<strong style="color:red;"><?php echo $an_info['takeflag'];?></strong>
		</td>
	</tr>
	<tr>
		<th ><strong>邮单号</strong></th>
		<td ><input name="package[expressno]" id="expressno" class="input-text" type="text" size="22"  value="<?php echo $an_info['expressno'];?>"></td>
	</tr>
		<tr>
		<th><strong><?php echo L('status');?></strong></th>
		<td>
		<select name="waybill[status]" id="status" >
			<?php
				$allwaybilltatus = $this->getallwaybilltatus();
				foreach($allwaybilltatus as $row){

					$sel="";
					if($row['value']==$an_info['status']){$sel=' selected';}
					//if(($row['value']>0 && $row['value']<9) || $row['value']==99 || $row['value']==18){
						echo '<option value="'.$row['value'].'" '.$sel.'>'.$row['title'].'</option>';
					//}
				}
			?>
		</select>
		
		<strong style="padding-left:20px;">发货线路</strong>
			
				<select  name="waybill[shippingid]" id="shippingid"   class="inp inp-select"  onchange="document.getElementById('shippingname').value=document.getElementById('shippingid').options[document.getElementById('shippingid').selectedIndex].text;changeLine();">  
						<option value="0"> <?php echo L('please_select');?> 
					</option>
					<?php
					$allturnway = $this->getturnway();
					foreach($allturnway as $way){

					$sel="";
					if ($an_info[shippingid]==$way[aid]){$sel="selected";}
					echo '<option value="'.$way[aid].'" '.$sel.' org="'.$way[origin].'" dst="'.$way[destination].'" price="'.$way[price].'"  addprice="'.$way[addprice].'" addweight="'.$way[addweight].'" firstweight="'.$way[firstweight].'" otherdiscount="'.$way[discount].'">'.$way[title].'</option>';
				}?>
				</select> 
		</td>

	</tr>
	<tr  style="display:none;" >
		<th><strong><?php echo L('sendid');?>：</strong></th>
		<td> 
			<select name="waybill[sendid]" id="sendid"  class="inp inp-select" onchange="javascript:ajaxOpt(document.getElementById('sendid').options[document.getElementById('sendid').selectedIndex].value,1);" >
					<option value="0"> <?php echo L('please_select');?> </option>
					<?php
					$allsendlists = $this->getsendlists();
					foreach($allsendlists as $r){ 
						$sel="";
						if ($an_info[sendid]==$r[linkageid]){$sel= 'selected';}
						echo '<option value="'.$r[linkageid].'" '.$sel.'>'.$r[name].'</option>';
					}?>
			</select>

			<label style="float:none;width:auto;"><?php echo L('takeid');?></label>
			<select name="waybill[takeid]" id="takeid"  class="inp inp-select" onchange="javascript:ajaxOpt(document.getElementById('takeid').options[document.getElementById('takeid').selectedIndex].value,0);" >  
					<option value="0"> <?php echo L('please_select');?> </option>
			</select>
		</td>
	</tr>
	<tr style="display:none">
		<th><strong><?php echo L('storage');?>：</strong></th>
		<td>
		<select name="waybill[storageid]" id="status" >
			<?php
				foreach($allstorage as $row){
					$sel="";
					if($row['aid']==$an_info['storageid']){$sel=' selected';}
					echo '<option value="'.$row['aid'].'" '.$sel.'>'.$row['title'].'</option>';
				}
			?>
		</select>
		</td>
	</tr>
	
	<tr style="display:none">
		<th><strong><?php echo L('waybill_lwh');?>(cm)：</strong></th>
		<td><input name="waybill[bill_long]" id="bill_long"  type="text"  size=10 value="<?php echo $an_info['bill_long'];?>" onKeyUp="this.value=this.value.replace(/[^0-9.]/gi,'')" >X<input name="waybill[bill_width]" id="bill_width"  type="text"  size=10 value="<?php echo $an_info['bill_width'];?>" onKeyUp="this.value=this.value.replace(/[^0-9.]/gi,'')" >X<input name="waybill[bill_height]" id="bill_height"  type="text" size=10  value="<?php echo $an_info['bill_height'];?>" onKeyUp="this.value=this.value.replace(/[^0-9.]/gi,'')" ></td>
	</tr>

	<tr>
		<th><strong style="color:red"><?php echo L('actual_weight_p');?>：</strong></th>
		<td><input name="waybill[totalweight]" id="totalweight" class="input-text" type="text" size="10" value="<?php echo $an_info['totalweight'];?>"   onblur="comput();">
		<strong style="display:none;"><?php echo L('totalamount');?>：</strong>
		<input style="display:none;" name="waybill[totalamount]" id="totalamount" class="input-text" type="text" size="10" value="<?php echo $an_info['totalamount'];?>" onblur="comput();">
		<strong style="display:none;"><?php echo L('totalprice');?>(円)：</strong>
		<input style="display:none;" name="waybill[totalprice]" id="totalprice" class="input-text" type="text" size="10" value="<?php echo $an_info['totalprice'];?>" >
		</td>
	</tr>

	<tr>
		<th><strong><?php echo L('total_freight');?>(円)：</strong></th>
		<td><input readonly="readonly" name="waybill[totalship]" id="totalship" class="input-text" type="text" size="10" value="<?php echo $an_info['totalship'];?>" >&nbsp;&nbsp;<strong><?php echo L('total_value_added_costs');?>：</strong><input onblur="comput();" name="waybill[allvaluedfee]" id="allvaluedfee" class="input-text" type="text" size="10" value="<?php echo $an_info['allvaluedfee'];?>" >&nbsp;&nbsp;</td>
	</tr>
	<tr style="display:none">
		<th><strong><?php echo L('member_discount');?>：</strong></th>
		<td><input name="waybill[memberdiscount]" id="memberdiscount" class="input-text" type="text" size="10" value="0" onblur="comput();">%&nbsp;&nbsp;
		<?php echo L('additional_discount');?>：<input name="waybill[otherdiscount]" id="otherdiscount" class="input-text" type="text" size="10" value="<?php echo $an_info['otherdiscount'];?>" onblur="comput();"></td>
	</tr>
	
	<tr>
		<th><strong><?php echo L('other_fee');?>(円)：</strong></th>
		<td><input name="waybill[otherfee]" id="otherfee"  type="text"  size=10 value="<?php echo $an_info['otherfee'];?>"  onblur="comput();">&nbsp;&nbsp;<strong>操作费(円)：</strong><input name="waybill[taxfee]" id="taxfee" class="input-text" type="text" size="10" value="<?php echo $an_info['taxfee'];?>"  onblur="comput();"></td>
	</tr>

	<tr style="display:none">
		<th><strong><?php echo L('package_overday');?>：</strong></th>
		<td><input name="waybill[overdayscount]" id="overdayscount"  type="text"  size=10 value="<?php echo $an_info['overdayscount'];?>"   onblur="comput();">&nbsp;&nbsp;<?php echo L('package_overdayfee');?>：<input name="waybill[overdaysfee]" id="overdaysfee" class="input-text" type="text" size="10" value="<?php echo $an_info['overdaysfee'];?>"  onblur="comput();">&nbsp;&nbsp;</td>
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
		<th><strong><?php echo L('goodsname');?>：</strong></th>
		<td><input name="waybill[goodsname]" id="goodsname"  type="text"  size=30 value="<?php echo $an_info['goodsname'];?>"></td>
	</tr>
	<tr>
  		<th><strong><?php echo L('remark')?>：</strong></th>
        <td>
		<textarea name="waybill[remark]" id="remark" cols="50" rows=2><?php echo $an_info['remark'];?></textarea>
		</td>
	</tr>
	<?php //if($an_info['status']==19 || $an_info['status']==20 || $an_info['status']==21){?>
	<tr>
		
		<td  colspan=2>

	<table width="100%" cellspacing="0" style="border:solid 1px #d5dfe8;">
			<thead>
				<tr>
				<th style="text-align:center;display:none">邮单号</th>
				<th style="text-align:left">预报物品名称</th>
				<th style="text-align:center">数量</th>
				<th style="text-align:center">价值</th>
				<th style="text-align:center;display:none">重量</th>
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
		

		<br>

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
	<?php //}?>
	<tr>
		<th><strong><?php echo L('mode_of_despatch');?>：</strong></th>
		<td  ><input readonly="readonly" name="waybill[shippingname]" id="shippingname"  type="text"  size=15 value="<?php echo $an_info['shippingname'];?>">&nbsp;&nbsp;
		<strong><?php echo L('express_no');?>：</strong><input name="waybill[expressnumber]" id="expressnumber"  type="text"  size=15 value="<?php echo $an_info['expressnumber'];?>">&nbsp;&nbsp;
		<strong><?php echo L('express_no_url');?>：</strong><input name="waybill[expressurl]" id="expressurl"  type="text"  size=15 value="<?php echo $an_info['expressurl'];?>"></td>
	</tr>
	<tr>
		<th><strong><?php echo L('sendaddressid');?>：</strong></th>
		<td><input name="waybill[sendaddressname]" id="sendaddressname"  type="text"  size=65 value="<?php echo $an_info['sendaddressname'];?>"></td>
	</tr>
	<tr>
		<th><strong><?php echo L('takeaddressid');?>：</strong></th>
		<td><input name="waybill[takeaddressname]" id="takeaddressname"  type="text"  size=65 value="<?php echo $an_info['takeaddressname'];?>"></td>
	</tr>
	<tr>
		<th><strong><?php echo L('真实姓名')?>：</strong></th>
		<td colspan=3><input name="package[truename]" id="truename" class="input-text" type="text" size="15" value="<?php echo $an_info['truename'];?>"></td>
	</tr>
	
	<tr style="display:none">
		<th><strong><?php echo L('upload_image');?>：</strong></th>
		<td> <?php echo form::images('waybill[picture]','picture',$an_info['picture'],'waybill', '0',50,'', '','png|jpg|gif');?> </td>
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
				echo '<table><tr><td><INPUT class=input-text style="WIDTH: 310px" value="'.$img.'" name="imagesurl_url[]" /></td><td><span onclick="remove_input(this)" style="cursor:pointer;">delete</span></td></tr></table> ';
			}
		}
		
		?>
		
		</div>
		</fieldset>
		<div class="bk10"></div>
		<div class='picBut cu'><a herf='javascript:void(0);' onclick="javascript:flashupload('imagesurl_images', '附件上传','imagesurl',change_images,'50,gif|jpg|jpeg|png|bmp,1','waybill','60','<?php echo upload_key('50,gif|jpg|jpeg|png|bmp,1');?>')"/> 选择图片 </a></div>  </td>
    </tr>

	</tbody>
</table>


<input type="hidden" name="lastindex" id="lastindex" value="1"/>


<input type="submit" name="dosubmit" id="dosubmit" value=" <?php echo L('ok')?> " class="dialog">&nbsp;<input type="reset" class="dialog" value=" <?php echo L('clear')?> ">
</form>
</div>
</body>
</html>
<script type="text/javascript">
$(document).ready(function(){
	$.formValidator.initConfig({formid:"myform2",autotip:true,onerror:function(msg,obj){}});
	$('#expressno').formValidator({onshow:"<?php echo L('expressno').L('cannot_empty');?>",onfocus:"<?php echo  L('expressno').L('cannot_empty');?>",oncorrect:"<?php echo L('right');?>"}).inputValidator({min:1,onerror:"<?php echo  L('expressno').L('cannot_empty');?>"});
});
function remove_input(obj){
	$(obj).parent().parent().remove();
}

$(document).ready(function(){
	$.formValidator.initConfig({formid:"myform",autotip:true,onerror:function(msg,obj){window.top.art.dialog({content:msg,lock:true,width:'220',height:'70'}, function(){this.close();$(obj).focus();})}});
	$('#title').formValidator({onshow:"<?php echo L('number').L('cannot_empty');?>",onfocus:"<?php echo L('number').L('cannot_empty');?>",oncorrect:"<?php echo L('right')?>"}).inputValidator({min:1,onerror:"<?php echo L('number').L('cannot_empty');?>"});
});


function ajaxOpt(areaid,flag){
	if(flag==1){
	
	if(document.getElementById('sendid').options[document.getElementById('sendid').selectedIndex].value==0){
			document.getElementById('takeid').length=1;
		}else{

		$.post("/index.php?m=waybill&c=admin_waybill&a=edit&act=gettakelists&areaid="+areaid+"&checkhash=<?php echo $_SESSION['checkhash'];?>",function(data){
		
			var datas = eval(data);
			var takeobj = document.getElementById('takeid');
			takeobj.length=1;
			for(var i=0;i<datas.length;i++){
				var obj = datas[i];
				takeobj.options[i+1] = new Option(obj.name,obj.linkageid);
			}
			
			$("#takeid").val('<?php echo $an_info[takeid];?>');

		});}
	}else{

	if(document.getElementById('takeid').options[document.getElementById('takeid').selectedIndex].value==0){
			document.getElementById('shippingid').length=1;
		}else{
		var sendid = document.getElementById('sendid').options[document.getElementById('sendid').selectedIndex].value;
		$.post("/index.php?m=waybill&c=admin_waybill&a=edit&act=getshippingmethod&sendid="+sendid+"&idd="+areaid+"&checkhash=<?php echo $_SESSION['checkhash'];?>",function(data){
		
			var datas = eval(data);
			var takeobj = document.getElementById('shippingid');
			takeobj.length=1;
			for(var i=0;i<datas.length;i++){
				var obj = datas[i];
				takeobj.options[i+1] = new Option(obj.title,obj.aid);
				
			}
			$("#shippingid").val('<?php echo $an_info[shippingid];?>');

		});}
	}
}

ajaxOpt('<?php echo $an_info[sendid];?>',1);

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
	//var discount = parseFloat(memberdiscount)+parseFloat(otherdiscount);//需要打多少折
	var discount = parseFloat(otherdiscount);//需要打多少折
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