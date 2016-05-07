<?php 
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('foreground','member');
pc_base::load_sys_class('format', '', 0);
pc_base::load_sys_class('form', '', 0);
pc_base::load_app_func('global', 'member');

class index extends foreground{

	private $hid;
	public $current_user;
	function __construct() {
		
		$this->memberinfo=$this->get_current_userinfo();

		$this->db = pc_base::load_model('waybill_model');
		$this->_username = param::get_cookie('_username');
		$this->_userid = intval(param::get_cookie('_userid'));
		$this->siteid =  isset($_REQUEST['siteid']) && trim($_REQUEST['siteid']) ? intval($_REQUEST['siteid']) : 1;
		$this->hid =  isset($_GET['hid']) && trim($_GET['hid']) ? intval($_GET['hid']) : 0;
		$this->current_user = $this->get_current_userinfo();
		if(!isset($this->_userid) || $this->_userid==0 ){
			showmessage(L('please_login', '', 'member'), 'index.php?m=member&c=index&a=login');
			exit;
		}

		$storagedb = pc_base::load_model('storage_model');
		$srows=$storagedb->get_one(array('aid'=>$this->hid),'title');
		if($srows){
			$this->storagename=trim($srows['title']);
		}
		
		$status1count=$this->getwaybillcount(0,1);
		$status3count=$this->getpackagecount(0,3);
		$status21count=$this->getwaybillcount(0,21);
		$status8count=$this->getpackagecount(0,8);
		$status7count=$this->getpackagecount(0,7);
		$status14count=$this->getpackagecount(0,14);
		$status16count=$this->getpackagecount(0,16);

	}

	//我的包裹数
	private function getpackagecount($storageid=0,$status=0){
		$wdb = pc_base::load_model('waybill_model');
		$packcount=0;

		$sql="userid='".$this->current_user['userid']."'";
		if($status>0)
		{
			$sql.=" and status='$status'";
		}

		if($storageid>0){
			$sql.=" and storageid='$storageid'";
			$packcount=$wdb->count($sql);
		}else{
			$packcount=$wdb->count($sql);
		}
		return $packcount;
	}
	//我的运单数
	private function getwaybillcount($storageid=0,$status=0){
		$wdb = pc_base::load_model('waybill_model');
		$waycount=0;
		$sql="userid='".$this->current_user['userid']."'";
		if($status>0)
		{
			$sql.=" and status='$status'";
		}
		if($storageid>0){
			$sql.=" and storageid='$storageid'";
			$waycount=$wdb->count($sql);
		}else{
			$waycount=$wdb->count($sql);
		}
		return $waycount;
	}

	public function get_waybill_spend($waybillid=''){
		$spend_db = pc_base::load_model('pay_spend_model');
		$row= $spend_db->get_one(array('msg'=>trim($waybillid),'username'=>$this->current_user['username']));
		return format::date($row['creat_at'],1);
	}
	
	public function init() {
		
		$sql = " status!=99 AND  status!=19 AND status!=20 AND returnedstatus=0 ";

		$status = intval($_REQUEST['status']);

		if(isset($_POST['dosubmit'])) {
			
			$orderno = safe_one_string($_POST['orderno']);
			$starttime = strtotime($_POST['starttime']);
			$endtime = strtotime($_POST['endtime']);
			
			if($status>0){
				$sql.=" and status='$status' ";
			}
			if(!empty($orderno)){
				$sql.=" and (sysbillid like '%$orderno%' or waybillid like '%$orderno%') ";
			}

			if(!empty($starttime) && !empty($endtime)){
				$sql.=" and addtime>='$starttime'  and addtime<='$endtime' ";
			}
		}

		if($status>0){
			$sql.=" and status='$status' ";
		}

		$sql .= " and siteid='$this->siteid' and userid='$this->_userid' ";
		
		
		$page = max(intval($_GET['page']), 1);
		$datas = $this->db->listinfo($sql, '`aid` DESC', $page);
		
		$pages= $this->db->pages;

		include template('waybill', 'waybill_list');
	}
	
	//运单扣款处理
	public function pay(){
				$isSUCC=false;
				
				$udb = pc_base::load_model('member_model');
				$sdb = pc_base::load_model('storage_model');
				$historydb = pc_base::load_model('waybill_history_model');
				
				$outresult="";
			
					$aid =isset($_GET['oid']) ? intval($_GET['oid']) : 0;

					$where="aid='".trim($aid)."'  and status!=7 and userid='$this->_userid'";
					$info = $this->db->get_one($where);	

					$this->hid = $info['storageid'];
					if($info){
						$waybillid = $info['waybillid'];

						$udb->set_model();
						$urow=$udb->get_one(array('userid' => intval($info['userid'])));
						$useramount = floatval($urow['amount']);
						$value = floatval($info['payedfee']);

						if($useramount>=$value){
						

						//插入处理记录
						$handle=array();

						$handle['siteid'] = $this->siteid;
						$handle['username'] = $this->_username;
						$handle['userid'] = $this->_userid;
						$handle['addtime'] = SYS_TIME;
						$handle['sysbillid'] = trim($info['sysbillid']);
						$handle['waybillid'] = trim($info['waybillid']);
						$handle['placeid'] = $info['storageid'];
						$handle['placename'] = $info['storagename'];
						$handle['status'] = 7;
						$handle['remark'] = $this->getwaybilltatus($handle['status'],$handle['placeid']).$_POST['data']['remark'];


						$_spend_db = pc_base::load_model('pay_spend_model');
						$spend_row = $_spend_db->get_one("msg='".trim($handle['waybillid'])."' AND value='".trim($value)."'",'id');
						$pay_status =0;
						if($spend_row){$pay_status = 1;}
									
						
						if($pay_status==0){
						//修改账户流水线，财务变动

						
						$payment = L('admin_deduction');//后台扣款
						$spend = pc_base::load_app_class('spend','pay');
						$func = 'amount';
						$res  = $spend->$func($value,$handle['waybillid'],$urow['userid'],$urow['username'],$this->_userid,$this->_username);
						
							
							$this->db->update(array('status'=>7,'paidtime'=>SYS_TIME),array('sysbillid'=>trim($info['sysbillid'])));
							
							$lid = $historydb->insert($handle);

							//发邮件
							$this->sendemailwaybill($handle['sysbillid'],$handle['remark'],intval($_POST['send_email_nofity']));
							$isSUCC=true;
							$outresult.="<p>".$waybillid."  <font color=green>扣款成功 ".$value." </font></p>";
						}else{
							
							//发邮件
							$this->sendemailwaybill($handle['sysbillid'],'待付款'.$handle['remark'],intval($_POST['send_email_nofity']));

							$outresult.="<p>".$waybillid." 余额不足 <font color=red>扣款失败</font></p>";
							}
						}
						else{
							$outresult.="<p>".$waybillid." 余额不足 <font color=red>扣款失败</font></p>";
						}
					
					}else{
						$outresult.="<p>".$waybillid." 未处理 <font color=red>扣款失败</font></p>";
					}

					
				
// /index.php?m=package&c=index&a=nopaypackage&hid=
				if($isSUCC){
					showmessage($outresult,"/index.php?m=waybill&c=index&a=init&hid=0&status=7");
				}else{
					showmessage($outresult,"/index.php?m=package&c=index&a=nopaypackage&hid=".$this->hid);
				}					
					exit;
		

		
	}
	
	public function waybill_batch_pay_pre(){
		if(isset($_GET['aidd']))
		{
			$bill_array = array(intval($_GET['aidd']));
		}else{
			$bill_array = isset($_POST['aid']) ? $_POST['aid'] : showmessage("非法操作");
		}
		$datas = $this->db->select("aid in(".implode(',',$bill_array).") ","*",1000);

		include template('waybill', 'waybill_batch_pay_pre');

	}


	public function waybill_batch_pay(){
		if(isset($_POST['dosubmit'])) {
			
				$bill_array = isset($_POST['aid']) ? $_POST['aid'] : array();
			
				$isSUCC=false;
				
				$udb = pc_base::load_model('member_model');
				$sdb = pc_base::load_model('storage_model');
				$historydb = pc_base::load_model('waybill_history_model');
				
				$outresult="";
				foreach($bill_array as $aid){

					$where="aid='".trim($aid)."'  and status!=7";
					$info = $this->db->get_one($where);	
					if($info){
						$waybillid = $info['waybillid'];

						$udb->set_model();
						$urow=$udb->get_one(array('userid' => intval($info['userid'])));
						$useramount = floatval($urow['amount']);
						$value = floatval($info['payedfee']);

						if($useramount>=$value){
						

						//插入处理记录
						$handle=array();

						$handle['siteid'] = $this->siteid;
						$handle['username'] = $this->_username;
						$handle['userid'] = $this->_userid;
						$handle['addtime'] = SYS_TIME;
						$handle['sysbillid'] = trim($info['sysbillid']);
						$handle['waybillid'] = trim($info['waybillid']);
						$handle['placeid'] = $info['storageid'];
						$handle['placename'] = $info['storagename'];
						$handle['status'] = 7;
						$handle['remark'] = $this->getwaybilltatus($handle['status'],$handle['placeid']).$_POST['data']['remark'];


						$_spend_db = pc_base::load_model('pay_spend_model');
						$spend_row = $_spend_db->get_one("msg='".trim($handle['waybillid'])."' AND value='".trim($value)."'",'id');
						$pay_status =0;
						if($spend_row){$pay_status = 1;}
									
						
						if($pay_status==0){
						//修改账户流水线，财务变动

						
						$payment = L('admin_deduction');//后台扣款
						$spend = pc_base::load_app_class('spend','pay');
						$func = 'amount';
						$res  = $spend->$func($value,$handle['waybillid'],$urow['userid'],$urow['username'],$this->_userid,$this->_username);
						
							
							$this->db->update(array('status'=>7,'paidtime'=>SYS_TIME),array('sysbillid'=>trim($info['sysbillid'])));
							
							$lid = $historydb->insert($handle);

							//发邮件
							$this->sendemailwaybill($handle['sysbillid'],$handle['remark'],intval($_POST['send_email_nofity']));
							$isSUCC=true;
							$outresult.="<p>".$waybillid."  <font color=green>扣款成功</font></p>";
						}else{
							
							//发邮件
							$this->sendemailwaybill($handle['sysbillid'],'待付款'.$handle['remark'],intval($_POST['send_email_nofity']));

							$outresult.="<p>".$waybillid." 余额不足 <font color=red>扣款失败</font></p>";
						}
						}else{
							$outresult.="<p>".$waybillid." 余额不足 <font color=red>扣款失败</font></p>";
						}
					
					}else{
						$outresult.="<p>".$waybillid." 未处理 <font color=red>扣款失败</font></p>";
					}

					
				}
				//index.php?m=package&c=index&a=nopaypackage&hid=
				if($isSUCC){
					showmessage($outresult,"/index.php?m=waybill&c=index&a=init&hid=".$this->hid);
				}else{
					showmessage($outresult,"/index.php?m=package&c=index&a=nopaypackage&hid=".$this->hid);
				}
					exit;

		}
	}

