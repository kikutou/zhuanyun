<?php
defined('IN_PHPCMS') or exit('Access Denied');
defined('INSTALL') or exit('Access Denied');

$parentid = $menu_db->insert(array('name'=>'service', 'parentid'=>29, 'm'=>'service', 'c'=>'admin_service', 'a'=>'init', 'data'=>'s=1', 'listorder'=>0, 'display'=>'1'), true);
$menu_db->insert(array('name'=>'service_add', 'parentid'=>$parentid, 'm'=>'service', 'c'=>'admin_service', 'a'=>'add', 'data'=>'', 'listorder'=>0, 'display'=>'0'));
$menu_db->insert(array('name'=>'edit_service', 'parentid'=>$parentid, 'm'=>'service', 'c'=>'admin_service', 'a'=>'edit', 'data'=>'s=1', 'listorder'=>0, 'display'=>'0'));
$menu_db->insert(array('name'=>'del_service', 'parentid'=>$parentid, 'm'=>'service', 'c'=>'admin_service', 'a'=>'delete', 'data'=>'', 'listorder'=>0, 'display'=>'0'));

$language = array('service'=>'增值服务', 'service_add'=>'添加服务', 'edit_service'=>'编辑服务',  'del_service'=>'删除服务');
?>