<?php


defined('IN_PHPCMS') or exit('No permission resources.');

/**
 * 模块信息
 */
if (isset($set_modules) && $set_modules == TRUE)
{
    $i = isset($modules) ? count($modules) : 0;

    /* 代码 */
    $modules[$i]['code']    = basename(__FILE__, '.class.php');

	$modules[$i]['name']    = L('tenpay', '', 'pay');   
    
    /* 描述对应的语言项 */
    $modules[$i]['desc']    =  L('tenpay_tip', '', 'pay');

    /* 是否支持货到付款 */
    $modules[$i]['is_cod']  = '0';

    /* 是否支持在线支付 */
    $modules[$i]['is_online']  = '1';

    /* 作者 */
    $modules[$i]['author']  = 'Tenpay';

    /* 网址 */
    $modules[$i]['website'] = 'http://www.tenpay.com/';

    /* 版本号 */
    $modules[$i]['version'] = 'V3.0';

    /* 配置信息 */
    $modules[$i]['config'] = array(
        array('name' => 'tenpay_partner', 'type' => 'text',   'value' => ''),
        array('name' => 'tenpay_key',  'type' => 'text',   'value' => ''),
    );

    return;
}

pc_base::load_app_class('pay_abstract','','0');
pc_base::load_app_class('transport','','0');


class Tenpay extends paymentabstract
{
    /**
     * 构造函数
     *
     * @access  public
     * @param
     *
     * @return void
     */
	var $t          = null;

    function __construct($config = array())
    {
        if (!empty($config)) $this->set_config($config);       
		$this->config['gateway_url'] = 'https://gw.tenpay.com/gateway/pay.htm';
		$this->config['gateway_method'] = 'POST';
		$this->config['notify_url'] = return_url('tenpay',1);
		$this->config['return_url'] = return_url('tenpay');
		$this->t = new transport(-1, -1, -1, false);
    }
	
	public function getpreparedata() {
		$data="";
		
		//组织订单信息
		/* 交易参数 */
        $prepare_data = array(
            'partner'              => $this->config['tenpay_partner'],
            'out_trade_no'         => $this->order_info['id'],                           //订单号
            'total_fee'            => floatval($this->product_info['price'])*100,        //总金额
            'notify_url'           => $this->config['notify_url'],  //返回地址
            'return_url'           => $this->config['return_url'],  //提醒地址
            'body'                 => $this->product_info['name'],                            //交易描述
            'bank_type'            => '0',                       //交易类型  默认财付通
            //用户ip
            'spbill_create_ip'     => $_SERVER['REMOTE_ADDR'],          //交易ip
            'fee_type'             => '1',                        //币种  1 人民币
            'subject'              => $this->product_info['name'],                            //商品名称
            //系统可选参数
            'sign_type'            => 'MD5',                            //加密方式
            'service_version'      => '1.0',                            //接口版本号 默认1.0
            'input_charset'        => 'utf-8',                         //系统编码  'GBK'
            'sign_key_index'       => '1',                              //密钥序号
            //业务可选参数
            'attach'               => '',            //附加数据 原样返回  默认为空
            'product_fee'          => '',                 //商品费用
            'transport_fee'        => '0',                //物流费用
            'time_start'           => date("YmdHis"),     //订单生成时间   date("YmdHis")
            'time_expire'          => '',                 //订单失效时间
            'buyer_id'             => '',                 //买方财付通帐号
            'goods_tag'            => '',                 //商品标记
            'trade_mode'           => '1',    //交易模式（1.即时到帐，2.中介担保，3.后台选择）
            'transport_desc'       => '',                 //物流说明
            'trans_type'           => '1',        //交易类型
            'agentid'              => '',                 //平台ID
            'agent_type'           => '',             //代理模式（0.无代理，1.表示卡易售模式，2.表示网店模式）
            'seller_id'            => ''                  //卖家商户号
        );
        ksort($prepare_data);
        reset($prepare_data);
        $param = '';
        $sign  = '';
        foreach ($prepare_data AS $key => $val)
        {
            $param .= "$key=" .urlencode($val). "&";
            if("" != $val && "sign" != $key) {
                $sign  .= "$key=$val&";
            }
        }
        $param = substr($param, 0, -1);
        $sign .= "key=".$this->config['tenpay_key'];
        $sign = strtolower(md5($sign)); 
	
		
		// 数字签名
		$prepare_data['sign'] = $sign;

		return $prepare_data;
	}
	
	/**
	 * 客户端接收数据
	 * 状态码说明  （0 交易完成 1 交易失败 2 交易超时 3 交易处理中 4 交易未支付）
	 */
    public function receive() {

		
		$receive_sign = $_GET['sign'];

		$receive_data=array();
    	$receive_data = $this->filterParameter($_GET);

        $trade_state    = $receive_data['trade_state'];
        $order_amount      = $receive_data['total_fee'];
        $order_no = trim($receive_data['out_trade_no']);
    
        /* 检查数字签名是否正确 */
     
        $sign = '';

		ksort($receive_data);
        reset($receive_data);

		foreach ($receive_data AS $key => $val)
        {
            $sign  .= "$key=$val&";
        }

        $sign .= "key=".$this->config['tenpay_key'];
	
        if (strtolower(md5($sign)) == strtolower($receive_sign))
        {
            if($trade_state==0 &&$this->Verification($receive_data['notify_id']))
            {
			    $return_data['order_id'] = $order_no;
				$return_data['order_total'] = $order_amount;
				$return_data['price'] = $order_amount;
				$return_data['order_status'] = 0;

				return $return_data;
            }
               
        }else {
			error_log(date('m-d H:i:s',SYS_TIME).'| tenpay receive GET: illegality notice : flase |'."\r\n", 3, CACHE_PATH.'pay_error_log.php');			
			showmessage(L('illegal_sign'));
			return false;
		}
		
    }	

