<?php
defined('IN_PHPCMS') or exit('Access Denied');
defined('INSTALL') or exit('Access Denied');

$parentid = $menu_db->insert(array('name'=>'currency', 'parentid'=>29, 'm'=>'currency', 'c'=>'admin_currency', 'a'=>'init', 'data'=>'s=1', 'listorder'=>0, 'display'=>'1'), true);
$menu_db->insert(array('name'=>'currency_add', 'parentid'=>$parentid, 'm'=>'currency', 'c'=>'admin_currency', 'a'=>'add', 'data'=>'', 'listorder'=>0, 'display'=>'0'));
$menu_db->insert(array('name'=>'edit_currency', 'parentid'=>$parentid, 'm'=>'currency', 'c'=>'admin_currency', 'a'=>'edit', 'data'=>'s=1', 'listorder'=>0, 'display'=>'0'));
$menu_db->insert(array('name'=>'del_currency', 'parentid'=>$parentid, 'm'=>'currency', 'c'=>'admin_currency', 'a'=>'delete', 'data'=>'', 'listorder'=>0, 'display'=>'0'));

$language = array('currency'=>'货币', 'currency_add'=>'添加货币', 'edit_currency'=>'编辑货币',  'del_currency'=>'删除货币');
?>