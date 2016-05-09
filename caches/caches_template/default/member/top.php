<?php defined('IN_PHPCMS') or exit('No permission resources.'); ?>				<div class="layout proInfo-mod">
                	<div class="proInfo-hd clearfix">
                    	<div class="proInfo-name fl">
                    	  用户标识:<span style="font-size:16px;color:red;padding-right: 15px;"><?php echo $this->current_user[clientname]; ?></span>
						  
						  用户名:<span style="font-size:16px;color:red;padding-right: 15px;"><?php echo $this->current_user[username]; ?></span>
						  
						  <!--(<?php echo $memberinfo['groupname'];?> <?php if($memberinfo['vip']) { ?>,<img src="<?php echo IMG_PATH;?>icon/vip.gif"><?php } elseif ($memberinfo['overduedate']) { ?>,<img src="<?php echo IMG_PATH;?>icon/vip-expired.gif" title="<?php echo L('overdue');?>，<?php echo L('overduedate');?>：<?php echo format::date($memberinfo['overduedate'],1);?>"><?php } ?>)&nbsp;&nbsp;<span style="margin-left:30px;color:#ee3300;"><?php if((!$memberinfo[myaddress])) { ?>请您在 用户信息->我的转地地址 中，选择需要的转运地址!<?php } ?></span>-->
						  </div>
                        <div class="proInfo-cont fr">
                        
                        	当前账户可用余额<strong><?php echo $this->current_user[amount]; ?> <?php echo L('unit_yuan');?></strong><!---当前积分<strong><?php echo $memberinfo['point'];?> <?php echo L('unit_point');?></strong>--->
                        	<a href="/index.php?m=pay&c=deposit&a=pay" class="p-btn radius-five" title="充值">充 值</a>
                        	
                        </div>
                    </div>
				<?php if($catid!=25) { ?>
					<div class="proInfo-other clearfix">
		            	<div class="proInfo-v01 fl" style="width:50%;border-right:0;">
	                    	
                            <a href="/index.php?m=package&c=index&a=init&hid=15">未入库<font color="#ff6600">(<?php echo $this->getwaybillcount(0,1); ?>)</font></a>
							<a href="/index.php?m=package&c=index&a=housemanage">已入库<font color="#ff6600">(<?php echo $this->getpackagecount(0,3); ?>)</font></a>
                            <a href="/index.php?m=package&c=index&a=sendedmanage">待处理<font color="#ff6600">(<?php echo $this->getwaybillcount(0,21); ?>)</font></a>
							<a href="/index.php?m=package&c=index&a=nopaypackage">未付款<font color="#ff6600">(<?php echo $this->getpackagecount(0,8); ?>)</font></a>
							<a href="/index.php?m=waybill&c=index&a=init&hid=0&status=7">已付款<font color="#ff6600">(<?php echo $this->getpackagecount(0,7); ?>)</font></a>
							<a href="/index.php?m=waybill&c=index&a=init&hid=0&status=14">已发货<font color="#ff6600">(<?php echo $this->getpackagecount(0,14); ?>)</font></a>
							<a href="/index.php?m=waybill&c=index&a=init&hid=0&status=14">已签收<font color="#ff6600">(<?php echo $this->getpackagecount(0,16); ?>)</font></a>
                            
	                    </div>               
                    </div>
				<?php } ?>
	            </div>