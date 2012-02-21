<?php
require_once( $_SERVER['DOCUMENT_ROOT'] . "/engine/start.php");
//gatekeeper();

/* $user_guid = get_input('user_guid');
$user = get_entity($guid);
if( ! ( $user && $user instanceof ElggUser ) ) {
	$user = get_loggedin_user();
}

$filters = get_input('filters');
 */
$filters = river_get_item_types();
$user_filters = array('group' => array($filters[0], $filters[1]), 'blog' => array($filters[2], $filters[3]));

$moi = get_user_by_username('bouland');
set_user_filter_setting($moi, $user_filters);

$get = $moi->river_filters;
exit;
if ($filters && is_array($filters)) {
	
	if (set_user_filter_setting($user, $user_filters)){
	
		system_message(elgg_echo('custom_index_inria:filter:save:success'));
	
	}else{
		
		register_error(elgg_echo('custom_index_inria:filter:save:failed'));
	}
}else{
	register_error(elgg_echo('custom_index_inria:filter:form:filtersnotvalid'));	
}

forward($_SERVER['HTTP_REFERER']);