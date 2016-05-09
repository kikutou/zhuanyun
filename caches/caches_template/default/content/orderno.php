<?php defined('IN_PHPCMS') or exit('No permission resources.'); ?><?php include template("content","header"); ?>   
 
        <!--S 内容-->
 		<article class="content">
        	<!--S 面包屑-->
            <div class="layout crumbs">
                <em>当前位置：</em><a href="<?php echo siteurl($siteid);?>">首页</a><span> &gt; </span>
                <span class="curmbs-active">快件查询</span>
            </div>
            <!--E 面包屑-->                    	
        	<!--S 内容模块-->
        	<!--S 内容模块-->
        	<div class="content-mod">
            	<div class="layout sub-layout radius-five grid-s15m0">
			      <div class="col-main">
			       	 <div class="main-wrap" style="margin-left:0;">
					  	<div class="mod c-mod">
	                    	<div class="mod-bd s-mod-bd">
								<div class="s-search-mod">
				                  	<h2 class="home-h2"><label for="desc">日本邮政快递查询</label></h2>
				                    <form action="api.php" name="mainForm" id="mainForm" method="get">
									<input type="hidden" name="op" value="orderno"/>
				                    <div class="textarea-mod">
				                    	<textarea class="inp-tarea" id="ord" name="ord" style="ime-mode:disabled;" value="请输入日本邮政快递单号" onFocus="this.value=''" onKeyUp="this.value=this.value.replace(/[^0-9a-z]/gi,'')" ><?php echo $ord;?></textarea>
				                    </div>
				                     <button type="button" onclick="submitF();" class="h-btn btn-hSubmit" title="查询日本邮政快递"></button>
				                    </form>                                
                                </div><!--E .s-search-mod-->
                               
                                <div class="s-search-table" style="display:none;">

									<?php
									$cdb = pc_base::load_model('enum_model');
									$handle_datas  = $cdb->select("groupid=52 ","*",1000,"listorder asc");	
									
									?>

                                  <?php $n=1;if(is_array($ord_array)) foreach($ord_array AS $v) { ?>
								  <?php
								  $v=trim($v);
								  $datas = $wdb->select(" siteid='1' and waybillid='$v' ",'*',1000,'addtime ASC');
								  

								  $_rs = $_wdb->get_one(array('waybillid'=>$v));
								  ?>
                                	<div class="table-cont">
                                    	<div class="table-cont-hd"><?php echo $v;?>--走件流程</div>
                                        <table>
                                        	<thead>  
                                            	<tr>
                                            		<th width="20%">运单编号</th>
													<th width="29%">操作时间</th>
													<th width="20%">处理地点</th>
                                            		
                                            		<th width="48%">快件状态</th>
                                            	</tr>
                                              
                                            </thead>
                                            <tbody>
											<?php if($datas) { ?>

												<?php if($sinfo[wbinfocustom]==0) { ?>
                                            	<?php $n=1;if(is_array($datas)) foreach($datas AS $row) { ?>
                                             	<tr>
                                            		<td ><?php echo $v;?></td>
													<td ><?php echo date('Y-m-d H:i:s',$row[addtime]);?></td>
													<td ><?php echo $row['placename'];?></td>
                                            		
                                            		<td ><?php echo $row['remark'];?></td>
                                            	</tr>
                                             	<?php $n++;}unset($n); ?>
												<?php } else { ?>
												<?php $n=1;if(is_array($handle_datas)) foreach($handle_datas AS $hbill) { ?>
												<?php $n=1;if(is_array($datas)) foreach($datas AS $row) { ?>
												<?php if($row[status]==$hbill[value]) { ?>
                                             	<tr>
                                            		<td ><?php echo $v;?></td>
													<td ><?php echo date('Y-m-d H:i:s',$row[addtime]);?></td>
													<td ><?php echo $hbill['title'];?></td>
                                            		
                                            		<td ><?php echo $hbill['remark'];?></td>
                                            	</tr>
												<?php } ?>
                                             	<?php $n++;}unset($n); ?>

												<?php $n++;}unset($n); ?>
												<?php } ?>

                                               <?php } else { ?>
											   <tr>
                                             	 <td colspan="4" style="line-height: 50px;">查询暂无订单数据！</td>
                                             	</tr>
											   <?php } ?>			
                                            </tbody>
                                        </table>

										

                                    </div><!--E .table-cont-->
									
									<?php $n++;}unset($n); ?>
                                    
                                </div><!--E .s-search-table-->
								<div style="padding-top:30px; padding-left:15px;">
									<table width="100%">
                                        	<thead> 
											<label  style="font-size:18px; font-weight:500; color:#667503">其它快递公司快递查询: </label>
											
                                            	<tr>
                                            		<th width="10%" style="font-size:16px; font-weight:500;"><a href="http://www.sagawa-exp.co.jp">sagawa</a></th>
													<th width="10%" style="font-size:16px; font-weight:500;"><a href="http://www.kuronekoyamato.co.jp">yamato</a></th>
													<th width="10%" style="font-size:16px; font-weight:500;"><a href="http://www.nittsu.co.jp">日本通運</a></th>
                                            		<th width="10%" style="font-size:16px; font-weight:500;"><a href="http://www.post.japanpost.jp">郵便事業</a></th>
                                            		<th width="10%" style="font-size:16px; font-weight:500;"><a href="http://www.seino.co.jp">西濃運輸</a></th>
													<th width="10%" style="font-size:16px; font-weight:500;"><a href="http://www.fukutsu.co.jp">福山通運</a></th>
													<th width="40%" style="font-size:16px; font-weight:500;"></th>
                                            	</tr>
                                              
                                            </thead>
									</table>

								</div>
                                 
	                        </div><!--E .mod-bd-->
	                    </div><!--E .mod-->
                     </div>
			      </div>
				  <!--E .col-main-->
			     
<!--E .sub-nav-mod-->
                  </div><!--E .col-sub-->
                </div><!--E .grid-s19m0-->           
            </div>
            <!--E 内容模块-->     
        </article>
        <!--E 内容-->
<?php include template("content","footer"); ?>