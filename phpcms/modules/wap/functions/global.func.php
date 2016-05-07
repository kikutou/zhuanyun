<?php

 

	function get__all__urls(){
		$this__all__urls=array();
		$this__all__urls[0]['about']="关于我们";
		$this__all__urls[1]['about']="/index.php?m=wap&c=index&a=about";

		$this__all__urls[0]['packageinfo']="日淘教程";
		$this__all__urls[1]['packageinfo']="/index.php?m=wap&c=index&a=news&catid=19";

		$this__all__urls[0]['waybillinfo']="包裹预报";
		$this__all__urls[1]['waybillinfo']="/index.php?m=wap&c=waybill&a=add";

		$this__all__urls[0]['address']="收货地址";
		$this__all__urls[1]['address']="/index.php?m=wap&c=index&a=orderno";

		$this__all__urls[0]['package']="新手上路";
		$this__all__urls[1]['package']="/index.php?m=wap&c=index&a=news&catid=18";

		$this__all__urls[0]['waybill']="我的运单";
		$this__all__urls[1]['waybill']="/index.php?m=wap&c=waybill&a=init";

		$this__all__urls[0]['news']="日淘资讯";
		$this__all__urls[1]['news']="/index.php?m=wap&c=index&a=news";

		$this__all__urls[0]['userinfo']="个人资料";
		$this__all__urls[1]['userinfo']="/index.php?m=member&c=ajax_index&a=account_manage_info";

		$this__all__urls[0]['amount']="财务中心";
		$this__all__urls[1]['amount']="/index.php?m=wap&c=deposit&a=init";

		$this__all__urls[0]['register']="注册";
		$this__all__urls[1]['register']="/index.php?m=wap&c=member&a=register";
		
		$this__all__urls[0]['login']="登录";

		$this__all__urls[1]['login']="/index.php?m=wap&c=member&a=login";
		$this__all__urls[1]['logout']="/index.php?m=wap&c=member&a=logout";

		$this__all__urls[0]['addmoney']="充值中心";
		$this__all__urls[1]['addmoney']="/index.php?m=wap&c=deposit&a=pay";

		return $this__all__urls;
}

	function __L__($key=''){
		$LANG['select']['unpay'] = '<font color=red>未支付</font>';
		$LANG['select']['succ'] = '<font color=green>成功</font>';
		$LANG['select']['progress'] = '处理中';
		$LANG['select']['cancel'] = '取消';
		if($key!='select')
			return $LANG['select'][$key];
		else
			return $LANG;
	}

	/**
	 * 获取当前页面完整URL地址
	 */
	 function _get_url__() {
		$sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
		$php_self = $_SERVER['PHP_SELF'] ? _safe_replace__($_SERVER['PHP_SELF']) : _safe_replace__($_SERVER['SCRIPT_NAME']);
		$path_info = isset($_SERVER['PATH_INFO']) ? _safe_replace__($_SERVER['PATH_INFO']) : '';
		$relate_url = isset($_SERVER['REQUEST_URI']) ? _safe_replace__($_SERVER['REQUEST_URI']) : $php_self.(isset($_SERVER['QUERY_STRING']) ? '?'._safe_replace__($_SERVER['QUERY_STRING']) : $path_info);
		return $sys_protocal.(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '').$relate_url;
	}
	
	/**
	 * 安全过滤函数
	 *
	 * @param $string
	 * @return string
	 */
	 function _safe_replace__($string) {
		$string = str_replace('%20','',$string);
		$string = str_replace('%27','',$string);
		$string = str_replace('%2527','',$string);
		$string = str_replace('*','',$string);
		$string = str_replace('"','&quot;',$string);
		$string = str_replace("'",'',$string);
		$string = str_replace('"','',$string);
		$string = str_replace(';','',$string);
		$string = str_replace('<','&lt;',$string);
		$string = str_replace('>','&gt;',$string);
		$string = str_replace("{",'',$string);
		$string = str_replace('}','',$string);
		$string = str_replace('\\','',$string);
		return $string;
	}

	/**
	 *  post数据
	 *  @param string $url		post的url
	 *  @param int $limit		返回的数据的长度
	 *  @param string $post		post数据，字符串形式username='dalarge'&password='123456'
	 *  @param string $cookie	模拟 cookie，字符串形式username='dalarge'&password='123456'
	 *  @param string $ip		ip地址
	 *  @param int $timeout		连接超时时间
	 *  @param bool $block		是否为阻塞模式
	 *  @return string			返回字符串
	 */
	
	 function _post_data($url, $limit = 0, $post = '', $cookie = '', $ip = '', $timeout = 30, $block = true) {
		$return = '';
		$matches = parse_url($url);
		$host = $matches['host'];
		$path = $matches['path'] ? $matches['path'].($matches['query'] ? '?'.$matches['query'] : '') : '/';
		$port = !empty($matches['port']) ? $matches['port'] : 80;
		$siteurl = _get_url__();
		if($post) {
			$out = "POST $path HTTP/1.1\r\n";
			$out .= "Accept: */*\r\n";
			$out .= "Referer: ".$siteurl."\r\n";
			$out .= "Accept-Language: zh-cn\r\n";
			$out .= "Content-Type: application/x-www-form-urlencoded\r\n";
			$out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
			$out .= "Host: $host\r\n" ;
			$out .= 'Content-Length: '.strlen($post)."\r\n" ;
			$out .= "Connection: Close\r\n" ;
			$out .= "Cache-Control: no-cache\r\n" ;
			$out .= "Cookie: $cookie\r\n\r\n" ;
			$out .= $post ;
		} else {
			$out = "GET $path HTTP/1.1\r\n";
			$out .= "Accept: */*\r\n";
			$out .= "Referer: ".$siteurl."\r\n";
			$out .= "Accept-Language: zh-cn\r\n";
			$out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
			$out .= "Host: $host\r\n";
			$out .= "Connection: Close\r\n";
			$out .= "Cookie: $cookie\r\n\r\n";
		}
		$fp = @fsockopen(($ip ? $ip : $host), $port, $errno, $errstr, $timeout);
		if(!$fp) return '';
	
		stream_set_blocking($fp, $block);
		stream_set_timeout($fp, $timeout);
		@fwrite($fp, $out);
		$status = stream_get_meta_data($fp);
	
		if($status['timed_out']) return '';	
		while (!feof($fp)) {
			if(($header = @fgets($fp)) && ($header == "\r\n" ||  $header == "\n"))  break;				
		}
		
		$stop = false;
		while(!feof($fp) && !$stop) {
			$data = fread($fp, ($limit == 0 || $limit > 8192 ? 8192 : $limit));
			$return .= $data;
			if($limit) {
				$limit -= strlen($data);
				$stop = $limit <= 0;
			}
		}
		@fclose($fp);
		
		//部分虚拟主机返回数值有误，暂不确定原因，过滤返回数据格式
		$return_arr = explode("\n", $return);
		if(isset($return_arr[1])) {
			$return = trim($return_arr[1]);
		}
		unset($return_arr);
		
		return $return;
	}

