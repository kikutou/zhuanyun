<?php
defined('IN_PHPCMS') or exit('Access Denied');
defined('INSTALL') or exit('Access Denied');
$parentid = $menu_db->insert(array('name'=>'shopping', 'parentid'=>29, 'm'=>'shopping', 'c'=>'shopping', 'a'=>'init', 'data'=>'', 'listorder'=>0, 'display'=>'1'), true);
$menu_db->insert(array('name'=>'add_shopping', 'parentid'=>$parentid, 'm'=>'shopping', 'c'=>'shopping', 'a'=>'add', 'data'=>'', 'listorder'=>0, 'display'=>'0'));
$menu_db->insert(array('name'=>'edit_shopping', 'parentid'=>$parentid, 'm'=>'shopping', 'c'=>'shopping', 'a'=>'edit', 'data'=>'', 'listorder'=>0, 'display'=>'0'));
$menu_db->insert(array('name'=>'delete_shopping', 'parentid'=>$parentid, 'm'=>'shopping', 'c'=>'shopping', 'a'=>'delete', 'data'=>'', 'listorder'=>0, 'display'=>'0'));
$menu_db->insert(array('name'=>'shopping_setting', 'parentid'=>$parentid, 'm'=>'shopping', 'c'=>'shopping', 'a'=>'setting', 'data'=>'', 'listorder'=>0, 'display'=>'1'));
$menu_db->insert(array('name'=>'add_type', 'parentid'=>$parentid, 'm'=>'shopping', 'c'=>'shopping', 'a'=>'add_type', 'data'=>'', 'listorder'=>0, 'display'=>'1'));
$menu_db->insert(array('name'=>'list_type', 'parentid'=>$parentid, 'm'=>'shopping', 'c'=>'shopping', 'a'=>'list_type', 'data'=>'', 'listorder'=>0, 'display'=>'1'));
$menu_db->insert(array('name'=>'check_register', 'parentid'=>$parentid, 'm'=>'shopping', 'c'=>'shopping', 'a'=>'check_register', 'data'=>'', 'listorder'=>0, 'display'=>'1'));

$shopping_db = pc_base::load_model('shopping_model');


$language = array('shopping'=>'购物指南', 'add_shopping'=>'添加购物指南', 'edit_shopping'=>'编辑购物指南', 'delete_shopping'=>'删除购物指南', 'shopping_setting'=>'模块配置', 'add_type'=>'添加类别', 'list_type'=>'分类管理', 'check_register'=>'审核申请');
?>