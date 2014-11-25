<?php
/**
 * @Project NUKEVIET 3.4
 * @Author Nguyễn Thanh Hoàng  (hoang.nguyen@webvang.vn)
 * @Copyright (C) 2014 webvang.vn All rights reserved
 * @Createdate 08/10/2014
 */
 
if (! defined ( 'NV_IS_FILE_ADMIN' ))
	die ( 'Stop!!!' );
$page_title = $lang_module ['config_title'];
/*
$sql = 'SELECT max(group_id) gmax FROM ' . NV_GROUPS_GLOBALTABLE . '';
$result = $db->query( $sql );
while( $row = $result->fetch() )
{
	$qlhs_config['gmax'] = $row['gmax'];
}
$post = array();
if($qlhs_config['gmax']<=9){
	
	$weight = $db->query( "SELECT max(weight) FROM " . NV_GROUPS_GLOBALTABLE . " WHERE idsite=" . $global_config['idsite'] )->fetchColumn();
	$weight = intval( $weight ) + 1;

	$_sql = "INSERT INTO " . NV_GROUPS_GLOBALTABLE . "
		(title, content, add_time, exp_time, publics, weight, act, idsite, numbers, siteus)
		VALUES ( :title, :content, 1412787599, 0, 0, " . $weight . ", 1, " . $global_config['idsite'] . ", 0, 0)";

	$data_insert = array();
	$data_insert['title'] = 'Giáo viên';
	$data_insert['content'] = '';
	$ok = $post['gvid'] = $db->insert_id( $_sql, 'group_id', $data_insert );
	$weight = intval( $weight ) + 1;
	$_sql = "INSERT INTO " . NV_GROUPS_GLOBALTABLE . "
		(title, content, add_time, exp_time, publics, weight, act, idsite, numbers, siteus)
		VALUES ( :title, :content, 1412787599, 0, 0, " . $weight . ", 1, " . $global_config['idsite'] . ", 0, 0)";

	$data_insert = array();
	$data_insert['title'] = 'Học Sinh';
	$data_insert['content'] = '';
	$ok = $post['hsid'] = $db->insert_id( $_sql, 'group_id', $data_insert );
	
}
*/

	if ($nv_Request->isset_request ( 'do', 'post' )) {
		$contents .= "<div class=\"quote\" style=\"width:780px;\">\n";
		$contents .= "<blockquote class=\"error\"><span>" . $lang_module['add_cf_success'] . "</span></blockquote>\n";
		$contents .= "</div>\n";
		$contents .= "<div class=\"clear\"></div>\n";
	}else{
	$error='';

	$xtpl=new XTemplate("config.tpl",NV_ROOTDIR."/themes/".$global_config['module_theme']."/modules/".$module_file);
	$xtpl->assign('LANG',$lang_module);
	$xtpl->assign('GLANG',$lang_global);
	$xtpl->assign('NV_BASE_SITEURL',NV_BASE_SITEURL);
	if($error){
	$xtpl->assign('ERROR',$error);
	$xtpl->parse('main.error');
	}
	$xtpl->parse('main');
	$contents=$xtpl->text('main');
	}

include (NV_ROOTDIR . "/includes/header.php");
echo nv_admin_theme ( $contents );
include (NV_ROOTDIR . "/includes/footer.php");

