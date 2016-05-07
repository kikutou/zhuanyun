<?php
defined('IN_PHPCMS') or exit('No permission resources.');
class index {
	function __construct() {
		pc_base::load_app_func('global');
		$this->db = pc_base::load_model('kuaidi_model');
		$siteid = isset($_GET['siteid']) ? intval($_GET['siteid']) : get_siteid();
		$set = getcache('kuaidi', 'commons');
		$this->setting = $set[$siteid];
  		define("SITEID",$siteid);
	}
	
	public function init() {
		$siteid = SITEID;
		$setting = $this->setting;
		$SEO = seo($siteid, '', $setting['meta_title'],$setting['meta_description'],$setting['meta_keywords']);
		$default_style = $setting['template_list'];
		$template = $setting['index_template'] ? $setting['index_template'] : 'index';
		$KUAIDI = getcache('kuaidi_list_'.$siteid,'commons');
		//print_r($default_style);exit;
		include template('kuaidi', $template, $default_style);
	}

	/**
	 * 显示快递
	 */
	public function show() {
		if(!isset($_GET['id'])) {
			showmessage(L('illegal_operation'));
		}
		$setting = $this->setting;
		$parent_style = $setting['template_list'];
		$parent_template = $setting['show_template'] ? $setting['show_template'] : 'show';
		$_GET['id'] = intval($_GET['id']);
		$where = '';
		$where .= "`kdid`='".$_GET['id']."'";
		$where .= " AND `passed`='1' ";
		$r = $this->db->get_one($where);
		if($r['kdid']) {
			//$this->db->update(array('hits'=>'+=1'), array('aid'=>$r['aid']));
			$template = $r['show_template'] ? $r['show_template'] : $parent_template;
			$style = $r['style']? $r['style'] : $parent_style;
			extract($r);
			$SEO = seo(get_siteid(), "", $fullname,"",$name);
			include template('kuaidi', $template, $style);
		} else {
			showmessage(L('no_exists'));	
		}
	}
	
}
?>