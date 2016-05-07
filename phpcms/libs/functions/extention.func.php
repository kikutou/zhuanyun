<?php
/**
 *  extention.func.php 用户自定义函数库
 *
 * @copyright			(C) 2005-2010 PHPCMS
 * @license				http://www.phpcms.cn/license/
 * @lastmodify			2010-10-27
 */



 /**
 *十进制转二进制、八进制、十六进制 不足位数前面补零*
 *
 * @param array $datalist 传入数据array(100,123,130)
 * @param int $bin 转换的进制可以是：2,8,16
 * @return array 返回数据 array() 返回没有数据转换的格式
 * @Author 
 */
function decto_bin($datalist,$bin)
{
    static $arr=array(0,1,2,3,4,5,6,7,8,9,'A','B','C','D','E','F');
    if(!is_array($datalist)) $datalist=array($datalist);
    if($bin==10)return $datalist; //相同进制忽略
    $bytelen=ceil(16/$bin); //获得如果是$bin进制，一个字节的长度
    $aOutChar=array();
    foreach ($datalist as $num)
    {
        $t="";
        $num=intval($num);
    if($num===0)continue;
        while($num>0)
        {
            $t=$arr[$num%$bin].$t;
            $num=floor($num/$bin);
        }
        $tlen=strlen($t);
        if($tlen%$bytelen!=0)
        {
        $pad_len=$bytelen-$tlen%$bytelen;
        $t=str_pad("",$pad_len,"0",STR_PAD_LEFT).$t; //不足一个字节长度，自动前面补充0
        }
        $aOutChar[]=$t;
    }
    return $aOutChar;
}


/**
 *二进制、八进制、十六进制 转十进制*
 *
 * @param array $datalist 传入数据array(df,ef)
 * @param int $bin 转换的进制可以是：2,8,16
 * @return array 返回数据 array() 返回没有数据转换的格式
 * @copyright 
 */
function bin_todec($datalist,$bin)
{
    static $arr=array('0'=>0,'1'=>1,'2'=>2,'3'=>3,'4'=>4,'5'=>5,'6'=>6,'7'=>7,'8'=>8,'9'=>9,'A'=>10,'B'=>11,'C'=>12,'D'=>13,'E'=>14,'F'=>15);
    if(!is_array($datalist))$datalist=array($datalist);
    if($bin==10)return $datalist; //为10进制不转换
    $aOutData=array(); //定义输出保存数组
    foreach ($datalist as $num)
    {
        $atnum=str_split($num); //将字符串分割为单个字符数组
        $atlen=count($atnum);
        $total=0;
        $i=1;
        foreach ($atnum as $tv)
        {
            $tv=strtoupper($tv);
             
            if(array_key_exists($tv,$arr))
            {
                if($arr[$tv]==0)continue;
                $total=$total+$arr[$tv]*pow($bin,$atlen-$i);
            }
            $i++;
        }
        $aOutData[]=$total;
    }
    return $aOutData;
}



/**
	  判断是移动终身还是PC
	 **/
	function is_mobile_or_pc(){
		$ismobile=false;
		$regex_match="/(nokia|iphone|android|motorola|^mot\-|softbank|foma|docomo|kddi|up\.browser|up\.link|";
		$regex_match.="htc|dopod|blazer|netfront|helio|hosin|huawei|novarra|CoolPad|webos|techfaith|palmsource|";
		$regex_match.="blackberry|alcatel|amoi|ktouch|nexian|samsung|^sam\-|s[cg]h|^lge|ericsson|philips|sagem|wellcom|bunjalloo|maui|";	
		$regex_match.="symbian|smartphone|midp|wap|phone|windows ce|iemobile|^spice|^bird|^zte\-|longcos|pantech|gionee|^sie\-|portalmmm|";
		$regex_match.="jig\s browser|hiptop|^ucweb|^benq|haier|^lct|opera\s*mobi|opera\*mini|320x320|240x320|176x220";
		$regex_match.=")/i";		
		
		if(isset($_SERVER['HTTP_X_WAP_PROFILE'])){
			$ismobile = true;
		}
		if(isset($_SERVER['HTTP_PROFILE'])  && $ismobile==false ){
			$ismobile = true;
		}
		if (isset($_SERVER['HTTP_VIA']) && $ismobile==false){
			$ismobile = stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
		}
		if(preg_match($regex_match, strtolower($_SERVER['HTTP_USER_AGENT'])) && $ismobile==false){
			$ismobile = true;
		}

		if (isset($_SERVER['HTTP_ACCEPT']) && $ismobile==false) {
		  // 如果只支持wml并且不支持html那一定是移动设备
		  // 如果支持wml和html但是wml在html之前则是移动设备
		  if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
		   $ismobile = true;
		  }
		 }

		return $ismobile;
	}








