<?php defined('IN_PHPCMS') or exit('No permission resources.'); ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta property="qc:admins" content=""/>
        <meta property="wb:webmaster" content="" />
        <title><?php echo L('member','','member').L('manage_center');?></title>



<?php if(isset($_GET[flag]) && $_GET[flag]==1) { ?>

<link type="text/css" rel="stylesheet" href="/resource/css/global.css" media="screen" />
<link type="text/css" rel="stylesheet" href="/resource/css/home.css" media="screen" />
<link rel="stylesheet" href="/resource/css/style.css" type="text/css" media="all" />
<script type="text/javascript" src="<?php echo JS_PATH;?>jquery-1.7.2.min.js"></script>
<script src="<?php echo JS_PATH;?>jquery-ui.min.js"></script>
<script src="<?php echo JS_PATH;?>jquery.formvalidator.js"></script><!--jquery表单验证插件-->
<script src="<?php echo JS_PATH;?>checkform.js"></script>
<script type="text/javascript"> 
				function loginchk(){
					if($("#username").val()=="")
					{
						 dialog_alert("请输入账号！");
						 $("#username").focus();
						 return false;
					}

					if($("#password").val()=="")
					{
						 dialog_alert("请输入密码！");
						 $("#password").focus();
						 return false;
					}
					$('#loginform').submit();
					return true;
					location.reload();
				}
				</script>

				<?php if($_username) { ?>  
				<body style="background-color:transparent;  text-align:left; ">
					<a style="line-height:23px;">欢迎您, <?php echo $_username;?></a> <br />
					您的帐户余额<strong>
					<font style="color:red;">
					<?php
					$uinfo=  $this->get_current_userinfo();;
					echo $uinfo[amount];
					?>
					</font>
				 元</strong>，<a href="/index.php?m=pay&c=deposit&a=pay" class="p-btn radius-five" title="充值" style="line-height:28px; color:red;" target="_parent">充&nbsp;值</a><br>

					 <a href="/index.php?m=package&c=index&a=init&hid=15" target="_parent">未入库<font color="#ff6600">(<?php echo $status1count;?>)</font></a>&nbsp;&nbsp;
					 <a href="/index.php?m=package&c=index&a=housemanage" target="_parent">已入库<font color="#ff6600">(<?php echo $status3count;?>)</font></a>&nbsp;&nbsp;
					 <a href="/index.php?m=package&c=index&a=sendedmanage" target="_parent">待处理<font color="#ff6600">(<?php echo $status21count;?>)</font></a>&nbsp;&nbsp;
					 
					<a href="/index.php?m=package&c=index&a=nopaypackage" target="_parent">未付款<font color="#ff6600">(<?php echo $status8count;?>)</font></a>
					<br />
					<a href="/index.php?m=waybill&c=index&a=init&hid=0&status=7" target="_parent">已付款<font color="#ff6600">(<?php echo $status7count;?>)</font></a>&nbsp;&nbsp;
					<a href="/index.php?m=waybill&c=index&a=init&hid=0&status=14" target="_parent">已发货<font color="#ff6600">(<?php echo $status14count;?>)</font></a>
					<br>
					<a href="<?php echo APP_PATH;?>index.php?m=member&siteid=<?php echo $siteid;?>" target="_parent"><img src="<?php echo IMG_PATH;?>member.jpg" style="padding-top:8px;"></a><br>
					<a href="<?php echo APP_PATH;?>index.php?m=member&c=index&a=logout&forward=<?php echo urlencode($_GET['forward']);?>&siteid=<?php echo $siteid;?>" target="_top" style="line-height:33px;">退出登录</a>&nbsp;&nbsp;
				<?php } else { ?> 
					<form action="/index.php?m=member&c=index&a=login&siteid=<?php echo $siteid;?>" name="loginform" id="loginform" method="post" target="_parent">
					<input type="hidden" name="dosubmit" value="1" />
				
                    <div class="textarea-mod" style="font-size:16px;">
					<p >
                    	账&nbsp;&nbsp;号： <input class="inp-tarea" style="ime-mode:disabled;height:25px;width:190px;" name="username" id="username" type="text" />
                    </p>
					<p style="margin-top:20px;">
                    	密&nbsp;&nbsp;码： <input class="inp-tarea" style="ime-mode:disabled;height:25px;width:190px;" name="password" id="password" type="password" />
                    </p>
					
					</div>
                    <!--update 2013-3-18--> 
                    <div class="btn-search-mod clearfix" style="margin-top:17px;">
                       
                        <button type="button" onclick="javascript:window.open('/index.php?m=member&c=index&a=register&siteid=<?php echo $siteid;?>','_blank');" class="h-btn-search radius-three fr" title="免费注册"> 免费注册 </button>  
						 <button type="button" onclick="loginchk();"  class="h-btn-search  radius-three fr" title="用户登录"> 用户登录 </button>
                    </div><!--E .btn-search-mod-->
                    <!--update 2013-3-18-->
                    </form> 
				<?php } ?>


<?php } else { ?>

<body style="background-color:transparent;  text-align:right; ">
<link rel="stylesheet" type="text/css" href="<?php echo CSS_PATH;?>global2.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo CSS_PATH;?>profile.css"/>
<script type="text/javascript" src="<?php echo JS_PATH;?>jquery-1.7.2.min.js"></script>
<script src="<?php echo JS_PATH;?>jquery-ui.min.js"></script>
<script src="<?php echo JS_PATH;?>jquery.formvalidator.js"></script><!--jquery表单验证插件-->
<script src="<?php echo JS_PATH;?>checkform.js"></script>
<style type="text/css">
        .index-pop{background-color: #eee;}
        .index-pop .slide-box{width: 1490px;margin: 0 auto;}
   
</style>

<style type="text/css">
/*顶部导航*/
.top-menu{background: url() repeat-x center center #e8e8e8;height: 32px;line-height: 32px;color: #666666;position:fixed;width:100%;z-index:1000}
.top-menu .site-list li{padding: 0 0 0 25px;float: left;}
.top-menu .site-list li.time{padding-left:42px;background: url("<?php echo IMG_PATH;?>time.png") no-repeat 23px 8px;}
.top-menu .site-list li a{color: #666666;}
.header{background-color: #FFF;border-bottom: 1px solid #d1d1d1;font-family: "微软雅黑";overflow:hidden;}
.header-box{position: relative;height: 100px;margin-top:32px;}
.header-box .logo{position: absolute;z-index: 1;top:38px;font-size: 38px;left: 0px;}
.header-box .nav{position: absolute;z-index: 1;top:76px;left: 342px;}
.header-box .nav li{float: left;margin:0 10px;position:relative;}
.header-box .nav li .hot{position:absolute;right:0px;top:-22px;width:35px;height:22px; background: url("<?php echo IMG_PATH;?>hot.jpg") center center no-repeat}
.header-box .nav li a{width: 103px;height: 54px;display: block;font-size: 14px;color: #575757;text-align: center;font-weight: bold;}
.header-box .nav li a:hover{text-decoration: none;}
.header-box .nav li.on a{width: 103px;height: 54px;background: url("<?php echo IMG_PATH;?>nav-border.jpg") no-repeat 0 bottom;}
#top_banner{position:relative;}
.close-banner {
position: absolute;
top: 7px;
right: 12px;
width: 20px;
height: 20px;
background: #fff;
filter: alpha(opacity=0);
opacity: 0;
cursor: pointer;
}
.footer{border-top:1px solid #ededed;color: #c9c9c9;font-size: 14px;text-align: center;margin-top: 50px;}
.footer .copyright{line-height: 100px;}
.login-box{position: absolute;right:0;top: 66px;z-index:999;}
.login-box .userinfo{margin: 9px 0 0 1px;}

.ht-btn {display:inline-block;color:#FFF;border-radius:4px;padding:0px 1px;cursor:pointer;height:20px;line-height:20px}
.btn-red{color:red;}

</style>

<?php if($_username) { ?>  <a href="<?php echo APP_PATH;?>index.php?m=member&siteid=<?php echo $siteid;?>" target="_parent"><?php echo $_username;?>,</a> <a href="<?php echo APP_PATH;?>index.php?m=member&c=index&a=logout&forward=<?php echo urlencode($_GET['forward']);?>&siteid=<?php echo $siteid;?>" target="_top">[退出]</a><?php } else { ?> 还没有账号？赶紧 <a class="ht-btn btn-red" href="/index.php?m=member&c=index&a=register&siteid=1" target="_parent">注册</a> 吧！
				<a class="ht-btn btn-red" href="/index.php?m=member&c=index&a=login&forward=&siteid=1" target="_parent">登录</a>
<?php } ?>
<?php } ?>
</body>
</html>