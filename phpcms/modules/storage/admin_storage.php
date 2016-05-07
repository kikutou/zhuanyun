<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin','admin',0);

class admin_storage extends admin {

	private $db; public $username;
	public function __construct() {
		parent::__construct();
		//if (!module_exists(ROUTE_M)) showmessage(L('module_not_exists'));
		$this->username = param::get_cookie('admin_username');
		$this->db = pc_base::load_model('storage_model');
	}
	
	public function init() {
		//仓库列表
		$sql = '';

		$sql = '`siteid`=\''.$this->get_siteid().'\'';
		
		$page = max(intval($_GET['page']), 1);
		$data = $this->db->listinfo($sql, 'listorder asc', $page);
		$big_menu = array('javascript:window.top.art.dialog({id:\'add\'}).close();window.top.art.dialog({id:\'add\',iframe:\'?m=storage&c=admin_storage&a=add\', title:\''.L('storage_add').'\', width:\'700\', height:\'500\', lock:true}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'add\'}).close()});void(0);', L('storage_add'));
		include $this->admin_tpl('storage_list');
	}
	
	/**
	 * 添加仓库
	 */
	public function add() {
		$show_validator = 1;
		if(isset($_POST['dosubmit'])) {
			$_POST['storage'] = $this->check($_POST['storage']);
			if(!isset($_POST['storage']['isdefault']))
				$_POST['storage']['isdefault'] = 0;

			if(!isset($_POST['storage']['jihuodian']))
				$_POST['storage']['jihuodian'] = 0;

			if(!isset($_POST['storage']['zhongzhuandian']))
				$_POST['storage']['zhongzhuandian'] = 0;

			if(!isset($_POST['storage']['paifadian']))
				$_POST['storage']['paifadian'] = 0;

			$listorder = intval($_POST['storage']['listorder']);

			if($this->db->insert($_POST['storage'])){
			
				$lastinsertid = $this->db->insert_id();

				/*//添加仓库成功后，加入菜单选项
				//1577 添加
				$menu_db = pc_base::load_model('menu_model');

				$parentid = $menu_db->insert(array('name'=>'house__'.$lastinsertid, 'parentid'=>1577, 'm'=>'house', 'c'=>'admin_house', 'a'=>'init', 'data'=>'hid='.$lastinsertid, 'listorder'=>$listorder, 'display'=>'1'), true);
				
				$pointid1 = $menu_db->insert(array('name'=>'house_jihuo', 'parentid'=>$parentid, 'm'=>'house', 'c'=>'admin_house', 'a'=>'house_jihuo', 'data'=>'hid='.$lastinsertid, 'listorder'=>1, 'display'=>$_POST['storage']['jihuodian']));
				$pointid2 = $menu_db->insert(array('name'=>'house_zhongzhuan', 'parentid'=>$parentid, 'm'=>'house', 'c'=>'admin_house', 'a'=>'house_zhongzhuan', 'data'=>'hid='.$lastinsertid, 'listorder'=>2, 'display'=>$_POST['storage']['zhongzhuandian']));
				$pointid3 = $menu_db->insert(array('name'=>'house_paifa', 'parentid'=>$parentid, 'm'=>'house', 'c'=>'admin_house', 'a'=>'house_paifa', 'data'=>'hid='.$lastinsertid, 'listorder'=>3, 'display'=>$_POST['storage']['paifadian']));
				
				$this->editlanguage($_POST['storage']['title'],'house__'.$lastinsertid);//更新语言
			
				$this->editlanguage($_POST['jihuodian_name'],'house_jihuo__'.$pointid1);//更新语言
				$this->editlanguage($_POST['zhongzhuandian_name'],'house_zhongzhuan__'.$pointid2);//更新语言
				$this->editlanguage($_POST['paifadian_name'],'house_paifa__'.$pointid3);//更新语言
				*/
				
				showmessage(L('storagement_successful_added'), HTTP_REFERER, '', 'add');
			}
		} else {
			//获取站点模板信息
			pc_base::load_app_func('global', 'admin');
			
			$line = $this->getline();
			$defaultcurrency = $this->getdefaultcurrency();
			$currency = $this->getcurrency();

			include $this->admin_tpl('storage_add');
		}
	}
	