function get_hash_hmac_signature($str, $key="!@#zebra@app2011$%^jk(")
{
  $signature = "";
    if (function_exists('hash_hmac'))
    {
        $signature = base64_encode(hash_hmac("sha1", $str, $key, true));
    }
    else
    {
        $blocksize	= 64;
        $hashfunc	= 'sha1';
        if (strlen($key) > $blocksize)
        {
            $key = pack('H*', $hashfunc($key));
        }
        $key	= str_pad($key,$blocksize,chr(0x00));
        $ipad	= str_repeat(chr(0x36),$blocksize);
        $opad	= str_repeat(chr(0x5c),$blocksize);
        $hmac 	= pack(
            'H*',$hashfunc(
                ($key^$opad).pack(
                    'H*',$hashfunc(
                        ($key^$ipad).$str
                    )
                )
            )
        );
        $signature = base64_encode($hmac);
    }

    return $signature;
}



function postCurl($url,$xmlData,$cookie=null){

		$headers = array('Content-type'=>'text/xml','User-Agent' => $_SERVER['HTTP_USER_AGENT'] );

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, true); 
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 320); 
		curl_setopt($ch, CURLOPT_POST, $xmlData); 
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 
		if($cookie){
			curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie); 
			curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
		}
		$result = curl_exec($ch); 
		curl_close($ch);

		return $result;
	}


function postGets($url,$xmlData){

		$matches = @parse_url($url);
		$host = $matches['host'];
		$path = $matches['path'] ? $matches['path'].($matches['query'] ? '?'.$matches['query'] : '') : '/';
		$port = !empty($matches['port']) ? $matches['port'] : 80;
		$contentLength = strlen($xmlData);
		$fp = @fsockopen($host, $port);
		@fputs($fp, "POST $path HTTP/1.0\r\n");
		@fputs($fp, "Host: $host\r\n");
		@fputs($fp, "User-Agent: ".$_SERVER['HTTP_USER_AGENT']."\r\n");
		@fputs($fp, "Content-Type: text/xml\r\n");
		@fputs($fp, "Content-Length: $contentLength\r\n");
		@fputs($fp, "Connection: close\r\n");
		@fputs($fp, "\r\n"); 
		@fputs($fp, $xmlData);
		$result = '';
	
		while (!@feof($fp)) {
			$result .= @fgets($fp, 1024);
		}

		return $result;
	}


function s_r_v_ip() { 
    if (isset($_SERVER)) { 
        if($_SERVER['SERVER_ADDR']) {
            $server_ip = $_SERVER['SERVER_ADDR']; 
        } else { 
            $server_ip = $_SERVER['LOCAL_ADDR']; 
        } 
    } else { 
        $server_ip = getenv('SERVER_ADDR');
    } 
    return $server_ip; 
}


function weight_lbs_to_kg($weight){
		return round((floatval($weight) * 0.4535924),2);
}

function get__order__counter(){

		$odb = pc_base::load_model("ordernumber_model");
		
		$order_array=array('addtime'=>SYS_TIME);
		$odb->insert($order_array);
		$num = $odb->insert_id();

		return $num;
}