	public function waybill_uploadsfz(){

		if(isset($_POST['dosubmit'])) {
			$waybillid = trim($_POST['waybillid']);

			$this->db->update($_POST['waybill'],array('waybillid'=>$waybillid));

			showmessage(L('operation_success'));
		}


		include template('waybill', 'waybill_uploadsfz');
	}

	public function waybill_returned_list() {
		
		$sql = " status!=99 AND returnedstatus!=0 ";

		$status = intval($_REQUEST['status']);

		if(isset($_POST['dosubmit'])) {
			
			$orderno = safe_one_string($_POST['orderno']);
			$starttime = strtotime($_POST['starttime']);
			$endtime = strtotime($_POST['endtime']);

			if($status>0){
				$sql.=" and status='$status' ";
			}
			if(!empty($orderno)){
				$sql.=" and (sysbillid like '%$orderno%' or waybillid like '%$orderno%') ";
			}

			if(!empty($starttime) && !empty($endtime)){
				$sql.=" and addtime>='$starttime'  and addtime<='$endtime' ";
			}
		}

		if($status>0){
			$sql.=" and status='$status' ";
		}

		$sql .= " and siteid='$this->siteid' and userid='$this->_userid'  ";
		
		
		$page = max(intval($_GET['page']), 1);
		$datas = $this->db->listinfo($sql, '`aid` DESC', $page);
		
		$pages= $this->db->pages;

		include template('waybill', 'waybill_returned_list');
	}


	public function expno_exists(){
		$data = isset($_POST['data']) ? trim($_POST['data']) : '';
		$result="";
		if($data){
			$res = explode("_",$data);
			$pdb = pc_base::load_model('package_model');
			foreach($res as $v){
				$packageno = trim($v);
				$row = $pdb->get_one("expressno='$packageno' AND returncode='' AND status IN(1,2) AND userid='$this->_userid'",'expressno');
				if(!$row){
					$result.="您的包裹仓库里面 邮单号 $packageno 不存在 或 已处理!\n ";
				}
			}
		}
		echo $result;exit;
	}
	
	public function savequestion(){
		

			
			$content_db = pc_base::load_model('content_model');
			$content_db->set_model(3);

			$qstype = isset($_POST[info]['title']) ? trim($_POST[info]['title']) : '';
			$qsremark = isset($_POST[info]['content']) ? trim($_POST[info]['content']) : '';

			if($qstype && $qsremark){
				$datas =array();
				$datas['catid']=1;
				$datas['status']=99;
				$datas['typeid']=trim($_POST['typeid']);
				$datas['waybillid']=trim($_POST[info]['waybillid']);
				$datas['title']=$qstype;
				$datas['storageid']=$this->hid;
				$datas['description']=$qsremark;
				
				if($content_db->add_content($datas)){
					showmessage('您已成功提问!', HTTP_REFERER);
				}
			}

/*
			if(isset($_POST['dosubmit'])) {
			$memberinfo = $this->memberinfo;
			$grouplist = getcache('grouplist','member');
			$priv_db = pc_base::load_model('category_priv_model'); //加载栏目权限表数据模型
			//$siteid=1;
			$catid = intval($_POST['info']['catid']);
			//判断此类型用户是否有权限在此栏目下提交投稿
			if (!$priv_db->get_one(array('catid'=>$catid, 'roleid'=>$memberinfo['groupid'], 'is_admin'=>0, 'action'=>'add'))) showmessage(L('category').L('publish_deny'), APP_PATH.'index.php?m=member'); 
			
			
			$siteid = 1;
			$CATEGORYS = getcache('category_content_'.$siteid, 'commons');
			$category = $CATEGORYS[$catid];
			$modelid = $category['modelid'];
			if(!$modelid) showmessage(L('illegal_parameters'), HTTP_REFERER);
			$this->content_db = pc_base::load_model('content_model');
			$this->content_db->set_model($modelid);

			$_row = $this->content_db->get_one(array('waybillid'=>trim($_POST['info']['waybillid'])));
			if($_row){
				//showmessage("此订单已晒过单,不能重复晒单!", HTTP_REFERER);
				//exit;
			}

			$table_name = $this->content_db->table_name;
			$fields_sys = $this->content_db->get_fields();
			$this->content_db->table_name = $table_name.'_data';
			
			$fields_attr = $this->content_db->get_fields();
			$fields = array_merge($fields_sys,$fields_attr);
			$fields = array_keys($fields);
			$info = array();
			foreach($_POST['info'] as $_k=>$_v) {
				if(in_array($_k, $fields)) $info[$_k] = trim_script($_v);
			}
			
			$post_fields = array_keys($_POST['info']);
			$post_fields = array_intersect_assoc($fields,$post_fields);
			$setting = string2array($category['setting']);
			if($setting['presentpoint'] < 0 && $memberinfo['point'] < abs($setting['presentpoint']))
			showmessage(L('points_less_than',array('point'=>$memberinfo['point'],'need_point'=>abs($setting['presentpoint']))),APP_PATH.'index.php?m=pay&c=deposit&a=pay&exchange=point',3000);
			
			//判断会员组投稿是否需要审核
			if($grouplist[$memberinfo['groupid']]['allowpostverify'] || !$setting['workflowid']) {
				$info['status'] = 99;
			} else {
				$info['status'] = 1;
			}


			$info['username'] = $memberinfo['username'];
			if(isset($info['title'])) $info['title'] = safe_replace($info['title']);
			$this->content_db->siteid = $siteid;
			
			$id = $this->content_db->add_content($info);
			//检查投稿奖励或扣除积分
			if ($info['status']==99) {
				$flag = $catid.'_'.$id;
				if($setting['presentpoint']>0) {
					pc_base::load_app_class('receipts','pay',0);
					receipts::point($setting['presentpoint'],$memberinfo['userid'], $memberinfo['username'], $flag,'selfincome',L('contribute_add_point'),$memberinfo['username']);
				} else {
					pc_base::load_app_class('spend','pay',0);
					spend::point($setting['presentpoint'], L('contribute_del_point'), $memberinfo['userid'], $memberinfo['username'], '', '', $flag);
				}
			}
			//缓存结果
			$model_cache = getcache('model','commons');
			$infos = array();
			foreach ($model_cache as $modelid=>$model) {
				if($model['siteid']==$siteid) {
					$datas = array();
					$this->content_db->set_model($modelid);
					$datas = $this->content_db->select(array('username'=>$memberinfo['username'],'sysadd'=>0),'id,catid,title,url,username,sysadd,inputtime,status',100,'id DESC');
					if($datas) $infos = array_merge($infos,$datas);
				}
			}
			setcache('member_'.$memberinfo['userid'].'_'.$siteid, $infos,'content');
			//缓存结果 END
			if($info['status']==99) {
				showmessage("操作成功", APP_PATH.'index.php?m=waybill&c=index&a=init');
			} else {
				showmessage("操作成功,等待审核", APP_PATH.'index.php?m=waybill&c=index&a=init');
			}
			
		} */
	
	}
	
	private function sendemailwaybill($sysbillid,$statusinfo,$send_email_nofity=1){
			
			$sysres = $this->db->get_one(array('sysbillid'=>$sysbillid));
			if($sysres && $send_email_nofity==1){

			$moduledb = pc_base::load_model('module_model');
			$__mdb = pc_base::load_model('member_model');
			$userinfo=$__mdb->get_one(array('userid'=>$sysres['userid']));

			//$status_array=array(1=>'未入库',2=>'入库中',3=>'已入库',4=>'已处理',5=>'处理异常',6=>'已取消',7=>'已付款',8=>'待付款',9=>'已出库',10=>'已扫描',11=>'中转成功',12=>'已入xx仓库',13=>'等待自取',14=>'快递跟踪',15=>'等待赔偿',16=>'已签收',17=>'待发货');

			//发邮件
			pc_base::load_sys_func('mail');
			$member_setting = $moduledb->get_one(array('module'=>'member'), 'setting');
			$member_setting = string2array($member_setting['setting']);
			$url = APP_PATH."index.php?m=waybill&c=index&a=init&hid=".$sysres['storageid'];
			$message = $member_setting['expressnoemail'];
			$message = str_replace(array('{click}','{url}','{no}','{status}'), array('<a href="'.$url.'">'.L('please_click').'</a>',$url,$sysres['waybillid'],$statusinfo), $message);
			sendmail($userinfo['email'], '你的运单号'.$sysres['waybillid'].$statusinfo, $message);
			}
	}
	//签收
	public function waybill_finish(){
		
				$bid = isset($_GET['bid']) ? intval($_GET['bid']) : 0;
				$_a ="init";
				if($_GET['retruned']){$_a ="waybill_returned_list";}
				$sdb = pc_base::load_model('storage_model');
				$historydb = pc_base::load_model('waybill_history_model');
				
				$way=$this->db->get_one(array('aid'=>$bid));
				if($way['status']!=16){
					if($this->db->update(array('status'=>16),array('aid'=>$bid))){
						
						$takecity="";
						$addr = explode('|',$waybill['takeaddressname']);
						$takecity=$addr[4];

						//插入处理人备注
					
						$val=$sdb->get_one(array('aid'=>$way['storageid']));
						//插入处理记录
						$handle=array();
						$handle['siteid'] = $this->siteid;
						$handle['username'] = $this->_username;
						$handle['userid'] = $this->_userid;
						$handle['addtime'] = SYS_TIME;
						$handle['sysbillid'] = trim($way['sysbillid']);
						$handle['waybillid'] = trim($way['waybillid']);
						$handle['placeid'] = $way['storageid'];
						$handle['placename'] = $val['title'];
						$handle['status'] = 16;
						if($_GET['retruned']){
							$handle['remark'] = "退货已完成";
						}else{
							$handle['remark'] = $takecity;
						}
						$lid = $historydb->insert($handle);

						//发邮件
						$this->sendemailwaybill($handle['sysbillid'],$handle['remark'],1);

					showmessage(L('operation_success'),'/index.php?m=waybill&c=index&a='.$_a.'&hid='.$way['storageid']);
					exit;
				}

			}else{
				showmessage(L('operation_failure'),'/index.php?m=waybill&c=index&a='.$_a.'&hid='.$way['storageid']);
					exit;
			}
		
	}

