<?php
/**
 * 获取快递接口
 */
defined('IN_PHPCMS') or exit('No permission resources.'); 

if(isset($_GET['siteid'])) {
	$siteid = intval($_GET['siteid']);
} else {
	$siteid = 1;
}
$siteid = $GLOBALS['siteid'] = max($siteid,1);
$setting = getcache('kuaidi', 'commons');

$datatype = intval($setting[$siteid]['datatype']);
$order = $setting[$siteid]['order']==''?'asc':$setting[$siteid]['order'];
$AppKey = $setting[$siteid]['key'];

$typeCom = trim($_GET["com"]);//快递公司
$typeNu = trim($_GET["nu"]);  //快递单号

$url ='http://api.kuaidi100.com/api?id='.$AppKey.'&com='.$typeCom.'&nu='.$typeNu.'&show='.$datatype.'&muti=1&order='.$order;

//请勿删除变量$powered 的信息，否者本站将不再为你提供快递接口服务。
$powered = '查询数据由：<a href="http://kuaidi100.com" target="_blank">KuaiDi100.Com （快递100）</a> 网站提供 ';

//优先使用curl模式发送数据
if (function_exists('curl_init') == 1){
  $curl = curl_init();
  curl_setopt ($curl, CURLOPT_URL, $url);
  curl_setopt ($curl, CURLOPT_HEADER,0);
  curl_setopt ($curl, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt ($curl, CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
  curl_setopt ($curl, CURLOPT_TIMEOUT,5);
  $get_content = curl_exec($curl);
  curl_close ($curl);
}else{
  $snoopy = pc_base::load_app_class("snoopy","kuaidi");
  $snoopy->referer = 'http://www.google.com/';//伪装来源
  $snoopy->fetch($url);
  $get_content = $snoopy->results;
}

if(CHARSET != 'utf-8') {
	$get_content = iconv('utf-8', CHARSET, $get_content);
} 
//echo $get_content;
print_r($get_content);
exit();
?>