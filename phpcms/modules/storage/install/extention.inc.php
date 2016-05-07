<?php
defined('IN_PHPCMS') or exit('Access Denied');
defined('INSTALL') or exit('Access Denied');

$parentid = $menu_db->insert(array('name'=>'storage', 'parentid'=>29, 'm'=>'storage', 'c'=>'admin_storage', 'a'=>'init', 'data'=>'s=1', 'listorder'=>0, 'display'=>'1'), true);
$menu_db->insert(array('name'=>'storage_add', 'parentid'=>$parentid, 'm'=>'storage', 'c'=>'admin_storage', 'a'=>'add', 'data'=>'', 'listorder'=>0, 'display'=>'0'));
$menu_db->insert(array('name'=>'edit_storage', 'parentid'=>$parentid, 'm'=>'storage', 'c'=>'admin_storage', 'a'=>'edit', 'data'=>'s=1', 'listorder'=>0, 'display'=>'0'));
$menu_db->insert(array('name'=>'del_storage', 'parentid'=>$parentid, 'm'=>'storage', 'c'=>'admin_storage', 'a'=>'delete', 'data'=>'', 'listorder'=>0, 'display'=>'0'));

$language = array('storage'=>'仓库', 'storage_add'=>'添加仓库', 'edit_storage'=>'编辑仓库',  'del_storage'=>'删除仓库');
?>