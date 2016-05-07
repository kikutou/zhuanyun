<?php
defined('IN_PHPCMS') or exit('No permission resources.'); 
class respond {
	
	private $pay_db, $account_db,$member_db,$wdb;
	
	function __construct() {
		pc_base::load_app_func('global');
	}
	
	/**
	 * return_url get形式响应
	 */
	public function respond_get() {
		if ($_GET['code']){
			$payment = $this->get_by_code($_GET['code']);
			if(!$payment) showmessage(L('payment_failed'));
			$cfg = unserialize_config($payment['config']);
			$pay_name = ucwords($payment['pay_code']);
			pc_base::load_app_class('pay_factory','',0);
			$payment_handler = new pay_factory($pay_name, $cfg);
			$return_data = $payment_handler->receive();
			if($return_data) {
				
				if($return_data['order_status'] == 0) {				
					//$this->update_member_amount_by_sn($return_data['order_id']);
					//$this->update_waybill_status_by_sn($return_data['order_id']);//更新已付款
				}
				//$this->update_recode_status_by_sn($return_data['order_id'],$return_data['order_status']);
				
				showmessage(L('pay_success'),APP_PATH.'index.php?m=pay&c=deposit');
			} else {
				showmessage(L('pay_failed'),APP_PATH.'index.php?m=pay&c=deposit');
			}
		} else {
			showmessage(L('pay_success'));
		}
	}

	/**
	 * 服务器端 POST形式响应
	 */
	public function respond_post() {
		$_POST['code'] = $_POST['code'] ? $_POST['code'] : $_GET['code'];
		if ($_POST['code']){
			$payment = $this->get_by_code($_POST['code']);
			if(!$payment) error_log(date('m-d H:i:s',SYS_TIME).'| POST: payment is null |'."\r\n", 3, CACHE_PATH.'pay_error_log.php');;
			$cfg = unserialize_config($payment['config']);
			$pay_name = ucwords($payment['pay_code']);
			pc_base::load_app_class('pay_factory','',0);
			$payment_handler = new pay_factory($pay_name, $cfg);
			$return_data = $payment_handler->notify();
			if($return_data) {
				if($return_data['order_status'] == 0) {
					$this->update_member_amount_by_sn($return_data['order_id']);
					$this->update_waybill_status_by_sn($return_data['order_id']);//更新已付款
				}
				$this->update_recode_status_by_sn($return_data['order_id'],$return_data['order_status']);				
                $result = TRUE;
			} else {
				$result = FALSE;
			}
			$payment_handler->response($result);
		}
	}

	/**
	 * 更新订单状态
	 * @param unknown_type $trade_sn 订单ID
	 * @param unknown_type $status 订单状态
	 */
	private function update_recode_status_by_sn($trade_sn,$status) {
		$trade_sn = trim($trade_sn);
		$status = trim(intval($status));
		$data = array();
		$this->account_db = pc_base::load_model('pay_account_model');
		$status = return_status($status);
		$data = array('status'=>$status);
		return $this->account_db->update($data,array('trade_sn'=>$trade_sn));
	}

