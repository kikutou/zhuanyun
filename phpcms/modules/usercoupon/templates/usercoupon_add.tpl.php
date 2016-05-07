<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<div class="pad-10">
<form method="post" action="?m=usercoupon&c=admin_usercoupon&a=add" name="myform" id="myform">
<table class="table_form" width="100%" cellspacing="0">
	<tbody>
	<tr>
		<th ><strong><?php echo L('couponid')?>：</strong></th>
		<td>  <select name="usercoupon[couponid]" id="couponid">
				<option value="0">请选择</option>
				<option value="2">十一优惠劵</option>
				<option value="1">测试优惠券</option>
			</select></td>
	</tr>
	<tr>
		<th><strong><?php echo L('sendobject')?>：</strong></th>
		<td><input id="objecttype_0" type="radio" name="usercoupon[objecttype]" value="0" checked="checked" />指定用户：<input name="usercoupon[objectname]" type="text" id="objectname" class="normaltb"  size="30" style="width:100px;" />
             <input type="hidden" name="usercoupon[objectid]" id="objectid_0" />
             <a href="javascript:void(0)" onclick="javascript:OpenUserList();">选择会员</a>
             &nbsp;
             <input id="objecttype_1" type="radio" name="usercoupon[objecttype]" value="1" />会员组：
             <select name="usercoupon[objectid]" id="objectid_1" disabled="disabled">
				<option selected="selected" value="0">请选择</option>
				<option value="1">站点管理员</option>
				<option value="2">普通会员</option>
				<option value="3">中级会员</option>
				<option value="4">高级会员</option>
				<option value="5">待审核用户组</option>
			</select>
	</td>
	</tr>
	<tr>
		<th><strong><?php echo L('number')?>：</strong></th>
		<td><input name="usercoupon[number]" id="number" class="input-text" type="text" size="10" >张/人 </td>
	</tr>
	<tr>
		<th><strong><?php echo L('okdays')?>：</strong></th>
		<td>从今日起至<input name="usercoupon[okdays]" id="okdays" class="input-text" type="text" size="8" > 天后过期。</td>
	</tr>
	<tr>
  		<th><strong><?php echo L('remark')?>：</strong></th>
        <td>
		<textarea name="usercoupon[remark]" id="remark" class="input-text" cols="60" rows="5"></textarea>
		</td>
	</tr>
	</tbody>
</table>
<input type="submit" name="dosubmit" id="dosubmit" value=" <?php echo L('ok')?> " class="dialog">&nbsp;<input type="reset" class="dialog" value=" <?php echo L('clear')?> ">
</form>
</div>
</body>
</html>
<script type="text/javascript">


$(document).ready(function(){
	$.formValidator.initConfig({formid:"myform",autotip:true,onerror:function(msg,obj){window.top.art.dialog({content:msg,lock:true,width:'220',height:'70'}, function(){this.close();$(obj).focus();})}});
	$('#title').formValidator({onshow:"<?php echo L('input_usercoupon_title')?>",onfocus:"<?php echo L('title_min_3_chars')?>",oncorrect:"<?php echo L('right')?>"}).inputValidator({min:1,onerror:"<?php echo L('title_cannot_empty')?>"}).ajaxValidator({type:"get",url:"",data:"m=usercoupon&c=admin_usercoupon&a=public_check_title",datatype:"html",cached:false,async:'true',success : function(data) {
        if( data == "1" )
		{
            return true;
		}
        else
		{
            return false;
		}
	},
	error: function(){alert("<?php echo L('server_no_data')?>");},
	onerror : "<?php echo L('usercoupon_exist')?>",
	onwait : "<?php echo L('checking')?>"
	});


	
	
});
</script>