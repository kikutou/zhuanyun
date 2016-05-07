<?php 
defined('IN_PHPCMS') or exit('No permission resources.');
class index {
	function __construct() {
		$this->db = pc_base::load_model('currency_model');
	}
	
	public function init() {
		
	}
	
	/**
	 * 展示货币
	 */
	public function show() {
		if(!isset($_GET['aid'])) {
			showmessage(L('illegal_operation'));
		}
		$_GET['aid'] = intval($_GET['aid']);
		$where = '';
		$where .= "`aid`='".$_GET['aid']."'";
		$r = $this->db->get_one($where);
		if($r['aid']) {
			$this->db->update( array('aid'=>$r['aid']));
			$template = $r['show_template'] ? $r['show_template'] : 'show';
			extract($r);
			$SEO = seo(get_siteid(), '', $title);
			include template('currency', $template, $r['style']);
		} else {
			showmessage(L('no_exists'));	
		}
	}
}
?>