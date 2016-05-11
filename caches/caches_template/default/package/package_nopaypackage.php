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
                            	<h3>未付款件</h3>
								<div class="plink-mod">
                               
                                </div><!--E .plink-mod-->
                            </div><!--E .pcont-mod-hd-->
							
							<?php $allpackagestatus = $this->getpackagestatus();?>
                             <div class="pcont-mod-bd" style="padding-top:0">
							<?php
							$curruser=$this->get_current_userinfo();
							$result=$this->db->query("select sum(payedfee) as nopay from t_waybill where status=8 and userid='".$this->_userid."' ");
							$dataarray=$this->db->fetch_array($result);
							$nopayamount=0;
							foreach($dataarray as $r){
								$nopayamount+=$r[nopay];
							}

							?>
							<div class="balance-info proInfo-cont" style="padding-left: 20px;">
                                	您的账户余额<strong><?php echo $this->current_user[amount];?><?php echo L('unit_yuan');?></strong> 
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;待付款余额<strong><?php echo $nopayamount;?><?php echo L('unit_yuan');?></strong> 
                                    <a href="/index.php?m=pay&c=deposit&a=pay" style="top:0px;text-decoration:none;" class="p-btn radius-five" title="我要充值" ><font color="#ffffff">我要充值</font></a>
                                </div>
                            	<!--S 右边内容开始-->
			                	<div class="bag-mod">
							    
							        <div id="tab-cont">
							          <div id="tab-cont-v01"> 
			                           		<div class="pcont-date-mod" style="padding-top:0;">
				                                <form action="" method="post" name="mainForm" id="mainForm">
												<div class="search-mod" style="padding-bottom:12px;width:98%; border:none;">
											
                           					 <input type="hidden" name="dosubmit" id="dosubmit" value="1"/>
											            <ul class="pform-list clearfix" style="padding-top:2px;">
											                  <li>
											                    <label for="num">快递单号</label>
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
			                                    	<caption>未付款件</caption>
			                                        <thead>
			                                        	<tr>
                                                          	
			                                            <!--<th scope="col" ><input type="checkbox" name="chk_all"  onclick="allchk(this)"></th>-->
														<!--<th scope="col" >编号</th>-->
														<th scope="col" style=" width: 130px;">处理时间</th>
														<th scope="col" style=" width: 80px;">快递公司</th>
														<th scope="col" style=" width: 100px; ">快递单号</th>
														<th scope="col">物品名称</th>
														<!--<th scope="col" >长x宽x高 </th>-->
														<th scope="col" >收件人</th>
														<th scope="col" >发货线路</th>
														<th scope="col" >重量(g)</th>
														<th scope="col" >运费(<?php echo L('unit_yuan');?>)</th>
														<th scope="col" >操作</th>
			                                        		
			                                        	</tr>
			                                        </thead>
			                                        <tbody>
			                                        
			                                        <?php $n=1;if(is_array($datas)) foreach($datas AS $package) { ?>
													
															<tr>
																	<!--<td scope="col">
																	<input type="checkbox" name="aid[]" value="<?php echo $package['aid'];?>">
																	</td>-->
																	<td scope="col" style=" width: 130px;"><?php echo date('Y-m-d H:i:s',$package[comptime]);?> </td>
																	<!---<td scope="col" ><?php echo $package['aid'];?></td>-->
																	<td scope="col" ><?php echo $package['expressname'];?></td>
																	<td scope="col" style=" "><?php echo $package['expressno'];?>
																	</td>
																	<td scope="col" style="word-break: break-all;width: 180px;padding: 0;"><?php echo $package['goodsname'];?></td>
																	<!--<td scope="col" ><?php echo $package['bill_long'];?>x<?php echo $package['bill_width'];?>x<?php echo $package['bill_height'];?></td>
																	-->
																	<td scope="col" ><?php echo $package['truename'];?></td>
																	<td scope="col" ><?php echo $package['shippingname'];?></td>
																	<td scope="col" ><?php echo $package['totalweight'];?></td>
																	<td scope="col" ><?php echo $package['payedfee'];?></td>
																	
																
																	
																	<!--<a href="?m=waybill&c=index&a=waybill_detail&bid=<?php echo $package['aid'];?>">查看</a>&nbsp;&nbsp;
																	<a href="?m=waybill&c=index&a=pay&oid=<?php echo $package['aid'];?>" onclick="return confirm('您确定要扣款?');">扣款</a>&nbsp;&nbsp;
																	<a href="?m=waybill&c=index&a=editline&aid=<?php echo $package['aid'];?>" >更换渠道</a>
																	-->

																	<td scope="col" ><a href="?m=waybill&c=index&a=pay&oid=<?php echo $package['aid'];?>" onclick="return confirm('确认无误并支付?');">扣款</a>
																	<a href="/index.php?m=waybill&c=index&a=editline&aid=<?php echo $package['aid'];?>">更换线路</a></td>
																</tr>
																<div>
																	<?php echo $package['otherremark'];?>    
																</div>	
																	
																

																
													<?php $n++;}unset($n); ?>
			                                        </tbody>
			                                    </table>
</div>
									   </form>                                                                
									              <div class="function-page fr">
									               
	<div class="page">
		
	 
		<!--<span class="page-total">共<em>&nbsp;0&nbsp;</em>条记录&nbsp;&nbsp;1/1 页</span>-->
		<?php echo $pages;?>
		</div> <!-- End page -->   
		  
									              </div><!--E .function-page--> 
			                                      <div class="message-none hidden">暂无最新消息</div>
									<div class="tips">	<br>
														<span>
														操作提示：包裹已经打包处理完毕，现在您可以进行扣款支付运费(当前您还可以更换发货线路以及收件地址)，扣款完成后运单将转入到 已付款件;
												
														</span>
														
									</div>
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
        <!--E 内容-->
        <!--S 底部-->
   <script type="text/javascript">
   function allchk(obj){
     $("input[name='aid[]']").attr("checked",obj.checked);
   }

   function batch_pay(){
	 var str="";
	 $("input[name='aid[]']:checked").each(function(){
		if(str=="")
		{str=$(this).val();}else{
			str+=","+$(this).val();
		}
	 });

	 if(str==""){
		alert("请选择所需要扣款的订单");
		return false;
	 }else{
		//$("#mainForm").attr("action","/index.php?m=waybill&c=index&a=waybill_batch_pay&hid=<?php echo $this->hid;?>");
		$("#mainForm").attr("action","/index.php?m=waybill&c=index&a=waybill_batch_pay_pre&hid=<?php echo $this->hid;?>");
		$("#mainForm").submit();
	 }
   }

   function batch_pay2(id){
	 var str="";
	 $("input[name='aid[]']").each(function(){
		if(str=="")
		{str=$(this).val();}else{
			str+=","+$(this).val();
		}
	 });

	
		$("#mainForm").attr("action","/index.php?m=waybill&c=index&a=waybill_batch_pay&hid=<?php echo $this->hid;?>");
		
		$("#mainForm").submit();
   }
   </script>
</div>
</div>
  <?php include template('member', 'footer'); ?>