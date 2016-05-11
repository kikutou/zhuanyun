<?php defined('IN_PHPCMS') or exit('No permission resources.'); ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo $this->__all__urls[0]['news'];?>--<?php echo $this->wap['sitename'];?></title>

<meta name="viewport" content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=no;">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="format-detection" content="telephone=no">
<link rel="stylesheet" type="text/css" href="/resource/wap/css/common.css" />
<link rel="stylesheet" type="text/css" href="/resource/wap/css/font-awesome.css" />
<link rel="stylesheet" type="text/css" href="/resource/wap/css/home-menu.css" />
<link href="/resource/wap/css/listhome1_.css" rel="stylesheet" type="text/css" />

<style>
 .themeStyle{ background-color:#E83407 !important; }  
</style>
</head>
<script>
window.onload = function ()
{
var oWin = document.getElementById("win");
var oLay = document.getElementById("overlay");	
var oBtn = document.getElementById("popmenu");
var oClose = document.getElementById("close");
oBtn.onclick = function ()
{
oLay.style.display = "block";
oWin.style.display = "block"	
};
oLay.onclick = function ()
{
oLay.style.display = "none";
oWin.style.display = "none"	
}
};
</script>
<body id="listhome1">
<div id="ui-header">
<div class="fixed">
<a class="ui-title" id="popmenu">选择分类</a>
<a class="ui-btn-left_pre" href="javascript:history.go(-1);"></a>
<a class="ui-btn-right_home" href="<?php echo WAP_SITEURL;?>"></a>

</div>
</div>

<div id="overlay"></div>
<div id="win">
<ul class="dropdown"> 
<?php if(defined('IN_ADMIN')  && !defined('HTML')) {echo "<div class=\"admin_piao\" pc_action=\"content\" data=\"op=content&tag_md5=fb6bd5257a4b47d215fd046062fc3116&action=category&catid=0&num=10&siteid=1&order=listorder+ASC&return=catdata\"><a href=\"javascript:void(0)\" class=\"admin_piao_edit\">编辑</a>";}$content_tag = pc_base::load_app_class("content_tag", "content");if (method_exists($content_tag, 'category')) {$catdata = $content_tag->category(array('catid'=>'0','siteid'=>'1','order'=>'listorder ASC','limit'=>'10',));}?>
<?php $n=1; if(is_array($catdata)) foreach($catdata AS $i => $r) { ?>
<?php if($r[catid]>6 && $r[catid]<11) { ?>
<li><a href="<?php echo $this->__all__urls[1]['news'];?>&catid=<?php echo $r['catid'];?>"><span><?php echo $r['catname'];?></span></a></li>
<?php } ?>
<?php $n++;}unset($n); ?>
<?php if(defined('IN_ADMIN') && !defined('HTML')) {echo '</div>';}?>
<div class="clr"></div>
</ul>
</div>

<div class="Listpage">
<div class="top46"></div>
    <div id="todayList">
<ul  class="todayList">
 <?php if(defined('IN_ADMIN')  && !defined('HTML')) {echo "<div class=\"admin_piao\" pc_action=\"content\" data=\"op=content&tag_md5=dbd0e3d860bf98b4f04e1710f2869cd5&action=lists&catid=%24catid&num=10&siteid=1&page=%24page&order=inputtime+deSC&return=datas\"><a href=\"javascript:void(0)\" class=\"admin_piao_edit\">编辑</a>";}$content_tag = pc_base::load_app_class("content_tag", "content");if (method_exists($content_tag, 'lists')) {$pagesize = 10;$page = intval($page) ? intval($page) : 1;if($page<=0){$page=1;}$offset = ($page - 1) * $pagesize;$content_total = $content_tag->count(array('catid'=>$catid,'siteid'=>'1','order'=>'inputtime deSC','limit'=>$offset.",".$pagesize,'action'=>'lists',));$pages = pages($content_total, $page, $pagesize, $urlrule);$datas = $content_tag->lists(array('catid'=>$catid,'siteid'=>'1','order'=>'inputtime deSC','limit'=>$offset.",".$pagesize,'action'=>'lists',));}?>
<?php $n=1;if(is_array($datas)) foreach($datas AS $row) { ?>

<li>
<a href="/index.php?m=wap&c=index&a=show&catid=<?php echo $row['catid'];?>&id=<?php echo $row['id'];?>">
<?php if($row[thumb]) { ?>
<div class="img"><img src="<?php echo $row['thumb'];?>"></div>
<?php } ?>
<h2><?php echo $row['title'];?></h2>
<p class="onlyheight"><?php echo $row['description'];?></p>
<div class="commentNum"></div>
</a>
</li>
<?php $n++;}unset($n); ?>
<?php if(defined('IN_ADMIN') && !defined('HTML')) {echo '</div>';}?>
			
</ul>
</div>




<section id="Page_wrapper" >
	<div id="pNavDemo" class="c-pnav-con pages" >
		<section class="c-p-sec" >
		
		<?php echo $pages;?>

		</section>
	</div>
</section>
<p>&nbsp;</p>
</div>

<?php include template("wap","footer"); ?>

 </body>
</html>
