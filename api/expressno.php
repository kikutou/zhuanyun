<?php
$no = trim($_GET['no']);
if(file_exists(PHPCMS_PATH.'api/excacheno/'.$no.'.php')){
	include_once(PHPCMS_PATH.'api/excacheno/'.$no.'.php');
}
?>