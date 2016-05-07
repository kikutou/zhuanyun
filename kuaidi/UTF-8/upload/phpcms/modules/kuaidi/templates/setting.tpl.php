<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header','admin');
?>
<script language="JavaScript">
<!--
	window.top.$('#display_center_id').css('display','none');
	$(function(){
		$.formValidator.initConfig({formid:"myform",autotip:true,onerror:function(msg,obj){window.top.art.dialog({content:msg,lock:true,width:'200',height:'50'}, function(){this.close();$(obj).focus();})}});
		$("#moduleurl").formValidator({onshow:"<?php echo L("input").L('moduleurl')?>",onfocus:"<?php echo L("input").L('moduleurl')?>"}).inputValidator({min:1,onerror:"<?php echo L('moduleurl').L('noempty')?>"}).regexValidator({regexp:"^[A-Za-z0-9]+$",onerror:"<?php echo L('onerror')?>"});
		$("#key").formValidator({onshow:"<?php echo L("input").L('key').'，'.L('regurl')?>",onfocus:"<?php echo L("input").L('key')?>"}).inputValidator({min:1,onerror:"<?php echo L('key').L('noempty')?>"}).regexValidator({regexp:"^[a-z0-9]{16}$",onerror:"<?php echo L('onerror')?>"});
		$("#description").formValidator({empty:true,onshow:"<?php echo L("input").L('description')?>",onfocus:"<?php echo L("input").L('description')?>",onempty:"<?php echo L('canempty')?>"}).inputValidator({min:1,onerror:"<?php echo L("input").L('description')?>"});
		$('#template_list').formValidator({onshow:"<?php echo L('select_style')?>",onfocus:"<?php echo L('select_style')?>",oncorrect:"<?php echo L('right_all')?>"}).inputValidator({min:1,onerror:"<?php echo L('select_style')?>"});
	})
	function SwapTab(name,cls_show,cls_hide,cnt,cur){
		for(i=1;i<=cnt;i++){
			if(i==cur){
				 $('#div_'+name+'_'+i).show();
				 $('#tab_'+name+'_'+i).attr('class',cls_show);
			}else{
				 $('#div_'+name+'_'+i).hide();
				 $('#tab_'+name+'_'+i).attr('class',cls_hide);
			}
		}
	}
	function load_file_list(id) {
		if(id=='') return false;
		$.getJSON('?m=admin&c=category&a=public_tpl_file_list&style='+id+'&module=kuaidi&templates=index&name=setting', function(data){$('#index_template').html(data.index_template);});
		$.getJSON('?m=admin&c=category&a=public_tpl_file_list&style='+id+'&module=kuaidi&templates=show&name=setting', function(data){$('#show_template').html(data.show_template);});
	}

//-->
</script>
<form name="myform" id="myform" action="?m=kuaidi&c=kuaidi&a=setting" method="post">
<div class="pad-10">
<div class="col-tab">

<ul class="tabBut cu-li">
<li id="tab_setting_1" class="on" onclick="SwapTab('setting','on','',3,1);"><?php echo L('kuaidi_basic');?></li>
<li id="tab_setting_2" onclick="SwapTab('setting','on','',3,2);"><?php echo L('kuaidi_template');?></li>
<li id="tab_setting_3" onclick="SwapTab('setting','on','',3,3);"><?php echo L('kuaidi_seo');?></li>
</ul>
<div id="div_setting_1" class="contentList pad-10">

