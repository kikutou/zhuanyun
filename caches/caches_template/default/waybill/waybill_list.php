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
        	<div class="layout pro-mod"  style="border-top:none;">  
			<?php include template('member', 'top'); ?>
            	<div class="layout grid-s12m0">
			       <div class="col-main">
			       	 <div class="main-wrap">
						<div class="pcont-mod">
                        	<div class="pcont-mod-hd">
                            	<h3><?php if($status==7) { ?>已付款件<?php } elseif ($status==14) { ?>已发货件<?php } elseif ($status==16) { ?>已签收件<?php } ?></h3>
								<div class="plink-mod">
                             
                                </div><!--E .plink-mod-->
                            </div><!--E .pcont-mod-hd-->
                            <div class="pcont-mod-bd">
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
                                	您的账户余额<strong><?php echo $curruser['amount'];?><?php echo L('unit_yuan');?></strong> 
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;待付款余额<strong><?php echo $nopayamount;?><?php echo L('unit_yuan');?></strong> 
                                    <a href="/index.php?m=pay&c=deposit&a=pay" style="top:0px;text-decoration:none;" class="p-btn radius-five" title="我要充值" ><font color="#ffffff">我要充值</font></a>
                                </div><!--E .balance-info-->

                            	<!--S 右边内容开始-->
			              <div class="bag-mod">
							  <!--<div class="tab-mod">
			                     <ul class="clearfix">	
								 <li <?php if($status==0) { ?>class="current"<?php } ?>><a href="/index.php?m=waybill&c=index&a=init&hid=<?php echo $this->hid?>">所有订单</a></li>								  
									 <li <?php if($status==1) { ?>class="current"<?php } ?>><a href="/index.php?m=waybill&c=index&a=init&hid=<?php echo $this->hid?>&status=1">未入库</a></li>
									 <li <?php if($status==3) { ?>class="current"<?php } ?>><a href="/index.php?m=waybill&c=index&a=init&hid=<?php echo $this->hid?>&status=3">已入库</a></li>
									 <li <?php if($status==21) { ?>class="current"<?php } ?>><a href="/index.php?m=waybill&c=index&a=init&hid=<?php echo $this->hid?>&status=21">待处理</a></li>
			                         <li <?php if($status==8) { ?>class="current"<?php } ?>><a href="/index.php?m=waybill&c=index&a=init&hid=<?php echo $this->hid?>&status=8">待付款</a></li>
			                         <li <?php if($status==5) { ?>class="current"<?php } ?>><a href="/index.php?m=waybill&c=index&a=init&hid=<?php echo $this->hid?>&status=5">问题件</a></li>
									 <li <?php if($status==16) { ?>class="current"<?php } ?>><a href="/index.php?m=waybill&c=index&a=init&hid=<?php echo $this->hid?>&status=16">已签收</a></li>
								
										<li <?php if($status==7) { ?>class="current"<?php } ?>><a href="/index.php?m=waybill&c=index&a=init&hid=<?php echo $this->hid?>&status=7">已付款运单</a></li>
										<li <?php if($status==14) { ?>class="current"<?php } ?>><a href="/index.php?m=waybill&c=index&a=init&hid=<?php echo $this->hid?>&status=14">已发货运单</a></li>
									
			                      </ul>
							  </div>--><!--E .tab-mod--> 
							        <div id="tab-cont">
							          <div id="tab-cont-v01"> 
			                           		<div class="pcont-date-mod" style="padding:0;">
				                               
												 <form action="" method="post" name="mainForm" id="mainForm">
												<div class="search-mod" style="padding-bottom:12px;width:98%; border:none;">
											<input type="hidden" name="status" value="<?php echo $_GET['status'];?>"/>
                           					 <input type="hidden" name="dosubmit" id="dosubmit" value="1"/>
											           <ul class="pform-list clearfix">
							              
							                
							                   <li>
							                    <label for="num">快递单号</label>
							                     <input id="orderno" name="orderno" value="" class="inp w168" type="text" tabindex="3">                                       
											</li>
							                <li>
							                    <label for="date">起止日期</label>
							                    <input type="text" name="starttime" id="start_addtime" value="" size="10" class="date inp" readonly>&nbsp;<script type="text/javascript">
			Calendar.setup({
			weekNumbers: true,
		    inputField : "start_addtime",
		    trigger    : "start_addtime",
		    dateFormat: "%Y-%m-%d",
		    showTime: false,
		    minuteStep: 1,
		    onSelect   : function() {this.hide();}
			});
        </script>&nbsp;-&nbsp; <input type="text" name="endtime" id="end_addtime" value="" size="10" class="date inp" readonly>&nbsp;<script type="text/javascript">
			Calendar.setup({
			weekNumbers: true,
		    inputField : "end_addtime",
		    trigger    : "end_addtime",
		    dateFormat: "%Y-%m-%d",
		    showTime: false,
		    minuteStep: 1,
		    onSelect   : function() {this.hide();}
			});
        </script>
                              
							                </li>
							               
                                            <li class="search-smod">
                                                <button type="submit" class="btn-login radius-three fr" tabindex="6" title="搜索">搜&nbsp;索</button>											                
                                            </li>
							            </ul>
											                                          
				                                </div><!--E .search-mod-->
												<div class="table-border">
			                                	<table class="table-date">
			                                    	<caption>我的运单</caption>
			                                        <thead>
			                                        	<tr>
														<th <?php if($status==14) { ?>style="display:none;"<?php } ?> scope="col" style=" width: 120px;">付款时间</th>
														<th <?php if($status==7) { ?>style="display:none;"<?php } ?> scope="col" style=" width: 120px;">发货时间</th>
                                                        <th scope="col" style=" width: 110px; display:none;">快递单号</th>
														<th <?php if($status==7) { ?>style="display:none;"<?php } ?> scope="col" >国际运单号</th>
														<th scope="col" >收货人</th>
														<th scope="col" >国家</th>
														<th scope="col" >运费(<?php echo L('unit_yuan');?>)</th>
														<th scope="col" >发货线路</th>
														<th scope="col" >状态</th>
														<th scope="col" style="text-align: center;" >操作</th>
			                                        		
			                                        	</tr>
			                                        </thead>
			                                        <tbody>
			                                        <?php
													$pdb = pc_base::load_model('package_model');
													?>
			                                        <?php $n=1;if(is_array($datas)) foreach($datas AS $waybill) { ?>
													<?php $takeperson="";?>
													<?php $takecity="";?>

													<?php $addr = explode('|',$waybill['takeaddressname']);?>
													<?php $takeperson=$addr[0];?>
													<?php $takecity=$addr[4];?>
															<tr>
																	<td <?php if($status==14) { ?>style="display:none;"<?php } ?> scope="col" style=" width: 120px;">
																	<?php if($status==7) { ?>
																	<?php echo date('Y-m-d H:i:s',$waybill[paidtime]);?>
																	<?php } else { ?>
																	<?php echo date('Y-m-d H:i:s',$waybill[sendtime]);?>
																	<?php } ?>
																	</td>
																	<td <?php if($status==7) { ?>style="display:none;"<?php } ?> scope="col" style=" width: 120px;"><?php echo date('Y-m-d H:i:s',$waybill[sendtime]);?></td>
																	
																	<td scope="col" style=" width: 110px; display:none;"><?php echo $waybill['waybillid'];?></td>
																	<td <?php if($status==7) { ?>style="display:none;"<?php } ?> scope="col" style=" width: 100px;"><?php echo $waybill['expressnumber'];?></td>
																	<td scope="col" ><?php echo $waybill['truename'];?></td>
																	<td scope="col" ><?php echo $waybill['takename'];?></td>
																	<td scope="col" ><?php echo $waybill['payedfee'];?></td>
																	<td scope="col" ><?php echo $waybill['shippingname'];?></td>
																	

																	
																	<td scope="col" <?php if($waybill[status]==6) { ?> style="color:red"<?php } ?>><?php echo $this->getonestatus($waybill[status],$waybill[placeid])?></td>
																	<td scope="col" style="text-align:center;" >
																	
																	<?php if($waybill[status]==16) { ?>
																	<a href="index.php?m=member&c=content&a=publish&siteid=1&catid=25" style="color:blue">晒单</a> |
																	<?php } ?>


																	<!--<?php if($waybill[status]==3 || $waybill[status]==4 || $waybill[status]==8) { ?>
																	<a href="?m=waybill&c=index&a=editline&aid=<?php echo $waybill['aid'];?>" style="color:blue">提交发货</a> 
																	<?php } ?>
																	</p>-->
																	<!--<?php if($waybill[status]==8) { ?>
																	<a style="color:green" href="?m=waybill&c=index&a=pay&oid=<?php echo $waybill['aid'];?>" onclick="return confirm('您确定要扣款?');">扣款</a>
																	 | <?php } ?>-->
																	
																	<a href="/index.php?m=waybill&c=index&a=waybill_detail&bid=<?php echo $waybill['aid'];?>" >查看详细</a>
																	
																	<!--<?php if($waybill[status]==1) { ?>
																	| <a href="?m=waybill&c=index&a=edit&aid=<?php echo $waybill['aid'];?>">编辑</a>
																	<?php } ?>-->
																	

																	<?php if(trim($waybill[expressnumber])!="" ||  $waybill[status]==14) { ?>| <a href="<?php echo str_replace("#",trim($waybill[expressnumber]),trim($waybill[expressurl]));?>" target=_blank>快递跟踪</a>
																	<?php } ?>
																	
																	 
																	<?php if($waybill[status]==14) { ?>
																	<br/> 
																	| <a href="/index.php?m=waybill&c=index&a=waybill_finish&bid=<?php echo $waybill['aid'];?>" style="color:blue">签收</a>
																	<?php } ?>

																	<!--<?php if($waybill[status]==5) { ?>
																	| <a href="/index.php?m=waybill&c=index&a=waybill_returned_request&bid=<?php echo $waybill['aid'];?>" style="color:#ff6600">申请退货</a>
																	<?php } ?>-->

																	<!--<?php if($waybill[status]==1 ) { ?>
																	| <a href="/index.php?m=waybill&c=index&a=waybill_cancel&bid=<?php echo $waybill['aid'];?>" style="color:red" onclick="return confirm('确定要取消此订单？');">取消</a>
																	<?php } ?>-->


</td>
																</tr>

																<!--<tr style="display:none">
																<td >&nbsp;</td>
																<td >&nbsp;</td>
																<td colspan="6">快递单号：<font style="color:#f40"><?php echo $waybill['expressnumber'];?></font> &nbsp;&nbsp; &nbsp; 备注: <?php echo $waybill['remark'];?>/<?php echo $waybill['otherremark'];?></td>
																</tr>-->
													<?php $n++;}unset($n); ?>
			                                        </tbody>
			                                    </table>
												</div>
			          <br>
									<!--<p>
									<button type="submit" onclick="document.mainForm.action='?m=waybill&c=index&a=delete';return confirm('确定要删除所选记录？')" class="btn-login btn-cancel radius-three fl" tabindex="18" title="删除所选">删除所选</button>
									</p>
									-->
																																							 </form>  
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
        <!--E 内容-->
        <!--S 底部-->
   <script type="text/javascript">
   function allchk(obj){
     $("input[name='aid[]']").attr("checked",obj.checked);
   }
   </script>
 </div>
 </div>
  <?php include template('member', 'footer'); ?>