<?php defined('IN_PHPCMS') or exit('No permission resources.'); ?> <?php include template('member', 'header'); ?>

<script src="http://code.jquery.com/jquery-1.4.1.min.js"type="text/javascript"></script>
<script>
$(function(){
window.onload = function()
{
var $li = $('#tab li');
var $ul = $('#content ul');

$li.mouseover(function(){
var $this = $(this);
var $t = $this.index();
$li.removeClass();
$this.addClass('current');
$ul.css('display','none');
$ul.eq($t).css('display','block');
})
}
});
</script>
  	<div id="ht-main" class="clearfix">	
 
        <article class="content" style="margin-bottom: -1px;">
        
           <div class="layout pro-mod" style="padding-top:0px; border-top:none;">
	            
	            <div class="layout proInfo-mod">
                	<div class="proInfo-hd clearfix">
                    	<div class="proInfo-name fl">
                    	  用户标识:<span style="font-size:16px;color:red;padding-right: 15px;"><?php echo $memberinfo['clientname'];?></span>
						  
						  用户名:<span style="font-size:16px;color:red;padding-right: 15px;"><?php echo $memberinfo['username'];?></span>
						  
						  <!--(<?php echo $memberinfo['groupname'];?> <?php if($memberinfo['vip']) { ?>,<img src="<?php echo IMG_PATH;?>icon/vip.gif"><?php } elseif ($memberinfo['overduedate']) { ?>,<img src="<?php echo IMG_PATH;?>icon/vip-expired.gif" title="<?php echo L('overdue');?>，<?php echo L('overduedate');?>：<?php echo format::date($memberinfo['overduedate'],1);?>"><?php } ?>)&nbsp;&nbsp;<span style="margin-left:30px;color:#ee3300;"><?php if((!$memberinfo[myaddress])) { ?>请您在 用户信息->我的转地地址 中，选择需要的转运地址!<?php } ?></span>-->
						  </div>
                        <div class="proInfo-cont fr">
                        
                        	当前账户可用余额<strong><?php echo $memberinfo['amount'];?> <?php echo L('unit_yuan');?></strong><!---当前积分<strong><?php echo $memberinfo['point'];?> <?php echo L('unit_point');?></strong>--->
                        	<a href="/index.php?m=pay&c=deposit&a=pay" class="p-btn radius-five" title="充值">充 值</a>
                        	
                        </div>
                    </div>
					    
					<div class="proInfo-other clearfix">
		            	<div class="proInfo-v01 fl" style="width:50%;border-right:0;">
	                    	
                            <a href="/index.php?m=package&c=index&a=init&hid=15">未入库<font color="#ff6600">(<?php echo $status1count;?>)</font></a>
							<a href="/index.php?m=package&c=index&a=housemanage">已入库<font color="#ff6600">(<?php echo $status3count;?>)</font></a>
                            <a href="/index.php?m=package&c=index&a=sendedmanage">待处理<font color="#ff6600">(<?php echo $status21count;?>)</font></a>
							<a href="/index.php?m=package&c=index&a=nopaypackage">未付款<font color="#ff6600">(<?php echo $status8count;?>)</font></a>
							<a href="/index.php?m=waybill&c=index&a=init&hid=0&status=7">已付款<font color="#ff6600">(<?php echo $status7count;?>)</font></a>
							<a href="/index.php?m=waybill&c=index&a=init&hid=0&status=14">已发货<font color="#ff6600">(<?php echo $status14count;?>)</font></a>
							<a href="/index.php?m=waybill&c=index&a=init&hid=0&status=14">已签收<font color="#ff6600">(<?php echo $status16count;?>)</font></a>
                            
	                    </div>               
                    </div>

	            </div>
				
                      
            	<div class="layout grid-s12m1">
			      <div class="col-main" style="padding-bottom: 30px;">
			       	 <div class="main-wrap" style="  border-left: 1px solid #ddd;">
			       
				
				<?php $all____warehouse__lists=$this->get_warehouse__lists();?>
				<?php $n=1;if(is_array($all____warehouse__lists)) foreach($all____warehouse__lists AS $vrow) { ?>

			
<div class="pcont-mod index-mod" style="  border-left: 0;">
												
	<div class="pcont-mod-hd">
			<h3><?php echo $vrow['title'];?> 收货地址</h3>
	</div>
												
	<div class="back_home">
			<div class="haitundz">
				邮编：<strong><?php echo $stor['zipcode'];?> </strong> 	地址：<strong><?php echo $stor['address'];?></strong>　<br>
				电话：<strong><?php echo $stor['phone'];?></strong>
					<p style="font-size:12px; padding-top:12px; font-weight: normal;color:#999; margin: 0px">请注意：此电话仅用于日本商家的物流送货联系，其他任何咨询一概无法接受。</p>
			</div>
			<div class="haitundz_dizhi">
					<h3 style="font-size:14px;">日文网站收件地址填写对照表（供参考）</h3>
					<table width="95%" border="0" cellspacing="0" cellpadding="0">
					<tbody>
						<tr>
							<td class="nobr" width="15%"><strong>郵便番号</strong></td>
							<td width="35%"><?php echo $stor['zipcode'];?> </td>
							<td class="nobr" width="15%"><strong>氏名</strong></td>
							<td width="35%">您的姓名，请填写拼音</td>
						</tr>
						<tr>
							<td class="nobr" width="15%"><strong>都道府県</strong></td>
							<td width="35%"><?php echo $stor['province'];?> </td>
							<td class="nobr" width="15%"><strong>カナ</strong></td>
							<td width="35%">您姓名的片假名 【<a href="http://dokochina.com/katakana.php" target="_blank"><strong>汉文转换片假名</strong></a>】</td>
						</tr>
						<tr>
							<td class="nobr" width="15%"><strong>市区町村</strong></td>
							<td width="35%"><?php echo $stor['address'];?> </td>
							<td class="nobr" width="15%"><strong>メール</strong></td>
							<td width="35%">您的电子邮件</td>
						</tr>
						<tr>
							<td class="nobr" width="15%"><strong>番地</strong></td>
							<td width="35%"><strong class="bsm"><?php echo $memberinfo['clientname'];?></strong></td>
							<td class="nobr" width="15%">&nbsp;</td>
							<td width="35%">&nbsp;</td>
						</tr>
						<tr class="bbr">
							<td class="nobr" width="15%"><strong>電話番号</strong></td>
							<td width="35%"><?php echo $stor['phone'];?></td>
							<td class="nobr" width="15%">&nbsp;</td>
							<td width="35%">&nbsp;</td>
						</tr>
					</tbody>
					</table>
				<div class="haitundz_zhu">
					您的用户标识码<strong> <?php echo $memberinfo['clientname'];?> </strong>为包裹入库时关联到您账户的重要依据，请填写地址时务必正确填写，否则无法正常入库。部分购物网站可能需将<strong> <?php echo $memberinfo['clientname'];?> </strong>填在会社名、大厦/公寓、Bld/Apt等空格内。
				</div>
			</div>
			<ul id="tab" style="display:none;">
					<li class="current">转运包裹处理流程</li>
					<li class="">日本亚马逊地址填写方式</li>
			</ul>
			<div id="content" style="display:none;">
					<ul style="display: block;">
					<div class="home_bottom_img"><img src="/resource/images/zylc.png" width="750"></div>
					</ul>
					<ul style="display: none;">
					<div class="home_bottom_img"><img src="/resource/images/amdz.jpg" width="720"></div>
					</ul>
			</div>
</div>





                        </div>
					
						
						<?php $n++;}unset($n); ?>
						
                                      
						
                     </div>
			       </div>
			     
				  
				  <?php include template('member', 'left'); ?>



                </div>
            </div>               
        </article>

		<?php if(defined('IN_ADMIN')  && !defined('HTML')) {echo "<div class=\"admin_piao\" pc_action=\"announce\" data=\"op=announce&tag_md5=01447981acbb61ef83c6627929a6a54f&action=lists&siteid=%24siteid&num=1&moreinfo=1&order=inputtime+DESC\"><a href=\"javascript:void(0)\" class=\"admin_piao_edit\">编辑</a>";}$announce_tag = pc_base::load_app_class("announce_tag", "announce");if (method_exists($announce_tag, 'lists')) {$data = $announce_tag->lists(array('siteid'=>$siteid,'moreinfo'=>'1','order'=>'inputtime DESC','limit'=>'1',));}?>
		<?php $n=1;if(is_array($data)) foreach($data AS $r) { ?>
		<?php $tt=$r[endtime].' 23:59:59';?>
		<?php if(date('Y-m-d H:i:s',time())<=$tt) { ?>
		<?php $content=str_replace(PHP_EOL, '',str_replace('"',"'",($r[content])));?>
		<link rel="stylesheet" href="/resource/css/core.css" type="text/css" media="all" />
		<script type="text/javascript" src="/resource/js/XYTipsWindow-3.0.js"></script>
		
		<?php } ?>
		 <?php $n++;}unset($n); ?>
		 <?php if(defined('IN_ADMIN') && !defined('HTML')) {echo '</div>';}?>
		 
		 </div>
		 </div>
       <?php include template('member', 'footer'); ?>