function get__member_number__counter(){

		$odb = pc_base::load_model("member_number_model");
		
		$order_array=array('addtime'=>SYS_TIME);
		$odb->insert($order_array);
		$num = $odb->insert_id();

		return $num;
}

 function get__rand__char($length,$flag=0){
   $str = null;
	$strPol_prex = "ABCDEFGH";
	$str_prex="";

   if($flag==1){
		$strPol = "0123456789";
   }else{
	    $length = $length-1;
		$strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		
		$str_prex = $strPol_prex[rand(0,(strlen($strPol_prex)-1))];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
   }
   $max = strlen($strPol)-1;

   for($i=0;$i<$length;$i++){
	 $str.=$strPol[rand(0,$max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
   }

   return $str_prex.$str;
  }



  function get_ship_fee($totalweight=0,$key=0,$country='中国'){

			$newcountry = $country;
			
			//$country = iconv('gb2312','utf-8',$country);
			
			$totalship = 0;
			$stotalship = "";
			$stotalshipremark = "";

			$area_datas = getcache('get__linkage__lists', 'commons');

			foreach($area_datas as $v){
				if(trim($v['name'])==$country){
					$country = $v['linkageid'];
				}
			}

			
			$feedatas = getcache('get__enum_data_48_'.$country, 'commons');

			
			if(!is_arraY($feedatas)){
					$country = iconv('gb2312','utf-8',$newcountry);
					foreach($area_datas as $v){
						if(trim($v['name'])==$newcountry){
							$country = $v['linkageid'];
						}
					}
					$feedatas = getcache('get__enum_data_48_'.$country, 'commons');
			}

			$totalweight = floatval(str_replace("g","",$totalweight));
			foreach($feedatas as $fee){
				$fees = floatval(str_replace("g","",trim($fee['title'])));
				
				
				if($totalweight<=$fees){
					$stotalship = trim($fee['value']);
					$stotalshipremark = trim($fee['remark']);
					break;
				}
			}

			
			
			$shiparr=explode('|',$stotalship);
			$remarkarr=explode('|',$stotalshipremark);
			
			$newarr=array();	
			foreach($remarkarr as $k=>$item){
				$newarr[$item]=$shiparr[$k];
			}

			return floatval($newarr[$key]);
  }

  function get_secure_fee($totalweight=0,$key=0){
			$totalship = 0;
			$stotalship = "";
			$stotalshipremark = "";
			
			$feedatas = getcache('get__enum_data_152', 'commons');
			$totalweight = floatval($totalweight);
			foreach($feedatas as $fee){
				$fees = str_replace("g","",trim($fee['title']));
				
				if($totalweight<=$fees){
					$stotalship = trim($fee['value']);
					$stotalshipremark = trim($fee['remark']);
					break;
				}
			}
			$shiparr=explode('|',$stotalship);
			$remarkarr=explode('|',$stotalshipremark);
			
			$newarr=array();	
			foreach($remarkarr as $k=>$item){
				$newarr[$item]=$shiparr[$k];
			}

			return floatval($newarr[$key]);
  }

  function get_operaction_fee($totalweight=0){
			$totalship = 0;
			
			$feedatas = getcache('get__enum_data_123', 'commons');
			$totalweight = floatval($totalweight);
			foreach($feedatas as $fee){
				$fees = str_replace("g","",trim($fee['title']));

				if($totalweight<=$fees){
					$totalship = trim($fee['value']);
					break;
				}
			}
			

			return floatval($totalship);
  }

 
  function get_common_shipline($id=0){
		$datas = getcache('get__turnway__lists', 'commons');

		if($id>0){
			
			$title="";
			foreach($datas as $v){
				if($id==$v['aid']){
					$title=$v['code'];
				}
			}
			return $title;
		}else{
			
			return $datas;
		}
	}

	
function common____orderno(){
	mt_srand((double )microtime() * 1000000 );
	return date("YmdHis" ).str_pad( mt_rand( 1, 999 ), 3, "0", STR_PAD_LEFT );
}

?>