	//Cancel order bill
	public function waybill_cancel(){
		
				$bid = isset($_GET['bid']) ? intval($_GET['bid']) : 0;

				$sdb = pc_base::load_model('storage_model');
				$historydb = pc_base::load_model('waybill_history_model');
				$packdb = pc_base::load_model('package_model');
				$wgdb = pc_base::load_model('waybill_goods_model');

				
				$way=$this->db->get_one(array('aid'=>$bid));
				if($way['status']!=6){
					$status=6;
					$sysbillid_cancel=$billprefix.date('ymdHis',time()).floor(microtime()*1000);
					$returncode = $way['returncode'];
					if($this->db->update(array('status'=>$status,'returncode'=>$sysbillid_cancel),array('aid'=>$bid))){
					
						$where3="waybillid='$way[waybillid]' AND returncode='$returncode'";
							
						$allg = $wgdb->select($where3,"*",1000,"");
	
						$wgdb->update(array('returncode'=>$sysbillid_cancel,'returntime'=>SYS_TIME,'returnname'=>$this->_username),$where3);

						foreach($allg as $p){
						$packdb->update(array('issystem'=>0,'status'=>3,'returncode'=>$sysbillid_cancel),array('aid'=>$p['packageid'],'returncode'=>$returncode));
						}
						

						//插入处理人备注
					
						$val=$sdb->get_one(array('aid'=>$way['storageid']));
						//插入处理记录
						$handle=array();
						$handle['siteid'] = $this->siteid;
						$handle['username'] = $this->_username;
						$handle['userid'] = $this->_userid;
						$handle['addtime'] = SYS_TIME;
						$handle['sysbillid'] = trim($way['sysbillid']);
						$handle['waybillid'] = trim($way['waybillid']);
						$handle['placeid'] = $way['storageid'];
						$handle['placename'] = $val['title'];
						$handle['status'] = $status;
						$handle['remark'] = $this->getonestatus($handle['status']);

						$lid = $historydb->insert($handle);

						//发邮件
						$this->sendemailwaybill($handle['sysbillid'],$handle['remark'],1);

					showmessage(L('operation_success'),'/index.php?m=waybill&c=index&a=init&hid='.$way['storageid']);
					exit;
				}

			}else{
				showmessage(L('operation_failure'),'/index.php?m=waybill&c=index&a=init&hid='.$way['storageid']);
					exit;
			}
		
	}
	
	//申请退货 
	public function waybill_returned_request(){
		
				$bid = isset($_GET['bid']) ? intval($_GET['bid']) : 0;

				$sdb = pc_base::load_model('storage_model');
				$historydb = pc_base::load_model('waybill_history_model');
				$wgdb = pc_base::load_model('waybill_goods_model');

				$pdb = pc_base::load_model('package_model');
				
				$curruser=$this->get_current_userinfo();

				$info=$this->db->get_one(array('aid'=>$bid));
					
				$takeperson="";
				$takecity="";
				$mobile="";
				$addr=explode("|",$info['takeaddressname']);
				
				$takeperson=trim($addr[0]);
				$takecity=trim($addr[4]);
				$mobile=trim($addr[1]);

				$takeaddressname = $addr[2].' '.$addr[3].' '.$addr[4].' '.$addr[5].' '.$addr[6].' ';

				$allwaybill_goods = $this->getwaybill_goods($info['waybillid'],$info['returncode']);

				if(isset($_POST['dosubmit'])) {

					//-----------------------------------------------------------------------------------
					$allcount = count($_POST['waybill_goods']);
			$waybill_goods_count = intval($_POST['waybill_goods_count']);
			$num=1;
			
			$sysbillid=$billprefix.date('ymdHis',time()).floor(microtime()*1000);

			$waybillid = trim($_POST['waybillid']);
			$status = 15;
			$address = trim($_POST['address']);
			$contactname = trim($_POST['contactname']);
			$mobile = trim($_POST['mobile']);

			
			unset($info['aid']);

			
			
			
			
			$pack_array=array();
			

			foreach($_POST['waybill_goods_packageid'] as $goods){
				$k = $goods;

				$waybill_array = array();
				$waybill_array = $info;
				$pack_array[$k]=$k;

				$returncode = $sysbillid.$num;

				$_POST['waybill_goods'][$k]['returntime'] = SYS_TIME;
				$_POST['waybill_goods'][$k]['returnname'] = $this->username;
				$_POST['waybill_goods'][$k]['returncode'] = $returncode;
				$packageid = $_POST['waybill_goods'][$k]['packageid'];

				$where1 = "packageid='$packageid' AND returncode=''";
				$where2 = "aid='$packageid' AND returncode=''";
				$where3 = "waybillid='$waybillid' AND returncode=''";
				
				$waybill_array['returncode'] = $returncode;
				$waybill_array['status'] = $status;
				$waybill_array['returnedstatus'] = 2;
				
				$waybill_array['goodsname'] = trim($_POST['waybill_goods'][$k]['goodsname']);
				
				$_POST['waybill_goods'][$k]['return_address'] = $address;
				$_POST['waybill_goods'][$k]['return_person'] = $contactname;
				$_POST['waybill_goods'][$k]['return_mobile'] = $mobile;
				

				unset($_POST['waybill_goods'][$k]['id']);

				$wgdb->update($_POST['waybill_goods'][$k],$where1);
				$pdb->update(array('returncode'=>$returncode,'status'=>$status),$where2);

				if($num==$waybill_goods_count){//the last one
					
					$totalamount = $_POST['waybill_goods'][$k]['amount'];
					$totalprice = $_POST['waybill_goods'][$k]['price'];
					$totalweight = $_POST['waybill_goods'][$k]['weight'];
					$othername = $waybill_array['goodsname'];

					$this->db->update(array('goodsname'=>$othername,'totalamount'=>$totalamount,'totalprice'=>$totalprice,'totalweight'=>$totalweight,'returncode'=>$returncode,'status'=>$status,'returnedstatus'=>$waybill_array['returnedstatus']),$where3);
				
				}else{
					$waybill_array['addtime'] = SYS_TIME;
					
					$waybill_array['totalamount'] = $_POST['waybill_goods'][$k]['amount'];
					$waybill_array['totalprice'] = $_POST['waybill_goods'][$k]['price'];
					$waybill_array['totalweight'] = $_POST['waybill_goods'][$k]['weight'];
					$waybill_array['addvalues'] = array2string(string2array($info['addvalues'])); 

					$wbillid = $this->db->insert($waybill_array,true);	
					$lastinsertid = $this->db->insert_id();

					$this->db->update(array('waybillid'=>$waybillid."-".$lastinsertid),array('aid'=>$lastinsertid));
					$wgdb->update(array('waybillid'=>$waybillid."-".$lastinsertid),array('returncode'=>$returncode));
				}

				$num++;
			}
			
			//update package number
			$totalamount=0;
			$totalprice=0;
			$totalweight=0;
	
			
			
			$all_waybill_goods =$_POST['waybill_goods'];
			$othername = "";	
			$flagg=false;
			foreach($all_waybill_goods as $k=>$goods){
				if(!array_key_exists($k,$pack_array)){
				$totalamount += $goods['amount'];
				$totalprice += $goods['price'];
				$totalweight += $goods['weight'];
				
				if($othername){
					$othername = $waybill_array['goodsname'];
				}else{
					$othername .="_". $waybill_array['goodsname'];
				}
				$flagg=true;

				}
			}

			if($flagg){
				$this->db->update(array('goodsname'=>$othername,'totalamount'=>$totalamount,'totalprice'=>$totalprice,'totalweight'=>$totalweight),array('waybillid'=>$waybillid));
			}
					//-----------------------------------------------------------------------------------
					
				
						//插入处理记录
						$handle=array();
						$handle['siteid'] = $this->siteid;
						$handle['username'] = $this->_username;
						$handle['userid'] = $this->_userid;
						$handle['addtime'] = SYS_TIME;
						$handle['sysbillid'] = trim($info['sysbillid']);
						$handle['waybillid'] = trim($info['waybillid']);
						$handle['placeid'] = $info['storageid'];
						$handle['placename'] = $info['storagename'];
						$handle['status'] = $m_status;
						$handle['remark'] = $this->getonestatus($handle['status']);

						//$lid = $historydb->insert($handle);

							//发邮件
						$this->sendemailwaybill($handle['sysbillid'],$handle['remark'],1);
					
					showmessage(L('operation_success'),'/index.php?m=waybill&c=index&a=waybill_returned_list&hid='.$info['storageid']);
						exit;
				}


		include template('waybill', 'waybill_returned_request');	
		
	}

