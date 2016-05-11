<?php defined('IN_PHPCMS') or exit('No permission resources.'); ?><!--S 底部-->
		
  <style>
.t_kind{font-family:微软雅黑,宋体,Arial, Helvetica, sans-serif; font-size:14px; font-weight:bold; color:#cbda69;}
#footerListLi{float:left; margin-left:57px;}
#txt1{text-align:left; line-height:25px; }
.txt22 a{color:#fafafa;}
.txt22 a:hover{color:#0080C6;}
 </style>
 
        <footer class="footer">
        	<div class="layout footer-mod">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center" valign="top" >
	<table width="1002" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td align="center">
		<table width="1002" border="0" cellpadding="5" cellspacing="0" style="margin-bottom:20px">
        <tr>
		<?php if(defined('IN_ADMIN')  && !defined('HTML')) {echo "<div class=\"admin_piao\" pc_action=\"get\" data=\"op=get&tag_md5=119dc8d1d86963acd716fc929bebfb09&sql=select+%2A+from+t_category+where+catid%3E11&num=9&return=data2\"><a href=\"javascript:void(0)\" class=\"admin_piao_edit\">修改</a>";}pc_base::load_sys_class("get_model", "model", 0);$get_db = new get_model();$r = $get_db->sql_query("select * from t_category where catid>11 LIMIT 9");while(($s = $get_db->fetch_next()) != false) {$a[] = $s;}$data2 = $a;unset($a);?>
				<?php $n=1;if(is_array($data2)) foreach($data2 AS $r) { ?>
				<?php if($r[catid]==18 || $r[catid]==20 || $r[catid]==21) { ?>
          <td width="152" align="center" valign="top" bgcolor="#667503"><label class="t_kind"><?php echo $r['catname'];?></label></td>
				<?php } ?>
			<?php $n++;}unset($n); ?>
		<?php if(defined('IN_ADMIN') && !defined('HTML')) {echo '</div>';}?>

		<?php if(defined('IN_ADMIN')  && !defined('HTML')) {echo "<div class=\"admin_piao\" pc_action=\"get\" data=\"op=get&tag_md5=76e85b2bb59cfc040086712be8dfc402&sql=select+%2A+from+t_category+where+catid%3E11&num=9&return=data3\"><a href=\"javascript:void(0)\" class=\"admin_piao_edit\">修改</a>";}pc_base::load_sys_class("get_model", "model", 0);$get_db = new get_model();$r = $get_db->sql_query("select * from t_category where catid>11 LIMIT 9");while(($s = $get_db->fetch_next()) != false) {$a[] = $s;}$data3 = $a;unset($a);?>
				<?php $n=1;if(is_array($data3)) foreach($data3 AS $rr) { ?>
				<?php if($rr[catid]==12 || $rr[catid]==22 || $rr[catid]==23) { ?>
          <td width="152" align="center" valign="top" bgcolor="#667503"><label class="t_kind"><?php echo $rr['catname'];?></label></td>
				<?php } ?>
			<?php $n++;}unset($n); ?>
		<?php if(defined('IN_ADMIN') && !defined('HTML')) {echo '</div>';}?>
		
        </tr>
        <tr>
		<?php if(defined('IN_ADMIN')  && !defined('HTML')) {echo "<div class=\"admin_piao\" pc_action=\"get\" data=\"op=get&tag_md5=085f0d1471fe708c665ba918ba226e3f&sql=select+%2A+from+t_category+where+catid%3E11&num=9&return=data0\"><a href=\"javascript:void(0)\" class=\"admin_piao_edit\">修改</a>";}pc_base::load_sys_class("get_model", "model", 0);$get_db = new get_model();$r = $get_db->sql_query("select * from t_category where catid>11 LIMIT 9");while(($s = $get_db->fetch_next()) != false) {$a[] = $s;}$data0 = $a;unset($a);?>
			<?php $n=1;if(is_array($data0)) foreach($data0 AS $r0) { ?>
			<?php if($r0[catid]==18 || $r0[catid]==20 || $r0[catid]==21) { ?>
          <td align="left" valign="top" style="padding-top:5px;">
		  <div id="footerListLi">
              <ul id="txt1">
			  <?php if(defined('IN_ADMIN')  && !defined('HTML')) {echo "<div class=\"admin_piao\" pc_action=\"content\" data=\"op=content&tag_md5=d405f14119056565dc2abf719a0b248d&action=lists&catid=%24r0%5Bcatid%5D&num=5&siteid=%24siteid&return=data1\"><a href=\"javascript:void(0)\" class=\"admin_piao_edit\">修改</a>";}$content_tag = pc_base::load_app_class("content_tag", "content");if (method_exists($content_tag, 'lists')) {$data1 = $content_tag->lists(array('catid'=>$r0[catid],'siteid'=>$siteid,'limit'=>'5',));}?>
				<?php $n=1;if(is_array($data1)) foreach($data1 AS $r1) { ?>
                     <li class="txt22"><a href="<?php echo $r1['url'];?>"><?php echo $r1['title'];?></a></li>
				<?php $n++;}unset($n); ?>
			  <?php if(defined('IN_ADMIN') && !defined('HTML')) {echo '</div>';}?>	  
			</ul>
          </div>
		  </td>
		  <?php } ?>
		  <?php $n++;}unset($n); ?>
		<?php if(defined('IN_ADMIN') && !defined('HTML')) {echo '</div>';}?>
		<?php if(defined('IN_ADMIN')  && !defined('HTML')) {echo "<div class=\"admin_piao\" pc_action=\"get\" data=\"op=get&tag_md5=119dc8d1d86963acd716fc929bebfb09&sql=select+%2A+from+t_category+where+catid%3E11&num=9&return=data2\"><a href=\"javascript:void(0)\" class=\"admin_piao_edit\">修改</a>";}pc_base::load_sys_class("get_model", "model", 0);$get_db = new get_model();$r = $get_db->sql_query("select * from t_category where catid>11 LIMIT 9");while(($s = $get_db->fetch_next()) != false) {$a[] = $s;}$data2 = $a;unset($a);?>
			<?php $n=1;if(is_array($data2)) foreach($data2 AS $r2) { ?>
			<?php if($r2[catid]==12 || $r2[catid]==22 || $r2[catid]==23) { ?>
          <td align="left" valign="top" style="padding-top:5px;">
		  <div id="footerListLi">
              <ul id="txt1">
			  <?php if(defined('IN_ADMIN')  && !defined('HTML')) {echo "<div class=\"admin_piao\" pc_action=\"content\" data=\"op=content&tag_md5=02126356e0df1ee9760aa732a1ef2261&action=lists&catid=%24r2%5Bcatid%5D&num=5&siteid=%24siteid&return=data11\"><a href=\"javascript:void(0)\" class=\"admin_piao_edit\">修改</a>";}$content_tag = pc_base::load_app_class("content_tag", "content");if (method_exists($content_tag, 'lists')) {$data11 = $content_tag->lists(array('catid'=>$r2[catid],'siteid'=>$siteid,'limit'=>'5',));}?>
				<?php $n=1;if(is_array($data11)) foreach($data11 AS $r11) { ?>
                     <li class="txt22"><a href="<?php echo $r11['url'];?>"><?php echo $r11['title'];?></a></li>
				<?php $n++;}unset($n); ?>
			  <?php if(defined('IN_ADMIN') && !defined('HTML')) {echo '</div>';}?>	  
			</ul>
          </div>
		  </td>
		  <?php } ?>
		  <?php $n++;}unset($n); ?>
		<?php if(defined('IN_ADMIN') && !defined('HTML')) {echo '</div>';}?>
         
          </tr>
    </table>
        	<center> 
	            <div class="footer-link tc">
	               
	                   <?php if(defined('IN_ADMIN')  && !defined('HTML')) {echo "<div class=\"admin_piao\" pc_action=\"link\" data=\"op=link&tag_md5=52ced65e04d0bd896adc3513af4d6165&action=type_list&siteid=%24siteid&linktype=0&order=listorder+DESC&num=8&return=pic_link\"><a href=\"javascript:void(0)\" class=\"admin_piao_edit\">修改</a>";}$link_tag = pc_base::load_app_class("link_tag", "link");if (method_exists($link_tag, 'type_list')) {$pic_link = $link_tag->type_list(array('siteid'=>$siteid,'linktype'=>'0','order'=>'listorder DESC','limit'=>'8',));}?>
						<?php $n=1; if(is_array($pic_link)) foreach($pic_link AS $kk => $v) { ?>
	                <?php if($kk>0) { ?>
					<em>|</em>
					<?php } ?>
					<a href="<?php echo $v['url'];?>" title="<?php echo $v['name'];?>" target="_blank">
	                 <?php echo $v['name'];?></a> 
	                
	               
	                  <?php $n++;}unset($n); ?>
						<?php if(defined('IN_ADMIN') && !defined('HTML')) {echo '</div>';}?>
	               
	            </div>
			</center> 
			
	            <p class="tc"><?php echo $sinfo['copyright'];?></p>
	            <p class="tc">
          
				</p>
            </div>
         </footer>   
     
<!-- 运费计算 开始 -->


<DIV id="DialogDiv" style="display: none; top:180px;">
<DIV class="top">

<DIV class="top-title2 hidden-xs">运费计算</DIV>
<DIV class="top-x"><A href="javascript:closeDiv('DialogDiv')" style="color: #fff; font-size: 14px;">关闭</A></DIV>
<DIV class="top-x" style="background: #fff; right: 46px; bottom: -230px; font-size: 12px; z-index: 9999; padding:0;">上述金额不含“关税” “EMS保险费” “单位日元”</DIV>
</DIV>

<DIV class="body">
<DIV class="body-left" style="padding: 10px 0px 0px 20px; float: left;">
<FORM name="dialogform" class="form-horizontal" role="form" action="javascript:;">
<DIV class="form-group"><LABEL class="col-xs-12" style="font-weight: bold;">货物重量(g)：</LABEL>                
           
<DIV class="col-xs-12"><INPUT name="computer_weight" class="form-control" type="text" placeholder="请输入您的包裹重量" value="" style="width:84%;"> 
                          </DIV></DIV>
<DIV class="form-group"><LABEL class="col-xs-12" style="margin-top:20px;font-weight: bold;">转运目的地：</LABEL>                  
         
<DIV class="col-xs-12">
<SELECT name="computer_country" class="form-control computer_country">
<!--<OPTION selected="selected" value="">请选择您的转运目的地</OPTION>-->     
<?php
$cdatas = getcache('get__linkage__lists', 'commons');
foreach($cdatas as $v){
if($v[linkageid]==1){
echo '<OPTION value="'.$v[linkageid].'">'.$v[name].'</OPTION>  ';}
}
?>
                                  
</SELECT>                 
          </DIV></DIV>
<DIV class="form-group text-center">
<BUTTON class="btn btn-primary ac-login-btn1" id="price_sub" style="padding-left:62px;">立即报价</BUTTON>                       </DIV></FORM></DIV>
<DIV class="body-right" style="padding-top: 20px;">
<DIV class="body-right-div">
<DIV class="datalist">
<TABLE class="price_table">
  <THEAD>
  <TR>
    <TH style="width: 40%;">转运方式</TH>
    <TH>手续费</TH>
    <TH>运费</TH>
    <TH>合计</TH></TR>
	</THEAD>
  <TBODY id="price_data"></TBODY></TABLE>
<DIV class="morediv" style="display:none;"><A class="price_more" style="display: none;color:red;" href="javascript:;">显示更多<SPAN>+</SPAN></A></DIV>
</DIV>

<DIV class="body-right-line"></DIV></DIV></DIV>
<DIV class="footer1" style="display:none;">上述金额不含“关税”“EMS保险费”“单位日元”</DIV></DIV>

<SCRIPT language="javascript" type="text/javascript">
  function ShowDIV(thisObjID) {
	  $("#BgDiv").css({ display: "block", height: $(document).height() });
	  //var yscroll = document.documentElement.scrollTop;
	  $("#" + thisObjID ).css("top", "200px");
	  $("#" + thisObjID ).css("display", "block");
	  //document.documentElement.scrollTop = 100;
  }
  function closeDiv(thisObjID) {
	  $("#BgDiv").css("display", "none");
	  $("#" + thisObjID).css("display", "none");
  }
  $(".price_more").click(function(){
      $(".data_more").fadeIn();
	  $(this).hide();
  });
  $("#price_sub").click(function(){

		

	



	  var weight = $("input[name='computer_weight']");
	  var country = $(".computer_country");
	  var weightpreg = /^\d+(\.\d{1,})?$/;
	  if( !weightpreg.test(weight.val()) || weight.val()<=0 ){
		  layer.tips("请输入合法的重量", weight , {guide: 2, time: 2});
	  }else if(weight.val()>30000){
		  layer.tips("重量不能超过30000g", weight , {guide: 2, time: 2});
      }else if(!country.val()){
		  layer.tips("请选择转运目的地", country , {guide: 2, time: 2});
	  }else{


		  $.ajax( { 
   			type: 'POST',  
			url: '/api.php?op=public_api&a=get_line__fee', 
			data: {weight:weight.val(),country:country.val()}, 
			dataType: 'json' , 
			success:  function( json ) {
				if(json.data){
					var html2 = '';
					html1 = '<tr class="data"><td>EMS(国际特快专递)</td><td>'+json.data[123].EMS+'</td><td>'+json.data[48].EMS+'</td><td>'+(parseFloat(json.data[123].EMS)+parseInt(json.data[48].EMS))+'</td></tr>';
					html2 += '<tr class="data "><td>航空件</td><td>'+json.data[123].air+'</td><td>'+json.data[48].air+'</td><td>'+(parseFloat(json.data[123].air)+parseInt(json.data[48].air))+'</td></tr>';
					html2 += '<tr class="data "><td>经济航空件(SAL)</td><td>'+json.data[123].SAL+'</td><td>'+json.data[48].SAL+'</td><td>'+(parseFloat(json.data[123].SAL)+parseInt(json.data[48].SAL))+'</td></tr>';
					html2 += '<tr class="data "><td>海运件</td><td>'+json.data[123].sea+'</td><td>'+json.data[48].sea+'</td><td>'+(parseFloat(json.data[123].sea)+parseInt(json.data[48].sea))+'</td></tr>';
					
					if(country.val() == '1'){
						$("#price_data").html(html1+html2);
						$(".price_more").show();
					}else{
						$("#price_data").html(html1);
						$(".price_more").hide();
					}  	  
					
				}else{
					layer.msg("数据有误，请稍后重试",2,-1);
				}
			}
   		  });
	  }
  });
 </SCRIPT>
  
<STYLE>
 .datalist{position:relative;padding-bottom:30px;height:auto !important;}
 .data td{ text-align:center;}
 .data_more{display:none;}
 .price_table{width:100%;line-height:30px;}
 .price_table th{border-bottom:1px solid #ccc;}
 </STYLE>
     
<SCRIPT type="text/javascript">
var adways_track = adways_track || {'params':new Array()};
(function() {
    var param = {'client_id':2,'get_tag_url':'track.bluebeebox.com/script/','action_url':'track.bluebeebox.com/action/2','thanks_id':0};
    adways_track.params = param;
    var src = 'track.bluebeebox.com/js/adways_track.js';
    src = (location.protocol == 'https:') ? 'https://'+src : 'http://'+src;
    var ts = document.createElement('script');
    ts.type = 'text/javascript'; 
    ts.async = true;
    ts.charset = 'utf-8'; 
    ts.src = src;
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(ts, s);
})();
</SCRIPT>

<!-- 运费计算 结束 -->
 
 
<link rel="stylesheet" href="<?php echo CSS_PATH;?>core.css" type="text/css" media="all" />
<script type="text/javascript" src="<?php echo JS_PATH;?>XYTipsWindow-3.0.js"></script>
<script type="text/javascript"> 
 
 
function dialog_iframe(boxId,title,url){
 		Util.Dialog({
 		boxID: boxId,
 		fixed: true,
		title : title,
		content : "iframe:"+url,
		showbg: true,
		border: {opacity: "0", radius: "3"}
	});
}
function dialog_confirm(title,content,url){
    Util.Dialog({
		fixed: true,showbg: true,title : title,
		content: "text:<center style='line-height:25px;padding:15px 60px 15px 60px'>"+content+"</center>",
		button: ["确定","取消"],
		callback: function(){
		   f.action=url;
		   f.submit();
		}
	});
 
}
function dialog_alert(content){
  Util.Dialog({
        boxID: "dialog-callback-value",
		title: "对话框",
		fixed: true,
		content: "text:<center style='line-height:25px;padding:15px 60px 15px 60px'>"+content+"</center>",
		button: ["确定"],
		callback: function(){
			closeDialog = true; //是否关闭窗口
		}
		
	});
  
}
function loading(){
  Util.Dialog({
			title : "无标题，3秒后关闭",
			boxID : "notitle",
			fixed : true,
			height: 30,
			content : "text:<div style='padding:8px 15px'>载入中……</div>",
			showtitle : "remove",
			time : 10000,
			border : {opacity:"0"},
			bordercolor : "#666"
		});
}
function success(msg){
 	Util.Dialog({
			title : "无标题，3秒后关闭",
			boxID : "notitle",
			fixed: true,
			content : "text:<div style=\"background:url(<?php echo siteurl($siteid);?>/images/tick_48.png) #c3e4fd no-repeat 20px 50%;height:60px;line-height:51px;padding-left:80px;padding-right:60px; \"><b>"+msg+"</b></d",
			showtitle : "remove",
			time : 2000,
			border : {opacity:"-2"}
		});
		
}
 
</script>
 
  
 
        <!--E 底部-->        
    </div><!--E .wrap-->

    
<script type="text/javascript"> 
$(function() {
	var sWidth = $("#img-focus").width(); //获取焦点图的宽度（显示面积）
	var len = $("#img-focus ul li").length; //获取焦点图个数
	var index = 0;
	var picTimer;
	
	//以下代码添加数字按钮和按钮后的半透明条，还有上一页、下一页两个按钮
	var btn = "<div class='btnBg'></div><div class='btn'>";
	for(var i=0; i < len; i++) {
		btn += "<span></span>";
	}
	btn += "</div><div class='preNext pre'></div><div class='preNext next'></div>";
	$("#img-focus").append(btn);
	$("#img-focus .btnBg").css("opacity",0.5);
 
	//为小按钮添加鼠标滑入事件，以显示相应的内容
	$("#img-focus .btn span").css("opacity",0.4).mouseenter(function() {
		index = $("#img-focus .btn span").index(this);
		showPics(index);
	}).eq(0).trigger("mouseenter");
 
	//上一页、下一页按钮透明度处理
	$("#img-focus .preNext").css("opacity",0.2).hover(function() {
		$(this).stop(true,false).animate({"opacity":"0.5"},300);
	},function() {
		$(this).stop(true,false).animate({"opacity":"0.2"},300);
	});
 
	//上一页按钮
	$("#img-focus .pre").click(function() {
		index -= 1;
		if(index == -1) {index = len - 1;}
		showPics(index);
	});
 
	//下一页按钮
	$("#img-focus .next").click(function() {
		index += 1;
		if(index == len) {index = 0;}
		showPics(index);
	});
 
	//本例为左右滚动，即所有li元素都是在同一排向左浮动，所以这里需要计算出外围ul元素的宽度
	$("#img-focus ul").css("width",sWidth * (len));
	
	//鼠标滑上焦点图时停止自动播放，滑出时开始自动播放
	$("#img-focus").hover(function() {
		clearInterval(picTimer);
	},function() {
		picTimer = setInterval(function() {
			showPics(index);
			index++;
			if(index == len) {index = 0;}
		},4000); //此4000代表自动播放的间隔，单位：毫秒
	}).trigger("mouseleave");
	
	//显示图片函数，根据接收的index值显示相应的内容
	function showPics(index) { //普通切换
		var nowLeft = -index*sWidth; //根据index值计算ul元素的left值
		$("#img-focus ul").stop(true,false).animate({"left":nowLeft},300); //通过animate()调整ul元素滚动到计算出的position
		//$("#img-focus .btn span").removeClass("on").eq(index).addClass("on"); //为当前的按钮切换到选中的效果
		$("#img-focus .btn span").stop(true,false).animate({"opacity":"0.4"},300).eq(index).stop(true,false).animate({"opacity":"1"},300); //为当前的按钮切换到选中的效果
	}
});
 
//快运单号查询
function submitF(){
  var f=document.getElementById("mainForm");
  if(isNull($("#ord").val()) || $("#ord").val()=='国际物流单号查询'){
    dialog_alert("请输入快运单号！");
    return;
  }
	if($("#ord").val().trim().length==13 || $("#ord").val().trim().length==11){
  	window.open("https://trackings.post.japanpost.jp/services/srv/search/?requestNo1="+$("#ord").val().trim()+"&startingUrlPatten=&locale=ja&search.x=98&search.y=21");
  }else{
 // window.open("/api.php?op=orderno&ord="+$("#ord").val().trim())
 window.open("https://trackings.post.japanpost.jp/services/srv/search/?requestNo1="+$("#ord").val().trim()+"&startingUrlPatten=&locale=ja&search.x=98&search.y=21");
   f.submit();
  }
  
 
}
 
</script>    


<!-- JiaThis Button BEGIN ->
<script type="text/javascript" >
var jiathis_config={
	data_track_clickback:true,
	summary:"",
	showClose:true,
	shortUrl:false,
	hideMore:false
}
</script>
<script type="text/javascript" src="http://v3.jiathis.com/code/jiathis_r.js?uid=1870795&btn=r.gif&move=0" charset="utf-8"></script>
<!-- JiaThis Button END -->


<script>
window._bd_share_config={"common":{"bdSnsKey":{},"bdText":"","bdMini":"2","bdMiniList":["mshare","qzone","tsina","weixin","renren","tqq","douban","sqq","ty","iguba","fbook","twi","linkedin","copy","print"],"bdPic":"","bdStyle":"0","bdSize":"16"},"slide":{"type":"slide","bdImg":"4","bdPos":"left","bdTop":"100"}};with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion='+~(-new Date()/36e5)];
</script>



<?php $sinfo=siteinfo(1);?>
<?php if($sinfo) { ?>
<?php $allqq=explode(',',trim($sinfo[qqno]));?>


<!-- S 客服 -->
<style>
*{margin:0;padding:0;}

/* side */
.side{position:fixed;width:54px;height:275px;right:0;bottom:81px;z-index:100;}
.side ul li{width:54px;height:80px;float:left;position:relative;border-bottom:1px solid #333;list-style-type:none;}
.side ul li .sidebox{position:absolute;width:54px;height:54px;top:0;right:0;transition:all 0.3s;background:#666;opacity:0.8;filter:Alpha(opacity=80);color:#fff;font:14px/54px "微软雅黑";overflow:hidden;}
.side ul li .sidetop{width:54px;height:54px;line-height:26px;display:inline-block;background:#666;opacity:0.8;filter:Alpha(opacity=80);transition:all 0.3s;}
.side ul li .sidetop:hover{background:#ae1c1c;opacity:1;filter:Alpha(opacity=100);}
.side ul li img{float:left;}
</style>

<div class="side" style="clear:both;">
	<ul>
		<li><a href="javascript:ShowDIV('DialogDiv')"><div class="" style="background: #444; color:#fff;"><img src="/resource/css/side_icon01.png">运费计算</div></a></li>
		<?php $k=1;?>
		<?php $n=1;if(is_array($allqq)) foreach($allqq AS $q) { ?>
			<?php $arrq=explode('|',$q);?>
			<li><a href="http://wpa.qq.com/msgrd?v=3&amp;uin=<?php echo $arrq['0'];?>&amp;site=qq&amp;menu=yes" ><div class=""  style="background: #444; color:#fff;"><img src="/resource/images/side_icon0<?php if($k==1) { ?>6<?php } else { ?>7<?php } ?>.png">QQ客服<?php if($k>1) { ?><?php echo $k;?><?php } ?></div></a></li>
			<?php $k++;?>
		<?php $n++;}unset($n); ?>
	 <li style="border:none;"><a href="javascript:goTop();" class="sidetop"><div class=""  style="background: #444; color:#fff;"><img src="/resource/css/side_icon05.png">返回顶部</div></a></li>
  </ul>
</div>
<script src="/resource/jquery.min.js"></script>	
<script>
$(function(){
	$(".side ul li").hover(function(){
		$(this).find(".sidebox").stop().animate({"width":"124px"},200).css({"opacity":"1","filter":"Alpha(opacity=100)","background":"#ae1c1c"})	
	},function(){
		$(this).find(".sidebox").stop().animate({"width":"54px"},200).css({"opacity":"0.8","filter":"Alpha(opacity=80)","background":"#333"})	
	});
});
//回到顶部函数
function goTop(){
	$('html,body').animate({'scrollTop':0},300);
}
</script>
<!-- E 客服 -->
<?php } ?>
</body>


</html>

