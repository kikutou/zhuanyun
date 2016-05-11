<?php defined('IN_PHPCMS') or exit('No permission resources.'); ?><!DOCTYPE HTML>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta name="keywords" content="" />
  <meta name="description" content="" />
  <title><?php echo L('member','','member').L('manage_center');?></title>
  <link type="text/css" rel="stylesheet" href="<?php echo CSS_PATH;?>global.css" media="screen" />
  <link rel="stylesheet" href="<?php echo CSS_PATH;?>style.css" type="text/css" media="all" />
  <link rel="stylesheet" href="<?php echo CSS_PATH;?>lrtk.css" type="text/css" media="all" />

  <!--[if lt IE 9]>
  <script type="text/javascript" src="<?php echo JS_PATH;?>html5.js"></script>
  <![endif]-->
  <!--[if IE 6]>
    <script type="text/javascript" src="<?php echo JS_PATH;?>CC_belatedPNG.js" ></script>
    <script type="text/javascript">CC_belatedPNG.fix('img,.h-btn,.nav');</script>
  <![endif]-->  
  <script type="text/javascript" src="<?php echo JS_PATH;?>jquery.js"></script>
  <script type="text/javascript" src="<?php echo JS_PATH;?>lrtk.js"></script>
  <script type="text/javascript" src="<?php echo JS_PATH;?>jquery-1.6.2.min.js"></script>
  <script type="text/javascript" src="<?php echo JS_PATH;?>common.js"></script>
  <script type="text/javascript" src="<?php echo JS_PATH;?>util.js"></script>
  <!--<script type="text/javascript" src="<?php echo JS_PATH;?>Validform_v5.2.1_min.js"></script>-->
  <script type="text/javascript" src="<?php echo JS_PATH;?>cookie.js"></script>
  <script type="text/javascript" src="<?php echo JS_PATH;?>dialog.js"></script>
  <script type="text/javascript" src="<?php echo JS_PATH;?>formvalidator.js" charset="UTF-8"></script>
  <script type="text/javascript" src="<?php echo JS_PATH;?>formvalidatorregex.js" charset="UTF-8"></script>
  
  <link href="/favicon.ico" rel="shortcut icon">
