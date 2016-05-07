<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
 <style>
 .tdrow{line-height:23px;}
 </style>


<link type="text/css" rel="stylesheet" href="/resource/css/profile.css" media="screen" />
<link rel="stylesheet" href="/resource/css/style.css" type="text/css" media="all" />
   <?php
				 
				 $allsendlists=$this->getsendlists();
				 $allturnway=$this->getturnway();
				 $allship=$this->getallship();
				 ?>

        <!--E 导航-->   
  
        <!--S 内容-->
        <article class="content">
        	
         	<!--S 内容-->
        	<div class="layout pro-mod">        
            	<div class="layout grid-s12m0">
			       <div class="col-main">
			       	 <div class="main-wrap">
						<div class="pcont-mod">
                        	

				  <form action="/index.php?m=waybill&c=admin_waybill&a=addorder&aid=<?php echo $an_info[aid];?>&hid=<?php echo $an_info[storageid];?>" id="mainForm" name="mainForm" method="post"  enctype="multipart/form-data" onsubmit="return submitcheck();">


			<div class="pcont-mod-bd pro-info" >
                  <div class="balance-info">一、包裹信息</div><!--E .balance-info-->
                  <span class="blank"></span>
					<!--//////////////////////////////////////////////////////////////////////////////////////////////////-->
		
					<div class="pro-info-v02">
                                  
                <ul class="pform-list tip-pform-list">
<?php
$_uid=$_GET['uid'];

