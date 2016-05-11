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
                            	<h3>未入库件</h3>
								<div class="plink-mod">
                               
                                </div><!--E .plink-mod-->
                            </div><!--E .pcont-mod-hd-->
							<?php $allpackagestatus = $this->getpackagestatus();?>
                            <div class="pcont-mod-bd" style="padding-top:0">
                            	<!--S 右边内容开始-->
			                	<div class="bag-mod">
							    
							        <div id="tab-cont">
							          <div id="tab-cont-v01"> 
			                           		<div class="pcont-date-mod" style="padding-top:0;">
				                                <form action="" method="post" name="mainForm" id="mainForm">
												<div class="search-mod">
											
                           					 <input type="hidden" name="dosubmit" id="dosubmit" value="1"/>
											            <ul class="pform-list clearfix" style="padding-top:2px;">
											               <li><label>快递单号</label>
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
			                                    	<caption>未入库包裹列表</caption>
			                                        <thead>
			                                        	<tr>
                                                          	
			                                            <th scope="col" ><input type="checkbox" name="chk_all"  onclick="allchk(this)"></th>
														<!--<th scope="col" >编号</th>-->
														<th scope="col" style=" width: 130px;">预报时间</th>
														<th scope="col" style=" width: 120px;">快递公司</th>
														<th scope="col" style=" width: 100px;">快递单号</th>
													
														<!--<th scope="col" >数量</th>-->
														<!--<th scope="col" >重量</th>-->
														<!--<th scope="col" >价值</th>-->
														<th scope="col" >物品名称</th>
														<!--<th scope="col" >长x宽x高 </th>-->
														<th scope="col" >状态</th>
														
														<th scope="col" >操作</th>
			                                        		
			                                        	</tr>
			                                        </thead>
			                                        <tbody>
			                                        
			                                        <?php $n=1;if(is_array($datas)) foreach($datas AS $package) { ?>
													
															<tr>
																	<td scope="col">
																<?php if(($package[status]==1  || $package[status]==2) && !$this->getexpressno_exists($package[aid],$package[retruncode])  ) { ?>
																	<input type="checkbox" name="aid[]" value="<?php echo $package['aid'];?>">
																<?php } ?>
																	</td>
																	<td scope="col" style=" width: 130px;"><?php echo date('y-m-d H:i:s',$package[addtime]);?></td>
																	<!--<td scope="col" ><?php echo $package['aid'];?></td>--->
																	<td scope="col" style=" width: 120px;"><?php echo $package['expressname'];?></td>
																	<td scope="col" style=" width: 100px;padding-right: 5px;"><?php echo $package['expressno'];?></td>
																	
																	<!--<td scope="col" ><?php echo $package['totalamount'];?></td>--->
																	<!--<td scope="col" ><?php echo $package['totalweight'];?></td>--->
																	<!--<td scope="col" ><?php echo $package['totalprice'];?></td>--->
																	<td scope="col" style="word-break: break-all;width: 260px;padding: 0;"><?php echo $package['goodsname'];?></td>
																	<!--<td scope="col" ><?php echo $package['bill_long'];?>x<?php echo $package['bill_width'];?>x<?php echo $package['bill_height'];?></td>
																	-->
																
																	<td scope="col" >
																	<?php echo L("waybill_status".$package[status]);?>
																	</td>
																	<td scope="col" ><a href="/index.php?m=waybill&c=index&a=waybill_detail&bid=<?php echo $package['aid'];?>">查看</a><?php if(($package[status]==1  || $package[status]==2) && !$this->getexpressno_exists($package[aid])) { ?>
																	<a href="/index.php?m=waybill&c=index&a=edit&aid=<?php echo $package['aid'];?>">修改</a><?php } ?></td>
																</tr>
													<?php $n++;}unset($n); ?>
													<tr>
			                                        </tbody>
			                                    </table>
										</div>
			                                         <br>
									<p>
									<button type="submit" onclick="document.mainForm.action='?m=package&c=index&a=delete';return confirm('确定要删除所选记录？')" class="btn-login radius-three fl" tabindex="18" title="删除所选">删除所选</button>&nbsp;&nbsp;
									</p>     </form>                                                                
									              <div class="function-page fr">
									               
	<div class="page">
		
	 
		<!--<span class="page-total">共<em>&nbsp;0&nbsp;</em>条记录&nbsp;&nbsp;1/1 页</span>-->
		<?php echo $pages;?>
		</div> <!-- End page -->  


		
		  
									              </div><!--E .function-page--> 
			                                      <div class="message-none hidden">暂无最新消息</div>
									<div class="tips">	
														<span>
														操作提示：准确填写预报信息,能加快仓库对您包裹的处理速度;包裹未入库前，当前状态下您可对包裹所有信息进行修改。
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
		</div><!--id="main"结束->
        <!--E 内容-->
        <!--S 底部-->
   <script type="text/javascript">
   function allchk(obj){
     $("input[name='aid[]']").attr("checked",obj.checked);
   }
   </script>
 </div><!--  id="ht_index" 结束--->
  <?php include template('member', 'footer'); ?>