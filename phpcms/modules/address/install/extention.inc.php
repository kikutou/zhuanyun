<?php
defined('IN_PHPCMS') or exit('Access Denied');
defined('INSTALL') or exit('Access Denied');

$parentid = $menu_db->insert(array('name'=>'address', 'parentid'=>29, 'm'=>'address', 'c'=>'admin_address', 'a'=>'init', 'data'=>'s=1', 'listorder'=>0, 'display'=>'1'), true);
$menu_db->insert(array('name'=>'address_add', 'parentid'=>$parentid, 'm'=>'address', 'c'=>'admin_address', 'a'=>'add', 'data'=>'', 'listorder'=>0, 'display'=>'0'));
$menu_db->insert(array('name'=>'edit_address', 'parentid'=>$parentid, 'm'=>'address', 'c'=>'admin_address', 'a'=>'edit', 'data'=>'s=1', 'listorder'=>0, 'display'=>'0'));
$menu_db->insert(array('name'=>'del_address', 'parentid'=>$parentid, 'm'=>'address', 'c'=>'admin_address', 'a'=>'delete', 'data'=>'', 'listorder'=>0, 'display'=>'0'));

$language = array('address'=>'地址簿', 'address_add'=>'添加地址簿', 'edit_address'=>'编辑地址簿',  'del_address'=>'删除地址簿');
?>