if(!isset($_uid)){



?>
				
				  <li style="clear:both;">
                    <label for="package_l">用户信息<em class="color-red">*</em></label>
                    <strong>收件标识：</strong><input  name="package[takeflag]" id="takeflag" class="input-text" type="text" size="15" onblur="getusername(this.value,1);">   <strong>收件代号：</strong><input name="package[takecode]" id="takecode" class="input-text" type="text" size="15" onblur="getusername(this.value,2);">   <strong><?php echo L('username')?></strong>：<input name="package[username]" id="username" class="input-text" type="text" size="15" onblur="getusername(this.value,4);">  <strong>UserID：</strong><input name="package[userid]" id="userid" class="input-text" type="text" size="15" onblur="getusername(this.value,3);" >
					<p style="color:red"> <input type="button"  onclick="gotoNext()" value="next" /></p>
                                                                     
                </li>
<script>
					function gotoNext(){
					if($("#takeflag").val()=="" || $("#takecode").val()=="" || $("#username").val()=="" || $("#userid").val()==""){
						alert("请输入用户名或用户ID!");
						return false;
					}else{
					var sql="&takeflag="+$("#takeflag").val();
					sql+="&takecode="+$("#takecode").val();
					sql+="&username="+$("#username").val();
					window.location.href="/index.php?m=waybill&c=admin_waybill&a=addorder"+sql+"&uid="+$("#userid").val()+"&checkhash=<?php echo $_GET['checkhash']?>";
					}
				}
</script>
<?php
}else{	
?>

<li style="clear:both;display:none;">
                    <label for="package_l">用户信息<em class="color-red">*</em></label>
                    <strong>收件标识：</strong><input value="<?php echo $_GET['takeflag'];?>" name="package[takeflag]"  class="input-text" type="text" size="15" >   <strong>收件代号：</strong><input name="package[takecode]" id="takecode" class="input-text" type="text" size="15"  value="<?php echo $_GET['takecode'];?>" >   <strong><?php echo L('username')?></strong>：<input name="package[username]" id="username" class="input-text" type="text" size="15"  value="<?php echo $_GET['username'];?>" >  <strong>UserID：</strong><input name="package[userid]" id="userid" class="input-text" type="text" size="15"  value="<?php echo $_GET['uid'];?>"  >
					<p style="color:red"> <input type="button"  onclick="gotoNext()" value="next" /></p>
                                                                     
                </li>


				<li style="clear:both;">
                    <label for="storage_id">入库仓库：<em class="color-red">*</em></label>
                    <select name="package[storageid]" id="storageid" >
				<?php
					$seltext="";$num=0;
					$allstorage = $this->getallstorage();
					foreach($allstorage as $row){
						$sel="";$num++;
						if($num==1){$sel=" selected";$seltext=$row['title'];}
						echo '<option value="'.$row['aid'].'" '.$sel.'>'.$row['title'].'</option>';
					}
				?>
		</select>
		
		<input name="package[storagename]" id="storagename"  type="hidden" value="<?php echo $seltext;?>" >      
		



		
                </li>
				

                  <li style="clear:both;">
                    <label for="postno">商家发货快递<em class="color-red">*</em></label>
                    <select name="package[expressid]"  id="expressid" class="inp inp-select" onchange="javascript:document.getElementById('expressname').value=document.getElementById('expressid').options[document.getElementById('expressid').selectedIndex].text;">
							<option value="0">请选择</option>
						
							<?php
							$expressid=0;
							foreach($allship as $v){
								$sel="";
								if($an_info[expressname]==$v[title]){$expressid=$v[aid];}
								echo '<option value="'.$v[aid].'" '.$sel.'>'.$v[title].'</option>';
							}
							?>
							</select>
								
							
					  <input id="expressid" name="package[expressid]" value="<?php echo $expressid;?>"  type="hidden"> 
					 <input id="expressname" name="package[expressname]" value="<?php echo $an_info[expressname];?>"  type="hidden"> 
                                                                     
                </li> 
				 <li style="clear:both;">
                    <label for="expressno">邮单号<em class="color-red">*</em></label>
                 
					<input id="expressno" name="package[expressno]"  value="<?php echo $an_info[expressno];?>" maxlength="100" class="inp w268" type="text" tabindex="4"   onKeyUp="this.value=this.value.replace(/[^0-9a-z]/gi,'')"  >     
                   
                </li> 
				
				<li style="clear:both">
					<label for="package_l">数量合计：</label><input name="package[amount]" type="text" id="totalamount" class="inp"  size="5" onKeyUp="this.value=this.value.replace(/[^0-9]/gi,'')" value="0"/> 重量合计(G)：<input name="package[weight]" type="text" id="totalweight" class="inp"  size="5" onKeyUp="this.value=this.value.replace(/[^0-9]/gi,'')" value="0"/>  价值合计(円)： <input name="package[price]" type="text" id="totalprice" class="inp"  size="5" onKeyUp="this.value=this.value.replace(/[^0-9.]/gi,'')" value="0"/>
				</li>
					<li style="clear:both; display:none">
                    <label for="package_l">货物长宽高<em class="color-red">*</em></label>
                    长：<input id="package_l" name="package[package_l]"  value="<?php echo $an_info[bill_long];?>"  class="inp w88" type="text" tabindex="4"  >  x 宽：<input id="package_w" name="package[package_w]"  value="<?php echo $an_info[bill_width];?>"  class="inp w88" type="text" tabindex="5">  x 高：<input id="package_h" name="package[package_h]"  value="<?php echo $an_info[bill_height];?>"  class="inp w88" type="text" tabindex="6">
                                                                     
                </li>
				<li style="display:none">
					<strong><?php echo L('member_discount');?>：</strong>
					<input name="waybill[memberdiscount]" id="memberdiscount" class="input-text" type="text" size="10" value="0" onblur="preComputFee();">%&nbsp;&nbsp;
					<?php echo L('additional_discount');?>：<input name="waybill[otherdiscount]" id="otherdiscount" class="input-text" type="text" size="10" value="1" onblur="preComputFee();">&nbsp;
					<input name="waybill[taxfee]" id="taxfee" class="input-text" type="text" size="10" value="<?php echo $an_info['taxfee'];?>">
				</li>
				</ul>
<link rel="stylesheet" type="text/css" href="resource/css/global4.css"/>
				<span class="blank"></span>
				<table border=1 width="97%" align="center"  id="tb__product"  style="border:1px solid #999">
					<tr >
					<td align=center class="w58"  style="display:none;">序号</td>
					<td align=center class="w148" height="35px" >物品名称</td>
					<td align=center class="w58">数量</td>
					<td align=center class="w58" style="display:none;">重量(G)</td>
					<td align=center class="w58">价值(円)</td>
					<td width="40">&nbsp;</td>
					</tr>
					
					<tr id="trProduct" class="goods__row">
					<td align=center class="w58" class="index_number"  style="display:none">1</td>
					<td colspan="4" >
					<input id="number" name="waybill_goods[1][number]"  type="hidden"   value="1">
						<table border=0 width="100%" style="margin:5px 0;">
						<tr>
						
						<td align=center width="20%"><input id="goodsname" name="waybill_goods[1][goodsname]"  class="inp w148" type="text"  onblur="blurcheck(this)" value="<?php echo $an_info[goodsname];?>"> </td>
						<td align=left width="15%"><input id="amount" name="waybill_goods[1][amount]"  class="inp w58" type="text" onblur="blurcheck(this)"  onKeyUp="this.value=this.value.replace(/[^0-9]/gi,'')" value="0"></td>
						
						<td align=left width="15%" style="display:none;"><input id="weight" name="waybill_goods[1][weight]"  class="inp w58" type="text" onblur="blurcheck(this)"  onKeyUp="this.value=this.value.replace(/[^0-9]/gi,'')" value="0"></td>

						<td align=center width="15%"><input id="price" name="waybill_goods[1][price]"  class="inp w58" type="text" onblur="blurcheck(this)" onKeyUp="this.value=this.value.replace(/[^0-9.]/gi,'')" value="0"></td>
						</tr>
						</table>
					</td>
					<td width="40" align=center><a href="javascript:void(0);" onclick="delRow(this);">删除</a></td>
					</tr>
				</table>

					<div style="clear:both;margin-top:10px; float:center;">
					<button style="margin-left:650px; height:30px; width: 90px; border-color:#F25F6D; background-color:#F25F6D; color:#fff;" type="button"  class="btn-login btn-cancel radius-three fl add_row" onclick="javascript:addRow()">继续添加物品</button>
					 <input name='txtTRLastIndex' type='hidden' id='txtTRLastIndex' value="1" />
					</div>
					

				</div>
					<!--//////////////////////////////////////////////////////////////////////////////////////////////////-->
			</div>

			<span class="blank"></span>
			
					<div class="pcont-mod-bd pro-info" >
					
                               <div class="balance-info">二、发货线路</div>
                                <span class="blank"></span>

							<input type="hidden" value="<?php echo $an_info[firstprice];?>" name="waybill[firstprice]"  id="firstprice" />
							<input type="hidden" value="<?php echo $an_info[addprice];?>" name="waybill[addprice]"  id="addprice" />
							  <input type="hidden" value="<?php echo $an_info[addweight];?>" name="waybill[addweight]"  id="addweight" />
							  <input type="hidden" value="<?php echo $an_info[firstweight];?>" name="waybill[firstweight]"  id="firstweight" />
                              
			<input type="hidden" value="<?php echo $an_info[sendname];?>" name="waybill[sendname]"  id="sendname" />
			<input type="hidden" value="<?php echo $an_info[takename];?>" name="waybill[takename]"  id="takename" />
			<input type="hidden" value="<?php echo $an_info[shippingname];?>" name="waybill[shippingname]"  id="shippingname" />
				
				
				
				<div class="pro-info-v02">
                                  
                  <ul class="pform-list tip-pform-list">
                <li>
                    <label for="sendid" style="display:none;">发货地 (国家和地区)<em class="color-red">*</em></label>
                    <select  name="waybill[sendid]" id="sendid"  class="inp inp-select" style="display:none;">
					<option value="0"> 请选择 </option>
					
					<?php
					foreach($allsendlists as $r){
						$sel="";
						if ($an_info[sendid]==$r[linkageid]){$sel=" selected ";}
						echo '<option value="'.$r[linkageid].'" '.$sel.'>'.$r[name].'</option>';
					}
					?>
					
				
					</select> &nbsp;<label style="float:none;width:auto;display:none;">收货地 (国家和地区)</label><select  name="waybill[takeid]" id="takeid"   class="inp inp-select" style="display:none;">  
					<option value="0"> 请选择 </option>
					

					<?php
					foreach($allsendlists as $r){
						$sel="";
						if ($an_info[takeid]==$r[linkageid]){$sel=" selected";}

						echo '<option value="'.$r[linkageid].'" '.$sel.'>'.$r[name].'</option>';
					}
					?>
					
					
					</select>&nbsp;&nbsp;&nbsp;&nbsp;<label style="float:none;width:auto;">发货线路</label><select  name="waybill[shippingid]" id="shippingid"   class="inp inp-select"  onchange="document.getElementById('shippingname').value=document.getElementById('shippingid').options[document.getElementById('shippingid').selectedIndex].text;changeLine();">  
					<option value="0"> 请选择 </option>
				
					<?php
					foreach($allturnway  as $way){
						$sel="";
						if($an_info[shippingid]==$way[aid]){
						$sel=" selected";
						}
						echo '<option value="'.$way[aid].'" '.$sel.' org="'.$way[origin].'" dst="'.$way[destination].'" price="'.$way[price].'"  addprice="'.$way[addprice].'" addweight="'.$way[addweight].'" firstweight="'.$way[firstweight].'" title="'.$way[title].'" otherdiscount="'.$way[discount].'">'.$way[title].' '.strip_tags($way[content]).'</option>';
					}
					?>
					
					
					
					</select> 
                                                                     
                </li>  
                
				
				 </ul>
				 </div>
			</div>
			<span class="blank"></span>
			<div class="pcont-mod-bd pro-info" >
                                <div class="balance-info">三、选择服务项目</div><!--E .balance-info-->
                                <span class="blank"></span>
                                    <table border=0>
								<tr>
								<td><input type="radio" name="waybill[srvtype]" value="0" checked  onclick="serviceOpt(0)"/> 单票转运&nbsp;&nbsp;<input type="radio" name="waybill[srvtype]" value="1" onclick="serviceOpt(1)"/> 合箱&nbsp;&nbsp;<input type="radio" name="waybill[srvtype]" value="2" onclick="serviceOpt(2)"/> 分箱</td>
								</tr>
								</table>
								<script>
								function serviceOpt(flag){
									if(flag==0){
										$("#values_baox").show();
										$("#span_addValues").show();
									}else{
										
									
										$("#values_baox").hide();
										$("#span_addValues").hide();
										//$('#service_value_sprice').val(0);
										//securtychk();
										
									}
								}
								</script>
								<div class="pro-info-v02">
                                  
                     <ul class="pform-list tip-pform-list">
			
					<li style="margin-left:15px;">
					<?php
					$_addvalues = string2array($an_info['addvalues']);
					echo '<span id="span_addValues">';
					foreach($servicelist as $srv){

						$currencyname = $this->getcurrencyname($srv['currencyid']);
						$unitname = $this->getunitname($srv['unit']);
						$sel="";
						foreach($_addvalues as $bill){

							if ($srv['aid']==$bill['servicetypeid']){
								$sel=" checked";
								$temp_price=$bill['price'];
							}
							
						}

						if ($srv[title]=='保险'){
								$secuty=1;
								$temp_checked=$sel;
								$temp_aid=$srv[aid];
								$temp_title=$srv[title];
								$temp_currencyid=$srv['currencyid'];
								$temp_unit=$srv['unit'];
								$temp_type=$srv[type];
								$temp_remark=$srv['remark'];
								$temp_currencyname=$currencyname;
								$temp_unitname=$unitname;
						}else{
					?>

						<input name="service_value[<?php echo $srv[aid];?>][title]" type="hidden"  value="<?php echo $srv['title'];?>"/>
						<input name="service_value[<?php echo $srv[aid];?>][currencyname]" type="hidden"  value="<?php echo $currencyname;?>"/>
						<input name="service_value[<?php echo $srv[aid];?>][unitname]" type="hidden"  value="<?php echo $unitname;?>"/>
						<input name="service_value[<?php echo $srv[aid];?>][price]" type="hidden"  value="<?php echo $srv['price'];?>"/>
						<input name="service_value[<?php echo $srv[aid];?>][currencyid]" type="hidden"  value="<?php echo $srv['currencyid'];?>"/>
						<input name="service_value[<?php echo $srv[aid];?>][unitid]" type="hidden"  value="<?php echo $srv['unit'];?>"/>
						<input name="service_value[<?php echo $srv[aid];?>][servicetype]" type="hidden"  value="<?php echo $srv[type];?>"/>
						<input onclick="preComputFee();" rel="<?php echo $srv['price'];?>" name="service_value[<?php echo $srv[aid];?>][servicetypeid]"  value="<?php echo $srv[aid];?>" type="checkbox"  <?php echo $sel;?>> <label for="service<?php echo $srv[aid];?>" style="float:none;width:auto;" ><?php echo $srv[title];?><em class="color-red"></em></label>&nbsp;&nbsp;<font color="#FF953F"><?php  echo $currencyname; ?><?php echo $srv['price'];?></font>/<?php    echo $unitname; ?>&nbsp;&nbsp;&nbsp;&nbsp;

						

				
					
					<?php
						}
					}

					echo '</span>';

					if ($secuty){
					?>
						  <p style="border-top:solid 1px #ccc;padding-top:10px;margin-top:10px;" id="values_baox">
						    <input name="service_value[<?php echo $temp_aid;?>][title]" type="hidden"  value="<?php echo $temp_title;?>"/>
							<input name="service_value[<?php echo $temp_aid;?>][currencyname]" type="hidden"  value="<?php echo $temp_currencyname;?>"/>
							<input name="service_value[<?php echo $temp_aid;?>][unitname]" type="hidden"  value="<?php echo $temp_unitname;?>"/>
							
							<input name="service_value[<?php echo $temp_aid;?>][currencyid]" type="hidden"  value="<?php echo $temp_currencyid;?>"/>
							<input name="service_value[<?php echo $temp_aid;?>][unitid]" type="hidden"  value="<?php echo $temp_unit;?>"/>
							<input name="service_value[<?php echo $temp_aid;?>][servicetype]" type="hidden"  value="<?php echo $temp_type;?>"/>
							<input  id="_secutychk" name="service_value[<?php echo $temp_aid;?>][servicetypeid]"  value="<?php echo $temp_aid;?>" type="checkbox" onclick="if(this.checked){$('#secuty_text').show();}else{$('#service_value_sprice').val(0);$('#secuty_text').hide();}" <?php echo $temp_checked;?>> <label for="service<?php echo $temp_aid;?>" style="float:none;width:auto;" ><?php echo $temp_title;?><em class="color-red"></em></label>&nbsp;&nbsp;<font  id="secuty_text" <?php if (!$temp_checked){echo ' style="display:none"';} ?>><?php  echo $temp_currencyname; ?><input name="service_value[<?php echo $temp_aid;?>][price]" id="service_value_sprice" type="text"  value="<?php echo $temp_price;?>" size=5 class="inp" onKeyUp="this.value=this.value.replace(/[^0-9.]/gi,'');securtychk();"/>&nbsp;<span id="money_show" style="color:#ff6600"></span>&nbsp;<?php echo $temp_remark;?></font>
						  </p>

						 <?php
					}	
							?>
                                                                     
					</li>

				
				</ul>
				 </div>
			</div>
			<input id="txtSecuty" name="waybill[otherfee]" type="hidden"  value="0"/>
			<script>
			function securtychk(){
				
			var mval=$("#service_value_sprice").val();
				if(mval==""){mval=0;}
				mval=parseFloat(mval);

				var result=0;
				var shippingid = document.getElementById('shippingid').options[document.getElementById('shippingid').selectedIndex].value;
				
				if(shippingid=='0'){
					alert("请选择发货线路！");
					return false;
				}else{
				
					
					$.ajax({ 
					  type: 'POST', 
					  url: "/api.php?op=public_api&a=get_securefee&shippingid="+shippingid+"&securevalue="+mval+"&t="+Math.random(),  
					  dataType: 'text', 
					  async:false,
					  success: function(data){
						result = data;
					  }
					}); 

					if(mval<=0){
						$("#_secutychk").attr("checked",false);
					}
					
					var val = parseFloat(result);	

					$("#txtSecuty").val(val.toFixed(2));
					$("#_secutychk").attr('rel',val.toFixed(2));
					$("#money_show").html(val.toFixed(2)+"円");
				}

			}
			</script>

			<div class="pcont-mod-bd pro-info" style="display:none">
                  
                   <span class="blank"></span>
					<table border=0 width="97%" align=center class="balance-info" cellpadding=2 cellspacing=2>
					<thead>
					<tr>
					<th height=25>发货渠道</th>
					<th>预期重量</th>
					<th>预估运费(円)</th>
					<th>其它费用</th>
					<th>总费用(円)</th>
					</tr>
					</thead>
					<tr>
					<td height=25 bgcolor="#ffffff" align="center" id="td_sendline">&nbsp;</td>
					<td bgcolor="#ffffff" align="center" id="td_weight">  0 </td>
					<td bgcolor="#ffffff" align="center" id="td_shipfee">0</td>
					<td bgcolor="#ffffff" align="center" id="td_otherfee">0</td>
					<td bgcolor="#ffffff" align="center" id="td_allfee">0</td>
					</tr>
					</table>
			</div>
			
			<span class="blank"></span>

			<div class="pcont-mod-bd pro-info" style="display:none;">
                <div class="balance-info">四、填写发货地址</div><!--E .balance-info-->
                <span class="blank"></span>                 
				<div class="pro-info-v02">           
				<ul class="pform-list tip-pform-list">

				
					<li style="margin-left:15px;">
					<input  name="waybill[sendaddressid]"  value="0" type="radio" onclick="newaddress(0,0)" checked> <label for="sendaddress" style="float:none;width:auto;">填写新发货地址<em class="color-red"></em></label>  
																		 
					</li>
				</ul>
				 </div>

					<!-- 新发货地址 -->
					<input type="hidden" value="2" name="send_address[addresstype]"  id="addresstype" />
					<div class="pro-info-v02" id="new_waybill_sendaddress" style="display:none">
                                  
                 <ul class="pform-list tip-pform-list">
               
                 <li>
                    <label for="send_truename">寄件人<em class="color-red">*</em></label>
                    <input id="send_truename" name="send_address[truename]" value="" maxlength="20" class="inp w268" type="text" tabindex="1">     
                                                              
                </li> 
				 <li>
                    <label for="send_country">国家<em class="color-red">*</em></label>
                    <input id="send_country" name="send_address[country]" value="" maxlength="20" class="inp w268" type="text" tabindex="1">     
                                                              
                </li> 
                 <li class="li_receiver">
                    <label for="send_province">所在地区<em class="color-red">*</em></label>
                    <input id="send_province" name="send_address[province]" value=""  maxlength="20" class="inp w88" type="text" tabindex="1"> &nbsp;<label style="float:none;width:36px;">省/州</label>&nbsp; 
 					<input id="send_city" name="send_address[city]" value=""  maxlength="20" class="inp w88" type="text" tabindex="1">&nbsp;<label style="float:none;width:20px;">市</label>
                   
                                                                   
                </li>     
				<?php 
					if (strpos($storage_row[address],"香港")!==false){
						$m_address= $storage_row[address];
					}else{
						$m_address= str_replace("St"," St&nbsp;&nbsp;,#".(10000+$this->_userid),$storage_row[address]);
					}
					?>
                <li>
                    <label for="send_address">详细地址<em class="color-red"></em></label>
                    <input id="send_address" name="send_address[address]"  value=""  maxlength="250" class="inp w268" type="text" tabindex="4">     
                                                                     
                </li>
				 <!--<li>
                    <label for="send_company">公司名称<em class="color-red">*</em></label>
                    <input id="send_company" name="send_address[company]"  value="" maxlength="100" class="inp w268" type="text" tabindex="4">     
                                                                     
                </li>-->
                <li>
                    <label for="send_postcode">邮政编码<em class="color-red"></em></label>
                    <input id="send_postcode" name="send_address[postcode]" onKeyUp="this.value=this.value.replace(/[^0-9]/gi,'')" style="ime-mode:disabled;"  value="" maxlength="6" class="inp w268" type="text" tabindex="5">     
                                                                  
                </li>
                <li>
                    <label for="send_mobile">手机号码<em class="color-red">*</em></label>
                    <input id="send_mobile" name="send_address[mobile]"  value=""  style="ime-mode:disabled;" maxlength="30"  class="inp w268" type="text" tabindex="6">     
                                                                  
                </li>

                  <li>
                    <label for="send_email">邮箱地址</label>
                    <input id="send_email" name="send_address[email]"  style="ime-mode:disabled;" value="" maxlength="30" class="inp w268" type="text" tabindex="10">     
                                                         
                </li>
				
            
                
                </ul>
                                </div><!--E .pro-info-v01-->

			</div>

			<div class="pcont-mod-bd pro-info" >
                <div class="balance-info">四、填写收货地址</div><!--E .balance-info-->
                <span class="blank"></span>                
				<div class="pro-info-v02">           
                <ul class="pform-list tip-pform-list">
              <?php
				$k=0;
				foreach($addresslist as $addr){

					if  ($addr[addresstype]==1){$k++;

					$sel="";
					if ($an_info[takeaddressid]==$addr[aid]){$sel=" checked";}
					$chk="";
					if ($an_info[takeaddressid]==0 && $k==1){
						$chk=" checked";
					}
			  ?>
				
				
				
				<li style="margin-left:15px;"><div style="float:left;">
					<input onclick="newaddress(<?php echo $addr[aid];?>,1)" name="waybill[takeaddressid]"  value="<?php echo $addr[aid];?>" type="radio" <?php echo $sel;?> <?php echo $chk;?> style="margin-top:10px;"> </div><div style="margin-left:25px;"> <label for="address<?php echo $addr[aid];?>" style="float: none;text-align:left;width:700px;margin-top:-12px;"><?php echo $addr[truename];?> <?php echo $addr[mobile];?> <?php echo $addr[country];?> <?php echo $addr[province];?> <?php echo $addr[city];?> <?php echo $addr[address];?> <?php echo $addr[postcode];?><em class="color-red"></em></label>  </div>
																		 
					</li>
				
				<?php
					}
				}	
				?>
				<li style="margin-left:15px;">
					<input  name="waybill[takeaddressid]"  value="0" type="radio" onclick="newaddress(0,1)"> <label for="takeaddress" style="float:none;width:auto;" >填写新收货地址<em class="color-red"></em></label>  
																		 
					</li>
			</ul>
				 </div>

				
				<!-- 新收货地址 -->
					<input type="hidden" value="1" name="take_address[addresstype]"  id="addresstype" />
					<div class="pro-info-v02" id="new_waybill_takeaddress" style="display:none">
                                  
                 <ul class="pform-list tip-pform-list">
               
                 <li>
                    <label for="take_truename">收件人<em class="color-red">*</em></label>
                    <input id="take_truename" name="take_address[truename]" value=""  maxlength="20" class="inp w268" type="text" tabindex="1">     
                                                              
                </li> 
				 <li>
                    <label for="take_country">国家<em class="color-red">*</em></label>
                    <!--<input id="take_country" name="take_address[country]" value="" maxlength="20" class="inp w268" type="text" tabindex="1">     -->

					<select  id="take_country" name="take_address[country]">
					<?php
					
					$countrydatas = getcache('get__linkage__lists', 'commons');
					
					
					foreach($countrydatas as $v){
						echo '<option value="'.trim($v['name']).'">'.$v['name'].'</option>';
					}

					?>
					</select>
                                                              
                </li> 
                 <li class="li_receiver">
                    <label for="take_province">所在地区<em class="color-red">*</em></label>
                    <input id="take_province" name="take_address[province]" value=""  maxlength="20" class="inp w88" type="text" tabindex="1"> &nbsp;<label style="float:none;width:36px;">省/州</label>&nbsp; 
 					<input id="take_city" name="take_address[city]" value=""  maxlength="20" class="inp w88" type="text" tabindex="1">&nbsp;<label style="float:none;width:20px;">市</label>
                   
                                                                   
                </li>     
                <li>
                    <label for="take_address">详细地址<em class="color-red">*</em></label>
                    <input id="take_address" name="take_address[address]"  value=""  maxlength="100" class="inp w268" type="text" tabindex="4">     
                                                                     
                </li>
				 <!--<li>
                    <label for="take_company">公司名称<em class="color-red">*</em></label>
                    <input id="take_company" name="take_address[company]"  value="" maxlength="100" class="inp w268" type="text" tabindex="4">     
                                                                     
                </li>-->
                <li>
                    <label for="take_postcode">邮政编码<em class="color-red">*</em></label>
                    <input id="take_postcode" name="take_address[postcode]" onKeyUp="this.value=this.value.replace(/[^0-9]/gi,'')" style="ime-mode:disabled;"  value="" maxlength="6" class="inp w268" type="text" tabindex="5">     
                                                                  
                </li>
                <li>
                    <label for="take_mobile">手机号码<em class="color-red">*</em></label>
                    <input id="take_mobile" name="take_address[mobile]"  value="" style="ime-mode:disabled;" maxlength="30"  class="inp w268" type="text" tabindex="6">     
                                                                  
                </li>

                  <li>
                    <label for="take_email">邮箱地址<em class="color-red">*</em></label>
                    <input id="take_email" name="take_address[email]"  style="ime-mode:disabled;" value="" maxlength="30" class="inp w268" type="text" tabindex="10">     
                                                         
                </li>
				
            
                
                </ul>
                                </div><!--E .pro-info-v01-->


			</div>

			<div class="pcont-mod-bd pro-info" >
                                <div class="balance-info">六、运单备注</div><!--E .balance-info-->
                                <span class="blank"></span>
                                    
								<div class="pro-info-v02">
                                  
                     <ul class="pform-list tip-pform-list">
			

                

				<li>
                    <label for="remark" style="width:auto;margin-left:20px;">备注<em class="color-red"></em></label>
                    <textarea id="remark" name="waybill[remark]"  class="inp " style="height:60px;width:70%;"><?php echo $an_info[remark];?></textarea>     
                                                                  
                </li>
 
                 <li>
					<center>
					 <input type="hidden" name="dosubmit" id="dosubmit" value="1"/>
					 <input type="hidden" name="lastindex" id="lastindex" value="1"/>
                    
					<button style="float:none;" type="submit" class="btn-login radius-three fl" tabindex="17" title="保存">保&nbsp;存</button>
					</center>
				</li>

				<?php
					
}
				?>
                </ul>
                                </div><!--E .pro-info-v01-->
                              
                             <span class="blank22"></span>
                          </div><!--E .pcont-mod-bd-->
					 </form>
		




                        </div><!--E .pcont-mod-->
                     </div><!--E .main-wrap-->
			       </div><!--E .col-main-->
			     
				  
			

                </div><!--E .grid-s12m0-->
            </div>
            <!--E 内容-->                 
        </article>
        <!--E 内容-->
        <!--S 底部-->
   


  <script  type="text/javascript">
 
