<?php defined('IN_PHPCMS') or exit('No permission resources.'); ?> <?php include template('member', 'header'); ?>

        <!--E 导航-->   
		 
           <link rel="stylesheet" type="text/css" href="<?php echo JS_PATH;?>calendar/jscal2.css"/>
			<link rel="stylesheet" type="text/css" href="<?php echo JS_PATH;?>calendar/border-radius.css"/>
			<link rel="stylesheet" type="text/css" href="<?php echo JS_PATH;?>calendar/win2k.css"/>
			<script type="text/javascript" src="<?php echo JS_PATH;?>calendar/calendar.js"></script>
			<script type="text/javascript" src="<?php echo JS_PATH;?>calendar/lang/en.js"></script>
			  <!--S 内容-->
		<div id="ht-main" class="clearfixed">		
        <article class="content">
        	
         	<!--S 内容-->
        	<div class="layout pro-mod" style="border-top:none;">
			<?php include template('member', 'top'); ?>
            	<div class="layout grid-s12m0">
			       <div class="col-main">
			       	 <div class="main-wrap">
						<div class="pcont-mod">
                        	<div class="pcont-mod-hd">
                            	<h3>已入库件</h3>
								<div class="plink-mod">
                               
                                </div><!--E .plink-mod-->
                            </div><!--E .pcont-mod-hd-->
							
							<?php $allpackagestatus = $this->getpackagestatus();?>
                            <div class="pcont-mod-bd" style="padding-top: 0;">
							
						
                            	<!--S 右边内容开始-->
			                	<div class="bag-mod">
							    
							        <div id="tab-cont">
							          <div id="tab-cont-v01"> 
			                           		<div class="pcont-date-mod" style="padding-top:0;">
				                                <form action="" method="post" name="mainForm" id="mainForm">
												<div class="search-mod">
											
                           					 <input type="hidden" name="split_box_status" id="split_box_status" value=""/>
											 <input type="hidden" name="dosubmit" id="dosubmit" value="1"/>
											            <ul class="pform-list clearfix" style="padding-top:2px;">
											                  <li>
											                    <label>快递单号</label>
											                    <input id="orderno" name="orderno" value="" class="inp w168" type="text" tabindex="3">                                          
											                </li>
											                <li>
											                    <label for="date">起止日期</label>
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
																</script>                       
											                </li>
											                <li class="search-smod">
																<button type="submit" class="btn-login radius-three " tabindex="6" title="搜索">搜&nbsp;索</button>											                
                                                            </li>
											            </ul>
											                                           
				                                </div><!--E .search-mod-->
								<div class="table-border">
			                                	<table class="table-date">
			                                    	<caption>在库包裹列表</caption>
			                                        <thead>
			                                        	<tr>
                                                          	
			                                            <th scope="col" ><input type="checkbox" name="chk_all"  onclick="allchk(this)"></th>
														<th scope="col" style=" width: 130px;">入库时间</th>
														<!--<th scope="col" >编号</th>-->
														<th scope="col" style=" width: 100px;">快递公司</th>
														<!--<th scope="col" >发货公司</th>-->
														<th scope="col" style=" width: 100px;">快递单号</th>
														<!--<th scope="col" >数量</th>-->
														
														<!--<th scope="col" >价值</th>-->
														<th scope="col" style="width: 220px; padding-left:0px;">物品名称</th>
														<th scope="col" style="padding-left:0px;" >重量(g)</th>
														<!--<th scope="col" >长x宽x高 </th>-->
														<th scope="col" style="display:none;">转运方式</th>
														
														
														<th scope="col"  style="text-align:center;">操作</th>
			                                        		
			                                        	</tr>
			                                        </thead>
			                                        <tbody>
			                                        
			                                        <?php $n=1;if(is_array($datas)) foreach($datas AS $package) { ?>
													 
															<tr>
																	<td scope="col">
																<!---<?php if(($package[status]==1 || $package[status]==3) && $package[boxstatus]==0 && $package[srvtype]!=0 ) { ?>
																<?php } ?>-->
																	<input type="checkbox" name="aid[]" value="<?php echo $package['aid'];?>" relweight="<?php echo $package['totalweight'];?>">
																	</td>
																	<td scope="col" style=" width: 130px;">
																	<?php echo date('Y-m-d H:i:s',$package[inhousetime]);?> 
																	<!--<?php echo L("waybill_status".$package[status]);?>-->
																	</td>
																	
																	<!--<td scope="col" ><?php echo $package['aid'];?></td>--->
																	<td scope="col" style=" width: 100px;"><?php echo $package['expressname'];?></td>
																	
																	<!--<td scope="col" ><?php echo $package['expressname'];?></td>-->
																	<td scope="col" style="word-break: break-all;width: 100px;padding: 0;"><?php echo $package['expressno'];?></td>
																	<!--<td scope="col" ><?php echo $package['totalamount'];?></td>-->
																	<td scope="col" style="word-break: break-all;width: 220px;padding: 0;"><?php echo $package['goodsname'];?></td>
																	<td scope="col" ><?php echo $package['totalweight'];?></td>
																	<!--<td scope="col" ><?php echo $package['totalprice'];?></td>-->
																	
																	<!--<td scope="col" ><?php echo $package['bill_long'];?>x<?php echo $package['bill_width'];?>x<?php echo $package['bill_height'];?></td>
																	-->
																	<td scope="col"  style="display:none;"><?php echo str_replace('0','单票直发',str_replace('1','合箱',str_replace('2','分箱',$package[srvtype])));?></td>
																	
																
																	<td scope="col" >
																<button type="button" class="btn-login radius-three fl" tabindex="18" 
																onclick="
																<?php if($package[totalweight]>30000) { ?>alert('超出最大发货重量，请分箱发货');
																return false;
																<?php } else { ?>window.location.href='?m=waybill&c=index&a=editline&aid=<?php echo $package['aid'];?>';
																<?php } ?>">
																单件发货
																</button>
																</td>
																</tr>
																<!--<tr style="background:#fff;">
																<td colspan=20>备注:<?php echo $package['otherremark'];?></td>
																</tr>-->
													<?php $n++;}unset($n); ?>
												
			                                        </tbody>
			                                    </table>
			          </div>
			                                         <br>
									<p >
									<table border=0 ><tr><td>
 分
