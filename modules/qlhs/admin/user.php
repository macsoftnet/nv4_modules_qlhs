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


$page_title =  $lang_module['qlhs'];
if($nv_Request->isset_request('hs','get') OR $nv_Request->isset_request('gv','get') OR $nv_Request->isset_request('gvcn','get') OR $nv_Request->isset_request('gvbm','get') OR $nv_Request->isset_request('add','get')){
	
	//$array_hocsinh= $db->query(  $sql );
	$table_caption = $lang_module['dshs'];
	$usactive_old = $nv_Request->get_int( 'usactive', 'cookie', 3 );
	$usactive = $nv_Request->get_int( 'usactive', 'post,get', $usactive_old );
	if( $usactive_old != $usactive )
	{
		$nv_Request->set_Cookie( 'usactive', $usactive );
	}
	$_where = 'u.userid=ui.userid AND ui.AccType=2 AND u.active=' . ( $usactive % 2 );
	if( $usactive > 1 )
	{
		$_where .= ' AND (u.idsite=' . $global_config['idsite'] .' OR u.userid = ' . $admin_info['admin_id'] . ')';
	}
	
	$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&usactive=' . $usactive;
	
	$methods = array(
		'username' => array(
			'key' => 'username',
			'value' => $lang_module['search_accounths'],
			'selected' => ''
		),
		'full_name' => array(
			'key' => 'full_name',
			'value' => $lang_module['search_namehs'],
			'selected' => ''
		)
	);
	
	$method = $nv_Request->isset_request( 'method', 'post' ) ? $nv_Request->get_string( 'method', 'post', '' ) : ( $nv_Request->isset_request( 'method', 'get' ) ? urldecode( $nv_Request->get_string( 'method', 'get', '' ) ) : '' );
	$methodvalue = $nv_Request->isset_request( 'value', 'post' ) ? $nv_Request->get_string( 'value', 'post' ) : ( $nv_Request->isset_request( 'value', 'get' ) ? urldecode( $nv_Request->get_string( 'value', 'get', '' ) ) : '' );
	
	$orders = array( 'userid', 'username', 'full_name', 'email', 'regdate' );
	$orderby = $nv_Request->get_string( 'sortby', 'get', 'userid' );
	$ordertype = $nv_Request->get_string( 'sorttype', 'get', 'DESC' );
	if( $ordertype != 'ASC' ) $ordertype = 'DESC';
	$method = ( ! empty( $method ) and isset( $methods[$method] ) ) ? $method : '';
	
	if( ! empty( $methodvalue ) )
	{
		if( empty( $method ) )
		{
			$key_methods = array_keys( $methods );
			$array_like = array();
			foreach( $key_methods as $method_i )
			{
				$array_like[] = $method_i . " LIKE '%" . $db->dblikeescape( $methodvalue ) . "%'";
			}
			$_where .= ' AND (' . implode( ' OR ', $array_like ) . ')';
		}
		else
		{
			$_where .= " AND (" . $method . " LIKE '%" . $db->dblikeescape( $methodvalue ) . "%')";
			$methods[$method]['selected'] = ' selected="selected"';
		}
		$base_url .= '&amp;method=' . urlencode( $method ) . '&amp;value=' . urlencode( $methodvalue );
		$table_caption = $lang_module['dshs'];
	}
	
	$page = $nv_Request->get_int( 'page', 'get', 1 );
	$per_page = 30;
	
	$sql = 'SELECT COUNT(*) FROM ' . NV_USERS_GLOBALTABLE . ' u, ' . NV_USERS_GLOBALTABLE . '_info ui WHERE ' . $_where . '';
	
	$num_items = $db->query( $sql )->fetchColumn();
	
	if( ! empty( $orderby ) and in_array( $orderby, $orders ) )
	{
	$_where .= " ORDER BY u.". $orderby . " " . $ordertype;
	
	}
	
	$sql = 'SELECT * FROM ' . NV_USERS_GLOBALTABLE . ' u, ' . NV_USERS_GLOBALTABLE . '_info ui WHERE ' . $_where . ' LIMIT 0,30';
	$sqldanhsach=$sql;
	$result2 = $db->query( $sql );
	
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
	
	if( ! empty( $admin_in ) )
	{
		$admin_in = implode( ',', $admin_in );
		$sql = 'SELECT admin_id, lev FROM ' . NV_AUTHORS_GLOBALTABLE . ' WHERE admin_id IN (' . $admin_in . ')';
		$query = $db->query( $sql );
		while( $row = $query->fetch() )
		{
			$users_list[$row['admin_id']]['is_delete'] = false;
			if( $row['lev'] == 1 )
			{
				$users_list[$row['admin_id']]['level'] = $lang_global['level1'];
				$users_list[$row['admin_id']]['img'] = 'admin1';
			}
			elseif( $row['lev'] == 2 )
			{
				$users_list[$row['admin_id']]['level'] = $lang_global['level2'];
				$users_list[$row['admin_id']]['img'] = 'admin2';
			}
			else
			{
				$users_list[$row['admin_id']]['level'] = $lang_global['level3'];
				$users_list[$row['admin_id']]['img'] = 'admin3';
			}
	
			$users_list[$row['admin_id']]['is_admin'] = true;
			if( $users_list[$row['admin_id']]['is_edit'] )
			{
				if( defined( 'NV_IS_GODADMIN' ) )
				{
					$users_list[$row['admin_id']]['is_edit'] = true;
				}
				elseif( defined( 'NV_IS_SPADMIN' ) and ! ( $row['lev'] == 1 or $row['lev'] == 2 ) )
				{
					$users_list[$row['admin_id']]['is_edit'] = true;
				}
				else
				{
					$users_list[$row['admin_id']]['is_edit'] = false;
				}
			}
			if( ! $users_list[$row['admin_id']]['is_edit'] )
			{
				$users_list[$row['admin_id']]['disabled'] = ' disabled="disabled"';
			}
		}
		if( isset( $users_list[$admin_info['admin_id']] ) )
		{
			$users_list[$admin_info['admin_id']]['disabled'] = ' disabled="disabled"';
			$users_list[$admin_info['admin_id']]['is_edit'] = true;
		}
	}
	
	$generate_page = nv_generate_page( $base_url, $num_items, $per_page, $page );

	$head_tds = array();
	$head_tds['userid']['title'] = $lang_module['userid'];
	$head_tds['userid']['ordertype'] = 'userid';
	$head_tds['userid']['href'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=user&amp;hs'. '&amp;sortby=userid&amp;sorttype=ASC';
	$head_tds['username']['title'] = $lang_module['account'];
	$head_tds['username']['ordertype'] = 'username';
	$head_tds['username']['href'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=user&amp;hs'. '&amp;sortby=username&amp;sorttype=ASC';
	$head_tds['full_name']['title'] = $lang_module['name'];
	$head_tds['full_name']['ordertype'] = 'full_name';
	$head_tds['full_name']['href'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=user&amp;hs'. '&amp;sortby=full_name&amp;sorttype=ASC';
	$head_tds['email']['title'] = $lang_module['email'];
	$head_tds['email']['ordertype'] = 'email';
	$head_tds['email']['href'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=user&amp;hs'. '&amp;sortby=email&amp;sorttype=ASC';
	$head_tds['regdate']['title'] = $lang_module['register_date'];
	$head_tds['regdate']['ordertype'] = 'regdate';
	$head_tds['regdate']['href'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=user&amp;hs'. '&amp;sortby=regdate&amp;sorttype=ASC';
	
	foreach( $orders as $order )
	{
		if( $orderby == $order and $ordertype == 'ASC' )
		{
			$head_tds[$order]['href'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=user&amp;hs'. '&amp;sortby=' . $order . '&amp;sorttype=DESC';
			$head_tds[$order]['title'] .= ' &darr;';
		}
		elseif( $orderby == $order and $ordertype == 'DESC' )
		{
			$head_tds[$order]['href'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name .'&amp;' . NV_OP_VARIABLE . '=user&amp;hs'. '&amp;sortby=' . $order . '&amp;sorttype=ASC';
			$head_tds[$order]['title'] .= ' &uarr;';
		}
	}
	if($nv_Request->isset_request('hs','get')){
		$xtpl=new XTemplate($op.".tpl",NV_ROOTDIR."/themes/".$global_config['module_theme']."/modules/".$module_file);
		$xtpl->assign('LANG',$lang_module);
		$xtpl->assign('GLANG',$lang_global);
		
		$xtpl->assign('NV_BASE_SITEURL',NV_BASE_SITEURL);
		$xtpl->assign('MODULE_URL',NV_BASE_ADMINURL."index.php?".NV_NAME_VARIABLE."=".$module_name."&".NV_OP_VARIABLE);
		$xtpl->assign('OP',$op);
		$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
		$xtpl->assign( 'MODULE_NAME', $module_name );
		$xtpl->assign( 'SORTURL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name );
		$xtpl->assign( 'TABLE_CAPTION', $table_caption );
		
		
		foreach( $head_tds as $head_td )
		{
			$xtpl->assign( 'HEAD_TD', $head_td );
			$xtpl->parse( 'hocsinh.head_td' );
		}
		
		foreach( $users_list as $u )
		{
			$xtpl->assign( 'CONTENT_TD', $u );
			$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
			$xtpl->assign( 'NV_ADMIN_THEME', $global_config['admin_theme'] );
			if( $u['is_admin'] )
			{
				$xtpl->parse( 'hocsinh.xusers.is_admin' );
			}
		
			if( ! defined( 'NV_IS_USER_FORUM' ) )
			{
				if( $u['is_edit'] )
				{
					$xtpl->assign( 'EDIT_URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=edit&amp;userid=' . $u['userid'] );
					$xtpl->parse( 'hocsinh.xusers.edit' );
				}
				if( $u['is_delete'] )
				{
					$xtpl->parse( 'hocsinh.xusers.del' );
				}
			}
		
			$xtpl->parse( 'hocsinh.xusers' );
		}
		
		if( ! empty( $generate_page ) )
		{
			$xtpl->assign( 'GENERATE_PAGE', $generate_page );
			$xtpl->parse( 'hocsinh.generate_page' );
		}
		
		if( defined( 'NV_IS_GODADMIN' ) )
		{
			$xtpl->parse( 'hocsinh.exportfile' );
		}
		$xtpl->parse('hocsinh');
		$contents=$xtpl->text('hocsinh').$sqldanhsachorder;
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

	
	echo $contents;
}else {
	
	$usactive_old = $nv_Request->get_int( 'usactive', 'cookie', 3 );
	$usactive = $nv_Request->get_int( 'usactive', 'post,get', $usactive_old );
	if( $usactive_old != $usactive )
	{
		$nv_Request->set_Cookie( 'usactive', $usactive );
	}
	$_where = 'u.userid=ui.userid AND ui.AccType=2 AND u.active=' . ( $usactive % 2 );
	if( $usactive > 1 )
	{
		$_where .= ' AND (u.idsite=' . $global_config['idsite'] .' OR u.userid = ' . $admin_info['admin_id'] . ')';
	}
	
	$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&usactive=' . $usactive;
	
	$methods = array(
		'username' => array(
			'key' => 'username',
			'value' => $lang_module['search_accounths'],
			'selected' => ''
		),
		'full_name' => array(
			'key' => 'full_name',
			'value' => $lang_module['search_namehs'],
			'selected' => ''
		)
	);
	
	$method = $nv_Request->isset_request( 'method', 'post' ) ? $nv_Request->get_string( 'method', 'post', '' ) : ( $nv_Request->isset_request( 'method', 'get' ) ? urldecode( $nv_Request->get_string( 'method', 'get', '' ) ) : '' );
	$methodvalue = $nv_Request->isset_request( 'value', 'post' ) ? $nv_Request->get_string( 'value', 'post' ) : ( $nv_Request->isset_request( 'value', 'get' ) ? urldecode( $nv_Request->get_string( 'value', 'get', '' ) ) : '' );
	
	$orders = array( 'userid', 'username', 'full_name', 'email', 'regdate' );
	$orderby = $nv_Request->get_string( 'sortby', 'get', 'userid' );
	$ordertype = $nv_Request->get_string( 'sorttype', 'get', 'DESC' );
	if( $ordertype != 'ASC' ) $ordertype = 'DESC';
	$method = ( ! empty( $method ) and isset( $methods[$method] ) ) ? $method : '';
	
	if( ! empty( $methodvalue ) )
	{
		if( empty( $method ) )
		{
			$key_methods = array_keys( $methods );
			$array_like = array();
			foreach( $key_methods as $method_i )
			{
				$array_like[] = $method_i . " LIKE '%" . $db->dblikeescape( $methodvalue ) . "%'";
			}
			$_where .= ' AND (' . implode( ' OR ', $array_like ) . ')';
		}
		else
		{
			$_where .= " AND (" . $method . " LIKE '%" . $db->dblikeescape( $methodvalue ) . "%')";
			$methods[$method]['selected'] = ' selected="selected"';
		}
		$base_url .= '&amp;method=' . urlencode( $method ) . '&amp;value=' . urlencode( $methodvalue );
		$table_caption = $lang_module['search_page_title'];
	}
	$xtpl=new XTemplate($op.".tpl",NV_ROOTDIR."/themes/".$global_config['module_theme']."/modules/".$module_file);
	$xtpl->assign('LANG',$lang_module);
	$xtpl->assign('GLANG',$lang_global);
	$xtpl->assign( 'FORM_ACTION', NV_BASE_ADMINURL . 'index.php' );
	$xtpl->assign('NV_BASE_SITEURL',NV_BASE_SITEURL);
	$xtpl->assign( 'MODULE_NAME', $module_name );
	$xtpl->assign('NV_OP_VARIABLE',NV_OP_VARIABLE);
	$xtpl->assign('MODULE_URL',NV_BASE_ADMINURL."index.php?".NV_NAME_VARIABLE."=".$module_name."&".NV_OP_VARIABLE);
	$xtpl->assign('OP',$op);
	foreach( $methods as $m )
	{
		$xtpl->assign( 'METHODS', $m );
		$xtpl->parse( 'main.method' );
	}
	$_bg = ( defined( 'NV_CONFIG_DIR' ) ) ? 3 : 1;
	for( $i = $_bg; $i >= 0; $i-- )
	{
		$m = array(
			'key' => $i,
			'selected' => ( $i == $usactive ) ? 'selected="selected"' : '',
			'value' => $lang_module['usactive_' . $i]
		);
		$xtpl->assign( 'USACTIVE', $m );
		$xtpl->parse( 'main.usactive' );
	}
	$xtpl->parse('main');
	$contents=$xtpl->text('main');
	include (NV_ROOTDIR . "/includes/header.php");
	echo nv_admin_theme($contents);
	include (NV_ROOTDIR . "/includes/footer.php");

}


