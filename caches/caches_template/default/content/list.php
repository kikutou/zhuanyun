<?php defined('IN_PHPCMS') or exit('No permission resources.'); ?><?php include template("content","header"); ?>   
 
        <!--S 内容-->
 		<article class="content">
        	<!--S 面包屑-->
            <div class="layout crumbs">
                <em>当前位置：</em><a href="<?php echo siteurl($siteid);?>">首页</a><span> &gt; </span>
                <?php echo catpos($catid);?> 列表
            </div>
            <!--E 面包屑-->                    	
        	<!--S 内容模块-->
        	<!--S 内容模块-->
        	<div class="content-mod">
            	<div class="layout sub-layout radius-five grid-s15m0">
			      <div class="col-main">
			       	 <div class="main-wrap" <?php if($catid!=12 && $catid!=13 && $catid!=25) { ?>style="margin-left:0;"<?php } else { ?>style="margin-left:0;"<?php } ?>>
					  	<div class="mod c-mod">
                        	
                            <form action="http://www.jiuhe3000.com:80/web/news" name="mainForm" id="mainForm">
                            	 <input type="hidden" name="pageNo" id="pageNo"  value="1" />
      							 <input type="hidden" name="pageSize" id="pageSize"  value="10" />
								 <style type="text/css">
								a.n{color:#787878;}
								a:hover.n{color:red;}
								</style>
	                    	<div class="mod-bd" style="padding-top:0px;">
								<ul class="s-news-list">
								  <?php if(defined('IN_ADMIN')  && !defined('HTML')) {echo "<div class=\"admin_piao\" pc_action=\"content\" data=\"op=content&tag_md5=3f2856377878eeba9d77ba4419bf6a13&action=lists&catid=%24catid&num=20&order=inputtime+desC&page=%24page\"><a href=\"javascript:void(0)\" class=\"admin_piao_edit\">编辑</a>";}$content_tag = pc_base::load_app_class("content_tag", "content");if (method_exists($content_tag, 'lists')) {$pagesize = 20;$page = intval($page) ? intval($page) : 1;if($page<=0){$page=1;}$offset = ($page - 1) * $pagesize;$content_total = $content_tag->count(array('catid'=>$catid,'order'=>'inputtime desC','limit'=>$offset.",".$pagesize,'action'=>'lists',));$pages = pages($content_total, $page, $pagesize, $urlrule);$data = $content_tag->lists(array('catid'=>$catid,'order'=>'inputtime desC','limit'=>$offset.",".$pagesize,'action'=>'lists',));}?>
								  <?php $n=1;if(is_array($data)) foreach($data AS $r) { ?>
                                	<li style="background: none; padding-left:0; padding-top:8px; height: 130px; line-height:normal;">
                                    	<img src="<?php echo thumb($r[thumb],150,105);?>" width="150" height="105" alt="<?php echo $r['title'];?>" style="float:left; padding-right:15px; padding-top:8px">
                                        	<a href="<?php echo $r['url'];?>" title="<?php echo $r['title'];?>" style="float:none;"><font style="font-weight:bold; line-height:28px; font-size:16px; "><?php echo str_cut($r[title],60);?></font></a>
                                            <br><a class="n"  href="<?php echo $r['url'];?>" title="<?php echo $r['title'];?>" style="float:none;"><font style=" line-height:18px;"><?php echo str_cut($r[description],300);?>...</font></a>
											<br><br>  <span><?php echo date('Y-m-d',$r[inputtime]);?></span>
                                    </li>
								
                                	<?php $n++;}unset($n); ?>
								
                                </ul>
					              <div class="function-page fr">
					              
	<script type="text/javascript">
	function goUrl(pageNo,pageSize){
    	document.getElementById("pageNo").value=pageNo;
    	document.getElementById("pageSize").value=pageSize;
    	document.getElementById("mainForm").submit();
	}
	</script>
 
 
 
 
	<div class="page">
      <!--
	  <strong class="page-cur">1</strong>
	  <a href="javascript:goUrl('2','10')">2</a>
	  <a href="javascript:goUrl('2','10');" title="下一页" >下一页</a> 
	  <a href="javascript:goUrl('2','10');"  title="尾页"  >尾页</a>
		<span class="page-total">共<em>&nbsp;14&nbsp;</em>条记录&nbsp;&nbsp;1/2 页</span>
		-->
		<?php echo $pages;?>
		</div> <!-- End page -->   
		  
					              
					         
					              </div><!--E .function-page-->                                 
	                        </div><!--E .mod-bd-->
	                        </form>
	                    </div><!--E .mod-->
                     </div>
			      </div><!--E .col-main->

				<?php if($catid==12 || $catid==13 || $catid==25) { ?>
			      <div class="col-sub" >
				  
                  	<div class="sub-nav-mod">
                    	<ul>
						
							<?php if(defined('IN_ADMIN')  && !defined('HTML')) {echo "<div class=\"admin_piao\" pc_action=\"content\" data=\"op=content&tag_md5=32fa54a631dc977a31aafd9c025fc368&action=category&catid=0&num=100&siteid=%24siteid&order=listorder+ASC\"><a href=\"javascript:void(0)\" class=\"admin_piao_edit\">编辑</a>";}$content_tag = pc_base::load_app_class("content_tag", "content");if (method_exists($content_tag, 'category')) {$data = $content_tag->category(array('catid'=>'0','siteid'=>$siteid,'order'=>'listorder ASC','limit'=>'100',));}?>
							<?php $n=1;if(is_array($data)) foreach($data AS $r) { ?>
							<?php if($r[catid]==12 || $r[catid]==13 || $r[catid]==25) { ?>
                        	<?php if($r[catid]!=$catid) { ?>
							<?php if(defined('IN_ADMIN')  && !defined('HTML')) {echo "<div class=\"admin_piao\" pc_action=\"content\" data=\"op=content&tag_md5=b9ebbfae50acd05f090117c80e53b770&action=lists&catid=%24r%5Bcatid%5D&num=100&siteid=%24siteid&order=listorder+ASC&return=contentdata\"><a href=\"javascript:void(0)\" class=\"admin_piao_edit\">编辑</a>";}$content_tag = pc_base::load_app_class("content_tag", "content");if (method_exists($content_tag, 'lists')) {$contentdata = $content_tag->lists(array('catid'=>$r[catid],'siteid'=>$siteid,'order'=>'listorder ASC','limit'=>'100',));}?>
							
							<li><a href="<?php echo $r['url'];?>" title="<?php echo $r['catname'];?>" <?php if($contentdata) { ?>class="current"<?php } ?>><?php echo $r['catname'];?></a></li>
							
							<?php if($r[catid]!=10) { ?>
							<?php $n=1;if(is_array($contentdata)) foreach($contentdata AS $row) { ?>
							<li><a href="<?php echo $row['url'];?>" title="<?php echo $row['title'];?>"  style="padding-left:0;" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $row['title'];?></a></li>
							<?php $n++;}unset($n); ?>
							<?php } ?>
							<?php if(defined('IN_ADMIN') && !defined('HTML')) {echo '</div>';}?>

							<?php } ?><?php } ?>
                        	<?php $n++;}unset($n); ?>
							<?php if(defined('IN_ADMIN') && !defined('HTML')) {echo '</div>';}?>
                        </ul>
                    </div><!--E .sub-nav-mod->
					
 
                   
                  </div><!--E .col-sub->
				  <?php } ?>
                </div><!--E .grid-s19m0-->           
            </div>
            <!--E 内容模块-->     
            <!--E 内容模块-->     
        </article>
        <!--E 内容-->
<?php include template("content","footer"); ?>