/**
 * 输出xml头部信息
 */
function wmlHeader() {
	echo "<?xml version=\"1.0\" encoding=\"".CHARSET."\"?>\n";
}
/**
 * 解析分类url路径
 */
function list_url($typeid) {
    return WAP_SITEURL."&amp;a=lists&amp;typeid=$typeid";
}

function bigimg_url($url,$w='') {
	return WAP_SITEURL.'&amp;a=big_image&amp;url='.base64_encode($url).'&amp;w='.$w;
}
/**
 * 解析内容url路径
 * $catid 栏目id
 * $typeid wap分类id
 * $id 文章id
 */
function show_url($catid, $id, $typeid='') {
	global $WAP;
	if($typeid=='') {
		$types = getcache('wap_type','wap');
		foreach ($types as $type) {
			if($type['cat']==$catid) {
				$typeid = $type['typeid'];
				break;
			}
		}
	}
    return WAP_SITEURL."&amp;a=show&amp;catid=$catid&amp;typeid=$typeid&amp;id=$id";
}


/**
 * 当前路径 
 * 返回指定分类路径层级
 * @param $typeid 分类id
 * @param $symbol 分类间隔符
 */
function wap_pos($typeid, $symbol=' > '){
	$type_arr = array();
	$type_arr = getcache('wap_type','wap');
	if(!isset($type_arr[$typeid])) return '';
	$pos = '';
	if($type_arr[$typeid]['parentid']!=0) {
		$pos = '<a href="'.list_url($type_arr[$typeid]['parentid']).'">'.$type_arr[$type_arr[$typeid]['parentid']]['typename'].'</a>'.$symbol;
	}
	$pos .= '<a href="'.list_url($typeid).'">'.$type_arr[$typeid]['typename'].'</a>'.$symbol;
	return $pos;
}