    /**
	 * GET接收数据
	 * 状态码说明  （0 交易完成 1 交易失败 2 交易超时 3 交易处理中 4 交易未支付）
	 */
    public function notify() {

		
		$receive_sign = $_GET['sign'];
		
		$receive_data=array();

		if($receive_sign){
    		$receive_data = $this->filterParameter($_GET);
		}else{
			$receive_data = $this->filterParameter($_POST);
		}

        $trade_state    = $receive_data['trade_state'];
        $order_amount      = $receive_data['total_fee'];
        $order_no = trim($receive_data['out_trade_no']);
     
        /* 检查数字签名是否正确 */
   
        $sign = '';

		ksort($receive_data);
        reset($receive_data);

		foreach ($receive_data AS $key => $val)
        {
            $sign  .= "$key=$val&";
        }

        $sign .= "key=".$this->config['tenpay_key'];
    
        if (strtolower(md5($sign)) == strtolower($receive_data['sign']))
        {
            if($trade_state==0 &&$this->Verification($receive_data['notify_id']))
            {
			    $return_data['order_id'] = $order_no;
				$return_data['order_total'] = $order_amount;
				$return_data['price'] = $order_amount;
				$return_data['order_status'] = 0;

				return $return_data;
            }

			error_log(date('m-d H:i:s',SYS_TIME).'| tenpay notify succ GET: illegality notice : flase |'."\r\n", 3, CACHE_PATH.'pay_error_log.php');
               
        }else {
			error_log(date('m-d H:i:s',SYS_TIME).'| tenpay notify fail GET: illegality notice : flase |'."\r\n", 3, CACHE_PATH.'pay_error_log.php');			
			showmessage(L('illegal_sign'));
			return false;
		}
		
		
    }
    	
    /**
     * 相应服务器应答状态
     * @param $result
     */
    public function response($result) {
    	if (FALSE == $result) echo 'fail';
		else echo 'success';
    }
    
    /**
     * 返回字符过滤
     * @param $parameter
     */
	private function filterParameter($parameter)
	{
		$para = array();
		foreach ($parameter as $key => $value)
		{
			if ('sign' == $key  || '' == $value || 'm' == $key  || 'a' == $key  || 'c' == $key   || 'code' == $key ) continue;
			else $para[$key] = $value;
		}
		return $para;
	}
    

	private function Verification($notify_id)
	{
        $tenpay_url="https://gw.tenpay.com/gateway/simpleverifynotifyid.xml";
        $send_str['partner']=$this->config['tenpay_partner'];
        $send_str['notify_id']=$notify_id;
	    ksort($send_str);
        $sign_notify = '';
        foreach ($send_str AS $key => $val)
        {
            if("" != $val && "sign" != $key) 
			{
                $sign_notify  .= "$key=$val&";
            }
        }
        
		$sign_notify .= "key=".$this->config['tenpay_key'];
		$send_str['sign']=strtolower(md5($sign_notify));
		$response= $this->t->request($tenpay_url, $send_str);
		
		if(!empty($response['body']))
		{
		   if(!function_exists('simplexml_load_string')||!function_exists('iconv'))
           {
              $result=$this->get_value_byxml_php4($response['body']);
           }
		   else
		   {
		      $result=$this->get_value_byxml($response['body']); 
		   }
		}
		else
		{
           return false;
		}
		if($result['retcode']==0)
		{
           return true;
		}
		else
		{
           return false;
		}

	}
    private function get_value_byxml($content)
    {
		$xml = simplexml_load_string($content);
		$encode = $this->getXmlEncode($content);
		if($xml && $xml->children()) 
		{
		   foreach ($xml->children() as $node)
		    {
				//有子节点
			   if($node->children()) 
			   {
					$k = $node->getName();
					$nodeXml = $node->asXML();
					$v = substr($nodeXml, strlen($k)+2, strlen($nodeXml)-2*strlen($k)-5);
					
			   }
			   else 
			   {
					$k = $node->getName();
					$v = (string)$node;
			   }
				
			   if($encode!="" && $encode != "UTF-8")
				{
					$k = iconv("UTF-8", $encode, $k);
					$v = iconv("UTF-8", $encode, $v);
				}
				
			   $res[$k]= $v;		
		   }
		   return $res;
		}
		else
		{
           return false;
		}
	}
	
	//解决PHP4老环境下不支持simplexml和 iconv功能的函数
    private function get_value_byxml_php4($content)
    {
        $encode = $this->getXmlEncode($content);
        $result = str_replace('<?xml version=\"1.0\" encoding='.$encode.'?>','',$result);
        $p = xml_parser_create();
        xml_parse_into_struct($p, $result, $vals, $index);
        xml_parser_free($p);
        
        foreach($vals as $key => $value)
        {
            if($encode!="" && $encode != "UTF-8")
	          {
		        $k = mb_convert_encoding(strtolower($value['tag']), $encode, "UTF-8");
		        $v = mb_convert_encoding($value['value'], $encode, "UTF-8");								
	          }
	        else 
	         {
		        $k = strtolower($value['tag']);
		        $v = $value['value'];
	         }
     
	  
	        $res[$k]= $v;
	  
        }
        return $res;
     }


	//获取xml编码
	private function getXmlEncode($xml) {
		$ret = preg_match ("/<?xml[^>]* encoding=\"(.*)\"[^>]* ?>/i", $xml, $arr);
		if($ret) {
			return strtoupper ( $arr[1] );
		} else {
			return "";
		}
	}

  
}

?>