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
                <span class="curmbs-active">充值记录</span>
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
                            	<h3>充值记录</h3>
                            </div><!--E .pcont-mod-hd-->
                            <div class="pcont-mod-bd">
                                <div class="balance-info proInfo-cont" style="padding-left: 20px;">
                                	当前账户可用余额<strong><?php echo $memberinfo['amount'];?><?php echo L('unit_yuan');?></strong> 
                                    <a href="/index.php?m=pay&c=deposit&a=pay" style="top:0px;text-decoration:none;" class="p-btn radius-five" title="我要充值"><font color="#ffffff">我要充值 &gt;&gt;</font></a>
                                </div><!--E .balance-info-->
                                <div class="search-mod">
							        <form action="<?php echo APP_PATH;?>index.php" method="get" id="mainForm" name="mainForm">
							          <input type="hidden" value="pay" name="m">
										<input type="hidden" value="deposit" name="c">
										<input type="hidden" value="init" name="a">

							            <ul class="pform-list clearfix">
							                <li>
							                    <label for="num">流水号</label>
							                    <input id="num" name="trade_sn" value="<?php echo $trade_sn;?>" class="inp w88" type="text" tabindex="1">                                          
							                </li>
							                <li>
							                    <label for="date">起止日期</label>
			
			
			<input type="text" name="start_addtime" id="start_addtime" value="<?php echo $start_addtime;?>" size="10" class="date inp" readonly>&nbsp;<script type="text/javascript">
			Calendar.setup({
			weekNumbers: true,
		    inputField : "start_addtime",
		    trigger    : "start_addtime",
		    dateFormat: "%Y-%m-%d",
		    showTime: false,
		    minuteStep: 1,
		    onSelect   : function() {this.hide();}
			});
        </script>&nbsp;-&nbsp; <input type="text" name="end_addtime" id="end_addtime" value="<?php echo $end_addtime;?>" size="10" class="date inp" readonly>&nbsp;<script type="text/javascript">
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
							                    <!--<input id="endDate" name="endDate" value="" readonly="readonly" class="Wdate inp w88" type="text" onFocus="WdatePicker({dateFmt:'yyyy-MM-dd',minDate:'#F{$dp.$D(\'beginDate\')}'})" tabindex="3">-->                          
							                </li>
							                <li>
							                    <!--<label for="cat">类型</label>
							                    <select  name="finance.type" id="cat" class="inp inp-select" tabindex="3" rel="select"><option value="">-所有-</option><option value="1" >消费</option><option value="2" >扣款</option><option value="3" >充值 </option><option value="4" >提现</option><option value="5" >加款</option><option value="6" >退款</option><option value="7" >返利</option><option value="8" >购卡赠送</option><option value="9" >冲正</option></select>-->
												<?php echo str_replace('<select','<select class="inp inp-select"',form::select($trade_status,$status,'name="status"', L('all_status')));?>
							                                                               
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
                                    	<caption>收货地址列表</caption>
                                        <thead>
                                        	<tr>
                                        		<th scope="col" width="20%">流水号</th>
												<th scope="col" width="21%">充值时间</th>
                                                
                                                <th scope="col" width="12%">充值金额</th>
                                                <th scope="col" width="10%">支付方式</th>
                                                <th scope="col" width="20%">状态</th>
                                                <th scope="col" width="15%">备注</th>
                                        	</tr>
                                        </thead>
                                        <tbody>
                                           <?php $n=1;if(is_array($infos)) foreach($infos AS $info) { ?> 
                                        	<tr>
                                        		<td><?php echo $info['trade_sn'];?></td>
												<td><?php echo date('Y-m-d H:i:s',$info['addtime']);?></td>
                                                
                                                <td><span class="color-red"><?php echo $info['money'];?> <?php echo $info['type']==1 ? '元':'积分'?></span></td>
                                                <td><?php echo $info['payment'];?></td>
                                               
                                                <td><?php echo L($info['status']);?></td>
                                                <td><?php echo $info['usernote'];?></td>
                                        	</tr>
                                        	<?php $n++;}unset($n); ?>
                                        	
                                        </tbody>
                                    </table>
						              <div class="function-page fr">
						                 
 
 
 

 
 
 
	<div class="page" id="pages">
	
	
 <?php echo $pages;?>
		<!--
		<strong class="page-cur">1</strong>	
		<span class="page-total">共<em>&nbsp;1&nbsp;</em>条记录&nbsp;&nbsp;1/1 页</span>
		-->
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