/**
 * 获取子分类
 */
function subtype($parentid = NULL, $siteid = '') {
	if (empty($siteid)) $siteid = $GLOBALS['siteid'];
	$types = getcache('wap_type','wap');
	
	return array();
}
/**
 * 分页函数
 * 
 * @param $num 信息总数
 * @param $curr_page 当前分页
 * @param $perpage 每页显示数
 * @param $urlrule URL规则
 * @param $array 需要传递的数组，用于增加额外的方法
 * @return 分页
 */
function wpa_pages($num, $curr_page, $perpage = 20, $urlrule = '', $array = array(),$setpages = 10) {
	if(defined('URLRULE')) {
		$urlrule = URLRULE;
		$array = $GLOBALS['URL_ARRAY'];
	} elseif($urlrule == '') {
		$urlrule = url_par('page={$page}');
	}
	$multipage = '';
	if($num > $perpage) {
		$page = $setpages+1;
		$offset = ceil($setpages/2-1);
		$pages = ceil($num / $perpage);
		if (defined('IN_ADMIN') && !defined('PAGES')) define('PAGES', $pages);
		$from = $curr_page - $offset;
		$to = $curr_page + $offset;
		$more = 0;
		if($page >= $pages) {
			$from = 2;
			$to = $pages-1;
		} else {
			if($from <= 1) {
				$to = $page-1;
				$from = 2;
			}  elseif($to >= $pages) { 
				$from = $pages-($page-2);  
				$to = $pages-1;  
			}
			$more = 1;
		} 
		$multipage .= $curr_page.'/'.$pages;
		if($curr_page>0) {
			$multipage .= ' <a href="'.pageurl($urlrule, $curr_page-1, $array).'">'.L('previous').'</a>';
		}
		if($curr_page==$pages) {
			$multipage .= ' <a href="'.pageurl($urlrule, $curr_page, $array).'">'.L('next').'</a>';
		} else {
			$multipage .= ' <a href="'.pageurl($urlrule, $curr_page+1, $array).'">'.L('next').'</a>';
		}
		
	}
	return $multipage;
}

/**
 * 过滤内容为wml格式
 */
function wml_strip($string) {
    $string = str_replace(array('&nbsp;', '&amp;', '&quot;', '&#039;', '&ldquo;', '&rdquo;', '&mdash;', '&lt;', '&gt;', '&middot;', '&hellip;', '&'), array(' ', '&', '"', "'", '“', '”', '—', '{<}', '{>}', '·', '…', '&amp;'), $string);
	return str_replace(array('{<}', '{>}'), array('&lt;', '&gt;'), $string);
}

/**
 * 内容中图片替换
 */
function content_strip($content,$ishow=1) {
    
   $content = preg_replace('/<img[^>]*src=[\'"]?([^>\'"\s]*)[\'"]?[^>]*>/ie', "wap_img('$1',$ishow)", $content);
      
   //匹配替换过的图片
      
   $content = strip_tags($content,'<b><br><img><p><div><a>');
   return $content;
}

