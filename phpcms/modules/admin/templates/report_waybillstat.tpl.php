<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<style>
.curr{font-weight:bold;}
</style>



<div class="pad-lr-10">


<form name="mysearch" id="mysearch"  action="" method="get">
<input type="hidden" name="m" value="<?php echo $_GET['m'];?>"/>
<input type="hidden" name="c" value="<?php echo $_GET['c'];?>"/>
<input type="hidden" name="a" value="<?php echo $_GET['a'];?>"/>
<input type="hidden" name="act" id="act" value=""/>
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td><div class="explain-col"> 
		时间范围：<?php echo form::date('start_time', $start_time)?>-
				<?php echo form::date('end_time', $end_time)?>&nbsp;
		&nbsp;<input type="submit" name="gotosearch" value="查 询"/>
		
				</div>
		</td>
		</tr>
		<tr>
		<td style="padding-top:10px;">
		<div align=right style="color:#000;"><span style="float:left;color:#ff6600">提示：统计只包括所有已发货，已支付，已完成的订单。</span><input type="submit" name="gobill" onclick="document.getElementById('act').value='download';" value="运单统计下载"/></div>
		</td>
		</tr>
    </tbody>
</table>
</form>


<div class="table-list">
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
			<th align="center">运单号</th>
			<th align="center">邮单号</th>
			<th align="center">客户名</th>
			<th align="center">真实姓名</th>
			<th align="center">物品名称</th>
			<th align="center">收货人</th>
			<th align="center">收货地址</th>
			<th align="center">联系方式</th>
			<th align="center">添加时间</th>
            </tr>
        </thead>
    <tbody>
 <?php 
if(is_array($datas)){
	$m_db = pc_base::load_model('member_model');
	$m_db->set_model(10);

	foreach($datas as $row){
		$usrow = $m_db->get_one(array('userid'=>$row['userid']));
		$addr=$this->get_takeinfo($row['takeaddressid'],$row['userid']);

		$allexp=$this->getwaybill_goods($row['waybillid']);
?>   
	<tr>
	<td align="center"><?php echo $row['waybillid'];?></td>
	<td align="center"><?php
		foreach($allexp as $v){
			echo $v['expressno'].'<br>';
		}
	
	?></td>
	<td align="center"><?php echo $row['username']?></td>
	<td align="center"><?php echo $usrow['lastname'].$usrow['firstname'];?></td>
	<td align="center"><?php echo $row['goodsname'];?></td>
	<td align="center"><?php echo $addr['truename'];?></td>
	<td align="center"><?php echo $addr['address']?></td>
	<td align="center"><?php echo $addr['mobile'];?></td>
	<td align="center"><?php echo date('Y-m-d H:i:s',$row['addtime']);?></td>
	</tr>
<?php 
	}
}
?>
</tbody>
    </table>
  
    <div class="btn" ></div>  </div>
	<div id="pages"><?php echo $this->db->pages;?></div>


</div>
</body>
</html>
