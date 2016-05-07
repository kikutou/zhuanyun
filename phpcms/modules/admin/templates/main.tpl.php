<?php
defined('IN_ADMIN') or exit('No permission resources.');
include PC_PATH.'modules'.DIRECTORY_SEPARATOR.'admin'.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.'header.tpl.php';
?>
<div id="main_frameid" class="pad-10 display" style="_margin-right:-12px;_width:98.9%;">
<script type="text/javascript">
$(function(){if ($.browser.msie && parseInt($.browser.version) < 7) $('#browserVersionAlert').show();}); 
</script>
<div class="explain-col mb10" style="display:none" id="browserVersionAlert">
<?php echo L('ie8_tip')?></div>
<div class="col-2 lf mr10" style="width:48%">
	<h6><?php echo L('personal_information')?></h6>
	<div class="content">
	<?php echo L('main_hello')?><?php echo $admin_username?><br />
	<?php echo L('main_role')?><?php echo $rolename?> <br />
	<div class="bk20 hr"><hr /></div>
	<?php echo L('main_last_logintime')?><?php echo date('Y-m-d H:i:s',$logintime)?><br />
	<?php echo L('main_last_loginip')?><?php echo $loginip?> <br />
	</div>
</div>

<div class="col-2 col-auto">
	<h6><?php echo L('main_sysinfo')?></h6>
	<div class="content">
	
	<?php echo L('main_os')?><?php echo $sysinfo['os']?> <br />
	<?php echo L('main_web_server')?><?php echo $sysinfo['web_server']?> <br />
	<?php echo L('main_sql_version')?><?php echo $sysinfo['mysqlv']?><br />
	<?php echo L('main_upload_limit')?><?php echo $sysinfo['fileupload']?><br />	
	</div>
</div>

<div class="col-2 col-auto" style="display:none;">
	<h6><?php echo L('main_safety_tips')?></h6>
	<div class="content" style="color:#ff0000;">
<?php if($pc_writeable) {?>	
<?php echo L('main_safety_permissions')?><br />
<?php } ?>
<?php if(pc_base::load_config('system','debug')) {?>
<?php echo L('main_safety_debug')?><br />
<?php } ?>
<?php if(!pc_base::load_config('system','errorlog')) {?>
<?php echo L('main_safety_errlog')?><br />
<?php } ?>
	<div class="bk20 hr"><hr /></div>	
<?php if(pc_base::load_config('system','execution_sql')) {?>	
<?php echo L('main_safety_sql')?> <br />
<?php } ?>
<?php if($logsize_warning) {?>	
<?php echo L('main_safety_log',array('size'=>$common_cache['errorlog_size'].'MB'))?>
 <br />
<?php } ?>
<?php 
$tpl_edit = pc_base::load_config('system','tpl_edit');
if($tpl_edit=='1') {?>
<?php echo L('main_safety_tpledit')?><br />
<?php } ?>
	</div>
</div>
<div class="bk10"></div>
<div class="col-2 lf mr10" style="width:48%">
<?php
	$w_db = pc_base::load_model('waybill_model');

	$count1 = $w_db->count("status=1");//未入库
	$count3 = $w_db->count("status=3");//已入库
	
	$count21 = $w_db->count("status=21");//待处理	
	$count8 = $w_db->count("status=8");//未付款
	
	$count7 = $w_db->count("status=7");//已付款
	$count14 = $w_db->count("status=14");//已发货

 ?>
	<h6><?php echo L('main_shortcut')?></h6>
	<div class="content" id="admin_panel">
		<table width="100%" cellpadding="3" cellspacing="3">

				 <tr style="border-bottom: 1px dashed #ccc;">
				<td style="width:85px;padding: 5px 0;"><?php echo L('main_weiruku');?>：</td>
				<td width="35%"><strong><a ><?php echo $count1;?></a></strong></td>
                  <td style="width:85px"><?php echo L('main_yiruku');?>：</td>
                  <td width="35%"><strong><a ><?php echo $count3;?></a></strong></td>				
				</tr>
		
                <tr style="border-bottom: 1px dashed #ccc;">
                  <td style="color:#ff6600;padding: 5px 0;"><?php echo L('main_daichuli');?>：</td>
                  <td><strong><a style="color:#ff6600"><?php echo $count21;?></a></strong></td>			   
                  <td><?php echo L('main_weifukuan');?>：</td>
                  <td><strong><a ><?php echo $count8;?></a></strong></td>
               </tr>
                <tr>
                  <td style="color:#ff6600;padding: 5px 0;"><?php echo L('main_yifukuan');?>：</td>
                  <td><strong><a style="color:#ff6600"><?php echo $count7;?></a></strong></td>
                  <td><?php echo L('main_yifahuo');?>：</td>
                  <td><strong><a ><?php echo $count14;?></a></strong></td>
               </tr>

            </table>

	</div>

</div>

