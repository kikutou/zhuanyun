<?php 
	defined('IN_ADMIN') or exit('No permission resources.');
	include $this->admin_tpl('header', 'admin');
?>

<div class="pad_10">
<div class="table-list">

<form name="searchform" action="?" method="get" >
<input type="hidden" value="pay" name="m">
<input type="hidden" value="payment" name="c">
<input type="hidden" value="1" name="dosubmit">
<input type="hidden" value="payment_history" name="a">
<input type="hidden" value="<?php echo $_GET['menuid']?>" name="menuid">
<div class="explain-col search-form">
<?php echo L('order_sn')?>  <input type="text" value="<?php echo $trade_sn?>" class="input-text" name="trade_sn"> 
<?php echo L('username')?>  <input type="text" value="<?php echo $username?>" class="input-text" name="username"> 
<?php echo L('addtime')?>  <?php echo form::date('start_addtime',$start_addtime)?><?php echo L('to')?>   <?php echo form::date('end_addtime',$end_addtime)?> 

<input type="submit" value="<?php echo L('search')?>" class="button" name="dosubmit2">
</div>
</form>

    <table width="100%" cellspacing="0">
        <thead>
            <tr>
            <th   align="left">流水号</th>
            <th >UserID</th>
            <th >UserName</th>
            <th >充值金额</th>
			<th >充值前用户金额</th>
            <th >充值后用户金额</th>
			 <th >充值时间</th>
			  <th >添加时间</th>
            </tr>
        </thead>
    <tbody>
 <?php 
if(is_array($infos)){
	$alluseramount=0;
	$allamount=0;
	$allnowuseramount=0;

	foreach($infos as $info){

		$alluseramount+=$info['useramount'];
		$allamount+=$info['amount'];
		$allnowuseramount+=$info['nowuseramount'];

?>   
	<tr>
	<td ><?php echo $info['orderid']?></td>
	<td   align="center"><?php echo $info['userid']?></td>
	<td   align="center"><?php echo $info['username']?></td>
	<td  align="center"><?php echo $info['amount']?></td>
	<td align="center"><?php echo $info['useramount']?></td>
	<td  align="center"><?php echo $info['nowuseramount']?></td>
	<td align="center"><?php echo date('Y-m-d H:i:s',$info['ordertime'])?></td>
	<td  align="center"><?php echo date('Y-m-d H:i:s',$info['addtime'])?></td>
	</tr>
<?php 
	}
}
?>
    </tbody>
    </table>
  
    <div class="btn text-r">
本页 充值金额:<?php echo $allamount;?> &nbsp;&nbsp;&nbsp;充值前用户金额:<?php echo $alluseramount;?>&nbsp;&nbsp;&nbsp;充值后用户金额:<?php echo $allnowuseramount;?>
</div>  </div>

 <div id="pages"> <?php echo $pages?></div>
</div>
</div>

</body>

</html>
