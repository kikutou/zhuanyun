<?php 
	defined('IN_ADMIN') or exit('No permission resources.');
	include $this->admin_tpl('header','admin');
?>
<div class="pad_10">
<div class="table-list">
<form name="searchform" action="" method="get" >
<input type="hidden" value="pay" name="m">
<input type="hidden" value="spend" name="c">
<input type="hidden" value="init" name="a">
<input type="hidden" value="<?php echo $_GET['menuid']?>" name="menuid">
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td>
		<div class="explain-col">
		<?php echo  form::select(array('1'=>L('username'), '2'=>L('userid')), $user_type, 'name="user_type"')?>： <input type="text" value="<?php echo $username?>" class="input-text" name="username"> 
		<?php echo L('from')?>  <?php echo form::date('starttime',format::date($starttime))?> <?php echo L('to')?>   <?php echo form::date('endtime',format::date($endtime))?> 
		
		<?php echo  form::select(array(''=>L('op'), '1'=>L('username'), '2'=>L('userid')), $op_type, 'name="op_type"')?>：
		<input type="text" value="<?php echo $op?>" class="input-text" name="op"> 
		<?php echo  form::select(array(''=>L('expenditure_patterns'), '1'=>L('money'), '2'=>L('point')), $type, 'name="type"')?>
		<input type="submit" value="<?php echo L('search')?>" class="button" name="dosubmit">
		</div>
		</td>
		</tr>
    </tbody>
</table>
</form>
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
            <th width="10%"><?php echo L('username')?></th>
			 <th width="10%">用户标识</th>
            <th width="20%"><?php echo L('content_of_consumption')?></th>
            <th width="15%"><?php echo L('empdisposetime')?> </th>
            <th width="9%"><?php echo L('op')?></th>
            <th width="8%"><?php echo L('expenditure_patterns')?></th>
			
			<th width="8%" style="display:none;">会员余额</th>

		
            <th width="8%"><?php echo L('consumption_quantity')?>/金额</th>
			
			

			
			
            </tr>
        </thead>
    <tbody>
 <?php 
if(is_array($list)){
	$amount = $point = 0;
	
	$spend_amount=0;
	$spend_point=0;
	
	$this_account_db = pc_base::load_model('pay_account_model');
	$memberdb = pc_base::load_model('member_model');

	foreach($list as $info){

		$orderid = trim($info['msg']);
		if(strpos($orderid,"：")!==false){
			$orderid_array=explode("：",$orderid);
			$orderid = trim($orderid_array[1]);
		}
		$memberamount=0;
		$clientname="";

		$mrow= $memberdb->get_one("username='".$info['username']."'");
		if($mrow){
			$memberamount=$mrow['amount'];
			$clientname=$mrow['clientname'];
		}

		$succamount=0;
		$succpoint=0;
		$resus = $this_account_db->select("username='".$info['username']."' and status='succ' ","money,type",100000);

		foreach($resus as $v){
			if ($v['type'] == 1) {
			$succamount+=$v['money'];
			}elseif ($v['type'] == 2) {
			$succpoint=$v['money'];
			}
		}


		 if ($info['type'] == 1) {$spend_amount+=$info['value'];} elseif($info['type'] == 2) {$spend_point+=$info['value'];}
		
?>   
	<tr>
	<td width="10%" align="center"><?php echo $info['username'];?></td>
	<td width="10%" align="center"><?php echo $clientname;?></td>
	<td width="20%" align="center"><?php echo $info['msg']?></td>
	<td  width="15%" align="center"><?php echo format::date($info['creat_at'], 1)?></td>
	<td width="9%" align="center"><?php if (!empty($info['op_userid'])) {echo $info['op_username'];} else {echo L('self');}?></td>
	<td width="8%" align="center"><?php if ($info['type'] == 1) {echo L('money');} elseif($info['type'] == 2) {echo L('point');}?></td>
	<td width="8%" align="center" style="display:none;"><?php echo $memberamount?></td>
	
	<td width="8%" align="center"><?php echo $info['value']?></td>

	
	</tr>
<?php 
	}
}
?>
    </tbody>
    </table>
<div class="btn text-r">
<?php echo L('thispage');?>  总消费<?php echo L('amount');?>：<span class="font-fixh green"><?php echo $spend_amount;?></span>&nbsp;日元  总消费<?php echo L('point');?>：<span class="font-fixh green"><?php echo $spend_point;?></span>&nbsp;日元 
</div>

 <div id="pages"> <?php echo $pages?></div>
</div>
</div>
</form>
</body>
</html>
<script type="text/javascript">
<!--
	function discount(id, name) {
	window.top.art.dialog({title:'<?php echo L('discount')?>--'+name, id:'discount', iframe:'?m=pay&c=payment&a=public_discount&id='+id ,width:'500px',height:'200px'}, 	function(){var d = window.top.art.dialog({id:'discount'}).data.iframe;
	var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'discount'}).close()});
}
function detail(id, name) {
	window.top.art.dialog({title:'<?php echo L('discount')?>--'+name, id:'discount', iframe:'?m=pay&c=payment&a=public_pay_detail&id='+id ,width:'500px',height:'550px'});
}
//-->
</script>