<div class="bk10"></div>
<div class="col-2 lf mr10" style="width:48%">
	<h6><?php echo L('main_product_team')?></h6>
	<div class="content">
	<?php

	$tday=date("Y-m-d",time());
	$tday_begin=$tday." 00:00:01";
	$tday_end=$tday." 23:59:59";

	$sqls="status=14  and sendtime>='".strtotime($tday_begin)."' and sendtime<='".strtotime($tday_end)."' ";
	$todayok = $w_db->count($sqls);

	$resfee = $w_db->query("select sum(payedfee) as totalamount from t_waybill where $sqls order by userid");
	$result =$w_db->fetch_array($resfee);
	$amount1=0;
	foreach($result as $row){
		$amount1+=floatval($row['totalamount']);
	}

	//昨天

	$zday=date("Y-m-d",strtotime("-1 day"));
	$zday_begin=$zday." 00:00:01";
	$zday_end=$zday." 23:59:59";
	$sqls="status=14  and sendtime>='".strtotime($zday_begin)."' and sendtime<='".strtotime($zday_end)."' ";
	$ztodayok = $w_db->count($sqls);
	$resfee = $w_db->query("select sum(payedfee) as totalamount from t_waybill where $sqls order by userid");
	$result2 =$w_db->fetch_array($resfee);
	$amount2=0;
	foreach($result2 as $row){
		$amount2+=floatval($row['totalamount']);
	}
	
	//前天

	$qday=date("Y-m-d",strtotime("-2 day"));
	$qday_begin=$qday." 00:00:01";
	$qday_end=$qday." 23:59:59";
	$sqls="status=14  and sendtime>='".strtotime($qday_begin)."' and sendtime<='".strtotime($qday_end)."' ";
	$qtodayok = $w_db->count($sqls);
	$resfee = $w_db->query("select sum(payedfee) as totalamount from t_waybill where $sqls order by userid");
	$result3 =$w_db->fetch_array($resfee);
	$amount3=0;
	foreach($result3 as $row){
		$amount3+=floatval($row['totalamount']);
	}

	?>

<table width="100%" cellpadding="3" cellspacing="3">
               <tr style="border-bottom: 1px dashed #ccc;">
                  <td style="width:125px;padding: 5px 0;"><?php echo date("Y-m-d",time());?><?php echo L('main_clinch_a_deal_today')?>：</td>
                  <td width="35%"><strong style="color:red;"><?php echo $todayok;?></strong></td>
                  <td style="width:125px"><?php echo L('main_the_total_order')?>：</td>
                  <td width="35%"><strong style="color:red;"><?php echo $amount1;?></strong></td>
               </tr>
               <tr style="border-bottom: 1px dashed #ccc;">
                  <td style="padding: 5px 0;"><?php echo date("Y-m-d",strtotime("-1 day"));?><?php echo L('main_clinch_a_deal_today')?>：</td>
                  <td><strong><?php echo $ztodayok;?></strong></td>
                  <td><?php echo L('main_the_total_order')?>：</td>
                  <td><strong><?php echo $amount2;?></strong></td>
               </tr>
			   
               <tr>
                   <td style="padding: 5px 0;"><?php echo date("Y-m-d",strtotime("-2 day"));?><?php echo L('main_clinch_a_deal_today')?>：</td>
                  <td><strong><?php echo $qtodayok;?></strong></td>
                  <td><?php echo L('main_the_total_order')?>：</td>
                  <td><strong><?php echo $amount3;?></strong></td>
               </tr>

			   </table>

	
	</div>
</div>

<div class="col-2 col-auto" style="display:none;">
	<h6><?php echo L('main_license')?></h6>
	<div class="content">
	<?php echo L('main_version')?><?php echo L('main_product_name')?> <?php echo PC_VERSION?>  <br />
	<?php echo L('main_license_type')?><span id="phpcms_license"></span> <br />
	<?php echo L('main_serial_number')?><span id="phpcms_sn"></span> <br />
	</div>
</div>
    <div class="bk10"></div>
	<div class="col-2 lf mr10" style="width:48%">
	<h6><?php L('main_website_info');?></h6>
	<div class="content">
	<?php
	$m_db = pc_base::load_model('member_model');
	$membercount = $m_db->count("");
	$res = $m_db->query("select sum(amount) as totalamount from t_member order by userid");
	$result=$m_db->fetch_array($res);

	$amount=0;
	foreach($result as $row){
		$amount+=floatval($row['totalamount']);
	}

	?>
<table width="100%" cellpadding="3" cellspacing="3">
               <tr>
                  <td style="width:85px"><?php echo L('main_member_counts');?>：</td>
                  <td width="35%"><strong style="color:#ff6600;"><?php echo $membercount ;?></strong></td>
                  <td style="width:85px"><?php echo L('main_advance_payment');?>：</td>
                  <td width="35%"><strong style="color:#ff6600;">￥<?php echo $amount;?></strong></td>
               </tr>
            </table>
	</div>
</div>
<div class="bk10"></div>
</div>


</body></html>