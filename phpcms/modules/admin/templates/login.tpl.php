<?php defined('IN_ADMIN') or exit('No permission resources.'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET;?>" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<title><?php echo L('phpcms_logon')?></title>
<style type="text/css">
	div{overflow:hidden; *display:inline-block;}div{*display:block;}
	.login_box{
		
		/* background:url(<?php echo IMG_PATH?>admin_img/login_bg.jpg) repeat-x;  width:auto; height:416px; overflow:hidden; position:absolute;  top:20%; left:20%;right:20%;*/

width: 500px;
  height: 300px;
  overflow: hidden;
  position: absolute;
  top: 20%;
  left: 30%;
  border: 1px #0079ad solid;

	}
	.login_iptbox{/*bottom:90px;_bottom:72px; color:#FFFFFF;font-size:12px;height:30px;left:50%;margin-left:-280px;position:absolute;width:560px; overflow:visible; */
	display: block;
  font-size: 12px;
  height: 200px;
  right: 25%;
  position: absolute;
  top: 80px;
  width: 370px;

	}
	.login_iptbox .ipt{
	/* 	height:24px; width:110px; margin-right:22px; color:#fff; background:url(<?php echo IMG_PATH?>admin_img/ipt_bg.jpg) repeat-x; *line-height:24px; border:none; color:#000; overflow:hidden;
		 */
		}
	<?php if(SYS_STYLE=='en'){ ?>
	.login_iptbox .ipt{width:100px; margin-right:12px;}
	<?php }?>
	.login_iptbox label{ *position:relative; *top:-6px;width: 50px;text-align: right;}
	.login_iptbox li{list-style: none;    height: 50px;  text-align: right;}
	.login_iptbox .ipt_reg{
		/*margin-left:12px;width:46px; margin-right:16px; background:url(<?php echo IMG_PATH?>admin_img/ipt_bg.jpg) repeat-x; *overflow:hidden;text-align:left;padding:2px 0 2px 5px;font-size:16px;font-weight:bold;
		*/
		}
	.login_tj_btn{ background:url(<?php echo IMG_PATH?>admin_img/login_dl_btn.jpg) no-repeat 0px 0px; width:52px; height:24px; margin-left:16px; border:none; cursor:pointer; padding:0px; float:right;}
	.yzm{position:absolute; background:url(<?php echo IMG_PATH?>admin_img/login_ts140x89.gif) no-repeat; width:140px; /* height:89px; */ text-align:center; font-size:12px; display:none;
	  left: 5px;    top: 40px;
	
	}
	.yzm a:link,.yzm a:visited{color:#036;text-decoration:none;}
	.yzm a:hover{color:#C30;}
	.yzm img{cursor:pointer; margin:4px auto 7px; width:130px; height:50px; border:1px solid #fff;}
	.cr{font-size:12px;font-style:inherit;text-align:center;color:#ccc;width:100%; position:absolute; bottom:58px;}
	.cr a{color:#ccc;text-decoration:none;}
	.l-mod-hd{  padding: 18px 0 10px 18px;color:#fff;
  background: #0079ad;}
</style>
<script language="JavaScript">
<!--
	if(top!=self)
	if(self!=top) top.location=self.location;
//-->
</script>
</head>

<body onload="javascript:document.myform.username.focus();">
<div id="login_bg" class="login_box">
<div class="l-mod-hd">后台登录</div>
<div class="login_iptbox">
   	 <form action="index.php?m=admin&c=index&a=login&dosubmit=1" method="post" name="myform">
	 <li>
		 <label><?php echo L('username')?>：</label><input name="username" type="text" class="ipt" value="" />
	 </li>
	 
	 <li>
		 <label><?php echo L('password')?>：</label><input name="password" type="password" class="ipt" value="" />
	 </li>
	 <li>
		 <label><?php echo L('security_code')?>：</label><input name="code" type="text" class="ipt ipt_reg" onfocus="document.getElementById('yzm').style.display='block'" />
	 </li>
	 <li>
	    <input name="dosubmit" value="登录" type="submit" style="  padding: 5px 10px;  width: 173px;  font-size: 16px; float:right;"/>
	 </li>
    <div id="yzm" class="yzm"><?php echo form::checkcode('code_img')?><br /><a href="javascript:document.getElementById('code_img').src='<?php echo SITE_PROTOCOL.SITE_URL.WEB_PATH;?>api.php?op=checkcode&m=admin&c=index&a=checkcode&time='+Math.random();void(0);"><?php echo L('click_change_validate')?></a></div>
  
	 </form>
    </div>
    <!---<div class="cr"><?php echo L("copyright")?></div>---->
</div>
</body>
</html>