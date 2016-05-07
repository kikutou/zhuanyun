<?php 
/**
 * 
 * 包裹类
 *
 */

defined('IN_PHPCMS') or exit('No permission resources.');

class package_tag {
	private $db;
	
	public function __construct() {
		$this->db = pc_base::load_model('package_model');
	}
	
	/**
	 * 包裹列表方法
	 * @param array $data 传递过来的参数
	 * @param return array 数据库中取出的数据数组
	 */
	public function lists($data) {
		$where = '1';
		$siteid = $data['siteid'] ? intval($data['siteid']) : get_siteid();
		if ($siteid) $where .= " AND `siteid`='".$siteid."'";
		$where .= ' AND `passed`=\'1\' AND (`endtime` >= \''.date('Y-m-d').'\' or `endtime`=\'0000-00-00\')';
		return $this->db->select($where, '*', $data['limit'], 'aid DESC');
	}
	
	public function count() {
		
	}
	
	/**
	 * pc标签初始方法
	 */
	public function pc_tag() {
		//获取站点
		$sites = pc_base::load_app_class('sites','admin');
		$sitelist = $sites->pc_tag_list();
		$result = getcache('special', 'commons');
		if(is_array($result)) {
			$specials = array(L('please_select', '', 'package'));
			foreach($result as $r) {
				if($r['siteid']!=get_siteid()) continue;
				$specials[$r['id']] = $r['title'];
			}
		}
		return array(
			'action'=>array('lists'=>L('lists', '', 'package')),
			'lists'=>array(
				'siteid'=>array('name'=>L('sitename', '', 'package'),'htmltype'=>'input_select', 'defaultvalue'=>get_siteid(), 'data'=>$sitelist),
			),
		);
	}
}
?>