	//确认已退 
	public function waybill_returned(){
		
				$bid = isset($_GET['bid']) ? intval($_GET['bid']) : 0;

				$sdb = pc_base::load_model('storage_model');
				$historydb = pc_base::load_model('waybill_history_model');
				$wgdb = pc_base::load_model('waybill_goods_model');

				$pdb = pc_base::load_model('package_model');
				
				$curruser=$this->get_current_userinfo();

				$info=$this->db->get_one(array('aid'=>$bid));
					
				$takeperson="";
				$takecity="";
				$mobile="";
				$addr=explode("|",$info['takeaddressname']);

				$s_addr=explode("|",$info['sendaddressname']);
				
				$sendcity=trim($s_addr[3]);

				$takeperson=$addr[0];
				$takecity=trim($addr[4]);
				$takecitys=trim($addr[3]);
				$mobile=trim($addr[1]);

				$takeaddressname = $addr[2].' '.$addr[3].' '.$addr[4].' '.$addr[5].' '.$addr[6].' ';

				$allwaybill_goods = $this->getwaybill_goods($info['waybillid'],$info['returncode']);

				if(isset($_POST['dosubmit'])) {

					$amount = $curruser['amount'];
					$fee = $allwaybill_goods[0]['returnfee'];
					$packageid = $allwaybill_goods[0]['packageid'];
					
					if($fee>$amount){
						showmessage("抱歉无法完成付款 此账户余额不足!",'/index.php?m=waybill&c=index&a='.$_GET['a'].'&bid='.$bid.'&hid='.$this->hid);
						exit;
					}
					
					//修改账户流水线，财务变动

					$value = floatval($fee);
					$msg = "退货".$info['waybillid'];
					$spend = pc_base::load_app_class('spend','pay');
					$func = 'amount';
					$res  = $spend->$func($value,$msg,$info['userid'],$info['username'],0,$curruser['username']);
							
					$_status = $info['status'];	
							
					if($res){

						
						$address = htmlspecialchars(addslashes(trim($_POST['address'])));
						$contactname = htmlspecialchars(addslashes(trim($_POST['contactname'])));
						$mobile = htmlspecialchars(addslashes(trim($_POST['mobile'])));

						$returnedcity = htmlspecialchars(addslashes(trim($_POST['returnedcity'])));

						if($this->db->update(array('returnedstatus'=>3,'status'=>$_status),array('aid'=>$bid))){
							
							
							$pdb->update(array('status'=>$_status),array('aid'=>$packageid,'returncode'=>$info['returncode']));

							$wgdb->update(array('return_address'=>$address,'return_person'=>$contactname,'return_mobile'=>$mobile,'return_time'=>SYS_TIME),array('waybillid'=>trim($info['waybillid']),'returncode'=>$info['returncode']));


							//插入处理人备注
						
							//$val=$sdb->get_one(array('aid'=>$info['storageid']));
							//插入处理记录
							$handle=array();
							$handle['siteid'] = $this->siteid;
							$handle['username'] = $this->_username;
							$handle['userid'] = $this->_userid;
							$handle['addtime'] = SYS_TIME;
							$handle['sysbillid'] = trim($info['sysbillid']);
							$handle['waybillid'] = trim($info['waybillid']);
							$handle['placeid'] = $info['storageid'];
							$handle['placename'] = $returnedcity;
							$handle['status'] = $_status;
							$handle['remark'] = $this->getonestatus($handle['status']);

							$lid = $historydb->insert($handle);

							//发邮件
							$this->sendemailwaybill($handle['sysbillid'],$handle['remark'],1);

							showmessage(L('operation_success'),'/index.php?m=waybill&c=index&a=waybill_returned_list&hid='.$info['storageid']);
							exit;
						}

					}else{
						showmessage(L('operation_failure'),'/index.php?m=waybill&c=index&a='.$_GET['a'].'&bid='.$bid.'&hid='.$info['storageid']);
							exit;
					}

				}


		include template('waybill', 'waybill_returned');	
		
	}

	//返回转运方式
	public function getshippingmethod(){
		
		$idd = isset($_GET['idd']) ? intval($_GET['idd']) : 0;
		$sendid = isset($_GET['sendid']) ? intval($_GET['sendid']) : 0;
		$datas = array();
		$sendlists =  $this->getturnway();
		
		foreach($sendlists as $k=>$v){
			if(($v['destination']==$idd && $v['origin']==$sendid) || ($v['destination']==$sendid && $v['origin']==$idd))
				$datas[]=$v;
		}
		print_r(json_encode($datas));
		exit;
	}

	//在线提问
	public function waybill_onlinequestion(){


		pc_base::load_app_class('client', 'member', 0);
		define('APPID', pc_base::load_config('system', 'phpsso_appid'));
		$phpsso_api_url = pc_base::load_config('system', 'phpsso_api_url');
		$phpsso_auth_key = pc_base::load_config('system', 'phpsso_auth_key');
		$this_client = new client($phpsso_api_url, $phpsso_auth_key);
		
		//获取头像数组
		$avatar = $this_client->ps_getavatar($this->memberinfo['phpssouid']);


		$bid = intval($_GET['bid']);
		$info = $this->db->get_one("siteid='".$this->siteid."' and aid='$bid'");
		$waybillid = trim($info['waybillid']);
		$sysbillid = trim($info['sysbillid']);
		$typeid = trim($info['typeid']);


		

		$catid=1;
		$CATEGORYS = getcache('category_content_1', 'commons'); 
		$category = $CATEGORYS[$catid];
		$modelid = $category['modelid'];
			$model_arr = getcache('model', 'commons');
			$MODEL = $model_arr[$modelid];
			unset($model_arr);
	
			require CACHE_MODEL_PATH.'content_form.class.php';
			$content_form = new content_form($modelid, $catid, $CATEGORYS);
			$forminfos_data = $content_form->get();
			$forminfos = array();
 			foreach($forminfos_data as $_fk=>$_fv) {
				if($_fv['isomnipotent']) continue;
				if($_fv['formtype']=='omnipotent') {
					foreach($forminfos_data as $_fm=>$_fm_value) {
						if($_fm_value['isomnipotent']) {
							$_fv['form'] = str_replace('{'.$_fm.'}',$_fm_value['form'],$_fv['form']);
						}
					}
				}
				$forminfos[$_fk] = $_fv;
			}
			$formValidator = $content_form->formValidator;
		
		$contentdb = pc_base::load_model('content_model');
		$contentdb->set_model(3);
		
		$qsql="waybillid='$waybillid'";
		$question_datas_temp = $contentdb->select($qsql,'*',10000,'listorder ASC');
		$contentdb->table_name=$contentdb->table_name."_data";
		$question_datas=array();
		foreach($question_datas_temp as $v){

			$_row = $contentdb->get_one(array('id'=>$v['id']),'content');
			$v['content'] = $_row['content'];
			$question_datas[]=$v;
		}
		


		$handledb = pc_base::load_model('waybill_history_model');

		$udb = pc_base::load_model('member_model');
		$udb->set_model();
		$urow = $udb->get_one(" userid='$this->_userid'");
		$mobile = $urow['mobile'];
		
		$s_row = $this->getshippingtypename($info['storageid']);
		if($s_row){
			$storagename=$s_row['title'];
		}

		$cdb = pc_base::load_model('enum_model');
		$sql = '`siteid`=\''.$this->siteid.'\' AND groupid=62 ';
		$datas = $cdb->select($sql,'*',10000,'listorder ASC');


		include template('waybill', 'waybill_onlinequestion');	
	}

	//运单列表
	public function waybill_detail(){
		$bid = intval($_GET['bid']);
		$info = $this->db->get_one("siteid='".$this->siteid."' and aid='$bid' and userid='$this->_userid' ");
		
		if(!$info){exit;}

		$waybillid = trim($info['waybillid']);
		$sysbillid = trim($info['sysbillid']);

		$handledb = pc_base::load_model('waybill_history_model');

		$udb = pc_base::load_model('member_model');
		$udb->set_model();
		$urow = $udb->get_one(" userid='$this->_userid'");
		$mobile = $urow['mobile'];
		
		$s_row = $this->getshippingtypename($info['storageid']);
		if($s_row){
			$storagename=$s_row['title'];
		}

		$factweight=$info['factweight'];//实际重量
		$volumeweight=$info['volumeweight'];//实际重量
		$totalship = 0;//总运费
	    
		$servicelist = $this->getservicelist($waybillid);

			
		$handledata = $handledb->select("sysbillid='$sysbillid' ","*",1000,"addtime asc");

		$cdb = pc_base::load_model('enum_model');
		$handle_datas  = $cdb->select("groupid=52 ","*",1000,"listorder asc");	
	
		include template('waybill', 'waybill_detail');	
	}

	//运单付款列表
	public function waybill_paydetail(){
		$bid = intval($_GET['bid']);
		$info = $this->db->get_one("siteid='".$this->siteid."' and aid='$bid' and userid='$this->_userid'");

		if(!$info){exit;}
		
		$udb = pc_base::load_model('member_model');
		$udb->set_model();
		$urow = $udb->get_one(" userid='$this->_userid'");
		$mobile = $urow['mobile'];

		$s_row = $this->getshippingtypename($info['storageid']);
		if($s_row){
			$storagename=$s_row['title'];
		}

		include template('waybill', 'waybill_paydetail');	
	}

	//打印

	public function printbill(){
		$bid = intval($_GET['bid']);
		$info = $this->db->get_one("siteid='".$this->siteid."' and aid='$bid' and userid='$this->_userid'");
		if(!$info){exit;}

		$udb = pc_base::load_model('member_model');
		$udb->set_model();
		$urow = $udb->get_one(" userid='".$info['userid']."'");
		$mobile = $urow['mobile'];

		

		$send_truename="";
		$send_country="";
		$send_province="";
		$send_city="";
		$send_address="";
		$send_postcode="";
		$send_mobile="";

		$take_truename="";
		$take_country="";
		$take_province="";
		$take_city="";
		$take_address="";
		$take_postcode="";
		$take_mobile="";

		$sendaddressname = 	$info['sendaddressname'];
		$takeaddressname = 	$info['takeaddressname'];

			

				$addr=explode("|",$sendaddressname);//发件
				$send_truename=$addr[0];
				$send_country=$addr[2];
				$send_province=$addr[3];
				$send_city=$addr[4];
				$send_address=$addr[5];
				$send_postcode=$addr[6];
				$send_mobile=$addr[1];
				//$send_company=$addr['company'];

				$addr=explode("|",$takeaddressname);///收件
				$take_truename=$addr[0];
				$take_country=$addr[2];
				$take_province=$addr[3];
				$take_city=$addr[4];
				$take_address=$addr[5];
				$take_postcode=$addr[6];
				$take_mobile=$addr[1];
				//$take_company=$addr['company'];
			
		

		include template('waybill', 'waybill_print');

	}
	
	//获取客户代码或个人运单号
	public function getbillcode($num){
		
		$row = $this->db->get_one("siteid='".$this->siteid."' and userid='$this->_userid'","*","addtime desc");
		//UK1000000100001KD2
		if($row){
			if($num==7)//客户
				$sourcenumber=intval(substr($row['waybillid'],3,$num))+1;
			else
				$sourcenumber=intval(substr($row['waybillid'],10,$num))+1;
		}else{
				$sourcenumber=1;
		}

		if($num==7){//客户
			return substr(strval($sourcenumber+10000000),1,$num);
		}else{//个人
			return substr(strval($sourcenumber+100000),1,$num);
		}
	}


	//two bill no
	public function getbill_orderno($org_h,$dst_h,$wid=0){
		$userid = $this->_userid;
		if(!is_numeric($org_h)){$org_h=100;}
	
		$num = $wid;
		$ordercode = str_replace("T","0",str_replace("H","",param::get_cookie('_clientname'))).date('md').str_pad($num,6,"0",STR_PAD_LEFT);
			
	
		return $ordercode;
	}




