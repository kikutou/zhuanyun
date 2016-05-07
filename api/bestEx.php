<?php
defined('IN_PHPCMS') or exit('No permission resources.');
//http://183.129.172.49/ems/api/process

$url = "http://183.129.172.49/ems/api/process";


$xmlData = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<FetchBillCodeRequest xmlns:ems="http://express.800best.com">
    <count>2</count>
</FetchBillCodeRequest>';


$msgId=date('ymdHis',time()).floor(microtime()*1000);



//$xmlData='210049691261';
$sServiceType = 'BillCodeFetchRequest';
//$sServiceType = 'RequestQuery';
$sParternId = 'TESTXML';
$sParternKey = '12345';
$sDigest = $xmlData.$sParternKey;
$sDigest = base64_encode(md5($sDigest,true));

$datas  = array('bizData'=>$xmlData,'serviceType'=>$sServiceType,'parternID'=>$sParternId,'digest'=>$sDigest,'msgId'=>$msgId);



$headers = array('Content-type'=>'text/xml','User-Agent' => $_SERVER['HTTP_USER_AGENT'] );

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true); 
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 320); 
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($datas));
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 

$result = curl_exec($ch); 
curl_close($ch);

print_r($result);
exit;
?>