<style type="text/css">
    body{ background-color:#f2f2f2;}
    .header{ height:100px; background-color:#fff; border-bottom:4px solid #9eb40a;-moz-box-shadow:0 1px 0 rgba(255, 255, 255, 0.1);-webkit-box-shadow:0 1px 0 rgba(255, 255, 255, 0.1);box-shadow:0 1px 0 rgba(255, 255, 255, 0.1);}
      
        .btn-zone{ right:30px; top:30px;}
    
    .content{ height:540px; background:url(<?php echo IMG_PATH;?>big-bg.jpg) no-repeat center center; border-bottom:1px solid #BBB;}
    .l-mod{ position:relative;}
      .ms-mod{ padding-bottom:40px; width:520px;background: none repeat scroll 0 0 rgba(255, 255, 255, 0.85); position:absolute; top:50px; right:-120px;}
      .l-mod-hd{ padding:28px 0 0 18px;}
        .l-mod-hd h3{ color:#333; font-size:16px; font-weight:bold;}
      
      .l-mod-bd{ padding:22px 0 0 18px;}
        .login-form-list li{ padding-bottom:24px;}
        .login-form-list label{ display:inline-block; padding-right:10px; width:50px; text-align:right; font-weight:bold; color:#666;}
        .login-form-list .inp{ height:36px; line-height:36px; border-color:#ccc;}
        
        .rem-text{ padding-bottom:5px !important;}
        .rem-text .login-v01{ padding-left:60px;}
          .rem-text .login-v01 label{ width:80px;}
       .submit-cont{ padding-bottom:8px !important}
       .l-mod-remark,.submit-cont{ padding-left:60px;}
         .submit-cont .btn-login{ padding:10px 30px; width:260px; font-size:16px;}
         
       .l-mod-remark a,.l-mod-remark a:hover{ color:#F60; font-size:14px; font-weight:bold}
    .footer, .header{ background-image:none;}

  </style>
   <style type="text/css">
  .scrollDiv{height:271px;/* 必要元素 */line-height:25px; overflow:hidden;/* 必要元素 */ width: 97%; margin: 0 auto;}
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
  <link href="<?php echo CSS_PATH;?>table_form.css" rel="stylesheet" type="text/css" />

  <script language="JavaScript">
<!--
$(function(){
	$.formValidator.initConfig({autotip:true,formid:"myform",onerror:function(msg){}});
	$("#username").formValidator({onshow:"<?php echo L('input').L('username');?>",onfocus:"<?php echo L('between_2_to_20');?>"}).inputValidator({min:2,max:20,onerror:"<?php echo L('between_2_to_20');?>"}).regexValidator({regexp:"ps_username",datatype:"enum",onerror:"<?php echo L('username').L('format_incorrect');?>"});
	$("#password").formValidator({onshow:"<?php echo L('input').L('password');?>",onfocus:"<?php echo L('password').L('between_6_to_20');?>"}).inputValidator({min:6,max:20,onerror:"<?php echo L('password').L('between_6_to_20');?>"});

});
//-->
</script>
<script language="javascript"> 


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
  
 
</script>
</head>
<body id="channel">
	<div class="wrap login">
    	<!--S 头部->
    	<header class="header">
        	<div class="layout header-mod clearfix">
            	<h1 class="fl logo"><a href="<?php echo $siteinfo['domain'];?>" title="返回首页">返回首页</a></h1>
                <a href="<?php echo $siteinfo['domain'];?>" title="返回首页" class="h-btn btn-zone">返回首页</a>
            </div><!--E .header-mod->
        </header>
        <!--E 头部-->
		<header class="header" style="border-bottom:4px solid #9eb40a; background:#f6f6f6">
		
        	<div class="layout header-mod clearfix">
            	<!--S 头部栏目-->
            	<div class="top-nav">
				
                	<ul class="top-nList clearfix">
						<li style="width:255px;">
							<div class="t_news">
							<b id="top">今日汇率：</b>
							<ul onMouseOver="document.all.menulayer_1.style.visibility=''" onMouseOut="document.all.menulayer_1.style.visibility='hidden'" class="news_li">
							<?php if(defined('IN_ADMIN')  && !defined('HTML')) {echo "<div class=\"admin_piao\" pc_action=\"content\" data=\"op=content&tag_md5=446674c84fa49422c9f49a9710e10246&action=lists&catid=27&num=16&siteid=%24siteid&order=id+ASC\"><a href=\"javascript:void(0)\" class=\"admin_piao_edit\">编辑</a>";}$content_tag = pc_base::load_app_class("content_tag", "content");if (method_exists($content_tag, 'lists')) {$data = $content_tag->lists(array('catid'=>'27','siteid'=>$siteid,'order'=>'id ASC','limit'=>'16',));}?>
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
				
                        <?php if(defined('IN_ADMIN')  && !defined('HTML')) {echo "<div class=\"admin_piao\" pc_action=\"get\" data=\"op=get&tag_md5=ccc805d662e02fd7d11df6a7c4fabc06&sql=SELECT+%2A+from+t_poster+where+spaceid%3D11+and+disabled%3D0+order+by+listorder+&num=3&return=addata\"><a href=\"javascript:void(0)\" class=\"admin_piao_edit\">编辑</a>";}pc_base::load_sys_class("get_model", "model", 0);$get_db = new get_model();$r = $get_db->sql_query("SELECT * from t_poster where spaceid=11 and disabled=0 order by listorder  LIMIT 3");while(($s = $get_db->fetch_next()) != false) {$a[] = $s;}$addata = $a;unset($a);?>
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
        <!--S 内容-->
       <?php if(defined('IN_ADMIN')  && !defined('HTML')) {echo "<div class=\"admin_piao\" pc_action=\"get\" data=\"op=get&tag_md5=2d3cd2a11f792cd3f676c5f03aa4d2df&sql=SELECT+%2A+from+t_poster+where+spaceid%3D13+and+disabled%3D0+order+by+listorder+&num=5&return=addata2\"><a href=\"javascript:void(0)\" class=\"admin_piao_edit\">编辑</a>";}pc_base::load_sys_class("get_model", "model", 0);$get_db = new get_model();$r = $get_db->sql_query("SELECT * from t_poster where spaceid=13 and disabled=0 order by listorder  LIMIT 5");while(($s = $get_db->fetch_next()) != false) {$a[] = $s;}$addata2 = $a;unset($a);?>
		<?php $k=0;?>
		<?php $n=1;if(is_array($addata2)) foreach($addata2 AS $rr) { ?>
			<?php $settings=string2array($rr[setting])?>
        <article class="content" style="background:url(<?php echo $settings['1']['imageurl'];?>) no-repeat center center;">
		<?php $k++;?>
		<?php $n++;}unset($n); ?>
		<?php if(defined('IN_ADMIN') && !defined('HTML')) {echo '</div>';}?>
          <div class="layout l-mod">
            <div class="ms-mod">
          	<div class="l-mod-hd">
            	<h3>登录系统</h3>
            </div><!--E .l-mod-hd-->
            <div class="l-mod-bd">
            	<div class="login-form-mod">
                <form action="" method="post" onsubmit="save_username();" id="myform" name="myform">
				<input type="hidden" name="forward" id="forward" value="<?php echo $forward;?>">
                	<ul class="login-form-list">
                    	<li>
                        	<label for="username">用户名</label>
                            <input id="username" class="inp w248" type="text" tabindex="1" name="username" value="" maxlength="30">
                        	
                        </li>
                    	<li>
                        	<label for="pwd">密码</label>
                           <input id="password" class="inp w248" type="password" name="password"  tabindex="2">
                           
                        </li>
                        <li class="rem-text">
                            <div class="login-v01">
                            &nbsp;&nbsp; <a href="index.php?m=member&c=index&a=public_forget_password&siteid=<?php echo $siteid;?>" title="忘记密码">忘记密码</a>
                            </div>
                        </li>
                        <li class="submit-cont">
                            <span id="msgdemo2"></span>
							<input type="hidden" name="dosubmit" value="1" />
				
                            <button type="submit" class="btn-login radius-three" tabindex="4" title="登录">登&nbsp;录</button>
                        </li>
                    </ul>
                </form>
                </div><!--E .login-form-mod-->
                <div class="error-cont hidden">错误的用户名或密码</div>
                <div class="l-mod-remark">
                	<span>&gt; 还没有账号？</span><a href="<?php echo $siteinfo['domain'];?>index.php?m=member&c=index&a=register&siteid=<?php echo $siteid;?>" title="立即注册">立即注册</a>
                </div><!--E .l-mod-remark-->
            </div><!--E .l-mod-bd-->
            </div><!--E .ms-mod-->
          </div><!--E .layout-->
        </article>
        <!--E 内容-->
        <!--S 底部-->
        
 
 
        <footer class="footer">
        	<div class="layout footer-mod">
        	<center> 
	            <div class="footer-link tc">
	               
	            </div></center> 
				<?php $_sinfo=siteinfo(1);?>
	            <p class="tc"><?php echo $_sinfo['copyright'];?> </p>
	    
            </div>
         </footer>   
      
		
 
 
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
			content : "text:<div style=\"background:url(<?php echo IMG_PATH;?>tick_48.png) #c3e4fd no-repeat 20px 50%;height:60px;line-height:51px;padding-left:80px;padding-right:60px; \"><b>"+msg+"</b></d",
			showtitle : "remove",
			time : 2000,
			border : {opacity:"-2"}
		});
		
}
 
</script>
 
  <script language="JavaScript">
<!--

	$(function(){
		$('#username').focus();
	})

	function save_username() {
		if($('#cookietime').attr('checked')==true) {
			var username = $('#username').val();
			setcookie('username', username, 3);
		} else {
			delcookie('username');
		}
	}
	var username = getcookie('username');
	if(username != '' && username != null) {
		$('#username').val(username);
		$('#cookietime').attr('checked',true);
	}

	function show_login(site) {
		if(site == 'sina') {
			art.dialog({lock:false,title:'<?php echo L('sina_login');?>',id:'protocoliframe', iframe:'index.php?m=member&c=index&a=public_sina_login',width:'500',height:'310',yesText:'<?php echo L('close');?>'}, function(){
			});
		} else if(site == 'snda') {
			art.dialog({lock:false,title:'<?php echo L('snda_login');?>',id:'protocoliframe', iframe:'index.php?m=member&c=index&a=public_snda_login',width:'500',height:'310',yesText:'<?php echo L('close');?>'}, function(){
			});
		} else if(site == 'qq') {
			art.dialog({lock:false,title:'<?php echo L('qq_login');?>',id:'protocoliframe', iframe:'index.php?m=member&c=index&a=public_qq_login',width:'500',height:'310',yesText:'<?php echo L('close');?>'}, function(){
			});
		}
	}
//-->
</script>

 
        <!--E 底部-->        
    </div><!--E .wrap--> 
</body>
</html>
