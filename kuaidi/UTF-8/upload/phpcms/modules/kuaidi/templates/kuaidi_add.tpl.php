<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header','admin');
?>
<script type="text/javascript">
<!--
	$(function(){
	$.formValidator.initConfig({formid:"myform",autotip:true,onerror:function(msg,obj){window.top.art.dialog({content:msg,lock:true,width:'200',height:'50'}, function(){this.close();$(obj).focus();})}});
	$("#kd_name").formValidator({onshow:"<?php echo L("input").L('kd_name')?>",onfocus:"<?php echo L("input").L('kd_name')?>"}).inputValidator({min:1,onerror:"<?php echo L('kd_name').L('noempty')?>"});
	$("#fullname").formValidator({onshow:"<?php echo L("input").L('fullname')?>",onfocus:"<?php echo L("input").L('fullname')?>"}).inputValidator({min:1,onerror:"<?php echo L('fullname').L('noempty')?>"});
	$("#code").formValidator({onshow:"<?php echo L("input").L('code')?>",onfocus:"<?php echo L("input").L('code')?>"}).inputValidator({min:1,onerror:"<?php echo L("input").L('code')?>"}).regexValidator({regexp:"^[A-Za-z0-9]+$",onerror:"<?php echo L('onerror')?>"})
 	$("#kuaidi_url").formValidator({empty:true,onshow:"<?php echo L("input").L('url').'，'.L('canempty')?>",onfocus:"<?php echo L("input").L('url')?>",onempty:"<?php echo L('canempty')?>"}).inputValidator({min:1,onerror:"<?php echo L("input").L('url')?>"}).regexValidator({regexp:"^http:\/\/[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&]*([^<>])*$",onerror:"<?php echo L('onerror')?>"})
	$("#tel").formValidator({empty:true,onshow:"<?php echo L("input").L('tel').'，'.L('canempty')?>",onfocus:"<?php echo L("input").L('tel')?>",onempty:"<?php echo L('canempty')?>"}).inputValidator({min:1,onerror:"<?php echo L("input").L('tel')?>"})
	$('#style').formValidator({onshow:"<?php echo L('select_style')?>",onfocus:"<?php echo L('select_style')?>",oncorrect:"<?php echo L('right_all')?>"}).inputValidator({min:1,onerror:"<?php echo L('select_style')?>"});
	})
//-->
</script>
<div class="pad_10">
<form action="?m=kuaidi&c=kuaidi&a=add" method="post" name="myform" id="myform">
<table cellpadding="2" cellspacing="1" class="table_form" width="100%">
	<tr>
		<th width="100"><?php echo L('typeid')?>：</th>
		<td><select name="kuaidi[typeid]" id="">
		<option value="0">默认分类</option>
		<?php
		  $i=0;
		  foreach($types as $typeid=>$type){
		  $i++;
		?>
		<option value="<?php echo $type['typeid'];?>"><?php echo $type['name'];?></option>
		<?php }?>
		</select></td>
	</tr>
	
	<tr>
		<th width="100"><?php echo L('kd_name')?>：</th>
		<td><input type="text" name="kuaidi[name]" id="kd_name"
			size="25" class="measure-input input-text"></td>
	</tr>
	
	<tr>
		<th width="100"><?php echo L('fullname')?>：</th>
		<td><input type="text" name="kuaidi[fullname]" id="fullname"
			size="45" class="measure-input input-text"></td>
	</tr>

	<tr>
		<th width="100"><?php echo L('code')?>：</th>
		<td><input type="text" name="kuaidi[code]" id="code"
			size="15" class="input-text"></td>
	</tr>
	<tr>
		<th><?php echo L('introduce')?>：</th>
		<td><textarea name="kuaidi[introduce]" id="introduce"></textarea><?php echo form::editor('introduce');?></td>
	</tr>
	<tr id="logolink">
		<th width="100"><?php echo L('logo')?>：</th>
		<td><?php echo form::images('kuaidi[logo]', 'logo', '', 'kuaidi')?></td>
	</tr>
	<tr>
		<th width="100"><?php echo L('url')?>：</th>
		<td><input type="text" name="kuaidi[url]" id="kuaidi_url"
			size="30" class="input-text"></td>
	</tr>
	<tr>
		<th width="100"><?php echo L('tel')?>：</th>
		<td><input type="text" name="kuaidi[tel]" id="tel"
			size="30" class="input-text"></td>
	</tr>
	<tr>
  		<th><?php echo L('available_styles')?>：</th>
        <td><?php echo form::select($template_list, $info['default_style'], 'name="kuaidi[style]" id="style" onchange="load_file_list(this.value)"', L('please_select'))?></td>
	</tr>
	<tr>
		<th><?php echo L('template_select')?>：</th>
		<td id="show_template"><script type="text/javascript">$.getJSON('?m=admin&c=category&a=public_tpl_file_list&style=<?php echo $info['default_style']?>&module=kuaidi&templates=show&name=kuaidi&pc_hash='+pc_hash, function(data){$('#show_template').html(data.show_template);});</script></td>
	</tr>
 
	<tr>
		<th><?php echo L('common')?>：</th>
		<td><input name="kuaidi[common]" type="radio" value="1" >&nbsp;<?php echo L('yes')?>&nbsp;&nbsp;<input
			name="kuaidi[common]" type="radio" value="0" checked>&nbsp;<?php echo L('no')?></td>
	</tr>
	 
	<tr>
		<th><?php echo L('passed')?>：</th>
		<td><input name="kuaidi[passed]" type="radio" value="1" checked>&nbsp;<?php echo L('yes')?>&nbsp;&nbsp;<input
			name="kuaidi[passed]" type="radio" value="0">&nbsp;<?php echo L('no')?></td>
	</tr>

<tr>
		<th></th>
		<td><input type="hidden" name="forward" value="?m=vote&c=vote&a=add"> <input
		type="submit" name="dosubmit" id="dosubmit" class="dialog"
		value=" <?php echo L('submit')?> "></td>
	</tr>

</table>
</form>
</div>
</body>
</html>
<script type="text/javascript">
function load_file_list(id) {
	if (id=='') return false;
	$.getJSON('?m=admin&c=kuaidi&a=public_tpl_file_list&style='+id+'&module=kuaidi&templates=show&name=kuaidi&pc_hash='+pc_hash, function(data){$('#show_template').html(data.show_template);});
}
</script>