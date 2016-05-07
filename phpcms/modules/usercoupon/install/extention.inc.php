<?php
defined('IN_PHPCMS') or exit('Access Denied');
defined('INSTALL') or exit('Access Denied');

$parentid = $menu_db->insert(array('name'=>'usercoupon', 'parentid'=>29, 'm'=>'usercoupon', 'c'=>'admin_usercoupon', 'a'=>'init', 'data'=>'s=1', 'listorder'=>0, 'display'=>'1'), true);
$menu_db->insert(array('name'=>'usercoupon_add', 'parentid'=>$parentid, 'm'=>'usercoupon', 'c'=>'admin_usercoupon', 'a'=>'add', 'data'=>'', 'listorder'=>0, 'display'=>'0'));
$menu_db->insert(array('name'=>'edit_usercoupon', 'parentid'=>$parentid, 'm'=>'usercoupon', 'c'=>'admin_usercoupon', 'a'=>'edit', 'data'=>'s=1', 'listorder'=>0, 'display'=>'0'));
$menu_db->insert(array('name'=>'del_usercoupon', 'parentid'=>$parentid, 'm'=>'usercoupon', 'c'=>'admin_usercoupon', 'a'=>'delete', 'data'=>'', 'listorder'=>0, 'display'=>'0'));

$language = array('usercoupon'=>'用户优惠券', 'usercoupon_add'=>'发放用户优惠券', 'edit_usercoupon'=>'编辑用户优惠券',  'del_usercoupon'=>'删除用户优惠券');
?>