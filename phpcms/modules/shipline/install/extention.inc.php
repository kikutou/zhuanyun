<?php
defined('IN_PHPCMS') or exit('Access Denied');
defined('INSTALL') or exit('Access Denied');

$parentid = $menu_db->insert(array('name'=>'shipline', 'parentid'=>29, 'm'=>'shipline', 'c'=>'admin_shipline', 'a'=>'init', 'data'=>'s=1', 'listorder'=>0, 'display'=>'1'), true);
$menu_db->insert(array('name'=>'shipline_add', 'parentid'=>$parentid, 'm'=>'shipline', 'c'=>'admin_shipline', 'a'=>'add', 'data'=>'', 'listorder'=>0, 'display'=>'0'));
$menu_db->insert(array('name'=>'edit_shipline', 'parentid'=>$parentid, 'm'=>'shipline', 'c'=>'admin_shipline', 'a'=>'edit', 'data'=>'s=1', 'listorder'=>0, 'display'=>'0'));
$menu_db->insert(array('name'=>'del_shipline', 'parentid'=>$parentid, 'm'=>'shipline', 'c'=>'admin_shipline', 'a'=>'delete', 'data'=>'', 'listorder'=>0, 'display'=>'0'));

$language = array('shipline'=>'线路', 'shipline_add'=>'添加线路', 'edit_shipline'=>'编辑线路',  'del_shipline'=>'删除线路');
?>