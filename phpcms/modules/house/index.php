<?php 
defined('IN_PHPCMS') or exit('No permission resources.');
class index {
	function __construct() {
		$this->db = pc_base::load_model('house_model');
	}
	
	public function init() {
		
	}
	
	
}
?>