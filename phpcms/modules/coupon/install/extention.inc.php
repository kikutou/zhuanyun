<?php
defined('IN_PHPCMS') or exit('Access Denied');
defined('INSTALL') or exit('Access Denied');

$parentid = $menu_db->insert(array('name'=>'coupon', 'parentid'=>29, 'm'=>'coupon', 'c'=>'admin_coupon', 'a'=>'init', 'data'=>'s=1', 'listorder'=>0, 'display'=>'1'), true);
$menu_db->insert(array('name'=>'coupon_add', 'parentid'=>$parentid, 'm'=>'coupon', 'c'=>'admin_coupon', 'a'=>'add', 'data'=>'', 'listorder'=>0, 'display'=>'0'));
$menu_db->insert(array('name'=>'edit_coupon', 'parentid'=>$parentid, 'm'=>'coupon', 'c'=>'admin_coupon', 'a'=>'edit', 'data'=>'s=1', 'listorder'=>0, 'display'=>'0'));
$menu_db->insert(array('name'=>'del_coupon', 'parentid'=>$parentid, 'm'=>'coupon', 'c'=>'admin_coupon', 'a'=>'delete', 'data'=>'', 'listorder'=>0, 'display'=>'0'));

$language = array('coupon'=>'优惠券', 'coupon_add'=>'添加优惠券', 'edit_coupon'=>'编辑优惠券',  'del_coupon'=>'删除优惠券');
?>