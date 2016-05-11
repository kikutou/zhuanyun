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
        	<!--S 面包屑-->
            <div class="layout crumbs">
                <em>当前位置：</em>
                <a href="/index.php?m=member&siteid=1" title="我的账户">我的账户</a>
				
                <span>&gt;</span>
                <span class="curmbs-active">消费记录</span>
            </div>
            <!--E 面包屑-->
         	<!--S 内容-->
        	<div class="layout pro-mod">
			<?php include template('member', 'top'); ?>
            	<div class="layout grid-s12m0">
			       <div class="col-main">
			       	 <div class="main-wrap">
						<div class="pcont-mod">
                        	<div class="pcont-mod-hd">
                            	<h3>消费记录</h3>
                            </div><!--E .pcont-mod-hd-->
                            <div class="pcont-mod-bd">
                              
                                <div class="search-mod">
							        <form action="/index.php?m=pay&c=spend_list&a=init" method="get" id="mainForm" name="mainForm">
							         <input type="hidden" value="pay" name="m">
									<input type="hidden" value="spend_list" name="c">
									<input type="hidden" value="init" name="a">
									<input type="hidden" value="1" name="dosubmit" />
							            <ul class="pform-list clearfix">
							               
							                <li>
							                    <label for="date">起止日期</label>
			
			
			<input type="text" name="starttime" id="starttime" value="<?php if($starttime) { ?><?php echo date('Y-m-d',$starttime);?><?php } ?>" size="10" class="date inp" readonly>&nbsp;<script type="text/javascript">
			Calendar.setup({
			weekNumbers: true,
		    inputField : "starttime",
		    trigger    : "starttime",
		    dateFormat: "%Y-%m-%d",
		    showTime: false,
		    minuteStep: 1,
		    onSelect   : function() {this.hide();}
			});
        </script>&nbsp;-&nbsp; <input type="text" name="endtime" id="endtime" value="<?php if($endtime) { ?><?php echo date('Y-m-d',$endtime);?><?php } ?>" size="10" class="date inp" readonly>&nbsp;<script type="text/javascript">
			Calendar.setup({
			weekNumbers: true,
		    inputField : "endtime",
		    trigger    : "endtime",
		    dateFormat: "%Y-%m-%d",
		    showTime: false,
		    minuteStep: 1,
		    onSelect   : function() {this.hide();}
			});
        </script>                        
							                </li>
							                <li>
							                   
												<select name="type" class="inp inp-select">
												<option value="">付款类型</option>
												<option value="1" <?php if($type==1) { ?>selected<?php } ?>>金钱</option>
												<option value="2" <?php if($type==2) { ?>selected<?php } ?>>积分</option>
												</select>
							                                                               
							                </li>
                                            <li class="search-smod">
                                                <button type="submit" class="btn-login radius-three fr" tabindex="6" title="搜索">搜&nbsp;索</button>											                
                                            </li>
							            </ul>
							        </form>                                     
                                </div><!--E .search-mod-->
                                <span class="blank"></span>
                           		<div class="pcont-date-mod">
                                	<table class="table-date">
                                    	<caption></caption>
                                        <thead>
                                        	<tr>
                                        		<th scope="col" width="20%">运单号</th>
												<th scope="col" width="21%">付款时间</th>
                                                <th scope="col" width="12%">付款类型</th>
                                                <th scope="col" width="10%">金额/数量</th>
												<th scope="col" width="15%">备注</th>
                                         
                                        	</tr>
                                        </thead>
                                        <tbody>
                                          <?php $n=1;if(is_array($list)) foreach($list AS $info) { ?>  
                                        	<tr>
                                        		<td><?php echo $info['msg'];?></td>
												<td><?php echo format::date($info['creat_at'], 1);?></td>
                                                <td><?php if($info[type]==1) { ?>金钱<?php } elseif ($info[type]==2) { ?>积分<?php } ?></td>
                                                <td><?php echo $info['value'];?></td>
												<td><?php echo $info['msg'];?></td>
                                               
                                        	</tr>
                                        	<?php $n++;}unset($n); ?>
                                        	
                                        </tbody>
                                    </table>
						              <div class="function-page fr">
						                 
 
 
 

 
 
 
	<div class="page" id="pages">
	
	
 <?php echo $pages;?>
		
		</div> <!-- End page -->   
		  
						              </div><!--E .function-page-->                                     
                                </div><!--E .pcont-date-mod-->
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
 </div>
</div> 
 
  <?php include template('member', 'footer'); ?>