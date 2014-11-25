<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 07/30/2013 10:27
 */

if( ! defined( 'NV_ADMIN' ) ) die( 'Stop!!!' );

$admin_id = $admin_info['admin_id'];

if(defined('NV_IS_GODADMIN')){

	$submenu['config'] = $lang_module['config'];

	$submenu['groups'] = $lang_module['groups'];

	$submenu['permissions'] = $lang_module['permissions'];
}

	$submenu['quanly'] = $lang_module['qlhs'];

	$submenu['user'] = $lang_module['user_manager'];

	$submenu['quanly_tkb'] = $lang_module['quanly_tkb'];

	$submenu['quanly_lichthi'] = $lang_module['quanly_lichthi'];

	if(defined('NV_IS_SPADMIN') or defined('NV_IS_GODADMIN')){
		$submenu['import'] = $lang_module['import'];	
	}


if(defined('NV_IS_GODADMIN')){
	$allow_func = array( 'main', 'groups', 'quanly', 'quanly_nam', 'test', 'addnam','delnam','quanly_mon', 'addmon','delmon','quanly_lop','addlop','dellop','quanly_dsgv','addgv','delgv','addhs','delhs', 'import','importgv','quanly_diem','adddiem','deldiem','quanly_xl','addxl','delxl', 'config', 'khoitaodl','khoitaoxl','change_ac','quanly_tkb','edit_tkb','quanly_lichthi','ltcontent', 'user', 'user_add');
}elseif(defined('NV_IS_SPADMIN')){
	$allow_func = array( 'main',  'quanly', 'quanly_nam', 'test', 'addnam','delnam','quanly_mon', 'addmon','delmon','quanly_lop','addlop','dellop','quanly_dsgv','addgv','delgv','addhs','delhs', 'import','importgv','quanly_diem','adddiem','deldiem','quanly_xl','addxl','delxl', 'khoitaodl','khoitaoxl','change_ac','quanly_tkb','edit_tkb','quanly_lichthi','ltcontent','user', 'user_add');
}else{
	$allow_func = array( 'main', 'quanly', 'quanly_nam', 'test', 'addnam','delnam','quanly_mon', 'addmon','delmon','quanly_lop','addlop','dellop','quanly_dsgv','addgv','delgv','addhs','delhs','quanly_diem','adddiem','deldiem','quanly_xl','addxl','delxl', 'khoitaodl','khoitaoxl','change_ac','quanly_tkb','edit_tkb','quanly_lichthi','ltcontent','user', 'user_add');

}