<select id='split_number' name="split_number" size='1' >
  <option value='0' selected >请选择分箱数量</option>
  <option value='2'>2</option><option value='3'>3</option><option value='4'>4</option><option value='5'>5</option>

</select> 
箱 </td><td>
<button type="button" class="btn-login btn-cancel radius-three fl" tabindex="18" onclick="split_box()">分箱发货</button><button type="button" class="btn-login btn-cancel radius-three fl" tabindex="18" onclick="union_box()">合箱发货</button></td></tr><tr><td colspan=2 >
<br>
<div class="tips">
<span style="line-height:20px;">注意：当前包裹重量为到货重量，发货前还需对包裹进行发货前处理(加固,减重等)，处理之后的重量为发货的确认重量。
<br><b>合箱：请选择直接发货 或者等待所有包裹到齐后选择合箱发货。</b>
<br>分箱：包裹限重30KG，超过30KG仓库自动分箱。
<br>日本邮局对发货最大体积限制，长度1.5m以内，长度+(高度+宽度)x2=3m以内。<b>(购买尿布湿用户:一箱最大6包，超过体积将超限无法发货)</b>
</span>
</div>
</td></tr></table>
									</p>     </form>                                                                
									              <div class="function-page fr">
									               
	<div class="page">
		
	 
		<!--<span class="page-total">共<em>&nbsp;0&nbsp;</em>条记录&nbsp;&nbsp;1/1 页</span>-->
		<?php echo $pages;?>
		</div> <!-- End page -->   
		  
									              </div><!--E .function-page--> 
			                                      <div class="message-none hidden">暂无最新消息</div>
			                                </div><!--E .pcont-date-mod-->                          
			                          </div><!--E #tab-cont-v01-->
							        
			                        </div><!--E #tab-cont-->                      
			                    </div><!--E .bag-mod-->  
                                <!--E 右边内容结束-->
                            </div><!--E .pcont-mod-bd-->
                        </div><!--E .pcont-mod-->
                     </div><!--E .main-wrap-->
			       </div><!--E .col-main-->
			     <?php include template('member', 'left'); ?>
                </div><!--E .grid-s12m0-->
            </div>
            <!--E 内容-->                 
        </article>
		</div><!--id="main"结束->
        <!--E 内容-->
        <!--S 底部-->
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
		var num=0;
		$("input[name='aid[]']:checked").each(function(data){
			if(str==""){str=$(this).val();}else{
				str+=","+$(this).val();
			}
			num++;
		});

		if(str=="" || num!=1){
			alert("请选择需要分箱包裹 或 只限选单个订单分箱!");
			return false;
		}else{
		
		
			$("#mainForm").attr("action","/index.php?m=package&c=index&a=splitbox_pre&hid=<?php echo $this->hid;?>");
			$("#mainForm").submit();
		
		}

	}

	function union_box(){
		
		var str="";
		var number=0;
		var totalweight=0;
		$("input[name='aid[]']:checked").each(function(data){
			if(str==""){str=$(this).val();totalweight=parseFloat($(this).attr('relweight'));}else{
				str+=","+$(this).val();
				totalweight+=parseFloat($(this).attr('relweight'));
			}
			number++;
		});
		if(totalweight>30000){
			alert("大于30KG无法合箱，超出最大发货重量，请重新选择包裹进行合箱");
			return false;
		}
		if(str=="" || number>17 || number==1){
			if(number==1){alert("单个包裹无法合箱，请直接点发货");}else{
			if(number>17){alert("最多支持17箱合箱发货");}else{
			alert("请选择需要合箱包裹");}
			}
			return false;
		}else{
		
		
			$("#mainForm").attr("action","/index.php?m=package&c=index&a=unionbox_pre&hid=<?php echo $this->hid;?>");
			$("#mainForm").submit();
		
		}
	}

   </script>
  </div>
  </div>
  <!--  id="ht_index" 结束--->
  <?php include template('member', 'footer'); ?>