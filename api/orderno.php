<?php
/**
 * 收藏url，必须登录
 * @param url 地址，需urlencode，防止乱码产生
 * @param title 标题，需urlencode，防止乱码产生
 * @return {1:成功;-1:未登录;-2:缺少参数}
 */
defined('IN_PHPCMS') or exit('No permission resources.');

if(empty($_GET['ord'])) {
	showmessage("请通正常路径访问网站！",APP_PATH);
} else {
	$ord = $_GET['ord'];
	$ord = addslashes(urldecode($ord));
	if(CHARSET != 'utf-8') {
		$ord = iconv('utf-8', CHARSET, $ord);
		$ord = addslashes($ord);
	}

	$wdb = pc_base::load_model('waybill_history_model');
	$_wdb = pc_base::load_model('waybill_model');

	$ord = htmlspecialchars($ord);
	$ord_array = explode("\n",$ord);
	
	//$Urls = "http://www.kuaidi100.com/all/sf.shtml?mscomnu=856119921437";

	foreach($ord_array as $oVal){
		$bill = trim($oVal);
		$_rs = $_wdb->get_one(array('waybillid'=>$bill));
		if($_rs['expressnumber']){
					$expressnumber = trim($_rs['expressnumber']);

					$expressurl = str_replace('chaxun?','applyurl?key=9a04067e75ab4699&',trim($_rs['expressurl']));
					$expressurl = str_replace('#',$expressnumber,$expressurl);

					$data_content ="<?php ".chr(13);
					
					$data_content .=chr(13).' defined("IN_PHPCMS") or exit("No permission resources.");';
					
					$data_content .=chr(13).' ';
					$data_content .=chr(13).' $no = trim($_GET["no"]);';
					$data_content .=chr(13).' $result = file_get_contents("'.$expressurl.'");';
					//$data_content .=chr(13).' echo "<iframe name=\"kuaidi100\" src=\"".$result."\" width=600 height=380 marginwidth=0 marginheight=0 hspace=0 vspace=0 frameborder=0 scrolling=no></iframe>";';
					$data_content .=chr(13).' echo $result;exit;';

					$data_content .=chr(13).'?>';

					$filename = PHPCMS_PATH.'api/excacheno/'.$expressnumber.'.php';
					
					//---------------------------------------------write file --------------------------------------------
					$fp = fopen($filename, 'w+');
					$startTime = microtime();
					do{
						$canWrite = flock( $fp, LOCK_EX );
						if (! $canWrite)
							usleep(round(rand( 0, 100 ) * 1000));
					} while((! $canWrite) && ((microtime() - $startTime) < 1000));

					if ($canWrite){
						fwrite($fp, $data_content);
					}

					fclose($fp);  
					//---------------------------------------------write file --------------------------------------------
					


		}
	}

	
	include template('content', 'orderno');
}

?>