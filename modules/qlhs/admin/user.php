<?php

/**
 * @Project NUKEVIET 3.4
 * @Author Nguyễn Thanh Hoàng  (hoang.nguyen@webvang.vn)
 * @Copyright (C) 2014 webvang.vn All rights reserved
 * @Createdate 08/10/2014
 */
if(!defined('NV_IS_FILE_ADMIN'))
{
	die('Stop!!!');
}


$page_title = $lang_module['qlhs'];
if($nv_Request->isset_request('hs','get') OR $nv_Request->isset_request('gv','get') OR $nv_Request->isset_request('gvcn','get') OR $nv_Request->isset_request('gvbm','get') OR $nv_Request->isset_request('add','get')){

	if($nv_Request->isset_request('hs','get')){
		
		$page = $nv_Request->get_int( 'page', 'get', 1 );
		$per_page = 30;
		
		$db->sqlreset()
	->select( 'COUNT(*)' )
	->from( NV_USERS_GLOBALTABLE)
	->from( NV_USERS_GLOBALTABLE.'_info')
	->where( 'WHERE ui.userid=u.userid AND ui.AccType =2' );

		
			$num_items = $db->query( $db->sql() )->fetchColumn();
		
		$db->select( '*' )
			->limit( $per_page )
			->offset( ( $page - 1 ) * $per_page );
		if( ! empty( $orderby ) and in_array( $orderby, $orders ) )
		{
			$db->order( $orderby . ' ' . $ordertype);
			$base_url .= '&amp;sortby=' . $orderby . '&amp;sorttype=' . $ordertype;
		}
		
		$result2 = $db->query( $db->sql() );
		
		$users_list = array();
		$admin_in = array();
		$is_edit = ( in_array( 'edit', $allow_func ) ) ? true : false;
		$is_delete = ( in_array( 'del', $allow_func ) ) ? true : false;
		$is_setactive = ( in_array( 'setactive', $allow_func ) ) ? true : false;
		
		while( $row = $result2->fetch() )
		{
			$users_list[$row['userid']] = array(
				'userid' => ( int )$row['userid'],
				'username' => ( string )$row['username'],
				'full_name' => ( string )$row['full_name'],
				'email' => ( string )$row['email'],
				'regdate' => date( 'd/m/Y H:i', $row['regdate'] ),
				'checked' => ( int )$row['active'] ? ' checked="checked"' : '',
				'disabled' => ( $is_setactive ) ? ' onclick="nv_chang_status(' . $row['userid'] . ');"' : ' disabled="disabled"',
				'is_edit' => $is_edit,
				'is_delete' => $is_delete,
				'level' => $lang_module['level0'],
				'is_admin' => false
			);
			if( $global_config['idsite'] > 0 and $row['idsite'] != $global_config['idsite'] )
			{
				$users_list[$row['userid']]['is_edit'] = false;
				$users_list[$row['userid']]['is_delete'] = false;
			}
			$admin_in[] = $row['userid'];
		}
		
		
		
		
		$xtpl=new XTemplate($op.".tpl",NV_ROOTDIR."/themes/".$global_config['module_theme']."/modules/".$module_file);
		$xtpl->assign('LANG',$lang_module);
		$xtpl->assign('GLANG',$lang_global);
		$xtpl->assign('NV_BASE_SITEURL',NV_BASE_SITEURL);
		$xtpl->assign('MODULE_URL',NV_BASE_ADMINURL."index.php?".NV_NAME_VARIABLE."=".$module_name."&".NV_OP_VARIABLE);
		$xtpl->assign('OP',$op);

		$xtpl->parse('hocsinh');
		$contents=$xtpl->text('hocsinh');
	}


	if($nv_Request->isset_request('gv','get')){
		$xtpl=new XTemplate($op.".tpl",NV_ROOTDIR."/themes/".$global_config['module_theme']."/modules/".$module_file);
		$xtpl->assign('LANG',$lang_module);
		$xtpl->assign('GLANG',$lang_global);
		$xtpl->assign('NV_BASE_SITEURL',NV_BASE_SITEURL);
		$xtpl->assign('MODULE_URL',NV_BASE_ADMINURL."index.php?".NV_NAME_VARIABLE."=".$module_name."&".NV_OP_VARIABLE);
		$xtpl->assign('OP',$op);

		$xtpl->parse('giaovien');
		$contents=$xtpl->text('giaovien');
	}

	if($nv_Request->isset_request('gvcn','get')){
		$xtpl=new XTemplate($op.".tpl",NV_ROOTDIR."/themes/".$global_config['module_theme']."/modules/".$module_file);
		$xtpl->assign('LANG',$lang_module);
		$xtpl->assign('GLANG',$lang_global);
		$xtpl->assign('NV_BASE_SITEURL',NV_BASE_SITEURL);
		$xtpl->assign('MODULE_URL',NV_BASE_ADMINURL."index.php?".NV_NAME_VARIABLE."=".$module_name."&".NV_OP_VARIABLE);
		$xtpl->assign('OP',$op);

		$xtpl->parse('gvcn');
		$contents=$xtpl->text('gvcn');
	}

	if($nv_Request->isset_request('gvbm','get')){
		$xtpl=new XTemplate($op.".tpl",NV_ROOTDIR."/themes/".$global_config['module_theme']."/modules/".$module_file);
		$xtpl->assign('LANG',$lang_module);
		$xtpl->assign('GLANG',$lang_global);
		$xtpl->assign('NV_BASE_SITEURL',NV_BASE_SITEURL);
		$xtpl->assign('MODULE_URL',NV_BASE_ADMINURL."index.php?".NV_NAME_VARIABLE."=".$module_name."&".NV_OP_VARIABLE);
		$xtpl->assign('OP',$op);

		$xtpl->parse('gvbm');
		$contents=$xtpl->text('gvbm');
	}

	if($nv_Request->isset_request('add','get')) {

		$xtpl=new XTemplate($op.".tpl",NV_ROOTDIR."/themes/".$global_config['module_theme']."/modules/".$module_file);
		$xtpl->assign('LANG',$lang_module);
		$xtpl->assign('GLANG',$lang_global);
		$xtpl->assign('NV_BASE_SITEURL',NV_BASE_SITEURL);
		$xtpl->assign('MODULE_URL',NV_BASE_ADMINURL."index.php?".NV_NAME_VARIABLE."=".$module_name."&".NV_OP_VARIABLE);
		$xtpl->assign('OP',$op);
		$error="";
		if( ! empty( $error ) )
		{
			$xtpl->assign( 'ERROR', $error );
			$xtpl->parse( 'add.error' );
		}else{
			$xtpl->parse( 'add.add_user' );
		}
		
		$xtpl->parse('add');
		$contents=$xtpl->text('add');

		
	}
	echo $contents;
}else {
$xtpl=new XTemplate($op.".tpl",NV_ROOTDIR."/themes/".$global_config['module_theme']."/modules/".$module_file);
$xtpl->assign('LANG',$lang_module);
$xtpl->assign('GLANG',$lang_global);
$xtpl->assign('NV_BASE_SITEURL',NV_BASE_SITEURL);
$xtpl->assign('MODULE_URL',NV_BASE_ADMINURL."index.php?".NV_NAME_VARIABLE."=".$module_name."&".NV_OP_VARIABLE);
$xtpl->assign('OP',$op);

$xtpl->parse('main');
$contents=$xtpl->text('main');
include (NV_ROOTDIR . "/includes/header.php");
echo nv_admin_theme($contents);
include (NV_ROOTDIR . "/includes/footer.php");

}


