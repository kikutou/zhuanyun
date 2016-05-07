<?php
defined('IN_PHPCMS') or exit('Access Denied');
defined('INSTALL') or exit('Access Denied');

$parentid = $menu_db->insert(array('name'=>'turnway', 'parentid'=>29, 'm'=>'turnway', 'c'=>'admin_turnway', 'a'=>'init', 'data'=>'', 'listorder'=>0, 'display'=>'1'), true);
$menu_db->insert(array('name'=>'turnway_add', 'parentid'=>$parentid, 'm'=>'turnway', 'c'=>'admin_turnway', 'a'=>'add', 'data'=>'', 'listorder'=>0, 'display'=>'0'));
$menu_db->insert(array('name'=>'edit_turnway', 'parentid'=>$parentid, 'm'=>'turnway', 'c'=>'admin_turnway', 'a'=>'edit', 'data'=>'', 'listorder'=>0, 'display'=>'0'));
$menu_db->insert(array('name'=>'del_turnway', 'parentid'=>$parentid, 'm'=>'turnway', 'c'=>'admin_turnway', 'a'=>'delete', 'data'=>'', 'listorder'=>0, 'display'=>'0'));

$language = array('turnway'=>'转运方式', 'turnway_add'=>'添加转运方式', 'edit_turnway'=>'编辑转运方式',  'del_turnway'=>'删除转运方式');
?>