	public function add() {
		
		$package_array = $_POST['aid'];
		$session_storage = 'session_'.pc_base::load_config('system','session_storage');
		pc_base::load_sys_class($session_storage);

		$show_validator = 1;
		if(isset($_POST['dosubmit'])) {
			$_POST['waybill'] = $this->check($_POST['waybill']);
			
			if($_SESSION["isrepeat"] !=0){
				//showmessage(L('operaction_repeat'), "/index.php?m=waybill&c=index&a=init&hid=".$this->hid);
				//exit;
			}

			$_SESSION["isrepeat"]=1;
			
			$packagedb = pc_base::load_model('package_model');
			$gdb = pc_base::load_model('waybill_goods_model');
			

			

			//生成运单号--begin
			/**
				运单生成规则18位:   仓库代号两个字母和一个数字  客户代码7位数字或字母  个人运单号5位数字或字母  目的地地址代码2位字母和一个数字 
			**/
			$waybillid="";
			$org_housecode = "000";//仓库代码3位 //发货地 (国家和地区)
			$dst_housecode = "000";//仓库代码3位 //收货地 (国家和地区)
			$allsendlists = $this->getsendlists();
			foreach($allsendlists as $addr){
				if(intval($_POST['waybill']['sendid'])==$addr['linkageid']){
					$org_housecode = $addr['storagecode'];
				}
				if(intval($_POST['waybill']['takeid'])==$addr['linkageid']){
					$dst_housecode = $addr['storagecode'];
				}
			}

			$seven=str_pad(dechex($this->_userid),7,'0',STR_PAD_LEFT);//客户代码7位
			$five=$this->getbillcode(5);//个人代码5位
			$waybillid=$org_housecode.$seven.$five.$dst_housecode;
			//生成运单号 结束end

			//生成系统订单号
			

			$_rs =siteinfo($this->siteid); 
			$billprefix="";
			if($_rs){
				$billprefix=$_rs['billprefix'];
			}
			$sysbillid=$billprefix.date('ymdHis',time()).floor(microtime()*1000);
			//--------------end
			
			/* 插入订单表 *
			$error_no = 0;
			do
			{
					$waybillid = $this->getbill_orderno($org_housecode,$dst_housecode,get__order__counter(),$this->_userid); //获取新订单号
					$error_no = $this->db->count(array('waybillid'=>trim($waybillid)));
			}
			while ($error_no > 0 ); //如果是订单号重复则重新提交数据

				*/
			$waybillid = common____orderno();
			$_POST['waybill']['waybillid']=$waybillid;
			$_POST['waybill']['sysbillid']=$sysbillid;

			//--------------end

			
			$_POST['waybill']['storageid'] = intval($_POST['storageidd']);

			$_POST['waybill']['status']=1;//未入库
			
			$address_db = pc_base::load_model('address_model');
			if($_POST['waybill']['takeaddressid']==0){//新增收货地址,要保护进入地址里面
				
				$_POST['take_address']['siteid']=$this->siteid;
				$_POST['take_address']['addresstype']=1;
				$_POST['take_address']['addtime'] = SYS_TIME;
				$_POST['take_address']['username'] = $this->_username;
				$_POST['take_address']['userid'] = $this->_userid;
				

				$address_db->insert(safe_array_string($_POST['take_address']));
				$address_idd = $address_db->insert_id();
				
				$_POST['waybill']['takeaddressid'] = $address_idd;
			}

			/*if($_POST['waybill']['sendaddressid']==0){//新增发货地址,要保护进入地址里面
				
				$_POST['send_address']['siteid']=$this->siteid;
				$_POST['send_address']['addtime'] = SYS_TIME;
				$_POST['send_address']['username'] = $this->_username;
				
				$_POST['send_address']['userid'] = $this->_userid;
			

				$address_db->insert(safe_array_string($_POST['send_address']));
				$address_sidd = $address_db->insert_id();
				
				$_POST['waybill']['sendaddressid'] = $address_sidd;
			}*/


			//保存地址
			$sendaddressname='';
			$sendaddr=$address_db->get_one(array('addresstype'=>2));
			if($sendaddr){
				$_POST['waybill']['sendaddressid'] = $sendaddr['aid'];

					$sendaddressname=$sendaddr['truename'].'|'.$sendaddr['mobile'].' '.'|'.$sendaddr['country'].'|'.$sendaddr['province'].'|'.$sendaddr['city'].'|'.$sendaddr['address'].'|'.$sendaddr['postcode'];
			}
			$_POST['waybill']['sendaddressname']=str_replace("&amp;","&",$sendaddressname);

			$takeaddressname='';
			$takeaddr=$address_db->get_one(array('aid'=>$_POST['waybill']['takeaddressid']));
			if($takeaddr){
				$_POST['waybill']['takename'] = trim($takeaddr['country']);
				$_POST['waybill']['truename'] = $takeaddr['truename'];
				$takeaddressname=$takeaddr['truename'].'|'.$takeaddr['mobile'].' '.'|'.$takeaddr['country'].'|'.$takeaddr['province'].'|'.$takeaddr['city'].'|'.$takeaddr['address'].'|'.$takeaddr['postcode'];
			}
			$_POST['waybill']['takeaddressname']=str_replace("&amp;","&",$takeaddressname);
$addFee=0;

			//print_r($_POST['waybill']);exit;
			//插入增值服务-----------------------------------------------------------------
				if(is_array($_POST['service_value'])){
					foreach($_POST['service_value'] as $key=>$v){
							$m_typeid = $_POST['service_value'][$key]['servicetypeid'];
							$m_price = $_POST['service_value'][$key]['price'];
							$m_currencyid = $_POST['service_value'][$key]['currencyid'];
							$m_unitid = $_POST['service_value'][$key]['unitid'];
							$m_type = $_POST['service_value'][$key]['servicetype'];
							
							$m_title = $_POST['service_value'][$key]['title'];
							$m_currencyname = $_POST['service_value'][$key]['currencyname'];
							$m_unitname = $_POST['service_value'][$key]['unitname'];
							if($m_typeid){
							$srv_value_array[$m_typeid]['currencyid'] = $m_currencyid;
							$srv_value_array[$m_typeid]['unitid'] = $m_unitid;
							$srv_value_array[$m_typeid]['servicetype'] = $m_type;
							$srv_value_array[$m_typeid]['servicetypeid'] = $m_typeid;
							$srv_value_array[$m_typeid]['price'] = $m_price;
							if($m_typeid!=13){
								$addFee+=floatval($m_price);
							}
							$srv_value_array[$m_typeid]['title'] = $m_title;
							$srv_value_array[$m_typeid]['currencyname'] = $m_currencyname;
							$srv_value_array[$m_typeid]['unitname'] = $m_unitname;}
					}
				}

			$_POST['waybill']['addvalues'] = array2string($srv_value_array);
			$_POST['waybill']['goodsdatas'] = array2string($_POST['waybill_goods']);
			//-------------------------------------------------------------------------------
			//$_POST['waybill']['totalweight']=0;
			$goodsname="";
			foreach($_POST['waybill_goods'] as $goods){
				if(empty($goodsname)){
					$goodsname = $goods['goodsname'];
				}else{
					$goodsname .=" ". $goods['goodsname'];
				}
			}
			
			$_POST['waybill']['goodsname']  = $goodsname;
			/*
		$udb = pc_base::load_model('member_model');

		$udb->set_model(10);
				$u_row = $udb->get_one(array('userid'=>$this->_userid));
				if($u_row['truename']!=""){
					$_POST['waybill']['truename']=$u_row['truename'];
				}
				else{
				$_POST['waybill']['truename'] = $_POST['take_address']['truename'];
				}
				$udb->set_model();
				*/



			//truename 
			
			//$_POST['waybill']['truename'] = $takeaddr['truename'];
			
			//$_POST['waybill']['storagename'] =  $this->storagename;

			$goods_names = "";
			
			
			/*$_POST['waybill']['goodsname'] = trim($_POST['package']['goodsname']);
			$_POST['waybill']['expressname'] = trim($_POST['package']['expressname']);
			$_POST['waybill']['expressno'] = trim($_POST['package']['expressno']);
			$_POST['waybill']['totalamount'] = trim($_POST['package']['amount']);
			$_POST['waybill']['totalweight'] = trim($_POST['package']['weight']);
			$_POST['waybill']['totalprice'] = trim($_POST['package']['price']);

			$_POST['waybill']['bill_long'] = trim($_POST['package']['package_l']);
			$_POST['waybill']['bill_width'] = trim($_POST['package']['package_w']);
			$_POST['waybill']['bill_height'] = trim($_POST['package']['package_h']);

			$_POST['waybill']['gsexpress'] = trim($_POST['package']['gsexpress']);

			if($_POST['waybill']['gsexpress']==2 ){
				$_POST['waybill']['status']=3;
			}*/

			$_POST['waybill']['takecode'] = param::get_cookie('_clientcode');
			$_POST['waybill']['takeflag'] = param::get_cookie('_clientname');

			//print_r($_POST['waybill']);exit;




			//$_POST['waybill']['allvaluedfee'] =0;
			


			//$payedfee = get_ship_fee($_POST['waybill']['totalweight'],get_common_shipline($_POST['waybill']['shippingid']));
			$payedfee = 0;
			
		
				$wayrate = $this->getwayrate();
			//$_POST['waybill']['payfeeusd']  = $payedfee+$addFee+floatval($_POST['waybill']['otherfee']);
			$_POST['waybill']['payfeeusd']  = 0;

				$_POST['waybill']['allvaluedfee'] = $addFee;
				$_POST['waybill']['wayrate'] = $wayrate;
				$_POST['waybill']['totalship'] = $payedfee;
				$_POST['waybill']['payedfee'] = floatval($_POST['waybill']['wayrate']) * floatval($_POST['waybill']['payfeeusd']);


			if($this->db->insert($_POST['waybill'])){
				
				$lastinsertid = $this->db->insert_id();


				$historydb = pc_base::load_model('waybill_history_model');
			
				//插入处理记录
				$handle=array();
				$handle['siteid'] = 1;
				$handle['username'] = $this->_username;
				$handle['userid'] = $this->_userid;
				$handle['addtime'] = SYS_TIME;
				$handle['sysbillid'] = trim($_POST['waybill']['sysbillid']);
				$handle['waybillid'] = trim($_POST['waybill']['waybillid']);
				$handle['placeid'] = $_POST['waybill']['storageid'];
				$handle['placename'] = $_POST['waybill']['storagename'];
				$handle['status'] = 1;
				$handle['remark'] = L('waybill_status'.$handle['status']);	
				$historydb->insert($handle);

			
					
				showmessage(L('waybillment_successful_added'), "/index.php?m=package&c=index&a=init&hid=".$this->hid);
				
			}
		} else {
			$shippingtypes = $this->getshippingtype();
			$addresslist = $this->getaddresslist();
			$servicelist = $this->getservicelist();
			


			$_SESSION["isrepeat"]=0;
			$waybilltm = str_replace('"date"','"date inp"',form::date('waybill[waybilltime]','',''));
			

			$storagedb = pc_base::load_model('storage_model');
			$storage_row = $storagedb->get_one(array('aid'=>$this->hid));
			$udb = pc_base::load_model('member_model');
			$udb->set_model();
			$urow = $udb->get_one(" userid='$this->_userid'");
			$mobile = $urow['mobile'];
			$email = $urow['email'];
			$areadb = pc_base::load_model('linkage_model');
			$area_row=$areadb->get_one(array('linkageid'=>$storage_row['area']));
			$areaname= $area_row['name'];

			include template('waybill', 'waybill_add');
		}

		
	}

