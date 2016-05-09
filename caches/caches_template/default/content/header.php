<?php defined('IN_PHPCMS') or exit('No permission resources.'); ?><!DOCTYPE HTML>
<html>
<head>
 <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
 <meta http-equiv="pragma" content="no-cache"> 
 <meta http-equiv="Cache-Control" content="no-cache, must-revalidate"> 
 <meta http-equiv="expires" content="Wed, 26 Feb 1997 08:21:57 GMT">
 <meta http-equiv="expires" content="0">

 <title><?php if(isset($SEO['title']) && !empty($SEO['title'])) { ?><?php echo $SEO['title'];?><?php } ?><?php echo $SEO['site_title'];?></title>
 <meta name="keywords" content="<?php echo $SEO['keyword'];?>">
 <meta name="description" content="<?php echo $SEO['description'];?>">
<link type="text/css" rel="stylesheet" href="<?php echo CSS_PATH;?>global.css" media="screen" />
<link type="text/css" rel="stylesheet" href="<?php echo CSS_PATH;?>home.css" media="screen" />
<link rel="stylesheet" href="<?php echo CSS_PATH;?>style.css" type="text/css" media="all" />
<link rel="stylesheet" href="<?php echo CSS_PATH;?>lrtk.css" type="text/css" media="all" />
<link rel="stylesheet" href="<?php echo CSS_PATH;?>lrtk2.css" type="text/css" media="all" />
<link rel="stylesheet" href="<?php echo CSS_PATH;?>top.css" type="text/css" media="all" />
<link href="/favicon.ico" rel="shortcut icon">  
  <!--[if lt IE 9]>
  <script type="text/javascript" src="<?php echo JS_PATH;?>html5.js"></script>
  <![endif]-->
  <!--[if IE 6]>
    <script type="text/javascript" src="<?php echo JS_PATH;?>CC_belatedPNG.js" ></script>
    <script type="text/javascript">CC_belatedPNG.fix('img,.h-btn,.nav');</script>
  <![endif]-->  
   <script type="text/javascript" src="<?php echo JS_PATH;?>jquery-1.9.1.min.js"></script>
   <script type="text/javascript" src="<?php echo JS_PATH;?>layer.min.js"></script>
  <script type="text/javascript" src="<?php echo JS_PATH;?>jquery.js"></script>
  <script type="text/javascript" src="<?php echo JS_PATH;?>lrtk.js"></script>
  <script type="text/javascript" src="<?php echo JS_PATH;?>jquery2.js"></script>
  <script type="text/javascript" src="<?php echo JS_PATH;?>lrtk2.js"></script>
  <script type="text/javascript" src="<?php echo JS_PATH;?>jquery-1.6.2.min.js"></script>
  <script type="text/javascript" src="<?php echo JS_PATH;?>common.js"></script>
  <script type="text/javascript" src="<?php echo JS_PATH;?>util.js"></script>
 <script type="text/javascript" src="<?php echo JS_PATH;?>base.js"></script>
  <script type="text/javascript" src="<?php echo JS_PATH;?>Validform_v5.2.1_min.js"></script>

  <script>

  //if(navigator.userAgent.match(/.*Mobile.*/)){
/*	if(window.location.href=="<?php echo siteurl($siteid);?>" || window.location.href=="<?php echo siteurl($siteid);?>/" || window.location.href=="<?php echo siteurl($siteid);?>/index.php" || window.location.href=="<?php echo siteurl($siteid);?>/index.html")
	window.location.href="<?php echo siteurl($siteid);?>/index.php?m=wap&siteid=1";
  }

   if(/AppleWebKit.*mobile/i.test(navigator.userAgent) || (/MIDP|SymbianOS|NOKIA|SAMSUNG|LG|NEC|TCL|Alcatel|BIRD|DBTEL|Dopod|PHILIPS|HAIER|LENOVO|MOT-|Nokia|SonyEricsson|SIE-|Amoi|ZTE/.test(navigator.userAgent))){
    if(window.location.href.indexOf("?mobile")<0){
        try{
            if(/Android|webOS|iPhone|iPod|BlackBerry/i.test(navigator.userAgent)){
                window.location.href="<?php echo siteurl($siteid);?>/index.php?m=wap&siteid=1";
            }else if(/iPad/i.test(navigator.userAgent)){
                window.location.href="<?php echo siteurl($siteid);?>/index.php?m=wap&siteid=1";
            }else{
                window.location.href="<?php echo siteurl($siteid);?>/index.php?m=wap&siteid=1"
            }
        }catch(e){}
    }
}*/


  </script>

  <style type="text/css">
  .scrollDiv{height:406px;/* 必要元素 */line-height:25px; overflow:hidden;/* 必要元素 */ width: 97%; margin: 0 auto;}
