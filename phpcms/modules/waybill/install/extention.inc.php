<?php
defined('IN_PHPCMS') or exit('Access Denied');
defined('INSTALL') or exit('Access Denied');

$parentid = $menu_db->insert(array('name'=>'waybill', 'parentid'=>29, 'm'=>'waybill', 'c'=>'admin_waybill', 'a'=>'init', 'data'=>'s=1', 'listorder'=>0, 'display'=>'1'), true);
$menu_db->insert(array('name'=>'waybill_add', 'parentid'=>$parentid, 'm'=>'waybill', 'c'=>'admin_waybill', 'a'=>'add', 'data'=>'', 'listorder'=>0, 'display'=>'0'));
$menu_db->insert(array('name'=>'edit_waybill', 'parentid'=>$parentid, 'm'=>'waybill', 'c'=>'admin_waybill', 'a'=>'edit', 'data'=>'s=1', 'listorder'=>0, 'display'=>'0'));
$menu_db->insert(array('name'=>'del_waybill', 'parentid'=>$parentid, 'm'=>'waybill', 'c'=>'admin_waybill', 'a'=>'delete', 'data'=>'', 'listorder'=>0, 'display'=>'0'));

$language = array('waybill'=>'运单', 'waybill_add'=>'添加运单', 'edit_waybill'=>'编辑运单',  'del_waybill'=>'删除运单');
?>