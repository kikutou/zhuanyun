<?php
$no = trim($_GET['no']);
$uid = trim($_GET['uid']);
 
if(file_exists(PHPCMS_PATH.'api/cacheno/'.$uid.'_'.$no.'.php')){
	include_once(PHPCMS_PATH.'api/cacheno/'.$uid.'_'.$no.'.php');
}else{
	$result = array("color"=>"#FF0000","time"=>time());
	print_r(json_encode($result));
}
?>