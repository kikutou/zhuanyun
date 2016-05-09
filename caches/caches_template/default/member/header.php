<?php defined('IN_PHPCMS') or exit('No permission resources.'); ?><!DOCTYPE HTML>
<html>
<head>
 	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=7" />

	<title>我的后台</title>

	<link href="/favicon.ico" rel="shortcut icon">
	<link type="text/css" rel="stylesheet" href="<?php echo CSS_PATH;?>global.css" media="screen" />
	<link type="text/css" rel="stylesheet" href="<?php echo CSS_PATH;?>global2.css" media="screen" />
	<link type="text/css" rel="stylesheet" href="<?php echo CSS_PATH;?>profile.css" media="screen" />
	<link rel="stylesheet" href="<?php echo CSS_PATH;?>style.css" type="text/css" media="all" />
	<link rel="stylesheet" href="<?php echo CSS_PATH;?>lrtk.css" type="text/css" media="all" />
    <link rel="stylesheet" href="<?php echo CSS_PATH;?>w-index.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo CSS_PATH;?>n-index.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo CSS_PATH;?>jquery.bigautocomplete.css" type="text/css" />
    <link rel="stylesheet" type="text/css" href="<?php echo CSS_PATH;?>Vizo-style.css" />
    <link href="<?php echo CSS_PATH;?>Pager.css" rel="stylesheet" type="text/css" />

	<script type="text/javascript" src="<?php echo JS_PATH;?>jquery.js"></script>
  	<script type="text/javascript" src="<?php echo JS_PATH;?>lrtk.js"></script>
  	<!--[if lt IE 9]>
  		<script type="text/javascript" src="<?php echo JS_PATH;?>html5.js"></script>
  	<![endif]-->
  	<!--[if IE 6]>
    	<script type="text/javascript" src="<?php echo JS_PATH;?>CC_belatedPNG.js" ></script>
    	<script type="text/javascript">CC_belatedPNG.fix('img,.h-btn,.nav');</script>
  	<![endif]-->
  	<script type="text/javascript" src="<?php echo JS_PATH;?>jquery-1.6.2.min.js"></script>
  	<script type="text/javascript" src="<?php echo JS_PATH;?>common.js"></script>
  	<script type="text/javascript" src="<?php echo JS_PATH;?>util.js"></script>
	<?php if(isset($show_validator)) { ?>
		<script type="text/javascript" src="<?php echo JS_PATH;?>formvalidator.js" charset="UTF-8"></script>
		<script type="text/javascript" src="<?php echo JS_PATH;?>formvalidatorregex.js" charset="UTF-8"></script>
	<?php } ?>
	<script language="javascript">
	function copyToClipBoard(clipBoardContent){
	 try {
		window.clipboardData.setData("Text",clipBoardContent);
		success("复制成功!");
		 } catch (e) {
		dialog_alert("你的浏器览不支持剪贴板操作，请自行复制！");
	   }
	}
