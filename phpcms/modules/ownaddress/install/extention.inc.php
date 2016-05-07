<?php
defined('IN_PHPCMS') or exit('Access Denied');
defined('INSTALL') or exit('Access Denied');

$parentid = $menu_db->insert(array('name'=>'ownaddress', 'parentid'=>29, 'm'=>'ownaddress', 'c'=>'admin_ownaddress', 'a'=>'init', 'data'=>'s=1', 'listorder'=>0, 'display'=>'1'), true);
$menu_db->insert(array('name'=>'ownaddress_add', 'parentid'=>$parentid, 'm'=>'ownaddress', 'c'=>'admin_ownaddress', 'a'=>'add', 'data'=>'', 'listorder'=>0, 'display'=>'0'));
$menu_db->insert(array('name'=>'edit_ownaddress', 'parentid'=>$parentid, 'm'=>'ownaddress', 'c'=>'admin_ownaddress', 'a'=>'edit', 'data'=>'s=1', 'listorder'=>0, 'display'=>'0'));
$menu_db->insert(array('name'=>'del_ownaddress', 'parentid'=>$parentid, 'm'=>'ownaddress', 'c'=>'admin_ownaddress', 'a'=>'delete', 'data'=>'', 'listorder'=>0, 'display'=>'0'));

$language = array('ownaddress'=>'私人地址簿', 'ownaddress_add'=>'添加私人地址簿', 'edit_ownaddress'=>'编辑私人地址簿',  'del_ownaddress'=>'删除私人地址簿');
?>