	/**
	 * 更新用户账户余额
	 * @param unknown_type $trade_sn
	 */
	private function update_member_amount_by_sn($trade_sn) {
		$data = $userinfo = array();
		$trade_sn = trim($trade_sn);
		$this->member_db = pc_base::load_model('member_model');
		$orderinfo = $this->get_userinfo_by_sn($trade_sn);
		$userinfo = $this->member_db->get_one(array('userid'=>$orderinfo['userid']));
		if($orderinfo){

			$this->account_db = pc_base::load_model('pay_account_model');
			$sqls="trade_sn='".$trade_sn."' and userid='".intval($userinfo['userid'])."' and  status!='succ'";
			$result = $this->account_db->get_one($sqls,"status","addtime desc");
			
			$money = floatval($orderinfo['money']);
			$amount = $userinfo['amount'] + $money;

			if($result){
			
			$data = array('amount'=>$amount);
			$this_account_history = pc_base::load_model('account_history_model');
			
			$handle=array();
			$handle['username'] = $userinfo['username'];
			$handle['userid'] = $userinfo['userid'];
			$handle['addtime'] = SYS_TIME;
			$handle['orderid'] = trim($trade_sn);
			$handle['ordertime'] = $orderinfo['addtime'];
			$handle['useramount'] = $userinfo['amount'];
			$handle['amount'] = $money;
			$handle['nowuseramount'] = $amount;



			$sql="orderid='".$handle['orderid']."' AND ordertime='".strtotime(trim($orderinfo['addtime']))."' ";
			
			if($this_account_history->get_one($sql)){
				error_log(date('m-d H:i:s',SYS_TIME).'|   trade_sn:'.$trade_sn.' is not existed money:'.$money.' amout:'.$amount.'  |'."\r\n", 3, CACHE_PATH.'pay_error_log.php');
				return false;
			}else{
				$this_account_history->insert($handle);

				return $this->member_db->update($data,array('userid'=>$orderinfo['userid']));

			}


			}else{
				error_log(date('m-d H:i:s',SYS_TIME).'|   trade_sn:'.$trade_sn.' is not existed money:'.$money.' amout:'.$amount.'  |'."\r\n", 3, CACHE_PATH.'pay_error_log.php');
				return false;
			}
		} else {
			error_log(date('m-d H:i:s',SYS_TIME).'|  POST: rechange failed! trade_sn:'.$trade_sn.' |'."\r\n", 3, CACHE_PATH.'pay_error_log.php');
			return false;
		}
	}

	//若是付款更新运单状态已付款
	private function update_waybill_status_by_sn($trade_sn){
		$trade_sn = trim($trade_sn);
		$this->wdb = pc_base::load_model('waybill_model');
		$this->member_db = pc_base::load_model('member_model');
		$bill=$this->wdb->get_one(array('sysbillid'=>$trade_sn));
		$orderinfo = $this->get_userinfo_by_sn($trade_sn);
		$_mrow = $this->member_db->get_one(array('userid'=>$orderinfo['userid']));
		
		//扣款记录
		if($bill && $orderinfo){
			$value = floatval($orderinfo['money']);
			$waybillid=trim($bill['waybillid']);
			if(!empty($waybillid)){
				$msg = '已成功付款运单'.$waybillid;
				$spend = pc_base::load_app_class('spend','pay');
				if($bill['status']!=7 && $_mrow){//非已付款状态方可操作
					
						$func = 'amount';
						$spend->$func($value,$msg,$_mrow['userid'],$_mrow['username'],0,$_mrow['username']);		
						$this->wdb->update(array('status'=>7),array('sysbillid'=>$trade_sn));
						//插入处理记录
						$historydb = pc_base::load_model('waybill_history_model');
						$sdb = pc_base::load_model('storage_model');
						$val = $sdb->get_one(array('aid'=>trim($bill['storageid'])));//查当前仓库,入库
						$_storagename = $val['title'];

						$handle=array();
						$handle['siteid'] = 1;
						$handle['username'] = $_mrow['username'];
						$handle['userid'] = $_mrow['userid'];
						$handle['addtime'] = SYS_TIME;
						$handle['sysbillid'] = trim($trade_sn);
						$handle['waybillid'] = trim($bill['waybillid']);
						$handle['placeid'] = trim($bill['storageid']);
						$handle['placename'] = $_storagename;
						$handle['status'] = 7;
						$handle['remark'] = "在线支付--已付款";

						$lid = $historydb->insert($handle);
					
				}//end 非已付款状态方可操作

			}//end waybillid
		}//end 扣款


	}
	
	/**
	 * 通过订单ID抓取用户信息
	 * @param unknown_type $trade_sn
	 */
	private function get_userinfo_by_sn($trade_sn) {
		$trade_sn = trim($trade_sn);
		$this->account_db = pc_base::load_model('pay_account_model');
		$result = $this->account_db->get_one(array('trade_sn'=>$trade_sn));
		$status_arr = array('succ','failed','error','timeout','cancel');
		return ($result && !in_array($result['status'],$status_arr)) ? $result : false;
	}
	
	/**
	 * 通过支付代码获取支付信息
	 * @param unknown_type $code
	 */
	private function get_by_code($code) {
		$result = array();
		$code = trim($code);
		$this->pay_db = pc_base::load_model('pay_payment_model');
		$result = $this->pay_db->get_one(array('pay_code'=>$code));
		return $result;
	}
}
?>