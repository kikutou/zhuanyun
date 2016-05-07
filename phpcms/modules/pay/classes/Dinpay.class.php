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

	$modules[$i]['name']    = L('dinpay', '', 'pay');   
    
    /* 描述对应的语言项 */
    $modules[$i]['desc']    =  L('dinpay_tip', '', 'pay');

    /* 是否支持货到付款 */
    $modules[$i]['is_cod']  = '0';

    /* 是否支持在线支付 */
    $modules[$i]['is_online']  = '1';

    /* 作者 */
    $modules[$i]['author']  = 'Dinpay';

    /* 网址 */
    $modules[$i]['website'] = 'http://www.dinpay.com/';

    /* 版本号 */
    $modules[$i]['version'] = 'V3.0';

    /* 配置信息 */
    $modules[$i]['config'] = array(
        array('name' => 'k_mid', 'type' => 'text',   'value' => ''),
        array('name' => 'k_pass',  'type' => 'text',   'value' => ''),
        /*array('name' => 'k_memo1',  'type' => 'text',   'value' => 'ecshop'),
        array('name' => 'k_moneytype',  'type' => 'select',   'value' => '0'),
        array('name' => 'k_language',  'type' => 'select',   'value' => '0'),
        array('name' => 'k_paygate', 'type' => 'select', 'value' => '')*/
    );

    return;
}

pc_base::load_app_class('pay_abstract','pay','0');


class Dinpay extends paymentabstract
{
    /**
     * 构造函数
     *
     * @access  public
     * @param
     *
     * @return void
     */
 

    function __construct($config = array())
    {
        if (!empty($config)) $this->set_config($config);       
		$this->config['gateway_url'] = 'https://pay.dinpay.com//gateway?input_charset=UTF-8';
		$this->config['gateway_method'] = 'POST';
		$this->config['notify_url'] = return_url('dinpay',1);
		$this->config['return_url'] = return_url('dinpay');
    }
	
	public function getpreparedata() {
		
		
		$prepare_data['merchant_code'] = $this->config['k_mid'];//商家号
		$prepare_data['service_type'] = 'direct_pay';//服务类型
		

		if (array_key_exists('wap', $this->order_info)){
			$prepare_data['notify_url'] = str_replace("m=pay","m=wap",$this->config['notify_url']);
			$prepare_data['return_url'] = str_replace("m=pay","m=wap",$this->config['return_url']);
		}else{
			$prepare_data['notify_url'] = $this->config['notify_url'];
			$prepare_data['return_url'] = $this->config['return_url'];
		}



		//$prepare_data['notify_url'] = $this->config['notify_url'];
		$prepare_data['interface_version'] = 'V3.0';
		$prepare_data['sign_type'] = 'MD5';
		$prepare_data['order_no'] = $this->order_info['id'];
		$prepare_data['order_time'] = date('Y-m-d H:i:s',$this->order_info['order_time']);
		$prepare_data['order_amount'] = $this->product_info['price'];
		$prepare_data['product_name'] = $this->product_info['name'];
		$prepare_data['bank_code'] = '';
		$prepare_data['client_ip'] = '';
		$prepare_data['input_charset'] = 'UTF-8';
		$prepare_data['extend_param'] = '';
		$prepare_data['extra_return_param'] = '';
		$prepare_data['product_code'] = '';
		$prepare_data['product_desc'] = '';
		$prepare_data['product_num'] = '';
		//$prepare_data['return_url'] = $this->config['return_url'];
		$prepare_data['show_url'] = '';
		
		$data="";
		
		//组织订单信息
		//$data.="bank_code=".$prepare_data['bank_code']."&";
		$data.="input_charset=".$prepare_data['input_charset']."&";
		$data.="interface_version=".$prepare_data['interface_version']."&";
		$data.="merchant_code=".$prepare_data['merchant_code']."&";
		$data.="notify_url=".$prepare_data['notify_url']."&";
		$data.="order_amount=".$prepare_data['order_amount']."&";
		$data.="order_no=".$prepare_data['order_no']."&";
		$data.="order_time=".$prepare_data['order_time']."&";
		$data.="product_name=".$prepare_data['product_name']."&";
		$data.="return_url=".$prepare_data['return_url']."&";
		$data.="service_type=".$prepare_data['service_type']."&";
		$data.="key=".$this->config['k_pass'];
	
		// 数字签名
		$prepare_data['sign'] = md5($data);

		return $prepare_data;
	}
	