	/**
	 * 修改仓库
	 */
	public function edit() {
		$show_validator = 1;
		$_GET['aid'] = intval($_GET['aid']);
		if(!$_GET['aid']) showmessage(L('illegal_operation'));
		if(isset($_POST['dosubmit'])) {
			

			$_POST['storage'] = $this->check($_POST['storage'], 'edit');

			if(!isset($_POST['storage']['isdefault']))
				$_POST['storage']['isdefault'] = 0;

			if(!isset($_POST['storage']['jihuodian']))
				$_POST['storage']['jihuodian'] = 0;

			if(!isset($_POST['storage']['zhongzhuandian']))
				$_POST['storage']['zhongzhuandian'] = 0;

			if(!isset($_POST['storage']['paifadian']))
				$_POST['storage']['paifadian'] = 0;

			$listorder = intval($_POST['storage']['listorder']);
			if($this->db->update($_POST['storage'], array('aid' => $_GET['aid']))){
				$idd = intval($_GET['aid']);
				/*$this->editlanguage($_POST['storage']['title'],'house__'.$idd);//更新语言
				
				//更新仓库点是否显示
				$menu_db = pc_base::load_model('menu_model');

				$menu_db->update(array('listorder'=>$listorder),array('name'=>'house__'.$idd,  'm'=>'house', 'c'=>'admin_house', 'a'=>'init', 'data'=>'hid='.$idd));//更新排序

				 $menu_db->update(array('display'=>$_POST['storage']['jihuodian']),array('name'=>'house_jihuo',  'm'=>'house', 'c'=>'admin_house', 'a'=>'house_jihuo', 'data'=>'hid='.$idd));
				
				$menu_db->update(array('display'=>$_POST['storage']['zhongzhuandian']),array('name'=>'house_zhongzhuan',  'm'=>'house', 'c'=>'admin_house', 'a'=>'house_zhongzhuan', 'data'=>'hid='.$idd));

				 $menu_db->update(array('display'=>$_POST['storage']['paifadian']),array('name'=>'house_paifa', 'm'=>'house', 'c'=>'admin_house', 'a'=>'house_paifa', 'data'=>'hid='.$idd));

				$p1 = $menu_db->get_one(array('name'=>'house_jihuo',  'm'=>'house', 'c'=>'admin_house', 'a'=>'house_jihuo', 'data'=>'hid='.$idd),'id');
				$p2 = $menu_db->get_one(array('name'=>'house_zhongzhuan',  'm'=>'house', 'c'=>'admin_house', 'a'=>'house_zhongzhuan', 'data'=>'hid='.$idd),'id');
				$p3 = $menu_db->get_one(array('name'=>'house_paifa', 'm'=>'house', 'c'=>'admin_house', 'a'=>'house_paifa', 'data'=>'hid='.$idd),'id');

				$pointid1 = $p1['id'];
				$pointid2 = $p2['id'];
				$pointid3 = $p3['id'];

				$this->editlanguage($_POST['jihuodian_name'],'house_jihuo__'.$pointid1);//更新语言
				$this->editlanguage($_POST['zhongzhuandian_name'],'house_zhongzhuan__'.$pointid2);//更新语言
				$this->editlanguage($_POST['paifadian_name'],'house_paifa__'.$pointid3);//更新语言


				*/

				//----------------------------------------------------------------------------------------------modify address begin
				$c_area = pc_base::load_model('linkage_model');
				$addr = pc_base::load_model('address_model');

				$home = $this->db->get_one( array('aid' => $_GET['aid']));

				if($home['xunistorage']==0){
							
						$val = $c_area->get_one(array('linkageid'=>$home['area']),'name');

						$wsql="select m.userid,m.email,m.username,md.lastname,md.firstname,m.clientcode,m.clientname from t_member m LEFT JOIN t_member_detail md ON m.userid=md.userid order by userid asc ";
						$result = $this->db->query($wsql);
						$rownum=1;
						$allusers = $this->db->fetch_array($result);
						foreach($allusers as $num=>$row) {
						$userid = $row['userid'];
						$addrow = $addr->get_one(array('storageid'=>$home['aid'],'userid'=>$userid),'userid');
						
						$addrs=array();
						
						$addrs['truename']=$row['lastname'].$row['firstname'];
						$addrs['country']=$val['name'];
						$addrs['province']=$home['province'];
						$addrs['city']=$home['city'];
						$addrs['company']=$home['company'];
						$addrs['postcode']=$home['zipcode'];
						$addrs['mobile']=$home['phone'];
						$addrs['storageid']=$home['aid'];
						
						if($home['homeplace']==1){//国内地址
							$addrs['address']=$home['address'];
							$addrs['addresstype']=1;
							$addrs['idcardtype']=1;
						}else{
							$addrs['address']=$home['address'].",#".$row['clientcode'];
							$addrs['addresstype']=2;
							$addrs['idcardtype']=2;
						}
						
							if($addrow){
								$addr->update($addrs,array('storageid'=>$home['aid'],'userid'=>$userid));//更新地址
							}else{
								$addrs['email']=$row['email'];
								$addrs['userid']=$userid;
								$addrs['siteid']=1;
								$addrs['addtime'] = SYS_TIME;
								$addrs['username'] = $row['username'];
								$addr->insert($addrs);//插入地址
							}

						}//--user
					}
				//----------------------------------------------------------------------------------------------modify address end



				showmessage(L('storaged_a'), HTTP_REFERER, '', 'edit');
			}

		} else {
			$where = array('aid' => $_GET['aid']);
			$an_info = $this->db->get_one($where);

			$line = $this->getline();
			$defaultcurrency = $this->getdefaultcurrency();
			$currency = $this->getcurrency();

			include $this->admin_tpl('storage_edit');
		}
	}
	
