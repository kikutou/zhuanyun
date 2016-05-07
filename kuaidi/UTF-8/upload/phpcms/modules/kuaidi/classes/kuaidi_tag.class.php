<?php
defined('IN_PHPCMS') or exit('No permission resources.');
class kuaidi_tag {
 	private $kuaidi_db,$type_db;
	
	public function __construct() {
		$this->kuaidi_db = pc_base::load_model('kuaidi_model');
		$this->type_db = pc_base::load_model('type_model');
 	}
	
 	/**
 	 * 取出该分类的详细 信息
  	 * @param $typeid 分类ID 
 	 */
 	  
 	public function get_type($data){
 		$typeid = intval($data['typeid']);
 		if($typeid=='0'){
 			$arr = array();
 			$arr['name'] = '默认分类';
 			return $arr;
 		}else {
		$r = $this->type_db->get_one(array('typeid'=>$typeid));
  		return new_html_special_chars($r);	
 		}
 	}
 	
	/**
	 * 快递列表
	 * @param  $data 
	 */
	public function lists($data) {
		$typeid = intval($data['typeid']);//分类ID
		$siteid = $data['siteid'];
		if (empty($siteid)){ 
			$siteid = get_siteid();
		}
  		if($typeid!='' || $typeid=='0'){
 			$sql = array('typeid'=>$typeid,'siteid'=>$siteid,'passed'=>'1');
		}else {
			$sql = array('siteid'=>$siteid,'passed'=>'1');
		}

  		$r = $this->kuaidi_db->select($sql, '*', $data['limit'], $data['order'], '', 'kdid');
		return new_html_special_chars($r);
	}
	
	public function count(){
		
	}

}