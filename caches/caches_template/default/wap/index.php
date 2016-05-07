<?php defined('IN_PHPCMS') or exit('No permission resources.'); ?><!DOCTYPE html>
<html>
    <head>
 <meta charset="utf-8" />
<link rel="stylesheet" type="text/css" href="/resource/wap/css/reset.css" />
<link rel="stylesheet" type="text/css" href="/resource/wap/css/snower.css" />
<link rel="stylesheet" type="text/css" href="/resource/wap/css/common.css" />
<link rel="stylesheet" type="text/css" href="/resource/wap/css/font-awesome.css" />
<link rel="stylesheet" type="text/css" href="/resource/wap/css/home.css" />
<link rel="stylesheet" type="text/css" href="/resource/wap/css/home-menu.css" />
<script type="text/javascript" src="/resource/wap/js/maivl.js"></script>
<script type="text/javascript" src="/resource/wap/js/jquery.js"></script>
<title><?php echo $WAP['sitename'];?></title>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
<meta name="Keywords" content="" />
<meta name="Description" content="" />
        <!-- Mobile Devices Support @begin -->
            <meta content="application/xhtml+xml;charset=UTF-8" http-equiv="Content-Type">
            <meta content="no-cache,must-revalidate" http-equiv="Cache-Control">
            <meta content="no-cache" http-equiv="pragma">
            <meta content="0" http-equiv="expires">
            <meta content="telephone=no, address=no" name="format-detection">
            <meta name="apple-mobile-web-app-capable" content="yes" /> <!-- apple devices fullscreen -->
            <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
        <!-- Mobile Devices Support @end -->
        <link rel="shortcut icon" href="/favicon.png" />
    </head>
    <body onselectstart="return true;" ondragstart="return false;">
        <style type="text/css">
.body{
		display:block;
		background-size:100% 100%;
		background: -moz-linear-gradient(center top,#6FCBEE,#E7F1F1);
		background: -webkit-gradient(linear,0% 0%,0% 100%,from(#6FCBEE),to(#E7F1F1));
		background: -webkit-linear-gradient(center top,#6FCBEE,#E7F1F1);
		background: -o-linear-gradient(center top,#6FCBEE,#E7F1F1);
		filter: progid:DXImageTransform.Microsoft.gradient(GradientType=0,startColorstr=#6FCBEE,endColorstr=#E7F1F1);
		-ms-filter: "progid:DXImageTransform.Microsoft.gradient (GradientType=0,startColorstr=#6FCBEE,endColorstr=#E7F1F1)";
}


</style>
<div class="body">
	  
	<section>
		<div class="box_title">
			<img src="<?php echo $WAP['logo'];?>" border=0/>
		</div>
		<ul class="list_ul" >
			<li class="box">
				<a href="<?php echo $this->__all__urls[1]['about'];?>">
					<center><label><small>关于我们</small></label></center>
					<span class="icon-file-text"></span>
				</a>
				<a href="<?php echo $this->__all__urls[1]['packageinfo'];?>">
					<center><label><small>日淘教程</small></label></center>
					<span class="icon-envelope-alt"></span>
				</a>
				<a href="<?php echo $this->__all__urls[1]['news'];?>">
					<center><label><small>日淘资讯</small></label></center>
					<span class="icon-comment-alt"></span>
				</a>

				
			</li>
			<li class="box">
				<a href="<?php echo $this->__all__urls[1]['package'];?>">
					<center><label><small>新手上路</small></label></center>
					<span class="icon-envelope"></span>
				</a>
				<a href="<?php echo $this->__all__urls[1]['waybillinfo'];?>">
					<center><label><small>在线下单</small></label></center>
					<span class="icon-pencil"></span>
				</a>

				<a href="<?php echo $this->__all__urls[1]['waybill'];?>">
					<center><label><small>我的运单</small></label></center>
					<span class="icon-globe"></span>
				</a>
			</li>
			<li class="box">
				<a href="<?php echo $this->__all__urls[1]['amount'];?>">
					<center><label><small>财务中心</small></label></center>
					<span class="icon-bar-chart"></span>
				</a>

				<a href="<?php echo $this->__all__urls[1]['userinfo'];?>">
					<center><label><small>个人资料</small></label></center>
					<span class="icon-user-md"></span>
				</a>

				<a href="<?php echo $this->__all__urls[1]['address'];?>">
					<center><label><small>运单查询</small></label></center>
					<span class="icon-list-alt"></span>
				</a>
			</li>
						
			</ul>
	</section>
</div>




	<?php include template("wap","footer"); ?>
        			
	</body>
		
</html>