	/**
	 * 客户端接收数据
	 * 状态码说明  （0 交易完成 1 交易失败 2 交易超时 3 交易处理中 4 交易未支付）
	 */
    public function receive() {

		//商号号
		$merchant_code	= $_POST["merchant_code"];

		//通知类型
		$notify_type = $_POST["notify_type"];

		//通知校验ID
		$notify_id = $_POST["notify_id"];

		//接口版本
		$interface_version = $_POST["interface_version"];

		//签名方式
		$sign_type = $_POST["sign_type"];

		//签名
		$dinpaySign = $_POST["sign"];

		//商家订单号
		$order_no = $_POST["order_no"];

		//商家订单时间
		$order_time = $_POST["order_time"];

		//商家订单金额
		$order_amount = $_POST["order_amount"];

		//回传参数
		$extra_return_param = $_POST["extra_return_param"];

		//智付交易定单号
		$trade_no = $_POST["trade_no"];

		//智付交易时间
		$trade_time = $_POST["trade_time"];

		//交易状态 SUCCESS 成功  FAILED 失败
		$trade_status = $_POST["trade_status"];

		//银行交易流水号
		$bank_seq_no = $_POST["bank_seq_no"];

		//$bank_code = $_POST["bank_code"];


		/**
		 *签名顺序按照参数名a到z的顺序排序，若遇到相同首字母，则看第二个字母，以此类推，
		*同时将商家支付密钥key放在最后参与签名，组成规则如下：
		*参数名1=参数值1&参数名2=参数值2&……&参数名n=参数值n&key=key值
		**/


		//组织订单信息
		$data = "";
		

		if($bank_seq_no != ""){
			$data = $data."bank_seq_no=".$bank_seq_no."&";
		}
		if($extra_return_param != ""){
			$data = $data."extra_return_param=".$extra_return_param."&";
		}
		$data = $data."interface_version=".$interface_version."&";
		$data = $data."merchant_code=".$merchant_code."&";
		if($notify_id != ""){
			$data = $data."notify_id=".$notify_id."&notify_type=page_notify&";
		}

		$data = $data."order_amount=".$order_amount."&";
		$data = $data."order_no=".$order_no."&";
		$data = $data."order_time=".$order_time."&";
		$data = $data."trade_no=".$trade_no."&";
		$data = $data."trade_status=".$trade_status."&";

		if($trade_time != ""){
			 $data = $data."trade_time=".$trade_time."&";
		}
	
		$data.="key=".$this->config['k_pass'];

		//将组装好的信息MD5签名
		$sign = md5($data);

		if($dinpaySign==$sign) {
			$return_data['order_id'] = $order_no;
			$return_data['order_total'] = $order_amount;
			$return_data['price'] = $order_amount;
			$return_data['order_status'] = 0;
			return $return_data;
		} else {
			error_log(date('m-d H:i:s',SYS_TIME).'| GET: illegality notice : flase |'."\r\n", 3, CACHE_PATH.'pay_error_log.php');			
			showmessage(L('illegal_sign'));
			return false;
		}
		
    }	

    /**
	 * POST接收数据
	 * 状态码说明  （0 交易完成 1 交易失败 2 交易超时 3 交易处理中 4 交易未支付）
	 */
    public function notify() {
    	//商号号
		$merchant_code	= $_POST["merchant_code"];

		//通知类型
		$notify_type = $_POST["notify_type"];

		//通知校验ID
		$notify_id = $_POST["notify_id"];

		//接口版本
		$interface_version = $_POST["interface_version"];

		//签名方式
		$sign_type = $_POST["sign_type"];

		//签名
		$dinpaySign = $_POST["sign"];

		//商家订单号
		$order_no = $_POST["order_no"];

		//商家订单时间
		$order_time = $_POST["order_time"];

		//商家订单金额
		$order_amount = $_POST["order_amount"];

		//回传参数
		$extra_return_param = $_POST["extra_return_param"];

		//智付交易定单号
		$trade_no = $_POST["trade_no"];

		//智付交易时间
		$trade_time = $_POST["trade_time"];

		//交易状态 SUCCESS 成功  FAILED 失败
		$trade_status = $_POST["trade_status"];

		//银行交易流水号
		$bank_seq_no = $_POST["bank_seq_no"];

		

		/**
		 *签名顺序按照参数名a到z的顺序排序，若遇到相同首字母，则看第二个字母，以此类推，
		*同时将商家支付密钥key放在最后参与签名，组成规则如下：
		*参数名1=参数值1&参数名2=参数值2&……&参数名n=参数值n&key=key值
		**/


		//组织订单信息
		$data = "";
		
		if($bank_seq_no != ""){
			$data = $data."bank_seq_no=".$bank_seq_no."&";
		}
		if($extra_return_param != ""){
			$data = $data."extra_return_param=".$extra_return_param."&";
		}
		$data = $data."interface_version=".$interface_version."&";
		$data = $data."merchant_code=".$merchant_code."&";
		if($notify_id != ""){
			$data = $data."notify_id=".$notify_id."&notify_type=offline_notify&";
		}

		$data = $data."order_amount=".$order_amount."&";
		$data = $data."order_no=".$order_no."&";
		$data = $data."order_time=".$order_time."&";
		$data = $data."trade_no=".$trade_no."&";
		$data = $data."trade_status=".$trade_status."&";

		if($trade_time != ""){
			 $data = $data."trade_time=".$trade_time."&";
		}
	
		$data.="key=".$this->config['k_pass'];
		

		//将组装好的信息MD5签名
		$sign = md5($data);

		if($dinpaySign==$sign) {
			$return_data['order_id'] = $order_no;
			$return_data['order_total'] = $order_amount;
			$return_data['price'] = $order_amount;
			$return_data['order_status'] = 0;

			return $return_data;
		} else {
			error_log(date('m-d H:i:s',SYS_TIME).'| GET: illegality notice : flase |'."\r\n", 3, CACHE_PATH.'pay_error_log.php');			
			showmessage(L('illegal_sign'));
			return false;
		}
    }
    	
    /**
     * 相应服务器应答状态
     * @param $result
     */
    public function response($result) {
    	if (FALSE == $result) echo 'bad';
		else echo 'SUCCESS';
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
			if ('sign' == $key || 'sign_type' == $key || '' == $value || 'm' == $key  || 'a' == $key  || 'c' == $key   || 'code' == $key ) continue;
			else $para[$key] = $value;
		}
		return $para;
	}
    

  
}

?>