	public function edit() {
		

		$this->db2 = pc_base::load_model('currency_model');
		$session_storage = 'session_'.pc_base::load_config('system','session_storage');
		pc_base::load_sys_class($session_storage);

		$show_validator = 1;
		$_GET['aid'] = intval($_GET['aid']);
		$aid = $_GET['aid'];
		if(!$_GET['aid']) showmessage(L('illegal_operation'));
		if(isset($_POST['dosubmit'])) {
			$wayrate = $this->getwayrate();
			$_POST['waybill'] = $this->check($_POST['waybill'], 'edit');
			if($_SESSION["isrepeat"] !=0){
				//showmessage(L('operaction_repeat'), "/index.php?m=waybill&c=index&a=init&hid=".$this->hid);
				//exit;
			}

			$_SESSION["isrepeat"]=1;

			$address_db = pc_base::load_model('address_model');
			if($_POST['waybill']['takeaddressid']==0){//新增收货地址,要保护进入地址里面
				

				$_POST['take_address']['siteid']=$this->siteid;
				$_POST['take_address']['addresstype']=1;
				$_POST['take_address']['addtime'] = SYS_TIME;
				$_POST['take_address']['username'] = $this->_username;
				$_POST['take_address']['userid'] = $this->_userid;
				
				$uaddr = $address_db->get_one(array('addresstype'=>1,'isdefault'=>1,'userid'=>$this->_userid));
				if(!$uaddr)
					$_POST['take_address']['isdefault']=1;

				$address_db->insert(safe_array_string($_POST['take_address']));
				$address_idd = $address_db->insert_id();
				$_POST['waybill']['takeaddressid'] = $address_idd;
			}

			/*if($_POST['waybill']['sendaddressid']==0){//新增发货地址,要保护进入地址里面
				
				$_POST['send_address']['siteid']=$this->siteid;
				$_POST['send_address']['addtime'] = SYS_TIME;
				$_POST['send_address']['username'] = $this->_username;
				$_POST['send_address']['userid'] = $this->_userid;

				$uaddr = $address_db->get_one(array('addresstype'=>2,'isdefault'=>1,'userid'=>$this->_userid));
				if(!$uaddr)
					$_POST['take_address']['isdefault']=1;

				$address_db->insert(safe_array_string($_POST['send_address']));
				$address_sidd = $address_db->insert_id();
				$_POST['waybill']['sendaddressid'] = $address_sidd;
			}*/

			//保存地址
			$sendaddressname='';
			$sendaddr=$address_db->get_one(array('addresstype'=>2));
			if($sendaddr){
					$_POST['waybill']['sendaddressid'] = $sendaddr['aid'];
					$sendaddressname=$sendaddr['truename'].'|'.$sendaddr['mobile'].' '.'|'.$sendaddr['country'].'|'.$sendaddr['province'].'|'.$sendaddr['city'].'|'.$sendaddr['address'].'|'.$sendaddr['postcode'];
			}
			$_POST['waybill']['sendaddressname']=str_replace("&amp;","&",$sendaddressname);

			$takeaddressname='';
			$takeaddr=$address_db->get_one(array('aid'=>$_POST['waybill']['takeaddressid']));
			if($takeaddr){
				$_POST['waybill']['truename'] = $takeaddr['truename'];
				$_POST['waybill']['takename'] = trim($takeaddr['country']);
				$takeaddressname=$takeaddr['truename'].'|'.$takeaddr['mobile'].' '.'|'.$takeaddr['country'].'|'.$takeaddr['province'].'|'.$takeaddr['city'].'|'.$takeaddr['address'].'|'.$takeaddr['postcode'];
			}
			$_POST['waybill']['takeaddressname']=str_replace("&amp;","&",$takeaddressname);

			//插入增值服务-----------------------------------------------------------------
			$addFee=0;
				$srv_value_array = array();
				if(is_array($_POST['service_value'])){
					foreach($_POST['service_value'] as $key=>$v){
							$m_typeid = $_POST['service_value'][$key]['servicetypeid'];
							$m_price = $_POST['service_value'][$key]['price'];
							$m_currencyid = $_POST['service_value'][$key]['currencyid'];
							$m_unitid = $_POST['service_value'][$key]['unitid'];
							$m_type = $_POST['service_value'][$key]['servicetype'];
							
							$m_title = $_POST['service_value'][$key]['title'];
							$m_currencyname = $_POST['service_value'][$key]['currencyname'];
							$m_unitname = $_POST['service_value'][$key]['unitname'];
							if($m_typeid){
							$srv_value_array[$m_typeid]['currencyid'] = $m_currencyid;
							$srv_value_array[$m_typeid]['unitid'] = $m_unitid;
							$srv_value_array[$m_typeid]['servicetype'] = $m_type;
							$srv_value_array[$m_typeid]['servicetypeid'] = $m_typeid;
							$srv_value_array[$m_typeid]['price'] = $m_price;
							if($m_typeid!=13){
								$addFee+=floatval($m_price);
							}
							$srv_value_array[$m_typeid]['title'] = $m_title;
							$srv_value_array[$m_typeid]['currencyname'] = $m_currencyname;
							$srv_value_array[$m_typeid]['unitname'] = $m_unitname;}
					}
				}

			$_POST['waybill']['addvalues'] = array2string($srv_value_array);
			//-------------------------------------------------------------------------------

			$goodsname="";
			foreach($_POST['waybill_goods'] as $goods){
				if(empty($goodsname)){
					$goodsname = $goods['goodsname'];
				}else{
					$goodsname .=",". $goods['goodsname'];
				}
			}
			
			$_POST['waybill']['goodsname']  = $goodsname;
			$_POST['waybill']['goodsdatas'] = array2string($_POST['waybill_goods']);

			//truename 
			//$_POST['waybill']['truename'] = $this->current_user['lastname'].$this->current_user['firstname'];
			//$_POST['waybill']['storagename'] =  $this->storagename;
			$_POST['waybill']['storageid'] = intval($_POST['storageidd']);


			$_POST['waybill']['allvaluedfee'] = $addFee;
			

			$an_info = $this->db->get_one(array('aid' => $_GET['aid']));

			$payedfee = get_ship_fee($_POST['waybill']['totalweight'],get_common_shipline($_POST['waybill']['shippingid']),trim($an_info['takename']));
			
			
				
			
		
				$wayrate = $this->getwayrate();
			$_POST['waybill']['payfeeusd']  = $payedfee+$addFee+floatval($_POST['waybill']['otherfee']);

				$_POST['waybill']['allvaluedfee'] = $addFee;
				$_POST['waybill']['wayrate'] = $wayrate;
				$_POST['waybill']['totalship'] = $payedfee;
				$_POST['waybill']['payedfee'] = ($an_info['taxfee']) + floatval($_POST['waybill']['wayrate']) * floatval($_POST['waybill']['payfeeusd']);
				
				
			//print_r($_POST['waybill']);exit;

			if($this->db->update(($_POST['waybill']), array('aid' => $_GET['aid']))){
			
				showmessage(L('waybilld_a'), "/index.php?m=package&c=index&a=init&hid=".$this->hid);
				
			}

		} else {
			$_SESSION["isrepeat"]=0;// 

			$where = array('aid' => $_GET['aid']);
			$an_info = $this->db->get_one($where);
			$an_info2 = $this->db2->get_one(array('aid' => 4));
			$shippingtypes = $this->getshippingtype();
			$addresslist = $this->getaddresslist();
			$servicelist = $this->getservicelist();
			
			$storagedb = pc_base::load_model('storage_model');
			$storage_row = $storagedb->get_one(array('aid'=>$an_info['storageid']));
			$udb = pc_base::load_model('member_model');
			$udb->set_model();
			$urow = $udb->get_one(" userid='$this->_userid'");
			$mobile = $urow['mobile'];
			$email = $urow['email'];
			$areadb = pc_base::load_model('linkage_model');
			$area_row=$areadb->get_one(array('linkageid'=>$storage_row['area']));
			$areaname= $area_row['name'];

			

			$waybilltm = str_replace('"date"','"date inp"',form::date('waybill[waybilltime]',date('Y-m-d',$an_info['waybilltime']),''));

			include template('waybill', 'waybill_edit');
		}
		
	}