	/**
	 * ajax检测仓库标题是否重复
	 */
	public function public_check_title() {
		if (!$_GET['title']) exit(0);
		if (CHARSET=='gbk') {
			$_GET['title'] = iconv('UTF-8', 'GBK', $_GET['title']);
		}
		$title = $_GET['title'];
		if ($_GET['aid']) {
			$r = $this->db->get_one(array('aid' => $_GET['aid']));
			if ($r['title'] == $title) {
				exit('1');
			}
		} 
		$r = $this->db->get_one(array('siteid' => $this->get_siteid(), 'title' => $title), 'aid');
		if($r['aid']) {
			exit('0');
		} else {
			exit('1');
		}
	}
	
	/**
	 * 批量修改仓库状态 使其成为审核、未审核状态
	 */
	public function public_approval($aid = 0) {
		if((!isset($_POST['aid']) || empty($_POST['aid'])) && !$aid) {
			showmessage(L('illegal_operation'));
		} else {
			if(is_array($_POST['aid']) && !$aid) {
				array_map(array($this, 'public_approval'), $_POST['aid']);
				showmessage(L('storage_passed'), HTTP_REFERER);
			} elseif($aid) {
				$aid = intval($aid);
				
				$this->db->update(array('default' => $_GET['default']), array('aid' => $aid));
				return true;
			}
		}
	}
	
	/**
	 * 批量删除仓库
	 */
	public function delete($aid = 0) {
		if((!isset($_POST['aid']) || empty($_POST['aid'])) && !$aid) {
			showmessage(L('illegal_operation'));
		} else {
			if(is_array($_POST['aid']) && !$aid) {
				array_map(array($this, 'delete'), $_POST['aid']);
				showmessage(L('storage_deleted'), HTTP_REFERER);
			} elseif($aid) {
				$aid = intval($aid);
				$this->db->delete(array('aid' => $aid));

				//删除菜单列表中仓库
				$menu_db = pc_base::load_model('menu_model');
				$r = $menu_db->get_one("name='house__$aid'");
				if($r){
					$menu_db->delete(array('parentid' => $r['id']));
					$menu_db->delete(array('id' => $r['id']));
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
		if($data['title']=='') showmessage(L('title_cannot_empty'));
		
		$r = $this->db->get_one(array('title' => $data['title']));
		
		if ($a=='add') {
			if (is_array($r) && !empty($r)) {
				showmessage(L('storage_exist'), HTTP_REFERER);
			}
			$data['siteid'] = $this->get_siteid();
			$data['addtime'] = SYS_TIME;
			$data['username'] = $this->username;
			
		} else {
			if ($r['aid'] && ($r['aid']!=$_GET['aid'])) {
				showmessage(L('storage_exist'), HTTP_REFERER);
			}
		}
		return $data;
	}


	/**获取所有货币**/
	private function getcurrency(){
		$cdb = pc_base::load_model('currency_model');
		$sql = '`siteid`=\''.$this->get_siteid().'\'';
		$datas = $cdb->select($sql,'*',10000);
		return $datas;
	}


	/**获取所有线路**/
	private function getline(){
		$cdb = pc_base::load_model('linkage_model');
		$sql = ' parentid=0 AND keyid=0 ';
		$datas = $cdb->select($sql,'*',10000,'listorder ASC');
		return $datas;
	}

	/**获取所有默认货币单位**/
	private function getdefaultcurrency(){
		$cdb = pc_base::load_model('currency_model');
		$sql = '`siteid`=\''.$this->get_siteid().'\' AND `isdefault`=1';
		$datas = $cdb->get_one($sql);

		
		return $datas;
	}


	//修改语言
	private function editlanguage($language,$key){
		
		//修改语言文件
		$file = PC_PATH.'languages'.DIRECTORY_SEPARATOR.pc_base::load_config('system', 'lang').DIRECTORY_SEPARATOR.'system_menu.lang.php';
	
		require_once $file;
		if(!isset($LANG[$key])) {
				$content = file_get_contents($file);
				$content = substr($content,0,-2);
				$data = $content."\$LANG['$key'] = '$language';\r\n?>";
				file_put_contents($file,$data);
		} elseif(isset($LANG[$key]) && $LANG[$key]!=$language) {
				$content = file_get_contents($file);
				$content = str_replace($LANG[$key],$language,$content);
				file_put_contents($file,$content);
		}

	}

}
?>