.scrollDiv li{height:130px;padding-left:10px; margin-top:5px; border-bottom:#ccc 1px dashed;}
ul,li{list-style-type:none;margin:0px;}

.dropdown-menu {
	left: 210px; background-color: rgb(241, 241, 241);
	border-radius: 0px;
	width:180px;
}
.dropdown-menu li{
	list-style:none;
	line-height:25px;
}

.weixin{ width:30px; height:24px; float:left; position:relative; font-size:12px; text-align:center;}
.weixin a{width:30px; height:24px; display:block; position:absolute; left:0; top:0;background:url(<?php echo IMG_PATH;?>h_weixin.png); }
.weixin .weixin_nr{width:120px; height:140px; padding:10px; background:#fff; text-align:center; position:absolute; left:-45px; top:45px; display:none;}
.weixin .weixin_nr img{ margin-bottom:5px;}
.weixin .weixin_nr .arrow{ width:0; height:0; border-bottom:10px solid #fff;border-left:10px solid transparent;border-right:10px solid transparent; position:absolute; left:50px; top:-10px;}
.weixin.on .weixin_nr{ display:block;}
.weixin.on a{ background:url(<?php echo IMG_PATH;?>h_weixin.png));}

.wtel{ width:26px; height:24px; margin-left:10px; float:left; position:relative; font-size:12px; text-align:center;}
.wtel a{width:16px; height:24px; display:block; position:absolute; left:0; top:0;background:url(<?php echo IMG_PATH;?>mb.jpg); }
.wtel .wtel_nr{width:120px; height:140px; padding:10px; background:#fff; text-align:center; position:absolute; left:-45px; top:45px; display:none;}
.wtel .wtel img{ margin-bottom:5px;}
.wtel .wtel_nr .arrow{ width:0; height:0; border-bottom:10px solid #fff;border-left:10px solid transparent;border-right:10px solid transparent; position:absolute; left:50px; top:-10px;}
.wtel.on .wtel_nr{ display:block;}
.wtel.on a{ background:url(<?php echo IMG_PATH;?>mb.jpg));}

.weibo{ width:28px; height:24px; margin-left:2px; float:left; position:relative; font-size:12px; text-align:center;}
.weibo a{width:28px; height:24px; display:block; position:absolute; left:0; top:0;background:url(<?php echo IMG_PATH;?>weibo.png); }
.weibo .weibo_nr{width:120px; height:140px; padding:10px; background:#fff; text-align:center; position:absolute; left:-45px; top:45px; display:none;}
.weibo .weibo img{ margin-bottom:5px;}
.weibo .weibo_nr .arrow{ width:0; height:0; border-bottom:10px solid #fff;border-left:10px solid transparent;border-right:10px solid transparent; position:absolute; left:50px; top:-10px;}
.weibo.on .weibo_nr{ display:block;}
.weibo.on a{ background:url(<?php echo IMG_PATH;?>weibo.png));}
 </style>

</head>
<body id="channel1">
	<div class="wrap">
    	
 <script language="javascript"> 

/*
function setHomePage1(o){ 
 try {    
	o.style.behavior='url(#default#homepage)';
	o.setHomePage('<?php echo siteurl($siteid);?>/');
     } catch (e) {    
    dialog_alert("你的浏器览不支持设为首页操作，请自行设置!");
   }
} 
 
function addFavorite(){ 
 try {    
	window.external.AddFavorite("<?php echo siteurl($siteid);?>/","讯立快运  - 您的私人快运公司");
     } catch (e) {    
    dialog_alert("你的浏器览不支持收藏操作，请收藏!");
   }
} */
//设为首页
function SetHome(obj,url){
    try{
        obj.style.behavior='url(#default#homepage)';
        obj.setHomePage(url);
    }catch(e){
        if(window.netscape){
            try{
                netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
            }catch(e){
                alert("抱歉，此操作被浏览器拒绝！\n\n请在浏览器地址栏输入“about:config”并回车然后将[signed.applets.codebase_principal_support]设置为'true'");
            }
        }else{
            alert("抱歉，您所使用的浏览器无法完成此操作。\n\n您需要手动将【"+url+"】设置为首页。");
        }
    }
}
//收藏本站
function AddFavorite(title, url) {
    try {
        window.external.addFavorite(url, title);
    }
    catch (e) {
        try {
            window.sidebar.addPanel(title, url, "");
        }
        catch (e) {
            alert("抱歉，您所使用的浏览器无法完成此操作。\n\n加入收藏失败，请使用Ctrl+D进行添加");
        }
    }
}
//保存到桌面
function toDesktop(sUrl,sName){
try {
    var WshShell = new ActiveXObject("WScript.Shell");
    var oUrlLink =          WshShell.CreateShortcut(WshShell.SpecialFolders("Desktop")     + "\\" + sName + ".url");
    oUrlLink.TargetPath = sUrl;
    oUrlLink.Save();
    }  
catch(e)  {  
          alert("当前IE安全级别不允许操作！");  
}
}    
 
</script> 

 
	<!--S 头部-->
    	<header class="header" style="border-bottom:1px solid #fff;">
		
        	<div class="layout header-mod clearfix">
            	<!--S 头部栏目-->
            	<div class="top-nav">
				
                	<ul class="top-nList clearfix">
						<li style="width:255px;">
							<div class="t_news">
							<b id="top">今日汇率：</b>
							<ul onMouseOver="document.all.menulayer_1.style.visibility=''" onMouseOut="document.all.menulayer_1.style.visibility='hidden'" class="news_li">
							<?php if(defined('IN_ADMIN')  && !defined('HTML')) {echo "<div class=\"admin_piao\" pc_action=\"content\" data=\"op=content&tag_md5=446674c84fa49422c9f49a9710e10246&action=lists&catid=27&num=16&siteid=%24siteid&order=id+ASC\"><a href=\"javascript:void(0)\" class=\"admin_piao_edit\">修改</a>";}$content_tag = pc_base::load_app_class("content_tag", "content");if (method_exists($content_tag, 'lists')) {$data = $content_tag->lists(array('catid'=>'27','siteid'=>$siteid,'order'=>'id ASC','limit'=>'16',));}?>
								<?php $n=1;if(is_array($data)) foreach($data AS $r) { ?>
								<li style="color:red;"><?php echo $r['title'];?></li>
								<?php $n++;}unset($n); ?>
							<?php if(defined('IN_ADMIN') && !defined('HTML')) {echo '</div>';}?>
							</ul>
							<ul class="swap"></ul>
							</div>
						</li>

						<li style="width:185px;padding-right:15px;">
						<script type="text/javascript">document.write('<iframe src="<?php echo APP_PATH;?>index.php?m=member&c=index&a=mini&forward='+encodeURIComponent(location.href)+'&siteid=<?php echo get_siteid();?>" allowTransparency="true"  width="200" height="24" frameborder="0" scrolling="no"></iframe>')</script>
						</li>
						
 
                        <!--新消息
                        <li class="h-nLine">|</li>
                        <li><a href="###" class="h-yMessage radius-three">消息<em>2</em></a></li>
                        -->
                        <li class="h-nLine">|</li>
                        <li><a href="javascript:void(0)" onclick="SetHome(this,'<?php echo APP_PATH;?>');">设为首页</a></li>
                        <li class="h-nLine">|</li>
                        <li><a href="javascript:void(0)" onclick="AddFavorite('<?php echo $SEO['title'];?>',location.href)">加入收藏</a></li>
                    </ul>
                </div>
                <!--E 头部栏目-->
				<?php $sinfo=siteinfo(1);?>
            	<h1 class="fl logo"><a href="<?php echo siteurl($siteid);?>/" title="<?php echo $SEO['site_title'];?>" style="background:url(<?php echo $sinfo['logo'];?>);"><?php echo $SEO['site_title'];?></a></h1>
               
			<div style="float:left; padding-top: 50px; padding-left: 160px; font-family: 宋体; font-size: 18px; color:#747474;">统一客服热线：<?php echo $sinfo['public_tel'];?></div>
			<div style="float:right; padding-top:48px; width:103px;">
				

                        <?php if(defined('IN_ADMIN')  && !defined('HTML')) {echo "<div class=\"admin_piao\" pc_action=\"get\" data=\"op=get&tag_md5=ccc805d662e02fd7d11df6a7c4fabc06&sql=SELECT+%2A+from+t_poster+where+spaceid%3D11+and+disabled%3D0+order+by+listorder+&num=3&return=addata\"><a href=\"javascript:void(0)\" class=\"admin_piao_edit\">修改</a>";}pc_base::load_sys_class("get_model", "model", 0);$get_db = new get_model();$r = $get_db->sql_query("SELECT * from t_poster where spaceid=11 and disabled=0 order by listorder  LIMIT 3");while(($s = $get_db->fetch_next()) != false) {$a[] = $s;}$addata = $a;unset($a);?>
						<?php $k=0;?>
						<?php $n=1;if(is_array($addata)) foreach($addata AS $rs) { ?>
						<?php $settings=string2array($rs[setting])?>
					   <div class="<?php if($k==0) { ?>weixin<?php } elseif ($k==1) { ?>wtel<?php } elseif ($k==2) { ?>weibo<?php } ?>" onmouseover="this.className = '<?php if($k==0) { ?>weixin on<?php } elseif ($k==1) { ?>wtel on<?php } elseif ($k==2) { ?>weibo on<?php } ?>';" onmouseout="this.className = '<?php if($k==0) { ?>weixin<?php } elseif ($k==1) { ?>wtel<?php } elseif ($k==2) { ?>weibo<?php } ?>';">
							<a href="javascript:;"></a>
							<div class="<?php if($k==0) { ?>weixin_nr<?php } elseif ($k==1) { ?>wtel_nr<?php } elseif ($k==2) { ?>weibo_nr<?php } ?>">
								<div class="arrow"></div>
								<img src="<?php echo $settings['1']['imageurl'];?>" width="120" height="120" />
								<?php echo $settings['1']['alt'];?>
							</div>
						</div>
						<?php $k++;?>
						<?php $n++;}unset($n); ?>
						<?php if(defined('IN_ADMIN') && !defined('HTML')) {echo '</div>';}?>
					
                    
			</div>

			
          	
            </div><!--E .header-mod-->
        </header>
        <!--E 头部-->
        <!--S 导航-->
        <div class="nav" style="background:#f6f6f6;">
        	<div class="nav-mod radius-three">
            	<div class="nav-main" style="margin-left:8px;">
                	<ul class="nav-mList clearfix" id="J_NavList">
                	<li data-id="0" <?php if(!$catid) { ?>class="current"<?php } ?>><a href="<?php echo siteurl($siteid);?>/" title="首页" >首页</a></li>
					<?php
					$ccatid=$catid;
					if($ccatid==2)
						$ccatid=11;
					if($ccatid==6)
						$ccatid=12;
					?>
               
<style>
*{ margin:0; padding:0; list-style:none;}
.nav-mList li .second a{background:#9eb40a; padding:0 33px; border-top:1px solid #f6f6f6;}
.nav-mList li .second a:hover{background:#f6f6f6; padding:0 32px; }
.nav-mList li .second{position:absolute;left:0;display:none; }
</style>
				<?php if(defined('IN_ADMIN')  && !defined('HTML')) {echo "<div class=\"admin_piao\" pc_action=\"content\" data=\"op=content&tag_md5=da4e9d0bec1f05caf76032527da518c4&action=category&catid=0&num=7&siteid=%24siteid\"><a href=\"javascript:void(0)\" class=\"admin_piao_edit\">修改</a>";}$content_tag = pc_base::load_app_class("content_tag", "content");if (method_exists($content_tag, 'category')) {$data = $content_tag->category(array('catid'=>'0','siteid'=>$siteid,'limit'=>'7',));}?>
				<?php $n=1;if(is_array($data)) foreach($data AS $r) { ?>
				<?php if($r[catid]!=8) { ?>
                	<li data-id="<?php echo $r['catid'];?>" <?php if($ccatid==$r[catid]) { ?>class="current"<?php } ?>>
                	<a href="<?php echo $r['url'];?>" title="<?php echo $r['catname'];?>" target="_top"><?php echo $r['catname'];?></a>
					<?php if(defined('IN_ADMIN')  && !defined('HTML')) {echo "<div class=\"admin_piao\" pc_action=\"content\" data=\"op=content&tag_md5=696bf3aabb4119ae8b381f6d942f7bb0&action=category&catid=%24r%5Bcatid%5D&num=7&return=data2\"><a href=\"javascript:void(0)\" class=\"admin_piao_edit\">修改</a>";}$content_tag = pc_base::load_app_class("content_tag", "content");if (method_exists($content_tag, 'category')) {$data2 = $content_tag->category(array('catid'=>$r[catid],'limit'=>'7',));}?>
						<?php if($data2) { ?>
						<div class="second">
						<?php $n=1;if(is_array($data2)) foreach($data2 AS $r2) { ?>
							<a href='$r2[url]}'><?php echo $r2['catname'];?></a>
						<?php $n++;}unset($n); ?>
						</div>
						<?php } ?>
						<?php if(defined('IN_ADMIN') && !defined('HTML')) {echo '</div>';}?>
						
                	</li>
				<?php } ?>
                <?php $n++;}unset($n); ?>
				<?php if(defined('IN_ADMIN') && !defined('HTML')) {echo '</div>';}?>
                	
 <script src="http://libs.baidu.com/jquery/1.9.1/jquery.min.js"></script>
<script>
$(function(){
	var lanrenzhijia = 0; // 默认值为0，二级菜单向下滑动显示；值为1，则二级菜单向上滑动显示
	if(lanrenzhijia ==0){
		$('.nav-mList li').hover(function(){
			$('.second',this).css('top','39px').show();
		},function(){
			$('.second',this).hide();
		});
	}else if(lanrenzhijia ==1){
		$('.nav-mList li').hover(function(){
			$('.second',this).css('bottom','30px').show();
		},function(){
			$('.second',this).hide();
		});
	}
});
</script>               	
 
                    </ul>
                </div><!--E .main-nav-->
            </div><!--E .nav-mod-->
        </div>
		<div style="background:url(/resource/images/nav_bottom3.png) repeat-x; height:15px;"></div>
        <!--E 导航-->
		</div>