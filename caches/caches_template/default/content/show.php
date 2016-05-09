<?php defined('IN_PHPCMS') or exit('No permission resources.'); ?><?php include template("content","header"); ?>   
 
        <!--S 内容-->
 		<article class="content">
        	<!--S 面包屑-->
            <div class="layout crumbs">
                <em>当前位置：</em><a href="<?php echo siteurl($siteid);?>">首页</a><span> &gt; </span>
                <?php echo catpos($catid);?> <?php echo $title;?>
            </div>
            <!--E 面包屑-->                    	
        	<!--S 内容模块-->
        	<!--S 内容模块-->
        	<div class="content-mod">
            	<div class="layout sub-layout radius-five grid-s15m0">
			      <div class="col-main">
			       	 <div class="main-wrap" style="margin-left:0;">
					  	<div class="mod c-mod">
	                    	<div class="mod-bd">
                          		<div class="c-news-hd">
                                	<h3><?php echo $title;?></h3>
                                    <span><?php echo $inputtime;?></span>
                                </div><!--E .c-news-hd-->
                                <div class="c-news-bd">
								<?php echo $content;?>
                                </div><!--E .c-news-bd-->
	                        </div><!--E .mod-bd-->
	                    </div><!--E .mod-->
                     </div>
			      </div><!--E .col-main-->

			      </div><!--E .col-sub-->
                </div><!--E .grid-s19m0-->           
            </div>
            <!--E 内容模块-->     
            <!--E 内容模块-->     
        </article>
        <!--E 内容-->
<?php include template("content","footer"); ?>