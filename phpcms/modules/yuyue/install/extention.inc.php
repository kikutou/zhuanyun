<?php
defined('IN_PHPCMS') or exit('Access Denied');
defined('INSTALL') or exit('Access Denied');

$parentid = $menu_db->insert(array('name'=>'yuyue', 'parentid'=>29, 'm'=>'yuyue', 'c'=>'admin_yuyue', 'a'=>'init', 'data'=>'s=1', 'listorder'=>0, 'display'=>'1'), true);
$menu_db->insert(array('name'=>'yuyue_add', 'parentid'=>$parentid, 'm'=>'yuyue', 'c'=>'admin_yuyue', 'a'=>'add', 'data'=>'', 'listorder'=>0, 'display'=>'0'));
$menu_db->insert(array('name'=>'edit_yuyue', 'parentid'=>$parentid, 'm'=>'yuyue', 'c'=>'admin_yuyue', 'a'=>'edit', 'data'=>'s=1', 'listorder'=>0, 'display'=>'0'));
$menu_db->insert(array('name'=>'del_yuyue', 'parentid'=>$parentid, 'm'=>'yuyue', 'c'=>'admin_yuyue', 'a'=>'delete', 'data'=>'', 'listorder'=>0, 'display'=>'0'));

$language = array('yuyue'=>'预约取件', 'yuyue_add'=>'添加预约', 'edit_yuyue'=>'编辑预约',  'del_yuyue'=>'删除预约');
?>