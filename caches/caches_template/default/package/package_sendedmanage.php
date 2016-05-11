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
                            	<h3>待处理件</h3>
								<div class="plink-mod">
                               
                                </div><!--E .plink-mod-->
                            </div><!--E .pcont-mod-hd-->
							<?php $allpackagestatus = $this->getpackagestatus();?>
                            <div class="pcont-mod-bd" style="padding-top:0;">
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
			                                	<table class="table-date" >
			                                    	<caption>待处理件</caption>
			                                        <thead>
			                                        	<tr>
                                                          	
			                                           <!--- <th scope="col" ><input type="checkbox" name="chk_all"  onclick="allchk(this)"></th>--->
														<th scope="col" style=" width: 80px;">&nbsp;</th>
														<th scope="col" style=" width: 80px;">提交时间</th>
														<th scope="col" style=" width: 100px;">快递公司</th>
														<th scope="col" style=" width: 120px;">快递单号</th>
														<!---<th scope="col" >邮单号</th>--->
														<!---<th scope="col" >数量</th>--->
														
														<!---<th scope="col" >价值</th>--->
														<th scope="col" >物品名称</th>
														<th scope="col" >重量(g)</th>
														<!--<th scope="col" >长x宽x高 </th>-->
														<!--<th scope="col" >预估运费(元)</th>-->
														<th scope="col" >状态</th>
														<th scope="col" >操作</th>
			                                        		
			                                        	</tr>
			                                        </thead>
			                                        <tbody>
			                                        
			                                        <?php $n=1;if(is_array($datas)) foreach($datas AS $package) { ?>

														<tr>
															<td colspan="7">
															<font color=blue><?php echo str_replace('2','分箱件',str_replace('1','合箱件',str_replace('0','单件直发件',$package['srvtype'])));?></font>    
															</td>
														</tr>
														
													<?php $p_arrno=explode('+',$package['expressno']);?>
													<?php $p_arrna=explode('+',$package['goodsname']);?>
													<?php $k=1;?>
													<?php $n=1; if(is_array($p_arrno)) foreach($p_arrno AS $key => $expressno) { ?>

														<?php if($package['srvtype']==1) { ?>
															<tr>
																	
																	<td scope="col" style=" width: 80px;">&nbsp;
																	合箱原件<?php echo $k;?>
																	</td>
																	<td scope="col" style=" width: 80px;"><?php echo date('Y-m-d H:i:s',$package[handletime]);?> </td>
																	<td scope="col" style=" width: 100px;"><?php echo $package['expressname'];?></td>
																	<td scope="col" style=" width: 88px; word-wrap: break-word;"><?php echo $expressno;?></td>
																	<td scope="col" style="word-break: break-all;width: 220px;padding: 0;"><?php echo $p_arrna[$key];?></td>
																	<td scope="col" ><?php echo $package['totalweight'];?></td>
																	<td scope="col" >
																	<?php echo L("waybill_status".$package[status]);?>
																	</td>
																	<td scope="col" ><a href="?m=waybill&c=index&a=waybill_detail&bid=<?php echo $package['aid'];?>">查看</a> </td></td>
															</tr>
															
														<?php } elseif ($package['srvtype']==2) { ?>
													
															<tr>
																	<td scope="col" style=" width: 80px;">&nbsp;
																	分箱<?php echo $k;?>号
																	</td>
																	<td scope="col" style=" width: 80px;"><?php echo date('Y-m-d H:i:s',$package[handletime]);?> </td>
																	<td scope="col" style=" width: 100px;"><?php echo $package['expressname'];?></td>
																	<td scope="col" style=" width: 88px; word-wrap: break-word;"><?php echo $expressno;?></td>
																	<td scope="col" style="word-break: break-all;width: 220px;padding: 0;"><?php echo $p_arrna[$key];?></td>
																	<td scope="col" ><?php echo $package['totalweight'];?></td>
																	<td scope="col" >
																	<?php echo L("waybill_status".$package[status]);?>
																	</td>
																	<td scope="col" ><a href="?m=waybill&c=index&a=waybill_detail&bid=<?php echo $package['aid'];?>">查看</a> </td></td>
																</tr>
														<?php } else { ?>
															<tr>
																	<td scope="col" style=" width: 80px;">&nbsp;</td>
																	<td scope="col" style=" width: 80px;"><?php echo date('Y-m-d H:i:s',$package[handletime]);?> </td>
																	<td scope="col" style=" width: 100px;"><?php echo $package['expressname'];?></td>
																	<td scope="col" style=" width: 88px; word-wrap: break-word;"><?php echo $expressno;?></td>
																	<td scope="col" style="word-break: break-all;width: 220px;padding: 0;"><?php echo $p_arrna[$key];?></td>
																	<td scope="col" ><?php echo $package['totalweight'];?></td>
																	<td scope="col" >
																	<?php echo L("waybill_status".$package[status]);?>
																	</td>
																	<td scope="col" ><a href="?m=waybill&c=index&a=waybill_detail&bid=<?php echo $package['aid'];?>">查看</a> </td></td>
																</tr>
															<?php } ?>
														  <?php $k++;?>
														<?php $n++;}unset($n); ?>
																
														<?php if($package['srvtype']==1) { ?>
														<tr>
																	<td scope="col" style=" width: 80px;"><font color=blue>&nbsp;&nbsp;合箱后</font>  </td>
																	<td scope="col" style=" width: 80px;">&nbsp; </td>
																	<td scope="col" style=" width: 100px;">&nbsp;</td>
																	<td scope="col" style=" width: 88px; word-wrap: break-word;">&nbsp;</td>
																	<td scope="col" style="word-break: break-all;width: 220px;padding: 0;"><?php echo $package['goodsname'];?></td>
																	<td scope="col" >&nbsp;</td>
																	<td scope="col" >
																	<?php echo L("waybill_status".$package[status]);?>
																	</td>
																	<td scope="col" ><a href="?m=waybill&c=index&a=waybill_detail&bid=<?php echo $package['aid'];?>">查看</a> </td></td>
															</tr>
														<?php } ?>	
														<tr>
																	<td scope="col" style=" width: 80px;">&nbsp;&nbsp;</td>
																	<td scope="col" style=" width: 80px;">&nbsp; </td>
																	<td scope="col" style=" width: 100px;">&nbsp;</td>
																	<td scope="col" style=" width: 88px; word-wrap: break-word;">&nbsp;</td>
																	<td scope="col" style="word-break: break-all;width: 220px;padding: 0;">&nbsp;</td>
																	<td scope="col" >&nbsp;</td>
																	<td scope="col" >
																	&nbsp;
																	</td>
																	<td scope="col" >&nbsp;</td></td>
															</tr>
													<?php $n++;}unset($n); ?>
			                                        </tbody>
			                                    </table>
			          </div>
			                                         <br>
									   </form>                                                                
									              <div class="function-page fr">
									               
	<div class="page">
		
	 
		<!--<span class="page-total">共<em>&nbsp;0&nbsp;</em>条记录&nbsp;&nbsp;1/1 页</span>-->
		<?php echo $pages;?>
		</div> <!-- End page -->   
		  
									              </div><!--E .function-page--> 
			                                      <div class="message-none hidden">暂无最新消息</div>
									<div class="tips">	
														<span>
														操作提示：仓库工作人员按照您提交的要求正在对您的包裹进行发货前的打包处理，处理完毕后您的运单会转入到”未付款件“中
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
   </script>
</div>
</div>
  <?php include template('member', 'footer'); ?>