function preComputFee(){
	var weight = $("#weight").val();if(weight==""){weight=0;}
	var amount = $("#amount").val();
	var firstprice = $("#firstprice").val();
	var addprice = $("#addprice").val();
	var addweight = $("#addweight").val();
	var firstweight = $("#firstweight").val();
	var totalweight = Math.ceil(weight)*amount;
	var otherfee=0;
	var totalfee = firstweight * firstprice + (totalweight-addweight) * addprice;

	var memberdiscount=parseFloat($("#memberdiscount").val());//会员折扣
	var otherdiscount=parseFloat($("#otherdiscount").val());//额外折扣
	var discount = parseFloat(memberdiscount)+parseFloat(otherdiscount);//需要打多少折
	$("#td_shipfee").html(totalfee.toFixed(2));
	$("#td_weight").html(totalweight);

	if(discount==0)
		discount=1;//不打折

	$("input[type='checkbox']:checked").each(function(){
		otherfee+=parseFloat($(this).attr('rel'));
	});
	
	$("#td_otherfee").html(otherfee.toFixed(2));

	var allfee = totalfee*discount + otherfee ;
	
	$("#td_allfee").html(allfee.toFixed(2));

  }

  function changeLine(){
	
		//document.getElementById('shippingid').options[document.getElementById('shippingid').selectedIndex].value;
		
		var sendid = $("#shippingid").find("option:selected").attr('org'); 
		var takeid = $("#shippingid").find("option:selected").attr('dst'); 
		$("#sendid").val(sendid);
		$("#takeid").val(takeid);

		$("#sendname").val($("#sendid").find("option:selected").text());
		$("#takename").val($("#takeid").find("option:selected").text());
		
		$("#firstprice").val($("#shippingid").find("option:selected").attr('price'));
		$("#addprice").val($("#shippingid").find("option:selected").attr('addprice'));
		$("#addweight").val($("#shippingid").find("option:selected").attr('addweight'));
		$("#firstweight").val($("#shippingid").find("option:selected").attr('firstweight'));

		$("#otherdiscount").val($("#shippingid").find("option:selected").attr('otherdiscount'));

		$("#td_sendline").html($("#shippingid").find("option:selected").attr('title'));

		//preComputFee();

  }
  function submitcheck(){
  
	if($("#expressid").val()=='0'){
		alert("请选择发货快递！");
		return false;
	}

	if($("#expressno").val()==''){
		alert("请填写邮单号!");
		return false;
	}

	if($("#shippingid").val()=='0'){
		alert("请选择发货线路！");
		return false;
	}

	

	changeLine();
	

	if($("#sendid").val()=='0'){
		alert("请选择发货地 (国家和地区)！");
		return false;
	}

	if($("#takeid").val()=='0'){
		alert("请选择收货地 (国家和地区)！");
		return false;
	}

	

	var gotoflag=0;
	var current_expressno="";

	$(".goods__row").each(function(){
	
		if($(this).find("select[id^='expressid']").val()=="0")
		{
			gotoflag=1;
			$(this).find("select[id^='expressid']").attr("style","border:solid 1px #f8cccc");
		}
		
		var expressname="";

		$(this).find("input[id^='expressname']").val($(this).find("select[id^='expressid']").find("option:selected").text());

		if($(this).find("input[id^='expressno']").val()=="")
		{
			gotoflag=1;
			$(this).find("input[id^='expressno']").attr("style","background:#ffe7e7");
		}

		if($(this).find("input[id^='goodsname']").val()=="")
		{
			gotoflag=1;
			$(this).find("input[id^='goodsname']").attr("style","background:#ffe7e7");
		}

		if($(this).find("input[id^='amount']").val()=="")
		{
			gotoflag=1;
			$(this).find("input[id^='amount']").attr("style","background:#ffe7e7");
		}

		if($(this).find("input[id^='weight']").val()=="")
		{
			gotoflag=1;
			$(this).find("input[id^='weight']").attr("style","background:#ffe7e7");
		}


		if($(this).find("input[id^='price']").val()=="")
		{
			gotoflag=1;
			$(this).find("input[id^='price']").attr("style","background:#ffe7e7");
		}

		if($(this).find("input[id^='boxcount']").val()=="")
		{
			gotoflag=1;
			$(this).find("input[id^='boxcount']").attr("style","background:#ffe7e7");
		}

		if($(this).find("input[id^='producturl']").val()=="")
		{
			gotoflag=1;
			$(this).find("input[id^='producturl']").attr("style","background:#ffe7e7");
		}

		if($(this).find("input[id^='sellername']").val()=="")
		{
			gotoflag=1;
			$(this).find("input[id^='sellername']").attr("style","background:#ffe7e7");
		}

		if($(this).find("input[id^='expressno']").val()!="")
		{
			if(current_expressno==""){
				current_expressno = $(this).find("input[id^='expressno']").val();
			}else{
				current_expressno = current_expressno +"_"+ $(this).find("input[id^='expressno']").val();
			}
		}


	});

	current_expressno = $("#expressno").val();

	var result="";
	

	$.ajax({ 
	  type: 'POST', 
	  url: "/api.php?op=public_api&a=expno_exists", 
	  data: {data : current_expressno}, 
	  dataType: 'text', 
	  async:false,
	  success: function(data){
		result = data;
	  }
	}); 
	
	if(result!=""){
		alert(result);
		return false;
	}

	if(gotoflag==1)
	{
		alert("请在运单里填写包裹！");
		return false;
	}

	if($("#totalamount").val()=='' || $("#totalprice").val()==''){
		alert("请在运单里填写包裹！");
		return false;
	}


	
	

	

	//判断收货地址
	
	
	if($('input:radio[name="waybill[takeaddressid]"]:checked').val()==null){
		alert("请填写收货地址！");
		return false;
	}


	if($('input:radio[name="waybill[takeaddressid]"]:checked').val()==0){
		//判断新收货地址
		if($("#take_truename").val()==''){
			alert("请填写收货寄件人！");
			$("#take_truename").focus();
			return false;
		}
		if($("#take_country").val()==''){
			alert("请填写收货国家！");
			$("#take_country").focus();
			return false;
		}
		if($("#take_province").val()==''){
			alert("请填写收货省/州！");
			$("#take_province").focus();
			return false;
		}
		/*if($("#take_city").val()==''){
			alert("请填写收货市！");
			$("#take_city").focus();
			return false;
		}*/
	
		if($("#take_address").val()==''){
			alert("请填写收货详细地址！");
			$("#take_address").focus();
			return false;
		}
		if($("#take_postcode").val()==''){
			alert("请填写收货邮编号码！");
			$("#take_postcode").focus();
			return false;
		}
		
		if($("#take_mobile").val()==''){
			alert("请填写收货手机号码！");
			$("#take_mobile").focus();
			return false;
		}
		if($("#take_email").val()==''){
			alert("请填写收货邮箱地址！");
			$("#take_email").focus();
			return false;
		}
	}

	//shippingid

  }	


  function newaddress(id,flag){
	if(flag==0){
		if(id==0)
		{
			document.getElementById('new_waybill_sendaddress').style.display="block"
		}else{
			document.getElementById('new_waybill_sendaddress').style.display="none"
		}
	}else if(flag==1){
		if(id==0)
		{
			document.getElementById('new_waybill_takeaddress').style.display="block"
		}else{
			document.getElementById('new_waybill_takeaddress').style.display="none"
		}
	}
  }

  function ajaxOpt(areaid,flag){
	/*if(flag==1){

	if(document.getElementById('sendid').options[document.getElementById('sendid').selectedIndex].value==0){
			document.getElementById('takeid').length=1;
		}else{

		$.post("/index.php?m=waybill&c=index&a=gettakelists&areaid="+areaid,function(data){
		
			var datas = eval(data);
			var takeobj = document.getElementById('takeid');
			takeobj.length=1;
			for(var i=0;i<datas.length;i++){
				var obj = datas[i];
				takeobj.options[i+1] = new Option(obj.name,obj.linkageid);
				
			}
			$("#takeid").val('{$an_info[takeid]}');

		});}
	}else{

	if(document.getElementById('takeid').options[document.getElementById('takeid').selectedIndex].value==0){
			document.getElementById('shippingid').length=1;
		}else{
		
		var sendid = document.getElementById('sendid').options[document.getElementById('sendid').selectedIndex].value;

		$.post("/index.php?m=waybill&c=index&a=getshippingmethod&sendid="+sendid+"&idd="+areaid,function(data){
		
			var datas = eval(data);
			var takeobj = document.getElementById('shippingid');
			takeobj.length=1;
			for(var i=0;i<datas.length;i++){
				var obj = datas[i];
				takeobj.options[i+1] = new Option(obj.title,obj.aid);
				
			}
			$("#shippingid").val('{$an_info[shippingid]}');

		});}
	}*/
}


	function addRow(){ 
		var n = $("#lastindex").val();
		
		if(n>8)
		{
			return false;
		}
		
		var num = $(".goods__row").size()+1;
		
		var mClone = $("#trProduct").clone(true);
		mClone[0].id="trProduct"+num;

		
		mClone.find("input[id^='expressno']").val("");
		mClone.find("input[id^='goodsname']").val("");
		mClone.find("input[id^='amount']").val(0);
		mClone.find("input[id^='weight']").val(0);
		mClone.find("input[id^='price']").val(0);
		mClone.find("input[id^='packageid']").val(0);

		mClone.find("input[id^='boxcount']").val(0);
		mClone.find("input[id^='producturl']").val("");
		mClone.find("input[id^='sellername']").val("");
		mClone.find("input[id^='remark']").val("");
	
		mClone.appendTo($("#tb__product"));
		
		orderRow();
		
	}
	
	function blurcheck(obj){
		if(obj.value!=""){
			obj.style.background="#ffffff";
		}else{
			obj.style.background="#ffe7e7";
		}

		if(obj.name.indexOf('[amount]')!=-1 || obj.name.indexOf('[weight]')!=-1 || obj.name.indexOf('[price]')!=-1){
			computtotal();//运算价格与数量
		}
	}


	function delRow(obj){
		var delid = $(obj).parent().parent().attr("id");
		if(delid!='trProduct'){
			$("#"+delid).remove();
			orderRow();
		}
		computtotal();
	}

	function orderRow(){
		$("#lastindex").val(1);
		$(".goods__row").each(function(i){
			$(this).find("td:first").html($("#lastindex").val());
			var num = $("#lastindex").val();
			$(this).find("input[id^='number']").val(num);
			
			$(this).find("select[id^='expressid']").attr("id","expressid"+num);
			$(this).find("input[id^='expressname']").attr("id","expressname"+num);

			$(this).find("input[id^='expressno']").attr("id","expressno"+num);
			$(this).find("input[id^='goodsname']").attr("id","goodsname"+num);
			$(this).find("input[id^='amount']").attr("id","amount"+num);
			$(this).find("input[id^='weight']").attr("id","weight"+num);
			$(this).find("input[id^='price']").attr("id","price"+num);
			$(this).find("input[id^='number']").attr("id","number"+num);
			$(this).find("input[id^='packageid']").attr("id","packageid"+num);

			$(this).find("input[id^='boxcount']").attr("id","boxcount"+num);
			$(this).find("input[id^='producturl']").attr("id","producturl"+num);
			$(this).find("input[id^='sellername']").attr("id","sellername"+num);
			$(this).find("input[id^='remark']").attr("id","remark"+num);

			$(this).find("input[id^='number']").attr("name","waybill_goods["+num+"][number]");
			$(this).find("select[id^='expressid']").attr("name","waybill_goods["+num+"][expressid]");
			$(this).find("input[id^='expressname']").attr("name","waybill_goods["+num+"][expressname]");

			$(this).find("input[id^='expressno']").attr("name","waybill_goods["+num+"][expressno]");
			$(this).find("input[id^='goodsname']").attr("name","waybill_goods["+num+"][goodsname]");
			$(this).find("input[id^='amount']").attr("name","waybill_goods["+num+"][amount]");
			$(this).find("input[id^='weight']").attr("name","waybill_goods["+num+"][weight]");
			$(this).find("input[id^='price']").attr("name","waybill_goods["+num+"][price]");
			$(this).find("input[id^='packageid']").attr("name","waybill_goods["+num+"][packageid]");

			$(this).find("input[id^='boxcount']").attr("name","waybill_goods["+num+"][boxcount]");
			$(this).find("input[id^='producturl']").attr("name","waybill_goods["+num+"][producturl]");
			$(this).find("input[id^='sellername']").attr("name","waybill_goods["+num+"][sellername]");
			$(this).find("input[id^='remark']").attr("name","waybill_goods["+num+"][remark]");

			var obj = $(this);
			//重新排序物品增值服务
			obj.find(".tdrow").find("input").each(function(){
				var $this = $(this);
				var onename = $this.attr("name").replace("service_order","");
				var arrname = onename.split('_');
				$this.attr("name","service_order["+num+"_"+arrname[arrname.length-1]);
			});

			num = parseInt(num)+1;
			$("#lastindex").val(num);
			
		});
		computtotal();
	}
	//计算
	function computtotal(){
		
		var totalamount=0,totalweight=0,totalprice=0;
		$(".goods__row").each(function(){
			
			totalamount += parseInt($(this).find("input[id^='amount']").val());
			totalweight += parseInt($(this).find("input[id^='weight']").val());
			totalprice +=parseFloat($(this).find("input[id^='price']").val())*parseInt($(this).find("input[id^='amount']").val());
		});

		$("#totalamount").val(totalamount);
		$("#totalweight").val(totalweight);
		$("#totalprice").val(totalprice);
	}
	


function getusername(userid,flag){
	if(userid){
		$.post("/api.php?op=public_api&a=getuid&checkhash=<?php echo $_SESSION['checkhash'];?>&flag="+flag+"&key="+userid,function(data){
			$("#username").val(data.username);
			$("#userid").val(data.userid);
			$("#takeflag").val(data.clientname);
			$("#takecode").val(data.clientcode);
			$("#truename").val(data.clientname);
		},'json');
	}
}
	
	
</script>
