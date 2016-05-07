<?php
defined('IN_PHPCMS') or exit('Access Denied');
defined('INSTALL') or exit('Access Denied');

$parentid = $menu_db->insert(array('name'=>'house', 'parentid'=>29, 'm'=>'house', 'c'=>'admin_house', 'a'=>'init', 'data'=>'s=1', 'listorder'=>0, 'display'=>'1'), true);
$menu_db->insert(array('name'=>'house_add', 'parentid'=>$parentid, 'm'=>'house', 'c'=>'admin_house', 'a'=>'add', 'data'=>'', 'listorder'=>0, 'display'=>'0'));
$menu_db->insert(array('name'=>'edit_house', 'parentid'=>$parentid, 'm'=>'house', 'c'=>'admin_house', 'a'=>'edit', 'data'=>'s=1', 'listorder'=>0, 'display'=>'0'));
$menu_db->insert(array('name'=>'del_house', 'parentid'=>$parentid, 'm'=>'house', 'c'=>'admin_house', 'a'=>'delete', 'data'=>'', 'listorder'=>0, 'display'=>'0'));



$language = array('house'=>'仓库配置', 'house_add'=>'添加仓库', 'edit_house'=>'编辑仓库',  'del_house'=>'删除仓库');
?>