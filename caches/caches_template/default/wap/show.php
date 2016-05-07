<?php defined('IN_PHPCMS') or exit('No permission resources.'); ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
<title><?php echo $title;?>--<?php echo $this->wap['sitename'];?></title> 
<meta name="viewport" content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=no;">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="format-detection" content="telephone=no">
<link rel="stylesheet" type="text/css" href="/resource/wap/css/font-awesome.css" />
<link rel="stylesheet" type="text/css" href="/resource/wap/css/common.css" />

<link rel="stylesheet" type="text/css" href="/resource/wap/css/home-menu.css" />
<link href="/resource/wap/css/news3_.css" rel="stylesheet" type="text/css" />
<script src="/resource/wap/js/audio.min.js" type="text/javascript"></script>
   
    <script>
      audiojs.events.ready(function() {
        audiojs.createAll();
      });
    </script>

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
<style>

 .themeStyle{ background-color:#E83407 !important; }  

</style>
</head> 
<body id="news">
<div id="mcover" onclick="document.getElementById('mcover').style.display='';"><img src="/resource/wap/images/guide.png"></div>
<div id="ui-header">
<div class="fixed">
<a class="ui-title" id="popmenu"><?php echo $CATEGORYS[$catid]['catname'];?></a>
<a class="ui-btn-left_pre" href="javascript:history.go(-1);"></a><a class="ui-btn-right_home" href="<?php echo WAP_SITEURL;?>"></a>
</div>
</div>
<div id="overlay"></div>
<div class="Listpage">
<div class="top46"></div>
<div class="page-bizinfo">

<div class="header" style="position: relative;">
<center><?php echo $title;?></center>
<p style="font-size:12px;" align=center>日期：<?php echo $inputtime;?></p>
</div>

<div class="text" id="content">
 
 <?php echo $content;?>
		

</div>


<div class="page-content" ></div>

</div>


</div>	
        
<a class="footer" href="#news" target="_self"><span class="top">返回顶部</span></a>

</div>


</body>
</html>