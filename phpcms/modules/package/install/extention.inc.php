<?php
defined('IN_PHPCMS') or exit('Access Denied');
defined('INSTALL') or exit('Access Denied');

$parentid = $menu_db->insert(array('name'=>'package', 'parentid'=>29, 'm'=>'package', 'c'=>'admin_package', 'a'=>'init', 'data'=>'s=1', 'listorder'=>0, 'display'=>'1'), true);
$menu_db->insert(array('name'=>'package_add', 'parentid'=>$parentid, 'm'=>'package', 'c'=>'admin_package', 'a'=>'add', 'data'=>'', 'listorder'=>0, 'display'=>'0'));
$menu_db->insert(array('name'=>'edit_package', 'parentid'=>$parentid, 'm'=>'package', 'c'=>'admin_package', 'a'=>'edit', 'data'=>'s=1', 'listorder'=>0, 'display'=>'0'));
$menu_db->insert(array('name'=>'del_package', 'parentid'=>$parentid, 'm'=>'package', 'c'=>'admin_package', 'a'=>'delete', 'data'=>'', 'listorder'=>0, 'display'=>'0'));

$language = array('package'=>'包裹', 'package_add'=>'添加包裹', 'edit_package'=>'编辑包裹',  'del_package'=>'删除包裹');
?>