<?php defined('IN_PHPCMS') or exit('No permission resources.'); ?> <div class="col-sub">
					<div class="p-nav p-nav-info">
				
						<div class="p-nav-hd" style="background:rgb(107, 9, 121);">
						<a href="/index.php?m=member&siteid=1" style="color:#fff">仓库地址</a>
						</div>
						
						<div class="p-nav-hd" style="color:#fff">转运管理</div>
						<ul class="p-nav-list">
                        	<li <?php if($_GET['m']=='waybill' &&  ($_GET['a']=='add' )) { ?>class="current"<?php } ?>>
							<a href="/index.php?m=waybill&c=index&a=add" >在线下单</a>
							</li>
							

							<li <?php if($_GET['m']=='package' && $vrow[aid] ==$this->hid && ($_GET['a']=='init' )) { ?>class="current"<?php } ?>>
							<a href="/index.php?m=package&c=index&a=init&hid=<?php echo $vrow['aid'];?>" >未入库件</a>
							</li>

							<li <?php if(($_GET['m']=='package' || $_GET['m']=='waybill') && $vrow[aid] ==$this->hid && ( $_GET['a']=='housemanage' || $_GET['a']=='edit' )) { ?>class="current"<?php } ?>>
							<a href="/index.php?m=package&c=index&a=housemanage&hid=<?php echo $vrow['aid'];?>">已入库件</a>
							</li>

							<li <?php if($_GET['m']=='package' && $vrow[aid] ==$this->hid && ($_GET['a']=='sendedmanage' )) { ?>class="current"<?php } ?>>
							<a href="/index.php?m=package&c=index&a=sendedmanage&hid=<?php echo $vrow['aid'];?>" >待处理件</a>
							</li>

							<li <?php if($_GET['m']=='package' && $vrow[aid] ==$this->hid && ($_GET['a']=='nopaypackage' )) { ?>class="current"<?php } ?>>
							<a href="/index.php?m=package&c=index&a=nopaypackage&hid=<?php echo $vrow['aid'];?>" >未付款件</a>
							</li>


							<li <?php if($_GET['status']==7) { ?>class="current"<?php } ?>>
							<a href="/index.php?m=waybill&c=index&a=init&hid=<?php echo $this->hid?>&status=7" >已付款件</a>
							</li>
							
							<li <?php if($_GET['status']==14) { ?>class="current"<?php } ?>>
							<a href="/index.php?m=waybill&c=index&a=init&hid=<?php echo $this->hid?>&status=14" >已发货件</a>
							</li>

							<li <?php if($_GET['status']==16) { ?>class="current"<?php } ?>>
							<a href="/index.php?m=waybill&c=index&a=init&hid=<?php echo $this->hid?>&status=16" >已签收件</a>
							</li>
                            
                            <li <?php if(ROUTE_A=="publish"  ) { ?>class="current"<?php } ?>><a href="/index.php?m=member&c=content&a=publish" >发布晒单</a></li>
							<li <?php if(ROUTE_A=="published"  ) { ?>class="current"<?php } ?>><a href="/index.php?m=member&c=content&a=published" >我的晒单</a></li>
							
                        </ul>
						

                    	
						<div class="p-nav-hd" style="color:#fff">用户信息</div>
                    	<ul class="p-nav-list">
						
						
                        	<li <?php if($_GET['m']=='address' && ($_GET['a']=='init' || $_GET['a']=='add' || $_GET['a']=='edit')) { ?>class="current"<?php } ?>><a href="/index.php?m=address&c=index&a=init" title="收件地址">收件地址</a></li>
                        <!--	<li style="display:none" <?php if($_GET['m']=='member' && $_GET['a']=='promotion') { ?>class="current"<?php } ?>><a href="index.php?m=member&c=index&a=promotion&t=1" title="推荐奖励">推荐奖励</a></li>--->
							
                        	<li <?php if($_GET['m']=='member' && $_GET['a']=='account_manage_info') { ?>class="current"<?php } ?>><a href="/index.php?m=member&c=index&a=account_manage_info&t=1" title="个人资料">个人资料</a></li>
                        	
                        	<li <?php if($_GET['m']=='member' && $_GET['a']=='account_manage_password') { ?>class="current"<?php } ?>><a href="/index.php?m=member&c=index&a=account_manage_password&t=1" title="修改密码">修改密码</a></li>

                        </ul>

						<div class="p-nav-hd" style="color:#fff">财务管理</div>
						<ul class="p-nav-list">
						
							<!---<li<?php if(ROUTE_A=="change_credit") { ?> class="on"<?php } ?>><a href="index.php?m=member&c=index&a=change_credit">积分兑换</a></li>--->
                        	<li <?php if($_GET['m']=='pay' && $_GET['a']=='pay' || $_GET['a']=='pay_recharge') { ?>class="current"<?php } ?>><a href="/index.php?m=pay&c=deposit&a=pay" title="账户充值">账户充值</a></li>
                        	
                        	<li <?php if($_GET['m']=='pay' && $_GET['c']=='deposit' && $_GET['a']=='init') { ?>class="current"<?php } ?>><a href="/index.php?m=pay&c=deposit&a=init" title="充值记录">充值记录</a></li>
							
							<li <?php if($_GET['m']=='pay' && $_GET['c']=='spend_list' && $_GET['a']=='init') { ?>class="current"<?php } ?>><a href="/index.php?m=pay&c=spend_list&a=init" title="消费记录">消费记录</a></li>

                        </ul>
						<!-- <div class="p-nav-hd"><a style="color:#0079ad" href="<?php echo APP_PATH;?>index.php?m=member&c=index&a=logout&siteid=1" target="_top">安全退出</a></div> -->
						

                    </div><!--E .p-nav-->

                  </div><!--E .col-sub-->