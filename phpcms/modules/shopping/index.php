<?php
defined('IN_PHPCMS') or exit('No permission resources.');
class index {
	function __construct() {
		pc_base::load_app_func('global');
		$siteid = isset($_GET['siteid']) ? intval($_GET['siteid']) : get_siteid();
  		define("SITEID",$siteid);
	}
	
	public function init() {
		$siteid = SITEID;
 		$setting = getcache('shopping', 'commons');
		$SEO = seo(SITEID, '', L('shopping'), '', '');
		include template('shopping', 'index');
	}
	
	 /**
	 *	购物指南列表页
	 */
	public function list_type() {
		$siteid = SITEID;
  		$type_id = trim(urldecode($_GET['type_id']));
		$type_id = intval($type_id);
  		if($type_id==""){
 			$type_id ='0';
 		}
   		$setting = getcache('shopping', 'commons');
		$SEO = seo(SITEID, '', L('shopping'), '', '');
  		include template('shopping', 'list_type');
	} 
 	
	 /**
	 *	申请购物指南 
	 */
	public function register() { 
 		$siteid = SITEID;
 		if(isset($_POST['dosubmit'])){
 			if($_POST['name']==""){
 				showmessage(L('sitename_noempty'),"?m=shopping&c=index&a=register&siteid=$siteid");
 			}
 			if($_POST['url']==""){
 				showmessage(L('siteurl_not_empty'),"?m=shopping&c=index&a=register&siteid=$siteid");
 			}
 			if(!in_array($_POST['shoppingtype'],array('0','1'))){
 				$_POST['shoppingtype'] = '0';
 			}
 			$shopping_db = pc_base::load_model(shopping_model);
 			$_POST['logo'] =new_html_special_chars($_POST['logo']);

			$logo = safe_replace(strip_tags($_POST['logo']));
			$name = safe_replace(strip_tags($_POST['name']));
			$url = safe_replace(strip_tags($_POST['url']));
			$url = trim_script($url);
 			if($_POST['shoppingtype']=='0'){
 				$sql = array('siteid'=>$siteid,'typeid'=>intval($_POST['typeid']),'shoppingtype'=>intval($_POST['shoppingtype']),'name'=>$name,'url'=>$url);
 			}else{
 				$sql = array('siteid'=>$siteid,'typeid'=>intval($_POST['typeid']),'shoppingtype'=>intval($_POST['shoppingtype']),'name'=>$name,'url'=>$url,'logo'=>$logo);
 			}
 			$shopping_db->insert($sql);
 			showmessage(L('add_success'), "?m=shopping&c=index&siteid=$siteid");
 		} else {
  			$setting = getcache('shopping', 'commons');
			$setting = $setting[$siteid];
 			if($setting['is_post']=='0'){
 				showmessage(L('suspend_application'), HTTP_REFERER);
 			}
 			$this->type = pc_base::load_model('type_model');
 			$types = $this->type->get_types($siteid);//获取站点下所有购物指南分类
 			pc_base::load_sys_class('form', '', 0);
  			$SEO = seo(SITEID, '', L('application_shoppings'), '', '');
   			include template('shopping', 'register');
 		}
	} 
	
}
?>