</script>
	<style type="text/css">
	.weixin{ width:30px; height:24px; float:left; position:relative; font-size:12px; text-align:center;}
	.weixin a{width:30px; height:24px; display:block; position:absolute; left:0; top:0;background:url(<?php echo IMG_PATH;?>h_weixin.png); }
	.weixin .weixin_nr{width:130px; height:135px; padding:10px; background:#fff; text-align:center; position:absolute; left:-45px; top:45px; z-index:999; display:none;}
	.weixin .weixin_nr img{ margin-bottom:5px;}
	.weixin .weixin_nr .arrow{ width:0; height:0; border-bottom:10px solid #fff;border-left:10px solid transparent;border-right:10px solid transparent; position:absolute; left:50px; top:-10px;}
	.weixin.on .weixin_nr{ display:block;}
	.weixin.on a{ background:url(<?php echo IMG_PATH;?>h_weixin.png));}

	.wtel{ width:26px; height:24px; margin-left:10px; float:left; position:relative; font-size:12px; text-align:center;}
	.wtel a{width:16px; height:24px; display:block; position:absolute; left:0; top:0;background:url(<?php echo IMG_PATH;?>mb.jpg); }
	.wtel .wtel_nr{width:130px; height:135px; padding:10px; background:#fff; text-align:center; position:absolute; left:-45px; top:45px; z-index:999; display:none;}
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

<body id="channel1" style="background:#fff;">
	<div class="wrap">

		<!--S 头部-->
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

        <div style="position: relative; z-index:999; background:#f6f6f6;">
        	<div id="head" style="height:98px; overflow:visible;">
            	<div class="head_w">
					<?php $sinfo=siteinfo(1);?>
                	<a href="/" class="logo" style="background:url(<?php echo $sinfo['logo'];?>); padding:0;">日本转运</a>
					<div class="top-nav" style="left:493px; width:550px;">
						<ul class="top-nList clearfix">
							<li style="width:250px;">
								<div class="t_news">
									<b id="top">今日汇率：</b>
									<ul onMouseOver="document.all.menulayer_1.style.visibility=''" onMouseOut="document.all.menulayer_1.style.visibility='hidden'" class="news_li">
										<?php if(defined('IN_ADMIN')  && !defined('HTML')) {echo "<div class=\"admin_piao\" pc_action=\"content\" data=\"op=content&tag_md5=446674c84fa49422c9f49a9710e10246&action=lists&catid=27&num=16&siteid=%24siteid&order=id+ASC\"><a href=\"javascript:void(0)\" class=\"admin_piao_edit\">编辑</a>";}$content_tag = pc_base::load_app_class("content_tag", "content");if (method_exists($content_tag, 'lists')) {$data = $content_tag->lists(array('catid'=>'27','siteid'=>$siteid,'order'=>'id ASC','limit'=>'16',));}?>
											<?php $n=1;if(is_array($data)) foreach($data AS $r) { ?>
												<li style="color:red; float:none;"><?php echo $r['title'];?></li>
											<?php $n++;}unset($n); ?>
										<?php if(defined('IN_ADMIN') && !defined('HTML')) {echo '</div>';}?>
									</ul>
									<ul class="swap"></ul>
								</div>
							</li>
                       		<li style="width:200px;">
								<script type="text/javascript">document.write('<iframe src="index.php?m=member&c=index&a=mini&forward='+encodeURIComponent(location.href)+'&siteid=<?php echo get_siteid();?>" allowTransparency="true"  width="200" height="24" frameborder="0" scrolling="no"></iframe>')</script>
							</li>
						</ul>
					</div>
                	<div class="fav" style="PADDING-TOP: 8px; PADDING-RIGHT: 8px">
                    	<a onclick="SetHome(this,'<?php echo APP_PATH;?>');" style="font:12px/1.5 Tahoma, Arial, sans-serif, microsoft yahei; color:#000;">设为首页</a> | <a onclick="AddFavorite('<?php echo $SEO['title'];?>',location.href)" href="javascript:;" id="sfav" style="font:12px/1.5 Tahoma, Arial, sans-serif, microsoft yahei; color:#000;">加入收藏</a>
                	</div>
                	<div class="w_sina" style="left:370px; width:706px; top:43px;">
                    	<div style="float:left; padding-top: 8px; padding-left: 40px; font-family: 宋体; font-size: 18px; color:#747474;">统一客服热线：<?php echo $sinfo['public_tel'];?></div>
						<div style="float:right; padding-top:3px;">
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
                	</div>
            	</div>
        	</div>
    	</div>


		<div class="lineb"></div>
        <!--E 头部-->


        <!--S 导航-->
        <div class="nav_w" style=" background:#f6f6f6;">
        	<div class="navmain">
				<div id="weixin">
					<img alt="日本转运官方微信" src="<?php echo IMG_PATH;?>mp_qrcode15def7.png" width="180" height="240" />
				</div>
				<div id="yixin">
					<img alt="日本转运官方易信" src="<?php echo IMG_PATH;?>yixin.jpg" width="180" height="240" />
				</div>
				<style>
				*{ margin:0; padding:0; list-style:none;}
				.nav-mList li .second a{background:#9eb40a; padding:0 33px; border-top:1px solid #f6f6f6;}
				.nav-mList li .second a:hover{background:#f6f6f6; padding:0 32px; }
				.nav-mList li .second{position:absolute;left:0;display:none; }
				</style>
				<ul class="nav" style="margin-left:7px;">
					<li style="line-height:38px;">
						<a href="/">首页</a>
					</li>
					<?php if(defined('IN_ADMIN')  && !defined('HTML')) {echo "<div class=\"admin_piao\" pc_action=\"content\" data=\"op=content&tag_md5=8620a06df680a17cfec2e84c556cfb2a&action=category&catid=0&num=7&siteid=%24siteid&order=listorder+ASC\"><a href=\"javascript:void(0)\" class=\"admin_piao_edit\">编辑</a>";}$content_tag = pc_base::load_app_class("content_tag", "content");if (method_exists($content_tag, 'category')) {$data = $content_tag->category(array('catid'=>'0','siteid'=>$siteid,'order'=>'listorder ASC','limit'=>'7',));}?>
						<?php $k=11?>
						<?php $n=1;if(is_array($data)) foreach($data AS $r) { ?>
							<?php $k++; ?>

							<li style="line-height:38px;" id="<?php echo $k;?>">
								<a href="<?php echo $r['url'];?>" title="<?php echo $r['catname'];?>" target="_top">
									<?php echo $r['catname'];?>
								</a>
								<?php if(defined('IN_ADMIN')  && !defined('HTML')) {echo "<div class=\"admin_piao\" pc_action=\"content\" data=\"op=content&tag_md5=696bf3aabb4119ae8b381f6d942f7bb0&action=category&catid=%24r%5Bcatid%5D&num=7&return=data2\"><a href=\"javascript:void(0)\" class=\"admin_piao_edit\">编辑</a>";}$content_tag = pc_base::load_app_class("content_tag", "content");if (method_exists($content_tag, 'category')) {$data2 = $content_tag->category(array('catid'=>$r[catid],'limit'=>'7',));}?>
									<?php if($data2) { ?>
										<div class="second">
											<?php $n=1;if(is_array($data2)) foreach($data2 AS $r2) { ?>
												<a href='$r2[url]}'><?php echo $r2['catname'];?></a>
											<?php $n++;}unset($n); ?>
										</div>
									<?php } ?>
								<?php if(defined('IN_ADMIN') && !defined('HTML')) {echo '</div>';}?>
							</li>

						<?php $n++;}unset($n); ?>
					<?php if(defined('IN_ADMIN') && !defined('HTML')) {echo '</div>';}?>
				</ul>

				<script src="http://www.lanrenzhijia.com/ajaxjs/jquery.min.js"></script>
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
			</div>
		</div>
		<!--E 导航-->

		<div style="background: url('/resource/images/nav_bottom3.png') repeat-x; height: 15px;"></div>





		<?php
			if($_GET['a']!='account_manage_info'){
				$currus = $this->get_current_userinfo();

				if($currus['mobile']=="" || $currus['truename']=="" || $currus['country']=="" || $currus['province']=="" || $currus['city']=="" || $currus['address']==""  || $currus['email']=="" || $currus['postcode']=="" ){

					//showmessage("您的资料尚未完善，请完善后再操作!", 'index.php?m=member&c=index&a=account_manage_info&t=1');
		?>
				   <script type="text/javascript">
				  		alert("您的资料尚未完善，请完善后再操作!");
				  		window.location.href="/index.php?m=member&c=index&a=account_manage_info&t=1";
				  </script>
		<?php
				 }


			}
		?>
</div>