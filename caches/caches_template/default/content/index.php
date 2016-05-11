<?php defined('IN_PHPCMS') or exit('No permission resources.'); ?><?php include template("content","header"); ?>  

 <style>
/* index */
#slide-index{width:1078px; height:320px; overflow:hidden; position:relative;}
#slide-index .slides{width:1078px; width:auto; height:320px; }
#slide-index .slide{ width:1078px; height:320px; float:left; position:relative; }
#slide-index .image{ z-index:1;position:absolute; }
#slide-index .text, #slide-index .button { z-index:2;position:absolute; top:-500px; }
#slide-index .button{ display:none !important; width:138px;}
#slide-index .control{ position:absolute; bottom:0px; width:100%; text-align:center; height:19px; cursor:pointer; z-index:40;}
#slide-index .control a{ width:11px; height:11px; cursor:pointer; display:inline-block; background-repeat:no-repeat; background-image:url(<?php echo IMG_PATH;?>hw_000505.gif); margin-right:6px; opacity:0.6; filter:alpha(opacity=60);}
#slide-index .control a:hover, #slide-index .control a.active{ background-image:url(<?php echo IMG_PATH;?>hw_000506.gif); opacity:1; filter:alpha(opacity=100);}
 </style>

        <!--S 内容-->
        <article class="content">
        	<!--S 内容第一栏-->
        	<div class="layout content-img-mod">
            	<div class="layout grid-s19m0">
					<div  style="float:right; width:1078px; height:320px;">
			       	 <div class="main-wrap">
                     	
						
                        	<?php if(defined('IN_ADMIN')  && !defined('HTML')) {echo "<div class=\"admin_piao\" pc_action=\"get\" data=\"op=get&tag_md5=b4fcfbb7bafd18f1f3716e872347d3a7&sql=SELECT+%2A+from+t_poster+where+spaceid%3D1+and+disabled%3D0+order+by+listorder+&num=5&return=addata\"><a href=\"javascript:void(0)\" class=\"admin_piao_edit\">修改</a>";}pc_base::load_sys_class("get_model", "model", 0);$get_db = new get_model();$r = $get_db->sql_query("SELECT * from t_poster where spaceid=1 and disabled=0 order by listorder  LIMIT 5");while(($s = $get_db->fetch_next()) != false) {$a[] = $s;}$addata = $a;unset($a);?>
	<div id="slide-index" style="height:320px;"> 
		<div class="slides">

		
			<?php $n=1;if(is_array($addata)) foreach($addata AS $rs) { ?>
			<?php $settings=string2array($rs[setting])?>
	
			<div class="slide autoMaxWidth" links=[{left:'30px',top:'81px'},{left:'30px',top:'244px'},{direction:'tb'}]>	  		
			<div class="image" id='bi_0'><a target="_blank" href="<?php echo $settings['1']['linkurl'];?>"><img width="1078" height="320" src="<?php echo $settings['1']['imageurl'];?>" /></a></div>			
			<div class="text" id='bt_0'></div>
			<div class="button" id='bb_0' style="display:none;"></div>
		</div>
			<?php $n++;}unset($n); ?>
		</div>
		<div class="control">
		<?php $n=1;if(is_array($addata)) foreach($addata AS $rs) { ?>
		<a href=""></a>
		<?php $n++;}unset($n); ?>
		</div>
	</div>
	<?php if(defined('IN_ADMIN') && !defined('HTML')) {echo '</div>';}?>
	<!--end slide-index-->

                       

                     </div><!--E main-wrap-->
			       </div><!--E col-main-->

				</div><!--E .grid-s19m0-->
				<!-- right begin-->
				<div class="col-sub" style="margin-left:0;">
					<div class="col-sub" style="margin-left:0; border:1px solid #CACACA; width:302px; height:203px; border-radius:3px; margin: 12px 0 0 0;">
						<div style="height:35px; background:rgb(203, 218, 105); line-height: 35px; font-size: 16px; padding-left:10px;">
						<label for="desc">会员登录</label><br>
						</div>
						<div style="  padding:5px 10px 0 15px;">
						<script type="text/javascript">document.write('<iframe src="<?php echo APP_PATH;?>index.php?m=member&c=index&a=mini&flag=1&forward='+encodeURIComponent(location.href)+'&siteid=<?php echo get_siteid();?>" allowTransparency="true"  width="275" height="153" frameborder="0" scrolling="no"></iframe>')</script>
						</div>
					</div>
                  	   <div class="mod radius-three box-shadow-v01" style="border: 1px solid rgb(202, 202, 202); border-image: none; width: 302px; height: 225px; margin: 230px 0 0 0;">
                            <div class="mod-hd radius-three-tr">
                              <h3>公告</h3>
							   <a href="/index.php?m=content&c=index&a=lists&catid=29" class="more-link">更多...</a> 
                            </div><!--E .mod-hd-->
                            <div class="mod-bd mod-shadow">
                                <ul class="learning-list">
                                     <?php if(defined('IN_ADMIN')  && !defined('HTML')) {echo "<div class=\"admin_piao\" pc_action=\"content\" data=\"op=content&tag_md5=1c7f8cedce06f303612184ff53cc9ef7&action=lists&catid=29&num=6&siteid=%24siteid&order=inputtime+desc\"><a href=\"javascript:void(0)\" class=\"admin_piao_edit\">修改</a>";}$content_tag = pc_base::load_app_class("content_tag", "content");if (method_exists($content_tag, 'lists')) {$data = $content_tag->lists(array('catid'=>'29','siteid'=>$siteid,'order'=>'inputtime desc','limit'=>'6',));}?>
									<?php $n=1;if(is_array($data)) foreach($data AS $r) { ?>
                                    	<li style="height:29px;border-bottom:1px dashed #ccc"><a href="<?php echo $r['url'];?>" title="<?php echo $r['title'];?>"><?php echo str_cut($r[title],50);?></a>
										<span style="float:right;"><?php echo date('Y-m-d',$r[inputtime]);?></span>
										</li>
                                		 <?php $n++;}unset($n); ?>
									 <?php if(defined('IN_ADMIN') && !defined('HTML')) {echo '</div>';}?>
                                    
                                </ul>
                                                             
                            </div><!--E .mod-bd-->
                        </div><!--E .mod-->                
                  </div>
					<div  style="width:758px;float:right; padding-top:12px;">
					<div class="mod radius-three box-shadow-v01">
                            	<div class="mod-hd radius-three-tr" style="margin-bottom:0;">
                                  <h3>日淘晒单</h3>
                                   <a href="/index.php?m=content&c=index&a=lists&catid=25" class="more-link">更多...</a> 
                                </div><!--E .mod-hd-->

								<style type="text/css">
								a.n{color:#787878;}
								a:hover.n{color:red;}
								</style>

								<div id="scrollDiv_keleyi_com" class="scrollDiv">
									<ul>
									<?php if(defined('IN_ADMIN')  && !defined('HTML')) {echo "<div class=\"admin_piao\" pc_action=\"content\" data=\"op=content&tag_md5=ff216c6e1cb96815eddd7093ae5ceb43&action=lists&catid=25&num=10&siteid=%24siteid&order=inputtime+DESC\"><a href=\"javascript:void(0)\" class=\"admin_piao_edit\">修改</a>";}$content_tag = pc_base::load_app_class("content_tag", "content");if (method_exists($content_tag, 'lists')) {$data = $content_tag->lists(array('catid'=>'25','siteid'=>$siteid,'order'=>'inputtime DESC','limit'=>'10',));}?>
									<?php $n=1;if(is_array($data)) foreach($data AS $r) { ?>
                                    	<li style="background: none; padding-left:0; height: 130px; line-height:normal;">
                                    		<img src="<?php echo thumb($r[thumb]);?>" width="150" height="105" alt="<?php echo $r['title'];?>" style="float:left; padding-right:15px; padding-top:10px">
                                        	<a href="<?php echo $r['url'];?>" title="<?php echo $r['title'];?>" style="float:none;"><font style=" line-height:28px; font-size:16px; "><?php echo str_cut($r[title],60);?></font></a>
                                            <br><a class="n"  href="<?php echo $r['url'];?>" title="<?php echo $r['title'];?>" style="float:none;"><font style=" line-height:18px;"><?php echo str_cut($r[description],300);?>...</font></a>
											<br><br>  <span><?php echo date('Y-m-d',$r[inputtime]);?></span>
                                        </li>
                     				 <?php $n++;}unset($n); ?>
									 <?php if(defined('IN_ADMIN') && !defined('HTML')) {echo '</div>';}?>
									</ul>
								</div>
									<script type="text/javascript">
										function AutoScroll(obj) {
											$(obj).find("ul:first").animate({
												marginTop: "-130px"
											}, 500, function () {
												$(this).css({ marginTop: "0px" }).find("li:first").appendTo(this);
											});
										}
										$(document).ready(function () {
											setInterval('AutoScroll("#scrollDiv_keleyi_com")', 3000);
										});
									</script>
								

                                <div class="mod-bd mod-shadow" style="display:none;">
                                	<ul class="news-list">
                                	 <?php if(defined('IN_ADMIN')  && !defined('HTML')) {echo "<div class=\"admin_piao\" pc_action=\"content\" data=\"op=content&tag_md5=ff216c6e1cb96815eddd7093ae5ceb43&action=lists&catid=25&num=10&siteid=%24siteid&order=inputtime+DESC\"><a href=\"javascript:void(0)\" class=\"admin_piao_edit\">修改</a>";}$content_tag = pc_base::load_app_class("content_tag", "content");if (method_exists($content_tag, 'lists')) {$data = $content_tag->lists(array('catid'=>'25','siteid'=>$siteid,'order'=>'inputtime DESC','limit'=>'10',));}?>
									<?php $n=1;if(is_array($data)) foreach($data AS $r) { ?>
                                    	<li style="background: none; padding-left:0; height: 120px; line-height:normal;">
                                    		<img src="<?php echo thumb($r[thumb]);?>" width="150" height="100" alt="<?php echo $r['title'];?>" style="float:left; padding-right:15px; padding-top:10px">
                                        	<a href="<?php echo $r['url'];?>" title="<?php echo $r['title'];?>" style="float:none;"><font style=" line-height:28px; font-size:16px; "><?php echo str_cut($r[title],60);?></font></a>
                                            <br><a class="n"  href="<?php echo $r['url'];?>" title="<?php echo $r['title'];?>" style="float:none;"><font style=" line-height:18px;"><?php echo str_cut($r[description],300);?>...</font></a>
                                        </li>
                     				 <?php $n++;}unset($n); ?>
									 <?php if(defined('IN_ADMIN') && !defined('HTML')) {echo '</div>';}?>
                                    	
                     				  
                                    </ul>
                                </div><!--E .mod-bd-->
                            </div><!--E .mod-->
				
				</div>
					<!--right end--->
            </div>
            <!--E 内容第一栏-->
			
			

        	<!--S 内容第二栏-->
        	<div class="content-mod" style="padding:10px 0;">
	
				<div class="layout grid-s19m0e15" >
					<div class="col-main">
						<div class="main-wrap">
                        	<div class="mod radius-three box-shadow-v01" style="height:245px;">
                            	<div class="mod-hd radius-three-tr">
                                  <h3>日淘资讯</h3>
                                   <a href="/index.php?m=content&c=index&a=lists&catid=13" class="more-link">更多...</a> 
                                </div><!--E .mod-hd-->
                                <div class="mod-bd mod-shadow">
                                	<ul class="news-list">
                                	 <?php if(defined('IN_ADMIN')  && !defined('HTML')) {echo "<div class=\"admin_piao\" pc_action=\"content\" data=\"op=content&tag_md5=ca3ddfdc3b939610101a0dd9a39d39a3&action=lists&catid=13&num=7&siteid=%24siteid&order=inputtime+desc\"><a href=\"javascript:void(0)\" class=\"admin_piao_edit\">修改</a>";}$content_tag = pc_base::load_app_class("content_tag", "content");if (method_exists($content_tag, 'lists')) {$data = $content_tag->lists(array('catid'=>'13','siteid'=>$siteid,'order'=>'inputtime desc','limit'=>'7',));}?>
									<?php $n=1;if(is_array($data)) foreach($data AS $r) { ?>
                                    	<li>
                                    	
                                        	<a href="<?php echo $r['url'];?>" title="<?php echo $r['title'];?>"><?php echo $r['title'];?></a>
                                            <span style="display:none;"><?php echo date('Y-m-d',$r[inputtime]);?></span>
                                        </li>
                     				 <?php $n++;}unset($n); ?>
									 <?php if(defined('IN_ADMIN') && !defined('HTML')) {echo '</div>';}?>
                                    	
                     				  
                                    </ul>
                                </div><!--E .mod-bd-->
                            </div><!--E .mod-->
                        </div><!--E .main-wrap-->
					</div><!--E .col-main-->
					<div class="col-sub" style="padding: 5px 10px 10px; border-radius: 3px; border: 1px solid #cacaca; border-image: none;">
						<h2 class="home-h2"><label for="desc" style="margin-bottom:0;">快件查询</label></h2>
						<form action="api.php" name="mainForm" id="mainForm" method="get">

					   <input type="hidden" name="op" value="orderno"/>
						<div class="textarea-mod">
							<textarea class="inp-tarea" id="ord" style="ime-mode:disabled; height:105px;" name="ord" value="请输入日本邮政快递单号" onFocus="this.value=''" onKeyUp="this.value=this.value.replace(/[^0-9a-z]/gi,'')">请输入日本邮政快递单号</textarea>
						</div>
						<!--update 2013-3-18--> 
						<div class="btn-search-mod clearfix">
							<button type="button" onclick="submitF();" class="h-btn-search h-btn-search-dblue radius-three fr" title="查询日本国内快递">查询日本邮政快递</button>        
						</div><!--E .btn-search-mod-->
						<div style="text-align:right; margin-top:9px; ">
							<a href="/api.php?op=orderno&ord=." style="color:#667503; font-size: 14px;">其他快递请点击这里>></a>
							        
						</div>
						<!--update 2013-3-18-->
						</form> 
					</div><!--E .col-sub-->
		      <div class="col-extra">
                      <div class="mod radius-three box-shadow-v01" style="height:245px;">
                            <div class="mod-hd radius-three-tr">
                              <h3>日淘教程</h3>
                              <a href="/index.php?m=content&c=index&a=lists&catid=12" class="more-link">更多...</a> 
                            </div><!--E .mod-hd-->
                            <div class="mod-bd mod-shadow">
							<ul class="news-list">
                                	 <?php if(defined('IN_ADMIN')  && !defined('HTML')) {echo "<div class=\"admin_piao\" pc_action=\"content\" data=\"op=content&tag_md5=a38762284973ef5820f49fc542b5d2ad&action=lists&catid=19&num=7&siteid=%24siteid&order=inputtime+desc\"><a href=\"javascript:void(0)\" class=\"admin_piao_edit\">修改</a>";}$content_tag = pc_base::load_app_class("content_tag", "content");if (method_exists($content_tag, 'lists')) {$data = $content_tag->lists(array('catid'=>'19','siteid'=>$siteid,'order'=>'inputtime desc','limit'=>'7',));}?>
									<?php $n=1;if(is_array($data)) foreach($data AS $r) { ?>
                                    	<li>
                                    	
                                        	<a href="<?php echo $r['url'];?>" title="<?php echo $r['title'];?>"><?php echo $r['title'];?></a>
                                            
                                        </li>
                     				 <?php $n++;}unset($n); ?>
									 <?php if(defined('IN_ADMIN') && !defined('HTML')) {echo '</div>';}?>
                                    	
                     				  
                                    </ul>
                           
                            </div><!--E .mod-bd-->
                        </div><!--E .mod-->   
              </div><!--E .col-extra-->
		 </div><!--E .grid-s19m0e15-->

				
                
                <!--广告组开始  2013/4/18-->
                
                 <div class="adver-group layout" style="display:none;">
                  <ul class="clearfix">
   <?php if(defined('IN_ADMIN')  && !defined('HTML')) {echo "<div class=\"admin_piao\" pc_action=\"get\" data=\"op=get&tag_md5=a10e4972e7764bca1308efd9c52a461a&sql=SELECT+%2A+from+t_poster+where+spaceid%3D3+and+disabled%3D0+order+by+listorder+&num=4&return=datad\"><a href=\"javascript:void(0)\" class=\"admin_piao_edit\">修改</a>";}pc_base::load_sys_class("get_model", "model", 0);$get_db = new get_model();$r = $get_db->sql_query("SELECT * from t_poster where spaceid=3 and disabled=0 order by listorder  LIMIT 4");while(($s = $get_db->fetch_next()) != false) {$a[] = $s;}$datad = $a;unset($a);?>

			<?php $n=1;if(is_array($datad)) foreach($datad AS $rs) { ?>
			<?php $settings=string2array($rs[setting])?>
                            	<li>
                            	<a href="<?php echo $settings['1']['linkurl'];?>"  target="_blank">
                            	<img src="<?php echo $settings['1']['imageurl'];?>" width="250" height="60" >
                            	</a></li>
                            
              <?php $n++;}unset($n); ?>
			<?php if(defined('IN_ADMIN') && !defined('HTML')) {echo '</div>';}?>       
                            	
                  </ul>
                </div>        
                       
               
                    <span class="blank16" style="padding-top:35px; border-bottom:1px solid rgb(185, 165, 10); margin:0px auto; width:1078px; font-size:18px; font-weight: bold; height:25px; color:rgb(107, 9, 121);">日淘推荐 </span> 
                <!--当没有广告时开始-->
           		<div class="layout" style="padding:30px 0;">
              	 <div class="box2">
					<div class="picbox" style="padding-left:5px;">
						<ul class="piclist mainlist">
							
							 <?php if(defined('IN_ADMIN')  && !defined('HTML')) {echo "<div class=\"admin_piao\" pc_action=\"link\" data=\"op=link&tag_md5=0129e0b5dcc1bb14adee5c75da781087&action=type_list&siteid=%24siteid&linktype=1&order=listorder+DESC&num=20&return=pic_link\"><a href=\"javascript:void(0)\" class=\"admin_piao_edit\">修改</a>";}$link_tag = pc_base::load_app_class("link_tag", "link");if (method_exists($link_tag, 'type_list')) {$pic_link = $link_tag->type_list(array('siteid'=>$siteid,'linktype'=>'1','order'=>'listorder DESC','limit'=>'20',));}?>
								<?php $n=1;if(is_array($pic_link)) foreach($pic_link AS $v) { ?>
									<li><a href="<?php echo $v['url'];?>" title="<?php echo $v['name'];?>" target="_blank"><img src="<?php echo $v['logo'];?>"  width="190" height="85" ></a></li>
								<?php $n++;}unset($n); ?>
							 <?php if(defined('IN_ADMIN') && !defined('HTML')) {echo '</div>';}?>
							
						</ul>
						<ul class="piclist swaplist"></ul>
					</div>
					<div class="og_prev"></div>
					<div class="og_next"></div>
				</div>
                </div><!--E .layout-->  
				
				
                <!--当没有广告时结束-->  
				
            </div>
            <!--E 内容第二栏-->            
        </article>
        <!--E 内容-->
        <script type="text/javascript" src="<?php echo JS_PATH;?>global_cn.index.js"></script>
<?php include template("content","footer"); ?>	
		