/**
 * 图片过滤替换
 */
function wap_img($url,$ishow) {
	$wap_site = getcache('wap_site','wap');
	$wap_setting = string2array($wap_site[$GLOBALS['siteid']]['setting']);
	$show_big = bigimg_url($url);
	if($ishow==1) $show_tips = '<br><a href="'.$show_big.'">浏览大图</a>';
	return '<img src="'.thumb($url,$wap_setting['thumb_w'],$wap_setting['thumb_h']).'">'.$show_tips;
}

function strip_selected_tags($text) {
    $tags = array('em','font','h1','h2','h3','h4','h5','h6','hr','i','ins','li','ol','p','pre','small','span','strike','strong','sub','sup','table','tbody','td','tfoot','th','thead','tr','tt','u','div','span');
    $args = func_get_args();
    $text = array_shift($args);
    $tags = func_num_args() > 2 ? array_diff($args,array($text)) : (array)$tags;
    foreach ($tags as $tag){
        if( preg_match_all( '/<'.$tag.'[^>]*>([^<]*)<\/'.$tag.'>/iu', $text, $found) ){
            $text = str_replace($found[0],$found[1],$text);
        }
    }
    return $text;
}

/**
 * 生成文章分页方法
 */

function content_pages($num, $curr_page,$pageurls,$showremain = 1) {
	$multipage = '';
	$page = 11;
	$offset = 4;
	$pages = $num;
	$from = $curr_page - $offset;
	$to = $curr_page + $offset;
	$more = 0;
	if($page >= $pages) {
		$from = 2;
		$to = $pages-1;
	} else {
		if($from <= 1) {
			$to = $page-1;
			$from = 2;
		} elseif($to >= $pages) {
			$from = $pages-($page-2);
			$to = $pages-1;
		}
		$more = 1;
	}
	$multipage .='('.$curr_page.'/'.$num.')';
	if($curr_page>0) {
		$perpage = $curr_page == 1 ? 1 : $curr_page-1;
		$multipage .= '<a class="a1" href="'.$pageurls[$perpage][1].'">'.L('previous').'</a>';
	}
	
	if($curr_page<$pages) {
		if($curr_page<$pages-5 && $more) {
			$multipage .= ' <a class="a1" href="'.$pageurls[$curr_page+1][1].'">'.L('next').'</a>';
		} else {
			$multipage .= ' <a class="a1" href="'.$pageurls[$curr_page+1][1].'">'.L('next').'</a>';
		}
	} elseif($curr_page==$pages) {
		$multipage .= ' <a class="a1" href="'.$pageurls[$curr_page][1].'">'.L('next').'</a>';
	}
	if($showremain) $multipage .="| <a href='".$pageurls[$curr_page][1]."&remains=true'>剩余全文</a>";
	return $multipage;
}

/**
 * 多图分页处理
 */

function pic_pages($array) {
	if(!is_array($array) || empty($array)) return false;
	foreach ($array as $k=>$p) {
		$photo_arr[$k]='<img src="'.$p['url'].'"><br>'.$p['alt'];
	}
	$photo_page = @implode('[page]', $photo_arr);
	$photo_page =content_strip(wml_strip($photo_page),0);
	return $photo_page;
}

/**
 * 获取热词
 */
function hotword() {
	$site = getcache('wap_site','wap');
	$setting = string2array($site[$GLOBALS['siteid']]['setting']);
	$hotword = $setting['hotwords'];
	$hotword_arr = explode("\n", $hotword);
	if(is_array($hotword_arr) && count($hotword_arr) > 0) {
		foreach($hotword_arr as $_k) {
			$v = explode("|",$_k);
			$hotword_string .= '<a href="'.$v[1].'">'.$v[0].'</a>&nbsp';
		}		
	}
	return $hotword_string;
}
?>