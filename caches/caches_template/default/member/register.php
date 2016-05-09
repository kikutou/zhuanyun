<?php defined('IN_PHPCMS') or exit('No permission resources.'); ?><!DOCTYPE HTML>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta name="keywords" content="" />
  <meta name="description" content="" />
  <title><?php echo L('member','','member').L('manage_center');?></title>
  <link type="text/css" rel="stylesheet" href="<?php echo CSS_PATH;?>global.css" media="screen" />
  <link rel="stylesheet" href="<?php echo CSS_PATH;?>style.css" type="text/css" media="all" />
  <link rel="stylesheet" href="<?php echo CSS_PATH;?>reset.css" type="text/css" media="all" />
  <link rel="stylesheet" href="<?php echo CSS_PATH;?>dialog_simp.css" type="text/css" media="all" />
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
<!--  <script type="text/javascript" src="<?php echo JS_PATH;?>Validform_v5.2.1_min.js"></script>-->
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
	.ms-mod{ padding-bottom:19px; width:390px;background: none repeat scroll 0 0 rgba(255, 255, 255, 0.85); position:absolute; top:20px; right:30px;}
    .l-mod-hd{ padding:0px 0 0 18px;}
    .l-mod-hd h3{ color:#333; font-size:16px; font-weight:bold;}
    .l-mod-bd{ padding:18px 0 0 18px;}
    .login-form-list li{ padding-bottom:1px;}
        .login-form-list label{ display:inline-block; padding-right:10px; width:60px; text-align:right; font-weight:bold; color:#666;}
        .login-form-list .inp{ height:25px; line-height:25px; border-color:#ccc;}
        
        .rem-text{ padding-bottom:10px !important;}
        .rem-text .login-v01{ padding-left:72px;}
          .rem-text .login-v01 label{ width:224px;}
       .submit-cont{ padding-bottom:8px !important}
       .l-mod-remark,.submit-cont{ padding-left:72px;}
         .submit-cont .btn-login{ padding:10px 30px; width:260px; font-size:16px;}
        
       .l-mod-remark a,.l-mod-remark a:hover{ color:#F60; font-size:14px; font-weight:bold}
    .footer, .header{ background-image:none;}
	.onError{color:#ff0000;}
	.onShow{color:#ff0000;}
	.onFocus{color:#ff0000;}
	.onCorrect{color:green}
	
	.login-form-list li{margin-top:5px;}
	#lastnameTip{float:right;right:88px;}
  </style>

<script language="JavaScript"  type="text/javascript">
<!--
$(function(){
	$.formValidator.initConfig({autotip:true,formid:"myregform",onerror:function(msg){}});

	$("#username").formValidator({onshow:"<?php echo L('input').L('username');?>",onfocus:"帐号名称必须为5-16位的英文或者数字等"}).inputValidator({min:3,max:16,onerror:"帐号名称必须为5-16位的英文或者数字等"}).regexValidator({regexp:"ps_username",datatype:"enum",onerror:"<?php echo L('username').L('format_incorrect');?>"}).ajaxValidator({
	    type : "get",
		url : "",
		data :"m=member&c=index&a=public_checkname_ajax",
		datatype : "html",
		async:'false',
		success : function(data){
            if( data == "1" ) {
                return true;
			} else {
                return false;
			}
		},
		buttons: $("#dosubmit"),
		onerror : "<?php echo L('deny_register').L('or').L('user_already_exist');?>",
		onwait : "<?php echo L('connecting_please_wait');?>"
	});
	
	$("#firstname").formValidator({onshow:"请填真实的名(拼音)",onfocus:"请填真实的名(拼音)"}).inputValidator({min:2,max:20,onerror:"请填真实的名(拼音)"});
	$("#lastname").formValidator({onshow:"请填真实的姓(拼音)",onfocus:"请填真实的姓(拼音)"}).inputValidator({min:2,max:20,onerror:"请填真实的姓(拼音)"});

	$("#password").formValidator({onshow:"<?php echo L('input').L('password');?>",onfocus:"<?php echo L('password').L('between_6_to_20');?>"}).inputValidator({min:6,max:20,onerror:"<?php echo L('password').L('between_6_to_20');?>"});
	$("#pwdconfirm").formValidator({onshow:"<?php echo L('input').L('cofirmpwd');?>",onfocus:"<?php echo L('passwords_not_match');?>",oncorrect:"<?php echo L('passwords_match');?>"}).compareValidator({desid:"password",operateor:"=",onerror:"<?php echo L('passwords_not_match');?>"});
	$("#email").formValidator({onshow:"<?php echo L('input').L('email');?>",onfocus:"<?php echo L('email').L('format_incorrect');?>",oncorrect:"<?php echo L('email').L('format_right');?>"}).inputValidator({min:2,max:32,onerror:"<?php echo L('email').L('between_2_to_32');?>"}).regexValidator({regexp:"email",datatype:"enum",onerror:"<?php echo L('email').L('format_incorrect');?>"}).ajaxValidator({
	    type : "get",
		url : "",
		data :"m=member&c=index&a=public_checkemail_ajax",
		datatype : "html",
		async:'false',
		success : function(data){	
            if( data == "1" ) {
                return true;
			} else {
                return false;
			}
		},
		buttons: $("#dosubmit"),
		onerror : "<?php echo L('deny_register').L('or').L('email_already_exist');?>",
		onwait : "<?php echo L('connecting_please_wait');?>"
	});
	/*$("#nickname").formValidator({onshow:"请选择客服",onfocus:"<?php echo L('nickname').L('between_2_to_20');?>"}).inputValidator({min:2,max:20,onerror:"<?php echo L('nickname').L('between_2_to_20');?>"}).regexValidator({regexp:"ps_username",datatype:"enum",onerror:"<?php echo L('nickname').L('format_incorrect');?>"}).ajaxValidator({
			type : "get",
			url : "",
			data :"m=member&c=index&a=public_checknickname_ajax",
			datatype : "html",
			async:'false',
			success : function(data){
				if( data == "1" ) {
					return true;
				} else {
					return false;
				}
			},
			buttons: $("#dosubmit"),
			onerror : "<?php echo L('already_exist').L('already_exist');?>",
			onwait : "<?php echo L('connecting_please_wait');?>"
		});*/

	$(":checkbox[name='protocol']").formValidator({tipid:"protocoltip",onshow:"<?php echo L('read_protocol');?>",onfocus:"<?php echo L('read_protocol');?>"}).inputValidator({min:1,onerror:"<?php echo L('read_protocol');?>"});

	

});

function show_protocol() {
	art.dialog({lock:false,title:'<?php echo L('register_protocol');?>',id:'protocoliframe', iframe:'?m=member&c=index&a=register&protocol=1',width:'500',height:'310',yesText:'<?php echo L('agree');?>'}, function(){
		$("#protocol").attr("checked",true);
	});
}

//-->
</script>
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
	$("#username").formValidator({onshow:"<?php echo L('input').L('username');?>",onfocus:"应该为5-16位之间"}).inputValidator({min:5,max:20,onerror:"应该为5-16位之间"}).regexValidator({regexp:"ps_username",datatype:"enum",onerror:"<?php echo L('username').L('format_incorrect');?>"});
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
            	<h3>注册会员</h3>
            </div><!--E .l-mod-hd-->
            <div class="l-mod-bd">
            	<div class="login-form-mod">
                <form action="" method="post"  id="myregform" name="myregform" >
				<input type="hidden" name="siteid" value="<?php echo $siteid;?>" />
				<input type="hidden" name="forward" value="<?php echo urlencode($_GET['forward']);?>" />
                	<ul class="login-form-list">

                    	<li>
                        	<label for="username">用户名</label>
                            <input id="username" name="username" value=""  onKeyUp="this.value=this.value.replace(/[^0-9a-z_.]/gi,'')"  style="ime-mode:disabled;" maxlength="16" class="inp w88" type="text" tabindex="1" />
                        </li>
						<li>
                        	<label for="firstname">名</label>
                            <input id="firstname" name="firstname"   onKeyUp="this.value=this.value.replace(/[^0-9a-z_.]/gi,'')"  style="ime-mode:disabled;" maxlength="16" class="inp w88" type="text" title="请用名的拼音来填写，如：三" value="如：shang" style="color:#efefef" onfocus="if(this.value=='如：shang'){this.value='';this.style.color='#000';}"/>
							<label for="lastname" style="width:44px;">姓</label>
                            <input id="lastname" name="lastname"   onKeyUp="this.value=this.value.replace(/[^0-9a-z_.]/gi,'')"  style="ime-mode:disabled;" maxlength="16" class="inp w88" type="text" title="请用姓的拼音来填写，如：张" value="如：li" style="color:#efefef" onfocus="if(this.value=='如：li'){this.value='';this.style.color='#000';}"/>
                        </li>
                    	<li>
                        	<label for="email">邮箱</label>
                            <input id="email" name="email" class="inp w248" type="text"  style="ime-mode:disabled;" value="" maxlength="50" tabindex="5">
                            
                        </li>
                    	<li>
                        	<label for="pwd">密码</label>
                            <input id="password" name="password" class="inp w248"  style="ime-mode:disabled;" maxlength="20"  type="password" tabindex="6">
                                    
                        </li>
                    	<li>
                        	<label for="pwd">确认密码</label>
                            <input id="pwdconfirm" name="pwdconfirm" class="inp w248"   style="ime-mode:disabled;" maxlength="20" type="password" tabindex="7">
                                 
                        </li>
                        <li class="rem-text">
                            <div class="login-v01">
                            <!--
                            <input id="protocol" type="checkbox"   tabindex="5" name="protocol" value="1" checked>
							<label class="remember" for="protocol" style="text-align: left;">同意《<a href="javascript:void(0);" id="btn_show_text">用户注册协议</a>》 </label>
							-->
							<input type="checkbox" name="protocol" id="protocol" value=""><a href="javascript:void(0);" onclick="show_protocol();return false;" class="blue"><?php echo L('click_read_protocol');?></a>
							 
                            </div>
                        </li>
                        <li class="submit-cont" style="margin-top:20px;">
                            <span id="msgdemo2"></span>
							<input type="hidden" name="dosubmit"  value="1"/>
                            <button type="submit" id="dosubmit" class="btn-login radius-three" tabindex="6" title="注册" onclick="document.getElementById('firstname').value=document.getElementById('firstname').value.replace('如：shang','');document.getElementById('lastname').value=document.getElementById('lastname').value.replace('如：li','');">注&nbsp;册</button>
                        </li>
                    </ul>
                </form>
                </div><!--E .login-form-mod-->
                <div class="error-cont hidden">错误的用户名或密码</div>
                <div class="l-mod-remark">
                	<span>&gt; 已拥有账号？</span><a href="<?php echo $siteinfo['domain'];?>index.php?m=member&c=index&a=login&forward=<?php echo urlencode($_GET['forward']);?>&siteid=<?php echo $siteid;?>" title="直接登录">直接登录</a>
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
	            <p class="tc"><?php $sinfo=siteinfo(1);?>
                <?php echo $sinfo['copyright'];?></p>
	    
            </div>
         </footer>   
      
		
 
 

 
  

 
        <!--E 底部-->        
    </div><!--E .wrap--> 

<link rel="stylesheet" href="<?php echo CSS_PATH;?>core.css" type="text/css" media="all" />
<script type="text/javascript" src="<?php echo JS_PATH;?>XYTipsWindow-3.0.js"></script>

</body>
</html>

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

<script> 
 
 
 function inputkeyup(inputobj){
  if(!inputobj.value.match(/^\d*?\.?\d*?$/))
   inputobj.value=inputobj.t_value;
  else
   inputobj.t_value=inputobj.value;
  if(inputobj.value.match(/^(?:\d+(?:\.\d+)?)?$/))
   inputobj.o_value=inputobj.value
 }
 
 

 
var str="<p>您注册网意味您同意以下条件或条款，并认同网依据这些条件或条款为您提供所有服务。</p>"+
		"<p>网根据网服务条款协议（以下简称“本协议”）为您提供一站式海淘服务。本协议所阐述的条款和条件适用您使用网站时提供给您的所有服务内容(下称“服务”)，包含本协议正文，及网 已经或即将发布的所有规定、规则、说明等，都作为本协议不可分割的一部分，具有同等法律效力。</p>"+
		"<p>您一旦选择使用网为您提供的各项服务，即表示您已充分阅读、理解并同意自己已经与网订立本协议，且将受本协议的条 款和条件约束。网有权根据需要不时地制定、修改本协议或涉及的规定、规则、说明等，如本协议及组成部分有任何变更，网将 在网站上刊载公告，通知予您。变更会在发布后即时生效，亦成为本协议的一部分。如果您登录或继续使用“服务”将表示您接受经修订的协议。发生争议或纠纷 时，将以最新的服务协议为准。如您不同意相关变更，必须停止使用网的“服务”。各类规则除另行明确声明外，任何使“服务”范围扩大或功能 增强的新内容均受本协议约束。</p>"+
        "<p>请您务必在注册之前认真阅读全部服务协议内容，如有任何疑问，可向网客服咨询。无论您事实上是否在注册之前认真阅读了本协议，只要 您开始使用网的服务，包括但不限于，工具查询，站内搜索，登陆注册等，即表示您同意并签署了本协议，您和网之间产生法律 效力。</p>"+
        "<strong>1、服务对象</strong>"+
        "<p>“服务”仅供能够根据相关法律订立具有法律约束力的合约的个人或公司使用。因此，您的年龄必须在十八周岁或以上，或注册申请人为未成年人时，经法定 保护人同意后方可申请注册，才可使用网服务。如不符合本项条件，请勿使用“服务”。 网可随时自行全权决定拒绝向任何人士提供“服务”。“服务”不再提供给被冻结账户，强制终止合作或因恶意瞒报谎报而列入黑名单的用户。</p>"+
        "<strong>2、注册义务</strong>"+
        "<p>如网发现注册会员属于以下任何一项规定，可以取消其会员资格。</p>"+
        "<p>•	入会申请人为未成年人，且未经法定保护人同意；</p>"+
        "<p>•	入会申请人曾经因违反本协议等行为而被取消会员资格；</p>"+
        "<p>•	入会申请人提交的申请中，有虚假、误写或漏写的事项；</p>"+
        "<p>•	入会申请人有未支付本网站债务的记录；</p>"+
        "<p>•	妨害网站的运营和服务或妨害其他会员使用本网站服务，或有阻碍以上运营和服务的行为；</p>"+
        "<p>•	本网站认为不符合规定的其他情况。为避免以上情况出现，您应同意：</p>"+
        "<p>•	提供关于您或公司的真实、准确、完整和反映当前情况的注册资料</p>"+
        "<p>•	提供真实、准确、完整的货物信息和收货信息，并确保无本网站无法承运的货物；</p>"+
        "<p>•	确保您已尽到支付本网站服务费用，或海关等政府机关或机构费用的义务；</p>"+
        "<p>•	其他本协议及其组成部分要求您遵守的规定、规则、说明等。</p>"+
        "<strong>3、账户ID及密码管理</strong>"+
        "<p>在您按照注册页面提示填写信息、阅读并同意本协议并完成全部注册程序后，您即成为网会员。您须自行负责对您的账户ID和密码保密， 账户ID及密码无法出借、转让、买卖、抵押。由于会员对持有的账户ID及密码的保管不当、使用上的过错等而造成的损失由该会员承担，本网站不承担任何责任。使用账户ID和密码利用服务的行为，无论何种情况，均认为由会员本人进行的行为，会员对服务的使用承担一切责任。</p>"+
        "<p>当怀疑或得知账户ID或密码被第三方知悉及可能被非法使用时，应立即通知本网站，并按照本网站发出的指示执行。您也应已了解因为处理您的请求需要合理时间，在执行前网不对在采取行动前已经产生的后果（包括但不限于您的任何损失）不承担任何责任。由于对账户ID及密码的不正当使用，而导致本网站发生损失时，该会员应赔偿本网站的损失。</p>"+
        "<p>网保留随时修改或中断服务而毋需通知用户的权利，可自行全权决定，在发出通知或不发出通知的情况下，随时停止提供“服务”或其任何部份，该权利毋需对用户或第三方负责。</p>"+
 
        "<strong>4、停止会员资格</strong>"+
        "<p>本网站由于以下理由，可以在不通知或催告会员的情况下，暂时停止会员资格或除名</p>"+
        "<p>•	本人或指使他人不正当地使用账户ID、密码及本服务时；</p>"+
        "<p>•	会员在规定的日期内未补足余额或欠费时；</p>"+
        "<p>•	会员已经进行退款或注销流程；</p>"+
        "<p>•	会员因被列入黑名单或被暂时冻结、或被刑事处理、要求民事重整时，或会员自己提出请求时；</p>"+
        "<p>•	其他，会员违反本协议或个别协议的任何条款时；</p>"+
        "<p>•	其他，本网站认为会员不符合要求时。</p>"+
        "<p>您同意，根据本协议的任何规定终止您使用“服务”时，网可立即使您的账户无效，或撤销您的账户，禁止您使用账户或“服务”。账号终止后，网没有义务为您保留原帐号中或与之相关的任何信息。</p>"+
        "<strong>5、个人信息的处理</strong>"+
        "<p>本网站依据本协议，可以与网公司各方共同使用公司持有的个人信息及积分等使用记录。如账户ID、姓名、性别、邮件地址、电话号码、 邮政编码、地址、公司名称所属部门等、昵称、笔名、生日、信用卡信息、购物记录、积分。 本网站遵循隐私政策保护个人信息，不向第三方提供具有可识别性的个人信息。</p>"+
        "<p>会员在利用本服务时，本网站可以使用Cookie。</p>"+
        "<strong>6、服务责任限制</strong>"+
        "<p>本网站未与销售公司或网站达成商品的买卖关系，因此本网站不成为买卖合同的当事人。本网站不承担一切与商品相关的瑕疵、侵害知识产权等责任。但，如 果会员有证据证明商品的遗失、破损发生在本网站仓储期间，则不受本条限制，除此之外的一切货物少发、漏发或质量问题，包括因商品本身瑕疵造成的遗失、破 损，本网站不承担任何责任。</p>"+
        "<p>本网站未与运输公司缔结运输合同，因此本网站不成为运输合同的当事人。由于运输事故造成未送达、延误、破损及其他与运输有关的问题而导致会员蒙受损失，本网站不承担任何责任。会员赋予本网站以会员的名义缔结运输合同的权限。</p>"+
        "<strong>7、禁止使用服务的商品</strong>"+
        "<p>航空或货运规定的危险品；运送、进出口受包括经由国在内的进出口国、州、地方自治区的法令禁止或限制的物品；航空、运输公司的运输协议中不允许承运的其他物品；本网站认为不符合规定的其他物品。以上物品无法使用网服务。</p>"+
        "<p>本网站有开封检查商品内容的权利，但是本网站并没有检验商品的义务，同时检验的结果也不对商品质量、有无瑕疵、真赝品做出保证，不保证商品不违反发 出地、经由地、目的地所颁布的相关法令。本网站在进行检验时，怀疑有违反法律法规可能性的商品时，本网站可以采取报告警察及相关执法部门，提交商品等措 施。若检验商品及采取本条规定的处理办法造成会员损失时，本网站不承担任何责任。</p>"+
        "<p>本网站开封检查商品，一旦发现符合本规定的商品，将立即停止服务，并可以马上废弃或以其他方法处置。</p>"+
        "<strong>8、服务收费</strong>"+
        "<p>本网站有因提供服务，收取“服务”费用的权利。本网站保留在无须发出书面通知，在网站公示的情况下，暂时或永久地更改或停止部分或全部“服务”的权利，本网站可对价格表进行变更，恕不另行通知。计价依据的商品重量，以本网站的计量规则和结果为准。</p>"+
        "<p>服务费用包括从本网站运送到会员处的单程运输费。关税、退运费或发生从本网站到会员处的运输费以外的其他费用（以下简称为“特别费用”）时，由会员承担实际发生的费用。本网站没有义务垫付特别费用。</p>"+
        "<strong>9、服务中止</strong>"+
        "<p>发生以下任何情况时，本网站可以暂时中断或停止本服务的部分或全部内容，恕不另行通知。</p>"+
        "<p>不可抗力，对于因本公司合理控制范围以外的原因，包括但不限于自然灾害、罢工或骚乱、物质短缺或定量配给、暴动、战争行为、政府行为、通讯或其他设施故障或严重伤亡事故等，致使本网站延迟或未能履约的，网不对您承担任何责任。</p>"+
        "<p>因您或第三方责任造成的，包括但不限于违反国家或地区和国际组织的法律法规、寄送禁限运品、谎报瞒报虚报申报信息、海关惩罚性扣关、您指定收货地址 无法送达、收件人拒绝接受、有违反本协议或相关的规则、规定、说明等，及本网站认为必须暂时中断或停止此项服务的其他情况，网不对您承担 任何责任。</p>"+
        "<strong>10、适用范围和管辖</strong>"+
        "<p>本服务条款之解释与适用，以及与本服务条款有关的争议，均应依照中华人民共和国法律予以处理，上海市仲裁委员会仲裁。</p>";
$("#btn_show_text").click(function() {
		Util.Dialog({
			boxID: "textDIA",
			title : "快运用户注册协议",
			width : 540,
			
			fixed: true,
			content : "text:<div style='padding:15px;line-height:25px;height:460px;overflow:auto;'>"+str+"</div>"
		});
		return false;
	});
</script>