	public function editline() {
		

		$this->db2 = pc_base::load_model('currency_model');

		$session_storage = 'session_'.pc_base::load_config('system','session_storage');
		pc_base::load_sys_class($session_storage);

		$show_validator = 1;
		$_GET['aid'] = intval($_GET['aid']);
		$aid = $_GET['aid'];
		if(!$_GET['aid']) showmessage(L('illegal_operation'));
		if(isset($_POST['dosubmit'])) {
			$wayrate = $this->getwayrate();
			$_POST['waybill'] = $this->check($_POST['waybill'], 'edit');
		

			$_SESSION["isrepeat"]=1;

		
			$address_db = pc_base::load_model('address_model');
			if($_POST['waybill']['takeaddressid']==0){//新增收货地址,要保护进入地址里面
				

				$_POST['take_address']['siteid']=$this->siteid;
				$_POST['take_address']['addresstype']=1;
				$_POST['take_address']['addtime'] = SYS_TIME;
				$_POST['take_address']['username'] = $this->_username;
				$_POST['take_address']['userid'] = $this->_userid;
				
				$uaddr = $address_db->get_one(array('addresstype'=>1,'isdefault'=>1,'userid'=>$this->_userid));
				if(!$uaddr)
					$_POST['take_address']['isdefault']=1;

				$address_db->insert(safe_array_string($_POST['take_address']));
				$address_idd = $address_db->insert_id();
				$_POST['waybill']['takeaddressid'] = $address_idd;
			}


			$takeaddressname='';
			$takeaddr=$address_db->get_one(array('aid'=>$_POST['waybill']['takeaddressid']));
			if($takeaddr){
				$_POST['waybill']['truename'] = $takeaddr['truename'];
				$_POST['waybill']['takename'] = trim($takeaddr['country']);

				$takeaddressname=$takeaddr['truename'].'|'.$takeaddr['mobile'].' '.'|'.$takeaddr['country'].'|'.$takeaddr['province'].'|'.$takeaddr['city'].'|'.$takeaddr['address'].'|'.$takeaddr['postcode'];
			}
			$_POST['waybill']['takeaddressname']=str_replace("&amp;","&",$takeaddressname);
			

			$an_info = $this->db->get_one( array('aid' => $_GET['aid']));
			
			$addFee = $an_info['allvaluedfee']; 
			$otherfee = $an_info['otherfee']; 
			$_POST['waybill']['otherfee'] = $otherfee;

			$payedfee = get_ship_fee($an_info['totalweight'],get_common_shipline($_POST['waybill']['shippingid']),trim($_POST['waybill']['takename']));
			
			if($an_info['status']!=8){
				$_POST['waybill']['status'] = 21;
			}

			//插入增值服务-----------------------------------------------------------------
			$addFee=0;
				$srv_value_array = array();
				if(is_array($_POST['service_value'])){
					foreach($_POST['service_value'] as $key=>$v){
							$m_typeid = $_POST['service_value'][$key]['servicetypeid'];
							$m_price = $_POST['service_value'][$key]['price'];
							$m_currencyid = $_POST['service_value'][$key]['currencyid'];
							$m_unitid = $_POST['service_value'][$key]['unitid'];
							$m_type = $_POST['service_value'][$key]['servicetype'];
							
							$m_title = $_POST['service_value'][$key]['title'];
							$m_currencyname = $_POST['service_value'][$key]['currencyname'];
							$m_unitname = $_POST['service_value'][$key]['unitname'];
							if($m_typeid){
							$srv_value_array[$m_typeid]['currencyid'] = $m_currencyid;
							$srv_value_array[$m_typeid]['unitid'] = $m_unitid;
							$srv_value_array[$m_typeid]['servicetype'] = $m_type;
							$srv_value_array[$m_typeid]['servicetypeid'] = $m_typeid;
							$srv_value_array[$m_typeid]['price'] = $m_price;
							if($m_typeid!=13){
								$addFee+=floatval($m_price);
							}
							$srv_value_array[$m_typeid]['title'] = $m_title;
							$srv_value_array[$m_typeid]['currencyname'] = $m_currencyname;
							$srv_value_array[$m_typeid]['unitname'] = $m_unitname;}
					}
				}

			$_POST['waybill']['addvalues'] = array2string($srv_value_array);
			//-------------------------------------------------------------------------------
				
			$_POST['waybill']['payfeeusd']  = $payedfee+$addFee+floatval($_POST['waybill']['otherfee']);

				$_POST['waybill']['allvaluedfee'] = $addFee;
				$_POST['waybill']['wayrate'] = $wayrate;
				$_POST['waybill']['totalship'] = $payedfee;
				//$_POST['waybill']['weight'] = 0;
				$_POST['waybill']['payedfee'] = ($an_info['taxfee']) + floatval($_POST['waybill']['wayrate']) * floatval($_POST['waybill']['payfeeusd']);	
		
			$_POST['waybill']['handletime'] = SYS_TIME;

			if($this->db->update(($_POST['waybill']), array('aid' => $_GET['aid']))){


				$historydb = pc_base::load_model('waybill_history_model');
			
				//插入处理记录
				$handle=array();
				$handle['siteid'] = 1;
				$handle['username'] = $this->_username;
				$handle['userid'] = $this->_userid;
				$handle['addtime'] = SYS_TIME;
				$handle['sysbillid'] = trim($an_info['sysbillid']);
				$handle['waybillid'] = trim($an_info['waybillid']);
				$handle['placeid'] = $an_info['storageid'];
				$handle['placename'] = $an_info['storagename'];
				$handle['status'] = $_POST['waybill']['status'];
				$historydb->delete(array('status'=>$handle['status'],'waybillid'=>$handle['waybillid'] ));
				$handle['remark'] = L('waybill_status'.$handle['status']);	
				$historydb->insert($handle);
				
				if($an_info['status']==8){
					showmessage(L('waybilld_a'), "/index.php?m=package&c=index&a=nopaypackage&hid=".$this->hid);
				}else{
					showmessage(L('waybilld_a'), "/index.php?m=package&c=index&a=sendedmanage&hid=".$this->hid);
				}
			}

		} else {
			$_SESSION["isrepeat"]=0;// 

			$where = array('aid' => $_GET['aid']);
			$an_info = $this->db->get_one($where);
			$an_info2 = $this->db2->get_one(array('aid' => 4));
			$shippingtypes = $this->getshippingtype();
			$addresslist = $this->getaddresslist();
			$servicelist = $this->getservicelist();
			
			$storagedb = pc_base::load_model('storage_model');
			$storage_row = $storagedb->get_one(array('aid'=>$an_info['storageid']));
			$udb = pc_base::load_model('member_model');
			$udb->set_model();
			$urow = $udb->get_one(" userid='$this->_userid'");
			$mobile = $urow['mobile'];
			$email = $urow['email'];
			$areadb = pc_base::load_model('linkage_model');
			$area_row=$areadb->get_one(array('linkageid'=>$storage_row['area']));
			$areaname= $area_row['name'];

			

			$waybilltm = str_replace('"date"','"date inp"',form::date('waybill[waybilltime]',date('Y-m-d',$an_info['waybilltime']),''));

			include template('waybill', 'waybill_editline');
		}
		
	}


	/**
	 * 批量删除预约
	 */
	public function delete($aid = 0) {

		if((!isset($_POST['aid']) || empty($_POST['aid'])) && !$aid) {
			showmessage(L('illegal_operation'));
		} else {
			if(is_array($_POST['aid']) && !$aid) {
				array_map(array($this, 'delete'), $_POST['aid']);
				showmessage(L('operation_success'), HTTP_REFERER);
			} elseif($aid) {
				$aid = intval($aid);
				$waybillid="";
				$row = $this->db->get_one(array('aid' => $aid));
				if($row)
				{
					if($row['status']==1 || $row['status']==6){
					$waybillid=$row['waybillid'];	
				
					$this->db->delete(array('aid' => $aid));
					
					//删除子关联数据
					$gdb = pc_base::load_model('waybill_goods_model');//运单物品
					$srvdb = pc_base::load_model('waybill_serviceitem_model');//运单服务项
					$historydb = pc_base::load_model('waybill_history_model');//历史记录
					/*$packdb = pc_base::load_model('package_model');//包裹

					$packall = $gdb->select(array('waybillid' => $waybillid),"*",1000,"");
					foreach($packall as $pack){
						$expressno= trim($pack['expressno']);
						$packdb->delete(array('expressno' => $expressno));
					}*/

					$gdb->delete(array('waybillid' => $waybillid));
					$srvdb->delete(array('waybillid' => $waybillid));
					$historydb->delete(array('waybillid' => $waybillid));

					}

				}

			}
		}
	}
	
	/**
	 * 验证表单数据
	 * @param  		array 		$data 表单数组数据
	 * @param  		string 		$a 当表单为添加数据时，自动补上缺失的数据。
	 * @return 		array 		验证后的数据
	 */
	private function check($data = array(), $a = 'add') {
		//if($data['truename']=='') showmessage(L('truename_cannot_empty'));
		
		//$r = $this->db->get_one(array('truename' => $data['truename']));
		
		
		if ($a=='add') {
			/*if (is_array($r) && !empty($r)) {
				showmessage(L('waybill_exist'), HTTP_REFERER);
			}*/
			$data['siteid'] = $this->siteid;
			$data['addtime'] = SYS_TIME;
			$data['username'] = $this->_username;
			$data['userid'] = $this->_userid;
			$data['storageid'] = $this->hid;
			
		} else {
			/*if ($r['aid'] && ($r['aid']!=$_GET['aid'])) {
				showmessage(L('waybill_exist'), HTTP_REFERER);
			}*/
		}

		

		return $data;
	}


	//更新运单状态
	private function updatewaybill_status($waybillid, $returncode='',$isadd=false){
		$billgoods = $this->getwaybill_goods($waybillid, $returncode);
		$pdb = pc_base::load_model('package_model');
		$wdb = pc_base::load_model('waybill_model');
		$status1=0;
		$status2=0;
		
		
		foreach($billgoods as $val){
			$row = $pdb->get_one(array('expressno'=>$val['expressno'], 'returncode'=>$returncode));
			if($row['status']==1){
				$status1+=1;
			}

			if($row['status']==2){
				$status2+=1;
			}
		}

		if($isadd){
			//----------------------------------------------------
				$historydb2 = pc_base::load_model('waybill_history_model');
				$row = $wdb->get_one(array('waybillid'=>$waybillid, 'returncode'=>$returncode));
				//插入处理记录
				$handle=array();
				$handle['siteid'] = 1;
				$handle['username'] = $this->_username;
				$handle['userid'] = $this->_userid;
				$handle['addtime'] = SYS_TIME;
				$handle['sysbillid'] = trim($row['sysbillid']);
				$handle['waybillid'] = trim($row['waybillid']);
				$handle['placeid'] = $row['storageid'];
				$handle['placename'] = $row['storagename'];
				$handle['status'] = 1;
				$handle['remark'] = L('waybill_status'.$handle['status']);	
				$lid = $historydb2->insert($handle);
				
				//-----------------------------------------------------	
		}

		if(count($billgoods)==$status2)//表示全部已入库
		{
			$wdb->update(array('status'=>3),array('waybillid'=>$waybillid, 'returncode'=>$returncode));//更新运单已入库状态


			//----------------------------------------------------
				$historydb = pc_base::load_model('waybill_history_model');
				$row = $wdb->get_one(array('waybillid'=>$waybillid, 'returncode'=>$returncode));
				//插入处理记录
				$handle=array();
				$handle['siteid'] = 1;
				$handle['username'] = $this->_username;
				$handle['userid'] = $this->_userid;
				$handle['addtime'] = SYS_TIME;
				$handle['sysbillid'] = trim($row['sysbillid']);
				$handle['waybillid'] = trim($row['waybillid']);
				$handle['placeid'] = $row['storageid'];
				$handle['placename'] = $row['storagename'];
				$handle['status'] = 3;
				$handle['remark'] = L('waybill_status'.$handle['status']);	
				$lid = $historydb->insert($handle);
				
				//-----------------------------------------------------	

		}elseif($status2>0)//表示全部已入库
		{
			//$wdb->update(array('status'=>2),array('waybillid'=>$waybillid, 'returncode'=>$returncode));//更新运单已入库状态
		}else{
			$wdb->update(array('status'=>1),array('waybillid'=>$waybillid, 'returncode'=>$returncode));//更新运单已入库状态
		}

	}


