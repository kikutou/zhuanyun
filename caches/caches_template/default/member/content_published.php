<?php defined('IN_PHPCMS') or exit('No permission resources.'); ?> <?php include template('member', 'header'); ?>

<style>
body fieldset{border:1px solid #D8D8D8; padding:10px;}
body fieldset legend{padding:3px 8px;font-weight:700;border:1px solid #D8D8D8;background-color: #F9F9F9;}
body fieldset.blue{border:1px solid #dce3ed}
body fieldset.blue legend{padding:3px 8px;font-weight:700;color:#347add; background:none; border:none}
.picBut {background:url("../images/admin_img/picBnt.png") no-repeat 0 -28px;color:#FFF;float:left; text-align:center;font-weight:700;height:28px;line-height:26px;*line-height:30px;margin-right:10px;width:75px}
.picBut a{color:#fff;text-decoration: none; width:75px; height:28px;display:inline-block;zoom:1;*display:inline;}

.table-list td,.table-list th{padding-left:12px}
.table-list thead th{ height:30px; background:#eef3f7; border-bottom:1px solid #d5dfe8; font-weight:normal}
.table-list tbody td,.table-list .btn{border-bottom: #eee 1px solid; padding-top:5px; padding-bottom:5px}
div.btn{background:#f6f6f6; padding:6px 12px 0 12px; height:30px;line-height:30px}
.table-list tr:hover,.table-list table tbody tr:hover{ background:#fbffe4}
.nHover tr:hover,.nHover tr:hover td{ background:none}
.table-list .input-text-c{ padding:0; height:18px}
.input-text-c{border:1px solid #A7A6AA;height:18px;padding:2px 0 0; text-align:center}
.td-line{border:1px solid #eee}
.td-line td,.td-line th{border:1px solid #eee}
.button{background:#ddd;height:24px; border-top:0;border-left:0; border-bottom:1px solid #666; border-right:1px solid #666; padding:3px 6px; margin-right:5px}
.table-list tr.on,.table-list tr.on td,.table-list tr.on th,.table-list td.on,.table-list th.on{background:#fdf9e5;}
a.close-own{background: url(../images/cross.png) no-repeat left 3px; display:block; width:16px; height:16px;position: absolute;outline:none;right:7px; top:8px; text-indent:200px; overflow: hidden}
a.close-own:hover{background-position: left -46px}
/*通用表单*/
.common-form{}
.common-form div.contentWrap{padding-right:20px}
.common-form ul li{color:#444; clear:both; vertical-align:middle}
.common-form ul li span.text{width:60px}
.common-form .set{border:1px dashed #e0e7ed;zoom:1; background:#f2f7fb; padding:10px; font-size:12px; margin-bottom:10px}
.common-form .set table td,.common-form .set table th{padding-left:12px}
.common-form .set table th{font-weight:normal; text-align:left;padding:0 8px}
.common-form .set table td{ padding:3px 0 3px 5px}
.common-form .set table td.y-bg{background: url(../images/admin_img/set_y_line.png) repeat-y right top}
.common-form .set table td input{ background-image:none; height:18px; font-size:12px}
.input-text,.measure-input,textarea,input.date,input.endDate,.input-focus{border:1px solid #A7A6AA;height:18px;margin:0 5px 0 0;padding:2px 0 2px 5px;border: 1px solid #d0d0d0;background: #FFF url(../images/admin_img/input.png) repeat-x; font-family: Verdana, Geneva, sans-serif,"宋体";font-size:12px;}
.input-focus{background: #FFF url(../images/admin_img/input_focus.png) repeat-x; border-color:#afcee6;font-size:12px;}
input.date,input.endDate{background: #fff url(../images/admin_img/input_date.png) no-repeat right 3px; padding-right:18px;font-size:12px;}
textarea,textarea.input-text,textarea.input-focus{font-size:12px;height:auto; padding:5px; margin:0;}
select{ vertical-align:middle;background:none repeat scroll 0 0 #F9F9F9;border-color:#666666 #CCCCCC #CCCCCC #666666;border-style:solid;border-width:1px;color:#333;padding:2px;}
.search-form{ margin-bottom:10px}
/*表格表单*/
.table_form{font-size:12px}
.table_form td{padding-left:12px}
.table_form th{font-weight:normal; text-align:right;padding-right:10px; color:#777}
.table_form td label{ vertical-align:middle}
.table_form td,.table_form th{padding:4px 0 4px 8px}
.table_form tbody td,.table_form tbody th{border-bottom:1px solid #eee; }
.colorpanel tbody td,.colorpanel tbody th{ padding:0;border-bottom: none;}
/*select美化*/
.js ul.newList {left:-9999px;}
ul.newList * {margin:0; padding:0;}
ul.newList {margin:0; padding:0; list-style:none; color:#000; background:#fff; position:absolute;  border:1px solid #ccc; top:22px; left:0; overflow:auto; z-index:9999;}
.newListSelected {color:#000; height:22px; padding:4px 0 0 6px; float:left; background:url(../images/select-bg.png) no-repeat right 0; border-left:1px solid #dfdfdf}
.newListSelected span {display:block;}
ul.newList li a {padding:3px 8px;display:block;text-decoration: none;}
.selectedTxt {overflow:hidden; height:16px; padding:0 23px 0 0;}
.measure-input {background:url("../images/ruler.gif") repeat-x scroll 0 9px transparent}
.hiLite {background:#e0ebf4!important; color:#444!important;}
.newListHover {background:#f2f7fb!important; color:#000!important; cursor:default;}
.newListSelHover, .newListSelFocus {background-position:right -26px; cursor:default;}
.newListOptionTitle {font-weight:bold;}
.newListOptionTitle ul {margin:3px 0 0;}
.newListOptionTitle li {font-weight:normal; border-left:1px solid #ccc;}

/*表单验证*/
.onShow,.onFocus,.onError,.onCorrect,.onLoad,.onTime{display:inline-block;display:-moz-inline-stack;zoom:1;*display:inline; vertical-align:middle;background:url(../images/msg_bg.png) no-repeat;	color:#444;line-height:18px;padding:2px 10px 2px 23px; margin-left:10px;_margin-left:5px}
.onShow{background-position:3px -147px;border-color:#40B3FF;color:#959595}
.onFocus{background-position:3px -147px;border-color:#40B3FF;}
.onError{background-position:3px -47px;border-color:#40B3FF; color:red}
.onCorrect{background-position:3px -247px;border-color:#40B3FF;}
.onLamp{background-position:3px -200px}
.onTime{background-position:3px -1356px}


</style>

        <!--E 导航-->   
			<link rel="stylesheet" type="text/css" href="<?php echo JS_PATH;?>calendar/jscal2.css"/>
			<link rel="stylesheet" type="text/css" href="<?php echo JS_PATH;?>calendar/border-radius.css"/>
			<link rel="stylesheet" type="text/css" href="<?php echo JS_PATH;?>calendar/win2k.css"/>
			<script type="text/javascript" src="<?php echo JS_PATH;?>calendar/calendar.js"></script>
			<script type="text/javascript" src="<?php echo JS_PATH;?>calendar/lang/en.js"></script>
<script type="text/javascript">
<!--
	var charset = '<?php echo CHARSET;?>';
	var uploadurl = '<?php echo pc_base::load_config('system','upload_url')?>';
//-->
</script>
<link href="<?php echo CSS_PATH;?>dialog.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH;?>dialog.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH;?>content_addtop.js"></script>
        <!--S 内容-->
        <article class="content">
        	
         	<!--S 内容-->
        	<div class="layout pro-mod" style="border-top:none;">        
            	<div class="layout grid-s12m0">
			       <div class="col-main">
			       	 <div class="main-wrap">
						<div class="pcont-mod">
                        	<div class="pcont-mod-hd">
                            	<h3>我的晒单</h3>
								<div class="plink-mod">
                               
                                </div><!--E .plink-mod-->
                            </div><!--E .pcont-mod-hd-->
                            <div class="pcont-mod-bd">
                            	<!--S 右边内容开始-->
			                	<div class="bag-mod">
							      
							        <div id="tab-cont">
							          <div id="tab-cont-v01"> 
			                           		<div class="pcont-date-mod" style="padding-top:0;">
												
															<style>td,th{border:solid 1px #ddd;padding:2px 10px;}</style>
												


			<table width="100%" cellspacing="0"  class="table-list">
        <thead>
            <tr>
            <th width="30" style="display:none;">ID</th>
            <th><?php echo L('title');?></th>
            <th width="80" style="display:none;"><?php echo L('category');?></th>
            <th width="80"><?php echo L('adddate');?></th>
            <th width="90" style="display:none;"><?php echo L('operation');?></th>
            </tr>
        </thead>
    <tbody>
   	<?php $n=1;if(is_array($datas)) foreach($datas AS $info) { ?> 
	<tr>
	<td align="center" style="display:none;"><?php echo $info['id'];?></td>
	<td align="left"><a href="<?php echo $info['url'];?>" target="_blank" title="<?php echo $info['title'];?>"><?php echo str_cut($info['title'], 60);?></a> <?php if($info[status]==0) { ?><font color="red"><?php echo L('reject_content');?></font><?php } elseif ($info[status]!='99') { ?><font color="#1D94C7">待审中</font><?php } ?></td>
	<td align="center" style="display:none;"><a ><?php echo $CATEGORYS[$info['catid']]['catname'];?></a></td>
	<td align="center"><?php echo date('Y-m-d',$info['inputtime']);?></td>
	<td align="center" style="display:none;">
	<?php if($info[status]==99) { ?><font color="green"><?php echo L('pass');?></font><?php } elseif (!$info[flag]) { ?><a href="index.php?m=member&c=content&a=edit&catid=<?php echo $info['catid'];?>&id=<?php echo $info['id'];?>"><font color="red"><?php echo L('edit');?></font></a> | <a href="index.php?m=member&c=content&a=delete&catid=<?php echo $info['catid'];?>&id=<?php echo $info['id'];?>">删除</a><?php } else { ?><a href="index.php?m=member&c=content&a=edit&catid=<?php echo $info['catid'];?>&id=<?php echo $info['id'];?>"><font color="red"><?php echo L('edit');?></font></a> | <a href="index.php?m=member&c=content&a=delete&catid=<?php echo $info['catid'];?>&id=<?php echo $info['id'];?>">删除</a><?php } ?>
  	 
	</td>
	</tr>
	<?php $n++;}unset($n); ?>
    </tbody>
    </table>

 <div id="pages"> <?php echo $pages;?></div>

<script language="JavaScript">
<!--
	function c_c(catid) {
		location.href='index.php?m=member&c=content&a=published&siteid=<?php echo $siteid;?>&catid='+catid;
	}
//-->
</script>

			                                </div><!--E .pcont-date-mod-->                          
			                          </div><!--E #tab-cont-v01-->
							        
			                        </div><!--E #tab-cont-->                      
			                    </div><!--E .bag-mod-->  
                                <!--E 右边内容结束-->
                            </div><!--E .pcont-mod-bd-->
                        </div><!--E .pcont-mod-->
                     </div><!--E .main-wrap-->
			       </div><!--E .col-main-->
			     <?php include template('member', 'left'); ?>
                </div><!--E .grid-s12m0-->
            </div>
            <!--E 内容-->                 
        </article>
        <!--E 内容-->
        <!--S 底部-->
   
 
  <?php include template('member', 'footer'); ?>