<table width="100%" class="table_form ">
      <tr>
        <th width="200"><?php echo L('modulename')?>：</th>
        <td><input type="text" id="modulename" class="input-text" value="<?php echo $name;?>" readonly ></td>
      </tr>
      <tr>
        <th><?php echo L('moduleurl')?>：</th>
        <td><input type="text" name="info[url]" id="moduleurl" class="input-text" value="<?php echo $url;?>"></td>
      </tr>
	  <tr>
        <th><?php echo L('key')?>：</th>
        <td><input type="text" name="setting[key]" id="key" size="30" maxlength="16" class="input-text" value="<?php echo $key;?>"></td>
      </tr>
	<tr>
        <th><?php echo L('description')?>：</th>
        <td>
		<textarea name="info[description]" maxlength="255" id="description" style="width:300px;height:60px;"><?php echo $description;?></textarea>
		</td>
      </tr>
	<tr>
		<th><?php echo L('datatype');?>：</th>
		<td>
			<select name="setting[datatype]" id="datatype">
				<option value="0"<?php if($datatype==0) echo " selected";?>>JSON</option>
				<option value="1"<?php if($datatype==1) echo " selected";?>>XML</option>
				<option value="2"<?php if($datatype==2) echo " selected";?>>HTML</option>
				<option value="3"<?php if($datatype==3) echo " selected";?>>TEXT</option>
			</select>
		</td>
	</tr>
	<tr>
     <th><?php echo L('order');?>：</th>
      <td>
	  <input type='radio' name='setting[order]' value='asc' <?php if($order=='asc' || $order=='') echo "checked";?>> <?php echo L('asc');?>&nbsp;&nbsp; <input type='radio' name='setting[order]' value='desc' <?php if($order=='desc') echo "checked";?>> <?php echo L('desc');?>（<?php echo L('data_order_tips');?>）</td>
    </tr>
</table>
</div>
<div id="div_setting_2" class="contentList pad-10 hidden">
<table width="100%" class="table_form ">
	  <tr>
        <th width="200"><?php echo L('available_styles');?>：</th>
        <td><?php echo form::select($temp_list, $nowsetting['template_list'], 'name="setting[template_list]" id="template_list" onchange="load_file_list(this.value)"', L('please_select'))?></td>
	  </tr>
	  <tr>
        <th width="200"><?php if ($nowsetting['template_list']) echo '<script type="text/javascript">$.getJSON(\'?m=admin&c=category&a=public_tpl_file_list&style='.$nowsetting['template_list'].'&id='.$nowsetting['index_template'].'&module=kuaidi&templates=index&name=setting&pc_hash=\'+pc_hash, function(data){$(\'#index_template\').html(data.index_template);});</script>'?><?php echo L('index_tpl')?>：</th>
        <td id="index_template"></td>      
	  </tr>
	  <tr>
        <th width="200"><?php echo L('content_tpl')?>：<?php if ($nowsetting['template_list']) echo '<script type="text/javascript">$.getJSON(\'?m=admin&c=category&a=public_tpl_file_list&style='.$nowsetting['template_list'].'&id='.$nowsetting['show_template'].'&module=kuaidi&templates=show&name=setting&pc_hash=\'+pc_hash, function(data){$(\'#show_template\').html(data.show_template);});</script>'?></th>
        <td id="show_template"></td>
      </tr>
</table>
</div>
<div id="div_setting_3" class="contentList pad-10 hidden">
<table width="100%" class="table_form ">
	<tr>
      <th width="200"><?php echo L('meta_title');?></th>
      <td><input name='setting[meta_title]' type='text' id='meta_title' value='<?php echo $meta_title;?>' size='60' maxlength='60'></td>
    </tr>
    <tr>
      <th ><?php echo L('meta_keywords');?></th>
      <td><textarea name='setting[meta_keywords]' id='meta_keywords' style="width:90%;height:40px"><?php echo $meta_keywords;?></textarea></td>
    </tr>
    <tr>
      <th ><strong><?php echo L('meta_description');?></th>
      <td><textarea name='setting[meta_description]' id='meta_description' style="width:90%;height:50px"><?php echo $meta_description;?></textarea></td>
    </tr>
</table>
</div>
 <div class="bk15"></div>
	<input name="catid" type="hidden" value="<?php echo $catid;?>">
    <input name="dosubmit" type="submit" value="<?php echo L('submit')?>" class="button">
</form>
</div>

</div>
<!--table_form_off-->
</div>
</body>
</html>