	/**根据运单号获取运单物品详细**/
	private function getwaybill_goods($waybillid, $returncode=''){
		$cdb = pc_base::load_model('waybill_goods_model');
		$sql = "  waybillid='$waybillid' AND returncode='$returncode' ";
		$datas = $cdb->select($sql,'*',10000,'number asc');
		return $datas;
	}

	/**根据运单号获取增值服务**/
	private function getwaybill_servicelist($waybillid,$waybill_goodsid){
		$cdb = pc_base::load_model('waybill_serviceitem_model');
		$sql = " waybillid='$waybillid' and waybill_goodsid='$waybill_goodsid'";
		$datas = $cdb->select($sql,'*',10000,'id desc');
		return $datas;
	}

	

	/**获取转运类别**/
	private function getshippingtype(){
		$datas = getcache('get__storage__lists', 'commons');
		return $datas;
	}

	/**获取转运类别名称**/
	private function getshippingtypename($typeid){
		$cdb = pc_base::load_model('storage_model');
		$sql = ' siteid=\''.$this->siteid.'\' and  aid='.$typeid.'  ';
		$datas = $cdb->get_one($sql);
		return $datas;
	}
	
	/**获取收货地址**/
	private function getaddresslist(){
		$cdb = pc_base::load_model('address_model');
		$sql = ' siteid=\''.$this->siteid.'\' and (userid='.$this->_userid.' OR username=\''.$this->_username.'\') ';
		$datas = $cdb->select($sql,'*',10000,'addtime desc');
		return $datas;
	}

	/**获取增值服务**/
	private function getservicelist(){
		$cdb = pc_base::load_model('service_model');
		$sql = ' siteid=\''.$this->siteid.'\' and type=\'value-added\'';
		$datas = $cdb->select($sql,'*',10000,'addtime desc');
		return $datas;
	}



	/** 获取发货地 (国家和地区) **/
	private function getsendlists(){
		$cdb = pc_base::load_model('storage_model');
		$sql = ' siteid=\''.$this->siteid.'\'  ';
		$results = $cdb->select($sql,'*',10000,'listorder ASC');

		$datas = array();
		$datas = $this->getline();
		/*foreach($lines as $k=>$v){
			foreach($results as $kk=>$row){
				if($v['linkageid']==$row['area']){
					$v['storagecode']=$row['storagecode'];
					$datas[]=$v;
				}
			}
		}*/
		return $datas;
	}



	/**获取运单订单状态**/
	private function getwaybilltatus($id=0,$storageid){
		$cdb = pc_base::load_model('enum_model');
		
			$sql = ' groupid=9 and value=\''.$id.'\' ';
			$datas = $cdb->get_one($sql);
			$statusname="";
			if($id==17)//已入xx仓库
			{
				$statusname=str_replace(L('enter_xxstorage'),$this->getstoragename($storageid),$datas['title']);
			}else{
				$statusname=$datas['title'];
			}
			return $statusname.' ';
		
	}

	/** 获取收货地 (国家和地区) **/
	public function gettakelists(){

		$areaid = isset($_GET['areaid']) ? intval($_GET['areaid']) : 0;
		$datas = array();
		$sendlists = $this->getsendlists();
		
		foreach($sendlists as $k=>$v){
			//if($v['linkageid']!=$areaid)
				$datas[]=$v;
		}
		print_r(json_encode($datas));
		exit;
	}

	/**获取国家地区**/
	private function getline(){
		$cdb = pc_base::load_model('linkage_model');
		$sql = ' parentid=0 AND keyid=0 ';
		$datas = $cdb->select($sql,'*',10000,'listorder ASC');
		return $datas;
	}

	/**获取转运方式**/
	private function getturnway(){
		$cdb = pc_base::load_model('shipline_model');
		$sql = ' siteid=\''.$this->siteid.'\' ';
		$datas = $cdb->select($sql,'*',10000,'listorder asc');
		return $datas;
	}

	

	/**获取所有货运公司**/
	private function getallship($id=0){
		$cdb = pc_base::load_model('enum_model');
		$sql = '`siteid`=\''.$this->siteid.'\' AND groupid=1 ';
		if($id>0){
			$sql.=' and aid=\''.$id.'\' ';
			$datas = $cdb->get_one($sql);
			return $datas;
		}else{
			$datas = $cdb->select($sql,'*',10000,'listorder ASC');
			return $datas;
		}
	}

	
	//获取增值分类名称
	private function getvaluetype($val){
		$valname="";
		$allvaluecategory = $this->valuecategory();
		foreach($allvaluecategory as $r){
			if($r['value']==$val)
				$valname=$r['title'];
		}
		return $valname;
	}

	/**获取所有货币**/
	private function getcurrency($idd=0){
		$cdb = pc_base::load_model('currency_model');
		if($idd==0){
			$sql = '`siteid`=\''.$this->siteid.'\'';
			$datas = $cdb->select($sql,'*',10000);
			return $datas;
		}else{
			$datas = $cdb->get_one("siteid='$this->siteid' AND aid='$idd'",'symbol');
			return $datas['symbol'];
		}
	}

	/**获取所有默认货币单位**/
	private function getdefaultcurrency(){
		$cdb = pc_base::load_model('currency_model');
		$sql = '`siteid`=\''.$this->siteid.'\' AND `isdefault`=1';
		$datas = $cdb->get_one($sql);

		
		return $datas;
	}

	/**获取所有单位**/
	private function getunits($idd=0){
		$cdb = pc_base::load_model('enum_model');
		if($idd==0){
		$sql = '`siteid`=\''.$this->siteid.'\' AND groupid=2 ';
		$datas = $cdb->select($sql,'*',10000,'listorder ASC');
		return $datas;
		}else{
			
			$datas = $cdb->get_one("siteid='$this->siteid' AND groupid=2 and aid='$idd'",'title');
			return $datas['title'];
		}
	}

	/**获取所有订单打包增值服务**/
	private function getgoodsservice(){
		$cdb = pc_base::load_model('service_model');
		$sql = '`siteid`=\''.$this->siteid.'\' AND type=\'order\' ';
		$datas = $cdb->select($sql,'*',10000,'');
		return $datas;
	}
	

	//根据ID返回货币名称
	private function getcurrencyname($cid){
		/*$name="";
		foreach($this->getcurrency($cid) as $v){
			if($v[aid]==$cid)
				$name=$v[symbol];
		}*/

		return $this->getcurrency($cid);
	}

	//根据ID返回单位名称
	private function getunitname($cid){
		/*$name="";
		foreach($this->getunits($cid) as $v){
			if($v[aid]==$cid)
				$name=$v[title];
		}*/
		return $this->getunits($cid);
	}

	/**获取所有订单状态**/
	private function getorderstatus(){
		$cdb = pc_base::load_model('enum_model');
		$sql = '`siteid`=\''.$this->siteid.'\' AND groupid=9 and value!=99 ';
		$datas = $cdb->select($sql,'*',10000,'listorder ASC');
		return $datas;
	}

	/**获取单个订单状态**/
	private function getonestatus($id,$storageid=0){
		$cdb = pc_base::load_model('enum_model');
		$sql = '`siteid`=\''.$this->siteid.'\' AND groupid=9 and value=\''.$id.'\' ';
		$datas = $cdb->get_one($sql);
		
		
		$statusname="";
		if($id==17 && $storageid>0)//已入xx仓库
		{
				$statusname=str_replace(L('enter_xxstorage'),$this->getstoragename($storageid),$datas['title']);
		}else{
				$statusname=$datas['title'];
		}
	

		return $statusname;
	}

	//获取当前仓库
	public function getstoragename($storageid){
		$udb = pc_base::load_model('storage_model');
		$r = $udb->get_one("aid='$storageid' ");
		return $r['title'];
	}



	/**获取所有当前用户包裹**/
	private function getmypackage($package_array){
		$cdb = pc_base::load_model('package_model');
		$datas=array();
		if(is_array($package_array)){
			
		$sql = " siteid='$this->siteid'  and userid='$this->_userid' and status!=3 and storageid='$this->hid' and aid in(".implode(',',$package_array).")";
		
		$datas = $cdb->select($sql,'*',10000,'addtime desc');
		}
		return $datas;
	}

	public function getwayrate(){
		$datas = getcache('get__currency__lists', 'commons');
		$title="";
		foreach($datas as $v){
			if('JPY'==$v['code']){
				$title=$v['exchangerate'];
			}
		}
		return $title;
	}

	/**返回当前用户包裹颜色 **/
	private function getpackage_color($expressno){

		$expcolor=array();
		$expcolor[0]='';
		$expcolor[1]='#FF6600';
	
		$flag=0;
		$cdb = pc_base::load_model('waybill_goods_model');
		$pdb = pc_base::load_model('package_model');
		$row = $cdb->get_one(array("expressno"=>$expressno, 'returncode'=>''));
		if($row){
			$wrs = $this->db->get_one(array('waybillid'=>trim($row['waybillid']), 'returncode'=>''));
			if($wrs){
				$rs = $pdb->get_one(array("expressno"=>$expressno, 'returncode'=>''));
				if($rs['status']!=1){
					$flag=1;
				}

			}
		}
		

		return $expcolor[$flag];
	}

	
}
?>