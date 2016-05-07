<?php
defined('IN_PHPCMS') or exit('Access Denied');
defined('INSTALL') or exit('Access Denied');

$parentid = $menu_db->insert(array('name'=>'enum', 'parentid'=>29, 'm'=>'enum', 'c'=>'admin_enum', 'a'=>'init', 'data'=>'s=1', 'listorder'=>0, 'display'=>'1'), true);
$menu_db->insert(array('name'=>'enum_add', 'parentid'=>$parentid, 'm'=>'enum', 'c'=>'admin_enum', 'a'=>'add', 'data'=>'', 'listorder'=>0, 'display'=>'0'));
$menu_db->insert(array('name'=>'edit_enum', 'parentid'=>$parentid, 'm'=>'enum', 'c'=>'admin_enum', 'a'=>'edit', 'data'=>'s=1', 'listorder'=>0, 'display'=>'0'));
$menu_db->insert(array('name'=>'del_enum', 'parentid'=>$parentid, 'm'=>'enum', 'c'=>'admin_enum', 'a'=>'delete', 'data'=>'', 'listorder'=>0, 'display'=>'0'));

$enum_db = pc_base::load_model('enum_model');

$enum_db->insert(array('siteid'=>1,'groupid'=>0,'title'=>'货运公司','value'=>'','username'=>'sunyl','addtime'=>SYS_TIME,'issystem'=>1,'listorder'=>0)); 

$enum_db->insert(array('siteid'=>1,'groupid'=>0,'title'=>'重量单位','value'=>'','username'=>'sunyl','addtime'=>SYS_TIME,'issystem'=>1,'listorder'=>0));

$enum_db->insert(array('siteid'=>1,'groupid'=>0,'title'=>'线路附加项','value'=>'','username'=>'sunyl','addtime'=>SYS_TIME,'issystem'=>1,'listorder'=>0));

$language = array('enum'=>'字典', 'enum_add'=>'添加字典', 'edit_enum'=>'编辑字典',  'del_enum'=>'删除字典');
?>